<?php

getpost_ifset(array('posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'current_page', 'filterprefix', 'filterprefix2'));


$HD_Form = new FormHandler("cc_call, cc_did","did billing");

$HD_Form -> FG_DEBUG = 0;
// FAQ
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "date";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";
$HD_Form -> FG_TABLE_CLAUSE = "( sipiax = 2 OR sipiax = 3) and cc_did.id=id_did ";


$HD_Form -> AddViewElement(gettext("DATE"), "starttime", "15%", "center", "nosort");
$HD_Form -> AddViewElement(gettext("DID"), "cc_did.did", "15%", "center", "nosort");
$HD_Form -> AddViewElement(gettext("NB_CALL"), "nb_call", "15%", "center", "nosort");
$HD_Form -> AddViewElement(gettext("TOTAL_DURATION"), "t_time", "15%", "center", "nosort");
$HD_Form -> AddViewElement(gettext("TOTAL_COST"), "t_cost", "15%", "center", "nosort");
$HD_Form -> AddViewElement(gettext("CARDNUMBER"), "username", "15%", "center", "nosort");

$HD_Form -> FG_OTHER_BUTTON1 = true;
$HD_Form -> FG_OTHER_BUTTON1_LINK = 'CC_entity_edit_charge.php?form_action=ask-add&atmenu=charge&stitle=Charge&username=|param1|&id_did=|param|&';
$HD_Form -> FG_OTHER_BUTTON1_ALT = '<font color="red">BILL</font>';
$HD_Form -> FG_OTHER_BUTTON1_IMG = '';


// added a parameter to append  FG_TABLE_ID  ( by default ) or disable 0.
$HD_Form -> FieldViewElement ("DATE(starttime) as date, cc_did.did, count(*) as nb_call, sum(sessiontime) as t_time, sum(sessionbill) as t_cost, username, id_did ", 0 );

$HD_Form -> SQL_GROUP = " GROUP BY username, cc_did.did, id_did, date ";

$HD_Form -> CV_NO_FIELDS  = gettext("NO DID BILLING HAS BEEN CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;




$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'cardnumber';
$HD_Form -> FG_FILTERFIELDNAME = 'cardnumber';
$HD_Form -> FG_FILTER_FORM_ACTION = 'list';

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="username like '$filterprefix%'";
}


$HD_Form -> FG_FILTER_APPLY2 = true;
$HD_Form -> FG_FILTERFIELD2 = 'did';
$HD_Form -> FG_FILTERFIELDNAME2 = 'did';
$HD_Form -> FG_FILTER_FORM_ACTION2 = 'list';

if (isset($filterprefix2)  &&  (strlen($filterprefix2)>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="cc_did.did like '$filterprefix2%'";
}




$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add a ".$HD_Form->FG_INSTANCE_NAME." now.");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Once you have completed the form above, click on the CONTINUE button.");



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";

