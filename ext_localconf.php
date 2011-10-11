<?php
/* $Id$ */

if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_mnepiserver2typo3_TestConnectionTask'] = array(
		'extension'        => $_EXTKEY,
		'title'            => 'Name', //'LLL:EXT:' . $_EXTKEY . '/locallang.xml:testTask.name',
		'description'      => 'Description', //'LLL:EXT:' . $_EXTKEY . '/locallang.xml:testTask.description',
		//'additionalFields' => 'tx_scheduler_TestTask_AdditionalFieldProvider'
	);
	
?>
