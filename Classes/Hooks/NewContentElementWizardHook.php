<?php

declare(strict_types=1);

namespace IchHabRecht\ContentDefender\Hooks;

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
use TYPO3\CMS\Backend\Controller\ContentElement\NewContentElementController;
use TYPO3\CMS\Backend\Wizard\NewContentElementWizardHookInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class NewContentElementWizardHook extends WizardItemsHook implements NewContentElementWizardHookInterface
{
    /**
     * @param array $wizardItems
     * @param NewContentElementController $parentObject
     * @return void
     */
    public function manipulateWizardItems(&$wizardItems, &$parentObject)
    {
        $pageId = (int)$parentObject->getPageInfo()['uid'];
        $backendLayoutConfiguration = BackendLayoutConfiguration::createFromPageId($pageId);

        $colPos = (int)$parentObject->getColPos();
        $columnConfiguration = $backendLayoutConfiguration->getConfigurationByColPos($colPos);
        if (empty($columnConfiguration) || (empty($columnConfiguration['allowed.']) && empty($columnConfiguration['disallowed.']))) {
            return;
        }

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

        $wizardItems = array_intersect_key($wizardItems, $availableWizardItems);
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
            if (count($keyParts) === 1 || !isset($configuration['tt_content_defValues'][$field])) {
                continue;
            }

            if (($allowed && !in_array($configuration['tt_content_defValues'][$field], $values))
                || (!$allowed && in_array($configuration['tt_content_defValues'][$field], $values))
            ) {
                unset($wizardItems[$key]);
                continue;
            }
        }

        return $wizardItems;
    }
}
