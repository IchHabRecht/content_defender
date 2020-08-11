<?php

declare(strict_types=1);

$tempColumns = [
    'tx_simpleselectboxsingle' => [
        'label' => 'Simple select box',
        'exclude' => '1',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'itemsProcFunc' => \IchHabRecht\ContentDefender\Tests\Functional\Fixtures\Classes\Hooks\SimpleSelectboxSingleHook::class . '->addSimpleSelectboxItems',
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'tx_simpleselectboxsingle');
