


ALTER TABLE cc_sip_buddies ADD COLUMN fullcontact character varying(80);
 
ALTER TABLE cc_sip_buddies ADD COLUMN setvar character varying(80);
ALTER TABLE cc_sip_buddies ALTER COLUMN setvar SET DEFAULT ''::character varying;


-- Forget that one previously :-/
ALTER TABLE cc_card ADD COLUMN nbservice integer;
ALTER TABLE cc_card ALTER COLUMN nbservice SET DEFAULT 0;
UPDATE cc_card SET nbservice = '0';




ALTER TABLE cc_card ADD COLUMN vat numeric(6,3);
ALTER TABLE cc_card ALTER COLUMN vat SET DEFAULT 0;
UPDATE cc_card SET vat = '0';


ALTER TABLE cc_card ADD COLUMN servicelastrun timestamp without time zone;
