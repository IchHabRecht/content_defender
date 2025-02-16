<?php

declare(strict_types=1);

namespace IchHabRecht\ContentDefender\Hooks;

/*
 * This file is part of the TYPO3 extension content_defender.
 *
 * (c) Nicole Hummel <nicole-typo3@nimut.dev>
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use IchHabRecht\ContentDefender\BackendLayout\BackendLayoutConfiguration;
use TYPO3\CMS\Backend\Controller\Event\ModifyNewContentElementWizardItemsEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class WizardItemsHook
{
    public function modifyWizardItems(ModifyNewContentElementWizardItemsEvent $event)
    {
        $pageInfo = $event->getPageInfo();
        $pageId = (int)$pageInfo['uid'];
        $backendLayoutConfiguration = BackendLayoutConfiguration::createFromPageId($pageId);

        $colPos = (int)$event->getColPos();
        $columnConfiguration = $backendLayoutConfiguration->getConfigurationByColPos($colPos);
        if (empty($columnConfiguration) || (empty($columnConfiguration['allowed.']) && empty($columnConfiguration['disallowed.']))) {
            return;
        }

        $wizardItems = $event->getWizardItems();

        $allowedConfiguration = $columnConfiguration['allowed.'] ?? [];
        foreach ($allowedConfiguration as $field => $value) {
            $allowedValues = GeneralUtility::trimExplode(',', $value);
            $wizardItems = $this->removeDisallowedValues($wizardItems, $field, $allowedValues);
        }

        $disallowedConfiguration = $columnConfiguration['disallowed.'] ?? [];
        foreach ($disallowedConfiguration as $field => $value) {
            $disAllowedValues = GeneralUtility::trimExplode(',', $value);
            $wizardItems = $this->removeDisallowedValues($wizardItems, $field, $disAllowedValues, false);
        }

        $availableWizardItems = [];
        foreach ($wizardItems as $key => $_) {
            $keyParts = explode('_', $key, 2);
            if (count($keyParts) === 1) {
                continue;
            }
            $availableWizardItems[$keyParts[0]] = $key;
            $availableWizardItems[$key] = $key;
        }

        $event->setWizardItems(array_intersect_key($wizardItems, $availableWizardItems));
    }

    /**
     * @param array $wizardItems
     * @param string $field
     * @param array $values
     * @param bool $allowed
     * @return array
     */
    protected function removeDisallowedValues(array $wizardItems, $field, array $values, $allowed = true)
    {
        foreach ($wizardItems as $key => $configuration) {
            $keyParts = explode('_', $key, 2);
            if (count($keyParts) === 1 || (!isset($configuration['defaultValues'][$field]) && !isset($configuration['tt_content_defValues'][$field]))) {
                continue;
            }

            $defaultValue = $configuration['defaultValues'][$field] ?? $configuration['tt_content_defValues'][$field] ?? '';

            if (($allowed && !in_array($defaultValue, $values))
                || (!$allowed && in_array($defaultValue, $values))
            ) {
                unset($wizardItems[$key]);
                continue;
            }
        }

        return $wizardItems;
    }
}
