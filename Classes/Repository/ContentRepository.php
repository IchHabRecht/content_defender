<?php

declare(strict_types=1);

namespace IchHabRecht\ContentDefender\Repository;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;

class ContentRepository
{
    protected ColPosCountState $colPosCount;

    protected array $recordRepositories;

    public function __construct(ColPosCountState $colPosCount = null, array $recordRepositories = [])
    {
        $this->colPosCount = $colPosCount ?? GeneralUtility::makeInstance(ColPosCountState::class);
        $this->recordRepositories = $recordRepositories;
    }

    public function countColPosByRecord(array $record): int
    {
        $identifier = $this->getIdentifier($record);

        if (!isset($this->colPosCount[$identifier])) {
            $this->initialize($record);
        }

        return count($this->colPosCount[$identifier]);
    }

    public function addRecordToColPos(array $record): int
    {
        $identifier = $this->getIdentifier($record);

        if (!isset($this->colPosCount[$identifier])) {
            $this->initialize($record);
        }

        $uid = ($record['t3ver_oid'] ?? 0) ?: $record['uid'];
        $this->colPosCount[$identifier][$uid] = $uid;

        return count($this->colPosCount[$identifier]);
    }

    public function removeRecordFromColPos(array $record): int
    {
        $identifier = $this->getIdentifier($record);

        if (!isset($this->colPosCount[$identifier])) {
            $this->initialize($record);
        }

        $uid = ($record['t3ver_oid'] ?? 0) ?: $record['uid'];
        if (isset($this->colPosCount[$identifier][$uid])) {
            unset($this->colPosCount[$identifier][$uid]);
        }

        return count($this->colPosCount[$identifier]);
    }

    public function isRecordInColPos(array $record): bool
    {
        $identifier = $this->getIdentifier($record);

        if (!isset($this->colPosCount[$identifier])) {
            $this->initialize($record);
        }

        $uid = ($record['t3ver_oid'] ?? 0) ?: $record['uid'];

        return isset($this->colPosCount[$identifier][$uid]);
    }

    public function substituteNewIdsWithUids(array $newIdUidArray)
    {
        if (empty($newIdUidArray)) {
            return;
        }

        foreach ($this->colPosCount as $identifier => $uidArray) {
            $intersect = array_intersect_key($newIdUidArray, $uidArray);
            if (empty($intersect)) {
                continue;
            }
            $this->colPosCount[$identifier] = array_replace(
                array_diff_key($uidArray, $newIdUidArray),
                array_combine($intersect, $intersect)
            );
        }
    }

    protected function initialize(array $record)
    {
        $rows = [];
        foreach ($this->recordRepositories as $recordRepository) {
            if (!$recordRepository instanceof RecordRepositoryInterface
                || !$recordRepository->canHandle($record)
            ) {
                continue;
            }
            $rows = $recordRepository->getExistingRecords($record);
            break;
        }
        $this->colPosCount[$this->getIdentifier($record)] = $rows;
    }

    protected function getIdentifier(array $record): string
    {
        $languageField = $GLOBALS['TCA']['tt_content']['ctrl']['languageField'];
        $language = (array)$record[$languageField];

        return implode('_', [$record['pid'], $language[0], $record['colPos']]);
    }
}
