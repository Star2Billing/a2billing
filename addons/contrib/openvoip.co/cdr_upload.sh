#!/bin/bash

SCRIPT_DIR=$( cd $(dirname $0) ; pwd -P );
SUFFIX=""
ARGS="$@"

# get cmd options
eval set -- `getopt -o s: --quiet -- "$@"`
while true; do
    case $1 in
        -s)
            SUFFIX="$2"
            shift 2 ;;
        --)
            shift ;;
        *)
            break ;;
    esac
done

# run parser and redirect output to NMS server
php $SCRIPT_DIR/cdr_format_mysql.php $ARGS | ssh cdranalyst@nms "cat >> /home/cdranalyst/cdrs/A$(date +%m%d%Y)$SUFFIX.txt"
