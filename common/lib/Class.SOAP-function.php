<?php

$disable_check_cp = true;

include (dirname(__FILE__)."/admin.defines.php");

class SOAP_A2Billing
{
    public $__dispatch_map = array();

    public $logfile = API_LOGFILE;

    public $system_security_key = API_SECURITY_KEY;

    public $instance_table;

    public $DBHandle;

    //Construct
    public function __construct()
    {
        $this->instance_table = new Table();

        $this->DBHandle  = DbConnect();

        // Define the signature of the dispatch map on the Web servicesmethod

        // Necessary for WSDL creation

        $this->__dispatch_map['Update_Currencies_list'] =
                 array('in' => array('security_key' => 'string'),
                       'out' => array('result' => 'boolean', 'message' => 'string')
                       );

        $this->__dispatch_map['Reload_Asterisk_SIP_IAX'] =
                 array('in' => array('security_key' => 'string'),
                       'out' => array('result' => 'boolean', 'message' => 'string')
                       );

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

        $this->__dispatch_map['Set_InstanceProvisioning'] =
                 array('in' => array('security_key' => 'string', 'instance' => 'string', 'provisioning' => 'string'),
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

        $this->__dispatch_map['Get_Currencies_value'] =
                 array('in' => array('security_key' => 'string', 'currency' => 'string'),
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

        $this->__dispatch_map['Get_Account_Attribute'] =
                 array('in' => array('security_key' => 'string', 'attribute' => 'string', 'username' => 'string'),
                       'out' => array('result' => 'array', 'message' => 'string')
                       );

        $this->__dispatch_map['Set_Account_Attribute'] =
                 array('in' => array('security_key' => 'string', 'attribute' => 'string', 'username' => 'string', 'value' => 'string'),
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
                 array('in' => array('security_key' => 'string', 'instance' => 'string', 'id_callplan' => 'integer', 'id_didgroup' => 'integer', 'units' => 'integer', 'accountnumber_len' => 'integer', 'balance' => 'float', 'activated' => 'boolean', 'status' => 'integer', 'simultaccess' => 'integer', 'currency' => 'string', 'typepaid' => 'integer', 'sip' => 'integer', 'iax' => 'integer', 'language' => 'string', 'voicemail_enabled' => 'boolean', 'country' => 'string'),
                       'out' => array('result' => 'array', 'message' => 'string')
                       );

        $this->__dispatch_map['Validate_DIDPrefix'] =
                 array('in' => array('security_key' => 'string', 'did_prefix' => 'string'),
                       'out' => array('result' => 'boolean', 'message' => 'string')
                       );

        $this->__dispatch_map['Create_DID'] =
                 array('in' => array('security_key' => 'string', 'account_id' => 'array', 'id_didgroup' => 'integer', 'rate' => 'float', 'connection_charge' => 'float', 'did_prefix' => 'string', 'did_suffix' => 'string', 'country' => 'integer'),
                       'out' => array('result' => 'array', 'message' => 'string')
                       );

        $this->__dispatch_map['Get_ProvisioningList'] =
                 array('in' => array('security_key' => 'string', 'provisioning_uri' => 'string'),
                       'out' => array('result' => 'array', 'message' => 'string')
                       );

        $this->__dispatch_map['Create_TrunkConfig'] =
                 array('in' => array('security_key' => 'string', 'instance' => 'string', 'uri_trunk' => 'string', 'activation_code' => 'string', 'provider_name' => 'string'),
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

        $this->__dispatch_map['Get_Subscription_Signup'] =
                 array('in' => array('security_key' => 'string'),
                       'out' => array('result' => 'array', 'message' => 'string')
                       );

        $this->__dispatch_map['Add_CallerID'] =
                 array('in' => array('security_key' => 'string', 'callerid' => 'integer', 'id_cc_card' => 'integer', 'accountnumber' => 'integer'),
                       'out' => array('result' => 'array', 'message' => 'string')
                       );

        $this->__dispatch_map['Get_Calls_History'] =
                 array('in' => array('security_key' => 'string', 'card_id' => 'integer', 'starttime_begin' => 'string', 'starttime_end' => 'string', 'offset' => 'integer', 'items_number' => 'integer', 'terminatecauseid' => 'integer'),
                       'out' => array('result' => 'array', 'message' => 'string')
                       );

        $this->__dispatch_map['Get_Log_Refill'] =
                 array('in' => array('security_key' => 'string', 'card_id' => 'integer', 'offset' => 'integer', 'items_number' => 'integer'),
                       'out' => array('result' => 'array', 'message' => 'string')
                       );

        $this->__dispatch_map['Add_Credit'] =
                 array('in' => array('security_key' => 'string', 'card_id' => 'integer', 'value' => 'string', 'description' => 'string', 'refill_type' => 'integer'),
                       'out' => array('result' => 'boolean', 'message' => 'string')
                       );
    }

    /*
     * Check the security key
     */
    public function Check_SecurityKey ($security_key)
    {

        if (md5($this->system_security_key) !== $security_key  || strlen($security_key)==0) {
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
    public function Check_KeyInstance($security_key, $instance)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        if (!strlen($instance) > 0) {
            return array("ERROR", "No instance provided");
        }

        // Check that there is not an existing Group with this name
        $QUERY = "SELECT id FROM cc_card_group WHERE name='$instance'";
        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
        if (!is_array($result) || $result[0][0] <= 0 ) {
            return array('ERROR', "GROUP DOES NOT EXIST");
        }

        return array($result[0][0], "Check_KeyInstance SUCCESS");
    }

    /*
     *		Function to Update the currency list
     */
    public function Update_Currencies_list($security_key)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        $return = currencies_update_yahoo($this->DBHandle, $this -> instance_table);

        if (!$return) {
            return array(false, "Currency Update Failure");
        }

        return array (true, 'Currency Update  SUCCESS');
    }

    /*
     *		Function to reload the SIP / IAX Asterisk Config
     */
    public function Reload_Asterisk_SIP_IAX($security_key)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        include (dirname(__FILE__)."/phpagi/phpagi-asmanager.php");

        $as = new AGI_AsteriskManager();

        $res = $as->connect(MANAGER_HOST, MANAGER_USERNAME, MANAGER_SECRET);

        if ($res) {

            $res_sip = $as->Command('sip reload');
            $res_iax = $as->Command('iax2 reload');

            $as->disconnect();

        } else {
            return array(false, "Cannot connect to the Asterisk Manager !");

        }

        return array (true, 'Asterisk SIP / IAX config reloaded SUCCESS');
    }

    /*
     *		Function to Verify credential : pwd in encrypt
     */
    public function Authenticate_Admin($security_key, $username, $pwd)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        $pwd_encoded = hash('whirlpool', $pwd);

        $QUERY = "SELECT count(*) FROM cc_ui_authen WHERE login='$username' AND pwd_encoded='$pwd_encoded'";
        $result = $this -> instance_table -> SQLExec ($this->DBHandle, $QUERY);
        if (!is_array($result) || $result[0][0] <= 0 ) {
            sleep(2);

            return array(false, "WRONG LOGIN / PASSWORD");
        }

        return array (true, 'Authenticate_Admin SUCCESS');
    }

    /*
     *		Function to Update Admin password
     */
    public function Set_AdminPwd($security_key, $username, $pwd)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        $pwd_encoded = hash('whirlpool', $pwd);

        $QUERY = "UPDATE cc_ui_authen SET login='$username', pwd_encoded='$pwd_encoded' WHERE login='$username'";

        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY, 0);
        if (!$result) {
            sleep(2);

            return array(false, "ERROR SQL UPDATE");
        }

        return array (true, 'Set_AdminPwd SUCCESS');
    }

