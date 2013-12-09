#!/bin/bash
#
# Startup script for A2Billing checker
#
# author: Roman Davydov <openvoip.co@gmail.com>
#
# chkconfig: 345 150 20
# description: A2Billing checker.

# Source function library.
. /etc/rc.d/init.d/functions

DAEMON=/path/to/checker.php
PIDFILE=/var/run/checker.php
PROG=checker
RETVAL=0

start() {
	echo -n $"Starting $PROG: "
	daemon --pidfile $PIDFILE php $DAEMON -p $PIDFILE
	RETVAL=$?
	echo
	return $RETVAL
}

stop() {
	echo -n $"Stopping $PROG: "
	killproc -p $PIDFILE
	RETVAL=$?
	echo
	return $RETVAL
}


# See how we were called.
case "$1" in
	start|debug)
		start
		;;
	stop)
		stop
		;;
	restart)
		stop
		start
		;;
	*)
		echo $"Usage: $PROG {start|stop|restart}"
		exit 1
esac

exit $RETVAL
