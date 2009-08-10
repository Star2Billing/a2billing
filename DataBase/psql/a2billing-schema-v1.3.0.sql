
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,   
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 * 
 * @copyright   Copyright (C) 2004-2009 - Star2billing S.L. 
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
--     psql -f a2billing-pgsql-schema-v1.2.3.sql template1

--     NOTE: the errors you will see about missing tables are OK, it's the default behaviour of pgsql.
    
--     When prompted for the password, please enter the one you choose. In our case, it's 'a2billing'. 



\set ON_ERROR_STOP ON;

SET default_with_oids = true;


CREATE TABLE cc_campaign (
    id 						BIGSERIAL NOT NULL,
    campaign_name 			TEXT NOT NULL,
    creationdate 			TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    startingdate 			TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    expirationdate 			TIMESTAMP WITHOUT TIME ZONE,
    description 			TEXT ,
    id_trunk 				BIGINT NOT NULL,	
    secondusedreal 			INTEGER DEFAULT 0,
    nb_callmade 			INTEGER DEFAULT 0,
    enable 					INTEGER DEFAULT 0 NOT NULL
);

ALTER TABLE ONLY cc_campaign
    ADD CONSTRAINT cc_campaign_pkey PRIMARY KEY (id);
ALTER TABLE ONLY cc_campaign
    ADD CONSTRAINT cons_phonelistname UNIQUE (campaign_name);



CREATE TABLE cc_phonelist (
    id 							BIGSERIAL NOT NULL,
    id_cc_campaign 				BIGINT DEFAULT 0 NOT NULL,
    id_cc_card 					BIGINT DEFAULT 0 NOT NULL,
    numbertodial 				TEXT NOT NULL,
    name 						TEXT NOT NULL,
    inuse 						INTEGER DEFAULT 0,
    enable 						INTEGER DEFAULT 1 NOT NULL,
    num_trials_done 			INTEGER DEFAULT 0,
    creationdate 				TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    last_attempt 				TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),		
    secondusedreal 				INTEGER DEFAULT 0,
    additionalinfo 				TEXT NOT NULL	
);

ALTER TABLE ONLY cc_phonelist
    ADD CONSTRAINT cc_phonelist_pkey PRIMARY KEY (id);
	
CREATE INDEX ind_cc_phonelist_numbertodial ON cc_phonelist USING btree (numbertodial);




CREATE TABLE cc_didgroup (
    id 							BIGSERIAL NOT NULL,
    iduser 					INTEGER DEFAULT 0 NOT NULL,	
    creationdate 				TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    didgroupname 				TEXT NOT NULL
);

ALTER TABLE ONLY cc_didgroup
    ADD CONSTRAINT cc_didgroup_pkey PRIMARY KEY (id);



CREATE TABLE cc_did (
    id 							BIGSERIAL NOT NULL,
    id_cc_didgroup 				BIGINT NOT NULL,
    id_cc_country 				INTEGER NOT NULL,    
    activated 					INTEGER DEFAULT 1 NOT NULL,
    reserved 					INTEGER DEFAULT 0,
    iduser 					BIGINT DEFAULT 0 NOT NULL,
    did 					TEXT NOT NULL,
    creationdate 				TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),	
    startingdate 				TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    expirationdate 				TIMESTAMP WITHOUT TIME ZONE,
    description 				TEXT,
    secondusedreal 				INTEGER DEFAULT 0,
    billingtype 				INTEGER DEFAULT 0,
    fixrate 					NUMERIC(12,4) NOT NULL
);
-- billtype: 0 = fix per month + dialoutrate, 1= fix per month, 2 = dialoutrate, 3 = free


ALTER TABLE ONLY cc_did
    ADD CONSTRAINT cc_did_pkey PRIMARY KEY (id);

ALTER TABLE ONLY cc_did
    ADD CONSTRAINT cons_did_cc_did UNIQUE (did);
	


CREATE TABLE cc_did_destination (
    id 								BIGSERIAL NOT NULL,
    destination 					TEXT NOT NULL,
    priority 						INTEGER DEFAULT 0 NOT NULL,
    id_cc_card 						BIGINT NOT NULL,
    id_cc_did 						BIGINT NOT NULL,	
    creationdate 					TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    activated 						INTEGER DEFAULT 1 NOT NULL,
    secondusedreal 					INTEGER DEFAULT 0,
    voip_call 						INTEGER DEFAULT 0
);


ALTER TABLE ONLY cc_did_destination
    ADD CONSTRAINT cc_did_destination_pkey PRIMARY KEY (id);



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
	id_cc_subscription_fee 	BIGINT DEFAULT 0
);

ALTER TABLE ONLY cc_charge
    ADD CONSTRAINT cc_charge_pkey PRIMARY KEY (id);


CREATE INDEX ind_cc_charge_id_cc_card				ON cc_charge USING btree (id_cc_card);
CREATE INDEX ind_cc_charge_id_cc_subscription_fee 	ON cc_charge USING btree (id_cc_subscription_fee);
CREATE INDEX ind_cc_charge_creationdate 			ON cc_charge USING btree (creationdate);



CREATE TABLE cc_paypal (
  id 								BIGSERIAL NOT NULL,
  payer_id 							CHARACTER VARYING(60) default NULL,
  payment_date 						CHARACTER VARYING(50) default NULL,
  txn_id 							CHARACTER VARYING(50) default NULL,
  first_name 						CHARACTER VARYING(50) default NULL,
  last_name 						CHARACTER VARYING(50) default NULL,
  payer_email 						CHARACTER VARYING(75) default NULL,
  payer_status 						CHARACTER VARYING(50) default NULL,
  payment_type 						CHARACTER VARYING(50) default NULL,
  memo 								TEXT ,
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
  txn_type 							CHARACTER VARYING(255) NOT NULL default ''
);

ALTER TABLE ONLY cc_paypal
ADD CONSTRAINT cc_paypal_pkey PRIMARY KEY (id);

ALTER TABLE ONLY cc_paypal
    ADD CONSTRAINT cons_txn_id_cc_paypal UNIQUE (txn_id);
	
	
	

CREATE TABLE cc_voucher (
    id 									BIGSERIAL NOT NULL,
    creationdate 						TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    usedate 							TIMESTAMP WITHOUT TIME ZONE,
    expirationdate 						TIMESTAMP WITHOUT TIME ZONE,	
    voucher 							TEXT NOT NULL,
    usedcardnumber 						TEXT ,
    tag 								TEXT ,	
    credit 								NUMERIC(12,4) NOT NULL,    
    activated 							BOOLEAN DEFAULT true NOT NULL,
    used 								INTEGER DEFAULT 0,
    currency 							CHARACTER VARYING(3) DEFAULT 'USD'::CHARACTER varying
);

