<?php
namespace IchHabRecht\ContentDefender\Hooks;

use IchHabRecht\ContentDefender\BackendLayout\BackendLayoutConfiguration;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CmdmapDataHandlerHook extends AbstractDataHandlerHook
{
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
                        if (is_array($value)
                            && !empty($value['action'])
                            && 'paste' === $value['action']
                            && isset($value['update']['colPos'])
                        ) {
                            $command = 'paste';
                            $pageId = (int)$value['target'];
                            $colPos = (int)$value['update']['colPos'];
                        } else {
                            $targetRecord = BackendUtility::getRecord('tt_content', abs($value));
                            $pageId = (int)$targetRecord['pid'];
                            $colPos = (int)$targetRecord['colPos'];
                        }

                        $backendLayoutConfiguration = BackendLayoutConfiguration::createFromPageId($pageId);
                        $columnConfiguration = $backendLayoutConfiguration->getConfigurationByColPos($colPos);
                        $allowed = $this->isAllowedRecord($columnConfiguration, $currentRecord);

                        if (!$allowed) {
                            unset($dataHandler->cmdmap['tt_content'][$id]);
                            $dataHandler->log(
                                'tt_content',
                                $id,
                                1,
                                $pageId,
                                1,
                                'The command "%s" for record "%s" couldn\'t be executed due to disallowed value(s).',
                                24,
                                [
                                    $command,
                                    $currentRecord[$GLOBALS['TCA']['tt_content']['ctrl']['label']],
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
