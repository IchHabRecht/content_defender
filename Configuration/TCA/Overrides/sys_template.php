<?php

defined('TYPO3') || die('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'content_defender',
    'Configuration/TypoScript',
    'EXT:content_defender :: Enable maxitems view in page module'
);
