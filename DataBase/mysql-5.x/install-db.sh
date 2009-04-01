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

# echo mysql --user=$username --password=$password --host=$hostname $dbname

cat a2billing-schema-v1.4.0.sql | mysql --user=$username --password=$password --host=$hostname $dbname

# cat a2billing-mysql-schema-v1.3.0-NDBCLUSTER.sql UPDATE-a2billing-v1.3.0-to-v1.3.1.sql UPDATE-a2billing-v1.3.3-to-v1.3.4.sql UPDATE-a2billing-v1.3.4-to-v1.4.0-NDBCLUSTER.sql a2billing-prefix-table-v1.4.0.sql | mysql --user=$username --password=$password --host=$hostname $dbname


# All done, exit ok
exit 0
