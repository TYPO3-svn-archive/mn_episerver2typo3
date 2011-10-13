<?php

/**
 * Queries for the local TYPO3 database
 * 
 * @author  Mattias Nilsson (tollepjaer@gmail.com)
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
        $insertArray = array(
            'pid' => $pageArray["pid"],
            'title' => $pageArray["PageName"],
            'tx_mnepiserver2typo3_episerver_id' => $pageArray["PageLink"],
            'tstamp' => mktime(),
            'crdate' => mktime(),   
            'urltype' => 1,
            'doktype' => 1,
            'cruser_id' => 1,
            'sorting' => 512,    
            /*'t3ver_label' => '',
            'perms_userid' => 1,
            'perms_groupid' => 5,
            'perms_user' =>	31,
            'perms_group' => 27,   */  
        );
        $res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('pages', $insertArray);
        $lastInsertId = mysql_insert_id();
        return $lastInsertId;
    }
    
}

?>