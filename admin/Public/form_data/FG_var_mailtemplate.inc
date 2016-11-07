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

getpost_ifset(array('id', 'IDCust', 'mailtype', 'fromemail', 'fromname', 'subject', 'messagetext', 'languages','id_language'));


// Get Latest id from the table
$latest_id = mailtemplate_latest_id();

$HD_Form = new FormHandler("cc_templatemail", gettext("mail template"));

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_ID = "id";
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "id";


if ($languages != '') {
    if ($languages != '') {
        $_SESSION["Langfilter"]=$languages;
    }
    if (isset($_SESSION["Langfilter"])) {
        $HD_Form -> FG_TABLE_CLAUSE = "id_language='".$_SESSION["Langfilter"]."'";
    }
} else {
    if (isset($_SESSION["Langfilter"])) {
        $languages = $_SESSION["Langfilter"];
    }
}
$id_language = $languages;
$HD_Form -> FG_EDITION_LINK = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?form_action=ask-edit&id_language=$languages&id=";
$HD_Form -> FG_DELETION_LINK = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?form_action=ask-delete&id_language=$languages&id=";


$typemail_list = array();
$typemail_list["reminder"]          = array( gettext("Reminder"), Mail::$TYPE_REMINDER);
$typemail_list["signup"]            = array( gettext("Signup"),  Mail::$TYPE_SIGNUP);
$typemail_list["forgetpassword"]    = array( gettext("Forget password"), Mail::$TYPE_FORGETPASSWORD);
$typemail_list["signupconfirmed"]   = array( gettext("Signup confirmed"), Mail::$TYPE_SIGNUPCONFIRM);
$typemail_list["payment"]           = array( gettext("Payment Confirmation"), Mail::$TYPE_PAYMENT);
$typemail_list["invoice_to_pay"]    = array( gettext("Invoice"), Mail::$TYPE_INVOICE_TO_PAY);
$typemail_list["epaymentverify"]    = array( gettext("EPayment Warning"), Mail::$TYPE_EPAYMENTVERIFY);
$typemail_list["did_paid"]          = array( gettext("Did Payment confirmation"), Mail::$TYPE_DID_PAID);
$typemail_list["did_unpaid"]        = array( gettext("Did not payment warning"), Mail::$TYPE_DID_UNPAID);
$typemail_list["did_released"]      = array( gettext("Did released warning"), Mail::$TYPE_DID_RELEASED);
$typemail_list["template1"]         = array( gettext("template1"), "template1");
$typemail_list["template2"]         = array( gettext("template2"), "template2");
$typemail_list["template3"]         = array( gettext("template3"), "template3");
$typemail_list["template4"]         = array( gettext("template4"), "template4");
$typemail_list["template5"]         = array( gettext("template5"), "template5");
$typemail_list["template6"]         = array( gettext("template6"), "template6");
$typemail_list["template7"]         = array( gettext("template7"), "template7");
$typemail_list["template8"]         = array( gettext("template8"), "template8");
$typemail_list["template9"]         = array( gettext("template9"), "template9");
$typemail_list["new_ticket"]        = array( gettext("New Ticket"), Mail::$TYPE_TICKET_NEW);
$typemail_list["modify_ticket"]     = array( gettext("Modify Ticket"), Mail::$TYPE_TICKET_MODIFY);
$typemail_list["invoice_to_pay"]    = array( gettext("Invoice to Pay"), Mail::$TYPE_INVOICE_TO_PAY);
$typemail_list["subscription_paid"] = array( gettext("Subscription Paid"), Mail::$TYPE_SUBSCRIPTION_PAID);
$typemail_list["subscription_unpaid"]   = array( gettext("Subscription Unpaid"), Mail::$TYPE_SUBSCRIPTION_UNPAID);
$typemail_list["subscription_disable_card"] = array( gettext("Subscription Disable Card"), Mail::$TYPE_SUBSCRIPTION_DISABLE_CARD);

