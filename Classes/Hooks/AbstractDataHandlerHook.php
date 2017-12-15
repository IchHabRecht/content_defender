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

        if (!empty($columnConfiguration['allowed.'])) {
            foreach ($columnConfiguration['allowed.'] as $field => $value) {
                if (!isset($record[$field])) {
                    continue;
                }

                $allowedValues = GeneralUtility::trimExplode(',', $value);
                if (!in_array($record[$field], $allowedValues)) {
                    return false;
                }
            }
        }
        if (!empty($columnConfiguration['disallowed.'])) {
            foreach ($columnConfiguration['disallowed.'] as $field => $value) {
                if (!isset($record[$field])) {
                    continue;
                }

                $disallowedValues = GeneralUtility::trimExplode(',', $value);
                if (in_array($record[$field], $disallowedValues)) {
                    return false;
                }
            }
        }

        return true;
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
