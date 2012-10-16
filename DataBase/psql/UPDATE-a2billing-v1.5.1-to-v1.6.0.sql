/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2012 - Star2billing S.L.
 * @author      Hironobu Suzuki <hironobu@interdb.jp> / Belaid Arezqui <areski@gmail.com>
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

--
-- A2Billing database script - Update database for Postgres
--
--

\set ON_ERROR_STOP ON;

-- Wrap the whole update in a transaction so everything is reverted upon failure
BEGIN;

ALTER TABLE cc_subscription_fee ADD COLUMN startdate TIMESTAMP NOT NULL DEFAULT '1970-01-01 00:00:00';
ALTER TABLE cc_subscription_fee ADD COLUMN stopdate TIMESTAMP NOT NULL DEFAULT '1970-01-01 00:00:00';

ALTER TABLE cc_subscription_fee  RENAME TO cc_subscription_service;
ALTER TABLE cc_card_subscription ADD COLUMN paid_status SMALLINT NOT NULL DEFAULT 0;
ALTER TABLE cc_card_subscription ADD COLUMN last_run TIMESTAMP NOT NULL DEFAULT '1970-01-01 00:00:00';
ALTER TABLE cc_card_subscription ADD COLUMN next_billing_date TIMESTAMP NOT NULL DEFAULT '1970-01-01 00:00:00';

UPDATE cc_card_subscription SET next_billing_date = NOW();
ALTER TABLE cc_card_subscription ADD COLUMN limit_pay_date TIMESTAMP NOT NULL DEFAULT '1970-01-01 00:00:00';

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
			VALUES ('Days to bill before month anniversary', 'subscription_bill_days_before_anniversary', '3',
					'Numbers of days to bill a subscription service before the month anniversary', 0, NULL, 'global');

ALTER TABLE cc_templatemail ALTER COLUMN subject TYPE VARCHAR(130);
ALTER TABLE cc_templatemail ALTER COLUMN subject SET DEFAULT NULL;

INSERT INTO cc_templatemail (id_language, mailtype, fromemail, fromname, subject, messagetext)
VALUES  ('en', 'subscription_paid', 'info@mydomainname.com', 'COMPANY NAME',
'Subscription notification - $subscription_label$ ($subscription_id$)',
'BALANCE  $credit$ $base_currency$\n\nA decrement of: $subscription_fee$ $base_currency$ has removed from your account to pay your service. ($subscription_label$)\n\nthe monthly cost is : $subscription_fee$\n\n');

INSERT INTO cc_templatemail (id_language, mailtype, fromemail, fromname, subject, messagetext)
VALUES  ('en', 'subscription_unpaid', 'info@mydomainname.com', 'COMPANY NAME',
'Subscription notification - $subscription_label$ ($subscription_id$)',
'BALANCE $credit$ $base_currency$\n\nYou do not have enough credit to pay your subscription,($subscription_label$), the monthly cost is : $subscription_fee$ $base_currency$\n\nYou have $days_remaining$ days to pay the invoice (REF: $invoice_ref$ ) or your service may cease \n\n');

INSERT INTO cc_templatemail (id_language, mailtype, fromemail, fromname, subject, messagetext)
VALUES  ('en', 'subscription_disable_card', 'info@mydomainname.com', 'COMPANY NAME',
'Service deactivated - unpaid service $subscription_label$ ($subscription_id$)',
'The account has been automatically deactivated until the invoice is settled.\n\n');


ALTER TABLE cc_subscription_service ALTER COLUMN label TYPE VARCHAR(200);
ALTER TABLE cc_subscription_service ALTER COLUMN label SET NOT NULL;
ALTER TABLE cc_subscription_service ALTER COLUMN emailreport TYPE VARCHAR(100);
ALTER TABLE cc_subscription_service ALTER COLUMN emailreport SET NOT NULL;
ALTER TABLE cc_subscription_signup ALTER COLUMN description TYPE VARCHAR(500);
ALTER TABLE cc_subscription_signup ALTER COLUMN description SET DEFAULT NULL;


INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES ('Enable info module about system', 'system_info_enable', 'LEFT', 'Enabled this if you want to display the info module and place it somewhere on the Dashboard.', 0, 'NONE,LEFT,CENTER,RIGHT', 'dashboard');

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES ('Enable news module', 'news_enabled','RIGHT','Enabled this if you want to display the news module and place it somewhere on the Dashboard.', 0, 'NONE,LEFT,CENTER,RIGHT', 'dashboard');


--- Ignore these queries. Because type of "destination" was already changed.
-- update destination field to a BIGINT
--ALTER TABLE cc_ratecard ALTER COLUMN destination TYPE BIGINT;
--ALTER TABLE cc_ratecard ALTER COLUMN destination SET DEFAULT 0;

-- query_type : 1 SQL ; 2 for shell script
-- result_type : 1 Text2Speech, 2 Date, 3 Number, 4 Digits
CREATE TABLE cc_monitor (
	id BIGSERIAL NOT NULL,
	label VARCHAR(50) NOT NULL,
	dial_code INT NULL ,
	description VARCHAR(250) NULL,
	text_intro VARCHAR(250) NULL,
	query_type SMALLINT NOT NULL DEFAULT 1,
	query VARCHAR(1000) NULL,
	result_type SMALLINT NOT NULL DEFAULT 1,
	enable SMALLINT NOT NULL DEFAULT 1,
	PRIMARY KEY ( id )
);

INSERT INTO cc_monitor (label, dial_code, description, text_intro, query_type, query, result_type, enable) VALUES
('TotalCall', 2, 'To say the total amount of calls', 'The total amount of calls on your system is', 1, 'select count(*) from cc_call;', 3, 1);
INSERT INTO cc_monitor (label, dial_code, description, text_intro, query_type, query, result_type, enable) VALUES
('Say Time', 1, 'just saying the current date and time', 'The current date and time is', 1, 'SELECT UNIX_TIMESTAMP( );', 2, 1);
INSERT INTO cc_monitor (label, dial_code, description, text_intro, query_type, query, result_type, enable) VALUES
('Test Connectivity', 3, 'Test Connectivity with Google', 'your Internet connection is', 2, 'check_connectivity.sh', 1, 1);

INSERT INTO cc_config ( config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES
( 'Busy Timeout', 'busy_timeout', '1', 'Define the timeout in second when indicate the busy condition', 0, NULL, 'agi-conf1');


ALTER TABLE cc_subscription_signup ADD COLUMN id_callplan BIGINT;



INSERT INTO cc_config ( config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES
( 'Callback Reduce Balance', 'callback_reduce_balance', '1', 'Define the amount to reduce the balance on Callback in order to make sure that the B leg wont alter the account into a negative value.', 0, NULL, 'agi-conf1');


UPDATE cc_version SET version = '1.6.0';

-- Commit the whole update;  psql will automatically rollback if we failed at any point
COMMIT;
