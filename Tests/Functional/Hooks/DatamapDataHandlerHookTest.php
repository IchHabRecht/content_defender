<?php
namespace IchHabRecht\ContentDefender\Tests\Functional\Hooks;

use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\DataHandling\DataHandler;

class DatamapDataHandlerHookTest extends FunctionalTestCase
{
    /**
     * @var array
     */
    protected $coreExtensionsToLoad = [
        'fluid_styled_content',
    ];

    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/content_defender',
    ];

    public function setUp()
    {
        parent::setUp();

        $fixturePath = ORIGINAL_ROOT . 'typo3conf/ext/content_defender/Tests/Functional/Fixtures/';
        $this->importDataSet($fixturePath . 'backend_layout.xml');
        $this->importDataSet($fixturePath . 'pages.xml');
        $this->importDataSet($fixturePath . 'tt_content.xml');

        $this->setUpBackendUserFromFixture(1);
        Bootstrap::getInstance()->initializeLanguageObject();
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