ALTER TABLE ONLY cc_voucher
    ADD CONSTRAINT cc_voucher_pkey PRIMARY KEY (id);
ALTER TABLE ONLY cc_voucher
    ADD CONSTRAINT cons_voucher_cc_voucher UNIQUE (voucher);




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
    emailreport 						TEXT ,
    totalcredit 						double precision NOT NULL DEFAULT 0,
    totalcardperform 					INTEGER NOT NULL DEFAULT 0
);
ALTER TABLE ONLY cc_service
ADD CONSTRAINT cc_service_pkey PRIMARY KEY (id);

	
CREATE TABLE cc_service_report (
    id 									BIGSERIAL NOT NULL,
    cc_service_id 						BIGINT NOT NULL,
    daterun 							TIMESTAMP(0) without time zone DEFAULT NOW(),
    totalcardperform 					INTEGER,
    totalcredit 						double precision
);
ALTER TABLE ONLY cc_service_report
ADD CONSTRAINT cc_service_report_pkey PRIMARY KEY (id);





CREATE TABLE cc_callerid (
    id 									BIGSERIAL NOT NULL,
    cid 								TEXT NOT NULL,
    id_cc_card 							BIGINT NOT NULL,
    activated 							BOOLEAN DEFAULT true NOT NULL
);

ALTER TABLE ONLY cc_callerid
    ADD CONSTRAINT cc_calleridd_pkey PRIMARY KEY (id);

ALTER TABLE ONLY cc_callerid
    ADD CONSTRAINT cc_callerid_cid_key UNIQUE (cid);


CREATE TABLE cc_ui_authen (
    userid 								BIGSERIAL NOT NULL,
    login 								TEXT NOT NULL,
    "password" 							TEXT NOT NULL,
    groupid 							INTEGER,
    perms 								INTEGER,
    confaddcust 						INTEGER,
    name 								TEXT ,
    direction 							TEXT ,
    zipcode 							TEXT ,
    state 								TEXT ,
    phone	 							TEXT ,
    fax 								TEXT ,
    datecreation 						TIMESTAMP without time zone DEFAULT NOW()
);

ALTER TABLE ONLY cc_ui_authen
    ADD CONSTRAINT cc_ui_authen_pkey PRIMARY KEY (userid);

ALTER TABLE ONLY cc_ui_authen
    ADD CONSTRAINT cons_cc_ui_authen_login_key UNIQUE(login);

CREATE TABLE cc_call (
    id 									BIGSERIAL NOT NULL,
    sessionid 							TEXT NOT NULL,
    uniqueid 							TEXT NOT NULL,
    username 							TEXT NOT NULL,
    nasipaddress 						TEXT ,
    starttime 							TIMESTAMP WITHOUT TIME ZONE,
    stoptime 							TIMESTAMP WITHOUT TIME ZONE,
    sessiontime 						INTEGER,
    calledstation 						TEXT ,
    startdelay 							INTEGER,
    stopdelay 							INTEGER,
    terminatecause 						TEXT ,
    usertariff 							TEXT ,
    calledprovider 						TEXT ,
    calledcountry 						TEXT ,
    calledsub 							TEXT ,
    calledrate 							double precision,
    sessionbill 						double precision,
    destination 						TEXT ,
    id_tariffgroup 						INTEGER,
    id_tariffplan 						INTEGER,
    id_ratecard 						INTEGER,
    id_trunk 							INTEGER,
    sipiax 								INTEGER DEFAULT 0,
    src 								TEXT ,
    id_did 								INTEGER,
    buyrate 							NUMERIC(15,5) DEFAULT 0,
    buycost 							NUMERIC(15,5) DEFAULT 0,
	id_card_package_offer 				INTEGER DEFAULT 0
);


CREATE TABLE cc_templatemail (
    mailtype 							TEXT ,
    fromemail 							TEXT ,
    fromname 							TEXT ,
    subject 							TEXT ,
    messagetext 						TEXT ,
    messagehtml 						TEXT 
);
ALTER TABLE ONLY cc_templatemail
    ADD CONSTRAINT cons_cc_templatemail_mailtype UNIQUE (mailtype);



CREATE TABLE cc_tariffgroup (
    id 									serial NOT NULL,
    iduser 								INTEGER DEFAULT 0 NOT NULL,
    idtariffplan 						INTEGER DEFAULT 0 NOT NULL,
    tariffgroupname 					TEXT NOT NULL,
    lcrtype 							INTEGER DEFAULT 0 NOT NULL,
    creationdate 						TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    removeinterprefix 					INTEGER DEFAULT 0 NOT NULL,
	id_cc_package_offer 				BIGINT not null default 0
);



CREATE TABLE cc_tariffgroup_plan (
    idtariffgroup 						INTEGER NOT NULL,
    idtariffplan 						INTEGER NOT NULL
);



CREATE TABLE cc_tariffplan (
    id 									serial NOT NULL,
    iduser 								INTEGER DEFAULT 0 NOT NULL,
    tariffname 							TEXT NOT NULL,
    creationdate 						TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    startingdate 						TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    expirationdate 						TIMESTAMP WITHOUT TIME ZONE,
    description 						TEXT ,
    id_trunk 							INTEGER DEFAULT 0,
    secondusedreal 						INTEGER DEFAULT 0,
    secondusedcarrier 					INTEGER DEFAULT 0,
    secondusedratecard 					INTEGER DEFAULT 0,
    reftariffplan 						INTEGER DEFAULT 0,
    idowner 							INTEGER DEFAULT 0,
    dnidprefix 							TEXT NOT NULL DEFAULT 'all'::text,
    calleridprefix 						TEXT NOT NULL DEFAULT 'all'::text
);



