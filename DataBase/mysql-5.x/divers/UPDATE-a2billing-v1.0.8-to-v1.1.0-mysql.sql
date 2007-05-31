--
-- A2Billing database - update database schema - v1.0.8 to update to v1.1.0
--

/* 

To create the database : 

mysql -u root -p"root password" < UPDATE-a2billing-v1.0.8-to-v1.1.0-mysql.sql

*/

 
--
--	MYSQL 5.x - MYSQL 5.x - MYSQL 5.x - MYSQL 5.x - MYSQL 5.x
--
--
-- Add Voip provider - update database - Create database schema 
--



CREATE TABLE cc_provider(
    id INT NOT NULL AUTO_INCREMENT,
    provider_name CHAR(30) NOT NULL,
    creationdate TIMESTAMP TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    description MEDIUMTEXT,
    PRIMARY KEY (id),
    UNIQUE cons_cc_provider_provider_name (provider_name)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;


ALTER TABLE cc_trunk ADD COLUMN id_provider INT;

---
--- Change model for ratecard time range
---

ALTER TABLE cc_ratecard   DROP COLUMN monday;
ALTER TABLE cc_ratecard   DROP COLUMN tuesday;
ALTER TABLE cc_ratecard   DROP COLUMN wednesday;
ALTER TABLE cc_ratecard   DROP COLUMN thursday;
ALTER TABLE cc_ratecard   DROP COLUMN friday;
ALTER TABLE cc_ratecard   DROP COLUMN saturday;
ALTER TABLE cc_ratecard   DROP COLUMN sunday;


ALTER TABLE cc_ratecard ADD starttime SMALLINT( 5 ) UNSIGNED DEFAULT '0' AFTER stopdate;
ALTER TABLE cc_ratecard ADD endtime SMALLINT( 5 ) UNSIGNED DEFAULT '10079' AFTER starttime ;

ALTER TABLE `cc_sip_buddies` CHANGE `dtmfmode` `dtmfmode` CHAR( 7 ) NOT NULL DEFAULT 'RFC2833';
ALTER TABLE `cc_iax_buddies` CHANGE `dtmfmode` `dtmfmode` CHAR( 7 ) NOT NULL DEFAULT 'RFC2833';

--
--  cc_currencies table
--

CREATE TABLE cc_currencies (
    id smallint(5) unsigned NOT NULL auto_increment,
    currency char(3) NOT NULL default '',
    name varchar(30) NOT NULL default '',
    value float(7,5) unsigned NOT NULL default '0.00000',
    lastupdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    basecurrency char(3) NOT NULL default 'USD',
    PRIMARY KEY  (id),
    UNIQUE cons_cc_currencies_currency(currency)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin AUTO_INCREMENT=150;


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





ALTER TABLE `cc_templatemail` ADD UNIQUE (mailtype);

--
-- Backup Database
--

CREATE TABLE cc_backup (
    id BIGINT NOT NULL AUTO_INCREMENT ,
    name VARCHAR( 255 ) NOT NULL ,
    path VARCHAR( 255 ) NOT NULL ,
    creationdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY ( id ) ,
    UNIQUE cons_cc_backup_name(name)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;





-- 
-- E-Commerce Table
--


CREATE TABLE cc_ecommerce_product (
    id BIGINT NOT NULL AUTO_INCREMENT,
    product_name VARCHAR(255) NOT NULL,	
    creationdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;

ALTER TABLE cc_ui_authen ADD UNIQUE cons_cc_ui_authen_login(login);




-- 
-- Speed Dial Table
--


CREATE TABLE cc_speeddial (
    id BIGINT NOT NULL AUTO_INCREMENT,
    id_cc_card BIGINT NOT NULL DEFAULT 0,
    phone VARCHAR(100) NOT NULL,	
    name VARCHAR(100) NOT NULL,	
    speeddial INT DEFAULT 0,
    creationdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY ( id ),
    UNIQUE cons_cc_speeddial_id_cc_card_speeddial (id_cc_card, speeddial)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;
