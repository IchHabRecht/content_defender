<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "content_defender".
 *
 * Auto generated 11-05-2017 17:45
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
  'version' => '2.0.0',
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
  '_md5_values_when_last_written' => 'a:19:{s:9:"ChangeLog";s:4:"d96c";s:9:"README.md";s:4:"d07c";s:13:"composer.json";s:4:"d108";s:12:"ext_icon.png";s:4:"f488";s:17:"ext_localconf.php";s:4:"4b7b";s:52:"Classes/BackendLayout/BackendLayoutConfiguration.php";s:4:"8f99";s:47:"Classes/Form/FormDataProvider/TcaCTypeItems.php";s:4:"cbc4";s:41:"Classes/Hooks/AbstractDataHandlerHook.php";s:4:"8bf7";s:39:"Classes/Hooks/CmdmapDataHandlerHook.php";s:4:"087b";s:40:"Classes/Hooks/DatamapDataHandlerHook.php";s:4:"31d8";s:33:"Classes/Hooks/WizardItemsHook.php";s:4:"450f";s:47:"Tests/Functional/AbstractFunctionalTestCase.php";s:4:"b9d1";s:53:"Tests/Functional/Fixtures/Database/backend_layout.xml";s:4:"bcb6";s:44:"Tests/Functional/Fixtures/Database/pages.xml";s:4:"85f0";s:49:"Tests/Functional/Fixtures/Database/tt_content.xml";s:4:"57e9";s:61:"Tests/Functional/Fixtures/TSconfig/NewContentElementWizard.ts";s:4:"ca7e";s:52:"Tests/Functional/Hooks/CmpmapDataHandlerHookTest.php";s:4:"dac8";s:53:"Tests/Functional/Hooks/DatamapDataHandlerHookTest.php";s:4:"9cd8";s:46:"Tests/Functional/Hooks/WizardItemsHookTest.php";s:4:"0fa0";}',
);

