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
        if (empty($columnConfiguration) || (empty($columnConfiguration['allowed']) && empty($columnConfiguration['disallowed']))) {
            return;
        }

        $headersUsed = [];
        if (!empty($columnConfiguration['allowed'])) {
            $cTypes = GeneralUtility::trimExplode(',', $columnConfiguration['allowed']);
            foreach ($wizardItems as $key => $configuration) {
                $keyParts = explode('_', $key, 2);
                if (count($keyParts) === 1) {
                    continue;
                }

                if (empty($configuration['tt_content_defValues']['CType'])
                    || !in_array($configuration['tt_content_defValues']['CType'], $cTypes, true)
                ) {
                    unset($wizardItems[$key]);
                    continue;
                }

                $headersUsed[$keyParts[0]] = $key;
            }
        } else {
            $cTypes = GeneralUtility::trimExplode(',', $columnConfiguration['disallowed']);
            foreach ($wizardItems as $key => $configuration) {
                $keyParts = explode('_', $key, 2);
                if (count($keyParts) === 1) {
                    continue;
                }

                if (!empty($configuration['tt_content_defValues']['CType'])
                    && in_array($configuration['tt_content_defValues']['CType'], $cTypes, true)
                ) {
                    unset($wizardItems[$key]);
                    continue;
                }

                $headersUsed[$keyParts[0]] = $key;
            }
        }

        foreach ($wizardItems as $key => $_) {
            $keyParts = explode('_', $key, 2);
            if (count($keyParts) === 1 && !isset($headersUsed[$keyParts[0]])) {
                unset($wizardItems[$key]);
            }
        }
    }
}
