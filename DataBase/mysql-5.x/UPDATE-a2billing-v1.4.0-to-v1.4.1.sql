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
