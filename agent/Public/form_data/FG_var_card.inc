<?php

getpost_ifset(array('id', 'username', 'useralias', 'uipass', 'language', 'tariff',
'id_didgroup','id_campaign', 'callback', 'activated','simultaccess', 'currency','typepaid', 'creditlimit',
'lastname', 'firstname', 'email', 'address','city', 'state', 'country', 'zipcode', 'phone', 'fax', 'inuse',
'cid', 'runservice', 'firstusedate','expirationdate', 'enableexpire', 'expiredays', 'sip_buddy',
'iax_buddy','popup_select', 'vat', 'autorefill', 'initialbalance', 'mac_addr', 'cardnumberlenght_list',
'status', 'template_invoice', 'template_outstanding', 'description', 'voicemail_activated', 'voicemail_permitted','email_notification','credit_notification','notify_email','id_agent'  ));



$HD_Form = new FormHandler("cc_card","Card");
$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_ID="id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";
$HD_Form -> FG_FILTER_SEARCH_SESSION_NAME = 'entity_card_selection';
$HD_Form -> FG_TABLE_CLAUSE = " cc_card.id_agent=".$_SESSION['agent_id'] ;
$HD_Form -> FG_FK_DELETE_CONFIRM = true;
$HD_Form -> FG_FK_DELETE_ALLOWED = true;
$HD_Form -> FG_FK_TABLENAMES = array('cc_iax_buddies', 'cc_sip_buddies', 'cc_callerid', 'cc_card_history');
$HD_Form -> FG_FK_EDITION_CLAUSE = array("id_cc_card", "id_cc_card", "id_cc_card", "id_cc_card");
// DEFINE IF WE WANT TO DELETE THE CARD OR NOT
$HD_Form -> FG_FK_WARNONLY = (DELETE_FK_CARD == true) ? false: true;

if (DELETE_FK_CARD){
	// WE WILL DELETE THE FK
	$HD_Form -> FG_FK_DELETE_MESSAGE = gettext("You are going as well to remove all the SIP/IAX accounts & CallerIDs attached to this card! Please confirm that you really want to remove this card ? ");
} else {
	// WE JUST NOTIFY
	$HD_Form -> FG_FK_DELETE_MESSAGE = gettext("You are going to remove a card that have SIP/IAX accounts and/or CallerIDs attached to it ! Please confirm that you really want to remove this card ? ");
}

// TODO Unify order of parameters

$language_list = array();
$language_list["0"] = array( gettext("ENGLISH"), "en");
$language_list["1"] = array( gettext("SPANISH"), "es");
$language_list["2"] = array( gettext("FRENCH"),  "fr");

$language_list_r = array();
$language_list_r["0"] = array("en", gettext("ENGLISH"));
$language_list_r["1"] = array("es", gettext("SPANISH"));
$language_list_r["2"] = array("fr", gettext("FRENCH"));


$simultaccess_list = array();
$simultaccess_list["0"] = array( gettext("INDIVIDUAL ACCESS"), "0");
$simultaccess_list["1"] = array( gettext("SIMULTANEOUS ACCESS"), "1");

$simultaccess_list_r = array();
$simultaccess_list_r["0"] = array( "0", gettext("INDIVIDUAL ACCESS"));
$simultaccess_list_r["1"] = array( "1", gettext("SIMULTANEOUS ACCESS"));


// Possible value to notify the user

