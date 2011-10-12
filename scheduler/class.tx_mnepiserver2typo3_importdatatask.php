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
require_once(t3lib_extMgm::extPath('mn_episerver2typo3') . "lib/class.webservice_connect.php");

/**
 * Class "tx_mnepiserver2typo3_ImportDataTask" provides importing for the page data/structure
 *
 * @author		Mattias Nilsson <tollepjaer@gmail.com>
 * @package		TYPO3
 * @subpackage	tx_scheduler
 *
 * $Id$
 */ 
class tx_mnepiserver2typo3_ImportDataTask extends tx_scheduler_Task {
    
	/**
	 * A domain to be used during the process
	 *
	 * @var	string		$domain
	 */
	 var $domain = "";

	/**
	 * Function executed from the Scheduler.
	 * Test the connection to EPiServer.
	 *
	 * @return	void
	 */
	public function execute() {
		$success = false; 
        $loginCredentials = $this->getLoginCredentials($this->domain);
        
		if (!empty($loginCredentials) && !empty($this->domain)) {
            
            try {
                $this->domain = $loginCredentials["domain"];
                $webserviceObject = new WebserviceConnect($this->domain, $loginCredentials["ws_username"], $loginCredentials["ws_password"]);
                
                $firstLevel = $webserviceObject->getChildren($loginCredentials["episerver_startpage_id"], 0, "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
                
                $pageData = array();
                //Check if the result is an array
                if(is_array($firstLevel["GetChildrenResult"])) {
                    foreach($firstLevel["GetChildrenResult"]["RawPage"] as $pageItem) {
                        $tempData = array();
                        $pageId = 0;
                        foreach($pageItem["Property"]["RawProperty"] as $pageProperties) {
                            $tempData[] = $pageProperties;
                            if($pageProperties["Name"] == "PageLink") {
                                $pageId = $pageProperties["Value"];
                            }
                        }
                        $pageData[$pageId] = $this->generatePageDataArray($tempData);
                    }    
                }
                
                print_r($pageData);
                exit;
                
                //$success = $webserviceObject->testEpiserverConnection();
                
                if($success == true) {
                    // Logging a successful test to EPiServer 
                    t3lib_div::devLog('[tx_mnepiserver2typo3_ImportDataTask]: Connection with EPiServer is working for: ' . $this->domain, 'scheduler', 0);    
                }   
                else {
                    // Logging a successful test to EPiServer 
                    t3lib_div::devLog('[tx_mnepiserver2typo3_ImportDataTask]: Connection with EPiServer for: ' . $this->domain . ' failed.', 'scheduler', 2);
                } 
            }
            catch (Exception $e) {
                $success = false;
            }
          
		} else {
            // No config defined, just log the task
            t3lib_div::devLog('[tx_mnepiserver2typo3_ImportDataTask]: No config is defined', 'scheduler', 2);
		}

		return $success;
	}
    
    /**
     * tx_mnepiserver2typo3_ImportDataTask::generatePageDataArray()
     * Generate a page data array.
     * 
     * @param array $data
     * @return array $pageArray
     */
    private function generatePageDataArray($data) {
        $pageArray = array();
        foreach($data as $tempData) {
            if($tempData["Name"] == "PageLink" || $tempData["Name"] == "PageParentLink" || $tempData["Name"] == "PageDeleted" 
            || $tempData["Name"] == "PageSaved" || $tempData["Name"] == "PageChanged" || $tempData["Name"] == "PageCreatedBy" 
            || $tempData["Name"] == "PageMasterLanguageBranch" || $tempData["Name"] == "PageName") {
                $pageArray[$tempData["Name"]] = $tempData["Value"];
            }
        }
        return $pageArray;
    }

    /**
     * Getting the login credentials for the webservice.
     * 
     * @return  array  Login credentials
     */
    private function getLoginCredentials($credentialUid) {
        $loginArray = new DatabaseQueries();
        return $loginArray->getWebserviceCredentials($credentialUid);
    }

	/**
	 * This method returns the destination domain as additional information
	 *
	 * @return	string	Information to display
	 */
	public function getAdditionalInformation() {
        $databaseQueries = new DatabaseQueries();
        $domainName = $databaseQueries->getWebserviceCredentials($this->domain);         
		return $GLOBALS['LANG']->sL('LLL:EXT:mn_episerver2typo3/locallang.xml:label.pagesToImport') . ": " . $domainName["domain"];
	}
}

?>