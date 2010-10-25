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



getpost_ifset(array('id', 'username', 'useralias', 'uipass', 'credit', 'language', 'tariff',
'id_didgroup','id_campaign', 'callback', 'simultaccess', 'currency','typepaid', 'creditlimit',
'lastname', 'firstname', 'email', 'address','city', 'state', 'country', 'zipcode', 'phone', 'fax', 'inuse',
'cid', 'runservice', 'firstusedate','expirationdate', 'enableexpire', 'expiredays', 'sip_buddy',
'iax_buddy','popup_select', 'vat', 'autorefill', 'initialbalance', 'mac_addr', 'cardnumberlenght_list',
'status', 'block', 'lock_pin', 'template_invoice', 'template_outstanding', 'description', 'voicemail_activated', 
'voicemail_permitted','email_notification','credit_notification','notify_email', 'id_seria',
'company_name' , 'company_website' , 'vat_rn' , 'traffic' , 'traffic_target','discount','restriction' ));

$HD_Form = new FormHandler("cc_card", gettext("Card"));

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_ID = "id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";
$HD_Form -> FG_FILTER_SEARCH_SESSION_NAME = 'entity_card_selection';
$HD_Form -> FG_FK_DELETE_CONFIRM = true;
$HD_Form -> FG_FK_DELETE_ALLOWED = true;
$HD_Form -> FG_FK_TABLENAMES = array('cc_iax_buddies', 'cc_sip_buddies', 'cc_callerid', 'cc_card_history','cc_status_log');
$HD_Form -> FG_FK_EDITION_CLAUSE = array("id_cc_card", "id_cc_card", "id_cc_card", "id_cc_card","id_cc_card");
// DEFINE IF WE WANT TO DELETE THE CARD OR NOT
$HD_Form -> FG_FK_WARNONLY = (DELETE_FK_CARD == true) ? false: true;

if (DELETE_FK_CARD) {
	// WE WILL DELETE THE FK
	$HD_Form -> FG_FK_DELETE_MESSAGE = gettext("You are going as well to remove all the SIP/IAX accounts & CallerIDs attached to this card! Please confirm that you really want to remove this card ? ");
} else {
	// WE JUST NOTIFY
	$HD_Form -> FG_FK_DELETE_MESSAGE = gettext("You are going to remove a card that have SIP/IAX accounts and/or CallerIDs attached to it ! Please confirm that you really want to remove this card ? ");
}

if (!($popup_select>=1)) {
	$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
	$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_card_multi.php?stitle=Card&section=".$_SESSION["menu_section"];
	$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Generate Customers");
	$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/user_multi_add.png" ;
	
	$HD_Form ->FG_LIST_ADDING_BUTTON2 = true;
	$HD_Form ->FG_LIST_ADDING_BUTTON_LINK2 = "A2B_entity_card.php?form_action=ask-add&atmenu=card&stitle=Card&section=".$_SESSION["menu_section"];
	$HD_Form ->FG_LIST_ADDING_BUTTON_ALT2 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG2 = gettext("Add Customer");
	$HD_Form ->FG_LIST_ADDING_BUTTON_IMG2 = Images_Path ."/user_add.png" ;
}

$language_list = Constants::getLanguagesList();
$language_list_r = Constants::getLanguagesRevertList();
$discount_list  = Constants::getDiscount_List();
$simultaccess_list = Constants::getCardAccess_List();
$simultaccess_list_r = Constants::getCardAccess_Revert_List();
$limits_notify_list = Constants::getLimitNotify_List($A2B);

$currency_list = array();
$currency_list_r = array();
$indcur=0;

$currencies_list = get_currencies();
foreach($currencies_list as $key => $cur_value) {
	$currency_list[$key]  = array( $cur_value[1].' ('.$cur_value[2].')', $key);
	$currency_list_r[$key]  = array( $key, $cur_value[1]);
	$currency_list_key[$key][0] = $key;
}

$restriction_list = Constants::getRestrictionList();
$cardstatus_list = Constants::getCardStatus_List();
$cardstatus_list_r = Constants::getCardStatus_Revert_List();
$cardstatus_list_acronym = Constants::getCardStatus_Acronym_List();
$typepaid_list = Constants::getPaidTypeList();
$typepaid_list_r = Constants::getPaidTypeList_Revert_List();
$expire_list = Constants::getCardExpire_List();
$yesno = Constants::getYesNoList();
$invoiceday_list = Constants::getInvoiceDay_List();


// Search for the field to display on the show list
$show_list = array();
$show_list_sizes = array();
$show_list_temp = array();

$show_list_temp = preg_split('/,/', $A2B->config["webui"]['card_show_field_list']);
foreach ($show_list_temp as $i => $value) {
	$a = strtolower(trim($value));
	$pos = strpos($a, ':');
	if (!($pos === false)) {
		$temp_ar = preg_split('/:/', $a);
		$b = $temp_ar[1];
		$a = strtolower(trim($temp_ar[0]));	
		if (strlen($a)>1) {
		$show_list_sizes[$a] = $b;
		}
	}
	if (strlen($a)>1) {
		$show_list[$a] = $a;
	}
}
$show_fields = array();
$show_fields['id'] = array(gettext("ID"), "id", "3%", "center", "sort","", "", "", "", "", "", "");

