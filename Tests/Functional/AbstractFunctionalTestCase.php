<?php

declare(strict_types=1);

namespace IchHabRecht\ContentDefender\Tests\Functional;

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

use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractFunctionalTestCase extends FunctionalTestCase
{
    /**
     * @var array
     */
    protected $coreExtensionsToLoad = [
        'fluid_styled_content',
    ];

    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/content_defender',
    ];

    protected function setUp()
    {
        parent::setUp();

        $this->importDataSet('ntf://Database/sys_language.xml');

        $fixturePath = ORIGINAL_ROOT . 'typo3conf/ext/content_defender/Tests/Functional/Fixtures/Database/';
        $this->importDataSet($fixturePath . 'pages.xml');
        $this->importDataSet($fixturePath . 'tt_content.xml');
        if (!empty($GLOBALS['TCA']['pages_language_overlay'])) {
            $this->importDataSet($fixturePath . 'pages_language_overlay.xml');
        }

        ExtensionManagementUtility::addPageTSConfig(
            '<INCLUDE_TYPOSCRIPT: source="DIR:EXT:content_defender/Tests/Functional/Fixtures/TSconfig/BackendLayouts" extensions="ts">'
        );

        $this->setUpBackendUserFromFixture(1);
        if (!method_exists(Bootstrap::class, 'getInstance')) {
            Bootstrap::initializeLanguageObject();
        } else {
            // TODO: 8.7 legacy support
            Bootstrap::getInstance()->initializeLanguageObject();
        }
    }

    protected function assertNoProcessingErrorsInDataHandler(DataHandler $dataHandler)
    {
        $dataHandler->printLogErrorMessages();
        $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
        $flashMessageQueue = $flashMessageService->getMessageQueueByIdentifier();

        $this->assertSame(0, count($flashMessageQueue->getAllMessages()));
    }

    /**
     * @param array $input
     * @param array $defaultValues
     * @return array
     */
    protected function mergeDefaultValuesWithCompilerInput(array $input, array $defaultValues)
    {
        if (version_compare(TYPO3_version, '10', '>=')) {
            $input = array_merge($input, ['defaultValues' => $defaultValues]);
        } else {
            // TODO: 8.7 legacy support
            if (!isset($_GET['defVals'])) {
                $_GET['defVals'] = [];
            }
            $_GET['defVals'] = array_merge($_GET['defVals'], $defaultValues);
        }

        return $input;
    }
}
