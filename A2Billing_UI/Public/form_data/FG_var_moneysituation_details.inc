<?php

getpost_ifset(array('id', 'displayheader', 'type','popup_select','popup_formname','popup_fieldname'));


// type : refill or payment
$HD_Form -> FG_DEBUG = 0;

if ($type=='payment'){
	$HD_Form = new FormHandler("cc_logpayment as t2","Payment");
	$HD_Form -> FG_TABLE_CLAUSE = "t2.card_id = $id";

	//SELECT t3.date, t3.credit, t3.reseller_id from cc_logrefill as t3 WHERE t3.card_id='1'
	//SELECT t2.date, t2.payment, t2.reseller_id from cc_logpayment as t2 WHERE t2.card_id='1'
	$HD_Form -> AddViewElement(gettext("DATE"), "date", "50%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
	$HD_Form -> AddViewElement(gettext("AMOUNT"), "payment", "20%", "center", "sort");

	$HD_Form -> FieldViewElement ('t2.date, t2.payment, t2.reseller_id');
}else{
	$HD_Form = new FormHandler("cc_logrefill as t2","Refill");
	$HD_Form -> FG_TABLE_CLAUSE = "t2.card_id = $id";
	$HD_Form -> FieldViewElement ('t2.date, t2.credit, t2.reseller_id');

	//SELECT t3.date, t3.credit, t3.reseller_id from cc_logrefill as t3 WHERE t3.card_id='1'
	//SELECT t2.date, t2.payment, t2.reseller_id from cc_logpayment as t2 WHERE t2.card_id='1'
	$HD_Form -> AddViewElement(gettext("DATE"), "date", "50%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
	$HD_Form -> AddViewElement(gettext("AMOUNT"), "credit", "20%", "center", "sort");
}

$HD_Form -> FG_LIMITE_DISPLAY = 5;



	

$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO").strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("CREATED!");  
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> CV_FOLLOWPARAMETERS = "&popup_select=".$_GET['popup_select']."&id=$id&type=$type";
$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;


$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add a $HD_Form->FG_INSTANCE_NAME now.");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new").$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Once you have completed the form above, click on the CONTINUE button.");



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
?>
