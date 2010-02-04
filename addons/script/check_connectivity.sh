#!/bin/bash

WGET="/usr/bin/wget"

$WGET -q --tries=10 --timeout=5 http://www.google.com -O /tmp/index.google &> /dev/null
if [ ! -s /tmp/index.google ];then
	echo "down"
else
	echo "good"
fi



