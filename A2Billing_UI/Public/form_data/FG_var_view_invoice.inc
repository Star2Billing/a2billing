<?php

$plang='en';
getpost_ifset(array('cardid','entercustomer','section','Period','frommonth','tomonth','fromstatsmonth','tostatsmonth'));
if ($totaltext == "")
{
	$totaltext = 0;
}
$HD_Form = new FormHandler("cc_card card, cc_invoices inv LEFT JOIN (SELECT * FROM cc_invoice_history GROUP BY invoiceid HAVING Max(invoicesent_date)) as his ON inv.ID = his.invoiceid ","Invoice");

$HD_Form -> FG_TABLE_NAME = "cc_card card, cc_invoices inv LEFT JOIN (SELECT * FROM cc_invoice_history GROUP BY invoiceid HAVING Max(invoicesent_date)) as his ON inv.ID = his.invoiceid";

$HD_Form -> FG_TABLE_CLAUSE = " card.id = inv.cardid ";
if (isset($entercustomer)  &&  ($entercustomer>0))
{
	$HD_Form -> FG_TABLE_CLAUSE .= " AND card.username = '$entercustomer' ";	
}

$date_clause='';
if (DB_TYPE == "postgres"){
	 	$UNIX_TIMESTAMP = "";
}else{
		$UNIX_TIMESTAMP = "UNIX_TIMESTAMP";
}
$lastdayofmonth = date("t", strtotime($tostatsmonth.'-01'));
if (strlen($HD_Form -> FG_TABLE_CLAUSE) > 0 && $frommonth)
{
	$date_clause .=' AND ';
}	
if ($frommonth && isset($fromstatsmonth))
{
	$date_clause.="  $UNIX_TIMESTAMP(cover_startdate) >= $UNIX_TIMESTAMP('$fromstatsmonth-01')";
}
if( ($frommonth && $tomonth) || (strlen($HD_Form -> FG_TABLE_CLAUSE) > 0 && $tomonth) )
{
	$date_clause .=' AND ';
}

if ($tomonth && isset($tostatsmonth))
{
	$date_clause.=" $UNIX_TIMESTAMP(cover_startdate) <= $UNIX_TIMESTAMP('".$tostatsmonth."-$lastdayofmonth 23:59:59')"; 
}	

$HD_Form -> FG_TABLE_CLAUSE .= $date_clause;

	
//
$HD_Form -> FG_TABLE_ID = "inv.id";
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "inv.invoicecreated_date";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";
$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_LIMITE_DISPLAY = 10;

$status_list = array();
$status_list["0"] = array( gettext("Failed"), "0");
$status_list["1"] = array( gettext("Successful"), "1");

$payment_status_list = array();
$payment_status_list["0"] = array( gettext("UNPAID"), "0");
$payment_status_list["1"] = array( gettext("SENT-UNPAID"), "1");
$payment_status_list["2"] = array( gettext("SENT-PAID"),  "2");
$payment_status_list["3"] = array( gettext("PAID"),  "3");

$HD_Form -> AddViewElement(gettext("ID"), "id", "4%", "center", "sort");
$HD_Form -> AddViewElement(gettext("CARDNUMBER"), "username", "4%", "center", "sort", "", "30", "", "", "", "", "linktocustomer");
$HD_Form -> AddViewElement(gettext("INVOICEDATE"), "invoicecreated_date", "7%", "center", "sort", "22", "", "", "", "", "", "display_dateonly");
$HD_Form -> AddViewElement(gettext("COVERSTART"), "cover_startdate", "7%", "center", "sort", "22", "", "", "", "", "", "display_dateonly");
$HD_Form -> AddViewElement(gettext("COVEREND"), "cover_enddate", "7%", "center", "sort", "22", "", "", "", "", "", "display_dateonly");
$HD_Form -> AddViewElement(gettext("SENTDATE"), "invoicesent_date", "7%", "center", "sort", "22", "", "", "", "", "", "display_dateonly");
$HD_Form -> AddViewElement(gettext("STATUS"), "invoicestatus", "5%", "center", "sort", "", "list", $status_list);
$HD_Form -> AddViewElement(gettext("PAYMENTSTATUS"), "payment_status", "5%", "center", "sort", "", "list", $payment_status_list);
$HD_Form -> AddViewElement(gettext("AMOUNT"), "total", "9%", "center", "sort", "", "", "", "", "", "", "display_2bill");
//$HD_Form -> AddViewElement(gettext("PAYMENT"), "payment", "10%", "center", "sort", "15", "lie", "cc_logpayment as t2, cc_invoices t3", "CASE WHEN SUM(t2.payment) IS NULL THEN 0 ELSE SUM(t2.payment) END", "t3.cardid = t2.card_id  AND t2.card_id='$cardid' AND t3.id='%id' AND t2.date >= t3.cover_startdate AND t2.date < t3.cover_enddate", "%1");

$HD_Form -> FieldViewElement ('inv.id, card.username,invoicecreated_date, cover_startdate, cover_enddate, invoicesent_date, invoicestatus, inv.payment_status, total');

$HD_Form -> FG_ACTION_SIZE_COLUMN = '10%';
$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;

	$HD_Form -> FG_OTHER_BUTTON1 = true;
	$HD_Form -> FG_OTHER_BUTTON2 = true;
	
	$HD_Form -> FG_OTHER_BUTTON1_LINK="A2B_entity_invoice_detail.php?id=|param|&invoice_type=2";
	$HD_Form -> FG_OTHER_BUTTON2_LINK="A2B_entity_invoice_detail_pdf.php?id=|param|&cardid=$cardid&action=sendinvoice&exporttype=pdf&invoice_type=2";
	
	$HD_Form -> FG_OTHER_BUTTON1_ALT = 'Details';
	$HD_Form -> FG_OTHER_BUTTON2_ALT = 'Send';
		
	$HD_Form -> FG_OTHER_BUTTON1_IMG = Images_Path.'/details.gif';
	$HD_Form -> FG_OTHER_BUTTON2_IMG = Images_Path.'/email03.gif';

	//$HD_Form -> FG_OTHER_BUTTON2_IMG = '';

$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your ".$HD_Form->FG_INSTANCE_NAME);
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this ".$HD_Form->FG_INSTANCE_NAME.", click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new ".$HD_Form->FG_INSTANCE_NAME.".<br>Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_FILTER_APPLY = false;
$HD_Form -> FG_FILTERFIELD = 'cardnumber';
$HD_Form -> FG_FILTERFIELDNAME = 'cardnumber';
$HD_Form -> FG_FILTER_FORM_ACTION = 'list';

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="username like '$filterprefix%'";
}

$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add a ".$HD_Form->FG_INSTANCE_NAME." now.");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted. <br>");

$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Once you have completed the form above, click on the CONTINUE button.");

$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
?>
