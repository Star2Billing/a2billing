#!/bin/bash

SCRIPT_NAME=$(basename "$0")
SCRIPT_DIR=$(cd $(dirname $0) ; pwd -P);

lock() {
    exec 200>/var/lock/$SCRIPT_NAME
    flock -n 200
}

if ! lock; then
    exit 1;
fi

# run parser and redirect output to NMS server
php $SCRIPT_DIR/cdr_format_mysql.php $@ | ssh cdranalyst@nms "cat >> /home/cdranalyst/cdrs/A$(date +%m%d%Y).txt"
