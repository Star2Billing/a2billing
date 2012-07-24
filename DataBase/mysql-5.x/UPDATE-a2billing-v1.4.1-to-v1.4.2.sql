
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


CREATE TABLE cc_message_agent (
    id BIGINT NOT NULL AUTO_INCREMENT ,
    id_agent INT NOT NULL ,
    message LONGTEXT CHARACTER SET utf8 COLLATE utf8_bin NULL ,
    type TINYINT NOT NULL DEFAULT '0' ,
    logo TINYINT NOT NULL DEFAULT '1',
    order_display INT NOT NULL ,
    PRIMARY KEY ( id )
) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


INSERT INTO cc_config ( config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES( 'Auto Create Card', 'cid_auto_create_card', '0', 'if the callerID is captured on a2billing, this option will create automatically a new card and add the callerID to it.', 1, 'yes,no', 'agi-conf1');
INSERT INTO cc_config ( config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES( 'Auto Create Card Length', 'cid_auto_create_card_len', '10', 'set the length of the card that will be auto create (ie, 10).', 0, NULL, 'agi-conf1');
INSERT INTO cc_config ( config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES( 'Auto Create Card Type', 'cid_auto_create_card_typepaid', 'PREPAID', 'billing type of the new card( value : POSTPAID or PREPAID) .', 0, NULL, 'agi-conf1');
INSERT INTO cc_config ( config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES( 'Auto Create Card Credit', 'cid_auto_create_card_credit', '0', 'amount of credit of the new card.', 0, NULL, 'agi-conf1');
INSERT INTO cc_config ( config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES( 'Auto Create Card Limit', 'cid_auto_create_card_credit_limit', '0', 'if postpay, define the credit limit for the card.', 0, NULL, 'agi-conf1');
INSERT INTO cc_config ( config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES( 'Auto Create Card TariffGroup', 'cid_auto_create_card_tariffgroup', '1', 'the tariffgroup to use for the new card (this is the ID that you can find on the admin web interface) .', 0, NULL, 'agi-conf1');

INSERT INTO cc_config (id ,config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_listvalues ,config_group_title)
    VALUES  (NULL , 'Paypal Amount Subscription', 'paypal_subscription_amount', '10' , 'amount to billed each recurrence of payment ', '0', NULL , 'epayment_method'),
	    (NULL , 'Paypal Subscription Time period number', 'paypal_subscription_period_number', '1', 'number of time periods between each recurrence', '0', NULL , 'epayment_method'),
	    (NULL , 'Paypal Subscription Time period', 'paypal_subscription_time_period', 'M', 'time period (D=days, W=weeks, M=months, Y=years)', '0', NULL , 'epayment_method'),
	    (NULL , 'Enable PayPal subscription', 'paypal_subscription_enabled', '0', 'Enable Paypal subscription on the User home page, you need a Premier or Business account.', '1', 'yes,no', 'epayment_method'),
	    (NULL , 'Paypal Subscription account', 'paypal_subscription_account', '', 'Your PayPal ID or an email address associated with your PayPal account. Email addresses must be confirmed and bound to a Premier or Business Verified Account.', '0', NULL , 'epayment_method');


-- make sure we disabled Authorize
DELETE FROM cc_payment_methods where payment_filename = 'authorizenet.php';

ALTER TABLE cc_templatemail ADD PRIMARY KEY ( id )  ;
ALTER TABLE cc_templatemail CHANGE id id INT( 11 ) NOT NULL AUTO_INCREMENT  ;

INSERT INTO cc_templatemail (id_language, mailtype, fromemail, fromname, subject, messagetext)
    VALUES	('en', 'did_paid', 'info@mydomainname.com', 'COMPANY NAME', 'DID notification - ($did$)', 'BALANCE REMAINING $balance_remaining$ $base_currency$\n\nAn automatic taking away of : $did_cost$ $base_currency$ has been carry out of your account to pay your DID ($did$)\n\nMonthly cost for DID : $did_cost$ $base_currency$\n\n'),
		('en', 'did_unpaid', 'info@mydomainname.com', 'COMPANY NAME', 'DID notification - ($did$)', 'BALANCE REMAINING $balance_remaining$ $base_currency$\n\nYour credit is not enough to pay your DID number ($did$), the monthly cost is : $did_cost$ $base_currency$\n\nYou have $days_remaining$ days to pay the invoice (REF: $invoice_ref$ ) or the DID will be automatically released \n\n'),
		('en', 'did_released', 'info@mydomainname.com', 'COMPANY NAME', 'DID released - ($did$)', 'The DID $did$ has been automatically released!\n\n');		


DELETE FROM cc_configuration WHERE configuration_key = 'MODULE_PAYMENT_PAYPAL_CURRENCY';
DELETE FROM cc_configuration WHERE configuration_key = 'MODULE_PAYMENT_MONEYBOOKERS_CURRENCY';

ALTER TABLE cc_support ADD email VARCHAR( 70 ) CHARACTER SET utf8 COLLATE utf8_bin NULL ;
ALTER TABLE cc_support ADD language CHAR( 5 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'en';
INSERT INTO cc_templatemail (id_language, mailtype, fromemail, fromname, subject, messagetext)
    VALUES	('en', 'new_ticket', 'info@mydomainname.com', 'COMPANY NAME', 'Support Ticket #$ticket_id$', 'New Ticket Open (#$ticket_id$) From $ticket_owner$.\n Title : $ticket_title$\n Priority : $ticket_priority$ \n Status : $ticket_status$ \n Description : $ticket_description$ \n'),
		('en', 'modify_ticket', 'info@mydomainname.com', 'COMPANY NAME', 'Support Ticket #$ticket_id$', 'Ticket modified (#$ticket_id$) By $comment_creator$.\n Ticket Status -> $ticket_status$\n Description : $comment_description$ \n');
DELETE FROM cc_templatemail WHERE mailtype = 'invoice';
INSERT INTO cc_templatemail (id_language, mailtype, fromemail, fromname, subject, messagetext)
    VALUES	('en', 'invoice_to_pay', 'info@mydomainname.com', 'COMPANY NAME', 'Invoice to pay Ref: $invoice_reference$', 
    'New Invoice send with the reference : $invoice_reference$ .\n 
    Title : $invoice_title$ .\n Description : $invoice_description$\n 
    TOTAL (exclude VAT) : $invoice_total$  $base_currency$\n TOTAL (invclude VAT) : $invoice_total_vat$ $base_currency$ \n\n 
    TOTAL TO PAY : $invoice_total_vat$ $base_currency$\n\n 
    You can check and pay this invoice by your account on the web interface : http://mydomainname.com/customer/  ');



