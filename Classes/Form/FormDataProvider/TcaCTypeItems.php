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
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TcaCTypeItems implements FormDataProviderInterface
{
    /**
     * @param array $result
     * @return array
     */
    public function addData(array $result)
    {
        if ('tt_content' !== $result['tableName']
            || !empty($result['isInlineChild'])
        ) {
            return $result;
        }

        $pageId = !empty($result['effectivePid']) ? (int)$result['effectivePid'] : (int)$result['databaseRow']['pid'];
        $backendLayoutConfiguration = BackendLayoutConfiguration::createFromPageId($pageId);

        if (is_array($result['databaseRow']['colPos'] ?? [])) {
            $colPos = (int)($result['databaseRow']['colPos'][0] ?? $result['processedTca']['columns']['colPos']['config']['default'] ?? 0);
        } else {
            $colPos = (int)($result['databaseRow']['colPos'] ?? $result['processedTca']['columns']['colPos']['config']['default'] ?? 0);
        }
        $columnConfiguration = $backendLayoutConfiguration->getConfigurationByColPos($colPos, $result['databaseRow']['uid']);
        if (empty($columnConfiguration) || (empty($columnConfiguration['allowed.']) && empty($columnConfiguration['disallowed.']))) {
            return $result;
        }

        $allowedConfiguration = array_intersect_key($columnConfiguration['allowed.'] ?? [], $result['processedTca']['columns']);
        foreach ($allowedConfiguration as $field => $value) {
            $currentRecordValue = is_array($result['databaseRow'][$field]) ? $result['databaseRow'][$field][0] : $result['databaseRow'][$field];
            $allowedValues = GeneralUtility::trimExplode(',', $value);
            $result['processedTca']['columns'][$field]['config']['items'] = $this->filterAllowedItems(
                $result['processedTca']['columns'][$field]['config']['items'],
                $allowedValues,
                false,
                $currentRecordValue
            );
        }

        $disallowedConfiguration = array_intersect_key($columnConfiguration['disallowed.'] ?? [], $result['processedTca']['columns']);
        foreach ($disallowedConfiguration as $field => $value) {
            $currentRecordValue = is_array($result['databaseRow'][$field]) ? $result['databaseRow'][$field][0] : $result['databaseRow'][$field];
            $disallowedValues = GeneralUtility::trimExplode(',', $value);
            $result['processedTca']['columns'][$field]['config']['items'] = $this->filterAllowedItems(
                $result['processedTca']['columns'][$field]['config']['items'],
                $disallowedValues,
                true,
                $currentRecordValue
            );
        }

        return $result;
    }

    /**
     * Remove items not in filter list, unless it matches the current record value, then label it as 'invalid value'
     * Remove items in filter list if $disallow=true
     *
     * @param $items
     * @param $filterItems
     * @param $disallow
     * @param $currentRecordValue
     * @return array
     */
    protected function filterAllowedItems($items, $filterItems, $disallow, $currentRecordValue)
    {
        foreach ($items as $key => $item) {
            if ($disallow ? in_array($item[1], $filterItems) : !in_array($item[1], $filterItems)) {
                if ($item[1] !== $currentRecordValue) {
                    unset($items[$key]);
                } else {
                    $items[$key][0] = sprintf(
                        $this->getLanguageService()->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.noMatchingValue'),
                        $item[0]
                    );
                }
            }
        }

        return $items;
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
