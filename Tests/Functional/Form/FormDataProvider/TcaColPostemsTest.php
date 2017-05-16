<?php
namespace IchHabRecht\ContentDefender\Tests\Functional\Form\FormDataProvider;

require_once __DIR__ . '/../../AbstractFunctionalTestCase.php';

use IchHabRecht\ContentDefender\Tests\Functional\AbstractFunctionalTestCase;
use TYPO3\CMS\Backend\Form\FormDataCompiler;
use TYPO3\CMS\Backend\Form\FormDataGroup\TcaDatabaseRecord;

class TcaColPostemsTest extends AbstractFunctionalTestCase
{
    /**
     * @test
     */
    public function loadedColumnIsRemovedFormColPosList()
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
    public function existingRecordsInLoadedColumnCanBeOpened()
    {
        $formDataCompilerInput = [
            'command' => 'edit',
            'tableName' => 'tt_content',
            'vanillaUid' => 4,
        ];

        $formDataGroup = new TcaDatabaseRecord();
        $formDataCompiler = new FormDataCompiler($formDataGroup);
        $result = $formDataCompiler->compile($formDataCompilerInput);
    }
}
