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
--     psql -f a2billing-pgsql-schema-v1.0.2.sql template1

--     NOTE: the errors you will see about missing tables are OK, it's the default behaviour of pgsql.
    
--     When prompted for the password, please enter the one you choose. In our case, it's 'a2billing'. 





CREATE TABLE cc_didgroup (
	id bigserial NOT NULL,
    idreseller integer DEFAULT 0 NOT NULL,	
    creationdate timestamp without time zone DEFAULT now(),
    didgroupname text NOT NULL
);

ALTER TABLE ONLY cc_didgroup
    ADD CONSTRAINT cc_didgroup_pkey PRIMARY KEY (id);



CREATE TABLE cc_did (
    id bigserial NOT NULL,
	id_cc_didgroup bigint NOT NULL,
	id_cc_country integer NOT NULL,    
    activated integer DEFAULT 1 NOT NULL,
    iduser integer DEFAULT 0 NOT NULL,
    did text NOT NULL,
    creationdate timestamp without time zone DEFAULT now(),	
    startingdate timestamp without time zone DEFAULT now(),
    expirationdate timestamp without time zone,
    description text,
    secondusedreal integer DEFAULT 0,
	billingtype integer DEFAULT 0,
	fixrate numeric(12,4) NOT NULL
);
-- billtype: 0 = fix per month + dialoutrate, 1= fix per month, 2 = dialoutrate, 3 = free


ALTER TABLE ONLY cc_did
    ADD CONSTRAINT cc_did_pkey PRIMARY KEY (id);

ALTER TABLE ONLY cc_did
    ADD CONSTRAINT cons_did_cc_did UNIQUE (did);
	


CREATE TABLE cc_did_destination (
    id bigserial NOT NULL,
    destination text NOT NULL,
	priority integer DEFAULT 0 NOT NULL,
    id_cc_card bigint NOT NULL,
	id_cc_did bigint NOT NULL,	
	creationdate timestamp without time zone DEFAULT now(),
    activated integer DEFAULT 1 NOT NULL,
	secondusedreal integer DEFAULT 0,
	voip_call integer DEFAULT 0
);


ALTER TABLE ONLY cc_did_destination
    ADD CONSTRAINT cc_did_destination_pkey PRIMARY KEY (id);




CREATE TABLE cc_charge (
    id bigserial NOT NULL,
	id_cc_card bigint NOT NULL,	
    iduser integer DEFAULT 0 NOT NULL,
	creationdate timestamp without time zone DEFAULT now(),	
	amount numeric(12,4) NOT NULL,
	chargetype integer DEFAULT 0,    
    description text
);

ALTER TABLE ONLY cc_charge
    ADD CONSTRAINT cc_charge_pkey PRIMARY KEY (id);

-- chargetype : 1 - connection charge for DID setup, 2 - Montly charge for DID use, 3 - just wanted to charge you for extra, 4 - cactus renting charges, etc...



CREATE TABLE cc_paypal (
  id bigserial NOT NULL,
  payer_id character varying(60) default NULL,
  payment_date character varying(50) default NULL,
  txn_id character varying(50) default NULL,
  first_name character varying(50) default NULL,
  last_name character varying(50) default NULL,
  payer_email character varying(75) default NULL,
  payer_status character varying(50) default NULL,
  payment_type character varying(50) default NULL,
  memo text,
  item_name character varying(127) default NULL,
  item_number character varying(127) default NULL,
  quantity bigint NOT NULL default '0',
  mc_gross numeric(9,2) default NULL,
  mc_fee numeric(9,2) default NULL,
  tax numeric(9,2) default NULL,
  mc_currency character varying(3) default NULL,
  address_name character varying(255) NOT NULL default '',
  address_street character varying(255) NOT NULL default '',
  address_city character varying(255) NOT NULL default '',
  address_state character varying(255) NOT NULL default '',
  address_zip character varying(255) NOT NULL default '',
  address_country character varying(255) NOT NULL default '',
  address_status character varying(255) NOT NULL default '',
  payer_business_name character varying(255) NOT NULL default '',
  payment_status character varying(255) NOT NULL default '',
  pending_reason character varying(255) NOT NULL default '',
  reason_code character varying(255) NOT NULL default '',
  txn_type character varying(255) NOT NULL default ''
);

ALTER TABLE ONLY cc_paypal
ADD CONSTRAINT cc_paypal_pkey PRIMARY KEY (id);

ALTER TABLE ONLY cc_paypal
    ADD CONSTRAINT cons_txn_id_cc_paypal UNIQUE (txn_id);
	
	
	

CREATE TABLE cc_voucher (
    id bigserial NOT NULL,
    creationdate timestamp without time zone DEFAULT now(),
	usedate timestamp without time zone,
    expirationdate timestamp without time zone,	
	voucher text NOT NULL,
	usedcardnumber text,
    tag text,	
    credit numeric(12,4) NOT NULL,    
    activated boolean DEFAULT true NOT NULL,
	used integer DEFAULT 0,
    currency character varying(3) DEFAULT 'USD'::character varying
);

