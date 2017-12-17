<?php
namespace IchHabRecht\ContentDefender\Form\FormDataProvider;

use IchHabRecht\ContentDefender\BackendLayout\BackendLayoutConfiguration;
use IchHabRecht\ContentDefender\Form\Exception\AccessDeniedColPosException;
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TcaColPosItems implements FormDataProviderInterface
{
    /**
     * @var array
     */
    protected static $colPosCount = [];

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

        foreach ($result['processedTca']['columns']['colPos']['config']['items'] as $key => $item) {
            $colPos = (int)$item[1];
            $columnConfiguration = $backendLayoutConfiguration->getConfigurationByColPos($colPos);
            if (empty($columnConfiguration)) {
                continue;
            }

            $allowedConfiguration = $columnConfiguration['allowed.'] ?? [];
            foreach ($allowedConfiguration as $field => $value) {
                if (!isset($result['databaseRow'][$field])) {
                    continue;
                }

                $allowedValues = GeneralUtility::trimExplode(',', $value);
                if ($this->fieldContainsDisallowedValues($result['databaseRow'][$field], $allowedValues)) {
                    unset($result['processedTca']['columns']['colPos']['config']['items'][$key]);
                }
            }

            $disallowedConfiguration = $columnConfiguration['disallowed.'] ?? [];
            foreach ($disallowedConfiguration as $field => $value) {
                if (!isset($result['databaseRow'][$field])) {
                    continue;
                }

                $disallowedValues = GeneralUtility::trimExplode(',', $value);
                if ($this->fieldContainsDisallowedValues($result['databaseRow'][$field], $disallowedValues, false)) {
                    unset($result['processedTca']['columns']['colPos']['config']['items'][$key]);
                }
            }

            if (!empty($columnConfiguration['maxitems'])
                && $columnConfiguration['maxitems'] <= $this->getCurrentColPosCount($pageId, $colPos, $result['databaseRow'])
            ) {
                if ($colPos === (int)$result['databaseRow']['colPos'][0]) {
                    throw  new AccessDeniedColPosException(
                        'Maximum number of allowed content elements (' . $columnConfiguration['maxitems'] . ') reached.',
                        1494605357
                    );
                }
                unset($result['processedTca']['columns']['colPos']['config']['items'][$key]);
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

    /**
     * @param int $pageId
     * @param int $colPos
     * @param array $record
     * @return int
     */
    protected function getCurrentColPosCount($pageId, $colPos, array $record)
    {
        $languageField = $GLOBALS['TCA']['tt_content']['ctrl']['languageField'];
        $language = $record[$languageField][0];

        $identifier = $pageId . '/' . $language . '/' . $colPos;

        if (isset(self::$colPosCount[$identifier])) {
            return self::$colPosCount[$identifier];
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);
        $count = $queryBuilder->count('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'pid',
                    $queryBuilder->createNamedParameter($pageId, \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'colPos',
                    $queryBuilder->createNamedParameter($colPos, \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    $languageField,
                    $queryBuilder->createNamedParameter($language, \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->neq(
                    'uid',
                    $queryBuilder->createNamedParameter($record['uid'], \PDO::PARAM_INT)
                )
            )
            ->execute()
            ->fetchColumn();

        return self::$colPosCount[$identifier] = $count;
    }
}
