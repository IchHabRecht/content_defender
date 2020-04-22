<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "content_defender".
 *
 * Auto generated 15-05-2019 21:26
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
  'author_company' => 'biz-design',
  'state' => 'stable',
  'uploadfolder' => 0,
  'createDirs' => '',
  'clearCacheOnLoad' => 0,
  'version' => '3.0.11',
  'constraints' =>
  array (
    'depends' =>
    array (
      'typo3' => '8.7.0-10.4.99',
    ),
    'conflicts' =>
    array (
    ),
    'suggests' =>
    array (
    ),
  ),
  '_md5_values_when_last_written' => 'a:33:{s:9:"ChangeLog";s:4:"a71e";s:7:"LICENSE";s:4:"b234";s:9:"README.md";s:4:"c3dd";s:13:"composer.json";s:4:"5026";s:13:"composer.lock";s:4:"78f3";s:12:"ext_icon.png";s:4:"f488";s:14:"ext_tables.php";s:4:"1f68";s:16:"phpunit.xml.dist";s:4:"6068";s:24:"sonar-project.properties";s:4:"ee02";s:52:"Classes/BackendLayout/BackendLayoutConfiguration.php";s:4:"17e8";s:54:"Classes/Form/Exception/AccessDeniedColPosException.php";s:4:"075d";s:47:"Classes/Form/FormDataProvider/TcaCTypeItems.php";s:4:"ecdd";s:48:"Classes/Form/FormDataProvider/TcaColPosItems.php";s:4:"56c4";s:41:"Classes/Hooks/AbstractDataHandlerHook.php";s:4:"e76e";s:39:"Classes/Hooks/CmdmapDataHandlerHook.php";s:4:"4650";s:40:"Classes/Hooks/DatamapDataHandlerHook.php";s:4:"f82a";s:33:"Classes/Hooks/WizardItemsHook.php";s:4:"26ed";s:40:"Classes/Repository/ContentRepository.php";s:4:"1b1b";s:47:"Tests/Functional/AbstractFunctionalTestCase.php";s:4:"c70d";s:69:"Tests/Functional/Fixtures/Classes/Hooks/SimpleSelectboxSingleHook.php";s:4:"4cce";s:68:"Tests/Functional/Fixtures/Configuration/TCA/Overrides/tt_content.php";s:4:"eb38";s:44:"Tests/Functional/Fixtures/Database/pages.xml";s:4:"7199";s:61:"Tests/Functional/Fixtures/Database/pages_language_overlay.xml";s:4:"49d9";s:49:"Tests/Functional/Fixtures/Database/tt_content.xml";s:4:"8929";s:61:"Tests/Functional/Fixtures/TSconfig/NewContentElementWizard.ts";s:4:"ca7e";s:60:"Tests/Functional/Fixtures/TSconfig/BackendLayouts/Default.ts";s:4:"9e42";s:60:"Tests/Functional/Fixtures/TSconfig/BackendLayouts/Subpage.ts";s:4:"e1da";s:60:"Tests/Functional/Form/FormDataProvider/TcaCTypeItemsTest.php";s:4:"4d51";s:61:"Tests/Functional/Form/FormDataProvider/TcaColPosItemsTest.php";s:4:"c8e4";s:52:"Tests/Functional/Hooks/CmdmapDataHandlerHookTest.php";s:4:"4f52";s:53:"Tests/Functional/Hooks/DatamapDataHandlerHookTest.php";s:4:"0364";s:46:"Tests/Functional/Hooks/WizardItemsHookTest.php";s:4:"f9ed";s:47:"Tests/Unit/Repository/ContentRepositoryTest.php";s:4:"e759";}',
);

