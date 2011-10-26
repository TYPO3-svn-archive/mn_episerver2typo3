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
	 * The choice for update pages
	 *
	 * @var	string		$update_pages
	 */
	 var $update_pages = "";

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
                
                //Get the mapped content fields in config for EPiServer specific fields to import
                $episerverContentArray = $this->generateContentFields($loginCredentials["episerver_content_fields"]);
                
                $this->domain = $loginCredentials["domain"];
                $webserviceObject = new WebserviceConnect($this->domain, $loginCredentials["ws_username"], $loginCredentials["ws_password"]);
                
                $startPage = $webserviceObject->getPage($loginCredentials["episerver_startpage_id"], 0, "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
                $firstLevel = $webserviceObject->getChildren($loginCredentials["episerver_startpage_id"], 0, "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
                
                $pageData = array();
                //Get the startpage of the structure and generate a PageDataArray
                $startPageArray = $this->generatePageDataArray($startPage["GetPageResult"]["Property"]["RawProperty"], $loginCredentials["t3_root_page_id"], $loginCredentials["uid"], $episerverContentArray);
                $pageData[$startPageArray["PageLink"]] = $startPageArray;
                //Then insert starpage into the database
                $insertPage = new DatabaseQueries();
                foreach($pageData as $page) {
                    $tempPageData = $insertPage->getPageInT3($page["PageLink"]);
                    if($tempPageData["uid"] > 0) {
                        //Update page if choosen in scheduler
                        if($this->update_pages == "true") {
                            $insertPage->updatePageData($page);    
                        }
                        $startPageId = $tempPageData["uid"];
                    }
                    else {
                        $startPageId = $insertPage->insertPageData($page);    
                        $insertPage->insertPageContent($page, $startPageId, $episerverContentArray);
                    }
                }
                
                $pageData = array();
                /** First level data sorting and inserting */
                if(is_array($firstLevel["GetChildrenResult"])) {
                    //Iterate the first level of pages
                    foreach($firstLevel["GetChildrenResult"]["RawPage"] as $pageItem) {
                        $tempData = array();
                        $pageId = 0;
                        foreach($pageItem["Property"]["RawProperty"] as $pageProperties) {
                            $tempData[] = $pageProperties;
                            if($pageProperties["Name"] == "PageLink") {
                                $pageId = $pageProperties["Value"];
                            }
                            //Set that page is first level item
                            $tempData[] = array("Name" => "IsFirstLevel", "Value" => true);
                        }
                        $pageData[$pageId] = $this->generatePageDataArray($tempData, $startPageId, $loginCredentials["uid"], $episerverContentArray);
                        
                        //Second level
                        $secondLevel = $webserviceObject->getChildren($pageId, 0, "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
                        if(is_array($secondLevel["GetChildrenResult"])) {
                            foreach($secondLevel["GetChildrenResult"]["RawPage"] as $secondLevelPageItem) {
                                $secondLevelTempData = array();
                                $secondLevelPageId = 0;
                                if(is_array($secondLevelPageItem)) {
                                    foreach($secondLevelPageItem["Property"]["RawProperty"] as $secondLevelPageProperties) {
                                        $secondLevelTempData[] = $secondLevelPageProperties;
                                        if($secondLevelPageProperties["Name"] == "PageLink") {
                                            $secondLevelPageId = $secondLevelPageProperties["Value"];
                                        }
                                    }
                                    $pageData[$secondLevelPageId] = $this->generatePageDataArray($secondLevelTempData, $pageId, $loginCredentials["uid"], $episerverContentArray);
                                    
                                    //Third level
                                    $thirdLevel = $webserviceObject->getChildren($secondLevelPageId, 0, "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
                                    if(is_array($thirdLevel["GetChildrenResult"])) {
                                        foreach($thirdLevel["GetChildrenResult"]["RawPage"] as $thirdLevelPageItem) {
                                            $thirdLevelTempData = array();
                                            $thirdLevelPageId = 0;
                                            if(is_array($thirdLevelPageItem)) {
                                                foreach($thirdLevelPageItem["Property"]["RawProperty"] as $thirdLevelPageProperties) {
                                                    $thirdLevelTempData[] = $thirdLevelPageProperties;
                                                    if($thirdLevelPageProperties["Name"] == "PageLink") {
                                                        $thirdLevelPageId = $thirdLevelPageProperties["Value"];
                                                    }
                                                }
                                                $pageData[$thirdLevelPageId] = $this->generatePageDataArray($thirdLevelTempData, $secondLevelPageId, $loginCredentials["uid"], $episerverContentArray);
                                                
                                                //Fourth level
                                                $fourthLevel = $webserviceObject->getChildren($thirdLevelPageId, 0, "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
                                                if(is_array($fourthLevel["GetChildrenResult"])) {
                                                    foreach($fourthLevel["GetChildrenResult"]["RawPage"] as $fourthLevelPageItem) {
                                                        $fourthLevelTempData = array();
                                                        $fourthLevelPageId = 0;
                                                        if(is_array($fourthLevelPageItem)) {
                                                            foreach($fourthLevelPageItem["Property"]["RawProperty"] as $fourthLevelPageProperties) {
                                                                $fourthLevelTempData[] = $fourthLevelPageProperties;
                                                                if($fourthLevelPageProperties["Name"] == "PageLink") {
                                                                    $fourthLevelPageId = $fourthLevelPageProperties["Value"];
                                                                }
                                                            }
                                                            $pageData[$fourthLevelPageId] = $this->generatePageDataArray($fourthLevelTempData, $thirdLevelPageId, $loginCredentials["uid"], $episerverContentArray);
                                                            
                                                            //Fifth level
                                                            $fifthLevel = $webserviceObject->getChildren($fourthLevelPageId, 0, "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
                                                            if(is_array($fifthLevel["GetChildrenResult"])) {
                                                                foreach($fifthLevel["GetChildrenResult"]["RawPage"] as $fifthLevelPageItem) {
                                                                    $fifthLevelTempData = array();
                                                                    $fifthLevelPageId = 0;
                                                                    if(is_array($fifthLevelPageItem)) {
                                                                        foreach($fifthLevelPageItem["Property"]["RawProperty"] as $fifthLevelPageProperties) {
                                                                            $fifthLevelTempData[] = $fifthLevelPageProperties;
                                                                            if($fifthLevelPageProperties["Name"] == "PageLink") {
                                                                                $fifthLevelPageId = $fifthLevelPageProperties["Value"];
                                                                            }
                                                                        }
                                                                        $pageData[$fifthLevelPageId] = $this->generatePageDataArray($fifthLevelTempData, $fourthLevelPageId, $loginCredentials["uid"], $episerverContentArray);
                                                                        
                                                                        //Sixth level
                                                                        $sixthLevel = $webserviceObject->getChildren($fifthLevelPageId, 0, "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
                                                                        if(is_array($sixthLevel["GetChildrenResult"])) {
                                                                            foreach($sixthLevel["GetChildrenResult"]["RawPage"] as $sixthLevelPageItem) {
                                                                                $sixthLevelTempData = array();
                                                                                $sixthLevelPageId = 0;
                                                                                if(is_array($sixthLevelPageItem)) {
                                                                                    foreach($sixthLevelPageItem["Property"]["RawProperty"] as $sixthLevelPageProperties) {
                                                                                        $sixthLevelTempData[] = $sixthLevelPageProperties;
                                                                                        if($sixthLevelPageProperties["Name"] == "PageLink") {
                                                                                            $sixthLevelPageId = $sixthLevelPageProperties["Value"];
                                                                                        }
                                                                                    }
                                                                                    $pageData[$sixthLevelPageId] = $this->generatePageDataArray($sixthLevelTempData, $fifthLevelPageId, $loginCredentials["uid"], $episerverContentArray);    
                                                                                }
                                                                            }
                                                                        }
                                                                            
                                                                    }
                                                                }
                                                            }
                                                                
                                                        }
                                                    }
                                                }
                                                    
                                            }
                                        }
                                    }
                                    
                                                                           
                                }
                            }
                        }
                        
                    }    
                }
                
                foreach($pageData as $page) {
                    $tempPageData = $insertPage->getPageInT3($page["PageLink"]);
                    if(!$page["IsFirstLevel"]) {
                        $parentUidInDatabaseArray = $insertPage->getPageInT3($page["PageParentLink"]);
                        $page["pid"] = $parentUidInDatabaseArray["uid"]; 
                    }
                    if($tempPageData["uid"] > 0) {
                        //Update page if choosen in scheduler
                        if($this->update_pages == "true") {
                            $insertPage->updatePageData($page);
                        }
                        $pageId = $tempPageData["uid"];
                    }
                    else {
                        $pageId = $insertPage->insertPageData($page);    
                        $insertPage->insertPageContent($page, $pageId, $episerverContentArray);
                    }
                }
                
                //Successful import
                $success = true;
                
                if($success == true) {
                    // Logging a successful import from EPiServer 
                    t3lib_div::devLog('[tx_mnepiserver2typo3_ImportDataTask]: Succesfully imported data from: ' . $this->domain, 'scheduler', 0);    
                }   
                else {
                    // Logging a unsuccessful import from EPiServer
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
     * tx_mnepiserver2typo3_ImportDataTask::generateContentFields()
     * Get the mapped content fields in config for EPiServer specific fields to import.
     * 
     * @param string $commaSeparatedString
     * @return array $contentArray
     */
    private function generateContentFields($commaSeparatedString) {
        if($commaSeparatedString) {
            $contentArray = explode(",", $commaSeparatedString);
            return $contentArray;
        }
    }
    
    /**
     * tx_mnepiserver2typo3_ImportDataTask::generatePageDataArray()
     * Generate a page data array.
     * 
     * @param array $data
     * @return array $pageArray
     */
    private function generatePageDataArray($data, $pid, $episerverSiteId, $contentArray) {
        $pageArray = array();
        foreach($data as $tempData) {
            //Values to use from the EPiServer page webservice
            if($tempData["Name"] == "PageLink" || $tempData["Name"] == "PageParentLink" || $tempData["Name"] == "PageDeleted" 
            || $tempData["Name"] == "PageSaved" || $tempData["Name"] == "PageChanged" || $tempData["Name"] == "PageCreatedBy" 
            || $tempData["Name"] == "PageMasterLanguageBranch" || $tempData["Name"] == "PageName" 
            || $tempData["Name"] == "PageVisibleInMenu" || $tempData["Name"] == "IsFirstLevel"
            || $tempData["Name"] == "PageMasterLanguageBranch" || in_array($tempData["Name"], $contentArray)) {    
                $pageArray[$tempData["Name"]] = $tempData["Value"];
            }
            //Set the parent id (pid)
            $pageArray["pid"] = $pid;
            $pageArray["EpiserverSiteId"] = $episerverSiteId;
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