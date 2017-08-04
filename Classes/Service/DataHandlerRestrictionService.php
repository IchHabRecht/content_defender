<?php

namespace IchHabRecht\ContentDefender\Service;


use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DataHandlerRestrictionService implements SingletonInterface
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
    public function isRecordAllowedByRestriction(array $columnConfiguration, array $record)
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
    public function isRecordAllowedByItemsCount(array $columnConfiguration, array $record)
    {
        if (empty($columnConfiguration['maxitems'])) {
            return true;
        }

        $identifier = $this->getIdentifierForRecord($record);

        if (!isset(self::$colPosCount[$identifier])) {
            $languageField = $GLOBALS['TCA']['tt_content']['ctrl']['languageField'];
            list($pageId, $colPos, $language) = explode('/', $identifier);
            $count = $this->getDatabaseConnection()->exec_SELECTcountRows(
                '*',
                'tt_content',
                'pid=' . (int)$pageId
                . ' AND colPos=' . (int)$colPos
                . ' AND ' . $languageField . '=' . (int)$language
                . ' AND uid!=' . (int)$record['uid']
                . BackendUtility::deleteClause('tt_content')
            );

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

    /**
     * @return DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}