CREATE TABLE cc_card (
    id 									BIGSERIAL NOT NULL,
    creationdate 						TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    firstusedate 						TIMESTAMP WITHOUT TIME ZONE,
    expirationdate 						TIMESTAMP WITHOUT TIME ZONE,
    enableexpire 						INTEGER DEFAULT 0,
    expiredays 							INTEGER DEFAULT 0,
    username 							TEXT NOT NULL,
    useralias 							TEXT NOT NULL,
    userpass 							TEXT NOT NULL,
    uipass 								TEXT ,
    credit 								NUMERIC(12,4) NOT NULL,
    tariff 								INTEGER DEFAULT 0,
    id_didgroup 						INTEGER DEFAULT 0,
    activated 							BOOLEAN DEFAULT false NOT NULL,
    lastname 							TEXT ,
    firstname 							TEXT ,
    address 							TEXT ,
    city 								TEXT ,
    state 								TEXT ,
    country 							TEXT ,
    zipcode 							TEXT ,
    phone 								TEXT ,
    email 								TEXT ,
    fax 								TEXT ,
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
    redial 								TEXT ,
    runservice 							INTEGER DEFAULT 0,
    nbservice 							INTEGER DEFAULT 0,
    id_campaign 						INTEGER DEFAULT 0,
    num_trials_done 					INTEGER DEFAULT 0,
    callback 							TEXT ,
    vat 								NUMERIC(6,3) DEFAULT 0,
    servicelastrun 						TIMESTAMP WITHOUT TIME ZONE,
    initialbalance 						NUMERIC(12,4) NOT NULL DEFAULT 0,
    invoiceday 							INTEGER DEFAULT 1,
    autorefill 							INTEGER DEFAULT 0,
    loginkey 							TEXT ,
    activatedbyuser 					BOOLEAN DEFAULT false NOT NULL,
	id_subscription_fee 				INTEGER DEFAULT 0,
	mac_addr							VARCHAR(17) DEFAULT '00-00-00-00-00-00' NOT NULL
);
ALTER TABLE ONLY cc_card
    ADD CONSTRAINT cons_cc_card_username UNIQUE (username);
ALTER TABLE ONLY cc_card
    ADD CONSTRAINT cons_cc_card_useralias UNIQUE (useralias);
ALTER TABLE ONLY cc_card
    ADD CONSTRAINT cons_cc_card_pkey PRIMARY KEY (id);


CREATE TABLE cc_ratecard (
    id 									serial NOT NULL,
    idtariffplan 						INTEGER DEFAULT 0 NOT NULL,
    dialprefix 							TEXT NOT NULL,
    destination 						TEXT NOT NULL,
    buyrate 							real DEFAULT 0 NOT NULL,
    buyrateinitblock 					INTEGER DEFAULT 0 NOT NULL,
    buyrateincrement 					INTEGER DEFAULT 0 NOT NULL,
    rateinitial 						real DEFAULT 0 NOT NULL,
    initblock 							INTEGER DEFAULT 0 NOT NULL,
    billingblock 						INTEGER DEFAULT 0 NOT NULL,
    connectcharge 						real DEFAULT 0 NOT NULL,
    disconnectcharge 					real DEFAULT 0 NOT NULL,
    stepchargea 						real DEFAULT 0 NOT NULL,
    chargea 							real DEFAULT 0 NOT NULL,
    timechargea 						INTEGER DEFAULT 0 NOT NULL,
    billingblocka 						INTEGER DEFAULT 0 NOT NULL,
    stepchargeb 						real DEFAULT 0 NOT NULL,
    chargeb 							real DEFAULT 0 NOT NULL,
    timechargeb 						INTEGER DEFAULT 0 NOT NULL,
    billingblockb 						INTEGER DEFAULT 0 NOT NULL,
    stepchargec 						real DEFAULT 0 NOT NULL,
    chargec 							real DEFAULT 0 NOT NULL,
    timechargec 						INTEGER DEFAULT 0 NOT NULL,
    billingblockc 						INTEGER DEFAULT 0 NOT NULL,
    startdate 							TIMESTAMP(0) without time zone DEFAULT NOW(),
    stopdate 							TIMESTAMP(0) without time zone,
    starttime 							INTEGER NOT NULL DEFAULT 0,
    endtime 							INTEGER NOT NULL DEFAULT 10079,
    id_trunk 							INTEGER DEFAULT -1,	
    musiconhold 						CHARACTER VARYING(100),
    freetimetocall_package_offer 		INTEGER NOT NULL DEFAULT 0,
    id_outbound_cidgroup 				INTEGER NOT NULL DEFAULT -1
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
    id_provider							INTEGER
);


CREATE TABLE cc_sip_buddies (
    id 									serial NOT NULL,
    id_cc_card 							INTEGER DEFAULT 0 NOT NULL,
    name 								CHARACTER VARYING(80) DEFAULT ''::CHARACTER varying NOT NULL,
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
    canreinvite 						CHARACTER VARYING(3) DEFAULT 'yes'::CHARACTER varying,
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
    setvar 								CHARACTER VARYING(100) DEFAULT ''::CHARACTER varying NOT NULL
);



CREATE TABLE cc_iax_buddies (
    id 									serial NOT NULL,
    id_cc_card 							INTEGER DEFAULT 0 NOT NULL,
    name 								CHARACTER VARYING(80) DEFAULT ''::CHARACTER varying NOT NULL,
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
    canreinvite 						CHARACTER VARYING(3) DEFAULT 'yes'::CHARACTER varying,
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
    cancallforward 						CHARACTER VARYING(3) DEFAULT 'yes'::CHARACTER varying
);




CREATE TABLE cc_logrefill (
    id 									serial NOT NULL,
    date 								TIMESTAMP(0) without time zone DEFAULT NOW() NOT NULL,
    credit 								NUMERIC(12,4) NOT NULL,
    card_id 							BIGINT NOT NULL,
    reseller_id 						BIGINT
);


CREATE TABLE cc_logpayment (
    id 									serial NOT NULL,
    date 								TIMESTAMP(0) without time zone DEFAULT NOW() NOT NULL,
    payment 							real NOT NULL,
    card_id 							BIGINT NOT NULL,
    reseller_id 						BIGINT
);

create table cc_did_use (
    id 									bigserial not null ,
    id_cc_card 							BIGINT,
    id_did 								BIGINT not null,
    reservationdate						TIMESTAMP WITHOUT TIME ZONE not null default NOW(),
    releasedate 						TIMESTAMP WITHOUT TIME ZONE,
    activated 							INTEGER default 0,
    month_payed 						INTEGER default 0
);
ALTER TABLE cc_did_use
ADD CONSTRAINT cc_did_use_pkey PRIMARY KEY (id);

