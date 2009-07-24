--
-- A2Billing database script - Update database for MYSQL 5.X
-- 
-- 
-- Usage:
-- mysql -u root -p"root password" < UPDATE-a2billing-v1.4.0-to-v1.4.1.sql
--



ALTER TABLE cc_charge DROP currency;
ALTER TABLE cc_subscription_fee DROP currency;  
ALTER TABLE cc_ui_authen ADD country VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_bin NULL ;
ALTER TABLE cc_ui_authen ADD city VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_bin NULL ;

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES('Option CallerID update', 'callerid_update', '0', 'Prompt the caller to update his callerID', 1, 'yes,no', 'agi-conf1');

DELETE FROM cc_config WHERE config_key = 'paymentmethod' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'personalinfo' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'customerinfo' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'password' AND config_group_title = 'webcustomerui';
UPDATE cc_card_group SET users_perms = '262142' WHERE cc_card_group.id = 1;


CREATE TABLE cc_subscription_signup (
	id BIGINT NOT NULL auto_increment,
	label VARCHAR( 50 ) collate utf8_bin NOT NULL ,
	id_subscription BIGINT NULL ,
	description MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_bin NULL ,
	enable TINYINT NOT NULL DEFAULT '1',
	PRIMARY KEY ( id )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DELETE FROM cc_config WHERE config_key = 'currency_cents_association';
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
	VALUES ('Cents Currency Associated', 'currency_cents_association', 'usd:prepaid-cents,eur:prepaid-cents,gbp:prepaid-pence,all:credit', 'Define all the audio (without file extensions) that you want to play according to cents currency (use , to separate, ie "amd:lumas").By default the file used is "prepaid-cents" .Use plural to define the cents currency sound, but import two sounds but cents currency defined : ending by ''s'' and not ending by ''s'' (i.e. for lumas , add 2 files : ''lumas'' and ''luma'') ', '0', NULL, 'ivr_creditcard');
DELETE FROM cc_config WHERE config_key = 'currency_association_minor';


-- Local Dialing Normalisation
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES 
	('Option Local Dialing', 'local_dialing_addcountryprefix', '0', 'Add the countryprefix of the user in front of the dialed number if this one have only 1 leading zero', 1, 'yes,no', 'agi-conf1');


-- Remove E-Product from 1.4.1
DROP TABLE cc_ecommerce_product;

INSERT INTO cc_invoice_conf (key_val ,`value`) VALUES ('display_account', '0');

-- add missing agent field
ALTER TABLE cc_system_log ADD agent TINYINT DEFAULT 0;

DELETE FROM cc_config WHERE config_key = 'show_icon_invoice';
DELETE FROM cc_config WHERE config_key = 'show_top_frame';

-- add MXN currency on Paypal
UPDATE cc_configuration SET set_function = 'tep_cfg_select_option(array(''Selected Currency'',''USD'',''CAD'',''EUR'',''GBP'',''JPY'',''MXN''), ' WHERE configuration_key = 'MODULE_PAYMENT_PAYPAL_CURRENCY' ;


-- DID CALL AND BILLING
ALTER TABLE cc_didgroup DROP iduser;
ALTER TABLE cc_didgroup ADD connection_charge DECIMAL( 15, 5 ) NOT NULL DEFAULT '0',
ADD selling_rate DECIMAL( 15, 5 ) NOT NULL DEFAULT '0';

ALTER TABLE cc_did ADD UNIQUE (did);

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


-- change type in cc_config
ALTER TABLE cc_config CHANGE config_title config_title VARCHAR( 100 ); 
ALTER TABLE cc_config CHANGE config_key config_key VARCHAR( 100 ); 
ALTER TABLE cc_config CHANGE config_value config_value VARCHAR( 100 ); 
ALTER TABLE cc_config CHANGE config_listvalues config_listvalues VARCHAR( 100 ); 

-- Set Qualify at No per default
UPDATE cc_config SET config_value='no' WHERE config_key='qualify';


-- Update Paypal URL API
UPDATE cc_config SET config_value='https://www.paypal.com/cgi-bin/webscr' WHERE config_key='paypal_payment_url';

-- change type in cc_config
ALTER TABLE cc_config CHANGE config_value config_value VARCHAR( 200 ); 


-- DROP TABLE `cc_templatemail` ;

--
-- Table structure for table `cc_templatemail`
--

CREATE TABLE IF NOT EXISTS `cc_templatemail` (
  `id` int(11) NOT NULL,
  `id_language` char(20) collate utf8_bin NOT NULL default 'en',
  `mailtype` char(50) collate utf8_bin default NULL,
  `fromemail` char(70) collate utf8_bin default NULL,
  `fromname` char(70) collate utf8_bin default NULL,
  `subject` char(70) collate utf8_bin default NULL,
  `messagetext` longtext collate utf8_bin,
  UNIQUE KEY `cons_cc_templatemail_id_language` (`mailtype`,`id_language`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `cc_templatemail`
--

INSERT INTO `cc_templatemail` (`id`, `id_language`, `mailtype`, `fromemail`, `fromname`, `subject`, `messagetext`) VALUES(1, 'en', 'signup', 'info@call-labs.com', 'Call-Labs', 'SIGNUP CONFIRMATION', '\nThank you for registering with us\n\nPlease click on below link to activate your account.\n\nhttp://call-labs.com/A2Billing_UI/signup/activate.php?key=$login$\n\nPlease make sure you active your account by making payment to us either by\ncredit card, wire transfer, money order, cheque, and western union money\ntransfer, money Gram, and Pay pal.\n\n\nKind regards,\nCall Labs\n');
INSERT INTO `cc_templatemail` (`id`, `id_language`, `mailtype`, `fromemail`, `fromname`, `subject`, `messagetext`) VALUES(2, 'en', 'reminder', 'info@call-labs.com', 'Call-Labs', 'Your Call-Labs account $cardnumber$ is low on credit ($currency$ $credit$', '\n\nYour Call-Labs Account number $cardnumber$ is running low on credit.\n\nThere is currently only $creditcurrency$ $currency$ left on your account which is lower than the warning level defined ($credit_notification$)\n\n\nPlease top up your account ASAP to ensure continued service\n\nIf you no longer wish to receive these notifications or would like to change the balance amount at which these warnings are generated,\nplease connect on your myaccount panel and change the appropriate parameters\n\n\nyour account information :\nYour account number for VOIP authentication : $cardnumber$\n\nhttp://myaccount.call-labs.com/\nYour account login : $cardalias$\nYour account password : $password$\n\n\nThanks,\n/Call-Labs Team\n-------------------------------------\nhttp://www.call-labs.com\n ');
INSERT INTO `cc_templatemail` (`id`, `id_language`, `mailtype`, `fromemail`, `fromname`, `subject`, `messagetext`) VALUES(3, 'en', 'forgetpassword', 'info@call-labs.com', 'Call-Labs', 'Login Information', 'Your login information is as below:\n\nYour account is $cardnumber$\n\nYour password is $password$\n\nYour cardalias is $login$\n\nhttp://call-labs.com/A2BCustomer_UI/\n\nKind regards,\nCall Labs\n');
INSERT INTO `cc_templatemail` (`id`, `id_language`, `mailtype`, `fromemail`, `fromname`, `subject`, `messagetext`) VALUES(4, 'en', 'signupconfirmed', 'info@call-labs.com', 'Call-Labs', 'SIGNUP CONFIRMATION', 'Thank you for registering with us\n\nPlease make sure you active your account by making payment to us either by\ncredit card, wire transfer, money order, cheque, and western union money\ntransfer, money Gram, and Pay pal.\n\nYour account is $cardnumber$\n\nYour password is $password$\n\nTo go to your account :\nhttp://call-labs.com/A2BCustomer_UI/\n\nKind regards,\nCall Labs\n');
INSERT INTO `cc_templatemail` (`id`, `id_language`, `mailtype`, `fromemail`, `fromname`, `subject`, `messagetext`) VALUES(5, 'en', 'epaymentverify', 'info@call-labs.com', 'Call-Labs', 'Epayment Gateway Security Verification Failed', 'Dear Administrator\n\nPlease check the Epayment Log, System has logged a Epayment Security failure. that may be a possible attack on epayment processing.\n\nTime of Transaction: $time$ \nPayment Gateway: $paymentgateway$\nAmount: $itemAmount$\n\n\n\nKind regards,\nCall Labs\n');
INSERT INTO `cc_templatemail` (`id`, `id_language`, `mailtype`, `fromemail`, `fromname`, `subject`, `messagetext`) VALUES(6, 'en', 'payment', 'info@call-labs.com', 'Call-Labs', 'PAYMENT CONFIRMATION', 'Thank you for shopping at Call-Labs.\n\nShopping details is as below.\n\nItem Name = <b>$itemName$</b>\nItem ID = <b>$itemID$</b>\nAmount = <b>$itemAmount$ $base_currency$</b>\nPayment Method = <b>$paymentMethod$</b>\nStatus = <b>$paymentStatus$</b>\n\n\nKind regards,\nCall Labs\n');
INSERT INTO `cc_templatemail` (`id`, `id_language`, `mailtype`, `fromemail`, `fromname`, `subject`, `messagetext`) VALUES(7, 'en', 'invoice', 'info@call-labs.com', 'Call-Labs', 'A2BILLING INVOICE', 'Dear Customer.\n\nAttached is the invoice.\n\nKind regards,\nCall Labs\n');
INSERT INTO `cc_templatemail` (`id`, `id_language`, `mailtype`, `fromemail`, `fromname`, `subject`, `messagetext`) VALUES(8, 'en', 'remindercall', 'info@call-labs.com', 'Call-Labs', 'Your Call-Labs account $cardnumber$ is low on credit ($currency$ $credit$', '\n\nYour Call-Labs Account number $cardnumber$ is running low on credit.\n\nThere is currently only $creditcurrency$ $currency$ left on your account which is lower than the minimum credit to call\n\n\nPlease top up your account ASAP to ensure continued service\n\n\nPlease connect on your myaccount panel and change the appropriate parameters\n\n\nyour account information :\nYour account number for VOIP authentication : $cardnumber$\n\nhttp://myaccount.call-labs.com/\nYour account login : $cardalias$\nYour account password : $password$\n\n\nThanks,\n/Call-Labs Team\n-------------------------------------\nhttp://www.call-labs.com\n ');
