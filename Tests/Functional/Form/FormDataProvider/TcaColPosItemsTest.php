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
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TcaColPosItemsTest extends AbstractFunctionalTestCase
{
    /**
     * @test
     */
    public function loadedColumnIsRemovedFromColPosList()
    {
        $expected = [
            0 => [
                'Left (maxitems = 3)',
                '0',
                null,
                null,
            ],
            1 => [
                'Footer1 (header, list[indexed_search_pi2])',
                '10',
                null,
                null,
            ],
            2 => [
                'Footer3 (all)',
                '12',
                null,
                null,
            ],
        ];

        $formDataCompilerInput = $this->mergeDefaultValuesWithCompilerInput(
            [
                'command' => 'new',
                'request' => $GLOBALS['TYPO3_REQUEST'] ?? null,
                'tableName' => 'tt_content',
                'vanillaUid' => 3,
            ],
            [
                'tt_content' => [
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

        $items = array_values($result['processedTca']['columns']['colPos']['config']['items']);

        $this->assertCount(3, $items);
        $this->assertSame('0', $items[0]['value'] ?? $items[0][1]);
        $this->assertSame('10', $items[1]['value'] ?? $items[1][1]);
        $this->assertSame('12', $items[2]['value'] ?? $items[2][1]);
    }

    /**
     * @test
     */
    public function loadedColumnIsNotRemovedFromColPosListForCurrentRecord()
    {
        $expected = [
            1 => [
                'Right (maxitems = 1)',
                '3',
                null,
                null,
            ],
        ];

        $formDataCompilerInput = [
            'command' => 'edit',
            'request' => $GLOBALS['TYPO3_REQUEST'] ?? null,
            'tableName' => 'tt_content',
            'vanillaUid' => 4,
        ];

        if (version_compare($this->versionBranch, '13', '<')) {
            unset($formDataCompilerInput['request']);
        }

        $formDataGroup = new TcaDatabaseRecord();
        $formDataCompiler = new FormDataCompiler($formDataGroup);
        $result = $formDataCompiler->compile($formDataCompilerInput, $formDataGroup);

        $items = array_values($result['processedTca']['columns']['colPos']['config']['items']);
        foreach ($expected as $key => $valueArray) {
            $this->assertArrayHasKey($key, $items);
            foreach ($valueArray as $index => $value) {
                $this->assertEquals($value, $valueArray[$index]);
            }
        }
    }

    /**
     * @test
     */
    public function notAllowedColumnsAreRemovedFromColPosList()
    {
        $formDataCompilerInput = $this->mergeDefaultValuesWithCompilerInput(
            [
                'command' => 'new',
                'request' => $GLOBALS['TYPO3_REQUEST'] ?? null,
                'tableName' => 'tt_content',
                'vanillaUid' => 3,
            ],
            [
                'tt_content' => [
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

        $items = array_values($result['processedTca']['columns']['colPos']['config']['items']);

        $this->assertCount(3, $items);
        $this->assertSame('0', $items[0]['value'] ?? $items[0][1]);
        $this->assertSame('11', $items[1]['value'] ?? $items[1][1]);
        $this->assertSame('12', $items[2]['value'] ?? $items[2][1]);
    }

    /**
     * @test
     */
    public function existingRecordInLoadedColumnCanBeOpened()
    {
        $formDataCompilerInput = [
            'command' => 'edit',
            'request' => $GLOBALS['TYPO3_REQUEST'] ?? null,
            'tableName' => 'tt_content',
            'vanillaUid' => 4,
        ];

        if (version_compare($this->versionBranch, '13', '<')) {
            unset($formDataCompilerInput['request']);
        }

        $formDataGroup = new TcaDatabaseRecord();
        $formDataCompiler = new FormDataCompiler($formDataGroup);

        $this->assertNotEmpty($formDataCompiler->compile($formDataCompilerInput, $formDataGroup));
    }

    /**
     * @test
     */
    public function newRecordInLoadedColumnCanBeOpened()
    {
        $datamap['tt_content'] = [
            'NEW123' => [
                'pid' => 4,
                'CType' => 'textmedia',
                'colPos' => 3,
                'header' => 'Text & Media',
            ],
        ];

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start($datamap, []);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $this->assertGreaterThan(0, (int)$dataHandler->substNEWwithIDs['NEW123']);

        $formDataCompilerInput = [
            'command' => 'edit',
            'request' => $GLOBALS['TYPO3_REQUEST'] ?? null,
            'tableName' => 'tt_content',
            'vanillaUid' => (int)$dataHandler->substNEWwithIDs['NEW123'],
        ];

        if (version_compare($this->versionBranch, '13', '<')) {
            unset($formDataCompilerInput['request']);
        }

        $formDataGroup = new TcaDatabaseRecord();
        $formDataCompiler = new FormDataCompiler($formDataGroup);

        $this->assertNotEmpty($formDataCompiler->compile($formDataCompilerInput, $formDataGroup));
    }
}
