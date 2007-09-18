
CREATE TABLE cc_invoice_items (
	id 						BIGSERIAL NOT NULL,
	invoiceid 				INTEGER NOT NULL,
	invoicesection 			TEXT,
	designation 			TEXT,
	sub_designation 		TEXT,
	start_date 				DATE DEFAULT NULL,
	end_date 				date default NULL,
	bill_date 				date default NULL,
	calltime 				INTEGER DEFAULT NULL,
	nbcalls 				INTEGER DEFAULT NULL,
	quantity 				INTEGER DEFAULT NULL,
	price 					DECIMAL(15,5) DEFAULT NULL,
	buy_price				DECIMAL(15,5) DEFAULT NULL
);
ALTER TABLE ONLY cc_invoice_items
ADD CONSTRAINT cc_invoice_items_pkey PRIMARY KEY (id);


CREATE TABLE cc_invoice (
	id 						BIGSERIAL NOT NULL,
	cardid 					BIGINT DEFAULT 0 NOT NULL,
	invoicecreated_date 	TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
	amount 					DECIMAL(15,5) DEFAULT '0.00000',
	tax						DECIMAL(15,5) DEFAULT '0.00000',
	total					DECIMAL(15,5) DEFAULT '0.00000',
	filename				CHARACTER VARYING(255) NOT NULL,
	payment_status 			INT DEFAULT 0 NOT NULL,
	cover_call_startdate 	TIMESTAMP WITHOUT TIME ZONE,
	cover_call_enddate 		TIMESTAMP WITHOUT TIME ZONE,
	cover_charge_startdate 	TIMESTAMP WITHOUT TIME ZONE,
	cover_charge_enddate 	TIMESTAMP WITHOUT TIME ZONE,
	currency 				CHARACTER VARYING(3) NOT NULL,
	previous_balance 		DECIMAL(15,5) DEFAULT '0.00000',
	current_balance 		DECIMAL(15,5) DEFAULT '0.00000',
	templatefile 			CHARACTER VARYING(255) NOT NULL,
	username 				CHARACTER VARYING(50) NOT NULL,
	lastname 				CHARACTER VARYING(50) NOT NULL,
	firstname 				CHARACTER VARYING(50) NOT NULL,
	address 				CHARACTER VARYING(100) NOT NULL,
	city					CHARACTER VARYING(40) NOT NULL,
	state					CHARACTER VARYING(40) NOT NULL,
	country					CHARACTER VARYING(40) NOT NULL,
	zipcode 				CHARACTER VARYING(20) NOT NULL,
	phone					CHARACTER VARYING(20) NOT NULL,
	email					CHARACTER VARYING(70) NOT NULL,
	fax						CHARACTER VARYING(20) NOT NULL,
	vat						FLOAT DEFAULT NULL
);
ALTER TABLE ONLY cc_invoice
ADD CONSTRAINT cc_invoice_pkey PRIMARY KEY (id);


ALTER TABLE cc_charge DROP COLUMN id_cc_subscription_fee;

ALTER TABLE cc_charge ADD COLUMN id_cc_card_subscription BIGINT;
ALTER TABLE cc_charge ADD COLUMN cover_from DATE;
ALTER TABLE cc_charge ADD COLUMN cover_to 	DATE;


ALTER TABLE cc_trunk ADD COLUMN inuse INT DEFAULT 0;
ALTER TABLE cc_trunk ADD COLUMN maxuse INT DEFAULT -1;
ALTER TABLE cc_trunk ADD COLUMN status INT DEFAULT 1;
ALTER TABLE cc_trunk ADD COLUMN if_max_use INT DEFAULT 0;


CREATE TABLE cc_card_subscription (
	id 								BIGSERIAL NOT NULL,
	id_cc_card 						BIGINT DEFAULT 0 NOT NULL,
	id_subscription_fee 			BIGINT DEFAULT 0 NOT NULL,
    startdate 						TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    stopdate 						TIMESTAMP WITHOUT TIME ZONE,
	description 					TEXT
);
ALTER TABLE ONLY cc_card_subscription
ADD CONSTRAINT cc_card_subscription_pkey PRIMARY KEY (id);


