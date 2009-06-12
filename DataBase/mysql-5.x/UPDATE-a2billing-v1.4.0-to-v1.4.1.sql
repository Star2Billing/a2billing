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


-- Dialled Digit Normalisation
ALTER TABLE cc_card ADD add_dialing_prefix varchar(10) collate utf8_bin;


-- Remove E-Product from 1.4.1
DROP TABLE cc_ecommerce_product;

INSERT INTO cc_invoice_conf (key_val ,`value`) VALUES ('display_account', '0');

-- add missing agent field
ALTER TABLE cc_system_log ADD agent TINYINT DEFAULT 0;

DELETE FROM cc_config WHERE config_key = 'show_icon_invoice';
DELETE FROM cc_config WHERE config_key = 'show_top_frame';

-- add MXN currency on Paypal
UPDATE cc_configuration SET set_function = 'tep_cfg_select_option(array(''Selected Currency'',''USD'',''CAD'',''EUR'',''GBP'',''JPY'',''MXN''), ' WHERE configuration_key = 'MODULE_PAYMENT_PAYPAL_CURRENCY' ;


