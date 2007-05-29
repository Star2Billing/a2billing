--
-- A2Billing database - update database schema - v1.1.0 to update to v1.2.0
--

/* 

To create the database : 

mysql -u root -p"root password" < UPDATE-a2billing-v1.1.0-to-v1.2.0-mysql.sql

*/


 
 
 
ALTER TABLE cc_sip_buddies ADD COLUMN fullcontact varchar(80);
ALTER TABLE cc_sip_buddies ALTER COLUMN fullcontact SET default NULL;

 
ALTER TABLE cc_sip_buddies ADD COLUMN setvar varchar(80);
ALTER TABLE cc_sip_buddies ALTER COLUMN setvar SET default NULL;


ALTER TABLE cc_card ADD COLUMN nbservice INT;
ALTER TABLE cc_card ALTER COLUMN nbservice SET DEFAULT 0;
UPDATE cc_card SET nbservice = '0';


ALTER TABLE cc_card ADD COLUMN vat float;
ALTER TABLE cc_card ALTER COLUMN vat SET DEFAULT 0;
UPDATE cc_card SET vat = '0';


ALTER TABLE cc_card ADD COLUMN servicelastrun TIMESTAMP;
