<?php


getpost_ifset(array('id', 'provider_name', 'creationdate', 'description', 'popup_select'));

$plang='en';

$HD_Form = new FormHandler("cc_provider","Provider");

$HD_Form -> FG_DEBUG = 0;


if ($popup_select)
	$HD_Form -> AddViewElement(gettext("ID"), "id", "15%", "center", "sort");
$HD_Form -> AddViewElement(gettext("PROVIDER NAME"), "provider_name", "25%", "center", "sort");
$HD_Form -> AddViewElement(gettext("CREATIONDATE"), "creationdate", "20%", "center", "sort", "19", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("DESCRIPTION"), "description", "30%", "center", "sort", "30");
$HD_Form -> FieldViewElement (($popup_select ? 'id,' : '') . 'provider_name, creationdate, description');


$HD_Form -> CV_NO_FIELDS  = gettext("NO PROVIDERS CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_VIEW_TABLE_WITDH = '70%';


if ($popup_select) {
	$HD_Form -> FG_LIMITE_DISPLAY = 7;
	$HD_Form -> CV_FOLLOWPARAMETERS = "&popup_select=" . $_GET['popup_select'] . "&popup_formname=" . $_GET['popup_formname'] . "&popup_fieldname=" . $_GET['popup_fieldname'];

	$HD_Form -> FG_OTHER_BUTTON1 = true;
	$HD_Form -> FG_OTHER_BUTTON1_ALT = '<font color="red">&lt;select&gt;</font>';
	$HD_Form -> FG_OTHER_BUTTON1_IMG = '';

	//echo $popup_select;
	if ($popup_select == 1)
		$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:sendValue('|param|');";
	elseif ($popup_select == 2)
		$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:sendValue('|col0|');";
}
else {
	$HD_Form -> FG_EDITION = true;
	$HD_Form -> FG_DELETION = true;
}

$HD_Form -> AddEditElement ( gettext("PROVIDER NAME"),	
		"provider_name",	'$value',	
		"INPUT",
		"size=40 maxlength=40",
		"0",
		gettext("Insert the provider name"), 
		"" , "", "",	"", "", "", "", "" );


$HD_Form -> AddEditElement ( gettext("DESCRIPTION"),
		"description",
		'$value',	
		"TEXTAREA",
		"cols=50 rows=5",
		"",
		gettext("Insert the description"), 
		"" , "", "",	"", "", "", "", "");

			

$HD_Form -> FieldEditElement ('provider_name, description');


if (DB_TYPE != "postgres"){
	$HD_Form ->FG_QUERY_ADITION_HIDDEN_FIELDS = "creationdate ";
	$HD_Form ->FG_QUERY_ADITION_HIDDEN_VALUE  = "now() ";
}



$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add $HD_Form->FG_INSTANCE_NAME.");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
?>
