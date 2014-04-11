<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2012 - Star2billing S.L.
 * @author      Belaid Rachid <rachid.belaid@gmail.com>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 * @package     A2Billing
 * @contributor Belaid Arezqui <areski@gmail.com>
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

class A2bMailException extends Exception
{
}

class Mail
{
    private $id_card;
    private $message = '';
    private $title = '';
    private $from_email = '';
    private $from_name = '';
    private $to_email = '';

    //mail type
    public static $TYPE_PAYMENT = 'payment';
    public static $TYPE_REMINDER = 'reminder';
    public static $TYPE_SIGNUP = 'signup';
    public static $TYPE_FORGETPASSWORD = 'forgetpassword';
    public static $TYPE_SIGNUPCONFIRM = 'signupconfirmed';
    public static $TYPE_EPAYMENTVERIFY = 'epaymentverify';
    public static $TYPE_REMINDERCALL = 'reminder';
    public static $TYPE_SUBSCRIPTION_PAID = 'subscription_paid';
    public static $TYPE_SUBSCRIPTION_UNPAID = 'subscription_unpaid';
    public static $TYPE_SUBSCRIPTION_DISABLE_CARD = 'subscription_disable_card';

    public static $TYPE_DID_PAID = 'did_paid';
    public static $TYPE_DID_UNPAID = 'did_unpaid';
    public static $TYPE_DID_RELEASED = 'did_released';
    public static $TYPE_TICKET_NEW = 'new_ticket';
    public static $TYPE_TICKET_MODIFY = 'modify_ticket';
    public static $TYPE_INVOICE_TO_PAY = 'invoice_to_pay';

    //Used by mail type = invoice_to_pay
    public static $INVOICE_TITLE_KEY = '$invoice_title$';
    public static $INVOICE_REFERENCE_KEY = '$invoice_reference$';
    public static $INVOICE_DESCRIPTION_KEY = '$invoice_description$';
    public static $INVOICE_TOTAL_KEY = '$invoice_total$';
    public static $INVOICE_TOTAL_VAT_KEY = '$invoice_total_vat$';

    //Used by mail type = new_ticket AND modify_ticket
    public static $TICKET_NUMBER_KEY = '$ticket_id$';
    public static $TICKET_OWNER_KEY = '$ticket_owner$';
    public static $TICKET_PRIORITY_KEY = '$ticket_priority$';
    public static $TICKET_STATUS_KEY = '$ticket_status$';
    public static $TICKET_TITLE_KEY = '$ticket_title$';
    public static $TICKET_DESCRIPTION_KEY = '$ticket_description$';

    //Used by mail type = modify_ticket
    public static $TICKET_COMMENT_CREATOR_KEY = '$comment_creator$';
    public static $TICKET_COMMENT_DESCRIPTION_KEY = '$comment_description$';

    //Used by mail type = did_paid
    public static $BALANCE_REMAINING_KEY = '$balance_remaining$';

    //Used by mail type = subscription_paid OR subscription_unpaid
    public static $SUBSCRIPTION_LABEL = '$subscription_label$';
    public static $SUBSCRIPTION_ID = '$subscription_id$';
    public static $SUBSCRIPTION_FEE = '$subscription_fee$';

    //Used by mail type = did_paid OR did_unpaid OR did_released
    public static $DID_NUMBER_KEY = '$did$';
    public static $DID_COST_KEY = '$did_cost$';

    //Used by mail type = did_unpaid  & subscription_unpaid
    public static $DAY_REMAINING_KEY = '$days_remaining$';
    public static $INVOICE_REF_KEY = '$invoice_ref$';

    //Used by mail type = epaymentverify
    public static $TIME_KEY = '$time$';
    public static $PAYMENTGATEWAY_KEY = '$paymentgateway$';

    //Used by mail type = payment
    public static $ITEM_NAME_KEY = '$itemName$';
    public static $ITEM_ID_KEY = '$itemID$';
    public static $PAYMENT_METHOD_KEY = '$paymentMethod$';
    public static $PAYMENT_STATUS_KEY = '$paymentStatus$';

    //used by type = payment and type = epaymentverify
    public static $ITEM_AMOUNT_KEY = '$itemAmount$';

    //used in all mail
    public static $CUSTOMER_EMAIL_KEY = '$email$';
    public static $CUSTOMER_FIRSTNAME_KEY = '$firstname$';
    public static $CUSTOMER_LASTNAME_KEY = '$lastname$';
    public static $CUSTOMER_CREDIT_BASE_CURRENCY_KEY = '$credit$';
    public static $CUSTOMER_CREDIT_IN_OWN_CURRENCY_KEY = '$creditcurrency$';
    public static $CUSTOMER_CURRENCY = '$currency$';
    public static $CUSTOMER_CARDNUMBER_KEY = '$cardnumber$';
    public static $CUSTOMER_PASSWORD_KEY = '$password$';
    public static $CUSTOMER_LOGIN = '$login$';
    public static $CUSTOMER_LOGINKEY = '$loginkey$';
    public static $CUSTOMER_CREDIT_NOTIFICATION = '$credit_notification$';

    //used in all mail
    public static $SYSTEM_CURRENCY = '$base_currency$';

