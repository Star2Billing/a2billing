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



$HD_Form = new FormHandler("cc_agent","Agent");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_LIMITE_DISPLAY = 10;

$HD_Form -> AddViewElement(gettext("ID"), "id", "7%", "center", "sort", "");
$HD_Form -> AddViewElement(gettext("Agent"), "username", "7%", "center", "sort", "", "30", "", "", "", "", "linktoagent");
$HD_Form -> AddViewElement(gettext("CREDIT"), "credit", "7%", "center", "sort",  "", "", "", "", "", "", "display_2bill");
$HD_Form -> AddViewElement(gettext("COMMISSION BAL"), "com_balance", "7%", "center", "sort",  "", "", "", "", "", "", "display_2bill");
$HD_Form -> AddViewElement(gettext("TOTAL COM. ACCRUED"), "com_accrued", "10%", "center", "sort", "15", "lie", "cc_agent_commission as t2", "TRUNCATE(SUM(t2.amount),2)", "t2.id_agent='%id'  ", "%1");
$HD_Form -> AddViewElement(gettext("CUSTOMER REFILL BY AGENT"), "refill", "10%", "center", "sort", "15", "lie", "cc_logrefill as t2", "TRUNCATE(SUM(t2.credit),2)", "t2.agent_id='%id' AND t2.refill_type=3 ", "%1");
$HD_Form -> AddViewElement(gettext("AGENT REFILL"), "refill", "10%", "center", "sort", "15", "lie", "cc_logrefill_agent as t2", "TRUNCATE(SUM(t2.credit),2)", "t2.agent_id='%id' ", "%1");
$HD_Form -> AddViewElement(gettext("AGENT PAYMENT"), "payment", "7%", "center", "sort", "15", "lie", "cc_logpayment_agent as t2", "TRUNCATE(SUM(t2.payment),2)", "t2.agent_id='%id' ", "%1");

$HD_Form -> FieldViewElement ('id, id, credit,com_balance,id,id, id, id');

$HD_Form -> FG_FILTER_SEARCH_FORM = true;
$HD_Form -> FG_FILTER_SEARCH_SESSION_NAME = 'agent_situation';
$HD_Form -> FG_FILTER_SEARCH_TOP_TEXT = gettext('Define specific criteria to search for agent infos.');
$HD_Form -> FG_FILTER_SEARCH_DELETE_ALL =false;
$HD_Form -> AddSearchElement_C1(gettext("LOGIN"), 'login','logintype');
$HD_Form -> AddSearchElement_C1(gettext("LASTNAME"),'lastname','lastnametype');
$HD_Form -> AddSearchElement_C1(gettext("FIRSTNAME"),'firstname','firstnametype');



$HD_Form -> FG_ACTION_SIZE_COLUMN = '15%';
$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO").strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;
$HD_Form -> FG_ADDITION = false;



$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");



$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add a ".$HD_Form->FG_INSTANCE_NAME." now.");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new ".$HD_Form->FG_INSTANCE_NAME." has been inserted. <br>");


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Once you have completed the form above, click on the CONTINUE button.");



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";

