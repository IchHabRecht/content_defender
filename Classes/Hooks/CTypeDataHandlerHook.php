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
            if (!isset($incomingFieldArray['colPos']) || !isset($incomingFieldArray['CType']) || !isset($incomingFieldArray['pid'])) {
                if (!MathUtility::canBeInterpretedAsInteger($id)) {
                    continue;
                }
                $incomingFieldArray = array_merge(BackendUtility::getRecord('tt_content', $id), $incomingFieldArray);
            }

            $pageId = (int)$incomingFieldArray['pid'];
            $backendLayoutConfiguration = BackendLayoutConfiguration::createFromPageId($pageId);

            $colPos = (int)$incomingFieldArray['colPos'];
            $cType = $incomingFieldArray['CType'];
            $allowed = $backendLayoutConfiguration->isAllowedCTypeInColPos($cType, $colPos);

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
                        $cType,
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

        foreach ($cmdmap['tt_content'] as $id => $incomingFieldArray) {
            foreach ($incomingFieldArray as $command => $value) {
                switch ($command) {
                    case 'move':
                        // New colPos is passed as datamap array and already processed in processDatamap_beforeStart
                        if (!isset($dataHandler->datamap['tt_content'][$id])) {
                            unset($dataHandler->cmdmap['tt_content'][$id]);
                        }
                        break;
                    case 'copy':
                        $currentRecord = BackendUtility::getRecord('tt_content', $id);
                        $cType = $currentRecord['CType'];
                        if (is_array($value)
                            && !empty($value['action'])
                            && 'paste' === $value['action']
                            && isset($value['update']['colPos'])
                        ) {
                            $pageId = (int)$value['target'];
                            $colPos = (int)$value['update']['colPos'];
                            $backendLayoutConfiguration = BackendLayoutConfiguration::createFromPageId($pageId);
                        } else {
                            $targetRecord = BackendUtility::getRecord('tt_content', abs($value));
                            $pageId = (int)$targetRecord['pid'];
                            $colPos = (int)$targetRecord['colPos'];
                            $backendLayoutConfiguration = BackendLayoutConfiguration::createFromPageId($pageId);
                        }
                        $allowed = $backendLayoutConfiguration->isAllowedCTypeInColPos($cType, $colPos);

                        if (!$allowed) {
                            unset($dataHandler->cmdmap['tt_content'][$id]);
                            $dataHandler->log(
                                'tt_content',
                                $id,
                                1,
                                $pageId,
                                1,
                                'The command "%s" for record "%s" with CType "%s" couldn\'t be executed due to disallowed CType value.',
                                23,
                                [
                                    $command,
                                    $currentRecord[$GLOBALS['TCA']['tt_content']['ctrl']['label']],
                                    $cType,
                                ]
                            );
                        }
                        break;
                }
            }
        }

        if (count($cmdmap['tt_content']) !== count($dataHandler->cmdmap['tt_content'])
            && empty(GeneralUtility::_GP('prErr'))
        ) {
            $dataHandler->printLogErrorMessages('');
        }
    }
}
