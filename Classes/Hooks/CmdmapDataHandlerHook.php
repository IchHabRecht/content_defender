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
                $currentRecord = BackendUtility::getRecord('tt_content', $id);
                switch ($command) {
                    case 'move':
                        // New colPos is passed as datamap array and already processed in processDatamap_beforeStart
                        if (isset($dataHandler->datamap['tt_content'][$id])) {
                            $data = $dataHandler->datamap;
                        } else {
                            $data = GeneralUtility::_GP('data');
                        }
                        if (isset($data['tt_content'][$id])) {
                            if (isset($data['tt_content'][$id]['colPos'])
                                && (int)$currentRecord['colPos'] !== (int)$data['tt_content'][$id]['colPos']
                            ) {
                                unset($dataHandler->cmdmap['tt_content'][$id]);
                            }
                            break;
                        }
                        // no break
                    case 'copy':
                        if (is_array($value)
                            && !empty($value['action'])
                            && 'paste' === $value['action']
                            && isset($value['update']['colPos'])
                        ) {
                            $command = 'paste';
                            $pageId = (int)$value['target'];
                            $colPos = (int)$value['update']['colPos'];
                        } elseif ($value > 0) {
                            $pageId = (int)$value;
                            $colPos = (int)$currentRecord['colPos'];
                        } else {
                            $targetRecord = BackendUtility::getRecord('tt_content', abs($value));
                            $pageId = (int)$targetRecord['pid'];
                            $colPos = (int)$targetRecord['colPos'];
                        }
                        $currentRecord['pid'] = $pageId;
                        $currentRecord['colPos'] = $colPos;

                        $backendLayoutConfiguration = BackendLayoutConfiguration::createFromPageId($pageId);
                        $columnConfiguration = $backendLayoutConfiguration->getConfigurationByColPos($colPos);

                        // Failing one of the conditions prevents a new record from being added to the database for the
                        // current command

                        if (!$this->isRecordAllowedByRestriction($columnConfiguration, $currentRecord)) {
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

                        if (!$this->isRecordAllowedByItemsCount($columnConfiguration, $currentRecord)) {
                            unset($dataHandler->cmdmap['tt_content'][$id]);
                            $dataHandler->log(
                                'tt_content',
                                $id,
                                1,
                                $pageId,
                                1,
                                'The command "%s" for record "%s" couldn\'t be executed due to reached maxitems configuration of %d.',
                                28,
                                [
                                    $command,
                                    $currentRecord[$GLOBALS['TCA']['tt_content']['ctrl']['label']],
                                    $columnConfiguration['maxitems'],
                                ]
                            );
                        }

                        // As for copy command the wrong record uid is used (the one of the record which should
                        // be copied), we need to decrease the self::$colPosCount count again
                        if ('paste' === $command) {
                            self::$colPosCount[$this->getIdentifierForRecord($currentRecord)]--;
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
