<?php

getpost_ifset(array('cardid','searchenabled','monthselect','yearselect','paymentoperator','totaloperator','paymenttext','totaltext','filterradio','section'));
if ($totaltext == "")
{
	$totaltext = 0;
}
$HD_Form = new FormHandler("cc_invoices","Invoice");

$HD_Form -> FG_TABLE_NAME = "cc_invoices inv";

if(isset($searchenabled) && $searchenabled == "yes")
{
	if ($filterradio == "date")
	{
		$inv_create_startdate = date('Y-m-d',mktime(0,0,0, $monthselect,1, $yearselect));
		if ($monthselect == 12)
			$inv_create_enddate = date('Y-m-d',mktime(0,0,0, 1,1, $yearselect + 1));
		} else {
			$inv_create_enddate = date('Y-m-d',mktime(0,0,0, $monthselect + 1,1, $yearselect));
		}
		$HD_Form -> FG_TABLE_CLAUSE = "inv.cardid = $cardid AND inv.cover_enddate >= '".$inv_create_startdate."' AND inv.cover_enddate < '".$inv_create_enddate."'";
	}
	if ($filterradio == "payment")
	{
	}
	if($filterradio == "total")
	{
		if($totaloperator == "equal")
		{
			$HD_Form -> FG_TABLE_CLAUSE = "inv.cardid = $cardid AND inv.total = ".$totaltext;
		}
		if($totaloperator == "greater")
		{
			$HD_Form -> FG_TABLE_CLAUSE = "inv.cardid = $cardid AND inv.total > ".$totaltext;
		}
		if($totaloperator == "less")
		{
			$HD_Form -> FG_TABLE_CLAUSE = "inv.cardid = $cardid AND inv.total < ".$totaltext;
		}
		if($totaloperator == "greaterthanequal")
		{
			$HD_Form -> FG_TABLE_CLAUSE = "inv.cardid = $cardid AND inv.total >= ".$totaltext;
		}
		if($totaloperator == "lessthanequal")
		{
			$HD_Form -> FG_TABLE_CLAUSE = "inv.cardid = $cardid AND inv.total <= ".$totaltext;
		}		
	}
}
else
{
	$HD_Form -> FG_TABLE_CLAUSE = "inv.cardid = $cardid";
}

//$HD_Form -> FG_TABLE_CLAUSE = "inv.cardid = $cardid";
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "inv.invoicecreated_date";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";
$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_LIMITE_DISPLAY = 10;


$QUERY_REFILL="SELECT t1.id, sum(t3.credit) from cc_card as t1, cc_logrefill as t3 WHERE t1.id=t3.card_id GROUP BY t1.id";
$QUERY_PAYMENT="SELECT t1.id, SUM(t2.payment) from cc_card as t1, cc_logpayment as t2 WHERE t1.id=t2.card_id GROUP BY t1.id";

$HD_Form -> AddViewElement(gettext("Invoice Date"), "invoicecreated_date", "12%", "center", "sort", "22", "", "", "", "", "", "display_dateonly");
$HD_Form -> AddViewElement(gettext("Cover Start"), "cover_startdate", "12%", "center", "sort", "22", "", "", "", "", "", "display_dateonly");
$HD_Form -> AddViewElement(gettext("Cover End"), "cover_enddate", "12%", "center", "sort", "22", "", "", "", "", "", "display_dateonly");

$HD_Form -> AddViewElement(gettext("Amount"), "amount", "10%", "center", "sort", "15");
$HD_Form -> AddViewElement(gettext("Tax"), "tax", "7%", "center", "sort", "15");
$HD_Form -> AddViewElement(gettext("Total"), "total", "7%", "center", "sort");
$HD_Form -> AddViewElement(gettext("PAYMENT"), "payment", "10%", "center", "sort", "15", "lie", "cc_logpayment as t2, cc_invoices t3", "CASE WHEN SUM(t2.payment) IS NULL THEN 0 ELSE SUM(t2.payment) END", "t3.cardid = t2.card_id  AND t2.card_id='$cardid' AND t3.id='%id' AND t2.date >= t3.cover_startdate AND t2.date < t3.cover_enddate", "%1");

$HD_Form -> FieldViewElement ('invoicecreated_date, cover_startdate, cover_enddate, amount, tax, total, id');

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
