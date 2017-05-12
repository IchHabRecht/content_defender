<?php
namespace IchHabRecht\ContentDefender\Tests\Functional\Form\FormDataProvider;

require_once __DIR__ . '/../../AbstractFunctionalTestCase.php';

use IchHabRecht\ContentDefender\Tests\Functional\AbstractFunctionalTestCase;
use TYPO3\CMS\Backend\Form\FormDataCompiler;
use TYPO3\CMS\Backend\Form\FormDataGroup\TcaDatabaseRecord;

class TcaCTypeItemsTest extends AbstractFunctionalTestCase
{
    /**
     * @test
     */
    public function disallowedCTypesAreRemovedFromCTypeList()
    {
        $expected = [
            0 => [
                'Bullet List',
                'bullets',
                'content-bullets',
                null,
            ],
        ];

        $_GET['defVals']['tt_content'] = [
            'colPos' => 11,
            'CType' => 'bullets',
        ];
        $formDataCompilerInput = [
            'command' => 'new',
            'tableName' => 'tt_content',
            'vanillaUid' => 2,
        ];

        $formDataGroup = new TcaDatabaseRecord();
        $formDataCompiler = new FormDataCompiler($formDataGroup);
        $result = $formDataCompiler->compile($formDataCompilerInput);

        $this->assertSame($expected, $result['processedTca']['columns']['CType']['config']['items']);
    }
}
