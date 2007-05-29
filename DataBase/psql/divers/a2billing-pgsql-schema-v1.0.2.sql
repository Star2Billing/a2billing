--
-- A2Billing database
--

/* Default values - Please change them to whatever you want 
 
Database name is: mya2billing
Database user is: a2billinguser
User password is: a2billing



1. make sure that the Database user is GRANT to access the database in pg_hba.conf!

    a line like this will do it
    
    # TYPE  DATABASE    USER        IP-ADDRESS        IP-MASK           METHOD
    # Database asterisk/a2billing login with password for a non real user
    #
    local   mya2billing all						md5
    
    DON'T FORGET TO RESTART Postgresql SERVER IF YOU MADE ANY MODIFICATION ON THIS FILE
    
2. open a terminal and enter the below commands. We assume our superuser to be postgres.
   Please adapt to your setup.

    su - postgres
    psql -f a2billing-pgsql-schema-v1.0.2.sql template1

    NOTE: the errors you will see about missing tables are OK, it's the default behaviour of pgsql.
    
    When prompted for the password, please enter the one you choose. In our case, it's 'a2billing'. 
*/



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
	src text
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
INSERT INTO cc_templatemail VALUES ('reminder', 'billing@kikicoo.com', 'kikicoo', 'REMINDER', 'Our record indicates that you have less than $5.00 in your "$card_gen" kikicoo account.

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
