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



getpost_ifset(array('id', 'card_id', 'date', 'payment','description','added_refill','refill_type'));



if (!isset($form_action) || $form_action=="list"){
	$HD_Form = new FormHandler("cc_logpayment LEFT JOIN cc_card ON cc_card.id=cc_logpayment.card_id LEFT JOIN cc_card_group ON cc_card.id_group=cc_card_group.id","payment");
	$HD_Form -> FG_TABLE_CLAUSE = " cc_card_group.id_agent=".$_SESSION['agent_id'] ;
}else{
	$HD_Form = new FormHandler("cc_logpayment","payment");
}



$HD_Form -> FG_TABLE_ID = 'cc_logpayment.id';

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

$list_refill_type=Constants::getRefillType_List();
$HD_Form -> AddViewElement(gettext("ID"), "cc_logpayment.id", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ACCOUNT"), "username", "20%", "center", "sort", "", "30", "", "", "", "", "linktocustomer");
$HD_Form -> AddViewElement(gettext("PAYMENT DATE"), "date", "20%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("PAYMENT AMOUNT"), "payment", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DESCRIPTION"), "description", "23%", "center", "sort");
$HD_Form -> AddViewElement(gettext("PAYMENT TYPE"),"payment_type", "7%", "center", "sort", "", "list", $list_refill_type);
$HD_Form -> AddViewElement(gettext("REFILL"), "id_logrefill", "20%", "center", "sort", "", "lie_link", "cc_logrefill", "credit, id", "id='%id'", "%1 ".BASE_CURRENCY." (id:%2)", "", "A2B_entity_logrefill.php");

$HD_Form -> FieldViewElement ('cc_logpayment.id, username, date, payment, cc_logpayment.description ,payment_type,id_logrefill');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_INFO = true;
$HD_Form -> FG_INFO_LINK = "A2B_payment_info.php?id=";

$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'cardnumber';
$HD_Form -> FG_FILTERFIELDNAME = 'account number';
$HD_Form -> FG_FILTER_FORM_ACTION = 'list';


if (!($popup_select>=1))	 $HD_Form -> FG_FILTER_SEARCH_FORM = true;

$HD_Form -> FG_FILTER_SEARCH_SESSION_NAME = 'payment_cust_selection';

$HD_Form -> FG_FILTER_SEARCH_TOP_TEXT = gettext('Define specific criteria to search for payments created.');
$HD_Form -> FG_FILTER_SEARCH_1_TIME = true;
$HD_Form -> FG_FILTER_SEARCH_1_TIME_TEXT = gettext('DATE');
$HD_Form -> FG_FILTER_SEARCH_1_TIME_FIELD = 'date';

$HD_Form -> AddSearchElement_C1(gettext("ACCOUNT NUMBER"), 'username','usernametype');
$HD_Form -> AddSearchElement_C1(gettext("LASTNAME"),'lastname','lastnametype');
$HD_Form -> AddSearchElement_C1(gettext("FIRSTNAME"),'firstname','firstnametype');


if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="username like '$filterprefix%'";
}




