<?php

require_once('/nusoap/nusoap.php');

/**
 * The class for connecting to webservice in EPiServer.
 * 
 * @author  Mattias Nilsson (tollepjaer@gmail.com)
 * @version 1.0 
 */    
class WebserviceConnect {
        
    var $domain = "";
    var $wsUserName = "";
    var $wsPassword = "";
    var $client = "";
    
    public function __construct($domain, $wsUserName, $wsPassword) {
        $this->domain = $domain;
        $this->wsUserName = $wsUserName;
        $this->wsPassword = $wsPassword;
    }
    
    /**
     * THe function to connect to the webservice.
     * 
     * @return  void
     */
    private function connectToWebservice() {
        $this->client = new nusoap_client("http://" . $this->domain . "/WebServices/DataFactoryService.asmx?WSDL", 'wsdl');
        $this->client->setCredentials($this->wsUserName, $this->wsPassword);
    }
    
    /**
     * A function to test the connectivity to the webservice.
     * 
     * @return boolean $success  
     */
    public function testEpiserverConnection() {
        $success = false;
        $this->connectToWebservice();
        $result = $this->client->call('Ping');
        // Check for a fault
        if ($this->client->fault) {
        	$success = false;
        } else {
        	// Check for errors
        	$err = $this->client->getError();
        	if ($err) {
        		$success = false;
        	} else {
                $success = $result["PingResult"];
        	}
        }
        
        return $success;
    }
    
    
    /**
     * WebserviceConnect::getPage()
     * 
     * @param integer $pageId
     * @param integer $workId
     * @param string $remoteSite
     * @return
     */
    public function getPage($pageId, $workId, $remoteSite) {
        $success = false;
        $param = array(
            'ID' => $pageId, 
            'WorkID' => $workId, 
            'RemoteSite' => $remoteSite
        );
        $this->connectToWebservice();
        $result = $this->client->call('GetPage', array('pageLink' => $param), '', '', false, true);
        // Check for a fault
        if ($this->client->fault) {
        	$success = false;
        } else {
        	// Check for errors
        	$err = $this->client->getError();
        	if ($err) {
        		$success = false;
        	} else {
                $success = $result;
        	}
        }
        
        return $success;
    }
        
}

?>