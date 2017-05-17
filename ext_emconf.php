<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "content_defender".
 *
 * Auto generated 17-05-2017 12:23
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
  'version' => '2.2.1',
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
  'autoload-dev' => 
  array (
    'psr-4' => 
    array (
      'IchHabRecht\\ContentDefender\\Tests\\' => 'Tests/',
    ),
  ),
  '_md5_values_when_last_written' => 'a:25:{s:9:"ChangeLog";s:4:"3245";s:9:"README.md";s:4:"4ff8";s:13:"composer.json";s:4:"d108";s:12:"ext_icon.png";s:4:"f488";s:17:"ext_localconf.php";s:4:"195f";s:10:"phpmd.html";s:4:"c70f";s:52:"Classes/BackendLayout/BackendLayoutConfiguration.php";s:4:"8f99";s:54:"Classes/Form/Exception/AccessDeniedColPosException.php";s:4:"3fa2";s:47:"Classes/Form/FormDataProvider/TcaCTypeItems.php";s:4:"cbc4";s:48:"Classes/Form/FormDataProvider/TcaColPosItems.php";s:4:"5527";s:41:"Classes/Hooks/AbstractDataHandlerHook.php";s:4:"5aa3";s:39:"Classes/Hooks/CmdmapDataHandlerHook.php";s:4:"024a";s:40:"Classes/Hooks/DatamapDataHandlerHook.php";s:4:"df24";s:33:"Classes/Hooks/WizardItemsHook.php";s:4:"450f";s:47:"Tests/Functional/AbstractFunctionalTestCase.php";s:4:"b8c1";s:44:"Tests/Functional/Fixtures/Database/pages.xml";s:4:"43c6";s:49:"Tests/Functional/Fixtures/Database/tt_content.xml";s:4:"8555";s:61:"Tests/Functional/Fixtures/TSconfig/NewContentElementWizard.ts";s:4:"ca7e";s:60:"Tests/Functional/Fixtures/TSconfig/BackendLayouts/Default.ts";s:4:"f2cd";s:60:"Tests/Functional/Fixtures/TSconfig/BackendLayouts/Subpage.ts";s:4:"87bc";s:60:"Tests/Functional/Form/FormDataProvider/TcaCTypeItemsTest.php";s:4:"9fec";s:60:"Tests/Functional/Form/FormDataProvider/TcaColPostemsTest.php";s:4:"0f53";s:52:"Tests/Functional/Hooks/CmpmapDataHandlerHookTest.php";s:4:"9297";s:53:"Tests/Functional/Hooks/DatamapDataHandlerHookTest.php";s:4:"d9cc";s:46:"Tests/Functional/Hooks/WizardItemsHookTest.php";s:4:"7ba7";}',
);

