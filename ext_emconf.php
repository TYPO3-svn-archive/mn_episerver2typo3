<?php

########################################################################
# Extension Manager/Repository config file for ext "mn_episerver2typo3".
#
# Auto generated 08-03-2012 09:45
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'EPiServer 2 TYPO3',
	'description' => 'Import page data/structure from EPiServer to TYPO3.',
	'category' => 'module',
	'author' => 'Mattias Nilsson',
	'author_email' => 'tollepjaer@gmail.com',
	'shy' => '',
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.3.0',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:44:{s:9:"ChangeLog";s:4:"9880";s:10:"README.txt";s:4:"ee2d";s:16:"ext_autoload.php";s:4:"0015";s:12:"ext_icon.gif";s:4:"38ff";s:17:"ext_localconf.php";s:4:"9b0f";s:14:"ext_tables.php";s:4:"cd3e";s:14:"ext_tables.sql";s:4:"f57e";s:25:"ext_tables_static+adt.sql";s:4:"b84d";s:39:"icon_tx_mnepiserver2typo3_episerver.gif";s:4:"38ff";s:60:"icon_tx_mnepiserver2typo3_episerver_language_translation.gif";s:4:"7b45";s:13:"locallang.xml";s:4:"4812";s:16:"locallang_db.xml";s:4:"c518";s:7:"tca.php";s:4:"7d99";s:14:"doc/manual.pdf";s:4:"f7b0";s:14:"doc/manual.sxw";s:4:"2b0a";s:19:"doc/wizard_form.dat";s:4:"9ddc";s:20:"doc/wizard_form.html";s:4:"9a93";s:30:"lib/class.database_queries.php";s:4:"7499";s:32:"lib/class.webservice_connect.php";s:4:"e20d";s:20:"lib/nusoap/changelog";s:4:"87c5";s:32:"lib/nusoap/class.nusoap_base.php";s:4:"0b69";s:31:"lib/nusoap/class.soap_fault.php";s:4:"a8f5";s:32:"lib/nusoap/class.soap_parser.php";s:4:"4e1e";s:32:"lib/nusoap/class.soap_server.php";s:4:"5192";s:40:"lib/nusoap/class.soap_transport_http.php";s:4:"dc62";s:29:"lib/nusoap/class.soap_val.php";s:4:"0a42";s:31:"lib/nusoap/class.soapclient.php";s:4:"1d9a";s:25:"lib/nusoap/class.wsdl.php";s:4:"74b6";s:30:"lib/nusoap/class.wsdlcache.php";s:4:"4b29";s:30:"lib/nusoap/class.xmlschema.php";s:4:"db26";s:21:"lib/nusoap/nusoap.php";s:4:"0824";s:25:"lib/nusoap/nusoapmime.php";s:4:"be4d";s:13:"mod1/conf.php";s:4:"7d4f";s:14:"mod1/index.php";s:4:"3223";s:18:"mod1/locallang.xml";s:4:"6eed";s:22:"mod1/locallang_mod.xml";s:4:"9f7d";s:19:"mod1/moduleicon.gif";s:4:"38ff";s:55:"scheduler/class.tx_mnepiserver2typo3_deletedatatask.php";s:4:"a1b6";s:79:"scheduler/class.tx_mnepiserver2typo3_deletedatatask_additionalfieldprovider.php";s:4:"1d53";s:55:"scheduler/class.tx_mnepiserver2typo3_importdatatask.php";s:4:"3769";s:84:"scheduler/class.tx_mnepiserver2typo3_importlanguagestask_additionalfieldprovider.php";s:4:"b340";s:59:"scheduler/class.tx_mnepiserver2typo3_testconnectiontask.php";s:4:"cfd0";s:83:"scheduler/class.tx_mnepiserver2typo3_testconnectiontask_additionalfieldprovider.php";s:4:"4bf9";s:35:"tca/class.tx_propertyfields_tca.php";s:4:"e469";}',
	'suggests' => array(
	),
);

?>