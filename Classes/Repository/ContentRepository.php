<?php
declare(strict_types=1);
namespace IchHabRecht\ContentDefender\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ContentRepository
{
    /**
     * @var int[][]
     */
    protected static $colPosCount = [];

    public function countColPosByRecord(array $record): int
    {
        $identifier = $this->getIdentifier($record);

        if (!isset(self::$colPosCount[$identifier])) {
            $this->initialize($record);
        }

        return count(self::$colPosCount[$identifier]);
    }

    public function addRecordToColPos(array $record): int
    {
        $identifier = $this->getIdentifier($record);

        if (!isset(self::$colPosCount[$identifier])) {
            $this->initialize($record);
        }
        self::$colPosCount[$identifier][$record['uid']] = $record['uid'];

        return count(self::$colPosCount[$identifier]);
    }

    public function removeRecordFromColPos(array $record): int
    {
        $identifier = $this->getIdentifier($record);

        if (!isset(self::$colPosCount[$identifier])) {
            $this->initialize($record);
        }
        if (isset(self::$colPosCount[$identifier][$record['uid']])) {
            unset(self::$colPosCount[$identifier][$record['uid']]);
        }

        return count(self::$colPosCount[$identifier]);
    }

    public function isRecordInColPos(array $record): bool
    {
        $identifier = $this->getIdentifier($record);

        if (!isset(self::$colPosCount[$identifier])) {
            $this->initialize($record);
        }

        return isset(self::$colPosCount[$identifier][$record['uid']]);
    }

    public function substituteNewIdsWithUids(array $newIdUidArray)
    {
        if (empty($newIdUidArray)) {
            return;
        }

        foreach (self::$colPosCount as $identifier => $uidArray) {
            $intersect = array_intersect_key($newIdUidArray, $uidArray);
            if (empty($intersect)) {
                continue;
            }
            self::$colPosCount[$identifier] = array_replace(
                array_diff_key($uidArray, $newIdUidArray),
                array_combine($intersect, $intersect)
            );
        }
    }

    /**
     * @param array $record
     * @param int $inc
     * @return int
     * @deprecated since version 3.0.4, will be removed in version 4.0.0
     */
    public function increaseColPosCountByRecord(array $record, int $inc = 1): int
    {
        trigger_error(
            'Method "increaseColPosCountByRecord" is deprecated since version 3.0.4, will be removed in version 4.0.0',
            E_USER_DEPRECATED
        );
        $identifier = $this->getIdentifier($record);

        if (!isset(self::$colPosCount[$identifier])) {
            $this->initialize($record);
        }
        self::$colPosCount[$identifier] = array_merge(
            self::$colPosCount[$identifier],
            array_fill(0, $inc, 0)
        );

        return count(self::$colPosCount[$identifier]);
    }

    /**
     * @param array $record
     * @param int $dec
     * @return int
     * @deprecated since version 3.0.4, will be removed in version 4.0.0
     */
    public function decreaseColPosCountByRecord(array $record, int $dec = 1): int
    {
        trigger_error(
            'Method "decreaseColPosCountByRecord" is deprecated since version 3.0.4, will be removed in version 4.0.0',
            E_USER_DEPRECATED
        );
        $identifier = $this->getIdentifier($record);

        if (!isset(self::$colPosCount[$identifier])) {
            $this->initialize($record);
        }
        if (!empty($record['uid']) && isset(self::$colPosCount[$identifier][$record['uid']])) {
            unset(self::$colPosCount[$identifier][$record['uid']]);
            $dec -= 1;
        }
        while ($dec > 0 && in_array(0, self::$colPosCount[$identifier], true)) {
            $index = array_search(0, self::$colPosCount[$identifier], true);
            unset(self::$colPosCount[$identifier][$index]);
            $dec -= 1;
        }

        return count(self::$colPosCount[$identifier]);
    }

    protected function initialize(array $record)
    {
        $result = $this->fetchRecordsForColPos($record);

        self::$colPosCount[$this->getIdentifier($record)] = array_combine($result, $result);
    }

    protected function fetchRecordsForColPos(array $record): array
    {
        $languageField = $GLOBALS['TCA']['tt_content']['ctrl']['languageField'];
        $language = (array)$record[$languageField];

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);

        return $queryBuilder->select('uid')
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
                    $queryBuilder->createNamedParameter($language[0], \PDO::PARAM_INT)
                )
            )
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);
    }

    protected function getIdentifier(array $record): string
    {
        $languageField = $GLOBALS['TCA']['tt_content']['ctrl']['languageField'];
        $language = (array)$record[$languageField];

        return $record['pid'] . '/' . $language[0] . '/' . $record['colPos'];
    }
}