ALTER TABLE cc_card DROP id_subscription_fee;
ALTER TABLE cc_card ADD COLUMN id_timezone INTEGER DEFAULT 0;

CREATE TABLE cc_config_group (
  	id 								BIGSERIAL NOT NULL,
	group_title 					CHARACTER VARYING(64) NOT NULL,	
	group_description 				CHARACTER VARYING(255) NOT NULL
);
ALTER TABLE ONLY cc_config_group
ADD CONSTRAINT cc_config_group_pkey PRIMARY KEY (id);


INSERT INTO cc_config_group (group_title, group_description) VALUES ('global', 'This configuration group handles the global settings for application.');
INSERT INTO cc_config_group (group_title, group_description) VALUES ('callback', 'This configuration group handles calllback settings.');
INSERT INTO cc_config_group (group_title, group_description) VALUES ('webcustomerui', 'This configuration group handles Web Customer User Interface.');
INSERT INTO cc_config_group (group_title, group_description) VALUES ('sip-iax-info', 'SIP & IAX client configuration information.');
INSERT INTO cc_config_group (group_title, group_description) VALUES ('epayment_method', 'Epayment Methods Configuration.');
INSERT INTO cc_config_group (group_title, group_description) VALUES ('signup', 'This configuration group handles the signup related settings.');
INSERT INTO cc_config_group (group_title, group_description) VALUES ('backup', 'This configuration group handles the backup/restore related settings.');
INSERT INTO cc_config_group (group_title, group_description) VALUES ('webui', 'This configuration group handles the WEBUI and API Configuration.');
INSERT INTO cc_config_group (group_title, group_description) VALUES ('peer_friend', 'This configuration group handles the WEBUI and API Configuration.');
INSERT INTO cc_config_group (group_title, group_description) VALUES ('log-files', 'This configuration group handles the Log Files Directory Paths.');
INSERT INTO cc_config_group (group_title, group_description) VALUES ('agi-conf1', 'This configuration group handles the AGI Configuration.');