$show_fields['username'] = array(gettext("Account Number"), "username", "14%", "center", "sort", "", "30", "", "", "", "", "linktocustomer");
if (!($popup_select>=1)) 
 $show_fields['useralias'] = array("<acronym title=\"WEB UI LOGIN\">".gettext("LOGIN")."</acronym>", "useralias", "12%", "center", "sort","", "", "", "", "", "", "");

$show_fields['lastname'] = array(gettext("LASTNAME"), "lastname", "7%", "center", "sort", "20","", "", "", "", "", "");
$show_fields['id_group'] = array(gettext("GROUP"), "id_group", "6%", "center", "sort", "20", "lie", "cc_card_group", "name", "id='%id'", "%1","");
$show_fields['credit'] = array("<acronym title=\"".gettext("BALANCE")."\">".gettext("BA")."</acronym>", "credit", "5%", "center", "sort", "", "", "", "", "", "", "display_2bill");

if (!($popup_select>=1)) {
	$serial_pad_length = $A2B->config["webui"]['card_serial_length'];
	$show_fields['id_seria'] = array(gettext("SERIE"), "id_seria", "*", "center", "sort", "15", "lie", "cc_card_seria", "name","id='%id'", "%1","");
	$show_fields['serial'] = array("<acronym title=\"".gettext("SERIAL NUMBER")."\">SERIAL#</acronym>", "serial", "*","center", "sort","","eval",'str_pad("%C",'.$serial_pad_length.' , "0", STR_PAD_LEFT)');
	$show_fields['tariff'] = array("<acronym title=\"".gettext("CALL PLAN")."\">".gettext('PLAN')."</acronym>",
	       "tariff", "8%", "center", "sort", "20", "lie", "cc_tariffgroup", "tariffgroupname", "id='%id'", "%1");
	$show_fields['status'] = array(gettext("STATUS"), "status", "4%", "center", "sort", "", "list", $cardstatus_list_acronym);
	$show_fields['language']=array(gettext("LG"), "language", "3%", "center", "sort");
	$show_fields['inuse'] = array(gettext("USE"), "inuse", "3%", "center", "sort");
	$show_fields['currency'] = array("<acronym title=\"".gettext("CURRENCY")."\">".gettext("CUR")."</acronym>", "currency", "4%", "center", "sort", "", "list", $currency_list_key);
	$show_fields['sip_buddy'] = array(gettext("SIP"), "sip_buddy", "2%", "center", "sort", "", "list", $yesno);
	$show_fields['iax_buddy'] = array(gettext("IAX"), "iax_buddy", "2%", "center", "sort", "", "list", $yesno);
	$show_fields['nbused'] = array("<acronym title=\"AMOUNT OF CALL DONE\">".gettext("ACD")."</acronym>", "nbused", "3%", "center", "sort");
	$show_fields['firstname'] = array(gettext("FIRSTNAME"), "firstname", "7%", "center", "sort", "15");
	$show_fields['email'] = array(gettext("EMAIL"), "email","","center", "sort", "15");
	$show_fields['discount'] = array("<acronym title=\"DISCOUNT\">".gettext("DISC")."</acronym>", "discount","","center", "sort", "15","30", "", "", "", "", "display_percentage");
	$show_fields['callerid'] = array("<acronym title=\"CALLER IDENTIFICATION NUMBER\">".gettext("CID")."</acronym>", "callerid","","center", "sort", "15");
	$FG_COL_QUERY = 'DISTINCT ';
}else{
	$show_list_temp = array('id', 'username', 'lastname', 'id_group',  'credit');
	foreach ($show_list_temp as $i => $value) {
                $show_list[$value] = $value;
	}
	
	$FG_COL_QUERY = 'DISTINCT ';
}
$FG_COL_QUERY_FIELD = '';
$index = 0;
foreach ($show_list as $i => $value) {
	if (array_key_exists($i,$show_fields)) {
		$param = $show_fields[$i];
		$param = $param + array("","","","","","");#expand array to max length
		$size = "*";
		$param7 = $param[7];
		if ($param[6]=="eval"){
			$param7 = str_replace('%C', '%'.$index, $param7);//because of dynamic nature need set current field for eval
		};
		if (array_key_exists($i,$show_list_sizes)) {
			if ((strlen(trim($show_list_sizes[$i])==0))&&(strlen(trim($param[2])>0))){
				$size = $param[2];
			}else{
				$size = $show_list_sizes[$i];
			}
		}
		$HD_Form -> AddViewElement($param[0],$param[1],$size,$param[3],$param[4],$param[5],$param[6],$param7,$param[8],
						$param[9],$param[10],$param[11],$param[12]);
		if ($value=='callerid') {
			$FG_COL_QUERY_FIELD = $FG_COL_QUERY_FIELD .', (select GROUP_CONCAT(cid SEPARATOR \'<br>\') from cc_callerid where id_cc_card=cc_card.id) as callerid';
		} else {
			$FG_COL_QUERY_FIELD = $FG_COL_QUERY_FIELD .', '.$value;
		}
		$index = $index + 1;
	}
}
if (strlen($FG_COL_QUERY_FIELD)>2) {
	$FG_COL_QUERY_FIELD = substr( $FG_COL_QUERY_FIELD,2);
}
$FG_COL_QUERY_COUNT = count(preg_split('/,/',$FG_COL_QUERY_FIELD));
$FG_COL_QUERY_FIELD = $FG_COL_QUERY_FIELD.', id as id_,username as username_,useralias as alias_ ';
$HD_Form -> FieldViewElement ($FG_COL_QUERY.' '.$FG_COL_QUERY_FIELD);


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


