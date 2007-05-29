--
-- A2Billing database script - Create user & create a new database
--


/* Default values - Please change them to whatever you want

Database name is: mya2billing
Database user is: a2billinguser
User password is: a2billing


Usage:

mysql -u root -p"root password" < a2billing-MYSQL-createdb-user.sql 

*/


use mysql;

DELETE from user where User='a2billinguser';
DELETE from db where User='a2billinguser';

GRANT ALL PRIVILEGES ON mya2billing.* TO 'a2billinguser'@'%' IDENTIFIED BY 'a2billing' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON mya2billing.* TO 'a2billinguser'@'localhost' IDENTIFIED BY 'a2billing' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON mya2billing.* TO 'a2billinguser'@'localhost.localdomain' IDENTIFIED BY 'a2billing' WITH GRANT OPTION;

create DATABASE if not exists `mya2billing`;
