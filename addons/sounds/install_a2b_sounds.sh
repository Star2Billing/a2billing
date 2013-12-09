#!/bin/bash


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

lang=en
echo 
echo Install A2Billing Audio files : "$lang"
echo ---------------------------------------------------
echo creating relevant folders : $ast_sound/$lang
echo creating relevant folders : $ast_sound/$lang/digits

mkdir -p $ast_sound/$lang
mkdir -p $ast_sound/$lang/digits
echo Copy $lang files in the right folder ...

cp ./$lang/* $ast_sound/$lang/
cp ./global/* $ast_sound/$lang/


lang=es
echo 
echo Install A2Billing Audio files : "$lang"
echo ---------------------------------------------------
echo creating relevant folders : $ast_sound/$lang
echo creating relevant folders : $ast_sound/$lang/digits

mkdir -p $ast_sound/$lang
mkdir -p $ast_sound/$lang/digits
echo Copy $lang files in the right folder ...

cp ./$lang/* $ast_sound/$lang/
cp ./global/* $ast_sound/$lang/


lang=fr
echo 
echo Install A2Billing Audio files : "$lang"
echo ---------------------------------------------------
echo creating relevant folders : $ast_sound/$lang
echo creating relevant folders : $ast_sound/$lang/digits

mkdir -p $ast_sound/$lang
mkdir -p $ast_sound/$lang/digits
echo Copy $lang files in the right folder ...

cp ./$lang/* $ast_sound/$lang/
cp ./global/* $ast_sound/$lang/



lang=br
echo 
echo Install A2Billing Audio files : "$lang"
echo ---------------------------------------------------
echo creating relevant folders : $ast_sound/$lang
echo creating relevant folders : $ast_sound/$lang/digits

mkdir -p $ast_sound/$lang
mkdir -p $ast_sound/$lang/digits
echo Copy $lang files in the right folder ...

cp ./$lang/* $ast_sound/$lang/
cp ./global/* $ast_sound/$lang/



lang=ru
echo 
echo Install A2Billing Audio files : "$lang"
echo ---------------------------------------------------
echo creating relevant folders : $ast_sound/$lang
echo creating relevant folders : $ast_sound/$lang/digits

mkdir -p $ast_sound/$lang
mkdir -p $ast_sound/$lang/digits
echo Copy $lang files in the right folder ...

cp ./$lang/* $ast_sound/$lang/
echo Copy $lang digits files in the right folder ...
cp ./$lang/digits/* $ast_sound/$lang/digits/

