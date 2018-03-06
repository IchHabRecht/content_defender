<?php
declare(strict_types=1);
namespace IchHabRecht\ContentDefender\Tests\Unit\Repository;

use IchHabRecht\ContentDefender\Repository\ContentRepository;
use Nimut\TestingFramework\TestCase\UnitTestCase;

class ContentRepositoryTest extends UnitTestCase
{
    /**
     * @var ContentRepository
     */
    protected $subject;

    /**
     * @var array
     */
    protected $record = [
        'pid' => 1,
        'colPos' => 0,
        'sys_language_uid' => 0,
        'uid' => 4,
    ];

    protected function setUp()
    {
        parent::setUp();

        $GLOBALS['TCA']['tt_content']['ctrl']['languageField'] = 'sys_language_uid';

        $subject = $this->getMockBuilder(ContentRepository::class)
            ->setMethods(['fetchRecordsForColpos'])
            ->getMock();
        $subject->expects($this->once())
            ->method('fetchRecordsForColpos')
            ->willReturn([
                1 => 1,
                2 => 2,
                3 => 3,
            ]);

        \Closure::bind(function () use ($subject) {
            $subject::$colPosCount = [];
        }, null, ContentRepository::class)();

        $this->subject = $subject;
    }

    /**
     * @test
     */
    public function countColPosByRecordReturnsCountOfRecordsInCurrentColPos()
    {
        $this->assertSame(3, $this->subject->countColPosByRecord($this->record));
    }

    /**
     * @test
     */
    public function addRecordToColPosReturnsNewCountOfRecordsInCurrentColPos()
    {
        $this->assertSame(4, $this->subject->addRecordToColPos($this->record));
    }

    /**
     * @test
     */
    public function isRecordInColPosReturnsTrueForRecordInColPos()
    {
        $record = $this->record;
        $record['uid'] = 1;

        $this->assertTrue($this->subject->isRecordInColPos($record));
    }

    /**
     * @test
     */
    public function isRecordInColPosReturnsFalseForRecordNotInColPos()
    {
        $this->assertFalse($this->subject->isRecordInColPos($this->record));
    }

    /**
     * @test
     */
    public function substituteNewIdsWithUidsReplacesNewIdsWithUids()
    {
        $record = $this->record;
        $record['uid'] = 'NEW123';

        $this->subject->addRecordToColPos($record);
        $this->subject->substituteNewIdsWithUids(['NEW123' => $this->record['uid']]);

        $this->assertSame(4, $this->subject->countColPosByRecord($this->record));
        $this->assertTrue($this->subject->isRecordInColPos($this->record));

        $record['uid'] = 1;
        $this->assertTrue($this->subject->isRecordInColPos($record));
    }
}
