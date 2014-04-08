
============================
A2BILLING INSTALLATION GUIDE
============================


0. Sypnosis
-----------

    A2Billing is a voip billing software licensed under the AGPL 3.
    Copyright (C) 2004-2013 - Star2billing S.L. http://www.star2billing.com/

    This document focuses on the installation of A2Billing system for the Asterisk open source PBX. The document covers the installation and basic configuration of
    A2Billing. A2billing is an open source implementation of a telecommunication billing and added value services platform.

    A2billing is a LAMP (Linux Apache Mysql(Postgresql) PHP) application that interfaces with Asterisk using both the AMI and AGI interfaces.

    This documentation has been tested using Debian etch, Debian etch and half, Ubuntu 8.04 and Ubuntu 8.10 and A2Billing.


1. A2Billing installation guide
-------------------------------

1.1 Important note about distributions
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    This documentation assumes that you are using a .deb based distro that has used the folder /usr/share/asterisk during packaging. Other distributions use the alternate
    folder /var/lib/asterisk. If you compile from source the path by default is /var/lib/asterisk.

    The basic assumptions of this documentation is that used pre-packaged software and:
        * your apache2 default root folder is /var/www
        * your asterisk sounds are under /usr/share/asterisk
        * your asterisk AGI folder is expected under /usr/share/asterisk
        * your apache2 runs as www-data (uid)
        * you asterisk runs as asterisk (uid)
        * those using subversion to check out the code, can use symbolic links instead of copying the files to the right directories

1.2 Default passwords and access info
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    This are the default passwords that you should know about
        * mysql root password (in default system normally is <ENTER>)
        * A2Billing default database is mya2billing, user is a2billinguser and password is a2billing
        * asterisk manager default information is: [myasterisk] and secret=mycode
        * A2Billing admin default password is: user: root password: changepassword


1.3 Pre-required software packages
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    A2billing requires the packages of a LAMP (PHP5) installation. To install the necessary packages, run the following commands: ::

        apt-get install libapache2-mod-php5 php5 php5-common
        apt-get install php5-cli php5-mysql mysql-server apache2 php5-gd
        apt-get install openssh-server subversion

    A2Billing also requires MCrypt module for PHP5::

        apt-get install php5-mcrypt

    Asterisk is of course also needed. ::

        apt-get install asterisk

    1.3.1 Extra software to support text-to-speech IVR monitoring

        Version 1.7.1 includes a new AGI mode that allows the monitoring of the a2billing system via an IVR.

        The new monitoring feature requires text-to-speech TTS support, the default TTS engine is Cepstral
        http://www.cepstral.com/ although a2billing can support Festival too.

        Install Cepstral (default path: /opt/swift) and make a symbolic link:
        - ln -s /opt/swift/bin/swift /usr/bin/swift

        Make sure that the dynamic libraries are linked, create a file called cepstral.conf under /etc/ld.so.conf.d/ including the path : /opt/swift/lib

        Do not forget to register your voice!



