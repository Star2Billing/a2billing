<?php

getpost_ifset(array('id', 'card_id', 'date', 'credit','description'));


if (!isset($form_action) || $form_action=="list") {
	$HD_Form = new FormHandler("cc_logrefill LEFT JOIN cc_card ON cc_card.id=cc_logrefill.card_id LEFT JOIN cc_agent_cardgroup ON cc_card.id_group=cc_agent_cardgroup.id_card_group","Refill");
	$HD_Form -> FG_TABLE_CLAUSE = " cc_agent_cardgroup.id_agent=".$_SESSION['agent_id'] ;
} else {
	$HD_Form = new FormHandler("cc_logrefill","Refill");
}

$HD_Form -> FG_TABLE_ID = 'cc_logrefill.id';

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";


$list_refill_type=Constants::getRefillType_List();
$HD_Form -> AddViewElement(gettext("ID"), "cc_logrefill.id", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ACCOUNT"), "username", "15%", "center", "sort", "", "30", "", "", "", "", "linktocustomer");
$HD_Form -> AddViewElement(gettext("REFILL DATE"), "date", "20%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("DESCRIPTION"), "description", "25%", "center", "sort");
$HD_Form -> AddViewElement(gettext("REFILL TYPE"),"refill_type", "7%", "center", "sort", "", "list", $list_refill_type);
$HD_Form -> AddViewElement(gettext("REFILL AMOUNT"), "cc_logrefill.credit", "18%", "center", "sort");

$HD_Form -> FieldViewElement ('cc_logrefill.id, username, date, cc_logrefill.description, cc_logrefill.refill_type as refill_type, cc_logrefill.credit');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'cardnumber';
$HD_Form -> FG_FILTERFIELDNAME = 'Account number';
$HD_Form -> FG_FILTER_FORM_ACTION = 'list';

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="username like '$filterprefix%'";
}






