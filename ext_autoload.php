<?php
/*
 * Register necessary class names with autoloader
 *
 * $Id$
 */
$extensionPath = t3lib_extMgm::extPath('mn_episerver2typo3');
return array(
	'tx_mnepiserver2typo3_testconnectiontask' => $extensionPath . 'scheduler/class.tx_mnepiserver2typo3_testconnectiontask.php',
    'tx_mnepiserver2typo3_testconnectiontask_additionalfieldprovider' => $extensionPath . 'scheduler/class.tx_mnepiserver2typo3_testconnectiontask_additionalfieldprovider.php',
	'tx_mnepiserver2typo3_importdatatask' => $extensionPath . 'scheduler/class.tx_mnepiserver2typo3_importdatatask.php',
    'tx_mnepiserver2typo3_importdatatask_additionalfieldprovider' => $extensionPath . 'scheduler/class.tx_mnepiserver2typo3_importdatatask_additionalfieldprovider.php',
);
?>