if (($popup_select>=1))	$HD_Form ->FG_LIMITE_DISPLAY = 7;


$HD_Form -> CV_FOLLOWPARAMETERS= "&popup_select=".$popup_select."&popup_formname=".$popup_formname."&popup_fieldname=".$popup_fieldname;

// Search form
if (!($popup_select>=1))	 $HD_Form -> FG_FILTER_SEARCH_FORM = true;

$HD_Form -> FG_FILTER_SEARCH_TOP_TEXT = gettext('Define specific criteria to search for cards created.');
$HD_Form -> FG_FILTER_SEARCH_1_TIME = true;
$HD_Form -> FG_FILTER_SEARCH_1_TIME_TEXT = gettext('CREATION DATE');

$HD_Form -> FG_FILTER_SEARCH_1_TIME_BIS = true;
$HD_Form -> FG_FILTER_SEARCH_1_TIME_TEXT_BIS = gettext('FIRST USE DATE');
$HD_Form -> FG_FILTER_SEARCH_1_TIME_FIELD_BIS = 'firstusedate';


$HD_Form -> AddSearchElement_C1(gettext("ACCOUNT NUMBER"), 'username','usernametype');
$HD_Form -> AddSearchElement_C1(gettext("EMAIL"), 'email','emailtype');
$HD_Form -> AddSearchElement_C1(gettext("LASTNAME"),'lastname','lastnametype');
$HD_Form -> AddSearchElement_C1(gettext("LOGIN"),'useralias','useraliastype');
$HD_Form -> AddSearchElement_C1(gettext("MACADDRESS"),'mac_addr','macaddresstype');
$HD_Form -> AddSearchElement_C2(gettext("CUSTOMER ID"),'id1','id1type','id2','id2type','id');
$HD_Form -> AddSearchElement_C2(gettext("SERIAL"),'serial1','serial1type','serial2','serial2type','serial');
$HD_Form -> AddSearchElement_C2(gettext("CREDIT"),'credit1','credit1type','credit2','credit2type','credit');
$HD_Form -> AddSearchElement_C2(gettext("INUSE"),'inuse1','inuse1type','inuse2','inuse2type','inuse');

$HD_Form -> FG_FILTER_SEARCH_FORM_SELECT_TEXT = '';
$HD_Form -> AddSearchElement_Select(gettext("SELECT SERIE"),"cc_card_seria","id, name", "", "name", "ASC", "id_seria");
$HD_Form -> AddSearchElement_Select(gettext("SELECT LANGUAGE"), null, null, null, null, null, "language", 0, $language_list_r);
$HD_Form -> AddSearchElement_Select(gettext("SELECT TARIFF"), "cc_tariffgroup", "id, tariffgroupname", "", "tariffgroupname", "ASC", "tariff");
$HD_Form -> AddSearchElement_Select(gettext("SELECT STATUS"), null, null, null, null,null , "status", 0, $cardstatus_list_r);
$HD_Form -> AddSearchElement_Select(gettext("SELECT ACCESS"), null, null, null, null, null, "simultaccess", 0, $simultaccess_list_r);
$HD_Form -> AddSearchElement_Select(gettext("SELECT GROUP"), "cc_card_group", "id, name", "", "name", "ASC", "id_group");
$HD_Form -> AddSearchElement_Select(gettext("SELECT CURRENCY"), null, null, null, null, null, "currency", 0, $currency_list_r);
$HD_Form -> AddSearchElement_Select(gettext("SELECT LANGUAGE"), null, null, null, null, null, "language", 0, $language_list_r);
$HD_Form -> AddSearchElement_Select(gettext("SELECT PAYMENT TYPE"), null, null, null, null, null, "typepaid", 0, $typepaid_list_r);

