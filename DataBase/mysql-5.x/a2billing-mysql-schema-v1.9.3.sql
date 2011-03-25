/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2010 - Star2billing S.L.
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
-- mysql -u root -p [a2billing db name] < a2billing-schema-mysql-v1.9.3.sql


--
-- A2Billing database - Create database schema
--



-- MySQL dump 10.13  Distrib 5.1.49, for debian-linux-gnu (i486)
--
-- Host: localhost    Database: mya2billing
-- ------------------------------------------------------
-- Server version	5.1.49-3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cc_agent`
--

DROP TABLE IF EXISTS `cc_agent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_agent` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `datecreation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` char(1) COLLATE utf8_bin NOT NULL DEFAULT 'f',
  `login` char(20) COLLATE utf8_bin NOT NULL,
  `passwd` char(40) COLLATE utf8_bin DEFAULT NULL,
  `location` text COLLATE utf8_bin,
  `language` char(5) COLLATE utf8_bin DEFAULT 'en',
  `id_tariffgroup` int(11) DEFAULT NULL,
  `options` int(11) NOT NULL DEFAULT '0',
  `credit` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `currency` char(3) COLLATE utf8_bin DEFAULT 'USD',
  `locale` char(10) COLLATE utf8_bin DEFAULT 'C',
  `commission` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `vat` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `banner` text COLLATE utf8_bin,
  `perms` int(11) DEFAULT NULL,
  `lastname` char(50) COLLATE utf8_bin DEFAULT NULL,
  `firstname` char(50) COLLATE utf8_bin DEFAULT NULL,
  `address` char(100) COLLATE utf8_bin DEFAULT NULL,
  `city` char(40) COLLATE utf8_bin DEFAULT NULL,
  `state` char(40) COLLATE utf8_bin DEFAULT NULL,
  `country` char(40) COLLATE utf8_bin DEFAULT NULL,
  `zipcode` char(20) COLLATE utf8_bin DEFAULT NULL,
  `phone` char(20) COLLATE utf8_bin DEFAULT NULL,
  `email` char(70) COLLATE utf8_bin DEFAULT NULL,
  `fax` char(20) COLLATE utf8_bin DEFAULT NULL,
  `company` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `com_balance` decimal(15,5) NOT NULL,
  `threshold_remittance` decimal(15,5) NOT NULL,
  `bank_info` mediumtext COLLATE utf8_bin,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_agent`
--

LOCK TABLES `cc_agent` WRITE;
/*!40000 ALTER TABLE `cc_agent` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_agent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_agent_commission`
--

DROP TABLE IF EXISTS `cc_agent_commission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_agent_commission` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_payment` bigint(20) DEFAULT NULL,
  `id_card` bigint(20) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `amount` decimal(15,5) NOT NULL,
  `description` mediumtext COLLATE utf8_bin,
  `id_agent` int(11) NOT NULL,
  `commission_type` tinyint(4) NOT NULL,
  `commission_percent` decimal(10,4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_agent_commission`
--

LOCK TABLES `cc_agent_commission` WRITE;
/*!40000 ALTER TABLE `cc_agent_commission` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_agent_commission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_agent_signup`
--

DROP TABLE IF EXISTS `cc_agent_signup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_agent_signup` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_agent` int(11) NOT NULL,
  `code` varchar(30) COLLATE utf8_bin NOT NULL,
  `id_tariffgroup` int(11) NOT NULL,
  `id_group` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_agent_signup`
--

LOCK TABLES `cc_agent_signup` WRITE;
/*!40000 ALTER TABLE `cc_agent_signup` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_agent_signup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_agent_tariffgroup`
--

DROP TABLE IF EXISTS `cc_agent_tariffgroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_agent_tariffgroup` (
  `id_agent` bigint(20) NOT NULL,
  `id_tariffgroup` int(11) NOT NULL,
  PRIMARY KEY (`id_agent`,`id_tariffgroup`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_agent_tariffgroup`
--

LOCK TABLES `cc_agent_tariffgroup` WRITE;
/*!40000 ALTER TABLE `cc_agent_tariffgroup` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_agent_tariffgroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_alarm`
--

DROP TABLE IF EXISTS `cc_alarm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_alarm` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_bin NOT NULL,
  `periode` int(11) NOT NULL DEFAULT '1',
  `type` int(11) NOT NULL DEFAULT '1',
  `maxvalue` float NOT NULL,
  `minvalue` float NOT NULL DEFAULT '-1',
  `id_trunk` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `numberofrun` int(11) NOT NULL DEFAULT '0',
  `numberofalarm` int(11) NOT NULL DEFAULT '0',
  `datecreate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `datelastrun` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `emailreport` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_alarm`
--

LOCK TABLES `cc_alarm` WRITE;
/*!40000 ALTER TABLE `cc_alarm` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_alarm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_alarm_report`
--

DROP TABLE IF EXISTS `cc_alarm_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_alarm_report` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cc_alarm_id` bigint(20) NOT NULL,
  `calculatedvalue` float NOT NULL,
  `daterun` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_alarm_report`
--

LOCK TABLES `cc_alarm_report` WRITE;
/*!40000 ALTER TABLE `cc_alarm_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_alarm_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_autorefill_report`
--

DROP TABLE IF EXISTS `cc_autorefill_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_autorefill_report` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `daterun` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `totalcardperform` int(11) DEFAULT NULL,
  `totalcredit` decimal(15,5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_autorefill_report`
--

LOCK TABLES `cc_autorefill_report` WRITE;
/*!40000 ALTER TABLE `cc_autorefill_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_autorefill_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_backup`
--

DROP TABLE IF EXISTS `cc_backup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_backup` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `path` varchar(255) COLLATE utf8_bin NOT NULL,
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cons_cc_backup_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_backup`
--

LOCK TABLES `cc_backup` WRITE;
/*!40000 ALTER TABLE `cc_backup` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_backup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_billing_customer`
--

DROP TABLE IF EXISTS `cc_billing_customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_billing_customer` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_card` bigint(20) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_invoice` bigint(20) NOT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_billing_customer`
--

LOCK TABLES `cc_billing_customer` WRITE;
/*!40000 ALTER TABLE `cc_billing_customer` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_billing_customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_call`
--

DROP TABLE IF EXISTS `cc_call`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_call` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sessionid` varchar(40) COLLATE utf8_bin NOT NULL,
  `uniqueid` varchar(30) COLLATE utf8_bin NOT NULL,
  `card_id` bigint(20) NOT NULL,
  `nasipaddress` varchar(30) COLLATE utf8_bin NOT NULL,
  `starttime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `stoptime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sessiontime` int(11) DEFAULT NULL,
  `calledstation` varchar(50) COLLATE utf8_bin NOT NULL,
  `sessionbill` float DEFAULT NULL,
  `id_tariffgroup` int(11) DEFAULT NULL,
  `id_tariffplan` int(11) DEFAULT NULL,
  `id_ratecard` int(11) DEFAULT NULL,
  `id_trunk` int(11) DEFAULT NULL,
  `sipiax` int(11) DEFAULT '0',
  `src` varchar(40) COLLATE utf8_bin NOT NULL,
  `id_did` int(11) DEFAULT NULL,
  `buycost` decimal(15,5) DEFAULT '0.00000',
  `id_card_package_offer` int(11) DEFAULT '0',
  `real_sessiontime` int(11) DEFAULT NULL,
  `dnid` varchar(40) COLLATE utf8_bin NOT NULL,
  `terminatecauseid` int(1) DEFAULT '1',
  `destination` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `starttime` (`starttime`),
  KEY `calledstation` (`calledstation`),
  KEY `terminatecauseid` (`terminatecauseid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_call`
--

LOCK TABLES `cc_call` WRITE;
/*!40000 ALTER TABLE `cc_call` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_call` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_call_archive`
--

DROP TABLE IF EXISTS `cc_call_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_call_archive` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sessionid` varchar(40) COLLATE utf8_bin NOT NULL,
  `uniqueid` varchar(30) COLLATE utf8_bin NOT NULL,
  `card_id` bigint(20) NOT NULL,
  `nasipaddress` varchar(30) COLLATE utf8_bin NOT NULL,
  `starttime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `stoptime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sessiontime` int(11) DEFAULT NULL,
  `calledstation` varchar(30) COLLATE utf8_bin NOT NULL,
  `sessionbill` float DEFAULT NULL,
  `id_tariffgroup` int(11) DEFAULT NULL,
  `id_tariffplan` int(11) DEFAULT NULL,
  `id_ratecard` int(11) DEFAULT NULL,
  `id_trunk` int(11) DEFAULT NULL,
  `sipiax` int(11) DEFAULT '0',
  `src` varchar(40) COLLATE utf8_bin NOT NULL,
  `id_did` int(11) DEFAULT NULL,
  `buycost` decimal(15,5) DEFAULT '0.00000',
  `id_card_package_offer` int(11) DEFAULT '0',
  `real_sessiontime` int(11) DEFAULT NULL,
  `dnid` varchar(40) COLLATE utf8_bin NOT NULL,
  `terminatecauseid` int(1) DEFAULT '1',
  `destination` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `starttime` (`starttime`),
  KEY `calledstation` (`calledstation`),
  KEY `terminatecauseid` (`terminatecauseid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_call_archive`
--

LOCK TABLES `cc_call_archive` WRITE;
/*!40000 ALTER TABLE `cc_call_archive` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_call_archive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_callback_spool`
--

DROP TABLE IF EXISTS `cc_callback_spool`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_callback_spool` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueid` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `entry_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `server_ip` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `num_attempt` int(11) NOT NULL DEFAULT '0',
  `last_attempt_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `manager_result` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  `agi_result` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  `callback_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `channel` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  `exten` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  `context` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  `priority` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  `application` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  `data` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  `timeout` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  `callerid` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  `variable` varchar(2000) COLLATE utf8_bin DEFAULT NULL,
  `account` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  `async` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  `actionid` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  `id_server` int(11) DEFAULT NULL,
  `id_server_group` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cc_callback_spool_uniqueid_key` (`uniqueid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_callback_spool`
--

LOCK TABLES `cc_callback_spool` WRITE;
/*!40000 ALTER TABLE `cc_callback_spool` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_callback_spool` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_callerid`
--

DROP TABLE IF EXISTS `cc_callerid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_callerid` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cid` varchar(100) COLLATE utf8_bin NOT NULL,
  `id_cc_card` bigint(20) NOT NULL,
  `activated` char(1) COLLATE utf8_bin NOT NULL DEFAULT 't',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cons_cc_callerid_cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_callerid`
--

LOCK TABLES `cc_callerid` WRITE;
/*!40000 ALTER TABLE `cc_callerid` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_callerid` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `cc_callplan_lcr`
--

DROP TABLE IF EXISTS `cc_callplan_lcr`;
/*!50001 DROP VIEW IF EXISTS `cc_callplan_lcr`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `cc_callplan_lcr` (
  `id` int(11),
  `destination` varchar(60),
  `dialprefix` char(30),
  `buyrate` decimal(15,5),
  `rateinitial` decimal(15,5),
  `startdate` timestamp,
  `stopdate` timestamp,
  `initblock` int(11),
  `connectcharge` decimal(15,5),
  `id_trunk` int(11),
  `idtariffplan` int(11),
  `ratecard_id` int(11),
  `tariffgroup_id` int(11)
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `cc_campaign`
--

DROP TABLE IF EXISTS `cc_campaign`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_campaign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(50) COLLATE utf8_bin NOT NULL,
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `startingdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expirationdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `description` mediumtext COLLATE utf8_bin,
  `id_card` bigint(20) NOT NULL DEFAULT '0',
  `secondusedreal` int(11) DEFAULT '0',
  `nb_callmade` int(11) DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  `frequency` int(11) NOT NULL DEFAULT '20',
  `forward_number` char(50) COLLATE utf8_bin DEFAULT NULL,
  `daily_start_time` time NOT NULL DEFAULT '10:00:00',
  `daily_stop_time` time NOT NULL DEFAULT '18:00:00',
  `monday` tinyint(4) NOT NULL DEFAULT '1',
  `tuesday` tinyint(4) NOT NULL DEFAULT '1',
  `wednesday` tinyint(4) NOT NULL DEFAULT '1',
  `thursday` tinyint(4) NOT NULL DEFAULT '1',
  `friday` tinyint(4) NOT NULL DEFAULT '1',
  `saturday` tinyint(4) NOT NULL DEFAULT '0',
  `sunday` tinyint(4) NOT NULL DEFAULT '0',
  `id_cid_group` int(11) NOT NULL,
  `id_campaign_config` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cons_cc_campaign_campaign_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_campaign`
--

LOCK TABLES `cc_campaign` WRITE;
/*!40000 ALTER TABLE `cc_campaign` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_campaign` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_campaign_config`
--

DROP TABLE IF EXISTS `cc_campaign_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_campaign_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) COLLATE utf8_bin NOT NULL,
  `flatrate` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `context` varchar(40) COLLATE utf8_bin NOT NULL,
  `description` mediumtext COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_campaign_config`
--

LOCK TABLES `cc_campaign_config` WRITE;
/*!40000 ALTER TABLE `cc_campaign_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_campaign_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_campaign_phonebook`
--

DROP TABLE IF EXISTS `cc_campaign_phonebook`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_campaign_phonebook` (
  `id_campaign` int(11) NOT NULL,
  `id_phonebook` int(11) NOT NULL,
  PRIMARY KEY (`id_campaign`,`id_phonebook`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_campaign_phonebook`
--

LOCK TABLES `cc_campaign_phonebook` WRITE;
/*!40000 ALTER TABLE `cc_campaign_phonebook` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_campaign_phonebook` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_campaign_phonestatus`
--

DROP TABLE IF EXISTS `cc_campaign_phonestatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_campaign_phonestatus` (
  `id_phonenumber` bigint(20) NOT NULL,
  `id_campaign` int(11) NOT NULL,
  `id_callback` varchar(40) COLLATE utf8_bin NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `lastuse` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_phonenumber`,`id_campaign`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_campaign_phonestatus`
--

LOCK TABLES `cc_campaign_phonestatus` WRITE;
/*!40000 ALTER TABLE `cc_campaign_phonestatus` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_campaign_phonestatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_campaignconf_cardgroup`
--

DROP TABLE IF EXISTS `cc_campaignconf_cardgroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_campaignconf_cardgroup` (
  `id_campaign_config` int(11) NOT NULL,
  `id_card_group` int(11) NOT NULL,
  PRIMARY KEY (`id_campaign_config`,`id_card_group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_campaignconf_cardgroup`
--

LOCK TABLES `cc_campaignconf_cardgroup` WRITE;
/*!40000 ALTER TABLE `cc_campaignconf_cardgroup` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_campaignconf_cardgroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_card`
--

DROP TABLE IF EXISTS `cc_card`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_card` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `firstusedate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expirationdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `enableexpire` int(11) DEFAULT '0',
  `expiredays` int(11) DEFAULT '0',
  `username` varchar(50) COLLATE utf8_bin NOT NULL,
  `useralias` varchar(50) COLLATE utf8_bin NOT NULL,
  `uipass` varchar(50) COLLATE utf8_bin NOT NULL,
  `credit` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `tariff` int(11) DEFAULT '0',
  `id_didgroup` int(11) DEFAULT '0',
  `activated` char(1) COLLATE utf8_bin NOT NULL DEFAULT 'f',
  `status` int(11) NOT NULL DEFAULT '1',
  `lastname` varchar(50) COLLATE utf8_bin NOT NULL,
  `firstname` varchar(50) COLLATE utf8_bin NOT NULL,
  `address` varchar(100) COLLATE utf8_bin NOT NULL,
  `city` varchar(40) COLLATE utf8_bin NOT NULL,
  `state` varchar(40) COLLATE utf8_bin NOT NULL,
  `country` varchar(40) COLLATE utf8_bin NOT NULL,
  `zipcode` varchar(20) COLLATE utf8_bin NOT NULL,
  `phone` varchar(20) COLLATE utf8_bin NOT NULL,
  `email` varchar(70) COLLATE utf8_bin NOT NULL,
  `fax` varchar(20) COLLATE utf8_bin NOT NULL,
  `inuse` int(11) DEFAULT '0',
  `simultaccess` int(11) DEFAULT '0',
  `currency` char(3) COLLATE utf8_bin DEFAULT 'USD',
  `lastuse` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `nbused` int(11) DEFAULT '0',
  `typepaid` int(11) DEFAULT '0',
  `creditlimit` int(11) DEFAULT '0',
  `voipcall` int(11) DEFAULT '0',
  `sip_buddy` int(11) DEFAULT '0',
  `iax_buddy` int(11) DEFAULT '0',
  `language` char(5) COLLATE utf8_bin DEFAULT 'en',
  `redial` varchar(50) COLLATE utf8_bin NOT NULL,
  `runservice` int(11) DEFAULT '0',
  `nbservice` int(11) DEFAULT '0',
  `id_campaign` int(11) DEFAULT '0',
  `num_trials_done` bigint(20) DEFAULT '0',
  `vat` float NOT NULL DEFAULT '0',
  `servicelastrun` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `initialbalance` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `invoiceday` int(11) DEFAULT '1',
  `autorefill` int(11) DEFAULT '0',
  `loginkey` varchar(40) COLLATE utf8_bin NOT NULL,
  `mac_addr` char(17) COLLATE utf8_bin NOT NULL DEFAULT '00-00-00-00-00-00',
  `id_timezone` int(11) DEFAULT '0',
  `tag` varchar(50) COLLATE utf8_bin NOT NULL,
  `voicemail_permitted` int(11) NOT NULL DEFAULT '0',
  `voicemail_activated` smallint(6) NOT NULL DEFAULT '0',
  `last_notification` timestamp NULL DEFAULT NULL,
  `email_notification` varchar(70) COLLATE utf8_bin NOT NULL,
  `notify_email` smallint(6) NOT NULL DEFAULT '0',
  `credit_notification` int(11) NOT NULL DEFAULT '-1',
  `id_group` int(11) NOT NULL DEFAULT '1',
  `company_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `company_website` varchar(60) COLLATE utf8_bin NOT NULL,
  `vat_rn` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `traffic` bigint(20) DEFAULT NULL,
  `traffic_target` varchar(300) COLLATE utf8_bin NOT NULL,
  `discount` decimal(5,2) NOT NULL DEFAULT '0.00',
  `restriction` tinyint(4) NOT NULL DEFAULT '0',
  `id_seria` int(11) DEFAULT NULL,
  `serial` bigint(20) DEFAULT NULL,
  `block` tinyint(4) NOT NULL DEFAULT '0',
  `lock_pin` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `lock_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cons_cc_card_username` (`username`),
  UNIQUE KEY `cons_cc_card_useralias` (`useralias`),
  KEY `creationdate` (`creationdate`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_card`
--

LOCK TABLES `cc_card` WRITE;
/*!40000 ALTER TABLE `cc_card` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_card` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `cc_card_serial_set` BEFORE INSERT ON `cc_card`
 FOR EACH ROW BEGIN
	UPDATE cc_card_seria set value=value+1  where id=NEW.id_seria ;
	SELECT value INTO @serial from cc_card_seria where id=NEW.id_seria ;
	SET NEW.serial=@serial;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `cc_card_serial_update` BEFORE UPDATE ON `cc_card`
 FOR EACH ROW BEGIN
	IF NEW.id_seria<>OLD.id_seria OR OLD.id_seria IS NULL THEN
		UPDATE cc_card_seria set value=value+1  where id=NEW.id_seria ;
		SELECT value INTO @serial from cc_card_seria where id=NEW.id_seria ;
		SET NEW.serial=@serial;
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `cc_card_archive`
--

DROP TABLE IF EXISTS `cc_card_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_card_archive` (
  `id` bigint(20) NOT NULL,
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `firstusedate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expirationdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `enableexpire` int(11) DEFAULT '0',
  `expiredays` int(11) DEFAULT '0',
  `username` char(50) COLLATE utf8_bin NOT NULL,
  `useralias` char(50) COLLATE utf8_bin NOT NULL,
  `uipass` char(50) COLLATE utf8_bin DEFAULT NULL,
  `credit` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `tariff` int(11) DEFAULT '0',
  `id_didgroup` int(11) DEFAULT '0',
  `activated` char(1) COLLATE utf8_bin NOT NULL DEFAULT 'f',
  `status` int(11) DEFAULT '1',
  `lastname` char(50) COLLATE utf8_bin DEFAULT NULL,
  `firstname` char(50) COLLATE utf8_bin DEFAULT NULL,
  `address` char(100) COLLATE utf8_bin DEFAULT NULL,
  `city` char(40) COLLATE utf8_bin DEFAULT NULL,
  `state` char(40) COLLATE utf8_bin DEFAULT NULL,
  `country` char(40) COLLATE utf8_bin DEFAULT NULL,
  `zipcode` char(20) COLLATE utf8_bin DEFAULT NULL,
  `phone` char(20) COLLATE utf8_bin DEFAULT NULL,
  `email` char(70) COLLATE utf8_bin DEFAULT NULL,
  `fax` char(20) COLLATE utf8_bin DEFAULT NULL,
  `inuse` int(11) DEFAULT '0',
  `simultaccess` int(11) DEFAULT '0',
  `currency` char(3) COLLATE utf8_bin DEFAULT 'USD',
  `lastuse` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `nbused` int(11) DEFAULT '0',
  `typepaid` int(11) DEFAULT '0',
  `creditlimit` int(11) DEFAULT '0',
  `voipcall` int(11) DEFAULT '0',
  `sip_buddy` int(11) DEFAULT '0',
  `iax_buddy` int(11) DEFAULT '0',
  `language` char(5) COLLATE utf8_bin DEFAULT 'en',
  `redial` char(50) COLLATE utf8_bin DEFAULT NULL,
  `runservice` int(11) DEFAULT '0',
  `nbservice` int(11) DEFAULT '0',
  `id_campaign` int(11) DEFAULT '0',
  `num_trials_done` bigint(20) DEFAULT '0',
  `vat` float NOT NULL DEFAULT '0',
  `servicelastrun` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `initialbalance` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `invoiceday` int(11) DEFAULT '1',
  `autorefill` int(11) DEFAULT '0',
  `loginkey` char(40) COLLATE utf8_bin DEFAULT NULL,
  `activatedbyuser` char(1) COLLATE utf8_bin NOT NULL DEFAULT 't',
  `id_timezone` int(11) DEFAULT '0',
  `tag` char(50) COLLATE utf8_bin DEFAULT NULL,
  `voicemail_permitted` int(11) NOT NULL DEFAULT '0',
  `voicemail_activated` smallint(6) NOT NULL DEFAULT '0',
  `last_notification` timestamp NULL DEFAULT NULL,
  `email_notification` char(70) COLLATE utf8_bin DEFAULT NULL,
  `notify_email` smallint(6) NOT NULL DEFAULT '0',
  `credit_notification` int(11) NOT NULL DEFAULT '-1',
  `id_group` int(11) NOT NULL DEFAULT '1',
  `company_name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `company_website` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  `VAT_RN` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `traffic` bigint(20) DEFAULT NULL,
  `traffic_target` mediumtext COLLATE utf8_bin,
  `discount` decimal(5,2) NOT NULL DEFAULT '0.00',
  `restriction` tinyint(4) NOT NULL DEFAULT '0',
  `mac_addr` char(17) COLLATE utf8_bin NOT NULL DEFAULT '00-00-00-00-00-00',
  PRIMARY KEY (`id`),
  KEY `creationdate` (`creationdate`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_card_archive`
--

LOCK TABLES `cc_card_archive` WRITE;
/*!40000 ALTER TABLE `cc_card_archive` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_card_archive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_card_group`
--

DROP TABLE IF EXISTS `cc_card_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_card_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `description` varchar(400) COLLATE utf8_bin DEFAULT NULL,
  `users_perms` int(11) NOT NULL DEFAULT '0',
  `id_agent` int(11) DEFAULT NULL,
  `provisioning` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_card_group`
--

LOCK TABLES `cc_card_group` WRITE;
/*!40000 ALTER TABLE `cc_card_group` DISABLE KEYS */;
INSERT INTO `cc_card_group` VALUES (1,'DEFAULT','This group is the default group used when you create a customer. It\'s forbidden to delete it because you need at least one group but you can edit it.',262142,NULL,NULL);
/*!40000 ALTER TABLE `cc_card_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_card_history`
--

DROP TABLE IF EXISTS `cc_card_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_card_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_cc_card` bigint(20) DEFAULT NULL,
  `datecreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_card_history`
--

LOCK TABLES `cc_card_history` WRITE;
/*!40000 ALTER TABLE `cc_card_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_card_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_card_package_offer`
--

DROP TABLE IF EXISTS `cc_card_package_offer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_card_package_offer` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_cc_card` bigint(20) NOT NULL,
  `id_cc_package_offer` bigint(20) NOT NULL,
  `date_consumption` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `used_secondes` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ind_cc_card_package_offer_id_card` (`id_cc_card`),
  KEY `ind_cc_card_package_offer_id_package_offer` (`id_cc_package_offer`),
  KEY `ind_cc_card_package_offer_date_consumption` (`date_consumption`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_card_package_offer`
--

LOCK TABLES `cc_card_package_offer` WRITE;
/*!40000 ALTER TABLE `cc_card_package_offer` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_card_package_offer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_card_seria`
--

DROP TABLE IF EXISTS `cc_card_seria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_card_seria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(30) COLLATE utf8_bin NOT NULL,
  `description` mediumtext COLLATE utf8_bin,
  `value` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_card_seria`
--

LOCK TABLES `cc_card_seria` WRITE;
/*!40000 ALTER TABLE `cc_card_seria` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_card_seria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_card_subscription`
--

DROP TABLE IF EXISTS `cc_card_subscription`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_card_subscription` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_cc_card` bigint(20) DEFAULT NULL,
  `id_subscription_fee` int(11) DEFAULT NULL,
  `startdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `stopdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `product_id` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `product_name` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `paid_status` tinyint(4) NOT NULL DEFAULT '0',
  `last_run` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `next_billing_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `limit_pay_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_card_subscription`
--

LOCK TABLES `cc_card_subscription` WRITE;
/*!40000 ALTER TABLE `cc_card_subscription` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_card_subscription` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_cardgroup_service`
--

DROP TABLE IF EXISTS `cc_cardgroup_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_cardgroup_service` (
  `id_card_group` int(11) NOT NULL,
  `id_service` int(11) NOT NULL,
  PRIMARY KEY (`id_card_group`,`id_service`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_cardgroup_service`
--

LOCK TABLES `cc_cardgroup_service` WRITE;
/*!40000 ALTER TABLE `cc_cardgroup_service` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_cardgroup_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_charge`
--

DROP TABLE IF EXISTS `cc_charge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_charge` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_cc_card` bigint(20) NOT NULL,
  `iduser` int(11) NOT NULL DEFAULT '0',
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `amount` float NOT NULL DEFAULT '0',
  `chargetype` int(11) DEFAULT '0',
  `description` mediumtext COLLATE utf8_bin,
  `id_cc_did` bigint(20) DEFAULT '0',
  `id_cc_card_subscription` bigint(20) DEFAULT NULL,
  `cover_from` date DEFAULT NULL,
  `cover_to` date DEFAULT NULL,
  `charged_status` tinyint(4) NOT NULL DEFAULT '0',
  `invoiced_status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ind_cc_charge_id_cc_card` (`id_cc_card`),
  KEY `ind_cc_charge_creationdate` (`creationdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_charge`
--

LOCK TABLES `cc_charge` WRITE;
/*!40000 ALTER TABLE `cc_charge` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_charge` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_config`
--

DROP TABLE IF EXISTS `cc_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_title` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `config_key` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `config_value` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `config_description` varchar(500) COLLATE utf8_bin DEFAULT NULL,
  `config_valuetype` int(11) NOT NULL DEFAULT '0',
  `config_listvalues` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `config_group_title` varchar(64) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=306 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_config`
--

LOCK TABLES `cc_config` WRITE;
/*!40000 ALTER TABLE `cc_config` DISABLE KEYS */;
INSERT INTO `cc_config` VALUES (1,'Card Number length','interval_len_cardnumber','10-15','Card Number length, You can define a Range e.g: 10-15.',0,'10-15,5-20,10-30','global'),(2,'Card Alias length','len_aliasnumber','15','Card Number Alias Length e.g: 15.',0,NULL,'global'),(3,'Voucher length','len_voucher','15','Voucher Number Length.',0,NULL,'global'),(4,'Base Currency','base_currency','usd','Base Currency to use for application.',0,NULL,'global'),(5,'Invoice Image','invoice_image','asterisk01.jpg','Image to Display on the Top of Invoice',0,NULL,'global'),(6,'Admin Email','admin_email','root@localhost','Web Administrator Email Address.',0,NULL,'global'),(7,'DID Billing Days to pay','didbilling_daytopay','5','Define the amount of days you want to give to the user before releasing its DIDs',0,NULL,'global'),(8,'Manager Host','manager_host','localhost','Manager Host Address',0,NULL,'global'),(9,'Manager User ID','manager_username','myasterisk','Manger Host User Name',0,NULL,'global'),(10,'Manager Password','manager_secret','mycode','Manager Host Password',0,NULL,'global'),(11,'Use SMTP Server','smtp_server','0','Define if you want to use an STMP server or Send Mail (value yes for server SMTP)',1,'yes,no','global'),(12,'SMTP Host','smtp_host','localhost','SMTP Hostname',0,NULL,'global'),(13,'SMTP UserName','smtp_username','','User Name to connect on the SMTP server',0,NULL,'global'),(14,'SMTP Password','smtp_password','','Password to connect on the SMTP server',0,NULL,'global'),(15,'Use Realtime','use_realtime','1','if Disabled, it will generate the config files and offer an option to reload asterisk after an update on the Voip settings',1,'yes,no','global'),(16,'Go To Customer','customer_ui_url','../../customer/index.php','Link to the customer account',0,NULL,'global'),(17,'Context Callback','context_callback','a2billing-callback','Contaxt to use in Callback',0,NULL,'callback'),(18,'Extension','extension','1000','Extension to call while callback.',0,NULL,'callback'),(19,'Wait before callback','sec_wait_before_callback','10','Seconds to wait before callback.',0,NULL,'callback'),(20,'Avoid Repeat Duration','sec_avoid_repeate','10','Number of seconds before the call-back can be re-initiated from the web page to prevent repeated and unwanted calls.',0,NULL,'callback'),(21,'Time out','timeout','20','if the callback doesnt succeed within the value below, then the call is deemed to have failed.',0,NULL,'callback'),(22,'Answer on Call','answer_call','1','if we want to manage the answer on the call. Disabling this for callback trigger numbers makes it ring not hang up.',1,'yes,no','callback'),(23,'No of Predictive Calls','nb_predictive_call','10','number of calls an agent will do when the call button is clicked.',0,NULL,'callback'),(24,'Delay for Availability','nb_day_wait_before_retry','1','Number of days to wait before the number becomes available to call again.',0,NULL,'callback'),(25,'PD Contect','context_preditctivedialer','a2billing-predictivedialer','The context to redirect the call for the predictive dialer.',0,NULL,'callback'),(26,'Max Time to call','predictivedialer_maxtime_tocall','5400','When a call is made we need to limit the call duration : amount in seconds.',0,NULL,'callback'),(27,'PD Caller ID','callerid','123456','Set the callerID for the predictive dialer and call-back.',0,NULL,'callback'),(28,'Callback CallPlan ID','all_callback_tariff','1','ID Call Plan to use when you use the all-callback mode, check the ID in the \"list Call Plan\" - WebUI.',0,NULL,'callback'),(29,'Server Group ID','id_server_group','1','Define the group of servers that are going to be used by the callback.',0,NULL,'callback'),(30,'Audio Intro','callback_audio_intro','prepaid-callback_intro','Audio intro message when the callback is initiate.',0,NULL,'callback'),(256,'SMTP Secure','smtp_secure','','sets the prefix to the SMTP server : tls ; ssl',0,NULL,'global'),(255,'SMTP Port','smtp_port','25','Port to connect on the SMTP server',0,NULL,'global'),(254,'Return URL distant Forget Password','return_url_distant_forgetpassword','','URL for specific return if an error occur after forgetpassword',0,NULL,'webcustomerui'),(47,'WebPhone Server','webphoneserver','localhost','IP address or domain name of asterisk server that would be used by the web-phone.',0,NULL,'webcustomerui'),(50,'CallerID Limit','limit_callerid','5','The total number of callerIDs for CLI Recognition that can be add by the customer.',0,NULL,'webcustomerui'),(51,'Trunk Name','sip_iax_info_trunkname','call-labs','Trunk Name to show in sip/iax info.',0,NULL,'sip-iax-info'),(52,'Codecs Allowed','sip_iax_info_allowcodec','g729','Allowed Codec, ulaw, gsm, g729.',0,NULL,'sip-iax-info'),(53,'Host','sip_iax_info_host','call-labs.com','Host information.',0,NULL,'sip-iax-info'),(54,'IAX Parms','iax_additional_parameters','canreinvite = no','IAX Additional Parameters.',0,NULL,'sip-iax-info'),(55,'SIP Parms','sip_additional_parameters','trustrpid = yes | sendrpid = yes | canreinvite = no','SIP Additional Parameters.',0,NULL,'sip-iax-info'),(56,'Enable','enable','1','Enable/Disable.',1,'yes,no','epayment_method'),(57,'HTTP Server Customer','http_server','http://www.call-labs.com','Set the Server Address of Customer Website, It should be empty for productive Servers.',0,NULL,'epayment_method'),(58,'HTTPS Server Customer','https_server','https://www.call-labs.com','https://localhost - Enter here your Secure Customers Server Address, should not be empty for productive servers.',0,NULL,'epayment_method'),(59,'Server Customer IP/Domain','http_cookie_domain','26.63.165.200','Enter your Domain Name or IP Address for the Customers application, eg, 26.63.165.200.',0,NULL,'epayment_method'),(60,'Secure Server Customer IP/Domain','https_cookie_domain','26.63.165.200','Enter your Secure server Domain Name or IP Address for the Customers application, eg, 26.63.165.200.',0,NULL,'epayment_method'),(61,'Application Customer Path','http_cookie_path','/customer/','Enter the Physical path of your Customers Application on your server.',0,NULL,'epayment_method'),(62,'Secure Application Customer Path','https_cookie_path','/customer/','Enter the Physical path of your Customers Application on your Secure Server.',0,NULL,'epayment_method'),(63,'Application Customer Physical Path','dir_ws_http_catalog','/customer/','Enter the Physical path of your Customers Application on your server.',0,NULL,'epayment_method'),(64,'Secure Application Customer Physical Path','dir_ws_https_catalog','/customer/','Enter the Physical path of your Customers Application on your Secure server.',0,NULL,'epayment_method'),(65,'Enable SSL','enable_ssl','1','secure webserver for checkout procedure?',1,'yes,no','epayment_method'),(66,'HTTP Domain','http_domain','26.63.165.200','Http Address.',0,NULL,'epayment_method'),(67,'Directory Path','dir_ws_http','/customer/','Directory Path.',0,NULL,'epayment_method'),(68,'Payment Amount','purchase_amount','1:2:5:10:20','define the different amount of purchase that would be available - 5 amount maximum (5:10:15).',0,NULL,'epayment_method'),(69,'Item Name','item_name','Credit Purchase','Item name that would be display to the user when he will buy credit.',0,NULL,'epayment_method'),(70,'Currency Code','currency_code','USD','Currency for the Credit purchase, only one can be define here.',0,NULL,'epayment_method'),(71,'Paypal Payment URL','paypal_payment_url','https://www.paypal.com/cgi-bin/webscr','Define here the URL of paypal gateway the payment (to test with paypal sandbox).',0,NULL,'epayment_method'),(72,'Paypal Verify URL','paypal_verify_url','ssl://www.paypal.com','paypal transaction verification url.',0,NULL,'epayment_method'),(73,'Authorize.NET Payment URL','authorize_payment_url','https://secure.authorize.net/gateway/transact.dll','Define here the URL of Authorize gateway.',0,NULL,'epayment_method'),(74,'PayPal Store Name','store_name','Asterisk2Billing','paypal store name to show in the paypal site when customer will go to pay.',0,NULL,'epayment_method'),(75,'Transaction Key','transaction_key','asdf1212fasd121554sd4f5s45sdf','Transaction Key for security of Epayment Max length of 60 Characters.',0,NULL,'epayment_method'),(76,'Secret Word','moneybookers_secretword','','Moneybookers secret word.',0,NULL,'epayment_method'),(77,'Enable','enable_signup','1','Enable Signup Module.',1,'yes,no','signup'),(78,'Captcha Security','enable_captcha','1','enable Captcha on the signup module (value : YES or NO).',1,'yes,no','signup'),(79,'Credit','credit','0','amount of credit applied to a new user.',0,NULL,'signup'),(80,'CallPlan ID List','callplan_id_list','1,2','the list of id of call plans which will be shown in signup.',0,NULL,'signup'),(81,'Card Activation','activated','0','Specify whether the card is created as active or pending.',1,'yes,no','signup'),(82,'Access Type','simultaccess','0','Simultaneous or non concurrent access with the card - 0 = INDIVIDUAL ACCESS or 1 = SIMULTANEOUS ACCESS.',0,NULL,'signup'),(83,'Paid Type','typepaid','0','PREPAID CARD  =  0 - POSTPAY CARD  =  1.',0,NULL,'signup'),(84,'Credit Limit','creditlimit','0','Define credit limit, which is only used for a POSTPAY card.',0,NULL,'signup'),(85,'Run Service','runservice','0','Authorise the recurring service to apply on this card  -  Yes 1 - No 0.',0,NULL,'signup'),(86,'Enable Expire','enableexpire','0','Enable the expiry of the card  -  Yes 1 - No 0.',0,NULL,'signup'),(87,'Date Format','expirationdate','','Expiry Date format YYYY-MM-DD HH:MM:SS. For instance 2004-12-31 00:00:00',0,NULL,'signup'),(88,'Expire Limit','expiredays','0','The number of days after which the card will expire.',0,NULL,'signup'),(89,'Create SIP','sip_account','1','Create a sip account from signup ( default : yes ).',1,'yes,no','signup'),(90,'Create IAX','iax_account','1','Create an iax account from signup ( default : yes ).',1,'yes,no','signup'),(91,'Activate Card','activatedbyuser','0','active card after the new signup. if No, the Signup confirmation is needed and an email will be sent to the user with a link for activation (need to put the link into the Signup mail template).',1,'yes,no','signup'),(92,'Customer Interface URL','urlcustomerinterface','http://localhost/customer/','url of the customer interface to display after activation.',0,NULL,'signup'),(93,'Asterisk Reload','reload_asterisk_if_sipiax_created','0','Define if you want to reload Asterisk when a SIP / IAX Friend is created at signup time.',1,'yes,no','signup'),(94,'Backup Path','backup_path','/tmp','Path to store backup of database.',0,NULL,'backup'),(95,'GZIP Path','gzip_exe','/bin/gzip','Path for gzip.',0,NULL,'backup'),(96,'GunZip Path','gunzip_exe','/bin/gunzip','Path for gunzip .',0,NULL,'backup'),(97,'MySql Dump Path','mysqldump','/usr/bin/mysqldump','path for mysqldump.',0,NULL,'backup'),(98,'PGSql Dump Path','pg_dump','/usr/bin/pg_dump','path for pg_dump.',0,NULL,'backup'),(99,'MySql Path','mysql','/usr/bin/mysql','Path for MySql.',0,NULL,'backup'),(100,'PSql Path','psql','/usr/bin/psql','Path for PSql.',0,NULL,'backup'),(101,'SIP File Path','buddy_sip_file','/etc/asterisk/additional_a2billing_sip.conf','Path to store the asterisk configuration files SIP.',0,NULL,'webui'),(102,'IAX File Path','buddy_iax_file','/etc/asterisk/additional_a2billing_iax.conf','Path to store the asterisk configuration files IAX.',0,NULL,'webui'),(103,'API Security Key','api_security_key','Ae87v56zzl34v','API have a security key to validate the http request, the key has to be sent after applying md5, Valid characters are [a-z,A-Z,0-9].',0,NULL,'webui'),(104,'Authorized IP','api_ip_auth','127.0.0.1','API to restrict the IPs authorised to make a request, Define The the list of ips separated by \';\'.',0,NULL,'webui'),(105,'Admin Email','email_admin','root@localhost','Administative Email.',0,NULL,'webui'),(106,'MOH Directory','dir_store_mohmp3','/var/lib/asterisk/mohmp3','MOH (Music on Hold) base directory.',0,NULL,'webui'),(107,'MOH Classes','num_musiconhold_class','10','Number of MOH classes you have created in musiconhold.conf : acc_1, acc_2... acc_10 class	etc....',0,NULL,'webui'),(108,'Display Help','show_help','1','Display the help section inside the admin interface  (YES - NO).',1,'yes,no','webui'),(109,'Max File Upload Size','my_max_file_size_import','1024000','File Upload parameters, PLEASE CHECK ALSO THE VALUE IN YOUR PHP.INI THE LIMIT IS 2MG BY DEFAULT .',0,NULL,'webui'),(110,'Audio Directory Path','dir_store_audio','/var/lib/asterisk/sounds/a2billing','Not used yet, The goal is to upload files and use them in the IVR.',0,NULL,'webui'),(111,'Max Audio File Size','my_max_file_size_audio','3072000','upload maximum file size.',0,NULL,'webui'),(112,'Extensions Allowed','file_ext_allow','gsm, mp3, wav','File type extensions permitted to be uploaded such as \"gsm, mp3, wav\" (separated by ,).',0,NULL,'webui'),(113,'Muzic Files Allowed','file_ext_allow_musiconhold','mp3','File type extensions permitted to be uploaded for the musiconhold such as \"gsm, mp3, wav\" (separate by ,).',0,NULL,'webui'),(114,'Link Audio','link_audio_file','0','Enable link on the CDR viewer to the recordings. (YES - NO).',1,'yes,no','webui'),(115,'Monitor Path','monitor_path','/var/spool/asterisk/monitor','Path to link the recorded monitor files.',0,NULL,'webui'),(116,'Monitor Format','monitor_formatfile','gsm','FORMAT OF THE RECORDED MONITOR FILE.',0,NULL,'webui'),(260,'Call to free DID Dial Command Params','dialcommand_param_call_2did','|60|HiL(%timeout%:61000:30000)','%timeout% is the value of the paramater : \'Max time to Call a DID no billed\'',0,NULL,'agi-conf1'),(119,'Currency','currency_choose','usd, eur, cad, hkd','Allow the customer to chose the most appropriate currency (\"all\" can be used).',0,NULL,'webui'),(120,'Card Export Fields','card_export_field_list','id, username, useralias, lastname, credit, tariff, activated, language, inuse, currency, sip_buddy','Fields to export in csv format from cc_card table.',0,NULL,'webui'),(121,'Vouvher Export Fields','voucher_export_field_list','voucher, credit, tag, activated, usedcardnumber, usedate, currency','Field to export in csv format from cc_voucher table.',0,NULL,'webui'),(122,'Advance Mode','advanced_mode','0','Advanced mode - Display additional configuration options on the ratecard (progressive rates, musiconhold, ...).',1,'yes,no','webui'),(123,'SIP/IAX Delete','delete_fk_card','1','Delete the SIP/IAX Friend & callerid when a card is deleted.',1,'yes,no','webui'),(124,'Type','type','friend','Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0,NULL,'peer_friend'),(125,'Allow','allow','ulaw,alaw,gsm,g729','Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0,NULL,'peer_friend'),(126,'Context','context','a2billing','Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0,NULL,'peer_friend'),(127,'Nat','nat','yes','Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0,NULL,'peer_friend'),(128,'AMA Flag','amaflag','billing','Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0,NULL,'peer_friend'),(129,'Qualify','qualify','no','Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0,NULL,'peer_friend'),(130,'Host','host','dynamic','Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0,NULL,'peer_friend'),(131,'DTMF Mode','dtmfmode','RFC2833','Refer to sip.conf & iax.conf documentation for the meaning of those parameters.',0,NULL,'peer_friend'),(132,'Alarm Log File','cront_alarm','/var/log/a2billing/cront_a2b_alarm.log','To disable application logging, remove/comment the log file name aside service.',0,NULL,'log-files'),(133,'Auto refill Log File','cront_autorefill','/var/log/a2billing/cront_a2b_autorefill.log','To disable application logging, remove/comment the log file name aside service.',0,NULL,'log-files'),(134,'Bactch Process Log File','cront_batch_process','/var/log/a2billing/cront_a2b_batch_process.log','To disable application logging, remove/comment the log file name aside service .',0,NULL,'log-files'),(135,'Archive Log File','cront_archive_data','/var/log/a2billing/cront_a2b_archive_data.log','To disable application logging, remove/comment the log file name aside service .',0,NULL,'log-files'),(136,'DID Billing Log File','cront_bill_diduse','/var/log/a2billing/cront_a2b_bill_diduse.log','To disable application logging, remove/comment the log file name aside service .',0,NULL,'log-files'),(137,'Subscription Fee Log File','cront_subscriptionfee','/var/log/a2billing/cront_a2b_subscription_fee.log','To disable application logging, remove/comment the log file name aside service.',0,NULL,'log-files'),(138,'Currency Cront Log File','cront_currency_update','/var/log/a2billing/cront_a2b_currency_update.log','To disable application logging, remove/comment the log file name aside service.',0,NULL,'log-files'),(139,'Invoice Cront Log File','cront_invoice','/var/log/a2billing/cront_a2b_invoice.log','To disable application logging, remove/comment the log file name aside service.',0,NULL,'log-files'),(140,'Cornt Log File','cront_check_account','/var/log/a2billing/cront_a2b_check_account.log','To disable application logging, remove/comment the log file name aside service .',0,NULL,'log-files'),(141,'Paypal Log File','paypal','/var/log/a2billing/a2billing_paypal.log','paypal log file, to log all the transaction & error.',0,NULL,'log-files'),(142,'EPayment Log File','epayment','/var/log/a2billing/a2billing_epayment.log','epayment log file, to log all the transaction & error .',0,NULL,'log-files'),(143,'ECommerce Log File','api_ecommerce','/var/log/a2billing/a2billing_api_ecommerce_request.log','Log file to store the ecommerce API requests .',0,NULL,'log-files'),(144,'Callback Log File','api_callback','/var/log/a2billing/a2billing_api_callback_request.log','Log file to store the CallBack API requests.',0,NULL,'log-files'),(145,'Webservice Card Log File','api_card','/var/log/a2billing/a2billing_api_card.log','Log file to store the Card Webservice Logs',0,NULL,'log-files'),(146,'AGI Log File','agi','/var/log/a2billing/a2billing_agi.log','File to log.',0,NULL,'log-files'),(147,'Description','description','agi-config','Description/notes field',0,NULL,'agi-conf1'),(148,'Asterisk Version','asterisk_version','1_4','Asterisk Version Information, 1_1,1_2,1_4 By Default it will take 1_2 or higher .',0,NULL,'agi-conf1'),(149,'Answer Call','answer_call','1','Manage the answer on the call. Disabling this for callback trigger numbers makes it ring not hang up.',1,'yes,no','agi-conf1'),(150,'Play Audio','play_audio','1','Play audio - this will disable all stream file but not the Get Data , for wholesale ensure that the authentication works and than number_try = 1.',1,'yes,no','agi-conf1'),(151,'Say GoodBye','say_goodbye','0','play the goodbye message when the user has finished.',1,'yes,no','agi-conf1'),(152,'Play Language Menu','play_menulanguage','0','enable the menu to choose the language, press 1 for English, pulsa 2 para el espaÃƒÆ’Ã‚Â±ol, Pressez 3 pour FranÃƒÆ’Ã‚Â§ais',1,'yes,no','agi-conf1'),(153,'Force Language','force_language','','force the use of a language, if you dont want to use it leave the option empty, Values : ES, EN, FR, etc... (according to the audio you have installed).',0,NULL,'agi-conf1'),(154,'Intro Prompt','intro_prompt','','Introduction prompt : to specify an additional prompt to play at the beginning of the application .',0,NULL,'agi-conf1'),(155,'Min Call Credit','min_credit_2call','0','Minimum amount of credit to use the application .',0,NULL,'agi-conf1'),(156,'Min Bill Duration','min_duration_2bill','0','this is the minimum duration in seconds of a call in order to be billed any call with a length less than min_duration_2bill will have a 0 cost useful not to charge callers for system errors when a call was answered but it actually didn\'t connect.',0,NULL,'agi-conf1'),(157,'Not Enough Credit','notenoughcredit_cardnumber','0','if user doesn\'t have enough credit to call a destination, prompt him to enter another cardnumber .',1,'yes,no','agi-conf1'),(158,'New Caller ID','notenoughcredit_assign_newcardnumber_cid','0','if notenoughcredit_cardnumber = YES  then	assign the CallerID to the new cardnumber.',1,'yes,no','agi-conf1'),(159,'Use DNID','use_dnid','0','if YES it will use the DNID and try to dial out, without asking for the phonenumber to call.',1,'yes,no','agi-conf1'),(160,'Not Use DNID','no_auth_dnid','2400,2300','list the dnid on which you want to avoid the use of the previous option \"use_dnid\" .',0,NULL,'agi-conf1'),(161,'Try Count','number_try','3','number of times the user can dial different number.',0,NULL,'agi-conf1'),(162,'Force CallPlan','force_callplan_id','','this will force to select a specific call plan by the Rate Engine.',0,NULL,'agi-conf1'),(163,'Say Balance After Auth','say_balance_after_auth','1','Play the balance to the user after the authentication (values : yes - no).',1,'yes,no','agi-conf1'),(164,'Say Balance After Call','say_balance_after_call','0','Play the balance to the user after the call (values : yes - no).',1,'yes,no','agi-conf1'),(165,'Say Rate','say_rateinitial','0','Play the initial cost of the route (values : yes - no)',1,'yes,no','agi-conf1'),(166,'Say Duration','say_timetocall','1','Play the amount of time that the user can call (values : yes - no).',1,'yes,no','agi-conf1'),(167,'Auto Set CLID','auto_setcallerid','1','enable the setup of the callerID number before the outbound is made, by default the user callerID value will be use.',1,'yes,no','agi-conf1'),(168,'Force CLID','force_callerid','','If auto_setcallerid is enabled, the value of force_callerid will be set as CallerID.',0,NULL,'agi-conf1'),(169,'CLID Sanitize','cid_sanitize','0','If force_callerid is not set, then the following option ensures that CID is set to one of the card\'s configured caller IDs or blank if none available.(NO - disable this feature, caller ID can be anything, CID - Caller ID must be one of the customers caller IDs, DID - Caller ID must be one of the customers DID nos, BOTH - Caller ID must be one of the above two items)',0,NULL,'agi-conf1'),(170,'CLID Enable','cid_enable','0','enable the callerid authentication if this option is active the CC system will check the CID of caller  .',1,'yes,no','agi-conf1'),(171,'Ask PIN','cid_askpincode_ifnot_callerid','1','if the CID does not exist, then the caller will be prompt to enter his cardnumber .',1,'yes,no','agi-conf1'),(172,'FailOver LCR/LCD Prefix','failover_lc_prefix','0','if we will failover for LCR/LCD prefix. For instance if you have 346 and 34 for if 346 fail it will try to outbound with 34 route.',1,'yes,no','agi-conf1'),(173,'Auto CLID','cid_auto_assign_card_to_cid','1','if the callerID authentication is enable and the authentication fails then the user will be prompt to enter his cardnumber;this option will bound the cardnumber entered to the current callerID so that next call will be directly authenticate.',1,'yes,no','agi-conf1'),(180,'Auto CLID Security','callerid_authentication_over_cardnumber','0','to check callerID over the cardnumber authentication (to guard against spoofing).',1,'yes,no','agi-conf1'),(181,'SIP Call','sip_iax_friends','0','enable the option to call sip/iax friend for free (values : YES - NO).',1,'yes,no','agi-conf1'),(182,'SIP Call Prefix','sip_iax_pstn_direct_call_prefix','555','if SIP_IAX_FRIENDS is active, you can define a prefix for the dialed digits to call a pstn number .',0,NULL,'agi-conf1'),(183,'Direct Call','sip_iax_pstn_direct_call','0','this will enable a prompt to enter your destination number. if number start by sip_iax_pstn_direct_call_prefix we do directly a sip iax call, if not we do a normal call.',1,'yes,no','agi-conf1'),(184,'IVR Voucher Refill','ivr_voucher','0','enable the option to refill card with voucher in IVR (values : YES - NO) .',1,'yes,no','agi-conf1'),(185,'IVR Voucher Prefix','ivr_voucher_prefix','8','if ivr_voucher is active, you can define a prefix for the voucher number to refill your card, values : number - don\'t forget to change prepaid-refill_card_with_voucher audio accordingly .',0,NULL,'agi-conf1'),(186,'IVR Low Credit','jump_voucher_if_min_credit','0','When the user credit are below the minimum credit to call min_credit jump directly to the voucher IVR menu  (values: YES - NO) .',1,'yes,no','agi-conf1'),(187,'Dial Command Params','dialcommand_param','|60|HRrL(%timeout%:61000:30000)','More information about the Dial : http://voip-info.org/wiki-Asterisk+cmd+dial<br>30 :  The timeout parameter is optional. If not specifed, the Dial command will wait indefinitely, exiting only when the originating channel hangs up, or all the dialed channels return a busy or error condition. Otherwise it specifies a maximum time, in seconds, that the Dial command is to wait for a channel to answer.<br>H: Allow the caller to hang up by dialing * <br>r: Generate a ringing tone for the calling part',0,NULL,'agi-conf1'),(188,'SIP/IAX Dial Command Params','dialcommand_param_sipiax_friend','|60|HiL(3600000:61000:30000)','by default (3600000  =  1HOUR MAX CALL).',0,NULL,'agi-conf1'),(189,'Outbound Call','switchdialcommand','0','Define the order to make the outbound call<br>YES -> SIP/dialedphonenumber@gateway_ip - NO  SIP/gateway_ip/dialedphonenumber<br>Both should work exactly the same but i experimented one case when gateway was supporting dialedphonenumber@gateway_ip, So in case of trouble, try it out.',1,'yes,no','agi-conf1'),(190,'Failover Retry Limit','failover_recursive_limit','2','failover recursive search - define how many time we want to authorize the research of the failover trunk when a call fails (value : 0 - 20) .',0,NULL,'agi-conf1'),(191,'Max Time','maxtime_tocall_negatif_free_route','5400','This setting specifies an upper limit for the duration of a call to a destination for which the selling rate is less than or equal to 0.',0,NULL,'agi-conf1'),(192,'Send Reminder','send_reminder','0','Send a reminder email to the user when they are under min_credit_2call.',1,'yes,no','agi-conf1'),(193,'Record Call','record_call','0','enable to monitor the call (to record all the conversations) value : YES - NO .',1,'yes,no','agi-conf1'),(194,'Monitor File Format','monitor_formatfile','gsm','format of the recorded monitor file.',0,NULL,'agi-conf1'),(195,'AGI Force Currency','agi_force_currency','','Force to play the balance to the caller in a predefined currency, to use the currency set for by the customer leave this field empty.',0,NULL,'agi-conf1'),(196,'Currency Associated','currency_association','usd:dollars,mxn:pesos,eur:euros,all:credit','Define all the audio (without file extensions) that you want to play according to currency (use , to separate, ie \"usd:prepaid-dollar,mxn:pesos,eur:Euro,all:credit\").',0,NULL,'agi-conf1'),(259,'Option Local Dialing','local_dialing_addcountryprefix','0','Add the countryprefix of the user in front of the dialed number if this one have only 1 leading zero',1,'yes,no','agi-conf1'),(261,'Max time to Call a DID no billed','max_call_call_2_did','3600','max time to call a did of the system and not billed . this max value is in seconde and by default (3600 = 1HOUR MAX CALL).',0,NULL,'agi-conf1'),(198,'File Enter Destination','file_conf_enter_destination','prepaid-enter-dest','Please enter the file name you want to play when we prompt the calling party to enter the destination number, file_conf_enter_destination = prepaid-enter-number-u-calling-1-or-011.',0,NULL,'agi-conf1'),(219,'Menu Language Order','conf_order_menulang','en:fr:es','Enter the list of languages authorized for the menu.Use the code language separate by a colon charactere e.g: en:es:fr',0,NULL,'agi-conf1'),(200,'Bill Callback','callback_bill_1stleg_ifcall_notconnected','1','Define if you want to bill the 1st leg on callback even if the call is not connected to the destination.',1,'yes,no','agi-conf1'),(201,'International prefixes','international_prefixes','011,00,09,1','List the prefixes you want stripped off if the call plan requires it',0,NULL,'agi-conf1'),(202,'Server GMT','server_GMT','GMT+10:00','Define the sever gmt time',0,NULL,'global'),(203,'Invoice Template Path','invoice_template_path','../invoice/','gives invoice template path from default one',0,NULL,'global'),(204,'Outstanding Template Path','outstanding_template_path','../outstanding/','gives outstanding template path from default one',0,NULL,'global'),(205,'Sales Template Path','sales_template_path','../sales/','gives sales template path from default one',0,NULL,'global'),(206,'Extra charge DIDs','extracharge_did','1800,1900','Add extra per-minute charges to this comma-separated list of DNIDs; needs \"extracharge_fee\" and \"extracharge_buyfee\"',0,NULL,'agi-conf1'),(207,'Extra charge DID fees','extracharge_fee','0,0','Comma-separated list of extra sell-rate charges corresponding to the DIDs in \"extracharge_did\" - ie : 0.08,0.18',0,NULL,'agi-conf1'),(208,'Extra charge DID buy fees','extracharge_buyfee','0,0','Comma-separated list of extra buy-rate charges corresponding to the DIDs in \"extracharge_did\" - ie : 0.04,0.13',0,NULL,'agi-conf1'),(253,'Return URL distant Login','return_url_distant_login','','URL for specific return if an error occur after login',0,NULL,'webcustomerui'),(210,'List of possible values to notify','values_notifications','10:20:50:100:500:1000','Possible values to choose when the user receive a notification. You can define a List e.g: 10:20:100.',0,NULL,'notifications'),(211,'Notifications Modules','notification','1','Enable or Disable the module of notification for the customers',1,'yes,no','webcustomerui'),(212,'Notications Cron Module','cron_notifications','1','Enable or Disable the cron module of notification for the customers. If it correctly configured in the crontab',0,'yes,no','notifications'),(213,'Notications Delay','delay_notifications','1','Delay in number of days to send an other notification for the customers. If the value is 0, it will notify the user everytime the cront is running.',0,NULL,'notifications'),(214,'Payment Amount','purchase_amount_agent','100:200:500:1000','define the different amount of purchase that would be available.',0,NULL,'epayment_method'),(215,'Max Time For Unlimited Calls','maxtime_tounlimited_calls','5400','For unlimited calls, limit the duration: amount in seconds .',0,NULL,'agi-conf1'),(216,'Max Time For Free Calls','maxtime_tofree_calls','5400','For free calls, limit the duration: amount in seconds .',0,NULL,'agi-conf1'),(217,'CallPlan threshold Deck switch','callplan_deck_minute_threshold','','CallPlan threshold Deck switch. <br/>This option will switch the user callplan from one call plan ID to and other Callplan ID\nThe parameters are as follow : <br/>\n-- ID of the first callplan : called seconds needed to switch to the next CallplanID <br/>\n-- ID of the second callplan : called seconds needed to switch to the next CallplanID <br/>\n-- if not needed seconds are defined it will automatically switch to the next one <br/>\n-- if defined we will sum the previous needed seconds and check if',0,NULL,'agi-conf1'),(252,'Personal Info','personalinfo','1','Enable or disable the page which allow agent to modify its personal information.',0,'yes,no','webagentui'),(220,'Disable annoucement the second of the times that the card can call','disable_announcement_seconds','0','Desactived the annoucement of the seconds when there are more of one minutes (values : yes - no)',1,'yes,no','agi-conf1'),(221,'Charge for the paypal extra fees','charge_paypal_fee','0','Actived, if you want assum the fee of paypal and don\'t apply it on the customer (values : yes - no)',1,'yes,no','epayment_method'),(258,'Cents Currency Associated','currency_cents_association','usd:prepaid-cents,eur:prepaid-cents,gbp:prepaid-pence,all:credit','Define all the audio (without file extensions) that you want to play according to cents currency (use , to separate, ie \"amd:lumas\").By default the file used is \"prepaid-cents\" .Use plural to define the cents currency sound, but import two sounds but cents currency defined : ending by \'s\' and not ending by \'s\' (i.e. for lumas , add 2 files : \'lumas\' and \'luma\') ',0,NULL,'ivr_creditcard'),(223,'Context Campaign\'s Callback','context_campaign_callback','a2billing-campaign-callback','Context to use in Campaign of Callback',0,NULL,'callback'),(224,'Default Context forward Campaign\'s Callback ','default_context_campaign','campaign','Context to use by default to forward the call in Campaign of Callback',0,NULL,'callback'),(225,'Card Show Fields','card_show_field_list','id:,username:, useralias:, lastname:,id_group:, id_agent:,  credit:, tariff:, status:, language:, in','Fields to show in Customer. Order is important. You can setup size of field using \"fieldname:10%\" notation or \"fieldname:\" for harcoded size,\"fieldname\" for autosize. <br/>You can use:<br/> id,username, useralias, lastname, id_group, id_agent,  credit, tariff, status, language, inuse, currency, sip_buddy, iax_buddy, nbused, firstname, email, discount, callerid, id_seria, serial',0,NULL,'webui'),(226,'Enable CDR local cache','cache_enabled','0','If you want enabled the local cache to save the CDR in a SQLite Database.',1,'yes,no','global'),(227,'Path for the CDR cache file','cache_path','/etc/asterisk/cache_a2billing','Defined the file that you want use for the CDR cache to save the CDR in a local SQLite database.',0,NULL,'global'),(228,'PNL Pay Phones','report_pnl_pay_phones','(8887798764,0.02,0.06)','Info for PNL report. Must be in form \"(number1,buycost,sellcost),(number2,buycost,sellcost)\", number can be prefix, i.e 1800',0,NULL,'webui'),(229,'PNL Toll Free Numbers','report_pnl_toll_free','(6136864646,0.1,0),(6477249717,0.1,0)','Info for PNL report. must be in form \"(number1,buycost,sellcost),(number2,buycost,sellcost)\", number can be prefix, i.e 1800',0,NULL,'webui'),(230,'Verbosity','verbosity_level','0','0 = FATAL; 1 = ERROR; WARN = 2 ; INFO = 3 ; DEBUG = 4',0,NULL,'agi-conf1'),(231,'Logging','logging_level','3','0 = FATAL; 1 = ERROR; WARN = 2 ; INFO = 3 ; DEBUG = 4',0,NULL,'agi-conf1'),(232,'Enable info module about customers','customer_info_enabled','LEFT','If you want enabled the info module customer and place it somewhere on the home page.',0,'NONE,LEFT,CENTER,RIGHT','dashboard'),(233,'Enable info module about refills','refill_info_enabled','CENTER','If you want enabled the info module refills and place it somewhere on the home page.',0,'NONE,LEFT,CENTER,RIGHT','dashboard'),(234,'Enable info module about payments','payment_info_enabled','CENTER','If you want enabled the info module payments and place it somewhere on the home page.',0,'NONE,LEFT,CENTER,RIGHT','dashboard'),(235,'Enable info module about calls','call_info_enabled','RIGHT','If you want enabled the info module calls and place it somewhere on the home page.',0,'NONE,LEFT,CENTER,RIGHT','dashboard'),(236,'PlugnPay Payment URL','plugnpay_payment_url','https://pay1.plugnpay.com/payment/pnpremote.cgi','Define here the URL of PlugnPay gateway.',0,NULL,'epayment_method'),(237,'DIDX ID','didx_id','708XXX','DIDX parameter : ID',0,NULL,'webui'),(238,'DIDX PASS','didx_pass','XXXXXXXXXX','DIDX parameter : Password',0,NULL,'webui'),(239,'DIDX MIN RATING','didx_min_rating','0','DIDX parameter : min rating',0,NULL,'webui'),(240,'DIDX RING TO','didx_ring_to','0','DIDX parameter : ring to',0,NULL,'webui'),(241,'Card Serial Pad Length','card_serial_length','7','Value of zero padding for serial. If this value set to 3 serial wil looks like 001',0,NULL,'webui'),(242,'Dial Balance reservation','dial_balance_reservation','0.25','Credit to reserve from the balance when a call is made. This will prevent negative balance on huge peak.',0,NULL,'agi-conf1'),(243,'Rate Export Fields','rate_export_field_list','destination, dialprefix, rateinitial','Fields to export in csv format from rates table.Use dest_name from prefix name',0,NULL,'webui'),(244,'HTTP Server Agent','http_server_agent','http://www.call-labs.com','Set the Server Address of Agent Website, It should be empty for productive Servers.',0,NULL,'epayment_method'),(245,'HTTPS Server Agent','https_server_agent','https://www.call-labs.com','https://localhost - Enter here your Secure Agents Server Address, should not be empty for productive servers.',0,NULL,'epayment_method'),(246,'Server Agent IP/Domain','http_cookie_domain_agent','26.63.165.200','Enter your Domain Name or IP Address for the Agents application, eg, 26.63.165.200.',0,NULL,'5'),(247,'Secure Server Agent IP/Domain','https_cookie_domain_agent','26.63.165.200','Enter your Secure server Domain Name or IP Address for the Agents application, eg, 26.63.165.200.',0,NULL,'epayment_method'),(248,'Application Agent Path','http_cookie_path_agent','/agent/Public/','Enter the Physical path of your Agents Application on your server.',0,NULL,'epayment_method'),(249,'Secure Application Agent Path','https_cookie_path_agent','/agent/Public/','Enter the Physical path of your Agents Application on your Secure Server.',0,NULL,'epayment_method'),(250,'Application Agent Physical Path','dir_ws_http_catalog_agent','/agent/Public/','Enter the Physical path of your Agents Application on your server.',0,NULL,'epayment_method'),(251,'Secure Application Agent Physical Path','dir_ws_https_catalog_agent','/agent/Public/','Enter the Physical path of your Agents Application on your Secure server.',0,NULL,'epayment_method'),(257,'Option CallerID update','callerid_update','0','Prompt the caller to update his callerID',1,'yes,no','agi-conf1'),(262,'Auto Create Card','cid_auto_create_card','0','if the callerID is captured on a2billing, this option will create automatically a new card and add the callerID to it.',1,'yes,no','agi-conf1'),(263,'Auto Create Card Length','cid_auto_create_card_len','10','set the length of the card that will be auto create (ie, 10).',0,NULL,'agi-conf1'),(264,'Auto Create Card Type','cid_auto_create_card_typepaid','PREPAID','billing type of the new card( value : POSTPAID or PREPAID) .',0,NULL,'agi-conf1'),(265,'Auto Create Card Credit','cid_auto_create_card_credit','0','amount of credit of the new card.',0,NULL,'agi-conf1'),(266,'Auto Create Card Limit','cid_auto_create_card_credit_limit','0','if postpay, define the credit limit for the card.',0,NULL,'agi-conf1'),(267,'Auto Create Card TariffGroup','cid_auto_create_card_tariffgroup','1','the tariffgroup to use for the new card (this is the ID that you can find on the admin web interface) .',0,NULL,'agi-conf1'),(268,'Paypal Amount Subscription','paypal_subscription_amount','10','amount to billed each recurrence of payment ',0,NULL,'epayment_method'),(269,'Paypal Subscription Time period number','paypal_subscription_period_number','1','number of time periods between each recurrence',0,NULL,'epayment_method'),(270,'Paypal Subscription Time period','paypal_subscription_time_period','M','time period (D=days, W=weeks, M=months, Y=years)',0,NULL,'epayment_method'),(271,'Enable PayPal subscription','paypal_subscription_enabled','0','Enable Paypal subscription on the User home page, you need a Premier or Business account.',1,'yes,no','epayment_method'),(272,'Paypal Subscription account','paypal_subscription_account','','Your PayPal ID or an email address associated with your PayPal account. Email addresses must be confirmed and bound to a Premier or Business Verified Account.',0,NULL,'epayment_method'),(273,'Base Country','base_country','USA','Define the country code in 3 letters where you are located (ISO 3166-1 : \"USA\" for United States)',0,'','global'),(274,'Base Language','base_language','en','Define your language code in 2 letters (ISO 639 : \"en\" for English)',0,'','global'),(275,'Authorize Remittance Request','remittance_request','1','Enable or disable the link which allow agent to submit a remittance request',0,'yes,no','webagentui'),(276,'Asterisk Version Global','asterisk_version','1_4','Asterisk Version Information, 1_1, 1_2, 1_4, 1_6. By Default the version is 1_4.',0,NULL,'global'),(277,'Archive Calls','archive_call_prior_x_month','24','A cront can be enabled in order to archive your CDRs, this setting allow to define prior which month it will archive',0,NULL,'backup'),(278,'Days to bill before month anniversary','subscription_bill_days_before_anniversary','3','Numbers of days to bill a subscription service before the month anniversary',0,NULL,'global'),(279,'Enable info module about system','system_info_enable','LEFT','Enabled this if you want to display the info module and place it somewhere on the Dashboard.',0,'NONE,LEFT,CENTER,RIGHT','dashboard'),(280,'Enable news module','news_enabled','RIGHT','Enabled this if you want to display the news module and place it somewhere on the Dashboard.',0,'NONE,LEFT,CENTER,RIGHT','dashboard'),(281,'Busy Timeout','busy_timeout','1','Define the timeout in second when indicate the busy condition',0,NULL,'agi-conf1'),(282,'Callback Reduce Balance','callback_reduce_balance','1','Define the amount to reduce the balance on Callback in order to make sure that the B leg wont alter the account into a negative value.',0,NULL,'agi-conf1'),(283,'Language field','field_language','1','Enable The Language Field -  Yes 1 - No 0.',1,'yes,no','signup'),(284,'Currency field','field_currency','1','Enable The Currency Field - Yes 1 - No 0. ',1,'yes,no','signup'),(285,'Last Name Field','field_lastname','1','Enable The Last Name Field - Yes 1 - No 0. ',1,'yes,no','signup'),(286,'First Name Field','field_firstname','1','Enable The First Name Field - Yes 1 - No 0. ',1,'yes,no','signup'),(287,'Address Field','field_address','1','Enable The Address Field - Yes 1 - No 0. ',1,'yes,no','signup'),(288,'City Field','field_city','1','Enable The City Field - Yes 1 - No 0. ',1,'yes,no','signup'),(289,'State Field','field_state','1','Enable The State Field - Yes 1 - No 0. ',1,'yes,no','signup'),(290,'Country Field','field_country','1','Enable The Country Field - Yes 1 - No 0. ',1,'yes,no','signup'),(291,'Zipcode Field','field_zipcode','1','Enable The Zipcode Field - Yes 1 - No 0. ',1,'yes,no','signup'),(292,'Timezone Field','field_id_timezone','1','Enable The Timezone Field - Yes 1 - No 0. ',1,'yes,no','signup'),(293,'Phone Field','field_phone','1','Enable The Phone Field - Yes 1 - No 0. ',1,'yes,no','signup'),(294,'Fax Field','field_fax','1','Enable The Fax Field - Yes 1 - No 0. ',1,'yes,no','signup'),(295,'Company Name Field','field_company','1','Enable The Company Name Field - Yes 1 - No 0. ',1,'yes,no','signup'),(296,'Company Website Field','field_company_website','1','Enable The Company Website Field - Yes 1 - No 0. ',1,'yes,no','signup'),(297,'VAT Registration Number Field','field_VAT_RN','1','Enable The VAT Registration Number Field - Yes 1 - No 0. ',1,'yes,no','signup'),(298,'Traffic Field','field_traffic','1','Enable The Traffic Field - Yes 1 - No 0. ',1,'yes,no','signup'),(299,'Traffic Target Field','field_traffic_target','1','Enable The Traffic Target Field - Yes 1 - No 0. ',1,'yes,no','signup'),(300,'IVR Locking option','ivr_enable_locking_option','0','Enable the IVR which allow the users to lock their account with an extra lock code.',1,'yes,no','agi-conf1'),(301,'IVR Account Information','ivr_enable_account_information','0','Enable the IVR which allow the users to retrieve different information about their account.',1,'yes,no','agi-conf1'),(302,'IVR Speed Dial','ivr_enable_ivr_speeddial','0','Enable the IVR which allow the users add speed dial.',1,'yes,no','agi-conf1'),(303,'Play rate lower one','play_rate_cents_if_lower_one','0','Play the initial cost even if the cents are less than one. if cost is 0.075, we will play : 7 point 5 cents per minute. (values : yes - no)',0,'yes,no','agi-conf1'),(304,'Callback Beep for Destination ','callback_beep_to_enter_destination','0','Set to yes, this will disable the standard prompt to enter destination and play a beep instead',1,'yes,no','agi-conf1'),(305,'Callback CID Prompt Confirm PhoneNumber ','cid_prompt_callback_confirm_phonenumber','0','Set to yes, a menu will be play to let the user confirm his phone number',1,'yes,no','agi-conf1');
/*!40000 ALTER TABLE `cc_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_config_group`
--

DROP TABLE IF EXISTS `cc_config_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_config_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_title` varchar(64) COLLATE utf8_bin NOT NULL,
  `group_description` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_title` (`group_title`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_config_group`
--

LOCK TABLES `cc_config_group` WRITE;
/*!40000 ALTER TABLE `cc_config_group` DISABLE KEYS */;
INSERT INTO `cc_config_group` VALUES (1,'global','This configuration group handles the global settings for application.'),(2,'callback','This configuration group handles calllback settings.'),(3,'webcustomerui','This configuration group handles Web Customer User Interface.'),(4,'sip-iax-info','SIP & IAX client configuration information.'),(5,'epayment_method','Epayment Methods Configuration.'),(6,'signup','This configuration group handles the signup related settings.'),(7,'backup','This configuration group handles the backup/restore related settings.'),(8,'webui','This configuration group handles the WEBUI and API Configuration.'),(9,'peer_friend','This configuration group define parameters for the friends creation.'),(10,'log-files','This configuration group handles the Log Files Directory Paths.'),(11,'agi-conf1','This configuration group handles the AGI Configuration.'),(12,'notifications','This configuration group handles the notifcations configuration'),(13,'dashboard','This configuration group handles the dashboard configuration'),(14,'webagentui','This configuration group handles Web Agent Interface.');
/*!40000 ALTER TABLE `cc_config_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_configuration`
--

DROP TABLE IF EXISTS `cc_configuration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_configuration` (
  `configuration_id` int(11) NOT NULL AUTO_INCREMENT,
  `configuration_title` varchar(64) COLLATE utf8_bin NOT NULL,
  `configuration_key` varchar(64) COLLATE utf8_bin NOT NULL,
  `configuration_value` varchar(255) COLLATE utf8_bin NOT NULL,
  `configuration_description` varchar(255) COLLATE utf8_bin NOT NULL,
  `configuration_type` int(11) NOT NULL DEFAULT '0',
  `use_function` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `set_function` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`configuration_id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_configuration`
--

LOCK TABLES `cc_configuration` WRITE;
/*!40000 ALTER TABLE `cc_configuration` DISABLE KEYS */;
INSERT INTO `cc_configuration` VALUES (1,'Login Username','MODULE_PAYMENT_AUTHORIZENET_LOGIN','testing','The login username used for the Authorize.net service',0,NULL,NULL),(2,'Transaction Key','MODULE_PAYMENT_AUTHORIZENET_TXNKEY','Test','Transaction Key used for encrypting TP data',0,NULL,NULL),(3,'Transaction Mode','MODULE_PAYMENT_AUTHORIZENET_TESTMODE','Test','Transaction mode used for processing orders',0,NULL,'tep_cfg_select_option(array(\'Test\', \'Production\'), '),(4,'Transaction Method','MODULE_PAYMENT_AUTHORIZENET_METHOD','Credit Card','Transaction method used for processing orders',0,NULL,'tep_cfg_select_option(array(\'Credit Card\', \'eCheck\'), '),(5,'Customer Notifications','MODULE_PAYMENT_AUTHORIZENET_EMAIL_CUSTOMER','False','Should Authorize.Net e-mail a receipt to the customer?',0,NULL,'tep_cfg_select_option(array(\'True\', \'False\'), '),(6,'Enable Authorize.net Module','MODULE_PAYMENT_AUTHORIZENET_STATUS','False','Do you want to accept Authorize.net payments?',0,NULL,'tep_cfg_select_option(array(\'True\', \'False\'), '),(7,'Enable PayPal Module','MODULE_PAYMENT_PAYPAL_STATUS','True','Do you want to accept PayPal payments?',0,NULL,'tep_cfg_select_option(array(\'True\', \'False\'), '),(8,'E-Mail Address','MODULE_PAYMENT_PAYPAL_ID','you@yourbusiness.com','The e-mail address to use for the PayPal service',0,NULL,NULL),(30,'Transaction Currency','MODULE_PAYMENT_IRIDIUM_CURRENCY','Selected Currency','The default currency for the payment transactions',0,NULL,'tep_cfg_select_option(array(\'Selected Currency\',\'EUR\', \'USD\', \'GBP\', \'HKD\', \'SGD\', \'JPY\', \'CAD\', \'AUD\', \'CHF\', \'DKK\', \'SEK\', \'NOK\', \'ILS\', \'MYR\', \'NZD\', \'TWD\', \'THB\', \'CZK\', \'HUF\', \'SKK\', \'ISK\', \'INR\'), '),(10,'E-Mail Address','MODULE_PAYMENT_MONEYBOOKERS_ID','you@yourbusiness.com','The eMail address to use for the moneybookers service',0,NULL,NULL),(11,'Referral ID','MODULE_PAYMENT_MONEYBOOKERS_REFID','989999','Your personal Referral ID from moneybookers.com',0,NULL,NULL),(26,'MerchantID','MODULE_PAYMENT_IRIDIUM_MERCHANTID','yourMerchantId','Your Mechant Id provided by Iridium',0,NULL,NULL),(27,'Password','MODULE_PAYMENT_IRIDIUM_PASSWORD','Password','password for Iridium merchant',0,NULL,NULL),(28,'PaymentProcessor','MODULE_PAYMENT_IRIDIUM_GATEWAY','PaymentGateway URL ','Enter payment gateway URL',0,NULL,NULL),(13,'Transaction Language','MODULE_PAYMENT_MONEYBOOKERS_LANGUAGE','Selected Language','The default language for the payment transactions',0,NULL,'tep_cfg_select_option(array(\'Selected Language\',\'EN\', \'DE\', \'ES\', \'FR\'), '),(14,'Enable moneybookers Module','MODULE_PAYMENT_MONEYBOOKERS_STATUS','True','Do you want to accept moneybookers payments?',0,NULL,'tep_cfg_select_option(array(\'True\', \'False\'), '),(15,'Enable PlugnPay Module','MODULE_PAYMENT_PLUGNPAY_STATUS','True','Do you want to accept payments through PlugnPay?',0,NULL,'tep_cfg_select_option(array(\'True\', \'False\'), '),(16,'Login Username','MODULE_PAYMENT_PLUGNPAY_LOGIN','Your Login Name','Enter your PlugnPay account username',0,NULL,NULL),(17,'Publisher Email','MODULE_PAYMENT_PLUGNPAY_PUBLISHER_EMAIL','Enter Your Email Address','The email address you want PlugnPay conformations sent to',0,NULL,NULL),(18,'cURL Setup','MODULE_PAYMENT_PLUGNPAY_CURL','Not Compiled','Whether cURL is compiled into PHP or not.  Windows users, select not compiled.',0,NULL,'tep_cfg_select_option(array(\'Not Compiled\', \'Compiled\'), '),(19,'cURL Path','MODULE_PAYMENT_PLUGNPAY_CURL_PATH','The Path To cURL','For Not Compiled mode only, input path to the cURL binary (i.e. c:/curl/curl)',0,NULL,NULL),(20,'Transaction Mode','MODULE_PAYMENT_PLUGNPAY_TESTMODE','Test','Transaction mode used for processing orders',0,NULL,'tep_cfg_select_option(array(\'Test\', \'Test And Debug\', \'Production\'), '),(21,'Require CVV','MODULE_PAYMENT_PLUGNPAY_CVV','yes','Ask For CVV information',0,NULL,'tep_cfg_select_option(array(\'yes\', \'no\'), '),(22,'Transaction Method','MODULE_PAYMENT_PLUGNPAY_PAYMETHOD','credit','Transaction method used for processing orders.<br><b>NOTE:</b> Selecting \'onlinecheck\' assumes you\'ll offer \'credit\' as well.',0,NULL,'tep_cfg_select_option(array(\'credit\', \'onlinecheck\'), '),(23,'Authorization Type','MODULE_PAYMENT_PLUGNPAY_CCMODE','authpostauth','Credit card processing mode',0,NULL,'tep_cfg_select_option(array(\'authpostauth\', \'authonly\'), '),(24,'Customer Notifications','MODULE_PAYMENT_PLUGNPAY_DONTSNDMAIL','yes','Should PlugnPay not email a receipt to the customer?',0,NULL,'tep_cfg_select_option(array(\'yes\', \'no\'), '),(25,'Accepted Credit Cards','MODULE_PAYMENT_PLUGNPAY_ACCEPTED_CC','Mastercard, Visa','The credit cards you currently accept',0,NULL,'_selectOptions(array(\'Amex\',\'Discover\', \'Mastercard\', \'Visa\'), '),(29,'PaymentProcessorPort','MODULE_PAYMENT_IRIDIUM_GATEWAY_PORT','PaymentGateway Port ','Enter payment gateway port',0,NULL,NULL),(31,'Transaction Language','MODULE_PAYMENT_IRIDIUM_LANGUAGE','Selected Language','The default language for the payment transactions',0,NULL,'tep_cfg_select_option(array(\'Selected Language\',\'EN\', \'DE\', \'ES\', \'FR\'), '),(32,'Enable iridium Module','MODULE_PAYMENT_IRIDIUM_STATUS','False','Do you want to accept Iridium payments?',0,NULL,'tep_cfg_select_option(array(\'True\', \'False\'), ');
/*!40000 ALTER TABLE `cc_configuration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_country`
--

DROP TABLE IF EXISTS `cc_country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_country` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `countrycode` char(80) COLLATE utf8_bin NOT NULL,
  `countryprefix` char(80) COLLATE utf8_bin NOT NULL,
  `countryname` char(80) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=256 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_country`
--

LOCK TABLES `cc_country` WRITE;
/*!40000 ALTER TABLE `cc_country` DISABLE KEYS */;
INSERT INTO `cc_country` VALUES (1,'AFG','93','Afghanistan'),(2,'ALB','355','Albania'),(3,'DZA','213','Algeria'),(4,'ASM','684','American Samoa'),(5,'AND','376','Andorra'),(6,'AGO','244','Angola'),(7,'AIA','1264','Anguilla'),(8,'ATA','672','Antarctica'),(9,'ATG','1268','Antigua And Barbuda'),(10,'ARG','54','Argentina'),(11,'ARM','374','Armenia'),(12,'ABW','297','Aruba'),(13,'AUS','61','Australia'),(14,'AUT','43','Austria'),(15,'AZE','994','Azerbaijan'),(16,'BHS','1242','Bahamas'),(17,'BHR','973','Bahrain'),(18,'BGD','880','Bangladesh'),(19,'BRB','1246','Barbados'),(20,'BLR','375','Belarus'),(21,'BEL','32','Belgium'),(22,'BLZ','501','Belize'),(23,'BEN','229','Benin'),(24,'BMU','1441','Bermuda'),(25,'BTN','975','Bhutan'),(26,'BOL','591','Bolivia'),(27,'BIH','387','Bosnia And Herzegovina'),(28,'BWA','267','Botswana'),(29,'BVT','0','Bouvet Island'),(30,'BRA','55','Brazil'),(31,'IOT','1284','British Indian Ocean Territory'),(32,'BRN','673','Brunei Darussalam'),(33,'BGR','359','Bulgaria'),(34,'BFA','226','Burkina Faso'),(35,'BDI','257','Burundi'),(36,'KHM','855','Cambodia'),(37,'CMR','237','Cameroon'),(38,'CAN','1','Canada'),(39,'CPV','238','Cape Verde'),(40,'CYM','1345','Cayman Islands'),(41,'CAF','236','Central African Republic'),(42,'TCD','235','Chad'),(43,'CHL','56','Chile'),(44,'CHN','86','China'),(45,'CXR','618','Christmas Island'),(46,'CCK','61','Cocos (Keeling); Islands'),(47,'COL','57','Colombia'),(48,'COM','269','Comoros'),(49,'COG','242','Congo'),(50,'COD','243','Congo, The Democratic Republic Of The'),(51,'COK','682','Cook Islands'),(52,'CRI','506','Costa Rica'),(54,'HRV','385','Croatia'),(55,'CUB','53','Cuba'),(56,'CYP','357','Cyprus'),(57,'CZE','420','Czech Republic'),(58,'DNK','45','Denmark'),(59,'DJI','253','Djibouti'),(60,'DMA','1767','Dominica'),(61,'DOM','1809','Dominican Republic'),(62,'ECU','593','Ecuador'),(63,'EGY','20','Egypt'),(64,'SLV','503','El Salvador'),(65,'GNQ','240','Equatorial Guinea'),(66,'ERI','291','Eritrea'),(67,'EST','372','Estonia'),(68,'ETH','251','Ethiopia'),(69,'FLK','500','Falkland Islands (Malvinas);'),(70,'FRO','298','Faroe Islands'),(71,'FJI','679','Fiji'),(72,'FIN','358','Finland'),(73,'FRA','33','France'),(74,'GUF','596','French Guiana'),(75,'PYF','594','French Polynesia'),(76,'ATF','689','French Southern Territories'),(77,'GAB','241','Gabon'),(78,'GMB','220','Gambia'),(79,'GEO','995','Georgia'),(80,'DEU','49','Germany'),(81,'GHA','233','Ghana'),(82,'GIB','350','Gibraltar'),(83,'GRC','30','Greece'),(84,'GRL','299','Greenland'),(85,'GRD','1473','Grenada'),(86,'GLP','590','Guadeloupe'),(87,'GUM','1671','Guam'),(88,'GTM','502','Guatemala'),(89,'GIN','224','Guinea'),(90,'GNB','245','Guinea-Bissau'),(91,'GUY','592','Guyana'),(92,'HTI','509','Haiti'),(93,'HMD','0','Heard Island And McDonald Islands'),(94,'VAT','0','Holy See (Vatican City State);'),(95,'HND','504','Honduras'),(96,'HKG','852','Hong Kong'),(97,'HUN','36','Hungary'),(98,'ISL','354','Iceland'),(99,'IND','91','India'),(100,'IDN','62','Indonesia'),(101,'IRN','98','Iran, Islamic Republic Of'),(102,'IRQ','964','Iraq'),(103,'IRL','353','Ireland'),(104,'ISR','972','Israel'),(105,'ITA','39','Italy'),(106,'JAM','1876','Jamaica'),(107,'JPN','81','Japan'),(108,'JOR','962','Jordan'),(109,'KAZ','7','Kazakhstan'),(110,'KEN','254','Kenya'),(111,'KIR','686','Kiribati'),(112,'PRK','850','Korea, Democratic People\'s Republic Of'),(113,'KOR','82','Korea, Republic of'),(114,'KWT','965','Kuwait'),(115,'KGZ','996','Kyrgyzstan'),(116,'LAO','856','Lao People\'s Democratic Republic'),(117,'LVA','371','Latvia'),(118,'LBN','961','Lebanon'),(119,'LSO','266','Lesotho'),(120,'LBR','231','Liberia'),(121,'LBY','218','Libyan Arab Jamahiriya'),(122,'LIE','423','Liechtenstein'),(123,'LTU','370','Lithuania'),(124,'LUX','352','Luxembourg'),(125,'MAC','853','Macao'),(126,'MKD','389','Macedonia, The Former Yugoslav Republic Of'),(127,'MDG','261','Madagascar'),(128,'MWI','265','Malawi'),(129,'MYS','60','Malaysia'),(130,'MDV','960','Maldives'),(131,'MLI','223','Mali'),(132,'MLT','356','Malta'),(133,'MHL','692','Marshall islands'),(134,'MTQ','596','Martinique'),(135,'MRT','222','Mauritania'),(136,'MUS','230','Mauritius'),(137,'MYT','269','Mayotte'),(138,'MEX','52','Mexico'),(139,'FSM','691','Micronesia, Federated States Of'),(140,'MDA','1808','Moldova, Republic Of'),(141,'MCO','377','Monaco'),(142,'MNG','976','Mongolia'),(143,'MSR','1664','Montserrat'),(144,'MAR','212','Morocco'),(145,'MOZ','258','Mozambique'),(146,'MMR','95','Myanmar'),(147,'NAM','264','Namibia'),(148,'NRU','674','Nauru'),(149,'NPL','977','Nepal'),(150,'NLD','31','Netherlands'),(151,'ANT','599','Netherlands Antilles'),(152,'NCL','687','New Caledonia'),(153,'NZL','64','New Zealand'),(154,'NIC','505','Nicaragua'),(155,'NER','227','Niger'),(156,'NGA','234','Nigeria'),(157,'NIU','683','Niue'),(158,'NFK','672','Norfolk Island'),(159,'MNP','1670','Northern Mariana Islands'),(160,'NOR','47','Norway'),(161,'OMN','968','Oman'),(162,'PAK','92','Pakistan'),(163,'PLW','680','Palau'),(164,'PSE','970','Palestinian Territory, Occupied'),(165,'PAN','507','Panama'),(166,'PNG','675','Papua New Guinea'),(167,'PRY','595','Paraguay'),(168,'PER','51','Peru'),(169,'PHL','63','Philippines'),(170,'PCN','0','Pitcairn'),(171,'POL','48','Poland'),(172,'PRT','351','Portugal'),(173,'PRI','1787','Puerto Rico'),(174,'QAT','974','Qatar'),(175,'REU','262','Reunion'),(176,'ROU','40','Romania'),(177,'RUS','7','Russian Federation'),(178,'RWA','250','Rwanda'),(179,'SHN','290','SaINT Helena'),(180,'KNA','1869','SaINT Kitts And Nevis'),(181,'LCA','1758','SaINT Lucia'),(182,'SPM','508','SaINT Pierre And Miquelon'),(183,'VCT','1784','SaINT Vincent And The Grenadines'),(184,'WSM','685','Samoa'),(185,'SMR','378','San Marino'),(186,'STP','239','SÃƒÆ’Ã‚Â£o TomÃƒÆ’Ã‚Â© And Principe'),(187,'SAU','966','Saudi Arabia'),(188,'SEN','221','Senegal'),(189,'SYC','248','Seychelles'),(190,'SLE','232','Sierra Leone'),(191,'SGP','65','Singapore'),(192,'SVK','421','Slovakia'),(193,'SVN','386','Slovenia'),(194,'SLB','677','Solomon Islands'),(195,'SOM','252','Somalia'),(196,'ZAF','27','South Africa'),(197,'SGS','0','South Georgia And The South Sandwich Islands'),(198,'ESP','34','Spain'),(199,'LKA','94','Sri Lanka'),(200,'SDN','249','Sudan'),(201,'SUR','597','Suriname'),(202,'SJM','0','Svalbard and Jan Mayen'),(203,'SWZ','268','Swaziland'),(204,'SWE','46','Sweden'),(205,'CHE','41','Switzerland'),(206,'SYR','963','Syrian Arab Republic'),(207,'TWN','886','Taiwan, Province Of China'),(208,'TJK','992','Tajikistan'),(209,'TZA','255','Tanzania, United Republic Of'),(210,'THA','66','Thailand'),(211,'TLS','670','Timor-Leste'),(212,'TGO','228','Togo'),(213,'TKL','690','Tokelau'),(214,'TON','676','Tonga'),(215,'TTO','1868','Trinidad And Tobago'),(216,'TUN','216','Tunisia'),(217,'TUR','90','Turkey'),(218,'TKM','993','Turkmenistan'),(219,'TCA','1649','Turks And Caicos Islands'),(220,'TUV','688','Tuvalu'),(221,'UGA','256','Uganda'),(222,'UKR','380','Ukraine'),(223,'ARE','971','United Arab Emirates'),(224,'GBR','44','United Kingdom'),(225,'USA','1','United States'),(226,'UMI','0','United States Minor Outlying Islands'),(227,'URY','598','Uruguay'),(228,'UZB','998','Uzbekistan'),(229,'VUT','678','Vanuatu'),(230,'VEN','58','Venezuela'),(231,'VNM','84','Vietnam'),(232,'VGB','1284','Virgin Islands, British'),(233,'VIR','808','Virgin Islands, U.S.'),(234,'WLF','681','Wallis And Futuna'),(235,'ESH','0','Western Sahara'),(236,'YEM','967','Yemen'),(237,'YUG','0','Yugoslavia'),(238,'ZMB','260','Zambia'),(239,'ZWE','263','Zimbabwe'),(240,'ASC','0','Ascension Island'),(241,'DGA','0','Diego Garcia'),(242,'XNM','0','Inmarsat'),(243,'TMP','0','East timor'),(244,'AK','0','Alaska'),(245,'HI','0','Hawaii'),(53,'CIV','225','CÃƒÆ’Ã‚Â´te d\'Ivoire'),(246,'ALA','35818','Aland Islands'),(247,'BLM','0','Saint Barthelemy'),(248,'GGY','441481','Guernsey'),(249,'IMN','441624','Isle of Man'),(250,'JEY','441534','Jersey'),(251,'MAF','0','Saint Martin'),(252,'MNE','382','Montenegro, Republic of'),(253,'SRB','381','Serbia, Republic of'),(254,'CPT','0','Clipperton Island'),(255,'TAA','0','Tristan da Cunha');
/*!40000 ALTER TABLE `cc_country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_currencies`
--

DROP TABLE IF EXISTS `cc_currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_currencies` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `currency` char(3) COLLATE utf8_bin NOT NULL DEFAULT '',
  `name` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '',
  `value` decimal(12,5) unsigned NOT NULL DEFAULT '0.00000',
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `basecurrency` char(3) COLLATE utf8_bin NOT NULL DEFAULT 'USD',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cons_cc_currencies_currency` (`currency`)
) ENGINE=MyISAM AUTO_INCREMENT=151 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_currencies`
--

LOCK TABLES `cc_currencies` WRITE;
/*!40000 ALTER TABLE `cc_currencies` DISABLE KEYS */;
INSERT INTO `cc_currencies` VALUES (1,'ALL','Albanian Lek (ALL)','0.00974','2009-05-15 07:38:43','USD'),(2,'DZD','Algerian Dinar (DZD)','0.01345','2009-05-15 07:38:43','USD'),(3,'XAL','Aluminium Ounces (XAL)','1.08295','2009-05-15 07:38:43','USD'),(4,'ARS','Argentine Peso (ARS)','0.32455','2009-05-15 07:38:43','USD'),(5,'AWG','Aruba Florin (AWG)','0.55866','2009-05-15 07:38:43','USD'),(6,'AUD','Australian Dollar (AUD)','0.73384','2009-05-15 07:38:43','USD'),(7,'BSD','Bahamian Dollar (BSD)','1.00000','2009-05-15 07:38:43','USD'),(8,'BHD','Bahraini Dinar (BHD)','2.65322','2009-05-15 07:38:43','USD'),(9,'BDT','Bangladesh Taka (BDT)','0.01467','2009-05-15 07:38:43','USD'),(10,'BBD','Barbados Dollar (BBD)','0.50000','2009-05-15 07:38:43','USD'),(11,'BYR','Belarus Ruble (BYR)','0.00046','2009-05-15 07:38:43','USD'),(12,'BZD','Belize Dollar (BZD)','0.50569','2009-05-15 07:38:43','USD'),(13,'BMD','Bermuda Dollar (BMD)','1.00000','2009-05-15 07:38:43','USD'),(14,'BTN','Bhutan Ngultrum (BTN)','0.02186','2009-05-15 07:38:43','USD'),(15,'BOB','Bolivian Boliviano (BOB)','0.12500','2009-05-15 07:38:43','USD'),(16,'BRL','Brazilian Real (BRL)','0.46030','2009-05-15 07:38:43','USD'),(17,'GBP','British Pound (GBP)','1.73702','2009-05-15 07:38:43','USD'),(18,'BND','Brunei Dollar (BND)','0.61290','2009-05-15 07:38:43','USD'),(19,'BGN','Bulgarian Lev (BGN)','0.60927','2009-05-15 07:38:43','USD'),(20,'BIF','Burundi Franc (BIF)','0.00103','2009-05-15 07:38:43','USD'),(21,'KHR','Cambodia Riel (KHR)','0.00000','2009-05-15 07:38:43','USD'),(22,'CAD','Canadian Dollar (CAD)','0.86386','2009-05-15 07:38:43','USD'),(23,'KYD','Cayman Islands Dollar (KYD)','1.16496','2009-05-15 07:38:43','USD'),(24,'XOF','CFA Franc (BCEAO) (XOF)','0.00182','2009-05-15 07:38:43','USD'),(25,'XAF','CFA Franc (BEAC) (XAF)','0.00182','2009-05-15 07:38:43','USD'),(26,'CLP','Chilean Peso (CLP)','0.00187','2009-05-15 07:38:43','USD'),(27,'CNY','Chinese Yuan (CNY)','0.12425','2009-05-15 07:38:43','USD'),(28,'COP','Colombian Peso (COP)','0.00044','2009-05-15 07:38:43','USD'),(29,'KMF','Comoros Franc (KMF)','0.00242','2009-05-15 07:38:43','USD'),(30,'XCP','Copper Ounces (XCP)','2.16403','2009-05-15 07:38:43','USD'),(31,'CRC','Costa Rica Colon (CRC)','0.00199','2009-05-15 07:38:43','USD'),(32,'HRK','Croatian Kuna (HRK)','0.16249','2009-05-15 07:38:43','USD'),(33,'CUP','Cuban Peso (CUP)','1.00000','2009-05-15 07:38:43','USD'),(34,'CYP','Cyprus Pound (CYP)','2.07426','2009-05-15 07:38:43','USD'),(35,'CZK','Czech Koruna (CZK)','0.04133','2009-05-15 07:38:43','USD'),(36,'DKK','Danish Krone (DKK)','0.15982','2009-05-15 07:38:43','USD'),(37,'DJF','Dijibouti Franc (DJF)','0.00000','2009-05-15 07:38:43','USD'),(38,'DOP','Dominican Peso (DOP)','0.03035','2009-05-15 07:38:43','USD'),(39,'XCD','East Caribbean Dollar (XCD)','0.37037','2009-05-15 07:38:43','USD'),(40,'ECS','Ecuador Sucre (ECS)','0.00004','2009-05-15 07:38:43','USD'),(41,'EGP','Egyptian Pound (EGP)','0.17433','2009-05-15 07:38:43','USD'),(42,'SVC','El Salvador Colon (SVC)','0.11426','2009-05-15 07:38:43','USD'),(43,'ERN','Eritrea Nakfa (ERN)','0.00000','2009-05-15 07:38:43','USD'),(44,'EEK','Estonian Kroon (EEK)','0.07615','2009-05-15 07:38:43','USD'),(45,'ETB','Ethiopian Birr (ETB)','0.11456','2009-05-15 07:38:43','USD'),(46,'EUR','Euro (EUR)','1.19175','2009-05-15 07:38:43','USD'),(47,'FKP','Falkland Islands Pound (FKP)','0.00000','2009-05-15 07:38:43','USD'),(48,'GMD','Gambian Dalasi (GMD)','0.03515','2009-05-15 07:38:43','USD'),(49,'GHC','Ghanian Cedi (GHC)','0.00011','2009-05-15 07:38:43','USD'),(50,'GIP','Gibraltar Pound (GIP)','0.00000','2009-05-15 07:38:43','USD'),(51,'XAU','Gold Ounces (XAU)','99.99999','2009-05-15 07:38:43','USD'),(52,'GTQ','Guatemala Quetzal (GTQ)','0.13103','2009-05-15 07:38:43','USD'),(53,'GNF','Guinea Franc (GNF)','0.00022','2009-05-15 07:38:43','USD'),(54,'HTG','Haiti Gourde (HTG)','0.02387','2009-05-15 07:38:43','USD'),(55,'HNL','Honduras Lempira (HNL)','0.05292','2009-05-15 07:38:43','USD'),(56,'HKD','Hong Kong Dollar (HKD)','0.12884','2009-05-15 07:38:43','USD'),(57,'HUF','Hungarian ForINT (HUF)','0.00461','2009-05-15 07:38:43','USD'),(58,'ISK','Iceland Krona (ISK)','0.01436','2009-05-15 07:38:43','USD'),(59,'INR','Indian Rupee (INR)','0.02253','2009-05-15 07:38:43','USD'),(60,'IDR','Indonesian Rupiah (IDR)','0.00011','2009-05-15 07:38:43','USD'),(61,'IRR','Iran Rial (IRR)','0.00011','2009-05-15 07:38:43','USD'),(62,'ILS','Israeli Shekel (ILS)','0.21192','2009-05-15 07:38:43','USD'),(63,'JMD','Jamaican Dollar (JMD)','0.01536','2009-05-15 07:38:43','USD'),(64,'JPY','Japanese Yen (JPY)','0.00849','2009-05-15 07:38:43','USD'),(65,'JOD','Jordanian Dinar (JOD)','1.41044','2009-05-15 07:38:43','USD'),(66,'KZT','Kazakhstan Tenge (KZT)','0.00773','2009-05-15 07:38:43','USD'),(67,'KES','Kenyan Shilling (KES)','0.01392','2009-05-15 07:38:43','USD'),(68,'KRW','Korean Won (KRW)','0.00102','2009-05-15 07:38:43','USD'),(69,'KWD','Kuwaiti Dinar (KWD)','3.42349','2009-05-15 07:38:43','USD'),(70,'LAK','Lao Kip (LAK)','0.00000','2009-05-15 07:38:43','USD'),(71,'LVL','Latvian Lat (LVL)','1.71233','2009-05-15 07:38:43','USD'),(72,'LBP','Lebanese Pound (LBP)','0.00067','2009-05-15 07:38:43','USD'),(73,'LSL','Lesotho Loti (LSL)','0.15817','2009-05-15 07:38:43','USD'),(74,'LYD','Libyan Dinar (LYD)','0.00000','2009-05-15 07:38:43','USD'),(75,'LTL','Lithuanian Lita (LTL)','0.34510','2009-05-15 07:38:43','USD'),(76,'MOP','Macau Pataca (MOP)','0.12509','2009-05-15 07:38:43','USD'),(77,'MKD','Macedonian Denar (MKD)','0.01945','2009-05-15 07:38:43','USD'),(78,'MGF','Malagasy Franc (MGF)','0.00011','2009-05-15 07:38:43','USD'),(79,'MWK','Malawi Kwacha (MWK)','0.00752','2009-05-15 07:38:43','USD'),(80,'MYR','Malaysian Ringgit (MYR)','0.26889','2009-05-15 07:38:43','USD'),(81,'MVR','Maldives Rufiyaa (MVR)','0.07813','2009-05-15 07:38:43','USD'),(82,'MTL','Maltese Lira (MTL)','2.77546','2009-05-15 07:38:43','USD'),(83,'MRO','Mauritania Ougulya (MRO)','0.00369','2009-05-15 07:38:43','USD'),(84,'MUR','Mauritius Rupee (MUR)','0.03258','2009-05-15 07:38:43','USD'),(85,'MXN','Mexican Peso (MXN)','0.09320','2009-05-15 07:38:43','USD'),(86,'MDL','Moldovan Leu (MDL)','0.07678','2009-05-15 07:38:43','USD'),(87,'MNT','Mongolian Tugrik (MNT)','0.00084','2009-05-15 07:38:43','USD'),(88,'MAD','Moroccan Dirham (MAD)','0.10897','2009-05-15 07:38:43','USD'),(89,'MZM','Mozambique Metical (MZM)','0.00004','2009-05-15 07:38:43','USD'),(90,'NAD','Namibian Dollar (NAD)','0.15817','2009-05-15 07:38:43','USD'),(91,'NPR','Nepalese Rupee (NPR)','0.01408','2009-05-15 07:38:43','USD'),(92,'ANG','Neth Antilles Guilder (ANG)','0.55866','2009-05-15 07:38:43','USD'),(93,'TRY','New Turkish Lira (TRY)','0.73621','2009-05-15 07:38:43','USD'),(94,'NZD','New Zealand Dollar (NZD)','0.65096','2009-05-15 07:38:43','USD'),(95,'NIO','Nicaragua Cordoba (NIO)','0.05828','2009-05-15 07:38:43','USD'),(96,'NGN','Nigerian Naira (NGN)','0.00777','2009-05-15 07:38:43','USD'),(97,'NOK','Norwegian Krone (NOK)','0.14867','2009-05-15 07:38:43','USD'),(98,'OMR','Omani Rial (OMR)','2.59740','2009-05-15 07:38:43','USD'),(99,'XPF','Pacific Franc (XPF)','0.00999','2009-05-15 07:38:43','USD'),(100,'PKR','Pakistani Rupee (PKR)','0.01667','2009-05-15 07:38:43','USD'),(101,'XPD','Palladium Ounces (XPD)','99.99999','2009-05-15 07:38:43','USD'),(102,'PAB','Panama Balboa (PAB)','1.00000','2009-05-15 07:38:43','USD'),(103,'PGK','Papua New Guinea Kina (PGK)','0.33125','2009-05-15 07:38:43','USD'),(104,'PYG','Paraguayan Guarani (PYG)','0.00017','2009-05-15 07:38:43','USD'),(105,'PEN','Peruvian Nuevo Sol (PEN)','0.29999','2009-05-15 07:38:43','USD'),(106,'PHP','Philippine Peso (PHP)','0.01945','2009-05-15 07:38:43','USD'),(107,'XPT','Platinum Ounces (XPT)','99.99999','2009-05-15 07:38:43','USD'),(108,'PLN','Polish Zloty (PLN)','0.30574','2009-05-15 07:38:43','USD'),(109,'QAR','Qatar Rial (QAR)','0.27476','2009-05-15 07:38:43','USD'),(110,'ROL','Romanian Leu (ROL)','0.00000','2009-05-15 07:38:43','USD'),(111,'RON','Romanian New Leu (RON)','0.34074','2009-05-15 07:38:43','USD'),(112,'RUB','Russian Rouble (RUB)','0.03563','2009-05-15 07:38:43','USD'),(113,'RWF','Rwanda Franc (RWF)','0.00185','2009-05-15 07:38:43','USD'),(114,'WST','Samoa Tala (WST)','0.35492','2009-05-15 07:38:43','USD'),(115,'STD','Sao Tome Dobra (STD)','0.00000','2009-05-15 07:38:43','USD'),(116,'SAR','Saudi Arabian Riyal (SAR)','0.26665','2009-05-15 07:38:43','USD'),(117,'SCR','Seychelles Rupee (SCR)','0.18114','2009-05-15 07:38:43','USD'),(118,'SLL','Sierra Leone Leone (SLL)','0.00034','2009-05-15 07:38:43','USD'),(119,'XAG','Silver Ounces (XAG)','9.77517','2009-05-15 07:38:43','USD'),(120,'SGD','Singapore Dollar (SGD)','0.61290','2009-05-15 07:38:43','USD'),(121,'SKK','Slovak Koruna (SKK)','0.03157','2009-05-15 07:38:43','USD'),(122,'SIT','Slovenian Tolar (SIT)','0.00498','2009-05-15 07:38:43','USD'),(123,'SOS','Somali Shilling (SOS)','0.00000','2009-05-15 07:38:43','USD'),(124,'ZAR','South African Rand (ZAR)','0.15835','2009-05-15 07:38:43','USD'),(125,'LKR','Sri Lanka Rupee (LKR)','0.00974','2009-05-15 07:38:43','USD'),(126,'SHP','St Helena Pound (SHP)','0.00000','2009-05-15 07:38:43','USD'),(127,'SDD','Sudanese Dinar (SDD)','0.00427','2009-05-15 07:38:43','USD'),(128,'SRG','Surinam Guilder (SRG)','0.36496','2009-05-15 07:38:43','USD'),(129,'SZL','Swaziland Lilageni (SZL)','0.15817','2009-05-15 07:38:43','USD'),(130,'SEK','Swedish Krona (SEK)','0.12609','2009-05-15 07:38:43','USD'),(131,'CHF','Swiss Franc (CHF)','0.76435','2009-05-15 07:38:43','USD'),(132,'SYP','Syrian Pound (SYP)','0.00000','2009-05-15 07:38:43','USD'),(133,'TWD','Taiwan Dollar (TWD)','0.03075','2009-05-15 07:38:43','USD'),(134,'TZS','Tanzanian Shilling (TZS)','0.00083','2009-05-15 07:38:43','USD'),(135,'THB','Thai Baht (THB)','0.02546','2009-05-15 07:38:43','USD'),(136,'TOP','Tonga Paanga (TOP)','0.48244','2009-05-15 07:38:43','USD'),(137,'TTD','Trinidad&Tobago Dollar (TTD)','0.15863','2009-05-15 07:38:43','USD'),(138,'TND','Tunisian Dinar (TND)','0.73470','2009-05-15 07:38:43','USD'),(139,'USD','U.S. Dollar (USD)','1.00000','2009-05-15 07:38:43','USD'),(140,'AED','UAE Dirham (AED)','0.27228','2009-05-15 07:38:43','USD'),(141,'UGX','Ugandan Shilling (UGX)','0.00055','2009-05-15 07:38:43','USD'),(142,'UAH','Ukraine Hryvnia (UAH)','0.19755','2009-05-15 07:38:43','USD'),(143,'UYU','Uruguayan New Peso (UYU)','0.04119','2009-05-15 07:38:43','USD'),(144,'VUV','Vanuatu Vatu (VUV)','0.00870','2009-05-15 07:38:43','USD'),(145,'VEB','Venezuelan Bolivar (VEB)','0.00037','2009-05-15 07:38:43','USD'),(146,'VND','Vietnam Dong (VND)','0.00006','2009-05-15 07:38:43','USD'),(147,'YER','Yemen Riyal (YER)','0.00510','2009-05-15 07:38:43','USD'),(148,'ZMK','Zambian Kwacha (ZMK)','0.00031','2009-05-15 07:38:43','USD'),(149,'ZWD','Zimbabwe Dollar (ZWD)','0.00001','2009-05-15 07:38:43','USD'),(150,'GYD','Guyana Dollar (GYD)','0.00527','2009-05-15 07:38:43','USD');
/*!40000 ALTER TABLE `cc_currencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_did`
--

DROP TABLE IF EXISTS `cc_did`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_did` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_cc_didgroup` bigint(20) NOT NULL,
  `id_cc_country` int(11) NOT NULL,
  `activated` int(11) NOT NULL DEFAULT '1',
  `reserved` int(11) DEFAULT '0',
  `iduser` bigint(20) NOT NULL DEFAULT '0',
  `did` char(50) COLLATE utf8_bin NOT NULL,
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `startingdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expirationdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `description` mediumtext COLLATE utf8_bin,
  `secondusedreal` int(11) DEFAULT '0',
  `billingtype` int(11) DEFAULT '0',
  `fixrate` float NOT NULL DEFAULT '0',
  `connection_charge` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `selling_rate` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `aleg_carrier_connect_charge` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `aleg_carrier_cost_min` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `aleg_retail_connect_charge` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `aleg_retail_cost_min` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `aleg_carrier_initblock` int(11) NOT NULL DEFAULT '0',
  `aleg_carrier_increment` int(11) NOT NULL DEFAULT '0',
  `aleg_retail_initblock` int(11) NOT NULL DEFAULT '0',
  `aleg_retail_increment` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cons_cc_did_did` (`did`),
  UNIQUE KEY `did` (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_did`
--

LOCK TABLES `cc_did` WRITE;
/*!40000 ALTER TABLE `cc_did` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_did` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_did_destination`
--

DROP TABLE IF EXISTS `cc_did_destination`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_did_destination` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `destination` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT '0',
  `id_cc_card` bigint(20) NOT NULL,
  `id_cc_did` bigint(20) NOT NULL,
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `activated` int(11) NOT NULL DEFAULT '1',
  `secondusedreal` int(11) DEFAULT '0',
  `voip_call` int(11) DEFAULT '0',
  `validated` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_did_destination`
--

LOCK TABLES `cc_did_destination` WRITE;
/*!40000 ALTER TABLE `cc_did_destination` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_did_destination` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_did_use`
--

DROP TABLE IF EXISTS `cc_did_use`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_did_use` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_cc_card` bigint(20) DEFAULT NULL,
  `id_did` bigint(20) NOT NULL,
  `reservationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `releasedate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `activated` int(11) DEFAULT '0',
  `month_payed` int(11) DEFAULT '0',
  `reminded` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_did_use`
--

LOCK TABLES `cc_did_use` WRITE;
/*!40000 ALTER TABLE `cc_did_use` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_did_use` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_didgroup`
--

DROP TABLE IF EXISTS `cc_didgroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_didgroup` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `didgroupname` char(50) COLLATE utf8_bin NOT NULL,
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_didgroup`
--

LOCK TABLES `cc_didgroup` WRITE;
/*!40000 ALTER TABLE `cc_didgroup` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_didgroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_epayment_log`
--

DROP TABLE IF EXISTS `cc_epayment_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_epayment_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cardid` bigint(20) NOT NULL DEFAULT '0',
  `amount` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `vat` float NOT NULL DEFAULT '0',
  `paymentmethod` char(50) COLLATE utf8_bin NOT NULL,
  `cc_owner` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  `cc_number` varchar(32) COLLATE utf8_bin DEFAULT NULL,
  `cc_expires` varchar(7) COLLATE utf8_bin DEFAULT NULL,
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '0',
  `cvv` varchar(4) COLLATE utf8_bin DEFAULT NULL,
  `credit_card_type` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `currency` varchar(4) COLLATE utf8_bin DEFAULT NULL,
  `transaction_detail` longtext COLLATE utf8_bin,
  `item_type` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `item_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_epayment_log`
--

LOCK TABLES `cc_epayment_log` WRITE;
/*!40000 ALTER TABLE `cc_epayment_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_epayment_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_epayment_log_agent`
--

DROP TABLE IF EXISTS `cc_epayment_log_agent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_epayment_log_agent` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `agent_id` bigint(20) NOT NULL DEFAULT '0',
  `amount` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `vat` float NOT NULL DEFAULT '0',
  `paymentmethod` char(50) COLLATE utf8_bin NOT NULL,
  `cc_owner` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  `cc_number` varchar(32) COLLATE utf8_bin DEFAULT NULL,
  `cc_expires` varchar(7) COLLATE utf8_bin DEFAULT NULL,
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '0',
  `cvv` varchar(4) COLLATE utf8_bin DEFAULT NULL,
  `credit_card_type` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `currency` varchar(4) COLLATE utf8_bin DEFAULT NULL,
  `transaction_detail` longtext COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_epayment_log_agent`
--

LOCK TABLES `cc_epayment_log_agent` WRITE;
/*!40000 ALTER TABLE `cc_epayment_log_agent` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_epayment_log_agent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_iax_buddies`
--

DROP TABLE IF EXISTS `cc_iax_buddies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_iax_buddies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cc_card` int(11) NOT NULL DEFAULT '0',
  `name` varchar(80) COLLATE utf8_bin NOT NULL,
  `accountcode` varchar(20) COLLATE utf8_bin NOT NULL,
  `regexten` varchar(20) COLLATE utf8_bin NOT NULL,
  `amaflags` char(7) COLLATE utf8_bin DEFAULT NULL,
  `callerid` varchar(80) COLLATE utf8_bin NOT NULL,
  `context` varchar(80) COLLATE utf8_bin NOT NULL,
  `DEFAULTip` char(15) COLLATE utf8_bin DEFAULT NULL,
  `host` varchar(31) COLLATE utf8_bin NOT NULL,
  `language` char(2) COLLATE utf8_bin DEFAULT NULL,
  `deny` varchar(95) COLLATE utf8_bin NOT NULL,
  `permit` varchar(95) COLLATE utf8_bin DEFAULT NULL,
  `mask` varchar(95) COLLATE utf8_bin NOT NULL,
  `port` char(5) COLLATE utf8_bin NOT NULL DEFAULT '',
  `qualify` char(7) COLLATE utf8_bin DEFAULT 'yes',
  `secret` varchar(80) COLLATE utf8_bin NOT NULL,
  `type` char(6) COLLATE utf8_bin NOT NULL DEFAULT 'friend',
  `username` varchar(80) COLLATE utf8_bin NOT NULL,
  `disallow` varchar(100) COLLATE utf8_bin NOT NULL,
  `allow` varchar(100) COLLATE utf8_bin NOT NULL,
  `regseconds` int(11) NOT NULL DEFAULT '0',
  `ipaddr` char(15) COLLATE utf8_bin NOT NULL DEFAULT '',
  `trunk` char(3) COLLATE utf8_bin DEFAULT 'no',
  `dbsecret` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `regcontext` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `sourceaddress` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '',
  `mohinterpret` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '',
  `mohsuggest` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '',
  `inkeys` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `outkey` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `cid_number` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `sendani` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fullname` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `auth` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '',
  `maxauthreq` varchar(15) COLLATE utf8_bin NOT NULL DEFAULT '',
  `encryption` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '',
  `transfer` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `jitterbuffer` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `forcejitterbuffer` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `codecpriority` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `qualifysmoothing` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `qualifyfreqok` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `qualifyfreqnotok` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `timezone` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '',
  `adsi` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `setvar` varchar(200) COLLATE utf8_bin NOT NULL DEFAULT '',
  `requirecalltoken` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '',
  `maxcallnumbers` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `maxcallnumbers_nonvalidated` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cons_cc_iax_buddies_name` (`name`),
  KEY `name` (`name`),
  KEY `host` (`host`),
  KEY `ipaddr` (`ipaddr`),
  KEY `port` (`port`),
  KEY `iax_friend_nh_index` (`name`,`host`),
  KEY `iax_friend_nip_index` (`name`,`ipaddr`,`port`),
  KEY `iax_friend_ip_index` (`ipaddr`,`port`),
  KEY `iax_friend_hp_index` (`host`,`port`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_iax_buddies`
--

LOCK TABLES `cc_iax_buddies` WRITE;
/*!40000 ALTER TABLE `cc_iax_buddies` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_iax_buddies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_invoice`
--

DROP TABLE IF EXISTS `cc_invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_invoice` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `reference` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `id_card` bigint(20) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `paid_status` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `title` varchar(50) COLLATE utf8_bin NOT NULL,
  `description` mediumtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reference` (`reference`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_invoice`
--

LOCK TABLES `cc_invoice` WRITE;
/*!40000 ALTER TABLE `cc_invoice` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_invoice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_invoice_conf`
--

DROP TABLE IF EXISTS `cc_invoice_conf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_invoice_conf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key_val` varchar(50) COLLATE utf8_bin NOT NULL,
  `value` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_val` (`key_val`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_invoice_conf`
--

LOCK TABLES `cc_invoice_conf` WRITE;
/*!40000 ALTER TABLE `cc_invoice_conf` DISABLE KEYS */;
INSERT INTO `cc_invoice_conf` VALUES (1,'company_name','My company'),(2,'address','address'),(3,'zipcode','xxxx'),(4,'country','country'),(5,'city','city'),(6,'phone','xxxxxxxxxxx'),(7,'fax','xxxxxxxxxxx'),(8,'email','xxxxxxx@xxxxxxx.xxx'),(9,'vat','xxxxxxxxxx'),(10,'web','www.xxxxxxx.xxx'),(11,'display_account','0');
/*!40000 ALTER TABLE `cc_invoice_conf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_invoice_item`
--

DROP TABLE IF EXISTS `cc_invoice_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_invoice_item` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_invoice` bigint(20) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `price` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `VAT` decimal(4,2) NOT NULL DEFAULT '0.00',
  `description` mediumtext COLLATE utf8_bin NOT NULL,
  `id_ext` bigint(20) DEFAULT NULL,
  `type_ext` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_invoice_item`
--

LOCK TABLES `cc_invoice_item` WRITE;
/*!40000 ALTER TABLE `cc_invoice_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_invoice_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_invoice_payment`
--

DROP TABLE IF EXISTS `cc_invoice_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_invoice_payment` (
  `id_invoice` bigint(20) NOT NULL,
  `id_payment` bigint(20) NOT NULL,
  PRIMARY KEY (`id_invoice`,`id_payment`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_invoice_payment`
--

LOCK TABLES `cc_invoice_payment` WRITE;
/*!40000 ALTER TABLE `cc_invoice_payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_invoice_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_iso639`
--

DROP TABLE IF EXISTS `cc_iso639`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_iso639` (
  `code` char(2) COLLATE utf8_bin NOT NULL,
  `name` char(16) COLLATE utf8_bin NOT NULL,
  `lname` char(16) COLLATE utf8_bin DEFAULT NULL,
  `charset` char(16) COLLATE utf8_bin NOT NULL DEFAULT 'ISO-8859-1',
  PRIMARY KEY (`code`),
  UNIQUE KEY `iso639_name_key` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_iso639`
--

LOCK TABLES `cc_iso639` WRITE;
/*!40000 ALTER TABLE `cc_iso639` DISABLE KEYS */;
INSERT INTO `cc_iso639` VALUES ('ab','Abkhazian','','ISO-8859-1'),('om','Afan (Oromo)','','ISO-8859-1'),('aa','Afar','','ISO-8859-1'),('af','Afrikaans','','ISO-8859-1'),('sq','Albanian','','ISO-8859-1'),('am','Amharic','','ISO-8859-1'),('ar','Arabic','','ISO-8859-1'),('hy','Armenian','','ISO-8859-1'),('as','Assamese','','ISO-8859-1'),('ay','Aymara','','ISO-8859-1'),('az','Azerbaijani','','ISO-8859-1'),('ba','Bashkir','','ISO-8859-1'),('eu','Basque','Euskera','ISO-8859-15'),('bn','Bengali Bangla','','ISO-8859-1'),('dz','Bhutani','','ISO-8859-1'),('bh','Bihari','','ISO-8859-1'),('bi','Bislama','','ISO-8859-1'),('br','Breton','','ISO-8859-1'),('bg','Bulgarian','','ISO-8859-1'),('my','Burmese','','ISO-8859-1'),('be','Byelorussian','','ISO-8859-1'),('km','Cambodian','','ISO-8859-1'),('ca','Catalan','          		','ISO-8859-15'),('zh','Chinese','','ISO-8859-1'),('co','Corsican','','ISO-8859-1'),('hr','Croatian','','ISO-8859-1'),('cs','Czech','','ISO-8859-1'),('da','Danish','','ISO-8859-1'),('nl','Dutch','','ISO-8859-1'),('en','English','','ISO-8859-1'),('eo','Esperanto','','ISO-8859-1'),('et','Estonian','','ISO-8859-1'),('fo','Faroese','','ISO-8859-1'),('fj','Fiji','','ISO-8859-1'),('fi','Finnish','','ISO-8859-1'),('fr','French','','ISO-8859-1'),('fy','Frisian','','ISO-8859-1'),('gl','Galician','','ISO-8859-1'),('ka','Georgian','','ISO-8859-1'),('de','German','','ISO-8859-1'),('el','Greek','','ISO-8859-1'),('kl','Greenlandic','','ISO-8859-1'),('gn','Guarani','','ISO-8859-1'),('gu','Gujarati','','ISO-8859-1'),('ha','Hausa','','ISO-8859-1'),('he','Hebrew','','ISO-8859-1'),('hi','Hindi','','ISO-8859-1'),('hu','Hungarian','','ISO-8859-1'),('is','Icelandic','','ISO-8859-1'),('id','Indonesian','','ISO-8859-1'),('ia','Interlingua','','ISO-8859-1'),('ie','Interlingue','','ISO-8859-1'),('iu','Inuktitut','','ISO-8859-1'),('ik','Inupiak','','ISO-8859-1'),('ga','Irish','','ISO-8859-1'),('it','Italian','','ISO-8859-1'),('ja','Japanese','','ISO-8859-1'),('jv','Javanese','','ISO-8859-1'),('kn','Kannada','','ISO-8859-1'),('ks','Kashmiri','','ISO-8859-1'),('kk','Kazakh','','ISO-8859-1'),('rw','Kinyarwanda','','ISO-8859-1'),('ky','Kirghiz','','ISO-8859-1'),('rn','Kurundi','','ISO-8859-1'),('ko','Korean','','ISO-8859-1'),('ku','Kurdish','','ISO-8859-1'),('lo','Laothian','','ISO-8859-1'),('la','Latin','','ISO-8859-1'),('lv','Latvian Lettish','','ISO-8859-1'),('ln','Lingala','','ISO-8859-1'),('lt','Lithuanian','','ISO-8859-1'),('mk','Macedonian','','ISO-8859-1'),('mg','Malagasy','','ISO-8859-1'),('ms','Malay','','ISO-8859-1'),('ml','Malayalam','','ISO-8859-1'),('mt','Maltese','','ISO-8859-1'),('mi','Maori','','ISO-8859-1'),('mr','Marathi','','ISO-8859-1'),('mo','Moldavian','','ISO-8859-1'),('mn','Mongolian','','ISO-8859-1'),('na','Nauru','','ISO-8859-1'),('ne','Nepali','','ISO-8859-1'),('no','Norwegian','','ISO-8859-1'),('oc','Occitan','','ISO-8859-1'),('or','Oriya','','ISO-8859-1'),('ps','Pashto Pushto','','ISO-8859-1'),('fa','Persian (Farsi)','','ISO-8859-1'),('pl','Polish','','ISO-8859-1'),('pt','Portuguese','','ISO-8859-1'),('pa','Punjabi','','ISO-8859-1'),('qu','Quechua','','ISO-8859-1'),('rm','Rhaeto-Romance','','ISO-8859-1'),('ro','Romanian','','ISO-8859-1'),('ru','Russian','','ISO-8859-1'),('sm','Samoan','','ISO-8859-1'),('sg','Sangho','','ISO-8859-1'),('sa','Sanskrit','','ISO-8859-1'),('gd','Scots Gaelic','','ISO-8859-1'),('sr','Serbian','','ISO-8859-1'),('sh','Serbo-Croatian','','ISO-8859-1'),('st','Sesotho','','ISO-8859-1'),('tn','Setswana','','ISO-8859-1'),('sn','Shona','','ISO-8859-1'),('sd','Sindhi','','ISO-8859-1'),('si','Singhalese','','ISO-8859-1'),('ss','Siswati','','ISO-8859-1'),('sk','Slovak','','ISO-8859-1'),('sl','Slovenian','','ISO-8859-1'),('so','Somali','','ISO-8859-1'),('es','Spanish','         		','ISO-8859-15'),('su','Sundanese','','ISO-8859-1'),('sw','Swahili','','ISO-8859-1'),('sv','Swedish','','ISO-8859-1'),('tl','Tagalog','','ISO-8859-1'),('tg','Tajik','','ISO-8859-1'),('ta','Tamil','','ISO-8859-1'),('tt','Tatar','','ISO-8859-1'),('te','Telugu','','ISO-8859-1'),('th','Thai','','ISO-8859-1'),('bo','Tibetan','','ISO-8859-1'),('ti','Tigrinya','','ISO-8859-1'),('to','Tonga','','ISO-8859-1'),('ts','Tsonga','','ISO-8859-1'),('tr','Turkish','','ISO-8859-1'),('tk','Turkmen','','ISO-8859-1'),('tw','Twi','','ISO-8859-1'),('ug','Uigur','','ISO-8859-1'),('uk','Ukrainian','','ISO-8859-1'),('ur','Urdu','','ISO-8859-1'),('uz','Uzbek','','ISO-8859-1'),('vi','Vietnamese','','ISO-8859-1'),('vo','Volapuk','','ISO-8859-1'),('cy','Welsh','','ISO-8859-1'),('wo','Wolof','','ISO-8859-1'),('xh','Xhosa','','ISO-8859-1'),('yi','Yiddish','','ISO-8859-1'),('yo','Yoruba','','ISO-8859-1'),('za','Zhuang','','ISO-8859-1'),('zu','Zulu','','ISO-8859-1');
/*!40000 ALTER TABLE `cc_iso639` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_logpayment`
--

DROP TABLE IF EXISTS `cc_logpayment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_logpayment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment` decimal(15,5) NOT NULL,
  `card_id` bigint(20) NOT NULL,
  `id_logrefill` bigint(20) DEFAULT NULL,
  `description` mediumtext COLLATE utf8_bin,
  `added_refill` smallint(6) NOT NULL DEFAULT '0',
  `payment_type` tinyint(4) NOT NULL DEFAULT '0',
  `added_commission` tinyint(4) NOT NULL DEFAULT '0',
  `agent_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_logpayment`
--

LOCK TABLES `cc_logpayment` WRITE;
/*!40000 ALTER TABLE `cc_logpayment` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_logpayment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_logpayment_agent`
--

DROP TABLE IF EXISTS `cc_logpayment_agent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_logpayment_agent` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment` decimal(15,5) NOT NULL,
  `agent_id` bigint(20) NOT NULL,
  `id_logrefill` bigint(20) DEFAULT NULL,
  `description` mediumtext COLLATE utf8_bin,
  `added_refill` tinyint(4) NOT NULL DEFAULT '0',
  `payment_type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_logpayment_agent`
--

LOCK TABLES `cc_logpayment_agent` WRITE;
/*!40000 ALTER TABLE `cc_logpayment_agent` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_logpayment_agent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_logrefill`
--

DROP TABLE IF EXISTS `cc_logrefill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_logrefill` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `credit` decimal(15,5) NOT NULL,
  `card_id` bigint(20) NOT NULL,
  `description` mediumtext COLLATE utf8_bin,
  `refill_type` tinyint(4) NOT NULL DEFAULT '0',
  `added_invoice` tinyint(4) NOT NULL DEFAULT '0',
  `agent_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_logrefill`
--

LOCK TABLES `cc_logrefill` WRITE;
/*!40000 ALTER TABLE `cc_logrefill` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_logrefill` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_logrefill_agent`
--

DROP TABLE IF EXISTS `cc_logrefill_agent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_logrefill_agent` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `credit` decimal(15,5) NOT NULL,
  `agent_id` bigint(20) NOT NULL,
  `description` mediumtext COLLATE utf8_bin,
  `refill_type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_logrefill_agent`
--

LOCK TABLES `cc_logrefill_agent` WRITE;
/*!40000 ALTER TABLE `cc_logrefill_agent` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_logrefill_agent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_message_agent`
--

DROP TABLE IF EXISTS `cc_message_agent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_message_agent` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_agent` int(11) NOT NULL,
  `message` longtext COLLATE utf8_bin,
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `logo` tinyint(4) NOT NULL DEFAULT '1',
  `order_display` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_message_agent`
--

LOCK TABLES `cc_message_agent` WRITE;
/*!40000 ALTER TABLE `cc_message_agent` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_message_agent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_monitor`
--

DROP TABLE IF EXISTS `cc_monitor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_monitor` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `label` varchar(50) COLLATE utf8_bin NOT NULL,
  `dial_code` int(11) DEFAULT NULL,
  `description` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `text_intro` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `query_type` tinyint(4) NOT NULL DEFAULT '1',
  `query` varchar(1000) COLLATE utf8_bin DEFAULT NULL,
  `result_type` tinyint(4) NOT NULL DEFAULT '1',
  `enable` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_monitor`
--

LOCK TABLES `cc_monitor` WRITE;
/*!40000 ALTER TABLE `cc_monitor` DISABLE KEYS */;
INSERT INTO `cc_monitor` VALUES (1,'TotalCall',2,'To say the total amount of calls','The total amount of calls on your system is',1,'select count(*) from cc_call;',3,1),(2,'Say Time',1,'just saying the current date and time','The current date and time is',1,'SELECT UNIX_TIMESTAMP( );',2,1),(3,'Test Connectivity',3,'Test Connectivity with Google','your Internet connection is',2,'check_connectivity.sh',1,1);
/*!40000 ALTER TABLE `cc_monitor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_notification`
--

DROP TABLE IF EXISTS `cc_notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_notification` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `key_value` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `priority` tinyint(4) NOT NULL DEFAULT '0',
  `from_type` tinyint(4) NOT NULL,
  `from_id` bigint(20) DEFAULT '0',
  `link_id` bigint(20) DEFAULT NULL,
  `link_type` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_notification`
--

LOCK TABLES `cc_notification` WRITE;
/*!40000 ALTER TABLE `cc_notification` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_notification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_notification_admin`
--

DROP TABLE IF EXISTS `cc_notification_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_notification_admin` (
  `id_notification` bigint(20) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `viewed` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_notification`,`id_admin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_notification_admin`
--

LOCK TABLES `cc_notification_admin` WRITE;
/*!40000 ALTER TABLE `cc_notification_admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_notification_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_outbound_cid_group`
--

DROP TABLE IF EXISTS `cc_outbound_cid_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_outbound_cid_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `group_name` varchar(70) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_outbound_cid_group`
--

LOCK TABLES `cc_outbound_cid_group` WRITE;
/*!40000 ALTER TABLE `cc_outbound_cid_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_outbound_cid_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_outbound_cid_list`
--

DROP TABLE IF EXISTS `cc_outbound_cid_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_outbound_cid_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `outbound_cid_group` int(11) NOT NULL,
  `cid` char(100) COLLATE utf8_bin DEFAULT NULL,
  `activated` int(11) NOT NULL DEFAULT '0',
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_outbound_cid_list`
--

LOCK TABLES `cc_outbound_cid_list` WRITE;
/*!40000 ALTER TABLE `cc_outbound_cid_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_outbound_cid_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_package_group`
--

DROP TABLE IF EXISTS `cc_package_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_package_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(30) COLLATE utf8_bin NOT NULL,
  `description` mediumtext COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_package_group`
--

LOCK TABLES `cc_package_group` WRITE;
/*!40000 ALTER TABLE `cc_package_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_package_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_package_offer`
--

DROP TABLE IF EXISTS `cc_package_offer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_package_offer` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `label` varchar(70) COLLATE utf8_bin NOT NULL,
  `packagetype` int(11) NOT NULL,
  `billingtype` int(11) NOT NULL,
  `startday` int(11) NOT NULL,
  `freetimetocall` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_package_offer`
--

LOCK TABLES `cc_package_offer` WRITE;
/*!40000 ALTER TABLE `cc_package_offer` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_package_offer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_package_rate`
--

DROP TABLE IF EXISTS `cc_package_rate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_package_rate` (
  `package_id` int(11) NOT NULL,
  `rate_id` int(11) NOT NULL,
  PRIMARY KEY (`package_id`,`rate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_package_rate`
--

LOCK TABLES `cc_package_rate` WRITE;
/*!40000 ALTER TABLE `cc_package_rate` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_package_rate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_packgroup_package`
--

DROP TABLE IF EXISTS `cc_packgroup_package`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_packgroup_package` (
  `packagegroup_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  PRIMARY KEY (`packagegroup_id`,`package_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_packgroup_package`
--

LOCK TABLES `cc_packgroup_package` WRITE;
/*!40000 ALTER TABLE `cc_packgroup_package` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_packgroup_package` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_payment_methods`
--

DROP TABLE IF EXISTS `cc_payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_payment_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_method` char(100) COLLATE utf8_bin NOT NULL,
  `payment_filename` char(200) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_payment_methods`
--

LOCK TABLES `cc_payment_methods` WRITE;
/*!40000 ALTER TABLE `cc_payment_methods` DISABLE KEYS */;
INSERT INTO `cc_payment_methods` VALUES (1,'paypal','paypal.php'),(3,'MoneyBookers','moneybookers.php'),(4,'plugnpay','plugnpay.php'),(5,'iridium','iridium.php');
/*!40000 ALTER TABLE `cc_payment_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_payments`
--

DROP TABLE IF EXISTS `cc_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_payments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `customers_id` bigint(20) NOT NULL DEFAULT '0',
  `customers_name` varchar(200) COLLATE utf8_bin NOT NULL,
  `customers_email_address` varchar(96) COLLATE utf8_bin NOT NULL,
  `item_name` varchar(127) COLLATE utf8_bin DEFAULT NULL,
  `item_id` varchar(127) COLLATE utf8_bin DEFAULT NULL,
  `item_quantity` int(11) NOT NULL DEFAULT '0',
  `payment_method` varchar(32) COLLATE utf8_bin NOT NULL,
  `cc_type` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `cc_owner` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  `cc_number` varchar(32) COLLATE utf8_bin DEFAULT NULL,
  `cc_expires` varchar(4) COLLATE utf8_bin DEFAULT NULL,
  `orders_status` int(5) NOT NULL,
  `orders_amount` decimal(14,6) DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_purchased` datetime DEFAULT NULL,
  `orders_date_finished` datetime DEFAULT NULL,
  `currency` char(3) COLLATE utf8_bin DEFAULT NULL,
  `currency_value` decimal(14,6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_payments`
--

LOCK TABLES `cc_payments` WRITE;
/*!40000 ALTER TABLE `cc_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_payments_agent`
--

DROP TABLE IF EXISTS `cc_payments_agent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_payments_agent` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `agent_id` bigint(20) NOT NULL,
  `agent_name` varchar(200) COLLATE utf8_bin NOT NULL,
  `agent_email_address` varchar(96) COLLATE utf8_bin NOT NULL,
  `item_name` varchar(127) COLLATE utf8_bin DEFAULT NULL,
  `item_id` varchar(127) COLLATE utf8_bin DEFAULT NULL,
  `item_quantity` int(11) NOT NULL DEFAULT '0',
  `payment_method` varchar(32) COLLATE utf8_bin NOT NULL,
  `cc_type` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `cc_owner` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  `cc_number` varchar(32) COLLATE utf8_bin DEFAULT NULL,
  `cc_expires` varchar(4) COLLATE utf8_bin DEFAULT NULL,
  `orders_status` int(5) NOT NULL,
  `orders_amount` decimal(14,6) DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_purchased` datetime DEFAULT NULL,
  `orders_date_finished` datetime DEFAULT NULL,
  `currency` char(3) COLLATE utf8_bin DEFAULT NULL,
  `currency_value` decimal(14,6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_payments_agent`
--

LOCK TABLES `cc_payments_agent` WRITE;
/*!40000 ALTER TABLE `cc_payments_agent` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_payments_agent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_payments_status`
--

DROP TABLE IF EXISTS `cc_payments_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_payments_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status_id` int(11) NOT NULL,
  `status_name` varchar(200) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_payments_status`
--

LOCK TABLES `cc_payments_status` WRITE;
/*!40000 ALTER TABLE `cc_payments_status` DISABLE KEYS */;
INSERT INTO `cc_payments_status` VALUES (1,-2,'Failed'),(2,-1,'Denied'),(3,0,'Pending'),(4,1,'In-Progress'),(5,2,'Completed'),(6,3,'Processed'),(7,4,'Refunded'),(8,5,'Unknown');
/*!40000 ALTER TABLE `cc_payments_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_paypal`
--

DROP TABLE IF EXISTS `cc_paypal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_paypal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payer_id` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `payment_date` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `txn_id` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `first_name` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `last_name` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `payer_email` varchar(55) COLLATE utf8_bin DEFAULT NULL,
  `payer_status` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `payment_type` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `memo` tinytext COLLATE utf8_bin,
  `item_name` varchar(70) COLLATE utf8_bin DEFAULT NULL,
  `item_number` varchar(70) COLLATE utf8_bin DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `mc_gross` decimal(9,2) DEFAULT NULL,
  `mc_fee` decimal(9,2) DEFAULT NULL,
  `tax` decimal(9,2) DEFAULT NULL,
  `mc_currency` char(3) COLLATE utf8_bin DEFAULT NULL,
  `address_name` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '',
  `address_street` varchar(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `address_city` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `address_state` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `address_zip` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '',
  `address_country` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '',
  `address_status` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '',
  `payer_business_name` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `payment_status` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '',
  `pending_reason` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '',
  `reason_code` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '',
  `txn_type` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `txn_id` (`txn_id`),
  KEY `txn_id_2` (`txn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_paypal`
--

LOCK TABLES `cc_paypal` WRITE;
/*!40000 ALTER TABLE `cc_paypal` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_paypal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_phonebook`
--

DROP TABLE IF EXISTS `cc_phonebook`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_phonebook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(30) COLLATE utf8_bin NOT NULL,
  `description` mediumtext COLLATE utf8_bin,
  `id_card` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_phonebook`
--

LOCK TABLES `cc_phonebook` WRITE;
/*!40000 ALTER TABLE `cc_phonebook` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_phonebook` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_phonenumber`
--

DROP TABLE IF EXISTS `cc_phonenumber`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_phonenumber` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_phonebook` int(11) NOT NULL,
  `number` char(30) COLLATE utf8_bin NOT NULL,
  `name` char(40) COLLATE utf8_bin DEFAULT NULL,
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` smallint(6) NOT NULL DEFAULT '1',
  `info` mediumtext COLLATE utf8_bin,
  `amount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_phonenumber`
--

LOCK TABLES `cc_phonenumber` WRITE;
/*!40000 ALTER TABLE `cc_phonenumber` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_phonenumber` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_prefix`
--

DROP TABLE IF EXISTS `cc_prefix`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_prefix` (
  `prefix` bigint(20) NOT NULL AUTO_INCREMENT,
  `destination` varchar(60) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`prefix`),
  KEY `destination` (`destination`)
) ENGINE=MyISAM AUTO_INCREMENT=998795791 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_prefix`
--

LOCK TABLES `cc_prefix` WRITE;
/*!40000 ALTER TABLE `cc_prefix` DISABLE KEYS */;
INSERT INTO `cc_prefix` VALUES (93,'Afghanistan'),(9370,'Afghanistan Mobile'),(9375,'Afghanistan Mobile'),(9377,'Afghanistan Mobile'),(9378,'Afghanistan Mobile'),(9379,'Afghanistan Mobile'),(355,'Albania'),(35567,'Albania Mobile'),(35568,'Albania Mobile'),(35569,'Albania Mobile'),(213,'Algeria'),(2135,'Algeria Mobile'),(2136,'Algeria Mobile'),(2137,'Algeria Mobile'),(2139,'Algeria Mobile'),(1684,'American Samoa'),(684,'American Samoa'),(1684733,'American Samoa Mobile'),(376,'Andorra'),(3763,'Andorra Mobile'),(3764,'Andorra Mobile'),(3766,'Andorra Mobile'),(244,'Angola'),(24491,'Angola Mobile'),(24492,'Angola Mobile'),(1264,'Anguilla'),(1264235,'Anguilla Mobile'),(1264469,'Anguilla Mobile'),(1264476,'Anguilla Mobile'),(1264536,'Anguilla Mobile'),(1264537,'Anguilla Mobile'),(1264538,'Anguilla Mobile'),(1264539,'Anguilla Mobile'),(1264581,'Anguilla Mobile'),(1264582,'Anguilla Mobile'),(1264583,'Anguilla Mobile'),(1264584,'Anguilla Mobile'),(1264724,'Anguilla Mobile'),(1264729,'Anguilla Mobile'),(1264772,'Anguilla Mobile'),(67210,'Antarctica'),(67211,'Antarctica'),(67212,'Antarctica'),(67213,'Antarctica'),(1268,'Antigua and Barbuda'),(1268406,'Antigua and Barbuda Mobile'),(1268409,'Antigua and Barbuda Mobile'),(1268464,'Antigua and Barbuda Mobile'),(1268720,'Antigua and Barbuda Mobile'),(1268721,'Antigua and Barbuda Mobile'),(1268722,'Antigua and Barbuda Mobile'),(1268723,'Antigua and Barbuda Mobile'),(1268724,'Antigua and Barbuda Mobile'),(1268725,'Antigua and Barbuda Mobile'),(1268726,'Antigua and Barbuda Mobile'),(1268727,'Antigua and Barbuda Mobile'),(1268728,'Antigua and Barbuda Mobile'),(1268729,'Antigua and Barbuda Mobile'),(1268764,'Antigua and Barbuda Mobile'),(1268770,'Antigua and Barbuda Mobile'),(1268771,'Antigua and Barbuda Mobile'),(1268772,'Antigua and Barbuda Mobile'),(1268773,'Antigua and Barbuda Mobile'),(1268774,'Antigua and Barbuda Mobile'),(1268775,'Antigua and Barbuda Mobile'),(1268779,'Antigua and Barbuda Mobile'),(1268780,'Antigua and Barbuda Mobile'),(1268781,'Antigua and Barbuda Mobile'),(1268782,'Antigua and Barbuda Mobile'),(1268783,'Antigua and Barbuda Mobile'),(1268784,'Antigua and Barbuda Mobile'),(1268785,'Antigua and Barbuda Mobile'),(1268786,'Antigua and Barbuda Mobile'),(1268788,'Antigua and Barbuda Mobile'),(54,'Argentina'),(549,'Argentina Mobile'),(374,'Armenia'),(37477,'Armenia Mobile'),(3749,'Armenia Mobile'),(297,'Aruba'),(29756,'Aruba Mobile'),(29759,'Aruba Mobile'),(29773,'Aruba Mobile'),(29774,'Aruba Mobile'),(29796,'Aruba Mobile'),(29799,'Aruba Mobile'),(247,'Ascension Islands'),(61,'Australia'),(61145,'Australia Mobile'),(61147,'Australia Mobile'),(6116,'Australia Mobile'),(614,'Australia Mobile'),(43,'Austria'),(43644,'Austria Mobile'),(43650,'Austria Mobile'),(43660,'Austria Mobile'),(43664,'Austria Mobile'),(43676,'Austria Mobile'),(43677,'Austria Mobile'),(43678,'Austria Mobile'),(43680,'Austria Mobile'),(43681,'Austria Mobile'),(43688,'Austria Mobile'),(43699,'Austria Mobile'),(994,'Azerbaijan'),(99440,'Azerbaijan Mobile'),(99450,'Azerbaijan Mobile'),(99451,'Azerbaijan Mobile'),(99455,'Azerbaijan Mobile'),(99470,'Azerbaijan Mobile'),(1242,'Bahamas'),(1242357,'Bahamas Mobile'),(1242359,'Bahamas Mobile'),(1242375,'Bahamas Mobile'),(1242395,'Bahamas Mobile'),(1242422,'Bahamas Mobile'),(1242423,'Bahamas Mobile'),(1242424,'Bahamas Mobile'),(1242425,'Bahamas Mobile'),(1242426,'Bahamas Mobile'),(1242427,'Bahamas Mobile'),(1242434,'Bahamas Mobile'),(1242436,'Bahamas Mobile'),(1242441,'Bahamas Mobile'),(1242442,'Bahamas Mobile'),(1242454,'Bahamas Mobile'),(1242455,'Bahamas Mobile'),(1242456,'Bahamas Mobile'),(1242457,'Bahamas Mobile'),(1242464,'Bahamas Mobile'),(1242465,'Bahamas Mobile'),(1242466,'Bahamas Mobile'),(1242467,'Bahamas Mobile'),(1242468,'Bahamas Mobile'),(1242475,'Bahamas Mobile'),(1242477,'Bahamas Mobile'),(1242524,'Bahamas Mobile'),(1242525,'Bahamas Mobile'),(1242533,'Bahamas Mobile'),(1242535,'Bahamas Mobile'),(1242544,'Bahamas Mobile'),(1242551,'Bahamas Mobile'),(1242552,'Bahamas Mobile'),(1242553,'Bahamas Mobile'),(1242554,'Bahamas Mobile'),(1242556,'Bahamas Mobile'),(1242557,'Bahamas Mobile'),(1242558,'Bahamas Mobile'),(1242559,'Bahamas Mobile'),(1242565,'Bahamas Mobile'),(1242577,'Bahamas Mobile'),(1242636,'Bahamas Mobile'),(1242646,'Bahamas Mobile'),(1242727,'Bahamas Mobile'),(973,'Bahrain'),(9733,'Bahrain Mobile'),(880,'Bangladesh'),(8801,'Bangladesh Mobile'),(1246,'Barbados'),(124623,'Barbados Mobile'),(124624,'Barbados Mobile'),(124625,'Barbados Mobile'),(124626,'Barbados Mobile'),(1246446,'Barbados Mobile'),(1246447,'Barbados Mobile'),(1246448,'Barbados Mobile'),(1246449,'Barbados Mobile'),(124645,'Barbados Mobile'),(124652,'Barbados Mobile'),(1246820,'Barbados Mobile'),(1246821,'Barbados Mobile'),(1246822,'Barbados Mobile'),(1246823,'Barbados Mobile'),(1246824,'Barbados Mobile'),(1246825,'Barbados Mobile'),(1246826,'Barbados Mobile'),(1246827,'Barbados Mobile'),(1246828,'Barbados Mobile'),(1246829,'Barbados Mobile'),(375,'Belarus'),(375259,'Belarus Mobile'),(37529,'Belarus Mobile'),(37533,'Belarus Mobile'),(37544,'Belarus Mobile'),(32,'Belgium'),(32484,'Belgium [Base]'),(32485,'Belgium [Base]'),(32486,'Belgium [Base]'),(32487,'Belgium [Base]'),(32488,'Belgium [Base]'),(32494,'Belgium [Mobistar]'),(32495,'Belgium [Mobistar]'),(32496,'Belgium [Mobistar]'),(32497,'Belgium [Mobistar]'),(32498,'Belgium [Mobistar]'),(32499,'Belgium [Mobistar]'),(32472,'Belgium [Proximus]'),(32473,'Belgium [Proximus]'),(32474,'Belgium [Proximus]'),(32475,'Belgium [Proximus]'),(32476,'Belgium [Proximus]'),(32477,'Belgium [Proximus]'),(32478,'Belgium [Proximus]'),(32479,'Belgium [Proximus]'),(3245,'Belgium Mobile'),(3247,'Belgium Mobile'),(3248,'Belgium Mobile'),(3249,'Belgium Mobile'),(501,'Belize'),(5016,'Belize Mobile'),(229,'Benin'),(22990,'Benin Mobile'),(22991,'Benin Mobile'),(22992,'Benin Mobile'),(22993,'Benin Mobile'),(22995,'Benin Mobile'),(22996,'Benin Mobile'),(22997,'Benin Mobile'),(1441,'Bermuda'),(14413,'Bermuda Mobile'),(144150,'Bermuda Mobile'),(144151,'Bermuda Mobile'),(144152,'Bermuda Mobile'),(144153,'Bermuda Mobile'),(1441590,'Bermuda Mobile'),(1441599,'Bermuda Mobile'),(14417,'Bermuda Mobile'),(975,'Bhutan'),(97517,'Bhutan Mobile'),(591,'Bolivia'),(5917,'Bolivia Mobile'),(387,'Bosnia-Herzegovina'),(3876,'Bosnia-Herzegovina Mobile'),(267,'Botswana'),(26771,'Botswana Mobile'),(26772,'Botswana Mobile'),(26773,'Botswana Mobile'),(26774,'Botswana Mobile'),(55,'Brazil'),(55117,'Brazil Mobile'),(55118,'Brazil Mobile'),(55119,'Brazil Mobile'),(551276,'Brazil Mobile'),(551278,'Brazil Mobile'),(551281,'Brazil Mobile'),(551282,'Brazil Mobile'),(551283,'Brazil Mobile'),(551284,'Brazil Mobile'),(551285,'Brazil Mobile'),(551286,'Brazil Mobile'),(551287,'Brazil Mobile'),(551289,'Brazil Mobile'),(55129,'Brazil Mobile'),(551376,'Brazil Mobile'),(551378,'Brazil Mobile'),(551381,'Brazil Mobile'),(551382,'Brazil Mobile'),(551383,'Brazil Mobile'),(551384,'Brazil Mobile'),(551385,'Brazil Mobile'),(551386,'Brazil Mobile'),(551387,'Brazil Mobile'),(551389,'Brazil Mobile'),(55139,'Brazil Mobile'),(551476,'Brazil Mobile'),(551478,'Brazil Mobile'),(551481,'Brazil Mobile'),(551482,'Brazil Mobile'),(551483,'Brazil Mobile'),(551484,'Brazil Mobile'),(551485,'Brazil Mobile'),(551486,'Brazil Mobile'),(551487,'Brazil Mobile'),(551489,'Brazil Mobile'),(55149,'Brazil Mobile'),(551576,'Brazil Mobile'),(551578,'Brazil Mobile'),(551581,'Brazil Mobile'),(551582,'Brazil Mobile'),(551583,'Brazil Mobile'),(551584,'Brazil Mobile'),(551585,'Brazil Mobile'),(551586,'Brazil Mobile'),(551587,'Brazil Mobile'),(551589,'Brazil Mobile'),(55159,'Brazil Mobile'),(55167,'Brazil Mobile'),(551681,'Brazil Mobile'),(551682,'Brazil Mobile'),(551683,'Brazil Mobile'),(551684,'Brazil Mobile'),(551685,'Brazil Mobile'),(551686,'Brazil Mobile'),(551687,'Brazil Mobile'),(551689,'Brazil Mobile'),(55169,'Brazil Mobile'),(55177,'Brazil Mobile'),(551781,'Brazil Mobile'),(551782,'Brazil Mobile'),(551783,'Brazil Mobile'),(551784,'Brazil Mobile'),(551785,'Brazil Mobile'),(551786,'Brazil Mobile'),(551787,'Brazil Mobile'),(551789,'Brazil Mobile'),(55179,'Brazil Mobile'),(551876,'Brazil Mobile'),(551878,'Brazil Mobile'),(551881,'Brazil Mobile'),(551882,'Brazil Mobile'),(551883,'Brazil Mobile'),(551884,'Brazil Mobile'),(551885,'Brazil Mobile'),(551886,'Brazil Mobile'),(551887,'Brazil Mobile'),(551889,'Brazil Mobile'),(55189,'Brazil Mobile'),(551976,'Brazil Mobile'),(551978,'Brazil Mobile'),(55198,'Brazil Mobile'),(55199,'Brazil Mobile'),(55217,'Brazil Mobile'),(55218,'Brazil Mobile'),(55219,'Brazil Mobile'),(552278,'Brazil Mobile'),(55228,'Brazil Mobile'),(55229,'Brazil Mobile'),(552478,'Brazil Mobile'),(55248,'Brazil Mobile'),(55249,'Brazil Mobile'),(552778,'Brazil Mobile'),(55278,'Brazil Mobile'),(55279,'Brazil Mobile'),(552878,'Brazil Mobile'),(552881,'Brazil Mobile'),(552882,'Brazil Mobile'),(552883,'Brazil Mobile'),(552885,'Brazil Mobile'),(552886,'Brazil Mobile'),(552887,'Brazil Mobile'),(552888,'Brazil Mobile'),(55289,'Brazil Mobile'),(553178,'Brazil Mobile'),(55318,'Brazil Mobile'),(55319,'Brazil Mobile'),(553278,'Brazil Mobile'),(553284,'Brazil Mobile'),(553285,'Brazil Mobile'),(553286,'Brazil Mobile'),(553287,'Brazil Mobile'),(553288,'Brazil Mobile'),(55329,'Brazil Mobile'),(553378,'Brazil Mobile'),(553384,'Brazil Mobile'),(553385,'Brazil Mobile'),(553386,'Brazil Mobile'),(553387,'Brazil Mobile'),(553388,'Brazil Mobile'),(55339,'Brazil Mobile'),(553478,'Brazil Mobile'),(553484,'Brazil Mobile'),(553485,'Brazil Mobile'),(553486,'Brazil Mobile'),(553487,'Brazil Mobile'),(553488,'Brazil Mobile'),(55349,'Brazil Mobile'),(553578,'Brazil Mobile'),(553584,'Brazil Mobile'),(553585,'Brazil Mobile'),(553586,'Brazil Mobile'),(553587,'Brazil Mobile'),(553588,'Brazil Mobile'),(55359,'Brazil Mobile'),(553778,'Brazil Mobile'),(553784,'Brazil Mobile'),(553785,'Brazil Mobile'),(553786,'Brazil Mobile'),(553787,'Brazil Mobile'),(553788,'Brazil Mobile'),(55379,'Brazil Mobile'),(553878,'Brazil Mobile'),(553884,'Brazil Mobile'),(553885,'Brazil Mobile'),(553886,'Brazil Mobile'),(553887,'Brazil Mobile'),(553888,'Brazil Mobile'),(55389,'Brazil Mobile'),(554170,'Brazil Mobile'),(554178,'Brazil Mobile'),(554184,'Brazil Mobile'),(554185,'Brazil Mobile'),(554188,'Brazil Mobile'),(55419,'Brazil Mobile'),(55427,'Brazil Mobile'),(554284,'Brazil Mobile'),(554285,'Brazil Mobile'),(554288,'Brazil Mobile'),(55429,'Brazil Mobile'),(554378,'Brazil Mobile'),(554381,'Brazil Mobile'),(554384,'Brazil Mobile'),(554385,'Brazil Mobile'),(554388,'Brazil Mobile'),(55439,'Brazil Mobile'),(554478,'Brazil Mobile'),(554484,'Brazil Mobile'),(554485,'Brazil Mobile'),(554488,'Brazil Mobile'),(55449,'Brazil Mobile'),(554578,'Brazil Mobile'),(554584,'Brazil Mobile'),(554585,'Brazil Mobile'),(554588,'Brazil Mobile'),(55459,'Brazil Mobile'),(554678,'Brazil Mobile'),(554684,'Brazil Mobile'),(554685,'Brazil Mobile'),(554688,'Brazil Mobile'),(55469,'Brazil Mobile'),(55477,'Brazil Mobile'),(554784,'Brazil Mobile'),(554785,'Brazil Mobile'),(554788,'Brazil Mobile'),(55479,'Brazil Mobile'),(55487,'Brazil Mobile'),(554881,'Brazil Mobile'),(554884,'Brazil Mobile'),(554885,'Brazil Mobile'),(554888,'Brazil Mobile'),(55489,'Brazil Mobile'),(554978,'Brazil Mobile'),(554984,'Brazil Mobile'),(554985,'Brazil Mobile'),(554988,'Brazil Mobile'),(55499,'Brazil Mobile'),(555178,'Brazil Mobile'),(55518,'Brazil Mobile'),(55519,'Brazil Mobile'),(555378,'Brazil Mobile'),(555381,'Brazil Mobile'),(555382,'Brazil Mobile'),(555384,'Brazil Mobile'),(555385,'Brazil Mobile'),(555389,'Brazil Mobile'),(55539,'Brazil Mobile'),(555478,'Brazil Mobile'),(555481,'Brazil Mobile'),(555482,'Brazil Mobile'),(555484,'Brazil Mobile'),(555485,'Brazil Mobile'),(555489,'Brazil Mobile'),(55549,'Brazil Mobile'),(555578,'Brazil Mobile'),(555581,'Brazil Mobile'),(555582,'Brazil Mobile'),(555584,'Brazil Mobile'),(555585,'Brazil Mobile'),(555589,'Brazil Mobile'),(55559,'Brazil Mobile'),(556178,'Brazil Mobile'),(556181,'Brazil Mobile'),(556182,'Brazil Mobile'),(556184,'Brazil Mobile'),(556185,'Brazil Mobile'),(556189,'Brazil Mobile'),(55619,'Brazil Mobile'),(55627,'Brazil Mobile'),(55628,'Brazil Mobile'),(55629,'Brazil Mobile'),(556378,'Brazil Mobile'),(556381,'Brazil Mobile'),(556382,'Brazil Mobile'),(556384,'Brazil Mobile'),(556385,'Brazil Mobile'),(556389,'Brazil Mobile'),(55639,'Brazil Mobile'),(556478,'Brazil Mobile'),(556481,'Brazil Mobile'),(556482,'Brazil Mobile'),(556484,'Brazil Mobile'),(556485,'Brazil Mobile'),(556489,'Brazil Mobile'),(55649,'Brazil Mobile'),(556578,'Brazil Mobile'),(55658,'Brazil Mobile'),(55659,'Brazil Mobile'),(556678,'Brazil Mobile'),(556681,'Brazil Mobile'),(556682,'Brazil Mobile'),(556684,'Brazil Mobile'),(556685,'Brazil Mobile'),(556688,'Brazil Mobile'),(556689,'Brazil Mobile'),(55669,'Brazil Mobile'),(556778,'Brazil Mobile'),(556781,'Brazil Mobile'),(556782,'Brazil Mobile'),(556784,'Brazil Mobile'),(556785,'Brazil Mobile'),(556788,'Brazil Mobile'),(556789,'Brazil Mobile'),(55679,'Brazil Mobile'),(556878,'Brazil Mobile'),(55688,'Brazil Mobile'),(55689,'Brazil Mobile'),(556978,'Brazil Mobile'),(556981,'Brazil Mobile'),(556982,'Brazil Mobile'),(556984,'Brazil Mobile'),(556985,'Brazil Mobile'),(556988,'Brazil Mobile'),(556989,'Brazil Mobile'),(55699,'Brazil Mobile'),(557178,'Brazil Mobile'),(55718,'Brazil Mobile'),(55719,'Brazil Mobile'),(557378,'Brazil Mobile'),(557381,'Brazil Mobile'),(557382,'Brazil Mobile'),(557385,'Brazil Mobile'),(557386,'Brazil Mobile'),(557387,'Brazil Mobile'),(557388,'Brazil Mobile'),(55739,'Brazil Mobile'),(557478,'Brazil Mobile'),(557481,'Brazil Mobile'),(557482,'Brazil Mobile'),(557485,'Brazil Mobile'),(557486,'Brazil Mobile'),(557487,'Brazil Mobile'),(557488,'Brazil Mobile'),(55749,'Brazil Mobile'),(557578,'Brazil Mobile'),(557581,'Brazil Mobile'),(557582,'Brazil Mobile'),(557585,'Brazil Mobile'),(557586,'Brazil Mobile'),(557587,'Brazil Mobile'),(557588,'Brazil Mobile'),(55759,'Brazil Mobile'),(557778,'Brazil Mobile'),(557781,'Brazil Mobile'),(557782,'Brazil Mobile'),(557785,'Brazil Mobile'),(557786,'Brazil Mobile'),(557787,'Brazil Mobile'),(557788,'Brazil Mobile'),(55779,'Brazil Mobile'),(557978,'Brazil Mobile'),(557981,'Brazil Mobile'),(557982,'Brazil Mobile'),(557985,'Brazil Mobile'),(557986,'Brazil Mobile'),(557987,'Brazil Mobile'),(557988,'Brazil Mobile'),(55799,'Brazil Mobile'),(55818,'Brazil Mobile'),(55819,'Brazil Mobile'),(558285,'Brazil Mobile'),(558286,'Brazil Mobile'),(558287,'Brazil Mobile'),(558288,'Brazil Mobile'),(55829,'Brazil Mobile'),(55838,'Brazil Mobile'),(55839,'Brazil Mobile'),(55848,'Brazil Mobile'),(55849,'Brazil Mobile'),(55858,'Brazil Mobile'),(55859,'Brazil Mobile'),(558685,'Brazil Mobile'),(558686,'Brazil Mobile'),(558687,'Brazil Mobile'),(558688,'Brazil Mobile'),(55869,'Brazil Mobile'),(558785,'Brazil Mobile'),(558786,'Brazil Mobile'),(558787,'Brazil Mobile'),(558788,'Brazil Mobile'),(55879,'Brazil Mobile'),(558885,'Brazil Mobile'),(558886,'Brazil Mobile'),(558887,'Brazil Mobile'),(558888,'Brazil Mobile'),(55889,'Brazil Mobile'),(558985,'Brazil Mobile'),(558986,'Brazil Mobile'),(558987,'Brazil Mobile'),(558988,'Brazil Mobile'),(55899,'Brazil Mobile'),(55918,'Brazil Mobile'),(55919,'Brazil Mobile'),(55928,'Brazil Mobile'),(55929,'Brazil Mobile'),(559381,'Brazil Mobile'),(559382,'Brazil Mobile'),(559383,'Brazil Mobile'),(559385,'Brazil Mobile'),(559386,'Brazil Mobile'),(559387,'Brazil Mobile'),(559388,'Brazil Mobile'),(559389,'Brazil Mobile'),(55939,'Brazil Mobile'),(559481,'Brazil Mobile'),(559482,'Brazil Mobile'),(559483,'Brazil Mobile'),(559485,'Brazil Mobile'),(559486,'Brazil Mobile'),(559487,'Brazil Mobile'),(559488,'Brazil Mobile'),(559489,'Brazil Mobile'),(55949,'Brazil Mobile'),(55958,'Brazil Mobile'),(55959,'Brazil Mobile'),(55968,'Brazil Mobile'),(55969,'Brazil Mobile'),(559781,'Brazil Mobile'),(559782,'Brazil Mobile'),(559783,'Brazil Mobile'),(559785,'Brazil Mobile'),(559786,'Brazil Mobile'),(559787,'Brazil Mobile'),(559788,'Brazil Mobile'),(55979,'Brazil Mobile'),(559881,'Brazil Mobile'),(559882,'Brazil Mobile'),(559883,'Brazil Mobile'),(559885,'Brazil Mobile'),(559886,'Brazil Mobile'),(559887,'Brazil Mobile'),(559888,'Brazil Mobile'),(559889,'Brazil Mobile'),(55989,'Brazil Mobile'),(55998,'Brazil Mobile'),(55999,'Brazil Mobile'),(246,'British Indian Ocean Territory'),(1284,'British Virgin Islands'),(12843,'British Virgin Islands Mobile'),(1284301,'British Virgin Islands Mobile'),(1284302,'British Virgin Islands Mobile'),(1284303,'British Virgin Islands Mobile'),(1284440,'British Virgin Islands Mobile'),(1284441,'British Virgin Islands Mobile'),(1284442,'British Virgin Islands Mobile'),(1284443,'British Virgin Islands Mobile'),(1284444,'British Virgin Islands Mobile'),(1284445,'British Virgin Islands Mobile'),(1284468,'British Virgin Islands Mobile'),(12844966,'British Virgin Islands Mobile'),(12844967,'British Virgin Islands Mobile'),(12844968,'British Virgin Islands Mobile'),(12844969,'British Virgin Islands Mobile'),(1284499,'British Virgin Islands Mobile'),(1284540,'British Virgin Islands Mobile'),(1284541,'British Virgin Islands Mobile'),(1284542,'British Virgin Islands Mobile'),(1284543,'British Virgin Islands Mobile'),(1284544,'British Virgin Islands Mobile'),(673,'Brunei Darussalam'),(6738,'Brunei Darussalam Mobile'),(359,'Bulgaria'),(359430,'Bulgaria Mobile'),(359437,'Bulgaria Mobile'),(359438,'Bulgaria Mobile'),(359439,'Bulgaria Mobile'),(35948,'Bulgaria Mobile'),(35987,'Bulgaria Mobile'),(35988,'Bulgaria Mobile'),(35989,'Bulgaria Mobile'),(35998,'Bulgaria Mobile'),(35999,'Bulgaria Mobile'),(226,'Burkina Faso'),(22670,'Burkina Faso Mobile'),(22675,'Burkina Faso Mobile'),(22676,'Burkina Faso Mobile'),(22678,'Burkina Faso Mobile'),(257,'Burundi'),(2572955,'Burundi Mobile'),(25776,'Burundi Mobile'),(25777,'Burundi Mobile'),(25778,'Burundi Mobile'),(25779,'Burundi Mobile'),(855,'Cambodia'),(8551,'Cambodia Mobile'),(85589,'Cambodia Mobile'),(8559,'Cambodia Mobile'),(237,'Cameroon'),(2377,'Cameroon Mobile'),(2379,'Cameroon Mobile'),(1204,'Canada'),(1226,'Canada'),(1250,'Canada'),(1289,'Canada'),(1306,'Canada'),(1403,'Canada'),(1416,'Canada'),(1418,'Canada'),(1438,'Canada'),(1450,'Canada'),(1506,'Canada'),(1514,'Canada'),(1519,'Canada'),(1581,'Canada'),(1587,'Canada'),(16,'Canada'),(1604,'Canada'),(1613,'Canada'),(1647,'Canada'),(1705,'Canada'),(1709,'Canada'),(1778,'Canada'),(1780,'Canada'),(1807,'Canada'),(1819,'Canada'),(1867,'Canada'),(1871,'Canada'),(1902,'Canada'),(1905,'Canada'),(238,'Cape Verde'),(23891,'Cape Verde Mobile'),(23897,'Cape Verde Mobile'),(23898,'Cape Verde Mobile'),(23899,'Cape Verde Mobile'),(1345,'Cayman Islands'),(1345229,'Cayman Islands Mobile'),(1345321,'Cayman Islands Mobile'),(1345322,'Cayman Islands Mobile'),(1345323,'Cayman Islands Mobile'),(1345324,'Cayman Islands Mobile'),(1345325,'Cayman Islands Mobile'),(1345326,'Cayman Islands Mobile'),(1345327,'Cayman Islands Mobile'),(1345328,'Cayman Islands Mobile'),(1345329,'Cayman Islands Mobile'),(1345516,'Cayman Islands Mobile'),(1345517,'Cayman Islands Mobile'),(1345525,'Cayman Islands Mobile'),(1345526,'Cayman Islands Mobile'),(1345527,'Cayman Islands Mobile'),(1345547,'Cayman Islands Mobile'),(1345548,'Cayman Islands Mobile'),(1345916,'Cayman Islands Mobile'),(1345917,'Cayman Islands Mobile'),(1345919,'Cayman Islands Mobile'),(1345924,'Cayman Islands Mobile'),(1345925,'Cayman Islands Mobile'),(1345926,'Cayman Islands Mobile'),(1345927,'Cayman Islands Mobile'),(1345928,'Cayman Islands Mobile'),(1345929,'Cayman Islands Mobile'),(1345930,'Cayman Islands Mobile'),(1345938,'Cayman Islands Mobile'),(1345939,'Cayman Islands Mobile'),(236,'Central African Republic'),(23670,'Central African Republic Mobil'),(23672,'Central African Republic Mobil'),(23675,'Central African Republic Mobil'),(23677,'Central African Republic Mobil'),(235,'Chad'),(2352,'Chad Mobile'),(23530,'Chad Mobile'),(23531,'Chad Mobile'),(23532,'Chad Mobile'),(23533,'Chad Mobile'),(23534,'Chad Mobile'),(23535,'Chad Mobile'),(2356,'Chad Mobile'),(2357,'Chad Mobile'),(2359,'Chad Mobile'),(56,'Falkland Islands Mobile'),(568,'Chile Mobile'),(569,'Chile Mobile'),(86,'China'),(8613,'China Mobile'),(8615,'China Mobile'),(86189,'China Mobile'),(57,'Colombia'),(57310,'Colombia [Comcel]'),(57311,'Colombia [Comcel]'),(57312,'Colombia [Comcel]'),(57313,'Colombia [Comcel]'),(57314,'Colombia [Comcel]'),(57315,'Colombia [Movistar]'),(57316,'Colombia [Movistar]'),(57317,'Colombia [Movistar]'),(57318,'Colombia [Movistar]'),(573,'Colombia Mobile'),(57301,'Colombia [Ola]'),(57304,'Colombia [Ola]'),(269,'Comoros'),(2693,'Comoros Mobile'),(242,'Congo'),(243,'Congo Democratic Republic'),(24322,'Congo Democratic Republic Mobi'),(24378,'Congo Democratic Republic Mobi'),(24380,'Congo Democratic Republic Mobi'),(24381,'Congo Democratic Republic Mobi'),(24384,'Congo Democratic Republic Mobi'),(24385,'Congo Democratic Republic Mobi'),(24388,'Congo Democratic Republic Mobi'),(24389,'Congo Democratic Republic Mobi'),(24397,'Congo Democratic Republic Mobi'),(24398,'Congo Democratic Republic Mobi'),(24399,'Congo Democratic Republic Mobi'),(2424,'Congo Mobile'),(2425,'Congo Mobile'),(2426,'Congo Mobile'),(682,'Cook Islands'),(68250,'Cook Islands Mobile'),(68251,'Cook Islands Mobile'),(68252,'Cook Islands Mobile'),(68253,'Cook Islands Mobile'),(68254,'Cook Islands Mobile'),(68255,'Cook Islands Mobile'),(68256,'Cook Islands Mobile'),(68258,'Cook Islands Mobile'),(6827,'Cook Islands Mobile'),(506,'Costa Rica'),(5068,'Costa Rica Mobile'),(385,'Croatia'),(38591,'Croatia Mobile'),(38592,'Croatia Mobile'),(38595,'Croatia Mobile'),(38597,'Croatia Mobile'),(38598,'Croatia Mobile'),(38599,'Croatia Mobile'),(53,'Cuba'),(5352,'Cuba Mobile'),(5358,'Cuba Mobile'),(537750,'Cuba Mobile'),(537751,'Cuba Mobile'),(537752,'Cuba Mobile'),(537753,'Cuba Mobile'),(537754,'Cuba Mobile'),(537755,'Cuba Mobile'),(537756,'Cuba Mobile'),(537758,'Cuba Mobile'),(357,'Cyprus'),(35796,'Cyprus Mobile'),(357976,'Cyprus Mobile'),(357977,'Cyprus Mobile'),(35799,'Cyprus Mobile'),(420,'Czech Republic'),(42060,'Czech Republic Mobile'),(42072,'Czech Republic Mobile'),(42073,'Czech Republic Mobile'),(42077,'Czech Republic Mobile'),(42079,'Czech Republic Mobile'),(42093,'Czech Republic Mobile'),(42096,'Czech Republic Mobile'),(45,'Denmark'),(452,'Denmark Mobile'),(4530,'Denmark Mobile'),(4531,'Denmark Mobile'),(4540,'Denmark Mobile'),(45411,'Denmark Mobile'),(45412,'Denmark Mobile'),(45413,'Denmark Mobile'),(45414,'Denmark Mobile'),(45415,'Denmark Mobile'),(45416,'Denmark Mobile'),(45417,'Denmark Mobile'),(45418,'Denmark Mobile'),(45419,'Denmark Mobile'),(45420,'Denmark Mobile'),(45421,'Denmark Mobile'),(45422,'Denmark Mobile'),(45423,'Denmark Mobile'),(45424,'Denmark Mobile'),(45425,'Denmark Mobile'),(454260,'Denmark Mobile'),(454270,'Denmark Mobile'),(454276,'Denmark Mobile'),(454277,'Denmark Mobile'),(454278,'Denmark Mobile'),(454279,'Denmark Mobile'),(454280,'Denmark Mobile'),(454281,'Denmark Mobile'),(454282,'Denmark Mobile'),(454283,'Denmark Mobile'),(454284,'Denmark Mobile'),(454285,'Denmark Mobile'),(454286,'Denmark Mobile'),(454287,'Denmark Mobile'),(454288,'Denmark Mobile'),(454289,'Denmark Mobile'),(454290,'Denmark Mobile'),(454291,'Denmark Mobile'),(454292,'Denmark Mobile'),(454293,'Denmark Mobile'),(454294,'Denmark Mobile'),(454295,'Denmark Mobile'),(454296,'Denmark Mobile'),(454297,'Denmark Mobile'),(454298,'Denmark Mobile'),(454299,'Denmark Mobile'),(4550,'Denmark Mobile'),(4551,'Denmark Mobile'),(455210,'Denmark Mobile'),(455211,'Denmark Mobile'),(455212,'Denmark Mobile'),(455213,'Denmark Mobile'),(455214,'Denmark Mobile'),(455215,'Denmark Mobile'),(455216,'Denmark Mobile'),(455217,'Denmark Mobile'),(455218,'Denmark Mobile'),(455219,'Denmark Mobile'),(455220,'Denmark Mobile'),(455221,'Denmark Mobile'),(455222,'Denmark Mobile'),(455223,'Denmark Mobile'),(455224,'Denmark Mobile'),(455225,'Denmark Mobile'),(455226,'Denmark Mobile'),(455227,'Denmark Mobile'),(455228,'Denmark Mobile'),(455229,'Denmark Mobile'),(455230,'Denmark Mobile'),(455231,'Denmark Mobile'),(455232,'Denmark Mobile'),(455233,'Denmark Mobile'),(455234,'Denmark Mobile'),(455235,'Denmark Mobile'),(455236,'Denmark Mobile'),(455237,'Denmark Mobile'),(455238,'Denmark Mobile'),(455239,'Denmark Mobile'),(455240,'Denmark Mobile'),(455241,'Denmark Mobile'),(455242,'Denmark Mobile'),(455243,'Denmark Mobile'),(455244,'Denmark Mobile'),(455245,'Denmark Mobile'),(455246,'Denmark Mobile'),(455247,'Denmark Mobile'),(455249,'Denmark Mobile'),(455250,'Denmark Mobile'),(455252,'Denmark Mobile'),(455253,'Denmark Mobile'),(455255,'Denmark Mobile'),(455258,'Denmark Mobile'),(455260,'Denmark Mobile'),(455262,'Denmark Mobile'),(455266,'Denmark Mobile'),(455270,'Denmark Mobile'),(455271,'Denmark Mobile'),(455272,'Denmark Mobile'),(455273,'Denmark Mobile'),(455274,'Denmark Mobile'),(455275,'Denmark Mobile'),(455276,'Denmark Mobile'),(455277,'Denmark Mobile'),(455280,'Denmark Mobile'),(455282,'Denmark Mobile'),(455288,'Denmark Mobile'),(455290,'Denmark Mobile'),(455292,'Denmark Mobile'),(455299,'Denmark Mobile'),(455310,'Denmark Mobile'),(455311,'Denmark Mobile'),(455312,'Denmark Mobile'),(455314,'Denmark Mobile'),(455315,'Denmark Mobile'),(455316,'Denmark Mobile'),(455317,'Denmark Mobile'),(455318,'Denmark Mobile'),(455319,'Denmark Mobile'),(45532,'Denmark Mobile'),(455330,'Denmark Mobile'),(455331,'Denmark Mobile'),(455332,'Denmark Mobile'),(455333,'Denmark Mobile'),(455334,'Denmark Mobile'),(455335,'Denmark Mobile'),(455336,'Denmark Mobile'),(455337,'Denmark Mobile'),(455338,'Denmark Mobile'),(455339,'Denmark Mobile'),(45534,'Denmark Mobile'),(45535,'Denmark Mobile'),(45536,'Denmark Mobile'),(45537,'Denmark Mobile'),(45538,'Denmark Mobile'),(45539,'Denmark Mobile'),(4560,'Denmark Mobile'),(4561,'Denmark Mobile'),(253,'Rwanda Mobile'),(2536,'Djibouti Mobile'),(25380,'Djibouti Mobile'),(25381,'Djibouti Mobile'),(25382,'Djibouti Mobile'),(25383,'Djibouti Mobile'),(25384,'Djibouti Mobile'),(25385,'Djibouti Mobile'),(25386,'Djibouti Mobile'),(25387,'Djibouti Mobile'),(1767,'Dominica'),(1767225,'Dominica Mobile'),(1767235,'Dominica Mobile'),(1767245,'Dominica Mobile'),(1767265,'Dominica Mobile'),(1767275,'Dominica Mobile'),(1767276,'Dominica Mobile'),(1767277,'Dominica Mobile'),(1767315,'Dominica Mobile'),(1767316,'Dominica Mobile'),(1767317,'Dominica Mobile'),(1767611,'Dominica Mobile'),(1767612,'Dominica Mobile'),(1767613,'Dominica Mobile'),(1767614,'Dominica Mobile'),(1767615,'Dominica Mobile'),(1767616,'Dominica Mobile'),(1767617,'Dominica Mobile'),(1809,'Dominican Republic'),(1829,'Dominican Republic'),(1809201,'Dominican Republic Mobile'),(1809203,'Dominican Republic Mobile'),(1809204,'Dominican Republic Mobile'),(1809205,'Dominican Republic Mobile'),(1809206,'Dominican Republic Mobile'),(1809207,'Dominican Republic Mobile'),(1809208,'Dominican Republic Mobile'),(1809209,'Dominican Republic Mobile'),(1809210,'Dominican Republic Mobile'),(1809212,'Dominican Republic Mobile'),(1809214,'Dominican Republic Mobile'),(1809215,'Dominican Republic Mobile'),(1809216,'Dominican Republic Mobile'),(1809217,'Dominican Republic Mobile'),(1809218,'Dominican Republic Mobile'),(1809219,'Dominican Republic Mobile'),(18092223,'Dominican Republic Mobile'),(18092224,'Dominican Republic Mobile'),(18092225,'Dominican Republic Mobile'),(1809223,'Dominican Republic Mobile'),(1809224,'Dominican Republic Mobile'),(1809225,'Dominican Republic Mobile'),(1809228,'Dominican Republic Mobile'),(1809229,'Dominican Republic Mobile'),(1809230,'Dominican Republic Mobile'),(1809232,'Dominican Republic Mobile'),(1809235,'Dominican Republic Mobile'),(18092480,'Dominican Republic Mobile'),(18092482,'Dominican Republic Mobile'),(18092484,'Dominican Republic Mobile'),(18092485,'Dominican Republic Mobile'),(18092486,'Dominican Republic Mobile'),(18092487,'Dominican Republic Mobile'),(18092488,'Dominican Republic Mobile'),(1809249,'Dominican Republic Mobile'),(1809250,'Dominican Republic Mobile'),(1809251,'Dominican Republic Mobile'),(1809252,'Dominican Republic Mobile'),(1809253,'Dominican Republic Mobile'),(1809254,'Dominican Republic Mobile'),(1809256,'Dominican Republic Mobile'),(1809257,'Dominican Republic Mobile'),(1809258,'Dominican Republic Mobile'),(1809259,'Dominican Republic Mobile'),(1809260,'Dominican Republic Mobile'),(1809264,'Dominican Republic Mobile'),(18092651,'Dominican Republic Mobile'),(18092652,'Dominican Republic Mobile'),(18092653,'Dominican Republic Mobile'),(18092654,'Dominican Republic Mobile'),(18092655,'Dominican Republic Mobile'),(18092656,'Dominican Republic Mobile'),(18092657,'Dominican Republic Mobile'),(18092658,'Dominican Republic Mobile'),(18092659,'Dominican Republic Mobile'),(1809266,'Dominican Republic Mobile'),(1809267,'Dominican Republic Mobile'),(1809268,'Dominican Republic Mobile'),(1809269,'Dominican Republic Mobile'),(1809270,'Dominican Republic Mobile'),(1809271,'Dominican Republic Mobile'),(1809272,'Dominican Republic Mobile'),(1809280,'Dominican Republic Mobile'),(1809281,'Dominican Republic Mobile'),(1809282,'Dominican Republic Mobile'),(1809283,'Dominican Republic Mobile'),(1809284,'Dominican Republic Mobile'),(1809292,'Dominican Republic Mobile'),(1809293,'Dominican Republic Mobile'),(1809297,'Dominican Republic Mobile'),(1809298,'Dominican Republic Mobile'),(1809299,'Dominican Republic Mobile'),(1809301,'Dominican Republic Mobile'),(1809302,'Dominican Republic Mobile'),(1809303,'Dominican Republic Mobile'),(1809304,'Dominican Republic Mobile'),(1809305,'Dominican Republic Mobile'),(1809306,'Dominican Republic Mobile'),(1809307,'Dominican Republic Mobile'),(1809308,'Dominican Republic Mobile'),(1809309,'Dominican Republic Mobile'),(1809310,'Dominican Republic Mobile'),(1809313,'Dominican Republic Mobile'),(1809315,'Dominican Republic Mobile'),(1809316,'Dominican Republic Mobile'),(1809317,'Dominican Republic Mobile'),(1809318,'Dominican Republic Mobile'),(1809319,'Dominican Republic Mobile'),(1809321,'Dominican Republic Mobile'),(1809322,'Dominican Republic Mobile'),(1809323,'Dominican Republic Mobile'),(1809324,'Dominican Republic Mobile'),(1809325,'Dominican Republic Mobile'),(1809326,'Dominican Republic Mobile'),(1809327,'Dominican Republic Mobile'),(1809330,'Dominican Republic Mobile'),(1809340,'Dominican Republic Mobile'),(1809341,'Dominican Republic Mobile'),(1809342,'Dominican Republic Mobile'),(1809343,'Dominican Republic Mobile'),(1809344,'Dominican Republic Mobile'),(1809345,'Dominican Republic Mobile'),(1809348,'Dominican Republic Mobile'),(1809350,'Dominican Republic Mobile'),(1809351,'Dominican Republic Mobile'),(1809352,'Dominican Republic Mobile'),(1809353,'Dominican Republic Mobile'),(1809354,'Dominican Republic Mobile'),(1809355,'Dominican Republic Mobile'),(1809356,'Dominican Republic Mobile'),(1809357,'Dominican Republic Mobile'),(1809358,'Dominican Republic Mobile'),(1809359,'Dominican Republic Mobile'),(1809360,'Dominican Republic Mobile'),(1809361,'Dominican Republic Mobile'),(1809366,'Dominican Republic Mobile'),(1809370,'Dominican Republic Mobile'),(1809371,'Dominican Republic Mobile'),(1809374,'Dominican Republic Mobile'),(1809376,'Dominican Republic Mobile'),(1809377,'Dominican Republic Mobile'),(1809383,'Dominican Republic Mobile'),(1809386,'Dominican Republic Mobile'),(1809387,'Dominican Republic Mobile'),(1809389,'Dominican Republic Mobile'),(1809390,'Dominican Republic Mobile'),(1809391,'Dominican Republic Mobile'),(1809392,'Dominican Republic Mobile'),(1809393,'Dominican Republic Mobile'),(1809394,'Dominican Republic Mobile'),(1809395,'Dominican Republic Mobile'),(1809396,'Dominican Republic Mobile'),(1809397,'Dominican Republic Mobile'),(1809398,'Dominican Republic Mobile'),(1809399,'Dominican Republic Mobile'),(1809401,'Dominican Republic Mobile'),(1809402,'Dominican Republic Mobile'),(1809403,'Dominican Republic Mobile'),(1809404,'Dominican Republic Mobile'),(1809405,'Dominican Republic Mobile'),(1809406,'Dominican Republic Mobile'),(1809407,'Dominican Republic Mobile'),(1809408,'Dominican Republic Mobile'),(1809409,'Dominican Republic Mobile'),(1809410,'Dominican Republic Mobile'),(1809413,'Dominican Republic Mobile'),(1809415,'Dominican Republic Mobile'),(1809416,'Dominican Republic Mobile'),(1809417,'Dominican Republic Mobile'),(1809418,'Dominican Republic Mobile'),(1809419,'Dominican Republic Mobile'),(1809420,'Dominican Republic Mobile'),(1809421,'Dominican Republic Mobile'),(1809423,'Dominican Republic Mobile'),(1809424,'Dominican Republic Mobile'),(1809425,'Dominican Republic Mobile'),(1809426,'Dominican Republic Mobile'),(1809427,'Dominican Republic Mobile'),(1809428,'Dominican Republic Mobile'),(1809429,'Dominican Republic Mobile'),(1809430,'Dominican Republic Mobile'),(1809431,'Dominican Republic Mobile'),(1809432,'Dominican Republic Mobile'),(1809433,'Dominican Republic Mobile'),(1809434,'Dominican Republic Mobile'),(1809436,'Dominican Republic Mobile'),(1809437,'Dominican Republic Mobile'),(1809438,'Dominican Republic Mobile'),(1809439,'Dominican Republic Mobile'),(1809440,'Dominican Republic Mobile'),(1809441,'Dominican Republic Mobile'),(1809442,'Dominican Republic Mobile'),(1809443,'Dominican Republic Mobile'),(1809444,'Dominican Republic Mobile'),(1809445,'Dominican Republic Mobile'),(1809446,'Dominican Republic Mobile'),(1809447,'Dominican Republic Mobile'),(1809448,'Dominican Republic Mobile'),(1809449,'Dominican Republic Mobile'),(1809451,'Dominican Republic Mobile'),(1809452,'Dominican Republic Mobile'),(1809453,'Dominican Republic Mobile'),(1809454,'Dominican Republic Mobile'),(1809456,'Dominican Republic Mobile'),(1809457,'Dominican Republic Mobile'),(1809458,'Dominican Republic Mobile'),(1809459,'Dominican Republic Mobile'),(1809460,'Dominican Republic Mobile'),(1809461,'Dominican Republic Mobile'),(1809462,'Dominican Republic Mobile'),(1809463,'Dominican Republic Mobile'),(1809464,'Dominican Republic Mobile'),(1809465,'Dominican Republic Mobile'),(1809467,'Dominican Republic Mobile'),(18094701,'Dominican Republic Mobile'),(18094702,'Dominican Republic Mobile'),(18094703,'Dominican Republic Mobile'),(18094704,'Dominican Republic Mobile'),(18094705,'Dominican Republic Mobile'),(18094706,'Dominican Republic Mobile'),(18094707,'Dominican Republic Mobile'),(18094708,'Dominican Republic Mobile'),(1809474,'Dominican Republic Mobile'),(1809475,'Dominican Republic Mobile'),(1809477,'Dominican Republic Mobile'),(1809478,'Dominican Republic Mobile'),(1809479,'Dominican Republic Mobile'),(1809481,'Dominican Republic Mobile'),(1809484,'Dominican Republic Mobile'),(1809485,'Dominican Republic Mobile'),(1809486,'Dominican Republic Mobile'),(1809488,'Dominican Republic Mobile'),(1809490,'Dominican Republic Mobile'),(1809491,'Dominican Republic Mobile'),(1809492,'Dominican Republic Mobile'),(1809493,'Dominican Republic Mobile'),(1809494,'Dominican Republic Mobile'),(1809495,'Dominican Republic Mobile'),(1809496,'Dominican Republic Mobile'),(1809497,'Dominican Republic Mobile'),(1809498,'Dominican Republic Mobile'),(1809499,'Dominican Republic Mobile'),(1809501,'Dominican Republic Mobile'),(1809502,'Dominican Republic Mobile'),(1809504,'Dominican Republic Mobile'),(1809505,'Dominican Republic Mobile'),(1809506,'Dominican Republic Mobile'),(1809507,'Dominican Republic Mobile'),(1809509,'Dominican Republic Mobile'),(1809510,'Dominican Republic Mobile'),(1809512,'Dominican Republic Mobile'),(1809513,'Dominican Republic Mobile'),(1809514,'Dominican Republic Mobile'),(1809515,'Dominican Republic Mobile'),(1809516,'Dominican Republic Mobile'),(1809517,'Dominican Republic Mobile'),(1809519,'Dominican Republic Mobile'),(1809520,'Dominican Republic Mobile'),(180954290,'Dominican Republic Mobile'),(180954291,'Dominican Republic Mobile'),(180954292,'Dominican Republic Mobile'),(180954293,'Dominican Republic Mobile'),(180954295,'Dominican Republic Mobile'),(180954296,'Dominican Republic Mobile'),(180954297,'Dominican Republic Mobile'),(180954298,'Dominican Republic Mobile'),(1809543,'Dominican Republic Mobile'),(18095450,'Dominican Republic Mobile'),(18095451,'Dominican Republic Mobile'),(18095454,'Dominican Republic Mobile'),(18095456,'Dominican Republic Mobile'),(18095459,'Dominican Republic Mobile'),(1809546,'Dominican Republic Mobile'),(1809601,'Dominican Republic Mobile'),(1809602,'Dominican Republic Mobile'),(1809603,'Dominican Republic Mobile'),(1809604,'Dominican Republic Mobile'),(1809605,'Dominican Republic Mobile'),(1809606,'Dominican Republic Mobile'),(1809607,'Dominican Republic Mobile'),(1809608,'Dominican Republic Mobile'),(1809609,'Dominican Republic Mobile'),(1809610,'Dominican Republic Mobile'),(1809613,'Dominican Republic Mobile'),(1809614,'Dominican Republic Mobile'),(1809615,'Dominican Republic Mobile'),(1809617,'Dominican Republic Mobile'),(1809618,'Dominican Republic Mobile'),(1809619,'Dominican Republic Mobile'),(1809624,'Dominican Republic Mobile'),(1809627,'Dominican Republic Mobile'),(1809628,'Dominican Republic Mobile'),(1809629,'Dominican Republic Mobile'),(1809630,'Dominican Republic Mobile'),(1809631,'Dominican Republic Mobile'),(1809632,'Dominican Republic Mobile'),(1809634,'Dominican Republic Mobile'),(1809635,'Dominican Republic Mobile'),(1809636,'Dominican Republic Mobile'),(1809637,'Dominican Republic Mobile'),(1809639,'Dominican Republic Mobile'),(1809640,'Dominican Republic Mobile'),(1809641,'Dominican Republic Mobile'),(1809642,'Dominican Republic Mobile'),(1809643,'Dominican Republic Mobile'),(1809644,'Dominican Republic Mobile'),(1809645,'Dominican Republic Mobile'),(1809646,'Dominican Republic Mobile'),(1809647,'Dominican Republic Mobile'),(1809648,'Dominican Republic Mobile'),(1809649,'Dominican Republic Mobile'),(1809650,'Dominican Republic Mobile'),(1809651,'Dominican Republic Mobile'),(1809652,'Dominican Republic Mobile'),(1809653,'Dominican Republic Mobile'),(1809654,'Dominican Republic Mobile'),(1809656,'Dominican Republic Mobile'),(1809657,'Dominican Republic Mobile'),(1809658,'Dominican Republic Mobile'),(1809659,'Dominican Republic Mobile'),(1809660,'Dominican Republic Mobile'),(1809661,'Dominican Republic Mobile'),(1809662,'Dominican Republic Mobile'),(1809663,'Dominican Republic Mobile'),(1809664,'Dominican Republic Mobile'),(1809665,'Dominican Republic Mobile'),(1809666,'Dominican Republic Mobile'),(1809667,'Dominican Republic Mobile'),(1809668,'Dominican Republic Mobile'),(1809669,'Dominican Republic Mobile'),(1809670,'Dominican Republic Mobile'),(1809671,'Dominican Republic Mobile'),(1809672,'Dominican Republic Mobile'),(1809673,'Dominican Republic Mobile'),(1809674,'Dominican Republic Mobile'),(1809675,'Dominican Republic Mobile'),(1809676,'Dominican Republic Mobile'),(1809677,'Dominican Republic Mobile'),(1809678,'Dominican Republic Mobile'),(1809693,'Dominican Republic Mobile'),(1809694,'Dominican Republic Mobile'),(1809696,'Dominican Republic Mobile'),(1809697,'Dominican Republic Mobile'),(1809698,'Dominican Republic Mobile'),(1809702,'Dominican Republic Mobile'),(1809703,'Dominican Republic Mobile'),(1809704,'Dominican Republic Mobile'),(1809705,'Dominican Republic Mobile'),(1809706,'Dominican Republic Mobile'),(1809707,'Dominican Republic Mobile'),(1809708,'Dominican Republic Mobile'),(1809709,'Dominican Republic Mobile'),(1809710,'Dominican Republic Mobile'),(1809712,'Dominican Republic Mobile'),(1809713,'Dominican Republic Mobile'),(1809714,'Dominican Republic Mobile'),(1809715,'Dominican Republic Mobile'),(1809716,'Dominican Republic Mobile'),(1809717,'Dominican Republic Mobile'),(1809718,'Dominican Republic Mobile'),(1809719,'Dominican Republic Mobile'),(1809720,'Dominican Republic Mobile'),(1809721,'Dominican Republic Mobile'),(1809722,'Dominican Republic Mobile'),(1809723,'Dominican Republic Mobile'),(1809727,'Dominican Republic Mobile'),(1809729,'Dominican Republic Mobile'),(18097421,'Dominican Republic Mobile'),(18097422,'Dominican Republic Mobile'),(1809743,'Dominican Republic Mobile'),(1809747,'Dominican Republic Mobile'),(1809749,'Dominican Republic Mobile'),(1809750,'Dominican Republic Mobile'),(1809751,'Dominican Republic Mobile'),(1809752,'Dominican Republic Mobile'),(1809753,'Dominican Republic Mobile'),(1809754,'Dominican Republic Mobile'),(1809756,'Dominican Republic Mobile'),(1809757,'Dominican Republic Mobile'),(1809758,'Dominican Republic Mobile'),(1809759,'Dominican Republic Mobile'),(1809760,'Dominican Republic Mobile'),(1809761,'Dominican Republic Mobile'),(1809762,'Dominican Republic Mobile'),(1809763,'Dominican Republic Mobile'),(1809764,'Dominican Republic Mobile'),(1809765,'Dominican Republic Mobile'),(1809767,'Dominican Republic Mobile'),(1809768,'Dominican Republic Mobile'),(1809769,'Dominican Republic Mobile'),(1809771,'Dominican Republic Mobile'),(1809772,'Dominican Republic Mobile'),(1809773,'Dominican Republic Mobile'),(1809774,'Dominican Republic Mobile'),(1809775,'Dominican Republic Mobile'),(1809776,'Dominican Republic Mobile'),(1809777,'Dominican Republic Mobile'),(1809778,'Dominican Republic Mobile'),(1809779,'Dominican Republic Mobile'),(1809780,'Dominican Republic Mobile'),(1809781,'Dominican Republic Mobile'),(1809782,'Dominican Republic Mobile'),(1809783,'Dominican Republic Mobile'),(1809785,'Dominican Republic Mobile'),(1809786,'Dominican Republic Mobile'),(1809787,'Dominican Republic Mobile'),(1809789,'Dominican Republic Mobile'),(1809790,'Dominican Republic Mobile'),(1809791,'Dominican Republic Mobile'),(1809796,'Dominican Republic Mobile'),(1809798,'Dominican Republic Mobile'),(1809801,'Dominican Republic Mobile'),(1809802,'Dominican Republic Mobile'),(1809803,'Dominican Republic Mobile'),(1809804,'Dominican Republic Mobile'),(1809805,'Dominican Republic Mobile'),(1809806,'Dominican Republic Mobile'),(1809807,'Dominican Republic Mobile'),(1809808,'Dominican Republic Mobile'),(1809810,'Dominican Republic Mobile'),(1809812,'Dominican Republic Mobile'),(1809814,'Dominican Republic Mobile'),(1809815,'Dominican Republic Mobile'),(1809816,'Dominican Republic Mobile'),(1809817,'Dominican Republic Mobile'),(1809818,'Dominican Republic Mobile'),(1809819,'Dominican Republic Mobile'),(1809820,'Dominican Republic Mobile'),(1809821,'Dominican Republic Mobile'),(1809827,'Dominican Republic Mobile'),(1809828,'Dominican Republic Mobile'),(1809829,'Dominican Republic Mobile'),(1809834,'Dominican Republic Mobile'),(1809835,'Dominican Republic Mobile'),(1809836,'Dominican Republic Mobile'),(1809837,'Dominican Republic Mobile'),(1809838,'Dominican Republic Mobile'),(1809839,'Dominican Republic Mobile'),(1809840,'Dominican Republic Mobile'),(1809841,'Dominican Republic Mobile'),(1809842,'Dominican Republic Mobile'),(1809843,'Dominican Republic Mobile'),(1809844,'Dominican Republic Mobile'),(1809845,'Dominican Republic Mobile'),(1809846,'Dominican Republic Mobile'),(1809847,'Dominican Republic Mobile'),(1809848,'Dominican Republic Mobile'),(1809849,'Dominican Republic Mobile'),(1809850,'Dominican Republic Mobile'),(1809851,'Dominican Republic Mobile'),(1809852,'Dominican Republic Mobile'),(1809853,'Dominican Republic Mobile'),(1809854,'Dominican Republic Mobile'),(1809855,'Dominican Republic Mobile'),(1809856,'Dominican Republic Mobile'),(1809857,'Dominican Republic Mobile'),(1809858,'Dominican Republic Mobile'),(18098597,'Dominican Republic Mobile'),(18098598,'Dominican Republic Mobile'),(180985990,'Dominican Republic Mobile'),(180985991,'Dominican Republic Mobile'),(180985992,'Dominican Republic Mobile'),(180985993,'Dominican Republic Mobile'),(180985994,'Dominican Republic Mobile'),(180985995,'Dominican Republic Mobile'),(180985996,'Dominican Republic Mobile'),(180985997,'Dominican Republic Mobile'),(1809860,'Dominican Republic Mobile'),(1809861,'Dominican Republic Mobile'),(1809862,'Dominican Republic Mobile'),(1809863,'Dominican Republic Mobile'),(1809864,'Dominican Republic Mobile'),(1809865,'Dominican Republic Mobile'),(1809866,'Dominican Republic Mobile'),(1809867,'Dominican Republic Mobile'),(1809868,'Dominican Republic Mobile'),(1809869,'Dominican Republic Mobile'),(1809871,'Dominican Republic Mobile'),(1809873,'Dominican Republic Mobile'),(1809874,'Dominican Republic Mobile'),(1809875,'Dominican Republic Mobile'),(1809876,'Dominican Republic Mobile'),(1809877,'Dominican Republic Mobile'),(1809878,'Dominican Republic Mobile'),(1809879,'Dominican Republic Mobile'),(1809880,'Dominican Republic Mobile'),(1809881,'Dominican Republic Mobile'),(1809882,'Dominican Republic Mobile'),(1809883,'Dominican Republic Mobile'),(1809884,'Dominican Republic Mobile'),(1809885,'Dominican Republic Mobile'),(1809886,'Dominican Republic Mobile'),(1809888,'Dominican Republic Mobile'),(1809889,'Dominican Republic Mobile'),(1809890,'Dominican Republic Mobile'),(1809891,'Dominican Republic Mobile'),(1809899,'Dominican Republic Mobile'),(1809901,'Dominican Republic Mobile'),(1809902,'Dominican Republic Mobile'),(1809903,'Dominican Republic Mobile'),(1809904,'Dominican Republic Mobile'),(1809905,'Dominican Republic Mobile'),(1809906,'Dominican Republic Mobile'),(1809907,'Dominican Republic Mobile'),(1809908,'Dominican Republic Mobile'),(1809909,'Dominican Republic Mobile'),(1809910,'Dominican Republic Mobile'),(1809912,'Dominican Republic Mobile'),(1809913,'Dominican Republic Mobile'),(1809914,'Dominican Republic Mobile'),(1809915,'Dominican Republic Mobile'),(1809916,'Dominican Republic Mobile'),(1809917,'Dominican Republic Mobile'),(1809918,'Dominican Republic Mobile'),(1809919,'Dominican Republic Mobile'),(1809923,'Dominican Republic Mobile'),(1809924,'Dominican Republic Mobile'),(1809928,'Dominican Republic Mobile'),(1809929,'Dominican Republic Mobile'),(1809931,'Dominican Republic Mobile'),(1809932,'Dominican Republic Mobile'),(1809935,'Dominican Republic Mobile'),(1809938,'Dominican Republic Mobile'),(1809939,'Dominican Republic Mobile'),(1809940,'Dominican Republic Mobile'),(1809941,'Dominican Republic Mobile'),(1809942,'Dominican Republic Mobile'),(1809943,'Dominican Republic Mobile'),(1809944,'Dominican Republic Mobile'),(1809945,'Dominican Republic Mobile'),(1809946,'Dominican Republic Mobile'),(1809949,'Dominican Republic Mobile'),(1809952,'Dominican Republic Mobile'),(1809953,'Dominican Republic Mobile'),(1809956,'Dominican Republic Mobile'),(1809958,'Dominican Republic Mobile'),(1809961,'Dominican Republic Mobile'),(1809962,'Dominican Republic Mobile'),(1809963,'Dominican Republic Mobile'),(1809964,'Dominican Republic Mobile'),(1809965,'Dominican Republic Mobile'),(1809966,'Dominican Republic Mobile'),(1809967,'Dominican Republic Mobile'),(1809968,'Dominican Republic Mobile'),(1809969,'Dominican Republic Mobile'),(1809972,'Dominican Republic Mobile'),(1809973,'Dominican Republic Mobile'),(1809974,'Dominican Republic Mobile'),(1809975,'Dominican Republic Mobile'),(1809977,'Dominican Republic Mobile'),(1809978,'Dominican Republic Mobile'),(1809979,'Dominican Republic Mobile'),(1809980,'Dominican Republic Mobile'),(1809981,'Dominican Republic Mobile'),(1809982,'Dominican Republic Mobile'),(1809983,'Dominican Republic Mobile'),(1809984,'Dominican Republic Mobile'),(1809986,'Dominican Republic Mobile'),(1809988,'Dominican Republic Mobile'),(1809989,'Dominican Republic Mobile'),(1809990,'Dominican Republic Mobile'),(1809991,'Dominican Republic Mobile'),(1809992,'Dominican Republic Mobile'),(1809993,'Dominican Republic Mobile'),(1809994,'Dominican Republic Mobile'),(1809995,'Dominican Republic Mobile'),(1809996,'Dominican Republic Mobile'),(1809997,'Dominican Republic Mobile'),(1809998,'Dominican Republic Mobile'),(1809999,'Dominican Republic Mobile'),(1829201,'Dominican Republic Mobile'),(1829202,'Dominican Republic Mobile'),(1829203,'Dominican Republic Mobile'),(1829204,'Dominican Republic Mobile'),(1829205,'Dominican Republic Mobile'),(1829206,'Dominican Republic Mobile'),(1829207,'Dominican Republic Mobile'),(1829208,'Dominican Republic Mobile'),(1829209,'Dominican Republic Mobile'),(1829210,'Dominican Republic Mobile'),(1829212,'Dominican Republic Mobile'),(1829214,'Dominican Republic Mobile'),(1829215,'Dominican Republic Mobile'),(1829221,'Dominican Republic Mobile'),(1829222,'Dominican Republic Mobile'),(1829230,'Dominican Republic Mobile'),(1829232,'Dominican Republic Mobile'),(1829233,'Dominican Republic Mobile'),(1829248,'Dominican Republic Mobile'),(1829250,'Dominican Republic Mobile'),(1829252,'Dominican Republic Mobile'),(1829255,'Dominican Republic Mobile'),(1829257,'Dominican Republic Mobile'),(1829258,'Dominican Republic Mobile'),(1829259,'Dominican Republic Mobile'),(1829260,'Dominican Republic Mobile'),(1829261,'Dominican Republic Mobile'),(1829262,'Dominican Republic Mobile'),(1829263,'Dominican Republic Mobile'),(1829264,'Dominican Republic Mobile'),(1829265,'Dominican Republic Mobile'),(1829266,'Dominican Republic Mobile'),(1829267,'Dominican Republic Mobile'),(1829268,'Dominican Republic Mobile'),(1829269,'Dominican Republic Mobile'),(1829270,'Dominican Republic Mobile'),(1829271,'Dominican Republic Mobile'),(1829272,'Dominican Republic Mobile'),(1829273,'Dominican Republic Mobile'),(1829274,'Dominican Republic Mobile'),(1829275,'Dominican Republic Mobile'),(1829276,'Dominican Republic Mobile'),(1829277,'Dominican Republic Mobile'),(1829278,'Dominican Republic Mobile'),(1829279,'Dominican Republic Mobile'),(1829280,'Dominican Republic Mobile'),(1829281,'Dominican Republic Mobile'),(1829282,'Dominican Republic Mobile'),(1829283,'Dominican Republic Mobile'),(1829284,'Dominican Republic Mobile'),(1829285,'Dominican Republic Mobile'),(1829286,'Dominican Republic Mobile'),(1829287,'Dominican Republic Mobile'),(1829288,'Dominican Republic Mobile'),(1829290,'Dominican Republic Mobile'),(1829296,'Dominican Republic Mobile'),(1829297,'Dominican Republic Mobile'),(1829298,'Dominican Republic Mobile'),(1829299,'Dominican Republic Mobile'),(1829301,'Dominican Republic Mobile'),(1829303,'Dominican Republic Mobile'),(1829304,'Dominican Republic Mobile'),(1829305,'Dominican Republic Mobile'),(1829306,'Dominican Republic Mobile'),(1829307,'Dominican Republic Mobile'),(1829308,'Dominican Republic Mobile'),(1829309,'Dominican Republic Mobile'),(1829313,'Dominican Republic Mobile'),(1829314,'Dominican Republic Mobile'),(1829315,'Dominican Republic Mobile'),(1829316,'Dominican Republic Mobile'),(1829317,'Dominican Republic Mobile'),(1829318,'Dominican Republic Mobile'),(1829319,'Dominican Republic Mobile'),(1829320,'Dominican Republic Mobile'),(1829321,'Dominican Republic Mobile'),(1829322,'Dominican Republic Mobile'),(1829323,'Dominican Republic Mobile'),(1829328,'Dominican Republic Mobile'),(1829329,'Dominican Republic Mobile'),(1829330,'Dominican Republic Mobile'),(1829331,'Dominican Republic Mobile'),(1829332,'Dominican Republic Mobile'),(1829333,'Dominican Republic Mobile'),(1829334,'Dominican Republic Mobile'),(1829335,'Dominican Republic Mobile'),(1829336,'Dominican Republic Mobile'),(1829337,'Dominican Republic Mobile'),(1829338,'Dominican Republic Mobile'),(1829339,'Dominican Republic Mobile'),(1829340,'Dominican Republic Mobile'),(1829341,'Dominican Republic Mobile'),(1829342,'Dominican Republic Mobile'),(1829343,'Dominican Republic Mobile'),(1829344,'Dominican Republic Mobile'),(1829345,'Dominican Republic Mobile'),(1829346,'Dominican Republic Mobile'),(1829347,'Dominican Republic Mobile'),(1829348,'Dominican Republic Mobile'),(1829349,'Dominican Republic Mobile'),(1829350,'Dominican Republic Mobile'),(1829351,'Dominican Republic Mobile'),(1829352,'Dominican Republic Mobile'),(1829353,'Dominican Republic Mobile'),(1829354,'Dominican Republic Mobile'),(1829355,'Dominican Republic Mobile'),(1829356,'Dominican Republic Mobile'),(1829357,'Dominican Republic Mobile'),(1829358,'Dominican Republic Mobile'),(1829359,'Dominican Republic Mobile'),(1829360,'Dominican Republic Mobile'),(1829361,'Dominican Republic Mobile'),(1829362,'Dominican Republic Mobile'),(1829363,'Dominican Republic Mobile'),(1829364,'Dominican Republic Mobile'),(1829365,'Dominican Republic Mobile'),(1829366,'Dominican Republic Mobile'),(1829367,'Dominican Republic Mobile'),(1829368,'Dominican Republic Mobile'),(1829369,'Dominican Republic Mobile'),(1829370,'Dominican Republic Mobile'),(1829371,'Dominican Republic Mobile'),(1829372,'Dominican Republic Mobile'),(1829373,'Dominican Republic Mobile'),(1829375,'Dominican Republic Mobile'),(1829376,'Dominican Republic Mobile'),(1829377,'Dominican Republic Mobile'),(1829379,'Dominican Republic Mobile'),(1829380,'Dominican Republic Mobile'),(1829383,'Dominican Republic Mobile'),(1829386,'Dominican Republic Mobile'),(1829387,'Dominican Republic Mobile'),(1829388,'Dominican Republic Mobile'),(1829389,'Dominican Republic Mobile'),(1829390,'Dominican Republic Mobile'),(1829392,'Dominican Republic Mobile'),(1829393,'Dominican Republic Mobile'),(1829394,'Dominican Republic Mobile'),(1829395,'Dominican Republic Mobile'),(1829396,'Dominican Republic Mobile'),(1829397,'Dominican Republic Mobile'),(1829398,'Dominican Republic Mobile'),(1829399,'Dominican Republic Mobile'),(1829401,'Dominican Republic Mobile'),(1829402,'Dominican Republic Mobile'),(1829403,'Dominican Republic Mobile'),(1829404,'Dominican Republic Mobile'),(1829405,'Dominican Republic Mobile'),(1829406,'Dominican Republic Mobile'),(1829407,'Dominican Republic Mobile'),(1829408,'Dominican Republic Mobile'),(1829409,'Dominican Republic Mobile'),(1829410,'Dominican Republic Mobile'),(1829412,'Dominican Republic Mobile'),(1829413,'Dominican Republic Mobile'),(1829414,'Dominican Republic Mobile'),(1829415,'Dominican Republic Mobile'),(1829416,'Dominican Republic Mobile'),(1829417,'Dominican Republic Mobile'),(1829422,'Dominican Republic Mobile'),(1829424,'Dominican Republic Mobile'),(1829425,'Dominican Republic Mobile'),(1829426,'Dominican Republic Mobile'),(1829427,'Dominican Republic Mobile'),(1829428,'Dominican Republic Mobile'),(1829429,'Dominican Republic Mobile'),(1829430,'Dominican Republic Mobile'),(1829432,'Dominican Republic Mobile'),(1829440,'Dominican Republic Mobile'),(1829441,'Dominican Republic Mobile'),(1829442,'Dominican Republic Mobile'),(1829443,'Dominican Republic Mobile'),(1829444,'Dominican Republic Mobile'),(1829445,'Dominican Republic Mobile'),(1829446,'Dominican Republic Mobile'),(1829447,'Dominican Republic Mobile'),(1829448,'Dominican Republic Mobile'),(1829449,'Dominican Republic Mobile'),(1829450,'Dominican Republic Mobile'),(1829451,'Dominican Republic Mobile'),(1829452,'Dominican Republic Mobile'),(1829453,'Dominican Republic Mobile'),(1829454,'Dominican Republic Mobile'),(1829456,'Dominican Republic Mobile'),(1829465,'Dominican Republic Mobile'),(1829470,'Dominican Republic Mobile'),(1829471,'Dominican Republic Mobile'),(1829472,'Dominican Republic Mobile'),(1829474,'Dominican Republic Mobile'),(1829543,'Dominican Republic Mobile'),(1829601,'Dominican Republic Mobile'),(1829602,'Dominican Republic Mobile'),(1829603,'Dominican Republic Mobile'),(1829604,'Dominican Republic Mobile'),(1829605,'Dominican Republic Mobile'),(1829610,'Dominican Republic Mobile'),(1829613,'Dominican Republic Mobile'),(1829616,'Dominican Republic Mobile'),(1829630,'Dominican Republic Mobile'),(1829633,'Dominican Republic Mobile'),(1829640,'Dominican Republic Mobile'),(1829644,'Dominican Republic Mobile'),(1829646,'Dominican Republic Mobile'),(1829650,'Dominican Republic Mobile'),(1829653,'Dominican Republic Mobile'),(1829654,'Dominican Republic Mobile'),(1829655,'Dominican Republic Mobile'),(1829657,'Dominican Republic Mobile'),(1829660,'Dominican Republic Mobile'),(1829661,'Dominican Republic Mobile'),(1829662,'Dominican Republic Mobile'),(1829663,'Dominican Republic Mobile'),(1829664,'Dominican Republic Mobile'),(1829665,'Dominican Republic Mobile'),(1829667,'Dominican Republic Mobile'),(1829668,'Dominican Republic Mobile'),(1829669,'Dominican Republic Mobile'),(1829676,'Dominican Republic Mobile'),(1829677,'Dominican Republic Mobile'),(1829678,'Dominican Republic Mobile'),(1829686,'Dominican Republic Mobile'),(1829696,'Dominican Republic Mobile'),(1829697,'Dominican Republic Mobile'),(1829699,'Dominican Republic Mobile'),(1829701,'Dominican Republic Mobile'),(1829702,'Dominican Republic Mobile'),(1829703,'Dominican Republic Mobile'),(1829704,'Dominican Republic Mobile'),(1829705,'Dominican Republic Mobile'),(1829706,'Dominican Republic Mobile'),(1829707,'Dominican Republic Mobile'),(1829709,'Dominican Republic Mobile'),(1829710,'Dominican Republic Mobile'),(1829712,'Dominican Republic Mobile'),(1829713,'Dominican Republic Mobile'),(1829714,'Dominican Republic Mobile'),(1829715,'Dominican Republic Mobile'),(1829716,'Dominican Republic Mobile'),(1829717,'Dominican Republic Mobile'),(1829718,'Dominican Republic Mobile'),(1829719,'Dominican Republic Mobile'),(1829720,'Dominican Republic Mobile'),(1829721,'Dominican Republic Mobile'),(1829722,'Dominican Republic Mobile'),(1829723,'Dominican Republic Mobile'),(1829725,'Dominican Republic Mobile'),(1829726,'Dominican Republic Mobile'),(1829727,'Dominican Republic Mobile'),(1829728,'Dominican Republic Mobile'),(1829729,'Dominican Republic Mobile'),(1829730,'Dominican Republic Mobile'),(1829731,'Dominican Republic Mobile'),(1829740,'Dominican Republic Mobile'),(1829744,'Dominican Republic Mobile'),(1829747,'Dominican Republic Mobile'),(1829750,'Dominican Republic Mobile'),(1829754,'Dominican Republic Mobile'),(1829755,'Dominican Republic Mobile'),(1829757,'Dominican Republic Mobile'),(1829760,'Dominican Republic Mobile'),(1829766,'Dominican Republic Mobile'),(1829770,'Dominican Republic Mobile'),(1829777,'Dominican Republic Mobile'),(1829779,'Dominican Republic Mobile'),(1829780,'Dominican Republic Mobile'),(1829787,'Dominican Republic Mobile'),(1829788,'Dominican Republic Mobile'),(1829790,'Dominican Republic Mobile'),(1829797,'Dominican Republic Mobile'),(1829799,'Dominican Republic Mobile'),(1829801,'Dominican Republic Mobile'),(1829802,'Dominican Republic Mobile'),(1829803,'Dominican Republic Mobile'),(1829804,'Dominican Republic Mobile'),(1829805,'Dominican Republic Mobile'),(1829806,'Dominican Republic Mobile'),(1829807,'Dominican Republic Mobile'),(1829808,'Dominican Republic Mobile'),(1829810,'Dominican Republic Mobile'),(1829815,'Dominican Republic Mobile'),(1829816,'Dominican Republic Mobile'),(1829817,'Dominican Republic Mobile'),(1829818,'Dominican Republic Mobile'),(1829819,'Dominican Republic Mobile'),(1829820,'Dominican Republic Mobile'),(1829826,'Dominican Republic Mobile'),(1829830,'Dominican Republic Mobile'),(1829838,'Dominican Republic Mobile'),(1829845,'Dominican Republic Mobile'),(1829846,'Dominican Republic Mobile'),(1829847,'Dominican Republic Mobile'),(1829848,'Dominican Republic Mobile'),(1829849,'Dominican Republic Mobile'),(1829850,'Dominican Republic Mobile'),(1829851,'Dominican Republic Mobile'),(1829852,'Dominican Republic Mobile'),(1829853,'Dominican Republic Mobile'),(1829854,'Dominican Republic Mobile'),(1829855,'Dominican Republic Mobile'),(1829856,'Dominican Republic Mobile'),(1829857,'Dominican Republic Mobile'),(1829858,'Dominican Republic Mobile'),(1829859,'Dominican Republic Mobile'),(1829860,'Dominican Republic Mobile'),(1829861,'Dominican Republic Mobile'),(1829862,'Dominican Republic Mobile'),(1829863,'Dominican Republic Mobile'),(1829864,'Dominican Republic Mobile'),(1829865,'Dominican Republic Mobile'),(1829866,'Dominican Republic Mobile'),(1829867,'Dominican Republic Mobile'),(1829868,'Dominican Republic Mobile'),(1829869,'Dominican Republic Mobile'),(1829870,'Dominican Republic Mobile'),(1829873,'Dominican Republic Mobile'),(1829875,'Dominican Republic Mobile'),(1829876,'Dominican Republic Mobile'),(1829877,'Dominican Republic Mobile'),(1829878,'Dominican Republic Mobile'),(1829879,'Dominican Republic Mobile'),(1829880,'Dominican Republic Mobile'),(1829881,'Dominican Republic Mobile'),(1829882,'Dominican Republic Mobile'),(1829883,'Dominican Republic Mobile'),(1829884,'Dominican Republic Mobile'),(1829885,'Dominican Republic Mobile'),(1829886,'Dominican Republic Mobile'),(1829887,'Dominican Republic Mobile'),(1829889,'Dominican Republic Mobile'),(1829890,'Dominican Republic Mobile'),(1829891,'Dominican Republic Mobile'),(1829892,'Dominican Republic Mobile'),(1829898,'Dominican Republic Mobile'),(1829899,'Dominican Republic Mobile'),(1829901,'Dominican Republic Mobile'),(1829902,'Dominican Republic Mobile'),(1829903,'Dominican Republic Mobile'),(1829904,'Dominican Republic Mobile'),(1829905,'Dominican Republic Mobile'),(1829906,'Dominican Republic Mobile'),(1829907,'Dominican Republic Mobile'),(1829908,'Dominican Republic Mobile'),(1829909,'Dominican Republic Mobile'),(1829910,'Dominican Republic Mobile'),(1829912,'Dominican Republic Mobile'),(1829913,'Dominican Republic Mobile'),(1829914,'Dominican Republic Mobile'),(1829915,'Dominican Republic Mobile'),(1829916,'Dominican Republic Mobile'),(1829917,'Dominican Republic Mobile'),(1829918,'Dominican Republic Mobile'),(1829919,'Dominican Republic Mobile'),(1829920,'Dominican Republic Mobile'),(1829921,'Dominican Republic Mobile'),(1829922,'Dominican Republic Mobile'),(1829923,'Dominican Republic Mobile'),(1829924,'Dominican Republic Mobile'),(1829925,'Dominican Republic Mobile'),(1829926,'Dominican Republic Mobile'),(1829929,'Dominican Republic Mobile'),(1829930,'Dominican Republic Mobile'),(1829931,'Dominican Republic Mobile'),(1829933,'Dominican Republic Mobile'),(1829935,'Dominican Republic Mobile'),(1829939,'Dominican Republic Mobile'),(1829958,'Dominican Republic Mobile'),(1829961,'Dominican Republic Mobile'),(1829962,'Dominican Republic Mobile'),(1829963,'Dominican Republic Mobile'),(1829964,'Dominican Republic Mobile'),(1829965,'Dominican Republic Mobile'),(1829966,'Dominican Republic Mobile'),(1829967,'Dominican Republic Mobile'),(1829968,'Dominican Republic Mobile'),(1829969,'Dominican Republic Mobile'),(1829970,'Dominican Republic Mobile'),(1829972,'Dominican Republic Mobile'),(1829973,'Dominican Republic Mobile'),(1829974,'Dominican Republic Mobile'),(1829975,'Dominican Republic Mobile'),(1829977,'Dominican Republic Mobile'),(1829978,'Dominican Republic Mobile'),(1829979,'Dominican Republic Mobile'),(1829980,'Dominican Republic Mobile'),(1829981,'Dominican Republic Mobile'),(1829982,'Dominican Republic Mobile'),(1829983,'Dominican Republic Mobile'),(1829984,'Dominican Republic Mobile'),(1829986,'Dominican Republic Mobile'),(1829990,'Dominican Republic Mobile'),(1829991,'Dominican Republic Mobile'),(1829993,'Dominican Republic Mobile'),(1829994,'Dominican Republic Mobile'),(670,'East Timor'),(67071,'East Timor Mobile'),(67072,'East Timor Mobile'),(67073,'East Timor Mobile'),(67079,'East Timor Mobile'),(593,'Ecuador'),(5938,'Ecuador Mobile'),(5939,'Ecuador Mobile'),(20,'Egypt'),(2010,'Egypt Mobile'),(2011,'Egypt Mobile'),(2012,'Egypt Mobile'),(2016,'Egypt Mobile'),(2017,'Egypt Mobile'),(2018,'Egypt Mobile'),(2019,'Egypt Mobile'),(503,'El Salvador'),(5037,'El Salvador Mobile'),(5038,'El Salvador Mobile'),(240,'Equatorial Guinea'),(2402,'Equatorial Guinea Mobile'),(2405,'Equatorial Guinea Mobile'),(2406,'Equatorial Guinea Mobile'),(291,'Eritrea'),(291171,'Eritrea Mobile'),(291172,'Eritrea Mobile'),(291173,'Eritrea Mobile'),(2917,'Eritrea Mobile'),(372,'Estonia'),(3725,'Estonia Mobile'),(251,'Ethiopia'),(25191,'Ethiopia Mobile'),(251958,'Ethiopia Mobile'),(251959,'Ethiopia Mobile'),(25198,'Ethiopia Mobile'),(298,'Faeroes Islands'),(29821,'Faeroes Islands Mobile'),(29822,'Faeroes Islands Mobile'),(29823,'Faeroes Islands Mobile'),(29824,'Faeroes Islands Mobile'),(29825,'Faeroes Islands Mobile'),(29826,'Faeroes Islands Mobile'),(29827,'Faeroes Islands Mobile'),(29828,'Faeroes Islands Mobile'),(29829,'Faeroes Islands Mobile'),(2985,'Faeroes Islands Mobile'),(29871,'Faeroes Islands Mobile'),(29872,'Faeroes Islands Mobile'),(29873,'Faeroes Islands Mobile'),(29874,'Faeroes Islands Mobile'),(29875,'Faeroes Islands Mobile'),(29876,'Faeroes Islands Mobile'),(29877,'Faeroes Islands Mobile'),(29879,'Faeroes Islands Mobile'),(29891,'Faeroes Islands Mobile'),(29892,'Faeroes Islands Mobile'),(29893,'Faeroes Islands Mobile'),(29894,'Faeroes Islands Mobile'),(29895,'Faeroes Islands Mobile'),(29896,'Faeroes Islands Mobile'),(29897,'Faeroes Islands Mobile'),(29898,'Faeroes Islands Mobile'),(29899,'Faeroes Islands Mobile'),(5,'Falkland Islands'),(679,'Fiji'),(67970,'Fiji Mobile'),(67971,'Fiji Mobile'),(67972,'Fiji Mobile'),(67973,'Fiji Mobile'),(67974,'Fiji Mobile'),(67983,'Fiji Mobile'),(67984,'Fiji Mobile'),(6799,'Fiji Mobile'),(358,'Finland'),(3584,'Finland Mobile'),(35850,'Finland Mobile'),(33,'France'),(33650,'France [Bouygues Telecom]'),(33653,'France [Bouygues Telecom]'),(33659,'France [Bouygues Telecom]'),(33660,'France [Bouygues Telecom]'),(33661,'France [Bouygues Telecom]'),(33662,'France [Bouygues Telecom]'),(33663,'France [Bouygues Telecom]'),(33664,'France [Bouygues Telecom]'),(33665,'France [Bouygues Telecom]'),(33666,'France [Bouygues Telecom]'),(33667,'France [Bouygues Telecom]'),(33668,'France [Bouygues Telecom]'),(33669,'France [Bouygues Telecom]'),(33698,'France [Bouygues Telecom]'),(33699,'France [Bouygues Telecom]'),(33607,'France [Orange]'),(33608,'France [Orange]'),(33630,'France [Orange]'),(33631,'France [Orange]'),(33632,'France [Orange]'),(33633,'France [Orange]'),(33637,'France [Orange]'),(33642,'France [Orange]'),(33643,'France [Orange]'),(33645,'France [Orange]'),(33654,'France [Orange]'),(3367,'France [Orange]'),(33670,'France [Orange]'),(3368,'France [Orange]'),(33603,'France [SFR]'),(33605,'France [SFR]'),(33609,'France [SFR]'),(3361,'France [SFR]'),(3362,'France [SFR]'),(33620,'France [SFR]'),(33621,'France [SFR]'),(33622,'France [SFR]'),(33623,'France [SFR]'),(33624,'France [SFR]'),(33625,'France [SFR]'),(33626,'France [SFR]'),(33627,'France [SFR]'),(33628,'France [SFR]'),(33629,'France [SFR]'),(33634,'France [SFR]'),(33635,'France [SFR]'),(33636,'France [SFR]'),(33641,'France [SFR]'),(33655,'France [SFR]'),(336,'France Mobile'),(33594,'French Guiana'),(594,'French Guiana'),(594694,'French Guiana Mobile'),(689,'French Polynesia'),(6892,'French Polynesia Mobile'),(68930,'French Polynesia Mobile'),(68931,'French Polynesia Mobile'),(6897,'French Polynesia Mobile'),(241,'Gabon'),(24103,'Gabon Mobile'),(24104,'Gabon Mobile'),(24105,'Gabon Mobile'),(24106,'Gabon Mobile'),(24107,'Gabon Mobile'),(24108,'Gabon Mobile'),(24109,'Gabon Mobile'),(24110,'Gabon Mobile'),(24111,'Gabon Mobile'),(24114,'Gabon Mobile'),(24115,'Gabon Mobile'),(24120,'Gabon Mobile'),(24121,'Gabon Mobile'),(24122,'Gabon Mobile'),(24123,'Gabon Mobile'),(24124,'Gabon Mobile'),(24125,'Gabon Mobile'),(24126,'Gabon Mobile'),(24127,'Gabon Mobile'),(24128,'Gabon Mobile'),(24129,'Gabon Mobile'),(24130,'Gabon Mobile'),(24131,'Gabon Mobile'),(24132,'Gabon Mobile'),(24133,'Gabon Mobile'),(24134,'Gabon Mobile'),(24135,'Gabon Mobile'),(24136,'Gabon Mobile'),(24137,'Gabon Mobile'),(24138,'Gabon Mobile'),(24139,'Gabon Mobile'),(24141,'Gabon Mobile'),(24151,'Gabon Mobile'),(24152,'Gabon Mobile'),(24153,'Gabon Mobile'),(24157,'Gabon Mobile'),(24161,'Gabon Mobile'),(24163,'Gabon Mobile'),(24168,'Gabon Mobile'),(24175,'Gabon Mobile'),(24180,'Gabon Mobile'),(24181,'Gabon Mobile'),(24184,'Gabon Mobile'),(24185,'Gabon Mobile'),(24187,'Gabon Mobile'),(24189,'Gabon Mobile'),(24191,'Gabon Mobile'),(24194,'Gabon Mobile'),(24195,'Gabon Mobile'),(24197,'Gabon Mobile'),(220,'Gambia'),(2206,'Gambia Mobile'),(2207,'Gambia Mobile'),(2209,'Gambia Mobile'),(995,'Georgia'),(99555,'Georgia Mobile'),(99557,'Georgia Mobile'),(99558,'Georgia Mobile'),(99577,'Georgia Mobile'),(99590,'Georgia Mobile'),(99591,'Georgia Mobile'),(99593,'Georgia Mobile'),(99595,'Georgia Mobile'),(99597,'Georgia Mobile'),(99598,'Georgia Mobile'),(99599,'Georgia Mobile'),(49,'Germany'),(49150,'Germany Mobile'),(49151,'Germany Mobile'),(49152,'Germany Mobile'),(49155,'Germany Mobile'),(49156,'Germany Mobile'),(49157,'Germany Mobile'),(49159,'Germany Mobile'),(49160,'Germany Mobile'),(49162,'Germany Mobile'),(49163,'Germany Mobile'),(49170,'Germany Mobile'),(49171,'Germany Mobile'),(49172,'Germany Mobile'),(49173,'Germany Mobile'),(49174,'Germany Mobile'),(49175,'Germany Mobile'),(49176,'Germany Mobile'),(49177,'Germany Mobile'),(49178,'Germany Mobile'),(49179,'Germany Mobile'),(233,'Ghana'),(23320,'Ghana Mobile'),(2332170,'Ghana Mobile'),(2332260,'Ghana Mobile'),(23324,'Ghana Mobile'),(23327,'Ghana Mobile'),(23328,'Ghana Mobile'),(350,'Gibraltar'),(35054,'Gibraltar Mobile'),(35056,'Gibraltar Mobile'),(35057,'Gibraltar Mobile'),(35058,'Gibraltar Mobile'),(35060,'Gibraltar Mobile'),(30,'Greece'),(3069,'Greece Mobile'),(299,'Greenland'),(2992,'Greenland Mobile'),(29942,'Greenland Mobile'),(29946,'Greenland Mobile'),(29947,'Greenland Mobile'),(29948,'Greenland Mobile'),(29949,'Greenland Mobile'),(29950,'Greenland Mobile'),(29952,'Greenland Mobile'),(29953,'Greenland Mobile'),(29954,'Greenland Mobile'),(29955,'Greenland Mobile'),(29956,'Greenland Mobile'),(29957,'Greenland Mobile'),(29958,'Greenland Mobile'),(29959,'Greenland Mobile'),(1473,'Grenada'),(1473403,'Grenada Mobile'),(1473404,'Grenada Mobile'),(1473405,'Grenada Mobile'),(1473406,'Grenada Mobile'),(1473407,'Grenada Mobile'),(1473409,'Grenada Mobile'),(1473410,'Grenada Mobile'),(1473414,'Grenada Mobile'),(1473415,'Grenada Mobile'),(1473416,'Grenada Mobile'),(1473417,'Grenada Mobile'),(1473418,'Grenada Mobile'),(1473419,'Grenada Mobile'),(1473420,'Grenada Mobile'),(1473456,'Grenada Mobile'),(1473457,'Grenada Mobile'),(1473458,'Grenada Mobile'),(1473459,'Grenada Mobile'),(1473533,'Grenada Mobile'),(1473534,'Grenada Mobile'),(1473535,'Grenada Mobile'),(1473536,'Grenada Mobile'),(1473537,'Grenada Mobile'),(1473538,'Grenada Mobile'),(33590,'Guadeloupe'),(590,'Guadeloupe'),(590690,'Guadeloupe Mobile'),(1671,'Guam'),(502,'Guatemala'),(5024,'Guatemala Mobile'),(5025,'Guatemala Mobile'),(224,'Guinea'),(22460,'Guinea Mobile'),(22462,'Guinea Mobile'),(22463,'Guinea Mobile'),(22464,'Guinea Mobile'),(22465,'Guinea Mobile'),(22467,'Guinea Mobile'),(245,'Guinea-Bissau'),(2455,'Guinea-Bissau Mobile'),(2456,'Guinea-Bissau Mobile'),(2457,'Guinea-Bissau Mobile'),(592,'Guyana'),(592214,'Guyana Mobile'),(592224,'Guyana Mobile'),(592248,'Guyana Mobile'),(592278,'Guyana Mobile'),(592284,'Guyana Mobile'),(592294,'Guyana Mobile'),(592304,'Guyana Mobile'),(592374,'Guyana Mobile'),(592384,'Guyana Mobile'),(592394,'Guyana Mobile'),(5926,'Guyana Mobile'),(592601,'Guyana Mobile'),(592602,'Guyana Mobile'),(592603,'Guyana Mobile'),(592604,'Guyana Mobile'),(592609,'Guyana Mobile'),(592610,'Guyana Mobile'),(592611,'Guyana Mobile'),(592612,'Guyana Mobile'),(592613,'Guyana Mobile'),(592614,'Guyana Mobile'),(592616,'Guyana Mobile'),(592617,'Guyana Mobile'),(59262,'Guyana Mobile'),(592630,'Guyana Mobile'),(592633,'Guyana Mobile'),(592634,'Guyana Mobile'),(592635,'Guyana Mobile'),(592638,'Guyana Mobile'),(592639,'Guyana Mobile'),(59264,'Guyana Mobile'),(592650,'Guyana Mobile'),(592651,'Guyana Mobile'),(592652,'Guyana Mobile'),(592653,'Guyana Mobile'),(592654,'Guyana Mobile'),(592655,'Guyana Mobile'),(592656,'Guyana Mobile'),(592657,'Guyana Mobile'),(592658,'Guyana Mobile'),(59266,'Guyana Mobile'),(59267,'Guyana Mobile'),(59268,'Guyana Mobile'),(59269,'Guyana Mobile'),(5928,'Guyana Mobile'),(509,'Haiti'),(5093,'Haiti Mobile'),(5094,'Haiti Mobile'),(509561,'Haiti Mobile'),(509562,'Haiti Mobile'),(509563,'Haiti Mobile'),(509564,'Haiti Mobile'),(509565,'Haiti Mobile'),(5096,'Haiti Mobile'),(5097,'Haiti Mobile'),(50981,'Haiti Mobile'),(50982,'Haiti Mobile'),(50983,'Haiti Mobile'),(50990,'Haiti Mobile'),(50991,'Haiti Mobile'),(50992,'Haiti Mobile'),(50993,'Haiti Mobile'),(50994,'Haiti Mobile'),(504,'Honduras'),(5043,'Honduras Mobile'),(5047214,'Honduras Mobile'),(5047215,'Honduras Mobile'),(5047217,'Honduras Mobile'),(504881,'Honduras Mobile'),(504882,'Honduras Mobile'),(504883,'Honduras Mobile'),(504884,'Honduras Mobile'),(504885,'Honduras Mobile'),(504886,'Honduras Mobile'),(504887,'Honduras Mobile'),(504888,'Honduras Mobile'),(504889,'Honduras Mobile'),(504890,'Honduras Mobile'),(504891,'Honduras Mobile'),(504892,'Honduras Mobile'),(504893,'Honduras Mobile'),(504894,'Honduras Mobile'),(504895,'Honduras Mobile'),(504896,'Honduras Mobile'),(504897,'Honduras Mobile'),(504898,'Honduras Mobile'),(504899,'Honduras Mobile'),(5049,'Honduras Mobile'),(852,'Hong Kong'),(85217,'Hong Kong Mobile'),(85251,'Hong Kong Mobile'),(85253,'Hong Kong Mobile'),(85254,'Hong Kong Mobile'),(85256,'Hong Kong Mobile'),(85259,'Hong Kong Mobile'),(8526,'Hong Kong Mobile'),(8529,'Hong Kong Mobile'),(36,'Hungary'),(3620,'Hungary Mobile'),(3630,'Hungary Mobile'),(3650,'Hungary Mobile'),(3660,'Hungary Mobile'),(3670,'Hungary Mobile'),(354,'Iceland'),(354373,'Iceland Mobile'),(354374,'Iceland Mobile'),(354380,'Iceland Mobile'),(354388,'Iceland Mobile'),(354389,'Iceland Mobile'),(354610,'Iceland Mobile'),(354615,'Iceland Mobile'),(354616,'Iceland Mobile'),(354617,'Iceland Mobile'),(35462,'Iceland Mobile'),(354630,'Iceland Mobile'),(354631,'Iceland Mobile'),(354632,'Iceland Mobile'),(354637,'Iceland Mobile'),(354638,'Iceland Mobile'),(354639,'Iceland Mobile'),(354640,'Iceland Mobile'),(354641,'Iceland Mobile'),(354642,'Iceland Mobile'),(354649,'Iceland Mobile'),(354650,'Iceland Mobile'),(354652,'Iceland Mobile'),(354655,'Iceland Mobile'),(354659,'Iceland Mobile'),(35466,'Iceland Mobile'),(35467,'Iceland Mobile'),(35468,'Iceland Mobile'),(35469,'Iceland Mobile'),(354770,'Iceland Mobile'),(354771,'Iceland Mobile'),(354772,'Iceland Mobile'),(354773,'Iceland Mobile'),(35482,'Iceland Mobile'),(35483,'Iceland Mobile'),(35484,'Iceland Mobile'),(35485,'Iceland Mobile'),(35486,'Iceland Mobile'),(35487,'Iceland Mobile'),(35488,'Iceland Mobile'),(35489,'Iceland Mobile'),(354954,'Iceland Mobile'),(354958,'Iceland Mobile'),(91,'India'),(9190,'India Mobile'),(9191,'India Mobile'),(9192,'India Mobile'),(9193,'India Mobile'),(9194,'India Mobile'),(9196,'India Mobile'),(9197,'India Mobile'),(9198,'India Mobile'),(9199,'India Mobile'),(62,'Indonesia'),(628,'Indonesia Mobile'),(98,'Iran'),(989,'Iran Mobile'),(964,'Iraq'),(9647,'Iraq Mobile'),(353,'Ireland'),(353821,'Ireland Mobile'),(353822,'Ireland Mobile'),(35383,'Ireland Mobile'),(35385,'Ireland Mobile'),(35386,'Ireland Mobile'),(35387,'Ireland Mobile'),(35388,'Ireland Mobile'),(35389,'Ireland Mobile'),(972,'Israel'),(972151,'Israel Mobile'),(972153,'Israel Mobile'),(9725,'Israel Mobile'),(9726,'Israel Mobile'),(39,'Italy'),(393,'Italy Mobile'),(225,'Ivory Coast'),(22501,'Ivory Coast Mobile'),(22502,'Ivory Coast Mobile'),(22503,'Ivory Coast Mobile'),(22504,'Ivory Coast Mobile'),(22505,'Ivory Coast Mobile'),(22506,'Ivory Coast Mobile'),(22507,'Ivory Coast Mobile'),(22508,'Ivory Coast Mobile'),(22509,'Ivory Coast Mobile'),(22545,'Ivory Coast Mobile'),(22546,'Ivory Coast Mobile'),(22547,'Ivory Coast Mobile'),(22548,'Ivory Coast Mobile'),(22566,'Ivory Coast Mobile'),(22567,'Ivory Coast Mobile'),(1876,'Jamaica'),(1876210,'Jamaica Mobile'),(187629,'Jamaica Mobile'),(187630,'Jamaica Mobile'),(187631,'Jamaica Mobile'),(187632,'Jamaica Mobile'),(187633,'Jamaica Mobile'),(187634,'Jamaica Mobile'),(187635,'Jamaica Mobile'),(187636,'Jamaica Mobile'),(187637,'Jamaica Mobile'),(187638,'Jamaica Mobile'),(187639,'Jamaica Mobile'),(187640,'Jamaica Mobile'),(1876410,'Jamaica Mobile'),(1876411,'Jamaica Mobile'),(1876412,'Jamaica Mobile'),(1876413,'Jamaica Mobile'),(1876414,'Jamaica Mobile'),(1876416,'Jamaica Mobile'),(1876417,'Jamaica Mobile'),(1876418,'Jamaica Mobile'),(1876419,'Jamaica Mobile'),(187642,'Jamaica Mobile'),(187643,'Jamaica Mobile'),(187644,'Jamaica Mobile'),(187645,'Jamaica Mobile'),(187646,'Jamaica Mobile'),(187647,'Jamaica Mobile'),(187648,'Jamaica Mobile'),(187649,'Jamaica Mobile'),(1876503,'Jamaica Mobile'),(1876504,'Jamaica Mobile'),(1876505,'Jamaica Mobile'),(1876506,'Jamaica Mobile'),(1876507,'Jamaica Mobile'),(1876508,'Jamaica Mobile'),(1876509,'Jamaica Mobile'),(1876520,'Jamaica Mobile'),(1876521,'Jamaica Mobile'),(1876522,'Jamaica Mobile'),(1876524,'Jamaica Mobile'),(1876527,'Jamaica Mobile'),(1876528,'Jamaica Mobile'),(1876529,'Jamaica Mobile'),(187653,'Jamaica Mobile'),(187654,'Jamaica Mobile'),(1876550,'Jamaica Mobile'),(1876551,'Jamaica Mobile'),(1876552,'Jamaica Mobile'),(1876553,'Jamaica Mobile'),(1876554,'Jamaica Mobile'),(1876556,'Jamaica Mobile'),(1876557,'Jamaica Mobile'),(1876558,'Jamaica Mobile'),(1876559,'Jamaica Mobile'),(187656,'Jamaica Mobile'),(187657,'Jamaica Mobile'),(187658,'Jamaica Mobile'),(187659,'Jamaica Mobile'),(18767,'Jamaica Mobile'),(1876707,'Jamaica Mobile'),(187677,'Jamaica Mobile'),(1876781,'Jamaica Mobile'),(1876782,'Jamaica Mobile'),(1876783,'Jamaica Mobile'),(1876784,'Jamaica Mobile'),(1876787,'Jamaica Mobile'),(1876788,'Jamaica Mobile'),(1876789,'Jamaica Mobile'),(1876790,'Jamaica Mobile'),(1876791,'Jamaica Mobile'),(1876792,'Jamaica Mobile'),(1876793,'Jamaica Mobile'),(1876796,'Jamaica Mobile'),(1876797,'Jamaica Mobile'),(1876798,'Jamaica Mobile'),(1876799,'Jamaica Mobile'),(1876801,'Jamaica Mobile'),(1876802,'Jamaica Mobile'),(1876803,'Jamaica Mobile'),(1876804,'Jamaica Mobile'),(1876805,'Jamaica Mobile'),(1876806,'Jamaica Mobile'),(1876807,'Jamaica Mobile'),(1876808,'Jamaica Mobile'),(1876809,'Jamaica Mobile'),(187681,'Jamaica Mobile'),(187682,'Jamaica Mobile'),(187683,'Jamaica Mobile'),(187684,'Jamaica Mobile'),(187685,'Jamaica Mobile'),(187686,'Jamaica Mobile'),(187687,'Jamaica Mobile'),(187688,'Jamaica Mobile'),(187689,'Jamaica Mobile'),(1876909,'Jamaica Mobile'),(1876919,'Jamaica Mobile'),(1876990,'Jamaica Mobile'),(1876995,'Jamaica Mobile'),(1876997,'Jamaica Mobile'),(1876999,'Jamaica Mobile'),(81,'Japan'),(8170,'Japan Mobile'),(8180,'Japan Mobile'),(8190,'Japan Mobile'),(962,'Jordan'),(96274,'Jordan Mobile'),(96277,'Jordan Mobile'),(962785,'Jordan Mobile'),(962786,'Jordan Mobile'),(962788,'Jordan Mobile'),(96279,'Jordan Mobile'),(771,'Kazakhstan'),(772,'Kazakhstan'),(7760,'Kazakhstan'),(7761,'Kazakhstan'),(7762,'Kazakhstan'),(7763,'Kazakhstan'),(77,'Kazakhstan Mobile'),(7701,'Kazakhstan Mobile'),(7702,'Kazakhstan Mobile'),(7705,'Kazakhstan Mobile'),(7707,'Kazakhstan Mobile'),(771290,'Kazakhstan Mobile'),(771291,'Kazakhstan Mobile'),(771390,'Kazakhstan Mobile'),(771391,'Kazakhstan Mobile'),(771490,'Kazakhstan Mobile'),(771491,'Kazakhstan Mobile'),(771590,'Kazakhstan Mobile'),(771591,'Kazakhstan Mobile'),(771790,'Kazakhstan Mobile'),(771791,'Kazakhstan Mobile'),(771890,'Kazakhstan Mobile'),(771891,'Kazakhstan Mobile'),(772190,'Kazakhstan Mobile'),(772191,'Kazakhstan Mobile'),(772390,'Kazakhstan Mobile'),(772391,'Kazakhstan Mobile'),(772490,'Kazakhstan Mobile'),(772491,'Kazakhstan Mobile'),(772590,'Kazakhstan Mobile'),(772591,'Kazakhstan Mobile'),(772690,'Kazakhstan Mobile'),(772691,'Kazakhstan Mobile'),(772790,'Kazakhstan Mobile'),(772791,'Kazakhstan Mobile'),(7777,'Kazakhstan Mobile'),(254,'Kenya'),(2547,'Kenya Mobile'),(686,'Kiribati'),(68630,'Kiribati Mobile'),(68650,'Kiribati Mobile'),(68689,'Kiribati Mobile'),(6869,'Kiribati Mobile'),(965,'Kuwait'),(96540,'Kuwait Mobile'),(96544,'Kuwait Mobile'),(965501,'Kuwait Mobile'),(965502,'Kuwait Mobile'),(965505,'Kuwait Mobile'),(965506,'Kuwait Mobile'),(965507,'Kuwait Mobile'),(965508,'Kuwait Mobile'),(965509,'Kuwait Mobile'),(96551,'Kuwait Mobile'),(965550,'Kuwait Mobile'),(965554,'Kuwait Mobile'),(965555,'Kuwait Mobile'),(965556,'Kuwait Mobile'),(965557,'Kuwait Mobile'),(965558,'Kuwait Mobile'),(965559,'Kuwait Mobile'),(965570,'Kuwait Mobile'),(965578,'Kuwait Mobile'),(965579,'Kuwait Mobile'),(96558,'Kuwait Mobile'),(96559,'Kuwait Mobile'),(9656,'Kuwait Mobile'),(9657,'Kuwait Mobile'),(965701,'Kuwait Mobile'),(965702,'Kuwait Mobile'),(965703,'Kuwait Mobile'),(965704,'Kuwait Mobile'),(965705,'Kuwait Mobile'),(965706,'Kuwait Mobile'),(965707,'Kuwait Mobile'),(965708,'Kuwait Mobile'),(965709,'Kuwait Mobile'),(96571,'Kuwait Mobile'),(96572,'Kuwait Mobile'),(96573,'Kuwait Mobile'),(96574,'Kuwait Mobile'),(96575,'Kuwait Mobile'),(96576,'Kuwait Mobile'),(965770,'Kuwait Mobile'),(965771,'Kuwait Mobile'),(965772,'Kuwait Mobile'),(965773,'Kuwait Mobile'),(965774,'Kuwait Mobile'),(965775,'Kuwait Mobile'),(965776,'Kuwait Mobile'),(965778,'Kuwait Mobile'),(965779,'Kuwait Mobile'),(96578,'Kuwait Mobile'),(96579,'Kuwait Mobile'),(9659,'Kuwait Mobile'),(996,'Kyrgyzstan'),(99631270,'Kyrgyzstan Mobile'),(99631272,'Kyrgyzstan Mobile'),(99631274,'Kyrgyzstan Mobile'),(99631275,'Kyrgyzstan Mobile'),(99631276,'Kyrgyzstan Mobile'),(99631277,'Kyrgyzstan Mobile'),(99631278,'Kyrgyzstan Mobile'),(99631279,'Kyrgyzstan Mobile'),(996502,'Kyrgyzstan Mobile'),(996503,'Kyrgyzstan Mobile'),(996515,'Kyrgyzstan Mobile'),(996517,'Kyrgyzstan Mobile'),(996543,'Kyrgyzstan Mobile'),(996545,'Kyrgyzstan Mobile'),(996550,'Kyrgyzstan Mobile'),(996555,'Kyrgyzstan Mobile'),(996575,'Kyrgyzstan Mobile'),(996577,'Kyrgyzstan Mobile'),(9967,'Kyrgyzstan Mobile'),(99677,'Kyrgyzstan Mobile'),(856,'Laos'),(85620,'Laos Mobile'),(371,'Latvia'),(37120,'Latvia Mobile'),(37121,'Latvia Mobile'),(37122,'Latvia Mobile'),(37123,'Latvia Mobile'),(37124,'Latvia Mobile'),(37125,'Latvia Mobile'),(37126,'Latvia Mobile'),(37127,'Latvia Mobile'),(37128,'Latvia Mobile'),(37129,'Latvia Mobile'),(961,'Lebanon'),(9613,'Lebanon Mobile'),(96170,'Lebanon Mobile'),(96171,'Lebanon Mobile'),(266,'Lesotho'),(2665,'Lesotho Mobile'),(2666,'Lesotho Mobile'),(231,'Liberia'),(23103,'Liberia Mobile'),(23128,'Liberia Mobile'),(23146,'Liberia Mobile'),(23147,'Liberia Mobile'),(2315,'Liberia Mobile'),(23164,'Liberia Mobile'),(23165,'Liberia Mobile'),(23166,'Liberia Mobile'),(23167,'Liberia Mobile'),(23168,'Liberia Mobile'),(23169,'Liberia Mobile'),(2317,'Liberia Mobile'),(218,'Libya'),(21891,'Libya Mobile'),(21892,'Libya Mobile'),(21894,'Libya Mobile'),(423,'Liechtenstein'),(4236,'Liechtenstein Mobile'),(4237,'Liechtenstein Mobile'),(370,'Lithuania'),(370393,'Lithuania Mobile'),(3706,'Lithuania Mobile'),(352,'Luxembourg'),(352021,'Luxembourg Mobile'),(352028,'Luxembourg Mobile'),(352061,'Luxembourg Mobile'),(352068,'Luxembourg Mobile'),(352091,'Luxembourg Mobile'),(352098,'Luxembourg Mobile'),(35221,'Luxembourg Mobile'),(35228,'Luxembourg Mobile'),(35261,'Luxembourg Mobile'),(352621,'Luxembourg Mobile'),(352628,'Luxembourg Mobile'),(352661,'Luxembourg Mobile'),(352668,'Luxembourg Mobile'),(35268,'Luxembourg Mobile'),(352691,'Luxembourg Mobile'),(352698,'Luxembourg Mobile'),(35291,'Luxembourg Mobile'),(35298,'Luxembourg Mobile'),(853,'Macao'),(85360,'Macao Mobile'),(85361,'Macao Mobile'),(85362,'Macao Mobile'),(85363,'Macao Mobile'),(85365,'Macao Mobile'),(85366,'Macao Mobile'),(85368,'Macao Mobile'),(85369,'Macao Mobile'),(389,'Macedonia'),(38951,'Macedonia Mobile'),(38970,'Macedonia Mobile'),(38971,'Macedonia Mobile'),(38972,'Macedonia Mobile'),(38973,'Macedonia Mobile'),(38974,'Macedonia Mobile'),(38975,'Macedonia Mobile'),(38976,'Macedonia Mobile'),(38977,'Macedonia Mobile'),(38978,'Macedonia Mobile'),(38979,'Macedonia Mobile'),(261,'Madagascar'),(26130,'Madagascar Mobile'),(26132,'Madagascar Mobile'),(26133,'Madagascar Mobile'),(26134,'Madagascar Mobile'),(265,'Malawi'),(2654,'Malawi Mobile'),(2655,'Malawi Mobile'),(2658,'Malawi Mobile'),(2659,'Malawi Mobile'),(60,'Malaysia'),(601,'Malaysia Mobile'),(960,'Maldives'),(9607,'Maldives Mobile'),(9609,'Maldives Mobile'),(223,'Mali'),(22330,'Mali Mobile'),(22331,'Mali Mobile'),(22332,'Mali Mobile'),(22333,'Mali Mobile'),(22334,'Mali Mobile'),(22340,'Mali Mobile'),(22341,'Mali Mobile'),(22344,'Mali Mobile'),(22345,'Mali Mobile'),(22346,'Mali Mobile'),(22347,'Mali Mobile'),(22350,'Mali Mobile'),(22351,'Mali Mobile'),(22352,'Mali Mobile'),(22353,'Mali Mobile'),(22354,'Mali Mobile'),(22355,'Mali Mobile'),(22356,'Mali Mobile'),(22357,'Mali Mobile'),(22358,'Mali Mobile'),(22359,'Mali Mobile'),(22360,'Mali Mobile'),(22361,'Mali Mobile'),(22362,'Mali Mobile'),(22363,'Mali Mobile'),(22364,'Mali Mobile'),(22365,'Mali Mobile'),(22366,'Mali Mobile'),(22367,'Mali Mobile'),(22368,'Mali Mobile'),(22369,'Mali Mobile'),(22385,'Mali Mobile'),(22386,'Mali Mobile'),(22387,'Mali Mobile'),(22388,'Mali Mobile'),(22389,'Mali Mobile'),(22390,'Mali Mobile'),(22391,'Mali Mobile'),(22392,'Mali Mobile'),(22393,'Mali Mobile'),(22394,'Mali Mobile'),(22395,'Mali Mobile'),(22396,'Mali Mobile'),(22397,'Mali Mobile'),(22398,'Mali Mobile'),(22399,'Mali Mobile'),(356,'Malta'),(3567117,'Malta Mobile'),(35672,'Malta Mobile'),(356777,'Malta Mobile'),(35679,'Malta Mobile'),(35692,'Malta Mobile'),(35699,'Malta Mobile'),(692,'Marshall Islands'),(6922350,'Marshall Islands Mobile'),(6922351,'Marshall Islands Mobile'),(6922352,'Marshall Islands Mobile'),(6922353,'Marshall Islands Mobile'),(6922354,'Marshall Islands Mobile'),(692455,'Marshall Islands Mobile'),(6926250,'Marshall Islands Mobile'),(6926251,'Marshall Islands Mobile'),(33596,'Martinique'),(596,'Martinique'),(596696,'Martinique Mobile'),(222,'Mauritania'),(2222,'Mauritania Mobile'),(2226,'Mauritania Mobile'),(22270,'Mauritania Mobile'),(22273,'Mauritania Mobile'),(230,'Mauritius'),(2302189,'Mauritius Mobile'),(230219,'Mauritius Mobile'),(23022,'Mauritius Mobile'),(23025,'Mauritius Mobile'),(230421,'Mauritius Mobile'),(230422,'Mauritius Mobile'),(230423,'Mauritius Mobile'),(230428,'Mauritius Mobile'),(230429,'Mauritius Mobile'),(23049,'Mauritius Mobile'),(23070,'Mauritius Mobile'),(23071,'Mauritius Mobile'),(23072,'Mauritius Mobile'),(23073,'Mauritius Mobile'),(23074,'Mauritius Mobile'),(23075,'Mauritius Mobile'),(23076,'Mauritius Mobile'),(23077,'Mauritius Mobile'),(23078,'Mauritius Mobile'),(23079,'Mauritius Mobile'),(230871,'Mauritius Mobile'),(230875,'Mauritius Mobile'),(230876,'Mauritius Mobile'),(230877,'Mauritius Mobile'),(23091,'Mauritius Mobile'),(23093,'Mauritius Mobile'),(23094,'Mauritius Mobile'),(23095,'Mauritius Mobile'),(23097,'Mauritius Mobile'),(23098,'Mauritius Mobile'),(262269,'Mayotte'),(262639,'Mayotte Mobile'),(52,'Mexico'),(521,'Mexico Mobile'),(691,'Micronesia'),(373,'Moldova'),(373650,'Moldova Mobile'),(373671,'Moldova Mobile'),(373672,'Moldova Mobile'),(373673,'Moldova Mobile'),(373680,'Moldova Mobile'),(373681,'Moldova Mobile'),(373682,'Moldova Mobile'),(373683,'Moldova Mobile'),(373684,'Moldova Mobile'),(373685,'Moldova Mobile'),(373686,'Moldova Mobile'),(373687,'Moldova Mobile'),(373688,'Moldova Mobile'),(37369,'Moldova Mobile'),(373774,'Moldova Mobile'),(373777,'Moldova Mobile'),(373778,'Moldova Mobile'),(373780,'Moldova Mobile'),(373781,'Moldova Mobile'),(37379,'Moldova Mobile'),(377,'Monaco'),(3774,'Monaco Mobile'),(3776,'Monaco Mobile'),(976,'Mongolia'),(97688,'Mongolia Mobile'),(97691,'Mongolia Mobile'),(97695,'Mongolia Mobile'),(97696,'Mongolia Mobile'),(97699,'Mongolia Mobile'),(382,'Montenegro'),(38263,'Montenegro Mobile'),(38267,'Montenegro Mobile'),(38268,'Montenegro Mobile'),(38269,'Montenegro Mobile'),(1664,'Montserrat'),(1664492,'Montserrat Mobile'),(1664724,'Montserrat Mobile'),(212,'Morocco'),(2121,'Morocco Mobile'),(21226,'Morocco Mobile'),(21227,'Morocco Mobile'),(21233,'Morocco Mobile'),(21234,'Morocco Mobile'),(21240,'Morocco Mobile'),(21241,'Morocco Mobile'),(21242,'Morocco Mobile'),(21244,'Morocco Mobile'),(21245,'Morocco Mobile'),(21246,'Morocco Mobile'),(21247,'Morocco Mobile'),(21248,'Morocco Mobile'),(21249,'Morocco Mobile'),(21250,'Morocco Mobile'),(21251,'Morocco Mobile'),(21252,'Morocco Mobile'),(21253,'Morocco Mobile'),(21254,'Morocco Mobile'),(21255,'Morocco Mobile'),(21259,'Morocco Mobile'),(2126,'Morocco Mobile'),(2127,'Morocco Mobile'),(21292,'Morocco Mobile'),(258,'Rwanda Mobile'),(25882,'Mozambique Mobile'),(25884,'Mozambique Mobile'),(95,'Myanmar'),(959,'Myanmar Mobile'),(264,'Namibia'),(26481,'Namibia Mobile'),(26485,'Namibia Mobile'),(674,'Nauru'),(674555,'Nauru Mobile'),(977,'Nepal'),(97798,'Nepal Mobile'),(31,'Netherlands'),(599,'Netherlands Antilles'),(5993181,'Netherlands Antilles Mobile'),(5993184,'Netherlands Antilles Mobile'),(5993185,'Netherlands Antilles Mobile'),(5993186,'Netherlands Antilles Mobile'),(5994161,'Netherlands Antilles Mobile'),(5994165,'Netherlands Antilles Mobile'),(5994166,'Netherlands Antilles Mobile'),(5994167,'Netherlands Antilles Mobile'),(599510,'Netherlands Antilles Mobile'),(599520,'Netherlands Antilles Mobile'),(599521,'Netherlands Antilles Mobile'),(599522,'Netherlands Antilles Mobile'),(599523,'Netherlands Antilles Mobile'),(599524,'Netherlands Antilles Mobile'),(599526,'Netherlands Antilles Mobile'),(599527,'Netherlands Antilles Mobile'),(599550,'Netherlands Antilles Mobile'),(599551,'Netherlands Antilles Mobile'),(599552,'Netherlands Antilles Mobile'),(599553,'Netherlands Antilles Mobile'),(599554,'Netherlands Antilles Mobile'),(599555,'Netherlands Antilles Mobile'),(599556,'Netherlands Antilles Mobile'),(599557,'Netherlands Antilles Mobile'),(599558,'Netherlands Antilles Mobile'),(599559,'Netherlands Antilles Mobile'),(599580,'Netherlands Antilles Mobile'),(599581,'Netherlands Antilles Mobile'),(599586,'Netherlands Antilles Mobile'),(599587,'Netherlands Antilles Mobile'),(599588,'Netherlands Antilles Mobile'),(5997,'Netherlands Antilles Mobile'),(599701,'Netherlands Antilles Mobile'),(59978,'Netherlands Antilles Mobile'),(59979,'Netherlands Antilles Mobile'),(59980,'Netherlands Antilles Mobile'),(599951,'Netherlands Antilles Mobile'),(599952,'Netherlands Antilles Mobile'),(5999530,'Netherlands Antilles Mobile'),(599954,'Netherlands Antilles Mobile'),(599955,'Netherlands Antilles Mobile'),(599956,'Netherlands Antilles Mobile'),(599961,'Netherlands Antilles Mobile'),(5999630,'Netherlands Antilles Mobile'),(5999631,'Netherlands Antilles Mobile'),(599966,'Netherlands Antilles Mobile'),(599967,'Netherlands Antilles Mobile'),(599969,'Netherlands Antilles Mobile'),(316,'Netherlands Mobile'),(687,'New Caledonia'),(68775,'New Caledonia Mobile'),(68776,'New Caledonia Mobile'),(68777,'New Caledonia Mobile'),(68778,'New Caledonia Mobile'),(68779,'New Caledonia Mobile'),(68780,'New Caledonia Mobile'),(68781,'New Caledonia Mobile'),(68782,'New Caledonia Mobile'),(68783,'New Caledonia Mobile'),(68784,'New Caledonia Mobile'),(68785,'New Caledonia Mobile'),(68786,'New Caledonia Mobile'),(68787,'New Caledonia Mobile'),(68789,'New Caledonia Mobile'),(6879,'New Caledonia Mobile'),(64,'New Zealand'),(6420,'New Zealand Mobile'),(6421,'New Zealand Mobile'),(6422,'New Zealand Mobile'),(6423,'New Zealand Mobile'),(6424,'New Zealand Mobile'),(6425,'New Zealand Mobile'),(6426,'New Zealand Mobile'),(6427,'New Zealand Mobile'),(6428,'New Zealand Mobile'),(6429,'New Zealand Mobile'),(505,'Nicaragua'),(5054,'Nicaragua Mobile'),(5056,'Nicaragua Mobile'),(5058,'Nicaragua Mobile'),(5059,'Nicaragua Mobile'),(227,'Niger'),(22790,'Niger Mobile'),(22793,'Niger Mobile'),(22794,'Niger Mobile'),(22796,'Niger Mobile'),(234,'Nigeria'),(234702,'Nigeria Mobile'),(234703,'Nigeria Mobile'),(234705,'Nigeria Mobile'),(234706,'Nigeria Mobile'),(234708,'Nigeria Mobile'),(23480,'Nigeria Mobile'),(23490,'Nigeria Mobile'),(683,'Niue'),(6723,'Norfolk Island'),(67238,'Norfolk Island Mobile'),(850,'North Korea'),(1670,'Northern Mariana Islands'),(1670285,'Northern Mariana Islands Mobil'),(1670286,'Northern Mariana Islands Mobil'),(1670287,'Northern Mariana Islands Mobil'),(1670483,'Northern Mariana Islands Mobil'),(1670484,'Northern Mariana Islands Mobil'),(1670488,'Northern Mariana Islands Mobil'),(1670588,'Northern Mariana Islands Mobil'),(1670788,'Northern Mariana Islands Mobil'),(1670789,'Northern Mariana Islands Mobil'),(1670838,'Northern Mariana Islands Mobil'),(1670868,'Northern Mariana Islands Mobil'),(1670878,'Northern Mariana Islands Mobil'),(1670888,'Northern Mariana Islands Mobil'),(1670898,'Northern Mariana Islands Mobil'),(1670989,'Northern Mariana Islands Mobil'),(47,'Norway'),(474,'Norway Mobile'),(479,'Norway Mobile'),(968,'Oman'),(96891,'Oman Mobile'),(96892,'Oman Mobile'),(96895,'Oman Mobile'),(96896,'Oman Mobile'),(96897,'Oman Mobile'),(96898,'Oman Mobile'),(96899,'Oman Mobile'),(92,'Pakistan'),(923,'Pakistan Mobile'),(680,'Palau'),(680620,'Palau Mobile'),(680630,'Palau Mobile'),(680640,'Palau Mobile'),(680660,'Palau Mobile'),(680680,'Palau Mobile'),(680690,'Palau Mobile'),(680775,'Palau Mobile'),(680779,'Palau Mobile'),(970,'Palestinian Territory'),(97222,'Palestinian Territory'),(97232,'Palestinian Territory'),(97242,'Palestinian Territory'),(97282,'Palestinian Territory'),(97292,'Palestinian Territory'),(97059,'Palestinian Territory Mobile'),(97259,'Palestinian Territory Mobile'),(507,'Panama'),(507272,'Panama Mobile'),(507276,'Panama Mobile'),(507443,'Panama Mobile'),(5076,'Panama Mobile'),(507810,'Panama Mobile'),(507811,'Panama Mobile'),(507855,'Panama Mobile'),(507872,'Panama Mobile'),(507873,'Panama Mobile'),(675,'Papua New Guinea'),(67563,'Papua New Guinea Mobile'),(67565,'Papua New Guinea Mobile'),(67567,'Papua New Guinea Mobile'),(67568,'Papua New Guinea Mobile'),(67569,'Papua New Guinea Mobile'),(67571,'Papua New Guinea Mobile'),(67572,'Papua New Guinea Mobile'),(595,'Paraguay'),(595941,'Paraguay Mobile'),(595943,'Paraguay Mobile'),(595945,'Paraguay Mobile'),(595961,'Paraguay Mobile'),(595971,'Paraguay Mobile'),(595973,'Paraguay Mobile'),(595975,'Paraguay Mobile'),(595981,'Paraguay Mobile'),(595982,'Paraguay Mobile'),(595983,'Paraguay Mobile'),(595985,'Paraguay Mobile'),(595991,'Paraguay Mobile'),(595993,'Paraguay Mobile'),(595995,'Paraguay Mobile'),(51,'Peru'),(5119,'Peru Mobile'),(51419,'Peru Mobile'),(51429,'Peru Mobile'),(51439,'Peru Mobile'),(51449,'Peru Mobile'),(51519,'Peru Mobile'),(51529,'Peru Mobile'),(51539,'Peru Mobile'),(51549,'Peru Mobile'),(51569,'Peru Mobile'),(51619,'Peru Mobile'),(51629,'Peru Mobile'),(51639,'Peru Mobile'),(51649,'Peru Mobile'),(51659,'Peru Mobile'),(51669,'Peru Mobile'),(51679,'Peru Mobile'),(51729,'Peru Mobile'),(51739,'Peru Mobile'),(51749,'Peru Mobile'),(51769,'Peru Mobile'),(51829,'Peru Mobile'),(51839,'Peru Mobile'),(51849,'Peru Mobile'),(519,'Peru Mobile'),(63,'Philippines'),(639,'Philippines Mobile'),(48,'Poland'),(4850,'Poland Mobile'),(4851,'Poland Mobile'),(4860,'Poland Mobile'),(48642,'Poland Mobile'),(4866,'Poland Mobile'),(4869,'Poland Mobile'),(48721,'Poland Mobile'),(48722,'Poland Mobile'),(48723,'Poland Mobile'),(48724,'Poland Mobile'),(48725,'Poland Mobile'),(48726,'Poland Mobile'),(487272,'Poland Mobile'),(487273,'Poland Mobile'),(487274,'Poland Mobile'),(487275,'Poland Mobile'),(487276,'Poland Mobile'),(487277,'Poland Mobile'),(487278,'Poland Mobile'),(487279,'Poland Mobile'),(487281,'Poland Mobile'),(487282,'Poland Mobile'),(487283,'Poland Mobile'),(487284,'Poland Mobile'),(487285,'Poland Mobile'),(487286,'Poland Mobile'),(487287,'Poland Mobile'),(487288,'Poland Mobile'),(487289,'Poland Mobile'),(48729,'Poland Mobile'),(48780,'Poland Mobile'),(48781,'Poland Mobile'),(48782,'Poland Mobile'),(48783,'Poland Mobile'),(48784,'Poland Mobile'),(48785,'Poland Mobile'),(48786,'Poland Mobile'),(48787,'Poland Mobile'),(48788,'Poland Mobile'),(48789,'Poland Mobile'),(48790,'Poland Mobile'),(48791,'Poland Mobile'),(48792,'Poland Mobile'),(48793,'Poland Mobile'),(48794,'Poland Mobile'),(48795,'Poland Mobile'),(48796,'Poland Mobile'),(48797,'Poland Mobile'),(48798,'Poland Mobile'),(48799,'Poland Mobile'),(48880,'Poland Mobile'),(488811,'Poland Mobile'),(488818,'Poland Mobile'),(48882,'Poland Mobile'),(488833,'Poland Mobile'),(488838,'Poland Mobile'),(48884,'Poland Mobile'),(48885,'Poland Mobile'),(48886,'Poland Mobile'),(48887,'Poland Mobile'),(48888,'Poland Mobile'),(48889,'Poland Mobile'),(351,'Portugal'),(3519,'Portugal Mobile'),(1787,'Puerto Rico'),(1939,'Puerto Rico'),(1787201,'Puerto Rico Mobile'),(1787202,'Puerto Rico Mobile'),(1787203,'Puerto Rico Mobile'),(1787204,'Puerto Rico Mobile'),(1787205,'Puerto Rico Mobile'),(1787206,'Puerto Rico Mobile'),(1787207,'Puerto Rico Mobile'),(1787208,'Puerto Rico Mobile'),(1787209,'Puerto Rico Mobile'),(1787210,'Puerto Rico Mobile'),(1787212,'Puerto Rico Mobile'),(1787213,'Puerto Rico Mobile'),(1787214,'Puerto Rico Mobile'),(1787215,'Puerto Rico Mobile'),(1787216,'Puerto Rico Mobile'),(1787217,'Puerto Rico Mobile'),(1787218,'Puerto Rico Mobile'),(1787219,'Puerto Rico Mobile'),(1787220,'Puerto Rico Mobile'),(1787221,'Puerto Rico Mobile'),(1787222,'Puerto Rico Mobile'),(1787223,'Puerto Rico Mobile'),(1787224,'Puerto Rico Mobile'),(1787225,'Puerto Rico Mobile'),(1787226,'Puerto Rico Mobile'),(1787228,'Puerto Rico Mobile'),(1787230,'Puerto Rico Mobile'),(1787231,'Puerto Rico Mobile'),(1787232,'Puerto Rico Mobile'),(1787233,'Puerto Rico Mobile'),(1787234,'Puerto Rico Mobile'),(1787235,'Puerto Rico Mobile'),(1787236,'Puerto Rico Mobile'),(1787237,'Puerto Rico Mobile'),(1787238,'Puerto Rico Mobile'),(1787239,'Puerto Rico Mobile'),(1787240,'Puerto Rico Mobile'),(1787241,'Puerto Rico Mobile'),(1787242,'Puerto Rico Mobile'),(1787243,'Puerto Rico Mobile'),(1787244,'Puerto Rico Mobile'),(1787245,'Puerto Rico Mobile'),(1787246,'Puerto Rico Mobile'),(1787247,'Puerto Rico Mobile'),(1787248,'Puerto Rico Mobile'),(1787249,'Puerto Rico Mobile'),(1787295,'Puerto Rico Mobile'),(1787297,'Puerto Rico Mobile'),(1787298,'Puerto Rico Mobile'),(1787299,'Puerto Rico Mobile'),(1787301,'Puerto Rico Mobile'),(1787302,'Puerto Rico Mobile'),(1787303,'Puerto Rico Mobile'),(1787304,'Puerto Rico Mobile'),(1787305,'Puerto Rico Mobile'),(1787306,'Puerto Rico Mobile'),(1787307,'Puerto Rico Mobile'),(1787308,'Puerto Rico Mobile'),(1787309,'Puerto Rico Mobile'),(1787310,'Puerto Rico Mobile'),(1787312,'Puerto Rico Mobile'),(1787313,'Puerto Rico Mobile'),(1787314,'Puerto Rico Mobile'),(1787315,'Puerto Rico Mobile'),(1787316,'Puerto Rico Mobile'),(1787317,'Puerto Rico Mobile'),(1787318,'Puerto Rico Mobile'),(1787319,'Puerto Rico Mobile'),(1787320,'Puerto Rico Mobile'),(1787321,'Puerto Rico Mobile'),(1787322,'Puerto Rico Mobile'),(1787323,'Puerto Rico Mobile'),(1787324,'Puerto Rico Mobile'),(1787325,'Puerto Rico Mobile'),(1787326,'Puerto Rico Mobile'),(1787327,'Puerto Rico Mobile'),(1787328,'Puerto Rico Mobile'),(1787329,'Puerto Rico Mobile'),(1787330,'Puerto Rico Mobile'),(1787331,'Puerto Rico Mobile'),(1787332,'Puerto Rico Mobile'),(1787333,'Puerto Rico Mobile'),(1787334,'Puerto Rico Mobile'),(1787335,'Puerto Rico Mobile'),(1787336,'Puerto Rico Mobile'),(1787337,'Puerto Rico Mobile'),(1787338,'Puerto Rico Mobile'),(1787339,'Puerto Rico Mobile'),(1787340,'Puerto Rico Mobile'),(1787341,'Puerto Rico Mobile'),(1787342,'Puerto Rico Mobile'),(1787344,'Puerto Rico Mobile'),(1787345,'Puerto Rico Mobile'),(1787346,'Puerto Rico Mobile'),(1787347,'Puerto Rico Mobile'),(1787348,'Puerto Rico Mobile'),(1787349,'Puerto Rico Mobile'),(1787350,'Puerto Rico Mobile'),(1787351,'Puerto Rico Mobile'),(1787352,'Puerto Rico Mobile'),(1787353,'Puerto Rico Mobile'),(1787354,'Puerto Rico Mobile'),(1787356,'Puerto Rico Mobile'),(1787358,'Puerto Rico Mobile'),(1787359,'Puerto Rico Mobile'),(1787360,'Puerto Rico Mobile'),(1787361,'Puerto Rico Mobile'),(1787362,'Puerto Rico Mobile'),(1787363,'Puerto Rico Mobile'),(1787364,'Puerto Rico Mobile'),(1787365,'Puerto Rico Mobile'),(1787366,'Puerto Rico Mobile'),(1787367,'Puerto Rico Mobile'),(1787368,'Puerto Rico Mobile'),(1787370,'Puerto Rico Mobile'),(1787371,'Puerto Rico Mobile'),(1787372,'Puerto Rico Mobile'),(1787373,'Puerto Rico Mobile'),(1787374,'Puerto Rico Mobile'),(1787375,'Puerto Rico Mobile'),(1787376,'Puerto Rico Mobile'),(1787377,'Puerto Rico Mobile'),(1787378,'Puerto Rico Mobile'),(1787379,'Puerto Rico Mobile'),(1787380,'Puerto Rico Mobile'),(1787381,'Puerto Rico Mobile'),(1787382,'Puerto Rico Mobile'),(1787383,'Puerto Rico Mobile'),(1787384,'Puerto Rico Mobile'),(1787385,'Puerto Rico Mobile'),(1787386,'Puerto Rico Mobile'),(1787387,'Puerto Rico Mobile'),(1787388,'Puerto Rico Mobile'),(1787389,'Puerto Rico Mobile'),(1787390,'Puerto Rico Mobile'),(1787391,'Puerto Rico Mobile'),(1787392,'Puerto Rico Mobile'),(1787393,'Puerto Rico Mobile'),(1787394,'Puerto Rico Mobile'),(1787396,'Puerto Rico Mobile'),(1787397,'Puerto Rico Mobile'),(1787398,'Puerto Rico Mobile'),(1787399,'Puerto Rico Mobile'),(17874,'Puerto Rico Mobile'),(1787401,'Puerto Rico Mobile'),(1787402,'Puerto Rico Mobile'),(1787403,'Puerto Rico Mobile'),(1787404,'Puerto Rico Mobile'),(1787405,'Puerto Rico Mobile'),(1787406,'Puerto Rico Mobile'),(1787407,'Puerto Rico Mobile'),(1787408,'Puerto Rico Mobile'),(1787409,'Puerto Rico Mobile'),(1787410,'Puerto Rico Mobile'),(1787412,'Puerto Rico Mobile'),(1787413,'Puerto Rico Mobile'),(1787414,'Puerto Rico Mobile'),(1787415,'Puerto Rico Mobile'),(1787416,'Puerto Rico Mobile'),(1787417,'Puerto Rico Mobile'),(1787418,'Puerto Rico Mobile'),(1787419,'Puerto Rico Mobile'),(1787420,'Puerto Rico Mobile'),(1787421,'Puerto Rico Mobile'),(1787422,'Puerto Rico Mobile'),(1787423,'Puerto Rico Mobile'),(1787424,'Puerto Rico Mobile'),(1787425,'Puerto Rico Mobile'),(1787426,'Puerto Rico Mobile'),(1787427,'Puerto Rico Mobile'),(1787428,'Puerto Rico Mobile'),(1787429,'Puerto Rico Mobile'),(1787430,'Puerto Rico Mobile'),(1787431,'Puerto Rico Mobile'),(1787432,'Puerto Rico Mobile'),(1787433,'Puerto Rico Mobile'),(1787435,'Puerto Rico Mobile'),(1787436,'Puerto Rico Mobile'),(1787438,'Puerto Rico Mobile'),(1787439,'Puerto Rico Mobile'),(1787440,'Puerto Rico Mobile'),(1787441,'Puerto Rico Mobile'),(1787442,'Puerto Rico Mobile'),(1787443,'Puerto Rico Mobile'),(1787444,'Puerto Rico Mobile'),(1787445,'Puerto Rico Mobile'),(1787446,'Puerto Rico Mobile'),(1787447,'Puerto Rico Mobile'),(1787448,'Puerto Rico Mobile'),(1787449,'Puerto Rico Mobile'),(1787450,'Puerto Rico Mobile'),(1787451,'Puerto Rico Mobile'),(1787452,'Puerto Rico Mobile'),(1787453,'Puerto Rico Mobile'),(1787454,'Puerto Rico Mobile'),(1787455,'Puerto Rico Mobile'),(1787456,'Puerto Rico Mobile'),(1787457,'Puerto Rico Mobile'),(1787458,'Puerto Rico Mobile'),(1787459,'Puerto Rico Mobile'),(1787460,'Puerto Rico Mobile'),(1787461,'Puerto Rico Mobile'),(1787462,'Puerto Rico Mobile'),(1787463,'Puerto Rico Mobile'),(1787464,'Puerto Rico Mobile'),(1787466,'Puerto Rico Mobile'),(1787467,'Puerto Rico Mobile'),(1787469,'Puerto Rico Mobile'),(1787470,'Puerto Rico Mobile'),(1787472,'Puerto Rico Mobile'),(1787473,'Puerto Rico Mobile'),(1787475,'Puerto Rico Mobile'),(1787477,'Puerto Rico Mobile'),(1787478,'Puerto Rico Mobile'),(1787479,'Puerto Rico Mobile'),(1787481,'Puerto Rico Mobile'),(1787484,'Puerto Rico Mobile'),(1787485,'Puerto Rico Mobile'),(1787486,'Puerto Rico Mobile'),(1787487,'Puerto Rico Mobile'),(1787488,'Puerto Rico Mobile'),(1787489,'Puerto Rico Mobile'),(1787490,'Puerto Rico Mobile'),(1787491,'Puerto Rico Mobile'),(1787492,'Puerto Rico Mobile'),(1787493,'Puerto Rico Mobile'),(1787494,'Puerto Rico Mobile'),(1787495,'Puerto Rico Mobile'),(1787496,'Puerto Rico Mobile'),(1787497,'Puerto Rico Mobile'),(1787498,'Puerto Rico Mobile'),(1787499,'Puerto Rico Mobile'),(1787501,'Puerto Rico Mobile'),(1787502,'Puerto Rico Mobile'),(1787503,'Puerto Rico Mobile'),(1787504,'Puerto Rico Mobile'),(1787505,'Puerto Rico Mobile'),(1787506,'Puerto Rico Mobile'),(1787507,'Puerto Rico Mobile'),(1787508,'Puerto Rico Mobile'),(1787509,'Puerto Rico Mobile'),(1787510,'Puerto Rico Mobile'),(1787512,'Puerto Rico Mobile'),(1787514,'Puerto Rico Mobile'),(1787515,'Puerto Rico Mobile'),(1787516,'Puerto Rico Mobile'),(1787517,'Puerto Rico Mobile'),(1787518,'Puerto Rico Mobile'),(1787519,'Puerto Rico Mobile'),(1787525,'Puerto Rico Mobile'),(1787526,'Puerto Rico Mobile'),(1787527,'Puerto Rico Mobile'),(1787528,'Puerto Rico Mobile'),(1787529,'Puerto Rico Mobile'),(1787530,'Puerto Rico Mobile'),(1787531,'Puerto Rico Mobile'),(1787532,'Puerto Rico Mobile'),(1787533,'Puerto Rico Mobile'),(1787536,'Puerto Rico Mobile'),(1787538,'Puerto Rico Mobile'),(1787539,'Puerto Rico Mobile'),(1787540,'Puerto Rico Mobile'),(1787541,'Puerto Rico Mobile'),(1787542,'Puerto Rico Mobile'),(1787543,'Puerto Rico Mobile'),(1787546,'Puerto Rico Mobile'),(1787547,'Puerto Rico Mobile'),(1787548,'Puerto Rico Mobile'),(1787549,'Puerto Rico Mobile'),(1787550,'Puerto Rico Mobile'),(1787552,'Puerto Rico Mobile'),(1787553,'Puerto Rico Mobile'),(1787554,'Puerto Rico Mobile'),(1787556,'Puerto Rico Mobile'),(1787557,'Puerto Rico Mobile'),(1787559,'Puerto Rico Mobile'),(1787560,'Puerto Rico Mobile'),(1787562,'Puerto Rico Mobile'),(1787564,'Puerto Rico Mobile'),(1787565,'Puerto Rico Mobile'),(1787566,'Puerto Rico Mobile'),(1787567,'Puerto Rico Mobile'),(1787568,'Puerto Rico Mobile'),(1787570,'Puerto Rico Mobile'),(1787571,'Puerto Rico Mobile'),(1787572,'Puerto Rico Mobile'),(1787573,'Puerto Rico Mobile'),(1787574,'Puerto Rico Mobile'),(1787575,'Puerto Rico Mobile'),(1787576,'Puerto Rico Mobile'),(1787577,'Puerto Rico Mobile'),(1787578,'Puerto Rico Mobile'),(1787579,'Puerto Rico Mobile'),(1787581,'Puerto Rico Mobile'),(1787582,'Puerto Rico Mobile'),(1787583,'Puerto Rico Mobile'),(1787584,'Puerto Rico Mobile'),(1787585,'Puerto Rico Mobile'),(1787586,'Puerto Rico Mobile'),(1787587,'Puerto Rico Mobile'),(1787590,'Puerto Rico Mobile'),(1787593,'Puerto Rico Mobile'),(1787594,'Puerto Rico Mobile'),(1787595,'Puerto Rico Mobile'),(1787596,'Puerto Rico Mobile'),(1787597,'Puerto Rico Mobile'),(1787598,'Puerto Rico Mobile'),(1787599,'Puerto Rico Mobile'),(1787601,'Puerto Rico Mobile'),(1787602,'Puerto Rico Mobile'),(1787603,'Puerto Rico Mobile'),(1787604,'Puerto Rico Mobile'),(1787605,'Puerto Rico Mobile'),(1787606,'Puerto Rico Mobile'),(1787607,'Puerto Rico Mobile'),(1787608,'Puerto Rico Mobile'),(1787610,'Puerto Rico Mobile'),(1787612,'Puerto Rico Mobile'),(1787613,'Puerto Rico Mobile'),(1787614,'Puerto Rico Mobile'),(1787615,'Puerto Rico Mobile'),(1787616,'Puerto Rico Mobile'),(1787617,'Puerto Rico Mobile'),(1787618,'Puerto Rico Mobile'),(1787619,'Puerto Rico Mobile'),(1787627,'Puerto Rico Mobile'),(1787628,'Puerto Rico Mobile'),(1787629,'Puerto Rico Mobile'),(1787630,'Puerto Rico Mobile'),(1787631,'Puerto Rico Mobile'),(1787632,'Puerto Rico Mobile'),(1787633,'Puerto Rico Mobile'),(1787634,'Puerto Rico Mobile'),(1787635,'Puerto Rico Mobile'),(1787636,'Puerto Rico Mobile'),(1787637,'Puerto Rico Mobile'),(1787638,'Puerto Rico Mobile'),(1787639,'Puerto Rico Mobile'),(1787640,'Puerto Rico Mobile'),(1787642,'Puerto Rico Mobile'),(1787643,'Puerto Rico Mobile'),(1787644,'Puerto Rico Mobile'),(1787645,'Puerto Rico Mobile'),(1787646,'Puerto Rico Mobile'),(1787647,'Puerto Rico Mobile'),(1787648,'Puerto Rico Mobile'),(1787649,'Puerto Rico Mobile'),(1787661,'Puerto Rico Mobile'),(1787662,'Puerto Rico Mobile'),(1787664,'Puerto Rico Mobile'),(1787667,'Puerto Rico Mobile'),(1787668,'Puerto Rico Mobile'),(1787669,'Puerto Rico Mobile'),(1787671,'Puerto Rico Mobile'),(1787672,'Puerto Rico Mobile'),(1787673,'Puerto Rico Mobile'),(1787674,'Puerto Rico Mobile'),(1787675,'Puerto Rico Mobile'),(1787676,'Puerto Rico Mobile'),(1787677,'Puerto Rico Mobile'),(1787678,'Puerto Rico Mobile'),(1787685,'Puerto Rico Mobile'),(1787688,'Puerto Rico Mobile'),(1787689,'Puerto Rico Mobile'),(1787690,'Puerto Rico Mobile'),(1787691,'Puerto Rico Mobile'),(1787692,'Puerto Rico Mobile'),(1787696,'Puerto Rico Mobile'),(1787697,'Puerto Rico Mobile'),(1787698,'Puerto Rico Mobile'),(1787702,'Puerto Rico Mobile'),(1787709,'Puerto Rico Mobile'),(1787717,'Puerto Rico Mobile'),(1787718,'Puerto Rico Mobile'),(1787810,'Puerto Rico Mobile'),(1787901,'Puerto Rico Mobile'),(1787902,'Puerto Rico Mobile'),(1787903,'Puerto Rico Mobile'),(1787904,'Puerto Rico Mobile'),(1787905,'Puerto Rico Mobile'),(1787906,'Puerto Rico Mobile'),(1787907,'Puerto Rico Mobile'),(1787908,'Puerto Rico Mobile'),(1787909,'Puerto Rico Mobile'),(1787910,'Puerto Rico Mobile'),(1787914,'Puerto Rico Mobile'),(1787918,'Puerto Rico Mobile'),(1787920,'Puerto Rico Mobile'),(1787922,'Puerto Rico Mobile'),(1787923,'Puerto Rico Mobile'),(1787925,'Puerto Rico Mobile'),(1787929,'Puerto Rico Mobile'),(1787930,'Puerto Rico Mobile'),(1787932,'Puerto Rico Mobile'),(1787934,'Puerto Rico Mobile'),(1787938,'Puerto Rico Mobile'),(1787940,'Puerto Rico Mobile'),(1787941,'Puerto Rico Mobile'),(1787942,'Puerto Rico Mobile'),(1787943,'Puerto Rico Mobile'),(1787944,'Puerto Rico Mobile'),(1787946,'Puerto Rico Mobile'),(1787948,'Puerto Rico Mobile'),(1787949,'Puerto Rico Mobile'),(1787951,'Puerto Rico Mobile'),(1787955,'Puerto Rico Mobile'),(1787960,'Puerto Rico Mobile'),(1787962,'Puerto Rico Mobile'),(1787963,'Puerto Rico Mobile'),(1787964,'Puerto Rico Mobile'),(1787967,'Puerto Rico Mobile'),(1787969,'Puerto Rico Mobile'),(1787972,'Puerto Rico Mobile'),(1787974,'Puerto Rico Mobile'),(1787975,'Puerto Rico Mobile'),(1787979,'Puerto Rico Mobile'),(1787983,'Puerto Rico Mobile'),(1787985,'Puerto Rico Mobile'),(1787988,'Puerto Rico Mobile'),(1787990,'Puerto Rico Mobile'),(1787994,'Puerto Rico Mobile'),(1787996,'Puerto Rico Mobile'),(1939218,'Puerto Rico Mobile'),(1939241,'Puerto Rico Mobile'),(1939242,'Puerto Rico Mobile'),(1939243,'Puerto Rico Mobile'),(1939244,'Puerto Rico Mobile'),(1939245,'Puerto Rico Mobile'),(1939246,'Puerto Rico Mobile'),(1939247,'Puerto Rico Mobile'),(1939248,'Puerto Rico Mobile'),(1939389,'Puerto Rico Mobile'),(1939397,'Puerto Rico Mobile'),(1939475,'Puerto Rico Mobile'),(1939579,'Puerto Rico Mobile'),(1939628,'Puerto Rico Mobile'),(1939630,'Puerto Rico Mobile'),(1939639,'Puerto Rico Mobile'),(1939640,'Puerto Rico Mobile'),(1939642,'Puerto Rico Mobile'),(1939644,'Puerto Rico Mobile'),(1939645,'Puerto Rico Mobile'),(1939717,'Puerto Rico Mobile'),(1939940,'Puerto Rico Mobile'),(1939969,'Puerto Rico Mobile'),(974,'Qatar'),(9741245,'Qatar Mobile'),(9741744,'Qatar Mobile'),(97420,'Qatar Mobile'),(97421,'Qatar Mobile'),(97422,'Qatar Mobile'),(9745,'Qatar Mobile'),(97460,'Qatar Mobile'),(97461,'Qatar Mobile'),(97464,'Qatar Mobile'),(97465,'Qatar Mobile'),(97466,'Qatar Mobile'),(97467,'Qatar Mobile'),(97468,'Qatar Mobile'),(97469,'Qatar Mobile'),(262,'Reunion'),(33262,'Reunion'),(262692,'Reunion Mobile'),(262693,'Reunion Mobile'),(26269301,'Reunion Mobile'),(26269302,'Reunion Mobile'),(26269303,'Reunion Mobile'),(26269304,'Reunion Mobile'),(26269310,'Reunion Mobile'),(26269320,'Reunion Mobile'),(26269330,'Reunion Mobile'),(26269333,'Reunion Mobile'),(26269340,'Reunion Mobile'),(26269350,'Reunion Mobile'),(26269360,'Reunion Mobile'),(26269370,'Reunion Mobile'),(26269380,'Reunion Mobile'),(26269390,'Reunion Mobile'),(26269391,'Reunion Mobile'),(26269392,'Reunion Mobile'),(26269393,'Reunion Mobile'),(26269394,'Reunion Mobile'),(26269397,'Reunion Mobile'),(40,'Romania'),(4076,'Romania [Cosmote]'),(403,'Romania [OLO]'),(4074,'Romania [Orange]'),(4075,'Romania [Orange]'),(4078,'Romania [Telemobil]'),(4072,'Romania [Vodafone]'),(4073,'Romania [Vodafone]'),(407,'Romania Mobile'),(7,'Russian Federation'),(734922,'Russian Federation [FIX2]'),(734932,'Russian Federation [FIX2]'),(734934,'Russian Federation [FIX2]'),(7349363,'Russian Federation [FIX2]'),(7349364,'Russian Federation [FIX2]'),(73493667,'Russian Federation [FIX2]'),(73493668,'Russian Federation [FIX2]'),(73493669,'Russian Federation [FIX2]'),(734938,'Russian Federation [FIX2]'),(73494,'Russian Federation [FIX2]'),(734940,'Russian Federation [FIX2]'),(734948,'Russian Federation [FIX2]'),(734992,'Russian Federation [FIX2]'),(734993,'Russian Federation [FIX2]'),(734994,'Russian Federation [FIX2]'),(734995,'Russian Federation [FIX2]'),(734996,'Russian Federation [FIX2]'),(734997,'Russian Federation [FIX2]'),(73842,'Russian Federation [FIX2]'),(738441,'Russian Federation [FIX2]'),(738442,'Russian Federation [FIX2]'),(738443,'Russian Federation [FIX2]'),(738444,'Russian Federation [FIX2]'),(738445,'Russian Federation [FIX2]'),(738446,'Russian Federation [FIX2]'),(738447,'Russian Federation [FIX2]'),(738448,'Russian Federation [FIX2]'),(738449,'Russian Federation [FIX2]'),(738451,'Russian Federation [FIX2]'),(738452,'Russian Federation [FIX2]'),(738453,'Russian Federation [FIX2]'),(738454,'Russian Federation [FIX2]'),(738455,'Russian Federation [FIX2]'),(738456,'Russian Federation [FIX2]'),(738459,'Russian Federation [FIX2]'),(738471,'Russian Federation [FIX2]'),(738473,'Russian Federation [FIX2]'),(738474,'Russian Federation [FIX2]'),(738475,'Russian Federation [FIX2]'),(738510,'Russian Federation [FIX2]'),(738511,'Russian Federation [FIX2]'),(738512,'Russian Federation [FIX2]'),(738513,'Russian Federation [FIX2]'),(738514,'Russian Federation [FIX2]'),(738515,'Russian Federation [FIX2]'),(738516,'Russian Federation [FIX2]'),(738517,'Russian Federation [FIX2]'),(738518,'Russian Federation [FIX2]'),(738519,'Russian Federation [FIX2]'),(738530,'Russian Federation [FIX2]'),(738531,'Russian Federation [FIX2]'),(738532,'Russian Federation [FIX2]'),(738533,'Russian Federation [FIX2]'),(738534,'Russian Federation [FIX2]'),(738535,'Russian Federation [FIX2]'),(738536,'Russian Federation [FIX2]'),(738537,'Russian Federation [FIX2]'),(738538,'Russian Federation [FIX2]'),(738539,'Russian Federation [FIX2]'),(738550,'Russian Federation [FIX2]'),(738551,'Russian Federation [FIX2]'),(738552,'Russian Federation [FIX2]'),(738553,'Russian Federation [FIX2]'),(738554,'Russian Federation [FIX2]'),(738555,'Russian Federation [FIX2]'),(738556,'Russian Federation [FIX2]'),(738557,'Russian Federation [FIX2]'),(738558,'Russian Federation [FIX2]'),(738559,'Russian Federation [FIX2]'),(738560,'Russian Federation [FIX2]'),(738561,'Russian Federation [FIX2]'),(738562,'Russian Federation [FIX2]'),(738563,'Russian Federation [FIX2]'),(738564,'Russian Federation [FIX2]'),(738565,'Russian Federation [FIX2]'),(738566,'Russian Federation [FIX2]'),(738567,'Russian Federation [FIX2]'),(738568,'Russian Federation [FIX2]'),(738569,'Russian Federation [FIX2]'),(738570,'Russian Federation [FIX2]'),(738571,'Russian Federation [FIX2]'),(738572,'Russian Federation [FIX2]'),(738573,'Russian Federation [FIX2]'),(738574,'Russian Federation [FIX2]'),(738575,'Russian Federation [FIX2]'),(738576,'Russian Federation [FIX2]'),(738577,'Russian Federation [FIX2]'),(738578,'Russian Federation [FIX2]'),(738579,'Russian Federation [FIX2]'),(738590,'Russian Federation [FIX2]'),(738591,'Russian Federation [FIX2]'),(738592,'Russian Federation [FIX2]'),(738593,'Russian Federation [FIX2]'),(738594,'Russian Federation [FIX2]'),(738595,'Russian Federation [FIX2]'),(738596,'Russian Federation [FIX2]'),(738597,'Russian Federation [FIX2]'),(738598,'Russian Federation [FIX2]'),(738599,'Russian Federation [FIX2]'),(742135,'Russian Federation [FIX2]'),(742137,'Russian Federation [FIX2]'),(742138,'Russian Federation [FIX2]'),(742141,'Russian Federation [FIX2]'),(742142,'Russian Federation [FIX2]'),(742144,'Russian Federation [FIX2]'),(742146,'Russian Federation [FIX2]'),(742149,'Russian Federation [FIX2]'),(742151,'Russian Federation [FIX2]'),(742153,'Russian Federation [FIX2]'),(742154,'Russian Federation [FIX2]'),(742155,'Russian Federation [FIX2]'),(742156,'Russian Federation [FIX2]'),(74217,'Russian Federation [FIX2]'),(742171,'Russian Federation [FIX2]'),(742331,'Russian Federation [FIX2]'),(742334,'Russian Federation [FIX2]'),(742335,'Russian Federation [FIX2]'),(742337,'Russian Federation [FIX2]'),(742339,'Russian Federation [FIX2]'),(742351,'Russian Federation [FIX2]'),(742352,'Russian Federation [FIX2]'),(742354,'Russian Federation [FIX2]'),(742355,'Russian Federation [FIX2]'),(742356,'Russian Federation [FIX2]'),(742357,'Russian Federation [FIX2]'),(742359,'Russian Federation [FIX2]'),(742371,'Russian Federation [FIX2]'),(742372,'Russian Federation [FIX2]'),(742373,'Russian Federation [FIX2]'),(742374,'Russian Federation [FIX2]'),(742375,'Russian Federation [FIX2]'),(742376,'Russian Federation [FIX2]'),(742377,'Russian Federation [FIX2]'),(782130,'Russian Federation [FIX2]'),(782131,'Russian Federation [FIX2]'),(782132,'Russian Federation [FIX2]'),(782133,'Russian Federation [FIX2]'),(782134,'Russian Federation [FIX2]'),(782135,'Russian Federation [FIX2]'),(782136,'Russian Federation [FIX2]'),(782137,'Russian Federation [FIX2]'),(782138,'Russian Federation [FIX2]'),(782139,'Russian Federation [FIX2]'),(782140,'Russian Federation [FIX2]'),(782141,'Russian Federation [FIX2]'),(782142,'Russian Federation [FIX2]'),(782144,'Russian Federation [FIX2]'),(782145,'Russian Federation [FIX2]'),(782146,'Russian Federation [FIX2]'),(782147,'Russian Federation [FIX2]'),(782149,'Russian Federation [FIX2]'),(782151,'Russian Federation [FIX2]'),(79,'Russian Federation Mobile'),(250,'Rwanda'),(255,'Tanzania'),(685,'Samoa'),(6857,'Samoa Mobile'),(378,'San Marino'),(3786,'San Marino Mobile'),(239,'Sao Tome and Principe'),(23990,'Sao Tome and Principe Mobile'),(966,'Saudi Arabia'),(9665,'Saudi Arabia Mobile'),(9668,'Saudi Arabia Mobile'),(221,'Senegal'),(22176,'Senegal Mobile'),(22177,'Senegal Mobile'),(381,'Serbia and Montenegro'),(3816,'Serbia and Montenegro Mobile'),(248,'Seychelles'),(2485,'Seychelles Mobile'),(2487,'Seychelles Mobile'),(232,'Sierra Leone'),(23223,'Sierra Leone Mobile'),(23230,'Sierra Leone Mobile'),(23233,'Sierra Leone Mobile'),(23235,'Sierra Leone Mobile'),(23240,'Sierra Leone Mobile'),(23250,'Sierra Leone Mobile'),(23276,'Sierra Leone Mobile'),(23277,'Sierra Leone Mobile'),(65,'Singapore'),(658,'Singapore Mobile'),(659,'Singapore Mobile'),(421,'Slovak Republic'),(42190,'Slovak Republic Mobile'),(421910,'Slovak Republic Mobile'),(421911,'Slovak Republic Mobile'),(421912,'Slovak Republic Mobile'),(421913,'Slovak Republic Mobile'),(421914,'Slovak Republic Mobile'),(421915,'Slovak Republic Mobile'),(421916,'Slovak Republic Mobile'),(421917,'Slovak Republic Mobile'),(421918,'Slovak Republic Mobile'),(421919,'Slovak Republic Mobile'),(421944,'Slovak Republic Mobile'),(421948,'Slovak Republic Mobile'),(421949,'Slovak Republic Mobile'),(386,'Slovenia'),(38630,'Slovenia Mobile'),(38631,'Slovenia Mobile'),(38640,'Slovenia Mobile'),(38641,'Slovenia Mobile'),(38649,'Slovenia Mobile'),(38650,'Slovenia Mobile'),(38651,'Slovenia Mobile'),(386641,'Slovenia Mobile'),(38670,'Slovenia Mobile'),(38671,'Slovenia Mobile'),(677,'Solomon Islands'),(67743,'Solomon Islands Mobile'),(67754,'Solomon Islands Mobile'),(67755,'Solomon Islands Mobile'),(67756,'Solomon Islands Mobile'),(67757,'Solomon Islands Mobile'),(67758,'Solomon Islands Mobile'),(67759,'Solomon Islands Mobile'),(67765,'Solomon Islands Mobile'),(67766,'Solomon Islands Mobile'),(67768,'Solomon Islands Mobile'),(67769,'Solomon Islands Mobile'),(6777,'Solomon Islands Mobile'),(6778,'Solomon Islands Mobile'),(6779,'Solomon Islands Mobile'),(252,'Somalia'),(25224,'Somalia Mobile'),(25228,'Somalia Mobile'),(25260,'Somalia Mobile'),(25262,'Somalia Mobile'),(25265,'Somalia Mobile'),(25266,'Somalia Mobile'),(25268,'Somalia Mobile'),(25290,'Somalia Mobile'),(25291,'Somalia Mobile'),(27,'South Africa'),(277,'South Africa Mobile'),(2782,'South Africa Mobile'),(2783,'South Africa Mobile'),(2784,'South Africa Mobile'),(2785,'South Africa Mobile'),(2786,'South Africa Mobile'),(82,'South Korea'),(821,'South Korea Mobile'),(34,'Spain'),(346,'Spain Mobile'),(94,'Sri Lanka'),(9471,'Sri Lanka Mobile'),(9472,'Sri Lanka Mobile'),(9477,'Sri Lanka Mobile'),(9478,'Sri Lanka Mobile'),(290,'St Helena'),(1869,'St Kitts and Nevis'),(1869556,'St Kitts and Nevis Mobile'),(1869557,'St Kitts and Nevis Mobile'),(1869558,'St Kitts and Nevis Mobile'),(1869565,'St Kitts and Nevis Mobile'),(1869566,'St Kitts and Nevis Mobile'),(1869567,'St Kitts and Nevis Mobile'),(1869662,'St Kitts and Nevis Mobile'),(1869663,'St Kitts and Nevis Mobile'),(1869664,'St Kitts and Nevis Mobile'),(1869665,'St Kitts and Nevis Mobile'),(1869667,'St Kitts and Nevis Mobile'),(1869668,'St Kitts and Nevis Mobile'),(1869669,'St Kitts and Nevis Mobile'),(1869762,'St Kitts and Nevis Mobile'),(1869763,'St Kitts and Nevis Mobile'),(1869764,'St Kitts and Nevis Mobile'),(1869765,'St Kitts and Nevis Mobile'),(1758,'St Lucia'),(1758284,'St Lucia Mobile'),(1758285,'St Lucia Mobile'),(1758286,'St Lucia Mobile'),(1758287,'St Lucia Mobile'),(1758384,'St Lucia Mobile'),(1758460,'St Lucia Mobile'),(1758461,'St Lucia Mobile'),(1758481,'St Lucia Mobile'),(1758482,'St Lucia Mobile'),(1758483,'St Lucia Mobile'),(1758484,'St Lucia Mobile'),(1758485,'St Lucia Mobile'),(1758486,'St Lucia Mobile'),(1758487,'St Lucia Mobile'),(1758488,'St Lucia Mobile'),(1758489,'St Lucia Mobile'),(1758518,'St Lucia Mobile'),(1758519,'St Lucia Mobile'),(1758520,'St Lucia Mobile'),(1758584,'St Lucia Mobile'),(1758712,'St Lucia Mobile'),(1758713,'St Lucia Mobile'),(1758714,'St Lucia Mobile'),(1758715,'St Lucia Mobile'),(1758716,'St Lucia Mobile'),(1758717,'St Lucia Mobile'),(1758718,'St Lucia Mobile'),(1758719,'St Lucia Mobile'),(1758720,'St Lucia Mobile'),(1758721,'St Lucia Mobile'),(1758722,'St Lucia Mobile'),(1758723,'St Lucia Mobile'),(1758724,'St Lucia Mobile'),(508,'St Pierre and Miquelon'),(50855,'St Pierre and Miquelon Mobile'),(1784,'St Vincent and the Grenadines'),(1784430,'St Vincent and the Grenadines'),(1784431,'St Vincent and the Grenadines'),(1784432,'St Vincent and the Grenadines'),(1784433,'St Vincent and the Grenadines'),(1784434,'St Vincent and the Grenadines'),(1784454,'St Vincent and the Grenadines'),(1784455,'St Vincent and the Grenadines'),(1784492,'St Vincent and the Grenadines'),(1784493,'St Vincent and the Grenadines'),(1784494,'St Vincent and the Grenadines'),(1784495,'St Vincent and the Grenadines'),(1784526,'St Vincent and the Grenadines'),(1784527,'St Vincent and the Grenadines'),(1784528,'St Vincent and the Grenadines'),(1784529,'St Vincent and the Grenadines'),(1784530,'St Vincent and the Grenadines'),(1784531,'St Vincent and the Grenadines'),(1784532,'St Vincent and the Grenadines'),(1784533,'St Vincent and the Grenadines'),(1784593,'St Vincent and the Grenadines'),(249,'Sudan'),(24991,'Sudan Mobile'),(24992,'Sudan Mobile'),(24994,'Sudan Mobile'),(597,'Suriname'),(5978,'Suriname Mobile'),(268,'Swaziland'),(26860,'Swaziland Mobile'),(26861,'Swaziland Mobile'),(26862,'Swaziland Mobile'),(26863,'Swaziland Mobile'),(26864,'Swaziland Mobile'),(26865,'Swaziland Mobile'),(26866,'Swaziland Mobile'),(26867,'Swaziland Mobile'),(46,'Sweden'),(4610,'Sweden Mobile'),(46252,'Sweden Mobile'),(46376,'Sweden Mobile'),(46518,'Sweden Mobile'),(46519,'Sweden Mobile'),(46673,'Sweden Mobile'),(46674,'Sweden Mobile'),(46675,'Sweden Mobile'),(46676,'Sweden Mobile'),(4670,'Sweden Mobile'),(4673,'Sweden Mobile'),(4674,'Sweden Mobile'),(4676,'Sweden Mobile'),(41,'Switzerland'),(4174,'Switzerland Mobile'),(4176,'Switzerland Mobile'),(4177,'Switzerland Mobile'),(4178,'Switzerland Mobile'),(4179,'Switzerland Mobile'),(4186,'Switzerland Mobile'),(963,'Syrian Arab Republic'),(96392,'Syrian Arab Republic Mobile'),(96393,'Syrian Arab Republic Mobile'),(96394,'Syrian Arab Republic Mobile'),(96395,'Syrian Arab Republic Mobile'),(96396,'Syrian Arab Republic Mobile'),(96398,'Syrian Arab Republic Mobile'),(96399,'Syrian Arab Republic Mobile'),(886,'Taiwan'),(88690,'Taiwan Mobile'),(88691,'Taiwan Mobile'),(88692,'Taiwan Mobile'),(88693,'Taiwan Mobile'),(88694,'Taiwan Mobile'),(88695,'Taiwan Mobile'),(88696,'Taiwan Mobile'),(88697,'Taiwan Mobile'),(88698,'Taiwan Mobile'),(992,'Tajikistan'),(9929,'Tajikistan Mobile'),(2556,'Tanzania Mobile'),(2557,'Tanzania Mobile'),(66,'Thailand'),(668,'Thailand Mobile'),(88216,'Thuraya'),(228,'Togo'),(2289,'Togo Mobile'),(676,'Tonga'),(67611,'Tonga Mobile'),(67612,'Tonga Mobile'),(67613,'Tonga Mobile'),(67614,'Tonga Mobile'),(67615,'Tonga Mobile'),(67616,'Tonga Mobile'),(67617,'Tonga Mobile'),(67618,'Tonga Mobile'),(67619,'Tonga Mobile'),(67645,'Tonga Mobile'),(67646,'Tonga Mobile'),(67647,'Tonga Mobile'),(67648,'Tonga Mobile'),(67649,'Tonga Mobile'),(67652,'Tonga Mobile'),(67653,'Tonga Mobile'),(67654,'Tonga Mobile'),(67655,'Tonga Mobile'),(67656,'Tonga Mobile'),(67657,'Tonga Mobile'),(67658,'Tonga Mobile'),(67659,'Tonga Mobile'),(67662,'Tonga Mobile'),(67663,'Tonga Mobile'),(67664,'Tonga Mobile'),(67665,'Tonga Mobile'),(67666,'Tonga Mobile'),(67667,'Tonga Mobile'),(67668,'Tonga Mobile'),(67675,'Tonga Mobile'),(67676,'Tonga Mobile'),(67677,'Tonga Mobile'),(67678,'Tonga Mobile'),(67681,'Tonga Mobile'),(67682,'Tonga Mobile'),(1868,'Trinidad and Tobago'),(186829,'Trinidad and Tobago Mobile'),(1868301,'Trinidad and Tobago Mobile'),(1868302,'Trinidad and Tobago Mobile'),(1868303,'Trinidad and Tobago Mobile'),(1868304,'Trinidad and Tobago Mobile'),(1868305,'Trinidad and Tobago Mobile'),(1868306,'Trinidad and Tobago Mobile'),(1868307,'Trinidad and Tobago Mobile'),(1868308,'Trinidad and Tobago Mobile'),(1868309,'Trinidad and Tobago Mobile'),(1868310,'Trinidad and Tobago Mobile'),(1868312,'Trinidad and Tobago Mobile'),(1868313,'Trinidad and Tobago Mobile'),(1868314,'Trinidad and Tobago Mobile'),(1868315,'Trinidad and Tobago Mobile'),(1868316,'Trinidad and Tobago Mobile'),(1868317,'Trinidad and Tobago Mobile'),(1868318,'Trinidad and Tobago Mobile'),(1868319,'Trinidad and Tobago Mobile'),(186832,'Trinidad and Tobago Mobile'),(186833,'Trinidad and Tobago Mobile'),(186834,'Trinidad and Tobago Mobile'),(186835,'Trinidad and Tobago Mobile'),(186836,'Trinidad and Tobago Mobile'),(186837,'Trinidad and Tobago Mobile'),(186838,'Trinidad and Tobago Mobile'),(186839,'Trinidad and Tobago Mobile'),(1868401,'Trinidad and Tobago Mobile'),(1868402,'Trinidad and Tobago Mobile'),(1868403,'Trinidad and Tobago Mobile'),(1868404,'Trinidad and Tobago Mobile'),(1868405,'Trinidad and Tobago Mobile'),(1868406,'Trinidad and Tobago Mobile'),(1868407,'Trinidad and Tobago Mobile'),(1868408,'Trinidad and Tobago Mobile'),(1868409,'Trinidad and Tobago Mobile'),(1868410,'Trinidad and Tobago Mobile'),(1868412,'Trinidad and Tobago Mobile'),(1868413,'Trinidad and Tobago Mobile'),(1868414,'Trinidad and Tobago Mobile'),(1868415,'Trinidad and Tobago Mobile'),(1868416,'Trinidad and Tobago Mobile'),(1868417,'Trinidad and Tobago Mobile'),(1868418,'Trinidad and Tobago Mobile'),(1868419,'Trinidad and Tobago Mobile'),(1868420,'Trinidad and Tobago Mobile'),(1868421,'Trinidad and Tobago Mobile'),(186846,'Trinidad and Tobago Mobile'),(186847,'Trinidad and Tobago Mobile'),(186848,'Trinidad and Tobago Mobile'),(186849,'Trinidad and Tobago Mobile'),(1868619,'Trinidad and Tobago Mobile'),(1868620,'Trinidad and Tobago Mobile'),(1868678,'Trinidad and Tobago Mobile'),(186868,'Trinidad and Tobago Mobile'),(1868701,'Trinidad and Tobago Mobile'),(1868702,'Trinidad and Tobago Mobile'),(1868703,'Trinidad and Tobago Mobile'),(1868704,'Trinidad and Tobago Mobile'),(1868705,'Trinidad and Tobago Mobile'),(1868706,'Trinidad and Tobago Mobile'),(1868707,'Trinidad and Tobago Mobile'),(1868708,'Trinidad and Tobago Mobile'),(1868709,'Trinidad and Tobago Mobile'),(1868710,'Trinidad and Tobago Mobile'),(1868712,'Trinidad and Tobago Mobile'),(1868713,'Trinidad and Tobago Mobile'),(1868714,'Trinidad and Tobago Mobile'),(1868715,'Trinidad and Tobago Mobile'),(1868716,'Trinidad and Tobago Mobile'),(1868717,'Trinidad and Tobago Mobile'),(1868718,'Trinidad and Tobago Mobile'),(1868719,'Trinidad and Tobago Mobile'),(186872,'Trinidad and Tobago Mobile'),(186873,'Trinidad and Tobago Mobile'),(186874,'Trinidad and Tobago Mobile'),(186875,'Trinidad and Tobago Mobile'),(186876,'Trinidad and Tobago Mobile'),(186877,'Trinidad and Tobago Mobile'),(186878,'Trinidad and Tobago Mobile'),(186879,'Trinidad and Tobago Mobile'),(2903,'Tristan da Cunha'),(216,'Tunisia'),(21620,'Tunisia Mobile'),(21621,'Tunisia Mobile'),(21622,'Tunisia Mobile'),(21623,'Tunisia Mobile'),(21624,'Tunisia Mobile'),(21625,'Tunisia Mobile'),(21690,'Tunisia Mobile'),(21691,'Tunisia Mobile'),(21693,'Tunisia Mobile'),(21694,'Tunisia Mobile'),(21695,'Tunisia Mobile'),(21696,'Tunisia Mobile'),(21697,'Tunisia Mobile'),(21698,'Tunisia Mobile'),(21699,'Tunisia Mobile'),(90,'Turkey'),(905,'Turkey Mobile'),(993,'Turkmenistan'),(993122,'Turkmenistan Mobile'),(9932221,'Turkmenistan Mobile'),(9932431,'Turkmenistan Mobile'),(9933221,'Turkmenistan Mobile'),(9934221,'Turkmenistan Mobile'),(9935221,'Turkmenistan Mobile'),(9936,'Turkmenistan Mobile'),(1649,'Turks and Caicos'),(1649231,'Turks and Caicos Mobile'),(1649232,'Turks and Caicos Mobile'),(1649241,'Turks and Caicos Mobile'),(1649242,'Turks and Caicos Mobile'),(1649243,'Turks and Caicos Mobile'),(1649244,'Turks and Caicos Mobile'),(1649245,'Turks and Caicos Mobile'),(1649249,'Turks and Caicos Mobile'),(1649331,'Turks and Caicos Mobile'),(1649332,'Turks and Caicos Mobile'),(1649333,'Turks and Caicos Mobile'),(1649341,'Turks and Caicos Mobile'),(1649342,'Turks and Caicos Mobile'),(1649343,'Turks and Caicos Mobile'),(1649344,'Turks and Caicos Mobile'),(1649345,'Turks and Caicos Mobile'),(1649431,'Turks and Caicos Mobile'),(1649432,'Turks and Caicos Mobile'),(1649441,'Turks and Caicos Mobile'),(1649442,'Turks and Caicos Mobile'),(1649724,'Turks and Caicos Mobile'),(688,'Tuvalu'),(256,'Uganda'),(25639,'Uganda Mobile'),(2567,'Uganda Mobile'),(256701,'Uganda Mobile'),(256702,'Uganda Mobile'),(256703,'Uganda Mobile'),(256704,'Uganda Mobile'),(25671,'Uganda Mobile'),(25675,'Uganda Mobile'),(25677,'Uganda Mobile'),(25678,'Uganda Mobile'),(380,'Ukraine'),(38039,'Ukraine Mobile'),(38050,'Ukraine Mobile'),(38063,'Ukraine Mobile'),(38066,'Ukraine Mobile'),(38067,'Ukraine Mobile'),(38068,'Ukraine Mobile'),(38091,'Ukraine Mobile'),(38092,'Ukraine Mobile'),(38093,'Ukraine Mobile'),(38094,'Ukraine Mobile'),(38095,'Ukraine Mobile'),(38096,'Ukraine Mobile'),(38097,'Ukraine Mobile'),(38098,'Ukraine Mobile'),(38099,'Ukraine Mobile'),(971,'United Arab Emirates'),(97150,'United Arab Emirates Mobile'),(97155,'United Arab Emirates Mobile'),(97156,'United Arab Emirates Mobile'),(44,'United Kingdom'),(441,'United Kingdom Landline'),(442,'United Kingdom Landline'),(443,'United Kingdom Landline'),(4470,'United Kingdom Personal Number'),(4475,'United Kingdom Mobile'),(4476,'United Kingdom Pager'),(4477,'United Kingdom Mobile'),(4478,'United Kingdom Mobile'),(4479,'United Kingdom Mobile'),(448,'United Kingdom Special-Rate'),(449,'United Kingdom Premium-Rate'),(1201,'United States'),(1202,'United States'),(1203,'United States'),(1205,'United States'),(1206,'United States'),(1207,'United States'),(1208,'United States'),(1209,'United States'),(1210,'United States'),(1212,'United States'),(1213,'United States'),(1214,'United States'),(1215,'United States'),(1216,'United States'),(1217,'United States'),(1218,'United States'),(1219,'United States'),(1224,'United States'),(1225,'United States'),(1227,'United States'),(1228,'United States'),(1229,'United States'),(1231,'United States'),(1234,'United States'),(1239,'United States'),(1240,'United States'),(1248,'United States'),(1251,'United States'),(1252,'United States'),(1253,'United States'),(1254,'United States'),(1256,'United States'),(1260,'United States'),(1262,'United States'),(1267,'United States'),(1269,'United States'),(1270,'United States'),(1276,'United States'),(1281,'United States'),(1283,'United States'),(1301,'United States'),(1302,'United States'),(1303,'United States'),(1304,'United States'),(1305,'United States'),(1307,'United States'),(1308,'United States'),(1309,'United States'),(1310,'United States'),(1312,'United States'),(1313,'United States'),(1314,'United States'),(1315,'United States'),(1316,'United States'),(1317,'United States'),(1318,'United States'),(1319,'United States'),(1320,'United States'),(1321,'United States'),(1323,'United States'),(1325,'United States'),(1330,'United States'),(1331,'United States'),(1334,'United States'),(1336,'United States'),(1337,'United States'),(1339,'United States'),(1341,'United States'),(1347,'United States'),(1351,'United States'),(1352,'United States'),(1360,'United States'),(1361,'United States'),(1369,'United States'),(1380,'United States'),(1385,'United States'),(1386,'United States'),(1401,'United States'),(1402,'United States'),(1404,'United States'),(1405,'United States'),(1406,'United States'),(1407,'United States'),(1408,'United States'),(1409,'United States'),(1410,'United States'),(1412,'United States'),(1413,'United States'),(1414,'United States'),(1415,'United States'),(1417,'United States'),(1419,'United States'),(1423,'United States'),(1424,'United States'),(1425,'United States'),(1430,'United States'),(1432,'United States'),(1434,'United States'),(1435,'United States'),(1440,'United States'),(1442,'United States'),(1443,'United States'),(1445,'United States'),(1447,'United States'),(1456,'United States'),(1464,'United States'),(1469,'United States'),(1470,'United States'),(1475,'United States'),(1478,'United States'),(1479,'United States'),(1480,'United States'),(1484,'United States'),(15,'United States'),(1501,'United States'),(1502,'United States'),(1503,'United States'),(1504,'United States'),(1505,'United States'),(1507,'United States'),(1508,'United States'),(1509,'United States'),(1510,'United States'),(1512,'United States'),(1513,'United States'),(1515,'United States'),(1516,'United States'),(1517,'United States'),(1518,'United States'),(1520,'United States'),(1530,'United States'),(1540,'United States'),(1541,'United States'),(1551,'United States'),(1555,'United States'),(1557,'United States'),(1559,'United States'),(1561,'United States'),(1562,'United States'),(1563,'United States'),(1564,'United States'),(1567,'United States'),(1570,'United States'),(1571,'United States'),(1573,'United States'),(1574,'United States'),(1575,'United States'),(1580,'United States'),(1585,'United States'),(1586,'United States'),(1601,'United States'),(1602,'United States'),(1603,'United States'),(1605,'United States'),(1606,'United States'),(1607,'United States'),(1608,'United States'),(1609,'United States'),(1610,'United States'),(1612,'United States'),(1614,'United States'),(1615,'United States'),(1616,'United States'),(1617,'United States'),(1618,'United States'),(1619,'United States'),(1620,'United States'),(1623,'United States'),(1626,'United States'),(1627,'United States'),(1628,'United States'),(1630,'United States'),(1631,'United States'),(1636,'United States'),(1641,'United States'),(1646,'United States'),(1650,'United States'),(1651,'United States'),(1657,'United States'),(1659,'United States'),(1660,'United States'),(1661,'United States'),(1662,'United States'),(1667,'United States'),(1669,'United States'),(1678,'United States'),(1679,'United States'),(1682,'United States'),(1689,'United States'),(17,'United States'),(1701,'United States'),(1702,'United States'),(1703,'United States'),(1704,'United States'),(1706,'United States'),(1707,'United States'),(1708,'United States'),(1710,'United States'),(1712,'United States'),(1713,'United States'),(1714,'United States'),(1715,'United States'),(1716,'United States'),(1717,'United States'),(1718,'United States'),(1719,'United States'),(1720,'United States'),(1724,'United States'),(1727,'United States'),(1730,'United States'),(1731,'United States'),(1732,'United States'),(1734,'United States'),(1737,'United States'),(1740,'United States'),(1747,'United States'),(1752,'United States'),(1754,'United States'),(1757,'United States'),(1760,'United States'),(1762,'United States'),(1763,'United States'),(1764,'United States'),(1765,'United States'),(1769,'United States'),(1770,'United States'),(1772,'United States'),(1773,'United States'),(1774,'United States'),(1775,'United States'),(1779,'United States'),(1781,'United States'),(1785,'United States'),(1786,'United States'),(1801,'United States'),(1802,'United States'),(1803,'United States'),(1804,'United States'),(1805,'United States'),(1806,'United States'),(1808,'United States'),(1810,'United States'),(1812,'United States'),(1813,'United States'),(1814,'United States'),(1815,'United States'),(1816,'United States'),(1817,'United States'),(1818,'United States'),(1828,'United States'),(1830,'United States'),(1831,'United States'),(1832,'United States'),(1835,'United States'),(1843,'United States'),(1845,'United States'),(1847,'United States'),(1848,'United States'),(1850,'United States'),(1856,'United States'),(1857,'United States'),(1858,'United States'),(1859,'United States'),(1860,'United States'),(1862,'United States'),(1863,'United States'),(1864,'United States'),(1865,'United States'),(1870,'United States'),(1872,'United States'),(1878,'United States'),(1901,'United States'),(1903,'United States'),(1904,'United States'),(1906,'United States'),(1908,'United States'),(1909,'United States'),(1910,'United States'),(1912,'United States'),(1913,'United States'),(1914,'United States'),(1915,'United States'),(1916,'United States'),(1917,'United States'),(1918,'United States'),(1919,'United States'),(1920,'United States'),(1925,'United States'),(1928,'United States'),(1931,'United States'),(1935,'United States'),(1936,'United States'),(1937,'United States'),(1940,'United States'),(1941,'United States'),(1947,'United States'),(1949,'United States'),(1951,'United States'),(1952,'United States'),(1954,'United States'),(1956,'United States'),(1959,'United States'),(1970,'United States'),(1971,'United States'),(1972,'United States'),(1973,'United States'),(1975,'United States'),(1978,'United States'),(1979,'United States'),(1980,'United States'),(1984,'United States'),(1985,'United States'),(1989,'United States'),(1907,'United States [ALASKA]'),(998722273,'Uzbekistan Mobile'),(998722274,'Uzbekistan Mobile'),(998722275,'Uzbekistan Mobile'),(998722276,'Uzbekistan Mobile'),(998722277,'Uzbekistan Mobile'),(998722278,'Uzbekistan Mobile'),(998722279,'Uzbekistan Mobile'),(998722295,'Uzbekistan Mobile'),(99872325,'Uzbekistan Mobile'),(99872326,'Uzbekistan Mobile'),(99872327,'Uzbekistan Mobile'),(99872328,'Uzbekistan Mobile'),(99872329,'Uzbekistan Mobile'),(99872360,'Uzbekistan Mobile'),(99872361,'Uzbekistan Mobile'),(99872362,'Uzbekistan Mobile'),(99872363,'Uzbekistan Mobile'),(99872364,'Uzbekistan Mobile'),(99872365,'Uzbekistan Mobile'),(99872366,'Uzbekistan Mobile'),(99872570,'Uzbekistan Mobile'),(99872575,'Uzbekistan Mobile'),(99872577,'Uzbekistan Mobile'),(99872579,'Uzbekistan Mobile'),(99873210,'Uzbekistan Mobile'),(99873211,'Uzbekistan Mobile'),(99873212,'Uzbekistan Mobile'),(99873213,'Uzbekistan Mobile'),(99873214,'Uzbekistan Mobile'),(99873215,'Uzbekistan Mobile'),(99873216,'Uzbekistan Mobile'),(99873221,'Uzbekistan Mobile'),(99873234,'Uzbekistan Mobile'),(99873236,'Uzbekistan Mobile'),(99873239,'Uzbekistan Mobile'),(99873271,'Uzbekistan Mobile'),(99873275,'Uzbekistan Mobile'),(99873279,'Uzbekistan Mobile'),(99873330,'Uzbekistan Mobile'),(99873333,'Uzbekistan Mobile'),(998735,'Uzbekistan Mobile'),(99873501,'Uzbekistan Mobile'),(99873502,'Uzbekistan Mobile'),(99873503,'Uzbekistan Mobile'),(99873504,'Uzbekistan Mobile'),(99873555,'Uzbekistan Mobile'),(99873557,'Uzbekistan Mobile'),(99873559,'Uzbekistan Mobile'),(99873590,'Uzbekistan Mobile'),(99873595,'Uzbekistan Mobile'),(99873599,'Uzbekistan Mobile'),(99873940,'Uzbekistan Mobile'),(99873941,'Uzbekistan Mobile'),(99873944,'Uzbekistan Mobile'),(99873955,'Uzbekistan Mobile'),(99873956,'Uzbekistan Mobile'),(99873966,'Uzbekistan Mobile'),(99874229,'Uzbekistan Mobile'),(99874250,'Uzbekistan Mobile'),(99874255,'Uzbekistan Mobile'),(99874257,'Uzbekistan Mobile'),(99874260,'Uzbekistan Mobile'),(99874261,'Uzbekistan Mobile'),(99874262,'Uzbekistan Mobile'),(99874263,'Uzbekistan Mobile'),(99874264,'Uzbekistan Mobile'),(99874265,'Uzbekistan Mobile'),(99874266,'Uzbekistan Mobile'),(99874267,'Uzbekistan Mobile'),(99874271,'Uzbekistan Mobile'),(99874272,'Uzbekistan Mobile'),(99874273,'Uzbekistan Mobile'),(99874274,'Uzbekistan Mobile'),(99874275,'Uzbekistan Mobile'),(99874277,'Uzbekistan Mobile'),(99874510,'Uzbekistan Mobile'),(99874580,'Uzbekistan Mobile'),(99874585,'Uzbekistan Mobile'),(99874775,'Uzbekistan Mobile'),(99874777,'Uzbekistan Mobile'),(99874778,'Uzbekistan Mobile'),(99874970,'Uzbekistan Mobile'),(99874971,'Uzbekistan Mobile'),(99874975,'Uzbekistan Mobile'),(99874976,'Uzbekistan Mobile'),(99874977,'Uzbekistan Mobile'),(99874978,'Uzbekistan Mobile'),(99874979,'Uzbekistan Mobile'),(99874980,'Uzbekistan Mobile'),(99874981,'Uzbekistan Mobile'),(99874982,'Uzbekistan Mobile'),(99874983,'Uzbekistan Mobile'),(99874984,'Uzbekistan Mobile'),(99874985,'Uzbekistan Mobile'),(99874986,'Uzbekistan Mobile'),(99874987,'Uzbekistan Mobile'),(99874988,'Uzbekistan Mobile'),(99874989,'Uzbekistan Mobile'),(99874990,'Uzbekistan Mobile'),(99874995,'Uzbekistan Mobile'),(99874999,'Uzbekistan Mobile'),(99875112,'Uzbekistan Mobile'),(99875222,'Uzbekistan Mobile'),(99875229,'Uzbekistan Mobile'),(99875244,'Uzbekistan Mobile'),(99875294,'Uzbekistan Mobile'),(9987531,'Uzbekistan Mobile'),(99875350,'Uzbekistan Mobile'),(99875355,'Uzbekistan Mobile'),(99875360,'Uzbekistan Mobile'),(99875363,'Uzbekistan Mobile'),(99875366,'Uzbekistan Mobile'),(99875380,'Uzbekistan Mobile'),(99875381,'Uzbekistan Mobile'),(99875382,'Uzbekistan Mobile'),(99875383,'Uzbekistan Mobile'),(99875384,'Uzbekistan Mobile'),(99875385,'Uzbekistan Mobile'),(99875386,'Uzbekistan Mobile'),(99875526,'Uzbekistan Mobile'),(99875527,'Uzbekistan Mobile'),(99875528,'Uzbekistan Mobile'),(99875529,'Uzbekistan Mobile'),(998762229,'Uzbekistan Mobile'),(998762246,'Uzbekistan Mobile'),(998762247,'Uzbekistan Mobile'),(998762248,'Uzbekistan Mobile'),(998762249,'Uzbekistan Mobile'),(998762257,'Uzbekistan Mobile'),(998762258,'Uzbekistan Mobile'),(998762259,'Uzbekistan Mobile'),(99876242,'Uzbekistan Mobile'),(99876243,'Uzbekistan Mobile'),(99876244,'Uzbekistan Mobile'),(99876390,'Uzbekistan Mobile'),(99876391,'Uzbekistan Mobile'),(99876392,'Uzbekistan Mobile'),(99876393,'Uzbekistan Mobile'),(99876394,'Uzbekistan Mobile'),(99876395,'Uzbekistan Mobile'),(99876396,'Uzbekistan Mobile'),(998764115,'Uzbekistan Mobile'),(998764116,'Uzbekistan Mobile'),(998764117,'Uzbekistan Mobile'),(998764118,'Uzbekistan Mobile'),(998764119,'Uzbekistan Mobile'),(998764171,'Uzbekistan Mobile'),(998764172,'Uzbekistan Mobile'),(998764173,'Uzbekistan Mobile'),(998764174,'Uzbekistan Mobile'),(998764175,'Uzbekistan Mobile'),(998764190,'Uzbekistan Mobile'),(998764191,'Uzbekistan Mobile'),(998764192,'Uzbekistan Mobile'),(998764193,'Uzbekistan Mobile'),(998764194,'Uzbekistan Mobile'),(998764198,'Uzbekistan Mobile'),(998764199,'Uzbekistan Mobile'),(99876535,'Uzbekistan Mobile'),(99876536,'Uzbekistan Mobile'),(99876537,'Uzbekistan Mobile'),(99876540,'Uzbekistan Mobile'),(99876544,'Uzbekistan Mobile'),(99876545,'Uzbekistan Mobile'),(99876550,'Uzbekistan Mobile'),(99876551,'Uzbekistan Mobile'),(99876552,'Uzbekistan Mobile'),(99876590,'Uzbekistan Mobile'),(99876595,'Uzbekistan Mobile'),(99879221,'Uzbekistan Mobile'),(998792225,'Uzbekistan Mobile'),(998792226,'Uzbekistan Mobile'),(998792227,'Uzbekistan Mobile'),(99879228,'Uzbekistan Mobile'),(99879320,'Uzbekistan Mobile'),(99879321,'Uzbekistan Mobile'),(99879322,'Uzbekistan Mobile'),(99879323,'Uzbekistan Mobile'),(99879324,'Uzbekistan Mobile'),(99879370,'Uzbekistan Mobile'),(99879371,'Uzbekistan Mobile'),(99879372,'Uzbekistan Mobile'),(99879377,'Uzbekistan Mobile'),(99879570,'Uzbekistan Mobile'),(998795726,'Uzbekistan Mobile'),(998795727,'Uzbekistan Mobile'),(998795728,'Uzbekistan Mobile'),(998795729,'Uzbekistan Mobile'),(99879575,'Uzbekistan Mobile'),(99879576,'Uzbekistan Mobile'),(998795790,'Uzbekistan Mobile'),(99879725,'Uzbekistan Mobile'),(99879727,'Uzbekistan Mobile'),(99879740,'Uzbekistan Mobile'),(99879744,'Uzbekistan Mobile'),(99879747,'Uzbekistan Mobile'),(99890,'Uzbekistan Mobile'),(99891,'Uzbekistan Mobile'),(99892,'Uzbekistan Mobile'),(99893,'Uzbekistan Mobile'),(99895,'Uzbekistan Mobile'),(99897,'Uzbekistan Mobile'),(99898,'Uzbekistan Mobile'),(678,'Vanuatu'),(67854,'Vanuatu Mobile'),(67855,'Vanuatu Mobile'),(67877,'Vanuatu Mobile'),(58,'Venezuela'),(584,'Venezuela Mobile'),(84,'Viet Nam'),(84122,'Viet Nam Mobile'),(84123,'Viet Nam Mobile'),(84126,'Viet Nam Mobile'),(84166,'Viet Nam Mobile'),(84168,'Viet Nam Mobile'),(84169,'Viet Nam Mobile'),(8490,'Viet Nam Mobile'),(8491,'Viet Nam Mobile'),(8492,'Viet Nam Mobile'),(8493,'Viet Nam Mobile'),(8494,'Viet Nam Mobile'),(8495,'Viet Nam Mobile'),(8496,'Viet Nam Mobile'),(8497,'Viet Nam Mobile'),(8498,'Viet Nam Mobile'),(8499,'Viet Nam Mobile'),(681,'Wallis and Futuna'),(967,'Yemen'),(96758,'Yemen Mobile'),(96771,'Yemen Mobile'),(96773,'Yemen Mobile'),(96777,'Yemen Mobile'),(260,'Zambia'),(26095,'Zambia Mobile'),(26096,'Zambia Mobile'),(26097,'Zambia Mobile'),(26098,'Zambia Mobile'),(26099,'Zambia Mobile'),(263,'Zimbabwe'),(26311,'Zimbabwe Mobile'),(26323,'Zimbabwe Mobile'),(26391,'Zimbabwe Mobile'),(1,'USA'),(379,'Vatican City'),(998714,'Uzbekistan Tashkent'),(998711,'Uzbekistan Tashkent'),(998713,'Uzbekistan Tashkent'),(998712,'Uzbekistan Tashkent'),(99899,'Uzbekistan Mobile'),(998,'Uzbekistan');
/*!40000 ALTER TABLE `cc_prefix` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_provider`
--

DROP TABLE IF EXISTS `cc_provider`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_provider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_name` char(30) COLLATE utf8_bin NOT NULL,
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` mediumtext COLLATE utf8_bin,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cons_cc_provider_provider_name` (`provider_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_provider`
--

LOCK TABLES `cc_provider` WRITE;
/*!40000 ALTER TABLE `cc_provider` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_provider` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_ratecard`
--

DROP TABLE IF EXISTS `cc_ratecard`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_ratecard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idtariffplan` int(11) NOT NULL DEFAULT '0',
  `dialprefix` char(30) COLLATE utf8_bin NOT NULL,
  `buyrate` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `buyrateinitblock` int(11) NOT NULL DEFAULT '0',
  `buyrateincrement` int(11) NOT NULL DEFAULT '0',
  `rateinitial` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `initblock` int(11) NOT NULL DEFAULT '0',
  `billingblock` int(11) NOT NULL DEFAULT '0',
  `connectcharge` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `disconnectcharge` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `stepchargea` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `chargea` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `timechargea` int(11) NOT NULL DEFAULT '0',
  `billingblocka` int(11) NOT NULL DEFAULT '0',
  `stepchargeb` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `chargeb` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `timechargeb` int(11) NOT NULL DEFAULT '0',
  `billingblockb` int(11) NOT NULL DEFAULT '0',
  `stepchargec` float NOT NULL DEFAULT '0',
  `chargec` float NOT NULL DEFAULT '0',
  `timechargec` int(11) NOT NULL DEFAULT '0',
  `billingblockc` int(11) NOT NULL DEFAULT '0',
  `startdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `stopdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `starttime` smallint(5) unsigned DEFAULT '0',
  `endtime` smallint(5) unsigned DEFAULT '10079',
  `id_trunk` int(11) DEFAULT '-1',
  `musiconhold` char(100) COLLATE utf8_bin NOT NULL,
  `id_outbound_cidgroup` int(11) DEFAULT '-1',
  `rounding_calltime` int(11) NOT NULL DEFAULT '0',
  `rounding_threshold` int(11) NOT NULL DEFAULT '0',
  `additional_block_charge` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `additional_block_charge_time` int(11) NOT NULL DEFAULT '0',
  `tag` char(50) COLLATE utf8_bin DEFAULT NULL,
  `disconnectcharge_after` int(11) NOT NULL DEFAULT '0',
  `is_merged` int(11) DEFAULT '0',
  `additional_grace` int(11) NOT NULL DEFAULT '0',
  `minimal_cost` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `announce_time_correction` decimal(5,3) NOT NULL DEFAULT '1.000',
  `destination` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ind_cc_ratecard_dialprefix` (`dialprefix`),
  KEY `idtariffplan_index` (`idtariffplan`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_ratecard`
--

LOCK TABLES `cc_ratecard` WRITE;
/*!40000 ALTER TABLE `cc_ratecard` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_ratecard` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `cc_ratecard_validate_regex_ins` BEFORE INSERT ON `cc_ratecard`
 FOR EACH ROW BEGIN
  DECLARE valid INTEGER;
  SELECT '0' REGEXP REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(CONCAT('^', NEW.dialprefix, '$'), 'X', '[0-9]'), 'Z', '[1-9]'), 'N', '[2-9]'), '.', '.+'), '_', '') INTO valid;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `cc_ratecard_validate_regex_upd` BEFORE UPDATE ON `cc_ratecard`
 FOR EACH ROW BEGIN
  DECLARE valid INTEGER;
  SELECT '0' REGEXP REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(CONCAT('^', NEW.dialprefix, '$'), 'X', '[0-9]'), 'Z', '[1-9]'), 'N', '[2-9]'), '.', '.+'), '_', '') INTO valid;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `cc_receipt`
--

DROP TABLE IF EXISTS `cc_receipt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_receipt` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_card` bigint(20) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(50) COLLATE utf8_bin NOT NULL,
  `description` mediumtext COLLATE utf8_bin NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_receipt`
--

LOCK TABLES `cc_receipt` WRITE;
/*!40000 ALTER TABLE `cc_receipt` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_receipt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_receipt_item`
--

DROP TABLE IF EXISTS `cc_receipt_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_receipt_item` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_receipt` bigint(20) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `price` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `description` mediumtext COLLATE utf8_bin NOT NULL,
  `id_ext` bigint(20) DEFAULT NULL,
  `type_ext` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_receipt_item`
--

LOCK TABLES `cc_receipt_item` WRITE;
/*!40000 ALTER TABLE `cc_receipt_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_receipt_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_remittance_request`
--

DROP TABLE IF EXISTS `cc_remittance_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_remittance_request` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_agent` bigint(20) NOT NULL,
  `amount` decimal(15,5) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_remittance_request`
--

LOCK TABLES `cc_remittance_request` WRITE;
/*!40000 ALTER TABLE `cc_remittance_request` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_remittance_request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_restricted_phonenumber`
--

DROP TABLE IF EXISTS `cc_restricted_phonenumber`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_restricted_phonenumber` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `number` varchar(50) COLLATE utf8_bin NOT NULL,
  `id_card` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_restricted_phonenumber`
--

LOCK TABLES `cc_restricted_phonenumber` WRITE;
/*!40000 ALTER TABLE `cc_restricted_phonenumber` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_restricted_phonenumber` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_server_group`
--

DROP TABLE IF EXISTS `cc_server_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_server_group` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  `description` mediumtext COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_server_group`
--

LOCK TABLES `cc_server_group` WRITE;
/*!40000 ALTER TABLE `cc_server_group` DISABLE KEYS */;
INSERT INTO `cc_server_group` VALUES (1,'default','default group of server');
/*!40000 ALTER TABLE `cc_server_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_server_manager`
--

DROP TABLE IF EXISTS `cc_server_manager`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_server_manager` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_group` int(11) DEFAULT '1',
  `server_ip` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `manager_host` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `manager_username` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `manager_secret` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `lasttime_used` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_server_manager`
--

LOCK TABLES `cc_server_manager` WRITE;
/*!40000 ALTER TABLE `cc_server_manager` DISABLE KEYS */;
INSERT INTO `cc_server_manager` VALUES (1,1,'localhost','localhost','myasterisk','mycode','2009-05-15 07:38:43');
/*!40000 ALTER TABLE `cc_server_manager` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_service`
--

DROP TABLE IF EXISTS `cc_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_service` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` char(100) COLLATE utf8_bin NOT NULL,
  `amount` float NOT NULL,
  `period` int(11) NOT NULL DEFAULT '1',
  `rule` int(11) NOT NULL DEFAULT '0',
  `daynumber` int(11) NOT NULL DEFAULT '0',
  `stopmode` int(11) NOT NULL DEFAULT '0',
  `maxnumbercycle` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `numberofrun` int(11) NOT NULL DEFAULT '0',
  `datecreate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `datelastrun` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `emailreport` char(100) COLLATE utf8_bin NOT NULL,
  `totalcredit` float NOT NULL DEFAULT '0',
  `totalcardperform` int(11) NOT NULL DEFAULT '0',
  `operate_mode` tinyint(4) DEFAULT '0',
  `dialplan` int(11) DEFAULT '0',
  `use_group` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_service`
--

LOCK TABLES `cc_service` WRITE;
/*!40000 ALTER TABLE `cc_service` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_service_report`
--

DROP TABLE IF EXISTS `cc_service_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_service_report` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cc_service_id` bigint(20) NOT NULL,
  `daterun` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `totalcardperform` int(11) DEFAULT NULL,
  `totalcredit` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_service_report`
--

LOCK TABLES `cc_service_report` WRITE;
/*!40000 ALTER TABLE `cc_service_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_service_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_sip_buddies`
--

DROP TABLE IF EXISTS `cc_sip_buddies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_sip_buddies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cc_card` int(11) NOT NULL DEFAULT '0',
  `name` varchar(80) COLLATE utf8_bin NOT NULL,
  `accountcode` varchar(20) COLLATE utf8_bin NOT NULL,
  `regexten` varchar(20) COLLATE utf8_bin NOT NULL,
  `amaflags` char(7) COLLATE utf8_bin DEFAULT NULL,
  `callgroup` char(10) COLLATE utf8_bin DEFAULT NULL,
  `callerid` varchar(80) COLLATE utf8_bin NOT NULL,
  `canreinvite` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT 'YES',
  `context` varchar(80) COLLATE utf8_bin NOT NULL,
  `DEFAULTip` char(15) COLLATE utf8_bin DEFAULT NULL,
  `dtmfmode` char(7) COLLATE utf8_bin NOT NULL DEFAULT 'RFC2833',
  `fromuser` varchar(80) COLLATE utf8_bin NOT NULL,
  `fromdomain` varchar(80) COLLATE utf8_bin NOT NULL,
  `host` varchar(31) COLLATE utf8_bin NOT NULL,
  `insecure` varchar(20) COLLATE utf8_bin NOT NULL,
  `language` char(2) COLLATE utf8_bin DEFAULT NULL,
  `mailbox` varchar(50) COLLATE utf8_bin NOT NULL,
  `md5secret` varchar(80) COLLATE utf8_bin NOT NULL,
  `nat` char(3) COLLATE utf8_bin DEFAULT 'yes',
  `deny` varchar(95) COLLATE utf8_bin NOT NULL,
  `permit` varchar(95) COLLATE utf8_bin DEFAULT NULL,
  `mask` varchar(95) COLLATE utf8_bin NOT NULL,
  `pickupgroup` char(10) COLLATE utf8_bin DEFAULT NULL,
  `port` char(5) COLLATE utf8_bin NOT NULL DEFAULT '',
  `qualify` char(7) COLLATE utf8_bin DEFAULT 'yes',
  `restrictcid` char(1) COLLATE utf8_bin DEFAULT NULL,
  `rtptimeout` char(3) COLLATE utf8_bin DEFAULT NULL,
  `rtpholdtimeout` char(3) COLLATE utf8_bin DEFAULT NULL,
  `secret` varchar(80) COLLATE utf8_bin NOT NULL,
  `type` char(6) COLLATE utf8_bin NOT NULL DEFAULT 'friend',
  `username` varchar(80) COLLATE utf8_bin NOT NULL,
  `disallow` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT 'ALL',
  `allow` varchar(100) COLLATE utf8_bin NOT NULL,
  `musiconhold` varchar(100) COLLATE utf8_bin NOT NULL,
  `regseconds` int(11) NOT NULL DEFAULT '0',
  `ipaddr` char(15) COLLATE utf8_bin NOT NULL DEFAULT '',
  `cancallforward` char(3) COLLATE utf8_bin DEFAULT 'yes',
  `fullcontact` varchar(80) COLLATE utf8_bin NOT NULL,
  `setvar` varchar(100) COLLATE utf8_bin NOT NULL,
  `regserver` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `lastms` varchar(11) COLLATE utf8_bin DEFAULT NULL,
  `defaultuser` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `auth` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `subscribemwi` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `vmexten` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '',
  `cid_number` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `callingpres` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '',
  `usereqphone` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `incominglimit` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `subscribecontext` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `musicclass` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '',
  `mohsuggest` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '',
  `allowtransfer` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '',
  `autoframing` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `maxcallbitrate` varchar(15) COLLATE utf8_bin NOT NULL DEFAULT '',
  `outboundproxy` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `rtpkeepalive` varchar(15) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `useragent` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cons_cc_sip_buddies_name` (`name`),
  KEY `name` (`name`),
  KEY `host` (`host`),
  KEY `ipaddr` (`ipaddr`),
  KEY `port` (`port`),
  KEY `sip_friend_hp_index` (`host`,`port`),
  KEY `sip_friend_ip_index` (`ipaddr`,`port`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_sip_buddies`
--

LOCK TABLES `cc_sip_buddies` WRITE;
/*!40000 ALTER TABLE `cc_sip_buddies` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_sip_buddies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `cc_sip_buddies_empty`
--

DROP TABLE IF EXISTS `cc_sip_buddies_empty`;
/*!50001 DROP VIEW IF EXISTS `cc_sip_buddies_empty`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `cc_sip_buddies_empty` (
  `id` int(11),
  `id_cc_card` int(11),
  `name` varchar(80),
  `accountcode` varchar(20),
  `regexten` varchar(20),
  `amaflags` char(7),
  `callgroup` char(10),
  `callerid` varchar(80),
  `canreinvite` varchar(20),
  `context` varchar(80),
  `DEFAULTip` char(15),
  `dtmfmode` char(7),
  `fromuser` varchar(80),
  `fromdomain` varchar(80),
  `host` varchar(31),
  `insecure` varchar(20),
  `language` char(2),
  `mailbox` varchar(50),
  `md5secret` varchar(80),
  `nat` char(3),
  `permit` varchar(95),
  `deny` varchar(95),
  `mask` varchar(95),
  `pickupgroup` char(10),
  `port` char(5),
  `qualify` char(7),
  `restrictcid` char(1),
  `rtptimeout` char(3),
  `rtpholdtimeout` char(3),
  `secret` char(0),
  `type` char(6),
  `username` varchar(80),
  `disallow` varchar(100),
  `allow` varchar(100),
  `musiconhold` varchar(100),
  `regseconds` int(11),
  `ipaddr` char(15),
  `cancallforward` char(3),
  `fullcontact` varchar(80),
  `setvar` varchar(100)
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `cc_speeddial`
--

DROP TABLE IF EXISTS `cc_speeddial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_speeddial` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_cc_card` bigint(20) NOT NULL DEFAULT '0',
  `phone` varchar(100) COLLATE utf8_bin NOT NULL,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `speeddial` int(11) DEFAULT '0',
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cons_cc_speeddial_id_cc_card_speeddial` (`id_cc_card`,`speeddial`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_speeddial`
--

LOCK TABLES `cc_speeddial` WRITE;
/*!40000 ALTER TABLE `cc_speeddial` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_speeddial` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_status_log`
--

DROP TABLE IF EXISTS `cc_status_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_status_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL,
  `id_cc_card` bigint(20) NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_status_log`
--

LOCK TABLES `cc_status_log` WRITE;
/*!40000 ALTER TABLE `cc_status_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_status_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_subscription_service`
--

DROP TABLE IF EXISTS `cc_subscription_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_subscription_service` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `label` varchar(200) COLLATE utf8_bin NOT NULL,
  `fee` float NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `numberofrun` int(11) NOT NULL DEFAULT '0',
  `datecreate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `datelastrun` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `emailreport` varchar(100) COLLATE utf8_bin NOT NULL,
  `totalcredit` float NOT NULL DEFAULT '0',
  `totalcardperform` int(11) NOT NULL DEFAULT '0',
  `startdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `stopdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_subscription_service`
--

LOCK TABLES `cc_subscription_service` WRITE;
/*!40000 ALTER TABLE `cc_subscription_service` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_subscription_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_subscription_signup`
--

DROP TABLE IF EXISTS `cc_subscription_signup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_subscription_signup` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `label` varchar(50) COLLATE utf8_bin NOT NULL,
  `id_subscription` bigint(20) DEFAULT NULL,
  `description` varchar(500) COLLATE utf8_bin DEFAULT NULL,
  `enable` tinyint(4) NOT NULL DEFAULT '1',
  `id_callplan` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_subscription_signup`
--

LOCK TABLES `cc_subscription_signup` WRITE;
/*!40000 ALTER TABLE `cc_subscription_signup` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_subscription_signup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_support`
--

DROP TABLE IF EXISTS `cc_support`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_support` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `email` varchar(70) COLLATE utf8_bin DEFAULT NULL,
  `language` char(5) COLLATE utf8_bin NOT NULL DEFAULT 'en',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_support`
--

LOCK TABLES `cc_support` WRITE;
/*!40000 ALTER TABLE `cc_support` DISABLE KEYS */;
INSERT INTO `cc_support` VALUES (1,'DEFAULT',NULL,'en');
/*!40000 ALTER TABLE `cc_support` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_support_component`
--

DROP TABLE IF EXISTS `cc_support_component`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_support_component` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `id_support` smallint(5) NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `activated` smallint(6) NOT NULL DEFAULT '1',
  `type_user` tinyint(4) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_support_component`
--

LOCK TABLES `cc_support_component` WRITE;
/*!40000 ALTER TABLE `cc_support_component` DISABLE KEYS */;
INSERT INTO `cc_support_component` VALUES (1,1,'DEFAULT',1,2);
/*!40000 ALTER TABLE `cc_support_component` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_system_log`
--

DROP TABLE IF EXISTS `cc_system_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_system_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL DEFAULT '0',
  `loglevel` int(11) NOT NULL DEFAULT '0',
  `action` text COLLATE utf8_bin NOT NULL,
  `description` mediumtext COLLATE utf8_bin,
  `data` blob,
  `tablename` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `pagename` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `ipaddress` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `agent` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_system_log`
--

LOCK TABLES `cc_system_log` WRITE;
/*!40000 ALTER TABLE `cc_system_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_system_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_tariffgroup`
--

DROP TABLE IF EXISTS `cc_tariffgroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_tariffgroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL DEFAULT '0',
  `idtariffplan` int(11) NOT NULL DEFAULT '0',
  `tariffgroupname` char(50) COLLATE utf8_bin NOT NULL,
  `lcrtype` int(11) NOT NULL DEFAULT '0',
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `removeinterprefix` int(11) NOT NULL DEFAULT '0',
  `id_cc_package_offer` bigint(20) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_tariffgroup`
--

LOCK TABLES `cc_tariffgroup` WRITE;
/*!40000 ALTER TABLE `cc_tariffgroup` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_tariffgroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_tariffgroup_plan`
--

DROP TABLE IF EXISTS `cc_tariffgroup_plan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_tariffgroup_plan` (
  `idtariffgroup` int(11) NOT NULL,
  `idtariffplan` int(11) NOT NULL,
  PRIMARY KEY (`idtariffgroup`,`idtariffplan`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_tariffgroup_plan`
--

LOCK TABLES `cc_tariffgroup_plan` WRITE;
/*!40000 ALTER TABLE `cc_tariffgroup_plan` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_tariffgroup_plan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_tariffplan`
--

DROP TABLE IF EXISTS `cc_tariffplan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_tariffplan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL DEFAULT '0',
  `tariffname` char(50) COLLATE utf8_bin NOT NULL,
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `startingdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expirationdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `description` mediumtext COLLATE utf8_bin,
  `id_trunk` int(11) DEFAULT '0',
  `secondusedreal` int(11) DEFAULT '0',
  `secondusedcarrier` int(11) DEFAULT '0',
  `secondusedratecard` int(11) DEFAULT '0',
  `reftariffplan` int(11) DEFAULT '0',
  `idowner` int(11) DEFAULT '0',
  `dnidprefix` char(30) COLLATE utf8_bin NOT NULL DEFAULT 'all',
  `calleridprefix` char(30) COLLATE utf8_bin NOT NULL DEFAULT 'all',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cons_cc_tariffplan_iduser_tariffname` (`iduser`,`tariffname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_tariffplan`
--

LOCK TABLES `cc_tariffplan` WRITE;
/*!40000 ALTER TABLE `cc_tariffplan` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_tariffplan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_templatemail`
--

DROP TABLE IF EXISTS `cc_templatemail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_templatemail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_language` char(20) COLLATE utf8_bin NOT NULL DEFAULT 'en',
  `mailtype` char(50) COLLATE utf8_bin DEFAULT NULL,
  `fromemail` char(70) COLLATE utf8_bin DEFAULT NULL,
  `fromname` char(70) COLLATE utf8_bin DEFAULT NULL,
  `subject` varchar(130) COLLATE utf8_bin DEFAULT NULL,
  `messagetext` varchar(3000) COLLATE utf8_bin DEFAULT NULL,
  `messagehtml` varchar(3000) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cons_cc_templatemail_id_language` (`mailtype`,`id_language`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_templatemail`
--

LOCK TABLES `cc_templatemail` WRITE;
/*!40000 ALTER TABLE `cc_templatemail` DISABLE KEYS */;
INSERT INTO `cc_templatemail` VALUES (1,'en','signup','info@mydomainname.com','COMPANY NAME','SIGNUP CONFIRMATION','\nThank you for registering with us\n\nPlease click on below link to activate your account.\n\nhttp://mydomainname.com/A2Billing_UI/signup/activate.php?key=$loginkey$\n\nPlease make sure you active your account by making payment to us either by\ncredit card, wire transfer, money order, cheque, and western union money\ntransfer, money Gram, and Pay pal.\n\n\nKind regards,\nCall Labs\n',''),(2,'en','reminder','info@mydomainname.com','COMPANY NAME','Your COMPANY NAME account $cardnumber$ is low on credit ($currency$ $c','\n\nYour COMPANY NAME Account number $cardnumber$ is running low on credit.\n\nThere is currently only $creditcurrency$ $currency$ left on your account which is lower than the warning level defined ($credit_notification$)\n\n\nPlease top up your account ASAP to ensure continued service\n\nIf you no longer wish to receive these notifications or would like to change the balance amount at which these warnings are generated,\nplease connect on your myaccount panel and change the appropriate parameters\n\n\nyour account information :\nYour account number for VOIP authentication : $cardnumber$\n\nhttp://myaccount.mydomainname.com/\nYour account login : $login$\nYour account password : $password$\n\n\nThanks,\n/COMPANY NAME Team\n-------------------------------------\nhttp://www.mydomainname.com\n ',''),(3,'en','forgetpassword','info@mydomainname.com','COMPANY NAME','Login Information','Your login information is as below:\n\nYour account is $cardnumber$\n\nYour password is $password$\n\nYour login is $login$\n\nhttp://mydomainname.com/A2BCustomer_UI/\n\nKind regards,\nCall Labs\n',''),(4,'en','signupconfirmed','info@mydomainname.com','COMPANY NAME','SIGNUP CONFIRMATION','Thank you for registering with us\n\nPlease make sure you active your account by making payment to us either by\ncredit card, wire transfer, money order, cheque, and western union money\ntransfer, money Gram, and Pay pal.\n\nYour account is $cardnumber$\n\nYour password is $password$\n\nTo go to your account :\nhttp://mydomainname.com/customer/\n\nKind regards,\nCall Labs\n',''),(5,'en','epaymentverify','info@mydomainname.com','COMPANY NAME','Epayment Gateway Security Verification Failed','Dear Administrator\n\nPlease check the Epayment Log, System has logged a Epayment Security failure. that may be a possible attack on epayment processing.\n\nTime of Transaction: $time$\nPayment Gateway: $paymentgateway$\nAmount: $itemAmount$\n\n\n\nKind regards,\nCall Labs\n',''),(6,'en','payment','info@mydomainname.com','COMPANY NAME','PAYMENT CONFIRMATION','Thank you for shopping at COMPANY NAME.\n\nShopping details is as below.\n\nItem Name = <b>$itemName$</b>\nItem ID = <b>$itemID$</b>\nAmount = <b>$itemAmount$</b>\nPayment Method = <b>$paymentMethod$</b>\nStatus = <b>$paymentStatus$</b>\n\n\nKind regards,\nCall Labs\n',''),(13,'en','invoice_to_pay','info@mydomainname.com','COMPANY NAME','Invoice to pay Ref: $invoice_reference$','New Invoice send with the reference : $invoice_reference$ .\n \n    Title : $invoice_title$ .\n Description : $invoice_description$\n \n    TOTAL (exclude VAT) : $invoice_total$  $base_currency$\n TOTAL (invclude VAT) : $invoice_total_vat$ $base_currency$ \n\n \n    TOTAL TO PAY : $invoice_total_vat$ $base_currency$\n\n \n    You can check and pay this invoice by your account on the web interface : http://mydomainname.com/customer/  ',NULL),(8,'en','did_paid','info@mydomainname.com','COMPANY NAME','DID notification - ($did$)','BALANCE REMAINING $balance_remaining$ $base_currency$\n\nAn automatic taking away of : $did_cost$ $base_currency$ has been carry out of your account to pay your DID ($did$)\n\nMonthly cost for DID : $did_cost$ $base_currency$\n\n',NULL),(9,'en','did_unpaid','info@mydomainname.com','COMPANY NAME','DID notification - ($did$)','BALANCE REMAINING $balance_remaining$ $base_currency$\n\nYour credit is not enough to pay your DID number ($did$), the monthly cost is : $did_cost$ $base_currency$\n\nYou have $days_remaining$ days to pay the invoice (REF: $invoice_ref$ ) or the DID will be automatically released \n\n',NULL),(10,'en','did_released','info@mydomainname.com','COMPANY NAME','DID released - ($did$)','The DID $did$ has been automatically released!\n\n',NULL),(11,'en','new_ticket','info@mydomainname.com','COMPANY NAME','Support Ticket #$ticket_id$','New Ticket Open (#$ticket_id$) From $ticket_owner$.\n Title : $ticket_title$\n Priority : $ticket_priority$ \n Status : $ticket_status$ \n Description : $ticket_description$ \n',NULL),(12,'en','modify_ticket','info@mydomainname.com','COMPANY NAME','Support Ticket #$ticket_id$','Ticket modified (#$ticket_id$) By $comment_creator$.\n Ticket Status -> $ticket_status$\n Description : $comment_description$ \n',NULL),(14,'en','subscription_paid','info@mydomainname.com','COMPANY NAME','Subscription notification - $subscription_label$ ($subscription_id$)','BALANCE  $credit$ $base_currency$\n\n\nA decrement of: $subscription_fee$ $base_currency$ has removed from your account to pay your service. ($subscription_label$)\n\n\nthe monthly cost is : $subscription_fee$\n\n',NULL),(15,'en','subscription_unpaid','info@mydomainname.com','COMPANY NAME','Subscription notification - $subscription_label$ ($subscription_id$)','BALANCE $credit$ $base_currency$\n\n\nYou do not have enough credit to pay your subscription,($subscription_label$), the monthly cost is : $subscription_fee$ $base_currency$\n\n\nYou have $days_remaining$ days to pay the invoice (REF: $invoice_ref$ ) or your service may cease \n\n',NULL),(16,'en','subscription_disable_card','info@mydomainname.com','COMPANY NAME','Service deactivated - unpaid service $subscription_label$ ($subscription_id$)','The account has been automatically deactivated until the invoice is settled.\n\n',NULL);
/*!40000 ALTER TABLE `cc_templatemail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_ticket`
--

DROP TABLE IF EXISTS `cc_ticket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_ticket` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `id_component` smallint(5) NOT NULL,
  `title` varchar(100) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin,
  `priority` smallint(6) NOT NULL DEFAULT '0',
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator` bigint(20) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `creator_type` tinyint(4) NOT NULL DEFAULT '0',
  `viewed_cust` tinyint(4) NOT NULL DEFAULT '1',
  `viewed_agent` tinyint(4) NOT NULL DEFAULT '1',
  `viewed_admin` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_ticket`
--

LOCK TABLES `cc_ticket` WRITE;
/*!40000 ALTER TABLE `cc_ticket` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_ticket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_ticket_comment`
--

DROP TABLE IF EXISTS `cc_ticket_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_ticket_comment` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_ticket` bigint(10) NOT NULL,
  `description` text COLLATE utf8_bin,
  `creator` bigint(20) NOT NULL,
  `creator_type` tinyint(4) NOT NULL DEFAULT '0',
  `viewed_cust` tinyint(4) NOT NULL DEFAULT '1',
  `viewed_agent` tinyint(4) NOT NULL DEFAULT '1',
  `viewed_admin` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_ticket_comment`
--

LOCK TABLES `cc_ticket_comment` WRITE;
/*!40000 ALTER TABLE `cc_ticket_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_ticket_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_timezone`
--

DROP TABLE IF EXISTS `cc_timezone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_timezone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gmtzone` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `gmttime` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `gmtoffset` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=76 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_timezone`
--

LOCK TABLES `cc_timezone` WRITE;
/*!40000 ALTER TABLE `cc_timezone` DISABLE KEYS */;
INSERT INTO `cc_timezone` VALUES (1,'(GMT-12:00) International Date Line West','GMT-12:00',-43200),(2,'(GMT-11:00) Midway Island, Samoa','GMT-11:00',-39600),(3,'(GMT-10:00) Hawaii','GMT-10:00',-36000),(4,'(GMT-09:00) Alaska','GMT-09:00',-32400),(5,'(GMT-08:00) Pacific Time (US & Canada) Tijuana','GMT-08:00',-28800),(6,'(GMT-07:00) Arizona','GMT-07:00',-25200),(7,'(GMT-07:00) Chihuahua, La Paz, Mazatlan','GMT-07:00',-25200),(8,'(GMT-07:00) Mountain Time(US & Canada)','GMT-07:00',-25200),(9,'(GMT-06:00) Central America','GMT-06:00',-21600),(10,'(GMT-06:00) Central Time (US & Canada)','GMT-06:00',-21600),(11,'(GMT-06:00) Guadalajara, Mexico City, Monterrey','GMT-06:00',-21600),(12,'(GMT-06:00) Saskatchewan','GMT-06:00',-21600),(13,'(GMT-05:00) Bogota, Lima, Quito','GMT-05:00',-18000),(14,'(GMT-05:00) Eastern Time (US & Canada)','GMT-05:00',-18000),(15,'(GMT-05:00) Indiana (East)','GMT-05:00',-18000),(16,'(GMT-04:00) Atlantic Time (Canada)','GMT-04:00',-14400),(17,'(GMT-04:00) Caracas, La Paz','GMT-04:00',-14400),(18,'(GMT-04:00) Santiago','GMT-04:00',-14400),(19,'(GMT-03:30) NewFoundland','GMT-03:30',-12600),(20,'(GMT-03:00) Brasillia','GMT-03:00',-10800),(21,'(GMT-03:00) Buenos Aires, Georgetown','GMT-03:00',-10800),(22,'(GMT-03:00) Greenland','GMT-03:00',-10800),(23,'(GMT-03:00) Mid-Atlantic','GMT-03:00',-10800),(24,'(GMT-01:00) Azores','GMT-01:00',-3600),(25,'(GMT-01:00) Cape Verd Is.','GMT-01:00',-3600),(26,'(GMT) Casablanca, Monrovia','GMT+00:00',0),(27,'(GMT) Greenwich Mean Time : Dublin, Edinburgh, Lisbon,  London','GMT',0),(28,'(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna','GMT+01:00',3600),(29,'(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague','GMT+01:00',3600),(30,'(GMT+01:00) Brussels, Copenhagen, Madrid, Paris','GMT+01:00',3600),(31,'(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb','GMT+01:00',3600),(32,'(GMT+01:00) West Central Africa','GMT+01:00',3600),(33,'(GMT+02:00) Athens, Istanbul, Minsk','GMT+02:00',7200),(34,'(GMT+02:00) Bucharest','GMT+02:00',7200),(35,'(GMT+02:00) Cairo','GMT+02:00',7200),(36,'(GMT+02:00) Harere, Pretoria','GMT+02:00',7200),(37,'(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius','GMT+02:00',7200),(38,'(GMT+02:00) Jeruasalem','GMT+02:00',7200),(39,'(GMT+03:00) Baghdad','GMT+03:00',10800),(40,'(GMT+03:00) Kuwait, Riyadh','GMT+03:00',10800),(41,'(GMT+03:00) Moscow, St.Petersburg, Volgograd','GMT+03:00',10800),(42,'(GMT+03:00) Nairobi','GMT+03:00',10800),(43,'(GMT+03:30) Tehran','GMT+03:30',12600),(44,'(GMT+04:00) Abu Dhabi, Muscat','GMT+04:00',14400),(45,'(GMT+04:00) Baku, Tbillisi, Yerevan','GMT+04:00',14400),(46,'(GMT+04:30) Kabul','GMT+04:30',16200),(47,'(GMT+05:00) Ekaterinburg','GMT+05:00',18000),(48,'(GMT+05:00) Islamabad, Karachi, Tashkent','GMT+05:00',18000),(49,'(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi','GMT+05:30',19800),(50,'(GMT+05:45) Kathmandu','GMT+05:45',20700),(51,'(GMT+06:00) Almaty, Novosibirsk','GMT+06:00',21600),(52,'(GMT+06:00) Astana, Dhaka','GMT+06:00',21600),(53,'(GMT+06:00) Sri Jayawardenepura','GMT+06:00',21600),(54,'(GMT+06:30) Rangoon','GMT+06:30',23400),(55,'(GMT+07:00) Bangkok, Hanoi, Jakarta','GMT+07:00',25200),(56,'(GMT+07:00) Krasnoyarsk','GMT+07:00',25200),(57,'(GMT+08:00) Beijiing, Chongging, Hong Kong, Urumqi','GMT+08:00',28800),(58,'(GMT+08:00) Irkutsk, Ulaan Bataar','GMT+08:00',28800),(59,'(GMT+08:00) Kuala Lumpur, Singapore','GMT+08:00',28800),(60,'(GMT+08:00) Perth','GMT+08:00',28800),(61,'(GMT+08:00) Taipei','GMT+08:00',28800),(62,'(GMT+09:00) Osaka, Sapporo, Tokyo','GMT+09:00',32400),(63,'(GMT+09:00) Seoul','GMT+09:00',32400),(64,'(GMT+09:00) Yakutsk','GMT+09:00',32400),(65,'(GMT+09:00) Adelaide','GMT+09:00',32400),(66,'(GMT+09:30) Darwin','GMT+09:30',34200),(67,'(GMT+10:00) Brisbane','GMT+10:00',36000),(68,'(GMT+10:00) Canberra, Melbourne, Sydney','GMT+10:00',36000),(69,'(GMT+10:00) Guam, Port Moresby','GMT+10:00',36000),(70,'(GMT+10:00) Hobart','GMT+10:00',36000),(71,'(GMT+10:00) Vladivostok','GMT+10:00',36000),(72,'(GMT+11:00) Magadan, Solomon Is., New Caledonia','GMT+11:00',39600),(73,'(GMT+12:00) Auckland, Wellington','GMT+1200',43200),(74,'(GMT+12:00) Fiji, Kamchatka, Marshall Is.','GMT+12:00',43200),(75,'(GMT+13:00) Nuku alofa','GMT+13:00',46800);
/*!40000 ALTER TABLE `cc_timezone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_trunk`
--

DROP TABLE IF EXISTS `cc_trunk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_trunk` (
  `id_trunk` int(11) NOT NULL AUTO_INCREMENT,
  `trunkcode` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `trunkprefix` char(20) COLLATE utf8_bin DEFAULT NULL,
  `providertech` char(20) COLLATE utf8_bin NOT NULL,
  `providerip` char(80) COLLATE utf8_bin NOT NULL,
  `removeprefix` char(20) COLLATE utf8_bin DEFAULT NULL,
  `secondusedreal` int(11) DEFAULT '0',
  `secondusedcarrier` int(11) DEFAULT '0',
  `secondusedratecard` int(11) DEFAULT '0',
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `failover_trunk` int(11) DEFAULT NULL,
  `addparameter` char(120) COLLATE utf8_bin DEFAULT NULL,
  `id_provider` int(11) DEFAULT NULL,
  `inuse` int(11) DEFAULT '0',
  `maxuse` int(11) DEFAULT '-1',
  `status` int(11) DEFAULT '1',
  `if_max_use` int(11) DEFAULT '0',
  PRIMARY KEY (`id_trunk`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_trunk`
--

LOCK TABLES `cc_trunk` WRITE;
/*!40000 ALTER TABLE `cc_trunk` DISABLE KEYS */;
INSERT INTO `cc_trunk` VALUES (1,'DEFAULT','011','IAX2','kiki@switch-2.kiki.net','',0,0,0,'2010-04-08 06:49:51',0,'',NULL,0,-1,1,0);
/*!40000 ALTER TABLE `cc_trunk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_ui_authen`
--

DROP TABLE IF EXISTS `cc_ui_authen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_ui_authen` (
  `userid` bigint(20) NOT NULL AUTO_INCREMENT,
  `login` char(50) COLLATE utf8_bin NOT NULL,
  `pwd_encoded` varchar(250) COLLATE utf8_bin NOT NULL,
  `groupid` int(11) DEFAULT NULL,
  `perms` int(11) DEFAULT NULL,
  `confaddcust` int(11) DEFAULT NULL,
  `name` char(50) COLLATE utf8_bin DEFAULT NULL,
  `direction` char(80) COLLATE utf8_bin DEFAULT NULL,
  `zipcode` char(20) COLLATE utf8_bin DEFAULT NULL,
  `state` char(20) COLLATE utf8_bin DEFAULT NULL,
  `phone` char(30) COLLATE utf8_bin DEFAULT NULL,
  `fax` char(30) COLLATE utf8_bin DEFAULT NULL,
  `datecreation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(70) COLLATE utf8_bin DEFAULT NULL,
  `country` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `city` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `cons_cc_ui_authen_login` (`login`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_ui_authen`
--

LOCK TABLES `cc_ui_authen` WRITE;
/*!40000 ALTER TABLE `cc_ui_authen` DISABLE KEYS */;
INSERT INTO `cc_ui_authen` VALUES (1,'root','410fc6268dd3332226de95e42d9efa4046c5463769d7493b85e65cfa5c26362dc2455cc23c0bc5831deb008def4ab11a9eaa9b76ba3f377da134f39ec60dd758',0,5242879,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2010-04-08 06:49:51',NULL,NULL,NULL);
/*!40000 ALTER TABLE `cc_ui_authen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_version`
--

DROP TABLE IF EXISTS `cc_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_version` (
  `version` varchar(30) COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_version`
--

LOCK TABLES `cc_version` WRITE;
/*!40000 ALTER TABLE `cc_version` DISABLE KEYS */;
INSERT INTO `cc_version` VALUES ('1.9.3');
/*!40000 ALTER TABLE `cc_version` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cc_voucher`
--

DROP TABLE IF EXISTS `cc_voucher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_voucher` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usedate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expirationdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `voucher` char(50) COLLATE utf8_bin NOT NULL,
  `usedcardnumber` char(50) COLLATE utf8_bin DEFAULT NULL,
  `tag` char(50) COLLATE utf8_bin DEFAULT NULL,
  `credit` float NOT NULL DEFAULT '0',
  `activated` char(1) COLLATE utf8_bin NOT NULL DEFAULT 'f',
  `used` int(11) DEFAULT '0',
  `currency` char(3) COLLATE utf8_bin DEFAULT 'USD',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cons_cc_voucher_voucher` (`voucher`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cc_voucher`
--

LOCK TABLES `cc_voucher` WRITE;
/*!40000 ALTER TABLE `cc_voucher` DISABLE KEYS */;
/*!40000 ALTER TABLE `cc_voucher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `cc_callplan_lcr`
--

/*!50001 DROP TABLE IF EXISTS `cc_callplan_lcr`*/;
/*!50001 DROP VIEW IF EXISTS `cc_callplan_lcr`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = latin1 */;
/*!50001 SET character_set_results     = latin1 */;
/*!50001 SET collation_connection      = latin1_swedish_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `cc_callplan_lcr` AS select `cc_ratecard`.`id` AS `id`,`cc_prefix`.`destination` AS `destination`,`cc_ratecard`.`dialprefix` AS `dialprefix`,`cc_ratecard`.`buyrate` AS `buyrate`,`cc_ratecard`.`rateinitial` AS `rateinitial`,`cc_ratecard`.`startdate` AS `startdate`,`cc_ratecard`.`stopdate` AS `stopdate`,`cc_ratecard`.`initblock` AS `initblock`,`cc_ratecard`.`connectcharge` AS `connectcharge`,`cc_ratecard`.`id_trunk` AS `id_trunk`,`cc_ratecard`.`idtariffplan` AS `idtariffplan`,`cc_ratecard`.`id` AS `ratecard_id`,`cc_tariffgroup`.`id` AS `tariffgroup_id` from ((((`cc_tariffgroup_plan` left join `cc_tariffgroup` on((`cc_tariffgroup_plan`.`idtariffgroup` = `cc_tariffgroup`.`id`))) join `cc_tariffplan` on((`cc_tariffplan`.`id` = `cc_tariffgroup_plan`.`idtariffplan`))) left join `cc_ratecard` on((`cc_ratecard`.`idtariffplan` = `cc_tariffplan`.`id`))) left join `cc_prefix` on((`cc_prefix`.`prefix` = `cc_ratecard`.`destination`))) where (`cc_ratecard`.`id` is not null) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `cc_sip_buddies_empty`
--

/*!50001 DROP TABLE IF EXISTS `cc_sip_buddies_empty`*/;
/*!50001 DROP VIEW IF EXISTS `cc_sip_buddies_empty`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = latin1 */;
/*!50001 SET character_set_results     = latin1 */;
/*!50001 SET collation_connection      = latin1_swedish_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `cc_sip_buddies_empty` AS select `cc_sip_buddies`.`id` AS `id`,`cc_sip_buddies`.`id_cc_card` AS `id_cc_card`,`cc_sip_buddies`.`name` AS `name`,`cc_sip_buddies`.`accountcode` AS `accountcode`,`cc_sip_buddies`.`regexten` AS `regexten`,`cc_sip_buddies`.`amaflags` AS `amaflags`,`cc_sip_buddies`.`callgroup` AS `callgroup`,`cc_sip_buddies`.`callerid` AS `callerid`,`cc_sip_buddies`.`canreinvite` AS `canreinvite`,`cc_sip_buddies`.`context` AS `context`,`cc_sip_buddies`.`DEFAULTip` AS `DEFAULTip`,`cc_sip_buddies`.`dtmfmode` AS `dtmfmode`,`cc_sip_buddies`.`fromuser` AS `fromuser`,`cc_sip_buddies`.`fromdomain` AS `fromdomain`,`cc_sip_buddies`.`host` AS `host`,`cc_sip_buddies`.`insecure` AS `insecure`,`cc_sip_buddies`.`language` AS `language`,`cc_sip_buddies`.`mailbox` AS `mailbox`,`cc_sip_buddies`.`md5secret` AS `md5secret`,`cc_sip_buddies`.`nat` AS `nat`,`cc_sip_buddies`.`permit` AS `permit`,`cc_sip_buddies`.`deny` AS `deny`,`cc_sip_buddies`.`mask` AS `mask`,`cc_sip_buddies`.`pickupgroup` AS `pickupgroup`,`cc_sip_buddies`.`port` AS `port`,`cc_sip_buddies`.`qualify` AS `qualify`,`cc_sip_buddies`.`restrictcid` AS `restrictcid`,`cc_sip_buddies`.`rtptimeout` AS `rtptimeout`,`cc_sip_buddies`.`rtpholdtimeout` AS `rtpholdtimeout`,_latin1'' AS `secret`,`cc_sip_buddies`.`type` AS `type`,`cc_sip_buddies`.`username` AS `username`,`cc_sip_buddies`.`disallow` AS `disallow`,`cc_sip_buddies`.`allow` AS `allow`,`cc_sip_buddies`.`musiconhold` AS `musiconhold`,`cc_sip_buddies`.`regseconds` AS `regseconds`,`cc_sip_buddies`.`ipaddr` AS `ipaddr`,`cc_sip_buddies`.`cancallforward` AS `cancallforward`,`cc_sip_buddies`.`fullcontact` AS `fullcontact`,`cc_sip_buddies`.`setvar` AS `setvar` from `cc_sip_buddies` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-03-25 13:55:23
