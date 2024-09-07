<?php

declare(strict_types=1);

namespace IchHabRecht\ContentDefender\Hooks;

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

use IchHabRecht\ContentDefender\Repository\ContentRepository;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Form\FormDataCompiler;
use TYPO3\CMS\Backend\Form\FormDataGroup\OnTheFly;
use TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRecordTypeValue;
use TYPO3\CMS\Backend\Form\FormDataProvider\InitializeProcessedTca;
use TYPO3\CMS\Backend\Form\FormDataProvider\PageTsConfig;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaColumnsProcessCommon;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaColumnsProcessShowitem;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaColumnsRemoveUnused;
use TYPO3\CMS\Backend\Form\FormDataProvider\UserTsConfig;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractDataHandlerHook
{
    /**
     * @var ContentRepository
     */
    protected $contentRepository;

    protected string $versionBranch;

    /**
     * @param ContentRepository $contentRepository
     */
    public function __construct(ContentRepository $contentRepository = null)
    {
        $this->contentRepository = $contentRepository ?? GeneralUtility::makeInstance(ContentRepository::class);
        $this->versionBranch = (new Typo3Version())->getBranch();
    }

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

        if (!($GLOBALS['TYPO3_REQUEST'] ?? null instanceof ServerRequestInterface) && version_compare($this->versionBranch, '13', '>=')) {
            return true;
        }

        $formDataGroup = GeneralUtility::makeInstance(OnTheFly::class);
        $formDataGroup->setProviderList(
            [
                UserTsConfig::class,
                PageTsConfig::class,
                InitializeProcessedTca::class,
                DatabaseRecordTypeValue::class,
                TcaColumnsProcessCommon::class,
                TcaColumnsProcessShowitem::class,
                TcaColumnsRemoveUnused::class,
            ]
        );
        $formDataCompiler = GeneralUtility::makeInstance(FormDataCompiler::class, $formDataGroup);
        $formDataCompilerInput = [
            'command' => 'edit',
            'request' => $GLOBALS['TYPO3_REQUEST'] ?? null,
            'databaseRow' => $record,
            'effectivePid' => $record['pid'],
            'tableName' => 'tt_content',
            'vanillaUid' => (int)$record['uid'],
        ];

        if (version_compare($this->versionBranch, '13', '<')) {
            unset($formDataCompilerInput['request']);
        }

        $result = $formDataCompiler->compile($formDataCompilerInput, $formDataGroup);

        $allowedConfiguration = array_intersect_key($columnConfiguration['allowed.'] ?? [], $result['processedTca']['columns']);
        foreach ($allowedConfiguration as $field => $value) {
            $allowedValues = GeneralUtility::trimExplode(',', $value);
            if (!$this->isAllowedValue($record, $field, $allowedValues)) {
                return false;
            }
        }

        $disallowedConfiguration = array_intersect_key($columnConfiguration['disallowed.'] ?? [], $result['processedTca']['columns']);
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

        return (int)$columnConfiguration['maxitems'] >= $this->contentRepository->addRecordToColPos($record);
    }
}
