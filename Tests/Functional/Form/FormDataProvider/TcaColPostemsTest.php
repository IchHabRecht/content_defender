<?php
declare(strict_types=1);
namespace IchHabRecht\ContentDefender\Tests\Functional\Form\FormDataProvider;

require_once __DIR__ . '/../../AbstractFunctionalTestCase.php';

use IchHabRecht\ContentDefender\Tests\Functional\AbstractFunctionalTestCase;
use TYPO3\CMS\Backend\Form\FormDataCompiler;
use TYPO3\CMS\Backend\Form\FormDataGroup\TcaDatabaseRecord;
use TYPO3\CMS\Core\DataHandling\DataHandler;

class TcaColPostemsTest extends AbstractFunctionalTestCase
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
                'Footer1 (no bullets)',
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

        $this->assertSame(6, $dataHandler->substNEWwithIDs['NEW123']);

        $formDataCompilerInput = [
            'command' => 'edit',
            'tableName' => 'tt_content',
            'vanillaUid' => 6,
        ];

        $formDataGroup = new TcaDatabaseRecord();
        $formDataCompiler = new FormDataCompiler($formDataGroup);

        $this->assertNotEmpty($formDataCompiler->compile($formDataCompilerInput));
    }
}
