<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

include_once(t3lib_extMgm::extPath($_EXTKEY).'tca/class.tx_propertyfields_tca.php');

$TCA['tx_mnepiserver2typo3_episerver'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:mn_episerver2typo3/locallang_db.xml:tx_mnepiserver2typo3_episerver',		
		'label'     => 'domain',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField'            => 'sys_language_uid',	
		'transOrigPointerField'    => 'l10n_parent',	
		'transOrigDiffSourceField' => 'l10n_diffsource',	
		'default_sortby' => 'ORDER BY crdate',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_mnepiserver2typo3_episerver.gif',
	),
);

$TCA['tx_mnepiserver2typo3_episerver_installation_languages'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:mn_episerver2typo3/locallang_db.xml:tx_mnepiserver2typo3_episerver_installation_languages',		
		'label'     => 'installation_uid',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField'            => 'sys_language_uid',	
		'transOrigPointerField'    => 'l10n_parent',	
		'transOrigDiffSourceField' => 'l10n_diffsource',	
		'default_sortby' => 'ORDER BY crdate',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_mnepiserver2typo3_episerver_language_translation.gif',
	),
);

/*if (TYPO3_MODE == 'BE') {
	t3lib_extMgm::addModulePath('web_txmnepiserver2typo3M1', t3lib_extMgm::extPath($_EXTKEY) . 'mod1/');
		
	t3lib_extMgm::addModule('web', 'txmnepiserver2typo3M1', '', t3lib_extMgm::extPath($_EXTKEY) . 'mod1/');
}*/

?>