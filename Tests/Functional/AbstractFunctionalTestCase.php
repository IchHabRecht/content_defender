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

use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\WorkspaceRestriction;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

abstract class AbstractFunctionalTestCase extends FunctionalTestCase
{
    protected string $versionBranch;

    use ProphecyTrait;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->configurationToUseInTestInstance = [
            'SYS' => [
                'exceptionalErrors' => 12290,
            ],
        ];

        $this->coreExtensionsToLoad = [
            'fluid_styled_content',
        ];

        $this->testExtensionsToLoad = [
            'typo3conf/ext/content_defender',
        ];

        $this->versionBranch = (new Typo3Version())->getBranch();

        parent::__construct($name, $data, $dataName);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $serverRequest = $this->prophesize(ServerRequestInterface::class);
        $serverRequest->getAttribute('applicationType')->willReturn(2);
        $serverRequest->getAttribute('frontend.user')->willReturn(null);
        $serverRequest->getAttribute('normalizedParams')->willReturn(null);
        $serverRequest->getAttribute('route')->willReturn(null);
        $serverRequest->getServerParams()->willReturn([]);
        $GLOBALS['TYPO3_REQUEST'] = $serverRequest->reveal();

        $fixturePath = ORIGINAL_ROOT . 'typo3conf/ext/content_defender/Tests/Functional/Fixtures/Database/';
        $this->importCSVDataSet($fixturePath . 'be_users.csv');
        $this->importCSVDataSet($fixturePath . 'pages.csv');
        $this->importCSVDataSet($fixturePath . 'tt_content.csv');

        if (!empty($GLOBALS['TCA']['sys_language'])) {
            $this->importCSVDataSet($fixturePath . 'sys_language.csv');
        }

        ExtensionManagementUtility::addPageTSConfig(
            '<INCLUDE_TYPOSCRIPT: source="DIR:EXT:content_defender/Tests/Functional/Fixtures/TSconfig/BackendLayouts" extensions="ts">'
        );

        $this->setUpFrontendPage(1);

        $this->setUpBackendUser(1);
        $GLOBALS['LANG'] = GeneralUtility::makeInstance(LanguageServiceFactory::class)->create('default');
    }

    protected function assertNoProcessingErrorsInDataHandler(DataHandler $dataHandler)
    {
        $dataHandler->printLogErrorMessages();
        $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
        $flashMessageQueue = $flashMessageService->getMessageQueueByIdentifier();

        $this->assertSame(0, count($flashMessageQueue->getAllMessages()));
    }

    protected function getQueryBuilderForTable(string $table)
    {
        $context = GeneralUtility::makeInstance(Context::class);
        $workspaceId = $context->getPropertyFromAspect('workspace', 'id');
        $queryBuilder = $this->getConnectionPool()->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class))
            ->add(GeneralUtility::makeInstance(WorkspaceRestriction::class, (int)$workspaceId, true));

        return $queryBuilder;
    }

    /**
     * @param array $input
     * @param array $defaultValues
     * @return array
     */
    protected function mergeDefaultValuesWithCompilerInput(array $input, array $defaultValues)
    {
        return array_merge($input, ['defaultValues' => $defaultValues]);
    }

    protected function setUpFrontendPage($pageId, array $typoScriptFiles = [], array $templateValues = [])
    {
        parent::setUpFrontendRootPage($pageId, $typoScriptFiles, $templateValues);

        $path = Environment::getConfigPath() . '/sites/page_' . $pageId . '/';
        $target = $path . 'config.yaml';
        $file = ORIGINAL_ROOT . 'typo3conf/ext/content_defender/Tests/Functional/Fixtures/Frontend/site.yaml';
        if (!file_exists($target)) {
            GeneralUtility::mkdir_deep($path);
            $fileContent = file_get_contents($file);
            $fileContent = str_replace('\'{rootPageId}\'', (string)$pageId, $fileContent);
            GeneralUtility::writeFile($target, $fileContent);
        }
    }
}
