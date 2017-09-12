<?php
namespace IchHabRecht\ContentDefender\Form\FormDataProvider;

use IchHabRecht\ContentDefender\BackendLayout\BackendLayoutConfiguration;
use IchHabRecht\ContentDefender\Form\Exception\AccessDeniedColPosException;
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TcaColPosItems implements FormDataProviderInterface
{
    /**
     * @var array
     */
    protected static $colPosCount = [];

    /**
     * @param array $result
     * @throws AccessDeniedColPosException
     * @return array
     */
    public function addData(array $result)
    {
        if ('tt_content' !== $result['tableName']
            || empty($result['processedTca']['columns']['colPos']['config']['items'])
            || !empty($result['isInlineChild'])
        ) {
            return $result;
        }

        $pageId = !empty($result['effectivePid']) ? (int)$result['effectivePid'] : (int)$result['databaseRow']['pid'];
        $backendLayoutConfiguration = BackendLayoutConfiguration::createFromPageId($pageId);

        $languageField = $GLOBALS['TCA']['tt_content']['ctrl']['languageField'];
        $language = $result['databaseRow'][$languageField][0];

        foreach ($result['processedTca']['columns']['colPos']['config']['items'] as $key => $item) {
            $colPos = (int)$item[1];
            $columnConfiguration = $backendLayoutConfiguration->getConfigurationByColPos($colPos);
            if (empty($columnConfiguration)) {
                continue;
            }

            if (!empty($columnConfiguration['allowed.'])) {
                foreach ($columnConfiguration['allowed.'] as $field => $value) {
                    if (!isset($result['databaseRow'][$field])) {
                        continue;
                    }

                    $allowedValues = GeneralUtility::trimExplode(',', $value);
                    if (is_array($result['databaseRow'][$field])) {
                        foreach ($result['databaseRow'][$field] as $item) {
                            if (!in_array($item, $allowedValues, true)) {
                                unset($result['processedTca']['columns']['colPos']['config']['items'][$key]);
                                continue 2;
                            }
                        }
                    } elseif (!in_array($result['databaseRow'][$field], $allowedValues, true)) {
                        unset($result['processedTca']['columns']['colPos']['config']['items'][$key]);
                        continue;
                    }
                }
            }
            if (!empty($columnConfiguration['disallowed.'])) {
                foreach ($columnConfiguration['disallowed.'] as $field => $value) {
                    if (!isset($result['databaseRow'][$field])) {
                        continue;
                    }

                    $disallowedValues = GeneralUtility::trimExplode(',', $value);
                    if (is_array($result['databaseRow'][$field])) {
                        foreach ($result['databaseRow'][$field] as $item) {
                            if (in_array($item, $disallowedValues, true)) {
                                unset($result['processedTca']['columns']['colPos']['config']['items'][$key]);
                                continue 2;
                            }
                        }
                    } elseif (in_array($result['databaseRow'][$field], $disallowedValues, true)) {
                        unset($result['processedTca']['columns']['colPos']['config']['items'][$key]);
                        continue;
                    }
                }
            }

            if (!empty($columnConfiguration['maxitems'])) {
                $identifier = $pageId . '/' . $language . '/' . $colPos;

                if (!isset(self::$colPosCount[$identifier])) {
                    $count = $this->getDatabaseConnection()->exec_SELECTcountRows(
                        '*',
                        'tt_content',
                        'pid=' . (int)$pageId
                        . ' AND colPos=' . (int)$colPos
                        . ' AND ' . $languageField . '=' . (int)$language
                        . ' AND uid!=' . (int)$result['databaseRow']['uid']
                        . BackendUtility::deleteClause('tt_content')
                    );

                    self::$colPosCount[$identifier] = $count;
                }

                if ((int)$columnConfiguration['maxitems'] <= self::$colPosCount[$identifier]) {
                    if ($colPos === (int)$result['databaseRow']['colPos'][0]) {
                        throw  new AccessDeniedColPosException(
                            'Maximum number of allowed content elements (' . $columnConfiguration['maxitems'] . ') reached.',
                            1494605357
                        );
                    }
                    unset($result['processedTca']['columns']['colPos']['config']['items'][$key]);
                }
            }
        }

        return $result;
    }

    /**
     * @return DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
