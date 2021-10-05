<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "content_defender".
 *
 * Auto generated 05-06-2021 19:38
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
  'version' => '3.1.3',
  'constraints' => 
  array (
    'depends' => 
    array (
      'typo3' => '8.7.0-11.5.99',
    ),
    'conflicts' => 
    array (
    ),
    'suggests' => 
    array (
    ),
  ),
  '_md5_values_when_last_written' => 'a:36:{s:9:"ChangeLog";s:4:"d6be";s:7:"LICENSE";s:4:"b234";s:9:"README.md";s:4:"878d";s:13:"composer.json";s:4:"06bb";s:13:"composer.lock";s:4:"3967";s:14:"ext_tables.php";s:4:"cdf1";s:16:"phpunit.xml.dist";s:4:"041c";s:24:"sonar-project.properties";s:4:"ee02";s:52:"Classes/BackendLayout/BackendLayoutConfiguration.php";s:4:"f6df";s:66:"Classes/BackendLayout/ColumnConfigurationManipulationInterface.php";s:4:"679f";s:47:"Classes/Exception/MissingInterfaceException.php";s:4:"9a79";s:54:"Classes/Form/Exception/AccessDeniedColPosException.php";s:4:"05f6";s:47:"Classes/Form/FormDataProvider/TcaCTypeItems.php";s:4:"7a82";s:48:"Classes/Form/FormDataProvider/TcaColPosItems.php";s:4:"4c14";s:41:"Classes/Hooks/AbstractDataHandlerHook.php";s:4:"5e07";s:39:"Classes/Hooks/CmdmapDataHandlerHook.php";s:4:"8d04";s:40:"Classes/Hooks/DatamapDataHandlerHook.php";s:4:"dfd4";s:33:"Classes/Hooks/WizardItemsHook.php";s:4:"56e7";s:40:"Classes/Repository/ContentRepository.php";s:4:"a704";s:36:"Resources/Public/Icons/Extension.png";s:4:"f488";s:47:"Tests/Functional/AbstractFunctionalTestCase.php";s:4:"276c";s:80:"Tests/Functional/Fixtures/Classes/ContentElement/NewContentElementController.php";s:4:"4c1b";s:69:"Tests/Functional/Fixtures/Classes/Hooks/SimpleSelectboxSingleHook.php";s:4:"19ea";s:68:"Tests/Functional/Fixtures/Configuration/TCA/Overrides/tt_content.php";s:4:"0d0a";s:44:"Tests/Functional/Fixtures/Database/pages.xml";s:4:"7199";s:61:"Tests/Functional/Fixtures/Database/pages_language_overlay.xml";s:4:"49d9";s:49:"Tests/Functional/Fixtures/Database/tt_content.xml";s:4:"8929";s:61:"Tests/Functional/Fixtures/TSconfig/NewContentElementWizard.ts";s:4:"ca7e";s:60:"Tests/Functional/Fixtures/TSconfig/BackendLayouts/Default.ts";s:4:"9e42";s:60:"Tests/Functional/Fixtures/TSconfig/BackendLayouts/Subpage.ts";s:4:"e1da";s:60:"Tests/Functional/Form/FormDataProvider/TcaCTypeItemsTest.php";s:4:"cc19";s:61:"Tests/Functional/Form/FormDataProvider/TcaColPosItemsTest.php";s:4:"0862";s:52:"Tests/Functional/Hooks/CmdmapDataHandlerHookTest.php";s:4:"0920";s:53:"Tests/Functional/Hooks/DatamapDataHandlerHookTest.php";s:4:"5b6c";s:46:"Tests/Functional/Hooks/WizardItemsHookTest.php";s:4:"5fe5";s:47:"Tests/Unit/Repository/ContentRepositoryTest.php";s:4:"c005";}',
);

