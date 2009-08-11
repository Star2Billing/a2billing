<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,   
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 * 
 * @copyright   Copyright (C) 2004-2009 - Star2billing S.L. 
 * @author      Belaid Arezqui <areski@gmail.com>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 * @package     A2Billing
 *
 * Software License Agreement (GNU Affero General Public License)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * 
**/



getpost_ifset(array(
	'invoicetype', // ('billed', 'unbilled')	(already got by FG_var_card_money.inc)
	'exporttype',  // ('pdf', 'html')			to see the outstanding
	'billcalls',   // ('on', '') 				show calls in outstanding 
	'billcharges'  // ('on', '') 				show charges in outstanding
	));

// set default values
if ($exporttype == '')	{ // that means it just entered
	
	$exporttype = 'html';
	$billcalls = 'on';
	$billcharges = 'on';
}

$HD_Form = new FormHandler("cc_card","Card");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_LIMITE_DISPLAY = 10;

$HD_Form -> AddViewElement(gettext("CARDNUMBER"), "username", "7%", "center", "sort", "15", "30", "", "", "", "", "linktocustomer");
$HD_Form -> AddViewElement(gettext("<acronym title=\"CARDALIAS\">ALIAS</acronym>"), "useralias", "12%", "center", "sort");
$HD_Form -> AddViewElement(gettext("LASTNAME"), "lastname", "10%", "center", "sort", "15");
$HD_Form -> AddViewElement(gettext("CREDIT"), "credit", "7%", "center", "sort", "15");

if($invoicetype == "billed") {
	
	$HD_Form -> AddViewElement(gettext("REFILL"), "refill", "10%", "center", "sort", "15", "lie", "cc_logrefill as t3", "CASE WHEN SUM(t3.credit) is NULL THEN 0 ELSE SUM(t3.credit) END", "t3.card_id='%id'", "%1");
	$HD_Form -> AddViewElement(gettext("PAYMENT"), "payment", "7%", "center", "sort", "15", "lie", "cc_logpayment as t2", "CASE WHEN SUM(t2.payment) is NULL THEN 0 ELSE SUM(t2.payment) END", "t2.card_id='%id'", "%1");
	
} else {
	
	$HD_Form -> AddViewElement(gettext("REFILL"), "refill", "10%", "center", "sort", "15", "lie", "cc_logrefill as t3", "CASE WHEN SUM(t3.credit) is NULL THEN 0 ELSE SUM(t3.credit) END", 
		"t3.card_id='%id' AND ((t3.date >= (Select max(invoicecreated_date) from cc_invoice where cardid='%id') OR (t3.date >= (Select max(invoicecreated_date) from cc_invoice where cardid='%id'))", "%1");
	$HD_Form -> AddViewElement(gettext("PAYMENT"), "payment", "7%", "center", "sort", "15", "lie", "cc_logpayment as t2", "CASE WHEN SUM(t2.payment) is NULL THEN 0 ELSE SUM(t2.payment) END", 
		"t2.card_id='%id' AND ((t2.date >= (Select max(invoicecreated_date) from cc_invoice where cardid='%id') OR (t2.date >= (Select max(invoicecreated_date) from cc_invoice where cardid='%id'))", "%1");
}

$HD_Form -> AddViewElement(gettext("TO PAY"), "to pay", "7%", "center", "sort", "", "eval",'(%4-%5)'); //I changed it to the opposite to make the same sign as 'total to pay'

$HD_Form -> FieldViewElement ('username, useralias, lastname, credit, id, id, id');

$HD_Form -> FG_ACTION_SIZE_COLUMN = '15%';
$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO")." ".strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_ADDITION = false;
$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;

$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'cardnumber';
$HD_Form -> FG_FILTERFIELDNAME = 'cardnumber';
$HD_Form -> FG_FILTER_FORM_ACTION = 'list';

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)) {
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="username like '$filterprefix%'";
}

$HD_Form -> FG_OTHER_BUTTON1_IMG = '';
$HD_Form -> FG_OTHER_BUTTON2_IMG = '';

if($invoicetype == "billed")
{
	$HD_Form -> FG_OTHER_BUTTON1 = true;
	$HD_Form -> FG_OTHER_BUTTON2 = true;	
	$HD_Form -> FG_OTHER_BUTTON1_ALT = gettext('Invoices');
	$HD_Form -> FG_OTHER_BUTTON1_LINK="A2B_entity_invoices2_period.php?oncard=|param|";
	$HD_Form -> FG_OTHER_BUTTON2_ALT = gettext('Payments');
	$HD_Form -> FG_OTHER_BUTTON2_LINK="javascript:;\" onClick=\"MM_openBrWindow('A2B_entity_moneysituation_details.php?popup_select=1&type=payment&form_action=list&atmenu=card&displayheader=0&id=|param|','','scrollbars=yes,resizable=yes,width=500,height=270')\"";	
}
else
{
	$HD_Form -> FG_OTHER_BUTTON1 = ($billcalls == 'on' || $billcharges == 'on');
	$HD_Form -> FG_OTHER_BUTTON2 = ($billcalls == 'on' || $billcharges == 'on');	
	$HD_Form -> FG_OTHER_BUTTON1_ALT = gettext('Outstanding');
	$HD_Form -> FG_OTHER_BUTTON1_LINK="javascript:;\" OnClick=\"OpenOutstanding('A2B_entity_invoices2_detail.php?cardid=|param|',UnbilledForm)";
	$HD_Form -> FG_OTHER_BUTTON2_ALT = gettext('Email');
	$HD_Form -> FG_OTHER_BUTTON2_LINK="javascript:;\" OnClick=\"SendOutstanding('A2B_entity_invoices2_detail.php?cardid=|param|',UnbilledForm)";
}
?>
<script type="text/javascript">
<!--
function OpenOutstanding(URL, form) {
	
	var exporttyp	= '';
	var template	= '';
	var bilcal		= '';
	var bilcha		= '';
	
	if (form.billcharges.checked)
		bilcha = 'on';
	if (form.billcalls.checked)
		bilcal = 'on';
			
	for (var i=0; i<form.exporttype.length;i++) {
		if (form.exporttype[i].checked) {
			exporttyp = form.exporttype[i].value;	  
		}
	}
	
	template = form.templatefile.options[form.templatefile.selectedIndex].value;	

	window.open(URL + '&invoice_type=1&exporttype=' + exporttyp + '&templatefile=' + template + '&billcalls=' + bilcal +  '&billcharges=' + bilcha);
}
//-->
<!--
function SendOutstanding(URL, form) {
	
	var template	= '';
	var bilcal		= '';
	var bilcha		= '';
	
	if (form.billcharges.checked)
		bilcha = 'on';
	if (form.billcalls.checked)
		bilcal = 'on';	
		
	template = form.templatefile.options[form.templatefile.selectedIndex].value;	

	window.open(URL + '&invoice_type=1&exporttype=email&templatefile=' + template + '&billcalls=' + bilcal +  '&billcharges=' + bilcha);
}
//-->
</script>
