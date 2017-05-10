<?php
namespace IchHabRecht\ContentDefender\BackendLayout;

use TYPO3\CMS\Backend\View\BackendLayoutView;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class BackendLayoutConfiguration
{
    /**
     * @var array
     */
    private $backendLayout;

    /**
     * @var array
     */
    private static $columnConfiguration = [];

    /**
     * @param array $backendLayout
     */
    public function __construct(array $backendLayout)
    {
        $this->backendLayout = $backendLayout;
    }

    /**
     * @param int $pageId
     * @return BackendLayoutConfiguration|null
     */
    public static function createFromPageId($pageId)
    {
        $backendLayoutView = GeneralUtility::makeInstance(BackendLayoutView::class);
        $backendLayout = $backendLayoutView->getSelectedBackendLayout($pageId);
        if (null === $backendLayout) {
            return null;
        }

        return new self($backendLayout);
    }

    /**
     * @param int $colPos
     * @return array
     */
    public function getConfigurationByColPos($colPos)
    {
        $configurationIdentifier = md5($this->backendLayout['config']);
        if (!isset(self::$columnConfiguration[$configurationIdentifier][$colPos])) {
            if (empty($this->backendLayout['__config']['backend_layout.']['rowCount'])
                || empty($this->backendLayout['__config']['backend_layout.']['colCount'])
                || !in_array($colPos, array_map('intval', $this->backendLayout['__colPosList']), true)
            ) {
                self::$columnConfiguration[$configurationIdentifier][$colPos] = [];
            } else {
                $columnConfiguration = [];
                foreach ($this->backendLayout['__config']['backend_layout.']['rows.'] as $row) {
                    if (empty($row['columns.'])) {
                        continue;
                    }

                    foreach ($row['columns.'] as $column) {
                        if ($colPos === (int)$column['colPos']) {
                            $columnConfiguration = $column;
                            break 2;
                        }
                    }
                }
                self::$columnConfiguration[$configurationIdentifier][$colPos] = $columnConfiguration;
            }
        }

        return self::$columnConfiguration[$configurationIdentifier][$colPos];
    }

    /**
     * @param string $cType
     * @param int $colPos
     * @return true
     */
    public function isAllowedCTypeInColPos($cType, $colPos)
    {
        $columnConfiguration = $this->getConfigurationByColPos($colPos);
        if (empty($columnConfiguration) || (empty($columnConfiguration['allowed']) && empty($columnConfiguration['disallowed']))) {
            return true;
        }

        if (!empty($columnConfiguration['allowed'])) {
            $cTypes = GeneralUtility::trimExplode(',', $columnConfiguration['allowed']);
            $allowed = in_array($cType, $cTypes, true);
        } else {
            $cTypes = GeneralUtility::trimExplode(',', $columnConfiguration['disallowed']);
            $allowed = !in_array($cType, $cTypes, true);
        }

        return $allowed;
    }
}