INSERT INTO cc_ui_authen VALUES (2, 'admin', 'mypassword', 0, 32767, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-02-26 21:14:05.391501-05');
INSERT INTO cc_ui_authen VALUES (1, 'root', 'myroot', 0, 32767, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-02-26 20:33:27.691314-05');


INSERT INTO cc_templatemail VALUES ('signup', 'info@call-labs.com', 'Call-Labs', 'SIGNUP CONFIRMATION', '
Thank you for registering with us
Please click on below link to activate your account.

http://www.call-labs.com/signup/activate.php?key=$loginkey

Please make sure you active your account by making payment to us either by
credit card, wire transfer, money order, cheque, and western union money
transfer, money Gram, and Pay pal.


Kind regards,
Call Labs
', '');

INSERT INTO cc_templatemail VALUES ('epaymentverify', 'info@call-labs.com', 'Call-Labs', 'Epayment Gateway Security Verification Failed', 'Dear Administrator

Please check the Epayment Log, System has logged a Epayment Security failure. that may be a possible attack on epayment processing.

Time of Transaction: $time
Payment Gateway: $paymentgateway
Amount: $amount



Kind regards,
Call Labs
', '');

INSERT INTO cc_templatemail VALUES ('reminder', 'info@call-labs.com', 'Call-Labs', 'REMINDER', '
Our record indicates that you have less than $min_credit usd in your "$card_gen" account.
We hope this message provides you with enough notice to refill your account.
We value your business, but our system can disconnect you automatically
when you reach your pre-paid balance.

Please login to your account through our website to check your account
details. Plus,
you can pay by credit card, on demand.

http://call-labs.com/A2BCustomer_UI/

If you believe this information to be incorrect please contact
info@call-labs.com
immediately.


Kind regards,
Call Labs
', '');

INSERT INTO cc_templatemail VALUES ('forgetpassword', 'info@call-labs.com', 'Call-Labs', 'Login Information', 'Your login information is as below:

Your account is $card_gen

Your password is $password

Your cardalias is $cardalias

http://call-labs.com/A2BCustomer_UI/


Kind regards,
Call Labs
', '');

INSERT INTO cc_templatemail VALUES ('signupconfirmed', 'info@call-labs.com', 'Call-Labs', 'SIGNUP CONFIRMATION', 'Thank you for registering with us

Please make sure you active your account by making payment to us either by
credit card, wire transfer, money order, cheque, and western union money
transfer, money Gram, and Pay pal.

Your account is $card_gen

Your password is $password

To go to your account :
http://call-labs.com/A2BCustomer_UI/

Kind regards,
Call Labs
', '');


INSERT INTO cc_templatemail VALUES ('payment', 'info@call-labs.com', 'Call-Labs', 'PAYMENT CONFIRMATION', 'Thank you for shopping at Call-Labs.

Shopping details is as below.

Item Name = <b>$itemName</b>
Item ID = <b>$itemID</b>
Amount = <b>$itemAmount</b>
Payment Method = <b>$paymentMethod</b>
Status = <b>$paymentStatus</b>


Kind regards,
Call Labs
', '');

INSERT INTO cc_templatemail VALUES ('invoice', 'info@call-labs.com', 'Call-Labs', 'A2BILLING INVOICE', 'Dear Customer.

Attached is the invoice.

Kind regards,
Call Labs
', '');

INSERT INTO cc_trunk VALUES (1, 'DEFAULT', '011', 'IAX2', 'kiki@switch-2.kiki.net', '', 0, 0, 0, '2005-03-14 01:01:36', 0, '', NULL);






ALTER TABLE ONLY cc_card
    ADD CONSTRAINT cons_username_cc_card UNIQUE (username);

ALTER TABLE ONLY cc_card
    ADD CONSTRAINT cons_useralias_cc_card UNIQUE (useralias);



ALTER TABLE ONLY cc_call
    ADD CONSTRAINT cc_call_pkey PRIMARY KEY (id);



ALTER TABLE ONLY cc_tariffgroup
    ADD CONSTRAINT cc_tariffgroup_pkey PRIMARY KEY (id);


ALTER TABLE ONLY cc_tariffplan
    ADD CONSTRAINT cc_tariffplan_pkey PRIMARY KEY (id);



ALTER TABLE ONLY cc_tariffplan
    ADD CONSTRAINT cons_iduser_tariffname UNIQUE (iduser, tariffname);


ALTER TABLE ONLY cc_tariffgroup_plan
    ADD CONSTRAINT pk_groupplan PRIMARY KEY (idtariffgroup, idtariffplan);



ALTER TABLE ONLY cc_ratecard
    ADD CONSTRAINT cc_ratecard_pkey PRIMARY KEY (id);


ALTER TABLE ONLY cc_trunk
    ADD CONSTRAINT cc_trunk_pkey PRIMARY KEY (id_trunk);

ALTER TABLE ONLY cc_sip_buddies
    ADD CONSTRAINT cc_sip_buddies_pkey PRIMARY KEY (id);


ALTER TABLE ONLY cc_sip_buddies
    ADD CONSTRAINT unique_name UNIQUE (name);



ALTER TABLE ONLY cc_iax_buddies
    ADD CONSTRAINT cc_iax_buddies_pkey PRIMARY KEY (id);


ALTER TABLE ONLY cc_iax_buddies
    ADD CONSTRAINT iax_unique_name UNIQUE (name);


SELECT pg_catalog.setval('cc_ui_authen_userid_seq', 3, true);

SELECT pg_catalog.setval('cc_trunk_id_trunk_seq', 2, true);






--
-- Country table : Store the iso country list
--

CREATE TABLE cc_country (
    id 									serial NOT NULL,
    countrycode 						TEXT NOT NULL,
    countryprefix 						TEXT NOT NULL DEFAULT '0',
    countryname 						TEXT NOT NULL
);

ALTER TABLE ONLY cc_country
    ADD CONSTRAINT cc_country_pkey PRIMARY KEY (id);


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
    provider_name 							TEXT NOT NULL,
    creationdate 							TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    description 							TEXT
);

ALTER TABLE ONLY cc_provider
    ADD CONSTRAINT cc_provider_pkey PRIMARY KEY (id);
ALTER TABLE ONLY cc_provider
    ADD CONSTRAINT cons_cc_provider_name_key UNIQUE (provider_name);



--
--  cc_currencies table
--

CREATE TABLE cc_currencies (
    id 									serial NOT NULL,
    currency 							char(3) default '' NOT NULL,
    name 								CHARACTER VARYING(30) default '' NOT NULL,
    value 								NUMERIC(12,5) default '0.00000' NOT NULL,
    lastupdate 							TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),	
    basecurrency 						char(3) default 'USD' NOT NULL
);


ALTER TABLE ONLY cc_currencies
    ADD CONSTRAINT cc_currencies_pkey PRIMARY KEY (id);
ALTER TABLE ONLY cc_currencies
    ADD CONSTRAINT cons_cc_currencies_currency_key UNIQUE(currency);


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
    name 									CHARACTER VARYING(255) DEFAULT ''::CHARACTER varying NOT NULL,
    path 									CHARACTER VARYING(255) DEFAULT ''::CHARACTER varying NOT NULL,
    creationdate 							TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW()
);

ALTER TABLE ONLY cc_backup
    ADD CONSTRAINT cc_backup_pkey PRIMARY KEY (id);
ALTER TABLE ONLY cc_backup
    ADD CONSTRAINT cons_cc_backup_name_key UNIQUE (name);
    

CREATE TABLE cc_ecommerce_product (
    id 										BIGSERIAL NOT NULL,
    product_name 							TEXT NOT NULL,
    creationdate 							TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),		
    description 							TEXT ,	
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
    iax_friend 								INTEGER DEFAULT 0
);

ALTER TABLE ONLY cc_ecommerce_product
    ADD CONSTRAINT cc_ecommerce_product_pkey PRIMARY KEY (id);





-- 
-- Speed Dial Table
--


CREATE TABLE cc_speeddial (
    id 									BIGSERIAL NOT NULL,
    id_cc_card 							BIGINT DEFAULT 0 NOT NULL,	
    phone 								TEXT NOT NULL,
    name 								TEXT NOT NULL,
    speeddial 							INTEGER DEFAULT 0,
    creationdate 						TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW()
);


ALTER TABLE ONLY cc_speeddial
    ADD CONSTRAINT cc_speeddial_pkey PRIMARY KEY (id);


ALTER TABLE ONLY cc_speeddial
    ADD CONSTRAINT cons_cc_speeddial_pkey UNIQUE (id_cc_card, speeddial);




-- Auto Refill Report Table	

CREATE TABLE cc_autorefill_report (
	id 									BIGSERIAL NOT NULL,
	daterun 							TIMESTAMP(0) without time zone DEFAULT NOW(),
	totalcardperform 					INTEGER,
	totalcredit 						double precision
);
ALTER TABLE ONLY cc_autorefill_report
ADD CONSTRAINT cc_autorefill_report_pkey PRIMARY KEY (id);


-- cc_prefix Table	

CREATE TABLE cc_prefix (
	id 									serial NOT NULL,
	prefixe 							TEXT NOT NULL,
	destination 						TEXT NOT NULL,
	id_cc_country 						BIGINT
);

ALTER TABLE ONLY cc_prefix ADD CONSTRAINT cc_prefix_pkey PRIMARY KEY (id);


INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Afghanistan','93','1');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Albania','355','2');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Algeria','213','3');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('American Samoa','684','4');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Andorra','376','5');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Angola','244','6');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Anguilla','1264','7');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Antarctica','672','8');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Antigua','1268',9);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Argentina','54','10');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Armenia','374','11');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Aruba','297','12');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Ascension','247',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Australia','61','13');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Australian External Territories','672','13');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Austria','43','14');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Azerbaijan','994','15');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Bahamas','1242','16');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Bahrain','973','17');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Bangladesh','880','18');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Barbados','1246','19');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Barbuda','1268',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Belarus','375','20');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Belgium','32','21');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Belize','501','22');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Benin','229','23');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Bermuda','1441','24');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Bhutan','975','25');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Bolivia','591','26');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Bosnia & Herzegovina','387','27');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Botswana','267','28');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Brazil','55','30');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Brasil Telecom','5514','30');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Brazil Telefonica','5515','30');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Brazil Embratel','5521','30');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Brazil Intelig','5523','30');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Brazil Telemar','5531','30');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Brazil mobile phones','550','30');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('British Virgin Islands','1284','31');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Brunei Darussalam','673','32');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Bulgaria','359','33');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Burkina Faso','226','34');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Burundi','257','35');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Cambodia','855','36');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Cameroon','237','37');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Canada','1','38');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Cape Verde Islands','238','39');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Cayman Islands','1345','40');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Central African Republic','236','41');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Chad','235','42');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Chatham Island (New Zealand)','64',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Chile','56','43');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('China (PRC)','86','44');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Christmas Island','618','45');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Cocos-Keeling Islands','61','46');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Colombia','57','47');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Colombia Mobile Phones','573','47');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Colombia Orbitel','575','47');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Colombia ETB','577','47');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Colombia Telecom','579','47');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Comoros','269','48');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Congo','242','49');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Congo, Dem. Rep. of  (former Zaire)','243',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Cook Islands','682','51');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Costa Rica','506','52');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Côte d''Ivoire (Ivory Coast)','225','53');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Croatia','385','54');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Cuba','53','55');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Cuba (Guantanamo Bay)','5399','55');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Curaâo','599',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Cyprus','357','56');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Czech Republic','420','57');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Denmark','45','58');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Diego Garcia','246','241');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Djibouti','253','59');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Dominica','1767','60');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Dominican Republic','1809','61');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('East Timor','670','211');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Easter Island','56',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Ecuador','593','62');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Egypt','20','63');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('El Salvador','503','64');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Ellipso (Mobile Satellite service)','8812',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('EMSAT (Mobile Satellite service)','88213',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Equatorial Guinea','240','65');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Eritrea','291','66');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Estonia','372','67');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Ethiopia','251','68');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Falkland Islands (Malvinas)','500','69');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Faroe Islands','298','70');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Fiji Islands','679','71');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Finland','358','72');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('France','33','73');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('French Antilles','596','74');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('French Guiana','594','75');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('French Polynesia','689','76');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Gabonese Republic','241','77');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Gambia','220','78');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Georgia','995','79');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Germany','49','80');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Ghana','233','81');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Gibraltar','350','82');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Global Mobile Satellite System (GMSS)','881',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('ICO Global','8810-8811',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Ellipso','8812-8813',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Iridium','8816-8817',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Globalstar','8818-8819',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Globalstar (Mobile Satellite Service)','8818-8819',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Greece','30','83');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Greenland','299','84');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Grenada','1473','85');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Guadeloupe','590','86');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Guam','1671','87');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Guantanamo Bay','5399',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Guatemala','502','88');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Guinea-Bissau','245','90');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Guinea','224','89');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Guyana','592','91');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Haiti','509','92');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Honduras','504','95');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Hong Kong','852','96');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Hungary','36','97');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('ICO Global (Mobile Satellite Service)','8810-8811',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Iceland','354','98');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('India','91','99');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Indonesia','62','100');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Inmarsat (Atlantic Ocean - East)','871','242');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Inmarsat (Atlantic Ocean - West)','874','242');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Inmarsat (Indian Ocean)','873','242');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Inmarsat (Pacific Ocean)','872','242');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Inmarsat SNAC','870','242');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('International Freephone Service','800',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('International Shared Cost Service (ISCS)','808',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Iran','98','101');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Iraq','964','102');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Ireland','353','103');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Iridium (Mobile Satellite service)','8816-8817',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Israel','972','104');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Italy','39','105');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Jamaica','1876','106');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Japan','81','107');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Jordan','962','108');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Kazakhstan','7','109');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Kenya','254','110');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Kiribati','686','111');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Korea (North)','850','112');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Korea (South)','82','113');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Kuwait','965','114');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Kyrgyz Republic','996','115');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Laos','856','116');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Latvia','371','117');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Lebanon','961','118');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Lesotho','266','119');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Liberia','231','120');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Libya','218','121');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Liechtenstein','423','122');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Lithuania','370','123');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Luxembourg','352','124');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Macao','853','125');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Macedonia (Former Yugoslav Rep of.)','389','126');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Madagascar','261','127');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Malawi','265','128');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Malaysia','60','129');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Maldives','960','130');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Mali Republic','223','131');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Malta','356','132');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Marshall Islands','692','133');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Martinique','596','134');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Mauritania','222','135');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Mauritius','230','136');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Mayotte Island','269','137');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Mexico','52','138');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Micronesia, (Federal States of)','691','139');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Midway Island','1808',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Moldova','373','140');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Monaco','377','141');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Mongolia','976','142');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Montserrat','1664','143');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Morocco','212','144');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Mozambique','258','145');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Myanmar','95','146');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Namibia','264','147');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Nauru','674','148');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Nepal','977','149');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Netherlands','31','150');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Netherlands Antilles','599','151');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Nevis','1869',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('New Caledonia','687','152');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('New Zealand','64','153');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Nicaragua','505','154');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Niger','227','155');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Nigeria','234','156');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Niue','683','157');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Norfolk Island','672','158');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Northern Marianas Islands(Saipan, Rota, & Tinian)','1670','159');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Norway','47','160');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Oman','968','161');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Pakistan','92','162');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Palau','680','163');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Palestinian Settlements','970','164');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Panama','507','165');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Papua New Guinea','675','166');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Paraguay','595','167');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Peru','51','168');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Philippines','63','169');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Poland','48','171');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Portugal','351','172');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Puerto Rico','1787','173');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Qatar','974','174');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Réunion Island','262','175');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Romania','40','176');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Russia','7','177');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Rwandese Republic','250','178');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('St. Helena','290','179');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('St. Kitts/Nevis','1869','180');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('St. Lucia','1758','181');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('St. Pierre & Miquelon','508','182');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('St. Vincent & Grenadines','1784','183');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('San Marino','378','185');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('São Tomé and Principe','239','186');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Saudi Arabia','966','187');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Senegal','221','188');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Serbia and Montenegro','381',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Seychelles Republic','248','189');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Sierra Leone','232','190');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Singapore','65','191');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Slovak Republic','421','192');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Slovenia','386','193');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Solomon Islands','677','194');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Somali Democratic Republic','252','195');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('South Africa','27','196');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Spain','34','198');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Sri Lanka','94','199');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Sudan','249','200');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Suriname','597','201');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Swaziland','268','203');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Sweden','46','204');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Switzerland','41','205');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Syria','963','206');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Taiwan','886','207');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Tajikistan','992','208');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Tanzania','255','209');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Thailand','66','210');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Thuraya (Mobile Satellite service)','88216',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Togolese Republic','228','212');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Tokelau','690','213');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Tonga Islands','676','214');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Trinidad & Tobago','1868','215');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Tunisia','216','216');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Turkey','90','217');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Turkmenistan','993','218');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Turks and Caicos Islands','1649','219');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Tuvalu','688','220');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Uganda','256','221');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Ukraine','380','222');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('United Arab Emirates','971','223');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('United Kingdom','44','224');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('United States of America','1','225');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('US Virgin Islands','1340','225');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Universal Personal Telecommunications (UPT)','878',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Uruguay','598','227');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Uzbekistan','998','228');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Vanuatu','678','229');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Vatican City','39',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Venezuela','58','230');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Venezuela Etelix','58102','230');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Venezuela http://www.multiphone.net.ve','58107','230');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Venezuela CANTV','58110','230');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Venezuela Convergence Comunications','58111','230');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Venezuela Telcel, C.A.','58114','230');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Venezuela Totalcom Venezuela','58119','230');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Venezuela Orbitel de Venezuela, C.A. ENTEL Venezuela','58123','230');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Venezuela LD Telecomunicaciones, C.A.','58150','230');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Venezuela Telecomunicaciones NGTV','58133','230');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Venezuela Veninfotel Comunicaciones','58199','230');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Vietnam','84','231');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Wake Island','808',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Wallis and Futuna Islands','681',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Western Samoa','685','184');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Yemen','967','236');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Zambia','260','238');
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Zanzibar','255',NULL);
INSERT INTO cc_prefix (destination,prefixe,id_cc_country) VALUES ('Zimbabwe','263','239');




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
    emailreport 					TEXT
);
ALTER TABLE ONLY cc_alarm
    ADD CONSTRAINT cc_alarm_pkey PRIMARY KEY (id);

