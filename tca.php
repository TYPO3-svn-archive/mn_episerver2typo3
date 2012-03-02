<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_mnepiserver2typo3_episerver'] = array (
	'ctrl' => $TCA['tx_mnepiserver2typo3_episerver']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,domain,ws_username,ws_password,episerver_startpage_id,t3_root_page_id,episerver_content_fields,episerver_languages'
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
        /*'episerver_content_fields' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:mn_episerver2typo3/locallang_db.xml:tx_mnepiserver2typo3_episerver.episerver_content_fields',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'max' => '255',	
                'default' => 'MainBody'
			)
		),*/
        'episerver_content_fields' => Array (
			'exclude' => 1,
		#	'l10n_mode' => 'exclude', // the localizalion mode will be handled by the userfunction
			'label' => 'LLL:EXT:mn_episerver2typo3/locallang_db.xml:tx_mnepiserver2typo3_episerver.episerver_content_fields',
			'config' => Array (
				/*'type' => 'select',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 10,
                'itemsProcFunc' => 'tx_propertyfields_tca->user_renderPropertyFields',*/
                'type' => 'input',
    			'size' => '40',
    			'wizards' => array(
    				'uproc' => array(
    					'type' => 'userFunc',
    					'userFunc' => 'tx_propertyfields_tca->user_renderPropertyFields',
    					'params' => array(),
    				),
    			), 
			)
		),
        'episerver_languages' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:mn_episerver2typo3/locallang_db.xml:tx_mnepiserver2typo3_episerver.episerver_languages',		
			'config' => array( 
                'type' => 'group', 
                'internal_type' => 'db', 
                'allowed' => 'sys_language', 
                'prepend_tname' => 1, 
                'size' => 5, 
                'maxitems' => 9999, 
                'MM' => 'tx_mnepiserver2typo3_episerver_language_mm',
            ) 
		),
	),
	'types' => array (
		'0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, domain, ws_username, ws_password, episerver_startpage_id, t3_root_page_id, episerver_content_fields, episerver_languages')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);



/*$TCA['tx_mnepiserver2typo3_episerver_language_translation'] = array (
	'ctrl' => $TCA['tx_mnepiserver2typo3_episerver_language_translation']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,domain,episerver_language_code,typo3_language_code'
	),
	'feInterface' => $TCA['tx_mnepiserver2typo3_episerver_language_translation']['feInterface'],
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
				'foreign_table'       => 'tx_mnepiserver2typo3_episerver_language_translation',
				'foreign_table_where' => 'AND tx_mnepiserver2typo3_episerver_language_translation.pid=###CURRENT_PID### AND tx_mnepiserver2typo3_episerver_language_translation.sys_language_uid IN (-1,0)',
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
        'episerver_language_code' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:mn_episerver2typo3/locallang_db.xml:tx_mnepiserver2typo3_episerver_language_translation.episerver_language_code',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'max' => '255',	
			)
		),
        'typo3_language_code' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:mn_episerver2typo3/locallang_db.xml:tx_mnepiserver2typo3_episerver_language_translation.typo3_language_code',		
    		'config' => array (            
            	'type'  => 'select',
    				'items' => array (
    					array('', 0),
				),
                'foreign_table' => 'sys_language',
            )
		),
	),
	'types' => array (
		'0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, domain, episerver_language_code, typo3_language_code')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);*/


$TCA['tx_mnepiserver2typo3_episerver_installation_languages'] = array (
	'ctrl' => $TCA['tx_mnepiserver2typo3_episerver_installation_languages']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,domain,episerver_language_code,typo3_language_code,installation_uid'
	),
	'feInterface' => $TCA['tx_mnepiserver2typo3_episerver_installation_languages']['feInterface'],
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
				'foreign_table'       => 'tx_mnepiserver2typo3_episerver_installation_languages',
				'foreign_table_where' => 'AND tx_mnepiserver2typo3_episerver_installation_languages.pid=###CURRENT_PID### AND tx_mnepiserver2typo3_episerver_installation_languages.sys_language_uid IN (-1,0)',
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
        'episerver_language_code' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:mn_episerver2typo3/locallang_db.xml:tx_mnepiserver2typo3_episerver_language_translation.episerver_language_code',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'max' => '255',	
			)
		),
        'typo3_language_code' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:mn_episerver2typo3/locallang_db.xml:tx_mnepiserver2typo3_episerver_language_translation.typo3_language_code',		
    		'config' => array (            
            	'type'  => 'select',
    				'items' => array (
    					array('English', 0),
				),
                'foreign_table' => 'sys_language',
            )
		),
        'installation_uid' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:mn_episerver2typo3/locallang_db.xml:tx_mnepiserver2typo3_episerver_installation_languages.installation_uid',		
    		'config' => array (            
            	'type'  => 'select',
    				'items' => array (
    					array('', 0),
				),
                'foreign_table' => 'tx_mnepiserver2typo3_episerver',
            )
		),
	),
	'types' => array (
		'0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, domain, episerver_language_code, typo3_language_code, installation_uid')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);

?>