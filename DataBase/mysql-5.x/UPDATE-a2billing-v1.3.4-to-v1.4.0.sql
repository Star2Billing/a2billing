
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

--
-- A2Billing database script - Update database for MYSQL 5.X
--
--
-- Usage:
-- mysql -u root -p"root password" < UPDATE-a2billing-v1.3.0-to-v1.4.0.sql
--




CREATE TABLE cc_invoice_items (
	id bigint(20) NOT NULL auto_increment,
	invoiceid int(11) NOT NULL,
	invoicesection text,
	designation text,
	sub_designation text,
	start_date date default NULL,
	end_date date default NULL,
	bill_date date default NULL,
	calltime int(11) default NULL,
	nbcalls int(11) default NULL,
	quantity int(11) default NULL,
	price decimal(15,5) default NULL,
	buy_price decimal(15,5) default NULL,
	PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE cc_invoice (
	id int(11) NOT NULL auto_increment,
	cardid bigint(20) NOT NULL,
	invoicecreated_date timestamp NOT NULL default CURRENT_TIMESTAMP,
	amount decimal(15,5) default '0.00000',
	tax decimal(15,5) default '0.00000',
	total decimal(15,5) default '0.00000',
	filename varchar(250) collate utf8_bin default NULL,
	payment_status int(11) default '0',
	cover_call_startdate timestamp NOT NULL default '0000-00-00 00:00:00',
	cover_call_enddate timestamp NOT NULL default '0000-00-00 00:00:00',
	cover_charge_startdate timestamp NOT NULL default '0000-00-00 00:00:00',
	cover_charge_enddate timestamp NOT NULL default '0000-00-00 00:00:00',
	currency varchar(3) collate utf8_bin default NULL,
	previous_balance decimal(15,5) default NULL,
	current_balance decimal(15,5) default NULL,
	templatefile varchar(250) collate utf8_bin default NULL,
	username char(50) collate utf8_bin default NULL,
	lastname char(50) collate utf8_bin default NULL,
	firstname char(50) collate utf8_bin default NULL,
	address char(100) collate utf8_bin default NULL,
	city char(40) collate utf8_bin default NULL,
	state char(40) collate utf8_bin default NULL,
	country char(40) collate utf8_bin default NULL,
	zipcode char(20) collate utf8_bin default NULL,
	phone char(20) collate utf8_bin default NULL,
	email char(70) collate utf8_bin default NULL,
	fax char(20) collate utf8_bin default NULL,
	vat float default NULL,
	PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

ALTER TABLE cc_charge DROP COLUMN id_cc_subscription_fee;

ALTER TABLE cc_charge ADD COLUMN id_cc_card_subscription BIGINT;
ALTER TABLE cc_charge ADD COLUMN cover_from DATE;
ALTER TABLE cc_charge ADD COLUMN cover_to 	DATE;

ALTER TABLE cc_trunk ADD COLUMN inuse INT DEFAULT 0;
ALTER TABLE cc_trunk ADD COLUMN maxuse INT DEFAULT -1;
ALTER TABLE cc_trunk ADD COLUMN status INT DEFAULT 1;
ALTER TABLE cc_trunk ADD COLUMN if_max_use INT DEFAULT 0;


CREATE TABLE cc_card_subscription (
	id BIGINT NOT NULL AUTO_INCREMENT,
	id_cc_card BIGINT ,
	id_subscription_fee INT,
	startdate TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	stopdate TIMESTAMP,
	product_id VARCHAR( 100 ),
	product_name VARCHAR( 100 ),
	PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE cc_card DROP id_subscription_fee;
ALTER TABLE cc_card ADD COLUMN id_timezone INT DEFAULT 0;


CREATE TABLE cc_config_group (
	id 								INT NOT NULL auto_increment,
	group_title 					VARCHAR(64) NOT NULL,
	group_description 				VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO cc_config_group (group_title, group_description) VALUES ('global', 'This configuration group handles the global settings for application.');
INSERT INTO cc_config_group (group_title, group_description) VALUES ('callback', 'This configuration group handles calllback settings.');
INSERT INTO cc_config_group (group_title, group_description) VALUES ('webcustomerui', 'This configuration group handles Web Customer User Interface.');
INSERT INTO cc_config_group (group_title, group_description) VALUES ('sip-iax-info', 'SIP & IAX client configuration information.');
INSERT INTO cc_config_group (group_title, group_description) VALUES ('epayment_method', 'Epayment Methods Configuration.');
INSERT INTO cc_config_group (group_title, group_description) VALUES ('signup', 'This configuration group handles the signup related settings.');
INSERT INTO cc_config_group (group_title, group_description) VALUES ('backup', 'This configuration group handles the backup/restore related settings.');
INSERT INTO cc_config_group (group_title, group_description) VALUES ('webui', 'This configuration group handles the WEBUI and API Configuration.');
INSERT INTO cc_config_group (group_title, group_description) VALUES ('peer_friend', 'This configuration group define parameters for the friends creation.');
INSERT INTO cc_config_group (group_title, group_description) VALUES ('log-files', 'This configuration group handles the Log Files Directory Paths.');
INSERT INTO cc_config_group (group_title, group_description) VALUES ('agi-conf1', 'This configuration group handles the AGI Configuration.');



CREATE TABLE cc_config (
	id 								INT NOT NULL auto_increment,
	config_title		 			VARCHAR( 100 )  NOT NULL,
	config_key 						VARCHAR( 100 )  NOT NULL,
	config_value 					VARCHAR( 100 )  NOT NULL,
	config_description 				TEXT NOT NULL,
	config_valuetype				INT NOT NULL DEFAULT 0,
	config_group_id 				INT NOT NULL,
	config_listvalues				VARCHAR( 100 ) ,
	PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Card Number length', 'interval_len_cardnumber', '10-15', 'Card Number length, You can define a Range e.g: 10-15.', 0, 1, '10-15,5-20,10-30');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Card Alias length', 'len_aliasnumber', '15', 'Card Number Alias Length e.g: 15.', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Voucher length', 'len_voucher', '15', 'Voucher Number Length.', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Base Currency', 'base_currency', 'usd', 'Base Currency to use for application.', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Invoice Image', 'invoice_image', 'asterisk01.jpg', 'Image to Display on the Top of Invoice', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Admin Email', 'admin_email', 'root@localhost', 'Web Administrator Email Address.', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('DID Bill Payment Day', 'didbilling_daytopay', '5', 'DID Bill Payment Day of Month', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Manager Host', 'manager_host', 'localhost', 'Manager Host Address', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Manager User ID', 'manager_username', 'myasterisk', 'Manger Host User Name', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Manager Password', 'manager_secret', 'mycode', 'Manager Host Password', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Use SMTP Server', 'smtp_server', '0', 'Define if you want to use an STMP server or Send Mail (value yes for server SMTP)', 1, 1, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('SMTP Host', 'smtp_host', 'localhost', 'SMTP Hostname', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('SMTP UserName', 'smtp_username', '', 'User Name to connect on the SMTP server', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('SMTP Password', 'smtp_password', '', 'Password to connect on the SMTP server', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Use Realtime', 'use_realtime', '1', 'if Disabled, it will generate the config files and offer an option to reload asterisk after an update on the Voip settings', 1, 1, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Go To Customer', 'customer_ui_url', '../../customer/index.php', 'Link to the customer account', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Context Callback', 'context_callback', 'a2billing-callback', 'Contaxt to use in Callback', 0, 2, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Extension', 'extension', '1000', 'Extension to call while callback.', 0, 2, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Wait before callback', 'sec_wait_before_callback', '10', 'Seconds to wait before callback.', 0, 2, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Avoid Repeat Duration', 'sec_avoid_repeate', '10', 'Number of seconds before the call-back can be re-initiated from the web page to prevent repeated and unwanted calls.', 0, 2, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Time out', 'timeout', '20', 'if the callback doesnt succeed within the value below, then the call is deemed to have failed.', 0, 2, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Answer on Call', 'answer_call', '1', 'if we want to manage the answer on the call. Disabling this for callback trigger numbers makes it ring not hang up.', 1, 2, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('No of Predictive Calls', 'nb_predictive_call', '10', 'number of calls an agent will do when the call button is clicked.', 0, 2, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Delay for Availability', 'nb_day_wait_before_retry', '1', 'Number of days to wait before the number becomes available to call again.', 0, 2, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('PD Contect', 'context_preditctivedialer', 'a2billing-predictivedialer', 'The context to redirect the call for the predictive dialer.', 0, 2, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Max Time to call', 'predictivedialer_maxtime_tocall', '5400', 'When a call is made we need to limit the call duration : amount in seconds.', 0, 2, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('PD Caller ID', 'callerid', '123456', 'Set the callerID for the predictive dialer and call-back.', 0, 2, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Callback CallPlan ID', 'all_callback_tariff', '1', 'ID Call Plan to use when you use the all-callback mode, check the ID in the "list Call Plan" - WebUI.', 0, 2, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Server Group ID', 'id_server_group', '1', 'Define the group of servers that are going to be used by the callback.', 0, 2, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Audio Intro', 'callback_audio_intro', 'prepaid-callback_intro', 'Audio intro message when the callback is initiate.', 0, 2, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Signup URL', 'signup_page_url', '', 'url of the signup page to show up on the sign in page (if empty no link will show up).', 0, 3, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Payment Method', 'paymentmethod', 1, 'Enable or disable the payment methods; yes for multi-payment or no for single payment method option.', 1, 3, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Personal Info', 'personalinfo', 1, 'Enable or disable the page which allow customer to modify its personal information.', 1, 3, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Payment Info', 'customerinfo', 1, 'Enable display of the payment interface - yes or no.', 1, 3, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('SIP/IAX Info', 'sipiaxinfo', 1, 'Enable display of the sip/iax info - yes or no.', 1, 3, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('CDR', 'cdr', 1, 'Enable the Call history - yes or no.', 1, 3, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Invoices', 'invoice', 1, 'Enable invoices - yes or no.', 1, 3, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Voucher Screen', 'voucher', 1, 'Enable the voucher screen - yes or no.', 1, 3, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Paypal', 'paypal', 1, 'Enable the paypal payment buttons - yes or no.', 1, 3, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Speed Dial', 'speeddial', 1, 'Allow Speed Dial capabilities - yes or no.', 1, 3, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('DID', 'did', 1, 'Enable the DID (Direct Inwards Dialling) interface - yes or no.', 1, 3, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('RateCard', 'ratecard', 1, 'Show the ratecards - yes or no.', 1, 3, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Simulator', 'simulator', 1, 'Offer simulator option on the customer interface - yes or no.', 1, 3, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('CallBack', 'callback', 1, 'Enable the callback option on the customer interface - yes or no.', 1, 3, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Predictive Dialer', 'predictivedialer', 1, 'Enable the predictivedialer option on the customer interface - yes or no.', 1, 3, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('WebPhone', 'webphone', 1, 'Let users use SIP/IAX Webphone (Options : yes/no).', 1, 3, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('WebPhone Server', 'webphoneserver', 'localhost', 'IP address or domain name of asterisk server that would be used by the web-phone.', 0, 3, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Caller ID', 'callerid', 1, 'Let the users add new callerid.', 1, 3, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Password', 'password', 1, 'Let the user change the webui password.', 1, 3, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('CallerID Limit', 'limit_callerid', '5', 'The total number of callerIDs for CLI Recognition that can be add by the customer.', 0, 3, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Trunk Name', 'sip_iax_info_trunkname', 'mytrunkname', 'Trunk Name to show in sip/iax info.', 0, 4, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Codecs Allowed', 'sip_iax_info_allowcodec', 'g729', 'Allowed Codec, ulaw, gsm, g729.', 0, 4, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Host', 'sip_iax_info_host', 'mydomainname.com', 'Host information.', 0, 4, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('IAX Parms', 'iax_additional_parameters', 'canreinvite = no', 'IAX Additional Parameters.', 0, 4, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('SIP Parms', 'sip_additional_parameters', 'trustrpid = yes | sendrpid = yes | canreinvite = no', 'SIP Additional Parameters.', 0, 4, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Enable', 'enable', 1, 'Enable/Disable.', 1, 5, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('HTTP Server Customer', 'http_server', 'http://www.mydomainname.com', 'Set the Server Address of Customer Website, It should be empty for productive Servers.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('HTTPS Server Customer', 'https_server', 'https://www.mydomainname.com', 'https://localhost - Enter here your Secure Customers Server Address, should not be empty for productive servers.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Server Customer IP/Domain', 'http_cookie_domain', '26.63.165.200', 'Enter your Domain Name or IP Address for the Customers application, eg, 26.63.165.200.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Secure Server Customer IP/Domain', 'https_cookie_domain', '26.63.165.200', 'Enter your Secure server Domain Name or IP Address for the Customers application, eg, 26.63.165.200.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Application Customer Path', 'http_cookie_path', '/customer/', 'Enter the Physical path of your Customers Application on your server.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Secure Application Customer Path', 'https_cookie_path', '/customer/', 'Enter the Physical path of your Customers Application on your Secure Server.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Application Customer Physical Path', 'dir_ws_http_catalog', '/customer/', 'Enter the Physical path of your Customers Application on your server.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Secure Application Customer Physical Path', 'dir_ws_https_catalog', '/customer/', 'Enter the Physical path of your Customers Application on your Secure server.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Enable SSL', 'enable_ssl', 1, 'secure webserver for checkout procedure?', 1, 5, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('HTTP Domain', 'http_domain', '26.63.165.200', 'Http Address.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Directory Path', 'dir_ws_http', '/customer/', 'Directory Path.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Payment Amount', 'purchase_amount', '1:2:5:10:20', 'define the different amount of purchase that would be available - 5 amount maximum (5:10:15).', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Item Name', 'item_name', 'Credit Purchase', 'Item name that would be display to the user when he will buy credit.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Currency Code', 'currency_code', 'USD', 'Currency for the Credit purchase, only one can be define here.', 0, 5, NULL);
-- https://www.sandbox.paypal.com/cgi-bin/webscr
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Paypal Payment URL', 'paypal_payment_url', 'https://secure.paypal.com/cgi-bin/webscr', 'Define here the URL of paypal gateway the payment (to test with paypal sandbox).', 0, 5, NULL);
-- www.sandbox.paypal.com
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Paypal Verify URL', 'paypal_verify_url', 'ssl://www.paypal.com', 'paypal transaction verification url.', 0, 5, NULL);
-- https://test.authorize.net/gateway/transact.dll
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Authorize.NET Payment URL', 'authorize_payment_url', 'https://secure.authorize.net/gateway/transact.dll', 'Define here the URL of Authorize gateway.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('PayPal Store Name', 'store_name', 'Asterisk2Billing', 'paypal store name to show in the paypal site when customer will go to pay.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Transaction Key', 'transaction_key', 'asdf1212fasd121554sd4f5s45sdf', 'Transaction Key for security of Epayment Max length of 60 Characters.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Secret Word', 'moneybookers_secretword', '', 'Moneybookers secret word.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Enable', 'enable_signup', 0, 'Enable Signup Module.', 1, 6, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Captcha Security', 'enable_captcha', 1, 'enable Captcha on the signup module (value : YES or NO).', 1, 6, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Credit', 'credit', '0', 'amount of credit applied to a new user.', 0, 6, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('CallPlan ID List', 'callplan_id_list', '1,2', 'the list of id of call plans which will be shown in signup.', 0, 6, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Card Activation', 'activated', '0', 'Specify whether the card is created as Active or New.', 1, 6, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Access Type', 'simultaccess', '0', 'Simultaneous or non concurrent access with the card - 0 = INDIVIDUAL ACCESS or 1 = SIMULTANEOUS ACCESS.', 0, 6, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Paid Type', 'typepaid', '0', 'PREPAID CARD  =  0 - POSTPAY CARD  =  1.', 0, 6, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Credit Limit', 'creditlimit', '0', 'Define credit limit, which is only used for a POSTPAY card.', 0, 6, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Run Service', 'runservice', '0', 'Authorise the recurring service to apply on this card  -  Yes 1 - No 0.', 0, 6, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Enable Expire', 'enableexpire', '0', 'Enable the expiry of the card  -  Yes 1 - No 0.', 0, 6, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Date Format', 'expirationdate', '', 'Expiry Date format YYYY-MM-DD HH:MM:SS. For instance 2004-12-31 00:00:00', 0, 6, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Expire Limit', 'expiredays', '0', 'The number of days after which the card will expire.', 0, 6, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Create SIP', 'sip_account', 1, 'Create a sip account from signup ( default : yes ).', 1, 6, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Create IAX', 'iax_account', 1, 'Create an iax account from signup ( default : yes ).', 1, 6, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Activate Card', 'activatedbyuser', 0, 'active card after the new signup. if No, the Signup confirmation is needed and an email will be sent to the user with a link for activation (need to put the link into the Signup mail template).', 1, 6, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Customer Interface URL', 'urlcustomerinterface', 'http://localhost/customer/', 'url of the customer interface to display after activation.', 0, 6, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Asterisk Reload', 'reload_asterisk_if_sipiax_created', '0', 'Define if you want to reload Asterisk when a SIP / IAX Friend is created at signup time.', 1, 6, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Backup Path', 'backup_path', '/tmp', 'Path to store backup of database.', 0, 7, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('GZIP Path', 'gzip_exe', '/bin/gzip', 'Path for gzip.', 0, 7, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('GunZip Path', 'gunzip_exe', '/bin/gunzip', 'Path for gunzip .', 0, 7, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('MySql Dump Path', 'mysqldump', '/usr/bin/mysqldump', 'path for mysqldump.', 0, 7, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('PGSql Dump Path', 'pg_dump', '/usr/bin/pg_dump', 'path for pg_dump.', 0, 7, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('MySql Path', 'mysql', '/usr/bin/mysql', 'Path for MySql.', 0, 7, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('PSql Path', 'psql', '/usr/bin/psql', 'Path for PSql.', 0, 7, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('SIP File Path', 'buddy_sip_file', '/etc/asterisk/additional_a2billing_sip.conf', 'Path to store the asterisk configuration files SIP.', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('IAX File Path', 'buddy_iax_file', '/etc/asterisk/additional_a2billing_iax.conf', 'Path to store the asterisk configuration files IAX.', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('API Security Key', 'api_security_key', 'Ae87v56zzl34v', 'API have a security key to validate the http request, the key has to be sent after applying md5, Valid characters are [a-z,A-Z,0-9].', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Authorized IP', 'api_ip_auth', '127.0.0.1', 'API to restrict the IPs authorised to make a request, Define The the list of ips separated by '';''.', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Admin Email', 'email_admin', 'root@localhost', 'Administative Email.', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('MOH Directory', 'dir_store_mohmp3', '/var/lib/asterisk/mohmp3', 'MOH (Music on Hold) base directory.', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('MOH Classes', 'num_musiconhold_class', '10', 'Number of MOH classes you have created in musiconhold.conf : acc_1, acc_2... acc_10 class	etc....', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Display Help', 'show_help', 1, 'Display the help section inside the admin interface  (YES - NO).', 1, 8, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Max File Upload Size', 'my_max_file_size_import', '1024000', 'File Upload parameters, PLEASE CHECK ALSO THE VALUE IN YOUR PHP.INI THE LIMIT IS 2MG BY DEFAULT .', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Audio Directory Path', 'dir_store_audio', '/var/lib/asterisk/sounds/a2billing', 'Not used yet, The goal is to upload files and use them in the IVR.', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Max Audio File Size', 'my_max_file_size_audio', '3072000', 'upload maximum file size.', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Extensions Allowed', 'file_ext_allow', 'gsm, mp3, wav', 'File type extensions permitted to be uploaded such as "gsm, mp3, wav" (separated by ,).', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Muzic Files Allowed', 'file_ext_allow_musiconhold', 'mp3', 'File type extensions permitted to be uploaded for the musiconhold such as "gsm, mp3, wav" (separate by ,).', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Link Audio', 'link_audio_file', '0', 'Enable link on the CDR viewer to the recordings. (YES - NO).', 1, 8, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Monitor Path', 'monitor_path', '/var/spool/asterisk/monitor', 'Path to link the recorded monitor files.', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Monitor Format', 'monitor_formatfile', 'gsm', 'FORMAT OF THE RECORDED MONITOR FILE.', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Invoice Icon', 'show_icon_invoice', 1, 'Display the icon in the invoice.', 1, 8, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Show Top Frame', 'show_top_frame', '0', 'Display the top frame (useful if you want to save space on your little tiny screen ) .', 1, 8, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Currency', 'currency_choose', 'usd, eur, cad, hkd', 'Allow the customer to chose the most appropriate currency ("all" can be used).', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Card Export Fields', 'card_export_field_list', 'cc_card.id, username, useralias, lastname, credit, tariff, activated, language, inuse, currency, sip_buddy, iax_buddy, nbused, mac_addr', 'Fields to export in csv format from cc_card table.', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Vouvher Export Fields', 'voucher_export_field_list', 'voucher, credit, tag, activated, usedcardnumber, usedate, currency', 'Field to export in csv format from cc_voucher table.', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Advance Mode', 'advanced_mode', '0', 'Advanced mode - Display additional configuration options on the ratecard (progressive rates, musiconhold, ...).', 1, 8, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('SIP/IAX Delete', 'delete_fk_card', 1, 'Delete the SIP/IAX Friend & callerid when a card is deleted.', 1, 8, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Type', 'type', 'friend', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, 9, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Allow', 'allow', 'ulaw,alaw,gsm,g729', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, 9, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Context', 'context', 'a2billing', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, 9, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Nat', 'nat', 'yes', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, 9, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('AMA Flag', 'amaflag', 'billing', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, 9, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Qualify', 'qualify', 'yes', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, 9, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Host', 'host', 'dynamic', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, 9, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('DTMF Mode', 'dtmfmode', 'RFC2833', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, 9, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Alarm Log File', 'cront_alarm', '/var/log/a2billing/cront_a2b_alarm.log', 'To disable application logging, remove/comment the log file name aside service.', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Auto refill Log File', 'cront_autorefill', '/var/log/a2billing/cront_a2b_autorefill.log', 'To disable application logging, remove/comment the log file name aside service.', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Bactch Process Log File', 'cront_batch_process', '/var/log/a2billing/cront_a2b_batch_process.log', 'To disable application logging, remove/comment the log file name aside service .', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Archive Log File', 'cront_archive_data', '/var/log/a2billing/cront_a2b_archive_data.log', 'To disable application logging, remove/comment the log file name aside service .', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('DID Billing Log File', 'cront_bill_diduse', '/var/log/a2billing/cront_a2b_bill_diduse.log', 'To disable application logging, remove/comment the log file name aside service .', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Subscription Fee Log File', 'cront_subscriptionfee', '/var/log/a2billing/cront_a2b_subscription_fee.log', 'To disable application logging, remove/comment the log file name aside service.', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Currency Cront Log File', 'cront_currency_update', '/var/log/a2billing/cront_a2b_currency_update.log', 'To disable application logging, remove/comment the log file name aside service.', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Invoice Cront Log File', 'cront_invoice', '/var/log/a2billing/cront_a2b_invoice.log', 'To disable application logging, remove/comment the log file name aside service.', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Cornt Log File', 'cront_check_account', '/var/log/a2billing/cront_a2b_check_account.log', 'To disable application logging, remove/comment the log file name aside service .', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Paypal Log File', 'paypal', '/var/log/a2billing/a2billing_paypal.log', 'paypal log file, to log all the transaction & error.', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('EPayment Log File', 'epayment', '/var/log/a2billing/a2billing_epayment.log', 'epayment log file, to log all the transaction & error .', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('ECommerce Log File', 'api_ecommerce', '/var/log/a2billing/a2billing_api_ecommerce_request.log', 'Log file to store the ecommerce API requests .', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Callback Log File', 'api_callback', '/var/log/a2billing/a2billing_api_callback_request.log', 'Log file to store the CallBack API requests.', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Webservice Card Log File', 'api_card', '/var/log/a2billing/a2billing_api_card.log', 'Log file to store the Card Webservice Logs', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('AGI Log File', 'agi', '/var/log/a2billing/a2billing_agi.log', 'File to log.', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Description', 'description', 'agi-config', 'Description/notes field', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Asterisk Version', 'asterisk_version', '1_4', 'Asterisk Version Information, 1_1,1_2,1_4 By Default it will take 1_2 or higher .', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Answer Call', 'answer_call', 1, 'Manage the answer on the call. Disabling this for callback trigger numbers makes it ring not hang up.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Play Audio', 'play_audio', 1, 'Play audio - this will disable all stream file but not the Get Data , for wholesale ensure that the authentication works and than number_try = 1.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Say GoodBye', 'say_goodbye', '0', 'play the goodbye message when the user has finished.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Play Language Menu', 'play_menulanguage', '0', 'enable the menu to choose the language, press 1 for English, pulsa 2 para el español, Pressez 3 pour Français', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Force Language', 'force_language', '', 'force the use of a language, if you dont want to use it leave the option empty, Values : ES, EN, FR, etc... (according to the audio you have installed).', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Intro Prompt', 'intro_prompt', '', 'Introduction prompt : to specify an additional prompt to play at the beginning of the application .', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Min Call Credit', 'min_credit_2call', '0', 'Minimum amount of credit to use the application .', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Min Bill Duration', 'min_duration_2bill', '0', 'this is the minimum duration in seconds of a call in order to be billed any call with a length less than min_duration_2bill will have a 0 cost useful not to charge callers for system errors when a call was answered but it actually didn''t connect.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Not Enough Credit', 'notenoughcredit_cardnumber', 0, 'if user doesn''t have enough credit to call a destination, prompt him to enter another cardnumber .', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('New Caller ID', 'notenoughcredit_assign_newcardnumber_cid', 0, 'if notenoughcredit_cardnumber = YES  then	assign the CallerID to the new cardnumber.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Use DNID', 'use_dnid', '0', 'if YES it will use the DNID and try to dial out, without asking for the phonenumber to call.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Not Use DNID', 'no_auth_dnid', '2400,2300', 'list the dnid on which you want to avoid the use of the previous option "use_dnid" .', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Try Count', 'number_try', '3', 'number of times the user can dial different number.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Force CallPlan', 'force_callplan_id', '', 'this will force to select a specific call plan by the Rate Engine.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Say Balance After Auth', 'say_balance_after_auth', 1, 'Play the balance to the user after the authentication (values : yes - no).', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Say Balance After Call', 'say_balance_after_call', '0', 'Play the balance to the user after the call (values : yes - no).', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Say Rate', 'say_rateinitial', '0', 'Play the initial cost of the route (values : yes - no)', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Say Duration', 'say_timetocall', 1, 'Play the amount of time that the user can call (values : yes - no).', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Auto Set CLID', 'auto_setcallerid', 1, 'enable the setup of the callerID number before the outbound is made, by default the user callerID value will be use.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Force CLID', 'force_callerid', '', 'If auto_setcallerid is enabled, the value of force_callerid will be set as CallerID.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('CLID Sanitize', 'cid_sanitize', '0', 'If force_callerid is not set, then the following option ensures that CID is set to one of the card''s configured caller IDs or blank if none available.(NO - disable this feature, caller ID can be anything, CID - Caller ID must be one of the customers caller IDs, DID - Caller ID must be one of the customers DID nos, BOTH - Caller ID must be one of the above two items)', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('CLID Enable', 'cid_enable', '0', 'enable the callerid authentication if this option is active the CC system will check the CID of caller  .', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Ask PIN', 'cid_askpincode_ifnot_callerid', 1, 'if the CID does not exist, then the caller will be prompt to enter his cardnumber .', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('FailOver LCR/LCD Prefix', 'failover_lc_prefix', 0, 'if we will failover for LCR/LCD prefix. For instance if you have 346 and 34 for if 346 fail it will try to outbound with 34 route.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Auto CLID', 'cid_auto_assign_card_to_cid', 1, 'if the callerID authentication is enable and the authentication fails then the user will be prompt to enter his cardnumber;this option will bound the cardnumber entered to the current callerID so that next call will be directly authenticate.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Auto Create Card', 'cid_auto_create_card', '0', 'if the callerID is captured on a2billing, this option will create automatically a new card and add the callerID to it.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Auto Create Card Length', 'cid_auto_create_card_len', '10', 'set the length of the card that will be auto create (ie, 10).', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Auto Create Card Type', 'cid_auto_create_card_typepaid', 'POSTPAY', 'billing type of the new card( value : POSTPAY or PREPAY) .', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Auto Create Card Credit', 'cid_auto_create_card_credit', '0', 'amount of credit of the new card.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Auto Create Card Limit', 'cid_auto_create_card_credit_limit', '1000', 'if postpay, define the credit limit for the card.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Auto Create Card TariffGroup', 'cid_auto_create_card_tariffgroup', '6', 'the tariffgroup to use for the new card (this is the ID that you can find on the admin web interface) .', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Auto CLID Security', 'callerid_authentication_over_cardnumber', '0', 'to check callerID over the cardnumber authentication (to guard against spoofing).', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('SIP Call', 'sip_iax_friends', '0', 'enable the option to call sip/iax friend for free (values : YES - NO).', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('SIP Call Prefix', 'sip_iax_pstn_direct_call_prefix', '555', 'if SIP_IAX_FRIENDS is active, you can define a prefix for the dialed digits to call a pstn number .', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Direct Call', 'sip_iax_pstn_direct_call', '0', 'this will enable a prompt to enter your destination number. if number start by sip_iax_pstn_direct_call_prefix we do directly a sip iax call, if not we do a normal call.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('IVR Voucher Refill', 'ivr_voucher', '0', 'enable the option to refill card with voucher in IVR (values : YES - NO) .', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('IVR Voucher Prefix', 'ivr_voucher_prefix', '8', 'if ivr_voucher is active, you can define a prefix for the voucher number to refill your card, values : number - don''t forget to change prepaid-refill_card_with_voucher audio accordingly .', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('IVR Low Credit', 'jump_voucher_if_min_credit', 0, 'When the user credit are below the minimum credit to call min_credit jump directly to the voucher IVR menu  (values: YES - NO) .', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Dail Command Parms', 'dialcommand_param', '|60|HRrL(%timeout%:61000:30000)', 'More information about the Dial : http://voip-info.org/wiki-Asterisk+cmd+dial<br>30 :  The timeout parameter is optional. If not specifed, the Dial command will wait indefinitely, exiting only when the originating channel hangs up, or all the dialed channels return a busy or error condition. Otherwise it specifies a maximum time, in seconds, that the Dial command is to wait for a channel to answer.<br>H: Allow the caller to hang up by dialing * <br>r: Generate a ringing tone for the calling party<br>R: Indicate ringing to the calling party when the called party indicates ringing, pass no audio until answered.<br>m: Provide Music on Hold to the calling party until the called channel answers.<br>L(x[:y][:z]): Limit the call to ''x'' ms, warning when ''y'' ms are left, repeated every ''z'' ms)<br>%timeout% tag is replaced by the calculated timeout according the credit & destination rate!.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('SIP/IAX Dial Command Parms', 'dialcommand_param_sipiax_friend', '|60|HL(3600000:61000:30000)', 'by default (3600000  =  1HOUR MAX CALL).', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Outbound Call', 'switchdialcommand', '0', 'Define the order to make the outbound call<br>YES -> SIP/dialedphonenumber@gateway_ip - NO  SIP/gateway_ip/dialedphonenumber<br>Both should work exactly the same but i experimented one case when gateway was supporting dialedphonenumber@gateway_ip, So in case of trouble, try it out.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Failover Retry Limit', 'failover_recursive_limit', '2', 'failover recursive search - define how many time we want to authorize the research of the failover trunk when a call fails (value : 0 - 20) .', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Max Time', 'maxtime_tocall_negatif_free_route', '5400', 'This setting specifies an upper limit for the duration of a call to a destination for which the selling rate is less than or equal to 0.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Send Reminder', 'send_reminder', '0', 'Send a reminder email to the user when they are under min_credit_2call.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Record Call', 'record_call', '0', 'enable to monitor the call (to record all the conversations) value : YES - NO .', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Monitor File Format', 'monitor_formatfile', 'gsm', 'format of the recorded monitor file.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('AGI Force Currency', 'agi_force_currency', '', 'Force to play the balance to the caller in a predefined currency, to use the currency set for by the customer leave this field empty.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Currency Associated', 'currency_association', 'usd:dollars,mxn:pesos,eur:euros,all:credit', 'Define all the audio (without file extensions) that you want to play according to currency (use , to separate, ie "usd:prepaid-dollar,mxn:pesos,eur:Euro,all:credit").', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Minor Currency Associated', 'currency_association_minor', 'usd:prepaid-cents,eur:prepaid-cents,gbp:prepaid-pence,all:credit', 'Define all the audio (without file extensions) that you want to play according to minor currency (use , to separate, ie "usd:prepaid-cents,eur:prepaid-cents,gbp:prepaid-pence,all:credit").', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('File Enter Destination', 'file_conf_enter_destination', 'prepaid-enter-dest', 'Please enter the file name you want to play when we prompt the calling party to enter the destination number, file_conf_enter_destination = prepaid-enter-number-u-calling-1-or-011.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('File Language Menu', 'file_conf_enter_menulang', 'prepaid-menulang2', 'Please enter the file name you want to play when we prompt the calling party to choose the prefered language .', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Bill Callback', 'callback_bill_1stleg_ifcall_notconnected', 1, 'Define if you want to bill the 1st leg on callback even if the call is not connected to the destination.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('International prefixes', 'international_prefixes', '011,00,09,1', 'List the prefixes you want stripped off if the call plan requires it', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Server GMT', 'server_GMT', 'GMT+1:00', 'Define the sever gmt time', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Invoice Template Path', 'invoice_template_path', '../invoice/', 'gives invoice template path from default one', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Outstanding Template Path', 'outstanding_template_path', '../outstanding/', 'gives outstanding template path from default one', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Sales Template Path', 'sales_template_path', '../sales/', 'gives sales template path from default one', 0, 1, NULL);




CREATE TABLE cc_timezone (
    id 								INT NOT NULL AUTO_INCREMENT,
    gmtzone							VARCHAR(255),
    gmttime		 					VARCHAR(255),
	gmtoffset						BIGINT NOT NULL DEFAULT 0,
    PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-12:00) International Date Line West', 'GMT-12:00', '-43200');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-11:00) Midway Island, Samoa', 'GMT-11:00', '-39600');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-10:00) Hawaii', 'GMT-10:00', '-36000');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-09:00) Alaska', 'GMT-09:00', '-32400');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-08:00) Pacific Time (US & Canada) Tijuana', 'GMT-08:00', '-28800');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-07:00) Arizona', 'GMT-07:00', '-25200');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-07:00) Chihuahua, La Paz, Mazatlan', 'GMT-07:00', '-25200');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-07:00) Mountain Time(US & Canada)', 'GMT-07:00', '-25200');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-06:00) Central America', 'GMT-06:00', '-21600');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-06:00) Central Time (US & Canada)', 'GMT-06:00', '-21600');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-06:00) Guadalajara, Mexico City, Monterrey', 'GMT-06:00', '-21600');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-06:00) Saskatchewan', 'GMT-06:00', '-21600');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-05:00) Bogota, Lima, Quito', 'GMT-05:00', '-18000');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-05:00) Eastern Time (US & Canada)', 'GMT-05:00', '-18000');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-05:00) Indiana (East)', 'GMT-05:00', '-18000');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-04:00) Atlantic Time (Canada)', 'GMT-04:00', '-14400');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-04:00) Caracas, La Paz', 'GMT-04:00', '-14400');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-04:00) Santiago', 'GMT-04:00', '-14400');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-03:30) NewFoundland', 'GMT-03:30', '-12600');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-03:00) Brasillia', 'GMT-03:00', '-10800');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-03:00) Buenos Aires, Georgetown', 'GMT-03:00', '-10800');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-03:00) Greenland', 'GMT-03:00', '-10800');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-03:00) Mid-Atlantic', 'GMT-03:00', '-10800');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-01:00) Azores', 'GMT-01:00', '-3600');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT-01:00) Cape Verd Is.', 'GMT-01:00', '-3600');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT) Casablanca, Monrovia', 'GMT+00:00', '0');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT) Greenwich Mean Time : Dublin, Edinburgh, Lisbon,  London', 'GMT', '0');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna', 'GMT+01:00', '3600');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague', 'GMT+01:00', '3600');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+01:00) Brussels, Copenhagen, Madrid, Paris', 'GMT+01:00', '3600');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb', 'GMT+01:00', '3600');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+01:00) West Central Africa', 'GMT+01:00', '3600');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+02:00) Athens, Istanbul, Minsk', 'GMT+02:00', '7200');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+02:00) Bucharest', 'GMT+02:00', '7200');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+02:00) Cairo', 'GMT+02:00', '7200');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+02:00) Harere, Pretoria', 'GMT+02:00', '7200');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius', 'GMT+02:00', '7200');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+02:00) Jeruasalem', 'GMT+02:00', '7200');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+03:00) Baghdad', 'GMT+03:00', '10800');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+03:00) Kuwait, Riyadh', 'GMT+03:00', '10800');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+03:00) Moscow, St.Petersburg, Volgograd', 'GMT+03:00', '10800');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+03:00) Nairobi', 'GMT+03:00', '10800');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+03:30) Tehran', 'GMT+03:30', '12600');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+04:00) Abu Dhabi, Muscat', 'GMT+04:00', '14400');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+04:00) Baku, Tbillisi, Yerevan', 'GMT+04:00', '14400');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+04:30) Kabul', 'GMT+04:30', '16200');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+05:00) Ekaterinburg', 'GMT+05:00', '18000');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+05:00) Islamabad, Karachi, Tashkent', 'GMT+05:00', '18000');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi', 'GMT+05:30', '19800');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+05:45) Kathmandu', 'GMT+05:45', '20700');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+06:00) Almaty, Novosibirsk', 'GMT+06:00', '21600');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+06:00) Astana, Dhaka', 'GMT+06:00', '21600');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+06:00) Sri Jayawardenepura', 'GMT+06:00', '21600');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+06:30) Rangoon', 'GMT+06:30', '23400');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+07:00) Bangkok, Hanoi, Jakarta', 'GMT+07:00', '25200');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+07:00) Krasnoyarsk', 'GMT+07:00', '25200');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+08:00) Beijiing, Chongging, Hong Kong, Urumqi', 'GMT+08:00', '28800');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+08:00) Irkutsk, Ulaan Bataar', 'GMT+08:00', '28800');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+08:00) Kuala Lumpur, Singapore', 'GMT+08:00', '28800');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+08:00) Perth', 'GMT+08:00', '28800');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+08:00) Taipei', 'GMT+08:00', '28800');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+09:00) Osaka, Sapporo, Tokyo', 'GMT+09:00', '32400');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+09:00) Seoul', 'GMT+09:00', '32400');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+09:00) Yakutsk', 'GMT+09:00', '32400');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+09:00) Adelaide', 'GMT+09:00', '32400');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+09:30) Darwin', 'GMT+09:30', '34200');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+10:00) Brisbane', 'GMT+10:00', '36000');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+10:00) Canberra, Melbourne, Sydney', 'GMT+10:00', '36000');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+10:00) Guam, Port Moresby', 'GMT+10:00', '36000');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+10:00) Hobart', 'GMT+10:00', '36000');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+10:00) Vladivostok', 'GMT+10:00', '36000');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+11:00) Magadan, Solomon Is., New Caledonia', 'GMT+11:00', '39600');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+12:00) Auckland, Wellington', 'GMT+1200', '43200');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+12:00) Fiji, Kamchatka, Marshall Is.', 'GMT+12:00', '43200');
