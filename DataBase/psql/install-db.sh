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

[[ -e ~/.pgpass ]] || \
	( echo "$hostname:5432:$dbname:$username:$password" > ~/.pgpass && chmod 0600 ~/.pgpass )

psql -h $hostname -U $username -d $dbname -f a2billing-schema-v1.4.0.sql && \
psql -h $hostname -U $username -d $dbname -f UPDATE-a2billing-v1.4.0-to-v1.4.1.sql && \
psql -h $hostname -U $username -d $dbname -f a2billing-prefix-table-v1.4.0.sql && exit 0

## If we get this far,  the psql commands failed.
echo "Installation failed."
exit 1
