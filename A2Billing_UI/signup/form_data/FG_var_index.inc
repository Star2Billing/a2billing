<?php

getpost_ifset(array('id', 'username', 'useralias', 'uipass', 'credit', 'language', 'tariff',
                    'activated', 'simultaccess', 'currency', 'typepaid', 'creditlimit',
                    'lastname', 'firstname', 'email', 'address', 'city', 'state', 'country',
                    'zipcode', 'phone', 'fax', 'inuse', 'cid', 'runservice', 'firstusedate',
                    'expirationdate', 'enableexpire', 'expiredays','captchacode'));


$HD_Form = new FormHandler("cc_card","User");
$HD_Form -> FG_DEBUG = 0;
// FAQ
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";
$HD_Form -> FG_FILTER_SEARCH_SESSION_NAME = 'entity_card_selection';


// TODO Unify order of parameters

$language_list = array();
$language_list["0"] = array( "ENGLISH", "en");
$language_list["1"] = array( "SPANISH", "es");
$language_list["2"] = array( "FRENCH",  "fr");

$language_list_r = array();
$language_list_r["0"] = array("en", "ENGLISH");
$language_list_r["1"] = array("es", "SPANISH");
$language_list_r["2"] = array("fr", "FRENCH");

$callPlan_idlist = isset($A2B->config["signup"]['callplan_id_list'])?$A2B->config["signup"]['callplan_id_list']:null; 
if(strlen($callPlan_idlist) == 0)
{
	exit("No Call Plan Defined.");
}


$call_table = new Table("cc_tariffgroup", "tariffgroupname,id");
$HD_Form -> setDBHandler (DbConnect());
$callPlan_list = $call_table-> Get_list($HD_Form -> DBHandle,"id in ($callPlan_idlist)");

if (!is_array($callPlan_list) && count($callPlan_list) <= 0)
{
	exit(gettext("No Call Plan id are defined"));
} 
$flag_callplan = 0;
if ($tariff!="" || $tariff != null)
{
	$callPlan_count = count($callPlan_list);	
	for ($i=0; $i< $callPlan_count; $i++ )
	{
		if($callPlan_list[$i][1] == trim($tariff,"\'"))
		{
			$flag_callplan = 1;
		}		
	}
	if($flag_callplan == 0)
	{
		exit("Invalid Call Plan Id");	
	}	 
}

$currency_list = array();
$currency_list_r = array();
$indcur=0;

$currencies_list = get_currencies();

foreach($currencies_list as $key => $cur_value) {
	$currency_list[$key]  = array( $cur_value[1].' ('.$cur_value[2].')', $key);
	$currency_list_r[$key]  = array( $key, $cur_value[1]);
	$currency_list_key[$key][0] = $key;
}



$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_LIMITE_DISPLAY = 10;
if (!($popup_select>=1)) $HD_Form -> FG_EXPORT_CSV = true;
if (!($popup_select>=1)) $HD_Form -> FG_EXPORT_XML = true;

if (($popup_select>=1))	$HD_Form ->FG_LIMITE_DISPLAY=7;

$HD_Form -> CV_FOLLOWPARAMETERS= "&popup_select=".$_GET['popup_select']."&popup_formname=".$_GET['popup_formname']."&popup_fieldname=".$_GET['popup_fieldname'];

$maxi='';
$maxi2='';
$maxi3='';
if (isset($form_action) && $form_action=="add")
{
	$array_card_generated  = gen_card_with_alias();
    $maxi = $array_card_generated[0];	
    $maxi2 = $array_card_generated[1];
	$pass = MDP_NUMERIC(10);
    $loginkey = MDP_STRING(20);
}

$HD_Form -> AddEditElement ("LANGUAGE",
			   "language",
			   "",
			   "SELECT",
			   "",
			   "",
			   "",
			   "list" , "", "",	"", $language_list, "%1", "", "" );


$HD_Form -> AddEditElement ("CURRENCY",
			   "currency",
			   "",
			   "SELECT",
			   "",
			   "",
			   "",
			   "list" , "", "",	"", $currency_list, "%1", "", "" );

