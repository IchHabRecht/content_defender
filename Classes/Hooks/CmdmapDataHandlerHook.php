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
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\DataHandling\DataHandler;

class CmdmapDataHandlerHook extends AbstractDataHandlerHook
{
    /**
     * @param DataHandler $dataHandler
     * @return void
     */
    public function processCmdmap_beforeStart(DataHandler $dataHandler)
    {
        $cmdmap = $dataHandler->cmdmap;
        if (empty($cmdmap['tt_content']) || $dataHandler->bypassAccessCheckForRecords) {
            return;
        }

        foreach ($cmdmap['tt_content'] as $id => $incomingFieldArray) {
            foreach ($incomingFieldArray as $command => $value) {
                if (!in_array($command, ['copy', 'move'], true)) {
                    continue;
                }

                $currentRecord = BackendUtility::getRecord('tt_content', $id);

                if ($command === 'move') {
                    // New colPos is passed as datamap array and already processed in processDatamap_beforeStart
                    $data = isset($dataHandler->datamap['tt_content'][$id])
                        ? $dataHandler->datamap : ($_POST['data'] ?? $_GET['data'] ?? null);
                    if (isset($data['tt_content'][$id])) {
                        if (isset($data['tt_content'][$id]['colPos'])
                            && (int)$currentRecord['colPos'] !== (int)$data['tt_content'][$id]['colPos']
                        ) {
                            unset($dataHandler->cmdmap['tt_content'][$id]);
                        }
                        // No further processing needed
                        continue;
                    }
                }

                if (is_array($value)
                    && !empty($value['action'])
                    && 'paste' === $value['action']
                    && isset($value['update']['colPos'])
                ) {
                    $command = 'paste';
                    $pageId = (int)$value['target'];
                    $colPos = (int)$value['update']['colPos'];
                } else {
                    $pageId = (int)$value;
                    $colPos = (int)$currentRecord['colPos'];
                }
                if ($pageId < 0) {
                    $targetRecord = BackendUtility::getRecord('tt_content', abs($pageId));
                    $pageId = (int)$targetRecord['pid'];
                    $colPos = (int)$targetRecord['colPos'];
                }
                $currentRecord['pid'] = $pageId;
                $currentRecord['colPos'] = $colPos;

                $backendLayoutConfiguration = BackendLayoutConfiguration::createFromPageId($pageId);
                $columnConfiguration = $backendLayoutConfiguration->getConfigurationByColPos($colPos, $id);

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
                    $this->contentRepository->removeRecordFromColPos($currentRecord);
                }
            }
        }

        if (count($cmdmap['tt_content']) !== count($dataHandler->cmdmap['tt_content'])
            && empty($_POST['prErr'] ?? $_GET['prErr'] ?? null)
        ) {
            $dataHandler->printLogErrorMessages();
        }
    }

    public function processCmdmap_afterFinish(DataHandler $dataHandler)
    {
        $this->contentRepository->substituteNewIdsWithUids($dataHandler->substNEWwithIDs);
    }
}
