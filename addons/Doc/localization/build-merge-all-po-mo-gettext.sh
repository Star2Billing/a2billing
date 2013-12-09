#!/bin/bash
#
# AGPL (c) 2009 areski@gmail.com
# Asterisk2Billing : Autogenerate gettext & merge
#

# Get the path of this script
cwd=`pwd`

PO_DIRECTORY_CUSTOMER='common/cust_ui_locale'
PO_DIRECTORY_ADMIN='common/admin_ui_locale'
PO_DIRECTORY_AGENT='common/agent_ui_local'

DIR_CUSTOMER='customer'
DIR_ADMIN='admin'
DIR_AGENT='agent'


show_menu(){
	echo "which PO do you want to generate ? "
	echo  ""
	echo  "1-. Admin"
	echo  "2-. Customer"
	echo  "3-. Agent"
	echo  "4-. Exit"
	echo  -n "(1-4) : "
	read OPTION < /dev/tty
}

generate_admin_PO(){
	clear
	echo " **** Generate PO for Admin UI *** "
	
	DIR_CURRENT=$DIR_ADMIN
	PO_DIRECTORY_CURRENT=$PO_DIRECTORY_ADMIN
	
	cd $cwd
	cd $DIR_CURRENT
	
	rm -rf templates_c/*
    find -follow | grep -i [.][pit][hnp][pcl]$  | grep -v "agent.defines.php" | grep -v "agent.help.php" | grep -v "agent.module.access.php" | grep -v "customer.defines.php" | grep -v "customer.help.php" | grep -v "customer.module.access.php" | xargs xgettext -n --no-wrap
    
    cd $cwd
    
    generate_customer_PO_MO $DIR_CURRENT $PO_DIRECTORY_CURRENT
    
}

generate_agent_PO(){
	clear
	echo " **** Generate PO for Agent UI *** "
	
	DIR_CURRENT=$DIR_AGENT
	PO_DIRECTORY_CURRENT=$PO_DIRECTORY_AGENT
	
	cd $cwd
	cd $DIR_CURRENT
	
	rm -rf templates_c/*
    find -follow | grep -i [.][pit][hnp][pcl]$  | grep -v "admin.defines.php" | grep -v "admin.help.php" | grep -v "admin.module.access.php" | grep -v "customer.defines.php" | grep -v "customer.help.php" | grep -v "customer.module.access.php" | xargs xgettext -n --no-wrap
    
    cd $cwd
    
    generate_customer_PO_MO $DIR_CURRENT $PO_DIRECTORY_CURRENT
    
}

generate_customer_PO(){
	clear
	echo " **** Generate PO for Customer UI *** "
	
	DIR_CURRENT=$DIR_CUSTOMER
	PO_DIRECTORY_CURRENT=$PO_DIRECTORY_CUSTOMER
	
	cd $cwd
	cd $DIR_CURRENT
	
	rm -rf templates_c/*
    find -follow | grep -i [.][pit][hnp][pcl]$  | grep -v "admin.defines.php" | grep -v "admin.help.php" | grep -v "admin.module.access.php" | grep -v "agent.defines.php" | grep -v "agent.help.php" | grep -v "agent.module.access.php" | xargs xgettext -n --no-wrap
    
    cd $cwd
    
    generate_customer_PO_MO $DIR_CURRENT $PO_DIRECTORY_CURRENT
    
}

generate_customer_PO_MO(){
	
	DIR_CURRENT=$1
	PO_DIRECTORY_CURRENT=$2
	
	dirfile=`ls $PO_DIRECTORY_CURRENT`
	# dirfile='zh_TW'
	
    for d in $dirfile
	do
		echo "Proceed for the directory : $d"
		if [ ! -d $PO_DIRECTORY_CURRENT/$d ];
		then
			echo "Error : $PO_DIRECTORY_CURRENT/$d directory does not exits!"
			s=1
		fi
		# move old messages before merging
		mv $PO_DIRECTORY_CURRENT/$d/LC_MESSAGES/messages.po $PO_DIRECTORY_CURRENT/$d/LC_MESSAGES/old_messages.po
		cp $DIR_CURRENT/messages.po $PO_DIRECTORY_CURRENT/$d/LC_MESSAGES/messages.po
		
		# merging new PO with the old one
		msgmerge --no-wrap $PO_DIRECTORY_CURRENT/$d/LC_MESSAGES/old_messages.po $PO_DIRECTORY_CURRENT/$d/LC_MESSAGES/messages.po --output-file=$PO_DIRECTORY_CURRENT/$d/LC_MESSAGES/new.po
		# remove old and use the new
		rm $PO_DIRECTORY_CURRENT/$d/LC_MESSAGES/old_messages.po
		mv $PO_DIRECTORY_CURRENT/$d/LC_MESSAGES/new.po $PO_DIRECTORY_CURRENT/$d/LC_MESSAGES/messages.po
		
		# generate mo
		msgfmt $PO_DIRECTORY_CURRENT/$d/LC_MESSAGES/messages.po -o $PO_DIRECTORY_CURRENT/$d/LC_MESSAGES/messages.mo
		
	done
	echo ""

	rm $DIR_CURRENT/messages.po
	
}

clear
echo " **** Asterisk2Billing : Autogenerate gettext & merge ***"


ExitFinish=0

while [ $ExitFinish -eq 0 ]; do

	# Show menu with Installation items
	show_menu

	case $OPTION in
		1) generate_admin_PO
		;;
		2) generate_customer_PO
		;;
		3) generate_agent_PO
		;;
		4)
		ExitFinish=1
		;;
		*)
	esac	
	
done


exit 0