CREATE TABLE cc_config (
  	id 								BIGSERIAL NOT NULL,
	config_title		 			TEXT NOT NULL,
	config_key 					TEXT NOT NULL,
	config_value 					TEXT NOT NULL,
	config_description 				TEXT NOT NULL,
	config_valuetype				INTEGER NOT NULL DEFAULT 0,	
	config_group_id 				INTEGER NOT NULL,
	config_listvalues				TEXT
);
ALTER TABLE ONLY cc_config
ADD CONSTRAINT cc_config_pkey PRIMARY KEY (id);

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Card Number length', 'interval_len_cardnumber', '11-15', 'Card Number length, You can define a Range e.g:10-15.', 0, 1, '10-15,11-15,12-15');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Card Alias Length', 'len_aliasnumber', '15', 'Card Number Alias Length e.g: 15.', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Voucher Lenght', 'len_voucher', '15', 'Voucher Number Length.', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Base Currency', 'base_currency', 'usd', 'Base Currency to use for application.', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Invoice Image', 'invoice_image', '10', 'Image to Display on the Top of Invoice', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Admin Email', 'admin_email', '10', 'Web Administrator Email Address.', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('DID Bill Payment Day', 'didbilling_daytopay', '5', 'DID Bill Payment Day of Month', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Manager Host', 'manager_host', 'localhost', 'Manager Host Address', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Manager User ID', 'manager_username', 'myastersik', 'Manger Host User Name', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Manager Password', 'manager_secret', 'mycode', 'Manager Host Password', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Go To Customer', 'customer_ui_url', '../../A2BCustomer_UI/index.php', 'Link to the customer account', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Context Callback', 'context_callback', 'a2billing-callback', 'Contaxt to use in Callback', 0, 2, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Extension', 'extension', '1000', 'Extension to call while callback.', 0, 2, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Avoid Repeat Duration', 'sec_avoid_repeate', '10', 'Number of seconds before the call-back can be re-initiated from the web page to prevent repeated and unwanted calls.', 0, 2, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Time out', 'timeout', '20', 'if the callback doesnt succeed within the value below, then the call is deemed to have failed.', 0, 2, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Answer on Call', 'answer_call', '0', 'if we want to manage the answer on the call. Disabling this for callback trigger numbers makes it ring not hang up.', 1, 2, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('No of Predictive Calls', 'nb_predictive_call', '10', 'number of calls an agent will do when the call button is clicked.', 0, 2, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Delay for Availability', 'nb_day_wait_before_retry', '1', 'Number of days to wait before the number becomes available to call again.', 0, 2, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('PD Contect', 'context_preditctivedialer', 'a2billing-predictivedialer', 'The context to redirect the call for the predictive dialer.', 0, 2, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Max Time to call', 'predictivedialer_maxtime_tocall', '10', 'When a call is made we need to limit the call duration : amount in seconds.', 0, 2, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('PD Caller ID', 'callerid', '10', 'Set the callerID for the predictive dialer and call-back.', 0, 2, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Callback CallPlan ID', 'all_callback_tariff', '1', 'ID Call Plan to use when you use the all-callback mode, check the ID in the "list Call Plan" - WebUI.', 1, 2, NULL);
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
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Predictive Dialer', 'predictivedialer', '10', 'Enable the predictivedialer option on the customer interface - yes or no.', 1, 3, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('WebPhone', 'webphone', 1, 'Let users use SIP/IAX Webphone (Options : yes/no).', 1, 3, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('WebPhone Server', 'webphoneserver', 'localhost', 'IP address or domain name of asterisk server that would be used by the web-phone.', 0, 3, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Caller ID', 'callerid', 1, 'Let the users add new callerid.', 1, 3, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Password', 'password', 1, 'Let the user change the webui password.', 1, 3, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('CallerID Limit', 'limit_callerid', '5', 'The total number of callerIDs for CLI Recognition that can be add by the customer.', 0, 3, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Error Email', 'error_email', 1, 'Email address to send the notification and error report - new DIDs assigned will also be emailed..', 0, 3, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Trunk Name', 'sip_iax_info_trunkname', 'call-labs', 'Trunk Name to show in sip/iax info.', 0, 4, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Codecs Allowed', 'sip_iax_info_allowcodec', 'g729', 'Allowed Codec, ulaw, gsm, g729.', 0, 4, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Host', 'sip_iax_info_host', 'call-labs.com', 'Host information.', 0, 4, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('IAX Parms', 'iax_additional_parameters', 'canreinvite = no', 'IAX Additional Parameters.', 0, 4, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('SIP Parms', 'sip_additional_parameters', 'trustrpid = yes | sendrpid = yes | canreinvite = no', 'SIP Additional Parameters.', 0, 4, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Enable', 'enable', 1, 'Enable/Disable.', 1, 5, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('HTTP Server', 'http_server', 'http://www.call-labs.com', 'Set the Server Address of Website, It should be empty for productive Servers.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('HTTPS Server', 'https_server', 'https://www.call-labs.com', 'https://localhost - Enter here your Secure Server Address, should not be empty for productive servers.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Server IP/Domain', 'http_cookie_domain', '26.63.165.200', 'Enter your Domain Name or IP Address, eg, 26.63.165.200.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Secure Server IP/Domain', 'https_cookie_domain', '26.63.165.200', 'Enter your Secure server Domain Name or IP Address, eg, 26.63.165.200.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Application Path', 'http_cookie_path', '/A2BCustomer_UI/', 'Enter the Physical path of your Application on your server.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Secure Application Path', 'https_cookie_path', '/A2BCustomer_UI/', 'Enter the Physical path of your Application on your Secure Server.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Application Physical Path', 'dir_ws_http_catalog', '/A2BCustomer_UI/', 'Enter the Physical path of your Application on your server.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Secure Application Physical Path', 'dir_ws_https_catalog', '/A2BCustomer_UI/', 'Set the callerID for .', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Enable SSL', 'enable_ssl', 1, 'secure webserver for checkout procedure?', 1, 5, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('HTTP Domain', 'http_domain', '26.63.165.200', 'Http Address.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Directory Path', 'dir_ws_http', '/~areski/svn/a2billing/payment/A2BCustomer_UI/', 'Directory Path.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Payment Amount', 'purchase_amount', '1:2:5:10:20', 'define the different amount of purchase that would be available - 5 amount maximum (5:10:15).', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Item Name', 'item_name', 'Credit Purchase', 'Item name that would be display to the user when he will buy credit.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Currency Code', 'currency_code', 'USD', 'Currency for the Credit purchase, only one can be define here.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Paypal Payment URL', 'paypal_payment_url', 'https://www.sandbox.paypal.com/cgi-bin/webscr', 'Define here the URL of paypal gateway the payment (to test with paypal sandbox).', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Paypal Verify URL', 'paypal_verify_url', 'www.sandbox.paypal.com', 'paypal transaction verification url.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Authorize.NET Payment URL', 'authorize_payment_url', 'https://test.authorize.net/gateway/transact.dll', 'Define here the URL of Authorize gateway.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('PayPal Store Name', 'store_name', 'Asterisk2Billing', 'paypal store name to show in the paypal site when customer will go to pay.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Transaction Key', 'callerid', 'asdf1212fasd121554sd4f5s45sdf', 'Transaction Key for security of Epayment Max length of 60 Characters.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Secret Word', 'moneybookers_secretword', 'areski', 'Moneybookers secret word.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Enable', 'enable_signup', 1, 'Enable Signup Module.', 1, 6, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Captcha Security', 'enable_captcha', 1, 'enable Captcha on the signup module (value : YES or NO).', 1, 6, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Credit', 'credit', '0', 'amount of credit applied to a new user.', 0, 6, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('CallPlan ID List', 'callplan_id_list', '1,2', 'the list of id of call plans which will be shown in signup.', 0, 6, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Card Activation', 'activated', '0', 'Specify whether the card is created as active or pending.', 1, 6, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Access Type', 'simultaccess', '0', 'Simultaneous or non concurrent access with the card - 0 = INDIVIDUAL ACCESS or 1 = SIMULTANEOUS ACCESS.', 0, 6, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Paid Type', 'typepaid', '0', 'PREPAID CARD  =  0 - POSTPAY CARD  =  1.', 0, 6, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Credit Limit', 'creditlimit', '0', 'Define credit limit, which is only used for a POSTPAY card.', 0, 6, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Run Service', 'runservice', '0', 'Authorise the recurring service to apply on this card  -  Yes 1 - No 0.', 0, 6, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Enable Expire', 'enableexpire', '0', 'Enable the expiry of the card  -  Yes 1 - No 0.', 0, 6, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Date Format', 'expirationdate', '', 'Expiry Date format YYYY-MM-DD HH:MM:SS. For instance 2004-12-31 00:00:00', 0, 6, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Expire Limit', 'expiredays', '0', 'The number of days after which the card will expire.', 0, 6, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Create SIP', 'sip_account', 1, 'Create a sip account from signup ( default : yes ).', 1, 6, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Create IAX', 'iax_account', 1, 'Create an iax account from signup ( default : yes ).', 1, 6, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Activate Card', 'activatedbyuser', 1, 'active card after the new signup. if No, the Signup confirmation is needed and an email will be sent to the user with a link for activation (need to put the link into the Signup mail template).', 1, 6, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Customer Interface URL', 'urlcustomerinterface', 'http://localhost/A2BCustomer_UI/', 'url of the customer interface to display after activation.', 0, 6, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Asterisk Reload', 'reload_asterisk_if_sipiax_crea', '0', 'Define if you want to reload Asterisk when a SIP / IAX Friend is created at signup time.', 1, 6, 'yes,no');
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
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Card Export Fields', 'card_export_field_list', 'creationdate, username, credit, lastname, firstname', 'Fields to export in csv format from cc_card table.', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Vouvher Export Fields', 'voucher_export_field_list', 'voucher, credit, tag, activated, usedcardnumber, usedate, currency', 'Field to export in csv format from cc_voucher table.', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Advance Mode', 'advanced_mode', '0', 'Advanced mode - Display additional configuration options on the ratecard (progressive rates, musiconhold, ...).', 1, 8, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('SIP/IAX Delete', 'delete_fk_card', 1, 'Delete the SIP/IAX Friend & callerid when a card is deleted.', 1, 8, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Type', 'type', 'friend', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, 9, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Allow', 'allow', 'ulaw, alaw, gsm, g729', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, 9, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Context', 'context', 'a2billing', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, 9, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('PD Caller ID', 'nat', 1, 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, 9, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('AMA Flag', 'amaflag', 'billing', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, 9, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Qualify', 'qualify', 1, 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, 9, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Host', 'host', 'dynamic', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, 9, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('DTMF Mode', 'dtmfmode', 'RFC2833', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, 9, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Alarm Log File', 'cront_alarm', '/tmp/cront_a2b_alarm.log', 'To disable application logging, remove/comment the log file name aside service.', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Auto refill Log File', 'cront_autorefill', '/tmp/cront_a2b_autorefill.log', 'To disable application logging, remove/comment the log file name aside service.', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Bactch Process Log File', 'cront_batch_process', '/tmp/cront_a2b_batch_process.log', 'To disable application logging, remove/comment the log file name aside service .', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('DID Billing Log File', 'cront_bill_diduse', '/tmp/cront_a2b_bill_diduse.log', 'To disable application logging, remove/comment the log file name aside service .', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Subscription Fee Log File', 'cront_subscriptionfee', '/tmp/cront_a2b_subscription_fee.log', 'To disable application logging, remove/comment the log file name aside service.', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Currency Cront Log File', 'cront_currency_update', '/tmp/cront_a2b_currency_update.log', 'To disable application logging, remove/comment the log file name aside service.', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Invoice Cront Log File', 'cront_invoice', '/tmp/cront_a2b_invoice.log', 'To disable application logging, remove/comment the log file name aside service.', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Cornt Log File', 'cront_check_account', '/tmp/cront_a2b_check_account.log', 'To disable application logging, remove/comment the log file name aside service .', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Paypal Log File', 'paypal', '/tmp/a2billing_paypal.log', 'paypal log file, to log all the transaction & error.', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('EPayment Log File', 'epayment', '/tmp/a2billing_epayment.log', 'epayment log file, to log all the transaction & error .', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('ECommerce Log File', 'api_ecommerce', '/tmp/a2billing_api_ecommerce_request.log', 'Log file to store the ecommerce API requests .', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Callback Log File', 'api_callback', '/tmp/a2billing_api_callback_request.log', 'Log file to store the CallBack API requests.', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Webservice Card Log File', 'api_card', '/tmp/a2billing_api_card.log', 'Log file to store the Card Webservice Logs', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('AGI Log File', 'agi', '/tmp/a2billing_agi.log', 'File to log.', 0, 10, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Debug', 'debug', '1', 'The debug level 0=none, 1=low, 2=normal, 3=all.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Asterisk Version', 'asterisk_version', '1_2', 'Asterisk Version Information, 1_1,1_2,1_4 By Default it will take 1_2 or higher .', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Answer Call', 'answer_call', 1, 'Manage the answer on the call. Disabling this for callback trigger numbers makes it ring not hang up.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Play Audio', 'play_audio', 1, 'Play audio - this will disable all stream file but not the Get Data , for wholesale ensure that the authentication works and than number_try = 1.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Say GoodBye', 'say_goodbye', '0', 'play the goodbye message when the user has finished.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Play Language Menu', 'play_menulanguage', '0', 'enable the menu to choose the language, press 1 for English, pulsa 2 para el español, Pressez 3 pour Français', 0, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Force Language', 'force_language', '', 'force the use of a language, if you dont want to use it leave the option empty, Values : ES, EN, FR, etc... (according to the audio you have installed).', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Intro Prompt', 'intro_prompt', '', 'Introduction prompt : to specify an additional prompt to play at the beginning of the application .', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Min Call Credit', 'min_credit_2call', '0', 'Minimum amount of credit to use the application .', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Min Bill Duration', 'min_duration_2bill', '0', 'this is the minimum duration in seconds of a call in order to be billed any call with a length less than min_duration_2bill will have a 0 cost useful not to charge callers for system errors when a call was answered but it actually didn''t connect.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Not Enough Credit', 'notenoughcredit_cardnumber', 1, 'if user doesn''t have enough credit to call a destination, prompt him to enter another cardnumber .', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('New Caller ID', 'notenoughcredit_assign_newcard', 1, 'if notenoughcredit_cardnumber = YES  then	assign the CallerID to the new cardnumber.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Use DNID', 'use_dnid', '0', 'if YES it will use the DNID and try to dial out, without asking for the phonenumber to call.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Not Use DNID', 'no_auth_dnid', '2400,2300', 'list the dnid on which you want to avoid the use of the previous option "use_dnid" .', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Try Count', 'number_try', '3', 'number of times the user can dial different number.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Force CallPlan', 'force_callplan_id', '', 'this will force to select a specific call plan by the Rate Engine.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Say Balance After Auth', 'say_balance_after_auth', 1, 'Play the balance to the user after the authentication (values : yes - no).', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Say Balance After Call', 'say_balance_after_call', '0', 'Play the balance to the user after the call (values : yes - no).', 0, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Say Rate', 'say_rateinitial', '0', 'Play the initial cost of the route (values : yes - no)', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Say Duration', 'say_timetocall', 1, 'Play the amount of time that the user can call (values : yes - no).', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Auto Set CLID', 'auto_setcallerid', 1, 'enable the setup of the callerID number before the outbound is made, by default the user callerID value will be use.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Force CLID', 'force_callerid', '', 'If auto_setcallerid is enabled, the value of force_callerid will be set as CallerID.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('CLID Sanitize', 'cid_sanitize', '0', 'If force_callerid is not set, then the following option ensures that CID is set to one of the card''s configured caller IDs or blank if none available.(NO - disable this feature, caller ID can be anything, CID - Caller ID must be one of the customers caller IDs, DID - Caller ID must be one of the customers DID nos, BOTH - Caller ID must be one of the above two items)', 0, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('CLID Enable', 'cid_enable', '0', 'enable the callerid authentication if this option is active the CC system will check the CID of caller  .', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Ask PIN', 'cid_askpincode_ifnot_callerid', 1, 'if the CID does not exist, then the caller will be prompt to enter his cardnumber .', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Auto CLID', 'cid_auto_assign_card_to_cid', 1, 'if the callerID authentication is enable and the authentication fails then the user will be prompt to enter his cardnumber;this option will bound the cardnumber entered to the current callerID so that next call will be directly authenticate.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Auto Create Card', 'cid_auto_create_card', '0', 'if the callerID is captured on a2billing, this option will create automatically a new card and add the callerID to it.', 0, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Auto Create Card Length', 'cid_auto_create_card_len', '10', 'set the length of the card that will be auto create (ie, 10).', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Auto Create Card Type', 'cid_auto_create_card_typepaid', 'POSTPAY', 'billing type of the new card( value : POSTPAY or PREPAY) .', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Auto Create Card Credit', 'cid_auto_create_card_credit', '0', 'amount of credit of the new card.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Auto Create Card Limit', 'cid_auto_create_card_credit_limit', '1000', 'if postpay, define the credit limit for the card.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Auto Create Card TariffGroup', 'cid_auto_create_card_tariffgroup', '6', 'the tariffgroup to use for the new card (this is the ID that you can find on the admin web interface) .', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Auto CLID Security', 'callerid_authentication_over_c', '0', 'to check callerID over the cardnumber authentication (to guard against spoofing).', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('SIP Call', 'sip_iax_friends', '0', 'enable the option to call sip/iax friend for free (values : YES - NO).', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('SIP Call Prefix', 'sip_iax_pstn_direct_call_prefix', '555', 'if SIP_IAX_FRIENDS is active, you can define a prefix for the dialed digits to call a pstn number .', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Direct Call', 'sip_iax_pstn_direct_call', '0', 'this will enable a prompt to enter your destination number. if number start by sip_iax_pstn_direct_call_prefix we do directly a sip iax call, if not we do a normal call.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('IVR Voucher Refill', 'ivr_voucher', '0', 'enable the option to refill card with voucher in IVR (values : YES - NO) .', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('IVR Voucher Prefix', 'ivr_voucher_prefix', '8', 'if ivr_voucher is active, you can define a prefix for the voucher number to refill your card, values : number - don''t forget to change prepaid-refill_card_with_voucher audio accordingly .', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('IVR Low Credit', 'jump_voucher_if_min_credit', '0', 'When the user credit are below the minimum credit to call min_credit jump directly to the voucher IVR menu  (values: YES - NO) .', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Dail Command Parms', 'dialcommand_param', '|60|HRrL(%timeout%:61000:30000)', 'More information about the Dial : http://voip-info.org/wiki-Asterisk+cmd+dial<br>30 :  The timeout parameter is optional. If not specifed, the Dial command will wait indefinitely, exiting only when the originating channel hangs up, or all the dialed channels return a busy or error condition. Otherwise it specifies a maximum time, in seconds, that the Dial command is to wait for a channel to answer.<br>H: Allow the caller to hang up by dialing * <br>r: Generate a ringing tone for the calling party<br>R: Indicate ringing to the calling party when the called party indicates ringing, pass no audio until answered.<br>m: Provide Music on Hold to the calling party until the called channel answers.<br>L(x[:y][:z]): Limit the call to ''x'' ms, warning when ''y'' ms are left, repeated every ''z'' ms)<br>%timeout% tag is replaced by the calculated timeout according the credit & destination rate!.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('SIP/IAX Dial Command Parms', 'dialcommand_param_sipiax_friend', '|60|HL(3600000:61000:30000)', 'by default (3600000  =  1HOUR MAX CALL).', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Outbound Call', 'switchdialcommand', '0', 'Define the order to make the outbound call<br>YES -> SIP/dialedphonenumber@gateway_ip - NO  SIP/gateway_ip/dialedphonenumber<br>Both should work exactly the same but i experimented one case when gateway was supporting dialedphonenumber@gateway_ip, So in case of trouble, try it out.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Failover Retry Limit', 'failover_recursive_limit', '2', 'failover recursive search - define how many time we want to authorize the research of the failover trunk when a call fails (value : 0 - 20) .', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Max Time', 'maxtime_tocall_negatif_free_route', '5400', 'For free calls, limit the duration: amount in seconds  .', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Send Reminder', 'send_reminder', '0', 'Send a reminder email to the user when they are under min_credit_2call  .', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Record Call', 'record_call', '0', 'enable to monitor the call (to record all the conversations) value : YES - NO .', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Monitor File Format', 'monitor_formatfile', 'gsm', 'format of the recorded monitor file.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('AGI Force Currency', 'agi_force_currency', '', 'Force to play the balance to the caller in a predefined currency, to use the currency set for by the customer leave this field empty.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Currency Associated', 'currency_association', 'usd:dollars,mxn:pesos,eur:euros,all:credit', 'Define all the audio (without file extensions) that you want to play according to currency (use , to separate, ie "usd:prepaid-dollar,mxn:pesos,eur:Euro,all:credit").', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('File Enter Destination', 'file_conf_enter_destination', 'prepaid-enter-dest', 'Please enter the file name you want to play when we prompt the calling party to enter the destination number, file_conf_enter_destination = prepaid-enter-number-u-calling-1-or-011.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('File Language Menu', 'file_conf_enter_menulang', 'prepaid-menulang2', 'Please enter the file name you want to play when we prompt the calling party to choose the prefered language .', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Bill Callback', 'callback_bill_1stleg_ifcall_no', 1, 'Define if you want to bill the 1st leg on callback even if the call is not connected to the destination.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('International prefixes', 'international_prefixes', '011,00,09,1', 'List the prefixes you want stripped off if the call plan requires it', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Server GMT', 'server_GMT', 'GMT+10:00', 'Define the sever gmt time', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Invoice Template Path', 'invoice_template_path', '../invoice/', 'gives invoice template path from default one', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Outstanding Template Path', 'outstanding_template_path', '../outstanding/', 'gives outstanding template path from default one', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Sales Template Path', 'sales_template_path', '../sales/', 'gives sales template path from default one', 0, 1, NULL);


CREATE TABLE cc_timezone (
    id 								BIGSERIAL NOT NULL,
    gmtzone							CHARACTER VARYING(255),
    gmttime		 					CHARACTER VARYING(255),
	gmtoffset						INTEGER NOT NULL DEFAULT 0 
);
ALTER TABLE ONLY cc_timezone
ADD CONSTRAINT cc_timezone_pkey PRIMARY KEY (id);

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
    charset character(16) NOT NULL DEFAULT 'ISO-8859-1'); 
ALTER TABLE ONLY cc_iso639
    ADD CONSTRAINT cc_iso639_pkey PRIMARY KEY (code);
ALTER TABLE ONLY cc_iso639
    ADD CONSTRAINT iso639_name_key UNIQUE (name);


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


ALTER TABLE ONLY cc_templatemail DROP CONSTRAINT cons_cc_templatemail_mailtype;

ALTER TABLE cc_templatemail ADD COLUMN id BIGSERIAL NOT NULL;

ALTER TABLE ONLY cc_templatemail
    ADD CONSTRAINT cc_templatemail_pkey PRIMARY KEY (id);

ALTER TABLE cc_templatemail ADD COLUMN id_language CHAR( 20 ) DEFAULT 'en';

ALTER TABLE ONLY cc_templatemail DROP CONSTRAINT cc_templatemail_pkey;

ALTER TABLE ONLY cc_templatemail
    ADD CONSTRAINT cons_cc_templatemail_mailtype UNIQUE (id,id_language);

    
    
ALTER TABLE ONLY cc_card ADD COLUMN status INT NOT NULL DEFAULT '2';
update cc_card set status = 1 where activated = 't';
update cc_card set status = 0 where activated = 'f';


CREATE TABLE cc_status_log (
  id		BIGSERIAL DEFAULT 0 NOT NULL,
  status 	INT NOT NULL,
  id_cc_card INT NOT NULL,
  updated_date TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW()
);
ALTER TABLE ONLY cc_status_log
    ADD CONSTRAINT cc_status_log_pkey PRIMARY KEY (id);


ALTER TABLE cc_card ADD COLUMN tag CHAR(50);
ALTER TABLE cc_ratecard ADD COLUMN rounding_calltime INT NOT NULL DEFAULT 0;
ALTER TABLE cc_ratecard ADD COLUMN rounding_threshold INT NOT NULL DEFAULT 0;
ALTER TABLE cc_ratecard ADD COLUMN additional_block_charge NUMERIC(15,5) NOT NULL DEFAULT 0;
ALTER TABLE cc_ratecard ADD COLUMN additional_block_charge_time INT NOT NULL DEFAULT 0;
ALTER TABLE cc_ratecad ADD COLUMN tag CHAR(50);

ALTER TABLE cc_card ADD COLUMN template_invoice TEXT;
ALTER TABLE cc_card ADD COLUMN template_outstanding TEXT;


CREATE TABLE cc_card_history (
	id 								BIGSERIAL NOT NULL,
	id_cc_card 						BIGINT DEFAULT 0 NOT NULL,
    datecreated						TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
	description 					TEXT
);
ALTER TABLE ONLY cc_card_history    ADD CONSTRAINT cc_card_history_pkey PRIMARY KEY (id);
