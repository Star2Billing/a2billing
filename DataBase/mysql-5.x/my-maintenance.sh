#!/bin/bash

echo ""
echo "Maintenance A2Billing DataBase"
echo "------------------------------"
echo ""

echo "Enter Database Name : "
read dbname

echo "Enter Hostname : "
read hostname

echo "Enter UserName : "
read username

echo "Enter Password : "
read password

NOW=$(date +"%m-%d-%Y")

echo "Dump a2billing.$NOW.dmp in process..."
mysqldump --user=$username --password=$password --host=$hostname --create-options --routines --triggers $dbname > a2billing.$NOW.dmp

echo "Tar.gz a2billing.$NOW.dmp in process..."
tar -pczf a2billing.$NOW.tar.gz a2billing.$NOW.dmp
rm a2billing.$NOW.dmp

echo "Database backup available at a2billing.$NOW.tar.gz"

echo "mysqlcheck --user=$username --password=$password --host=$hostname --check --databases $dbname"
mysqlcheck --user=$username --password=$password --host=$hostname --check --databases $dbname


echo "mysqlcheck --user=$username --password=$password --host=$hostname --optimize --databases $dbname"
mysqlcheck --user=$username --password=$password --host=$hostname --optimize --databases $dbname


echo "mysqlcheck --user=$username --password=$password --host=$hostname --analyze --databases $dbname"
mysqlcheck --user=$username --password=$password --host=$hostname --analyze --databases $dbname
