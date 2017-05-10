<?php
namespace IchHabRecht\ContentDefender\Tests\Functional\Hooks;

use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\DataHandling\DataHandler;

class CTypeDataHandlerHookTest extends FunctionalTestCase
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
    public function moveCommandMovesAllowedRecordToNewColPos()
    {
        $dataMap['tt_content'][2] = [
            'colPos' => 0,
        ];

        $commandMap['tt_content'][2] = [
            'move' => 1,
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start($dataMap, $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $count = $this->getDatabaseConnection()->exec_SELECTcountRows('*', 'tt_content', 'uid=2 AND colPos=0');

        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function moveCommandPreventsDisallowedRecordInNewColPos()
    {
        $dataMap['tt_content'][3] = [
            'colPos' => 0,
        ];

        $commandMap['tt_content'][3] = [
            'move' => 1,
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start($dataMap, $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $count = $this->getDatabaseConnection()->exec_SELECTcountRows('*', 'tt_content', 'uid=3 AND colPos=0');

        $this->assertSame(0, $count);
    }

    /**
     * @test
     */
    public function copyCommandGeneratesNewRecordInAllowedColPos()
    {
        $commandMap['tt_content'][2] = [
            'copy' => [
                'action' => 'paste',
                'target' => 2,
                'update' => [
                    'colPos' => '0',
                ],
            ],
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start([], $commandMap);
        $dataHandler->process_cmdmap();

        $recordUid = $dataHandler->copyMappingArray['tt_content'][2];
        $count = $this->getDatabaseConnection()->exec_SELECTcountRows('*', 'tt_content', 'uid=' . $recordUid . ' AND colPos=0');

        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function copyCommandGeneratesNewRecordInAllowedColPosAfterRecord()
    {
        $commandMap['tt_content'][2] = [
            'copy' => -1,
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start([], $commandMap);
        $dataHandler->process_cmdmap();

        $recordUid = $dataHandler->copyMappingArray['tt_content'][2];
        $count = $this->getDatabaseConnection()->exec_SELECTcountRows('*', 'tt_content', 'uid=' . $recordUid . ' AND colPos=0');

        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function copyCommandPreventsNewRecordInDisallowedColPos()
    {
        $commandMap['tt_content'][3] = [
            'copy' => [
                'action' => 'paste',
                'target' => 2,
                'update' => [
                    'colPos' => '0',
                ],
            ],
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start([], $commandMap);
        $dataHandler->process_cmdmap();

        $this->assertEmpty($dataHandler->copyMappingArray['tt_content'][3]);
    }

    /**
     * @test
     */
    public function copyCommandPreventsNewRecordInDisallowedColPosAfterRecord()
    {
        $commandMap['tt_content'][3] = [
            'copy' => -1,
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start([], $commandMap);
        $dataHandler->process_cmdmap();

        $this->assertEmpty($dataHandler->copyMappingArray['tt_content'][3]);
    }

    /**
     * @test
     */
    public function deleteCommandDeletesContentElement()
    {
        $commandMap['tt_content'][1] = [
            'delete' => true,
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start([], $commandMap);
        $dataHandler->process_cmdmap();

        $count = $this->getDatabaseConnection()->exec_SELECTcountRows('*', 'tt_content', 'deleted=1');

        $this->assertSame(1, $count);
    }
}
