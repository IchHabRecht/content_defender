<?php
declare(strict_types=1);
namespace IchHabRecht\ContentDefender\Tests\Functional\Hooks;

require_once __DIR__ . '/../AbstractFunctionalTestCase.php';

use IchHabRecht\ContentDefender\Hooks\WizardItemsHook;
use IchHabRecht\ContentDefender\Tests\Functional\AbstractFunctionalTestCase;
use TYPO3\CMS\Backend\Controller\ContentElement\NewContentElementController;
use TYPO3\CMS\Core\Core\Bootstrap;
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
        $_GET['id'] = 2;
        $_GET['colPos'] = $colPos;

        Bootstrap::getInstance()->initializeLanguageObject();
        $newContentElementController = new NewContentElementController();
        $wizardItems = $newContentElementController->wizardArray();

        $wizardItemsHook = new WizardItemsHook();
        $wizardItemsHook->manipulateWizardItems($wizardItems, $newContentElementController);

        $this->assertCount($expectedCount, $wizardItems);
    }
}
