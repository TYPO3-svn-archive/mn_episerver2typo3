<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Markus Friedrich (markus.friedrich@dkd.de)
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
 * Class "tx_mnepiserver2typo3_DeleteDataTask" deletes all the pages and page content imported.
 *
 * @author		Mattias Nilsson <tollepjaer@gmail.com>
 * @package		TYPO3
 * @subpackage	tx_scheduler
 *
 * $Id$
 */
class tx_mnepiserver2typo3_DeleteDataTask extends tx_scheduler_Task {

    /**
	 * A domain to be used during the process
	 *
	 * @var	string		$domain
	 */
	 var $domain = "";

	/**
	 * Function executed from the Scheduler.
	 * Delete the data imported from EPiServer.
	 *
	 * @return	void
	 */
	public function execute() {
		$success = false; 
        
        if (!empty($this->domain)) { 
            try {
                    
                $databaseQueries = new DatabaseQueries();
                $databaseQueries->deleteImportedPagesAndContent($this->domain);
                                               
                $success = true;
                  
                if($success == true) { 
                    t3lib_div::devLog('[tx_mnepiserver2typo3_DeleteDataTask]: Data from that has been imported from EPiServer has been removed.', 'scheduler', 0);    
                }   
                else { 
                    t3lib_div::devLog('[tx_mnepiserver2typo3_DeleteDataTask]: Data from that has been imported from EPiServer has NOT been removed.', 'scheduler', 2);
                } 
            }
            catch (Exception $e) {
                $success = false;
            }
            
        } else {
            // No config defined, just log the task
            t3lib_div::devLog('[tx_mnepiserver2typo3_DeleteDataTask]: No config is defined', 'scheduler', 2);
		}
        
		return $success;
	}
    
    /**
	 * This method returns the destination domain as additional information
	 *
	 * @return	string	Information to display
	 */
	public function getAdditionalInformation() {
        $databaseQueries = new DatabaseQueries();
        $domainName = $databaseQueries->getWebserviceCredentials($this->domain);         
		return $GLOBALS['LANG']->sL('LLL:EXT:mn_episerver2typo3/locallang.xml:label.pagesToDelete') . ": " . $domainName["domain"];
	}

}

?>