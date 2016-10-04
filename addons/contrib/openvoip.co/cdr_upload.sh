#!/bin/bash

SCRIPT_DIR=$( cd $(dirname $0) ; pwd -P );
cdr_log=/var/log/asterisk/cdr-csv/Master.csv

[ ! -f $cdr_log ] && {
    echo "No cdrs in $cdr_log"
    exit 1
}

[ "$(stat -c%s $cdr_log)" == "0" ] && {
    echo "Empty cdr file $cdr_log"
    exit 1
}

php $SCRIPT_DIR/cdr_format.php $cdr_log | ssh cdranalyst@nms "cat >> /home/cdranalyst/cdrs/A$(date +%m%d%Y)$1.txt"
