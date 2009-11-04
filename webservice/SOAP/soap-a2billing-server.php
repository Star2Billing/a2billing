<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,   
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 * 
 * @copyright   Copyright (C) 2004-2009 - Star2billing S.L. 
 * @author      Belaid Arezqui <areski@gmail.com>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 * @package     A2Billing
 *
 * Software License Agreement (GNU Affero General Public License)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * 
**/

 
include ("../lib/admin.defines.php");


require_once('SOAP/Server.php');
require_once('SOAP/Disco.php');




class SOAP_A2Billing
{
	var $__dispatch_map = array();
	
	var $logfile = LOG_WEBSERVICE;
	
	var $system_security_key = API_SECURITY_KEY;
	
	var $instance_table;
	
	var $DBHandle;
	
	
	//Construct
	function SOAP_A2Billing() {
	
	    $this->instance_table = new Table();
	    
	    $this->DBHandle  = DbConnect();
	    
        // Define the signature of the dispatch map on the Web servicesmethod
		
        // Necessary for WSDL creation
		
        $this->__dispatch_map['Authenticate_Admin'] =
                 array('in' => array('security_key' => 'string', 'username' => 'string', 'pwd' => 'string'),
                       'out' => array('result' => 'boolean', 'message' => 'string')
                       );

        $this->__dispatch_map['Set_AdminPwd'] =
                 array('in' => array('security_key' => 'string', 'username' => 'string', 'pwd' => 'string'),
                       'out' => array('result' => 'boolean', 'message' => 'string')
                       );

        $this->__dispatch_map['Write_Notification'] =
                 array('in' => array('security_key' => 'string', 'from' => 'string', 'subject' => 'string', 'priority' => 'integer'),
                       'out' => array('result' => 'string', 'message' => 'string')
                       );
                       
        $this->__dispatch_map['Create_Instance'] =
                 array('in' => array('security_key' => 'string', 'instance_name' => 'string'),
                       'out' => array('result' => 'string', 'message' => 'string')
                       );

        $this->__dispatch_map['Set_InstanceDescription'] =
                 array('in' => array('security_key' => 'string', 'instance' => 'string', 'description' => 'string'),
                       'out' => array('result' => 'boolean', 'message' => 'string')
                       );

        $this->__dispatch_map['Get_CustomerGroups'] =
                 array('in' => array('security_key' => 'string'),
                       'out' => array('result' => 'array', 'message' => 'string')
                       );

        $this->__dispatch_map['Get_Currencies'] =
                 array('in' => array('security_key' => 'string'),
                       'out' => array('result' => 'array', 'message' => 'string')
                       );

        $this->__dispatch_map['Get_Countries'] =
                 array('in' => array('security_key' => 'string'),
                       'out' => array('result' => 'array', 'message' => 'string')
                       );
     
        $this->__dispatch_map['Get_Setting'] =
                 array('in' => array('security_key' => 'string', 'setting_key' => 'string'),
                       'out' => array('result' => 'array', 'message' => 'string')
                       );

        $this->__dispatch_map['Set_Setting'] =
                 array('in' => array('security_key' => 'string', 'setting_key' => 'string', 'value' => 'string'),
                       'out' => array('result' => 'array', 'message' => 'string')
                       );

        $this->__dispatch_map['Get_Languages'] =
                 array('in' => array('security_key' => 'string'),
                       'out' => array('result' => 'array', 'message' => 'string')
                       );

        $this->__dispatch_map['Create_DIDGroup'] =
                 array('in' => array('security_key' => 'string', 'instance' => 'string'),
                       'out' => array('id_didgroup' => 'integer', 'message' => 'string')
                       );
     
        $this->__dispatch_map['Create_Provider'] =
                 array('in' => array('security_key' => 'string', 'instance' => 'string'),
                       'out' => array('id_provider' => 'integer', 'message' => 'string')
                       );

        $this->__dispatch_map['Create_Ratecard'] =
                 array('in' => array('security_key' => 'string', 'instance' => 'string'),
                       'out' => array('id_ratecard' => 'integer', 'message' => 'string')
                       );

        $this->__dispatch_map['Create_Callplan'] =
                 array('in' => array('security_key' => 'string', 'instance' => 'string', 'id_ratecard' => 'integer'),
                       'out' => array('id_callplan' => 'integer', 'message' => 'string')
                       );
     
        $this->__dispatch_map['Create_Voucher'] =
                 array('in' => array('security_key' => 'string', 'credit' => 'float', 'units' => 'integer', 'currency' => 'string'),
                       'out' => array('result' => 'array', 'message' => 'string')
                       );

         $this->__dispatch_map['Create_Customer'] =
                 array('in' => array('security_key' => 'string', 'instance' => 'string', 'id_didgroup' => 'integer', 'units' => 'integer', 'accountnumber_len' => 'integer', 'balance' => 'float', 'activated' => 'boolean', 'status' => 'integer', 'simultaccess' => 'integer', 'currency' => 'string', 'typepaid' => 'integer', 'sip_buddy' => 'integer', 'iax_buddy' => 'integer', 'language' => 'string', 'voicemail_enabled' => 'boolean'),
                       'out' => array('result' => 'array', 'message' => 'string')
                       );

        $this->__dispatch_map['Validate_DIDPrefix'] =
                 array('in' => array('security_key' => 'string', 'did_prefix' => 'string'),
                       'out' => array('result' => 'boolean', 'message' => 'string')
                       );

        $this->__dispatch_map['Create_DID'] =
                 array('in' => array('security_key' => 'string', 'account_id' => 'array', 'id_didgroup' => 'integer', 'rate' => 'float', 'connection_charge' => 'float', 'did_prefix' => 'string', 'did_suffix' => 'string', 'id_country' => 'integer'),
                       'out' => array('result' => 'array', 'message' => 'string')
                       );

        $this->__dispatch_map['Get_ProvisioningList'] =
                 array('in' => array('security_key' => 'string'),
                       'out' => array('result' => 'array', 'message' => 'string')
                       );
                       
        $this->__dispatch_map['Create_TrunkConfig'] =
                 array('in' => array('security_key' => 'string', 'instance' => 'string', 'uri_trunk' => 'string', 'activation_code' => 'string'),
                       'out' => array('result' => 'string', 'message' => 'string')
                       );
     
        $this->__dispatch_map['Get_Rates'] =
                 array('in' => array('security_key' => 'string', 'uri_rate' => 'string', 'activation_code' => 'string', 'margin' => 'float'),
                       'out' => array('result' => 'array', 'message' => 'string')
                       );
     
        $this->__dispatch_map['Create_Rates'] =
                 array('in' => array('security_key' => 'string', 'instance' => 'string', 'rates' => 'array'),
                       'out' => array('result' => 'boolean', 'message' => 'string')
                       );

        $this->__dispatch_map['Update_Rates'] =
                 array('in' => array('security_key' => 'string', 'instance' => 'string', 'rates' => 'array'),
                       'out' => array('result' => 'boolean', 'message' => 'string')
                       );
	
    }
    
