<?php

getpost_ifset(array('id', 'card_id', 'date', 'payment','description','added_refill'));



	$HD_Form = new FormHandler("cc_logpayment","payment");


$HD_Form -> FG_TABLE_ID = 'cc_logpayment.id';

$HD_Form -> FG_TABLE_CLAUSE="card_id=".$_SESSION["card_id"];

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

$HD_Form -> AddViewElement(gettext("ID"), "cc_logpayment.id", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("PAYMENT DATE"), "date", "20%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("PAYMENT AMOUNT"), "payment", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DESCRIPTION"), "description", "30%", "center", "sort");

$HD_Form -> FieldViewElement ('cc_logpayment.id, date, payment,description ');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;




