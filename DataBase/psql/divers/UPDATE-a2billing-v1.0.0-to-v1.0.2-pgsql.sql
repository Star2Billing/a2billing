--
-- A2Billing database - update database schema - v1.0.0 to update to v1.0.2 
--

/* Default values - Please change them to whatever you want 
 
Database name is: mya2billing
Database user is: a2billinguser


USAGE :

su - postgres
psql -f UPDATE-a2billing-v1.0.0-to-v1.0.2-pgsql.sql template1
	
*/



 
ALTER TABLE ui_authen RENAME TO cc_ui_authen;
ALTER TABLE call RENAME TO cc_call;
ALTER TABLE templatemail RENAME TO cc_templatemail;
ALTER TABLE logrefill RENAME TO cc_logrefill;
ALTER TABLE logpayment RENAME TO cc_logpayment;
ALTER TABLE trunk RENAME TO cc_trunk;
