<?php
namespace IchHabRecht\ContentDefender\Hooks;

use IchHabRecht\ContentDefender\BackendLayout\BackendLayoutConfiguration;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

class DatamapDataHandlerHook extends AbstractDataHandlerHook
{
    /**
     * @param DataHandler $dataHandler
     * @return void
     */
    public function processDatamap_beforeStart(DataHandler $dataHandler)
    {
        $datamap = $dataHandler->datamap;
        $dataHandler->processedRecords = [];
        if (empty($datamap['tt_content'])) {
            return;
        }

        foreach ($datamap['tt_content'] as $id => $incomingFieldArray) {
            if (MathUtility::canBeInterpretedAsInteger($id)) {
                $incomingFieldArray = array_merge(BackendUtility::getRecord('tt_content', $id), $incomingFieldArray);
            }

            $pageId = (int)$incomingFieldArray['pid'];
            if ($pageId < 0) {
                $previousRecord = BackendUtility::getRecord('tt_content', abs($pageId), 'pid');
                $pageId = (int)$previousRecord['pid'];
                $incomingFieldArray['pid'] = $pageId;
            }
            $colPos = (int)$incomingFieldArray['colPos'];
            $dataHandler->processedRecords[] = $incomingFieldArray['uid'];

            $backendLayoutConfiguration = BackendLayoutConfiguration::createFromPageId($pageId);
            $columnConfiguration = $backendLayoutConfiguration->getConfigurationByColPos($colPos);

            if (!$this->isRecordAllowedByRestriction($columnConfiguration, $incomingFieldArray)) {
                unset($dataHandler->datamap['tt_content'][$id]);
                $dataHandler->log(
                    'tt_content',
                    $id,
                    1,
                    $pageId,
                    1,
                    'The record "%s" couldn\'t be saved due to disallowed value(s).',
                    23,
                    [
                        $incomingFieldArray[$GLOBALS['TCA']['tt_content']['ctrl']['label']],
                    ]
                );
            }

            if (!$this->isRecordAllowedByItemsCount($columnConfiguration, $incomingFieldArray)) {
                // DataHandler copies a record by first add a new content element (in the old colPos) and then adjust
                // the colPos information to the target colPos. This means we have to allow this element to be added
                // even if the maxitems is reached already. The copy command was checked in CmdmapDataHandlerHook.
                if (empty($dataHandler->cmdmap)) {
                    $clipboard = GeneralUtility::_GP('CB');
                    if (!empty($clipboard['paste'])) {
                        continue;
                    }
                }

                unset($dataHandler->datamap['tt_content'][$id]);
                $dataHandler->log(
                    'tt_content',
                    $id,
                    1,
                    $pageId,
                    1,
                    'The record "%s" couldn\'t be saved due to reached maxitems configuration of %d.',
                    27,
                    [
                        $incomingFieldArray[$GLOBALS['TCA']['tt_content']['ctrl']['label']],
                        $columnConfiguration['maxitems'],
                    ]
                );
            }
        }
    }
}
