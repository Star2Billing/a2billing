<?php

getpost_ifset(array('id', 'amount', 'id_cc_card', 'chargetype', 'description', 'id_did', 'username'));


if (($form_action=='ask-edit') || ($form_action=='ask-add') || ($form_action=='add') || ($form_action=='edit') || ($form_action=='ask-delete') || ($form_action=='delete')){
	if ($wantinclude==1) $HD_Form_c = new FormHandler("cc_charge","charge","A2B_entity_charge.php");
	else $HD_Form_c = new FormHandler("cc_charge","charge");
}else {
	if ($wantinclude==1) $HD_Form_c = new FormHandler("cc_charge, cc_card", "charge", "A2B_entity_charge.php");
	else $HD_Form_c = new FormHandler("cc_charge, cc_card", "charge");
}

$HD_Form_c -> FG_DEBUG = 0;
if ($wantinclude==1) $HD_Form_c -> FG_TABLE_DEFAULT_ORDER = $order;
else $HD_Form_c -> FG_TABLE_DEFAULT_ORDER = "id_cc_card";

$HD_Form_c -> FG_TABLE_DEFAULT_SENS = "DESC";
$HD_Form_c -> FG_TABLE_CLAUSE = " cc_card.id=id_cc_card ";
$HD_Form_c -> FG_TABLE_ID="cc_charge.id";

// -- chargetype : 1 - connection charge for DID setup, 2 - Montly charge for DID use, 3 - Subscription fee, 4 - Extra Charge, etc...
$chargetype_list = array();
$chargetype_list["1"] = array( gettext("Connection charge for DID setup"), "1");
$chargetype_list["2"] = array( gettext("Monthly Charge for DID use"), "2");
$chargetype_list["3"] = array( gettext("Subscription fee"), "3");
$chargetype_list["4"] = array( gettext("Extra charge"), "4");




$currency_list = getCurrenciesList();
$currency_list_r = getCurrenciesRateList();
$currency_list_key = getCurrenciesKeyList() ;


$HD_Form_c -> AddViewElement(gettext("CARDHOLDER"), "id_cc_card", "10%", "center", "sort", "25", "lie", "cc_card", "lastname, firstname", "id='%id'", "%1 %2");
$HD_Form_c -> AddViewElement(gettext("CARDNUMBER"), "username", "12%", "center", "sort", "", "30", "", "", "", "", "linktocustomer");
$HD_Form_c -> AddViewElement(gettext("DATE"), "cc_charge.creationdate", "10%", "center", "sort", "16", "", "", "", "", "", "display_dateformat");
$HD_Form_c -> AddViewElement(gettext("AMOUNT"), "amount", "7%", "center", "sort");
$HD_Form_c -> AddViewElement("<acronym title=\"CURRENCY\">".gettext("CUR")."</acronym>", "currency", "5%", "center", "sort", "", "list", $currency_list_key);
$HD_Form_c -> AddViewElement(gettext("CHARGETYPE"), "chargetype", "20%", "center", "sort", "30", "list", $chargetype_list);
$HD_Form_c -> AddViewElement(gettext("DESCRIPTION"), "description", "25%", "center", "SORT", "40");

// added a parameter to append  FG_TABLE_ID  ( by default ) or disable 0.
$HD_Form_c -> FieldViewElement ('id_cc_card, username, cc_charge.creationdate, amount, cc_charge.currency, chargetype, description');


$HD_Form_c -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form_c->FG_INSTANCE_NAME)." CREATED!");
$HD_Form_c -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form_c -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form_c -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form_c -> FG_EDITION = true;
$HD_Form_c -> FG_DELETION = true;



// TODO integrate in Framework
if ($form_action=='ask-add'){
	$DBHandle_max  = DbConnect();
	if ($username) {
		$resmax = $DBHandle_max -> Execute("SELECT id FROM cc_card where username='$username'");
		$numrow = 0;
		if ($resmax)
			$numrow = $resmas -> RecordCount( );

		if ($numrow!=0) {
			$db_row [] = $resmax -> fetchRow();
			$val_default = "value='".$db_row[0][0]."'";
		}
	}

	if ($id_did){
		$resmax = $DBHandle_max -> Execute("SELECT fixrate FROM cc_did where id='$id_did'");
		$numrow = 0;
		if ($resmax)
			$numrow = $resmax -> RecordCount( );

		if ($numrow!=0){
			$db_row2 [] = $resmax -> fetchRow();
			$val_default_fixrate = "value='".$db_row2[0][0]."'";
		}
	}
}


$HD_Form_c -> AddEditElement (gettext("ID CARD"),
	"id_cc_card",
	'',
	"POPUPVALUE",
	"size=30 maxlength=50 $val_default",
	"4",
	gettext("Insert the Id CardNumber"),
	"" , "", "", "", "",
	"A2B_entity_card.php?popup_select=1&"
	, ", 'CardNumberSelection','width=550,height=350,top=20,left=100,scrollbars=1'" ,
	gettext("Set the Card Number ID"));

$HD_Form_c -> AddEditElement (gettext("AMOUNT"),
	"amount",
	'$value',
	"INPUT",
	"size=20  maxlength=20 $val_default_fixrate",
	"12",
	gettext("Insert the amount"),
	"" , "", "", "", "" , "", "" ,"");

$HD_Form_c -> AddEditElement (gettext("CURRENCY"),
   "currency",
   "",
   "SELECT",
   "",
   "",
   "",
   "list" , "", "",	"", $currency_list, "%1", "", "" );

$HD_Form_c -> AddEditElement (gettext("CHARGETYPE"),
	"chargetype",
	"",
	"SELECT",
	"",
	"",
	"",
	"list" , "", "",  "", $chargetype_list, "%1" , "" , "");

$HD_Form_c -> AddEditElement (gettext("DESCRIPTION"),
	"description",
	'',
	"TEXTAREA",
	"cols=50 rows=4",
	"",
	gettext("Insert the description"),
	"" , "", "", "", "" , "", "", "");


$HD_Form_c -> FieldEditElement ('id_cc_card, amount, currency, chargetype, description');



// Set the filter variables

$HD_Form_c -> FG_FILTER_APPLY = true;
$HD_Form_c -> FG_FILTERFIELD = 'cardnumber';
$HD_Form_c -> FG_FILTERFIELDNAME = 'cardnumber';

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	if (strlen($HD_Form_c -> FG_TABLE_CLAUSE)>0) $HD_Form_c -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form_c -> FG_TABLE_CLAUSE.="username like '$filterprefix%'";
}



$HD_Form_c -> FG_INTRO_TEXT_EDITION= gettext("Modify the properties of the charge");
$HD_Form_c -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form_c->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form_c -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form_c->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form_c -> FG_INTRO_TEXT_ADITION = '';
$HD_Form_c -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form_c->FG_INSTANCE_NAME." ".gettext("has been inserted.")." <br>";


$HD_Form_c -> FG_BUTTON_EDITION_SRC = $HD_Form_c -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form_c -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form_c -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");



$HD_Form_c -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form_c -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form_c -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";

