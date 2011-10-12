<?php

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
    
    private function connectToWebservice() {
        $this->client = new SoapClient(
            "http://" . $this->domain . "/WebServices/DataFactoryService.asmx?WSDL", 
            array(
                'login' => $this->wsUserName,
                'password' => $this->wsPassword
            )
        );
    }
    
    public function testEpiserverConnection() {
        $success = "";
        $this->connectToWebservice();
        try {
            $data = $this->client->ping();
            $success = ($data->PingResult == 1) ? true : false;
        } catch (SoapFault $fault) {
            $success = false;
        } 
        return $success;
    }
        
}

?>