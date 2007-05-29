<?php

$HD_Form = new FormHandler("cc_currencies","Currencies");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = " currency";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";

//$HD_Form -> AddViewElement("", "", "15%", "center", "sort", "", "value", '<img src="'.KICON_PATH.'/favorites_16.png"> <img src="'.KICON_PATH.'/favorites_16.png"> <img src="'.KICON_PATH.'/favorites_24.png"><img src="'.KICON_PATH.'/favorites_16.png"> ');
$HD_Form -> AddViewElement(gettext("CURRENCY"), "currency", "15%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DETAIL"), "name", "20%", "center", "sort");
$HD_Form -> AddViewElement(gettext("VALUE"), "value", "15%", "center", "sort");
//$HD_Form -> AddViewElement("", "", "15%", "center", "sort", "", "value", '<img src="'.KICON_PATH.'/favorites_16.png"> <img src="'.KICON_PATH.'/favorites_16.png"> <img src="'.KICON_PATH.'/favorites_24.png"><img src="'.KICON_PATH.'/favorites_16.png"> ');

$HD_Form -> FieldViewElement ('currency,name,value');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_VIEW_TABLE_WITDH = '70%';

$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;

//$HD_Form -> FG_TABLE_ALTERNATE_ROW_COLOR = array("#EEEEEE", "#EEEEEE");


// Set the filter variables

$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'currency';
$HD_Form -> FG_FILTERFIELDNAME = 'currency';

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	$filterprefix = strtoupper($filterprefix);
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="currency like '$filterprefix%'";
}




$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");



$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add a")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("now.");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Once you have completed the form above, click on the CONTINUE button.");



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
?>
