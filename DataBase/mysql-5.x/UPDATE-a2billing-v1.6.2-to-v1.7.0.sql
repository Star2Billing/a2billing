
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


INSERT INTO cc_config (id, config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES (NULL, 'Language field', 'field_language', '1', 'Enable The Language Field -  Yes 1 - No 0.', '1', 'yes,no', 'signup');

INSERT INTO cc_config (id, config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES (NULL, 'Currency field', 'field_currency', '1', 'Enable The Currency Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (id, config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES (NULL, 'Last Name Field', 'field_lastname', '1', 'Enable The Last Name Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (id, config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES (NULL, 'First Name Field', 'field_firstname', '1', 'Enable The First Name Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (id, config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES (NULL, 'Address Field', 'field_address', '1', 'Enable The Address Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (id, config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES (NULL, 'City Field', 'field_city', '1', 'Enable The City Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (id, config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES (NULL, 'State Field', 'field_state', '1', 'Enable The State Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (id, config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES (NULL, 'Country Field', 'field_country', '1', 'Enable The Country Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (id, config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES (NULL, 'Zipcode Field', 'field_zipcode', '1', 'Enable The Zipcode Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (id, config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES (NULL, 'Timezone Field', 'field_id_timezone', '1', 'Enable The Timezone Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (id, config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES (NULL, 'Phone Field', 'field_phone', '1', 'Enable The Phone Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (id, config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES (NULL, 'Fax Field', 'field_fax', '1', 'Enable The Fax Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (id, config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES (NULL, 'Company Name Field', 'field_company', '1', 'Enable The Company Name Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (id, config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES (NULL, 'Company Website Field', 'field_company_website', '1', 'Enable The Company Website Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (id, config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES (NULL, 'VAT Registration Number Field', 'field_VAT_RN', '1', 'Enable The VAT Registration Number Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (id, config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES (NULL, 'Traffic Field', 'field_traffic', '1', 'Enable The Traffic Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (id, config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES (NULL, 'Traffic Target Field', 'field_traffic_target', '1', 'Enable The Traffic Target Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');


-- fix Realtime Bug, Permit have to be after Deny
ALTER TABLE cc_sip_buddies MODIFY COLUMN permit varchar(95) AFTER deny;
ALTER TABLE cc_iax_buddies MODIFY COLUMN permit varchar(95) AFTER deny;


-- Locking features
ALTER TABLE cc_card ADD block TINYINT NOT NULL DEFAULT '0';
ALTER TABLE cc_card ADD lock_pin VARCHAR( 15 ) NULL DEFAULT NULL;
ALTER TABLE cc_card ADD lock_date timestamp NULL;


INSERT INTO cc_config ( config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES( 'IVR Locking option', 'ivr_enable_locking_option', '0', 'Enable the IVR which allow the users to lock their account with an extra lock code.', 1, 'yes,no', 'agi-conf1');

INSERT INTO cc_config ( config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES( 'IVR Account Information', 'ivr_enable_account_information', '0', 'Enable the IVR which allow the users to retrieve different information about their account.', 1, 'yes,no', 'agi-conf1');

INSERT INTO cc_config ( config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES( 'IVR Speed Dial', 'ivr_enable_ivr_speeddial', '0', 'Enable the IVR which allow the users add speed dial.', 1, 'yes,no', 'agi-conf1');


ALTER TABLE cc_templatemail CHANGE messagetext messagetext VARCHAR( 3000 ) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL;
ALTER TABLE cc_templatemail CHANGE messagehtml messagehtml VARCHAR( 3000 ) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL;

ALTER TABLE cc_card_group CHANGE description description VARCHAR( 400 ) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL;

ALTER TABLE cc_config CHANGE config_description config_description VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL;


-- Update Version
UPDATE cc_version SET version = '1.7.0';
