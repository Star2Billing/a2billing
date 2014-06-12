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

cat a2billing-schema-v1.4.0.sql UPDATE-a2billing-v1.4.0-to-v1.4.1.sql UPDATE-a2billing-v1.4.1-to-v1.4.2.sql UPDATE-a2billing-v1.4.2-to-v1.4.3.sql UPDATE-a2billing-v1.4.3-to-v1.4.4.sql UPDATE-a2billing-v1.4.4-to-v1.4.4.1.sql UPDATE-a2billing-v1.4.4.1-to-v1.4.5.sql UPDATE-a2billing-v1.4.5-to-v1.5.0.sql UPDATE-a2billing-v1.5.0-to-v1.5.1.sql UPDATE-a2billing-v1.5.1-to-v1.6.0.sql UPDATE-a2billing-v1.6.0-to-v1.6.1.sql UPDATE-a2billing-v1.6.1-to-v1.6.2.sql UPDATE-a2billing-v1.6.2-to-v1.7.0.sql UPDATE-a2billing-v1.7.0-to-v1.7.1.sql UPDATE-a2billing-v1.7.1-to-v1.7.2.sql UPDATE-a2billing-v1.7.2-to-v1.8.0.sql UPDATE-a2billing-v1.8.0-to-v1.8.1.sql UPDATE-a2billing-v1.8.1-to-v1.8.2.sql UPDATE-a2billing-v1.8.2-to-v1.8.3.sql UPDATE-a2billing-v1.8.3-to-v1.8.4.sql UPDATE-a2billing-v1.8.4-to-v1.8.5.sql UPDATE-a2billing-v1.8.5-to-v1.8.6.sql UPDATE-a2billing-v1.8.6-to-v1.9.0.sql UPDATE-a2billing-v1.9.0-to-v1.9.1.sql UPDATE-a2billing-v1.9.1-to-v1.9.2.sql UPDATE-a2billing-v1.9.2-to-v1.9.3.sql UPDATE-a2billing-v1.9.3-to-v1.9.4.sql UPDATE-a2billing-v1.9.4-to-v1.9.5.sql UPDATE-a2billing-v1.9.5-to-v2.0.sql UPDATE-a2billing-v2.0-to-v2.0.3.sql UPDATE-a2billing-v2.0.3-to-v2.0.4.sql UPDATE-a2billing-v2.0.4-to-v2.0.5.sql UPDATE-a2billing-v2.0.5-to-v2.0.6.sql UPDATE-a2billing-v2.0.6-to-v2.0.7.sql UPDATE-a2billing-v2.0.7-to-v2.0.8.sql UPDATE-a2billing-v2.0.9-to-v2.0.10.sql UPDATE-a2billing-v2.0.10-to-v2.0.11.sql UPDATE-a2billing-v2.0.11-to-v2.0.12.sql UPDATE-a2billing-v2.0.12-to-v2.0.13.sql | mysql --user=$username --password=$password --host=$hostname $dbname

# All done, exit ok
exit 0
