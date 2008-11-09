<?php

getpost_ifset(array('id', 'speeddial', 'id_cc_card', 'name', 'phone','popup_select','popup_formname','popup_fieldname'));

$HD_Form = new FormHandler("cc_speeddial","speed dial");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = " creationdate";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";


$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_speeddial.php?form_action=ask-add&atmenu=speeddial&section=1";
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add Speed Dial");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/page_white_add.png" ;


$speeddial_list = array();
for ($k=0;$k<=9;$k++)
	$speeddial_list["$k"]  = array( "$k", "$k");


$HD_Form -> AddViewElement(gettext("DATE"), "creationdate", "20%", "center", "sort", "16", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("ACCOUNT NUMBER"), "id_cc_card", "22%", "center", "sort", "15", "lie", "cc_card", "username", "id='%id'", "%1");
$HD_Form -> AddViewElement(gettext("NAME"), "name", "10%", "center", "sort", "26");
$HD_Form -> AddViewElement(gettext("PHONE"), "phone", "10%", "center", "sort", "26");
$HD_Form -> AddViewElement(gettext("SPEED DIAL"), "speeddial", "20%", "center", "sort", "26");


$HD_Form -> FieldViewElement ('creationdate, id_cc_card, name, phone, speeddial');


$HD_Form -> CV_NO_FIELDS  = gettext("NO SPEED DIALS HAVE BEEN CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;

$HD_Form -> AddEditElement (gettext("SPEED DIAL"),
			   "speeddial",			
			   "",	
			   "SELECT",	
			   "",	
			   "",	
			   "", 
			   "list" , "", "",	"", $speeddial_list, "%1", "", "" );


$HD_Form -> AddEditElement (gettext("NAME"),
			   "name",		
			   '$value',	
			   "INPUT",	
			   "size=40 maxlength=50",	
			   "0",	
			   gettext("Insert the name"),
			   "" , "", "",	"", "", "",	"", gettext("Enter the name or label that will identify this speed dial") );


$HD_Form -> AddEditElement (gettext("PHONE"),
			   "phone",		
			   '$value',	
			   "INPUT",	
			   "size=30 maxlength=50",	
			   "4",	
			   gettext("Insert the phone"),
			   "" , "", "",	"", "", "",	"", gettext("Enter the phone number for the speed dial.") );


$HD_Form -> AddEditElement (gettext("ID CARD"),
			   "id_cc_card",		
			   "$value",	
			   "POPUPVALUE",	
			   "size=30 maxlength=50",	
			   "4",	
			   gettext("Insert the Id CardNumber"),
			    "" , "", "", "", "", 				
				"A2B_entity_card.php?popup_select=1&"
				, ", 'CardNumberSelection','width=550,height=330,top=20,left=100,scrollbars=1'" ,
				gettext("Define the card number ID to use."));

$HD_Form -> FieldEditElement ('speeddial, name, phone, id_cc_card');


if (DB_TYPE == "postgres"){
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS = "";
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  = "";	
}else{
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS = "creationdate ";
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  = "now()";	
}


$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'id_cc_card ';
$HD_Form -> FG_FILTERFIELDNAME = 'Customer ID';
$HD_Form -> FG_FILTER_FORM_ACTION = 'list';
$HD_Form -> FG_FILTERTYPE = 'POPUPVALUE'; // INPUT :: SELECT :: POPUPVALUE
$HD_Form -> FG_FILTERPOPUP = array('CC_entity_card.php?popup_select=1&', ", 'CardNumberSelection','width=550,height=350,top=20,left=100'");


if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="id_cc_card like '$filterprefix'";
}

$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");



$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Once you have completed the form above, click Confirm Data.");


$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";



