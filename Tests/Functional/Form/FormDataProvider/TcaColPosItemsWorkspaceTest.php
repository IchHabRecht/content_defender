<?php

declare(strict_types=1);

namespace IchHabRecht\ContentDefender\Tests\Functional\Form\FormDataProvider;

/*
 * This file is part of the TYPO3 extension content_defender.
 *
 * (c) Nicole Hummel <nicole-typo3@nimut.dev>
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

require_once __DIR__ . '/../../AbstractFunctionalTestCase.php';

use IchHabRecht\ContentDefender\Tests\Functional\AbstractFunctionalTestCase;
use TYPO3\CMS\Backend\Form\FormDataCompiler;
use TYPO3\CMS\Backend\Form\FormDataGroup\TcaDatabaseRecord;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\WorkspaceAspect;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TcaColPosItemsWorkspaceTest extends AbstractFunctionalTestCase
{
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
    }

    /**
     * @test
     */
    public function existingRecordWithWorkspaceVersionInLoadedColumnCanBeOpened()
    {
        $this->initializeWorkspace(self::WORKSPACE_STAGE);

        $formDataCompilerInput = [
            'command' => 'edit',
            'request' => $GLOBALS['TYPO3_REQUEST'] ?? null,
            'tableName' => 'tt_content',
            'vanillaUid' => 4,
        ];

        if (version_compare($this->versionBranch, '13', '<')) {
            unset($formDataCompilerInput['request']);
        }

        $formDataGroup = GeneralUtility::makeInstance(TcaDatabaseRecord::class);
        $formDataCompiler = new FormDataCompiler($formDataGroup);
        $result = $formDataCompiler->compile($formDataCompilerInput, $formDataGroup);

        $items = array_values($result['processedTca']['columns']['colPos']['config']['items']);
        $colPosValues = array_map(static fn (array $item): string => (string)($item['value'] ?? $item[1]), $items);

        $this->assertContains('3', $colPosValues);
    }

    protected function initializeWorkspace(int $workspaceId)
    {
        $backendUser = $GLOBALS['BE_USER'];
        $backendUser->setWorkspace($workspaceId);

        $context = GeneralUtility::makeInstance(Context::class);
        $context->setAspect('workspace', new WorkspaceAspect($workspaceId));
    }
}
