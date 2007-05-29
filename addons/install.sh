#!/bin/sh

# Environment variables
PATH=.:/bin:/usr/bin:/usr/local/bin:/sbin:$PATH
export PATH

OPTION=0

# Get the path of this script
cwd=`dirname $0`


##############################
# Subroutines
##############################

# check distribution
check_os () {
	os="`uname -s`"
	arch="`uname -m`"

  cd_result () {
    echo "$1"
    exit
  }
	  
	case $os in
		Linux)
			cd_result LINUX
		;;	
		BSD)
			cd_result BSD
		;;	
		*)
			cd_result uknown
	esac
}	
	


show_menu(){
	echo "what do you want to install ? "
	echo  ""
	echo  "1-. A2Billing_UI"
	echo  "2-. A2BCustomer_UI"
	echo  "3-. A2Billing_AGI"
	echo  "4-. Exit"
	echo  -n "(1-4) : "
	read OPTION < /dev/tty
}	


install_A2Billing_UI(){
	clear
	echo " **** A2Billing_UI Intallation *** "
	echo "Enter target directory for A2Billing_UI ( i.ex : /var/www/html/a2biling)"
	read TARGET < /dev/tty
	
	if [ "$TARGET" = "" ]; then
		TARGET="/var/www/html/a2biling"
	fi
	
	mkdir -p $TARGET
	echo "coping files to $TARGET ..."
	cp -r A2Billing_UI/* $TARGET/.
	echo "[done]"

	echo "Please specify the apache user if it is not $APACHE_USER"
	
	read CUSTOM_APACHE_USER < /dev/tty

	if [ "$CUSTOM_APACHE_USER" != "" ]; then
		if [ "X$APACHE_USER" != "X$CUSTOM_APACHE_USER" ]; then
			APACHE_USER="$CUSTOM_APACHE_USER"
		fi	
	fi	
	
	echo "Setting permisions ... $APACHE_USER:$APACHE_USER to $TARGET"
	
	chown -R $APACHE_USER:$APACHE_USER $TARGET
	chmod -R 755 $TARGET

	echo "Please specify the directory of asterisk config files ( by default : /etc/asterisk )"
	read ASTERISK_ETC_TEMP < /dev/tty

	

	echo "[done] A2Billing_UI intalled" 
	echo ""
}


install_A2BCustomer_UI(){
	clear
	echo " **** A2BCustomer_UI Intallation *** "
}

install_A2Billing_AGI(){
	clear
	echo " **** A2Billing_AGI Intallation *** "
}



OS=`check_os`

echo $OS

ExitFinish=0

APACHE_USER=`ps axuw| grep apache | grep -v grep | tail -n1 | cut -d' ' -f1`

ASTERISK_ETC="/etc/asterisk"

while [ $ExitFinish -eq 0 ]; do

	# Show menu with Installation items
	show_menu

	case $OPTION in
		1) install_A2Billing_UI
		;;	
		2) install_A2BCustomer_UI
		;;
		3) install_A2Billing_AGI
		;;
		4)
		ExitFinish=1
		;;
		*)
	esac	
	
done