    public function __construct($type, $id_card = null, $lg = null, $msg = null, $title = null)
    {
        $DBHandle = Connection::GetDBHandler();

        if (!empty ($type)) {
            $tmpl_table = new Table("cc_templatemail", "*");
            $tmpl_clause = " mailtype = '$type'";
            $order = null;
            $order_field = null;
            if (!empty ($lg)) {
                $tmpl_clause .= " AND ( id_language = '$lg' OR  id_language = 'en' )";
                $order_field = 'id_language';
                if (strcasecmp($lg, 'en') < 0) {
                    $order = 'ASC';
                } else {
                    $order = 'DESC';
                }
            } elseif (!is_null($id_card) && is_numeric($id_card)) {
                $card_table = new Table("cc_card", "*, IF((typepaid=1) AND (creditlimit IS NOT NULL), credit + creditlimit, credit) AS real_credit");
                $card_clause = " id = " . $id_card;
                $result_card = $card_table->Get_list($DBHandle, $card_clause, 0);
                if (is_array($result_card) && sizeof($result_card) > 0)
                    $card = $result_card[0];
                $language = $card['language'];
                if (!empty ($language)) {
                    $tmpl_clause .= " AND ( id_language = '$language' OR  id_language = 'en' )";
                    $order_field = 'id_language';
                    if (strcasecmp($language, 'en') < 0) {
                        $order = 'ASC';
                    } else {
                        $order = 'DESC';
                    }
                }
            }
            $result_tmpl = $tmpl_table->Get_list($DBHandle, $tmpl_clause, $order_field, $order);
            if (is_array($result_tmpl) && sizeof($result_tmpl) > 0) {
                $mail_tmpl = $result_tmpl[0];
                $this->message = $mail_tmpl['messagetext'];
                $this->title = $mail_tmpl['subject'];
                $this->from_email = $mail_tmpl['fromemail'];
                $this->from_name = $mail_tmpl['fromname'];
            } else {
                throw new A2bMailException("Template Type '$type' cannot be found into the database!");
            }
        } elseif (!empty ($msg) || !empty ($title)) {
            $this->message = $msg;
            $this->title = $title;
        } else {
            throw new A2bMailException("Error : no Type defined and neither message or subject is provided!");
        }
        if (!empty ($this->message) || !empty ($this->title)) {
            if (!is_null($id_card) && is_numeric($id_card)) {
                $this->id_card = $id_card;
                if (is_null($card)) {
                    $card_table = new Table("cc_card", "*, IF((typepaid=1) AND (creditlimit IS NOT NULL), credit + creditlimit, credit) AS real_credit");
                    $card_clause = " id = " . $id_card;
                    $result_card = $card_table->Get_list($DBHandle, $card_clause, 0);
                    if (is_array($result_card) && sizeof($result_card) > 0)
                        $card = $result_card[0];
                }
                $credit = $card['real_credit'];
                $credit = round($credit, 3);
                $currency = $card['currency'];
                $currencies_list = get_currencies($DBHandle);
                if (!isset ($currencies_list[strtoupper($currency)][2]) || !is_numeric($currencies_list[strtoupper($currency)][2])) {
                    $mycur = 1;
                } else {
                    $mycur = $currencies_list[strtoupper($currency)][2];
                }
                $credit_currency = $credit / $mycur;
                $credit_currency = round($credit_currency, 3);
                $this->to_email = $card['email'];
                $this->replaceInEmail(self :: $CUSTOMER_CARDNUMBER_KEY, $card['username']);
                $this->replaceInEmail(self :: $CUSTOMER_EMAIL_KEY, $card['email']);
                $this->replaceInEmail(self :: $CUSTOMER_FIRSTNAME_KEY, $card['firstname']);
                $this->replaceInEmail(self :: $CUSTOMER_LASTNAME_KEY, $card['lastname']);
                $this->replaceInEmail(self :: $CUSTOMER_LOGIN, $card['useralias']);
                $this->replaceInEmail(self :: $CUSTOMER_LOGINKEY, $card['loginkey']);
                $this->replaceInEmail(self :: $CUSTOMER_PASSWORD_KEY, $card['uipass']);
                $this->replaceInEmail(self :: $CUSTOMER_CREDIT_IN_OWN_CURRENCY_KEY, $credit_currency);
                $this->replaceInEmail(self :: $CUSTOMER_CREDIT_BASE_CURRENCY_KEY, $credit);
                $this->replaceInEmail(self :: $CUSTOMER_CURRENCY, $currency);
                $this->replaceInEmail(self :: $CUSTOMER_CREDIT_NOTIFICATION, $card['credit_notification']);

            }
            $this->replaceInEmail(self :: $SYSTEM_CURRENCY, BASE_CURRENCY);
        }
    }

    public function replaceInEmail($key, $val)
    {
        $this->message = str_replace($key, $val, $this->message);
        $this->title = str_replace($key, $val, $this->title);
    }

    public function getIdCard()
    {
        return $this->id_card;
    }

    public function getFromEmail()
    {
        return $this->from_email;
    }

    public function getToEmail()
    {
        return $this->to_email;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function AddToMessage($msg)
    {
        $this->message = $this->message . $msg;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getFromName()
    {
        return $this->from_name;
    }

    public function setFromEmail($from_email)
    {
        $this->from_email = $from_email;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setToEmail($to_email)
    {
        $this->to_email = $to_email;
    }

    public function setFromName($from_name)
    {
        $this->from_name = $from_name;
    }

    public function send($to_email = null)
    {
        if (!empty ($to_email)) {
            $this->to_email = $to_email;
        }
        try {
            a2b_mail($this->to_email, $this->title, $this->message, $this->from_email, $this->from_name);
        } catch (phpmailerException $e) {
            throw new A2bMailException("Error sent mail : ".$e->getMessage()."\n");
        }

    }

}
