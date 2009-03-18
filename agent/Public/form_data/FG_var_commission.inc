<?php

getpost_ifset(array('id', 'card_id', 'date', 'payment','description','added_refill','refill_type'));


$HD_Form = new FormHandler("cc_agent_commission","Commission");

$HD_Form -> FG_TABLE_CLAUSE = " cc_agent_commission.id_agent=".$_SESSION['agent_id'] ;
$HD_Form -> FG_TABLE_ID = 'cc_agent_commission.id';

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_commission_agent.php?form_action=ask-add&section=2";
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add Commission");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/money_add.png" ;
$paid_status_list = Constants::getInvoicePaidStatusList();
$HD_Form -> AddViewElement(gettext("ID"), "cc_agent_commission.id", "7%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ACCOUNT"), "id_card", "15%", "center", "sort", "", "30", "", "", "", "", "linktocustomer_id");
$HD_Form -> AddViewElement(gettext("PAYMENT DATE"), "date", "20%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("AMOUNT"), "amount", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("PAID STATUS"),"payment_type", "7%", "center", "sort", "", "list", $paid_status_list);
$HD_Form -> AddViewElement(gettext("LINK TO PAYMENT"), "id_payment", "20%", "center", "sort", "", "lie_link", "cc_logpayment", "payment, id", "id='%id'", "%1 ".BASE_CURRENCY." (id:%2)", "", "A2B_entity_payment.php");

$HD_Form -> FieldViewElement ('cc_agent_commission.id, id_card, date, amount ,paid_status,id_payment');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;



