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
-- A2Billing database
--

--  Default values - Please change them to whatever you want

-- 	Database name is: mya2billing
-- 	Database user is: a2billinguser
-- 	User password is: a2billing



-- 1. make sure that the Database user is GRANT to access the database in pg_hba.conf!

--     a line like this will do it

--     # TYPE  DATABASE    USER        IP-ADDRESS        IP-MASK           METHOD
--     # Database asterisk/a2billing login with password for a non real user
--     #
--     local   mya2billing all						md5

--     DON'T FORGET TO RESTART Postgresql SERVER IF YOU MADE ANY MODIFICATION ON THIS FILE

-- 2. open a terminal and enter the below commands. We assume our superuser to be postgres.
--    Please adapt to your setup.

--     su - postgres
--     psql -f a2billing-schema-v1.4.0.sql template1

--     NOTE: the errors you will see about missing tables are OK, it's the default behaviour of pgsql.

--     When prompted for the password, please enter the one you choose. In our case, it's 'a2billing'.

\set ON_ERROR_STOP ON;
SET default_with_oids = true;
SET escape_string_warning = off;
SET standard_conforming_strings = off;

-- Wrap the whole update in a transaction so everything is reverted upon failure
BEGIN;


CREATE TABLE cc_campaign (
    id 						BIGSERIAL NOT NULL,
    name 					VARCHAR(50) UNIQUE NOT NULL,
    creationdate 			TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    startingdate 			TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    expirationdate 			TIMESTAMP WITHOUT TIME ZONE,
    description 			TEXT ,
    id_card 				BIGINT NOT NULL DEFAULT '0',
    secondusedreal 			INTEGER DEFAULT 0,
    nb_callmade 			INTEGER DEFAULT 0,
    status 					INTEGER DEFAULT 1 NOT NULL,
    frequency				INT NOT NULL DEFAULT '20',
    forward_number			VARCHAR(50),
    daily_start_time		TIME WITHOUT TIME ZONE NOT NULL DEFAULT '10:00:00',
    daily_stop_time			TIME WITHOUT TIME ZONE NOT NULL DEFAULT '18:00:00',
    monday					SMALLINT NOT NULL DEFAULT '1',
    tuesday					SMALLINT NOT NULL DEFAULT '1',
    wednesday				SMALLINT NOT NULL DEFAULT '1',
    thursday				SMALLINT NOT NULL DEFAULT '1',
    friday					SMALLINT NOT NULL DEFAULT '1',
    saturday				SMALLINT NOT NULL DEFAULT '0',
    sunday					SMALLINT NOT NULL DEFAULT '0',
    id_cid_group			INT NOT NULL,
    id_campaign_config		INT NOT NULL,
    PRIMARY KEY (id)
);



CREATE TABLE cc_didgroup (
    id 						BIGSERIAL NOT NULL,
    iduser 					INTEGER DEFAULT 0 NOT NULL,
    creationdate 			TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    didgroupname 			TEXT NOT NULL,
    PRIMARY KEY (id)
);


CREATE TABLE cc_did (
    id 							BIGSERIAL NOT NULL,
    id_cc_didgroup 				BIGINT NOT NULL,
    id_cc_country 				INTEGER NOT NULL,
    activated 					INTEGER DEFAULT 1 NOT NULL,
    reserved 					INTEGER DEFAULT 0,
    iduser 						BIGINT DEFAULT 0 NOT NULL,
    did 						TEXT UNIQUE NOT NULL,
    creationdate 				TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    startingdate 				TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    expirationdate 				TIMESTAMP WITHOUT TIME ZONE,
    description 				TEXT,
    secondusedreal 				INTEGER DEFAULT 0,
    billingtype 				INTEGER DEFAULT 0,
    fixrate 					NUMERIC(12,4) NOT NULL,
    PRIMARY KEY (id)
);
-- billtype: 0 = fix per month + dialoutrate, 1= fix per month, 2 = dialoutrate, 3 = free


CREATE TABLE cc_did_destination (
    id 								BIGSERIAL NOT NULL,
    destination 					TEXT NOT NULL,
    priority 						INTEGER DEFAULT 0 NOT NULL,
    id_cc_card 						BIGINT NOT NULL,
    id_cc_did 						BIGINT NOT NULL,
    creationdate 					TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    activated 						INTEGER DEFAULT 1 NOT NULL,
    secondusedreal 					INTEGER DEFAULT 0,
    voip_call 						INTEGER DEFAULT 0,
    PRIMARY KEY (id)
);


-- chargetype : 0 - subscription fee ; 1 - connection charge for DID setup, 2 - Montly charge for DID use, 3 - just wanted to charge you for extra, 4 - cactus renting charges, etc...
CREATE TABLE cc_charge (
	id 						BIGSERIAL NOT NULL,
	id_cc_card 				BIGINT NOT NULL,
	iduser 					INTEGER DEFAULT 0 NOT NULL,
	creationdate 			TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
	amount 					NUMERIC(12,4) NOT NULL,
	currency 				CHARACTER VARYING(3) DEFAULT 'USD'::CHARACTER VARYING,
	chargetype 				INTEGER DEFAULT 0,
	description 			TEXT,
	id_cc_did 				BIGINT DEFAULT 0,
	id_cc_card_subscription	BIGINT,
	cover_from				DATE,
	cover_to				DATE,
	charged_status			SMALLINT NOT NULL DEFAULT '0',
	invoiced_status			SMALLINT NOT NULL DEFAULT '0',
	PRIMARY KEY (id)
);
CREATE INDEX ind_cc_charge_id_cc_card				ON cc_charge USING btree (id_cc_card);
CREATE INDEX ind_cc_charge_creationdate 			ON cc_charge USING btree (creationdate);


CREATE TABLE cc_paypal (
  id 								BIGSERIAL NOT NULL,
  payer_id 							CHARACTER VARYING(60) default NULL,
  payment_date 						CHARACTER VARYING(50) default NULL,
  txn_id 							CHARACTER VARYING(50) UNIQUE default NULL,
  first_name 						CHARACTER VARYING(50) default NULL,
  last_name 						CHARACTER VARYING(50) default NULL,
  payer_email 						CHARACTER VARYING(75) default NULL,
  payer_status 						CHARACTER VARYING(50) default NULL,
  payment_type 						CHARACTER VARYING(50) default NULL,
  memo 								TEXT,
  item_name 						CHARACTER VARYING(127) default NULL,
  item_number 						CHARACTER VARYING(127) default NULL,
  quantity 							BIGINT NOT NULL default '0',
  mc_gross 							NUMERIC(9,2) default NULL,
  mc_fee 							NUMERIC(9,2) default NULL,
  tax 								NUMERIC(9,2) default NULL,
  mc_currency 						CHARACTER VARYING(3) default NULL,
  address_name 						CHARACTER VARYING(255) NOT NULL default '',
  address_street 					CHARACTER VARYING(255) NOT NULL default '',
  address_city 						CHARACTER VARYING(255) NOT NULL default '',
  address_state 					CHARACTER VARYING(255) NOT NULL default '',
  address_zip 						CHARACTER VARYING(255) NOT NULL default '',
  address_country 					CHARACTER VARYING(255) NOT NULL default '',
  address_status 					CHARACTER VARYING(255) NOT NULL default '',
  payer_business_name 				CHARACTER VARYING(255) NOT NULL default '',
  payment_status 					CHARACTER VARYING(255) NOT NULL default '',
  pending_reason 					CHARACTER VARYING(255) NOT NULL default '',
  reason_code 						CHARACTER VARYING(255) NOT NULL default '',
  txn_type 							CHARACTER VARYING(255) NOT NULL default '',
  PRIMARY KEY (id)
);


CREATE TABLE cc_voucher (
    id 									BIGSERIAL NOT NULL,
    creationdate 						TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    usedate 							TIMESTAMP WITHOUT TIME ZONE,
    expirationdate 						TIMESTAMP WITHOUT TIME ZONE,
    voucher 							TEXT UNIQUE NOT NULL,
    usedcardnumber 						TEXT,
    tag 								TEXT,
    credit 								NUMERIC(12,4) NOT NULL,
    activated 							BOOLEAN DEFAULT true NOT NULL,
    used 								INTEGER DEFAULT 0,
    currency 							CHARACTER VARYING(3) DEFAULT 'USD'::CHARACTER varying,
    PRIMARY KEY (id)
);


CREATE TABLE cc_service (
    id 									BIGSERIAL NOT NULL,
    name 								TEXT NOT NULL,
    amount 								double precision NOT NULL,
    period 								INTEGER NOT NULL DEFAULT 1,
    rule 								INTEGER NOT NULL DEFAULT 0,
    daynumber 							INTEGER NOT NULL DEFAULT 0,
    stopmode 							INTEGER NOT NULL DEFAULT 0,
    maxnumbercycle 						INTEGER NOT NULL DEFAULT 0,
    status 								INTEGER NOT NULL DEFAULT 0,
    numberofrun 						INTEGER NOT NULL DEFAULT 0,
    datecreate 							TIMESTAMP(0) without time zone DEFAULT NOW(),
    datelastrun 						TIMESTAMP(0) without time zone DEFAULT NOW(),
    emailreport 						TEXT,
    totalcredit 						double precision NOT NULL DEFAULT 0,
    totalcardperform 					INTEGER NOT NULL DEFAULT 0,
    operate_mode						SMALLINT default 0,
    dialplan							INTEGER default 0,
    use_group							SMALLINT default 0,
    PRIMARY KEY (id)
);


CREATE TABLE cc_service_report (
    id 									BIGSERIAL NOT NULL,
    cc_service_id 						BIGINT NOT NULL,
    daterun 							TIMESTAMP(0) without time zone DEFAULT NOW(),
    totalcardperform 					INTEGER,
    totalcredit 						double precision,
    PRIMARY KEY (id)
);


CREATE TABLE cc_callerid (
    id 									BIGSERIAL NOT NULL,
    cid 								TEXT UNIQUE NOT NULL,
    id_cc_card 							BIGINT NOT NULL,
    activated 							BOOLEAN DEFAULT true NOT NULL,
    PRIMARY KEY (id)
);


