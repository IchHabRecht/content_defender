<?php

declare(strict_types=1);

namespace IchHabRecht\ContentDefender\Form\FormDataProvider;

/*
 * This file is part of the TYPO3 extension content_defender.
 *
 * (c) Nicole Cordes <typo3@cordes.co>
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use IchHabRecht\ContentDefender\BackendLayout\BackendLayoutConfiguration;
use IchHabRecht\ContentDefender\Form\Exception\AccessDeniedColPosException;
use IchHabRecht\ContentDefender\Repository\ContentRepository;
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Core\Localization\LanguageService;
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
        $originalRecordColPos = $record['colPos'][0];

        foreach ($result['processedTca']['columns']['colPos']['config']['items'] as $key => $item) {
            $colPos = (int)$item[1];
            $columnConfiguration = $backendLayoutConfiguration->getConfigurationByColPos($colPos, $record['uid']);
            if (empty($columnConfiguration)) {
                continue;
            }

            $record['colPos'] = $colPos;

            $allowedConfiguration = array_intersect_key($columnConfiguration['allowed.'] ?? [], $result['processedTca']['columns']);
            foreach ($allowedConfiguration as $field => $value) {
                if (!isset($record[$field])) {
                    continue;
                }

                $allowedValues = GeneralUtility::trimExplode(',', $value);
                if ($this->fieldContainsDisallowedValues($record[$field], $allowedValues)) {
                    $result['processedTca']['columns']['colPos']['config']['items'] = $this->unsetIfNotCurrent(
                        $result['processedTca']['columns']['colPos']['config']['items'],
                        $key,
                        $originalRecordColPos
                    );
                }
            }

            $disallowedConfiguration = array_intersect_key($columnConfiguration['disallowed.'] ?? [], $result['processedTca']['columns']);
            foreach ($disallowedConfiguration as $field => $value) {
                if (!isset($record[$field])) {
                    continue;
                }

                $disallowedValues = GeneralUtility::trimExplode(',', $value);
                if ($this->fieldContainsDisallowedValues($record[$field], $disallowedValues, false)) {
                    $result['processedTca']['columns']['colPos']['config']['items'] = $this->unsetIfNotCurrent(
                        $result['processedTca']['columns']['colPos']['config']['items'],
                        $key,
                        $originalRecordColPos
                    );
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
                    $result['processedTca']['columns']['colPos']['config']['items'] = $this->unsetIfNotCurrent(
                        $result['processedTca']['columns']['colPos']['config']['items'],
                        $key,
                        $originalRecordColPos
                    );
                }
            }
        }

        return $result;
    }

    /**
     * Unset array item $items[$key] if colPos doesn't match current records colPos, otherwise add 'invalid' label
     *
     * @param $items
     * @param $key
     * @param $recordColPos
     * @return array
     */
    protected function unsetIfNotCurrent($items, $key, $recordColPos)
    {
        if (key_exists(1, $items[$key]) && $recordColPos === $items[$key][1])
        {
            $items[$key][0] = sprintf(
                $this->getLanguageService()->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.noMatchingValue'),
                $items[$key][0]
            );
        } else {
            unset($items[$key]);
        }
        return $items;
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

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