CREATE TABLE cc_alarm_report (
    id 								BIGSERIAL NOT NULL,
    cc_alarm_id 					BIGINT NOT NULL,
    calculatedvalue 				numeric NOT NULL,
    daterun 						TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW()
);
ALTER TABLE ONLY cc_alarm_report
    ADD CONSTRAINT cc_alarm_report_pkey PRIMARY KEY (id);


CREATE TABLE cc_callback_spool (
    id 								BIGSERIAL NOT NULL,
    uniqueid 						TEXT ,
    entry_time 						TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),	
    status 							TEXT ,
    server_ip 						TEXT ,	
    num_attempt 					int NOT NULL DEFAULT 0,
    last_attempt_time 				TIMESTAMP WITHOUT TIME ZONE,
    manager_result 					TEXT ,
    agi_result 						TEXT ,
    callback_time 					TIMESTAMP WITHOUT TIME ZONE,	
    channel 						TEXT ,
    exten 							TEXT ,
    context 						TEXT ,
    priority 						TEXT ,
    application 					TEXT ,
    data 							TEXT ,
    timeout 						TEXT ,
    callerid 						TEXT ,
    variable 						TEXT ,
    account 						TEXT ,
    async 							TEXT ,
    actionid 						TEXT ,
	id_server						INTEGER,
	id_server_group					INTEGER
) WITH OIDS;

