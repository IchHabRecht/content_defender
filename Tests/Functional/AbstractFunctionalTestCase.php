<?php
namespace IchHabRecht\ContentDefender\Tests\Functional\Hooks;

use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Core\Core\Bootstrap;

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

    public function setUp()
    {
        parent::setUp();

        $fixturePath = ORIGINAL_ROOT . 'typo3conf/ext/content_defender/Tests/Functional/Fixtures/';
        $this->importDataSet($fixturePath . 'backend_layout.xml');
        $this->importDataSet($fixturePath . 'pages.xml');
        $this->importDataSet($fixturePath . 'tt_content.xml');

        $this->setUpBackendUserFromFixture(1);
        Bootstrap::getInstance()->initializeLanguageObject();
    }
}
