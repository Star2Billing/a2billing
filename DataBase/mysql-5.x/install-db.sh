#!/bin/bash

echo ""
echo "Install A2Billing DataBase"
echo "-----------------------------"
echo ""

echo "Enter Database Name : "
read dbname

echo "Enter Hostname : "
read hostname

echo "Enter UserName : "
read username

echo "Enter Password : "
read password

echo mysql --user=$username --password=$password --host=$hostname $dbname

cat a2billing-schema-v1.4.0.sql UPDATE-a2billing-v1.4.0-to-v1.4.1.sql UPDATE-a2billing-v1.4.1-to-v1.4.2.sql UPDATE-a2billing-v1.4.2-to-v1.4.3.sql UPDATE-a2billing-v1.4.3-to-v1.4.4.sql UPDATE-a2billing-v1.4.4-to-v1.4.4.1.sql UPDATE-a2billing-v1.4.4.1-to-v1.4.5.sql UPDATE-a2billing-v1.4.5-to-v1.5.0.sql UPDATE-a2billing-v1.5.0-to-v1.5.1.sql UPDATE-a2billing-v1.5.1-to-v1.6.0.sql UPDATE-a2billing-v1.6.0-to-v1.6.1.sql UPDATE-a2billing-v1.6.1-to-v1.6.2.sql UPDATE-a2billing-v1.6.2-to-v1.7.0.sql UPDATE-a2billing-v1 .7.0-to-v1.7.1.sql| mysql --user=$username --password=$password --host=$hostname $dbname

# cat a2billing-mysql-schema-v1.3.0.sql UPDATE-a2billing-v1.3.0-to-v1.3.1.sql UPDATE-a2billing-v1.3.3-to-v1.3.4.sql UPDATE-a2billing-v1.3.4-to-v1.4.0.sql a2billing-prefix-table-josko-v1.4.0.sql UPDATE-a2billing-v1.4.0-to-v1.4.1.sql UPDATE-a2billing-v1.4.1-to-v1.4.2.sql UPDATE-a2billing-v1.4.2-to-v1.4.3.sql UPDATE-a2billing-v1.4.3-to-v1.4.4.sql UPDATE-a2billing-v1.4.4-to-v1.4.4.1.sql UPDATE-a2billing-v1.4.4.1-to-v1.4.5.sql UPDATE-a2billing-v1.4.5-to-v1.5.0.sql UPDATE-a2billing-v1.5.0-to-v1.5.1.sql UPDATE-a2billing-v1.5.1-to-v1.6.0.sql UPDATE-a2billing-v1.6.0-to-v1.6.1.sql UPDATE-a2billing-v1.6.1-to-v1.6.2.sql UPDATE-a2billing-v1.6.2-to-v1.7.0.sql UPDATE-a2billing-v1 .7.0-to-v1.7.1.sql| mysql --user=$username --password=$password --host=$hostname $dbname


# All done, exit ok
exit 0
