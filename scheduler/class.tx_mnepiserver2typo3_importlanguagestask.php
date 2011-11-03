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
 * Class "tx_mnepiserver2typo3_ImportLanguagesTask" get the languages of a episerver installation.
 *
 * @author		Mattias Nilsson <tollepjaer@gmail.com>
 * @package		TYPO3
 * @subpackage	tx_scheduler
 *
 * $Id$
 */
class tx_mnepiserver2typo3_ImportLanguagesTask extends tx_scheduler_Task {

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
        $domainUid = $this->domain;
        $loginCredentials = $this->getLoginCredentials($this->domain);
        
		if (!empty($loginCredentials) && !empty($this->domain)) {
		  
            try {
                
                $this->domain = $loginCredentials["domain"];
                $webserviceObject = new WebserviceConnect($this->domain, $loginCredentials["ws_username"], $loginCredentials["ws_password"]);
                $insertPage = new DatabaseQueries();                
                
                //If languages is chosen for a record.
                if($loginCredentials["episerver_languages"] > 0) {    
                    $translatedLanguageArray = array();
                    $episerverLanguageArray = $webserviceObject->getLanguageBranches($loginCredentials["episerver_startpage_id"], 0, "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
                    foreach($episerverLanguageArray as $epiLang) {
                        if($epiLang == "en") {
                            $translatedLanguageArray[$epiLang] = 0;
                        }
                        else {
                            $translatedLanguageArray[$epiLang] = $insertPage->getTranslatedLanguage($epiLang);    
                        }
                    }
                    
                    foreach($translatedLanguageArray as $key => $value) {
                        if($insertPage->checkIfEpiserverLanguageExistInTypo3($key, $domainUid) != true) {
                            $insertPage->insertEpiserverLanguage($key, $value, $domainUid);    
                        }
                    }
                    
                    $success = true;
                }
                    
                if($success == true) { 
                    t3lib_div::devLog('[tx_mnepiserver2typo3_ImportLanguagesTask]: Languages has been imported from EPiServer.', 'scheduler', 0);    
                }   
                else { 
                    t3lib_div::devLog('[tx_mnepiserver2typo3_ImportLanguagesTask]: Languages has NOT been imported from EPiServer.', 'scheduler', 2);
                } 
            }
            catch (Exception $e) {
                $success = false;
            }
            
        } else {
            // No config defined, just log the task
            t3lib_div::devLog('[tx_mnepiserver2typo3_ImportLanguagesDataTask]: No config is defined', 'scheduler', 2);
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
		return $GLOBALS['LANG']->sL('LLL:EXT:mn_episerver2typo3/locallang.xml:label.languagesToimport') . ": " . $domainName["domain"];
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
     * tx_mnepiserver2typo3_ImportDataTask::getLanguages()
     * 
     * @param mixed $recordUid
     * @return
     */
    private function getLanguages($recordUid) {
        $dbConnect = new DatabaseQueries();
        $systemLanguage = array();
        foreach($dbConnect->getLanguagesForEpiserverRecord($recordUid) as $languageUid) {
            $systemLanguage[] = $dbConnect->getSystemLanguage($languageUid["uid_foreign"]);
        }
        return $systemLanguage;
    }

}

?>