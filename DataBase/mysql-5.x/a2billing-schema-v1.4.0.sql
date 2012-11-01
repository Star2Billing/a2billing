
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
-- A2Billing database script - Create user & create database for MYSQL 5.X
--

-- Usage:
-- mysql -u root -p"root password" < a2billing-schema-mysql-v1.4.0.sql


--
-- A2Billing database - Create database schema
--


SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


-- --------------------------------------------------------

--
-- Table structure for table `cc_agent`
--

CREATE TABLE IF NOT EXISTS `cc_agent` (
  `id` bigint(20) NOT NULL auto_increment,
  `datecreation` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `active` char(1) collate utf8_bin NOT NULL default 'f',
  `login` char(20) collate utf8_bin NOT NULL,
  `passwd` char(40) collate utf8_bin default NULL,
  `location` text collate utf8_bin,
  `language` char(5) collate utf8_bin default 'en',
  `id_tariffgroup` int(11) default NULL,
  `options` int(11) NOT NULL default '0',
  `credit` decimal(15,5) NOT NULL default '0.00000',
  `currency` char(3) collate utf8_bin default 'USD',
  `locale` char(10) collate utf8_bin default 'C',
  `commission` decimal(10,4) NOT NULL default '0.0000',
  `vat` decimal(10,4) NOT NULL default '0.0000',
  `banner` text collate utf8_bin,
  `perms` int(11) default NULL,
  `lastname` char(50) collate utf8_bin default NULL,
  `firstname` char(50) collate utf8_bin default NULL,
  `address` char(100) collate utf8_bin default NULL,
  `city` char(40) collate utf8_bin default NULL,
  `state` char(40) collate utf8_bin default NULL,
  `country` char(40) collate utf8_bin default NULL,
  `zipcode` char(20) collate utf8_bin default NULL,
  `phone` char(20) collate utf8_bin default NULL,
  `email` char(70) collate utf8_bin default NULL,
  `fax` char(20) collate utf8_bin default NULL,
  `company` varchar(50) collate utf8_bin default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_agent`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_agent_commission`
--

CREATE TABLE IF NOT EXISTS `cc_agent_commission` (
  `id` bigint(20) NOT NULL auto_increment,
  `id_payment` bigint(20) default NULL,
  `id_card` bigint(20) NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `amount` decimal(15,5) NOT NULL,
  `paid_status` tinyint(4) NOT NULL default '0',
  `description` mediumtext collate utf8_bin,
  `id_agent` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_agent_commission`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_agent_signup`
--

CREATE TABLE IF NOT EXISTS `cc_agent_signup` (
  `id` bigint(20) NOT NULL auto_increment,
  `id_agent` int(11) NOT NULL,
  `code` varchar(30) collate utf8_bin NOT NULL,
  `id_tariffgroup` int(11) NOT NULL,
  `id_group` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_agent_signup`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_agent_tariffgroup`
--

CREATE TABLE IF NOT EXISTS `cc_agent_tariffgroup` (
  `id_agent` bigint(20) NOT NULL,
  `id_tariffgroup` int(11) NOT NULL,
  PRIMARY KEY  (`id_agent`,`id_tariffgroup`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `cc_agent_tariffgroup`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_alarm`
--

CREATE TABLE IF NOT EXISTS `cc_alarm` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` text collate utf8_bin NOT NULL,
  `periode` int(11) NOT NULL default '1',
  `type` int(11) NOT NULL default '1',
  `maxvalue` float NOT NULL,
  `minvalue` float NOT NULL default '-1',
  `id_trunk` int(11) default NULL,
  `status` int(11) NOT NULL default '0',
  `numberofrun` int(11) NOT NULL default '0',
  `numberofalarm` int(11) NOT NULL default '0',
  `datecreate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `datelastrun` timestamp NOT NULL default '0000-00-00 00:00:00',
  `emailreport` varchar(50) collate utf8_bin default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_alarm`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_alarm_report`
--

CREATE TABLE IF NOT EXISTS `cc_alarm_report` (
  `id` bigint(20) NOT NULL auto_increment,
  `cc_alarm_id` bigint(20) NOT NULL,
  `calculatedvalue` float NOT NULL,
  `daterun` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_alarm_report`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_autorefill_report`
--

CREATE TABLE IF NOT EXISTS `cc_autorefill_report` (
  `id` bigint(20) NOT NULL auto_increment,
  `daterun` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `totalcardperform` int(11) default NULL,
  `totalcredit` decimal(15,5) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_autorefill_report`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_backup`
--

CREATE TABLE IF NOT EXISTS `cc_backup` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_bin NOT NULL,
  `path` varchar(255) collate utf8_bin NOT NULL,
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `cons_cc_backup_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_backup`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_billing_customer`
--

CREATE TABLE IF NOT EXISTS `cc_billing_customer` (
  `id` bigint(20) NOT NULL auto_increment,
  `id_card` bigint(20) NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `id_invoice` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_billing_customer`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_call`
--

CREATE TABLE IF NOT EXISTS `cc_call` (
  `id` bigint(20) NOT NULL auto_increment,
  `sessionid` varchar(40) collate utf8_bin NOT NULL,
  `uniqueid` varchar(30) collate utf8_bin NOT NULL,
  `card_id` bigint(20) NOT NULL,
  `nasipaddress` varchar(30) collate utf8_bin NOT NULL,
  `starttime` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `stoptime` timestamp NOT NULL default '0000-00-00 00:00:00',
  `sessiontime` int(11) default NULL,
  `calledstation` varchar(30) collate utf8_bin NOT NULL,
  `sessionbill` float default NULL,
  `id_tariffgroup` int(11) default NULL,
  `id_tariffplan` int(11) default NULL,
  `id_ratecard` int(11) default NULL,
  `id_trunk` int(11) default NULL,
  `sipiax` int(11) default '0',
  `src` varchar(40) collate utf8_bin NOT NULL,
  `id_did` int(11) default NULL,
  `buycost` decimal(15,5) default '0.00000',
  `id_card_package_offer` int(11) default '0',
  `real_sessiontime` int(11) default NULL,
  `dnid` varchar(40) collate utf8_bin NOT NULL,
  `terminatecauseid` int(1) default '1',
  `destination` int(11) default '0',
  PRIMARY KEY  (`id`),
  KEY `starttime` (`starttime`),
  KEY `calledstation` (`calledstation`),
  KEY `terminatecauseid` (`terminatecauseid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_call`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_callback_spool`
--

CREATE TABLE IF NOT EXISTS `cc_callback_spool` (
  `id` bigint(20) NOT NULL auto_increment,
  `uniqueid` varchar(40) collate utf8_bin default NULL,
  `entry_time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `status` varchar(80) collate utf8_bin default NULL,
  `server_ip` varchar(40) collate utf8_bin default NULL,
  `num_attempt` int(11) NOT NULL default '0',
  `last_attempt_time` timestamp NOT NULL default '0000-00-00 00:00:00',
  `manager_result` varchar(60) collate utf8_bin default NULL,
  `agi_result` varchar(60) collate utf8_bin default NULL,
  `callback_time` timestamp NOT NULL default '0000-00-00 00:00:00',
  `channel` varchar(60) collate utf8_bin default NULL,
  `exten` varchar(60) collate utf8_bin default NULL,
  `context` varchar(60) collate utf8_bin default NULL,
  `priority` varchar(60) collate utf8_bin default NULL,
  `application` varchar(60) collate utf8_bin default NULL,
  `data` varchar(60) collate utf8_bin default NULL,
  `timeout` varchar(60) collate utf8_bin default NULL,
  `callerid` varchar(60) collate utf8_bin default NULL,
  `variable` varchar(300) collate utf8_bin default NULL,
  `account` varchar(60) collate utf8_bin default NULL,
  `async` varchar(60) collate utf8_bin default NULL,
  `actionid` varchar(60) collate utf8_bin default NULL,
  `id_server` int(11) default NULL,
  `id_server_group` int(11) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `cc_callback_spool_uniqueid_key` (`uniqueid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_callback_spool`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_callerid`
--

CREATE TABLE IF NOT EXISTS `cc_callerid` (
  `id` bigint(20) NOT NULL auto_increment,
  `cid` varchar(100) collate utf8_bin NOT NULL,
  `id_cc_card` bigint(20) NOT NULL,
  `activated` char(1) collate utf8_bin NOT NULL default 't',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `cons_cc_callerid_cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_callerid`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_call_archive`
--

CREATE TABLE IF NOT EXISTS `cc_call_archive` (
  `id` bigint(20) NOT NULL auto_increment,
  `sessionid` char(40) collate utf8_bin NOT NULL,
  `uniqueid` char(30) collate utf8_bin NOT NULL,
  `username` char(40) collate utf8_bin NOT NULL,
  `nasipaddress` char(30) collate utf8_bin default NULL,
  `starttime` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `stoptime` timestamp NOT NULL default '0000-00-00 00:00:00',
  `sessiontime` int(11) default NULL,
  `calledstation` char(30) collate utf8_bin default NULL,
  `startdelay` int(11) default NULL,
  `stopdelay` int(11) default NULL,
  `terminatecause` char(20) collate utf8_bin default NULL,
  `usertariff` char(20) collate utf8_bin default NULL,
  `calledprovider` char(20) collate utf8_bin default NULL,
  `calledcountry` char(30) collate utf8_bin default NULL,
  `calledsub` char(20) collate utf8_bin default NULL,
  `calledrate` float default NULL,
  `sessionbill` float default NULL,
  `destination` char(40) collate utf8_bin default NULL,
  `id_tariffgroup` int(11) default NULL,
  `id_tariffplan` int(11) default NULL,
  `id_ratecard` int(11) default NULL,
  `id_trunk` int(11) default NULL,
  `sipiax` int(11) default '0',
  `src` char(40) collate utf8_bin default NULL,
  `id_did` int(11) default NULL,
  `buyrate` decimal(15,5) default '0.00000',
  `buycost` decimal(15,5) default '0.00000',
  `id_card_package_offer` int(11) default '0',
  `real_sessiontime` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `username` (`username`),
  KEY `starttime` (`starttime`),
  KEY `terminatecause` (`terminatecause`),
  KEY `calledstation` (`calledstation`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_call_archive`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_campaign`
--

CREATE TABLE IF NOT EXISTS `cc_campaign` (
  `id` int(11) NOT NULL auto_increment,
  `name` char(50) collate utf8_bin NOT NULL,
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `startingdate` timestamp NOT NULL default '0000-00-00 00:00:00',
  `expirationdate` timestamp NOT NULL default '0000-00-00 00:00:00',
  `description` mediumtext collate utf8_bin,
  `id_card` bigint(20) NOT NULL default '0',
  `secondusedreal` int(11) default '0',
  `nb_callmade` int(11) default '0',
  `status` int(11) NOT NULL default '1',
  `frequency` int(11) NOT NULL default '20',
  `forward_number` char(50) collate utf8_bin default NULL,
  `daily_start_time` time NOT NULL default '10:00:00',
  `daily_stop_time` time NOT NULL default '18:00:00',
  `monday` tinyint(4) NOT NULL default '1',
  `tuesday` tinyint(4) NOT NULL default '1',
  `wednesday` tinyint(4) NOT NULL default '1',
  `thursday` tinyint(4) NOT NULL default '1',
  `friday` tinyint(4) NOT NULL default '1',
  `saturday` tinyint(4) NOT NULL default '0',
  `sunday` tinyint(4) NOT NULL default '0',
  `id_cid_group` int(11) NOT NULL,
  `id_campaign_config` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `cons_cc_campaign_campaign_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_campaign`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_campaignconf_cardgroup`
--

CREATE TABLE IF NOT EXISTS `cc_campaignconf_cardgroup` (
  `id_campaign_config` int(11) NOT NULL,
  `id_card_group` int(11) NOT NULL,
  PRIMARY KEY  (`id_campaign_config`,`id_card_group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `cc_campaignconf_cardgroup`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_campaign_config`
--

CREATE TABLE IF NOT EXISTS `cc_campaign_config` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(40) collate utf8_bin NOT NULL,
  `flatrate` decimal(15,5) NOT NULL default '0.00000',
  `context` varchar(40) collate utf8_bin NOT NULL,
  `description` mediumtext collate utf8_bin,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_campaign_config`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_campaign_phonebook`
--

CREATE TABLE IF NOT EXISTS `cc_campaign_phonebook` (
  `id_campaign` int(11) NOT NULL,
  `id_phonebook` int(11) NOT NULL,
  PRIMARY KEY  (`id_campaign`,`id_phonebook`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `cc_campaign_phonebook`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_campaign_phonestatus`
--

CREATE TABLE IF NOT EXISTS `cc_campaign_phonestatus` (
  `id_phonenumber` bigint(20) NOT NULL,
  `id_campaign` int(11) NOT NULL,
  `id_callback` varchar(40) collate utf8_bin NOT NULL,
  `status` int(11) NOT NULL default '0',
  `lastuse` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id_phonenumber`,`id_campaign`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `cc_campaign_phonestatus`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_card`
--

CREATE TABLE IF NOT EXISTS `cc_card` (
  `id` bigint(20) NOT NULL auto_increment,
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `firstusedate` timestamp NOT NULL default '0000-00-00 00:00:00',
  `expirationdate` timestamp NOT NULL default '0000-00-00 00:00:00',
  `enableexpire` int(11) default '0',
  `expiredays` int(11) default '0',
  `username` varchar(50) collate utf8_bin NOT NULL,
  `useralias` varchar(50) collate utf8_bin NOT NULL,
  `uipass` varchar(50) collate utf8_bin NOT NULL,
  `credit` decimal(15,5) NOT NULL default '0.00000',
  `tariff` int(11) default '0',
  `id_didgroup` int(11) default '0',
  `activated` char(1) collate utf8_bin NOT NULL default 'f',
  `status` int(11) NOT NULL default '1',
  `lastname` varchar(50) collate utf8_bin NOT NULL,
  `firstname` varchar(50) collate utf8_bin NOT NULL,
  `address` varchar(100) collate utf8_bin NOT NULL,
  `city` varchar(40) collate utf8_bin NOT NULL,
  `state` varchar(40) collate utf8_bin NOT NULL,
  `country` varchar(40) collate utf8_bin NOT NULL,
  `zipcode` varchar(20) collate utf8_bin NOT NULL,
  `phone` varchar(20) collate utf8_bin NOT NULL,
  `email` varchar(70) collate utf8_bin NOT NULL,
  `fax` varchar(20) collate utf8_bin NOT NULL,
  `inuse` int(11) default '0',
  `simultaccess` int(11) default '0',
  `currency` char(3) collate utf8_bin default 'USD',
  `lastuse` timestamp NOT NULL default '0000-00-00 00:00:00',
  `nbused` int(11) default '0',
  `typepaid` int(11) default '0',
  `creditlimit` int(11) default '0',
  `voipcall` int(11) default '0',
  `sip_buddy` int(11) default '0',
  `iax_buddy` int(11) default '0',
  `language` char(5) collate utf8_bin default 'en',
  `redial` varchar(50) collate utf8_bin NOT NULL,
  `runservice` int(11) default '0',
  `nbservice` int(11) default '0',
  `id_campaign` int(11) default '0',
  `num_trials_done` bigint(20) default '0',
  `vat` float NOT NULL default '0',
  `servicelastrun` timestamp NOT NULL default '0000-00-00 00:00:00',
  `initialbalance` decimal(15,5) NOT NULL default '0.00000',
  `invoiceday` int(11) default '1',
  `autorefill` int(11) default '0',
  `loginkey` varchar(40) collate utf8_bin NOT NULL,
  `mac_addr` char(17) collate utf8_bin NOT NULL default '00-00-00-00-00-00',
  `id_timezone` int(11) default '0',
  `tag` varchar(50) collate utf8_bin NOT NULL,
  `voicemail_permitted` int(11) NOT NULL default '0',
  `voicemail_activated` smallint(6) NOT NULL default '0',
  `last_notification` timestamp NULL default NULL,
  `email_notification` varchar(70) collate utf8_bin NOT NULL,
  `notify_email` smallint(6) NOT NULL default '0',
  `credit_notification` int(11) NOT NULL default '-1',
  `id_group` int(11) NOT NULL default '1',
  `company_name` varchar(50) collate utf8_bin NOT NULL,
  `company_website` varchar(60) collate utf8_bin NOT NULL,
  `vat_rn` varchar(40) collate utf8_bin default NULL,
  `traffic` bigint(20) default NULL,
  `traffic_target` varchar(300) collate utf8_bin NOT NULL,
  `discount` decimal(5,2) NOT NULL default '0.00',
  `restriction` tinyint(4) NOT NULL default '0',
  `id_seria` int(11) default NULL,
  `serial` bigint(20) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `cons_cc_card_username` (`username`),
  UNIQUE KEY `cons_cc_card_useralias` (`useralias`),
  KEY `creationdate` (`creationdate`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Triggers `cc_card`
--
DROP TRIGGER IF EXISTS `cc_card_serial_set`;
DELIMITER //
CREATE TRIGGER `cc_card_serial_set` BEFORE INSERT ON `cc_card`
 FOR EACH ROW BEGIN
	UPDATE cc_card_seria set value=value+1  where id=NEW.id_seria ;
	SELECT value INTO @serial from cc_card_seria where id=NEW.id_seria ;
	SET NEW.serial=@serial;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `cc_card_serial_update`;
DELIMITER //
CREATE TRIGGER `cc_card_serial_update` BEFORE UPDATE ON `cc_card`
 FOR EACH ROW BEGIN
	IF NEW.id_seria<>OLD.id_seria OR OLD.id_seria IS NULL THEN
		UPDATE cc_card_seria set value=value+1  where id=NEW.id_seria ;
		SELECT value INTO @serial from cc_card_seria where id=NEW.id_seria ;
		SET NEW.serial=@serial;
	END IF;
END
//
DELIMITER ;

--
-- Dumping data for table `cc_card`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_cardgroup_service`
--

CREATE TABLE IF NOT EXISTS `cc_cardgroup_service` (
  `id_card_group` int(11) NOT NULL,
  `id_service` int(11) NOT NULL,
  PRIMARY KEY  (`id_card_group`,`id_service`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `cc_cardgroup_service`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_card_archive`
--

CREATE TABLE IF NOT EXISTS `cc_card_archive` (
  `id` bigint(20) NOT NULL,
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `firstusedate` timestamp NOT NULL default '0000-00-00 00:00:00',
  `expirationdate` timestamp NOT NULL default '0000-00-00 00:00:00',
  `enableexpire` int(11) default '0',
  `expiredays` int(11) default '0',
  `username` char(50) collate utf8_bin NOT NULL,
  `useralias` char(50) collate utf8_bin NOT NULL,
  `uipass` char(50) collate utf8_bin default NULL,
  `credit` decimal(15,5) NOT NULL default '0.00000',
  `tariff` int(11) default '0',
  `id_didgroup` int(11) default '0',
  `activated` char(1) collate utf8_bin NOT NULL default 'f',
  `status` int(11) default '1',
  `lastname` char(50) collate utf8_bin default NULL,
  `firstname` char(50) collate utf8_bin default NULL,
  `address` char(100) collate utf8_bin default NULL,
  `city` char(40) collate utf8_bin default NULL,
  `state` char(40) collate utf8_bin default NULL,
  `country` char(40) collate utf8_bin default NULL,
  `zipcode` char(20) collate utf8_bin default NULL,
  `phone` char(20) collate utf8_bin default NULL,
  `email` char(70) collate utf8_bin default NULL,
  `fax` char(20) collate utf8_bin default NULL,
  `inuse` int(11) default '0',
  `simultaccess` int(11) default '0',
  `currency` char(3) collate utf8_bin default 'USD',
  `lastuse` timestamp NOT NULL default '0000-00-00 00:00:00',
  `nbused` int(11) default '0',
  `typepaid` int(11) default '0',
  `creditlimit` int(11) default '0',
  `voipcall` int(11) default '0',
  `sip_buddy` int(11) default '0',
  `iax_buddy` int(11) default '0',
  `language` char(5) collate utf8_bin default 'en',
  `redial` char(50) collate utf8_bin default NULL,
  `runservice` int(11) default '0',
  `nbservice` int(11) default '0',
  `id_campaign` int(11) default '0',
  `num_trials_done` bigint(20) default '0',
  `vat` float NOT NULL default '0',
  `servicelastrun` timestamp NOT NULL default '0000-00-00 00:00:00',
  `initialbalance` decimal(15,5) NOT NULL default '0.00000',
  `invoiceday` int(11) default '1',
  `autorefill` int(11) default '0',
  `loginkey` char(40) collate utf8_bin default NULL,
  `activatedbyuser` char(1) collate utf8_bin NOT NULL default 't',
  `id_timezone` int(11) default '0',
  `tag` char(50) collate utf8_bin default NULL,
  `voicemail_permitted` int(11) NOT NULL default '0',
  `voicemail_activated` smallint(6) NOT NULL default '0',
  `last_notification` timestamp NULL default NULL,
  `email_notification` char(70) collate utf8_bin default NULL,
  `notify_email` smallint(6) NOT NULL default '0',
  `credit_notification` int(11) NOT NULL default '-1',
  `id_group` int(11) NOT NULL default '1',
  `company_name` varchar(50) collate utf8_bin default NULL,
  `company_website` varchar(60) collate utf8_bin default NULL,
  `VAT_RN` varchar(40) collate utf8_bin default NULL,
  `traffic` bigint(20) default NULL,
  `traffic_target` mediumtext collate utf8_bin,
  `discount` decimal(5,2) NOT NULL default '0.00',
  `restriction` tinyint(4) NOT NULL default '0',
  `mac_addr` char(17) collate utf8_bin NOT NULL default '00-00-00-00-00-00',
  PRIMARY KEY  (`id`),
  KEY `creationdate` (`creationdate`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `cc_card_archive`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_card_group`
--

CREATE TABLE IF NOT EXISTS `cc_card_group` (
  `id` int(11) NOT NULL auto_increment,
  `name` char(30) collate utf8_bin NOT NULL,
  `description` mediumtext collate utf8_bin,
  `users_perms` int(11) NOT NULL default '0',
  `id_agent` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cc_card_group`
--

INSERT INTO `cc_card_group` (`id`, `name`, `description`, `users_perms`, `id_agent`) VALUES(1, 'DEFAULT', 'This group is the default group used when you create a customer. It''s forbidden to delete it because you need at least one group but you can edit it.', 129022, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cc_card_history`
--

CREATE TABLE IF NOT EXISTS `cc_card_history` (
  `id` bigint(20) NOT NULL auto_increment,
  `id_cc_card` bigint(20) default NULL,
  `datecreated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `description` text collate utf8_bin,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_card_history`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_card_package_offer`
--

CREATE TABLE IF NOT EXISTS `cc_card_package_offer` (
  `id` bigint(20) NOT NULL auto_increment,
  `id_cc_card` bigint(20) NOT NULL,
  `id_cc_package_offer` bigint(20) NOT NULL,
  `date_consumption` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `used_secondes` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ind_cc_card_package_offer_id_card` (`id_cc_card`),
  KEY `ind_cc_card_package_offer_id_package_offer` (`id_cc_package_offer`),
  KEY `ind_cc_card_package_offer_date_consumption` (`date_consumption`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_card_package_offer`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_card_seria`
--

CREATE TABLE IF NOT EXISTS `cc_card_seria` (
  `id` int(11) NOT NULL auto_increment,
  `name` char(30) collate utf8_bin NOT NULL,
  `description` mediumtext collate utf8_bin,
  `value` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_card_seria`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_card_subscription`
--

CREATE TABLE IF NOT EXISTS `cc_card_subscription` (
  `id` bigint(20) NOT NULL auto_increment,
  `id_cc_card` bigint(20) default NULL,
  `id_subscription_fee` int(11) default NULL,
  `startdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `stopdate` timestamp NOT NULL default '0000-00-00 00:00:00',
  `product_id` varchar(100) collate utf8_bin default NULL,
  `product_name` varchar(100) collate utf8_bin default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_card_subscription`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_charge`
--

CREATE TABLE IF NOT EXISTS `cc_charge` (
  `id` bigint(20) NOT NULL auto_increment,
  `id_cc_card` bigint(20) NOT NULL,
  `iduser` int(11) NOT NULL default '0',
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `amount` float NOT NULL default '0',
  `currency` char(3) collate utf8_bin default 'USD',
  `chargetype` int(11) default '0',
  `description` mediumtext collate utf8_bin,
  `id_cc_did` bigint(20) default '0',
  `id_cc_card_subscription` bigint(20) default NULL,
  `cover_from` date default NULL,
  `cover_to` date default NULL,
  `charged_status` tinyint(4) NOT NULL default '0',
  `invoiced_status` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ind_cc_charge_id_cc_card` (`id_cc_card`),
  KEY `ind_cc_charge_creationdate` (`creationdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_charge`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_config`
--

CREATE TABLE IF NOT EXISTS `cc_config` (
  `id` int(11) NOT NULL auto_increment,
  `config_title` varchar(100) collate utf8_bin NOT NULL,
  `config_key` varchar(100) collate utf8_bin NOT NULL,
  `config_value` varchar(300) collate utf8_bin default NULL,
  `config_description` text collate utf8_bin NOT NULL,
  `config_valuetype` int(11) NOT NULL default '0',
  `config_listvalues` varchar(100) collate utf8_bin default NULL,
  `config_group_title` varchar(64) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=257 ;

--
-- Dumping data for table `cc_config`
--

INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(1, 'Card Number length', 'interval_len_cardnumber', '10-15', 'Card Number length, You can define a Range e.g: 10-15.', 0, '10-15,5-20,10-30', 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(2, 'Card Alias length', 'len_aliasnumber', '15', 'Card Number Alias Length e.g: 15.', 0, NULL, 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(3, 'Voucher length', 'len_voucher', '15', 'Voucher Number Length.', 0, NULL, 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(4, 'Base Currency', 'base_currency', 'usd', 'Base Currency to use for application.', 0, NULL, 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(5, 'Invoice Image', 'invoice_image', 'asterisk01.jpg', 'Image to Display on the Top of Invoice', 0, NULL, 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(6, 'Admin Email', 'admin_email', 'root@localhost', 'Web Administrator Email Address.', 0, NULL, 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(7, 'DID Bill Payment Day', 'didbilling_daytopay', '5', 'DID Bill Payment Day of Month', 0, NULL, 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(8, 'Manager Host', 'manager_host', 'localhost', 'Manager Host Address', 0, NULL, 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(9, 'Manager User ID', 'manager_username', 'myasterisk', 'Manger Host User Name', 0, NULL, 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(10, 'Manager Password', 'manager_secret', 'mycode', 'Manager Host Password', 0, NULL, 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(11, 'Use SMTP Server', 'smtp_server', '0', 'Define if you want to use an STMP server or Send Mail (value yes for server SMTP)', 1, 'yes,no', 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(12, 'SMTP Host', 'smtp_host', 'localhost', 'SMTP Hostname', 0, NULL, 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(13, 'SMTP UserName', 'smtp_username', '', 'User Name to connect on the SMTP server', 0, NULL, 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(14, 'SMTP Password', 'smtp_password', '', 'Password to connect on the SMTP server', 0, NULL, 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(15, 'Use Realtime', 'use_realtime', '1', 'if Disabled, it will generate the config files and offer an option to reload asterisk after an update on the Voip settings', 1, 'yes,no', 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(16, 'Go To Customer', 'customer_ui_url', '../../customer/index.php', 'Link to the customer account', 0, NULL, 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(17, 'Context Callback', 'context_callback', 'a2billing-callback', 'Contaxt to use in Callback', 0, NULL, 'callback');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(18, 'Extension', 'extension', '1000', 'Extension to call while callback.', 0, NULL, 'callback');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(19, 'Wait before callback', 'sec_wait_before_callback', '10', 'Seconds to wait before callback.', 0, NULL, 'callback');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(20, 'Avoid Repeat Duration', 'sec_avoid_repeate', '10', 'Number of seconds before the call-back can be re-initiated from the web page to prevent repeated and unwanted calls.', 0, NULL, 'callback');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(21, 'Time out', 'timeout', '20', 'if the callback doesnt succeed within the value below, then the call is deemed to have failed.', 0, NULL, 'callback');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(22, 'Answer on Call', 'answer_call', '1', 'if we want to manage the answer on the call. Disabling this for callback trigger numbers makes it ring not hang up.', 1, 'yes,no', 'callback');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(23, 'No of Predictive Calls', 'nb_predictive_call', '10', 'number of calls an agent will do when the call button is clicked.', 0, NULL, 'callback');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(24, 'Delay for Availability', 'nb_day_wait_before_retry', '1', 'Number of days to wait before the number becomes available to call again.', 0, NULL, 'callback');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(25, 'PD Contect', 'context_preditctivedialer', 'a2billing-predictivedialer', 'The context to redirect the call for the predictive dialer.', 0, NULL, 'callback');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(26, 'Max Time to call', 'predictivedialer_maxtime_tocall', '5400', 'When a call is made we need to limit the call duration : amount in seconds.', 0, NULL, 'callback');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(27, 'PD Caller ID', 'callerid', '123456', 'Set the callerID for the predictive dialer and call-back.', 0, NULL, 'callback');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(28, 'Callback CallPlan ID', 'all_callback_tariff', '1', 'ID Call Plan to use when you use the all-callback mode, check the ID in the "list Call Plan" - WebUI.', 0, NULL, 'callback');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(29, 'Server Group ID', 'id_server_group', '1', 'Define the group of servers that are going to be used by the callback.', 0, NULL, 'callback');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(30, 'Audio Intro', 'callback_audio_intro', 'prepaid-callback_intro', 'Audio intro message when the callback is initiate.', 0, NULL, 'callback');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(31, 'Signup URL', 'signup_page_url', '', 'url of the signup page to show up on the sign in page (if empty no link will show up).', 0, NULL, 'webcustomerui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(32, 'Payment Method', 'paymentmethod', '1', 'Enable or disable the payment methods; yes for multi-payment or no for single payment method option.', 1, 'yes,no', 'webcustomerui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(33, 'Personal Info', 'personalinfo', '1', 'Enable or disable the page which allow customer to modify its personal information.', 1, 'yes,no', 'webcustomerui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(34, 'Payment Info', 'customerinfo', '1', 'Enable display of the payment interface - yes or no.', 1, 'yes,no', 'webcustomerui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(256, 'SMTP Secure', 'smtp_secure', '', 'sets the prefix to the SMTP server : tls ; ssl', 0, NULL, 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(255, 'SMTP Port', 'smtp_port', '25', 'Port to connect on the SMTP server', 0, NULL, 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(254, 'Return URL distant Forget Password', 'return_url_distant_forgetpassword', '', 'URL for specific return if an error occur after forgetpassword', 0, NULL, 'webcustomerui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(47, 'WebPhone Server', 'webphoneserver', 'localhost', 'IP address or domain name of asterisk server that would be used by the web-phone.', 0, NULL, 'webcustomerui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(49, 'Password', 'password', '1', 'Let the user change the webui password.', 1, 'yes,no', 'webcustomerui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(50, 'CallerID Limit', 'limit_callerid', '5', 'The total number of callerIDs for CLI Recognition that can be add by the customer.', 0, NULL, 'webcustomerui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(51, 'Trunk Name', 'sip_iax_info_trunkname', 'YourDomain', 'Trunk Name to show in sip/iax info.', 0, NULL, 'sip-iax-info');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(52, 'Codecs Allowed', 'sip_iax_info_allowcodec', 'g729', 'Allowed Codec, ulaw, gsm, g729.', 0, NULL, 'sip-iax-info');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(53, 'Host', 'sip_iax_info_host', 'YourDomain.com', 'Host information.', 0, NULL, 'sip-iax-info');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(54, 'IAX Parms', 'iax_additional_parameters', 'canreinvite = no', 'IAX Additional Parameters.', 0, NULL, 'sip-iax-info');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(55, 'SIP Parms', 'sip_additional_parameters', 'trustrpid = yes | sendrpid = yes | canreinvite = no', 'SIP Additional Parameters.', 0, NULL, 'sip-iax-info');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(56, 'Enable', 'enable', '1', 'Enable/Disable.', 1, 'yes,no', 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(57, 'HTTP Server Customer', 'http_server', 'http://www.YourDomain.com', 'Set the Server Address of Customer Website, It should be empty for productive Servers.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(58, 'HTTPS Server Customer', 'https_server', 'https://www.YourDomain.com', 'https://localhost - Enter here your Secure Customers Server Address, should not be empty for productive servers.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(59, 'Server Customer IP/Domain', 'http_cookie_domain', '26.63.165.200', 'Enter your Domain Name or IP Address for the Customers application, eg, 26.63.165.200.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(60, 'Secure Server Customer IP/Domain', 'https_cookie_domain', '26.63.165.200', 'Enter your Secure server Domain Name or IP Address for the Customers application, eg, 26.63.165.200.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(61, 'Application Customer Path', 'http_cookie_path', '/customer/', 'Enter the Physical path of your Customers Application on your server.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(62, 'Secure Application Customer Path', 'https_cookie_path', '/customer/', 'Enter the Physical path of your Customers Application on your Secure Server.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(63, 'Application Customer Physical Path', 'dir_ws_http_catalog', '/customer/', 'Enter the Physical path of your Customers Application on your server.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(64, 'Secure Application Customer Physical Path', 'dir_ws_https_catalog', '/customer/', 'Enter the Physical path of your Customers Application on your Secure server.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(65, 'Enable SSL', 'enable_ssl', '1', 'secure webserver for checkout procedure?', 1, 'yes,no', 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(66, 'HTTP Domain', 'http_domain', '26.63.165.200', 'Http Address.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(67, 'Directory Path', 'dir_ws_http', '/customer/', 'Directory Path.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(68, 'Payment Amount', 'purchase_amount', '1:2:5:10:20', 'define the different amount of purchase that would be available - 5 amount maximum (5:10:15).', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(69, 'Item Name', 'item_name', 'Credit Purchase', 'Item name that would be display to the user when he will buy credit.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(70, 'Currency Code', 'currency_code', 'USD', 'Currency for the Credit purchase, only one can be define here.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(71, 'Paypal Payment URL', 'paypal_payment_url', 'https://secure.paypal.com/cgi-bin/webscr', 'Define here the URL of paypal gateway the payment (to test with paypal sandbox).', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(72, 'Paypal Verify URL', 'paypal_verify_url', 'ssl://www.paypal.com', 'paypal transaction verification url.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(73, 'Authorize.NET Payment URL', 'authorize_payment_url', 'https://secure.authorize.net/gateway/transact.dll', 'Define here the URL of Authorize gateway.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(74, 'PayPal Store Name', 'store_name', 'Asterisk2Billing', 'paypal store name to show in the paypal site when customer will go to pay.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(75, 'Transaction Key', 'transaction_key', 'asdf1212fasd121554sd4f5s45sdf', 'Transaction Key for security of Epayment Max length of 60 Characters.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(76, 'Secret Word', 'moneybookers_secretword', '', 'Moneybookers secret word.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(77, 'Enable', 'enable_signup', '0', 'Enable Signup Module.', 1, 'yes,no', 'signup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(78, 'Captcha Security', 'enable_captcha', '1', 'enable Captcha on the signup module (value : YES or NO).', 1, 'yes,no', 'signup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(79, 'Credit', 'credit', '0', 'amount of credit applied to a new user.', 0, NULL, 'signup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(80, 'CallPlan ID List', 'callplan_id_list', '1,2', 'the list of id of call plans which will be shown in signup.', 0, NULL, 'signup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(81, 'Card Activation', 'activated', '0', 'Specify whether the card is created as active or pending.', 1, 'yes,no', 'signup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(82, 'Access Type', 'simultaccess', '0', 'Simultaneous or non concurrent access with the card - 0 = INDIVIDUAL ACCESS or 1 = SIMULTANEOUS ACCESS.', 0, NULL, 'signup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(83, 'Paid Type', 'typepaid', '0', 'PREPAID CARD  =  0 - POSTPAY CARD  =  1.', 0, NULL, 'signup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(84, 'Credit Limit', 'creditlimit', '0', 'Define credit limit, which is only used for a POSTPAY card.', 0, NULL, 'signup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(85, 'Run Service', 'runservice', '0', 'Authorise the recurring service to apply on this card  -  Yes 1 - No 0.', 0, NULL, 'signup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(86, 'Enable Expire', 'enableexpire', '0', 'Enable the expiry of the card  -  Yes 1 - No 0.', 0, NULL, 'signup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(87, 'Date Format', 'expirationdate', '', 'Expiry Date format YYYY-MM-DD HH:MM:SS. For instance 2004-12-31 00:00:00', 0, NULL, 'signup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(88, 'Expire Limit', 'expiredays', '0', 'The number of days after which the card will expire.', 0, NULL, 'signup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(89, 'Create SIP', 'sip_account', '1', 'Create a sip account from signup ( default : yes ).', 1, 'yes,no', 'signup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(90, 'Create IAX', 'iax_account', '1', 'Create an iax account from signup ( default : yes ).', 1, 'yes,no', 'signup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(91, 'Activate Card', 'activatedbyuser', '0', 'active card after the new signup. if No, the Signup confirmation is needed and an email will be sent to the user with a link for activation (need to put the link into the Signup mail template).', 1, 'yes,no', 'signup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(92, 'Customer Interface URL', 'urlcustomerinterface', 'http://localhost/customer/', 'url of the customer interface to display after activation.', 0, NULL, 'signup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(93, 'Asterisk Reload', 'reload_asterisk_if_sipiax_created', '0', 'Define if you want to reload Asterisk when a SIP / IAX Friend is created at signup time.', 1, 'yes,no', 'signup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(94, 'Backup Path', 'backup_path', '/tmp', 'Path to store backup of database.', 0, NULL, 'backup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(95, 'GZIP Path', 'gzip_exe', '/bin/gzip', 'Path for gzip.', 0, NULL, 'backup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(96, 'GunZip Path', 'gunzip_exe', '/bin/gunzip', 'Path for gunzip .', 0, NULL, 'backup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(97, 'MySql Dump Path', 'mysqldump', '/usr/bin/mysqldump', 'path for mysqldump.', 0, NULL, 'backup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(98, 'PGSql Dump Path', 'pg_dump', '/usr/bin/pg_dump', 'path for pg_dump.', 0, NULL, 'backup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(99, 'MySql Path', 'mysql', '/usr/bin/mysql', 'Path for MySql.', 0, NULL, 'backup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(100, 'PSql Path', 'psql', '/usr/bin/psql', 'Path for PSql.', 0, NULL, 'backup');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(101, 'SIP File Path', 'buddy_sip_file', '/etc/asterisk/additional_a2billing_sip.conf', 'Path to store the asterisk configuration files SIP.', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(102, 'IAX File Path', 'buddy_iax_file', '/etc/asterisk/additional_a2billing_iax.conf', 'Path to store the asterisk configuration files IAX.', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(103, 'API Security Key', 'api_security_key', 'Ae87v56zzl34v', 'API have a security key to validate the http request, the key has to be sent after applying md5, Valid characters are [a-z,A-Z,0-9].', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(104, 'Authorized IP', 'api_ip_auth', '127.0.0.1', 'API to restrict the IPs authorised to make a request, Define The the list of ips separated by '';''.', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(105, 'Admin Email', 'email_admin', 'root@localhost', 'Administative Email.', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(106, 'MOH Directory', 'dir_store_mohmp3', '/var/lib/asterisk/mohmp3', 'MOH (Music on Hold) base directory.', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(107, 'MOH Classes', 'num_musiconhold_class', '10', 'Number of MOH classes you have created in musiconhold.conf : acc_1, acc_2... acc_10 class	etc....', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(108, 'Display Help', 'show_help', '1', 'Display the help section inside the admin interface  (YES - NO).', 1, 'yes,no', 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(109, 'Max File Upload Size', 'my_max_file_size_import', '1024000', 'File Upload parameters, PLEASE CHECK ALSO THE VALUE IN YOUR PHP.INI THE LIMIT IS 2MG BY DEFAULT .', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(110, 'Audio Directory Path', 'dir_store_audio', '/var/lib/asterisk/sounds/a2billing', 'Not used yet, The goal is to upload files and use them in the IVR.', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(111, 'Max Audio File Size', 'my_max_file_size_audio', '3072000', 'upload maximum file size.', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(112, 'Extensions Allowed', 'file_ext_allow', 'gsm, mp3, wav', 'File type extensions permitted to be uploaded such as "gsm, mp3, wav" (separated by ,).', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(113, 'Muzic Files Allowed', 'file_ext_allow_musiconhold', 'mp3', 'File type extensions permitted to be uploaded for the musiconhold such as "gsm, mp3, wav" (separate by ,).', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(114, 'Link Audio', 'link_audio_file', '0', 'Enable link on the CDR viewer to the recordings. (YES - NO).', 1, 'yes,no', 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(115, 'Monitor Path', 'monitor_path', '/var/spool/asterisk/monitor', 'Path to link the recorded monitor files.', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(116, 'Monitor Format', 'monitor_formatfile', 'gsm', 'FORMAT OF THE RECORDED MONITOR FILE.', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(117, 'Invoice Icon', 'show_icon_invoice', '1', 'Display the icon in the invoice.', 1, 'yes,no', 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(118, 'Show Top Frame', 'show_top_frame', '0', 'Display the top frame (useful if you want to save space on your little tiny screen ) .', 1, 'yes,no', 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(119, 'Currency', 'currency_choose', 'usd, eur, cad, hkd', 'Allow the customer to chose the most appropriate currency ("all" can be used).', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(120, 'Card Export Fields', 'card_export_field_list', 'card.id, username, useralias, lastname, credit, tariff, activated, language, inuse, currency, sip_buddy', 'Fields to export in csv format from cc_card table.', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(121, 'Vouvher Export Fields', 'voucher_export_field_list', 'voucher, credit, tag, activated, usedcardnumber, usedate, currency', 'Field to export in csv format from cc_voucher table.', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(122, 'Advance Mode', 'advanced_mode', '0', 'Advanced mode - Display additional configuration options on the ratecard (progressive rates, musiconhold, ...).', 1, 'yes,no', 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(123, 'SIP/IAX Delete', 'delete_fk_card', '1', 'Delete the SIP/IAX Friend & callerid when a card is deleted.', 1, 'yes,no', 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(124, 'Type', 'type', 'friend', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, NULL, 'peer_friend');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(125, 'Allow', 'allow', 'ulaw,alaw,gsm,g729', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, NULL, 'peer_friend');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(126, 'Context', 'context', 'a2billing', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, NULL, 'peer_friend');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(127, 'Nat', 'nat', 'yes', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, NULL, 'peer_friend');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(128, 'AMA Flag', 'amaflag', 'billing', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, NULL, 'peer_friend');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(129, 'Qualify', 'qualify', 'yes', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, NULL, 'peer_friend');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(130, 'Host', 'host', 'dynamic', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, NULL, 'peer_friend');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(131, 'DTMF Mode', 'dtmfmode', 'RFC2833', 'Refer to sip.conf & iax.conf documentation for the meaning of those parameters.', 0, NULL, 'peer_friend');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(132, 'Alarm Log File', 'cront_alarm', '/var/log/a2billing/cront_a2b_alarm.log', 'To disable application logging, remove/comment the log file name aside service.', 0, NULL, 'log-files');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(133, 'Auto refill Log File', 'cront_autorefill', '/var/log/a2billing/cront_a2b_autorefill.log', 'To disable application logging, remove/comment the log file name aside service.', 0, NULL, 'log-files');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(134, 'Bactch Process Log File', 'cront_batch_process', '/var/log/a2billing/cront_a2b_batch_process.log', 'To disable application logging, remove/comment the log file name aside service .', 0, NULL, 'log-files');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(135, 'Archive Log File', 'cront_archive_data', '/var/log/a2billing/cront_a2b_archive_data.log', 'To disable application logging, remove/comment the log file name aside service .', 0, NULL, 'log-files');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(136, 'DID Billing Log File', 'cront_bill_diduse', '/var/log/a2billing/cront_a2b_bill_diduse.log', 'To disable application logging, remove/comment the log file name aside service .', 0, NULL, 'log-files');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(137, 'Subscription Fee Log File', 'cront_subscriptionfee', '/var/log/a2billing/cront_a2b_subscription_fee.log', 'To disable application logging, remove/comment the log file name aside service.', 0, NULL, 'log-files');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(138, 'Currency Cront Log File', 'cront_currency_update', '/var/log/a2billing/cront_a2b_currency_update.log', 'To disable application logging, remove/comment the log file name aside service.', 0, NULL, 'log-files');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(139, 'Invoice Cront Log File', 'cront_invoice', '/var/log/a2billing/cront_a2b_invoice.log', 'To disable application logging, remove/comment the log file name aside service.', 0, NULL, 'log-files');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(140, 'Cornt Log File', 'cront_check_account', '/var/log/a2billing/cront_a2b_check_account.log', 'To disable application logging, remove/comment the log file name aside service .', 0, NULL, 'log-files');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(141, 'Paypal Log File', 'paypal', '/var/log/a2billing/a2billing_paypal.log', 'paypal log file, to log all the transaction & error.', 0, NULL, 'log-files');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(142, 'EPayment Log File', 'epayment', '/var/log/a2billing/a2billing_epayment.log', 'epayment log file, to log all the transaction & error .', 0, NULL, 'log-files');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(143, 'ECommerce Log File', 'api_ecommerce', '/var/log/a2billing/a2billing_api_ecommerce_request.log', 'Log file to store the ecommerce API requests .', 0, NULL, 'log-files');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(144, 'Callback Log File', 'api_callback', '/var/log/a2billing/a2billing_api_callback_request.log', 'Log file to store the CallBack API requests.', 0, NULL, 'log-files');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(145, 'Webservice Card Log File', 'api_card', '/var/log/a2billing/a2billing_api_card.log', 'Log file to store the Card Webservice Logs', 0, NULL, 'log-files');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(146, 'AGI Log File', 'agi', '/var/log/a2billing/a2billing_agi.log', 'File to log.', 0, NULL, 'log-files');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(147, 'Description', 'description', 'agi-config', 'Description/notes field', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(148, 'Asterisk Version', 'asterisk_version', '1_4', 'Asterisk Version Information, 1_1,1_2,1_4 By Default it will take 1_2 or higher .', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(149, 'Answer Call', 'answer_call', '1', 'Manage the answer on the call. Disabling this for callback trigger numbers makes it ring not hang up.', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(150, 'Play Audio', 'play_audio', '1', 'Play audio - this will disable all stream file but not the Get Data , for wholesale ensure that the authentication works and than number_try = 1.', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(151, 'Say GoodBye', 'say_goodbye', '0', 'play the goodbye message when the user has finished.', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(152, 'Play Language Menu', 'play_menulanguage', '0', 'enable the menu to choose the language, press 1 for English, pulsa 2 para el espaÃ±ol, Pressez 3 pour FranÃ§ais', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(153, 'Force Language', 'force_language', '', 'force the use of a language, if you dont want to use it leave the option empty, Values : ES, EN, FR, etc... (according to the audio you have installed).', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(154, 'Intro Prompt', 'intro_prompt', '', 'Introduction prompt : to specify an additional prompt to play at the beginning of the application .', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(155, 'Min Call Credit', 'min_credit_2call', '0', 'Minimum amount of credit to use the application .', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(156, 'Min Bill Duration', 'min_duration_2bill', '0', 'this is the minimum duration in seconds of a call in order to be billed any call with a length less than min_duration_2bill will have a 0 cost useful not to charge callers for system errors when a call was answered but it actually didn''t connect.', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(157, 'Not Enough Credit', 'notenoughcredit_cardnumber', '0', 'if user doesn''t have enough credit to call a destination, prompt him to enter another cardnumber .', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(158, 'New Caller ID', 'notenoughcredit_assign_newcardnumber_cid', '0', 'if notenoughcredit_cardnumber = YES  then	assign the CallerID to the new cardnumber.', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(159, 'Use DNID', 'use_dnid', '0', 'if YES it will use the DNID and try to dial out, without asking for the phonenumber to call.', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(160, 'Not Use DNID', 'no_auth_dnid', '2400,2300', 'list the dnid on which you want to avoid the use of the previous option "use_dnid" .', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(161, 'Try Count', 'number_try', '3', 'number of times the user can dial different number.', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(162, 'Force CallPlan', 'force_callplan_id', '', 'this will force to select a specific call plan by the Rate Engine.', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(163, 'Say Balance After Auth', 'say_balance_after_auth', '1', 'Play the balance to the user after the authentication (values : yes - no).', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(164, 'Say Balance After Call', 'say_balance_after_call', '0', 'Play the balance to the user after the call (values : yes - no).', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(165, 'Say Rate', 'say_rateinitial', '0', 'Play the initial cost of the route (values : yes - no)', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(166, 'Say Duration', 'say_timetocall', '1', 'Play the amount of time that the user can call (values : yes - no).', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(167, 'Auto Set CLID', 'auto_setcallerid', '1', 'enable the setup of the callerID number before the outbound is made, by default the user callerID value will be use.', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(168, 'Force CLID', 'force_callerid', '', 'If auto_setcallerid is enabled, the value of force_callerid will be set as CallerID.', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(169, 'CLID Sanitize', 'cid_sanitize', '0', 'If force_callerid is not set, then the following option ensures that CID is set to one of the card''s configured caller IDs or blank if none available.(NO - disable this feature, caller ID can be anything, CID - Caller ID must be one of the customers caller IDs, DID - Caller ID must be one of the customers DID nos, BOTH - Caller ID must be one of the above two items)', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(170, 'CLID Enable', 'cid_enable', '0', 'enable the callerid authentication if this option is active the CC system will check the CID of caller  .', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(171, 'Ask PIN', 'cid_askpincode_ifnot_callerid', '1', 'if the CID does not exist, then the caller will be prompt to enter his cardnumber .', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(172, 'FailOver LCR/LCD Prefix', 'failover_lc_prefix', '0', 'if we will failover for LCR/LCD prefix. For instance if you have 346 and 34 for if 346 fail it will try to outbound with 34 route.', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(173, 'Auto CLID', 'cid_auto_assign_card_to_cid', '1', 'if the callerID authentication is enable and the authentication fails then the user will be prompt to enter his cardnumber;this option will bound the cardnumber entered to the current callerID so that next call will be directly authenticate.', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(174, 'Auto Create Card', 'cid_auto_create_card', '0', 'if the callerID is captured on a2billing, this option will create automatically a new card and add the callerID to it.', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(175, 'Auto Create Card Length', 'cid_auto_create_card_len', '10', 'set the length of the card that will be auto create (ie, 10).', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(176, 'Auto Create Card Type', 'cid_auto_create_card_typepaid', 'POSTPAY', 'billing type of the new card( value : POSTPAY or PREPAY) .', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(177, 'Auto Create Card Credit', 'cid_auto_create_card_credit', '0', 'amount of credit of the new card.', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(178, 'Auto Create Card Limit', 'cid_auto_create_card_credit_limit', '1000', 'if postpay, define the credit limit for the card.', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(179, 'Auto Create Card TariffGroup', 'cid_auto_create_card_tariffgroup', '6', 'the tariffgroup to use for the new card (this is the ID that you can find on the admin web interface) .', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(180, 'Auto CLID Security', 'callerid_authentication_over_cardnumber', '0', 'to check callerID over the cardnumber authentication (to guard against spoofing).', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(181, 'SIP Call', 'sip_iax_friends', '0', 'enable the option to call sip/iax friend for free (values : YES - NO).', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(182, 'SIP Call Prefix', 'sip_iax_pstn_direct_call_prefix', '555', 'if SIP_IAX_FRIENDS is active, you can define a prefix for the dialed digits to call a pstn number .', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(183, 'Direct Call', 'sip_iax_pstn_direct_call', '0', 'this will enable a prompt to enter your destination number. if number start by sip_iax_pstn_direct_call_prefix we do directly a sip iax call, if not we do a normal call.', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(184, 'IVR Voucher Refill', 'ivr_voucher', '0', 'enable the option to refill card with voucher in IVR (values : YES - NO) .', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(185, 'IVR Voucher Prefix', 'ivr_voucher_prefix', '8', 'if ivr_voucher is active, you can define a prefix for the voucher number to refill your card, values : number - don''t forget to change prepaid-refill_card_with_voucher audio accordingly .', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(186, 'IVR Low Credit', 'jump_voucher_if_min_credit', '0', 'When the user credit are below the minimum credit to call min_credit jump directly to the voucher IVR menu  (values: YES - NO) .', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(187, 'Dial Command Params', 'dialcommand_param', '|60|HRrL(%timeout%:61000:30000)', 'More information about the Dial : http://voip-info.org/wiki-Asterisk+cmd+dial<br>30 :  The timeout parameter is optional. If not specifed, the Dial command will wait indefinitely, exiting only when the originating channel hangs up, or all the dialed channels return a busy or error condition. Otherwise it specifies a maximum time, in seconds, that the Dial command is to wait for a channel to answer.<br>H: Allow the caller to hang up by dialing * <br>r: Generate a ringing tone for the calling party<br>R: Indicate ringing to the calling party when the called party indicates ringing, pass no audio until answered.<br>g: When the called party hangs up, exit to execute more commands in the current context. (new in 1.4)<br>i: Asterisk will ignore any forwarding (302 Redirect) requests received. Essential for DID usage to prevent fraud. (new in 1.4)<br>m: Provide Music on Hold to the calling party until the called channel answers.<br>L(x[:y][:z]): Limit the call to ''x'' ms, warning when ''y'' ms are left, repeated every ''z'' ms)<br>%timeout% tag is replaced by the calculated timeout according the credit & destination rate!.', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(188, 'SIP/IAX Dial Command Params', 'dialcommand_param_sipiax_friend', '|60|HiL(3600000:61000:30000)', 'by default (3600000  =  1HOUR MAX CALL).', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(189, 'Outbound Call', 'switchdialcommand', '0', 'Define the order to make the outbound call<br>YES -> SIP/dialedphonenumber@gateway_ip - NO  SIP/gateway_ip/dialedphonenumber<br>Both should work exactly the same but i experimented one case when gateway was supporting dialedphonenumber@gateway_ip, So in case of trouble, try it out.', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(190, 'Failover Retry Limit', 'failover_recursive_limit', '2', 'failover recursive search - define how many time we want to authorize the research of the failover trunk when a call fails (value : 0 - 20) .', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(191, 'Max Time', 'maxtime_tocall_negatif_free_route', '5400', 'This setting specifies an upper limit for the duration of a call to a destination for which the selling rate is less than or equal to 0.', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(192, 'Send Reminder', 'send_reminder', '0', 'Send a reminder email to the user when they are under min_credit_2call.', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(193, 'Record Call', 'record_call', '0', 'enable to monitor the call (to record all the conversations) value : YES - NO .', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(194, 'Monitor File Format', 'monitor_formatfile', 'gsm', 'format of the recorded monitor file.', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(195, 'AGI Force Currency', 'agi_force_currency', '', 'Force to play the balance to the caller in a predefined currency, to use the currency set for by the customer leave this field empty.', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(196, 'Currency Associated', 'currency_association', 'usd:dollars,mxn:pesos,eur:euros,all:credit', 'Define all the audio (without file extensions) that you want to play according to currency (use , to separate, ie "usd:prepaid-dollar,mxn:pesos,eur:Euro,all:credit").', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(197, 'Minor Currency Associated', 'currency_association_minor', 'usd:prepaid-cents,eur:prepaid-cents,gbp:prepaid-pence,all:credit', 'Define all the audio (without file extensions) that you want to play according to minor currency (use , to separate, ie "usd:prepaid-cents,eur:prepaid-cents,gbp:prepaid-pence,all:credit").', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(198, 'File Enter Destination', 'file_conf_enter_destination', 'prepaid-enter-dest', 'Please enter the file name you want to play when we prompt the calling party to enter the destination number, file_conf_enter_destination = prepaid-enter-number-u-calling-1-or-011.', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(219, 'Menu Language Order', 'conf_order_menulang', 'en:fr:es', 'Enter the list of languages authorized for the menu.Use the code language separate by a colon charactere e.g: en:es:fr', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(200, 'Bill Callback', 'callback_bill_1stleg_ifcall_notconnected', '1', 'Define if you want to bill the 1st leg on callback even if the call is not connected to the destination.', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(201, 'International prefixes', 'international_prefixes', '011,00,09,1', 'List the prefixes you want stripped off if the call plan requires it', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(202, 'Server GMT', 'server_GMT', 'GMT+10:00', 'Define the sever gmt time', 0, NULL, 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(203, 'Invoice Template Path', 'invoice_template_path', '../invoice/', 'gives invoice template path from default one', 0, NULL, 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(204, 'Outstanding Template Path', 'outstanding_template_path', '../outstanding/', 'gives outstanding template path from default one', 0, NULL, 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(205, 'Sales Template Path', 'sales_template_path', '../sales/', 'gives sales template path from default one', 0, NULL, 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(206, 'Extra charge DIDs', 'extracharge_did', '1800,1900', 'Add extra per-minute charges to this comma-separated list of DNIDs; needs "extracharge_fee" and "extracharge_buyfee"', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(207, 'Extra charge DID fees', 'extracharge_fee', '0,0', 'Comma-separated list of extra sell-rate charges corresponding to the DIDs in "extracharge_did" - ie : 0.08,0.18', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(208, 'Extra charge DID buy fees', 'extracharge_buyfee', '0,0', 'Comma-separated list of extra buy-rate charges corresponding to the DIDs in "extracharge_did" - ie : 0.04,0.13', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(253, 'Return URL distant Login', 'return_url_distant_login', '', 'URL for specific return if an error occur after login', 0, NULL, 'webcustomerui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(210, 'List of possible values to notify', 'values_notifications', '10:20:50:100:500:1000', 'Possible values to choose when the user receive a notification. You can define a List e.g: 10:20:100.', 0, NULL, 'notifications');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(211, 'Notifications Modules', 'notification', '1', 'Enable or Disable the module of notification for the customers', 1, 'yes,no', 'webcustomerui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(212, 'Notications Cron Module', 'cron_notifications', '1', 'Enable or Disable the cron module of notification for the customers. If it correctly configured in the crontab', 0, 'yes,no', 'notifications');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(213, 'Notications Delay', 'delay_notifications', '1', 'Delay in number of days to send an other notification for the customers. If the value is 0, it will notify the user everytime the cront is running.', 0, NULL, 'notifications');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(214, 'Payment Amount', 'purchase_amount_agent', '100:200:500:1000', 'define the different amount of purchase that would be available.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(215, 'Max Time For Unlimited Calls', 'maxtime_tounlimited_calls', '5400', 'For unlimited calls, limit the duration: amount in seconds .', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(216, 'Max Time For Free Calls', 'maxtime_tofree_calls', '5400', 'For free calls, limit the duration: amount in seconds .', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(217, 'CallPlan threshold Deck switch', 'callplan_deck_minute_threshold', '', 'CallPlan threshold Deck switch. <br/>This option will switch the user callplan from one call plan ID to and other Callplan ID\nThe parameters are as follow : <br/>\n-- ID of the first callplan : called seconds needed to switch to the next CallplanID <br/>\n-- ID of the second callplan : called seconds needed to switch to the next CallplanID <br/>\n-- if not needed seconds are defined it will automatically switch to the next one <br/>\n-- if defined we will sum the previous needed seconds and check if the caller had done at least the amount of calls necessary to go to the next step and have the amount of seconds needed<br/>\nvalue example for callplan_deck_minute_threshold = 1:300, 2:60, 3', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(252, 'Personal Info', 'personalinfo', '1', 'Enable or disable the page which allow agent to modify its personal information.', 0, 'yes,no', 'webagentui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(220, 'Disable annoucement the second of the times that the card can call', 'disable_announcement_seconds', '0', 'Desactived the annoucement of the seconds when there are more of one minutes (values : yes - no)', 1, 'yes,no', 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(221, 'Charge for the paypal extra fees', 'charge_paypal_fee', '0', 'Actived, if you want assum the fee of paypal and don''t apply it on the customer (values : yes - no)', 1, 'yes,no', 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(222, 'Cents Currency Associated', 'currency_cents_association', '', 'Define all the audio (without file extensions) that you want to play according to cents currency (use , to separate, ie "amd:lumas").By default the file used is "prepaid-cents" .Use plural to define the cents currency sound, but import two sounds but cents currency defined : ending by ''s'' and not ending by ''s'' (i.e. for lumas , add 2 files : ''lumas'' and ''luma'') ', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(223, 'Context Campaign''s Callback', 'context_campaign_callback', 'a2billing-campaign-callback', 'Context to use in Campaign of Callback', 0, NULL, 'callback');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(224, 'Default Context forward Campaign''s Callback ', 'default_context_campaign', 'campaign', 'Context to use by default to forward the call in Campaign of Callback', 0, NULL, 'callback');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(225, 'Card Show Fields', 'card_show_field_list', 'id:,username:, useralias:, lastname:,id_group:, id_agent:,  credit:, tariff:, status:, language:, inuse:, currency:, sip_buddy:, iax_buddy:, nbused:,', 'Fields to show in Customer. Order is important. You can setup size of field using "fieldname:10%" notation or "fieldname:" for harcoded size,"fieldname" for autosize. <br/>You can use:<br/> id,username, useralias, lastname, id_group, id_agent,  credit, tariff, status, language, inuse, currency, sip_buddy, iax_buddy, nbused, firstname, email, discount, callerid, id_seria, serial', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(226, 'Enable CDR local cache', 'cache_enabled', '0', 'If you want enabled the local cache to save the CDR in a SQLite Database.', 1, 'yes,no', 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(227, 'Path for the CDR cache file', 'cache_path', '/etc/asterisk/cache_a2billing', 'Defined the file that you want use for the CDR cache to save the CDR in a local SQLite database.', 0, NULL, 'global');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(228, 'PNL Pay Phones', 'report_pnl_pay_phones', '(8887798764,0.02,0.06)', 'Info for PNL report. Must be in form "(number1,buycost,sellcost),(number2,buycost,sellcost)", number can be prefix, i.e 1800', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(229, 'PNL Toll Free Numbers', 'report_pnl_toll_free', '(6136864646,0.1,0),(6477249717,0.1,0)', 'Info for PNL report. must be in form "(number1,buycost,sellcost),(number2,buycost,sellcost)", number can be prefix, i.e 1800', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(230, 'Verbosity', 'verbosity_level', '0', '0 = FATAL; 1 = ERROR; WARN = 2 ; INFO = 3 ; DEBUG = 4', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(231, 'Logging', 'logging_level', '3', '0 = FATAL; 1 = ERROR; WARN = 2 ; INFO = 3 ; DEBUG = 4', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(232, 'Enable info module about customers', 'customer_info_enabled', 'LEFT', 'If you want enabled the info module customer and place it somewhere on the home page.', 0, 'NONE,LEFT,CENTER,RIGHT', 'dashboard');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(233, 'Enable info module about refills', 'refill_info_enabled', 'CENTER', 'If you want enabled the info module refills and place it somewhere on the home page.', 0, 'NONE,LEFT,CENTER,RIGHT', 'dashboard');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(234, 'Enable info module about payments', 'payment_info_enabled', 'CENTER', 'If you want enabled the info module payments and place it somewhere on the home page.', 0, 'NONE,LEFT,CENTER,RIGHT', 'dashboard');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(235, 'Enable info module about calls', 'call_info_enabled', 'RIGHT', 'If you want enabled the info module calls and place it somewhere on the home page.', 0, 'NONE,LEFT,CENTER,RIGHT', 'dashboard');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(236, 'PlugnPay Payment URL', 'plugnpay_payment_url', 'https://pay1.plugnpay.com/payment/pnpremote.cgi', 'Define here the URL of PlugnPay gateway.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(237, 'DIDX ID', 'didx_id', '708XXX', 'DIDX parameter : ID', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(238, 'DIDX PASS', 'didx_pass', 'XXXXXXXXXX', 'DIDX parameter : Password', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(239, 'DIDX MIN RATING', 'didx_min_rating', '0', 'DIDX parameter : min rating', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(240, 'DIDX RING TO', 'didx_ring_to', '0', 'DIDX parameter : ring to', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(241, 'Card Serial Pad Length', 'card_serial_length', '7', 'Value of zero padding for serial. If this value set to 3 serial wil looks like 001', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(242, 'Dial Balance reservation', 'dial_balance_reservation', '0.25', 'Credit to reserve from the balance when a call is made. This will prevent negative balance on huge peak.', 0, NULL, 'agi-conf1');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(243, 'Rate Export Fields', 'rate_export_field_list', 'destination, dialprefix, rateinitial', 'Fields to export in csv format from rates table.Use dest_name from prefix name', 0, NULL, 'webui');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(244, 'HTTP Server Agent', 'http_server_agent', 'http://www.YourDomain.com', 'Set the Server Address of Agent Website, It should be empty for productive Servers.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(245, 'HTTPS Server Agent', 'https_server_agent', 'https://www.YourDomain.com', 'https://localhost - Enter here your Secure Agents Server Address, should not be empty for productive servers.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(246, 'Server Agent IP/Domain', 'http_cookie_domain_agent', '26.63.165.200', 'Enter your Domain Name or IP Address for the Agents application, eg, 26.63.165.200.', 0, NULL, '5');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(247, 'Secure Server Agent IP/Domain', 'https_cookie_domain_agent', '26.63.165.200', 'Enter your Secure server Domain Name or IP Address for the Agents application, eg, 26.63.165.200.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(248, 'Application Agent Path', 'http_cookie_path_agent', '/agent/Public/', 'Enter the Physical path of your Agents Application on your server.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(249, 'Secure Application Agent Path', 'https_cookie_path_agent', '/agent/Public/', 'Enter the Physical path of your Agents Application on your Secure Server.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(250, 'Application Agent Physical Path', 'dir_ws_http_catalog_agent', '/agent/Public/', 'Enter the Physical path of your Agents Application on your server.', 0, NULL, 'epayment_method');
INSERT INTO `cc_config` (`id`, `config_title`, `config_key`, `config_value`, `config_description`, `config_valuetype`, `config_listvalues`, `config_group_title`) VALUES(251, 'Secure Application Agent Physical Path', 'dir_ws_https_catalog_agent', '/agent/Public/', 'Enter the Physical path of your Agents Application on your Secure server.', 0, NULL, 'epayment_method');

-- --------------------------------------------------------

--
-- Table structure for table `cc_configuration`
--

CREATE TABLE IF NOT EXISTS `cc_configuration` (
  `configuration_id` int(11) NOT NULL auto_increment,
  `configuration_title` varchar(64) collate utf8_bin NOT NULL,
  `configuration_key` varchar(64) collate utf8_bin NOT NULL,
  `configuration_value` varchar(255) collate utf8_bin NOT NULL,
  `configuration_description` varchar(255) collate utf8_bin NOT NULL,
  `configuration_type` int(11) NOT NULL default '0',
  `use_function` varchar(255) collate utf8_bin default NULL,
  `set_function` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`configuration_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=26 ;

--
-- Dumping data for table `cc_configuration`
--

INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(1, 'Login Username', 'MODULE_PAYMENT_AUTHORIZENET_LOGIN', 'testing', 'The login username used for the Authorize.net service', 0, NULL, NULL);
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(2, 'Transaction Key', 'MODULE_PAYMENT_AUTHORIZENET_TXNKEY', 'Test', 'Transaction Key used for encrypting TP data', 0, NULL, NULL);
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(3, 'Transaction Mode', 'MODULE_PAYMENT_AUTHORIZENET_TESTMODE', 'Test', 'Transaction mode used for processing orders', 0, NULL, 'tep_cfg_select_option(array(''Test'', ''Production''), ');
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(4, 'Transaction Method', 'MODULE_PAYMENT_AUTHORIZENET_METHOD', 'Credit Card', 'Transaction method used for processing orders', 0, NULL, 'tep_cfg_select_option(array(''Credit Card'', ''eCheck''), ');
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(5, 'Customer Notifications', 'MODULE_PAYMENT_AUTHORIZENET_EMAIL_CUSTOMER', 'False', 'Should Authorize.Net e-mail a receipt to the customer?', 0, NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(6, 'Enable Authorize.net Module', 'MODULE_PAYMENT_AUTHORIZENET_STATUS', 'False', 'Do you want to accept Authorize.net payments?', 0, NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(7, 'Enable PayPal Module', 'MODULE_PAYMENT_PAYPAL_STATUS', 'True', 'Do you want to accept PayPal payments?', 0, NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(8, 'E-Mail Address', 'MODULE_PAYMENT_PAYPAL_ID', 'you@yourbusiness.com', 'The e-mail address to use for the PayPal service', 0, NULL, NULL);
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(9, 'Alternative Transaction Currency', 'MODULE_PAYMENT_PAYPAL_CURRENCY', 'Selected Currency', 'The alternative currency to use for credit card transactions if the system currency is not usable', 0, NULL, 'tep_cfg_select_option(array(''USD'',''CAD'',''EUR'',''GBP'',''JPY''), ');
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(10, 'E-Mail Address', 'MODULE_PAYMENT_MONEYBOOKERS_ID', 'you@yourbusiness.com', 'The eMail address to use for the moneybookers service', 0, NULL, NULL);
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(11, 'Referral ID', 'MODULE_PAYMENT_MONEYBOOKERS_REFID', '989999', 'Your personal Referral ID from moneybookers.com', 0, NULL, NULL);
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(12, 'Alternative Transaction Currency', 'MODULE_PAYMENT_MONEYBOOKERS_CURRENCY', 'Selected Currency', 'The alternative currency to use for credit card transactions if the system currency is not usable', 0, NULL, 'tep_cfg_select_option(array(''EUR'', ''USD'', ''GBP'', ''HKD'', ''SGD'', ''JPY'', ''CAD'', ''AUD'', ''CHF'', ''DKK'', ''SEK'', ''NOK'', ''ILS'', ''MYR'', ''NZD'', ''TWD'', ''THB'', ''CZK'', ''HUF'', ''SKK'', ''ISK'', ''INR''), ');
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(13, 'Transaction Language', 'MODULE_PAYMENT_MONEYBOOKERS_LANGUAGE', 'Selected Language', 'The default language for the payment transactions', 0, NULL, 'tep_cfg_select_option(array(''Selected Language'',''EN'', ''DE'', ''ES'', ''FR''), ');
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(14, 'Enable moneybookers Module', 'MODULE_PAYMENT_MONEYBOOKERS_STATUS', 'True', 'Do you want to accept moneybookers payments?', 0, NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(15, 'Enable PlugnPay Module', 'MODULE_PAYMENT_PLUGNPAY_STATUS', 'True', 'Do you want to accept payments through PlugnPay?', 0, NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(16, 'Login Username', 'MODULE_PAYMENT_PLUGNPAY_LOGIN', 'Your Login Name', 'Enter your PlugnPay account username', 0, NULL, NULL);
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(17, 'Publisher Email', 'MODULE_PAYMENT_PLUGNPAY_PUBLISHER_EMAIL', 'Enter Your Email Address', 'The email address you want PlugnPay conformations sent to', 0, NULL, NULL);
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(18, 'cURL Setup', 'MODULE_PAYMENT_PLUGNPAY_CURL', 'Not Compiled', 'Whether cURL is compiled into PHP or not.  Windows users, select not compiled.', 0, NULL, 'tep_cfg_select_option(array(''Not Compiled'', ''Compiled''), ');
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(19, 'cURL Path', 'MODULE_PAYMENT_PLUGNPAY_CURL_PATH', 'The Path To cURL', 'For Not Compiled mode only, input path to the cURL binary (i.e. c:/curl/curl)', 0, NULL, NULL);
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(20, 'Transaction Mode', 'MODULE_PAYMENT_PLUGNPAY_TESTMODE', 'Test', 'Transaction mode used for processing orders', 0, NULL, 'tep_cfg_select_option(array(''Test'', ''Test And Debug'', ''Production''), ');
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(21, 'Require CVV', 'MODULE_PAYMENT_PLUGNPAY_CVV', 'yes', 'Ask For CVV information', 0, NULL, 'tep_cfg_select_option(array(''yes'', ''no''), ');
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(22, 'Transaction Method', 'MODULE_PAYMENT_PLUGNPAY_PAYMETHOD', 'credit', 'Transaction method used for processing orders.<br><b>NOTE:</b> Selecting ''onlinecheck'' assumes you''ll offer ''credit'' as well.', 0, NULL, 'tep_cfg_select_option(array(''credit'', ''onlinecheck''), ');
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(23, 'Authorization Type', 'MODULE_PAYMENT_PLUGNPAY_CCMODE', 'authpostauth', 'Credit card processing mode', 0, NULL, 'tep_cfg_select_option(array(''authpostauth'', ''authonly''), ');
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(24, 'Customer Notifications', 'MODULE_PAYMENT_PLUGNPAY_DONTSNDMAIL', 'yes', 'Should PlugnPay not email a receipt to the customer?', 0, NULL, 'tep_cfg_select_option(array(''yes'', ''no''), ');
INSERT INTO `cc_configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_type`, `use_function`, `set_function`) VALUES(25, 'Accepted Credit Cards', 'MODULE_PAYMENT_PLUGNPAY_ACCEPTED_CC', 'Mastercard, Visa', 'The credit cards you currently accept', 0, NULL, '_selectOptions(array(''Amex'',''Discover'', ''Mastercard'', ''Visa''), ');

-- --------------------------------------------------------

--
-- Table structure for table `cc_config_group`
--

CREATE TABLE IF NOT EXISTS `cc_config_group` (
  `id` int(11) NOT NULL auto_increment,
  `group_title` varchar(64) collate utf8_bin NOT NULL,
  `group_description` varchar(255) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `group_title` (`group_title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=15 ;

--
-- Dumping data for table `cc_config_group`
--

INSERT INTO `cc_config_group` (`id`, `group_title`, `group_description`) VALUES(1, 'global', 'This configuration group handles the global settings for application.');
INSERT INTO `cc_config_group` (`id`, `group_title`, `group_description`) VALUES(2, 'callback', 'This configuration group handles calllback settings.');
INSERT INTO `cc_config_group` (`id`, `group_title`, `group_description`) VALUES(3, 'webcustomerui', 'This configuration group handles Web Customer User Interface.');
INSERT INTO `cc_config_group` (`id`, `group_title`, `group_description`) VALUES(4, 'sip-iax-info', 'SIP & IAX client configuration information.');
INSERT INTO `cc_config_group` (`id`, `group_title`, `group_description`) VALUES(5, 'epayment_method', 'Epayment Methods Configuration.');
INSERT INTO `cc_config_group` (`id`, `group_title`, `group_description`) VALUES(6, 'signup', 'This configuration group handles the signup related settings.');
INSERT INTO `cc_config_group` (`id`, `group_title`, `group_description`) VALUES(7, 'backup', 'This configuration group handles the backup/restore related settings.');
INSERT INTO `cc_config_group` (`id`, `group_title`, `group_description`) VALUES(8, 'webui', 'This configuration group handles the WEBUI and API Configuration.');
INSERT INTO `cc_config_group` (`id`, `group_title`, `group_description`) VALUES(9, 'peer_friend', 'This configuration group define parameters for the friends creation.');
INSERT INTO `cc_config_group` (`id`, `group_title`, `group_description`) VALUES(10, 'log-files', 'This configuration group handles the Log Files Directory Paths.');
INSERT INTO `cc_config_group` (`id`, `group_title`, `group_description`) VALUES(11, 'agi-conf1', 'This configuration group handles the AGI Configuration.');
INSERT INTO `cc_config_group` (`id`, `group_title`, `group_description`) VALUES(12, 'notifications', 'This configuration group handles the notifcations configuration');
INSERT INTO `cc_config_group` (`id`, `group_title`, `group_description`) VALUES(13, 'dashboard', 'This configuration group handles the dashboard configuration');
INSERT INTO `cc_config_group` (`id`, `group_title`, `group_description`) VALUES(14, 'webagentui', 'This configuration group handles Web Agent Interface.');

-- --------------------------------------------------------

--
-- Table structure for table `cc_country`
--

CREATE TABLE IF NOT EXISTS `cc_country` (
  `id` bigint(20) NOT NULL auto_increment,
  `countrycode` char(80) collate utf8_bin NOT NULL,
  `countryprefix` char(80) collate utf8_bin NOT NULL,
  `countryname` char(80) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=256 ;

--
-- Dumping data for table `cc_country`
--

INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(1, 'AFG', '93', 'Afghanistan');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(2, 'ALB', '355', 'Albania');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(3, 'DZA', '213', 'Algeria');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(4, 'ASM', '684', 'American Samoa');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(5, 'AND', '376', 'Andorra');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(6, 'AGO', '244', 'Angola');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(7, 'AIA', '1264', 'Anguilla');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(8, 'ATA', '672', 'Antarctica');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(9, 'ATG', '1268', 'Antigua And Barbuda');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(10, 'ARG', '54', 'Argentina');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(11, 'ARM', '374', 'Armenia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(12, 'ABW', '297', 'Aruba');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(13, 'AUS', '61', 'Australia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(14, 'AUT', '43', 'Austria');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(15, 'AZE', '994', 'Azerbaijan');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(16, 'BHS', '1242', 'Bahamas');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(17, 'BHR', '973', 'Bahrain');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(18, 'BGD', '880', 'Bangladesh');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(19, 'BRB', '1246', 'Barbados');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(20, 'BLR', '375', 'Belarus');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(21, 'BEL', '32', 'Belgium');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(22, 'BLZ', '501', 'Belize');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(23, 'BEN', '229', 'Benin');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(24, 'BMU', '1441', 'Bermuda');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(25, 'BTN', '975', 'Bhutan');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(26, 'BOL', '591', 'Bolivia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(27, 'BIH', '387', 'Bosnia And Herzegovina');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(28, 'BWA', '267', 'Botswana');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(29, 'BVT', '0', 'Bouvet Island');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(30, 'BRA', '55', 'Brazil');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(31, 'IOT', '1284', 'British Indian Ocean Territory');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(32, 'BRN', '673', 'Brunei Darussalam');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(33, 'BGR', '359', 'Bulgaria');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(34, 'BFA', '226', 'Burkina Faso');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(35, 'BDI', '257', 'Burundi');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(36, 'KHM', '855', 'Cambodia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(37, 'CMR', '237', 'Cameroon');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(38, 'CAN', '1', 'Canada');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(39, 'CPV', '238', 'Cape Verde');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(40, 'CYM', '1345', 'Cayman Islands');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(41, 'CAF', '236', 'Central African Republic');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(42, 'TCD', '235', 'Chad');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(43, 'CHL', '56', 'Chile');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(44, 'CHN', '86', 'China');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(45, 'CXR', '618', 'Christmas Island');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(46, 'CCK', '61', 'Cocos (Keeling); Islands');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(47, 'COL', '57', 'Colombia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(48, 'COM', '269', 'Comoros');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(49, 'COG', '242', 'Congo');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(50, 'COD', '243', 'Congo, The Democratic Republic Of The');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(51, 'COK', '682', 'Cook Islands');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(52, 'CRI', '506', 'Costa Rica');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(54, 'HRV', '385', 'Croatia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(55, 'CUB', '53', 'Cuba');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(56, 'CYP', '357', 'Cyprus');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(57, 'CZE', '420', 'Czech Republic');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(58, 'DNK', '45', 'Denmark');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(59, 'DJI', '253', 'Djibouti');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(60, 'DMA', '1767', 'Dominica');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(61, 'DOM', '1809', 'Dominican Republic');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(62, 'ECU', '593', 'Ecuador');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(63, 'EGY', '20', 'Egypt');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(64, 'SLV', '503', 'El Salvador');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(65, 'GNQ', '240', 'Equatorial Guinea');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(66, 'ERI', '291', 'Eritrea');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(67, 'EST', '372', 'Estonia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(68, 'ETH', '251', 'Ethiopia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(69, 'FLK', '500', 'Falkland Islands (Malvinas);');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(70, 'FRO', '298', 'Faroe Islands');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(71, 'FJI', '679', 'Fiji');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(72, 'FIN', '358', 'Finland');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(73, 'FRA', '33', 'France');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(74, 'GUF', '596', 'French Guiana');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(75, 'PYF', '594', 'French Polynesia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(76, 'ATF', '689', 'French Southern Territories');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(77, 'GAB', '241', 'Gabon');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(78, 'GMB', '220', 'Gambia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(79, 'GEO', '995', 'Georgia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(80, 'DEU', '49', 'Germany');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(81, 'GHA', '233', 'Ghana');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(82, 'GIB', '350', 'Gibraltar');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(83, 'GRC', '30', 'Greece');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(84, 'GRL', '299', 'Greenland');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(85, 'GRD', '1473', 'Grenada');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(86, 'GLP', '590', 'Guadeloupe');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(87, 'GUM', '1671', 'Guam');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(88, 'GTM', '502', 'Guatemala');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(89, 'GIN', '224', 'Guinea');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(90, 'GNB', '245', 'Guinea-Bissau');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(91, 'GUY', '592', 'Guyana');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(92, 'HTI', '509', 'Haiti');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(93, 'HMD', '0', 'Heard Island And McDonald Islands');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(94, 'VAT', '0', 'Holy See (Vatican City State);');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(95, 'HND', '504', 'Honduras');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(96, 'HKG', '852', 'Hong Kong');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(97, 'HUN', '36', 'Hungary');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(98, 'ISL', '354', 'Iceland');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(99, 'IND', '91', 'India');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(100, 'IDN', '62', 'Indonesia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(101, 'IRN', '98', 'Iran, Islamic Republic Of');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(102, 'IRQ', '964', 'Iraq');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(103, 'IRL', '353', 'Ireland');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(104, 'ISR', '972', 'Israel');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(105, 'ITA', '39', 'Italy');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(106, 'JAM', '1876', 'Jamaica');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(107, 'JPN', '81', 'Japan');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(108, 'JOR', '962', 'Jordan');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(109, 'KAZ', '7', 'Kazakhstan');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(110, 'KEN', '254', 'Kenya');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(111, 'KIR', '686', 'Kiribati');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(112, 'PRK', '850', 'Korea, Democratic People''s Republic Of');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(113, 'KOR', '82', 'Korea, Republic of');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(114, 'KWT', '965', 'Kuwait');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(115, 'KGZ', '996', 'Kyrgyzstan');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(116, 'LAO', '856', 'Lao People''s Democratic Republic');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(117, 'LVA', '371', 'Latvia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(118, 'LBN', '961', 'Lebanon');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(119, 'LSO', '266', 'Lesotho');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(120, 'LBR', '231', 'Liberia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(121, 'LBY', '218', 'Libyan Arab Jamahiriya');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(122, 'LIE', '423', 'Liechtenstein');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(123, 'LTU', '370', 'Lithuania');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(124, 'LUX', '352', 'Luxembourg');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(125, 'MAC', '853', 'Macao');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(126, 'MKD', '389', 'Macedonia, The Former Yugoslav Republic Of');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(127, 'MDG', '261', 'Madagascar');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(128, 'MWI', '265', 'Malawi');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(129, 'MYS', '60', 'Malaysia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(130, 'MDV', '960', 'Maldives');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(131, 'MLI', '223', 'Mali');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(132, 'MLT', '356', 'Malta');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(133, 'MHL', '692', 'Marshall islands');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(134, 'MTQ', '596', 'Martinique');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(135, 'MRT', '222', 'Mauritania');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(136, 'MUS', '230', 'Mauritius');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(137, 'MYT', '269', 'Mayotte');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(138, 'MEX', '52', 'Mexico');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(139, 'FSM', '691', 'Micronesia, Federated States Of');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(140, 'MDA', '1808', 'Moldova, Republic Of');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(141, 'MCO', '377', 'Monaco');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(142, 'MNG', '976', 'Mongolia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(143, 'MSR', '1664', 'Montserrat');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(144, 'MAR', '212', 'Morocco');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(145, 'MOZ', '258', 'Mozambique');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(146, 'MMR', '95', 'Myanmar');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(147, 'NAM', '264', 'Namibia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(148, 'NRU', '674', 'Nauru');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(149, 'NPL', '977', 'Nepal');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(150, 'NLD', '31', 'Netherlands');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(151, 'ANT', '599', 'Netherlands Antilles');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(152, 'NCL', '687', 'New Caledonia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(153, 'NZL', '64', 'New Zealand');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(154, 'NIC', '505', 'Nicaragua');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(155, 'NER', '227', 'Niger');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(156, 'NGA', '234', 'Nigeria');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(157, 'NIU', '683', 'Niue');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(158, 'NFK', '672', 'Norfolk Island');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(159, 'MNP', '1670', 'Northern Mariana Islands');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(160, 'NOR', '47', 'Norway');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(161, 'OMN', '968', 'Oman');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(162, 'PAK', '92', 'Pakistan');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(163, 'PLW', '680', 'Palau');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(164, 'PSE', '970', 'Palestinian Territory, Occupied');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(165, 'PAN', '507', 'Panama');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(166, 'PNG', '675', 'Papua New Guinea');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(167, 'PRY', '595', 'Paraguay');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(168, 'PER', '51', 'Peru');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(169, 'PHL', '63', 'Philippines');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(170, 'PCN', '0', 'Pitcairn');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(171, 'POL', '48', 'Poland');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(172, 'PRT', '351', 'Portugal');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(173, 'PRI', '1787', 'Puerto Rico');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(174, 'QAT', '974', 'Qatar');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(175, 'REU', '262', 'Reunion');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(176, 'ROU', '40', 'Romania');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(177, 'RUS', '7', 'Russian Federation');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(178, 'RWA', '250', 'Rwanda');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(179, 'SHN', '290', 'SaINT Helena');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(180, 'KNA', '1869', 'SaINT Kitts And Nevis');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(181, 'LCA', '1758', 'SaINT Lucia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(182, 'SPM', '508', 'SaINT Pierre And Miquelon');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(183, 'VCT', '1784', 'SaINT Vincent And The Grenadines');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(184, 'WSM', '685', 'Samoa');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(185, 'SMR', '378', 'San Marino');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(186, 'STP', '239', 'SÃ£o TomÃ© And Principe');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(187, 'SAU', '966', 'Saudi Arabia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(188, 'SEN', '221', 'Senegal');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(189, 'SYC', '248', 'Seychelles');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(190, 'SLE', '232', 'Sierra Leone');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(191, 'SGP', '65', 'Singapore');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(192, 'SVK', '421', 'Slovakia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(193, 'SVN', '386', 'Slovenia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(194, 'SLB', '677', 'Solomon Islands');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(195, 'SOM', '252', 'Somalia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(196, 'ZAF', '27', 'South Africa');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(197, 'SGS', '0', 'South Georgia And The South Sandwich Islands');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(198, 'ESP', '34', 'Spain');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(199, 'LKA', '94', 'Sri Lanka');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(200, 'SDN', '249', 'Sudan');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(201, 'SUR', '597', 'Suriname');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(202, 'SJM', '0', 'Svalbard and Jan Mayen');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(203, 'SWZ', '268', 'Swaziland');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(204, 'SWE', '46', 'Sweden');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(205, 'CHE', '41', 'Switzerland');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(206, 'SYR', '963', 'Syrian Arab Republic');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(207, 'TWN', '886', 'Taiwan, Province Of China');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(208, 'TJK', '992', 'Tajikistan');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(209, 'TZA', '255', 'Tanzania, United Republic Of');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(210, 'THA', '66', 'Thailand');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(211, 'TLS', '670', 'Timor-Leste');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(212, 'TGO', '228', 'Togo');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(213, 'TKL', '690', 'Tokelau');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(214, 'TON', '676', 'Tonga');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(215, 'TTO', '1868', 'Trinidad And Tobago');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(216, 'TUN', '216', 'Tunisia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(217, 'TUR', '90', 'Turkey');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(218, 'TKM', '993', 'Turkmenistan');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(219, 'TCA', '1649', 'Turks And Caicos Islands');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(220, 'TUV', '688', 'Tuvalu');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(221, 'UGA', '256', 'Uganda');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(222, 'UKR', '380', 'Ukraine');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(223, 'ARE', '971', 'United Arab Emirates');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(224, 'GBR', '44', 'United Kingdom');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(225, 'USA', '1', 'United States');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(226, 'UMI', '0', 'United States Minor Outlying Islands');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(227, 'URY', '598', 'Uruguay');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(228, 'UZB', '998', 'Uzbekistan');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(229, 'VUT', '678', 'Vanuatu');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(230, 'VEN', '58', 'Venezuela');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(231, 'VNM', '84', 'Vietnam');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(232, 'VGB', '1284', 'Virgin Islands, British');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(233, 'VIR', '808', 'Virgin Islands, U.S.');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(234, 'WLF', '681', 'Wallis And Futuna');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(235, 'ESH', '0', 'Western Sahara');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(236, 'YEM', '967', 'Yemen');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(237, 'YUG', '0', 'Yugoslavia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(238, 'ZMB', '260', 'Zambia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(239, 'ZWE', '263', 'Zimbabwe');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(240, 'ASC', '0', 'Ascension Island');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(241, 'DGA', '0', 'Diego Garcia');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(242, 'XNM', '0', 'Inmarsat');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(243, 'TMP', '0', 'East timor');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(244, 'AK', '0', 'Alaska');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(245, 'HI', '0', 'Hawaii');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(53, 'CIV', '225', 'CÃ´te d''Ivoire');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(246, 'ALA', '35818', 'Aland Islands');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(247, 'BLM', '0', 'Saint Barthelemy');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(248, 'GGY', '441481', 'Guernsey');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(249, 'IMN', '441624', 'Isle of Man');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(250, 'JEY', '441534', 'Jersey');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(251, 'MAF', '0', 'Saint Martin');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(252, 'MNE', '382', 'Montenegro, Republic of');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(253, 'SRB', '381', 'Serbia, Republic of');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(254, 'CPT', '0', 'Clipperton Island');
INSERT INTO `cc_country` (`id`, `countrycode`, `countryprefix`, `countryname`) VALUES(255, 'TAA', '0', 'Tristan da Cunha');

-- --------------------------------------------------------

--
-- Table structure for table `cc_currencies`
--

CREATE TABLE IF NOT EXISTS `cc_currencies` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `currency` char(3) collate utf8_bin NOT NULL default '',
  `name` varchar(30) collate utf8_bin NOT NULL default '',
  `value` decimal(12,5) unsigned NOT NULL default '0.00000',
  `lastupdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `basecurrency` char(3) collate utf8_bin NOT NULL default 'USD',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `cons_cc_currencies_currency` (`currency`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=151 ;

--
-- Dumping data for table `cc_currencies`
--

INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(1, 'ALL', 'Albanian Lek (ALL)', 0.00974, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(2, 'DZD', 'Algerian Dinar (DZD)', 0.01345, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(3, 'XAL', 'Aluminium Ounces (XAL)', 1.08295, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(4, 'ARS', 'Argentine Peso (ARS)', 0.32455, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(5, 'AWG', 'Aruba Florin (AWG)', 0.55866, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(6, 'AUD', 'Australian Dollar (AUD)', 0.73384, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(7, 'BSD', 'Bahamian Dollar (BSD)', 1.00000, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(8, 'BHD', 'Bahraini Dinar (BHD)', 2.65322, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(9, 'BDT', 'Bangladesh Taka (BDT)', 0.01467, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(10, 'BBD', 'Barbados Dollar (BBD)', 0.50000, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(11, 'BYR', 'Belarus Ruble (BYR)', 0.00046, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(12, 'BZD', 'Belize Dollar (BZD)', 0.50569, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(13, 'BMD', 'Bermuda Dollar (BMD)', 1.00000, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(14, 'BTN', 'Bhutan Ngultrum (BTN)', 0.02186, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(15, 'BOB', 'Bolivian Boliviano (BOB)', 0.12500, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(16, 'BRL', 'Brazilian Real (BRL)', 0.46030, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(17, 'GBP', 'British Pound (GBP)', 1.73702, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(18, 'BND', 'Brunei Dollar (BND)', 0.61290, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(19, 'BGN', 'Bulgarian Lev (BGN)', 0.60927, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(20, 'BIF', 'Burundi Franc (BIF)', 0.00103, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(21, 'KHR', 'Cambodia Riel (KHR)', 0.00000, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(22, 'CAD', 'Canadian Dollar (CAD)', 0.86386, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(23, 'KYD', 'Cayman Islands Dollar (KYD)', 1.16496, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(24, 'XOF', 'CFA Franc (BCEAO) (XOF)', 0.00182, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(25, 'XAF', 'CFA Franc (BEAC) (XAF)', 0.00182, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(26, 'CLP', 'Chilean Peso (CLP)', 0.00187, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(27, 'CNY', 'Chinese Yuan (CNY)', 0.12425, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(28, 'COP', 'Colombian Peso (COP)', 0.00044, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(29, 'KMF', 'Comoros Franc (KMF)', 0.00242, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(30, 'XCP', 'Copper Ounces (XCP)', 2.16403, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(31, 'CRC', 'Costa Rica Colon (CRC)', 0.00199, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(32, 'HRK', 'Croatian Kuna (HRK)', 0.16249, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(33, 'CUP', 'Cuban Peso (CUP)', 1.00000, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(34, 'CYP', 'Cyprus Pound (CYP)', 2.07426, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(35, 'CZK', 'Czech Koruna (CZK)', 0.04133, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(36, 'DKK', 'Danish Krone (DKK)', 0.15982, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(37, 'DJF', 'Dijibouti Franc (DJF)', 0.00000, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(38, 'DOP', 'Dominican Peso (DOP)', 0.03035, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(39, 'XCD', 'East Caribbean Dollar (XCD)', 0.37037, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(40, 'ECS', 'Ecuador Sucre (ECS)', 0.00004, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(41, 'EGP', 'Egyptian Pound (EGP)', 0.17433, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(42, 'SVC', 'El Salvador Colon (SVC)', 0.11426, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(43, 'ERN', 'Eritrea Nakfa (ERN)', 0.00000, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(44, 'EEK', 'Estonian Kroon (EEK)', 0.07615, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(45, 'ETB', 'Ethiopian Birr (ETB)', 0.11456, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(46, 'EUR', 'Euro (EUR)', 1.19175, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(47, 'FKP', 'Falkland Islands Pound (FKP)', 0.00000, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(48, 'GMD', 'Gambian Dalasi (GMD)', 0.03515, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(49, 'GHC', 'Ghanian Cedi (GHC)', 0.00011, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(50, 'GIP', 'Gibraltar Pound (GIP)', 0.00000, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(51, 'XAU', 'Gold Ounces (XAU)', 99.99999, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(52, 'GTQ', 'Guatemala Quetzal (GTQ)', 0.13103, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(53, 'GNF', 'Guinea Franc (GNF)', 0.00022, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(54, 'HTG', 'Haiti Gourde (HTG)', 0.02387, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(55, 'HNL', 'Honduras Lempira (HNL)', 0.05292, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(56, 'HKD', 'Hong Kong Dollar (HKD)', 0.12884, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(57, 'HUF', 'Hungarian ForINT (HUF)', 0.00461, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(58, 'ISK', 'Iceland Krona (ISK)', 0.01436, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(59, 'INR', 'Indian Rupee (INR)', 0.02253, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(60, 'IDR', 'Indonesian Rupiah (IDR)', 0.00011, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(61, 'IRR', 'Iran Rial (IRR)', 0.00011, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(62, 'ILS', 'Israeli Shekel (ILS)', 0.21192, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(63, 'JMD', 'Jamaican Dollar (JMD)', 0.01536, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(64, 'JPY', 'Japanese Yen (JPY)', 0.00849, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(65, 'JOD', 'Jordanian Dinar (JOD)', 1.41044, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(66, 'KZT', 'Kazakhstan Tenge (KZT)', 0.00773, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(67, 'KES', 'Kenyan Shilling (KES)', 0.01392, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(68, 'KRW', 'Korean Won (KRW)', 0.00102, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(69, 'KWD', 'Kuwaiti Dinar (KWD)', 3.42349, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(70, 'LAK', 'Lao Kip (LAK)', 0.00000, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(71, 'LVL', 'Latvian Lat (LVL)', 1.71233, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(72, 'LBP', 'Lebanese Pound (LBP)', 0.00067, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(73, 'LSL', 'Lesotho Loti (LSL)', 0.15817, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(74, 'LYD', 'Libyan Dinar (LYD)', 0.00000, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(75, 'LTL', 'Lithuanian Lita (LTL)', 0.34510, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(76, 'MOP', 'Macau Pataca (MOP)', 0.12509, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(77, 'MKD', 'Macedonian Denar (MKD)', 0.01945, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(78, 'MGF', 'Malagasy Franc (MGF)', 0.00011, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(79, 'MWK', 'Malawi Kwacha (MWK)', 0.00752, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(80, 'MYR', 'Malaysian Ringgit (MYR)', 0.26889, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(81, 'MVR', 'Maldives Rufiyaa (MVR)', 0.07813, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(82, 'MTL', 'Maltese Lira (MTL)', 2.77546, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(83, 'MRO', 'Mauritania Ougulya (MRO)', 0.00369, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(84, 'MUR', 'Mauritius Rupee (MUR)', 0.03258, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(85, 'MXN', 'Mexican Peso (MXN)', 0.09320, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(86, 'MDL', 'Moldovan Leu (MDL)', 0.07678, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(87, 'MNT', 'Mongolian Tugrik (MNT)', 0.00084, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(88, 'MAD', 'Moroccan Dirham (MAD)', 0.10897, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(89, 'MZM', 'Mozambique Metical (MZM)', 0.00004, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(90, 'NAD', 'Namibian Dollar (NAD)', 0.15817, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(91, 'NPR', 'Nepalese Rupee (NPR)', 0.01408, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(92, 'ANG', 'Neth Antilles Guilder (ANG)', 0.55866, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(93, 'TRY', 'New Turkish Lira (TRY)', 0.73621, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(94, 'NZD', 'New Zealand Dollar (NZD)', 0.65096, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(95, 'NIO', 'Nicaragua Cordoba (NIO)', 0.05828, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(96, 'NGN', 'Nigerian Naira (NGN)', 0.00777, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(97, 'NOK', 'Norwegian Krone (NOK)', 0.14867, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(98, 'OMR', 'Omani Rial (OMR)', 2.59740, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(99, 'XPF', 'Pacific Franc (XPF)', 0.00999, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(100, 'PKR', 'Pakistani Rupee (PKR)', 0.01667, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(101, 'XPD', 'Palladium Ounces (XPD)', 99.99999, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(102, 'PAB', 'Panama Balboa (PAB)', 1.00000, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(103, 'PGK', 'Papua New Guinea Kina (PGK)', 0.33125, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(104, 'PYG', 'Paraguayan Guarani (PYG)', 0.00017, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(105, 'PEN', 'Peruvian Nuevo Sol (PEN)', 0.29999, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(106, 'PHP', 'Philippine Peso (PHP)', 0.01945, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(107, 'XPT', 'Platinum Ounces (XPT)', 99.99999, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(108, 'PLN', 'Polish Zloty (PLN)', 0.30574, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(109, 'QAR', 'Qatar Rial (QAR)', 0.27476, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(110, 'ROL', 'Romanian Leu (ROL)', 0.00000, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(111, 'RON', 'Romanian New Leu (RON)', 0.34074, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(112, 'RUB', 'Russian Rouble (RUB)', 0.03563, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(113, 'RWF', 'Rwanda Franc (RWF)', 0.00185, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(114, 'WST', 'Samoa Tala (WST)', 0.35492, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(115, 'STD', 'Sao Tome Dobra (STD)', 0.00000, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(116, 'SAR', 'Saudi Arabian Riyal (SAR)', 0.26665, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(117, 'SCR', 'Seychelles Rupee (SCR)', 0.18114, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(118, 'SLL', 'Sierra Leone Leone (SLL)', 0.00034, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(119, 'XAG', 'Silver Ounces (XAG)', 9.77517, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(120, 'SGD', 'Singapore Dollar (SGD)', 0.61290, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(121, 'SKK', 'Slovak Koruna (SKK)', 0.03157, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(122, 'SIT', 'Slovenian Tolar (SIT)', 0.00498, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(123, 'SOS', 'Somali Shilling (SOS)', 0.00000, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(124, 'ZAR', 'South African Rand (ZAR)', 0.15835, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(125, 'LKR', 'Sri Lanka Rupee (LKR)', 0.00974, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(126, 'SHP', 'St Helena Pound (SHP)', 0.00000, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(127, 'SDD', 'Sudanese Dinar (SDD)', 0.00427, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(128, 'SRG', 'Surinam Guilder (SRG)', 0.36496, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(129, 'SZL', 'Swaziland Lilageni (SZL)', 0.15817, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(130, 'SEK', 'Swedish Krona (SEK)', 0.12609, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(131, 'CHF', 'Swiss Franc (CHF)', 0.76435, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(132, 'SYP', 'Syrian Pound (SYP)', 0.00000, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(133, 'TWD', 'Taiwan Dollar (TWD)', 0.03075, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(134, 'TZS', 'Tanzanian Shilling (TZS)', 0.00083, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(135, 'THB', 'Thai Baht (THB)', 0.02546, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(136, 'TOP', 'Tonga Paanga (TOP)', 0.48244, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(137, 'TTD', 'Trinidad&Tobago Dollar (TTD)', 0.15863, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(138, 'TND', 'Tunisian Dinar (TND)', 0.73470, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(139, 'USD', 'U.S. Dollar (USD)', 1.00000, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(140, 'AED', 'UAE Dirham (AED)', 0.27228, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(141, 'UGX', 'Ugandan Shilling (UGX)', 0.00055, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(142, 'UAH', 'Ukraine Hryvnia (UAH)', 0.19755, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(143, 'UYU', 'Uruguayan New Peso (UYU)', 0.04119, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(144, 'VUV', 'Vanuatu Vatu (VUV)', 0.00870, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(145, 'VEB', 'Venezuelan Bolivar (VEB)', 0.00037, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(146, 'VND', 'Vietnam Dong (VND)', 0.00006, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(147, 'YER', 'Yemen Riyal (YER)', 0.00510, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(148, 'ZMK', 'Zambian Kwacha (ZMK)', 0.00031, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(149, 'ZWD', 'Zimbabwe Dollar (ZWD)', 0.00001, '2009-05-15 16:38:43', 'USD');
INSERT INTO `cc_currencies` (`id`, `currency`, `name`, `value`, `lastupdate`, `basecurrency`) VALUES(150, 'GYD', 'Guyana Dollar (GYD)', 0.00527, '2009-05-15 16:38:43', 'USD');

-- --------------------------------------------------------

--
-- Table structure for table `cc_did`
--

CREATE TABLE IF NOT EXISTS `cc_did` (
  `id` bigint(20) NOT NULL auto_increment,
  `id_cc_didgroup` bigint(20) NOT NULL,
  `id_cc_country` int(11) NOT NULL,
  `activated` int(11) NOT NULL default '1',
  `reserved` int(11) default '0',
  `iduser` bigint(20) NOT NULL default '0',
  `did` char(50) collate utf8_bin NOT NULL,
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `startingdate` timestamp NOT NULL default '0000-00-00 00:00:00',
  `expirationdate` timestamp NOT NULL default '0000-00-00 00:00:00',
  `description` mediumtext collate utf8_bin,
  `secondusedreal` int(11) default '0',
  `billingtype` int(11) default '0',
  `fixrate` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `cons_cc_did_did` (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_did`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_didgroup`
--

CREATE TABLE IF NOT EXISTS `cc_didgroup` (
  `id` bigint(20) NOT NULL auto_increment,
  `iduser` int(11) NOT NULL default '0',
  `didgroupname` char(50) collate utf8_bin NOT NULL,
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_didgroup`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_did_destination`
--

CREATE TABLE IF NOT EXISTS `cc_did_destination` (
  `id` bigint(20) NOT NULL auto_increment,
  `destination` char(50) collate utf8_bin NOT NULL,
  `priority` int(11) NOT NULL default '0',
  `id_cc_card` bigint(20) NOT NULL,
  `id_cc_did` bigint(20) NOT NULL,
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `activated` int(11) NOT NULL default '1',
  `secondusedreal` int(11) default '0',
  `voip_call` int(11) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_did_destination`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_did_use`
--

CREATE TABLE IF NOT EXISTS `cc_did_use` (
  `id` bigint(20) NOT NULL auto_increment,
  `id_cc_card` bigint(20) default NULL,
  `id_did` bigint(20) NOT NULL,
  `reservationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `releasedate` timestamp NOT NULL default '0000-00-00 00:00:00',
  `activated` int(11) default '0',
  `month_payed` int(11) default '0',
  `reminded` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_did_use`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_ecommerce_product`
--

CREATE TABLE IF NOT EXISTS `cc_ecommerce_product` (
  `id` bigint(20) NOT NULL auto_increment,
  `product_name` varchar(255) collate utf8_bin NOT NULL,
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `description` mediumtext collate utf8_bin,
  `expirationdate` timestamp NOT NULL default '0000-00-00 00:00:00',
  `enableexpire` int(11) default '0',
  `expiredays` int(11) default '0',
  `mailtype` varchar(50) collate utf8_bin NOT NULL,
  `credit` float NOT NULL default '0',
  `tariff` int(11) default '0',
  `id_didgroup` int(11) default '0',
  `activated` char(1) collate utf8_bin NOT NULL default 'f',
  `simultaccess` int(11) default '0',
  `currency` char(3) collate utf8_bin default 'USD',
  `typepaid` int(11) default '0',
  `creditlimit` int(11) default '0',
  `language` char(5) collate utf8_bin default 'en',
  `runservice` int(11) default '0',
  `sip_friend` int(11) default '0',
  `iax_friend` int(11) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_ecommerce_product`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_epayment_log`
--

CREATE TABLE IF NOT EXISTS `cc_epayment_log` (
  `id` bigint(20) NOT NULL auto_increment,
  `cardid` bigint(20) NOT NULL default '0',
  `amount` varchar(50) collate utf8_bin NOT NULL default '0',
  `vat` float NOT NULL default '0',
  `paymentmethod` char(50) collate utf8_bin NOT NULL,
  `cc_owner` varchar(64) collate utf8_bin default NULL,
  `cc_number` varchar(32) collate utf8_bin default NULL,
  `cc_expires` varchar(7) collate utf8_bin default NULL,
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL default '0',
  `cvv` varchar(4) collate utf8_bin default NULL,
  `credit_card_type` varchar(20) collate utf8_bin default NULL,
  `currency` varchar(4) collate utf8_bin default NULL,
  `transaction_detail` longtext collate utf8_bin,
  `item_type` varchar(30) collate utf8_bin default NULL,
  `item_id` bigint(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_epayment_log`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_epayment_log_agent`
--

CREATE TABLE IF NOT EXISTS `cc_epayment_log_agent` (
  `id` bigint(20) NOT NULL auto_increment,
  `agent_id` bigint(20) NOT NULL default '0',
  `amount` varchar(50) collate utf8_bin NOT NULL default '0',
  `vat` float NOT NULL default '0',
  `paymentmethod` char(50) collate utf8_bin NOT NULL,
  `cc_owner` varchar(64) collate utf8_bin default NULL,
  `cc_number` varchar(32) collate utf8_bin default NULL,
  `cc_expires` varchar(7) collate utf8_bin default NULL,
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL default '0',
  `cvv` varchar(4) collate utf8_bin default NULL,
  `credit_card_type` varchar(20) collate utf8_bin default NULL,
  `currency` varchar(4) collate utf8_bin default NULL,
  `transaction_detail` longtext collate utf8_bin,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_epayment_log_agent`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_iax_buddies`
--

CREATE TABLE IF NOT EXISTS `cc_iax_buddies` (
  `id` int(11) NOT NULL auto_increment,
  `id_cc_card` int(11) NOT NULL default '0',
  `name` varchar(80) collate utf8_bin NOT NULL,
  `accountcode` varchar(20) collate utf8_bin NOT NULL,
  `regexten` varchar(20) collate utf8_bin NOT NULL,
  `amaflags` char(7) collate utf8_bin default NULL,
  `callgroup` char(10) collate utf8_bin default NULL,
  `callerid` varchar(80) collate utf8_bin NOT NULL,
  `canreinvite` varchar(20) collate utf8_bin NOT NULL,
  `context` varchar(80) collate utf8_bin NOT NULL,
  `DEFAULTip` char(15) collate utf8_bin default NULL,
  `dtmfmode` char(7) collate utf8_bin NOT NULL default 'RFC2833',
  `fromuser` varchar(80) collate utf8_bin NOT NULL,
  `fromdomain` varchar(80) collate utf8_bin NOT NULL,
  `host` varchar(31) collate utf8_bin NOT NULL,
  `insecure` varchar(20) collate utf8_bin NOT NULL,
  `language` char(2) collate utf8_bin default NULL,
  `mailbox` varchar(50) collate utf8_bin NOT NULL,
  `md5secret` varchar(80) collate utf8_bin NOT NULL,
  `nat` char(3) collate utf8_bin default 'yes',
  `permit` varchar(95) collate utf8_bin NOT NULL,
  `deny` varchar(95) collate utf8_bin NOT NULL,
  `mask` varchar(95) collate utf8_bin NOT NULL,
  `pickupgroup` char(10) collate utf8_bin default NULL,
  `port` char(5) collate utf8_bin NOT NULL default '',
  `qualify` char(7) collate utf8_bin default 'yes',
  `restrictcid` char(1) collate utf8_bin default NULL,
  `rtptimeout` char(3) collate utf8_bin default NULL,
  `rtpholdtimeout` char(3) collate utf8_bin default NULL,
  `secret` varchar(80) collate utf8_bin NOT NULL,
  `type` char(6) collate utf8_bin NOT NULL default 'friend',
  `username` varchar(80) collate utf8_bin NOT NULL,
  `disallow` varchar(100) collate utf8_bin NOT NULL,
  `allow` varchar(100) collate utf8_bin NOT NULL,
  `musiconhold` varchar(100) collate utf8_bin NOT NULL,
  `regseconds` int(11) NOT NULL default '0',
  `ipaddr` char(15) collate utf8_bin NOT NULL default '',
  `cancallforward` char(3) collate utf8_bin default 'yes',
  `trunk` char(3) collate utf8_bin default 'no',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `cons_cc_iax_buddies_name` (`name`),
  KEY `name` (`name`),
  KEY `host` (`host`),
  KEY `ipaddr` (`ipaddr`),
  KEY `port` (`port`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_iax_buddies`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_invoice`
--

CREATE TABLE IF NOT EXISTS `cc_invoice` (
  `id` bigint(20) NOT NULL auto_increment,
  `reference` varchar(30) collate utf8_bin default NULL,
  `id_card` bigint(20) NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `paid_status` tinyint(4) NOT NULL default '0',
  `status` tinyint(4) NOT NULL default '0',
  `title` varchar(50) collate utf8_bin NOT NULL,
  `description` mediumtext collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `reference` (`reference`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_invoice`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_invoice_conf`
--

CREATE TABLE IF NOT EXISTS `cc_invoice_conf` (
  `id` int(11) NOT NULL auto_increment,
  `key_val` varchar(50) collate utf8_bin NOT NULL,
  `value` varchar(50) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `key_val` (`key_val`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=11 ;

--
-- Dumping data for table `cc_invoice_conf`
--

INSERT INTO `cc_invoice_conf` (`id`, `key_val`, `value`) VALUES(1, 'company_name', 'My company');
INSERT INTO `cc_invoice_conf` (`id`, `key_val`, `value`) VALUES(2, 'address', 'address');
INSERT INTO `cc_invoice_conf` (`id`, `key_val`, `value`) VALUES(3, 'zipcode', 'xxxx');
INSERT INTO `cc_invoice_conf` (`id`, `key_val`, `value`) VALUES(4, 'country', 'country');
INSERT INTO `cc_invoice_conf` (`id`, `key_val`, `value`) VALUES(5, 'city', 'city');
INSERT INTO `cc_invoice_conf` (`id`, `key_val`, `value`) VALUES(6, 'phone', 'xxxxxxxxxxx');
INSERT INTO `cc_invoice_conf` (`id`, `key_val`, `value`) VALUES(7, 'fax', 'xxxxxxxxxxx');
INSERT INTO `cc_invoice_conf` (`id`, `key_val`, `value`) VALUES(8, 'email', 'xxxxxxx@xxxxxxx.xxx');
INSERT INTO `cc_invoice_conf` (`id`, `key_val`, `value`) VALUES(9, 'vat', 'xxxxxxxxxx');
INSERT INTO `cc_invoice_conf` (`id`, `key_val`, `value`) VALUES(10, 'web', 'www.xxxxxxx.xxx');

-- --------------------------------------------------------

--
-- Table structure for table `cc_invoice_item`
--

CREATE TABLE IF NOT EXISTS `cc_invoice_item` (
  `id` bigint(20) NOT NULL auto_increment,
  `id_invoice` bigint(20) NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `price` decimal(15,5) NOT NULL default '0.00000',
  `VAT` decimal(4,2) NOT NULL default '0.00',
  `description` mediumtext collate utf8_bin NOT NULL,
  `id_ext` bigint(20) default NULL,
  `type_ext` varchar(10) collate utf8_bin default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_invoice_item`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_invoice_payment`
--

CREATE TABLE IF NOT EXISTS `cc_invoice_payment` (
  `id_invoice` bigint(20) NOT NULL,
  `id_payment` bigint(20) NOT NULL,
  PRIMARY KEY  (`id_invoice`,`id_payment`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `cc_invoice_payment`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_iso639`
--

CREATE TABLE IF NOT EXISTS `cc_iso639` (
  `code` char(2) collate utf8_bin NOT NULL,
  `name` char(16) collate utf8_bin NOT NULL,
  `lname` char(16) collate utf8_bin default NULL,
  `charset` char(16) collate utf8_bin NOT NULL default 'ISO-8859-1',
  PRIMARY KEY  (`code`),
  UNIQUE KEY `iso639_name_key` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `cc_iso639`
--

INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ab', 'Abkhazian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('om', 'Afan (Oromo)', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('aa', 'Afar', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('af', 'Afrikaans', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('sq', 'Albanian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('am', 'Amharic', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ar', 'Arabic', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('hy', 'Armenian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('as', 'Assamese', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ay', 'Aymara', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('az', 'Azerbaijani', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ba', 'Bashkir', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('eu', 'Basque', 'Euskera', 'ISO-8859-15');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('bn', 'Bengali Bangla', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('dz', 'Bhutani', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('bh', 'Bihari', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('bi', 'Bislama', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('br', 'Breton', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('bg', 'Bulgarian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('my', 'Burmese', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('be', 'Byelorussian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('km', 'Cambodian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ca', 'Catalan', '          		', 'ISO-8859-15');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('zh', 'Chinese', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('co', 'Corsican', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('hr', 'Croatian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('cs', 'Czech', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('da', 'Danish', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('nl', 'Dutch', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('en', 'English', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('eo', 'Esperanto', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('et', 'Estonian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('fo', 'Faroese', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('fj', 'Fiji', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('fi', 'Finnish', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('fr', 'French', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('fy', 'Frisian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('gl', 'Galician', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ka', 'Georgian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('de', 'German', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('el', 'Greek', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('kl', 'Greenlandic', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('gn', 'Guarani', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('gu', 'Gujarati', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ha', 'Hausa', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('he', 'Hebrew', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('hi', 'Hindi', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('hu', 'Hungarian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('is', 'Icelandic', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('id', 'Indonesian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ia', 'Interlingua', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ie', 'Interlingue', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('iu', 'Inuktitut', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ik', 'Inupiak', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ga', 'Irish', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('it', 'Italian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ja', 'Japanese', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('jv', 'Javanese', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('kn', 'Kannada', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ks', 'Kashmiri', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('kk', 'Kazakh', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('rw', 'Kinyarwanda', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ky', 'Kirghiz', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('rn', 'Kurundi', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ko', 'Korean', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ku', 'Kurdish', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('lo', 'Laothian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('la', 'Latin', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('lv', 'Latvian Lettish', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ln', 'Lingala', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('lt', 'Lithuanian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('mk', 'Macedonian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('mg', 'Malagasy', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ms', 'Malay', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ml', 'Malayalam', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('mt', 'Maltese', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('mi', 'Maori', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('mr', 'Marathi', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('mo', 'Moldavian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('mn', 'Mongolian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('na', 'Nauru', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ne', 'Nepali', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('no', 'Norwegian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('oc', 'Occitan', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('or', 'Oriya', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ps', 'Pashto Pushto', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('fa', 'Persian (Farsi)', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('pl', 'Polish', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('pt', 'Portuguese', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('pa', 'Punjabi', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('qu', 'Quechua', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('rm', 'Rhaeto-Romance', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ro', 'Romanian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ru', 'Russian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('sm', 'Samoan', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('sg', 'Sangho', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('sa', 'Sanskrit', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('gd', 'Scots Gaelic', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('sr', 'Serbian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('sh', 'Serbo-Croatian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('st', 'Sesotho', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('tn', 'Setswana', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('sn', 'Shona', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('sd', 'Sindhi', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('si', 'Singhalese', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ss', 'Siswati', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('sk', 'Slovak', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('sl', 'Slovenian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('so', 'Somali', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('es', 'Spanish', '         		', 'ISO-8859-15');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('su', 'Sundanese', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('sw', 'Swahili', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('sv', 'Swedish', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('tl', 'Tagalog', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('tg', 'Tajik', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ta', 'Tamil', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('tt', 'Tatar', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('te', 'Telugu', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('th', 'Thai', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('bo', 'Tibetan', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ti', 'Tigrinya', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('to', 'Tonga', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ts', 'Tsonga', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('tr', 'Turkish', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('tk', 'Turkmen', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('tw', 'Twi', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ug', 'Uigur', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('uk', 'Ukrainian', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('ur', 'Urdu', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('uz', 'Uzbek', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('vi', 'Vietnamese', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('vo', 'Volapuk', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('cy', 'Welsh', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('wo', 'Wolof', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('xh', 'Xhosa', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('yi', 'Yiddish', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('yo', 'Yoruba', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('za', 'Zhuang', '', 'ISO-8859-1');
INSERT INTO `cc_iso639` (`code`, `name`, `lname`, `charset`) VALUES('zu', 'Zulu', '', 'ISO-8859-1');

-- --------------------------------------------------------

--
-- Table structure for table `cc_logpayment`
--

CREATE TABLE IF NOT EXISTS `cc_logpayment` (
  `id` int(11) NOT NULL auto_increment,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `payment` decimal(15,5) NOT NULL,
  `card_id` bigint(20) NOT NULL,
  `id_logrefill` bigint(20) default NULL,
  `description` mediumtext collate utf8_bin,
  `added_refill` smallint(6) NOT NULL default '0',
  `payment_type` tinyint(4) NOT NULL default '0',
  `added_commission` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_logpayment`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_logpayment_agent`
--

CREATE TABLE IF NOT EXISTS `cc_logpayment_agent` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `payment` decimal(15,5) NOT NULL,
  `agent_id` bigint(20) NOT NULL,
  `id_logrefill` bigint(20) default NULL,
  `description` mediumtext collate utf8_bin,
  `added_refill` tinyint(4) NOT NULL default '0',
  `payment_type` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_logpayment_agent`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_logrefill`
--

CREATE TABLE IF NOT EXISTS `cc_logrefill` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `credit` decimal(15,5) NOT NULL,
  `card_id` bigint(20) NOT NULL,
  `description` mediumtext collate utf8_bin,
  `refill_type` tinyint(4) NOT NULL default '0',
  `added_invoice` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_logrefill`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_logrefill_agent`
--

CREATE TABLE IF NOT EXISTS `cc_logrefill_agent` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `credit` decimal(15,5) NOT NULL,
  `agent_id` bigint(20) NOT NULL,
  `description` mediumtext collate utf8_bin,
  `refill_type` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_logrefill_agent`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_notification`
--

CREATE TABLE IF NOT EXISTS `cc_notification` (
  `id` bigint(20) NOT NULL auto_increment,
  `key_value` varchar(40) collate utf8_bin default NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `priority` tinyint(4) NOT NULL default '0',
  `from_type` tinyint(4) NOT NULL,
  `from_id` bigint(20) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_notification`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_notification_admin`
--

CREATE TABLE IF NOT EXISTS `cc_notification_admin` (
  `id_notification` bigint(20) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `viewed` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id_notification`,`id_admin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `cc_notification_admin`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_outbound_cid_group`
--

CREATE TABLE IF NOT EXISTS `cc_outbound_cid_group` (
  `id` int(11) NOT NULL auto_increment,
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `group_name` varchar(70) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_outbound_cid_group`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_outbound_cid_list`
--

CREATE TABLE IF NOT EXISTS `cc_outbound_cid_list` (
  `id` int(11) NOT NULL auto_increment,
  `outbound_cid_group` int(11) NOT NULL,
  `cid` char(100) collate utf8_bin default NULL,
  `activated` int(11) NOT NULL default '0',
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_outbound_cid_list`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_package_group`
--

CREATE TABLE IF NOT EXISTS `cc_package_group` (
  `id` int(11) NOT NULL auto_increment,
  `name` char(30) collate utf8_bin NOT NULL,
  `description` mediumtext collate utf8_bin,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_package_group`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_package_offer`
--

CREATE TABLE IF NOT EXISTS `cc_package_offer` (
  `id` bigint(20) NOT NULL auto_increment,
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `label` varchar(70) collate utf8_bin NOT NULL,
  `packagetype` int(11) NOT NULL,
  `billingtype` int(11) NOT NULL,
  `startday` int(11) NOT NULL,
  `freetimetocall` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_package_offer`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_package_rate`
--

CREATE TABLE IF NOT EXISTS `cc_package_rate` (
  `package_id` int(11) NOT NULL,
  `rate_id` int(11) NOT NULL,
  PRIMARY KEY  (`package_id`,`rate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `cc_package_rate`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_packgroup_package`
--

CREATE TABLE IF NOT EXISTS `cc_packgroup_package` (
  `packagegroup_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  PRIMARY KEY  (`packagegroup_id`,`package_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `cc_packgroup_package`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_payments`
--

CREATE TABLE IF NOT EXISTS `cc_payments` (
  `id` bigint(20) NOT NULL auto_increment,
  `customers_id` bigint(20) NOT NULL default '0',
  `customers_name` varchar(200) collate utf8_bin NOT NULL,
  `customers_email_address` varchar(96) collate utf8_bin NOT NULL,
  `item_name` varchar(127) collate utf8_bin default NULL,
  `item_id` varchar(127) collate utf8_bin default NULL,
  `item_quantity` int(11) NOT NULL default '0',
  `payment_method` varchar(32) collate utf8_bin NOT NULL,
  `cc_type` varchar(20) collate utf8_bin default NULL,
  `cc_owner` varchar(64) collate utf8_bin default NULL,
  `cc_number` varchar(32) collate utf8_bin default NULL,
  `cc_expires` varchar(4) collate utf8_bin default NULL,
  `orders_status` int(5) NOT NULL,
  `orders_amount` decimal(14,6) default NULL,
  `last_modified` datetime default NULL,
  `date_purchased` datetime default NULL,
  `orders_date_finished` datetime default NULL,
  `currency` char(3) collate utf8_bin default NULL,
  `currency_value` decimal(14,6) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_payments`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_payments_agent`
--

CREATE TABLE IF NOT EXISTS `cc_payments_agent` (
  `id` bigint(20) NOT NULL auto_increment,
  `agent_id` bigint(20) NOT NULL,
  `agent_name` varchar(200) collate utf8_bin NOT NULL,
  `agent_email_address` varchar(96) collate utf8_bin NOT NULL,
  `item_name` varchar(127) collate utf8_bin default NULL,
  `item_id` varchar(127) collate utf8_bin default NULL,
  `item_quantity` int(11) NOT NULL default '0',
  `payment_method` varchar(32) collate utf8_bin NOT NULL,
  `cc_type` varchar(20) collate utf8_bin default NULL,
  `cc_owner` varchar(64) collate utf8_bin default NULL,
  `cc_number` varchar(32) collate utf8_bin default NULL,
  `cc_expires` varchar(4) collate utf8_bin default NULL,
  `orders_status` int(5) NOT NULL,
  `orders_amount` decimal(14,6) default NULL,
  `last_modified` datetime default NULL,
  `date_purchased` datetime default NULL,
  `orders_date_finished` datetime default NULL,
  `currency` char(3) collate utf8_bin default NULL,
  `currency_value` decimal(14,6) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_payments_agent`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_payments_status`
--

CREATE TABLE IF NOT EXISTS `cc_payments_status` (
  `id` int(11) NOT NULL auto_increment,
  `status_id` int(11) NOT NULL,
  `status_name` varchar(200) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=9 ;

--
-- Dumping data for table `cc_payments_status`
--

INSERT INTO `cc_payments_status` (`id`, `status_id`, `status_name`) VALUES(1, -2, 'Failed');
INSERT INTO `cc_payments_status` (`id`, `status_id`, `status_name`) VALUES(2, -1, 'Denied');
INSERT INTO `cc_payments_status` (`id`, `status_id`, `status_name`) VALUES(3, 0, 'Pending');
INSERT INTO `cc_payments_status` (`id`, `status_id`, `status_name`) VALUES(4, 1, 'In-Progress');
INSERT INTO `cc_payments_status` (`id`, `status_id`, `status_name`) VALUES(5, 2, 'Completed');
INSERT INTO `cc_payments_status` (`id`, `status_id`, `status_name`) VALUES(6, 3, 'Processed');
INSERT INTO `cc_payments_status` (`id`, `status_id`, `status_name`) VALUES(7, 4, 'Refunded');
INSERT INTO `cc_payments_status` (`id`, `status_id`, `status_name`) VALUES(8, 5, 'Unknown');

-- --------------------------------------------------------

--
-- Table structure for table `cc_payment_methods`
--

CREATE TABLE IF NOT EXISTS `cc_payment_methods` (
  `id` int(11) NOT NULL auto_increment,
  `payment_method` char(100) collate utf8_bin NOT NULL,
  `payment_filename` char(200) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

--
-- Dumping data for table `cc_payment_methods`
--

INSERT INTO `cc_payment_methods` (`id`, `payment_method`, `payment_filename`) VALUES(1, 'paypal', 'paypal.php');
INSERT INTO `cc_payment_methods` (`id`, `payment_method`, `payment_filename`) VALUES(3, 'MoneyBookers', 'moneybookers.php');
INSERT INTO `cc_payment_methods` (`id`, `payment_method`, `payment_filename`) VALUES(4, 'plugnpay', 'plugnpay.php');

-- --------------------------------------------------------

--
-- Table structure for table `cc_paypal`
--

CREATE TABLE IF NOT EXISTS `cc_paypal` (
  `id` int(11) NOT NULL auto_increment,
  `payer_id` varchar(50) collate utf8_bin default NULL,
  `payment_date` varchar(30) collate utf8_bin default NULL,
  `txn_id` varchar(30) collate utf8_bin default NULL,
  `first_name` varchar(40) collate utf8_bin default NULL,
  `last_name` varchar(40) collate utf8_bin default NULL,
  `payer_email` varchar(55) collate utf8_bin default NULL,
  `payer_status` varchar(30) collate utf8_bin default NULL,
  `payment_type` varchar(30) collate utf8_bin default NULL,
  `memo` tinytext collate utf8_bin,
  `item_name` varchar(70) collate utf8_bin default NULL,
  `item_number` varchar(70) collate utf8_bin default NULL,
  `quantity` int(11) NOT NULL default '0',
  `mc_gross` decimal(9,2) default NULL,
  `mc_fee` decimal(9,2) default NULL,
  `tax` decimal(9,2) default NULL,
  `mc_currency` char(3) collate utf8_bin default NULL,
  `address_name` varchar(50) collate utf8_bin NOT NULL default '',
  `address_street` varchar(80) collate utf8_bin NOT NULL default '',
  `address_city` varchar(40) collate utf8_bin NOT NULL default '',
  `address_state` varchar(40) collate utf8_bin NOT NULL default '',
  `address_zip` varchar(20) collate utf8_bin NOT NULL default '',
  `address_country` varchar(30) collate utf8_bin NOT NULL default '',
  `address_status` varchar(30) collate utf8_bin NOT NULL default '',
  `payer_business_name` varchar(40) collate utf8_bin NOT NULL default '',
  `payment_status` varchar(30) collate utf8_bin NOT NULL default '',
  `pending_reason` varchar(50) collate utf8_bin NOT NULL default '',
  `reason_code` varchar(30) collate utf8_bin NOT NULL default '',
  `txn_type` varchar(30) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `txn_id` (`txn_id`),
  KEY `txn_id_2` (`txn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_paypal`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_phonebook`
--

CREATE TABLE IF NOT EXISTS `cc_phonebook` (
  `id` int(11) NOT NULL auto_increment,
  `name` char(30) collate utf8_bin NOT NULL,
  `description` mediumtext collate utf8_bin,
  `id_card` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_phonebook`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_phonenumber`
--

CREATE TABLE IF NOT EXISTS `cc_phonenumber` (
  `id` bigint(20) NOT NULL auto_increment,
  `id_phonebook` int(11) NOT NULL,
  `number` char(30) collate utf8_bin NOT NULL,
  `name` char(40) collate utf8_bin default NULL,
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `status` smallint(6) NOT NULL default '1',
  `info` mediumtext collate utf8_bin,
  `amount` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_phonenumber`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_prefix`
--

CREATE TABLE IF NOT EXISTS `cc_prefix` (
  `prefix` bigint(20) NOT NULL auto_increment,
  `destination` varchar(60) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`prefix`),
  KEY `destination` (`destination`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=998795791 ;

--
-- Dumping data for table `cc_prefix`
--

INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(93, 'Afghanistan');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9370, 'Afghanistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9375, 'Afghanistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9377, 'Afghanistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9378, 'Afghanistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9379, 'Afghanistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(355, 'Albania');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35567, 'Albania Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35568, 'Albania Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35569, 'Albania Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(213, 'Algeria');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2135, 'Algeria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2136, 'Algeria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2137, 'Algeria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2139, 'Algeria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1684, 'American Samoa');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(684, 'American Samoa');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1684733, 'American Samoa Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(376, 'Andorra');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3763, 'Andorra Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3764, 'Andorra Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3766, 'Andorra Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(244, 'Angola');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24491, 'Angola Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24492, 'Angola Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1264, 'Anguilla');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1264235, 'Anguilla Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1264469, 'Anguilla Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1264476, 'Anguilla Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1264536, 'Anguilla Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1264537, 'Anguilla Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1264538, 'Anguilla Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1264539, 'Anguilla Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1264581, 'Anguilla Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1264582, 'Anguilla Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1264583, 'Anguilla Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1264584, 'Anguilla Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1264724, 'Anguilla Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1264729, 'Anguilla Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1264772, 'Anguilla Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67210, 'Antarctica');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67211, 'Antarctica');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67212, 'Antarctica');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67213, 'Antarctica');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268, 'Antigua and Barbuda');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268406, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268409, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268464, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268720, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268721, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268722, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268723, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268724, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268725, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268726, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268727, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268728, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268729, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268764, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268770, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268771, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268772, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268773, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268774, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268775, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268779, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268780, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268781, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268782, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268783, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268784, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268785, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268786, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1268788, 'Antigua and Barbuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(54, 'Argentina');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(549, 'Argentina Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(374, 'Armenia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(37477, 'Armenia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3749, 'Armenia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(297, 'Aruba');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29756, 'Aruba Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29759, 'Aruba Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29773, 'Aruba Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29774, 'Aruba Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29796, 'Aruba Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29799, 'Aruba Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(247, 'Ascension Islands');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(61, 'Australia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(61145, 'Australia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(61147, 'Australia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6116, 'Australia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(614, 'Australia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(43, 'Austria');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(43644, 'Austria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(43650, 'Austria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(43660, 'Austria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(43664, 'Austria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(43676, 'Austria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(43677, 'Austria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(43678, 'Austria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(43680, 'Austria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(43681, 'Austria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(43688, 'Austria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(43699, 'Austria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(994, 'Azerbaijan');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99440, 'Azerbaijan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99450, 'Azerbaijan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99451, 'Azerbaijan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99455, 'Azerbaijan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99470, 'Azerbaijan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242, 'Bahamas');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242357, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242359, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242375, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242395, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242422, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242423, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242424, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242425, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242426, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242427, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242434, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242436, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242441, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242442, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242454, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242455, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242456, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242457, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242464, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242465, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242466, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242467, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242468, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242475, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242477, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242524, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242525, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242533, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242535, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242544, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242551, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242552, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242553, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242554, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242556, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242557, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242558, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242559, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242565, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242577, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242636, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242646, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1242727, 'Bahamas Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(973, 'Bahrain');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9733, 'Bahrain Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(880, 'Bangladesh');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(8801, 'Bangladesh Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1246, 'Barbados');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(124623, 'Barbados Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(124624, 'Barbados Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(124625, 'Barbados Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(124626, 'Barbados Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1246446, 'Barbados Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1246447, 'Barbados Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1246448, 'Barbados Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1246449, 'Barbados Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(124645, 'Barbados Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(124652, 'Barbados Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1246820, 'Barbados Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1246821, 'Barbados Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1246822, 'Barbados Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1246823, 'Barbados Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1246824, 'Barbados Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1246825, 'Barbados Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1246826, 'Barbados Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1246827, 'Barbados Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1246828, 'Barbados Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1246829, 'Barbados Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(375, 'Belarus');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(375259, 'Belarus Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(37529, 'Belarus Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(37533, 'Belarus Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(37544, 'Belarus Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(32, 'Belgium');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(32484, 'Belgium [Base]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(32485, 'Belgium [Base]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(32486, 'Belgium [Base]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(32487, 'Belgium [Base]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(32488, 'Belgium [Base]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(32494, 'Belgium [Mobistar]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(32495, 'Belgium [Mobistar]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(32496, 'Belgium [Mobistar]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(32497, 'Belgium [Mobistar]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(32498, 'Belgium [Mobistar]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(32499, 'Belgium [Mobistar]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(32472, 'Belgium [Proximus]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(32473, 'Belgium [Proximus]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(32474, 'Belgium [Proximus]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(32475, 'Belgium [Proximus]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(32476, 'Belgium [Proximus]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(32477, 'Belgium [Proximus]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(32478, 'Belgium [Proximus]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(32479, 'Belgium [Proximus]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3245, 'Belgium Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3247, 'Belgium Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3248, 'Belgium Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3249, 'Belgium Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(501, 'Belize');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5016, 'Belize Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(229, 'Benin');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22990, 'Benin Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22991, 'Benin Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22992, 'Benin Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22993, 'Benin Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22995, 'Benin Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22996, 'Benin Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22997, 'Benin Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1441, 'Bermuda');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(14413, 'Bermuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(144150, 'Bermuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(144151, 'Bermuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(144152, 'Bermuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(144153, 'Bermuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1441590, 'Bermuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1441599, 'Bermuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(14417, 'Bermuda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(975, 'Bhutan');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97517, 'Bhutan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(591, 'Bolivia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5917, 'Bolivia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(387, 'Bosnia-Herzegovina');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3876, 'Bosnia-Herzegovina Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(267, 'Botswana');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26771, 'Botswana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26772, 'Botswana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26773, 'Botswana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26774, 'Botswana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55, 'Brazil');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55117, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55118, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55119, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551276, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551278, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551281, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551282, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551283, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551284, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551285, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551286, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551287, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551289, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55129, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551376, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551378, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551381, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551382, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551383, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551384, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551385, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551386, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551387, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551389, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55139, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551476, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551478, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551481, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551482, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551483, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551484, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551485, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551486, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551487, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551489, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55149, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551576, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551578, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551581, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551582, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551583, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551584, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551585, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551586, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551587, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551589, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55159, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55167, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551681, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551682, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551683, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551684, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551685, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551686, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551687, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551689, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55169, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55177, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551781, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551782, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551783, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551784, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551785, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551786, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551787, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551789, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55179, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551876, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551878, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551881, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551882, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551883, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551884, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551885, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551886, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551887, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551889, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55189, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551976, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(551978, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55198, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55199, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55217, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55218, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55219, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(552278, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55228, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55229, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(552478, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55248, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55249, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(552778, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55278, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55279, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(552878, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(552881, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(552882, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(552883, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(552885, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(552886, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(552887, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(552888, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55289, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553178, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55318, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55319, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553278, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553284, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553285, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553286, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553287, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553288, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55329, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553378, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553384, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553385, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553386, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553387, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553388, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55339, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553478, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553484, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553485, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553486, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553487, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553488, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55349, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553578, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553584, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553585, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553586, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553587, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553588, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55359, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553778, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553784, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553785, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553786, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553787, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553788, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55379, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553878, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553884, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553885, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553886, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553887, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(553888, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55389, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554170, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554178, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554184, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554185, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554188, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55419, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55427, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554284, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554285, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554288, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55429, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554378, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554381, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554384, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554385, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554388, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55439, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554478, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554484, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554485, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554488, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55449, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554578, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554584, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554585, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554588, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55459, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554678, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554684, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554685, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554688, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55469, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55477, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554784, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554785, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554788, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55479, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55487, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554881, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554884, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554885, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554888, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55489, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554978, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554984, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554985, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(554988, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55499, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(555178, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55518, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55519, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(555378, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(555381, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(555382, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(555384, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(555385, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(555389, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55539, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(555478, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(555481, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(555482, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(555484, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(555485, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(555489, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55549, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(555578, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(555581, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(555582, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(555584, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(555585, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(555589, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55559, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556178, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556181, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556182, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556184, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556185, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556189, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55619, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55627, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55628, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55629, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556378, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556381, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556382, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556384, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556385, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556389, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55639, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556478, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556481, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556482, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556484, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556485, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556489, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55649, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556578, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55658, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55659, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556678, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556681, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556682, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556684, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556685, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556688, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556689, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55669, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556778, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556781, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556782, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556784, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556785, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556788, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556789, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55679, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556878, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55688, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55689, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556978, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556981, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556982, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556984, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556985, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556988, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(556989, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55699, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557178, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55718, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55719, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557378, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557381, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557382, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557385, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557386, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557387, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557388, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55739, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557478, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557481, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557482, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557485, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557486, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557487, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557488, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55749, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557578, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557581, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557582, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557585, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557586, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557587, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557588, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55759, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557778, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557781, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557782, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557785, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557786, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557787, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557788, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55779, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557978, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557981, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557982, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557985, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557986, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557987, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(557988, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55799, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55818, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55819, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(558285, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(558286, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(558287, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(558288, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55829, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55838, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55839, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55848, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55849, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55858, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55859, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(558685, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(558686, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(558687, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(558688, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55869, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(558785, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(558786, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(558787, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(558788, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55879, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(558885, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(558886, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(558887, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(558888, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55889, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(558985, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(558986, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(558987, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(558988, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55899, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55918, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55919, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55928, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55929, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559381, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559382, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559383, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559385, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559386, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559387, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559388, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559389, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55939, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559481, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559482, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559483, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559485, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559486, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559487, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559488, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559489, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55949, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55958, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55959, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55968, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55969, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559781, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559782, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559783, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559785, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559786, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559787, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559788, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55979, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559881, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559882, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559883, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559885, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559886, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559887, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559888, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(559889, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55989, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55998, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(55999, 'Brazil Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(246, 'British Indian Ocean Territory');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1284, 'British Virgin Islands');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(12843, 'British Virgin Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1284301, 'British Virgin Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1284302, 'British Virgin Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1284303, 'British Virgin Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1284440, 'British Virgin Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1284441, 'British Virgin Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1284442, 'British Virgin Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1284443, 'British Virgin Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1284444, 'British Virgin Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1284445, 'British Virgin Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1284468, 'British Virgin Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(12844966, 'British Virgin Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(12844967, 'British Virgin Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(12844968, 'British Virgin Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(12844969, 'British Virgin Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1284499, 'British Virgin Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1284540, 'British Virgin Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1284541, 'British Virgin Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1284542, 'British Virgin Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1284543, 'British Virgin Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1284544, 'British Virgin Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(673, 'Brunei Darussalam');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6738, 'Brunei Darussalam Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(359, 'Bulgaria');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(359430, 'Bulgaria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(359437, 'Bulgaria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(359438, 'Bulgaria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(359439, 'Bulgaria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35948, 'Bulgaria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35987, 'Bulgaria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35988, 'Bulgaria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35989, 'Bulgaria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35998, 'Bulgaria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35999, 'Bulgaria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(226, 'Burkina Faso');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22670, 'Burkina Faso Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22675, 'Burkina Faso Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22676, 'Burkina Faso Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22678, 'Burkina Faso Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(257, 'Burundi');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2572955, 'Burundi Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25776, 'Burundi Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25777, 'Burundi Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25778, 'Burundi Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25779, 'Burundi Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(855, 'Cambodia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(8551, 'Cambodia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(85589, 'Cambodia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(8559, 'Cambodia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(237, 'Cameroon');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2377, 'Cameroon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2379, 'Cameroon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1204, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1226, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1250, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1289, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1306, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1403, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1416, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1418, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1438, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1450, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1506, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1514, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1519, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1581, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1587, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(16, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1604, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1613, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1647, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1705, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1709, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1778, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1780, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1807, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1819, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1867, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1871, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1902, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1905, 'Canada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(238, 'Cape Verde');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23891, 'Cape Verde Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23897, 'Cape Verde Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23898, 'Cape Verde Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23899, 'Cape Verde Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345, 'Cayman Islands');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345229, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345321, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345322, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345323, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345324, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345325, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345326, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345327, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345328, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345329, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345516, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345517, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345525, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345526, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345527, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345547, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345548, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345916, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345917, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345919, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345924, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345925, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345926, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345927, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345928, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345929, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345930, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345938, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1345939, 'Cayman Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(236, 'Central African Republic');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23670, 'Central African Republic Mobil');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23672, 'Central African Republic Mobil');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23675, 'Central African Republic Mobil');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23677, 'Central African Republic Mobil');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(235, 'Chad');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2352, 'Chad Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23530, 'Chad Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23531, 'Chad Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23532, 'Chad Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23533, 'Chad Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23534, 'Chad Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23535, 'Chad Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2356, 'Chad Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2357, 'Chad Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2359, 'Chad Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(56, 'Falkland Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(568, 'Chile Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(569, 'Chile Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(86, 'China');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(8613, 'China Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(8615, 'China Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(86189, 'China Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(57, 'Colombia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(57310, 'Colombia [Comcel]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(57311, 'Colombia [Comcel]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(57312, 'Colombia [Comcel]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(57313, 'Colombia [Comcel]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(57314, 'Colombia [Comcel]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(57315, 'Colombia [Movistar]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(57316, 'Colombia [Movistar]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(57317, 'Colombia [Movistar]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(57318, 'Colombia [Movistar]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(573, 'Colombia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(57301, 'Colombia [Ola]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(57304, 'Colombia [Ola]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(269, 'Comoros');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2693, 'Comoros Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(242, 'Congo');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(243, 'Congo Democratic Republic');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24322, 'Congo Democratic Republic Mobi');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24378, 'Congo Democratic Republic Mobi');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24380, 'Congo Democratic Republic Mobi');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24381, 'Congo Democratic Republic Mobi');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24384, 'Congo Democratic Republic Mobi');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24385, 'Congo Democratic Republic Mobi');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24388, 'Congo Democratic Republic Mobi');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24389, 'Congo Democratic Republic Mobi');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24397, 'Congo Democratic Republic Mobi');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24398, 'Congo Democratic Republic Mobi');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24399, 'Congo Democratic Republic Mobi');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2424, 'Congo Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2425, 'Congo Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2426, 'Congo Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(682, 'Cook Islands');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68250, 'Cook Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68251, 'Cook Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68252, 'Cook Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68253, 'Cook Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68254, 'Cook Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68255, 'Cook Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68256, 'Cook Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68258, 'Cook Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6827, 'Cook Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(506, 'Costa Rica');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5068, 'Costa Rica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(385, 'Croatia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38591, 'Croatia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38592, 'Croatia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38595, 'Croatia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38597, 'Croatia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38598, 'Croatia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38599, 'Croatia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(53, 'Cuba');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5352, 'Cuba Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5358, 'Cuba Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(537750, 'Cuba Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(537751, 'Cuba Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(537752, 'Cuba Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(537753, 'Cuba Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(537754, 'Cuba Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(537755, 'Cuba Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(537756, 'Cuba Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(537758, 'Cuba Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(357, 'Cyprus');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35796, 'Cyprus Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(357976, 'Cyprus Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(357977, 'Cyprus Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35799, 'Cyprus Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(420, 'Czech Republic');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(42060, 'Czech Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(42072, 'Czech Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(42073, 'Czech Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(42077, 'Czech Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(42079, 'Czech Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(42093, 'Czech Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(42096, 'Czech Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45, 'Denmark');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(452, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4530, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4531, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4540, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45411, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45412, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45413, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45414, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45415, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45416, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45417, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45418, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45419, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45420, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45421, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45422, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45423, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45424, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45425, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454260, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454270, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454276, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454277, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454278, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454279, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454280, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454281, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454282, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454283, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454284, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454285, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454286, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454287, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454288, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454289, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454290, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454291, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454292, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454293, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454294, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454295, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454296, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454297, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454298, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(454299, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4550, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4551, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455210, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455211, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455212, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455213, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455214, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455215, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455216, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455217, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455218, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455219, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455220, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455221, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455222, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455223, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455224, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455225, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455226, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455227, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455228, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455229, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455230, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455231, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455232, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455233, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455234, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455235, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455236, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455237, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455238, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455239, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455240, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455241, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455242, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455243, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455244, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455245, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455246, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455247, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455249, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455250, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455252, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455253, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455255, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455258, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455260, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455262, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455266, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455270, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455271, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455272, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455273, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455274, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455275, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455276, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455277, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455280, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455282, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455288, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455290, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455292, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455299, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455310, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455311, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455312, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455314, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455315, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455316, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455317, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455318, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455319, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45532, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455330, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455331, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455332, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455333, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455334, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455335, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455336, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455337, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455338, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(455339, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45534, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45535, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45536, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45537, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45538, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(45539, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4560, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4561, 'Denmark Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(253, 'Rwanda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2536, 'Djibouti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25380, 'Djibouti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25381, 'Djibouti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25382, 'Djibouti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25383, 'Djibouti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25384, 'Djibouti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25385, 'Djibouti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25386, 'Djibouti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25387, 'Djibouti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1767, 'Dominica');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1767225, 'Dominica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1767235, 'Dominica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1767245, 'Dominica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1767265, 'Dominica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1767275, 'Dominica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1767276, 'Dominica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1767277, 'Dominica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1767315, 'Dominica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1767316, 'Dominica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1767317, 'Dominica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1767611, 'Dominica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1767612, 'Dominica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1767613, 'Dominica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1767614, 'Dominica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1767615, 'Dominica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1767616, 'Dominica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1767617, 'Dominica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809, 'Dominican Republic');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829, 'Dominican Republic');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809201, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809203, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809204, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809205, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809206, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809207, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809208, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809209, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809210, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809212, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809214, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809215, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809216, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809217, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809218, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809219, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18092223, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18092224, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18092225, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809223, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809224, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809225, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809228, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809229, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809230, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809232, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809235, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18092480, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18092482, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18092484, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18092485, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18092486, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18092487, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18092488, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809249, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809250, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809251, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809252, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809253, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809254, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809256, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809257, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809258, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809259, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809260, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809264, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18092651, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18092652, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18092653, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18092654, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18092655, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18092656, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18092657, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18092658, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18092659, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809266, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809267, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809268, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809269, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809270, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809271, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809272, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809280, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809281, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809282, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809283, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809284, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809292, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809293, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809297, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809298, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809299, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809301, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809302, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809303, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809304, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809305, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809306, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809307, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809308, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809309, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809310, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809313, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809315, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809316, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809317, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809318, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809319, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809321, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809322, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809323, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809324, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809325, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809326, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809327, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809330, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809340, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809341, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809342, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809343, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809344, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809345, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809348, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809350, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809351, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809352, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809353, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809354, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809355, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809356, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809357, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809358, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809359, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809360, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809361, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809366, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809370, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809371, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809374, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809376, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809377, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809383, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809386, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809387, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809389, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809390, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809391, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809392, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809393, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809394, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809395, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809396, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809397, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809398, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809399, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809401, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809402, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809403, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809404, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809405, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809406, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809407, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809408, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809409, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809410, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809413, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809415, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809416, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809417, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809418, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809419, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809420, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809421, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809423, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809424, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809425, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809426, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809427, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809428, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809429, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809430, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809431, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809432, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809433, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809434, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809436, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809437, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809438, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809439, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809440, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809441, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809442, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809443, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809444, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809445, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809446, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809447, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809448, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809449, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809451, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809452, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809453, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809454, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809456, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809457, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809458, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809459, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809460, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809461, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809462, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809463, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809464, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809465, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809467, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18094701, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18094702, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18094703, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18094704, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18094705, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18094706, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18094707, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18094708, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809474, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809475, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809477, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809478, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809479, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809481, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809484, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809485, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809486, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809488, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809490, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809491, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809492, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809493, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809494, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809495, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809496, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809497, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809498, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809499, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809501, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809502, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809504, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809505, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809506, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809507, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809509, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809510, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809512, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809513, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809514, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809515, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809516, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809517, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809519, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809520, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(180954290, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(180954291, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(180954292, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(180954293, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(180954295, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(180954296, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(180954297, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(180954298, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809543, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18095450, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18095451, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18095454, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18095456, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18095459, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809546, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809601, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809602, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809603, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809604, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809605, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809606, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809607, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809608, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809609, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809610, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809613, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809614, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809615, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809617, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809618, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809619, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809624, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809627, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809628, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809629, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809630, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809631, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809632, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809634, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809635, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809636, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809637, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809639, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809640, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809641, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809642, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809643, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809644, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809645, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809646, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809647, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809648, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809649, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809650, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809651, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809652, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809653, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809654, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809656, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809657, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809658, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809659, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809660, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809661, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809662, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809663, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809664, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809665, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809666, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809667, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809668, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809669, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809670, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809671, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809672, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809673, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809674, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809675, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809676, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809677, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809678, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809693, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809694, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809696, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809697, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809698, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809702, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809703, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809704, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809705, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809706, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809707, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809708, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809709, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809710, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809712, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809713, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809714, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809715, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809716, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809717, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809718, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809719, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809720, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809721, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809722, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809723, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809727, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809729, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18097421, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18097422, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809743, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809747, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809749, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809750, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809751, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809752, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809753, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809754, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809756, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809757, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809758, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809759, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809760, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809761, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809762, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809763, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809764, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809765, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809767, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809768, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809769, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809771, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809772, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809773, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809774, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809775, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809776, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809777, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809778, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809779, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809780, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809781, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809782, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809783, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809785, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809786, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809787, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809789, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809790, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809791, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809796, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809798, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809801, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809802, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809803, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809804, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809805, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809806, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809807, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809808, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809810, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809812, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809814, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809815, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809816, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809817, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809818, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809819, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809820, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809821, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809827, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809828, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809829, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809834, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809835, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809836, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809837, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809838, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809839, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809840, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809841, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809842, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809843, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809844, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809845, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809846, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809847, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809848, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809849, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809850, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809851, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809852, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809853, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809854, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809855, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809856, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809857, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809858, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18098597, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18098598, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(180985990, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(180985991, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(180985992, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(180985993, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(180985994, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(180985995, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(180985996, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(180985997, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809860, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809861, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809862, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809863, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809864, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809865, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809866, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809867, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809868, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809869, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809871, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809873, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809874, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809875, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809876, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809877, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809878, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809879, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809880, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809881, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809882, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809883, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809884, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809885, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809886, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809888, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809889, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809890, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809891, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809899, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809901, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809902, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809903, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809904, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809905, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809906, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809907, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809908, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809909, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809910, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809912, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809913, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809914, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809915, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809916, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809917, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809918, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809919, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809923, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809924, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809928, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809929, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809931, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809932, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809935, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809938, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809939, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809940, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809941, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809942, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809943, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809944, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809945, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809946, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809949, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809952, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809953, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809956, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809958, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809961, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809962, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809963, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809964, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809965, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809966, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809967, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809968, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809969, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809972, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809973, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809974, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809975, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809977, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809978, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809979, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809980, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809981, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809982, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809983, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809984, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809986, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809988, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809989, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809990, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809991, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809992, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809993, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809994, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809995, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809996, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809997, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809998, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1809999, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829201, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829202, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829203, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829204, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829205, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829206, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829207, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829208, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829209, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829210, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829212, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829214, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829215, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829221, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829222, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829230, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829232, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829233, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829248, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829250, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829252, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829255, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829257, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829258, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829259, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829260, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829261, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829262, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829263, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829264, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829265, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829266, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829267, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829268, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829269, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829270, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829271, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829272, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829273, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829274, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829275, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829276, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829277, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829278, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829279, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829280, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829281, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829282, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829283, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829284, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829285, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829286, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829287, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829288, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829290, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829296, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829297, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829298, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829299, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829301, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829303, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829304, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829305, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829306, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829307, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829308, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829309, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829313, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829314, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829315, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829316, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829317, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829318, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829319, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829320, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829321, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829322, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829323, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829328, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829329, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829330, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829331, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829332, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829333, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829334, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829335, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829336, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829337, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829338, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829339, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829340, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829341, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829342, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829343, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829344, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829345, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829346, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829347, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829348, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829349, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829350, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829351, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829352, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829353, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829354, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829355, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829356, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829357, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829358, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829359, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829360, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829361, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829362, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829363, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829364, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829365, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829366, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829367, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829368, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829369, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829370, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829371, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829372, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829373, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829375, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829376, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829377, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829379, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829380, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829383, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829386, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829387, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829388, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829389, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829390, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829392, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829393, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829394, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829395, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829396, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829397, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829398, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829399, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829401, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829402, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829403, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829404, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829405, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829406, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829407, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829408, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829409, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829410, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829412, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829413, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829414, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829415, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829416, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829417, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829422, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829424, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829425, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829426, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829427, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829428, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829429, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829430, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829432, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829440, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829441, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829442, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829443, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829444, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829445, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829446, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829447, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829448, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829449, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829450, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829451, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829452, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829453, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829454, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829456, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829465, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829470, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829471, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829472, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829474, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829543, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829601, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829602, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829603, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829604, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829605, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829610, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829613, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829616, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829630, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829633, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829640, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829644, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829646, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829650, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829653, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829654, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829655, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829657, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829660, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829661, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829662, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829663, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829664, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829665, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829667, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829668, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829669, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829676, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829677, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829678, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829686, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829696, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829697, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829699, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829701, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829702, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829703, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829704, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829705, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829706, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829707, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829709, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829710, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829712, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829713, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829714, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829715, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829716, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829717, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829718, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829719, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829720, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829721, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829722, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829723, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829725, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829726, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829727, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829728, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829729, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829730, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829731, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829740, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829744, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829747, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829750, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829754, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829755, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829757, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829760, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829766, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829770, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829777, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829779, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829780, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829787, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829788, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829790, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829797, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829799, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829801, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829802, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829803, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829804, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829805, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829806, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829807, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829808, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829810, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829815, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829816, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829817, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829818, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829819, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829820, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829826, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829830, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829838, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829845, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829846, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829847, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829848, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829849, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829850, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829851, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829852, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829853, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829854, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829855, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829856, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829857, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829858, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829859, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829860, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829861, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829862, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829863, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829864, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829865, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829866, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829867, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829868, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829869, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829870, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829873, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829875, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829876, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829877, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829878, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829879, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829880, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829881, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829882, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829883, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829884, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829885, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829886, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829887, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829889, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829890, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829891, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829892, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829898, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829899, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829901, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829902, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829903, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829904, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829905, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829906, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829907, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829908, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829909, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829910, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829912, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829913, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829914, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829915, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829916, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829917, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829918, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829919, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829920, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829921, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829922, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829923, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829924, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829925, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829926, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829929, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829930, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829931, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829933, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829935, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829939, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829958, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829961, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829962, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829963, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829964, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829965, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829966, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829967, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829968, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829969, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829970, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829972, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829973, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829974, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829975, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829977, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829978, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829979, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829980, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829981, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829982, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829983, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829984, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829986, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829990, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829991, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829993, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1829994, 'Dominican Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(670, 'East Timor');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67071, 'East Timor Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67072, 'East Timor Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67073, 'East Timor Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67079, 'East Timor Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(593, 'Ecuador');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5938, 'Ecuador Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5939, 'Ecuador Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(20, 'Egypt');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2010, 'Egypt Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2011, 'Egypt Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2012, 'Egypt Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2016, 'Egypt Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2017, 'Egypt Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2018, 'Egypt Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2019, 'Egypt Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(503, 'El Salvador');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5037, 'El Salvador Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5038, 'El Salvador Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(240, 'Equatorial Guinea');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2402, 'Equatorial Guinea Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2405, 'Equatorial Guinea Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2406, 'Equatorial Guinea Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(291, 'Eritrea');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(291171, 'Eritrea Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(291172, 'Eritrea Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(291173, 'Eritrea Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2917, 'Eritrea Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(372, 'Estonia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3725, 'Estonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(251, 'Ethiopia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25191, 'Ethiopia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(251958, 'Ethiopia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(251959, 'Ethiopia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25198, 'Ethiopia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(298, 'Faeroes Islands');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29821, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29822, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29823, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29824, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29825, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29826, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29827, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29828, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29829, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2985, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29871, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29872, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29873, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29874, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29875, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29876, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29877, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29879, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29891, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29892, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29893, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29894, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29895, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29896, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29897, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29898, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29899, 'Faeroes Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5, 'Falkland Islands');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(679, 'Fiji');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67970, 'Fiji Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67971, 'Fiji Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67972, 'Fiji Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67973, 'Fiji Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67974, 'Fiji Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67983, 'Fiji Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67984, 'Fiji Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6799, 'Fiji Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(358, 'Finland');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3584, 'Finland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35850, 'Finland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33, 'France');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33650, 'France [Bouygues Telecom]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33653, 'France [Bouygues Telecom]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33659, 'France [Bouygues Telecom]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33660, 'France [Bouygues Telecom]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33661, 'France [Bouygues Telecom]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33662, 'France [Bouygues Telecom]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33663, 'France [Bouygues Telecom]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33664, 'France [Bouygues Telecom]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33665, 'France [Bouygues Telecom]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33666, 'France [Bouygues Telecom]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33667, 'France [Bouygues Telecom]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33668, 'France [Bouygues Telecom]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33669, 'France [Bouygues Telecom]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33698, 'France [Bouygues Telecom]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33699, 'France [Bouygues Telecom]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33607, 'France [Orange]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33608, 'France [Orange]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33630, 'France [Orange]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33631, 'France [Orange]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33632, 'France [Orange]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33633, 'France [Orange]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33637, 'France [Orange]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33642, 'France [Orange]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33643, 'France [Orange]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33645, 'France [Orange]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33654, 'France [Orange]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3367, 'France [Orange]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33670, 'France [Orange]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3368, 'France [Orange]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33603, 'France [SFR]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33605, 'France [SFR]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33609, 'France [SFR]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3361, 'France [SFR]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3362, 'France [SFR]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33620, 'France [SFR]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33621, 'France [SFR]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33622, 'France [SFR]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33623, 'France [SFR]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33624, 'France [SFR]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33625, 'France [SFR]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33626, 'France [SFR]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33627, 'France [SFR]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33628, 'France [SFR]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33629, 'France [SFR]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33634, 'France [SFR]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33635, 'France [SFR]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33636, 'France [SFR]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33641, 'France [SFR]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33655, 'France [SFR]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(336, 'France Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33594, 'French Guiana');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(594, 'French Guiana');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(594694, 'French Guiana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(689, 'French Polynesia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6892, 'French Polynesia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68930, 'French Polynesia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68931, 'French Polynesia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6897, 'French Polynesia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(241, 'Gabon');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24103, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24104, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24105, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24106, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24107, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24108, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24109, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24110, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24111, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24114, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24115, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24120, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24121, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24122, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24123, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24124, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24125, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24126, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24127, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24128, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24129, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24130, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24131, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24132, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24133, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24134, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24135, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24136, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24137, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24138, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24139, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24141, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24151, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24152, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24153, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24157, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24161, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24163, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24168, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24175, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24180, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24181, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24184, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24185, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24187, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24189, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24191, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24194, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24195, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24197, 'Gabon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(220, 'Gambia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2206, 'Gambia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2207, 'Gambia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2209, 'Gambia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(995, 'Georgia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99555, 'Georgia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99557, 'Georgia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99558, 'Georgia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99577, 'Georgia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99590, 'Georgia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99591, 'Georgia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99593, 'Georgia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99595, 'Georgia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99597, 'Georgia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99598, 'Georgia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99599, 'Georgia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(49, 'Germany');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(49150, 'Germany Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(49151, 'Germany Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(49152, 'Germany Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(49155, 'Germany Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(49156, 'Germany Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(49157, 'Germany Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(49159, 'Germany Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(49160, 'Germany Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(49162, 'Germany Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(49163, 'Germany Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(49170, 'Germany Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(49171, 'Germany Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(49172, 'Germany Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(49173, 'Germany Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(49174, 'Germany Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(49175, 'Germany Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(49176, 'Germany Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(49177, 'Germany Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(49178, 'Germany Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(49179, 'Germany Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(233, 'Ghana');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23320, 'Ghana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2332170, 'Ghana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2332260, 'Ghana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23324, 'Ghana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23327, 'Ghana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23328, 'Ghana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(350, 'Gibraltar');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35054, 'Gibraltar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35056, 'Gibraltar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35057, 'Gibraltar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35058, 'Gibraltar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35060, 'Gibraltar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(30, 'Greece');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3069, 'Greece Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(299, 'Greenland');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2992, 'Greenland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29942, 'Greenland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29946, 'Greenland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29947, 'Greenland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29948, 'Greenland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29949, 'Greenland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29950, 'Greenland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29952, 'Greenland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29953, 'Greenland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29954, 'Greenland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29955, 'Greenland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29956, 'Greenland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29957, 'Greenland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29958, 'Greenland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(29959, 'Greenland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473, 'Grenada');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473403, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473404, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473405, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473406, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473407, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473409, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473410, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473414, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473415, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473416, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473417, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473418, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473419, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473420, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473456, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473457, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473458, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473459, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473533, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473534, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473535, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473536, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473537, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1473538, 'Grenada Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33590, 'Guadeloupe');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(590, 'Guadeloupe');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(590690, 'Guadeloupe Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1671, 'Guam');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(502, 'Guatemala');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5024, 'Guatemala Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5025, 'Guatemala Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(224, 'Guinea');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22460, 'Guinea Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22462, 'Guinea Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22463, 'Guinea Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22464, 'Guinea Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22465, 'Guinea Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22467, 'Guinea Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(245, 'Guinea-Bissau');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2455, 'Guinea-Bissau Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2456, 'Guinea-Bissau Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2457, 'Guinea-Bissau Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592, 'Guyana');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592214, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592224, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592248, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592278, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592284, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592294, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592304, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592374, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592384, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592394, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5926, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592601, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592602, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592603, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592604, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592609, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592610, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592611, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592612, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592613, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592614, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592616, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592617, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(59262, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592630, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592633, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592634, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592635, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592638, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592639, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(59264, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592650, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592651, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592652, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592653, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592654, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592655, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592656, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592657, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(592658, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(59266, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(59267, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(59268, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(59269, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5928, 'Guyana Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(509, 'Haiti');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5093, 'Haiti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5094, 'Haiti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(509561, 'Haiti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(509562, 'Haiti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(509563, 'Haiti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(509564, 'Haiti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(509565, 'Haiti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5096, 'Haiti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5097, 'Haiti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(50981, 'Haiti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(50982, 'Haiti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(50983, 'Haiti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(50990, 'Haiti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(50991, 'Haiti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(50992, 'Haiti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(50993, 'Haiti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(50994, 'Haiti Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(504, 'Honduras');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5043, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5047214, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5047215, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5047217, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(504881, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(504882, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(504883, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(504884, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(504885, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(504886, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(504887, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(504888, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(504889, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(504890, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(504891, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(504892, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(504893, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(504894, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(504895, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(504896, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(504897, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(504898, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(504899, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5049, 'Honduras Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(852, 'Hong Kong');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(85217, 'Hong Kong Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(85251, 'Hong Kong Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(85253, 'Hong Kong Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(85254, 'Hong Kong Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(85256, 'Hong Kong Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(85259, 'Hong Kong Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(8526, 'Hong Kong Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(8529, 'Hong Kong Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(36, 'Hungary');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3620, 'Hungary Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3630, 'Hungary Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3650, 'Hungary Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3660, 'Hungary Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3670, 'Hungary Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354, 'Iceland');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354373, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354374, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354380, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354388, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354389, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354610, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354615, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354616, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354617, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35462, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354630, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354631, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354632, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354637, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354638, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354639, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354640, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354641, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354642, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354649, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354650, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354652, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354655, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354659, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35466, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35467, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35468, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35469, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354770, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354771, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354772, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354773, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35482, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35483, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35484, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35485, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35486, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35487, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35488, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35489, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354954, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(354958, 'Iceland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(91, 'India');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9190, 'India Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9191, 'India Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9192, 'India Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9193, 'India Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9194, 'India Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9196, 'India Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9197, 'India Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9198, 'India Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9199, 'India Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(62, 'Indonesia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(628, 'Indonesia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(98, 'Iran');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(989, 'Iran Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(964, 'Iraq');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9647, 'Iraq Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(353, 'Ireland');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(353821, 'Ireland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(353822, 'Ireland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35383, 'Ireland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35385, 'Ireland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35386, 'Ireland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35387, 'Ireland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35388, 'Ireland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35389, 'Ireland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(972, 'Israel');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(972151, 'Israel Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(972153, 'Israel Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9725, 'Israel Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9726, 'Israel Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(39, 'Italy');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(393, 'Italy Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(225, 'Ivory Coast');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22501, 'Ivory Coast Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22502, 'Ivory Coast Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22503, 'Ivory Coast Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22504, 'Ivory Coast Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22505, 'Ivory Coast Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22506, 'Ivory Coast Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22507, 'Ivory Coast Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22508, 'Ivory Coast Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22509, 'Ivory Coast Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22545, 'Ivory Coast Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22546, 'Ivory Coast Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22547, 'Ivory Coast Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22548, 'Ivory Coast Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22566, 'Ivory Coast Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22567, 'Ivory Coast Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876, 'Jamaica');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876210, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187629, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187630, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187631, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187632, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187633, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187634, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187635, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187636, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187637, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187638, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187639, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187640, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876410, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876411, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876412, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876413, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876414, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876416, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876417, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876418, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876419, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187642, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187643, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187644, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187645, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187646, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187647, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187648, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187649, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876503, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876504, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876505, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876506, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876507, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876508, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876509, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876520, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876521, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876522, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876524, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876527, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876528, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876529, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187653, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187654, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876550, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876551, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876552, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876553, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876554, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876556, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876557, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876558, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876559, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187656, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187657, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187658, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187659, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(18767, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876707, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187677, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876781, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876782, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876783, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876784, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876787, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876788, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876789, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876790, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876791, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876792, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876793, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876796, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876797, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876798, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876799, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876801, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876802, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876803, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876804, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876805, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876806, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876807, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876808, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876809, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187681, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187682, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187683, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187684, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187685, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187686, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187687, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187688, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(187689, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876909, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876919, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876990, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876995, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876997, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1876999, 'Jamaica Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(81, 'Japan');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(8170, 'Japan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(8180, 'Japan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(8190, 'Japan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(962, 'Jordan');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96274, 'Jordan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96277, 'Jordan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(962785, 'Jordan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(962786, 'Jordan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(962788, 'Jordan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96279, 'Jordan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(771, 'Kazakhstan');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(772, 'Kazakhstan');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(7760, 'Kazakhstan');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(7761, 'Kazakhstan');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(7762, 'Kazakhstan');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(7763, 'Kazakhstan');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(77, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(7701, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(7702, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(7705, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(7707, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(771290, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(771291, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(771390, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(771391, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(771490, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(771491, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(771590, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(771591, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(771790, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(771791, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(771890, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(771891, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(772190, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(772191, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(772390, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(772391, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(772490, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(772491, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(772590, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(772591, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(772690, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(772691, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(772790, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(772791, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(7777, 'Kazakhstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(254, 'Kenya');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2547, 'Kenya Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(686, 'Kiribati');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68630, 'Kiribati Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68650, 'Kiribati Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68689, 'Kiribati Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6869, 'Kiribati Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965, 'Kuwait');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96540, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96544, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965501, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965502, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965505, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965506, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965507, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965508, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965509, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96551, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965550, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965554, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965555, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965556, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965557, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965558, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965559, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965570, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965578, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965579, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96558, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96559, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9656, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9657, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965701, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965702, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965703, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965704, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965705, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965706, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965707, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965708, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965709, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96571, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96572, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96573, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96574, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96575, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96576, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965770, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965771, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965772, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965773, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965774, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965775, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965776, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965778, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(965779, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96578, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96579, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9659, 'Kuwait Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(996, 'Kyrgyzstan');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99631270, 'Kyrgyzstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99631272, 'Kyrgyzstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99631274, 'Kyrgyzstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99631275, 'Kyrgyzstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99631276, 'Kyrgyzstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99631277, 'Kyrgyzstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99631278, 'Kyrgyzstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99631279, 'Kyrgyzstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(996502, 'Kyrgyzstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(996503, 'Kyrgyzstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(996515, 'Kyrgyzstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(996517, 'Kyrgyzstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(996543, 'Kyrgyzstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(996545, 'Kyrgyzstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(996550, 'Kyrgyzstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(996555, 'Kyrgyzstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(996575, 'Kyrgyzstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(996577, 'Kyrgyzstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9967, 'Kyrgyzstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99677, 'Kyrgyzstan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(856, 'Laos');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(85620, 'Laos Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(371, 'Latvia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(37120, 'Latvia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(37121, 'Latvia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(37122, 'Latvia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(37123, 'Latvia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(37124, 'Latvia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(37125, 'Latvia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(37126, 'Latvia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(37127, 'Latvia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(37128, 'Latvia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(37129, 'Latvia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(961, 'Lebanon');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9613, 'Lebanon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96170, 'Lebanon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96171, 'Lebanon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(266, 'Lesotho');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2665, 'Lesotho Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2666, 'Lesotho Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(231, 'Liberia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23103, 'Liberia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23128, 'Liberia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23146, 'Liberia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23147, 'Liberia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2315, 'Liberia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23164, 'Liberia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23165, 'Liberia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23166, 'Liberia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23167, 'Liberia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23168, 'Liberia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23169, 'Liberia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2317, 'Liberia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(218, 'Libya');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21891, 'Libya Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21892, 'Libya Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21894, 'Libya Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(423, 'Liechtenstein');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4236, 'Liechtenstein Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4237, 'Liechtenstein Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(370, 'Lithuania');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(370393, 'Lithuania Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3706, 'Lithuania Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(352, 'Luxembourg');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(352021, 'Luxembourg Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(352028, 'Luxembourg Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(352061, 'Luxembourg Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(352068, 'Luxembourg Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(352091, 'Luxembourg Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(352098, 'Luxembourg Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35221, 'Luxembourg Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35228, 'Luxembourg Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35261, 'Luxembourg Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(352621, 'Luxembourg Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(352628, 'Luxembourg Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(352661, 'Luxembourg Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(352668, 'Luxembourg Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35268, 'Luxembourg Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(352691, 'Luxembourg Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(352698, 'Luxembourg Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35291, 'Luxembourg Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35298, 'Luxembourg Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(853, 'Macao');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(85360, 'Macao Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(85361, 'Macao Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(85362, 'Macao Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(85363, 'Macao Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(85365, 'Macao Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(85366, 'Macao Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(85368, 'Macao Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(85369, 'Macao Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(389, 'Macedonia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38951, 'Macedonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38970, 'Macedonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38971, 'Macedonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38972, 'Macedonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38973, 'Macedonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38974, 'Macedonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38975, 'Macedonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38976, 'Macedonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38977, 'Macedonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38978, 'Macedonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38979, 'Macedonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(261, 'Madagascar');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26130, 'Madagascar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26132, 'Madagascar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26133, 'Madagascar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26134, 'Madagascar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(265, 'Malawi');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2654, 'Malawi Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2655, 'Malawi Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2658, 'Malawi Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2659, 'Malawi Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(60, 'Malaysia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(601, 'Malaysia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(960, 'Maldives');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9607, 'Maldives Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9609, 'Maldives Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(223, 'Mali');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22330, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22331, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22332, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22333, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22334, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22340, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22341, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22344, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22345, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22346, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22347, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22350, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22351, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22352, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22353, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22354, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22355, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22356, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22357, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22358, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22359, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22360, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22361, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22362, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22363, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22364, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22365, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22366, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22367, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22368, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22369, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22385, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22386, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22387, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22388, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22389, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22390, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22391, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22392, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22393, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22394, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22395, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22396, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22397, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22398, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22399, 'Mali Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(356, 'Malta');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3567117, 'Malta Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35672, 'Malta Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(356777, 'Malta Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35679, 'Malta Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35692, 'Malta Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(35699, 'Malta Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(692, 'Marshall Islands');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6922350, 'Marshall Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6922351, 'Marshall Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6922352, 'Marshall Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6922353, 'Marshall Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6922354, 'Marshall Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(692455, 'Marshall Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6926250, 'Marshall Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6926251, 'Marshall Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33596, 'Martinique');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(596, 'Martinique');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(596696, 'Martinique Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(222, 'Mauritania');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2222, 'Mauritania Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2226, 'Mauritania Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22270, 'Mauritania Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22273, 'Mauritania Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(230, 'Mauritius');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2302189, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(230219, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23022, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23025, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(230421, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(230422, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(230423, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(230428, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(230429, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23049, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23070, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23071, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23072, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23073, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23074, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23075, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23076, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23077, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23078, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23079, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(230871, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(230875, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(230876, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(230877, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23091, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23093, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23094, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23095, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23097, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23098, 'Mauritius Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(262269, 'Mayotte');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(262639, 'Mayotte Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(52, 'Mexico');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(521, 'Mexico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(691, 'Micronesia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(373, 'Moldova');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(373650, 'Moldova Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(373671, 'Moldova Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(373672, 'Moldova Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(373673, 'Moldova Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(373680, 'Moldova Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(373681, 'Moldova Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(373682, 'Moldova Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(373683, 'Moldova Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(373684, 'Moldova Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(373685, 'Moldova Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(373686, 'Moldova Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(373687, 'Moldova Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(373688, 'Moldova Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(37369, 'Moldova Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(373774, 'Moldova Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(373777, 'Moldova Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(373778, 'Moldova Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(373780, 'Moldova Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(373781, 'Moldova Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(37379, 'Moldova Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(377, 'Monaco');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3774, 'Monaco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3776, 'Monaco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(976, 'Mongolia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97688, 'Mongolia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97691, 'Mongolia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97695, 'Mongolia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97696, 'Mongolia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97699, 'Mongolia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(382, 'Montenegro');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38263, 'Montenegro Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38267, 'Montenegro Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38268, 'Montenegro Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38269, 'Montenegro Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1664, 'Montserrat');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1664492, 'Montserrat Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1664724, 'Montserrat Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(212, 'Morocco');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2121, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21226, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21227, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21233, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21234, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21240, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21241, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21242, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21244, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21245, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21246, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21247, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21248, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21249, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21250, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21251, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21252, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21253, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21254, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21255, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21259, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2126, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2127, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21292, 'Morocco Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(258, 'Rwanda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25882, 'Mozambique Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25884, 'Mozambique Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(95, 'Myanmar');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(959, 'Myanmar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(264, 'Namibia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26481, 'Namibia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26485, 'Namibia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(674, 'Nauru');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(674555, 'Nauru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(977, 'Nepal');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97798, 'Nepal Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(31, 'Netherlands');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599, 'Netherlands Antilles');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5993181, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5993184, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5993185, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5993186, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5994161, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5994165, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5994166, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5994167, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599510, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599520, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599521, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599522, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599523, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599524, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599526, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599527, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599550, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599551, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599552, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599553, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599554, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599555, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599556, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599557, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599558, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599559, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599580, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599581, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599586, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599587, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599588, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5997, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599701, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(59978, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(59979, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(59980, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599951, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599952, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5999530, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599954, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599955, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599956, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599961, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5999630, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5999631, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599966, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599967, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(599969, 'Netherlands Antilles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(316, 'Netherlands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(687, 'New Caledonia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68775, 'New Caledonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68776, 'New Caledonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68777, 'New Caledonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68778, 'New Caledonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68779, 'New Caledonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68780, 'New Caledonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68781, 'New Caledonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68782, 'New Caledonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68783, 'New Caledonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68784, 'New Caledonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68785, 'New Caledonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68786, 'New Caledonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68787, 'New Caledonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(68789, 'New Caledonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6879, 'New Caledonia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(64, 'New Zealand');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6420, 'New Zealand Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6421, 'New Zealand Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6422, 'New Zealand Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6423, 'New Zealand Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6424, 'New Zealand Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6425, 'New Zealand Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6426, 'New Zealand Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6427, 'New Zealand Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6428, 'New Zealand Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6429, 'New Zealand Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(505, 'Nicaragua');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5054, 'Nicaragua Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5056, 'Nicaragua Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5058, 'Nicaragua Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5059, 'Nicaragua Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(227, 'Niger');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22790, 'Niger Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22793, 'Niger Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22794, 'Niger Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22796, 'Niger Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(234, 'Nigeria');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(234702, 'Nigeria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(234703, 'Nigeria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(234705, 'Nigeria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(234706, 'Nigeria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(234708, 'Nigeria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23480, 'Nigeria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23490, 'Nigeria Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(683, 'Niue');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6723, 'Norfolk Island');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67238, 'Norfolk Island Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(850, 'North Korea');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1670, 'Northern Mariana Islands');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1670285, 'Northern Mariana Islands Mobil');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1670286, 'Northern Mariana Islands Mobil');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1670287, 'Northern Mariana Islands Mobil');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1670483, 'Northern Mariana Islands Mobil');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1670484, 'Northern Mariana Islands Mobil');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1670488, 'Northern Mariana Islands Mobil');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1670588, 'Northern Mariana Islands Mobil');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1670788, 'Northern Mariana Islands Mobil');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1670789, 'Northern Mariana Islands Mobil');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1670838, 'Northern Mariana Islands Mobil');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1670868, 'Northern Mariana Islands Mobil');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1670878, 'Northern Mariana Islands Mobil');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1670888, 'Northern Mariana Islands Mobil');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1670898, 'Northern Mariana Islands Mobil');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1670989, 'Northern Mariana Islands Mobil');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(47, 'Norway');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(474, 'Norway Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(479, 'Norway Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(968, 'Oman');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96891, 'Oman Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96892, 'Oman Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96895, 'Oman Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96896, 'Oman Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96897, 'Oman Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96898, 'Oman Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96899, 'Oman Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(92, 'Pakistan');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(923, 'Pakistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(680, 'Palau');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(680620, 'Palau Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(680630, 'Palau Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(680640, 'Palau Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(680660, 'Palau Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(680680, 'Palau Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(680690, 'Palau Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(680775, 'Palau Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(680779, 'Palau Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(970, 'Palestinian Territory');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97222, 'Palestinian Territory');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97232, 'Palestinian Territory');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97242, 'Palestinian Territory');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97282, 'Palestinian Territory');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97292, 'Palestinian Territory');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97059, 'Palestinian Territory Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97259, 'Palestinian Territory Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(507, 'Panama');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(507272, 'Panama Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(507276, 'Panama Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(507443, 'Panama Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5076, 'Panama Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(507810, 'Panama Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(507811, 'Panama Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(507855, 'Panama Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(507872, 'Panama Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(507873, 'Panama Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(675, 'Papua New Guinea');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67563, 'Papua New Guinea Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67565, 'Papua New Guinea Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67567, 'Papua New Guinea Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67568, 'Papua New Guinea Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67569, 'Papua New Guinea Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67571, 'Papua New Guinea Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67572, 'Papua New Guinea Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(595, 'Paraguay');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(595941, 'Paraguay Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(595943, 'Paraguay Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(595945, 'Paraguay Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(595961, 'Paraguay Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(595971, 'Paraguay Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(595973, 'Paraguay Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(595975, 'Paraguay Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(595981, 'Paraguay Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(595982, 'Paraguay Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(595983, 'Paraguay Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(595985, 'Paraguay Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(595991, 'Paraguay Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(595993, 'Paraguay Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(595995, 'Paraguay Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51, 'Peru');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5119, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51419, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51429, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51439, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51449, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51519, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51529, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51539, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51549, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51569, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51619, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51629, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51639, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51649, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51659, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51669, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51679, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51729, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51739, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51749, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51769, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51829, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51839, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(51849, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(519, 'Peru Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(63, 'Philippines');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(639, 'Philippines Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48, 'Poland');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4850, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4851, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4860, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48642, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4866, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4869, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48721, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48722, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48723, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48724, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48725, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48726, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(487272, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(487273, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(487274, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(487275, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(487276, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(487277, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(487278, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(487279, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(487281, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(487282, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(487283, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(487284, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(487285, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(487286, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(487287, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(487288, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(487289, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48729, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48780, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48781, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48782, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48783, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48784, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48785, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48786, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48787, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48788, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48789, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48790, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48791, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48792, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48793, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48794, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48795, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48796, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48797, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48798, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48799, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48880, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(488811, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(488818, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48882, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(488833, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(488838, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48884, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48885, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48886, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48887, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48888, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(48889, 'Poland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(351, 'Portugal');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3519, 'Portugal Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787, 'Puerto Rico');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939, 'Puerto Rico');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787201, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787202, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787203, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787204, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787205, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787206, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787207, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787208, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787209, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787210, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787212, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787213, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787214, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787215, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787216, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787217, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787218, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787219, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787220, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787221, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787222, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787223, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787224, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787225, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787226, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787228, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787230, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787231, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787232, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787233, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787234, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787235, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787236, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787237, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787238, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787239, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787240, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787241, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787242, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787243, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787244, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787245, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787246, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787247, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787248, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787249, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787295, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787297, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787298, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787299, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787301, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787302, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787303, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787304, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787305, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787306, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787307, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787308, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787309, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787310, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787312, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787313, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787314, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787315, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787316, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787317, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787318, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787319, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787320, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787321, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787322, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787323, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787324, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787325, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787326, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787327, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787328, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787329, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787330, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787331, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787332, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787333, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787334, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787335, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787336, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787337, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787338, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787339, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787340, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787341, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787342, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787344, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787345, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787346, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787347, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787348, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787349, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787350, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787351, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787352, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787353, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787354, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787356, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787358, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787359, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787360, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787361, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787362, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787363, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787364, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787365, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787366, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787367, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787368, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787370, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787371, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787372, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787373, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787374, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787375, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787376, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787377, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787378, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787379, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787380, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787381, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787382, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787383, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787384, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787385, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787386, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787387, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787388, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787389, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787390, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787391, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787392, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787393, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787394, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787396, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787397, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787398, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787399, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(17874, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787401, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787402, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787403, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787404, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787405, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787406, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787407, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787408, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787409, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787410, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787412, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787413, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787414, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787415, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787416, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787417, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787418, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787419, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787420, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787421, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787422, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787423, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787424, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787425, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787426, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787427, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787428, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787429, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787430, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787431, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787432, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787433, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787435, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787436, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787438, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787439, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787440, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787441, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787442, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787443, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787444, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787445, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787446, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787447, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787448, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787449, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787450, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787451, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787452, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787453, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787454, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787455, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787456, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787457, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787458, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787459, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787460, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787461, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787462, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787463, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787464, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787466, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787467, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787469, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787470, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787472, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787473, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787475, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787477, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787478, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787479, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787481, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787484, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787485, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787486, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787487, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787488, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787489, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787490, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787491, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787492, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787493, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787494, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787495, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787496, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787497, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787498, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787499, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787501, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787502, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787503, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787504, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787505, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787506, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787507, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787508, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787509, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787510, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787512, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787514, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787515, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787516, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787517, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787518, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787519, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787525, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787526, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787527, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787528, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787529, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787530, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787531, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787532, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787533, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787536, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787538, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787539, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787540, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787541, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787542, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787543, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787546, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787547, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787548, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787549, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787550, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787552, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787553, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787554, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787556, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787557, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787559, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787560, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787562, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787564, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787565, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787566, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787567, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787568, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787570, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787571, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787572, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787573, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787574, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787575, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787576, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787577, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787578, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787579, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787581, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787582, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787583, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787584, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787585, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787586, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787587, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787590, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787593, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787594, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787595, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787596, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787597, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787598, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787599, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787601, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787602, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787603, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787604, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787605, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787606, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787607, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787608, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787610, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787612, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787613, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787614, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787615, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787616, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787617, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787618, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787619, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787627, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787628, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787629, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787630, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787631, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787632, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787633, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787634, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787635, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787636, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787637, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787638, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787639, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787640, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787642, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787643, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787644, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787645, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787646, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787647, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787648, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787649, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787661, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787662, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787664, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787667, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787668, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787669, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787671, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787672, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787673, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787674, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787675, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787676, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787677, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787678, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787685, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787688, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787689, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787690, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787691, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787692, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787696, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787697, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787698, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787702, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787709, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787717, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787718, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787810, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787901, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787902, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787903, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787904, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787905, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787906, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787907, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787908, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787909, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787910, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787914, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787918, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787920, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787922, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787923, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787925, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787929, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787930, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787932, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787934, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787938, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787940, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787941, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787942, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787943, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787944, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787946, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787948, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787949, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787951, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787955, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787960, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787962, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787963, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787964, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787967, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787969, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787972, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787974, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787975, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787979, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787983, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787985, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787988, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787990, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787994, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1787996, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939218, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939241, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939242, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939243, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939244, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939245, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939246, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939247, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939248, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939389, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939397, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939475, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939579, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939628, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939630, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939639, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939640, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939642, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939644, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939645, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939717, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939940, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1939969, 'Puerto Rico Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(974, 'Qatar');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9741245, 'Qatar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9741744, 'Qatar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97420, 'Qatar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97421, 'Qatar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97422, 'Qatar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9745, 'Qatar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97460, 'Qatar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97461, 'Qatar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97464, 'Qatar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97465, 'Qatar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97466, 'Qatar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97467, 'Qatar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97468, 'Qatar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97469, 'Qatar Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(262, 'Reunion');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(33262, 'Reunion');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(262692, 'Reunion Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(262693, 'Reunion Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26269301, 'Reunion Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26269302, 'Reunion Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26269303, 'Reunion Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26269304, 'Reunion Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26269310, 'Reunion Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26269320, 'Reunion Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26269330, 'Reunion Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26269333, 'Reunion Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26269340, 'Reunion Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26269350, 'Reunion Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26269360, 'Reunion Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26269370, 'Reunion Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26269380, 'Reunion Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26269390, 'Reunion Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26269391, 'Reunion Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26269392, 'Reunion Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26269393, 'Reunion Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26269394, 'Reunion Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26269397, 'Reunion Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(40, 'Romania');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4076, 'Romania [Cosmote]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(403, 'Romania [OLO]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4074, 'Romania [Orange]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4075, 'Romania [Orange]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4078, 'Romania [Telemobil]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4072, 'Romania [Vodafone]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4073, 'Romania [Vodafone]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(407, 'Romania Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(7, 'Russian Federation');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(734922, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(734932, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(734934, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(7349363, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(7349364, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(73493667, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(73493668, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(73493669, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(734938, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(73494, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(734940, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(734948, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(734992, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(734993, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(734994, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(734995, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(734996, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(734997, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(73842, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738441, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738442, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738443, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738444, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738445, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738446, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738447, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738448, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738449, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738451, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738452, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738453, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738454, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738455, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738456, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738459, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738471, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738473, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738474, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738475, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738510, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738511, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738512, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738513, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738514, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738515, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738516, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738517, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738518, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738519, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738530, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738531, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738532, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738533, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738534, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738535, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738536, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738537, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738538, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738539, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738550, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738551, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738552, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738553, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738554, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738555, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738556, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738557, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738558, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738559, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738560, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738561, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738562, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738563, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738564, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738565, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738566, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738567, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738568, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738569, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738570, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738571, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738572, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738573, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738574, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738575, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738576, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738577, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738578, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738579, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738590, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738591, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738592, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738593, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738594, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738595, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738596, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738597, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738598, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(738599, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742135, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742137, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742138, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742141, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742142, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742144, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742146, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742149, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742151, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742153, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742154, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742155, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742156, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(74217, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742171, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742331, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742334, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742335, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742337, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742339, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742351, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742352, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742354, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742355, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742356, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742357, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742359, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742371, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742372, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742373, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742374, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742375, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742376, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(742377, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(782130, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(782131, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(782132, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(782133, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(782134, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(782135, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(782136, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(782137, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(782138, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(782139, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(782140, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(782141, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(782142, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(782144, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(782145, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(782146, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(782147, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(782149, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(782151, 'Russian Federation [FIX2]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(79, 'Russian Federation Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(250, 'Rwanda');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(255, 'Tanzania');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(685, 'Samoa');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6857, 'Samoa Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(378, 'San Marino');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3786, 'San Marino Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(239, 'Sao Tome and Principe');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23990, 'Sao Tome and Principe Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(966, 'Saudi Arabia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9665, 'Saudi Arabia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9668, 'Saudi Arabia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(221, 'Senegal');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22176, 'Senegal Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(22177, 'Senegal Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(381, 'Serbia and Montenegro');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(3816, 'Serbia and Montenegro Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(248, 'Seychelles');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2485, 'Seychelles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2487, 'Seychelles Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(232, 'Sierra Leone');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23223, 'Sierra Leone Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23230, 'Sierra Leone Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23233, 'Sierra Leone Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23235, 'Sierra Leone Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23240, 'Sierra Leone Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23250, 'Sierra Leone Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23276, 'Sierra Leone Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(23277, 'Sierra Leone Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(65, 'Singapore');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(658, 'Singapore Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(659, 'Singapore Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(421, 'Slovak Republic');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(42190, 'Slovak Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(421910, 'Slovak Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(421911, 'Slovak Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(421912, 'Slovak Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(421913, 'Slovak Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(421914, 'Slovak Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(421915, 'Slovak Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(421916, 'Slovak Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(421917, 'Slovak Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(421918, 'Slovak Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(421919, 'Slovak Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(421944, 'Slovak Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(421948, 'Slovak Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(421949, 'Slovak Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(386, 'Slovenia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38630, 'Slovenia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38631, 'Slovenia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38640, 'Slovenia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38641, 'Slovenia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38649, 'Slovenia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38650, 'Slovenia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38651, 'Slovenia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(386641, 'Slovenia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38670, 'Slovenia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38671, 'Slovenia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(677, 'Solomon Islands');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67743, 'Solomon Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67754, 'Solomon Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67755, 'Solomon Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67756, 'Solomon Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67757, 'Solomon Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67758, 'Solomon Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67759, 'Solomon Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67765, 'Solomon Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67766, 'Solomon Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67768, 'Solomon Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67769, 'Solomon Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6777, 'Solomon Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6778, 'Solomon Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(6779, 'Solomon Islands Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(252, 'Somalia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25224, 'Somalia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25228, 'Somalia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25260, 'Somalia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25262, 'Somalia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25265, 'Somalia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25266, 'Somalia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25268, 'Somalia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25290, 'Somalia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25291, 'Somalia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(27, 'South Africa');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(277, 'South Africa Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2782, 'South Africa Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2783, 'South Africa Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2784, 'South Africa Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2785, 'South Africa Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2786, 'South Africa Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(82, 'South Korea');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(821, 'South Korea Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(34, 'Spain');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(346, 'Spain Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(94, 'Sri Lanka');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9471, 'Sri Lanka Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9472, 'Sri Lanka Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9477, 'Sri Lanka Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9478, 'Sri Lanka Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(290, 'St Helena');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1869, 'St Kitts and Nevis');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1869556, 'St Kitts and Nevis Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1869557, 'St Kitts and Nevis Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1869558, 'St Kitts and Nevis Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1869565, 'St Kitts and Nevis Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1869566, 'St Kitts and Nevis Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1869567, 'St Kitts and Nevis Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1869662, 'St Kitts and Nevis Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1869663, 'St Kitts and Nevis Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1869664, 'St Kitts and Nevis Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1869665, 'St Kitts and Nevis Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1869667, 'St Kitts and Nevis Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1869668, 'St Kitts and Nevis Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1869669, 'St Kitts and Nevis Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1869762, 'St Kitts and Nevis Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1869763, 'St Kitts and Nevis Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1869764, 'St Kitts and Nevis Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1869765, 'St Kitts and Nevis Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758, 'St Lucia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758284, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758285, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758286, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758287, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758384, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758460, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758461, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758481, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758482, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758483, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758484, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758485, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758486, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758487, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758488, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758489, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758518, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758519, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758520, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758584, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758712, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758713, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758714, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758715, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758716, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758717, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758718, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758719, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758720, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758721, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758722, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758723, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1758724, 'St Lucia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(508, 'St Pierre and Miquelon');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(50855, 'St Pierre and Miquelon Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1784, 'St Vincent and the Grenadines');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1784430, 'St Vincent and the Grenadines');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1784431, 'St Vincent and the Grenadines');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1784432, 'St Vincent and the Grenadines');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1784433, 'St Vincent and the Grenadines');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1784434, 'St Vincent and the Grenadines');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1784454, 'St Vincent and the Grenadines');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1784455, 'St Vincent and the Grenadines');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1784492, 'St Vincent and the Grenadines');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1784493, 'St Vincent and the Grenadines');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1784494, 'St Vincent and the Grenadines');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1784495, 'St Vincent and the Grenadines');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1784526, 'St Vincent and the Grenadines');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1784527, 'St Vincent and the Grenadines');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1784528, 'St Vincent and the Grenadines');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1784529, 'St Vincent and the Grenadines');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1784530, 'St Vincent and the Grenadines');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1784531, 'St Vincent and the Grenadines');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1784532, 'St Vincent and the Grenadines');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1784533, 'St Vincent and the Grenadines');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1784593, 'St Vincent and the Grenadines');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(249, 'Sudan');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24991, 'Sudan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24992, 'Sudan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(24994, 'Sudan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(597, 'Suriname');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(5978, 'Suriname Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(268, 'Swaziland');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26860, 'Swaziland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26861, 'Swaziland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26862, 'Swaziland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26863, 'Swaziland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26864, 'Swaziland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26865, 'Swaziland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26866, 'Swaziland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26867, 'Swaziland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(46, 'Sweden');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4610, 'Sweden Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(46252, 'Sweden Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(46376, 'Sweden Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(46518, 'Sweden Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(46519, 'Sweden Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(46673, 'Sweden Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(46674, 'Sweden Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(46675, 'Sweden Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(46676, 'Sweden Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4670, 'Sweden Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4673, 'Sweden Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4674, 'Sweden Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4676, 'Sweden Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(41, 'Switzerland');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4174, 'Switzerland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4176, 'Switzerland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4177, 'Switzerland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4178, 'Switzerland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4179, 'Switzerland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4186, 'Switzerland Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(963, 'Syrian Arab Republic');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96392, 'Syrian Arab Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96393, 'Syrian Arab Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96394, 'Syrian Arab Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96395, 'Syrian Arab Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96396, 'Syrian Arab Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96398, 'Syrian Arab Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96399, 'Syrian Arab Republic Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(886, 'Taiwan');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(88690, 'Taiwan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(88691, 'Taiwan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(88692, 'Taiwan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(88693, 'Taiwan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(88694, 'Taiwan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(88695, 'Taiwan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(88696, 'Taiwan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(88697, 'Taiwan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(88698, 'Taiwan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(992, 'Tajikistan');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9929, 'Tajikistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2556, 'Tanzania Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2557, 'Tanzania Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(66, 'Thailand');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(668, 'Thailand Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(88216, 'Thuraya');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(228, 'Togo');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2289, 'Togo Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(676, 'Tonga');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67611, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67612, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67613, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67614, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67615, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67616, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67617, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67618, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67619, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67645, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67646, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67647, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67648, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67649, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67652, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67653, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67654, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67655, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67656, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67657, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67658, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67659, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67662, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67663, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67664, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67665, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67666, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67667, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67668, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67675, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67676, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67677, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67678, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67681, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67682, 'Tonga Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868, 'Trinidad and Tobago');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(186829, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868301, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868302, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868303, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868304, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868305, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868306, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868307, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868308, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868309, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868310, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868312, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868313, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868314, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868315, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868316, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868317, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868318, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868319, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(186832, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(186833, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(186834, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(186835, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(186836, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(186837, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(186838, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(186839, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868401, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868402, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868403, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868404, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868405, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868406, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868407, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868408, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868409, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868410, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868412, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868413, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868414, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868415, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868416, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868417, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868418, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868419, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868420, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868421, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(186846, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(186847, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(186848, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(186849, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868619, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868620, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868678, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(186868, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868701, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868702, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868703, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868704, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868705, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868706, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868707, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868708, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868709, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868710, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868712, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868713, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868714, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868715, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868716, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868717, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868718, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1868719, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(186872, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(186873, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(186874, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(186875, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(186876, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(186877, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(186878, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(186879, 'Trinidad and Tobago Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2903, 'Tristan da Cunha');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(216, 'Tunisia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21620, 'Tunisia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21621, 'Tunisia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21622, 'Tunisia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21623, 'Tunisia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21624, 'Tunisia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21625, 'Tunisia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21690, 'Tunisia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21691, 'Tunisia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21693, 'Tunisia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21694, 'Tunisia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21695, 'Tunisia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21696, 'Tunisia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21697, 'Tunisia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21698, 'Tunisia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(21699, 'Tunisia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(90, 'Turkey');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(905, 'Turkey Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(993, 'Turkmenistan');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(993122, 'Turkmenistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9932221, 'Turkmenistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9932431, 'Turkmenistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9933221, 'Turkmenistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9934221, 'Turkmenistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9935221, 'Turkmenistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9936, 'Turkmenistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1649, 'Turks and Caicos');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1649231, 'Turks and Caicos Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1649232, 'Turks and Caicos Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1649241, 'Turks and Caicos Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1649242, 'Turks and Caicos Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1649243, 'Turks and Caicos Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1649244, 'Turks and Caicos Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1649245, 'Turks and Caicos Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1649249, 'Turks and Caicos Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1649331, 'Turks and Caicos Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1649332, 'Turks and Caicos Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1649333, 'Turks and Caicos Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1649341, 'Turks and Caicos Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1649342, 'Turks and Caicos Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1649343, 'Turks and Caicos Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1649344, 'Turks and Caicos Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1649345, 'Turks and Caicos Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1649431, 'Turks and Caicos Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1649432, 'Turks and Caicos Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1649441, 'Turks and Caicos Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1649442, 'Turks and Caicos Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1649724, 'Turks and Caicos Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(688, 'Tuvalu');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(256, 'Uganda');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25639, 'Uganda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(2567, 'Uganda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(256701, 'Uganda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(256702, 'Uganda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(256703, 'Uganda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(256704, 'Uganda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25671, 'Uganda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25675, 'Uganda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25677, 'Uganda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(25678, 'Uganda Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(380, 'Ukraine');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38039, 'Ukraine Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38050, 'Ukraine Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38063, 'Ukraine Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38066, 'Ukraine Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38067, 'Ukraine Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38068, 'Ukraine Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38091, 'Ukraine Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38092, 'Ukraine Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38093, 'Ukraine Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38094, 'Ukraine Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38095, 'Ukraine Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38096, 'Ukraine Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38097, 'Ukraine Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38098, 'Ukraine Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(38099, 'Ukraine Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(971, 'United Arab Emirates');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97150, 'United Arab Emirates Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97155, 'United Arab Emirates Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(97156, 'United Arab Emirates Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(44, 'United Kingdom');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(441, 'United Kingdom Landline');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(442, 'United Kingdom Landline');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(443, 'United Kingdom Landline');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4470, 'United Kingdom Personal Number');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4475, 'United Kingdom Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4476, 'United Kingdom Pager');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4477, 'United Kingdom Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4478, 'United Kingdom Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(4479, 'United Kingdom Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(448, 'United Kingdom Special-Rate');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(449, 'United Kingdom Premium-Rate');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1201, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1202, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1203, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1205, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1206, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1207, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1208, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1209, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1210, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1212, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1213, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1214, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1215, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1216, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1217, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1218, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1219, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1224, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1225, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1227, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1228, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1229, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1231, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1234, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1239, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1240, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1248, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1251, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1252, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1253, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1254, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1256, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1260, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1262, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1267, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1269, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1270, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1276, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1281, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1283, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1301, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1302, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1303, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1304, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1305, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1307, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1308, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1309, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1310, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1312, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1313, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1314, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1315, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1316, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1317, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1318, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1319, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1320, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1321, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1323, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1325, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1330, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1331, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1334, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1336, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1337, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1339, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1341, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1347, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1351, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1352, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1360, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1361, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1369, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1380, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1385, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1386, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1401, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1402, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1404, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1405, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1406, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1407, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1408, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1409, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1410, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1412, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1413, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1414, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1415, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1417, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1419, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1423, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1424, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1425, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1430, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1432, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1434, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1435, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1440, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1442, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1443, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1445, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1447, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1456, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1464, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1469, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1470, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1475, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1478, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1479, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1480, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1484, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(15, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1501, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1502, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1503, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1504, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1505, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1507, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1508, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1509, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1510, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1512, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1513, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1515, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1516, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1517, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1518, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1520, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1530, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1540, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1541, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1551, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1555, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1557, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1559, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1561, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1562, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1563, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1564, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1567, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1570, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1571, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1573, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1574, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1575, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1580, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1585, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1586, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1601, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1602, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1603, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1605, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1606, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1607, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1608, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1609, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1610, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1612, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1614, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1615, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1616, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1617, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1618, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1619, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1620, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1623, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1626, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1627, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1628, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1630, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1631, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1636, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1641, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1646, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1650, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1651, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1657, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1659, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1660, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1661, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1662, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1667, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1669, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1678, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1679, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1682, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1689, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(17, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1701, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1702, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1703, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1704, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1706, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1707, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1708, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1710, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1712, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1713, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1714, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1715, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1716, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1717, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1718, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1719, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1720, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1724, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1727, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1730, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1731, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1732, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1734, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1737, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1740, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1747, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1752, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1754, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1757, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1760, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1762, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1763, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1764, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1765, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1769, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1770, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1772, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1773, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1774, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1775, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1779, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1781, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1785, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1786, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1801, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1802, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1803, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1804, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1805, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1806, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1808, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1810, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1812, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1813, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1814, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1815, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1816, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1817, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1818, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1828, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1830, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1831, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1832, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1835, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1843, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1845, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1847, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1848, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1850, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1856, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1857, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1858, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1859, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1860, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1862, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1863, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1864, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1865, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1870, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1872, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1878, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1901, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1903, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1904, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1906, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1908, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1909, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1910, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1912, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1913, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1914, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1915, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1916, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1917, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1918, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1919, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1920, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1925, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1928, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1931, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1935, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1936, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1937, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1940, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1941, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1947, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1949, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1951, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1952, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1954, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1956, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1959, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1970, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1971, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1972, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1973, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1975, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1978, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1979, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1980, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1984, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1985, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1989, 'United States');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1907, 'United States [ALASKA]');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998722273, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998722274, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998722275, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998722276, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998722277, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998722278, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998722279, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998722295, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99872325, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99872326, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99872327, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99872328, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99872329, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99872360, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99872361, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99872362, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99872363, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99872364, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99872365, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99872366, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99872570, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99872575, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99872577, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99872579, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873210, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873211, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873212, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873213, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873214, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873215, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873216, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873221, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873234, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873236, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873239, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873271, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873275, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873279, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873330, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873333, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998735, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873501, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873502, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873503, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873504, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873555, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873557, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873559, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873590, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873595, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873599, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873940, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873941, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873944, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873955, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873956, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99873966, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874229, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874250, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874255, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874257, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874260, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874261, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874262, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874263, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874264, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874265, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874266, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874267, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874271, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874272, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874273, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874274, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874275, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874277, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874510, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874580, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874585, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874775, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874777, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874778, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874970, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874971, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874975, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874976, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874977, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874978, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874979, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874980, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874981, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874982, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874983, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874984, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874985, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874986, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874987, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874988, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874989, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874990, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874995, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99874999, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99875112, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99875222, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99875229, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99875244, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99875294, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(9987531, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99875350, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99875355, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99875360, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99875363, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99875366, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99875380, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99875381, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99875382, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99875383, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99875384, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99875385, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99875386, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99875526, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99875527, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99875528, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99875529, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998762229, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998762246, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998762247, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998762248, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998762249, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998762257, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998762258, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998762259, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99876242, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99876243, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99876244, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99876390, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99876391, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99876392, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99876393, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99876394, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99876395, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99876396, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998764115, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998764116, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998764117, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998764118, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998764119, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998764171, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998764172, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998764173, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998764174, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998764175, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998764190, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998764191, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998764192, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998764193, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998764194, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998764198, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998764199, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99876535, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99876536, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99876537, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99876540, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99876544, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99876545, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99876550, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99876551, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99876552, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99876590, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99876595, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99879221, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998792225, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998792226, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998792227, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99879228, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99879320, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99879321, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99879322, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99879323, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99879324, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99879370, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99879371, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99879372, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99879377, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99879570, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998795726, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998795727, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998795728, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998795729, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99879575, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99879576, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998795790, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99879725, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99879727, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99879740, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99879744, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99879747, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99890, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99891, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99892, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99893, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99895, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99897, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99898, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(678, 'Vanuatu');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67854, 'Vanuatu Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67855, 'Vanuatu Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(67877, 'Vanuatu Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(58, 'Venezuela');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(584, 'Venezuela Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(84, 'Viet Nam');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(84122, 'Viet Nam Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(84123, 'Viet Nam Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(84126, 'Viet Nam Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(84166, 'Viet Nam Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(84168, 'Viet Nam Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(84169, 'Viet Nam Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(8490, 'Viet Nam Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(8491, 'Viet Nam Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(8492, 'Viet Nam Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(8493, 'Viet Nam Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(8494, 'Viet Nam Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(8495, 'Viet Nam Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(8496, 'Viet Nam Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(8497, 'Viet Nam Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(8498, 'Viet Nam Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(8499, 'Viet Nam Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(681, 'Wallis and Futuna');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(967, 'Yemen');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96758, 'Yemen Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96771, 'Yemen Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96773, 'Yemen Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(96777, 'Yemen Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(260, 'Zambia');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26095, 'Zambia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26096, 'Zambia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26097, 'Zambia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26098, 'Zambia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26099, 'Zambia Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(263, 'Zimbabwe');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26311, 'Zimbabwe Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26323, 'Zimbabwe Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(26391, 'Zimbabwe Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(1, 'USA');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(379, 'Vatican City');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998714, 'Uzbekistan Tashkent');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998711, 'Uzbekistan Tashkent');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998713, 'Uzbekistan Tashkent');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998712, 'Uzbekistan Tashkent');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(99899, 'Uzbekistan Mobile');
INSERT INTO `cc_prefix` (`prefix`, `destination`) VALUES(998, 'Uzbekistan');

-- --------------------------------------------------------

--
-- Table structure for table `cc_provider`
--

CREATE TABLE IF NOT EXISTS `cc_provider` (
  `id` int(11) NOT NULL auto_increment,
  `provider_name` char(30) collate utf8_bin NOT NULL,
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `description` mediumtext collate utf8_bin,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `cons_cc_provider_provider_name` (`provider_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_provider`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_ratecard`
--

CREATE TABLE IF NOT EXISTS `cc_ratecard` (
  `id` int(11) NOT NULL auto_increment,
  `idtariffplan` int(11) NOT NULL default '0',
  `dialprefix` char(30) collate utf8_bin NOT NULL,
  `buyrate` decimal(15,5) NOT NULL default '0.00000',
  `buyrateinitblock` int(11) NOT NULL default '0',
  `buyrateincrement` int(11) NOT NULL default '0',
  `rateinitial` decimal(15,5) NOT NULL default '0.00000',
  `initblock` int(11) NOT NULL default '0',
  `billingblock` int(11) NOT NULL default '0',
  `connectcharge` decimal(15,5) NOT NULL default '0.00000',
  `disconnectcharge` decimal(15,5) NOT NULL default '0.00000',
  `stepchargea` decimal(15,5) NOT NULL default '0.00000',
  `chargea` decimal(15,5) NOT NULL default '0.00000',
  `timechargea` int(11) NOT NULL default '0',
  `billingblocka` int(11) NOT NULL default '0',
  `stepchargeb` decimal(15,5) NOT NULL default '0.00000',
  `chargeb` decimal(15,5) NOT NULL default '0.00000',
  `timechargeb` int(11) NOT NULL default '0',
  `billingblockb` int(11) NOT NULL default '0',
  `stepchargec` float NOT NULL default '0',
  `chargec` float NOT NULL default '0',
  `timechargec` int(11) NOT NULL default '0',
  `billingblockc` int(11) NOT NULL default '0',
  `startdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `stopdate` timestamp NOT NULL default '0000-00-00 00:00:00',
  `starttime` smallint(5) unsigned default '0',
  `endtime` smallint(5) unsigned default '10079',
  `id_trunk` int(11) default '-1',
  `musiconhold` char(100) collate utf8_bin NOT NULL,
  `id_outbound_cidgroup` int(11) default '-1',
  `rounding_calltime` int(11) NOT NULL default '0',
  `rounding_threshold` int(11) NOT NULL default '0',
  `additional_block_charge` decimal(15,5) NOT NULL default '0.00000',
  `additional_block_charge_time` int(11) NOT NULL default '0',
  `tag` char(50) collate utf8_bin default NULL,
  `disconnectcharge_after` int(11) NOT NULL default '0',
  `is_merged` int(11) default '0',
  `additional_grace` int(11) NOT NULL default '0',
  `minimal_cost` decimal(15,5) NOT NULL default '0.00000',
  `announce_time_correction` decimal(5,3) NOT NULL default '1.000',
  `destination` int(11) default '0',
  PRIMARY KEY  (`id`),
  KEY `ind_cc_ratecard_dialprefix` (`dialprefix`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Triggers `cc_ratecard`
--
DROP TRIGGER IF EXISTS `cc_ratecard_validate_regex_ins`;
DELIMITER //
CREATE TRIGGER `cc_ratecard_validate_regex_ins` BEFORE INSERT ON `cc_ratecard`
 FOR EACH ROW BEGIN
  DECLARE valid INTEGER;
  SELECT '0' REGEXP REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(CONCAT('^', NEW.dialprefix, '$'), 'X', '[0-9]'), 'Z', '[1-9]'), 'N', '[2-9]'), '.', '.+'), '_', '') INTO valid;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `cc_ratecard_validate_regex_upd`;
DELIMITER //
CREATE TRIGGER `cc_ratecard_validate_regex_upd` BEFORE UPDATE ON `cc_ratecard`
 FOR EACH ROW BEGIN
  DECLARE valid INTEGER;
  SELECT '0' REGEXP REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(CONCAT('^', NEW.dialprefix, '$'), 'X', '[0-9]'), 'Z', '[1-9]'), 'N', '[2-9]'), '.', '.+'), '_', '') INTO valid;
END
//
DELIMITER ;

--
-- Dumping data for table `cc_ratecard`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_receipt`
--

CREATE TABLE IF NOT EXISTS `cc_receipt` (
  `id` bigint(20) NOT NULL auto_increment,
  `id_card` bigint(20) NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `title` varchar(50) collate utf8_bin NOT NULL,
  `description` mediumtext collate utf8_bin NOT NULL,
  `status` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_receipt`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_receipt_item`
--

CREATE TABLE IF NOT EXISTS `cc_receipt_item` (
  `id` bigint(20) NOT NULL auto_increment,
  `id_receipt` bigint(20) NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `price` decimal(15,5) NOT NULL default '0.00000',
  `description` mediumtext collate utf8_bin NOT NULL,
  `id_ext` bigint(20) default NULL,
  `type_ext` varchar(10) collate utf8_bin default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_receipt_item`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_restricted_phonenumber`
--

CREATE TABLE IF NOT EXISTS `cc_restricted_phonenumber` (
  `id` bigint(20) NOT NULL auto_increment,
  `number` varchar(50) collate utf8_bin NOT NULL,
  `id_card` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_restricted_phonenumber`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_server_group`
--

CREATE TABLE IF NOT EXISTS `cc_server_group` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(60) collate utf8_bin default NULL,
  `description` mediumtext collate utf8_bin,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cc_server_group`
--

INSERT INTO `cc_server_group` (`id`, `name`, `description`) VALUES(1, 'default', 'default group of server');

-- --------------------------------------------------------

--
-- Table structure for table `cc_server_manager`
--

CREATE TABLE IF NOT EXISTS `cc_server_manager` (
  `id` bigint(20) NOT NULL auto_increment,
  `id_group` int(11) default '1',
  `server_ip` varchar(40) collate utf8_bin default NULL,
  `manager_host` varchar(50) collate utf8_bin default NULL,
  `manager_username` varchar(50) collate utf8_bin default NULL,
  `manager_secret` varchar(50) collate utf8_bin default NULL,
  `lasttime_used` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cc_server_manager`
--

INSERT INTO `cc_server_manager` (`id`, `id_group`, `server_ip`, `manager_host`, `manager_username`, `manager_secret`, `lasttime_used`) VALUES(1, 1, 'localhost', 'localhost', 'myasterisk', 'mycode', '2009-05-15 16:38:43');

-- --------------------------------------------------------

--
-- Table structure for table `cc_service`
--

CREATE TABLE IF NOT EXISTS `cc_service` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` char(100) collate utf8_bin NOT NULL,
  `amount` float NOT NULL,
  `period` int(11) NOT NULL default '1',
  `rule` int(11) NOT NULL default '0',
  `daynumber` int(11) NOT NULL default '0',
  `stopmode` int(11) NOT NULL default '0',
  `maxnumbercycle` int(11) NOT NULL default '0',
  `status` int(11) NOT NULL default '0',
  `numberofrun` int(11) NOT NULL default '0',
  `datecreate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `datelastrun` timestamp NOT NULL default '0000-00-00 00:00:00',
  `emailreport` char(100) collate utf8_bin NOT NULL,
  `totalcredit` float NOT NULL default '0',
  `totalcardperform` int(11) NOT NULL default '0',
  `operate_mode` tinyint(4) default '0',
  `dialplan` int(11) default '0',
  `use_group` tinyint(4) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_service`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_service_report`
--

CREATE TABLE IF NOT EXISTS `cc_service_report` (
  `id` bigint(20) NOT NULL auto_increment,
  `cc_service_id` bigint(20) NOT NULL,
  `daterun` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `totalcardperform` int(11) default NULL,
  `totalcredit` float default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_service_report`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_sip_buddies`
--

CREATE TABLE IF NOT EXISTS `cc_sip_buddies` (
  `id` int(11) NOT NULL auto_increment,
  `id_cc_card` int(11) NOT NULL default '0',
  `name` varchar(80) collate utf8_bin NOT NULL,
  `accountcode` varchar(20) collate utf8_bin NOT NULL,
  `regexten` varchar(20) collate utf8_bin NOT NULL,
  `amaflags` char(7) collate utf8_bin default NULL,
  `callgroup` char(10) collate utf8_bin default NULL,
  `callerid` varchar(80) collate utf8_bin NOT NULL,
  `canreinvite` varchar(20) collate utf8_bin NOT NULL,
  `context` varchar(80) collate utf8_bin NOT NULL,
  `DEFAULTip` char(15) collate utf8_bin default NULL,
  `dtmfmode` char(7) collate utf8_bin NOT NULL default 'RFC2833',
  `fromuser` varchar(80) collate utf8_bin NOT NULL,
  `fromdomain` varchar(80) collate utf8_bin NOT NULL,
  `host` varchar(31) collate utf8_bin NOT NULL,
  `insecure` varchar(20) collate utf8_bin NOT NULL,
  `language` char(2) collate utf8_bin default NULL,
  `mailbox` varchar(50) collate utf8_bin NOT NULL,
  `md5secret` varchar(80) collate utf8_bin NOT NULL,
  `nat` char(3) collate utf8_bin default 'yes',
  `permit` varchar(95) collate utf8_bin NOT NULL,
  `deny` varchar(95) collate utf8_bin NOT NULL,
  `mask` varchar(95) collate utf8_bin NOT NULL,
  `pickupgroup` char(10) collate utf8_bin default NULL,
  `port` char(5) collate utf8_bin NOT NULL default '',
  `qualify` char(7) collate utf8_bin default 'yes',
  `restrictcid` char(1) collate utf8_bin default NULL,
  `rtptimeout` char(3) collate utf8_bin default NULL,
  `rtpholdtimeout` char(3) collate utf8_bin default NULL,
  `secret` varchar(80) collate utf8_bin NOT NULL,
  `type` char(6) collate utf8_bin NOT NULL default 'friend',
  `username` varchar(80) collate utf8_bin NOT NULL,
  `disallow` varchar(100) collate utf8_bin NOT NULL,
  `allow` varchar(100) collate utf8_bin NOT NULL,
  `musiconhold` varchar(100) collate utf8_bin NOT NULL,
  `regseconds` int(11) NOT NULL default '0',
  `ipaddr` char(15) collate utf8_bin NOT NULL default '',
  `cancallforward` char(3) collate utf8_bin default 'yes',
  `fullcontact` varchar(80) collate utf8_bin NOT NULL,
  `setvar` varchar(100) collate utf8_bin NOT NULL,
  `regserver` varchar(20) collate utf8_bin default NULL,
  `lastms` varchar(11) collate utf8_bin default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `cons_cc_sip_buddies_name` (`name`),
  KEY `name` (`name`),
  KEY `host` (`host`),
  KEY `ipaddr` (`ipaddr`),
  KEY `port` (`port`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_sip_buddies`
--


-- --------------------------------------------------------

--
-- Stand-in structure for view `cc_sip_buddies_empty`
--
CREATE TABLE IF NOT EXISTS `cc_sip_buddies_empty` (
`id` int(11)
,`id_cc_card` int(11)
,`name` varchar(80)
,`accountcode` varchar(20)
,`regexten` varchar(20)
,`amaflags` char(7)
,`callgroup` char(10)
,`callerid` varchar(80)
,`canreinvite` varchar(20)
,`context` varchar(80)
,`DEFAULTip` char(15)
,`dtmfmode` char(7)
,`fromuser` varchar(80)
,`fromdomain` varchar(80)
,`host` varchar(31)
,`insecure` varchar(20)
,`language` char(2)
,`mailbox` varchar(50)
,`md5secret` varchar(80)
,`nat` char(3)
,`permit` varchar(95)
,`deny` varchar(95)
,`mask` varchar(95)
,`pickupgroup` char(10)
,`port` char(5)
,`qualify` char(7)
,`restrictcid` char(1)
,`rtptimeout` char(3)
,`rtpholdtimeout` char(3)
,`secret` char(0)
,`type` char(6)
,`username` varchar(80)
,`disallow` varchar(100)
,`allow` varchar(100)
,`musiconhold` varchar(100)
,`regseconds` int(11)
,`ipaddr` char(15)
,`cancallforward` char(3)
,`fullcontact` varchar(80)
,`setvar` varchar(100)
);
-- --------------------------------------------------------

--
-- Table structure for table `cc_speeddial`
--

CREATE TABLE IF NOT EXISTS `cc_speeddial` (
  `id` bigint(20) NOT NULL auto_increment,
  `id_cc_card` bigint(20) NOT NULL default '0',
  `phone` varchar(100) collate utf8_bin NOT NULL,
  `name` varchar(100) collate utf8_bin NOT NULL,
  `speeddial` int(11) default '0',
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `cons_cc_speeddial_id_cc_card_speeddial` (`id_cc_card`,`speeddial`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_speeddial`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_status_log`
--

CREATE TABLE IF NOT EXISTS `cc_status_log` (
  `id` bigint(20) NOT NULL auto_increment,
  `status` int(11) NOT NULL,
  `id_cc_card` bigint(20) NOT NULL,
  `updated_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_status_log`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_subscription_fee`
--

CREATE TABLE IF NOT EXISTS `cc_subscription_fee` (
  `id` bigint(20) NOT NULL auto_increment,
  `label` text collate utf8_bin NOT NULL,
  `fee` float NOT NULL default '0',
  `currency` char(3) collate utf8_bin default 'USD',
  `status` int(11) NOT NULL default '0',
  `numberofrun` int(11) NOT NULL default '0',
  `datecreate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `datelastrun` timestamp NOT NULL default '0000-00-00 00:00:00',
  `emailreport` text collate utf8_bin,
  `totalcredit` float NOT NULL default '0',
  `totalcardperform` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_subscription_fee`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_support`
--

CREATE TABLE IF NOT EXISTS `cc_support` (
  `id` smallint(5) NOT NULL auto_increment,
  `name` varchar(50) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cc_support`
--

INSERT INTO `cc_support` (`id`, `name`) VALUES(1, 'DEFAULT');

-- --------------------------------------------------------

--
-- Table structure for table `cc_support_component`
--

CREATE TABLE IF NOT EXISTS `cc_support_component` (
  `id` smallint(5) NOT NULL auto_increment,
  `id_support` smallint(5) NOT NULL,
  `name` varchar(50) collate utf8_bin NOT NULL,
  `activated` smallint(6) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cc_support_component`
--

INSERT INTO `cc_support_component` (`id`, `id_support`, `name`, `activated`) VALUES(1, 1, 'DEFAULT', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cc_system_log`
--

CREATE TABLE IF NOT EXISTS `cc_system_log` (
  `id` int(11) NOT NULL auto_increment,
  `iduser` int(11) NOT NULL default '0',
  `loglevel` int(11) NOT NULL default '0',
  `action` text collate utf8_bin NOT NULL,
  `description` mediumtext collate utf8_bin,
  `data` blob,
  `tablename` varchar(255) collate utf8_bin default NULL,
  `pagename` varchar(255) collate utf8_bin default NULL,
  `ipaddress` varchar(255) collate utf8_bin default NULL,
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `cc_tariffgroup`
--

CREATE TABLE IF NOT EXISTS `cc_tariffgroup` (
  `id` int(11) NOT NULL auto_increment,
  `iduser` int(11) NOT NULL default '0',
  `idtariffplan` int(11) NOT NULL default '0',
  `tariffgroupname` char(50) collate utf8_bin NOT NULL,
  `lcrtype` int(11) NOT NULL default '0',
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `removeinterprefix` int(11) NOT NULL default '0',
  `id_cc_package_offer` bigint(20) NOT NULL default '-1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_tariffgroup`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_tariffgroup_plan`
--

CREATE TABLE IF NOT EXISTS `cc_tariffgroup_plan` (
  `idtariffgroup` int(11) NOT NULL,
  `idtariffplan` int(11) NOT NULL,
  PRIMARY KEY  (`idtariffgroup`,`idtariffplan`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `cc_tariffgroup_plan`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_tariffplan`
--

CREATE TABLE IF NOT EXISTS `cc_tariffplan` (
  `id` int(11) NOT NULL auto_increment,
  `iduser` int(11) NOT NULL default '0',
  `tariffname` char(50) collate utf8_bin NOT NULL,
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `startingdate` timestamp NOT NULL default '0000-00-00 00:00:00',
  `expirationdate` timestamp NOT NULL default '0000-00-00 00:00:00',
  `description` mediumtext collate utf8_bin,
  `id_trunk` int(11) default '0',
  `secondusedreal` int(11) default '0',
  `secondusedcarrier` int(11) default '0',
  `secondusedratecard` int(11) default '0',
  `reftariffplan` int(11) default '0',
  `idowner` int(11) default '0',
  `dnidprefix` char(30) collate utf8_bin NOT NULL default 'all',
  `calleridprefix` char(30) collate utf8_bin NOT NULL default 'all',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `cons_cc_tariffplan_iduser_tariffname` (`iduser`,`tariffname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_tariffplan`
--


-- --------------------------------------------------------

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
  `messagehtml` longtext collate utf8_bin,
  UNIQUE KEY `cons_cc_templatemail_id_language` (`mailtype`,`id_language`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `cc_templatemail`
--

INSERT INTO `cc_templatemail` (`id`, `id_language`, `mailtype`, `fromemail`, `fromname`, `subject`, `messagetext`, `messagehtml`)
VALUES (1, 'en', 'signup', 'info@mydomainname.com', 'COMPANY NAME', 'SIGNUP CONFIRMATION', '\nThank you for registering with us\n\nPlease click on below link to activate your account.\n\nhttp://customer.mydomainname.com/activate.php?key=$loginkey$\n\nPlease make sure you active your account by making payment to us either by\ncredit card, wire transfer, money order, cheque, and western union money\ntransfer, money Gram, and Pay pal.\n\n\nKind regards,\nYourDomain\n', '');
INSERT INTO `cc_templatemail` (`id`, `id_language`, `mailtype`, `fromemail`, `fromname`, `subject`, `messagetext`, `messagehtml`)
VALUES (2, 'en', 'reminder', 'info@mydomainname.com', 'COMPANY NAME', 'Your COMPANY NAME account $cardnumber$ is low on credit ($currency$ $credit$', '\n\nYour COMPANY NAME Account number $cardnumber$ is running low on credit.\n\nThere is currently only $creditcurrency$ $currency$ left on your account which is lower than the warning level defined ($credit_notification$)\n\n\nPlease top up your account ASAP to ensure continued service\n\nIf you no longer wish to receive these notifications or would like to change the balance amount at which these warnings are generated,\nplease connect on your myaccount panel and change the appropriate parameters\n\n\nyour account information :\nYour account number for VOIP authentication : $cardnumber$\n\nhttp://myaccount.mydomainname.com/\nYour account login : $login$\nYour account password : $password$\n\n\nThanks,\n/COMPANY NAME Team\n-------------------------------------\nhttp://www.mydomainname.com\n ', '');
INSERT INTO `cc_templatemail` (`id`, `id_language`, `mailtype`, `fromemail`, `fromname`, `subject`, `messagetext`, `messagehtml`)
VALUES (3, 'en', 'forgetpassword', 'info@mydomainname.com', 'COMPANY NAME', 'Login Information', 'Your login information is as below:\n\nYour account is $cardnumber$\n\nYour password is $password$\n\nYour login is $login$\n\nhttp://mydomainname.com/A2BCustomer_UI/\n\nKind regards,\nYourDomain\n', '');
INSERT INTO `cc_templatemail` (`id`, `id_language`, `mailtype`, `fromemail`, `fromname`, `subject`, `messagetext`, `messagehtml`)
VALUES (4, 'en', 'signupconfirmed', 'info@mydomainname.com', 'COMPANY NAME', 'SIGNUP CONFIRMATION', 'Thank you for registering with us\n\nPlease make sure you active your account by making payment to us either by\ncredit card, wire transfer, money order, cheque, and western union money\ntransfer, money Gram, and Pay pal.\n\nYour account is $cardnumber$\n\nYour password is $password$\n\nTo go to your account :\nhttp://mydomainname.com/customer/\n\nKind regards,\nYourDomain\n', '');
INSERT INTO `cc_templatemail` (`id`, `id_language`, `mailtype`, `fromemail`, `fromname`, `subject`, `messagetext`, `messagehtml`)
VALUES (5, 'en', 'epaymentverify', 'info@mydomainname.com', 'COMPANY NAME', 'Epayment Gateway Security Verification Failed', 'Dear Administrator\n\nPlease check the Epayment Log, System has logged a Epayment Security failure. that may be a possible attack on epayment processing.\n\nTime of Transaction: $time$\nPayment Gateway: $paymentgateway$\nAmount: $itemAmount$\n\n\n\nKind regards,\nYourDomain\n', '');
INSERT INTO `cc_templatemail` (`id`, `id_language`, `mailtype`, `fromemail`, `fromname`, `subject`, `messagetext`, `messagehtml`)
VALUES (6, 'en', 'payment', 'info@mydomainname.com', 'COMPANY NAME', 'PAYMENT CONFIRMATION', 'Thank you for shopping at COMPANY NAME.\n\nShopping details is as below.\n\nItem Name = <b>$itemName$</b>\nItem ID = <b>$itemID$</b>\nAmount = <b>$itemAmount$</b>\nPayment Method = <b>$paymentMethod$</b>\nStatus = <b>$paymentStatus$</b>\n\n\nKind regards,\nYourDomain\n', '');
INSERT INTO `cc_templatemail` (`id`, `id_language`, `mailtype`, `fromemail`, `fromname`, `subject`, `messagetext`, `messagehtml`)
VALUES (7, 'en', 'invoice', 'info@mydomainname.com', 'COMPANY NAME', 'A2BILLING INVOICE', 'Dear Customer.\n\nAttached is the invoice.\n\nKind regards,\nYourDomain\n', '');

-- --------------------------------------------------------

--
-- Table structure for table `cc_ticket`
--

CREATE TABLE IF NOT EXISTS `cc_ticket` (
  `id` bigint(10) NOT NULL auto_increment,
  `id_component` smallint(5) NOT NULL,
  `title` varchar(100) collate utf8_bin NOT NULL,
  `description` text collate utf8_bin,
  `priority` smallint(6) NOT NULL default '0',
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `creator` bigint(20) NOT NULL,
  `status` smallint(6) NOT NULL default '0',
  `creator_type` tinyint(4) NOT NULL default '0',
  `viewed_cust` tinyint(4) NOT NULL default '1',
  `viewed_agent` tinyint(4) NOT NULL default '1',
  `viewed_admin` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_ticket`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_ticket_comment`
--

CREATE TABLE IF NOT EXISTS `cc_ticket_comment` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `id_ticket` bigint(10) NOT NULL,
  `description` text collate utf8_bin,
  `creator` bigint(20) NOT NULL,
  `creator_type` tinyint(4) NOT NULL default '0',
  `viewed_cust` tinyint(4) NOT NULL default '1',
  `viewed_agent` tinyint(4) NOT NULL default '1',
  `viewed_admin` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_ticket_comment`
--


-- --------------------------------------------------------

--
-- Table structure for table `cc_timezone`
--

CREATE TABLE IF NOT EXISTS `cc_timezone` (
  `id` int(11) NOT NULL auto_increment,
  `gmtzone` varchar(255) collate utf8_bin default NULL,
  `gmttime` varchar(255) collate utf8_bin default NULL,
  `gmtoffset` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=76 ;

--
-- Dumping data for table `cc_timezone`
--

INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(1, '(GMT-12:00) International Date Line West', 'GMT-12:00', -43200);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(2, '(GMT-11:00) Midway Island, Samoa', 'GMT-11:00', -39600);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(3, '(GMT-10:00) Hawaii', 'GMT-10:00', -36000);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(4, '(GMT-09:00) Alaska', 'GMT-09:00', -32400);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(5, '(GMT-08:00) Pacific Time (US & Canada) Tijuana', 'GMT-08:00', -28800);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(6, '(GMT-07:00) Arizona', 'GMT-07:00', -25200);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(7, '(GMT-07:00) Chihuahua, La Paz, Mazatlan', 'GMT-07:00', -25200);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(8, '(GMT-07:00) Mountain Time(US & Canada)', 'GMT-07:00', -25200);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(9, '(GMT-06:00) Central America', 'GMT-06:00', -21600);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(10, '(GMT-06:00) Central Time (US & Canada)', 'GMT-06:00', -21600);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(11, '(GMT-06:00) Guadalajara, Mexico City, Monterrey', 'GMT-06:00', -21600);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(12, '(GMT-06:00) Saskatchewan', 'GMT-06:00', -21600);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(13, '(GMT-05:00) Bogota, Lima, Quito', 'GMT-05:00', -18000);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(14, '(GMT-05:00) Eastern Time (US & Canada)', 'GMT-05:00', -18000);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(15, '(GMT-05:00) Indiana (East)', 'GMT-05:00', -18000);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(16, '(GMT-04:00) Atlantic Time (Canada)', 'GMT-04:00', -14400);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(17, '(GMT-04:00) Caracas, La Paz', 'GMT-04:00', -14400);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(18, '(GMT-04:00) Santiago', 'GMT-04:00', -14400);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(19, '(GMT-03:30) NewFoundland', 'GMT-03:30', -12600);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(20, '(GMT-03:00) Brasillia', 'GMT-03:00', -10800);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(21, '(GMT-03:00) Buenos Aires, Georgetown', 'GMT-03:00', -10800);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(22, '(GMT-03:00) Greenland', 'GMT-03:00', -10800);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(23, '(GMT-03:00) Mid-Atlantic', 'GMT-03:00', -10800);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(24, '(GMT-01:00) Azores', 'GMT-01:00', -3600);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(25, '(GMT-01:00) Cape Verd Is.', 'GMT-01:00', -3600);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(26, '(GMT) Casablanca, Monrovia', 'GMT+00:00', 0);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(27, '(GMT) Greenwich Mean Time : Dublin, Edinburgh, Lisbon,  London', 'GMT', 0);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(28, '(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna', 'GMT+01:00', 3600);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(29, '(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague', 'GMT+01:00', 3600);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(30, '(GMT+01:00) Brussels, Copenhagen, Madrid, Paris', 'GMT+01:00', 3600);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(31, '(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb', 'GMT+01:00', 3600);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(32, '(GMT+01:00) West Central Africa', 'GMT+01:00', 3600);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(33, '(GMT+02:00) Athens, Istanbul, Minsk', 'GMT+02:00', 7200);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(34, '(GMT+02:00) Bucharest', 'GMT+02:00', 7200);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(35, '(GMT+02:00) Cairo', 'GMT+02:00', 7200);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(36, '(GMT+02:00) Harere, Pretoria', 'GMT+02:00', 7200);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(37, '(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius', 'GMT+02:00', 7200);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(38, '(GMT+02:00) Jeruasalem', 'GMT+02:00', 7200);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(39, '(GMT+03:00) Baghdad', 'GMT+03:00', 10800);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(40, '(GMT+03:00) Kuwait, Riyadh', 'GMT+03:00', 10800);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(41, '(GMT+03:00) Moscow, St.Petersburg, Volgograd', 'GMT+03:00', 10800);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(42, '(GMT+03:00) Nairobi', 'GMT+03:00', 10800);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(43, '(GMT+03:30) Tehran', 'GMT+03:30', 12600);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(44, '(GMT+04:00) Abu Dhabi, Muscat', 'GMT+04:00', 14400);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(45, '(GMT+04:00) Baku, Tbillisi, Yerevan', 'GMT+04:00', 14400);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(46, '(GMT+04:30) Kabul', 'GMT+04:30', 16200);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(47, '(GMT+05:00) Ekaterinburg', 'GMT+05:00', 18000);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(48, '(GMT+05:00) Islamabad, Karachi, Tashkent', 'GMT+05:00', 18000);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(49, '(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi', 'GMT+05:30', 19800);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(50, '(GMT+05:45) Kathmandu', 'GMT+05:45', 20700);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(51, '(GMT+06:00) Almaty, Novosibirsk', 'GMT+06:00', 21600);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(52, '(GMT+06:00) Astana, Dhaka', 'GMT+06:00', 21600);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(53, '(GMT+06:00) Sri Jayawardenepura', 'GMT+06:00', 21600);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(54, '(GMT+06:30) Rangoon', 'GMT+06:30', 23400);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(55, '(GMT+07:00) Bangkok, Hanoi, Jakarta', 'GMT+07:00', 25200);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(56, '(GMT+07:00) Krasnoyarsk', 'GMT+07:00', 25200);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(57, '(GMT+08:00) Beijiing, Chongging, Hong Kong, Urumqi', 'GMT+08:00', 28800);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(58, '(GMT+08:00) Irkutsk, Ulaan Bataar', 'GMT+08:00', 28800);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(59, '(GMT+08:00) Kuala Lumpur, Singapore', 'GMT+08:00', 28800);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(60, '(GMT+08:00) Perth', 'GMT+08:00', 28800);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(61, '(GMT+08:00) Taipei', 'GMT+08:00', 28800);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(62, '(GMT+09:00) Osaka, Sapporo, Tokyo', 'GMT+09:00', 32400);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(63, '(GMT+09:00) Seoul', 'GMT+09:00', 32400);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(64, '(GMT+09:00) Yakutsk', 'GMT+09:00', 32400);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(65, '(GMT+09:00) Adelaide', 'GMT+09:00', 32400);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(66, '(GMT+09:30) Darwin', 'GMT+09:30', 34200);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(67, '(GMT+10:00) Brisbane', 'GMT+10:00', 36000);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(68, '(GMT+10:00) Canberra, Melbourne, Sydney', 'GMT+10:00', 36000);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(69, '(GMT+10:00) Guam, Port Moresby', 'GMT+10:00', 36000);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(70, '(GMT+10:00) Hobart', 'GMT+10:00', 36000);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(71, '(GMT+10:00) Vladivostok', 'GMT+10:00', 36000);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(72, '(GMT+11:00) Magadan, Solomon Is., New Caledonia', 'GMT+11:00', 39600);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(73, '(GMT+12:00) Auckland, Wellington', 'GMT+1200', 43200);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(74, '(GMT+12:00) Fiji, Kamchatka, Marshall Is.', 'GMT+12:00', 43200);
INSERT INTO `cc_timezone` (`id`, `gmtzone`, `gmttime`, `gmtoffset`) VALUES(75, '(GMT+13:00) Nuku alofa', 'GMT+13:00', 46800);

-- --------------------------------------------------------

--
-- Table structure for table `cc_trunk`
--

CREATE TABLE IF NOT EXISTS `cc_trunk` (
  `id_trunk` int(11) NOT NULL auto_increment,
  `trunkcode` char(20) collate utf8_bin NOT NULL,
  `trunkprefix` char(20) collate utf8_bin default NULL,
  `providertech` char(20) collate utf8_bin NOT NULL,
  `providerip` char(80) collate utf8_bin NOT NULL,
  `removeprefix` char(20) collate utf8_bin default NULL,
  `secondusedreal` int(11) default '0',
  `secondusedcarrier` int(11) default '0',
  `secondusedratecard` int(11) default '0',
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `failover_trunk` int(11) default NULL,
  `addparameter` char(120) collate utf8_bin default NULL,
  `id_provider` int(11) default NULL,
  `inuse` int(11) default '0',
  `maxuse` int(11) default '-1',
  `status` int(11) default '1',
  `if_max_use` int(11) default '0',
  PRIMARY KEY  (`id_trunk`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cc_trunk`
--

INSERT INTO `cc_trunk` (`id_trunk`, `trunkcode`, `trunkprefix`, `providertech`, `providerip`, `removeprefix`, `secondusedreal`, `secondusedcarrier`, `secondusedratecard`, `creationdate`, `failover_trunk`, `addparameter`, `id_provider`, `inuse`, `maxuse`, `status`, `if_max_use`) VALUES(1, 'DEFAULT', '011', 'IAX2', 'examplehost', '', 0, 0, 0, CURRENT_TIMESTAMP, 0, '', NULL, 0, -1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cc_ui_authen`
--

CREATE TABLE IF NOT EXISTS `cc_ui_authen` (
  `userid` bigint(20) NOT NULL auto_increment,
  `login` char(50) collate utf8_bin NOT NULL,
  `pwd_encoded` varchar(250) collate utf8_bin NOT NULL,
  `groupid` int(11) default NULL,
  `perms` int(11) default NULL,
  `confaddcust` int(11) default NULL,
  `name` char(50) collate utf8_bin default NULL,
  `direction` char(80) collate utf8_bin default NULL,
  `zipcode` char(20) collate utf8_bin default NULL,
  `state` char(20) collate utf8_bin default NULL,
  `phone` char(30) collate utf8_bin default NULL,
  `fax` char(30) collate utf8_bin default NULL,
  `datecreation` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `email` varchar(70) collate utf8_bin default NULL,
  PRIMARY KEY  (`userid`),
  UNIQUE KEY `cons_cc_ui_authen_login` (`login`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cc_ui_authen`
--

INSERT INTO `cc_ui_authen` (`userid`, `login`, `pwd_encoded`, `groupid`, `perms`, `confaddcust`, `name`, `direction`, `zipcode`, `state`, `phone`, `fax`, `datecreation`, `email`) VALUES(1, 'root', '410fc6268dd3332226de95e42d9efa4046c5463769d7493b85e65cfa5c26362dc2455cc23c0bc5831deb008def4ab11a9eaa9b76ba3f377da134f39ec60dd758', 0, 5242879, NULL, NULL, NULL, NULL, NULL, NULL, NULL, CURRENT_TIMESTAMP, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cc_voucher`
--

CREATE TABLE IF NOT EXISTS `cc_voucher` (
  `id` bigint(20) NOT NULL auto_increment,
  `creationdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `usedate` timestamp NOT NULL default '0000-00-00 00:00:00',
  `expirationdate` timestamp NOT NULL default '0000-00-00 00:00:00',
  `voucher` char(50) collate utf8_bin NOT NULL,
  `usedcardnumber` char(50) collate utf8_bin default NULL,
  `tag` char(50) collate utf8_bin default NULL,
  `credit` float NOT NULL default '0',
  `activated` char(1) collate utf8_bin NOT NULL default 'f',
  `used` int(11) default '0',
  `currency` char(3) collate utf8_bin default 'USD',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `cons_cc_voucher_voucher` (`voucher`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cc_voucher`
--


-- --------------------------------------------------------

--
-- Structure for view `cc_sip_buddies_empty`
--
DROP TABLE IF EXISTS `cc_sip_buddies_empty`;

CREATE VIEW cc_sip_buddies_empty AS SELECT
id, id_cc_card, name, accountcode, regexten, amaflags, callgroup, callerid, canreinvite, context, DEFAULTip, dtmfmode, fromuser, fromdomain, host, insecure, language, mailbox, md5secret, nat, permit, deny, mask, pickupgroup, port, qualify, restrictcid, rtptimeout, rtpholdtimeout, '' as secret, type, username, disallow, allow, musiconhold, regseconds, ipaddr, cancallforward, fullcontact, setvar
FROM cc_sip_buddies;


ALTER TABLE cc_support_component ADD type_user TINYINT NOT NULL DEFAULT '2';