if (($popup_select>=1)) {
	$HD_Form -> FG_OTHER_BUTTON1 = true;
	$HD_Form -> FG_OTHER_BUTTON1_ALT = '<font color="red">&lt;select&gt;</font>';
	$HD_Form -> FG_OTHER_BUTTON1_IMG = '';

	if ($popup_select==1) {
		$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:sendValue('|param|', '|col1|');";
	} elseif ($popup_select==2) {
		$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:sendValue('|col1|');";
	}

} else {
	$HD_Form -> FG_INFO = true;
	$HD_Form -> FG_OTHER_BUTTON1 = true;
	$HD_Form -> FG_OTHER_BUTTON2 = true;
	$HD_Form -> FG_OTHER_BUTTON3 = true;
	$HD_Form -> FG_OTHER_BUTTON4 = true;
	$HD_Form -> FG_OTHER_BUTTON5 = true;
	$FN_COLUMN_ID="col".($FG_COL_QUERY_COUNT);//get numbers of rows for needed fields
	$FN_COLUMN_USERNAME="col".($FG_COL_QUERY_COUNT+1);
	$FN_COLUMN_ALIAS="col".($FG_COL_QUERY_COUNT+2);
	$HD_Form -> FG_OTHER_BUTTON1_LINK="A2B_entity_friend.php?atmenu=sip&form_action=add_sip&id_cc_card=|$FN_COLUMN_ID|&cardnumber=|$FN_COLUMN_USERNAME|&useralias=|$FN_COLUMN_ALIAS|";
	$HD_Form -> FG_OTHER_BUTTON2_LINK="A2B_entity_friend.php?atmenu=iax&form_action=add_iax&id_cc_card=|$FN_COLUMN_ID|&cardnumber=|$FN_COLUMN_USERNAME|&useralias=|$FN_COLUMN_ALIAS|";
	$HD_Form -> FG_OTHER_BUTTON3_LINK="A2B_entity_payment.php?form_action=ask-add&card_id=|$FN_COLUMN_ID|";
	$HD_Form -> FG_OTHER_BUTTON4_LINK="A2B_entity_subscriber.php?form_action=ask-add&id=|$FN_COLUMN_ID|";
	$HD_Form -> FG_OTHER_BUTTON5_LINK="A2B_mass_mail.php?form_action=ask-add&id=|$FN_COLUMN_ID|";
	$HD_Form -> FG_INFO_LINK = "A2B_card_info.php?id=";
	//FOR INFO , DEL, EDIT  the image is already in Image_Path
	$HD_Form -> FG_OTHER_BUTTON1_IMG = Images_Path . "/icon-sip.gif";
	$HD_Form -> FG_OTHER_BUTTON2_IMG = Images_Path . "/icon-iax.gif";
	$HD_Form -> FG_OTHER_BUTTON3_IMG = Images_Path . "/money.png";
	$HD_Form -> FG_OTHER_BUTTON4_IMG = Images_Path . "/book_add.png";
	$HD_Form -> FG_OTHER_BUTTON5_IMG = Images_Path . "/email.png";
	$HD_Form -> FG_OTHER_BUTTON1_ALT=gettext("Auto Create SIP Config");
	$HD_Form -> FG_OTHER_BUTTON2_ALT=gettext("Auto Create IAX Config");
	$HD_Form -> FG_OTHER_BUTTON3_ALT = gettext('Add Payment to this Card');
	$HD_Form -> FG_OTHER_BUTTON4_ALT = gettext('Add New Subscription to this Card');
	$HD_Form -> FG_OTHER_BUTTON5_ALT = gettext('Email to this Customer');
}

$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'cardnumber';
$HD_Form -> FG_FILTERFIELDNAME = gettext('Account Number');
$HD_Form -> FG_FILTER_FORM_ACTION = 'list';


if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="username like '$filterprefix%'";
}

$HD_Form -> FG_ADDITION = true;

