<?php

namespace IchHabRecht\ContentDefender\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class ExtendPartialViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    protected $escapeOutput = false;

    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $packagePath = GeneralUtility::getFileAbsFileName('EXT:content_defender/Resources/Private/');
        $partialPaths = $renderingContext->getTemplatePaths()->getPartialRootPaths();
        $newPartialPaths = array_filter($partialPaths, function ($path) use ($packagePath) {
            return strpos($path, $packagePath) === false;
        });
        $renderingContext->getTemplatePaths()->setPartialRootPaths($newPartialPaths);
        $content = $renderChildrenClosure();
        $renderingContext->getTemplatePaths()->setPartialRootPaths($partialPaths);

        return $content;
    }
}
