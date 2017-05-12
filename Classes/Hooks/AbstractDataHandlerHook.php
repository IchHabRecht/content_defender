<?php
namespace IchHabRecht\ContentDefender\Hooks;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\DatabaseConnection;
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

        $pageId = $record['pid'];
        $colPos = $record['colPos'];
        $languageField = $GLOBALS['TCA']['tt_content']['ctrl']['languageField'];
        $language = $record[$languageField];

        $identifier = $pageId . '/' . $language . '/' . $colPos;

        if (!isset(self::$colPosCount[$identifier])) {
            $count = $this->getDatabaseConnection()->exec_SELECTcountRows(
                '*',
                'tt_content',
                'pid=' . (int)$pageId
                . ' AND colPos=' . (int)$colPos
                . ' AND ' . $languageField . '=' . (int)$language
                . BackendUtility::deleteClause('tt_content')
            );

            self::$colPosCount[$identifier] = $count;
        }

        return (int)$columnConfiguration['maxitems'] >= ++self::$colPosCount[$identifier];
    }

    /**
     * @return DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
