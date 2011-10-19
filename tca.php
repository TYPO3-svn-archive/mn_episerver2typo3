<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_mnepiserver2typo3_episerver'] = array (
	'ctrl' => $TCA['tx_mnepiserver2typo3_episerver']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,domain,ws_username,ws_password,episerver_startpage_id,t3_root_page_id,episerver_content_fields'
	),
	'feInterface' => $TCA['tx_mnepiserver2typo3_episerver']['feInterface'],
	'columns' => array (
		'sys_language_uid' => array (		
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array (
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
				),
				'foreign_table'       => 'tx_mnepiserver2typo3_episerver',
				'foreign_table_where' => 'AND tx_mnepiserver2typo3_episerver.pid=###CURRENT_PID### AND tx_mnepiserver2typo3_episerver.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array (		
			'config' => array (
				'type' => 'passthrough'
			)
		),
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'domain' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:mn_episerver2typo3/locallang_db.xml:tx_mnepiserver2typo3_episerver.domain',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'max' => '255',	
				'eval' => 'required',
			)
		),
		'ws_username' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:mn_episerver2typo3/locallang_db.xml:tx_mnepiserver2typo3_episerver.ws_username',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'max' => '255',	
				'eval' => 'required',
			)
		),
		'ws_password' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:mn_episerver2typo3/locallang_db.xml:tx_mnepiserver2typo3_episerver.ws_password',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'max' => '255',	
				'eval' => 'required',
			)
		),
        'episerver_startpage_id' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:mn_episerver2typo3/locallang_db.xml:tx_mnepiserver2typo3_episerver.episerver_startpage_id',		
			'config' => array (
				'type' => 'input',	
				'size' => '3',	
				'eval' => 'required',
			)
		),
        't3_root_page_id' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:mn_episerver2typo3/locallang_db.xml:tx_mnepiserver2typo3_episerver.t3_root_page_id',		
			'config' => array (
				'type' => 'input',	
				'size' => '3',	
				'eval' => 'required',
			)
		),
        'episerver_content_fields' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:mn_episerver2typo3/locallang_db.xml:tx_mnepiserver2typo3_episerver.episerver_content_fields',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'max' => '255',	
                'default' => 'MainBody,'
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, domain, ws_username, ws_password, episerver_startpage_id, t3_root_page_id, episerver_content_fields')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);
?>