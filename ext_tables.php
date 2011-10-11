<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
$tempColumns = array (
	'tx_mnepiserver2typo3_episerver_id' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:mn_episerver2typo3/locallang_db.xml:pages.tx_mnepiserver2typo3_episerver_id',		
		'config' => array (
			'type'     => 'input',
			'size'     => '4',
			'max'      => '4',
			'eval'     => 'int',
			'checkbox' => '0',
			'range'    => array (
				'upper' => '1000',
				'lower' => '10'
			),
			'default' => 0
		)
	),
);


t3lib_div::loadTCA('pages');
t3lib_extMgm::addTCAcolumns('pages',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('pages','tx_mnepiserver2typo3_episerver_id;;;;1-1-1');

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


if (TYPO3_MODE == 'BE') {
	t3lib_extMgm::addModulePath('web_txmnepiserver2typo3M1', t3lib_extMgm::extPath($_EXTKEY) . 'mod1/');
		
	t3lib_extMgm::addModule('web', 'txmnepiserver2typo3M1', '', t3lib_extMgm::extPath($_EXTKEY) . 'mod1/');
}
?>