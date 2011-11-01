<?php
/* $Id$ */

if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_mnepiserver2typo3_TestConnectionTask'] = array(
		'extension'        => $_EXTKEY,
		'title'            => 'LLL:EXT:' . $_EXTKEY . '/locallang.xml:testConnectionTask.name',
		'description'      => 'LLL:EXT:' . $_EXTKEY . '/locallang.xml:testConnectionTask.description',
        'additionalFields' => 'tx_mnepiserver2typo3_TestConnectionTask_AdditionalFieldProvider'
	);
    
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_mnepiserver2typo3_ImportDataTask'] = array(
		'extension'        => $_EXTKEY,
		'title'            => 'LLL:EXT:' . $_EXTKEY . '/locallang.xml:importDataTask.name',
		'description'      => 'LLL:EXT:' . $_EXTKEY . '/locallang.xml:importDataTask.description',
        'additionalFields' => 'tx_mnepiserver2typo3_ImportDataTask_AdditionalFieldProvider'
	);
	
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_mnepiserver2typo3_DeleteDataTask'] = array(
		'extension'        => $_EXTKEY,
		'title'            => 'LLL:EXT:' . $_EXTKEY . '/locallang.xml:deleteDataTask.name',
		'description'      => 'LLL:EXT:' . $_EXTKEY . '/locallang.xml:deleteDataTask.description',
        'additionalFields' => 'tx_mnepiserver2typo3_DeleteDataTask_AdditionalFieldProvider'
	);
    
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_mnepiserver2typo3_ImportLanguagesTask'] = array(
		'extension'        => $_EXTKEY,
		'title'            => 'LLL:EXT:' . $_EXTKEY . '/locallang.xml:importLanguagesTask.name',
		'description'      => 'LLL:EXT:' . $_EXTKEY . '/locallang.xml:importLanguagesTask.description',
        'additionalFields' => 'tx_mnepiserver2typo3_ImportLanguagesTask_AdditionalFieldProvider'
	);
    
?>
