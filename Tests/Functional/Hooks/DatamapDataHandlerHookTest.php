<?php
namespace IchHabRecht\ContentDefender\Tests\Functional\Hooks;

require_once __DIR__ . '/../AbstractFunctionalTestCase.php';

use TYPO3\CMS\Core\DataHandling\DataHandler;

class DatamapDataHandlerHookTest extends AbstractFunctionalTestCase
{
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
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start($datamap, []);
        $dataHandler->process_datamap();

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
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start($datamap, []);
        $dataHandler->process_datamap();

        $this->assertEmpty($dataHandler->substNEWwithIDs);
    }
}
