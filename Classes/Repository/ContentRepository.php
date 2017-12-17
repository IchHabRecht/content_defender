<?php
declare(strict_types=1);
namespace IchHabRecht\ContentDefender\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ContentRepository
{
    /**
     * @var int[]
     */
    protected static $colPosCount = [];

    /**
     * @param array $record
     * @return int
     */
    public function countColPosByRecord(array $record): int
    {
        $identifier = $this->getIdentifier($record);

        if (!isset(self::$colPosCount[$identifier])) {
            $this->initialize($record);
        }

        return self::$colPosCount[$identifier];
    }

    public function increaseColPosCountByRecord(array $record, int $inc = 1): int
    {
        $identifier = $this->getIdentifier($record);

        if (!isset(self::$colPosCount[$identifier])) {
            $this->initialize($record);
        }

        return self::$colPosCount[$identifier] += $inc;
    }

    public function decreaseColPosCountByRecord(array $record, int $dec = 1): int
    {
        $identifier = $this->getIdentifier($record);

        if (!isset(self::$colPosCount[$identifier])) {
            $this->initialize($record);
        }

        return self::$colPosCount[$identifier] -= $dec;
    }

    protected function initialize(array $record)
    {
        $languageField = $GLOBALS['TCA']['tt_content']['ctrl']['languageField'];
        $language = (int)$record[$languageField][0];

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);
        $count = $queryBuilder->count('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq(
                    'pid',
                    $queryBuilder->createNamedParameter($record['pid'], \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'colPos',
                    $queryBuilder->createNamedParameter($record['colPos'], \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    $languageField,
                    $queryBuilder->createNamedParameter($language, \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->neq(
                    'uid',
                    $queryBuilder->createNamedParameter($record['uid'], \PDO::PARAM_INT)
                )
            )
            ->execute()
            ->fetchColumn();

        self::$colPosCount[$this->getIdentifier($record)] = (int)$count;
    }

    protected function getIdentifier(array $record): string
    {
        $languageField = $GLOBALS['TCA']['tt_content']['ctrl']['languageField'];
        $language = (int)$record[$languageField][0];

        return $record['pid'] . '/' . $language . '/' . $record['colPos'];
    }
}
