<?php

class tx_propertyfields_tca {
    
    /**
	 * Returns a list of recipients.
	 * 
	 * @param array $params User parameters
	 * @param t3lib_TCEforms $pObj Parent object
	 * @return array
	 */
    public function user_renderPropertyFields($params, $pObj) {
        /*// Add tt_address #4 to the recipient list
		$params['lists']['tt_address'][] = 4;

			// Add frontend user #1 to the recipient list
		$params['lists']['fe_users'][] = 1;

			// Retrieve user parameters
		$sizeOfRecipientList = $params['userParams'] ? $params['userParams'] : 2;
		for ($i = 0; $i < $sizeOfRecipientList; $i++) {
			$params['lists']['PLAINLIST'][] = array('name' => 'John Doo #' . $i, 'email' => 'john.doo-' . $i . '@hotmail.com');
		}*/
        /*print_r($params);
        print_r($pObj);*/
        
        //$html = "<script type='text/javascript' src='" . t3lib_extMgm::extRelPath('mn_episerver2typo3') . "tca/property_fields.js'></script>";
                
        //$html .= "<input type='text' value='najs' />";
        
        /*print_r($params);
        print_r($pObj);
        exit;*/
        
        $html = 
            "<style type='text/css'>
                /* FIX: for if there is no input fields then TYPO3 CSS images block the add button */
                img[name=cm_tx_mnepiserver2typo3_episerver_2_episerver_content_fields] {
                    display: none;
                }
                img[name=req_tx_mnepiserver2typo3_episerver_2_episerver_content_fields] {
                    display: none;
                }
            </style>";
        
        $contentFieldsArray = explode(",", $params["row"]["episerver_content_fields"]);
        $html .= '<div id="episerver_content_wrapper">';
        foreach($contentFieldsArray as $contentField) {
            $html .= $this->generateInputField($contentField);
        }
        $html .= '</div>';
        
        $html .= '<span id="episerver_content_field_add" class="t3-icon t3-icon-actions t3-icon-actions-document t3-icon-document-new" style="cursor: pointer; float: left; clear: both;">&nbsp;</span>';
        
        $html .= $this->generateJavascript();
        
        return $html;
    }
    
    /**
     * tx_propertyfields_tca::generateJavascript()
     * Generate the necessary javascript for the logic.
     * 
     * @return string $html
     */
    public function generateJavascript() {
        $html = 
            '<script type="text/javascript">
                Ext.onReady(function() {
            
                    var hiddenInput = "";
                    
                    //Get the hidden field with value on page load
                    Ext.each(Ext.query("[type=hidden][name*=episerver_content_fields]"), function(item, index) {    
                        hiddenInput = item;
                    });
                    
                    //console.log(hiddenInput.getValue().split(","));
                    
                    //Hide default input type in TCA
                    hiddenInput.parentNode.hide();
                    
                    //Add content field
                    Ext.select("#episerver_content_field_add").on("click", function () { 
                        Ext.DomHelper.append("episerver_content_wrapper", "' . $this->generateInputField("") . '");
                    });
                    
                    //On record save
                    Ext.select(".buttongroup .c-inputButton").on("click", function() {
                        var contentString = "";
                        var contentFields = Ext.query(".episerver_content_field_container .episerver_content_field");
                        Ext.each(contentFields, function(item) {
                            var value = Ext.get(item).getValue();
                            if(value != "") {
                                contentString += Ext.get(item).getValue() + ",";    
                            }
                        });
                        
                        Ext.query("[type=hidden][name*=episerver_content_fields]")[0].setValue(contentString.substring(0, contentString.length-1));
                    });
                    
                });
                
                function removeInputField(elem) {
                    Ext.get(elem).parent().remove();
                }
                
            </script>';
        return $html;
    }
    
    /**
     * tx_propertyfields_tca::generateInputField()
     * Generate the input field for content.
     * 
     * @param string $value
     * @return string $html
     */
    public function generateInputField($value) {
        //Oneliner because of javascript string compability
        $html = "<div class='episerver_content_field_container'><input type='text' value='" . $value . "' class='episerver_content_field formField1 tceforms-textfield' /><span class='t3-icon t3-icon-actions t3-icon-actions-edit t3-icon-edit-delete episerver_content_field_delete' style='cursor: pointer;' onclick='removeInputField(this);'>&nbsp;</span><br /></div>";
        return $html;
    }
    
}

?>