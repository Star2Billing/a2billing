--
-- A2Billing database script - create database for MYSQL 3.X & 4.X
--

/* 

Usage: 

mysql -u root -p"root password" < a2billing-mysql-schema-MYSQL.3.X-4.X_v1.1.0.sql  

*/

--
-- A2Billing database - Create database schema
--

 

CREATE TABLE cc_didgroup (
    id BIGINT NOT NULL AUTO_INCREMENT,
    idreseller INT DEFAULT 0 NOT NULL,
    didgroupname CHAR(50) NOT NULL,    
    creationdate  TIMESTAMP DEFAULT 'now()' NOT NULL,
    PRIMARY KEY (id)
);



CREATE TABLE cc_did (
    id BIGINT NOT NULL AUTO_INCREMENT,	
    id_cc_didgroup BIGINT NOT NULL,
    id_cc_country INT NOT NULL,    
    activated INT DEFAULT '1' NOT NULL,
    iduser INT DEFAULT '0' NOT NULL,
    did CHAR(50) NOT NULL,
    creationdate TIMESTAMP DEFAULT 'now()' NOT NULL,
    startingdate TIMESTAMP DEFAULT 'now()' NOT NULL,
    expirationdate TIMESTAMP,
    description MEDIUMTEXT,
    secondusedreal INT DEFAULT 0,
    billingtype INT DEFAULT 0,
    fixrate float DEFAULT 0 NOT NULL,	
    PRIMARY KEY (id),
    UNIQUE cons_cc_did_did (did)
);

-- billtype: 0 = fix per month + dialoutrate, 1= fix per month, 2 = dialoutrate, 3 = free



CREATE TABLE cc_did_destination (
    id BIGINT NOT NULL AUTO_INCREMENT,	
    destination CHAR(50) NOT NULL,
    priority INT DEFAULT 0 NOT NULL,
    id_cc_card BIGINT NOT NULL,
    id_cc_did BIGINT NOT NULL,	
    creationdate TIMESTAMP DEFAULT 'now()' NOT NULL,
    activated INT DEFAULT 1 NOT NULL,
    secondusedreal INT DEFAULT 0,	
    voip_call INT DEFAULT 0,
    PRIMARY KEY (id)
);




CREATE TABLE cc_charge (
    id BIGINT NOT NULL AUTO_INCREMENT,
    id_cc_card BIGINT NOT NULL,	
    iduser INT DEFAULT '0' NOT NULL,
    creationdate TIMESTAMP DEFAULT 'now()' NOT NULL,
    amount float DEFAULT 0 NOT NULL,
    chargetype INT DEFAULT 0,    
    description MEDIUMTEXT,
    PRIMARY KEY (id)
);

 

CREATE TABLE cc_paypal (
    id int(11) NOT NULL auto_increment,
    payer_id varchar(60) default NULL,
    payment_date varchar(50) default NULL,
    txn_id varchar(50) default NULL,
    first_name varchar(50) default NULL,
    last_name varchar(50) default NULL,
    payer_email varchar(75) default NULL,
    payer_status varchar(50) default NULL,
    payment_type varchar(50) default NULL,
    memo tinytext,
    item_name varchar(127) default NULL,
    item_number varchar(127) default NULL,
    quantity int(11) NOT NULL default '0',
    mc_gross decimal(9,2) default NULL,
    mc_fee decimal(9,2) default NULL,
    tax decimal(9,2) default NULL,
    mc_currency char(3) default NULL,
    address_name varchar(255) NOT NULL default '',
    address_street varchar(255) NOT NULL default '',
    address_city varchar(255) NOT NULL default '',
    address_state varchar(255) NOT NULL default '',
    address_zip varchar(255) NOT NULL default '',
    address_country varchar(255) NOT NULL default '',
    address_status varchar(255) NOT NULL default '',
    payer_business_name varchar(255) NOT NULL default '',
    payment_status varchar(255) NOT NULL default '',
    pending_reason varchar(255) NOT NULL default '',
    reason_code varchar(255) NOT NULL default '',
    txn_type varchar(255) NOT NULL default '',
    PRIMARY KEY  (id),
    UNIQUE KEY txn_id (txn_id),
    KEY txn_id_2 (txn_id)
);




