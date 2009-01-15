<?php

getpost_ifset(array('id', 'payment_method', 'payment_filename'));


$HD_Form = new FormHandler("cc_payment_methods","payment method");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "payment_method";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";


$HD_Form -> AddViewElement(gettext("Payment Method"), "payment_method", "25%", "center", "sort");
$HD_Form -> AddViewElement(gettext("File Name"), "payment_filename", "20%", "center", "sort");


// added a parameter to append  FG_TABLE_ID  ( by default ) or disable 0.
//$HD_Form -> FieldViewElement ('payment_method, payment_filename, active');
$HD_Form -> FieldViewElement ('payment_method, payment_filename');



$HD_Form -> FG_OTHER_BUTTON1_LINK = "A2B_entity_payment_settings.php?method=authorizenet&id=";
$HD_Form -> FG_OTHER_BUTTON1_IMG = Images_Path."/icon-viewdetails.gif";

$HD_Form -> CV_NO_FIELDS  = gettext("THERE ARE NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;
$HD_Form -> FG_OTHER_BUTTON1 = true;

// TODO integrate in Framework
if ($form_action=="ask-add"){
	$begin_date = date("Y");
	$begin_date_plus = date("Y")+25;
	$end_date = date("-m-d H:i:s");
	$comp_date = "value='".$begin_date.$end_date."'";
	$comp_date_plus = "value='".$begin_date_plus.$end_date."'";
}

$did_regexpress = '9';
if ($form_action=="ask-edit" || $form_action=="edit"){
	$did_regexpress = '13';
}

$HD_Form -> AddEditElement (gettext("Payment Method"),
	"payment_method",
	'$value',
	"INPUT",
	"size=30 maxlength=100",
	"",
	gettext("Insert the payment method name"),
	"" , "", "", "", "" , "", "" ,"Insert Payment Method");

$HD_Form -> AddEditElement (gettext("File Name"),
	"payment_filename",
	"$value",
	"INPUT",
	"size=30 maxlength=100",
	"",
	gettext("Insert the payment method file name"),
	"" , "", "",  "", "", "" , "", "Insert Payment Method File Name");

$HD_Form -> AddEditElement (gettext("ACTIVATED"),
	"active",
	'1',
	"RADIOBUTTON",
	"",
	"",
	gettext("Choose if you want to activate this method"),
	"" , "", "", "Yes :t, - No:f", "", "", "" , "" );



$HD_Form -> FieldEditElement ('payment_method, payment_filename, active');


$HD_Form -> FG_INTRO_TEXT_EDITION= '';
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this ".$HD_Form->FG_INSTANCE_NAME.", click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new ".$HD_Form->FG_INSTANCE_NAME.".<br>Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new ".$HD_Form->FG_INSTANCE_NAME." has been inserted. <br>");


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = 'Fill correctly the fields to add a new '.$HD_Form->FG_INSTANCE_NAME;


$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";


