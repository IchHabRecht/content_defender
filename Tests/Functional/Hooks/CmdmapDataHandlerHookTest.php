<?php

declare(strict_types=1);

namespace IchHabRecht\ContentDefender\Tests\Functional\Hooks;

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

require_once __DIR__ . '/../AbstractFunctionalTestCase.php';

use IchHabRecht\ContentDefender\Tests\Functional\AbstractFunctionalTestCase;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CmdmapDataHandlerHookTest extends AbstractFunctionalTestCase
{
    /**
     * @test
     */
    public function moveCommandMovesRecordInCurrentColPos()
    {
        $dataMap['tt_content'][2] = [
            'colPos' => 3,
        ];

        $commandMap['tt_content'][2] = [
            'move' => -3,
        ];

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start($dataMap, $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $count = (int)(int)$queryBuilder->count('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter(2)
                ),
                $queryBuilder->expr()->eq(
                    'colPos',
                    $queryBuilder->createNamedParameter(3)
                ),
                $queryBuilder->expr()->eq(
                    'sorting',
                    $queryBuilder->createNamedParameter(512)
                )
            )
            ->executeQuery()
            ->fetchOne();

        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function moveCommandMovesAllowedRecordToNewColPos()
    {
        $dataMap['tt_content'][2] = [
            'colPos' => 0,
        ];

        $commandMap['tt_content'][2] = [
            'move' => 2,
        ];

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start($dataMap, $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $count = (int)$queryBuilder->count('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter(2)
                ),
                $queryBuilder->expr()->eq(
                    'colPos',
                    $queryBuilder->createNamedParameter(0)
                )
            )
            ->executeQuery()
            ->fetchOne();

        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function moveCommandMovesRecordWithEmptyListType()
    {
        $dataMap['tt_content'][7] = [
            'colPos' => 10,
        ];

        $commandMap['tt_content'][7] = [
            'move' => 4,
        ];

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start($dataMap, $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $count = (int)$queryBuilder->count('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter(7)
                ),
                $queryBuilder->expr()->eq(
                    'colPos',
                    $queryBuilder->createNamedParameter(10)
                ),
                $queryBuilder->expr()->eq(
                    'sorting',
                    $queryBuilder->createNamedParameter(64)
                )
            )
            ->executeQuery()
            ->fetchOne();

        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function moveCommandPreventsDisallowedRecordInNewColPos()
    {
        $dataMap['tt_content'][3] = [
            'colPos' => 0,
        ];

        $commandMap['tt_content'][3] = [
            'move' => 2,
        ];

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start($dataMap, $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $count = (int)$queryBuilder->count('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter(3)
                ),
                $queryBuilder->expr()->eq(
                    'colPos',
                    $queryBuilder->createNamedParameter(0)
                )
            )
            ->executeQuery()
            ->fetchOne();

        $this->assertSame(0, $count);
    }

    /**
     * @test
     */
    public function moveCommandMovesOnlyOneRecordToMaxitemsColPos()
    {
        $commandMap['tt_content'] = [
            2 => [
                'move' => 3,
            ],
            3 => [
                'move' => 3,
            ],
        ];

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start([], $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $count = (int)$queryBuilder->count('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'pid',
                    $queryBuilder->createNamedParameter(3)
                ),
                $queryBuilder->expr()->eq(
                    'colPos',
                    $queryBuilder->createNamedParameter(3)
                )
            )
            ->executeQuery()
            ->fetchOne();

        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function moveCommandMovesRecordToMaxitemsColPosWithoutError()
    {
        $dataMap['tt_content'][5] = [
            'colPos' => 3,
        ];

        $commandMap['tt_content'][5] = [
            'move' => 4,
        ];

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start($dataMap, $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $count = (int)$queryBuilder->count('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter(5)
                ),
                $queryBuilder->expr()->eq(
                    'colPos',
                    $queryBuilder->createNamedParameter(3)
                )
            )
            ->executeQuery()
            ->fetchOne();

        $this->assertSame(1, $count);
        $this->assertNoProcessingErrorsInDataHandler($dataHandler);
    }

    /**
     * @test
     */
    public function copyCommandGeneratesNewRecordInAllowedColPos()
    {
        $commandMap['tt_content'][2] = [
            'copy' => [
                'action' => 'paste',
                'target' => 2,
                'update' => [
                    'colPos' => '0',
                ],
            ],
        ];

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start([], $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $recordUid = $dataHandler->copyMappingArray['tt_content'][2];

        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $count = (int)$queryBuilder->count('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($recordUid, Connection::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'colPos',
                    $queryBuilder->createNamedParameter(0)
                )
            )
            ->executeQuery()
            ->fetchOne();

        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function copyCommandGeneratesNewRecordInAllowedColPosAfterRecord()
    {
        $commandMap['tt_content'][2] = [
            'copy' => [
                'action' => 'paste',
                'target' => '-1',
                'update' => [
                    'colPos' => '0',
                    'sys_language_uid' => '0',
                ],
            ],
        ];

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start([], $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $recordUid = $dataHandler->copyMappingArray['tt_content'][2];

        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $count = (int)$queryBuilder->count('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($recordUid, Connection::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'colPos',
                    $queryBuilder->createNamedParameter(0)
                )
            )
            ->executeQuery()
            ->fetchOne();

        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function copyCommandMovesRecordInAllowedColPosWithEmptyListType()
    {
        $commandMap['tt_content'][2] = [
            'move' => [
                'action' => 'paste',
                'target' => 3,
                'update' => [
                    'colPos' => '10',
                ],
            ],
        ];

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start([], $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $count = (int)$queryBuilder->count('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter(2)
                ),
                $queryBuilder->expr()->eq(
                    'colPos',
                    $queryBuilder->createNamedParameter(10)
                )
            )
            ->executeQuery()
            ->fetchOne();

        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function copyCommandPreventsNewRecordInDisallowedColPos()
    {
        $commandMap['tt_content'][3] = [
            'copy' => [
                'action' => 'paste',
                'target' => 2,
                'update' => [
                    'colPos' => '0',
                ],
            ],
        ];

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start([], $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $this->assertArrayNotHasKey('tt_content', $dataHandler->copyMappingArray);
    }

    /**
     * @test
     */
    public function copyCommandPreventsNewRecordInDisallowedColPosAfterRecord()
    {
        $commandMap['tt_content'][3] = [
            'copy' => [
                'action' => 'paste',
                'target' => '-1',
                'update' => [
                    'colPos' => '0',
                    'sys_language_uid' => '0',
                ],
            ],
        ];

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start([], $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $this->assertArrayNotHasKey('tt_content', $dataHandler->copyMappingArray);
    }

    /**
     * @test
     */
    public function copyCommandCopiesOnlyOneRecordToMaxitemsColPos()
    {
        $_GET['CB']['paste'] = '|3';
        $commandMap['tt_content'] = [
            2 => [
                'copy' => 3,
            ],
            3 => [
                'copy' => 3,
            ],
        ];

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start([], $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $count = (int)$queryBuilder->count('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'pid',
                    $queryBuilder->createNamedParameter(3)
                ),
                $queryBuilder->expr()->eq(
                    'colPos',
                    $queryBuilder->createNamedParameter(3)
                )
            )
            ->executeQuery()
            ->fetchOne();

        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function copyCommandCopiesAllowedRecordToEmptyMaxitemsColpos()
    {
        $commandMap['tt_content'][4] = [
            'copy' => [
                'action' => 'paste',
                'target' => 3,
                'update' => [
                    'colPos' => '3',
                ],
            ],
        ];

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start([], $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $recordUid = $dataHandler->copyMappingArray['tt_content'][4];

        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $count = (int)$queryBuilder->count('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($recordUid, Connection::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'colPos',
                    $queryBuilder->createNamedParameter(3)
                )
            )
            ->executeQuery()
            ->fetchOne();

        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function deleteCommandDeletesContentElement()
    {
        $commandMap['tt_content'][1] = [
            'delete' => true,
        ];

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start([], $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeAll();
        $count = (int)$queryBuilder->count('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'pid',
                    $queryBuilder->createNamedParameter(2)
                ),
                $queryBuilder->expr()->eq(
                    'deleted',
                    $queryBuilder->createNamedParameter(1)
                )
            )
            ->executeQuery()
            ->fetchOne();

        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function localizeCommandTranslatesRecordInLoadedColumn()
    {
        $commandMap['tt_content'][4] = [
            'localize' => 2,
        ];

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start([], $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $count = (int)$queryBuilder->count('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'pid',
                    $queryBuilder->createNamedParameter(3)
                ),
                $queryBuilder->expr()->eq(
                    'sys_language_uid',
                    $queryBuilder->createNamedParameter(2)
                ),
                $queryBuilder->expr()->eq(
                    'colPos',
                    $queryBuilder->createNamedParameter(3)
                )
            )
            ->executeQuery()
            ->fetchOne();

        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function copyToLanguageCommandTranslatesRecordInLoadedColumn()
    {
        $commandMap['tt_content'][4] = [
            'copyToLanguage' => 2,
        ];

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start([], $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $count = (int)$queryBuilder->count('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'pid',
                    $queryBuilder->createNamedParameter(3)
                ),
                $queryBuilder->expr()->eq(
                    'sys_language_uid',
                    $queryBuilder->createNamedParameter(2)
                ),
                $queryBuilder->expr()->eq(
                    'colPos',
                    $queryBuilder->createNamedParameter(3)
                )
            )
            ->executeQuery()
            ->fetchOne();

        $this->assertSame(1, $count);
    }
}