ALTER TABLE ONLY cc_voucher
    ADD CONSTRAINT cc_voucher_pkey PRIMARY KEY (id);
ALTER TABLE ONLY cc_voucher
    ADD CONSTRAINT cons_voucher_cc_voucher UNIQUE (voucher);




CREATE TABLE cc_service (
	id bigserial NOT NULL,	
	name text NOT NULL,
	amount double precision NOT NULL,
	period integer NOT NULL DEFAULT 1,
	rule integer NOT NULL DEFAULT 0,
	daynumber integer NOT NULL DEFAULT 0,
	stopmode integer NOT NULL DEFAULT 0,
	maxnumbercycle integer NOT NULL DEFAULT 0,
	status integer NOT NULL DEFAULT 0,
	numberofrun integer NOT NULL DEFAULT 0,
	datecreate timestamp(0) without time zone DEFAULT now(),
	datelastrun timestamp(0) without time zone DEFAULT now(),
	emailreport text,
	totalcredit double precision NOT NULL DEFAULT 0,
	totalcardperform integer NOT NULL DEFAULT 0
);
ALTER TABLE ONLY cc_service
ADD CONSTRAINT cc_service_pkey PRIMARY KEY (id);

	
CREATE TABLE cc_service_report (
	id bigserial NOT NULL,
	cc_service_id bigserial NOT NULL,
	daterun timestamp(0) without time zone DEFAULT now(),
	totalcardperform integer,
	totalcredit double precision
);
ALTER TABLE ONLY cc_service_report
ADD CONSTRAINT cc_service_report_pkey PRIMARY KEY (id);





CREATE TABLE cc_callerid (
    id bigserial NOT NULL,
    cid text NOT NULL,
    id_cc_card bigint NOT NULL,    
    activated boolean DEFAULT true NOT NULL
);

ALTER TABLE ONLY cc_callerid
    ADD CONSTRAINT cc_calleridd_pkey PRIMARY KEY (id);

ALTER TABLE ONLY cc_callerid
    ADD CONSTRAINT cc_callerid_cid_key UNIQUE (cid);


CREATE TABLE cc_ui_authen (
    userid bigserial NOT NULL,
    login text NOT NULL,
    "password" text NOT NULL,
    groupid integer,
    perms integer,
    confaddcust integer,
    name text,
    direction text,
    zipcode text,
    state text,
    phone text,
    fax text,
    datecreation timestamp with time zone DEFAULT now()
);



CREATE TABLE cc_call (
    id bigserial NOT NULL,
    sessionid text NOT NULL,
    uniqueid text NOT NULL,
    username text NOT NULL,
    nasipaddress text,
    starttime timestamp without time zone,
    stoptime timestamp without time zone,
    sessiontime integer,
    calledstation text,
    startdelay integer,
    stopdelay integer,
    terminatecause text,
    usertariff text,
    calledprovider text,
    calledcountry text,
    calledsub text,
    calledrate double precision,
    sessionbill double precision,
    destination text,
    id_tariffgroup integer,
    id_tariffplan integer,
    id_ratecard integer,
    id_trunk integer,
    sipiax integer DEFAULT 0,
	src text,
	id_did integer
);


CREATE TABLE cc_templatemail (
    mailtype text,
    fromemail text,
    fromname text,
    subject text,
    messagetext text,
    messagehtml text
);



CREATE TABLE cc_tariffgroup (
    id serial NOT NULL,
    iduser integer DEFAULT 0 NOT NULL,
    idtariffplan integer DEFAULT 0 NOT NULL,
    tariffgroupname text NOT NULL,
    lcrtype integer DEFAULT 0 NOT NULL,
    creationdate timestamp without time zone DEFAULT now(),
	removeinterprefix integer DEFAULT 0 NOT NULL
);



CREATE TABLE cc_tariffgroup_plan (
    idtariffgroup integer NOT NULL,
    idtariffplan integer NOT NULL
);



CREATE TABLE cc_tariffplan (
    id serial NOT NULL,
    iduser integer DEFAULT 0 NOT NULL,
    tariffname text NOT NULL,
    creationdate timestamp without time zone DEFAULT now(),
    startingdate timestamp without time zone DEFAULT now(),
    expirationdate timestamp without time zone,
    description text,
    id_trunk integer DEFAULT 0,
    secondusedreal integer DEFAULT 0,
    secondusedcarrier integer DEFAULT 0,
    secondusedratecard integer DEFAULT 0,
    reftariffplan integer DEFAULT 0,
    idowner integer DEFAULT 0,
	dnidprefix text NOT NULL DEFAULT 'all'::text	
);



