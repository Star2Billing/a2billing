#
# Minimalist Install on Debian 7.4
#
#
# This scripts only install the A2Billing Web UI and Asterisk
# It doesn't include realtime, callback, security
#
# WARNING: Don't use this script for production deployment, this is intended for development purpose
#

apt-get update
apt-get -y upgrade

apt-get -y install asterisk
apt-get -y install mysql-client mysql-server apache2 libapache2-mod-php5 php5-mysql vim-tiny
apt-get -y install php-pear php-db php5-gd php5-curl php-soap

mkdir -p /usr/share/a2billing/latest/
cd /usr/share/a2billing/
wget -O master.tar.gz --no-check-certificate https://codeload.github.com/Star2Billing/a2billing/tar.gz/master
tar zxf master.tar.gz
mv a2billing-master/* /usr/share/a2billing/latest/
rm -rf a2billing-master master.tar.gz

chmod u+xwr /usr/share/a2billing/latest/admin/templates_c
chmod a+w /usr/share/a2billing/latest/admin/templates_c
chmod u+xwr /usr/share/a2billing/latest/agent/templates_c
chmod a+w /usr/share/a2billing/latest/agent/templates_c
chmod u+xwr /usr/share/a2billing/latest/customer/templates_c
chmod a+w /usr/share/a2billing/latest/customer/templates_c

rm -rf /usr/share/a2billing/latest/admin/templates_c/*
rm -rf /usr/share/a2billing/latest/agent/templates_c/*
rm -rf /usr/share/a2billing/latest/customer/templates_c/*

# copy conf files
cp /usr/share/a2billing/latest//a2billing.conf /etc/a2billing.conf

cd /etc/apache2/sites-enabled/
wget https://raw.github.com/Star2Billing/a2billing/develop/addons/apache2/a2billing_admin.conf
wget https://raw.github.com/Star2Billing/a2billing/develop/addons/apache2/a2billing_customer.conf

ln -s /usr/share/a2billing/latest/AGI/a2billing.php /usr/share/asterisk/agi-bin/a2billing.php
chown asterisk:asterisk /usr/share/asterisk/agi-bin/a2billing.php
chmod +x /usr/share/asterisk/agi-bin/a2billing.php

# Install Audio files
cd /usr/share/a2billing/latest/addons/sounds
./install_a2b_sounds.sh
#set ownership on sounds
chown -R asterisk:asterisk /usr/share/asterisk/

cd /etc/asterisk
wget -O extensions_a2billing.conf https://raw.github.com/Star2Billing/a2billing/develop/addons/asterisk-conf/extensions_a2billing_1_8.conf

#include "extensions_a2billing.conf"
echo "Adding A2Billing extensions to /etc/asterisk/extensions.conf"
echo '
; include a2billing extension
#include extensions_a2billing.conf
' >> /etc/asterisk/extensions.conf

#Install A2billing DB
/etc/init.d/mysql start
mysql -uroot -ppassword -e "CREATE DATABASE a2billing_db;"
cd /usr/share/a2billing/latest/DataBase/mysql-5.x
bash install-db.sh

sed -i "s/a2billing_dbuser/root/g" /etc/a2billing.conf
sed -i "s/a2billing_dbpassword/password/g" /etc/a2billing.conf
sed -i "s/a2billing_dbname/a2billing_db/g" /etc/a2billing.conf

/etc/init.d/mysql restart
/etc/init.d/apache2 restart