$values = explode(":",$A2B->config['notifications']['values_notifications']);
$limits_notify = array();
$idx =0;
foreach ($values as $val){
 	$limits_notify [$idx] = array($val,$val);
	$idx++;
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


$cardstatus_list = array();
$cardstatus_list["0"]  = array( gettext("CANCELLED"), "0");
$cardstatus_list["1"]  = array( gettext("ACTIVE"), "1");
$cardstatus_list["2"]  = array( gettext("NEW"), "2");
$cardstatus_list["3"]  = array( gettext("WAITING-MAILCONFIRMATION"), "3");
$cardstatus_list["4"]  = array( gettext("RESERVED"), "4");
$cardstatus_list["5"]  = array( gettext("EXPIRED"), "5");
$cardstatus_list["6"]  = array( gettext("SUSPENDED FOR UNDERPAYEMENT"), "6");
$cardstatus_list["7"]  = array( gettext("SUSPENDED FOR LITIGATION"), "7");

$cardstatus_list_r = array();
$cardstatus_list_r["0"]  = array("0", gettext("CANCELLED"));
$cardstatus_list_r["1"]  = array("1", gettext("ACTIVE"));
$cardstatus_list_r["2"]  = array("2", gettext("NEW"));
$cardstatus_list_r["3"]  = array("3", gettext("WAITING-MAILCONFIRMATION"));
$cardstatus_list_r["4"]  = array("4", gettext("RESERVED"));
$cardstatus_list_r["5"]  = array("5", gettext("EXPIRED"));
$cardstatus_list_r["6"]  = array("6", gettext("SUSPENDED FOR UNDERPAYMENT"));
$cardstatus_list_r["7"]  = array("7", gettext("SUSPENDED FOR LITIGATION"));

$cardstatus_list_acronym = array();
$cardstatus_list_acronym["0"]  = array( gettext("<acronym title=\"CANCELLED\">".gettext("CANCEL")."</acronym>"), "0");
$cardstatus_list_acronym["1"]  = array( gettext("<acronym title=\"ACTIVE\">".gettext("ACTIV")."</acronym>"), "1");
$cardstatus_list_acronym["2"]  = array( gettext("<acronym title=\"NEW\">".gettext("NEW")."</acronym>"), "2");
$cardstatus_list_acronym["3"]  = array( gettext("<acronym title=\"WAITING-MAILCONFIRMATION\">".gettext("WAIT")."</acronym>"), "3");
$cardstatus_list_acronym["4"]  = array( gettext("<acronym title=\"RESERVED\">".gettext("RESERV")."</acronym>"), "4");
$cardstatus_list_acronym["5"]  = array( gettext("<acronym title=\"EXPIRED\">".gettext("EXPIR")."</acronym>"), "5");
$cardstatus_list_acronym["6"]  = array( gettext("<acronym title=\"SUSPENDED FOR UNDERPAYMENT\">".gettext("UNDERPAY")."</acronym>"), "6");
$cardstatus_list_acronym["7"]  = array( gettext("<acronym title=\"SUSPENDED FOR LITIGATION\">".gettext("LITIG")."</acronym>"), "7");

$typepaid_list = array();
$typepaid_list["0"]  = array( gettext("PREPAID CARD"), "0");
$typepaid_list["1"]  = array( gettext("POSTPAY CARD"), "1");

$expire_list = array();
$expire_list["0"]  = array( gettext("NO EXPIRY"), "0");
$expire_list["1"]  = array( gettext("EXPIRE DATE"), "1");
$expire_list["2"]  = array( gettext("EXPIRE DAYS SINCE FIRST USE"), "2");
$expire_list["3"]  = array( gettext("EXPIRE DAYS SINCE CREATION"), "3");

$template_invoice_list = array();
$no = 0;
$dir = opendir ('./templates/default/'.$A2B->config['global']['invoice_template_path']);
while (false !== ($file = readdir($dir))) {
	if (strpos($file, '.tpl',1)) {
		$template_invoice_list[''.$no++] = array(substr($file, 0, -4), $file);
	}
}
closedir($dir);

$template_outstanding_list = array();
$no = 0;
$dir = opendir ('./templates/default/'.$A2B->config['global']['outstanding_template_path']);
while (false !== ($file = readdir($dir))) {
	if (strpos($file, '.tpl',1)) {
		$template_outstanding_list[''.$no++] = array(substr($file, 0, -4), $file);
	}
}
closedir($dir);

$inuse_list = array();
for ($k=0;$k<=40;$k++)
	$inuse_list["$k"]  = array( gettext("currently in use")." : $k", "$k");

$actived_list = array();
$actived_list["t"] = array( gettext("On"), "t");
$actived_list["f"] = array( gettext("Off"), "f");

$yesno = array();
$yesno["1"] = array( gettext("Yes"), "1");
$yesno["0"] = array( gettext("No"), "0");

$invoiceday_list = array();
for ($k=0;$k<=28;$k++)
	$invoiceday_list["$k"]  = array( "$k", "$k");


$HD_Form -> AddViewElement(gettext("ID"), "id", "3%", "center", "sort");
if(stristr($_SERVER['REQUEST_URI'], 'A2B_entity_card_multi.php'))
	$HD_Form -> AddViewElement(gettext("TAG"), "tag", "11%", "center", "sort");
$HD_Form -> AddViewElement("Card Number", "username", "11%", "center", "sort", "", "30", "", "", "", "", "linktocustomer");
if (!($popup_select>=1))  $HD_Form -> AddViewElement("<acronym title=\"CARDALIAS\">".gettext("ALIAS")."</acronym>", "useralias", "12%", "center", "sort");
$HD_Form -> AddViewElement(gettext("LASTNAME"), "lastname", "10%", "center", "sort", "15");
$HD_Form -> AddViewElement("<acronym title=\"BALANCE\">".gettext("BA")."</acronym>", "credit", "5%", "center", "sort", "", "", "", "", "", "", "");

if (!($popup_select>=1)){
	$HD_Form -> AddViewElement(gettext("Call Plan"), "tariff", "10%", "center", "sort", "15", "lie", "cc_tariffgroup", "tariffgroupname", "id='%id'", "%1");
	$HD_Form -> AddViewElement(gettext("STATUS"), "status", "4%", "center", "sort", "", "list", $cardstatus_list_acronym);
	$HD_Form -> AddViewElement(gettext("LG"), "language", "3%", "center", "sort");
	$HD_Form -> AddViewElement(gettext("USE"), "inuse", "3%", "center", "sort");
	$HD_Form -> AddViewElement("<acronym title=\"".gettext("CURRENCY")."\">".gettext("CUR")."</acronym>", "currency", "5%", "center", "sort", "", "list", $currency_list_key);
	$HD_Form -> AddViewElement(gettext("SIP"), "sip_buddy", "3%", "center", "sort", "", "list", $yesno);
	$HD_Form -> AddViewElement(gettext("IAX"), "iax_buddy", "3%", "center", "sort", "", "list", $yesno);
	$HD_Form -> AddViewElement("<acronym title=\"AMOUNT OF CALL DONE\">".gettext("ACD")."</acronym>", "nbused", "4%", "center", "sort");
	if(stristr($_SERVER['REQUEST_URI'], 'A2B_entity_card_multi.php')) {
		$FG_COL_QUERY='id, tag, username, useralias, lastname, credit, tariff, status, language, inuse, currency, sip_buddy, iax_buddy, nbused';
	} else {
		$FG_COL_QUERY='id, username, useralias, lastname, credit, tariff, status, language, inuse, currency, sip_buddy, iax_buddy, nbused ';
	}
}else{
	$FG_COL_QUERY='id, username, lastname, credit';
}

$HD_Form -> FieldViewElement ($FG_COL_QUERY);


$HD_Form -> CV_NO_FIELDS  = gettext("NO CARDS HAVE BEEN CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_LIMITE_DISPLAY = 10;


// Code here for adding the fields in the Export File
$HD_Form -> FieldExportElement(CARD_EXPORT_FIELD_LIST);
if (!($popup_select>=1)) $HD_Form -> FG_EXPORT_CSV = true;
if (!($popup_select>=1)) $HD_Form -> FG_EXPORT_XML = true;
$HD_Form -> FG_EXPORT_SESSION_VAR = "pr_export_entity_card";


if (($popup_select>=1))	$HD_Form ->FG_LIMITE_DISPLAY=7;



$HD_Form -> CV_FOLLOWPARAMETERS= "&popup_select=".$_GET['popup_select']."&popup_formname=".$_GET['popup_formname']."&popup_fieldname=".$_GET['popup_fieldname'];

// Search form
if (!($popup_select>=1))	 $HD_Form -> FG_FILTER_SEARCH_FORM = true;
$HD_Form -> FG_FILTER_SEARCH_TOP_TEXT = gettext('Define specific criteria to search for cards created.');
$HD_Form -> FG_FILTER_SEARCH_1_TIME_TEXT = gettext('Creation date / Month');
$HD_Form -> FG_FILTER_SEARCH_2_TIME_TEXT = gettext('Creation date / Day');
$HD_Form -> FG_FILTER_SEARCH_2_TIME_FIELD = 'creationdate';
$HD_Form -> AddSearchElement_C1(gettext("CARDNUMBER"), 'username','usernametype');
$HD_Form -> AddSearchElement_C1(gettext("EMAIL"), 'email','emailtype');
$HD_Form -> AddSearchElement_C1(gettext("LASTNAME"),'lastname','lastnametype');
$HD_Form -> AddSearchElement_C1(gettext("CARDALIAS"),'useralias','useraliastype');
$HD_Form -> AddSearchElement_C1(gettext("MACADDRESS"),'mac_addr','macaddresstype');
$HD_Form -> AddSearchElement_C2(gettext("CARDID (SERIAL)"),'id1','id1type','id2','id2type','id');
$HD_Form -> AddSearchElement_C2(gettext("CREDIT"),'credit1','credit1type','credit2','credit2type','credit');
$HD_Form -> AddSearchElement_C2(gettext("INUSE"),'inuse1','inuse1type','inuse2','inuse2type','inuse');

$HD_Form -> FG_FILTER_SEARCH_FORM_SELECT_TEXT = '';
$HD_Form -> AddSearchElement_Select(gettext("SELECT LANGUAGE"), null, null, null, null, null, "language", 0, $language_list_r);
$HD_Form -> AddSearchElement_Select(gettext("SELECT TARIFF"), "cc_tariffgroup", "id, tariffgroupname, id", "", "tariffgroupname", "ASC", "tariff");
$HD_Form -> AddSearchElement_Select(gettext("SELECT STATUS"), null, null, null, null,null , "status", 0, $cardstatus_list_r);
$HD_Form -> AddSearchElement_Select(gettext("SELECT ACCESS"), null, null, null, null, null, "simultaccess", 0, $simultaccess_list_r);
$HD_Form -> AddSearchElement_Select(gettext("SELECT CURRENCY"), null, null, null, null, null, "currency", 0, $currency_list_r);


if (($popup_select>=1)) {
	$HD_Form -> FG_OTHER_BUTTON1 = true;
	$HD_Form -> FG_OTHER_BUTTON1_ALT = '<font color="red">&lt;select&gt;</font>';
	$HD_Form -> FG_OTHER_BUTTON1_IMG = '';

	if ($popup_select==1){
		$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:sendValue('|param|');";
		//echo"<a title=\"Select Card\" href=\"javascript:sendValue('".$recordset[$FG_NB_TABLE_COL]."');\">&lt;Select Card&gt;</a>";
	}elseif ($popup_select==2){
		$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:sendValue('|col1|');";
		//echo"<a title=\"Select Card\" href=\"javascript:sendValue('".$recordset[1]."');\">&lt;Select Card&gt;</a>";
	}

} else {
	$HD_Form -> FG_EDITION = true;
	$HD_Form -> FG_DELETION = true;
	$HD_Form -> FG_OTHER_BUTTON1 = true;
	$HD_Form -> FG_OTHER_BUTTON2 = true;
	$HD_Form -> FG_OTHER_BUTTON1_LINK="A2B_entity_friend.php?atmenu=sipfriend&form_action=add_sip&stitle=Customers_Card&id_cc_card=|col0|&cardnumber=|col1|&useralias=|col2|";
	$HD_Form -> FG_OTHER_BUTTON2_LINK="A2B_entity_friend.php?atmenu=iaxfriend&form_action=add_iax&stitle=Customers_Card&id_cc_card=|col0|&cardnumber=|col1|&useralias=|col2|";
	$HD_Form -> FG_OTHER_BUTTON1_IMG = Images_Path . "/icon-sip.gif";
	$HD_Form -> FG_OTHER_BUTTON2_IMG = Images_Path . "/icon-iax.gif";

	$HD_Form -> FG_OTHER_BUTTON1_ALT=gettext("SIP buddy");
	$HD_Form -> FG_OTHER_BUTTON2_ALT=gettext("IAX buddy");
}

$maxi='';
$maxi2='';
$maxi3='';
if (isset($form_action) && $form_action=="ask-add"){
	if (!is_numeric($cardnumberlenght_list)) 	$cardnumberlenght_list = LEN_CARDNUMBER;
	$array_card_generated  = gen_card_with_alias("cc_card", 0, $cardnumberlenght_list);

	$maxi = "value='".$array_card_generated[0]."'";
	$maxi2 = "value='".$array_card_generated[1]."'";
	$pass = MDP_NUMERIC(10);
	$maxi3 = "value='".$pass."'";
	$default_initbalance = "value='0'";
}

$HD_Form -> AddEditElement (gettext("CARD NUMBER"),
			   "username",
			   "",
			   "INPUT",
			    "size=30 $maxi readonly  maxlength=40",
			   "4",
			   gettext("Insert the username"),
			   "" , "", "",	"", "", "",	"", "" );

$HD_Form -> AddEditElement (gettext("CARD ALIAS"),
			   "useralias",
			   "",
			   "INPUT",
			    "size=20 $maxi2 maxlength=40",
			   "4",
			   gettext("Insert the useralias"),
			   "" , "", "",	"", "" , "", "", "");


$HD_Form -> AddEditElement (gettext("WEBUI PASSWORD"),
			   "uipass",
			   '',
			   "INPUT",
			   "size=20 $maxi3 maxlength=20",
			   "",
			   gettext("Insert the user password"),
			   "" , "", "",	"", "", "",	"", gettext("Password for customer to access to the web interface and view the balance.") );


$HD_Form -> AddEditElement (gettext("LANGUAGE"),
			   "language",
			   "",
			   "SELECT",
			   "",
			   "",
			   "",
			   "list" , "", "",	"", $language_list, "%1", "", "" );


$HD_Form -> AddEditElement (gettext("CALL PLAN"),
			   "tariff",
			   "",
			   "SELECT",
			   "", "", "",
			   "sql",
			   "cc_tariffgroup, cc_agent_tariffgroup",
			   "tariffgroupname, id",
			   "cc_tariffgroup.id = cc_agent_tariffgroup.id_tariffgroup AND cc_agent_tariffgroup.id_agent = ".$_SESSION['agent_id'] , "", "%1", "", "");


$HD_Form -> AddEditElement (gettext("DIDGROUP"),
			   "id_didgroup",
			   "",
			   "SELECT",
			   "", "", "",
			   "sql",
			   "cc_didgroup",
			   "didgroupname, id",
			   "", "", "%1", "", "", "", '<OPTION  value="-1" selected>'.gettext("NOT DEFINED").'</OPTION>' );

/*
// The campaign for predictive dialer is removing for now, as this feature arent supported anymore
// this is not fully depreciated
$HD_Form -> AddEditElement (gettext("CAMPAIGN"),
			   "id_campaign",
			   "",
			   "SELECT",
			   "", "", "",
			   "sql",
			   "cc_campaign",
			   "campaign_name, id",
			   "", "", "%1", "", "", "", '<OPTION  value="-1" selected>'.gettext("NOT DEFINED").'</OPTION>' );
*/

$HD_Form -> AddEditElement (gettext("CALLBACK"),
			   "callback",
			   '',
			   "INPUT",
			   "size=40 maxlength=60",
			   "",
			   gettext("Insert the callback"),
			   "" , "", "",	"", "", "",	"", gettext("Configure the callback url, ie : SIP/jeremy@211.23.76.12 or ZAP/g2, etc...") );


$HD_Form -> AddEditElement (gettext("STATUS"),
			   "status",
			   "",
			   "SELECT",
			   "",
			   "",
			   "",
			   "list" , "", "",	"", $cardstatus_list, "%1", "", "" );


$HD_Form -> AddEditElement (gettext("SIGNUP CONFIRMATION"),
			   "activatedbyuser",
			   't',
			   "RADIOBUTTON",
			   "",
			   "",
			   gettext("Choose if you want to activate this card"),
			   "" , "", "" , "Yes :t, - No:f",	"", "", "", gettext("Configure if the card has been activated over the signup confirmation mail") );

$HD_Form -> AddEditElement (gettext("SIMULTANEOUS ACCESS"),
			   "simultaccess",
			   "0",
			   "SELECT",
			   "",
			   "",
			   "",
			   "list" , "", "",	"", $simultaccess_list, "%1", "", "" );


$HD_Form -> AddEditElement (gettext("CURRENCY"),
			   "currency",
			   "",
			   "SELECT",
			   "",
			   "", "", "list" , "", "",	"", $currency_list, "%1", "", gettext("Currency used at the customer end.") );


$HD_Form -> AddEditElement (gettext("RUN SERVICE"),
			   "runservice",
			   '0',
			   "RADIOBUTTON",
			   "",
			   "",
			   gettext("Choose if you want to enable the service recurring on this card"),
			   "" , "", "", "Yes :1, - No:0", "", "" , "", gettext("Apply recurring service to this card."));


$HD_Form -> AddEditElement (gettext("RUN AUTOREFILL"),
			   "autorefill",
			   '0',
			   "RADIOBUTTON",
			   "",
			   "",
			   gettext("Choose if you want to enable the autorefill on this card"),
			   "" , "", "", "Yes :1, - No:0", "", "" , "", gettext("Define if you want to authorize the autorefill to apply on this card"));

$HD_Form -> AddEditElement (gettext("INITIAL BALANCE"),
			   "initialbalance",
			   "",
			   "INPUT",
			   "size=30 $default_initbalance maxlength=30",
			   "12",
			   gettext("Insert the amount of the initial balance"),
			   "" , "", "",	"", "", "",	"", gettext("The initial balance is used by autorefill to reset the current balance to this amount"));

$HD_Form -> AddEditElement (gettext("CARD TYPE"),
			   "typepaid",
			   "",
			   "SELECT",
			   "",
			   "",
			   "",
			   "list" , "", "",	"", $typepaid_list, "%1", "", "" );


$HD_Form -> AddEditElement (gettext("CREDIT LIMIT"),
			   "creditlimit",
			   "",
			   "INPUT",
			   "size=20 maxlength=20",
			   "4",
			   gettext("Insert the credit limit"),
			   "" , "", "",	"", "", "", "NO-NULL", gettext("Credit limit is only used for POSTPAY card."));


$HD_Form -> AddEditElement (gettext("FIRST USE DATE"),
			   "firstusedate",
			   "",
			   "INPUT",
			   "size=40 maxlength=40 readonly",
			   "",
			   "the first use date",
			   "" , "" , "", "", "", "", "NO-NULL", "", "", "res_display_dateformat");


$HD_Form -> AddEditElement (gettext("ENABLE EXPIRY"),
			   "enableexpire",
			   "",
			   "SELECT",
			   "",
			   "",
			   "",
			   "list" , "", "",	"", $expire_list, "%1", "", gettext("Select method of expiry for the card.") );



$comp_zero = $comp_date_plus='';
if (isset($form_action) && $form_action=="ask-add"){
	$begin_date = date("Y");
	$begin_date_plus = date("Y")+10;
	$end_date = date("-m-d H:i:s");
	$comp_date = "value='".$begin_date.$end_date."'";
	$comp_date_plus = "value='".$begin_date_plus.$end_date."'";
	$comp_zero = "value='0'";
}

$HD_Form -> AddEditElement (gettext("EXPIRY DATE"),
			   "expirationdate",
			   '$value',
			   "INPUT",
			   "size=40 maxlength=40  $comp_date_plus",
			   "10",
			   gettext("Insert the expiration date"),
			   "" , "", "",	"", "", "", "NO-NULL", gettext("please respect the format YYYY-MM-DD HH:MM:SS. For instance, '2004-12-31 00:00:00'"), "", "res_display_dateformat");



$HD_Form -> AddEditElement (gettext("EXPIRY DAYS"),
			   "expiredays",
			   "",
			   "INPUT",
			    "size=10 maxlength=6 $comp_zero",
			   "4",
			   gettext("Insert the number of days after which the card will expire"),
			   "" , "", "",	"", "", "", "", gettext("The number of days after which the card will expire."));

$HD_Form -> AddEditElement (gettext("PERMITTED VOICEMAIL"),
			   "voicemail_permitted",
			   '1',
			   "RADIOBUTTON",
			   "",
			   "",
			   gettext("Choose if you want to enable the voicemail functionality for this card"),
			   "" , "", "", "Yes :1, - No:0", "", "" , "", gettext("Enable the voicemail functionality for this card."));

$HD_Form -> AddEditElement (gettext("ACTIVE VOICEMAIL SERVICE"),
			   "voicemail_activated",
			   '0',
			   "RADIOBUTTON",
			   "",
			   "",
			   gettext("Customer can choose to enable the voicemail for this card"),
			   "" , "", "", "Yes :1, - No:0", "", "" , "", gettext("Customer can enable the voicemail for this card."));

$HD_Form -> AddEditElement (gettext("INVOICE DAY"),
			   "invoiceday",
			   '$value',
			   "SELECT",
			   "",
			   "",
			   "",
			   "list" , "", "",	"", $invoiceday_list, "%1", "", gettext("Define the day of the month when the system will generate the customer invoice.") );


$HD_Form -> AddEditElement (gettext("VAT"),
			   "vat",
			   "",
			   "INPUT",
			   "size=10 maxlength=6",
			   "12",
			   gettext("Insert the number for the VAT"),
			   "" , "", "",	"", "", "", "NO-NULL" , gettext("VAT to add on the invoice of this customer. it should be a decimal value '21' this will be for 21% of VAT!"));


$HD_Form -> AddEditElement (gettext("LASTNAME"),
			   "lastname",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=50",
			   "11",
			   gettext("Insert the lastname of the customers"),
			   "" , "", "",	"", "", "" , "NO", "");

$HD_Form -> AddEditElement (gettext("FIRSTNAME"),
			   "firstname",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=50",
			   "11",
			   gettext("Insert the firstname of the customers"),
			   "" , "", "",	"", "", "" , "NO", "");


$HD_Form -> AddEditElement (gettext("EMAIL"),
			   "email",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=70",
			   "1",
			   gettext("Insert the email"),
			   "" , "", "",	"", "", "" , "NO", "");


$HD_Form -> AddEditElement (gettext("ADDRESS"),
			   "address",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=100",
			   "0",
			   gettext("Insert the address of the customers"),
			   "" , "", "",	"", "", "" , "NO", "");


$HD_Form -> AddEditElement (gettext("CITY"),
			   "city",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=40",
			   "0",
			   gettext("Insert the city"),
			   "" , "", "",	"", "", "" , "NO", "");


$HD_Form -> AddEditElement (gettext("STATE/PROVINCE"),
			   "state",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=40",
			   "11",
			   gettext("Insert the state"),
			   "" , "", "",	"", "", "" , "NO", "");

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
			   "size=30 maxlength=20",
			   "0",
			   gettext("Insert the zipcode"),
			   "" , "", "",	"", "", "" , "NO", "");

$timezone_list = get_timezones();
foreach($timezone_list as $key => $cur_value) {
	if(in_array(SERVER_GMT,$cur_value)){
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

$HD_Form -> AddEditElement (gettext("PHONE NUMBER"),
			   "phone",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=20",
			   "7",
			   gettext("Insert the phone number of the customers"),
			   "" , "", "",	"", "", "" , "NO", "");


$HD_Form -> AddEditElement (gettext("FAX NUMBER"),
			   "fax",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=20",
			   "",
			   gettext("Insert the fax number of the customers"),
			   "" , "", "",	"", "", "" , "NO", "");


$HD_Form -> AddEditElement (gettext("SIP ACCOUNT"),
			   "sip_buddy",
			   '0',
			   "RADIOBUTTON",
			   "",
			   "",
			   gettext("Choose if you want to enable the sip account"),
			   "" , "", "" , "Yes :1, - No:0", "", "" , "", gettext("Enable SIP account"));


$HD_Form -> AddEditElement (gettext("IAX ACCOUNT"),
			   "iax_buddy",
			   '0',
			   "RADIOBUTTON",
			   "",
			   "",
			   gettext("Choose if you want to enable the iax account"),
			   "" , "", "" , "Yes :1, - No:0",  "", "" , "", gettext("Enable IAX account"));

$HD_Form -> AddEditElement (gettext("MAC ADDRESS"),
			   "mac_addr",
			   '$value',
			   "INPUT",
			   "size=20 maxlength=17",
			   "",
			   gettext("Insert the MAC address customers' device"),
			   "" , "", "",	"", "", "" , "NO", gettext("FORMAT: 00-08-74-4C-7F-1D"));


$HD_Form -> AddEditElement (gettext("IN USE"),
			   "inuse",
			   '$value',
			   "SELECT",
			   "",
			   "",
			   "",
			   "list" , "", "",	"", $inuse_list, "%1", "", gettext("Define the number of use of this cardnumber. If a non-standard stop of the application occurs it maybe that the card is in 'in-use' > 0  status. Get it back to '0' if you know there is a issue on this card.") );

$HD_Form -> AddEditElement (gettext("INVOICE TEMPLATE"),
			   "template_invoice",
			   '',
			   "SELECT",
			   "",
			   "",
			   "",
			   "list" , "", "",	"", $template_invoice_list, "%1", "", gettext("Define the default template to use to display invoice.") );

$HD_Form -> AddEditElement (gettext("INCUR TEMPLATE"),
			   "template_outstanding",
			   '',
			   "SELECT",
			   "",
			   "",
			   "",
			   "list" , "", "",	"", $template_outstanding_list, "%1", "", gettext("Define the default template to use to display incur.") );


$HD_Form -> AddEditElement (gettext("LIMIT CREDIT NOTIFICATION"),
			   "credit_notification",
			   '',
			   "SELECT",
			   "",
			   "",
			   gettext("Insert the number of credit when the customer receive a notification"),
			   "list" , "", "", "", $limits_notify , "%1", "", gettext("currency : ").strtoupper(BASE_CURRENCY),"", '<OPTION  value="-1" selected>'.gettext("NOT DEFINED").'</OPTION>'  );


$HD_Form -> AddEditElement (gettext("PERMITTED NOTIFICATIONS BY MAIL"),
			   "notify_email",
			   '0',
			   "RADIOBUTTON",
			   "",
			   "",
			   gettext("Choose if you want to enable the notification by email for this card"),
			   "" , "", "", "Yes :1, - No:0", "", "" , "", gettext("Enable the notification by mail for this card."));

$HD_Form -> AddEditElement (gettext("EMAIL NOTIFICATION"),
			   "email_notification",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=70",
			   "1",
			   gettext("Insert the email to notify the customers"),
			   "" , "", "",	"", "", "" , "NO", "");







$HD_Form -> AddEditElement (gettext("CALLERID"),
			   "callerid",
			   '$value',
			   "HAS_MANY",
			   "",
			   "",
			   "",
			   "",
			   "",
			   "",
			   "", "", "", "", "", "cc_callerid:cid:cid, cid, id_cc_card:id_cc_card = %id::id_cc_card");



$HD_Form -> AddEditElement (gettext("CARD HISTORY"),
			   "description",
			   '$value',
			   "HAS_MANY",
			   "multiline",
			   "",
			   "",
			   "",
			   "",
			   "",
			   "", "", "", "", "", "cc_card_history:description:description,id_cc_card:id_cc_card = %id::id_cc_card");

			   // cc_card_history:description:description,id_cc_card:id_cc_card = %id::id_cc_card
			   // Above function will provid Has Many functionality
			   // Each parameter is seperated by (:) notation
			   // 0. HAS_MANY will allow to get one to many functionality
			   // 1. First param is the table name
			   // 2. Second param is the join condition coloumn
			   // 3. Third will get all the columns (but first should be joining column)
			   // 4. Fourth param generates the where cluase
			   // 5. Last, Sixth param is the FK column


$HD_Form -> FieldEditElement ('username, useralias, uipass,  language, tariff, id_didgroup, callback, status,  activatedbyuser, simultaccess, currency, runservice, autorefill, initialbalance, typepaid, creditlimit,	firstusedate, enableexpire, expirationdate, expiredays, voicemail_permitted, voicemail_activated, invoiceday, vat, lastname, firstname, email, address, city, state, country, zipcode, id_timezone, phone, fax, sip_buddy, iax_buddy, mac_addr, inuse, template_invoice, template_outstanding, credit_notification, notify_email, email_notification ' );




if(!isset($myusername)) $myusername='';

if (DB_TYPE != "postgres") {
	$HD_Form ->FG_QUERY_ADITION_HIDDEN_FIELDS = "creationdate ";
	$HD_Form ->FG_QUERY_ADITION_HIDDEN_VALUE  = "now() ";
}

$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'cardnumber';
$HD_Form -> FG_FILTERFIELDNAME = 'cardnumber';
$HD_Form -> FG_FILTER_FORM_ACTION = 'list';


if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="username like '$filterprefix%'";
}



$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");





$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Once you have completed the form above, click on the CONTINUE button.");


$HD_Form -> FG_ADDITIONAL_FUNCTION_AFTER_ADD = 'create_sipiax_friends';
$HD_Form -> FG_ADDITIONAL_FUNCTION_AFTER_EDITION = 'create_status_log';

if (isset($form_action) && $form_action=='delete'){
	$_SESSION["is_sip_iax_change"]	= 1;
	$_SESSION["is_sip_changed"]		= 1;
	$_SESSION["is_iax_changed"]		= 1;
}



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";





