<?php
declare(strict_types=1);
namespace IchHabRecht\ContentDefender\Form\FormDataProvider;

use IchHabRecht\ContentDefender\BackendLayout\BackendLayoutConfiguration;
use IchHabRecht\ContentDefender\Form\Exception\AccessDeniedColPosException;
use IchHabRecht\ContentDefender\Repository\ContentRepository;
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TcaColPosItems implements FormDataProviderInterface
{
    /**
     * @var ContentRepository
     */
    protected $contentRepository;

    /**
     * @param ContentRepository $contentRepository
     */
    public function __construct(ContentRepository $contentRepository = null)
    {
        $this->contentRepository = $contentRepository ?? GeneralUtility::makeInstance(ContentRepository::class);
    }

    /**
     * @param array $result
     * @throws AccessDeniedColPosException
     * @return array
     */
    public function addData(array $result)
    {
        if ('tt_content' !== $result['tableName']
            || empty($result['processedTca']['columns']['colPos']['config']['items'])
            || !empty($result['isInlineChild'])
        ) {
            return $result;
        }

        $pageId = !empty($result['effectivePid']) ? (int)$result['effectivePid'] : (int)$result['databaseRow']['pid'];
        $backendLayoutConfiguration = BackendLayoutConfiguration::createFromPageId($pageId);

        $record = $result['databaseRow'];
        $record['pid'] = $pageId;

        foreach ($result['processedTca']['columns']['colPos']['config']['items'] as $key => $item) {
            $colPos = (int)$item[1];
            $columnConfiguration = $backendLayoutConfiguration->getConfigurationByColPos($colPos);
            if (empty($columnConfiguration)) {
                continue;
            }

            $record['colPos'] = $colPos;

            $allowedConfiguration = $columnConfiguration['allowed.'] ?? [];
            foreach ($allowedConfiguration as $field => $value) {
                if (!isset($record[$field])) {
                    continue;
                }

                $allowedValues = GeneralUtility::trimExplode(',', $value);
                if ($this->fieldContainsDisallowedValues($record[$field], $allowedValues)) {
                    unset($result['processedTca']['columns']['colPos']['config']['items'][$key]);
                }
            }

            $disallowedConfiguration = $columnConfiguration['disallowed.'] ?? [];
            foreach ($disallowedConfiguration as $field => $value) {
                if (!isset($record[$field])) {
                    continue;
                }

                $disallowedValues = GeneralUtility::trimExplode(',', $value);
                if ($this->fieldContainsDisallowedValues($record[$field], $disallowedValues, false)) {
                    unset($result['processedTca']['columns']['colPos']['config']['items'][$key]);
                }
            }

            if (!empty($columnConfiguration['maxitems'])
                && $columnConfiguration['maxitems'] <= $this->contentRepository->countColPosByRecord($record)
            ) {
                $isCurrentColPos = $colPos === (int)$result['databaseRow']['colPos'][0];
                if ($isCurrentColPos && !$this->contentRepository->isRecordInColPos($record)) {
                    throw  new AccessDeniedColPosException(
                        'Maximum number of allowed content elements (' . $columnConfiguration['maxitems'] . ') reached.',
                        1494605357
                    );
                } elseif (!$isCurrentColPos) {
                    unset($result['processedTca']['columns']['colPos']['config']['items'][$key]);
                }
            }
        }

        return $result;
    }

    /**
     * @param string|array $fieldValue
     * @param array $values
     * @param bool $allowed
     * @return bool
     */
    protected function fieldContainsDisallowedValues($fieldValue, array $values, $allowed = true)
    {
        foreach ((array)$fieldValue as $item) {
            if (($allowed && !in_array($item, $values, true))
                || (!$allowed && in_array($item, $values, true))
            ) {
                return true;
            }
        }

        return false;
    }
}