if (strlen($callPlan_idlist) > 1)
{
$HD_Form -> AddEditElement (gettext("CALL PLAN"),
			   "tariff",
			   "",
			   "SELECT",
			   "",
			   "",
			   "",
			   "list" , "", "",	"", $callPlan_list, "%1", "", "" );			   
}
$comp_zero = $comp_date_plus='';
if (isset($form_action) && $form_action=="ask-add"){
	$begin_date = date("Y");
	$begin_date_plus = date("Y")+10;
	$end_date = date("-m-d H:i:s");
	$comp_date = "value='".$begin_date.$end_date."'";
	$comp_date_plus = "value='".$begin_date_plus.$end_date."'";
	$comp_zero = "value='0'";
}

$HD_Form -> AddEditElement (gettext("LASTNAME"),
			   "lastname",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=30",
			   "11",
			   gettext("Insert your lastname"),
			   "" , "", "",	"", "", "" , "", "");

$HD_Form -> AddEditElement (gettext("FIRSTNAME"),
			   "firstname",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=30",
			   "11",
			   gettext("Insert your firstname"),
			   "" , "", "",	"", "", "" , "", "");


$HD_Form -> AddEditElement (gettext("EMAIL"),
			   "email",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=30",
			   "1",
			   gettext("Insert your email"),
			   "" , "", "",	"", "", "" , "", "");


$HD_Form -> AddEditElement (gettext("ADDRESS"),
			   "address",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=30",
			   "0",
			   gettext("Insert your address"),
			   "" , "", "",	"", "", "" , "", "");


$HD_Form -> AddEditElement (gettext("CITY"),
			   "city",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=30",
			   "0",
			   gettext("Insert your city"),
			   "" , "", "",	"", "", "" , "", "");


$HD_Form -> AddEditElement (gettext("STATE/PROVINCE"),
			   "state",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=30",
			   "11",
			   gettext("Insert your state"),
			   "" , "", "",	"", "", "" , "", "");


$HD_Form -> AddEditElement (gettext("COUNTRY"),
			   "country",
			   "",
			   "SELECT",
			   "", "", "",
			   "sql",
			   "cc_country",
			   "countryname, countrycode",
			   "", "", "%1", "", "", "", '' );


$HD_Form -> AddEditElement (gettext("ZIP/POSTAL CODE"),
			   "zipcode",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=30",
			   "0",
			   gettext("Insert your zipcode"),
			   "" , "", "",	"", "", "" , "", "");

$timezone_list = get_timezones();  
foreach($timezone_list as $key => $cur_value) {
	if(in_array(SERVER_GMT, $cur_value)){
		$timezone_list[$key]  = array( $cur_value[2], $key." selected");
	}else{
		$timezone_list[$key]  = array( $cur_value[2], $key);
	}
}


$HD_Form -> AddEditElement (gettext("TIMEZONE"),
			   "id_timezone",
			   "",
			   "SELECT",
			   "","","",
			   "list" , "", "",	"", $timezone_list, "%1", "", "" );
			   
$HD_Form -> AddEditElement (gettext("PHONENUMBER"),
			   "phone",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=30",
			   "7",
			   gettext("Insert your phone number"),
			   "" , "", "",	"", "", "" , "", "");


$HD_Form -> AddEditElement (gettext("FAXNUMBER"),
			   "fax",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=30",
			   "",
			   gettext("Insert your fax number"),
			   "" , "", "",	"", "", "" , "", "");
			   
$HD_Form -> AddEditElement (gettext("VERIFICATION"),
			   "captchacode",
			   '$value',
			   "CAPTCHAIMAGE",
			   "size=10 maxlength=6",
			   "19",
			   gettext("Insert code from above picture"),
			   "" , "", "",	"", "", "" , "", "", null, null, null,CAPTCHA_ENABLE);

if (strlen($callPlan_idlist) > 1)
{
	$HD_Form -> FieldEditElement ('username, useralias, uipass, credit, language, tariff, id_didgroup, id_campaign,'.
	'callback, activated, simultaccess, currency, runservice, typepaid, creditlimit, firstusedate, enableexpire,'.
	'expirationdate, expiredays, vat, lastname, firstname, email, address, city, state, country, zipcode, id_timezone, phone,'.
	'fax, captchacode, sip_buddy, iax_buddy, inuse');
}
else
{
	$HD_Form -> FieldEditElement ('username, useralias, uipass, credit, language, id_didgroup, id_campaign,'.
	'callback, activated, simultaccess, currency, runservice, typepaid, creditlimit, firstusedate, enableexpire,'.
	'expirationdate, expiredays, vat, lastname, firstname, email, address, city, state, country, zipcode, id_timezone, phone,'.
	'fax, captchacode, sip_buddy, iax_buddy, inuse');
}

