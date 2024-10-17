<?php

declare(strict_types=1);

namespace IchHabRecht\ContentDefender\Tests\Functional\Fixtures\Classes\ContentElement;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\JsonResponse;

class NewContentElementController extends \TYPO3\CMS\Backend\Controller\ContentElement\NewContentElementController
{
    public function wizardAction(ServerRequestInterface $request = null): ResponseInterface
    {
        return new JsonResponse([
            'wizardItems' => $this->getWizards(),
        ]);
    }
}
