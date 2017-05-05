<?php
namespace IchHabRecht\ContentDefender\Hooks;

use IchHabRecht\ContentDefender\BackendLayout\BackendLayoutConfiguration;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

class CTypeDataHandlerHook
{
    /**
     * @param DataHandler $dataHandler
     * @return void
     */
    public function processDatamap_beforeStart(DataHandler $dataHandler)
    {
        $datamap = $dataHandler->datamap;
        if (empty($datamap['tt_content'])) {
            return;
        }

        foreach ($datamap['tt_content'] as $id => $incomingFieldArray) {
            if (MathUtility::canBeInterpretedAsInteger($id)) {
                $incomingFieldArray = array_merge(BackendUtility::getRecord('tt_content', $id), $incomingFieldArray);
            }

            $pageId = (int)$incomingFieldArray['pid'];
            $backendLayoutConfiguration = BackendLayoutConfiguration::createFromPageId($pageId);
            if (null === $backendLayoutConfiguration) {
                return;
            }

            $colPos = (int)$incomingFieldArray['colPos'];
            $columnConfiguration = $backendLayoutConfiguration->getConfigurationByColPos($colPos);
            if (empty($columnConfiguration) || (empty($columnConfiguration['allowed']) && empty($columnConfiguration['disallowed']))) {
                return;
            }

            if (!empty($columnConfiguration['allowed'])) {
                $cTypes = GeneralUtility::trimExplode(',', $columnConfiguration['allowed']);
                $allowed = in_array($incomingFieldArray['CType'], $cTypes, true);
            } else {
                $cTypes = GeneralUtility::trimExplode(',', $columnConfiguration['disallowed']);
                $allowed = !in_array($incomingFieldArray['CType'], $cTypes, true);
            }

            if (!$allowed) {
                unset($dataHandler->datamap['tt_content'][$id]);
                $dataHandler->log(
                    'tt_content',
                    $id,
                    1,
                    $pageId,
                    1,
                    'The record "%s" with CType "%s" couldn\'t be saved due to disallowed CType value.',
                    23,
                    [
                        $incomingFieldArray[$GLOBALS['TCA']['tt_content']['ctrl']['label']],
                        $incomingFieldArray['CType'],
                    ]
                );
            }
        }
    }

    /**
     * @param DataHandler $dataHandler
     * @return void
     */
    public function processCmdmap_beforeStart(DataHandler $dataHandler)
    {
        $cmdmap = $dataHandler->cmdmap;
        if (empty($cmdmap['tt_content'])) {
            return;
        }

        $datamap = $dataHandler->datamap;
        foreach ($cmdmap['tt_content'] as $id => $incomingFieldArray) {
            if (!isset($datamap['tt_content'][$id])) {
                unset($dataHandler->cmdmap['tt_content'][$id]);
            }
        }

        if (count($cmdmap['tt_content']) !== count($dataHandler->cmdmap['tt_content'])) {
            $dataHandler->printLogErrorMessages('');
        }
    }
}
