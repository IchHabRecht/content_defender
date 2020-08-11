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
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class WizardItemsHookTest extends AbstractFunctionalTestCase
{
    /**
     * @var array
     */
    protected $coreExtensionsToLoad = [
        'fluid_styled_content',
        'indexed_search',
    ];

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
    public function manipulateWizardItemsFiltersWizardItems($colPos, $expectedCount)
    {
        ExtensionManagementUtility::addPageTSConfig(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:content_defender/Tests/Functional/Fixtures/TSconfig/NewContentElementWizard.ts">'
        );

        // TODO: 8.7 legacy support
        $_GET['id'] = 2;
        $_GET['colPos'] = $colPos;

        $serverRequest = $this->prophesize(ServerRequestInterface::class);
        $serverRequest->getParsedBody()->willReturn([
            'id' => 2,
            'colPos' => $colPos,
        ]);
        $serverRequest->getQueryParams()->willReturn([]);
        $GLOBALS['TYPO3_REQUEST'] = $serverRequest->reveal();

        $newContentElementController = new NewContentElementController();
        $wizardItems = $newContentElementController->getWizardArray();

        $wizardItemsHook = new WizardItemsHook();
        $wizardItemsHook->manipulateWizardItems($wizardItems, $newContentElementController);

        $this->assertCount($expectedCount, $wizardItems);
    }
}
