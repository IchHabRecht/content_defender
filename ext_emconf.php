<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "content_defender".
 *
 * Auto generated 03-10-2018 13:02
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
  'version' => '3.0.9',
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
  '_md5_values_when_last_written' => 'a:31:{s:9:"ChangeLog";s:4:"e3b7";s:7:"LICENSE";s:4:"b234";s:9:"README.md";s:4:"5be2";s:13:"composer.json";s:4:"4544";s:13:"composer.lock";s:4:"9f66";s:12:"ext_icon.png";s:4:"f488";s:17:"ext_localconf.php";s:4:"b918";s:16:"phpunit.xml.dist";s:4:"7d0d";s:24:"sonar-project.properties";s:4:"ee02";s:52:"Classes/BackendLayout/BackendLayoutConfiguration.php";s:4:"17e8";s:54:"Classes/Form/Exception/AccessDeniedColPosException.php";s:4:"075d";s:47:"Classes/Form/FormDataProvider/TcaCTypeItems.php";s:4:"48f7";s:48:"Classes/Form/FormDataProvider/TcaColPosItems.php";s:4:"71c1";s:41:"Classes/Hooks/AbstractDataHandlerHook.php";s:4:"6c92";s:39:"Classes/Hooks/CmdmapDataHandlerHook.php";s:4:"898f";s:40:"Classes/Hooks/DatamapDataHandlerHook.php";s:4:"f82a";s:33:"Classes/Hooks/WizardItemsHook.php";s:4:"26ed";s:40:"Classes/Repository/ContentRepository.php";s:4:"1b1b";s:47:"Tests/Functional/AbstractFunctionalTestCase.php";s:4:"4f7b";s:44:"Tests/Functional/Fixtures/Database/pages.xml";s:4:"7199";s:61:"Tests/Functional/Fixtures/Database/pages_language_overlay.xml";s:4:"49d9";s:49:"Tests/Functional/Fixtures/Database/tt_content.xml";s:4:"8c39";s:61:"Tests/Functional/Fixtures/TSconfig/NewContentElementWizard.ts";s:4:"ca7e";s:60:"Tests/Functional/Fixtures/TSconfig/BackendLayouts/Default.ts";s:4:"a875";s:60:"Tests/Functional/Fixtures/TSconfig/BackendLayouts/Subpage.ts";s:4:"87bc";s:60:"Tests/Functional/Form/FormDataProvider/TcaCTypeItemsTest.php";s:4:"d050";s:60:"Tests/Functional/Form/FormDataProvider/TcaColPostemsTest.php";s:4:"44cc";s:52:"Tests/Functional/Hooks/CmdmapDataHandlerHookTest.php";s:4:"e8d9";s:53:"Tests/Functional/Hooks/DatamapDataHandlerHookTest.php";s:4:"0364";s:46:"Tests/Functional/Hooks/WizardItemsHookTest.php";s:4:"f9ed";s:47:"Tests/Unit/Repository/ContentRepositoryTest.php";s:4:"e759";}',
);

