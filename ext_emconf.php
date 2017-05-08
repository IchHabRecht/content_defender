<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "content_defender".
 *
 * Auto generated 08-05-2017 11:39
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
  'state' => 'alpha',
  'uploadfolder' => 0,
  'createDirs' => '',
  'clearCacheOnLoad' => 0,
  'version' => '1.0.1',
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
  '_md5_values_when_last_written' => 'a:9:{s:9:"ChangeLog";s:4:"7b5a";s:9:"README.md";s:4:"b63d";s:13:"composer.json";s:4:"6028";s:12:"ext_icon.png";s:4:"f488";s:17:"ext_localconf.php";s:4:"974f";s:52:"Classes/BackendLayout/BackendLayoutConfiguration.php";s:4:"6cbb";s:47:"Classes/Form/FormDataProvider/TcaCTypeItems.php";s:4:"2495";s:38:"Classes/Hooks/CTypeDataHandlerHook.php";s:4:"d3e4";s:33:"Classes/Hooks/WizardItemsHook.php";s:4:"27da";}',
);