CREATE TABLE cc_card (
    id bigserial NOT NULL,
    creationdate timestamp without time zone DEFAULT now(),
	firstusedate timestamp without time zone,
    expirationdate timestamp without time zone,
	enableexpire integer DEFAULT 0,
	expiredays integer DEFAULT 0,
    username text NOT NULL,
	useralias text NOT NULL,
    userpass text NOT NULL,
    uipass text,
    credit numeric(12,4) NOT NULL,
    tariff integer DEFAULT 0,
	id_didgroup integer DEFAULT 0,
    activated boolean DEFAULT false NOT NULL,
    lastname text,
    firstname text,
    address text,
    city text,
    state text,
    country text,
    zipcode text,
    phone text,
    email text,
    fax text,
    inuse integer DEFAULT 0,
    simultaccess integer DEFAULT 0,
    currency character varying(3) DEFAULT 'USD'::character varying,
    lastuse date DEFAULT now(),
    nbused integer DEFAULT 0,
    typepaid integer DEFAULT 0,
    creditlimit integer DEFAULT 0,
    voipcall integer DEFAULT 0,
    sip_buddy integer DEFAULT 0,
    iax_buddy integer DEFAULT 0,
    "language" text DEFAULT 'en'::text,
	redial text,
	runservice integer DEFAULT 0
);



CREATE TABLE cc_ratecard (
    id serial NOT NULL,
    idtariffplan integer DEFAULT 0 NOT NULL,
    dialprefix text NOT NULL,
    destination text NOT NULL,
    buyrate real DEFAULT 0 NOT NULL,
    buyrateinitblock integer DEFAULT 0 NOT NULL,
    buyrateincrement integer DEFAULT 0 NOT NULL,
    rateinitial real DEFAULT 0 NOT NULL,
    initblock integer DEFAULT 0 NOT NULL,
    billingblock integer DEFAULT 0 NOT NULL,
    connectcharge real DEFAULT 0 NOT NULL,
    disconnectcharge real DEFAULT 0 NOT NULL,
    stepchargea real DEFAULT 0 NOT NULL,
    chargea real DEFAULT 0 NOT NULL,
    timechargea integer DEFAULT 0 NOT NULL,
    billingblocka integer DEFAULT 0 NOT NULL,
    stepchargeb real DEFAULT 0 NOT NULL,
    chargeb real DEFAULT 0 NOT NULL,
    timechargeb integer DEFAULT 0 NOT NULL,
    billingblockb integer DEFAULT 0 NOT NULL,
    stepchargec real DEFAULT 0 NOT NULL,
    chargec real DEFAULT 0 NOT NULL,
    timechargec integer DEFAULT 0 NOT NULL,
    billingblockc integer DEFAULT 0 NOT NULL,
    startdate timestamp(0) without time zone DEFAULT now(),
    stopdate timestamp(0) without time zone,
    monday integer DEFAULT 1,
    tuesday integer DEFAULT 1,
    wednesday integer DEFAULT 1,
    thursday integer DEFAULT 1,
    friday integer DEFAULT 1,
    saturday integer DEFAULT 1,
    sunday integer DEFAULT 1,
    id_trunk integer DEFAULT -1,	
	musiconhold character varying(100)
);



CREATE TABLE cc_trunk (
    id_trunk serial NOT NULL,
    trunkcode text NOT NULL,
    trunkprefix text,
    providertech text NOT NULL,
    providerip text NOT NULL,
    removeprefix text,
    secondusedreal integer DEFAULT 0,
    secondusedcarrier integer DEFAULT 0,
    secondusedratecard integer DEFAULT 0,
    creationdate timestamp(0) without time zone DEFAULT now(),
	failover_trunk integer,
	addparameter text
);



CREATE TABLE cc_sip_buddies (
    id serial NOT NULL,
    name character varying(80) DEFAULT ''::character varying NOT NULL,
	"type" character varying(6) DEFAULT 'friend'::character varying NOT NULL,
	username character varying(80) DEFAULT ''::character varying NOT NULL,	
    accountcode character varying(20),    
	regexten character varying(20),
    callerid character varying(80),	
	amaflags character varying(7),
	secret character varying(80),
	md5secret character varying(80),
    nat character varying(3) DEFAULT 'yes'::character varying NOT NULL,
    dtmfmode character varying(7) DEFAULT 'yes'::character varying NOT NULL,	
	disallow character varying(100) DEFAULT 'all'::character varying,
    allow character varying(100) DEFAULT 'gsm,ulaw,alaw'::character varying,
    host character varying(31) DEFAULT ''::character varying NOT NULL,
	qualify character varying(3) DEFAULT 'yes'::character varying NOT NULL,
	canreinvite character varying(3) DEFAULT 'yes'::character varying,
	callgroup character varying(10),
    context character varying(80),
    defaultip character varying(15),
    fromuser character varying(80),    
	fromdomain character varying(80),
    insecure character varying(4),
    "language" character varying(2),
    mailbox character varying(50),
    permit character varying(95),
    deny character varying(95),
    mask character varying(95),
	pickupgroup character varying(10),
    port character varying(5) DEFAULT ''::character varying NOT NULL,
    restrictcid character varying(1),
    rtptimeout character varying(3),
    rtpholdtimeout character varying(3),
	musiconhold character varying(100),
    regseconds integer DEFAULT 0 NOT NULL,
    ipaddr character varying(15) DEFAULT ''::character varying NOT NULL,
    cancallforward character varying(3) DEFAULT 'yes'::character varying
);



