#!/bin/bash

if [ ${#@} == 0 ]; then
    echo -e "\n*** Now is possible to pass only wanted languages \n*** for installing as arguments to script. Example:"
	echo -e "\t$0 en es\n"
fi

# Identify Linux Distribution type
if [ -f /etc/debian_version ] ; then
    DIST='DEBIAN'
elif [ -f /etc/redhat-release ] ; then
    DIST='CENTOS'
else
    echo ""
    echo "This Installer should be run on a CentOS or a Debian based system"
    echo ""
    exit 1
fi



case $DIST in
    'DEBIAN')
		ast_sound=/usr/share/asterisk/sounds
	;;
    'CENTOS')
		ast_sound=/var/lib/asterisk/sounds
	;;
esac


#Install audiofiles

if ((0 < $#)) ; then
    LANGUAGES="$*"
else # for full retro compatibility
    LANGUAGES='en es fr br ru'
fi


for lang in $LANGUAGES
do
    if ! [ -d "$lang" ] ; then
        echo "*** The folder containing A2Billing Audio files for $lang language is missing." >&2
    else
        echo
        echo "Install A2Billing Audio files : $lang"
        echo ---------------------------------------------------
        echo "creating relevant folders : $ast_sound/$lang"
        echo "creating relevant folders : $ast_sound/$lang/digits"

        mkdir -p $ast_sound/"$lang"/digits
        echo "Copy '$lang' files in the right folder ..."

        /bin/cp -Rf $lang $ast_sound/ && \
        /bin/cp -f global/*_"$lang"* $ast_sound/$lang/ 
    fi
done
