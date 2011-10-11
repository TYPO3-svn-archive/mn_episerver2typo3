<?php
/*
 * Register necessary class names with autoloader
 *
 * $Id$
 */
$extensionPath = t3lib_extMgm::extPath('mn_episerver2typo3');
return array(
	'tx_mnepiserver2typo3_testconnectiontask' => $extensionPath . 'scheduler/class.tx_mnepiserver2typo3_testconnectiontask.php',
	//'tx_scheduler_testtask_additionalfieldprovider' => $extensionPath . 'scheduler/class.tx_scheduler_testtask_additionalfieldprovider.php',
);
?>