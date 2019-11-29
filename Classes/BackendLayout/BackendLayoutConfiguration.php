<?php
declare(strict_types = 1);
namespace IchHabRecht\ContentDefender\BackendLayout;

/*
 * This file is part of the TYPO3 extension content_defender.
 *
 * (c) Nicole Cordes <typo3@cordes.co>
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

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

    public function __construct(array $backendLayout)
    {
        $this->backendLayout = $backendLayout;
    }

    /**
     * @param int $pageId
     * @return BackendLayoutConfiguration
     */
    public static function createFromPageId($pageId)
    {
        $backendLayoutView = GeneralUtility::makeInstance(BackendLayoutView::class);
        $backendLayout = $backendLayoutView->getSelectedBackendLayout($pageId);
        if (null === $backendLayout) {
            $backendLayout = [
                'config' => '',
            ];
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
        if (isset(self::$columnConfiguration[$configurationIdentifier][$colPos])) {
            return self::$columnConfiguration[$configurationIdentifier][$colPos];
        }

        if (empty($this->backendLayout['__config']['backend_layout.']['rowCount'])
            || empty($this->backendLayout['__config']['backend_layout.']['colCount'])
            || !in_array($colPos, array_map('intval', $this->backendLayout['__colPosList']), true)
        ) {
            return self::$columnConfiguration[$configurationIdentifier][$colPos] = [];
        }

        $configuration = [];
        foreach ($this->backendLayout['__config']['backend_layout.']['rows.'] as $row) {
            if (empty($row['columns.'])) {
                continue;
            }

            foreach ($row['columns.'] as $column) {
                if (isset($column['colPos'])
                    && $column['colPos'] !== ''
                    && $colPos === (int)$column['colPos']
                ) {
                    $configuration = $column;
                    break 2;
                }
            }
        }

        return self::$columnConfiguration[$configurationIdentifier][$colPos] = $configuration;
    }
}
