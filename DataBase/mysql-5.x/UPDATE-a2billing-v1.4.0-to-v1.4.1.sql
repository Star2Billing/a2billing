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

INSERT INTO `cc_config` (`config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES('Option CallerID update', 'callerid_update', '0', 'Prompt the caller to update his callerID', 1, 'yes,no', 'agi-conf1');

DELETE FROM cc_config WHERE config_key = 'paymentmethod' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'personalinfo' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'customerinfo' AND config_group_title = 'webcustomerui';
DELETE FROM cc_config WHERE config_key = 'password' AND config_group_title = 'webcustomerui';
UPDATE cc_card_group SET users_perms = '262142' WHERE cc_card_group.id = 1;


CREATE TABLE cc_subscription_signup (
	id BIGINT NOT NULL ,
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

INSERT INTO cc_config_group (group_title ,group_description) VALUES ( 'ivr_creditcard', 'This configuration group handles the IVR Credit Card configuration.');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES('Play Ivr Credit Card Menu Language', 'menu_language', '0', 'enable the menu to choose the language, press 1 for English, pulsa 2 para el español, Pressez 3 pour Français', 1, 'yes,no', 'ivr_creditcard');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES('Menu Language Order', 'conf_order_menulang', 'en:fr:es', 'Enter the list of languages authorized for the menu.Use the code language separate by a colon charactere e.g: en:es:fr',0, NULL, 'ivr_creditcard');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES('Force Language', 'force_language', '', 'force the use of a language, if you dont want to use it leave the option empty, Values : ES, EN, FR, etc... (according to the audio you have installed).', 0, NULL, 'ivr_creditcard');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES('Asterisk Version', 'asterisk_version', '1_4', 'Asterisk Version Information, 1_1,1_2,1_4 By Default it will take 1_2 or higher .', 0, NULL, 'ivr_creditcard');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES('Intro Prompt', 'intro_prompt', '', 'Introduction prompt : to specify an additional prompt to play at the beginning of credit card ivr .', 0, NULL, 'ivr_creditcard');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES('Try count', 'number_try', '3', 'number of times the user can enter a card number.', 0, NULL, 'ivr_creditcard');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES('Max Credit card length', 'max_credit_card_length', '16', 'max length of the credit card number accepted.', 0, NULL, 'ivr_creditcard');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES('Min Credit card length', 'min_credit_card_length', '13', 'min length of the credit card number accepted.', 0, NULL, 'ivr_creditcard');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES('Verbosity', 'verbosity_level', '3', '0 = FATAL; 1 = ERROR; WARN = 2 ; INFO = 3 ; DEBUG = 4', 0, NULL, 'ivr_creditcard');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES('Logging', 'logging_level', '3', '0 = FATAL; 1 = ERROR; WARN = 2 ; INFO = 3 ; DEBUG = 4', 0, NULL, 'ivr_creditcard');

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES
	( 'Currency Associated', 'currency_association', 'usd:dollars,mxn:pesos,eur:euros,all:credit', 'Define all the audio (without file extensions) that you want to play according to currency (use , to separate, ie "usd:prepaid-dollar,mxn:pesos,eur:Euro,all:credit").', 0, NULL, 'ivr_creditcard'),
	( 'Cents Currency Associated', 'currency_cents_association', 'usd:prepaid-cents,eur:prepaid-cents,gbp:prepaid-pence,all:credit', 'Define all the audio (without file extensions) that you want to play according to cents currency (use , to separate, ie "amd:lumas").By default the file used is "prepaid-cents" .Use plural to define the cents currency sound, but import two sounds but cents currency defined : ending by ''s'' and not ending by ''s'' (i.e. for lumas , add 2 files : ''lumas'' and ''luma'') ', 0, NULL, 'ivr_creditcard');


INSERT INTO cc_config ( config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES ('Say GoodBye', 'say_goodbye', '0', 'play the goodbye message when the user has finished.', 1, 'yes,no', 'ivr_creditcard');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES ('Maximum amount payment', 'max_amount', '100', 'Maximum payment amount for one refill', 0, NULL, 'ivr_creditcard');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES ('Minimum amount payment', 'min_amount', '10', 'Minimum payment amount for one refill', 0, NULL, 'ivr_creditcard');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES ( 'Amount refill currency', 'refill_currency', 'dollars', 'Define the audio (without file extensions) that you want to play the min/max amounts of refills.', 0, NULL, 'ivr_creditcard');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES ('Account User name parameter', 'account_param', 'account', 'Name of the account parameter used to post the request to the external webservice', 0, NULL, 'ivr_creditcard');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES ('Credit Card number name parameter', 'creditcard_param', 'ccnum', 'Name of the account parameter used to post the request to the external webservice', 0, NULL, 'ivr_creditcard');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES ('Expiry Date name parameter', 'expirydate_param', 'exp', 'Name of the expiry date parameter used to post the request to the external webservice', 0, NULL, 'ivr_creditcard');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES ('CCV name parameter', 'ccv_param', 'ccv', 'Name of the ccv parameter used to post the request to the external webservice', 0, NULL, 'ivr_creditcard');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES ('URL Web service', 'url_creditcard', '', 'Url used to post the request to the external webservice', 0, NULL, 'ivr_creditcard');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES ('Amount name parameter', 'amount_param', 'amont', 'Name of the amount parameter used to post the request to the external webservice', 0, NULL, 'ivr_creditcard');
INSERT INTO cc_config ( config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES ('Say balance after refill', 'say_balance_after_refill', '1', 'play the credit balance after a refill when the user has finished.', 1, 'yes,no', 'ivr_creditcard');