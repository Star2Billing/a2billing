<?php

getpost_ifset(array('id', 'agent_id', 'date', 'payment','description','added_refill','refill_type'));


if (!isset($form_action) || $form_action=="list"){
	$HD_Form = new FormHandler("cc_logpayment_agent LEFT JOIN cc_agent ON cc_agent.id=agent_id","Payment Agent");
	$HD_Form -> FG_TABLE_CLAUSE = " cc_agent.id =".$_SESSION['agent_id'] ;
}else{
	$HD_Form = new FormHandler("cc_logpayment_agent","Payment Agent");
}

$HD_Form -> FG_TABLE_ID = 'cc_logpayment_agent.id';

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

$list_refill_type=Constants::getRefillType_List();
$HD_Form -> AddViewElement(gettext("ID"), "cc_logpayment_agent.id", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("AGENT"), "creator", "10%", "center", "sort", "25", "lie", "cc_agent", "lastname, firstname", "id='%id'", "%1 %2");
$HD_Form -> AddViewElement(gettext("LOGIN"), "login", "10%", "center", "sort", "25", "lie", "cc_agent", "login", "id='%id'", "%1");
$HD_Form -> AddViewElement(gettext("PAYMENT DATE"), "date", "20%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("PAYMENT AMOUNT"), "payment", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DESCRIPTION"), "description", "23%", "center", "sort");
$HD_Form -> AddViewElement(gettext("PAYMENT TYPE"),"payment_type", "7%", "center", "sort", "", "list", $list_refill_type);

$HD_Form -> FieldViewElement ('cc_logpayment_agent.id, agent_id, agent_id, date, payment,description ,payment_type');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_INFO = true;
$HD_Form -> FG_INFO_LINK = "A2B_payment_info_agent.php?id=";


