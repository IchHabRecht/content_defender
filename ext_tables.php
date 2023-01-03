<?php

defined('TYPO3') || die('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][\IchHabRecht\ContentDefender\Form\FormDataProvider\TcaCTypeItems::class] = [
    'depends' => [
        \TYPO3\CMS\Backend\Form\FormDataProvider\TcaSelectItems::class,
    ],
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][\IchHabRecht\ContentDefender\Form\FormDataProvider\TcaColPosItems::class] = [
    'depends' => [
        \IchHabRecht\ContentDefender\Form\FormDataProvider\TcaCTypeItems::class,
    ],
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['content_defender'] =
    \IchHabRecht\ContentDefender\Hooks\DatamapDataHandlerHook::class;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass']['content_defender'] =
    \IchHabRecht\ContentDefender\Hooks\CmdmapDataHandlerHook::class;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms']['db_new_content_el']['wizardItemsHook']['content_defender'] =
    \IchHabRecht\ContentDefender\Hooks\WizardItemsHook::class;
