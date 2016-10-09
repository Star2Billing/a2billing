#!/bin/bash

SCRIPT_DIR=$( cd $(dirname $0) ; pwd -P );

# run parser and redirect output to NMS server
php $SCRIPT_DIR/cdr_format_mysql.php $ARGS | ssh cdranalyst@nms "cat >> /home/cdranalyst/cdrs/A$(date +%m%d%Y).txt"
