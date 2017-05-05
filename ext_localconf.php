<?php
defined('TYPO3_MODE') or die('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][\IchHabRecht\ContentDefender\Form\FormDataProvider\TcaCTypeItems::class] = [
    'depends' => [
        \TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRowDefaultValues::class,
    ],
    'before' => [
        \TYPO3\CMS\Backend\Form\FormDataProvider\TcaSelectItems::class,
    ],
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['content_defender'] =
    \IchHabRecht\ContentDefender\Hooks\CTypeDataHandlerHook::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass']['content_defender'] =
    \IchHabRecht\ContentDefender\Hooks\CTypeDataHandlerHook::class;
