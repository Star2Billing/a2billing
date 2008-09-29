<?php

$HD_Form = new FormHandler("cc_card","Card");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_LIMITE_DISPLAY = 10;

$HD_Form -> AddViewElement(gettext("CardNumber"), "username", "7%", "center", "sort", "", "30", "", "", "", "", "linktocustomer");
$HD_Form -> AddViewElement(gettext("<acronym title=\"CARDALIAS\">ALIAS</acronym>"), "useralias", "12%", "center", "sort");
$HD_Form -> AddViewElement(gettext("LASTNAME"), "lastname", "10%", "center", "sort", "15");

$HD_Form -> AddViewElement(gettext("CREDIT"), "credit", "7%", "center", "sort", "15");
$HD_Form -> AddViewElement(gettext("REFILL"), "refill", "10%", "center", "sort", "15", "lie", "cc_logrefill as t3", "SUM(t3.credit),t3.card_id", "t3.card_id='%id' GROUP BY t3.card_id", "%1");
$HD_Form -> AddViewElement(gettext("PAYMENT"), "payment", "7%", "center", "sort", "15", "lie", "cc_logpayment as t2", "SUM(t2.payment),t2.card_id", "t2.card_id='%id' GROUP BY t2.card_id", "%1");
$HD_Form -> AddViewElement(gettext("TO PAY"), "to pay", "7%", "center", "sort", "", "eval",'(%5-%4)'); //abs

$HD_Form -> FieldViewElement ('username, useralias, lastname, credit, id, id, id');

$HD_Form -> FG_ACTION_SIZE_COLUMN = '15%';
$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO").strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;


$HD_Form -> FG_OTHER_BUTTON1 = true;
$HD_Form -> FG_OTHER_BUTTON2 = true;
$HD_Form -> FG_OTHER_BUTTON2_LINK="javascript:;\" onClick=\"MM_openBrWindow('A2B_entity_moneysituation_details.php?type=payment&form_action=list&atmenu=card&displayheader=0&popup_select=1&id=|param|','','scrollbars=yes,resizable=yes,width=500,height=270')\"";
$HD_Form -> FG_OTHER_BUTTON1_LINK="javascript:;\" onClick=\"MM_openBrWindow('A2B_entity_moneysituation_details.php?type=refill&form_action=list&atmenu=card&displayheader=0&popup_select=1&id=|param|','','scrollbars=yes,resizable=yes,width=500,height=270')\"";
$HD_Form -> FG_OTHER_BUTTON2_ALT = '<font color="red">'.gettext('PAYMENT').'</font>';
$HD_Form -> FG_OTHER_BUTTON1_ALT = '<font color="red">'.gettext('REFILL').'</font>';

$HD_Form -> FG_OTHER_BUTTON1_IMG = '';
$HD_Form -> FG_OTHER_BUTTON2_IMG = '';


$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'cardnumber';
$HD_Form -> FG_FILTERFIELDNAME = 'cardnumber';
$HD_Form -> FG_FILTER_FORM_ACTION = 'list';

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="username like '$filterprefix%'";
}



$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add a ".$HD_Form->FG_INSTANCE_NAME." now.");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new ".$HD_Form->FG_INSTANCE_NAME." has been inserted. <br>");


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Once you have completed the form above, click on the CONTINUE button.");



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";