CREATE TABLE cc_voucher (
    id BIGINT NOT NULL AUTO_INCREMENT,   
    creationdate TIMESTAMP NOT NULL DEFAULT 'now()',
    usedate TIMESTAMP,
    expirationdate TIMESTAMP,
    voucher CHAR(50) NOT NULL,
    usedcardnumber CHAR(50),
    tag CHAR(50),
    credit float DEFAULT 0 NOT NULL,
    activated CHAR(1) DEFAULT 'f' NOT NULL,
    used INT DEFAULT 0,    
    currency CHAR(3) DEFAULT 'USD',
    PRIMARY KEY (id),
    UNIQUE cons_cc_voucher_voucher (voucher)
);



CREATE TABLE cc_service (
    id BIGINT NOT NULL AUTO_INCREMENT,	
    name CHAR(100) NOT NULL,	
    amount float NOT NULL,	
    period INT NOT NULL DEFAULT '1',	
    rule INT NOT NULL DEFAULT '0',
    daynumber INT NOT NULL DEFAULT '0',
    stopmode INT NOT NULL DEFAULT '0',
    maxnumbercycle INT NOT NULL DEFAULT '0',	
    status INT NOT NULL DEFAULT '0',	
    numberofrun INT NOT NULL DEFAULT '0',	
    datecreate TIMESTAMP DEFAULT 'now()' NOT NULL,
    datelastrun TIMESTAMP DEFAULT 'now()' NOT NULL,
    emailreport CHAR(100) NOT NULL,
    totalcredit float NOT NULL DEFAULT '0',
    totalcardperform INT NOT NULL DEFAULT '0',
    PRIMARY KEY (id)
);
	


CREATE TABLE cc_service_report (
    id BIGINT NOT NULL AUTO_INCREMENT,
    cc_service_id BIGINT NOT NULL,
    daterun TIMESTAMP DEFAULT 'now()' NOT NULL,
    totalcardperform INT,
    totalcredit float,
    PRIMARY KEY (id)
);



CREATE TABLE cc_callerid (
    id BIGINT NOT NULL AUTO_INCREMENT,
    cid CHAR(100) NULL,
    id_cc_card BIGINT NOT NULL,    
    activated CHAR(1) DEFAULT 't' NOT NULL,
    PRIMARY KEY (id),
    UNIQUE cons_cc_callerid_cid (cid)
);


CREATE TABLE cc_ui_authen (
    userid BIGINT NOT NULL AUTO_INCREMENT,
    login CHAR(50) NOT NULL,
    password CHAR(50) NOT NULL,
    groupid INT,
    perms INT,
    confaddcust INT,
    name CHAR(50),
    direction CHAR(80),
    zipcode CHAR(20),
    state CHAR(20),
    phone CHAR(30),
    fax CHAR(30),
    datecreation TIMESTAMP NOT NULL DEFAULT 'now()',
    PRIMARY KEY (userid),
    UNIQUE cons_cc_ui_authen_login (login)
);


CREATE TABLE cc_call (
    id bigint(20) NOT NULL auto_increment,
    sessionid char(40) NOT NULL,
    uniqueid char(30) NOT NULL,
    username char(40) NOT NULL,
    nasipaddress char(30) default NULL,
    starttime timestamp NOT NULL default 'now()',
    stoptime timestamp NOT NULL default '0000-00-00 00:00:00',
    sessiontime int(11) default NULL,
    calledstation char(30) default NULL,
    startdelay int(11) default NULL,
    stopdelay int(11) default NULL,
    terminatecause char(20) default NULL,
    usertariff char(20) default NULL,
    calledprovider char(20) default NULL,
    calledcountry char(30) default NULL,
    calledsub char(20) default NULL,
    calledrate float default NULL,
    sessionbill float default NULL,
    destination char(40) default NULL,
    id_tariffgroup int(11) default NULL,
    id_tariffplan int(11) default NULL,
    id_ratecard int(11) default NULL,
    id_trunk int(11) default NULL,
    sipiax int(11) default '0',
    src char(40) default NULL,
    id_did int(11) default NULL,
    PRIMARY KEY  (id)
);


CREATE TABLE cc_templatemail (
    mailtype CHAR(50),
    fromemail CHAR(70),
    fromname CHAR(70),
    subject CHAR(70),
    messagetext LONGTEXT,
    messagehtml LONGTEXT,
    UNIQUE cons_cc_templatemail_mailtype (mailtype)
);



