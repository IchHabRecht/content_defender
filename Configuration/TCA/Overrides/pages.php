<?php

defined('TYPO3') || die('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    'content_defender',
    'Configuration/TSconfig/Page/maxitems.tsconfig',
    'EXT:content_defender :: Enable maxitems view in page module'
);