if(!isset($myusername)) $myusername='';
//Code here for Getting Data from configuration files
$comp_date_plus = $A2B->config["signup"]['expirationdate'];

if (!$A2B->config["signup"]['expirationdate']){
	$comp_date_plus='';
	$begin_date = date("Y");
	$begin_date_plus = date("Y")+10;
	$end_date = date("-m-d H:i:s");
	$comp_date_plus = $begin_date_plus.$end_date;
}

$mytariff = '';
$mycredit = '';

$myexpirationdate = $comp_date_plus;
$mytariff = $callPlan_idlist;
$myactivated = $A2B->config["signup"]['activated'] ? 't' : 'f';
$mysimultaccess = $A2B->config["signup"]['simultaccess'];
$mytypepaid = $A2B->config["signup"]['typepaid'];
$mycreditlimit = $A2B->config["signup"]['creditlimit'];
$myrunservice = $A2B->config["signup"]['runservice'];
$myenableexpire = $A2B->config["signup"]['enableexpire'];
$myexpiredays = $A2B->config["signup"]['expiredays'];
$mycredit = $A2B->config["signup"]['credit'];
$sip_account = $A2B->config["signup"]['sip_account'];
$iax_account = $A2B->config["signup"]['iax_account'];
$amaflag = $A2B->config["signup"]['amaflag'];
$context = $A2B->config["signup"]['context'];
$activatedbyuser = $A2B->config["signup"]['activatedbyuser'] ? 't' : 'f';

//END END END  Code here for getting data from configuration files


//$HD_Form ->FG_QUERY_ADITION_SIP_IAX_VALUE = "'$maxi', '$maxi', '$maxi', '$amaflag', '$maxi2', '$context', 'RFC2833','dynamic', 'friend', '$maxi', 'g729,ulaw,alaw,gsm','$pass'";

$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS = "iax_buddy, sip_buddy, expiredays, expirationdate, typepaid, enableexpire, runservice, simultaccess, activated, credit, username, useralias, uipass,  userpass, loginkey, activatedbyuser";
$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  = "'$iax_account','$sip_account','$myexpiredays','$myexpirationdate','$mytypepaid','$myenableexpire', '$myrunservice','$mysimultaccess','$myactivated','$mycredit','$maxi','$maxi2','$pass','$maxi','$loginkey','$activatedbyuser'";

// username, useralias, uipass, loginkey
$HD_Form -> FG_QUERY_EXTRA_HIDDED = array($maxi, $maxi2, $pass, $loginkey);


if(strlen($callPlan_idlist) == 1)
{
	$HD_Form ->FG_QUERY_ADITION_HIDDEN_FIELDS .= ", tariff";
	$HD_Form ->FG_QUERY_ADITION_HIDDEN_VALUE .= ", '$mytariff'";
}
if (DB_TYPE != "postgres"){
	$FG_QUERY_ADITION_HIDDEN_FIELDS .= ", creationdate ";
	$FG_QUERY_ADITION_HIDDEN_VALUE  .= ",now() ";
}


$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'cardnumber';
$HD_Form -> FG_FILTERFIELDNAME = 'cardnumber';
$HD_Form -> FG_FILTER_FORM_ACTION = 'list';


$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your ".$HD_Form->FG_INSTANCE_NAME);
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this ".$HD_Form->FG_INSTANCE_NAME.", click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new ".$HD_Form->FG_INSTANCE_NAME.".<br>Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = "<font>".gettext("Thanks for registering with us.<br>A email with your information has been sent to your account ").$_POST["email"]."<br><br><b>".gettext("Your card number is ").$maxi."</b><br><b>".gettext("Your password is ").$pass."</b></font>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Once you have completed the form above, click on the CONFIRM DATA button.");

if(RELOAD_ASTERISK_IF_SIPIAX_CREATED)
{
	$HD_Form -> FG_ADDITIONAL_FUNCTION_AFTER_ADD = 'create_sipiax_friends_reload';
}
else
{
	$HD_Form -> FG_ADDITIONAL_FUNCTION_AFTER_ADD = 'create_sipiax_friends';
}


//$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
//$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = "signup_confirmation.php?id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";

?>
