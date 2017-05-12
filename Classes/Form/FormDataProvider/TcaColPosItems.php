<?php
namespace IchHabRecht\ContentDefender\Form\FormDataProvider;

use IchHabRecht\ContentDefender\BackendLayout\BackendLayoutConfiguration;
use IchHabRecht\ContentDefender\Form\Exception\AccessDeniedColPosException;
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\DatabaseConnection;

class TcaColPosItems implements FormDataProviderInterface
{
    /**
     * @var array
     */
    protected static $colPosCount = [];

    /**
     * @param array $result
     * @return array
     */
    public function addData(array $result)
    {
        if ('tt_content' !== $result['tableName']
            || empty($result['processedTca']['columns']['colPos']['config']['items'])
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
            if (empty($columnConfiguration) || empty($columnConfiguration['maxitems'])) {
                continue;
            }

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
