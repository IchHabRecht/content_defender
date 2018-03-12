<?php
namespace IchHabRecht\ContentDefender\Tests\Functional\Hooks;

require_once __DIR__ . '/../AbstractFunctionalTestCase.php';

use IchHabRecht\ContentDefender\Tests\Functional\AbstractFunctionalTestCase;
use TYPO3\CMS\Core\DataHandling\DataHandler;

class CmdmapDataHandlerHookTest extends AbstractFunctionalTestCase
{
    /**
     * @test
     */
    public function moveCommandMovesRecordInCurrentColPos()
    {
        $dataMap['tt_content'][2] = [
            'colPos' => 3,
        ];

        $commandMap['tt_content'][2] = [
            'move' => -3,
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start($dataMap, $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $count = $this->getDatabaseConnection()->selectCount('*', 'tt_content', 'uid=2 AND colPos=3 AND sorting=512');

        $this->assertSame(1, $count);
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
            'move' => 2,
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start($dataMap, $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $count = $this->getDatabaseConnection()->selectCount('*', 'tt_content', 'uid=2 AND colPos=0');

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
            'move' => 2,
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start($dataMap, $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $count = $this->getDatabaseConnection()->selectCount('*', 'tt_content', 'uid=3 AND colPos=0');

        $this->assertSame(0, $count);
    }

    /**
     * @test
     */
    public function moveCommandMovesOnlyOneRecordToMaxitemsColPos()
    {
        $commandMap['tt_content'] = [
            2 => [
                'move' => 3,
            ],
            3 => [
                'move' => 3,
            ],
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start([], $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $count = $this->getDatabaseConnection()->selectCount('*', 'tt_content', 'pid=3 AND colPos=3');

        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function moveCommandMovesRecordToMaxitemsColPosWithoutError()
    {
        $dataMap['tt_content'][5] = [
            'colPos' => 3,
        ];

        $commandMap['tt_content'][5] = [
            'move' => 4,
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start($dataMap, $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $count = $this->getDatabaseConnection()->selectCount('*', 'tt_content', 'uid=5 AND colPos=3');

        $this->assertSame(1, $count);
        $this->assertNoProcessingErrorsInDataHandler($dataHandler);
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
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $recordUid = $dataHandler->copyMappingArray['tt_content'][2];
        $count = $this->getDatabaseConnection()->selectCount('*', 'tt_content', 'uid=' . $recordUid . ' AND colPos=0');

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
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $recordUid = $dataHandler->copyMappingArray['tt_content'][2];
        $count = $this->getDatabaseConnection()->selectCount('*', 'tt_content', 'uid=' . $recordUid . ' AND colPos=0');

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
        $dataHandler->process_datamap();
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
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $this->assertEmpty($dataHandler->copyMappingArray['tt_content'][3]);
    }

    /**
     * @test
     */
    public function copyCommandCopiesOnlyOneRecordToMaxitemsColPos()
    {
        $_GET['CB']['paste'] = '|3';
        $commandMap['tt_content'] = [
            2 => [
                'copy' => 3,
            ],
            3 => [
                'copy' => 3,
            ],
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start([], $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $count = $this->getDatabaseConnection()->selectCount('*', 'tt_content', 'pid=3 AND colPos=3');

        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function copyCommandCopiesAllowedRecordToEmptyMaxitemsColpos()
    {
        $commandMap['tt_content'][4] = [
            'copy' => [
                'action' => 'paste',
                'target' => 3,
                'update' => [
                    'colPos' => '3',
                ],
            ],
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start([], $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $recordUid = $dataHandler->copyMappingArray['tt_content'][4];
        $count = $this->getDatabaseConnection()->selectCount('*', 'tt_content', 'uid=' . $recordUid . ' AND colPos=3');

        $this->assertSame(1, $count);
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
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $count = $this->getDatabaseConnection()->selectCount('*', 'tt_content', 'deleted=1');

        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function localizeCommandTranslatesRecordInLoadedColumn()
    {
        $commandMap['tt_content'][4] = [
            'localize' => 2,
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start([], $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $count = $this->getDatabaseConnection()->selectCount('*', 'tt_content', 'pid=3 AND sys_language_uid=2 AND colPos=3');

        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function copyToLanguageCommandTranslatesRecordInLoadedColumn()
    {
        $commandMap['tt_content'][4] = [
            'copyToLanguage' => 2,
        ];

        $dataHandler = new DataHandler();
        $dataHandler->start([], $commandMap);
        $dataHandler->process_datamap();
        $dataHandler->process_cmdmap();

        $count = $this->getDatabaseConnection()->selectCount('*', 'tt_content', 'pid=3 AND sys_language_uid=2 AND colPos=3');

        $this->assertSame(1, $count);
    }
}
