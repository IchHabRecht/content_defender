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

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\BackendWorkspaceRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Versioning\VersionState;

class ContentRepository
{
    /**
     * @var int[][]
     */
    protected static $colPosCount = [];

    public function countColPosByRecord(array $record): int
    {
        $identifier = $this->getIdentifier($record);

        if (!isset(self::$colPosCount[$identifier])) {
            $this->initialize($record);
        }

        return count(self::$colPosCount[$identifier]);
    }

    public function addRecordToColPos(array $record): int
    {
        $identifier = $this->getIdentifier($record);

        if (!isset(self::$colPosCount[$identifier])) {
            $this->initialize($record);
        }

        $uid = ($record['t3ver_oid'] ?? 0) ?: $record['uid'];
        self::$colPosCount[$identifier][$uid] = $uid;

        return count(self::$colPosCount[$identifier]);
    }

    public function removeRecordFromColPos(array $record): int
    {
        $identifier = $this->getIdentifier($record);

        if (!isset(self::$colPosCount[$identifier])) {
            $this->initialize($record);
        }

        $uid = ($record['t3ver_oid'] ?? 0) ?: $record['uid'];
        if (isset(self::$colPosCount[$identifier][$uid])) {
            unset(self::$colPosCount[$identifier][$uid]);
        }

        return count(self::$colPosCount[$identifier]);
    }

    public function isRecordInColPos(array $record): bool
    {
        $identifier = $this->getIdentifier($record);

        if (!isset(self::$colPosCount[$identifier])) {
            $this->initialize($record);
        }

        $uid = ($record['t3ver_oid'] ?? 0) ?: $record['uid'];

        return isset(self::$colPosCount[$identifier][$uid]);
    }

    public function substituteNewIdsWithUids(array $newIdUidArray)
    {
        if (empty($newIdUidArray)) {
            return;
        }

        foreach (self::$colPosCount as $identifier => $uidArray) {
            $intersect = array_intersect_key($newIdUidArray, $uidArray);
            if (empty($intersect)) {
                continue;
            }
            self::$colPosCount[$identifier] = array_replace(
                array_diff_key($uidArray, $newIdUidArray),
                array_combine($intersect, $intersect)
            );
        }
    }

    /**
     * @param array $record
     * @param int $inc
     * @return int
     * @deprecated since version 3.0.4, will be removed in version 4.0.0
     * @codeCoverageIgnore
     */
    public function increaseColPosCountByRecord(array $record, int $inc = 1): int
    {
        trigger_error(
            'Method "increaseColPosCountByRecord" is deprecated since version 3.0.4, will be removed in version 4.0.0',
            E_USER_DEPRECATED
        );
        $identifier = $this->getIdentifier($record);

        if (!isset(self::$colPosCount[$identifier])) {
            $this->initialize($record);
        }
        self::$colPosCount[$identifier] = array_merge(
            self::$colPosCount[$identifier],
            array_fill(0, $inc, 0)
        );

        return count(self::$colPosCount[$identifier]);
    }

    /**
     * @param array $record
     * @param int $dec
     * @return int
     * @deprecated since version 3.0.4, will be removed in version 4.0.0
     * @codeCoverageIgnore
     */
    public function decreaseColPosCountByRecord(array $record, int $dec = 1): int
    {
        trigger_error(
            'Method "decreaseColPosCountByRecord" is deprecated since version 3.0.4, will be removed in version 4.0.0',
            E_USER_DEPRECATED
        );
        $identifier = $this->getIdentifier($record);

        if (!isset(self::$colPosCount[$identifier])) {
            $this->initialize($record);
        }
        $uid = ($record['t3ver_oid'] ?? 0) ?: ($record['uid'] ?? 0);
        if (!empty($uid) && isset(self::$colPosCount[$identifier][$uid])) {
            unset(self::$colPosCount[$identifier][$uid]);
            $dec -= 1;
        }
        while ($dec > 0 && in_array(0, self::$colPosCount[$identifier], true)) {
            $index = array_search(0, self::$colPosCount[$identifier], true);
            unset(self::$colPosCount[$identifier][$index]);
            $dec -= 1;
        }

        return count(self::$colPosCount[$identifier]);
    }

    protected function initialize(array $record)
    {
        self::$colPosCount[$this->getIdentifier($record)] = $this->fetchRecordsForColPos($record);
    }

    protected function fetchRecordsForColPos(array $record): array
    {
        $rows = [];

        $languageField = $GLOBALS['TCA']['tt_content']['ctrl']['languageField'];
        $language = (array)$record[$languageField];

        $selectFields = ['uid', 'pid'];
        if (!empty($GLOBALS['TCA']['tt_content']['ctrl']['versioningWS'])) {
            $selectFields[] = 't3ver_state';
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);
        $queryBuilder->getRestrictions()->add(GeneralUtility::makeInstance(BackendWorkspaceRestriction::class));

        $statement = $queryBuilder->select(...$selectFields)
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'pid',
                    $queryBuilder->createNamedParameter($record['pid'], \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'colPos',
                    $queryBuilder->createNamedParameter($record['colPos'], \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    $languageField,
                    $queryBuilder->createNamedParameter($language[0], \PDO::PARAM_INT)
                )
            )
            ->execute();

        while ($row = $statement->fetch()) {
            BackendUtility::workspaceOL('tt_content', $row, -99, true);
            if (is_array($row) && !VersionState::cast($row['t3ver_state'])->equals(VersionState::DELETE_PLACEHOLDER)) {
                $rows[$row['uid']] = $row['uid'];
            }
        }

        return $rows;
    }

    protected function getIdentifier(array $record): string
    {
        $languageField = $GLOBALS['TCA']['tt_content']['ctrl']['languageField'];
        $language = (array)$record[$languageField];

        return $record['pid'] . '/' . $language[0] . '/' . $record['colPos'];
    }
}
