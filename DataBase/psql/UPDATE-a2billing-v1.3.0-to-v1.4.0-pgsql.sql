

ALTER TABLE cc_card ALTER COLUMN id_timezone INTEGER DEFAULT 0;

CREATE TABLE cc_config (
  	id 								BIGSERIAL NOT NULL,
	config_title		 			CHARACTER VARYING(64),
	config_key 						CHARACTER VARYING(64),
	config_value 					TEXT NOT NULL,
	config_description 				TEXT NOT NULL,
	config_valuetype				INTEGER NOT NULL DEFAULT 0,	
	config_group_id 				INTEGER NOT NULL,
	config_listvalues				TEXT
);
ALTER TABLE ONLY cc_config
ADD CONSTRAINT cc_config_pkey PRIMARY KEY (id);

CREATE TABLE cc_config_group (
  	id 								BIGSERIAL NOT NULL,
	group_title 					CHARACTER VARYING(64) NOT NULL,	
	group_description 				CHARACTER VARYING(255) NOT NULL
);
ALTER TABLE ONLY cc_config_group
ADD CONSTRAINT cc_config_group_pkey PRIMARY KEY (id);


INSERT INTO cc_config_group ( group_title, group_description) VALUES ( 'global', 'This configuration group handles the global settings for application.');
INSERT INTO cc_config_group ( group_title, group_description) VALUES ( 'callback', 'This configuration group handles calllback settings.');
INSERT INTO cc_config_group ( group_title, group_description) VALUES ( 'webcustomerui', 'This configuration group handles Web Customer User Interface.');
INSERT INTO cc_config_group ( group_title, group_description) VALUES ( 'sip-iax-info', 'SIP & IAX client configuration information.');
INSERT INTO cc_config_group ( group_title, group_description) VALUES ( 'epayment_method', 'Epayment Methods Configuration.');
INSERT INTO cc_config_group ( group_title, group_description) VALUES ( 'signup', 'This configuration group handles the signup related settings.');
INSERT INTO cc_config_group ( group_title, group_description) VALUES ( 'backup', 'This configuration group handles the backup/restore related settings.');
INSERT INTO cc_config_group ( group_title, group_description) VALUES ( 'webui', 'This configuration group handles the WEBUI and API Configuration.');
INSERT INTO cc_config_group ( group_title, group_description) VALUES ( 'peer_friend', 'This configuration group handles the WEBUI and API Configuration.');
INSERT INTO cc_config_group ( group_title, group_description) VALUES ( 'log-files', 'This configuration group handles the Log Files Directory Paths.');
INSERT INTO cc_config_group ( group_title, group_description) VALUES ( 'agi-conf1', 'This configuration group handles the AGI Configuration.');



INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Card Number length', 'interval_len_cardnumber', '10-15', 'Card Number length, You can define a Range e.g:10-15.',0 , 1);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Card Alias Length', 'len_aliasnumber', '15', 'Card Number Alias Length e.g: 15.',0 , 1);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Voucher Lenght', 'len_voucher', '15', 'Voucher Number Length.',0 , 1);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Base Currency', 'base_currency', 'usd', 'Base Currency to use for application.',0 , 1);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Invoice Image', 'invoice_image', '10', 'Image to Display on the Top of Invoice',0 , 1);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Admin Email', 'admin_email', '10', 'Web Administrator Email Address.',0 , 1);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('DID Bill Payment Day', 'didbilling_daytopay', '5', 'DID Bill Payment Day of Month',0 , 1);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Manager Host', 'manager_host', 'localhost', 'Manager Host Address',0 , 1);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Manager User ID', 'manager_username', 'myastersik', 'Manger Host User Name',0 , 1);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Manager Password', 'manager_secret', 'mycode', 'Manager Host Password',0 , 1);

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Context Callback', 'context_callback', '10', 'Contaxt to use in Callback',0 , 2);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Extension', 'extension', '1000', 'Extension to call while callback.',0 , 2);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Avoid Repeat Duration', 'sec_avoid_repeate', '10', 'Number of seconds before the call-back can be re-initiated from the web page to prevent repeated and unwanted calls.',0 , 2);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Time out', 'timeout', '20', 'if the callback doesnt succeed within the value below, then the call is deemed to have failed.',0 , 2);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Answer on Call', 'answer_call', 'yes', 'if we want to manage the answer on the call.',1 , 2);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('No of Predictive Calls', 'nb_predictive_call', '10', 'number of calls an agent will do when the call button is clicked.',0 , 2);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Delay for Availability', 'nb_day_wait_before_retry', '1', 'Number of days to wait before the number becomes available to call again.',0 , 2);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('PD Contect', 'context_preditctivedialer', 'a2billing-predictivedialer', 'The context to redirect the call for the predictive dialer.',0 , 2);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Max Time to call', 'predictivedialer_maxtime_tocall', '10', 'When a call is made we need to limit the call duration : amount in seconds.',0 , 2);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('PD Caller ID', 'callerid', '10', 'Set the callerID for the predictive dialer and call-back.',0 , 2);

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Callback CallPlan ID', 'all_callback_tariff', '1', 'ID Call Plan to use when you use the all-callback mode, check the ID in the "list Call Plan" - WebUI.',0 , 2);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Server Group ID', 'id_server_group', '1', 'Define the group of servers that are going to be used by the callback.',0 , 2);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Audio Intro', 'callback_audio_intro', 'prepaid-callback_intro', 'Audio intro message when the callback is initiate.',0 , 2);

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Signup URL', 'signup_page_url', '', 'url of the signup page to show up on the sign in page (if empty no link will show up).',0 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Payment Method', 'paymentmethod', 'yes', 'Enable or disable the payment methods; yes for multi-payment or no for single payment method option.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Personal Info', 'personalinfo', 'yes', 'Enable or disable the page which allow customer to modify its personal information.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Payment Info', 'customerinfo', 'yes', 'Enable display of the payment interface - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('SIP/IAX Info', 'sipiaxinfo', 'yes', 'Enable display of the sip/iax info - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('CDR', 'cdr', 'yes', 'Enable the Call history - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Invoices', 'invoice', 'yes', 'Enable invoices - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Voucher Screen', 'voucher', 'yes', 'Enable the voucher screen - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Paypal', 'paypal', 'yes', 'Enable the paypal payment buttons - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Speed Dial', 'speeddial', 'yes', 'Allow Speed Dial capabilities - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('DID', 'did', 'yes', 'Enable the DID (Direct Inwards Dialling) interface - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('RateCard', 'ratecard', 'yes', 'Show the ratecards - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Simulator', 'simulator', 'yes', 'Offer simulator option on the customer interface - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('CallBack', 'callback', 'yes', 'Enable the callback option on the customer interface - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Predictive Dialer', 'predictivedialer', '10', 'Enable the predictivedialer option on the customer interface - yes or no.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('WebPhone', 'webphone', 'yes', 'Let users use SIP/IAX Webphone (Options : yes/no).',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('WebPhone Server', 'webphoneserver', 'localhost', 'IP address or domain name of asterisk server that would be used by the web-phone.',0 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Caller ID', 'callerid', 'yes', 'Let the users add new callerid.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Password', 'password', 'yes', 'Let the user change the webui password.',1 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('CallerID Limit', 'limit_callerid', '5', 'The total number of callerIDs for CLI Recognition that can be add by the customer.',0 , 3);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Error Email', 'error_email', 'root@localhost', 'Email address to send the notification and error report - new DIDs assigned will also be emailed..',0 , 3);

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Trunk Name', 'sip_iax_info_trunkname', 'call-labs', 'Trunk Name to show in sip/iax info.',0 , 4);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Codecs Allowed', 'sip_iax_info_allowcodec', 'g729', 'Allowed Codec, ulaw, gsm, g729.',0 , 4);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Host', 'sip_iax_info_host', 'call-labs.com', 'Host information.',0 , 4);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('IAX Parms', 'iax_additional_parameters', 'canreinvite = no', 'IAX Additional Parameters.',0 , 4);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('SIP Parms', 'sip_additional_parameters', 'trustrpid = yes | sendrpid = yes | canreinvite = no', 'SIP Additional Parameters.',0 , 4);


INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Enable', 'enable', 'yes', 'Enable/Disable.',1 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('HTTP Server', 'http_server', 'http://www.call-labs.com', 'Set the Server Address of Website, It should be empty for productive Servers.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('HTTPS Server', 'https_server', 'https://www.call-labs.com', 'https://localhost - Enter here your Secure Server Address, should not be empty for productive servers.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Server IP/Domain', 'http_cookie_domain', '26.63.165.200', 'Enter your Domain Name or IP Address, eg, 26.63.165.200.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Secure Server IP/Domain', 'https_cookie_domain', '26.63.165.200', 'Enter your Secure server Domain Name or IP Address, eg, 26.63.165.200.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Application Path', 'http_cookie_path', '/A2BCustomer_UI/', 'Enter the Physical path of your Application on your server.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Secure Application Path', 'https_cookie_path', '/A2BCustomer_UI/', 'Enter the Physical path of your Application on your Secure Server.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Application Physical Path', 'dir_ws_http_catalog', '/A2BCustomer_UI/', 'Enter the Physical path of your Application on your server.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Secure Application Physical Path', 'dir_ws_https_catalog', '/A2BCustomer_UI/', 'Set the callerID for .',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Enable SSL', 'enable_ssl', 'yes', 'secure webserver for checkout procedure?',1 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('HTTP Domain', 'http_domain', '26.63.165.200', 'Http Address.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Directory Path', 'dir_ws_http', '/~areski/svn/a2billing/payment/A2BCustomer_UI/', 'Directory Path.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Payment Amount', 'purchase_amount', '1:2:5:10:20', 'define the different amount of purchase that would be available - 5 amount maximum (5:10:15).',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Item Name', 'item_name', 'Credit Purchase', 'Item name that would be display to the user when he will buy credit.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Currency Code', 'currency_code', 'USD', 'Currency for the Credit purchase, only one can be define here.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Paypal Payment URL', 'paypal_payment_url', 'https://www.sandbox.paypal.com/cgi-bin/webscr', 'Define here the URL of paypal gateway the payment (to test with paypal sandbox).',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Paypal Verify URL', 'paypal_verify_url', 'www.sandbox.paypal.com', 'paypal transaction verification url.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Authorize.NET Payment URL', 'authorize_payment_url', 'https://test.authorize.net/gateway/transact.dll', 'Define here the URL of Authorize gateway.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('PayPal Store Name', 'store_name', 'Asterisk2Billing', 'paypal store name to show in the paypal site when customer will go to pay.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Transaction Key', 'callerid', 'asdf1212fasd121554sd4f5s45sdf', 'Transaction Key for security of Epayment Max length of 60 Characters.',0 , 5);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Secret Word', 'moneybookers_secretword', 'areski', 'Moneybookers secret word.',0 , 5);

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Enable', 'enable_signup', 'yes', 'Enable Signup Module.',1 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Captcha Security', 'enable_captcha', 'yes', 'enable Captcha on the signup module (value : YES or NO).',1 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Credit', 'credit', '0', 'amount of credit applied to a new user.',0 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('CallPlan ID List', 'callplan_id_list', '1,2', 'the list of id of call plans which will be shown in signup.',0 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Card Activation', 'activated', 'no', 'Specify whether the card is created as active or pending.',1 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Access Type', 'simultaccess', '0', 'Simultaneous or non concurrent access with the card - 0 = INDIVIDUAL ACCESS or 1 = SIMULTANEOUS ACCESS.',0 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Paid Type', 'typepaid', '0', 'PREPAID CARD  =  0 - POSTPAY CARD  =  1.',0 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Credit Limit', 'creditlimit', '0', 'Define credit limit, which is only used for a POSTPAY card.',0 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Run Service', 'runservice', '0', 'Authorise the recurring service to apply on this card  -  Yes 1 - No 0.',0 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Enable Expire', 'enableexpire', '0', 'Enable the expiry of the card  -  Yes 1 - No 0.',0 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Date Format', 'expirationdate', '', 'Expiry Date format YYYY-MM-DD HH:MM:SS. For instance 2004-12-31 00:00:00',0 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Expire Limit', 'expiredays', '0', 'The number of days after which the card will expire.',0 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Create SIP', 'sip_account', 'yes', 'Create a sip account from signup ( default : yes ).',1 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Create IAX', 'iax_account', 'yes', 'Create an iax account from signup ( default : yes ).',1 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Activate Card', 'activatedbyuser', 'yes', 'active card after the new signup. if No, the Signup confirmation is needed and an email will be sent to the user with a link for activation (need to put the link into the Signup mail template).',1 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Customer Interface URL', 'urlcustomerinterface', 'http://localhost/A2BCustomer_UI/', 'url of the customer interface to display after activation.',0 , 6);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Asterisk Reload', 'reload_asterisk_if_sipiax_created', 'no', 'Define if you want to reload Asterisk when a SIP / IAX Friend is created at signup time.',1 , 6);

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Backup Path', 'backup_path', '/tmp', 'Path to store backup of database.',0 , 7);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('GZIP Path', 'gzip_exe', '/bin/gzip', 'Path for gzip.',0 , 7);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('GunZip Path', 'gunzip_exe', '/bin/gunzip', 'Path for gunzip .',0 , 7);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('MySql Dump Path', 'mysqldump', '/usr/bin/mysqldump', 'path for mysqldump.',0 , 7);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('PGSql Dump Path', 'pg_dump', '/usr/bin/pg_dump', 'path for pg_dump.',0 , 7);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('MySql Path', 'mysql', '/usr/bin/mysql', 'Path for MySql.',0 , 7);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('PSql Path', 'psql', '/usr/bin/psql', 'Path for PSql.',0 , 7);


INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('SIP File Path', 'buddy_sip_file', '/etc/asterisk/additional_a2billing_sip.conf', 'Path to store the asterisk configuration files SIP.',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('IAX File Path', 'buddy_iax_file', '/etc/asterisk/additional_a2billing_iax.conf', 'Path to store the asterisk configuration files IAX.',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('API Security Key', 'api_security_key', 'Ae87v56zzl34v', 'API have a security key to validate the http request, the key has to be sent after applying md5, Valid characters are [a-z,A-Z,0-9].',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Authorized IP', 'api_ip_auth', '127.0.0.1', 'API to restrict the IPs authorised to make a request, Define The the list of ips separated by '';''.',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Admin Email', 'email_admin', 'root@localhost', 'Administative Email.',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('MOH Directory', 'dir_store_mohmp3', '/var/lib/asterisk/mohmp3', 'MOH (Music on Hold) base directory.',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('MOH Classes', 'num_musiconhold_class', '10', 'Number of MOH classes you have created in musiconhold.conf : acc_1, acc_2... acc_10 class	etc....',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Display Help', 'show_help', 'yes', 'Display the help section inside the admin interface  (YES - NO).',1 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Max File Upload Size', 'my_max_file_size_import', '1024000', 'File Upload parameters, PLEASE CHECK ALSO THE VALUE IN YOUR PHP.INI THE LIMIT IS 2MG BY DEFAULT .',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Audio Directory Path', 'dir_store_audio', '/var/lib/asterisk/sounds/a2billing', 'Not used yet, The goal is to upload files and use them in the IVR.',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Max Audio File Size', 'my_max_file_size_audio', '3072000', 'upload maximum file size.',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Extensions Allowed', 'file_ext_allow', 'gsm, mp3, wav', 'File type extensions permitted to be uploaded such as "gsm, mp3, wav" (separated by ,).',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Muzic Files Allowed', 'file_ext_allow_musiconhold', 'mp3', 'File type extensions permitted to be uploaded for the musiconhold such as "gsm, mp3, wav" (separate by ,).',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Link Audio', 'link_audio_file', 'no', 'Enable link on the CDR viewer to the recordings. (YES - NO).',1 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Monitor Path', 'monitor_path', '/var/spool/asterisk/monitor', 'Path to link the recorded monitor files.',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Monitor Format', 'monitor_formatfile', 'gsm', 'FORMAT OF THE RECORDED MONITOR FILE.',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Invoice Icon', 'show_icon_invoice', 'yes', 'Display the icon in the invoice.',1 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Show Top Frame', 'show_top_frame', 'no', 'Display the top frame (useful if you want to save space on your little tiny screen ) .',1 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Currency', 'currency_choose', 'usd, eur, cad, hkd', 'Allow the customer to chose the most appropriate currency ("all" can be used).',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Card Export Fields', 'card_export_field_list', 'creationdate, username, credit, lastname, firstname', 'Fields to export in csv format from cc_card table.',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Vouvher Export Fields', 'voucher_export_field_list', 'id, voucher, credit, tag, activated, usedcardnumber, usedate, currency', 'Field to export in csv format from cc_voucher table.',0 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Advance Mode', 'advanced_mode', 'no', 'Advanced mode - Display additional configuration options on the ratecard (progressive rates, musiconhold, ...).',1 , 8);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('SIP/IAX Delete', 'delete_fk_card', 'yes', 'Delete the SIP/IAX Friend & callerid when a card is deleted.',1 , 8);

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Type', 'type', 'friend', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0 , 9);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Allow', 'allow', 'ulaw, alaw, gsm, g729', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0 , 9);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Context', 'context', 'a2billing', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0 , 9);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('PD Caller ID', 'nat', 'yes', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0 , 9);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('AMA Flag', 'amaflag', 'billing', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0 , 9);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Qualify', 'qualify', 'yes', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0 , 9);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Host', 'host', 'dynamic', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0 , 9);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('DTMF Mode', 'dtmfmode', 'RFC2833', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0 , 9);

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Alarm Log File', 'cront_alarm', '/tmp/cront_a2b_alarm.log', 'To disable application logging, remove/comment the log file name aside service.',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Auto refill Log File', 'cront_autorefill', '/tmp/cront_a2b_autorefill.log', 'To disable application logging, remove/comment the log file name aside service.',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Bactch Process Log File', 'cront_batch_process', '/tmp/cront_a2b_batch_process.log', 'To disable application logging, remove/comment the log file name aside service .',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('DID Billing Log File', 'cront_bill_diduse', '/tmp/cront_a2b_bill_diduse.log', 'To disable application logging, remove/comment the log file name aside service .',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Subscription Fee Log File', 'cront_subscriptionfee', '/tmp/cront_a2b_subscription_fee.log', 'To disable application logging, remove/comment the log file name aside service.',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Currency Cront Log File', 'cront_currency_update', '/tmp/cront_a2b_currency_update.log', 'To disable application logging, remove/comment the log file name aside service.',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Invoice Cront Log File', 'cront_invoice', '/tmp/cront_a2b_invoice.log', 'To disable application logging, remove/comment the log file name aside service.',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Cornt Log File', 'cront_check_account', '/tmp/cront_a2b_check_account.log', 'To disable application logging, remove/comment the log file name aside service .',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Paypal Log File', 'paypal', '/tmp/a2billing_paypal.log', 'paypal log file, to log all the transaction & error.',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('EPayment Log File', 'epayment', '/tmp/a2billing_epayment.log', 'epayment log file, to log all the transaction & error .',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('ECommerce Log File', 'api_ecommerce', '/tmp/api_ecommerce_request.log', 'Log file to store the ecommerce API requests .',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Callback Log File', 'api_callback', '/tmp/api_callback_request.log', 'Log file to store the CallBack API requests.',0 , 10);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('AGI Log File', 'agi', '/tmp/a2billing_agi.log', 'File to log.',0 , 10);


INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Debug', 'debug', '1', 'The debug level 0=none, 1=low, 2=normal, 3=all.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Asterisk Version', 'asterisk_version', '1_2', 'Asterisk Version Information, 1_1,1_2,1_4 By Default it will take 1_2 or higher .',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Answer Call', 'answer_call', 'yes', 'Manage the answer on the call.',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Play Audio', 'play_audio', 'yes', 'Play audio - this will disable all stream file but not the Get Data , for wholesale ensure that the authentication works and than number_try = 1.',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Say GoodBye', 'say_goodbye', 'no', 'play the goodbye message when the user has finished.',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Play Language Menu', 'play_menulanguage', 'no', 'enable the menu to choose the language, press 1 for English, pulsa 2 para el español, Pressez 3 pour Français',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Force Language', 'force_language', '', 'force the use of a language, if you dont want to use it leave the option empty, Values : ES, EN, FR, etc... (according to the audio you have installed).',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Intro Prompt', 'intro_prompt', '', 'Introduction prompt : to specify an additional prompt to play at the beginning of the application .',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Min Call Credit', 'min_credit_2call', '0', 'Minimum amount of credit to use the application .',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Min Bill Duration', 'min_duration_2bill', '0', 'this is the minimum duration in seconds of a call in order to be billed any call with a length less than min_duration_2bill will have a 0 cost useful not to charge callers for system errors when a call was answered but it actually didn''t connect.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Not Enough Credit', 'notenoughcredit_cardnumber', 'yes', 'if user doesn''t have enough credit to call a destination, prompt him to enter another cardnumber .',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('New Caller ID', 'notenoughcredit_assign_newcardnumber_cid', 'yes', 'if notenoughcredit_cardnumber = YES  then	assign the CallerID to the new cardnumber.',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Use DNID', 'use_dnid', 'no', 'if YES it will use the DNID and try to dial out, without asking for the phonenumber to call.',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Not Use DNID', 'no_auth_dnid', '2400,2300', 'list the dnid on which you want to avoid the use of the previous option "use_dnid" .',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Try Count', 'number_try', '3', 'number of times the user can dial different number.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Force CallPlan', 'force_callplan_id', '', 'this will force to select a specific call plan by the Rate Engine.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Say Balance After Auth', 'say_balance_after_auth', 'yes', 'Play the balance to the user after the authentication (values : yes - no).',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Say Balance After Call', 'say_balance_after_call', 'no', 'Play the balance to the user after the call (values : yes - no).',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Say Rate', 'say_rateinitial', 'no', 'Play the initial cost of the route (values : yes - no)',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Say Duration', 'say_timetocall', 'yes', 'Play the amount of time that the user can call (values : yes - no).',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Auto Set CLID', 'auto_setcallerid', 'yes', 'enable the setup of the callerID number before the outbound is made, by default the user callerID value will be use.',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Force CLID', 'force_callerid', '', 'If auto_setcallerid is enabled, the value of force_callerid will be set as CallerID.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('CLID Sanitize', 'cid_sanitize', 'NO', 'If force_callerid is not set, then the following option ensures that CID is set to one of the card''s configured caller IDs or blank if none available.(NO - disable this feature, caller ID can be anything, CID - Caller ID must be one of the customers caller IDs, DID - Caller ID must be one of the customers DID nos, BOTH - Caller ID must be one of the above two items)',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('CLID Enable', 'cid_enable', 'NO', 'enable the callerid authentication if this option is active the CC system will check the CID of caller  .',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Ask PIN', 'cid_askpincode_ifnot_callerid', 'yes', 'if the CID does not exist, then the caller will be prompt to enter his cardnumber .',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Auto CLID', 'cid_auto_assign_card_to_cid', 'yes', 'if the callerID authentication is enable and the authentication fails then the user will be prompt to enter his cardnumber;this option will bound the cardnumber entered to the current callerID so that next call will be directly authenticate.',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Auto Create Card', 'cid_auto_create_card', 'no', 'if the callerID is captured on a2billing, this option will create automatically a new card and add the callerID to it.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Auto Create Card Length', 'cid_auto_create_card_len', '10', 'set the length of the card that will be auto create (ie, 10).',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Auto Create Card Type', 'cid_auto_create_card_typepaid', 'POSTPAY', 'billing type of the new card( value : POSTPAY or PREPAY) .',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Auto Create Card Credit', 'cid_auto_create_card_credit', '0', 'amount of credit of the new card.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Auto Create Card Limit', 'cid_auto_create_card_credit_limit', '1000', 'if postpay, define the credit limit for the card.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Auto Create Card TariffGroup', 'cid_auto_create_card_tariffgroup', '6', 'the tariffgroup to use for the new card (this is the ID that you can find on the admin web interface) .',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Auto CLID Security', 'callerid_authentication_over_cardnumber', 'NO', 'to check callerID over the cardnumber authentication (to guard against spoofing).',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('SIP Call', 'sip_iax_friends', 'NO', 'enable the option to call sip/iax friend for free (values : YES - NO).',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('SIP Call Prefix', 'sip_iax_pstn_direct_call_prefix', '555', 'if SIP_IAX_FRIENDS is active, you can define a prefix for the dialed digits to call a pstn number .',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Direct Call', 'sip_iax_pstn_direct_call', 'no', 'this will enable a prompt to enter your destination number. if number start by sip_iax_pstn_direct_call_prefix we do directly a sip iax call, if not we do a normal call.',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('IVR Voucher Refill', 'ivr_voucher', 'NO', 'enable the option to refill card with voucher in IVR (values : YES - NO) .',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('IVR Voucher Prefix', 'ivr_voucher_prefix', '8', 'if ivr_voucher is active, you can define a prefix for the voucher number to refill your card, values : number - don''t forget to change prepaid-refill_card_with_voucher audio accordingly .',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('IVR Low Credit', 'jump_voucher_if_min_credit', 'NO', 'When the user credit are below the minimum credit to call min_credit jump directly to the voucher IVR menu  (values: YES - NO) .',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Dail Command Parms', 'dialcommand_param', '|60|HRrL(%timeout%:61000:30000)', 'More information about the Dial : http://voip-info.org/wiki-Asterisk+cmd+dial<br>30 :  The timeout parameter is optional. If not specifed, the Dial command will wait indefinitely, exiting only when the originating channel hangs up, or all the dialed channels return a busy or error condition. Otherwise it specifies a maximum time, in seconds, that the Dial command is to wait for a channel to answer.<br>H: Allow the caller to hang up by dialing * <br>r: Generate a ringing tone for the calling party<br>R: Indicate ringing to the calling party when the called party indicates ringing, pass no audio until answered.<br>m: Provide Music on Hold to the calling party until the called channel answers.<br>L(x[:y][:z]): Limit the call to ''x'' ms, warning when ''y'' ms are left, repeated every ''z'' ms)<br>%timeout% tag is replaced by the calculated timeout according the credit & destination rate!.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('SIP/IAX Dial Command Parms', 'dialcommand_param_sipiax_friend', '|60|HL(3600000:61000:30000)', 'by default (3600000  =  1HOUR MAX CALL).',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Outbound Call', 'switchdialcommand', 'NO', 'Define the order to make the outbound call<br>YES -> SIP/dialedphonenumber@gateway_ip - NO  SIP/gateway_ip/dialedphonenumber<br>Both should work exactly the same but i experimented one case when gateway was supporting dialedphonenumber@gateway_ip, So in case of trouble, try it out.',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Failover Retry Limit', 'failover_recursive_limit', '2', 'failover recursive search - define how many time we want to authorize the research of the failover trunk when a call fails (value : 0 - 20) .',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Max Time', 'maxtime_tocall_negatif_free_route', '5400', 'For free calls, limit the duration: amount in seconds  .',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Send Reminder', 'send_reminder', 'NO', 'Send a reminder email to the user when they are under min_credit_2call  .',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Record Call', 'record_call', 'NO', 'enable to monitor the call (to record all the conversations) value : YES - NO .',1 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Monitor File Format', 'monitor_formatfile', 'gsm', 'format of the recorded monitor file.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('AGI Force Currency', 'agi_force_currency', '', 'Force to play the balance to the caller in a predefined currency, to use the currency set for by the customer leave this field empty.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Currency Associated', 'currency_association', 'usd:dollars,mxn:pesos,eur:euros,all:credit', 'Define all the audio (without file extensions) that you want to play according to currency (use , to separate, ie "usd:prepaid-dollar,mxn:pesos,eur:Euro,all:credit").',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('File Enter Destination', 'file_conf_enter_destination', 'prepaid-enter-dest', 'Please enter the file name you want to play when we prompt the calling party to enter the destination number, file_conf_enter_destination = prepaid-enter-number-u-calling-1-or-011.',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('File Language Menu', 'file_conf_enter_menulang', 'prepaid-menulang2', 'Please enter the file name you want to play when we prompt the calling party to choose the prefered language .',0 , 11);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id) VALUES ('Bill Callback', 'callback_bill_1stleg_ifcall_notconnected', 'YES', 'Define if you want to bill the 1st leg on callback even if the call is not connected to the destination.',1 , 11);





CREATE TABLE cc_timezone (
    id 								BIGSERIAL NOT NULL,
    gmtzone							CHARACTER VARYING(255),
    gmttime		 					CHARACTER VARYING(255),
	gmtoffset						INTEGER NOT NULL DEFAULT 0 
);
ALTER TABLE ONLY cc_timezone
ADD CONSTRAINT cc_timezone_pkey PRIMARY KEY (id);

INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-12:00) International Date Line West', 'GMT-12:00', '-43200');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-11:00) Midway Island,Samoa', 'GMT-11:00', '-39600');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-10:00) Hawaii', 'GMT-10:00', '-36000');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-09:00) Alaska', 'GMT-09:00', '-32400');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-08:00) Pacific Time (US & Canada) Tijuana', 'GMT-08:00', '-28800');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-07:00) Arizona', 'GMT+07:00', '-25200');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-07:00) Chihuahua, La Paz, Mazatlan', 'GMT+07:00', '-25200');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-07:00) Mountain Time(US & Canada)', 'GMT+07:00', '-25200');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-06:00) Central America', 'GMT+06:00', '-21600');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-06:00) Central Time (US & Canada)', 'GMT+06:00', '-21600');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-06:00) Guadalajara, Mexico City, Monterrey', 'GMT+06:00', '-21600');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-06:00) Saskatchewan', 'GMT+06:00', '-21600');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-05:00) Bagota, LIMA,Quito', 'GMT+05:00', '-18000');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-05:00) Eastern Time (US & Canada)', 'GMT+05:00', '-18000');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-05:00) Indiana (East)', 'GMT+05:00', '-18000');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-04:00) Atlantic Time (Canada)', 'GMT+04:00', '-14400');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-04:00) Caracas, La Paz', 'GMT+13:00', '-14400');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-04:00) Santiago', 'GMT+04:00', '-14400');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-03:30) NewFoundland', 'GMT+03:30', '-12600');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-03:00) Brasillia', 'GMT+03:00', '-10800');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-03:00) Buenos Aires, Georgetown', 'GMT+03:00', '-10800');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-03:00) Greenland', 'GMT+03:00', '-10800');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-03:00) Mid-Atlantic', 'GMT+03:00', '-10800');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-01:00) Azores', 'GMT+01:00', '-3600');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT-01:00) Cape Verd Is.', 'GMT+01:00', '-3600');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT) Casablanca, Monrovia', 'GMT+13:00', '0');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT) Greenwich Mean Time : Dublin, Edinburgh, Lisbon,  London', 'GMT', '0');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm,Vienna', 'GMT+01:00', '3600');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Pragua', 'GMT+01:00', '3600');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+01:00) Brussels, Copenhagen, Madrid, Paris', 'GMT+01:00', '3600');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb', 'GMT+01:00', '3600');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+01:00) West Central Africa', 'GMT+01:00', '3600');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+02:00) Athens, Istanbul, Minsk', 'GMT+02:00', '7200');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+02:00) Bucharest', 'GMT+02:00', '7200');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+02:00) Cairo', 'GMT+02:00', '7200');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+02:00) Harere,Pretoria', 'GMT+02:00', '7200');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+02:00) Helsinki, Kyiv, Riga, Sofia,Tallinn, Vilnius', 'GMT+02:00', '7200');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+02:00) Jeruasalem', 'GMT+02:00', '7200');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+03:00) Baghdad', 'GMT+03:00', '10800');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+03:00) Kuwait, Riyadh', 'GMT+03:00', '10800');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+03:00) Moscow, St.Petersburg,Volgograd', 'GMT+03:00', '10800');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+03:00) Nairobi', 'GMT+03:00', '10800');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+03:30) Tehran', 'GMT+03:30', '10800');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+04:00)Abu Dhabi, Muscat', 'GMT+04:00', '14400');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+04:00) Baku, Tbillisi, Yerevan', 'GMT+04:00', '14400');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+04:30) Kabul', 'GMT+04:30', '16200');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+05:00) Ekaterinburg', 'GMT+05:00', '18000');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+05:00) Islamabad, Karachi,Tashkent', 'GMT+05:00', '18000');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi', 'GMT+05:30', '19800');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+05:45) Kathmandu', 'GMT+05:45', '20700');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+06:00) Almaty, Novosibirsk', 'GMT+06:00', '21600');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+06:00) Astana, Dhaka', 'GMT+06:00', '21600');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+06:00) Sri Jayawardenepura', 'GMT+06:00', '21600');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+06:30) Rangoon', 'GMT+13:00', '23400');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+07:00) Bangkok, Honoi, Jakarta', 'GMT+07:00', '25200');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+07:00) Krasnoyarsk', 'GMT+07:00', '25200');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+08:00) Beijiing,Chongging, Honk King, Urumqi', 'GMT+08:00', '28800');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+08:00) Irkutsk, Ulaan Bataar', 'GMT+08:00', '28800');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+08:00) Kaula Lampur, Singapore', 'GMT+08:00', '28800');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+08:00) Perth', 'GMT+08:00', '28800');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+08:00) Taipei', 'GMT+08:00', '28800');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+09:00) Osaka, Sapporo, Tokyo', 'GMT+09:00', '32400');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+09:00) Seoul', 'GMT+09:00', '32400');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+09:00) Yakutsk', 'GMT+09:00', '32400');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+09:00) Adelaide', 'GMT+09:00', '32400');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+09:30) Darwin', 'GMT+10:00', '34200');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+10:00) Brisbane', 'GMT+10:00', '36000');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+10:00) Canberra, Melbourne, Sydney', 'GMT+10:00', '36000');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+10:00) Guam, Port Moresby', 'GMT+10:00', '36000');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+10:00) Hobart', 'GMT+10:00', '36000');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+10:00) Vladivostok', 'GMT+10:00', '36000');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+11:00) Magadan, Solomon Is. , New Caledonia', 'GMT+11:00', '39600');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+12:00) Auckland, Wellington', 'GMT+1200', '43200');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+12:00) Fiji, Kamchatka, Marshall Is.', 'GMT+12:00', '43200');
INSERT INTO cc_timezone ( gmtzone, gmttime, gmtoffset) VALUES ( '(GMT+13:00) Nuku alofa', 'GMT+13:00', '46800');