$HD_Form -> AddViewElement(gettext("ID MAIL"), "id", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("MAILTYPE"), "mailtype", "14%", "center", "sort");
$HD_Form -> AddViewElement(gettext("FROMEMAIL"), "fromemail", "25%", "center", "sort");
$HD_Form -> AddViewElement(gettext("SUBJECT"), "subject", "30%", "center", "sort");


$HD_Form -> FieldViewElement ('id, mailtype, fromemail, subject');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

if ($popup_select >= 1) {
    $HD_Form -> FG_OTHER_BUTTON1 = true;
    $HD_Form -> FG_OTHER_BUTTON1_ALT = '<font color="red">&lt;'.gettext("SELECT TEXT").'&gt;</font>';
    $HD_Form -> FG_OTHER_BUTTON1_IMG = '';

    if ($popup_select == 1) {
        $HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:sendValue('|col0|');";
    }
} else {
    $HD_Form -> FG_LIST_ADDING_BUTTON1 = true;
    $HD_Form -> FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_mailtemplate.php?form_action=ask-add&id_language=$languages&section=".$_SESSION["menu_section"];
    $HD_Form -> FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add MAIL TEMPLATE");
    $HD_Form -> FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/email_add.png" ;

    $HD_Form -> FG_ADDITION = true;
    $HD_Form -> FG_EDITION = true;
    $HD_Form -> FG_DELETION = true;

    // Translation
    $HD_Form -> FG_OTHER_BUTTON2 = true;
    $HD_Form -> FG_OTHER_BUTTON2_LINK= "A2B_entity_translate.php?form_action=ask-add&id=|param|&mailtype=|col0|&languages=$languages";
    $HD_Form -> FG_OTHER_BUTTON2_ALT = gettext('Translate');
}


$HD_Form -> AddEditElement (gettext("MAIL TYPE"),
    "mailtype",
    '$value',
    "SELECT",
    "",
    "",
    "",
    "list" , "", "", "", $typemail_list, "%1", "", "");

$HD_Form -> AddEditElement (gettext("LANGUAGE"),
    "id_language",
    '',
    "INPUT",
    "size=30 maxlength=60",
    "",
    gettext("Insert the mail id language"),
    "" , "", "", "", "" , "", "", "");


$HD_Form -> AddEditElement (gettext("FROM EMAIL"),
    "fromemail",
    '',
    "INPUT",
    "size=30 maxlength=60",
    "1",
    gettext("Insert the user fromemail"),
    "" , "", "", "", "" , "", "", "");

$HD_Form -> AddEditElement (gettext("FROM NAME"),
    "fromname",
    '',
    "INPUT",
    "size=30 maxlength=60",
    "",
    gettext("Insert the user fromname"),
    "" , "", "", "", "" , "", "", "");

$HD_Form -> AddEditElement (gettext("SUBJECT"),
    "subject",
    '',
    "INPUT",
    "size=30 maxlength=80",
    "",
    gettext("Insert the user subject"),
    "" , "", "", "", "" , "", "", "");

$HD_Form -> AddEditElement (gettext("MESSAGE TEXT"),
    "messagetext",
    '',
    "TEXTAREA",
    "cols=70 rows=10",
    "",
    gettext("Insert the user subject"),
    "" , "", "", "", "" , "", "", "");

$HD_Form -> FieldEditElement ('mailtype, id_language, fromemail, fromname, subject, messagetext');

$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS = "id, id_language";
$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  = "$latest_id, $id_language";

$HD_Form -> FG_QUERY_EDITION_HIDDEN_FIELDS = "id_language";
$HD_Form -> FG_QUERY_EDITION_HIDDEN_VALUE  = "$id_language";

$HD_Form -> FG_EDITION_CLAUSE = "id ='%id' ";

// if ($id_language != '' || !is_null($id_language)) {
//     $HD_Form -> FG_EDITION_CLAUSE .= "AND id_language = '$id_language'";
// }

$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("Modify the contents of the email template below.");
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this").$HD_Form->FG_INSTANCE_NAME." ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");

