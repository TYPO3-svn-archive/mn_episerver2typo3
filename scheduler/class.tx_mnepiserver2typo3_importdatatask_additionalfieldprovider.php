<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009-2011 François Suter <francois@typo3.org>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(t3lib_extMgm::extPath('mn_episerver2typo3') . "lib/class.database_queries.php");

/**
 * Aditional fields provider class for usage with the ImportDataTask
 *
 * @author		Mattias Nilsson <tollepjaer@gmail.com>
 * @package		TYPO3
 * @subpackage	tx_scheduler
 *
 * $Id$
 */
class tx_mnepiserver2typo3_ImportDataTask_AdditionalFieldProvider implements tx_scheduler_AdditionalFieldProvider {

	/**
	 * This method is used to define new fields for adding or editing a task
	 * In this case, it adds an email field
	 *
	 * @param	array					$taskInfo: reference to the array containing the info used in the add/edit form
	 * @param	object					$task: when editing, reference to the current task object. Null when adding.
	 * @param	tx_scheduler_Module		$parentObject: reference to the calling object (Scheduler's BE module)
	 * @return	array					Array containg all the information pertaining to the additional fields
	 *									The array is multidimensional, keyed to the task class name and each field's id
	 *									For each field it provides an associative sub-array with the following:
	 *										['code']		=> The HTML code for the field
	 *										['label']		=> The label of the field (possibly localized)
	 *										['cshKey']		=> The CSH key for the field
	 *										['cshLabel']	=> The code of the CSH label
	 */
	public function getAdditionalFields(array &$taskInfo, $task, tx_scheduler_Module $parentObject) {

			// Initialize extra field value
		if (empty($taskInfo['domain'])) {
			if ($parentObject->CMD == 'add') {
					// In case of new task and if field is empty, set default domain
				$taskInfo['domain'] = $task->domain;

			} elseif ($parentObject->CMD == 'edit') {
					// In case of edit, and editing a test task, set to internal value if not data was submitted already
				$taskInfo['domain'] = $task->domain;
			} else {
					// Otherwise set an empty value, as it will not be used anyway
				$taskInfo['domain'] = '';
			}
		}
        
		// Write the code for the field
		$fieldID = 'task_domain';
        
        $databaseQueries = new DatabaseQueries();
        $fieldCode = '<select name="tx_scheduler[domain]" id="' . $fieldID . '">';
        foreach($databaseQueries->getAllWebserviceCredentials() as $item) {
            if($task->domain == $item["uid"]) {
                $fieldCode .= '<option selected="selected" value="' . $item["uid"] . '">' . $item["domain"] . ": " . $item["ws_username"] . "</option>";
            }
            else {
                $fieldCode .= '<option value="' . $item["uid"] . '">' . $item["domain"] . ": " . $item["ws_username"] . "</option>";    
            }
        }
        $fieldCode .= '</select>';        
		$additionalFields = array();
		$additionalFields[$fieldID] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:mn_episerver2typo3/locallang.xml:label.domain',
			'cshKey'   => '_MOD_tools_txmnepiserver2typo3M1',
			'cshLabel' => $fieldID
		);

		return $additionalFields;
	}

	/**
	 * This method checks any additional data that is relevant to the specific task
	 * If the task class is not relevant, the method is expected to return true
	 *
	 * @param	array					$submittedData: reference to the array containing the data submitted by the user
	 * @param	tx_scheduler_Module		$parentObject: reference to the calling object (Scheduler's BE module)
	 * @return	boolean					True if validation was ok (or selected class is not relevant), false otherwise
	 */
	public function validateAdditionalFields(array &$submittedData, tx_scheduler_Module $parentObject) {
		$submittedData['domain'] = trim($submittedData['domain']);

		if (empty($submittedData['domain'])) {
			$parentObject->addMessage($GLOBALS['LANG']->sL('LLL:EXT:mn_episerver2typo3/mod1/locallang.xml:msg.noDomain'), t3lib_FlashMessage::ERROR);
			$result = false;
		} else {
			$result = true;
		}

		return $result;
	}

	/**
	 * This method is used to save any additional input into the current task object
	 * if the task class matches
	 *
	 * @param	array				$submittedData: array containing the data submitted by the user
	 * @param	tx_scheduler_Task	$task: reference to the current task object
	 * @return	void
	 */
	public function saveAdditionalFields(array $submittedData, tx_scheduler_Task $task) {
		$task->domain = $submittedData['domain'];
	}
}

?>