CREATE TABLE cc_ui_authen (
    userid 								BIGSERIAL NOT NULL,
    login 								TEXT UNIQUE NOT NULL,
    pwd_encoded							TEXT NOT NULL,
    groupid 							INTEGER,
    perms 								INTEGER,
    confaddcust 						INTEGER,
    name 								TEXT,
    direction 							TEXT,
    zipcode 							TEXT,
    state 								TEXT,
    phone	 							TEXT,
    fax 								TEXT,
    email								VARCHAR(70),
    datecreation 						TIMESTAMP without time zone DEFAULT NOW(),
    PRIMARY KEY (userid)
);
-- The password for these two defaults admin users is: "changepassword"
INSERT INTO cc_ui_authen VALUES (1, 'root', '410fc6268dd3332226de95e42d9efa4046c5463769d7493b85e65cfa5c26362dc2455cc23c0bc5831deb008def4ab11a9eaa9b76ba3f377da134f39ec60dd758', 0, 5242879, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-02-26 20:33:27.691314-05');
INSERT INTO cc_ui_authen VALUES (2, 'admin', '410fc6268dd3332226de95e42d9efa4046c5463769d7493b85e65cfa5c26362dc2455cc23c0bc5831deb008def4ab11a9eaa9b76ba3f377da134f39ec60dd758', 0, 5242879, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-02-26 21:14:05.391501-05');


CREATE TABLE cc_call (
    id 									BIGSERIAL NOT NULL,
    card_id								BIGINT NOT NULL,
    sessionid 							TEXT NOT NULL,
    uniqueid 							TEXT NOT NULL,
    nasipaddress 						TEXT,
    starttime 							TIMESTAMP WITHOUT TIME ZONE,
    stoptime 							TIMESTAMP WITHOUT TIME ZONE,
    sessiontime 						INTEGER,
    calledstation 						TEXT,
    destination							INT DEFAULT 0,
    terminatecauseid					SMALLINT DEFAULT 1,
    sessionbill 						double precision,
    id_tariffgroup 						INTEGER,
    id_tariffplan 						INTEGER,
    id_ratecard 						INTEGER,
    id_trunk 							INTEGER,
    sipiax 								INTEGER DEFAULT 0,
    src 								TEXT,
    id_did 								INTEGER,
    buycost 							NUMERIC(15,5) DEFAULT 0,
    id_card_package_offer 				INTEGER DEFAULT 0,
    real_sessiontime					INTEGER,
    dnid								CHARACTER VARYING(40),
    PRIMARY KEY (id)
);
CREATE INDEX cc_call_username_ind ON cc_call USING btree (card_id);
CREATE INDEX cc_call_starttime_ind ON cc_call USING btree (starttime);
CREATE INDEX cc_call_calledstation_ind ON cc_call USING btree (calledstation);
CREATE INDEX cc_call_terminatecause_id ON cc_call USING btree(terminatecauseid);


CREATE TABLE cc_call_archive (
    id 									BIGSERIAL NOT NULL,
    card_id								BIGINT NOT NULL,
    sessionid 							TEXT NOT NULL,
    uniqueid 							TEXT NOT NULL,
    nasipaddress 						TEXT,
    starttime 							TIMESTAMP WITHOUT TIME ZONE,
    stoptime 							TIMESTAMP WITHOUT TIME ZONE,
    sessiontime 						INTEGER,
    calledstation 						TEXT,
    destination							INT DEFAULT 0,
    terminatecauseid					SMALLINT DEFAULT 1,
    sessionbill 						double precision,
    id_tariffgroup 						INTEGER,
    id_tariffplan 						INTEGER,
    id_ratecard 						INTEGER,
    id_trunk 							INTEGER,
    sipiax 								INTEGER DEFAULT 0,
    src 								TEXT,
    id_did 								INTEGER,
    buycost 							NUMERIC(15,5) DEFAULT 0,
    id_card_package_offer 				INTEGER DEFAULT 0,
    real_sessiontime					INTEGER,
    dnid								CHARACTER VARYING(40),
    PRIMARY KEY (id)
);
CREATE INDEX cc_call_archive_username_ind ON cc_call_archive USING btree (card_id);
CREATE INDEX cc_call_archive_starttime_ind ON cc_call_archive USING btree (starttime);
CREATE INDEX cc_call_archive_calledstation_ind ON cc_call_archive USING btree (calledstation);
CREATE INDEX cc_call_archive_terminatecause_id ON cc_call_archive USING btree(terminatecauseid);


CREATE TABLE cc_templatemail (
	id									SERIAL NOT NULL,
    mailtype 							TEXT,
    fromemail 							TEXT,
    fromname 							TEXT,
    subject 							TEXT,
    messagetext 						TEXT,
    messagehtml 						TEXT,
    id_language							CHARACTER VARYING(20) DEFAULT 'en',
    CONSTRAINT cons_cc_templatemail_mailtype UNIQUE (mailtype, id_language)
);


INSERT INTO cc_templatemail (mailtype, fromemail, fromname, subject, messagetext, messagehtml) VALUES ('signup', 'info@YourDomain.com', 'YourDomain', 'SIGNUP CONFIRMATION', '
Thank you for registering with us
Please click on below link to activate your account.

http://YourDomain.com/activate.php?key$loginkey

Please make sure you active your account by making payment to us either by
credit card, wire transfer, money order, cheque, and western union money
transfer, money Gram, and Pay pal.


Kind regards,
YourDomain
', '');

INSERT INTO cc_templatemail (mailtype, fromemail, fromname, subject, messagetext, messagehtml) VALUES ('epaymentverify', 'info@YourDomain.com', 'YourDomain', 'Epayment Gateway Security Verification Failed', 'Dear Administrator

Please check the e-payment logs, the system has logged an e-payment security failure. This may be an attack attempt on epayment module.

Time of Transaction: $time
Payment Gateway: $paymentgateway
Amount: $amount


Kind regards,
YourDomain
', '');

INSERT INTO cc_templatemail (mailtype, fromemail, fromname, subject, messagetext, messagehtml) VALUES ('reminder', 'info@YourDomain.com', 'YourDomain', 'REMINDER', '

Your YourDomain Account number $cardnumber is running low on credit.

There is currently only $credit_currency $currency left on your account which is lower than the warning level defined ($credit_notification)


Please top up your account ASAP to ensure continued service

If you no longer wish to receive these notifications or would like to change the balance amount at which these warnings are generated,
please connect on your myaccount panel and change the appropriate parameters


your account information :
Your account number for VOIP authentication : $cardnumber

http://myaccount.YourDomain.com/
Your account login : $cardalias
Your account password : $password


Thanks,
YourDomain
 ', '');


INSERT INTO cc_templatemail (mailtype, fromemail, fromname, subject, messagetext, messagehtml) VALUES ('forgetpassword', 'info@YourDomain.com', 'YourDomain', 'Login Information', 'Your login information is as below:

Your account is $card_gen

Your password is $password

Your cardalias is $cardalias

http://YourDomain.com/A2BCustomer_UI/


Kind regards,
YourDomain
', '');

INSERT INTO cc_templatemail (mailtype, fromemail, fromname, subject, messagetext, messagehtml) VALUES ('signupconfirmed', 'info@YourDomain.com', 'YourDomain', 'SIGNUP CONFIRMATION', 'Thank you for registering with us

Please make sure you active your account by making payment to us either by
credit card, wire transfer, money order, cheque, and western union money
transfer, money Gram, and Pay pal.

Your account is $card_gen

Your password is $password

To go to your account :
http://YourDomain.com/customer/

Kind regards,
YourDomain
', '');

INSERT INTO cc_templatemail (mailtype, fromemail, fromname, subject, messagetext, messagehtml) VALUES ('payment', 'info@YourDomain.com', 'YourDomain', 'PAYMENT CONFIRMATION', 'Thank you for shopping at YourDomain.

Shopping details is as below.

Item Name = <b>$itemName</b>
Item ID = <b>$itemID</b>
Amount = <b>$itemAmount</b>
Payment Method = <b>$paymentMethod</b>
Status = <b>$paymentStatus</b>


Kind regards,
YourDomain
', '');

INSERT INTO cc_templatemail (mailtype, fromemail, fromname, subject, messagetext, messagehtml) VALUES ('invoice', 'info@YourDomain.com', 'YourDomain', 'A2BILLING INVOICE', 'Dear Customer.

Attached is the invoice.

Kind regards,
YourDomain
', '');


CREATE TABLE cc_tariffgroup (
    id 									serial NOT NULL,
    iduser 								INTEGER DEFAULT 0 NOT NULL,
    idtariffplan 						INTEGER DEFAULT 0 NOT NULL,
    tariffgroupname 					TEXT NOT NULL,
    lcrtype 							INTEGER DEFAULT 0 NOT NULL,
    creationdate 						TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    removeinterprefix 					INTEGER DEFAULT 0 NOT NULL,
	id_cc_package_offer 				BIGINT not null default 0,
	PRIMARY KEY (id)
);


CREATE TABLE cc_tariffgroup_plan (
    idtariffgroup 						INTEGER NOT NULL,
    idtariffplan 						INTEGER NOT NULL,
    PRIMARY KEY (idtariffgroup, idtariffplan)
);


CREATE TABLE cc_tariffplan (
    id 									serial NOT NULL,
    iduser 								INTEGER DEFAULT 0 NOT NULL,
    tariffname 							TEXT NOT NULL,
    creationdate 						TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    startingdate 						TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    expirationdate 						TIMESTAMP WITHOUT TIME ZONE,
    description 						TEXT,
    id_trunk 							INTEGER DEFAULT 0,
    secondusedreal 						INTEGER DEFAULT 0,
    secondusedcarrier 					INTEGER DEFAULT 0,
    secondusedratecard 					INTEGER DEFAULT 0,
    reftariffplan 						INTEGER DEFAULT 0,
    idowner 							INTEGER DEFAULT 0,
    dnidprefix 							TEXT NOT NULL DEFAULT 'all'::text,
    calleridprefix 						TEXT NOT NULL DEFAULT 'all'::text,
    PRIMARY KEY (id),
    CONSTRAINT cons_iduser_tariffname UNIQUE (iduser, tariffname)
);


CREATE TABLE cc_card (
    id 									BIGSERIAL NOT NULL,
    creationdate 						TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    firstusedate 						TIMESTAMP WITHOUT TIME ZONE,
    expirationdate 						TIMESTAMP WITHOUT TIME ZONE,
    enableexpire 						INTEGER DEFAULT 0,
    expiredays 							INTEGER DEFAULT 0,
    username 							TEXT UNIQUE NOT NULL,
    useralias 							TEXT UNIQUE NOT NULL,
    uipass 								TEXT,
    credit 								NUMERIC(12,4) NOT NULL,
    tariff 								INTEGER DEFAULT 0,
    id_didgroup 						INTEGER DEFAULT 0,
    activated 							BOOLEAN DEFAULT false NOT NULL,
    lastname 							TEXT,
    firstname 							TEXT,
    address 							TEXT,
    city 								TEXT,
    state 								TEXT,
    country 							TEXT,
    zipcode 							TEXT,
    phone 								TEXT,
    email 								TEXT,
    fax 								TEXT,
    inuse 								INTEGER DEFAULT 0,
    simultaccess 						INTEGER DEFAULT 0,
    currency 							CHARACTER VARYING(3) DEFAULT 'USD'::CHARACTER varying,
    lastuse 							date DEFAULT NOW(),
    nbused 								INTEGER DEFAULT 0,
    typepaid 							INTEGER DEFAULT 0,
    creditlimit 						INTEGER DEFAULT 0,
    voipcall 							INTEGER DEFAULT 0,
    sip_buddy 							INTEGER DEFAULT 0,
    iax_buddy 							INTEGER DEFAULT 0,
    "language" 							TEXT DEFAULT 'en'::text,
    redial 								TEXT,
    runservice 							INTEGER DEFAULT 0,
    nbservice 							INTEGER DEFAULT 0,
    id_campaign 						INTEGER DEFAULT 0,
    num_trials_done 					INTEGER DEFAULT 0,
    vat 								NUMERIC(6,3) DEFAULT 0,
    servicelastrun 						TIMESTAMP WITHOUT TIME ZONE,
    initialbalance 						NUMERIC(12,4) NOT NULL DEFAULT 0,
    invoiceday 							INTEGER DEFAULT 1,
    autorefill 							INTEGER DEFAULT 0,
    loginkey 							TEXT,
    mac_addr							VARCHAR(17) DEFAULT '00-00-00-00-00-00' NOT NULL,
    id_timezone							INTEGER DEFAULT 0,
    status								INTEGER NOT NULL DEFAULT '1',
    tag									CHARACTER VARYING(50),
    voicemail_permitted					INTEGER DEFAULT 0 NOT NULL,
    voicemail_activated					INTEGER DEFAULT 0 NOT NULL,
    last_notification					TIMESTAMP WITHOUT TIME ZONE,
    email_notification					CHARACTER VARYING(70),
    notify_email						SMALLINT NOT NULL DEFAULT 0,
    credit_notification					INTEGER NOT NULL DEFAULT -1,
    id_group							INTEGER NOT NULL DEFAULT 1,
    company_name						CHARACTER VARYING(50),
    company_website						CHARACTER VARYING(60),
    vat_rn								CHARACTER VARYING(40) DEFAULT NULL,
    traffic								BIGINT DEFAULT 0,
    traffic_target						TEXT,
    discount							decimal(5,2) NOT NULL DEFAULT '0',
    restriction							SMALLINT NOT NULL DEFAULT '0',
    id_seria							integer,
    serial								BIGINT,
    PRIMARY KEY (id)
);
CREATE INDEX cc_card_creationdate_ind ON cc_card USING btree (creationdate);
CREATE INDEX cc_card_username_ind ON cc_card USING btree (username);


CREATE TABLE cc_card_archive (
    id 									BIGSERIAL NOT NULL,
    creationdate 						TIMESTAMP WITHOUT TIME ZONE,
    firstusedate 						TIMESTAMP WITHOUT TIME ZONE,
    expirationdate 						TIMESTAMP WITHOUT TIME ZONE,
    enableexpire 						INTEGER DEFAULT 0,
    expiredays 							INTEGER DEFAULT 0,
    username 							TEXT UNIQUE NOT NULL,
    useralias 							TEXT UNIQUE NOT NULL,
    uipass 								TEXT,
    credit 								NUMERIC(12,4) NOT NULL,
    tariff 								INTEGER DEFAULT 0,
    id_didgroup 						INTEGER DEFAULT 0,
    activated 							BOOLEAN DEFAULT false NOT NULL,
    lastname 							TEXT,
    firstname 							TEXT,
    address 							TEXT,
    city 								TEXT,
    state 								TEXT,
    country 							TEXT,
    zipcode 							TEXT,
    phone 								TEXT,
    email 								TEXT,
    fax 								TEXT,
    inuse 								INTEGER DEFAULT 0,
    simultaccess 						INTEGER DEFAULT 0,
    currency 							CHARACTER VARYING(3) DEFAULT 'USD'::CHARACTER varying,
    lastuse 							date DEFAULT NOW(),
    nbused 								INTEGER DEFAULT 0,
    typepaid 							INTEGER DEFAULT 0,
    creditlimit 						INTEGER DEFAULT 0,
    voipcall 							INTEGER DEFAULT 0,
    sip_buddy 							INTEGER DEFAULT 0,
    iax_buddy 							INTEGER DEFAULT 0,
    "language" 							TEXT DEFAULT 'en'::text,
    redial 								TEXT,
    runservice 							INTEGER DEFAULT 0,
    nbservice 							INTEGER DEFAULT 0,
    id_campaign 						INTEGER DEFAULT 0,
    num_trials_done 					INTEGER DEFAULT 0,
    vat 								NUMERIC(6,3) DEFAULT 0,
    servicelastrun 						TIMESTAMP WITHOUT TIME ZONE,
    initialbalance 						NUMERIC(12,4) NOT NULL DEFAULT 0,
    invoiceday 							INTEGER DEFAULT 1,
    autorefill 							INTEGER DEFAULT 0,
    loginkey 							TEXT,
    mac_addr							VARCHAR(17) DEFAULT '00-00-00-00-00-00' NOT NULL,
    id_timezone							INTEGER DEFAULT 0,
    status								INTEGER NOT NULL DEFAULT '1',
    tag									CHARACTER VARYING(50),
    voicemail_permitted					INTEGER DEFAULT 0 NOT NULL,
    voicemail_activated					INTEGER DEFAULT 0 NOT NULL,
    last_notification					TIMESTAMP WITHOUT TIME ZONE,
    email_notification					CHARACTER VARYING(70),
    notify_email						SMALLINT NOT NULL DEFAULT 0,
    credit_notification					INTEGER NOT NULL DEFAULT -1,
    id_group							INTEGER NOT NULL DEFAULT 1,
    company_name						CHARACTER VARYING(50),
    company_website						CHARACTER VARYING(60),
    vat_rn								CHARACTER VARYING(40) DEFAULT NULL,
    traffic								BIGINT DEFAULT 0,
    traffic_target						TEXT,
    discount							decimal(5,2) NOT NULL DEFAULT '0',
    restriction							SMALLINT NOT NULL DEFAULT '0',
    id_seria							integer,
    serial								BIGINT,
    PRIMARY KEY (id)
);
CREATE INDEX cc_card_archive_creationdate_ind ON cc_card_archive USING btree (creationdate);
CREATE INDEX cc_card_archive_username_ind ON cc_card_archive USING btree (username);


CREATE TABLE cc_ratecard (
    id 									serial NOT NULL,
    idtariffplan 						INTEGER DEFAULT 0 NOT NULL,
    dialprefix 							TEXT NOT NULL,
    destination 						INT DEFAULT 0,
    buyrate 							DECIMAL(15,5) DEFAULT 0 NOT NULL,
    buyrateinitblock 					INTEGER DEFAULT 0 NOT NULL,
    buyrateincrement 					INTEGER DEFAULT 0 NOT NULL,
    rateinitial 						DECIMAL(15,5) DEFAULT 0 NOT NULL,
    initblock 							INTEGER DEFAULT 0 NOT NULL,
    billingblock 						INTEGER DEFAULT 0 NOT NULL,
    connectcharge 						DECIMAL(15,5) DEFAULT 0 NOT NULL,
    disconnectcharge 					DECIMAL(15,5) DEFAULT 0 NOT NULL,
    stepchargea 						DECIMAL(15,5) DEFAULT 0 NOT NULL,
    chargea 							DECIMAL(15,5) DEFAULT 0 NOT NULL,
    timechargea 						INTEGER DEFAULT 0 NOT NULL,
    billingblocka 						INTEGER DEFAULT 0 NOT NULL,
    stepchargeb 						DECIMAL(15,5) DEFAULT 0 NOT NULL,
    chargeb 							DECIMAL(15,5) DEFAULT 0 NOT NULL,
    timechargeb 						INTEGER DEFAULT 0 NOT NULL,
    billingblockb 						INTEGER DEFAULT 0 NOT NULL,
    stepchargec 						DECIMAL(15,5) DEFAULT 0 NOT NULL,
    chargec 							DECIMAL(15,5) DEFAULT 0 NOT NULL,
    timechargec 						INTEGER DEFAULT 0 NOT NULL,
    billingblockc 						INTEGER DEFAULT 0 NOT NULL,
    startdate 							TIMESTAMP(0) without time zone DEFAULT NOW(),
    stopdate 							TIMESTAMP(0) without time zone,
    starttime 							INTEGER NOT NULL DEFAULT 0,
    endtime 							INTEGER NOT NULL DEFAULT 10079,
    id_trunk 							INTEGER DEFAULT -1,
    musiconhold 						CHARACTER VARYING(100),
    id_outbound_cidgroup 				INTEGER NOT NULL DEFAULT -1,
    rounding_calltime					INTEGER NOT NULL DEFAULT 0,
    rounding_threshold					INTEGER NOT NULL DEFAULT 0,
    additional_block_charge				DECIMAL(15,5) NOT NULL DEFAULT 0,
    additional_block_charge_time		INTEGER NOT NULL DEFAULT 0,
    tag									CHARACTER VARYING(50),
    is_merged							INTEGER DEFAULT 0,
    additional_grace					INTEGER NOT NULL DEFAULT 0,
    minimal_cost						DECIMAL(15,5) NOT NULL DEFAULT 0,
    announce_time_correction			DECIMAL(5,3) NOT NULL DEFAULT 1.0,
    PRIMARY KEY (id)
);
CREATE INDEX ind_cc_ratecard_dialprefix ON cc_ratecard USING btree (dialprefix);


CREATE TABLE cc_trunk (
    id_trunk 							serial NOT NULL,
    trunkcode 							TEXT NOT NULL,
    trunkprefix 						TEXT ,
    providertech 						TEXT NOT NULL,
    providerip 							TEXT NOT NULL,
    removeprefix 						TEXT ,
    secondusedreal 						INTEGER DEFAULT 0,
    secondusedcarrier 					INTEGER DEFAULT 0,
    secondusedratecard 					INTEGER DEFAULT 0,
    creationdate 						TIMESTAMP(0) without time zone DEFAULT NOW(),
    failover_trunk 						INTEGER,
    addparameter 						TEXT,
    id_provider							INTEGER,
    inuse								INTEGER DEFAULT 0,
    maxuse								INTEGER DEFAULT -1,
    status								INTEGER DEFAULT 1,
    if_max_use							INTEGER DEFAULT 0,
    PRIMARY KEY (id_trunk)
);
INSERT INTO cc_trunk VALUES (1, 'DEFAULT', '011', 'IAX2', 'exampletrunk', '', 0, 0, 0, '2005-03-14 01:01:36', 0, '', NULL);


CREATE TABLE cc_sip_buddies (
    id 									serial NOT NULL,
    id_cc_card 							INTEGER DEFAULT 0 NOT NULL,
    name 								CHARACTER VARYING(80) DEFAULT ''::CHARACTER varying UNIQUE NOT NULL,
    "type" 								CHARACTER VARYING(6) DEFAULT 'friend'::CHARACTER varying NOT NULL,
    username 							CHARACTER VARYING(80) DEFAULT ''::CHARACTER varying NOT NULL,
    accountcode 						CHARACTER VARYING(20),
    regexten 							CHARACTER VARYING(20),
    callerid 							CHARACTER VARYING(80),
    amaflags 							CHARACTER VARYING(7),
    secret 								CHARACTER VARYING(80),
    md5secret 							CHARACTER VARYING(80),
    nat 								CHARACTER VARYING(3) DEFAULT 'yes'::CHARACTER varying NOT NULL,
    dtmfmode 							CHARACTER VARYING(7) DEFAULT 'RFC2833'::CHARACTER varying NOT NULL,
    disallow 							CHARACTER VARYING(100) DEFAULT 'all'::CHARACTER varying,
    allow 								CHARACTER VARYING(100) DEFAULT 'gsm,ulaw,alaw'::CHARACTER varying,
    host 								CHARACTER VARYING(31) DEFAULT ''::CHARACTER varying NOT NULL,
    qualify 							CHARACTER VARYING(7) DEFAULT 'yes'::CHARACTER varying NOT NULL,
    canreinvite 						CHARACTER VARYING(20) DEFAULT 'yes'::CHARACTER varying,
    callgroup 							CHARACTER VARYING(10),
    context 							CHARACTER VARYING(80),
    defaultip 							CHARACTER VARYING(15),
    fromuser 							CHARACTER VARYING(80),
    fromdomain 							CHARACTER VARYING(80),
    insecure 							CHARACTER VARYING(20),
    "language" 							CHARACTER VARYING(2),
    mailbox 							CHARACTER VARYING(50),
    permit 								CHARACTER VARYING(95),
    deny 								CHARACTER VARYING(95),
    mask 								CHARACTER VARYING(95),
    pickupgroup 						CHARACTER VARYING(10),
    port 								CHARACTER VARYING(5) DEFAULT ''::CHARACTER varying NOT NULL,
    restrictcid 						CHARACTER VARYING(1),
    rtptimeout 							CHARACTER VARYING(3),
    rtpholdtimeout 						CHARACTER VARYING(3),
    musiconhold 						CHARACTER VARYING(100),
    regseconds 							INTEGER DEFAULT 0 NOT NULL,
    ipaddr 								CHARACTER VARYING(15) DEFAULT ''::CHARACTER varying NOT NULL,
    cancallforward 						CHARACTER VARYING(3) DEFAULT 'yes'::CHARACTER varying,
    fullcontact 						CHARACTER VARYING(80),
    setvar 								CHARACTER VARYING(100) DEFAULT ''::CHARACTER varying NOT NULL,
    regserver							CHARACTER VARYING(20),
    PRIMARY KEY (id)
);


-- Empty password view for OpenSips / Kamailio
CREATE OR REPLACE VIEW cc_sip_buddies_empty AS
  SELECT id, id_cc_card, name, accountcode, regexten, amaflags, callgroup, callerid, canreinvite, context,
  DEFAULTip, dtmfmode, fromuser, fromdomain, host, insecure, language, mailbox, md5secret, nat, permit,
  deny, mask, pickupgroup, port, qualify, restrictcid, rtptimeout, rtpholdtimeout, ''::text as secret,
  type, username, disallow, allow, musiconhold, regseconds, ipaddr, cancallforward, fullcontact, setvar
  FROM cc_sip_buddies;


CREATE TABLE cc_iax_buddies (
    id 									serial NOT NULL,
    id_cc_card 							INTEGER DEFAULT 0 NOT NULL,
    name 								CHARACTER VARYING(80) DEFAULT ''::CHARACTER varying UNIQUE NOT NULL,
    "type" 								CHARACTER VARYING(6) DEFAULT 'friend'::CHARACTER varying NOT NULL,
    username 							CHARACTER VARYING(80) DEFAULT ''::CHARACTER varying NOT NULL,
    accountcode 						CHARACTER VARYING(20),
    regexten 							CHARACTER VARYING(20),
    callerid 							CHARACTER VARYING(80),
    amaflags 							CHARACTER VARYING(7),
    secret 								CHARACTER VARYING(80),
    md5secret 							CHARACTER VARYING(80),
    nat 								CHARACTER VARYING(3) DEFAULT 'yes'::CHARACTER varying NOT NULL,
    dtmfmode 							CHARACTER VARYING(7) DEFAULT 'RFC2833'::CHARACTER varying NOT NULL,
    disallow 							CHARACTER VARYING(100) DEFAULT 'all'::CHARACTER varying,
    allow 								CHARACTER VARYING(100) DEFAULT 'gsm,ulaw,alaw'::CHARACTER varying,
    host 								CHARACTER VARYING(31) DEFAULT ''::CHARACTER varying NOT NULL,
    qualify 							CHARACTER VARYING(7) DEFAULT 'yes'::CHARACTER varying NOT NULL,
    canreinvite 						CHARACTER VARYING(20) DEFAULT 'yes'::CHARACTER varying,
    callgroup 							CHARACTER VARYING(10),
    context 							CHARACTER VARYING(80),
    defaultip 							CHARACTER VARYING(15),
    fromuser 							CHARACTER VARYING(80),
    fromdomain 							CHARACTER VARYING(80),
    insecure 							CHARACTER VARYING(20),
    "language" 							CHARACTER VARYING(2),
    mailbox 							CHARACTER VARYING(50),
    permit 								CHARACTER VARYING(95),
    deny 								CHARACTER VARYING(95),
    mask 								CHARACTER VARYING(95),
    pickupgroup 						CHARACTER VARYING(10),
    port 								CHARACTER VARYING(5) DEFAULT ''::CHARACTER varying NOT NULL,
    restrictcid 						CHARACTER VARYING(1),
    rtptimeout 							CHARACTER VARYING(3),
    rtpholdtimeout 						CHARACTER VARYING(3),
    musiconhold 						CHARACTER VARYING(100),
    regseconds							INTEGER DEFAULT 0 NOT NULL,
    ipaddr 								CHARACTER VARYING(15) DEFAULT ''::CHARACTER varying NOT NULL,
    cancallforward 						CHARACTER VARYING(3) DEFAULT 'yes'::CHARACTER varying,
    trunk								CHARACTER VARYING(3) DEFAULT 'no',
    PRIMARY KEY (id)
);


CREATE TABLE cc_logrefill (
    id 									bigserial NOT NULL,
    date 								TIMESTAMP(0) without time zone DEFAULT NOW() NOT NULL,
    credit 								DECIMAL(15,5) NOT NULL,
    card_id 							BIGINT NOT NULL,
    description							TEXT,
    refill_type							SMALLINT NOT NULL DEFAULT 0,
    added_invoice						SMALLINT NOT NULL DEFAULT '0',
	PRIMARY KEY (id)
);


CREATE TABLE cc_logpayment (
    id 									bigserial NOT NULL,
    date 								TIMESTAMP(0) without time zone DEFAULT NOW() NOT NULL,
    payment 							DECIMAL(15, 5) NOT NULL,
    card_id 							BIGINT NOT NULL,
    id_logrefill						BIGINT,
    description							TEXT,
    added_refill						SMALLINT NOT NULL DEFAULT 0,
    payment_type						SMALLINT NOT NULL DEFAULT 0,
    added_commission					SMALLINT NOT NULL DEFAULT 0,
    PRIMARY KEY (id)
);



-- Refill table for Agent
CREATE TABLE cc_logrefill_agent (
	id 					BIGSERIAL NOT NULL,
	date 				timestamp WITHOUT TIME ZONE NOT NULL DEFAULT now(),
	credit 				DECIMAL(15, 5) NOT NULL,
	agent_id 			BIGINT NOT NULL,
	description 		TEXT,
	refill_type 		SMALLINT NOT NULL default '0',
	PRIMARY KEY  (id)
);

-- logpayment table for Agent
CREATE TABLE cc_logpayment_agent (
	id 					BIGSERIAL NOT NULL,
	date 				TIMESTAMP WITHOUT TIME ZONE NOT NULL default now(),
	payment 			DECIMAL(15, 5) NOT NULL,
	agent_id 			BIGINT NOT NULL,
	id_logrefill 		BIGINT default NULL,
	description 		TEXT,
	added_refill 		SMALLINT NOT NULL default '0',
	payment_type 		SMALLINT NOT NULL default '0',
    added_commission	SMALLINT NOT NULL DEFAULT '0',
	PRIMARY KEY (id)
);


create table cc_did_use (
    id 									bigserial not null ,
    id_cc_card 							BIGINT,
    id_did 								BIGINT not null,
    reservationdate						TIMESTAMP WITHOUT TIME ZONE not null default NOW(),
    releasedate 						TIMESTAMP WITHOUT TIME ZONE,
    activated 							INTEGER default 0,
    month_payed 						INTEGER default 0,
    reminded							SMALLINT NOT NULL DEFAULT '0',
    PRIMARY KEY (id)
);


--
-- Country table : Store the iso country list
--

CREATE TABLE cc_country (
    id 									serial NOT NULL,
    countrycode 						TEXT NOT NULL,
    countryprefix 						TEXT NOT NULL DEFAULT '0',
    countryname 						TEXT NOT NULL,
    PRIMARY KEY (id)
);



INSERT INTO cc_country VALUES (1, 'AFG' ,'93', 'Afghanistan');
INSERT INTO cc_country VALUES (2, 'ALB' ,'355',  'Albania');
INSERT INTO cc_country VALUES (3, 'DZA' ,'213',  'Algeria');
INSERT INTO cc_country VALUES (4, 'ASM' ,'684',  'American Samoa');
INSERT INTO cc_country VALUES (5, 'AND' ,'376',  'Andorra');
INSERT INTO cc_country VALUES (6, 'AGO' ,'244',  'Angola');
INSERT INTO cc_country VALUES (7, 'AIA' ,'1264',  'Anguilla');
INSERT INTO cc_country VALUES (8, 'ATA' ,'672',  'Antarctica');
INSERT INTO cc_country VALUES (9, 'ATG' ,'1268',  'Antigua And Barbuda');
INSERT INTO cc_country VALUES (10, 'ARG' ,'54',  'Argentina');
INSERT INTO cc_country VALUES (11, 'ARM' ,'374',  'Armenia');
INSERT INTO cc_country VALUES (12, 'ABW' ,'297', 'Aruba');
INSERT INTO cc_country VALUES (13, 'AUS' ,'61',  'Australia');
INSERT INTO cc_country VALUES (14, 'AUT' ,'43',  'Austria');
INSERT INTO cc_country VALUES (15, 'AZE' ,'994',  'Azerbaijan');
INSERT INTO cc_country VALUES (16, 'BHS' ,'1242',  'Bahamas');
INSERT INTO cc_country VALUES (17, 'BHR' ,'973',  'Bahrain');
INSERT INTO cc_country VALUES (18, 'BGD' ,'880',  'Bangladesh');
INSERT INTO cc_country VALUES (19, 'BRB' ,'1246',  'Barbados');
INSERT INTO cc_country VALUES (20, 'BLR' ,'375',  'Belarus');
INSERT INTO cc_country VALUES (21, 'BEL' ,'32',  'Belgium');
INSERT INTO cc_country VALUES (22, 'BLZ' ,'501',  'Belize');
INSERT INTO cc_country VALUES (23, 'BEN' ,'229',  'Benin');
INSERT INTO cc_country VALUES (24, 'BMU' ,'1441', 'Bermuda');
INSERT INTO cc_country VALUES (25,  'BTN' ,'975', 'Bhutan');
INSERT INTO cc_country VALUES (26,  'BOL' ,'591', 'Bolivia');
INSERT INTO cc_country VALUES (27,  'BIH' ,'387', 'Bosnia And Herzegovina');
INSERT INTO cc_country VALUES (28,  'BWA' ,'267', 'Botswana');
INSERT INTO cc_country VALUES (29,  'BVT' ,'0', 'Bouvet Island');
INSERT INTO cc_country VALUES (30,  'BRA' ,'55', 'Brazil');
INSERT INTO cc_country VALUES (31,  'IOT' ,'1284', 'British Indian Ocean Territory');
INSERT INTO cc_country VALUES (32,  'BRN' ,'673', 'Brunei Darussalam');
INSERT INTO cc_country VALUES (33,  'BGR' ,'359', 'Bulgaria');
INSERT INTO cc_country VALUES (34,  'BFA' ,'226', 'Burkina Faso');
INSERT INTO cc_country VALUES (35,  'BDI' ,'257', 'Burundi');
INSERT INTO cc_country VALUES (36,  'KHM' ,'855', 'Cambodia');
INSERT INTO cc_country VALUES (37,  'CMR' ,'237', 'Cameroon');
INSERT INTO cc_country VALUES (38,  'CAN' ,'1', 'Canada');
INSERT INTO cc_country VALUES (39, 'CPV' ,'238',  'Cape Verde');
INSERT INTO cc_country VALUES (40, 'CYM' ,'1345',  'Cayman Islands');
INSERT INTO cc_country VALUES (41, 'CAF' ,'236',  'Central African Republic');
INSERT INTO cc_country VALUES (42, 'TCD' ,'235',  'Chad');
INSERT INTO cc_country VALUES (43, 'CHL' ,'56',  'Chile');
INSERT INTO cc_country VALUES (44, 'CHN' ,'86', 'China');
INSERT INTO cc_country VALUES (45,  'CXR' ,'618', 'Christmas Island');
INSERT INTO cc_country VALUES (46, 'CCK' ,'61',  'Cocos (Keeling); Islands');
INSERT INTO cc_country VALUES (47, 'COL' ,'57', 'Colombia');
INSERT INTO cc_country VALUES (48, 'COM' ,'269', 'Comoros');
INSERT INTO cc_country VALUES (49, 'COG' ,'242', 'Congo');
INSERT INTO cc_country VALUES (50, 'COD' ,'243','Congo, The Democratic Republic Of The');
INSERT INTO cc_country VALUES (51, 'COK' ,'682', 'Cook Islands');
INSERT INTO cc_country VALUES (52, 'CRI' ,'506', 'Costa Rica');
INSERT INTO cc_country VALUES (54, 'HRV' ,'385', 'Croatia');
INSERT INTO cc_country VALUES (55, 'CUB' ,'53', 'Cuba');
INSERT INTO cc_country VALUES (56, 'CYP' ,'357', 'Cyprus');
INSERT INTO cc_country VALUES (57, 'CZE' ,'420', 'Czech Republic');
INSERT INTO cc_country VALUES (58, 'DNK' ,'45', 'Denmark');
INSERT INTO cc_country VALUES (59, 'DJI' ,'253', 'Djibouti');
INSERT INTO cc_country VALUES (60, 'DMA' ,'1767', 'Dominica');
INSERT INTO cc_country VALUES (61, 'DOM' ,'1809', 'Dominican Republic');
INSERT INTO cc_country VALUES (62, 'ECU' ,'593', 'Ecuador');
INSERT INTO cc_country VALUES (63, 'EGY' ,'20', 'Egypt');
INSERT INTO cc_country VALUES (64, 'SLV' ,'503', 'El Salvador');
INSERT INTO cc_country VALUES (65, 'GNQ' ,'240', 'Equatorial Guinea');
INSERT INTO cc_country VALUES (66, 'ERI' ,'291', 'Eritrea');
INSERT INTO cc_country VALUES (67, 'EST' ,'372', 'Estonia');
INSERT INTO cc_country VALUES (68, 'ETH' ,'251', 'Ethiopia');
INSERT INTO cc_country VALUES (69, 'FLK' ,'500', 'Falkland Islands (Malvinas);');
INSERT INTO cc_country VALUES (70, 'FRO' ,'298', 'Faroe Islands');
INSERT INTO cc_country VALUES (71, 'FJI' ,'679', 'Fiji');
INSERT INTO cc_country VALUES (72, 'FIN' ,'358', 'Finland');
INSERT INTO cc_country VALUES (73, 'FRA' ,'33', 'France');
INSERT INTO cc_country VALUES (74, 'GUF' ,'596', 'French Guiana');
INSERT INTO cc_country VALUES (75, 'PYF' ,'594', 'French Polynesia');
INSERT INTO cc_country VALUES (76, 'ATF' ,'689', 'French Southern Territories');
INSERT INTO cc_country VALUES (77, 'GAB' ,'241', 'Gabon');
INSERT INTO cc_country VALUES (78, 'GMB' ,'220', 'Gambia');
INSERT INTO cc_country VALUES (79, 'GEO' ,'995', 'Georgia');
INSERT INTO cc_country VALUES (80, 'DEU' ,'49', 'Germany');
INSERT INTO cc_country VALUES (81, 'GHA' ,'233', 'Ghana');
INSERT INTO cc_country VALUES (82, 'GIB' ,'350', 'Gibraltar');
INSERT INTO cc_country VALUES (83, 'GRC' ,'30', 'Greece');
INSERT INTO cc_country VALUES (84, 'GRL' ,'299', 'Greenland');
INSERT INTO cc_country VALUES (85, 'GRD' ,'1473', 'Grenada');
INSERT INTO cc_country VALUES (86, 'GLP' ,'590', 'Guadeloupe');
INSERT INTO cc_country VALUES (87, 'GUM' ,'1671', 'Guam');
INSERT INTO cc_country VALUES (88, 'GTM' ,'502', 'Guatemala');
INSERT INTO cc_country VALUES (89, 'GIN' ,'224', 'Guinea');
INSERT INTO cc_country VALUES (90, 'GNB' ,'245', 'Guinea-Bissau');
INSERT INTO cc_country VALUES (91, 'GUY' ,'592', 'Guyana');
INSERT INTO cc_country VALUES (92, 'HTI' ,'509', 'Haiti');
INSERT INTO cc_country VALUES (93, 'HMD' ,'0', 'Heard Island And McDonald Islands');
INSERT INTO cc_country VALUES (94, 'VAT' ,'0', 'Holy See (Vatican City State);');
INSERT INTO cc_country VALUES (95, 'HND' ,'504', 'Honduras');
INSERT INTO cc_country VALUES (96, 'HKG' ,'852', 'Hong Kong');
INSERT INTO cc_country VALUES (97, 'HUN' ,'36', 'Hungary');
INSERT INTO cc_country VALUES (98, 'ISL' ,'354', 'Iceland');
INSERT INTO cc_country VALUES (99, 'IND' ,'91', 'India');
INSERT INTO cc_country VALUES (100, 'IDN' ,'62', 'Indonesia');
INSERT INTO cc_country VALUES (101, 'IRN' ,'98', 'Iran, Islamic Republic Of');
INSERT INTO cc_country VALUES (102, 'IRQ' ,'964', 'Iraq');
INSERT INTO cc_country VALUES (103, 'IRL' ,'353', 'Ireland');
INSERT INTO cc_country VALUES (104, 'ISR' ,'972', 'Israel');
INSERT INTO cc_country VALUES (105, 'ITA' ,'39', 'Italy');
INSERT INTO cc_country VALUES (106, 'JAM' ,'1876', 'Jamaica');
INSERT INTO cc_country VALUES (107, 'JPN' ,'81', 'Japan');
INSERT INTO cc_country VALUES (108, 'JOR' ,'962', 'Jordan');
INSERT INTO cc_country VALUES (109, 'KAZ' ,'7', 'Kazakhstan');
INSERT INTO cc_country VALUES (110, 'KEN' ,'254', 'Kenya');
INSERT INTO cc_country VALUES (111, 'KIR' ,'686', 'Kiribati');
INSERT INTO cc_country VALUES (112, 'PRK' ,'850', 'Korea, Democratic People''s Republic Of');
INSERT INTO cc_country VALUES (113, 'KOR' ,'82', 'Korea, Republic of');
INSERT INTO cc_country VALUES (114, 'KWT' ,'965', 'Kuwait');
INSERT INTO cc_country VALUES (115, 'KGZ' ,'996', 'Kyrgyzstan');
INSERT INTO cc_country VALUES (116, 'LAO' ,'856', 'Lao People''s Democratic Republic');
INSERT INTO cc_country VALUES (117, 'LVA' ,'371', 'Latvia');
INSERT INTO cc_country VALUES (118, 'LBN' ,'961', 'Lebanon');
INSERT INTO cc_country VALUES (119, 'LSO' ,'266', 'Lesotho');
INSERT INTO cc_country VALUES (120, 'LBR' ,'231', 'Liberia');
INSERT INTO cc_country VALUES (121, 'LBY' ,'218', 'Libyan Arab Jamahiriya');
INSERT INTO cc_country VALUES (122, 'LIE' ,'423', 'Liechtenstein');
INSERT INTO cc_country VALUES (123, 'LTU' ,'370', 'Lithuania');
INSERT INTO cc_country VALUES (124, 'LUX' ,'352', 'Luxembourg');
INSERT INTO cc_country VALUES (125, 'MAC' ,'853', 'Macao');
INSERT INTO cc_country VALUES (126, 'MKD' ,'389', 'Macedonia, The Former Yugoslav Republic Of');
INSERT INTO cc_country VALUES (127, 'MDG' ,'261', 'Madagascar');
INSERT INTO cc_country VALUES (128, 'MWI' ,'265', 'Malawi');
INSERT INTO cc_country VALUES (129, 'MYS' ,'60', 'Malaysia');
INSERT INTO cc_country VALUES (130, 'MDV' ,'960', 'Maldives');
INSERT INTO cc_country VALUES (131, 'MLI' ,'223', 'Mali');
INSERT INTO cc_country VALUES (132, 'MLT' ,'356', 'Malta');
INSERT INTO cc_country VALUES (133, 'MHL' ,'692', 'Marshall islands');
INSERT INTO cc_country VALUES (134, 'MTQ' ,'596', 'Martinique');
INSERT INTO cc_country VALUES (135, 'MRT' ,'222', 'Mauritania');
INSERT INTO cc_country VALUES (136, 'MUS' ,'230', 'Mauritius');
INSERT INTO cc_country VALUES (137, 'MYT' ,'269', 'Mayotte');
INSERT INTO cc_country VALUES (138, 'MEX' ,'52', 'Mexico');
INSERT INTO cc_country VALUES (139, 'FSM' ,'691', 'Micronesia, Federated States Of');
INSERT INTO cc_country VALUES (140, 'MDA' ,'1808', 'Moldova, Republic Of');
INSERT INTO cc_country VALUES (141, 'MCO' ,'377', 'Monaco');
INSERT INTO cc_country VALUES (142, 'MNG' ,'976', 'Mongolia');
INSERT INTO cc_country VALUES (143, 'MSR' ,'1664', 'Montserrat');
INSERT INTO cc_country VALUES (144, 'MAR' ,'212', 'Morocco');
INSERT INTO cc_country VALUES (145, 'MOZ' ,'258', 'Mozambique');
INSERT INTO cc_country VALUES (146, 'MMR' ,'95', 'Myanmar');
INSERT INTO cc_country VALUES (147, 'NAM' ,'264', 'Namibia');
INSERT INTO cc_country VALUES (148, 'NRU' ,'674', 'Nauru');
INSERT INTO cc_country VALUES (149, 'NPL' ,'977', 'Nepal');
INSERT INTO cc_country VALUES (150, 'NLD' ,'31', 'Netherlands');
INSERT INTO cc_country VALUES (151, 'ANT' ,'599', 'Netherlands Antilles');
INSERT INTO cc_country VALUES (152, 'NCL' ,'687', 'New Caledonia');
INSERT INTO cc_country VALUES (153, 'NZL' ,'64', 'New Zealand');
INSERT INTO cc_country VALUES (154, 'NIC' ,'505', 'Nicaragua');
INSERT INTO cc_country VALUES (155, 'NER' ,'227', 'Niger');
INSERT INTO cc_country VALUES (156, 'NGA' ,'234', 'Nigeria');
INSERT INTO cc_country VALUES (157, 'NIU' ,'683', 'Niue');
INSERT INTO cc_country VALUES (158, 'NFK' ,'672', 'Norfolk Island');
INSERT INTO cc_country VALUES (159, 'MNP' ,'1670', 'Northern Mariana Islands');
INSERT INTO cc_country VALUES (160, 'NOR' ,'47', 'Norway');
INSERT INTO cc_country VALUES (161, 'OMN' ,'968', 'Oman');
INSERT INTO cc_country VALUES (162, 'PAK' ,'92', 'Pakistan');
INSERT INTO cc_country VALUES (163, 'PLW' ,'680', 'Palau');
INSERT INTO cc_country VALUES (164, 'PSE' ,'970', 'Palestinian Territory, Occupied');
INSERT INTO cc_country VALUES (165, 'PAN' ,'507', 'Panama');
INSERT INTO cc_country VALUES (166, 'PNG' ,'675', 'Papua New Guinea');
INSERT INTO cc_country VALUES (167, 'PRY' ,'595', 'Paraguay');
INSERT INTO cc_country VALUES (168, 'PER' ,'51', 'Peru');
INSERT INTO cc_country VALUES (169, 'PHL' ,'63', 'Philippines');
INSERT INTO cc_country VALUES (170, 'PCN' ,'0', 'Pitcairn');
INSERT INTO cc_country VALUES (171, 'POL' ,'48', 'Poland');
INSERT INTO cc_country VALUES (172, 'PRT' ,'351', 'Portugal');
INSERT INTO cc_country VALUES (173, 'PRI' ,'1787', 'Puerto Rico');
INSERT INTO cc_country VALUES (174, 'QAT' ,'974', 'Qatar');
INSERT INTO cc_country VALUES (175, 'REU' ,'262', 'Reunion');
INSERT INTO cc_country VALUES (176, 'ROU' ,'40', 'Romania');
INSERT INTO cc_country VALUES (177, 'RUS' ,'7', 'Russian Federation');
INSERT INTO cc_country VALUES (178, 'RWA' ,'250', 'Rwanda');
INSERT INTO cc_country VALUES (179, 'SHN' ,'290', 'Saint Helena');
INSERT INTO cc_country VALUES (180, 'KNA' ,'1869', 'Saint Kitts And Nevis');
INSERT INTO cc_country VALUES (181, 'LCA' ,'1758', 'Saint Lucia');
INSERT INTO cc_country VALUES (182, 'SPM' ,'508', 'Saint Pierre And Miquelon');
INSERT INTO cc_country VALUES (183, 'VCT' ,'1784', 'Saint Vincent And The Grenadines');
INSERT INTO cc_country VALUES (184, 'WSM' ,'685', 'Samoa');
INSERT INTO cc_country VALUES (185, 'SMR' ,'378', 'San Marino');
INSERT INTO cc_country VALUES (186, 'STP' ,'239', 'São Tomé And Principe');
INSERT INTO cc_country VALUES (187, 'SAU' ,'966', 'Saudi Arabia');
INSERT INTO cc_country VALUES (188, 'SEN' ,'221', 'Senegal');
INSERT INTO cc_country VALUES (189, 'SYC' ,'248', 'Seychelles');
INSERT INTO cc_country VALUES (190, 'SLE' ,'232', 'Sierra Leone');
INSERT INTO cc_country VALUES (191, 'SGP' ,'65', 'Singapore');
INSERT INTO cc_country VALUES (192, 'SVK' ,'421', 'Slovakia');
INSERT INTO cc_country VALUES (193, 'SVN' ,'386', 'Slovenia');
INSERT INTO cc_country VALUES (194, 'SLB' ,'677', 'Solomon Islands');
INSERT INTO cc_country VALUES (195, 'SOM' ,'252', 'Somalia');
INSERT INTO cc_country VALUES (196, 'ZAF' ,'27', 'South Africa');
INSERT INTO cc_country VALUES (197, 'SGS' ,'0', 'South Georgia And The South Sandwich Islands');
INSERT INTO cc_country VALUES (198, 'ESP' ,'34', 'Spain');
INSERT INTO cc_country VALUES (199, 'LKA' ,'94', 'Sri Lanka');
INSERT INTO cc_country VALUES (200, 'SDN' ,'249', 'Sudan');
INSERT INTO cc_country VALUES (201, 'SUR' ,'597', 'Suriname');
INSERT INTO cc_country VALUES (202, 'SJM' ,'0', 'Svalbard and Jan Mayen');
INSERT INTO cc_country VALUES (203, 'SWZ' ,'268', 'Swaziland');
INSERT INTO cc_country VALUES (204, 'SWE' ,'46', 'Sweden');
INSERT INTO cc_country VALUES (205, 'CHE' ,'41', 'Switzerland');
INSERT INTO cc_country VALUES (206, 'SYR' ,'963', 'Syrian Arab Republic');
INSERT INTO cc_country VALUES (207, 'TWN' ,'886', 'Taiwan, Province Of China');
INSERT INTO cc_country VALUES (208, 'TJK' ,'992', 'Tajikistan');
INSERT INTO cc_country VALUES (209, 'TZA' ,'255', 'Tanzania, United Republic Of');
INSERT INTO cc_country VALUES (210, 'THA' ,'66', 'Thailand');
INSERT INTO cc_country VALUES (211, 'TLS' ,'670', 'Timor-Leste');
INSERT INTO cc_country VALUES (212, 'TGO' ,'228', 'Togo');
INSERT INTO cc_country VALUES (213, 'TKL' ,'690', 'Tokelau');
INSERT INTO cc_country VALUES (214, 'TON' ,'676', 'Tonga');
INSERT INTO cc_country VALUES (215, 'TTO' ,'1868', 'Trinidad And Tobago');
INSERT INTO cc_country VALUES (216, 'TUN' ,'216', 'Tunisia');
INSERT INTO cc_country VALUES (217, 'TUR' ,'90', 'Turkey');
INSERT INTO cc_country VALUES (218, 'TKM' ,'993', 'Turkmenistan');
INSERT INTO cc_country VALUES (219, 'TCA' ,'1649', 'Turks And Caicos Islands');
INSERT INTO cc_country VALUES (220, 'TUV' ,'688', 'Tuvalu');
INSERT INTO cc_country VALUES (221, 'UGA' ,'256', 'Uganda');
INSERT INTO cc_country VALUES (222, 'UKR' ,'380', 'Ukraine');
INSERT INTO cc_country VALUES (223, 'ARE' ,'971', 'United Arab Emirates');
INSERT INTO cc_country VALUES (224, 'GBR' ,'44', 'United Kingdom');
INSERT INTO cc_country VALUES (225, 'USA' ,'1', 'United States');
INSERT INTO cc_country VALUES (226, 'UMI' ,'0', 'United States Minor Outlying Islands');
INSERT INTO cc_country VALUES (227, 'URY' ,'598', 'Uruguay');
INSERT INTO cc_country VALUES (228, 'UZB' ,'998', 'Uzbekistan');
INSERT INTO cc_country VALUES (229, 'VUT' ,'678', 'Vanuatu');
INSERT INTO cc_country VALUES (230, 'VEN' ,'58', 'Venezuela');
INSERT INTO cc_country VALUES (231, 'VNM' ,'84', 'Vietnam');
INSERT INTO cc_country VALUES (232, 'VGB','1284', 'Virgin Islands, British');
INSERT INTO cc_country VALUES (233, 'VIR','808', 'Virgin Islands, U.S.');
INSERT INTO cc_country VALUES (234, 'WLF' ,'681', 'Wallis And Futuna');
INSERT INTO cc_country VALUES (235, 'ESH' ,'0', 'Western Sahara');
INSERT INTO cc_country VALUES (236, 'YEM' ,'967', 'Yemen');
INSERT INTO cc_country VALUES (237, 'YUG' ,'0', 'Yugoslavia');
INSERT INTO cc_country VALUES (238, 'ZMB' ,'260', 'Zambia');
INSERT INTO cc_country VALUES (239, 'ZWE' ,'263', 'Zimbabwe');
INSERT INTO cc_country VALUES (240, 'ASC' ,'0', 'Ascension Island');
INSERT INTO cc_country VALUES (241, 'DGA' ,'0', 'Diego Garcia');
INSERT INTO cc_country VALUES (242, 'XNM' ,'0', 'Inmarsat');
INSERT INTO cc_country VALUES (243, 'TMP' ,'0', 'East timor');
INSERT INTO cc_country VALUES (244, 'AK' ,'0', 'Alaska');
INSERT INTO cc_country VALUES (245, 'HI' ,'0', 'Hawaii');
INSERT INTO cc_country VALUES (53, 'CIV' ,'225', 'Côte d''Ivoire');
INSERT INTO cc_country VALUES (246, 'ALA' ,'35818', 'Aland Islands');
INSERT INTO cc_country VALUES (247, 'BLM' ,'0', 'Saint Barthelemy');
INSERT INTO cc_country VALUES (248, 'GGY' ,'441481', 'Guernsey');
INSERT INTO cc_country VALUES (249, 'IMN' ,'441624', 'Isle of Man');
INSERT INTO cc_country VALUES (250, 'JEY' ,'441534', 'Jersey');
INSERT INTO cc_country VALUES (251, 'MAF' ,'0', 'Saint Martin');
INSERT INTO cc_country VALUES (252, 'MNE' ,'382', 'Montenegro, Republic of');
INSERT INTO cc_country VALUES (253, 'SRB' ,'381', 'Serbia, Republic of');
INSERT INTO cc_country VALUES (254, 'CPT' ,'0', 'Clipperton Island');
INSERT INTO cc_country VALUES (255, 'TAA' ,'0', 'Tristan da Cunha');


CREATE TABLE cc_provider(
    id 										BIGSERIAL NOT NULL,
    provider_name 							TEXT UNIQUE NOT NULL,
    creationdate 							TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    description 							TEXT,
    PRIMARY KEY (id)
);


--
--  cc_currencies table
--

CREATE TABLE cc_currencies (
    id 									serial NOT NULL,
    currency 							char(3) UNIQUE default '' NOT NULL,
    name 								CHARACTER VARYING(30) default '' NOT NULL,
    value 								NUMERIC(12,5) default '0.00000' NOT NULL,
    lastupdate 							TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    basecurrency 						char(3) default 'USD' NOT NULL,
    PRIMARY KEY (id)
);


INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (1, 'ALL', 'Albanian Lek (ALL)', 0.00974,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (2, 'DZD', 'Algerian Dinar (DZD)', 0.01345,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (3, 'XAL', 'Aluminium Ounces (XAL)', 1.08295,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (4, 'ARS', 'Argentine Peso (ARS)', 0.32455,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (5, 'AWG', 'Aruba Florin (AWG)', 0.55866,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (6, 'AUD', 'Australian Dollar (AUD)', 0.73384,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (7, 'BSD', 'Bahamian Dollar (BSD)', 1.00000,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (8, 'BHD', 'Bahraini Dinar (BHD)', 2.65322,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (9, 'BDT', 'Bangladesh Taka (BDT)', 0.01467,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (10, 'BBD', 'Barbados Dollar (BBD)', 0.50000,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (11, 'BYR', 'Belarus Ruble (BYR)', 0.00046,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (12, 'BZD', 'Belize Dollar (BZD)', 0.50569,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (13, 'BMD', 'Bermuda Dollar (BMD)', 1.00000,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (14, 'BTN', 'Bhutan Ngultrum (BTN)', 0.02186,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (15, 'BOB', 'Bolivian Boliviano (BOB)', 0.12500,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (16, 'BRL', 'Brazilian Real (BRL)', 0.46030, 'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (17, 'GBP', 'British Pound (GBP)', 1.73702,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (18, 'BND', 'Brunei Dollar (BND)', 0.61290,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (19, 'BGN', 'Bulgarian Lev (BGN)', 0.60927,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (20, 'BIF', 'Burundi Franc (BIF)', 0.00103,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (21, 'KHR', 'Cambodia Riel (KHR)', 0.00000,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (22, 'CAD', 'Canadian Dollar (CAD)', 0.86386,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (23, 'KYD', 'Cayman Islands Dollar (KYD)', 1.16496,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (24, 'XOF', 'CFA Franc (BCEAO) (XOF)', 0.00182,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (25, 'XAF', 'CFA Franc (BEAC) (XAF)', 0.00182, 'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (26, 'CLP', 'Chilean Peso (CLP)', 0.00187,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (27, 'CNY', 'Chinese Yuan (CNY)', 0.12425,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (28, 'COP', 'Colombian Peso (COP)', 0.00044,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (29, 'KMF', 'Comoros Franc (KMF)', 0.00242,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (30, 'XCP', 'Copper Ounces (XCP)', 2.16403,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (31, 'CRC', 'Costa Rica Colon (CRC)', 0.00199,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (32, 'HRK', 'Croatian Kuna (HRK)', 0.16249,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (33, 'CUP', 'Cuban Peso (CUP)', 1.00000,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (34, 'CYP', 'Cyprus Pound (CYP)', 2.07426, 'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (35, 'CZK', 'Czech Koruna (CZK)', 0.04133,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (36, 'DKK', 'Danish Krone (DKK)', 0.15982,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (37, 'DJF', 'Dijibouti Franc (DJF)', 0.00000,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (38, 'DOP', 'Dominican Peso (DOP)', 0.03035,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (39, 'XCD', 'East Caribbean Dollar (XCD)', 0.37037,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (40, 'ECS', 'Ecuador Sucre (ECS)', 0.00004,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (41, 'EGP', 'Egyptian Pound (EGP)', 0.17433,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (42, 'SVC', 'El Salvador Colon (SVC)', 0.11426,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (43, 'ERN', 'Eritrea Nakfa (ERN)', 0.00000,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (44, 'EEK', 'Estonian Kroon (EEK)', 0.07615,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (45, 'ETB', 'Ethiopian Birr (ETB)', 0.11456,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (46, 'EUR', 'Euro (EUR)', 1.19175,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (47, 'FKP', 'Falkland Islands Pound (FKP)', 0.00000,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (48, 'GMD', 'Gambian Dalasi (GMD)', 0.03515,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (49, 'GHC', 'Ghanian Cedi (GHC)', 0.00011,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (50, 'GIP', 'Gibraltar Pound (GIP)', 0.00000,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (51, 'XAU', 'Gold Ounces (XAU)', 555.55556,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (52, 'GTQ', 'Guatemala Quetzal (GTQ)', 0.13103,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (53, 'GNF', 'Guinea Franc (GNF)', 0.00022,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (54, 'HTG', 'Haiti Gourde (HTG)', 0.02387,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (55, 'HNL', 'Honduras Lempira (HNL)', 0.05292,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (56, 'HKD', 'Hong Kong Dollar (HKD)', 0.12884,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (57, 'HUF', 'Hungarian Forint (HUF)', 0.00461,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (58, 'ISK', 'Iceland Krona (ISK)', 0.01436,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (59, 'INR', 'Indian Rupee (INR)', 0.02253,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (60, 'IDR', 'Indonesian Rupiah (IDR)', 0.00011,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (61, 'IRR', 'Iran Rial (IRR)', 0.00011, 'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (62, 'ILS', 'Israeli Shekel (ILS)', 0.21192,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (63, 'JMD', 'Jamaican Dollar (JMD)', 0.01536,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (64, 'JPY', 'Japanese Yen (JPY)', 0.00849,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (65, 'JOD', 'Jordanian Dinar (JOD)', 1.41044,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (66, 'KZT', 'Kazakhstan Tenge (KZT)', 0.00773,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (67, 'KES', 'Kenyan Shilling (KES)', 0.01392,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (68, 'KRW', 'Korean Won (KRW)', 0.00102,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (69, 'KWD', 'Kuwaiti Dinar (KWD)', 3.42349,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (70, 'LAK', 'Lao Kip (LAK)', 0.00000, 'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (71, 'LVL', 'Latvian Lat (LVL)', 1.71233,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (72, 'LBP', 'Lebanese Pound (LBP)', 0.00067,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (73, 'LSL', 'Lesotho Loti (LSL)', 0.15817,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (74, 'LYD', 'Libyan Dinar (LYD)', 0.00000,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (75, 'LTL', 'Lithuanian Lita (LTL)', 0.34510, 'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (76, 'MOP', 'Macau Pataca (MOP)', 0.12509,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (77, 'MKD', 'Macedonian Denar (MKD)', 0.01945,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (78, 'MGF', 'Malagasy Franc (MGF)', 0.00011,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (79, 'MWK', 'Malawi Kwacha (MWK)', 0.00752, 'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (80, 'MYR', 'Malaysian Ringgit (MYR)', 0.26889,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (81, 'MVR', 'Maldives Rufiyaa (MVR)', 0.07813,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (82, 'MTL', 'Maltese Lira (MTL)', 2.77546,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (83, 'MRO', 'Mauritania Ougulya (MRO)', 0.00369,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (84, 'MUR', 'Mauritius Rupee (MUR)', 0.03258,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (85, 'MXN', 'Mexican Peso (MXN)', 0.09320,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (86, 'MDL', 'Moldovan Leu (MDL)', 0.07678,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (87, 'MNT', 'Mongolian Tugrik (MNT)', 0.00084,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (88, 'MAD', 'Moroccan Dirham (MAD)', 0.10897,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (89, 'MZM', 'Mozambique Metical (MZM)', 0.00004,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (90, 'NAD', 'Namibian Dollar (NAD)', 0.15817, 'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (91, 'NPR', 'Nepalese Rupee (NPR)', 0.01408, 'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (92, 'ANG', 'Neth Antilles Guilder (ANG)', 0.55866,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (93, 'TRY', 'New Turkish Lira (TRY)', 0.73621,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (94, 'NZD', 'New Zealand Dollar (NZD)', 0.65096,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (95, 'NIO', 'Nicaragua Cordoba (NIO)', 0.05828,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (96, 'NGN', 'Nigerian Naira (NGN)', 0.00777,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (97, 'NOK', 'Norwegian Krone (NOK)', 0.14867,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (98, 'OMR', 'Omani Rial (OMR)', 2.59740,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (99, 'XPF', 'Pacific Franc (XPF)', 0.00999,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (100, 'PKR', 'Pakistani Rupee (PKR)', 0.01667,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (101, 'XPD', 'Palladium Ounces (XPD)', 277.77778,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (102, 'PAB', 'Panama Balboa (PAB)', 1.00000,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (103, 'PGK', 'Papua New Guinea Kina (PGK)', 0.33125,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (104, 'PYG', 'Paraguayan Guarani (PYG)', 0.00017,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (105, 'PEN', 'Peruvian Nuevo Sol (PEN)', 0.29999,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (106, 'PHP', 'Philippine Peso (PHP)', 0.01945,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (107, 'XPT', 'Platinum Ounces (XPT)', 1000.00000,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (108, 'PLN', 'Polish Zloty (PLN)', 0.30574, 'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (109, 'QAR', 'Qatar Rial (QAR)', 0.27476,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (110, 'ROL', 'Romanian Leu (ROL)', 0.00000,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (111, 'RON', 'Romanian New Leu (RON)', 0.34074,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (112, 'RUB', 'Russian Rouble (RUB)', 0.03563,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (113, 'RWF', 'Rwanda Franc (RWF)', 0.00185,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (114, 'WST', 'Samoa Tala (WST)', 0.35492,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (115, 'STD', 'Sao Tome Dobra (STD)', 0.00000,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (116, 'SAR', 'Saudi Arabian Riyal (SAR)', 0.26665,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (117, 'SCR', 'Seychelles Rupee (SCR)', 0.18114,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (118, 'SLL', 'Sierra Leone Leone (SLL)', 0.00034,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (119, 'XAG', 'Silver Ounces (XAG)', 9.77517,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (120, 'SGD', 'Singapore Dollar (SGD)', 0.61290,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (121, 'SKK', 'Slovak Koruna (SKK)', 0.03157, 'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (122, 'SIT', 'Slovenian Tolar (SIT)', 0.00498,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (123, 'SOS', 'Somali Shilling (SOS)', 0.00000, 'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (124, 'ZAR', 'South African Rand (ZAR)', 0.15835, 'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (125, 'LKR', 'Sri Lanka Rupee (LKR)', 0.00974,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (126, 'SHP', 'St Helena Pound (SHP)', 0.00000,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (127, 'SDD', 'Sudanese Dinar (SDD)', 0.00427,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (128, 'SRG', 'Surinam Guilder (SRG)', 0.36496,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (129, 'SZL', 'Swaziland Lilageni (SZL)', 0.15817,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (130, 'SEK', 'Swedish Krona (SEK)', 0.12609,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (131, 'CHF', 'Swiss Franc (CHF)', 0.76435,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (132, 'SYP', 'Syrian Pound (SYP)', 0.00000,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (133, 'TWD', 'Taiwan Dollar (TWD)', 0.03075,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (134, 'TZS', 'Tanzanian Shilling (TZS)', 0.00083,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (135, 'THB', 'Thai Baht (THB)', 0.02546,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (136, 'TOP', 'Tonga Paanga (TOP)', 0.48244, 'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (137, 'TTD', 'Trinidad&Tobago Dollar (TTD)', 0.15863,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (138, 'TND', 'Tunisian Dinar (TND)', 0.73470,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (139, 'USD', 'U.S. Dollar (USD)', 1.00000,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (140, 'AED', 'UAE Dirham (AED)', 0.27228,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (141, 'UGX', 'Ugandan Shilling (UGX)', 0.00055, 'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (142, 'UAH', 'Ukraine Hryvnia (UAH)', 0.19755,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (143, 'UYU', 'Uruguayan New Peso (UYU)', 0.04119,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (144, 'VUV', 'Vanuatu Vatu (VUV)', 0.00870,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (145, 'VEB', 'Venezuelan Bolivar (VEB)', 0.00037,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (146, 'VND', 'Vietnam Dong (VND)', 0.00006,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (147, 'YER', 'Yemen Riyal (YER)', 0.00510,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (148, 'ZMK', 'Zambian Kwacha (ZMK)', 0.00031, 'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (149, 'ZWD', 'Zimbabwe Dollar (ZWD)', 0.00001,  'USD');
INSERT INTO cc_currencies (id, currency, name, value, basecurrency) VALUES (150, 'GYD', 'Guyana Dollar (GYD)', 0.00527,  'USD');


CREATE TABLE cc_backup (
    id 										BIGSERIAL NOT NULL,
    name 									CHARACTER VARYING(255) UNIQUE DEFAULT ''::CHARACTER varying NOT NULL,
    path 									CHARACTER VARYING(255) DEFAULT ''::CHARACTER varying NOT NULL,
    creationdate 							TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    PRIMARY KEY (id)
);


CREATE TABLE cc_ecommerce_product (
    id 										BIGSERIAL NOT NULL,
    product_name 							TEXT NOT NULL,
    creationdate 							TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    description 							TEXT,
    expirationdate 							TIMESTAMP WITHOUT TIME ZONE,
    enableexpire 							INTEGER DEFAULT 0,
    expiredays 								INTEGER DEFAULT 0,
    credit 									NUMERIC(12,4) NOT NULL,
    tariff 									INTEGER DEFAULT 0,
    id_didgroup 							INTEGER DEFAULT 0,
    mailtype 								CHARACTER VARYING(50) DEFAULT ''::CHARACTER varying NOT NULL,
    activated 								BOOLEAN DEFAULT false NOT NULL,
    simultaccess 							INTEGER DEFAULT 0,
    currency 								CHARACTER VARYING(3) DEFAULT 'USD'::CHARACTER varying,
    typepaid 								INTEGER DEFAULT 0,
    creditlimit 							INTEGER DEFAULT 0,
    "language" 								TEXT DEFAULT 'en'::text,
    runservice 								INTEGER DEFAULT 0,
    sip_friend 								INTEGER DEFAULT 0,
    iax_friend 								INTEGER DEFAULT 0,
    PRIMARY KEY (id)
);


--
-- Speed Dial Table
--

CREATE TABLE cc_speeddial (
    id 									BIGSERIAL NOT NULL,
    id_cc_card 							BIGINT DEFAULT 0 NOT NULL,
    phone 								TEXT NOT NULL,
    name 								TEXT NOT NULL,
    speeddial 							INTEGER DEFAULT 0,
    creationdate 						TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    PRIMARY KEY (id),
    CONSTRAINT cons_cc_speeddial_pkey UNIQUE (id_cc_card, speeddial)
);


-- Auto Refill Report Table
CREATE TABLE cc_autorefill_report (
	id 									BIGSERIAL NOT NULL,
	daterun 							TIMESTAMP(0) without time zone DEFAULT NOW(),
	totalcardperform 					INTEGER,
	totalcredit 						double precision,
	PRIMARY KEY (id)
);


-- cc_prefix Table
CREATE TABLE cc_prefix (
	prefix 				BIGSERIAL NOT NULL,
	destination 		varchar(60) NOT NULL,
	PRIMARY KEY (prefix)
);
CREATE INDEX cc_prefix_dest ON cc_prefix USING btree(destination);


CREATE TABLE cc_alarm (
    id 								BIGSERIAL NOT NULL,
    name 							TEXT NOT NULL,
    periode 						INTEGER NOT NULL DEFAULT 1,
    type 							INTEGER NOT NULL DEFAULT 1,
    maxvalue 						numeric NOT NULL,
    minvalue 						numeric NOT NULL DEFAULT -1,
    id_trunk 						INTEGER,
    status 							INTEGER NOT NULL DEFAULT 0,
    numberofrun 					INTEGER NOT NULL DEFAULT 0,
    numberofalarm 					INTEGER NOT NULL DEFAULT 0,
    datecreate 						TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    datelastrun 					TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    emailreport 					TEXT,
    PRIMARY KEY (id)
);


CREATE TABLE cc_alarm_report (
    id 								BIGSERIAL NOT NULL,
    cc_alarm_id 					BIGINT NOT NULL,
    calculatedvalue 				numeric NOT NULL,
    daterun 						TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    PRIMARY KEY (id)
);


CREATE TABLE cc_callback_spool (
    id 								BIGSERIAL NOT NULL,
    uniqueid 						TEXT UNIQUE,
    entry_time 						TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    status 							TEXT,
    server_ip 						TEXT,
    num_attempt 					int NOT NULL DEFAULT 0,
    last_attempt_time 				TIMESTAMP WITHOUT TIME ZONE,
    manager_result 					TEXT,
    agi_result 						TEXT,
    callback_time 					TIMESTAMP WITHOUT TIME ZONE,
    channel 						TEXT,
    exten 							TEXT,
    context 						TEXT,
    priority 						TEXT,
    application 					TEXT,
    data 							TEXT,
    timeout 						TEXT,
    callerid 						TEXT,
	variable						CHARACTER VARYING(300),
    account 						TEXT,
    async 							TEXT,
    actionid 						TEXT,
	id_server						INTEGER,
	id_server_group					INTEGER,
	PRIMARY KEY (id)
) WITH OIDS;


CREATE TABLE cc_server_manager (
    id 								BIGSERIAL NOT NULL,
	id_group						INTEGER DEFAULT 1,
    server_ip 						TEXT,
    manager_host 					TEXT,
    manager_username 				TEXT,
    manager_secret 					TEXT,
	lasttime_used		 			TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
	PRIMARY KEY (id)
) WITH OIDS;
INSERT INTO cc_server_manager (id_group, server_ip, manager_host, manager_username, manager_secret) VALUES (1, 'localhost', 'localhost', 'myasterisk', 'mycode');


CREATE TABLE cc_server_group (
	id								BIGSERIAL NOT NULL,
	name							TEXT,
	description						TEXT,
	PRIMARY KEY (id)
) WITH OIDS;
INSERT INTO cc_server_group (id, name, description) VALUES (1, 'default', 'default group of server');


CREATE TABLE cc_package_offer (
    id 								BIGSERIAL NOT NULL,
    creationdate 					TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    label 							TEXT NOT NULL,
    packagetype 					INTEGER NOT NULL,
	billingtype 					INTEGER NOT NULL,
	startday 						INTEGER NOT NULL,
	freetimetocall 					INTEGER NOT NULL
);
-- packagetype : Free minute + Unlimited ; Free minute ; Unlimited ; Normal
-- billingtype : Monthly ; Weekly
-- startday : according to billingtype ; if monthly value 1-31 ; if Weekly value 1-7 (Monday to Sunday)


CREATE TABLE cc_card_package_offer (
    id 					BIGSERIAL NOT NULL,
	id_cc_card 			BIGINT NOT NULL,
	id_cc_package_offer BIGINT NOT NULL,
    date_consumption 	TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
	used_secondes 		BIGINT NOT NULL
);
CREATE INDEX ind_cc_card_package_offer_id_card ON cc_card_package_offer USING btree (id_cc_card);
CREATE INDEX ind_cc_card_package_offer_id_package_offer ON cc_card_package_offer USING btree (id_cc_package_offer);
CREATE INDEX ind_cc_card_package_offer_date_consumption ON cc_card_package_offer USING btree (date_consumption);


CREATE TABLE cc_subscription_fee (
    id 				BIGSERIAL NOT NULL,
    label 			TEXT NOT NULL,
	fee 			NUMERIC(12,4) NOT NULL,
	currency 		CHARACTER VARYING(3) DEFAULT 'USD'::CHARACTER varying,
	status 			INTEGER NOT NULL DEFAULT 0,
    numberofrun 	INTEGER NOT NULL DEFAULT 0,
    datecreate 		TIMESTAMP(0) without time zone DEFAULT NOW(),
    datelastrun 	TIMESTAMP(0) without time zone DEFAULT NOW(),
    emailreport 	TEXT,
    totalcredit 	DOUBLE PRECISION NOT NULL DEFAULT 0,
    totalcardperform INTEGER NOT NULL DEFAULT 0,
    PRIMARY KEY (id)
);


CREATE TABLE cc_outbound_cid_group (
    id 					BIGSERIAL NOT NULL,
    creationdate 		TIMESTAMP(0) without time zone DEFAULT NOW(),
    group_name 			TEXT NOT NULL,
    PRIMARY KEY (id)
);


CREATE TABLE cc_outbound_cid_list (
	id 					BIGSERIAL NOT NULL,
	outbound_cid_group	BIGINT NOT NULL,
	cid					TEXT NOT NULL,
	activated 			INTEGER NOT NULL DEFAULT 0,
	creationdate 		TIMESTAMP(0) without time zone DEFAULT NOW(),
	PRIMARY KEY (id)
);


CREATE TABLE cc_payment_methods (
    id 									BIGSERIAL NOT NULL,
    payment_method 						TEXT NOT NULL,
    payment_filename 					TEXT NOT NULL,
    PRIMARY KEY (id)
);
INSERT INTO cc_payment_methods (payment_method,payment_filename) VALUES ('paypal','paypal.php');
INSERT INTO cc_payment_methods (payment_method,payment_filename) VALUES ('Authorize.Net','authorizenet.php');
INSERT INTO cc_payment_methods (payment_method,payment_filename) VALUES ('MoneyBookers','moneybookers.php');
INSERT INTO cc_payment_methods (payment_method,payment_filename) VALUES ('plugnpay','plugnpay.php');


CREATE TABLE cc_payments (
	id 									BIGSERIAL NOT NULL,
	customers_id						BIGINT DEFAULT '0' NOT NULL,
	customers_name						TEXT NOT NULL,
	customers_email_address 			TEXT NOT NULL,
	item_name 							TEXT NOT NULL,
	item_id 							TEXT NOT NULL,
	item_quantity 						INTEGER NOT NULL DEFAULT 0,
	payment_method 						VARCHAR(32) NOT NULL,
	cc_type 							CHARACTER VARYING(20),
	cc_owner 							CHARACTER VARYING(64),
	cc_number 							CHARACTER VARYING(32),
	cc_expires 							CHARACTER VARYING(6),
	orders_status 						INTEGER NOT NULL,
	orders_amount 						NUMERIC(14,6),
	last_modified 						TIMESTAMP WITHOUT TIME ZONE,
	date_purchased 						TIMESTAMP WITHOUT TIME ZONE,
	orders_date_finished 				TIMESTAMP WITHOUT TIME ZONE,
	currency 							CHARACTER VARYING(3),
	currency_value 						decimal(14,6),
	PRIMARY KEY (id)
);


CREATE TABLE cc_payments_agent (
	id									BIGSERIAL,
	agent_id							BIGINT NOT NULL,
	agent_name							varchar(200) NOT NULL,
	agent_email_address					varchar(96) NOT NULL,
	item_name							varchar(127) default NULL,
	item_id								varchar(127) default NULL,
	item_quantity						int NOT NULL default '0',
	payment_method						varchar(32) NOT NULL,
	cc_type								varchar(20) default NULL,
	cc_owner							varchar(64) default NULL,
	cc_number							varchar(32) default NULL,
	cc_expires							varchar(4) default NULL,
	orders_status						int NOT NULL,
	orders_amount						decimal(14,6) default NULL,
	last_modified						timestamp without time zone default NULL,
	date_purchased						timestamp without time zone default NULL,
	orders_date_finished				timestamp without time zone default NULL,
	currency							char(3) default NULL,
	currency_value						decimal(14,6) default NULL,
	PRIMARY KEY (id)
);


CREATE TABLE cc_payments_status (
	id 									BIGSERIAL NOT NULL,
	status_id 							INTEGER NOT NULL,
	status_name 						CHARACTER VARYING(200) NOT NULL,
	PRIMARY KEY (id)
);
INSERT INTO cc_payments_status (status_id,status_name) VALUES (-2, 'Failed');
INSERT INTO cc_payments_status (status_id,status_name) VALUES (-1, 'Denied');
INSERT INTO cc_payments_status (status_id,status_name) VALUES (0, 'Pending');
INSERT INTO cc_payments_status (status_id,status_name) VALUES (1, 'In-Progress');
INSERT INTO cc_payments_status (status_id,status_name) VALUES (2, 'Completed');
INSERT INTO cc_payments_status (status_id,status_name) VALUES (3, 'Processed');
INSERT INTO cc_payments_status (status_id,status_name) VALUES (4, 'Refunded');
INSERT INTO cc_payments_status (status_id,status_name) VALUES (5, 'Unknown');


CREATE TABLE cc_configuration (
  configuration_id 						BIGSERIAL NOT NULL,
  configuration_title 					CHARACTER VARYING(64) NOT NULL,
  configuration_key 					CHARACTER VARYING(64) NOT NULL,
  configuration_value 					CHARACTER VARYING(255) NOT NULL,
  configuration_description 			CHARACTER VARYING(255) NOT NULL,
  configuration_type 					INTEGER NOT NULL DEFAULT 0,
  use_function 							CHARACTER VARYING(255) NULL,
  set_function 							CHARACTER VARYING(255) NULL,
  PRIMARY KEY (configuration_id)
);


insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description) values ('Login Username', 'MODULE_PAYMENT_AUTHORIZENET_LOGIN', 'testing', 'The login username used for the Authorize.net service');
insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description) values ('Transaction Key', 'MODULE_PAYMENT_AUTHORIZENET_TXNKEY', 'Test', 'Transaction Key used for encrypting TP data');
insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Transaction Mode', 'MODULE_PAYMENT_AUTHORIZENET_TESTMODE', 'Test', 'Transaction mode used for processing orders', 'tep_cfg_select_option(array(\'Test\', \'Production\'), ');
insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Transaction Method', 'MODULE_PAYMENT_AUTHORIZENET_METHOD', 'Credit Card', 'Transaction method used for processing orders', 'tep_cfg_select_option(array(\'Credit Card\', \'eCheck\'), ');
insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Customer Notifications', 'MODULE_PAYMENT_AUTHORIZENET_EMAIL_CUSTOMER', 'False', 'Should Authorize.Net e-mail a receipt to the customer?', 'tep_cfg_select_option(array(\'True\', \'False\'), ');
insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Enable Authorize.net Module', 'MODULE_PAYMENT_AUTHORIZENET_STATUS', 'True', 'Do you want to accept Authorize.net payments?', 'tep_cfg_select_option(array(\'True\', \'False\'), ');

insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Enable PayPal Module', 'MODULE_PAYMENT_PAYPAL_STATUS', 'True', 'Do you want to accept PayPal payments?','tep_cfg_select_option(array(\'True\', \'False\'), ');
insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description) values ('E-Mail Address', 'MODULE_PAYMENT_PAYPAL_ID', 'you@yourbusiness.com', 'The e-mail address to use for the PayPal service');
insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Transaction Currency', 'MODULE_PAYMENT_PAYPAL_CURRENCY', 'Alternative Transaction Currency', 'The alternative currency to use for credit card transactions if the system currency is not usable', 'tep_cfg_select_option(array(''USD'',''CAD'',''EUR'',''GBP'',''JPY''), ');

insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description) values ('E-Mail Address', 'MODULE_PAYMENT_MONEYBOOKERS_ID', 'you@yourbusiness.com', 'The eMail address to use for the moneybookers service');
insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description) values ('Referral ID', 'MODULE_PAYMENT_MONEYBOOKERS_REFID', '989999', 'Your personal Referral ID from moneybookers.com');
insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Transaction Currency', 'MODULE_PAYMENT_MONEYBOOKERS_CURRENCY', 'Alternative Transaction Currency', 'The alternative currency to use for credit card transactions if the system currency is not usable', 'tep_cfg_select_option(array(''EUR'', ''USD'', ''GBP'', ''HKD'', ''SGD'', ''JPY'', ''CAD'', ''AUD'', ''CHF'', ''DKK'', ''SEK'', ''NOK'', ''ILS'', ''MYR'', ''NZD'', ''TWD'', ''THB'', ''CZK'', ''HUF'', ''SKK'', ''ISK'', ''INR''), ');
insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Transaction Language', 'MODULE_PAYMENT_MONEYBOOKERS_LANGUAGE', 'Selected Language', 'The default language for the payment transactions', 'tep_cfg_select_option(array(\'Selected Language\',\'EN\', \'DE\', \'ES\', \'FR\'), ');
insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Enable moneybookers Module', 'MODULE_PAYMENT_MONEYBOOKERS_STATUS', 'True', 'Do you want to accept moneybookers payments?','tep_cfg_select_option(array(\'True\', \'False\'), ');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Enable PlugnPay Module', 'MODULE_PAYMENT_PLUGNPAY_STATUS', 'True', 'Do you want to accept payments through PlugnPay?', 'tep_cfg_select_option(array(\'True\', \'False\'), ');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description) values ('Login Username', 'MODULE_PAYMENT_PLUGNPAY_LOGIN', 'Your Login Name', 'Enter your PlugnPay account username');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description) values ('Publisher Email', 'MODULE_PAYMENT_PLUGNPAY_PUBLISHER_EMAIL', 'Enter Your Email Address', 'The email address you want PlugnPay conformations sent to');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('cURL Setup', 'MODULE_PAYMENT_PLUGNPAY_CURL', 'Not Compiled', 'Whether cURL is compiled into PHP or not.  Windows users, select not compiled.', 'tep_cfg_select_option(array(\'Not Compiled\', \'Compiled\'), ');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description) values ('cURL Path', 'MODULE_PAYMENT_PLUGNPAY_CURL_PATH', 'The Path To cURL', 'For Not Compiled mode only, input path to the cURL binary (i.e. c:/curl/curl)');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Transaction Mode', 'MODULE_PAYMENT_PLUGNPAY_TESTMODE', 'Test', 'Transaction mode used for processing orders', 'tep_cfg_select_option(array(\'Test\', \'Test And Debug\', \'Production\'), ');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Require CVV', 'MODULE_PAYMENT_PLUGNPAY_CVV', 'yes', 'Ask For CVV information', 'tep_cfg_select_option(array(\'yes\', \'no\'), ');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Transaction Method', 'MODULE_PAYMENT_PLUGNPAY_PAYMETHOD', 'credit', 'Transaction method used for processing orders.<br><b>NOTE:</b> Selecting \'onlinecheck\' assumes you will offer \'credit\' as well.',  'tep_cfg_select_option(array(\'credit\', \'onlinecheck\'), ');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Authorization Type', 'MODULE_PAYMENT_PLUGNPAY_CCMODE', 'authpostauth', 'Credit card processing mode', 'tep_cfg_select_option(array(\'authpostauth\', \'authonly\'), ');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Customer Notifications', 'MODULE_PAYMENT_PLUGNPAY_DONTSNDMAIL', 'yes', 'Should PlugnPay not email a receipt to the customer?', 'tep_cfg_select_option(array(\'yes\', \'no\'), ');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Accepted Credit Cards', 'MODULE_PAYMENT_PLUGNPAY_ACCEPTED_CC', 'Mastercard, Visa', 'The credit cards you currently accept', '_selectOptions(array(\'Amex\',\'Discover\', \'Mastercard\', \'Visa\'), ');


CREATE TABLE cc_epayment_log (
    id 					BIGSERIAL NOT NULL,
    cardid 				BIGINT NOT NULL DEFAULT 0,
	amount 				DECIMAL(15, 5) NOT NULL DEFAULT 0,
	vat 				DOUBLE PRECISION NOT NULL DEFAULT 0,
	paymentmethod		CHARACTER VARYING(255) NOT NULL,
    cc_owner 			CHARACTER VARYING(255) NOT NULL,
    cc_number 			CHARACTER VARYING(255) NOT NULL,
    cc_expires 			CHARACTER VARYING(255) NOT NULL,
    creationdate 		TIMESTAMP(0) without time zone DEFAULT NOW(),
    status 				INTEGER NOT NULL DEFAULT 0,
    cvv					VARCHAR(4),
    credit_card_type	VARCHAR(20),
    currency			VARCHAR(4),
    transaction_detail	TEXT NULL,
    PRIMARY KEY (id)
);


CREATE TABLE cc_system_log (
    id								BIGSERIAL NOT NULL,
    iduser							INTEGER NOT NULL DEFAULT 0,
    loglevel	 					INTEGER NOT NULL DEFAULT 0,
    action			 				TEXT NOT NULL,
    description						TEXT,
    data			 				TEXT,
	tablename						CHARACTER VARYING(255),
	pagename			 			CHARACTER VARYING(255),
	ipaddress						CHARACTER VARYING(255),
	creationdate					TIMESTAMP(0) without time zone DEFAULT NOW(),
	PRIMARY KEY (id)
);

CREATE TABLE cc_card_subscription (
	id								BIGSERIAL NOT NULL,
	id_cc_card						BIGINT DEFAULT 0 NOT NULL,
	id_subscription_fee 			INTEGER DEFAULT 0 NOT NULL,
	startdate 						TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
	stopdate 						TIMESTAMP WITHOUT TIME ZONE,
	product_id						CHARACTER VARYING(100) DEFAULT NULL,
	product_name 					CHARACTER VARYING(100) DEFAULT NULL,
	PRIMARY KEY (id)
);


CREATE TABLE cc_config_group (
	id 								SERIAL NOT NULL,
	group_title 					CHARACTER VARYING(64) NOT NULL,
	group_description 				CHARACTER VARYING(255) NOT NULL,
	PRIMARY KEY (id)
);
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
INSERT INTO cc_config_group (group_title ,group_description) VALUES ('notifications', 'This configuration group handles the notifcations configuration');
INSERT INTO cc_config_group (group_title, group_description) VALUES ('dashboard', 'This configuration group handles the dashboard configuration');


CREATE TABLE cc_config (
	id								SERIAL NOT NULL,
	config_title		 			CHARACTER VARYING(100) NOT NULL,
	config_key						CHARACTER VARYING(100) NOT NULL,
	config_value					TEXT NOT NULL,  -- Some of the data to insert is > 100 chars! XXX
	config_description				TEXT NOT NULL,  -- Some of the data to insert is > 255 chars! XXX
	config_valuetype				INTEGER NOT NULL DEFAULT 0,
	config_group_id					INTEGER NOT NULL,
	config_listvalues				TEXT,
	PRIMARY KEY (id)
);
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
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Trunk Name', 'sip_iax_info_trunkname', 'YourDomain', 'Trunk Name to show in sip/iax info.', 0, 4, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Codecs Allowed', 'sip_iax_info_allowcodec', 'g729', 'Allowed Codec, ulaw, gsm, g729.', 0, 4, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Host', 'sip_iax_info_host', 'YourDomain.com', 'Host information.', 0, 4, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('IAX Parms', 'iax_additional_parameters', 'canreinvite = no', 'IAX Additional Parameters.', 0, 4, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('SIP Parms', 'sip_additional_parameters', 'trustrpid = yes | sendrpid = yes | canreinvite = no', 'SIP Additional Parameters.', 0, 4, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Enable', 'enable', 1, 'Enable/Disable.', 1, 5, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('HTTP Server Customer', 'http_server', 'http://www.YourDomain.com', 'Set the Server Address of Customer Website, It should be empty for productive Servers.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('HTTPS Server Customer', 'https_server', 'https://www.YourDomain.com', 'https://localhost - Enter here your Secure Customers Server Address, should not be empty for productive servers.', 0, 5, NULL);
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
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Card Export Fields', 'card_export_field_list', 'id, username, useralias, lastname, credit, tariff, activated, language, inuse, currency, sip_buddy, iax_buddy, nbused, mac_addr, template_invoice, template_outstanding', 'Fields to export in csv format from cc_card table.', 0, 8, NULL);
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
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Dial Command Params', 'dialcommand_param', '|60|HRirL(%timeout%:61000:30000)', 'More information about the Dial : http://voip-info.org/wiki-Asterisk+cmd+dial<br>30 :  The timeout parameter is optional. If not specifed, the Dial command will wait indefinitely, exiting only when the originating channel hangs up, or all the dialed channels return a busy or error condition. Otherwise it specifies a maximum time, in seconds, that the Dial command is to wait for a channel to answer.<br>H: Allow the caller to hang up by dialing * <br>r: Generate a ringing tone for the calling party<br>R: Indicate ringing to the calling party when the called party indicates ringing, pass no audio until answered.<br>g: When the called party hangs up, exit to execute more commands in the current context. (new in 1.4)<br>i: Asterisk will ignore any forwarding (302 Redirect) requests received. Essential for DID usage to prevent fraud. (new in 1.4)<br>m: Provide Music on Hold to the calling party until the called channel answers.<br>L(x[:y][:z]): Limit the call to ''x'' ms, warning when ''y'' ms are left, repeated every ''z'' ms)<br>%timeout% tag is replaced by the calculated timeout according the credit & destination rate!.', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('SIP/IAX Dial Command Params', 'dialcommand_param_sipiax_friend', '|60|HiL(3600000:61000:30000)', 'by default (3600000  =  1HOUR MAX CALL).', 0, 11, NULL);
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
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Bill Callback', 'callback_bill_1stleg_ifcall_notconnected', 1, 'Define if you want to bill the 1st leg on callback even if the call is not connected to the destination.', 1, 11, 'yes,no');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('International prefixes', 'international_prefixes', '011,00,09,1', 'List the prefixes you want stripped off if the call plan requires it', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Server GMT', 'server_GMT', 'GMT+10:00', 'Define the sever gmt time', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Invoice Template Path', 'invoice_template_path', '../invoice/', 'gives invoice template path from default one', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Outstanding Template Path', 'outstanding_template_path', '../outstanding/', 'gives outstanding template path from default one', 0, 1, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Sales Template Path', 'sales_template_path', '../sales/', 'gives sales template path from default one', 0, 1, NULL);
-- Add payment history in customer WebUI
INSERT INTO cc_config( config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues )
VALUES ('Payment Historique Modules', 'payment', '1', 'Enable or Disable the module of payment historique for the customers', 1, 3, 'yes,no');
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
INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues) VALUES ( 'Max Time For Unlimited Calls', 'maxtime_tounlimited_calls', '5400', 'For unlimited calls, limit the duration: amount in seconds .', '0', '11', NULL), ( 'Max Time For Free Calls', 'maxtime_tofree_calls', '5400', 'For free calls, limit the duration: amount in seconds .', '0', '11', NULL);
-- Add new configuration payment agent
INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues) VALUES ( 'Payment Amount', 'purchase_amount_agent', '100:200:500:1000', 'define the different amount of purchase that would be available.', '0', '5', NULL);
INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
 VALUES ( 'List of possible values to notify', 'values_notifications', '10:20:50:100:500:1000', 'Possible values to choose when the user receive a notification. You can define a List e.g: 10:20:100.', '0', '12', NULL);
INSERT INTO cc_config ( config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues)
 VALUES ( 'Notifications Modules', 'notification', '1', 'Enable or Disable the module of notification for the customers', 1, 3, 'yes,no');
INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ( 'Notications Cron Module', 'cron_notifications', '1', 'Enable or Disable the cron module of notification for the customers. If it correctly configured in the crontab', '0', '12', 'yes,no');
INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ( 'Notications Delay', 'delay_notifications', '1', 'Delay in number of days to send an other notification for the customers. If the value is 0, it will notify the user everytime the cront is running.', '0', '12', NULL);
INSERT INTO cc_config( config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ( 'Support Modules', 'support', '1', 'Enable or Disable the module of support', 1, 3, 'yes,no');
-- ADD MISSING extracharge_did settings
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Extra charge DIDs', 'extracharge_did', '1800,1900', 'Add extra per-minute charges to this comma-separated list of DNIDs; needs "extracharge_fee" and "extracharge_buyfee"', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Extra charge DID fees', 'extracharge_fee', '0.05,0.15', 'Comma-separated list of extra sell-rate charges corresponding to the DIDs in "extracharge_did"', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Extra charge DID buy fees', 'extracharge_buyfee', '0.04,0.13', 'Comma-separated list of extra buy-rate charges corresponding to the DIDs in "extracharge_did"', 0, 11, NULL);
INSERT INTO  cc_config (config_title,config_key,config_value,config_description,config_valuetype,config_group_id) values('Card Serial Pad Length','card_serial_length','7','Value of zero padding for serial. If this value set to 3 serial wil looks like 001',0,8);
-- Reserve credit :
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('Dial Balance reservation', 'dial_balance_reservation', '0.25', 'Credit to reserve from the balance when a call is made. This will prevent negative balance on huge peak.', 0, 11, NULL);
INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ('Menu Language Order', 'conf_order_menulang', 'en:fr:es', 'Enter the list of languages authorized for the menu.Use the code language separate by a colon charactere e.g: en:es:fr', '0', '11', NULL);
INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ( 'Disable annoucement the second of the times that the card can call', 'disable_announcement_seconds', '0', 'Desactived the annoucement of the seconds when there are more of one minutes (values : yes - no)', '1', '11', 'yes,no');
INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ( 'Charge for the paypal extra fees', 'charge_paypal_fee', '0', 'Actived, if you want assum the fee of paypal and don''t apply it on the customer (values : yes - no)', '1', '5', 'yes,no');
INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ('Cents Currency Associated', 'currency_cents_association', '', 'Define all the audio (without file extensions) that you want to play according to cents currency (use , to separate, ie "amd:lumas").By default the file used is "prepaid-cents" .Use plural to define the cents currency sound, but import two sounds but cents currency defined : ending by ''s'' and not ending by ''s'' (i.e. for lumas , add 2 files : ''lumas'' and ''luma'') ', '0', '11', NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES
( 'Context Campaign''s Callback', 'context_campaign_callback', 'a2billing-campaign-callback', 'Context to use in Campaign of Callback', '0', '2', NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES
( 'Default Context forward Campaign''s Callback ', 'default_context_campaign', 'campaign', 'Context to use by default to forward the call in Campaign of Callback', '0', '2', NULL);
INSERT INTO  cc_config (config_title,config_key,config_value,config_description,config_valuetype,config_group_id) values ('Card Show Fields','card_show_field_list','id:,username:, useralias:, lastname:,id_group:, id_agent:,  credit:, tariff:, status:, language:, inuse:, currency:, sip_buddy:, iax_buddy:, nbused, id_seria, serial:','Fields to show in Customer. Order is important. You can setup size of field using "fieldname:10%" notation or "fieldname:" for harcoded size,"fieldname" for autosize. <br/>You can use:<br/> id,username, useralias, lastname, id_group, id_agent,  credit, tariff, status, language, inuse, currency, sip_buddy, iax_buddy, nbused, firstname, email, discount, callerid',0,8);

-- ------------------------------------------------------
-- Cache system with SQLite Agent
-- ------------------------------------------------------
INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ( 'Enable CDR local cache', 'cache_enabled', '0', 'If you want enabled the local cache to save the CDR in a SQLite Database.', '1', '1', 'yes,no'),
( 'Path for the CDR cache file', 'cache_path', '/etc/asterisk/cache_a2billing', 'Defined the file that you want use for the CDR cache to save the CDR in a local SQLite database.', '0', '1', NULL);

-- ------------------------------------------------------
-- PNL report
-- ------------------------------------------------------
INSERT INTO  cc_config(config_title,config_key,config_value,config_description,config_valuetype,config_group_id) values
('PNL Pay Phones','report_pnl_pay_phones','(8887798764,0.02,0.06)','Info for PNL report. Must be in form "(number1,buycost,sellcost),(number2,buycost,sellcost)", number can be prefix, i.e 1800',0,8);
INSERT INTO  cc_config(config_title,config_key,config_value,config_description,config_valuetype,config_group_id) values
('PNL Toll Free Numbers','report_pnl_toll_free','(6136864646,0.1,0),(6477249717,0.1,0)','Info for PNL report. must be in form "(number1,buycost,sellcost),(number2,buycost,sellcost)", number can be prefix, i.e 1800',0,8);

-- Change AGI Verbosity & logging
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues)
VALUES ('Verbosity', 'verbosity_level', '0', '0 = FATAL; 1 = ERROR; WARN = 2 ; INFO = 3 ; DEBUG = 4', 0, 11, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues)
VALUES ('Logging', 'logging_level', '3', '0 = FATAL; 1 = ERROR; WARN = 2 ; INFO = 3 ; DEBUG = 4', 0, 11, NULL);

INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ( 'Enable info module about customers', 'customer_info_enabled', 'LEFT', 'If you want enabled the info module customer and place it somewhere on the home page.', '0', '13', 'NONE,LEFT,CENTER,RIGHT');
INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ( 'Enable info module about refills', 'refill_info_enabled', 'CENTER', 'If you want enabled the info module refills and place it somewhere on the home page.', '0', '13', 'NONE,LEFT,CENTER,RIGHT');
INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ( 'Enable info module about payments', 'payment_info_enabled', 'CENTER', 'If you want enabled the info module payments and place it somewhere on the home page.', '0', '13', 'NONE,LEFT,CENTER,RIGHT');
INSERT INTO cc_config (config_title ,config_key ,config_value ,config_description ,config_valuetype ,config_group_id ,config_listvalues)
VALUES ( 'Enable info module about calls', 'call_info_enabled', 'RIGHT', 'If you want enabled the info module calls and place it somewhere on the home page.', '0', '13', 'NONE,LEFT,CENTER,RIGHT');

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues)
VALUES ('PlugnPay Payment URL', 'plugnpay_payment_url', 'https://pay1.plugnpay.com/payment/pnpremote.cgi', 'Define here the URL of PlugnPay gateway.', 0, 5, NULL);


-- DIDX.NET
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('DIDX ID', 'didx_id', '708XXX', 'DIDX parameter : ID', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('DIDX PASS', 'didx_pass', 'XXXXXXXXXX', 'DIDX parameter : Password', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('DIDX MIN RATING', 'didx_min_rating', '0', 'DIDX parameter : min rating', 0, 8, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_id, config_listvalues) VALUES ('DIDX RING TO', 'didx_ring_to', '0', 'DIDX parameter : ring to', 0, 8, NULL);


-- update on configuration
ALTER TABLE cc_config_group ADD UNIQUE (group_title);
ALTER TABLE cc_config ADD config_group_title varchar(64);

UPDATE cc_config SET config_group_title=(SELECT group_title FROM cc_config_group WHERE cc_config_group.id=cc_config.config_group_id);

ALTER TABLE cc_config DROP COLUMN config_group_id;
ALTER TABLE cc_config ALTER COLUMN config_group_title SET NOT NULL;
-- Agent epayment

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_title, config_listvalues) VALUES ('HTTP Server Agent', 'http_server_agent', 'http://www.YourDomain.com', 'Set the Server Address of Agent Website, It should be empty for productive Servers.', 0, 'epayment_method', NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_title, config_listvalues) VALUES ('HTTPS Server Agent', 'https_server_agent', 'https://www.YourDomain.com', 'https://localhost - Enter here your Secure Agents Server Address, should not be empty for productive servers.', 0, 'epayment_method', NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_title, config_listvalues) VALUES ('Server Agent IP/Domain', 'http_cookie_domain_agent', '26.63.165.200', 'Enter your Domain Name or IP Address for the Agents application, eg, 26.63.165.200.', 0, 5, NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_title, config_listvalues) VALUES ('Secure Server Agent IP/Domain', 'https_cookie_domain_agent', '26.63.165.200', 'Enter your Secure server Domain Name or IP Address for the Agents application, eg, 26.63.165.200.', 0, 'epayment_method', NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_title, config_listvalues) VALUES ('Application Agent Path', 'http_cookie_path_agent', '/agent/Public/', 'Enter the Physical path of your Agents Application on your server.', 0, 'epayment_method', NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_title, config_listvalues) VALUES ('Secure Application Agent Path', 'https_cookie_path_agent', '/agent/Public/', 'Enter the Physical path of your Agents Application on your Secure Server.', 0, 'epayment_method', NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_title, config_listvalues) VALUES ('Application Agent Physical Path', 'dir_ws_http_catalog_agent', '/agent/Public/', 'Enter the Physical path of your Agents Application on your server.', 0, 'epayment_method', NULL);
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_group_title, config_listvalues) VALUES ('Secure Application Agent Physical Path', 'dir_ws_https_catalog_agent', '/agent/Public/', 'Enter the Physical path of your Agents Application on your Secure server.', 0, 'epayment_method', NULL);



CREATE TABLE cc_timezone (
    id 								SERIAL NOT NULL,
    gmtzone							CHARACTER VARYING(255),
    gmttime		 					CHARACTER VARYING(255),
	gmtoffset						BIGINT NOT NULL DEFAULT 0,
	PRIMARY KEY (id)
);
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
    code		TEXT NOT NULL,
    name		TEXT UNIQUE NOT NULL,
    lname		TEXT,
    charset		TEXT NOT NULL DEFAULT 'ISO-8859-1',
    PRIMARY KEY (code)
);
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


CREATE TABLE cc_status_log (
  id						BIGSERIAL NOT NULL,
  status 					INTEGER NOT NULL,
  id_cc_card 				BIGINT NOT NULL,
  updated_date 				TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
  PRIMARY KEY (id)
);


CREATE TABLE cc_card_history (
	id 							BIGSERIAL NOT NULL,
	id_cc_card 					BIGINT ,
    datecreated					TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
	description 				TEXT,
	PRIMARY KEY (id)
);


-- This trigger is to prevent bogus regexes making it into the database
CREATE OR REPLACE FUNCTION cc_ratecard_validate_regex() RETURNS TRIGGER AS $$
  BEGIN
    IF SUBSTRING(new.dialprefix,1,1) != '_' THEN
      RETURN new;
    END IF;
    PERFORM '0' ~* REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE('^' || new.dialprefix || '$', 'X', '[0-9]', 'g'), 'Z', '[1-9]', 'g'), 'N', '[2-9]', 'g'), E'\\.', E'\\.+', 'g'), '_', '', 'g');
    RETURN new;
  END
$$ LANGUAGE plpgsql;

CREATE TRIGGER cc_ratecard_validate_regex BEFORE INSERT OR UPDATE ON cc_ratecard FOR EACH ROW EXECUTE PROCEDURE cc_ratecard_validate_regex();


-- Support / Ticket section

CREATE TABLE cc_support (
	id 				SERIAL NOT NULL,
	"name"			CHARACTER VARYING(50) NOT NULL,
	CONSTRAINT		cc_support_pkey PRIMARY KEY (id)
);


CREATE TABLE cc_support_component (
	id						SERIAL NOT NULL,
	id_support				INTEGER NOT NULL,
	"name"					CHARACTER VARYING(50) NOT NULL DEFAULT ''::CHARACTER VARYING,
	activated					SMALLINT NOT NULL DEFAULT 1,
	CONSTRAINT cc_support_component_pkey PRIMARY KEY (id),
	CONSTRAINT cc_support_id_fkey FOREIGN KEY (id_support)
	REFERENCES cc_support (id) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE CASCADE
);


CREATE TABLE cc_ticket (
	id					BIGSERIAL NOT NULL,
	id_component		INTEGER NOT NULL,
	title				CHARACTER VARYING(100) NOT NULL,
	description			TEXT,
	priority			SMALLINT NOT NULL DEFAULT 0,
	creationdate		TIMESTAMP without time zone NOT NULL DEFAULT now(),
	creator				BIGINT NOT NULL,
	status				INTEGER NOT NULL DEFAULT 0,
	creator_type		SMALLINT NOT NULL DEFAULT '0',
	viewed_cust			SMALLINT NOT NULL DEFAULT '1',
	viewed_agent		SMALLINT NOT NULL DEFAULT '1',
	viewed_admin		SMALLINT NOT NULL DEFAULT '1',
	CONSTRAINT			cc_ticket_pkey PRIMARY KEY (id)
);


CREATE TABLE cc_ticket_comment (
	id					BIGSERIAL NOT NULL,
	date				TIMESTAMP without time zone NOT NULL DEFAULT now(),
	id_ticket			BIGSERIAL NOT NULL,
	description			TEXT,
	creator				BIGINT NOT NULL,
	creator_type		SMALLINT NOT NULL DEFAULT '0',
	viewed_cust			SMALLINT NOT NULL DEFAULT '1',
	viewed_agent		SMALLINT NOT NULL DEFAULT '1',
	viewed_admin		SMALLINT NOT NULL DEFAULT '1',
	CONSTRAINT			cc_ticket_comment_pkey PRIMARY KEY (id),
	CONSTRAINT			cc_ticket_id_fkey FOREIGN KEY (id_ticket) REFERENCES cc_ticket (id) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE CASCADE
);


-- Section for Agent

CREATE TABLE cc_agent (
    id 								BIGSERIAL NOT NULL PRIMARY KEY,
    datecreation 					TIMESTAMP without time zone DEFAULT now(),
    active 							BOOLEAN NOT NULL DEFAULT false,
    login 							CHARACTER VARYING(20) UNIQUE NOT NULL,
    passwd 							CHARACTER VARYING(40),
    location 						TEXT,
    "language" 						CHARACTER VARYING(5) DEFAULT 'en'::text,
    id_tariffgroup 					INTEGER REFERENCES cc_tariffgroup(id),
    options 						INTEGER NOT NULL DEFAULT 0,
    credit 							DECIMAL(15,5) NOT NULL DEFAULT 0,
    currency 						CHARACTER VARYING(3) NOT NULL DEFAULT 'USD',
    locale 							CHARACTER VARYING(10) DEFAULT 'C',
    commission 						DECIMAL(10,4) NOT NULL DEFAULT 0,
    vat 							DECIMAL(10,4) NOT NULL DEFAULT 0,
    banner 							TEXT,
    perms 							INTEGER,
    lastname 						CHARACTER VARYING(50) ,
    firstname 						CHARACTER VARYING(50) ,
    address 						CHARACTER VARYING(100) ,
    city 							CHARACTER VARYING(40) ,
    state 							CHARACTER VARYING(40) ,
    country 						CHARACTER VARYING(40) ,
    zipcode 						CHARACTER VARYING(20),
    phone 							CHARACTER VARYING(20),
    email 							CHARACTER VARYING(70),
    fax 							CHARACTER VARYING(20),
    company							CHARACTER VARYING(50),
    secret							CHARACTER VARYING(20) NOT NULL
);



-- Add card id field in CDR to authorize filtering by agent

CREATE TABLE cc_agent_tariffgroup (
	id_agent			BIGINT NOT NULL ,
	id_tariffgroup		INTEGER NOT NULL,
	CONSTRAINT cc_agent_tariffgroup_pkey PRIMARY KEY (id_agent, id_tariffgroup)
);


-- create group for the card

CREATE TABLE cc_card_group (
	id 				SERIAL NOT NULL,
	name 			CHARACTER VARYING(30) NOT NULL,
	description		TEXT,
	users_perms		INT NOT NULL DEFAULT '0',
	id_agent		INT NULL,
	CONSTRAINT cc_card_group_pkey PRIMARY KEY (id)
);

-- insert default group

SELECT setval('cc_card_group_id_seq'::regclass, 1, false); -- we need it to have id 1
INSERT INTO cc_card_group (name, description, users_perms) VALUES ('DEFAULT', 'This group is the default group used when you create a customer. It''s forbidden to delete it because you need at least one group but you can edit it.', '129022');


-- new table for the free minutes/calls package

CREATE TABLE cc_package_group (
	id 				SERIAL NOT NULL  ,
	name 			CHARACTER VARYING(30)  NOT NULL ,
	description 	TEXT ,
	CONSTRAINT		cc_package_group_pkey PRIMARY KEY (id)
);


CREATE TABLE cc_packgroup_package (
	packagegroup_id 	INTEGER NOT NULL ,
	package_id			INTEGER NOT NULL ,
	CONSTRAINT cc_packgroup_package_pkey PRIMARY KEY  ( packagegroup_id , package_id )
);


CREATE TABLE cc_package_rate (
	package_id		INTEGER NOT NULL ,
	rate_id			INTEGER NOT NULL ,
	CONSTRAINT cc_package_rate_pkey PRIMARY KEY  ( package_id , rate_id )
);


CREATE TABLE cc_cardgroup_service (
	id_card_group		INT NOT NULL,
	id_service 			INT NOT NULL,
	CONSTRAINT cons_cc_cardgroup_unique	UNIQUE (id_card_group, id_service)
);


-- ------------------------------------------------------
-- for AutoDialer
-- ------------------------------------------------------

-- Create phonebook for
CREATE TABLE cc_phonebook (
	id 					SERIAL NOT NULL,
	name 				VARCHAR(30) NOT NULL,
	description			TEXT,
	id_card				BIGINT NOT NULL,
	PRIMARY KEY (id)
);


CREATE TABLE cc_phonenumber (
	id 					BIGSERIAL NOT NULL,
	id_phonebook 		INT NOT NULL,
	number 				VARCHAR(30) NOT NULL,
	name 				VARCHAR(40),
	creationdate 		TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT now(),
	status 				SMALLINT NOT NULL DEFAULT '1',
	info 				TEXT,
	amount				INT NOT NULL DEFAULT '0',
	PRIMARY KEY (id)
);





CREATE TABLE cc_campaign_phonebook (
	id_campaign 		INT NOT NULL,
	id_phonebook 		INT NOT NULL,
	PRIMARY KEY (id_campaign, id_phonebook)
);


CREATE TABLE cc_campaign_phonestatus (
	id_phonenumber 		BIGINT NOT NULL,
	id_campaign 		INT NOT NULL,
	id_callback 		VARCHAR(40) NOT NULL,
	status 				INT NOT NULL DEFAULT '0',
	lastuse 			TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT now(),
	PRIMARY KEY (id_phonenumber, id_campaign)
);


CREATE TABLE cc_campaign_config (
	id 					SERIAL NOT NULL,
	name 				VARCHAR(40) NOT NULL,
	flatrate			DECIMAL(15,5) DEFAULT 0 NOT NULL,
	context 			VARCHAR(40) NOT NULL,
	description 		TEXT,
	PRIMARY KEY (id)
);


CREATE TABLE cc_campaignconf_cardgroup (
	id_campaign_config 	INT NOT NULL,
	id_card_group 		INT NOT NULL,
	PRIMARY KEY (id_campaign_config, id_card_group)
);


-- ------------------------------------------------------
-- Add restricted rules on the call system for customers
-- ------------------------------------------------------

CREATE TABLE cc_restricted_phonenumber (
	id 					BIGSERIAL NOT NULL,
	number 				VARCHAR(50) NOT NULL,
	id_card 			BIGINT NOT NULL,
	PRIMARY KEY (id)
);


-- New Invoice Tables
CREATE TABLE cc_invoice (
	id 					BIGSERIAL NOT NULL,
	reference 			VARCHAR(30) UNIQUE,
	id_card 			BIGINT NOT NULL ,
	date 				TIMESTAMP WITHOUT TIME ZONE NOT NULL default now(),
	paid_status 		SMALLINT NOT NULL DEFAULT '0',
	status 				SMALLINT NOT NULL DEFAULT '0',
	title 				VARCHAR(50) NOT NULL,
	description 		TEXT  NOT NULL,
	PRIMARY KEY ( id )
);

CREATE TABLE cc_invoice_item (
	id 					BIGSERIAL NOT NULL,
	id_invoice 			BIGINT NOT NULL,
	date 				TIMESTAMP WITHOUT TIME ZONE NOT NULL default now(),
	price 				DECIMAL(15, 5) NOT NULL DEFAULT '0',
	VAT 				DECIMAL( 4, 2) NOT NULL DEFAULT '0',
	description 		TEXT NOT NULL,
	id_ext				BIGINT NULL,
	type_ext			VARCHAR(10) NULL,
	PRIMARY KEY ( id )
);


CREATE TABLE cc_invoice_conf (
	id 					SERIAL NOT NULL,
	key_val 			VARCHAR(50) UNIQUE NOT NULL,
	value 				VARCHAR(50) NOT NULL,
	PRIMARY KEY ( id )
);

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


CREATE TABLE cc_invoice_payment (
	id_invoice			BIGINT NOT NULL,
	id_payment			BIGINT UNIQUE NOT NULL,
	PRIMARY KEY (id_invoice, id_payment)
);


-- synched with MySQL up to r1405
CREATE TABLE cc_billing_customer (
	id					BIGSERIAL ,
	id_card				BIGINT NOT NULL ,
	date				TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP ,
	id_invoice			BIGINT NOT NULL ,
	PRIMARY KEY (id)
);


-- Commission Agent
CREATE TABLE cc_agent_commission (
	id					BIGSERIAL,
	id_payment			BIGINT NULL,
	id_card				BIGINT NOT NULL,
	date				TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
	amount				DECIMAL(15, 5) NOT NULL,
	paid_status			SMALLINT NOT NULL DEFAULT '0',
	description			TEXT NULL,
	id_agent			INT NOT NULL,
	PRIMARY KEY (id)
);


-- Card Serial Number
CREATE TABLE cc_card_seria (
	id					SERIAL,
	name				CHAR(30) NOT NULL,
	description			TEXT NULL,
	value				BIGINT NOT NULL DEFAULT 0,
	PRIMARY KEY (id)
);


CREATE OR REPLACE FUNCTION cc_card_serial_set() RETURNS TRIGGER AS $$
  BEGIN
    UPDATE cc_card_seria SET value=value+1 WHERE id=NEW.id_seria;
    SELECT value INTO NEW.serial FROM cc_card_seria WHERE id=NEW.id_seria;
    RETURN NEW;
  END
$$ LANGUAGE plpgsql;
CREATE TRIGGER cc_card_serial BEFORE INSERT ON cc_card
  FOR EACH ROW EXECUTE PROCEDURE cc_card_serial_set();

CREATE OR REPLACE FUNCTION cc_card_serial_update() RETURNS TRIGGER AS $$
  BEGIN
    IF NEW.id_seria IS NOT NULL AND NEW.id_seria = OLD.id_seria THEN
      RETURN NEW;
    END IF;
    UPDATE cc_card_seria SET value=value+1 WHERE id=NEW.id_seria;
    SELECT value INTO NEW.serial FROM cc_card_seria WHERE id=NEW.id_seria;
    RETURN NEW;
  END
$$ LANGUAGE plpgsql;
CREATE TRIGGER cc_card_serial_upd BEFORE UPDATE ON cc_card
  FOR EACH ROW EXECUTE PROCEDURE cc_card_serial_update();


-- add receipt objects
CREATE TABLE cc_receipt (
	id					BIGSERIAL,
	id_card				BIGINT NOT NULL,
	date				TIMESTAMP WITHOUT TIME ZONE NOT NULL default CURRENT_TIMESTAMP,
	title				VARCHAR(50) NOT NULL,
	description			TEXT NOT NULL,
	status				SMALLINT NOT NULL DEFAULT '0',
	PRIMARY KEY (id)
);


CREATE TABLE cc_receipt_item (
	id					BIGSERIAL,
	id_receipt			BIGINT NOT NULL,
	date				TIMESTAMP WITHOUT TIME ZONE NOT NULL default CURRENT_TIMESTAMP,
	price				DECIMAL(15, 5) NOT NULL DEFAULT '0',
	description			TEXT NOT NULL,
	id_ext				BIGINT NULL DEFAULT NULL,
	type_ext			VARCHAR(10) NULL DEFAULT NULL,
	PRIMARY KEY (id)
);


CREATE TABLE cc_epayment_log_agent (
	id					BIGSERIAL NOT NULL,
	agent_id			BIGINT NOT NULL default '0',
	amount				DECIMAL(15, 5) NOT NULL default '0',
	vat					FLOAT NOT NULL default '0',
	paymentmethod		char(50) NOT NULL,
	cc_owner			varchar(64) default NULL,
	cc_number			varchar(32) default NULL,
	cc_expires			varchar(7) default NULL,
	creationdate		timestamp without time zone NOT NULL default CURRENT_TIMESTAMP,
	status				int NOT NULL default '0',
	cvv					varchar(4) default NULL,
	credit_card_type	varchar(20) default NULL,
	currency			varchar(4) default NULL,
	transaction_detail	text,
	PRIMARY KEY (id)
);


-- Add notification system
CREATE TABLE cc_notification (
	id 					BIGSERIAL,
	key_value 			varchar(40),
	date 				timestamp NOT NULL default CURRENT_TIMESTAMP,
	priority 			SMALLINT NOT NULL DEFAULT '0',
	from_type 			SMALLINT NOT NULL,
	from_id 			BIGINT NULL DEFAULT '0',
	PRIMARY KEY (id)
);


CREATE TABLE cc_notification_admin (
	id_notification		BIGINT NOT NULL,
	id_admin			INT NOT NULL,
	viewed				SMALLINT NOT NULL DEFAULT '0',
	PRIMARY KEY (id_notification , id_admin)
);


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

INSERT INTO cc_config_group (group_title, group_description)
	VALUES ( 'webagentui', 'This configuration group handles Web Agent Interface.');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
	VALUES ( 'Personal Info', 'personalinfo', '1', 'Enable or disable the page which allow agent to modify its personal information.', '0', 'yes,no', 'webagentui');

-- Add index for SIP / IAX Friend
CREATE INDEX cc_iax_buddies_name ON cc_iax_buddies USING btree(name);
CREATE INDEX cc_iax_buddies_host ON cc_iax_buddies USING btree(host);
CREATE INDEX cc_iax_buddies_ipaddr ON cc_iax_buddies USING btree(ipaddr);
CREATE INDEX cc_iax_buddies_port ON cc_iax_buddies USING btree(port);

CREATE INDEX cc_sip_buddies_name ON cc_sip_buddies USING btree(name);
CREATE INDEX cc_sip_buddies_host ON cc_sip_buddies USING btree(host);
CREATE INDEX cc_sip_buddies_ipaddr ON cc_sip_buddies USING btree(ipaddr);
CREATE INDEX cc_sip_buddies_port ON cc_sip_buddies USING btree(port);

-- add parameters return_url_distant_login & return_url_distant_forgetpassword on webcustomerui
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES('Return URL distant Login', 'return_url_distant_login', '', 'URL for specific return if an error occur after login', 0, NULL, 'webcustomerui');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES('Return URL distant Forget Password', 'return_url_distant_forgetpassword', '', 'URL for specific return if an error occur after forgetpassword', 0, NULL, 'webcustomerui');

CREATE TABLE cc_agent_signup (
	id 				BIGSERIAL,
	id_agent 		INT NOT NULL,
	code 			VARCHAR(30) NOT NULL,
	id_tariffgroup 	INT NOT NULL,
	id_group 		INT NOT NULL,
	PRIMARY KEY (id),
	UNIQUE (code)
);

ALTER TABLE cc_agent DROP secret;

-- disable Authorize.net
-- UPDATE cc_payment_methods SET active = 'f';  -- WTF?  We dropped that column earlier.
UPDATE cc_configuration SET configuration_value = 'False' WHERE configuration_key = 'MODULE_PAYMENT_AUTHORIZENET_STATUS';
DELETE FROM cc_payment_methods WHERE payment_method = 'Authorize.Net';

ALTER TABLE cc_epayment_log ALTER amount TYPE VARCHAR(50);
ALTER TABLE cc_epayment_log_agent ALTER amount TYPE VARCHAR(50);

UPDATE cc_config SET config_value = 'id, username, useralias, lastname, credit, tariff, activated, language, inuse, currency, sip_buddy' WHERE config_key = 'card_export_field_list';
-- ALTER TABLE cc_tariffgroup ALTER id_cc_package_offer TYPE NUMERIC(20) NOT NULL DEFAULT '-1'; -- WTF?  BIGINT already does 19 decimal digits.

ALTER TABLE cc_epayment_log ADD item_type VARCHAR(30) NULL, ADD item_id BIGINT NULL;

UPDATE cc_config SET config_description = 'Comma-separated list of extra sell-rate charges corresponding to the DIDs in "extracharge_did" - ie : 0.08,0.18', config_value = '0,0' WHERE config_key = 'extracharge_fee';
UPDATE cc_config SET config_description = 'Comma-separated list of extra buy-rate charges corresponding to the DIDs in "extracharge_did" - ie : 0.04,0.13', config_value = '0,0' WHERE config_key = 'extracharge_buyfee';

UPDATE cc_config SET config_value = 'cc_card.id, username, useralias, lastname, credit, tariff, activated, language, inuse, currency, sip_buddy, iax_buddy, nbused, mac_addr' WHERE config_key = 'card_export_field_list';

-- Last registration
ALTER TABLE cc_sip_buddies ADD lastms varchar(11);

-- Add new SMTP Settings
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES ('SMTP Port', 'smtp_port', '25', 'Port to connect on the SMTP server', 0, NULL, 'global');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES ('SMTP Secure', 'smtp_secure', '', 'sets the prefix to the SMTP server : tls ; ssl', 0, NULL, 'global');

ALTER TABLE cc_support_component ADD type_user SMALLINT NOT NULL DEFAULT '2';

-- Some changes from r1093-1099 that were missed somehow
ALTER TABLE cc_campaign ADD callerid VARCHAR(60) NOT NULL;
ALTER TABLE cc_card_group ADD flatrate DECIMAL(15, 5) DEFAULT 0 NOT NULL;
ALTER TABLE cc_card_group ADD campaign_context VARCHAR(40);
ALTER TABLE cc_ratecard ADD COLUMN disconnectcharge_after INT NOT NULL DEFAULT 0;

-- synched with MySQL up to r2051

-- Commit the whole update;  psql will automatically rollback if we failed at any point
COMMIT;



VACUUM FULL ANALYZE;

-- Change below your dbname & uncomment
-- REINDEX DATABASE a2billing;
