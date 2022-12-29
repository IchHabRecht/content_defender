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
use TYPO3\CMS\Core\DataHandling\DataHandler;

class DatamapDataHandlerHookTest extends AbstractFunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Ensure proper site configuration for DataHandler tests
        $this->setUpFrontendRootPage(1);
    }

    /**
     * @test
     */
    public function newRecordWithDisallowedCTypeIsNotSaved()
    {
        $datamap['tt_content']['NEW123'] = [
            'pid' => 2,
            'CType' => 'bullets',
            'colPos' => 0,
            'header' => 'Bullet List',
            'sys_language_uid' => 0,
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start($datamap, []);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $this->assertEmpty($dataHandler->substNEWwithIDs);
    }

    /**
     * @test
     */
    public function newRecordWithDisallowedCTypeAfterRecordIsNotSaved()
    {
        $datamap['tt_content']['NEW123'] = [
            'pid' => -2,
            'CType' => 'bullets',
            'colPos' => 0,
            'header' => 'Bullet List',
            'sys_language_uid' => 0,
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start($datamap, []);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $this->assertEmpty($dataHandler->substNEWwithIDs);
    }

    /**
     * @test
     */
    public function newRecordsWithMaxitemsCountAreNotSaved()
    {
        $datamap['tt_content'] = [
            'NEW123' => [
                'pid' => 3,
                'CType' => 'textmedia',
                'colPos' => 0,
                'header' => 'Text & Media 1',
                'sys_language_uid' => 0,
            ],
            'NEW456' => [
                'pid' => 3,
                'CType' => 'textmedia',
                'colPos' => 0,
                'header' => 'Text & Media 2',
                'sys_language_uid' => 0,
            ],
            'NEW789' => [
                'pid' => 3,
                'CType' => 'textmedia',
                'colPos' => 0,
                'header' => 'Text & Media 3',
                'sys_language_uid' => 0,
            ],
            'NEW147' => [
                'pid' => 3,
                'CType' => 'textmedia',
                'colPos' => 0,
                'header' => 'Text & Media 4',
                'sys_language_uid' => 0,
            ],
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start($datamap, []);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $count = $queryBuilder->count('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'pid',
                    $queryBuilder->createNamedParameter(3)
                ),
                $queryBuilder->expr()->eq(
                    'colPos',
                    $queryBuilder->createNamedParameter(0)
                )
            )
            ->execute()
            ->fetchOne();

        $this->assertSame(3, $count);
    }

    /**
     * @test
     */
    public function newRecordWithMaxitemsCountInLoadedColumnIsNotSaved()
    {
        $datamap['tt_content'] = [
            'NEW123' => [
                'pid' => 3,
                'CType' => 'textmedia',
                'colPos' => 3,
                'header' => 'Text & Media',
                'sys_language_uid' => 0,
            ],
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start($datamap, []);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $count = $queryBuilder->count('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'pid',
                    $queryBuilder->createNamedParameter(3)
                ),
                $queryBuilder->expr()->eq(
                    'colPos',
                    $queryBuilder->createNamedParameter(3)
                ),
                $queryBuilder->expr()->eq(
                    'header',
                    $queryBuilder->createNamedParameter('Text & Media')
                )
            )
            ->execute()
            ->fetchOne();

        $this->assertSame(0, $count);
    }

    /**
     * @test
     */
    public function existingRecordWithinMaxitemsCountIsSaved()
    {
        $datamap['tt_content'] = [
            '4' => [
                'header' => 'New Header',
            ],
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start($datamap, []);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $count = $queryBuilder->count('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'pid',
                    $queryBuilder->createNamedParameter(3)
                ),
                $queryBuilder->expr()->eq(
                    'colPos',
                    $queryBuilder->createNamedParameter(3)
                ),
                $queryBuilder->expr()->eq(
                    'header',
                    $queryBuilder->createNamedParameter('New Header')
                )
            )
            ->execute()
            ->fetchOne();

        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function existingDeletedRecordCanBeProcessed()
    {
        $datamap['tt_content'] = [
            '6' => [
                'header' => 'New Header',
            ],
        ];

        $dataHandler = new DataHandler();
        $dataHandler->bypassAccessCheckForRecords = true;
        $dataHandler->start($datamap, []);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $queryBuilder = $this->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeAll();
        $count = $queryBuilder->count('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter(6)
                ),
                $queryBuilder->expr()->eq(
                    'header',
                    $queryBuilder->createNamedParameter('New Header')
                )
            )
            ->execute()
            ->fetchOne();

        $this->assertSame(1, $count);
    }
}