CREATE TABLE cc_tariffgroup (
    id INT NOT NULL AUTO_INCREMENT,
    iduser INT DEFAULT 0 NOT NULL,
    idtariffplan INT DEFAULT 0 NOT NULL,
    tariffgroupname CHAR(50) NOT NULL,
    lcrtype INT DEFAULT 0 NOT NULL,
    creationdate  TIMESTAMP DEFAULT 'now()' NOT NULL,
    removeinterprefix INT DEFAULT 0 NOT NULL,
    PRIMARY KEY (id)
);



CREATE TABLE cc_tariffgroup_plan (
    idtariffgroup INT NOT NULL,
    idtariffplan INT NOT NULL,
    PRIMARY KEY (idtariffgroup, idtariffplan)
);



CREATE TABLE cc_tariffplan (
    id INT NOT NULL AUTO_INCREMENT,
    iduser INT DEFAULT 0 NOT NULL,
    tariffname CHAR(50) NOT NULL,
    creationdate TIMESTAMP DEFAULT 'now()' NOT NULL,
    startingdate TIMESTAMP DEFAULT 'now()' NOT NULL,
    expirationdate TIMESTAMP,
    description MEDIUMTEXT,
    id_trunk INT DEFAULT 0,
    secondusedreal INT DEFAULT 0,
    secondusedcarrier INT DEFAULT 0,
    secondusedratecard INT DEFAULT 0,
    reftariffplan INT DEFAULT 0,
    idowner INT DEFAULT 0,
    dnidprefix CHAR(30) NOT NULL DEFAULT 'all',
    PRIMARY KEY (id),
    UNIQUE cons_cc_tariffplan_iduser_tariffname (iduser,tariffname)
);



CREATE TABLE cc_card (
    id BIGINT NOT NULL AUTO_INCREMENT,   
    creationdate TIMESTAMP DEFAULT 'now()' NOT NULL,
    firstusedate TIMESTAMP,
    expirationdate TIMESTAMP,
    enableexpire INT DEFAULT 0,
    expiredays INT DEFAULT 0,
    username CHAR(50) NOT NULL,
    useralias CHAR(50) NOT NULL,
    userpass CHAR(50) NOT NULL,
    uipass CHAR(50),
    credit float DEFAULT 0 NOT NULL,
    tariff INT DEFAULT 0,
    id_didgroup INT DEFAULT 0,
    activated CHAR(1) DEFAULT 'f' NOT NULL,
    lastname CHAR(50),
    firstname CHAR(50),
    address CHAR(100),
    city CHAR(40),
    state CHAR(40),
    country CHAR(40),
    zipcode CHAR(20),
    phone CHAR(20),
    email CHAR(70),
    fax CHAR(20),
    inuse INT DEFAULT 0,
    simultaccess INT DEFAULT 0,
    currency CHAR(3) DEFAULT 'USD',
    lastuse  TIMESTAMP DEFAULT 'now()' NOT NULL,
    nbused INT DEFAULT 0,
    typepaid INT DEFAULT 0,
    creditlimit INT DEFAULT 0,
    voipcall INT DEFAULT 0,
    sip_buddy INT DEFAULT 0,
    iax_buddy INT DEFAULT 0,
    language CHAR(5) DEFAULT 'en',
    redial CHAR(50),
    runservice INT DEFAULT 0,
    id_campaign INT DEFAULT 0,
    num_trials_done BIGINT DEFAULT 0,
    callback CHAR(50),
    PRIMARY KEY (id),
    UNIQUE cons_cc_card_username (username),
    UNIQUE cons_cc_card_useralias (useralias)
);



