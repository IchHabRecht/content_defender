<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "content_defender".
 *
 * Auto generated 20-09-2017 10:35
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
  'version' => '2.2.4',
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
  '_md5_values_when_last_written' => 'a:24:{s:9:"ChangeLog";s:4:"966f";s:9:"README.md";s:4:"4ff8";s:13:"composer.json";s:4:"cbd5";s:12:"ext_icon.png";s:4:"f488";s:17:"ext_localconf.php";s:4:"195f";s:52:"Classes/BackendLayout/BackendLayoutConfiguration.php";s:4:"8f99";s:54:"Classes/Form/Exception/AccessDeniedColPosException.php";s:4:"3fa2";s:47:"Classes/Form/FormDataProvider/TcaCTypeItems.php";s:4:"cbc4";s:48:"Classes/Form/FormDataProvider/TcaColPosItems.php";s:4:"8624";s:41:"Classes/Hooks/AbstractDataHandlerHook.php";s:4:"5534";s:39:"Classes/Hooks/CmdmapDataHandlerHook.php";s:4:"e2fc";s:40:"Classes/Hooks/DatamapDataHandlerHook.php";s:4:"df24";s:33:"Classes/Hooks/WizardItemsHook.php";s:4:"450f";s:47:"Tests/Functional/AbstractFunctionalTestCase.php";s:4:"36de";s:44:"Tests/Functional/Fixtures/Database/pages.xml";s:4:"7199";s:49:"Tests/Functional/Fixtures/Database/tt_content.xml";s:4:"7589";s:61:"Tests/Functional/Fixtures/TSconfig/NewContentElementWizard.ts";s:4:"ca7e";s:60:"Tests/Functional/Fixtures/TSconfig/BackendLayouts/Default.ts";s:4:"f2cd";s:60:"Tests/Functional/Fixtures/TSconfig/BackendLayouts/Subpage.ts";s:4:"87bc";s:60:"Tests/Functional/Form/FormDataProvider/TcaCTypeItemsTest.php";s:4:"9fec";s:60:"Tests/Functional/Form/FormDataProvider/TcaColPostemsTest.php";s:4:"94e9";s:52:"Tests/Functional/Hooks/CmpmapDataHandlerHookTest.php";s:4:"df4c";s:53:"Tests/Functional/Hooks/DatamapDataHandlerHookTest.php";s:4:"9bda";s:46:"Tests/Functional/Hooks/WizardItemsHookTest.php";s:4:"7ba7";}',
);

