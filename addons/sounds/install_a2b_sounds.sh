#!/bin/bash

ast_sound=/var/lib/asterisk/sounds


lang=en
echo 
echo Install A2Billing Audio files : "$lang"
echo ---------------------------------------------------
echo creating relevant folders : $ast_sound/$lang
echo creating relevant folders : $ast_sound/$lang/digits

mkdir $ast_sound/$lang
mkdir $ast_sound/$lang/digits
echo Copy $lang files in the right folder ...

cp ./$lang/* $ast_sound/$lang/
cp ./global/* $ast_sound/$lang/


lang=es
echo 
echo Install A2Billing Audio files : "$lang"
echo ---------------------------------------------------
echo creating relevant folders : $ast_sound/$lang
echo creating relevant folders : $ast_sound/$lang/digits

mkdir $ast_sound/$lang
mkdir $ast_sound/$lang/digits
echo Copy $lang files in the right folder ...

cp ./$lang/* $ast_sound/$lang/
cp ./global/* $ast_sound/$lang/


lang=fr
echo 
echo Install A2Billing Audio files : "$lang"
echo ---------------------------------------------------
echo creating relevant folders : $ast_sound/$lang
echo creating relevant folders : $ast_sound/$lang/digits

mkdir $ast_sound/$lang
mkdir $ast_sound/$lang/digits
echo Copy $lang files in the right folder ...

cp ./$lang/* $ast_sound/$lang/
cp ./global/* $ast_sound/$lang/



lang=br
echo 
echo Install A2Billing Audio files : "$lang"
echo ---------------------------------------------------
echo creating relevant folders : $ast_sound/$lang
echo creating relevant folders : $ast_sound/$lang/digits

mkdir $ast_sound/$lang
mkdir $ast_sound/$lang/digits
echo Copy $lang files in the right folder ...

cp ./$lang/* $ast_sound/$lang/
cp ./global/* $ast_sound/$lang/



lang=ru
echo 
echo Install A2Billing Audio files : "$lang"
echo ---------------------------------------------------
echo creating relevant folders : $ast_sound/$lang
echo creating relevant folders : $ast_sound/$lang/digits

mkdir $ast_sound/$lang
mkdir $ast_sound/$lang/digits
echo Copy $lang files in the right folder ...

cp ./$lang/* $ast_sound/$lang/
echo Copy $lang digits files in the right folder ...
cp ./$lang/digits/* $ast_sound/$lang/digits/



