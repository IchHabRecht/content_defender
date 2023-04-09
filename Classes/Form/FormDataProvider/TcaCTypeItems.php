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
            $allowedValues = GeneralUtility::trimExplode(',', $value);
            $result['processedTca']['columns'][$field]['config']['items'] = array_filter(
                $result['processedTca']['columns'][$field]['config']['items'],
                function ($item) use ($allowedValues) {
                    return in_array($item['value'] ?? $item[1], $allowedValues);
                }
            );
        }

        $disallowedConfiguration = array_intersect_key($columnConfiguration['disallowed.'] ?? [], $result['processedTca']['columns']);
        foreach ($disallowedConfiguration as $field => $value) {
            $disallowedValues = GeneralUtility::trimExplode(',', $value);
            $result['processedTca']['columns'][$field]['config']['items'] = array_filter(
                $result['processedTca']['columns'][$field]['config']['items'],
                function ($item) use ($disallowedValues) {
                    return !in_array($item['value'] ?? $item[1], $disallowedValues);
                }
            );
        }

        return $result;
    }
}
