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

ALTER TABLE cc_charge DROP currency;
ALTER TABLE cc_subscription_fee DROP currency;  
ALTER TABLE cc_ui_authen ADD country VARCHAR(40) NULL;
ALTER TABLE cc_ui_authen ADD city VARCHAR(40) NULL;

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES('Option CallerID update', 'callerid_update', '0', 'Prompt the caller to update his callerID', 1, 'yes,no', 'agi-conf1');

DELETE FROM cc_config WHERE config_key = 'paymentmethod' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'personalinfo' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'customerinfo' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'password' AND config_group_title = 'webcustomerui';
UPDATE cc_card_group SET users_perms = '262142' WHERE cc_card_group.id = 1;

CREATE TABLE cc_subscription_signup (
	id 				BIGSERIAL,
	label 			VARCHAR(50) NOT NULL,
	id_subscription BIGINT NULL,
	description 	TEXT NULL,
	enable 			SMALLINT NOT NULL DEFAULT '1',
	PRIMARY KEY ( id )
);

DELETE FROM cc_config WHERE config_key = 'currency_cents_association';
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
	VALUES ('Cents Currency Associated', 'currency_cents_association', 'usd:prepaid-cents,eur:prepaid-cents,gbp:prepaid-pence,all:credit', 'Define all the audio (without file extensions) that you want to play according to cents currency (use , to separate, ie "amd:lumas").By default the file used is "prepaid-cents" .Use plural to define the cents currency sound, but import two sounds but cents currency defined : ending by ''s'' and not ending by ''s'' (i.e. for lumas , add 2 files : ''lumas'' and ''luma'') ', '0', NULL, 'ivr_creditcard');
DELETE FROM cc_config WHERE config_key = 'currency_association_minor';


-- Local Dialing Normalisation
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES 
	('Option Local Dialing', 'local_dialing_addcountryprefix', '0', 'Add the countryprefix of the user in front of the dialed number if this one have only 1 leading zero', 1, 'yes,no', 'agi-conf1');


-- Remove E-Product from 1.4.1
DROP TABLE cc_ecommerce_product;

INSERT INTO cc_invoice_conf (key_val , value) VALUES ('display_account', '0');

-- add missing agent field
ALTER TABLE cc_system_log ADD agent SMALLINT DEFAULT 0;

DELETE FROM cc_config WHERE config_key = 'show_icon_invoice';
DELETE FROM cc_config WHERE config_key = 'show_top_frame';

-- add MXN currency on Paypal
UPDATE cc_configuration SET set_function = 'tep_cfg_select_option(array(''Selected Currency'',''USD'',''CAD'',''EUR'',''GBP'',''JPY'',''MXN''), ' WHERE configuration_key = 'MODULE_PAYMENT_PAYPAL_CURRENCY';


-- DID CALL AND BILLING
ALTER TABLE cc_didgroup DROP COLUMN iduser;
ALTER TABLE cc_didgroup ADD COLUMN connection_charge DECIMAL( 15, 5 ) NOT NULL DEFAULT '0';
ALTER TABLE cc_didgroup ADD COLUMN selling_rate DECIMAL( 15, 5 ) NOT NULL DEFAULT '0';

ALTER TABLE cc_did ADD CONSTRAINT cc_did_uniq UNIQUE (did);

INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_listvalues ,config_group_title)
VALUES ('Call to free DID Dial Command Params', 'dialcommand_param_call_2did', '|60|HiL(%timeout%:61000:30000)',  '%timeout% is the value of the paramater : ''Max time to Call a DID no billed''', '0', NULL , 'agi-conf1');
INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_listvalues ,config_group_title)
VALUES ('Max time to Call a DID no billed', 'max_call_call_2_did', '3600', 'max time to call a did of the system and not billed . this max value is in seconde and by default (3600 = 1HOUR MAX CALL).', '0', NULL , 'agi-conf1');


-- remove the Signup Link option
Delete from cc_config where config_key='signup_page_url';

-- remove the old auto create card feature
Delete from cc_config where config_key='cid_auto_create_card';
Delete from cc_config where config_key='cid_auto_create_card_len';
Delete from cc_config where config_key='cid_auto_create_card_typepaid';
Delete from cc_config where config_key='cid_auto_create_card_credit';
Delete from cc_config where config_key='cid_auto_create_card_credit_limit';
Delete from cc_config where config_key='cid_auto_create_card_tariffgroup';

-- Set Qualify at No per default
UPDATE cc_config SET config_value='no' WHERE config_key='qualify';


-- Update Paypal URL API
UPDATE cc_config SET config_value='https://www.paypal.com/cgi-bin/webscr' WHERE config_key='paypal_payment_url';

ALTER TABLE cc_didgroup DROP COLUMN connection_charge;
ALTER TABLE cc_didgroup DROP COLUMN selling_rate;


ALTER TABLE cc_did ADD COLUMN connection_charge DECIMAL( 15, 5 ) NOT NULL DEFAULT '0';
ALTER TABLE cc_did ADD COLUMN selling_rate DECIMAL( 15, 5 ) NOT NULL DEFAULT '0';

ALTER TABLE cc_billing_customer ADD COLUMN start_date TIMESTAMP NULL ;

-- Commit the whole update;  psql will automatically rollback if we failed at any point
COMMIT;
