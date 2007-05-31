--
-- A2Billing database - update database schema - v1.2.0 to update to v1.2.2
--

/* 

To create the database : 

mysql -u root -p"root password" < UPDATE-a2billing-v1.2.0-to-v1.2.2-mysql.sql

*/


 
-- This is to solve a precision issue with Float

ALTER TABLE `cc_card` CHANGE `credit` `credit` DECIMAL( 15, 5 ) DEFAULT '0';
