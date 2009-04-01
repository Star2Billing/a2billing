#!/bin/bash
# GPL (c) 2009 areski@gmail.com
# Asterisk2Billing : Autogenerate gettext & merge

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

generate_customer_PO(){
	clear
	echo " **** Generate PO for Customer UI *** "
	
	cd $cwd
	cd $DIR_CUSTOMER
	
	rm -rf templates_c/*
    find -follow | grep -i [.][pit][hnp][pcl]$  | grep -v "admin.*" | grep -v "agent.*" | xargs xgettext -n --no-wrap
    
    cd $cwd
    
    
    # 
	dirfile=`ls $PO_DIRECTORY_CUSTOMER`
	# dirfile='zh_TW'
	
    for d in $dirfile
	do
		echo "Proceed for the directory : $d"
		if [ ! -d $PO_DIRECTORY_CUSTOMER/$d ];
		then
			echo "Error : $PO_DIRECTORY_CUSTOMER/$d directory does not exits!"
			s=1
		fi
		# move old messages before merging
		mv $PO_DIRECTORY_CUSTOMER/$d/LC_MESSAGES/messages.po $PO_DIRECTORY_CUSTOMER/$d/LC_MESSAGES/old_messages.po
		cp $DIR_CUSTOMER/messages.po $PO_DIRECTORY_CUSTOMER/$d/LC_MESSAGES/messages.po
		
		# merging new PO with the old one
		msgmerge --no-wrap $PO_DIRECTORY_CUSTOMER/$d/LC_MESSAGES/old_messages.po $PO_DIRECTORY_CUSTOMER/$d/LC_MESSAGES/messages.po --output-file=$PO_DIRECTORY_CUSTOMER/$d/LC_MESSAGES/new.po
		# remove old and use the new
		rm $PO_DIRECTORY_CUSTOMER/$d/LC_MESSAGES/old_messages.po
		mv $PO_DIRECTORY_CUSTOMER/$d/LC_MESSAGES/new.po $PO_DIRECTORY_CUSTOMER/$d/LC_MESSAGES/messages.po
		
		# generate mo
		msgfmt $PO_DIRECTORY_CUSTOMER/$d/LC_MESSAGES/messages.po -o $PO_DIRECTORY_CUSTOMER/$d/LC_MESSAGES/messages.mo
		
	done
	echo ""

	rm $DIR_CUSTOMER/messages.po
    
}

clear
echo " **** Asterisk2Billing : Autogenerate gettext & merge ***"


ExitFinish=0

generate_customer_PO
while [ $ExitFinish -eq 0 ]; do

	# Show menu with Installation items
	show_menu

	case $OPTION in
		2) generate_customer_PO
		;;
		4)
		ExitFinish=1
		;;
		*)
	esac	
	
done

########################################################################
exit 0
