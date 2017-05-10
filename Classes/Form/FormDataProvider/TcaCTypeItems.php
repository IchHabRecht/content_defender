<?php
namespace IchHabRecht\ContentDefender\Form\FormDataProvider;

use IchHabRecht\ContentDefender\BackendLayout\BackendLayoutConfiguration;
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TcaCTypeItems implements FormDataProviderInterface
{
    /**
     * @param array $result
     * @return array
     */
    public function addData(array $result)
    {
        if ('tt_content' !== $result['tableName']) {
            return $result;
        }

        $pageId = !empty($result['effectivePid']) ? (int)$result['effectivePid'] : (int)$result['databaseRow']['pid'];
        $backendLayoutConfiguration = BackendLayoutConfiguration::createFromPageId($pageId);

        $colPos = (int)$result['databaseRow']['colPos'];
        $columnConfiguration = $backendLayoutConfiguration->getConfigurationByColPos($colPos);
        if (empty($columnConfiguration) || (empty($columnConfiguration['allowed']) && empty($columnConfiguration['disallowed']))) {
            return $result;
        }

        if (!empty($columnConfiguration['allowed'])) {
            $cTypes = GeneralUtility::trimExplode(',', $columnConfiguration['allowed']);
            $result['processedTca']['columns']['CType']['config']['items'] = array_filter(
                $result['processedTca']['columns']['CType']['config']['items'],
                function ($item) use ($cTypes) {
                    return in_array($item[1], $cTypes);
                }
            );
        } else {
            $cTypes = GeneralUtility::trimExplode(',', $columnConfiguration['disallowed']);
            $result['processedTca']['columns']['CType']['config']['items'] = array_filter(
                $result['processedTca']['columns']['CType']['config']['items'],
                function ($item) use ($cTypes) {
                    return !in_array($item[1], $cTypes);
                }
            );
        }

        return $result;
    }
}