2. Installation
~~~~~~~~~~~~~~~

    In a nutshell installing A2Billing requires a minimum of seven steps (1-7)

    1. Download and unpack source code
    2. Setup the database
    3. Edit a2billing.conf file. Setting up the database parameters
    4. Fix permissions and folders
    5. Installing the web based graphical user interfaces (Customer and Admin)
    6. Place the AGI files
    7. Prepare your dialplan
    8. Add your cronjobs (only for notifcations, alarms and recurring services)
    9. Configure your callback daemon (only for callback)
    10. Enable monitoring (only for IVR monitoring)
    11. Enable card locking (only for card PIN locking)

    2.1. Step 1: Download and unpack source code

        Create a a2billing folder under /usr/local/src::

            mkdir /usr/local/src/a2billing

        Unpack the code

        Download the code from the SVN repository run: ::

            svn co --username guest --password guest http://svn.a2billing.net/svn/asterisk2billing/tags/1-current /usr/local/src/a2billing/

        At the end of this step you should have a a2billing tree structure that should look like:

        /usr/local/src/a2billing/

        Files :
            - AGI
            - CHANGELOG
            - COPYING
            - CallBack
            - Cronjobs : Recurrent services run via crontab
            - DataBase : Database Schema / DB Installation
            - FEATURES_LIST
            - a2billing.conf : Main Configuration file
            - addons : Sounds and other addons
            - admin : Admin UI
            - agent : Agent UI
            - customer : Customer UI
            - webservice


    2.2. Step 2: Prepare the Database

        We will now create a MySQL database (mya2billing) for the billing software. The file a2billing-createdb-user.sql includes a script that creates the database with the correct access control users and permissions. ::

            cd /usr/local/src/a2billing
            mysql -u root -p < DataBase/mysql-5.x/a2billing-createdb-user.sql

        The script with create a database, username and password with the following default values
            - Database name is: mya2billing
            - Database user is: a2billinguser
            - User password is: a2billing

        After creating the database structure, we will create a set of tables and insert some initial basic configuration data::

            cd DataBase/mysql-5.x/
            ./install-db.sh

        **Checkpoint 1 :** Check that the database (my2billing) and that (97) tables have been created. ::

            mysql -u root -p mya2billing
            mysql>show tables
            mysql>exit


    2.3. Step 3: Edit the a2billing.conf configuration file

        The A2Billing configuration file (a2billing.conf) contains the basic information to connect to the a2billing database. Copy or make a symbolic link from
        /usr/local/src/a2billing/a2billing.conf to /etc/a2billing.conf

        a2billing.conf -> /usr/local/src/a2billing/a2billing.conf

        Option 1::

          cp /usr/local/src/a2billing/a2billing.conf /etc/

        Option 2::

          ln -s /usr/local/src/a2billing/a2billing.conf /etc/a2billing.conf

        Open the file with your favorite text editor (vi is used in this example). If you are new to Linux, we recommend you to use the text editor Gedit. ::

          vi /etc/a2billing.conf

        The only parameters that you need to change here is the database connection information, an example follows: ::

            [database]
            hostname = localhost
            port = 3306
            user = a2billinguser
            password = a2billing
            dbname = mya2billing
            dbtype = mysql


    2.4. Step 4: Fix permissions, files and folders

        In this step, we will tweak the file permissions of Asterisk to fit the A2Billing software. We will also create a number of additional files and folders that A2Billing
        needs, which does not come with the default installation.

        2.4.1. SIP and IAX

            First we will set a few file permissions (chmod, chown) and create (touch) the SIP and IAX configuration files for Asterisk.::

                chmod 777 /etc/asterisk
                touch /etc/asterisk/additional_a2billing_iax.conf
                touch /etc/asterisk/additional_a2billing_sip.conf
                echo \#include additional_a2billing_sip.conf >> /etc/asterisk/sip.conf
                echo \#include additional_a2billing_iax.conf >> /etc/asterisk/iax.conf
                chown -Rf www-data /etc/asterisk/additional_a2billing_iax.conf
                chown -Rf www-data /etc/asterisk/additional_a2billing_sip.conf

        2.4.2. Sound files

            Run the sounds installation script available in the addons folder (IMPORTANT: the script assumes that asterisk sounds are under /usr/share/asterisk/sounds/)::

                /usr/local/src/a2billing/addons/install_a2b_sounds_deb.sh
                chown -R asterisk:asterisk /usr/share/asterisk/sounds/

        2.4.3. Configure Asterisk Manager

            Configure the Asterisk Manager by editing the manager.conf file. ::

              vi /etc/asterisk/manager.conf

            Notice that we are using the default values (myasterisk, mycode) in this section. The configuration should look like this::

                [general]
                enabled = yes
                port = 5038
                bindaddr = 0.0.0.0

                [myasterisk]
                secret=mycode
                read=system,call,log,verbose,command,agent,user
                write=system,call,log,verbose,command,agent,user

    2.5. Step 6: Install The AGI components

        Copy or create a symbolic link of the entire content of the AGI directory into asterisk agi-bin directory. ::

            mkdir /usr/share/asterisk/agi-bin
            chown asterisk:asterisk /usr/share/asterisk/agi-bin

        Option 1::

            cd /usr/local/src/a2billing/AGI
            cp a2billing.php /usr/share/asterisk/agi-bin/
            cp a2billing-monitoring.php /usr/share/asterisk/agi-bin/
            cp -Rf ../common/lib /usr/share/asterisk/agi-bin/

        Option 2::

            ln -s /usr/local/src/a2billing/AGI/a2billing.php /usr/share/asterisk/agi-bin/a2billing.php
            ln -s /usr/local/src/a2billing/AGI/lib /usr/share/asterisk/agi-bin/lib

        Make sure the scripts are executable::

            chmod +x /usr/share/asterisk/agi-bin/a2billing.php

        (if you are going to run the monitoring AGI script)::

            chmod +x /usr/share/asterisk/agi-bin/a2billing_monitoring.php


    2.6. Step 5: Install web-based Graphical interfaces

        In this step, we will install the three graphical interfaces of A2Billing: the Administration (admin), Agent (agent) and Customer (customer) interface. As in previous
        steps you can copy the folders of make symbolic links.

        Place the directories "admin" and "customer" into your webserver document root.

        Create a2billing folder in your web root folder::

            mkdir /var/www/a2billing
            chown www-data:www-data /var/www/a2billing

        Create folder directory for monitoring Scripts::

            mkdir -p /var/lib/a2billing/script

        Create folder directory for Cronts PID::

            mkdir -p /var/run/a2billing

        Option 1::

            cp -rf /usr/local/src/a2billing/admin /var/www/a2billing
            cp -rf /usr/local/src/a2billing/agent /var/www/a2billing
            cp -rf /usr/local/src/a2billing/customer /var/www/a2billing
            cp -rf /usr/local/src/a2billing/common /var/www/a2billing

        Option 2::

            ln -s /usr/local/src/a2billing/admin /var/www/a2billing/admin
            ln -s /usr/local/src/a2billing/agent /var/www/a2billing/agent
            ln -s /usr/local/src/a2billing/customer /var/www/a2billing/customer
            ln -s /usr/local/src/a2billing/common /var/www/a2billing/common

        Fix the permissions of the templates_c folder in each of the UI::

            chmod 755 /usr/local/src/a2billing/admin/templates_c
            chmod 755 /usr/local/src/a2billing/customer/templates_c
            chmod 755 /usr/local/src/a2billing/agent/templates_c
            chown -Rf www-data:www-data /usr/local/src/a2billing/admin/templates_c
            chown -Rf www-data:www-data /usr/local/src/a2billing/customer/templates_c
            chown -Rf www-data:www-data /usr/local/src/a2billing/agent/templates_c


        Checkpoint 2: Direct a browser to the administrative web interface (http://<ip-addr>/a2billing/admin) and login as administrator. Default passwords are:
            - user: root
            - pass: changepassword


    2.7. Step 7: Create a dialplan for A2Billing

        The extensions.conf is the Asterisk dialplan. Calls that interact with the billing software need to be handled inside of one or many A2Billing related contexts.

        The calls that reach the context are processed using the a2billing.php AGI script. The a2billing.php script can be invoked in many different modes (standard, did,voucher, callback, etc). In the example, we create two different contexts, the first context [a2billing] handles all the calls from our VoIP clients. When a call arrives, any extension number _X. (2 digits or more) reaches the script a2billing.php

        The second context [did], will be used to route inward calls back to the users. Calls to the clients (DID) are handled inside of the [did] context. The script a2billing.php in did mode is responsible of routing the call back to one of our users.

        Edit extension.conf::

            vi /etc/asterisk/extensions.conf

        and the following contexts::

         [a2billing]
         include => a2billing_callingcard
         include => a2billing_monitoring
         include => a2billing_voucher

         [a2billing_callingcard]
         ; CallingCard application
         exten => _X.,1,NoOp(A2Billing Start)
         exten => _X.,n,DeadAgi(a2billing.php|1)
         exten => _X.,n,Hangup

         [a2billing_voucher]
         exten => _X.,1,Answer(1)
         exten => _X.,n,DeadAgi(a2billing.php|1|voucher)
         ;exten => _X.,n,AGI(a2billing.php|1|voucher44) ; will add 44 in front of the callerID for the CID authentication
         exten => _X.,n,Hangup

         [a2billing_did]
         exten => _X.,1,DeadAgi(a2billing.php|1|did)
         exten => _X.,2,Hangup

        Note that newer versions of Asterisk use a comma (,) instead of a pipe (|) to separate the AGI arguments.


    2.8. Step 8: Configure recurring services

        Recurring services are handled via the /etc/crontab

        You can add the following cron jobs to your /etc/crontab or create a file with the jobs in /var/spool/cron/a2billing

            -  update the currency table::

                0 6 * * * php /usr/local/src/a2billing/Cronjobs/currencies_update_yahoo.php

            -  manage the monthly services subscription::

                0 6 1 * * php /usr/local/src/a2billing/Cronjobs/a2billing_subscription_fee.php

            -  To check account of each Users and send an email if the balance is less than the user have choice::

                0 * * * * php /usr/local/src/a2billing/Cronjobs/a2billing_notify_account.php

            -  this script will browse all the DID that are reserve and check if the customer need to pay for it bill them or warn them per email to know if they want to pay in order to keep their DIDs::

                0 2 * * * php /usr/local/src/a2billing/Cronjobs/a2billing_bill_diduse.php

            -  This script will take care of the recurring service. ::

                0 12 * * * php /usr/local/src/a2billing/Cronjobs/a2billing_batch_process.php

            - Generate Invoices at 6am everyday::

                0 6 * * * php /usr/local/src/a2billing/Cronjobs/a2billing_batch_billing.php

            -  to proceed the autodialer::

                * / 5 * * * * php /usr/local/src/a2billing/Cronjobs/a2billing_batch_autodialer.php

            -  manage alarms::

                0 * * * * php /usr/local/src/a2billing/Cronjobs/a2billing_alarm.php


    2.9. Step 9: Call back daemon (only for Call backs)

        The call back daemon is responsible of reading from the database the pool of calls stored for call back and trigger those calls periodically.

        The daemon is written in Python. Install the python-setuptools and use easy_install to install the callback_daemon::

            apt-get install python-setuptools python-mysqldb python-psycopg2 python-sqlalchemy
            cd /usr/local/src/a2billing/CallBack
            easy_install callback-daemon-py/dist/callback_daemon-1.0.prod_r1527-py2.5.egg

        Install the init.d startup script::

            cd /usr/local/src/a2billing/CallBack/callback-daemon-py/callback_daemon/

        For Debian::

            cp a2b-callback-daemon.debian  /etc/init.d/a2b-callback-daemon

        For RedHat::

            cp a2b-callback-daemon.rc /etc/init.d/a2b-callback-daemon
            chmod +x /etc/init.d/a2b-callback-daemon

        Make sure the daemon starts
            For Debian::

                update-rc.d a2b-callback-daemon defaults 40 60

            If you need to remove the daemon in the future run::

                  update-rc.d -f a2b-callback-daemon remove

            For RedHat::

                chkconfig --add a2b-callback-daemon
                service a2b-callback-daemon start
                chkconfig a2b-callback-daemon on


    2.10. Step 10: Enable Monitoring

        General system monitoring via IVR is available from version 1.7, the new AGI
        a2billing_monitoring.php provides access to an IVR where monitoring tasks can be
        configured via the new Monitoring Menu under Maintenance.

        SQL queries can be performed and shell scripts can be invoked.
        Place your scripts under /var/lib/a2billing/script/

    2.11. Step 11: Security features via IVR (Monitor account and locking calling card)

        Two new IVR menus are now available via the main a2billing.php AGI. The menus
        needs to be enabled setting the variables in the agi-conf menu (GUI system settings)

        Locking Options IVR menu
        ivr_enable_locking_option = true (default: false)


        Monitoring your Calling Card IVR menu
        ivr_enable_account_information = true (default: false)


3. Support
----------

    Star2Billing S.L. offers consultancy including installation, training and customisation

    Please email us at sales@star2billing.com for more information