INSERT INTO cc_timezone (gmtzone, gmttime, gmtoffset) VALUES ('(GMT+13:00) Nuku alofa', 'GMT+13:00', '46800');


CREATE TABLE cc_iso639 (
    code character(2) NOT NULL,
    name character(16) NOT NULL,
    lname character(16),
    `charset` character(16) NOT NULL DEFAULT 'ISO-8859-1',
    CONSTRAINT iso639_name_key UNIQUE (name),
    CONSTRAINT iso639_pkey PRIMARY KEY (code)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ab', 'Abkhazian       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('om', 'Afan (Oromo)    ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('aa', 'Afar            ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('af', 'Afrikaans       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('sq', 'Albanian        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('am', 'Amharic         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ar', 'Arabic          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('hy', 'Armenian        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('as', 'Assamese        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ay', 'Aymara          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('az', 'Azerbaijani     ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ba', 'Bashkir         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('eu', 'Basque          ', 'Euskera         ', 'ISO-8859-15     ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('bn', 'Bengali Bangla  ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('dz', 'Bhutani         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('bh', 'Bihari          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('bi', 'Bislama         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('br', 'Breton          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('bg', 'Bulgarian       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('my', 'Burmese         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('be', 'Byelorussian    ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('km', 'Cambodian       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ca', 'Catalan         ', '          \t\t    ', 'ISO-8859-15     ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('zh', 'Chinese         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('co', 'Corsican        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('hr', 'Croatian        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('cs', 'Czech           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('da', 'Danish          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('nl', 'Dutch           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('en', 'English         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('eo', 'Esperanto       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('et', 'Estonian        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('fo', 'Faroese         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('fj', 'Fiji            ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('fi', 'Finnish         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('fr', 'French          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('fy', 'Frisian         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('gl', 'Galician        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ka', 'Georgian        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('de', 'German          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('el', 'Greek           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('kl', 'Greenlandic     ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('gn', 'Guarani         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('gu', 'Gujarati        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ha', 'Hausa           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('he', 'Hebrew          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('hi', 'Hindi           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('hu', 'Hungarian       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('is', 'Icelandic       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('id', 'Indonesian      ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ia', 'Interlingua     ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ie', 'Interlingue     ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('iu', 'Inuktitut       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ik', 'Inupiak         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ga', 'Irish           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('it', 'Italian         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ja', 'Japanese        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('jv', 'Javanese        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('kn', 'Kannada         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ks', 'Kashmiri        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('kk', 'Kazakh          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('rw', 'Kinyarwanda     ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ky', 'Kirghiz         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('rn', 'Kurundi         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ko', 'Korean          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ku', 'Kurdish         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('lo', 'Laothian        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('la', 'Latin           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('lv', 'Latvian Lettish ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ln', 'Lingala         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('lt', 'Lithuanian      ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('mk', 'Macedonian      ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('mg', 'Malagasy        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ms', 'Malay           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ml', 'Malayalam       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('mt', 'Maltese         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('mi', 'Maori           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('mr', 'Marathi         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('mo', 'Moldavian       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('mn', 'Mongolian       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('na', 'Nauru           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ne', 'Nepali          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('no', 'Norwegian       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('oc', 'Occitan         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('or', 'Oriya           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ps', 'Pashto Pushto   ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('fa', 'Persian (Farsi) ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('pl', 'Polish          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('pt', 'Portuguese      ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('pa', 'Punjabi         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('qu', 'Quechua         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('rm', 'Rhaeto-Romance  ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ro', 'Romanian        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ru', 'Russian         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('sm', 'Samoan          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('sg', 'Sangho          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('sa', 'Sanskrit        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('gd', 'Scots Gaelic    ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('sr', 'Serbian         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('sh', 'Serbo-Croatian  ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('st', 'Sesotho         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('tn', 'Setswana        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('sn', 'Shona           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('sd', 'Sindhi          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('si', 'Singhalese      ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ss', 'Siswati         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('sk', 'Slovak          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('sl', 'Slovenian       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('so', 'Somali          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('es', 'Spanish         ', '         \t\t     ', 'ISO-8859-15     ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('su', 'Sundanese       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('sw', 'Swahili         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('sv', 'Swedish         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('tl', 'Tagalog         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('tg', 'Tajik           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ta', 'Tamil           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('tt', 'Tatar           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('te', 'Telugu          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('th', 'Thai            ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('bo', 'Tibetan         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ti', 'Tigrinya        ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('to', 'Tonga           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ts', 'Tsonga          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('tr', 'Turkish         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('tk', 'Turkmen         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('tw', 'Twi             ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ug', 'Uigur           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('uk', 'Ukrainian       ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('ur', 'Urdu            ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('uz', 'Uzbek           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('vi', 'Vietnamese      ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('vo', 'Volapuk         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('cy', 'Welsh           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('wo', 'Wolof           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('xh', 'Xhosa           ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('yi', 'Yiddish         ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('yo', 'Yoruba          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('za', 'Zhuang          ', '                ', 'ISO-8859-1      ');
INSERT INTO cc_iso639 (code, name, lname, charset) VALUES ('zu', 'Zulu            ', '                ', 'ISO-8859-1      ');

ALTER TABLE cc_templatemail DROP INDEX cons_cc_templatemail_mailtype;
ALTER TABLE cc_templatemail ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST, ADD id_language CHAR( 20 ) NOT NULL DEFAULT 'en' AFTER id ;
ALTER TABLE cc_templatemail CHANGE id id INT( 11 ) NOT NULL ;
ALTER TABLE cc_templatemail DROP PRIMARY KEY;
ALTER TABLE cc_templatemail ADD UNIQUE cons_cc_templatemail_id_language ( mailtype, id_language );


ALTER TABLE cc_card ADD status INT NOT NULL DEFAULT '1' AFTER activated ;
update cc_card set status = 1 where activated = 't';
update cc_card set status = 0 where activated = 'f';

CREATE TABLE cc_status_log (
	id 				BIGINT(20) NOT NULL AUTO_INCREMENT,
	status 			INT(11) NOT NULL,
	id_cc_card 		BIGINT(20) NOT NULL,
	updated_date 	TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE cc_card ADD COLUMN tag CHAR(50);
ALTER TABLE cc_ratecard ADD COLUMN rounding_calltime INT NOT NULL DEFAULT 0;
ALTER TABLE cc_ratecard ADD COLUMN rounding_threshold INT NOT NULL DEFAULT 0;
ALTER TABLE cc_ratecard ADD COLUMN additional_block_charge DECIMAL(15,5) NOT NULL DEFAULT 0;
ALTER TABLE cc_ratecard ADD COLUMN additional_block_charge_time INT NOT NULL DEFAULT 0;
ALTER TABLE cc_ratecard ADD COLUMN tag CHAR(50);
ALTER TABLE cc_ratecard ADD COLUMN disconnectcharge_after INT NOT NULL DEFAULT 0;

ALTER TABLE cc_card ADD COLUMN template_invoice VARCHAR( 100 ) ;
ALTER TABLE cc_card ADD COLUMN template_outstanding VARCHAR( 100 ) ;

CREATE TABLE cc_card_history (
	id 					BIGINT NOT NULL AUTO_INCREMENT,
	id_cc_card 			BIGINT,
	datecreated 		TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	description			TEXT,
	PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



ALTER TABLE cc_callback_spool CHANGE variable variable VARCHAR( 300 ) DEFAULT NULL;


ALTER TABLE cc_call ADD COLUMN real_sessiontime INT (11) DEFAULT NULL;


-- ?? update this when release 1.4
CREATE TABLE cc_call_archive (
	id 									bigINT (20) NOT NULL AUTO_INCREMENT,
	sessionid 							char(40) NOT NULL,
	uniqueid 							char(30) NOT NULL,
	username 							char(40) NOT NULL,
	nasipaddress 						char(30) DEFAULT NULL,
	starttime 							timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	stoptime 							timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	sessiontime 						INT (11) DEFAULT NULL,
	calledstation 						char(30) DEFAULT NULL,
	startdelay 							INT (11) DEFAULT NULL,
	stopdelay 							INT (11) DEFAULT NULL,
	terminatecause 						char(20) DEFAULT NULL,
	usertariff 							char(20) DEFAULT NULL,
	calledprovider 						char(20) DEFAULT NULL,
	calledcountry 						char(30) DEFAULT NULL,
	calledsub 							char(20) DEFAULT NULL,
	calledrate 							FLOAT DEFAULT NULL,
	sessionbill 						FLOAT DEFAULT NULL,
	destination 						char(40) DEFAULT NULL,
	id_tariffgroup 						INT (11) DEFAULT NULL,
	id_tariffplan 						INT (11) DEFAULT NULL,
	id_ratecard 						INT (11) DEFAULT NULL,
	id_trunk 							INT (11) DEFAULT NULL,
	sipiax 								INT (11) DEFAULT '0',
	src 								char(40) DEFAULT NULL,
	id_did 								INT (11) DEFAULT NULL,
	buyrate 							DECIMAL(15,5) DEFAULT 0,
	buycost 							DECIMAL(15,5) DEFAULT 0,
	id_card_package_offer 				INT (11) DEFAULT 0,
	real_sessiontime					INT (11) DEFAULT NULL,
	PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

ALTER TABLE cc_call_archive ADD INDEX ( username );
ALTER TABLE cc_call_archive ADD INDEX ( starttime );
ALTER TABLE cc_call_archive ADD INDEX ( terminatecause );
ALTER TABLE cc_call_archive ADD INDEX ( calledstation );



ALTER TABLE cc_card DROP COLUMN userpass;

CREATE TABLE cc_card_archive (
	id 								BIGINT NOT NULL,
	creationdate 					TIMESTAMP DEFAULT  CURRENT_TIMESTAMP NOT NULL,
	firstusedate 					TIMESTAMP,
	expirationdate 					TIMESTAMP,
	enableexpire 					INT DEFAULT 0,
	expiredays 						INT DEFAULT 0,
	username 						CHAR(50) NOT NULL,
	useralias 						CHAR(50) NOT NULL,
	uipass 							CHAR(50),
	credit 							DECIMAL(15,5) DEFAULT 0 NOT NULL,
	tariff 							INT DEFAULT 0,
	id_didgroup 					INT DEFAULT 0,
	activated 						CHAR(1) DEFAULT 'f' NOT NULL,
	status							INT DEFAULT 1,
	lastname 						CHAR(50),
	firstname 						CHAR(50),
	address 						CHAR(100),
	city 							CHAR(40),
	state 							CHAR(40),
	country 						CHAR(40),
	zipcode 						CHAR(20),
	phone 							CHAR(20),
	email 							CHAR(70),
	fax 							CHAR(20),
	inuse 							INT DEFAULT 0,
	simultaccess 					INT DEFAULT 0,
	currency 						CHAR(3) DEFAULT 'USD',
	lastuse  						TIMESTAMP,
	nbused 							INT DEFAULT 0,
	typepaid 						INT DEFAULT 0,
	creditlimit 					INT DEFAULT 0,
	voipcall 						INT DEFAULT 0,
	sip_buddy 						INT DEFAULT 0,
	iax_buddy 						INT DEFAULT 0,
	language 						CHAR(5) DEFAULT 'en',
	redial 							CHAR(50),
	runservice 						INT DEFAULT 0,
	nbservice 						INT DEFAULT 0,
	id_campaign						INT DEFAULT 0,
	num_trials_done 				BIGINT DEFAULT 0,
	callback 						CHAR(50),
	vat 							FLOAT DEFAULT 0 NOT NULL,
	servicelastrun 					TIMESTAMP,
	initialbalance 					DECIMAL(15,5) DEFAULT 0 NOT NULL,
	invoiceday 						INT DEFAULT 1,
	autorefill 						INT DEFAULT 0,
	loginkey 						CHAR(40),
	activatedbyuser 				CHAR(1) DEFAULT 't' NOT NULL,
	id_timezone 					INT DEFAULT 0,
	tag char(50) 					collate utf8_bin default NULL,
	template_invoice 				text collate utf8_bin,
	template_outstanding			text collate utf8_bin,
	mac_addr						CHAR(17) DEFAULT '00-00-00-00-00-00' NOT NULL,
	PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



ALTER TABLE cc_card_archive ADD INDEX ( creationdate );
ALTER TABLE cc_card_archive ADD INDEX ( username );
ALTER TABLE cc_ratecard ADD COLUMN is_merged INT DEFAULT 0;

UPDATE cc_config SET config_title='Dial Command Params', config_description='More information about the Dial : http://voip-info.org/wiki-Asterisk+cmd+dial<br>30 :  The timeout parameter is optional. If not specifed, the Dial command will wait indefinitely, exiting only when the originating channel hangs up, or all the dialed channels return a busy or error condition. Otherwise it specifies a maximum time, in seconds, that the Dial command is to wait for a channel to answer.<br>H: Allow the caller to hang up by dialing * <br>r: Generate a ringing tone for the calling party<br>R: Indicate ringing to the calling party when the called party indicates ringing, pass no audio until answered.<br>g: When the called party hangs up, exit to execute more commands in the current context. (new in 1.4)<br>i: Asterisk will ignore any forwarding (302 Redirect) requests received. Essential for DID usage to prevent fraud. (new in 1.4)<br>m: Provide Music on Hold to the calling party until the called channel answers.<br>L(x[:y][:z]): Limit the call to ''x'' ms, warning when ''y'' ms are left, repeated every ''z'' ms)<br>%timeout% tag is replaced by the calculated timeout according the credit & destination rate!.' WHERE  config_key='dialcommand_param';
UPDATE cc_config SET config_title='SIP/IAX Dial Command Params', config_value='|60|HiL(3600000:61000:30000)' WHERE config_key='dialcommand_param_sipiax_friend';





-- VOICEMAIL CHANGES
ALTER TABLE cc_card ADD voicemail_permitted INTEGER DEFAULT 0 NOT NULL;
ALTER TABLE cc_card ADD voicemail_activated SMALLINT DEFAULT 0 NOT NULL;



-- ADD MISSING extracharge_did settings
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Extra charge DIDs', 'extracharge_did', '1800,1900', 'Add extra per-minute charges to this comma-separated list of DNIDs; needs "extracharge_fee" and "extracharge_buyfee"', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Extra charge DID fees', 'extracharge_fee', '0,0', 'Comma-separated list of extra sell-rate charges corresponding to the DIDs in "extracharge_did" - ie : 0.08,0.18', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Extra charge DID buy fees', 'extracharge_buyfee', '0,0', 'Comma-separated list of extra buy-rate charges corresponding to the DIDs in "extracharge_did" - ie : 0.04,0.13', 0, 11, NULL);


-- These triggers are to prevent bogus regexes making it into the database
DELIMITER //
CREATE TRIGGER cc_ratecard_validate_regex_ins BEFORE INSERT ON cc_ratecard
FOR EACH ROW
BEGIN
  DECLARE valid INTEGER;
  SELECT '0' REGEXP REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(CONCAT('^', NEW.dialprefix, '$'), 'X', '[0-9]'), 'Z', '[1-9]'), 'N', '[2-9]'), '.', '.+'), '_', '') INTO valid;
END
//
CREATE TRIGGER cc_ratecard_validate_regex_upd BEFORE UPDATE ON cc_ratecard
FOR EACH ROW
BEGIN
  DECLARE valid INTEGER;
  SELECT '0' REGEXP REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(CONCAT('^', NEW.dialprefix, '$'), 'X', '[0-9]'), 'Z', '[1-9]'), 'N', '[2-9]'), '.', '.+'), '_', '') INTO valid;
END
//
DELIMITER ;

ALTER TABLE cc_currencies CHANGE value value NUMERIC (12,5) unsigned NOT NULL DEFAULT '0.00000';



-- More info into log payment
ALTER TABLE cc_logpayment ADD COLUMN id_logrefill BIGINT DEFAULT NULL;


-- Support / Ticket section

CREATE TABLE cc_support (
	id smallint(5) NOT NULL auto_increment,
	`name` varchar(50) collate utf8_bin NOT NULL,
	PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE cc_support_component (
	id smallint(5) NOT NULL auto_increment,
	id_support smallint(5) NOT NULL,
	name varchar(50) collate utf8_bin NOT NULL,
	activated smallint(6) NOT NULL default '1',
	PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE cc_ticket (
	id bigint(10) NOT NULL auto_increment,
	id_component smallint(5) NOT NULL,
	title varchar(100) collate utf8_bin NOT NULL,
	description text collate utf8_bin,
	priority smallint(6) NOT NULL default '0',
	creationdate timestamp NOT NULL default CURRENT_TIMESTAMP,
	creator bigint(20) NOT NULL,
	status smallint(6) NOT NULL default '0',
	PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE cc_ticket_comment (
	id bigint(20) NOT NULL auto_increment,
	date timestamp NOT NULL default CURRENT_TIMESTAMP,
	id_ticket bigint(10) NOT NULL,
	description text collate utf8_bin,
	creator bigint(20) NOT NULL,
	is_admin char(1) collate utf8_bin NOT NULL default 'f',
	PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


INSERT INTO cc_config ( config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ( 'Support Modules', 'support', '1', 'Enable or Disable the module of support', 1, 3, 'yes,no');



-- change charset to use LIKE without "casse"
ALTER TABLE cc_ratecard CHANGE destination destination CHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;


-- section for notification

INSERT INTO cc_config_group (group_title ,group_description) VALUES
 ( 'notifications', 'This configuration group handles the notifcations configuration');

INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ( 'List of possible values to notify', 'values_notifications', '10:20:50:100:500:1000', 'Possible values to choose when the user receive a notification. You can define a List e.g: 10:20:100.', '0', '12', NULL);

INSERT INTO cc_config ( config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues)
 VALUES ( 'Notifications Modules', 'notification', '1', 'Enable or Disable the module of notification for the customers', 1, 3, 'yes,no');


INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ( 'Notications Cron Module', 'cron_notifications', '1', 'Enable or Disable the cron module of notification for the customers. If it correctly configured in the crontab', '0', '12', 'yes,no');


INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ( 'Notications Delay', 'delay_notifications', '1', 'Delay in number of days to send an other notification for the customers. If the value is 0, it will notify the user everytime the cront is running.', '0', '12', NULL);

ALTER TABLE cc_card ADD last_notification TIMESTAMP NULL DEFAULT NULL ;


ALTER TABLE cc_card ADD email_notification CHAR( 70 ) CHARACTER SET utf8 COLLATE utf8_bin NULL ;

ALTER TABLE cc_card
ADD notify_email SMALLINT NOT NULL DEFAULT '0';

ALTER TABLE cc_card ADD credit_notification INT NOT NULL DEFAULT -1;

UPDATE cc_templatemail SET subject='Your account $cardnumber$ is low on credit ($currency$ $creditcurrency$)', messagetext = '

Your Account number $cardnumber$ is running low on credit.

There is currently only $creditcurrency$ $currency$ left on your account which is lower than the warning level defined ($credit_notification$)


Please top up your account ASAP to ensure continued service

If you no longer wish to receive these notifications or would like to change the balance amount at which these warnings are generated,
please connect on your myaccount panel and change the appropriate parameters


your account information :
Your account number for VOIP authentication : $cardnumber$

https://myaccount.mydomainname.com/
Your account login : $login$
Your account password : $password$


Thanks,
/My Company Name
-------------------------------------
http://www.mydomainname.com
 '
WHERE cc_templatemail.mailtype ='reminder' AND CONVERT( cc_templatemail.id_language USING utf8 ) = 'en' LIMIT 1 ;





-- Section for Agent

CREATE TABLE cc_agent (
	id 								BIGINT NOT NULL AUTO_INCREMENT,
    datecreation 					TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    active 							CHAR(1) DEFAULT 'f' NOT NULL,
    login 							CHAR(20) NOT NULL,
    passwd 							CHAR(40),
    location 						text,
    language 						CHAR(5) DEFAULT 'en',
    id_tariffgroup					INT,
    options 						integer NOT NULL DEFAULT 0,
    credit 							DECIMAL(15,5) DEFAULT 0 NOT NULL,
    climit 							DECIMAL(15,5) DEFAULT 0 NOT NULL,
    currency 						CHAR(3) DEFAULT 'USD',
    locale 							CHAR(10) DEFAULT 'C',
    commission 						DECIMAL(10,4) DEFAULT 0 NOT NULL,
    vat 							DECIMAL(10,4) DEFAULT 0 NOT NULL,
    banner 							TEXT,
	perms 							INT,
    lastname 						CHAR(50),
    firstname 						CHAR(50),
    address 						CHAR(100),
    city 							CHAR(40),
    state 							CHAR(40),
    country 						CHAR(40),
    zipcode 						CHAR(20),
    phone 							CHAR(20),
    email 							CHAR(70),
    fax 							CHAR(20),
	PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



ALTER TABLE cc_card ADD id_agent INT NOT NULL DEFAULT '0';

-- Add card id field in CDR to authorize filtering by agent

ALTER TABLE cc_call ADD card_id BIGINT( 20 ) NOT NULL AFTER username;

UPDATE cc_call,cc_card SET cc_call.card_id=cc_card.id WHERE cc_card.username=cc_call.username;


CREATE TABLE cc_agent_tariffgroup (
	id_agent BIGINT( 20 ) NOT NULL ,
	id_tariffgroup INT( 11 ) NOT NULL,
	PRIMARY KEY ( id_agent,id_tariffgroup )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;




-- Add new configuration payment agent

INSERT INTO cc_config ( id, config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES (NULL , 'Payment Amount', 'purchase_amount_agent', '100:200:500:1000', 'define the different amount of purchase that would be available.', '0', '5', NULL);


-- create group for the card

CREATE TABLE cc_card_group (
	id 					INT NOT NULL AUTO_INCREMENT ,
	name 				CHAR( 30 ) NOT NULL collate utf8_bin ,
	id_agi_conf 		INT NOT NULL ,
	description 		MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_bin NULL ,
	PRIMARY KEY ( id )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- insert default group

INSERT INTO cc_card_group (id ,name ,id_agi_conf) VALUES ('1' , 'DEFAULT', '-1');

ALTER TABLE cc_card ADD id_group INT NOT NULL DEFAULT '1';


-- new table for the free minutes/calls package


CREATE TABLE cc_package_group (
	id INT NOT NULL AUTO_INCREMENT ,
	name CHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
	description MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_bin NULL,
	PRIMARY KEY ( id )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE cc_packgroup_package (
	packagegroup_id INT NOT NULL ,
	package_id INT NOT NULL ,
	PRIMARY KEY ( packagegroup_id , package_id )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE cc_package_rate (
	package_id INT NOT NULL ,
	rate_id INT NOT NULL ,
	PRIMARY KEY ( package_id , rate_id )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO cc_config ( id , config_title , config_key , config_value , config_description , config_valuetype , config_group_id , config_listvalues ) VALUES ( NULL , 'Max Time For Unlimited Calls', 'maxtime_tounlimited_calls', '5400', 'For unlimited calls, limit the duration: amount in seconds .', '0', '11', NULL), (NULL , 'Max Time For Free Calls', 'maxtime_tofree_calls', '5400', 'For free calls, limit the duration: amount in seconds .', '0', '11', NULL);

ALTER TABLE cc_ratecard DROP freetimetocall_package_offer;
-- add additionnal grace to the ratecard

ALTER TABLE cc_ratecard ADD additional_grace INT NOT NULL DEFAULT '0';

-- add minimum cost option for a rate card

ALTER TABLE cc_ratecard ADD minimal_cost FLOAT NOT NULL DEFAULT '0';

-- add description for the REFILL AND PAYMENT
ALTER TABLE cc_logpayment ADD description MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_bin NULL ;
ALTER TABLE cc_logrefill ADD description MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_bin NULL ;


ALTER TABLE cc_config CHANGE config_description config_description TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;


-- Deck threshold switch for callplan
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues)
VALUES ('CallPlan threshold Deck switch', 'callplan_deck_minute_threshold', '', 'CallPlan threshold Deck switch. <br/>This option will switch the user callplan from one call plan ID to and other Callplan ID
The parameters are as follow : <br/>
-- ID of the first callplan : called seconds needed to switch to the next CallplanID <br/>
-- ID of the second callplan : called seconds needed to switch to the next CallplanID <br/>
-- if not needed seconds are defined it will automatically switch to the next one <br/>
-- if defined we will sum the previous needed seconds and check if the caller had done at least the amount of calls necessary to go to the next step and have the amount of seconds needed<br/>
value example for callplan_deck_minute_threshold = 1:300, 2:60, 3',
'0', '11', NULL);


ALTER TABLE cc_call ADD dnid CHAR( 40 );

-- update password field
ALTER TABLE cc_ui_authen CHANGE password pwd_encoded VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;

-- CHANGE SECURITY ABOUT PASSWORD : All password will be changed to "changepassword"
UPDATE cc_ui_authen SET pwd_encoded = '410fc6268dd3332226de95e42d9efa4046c5463769d7493b85e65cfa5c26362dc2455cc23c0bc5831deb008def4ab11a9eaa9b76ba3f377da134f39ec60dd758';


ALTER TABLE cc_card ADD company_name VARCHAR( 50 ) NULL ,
ADD company_website VARCHAR( 60 ) NULL ,
ADD VAT_RN VARCHAR( 40 ) NULL ,
ADD traffic BIGINT NULL ,
ADD traffic_target MEDIUMTEXT NULL ;

ALTER TABLE cc_logpayment ADD added_refill SMALLINT NOT NULL DEFAULT '0';

-- Add payment history in customer WebUI
INSERT INTO cc_config( config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues )
VALUES ('Payment Historique Modules', 'payment', '1', 'Enable or Disable the module of payment historique for the customers', 1, 3, 'yes,no');

-- modify the field type to authoriz to search by sell rate
ALTER TABLE cc_call CHANGE calledrate calledrate DECIMAL( 15, 5 ) NULL DEFAULT NULL;

-- Delete old menufile.
 DELETE FROM cc_config WHERE config_key = 'file_conf_enter_menulang' ;

INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ('Menu Language Order', 'conf_order_menulang', 'en:fr:es', 'Enter the list of languages authorized for the menu.Use the code language separate by a colon charactere e.g: en:es:fr', '0', '11', NULL);
INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ( 'Disable annoucement the second of the times that the card can call', 'disable_announcement_seconds', '0', 'Desactived the annoucement of the seconds when there are more of one minutes (values : yes - no)', '1', '11', 'yes,no');
INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ( 'Charge for the paypal extra fees', 'charge_paypal_fee', '0', 'Actived, if you want assum the fee of paypal and don''t apply it on the customer (values : yes - no)', '1', '5', 'yes,no');



-- Optimization on terminatecause
ALTER TABLE cc_call ADD COLUMN terminatecauseid INT (1) DEFAULT 1;
UPDATE cc_call SET terminatecauseid=1 WHERE terminatecause='ANSWER';
UPDATE cc_call SET terminatecauseid=1 WHERE terminatecause='ANSWERED';
UPDATE cc_call SET terminatecauseid=2 WHERE terminatecause='BUSY';
UPDATE cc_call SET terminatecauseid=3 WHERE terminatecause='NOANSWER';
UPDATE cc_call SET terminatecauseid=4 WHERE terminatecause='CANCEL';
UPDATE cc_call SET terminatecauseid=5 WHERE terminatecause='CONGESTION';
UPDATE cc_call SET terminatecauseid=6 WHERE terminatecause='CHANUNAVAIL';

ALTER TABLE cc_call DROP terminatecause;
ALTER TABLE cc_call ADD INDEX ( terminatecauseid );

-- Add index on prefix
ALTER TABLE cc_prefix ADD INDEX ( prefixe );

-- optimization on CDR
ALTER TABLE cc_call ADD COLUMN id_cc_prefix INT (11) DEFAULT 0;
ALTER TABLE cc_ratecard ADD COLUMN id_cc_prefix INT (11) DEFAULT 0;

ALTER TABLE cc_call DROP username;
ALTER TABLE cc_call DROP destination;
ALTER TABLE cc_call DROP startdelay;
ALTER TABLE cc_call DROP stopdelay;
ALTER TABLE cc_call DROP usertariff;
ALTER TABLE cc_call DROP calledprovider;
ALTER TABLE cc_call DROP calledcountry;
ALTER TABLE cc_call DROP calledsub;


-- Update all rates values to use Decimal
ALTER TABLE cc_ratecard CHANGE buyrate buyrate decimal(15,5) NOT NULL DEFAULT '0';
ALTER TABLE cc_ratecard CHANGE rateinitial rateinitial decimal(15,5) NOT NULL DEFAULT '0';
ALTER TABLE cc_ratecard CHANGE connectcharge connectcharge decimal(15,5) NOT NULL DEFAULT '0';
ALTER TABLE cc_ratecard CHANGE disconnectcharge disconnectcharge decimal(15,5) NOT NULL DEFAULT '0';
ALTER TABLE cc_ratecard CHANGE stepchargea stepchargea decimal(15,5) NOT NULL DEFAULT '0';
ALTER TABLE cc_ratecard CHANGE chargea chargea decimal(15,5) NOT NULL DEFAULT '0';
ALTER TABLE cc_ratecard CHANGE stepchargeb stepchargeb decimal(15,5) NOT NULL DEFAULT '0';
ALTER TABLE cc_ratecard CHANGE chargeb chargeb decimal(15,5) NOT NULL DEFAULT '0';
ALTER TABLE cc_ratecard CHANGE stepchargeb stepchargeb decimal(15,5) NOT NULL DEFAULT '0';
ALTER TABLE cc_ratecard CHANGE chargeb chargeb decimal(15,5) NOT NULL DEFAULT '0';
ALTER TABLE cc_ratecard CHANGE minimal_cost minimal_cost decimal(15,5) NOT NULL DEFAULT '0';



-- change perms for new menu
UPDATE cc_ui_authen SET perms = '5242879' WHERE userid=1 LIMIT 1;

-- correct card group
ALTER TABLE cc_card_group DROP id_agi_conf;


CREATE TABLE cc_cardgroup_service (
	id_card_group INT NOT NULL ,
	id_service INT NOT NULL,
	PRIMARY KEY ( id_card_group , id_service )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ('Cents Currency Associated', 'currency_cents_association', '', 'Define all the audio (without file extensions) that you want to play according to cents currency (use , to separate, ie "amd:lumas").By default the file used is "prepaid-cents" .Use plural to define the cents currency sound, but import two sounds but cents currency defined : ending by ''s'' and not ending by ''s'' (i.e. for lumas , add 2 files : ''lumas'' and ''luma'') ', '0', '11', NULL);

ALTER TABLE cc_call DROP calledrate, DROP buyrate;


-- ------------------------------------------------------
-- for AutoDialer
-- ------------------------------------------------------

-- Create phonebook for
CREATE TABLE cc_phonebook (
	id 				INT NOT NULL AUTO_INCREMENT ,
	name 			CHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
	description 	MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_bin NULL ,
	PRIMARY KEY ( id )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE cc_phonenumber (
	id 				BIGINT NOT NULL AUTO_INCREMENT ,
	id_phonebook 	INT NOT NULL ,
	number 			CHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
	name 			CHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_bin NULL ,
	creationdate 	TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
	status 			SMALLINT NOT NULL DEFAULT '1',
	info 			MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_bin NULL,
	PRIMARY KEY ( id )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

ALTER TABLE cc_phonebook ADD id_card BIGINT NOT NULL ;

CREATE TABLE cc_campaign_phonebook (
	id_campaign 	INT NOT NULL ,
	id_phonebook 	INT NOT NULL,
	PRIMARY KEY ( id_campaign , id_phonebook )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

ALTER TABLE cc_campaign CHANGE campaign_name name CHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
CHANGE enable status INT( 11 ) NOT NULL DEFAULT '1';

ALTER TABLE cc_campaign ADD frequency INT NOT NULL DEFAULT '20';

CREATE TABLE cc_campaign_phonestatus (
	id_phonenumber BIGINT NOT NULL ,
	id_campaign INT NOT NULL ,
	id_callback VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
	status INT NOT NULL DEFAULT '0',
	lastuse TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY ( id_phonenumber , id_campaign )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

ALTER TABLE cc_campaign CHANGE id_trunk id_card BIGINT NOT NULL DEFAULT '0';
ALTER TABLE cc_campaign ADD forward_number CHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_bin NULL;

DROP TABLE cc_phonelist;

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES
( 'Context Campaign''s Callback', 'context_campaign_callback', 'a2billing-campaign-callback', 'Context to use in Campaign of Callback', '0', '2', NULL);

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES
( 'Default Context forward Campaign''s Callback ', 'default_context_campaign', 'campaign', 'Context to use by default to forward the call in Campaign of Callback', '0', '2', NULL);

ALTER TABLE cc_campaign ADD daily_start_time TIME NOT NULL DEFAULT '10:00:00',
ADD daily_stop_time TIME NOT NULL DEFAULT '18:00:00',
ADD monday TINYINT NOT NULL DEFAULT '1',
ADD tuesday TINYINT NOT NULL DEFAULT '1',
ADD wednesday TINYINT NOT NULL DEFAULT '1',
ADD thursday TINYINT NOT NULL DEFAULT '1',
ADD friday TINYINT NOT NULL DEFAULT '1',
ADD saturday TINYINT NOT NULL DEFAULT '0',
ADD sunday TINYINT NOT NULL DEFAULT '0';

ALTER TABLE cc_campaign ADD id_cid_group INT NOT NULL ;

CREATE TABLE cc_campaign_config (
	id INT NOT NULL AUTO_INCREMENT ,
	name VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
	flatrate DECIMAL(15,5) DEFAULT 0 NOT NULL,
	context VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
	description MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_bin NULL ,
	PRIMARY KEY ( id )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE cc_campaignconf_cardgroup (
	id_campaign_config INT NOT NULL ,
	id_card_group INT NOT NULL ,
	PRIMARY KEY ( id_campaign_config , id_card_group )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE cc_campaign ADD id_campaign_config INT NOT NULL ;


-- ------------------------------------------------------
-- for Agent
-- ------------------------------------------------------

ALTER TABLE cc_card ADD COLUMN discount decimal(5,2) NOT NULL DEFAULT '0';


-- New config parameter to display card list : card_show_field_list
ALTER TABLE cc_config MODIFY config_value VARCHAR( 300 );
INSERT INTO  cc_config (config_title,config_key,config_value,config_description,config_valuetype,config_group_id) values ('Card Show Fields','card_show_field_list','id:,username:, useralias:, lastname:,id_group:, id_agent:,  credit:, tariff:, status:, language:, inuse:, currency:, sip_buddy:, iax_buddy:, nbused:,','Fields to show in Customer. Order is important. You can setup size of field using "fieldname:10%" notation or "fieldname:" for harcoded size,"fieldname" for autosize. <br/>You can use:<br/> id,username, useralias, lastname, id_group, id_agent,  credit, tariff, status, language, inuse, currency, sip_buddy, iax_buddy, nbused, firstname, email, discount, callerid',0,8);


-- ------------------------------------------------------
-- Cache system with SQLite Agent
-- ------------------------------------------------------
INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ( 'Enable CDR local cache', 'cache_enabled', '0', 'If you want enabled the local cache to save the CDR in a SQLite Database.', '1', '1', 'yes,no'),
( 'Path for the CDR cache file', 'cache_path', '/etc/asterisk/cache_a2billing', 'Defined the file that you want use for the CDR cache to save the CDR in a local SQLite database.', '0', '1', NULL);


ALTER TABLE cc_logrefill ADD COLUMN refill_type TINYINT NOT NULL DEFAULT 0;
ALTER TABLE cc_logpayment ADD COLUMN payment_type TINYINT NOT NULL DEFAULT 0;


-- ------------------------------------------------------
-- Add management of the web customer in groups
-- ------------------------------------------------------
ALTER TABLE cc_card_group ADD users_perms INT NOT NULL DEFAULT '0';



-- ------------------------------------------------------
-- PNL report
-- ------------------------------------------------------
INSERT INTO  cc_config(config_title,config_key,config_value,config_description,config_valuetype,config_group_id) values
('PNL Pay Phones','report_pnl_pay_phones','(8887798764,0.02,0.06)','Info for PNL report. Must be in form "(number1,buycost,sellcost),(number2,buycost,sellcost)", number can be prefix, i.e 1800',0,8);
INSERT INTO  cc_config(config_title,config_key,config_value,config_description,config_valuetype,config_group_id) values
('PNL Toll Free Numbers','report_pnl_toll_free','(6136864646,0.1,0),(6477249717,0.1,0)','Info for PNL report. must be in form "(number1,buycost,sellcost),(number2,buycost,sellcost)", number can be prefix, i.e 1800',0,8);



-- ------------------------------------------------------
-- Update to use VarChar instead of Char
-- ------------------------------------------------------
ALTER TABLE cc_call CHANGE sessionid sessionid VARCHAR( 40 ) NOT NULL;
ALTER TABLE cc_call CHANGE uniqueid uniqueid VARCHAR( 30 ) NOT NULL;
ALTER TABLE cc_call CHANGE nasipaddress nasipaddress VARCHAR( 30 ) NOT NULL;
ALTER TABLE cc_call CHANGE calledstation calledstation VARCHAR( 30 ) NOT NULL;
ALTER TABLE cc_call CHANGE src src VARCHAR( 40 ) NOT NULL;
ALTER TABLE cc_call CHANGE dnid dnid VARCHAR( 40 ) NOT NULL;

ALTER TABLE cc_card CHANGE username username VARCHAR( 50 ) NOT NULL;
ALTER TABLE cc_card CHANGE useralias useralias VARCHAR( 50 ) NOT NULL;
ALTER TABLE cc_card CHANGE uipass uipass VARCHAR( 50 ) NOT NULL;
ALTER TABLE cc_card CHANGE lastname lastname VARCHAR( 50 ) NOT NULL;
ALTER TABLE cc_card CHANGE firstname firstname VARCHAR( 50 ) NOT NULL;
ALTER TABLE cc_card CHANGE address address VARCHAR( 100 ) NOT NULL;
ALTER TABLE cc_card CHANGE city city VARCHAR( 40 ) NOT NULL;
ALTER TABLE cc_card CHANGE state state VARCHAR( 40 ) NOT NULL;
ALTER TABLE cc_card CHANGE country country VARCHAR( 40 ) NOT NULL;
ALTER TABLE cc_card CHANGE zipcode zipcode VARCHAR( 20 ) NOT NULL;
ALTER TABLE cc_card CHANGE phone phone VARCHAR( 20 ) NOT NULL;
ALTER TABLE cc_card CHANGE email email VARCHAR( 70 ) NOT NULL;
ALTER TABLE cc_card CHANGE fax fax VARCHAR( 20 ) NOT NULL;
ALTER TABLE cc_card CHANGE redial redial VARCHAR( 50 ) NOT NULL;
ALTER TABLE cc_card CHANGE callback callback VARCHAR( 50 ) NOT NULL;
ALTER TABLE cc_card CHANGE loginkey loginkey VARCHAR( 40 ) NOT NULL;
ALTER TABLE cc_card CHANGE tag tag VARCHAR( 50 ) NOT NULL;
ALTER TABLE cc_card CHANGE email_notification email_notification VARCHAR( 70 ) NOT NULL;
ALTER TABLE cc_card CHANGE company_name company_name VARCHAR( 50 ) NOT NULL;
ALTER TABLE cc_card CHANGE company_website company_website VARCHAR( 60 ) NOT NULL;
ALTER TABLE cc_card CHANGE vat_rn vat_rn VARCHAR( 40 ) NOT NULL;
ALTER TABLE cc_card CHANGE traffic_target traffic_target VARCHAR( 300 ) NOT NULL;

ALTER TABLE cc_callerid CHANGE cid cid VARCHAR( 100 ) NOT NULL;


ALTER TABLE cc_iax_buddies CHANGE name name VARCHAR(80) NOT NULL;
ALTER TABLE cc_iax_buddies CHANGE accountcode accountcode VARCHAR(20) NOT NULL;
ALTER TABLE cc_iax_buddies CHANGE regexten regexten VARCHAR(20) NOT NULL;
ALTER TABLE cc_iax_buddies CHANGE callerid callerid VARCHAR(80) NOT NULL;
ALTER TABLE cc_iax_buddies CHANGE context context VARCHAR(80) NOT NULL;
ALTER TABLE cc_iax_buddies CHANGE fromuser fromuser VARCHAR(80) NOT NULL;
ALTER TABLE cc_iax_buddies CHANGE fromdomain fromdomain VARCHAR(80) NOT NULL;
ALTER TABLE cc_iax_buddies CHANGE host host VARCHAR(31) NOT NULL;
ALTER TABLE cc_iax_buddies CHANGE insecure insecure VARCHAR(20) NOT NULL;
ALTER TABLE cc_iax_buddies CHANGE mailbox mailbox VARCHAR(50) NOT NULL;
ALTER TABLE cc_iax_buddies CHANGE md5secret md5secret VARCHAR(80) NOT NULL;
ALTER TABLE cc_iax_buddies CHANGE permit permit VARCHAR(95) NOT NULL;
ALTER TABLE cc_iax_buddies CHANGE deny deny VARCHAR(95) NOT NULL;
ALTER TABLE cc_iax_buddies CHANGE mask mask VARCHAR(95) NOT NULL;
ALTER TABLE cc_iax_buddies CHANGE secret secret VARCHAR(80) NOT NULL;
ALTER TABLE cc_iax_buddies CHANGE username username VARCHAR(80) NOT NULL;
ALTER TABLE cc_iax_buddies CHANGE disallow disallow VARCHAR(100) NOT NULL;
ALTER TABLE cc_iax_buddies CHANGE allow allow VARCHAR(100) NOT NULL;
ALTER TABLE cc_iax_buddies CHANGE musiconhold musiconhold VARCHAR(100) NOT NULL;
ALTER TABLE cc_iax_buddies CHANGE canreinvite canreinvite VARCHAR(20) NOT NULL;

ALTER TABLE cc_sip_buddies CHANGE name name VARCHAR(80) NOT NULL;
ALTER TABLE cc_sip_buddies CHANGE accountcode accountcode VARCHAR(20) NOT NULL;
ALTER TABLE cc_sip_buddies CHANGE regexten regexten VARCHAR(20) NOT NULL;
ALTER TABLE cc_sip_buddies CHANGE callerid callerid VARCHAR(80) NOT NULL;
ALTER TABLE cc_sip_buddies CHANGE context context VARCHAR(80) NOT NULL;
ALTER TABLE cc_sip_buddies CHANGE fromuser fromuser VARCHAR(80) NOT NULL;
ALTER TABLE cc_sip_buddies CHANGE fromdomain fromdomain VARCHAR(80) NOT NULL;
ALTER TABLE cc_sip_buddies CHANGE host host VARCHAR(31) NOT NULL;
ALTER TABLE cc_sip_buddies CHANGE insecure insecure VARCHAR(20) NOT NULL;
ALTER TABLE cc_sip_buddies CHANGE mailbox mailbox VARCHAR(50) NOT NULL;
ALTER TABLE cc_sip_buddies CHANGE md5secret md5secret VARCHAR(80) NOT NULL;
ALTER TABLE cc_sip_buddies CHANGE permit permit VARCHAR(95) NOT NULL;
ALTER TABLE cc_sip_buddies CHANGE deny deny VARCHAR(95) NOT NULL;
ALTER TABLE cc_sip_buddies CHANGE mask mask VARCHAR(95) NOT NULL;
ALTER TABLE cc_sip_buddies CHANGE secret secret VARCHAR(80) NOT NULL;
ALTER TABLE cc_sip_buddies CHANGE username username VARCHAR(80) NOT NULL;
ALTER TABLE cc_sip_buddies CHANGE disallow disallow VARCHAR(100) NOT NULL;
ALTER TABLE cc_sip_buddies CHANGE allow allow VARCHAR(100) NOT NULL;
ALTER TABLE cc_sip_buddies CHANGE musiconhold musiconhold VARCHAR(100) NOT NULL;
ALTER TABLE cc_sip_buddies CHANGE fullcontact fullcontact VARCHAR(80) NOT NULL;
ALTER TABLE cc_sip_buddies CHANGE setvar setvar VARCHAR(100) NOT NULL;
ALTER TABLE cc_sip_buddies CHANGE canreinvite canreinvite VARCHAR(20) NOT NULL;


-- ------------------------------------------------------
-- Add restricted rules on the call system for customers
-- ------------------------------------------------------

CREATE TABLE cc_restricted_phonenumber (
	id BIGINT NOT NULL AUTO_INCREMENT ,
	number VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
	id_card BIGINT NOT NULL,
	PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE cc_card ADD restriction TINYINT NOT NULL DEFAULT '0';

-- remove callback from card
ALTER TABLE cc_card DROP COLUMN callback;

-- ADD IAX TRUNKING
ALTER TABLE cc_iax_buddies ADD trunk CHAR(3) DEFAULT 'no';

-- Refactor Agent Section
ALTER TABLE cc_card DROP id_agent;
ALTER TABLE cc_card_group ADD id_agent INT NOT NULL DEFAULT '0';

-- remove old template invoice
ALTER TABLE cc_card DROP template_invoice;
ALTER TABLE cc_card DROP template_outstanding;

-- rename vat field
ALTER TABLE cc_card CHANGE VAT_RN vat_rn VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL;

-- add amount
ALTER TABLE cc_phonenumber ADD amount INT NOT NULL DEFAULT '0';


-- add company to Agent
ALTER TABLE cc_agent ADD COLUMN company varchar(50);


-- Change AGI Verbosity & logging
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues)
VALUES ('Verbosity', 'verbosity_level', '0', '0 = FATAL; 1 = ERROR; WARN = 2 ; INFO = 3 ; DEBUG = 4', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues)
VALUES ('Logging', 'logging_level', '3', '0 = FATAL; 1 = ERROR; WARN = 2 ; INFO = 3 ; DEBUG = 4', 0, 11, NULL);


ALTER TABLE cc_ticket ADD creator_type TINYINT NOT NULL DEFAULT '0';
ALTER TABLE cc_ticket_comment CHANGE is_admin creator_type TINYINT NOT NULL DEFAULT '0';

ALTER TABLE cc_ratecard ADD COLUMN announce_time_correction decimal(5,3) NOT NULL DEFAULT 1.0;


ALTER TABLE cc_agent DROP climit;

CREATE TABLE cc_agent_cardgroup (
	id_agent INT NOT NULL ,
	id_card_group INT NOT NULL ,
	PRIMARY KEY ( id_agent , id_card_group )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

ALTER TABLE cc_card_group DROP id_agent;

ALTER TABLE cc_agent ADD secret VARCHAR( 20 ) NOT NULL;

-- optimization on CDR
ALTER TABLE cc_ratecard DROP destination;
ALTER TABLE cc_call DROP id_cc_prefix;
ALTER TABLE cc_ratecard DROP id_cc_prefix;
ALTER TABLE cc_call ADD COLUMN destination INT (11) DEFAULT 0;
ALTER TABLE cc_ratecard ADD COLUMN destination INT (11) DEFAULT 0;


UPDATE cc_card_group SET description = 'This group is the default group used when you create a customer. It''s forbidden to delete it because you need at least one group but you can edit it.' WHERE id = 1 LIMIT 1 ;
UPDATE cc_card_group SET users_perms = '129022' WHERE id = 1;

ALTER TABLE cc_ticket ADD viewed_cust TINYINT NOT NULL DEFAULT '1',
ADD viewed_agent TINYINT NOT NULL DEFAULT '1',
ADD viewed_admin TINYINT NOT NULL DEFAULT '1';


ALTER TABLE cc_ticket_comment ADD viewed_cust TINYINT NOT NULL DEFAULT '1',
ADD viewed_agent TINYINT NOT NULL DEFAULT '1',
ADD viewed_admin TINYINT NOT NULL DEFAULT '1';

ALTER TABLE cc_ui_authen ADD email VARCHAR( 70 ) CHARACTER SET utf8 COLLATE utf8_bin NULL ;

ALTER TABLE cc_logrefill CHANGE id id BIGINT NOT NULL AUTO_INCREMENT  ;

-- Refill table for Agent
CREATE TABLE cc_logrefill_agent (
	id BIGINT NOT NULL auto_increment,
	date timestamp NOT NULL default CURRENT_TIMESTAMP,
	credit float NOT NULL,
	agent_id BIGINT NOT NULL,
	description mediumtext collate utf8_bin,
	refill_type tinyint NOT NULL default '0',
	PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- logpayment table for Agent
CREATE TABLE cc_logpayment_agent (
	id BIGINT NOT NULL auto_increment,
	date timestamp NOT NULL default CURRENT_TIMESTAMP,
	payment float NOT NULL,
	agent_id BIGINT NOT NULL,
	id_logrefill BIGINT default NULL,
	description mediumtext collate utf8_bin,
	added_refill tinyint NOT NULL default '0',
	payment_type tinyint NOT NULL default '0',
	PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- Table structure for table cc_prefix
DROP TABLE IF EXISTS cc_prefix;
CREATE TABLE IF NOT EXISTS cc_prefix (
	prefix bigint(20) NOT NULL auto_increment,
	destination varchar(60) collate utf8_bin NOT NULL,
	PRIMARY KEY (prefix),
	KEY destination (destination)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



INSERT INTO cc_config_group (group_title ,group_description) VALUES ( 'dashboard', 'This configuration group handles the dashboard configuration');

INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ( 'Enable info module about customers', 'customer_info_enabled', 'LEFT', 'If you want enabled the info module customer and place it somewhere on the home page.', '0', '13', 'NONE,LEFT,CENTER,RIGHT');
INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ( 'Enable info module about refills', 'refill_info_enabled', 'CENTER', 'If you want enabled the info module refills and place it somewhere on the home page.', '0', '13', 'NONE,LEFT,CENTER,RIGHT');
INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ( 'Enable info module about payments', 'payment_info_enabled', 'CENTER', 'If you want enabled the info module payments and place it somewhere on the home page.', '0', '13', 'NONE,LEFT,CENTER,RIGHT');
INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ( 'Enable info module about calls', 'call_info_enabled', 'RIGHT', 'If you want enabled the info module calls and place it somewhere on the home page.', '0', '13', 'NONE,LEFT,CENTER,RIGHT');


-- New Invoice Tables
RENAME TABLE cc_invoices  TO bkp_cc_invoices;
RENAME TABLE cc_invoice  TO bkp_cc_invoice;
RENAME TABLE cc_invoice_history  TO bkp_cc_invoice_history;
RENAME TABLE cc_invoice_items  TO bkp_cc_invoice_items;

CREATE TABLE cc_invoice (
	id BIGINT NOT NULL AUTO_INCREMENT ,
	reference VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_bin NULL ,
	id_card BIGINT NOT NULL ,
	date timestamp NOT NULL default CURRENT_TIMESTAMP,
	paid_status TINYINT NOT NULL DEFAULT '0',
	status TINYINT NOT NULL DEFAULT '0',
	title VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
	description MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
	PRIMARY KEY ( id ) ,
	UNIQUE (reference)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE cc_invoice_item (
	id BIGINT NOT NULL AUTO_INCREMENT ,
	id_invoice BIGINT NOT NULL ,
	date timestamp NOT NULL default CURRENT_TIMESTAMP,
	price DECIMAL( 15, 5 ) NOT NULL DEFAULT '0',
	VAT DECIMAL( 4, 2 ) NOT NULL DEFAULT '0',
	description MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
	PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE cc_invoice_conf (
	id INT NOT NULL AUTO_INCREMENT ,
	key_val VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
	value VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
	PRIMARY KEY ( id ),
	UNIQUE (key_val)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO cc_invoice_conf (key_val ,value)
	VALUES 	('company_name', 'My company'),
		('address', 'address'),
		('zipcode', 'xxxx'),
		('country', 'country'),
		('city', 'city'),
		('phone', 'xxxxxxxxxxx'),
		('fax', 'xxxxxxxxxxx'),
		('email', 'xxxxxxx@xxxxxxx.xxx'),
		('vat', 'xxxxxxxxxx'),
		('web', 'www.xxxxxxx.xxx');

ALTER TABLE cc_logrefill ADD added_invoice TINYINT NOT NULL DEFAULT '0';

CREATE TABLE cc_invoice_payment (
	id_invoice BIGINT NOT NULL ,
	id_payment BIGINT NOT NULL ,
	PRIMARY KEY ( id_invoice , id_payment )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Enable PlugnPay Module', 'MODULE_PAYMENT_PLUGNPAY_STATUS', 'True', 'Do you want to accept payments through PlugnPay?', 'tep_cfg_select_option(array(\'True\', \'False\'), ');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description) values ('Login Username', 'MODULE_PAYMENT_PLUGNPAY_LOGIN', 'Your Login Name', 'Enter your PlugnPay account username');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description) values ('Publisher Email', 'MODULE_PAYMENT_PLUGNPAY_PUBLISHER_EMAIL', 'Enter Your Email Address', 'The email address you want PlugnPay conformations sent to');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('cURL Setup', 'MODULE_PAYMENT_PLUGNPAY_CURL', 'Not Compiled', 'Whether cURL is compiled into PHP or not.  Windows users, select not compiled.', 'tep_cfg_select_option(array(\'Not Compiled\', \'Compiled\'), ');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description) values ('cURL Path', 'MODULE_PAYMENT_PLUGNPAY_CURL_PATH', 'The Path To cURL', 'For Not Compiled mode only, input path to the cURL binary (i.e. c:/curl/curl)');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Transaction Mode', 'MODULE_PAYMENT_PLUGNPAY_TESTMODE', 'Test', 'Transaction mode used for processing orders', 'tep_cfg_select_option(array(\'Test\', \'Test And Debug\', \'Production\'), ');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Require CVV', 'MODULE_PAYMENT_PLUGNPAY_CVV', 'yes', 'Ask For CVV information', 'tep_cfg_select_option(array(\'yes\', \'no\'), ');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Transaction Method', 'MODULE_PAYMENT_PLUGNPAY_PAYMETHOD', 'credit', 'Transaction method used for processing orders.<br><b>NOTE:</b> Selecting \'onlinecheck\' assumes you\'ll offer \'credit\' as well.',  'tep_cfg_select_option(array(\'credit\', \'onlinecheck\'), ');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Authorization Type', 'MODULE_PAYMENT_PLUGNPAY_CCMODE', 'authpostauth', 'Credit card processing mode', 'tep_cfg_select_option(array(\'authpostauth\', \'authonly\'), ');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Customer Notifications', 'MODULE_PAYMENT_PLUGNPAY_DONTSNDMAIL', 'yes', 'Should PlugnPay not email a receipt to the customer?', 'tep_cfg_select_option(array(\'yes\', \'no\'), ');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Accepted Credit Cards', 'MODULE_PAYMENT_PLUGNPAY_ACCEPTED_CC', 'Mastercard, Visa', 'The credit cards you currently accept', '_selectOptions(array(\'Amex\',\'Discover\', \'Mastercard\', \'Visa\'), ');


INSERT INTO cc_payment_methods (payment_method,payment_filename,active) VALUES ('plugnpay','plugnpay.php','t');





ALTER TABLE cc_card_archive DROP COLUMN  callback;
-- already present ALTER TABLE cc_card_archive ADD COLUMN  id_timezone int(11) default '0';
ALTER TABLE cc_card_archive ADD COLUMN  voicemail_permitted int(11) NOT NULL default '0';
ALTER TABLE cc_card_archive ADD COLUMN  voicemail_activated smallint(6) NOT NULL default '0';
ALTER TABLE cc_card_archive ADD COLUMN  last_notification timestamp NULL default NULL;
ALTER TABLE cc_card_archive ADD COLUMN  email_notification char(70) collate utf8_bin default NULL;
ALTER TABLE cc_card_archive ADD COLUMN  notify_email smallint(6) NOT NULL default '0';
ALTER TABLE cc_card_archive ADD COLUMN  credit_notification int(11) NOT NULL default '-1';
ALTER TABLE cc_card_archive ADD COLUMN  id_group int(11) NOT NULL default '1';
ALTER TABLE cc_card_archive ADD COLUMN  company_name varchar(50) collate utf8_bin default NULL;
ALTER TABLE cc_card_archive ADD COLUMN  company_website varchar(60) collate utf8_bin default NULL;
ALTER TABLE cc_card_archive ADD COLUMN  VAT_RN varchar(40) collate utf8_bin default NULL;
ALTER TABLE cc_card_archive ADD COLUMN  traffic bigint(20) default NULL;
ALTER TABLE cc_card_archive ADD COLUMN  traffic_target mediumtext collate utf8_bin;
ALTER TABLE cc_card_archive ADD COLUMN  discount decimal(5,2) NOT NULL default '0.00';
ALTER TABLE cc_card_archive ADD COLUMN  restriction tinyint(4) NOT NULL default '0';
ALTER TABLE cc_card_archive DROP COLUMN template_invoice;
ALTER TABLE cc_card_archive DROP COLUMN template_outstanding;
ALTER TABLE cc_card_archive DROP COLUMN mac_addr;
ALTER TABLE cc_card_archive ADD COLUMN mac_addr char(17) collate utf8_bin NOT NULL default '00-00-00-00-00-00';

CREATE TABLE cc_billing_customer (
	id BIGINT NOT NULL AUTO_INCREMENT,
	id_card BIGINT NOT NULL ,
	date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
	id_invoice BIGINT NOT NULL ,
	PRIMARY KEY ( id )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- PLUGNPAY
ALTER TABLE cc_epayment_log ADD COLUMN cvv VARCHAR(4);
ALTER TABLE cc_epayment_log ADD COLUMN credit_card_type VARCHAR(20);
ALTER TABLE cc_epayment_log ADD COLUMN currency VARCHAR(4);


INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues)
VALUES ('PlugnPay Payment URL', 'plugnpay_payment_url', 'https://pay1.plugnpay.com/payment/pnpremote.cgi', 'Define here the URL of PlugnPay gateway.', 0, 5, NULL);


-- Currency handle update
UPDATE cc_configuration SET configuration_description = 'The alternative currency to use for credit card transactions if the system currency is not usable' WHERE configuration_key = 'MODULE_PAYMENT_PAYPAL_CURRENCY';
UPDATE cc_configuration SET configuration_title = 'Alternative Transaction Currency' WHERE configuration_key = 'MODULE_PAYMENT_PAYPAL_CURRENCY';
UPDATE cc_configuration SET configuration_description = 'The alternative currency to use for credit card transactions if the system currency is not usable' WHERE configuration_key = 'MODULE_PAYMENT_MONEYBOOKERS_CURRENCY';
UPDATE cc_configuration SET configuration_title = 'Alternative Transaction Currency' WHERE configuration_key = 'MODULE_PAYMENT_MONEYBOOKERS_CURRENCY';
UPDATE cc_configuration SET set_function = 'tep_cfg_select_option(array(''USD'',''CAD'',''EUR'',''GBP'',''JPY''), ' WHERE configuration_key = 'MODULE_PAYMENT_PAYPAL_CURRENCY';
UPDATE cc_configuration SET set_function = 'tep_cfg_select_option(array(''EUR'', ''USD'', ''GBP'', ''HKD'', ''SGD'', ''JPY'', ''CAD'', ''AUD'', ''CHF'', ''DKK'', ''SEK'', ''NOK'', ''ILS'', ''MYR'', ''NZD'', ''TWD'', ''THB'', ''CZK'', ''HUF'', ''SKK'', ''ISK'', ''INR''), '  WHERE configuration_key = 'MODULE_PAYMENT_MONEYBOOKERS_CURRENCY';

ALTER TABLE cc_payment_methods DROP active;


ALTER TABLE cc_epayment_log ADD transaction_detail LONGTEXT NULL;

ALTER TABLE cc_invoice_item ADD id_billing BIGINT NULL ,
ADD billing_type VARCHAR( 10 ) NULL ;



-- DIDX.NET
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('DIDX ID', 'didx_id', '708XXX', 'DIDX parameter : ID', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('DIDX PASS', 'didx_pass', 'XXXXXXXXXX', 'DIDX parameter : Password', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('DIDX MIN RATING', 'didx_min_rating', '0', 'DIDX parameter : min rating', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('DIDX RING TO', 'didx_ring_to', '0', 'DIDX parameter : ring to', 0, 8, NULL);

-- Commission Agent
CREATE TABLE cc_agent_commission (
	id BIGINT NOT NULL AUTO_INCREMENT ,
	id_payment BIGINT NULL ,
	id_card BIGINT NOT NULL ,
	date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
	amount DECIMAL( 15, 5 ) NOT NULL ,
	PRIMARY KEY ( id )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

ALTER TABLE cc_card_group ADD id_agent INT NULL ;

DROP TABLE cc_agent_cardgroup;

ALTER TABLE cc_agent_commission ADD paid_status TINYINT NOT NULL DEFAULT '0';
ALTER TABLE cc_agent_commission ADD description MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_bin NULL ;





-- Card Serial Number
CREATE TABLE cc_card_seria (
	id INT NOT NULL AUTO_INCREMENT ,
	name CHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
	description MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_bin NULL,
	value	BIGINT NOT NULL DEFAULT 0,
	PRIMARY KEY ( id )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

ALTER TABLE cc_card ADD id_seria integer;
ALTER TABLE cc_card ADD serial BIGINT;
UPDATE cc_config SET config_description = concat(config_description,', id_seria, serial') WHERE config_key = 'card_show_field_list' ;

DELIMITER //
CREATE TRIGGER cc_card_serial_set BEFORE INSERT ON cc_card
FOR EACH ROW
BEGIN
	UPDATE cc_card_seria set value=value+1  where id=NEW.id_seria ;
	SELECT value INTO @serial from cc_card_seria where id=NEW.id_seria ;
	SET NEW.serial=@serial;
END
//
CREATE TRIGGER cc_card_serial_update BEFORE UPDATE ON cc_card
FOR EACH ROW
BEGIN
	IF NEW.id_seria<>OLD.id_seria OR OLD.id_seria IS NULL THEN
		UPDATE cc_card_seria set value=value+1  where id=NEW.id_seria ;
		SELECT value INTO @serial from cc_card_seria where id=NEW.id_seria ;
		SET NEW.serial=@serial;
	END IF;
END
//
DELIMITER ;


INSERT INTO  cc_config (config_title,config_key,config_value,config_description,config_valuetype,config_group_id) values('Card Serial Pad Length','card_serial_length','7','Value of zero padding for serial. If this value set to 3 serial wil looks like 001',0,8);



-- Reserve credit :
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Dial Balance reservation', 'dial_balance_reservation', '0.25', 'Credit to reserve from the balance when a call is made. This will prevent negative balance on huge peak.', 0, 11, NULL);


-- change the schema to authorize only one login
ALTER TABLE cc_agent ADD UNIQUE (login);
ALTER TABLE cc_ui_authen ADD UNIQUE (login);

-- update for invoice
ALTER TABLE cc_charge ADD charged_status TINYINT NOT NULL DEFAULT '0',
ADD invoiced_status TINYINT NOT NULL DEFAULT '0';
ALTER TABLE cc_did_use ADD reminded TINYINT NOT NULL DEFAULT '0';

ALTER TABLE cc_invoice_item CHANGE id_billing id_ext BIGINT( 20 ) NULL DEFAULT NULL;
ALTER TABLE cc_invoice_item CHANGE billing_type type_ext VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL;



-- update on configuration
ALTER TABLE cc_config_group ADD UNIQUE (group_title);
ALTER TABLE cc_config ADD config_group_title varchar(64) NOT NULL;

UPDATE cc_config SET config_group_title=(SELECT group_title FROM cc_config_group WHERE cc_config_group.id=cc_config.config_group_id);

ALTER TABLE cc_config DROP COLUMN config_group_id;


-- add receipt objects
CREATE TABLE cc_receipt (
	id BIGINT NOT NULL AUTO_INCREMENT ,
	id_card BIGINT NOT NULL ,
	date timestamp NOT NULL default CURRENT_TIMESTAMP,
	title VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
	description MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
	status TINYINT NOT NULL DEFAULT '0',
	PRIMARY KEY ( id )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE cc_receipt_item (
	id BIGINT NOT NULL AUTO_INCREMENT ,
	id_receipt BIGINT NOT NULL ,
	date timestamp NOT NULL default CURRENT_TIMESTAMP,
	price DECIMAL( 15, 5 ) NOT NULL DEFAULT '0',
	description MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
	id_ext BIGINT( 20 ) NULL DEFAULT NULL,
	type_ext VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
	PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE cc_logpayment CHANGE payment payment DECIMAL( 15, 5 ) NOT NULL;
ALTER TABLE cc_logpayment_agent CHANGE payment payment DECIMAL( 15, 5 ) NOT NULL;
ALTER TABLE cc_logrefill CHANGE credit credit DECIMAL( 15, 5 ) NOT NULL;
ALTER TABLE cc_logrefill_agent CHANGE credit credit DECIMAL( 15, 5 ) NOT NULL ;


-- changes from recurring services - bound to callplan
alter table cc_service add column operate_mode tinyint default 0;
alter table cc_service add column dialplan integer default 0;
alter table cc_service add column use_group tinyint default 0;

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_title, config_listvalues) VALUES ('Rate Export Fields', 'rate_export_field_list', 'destination, dialprefix, rateinitial', 'Fields to export in csv format from rates table.Use dest_name from prefix name', 0, 'webui', NULL);



-- ADD SIP REGSERVER
ALTER TABLE cc_sip_buddies ADD regserver varchar(20);


ALTER TABLE cc_logpayment ADD added_commission TINYINT NOT NULL DEFAULT '0';
-- Empty password view for OpenSips
CREATE VIEW cc_sip_buddies_empty AS SELECT
id, id_cc_card, name, accountcode, regexten, amaflags, callgroup, callerid, canreinvite, context, DEFAULTip, dtmfmode, fromuser, fromdomain, host, insecure, language, mailbox, md5secret, nat, permit, deny, mask, pickupgroup, port, qualify, restrictcid, rtptimeout, rtpholdtimeout, '' as secret, type, username, disallow, allow, musiconhold, regseconds, ipaddr, cancallforward, fullcontact, setvar
FROM cc_sip_buddies;


-- remove activatedbyuser
ALTER TABLE cc_card DROP activatedbyuser;


-- Agent epayment

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_title, config_listvalues) VALUES ('HTTP Server Agent', 'http_server_agent', 'http://www.mydomainname.com', 'Set the Server Address of Agent Website, It should be empty for productive Servers.', 0, 'epayment_method', NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_title, config_listvalues) VALUES ('HTTPS Server Agent', 'https_server_agent', 'https://www.mydomainname.com', 'https://localhost - Enter here your Secure Agents Server Address, should not be empty for productive servers.', 0, 'epayment_method', NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_title, config_listvalues) VALUES ('Server Agent IP/Domain', 'http_cookie_domain_agent', '26.63.165.200', 'Enter your Domain Name or IP Address for the Agents application, eg, 26.63.165.200.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_title, config_listvalues) VALUES ('Secure Server Agent IP/Domain', 'https_cookie_domain_agent', '26.63.165.200', 'Enter your Secure server Domain Name or IP Address for the Agents application, eg, 26.63.165.200.', 0, 'epayment_method', NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_title, config_listvalues) VALUES ('Application Agent Path', 'http_cookie_path_agent', '/agent/Public/', 'Enter the Physical path of your Agents Application on your server.', 0, 'epayment_method', NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_title, config_listvalues) VALUES ('Secure Application Agent Path', 'https_cookie_path_agent', '/agent/Public/', 'Enter the Physical path of your Agents Application on your Secure Server.', 0, 'epayment_method', NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_title, config_listvalues) VALUES ('Application Agent Physical Path', 'dir_ws_http_catalog_agent', '/agent/Public/', 'Enter the Physical path of your Agents Application on your server.', 0, 'epayment_method', NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_title, config_listvalues) VALUES ('Secure Application Agent Physical Path', 'dir_ws_https_catalog_agent', '/agent/Public/', 'Enter the Physical path of your Agents Application on your Secure server.', 0, 'epayment_method', NULL);

CREATE TABLE cc_epayment_log_agent (
	id BIGINT NOT NULL auto_increment,
	agent_id BIGINT NOT NULL default '0',
	amount DECIMAL( 15, 5 ) NOT NULL default '0',
	vat FLOAT NOT NULL default '0',
	paymentmethod char(50) collate utf8_bin NOT NULL,
	cc_owner varchar(64) collate utf8_bin default NULL,
	cc_number varchar(32) collate utf8_bin default NULL,
	cc_expires varchar(7) collate utf8_bin default NULL,
	creationdate timestamp NOT NULL default CURRENT_TIMESTAMP,
	`status` int(11) NOT NULL default '0',
	cvv varchar(4) collate utf8_bin default NULL,
	credit_card_type varchar(20) collate utf8_bin default NULL,
	currency varchar(4) collate utf8_bin default NULL,
	transaction_detail longtext collate utf8_bin,
	PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

ALTER TABLE cc_epayment_log CHANGE id id BIGINT NOT NULL AUTO_INCREMENT ,
	CHANGE cardid cardid BIGINT NOT NULL DEFAULT '0',
	CHANGE amount amount DECIMAL( 15, 5 ) NOT NULL DEFAULT '0';

ALTER TABLE cc_payments CHANGE id id BIGINT NOT NULL AUTO_INCREMENT ,
	CHANGE customers_id customers_id BIGINT NOT NULL DEFAULT '0';

CREATE TABLE cc_payments_agent (
	id BIGINT NOT NULL auto_increment,
	agent_id BIGINT collate utf8_bin NOT NULL,
	agent_name varchar(200) collate utf8_bin NOT NULL,
	agent_email_address varchar(96) collate utf8_bin NOT NULL,
	item_name varchar(127) collate utf8_bin default NULL,
	item_id varchar(127) collate utf8_bin default NULL,
	item_quantity int(11) NOT NULL default '0',
	payment_method varchar(32) collate utf8_bin NOT NULL,
	cc_type varchar(20) collate utf8_bin default NULL,
	cc_owner varchar(64) collate utf8_bin default NULL,
	cc_number varchar(32) collate utf8_bin default NULL,
	cc_expires varchar(4) collate utf8_bin default NULL,
	orders_status int(5) NOT NULL,
	orders_amount decimal(14,6) default NULL,
	last_modified datetime default NULL,
	date_purchased datetime default NULL,
	orders_date_finished datetime default NULL,
	currency char(3) collate utf8_bin default NULL,
	currency_value decimal(14,6) default NULL,
	PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE cc_agent_commission ADD id_agent INT NOT NULL ;

-- remove reseller field from logpayment & log refill
ALTER TABLE cc_logpayment DROP reseller_id;
ALTER TABLE cc_logrefill DROP reseller_id;


-- Add notification system
CREATE TABLE cc_notification (
	id 					BIGINT NOT NULL auto_increment,
	key_value 			varchar(40) collate utf8_bin default NULL,
	date 				timestamp NOT NULL default CURRENT_TIMESTAMP,
	priority 			TINYINT NOT NULL DEFAULT '0',
	from_type 			TINYINT NOT NULL ,
	from_id 			BIGINT NULL DEFAULT '0',
	PRIMARY KEY ( id )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE cc_notification_admin (
	id_notification BIGINT NOT NULL ,
	id_admin INT NOT NULL ,
	viewed TINYINT NOT NULL DEFAULT '0',
	PRIMARY KEY ( id_notification , id_admin )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- Add default value for support box
INSERT INTO cc_support (id ,name) VALUES (1, 'DEFAULT');
INSERT INTO cc_support_component (id ,id_support ,name ,activated) VALUES (1, 1, 'DEFAULT', 1);

DELETE FROM cc_config WHERE config_key = 'sipiaxinfo' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'cdr' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'invoice' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'voucher' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'paypal' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'speeddial' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'did' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'ratecard' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'simulator' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'callback' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'predictivedialer' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'callerid' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'webphone' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'support' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'payment' AND config_group_title = 'webcustomerui';

INSERT INTO cc_config_group (group_title ,group_description)
	VALUES ( 'webagentui', 'This configuration group handles Web Agent Interface.');
INSERT INTO cc_config (`config_title` ,`config_key` ,`config_value` ,`config_description` ,`config_valuetype` ,`config_listvalues` ,`config_group_title`)
	VALUES ( 'Personal Info', 'personalinfo', '1', 'Enable or disable the page which allow agent to modify its personal information.', '0', 'yes,no', 'webagentui');


-- Add index for SIP / IAX Friend
ALTER TABLE cc_iax_buddies ADD INDEX ( name );
ALTER TABLE cc_iax_buddies ADD INDEX ( host );
ALTER TABLE cc_iax_buddies ADD INDEX ( ipaddr );
ALTER TABLE cc_iax_buddies ADD INDEX ( port );

ALTER TABLE cc_sip_buddies ADD INDEX ( name );
ALTER TABLE cc_sip_buddies ADD INDEX ( host );
ALTER TABLE cc_sip_buddies ADD INDEX ( ipaddr );
ALTER TABLE cc_sip_buddies ADD INDEX ( port );


-- add parameters return_url_distant_login & return_url_distant_forgetpassword on webcustomerui
INSERT INTO `cc_config` (`config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES('Return URL distant Login', 'return_url_distant_login', '', 'URL for specific return if an error occur after login', 0, NULL, 'webcustomerui');
INSERT INTO `cc_config` (`config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES('Return URL distant Forget Password', 'return_url_distant_forgetpassword', '', 'URL for specific return if an error occur after forgetpassword', 0, NULL, 'webcustomerui');

CREATE TABLE cc_agent_signup (
	id BIGINT NOT NULL AUTO_INCREMENT ,
	id_agent INT NOT NULL ,
	code VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
	id_tariffgroup INT NOT NULL ,
	id_group INT NOT NULL ,
	PRIMARY KEY (id) ,
	UNIQUE (code)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

ALTER TABLE cc_agent DROP secret;

-- disable Authorize.net
DELETE FROM cc_payment_methods WHERE payment_method = 'Authorize.Net';
UPDATE cc_configuration SET configuration_value = 'False' WHERE configuration_key = 'MODULE_PAYMENT_AUTHORIZENET_STATUS';

ALTER TABLE cc_epayment_log CHANGE amount amount VARCHAR( 50 ) NOT NULL DEFAULT '0';
ALTER TABLE cc_epayment_log_agent CHANGE amount amount VARCHAR( 50 ) NOT NULL DEFAULT '0';

UPDATE cc_config SET config_value = 'card.id, username, useralias, lastname, credit, tariff, activated, language, inuse, currency, sip_buddy' WHERE config_key = 'card_export_field_list';
ALTER TABLE cc_tariffgroup CHANGE id_cc_package_offer id_cc_package_offer BIGINT( 20 ) NOT NULL DEFAULT '-1';

ALTER TABLE cc_epayment_log ADD item_type VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_bin NULL ,ADD item_id BIGINT NULL ;


-- Last registration
ALTER TABLE cc_sip_buddies ADD lastms varchar(11);


-- Add new SMTP Settings
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES ('SMTP Port', 'smtp_port', '25', 'Port to connect on the SMTP server', 0, NULL, 'global');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES ('SMTP Secure', 'smtp_secure', '', 'sets the prefix to the SMTP server : tls ; ssl', 0, NULL, 'global');

ALTER TABLE cc_support_component ADD type_user TINYINT NOT NULL DEFAULT '2';

