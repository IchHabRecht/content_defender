<?php

declare(strict_types=1);

namespace IchHabRecht\ContentDefender\Tests\Functional\Hooks;

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

require_once __DIR__ . '/../AbstractFunctionalTestCase.php';

use IchHabRecht\ContentDefender\Hooks\WizardItemsHook;
use IchHabRecht\ContentDefender\Tests\Functional\AbstractFunctionalTestCase;
use IchHabRecht\ContentDefender\Tests\Functional\Fixtures\Classes\ContentElement\NewContentElementController;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Controller\Event\ModifyNewContentElementWizardItemsEvent;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

class WizardItemsHookTest extends AbstractFunctionalTestCase
{
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->coreExtensionsToLoad = [
            'fluid_styled_content',
            'indexed_search',
        ];

        parent::__construct($name, $data, $dataName);
    }

    /**
     * @return array
     */
    public function manipulateWizardItemsFiltersWizardItemsDataProvider()
    {
        return [
            'Border (all)' => [
                3,
                14,
            ],
            'Normal (header, textmedia, list[-indexed_search_pi2])' => [
                0,
                6,
            ],
            'Footer2 (bullets)' => [
                11,
                2,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider manipulateWizardItemsFiltersWizardItemsDataProvider
     * @param int $colPos
     * @param int $expectedCount
     */
    public function modifyWizardItemsFiltersWizardItems($colPos, $expectedCount)
    {
        if (version_compare(VersionNumberUtility::getNumericTypo3Version(), '12', '<')) {
            $this->markTestSkipped('The event gets called in TYPO3 >= 12 only.');
        }

        $request = $this->getRequestForColPos($colPos);

        $newContentElementController = GeneralUtility::makeInstance(NewContentElementController::class);
        $response = $newContentElementController->getWizardArray($request);
        $wizardItems = json_decode((string)$response->getBody(), true)['wizardItems'];

        $event = new ModifyNewContentElementWizardItemsEvent(
            $wizardItems,
            [
                'uid' => 2,
            ],
            $colPos,
            0,
            0
        );

        $wizardItemsHook = new WizardItemsHook();
        $wizardItemsHook->modifyWizardItems($event);

        $this->assertCount($expectedCount, $event->getWizardItems());
    }

    /**
     * @test
     * @dataProvider manipulateWizardItemsFiltersWizardItemsDataProvider
     * @param int $colPos
     * @param int $expectedCount
     */
    public function manipulateWizardItemsFiltersWizardItems($colPos, $expectedCount)
    {
        if (version_compare(VersionNumberUtility::getNumericTypo3Version(), '11', '>')) {
            $this->markTestSkipped('The hook gets called in TYPO3 < 12 only.');
        }

        $request = $this->getRequestForColPos($colPos);

        // TODO: $GLOBALS['TYPO3_REQUEST'] needs to be set for NewContentElementController initialization
        $GLOBALS['TYPO3_REQUEST'] = $request;
        $newContentElementController = GeneralUtility::makeInstance(NewContentElementController::class);
        $GLOBALS['TYPO3_REQUEST'] = null;
        $response = $newContentElementController->getWizardArray($request);
        $wizardItems = json_decode((string)$response->getBody(), true)['wizardItems'];

        $this->assertNotEmpty($wizardItems);

        $wizardItemsHook = new WizardItemsHook();
        $wizardItemsHook->manipulateWizardItems($wizardItems, $newContentElementController);

        $this->assertCount($expectedCount, $wizardItems);
    }

    protected function getRequestForColPos(int $colPos): ServerRequestInterface
    {
        ExtensionManagementUtility::addPageTSConfig(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:content_defender/Tests/Functional/Fixtures/TSconfig/NewContentElementWizard.ts">'
        );

        $serverRequest = $this->prophesize(ServerRequestInterface::class);
        $serverRequest->getParsedBody()->willReturn([
            'id' => 2,
            'colPos' => $colPos,
            'action' => 'wizard',
        ]);
        $serverRequest->getQueryParams()->willReturn([]);
        $serverRequest->getAttribute('applicationType')->willReturn(2);
        $serverRequest->getAttribute('route')->willReturn(null);

        return $serverRequest->reveal();
    }
}