if (has_rights (ACX_MODIFY_CUSTOMERS) && !($popup_select>=1)) {
	$HD_Form -> FG_EDITION = true;
	$HD_Form -> FG_DELETION = true;
	
	$maxi	= '';
	$maxi2	= '';
	$maxi3	= '';
	
	if (isset($form_action) && $form_action=="ask-add") {
		if (!is_numeric($cardnumberlenght_list)) 	$cardnumberlenght_list = LEN_CARDNUMBER;
		$array_card_generated  = gen_card_with_alias("cc_card", 0, $cardnumberlenght_list);
		$maxi = "value='".$array_card_generated[0]."'";
		$maxi2 = "value='".$array_card_generated[1]."'";
		$pass = MDP_NUMERIC(10);
		$maxi3 = "value='".$pass."'";
		$default_initbalance = "value='0'";
	}
	
	$HD_Form -> AddEditElement (gettext("ACCOUNT NUMBER"),
				   "username",
				   "",
				   "INPUT",
				    "size=30 $maxi readonly  maxlength=40",
				   "4",
				   gettext("Insert the account number"),
				   "" , "", "",	"", "" , "", "", '', null, null, gettext("Customer Information"));
				   
	
	$HD_Form -> AddEditElement (gettext("WEBUI LOGIN"),
				   "useralias",
				   "",
				   "INPUT",
				    "size=20 $maxi2 maxlength=40",
				   "4",
				   gettext("Insert the webui login"),
				   "" , "", "",	"", "" , "", "", "");
	
	
	$HD_Form -> AddEditElement (gettext("WEBUI PASSWORD"),
				   "uipass",
				   '',
				   "INPUT",
				   "size=20 $maxi3 maxlength=20",
				   "",
				   gettext("Insert the password"),
				   "" , "", "",	"", "", "",	"", gettext("Password for customer to access to the web interface and view the balance.") );
	
	if ($form_action =="ask-add" || $form_action=="add") {
		$balance_access = "value = '0'";
		$payment_status = "";
		
	} else {
		$balance_access = 'readonly';
		$payment_status= 'disabled="true" style="color: gray;" ';
	}
	
	$HD_Form -> AddEditElement (gettext("BALANCE"),
				   "credit",
				   "",
				   "INPUT",
				   "size=30 maxlength=30 ".$balance_access,
				   "12",
				   gettext("Insert the number of credit"),
				   "" , "", "", "", "", "", "", gettext("currency : ").strtoupper(BASE_CURRENCY) );
	
				   
	$HD_Form -> AddEditElement (gettext("CUSTOMER GROUP"),
				   "id_group",
				   "",
				   "SELECT",
				   "", "", "",
				   "sql",
				   "cc_card_group",
				   "name, id",
				   "", "", "%1", "", "", "", "", "");
			   
	$HD_Form -> AddEditElement (gettext("SERIAL"),
	               "id_seria",
	               "",
	               "SELECT",
	               "", "", "",
	               "sql",
	               "cc_card_seria",
	               "name, id",
	               "", "", "%1", "", "", "", '<OPTION  value="-1" selected>'.gettext("NOT DEFINED").'</OPTION>' );
	
	
	$HD_Form -> AddEditElement (gettext("LAST NAME"),
				   "lastname",
				   '$value',
				   "INPUT",
				   "size=30 maxlength=50",
				   "11",
				   gettext("Insert the lastname of the customers"),
				   "" , "", "",	"", "", "" , "NO", "", null, null, gettext("Personal Information"));
	
	$HD_Form -> AddEditElement (gettext("FIRST NAME"),
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
	
	if ($form_action == 'ask-add') {
		$country_default_val = 'USA';
	}
	
	$HD_Form -> AddEditElement (gettext("COUNTRY"),
				   "country",
				   "$country_default_val",
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
	
	$HD_Form -> AddEditElement (gettext("COMPANY NAME"),
				   "company_name",
				   '$value',
				   "INPUT",
				   "size=40 maxlength=50",
				   "",
				   gettext("Insert Company name of this customer"),
				   "" , "", "",	"", "", "" , "NO", "");
				   
				   
	$HD_Form -> AddEditElement (gettext("COMPANY WEBSITE"),
				   "company_website",
				   '$value',
				   "INPUT",
				   "size=40 maxlength=60",
				   "",
				   gettext("Insert the company website of this customer"),
				   "" , "", "",	"", "", "" , "NO", "");
	
	if ($form_action != "edit") {
		
		$update_field_typepaid = 'typepaid ,';
	
		$HD_Form -> AddEditElement (gettext("PAYMENT TYPE"),
					   "typepaid",
					   "",
					   "SELECT",
					   "$payment_status",
					   "",
					   "",
					   "list" , "", "",	"", $typepaid_list, "%1", "", '', null, null, gettext("Customer Status") );
	}
	
	$HD_Form -> AddEditElement (gettext("CALL PLAN"),
				   "tariff",
				   "",
				   "SELECT",
				   "", "", "",
				   "sql",
				   "cc_tariffgroup",
				   "tariffgroupname, id",
				   "", "", "%1", "", gettext("Changing the call plan will result in the free minutes or free calls package being reset.") );
	
	
	$HD_Form -> AddEditElement (gettext("DIDGROUP"),
				   "id_didgroup",
				   "",
				   "SELECT",
				   "", "", "",
				   "sql",
				   "cc_didgroup",
				   "didgroupname, id",
				   "", "", "%1", "", "", "", '<OPTION  value="-1" selected>'.gettext("NOT DEFINED").'</OPTION>' );
	
	$timezone_list = get_timezones();
	$one_select = false;
	foreach ($timezone_list as $key => $cur_value) {
		$timezone_list[$key] = array($cur_value[2], $key);
		if ($form_action == 'ask-add') {
			if (in_array(SERVER_GMT,$cur_value) && !$one_select) {
				$timezone_default_val = $key;
				$one_select = true;
			}
		}
	}
	
	$HD_Form -> AddEditElement (gettext("TIMEZONE"),
				   "id_timezone",
				   "$timezone_default_val",
				   "SELECT",
				   "","","",
				   "list" , "", "",	"", $timezone_list, "%1", "", "" );
	
	
	$HD_Form -> AddEditElement (gettext("LANGUAGE"),
				   "language",
				   "",
				   "SELECT",
				   "",
				   "",
				   "",
				   "list" , "", "",	"", $language_list, "%1", "", "" );
	
	
	$HD_Form -> AddEditElement (gettext("CURRENCY"),
				   "currency",
				   "",
				   "SELECT",
				   "",
				   "", "", "list" , "", "",	"", $currency_list, "%1", "", gettext("Currency used at the customer end.") );
	
	
	
	$HD_Form -> AddEditElement (gettext("STATUS"),
				   "status",
				   "",
				   "SELECT",
				   "",
				   "",
				   "",
				   "list" , "", "",	"", $cardstatus_list, "%1", "", "" );

	$HD_Form -> AddEditElement (gettext("LOCK"),
				   "block",
				   '0',
				   "RADIOBUTTON",
				   "",
				   "",
				   gettext("Choose if you want to enable the lock on this account"),
				   "" , "", "", "Yes :1, - No:0", "", "" , "", gettext("Enable lock for this account."));

	if ($form_action =="ask-add" || $form_action=="add") {
		$lockpin_default = "value = '0'";
	}

	$HD_Form -> AddEditElement (gettext("LOCK PIN"),
				   "lock_pin",
				   '$value',
				   "INPUT",
				   "size=20 maxlength=10 $lockpin_default",
				   "4",
				   "",
				   "" , "", "",	"", "", "", "NO", gettext("Code required to make the call if the lock is active."));


	$HD_Form -> AddEditElement (gettext("SIMULTANEOUS ACCESS"),
				   "simultaccess",
				   "1",
				   "SELECT",
				   "",
				   "",
				   "",
				   "list" , "", "",	"", $simultaccess_list, "%1", "", "" );
	
	
	$HD_Form -> AddEditElement (gettext("RUN SERVICE"),
				   "runservice",
				   '0',
				   "RADIOBUTTON",
				   "",
				   "",
				   gettext("Choose if you want to enable the service recurring on this account"),
				   "" , "", "", "Yes :1, - No:0", "", "" , "", gettext("Apply recurring service to this account."));
	
	
	$HD_Form -> AddEditElement (gettext("CREDIT LIMIT"),
				   "creditlimit",
				   "",
				   "INPUT",
				   "size=20 maxlength=20",
				   "4",
				   gettext("Insert the credit limit"),
				   "" , "", "",	"", "", "", "NO-NULL", gettext("Credit limit is only used for the postpaid account."));
	
	
	
	$HD_Form -> AddEditElement (gettext("CREDIT LIMIT NOTIFICATION"),
				   "credit_notification",
				   '',
				   "SELECT",
				   "",
				   "",
				   "",
				   "list" , "", "", "", $limits_notify_list , "%1", "", 
				   gettext("currency : ").strtoupper(BASE_CURRENCY).'<BR>'.gettext("Low credit limit to alert the customer"), 
				   "", '<OPTION  value="-1" selected>'.gettext("NOT DEFINED").'</OPTION>');
	
	
	$HD_Form -> AddEditElement (gettext("PERMITTED NOTIFICATIONS BY MAIL"),
				   "notify_email",
				   '0',
				   "RADIOBUTTON",
				   "",
				   "",
				   gettext("Choose if you want to enable the notification by email for this account"),
				   "" , "", "", "Yes :1, - No:0", "", "" , "", gettext("Enable the notification by mail for this account."));
	
	
	$HD_Form -> AddEditElement (gettext("EMAIL NOTIFICATION"),
				   "email_notification",
				   '$value',
				   "INPUT",
				   "size=30 maxlength=70",
				   "1",
				   gettext("Insert the email to notify this customer"),
				   "" , "", "",	"", "", "" , "NO", "");
	
	if (ACT_VOICEMAIL) { 
		$HD_Form -> AddEditElement (gettext("PERMITTED VOICEMAIL"),
					   "voicemail_permitted",
					   '1',
					   "RADIOBUTTON",
					   "",
					   "",
					   gettext("Choose if you want to enable the voicemail functionality for this account"),
					   "" , "", "", "Yes :1, - No:0", "", "" , "", gettext("Enable the voicemail functionality for this account."));
		
		$HD_Form -> AddEditElement (gettext("ACTIVE VOICEMAIL SERVICE"),
					   "voicemail_activated",
					   '0',
					   "RADIOBUTTON",
					   "",
					   "",
					   gettext("Customer can choose to enable the voicemail for this account"),
					   "" , "", "", "Yes :1, - No:0", "", "" , "", gettext("Customer can enable the voicemail for this account."));
	}
	
	$HD_Form -> AddEditElement (gettext("CAMPAIGN"),
				   "id_campaign",
				   "",
				   "SELECT",
				   "", "", "",
				   "sql",
				   "cc_campaign",
				   "name, id",
				   "", "", "%1", "", "", "", '<OPTION  value="-1" selected>'.gettext("NOT DEFINED").'</OPTION>' );
	
	
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
				   "list" , "", "",	"", $expire_list, "%1", "", gettext("Select method of expiry for the account.") );
	
	
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
				   "" , "", "",	"", "", "", "NO-NULL", gettext("please use the format YYYY-MM-DD HH:MM:SS. For instance, '2004-12-31 00:00:00'"), "", "res_display_dateformat");
	
	
	
	$HD_Form -> AddEditElement (gettext("EXPIRY DAYS"),
				   "expiredays",
				   "",
				   "INPUT",
				    "size=10 maxlength=6 $comp_zero",
				   "4",
				   gettext("Insert the number of days after which the account will expire"),
				   "" , "", "",	"", "", "", "", gettext("The number of days after which the account will expire."));
	
	
	$HD_Form -> AddEditElement (gettext("CREATE SIP CONFIG"),
				   "sip_buddy",
				   '1',
				   "RADIOBUTTON",
				   "",
				   "",
				   gettext("Choose if you want to enable the sip account"),
				   "" , "", "" , "Yes :1, - No:0", "", "" , "", gettext("Create the SIP config automatically"));
	
	
	$HD_Form -> AddEditElement (gettext("CREATE IAX CONFIG"),
				   "iax_buddy",
				   '1',
				   "RADIOBUTTON",
				   "",
				   "",
				   gettext("Choose if you want to enable the iax account"),
				   "" , "", "" , "Yes :1, - No:0",  "", "" , "", gettext("Create the IAX config automatically"));
	
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
				   "INPUT",
				   "size=5 maxlength=5",
				   "",
				   "",
				   "" , "", "",	"", "", "", "NO", gettext("Updated to show the number of concurrent calls in use by this customer. If there are no currently no calls, and the system shows that there are, manually reset this field back to zero.") );
	
	$HD_Form -> AddEditElement (gettext("AUTOREFILL"),
				   "autorefill",
				   '0',
				   "RADIOBUTTON",
				   "",
				   "",
				   gettext("Choose if you want to enable the autorefill on this account"),
				   "" , "", "", "Yes :1, - No:0", "", "" , "", gettext("Define if you want to authorize the autorefill to apply on this accout"),
				   '', '', gettext("AUTOREFILL"));
	
	$HD_Form -> AddEditElement (gettext("INITIAL BALANCE"),
				   "initialbalance",
				   "",
				   "INPUT",
				   "size=30 $default_initbalance maxlength=30",
				   "12",
				   gettext("Insert the amount of the initial balance"),
				   "" , "", "",	"", "", "",	"", gettext("The initial balance is used by autorefill to reset the current balance to this amount"));
	
	
	
	$HD_Form -> AddEditElement (gettext("INVOICE DAY"),
				   "invoiceday",
				   '$value',
				   "SELECT",
				   "",
				   "",
				   "",
				   "list" , "", "",	"", $invoiceday_list, "%1", "", gettext("Define the day of the month when the system will generate the customer invoice."), 
				   '', '', gettext("Invoice Status"));
	
	
	if ($form_action =="ask-add" || $form_action=="add") {
		$vat_init = "value = '0'";
		
	}
	
	$HD_Form -> AddEditElement (gettext("VAT"),
				   "vat",
				   "",
				   "INPUT",
				   "size=10 maxlength=6 $vat_init",
				   "12",
				   gettext("Insert the VAT"),
				   "" , "", "",	"", "", "", "" , gettext("VAT to add on the invoice of this customer. it should be a decimal value '21' this will be for 21% of VAT!"));
	
	
	$HD_Form -> AddEditElement (gettext("VAT REGISTRATION NUMBER "),
				   "vat_rn",
				   '$value',
				   "INPUT",
				   "size=40 maxlength=40",
				   "",
				   gettext("Insert the VAT registration number of this customer"),
				   "" , "", "",	"", "", "" , "NO", "");			   
	
	
	$HD_Form -> AddEditElement (gettext("DISCOUNT"),
	               "discount",
	               "",
	               "SELECT",
	               "",
	               "",
	               "",
	               "list" , "", "",     "", $discount_list, "%1", "", ""); 
					
	
	$HD_Form -> AddEditElement (gettext("TRAFFIC PER MONTH"),
				   "traffic",
				   '$value',
				   "INPUT",
				   "size=30 maxlength=20",
				   "4",
				   gettext("Insert the traffic per month for this customer"),
				   "" , "", "",	"", "", "" , "NO", "",
				   '', '', gettext("TARGET TRAFFIC")); 
	
	$HD_Form -> AddEditElement (gettext("TARGET TRAFFIC"),
					"traffic_target",
					'',
					"TEXTAREA",
					"cols=50 rows=4",
					"",
					gettext("Insert the target traffic description"),
					"" , "", "", "", "" , "", "", "");
	
					
	$HD_Form -> AddEditElement (gettext("RESTRICTION"),
				   "restriction",
				   "0",
				   "SELECT",
				   "",
				   "",
				   "",
				   "list" , "", "",	"", $restriction_list, "%1", "", "",
				   '', '', gettext("RESTRICTED NUMBERS")); 
					
	$HD_Form -> AddEditElement (gettext("RESTRICTED NUMBERS"),
				   "restrictednumber",
				   '$value',
				   "HAS_MANY",
				   "",
				   "",
				   gettext("Insert numbers that restrict the customer"),
				   "",
				   "",
				   "",
				   "", "", "", "", "", "cc_restricted_phonenumber:number:number, id_card:id_card = %id::id_card:::::::0");
	
	
				   
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
				   "", "", "", "", "", "cc_callerid:cid:cid, cid, id_cc_card:id_cc_card = %id::id_cc_card:::::::0",
				   '', gettext("CALLERID / CUSTOMER HISTORY"));
					
				   
	
	
	
	$HD_Form -> AddEditElement (gettext("CUSTOMER HISTORY"),
				   "description",
				   '$value',
				   "HAS_MANY",
				   "multiline",
				   "",
				   "",
				   "",
				   "",
				   "",
				   "", "", "", "", "", "cc_card_history:description:description,id_cc_card,datecreated:id_cc_card = %id::id_cc_card::2:::::0");
	
				   // cc_card_history:description:description,id_cc_card:id_cc_card = %id::id_cc_card
				   // Above function will provid Has Many functionality
				   // Each parameter is seperated by (:) notation
				   // 0. HAS_MANY will allow to get one to many functionality
				   // 1. First param is the table name
				   // 2. Second param is the join condition coloumn
				   // 3. Third will get all the columns (but first should be joining column)
				   // 4. Fourth param generates the where cluase
				   // 5. Last, Sixth param is the FK column
	
	$add_fields_voicemail = '';
	if (ACT_VOICEMAIL)
		$add_fields_voicemail = 'voicemail_permitted, voicemail_activated, ';
	
	$HD_Form -> FieldEditElement ('username, useralias, uipass, credit, id_group, id_seria, lastname, firstname, email, '.
			' address, city, state, country, zipcode, phone, fax,company_name, company_website, '.
			$update_field_typepaid.' tariff, id_didgroup, id_timezone, language, currency, status, block, lock_pin, simultaccess,  runservice, creditlimit, ' .
			' credit_notification, notify_email, email_notification, ' .$add_fields_voicemail.
			' id_campaign, ' .
			' firstusedate, enableexpire, expirationdate, expiredays, sip_buddy, iax_buddy, mac_addr, inuse, ' .
			' autorefill, initialbalance, invoiceday, vat, vat_rn, discount, traffic, traffic_target, restriction' );
	
	
	if(!isset($myusername)) 
		$myusername='';
	
	if ($form_action == "ask-edit") {
		$table_card = new Table("cc_card","status");
		$result_card = $table_card ->Get_list(DbConnect(),"id=$id");
		$HD_Form -> FG_EDITION_HIDDEN_PARAM = "oldstatus";
		$HD_Form -> FG_EDITION_HIDDEN_PARAM_VALUE = "".$result_card[0][0];
	}
	
	
	$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
	$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
	$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");
	
	
	$HD_Form -> FG_INTRO_TEXT_ADITION = '';
	$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";
	
	
	$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
	$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Once you have completed the form above, click on Confirm Data");
	
	$HD_Form -> FG_ADDITIONAL_FUNCTION_BEFORE_EDITION = 'change_card_lock';
	
	$HD_Form -> FG_ADDITIONAL_FUNCTION_AFTER_ADD = 'processing_card_add';
	$HD_Form -> FG_ADDITIONAL_FUNCTION_AFTER_EDITION = 'create_status_log';
	
	if (isset($form_action) && $form_action=='delete' && USE_REALTIME) {
		$card_table = new Table('cc_card','sip_buddy,iax_buddy');
		$card_clause = "id = ".$id;
		$card_result = $card_table -> Get_list(DbConnect(), $card_clause, 0);
		$sip_buddy = $card_result[0]['sip_buddy'];
		$iax_buddy = $card_result[0]['iax_buddy'];
		if(($sip_buddy == 1) && ($iax_buddy == 1)) 
			$key = "sip_iax_changed";
		elseif (($sip_buddy == 1) ) 
			$key = "sip_changed";
		elseif ($iax_buddy == 1) 
			$key = "iax_changed";
		
		if ($_SESSION["user_type"]=="ADMIN") {
				$who= Notification::$ADMIN;$who_id=$_SESSION['admin_id'];
		} elseif ($_SESSION["user_type"]=="AGENT") {
			$who= Notification::$AGENT;$who_id=$_SESSION['agent_id'];
		} else { 
			$who=Notification::$UNKNOWN;$who_id=-1;
		}
		NotificationsDAO::AddNotification($key,Notification::$HIGH,$who,$who_id);
	}
	
	$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?section=".$_SESSION["menu_section"]."&id=";
	$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?section=".$_SESSION["menu_section"]."&id=";
	$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?section=".$_SESSION["menu_section"]."&id=";
}