CREATE TABLE cc_iax_buddies (
    id serial NOT NULL,
    name character varying(80) DEFAULT ''::character varying NOT NULL,
	"type" character varying(6) DEFAULT 'friend'::character varying NOT NULL,
	username character varying(80) DEFAULT ''::character varying NOT NULL,	
    accountcode character varying(20),    
	regexten character varying(20),
    callerid character varying(80),	
	amaflags character varying(7),
	secret character varying(80),
	md5secret character varying(80),
    nat character varying(3) DEFAULT 'yes'::character varying NOT NULL,
    dtmfmode character varying(7) DEFAULT 'yes'::character varying NOT NULL,	
	disallow character varying(100) DEFAULT 'all'::character varying,
    allow character varying(100) DEFAULT 'gsm,ulaw,alaw'::character varying,
    host character varying(31) DEFAULT ''::character varying NOT NULL,
	qualify character varying(3) DEFAULT 'yes'::character varying NOT NULL,
	canreinvite character varying(3) DEFAULT 'yes'::character varying,
	callgroup character varying(10),
    context character varying(80),
    defaultip character varying(15),
    fromuser character varying(80),    
	fromdomain character varying(80),
    insecure character varying(4),
    "language" character varying(2),
    mailbox character varying(50),
    permit character varying(95),
    deny character varying(95),
    mask character varying(95),
	pickupgroup character varying(10),
    port character varying(5) DEFAULT ''::character varying NOT NULL,
    restrictcid character varying(1),
    rtptimeout character varying(3),
    rtpholdtimeout character varying(3),
	musiconhold character varying(100),
    regseconds integer DEFAULT 0 NOT NULL,
    ipaddr character varying(15) DEFAULT ''::character varying NOT NULL,
    cancallforward character varying(3) DEFAULT 'yes'::character varying
);




CREATE TABLE cc_logrefill (
    id serial NOT NULL,
    date timestamp(0) without time zone DEFAULT now() NOT NULL,
    credit numeric(12,4) NOT NULL,
    card_id bigint NOT NULL,
    reseller_id bigint
);


CREATE TABLE cc_logpayment (
    id serial NOT NULL,
    date timestamp(0) without time zone DEFAULT now() NOT NULL,
    payment real NOT NULL,
    card_id bigint NOT NULL,
    reseller_id bigint
);




