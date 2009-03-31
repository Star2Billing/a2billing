
\set ON_ERROR_STOP ON;


-- integrate changes from ISO-3166-1 newsletters V-1 to V-12
UPDATE cc_country SET countryname='Lao People''s Democratic Republic' WHERE countrycode='LAO';
UPDATE cc_country SET countryname='Timor-Leste', countryprefix='670' WHERE countrycode='TLS';
UPDATE cc_country SET countryprefix='0' WHERE countrycode='TMP';
