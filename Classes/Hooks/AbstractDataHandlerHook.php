<?php
namespace IchHabRecht\ContentDefender\Hooks;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractDataHandlerHook
{
    /**
     * @var array
     */
    protected static $colPosCount = [];

    /**
     * @param array $columnConfiguration
     * @param array $record
     * @return bool
     */
    protected function isRecordAllowedByRestriction(array $columnConfiguration, array $record)
    {
        if (empty($columnConfiguration['allowed.']) && empty($columnConfiguration['disallowed.'])) {
            return true;
        }

        $allowedConfiguration = $columnConfiguration['allowed.'] ?? [];
        foreach ($allowedConfiguration as $field => $value) {
            $allowedValues = GeneralUtility::trimExplode(',', $value);
            if (!$this->isAllowedValue($record, $field, $allowedValues)) {
                return false;
            }
        }

        $disallowedConfiguration = $columnConfiguration['disallowed.'] ?? [];
        foreach ($disallowedConfiguration as $field => $value) {
            $disallowedValues = GeneralUtility::trimExplode(',', $value);
            if (!$this->isAllowedValue($record, $field, $disallowedValues, false)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $record
     * @param string $field
     * @param array $values
     * @param bool $allowed
     * @return bool
     */
    protected function isAllowedValue(array $record, $field, array $values, $allowed = true)
    {
        return !isset($record[$field])
            || ($allowed && in_array($record[$field], $values))
            || (!$allowed && !in_array($record[$field], $values));
    }

    /**
     * @param array $columnConfiguration
     * @param array $record
     * @return bool
     */
    protected function isRecordAllowedByItemsCount(array $columnConfiguration, array $record)
    {
        if (empty($columnConfiguration['maxitems'])) {
            return true;
        }

        $identifier = $this->getIdentifierForRecord($record);

        if (!isset(self::$colPosCount[$identifier])) {
            $languageField = $GLOBALS['TCA']['tt_content']['ctrl']['languageField'];
            list($pageId, $colPos, $language) = explode('/', $identifier);
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
            $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);
            $count = $queryBuilder->count('*')
                ->from('tt_content')
                ->where(
                    $queryBuilder->expr()->eq(
                        'pid',
                        $queryBuilder->createNamedParameter($pageId, \PDO::PARAM_INT)
                    ),
                    $queryBuilder->expr()->eq(
                        'colPos',
                        $queryBuilder->createNamedParameter($colPos, \PDO::PARAM_INT)
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
            self::$colPosCount[$identifier] = $count;
        }

        return (int)$columnConfiguration['maxitems'] >= ++self::$colPosCount[$identifier];
    }

    /**
     * @param array $record
     * @return string
     */
    protected function getIdentifierForRecord(array $record)
    {
        $pageId = $record['pid'];
        $colPos = $record['colPos'];
        $languageField = $GLOBALS['TCA']['tt_content']['ctrl']['languageField'];
        $language = $record[$languageField];

        return $pageId . '/' . $colPos . '/' . $language;
    }
}
