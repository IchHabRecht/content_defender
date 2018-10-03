<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "content_defender".
 *
 * Auto generated 11-09-2018 02:01
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
  'state' => 'stable',
  'uploadfolder' => 0,
  'createDirs' => '',
  'clearCacheOnLoad' => 0,
  'version' => '3.0.8',
  'constraints' => 
  array (
    'depends' => 
    array (
      'typo3' => '8.7.0-9.5.99',
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
  '_md5_values_when_last_written' => 'a:31:{s:9:"ChangeLog";s:4:"ab86";s:7:"LICENSE";s:4:"b234";s:9:"README.md";s:4:"5be2";s:13:"composer.json";s:4:"8d73";s:13:"composer.lock";s:4:"9f66";s:12:"ext_icon.png";s:4:"f488";s:17:"ext_localconf.php";s:4:"b918";s:16:"phpunit.xml.dist";s:4:"7d0d";s:24:"sonar-project.properties";s:4:"ee02";s:52:"Classes/BackendLayout/BackendLayoutConfiguration.php";s:4:"a9c0";s:54:"Classes/Form/Exception/AccessDeniedColPosException.php";s:4:"7671";s:47:"Classes/Form/FormDataProvider/TcaCTypeItems.php";s:4:"cc53";s:48:"Classes/Form/FormDataProvider/TcaColPosItems.php";s:4:"5b26";s:41:"Classes/Hooks/AbstractDataHandlerHook.php";s:4:"5ceb";s:39:"Classes/Hooks/CmdmapDataHandlerHook.php";s:4:"0160";s:40:"Classes/Hooks/DatamapDataHandlerHook.php";s:4:"4f4b";s:33:"Classes/Hooks/WizardItemsHook.php";s:4:"eea9";s:40:"Classes/Repository/ContentRepository.php";s:4:"fba1";s:47:"Tests/Functional/AbstractFunctionalTestCase.php";s:4:"1d51";s:44:"Tests/Functional/Fixtures/Database/pages.xml";s:4:"7199";s:61:"Tests/Functional/Fixtures/Database/pages_language_overlay.xml";s:4:"49d9";s:49:"Tests/Functional/Fixtures/Database/tt_content.xml";s:4:"8c39";s:61:"Tests/Functional/Fixtures/TSconfig/NewContentElementWizard.ts";s:4:"ca7e";s:60:"Tests/Functional/Fixtures/TSconfig/BackendLayouts/Default.ts";s:4:"a875";s:60:"Tests/Functional/Fixtures/TSconfig/BackendLayouts/Subpage.ts";s:4:"87bc";s:60:"Tests/Functional/Form/FormDataProvider/TcaCTypeItemsTest.php";s:4:"506f";s:60:"Tests/Functional/Form/FormDataProvider/TcaColPostemsTest.php";s:4:"2c80";s:52:"Tests/Functional/Hooks/CmdmapDataHandlerHookTest.php";s:4:"a2e3";s:53:"Tests/Functional/Hooks/DatamapDataHandlerHookTest.php";s:4:"0a8a";s:46:"Tests/Functional/Hooks/WizardItemsHookTest.php";s:4:"24ea";s:47:"Tests/Unit/Repository/ContentRepositoryTest.php";s:4:"d853";}',
);

