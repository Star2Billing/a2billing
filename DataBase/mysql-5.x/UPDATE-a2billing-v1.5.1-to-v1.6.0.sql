
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


ALTER TABLE cc_subscription_fee ADD startdate TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
ADD stopdate TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00';

RENAME TABLE cc_subscription_fee  TO cc_subscription_service ;
ALTER TABLE cc_card_subscription ADD paid_status TINYINT NOT NULL DEFAULT '0' ;
ALTER TABLE cc_card_subscription ADD last_run TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE cc_card_subscription ADD next_billing_date TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00';
UPDATE cc_card_subscription SET next_billing_date = NOW();
ALTER TABLE cc_card_subscription ADD limit_pay_date TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00';


INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
			VALUES ('Days to bill before month anniversary', 'subscription_bill_days_before_anniversary', '3',
					'Numbers of days to bill a subscription service before the month anniversary', 0, NULL, 'global');

ALTER TABLE cc_templatemail CHANGE subject subject VARCHAR( 130 ) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL;

INSERT INTO cc_templatemail (id_language, mailtype, fromemail, fromname, subject, messagetext)
VALUES  ('en', 'subscription_paid', 'info@mydomainname.com', 'COMPANY NAME',
'Subscription notification - $subscription_label$ ($subscription_id$)',
'BALANCE  $credit$ $base_currency$\n\n
A decrement of: $subscription_fee$ $base_currency$ has removed from your account to pay your service. ($subscription_label$)\n\n
the monthly cost is : $subscription_fee$\n\n'),

('en', 'subscription_unpaid', 'info@mydomainname.com', 'COMPANY NAME',
'Subscription notification - $subscription_label$ ($subscription_id$)',
'BALANCE $credit$ $base_currency$\n\n
You do not have enough credit to pay your subscription,($subscription_label$), the monthly cost is : $subscription_fee$ $base_currency$\n\n
You have $days_remaining$ days to pay the invoice (REF: $invoice_ref$ ) or your service may cease \n\n'),

('en', 'subscription_disable_card', 'info@mydomainname.com', 'COMPANY NAME',
'Service deactivated - unpaid service $subscription_label$ ($subscription_id$)',
'The account has been automatically deactivated until the invoice is settled.\n\n');



ALTER TABLE cc_subscription_service CHANGE label label VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;
ALTER TABLE cc_subscription_service CHANGE emailreport emailreport VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;
ALTER TABLE cc_subscription_signup CHANGE description description VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL;



INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES ('Enable info module about system', 'system_info_enable', 'LEFT', 'Enabled this if you want to display the info module and place it somewhere on the Dashboard.', 0, 'NONE,LEFT,CENTER,RIGHT', 'dashboard');

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES ('Enable news module', 'news_enabled','RIGHT','Enabled this if you want to display the news module and place it somewhere on the Dashboard.', 0, 'NONE,LEFT,CENTER,RIGHT', 'dashboard');



# update destination field to a BIGINT
ALTER TABLE cc_ratecard CHANGE destination destination BIGINT( 20 ) NULL DEFAULT '0';


# query_type : 1 SQL ; 2 for shell script
# result_type : 1 Text2Speech, 2 Date, 3 Number, 4 Digits
CREATE TABLE cc_monitor (
	id BIGINT NOT NULL auto_increment,
	label VARCHAR( 50 ) collate utf8_bin NOT NULL ,
	dial_code INT NULL ,
	description VARCHAR( 250 ) collate utf8_bin NULL,
	text_intro VARCHAR( 250 ) collate utf8_bin NULL,
	query_type TINYINT NOT NULL DEFAULT '1',
	query VARCHAR( 1000 ) collate utf8_bin NULL,
	result_type TINYINT NOT NULL DEFAULT '1',
	enable TINYINT NOT NULL DEFAULT '1',
	PRIMARY KEY ( id )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO cc_monitor (label, dial_code, description, text_intro, query_type, query, result_type, enable) VALUES
('TotalCall', 2, 'To say the total amount of calls', 'The total amount of calls on your system is', 1, 'select count(*) from cc_call;', 3, 1),
('Say Time', 1, 'just saying the current date and time', 'The current date and time is', 1, 'SELECT UNIX_TIMESTAMP( );', 2, 1),
('Test Connectivity', 3, 'Test Connectivity with Google', 'your Internet connection is', 2, 'check_connectivity.sh', 1, 1);


INSERT INTO cc_config ( config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES
( 'Busy Timeout', 'busy_timeout', '1', 'Define the timeout in second when indicate the busy condition', 0, NULL, 'agi-conf1');


ALTER TABLE cc_subscription_signup ADD id_callplan BIGINT;



INSERT INTO cc_config ( config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES
( 'Callback Reduce Balance', 'callback_reduce_balance', '1', 'Define the amount to reduce the balance on Callback in order to make sure that the B leg wont alter the account into a negative value.', 0, NULL, 'agi-conf1');


UPDATE cc_version SET version = '1.6.0';