ALTER TABLE ONLY cc_callback_spool
    ADD CONSTRAINT cc_callback_spool_pkey PRIMARY KEY (id);
ALTER TABLE ONLY cc_callback_spool
    ADD CONSTRAINT cc_callback_spool_uniqueid_key UNIQUE (uniqueid);


CREATE TABLE cc_server_manager (
    id 								BIGSERIAL NOT NULL,
	id_group						INTEGER DEFAULT 1,
    server_ip 						TEXT ,
    manager_host 					TEXT ,
    manager_username 				TEXT ,
    manager_secret 					TEXT ,
	lasttime_used		 			TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW()
) WITH OIDS;
ALTER TABLE ONLY cc_server_manager
    ADD CONSTRAINT cc_server_manager_pkey PRIMARY KEY (id);
INSERT INTO cc_server_manager (id_group, server_ip, manager_host, manager_username, manager_secret) VALUES (1, 'localhost', 'localhost', 'myasterisk', 'mycode');


CREATE TABLE cc_server_group (
	id								BIGSERIAL NOT NULL,
	name							TEXT ,
	description						TEXT
) WITH OIDS;
ALTER TABLE ONLY cc_server_group
    ADD CONSTRAINT cc_server_group_pkey PRIMARY KEY (id);
INSERT INTO cc_server_group (id, name, description) VALUES (1, 'default', 'default group of server');



