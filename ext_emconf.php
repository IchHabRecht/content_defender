<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "content_defender".
 *
 * Auto generated 08-09-2023 10:51
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
  'version' => '3.4.3',
  'constraints' =>
  array (
    'depends' =>
    array (
      'typo3' => '10.4.0-12.4.99',
    ),
    'conflicts' =>
    array (
    ),
    'suggests' =>
    array (
    ),
  ),
  '_md5_values_when_last_written' => 'a:226:{s:9:"ChangeLog";s:4:"5bcb";s:7:"LICENSE";s:4:"b234";s:9:"README.md";s:4:"256b";s:13:"composer.json";s:4:"af93";s:13:"composer.lock";s:4:"4e2e";s:14:"ext_tables.php";s:4:"a6ab";s:16:"phpunit.xml.dist";s:4:"316a";s:24:"sonar-project.properties";s:4:"ee02";s:52:"Classes/BackendLayout/BackendLayoutConfiguration.php";s:4:"9ac6";s:66:"Classes/BackendLayout/ColumnConfigurationManipulationInterface.php";s:4:"679f";s:47:"Classes/Exception/MissingInterfaceException.php";s:4:"9a79";s:54:"Classes/Form/Exception/AccessDeniedColPosException.php";s:4:"05f6";s:47:"Classes/Form/FormDataProvider/TcaCTypeItems.php";s:4:"be07";s:48:"Classes/Form/FormDataProvider/TcaColPosItems.php";s:4:"d009";s:41:"Classes/Hooks/AbstractDataHandlerHook.php";s:4:"5e07";s:39:"Classes/Hooks/CmdmapDataHandlerHook.php";s:4:"8d04";s:40:"Classes/Hooks/DatamapDataHandlerHook.php";s:4:"51cd";s:33:"Classes/Hooks/WizardItemsHook.php";s:4:"6f5c";s:39:"Classes/Repository/ColPosCountState.php";s:4:"3945";s:40:"Classes/Repository/ContentRepository.php";s:4:"887b";s:27:"Configuration/Services.yaml";s:4:"b816";s:36:"Resources/Public/Icons/Extension.png";s:4:"f488";s:47:"Tests/Functional/AbstractFunctionalTestCase.php";s:4:"fab3";s:80:"Tests/Functional/Fixtures/Classes/ContentElement/NewContentElementController.php";s:4:"9d01";s:69:"Tests/Functional/Fixtures/Classes/Hooks/SimpleSelectboxSingleHook.php";s:4:"e8a1";s:68:"Tests/Functional/Fixtures/Configuration/TCA/Overrides/tt_content.php";s:4:"af4c";s:47:"Tests/Functional/Fixtures/Database/be_users.csv";s:4:"92a7";s:44:"Tests/Functional/Fixtures/Database/pages.csv";s:4:"629b";s:51:"Tests/Functional/Fixtures/Database/sys_language.csv";s:4:"e1b8";s:52:"Tests/Functional/Fixtures/Database/sys_workspace.csv";s:4:"ab48";s:49:"Tests/Functional/Fixtures/Database/tt_content.csv";s:4:"0370";s:44:"Tests/Functional/Fixtures/Frontend/site.yaml";s:4:"8c17";s:61:"Tests/Functional/Fixtures/TSconfig/NewContentElementWizard.ts";s:4:"44a8";s:60:"Tests/Functional/Fixtures/TSconfig/BackendLayouts/Default.ts";s:4:"0be4";s:60:"Tests/Functional/Fixtures/TSconfig/BackendLayouts/Subpage.ts";s:4:"e1da";s:60:"Tests/Functional/Form/FormDataProvider/TcaCTypeItemsTest.php";s:4:"2547";s:61:"Tests/Functional/Form/FormDataProvider/TcaColPosItemsTest.php";s:4:"d815";s:52:"Tests/Functional/Hooks/CmdmapDataHandlerHookTest.php";s:4:"d66f";s:53:"Tests/Functional/Hooks/DatamapDataHandlerHookTest.php";s:4:"4731";s:62:"Tests/Functional/Hooks/DatamapDataHandlerHookWorkspaceTest.php";s:4:"4ec7";s:46:"Tests/Functional/Hooks/WizardItemsHookTest.php";s:4:"2327";s:47:"Tests/Unit/Repository/ContentRepositoryTest.php";s:4:"7495";s:31:"config/sites/page_1/config.yaml";s:4:"10d2";s:26:"config/system/settings.php";s:4:"9254";s:13:"var/.htaccess";s:4:"8224";s:47:"var/cache/code/core/645a5f801bb34145498861.temp";s:4:"ceac";s:47:"var/cache/code/core/645a5f802ba15747094947.temp";s:4:"c553";s:47:"var/cache/code/core/645a5f815df67763158008.temp";s:4:"ba6f";s:47:"var/cache/code/core/645a5f82182d4929525866.temp";s:4:"d59d";s:47:"var/cache/code/core/645a5f822c049246912225.temp";s:4:"383f";s:47:"var/cache/code/core/645a5f822dfee565238338.temp";s:4:"6259";s:47:"var/cache/code/core/645a5f82364c6726618336.temp";s:4:"7bfe";s:47:"var/cache/code/core/645a5f82395b8203550725.temp";s:4:"3053";s:79:"var/cache/code/core/BackendModules_3677fd68c7eaa4c1f5769274e1e9b6d72a904062.php";s:4:"3053";s:78:"var/cache/code/core/BackendRoutes_3677fd68c7eaa4c1f5769274e1e9b6d72a904062.php";s:4:"7bfe";s:92:"var/cache/code/core/ExpressionLanguageProviders_3677fd68c7eaa4c1f5769274e1e9b6d72a904062.php";s:4:"52ad";s:70:"var/cache/code/core/Icons_3677fd68c7eaa4c1f5769274e1e9b6d72a904062.php";s:4:"2ebc";s:78:"var/cache/code/core/ext_localconf_3677fd68c7eaa4c1f5769274e1e9b6d72a904062.php";s:4:"ceac";s:75:"var/cache/code/core/ext_tables_3677fd68c7eaa4c1f5769274e1e9b6d72a904062.php";s:4:"c553";s:58:"var/cache/code/core/foo-46703b925af4518c-table-sys_log.php";s:4:"69bc";s:84:"var/cache/code/core/middlewares_backend_3677fd68c7eaa4c1f5769274e1e9b6d72a904062.php";s:4:"6259";s:85:"var/cache/code/core/middlewares_frontend_3677fd68c7eaa4c1f5769274e1e9b6d72a904062.php";s:4:"383f";s:43:"var/cache/code/core/sites-configuration.php";s:4:"d59d";s:73:"var/cache/code/core/tca_base_3677fd68c7eaa4c1f5769274e1e9b6d72a904062.php";s:4:"ba6f";s:67:"var/cache/code/core/typo3_cd-20fac9b3c1545ede-table-be_sessions.php";s:4:"a74f";s:64:"var/cache/code/core/typo3_cd-20fac9b3c1545ede-table-be_users.php";s:4:"4b56";s:63:"var/cache/code/core/typo3_cd-20fac9b3c1545ede-table-sys_log.php";s:4:"14cb";s:68:"var/cache/code/core/typo3_cd-20fac9b3c1545ede-table-sys_registry.php";s:4:"78ef";s:45:"var/cache/code/di/645a5f7ff3da5214062717.temp";s:4:"7e84";s:91:"var/cache/code/di/DependencyInjectionContainer_3677fd68c7eaa4c1f5769274e1e9b6d72a904062.php";s:4:"7e84";s:102:"var/cache/code/fluid_template/Default_action_Backend_Main_2083939683b75d81fb2239cde64387a5daf9dcf7.php";s:4:"3fe1";s:113:"var/cache/code/fluid_template/Default_action_Login_UserPassLoginForm_174da338f792f3d2a7407ac1edf2c6142453f351.php";s:4:"e871";s:119:"var/cache/code/fluid_template/Default_action_PageLayout_PageModuleNoAccess_e5657a280c6726a22517821141091541fcddfdea.php";s:4:"0395";s:132:"var/cache/code/fluid_template/Default_action_ToolbarItems_ClearCacheToolbarItemDropDown_9587c94c2e72962b8da46c36b3205413f0dd16bd.php";s:4:"5d5e";s:124:"var/cache/code/fluid_template/Default_action_ToolbarItems_ClearCacheToolbarItem_7a7a796d578185a6381fd714ab38a2c61b990fce.php";s:4:"ca49";s:111:"var/cache/code/fluid_template/Default_action_ToolbarItems_DropDown_a5b5648a56935d43121e08c3c131dbfbc2e03379.php";s:4:"6070";s:126:"var/cache/code/fluid_template/Default_action_ToolbarItems_HelpToolbarItemDropDown_8ac56b21bb2b11951a34debfe85ae75f91a244b1.php";s:4:"d705";s:118:"var/cache/code/fluid_template/Default_action_ToolbarItems_HelpToolbarItem_28501ec46eca37b7df658ab96cebcaf0dbad600f.php";s:4:"2245";s:124:"var/cache/code/fluid_template/Default_action_ToolbarItems_LiveSearchToolbarItem_5d8eaa22aa02c19c49b3318cf8606582d09ff656.php";s:4:"40aa";s:130:"var/cache/code/fluid_template/Default_action_ToolbarItems_ShortcutToolbarItemDropDown_13eee6d8e5bd1d41c7d2cbdd66936f43da4b5099.php";s:4:"2211";s:126:"var/cache/code/fluid_template/Default_action_ToolbarItems_ShortcutToolbarItemItem_9df7afb6f539b6989bf5abe7c1a1b1586813cecd.php";s:4:"b2b7";s:128:"var/cache/code/fluid_template/Default_action_ToolbarItems_SystemInformationDropDown_019495d4b9caa2a60fe7b46b1bcfc0013d5cecd2.php";s:4:"9782";s:131:"var/cache/code/fluid_template/Default_action_ToolbarItems_SystemInformationToolbarItem_c0e26bd793878a3ad4302c4021d418018fb8e1ab.php";s:4:"03e2";s:114:"var/cache/code/fluid_template/Default_action_ToolbarItems_ToolbarItem_e0d25c733c60efe542c376ceabd9ed4ab17a5437.php";s:4:"126d";s:126:"var/cache/code/fluid_template/Default_action_ToolbarItems_UserToolbarItemDropDown_93d09dbb146e21a68ea798443db8a9454e2c75c5.php";s:4:"7137";s:118:"var/cache/code/fluid_template/Default_action_ToolbarItems_UserToolbarItem_2d50a105e9f3da3977347f52bb5d3a42b69757f7.php";s:4:"033b";s:92:"var/cache/code/fluid_template/layout_Login_html_ea78b6ed0d67dc8d08bc96cc00a1040d1f551691.php";s:4:"0fd2";s:93:"var/cache/code/fluid_template/layout_Module_html_06bb36b96fa752ea87141ba952ba5c8c0ba70bc1.php";s:4:"cebd";s:101:"var/cache/code/fluid_template/partial_Backend_ModuleMenu_e7d3f7333759949f177404f9fe8c3b0938a123df.php";s:4:"8455";s:97:"var/cache/code/fluid_template/partial_Backend_Topbar_4c65b2114d1bc606f6a062ceb4ad3cc046bc6ccb.php";s:4:"5838";s:92:"var/cache/code/fluid_template/partial_ButtonBar_bf0d54a67544c1830eaeef128e7db182b204d10b.php";s:4:"7a06";s:92:"var/cache/code/fluid_template/partial_DocHeader_bc6fe46661d93ce81a85353927039405fd430fd8.php";s:4:"dec0";s:98:"var/cache/code/fluid_template/partial_Login_LoginNews_886bd5c58e429265f5f81bf33c3a7bedf7dd1a31.php";s:4:"7c39";s:107:"var/cache/code/fluid_template/partial_ToolbarItems_ToolbarItem_304396b3ab1920ef5cecde1398535f1dff50f524.php";s:4:"c372";s:76:"var/cache/code/typoscript/pagestsconfig-package-backend-2135928d427cd8ce.php";s:4:"0951";s:73:"var/cache/code/typoscript/pagestsconfig-package-core-ba717c59771eb07a.php";s:4:"714a";s:77:"var/cache/code/typoscript/pagestsconfig-package-filelist-84d513acdf6a8616.php";s:4:"0a1a";s:77:"var/cache/code/typoscript/pagestsconfig-package-frontend-d974ca2b9c31f783.php";s:4:"4150";s:83:"var/cache/code/typoscript/pagestsconfig-package-indexed_search-58ab72ea19848f4a.php";s:4:"9064";s:79:"var/cache/code/typoscript/pagestsconfig-package-workspaces-6523d2af010b3975.php";s:4:"206a";s:60:"var/cache/code/typoscript/pagestsconfig-packages-strings.php";s:4:"b90b";s:68:"var/cache/code/typoscript/usertsconfig-admpanel-e5a65aed57d3d51c.php";s:4:"b5dd";s:67:"var/cache/code/typoscript/usertsconfig-globals-8c4b6f9930696367.php";s:4:"fc2c";s:49:"var/cache/data/assets/645a5f82033a0456811536.temp";s:4:"bb33";s:49:"var/cache/data/assets/645a5f8232543998851125.temp";s:4:"ca69";s:75:"var/cache/data/assets/BackendIcons_3677fd68c7eaa4c1f5769274e1e9b6d72a904062";s:4:"ca69";s:81:"var/cache/data/assets/BackendIcons_3677fd68c7eaa4c1f5769274e1e9b6d72a904062_flags";s:4:"6a18";s:72:"var/cache/data/assets/ImportMap_3677fd68c7eaa4c1f5769274e1e9b6d72a904062";s:4:"9416";s:52:"var/cache/data/l10n/012e18f593f83e7aed329fa641a7b0b5";s:4:"40cd";s:52:"var/cache/data/l10n/01e53ae1cdeca496e8da7877a0f67a9a";s:4:"40cd";s:52:"var/cache/data/l10n/03dfd42286768dffbd8c5bedb75fe18f";s:4:"40cd";s:52:"var/cache/data/l10n/0414c2fa0ca92bc28cd50cfc04e78af4";s:4:"40cd";s:52:"var/cache/data/l10n/055d2dea7c351ea913baae66e561d327";s:4:"1911";s:52:"var/cache/data/l10n/0607dfe48b6b482955a1b7150c7e69a4";s:4:"40cd";s:52:"var/cache/data/l10n/12254dcb7431cbb6bc169bb6866161fe";s:4:"40cd";s:52:"var/cache/data/l10n/1433fc3bfb8d2a046e944995155218e8";s:4:"40cd";s:52:"var/cache/data/l10n/14594e087e1019a4343facf18d46b5d6";s:4:"40cd";s:52:"var/cache/data/l10n/169ddfde7c266198fdb5b0e715a3b66f";s:4:"40cd";s:52:"var/cache/data/l10n/1b4162e8f0bb7f02f6043f259deb6375";s:4:"f4bb";s:52:"var/cache/data/l10n/1cc04aa05f6d91dff3fa824b0066b382";s:4:"40cd";s:52:"var/cache/data/l10n/24028596dadc45b4321e322f71ab226c";s:4:"40cd";s:52:"var/cache/data/l10n/290866e37965f5aa2653bdec8e31a393";s:4:"40cd";s:52:"var/cache/data/l10n/2bbdc15af2447ecb7de4c1926b1e15a1";s:4:"40cd";s:52:"var/cache/data/l10n/2cf6b121c7d2a0e95002c404db8b05d9";s:4:"40cd";s:52:"var/cache/data/l10n/3059eac8e6ffa537e166204da9dc2724";s:4:"40cd";s:52:"var/cache/data/l10n/329425bba347f57ce74da1f8ce4ea389";s:4:"64b6";s:52:"var/cache/data/l10n/360a1f48150fb87ce086684b90c62cb1";s:4:"40cd";s:52:"var/cache/data/l10n/3873dd3116e784e9de2e57ede7df4824";s:4:"40cd";s:52:"var/cache/data/l10n/38eb80255c99f5afc4fa8c120913b0d9";s:4:"40cd";s:52:"var/cache/data/l10n/3a320d8d8241f1eef233ca8379367011";s:4:"40cd";s:52:"var/cache/data/l10n/3a9b66cf4053bc5caab2f4d55e3d5fe8";s:4:"40cd";s:52:"var/cache/data/l10n/3b5247c019f04ea6f05dd3692650032a";s:4:"40cd";s:52:"var/cache/data/l10n/3c651733383a4d60c50f3a93eea6aa9c";s:4:"dbd8";s:52:"var/cache/data/l10n/4051e7e1c42885a499642502dce0db0e";s:4:"a7c7";s:52:"var/cache/data/l10n/4250736484e58fc8cfb90759a9cc88cc";s:4:"40cd";s:52:"var/cache/data/l10n/473e680e7489718831c55be590c31305";s:4:"40cd";s:52:"var/cache/data/l10n/47eb9794f1fe73784e178a8915bd13bd";s:4:"40cd";s:52:"var/cache/data/l10n/50ac28206fa076de12273f31551ca2c9";s:4:"40cd";s:52:"var/cache/data/l10n/5627d4f7a3ef842a2b87973b94e0b9c8";s:4:"82d0";s:52:"var/cache/data/l10n/56a6f37f6ba2931da293c4bdcb195602";s:4:"40cd";s:52:"var/cache/data/l10n/57bfee0f567abd8758637cb336aba228";s:4:"40cd";s:52:"var/cache/data/l10n/5bcc5cced07fa0c8f3e173e0cdb5fcd9";s:4:"40cd";s:52:"var/cache/data/l10n/5cee450e14fd7bdb64a0d49408ed9a24";s:4:"b87e";s:52:"var/cache/data/l10n/5cf9e9643079d10f11db573924c8a32d";s:4:"40cd";s:52:"var/cache/data/l10n/608d69ba4791d354fd1a0a22beea4285";s:4:"40cd";s:52:"var/cache/data/l10n/6092afe0269b65c1be3dddcfcf98879b";s:4:"40cd";s:52:"var/cache/data/l10n/644567a722d9cd8623679ff05c868f12";s:4:"40cd";s:52:"var/cache/data/l10n/64e04c84a2568b71d492fc5c7fc5262f";s:4:"40cd";s:52:"var/cache/data/l10n/670069fa44061f5a785d5be10b8d6fbc";s:4:"667c";s:52:"var/cache/data/l10n/67b60f29852ecb26d8346e2e6d48747b";s:4:"40cd";s:52:"var/cache/data/l10n/6af5c773f520de70f793fcc9733fc9a2";s:4:"40cd";s:52:"var/cache/data/l10n/6e6d20569a767f000cd66a37e15afaa3";s:4:"40cd";s:52:"var/cache/data/l10n/6ed12305f78f0cfa83b2ab7c1d3e8893";s:4:"40cd";s:52:"var/cache/data/l10n/7117ccdbbe3b3b040fcff2d29211ceea";s:4:"cf2e";s:52:"var/cache/data/l10n/72071608a4b5dda56171e82bf91a5007";s:4:"40cd";s:52:"var/cache/data/l10n/7332d8ed39fdfbb9fe779d835dcf0846";s:4:"40cd";s:52:"var/cache/data/l10n/73c0a76727091bcafe4916184710c792";s:4:"fccf";s:52:"var/cache/data/l10n/76bdaf02120eaba33c4d5cf51ec7c620";s:4:"40cd";s:52:"var/cache/data/l10n/77fe89cc4e2bcb14d030f20138cbcf31";s:4:"40cd";s:52:"var/cache/data/l10n/79733de5fb83b97bd0566e3b43ae4fd2";s:4:"30bb";s:52:"var/cache/data/l10n/7a98756a7ea2673a3adf0be20a91a4cc";s:4:"40cd";s:52:"var/cache/data/l10n/7ae0d6fb424bc72d1240300e65a85684";s:4:"dd51";s:52:"var/cache/data/l10n/8959866461fa3b0b8f3dedde3d25c981";s:4:"962e";s:52:"var/cache/data/l10n/8baa425c95d7aed5ce3bfa835246e02c";s:4:"2f9a";s:52:"var/cache/data/l10n/8c0f028752029b7dd2334ba662cfc34a";s:4:"40cd";s:52:"var/cache/data/l10n/8c2a5f11960adc2848dd683fa3571177";s:4:"40cd";s:52:"var/cache/data/l10n/8d6e7edab4305a049c43c0230bd6927b";s:4:"40cd";s:52:"var/cache/data/l10n/8d76967fb804ddfb2eaad04791dc40ef";s:4:"40cd";s:52:"var/cache/data/l10n/93a8850392531638a0ce45327c4305fa";s:4:"40cd";s:52:"var/cache/data/l10n/9436352b062adca334b47ef279c939f8";s:4:"6701";s:52:"var/cache/data/l10n/951f3861f4b3195cc4485b563d944120";s:4:"40cd";s:52:"var/cache/data/l10n/956fbe97b727c24d114a76641203b24b";s:4:"40cd";s:52:"var/cache/data/l10n/965766ce399ee635daadfc08c48f0d3b";s:4:"40cd";s:52:"var/cache/data/l10n/97f3310931e000782f4e47abf44f28ec";s:4:"40cd";s:52:"var/cache/data/l10n/98f0a8f1ec79b513eb3125278e17228f";s:4:"6386";s:52:"var/cache/data/l10n/9dfe25640c3ce108bb5372109cd4a718";s:4:"40cd";s:52:"var/cache/data/l10n/a17b15b9a8d23a35d6eb2aee29f7b985";s:4:"29f4";s:52:"var/cache/data/l10n/a216ad6e9a82251da10bb0ce15a70a52";s:4:"40cd";s:52:"var/cache/data/l10n/a4a76dd7e3361c392834bf55ab3fcd0d";s:4:"40cd";s:52:"var/cache/data/l10n/a7dcce91b19fc132f3393b5fc7ebacea";s:4:"40cd";s:52:"var/cache/data/l10n/a91fa27eb861a3187e2244c0cb4cd044";s:4:"40cd";s:52:"var/cache/data/l10n/ad6c3dea4cd6537b86996f678deae5ae";s:4:"40cd";s:52:"var/cache/data/l10n/ad89a98749fa06f94de9d49171be082e";s:4:"76b8";s:52:"var/cache/data/l10n/b0df42c7abf279f5c0586d2ceb78e023";s:4:"40cd";s:52:"var/cache/data/l10n/b5982f6643fb67c7a285f9b52d88a53b";s:4:"e1a9";s:52:"var/cache/data/l10n/cb28c8a0cd5ae9d0403473093b3fb70c";s:4:"0824";s:52:"var/cache/data/l10n/cc59c5d9de1c58f2767710f54f1f1cca";s:4:"6828";s:52:"var/cache/data/l10n/cec67b989ffc747d098bace5612e9789";s:4:"40cd";s:52:"var/cache/data/l10n/cee35d58ad33c331838a9bc50a0943be";s:4:"4b1f";s:52:"var/cache/data/l10n/cfa79980c86a8abf8c0aa16766cf3f16";s:4:"40cd";s:52:"var/cache/data/l10n/d10170d3d298d90145578d5e93f7580e";s:4:"c48c";s:52:"var/cache/data/l10n/d115ba8dccc157f66a544973725db29d";s:4:"40cd";s:52:"var/cache/data/l10n/d58646522f1581af334ebf72d596738c";s:4:"40cd";s:52:"var/cache/data/l10n/d8cf30936e8c72f9ad1df70f3db0c0b5";s:4:"40cd";s:52:"var/cache/data/l10n/d91f2fa984cb9ce9a7242335b392f063";s:4:"40cd";s:52:"var/cache/data/l10n/dcd5466b883d2b812ad4194f7eba63cc";s:4:"804d";s:52:"var/cache/data/l10n/df9db56bd7d9d4c1d72b17f3346ea973";s:4:"40cd";s:52:"var/cache/data/l10n/dfb842bf696e04e45713629a4a80e933";s:4:"40cd";s:52:"var/cache/data/l10n/dfb9a676633b5e4e403a71c0353e784b";s:4:"40cd";s:52:"var/cache/data/l10n/e011cc14f1bda987a5d47ba21bb63dec";s:4:"40cd";s:52:"var/cache/data/l10n/e144343d3ad12777732a2755f2bda79d";s:4:"40cd";s:52:"var/cache/data/l10n/e41421099c0df47c6825481a2b7af6a9";s:4:"40cd";s:52:"var/cache/data/l10n/e5a08c6cd23a165e5d83a9164143b535";s:4:"40cd";s:52:"var/cache/data/l10n/e73a4ea25a3e655f12107d4a6231f359";s:4:"40cd";s:52:"var/cache/data/l10n/e7459d760f89e7f9748c942d5d714f8e";s:4:"40cd";s:52:"var/cache/data/l10n/ea486680d1c21335e4ff6b8638064557";s:4:"0014";s:52:"var/cache/data/l10n/edb24c105f76935cacad7025f5abdf6a";s:4:"40cd";s:52:"var/cache/data/l10n/eee74167adfc9c70cb886d8e126e86e0";s:4:"40cd";s:52:"var/cache/data/l10n/f43036463514ec2c1cf29fb24a1f196d";s:4:"40cd";s:52:"var/cache/data/l10n/f5a9c190935d50f782a2c0cccec24266";s:4:"40cd";s:52:"var/cache/data/l10n/f8fa2b590b3351ed86601dd28242709f";s:4:"40cd";s:52:"var/cache/data/l10n/fd1483f9e0758e87e7d55bfdc26d8859";s:4:"6db7";s:52:"var/cache/data/l10n/ff768122d3af4d1501b259db10045695";s:4:"40cd";s:29:"var/charset/csascii_utf-8.tbl";s:4:"fa4d";s:28:"var/log/typo3_388fbbff8e.log";s:4:"660f";s:28:"var/log/typo3_3a57bb40f2.log";s:4:"9959";s:28:"var/log/typo3_8dd0a7a209.log";s:4:"f101";s:41:"var/log/typo3_deprecations_8dd0a7a209.log";s:4:"02f5";s:62:"var/session/bd828f831ecb8beade0b5ad95e2735e25d2df087/.htaccess";s:4:"f2a3";s:90:"var/session/bd828f831ecb8beade0b5ad95e2735e25d2df087/hash_db99803167238ce5a40625df0e6ec209";s:4:"93d7";s:63:"var/session/bd828f831ecb8beade0b5ad95e2735e25d2df087/index.html";s:4:"0c14";s:62:"var/session/f8fbad790df02ea89670d28a6dba71e607cd443f/.htaccess";s:4:"f2a3";s:90:"var/session/f8fbad790df02ea89670d28a6dba71e607cd443f/hash_c5d8c28d1c4c22189aeaf57a669acd74";s:4:"2b40";s:90:"var/session/f8fbad790df02ea89670d28a6dba71e607cd443f/hash_d631d5f98a89b5c7ebf873840a7ad65b";s:4:"6222";s:63:"var/session/f8fbad790df02ea89670d28a6dba71e607cd443f/index.html";s:4:"0c14";s:33:"var/transient/ENABLE_INSTALL_TOOL";s:4:"7d2f";}',
);

