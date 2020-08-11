<?php

declare(strict_types=1);

namespace IchHabRecht\ContentDefender\Tests\Functional\Fixtures\Classes\ContentElement;

class NewContentElementController extends \TYPO3\CMS\Backend\Controller\ContentElement\NewContentElementController
{
    public function getWizardArray()
    {
        // TODO: 8.7 legacy support
        if (method_exists($this, 'wizardArray')) {
            return $this->wizardArray();
        }

        $this->init($GLOBALS['TYPO3_REQUEST']);

        return $this->getWizards();
    }
}
