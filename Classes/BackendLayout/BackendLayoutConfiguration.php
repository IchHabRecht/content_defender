<?php

declare(strict_types=1);

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

use IchHabRecht\ContentDefender\Exception\MissingInterfaceException;
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
        // TODO: Mitigate a problem in \TYPO3\CMS\Backend\Configuration\TypoScript\ConditionMatching\ConditionMatcher::determinePageId
        // @see: https://github.com/IchHabRecht/content_defender/issues/91
        if (($_POST['id'] ?? $_GET['id'] ?? null) === null) {
            $_GET['id'] = $pageId;
        }
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
     * @param int|string|null $recordUid
     * @return array
     */
    public function getConfigurationByColPos($colPos, $recordUid = null)
    {
        $configurationIdentifier = md5($this->backendLayout['config']);
        if (isset(self::$columnConfiguration[$configurationIdentifier][$colPos])) {
            return self::$columnConfiguration[$configurationIdentifier][$colPos];
        }

        $configuration = [];
        if (in_array($colPos, array_map('intval', $this->backendLayout['__colPosList']), true)) {
            foreach ($this->backendLayout['__config']['backend_layout.']['rows.'] as $row) {
                if (empty($row['columns.'])) {
                    continue;
                }

                foreach ($row['columns.'] as $column) {
                    if (isset($column['colPos']) && $column['colPos'] !== '' && $colPos === (int)$column['colPos']) {
                        $configuration = $column;
                        break 2;
                    }
                }
            }
        }

        foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['content_defender']['ColumnConfigurationManipulationHook'] ?? [] as $className) {
            $hookObject = GeneralUtility::makeInstance($className);
            if (!$hookObject instanceof ColumnConfigurationManipulationInterface) {
                throw new MissingInterfaceException(
                    'Class ' . $className . ' must implement interface ' . ColumnConfigurationManipulationInterface::class,
                    1597159146
                );
            }
            $configuration = $hookObject->manipulateConfiguration($configuration, $colPos, $recordUid);
        }

        return self::$columnConfiguration[$configurationIdentifier][$colPos] = $configuration;
    }
}
