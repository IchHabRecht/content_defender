<?php
namespace IchHabRecht\ContentDefender\Tests\Functional\Hooks;

require_once __DIR__ . '/../AbstractFunctionalTestCase.php';

use IchHabRecht\ContentDefender\Hooks\WizardItemsHook;
use IchHabRecht\ContentDefender\Tests\Functional\AbstractFunctionalTestCase;
use TYPO3\CMS\Backend\Controller\ContentElement\NewContentElementController;
use TYPO3\CMS\Backend\Controller\Wizard\NewContentElementWizardController;
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
        if (version_compare(TYPO3_version, '9.0', '<')) {
            $parentObject = new NewContentElementController();
            $wizardItems = $parentObject->wizardArray();
        } else {
            $parentObject = new NewContentElementWizardController();
            $closure = \Closure::bind(function () use ($parentObject) {
                return $parentObject->getWizardItems();
            }, null, get_class($parentObject));
            $wizardItems = $closure();
        }

        $wizardItemsHook = new WizardItemsHook();
        $wizardItemsHook->manipulateWizardItems($wizardItems, $parentObject);

        $this->assertCount($expectedCount, $wizardItems);
    }
}