    /*
     * Check the security key
     */
    function Check_SecurityKey ($key)
    {
        if (md5($mysecurity_key) !== $security_key  || strlen($security_key)==0) 
        {
			error_log ("[" . date("Y/m/d G:i:s", mktime()) . "] "." CODE_ERROR SECURITY_KEY"."\n", 3, $this->logfile);
			sleep(2);
			return false;	  
		}
		return true;
    }
    
    /*
     * Check the security key & Instance
     * return : group_id, message
     */
    function Check_KeyInstance($key, $instance)
    {
        if (!$this->$this->Check_SecurityKey ($key)) {
		    return array("ERROR", "INVALID KEY");
		}
		
		if (!strlen($instance) > 0) {
		    return array("ERROR", "No instance provided");
		}
		
		// Check that there is not an existing Group with this name
		$QUERY = "SELECT id FROM cc_card_group WHERE name='$instance'";
		$result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
		if (!is_array($result) || $result[0][0] <= 0 )
		{
		    return array('ERROR', "GROUP DOES NOT EXIST");
		}
		
		return array($result[0][0], "");
	}
    
    
    /*
	 *		Function to Verify credential : pwd in encrypt
	 */ 
    function Authenticate_Admin($security_key, $username, $pwd)
    {
        if (!$this->Check_SecurityKey ($key)) {
		    return array("ERROR", "INVALID KEY");
		}
		
		$pwd_encoded = hash('whirlpool', $pwd);
		
		$QUERY = "SELECT count(*) FROM cc_ui_authen WHERE login='$username' AND pwd_encoded='$pwd_encoded'";
		$result = $this -> instance_table -> SQLExec ($this->DBHandle, $QUERY);
		if (!is_array($result) || $result[0][0] <= 0 )
		{
		    sleep(2);
		    return array(false, "WRONG LOGIN / PASSWORD");
		}
        
        array (true, ''); 
    }
    
    
    /*
	 *		Function to Update Admin password
	 */ 
    function Set_AdminPwd($security_key, $username, $pwd)
    {
        if (!$this->Check_SecurityKey ($key)) {
		    return array("ERROR", "INVALID KEY");
		}
		
		$pwd_encoded = hash('whirlpool', $pwd);
		
		$QUERY = "UPDATE cc_ui_authen SET login='$username', pwd_encoded='$pwd_encoded' WHERE login='$username'";
		$result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
		if (!$result)
		{
		    sleep(2);
		    return array(false, "ERROR SQL UPDATE");
		}
        
        array (true, ''); 
    }
    
    
    /*
	 *		Function to Add Notification
	 */ 
    function Write_Notification($security_key, $from, $subject, $priority)
    {
        if (!$this->Check_SecurityKey ($key)) {
		    return array("ERROR", "INVALID KEY");
		}
		
        //add notification
		$who = Notification::$UNKNOWN;
		$who_id = -1;
		$key = "SOAP-Server";
		
		//Priority -> 0:Low ; 1:Medium ; 2:High
        if ($priority < 0 || $priority > 2) {
            $priority = 0;
        }
		
		NotificationsDAO::AddNotification($key, $priority, $who, $who_id);
	    
        return array (true, '');
    }
    
    
	/*
	 *		Function to create Instance for the provisioning
	 */ 
	function Create_Instance ($security_key, $instance_name) {
		
		if (!$this->Check_SecurityKey ($key)) {
		    return array("ERROR", "INVALID KEY");
		}
		
		if (!strlen($instance_name) > 0) {
		    return array("ERROR", "NO INSTANCE_NAME PROVIDED");
		}
		
		$this->instance_table = new Table();
		
		$instance_key = $instance_name.'_'.MDP_STRING(4).'-'.MDP_NUMERIC(4);
		
		// Check that there is not an existing Group with this name
		$QUERY = "SELECT count(*) FROM cc_card_group WHERE name='$instance_key'";
		$result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
		if (!is_array($result) || $result[0][0] >= 0 )
		{
		    return array(false, "EXISTING GROUP WITH SAME NAME AND KEY");
		}
		
        $value = "'$instance_key'";
        $func_fields = "name";
        $func_table = 'cc_card_group';
        $id_name = "id";
        $inserted = $this->instance_table->Add_table($this->DBHandle, $value, $func_fields, $func_table, $id_name);
		
		if (!$inserted) {
		    return array(false, "ERROR CREATING ACCOUNT GROUP");
		}
		return array($instance_key, "");
	}
	