CREATE TABLE cc_ratecard (
    id INT NOT NULL AUTO_INCREMENT,
    idtariffplan INT DEFAULT 0 NOT NULL,
    dialprefix CHAR(30) NOT NULL,
    destination CHAR(50) NOT NULL,
    buyrate float DEFAULT 0 NOT NULL,
    buyrateinitblock INT DEFAULT 0 NOT NULL,
    buyrateincrement INT DEFAULT 0 NOT NULL,
    rateinitial float DEFAULT 0 NOT NULL,
    initblock INT DEFAULT 0 NOT NULL,
    billingblock INT DEFAULT 0 NOT NULL,
    connectcharge float DEFAULT 0 NOT NULL,
    disconnectcharge float DEFAULT 0 NOT NULL,
    stepchargea float DEFAULT 0 NOT NULL,
    chargea float DEFAULT 0 NOT NULL,
    timechargea INT DEFAULT 0 NOT NULL,
    billingblocka INT DEFAULT 0 NOT NULL,
    stepchargeb float DEFAULT 0 NOT NULL,
    chargeb float DEFAULT 0 NOT NULL,
    timechargeb INT DEFAULT 0 NOT NULL,
    billingblockb INT DEFAULT 0 NOT NULL,
    stepchargec float DEFAULT 0 NOT NULL,
    chargec float DEFAULT 0 NOT NULL,
    timechargec INT DEFAULT 0 NOT NULL,
    billingblockc INT DEFAULT 0 NOT NULL,
    startdate TIMESTAMP DEFAULT 'now()' NOT NULL,
    stopdate TIMESTAMP,
    starttime smallint(5) unsigned default '0',
    endtime smallint(5) unsigned default '10079',
    id_trunk INT DEFAULT -1,
    musiconhold CHAR(100) NOT NULL,
    PRIMARY KEY (id)
);
CREATE INDEX ind_cc_ratecard_dialprefix ON cc_ratecard (dialprefix);


CREATE TABLE cc_logrefill (
    id INT NOT NULL AUTO_INCREMENT,
    date TIMESTAMP DEFAULT 'now()' NOT NULL,
    credit float NOT NULL,
    card_id bigint NOT NULL,
    reseller_id bigint,
    PRIMARY KEY (id)
);


CREATE TABLE cc_logpayment (
    id INT NOT NULL AUTO_INCREMENT,
    date TIMESTAMP DEFAULT 'now()' NOT NULL,
    payment float NOT NULL,
    card_id bigint NOT NULL,
    reseller_id BIGINT,
    PRIMARY KEY (id)
);



CREATE TABLE cc_trunk (
    id_trunk INT NOT NULL AUTO_INCREMENT,
    trunkcode CHAR(20) NOT NULL,
    trunkprefix CHAR(20),
    providertech CHAR(20) NOT NULL,
    providerip CHAR(80) NOT NULL,
    removeprefix CHAR(20),
    secondusedreal INT DEFAULT 0,
    secondusedcarrier INT DEFAULT 0,
    secondusedratecard INT DEFAULT 0,
    creationdate TIMESTAMP DEFAULT 'now()' NOT NULL,
    failover_trunk INT,
    addparameter CHAR(120),
    id_provider INT, 
    PRIMARY KEY (id_trunk)
);



CREATE TABLE cc_sip_buddies (
    id INT NOT NULL AUTO_INCREMENT,
    name CHAR(80) DEFAULT '' NOT NULL,
    accountcode CHAR(20),
    regexten CHAR(20),
    amaflags CHAR(7),
    callgroup CHAR(10),
    callerid CHAR(80),
    canreinvite CHAR(3) DEFAULT 'yes',
    context CHAR(80),
    defaultip CHAR(15),
    dtmfmode CHAR(7)  DEFAULT 'RFC2833' NOT NULL,	 
    fromuser CHAR(80),
    fromdomain CHAR(80),
    host CHAR(31) DEFAULT '' NOT NULL,
    insecure CHAR(4),
    language CHAR(2),
    mailbox CHAR(50),
    md5secret CHAR(80),
    nat CHAR(3) DEFAULT 'yes',
    permit CHAR(95),
    deny CHAR(95),
    mask CHAR(95),
    pickupgroup CHAR(10),
    port CHAR(5) DEFAULT '' NOT NULL,
    qualify CHAR(3) DEFAULT 'yes',
    restrictcid CHAR(1),
    rtptimeout CHAR(3),
    rtpholdtimeout CHAR(3),
    secret CHAR(80),
    type CHAR(6) DEFAULT 'friend' NOT NULL,
    username CHAR(80) DEFAULT '' NOT NULL,
    disallow CHAR(100) DEFAULT 'all',
    allow CHAR(100) DEFAULT 'gsm,ulaw,alaw',
    musiconhold CHAR(100),
    regseconds INT DEFAULT 0 NOT NULL,
    ipaddr CHAR(15) DEFAULT '' NOT NULL,
    cancallforward CHAR(3) DEFAULT 'yes',
    PRIMARY KEY (id),
    UNIQUE cons_cc_sip_buddies_name (name)
);



