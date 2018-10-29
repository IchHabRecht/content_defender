<?php
declare(strict_types = 1);
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
            2 => [
                'Footer1 (header, list[indexed_search_pi2])',
                '10',
                null,
                null,
            ],
            4 => [
                'Footer3 (all)',
                '12',
                null,
                null,
            ],
        ];

        $_GET['defVals'] = [
            'tt_content' => [
                'CType' => 'header',
            ],
        ];

        $formDataCompilerInput = [
            'command' => 'new',
            'tableName' => 'tt_content',
            'vanillaUid' => 3,
        ];

        $formDataGroup = new TcaDatabaseRecord();
        $formDataCompiler = new FormDataCompiler($formDataGroup);
        $result = $formDataCompiler->compile($formDataCompilerInput);

        $this->assertSame($expected, $result['processedTca']['columns']['colPos']['config']['items']);
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
            'tableName' => 'tt_content',
            'vanillaUid' => 4,
        ];

        $formDataGroup = new TcaDatabaseRecord();
        $formDataCompiler = new FormDataCompiler($formDataGroup);
        $result = $formDataCompiler->compile($formDataCompilerInput);

        $this->assertArraySubset($expected, $result['processedTca']['columns']['colPos']['config']['items']);
    }

    /**
     * @test
     */
    public function notAllowedColumnsAreRemovedFromColPosList()
    {
        $expected = [
            0 => [
                'Left (maxitems = 3)',
                '0',
                null,
                null,
            ],
            3 => [
                'Footer2 (bullets)',
                '11',
                null,
                null,
            ],
            4 => [
                'Footer3 (all)',
                '12',
                null,
                null,
            ],
        ];

        $_GET['defVals'] = [
            'tt_content' => [
                'CType' => 'bullets',
            ],
        ];

        $formDataCompilerInput = [
            'command' => 'new',
            'tableName' => 'tt_content',
            'vanillaUid' => 3,
        ];

        $formDataGroup = new TcaDatabaseRecord();
        $formDataCompiler = new FormDataCompiler($formDataGroup);
        $result = $formDataCompiler->compile($formDataCompilerInput);

        $this->assertSame($expected, $result['processedTca']['columns']['colPos']['config']['items']);
    }

    /**
     * @test
     */
    public function existingRecordInLoadedColumnCanBeOpened()
    {
        $formDataCompilerInput = [
            'command' => 'edit',
            'tableName' => 'tt_content',
            'vanillaUid' => 4,
        ];

        $formDataGroup = new TcaDatabaseRecord();
        $formDataCompiler = new FormDataCompiler($formDataGroup);

        $this->assertNotEmpty($formDataCompiler->compile($formDataCompilerInput));
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

        $dataHandler = new DataHandler();
        $dataHandler->start($datamap, []);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $this->assertGreaterThan(0, (int)$dataHandler->substNEWwithIDs['NEW123']);

        $formDataCompilerInput = [
            'command' => 'edit',
            'tableName' => 'tt_content',
            'vanillaUid' => (int)$dataHandler->substNEWwithIDs['NEW123'],
        ];

        $formDataGroup = new TcaDatabaseRecord();
        $formDataCompiler = new FormDataCompiler($formDataGroup);

        $this->assertNotEmpty($formDataCompiler->compile($formDataCompilerInput));
    }
}
