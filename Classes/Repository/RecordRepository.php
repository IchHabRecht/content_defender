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

class RecordRepository implements RecordRepositoryInterface
{
    protected ConnectionPool $connectionPool;

    public function __construct(ConnectionPool $connectionPool)
    {
        $this->connectionPool = $connectionPool;
    }

    public function canHandle(array $record): bool
    {
        return true;
    }

    public function getExistingRecords(array $record): array
    {
        $rows = [];

        $languageField = $GLOBALS['TCA']['tt_content']['ctrl']['languageField'];
        $language = (array)$record[$languageField];

        $selectFields = ['uid', 'pid'];
        if (!empty($GLOBALS['TCA']['tt_content']['ctrl']['versioningWS'])) {
            $selectFields[] = 't3ver_state';
        }

        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('tt_content');
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
}