$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add a new mail template");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";

$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("The followings tags will be replaced in the message by the value in the database.").'<br/>';

$HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT .= '<u>'.gettext('Tags used for EPayment Warning email').': </u><br/> ' .
        '<b>$time$</b> : time of payment <br/>' .
        '<b>$paymentgateway$</b>: '.gettext('display payment gateway').'<br/>' .
        '<b>$itemAmount$</b>: '.gettext('amount of payment').'<br/>';
$HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT .= '<u>'.gettext('Tags used for Payment email').': </u><br/> ' .
        '<b>$itemName$</b>: '.gettext('name of item paid').'<br/> ' .
        '<b>$itemID$</b> : '.gettext('id of customer').'<br/> ' .
        '<b>$paymentMethod$</b> : '.gettext('methode of payment').'<br/> ' .
        '<b>$paymentStatus$</b> : '.gettext('status of payment').'<br/>' .
        '<b>$itemAmount$</b>: '.gettext('amount of payment').'<br/>';
$HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT .= '<u>'.gettext('Tags used for Signup').': </u><br/> ' .
        '<b>$loginkey$</b>: '.gettext('key created to validate the signup').'<br/> ';
$HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT .= '<u>'.gettext('Tag used for Did Payment confirmation email').': </u><br/> ' .
        '<b>$balance_remaining$</b>: '.gettext('balance after payment').'<br/> ';
$HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT .= '<u>'.gettext('Tag used for Did not payment warning email').': </u><br/> ' .
        '<b>$days_remaining$</b>: '.gettext('number of day before release of the did').'<br/> '.
        '<b>$invoice_ref$</b>: '.gettext('invoice reference to pay for the did').'<br/> ';
$HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT .= '<u>'.gettext('Tag used for DID').': </u><br/> ' .
        '<b>$did$</b>: '.gettext('did number').'<br/> '.
        '<b>$did_cost$</b>: '.gettext('cost of the did').'<br/> ';
$HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT .= '<u>'.gettext('Tag used for Support Ticket').': </u><br/> ' .
        '<b>$ticket_id$</b>: '.gettext('Ticket Id').'<br/> '.
        '<b>$ticket_owner$</b>: '.gettext('Ticket Owner').'<br/> '.
        '<b>$ticket_priority$</b>: '.gettext('Ticket Priority').'<br/> '.
        '<b>$ticket_status$</b>: '.gettext('Ticket Status').'<br/> '.
        '<b>$ticket_title$</b>: '.gettext('Ticket Title').'<br/> '.
        '<b>$ticket_description$</b>: '.gettext('Ticket Description').'<br/> '.
        '<b>$comment_creator$</b>: '.gettext('Comment Creator').'<br/> '.
        '<b>$comment_description$</b>: '.gettext('Comment Description').'<br/> ';
$HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT .= '<u>'.gettext('Tags used in all emails').': </u><br/> ' .
        '<b>$email$</b>: '.gettext('email of the customer').'<br/>' .
        '<b>$firstname$</b>: '.gettext('firstname of the customer').'<br/>' .
        '<b>$lastname$</b>: '.gettext('lastname of the customer').'<br/>' .
        '<b>$credit$</b>: '.gettext('credit of the customer in the system currency').'<br/>' .
        '<b>$creditcurrency$</b>: '.gettext('credit of the customer in the own currency').'<br/>' .
        '<b>$currency$</b>: '.gettext('currency of the customer').'<br/>' .
        '<b>$cardnumber$</b>: '.gettext('card number of the customer').'<br/>' .
        '<b>$password$</b>: '.gettext('password of the customer').'<br/>' .
        '<b>$login$</b>: '.gettext('login of the customer').'<br/>' .
        '<b>$credit_notification$</b>: '.gettext('credit notification of the customer').'<br/>' .
        '<b>$base_currency$</b>: '.gettext('base currency of system').'<br/>';

$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT ;


$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&languages=en&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&languages=en&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&languages=en&id=";
