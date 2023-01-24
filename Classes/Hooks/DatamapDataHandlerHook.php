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
        if (empty($datamap['tt_content']) || $dataHandler->bypassAccessCheckForRecords) {
            return;
        }

        foreach ($datamap['tt_content'] as $id => $incomingFieldArray) {
            $incomingFieldArray['uid'] = $id;
            if (MathUtility::canBeInterpretedAsInteger($id)) {
                $incomingFieldArray = array_merge(BackendUtility::getRecord('tt_content', $id), $incomingFieldArray);
            } else {
                $incomingFieldArray = array_merge($dataHandler->defaultValues['tt_content'] ?? [], $incomingFieldArray);
            }

            $pageId = (int)$incomingFieldArray['pid'];
            if ($pageId < 0) {
                $previousRecord = BackendUtility::getRecord('tt_content', abs($pageId), 'pid');
                $pageId = (int)$previousRecord['pid'];
                $incomingFieldArray['pid'] = $pageId;
            }
            $colPos = (int)$incomingFieldArray['colPos'];

            $backendLayoutConfiguration = BackendLayoutConfiguration::createFromPageId($pageId);
            $columnConfiguration = $backendLayoutConfiguration->getConfigurationByColPos($colPos, $id);

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
                if (empty($dataHandler->cmdmap) && !empty(GeneralUtility::_GP('CB')['paste'])) {
                    continue;
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

    public function processDatamap_afterAllOperations(DataHandler $dataHandler)
    {
        $this->contentRepository->substituteNewIdsWithUids($dataHandler->substNEWwithIDs);
    }
}
