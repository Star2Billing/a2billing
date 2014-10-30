
===========================
INSTALL A2B CALLBACK DAEMON
===========================

To install A2B Callback Daemon, make sure you have Python 2.7 or greater installed.
Then follow the instructions bellow.

Dependencies::

    * python
    * python-mysqldb
    * python-psycopg2
    * python-sqlalchemy


1) INSTALL DAEMON : CENTOS / REDHAT
-----------------------------------

Install::

    cd CallBack/callback-daemon-py
    python setup.py install

Install Init Script::

    cp callback_daemon/a2b-callback-daemon.rc /etc/init.d/a2b-callback-daemon
    chmod +x /etc/init.d/a2b-callback-daemon

Add a service to start at boot::

    chkconfig --add a2b-callback-daemon
    chkconfig a2b-callback-daemon on

Start Daemon::

    service a2b-callback-daemon start


2) INSTALL DAEMON : DEBIAN
--------------------------

Install::

    cd CallBack/callback-daemon-py
    python setup.py install

Install Init script::

    cp callback_daemon/a2b-callback-daemon.debian /etc/init.d/a2b-callback-daemon
    chmod +x /etc/init.d/a2b-callback-daemon

    update-rc.d a2b-callback-daemon defaults 40 60
    * to remove update-rc.d -f a2b-callback-daemon remove

Start daemon::

    /etc/init.d/a2b-callback-daemon start


3) RUN DEBUG MODE
-----------------

Run Daemon on debug mode::

    cd CallBack/callback-daemon-py/callback_daemon
    ./a2b_callback_daemon.py --nodaemon


4) ADDITIONAL INFO
------------------

a) Rebuild the source::

    python setup.py build

b) Create a new egg::

    python setup.py bdist_egg

c) Kill Daemon::

    kill -9 `cat /var/run/a2b-callback-daemon.pid`
