

ALTER TABLE cc_callback_spool CHANGE variable variable VARCHAR( 300 ) DEFAULT NULL;

-- fix various uses of ISO-3166-1 alpha-2 rather than alpha-3
UPDATE cc_country SET countrycode='BVT' WHERE countrycode='BV';
UPDATE cc_country SET countrycode='IOT' WHERE countrycode='IO';
UPDATE cc_country SET countrycode='HMD' WHERE countrycode='HM';
UPDATE cc_country SET countrycode='PCN' WHERE countrycode='PN';
UPDATE cc_country SET countrycode='SGS' WHERE countrycode='GS';
UPDATE cc_country SET countrycode='SJM' WHERE countrycode='SJ';
UPDATE cc_country SET countrycode='TLS' WHERE countrycode='TL';
UPDATE cc_country SET countrycode='UMI' WHERE countrycode='UM';
UPDATE cc_country SET countrycode='ESH' WHERE countrycode='EH';

-- integrate changes from ISO-3166-1 newsletters V-1 to V-12
UPDATE cc_country SET countryname='Lao People''s Democratic Republic' WHERE countrycode='LAO';
UPDATE cc_country SET countryname='Timor-Leste', countryprefix='670' WHERE countrycode='TLS';
UPDATE cc_country SET countryprefix='0' WHERE countrycode='TMP';
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
