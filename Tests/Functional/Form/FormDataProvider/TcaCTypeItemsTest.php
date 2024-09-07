<?php

declare(strict_types=1);

namespace IchHabRecht\ContentDefender\Tests\Functional\Form\FormDataProvider;

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

require_once __DIR__ . '/../../AbstractFunctionalTestCase.php';

use IchHabRecht\ContentDefender\Tests\Functional\AbstractFunctionalTestCase;
use TYPO3\CMS\Backend\Form\FormDataCompiler;
use TYPO3\CMS\Backend\Form\FormDataGroup\TcaDatabaseRecord;

class TcaCTypeItemsTest extends AbstractFunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $tempColumns = [
            'tx_simpleselectboxsingle' => [
                'label' => 'Simple select box',
                'exclude' => '1',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'itemsProcFunc' => \IchHabRecht\ContentDefender\Tests\Functional\Fixtures\Classes\Hooks\SimpleSelectboxSingleHook::class . '->addSimpleSelectboxItems',
                ],
            ],
        ];

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'tx_simpleselectboxsingle');
    }

    /**
     * @test
     */
    public function disallowedCTypesAreRemovedFromCTypeList()
    {
        $formDataCompilerInput = $this->mergeDefaultValuesWithCompilerInput(
            [
                'command' => 'new',
                'request' => $GLOBALS['TYPO3_REQUEST'] ?? null,
                'tableName' => 'tt_content',
                'vanillaUid' => 2,
            ],
            [
                'tt_content' => [
                    'colPos' => 11,
                    'CType' => 'bullets',
                ],
            ]
        );

        if (version_compare($this->versionBranch, '13', '<')) {
            unset($formDataCompilerInput['request']);
        }

        $formDataGroup = new TcaDatabaseRecord();
        $formDataCompiler = new FormDataCompiler($formDataGroup);
        $result = $formDataCompiler->compile($formDataCompilerInput, $formDataGroup);

        $items = array_values($result['processedTca']['columns']['CType']['config']['items']);

        $this->assertCount(1, $items);
        $this->assertSame('bullets', $items[0]['value'] ?? $items[0][1]);
    }

    /**
     * @test
     */
    public function disallowedItemsAreRemovedFromListWithItemsProcFunc()
    {
        $formDataCompilerInput = $this->mergeDefaultValuesWithCompilerInput(
            [
                'command' => 'new',
                'request' => $GLOBALS['TYPO3_REQUEST'] ?? null,
                'tableName' => 'tt_content',
                'vanillaUid' => 2,
            ],
            [
                'tt_content' => [
                    'colPos' => 12,
                    'CType' => 'header',
                ],
            ]
        );

        if (version_compare($this->versionBranch, '13', '<')) {
            unset($formDataCompilerInput['request']);
        }

        $formDataGroup = new TcaDatabaseRecord();
        $formDataCompiler = new FormDataCompiler($formDataGroup);
        $result = $formDataCompiler->compile($formDataCompilerInput, $formDataGroup);

        $items = array_values($result['processedTca']['columns']['tx_simpleselectboxsingle']['config']['items']);

        $this->assertCount(1, $items);
        $this->assertSame('5', $items[0]['value'] ?? $items[0][1]);
    }
}
