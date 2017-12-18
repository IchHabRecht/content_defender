<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "content_defender".
 *
 * Auto generated 18-12-2017 20:43
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
  'version' => '3.0.0',
  'constraints' =>
  array (
    'depends' =>
    array (
      'typo3' => '8.7.0-9.0.99',
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
  '_md5_values_when_last_written' => 'a:26:{s:9:"ChangeLog";s:4:"bd66";s:9:"README.md";s:4:"a8eb";s:13:"composer.json";s:4:"afe2";s:12:"ext_icon.png";s:4:"f488";s:17:"ext_localconf.php";s:4:"b918";s:24:"sonar-project.properties";s:4:"bef2";s:52:"Classes/BackendLayout/BackendLayoutConfiguration.php";s:4:"7768";s:54:"Classes/Form/Exception/AccessDeniedColPosException.php";s:4:"3fa2";s:47:"Classes/Form/FormDataProvider/TcaCTypeItems.php";s:4:"47e7";s:48:"Classes/Form/FormDataProvider/TcaColPosItems.php";s:4:"fdf0";s:41:"Classes/Hooks/AbstractDataHandlerHook.php";s:4:"66b6";s:39:"Classes/Hooks/CmdmapDataHandlerHook.php";s:4:"b514";s:40:"Classes/Hooks/DatamapDataHandlerHook.php";s:4:"2b0c";s:33:"Classes/Hooks/WizardItemsHook.php";s:4:"dcf1";s:40:"Classes/Repository/ContentRepository.php";s:4:"c532";s:47:"Tests/Functional/AbstractFunctionalTestCase.php";s:4:"36de";s:44:"Tests/Functional/Fixtures/Database/pages.xml";s:4:"7199";s:49:"Tests/Functional/Fixtures/Database/tt_content.xml";s:4:"7589";s:61:"Tests/Functional/Fixtures/TSconfig/NewContentElementWizard.ts";s:4:"ca7e";s:60:"Tests/Functional/Fixtures/TSconfig/BackendLayouts/Default.ts";s:4:"f2cd";s:60:"Tests/Functional/Fixtures/TSconfig/BackendLayouts/Subpage.ts";s:4:"87bc";s:60:"Tests/Functional/Form/FormDataProvider/TcaCTypeItemsTest.php";s:4:"9fec";s:60:"Tests/Functional/Form/FormDataProvider/TcaColPostemsTest.php";s:4:"94e9";s:52:"Tests/Functional/Hooks/CmpmapDataHandlerHookTest.php";s:4:"df4c";s:53:"Tests/Functional/Hooks/DatamapDataHandlerHookTest.php";s:4:"9bda";s:46:"Tests/Functional/Hooks/WizardItemsHookTest.php";s:4:"7ba7";}',
);

