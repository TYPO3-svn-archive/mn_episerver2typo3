<?php

/**
 * Queries for the local TYPO3 database
 * 
 * @author  Mattias Nilsson <tollepjaer@gmail.com>
 * @version 1.0 
 */ 
class DatabaseQueries {
    
    public function __construct() {
        
    }
    
    /**
     * DatabaseQueries::getWebserviceCredentials()
     * 
     * @param integer $credentialUid
     * @return array $data
     */
    public function getWebserviceCredentials($credentialUid) {
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                '*',         // SELECT ...
                'tx_mnepiserver2typo3_episerver',     // FROM ...
                'uid = ' . $credentialUid . " AND deleted != 1 AND hidden != 1",    // WHERE...
                '',            // GROUP BY...
                '',    // ORDER BY...
                ''            // LIMIT to 10 rows, starting with number 5 (MySQL compat.)
        );
        while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $data = $row;
        }
        return $data;
    }
    
    /**
     * Get all config setups for EPiServer.
     * 
     * @return  array   The config setups in the database
     */
    public function getAllWebserviceCredentials() {
        $data = array();
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',         // SELECT ...
            'tx_mnepiserver2typo3_episerver',     // FROM ...
            'deleted != 1 AND hidden != 1',    // WHERE...
            '',            // GROUP BY...
            'domain',    // ORDER BY...
            ''            // LIMIT to 10 rows, starting with number 5 (MySQL compat.)
        );
        while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $data[] = $row;
        }
        return $data;
    }
    
    /**
     * DatabaseQueries::insertPageData()
     * Insert the page data into the pages table.
     * 
     * @param array $pageArray
     * @return integer $lastInsertId
     */
    public function insertPageData($pageArray) {
        if($pageArray["PageName"] != "") {
            $insertArray = array(
                'pid' => $pageArray["pid"],
                'title' => $pageArray["PageName"],
                'nav_title' => $pageArray["PageName"],
                'tx_mnepiserver2typo3_episerver_id' => $pageArray["PageLink"],
                'tx_mnepiserver2typo3_episerver_site_id' => $pageArray["EpiserverSiteId"],
                'tstamp' => mktime(),
                'crdate' => mktime(),   
                'urltype' => 1,
                'doktype' => 1,
                'cruser_id' => $GLOBALS["BE_USER"]->user["uid"],
           	    'author' => $GLOBALS["BE_USER"]->user["realName"],
                'author_email' => $GLOBALS["BE_USER"]->user["email"], 
                'sorting' => 0,
                'nav_hide' => ($pageArray["PageVisibleInMenu"] == True) ? 0 : 1,
            );
            $res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('pages', $insertArray);
            $lastInsertId = mysql_insert_id();
            return $lastInsertId;    
        }
        else {
            return 0;    
        }
    }
    
    /**
     * DatabaseQueries::updatePageData()
     * Update a the page data in the pages table.
     * 
     * @param array $pageArray
     * @return integer  
     */
    public function updatePageData($pageArray) {
        $updateArray = array(
            'pid' => $pageArray["pid"],
            'title' => utf8_encode($pageArray["PageName"]),
            'nav_title' => utf8_encode($pageArray["PageName"]),
            'tstamp' => mktime(),   
            'urltype' => 1,
            'doktype' => 1,
            'cruser_id' => $GLOBALS["BE_USER"]->user["uid"],
           	'author' => $GLOBALS["BE_USER"]->user["realName"],
            'author_email' => $GLOBALS["BE_USER"]->user["email"], 
            'sorting' => 0,
            'nav_hide' => ($pageArray["PageVisibleInMenu"] == True) ? 0 : 1
        );
        $res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery('pages', 'tx_mnepiserver2typo3_episerver_id=' . $pageArray["PageLink"], $updateArray);
        return $res;
    }
    
    /**
     * DatabaseQueries::getPageT3()
     * Get the page data from TYPO3 database.
     * 
     * @param integer $episerverPageId
     * @return array $data
     */
    public function getPageInT3($episerverPageId) {
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*', 
            'pages', 
            'deleted != 1 AND tx_mnepiserver2typo3_episerver_id=' . $episerverPageId, 
            '', 
            '',
            ''
        );
        while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $data = $row;
        }
        return $data;
    }
    
    /**
     * DatabaseQueries::checkIfPageExist()
     * Check if a page exist in the database.
     * 
     * @param integer $episerverPageId
     * @return boolean $pageExist
     */
    public function checkIfPageExist($episerverPageId) {
        $pageExist = false;
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*', 
            'pages', 
            'deleted != 1 AND hidden != 1 AND tx_mnepiserver2typo3_episerver_id=' . $episerverPageId
        );
        while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            if($row["uid"]) {
                $pageExist = true;        
            }
        }
        return $pageExist;
    }
    
    /**
     * DatabaseQueries::insertPageContent()
     * Insert the content for a page.
     * 
     * @param array $pageArray
     * @param integer $pid
     * @return integer $lastInsertId
     */
    public function insertPageContent($pageArray, $pid, $episerverContentArray) {
        foreach($episerverContentArray as $contentItem) {
            if($pageArray["PageName"] != "" && $pageArray[$contentItem] != "") {
                $insertArray = array(
                    'pid' => $pid,
                    'header' => utf8_encode($pageArray["PageName"]),
                    'bodytext' => utf8_encode($pageArray[$contentItem]),
                    'tx_mnepiserver2typo3_episerver_id' => $pageArray["PageLink"],
                    'tx_mnepiserver2typo3_episerver_site_id' => $pageArray["EpiserverSiteId"],
                    'CType' => 'text',
                    'colPos' => 0,
                    'tstamp' => mktime(),
                    'crdate' => mktime(),  
                    'sys_language_uid' => $pageArray["Typo3SystemLanguageUid"]
                );
                $res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tt_content', $insertArray);   
            }  
        }
    }
    
    /**
     * DatabaseQueries::deleteImportedPagesAndContent()
     * Delete all imported pages and page content.
     * 
     * @return void
     */
    public function deleteImportedPagesAndContent($episerverSiteId) {
        $GLOBALS['TYPO3_DB']->exec_DELETEquery('pages', 'tx_mnepiserver2typo3_episerver_id != 0 AND tx_mnepiserver2typo3_episerver_site_id = ' . $episerverSiteId);
        $GLOBALS['TYPO3_DB']->exec_DELETEquery('pages_language_overlay', 'tx_mnepiserver2typo3_episerver_id != 0 AND tx_mnepiserver2typo3_episerver_site_id = ' . $episerverSiteId);
        $GLOBALS['TYPO3_DB']->exec_DELETEquery('tt_content', 'tx_mnepiserver2typo3_episerver_id != 0 AND tx_mnepiserver2typo3_episerver_site_id = ' . $episerverSiteId);
    }
    
    /**
     * DatabaseQueries::getLanguagesForEpiserverRecord()
     * Get all the languages for a Episerver record.
     * 
     * @param integer $recordUid
     * @return array $languages
     */
    public function getLanguagesForEpiserverRecord($recordUid) {
        $languages = array();
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'uid_foreign', 
            'tx_mnepiserver2typo3_episerver_language_mm', 
            'uid_local = ' . $recordUid
        );
        while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $languages[] = $row;        
        }
        return $languages;
        
    }
    
    /**
     * Get the translated TYPO3 language to EPiServer language code.
     * 
     * @param string $typo3LanguageCode
     * @return string $language
     */
    public function getTranslatedLanguage($typo3LanguageCode) {
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'episerver_language_code', 
            'tx_mnepiserver2typo3_episerver_language_translation', 
            'typo3_language_code = "' . $typo3LanguageCode . '"'
        );
        while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $language = $row["episerver_language_code"];        
        }
        return $language;
    }
    
    /**
     * DatabaseQueries::getSystemLanguages()
     * Get the TYPO3 system language.
     * 
     * @return array $languages
     */
    public function getSystemLanguage($languageUid) {
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*', 
            'sys_language', 
            'uid = ' . $languageUid
        );
        while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $language = $row;        
        }
        return $language;
    }
    
    /**
     * DatabaseQueries::insertEpiserverLanguage()
     * Insert language from EPiServer into TYPO3.
     * 
     * @param string $episerverCountryCode
     * @param string $typo3LanguageCode
     * @param integer $installationUid
     * @return void
     */
    public function insertEpiserverLanguage($episerverCountryCode, $typo3LanguageCode, $installationUid) {
        $insertArray = array(
            'tstamp' => mktime(),
            'crdate' => mktime(),
            'episerver_language_code' => $episerverCountryCode,
            'typo3_language_code' => $typo3LanguageCode,
            'installation_uid' => $installationUid
        );
        $res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mnepiserver2typo3_episerver_installation_languages', $insertArray);  
    }
    
    /**
     * DatabaseQueries::checkIfEpiserverLanguageExistInTypo3()
     * Check if a language already has been imported from a specific EPiServer. 
     * 
     * @param string $episerverLanguageCode
     * @param integer $installationUid
     * @return
     */
    public function checkIfEpiserverLanguageExistInTypo3($episerverLanguageCode, $installationUid) {
        $exist = false;
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*', 
            'tx_mnepiserver2typo3_episerver_installation_languages', 
            'episerver_language_code LIKE "' . $episerverLanguageCode . '" AND installation_uid = ' . $installationUid
        );
        while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            if($row) {
                $exist = true;
                break;
            }
        }
        return $exist;
    }
    
    /**
     * DatabaseQueries::getEpiserverLanguageCode()
     * 
     * @param integer $typo3LanguageUid
     * @param integer $installationUid
     * @return string $code
     */
    public function getEpiserverLanguageCode($typo3LanguageUid, $installationUid) {
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*', 
            'tx_mnepiserver2typo3_episerver_installation_languages', 
            'typo3_language_code = "' . $typo3LanguageUid . '" AND installation_uid = ' . $installationUid
        );
        while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $code = $row["episerver_language_code"];
        }
        return $code;
    }
    
    /**
     * DatabaseQueries::getTypo3SpecificLanguagesByRecordUid()
     * 
     * @param integer $recordUid
     * @return array $languageArray
     */
    public function getTypo3SpecificLanguagesByRecordUid($recordUid) {
        $languageArray = array();
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*', 
            'tx_mnepiserver2typo3_episerver_language_mm', 
            'uid_local = ' . $recordUid
        );
        while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $languageArray[] = $row["uid_foreign"];
        }
        return $languageArray;
    }
    
    /**
     * Create a language specific page.
     * 
     * @param array $pageArray
     * @param integer $originalPid
     * @return integer $lastInsertId
     */
    public function createLanguageSpecificPage($pageArray, $originalPid) {
        if($pageArray["PageName"] != "") {
            $insertArray = array(
                'pid' => $originalPid,
                'title' => utf8_encode($pageArray["PageName"]),
                'nav_title' => utf8_encode($pageArray["PageName"]),
                'tx_mnepiserver2typo3_episerver_id' => $pageArray["PageLink"],
                'tx_mnepiserver2typo3_episerver_site_id' => $pageArray["EpiserverSiteId"],
                'tstamp' => mktime(),
                'crdate' => mktime(),   
                'author' => $GLOBALS["BE_USER"]->user["realName"],
                'author_email' => $GLOBALS["BE_USER"]->user["email"], 
                'urltype' => 1,
                'doktype' => 1,
                't3ver_oid' => 0,
                't3ver_id' => 0,
                't3ver_wsid' => 0,
                't3ver_state' => 0,
                't3ver_stage' => 0,
                't3ver_count' => 0,
                't3ver_tstamp' => 0,
                't3_origuid' => 0,
                'hidden' => 0,
                'starttime' => 0,
                'endtime' => 0,
                'deleted' => 0,
                'tx_impexp_origuid' => 0,
                'shortcut' => 0,
                'shortcut_mode' => 0,
                'cruser_id' => $GLOBALS["BE_USER"]->user["uid"],
                'sys_language_uid' => $pageArray["Typo3SystemLanguageUid"]
            );
            $res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('pages_language_overlay', $insertArray);
            $lastInsertId = mysql_insert_id();
            return $lastInsertId;    
        }
        else {
            return 0;    
        }
    }
        
}

?>