CREATE TABLE cc_iax_buddies (
    id INT NOT NULL AUTO_INCREMENT,
    name CHAR(80) DEFAULT '' NOT NULL,
    accountcode CHAR(20),
    regexten CHAR(20),
    amaflags CHAR(7),
    callgroup CHAR(10),
    callerid CHAR(80),
    canreinvite CHAR(3) DEFAULT 'yes',
    context CHAR(80),
    defaultip CHAR(15),
    dtmfmode CHAR(7)  DEFAULT 'RFC2833' NOT NULL,	 
    fromuser CHAR(80),
    fromdomain CHAR(80),
    host CHAR(31) DEFAULT '' NOT NULL,
    insecure CHAR(4),
    language CHAR(2),
    mailbox CHAR(50),
    md5secret CHAR(80),
    nat CHAR(3) DEFAULT 'yes',
    permit CHAR(95),
    deny CHAR(95),
    mask CHAR(95),
    pickupgroup CHAR(10),
    port CHAR(5) DEFAULT '' NOT NULL,
    qualify CHAR(3) DEFAULT 'yes',
    restrictcid CHAR(1),
    rtptimeout CHAR(3),
    rtpholdtimeout CHAR(3),
    secret CHAR(80),
    type CHAR(6) DEFAULT 'friend' NOT NULL,
    username CHAR(80) DEFAULT '' NOT NULL,
    disallow CHAR(100) DEFAULT 'all',
    allow CHAR(100) DEFAULT 'gsm,ulaw,alaw',
    musiconhold CHAR(100),
    regseconds INT DEFAULT 0 NOT NULL,
    ipaddr CHAR(15) DEFAULT '' NOT NULL,
    cancallforward CHAR(3) DEFAULT 'yes',
    PRIMARY KEY (id),
    UNIQUE cons_cc_iax_buddies_name (name)
);


