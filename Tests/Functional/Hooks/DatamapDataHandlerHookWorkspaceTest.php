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
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\WorkspaceAspect;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DatamapDataHandlerHookWorkspaceTest extends AbstractFunctionalTestCase
{
    protected const WORKSPACE_DEV = 1;

    protected const WORKSPACE_STAGE = 2;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->coreExtensionsToLoad[] = 'workspaces';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $fixturePath = ORIGINAL_ROOT . 'typo3conf/ext/content_defender/Tests/Functional/Fixtures/Database/';
        $this->importCSVDataSet($fixturePath . 'sys_workspace.csv');

        // Ensure proper site configuration for DataHandler tests
        $this->setUpFrontendPage(1);
    }

    /**
     * @test
     */
    public function existingRecordInWorkspaceWithinMaxitemsCountIsSaved()
    {
        $this->initializeWorkspace(self::WORKSPACE_STAGE);

        $datamap['tt_content'] = [
            '20' => [
                'header' => 'New Header DEV',
            ],
        ];

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start($datamap, []);
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
                ),
                $queryBuilder->expr()->eq(
                    'header',
                    $queryBuilder->createNamedParameter('New Header DEV')
                )
            )
            ->executeQuery()
            ->fetchOne();

        $this->assertSame(1, $count);
    }

    protected function initializeWorkspace(int $workspaceId)
    {
        $backendUser = $GLOBALS['BE_USER'];
        $backendUser->setWorkspace($workspaceId);

        $context = GeneralUtility::makeInstance(Context::class);
        $context->setAspect('workspace', new WorkspaceAspect($workspaceId));
    }
}