CREATE TABLE cc_invoices (
    id 								BIGSERIAL NOT NULL,
    cardid 							BIGINT NOT NULL,
    orderref 						TEXT ,
    invoicecreated_date 			TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    cover_startdate 				TIMESTAMP WITHOUT TIME ZONE,
    cover_enddate 					TIMESTAMP WITHOUT TIME ZONE,
    amount 							NUMERIC(15,5) DEFAULT 0,
    tax 							NUMERIC(15,5) DEFAULT 0,
    total 							NUMERIC(15,5) DEFAULT 0,
    invoicetype 					INTEGER,
    filename 						TEXT,
	payment_date 					TIMESTAMP WITHOUT TIME ZONE,
	payment_status 					INTEGER DEFAULT 0
) WITH OIDS;

ALTER TABLE ONLY cc_invoices
    ADD CONSTRAINT cc_invoices_pkey PRIMARY KEY (id);
CREATE INDEX ind_cc_invoices ON cc_invoices USING btree (cover_startdate);


CREATE TABLE cc_invoice_history (
    id 								BIGSERIAL NOT NULL,
    invoiceid 						INTEGER NOT NULL,	
    invoicesent_date 				TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    invoicestatus 					INTEGER
) WITH OIDS;
ALTER TABLE ONLY cc_invoice_history
    ADD CONSTRAINT cc_invoice_history_pkey PRIMARY KEY (id);
CREATE INDEX ind_cc_invoice_history ON cc_invoice_history USING btree (invoicesent_date);


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
    totalcardperform INTEGER NOT NULL DEFAULT 0
);
ALTER TABLE ONLY cc_subscription_fee
ADD CONSTRAINT cc_subscription_fee_pkey PRIMARY KEY (id);

-- ## 	INSTEAD USE CC_CHARGE  ##
-- CREATE TABLE cc_subscription_fee_card (
--     id 						BIGSERIAL NOT NULL,
--     id_cc_card 				 NOT NULL,
-- 	id_cc_subscription_fee 	 NOT NULL,
--     datefee 				TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT now(),
--     fee 					NUMERIC(12,4) NOT NULL,
-- 	fee_converted			NUMERIC(12,4) NOT NULL,
-- 	currency 				CHARACTER VARYING(3) DEFAULT 'USD'::CHARACTER VARYING
-- );
-- ALTER TABLE ONLY cc_subscription_fee_card
-- ADD CONSTRAINT cc_subscription_fee_card_pkey PRIMARY KEY (id)
-- 
-- 
-- CREATE INDEX ind_cc_charge_id_cc_card 								ON cc_subscription_fee_card USING btree (id_cc_card);
-- CREATE INDEX ind_cc_subscription_fee_card_id_cc_subscription_fee 	ON cc_subscription_fee_card USING btree (id_cc_subscription_fee);
-- CREATE INDEX ind_cc_subscription_fee_card_datefee 					ON cc_subscription_fee_card USING btree (datefee);


CREATE TABLE cc_outbound_cid_group (
    id 					BIGSERIAL NOT NULL,
    creationdate 		TIMESTAMP(0) without time zone DEFAULT NOW(),
    group_name 			TEXT NOT NULL    
    
);
ALTER TABLE ONLY cc_outbound_cid_group
ADD CONSTRAINT cc_outbound_cid_group_pkey PRIMARY KEY (id);


CREATE TABLE cc_outbound_cid_list (
    id 					BIGSERIAL NOT NULL,
	outbound_cid_group	BIGINT NOT NULL,
	cid					TEXT NOT NULL,    
    activated 			INTEGER NOT NULL DEFAULT 0,
    creationdate 		TIMESTAMP(0) without time zone DEFAULT NOW()   
);
ALTER TABLE ONLY cc_outbound_cid_list
ADD CONSTRAINT cc_outbound_cid_list_pkey PRIMARY KEY (id);








CREATE TABLE cc_payment_methods (
    id 									BIGSERIAL NOT NULL,
    payment_method 						TEXT NOT NULL,
    payment_filename 					TEXT NOT NULL,
    active 								CHARACTER VARYING(1) DEFAULT 'f' NOT NULL
);
ALTER TABLE ONLY cc_payment_methods
    ADD CONSTRAINT cc_payment_methods_pkey PRIMARY KEY (id);

Insert into cc_payment_methods (payment_method,payment_filename,active) values('paypal','paypal.php','t');
Insert into cc_payment_methods (payment_method,payment_filename,active) values('Authorize.Net','authorizenet.php','t');
Insert into cc_payment_methods (payment_method,payment_filename,active) values('MoneyBookers','moneybookers.php','t');


CREATE TABLE cc_payments (
  id 									BIGSERIAL NOT NULL,
  customers_id 							CHARACTER VARYING(60) NOT NULL,
  customers_name 						TEXT NOT NULL,
  customers_email_address 				TEXT NOT NULL,
  item_name 							TEXT NOT NULL,
  item_id 								TEXT NOT NULL,
  item_quantity 						INTEGER NOT NULL DEFAULT 0,
  payment_method 						VARCHAR(32) NOT NULL,
  cc_type 								CHARACTER VARYING(20),
  cc_owner 								CHARACTER VARYING(64),
  cc_number 							CHARACTER VARYING(32),
  cc_expires 							CHARACTER VARYING(6),
  orders_status 						INTEGER NOT NULL,
  orders_amount 						NUMERIC(14,6),
  last_modified 						TIMESTAMP WITHOUT TIME ZONE,
  date_purchased 						TIMESTAMP WITHOUT TIME ZONE,
  orders_date_finished 					TIMESTAMP WITHOUT TIME ZONE,
  currency 								CHARACTER VARYING(3),
  currency_value 						decimal(14,6)
);

ALTER TABLE ONLY cc_payments
    ADD CONSTRAINT cc_payments_pkey PRIMARY KEY (id);


CREATE TABLE cc_payments_status (
  id 									BIGSERIAL NOT NULL,
  status_id 							INTEGER NOT NULL,
  status_name 							CHARACTER VARYING(200) NOT NULL
);
ALTER TABLE ONLY cc_payments_status
    ADD CONSTRAINT cc_payments_status_pkey PRIMARY KEY (id);