INSERT INTO cc_ui_authen VALUES (2, 'admin', 'mypassword', 0, 1023, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-02-26 21:14:05.391501-05');
INSERT INTO cc_ui_authen VALUES (1, 'root', 'myroot', 0, 1023, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-02-26 20:33:27.691314-05');


INSERT INTO cc_templatemail VALUES ('signup', 'billing@kikicoo.com', 'kikicoo', 'SIGNUP CONFIRMATION', 'Thank you for registering with us

Please make sure you active your account by making payment to us either by
credit card, wire transfer, money order, cheque, and western union money
transfer, money Gram, and Pay pal.

Your account is <b>$card_gen</b>.

Your password is <b>$password</b>

Kind regards,
The kikicoo Team
', '');
INSERT INTO cc_templatemail VALUES ('reminder', 'billing@kikicoo.com', 'kikicoo', 'REMINDER', 'Our record indicates that you have less than $min_credit usd in your "$card_gen" kikicoo account.

We hope this message provides you with enough notice to refill your account.
We value your business, but our system can disconnect you automatically
when you reach your pre-paid balance.

Please login to your account through our website to check your account
details. Plus,
you can pay by credit card, on demand.

http://www.kikicoo.com/login.php

If you believe this information to be incorrect please contact
billing@kikicoo.com
immediately.


Kind regards,
The kikicoo Team', '');


INSERT INTO cc_trunk VALUES (1, 'default', '011', 'IAX2', 'kiki@switch-2.kiki.net', '', 0, 0, 0, '2005-03-14 01:01:36', 0, NULL);



CREATE INDEX ind_cc_ratecard_dialprefix ON cc_ratecard USING btree (dialprefix);




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
    id serial NOT NULL,
    countrycode text NOT NULL,
    countryname text NOT NULL
);

ALTER TABLE ONLY cc_country
    ADD CONSTRAINT cc_country_pkey PRIMARY KEY (id);



INSERT INTO cc_country VALUES (1, 'AFG', 'Afghanistan');
INSERT INTO cc_country VALUES (2, 'ALB', 'Albania');
INSERT INTO cc_country VALUES (3, 'DZA', 'Algeria');
INSERT INTO cc_country VALUES (4, 'ASM', 'American Samoa');
INSERT INTO cc_country VALUES (5, 'AND', 'Andorra');
INSERT INTO cc_country VALUES (6, 'AGO', 'Angola');
INSERT INTO cc_country VALUES (7, 'AIA', 'Anguilla');
INSERT INTO cc_country VALUES (8, 'ATA', 'Antarctica');
INSERT INTO cc_country VALUES (9, 'ATG', 'Antigua And Barbuda');
INSERT INTO cc_country VALUES (10, 'ARG', 'Argentina');
INSERT INTO cc_country VALUES (11, 'ARM', 'Armenia');
INSERT INTO cc_country VALUES (12, 'ABW', 'Aruba');
INSERT INTO cc_country VALUES (13, 'AUS', 'Australia');
INSERT INTO cc_country VALUES (14, 'AUT', 'Austria');
INSERT INTO cc_country VALUES (15, 'AZE', 'Azerbaijan');
INSERT INTO cc_country VALUES (16, 'BHS', 'Bahamas');
INSERT INTO cc_country VALUES (17, 'BHR', 'Bahrain');
INSERT INTO cc_country VALUES (18, 'BGD', 'Bangladesh');
INSERT INTO cc_country VALUES (19, 'BRB', 'Barbados');
INSERT INTO cc_country VALUES (20, 'BLR', 'Belarus');
INSERT INTO cc_country VALUES (21, 'BEL', 'Belgium');
INSERT INTO cc_country VALUES (22, 'BLZ', 'Belize');
INSERT INTO cc_country VALUES (23, 'BEN', 'Benin');
INSERT INTO cc_country VALUES (24, 'BMU', 'Bermuda');
INSERT INTO cc_country VALUES (25, 'BTN', 'Bhutan');
INSERT INTO cc_country VALUES (26, 'BOL', 'Bolivia');
INSERT INTO cc_country VALUES (27, 'BIH', 'Bosnia And Herzegovina');
INSERT INTO cc_country VALUES (28, 'BWA', 'Botswana');
INSERT INTO cc_country VALUES (29, 'BV', 'Bouvet Island');
INSERT INTO cc_country VALUES (30, 'BRA', 'Brazil');
INSERT INTO cc_country VALUES (31, 'IO', 'British Indian Ocean Territory');
INSERT INTO cc_country VALUES (32, 'BRN', 'Brunei Darussalam');
INSERT INTO cc_country VALUES (33, 'BGR', 'Bulgaria');
INSERT INTO cc_country VALUES (34, 'BFA', 'Burkina Faso');
INSERT INTO cc_country VALUES (35, 'BDI', 'Burundi');
INSERT INTO cc_country VALUES (36, 'KHM', 'Cambodia');
INSERT INTO cc_country VALUES (37, 'CMR', 'Cameroon');
INSERT INTO cc_country VALUES (38, 'CAN', 'Canada');
INSERT INTO cc_country VALUES (39, 'CPV', 'Cape Verde');
INSERT INTO cc_country VALUES (40, 'CYM', 'Cayman Islands');
INSERT INTO cc_country VALUES (41, 'CAF', 'Central African Republic');
INSERT INTO cc_country VALUES (42, 'TCD', 'Chad');
INSERT INTO cc_country VALUES (43, 'CHL', 'Chile');
INSERT INTO cc_country VALUES (44, 'CHN', 'China');
INSERT INTO cc_country VALUES (45, 'CXR', 'Christmas Island');
INSERT INTO cc_country VALUES (46, 'CCK', 'Cocos (Keeling) Islands');
INSERT INTO cc_country VALUES (47, 'COL', 'Colombia');
INSERT INTO cc_country VALUES (48, 'COM', 'Comoros');
INSERT INTO cc_country VALUES (49, 'COG', 'Congo');
INSERT INTO cc_country VALUES (50, 'COD', 'Congo, The Democratic Republic Of The');
INSERT INTO cc_country VALUES (51, 'COK', 'Cook Islands');
INSERT INTO cc_country VALUES (52, 'CRI', 'Costa Rica');
INSERT INTO cc_country VALUES (54, 'HRV', 'Croatia');
INSERT INTO cc_country VALUES (55, 'CUB', 'Cuba');
INSERT INTO cc_country VALUES (56, 'CYP', 'Cyprus');
INSERT INTO cc_country VALUES (57, 'CZE', 'Czech Republic');
INSERT INTO cc_country VALUES (58, 'DNK', 'Denmark');
INSERT INTO cc_country VALUES (59, 'DJI', 'Djibouti');
INSERT INTO cc_country VALUES (60, 'DMA', 'Dominica');
INSERT INTO cc_country VALUES (61, 'DOM', 'Dominican Republic');
INSERT INTO cc_country VALUES (62, 'ECU', 'Ecuador');
INSERT INTO cc_country VALUES (63, 'EGY', 'Egypt');
INSERT INTO cc_country VALUES (64, 'SLV', 'El Salvador');
INSERT INTO cc_country VALUES (65, 'GNQ', 'Equatorial Guinea');
INSERT INTO cc_country VALUES (66, 'ERI', 'Eritrea');
INSERT INTO cc_country VALUES (67, 'EST', 'Estonia');
INSERT INTO cc_country VALUES (68, 'ETH', 'Ethiopia');
INSERT INTO cc_country VALUES (69, 'FLK', 'Falkland Islands (Malvinas)');
INSERT INTO cc_country VALUES (70, 'FRO', 'Faroe Islands');
INSERT INTO cc_country VALUES (71, 'FJI', 'Fiji');
INSERT INTO cc_country VALUES (72, 'FIN', 'Finland');
INSERT INTO cc_country VALUES (73, 'FRA', 'France');
INSERT INTO cc_country VALUES (74, 'GUF', 'French Guiana');
INSERT INTO cc_country VALUES (75, 'PYF', 'French Polynesia');
INSERT INTO cc_country VALUES (76, 'ATF', 'French Southern Territories');
INSERT INTO cc_country VALUES (77, 'GAB', 'Gabon');
INSERT INTO cc_country VALUES (78, 'GMB', 'Gambia');
INSERT INTO cc_country VALUES (79, 'GEO', 'Georgia');
INSERT INTO cc_country VALUES (80, 'DEU', 'Germany');
INSERT INTO cc_country VALUES (81, 'GHA', 'Ghana');
INSERT INTO cc_country VALUES (82, 'GIB', 'Gibraltar');
INSERT INTO cc_country VALUES (83, 'GRC', 'Greece');
INSERT INTO cc_country VALUES (84, 'GRL', 'Greenland');
INSERT INTO cc_country VALUES (85, 'GRD', 'Grenada');
INSERT INTO cc_country VALUES (86, 'GLP', 'Guadeloupe');
INSERT INTO cc_country VALUES (87, 'GUM', 'Guam');
INSERT INTO cc_country VALUES (88, 'GTM', 'Guatemala');
INSERT INTO cc_country VALUES (89, 'GIN', 'Guinea');
INSERT INTO cc_country VALUES (90, 'GNB', 'Guinea-Bissau');
INSERT INTO cc_country VALUES (91, 'GUY', 'Guyana');
INSERT INTO cc_country VALUES (92, 'HTI', 'Haiti');
INSERT INTO cc_country VALUES (93, 'HM', 'Heard Island And McDonald Islands');
INSERT INTO cc_country VALUES (94, 'VAT', 'Holy See (Vatican City State)');
INSERT INTO cc_country VALUES (95, 'HND', 'Honduras');
INSERT INTO cc_country VALUES (96, 'HKG', 'Hong Kong');
INSERT INTO cc_country VALUES (97, 'HUN', 'Hungary');
INSERT INTO cc_country VALUES (98, 'ISL', 'Iceland');
INSERT INTO cc_country VALUES (99, 'IND', 'India');
INSERT INTO cc_country VALUES (100, 'IDN', 'Indonesia');
INSERT INTO cc_country VALUES (101, 'IRN', 'Iran, Islamic Republic Of');
INSERT INTO cc_country VALUES (102, 'IRQ', 'Iraq');
INSERT INTO cc_country VALUES (103, 'IRL', 'Ireland');
INSERT INTO cc_country VALUES (104, 'ISR', 'Israel');
INSERT INTO cc_country VALUES (105, 'ITA', 'Italy');
INSERT INTO cc_country VALUES (106, 'JAM', 'Jamaica');
INSERT INTO cc_country VALUES (107, 'JPN', 'Japan');
INSERT INTO cc_country VALUES (108, 'JOR', 'Jordan');
INSERT INTO cc_country VALUES (109, 'KAZ', 'Kazakhstan');
INSERT INTO cc_country VALUES (110, 'KEN', 'Kenya');
INSERT INTO cc_country VALUES (111, 'KIR', 'Kiribati');
INSERT INTO cc_country VALUES (112, 'PRK', 'Korea, Democratic People''s Republic Of');
INSERT INTO cc_country VALUES (113, 'KOR', 'Korea, Republic of');
INSERT INTO cc_country VALUES (114, 'KWT', 'Kuwait');
INSERT INTO cc_country VALUES (115, 'KGZ', 'Kyrgyzstan');
INSERT INTO cc_country VALUES (116, 'LAO', 'Lao People''s Democratic Republic');
INSERT INTO cc_country VALUES (117, 'LVA', 'Latvia');
INSERT INTO cc_country VALUES (118, 'LBN', 'Lebanon');
INSERT INTO cc_country VALUES (119, 'LSO', 'Lesotho');
INSERT INTO cc_country VALUES (120, 'LBR', 'Liberia');
INSERT INTO cc_country VALUES (121, 'LBY', 'Libyan Arab Jamahiriya');
INSERT INTO cc_country VALUES (122, 'LIE', 'Liechtenstein');
INSERT INTO cc_country VALUES (123, 'LTU', 'Lithuania');
INSERT INTO cc_country VALUES (124, 'LUX', 'Luxembourg');
INSERT INTO cc_country VALUES (125, 'MAC', 'Macao');
INSERT INTO cc_country VALUES (126, 'MKD', 'Macedonia, The Former Yugoslav Republic Of');
INSERT INTO cc_country VALUES (127, 'MDG', 'Madagascar');
INSERT INTO cc_country VALUES (128, 'MWI', 'Malawi');
INSERT INTO cc_country VALUES (129, 'MYS', 'Malaysia');
INSERT INTO cc_country VALUES (130, 'MDV', 'Maldives');
INSERT INTO cc_country VALUES (131, 'MLI', 'Mali');
INSERT INTO cc_country VALUES (132, 'MLT', 'Malta');
INSERT INTO cc_country VALUES (133, 'MHL', 'Marshall islands');
INSERT INTO cc_country VALUES (134, 'MTQ', 'Martinique');
INSERT INTO cc_country VALUES (135, 'MRT', 'Mauritania');
INSERT INTO cc_country VALUES (136, 'MUS', 'Mauritius');
INSERT INTO cc_country VALUES (137, 'MYT', 'Mayotte');
INSERT INTO cc_country VALUES (138, 'MEX', 'Mexico');
INSERT INTO cc_country VALUES (139, 'FSM', 'Micronesia, Federated States Of');
INSERT INTO cc_country VALUES (140, 'MDA', 'Moldova, Republic Of');
INSERT INTO cc_country VALUES (141, 'MCO', 'Monaco');
INSERT INTO cc_country VALUES (142, 'MNG', 'Mongolia');
INSERT INTO cc_country VALUES (143, 'MSR', 'Montserrat');
INSERT INTO cc_country VALUES (144, 'MAR', 'Morocco');
INSERT INTO cc_country VALUES (145, 'MOZ', 'Mozambique');
INSERT INTO cc_country VALUES (146, 'MMR', 'Myanmar');
INSERT INTO cc_country VALUES (147, 'NAM', 'Namibia');
INSERT INTO cc_country VALUES (148, 'NRU', 'Nauru');
INSERT INTO cc_country VALUES (149, 'NPL', 'Nepal');
INSERT INTO cc_country VALUES (150, 'NLD', 'Netherlands');
INSERT INTO cc_country VALUES (151, 'ANT', 'Netherlands Antilles');
INSERT INTO cc_country VALUES (152, 'NCL', 'New Caledonia');
INSERT INTO cc_country VALUES (153, 'NZL', 'New Zealand');
INSERT INTO cc_country VALUES (154, 'NIC', 'Nicaragua');
INSERT INTO cc_country VALUES (155, 'NER', 'Niger');
INSERT INTO cc_country VALUES (156, 'NGA', 'Nigeria');
INSERT INTO cc_country VALUES (157, 'NIU', 'Niue');
INSERT INTO cc_country VALUES (158, 'NFK', 'Norfolk Island');
INSERT INTO cc_country VALUES (159, 'MNP', 'Northern Mariana Islands');
INSERT INTO cc_country VALUES (160, 'NOR', 'Norway');
INSERT INTO cc_country VALUES (161, 'OMN', 'Oman');
INSERT INTO cc_country VALUES (162, 'PAK', 'Pakistan');
INSERT INTO cc_country VALUES (163, 'PLW', 'Palau');
INSERT INTO cc_country VALUES (164, 'PSE', 'Palestinian Territory, Occupied');
INSERT INTO cc_country VALUES (165, 'PAN', 'Panama');
INSERT INTO cc_country VALUES (166, 'PNG', 'Papua New Guinea');
INSERT INTO cc_country VALUES (167, 'PRY', 'Paraguay');
INSERT INTO cc_country VALUES (168, 'PER', 'Peru');
INSERT INTO cc_country VALUES (169, 'PHL', 'Philippines');
INSERT INTO cc_country VALUES (170, 'PN', 'Pitcairn');
INSERT INTO cc_country VALUES (171, 'POL', 'Poland');
INSERT INTO cc_country VALUES (172, 'PRT', 'Portugal');
INSERT INTO cc_country VALUES (173, 'PRI', 'Puerto Rico');
INSERT INTO cc_country VALUES (174, 'QAT', 'Qatar');
INSERT INTO cc_country VALUES (175, 'REU', 'Reunion');
INSERT INTO cc_country VALUES (176, 'ROU', 'Romania');
INSERT INTO cc_country VALUES (177, 'RUS', 'Russian Federation');
INSERT INTO cc_country VALUES (178, 'RWA', 'Rwanda');
INSERT INTO cc_country VALUES (179, 'SHN', 'Saint Helena');
INSERT INTO cc_country VALUES (180, 'KNA', 'Saint Kitts And Nevis');
INSERT INTO cc_country VALUES (181, 'LCA', 'Saint Lucia');
INSERT INTO cc_country VALUES (182, 'SPM', 'Saint Pierre And Miquelon');
INSERT INTO cc_country VALUES (183, 'VCT', 'Saint Vincent And The Grenadines');
INSERT INTO cc_country VALUES (184, 'WSM', 'Samoa');
INSERT INTO cc_country VALUES (185, 'SMR', 'San Marino');
INSERT INTO cc_country VALUES (186, 'STP', 'Sao Tome And Principe');
INSERT INTO cc_country VALUES (187, 'SAU', 'Saudi Arabia');
INSERT INTO cc_country VALUES (188, 'SEN', 'Senegal');
INSERT INTO cc_country VALUES (189, 'SYC', 'Seychelles');
INSERT INTO cc_country VALUES (190, 'SLE', 'Sierra Leone');
INSERT INTO cc_country VALUES (191, 'SGP', 'Singapore');
INSERT INTO cc_country VALUES (192, 'SVK', 'Slovakia');
INSERT INTO cc_country VALUES (193, 'SVN', 'Slovenia');
INSERT INTO cc_country VALUES (194, 'SLB', 'Solomon Islands');
INSERT INTO cc_country VALUES (195, 'SOM', 'Somalia');
INSERT INTO cc_country VALUES (196, 'ZAF', 'South Africa');
INSERT INTO cc_country VALUES (197, 'GS', 'South Georgia And The South Sandwich Islands');
INSERT INTO cc_country VALUES (198, 'ESP', 'Spain');
INSERT INTO cc_country VALUES (199, 'LKA', 'Sri Lanka');
INSERT INTO cc_country VALUES (200, 'SDN', 'Sudan');
INSERT INTO cc_country VALUES (201, 'SUR', 'Suriname');
INSERT INTO cc_country VALUES (202, 'SJ', 'Svalbard and Jan Mayen');
INSERT INTO cc_country VALUES (203, 'SWZ', 'Swaziland');
INSERT INTO cc_country VALUES (204, 'SWE', 'Sweden');
INSERT INTO cc_country VALUES (205, 'CHE', 'Switzerland');
INSERT INTO cc_country VALUES (206, 'SYR', 'Syrian Arab Republic');
INSERT INTO cc_country VALUES (207, 'TWN', 'Taiwan, Province Of China');
INSERT INTO cc_country VALUES (208, 'TJK', 'Tajikistan');
INSERT INTO cc_country VALUES (209, 'TZA', 'Tanzania, United Republic Of');
INSERT INTO cc_country VALUES (210, 'THA', 'Thailand');
INSERT INTO cc_country VALUES (211, 'TL', 'Timor L''Este');
INSERT INTO cc_country VALUES (212, 'TGO', 'Togo');
INSERT INTO cc_country VALUES (213, 'TKL', 'Tokelau');
INSERT INTO cc_country VALUES (214, 'TON', 'Tonga');
INSERT INTO cc_country VALUES (215, 'TTO', 'Trinidad And Tobago');
INSERT INTO cc_country VALUES (216, 'TUN', 'Tunisia');
INSERT INTO cc_country VALUES (217, 'TUR', 'Turkey');
INSERT INTO cc_country VALUES (218, 'TKM', 'Turkmenistan');
INSERT INTO cc_country VALUES (219, 'TCA', 'Turks And Caicos Islands');
INSERT INTO cc_country VALUES (220, 'TUV', 'Tuvalu');
INSERT INTO cc_country VALUES (221, 'UGA', 'Uganda');
INSERT INTO cc_country VALUES (222, 'UKR', 'Ukraine');
INSERT INTO cc_country VALUES (223, 'ARE', 'United Arab Emirates');
INSERT INTO cc_country VALUES (224, 'GBR', 'United Kingdom');
INSERT INTO cc_country VALUES (225, 'USA', 'United States');
INSERT INTO cc_country VALUES (226, 'UM', 'United States Minor Outlying Islands');
INSERT INTO cc_country VALUES (227, 'URY', 'Uruguay');
INSERT INTO cc_country VALUES (228, 'UZB', 'Uzbekistan');
INSERT INTO cc_country VALUES (229, 'VUT', 'Vanuatu');
INSERT INTO cc_country VALUES (230, 'VEN', 'Venezuela');
INSERT INTO cc_country VALUES (231, 'VNM', 'Vietnam');
INSERT INTO cc_country VALUES (232, 'VGB', 'Virgin Islands, British');
INSERT INTO cc_country VALUES (233, 'VIR', 'Virgin Islands, U.S.');
INSERT INTO cc_country VALUES (234, 'WLF', 'Wallis And Futuna');
INSERT INTO cc_country VALUES (235, 'EH', 'Western Sahara');
INSERT INTO cc_country VALUES (236, 'YEM', 'Yemen');
INSERT INTO cc_country VALUES (237, 'YUG', 'Yugoslavia');
INSERT INTO cc_country VALUES (238, 'ZMB', 'Zambia');
INSERT INTO cc_country VALUES (239, 'ZWE', 'Zimbabwe');
INSERT INTO cc_country VALUES (240, 'ASC', 'Ascension Island');
INSERT INTO cc_country VALUES (241, 'DGA', 'Diego Garcia');
INSERT INTO cc_country VALUES (242, 'XNM', 'Inmarsat');
INSERT INTO cc_country VALUES (243, 'TMP', 'East timor');
INSERT INTO cc_country VALUES (244, 'AK', 'Alaska');
INSERT INTO cc_country VALUES (245, 'HI', 'Hawaii');
INSERT INTO cc_country VALUES (53, 'CIV', 'Cote d''Ivoire');