	/*
	 *		Function to define the description of the Instance
	 */ 
	function Set_InstanceDescription ($security_key, $instance, $description) {
	    
	    $arr_check = $this->Check_KeyInstance($key, $instance);
		if ($arr_check[0] == 'ERROR') {
		    return $arr_check;
		}
		
		$QUERY = "UPDATE cc_card_group SET description='$description' WHERE name='$instance'";
		$result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY, 0);
		if (!$result)
		{
		    return array(false, "SQL ERROR UPDATING cc_card_group");
		}
		
		return array(true, '');
	}
	
	/*
	 *		Returns list of customer groups
	 */
    function Get_CustomerGroups($security_key)
    {
        if (!$this->Check_SecurityKey ($key)) {
		    return array("ERROR", "INVALID KEY");
		}
		
		$QUERY = "SELECT name, description, provisioning FROM cc_card_group";
		$result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
		if (!is_array($result))
		{
		    return array(false, "CANNOT LOAD THE GROUP LIST");
		}
		
		return array($result, '');
    }

	
	/*
	 *      Get list of all currencies ($currency is the ISO-xxx)
	 */
    function Get_Currencies($security_key)
    {
        if (!$this->Check_SecurityKey ($key)) {
		    return array("ERROR", "INVALID KEY");
		}
		
		$QUERY = "SELECT currency, name FROM cc_currencies";
		$result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
		if (!is_array($result))
		{
		    return array(false, "CANNOT LOAD THE CURRENCY LIST");
		}
		
		return array($result, '');
    }

	/*
	 *      Get list of all countries ($country is the ISO-3166)
	 */
    function Get_Countries($security_key)
    {
        if (!$this->Check_SecurityKey ($key)) {
		    return array("ERROR", "INVALID KEY");
		}
		
		$QUERY = "SELECT id, countrycode, countryname FROM cc_country";
		$result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
		if (!is_array($result))
		{
		    return array(false, "CANNOT LOAD THE CURRENCY LIST");
		}
		
		return array($result, '');
    }
    
    /*
	 *      Get a setting from A2Billing
	 *
	 *      Get_setting($security_key, 'base_currency')
	 *      Get_setting($security_key, 'base_country')
	 *      Get_setting($security_key, 'base_language')
	 */
    function Get_Setting($security_key, $setting_key)
    {
        if (!$this->Check_SecurityKey ($key)) {
		    return array("ERROR", "INVALID KEY");
		}
		
		$QUERY = "SELECT config_value FROM cc_config WHERE config_key = '$setting_key'";
		$result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
		if (!is_array($result))
		{
		    return array(false, "CANNOT LOAD THE SETTING");
		}
		
		return array($result[0][0], '');
    }
    
     /*
	 *      Set a setting from A2Billing
	 *
	 *      Set_setting($security_key, 'base_currency', 'USD')
	 *      Set_setting($security_key, 'base_country', 'USA')
	 *      Set_setting($security_key, 'base_language', 'en')
	 */
    function Set_Setting($security_key, $setting_key, $value)
    {
        if (!$this->Check_SecurityKey ($key)) {
		    return array("ERROR", "INVALID KEY");
		}
		
		$QUERY = "UPDATE cc_config SET config_value='$value' WHERE config_key = '$setting_key''";
		$result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY, 0);
		if (!$result)
		{
		    return array(false, "SQL ERROR UPDATING cc_config");
		}
		
		return array(true, '');
    }
    
    /*
	 *      Get list of languages supported
	 */
    function Get_Languages($security_key)
    {
        if (!$this->Check_SecurityKey ($key)) {
		    return array("ERROR", "INVALID KEY");
		}
		
		$language_list = Constants::getLanguagesRevertList();
		
		return array($language_list, '');
    }

	
	/*
	 *      Create DID group associated with $instance
	 */
    function Create_DIDGroup($security_key, $instance)
    {
        $arr_check = $this->Check_KeyInstance($key, $instance);
		if ($arr_check[0] == 'ERROR') {
		    return $arr_check;
		}
		
		$value = "'$instance'";
        $func_fields = "didgroupname";
        $func_table = 'cc_didgroup';
        $id_name = "id";
        $inserted = $this->instance_table->Add_table($this->DBHandle, $value, $func_fields, $func_table, $id_name);
		
		if (!$inserted) {
		    return array(false, "ERROR CREATING DID GROUP");
		}
		return array($inserted, "");
    }

    /*
	 *      Create provider associated with $instance
	 */
    function Create_Provider($security_key, $instance)
    {
        $arr_check = $this->Check_KeyInstance($key, $instance);
		if ($arr_check[0] == 'ERROR') {
		    return $arr_check;
		}
		
		$value = "'$instance'";
        $func_fields = "provider_name";
        $func_table = 'cc_provider';
        $id_name = "id";
        $inserted = $this->instance_table->Add_table($this->DBHandle, $value, $func_fields, $func_table, $id_name);
		
		if (!$inserted) {
		    return array(false, "ERROR CREATING PROVIDER");
		}
		return array($inserted, "");
    }

    /*
	 *      Create ratecard  associated with $instance
	 */
    function Create_Ratecard($security_key, $instance)
    {
        $arr_check = $this->Check_KeyInstance($key, $instance);
		if ($arr_check[0] == 'ERROR') {
		    return $arr_check;
		}
		
		$begin_date = date("Y");
	    $begin_date_plus = date("Y")+25;	
	    $end_date = date("-m-d H:i:s");
	    $startingdate = $begin_date.$end_date;
	    $expirationdate = $begin_date_plus.$end_date;
		
		$value = "'$instance', '$startingdate', '$expirationdate'";
        $func_fields = "tariffname, startingdate, expirationdate";
        $func_table = 'cc_tariffplan';
        $id_name = "id";
        $inserted = $this->instance_table->Add_table($this->DBHandle, $value, $func_fields, $func_table, $id_name);
		
		if (!$inserted) {
		    return array(false, "ERROR CREATING RATECARD");
		}
		return array($inserted, "");
    }

    /*
	 *      Create callplan associated with $instance
	 */
    function Create_Callplan($security_key, $instance, $id_ratecard)
    {
        $arr_check = $this->Check_KeyInstance($key, $instance);
		if ($arr_check[0] == 'ERROR') {
		    return $arr_check;
		}
		
		if (!is_numeric($id_ratecard)) {
		    return array("ERROR", "NO ID_RATECARD PROVIDED");
		}
		
		$value = "'$instance'";
        $func_fields = "tariffgroupname";
        $func_table = 'cc_tariffgroup';
        $id_name = "id";
        $inserted = $this->instance_table->Add_table($this->DBHandle, $value, $func_fields, $func_table, $id_name);
		
		if (!$inserted) {
		    return array(false, "ERROR CREATING CALLPLAN");
		}
		$id_callplan = $inserted;
		
		$value = "'$inserted', '$id_ratecard'";
        $func_fields = "idtariffgroup, idtariffplan";
        $func_table = 'cc_tariffgroup_plan';
        $id_name = "id";
        $inserted = $this->instance_table->Add_table($this->DBHandle, $value, $func_fields, $func_table, $id_name);
		
		if (!$inserted) {
		    return array(false, "ERROR ATTACHING CALLPLAN AND RATECARD");
		}
		
		return array($id_callplan, "");
    }

    /*
	 *      Create a set of vouchers
	 */
    function Create_Voucher($security_key, $credit, $units, $currency)
    {
        if (!$this->Check_SecurityKey ($key)) {
		    return array("ERROR", "INVALID KEY");
		}
		
		$func_table  = "cc_voucher";		
		$func_fields = "voucher, credit, activated, currency, expirationdate";
    	$id_name = "id";    
	    
		$begin_date_plus = date("Y") + 25;	
	    $end_date = date("-m-d H:i:s");
	    $expirationdate = $begin_date_plus.$end_date;
		$arr_voucher = array();
		
		for ($k=0;$k < $nbvoucher;$k++){
			$vouchernum = generate_unique_value($func_table, LEN_VOUCHER, 'voucher');
			$value  = "'$vouchernum', '$credit', 't', '$currency', '$expirationdate'";
			
			$arr_voucher[$k] = $vouchernum;
			$inserted = $this->instance_table->Add_table($this->DBHandle, $value, $func_fields, $func_table, $id_name);
            
            if (!$inserted) {
		        return array(false, "ERROR CREATING VOUCHER (".$k." Vouchers created)");
		    }
		}
		
		return array($arr_voucher, $k." VOUCHERS CREATED");
    }

    /*
	 *      Create a set of accounts
	 */
    //Default values ($activated = true, $status = 1, $simultaccess = 0, $typepaid =0, $sip_buddy=1, $iax_buddy=1, $voicemail_enabled = true)
    //$status : 1 Active
    function Create_Customer($security_key, $instance, $id_didgroup, $units, $accountnumber_len, $balance, $activated, $status,  $simultaccess, $currency, $typepaid, $sip_buddy, $iax_buddy,  $language, $voicemail_enabled)
    {
        $arr_check = $this->Check_KeyInstance($key, $instance);
		if ($arr_check[0] == 'ERROR') {
		    return $arr_check;
		}
		$id_group = $arr_check;
		
		if (!is_numeric($id_ratecard)) {
		    return array("ERROR", "NO ID_RATECARD PROVIDED");
		}
		
		if ($accountnumber_len < 2 || $accountnumber_len > 40) {
		    return array("ERROR", "WRONG ACCOUNT NUMBER LENGTH - $accountnumber_len");
		}
		
		if ($activated)
		$v_activated = ($activated) ? 't' : 'f';
		
		$instance_realtime = new Realtime();

	    $FG_ADITION_SECOND_ADD_TABLE = "cc_card";
	    $FG_ADITION_SECOND_ADD_FIELDS = "username, useralias, credit, tariff, activated, simultaccess, currency, typepaid, uipass, id_group, id_didgroup, sip_buddy, iax_buddy";

	    if (DB_TYPE != "postgres") {
		    $FG_ADITION_SECOND_ADD_FIELDS .= ",creationdate ";
	    }
	
	    $instance_sub_table = new Table($FG_ADITION_SECOND_ADD_TABLE, $FG_ADITION_SECOND_ADD_FIELDS);
	    
	    $sip_buddy = $iax_buddy = 0;
	    
	    if (isset ($sip) && $sip == 1)
            $sip_buddy = 1;
        
        if (isset ($iax) && $iax == 1)
            $iax_buddy = 1;
        
	    //initialize refill parameter
	    $description_refill = gettext("CREATION CARD REFILL");
	    $field_insert_refill = "credit, card_id, description";
	    $instance_refill_table = new Table("cc_logrefill", $field_insert_refill);
        $arr_account = array();
        
	    for ($k = 0; $k < $units; $k++) {
		    $arr_card_alias = gen_card_with_alias("cc_card", 0, $accountnumber_len);
		    $accountnumber = $arr_card_alias[0];
		    $useralias = $arr_card_alias[1];
		    if (!is_numeric($balance))
			    $balance = 0;
		    $passui_secret = MDP_NUMERIC(10);
		
		    $FG_ADITION_SECOND_ADD_VALUE = "'$accountnumber', '$useralias', '$balance', '$choose_tariff', '$v_activated', $choose_simultaccess,".
		                               " '$choose_currency', $choose_typepaid, '$passui_secret', '$id_group', '$id_didgroup', $sip_buddy, $iax_buddy";
            
		    if (DB_TYPE != "postgres")
			    $FG_ADITION_SECOND_ADD_VALUE .= ", now() ";
		    
            
		    $id_cc_card = $instance_sub_table->Add_table($this->DBHandle, $FG_ADITION_SECOND_ADD_VALUE, null, null, $HD_Form->FG_TABLE_ID);
		    
		    if (!$id_cc_card) {
		        return array(false, "ERROR CREATING ACCOUNT (".$k." Accounts created)");
		    }
		    
		    $arr_account[] = array ($accountnumber, $id_cc_card);
		    
		    // create refill for card
		    if ($balance > 0) {
			    $value_insert_refill = "'$balance', '$id_cc_card', '$description_refill' ";
			    $instance_refill_table->Add_table($this->DBHandle, $value_insert_refill, null, null);
		    }

		    $instance_realtime -> insert_voip_config ($sip, $iax, $id_cc_card, $accountnumber, $passui_secret);
	    }
	
	    // Save Sip accounts to file
	    $instance_realtime -> create_trunk_config_file ('sip');
	    
	    // Save IAX accounts to file
	    $instance_realtime -> create_trunk_config_file ('iax');
	    
	    
		return array($arr_account, $k." ACCOUNTS CREATED");
    }

    /*
	 *      Validation of a DID Prefix
	 */
    // array (bool $status, $message) 
    function Validate_DIDPrefix($security_key, $did_prefix)
    {
        if (!$this->Check_SecurityKey ($key)) {
		    return array("ERROR", "INVALID KEY");
		}
		
		if (!strlen($did_prefix) >= 1) {
		    return array("ERROR", "WRONG DID PREFIX - $did_prefix");
		}
		
		$QUERY = "SELECT did FROM cc_did WHERE did LIKE \"$did_prefix%\"";
		$result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
		if (!is_array($result))
		{
		    return array(true, "VALID DID PREFIX");
		}
		
		return array(false, "INVALID DID PREFIX");
		
    }

    /*
	 *      Local DID number are created by server side, 7 digits
	 * did_prefix = 600
	 * did_suffix = 8760
	 * as the DID are 7 digits, the following DID will be created 6008760, 6008761, 6008762, 6008763, etc...
	 */
    function Create_DID($security_key, $account_id, $id_didgroup, $rate, $connection_charge, $did_prefix, $did_suffix, $id_country)
    {
        if (!$this->Check_SecurityKey ($key)) {
		    return array("ERROR", "INVALID KEY");
		}
		
		if (!is_array($account_id))
		{
		    return array(false, "WRONG ARRAY OF ACCOUNT");
		}
		
		$arr_did = array();
		
		$begin_date = date("Y");
	    $begin_date_plus = date("Y")+25;	
	    $end_date = date("-m-d H:i:s");
	    $startingdate = $begin_date.$end_date;
	    $expirationdate = $begin_date_plus.$end_date;
		
		$increment_did = 0;
		foreach ($account_id as $val_account_id){
		    
		    $did_suffix_inc = $did_suffix + $increment_did;
		    $did_to_create = "$did_prefix"."$did_suffix_inc";
		    $increment_did++;
		    
		    $func_table  = "cc_did";		
		    $func_fields = "id_cc_didgroup, id_cc_country, iduser, did, startingdate, expirationdate, billingtype, connection_charge, selling_rate";
    	    $id_name = "id";    
    	    $value  = "'$id_didgroup', '$id_country', '$val_account_id', '$did_to_create', '$startingdate', '$expirationdate', 2, $connection_charge, $rate";
			
			$inserted = $this->instance_table->Add_table($this->DBHandle, $value, $func_fields, $func_table, $id_name);
            
		    if (!$inserted) {
		        return array(false, "ERROR CREATING DID (".$increment_did." DIDs created)");
		    }
		    
		    $arr_did[] = $did_to_create;
		}
		
		return array($arr_did, "DIDs CREATION SUCCESSFUL (".$increment_did." DIDs created)");
		
    }
    
    
    // *** Providers ***

 

    //Get the Provider list with details
    /*
     * $provisioning_uri : http://www.call-labs.com/provisioning.txt
     * sample result sent by the API
     * //Call-Labs|A-Z termination providing global good rates and quality|http://myaccount.call-labs.com/webservice/create-trunkconfig.php|http://myaccount.call-labs.com/webservice/get_rates.php|http://call-labs.com/images/logo.jpg
     Call-Labs|A-Z termination providing global good rates and quality|http://www.call-labs.com/Create_TrunkConfig.php|http://www.call-labs.com/Get_Rates.php|http://call-labs.com/images/logo.jpg
     
     VOIP.MS|Good A-Z Voip provider, specialized for US|http://test/uri_trunk|http://test/uri_rate|http://voip.ms/images/mainmenu/logo2.gif
     *
     * $RESULT : array(array (string $name, string $description, string $uri_trunk, string $uri_rate,string $uri_image), string $message) 
     */
    function Get_ProvisioningList ($security_key, $provisioning_uri)
    {
        if (!$this->Check_SecurityKey ($key)) {
		    return array("ERROR", "INVALID KEY");
		}
        
        $content = open_url ($provisioning_uri);

        $arr_provisioning = array();
        $content_exp = explode("\n", $content);

        foreach ($content_exp as $content_exp_val) {

	        $content_exp_val= trim($content_exp_val);
	        if (strlen($content_exp_val) > 1) {
		        $content_exp_val_arr = explode("|", $content_exp_val);
		        if (is_array($content_exp_val_arr) && count($content_exp_val_arr) > 1) {
			        $arr_provisioning[] = $content_exp_val_arr;
		        }
	        }
        }
        
        if (!is_array($arr_provisioning) && count($arr_provisioning) == 0) {
            return array(false, "ERROR NO PROVISIONING LIST FOUND");
        }
        
        return array($arr_provisioning, "");
        
    }
    
     

    /*
     *  Request to the Provider the Trunk configuration
     */
    function Create_TrunkConfig($security_key, $instance, $uri_trunk, $activation_code)
    {
        $arr_check = $this->Check_KeyInstance($key, $instance);
		if ($arr_check[0] == 'ERROR') {
		    return $arr_check;
		}
		$id_group = $arr_check;
		
		$add_param = "?activation_code=$activation_code";
		$content = open_url ($uri_trunk.$add_param);
        
        $pos_error = strpos($content, 'ERROR');
        if ($pos_error !== false) {
            return array(false, "ERROR ".substr($content, $pos_error+5, 255));
        }
        
        $arr_provisioning = array();
        $content_exp = explode("\n", $content);

        foreach ($content_exp as $content_exp_val) {

	        $content_exp_val= trim($content_exp_val);
	        if (strlen($content_exp_val) > 1) {
		        $content_exp_val_arr = explode("|", $content_exp_val);
		        if (is_array($content_exp_val_arr) && count($content_exp_val_arr) > 1) {
			        $arr_provisioning[] = $content_exp_val_arr;
		        }
	        }
        }
        
        /*
        search #SIP-TRUNK-CONFIG-START# and #SIP-TRUNK-CONFIG-END#
        mv "lol" to  sip_additional_$providername$_$instance$.conf.timestamp
        copy content to 
        sip_additional_$providername$-$instance$.conf
        include  sip_additional_$instance$.conf in sip.conf
        
        Check if cc_trunk with $instance exist
        */
        $func_fields = "name";
        $func_table = 'cc_trunk';
        $id_name = "trunkcode, providertech, providerip";
        $value = "'$instance', '$trunktech', '$trunkname'";
        $inserted = $this->instance_table->Add_table($this->DBHandle, $value, $func_fields, $func_table, $id_name);
		
		if (!$inserted) {
		    return array(false, "ERROR CREATING ACCOUNT GROUP");
		}
		return array($instance_key, "");
        
        return array(true, "TRUNK CONFIG CREATED WITH SUCCESS");
    }
     

    /*
     *  Retrieve the rates from the Provider
     */
    // RESULT : array(array(string $prefix, string $destination, float $buyrate, float $sellrate), string $message) 
    function Get_Rates(string $security_key, string $uri_rate, $activation_code, float $margin)
    {
        if (!$this->Check_SecurityKey ($key)) {
		    return array("ERROR", "INVALID KEY");
		}
		
		$add_param = "?activation_code=$activation_code";
		$content = open_url ($uri_trunk.$add_param);
		
		$pos_error = strpos($content, 'ERROR');
        if ($pos_error !== false) {
            return array(false, "ERROR ".substr($content, $pos_error+5, 255));
        }

        $arr_rates = array();
        $content_exp = explode("\n", $content);

        foreach ($content_exp as $content_exp_val) {

	        $content_exp_val = trim($content_exp_val);
	        if (strlen($content_exp_val) > 1) {
		        $content_exp_val_arr = explode(",", $content_exp_val);
		        if (is_array($content_exp_val_arr) && count($content_exp_val_arr) >= 3) {
			        $rate_margin = $content_exp_val_arr[2] + $content_exp_val_arr[2] * $margin;
			
			        $arr_rates[] = array( $content_exp_val_arr[0], $content_exp_val_arr[1], $content_exp_val_arr[2], $rate_margin);
		        }
	        }
        }
        
        return array($arr_rates, "RATES RETURNED WITH SUCCESS");
        
    }
    
    /*
     *  CHECK RATES VALIDITY - function check_rates_validity
     */ 
    function check_rates_validity ($arr_rates) {
        $valid_rate = true;
        foreach ($arr_rates as $arr_rates_val) {
		    
		    $dialprefix = trim($arr_rates_val[0]);
		    $destination = trim($arr_rates_val[1]);
		    $buyrate = trim($arr_rates_val[2]);
		    $sellrate = trim($arr_rates_val[3]);
		    
		    if ((strlen($dialprefix) == 0) || !is_numeric($dialprefix)) {
		        $valid_rate = false;
		        break;
		    }
		    
		    if (strlen($destination) == 0) {
		        $valid_rate = false;
		        break;
		    }
		    
		    if (!is_numeric($buyrate) || !is_numeric($sellrate)) {
		        $valid_rate = false;
		        break;
		    }
		    $nb_rates++;
		}
		
		return $valid_rate;
	}
    
    /*
     *  Add Rates into A2Billing
     */
    // array(bool $result, string $message) 
    // array(string $prefix, string $destination, float $buyrate, float $sellrate)
    function Create_Rates($security_key, $instance, $arr_rates)
    {
        $arr_check = $this->Check_KeyInstance($key, $instance);
		if ($arr_check[0] == 'ERROR') {
		    return $arr_check;
		}
		$id_group = $arr_check;
		
		$QUERY = "SELECT id_trunk FROM cc_trunk WHERE trunkcode = '$instance'";
		$result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
		if (!is_array($result))
		{
		    return array(false, "CANNOT LOAD THE TRUNK FOR THIS INSTANCE");
		}
		$id_trunk = $result[0][0];
		
		
		$QUERY = "SELECT id FROM cc_tariffplan WHERE tariffname = '$instance'";
		$result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
		if (!is_array($result))
		{
		    return array(false, "CANNOT LOAD THE RATECARD FOR THIS INSTANCE");
		}
		$id_ratecard = $result[0][0];
		$nb_to_import = 0;
        $nb_rates = 0;
        
		
		// CHECK RATES VALIDITY
		if (!check_rates_validity ($arr_rates)) {
		    return array(false, "ERROR RATES VALIDITY, LINE $nb_rates. ENSURE THAT ALL RATES HAVE A CORRECT FORMAT!");
		}
		
		// START RATES IMPORT
		foreach ($arr_rates as $arr_rates_val) {
		    
		    $dialprefix = trim($arr_rates_val[0]);
		    $destination = trim($arr_rates_val[1]);
		    $buyrate = trim($arr_rates_val[2]);
		    $sellrate = trim($arr_rates_val[3]);
		    
		    
		    // ADD PREFIX
		    $instance_table_prefix = new Table("cc_prefix");
		    $FG_ADITION_SECOND_ADD_FIELDS_PREFIX = 'prefix, destination';
		    
		    $FG_ADITION_SECOND_ADD_VALUE_PREFIX = "'" . intval($dialprefix) . "', '$destination'";
			$TT_QUERY_PREFIX = "INSERT INTO " . $FG_ADITION_SECOND_ADD_TABLE_PREFIX . " (" . $FG_ADITION_SECOND_ADD_FIELDS_PREFIX . ") values (" . $FG_ADITION_SECOND_ADD_VALUE_PREFIX . ") ";
			$instance_table_prefix -> Add_table ($this->DBHandle, $FG_ADITION_SECOND_ADD_VALUE_PREFIX, $FG_ADITION_SECOND_ADD_FIELDS_PREFIX);
			
			
			// ADD RATES
		    $FG_ADITION_SECOND_ADD_TABLE = 'cc_ratecard';
		    $FG_ADITION_SECOND_ADD_FIELDS = 'idtariffplan, id_trunk, dialprefix, destination, buyrate, rateinitial, startdate, stopdate';
		    
		    $startdate = date("Y-m-d H:i:s");
		    $stopdate_prefix = date("Y") + 30;
		    $stopdate_suffix = date("-m-d H:i:s");
		    
		    $FG_ADITION_SECOND_ADD_VALUE = "'$id_ratecard', '$id_trunk', '$dialprefix', '$destination', '$buyrate', '$sellrate', '$startdate', '$stopdate_prefix$stopdate_suffix'";
		    $TT_QUERY = "INSERT INTO " . $FG_ADITION_SECOND_ADD_TABLE . " (" . $FG_ADITION_SECOND_ADD_FIELDS . ") values (" . $FG_ADITION_SECOND_ADD_VALUE . ") ";
		    
		    $result_query = $this->DBHandle->Execute($TT_QUERY);
		    
		    if (!$result_query) {
		        return array(false, "ERROR RATES CREATION ($nb_to_import Rates imported)");
		    }
		    
		    $nb_to_import++;
		}
		
		
		return array(true, "RATES CREATED SUCCESSFULLY ($nb_to_import Rates imported)");
		
    }
 


    // *** Update rates ***

    /*
     *  Update the rates of an existing provisioning
     */
    // array(bool $result, string $message) 
    // array(string $prefix, string $destination, float $buyrate, float $sellrate)
    function Update_Rates($security_key, $instance, $arr_rates)
    {
        $arr_check = $this->Check_KeyInstance($key, $instance);
		if ($arr_check[0] == 'ERROR') {
		    return $arr_check;
		}
		$id_group = $arr_check;
		
		
		$QUERY = "SELECT id_trunk FROM cc_trunk WHERE trunkcode = '$instance'";
		$result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
		if (!is_array($result))
		{
		    return array(false, "CANNOT LOAD THE TRUNK FOR THIS INSTANCE");
		}
		$id_trunk = $result[0][0];
		
		
		$QUERY = "SELECT id FROM cc_tariffplan WHERE tariffname = '$instance'";
		$result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
		if (!is_array($result))
		{
		    return array(false, "CANNOT LOAD THE RATECARD FOR THIS INSTANCE");
		}
		$id_ratecard = $result[0][0];
		
		
		// CHECK RATES VALIDITY
		if (!check_rates_validity ($arr_rates)) {
		    return array(false, "ERROR RATES VALIDITY, LINE $nb_rates. ENSURE THAT ALL RATES HAVE A CORRECT FORMAT!");
		}
		
		// DELETE EXISTING RATES
		$DEL_QUERY = "DELETE FROM cc_ratecard WHERE idtariffplan=$id_ratecard";
	    $result_query = $this->DBHandle->Execute($DEL_QUERY);
	    
	    
		// REIMPORT RATES
		foreach ($arr_rates as $arr_rates_val) {
		    
		    $dialprefix = trim($arr_rates_val[0]);
		    $destination = trim($arr_rates_val[1]);
		    $buyrate = trim($arr_rates_val[2]);
		    $sellrate = trim($arr_rates_val[3]);
		    
		    
		    // ADD PREFIX
		    $instance_table_prefix = new Table("cc_prefix");
		    $FG_ADITION_SECOND_ADD_FIELDS_PREFIX = 'prefix, destination';
		    
		    $FG_ADITION_SECOND_ADD_VALUE_PREFIX = "'" . intval($dialprefix) . "', '$destination'";
			$TT_QUERY_PREFIX = "INSERT INTO " . $FG_ADITION_SECOND_ADD_TABLE_PREFIX . " (" . $FG_ADITION_SECOND_ADD_FIELDS_PREFIX . ") values (" . $FG_ADITION_SECOND_ADD_VALUE_PREFIX . ") ";
			$instance_table_prefix -> Add_table ($this->DBHandle, $FG_ADITION_SECOND_ADD_VALUE_PREFIX, $FG_ADITION_SECOND_ADD_FIELDS_PREFIX);
			
			
			// ADD RATES
		    $FG_ADITION_SECOND_ADD_TABLE = 'cc_ratecard';
		    $FG_ADITION_SECOND_ADD_FIELDS = 'idtariffplan, id_trunk, dialprefix, destination, buyrate, rateinitial, startdate, stopdate';
		    
		    $startdate = date("Y-m-d H:i:s");
		    $stopdate_prefix = date("Y") + 30;
		    $stopdate_suffix = date("-m-d H:i:s");
		    
		    $FG_ADITION_SECOND_ADD_VALUE = "'$id_ratecard', '$id_trunk', '$dialprefix', '$destination', '$buyrate', '$sellrate', '$startdate', '$stopdate_prefix$stopdate_suffix'";
		    $TT_QUERY = "INSERT INTO " . $FG_ADITION_SECOND_ADD_TABLE . " (" . $FG_ADITION_SECOND_ADD_FIELDS . ") values (" . $FG_ADITION_SECOND_ADD_VALUE . ") ";
		    
		    $result_query = $this->DBHandle->Execute($TT_QUERY);
		    
		    if (!$result_query) {
		        return array(false, "ERROR RATES CREATION ($nb_to_import Rates imported)");
		    }
		    
		    $nb_to_import++;
		}
		
		
		return array(true, "RATES UPDATED SUCCESSFULLY ($nb_to_import Rates imported)");
		
    }

}


$server = new SOAP_Server();

$webservice = new SOAP_A2Billing();

$server->addObjectMap($webservice, 'http://schemas.xmlsoap.org/soap/envelope/');


if (isset($_SERVER['REQUEST_METHOD'])  &&  $_SERVER['REQUEST_METHOD']=='POST') {

     $server->service($HTTP_RAW_POST_DATA);
	 
} else {
     // Create the DISCO server
     $disco = new SOAP_DISCO_Server($server,'Billing');
     header("Content-type: text/xml");
     if (isset($_SERVER['QUERY_STRING']) && strcasecmp($_SERVER['QUERY_STRING'],'wsdl') == 0) {
         echo $disco->getWSDL();
     } else {
         echo $disco->getDISCO();
     }
}

exit;