    /*
     *		Function to Add Notification
     */
    public function Write_Notification($security_key, $from, $subject, $priority)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        //add notification
        $who = Notification::$SOAPSERVER;
        $who_id = 0;
        $key = "SOAP-Server Notification : ".$subject;

        //Priority -> 0:Low ; 1:Medium ; 2:High
        if ($priority < 0 || $priority > 2) {
            $priority = 0;
        }

        NotificationsDAO::AddNotification($key, $priority, $who, $who_id);

        return array (true, 'Write_Notification SUCCESS');
    }

    /*
     *		Function to create Instance for the provisioning
     */
    public function Create_Instance ($security_key, $instance_name)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
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
        if (!is_array($result) || $result[0][0] > 0 ) {
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

        return array($instance_key, "Create_Instance SUCCESS");
    }

    /*
     *		Function to define the description of the Instance
     */
    public function Set_InstanceDescription ($security_key, $instance, $description)
    {
        $arr_check = $this->Check_KeyInstance($security_key, $instance);
        if ($arr_check[0] == 'ERROR') {
            return $arr_check;
        }

        $QUERY = "UPDATE cc_card_group SET description='$description' WHERE name='$instance'";
        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY, 0);
        if (!$result) {
            return array(false, "SQL ERROR UPDATING cc_card_group");
        }

        return array(true, 'Set_InstanceDescription SUCCESS');
    }

    /*
     *		Function to define the provisioning information of the Instance
     */
    public function Set_InstanceProvisioning ($security_key, $instance, $provisioning)
    {
        $arr_check = $this->Check_KeyInstance($security_key, $instance);
        if ($arr_check[0] == 'ERROR') {
            return $arr_check;
        }

        $QUERY = "UPDATE cc_card_group SET provisioning='$provisioning' WHERE name='$instance'";
        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY, 0);
        if (!$result) {
            return array(false, "SQL ERROR UPDATING cc_card_group");
        }

        return array(true, 'Set_InstanceProvisioning SUCCESS');
    }

    /*
     *		Returns list of customer groups
     */
    public function Get_CustomerGroups($security_key)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        $QUERY = "SELECT name, description, provisioning FROM cc_card_group";
        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
        if (!is_array($result)) {
            return array(false, "CANNOT LOAD THE GROUP LIST");
        }

        return array(serialize($result), 'Get_CustomerGroups SUCCESS');
    }

    /*
     *      Get list of service on signup subscription
     */
    public function Get_Subscription_Signup($security_key)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        $QUERY = "SELECT cc_subscription_signup.id, description, cc_subscription_service.label as fee_label, cc_subscription_service.fee, id_subscription FROM cc_subscription_signup LEFT JOIN cc_subscription_service ON id_subscription=cc_subscription_service.id ORDER BY cc_subscription_signup.id";

        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);

        if (!is_array($result)) {
            return array(false, "CANNOT LOAD THE SIGNUP SUBSCRIPTION LIST");
        }

        return array(base64_encode(serialize($result)), 'Get_Subscription_Signup SUCCESS');
    }

    /*
     *      Get list of all currencies ($currency is the ISO-xxx)
     */
    public function Get_Currencies($security_key)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        $QUERY = "SELECT currency, name FROM cc_currencies";
        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
        if (!is_array($result)) {
            return array(false, "CANNOT LOAD THE CURRENCY LIST");
        }

        return array(serialize($result), 'Get_Currencies SUCCESS');
    }

    /*
     *      Get the value of a currency
     */
    public function Get_Currencies_value($security_key, $currency)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }
        $QUERY = "SELECT value, basecurrency FROM cc_currencies WHERE currency='".strtoupper($currency)."'";
        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
        if (!is_array($result)) {
            return array(false, "CANNOT LOAD THE CURRENCY LIST");
        }

        return array(serialize($result[0]), 'Get_Currencies_value SUCCESS');
    }

    /*
     *      Get list of all countries ($country is the ISO-3166)
     */
    public function Get_Countries($security_key)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        $QUERY = "SELECT id, countrycode, countryname FROM cc_country";
        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
        if (!is_array($result)) {
            return array(false, "CANNOT LOAD THE CURRENCY LIST");
        }

        return array(serialize($result), 'Get_Countries SUCCESS');
    }

    /*
     *      Get a setting from A2Billing
     *
     *      Get_setting($security_key, 'base_currency')
     *      Get_setting($security_key, 'base_country')
     *      Get_setting($security_key, 'base_language')
     */
    public function Get_Setting($security_key, $setting_key)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        $QUERY = "SELECT config_value FROM cc_config WHERE config_key = '$setting_key'";
        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
        if (!is_array($result)) {
            return array(false, "CANNOT LOAD THE SETTING");
        }

        return array($result[0][0], 'Get_Setting SUCCESS');
    }

     /*
     *      Set a setting from A2Billing
     *
     *      Set_setting($security_key, 'base_currency', 'USD')
     *      Set_setting($security_key, 'base_country', 'USA')
     *      Set_setting($security_key, 'base_language', 'en')
     */
    public function Set_Setting($security_key, $setting_key, $value)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        $QUERY = "UPDATE cc_config SET config_value='$value' WHERE config_key = '$setting_key'";
        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY, 0);
        if (!$result) {
            return array(false, "SQL ERROR UPDATING cc_config");
        }

        return array(true, 'Set_Setting SUCCESS');
    }

    /*
     *      Get a cc_card attribute from A2Billing
     *
     *      Get_Account_Attribute($security_key, 'credit', '235412356')
     */
    public function Get_Account_Attribute($security_key, $attribute, $username)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        $QUERY = "SELECT $attribute FROM cc_card WHERE username = '$username' LIMIT 1";
        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
        if (!is_array($result)) {
            return array(false, "CANNOT LOAD THE SETTING");
        }

        return array($result[0][0], 'Get_Account_Attribute SUCCESS');
    }

     /*
     *      Set a cc_card attribute from A2Billing
     *
     *      Set_Account_Attribute($security_key, 'credit', '235412356', '26.00')
     */
    public function Set_Account_Attribute($security_key, $attribute, $username, $value)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        $QUERY = "UPDATE cc_card SET $attribute='$value' WHERE username = '$username' LIMIT 1";
        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY, 0);
        if (!$result) {
            return array(false, "SQL ERROR UPDATING cc_card");
        }

        return array(true, 'Set_Account_Attribute SUCCESS');
    }

    /*
     *      Get list of languages supported
     */
    public function Get_Languages($security_key)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        $language_list = Constants::getLanguagesRevertList();

        return array(serialize($language_list), 'Get_Languages SUCCESS');
    }

    /*
     *      Create DID group associated with $instance
     */
    public function Create_DIDGroup($security_key, $instance)
    {
        $arr_check = $this->Check_KeyInstance($security_key, $instance);
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

        return array($inserted, "Create_DIDGroup SUCCESS");
    }

    /*
     *      Create provider associated with $instance
     */
    public function Create_Provider($security_key, $instance)
    {
        $arr_check = $this->Check_KeyInstance($security_key, $instance);
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

        return array($inserted, "Create_Provider SUCCESS");
    }

    /*
     *      Create ratecard  associated with $instance
     */
    public function Create_Ratecard($security_key, $instance)
    {
        $arr_check = $this->Check_KeyInstance($security_key, $instance);
        if ($arr_check[0] == 'ERROR') {
            return $arr_check;
        }

        $begin_date = date("Y");
        $begin_date_plus = date("Y") + 10;
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

        return array($inserted, "Create_Ratecard SUCCESS");
    }

    /*
     *      Create callplan associated with $instance
     */
    public function Create_Callplan($security_key, $instance, $id_ratecard)
    {
        $arr_check = $this->Check_KeyInstance($security_key, $instance);
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

        return array($id_callplan, "Create_Callplan SUCCESS");
    }

    /*
     *      Create a set of vouchers
     */
    public function Create_Voucher($security_key, $credit, $units, $currency)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        $func_table  = "cc_voucher";
        $func_fields = "voucher, credit, activated, currency, expirationdate";
        $id_name = "id";

        $begin_date_plus = date("Y") + 10;
        $end_date = date("-m-d H:i:s");
        $expirationdate = $begin_date_plus.$end_date;
        $arr_voucher = array();

        for ($k=0;$k < $units;$k++) {
            $vouchernum = generate_unique_value($func_table, LEN_VOUCHER, 'voucher');
            $value  = "'$vouchernum', '$credit', 't', '$currency', '$expirationdate'";

            $arr_voucher[$k] = $vouchernum;
            $inserted = $this->instance_table->Add_table($this->DBHandle, $value, $func_fields, $func_table, $id_name);

            if (!$inserted) {
                return array(false, "ERROR CREATING VOUCHER (".$k." Vouchers created)");
            }
        }

        return array(serialize($arr_voucher), "Create_Voucher SUCCESS - ".$k." VOUCHERS CREATED");
    }

    /*
     *      Create a set of accounts
     */
    //Default values ($activated = true, $status = 1, $simultaccess = 0, $typepaid =0, $sip=1, $iax=1, $voicemail_enabled = true)
    //$status : 1 Active
    public function Create_Customer($security_key, $instance, $id_callplan, $id_didgroup, $units, $accountnumber_len, $balance, $activated, $status,  $simultaccess, $currency, $typepaid, $sip, $iax,  $language, $voicemail_enabled, $country)
    {
        $arr_check = $this->Check_KeyInstance($security_key, $instance);
        if ($arr_check[0] == 'ERROR') {
            return $arr_check;
        }
        $id_group = $arr_check[0];

        if (!is_numeric($id_callplan)) {
            return array("ERROR", "NO ID_CALLPLAN PROVIDED");
        }

        if ($accountnumber_len < 2 || $accountnumber_len > 40) {
            return array("ERROR", "WRONG ACCOUNT NUMBER LENGTH - $accountnumber_len");
        }

        if (strlen($country)==3)
            $country = strtoupper($country);
        else
            $country = 'USA';

        if (strlen($language)==2)
            $language = strtolower($language);
        else
            $language = 'en';

        if ($activated)
            $activated = 't';
        else
            $activated = 'f';

        $instance_realtime = new Realtime();

        $FG_ADITION_SECOND_ADD_TABLE = "cc_card";
        $FG_ADITION_SECOND_ADD_FIELDS = "username, useralias, credit, tariff, country, language, activated, simultaccess, currency, typepaid, uipass, id_group, id_didgroup, sip_buddy, iax_buddy";

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
            $passui_secret = MDP_NUMERIC(5).MDP_STRING(10).MDP_NUMERIC(5);

            $FG_ADITION_SECOND_ADD_VALUE = "'$accountnumber', '$useralias', '$balance', '$id_callplan', '$country', '$language', '$activated', ".
                                 " $simultaccess, '$currency', $typepaid, '$passui_secret', '$id_group', '$id_didgroup', $sip_buddy, $iax_buddy";

            if (DB_TYPE != "postgres")
                $FG_ADITION_SECOND_ADD_VALUE .= ", now() ";

            $id_cc_card = $instance_sub_table->Add_table($this->DBHandle, $FG_ADITION_SECOND_ADD_VALUE, null, null, 'id');

            if (!$id_cc_card) {
                return array(false, "ERROR CREATING ACCOUNT (".$k." Accounts created)");
            }

            $arr_account[] = array ($accountnumber, $id_cc_card);

            // create refill for card
            if ($balance > 0) {
                $value_insert_refill = "'$balance', '$id_cc_card', '$description_refill' ";
                $instance_refill_table->Add_table($this->DBHandle, $value_insert_refill, null, null);
            }

            $instance_realtime -> insert_voip_config ($sip_buddy, $iax_buddy, $id_cc_card, $accountnumber, $passui_secret);
        }

        // Save Sip accounts to file
        $instance_realtime -> create_trunk_config_file ('sip');

        // Save IAX accounts to file
        $instance_realtime -> create_trunk_config_file ('iax');

        return array(serialize($arr_account), "Create_Customer SUCCESS - ".$k." ACCOUNTS CREATED");
    }

    /*
     *      Validation of a DID Prefix
     */
    // array (bool $status, $message)
    public function Validate_DIDPrefix($security_key, $did_prefix)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        if (!strlen($did_prefix) >= 1) {
            return array("ERROR", "WRONG DID PREFIX - $did_prefix");
        }

        $QUERY = "SELECT did FROM cc_did WHERE did LIKE \"$did_prefix%\"";
        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
        if (is_array($result)) {
            return array(false, "ERROR - INVALID DID PREFIX");
        }

        return array(true, "Validate_DIDPrefix SUCCESS - VALID DID PREFIX");

    }

    /*
     *      Local DID number are created by server side, 7 digits
     * did_prefix = 600
     * did_suffix = 8760
     * as the DID are 7 digits, the following DID will be created 6008760, 6008761, 6008762, 6008763, etc...
     */
    public function Create_DID($security_key, $account_id, $id_didgroup, $rate, $connection_charge, $did_prefix, $did_suffix, $country)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        if (!is_array($account_id)) {
            return array(false, "WRONG ARRAY OF ACCOUNT");
        }

        if (strlen($country)==3)
            $country = strtoupper($country);
        else
            $country = 'USA';

        $QUERY = "SELECT id, countrycode, countryname FROM cc_country WHERE countrycode='$country'";
        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
        if (!is_array($result)) {
            $id_country = 225;
        }
        $id_country = $result[0][0];

        $arr_did = array();

        $begin_date = date("Y");
        $begin_date_plus = date("Y") + 10;
        $end_date = date("-m-d H:i:s");
        $startingdate = $begin_date.$end_date;
        $expirationdate = $begin_date_plus.$end_date;
        $lensuf = strlen($did_suffix);
        $increment_did = 0;
        foreach ($account_id as $val_account_id) {

            $did_suffix_inc = $did_suffix + $increment_did;
            $did_suffix_inc = sprintf("%0$lensuf".'d', $did_suffix_inc);

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

            $QUERY = "SELECT username FROM cc_card WHERE id='$val_account_id'";
            $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
            if (!is_array($result)) {
                return array(false, "ERROR RETRIEVING THE USERNAME (id:".$val_account_id.")");
            }
            $username = $result[0][0];

            $func_table  = "cc_did_destination";
            $func_fields = "destination, priority, id_cc_card, id_cc_did, creationdate, activated, secondusedreal, voip_call";
            $id_name = "id";
            $value  = "'SIP/$username', 1, '$val_account_id', '$inserted', NOW(), 1, 0, 1";

            $inserted = $this->instance_table->Add_table($this->DBHandle, $value, $func_fields, $func_table, $id_name);

            if (!$inserted) {
                return array(false, "ERROR CREATING DID DESTINATION (".$increment_did." DID_DESTINATIONs created)");
            }

            $QUERY = "UPDATE cc_sip_buddies SET callerid='$did_to_create' WHERE id_cc_card=$val_account_id";
            $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY, 0);
            if (!$result) {
                return array(false, "SQL ERROR UPDATING callerid on cc_sip_buddies");
            }

            $QUERY = "UPDATE cc_iax_buddies SET callerid='$did_to_create' WHERE id_cc_card=$val_account_id";
            $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY, 0);
            if (!$result) {
                return array(false, "SQL ERROR UPDATING callerid on cc_iax_buddies");
            }

            $arr_did[] = $did_to_create;
        }

        return array(serialize($arr_did), "Create_DID SUCCESS - DIDs CREATION SUCCESSFUL (".$increment_did." DIDs & DID_DESTINATIONs created)");

    }

    //Get the Provider list with details
    /*
     * $provisioning_uri : http://www.YourDomain.com/provisioning.txt
     * sample result sent by the API
     * //YourDomain|A-Z termination providing global good rates and quality|http://myaccount.YourDomain.com/webservice/create-trunkconfig.php|http://myaccount.YourDomain.com/webservice/get_rates.php|http://YourDomain.com/images/logo.jpg
     YourDomain|A-Z termination providing global good rates and quality|http://www.YourDomain.com/Create_TrunkConfig.php|http://www.YourDomain.com/Get_Rates.php|http://YourDomain.com/images/logo.jpg

     VOIP.MS|Good A-Z Voip provider, specialized for US|http://test/uri_trunk|http://test/uri_rate|http://voip.ms/images/mainmenu/logo2.gif
     *
     * $RESULT : array(array (string $name, string $description, string $uri_trunk, string $uri_rate,string $uri_image), string $message)
     */
    public function Get_ProvisioningList ($security_key, $provisioning_uri)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
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

        return array(serialize($arr_provisioning), "Get_ProvisioningList SUCCESS");

    }

    /*
     *  Request to the Provider the Trunk configuration
     */
    public function Create_TrunkConfig($security_key, $instance, $uri_trunk, $activation_code, $provider_name)
    {
        $arr_check = $this->Check_KeyInstance($security_key, $instance);
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
        $provider_name = trim($provider_name);
        // remove all special chars
        $provider_name = preg_replace('/[^A-Za-z0-9_]*/', '', $provider_name);

        $trunk_name = $provider_name.'_'.$instance;

        $content = str_replace("trunkname", $trunk_name, $content);

        $tag_start_sipconfig = '#SIP-TRUNK-CONFIG-START#';
        $tag_end_sipconfig = '#SIP-TRUNK-CONFIG-END#';
        $tag_start_iaxconfig = '#SIP-TRUNK-CONFIG-START#';
        $tag_end_iaxconfig = '#SIP-TRUNK-CONFIG-END#';

        $pos_beg_sip = strpos ($content, $tag_start_sipconfig);
        $pos_end_sip = strpos ($content, $tag_end_sipconfig);
        $pos_beg_iax = strpos ($content, $tag_start_iaxconfig);
        $pos_end_iax = strpos ($content, $tag_end_iaxconfig);

        $len_extra_sip = (($pos_end_sip-$pos_beg_sip-strlen($tag_start_sipconfig)));
        $len_extra_iax = (($pos_end_iax-$pos_beg_iax-strlen($tag_start_iaxconfig)));

        if ($len_extra_sip < 0) $len_extra_sip = 0;
        if ($len_extra_iax < 0) $len_extra_iax = 0;

        $sip_config = substr($content, $pos_beg_sip + strlen($tag_start_sipconfig), $len_extra_sip)."\n\n";
        $iax_config = substr($content, $pos_beg_iax + strlen($tag_start_iaxconfig), $len_extra_iax)."\n\n";

        $use_sip = $use_iax = false;

        if (strlen($sip_config) > 30) {
            $use_sip = true;
        }

        if (strlen($iax_config) > 30) {
            $use_iax = true;
        }

        $astconf_path = '/etc/asterisk/';

        $sip_filename = "sip_additional_$provider_name_$instance.conf";
        $iax_filename = "iax_additional_$provider_name_$instance.conf";
        $sip_ast_filename = $astconf_path . $sip_filename;
        $iax_ast_filename = $astconf_path . $iax_filename;

        $date_format = date("Y-m-d_His");

        if ($use_sip) {

            if (file_exists($sip_ast_filename)) {
                rename($sip_ast_filename, $sip_ast_filename.'.'.$date_format);
            }
            // Write SIP conf
            $handle = fopen($sip_ast_filename, "w");
            if (fwrite($handle, $sip_config) === false) {
                return array(false, "ERROR - Could not create $sip_ast_filename");
            }
            fclose($handle);

            // CHECK AND ADD INCLUDE
            $content_sip_conf = file_get_contents($astconf_path.'sip.conf');

            $pos_include = strpos ($content_sip_conf, "$sip_filename");
            if ($pos_include === false) {
                // Add include in SIP conf
                $handle = fopen($astconf_path.'sip.conf', "a");
                if (fwrite($handle, "\n#include $sip_filename\n") === false) {
                    return array(false, "ERROR - Could not add the include in ".$astconf_path.'sip.conf');
                }
                fclose($handle);
            }

            // ADD NEW TRUNK IN DATABASE
            $func_fields = "trunkcode, providertech, providerip";
            $func_table = 'cc_trunk';
            $id_name = "id_trunk";
            $value = "'$instance', 'SIP', '$trunk_name'";
            $inserted = $this->instance_table->Add_table($this->DBHandle, $value, $func_fields, $func_table, $id_name);

            if (!$inserted) {
                return array(false, "ERROR CREATING TRUNK");
            }

            return array(true, "TRUNK CONFIG CREATED - SUCCESS");

        }

        // CREATE IAX TRUNK IF NO SIP CREATED
        if ($use_iax && !$use_sip) {

            if (file_exists($iax_ast_filename)) {
                rename($iax_ast_filename, $iax_ast_filename.'.'.$date_format);
            }
            // Write IAX conf
            $handle = fopen($iax_ast_filename, "w");
            if (fwrite($handle, $iax_config) === false) {
                return array(false, "ERROR - Could not create $iax_ast_filename");
            }
            fclose($handle);

            // CHECK AND ADD INCLUDE
            $content_iax_conf = file_get_contents($astconf_path.'iax.conf');

            $pos_include = strpos ($content_iax_conf, "$sip_filename");
            if ($pos_include === false) {
                // Add include in IAX conf
                $handle = fopen($astconf_path.'iax.conf', "a");
                if (fwrite($handle, "\n#include $iax_filename\n") === false) {
                    return array(false, "ERROR - Could not add the include in ".$astconf_path.'iax.conf');
                }
                fclose($handle);
            }

            // ADD NEW TRUNK IN DATABASE
            $func_fields = "trunkcode, providertech, providerip";
            $func_table = 'cc_trunk';
            $id_name = "id_trunk";
            $value = "'$instance', 'IAX', '$trunk_name'";
            $inserted = $this->instance_table->Add_table($this->DBHandle, $value, $func_fields, $func_table, $id_name);

            if (!$inserted) {
                return array(false, "ERROR CREATING TRUNK");
            }

            return array(true, "TRUNK CONFIG CREATED - SUCCESS");

        }

        return array(false, "ERROR - NO TRUNK CONFIG CREATED");
    }

    /*
     *  Retrieve the rates from the Provider
     */
    // RESULT : array(array(string $prefix, string $destination, float $buyrate, float $sellrate), string $message)
    public function Get_Rates($security_key, $uri_rate, $activation_code, $margin)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        $full_uri_rate = "$uri_rate?activation_code=$activation_code";
        $content = open_url ($full_uri_rate);

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

                    $arr_rates[] = array( $content_exp_val_arr[1], $content_exp_val_arr[0], $content_exp_val_arr[2], $rate_margin);
                }
            }
        }

        return array(serialize($arr_rates), "RATES RETURNED - SUCCESS");

    }

    /*
     *  CHECK RATES VALIDITY - function check_rates_validity
     */
    // array(string $prefix, string $destination, float $buyrate, float $sellrate)
    public function check_rates_validity ($arr_rates)
    {
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
    public function Create_Rates($security_key, $instance, $arr_rates)
    {
        $arr_check = $this->Check_KeyInstance($security_key, $instance);
        if ($arr_check[0] == 'ERROR') {
            return $arr_check;
        }
        $id_group = $arr_check;

        $QUERY = "SELECT id_trunk FROM cc_trunk WHERE trunkcode = '$instance'";
        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
        if (!is_array($result)) {
            return array(false, "CANNOT LOAD THE TRUNK FOR THIS INSTANCE");
        }
        $id_trunk = $result[0][0];

        $QUERY = "SELECT id FROM cc_tariffplan WHERE tariffname = '$instance'";
        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
        if (!is_array($result)) {
            return array(false, "CANNOT LOAD THE RATECARD FOR THIS INSTANCE");
        }
        $id_ratecard = $result[0][0];
        $nb_to_import = 0;
        $nb_rates = 0;

        // CHECK RATES VALIDITY
        if (!$this->check_rates_validity ($arr_rates)) {
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

            $FG_ADITION_SECOND_ADD_VALUE = "'$id_ratecard', '$id_trunk', '$dialprefix', '".intval($dialprefix)."', '$buyrate', '$sellrate', '$startdate', '$stopdate_prefix$stopdate_suffix'";
            $TT_QUERY = "INSERT INTO " . $FG_ADITION_SECOND_ADD_TABLE . " (" . $FG_ADITION_SECOND_ADD_FIELDS . ") values (" . $FG_ADITION_SECOND_ADD_VALUE . ") ";

            $result_query = $this->DBHandle->Execute($TT_QUERY);

            if (!$result_query) {
                return array(false, "ERROR RATES CREATION ($nb_to_import Rates imported)");
            }

            $nb_to_import++;
        }

        return array(true, "RATES CREATED ($nb_to_import Rates imported) - SUCCESS");

    }

    /*
     *  Update the rates of an existing provisioning
     */
    // array(bool $result, string $message)
    // array(string $prefix, string $destination, float $buyrate, float $sellrate)
    public function Update_Rates($security_key, $instance, $arr_rates)
    {
        $arr_check = $this->Check_KeyInstance($security_key, $instance);
        if ($arr_check[0] == 'ERROR') {
            return $arr_check;
        }
        $id_group = $arr_check;

        $QUERY = "SELECT id_trunk FROM cc_trunk WHERE trunkcode = '$instance'";
        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
        if (!is_array($result)) {
            return array(false, "CANNOT LOAD THE TRUNK FOR THIS INSTANCE");
        }
        $id_trunk = $result[0][0];

        $QUERY = "SELECT id FROM cc_tariffplan WHERE tariffname = '$instance'";
        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
        if (!is_array($result)) {
            return array(false, "CANNOT LOAD THE RATECARD FOR THIS INSTANCE");
        }
        $id_ratecard = $result[0][0];

        // CHECK RATES VALIDITY
        if (!$this->check_rates_validity ($arr_rates)) {
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

            $FG_ADITION_SECOND_ADD_VALUE = "'$id_ratecard', '$id_trunk', '$dialprefix', '".intval($dialprefix)."', '$buyrate', '$sellrate', '$startdate', '$stopdate_prefix$stopdate_suffix'";
            $TT_QUERY = "INSERT INTO " . $FG_ADITION_SECOND_ADD_TABLE . " (" . $FG_ADITION_SECOND_ADD_FIELDS . ") values (" . $FG_ADITION_SECOND_ADD_VALUE . ") ";

            $result_query = $this->DBHandle->Execute($TT_QUERY);

            if (!$result_query) {
                return array(false, "ERROR RATES CREATION ($nb_to_import Rates imported)");
            }

            $nb_to_import++;
        }

        return array(true, "RATES UPDATED ($nb_to_import Rates imported) - SUCCESS");

    }

    /*
     *  Update Account Status
     */
    // array(bool $result, string $message)
    public function Account_Status_Update($security_key, $card_id, $cardnumber, $status)
    {
        $arr_check = $this->Check_KeyInstance($security_key, $instance);
        if ($arr_check[0] == 'ERROR') {
            return $arr_check;
        }

        $param_update = "status = $status";
        if (is_numeric($card_id) && $card_id > 0 ) {
            $clause = " id = $card_id ";
        } else {
            $clause = " username = '$cardnumber' ";
        }

        $QUERY = "UPDATE cc_card SET $param_update WHERE $clause";
        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY, 0);
        if (!$result) {
            return array(false, "SQL ERROR UPDATING cc_card");
        }

        return array(true, 'Account_Status_Update SUCCESS');
    }

    /*
     *  Add CallerID to a specific card_id or accountnumber
     */
    // array(bool $result, string $message)
    public function Add_CallerID($security_key, $callerid, $card_id, $accountnumber)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        if (is_numeric($card_id) && $card_id > 0 ) {

            $id_cc_card = $card_id;

        } else {

            $QUERY = "SELECT id FROM cc_card WHERE username='$accountnumber'";
            $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
            if (!is_array($result) || $result[0][0] <= 0 ) {
                return array('ERROR', "ACCOUNTNUMBER DOES NOT EXIST");
            }
            $id_cc_card = $result[0][0];

        }

        $func_table  = "cc_callerid";
        $func_fields = "id_cc_card, cid, activated";
        $id_name = "id";
        $activated = "t";
        $value = "'$id_cc_card', '$callerid', '$activated'";

        $inserted = $this->instance_table->Add_table($this->DBHandle, $value, $func_fields, $func_table, $id_name);

        if (!$inserted) {
            return array(false, "ERROR ADDING CID");
        }

        return array(serialize($arr_cid), "Add_CallerID SUCCESS");
    }

     /*
     *      Get calls history from a card id
     */
    public function Get_Calls_History($security_key, $card_id, $starttime_begin, $starttime_end, $offset, $items_number, $terminatecauseid = 1)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        $QUERY = "SELECT cc_call.id, cc_call.sessionid, cc_call.uniqueid, cc_call.card_id, cc_call.nasipaddress, cc_call.starttime, cc_call.stoptime, cc_call.sessiontime, cc_call.calledstation, cc_call.sessionbill, cc_call.id_tariffgroup, cc_call.id_tariffplan, cc_call.id_ratecard, cc_call.id_trunk, cc_call.sipiax, cc_call.src, cc_call.id_did, cc_call.buycost, cc_call.id_card_package_offer, cc_call.real_sessiontime, cc_call.dnid, cc_call.terminatecauseid, cc_call.destination, cc_prefix.prefix, cc_prefix.destination
                  FROM cc_call LEFT OUTER JOIN cc_prefix ON (cc_call.destination = cc_prefix.prefix)
                  WHERE (cc_call.card_id = $card_id AND cc_call.starttime >= '$starttime_begin' AND cc_call.starttime <= '$starttime_end' AND cc_call.terminatecauseid = $terminatecauseid )
                  ORDER BY cc_call.starttime DESC
                  LIMIT $offset, $items_number";
        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
        if (!is_array($result)) {
            return array(false, "SQL ERROR Get_Calls_History");
        }

        return array(serialize($result), 'Get_Calls_History SUCCESS');
    }

     /*
      *      Get calls refill from a card id
      */
    public function Get_Log_Refill($security_key, $card_id, $offset, $items_number)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        $QUERY = "SELECT *
                  FROM cc_logrefill
                  WHERE (cc_logrefill.card_id = $card_id)
                  ORDER BY cc_logrefill.date DESC
                  LIMIT $offset, $items_number";
        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
        if (!is_array($result)) {
            return array(false, "SQL ERROR Get_Log_Refill");
        }

        return array(serialize($result), 'Get_Log_Refill SUCCESS');
    }

     /*
     *      Add a credit amount on a cc_card
     */
    public function Add_Credit($security_key, $card_id, $value, $description,$refill_type)
    {
        if (!$this->Check_SecurityKey ($security_key)) {
            return array("ERROR", "INVALID KEY");
        }

        $QUERY = "INSERT INTO cc_logrefill (credit,card_id,description,refill_type) VALUES ($value,$card_id,'$description',$refill_type)";
        $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY, 0);

        if (!$result) {
            return array(false, "SQL ERROR UPDATING cc_logrefill");
        } else {
            $QUERY = "UPDATE cc_card SET credit=credit+$value WHERE id = '$card_id' LIMIT 1";
            $result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY, 0);
            if (!$result) {
            return array(false, "SQL ERROR UPDATING cc_card");
            }
        }

        return array(true, 'Add_Credit SUCCESS');
    }

// end Class
}
