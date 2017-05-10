<?php
namespace IchHabRecht\ContentDefender\Hooks;

use IchHabRecht\ContentDefender\BackendLayout\BackendLayoutConfiguration;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\DataHandling\DataHandler;
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
            if ($pageId < 0) {
                $currentRecord = BackendUtility::getRecord('tt_content', abs($pageId), 'pid');
                $pageId = (int)$currentRecord['pid'];
            }
            $colPos = (int)$incomingFieldArray['colPos'];
            $cType = $incomingFieldArray['CType'];

            $backendLayoutConfiguration = BackendLayoutConfiguration::createFromPageId($pageId);
            $columnConfiguration = $backendLayoutConfiguration->getConfigurationByColPos($colPos);
            $allowed = $this->isAllowedCType($columnConfiguration, $cType);

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
}
