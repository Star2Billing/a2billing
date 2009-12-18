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



$HD_Form = new FormHandler("cc_card LEFT JOIN cc_card_group ON cc_card.id_group=cc_card_group.id ","Card");
$HD_Form -> FG_TABLE_CLAUSE = " cc_card_group.id_agent=".$_SESSION['agent_id'] ;
$HD_Form -> FG_TABLE_ID="cc_card.id";
$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_LIMITE_DISPLAY = 10;

$HD_Form -> AddViewElement(gettext("ID"), "cc_card.id", "7%", "center", "sort", "");
$HD_Form -> AddViewElement(gettext("CardNumber"), "username", "7%", "center", "sort", "", "30", "", "", "", "", "linktocustomer");
$HD_Form -> AddViewElement(gettext("FIRSTNAME"), "firstname", "10%", "center", "sort", "15");
$HD_Form -> AddViewElement(gettext("LASTNAME"), "lastname", "10%", "center", "sort", "15");

$HD_Form -> AddViewElement(gettext("CREDIT"), "credit", "7%", "center", "sort",  "", "", "", "", "", "", "display_2bill");
$HD_Form -> AddViewElement(gettext("COMMISSION ACCRUED"), "commission", "10%", "center", "sort", "15", "lie", "cc_agent_commission", "COALESCE(TRUNCATE(SUM(amount),2),0)", "id_card='%id'", "%1 ".strtoupper(BASE_CURRENCY));
$HD_Form -> AddViewElement(gettext("INVOICE"), "invoice", "10%", "center", "sort", "15", "lie", "cc_invoice LEFT JOIN cc_invoice_item ON cc_invoice_item.id_invoice=cc_invoice.id", "TRUNCATE(SUM(CEIL(cc_invoice_item.price*(1+(cc_invoice_item.vat/100))*100)/100),2)", "cc_invoice.id_card='%id' GROUP BY cc_invoice.id_card", "%1");
$HD_Form -> AddViewElement(gettext("PAYMENT"), "payment", "7%", "center", "sort", "15", "lie", "cc_logpayment as t2", "TRUNCATE(SUM(t2.payment),2),t2.card_id", "t2.card_id='%id' GROUP BY t2.card_id", "%1");
$HD_Form -> AddViewElement(gettext("TO PAY"), "to pay", "7%", "center", "sort", "", "eval",'(%7 - %6 )'); //abs

$HD_Form -> FieldViewElement ('cc_card.id, username, firstname, lastname, credit,cc_card.id,cc_card.id, cc_card.id, cc_card.id');

$HD_Form -> FG_ACTION_SIZE_COLUMN = '15%';
$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO").strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;
$HD_Form -> FG_ADDITION = false;

$HD_Form -> FG_OTHER_BUTTON1 = true;
$HD_Form -> FG_OTHER_BUTTON2 = true;
$HD_Form -> FG_OTHER_BUTTON2_LINK="javascript:;\" onClick=\"MM_openBrWindow('A2B_entity_moneysituation_details.php?type=payment&form_action=list&atmenu=card&displayheader=0&popup_select=1&id=|param|','','scrollbars=yes,resizable=yes,width=500,height=270')\"";
$HD_Form -> FG_OTHER_BUTTON1_LINK="javascript:;\" onClick=\"MM_openBrWindow('A2B_entity_moneysituation_details.php?type=invoice&form_action=list&atmenu=card&displayheader=0&popup_select=1&id=|param|','','scrollbars=yes,resizable=yes,width=500,height=270')\"";
$HD_Form -> FG_OTHER_BUTTON2_ALT = '<font color="red">'.gettext('PAYMENT').'</font>';
$HD_Form -> FG_OTHER_BUTTON1_ALT = '<font color="red">'.gettext('INVOICE').'</font>';

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

