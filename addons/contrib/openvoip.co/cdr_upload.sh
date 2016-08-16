#!/bin/bash

SCRIPT_DIR=$( cd $(dirname $0) ; pwd -P );
cdr_log=/var/log/asterisk/cdr-csv/Master.csv
cdr_log_tmp=/var/log/asterisk/cdr-csv/Master.bak

[ ! -f $cdr_log -o "$(stat -c%s $cdr_log)" == "0" ] && {
    echo "No cdrs in $cdr_log"
    exit 1
}

mv -f $cdr_log $cdr_log_tmp || {
    echo "Cannot move data to tmp file"
    exit 1
}

truncate -s 0 $cdr_log

php $SCRIPT_DIR/cdr_format.php | ssh cdranalyst@nms "cat >> /home/cdranalyst/cdrs/cdr-$(date +%F)"
