<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "content_defender".
 *
 * Auto generated 10-05-2017 12:28
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
  'title' => 'Content Defender',
  'description' => 'Define allowed or denied content element types in your backend layouts',
  'category' => 'plugin',
  'author' => 'Nicole Cordes',
  'author_email' => 'typo3@cordes.co',
  'author_company' => 'CPS-IT GmbH',
  'state' => 'beta',
  'uploadfolder' => 0,
  'createDirs' => '',
  'clearCacheOnLoad' => 0,
  'version' => '1.0.2',
  'constraints' => 
  array (
    'depends' => 
    array (
      'typo3' => '7.6.0-8.7.99',
    ),
    'conflicts' => 
    array (
    ),
    'suggests' => 
    array (
    ),
  ),
  '_md5_values_when_last_written' => 'a:13:{s:9:"ChangeLog";s:4:"7cab";s:9:"README.md";s:4:"b63d";s:13:"composer.json";s:4:"ce5e";s:12:"ext_icon.png";s:4:"f488";s:17:"ext_localconf.php";s:4:"974f";s:52:"Classes/BackendLayout/BackendLayoutConfiguration.php";s:4:"1042";s:47:"Classes/Form/FormDataProvider/TcaCTypeItems.php";s:4:"2495";s:38:"Classes/Hooks/CTypeDataHandlerHook.php";s:4:"c4b1";s:33:"Classes/Hooks/WizardItemsHook.php";s:4:"27da";s:44:"Tests/Functional/Fixtures/backend_layout.xml";s:4:"93a4";s:35:"Tests/Functional/Fixtures/pages.xml";s:4:"85f0";s:40:"Tests/Functional/Fixtures/tt_content.xml";s:4:"57e9";s:51:"Tests/Functional/Hooks/CTypeDataHandlerHookTest.php";s:4:"38a1";}',
);