Insert into cc_payments_status (status_id,status_name) values (-2, 'Failed');
Insert into cc_payments_status (status_id,status_name) values (-1, 'Denied');
Insert into cc_payments_status (status_id,status_name) values (0, 'Pending');
Insert into cc_payments_status (status_id,status_name) values (1, 'In-Progress');
Insert into cc_payments_status (status_id,status_name) values (2, 'Completed');
Insert into cc_payments_status (status_id,status_name) values (3, 'Processed');
Insert into cc_payments_status (status_id,status_name) values (4, 'Refunded');
Insert into cc_payments_status (status_id,status_name) values (5, 'Unknown');

CREATE TABLE cc_configuration (
  configuration_id 						BIGSERIAL NOT NULL,
  configuration_title 					CHARACTER VARYING(64) NOT NULL,
  configuration_key 					CHARACTER VARYING(64) NOT NULL,
  configuration_value 					CHARACTER VARYING(255) NOT NULL,
  configuration_description 			CHARACTER VARYING(255) NOT NULL,
  configuration_type 					INTEGER NOT NULL DEFAULT 0,
  use_function 							CHARACTER VARYING(255) NULL,
  set_function 							CHARACTER VARYING(255) NULL

);
ALTER TABLE ONLY cc_configuration
ADD CONSTRAINT cc_configuration_id_pkey PRIMARY KEY (configuration_id);


insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description) values ('Login Username', 'MODULE_PAYMENT_AUTHORIZENET_LOGIN', 'testing', 'The login username used for the Authorize.net service');
insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description) values ('Transaction Key', 'MODULE_PAYMENT_AUTHORIZENET_TXNKEY', 'Test', 'Transaction Key used for encrypting TP data');
insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Transaction Mode', 'MODULE_PAYMENT_AUTHORIZENET_TESTMODE', 'Test', 'Transaction mode used for processing orders', 'tep_cfg_select_option(array(\'Test\', \'Production\'), ');
insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Transaction Method', 'MODULE_PAYMENT_AUTHORIZENET_METHOD', 'Credit Card', 'Transaction method used for processing orders', 'tep_cfg_select_option(array(\'Credit Card\', \'eCheck\'), ');
insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Customer Notifications', 'MODULE_PAYMENT_AUTHORIZENET_EMAIL_CUSTOMER', 'False', 'Should Authorize.Net e-mail a receipt to the customer?', 'tep_cfg_select_option(array(\'True\', \'False\'), ');
insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Enable Authorize.net Module', 'MODULE_PAYMENT_AUTHORIZENET_STATUS', 'True', 'Do you want to accept Authorize.net payments?', 'tep_cfg_select_option(array(\'True\', \'False\'), ');

insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Enable PayPal Module', 'MODULE_PAYMENT_PAYPAL_STATUS', 'True', 'Do you want to accept PayPal payments?','tep_cfg_select_option(array(\'True\', \'False\'), ');
insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description) values ('E-Mail Address', 'MODULE_PAYMENT_PAYPAL_ID', 'you@yourbusiness.com', 'The e-mail address to use for the PayPal service');
insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Transaction Currency', 'MODULE_PAYMENT_PAYPAL_CURRENCY', 'Selected Currency', 'The currency to use for credit card transactions', 'tep_cfg_select_option(array(\'Selected Currency\',\'USD\',\'CAD\',\'EUR\',\'GBP\',\'JPY\'), ');

insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description) values ('E-Mail Address', 'MODULE_PAYMENT_MONEYBOOKERS_ID', 'you@yourbusiness.com', 'The eMail address to use for the moneybookers service');
insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description) values ('Referral ID', 'MODULE_PAYMENT_MONEYBOOKERS_REFID', '989999', 'Your personal Referral ID from moneybookers.com');
insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Transaction Currency', 'MODULE_PAYMENT_MONEYBOOKERS_CURRENCY', 'Selected Currency', 'The default currency for the payment transactions', 'tep_cfg_select_option(array(\'Selected Currency\',\'EUR\', \'USD\', \'GBP\', \'HKD\', \'SGD\', \'JPY\', \'CAD\', \'AUD\', \'CHF\', \'DKK\', \'SEK\', \'NOK\', \'ILS\', \'MYR\', \'NZD\', \'TWD\', \'THB\', \'CZK\', \'HUF\', \'SKK\', \'ISK\', \'INR\'), ');
insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Transaction Language', 'MODULE_PAYMENT_MONEYBOOKERS_LANGUAGE', 'Selected Language', 'The default language for the payment transactions', 'tep_cfg_select_option(array(\'Selected Language\',\'EN\', \'DE\', \'ES\', \'FR\'), ');
insert into cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function) values ('Enable moneybookers Module', 'MODULE_PAYMENT_MONEYBOOKERS_STATUS', 'True', 'Do you want to accept moneybookers payments?','tep_cfg_select_option(array(\'True\', \'False\'), ');

CREATE TABLE cc_epayment_log (
    id 				BIGSERIAL NOT NULL,
    cardid 			INTEGER NOT NULL DEFAULT 0,	
	amount 			DOUBLE PRECISION NOT NULL DEFAULT 0,
	vat 			DOUBLE PRECISION NOT NULL DEFAULT 0,
	paymentmethod	CHARACTER VARYING(255) NOT NULL,
    cc_owner 		CHARACTER VARYING(255) NOT NULL,
    cc_number 		CHARACTER VARYING(255) NOT NULL,
    cc_expires 		CHARACTER VARYING(255) NOT NULL,
    creationdate 	TIMESTAMP(0) without time zone DEFAULT NOW(),
    status 			INTEGER NOT NULL DEFAULT 0
);
ALTER TABLE ONLY cc_epayment_log
ADD CONSTRAINT cc_epayment_log_pkey PRIMARY KEY (id);

CREATE TABLE cc_system_log (
    id 								BIGSERIAL NOT NULL,
    iduser 							INTEGER NOT NULL DEFAULT 0,
    loglevel	 					INTEGER NOT NULL DEFAULT 0,
    action			 				TEXT NOT NULL,
    description						TEXT,    
    data			 				TEXT,
	tablename						CHARACTER VARYING(255),
	pagename			 			CHARACTER VARYING(255),
	ipaddress						CHARACTER VARYING(255),	
	creationdate  					TIMESTAMP(0) without time zone DEFAULT NOW()   
);
ALTER TABLE ONLY cc_system_log
ADD CONSTRAINT cc_system_log_pkey PRIMARY KEY (id);
