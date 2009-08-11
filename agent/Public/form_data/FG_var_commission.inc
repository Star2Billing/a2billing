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


$HD_Form = new FormHandler("cc_agent_commission","Commission");

$HD_Form -> FG_TABLE_CLAUSE = " cc_agent_commission.id_agent=".$_SESSION['agent_id'] ;
$HD_Form -> FG_TABLE_ID = 'cc_agent_commission.id';

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

$paid_status_list = Constants::getInvoicePaidStatusList();
$HD_Form -> AddViewElement(gettext("ID"), "cc_agent_commission.id", "7%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ACCOUNT"), "id_card", "15%", "center", "sort", "", "30", "", "", "", "", "linktocustomer_id");
$HD_Form -> AddViewElement(gettext("PAYMENT DATE"), "date", "20%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("AMOUNT"), "amount", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("PAID STATUS"),"payment_type", "7%", "center", "sort", "", "list", $paid_status_list);
$HD_Form -> AddViewElement(gettext("LINK TO PAYMENT"), "id_payment", "20%", "center", "sort", "", "lie_link", "cc_logpayment", "TRUNCATE(payment,2), id", "id='%id'", "%1 ".strtoupper(BASE_CURRENCY)." (id:%2)", "", "A2B_payment_info.php");

$HD_Form -> FieldViewElement ('cc_agent_commission.id, id_card, date, amount ,paid_status,id_payment');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;
$HD_Form -> FG_ADDITION = false;


