<?php
getpost_ifset(array('id', 'id_cc_card', 'id_subscription_fee', 'datestart', 'datestop', 'product_name'));

$HD_Form = new FormHandler("cc_card_subscription","Subscriber");

$HD_Form -> FG_DEBUG = 0;

$HD_Form -> FG_TABLE_DEFAULT_ORDER = "id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";


$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_subscriber.php?form_action=ask-add&section=7";
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add SUBSCRIBER");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/text_signature.png" ;


$HD_Form -> AddViewElement(gettext("CARDNUMBER"), "id_cc_card", "15%", "center", "sort", "15", "lie", "cc_card", "username", "id='%id'", "%1");
$HD_Form -> AddViewElement(gettext("SERVICE"), "id_subscription_fee", "20%", "center", "sort", "15", "lie", "cc_subscription_fee", "label", "id='%id'", "%1");
$HD_Form -> AddViewElement(gettext("DATESTART"), "startdate", "15%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DATESTOP"), "stopdate", "15%", "center", "sort");
$HD_Form -> AddViewElement(gettext("PRODUCT"), "product_name", "20%", "center", "sort", "25");
$HD_Form -> FG_ACTION_SIZE_COLUMN = '15%';

// added a parameter to append  FG_TABLE_ID  ( by default ) or disable 0.
$HD_Form -> FieldViewElement ('id_cc_card, id_subscription_fee, startdate, stopdate, product_name');

$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." MADE!");  
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;

$comp_zero = $comp_date_plus='';
if (isset($form_action) && $form_action=="ask-add"){
	$begin_date = date("Y");
	$begin_date_plus = date("Y")+30;
	$end_date = date("-m-d H:i:s");
	$comp_date = "value='".$begin_date.$end_date."'";
	$comp_date_plus = "value='".$begin_date_plus.$end_date."'";
	$comp_zero = "value='0'";
}

$HD_Form -> AddEditElement (gettext("ID CARD"),
				"id_cc_card",
				'',
				"POPUPVALUE",
				"size=30 maxlength=50",
				"4",
				gettext("Insert the Id CardNumber"),
				"" , "", "", "", "",
				"A2B_entity_card.php?popup_select=1&"
				, ", 'CardNumberSelection','width=550,height=350,top=20,left=100,scrollbars=1'" ,
				gettext("Set the card number ID."));
		
$HD_Form -> AddEditElement (gettext("ID SUBSCRIPTION"),
				"id_subscription_fee",
				"",
				"SELECT",
				"", "", "",
				"sql",
				"cc_subscription_fee",
				"label, id",
				"", "", "%1", "", "", "", '<OPTION  value="-1" selected>'.gettext("NOT DEFINED").'</OPTION>' );

$HD_Form -> AddEditElement (gettext("START DATE"),
				"startdate",
				'$value',
				"INPUT",
				"size=40 maxlength=40  $comp_date",
				"10",
				gettext("Insert the start date"),
				"" , "", "",	"", "", "", "NO-NULL", gettext("please respect the format YYYY-MM-DD HH:MM:SS. For instance, '2004-12-31 00:00:00'"), "", "res_display_dateformat");
	
$HD_Form -> AddEditElement (gettext("STOP DATE"),
				"stopdate",
				'$value',
				"INPUT",
				"size=40 maxlength=40  $comp_date_plus",
				"10",
				gettext("Insert the stop date"),
				"" , "", "",	"", "", "", "NO-NULL", gettext("please respect the format YYYY-MM-DD HH:MM:SS. For instance, '2004-12-31 00:00:00'"), "", "res_display_dateformat");
		
$HD_Form -> AddEditElement (gettext("PRODUCT NAME"),
				"product_name",		
				'',	
				"TEXTAREA",	
				"cols=50 rows=4",	
				"",	
				gettext("Enter the name of the product wich is subscribed"),
				"" , "", "",	"", "" , "", "", "");

$HD_Form -> FieldEditElement ('id_cc_card, id_subscription_fee, startdate, stopdate, product_name');

if (DB_TYPE == "postgres"){
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS = "";
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  = "";
}else{
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS = "";//"datecreate ";
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  = "";//"now()";
}

$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add a suscriber below");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");


$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";

?>
