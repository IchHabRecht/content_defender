<?php

namespace IchHabRecht\ContentDefender\ViewHelpers;

use IchHabRecht\ContentDefender\BackendLayout\BackendLayoutConfiguration;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumn;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class MaxitemsViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerArgument('column', 'TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumn', 'Current column object', true);
    }

    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $maxitems = -9999;

        /** @var GridColumn $column */
        $column = $arguments['column'];
        $pageId = $column->getContext()->getPageId();
        $colPos = $column->getColumnNumber();
        $language = $column->getContext()->getSiteLanguage()->getLanguageId();

        $identifier = implode('_', [$pageId, $language, $colPos]);

        $viewHelperVariableContainer = $renderingContext->getViewHelperVariableContainer();
        if ($viewHelperVariableContainer->exists(self::class, $identifier)) {
            return $viewHelperVariableContainer->get(self::class, $identifier);
        }

        $backendLayoutConfiguration = BackendLayoutConfiguration::createFromPageId($pageId);
        $columnConfiguration = $backendLayoutConfiguration->getConfigurationByColPos($colPos);

        if (!empty($columnConfiguration['maxitems'])) {
            $maxitems = $columnConfiguration['maxitems'] - count($column->getItems());
        }

        $viewHelperVariableContainer->add(self::class, $identifier, $maxitems);

        return $maxitems;
    }
}
