<?php
namespace IchHabRecht\ContentDefender\Hooks;

use IchHabRecht\ContentDefender\BackendLayout\BackendLayoutConfiguration;
use TYPO3\CMS\Backend\Controller\ContentElement\NewContentElementController;
use TYPO3\CMS\Backend\Wizard\NewContentElementWizardHookInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class WizardItemsHook implements NewContentElementWizardHookInterface
{
    /**
     * @param array $wizardItems
     * @param NewContentElementController $parentObject
     * @return void
     */
    public function manipulateWizardItems(&$wizardItems, &$parentObject)
    {
        $pageId = $parentObject->id;
        $backendLayoutConfiguration = BackendLayoutConfiguration::createFromPageId($pageId);

        $colPos = (int)$parentObject->colPos;
        $columnConfiguration = $backendLayoutConfiguration->getConfigurationByColPos($colPos);
        if (empty($columnConfiguration) || (empty($columnConfiguration['allowed.']) && empty($columnConfiguration['disallowed.']))) {
            return;
        }

        if (!empty($columnConfiguration['allowed.'])) {
            foreach ($columnConfiguration['allowed.'] as $field => $value) {
                $allowedValues = GeneralUtility::trimExplode(',', $value);
                foreach ($wizardItems as $key => $configuration) {
                    $keyParts = explode('_', $key, 2);
                    if (count($keyParts) === 1 || !isset($configuration['tt_content_defValues'][$field])) {
                        continue;
                    }

                    if (!in_array($configuration['tt_content_defValues'][$field], $allowedValues)
                    ) {
                        unset($wizardItems[$key]);
                        continue;
                    }
                }
            }
        }
        if (!empty($columnConfiguration['disallowed.'])) {
            foreach ($columnConfiguration['disallowed.'] as $field => $value) {
                $disAllowedValues = GeneralUtility::trimExplode(',', $value);
                foreach ($wizardItems as $key => $configuration) {
                    $keyParts = explode('_', $key, 2);
                    if (count($keyParts) === 1 || !isset($configuration['tt_content_defValues'][$field])) {
                        continue;
                    }
                    if (in_array($configuration['tt_content_defValues'][$field], $disAllowedValues)
                    ) {
                        unset($wizardItems[$key]);
                        continue;
                    }
                }
            }
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
}
