<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2012 - Star2billing S.L.
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

if (! has_rights (ACX_ADMINISTRATOR)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}


getpost_ifset(array('id', 'payment_method', 'payment_filename'));

$HD_Form = new FormHandler("cc_payment_methods", "payment method");

$HD_Form->FG_DEBUG = 0;
$HD_Form->FG_TABLE_DEFAULT_ORDER = "payment_method";
$HD_Form->FG_TABLE_DEFAULT_SENS = "DESC";

$HD_Form->AddViewElement(gettext("Payment Method"), "payment_method", "25%", "center", "sort");
$HD_Form->AddViewElement(gettext("File Name"), "payment_filename", "20%", "center", "sort");

$HD_Form->FieldViewElement('payment_method, payment_filename');

$HD_Form->FG_OTHER_BUTTON1_LINK = "A2B_entity_payment_settings.php?id=";
$HD_Form->FG_OTHER_BUTTON1_IMG = Images_Path . "/icon-viewdetails.gif";
$HD_Form->FG_OTHER_BUTTON1_ALT = gettext("VIEW DETAILS");

$HD_Form->CV_NO_FIELDS = gettext("THERE ARE NO " . strtoupper($HD_Form->FG_INSTANCE_NAME) . " CREATED!");
$HD_Form->CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form->CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form->CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form->FG_EDITION = false;
$HD_Form->FG_DELETION = false;
$HD_Form->FG_ADDITION = false;
$HD_Form->FG_OTHER_BUTTON1 = true;