INSERT INTO cc_ui_authen VALUES (2, 'admin', 'mypassword', 0, 1023, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-02-26 21:14:05.391501-05');
INSERT INTO cc_ui_authen VALUES (1, 'root', 'myroot', 0, 1023, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2005-02-26 20:33:27.691314-05');

INSERT INTO cc_templatemail VALUES ('signup', 'billing@kikisys.com', 'test', 'SIGNUP CONFIRMATION', 'Thank you for registering with us

Please make sure you active your account by making payment to us either by
credit card, wire transfer, money order, cheque, and western union money
transfer, money Gram, and Pay pal.

Your account is <b>$card_gen</b>.

Your password is <b>$password</b>

Kind regards,
The Team
', '');
INSERT INTO cc_templatemail VALUES ('reminder', 'billing@kikisys.com', 'test', 'REMINDER', 'Our record indicates that you have less than $min_credit usd in your "$card_gen" Okeysystems account.

We hope this message provides you with enough notice to refill your account.
We value your business, but our system can disconnect you automatically
when you reach your pre-paid balance.

Please login to your account through our website to check your account
details. Plus,
you can pay by credit card, on demand.

http://www.kikisys.com/login.php

If you believe this information to be incorrect please contact
billing@kikisys.com
immediately.


Kind regards,
The Team', '');

INSERT INTO cc_trunk VALUES (1, 'default', '011', 'IAX2', 'kiki@switch-2.kiki.net', '', 0, 0, 0, '2005-03-14 01:01:36',0 ,0, NULL);


--
-- Country table : Store the iso country list
--

CREATE TABLE cc_country (
    id BIGINT NOT NULL AUTO_INCREMENT,
    countrycode CHAR(80) NOT NULL,
    countryname CHAR(80) NOT NULL,
    PRIMARY KEY (id)
);



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

--
-- Predictive Dialer update  database - Create database schema
--

--
-- CURRENT_TIMESTAMP
-- last_attempt TIMESTAMP DEFAULT 'now()' NOT NULL,  
-- last_attempt TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
--


CREATE TABLE cc_campaign (
    id INT NOT NULL AUTO_INCREMENT,    
    campaign_name CHAR(50) NOT NULL,
    creationdate TIMESTAMP DEFAULT 'now()' NOT NULL,
    startingdate TIMESTAMP DEFAULT 'now()' NOT NULL,  
    expirationdate TIMESTAMP,
    description MEDIUMTEXT,
    id_trunk INT DEFAULT 0,
    secondusedreal INT DEFAULT 0,
    nb_callmade INT DEFAULT 0,
    enable INT DEFAULT 0 NOT NULL,	
    PRIMARY KEY (id),
    UNIQUE cons_cc_campaign_campaign_name (campaign_name)
);


CREATE TABLE cc_phonelist (
    id INT NOT NULL AUTO_INCREMENT,
    id_cc_campaign INT DEFAULT 0 NOT NULL,
    id_cc_card INT DEFAULT 0 NOT NULL,
    numbertodial CHAR(50) NOT NULL,
    name CHAR(60) NOT NULL,
    inuse INT DEFAULT 0,	
    enable INT DEFAULT 1 NOT NULL,    
    num_trials_done INT DEFAULT 0,
    creationdate TIMESTAMP DEFAULT 'now()' NOT NULL,	
    last_attempt TIMESTAMP DEFAULT 'now()' NOT NULL,  
    secondusedreal INT DEFAULT 0,
    additionalinfo MEDIUMTEXT,
    PRIMARY KEY (id)
);
CREATE INDEX ind_cc_phonelist_numbertodial ON cc_phonelist (numbertodial);


CREATE TABLE cc_provider(
    id INT NOT NULL AUTO_INCREMENT,
    provider_name CHAR(30) NOT NULL,
    creationdate TIMESTAMP DEFAULT 'now()' NOT NULL,
    description MEDIUMTEXT,
    PRIMARY KEY (id),
    UNIQUE cons_cc_provider_provider_name (provider_name)
);

-- 
--  cc_currencies table
-- 

CREATE TABLE cc_currencies (
    id smallint(5) unsigned NOT NULL auto_increment,
    currency char(3) NOT NULL default '',
    name varchar(30) NOT NULL default '',
    value float(7,5) unsigned NOT NULL default '0.00000',
    lastupdate TIMESTAMP DEFAULT 'now()' NOT NULL,
    basecurrency char(3) NOT NULL default 'USD',
    PRIMARY KEY  (id),
    UNIQUE cons_cc_currencies_currency (currency)
)  AUTO_INCREMENT=150;

-- 
-- cc_currencies
-- 


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





--
-- Backup Database
--

CREATE TABLE cc_backup (
    id BIGINT NOT NULL AUTO_INCREMENT ,
    name VARCHAR( 255 ) NOT NULL ,
    path VARCHAR( 255 ) NOT NULL ,
    creationdate TIMESTAMP DEFAULT 'now()' NOT NULL ,
    PRIMARY KEY ( id ) ,
    UNIQUE cons_cc_backup_name(name)
) ;  




-- 
-- E-Commerce Table
--



CREATE TABLE cc_ecommerce_product (
    id BIGINT NOT NULL AUTO_INCREMENT,
    product_name VARCHAR(255) NOT NULL,	
    creationdate TIMESTAMP DEFAULT 'now()' NOT NULL,
    description MEDIUMTEXT,
    expirationdate TIMESTAMP,
    enableexpire INT DEFAULT 0,
    expiredays INT DEFAULT 0,
    mailtype VARCHAR(50) NOT NULL,
    credit float DEFAULT 0 NOT NULL,
    tariff INT DEFAULT 0,
    id_didgroup INT DEFAULT 0,
    activated CHAR(1) DEFAULT 'f' NOT NULL,
    simultaccess INT DEFAULT 0,
    currency CHAR(3) DEFAULT 'USD',
    typepaid INT DEFAULT 0,
    creditlimit INT DEFAULT 0,
    language CHAR(5) DEFAULT 'en',
    runservice INT DEFAULT 0,
    sip_friend INT DEFAULT 0,
    iax_friend INT DEFAULT 0,
    PRIMARY KEY ( id )
);




-- 
-- Speed Dial Table
--


CREATE TABLE cc_speeddial (
    id BIGINT NOT NULL AUTO_INCREMENT,
    id_cc_card BIGINT NOT NULL DEFAULT 0,
    phone VARCHAR(100) NOT NULL,	
    name VARCHAR(100) NOT NULL,	
    speeddial INT DEFAULT 0,
    creationdate TIMESTAMP DEFAULT 'now()' NOT NULL,
    PRIMARY KEY ( id ),
    UNIQUE cons_cc_speeddial_id_cc_card_speeddial (id_cc_card, speeddial)
);
