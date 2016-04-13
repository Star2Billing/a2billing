#!/usr/bin/env python
# vim: set expandtab shiftwidth=4:
'''

* This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2015 - Star2billing S.L.
 * @author      Belaid Arezqui <areski@gmail.com>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 * @package     A2Billing
 *
 * Software License Agreement (GNU Affero General Public License)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.

Daemon to proceed Call-Back request from the a2billing plaftorm

kill -9 `cat /var/run/a2b-callback-daemon.pid`
'''

import threading
import signal
import daemon
import database
import logging
import logging.config
import sys
import time
#import asterisk.manager
import manager


# Daemon Config File
CONFIG_FILE = '/etc/a2billing.conf'

# The next 2 parameters will define the speed of the daemon
# move this later in conf file

# amount of callback request to proceed at each loop
AMOUNT_TO_QUEUE = 10
# Amount of second the daemon will sleep after each check
DAEMON_CYCLE_TIME = 5

# Theard event for all shutdown
shutdown_all = threading.Event()


def handler(signum, frame):
    logging.debug('Signal handler called with signal %d', signum)
    logging.debug("At " + str(frame.f_code.co_name) + " in " +
                  str(frame.f_code.co_filename) + " line " + str(frame.f_lineno))
    shutdown_all.set()
    sys.exit()


def Init():
    """
        Catch signal
        Initialize logging
    """
    signal.signal(signal.SIGTERM, handler)
    signal.signal(signal.SIGHUP, handler)
    signal.signal(signal.SIGINT, handler)

    setup_logger()


def setup_logger():
    # load config for logger
    logging.config.fileConfig(CONFIG_FILE)
    #create logger
    logger = logging.getLogger("callbackLogger")

    # test logger
    logger.debug("debug message")
    logger.info("info message")
    logger.warn("warn message")
    logger.error("error message")
    logger.critical("critical message")


# Class For our callback Database
class CallBackDatabase(database.callback_database):
    config_filename = CONFIG_FILE
    section = 'database'


# Class For our callback Daemon
# Read conf and initiate Daemon behavior
class CallBackDaemon(daemon.Daemon):

    default_conf = CONFIG_FILE
    section = 'daemon-info'

    def run(self):

        Init()

        inst_cb_db = CallBackDatabase()
        run_action = CallBackAction(inst_cb_db)
        logging.info("------ Starting Callback Daemon ------ \n")

        while True:
            logging.info("waiting...")

            run_action.perform()

            # wait for few seconds before check if there's any call in PENDING
            time.sleep(DAEMON_CYCLE_TIME)


class CallBackAction(object):

    inst_cb_db = None
    inst_callback_manager = None
    num_placed = 0

    def __init__(self, inst_cb_db):
        self.inst_cb_db = inst_cb_db
        self.inst_callback_manager = callback_manager()

    def perform(self):
        request_list = self.inst_cb_db.find_callback_request('PENDING', 24)

        if (len(request_list) > 0):
            logging.info(request_list)

        for current_request in request_list:
            try:
                get_Server_Manager = self.inst_cb_db.find_server_manager_roundrobin(current_request.id_server_group)
                # print "%s;%s;%s;%s" % (get_Server_Manager.id, get_Server_Manager.id_group,
                #     get_Server_Manager.server_ip, get_Server_Manager.manager_username)
            except:
                logging.error("ERROR to find the Server Manager for the Id group : %s" %
                              str(current_request.id_server_group))
                self.inst_cb_db.update_callback_request(current_request.id, 'ERROR')
                continue

            # Connect to Manager
            logging.debug("We will try to connect to the manager : host=%s, username=%s" %
                          (get_Server_Manager.manager_host, get_Server_Manager.manager_username))
            try:
                self.inst_callback_manager.connect(
                    get_Server_Manager.manager_host,
                    get_Server_Manager.manager_username,
                    get_Server_Manager.manager_secret)
            except:
                # cannot connect to the manager
                logging.error("ERROR cannot connect to the manager")
                self.inst_cb_db.update_callback_request(current_request.id, 'ERROR')
                continue

            current_channel = current_request.channel

            # UPDATE Callback Request to "Perform Status"
            self.inst_cb_db.update_callback_request(current_request.id, 'PROCESSING')

            """
            id ; uniqueid ; entry_time ; status; server_ip ; num_attempt ; last_attempt_time ;
            manager_result ; agi_result ; callback_time ; channel ; exten
            context ; priority ; application ; data ; timeout ; callerid ; variable ; account ;
            async ; actionid ; id_server ;  id_server_group
            """
            self.num_placed = self.num_placed + 1

            # Initiate call
            logging.info("try_originate : " + current_request.channel + " : " +
                         current_request.exten + " : " + current_request.context)
            try:
                res_orig = self.inst_callback_manager.try_originate(
                    current_channel,
                    current_request.exten,
                    current_request.context,
                    current_request.priority,
                    current_request.timeout,
                    current_request.callerid,
                    False,
                    current_request.account,
                    current_request.application,
                    current_request.data,
                    current_request.variable)
            except:
                # cannot connect to the manager
                logging.error("Error connecting to the manager 2: %s" % reason)
                self.inst_cb_db.update_callback_request(current_request.id, 'ERROR')
                continue

            str_manager_res = str(res_orig)
            logging.info("CallBack Status : " + str_manager_res)

            if (str_manager_res.find('Success') == -1):
                # Callback Failed
                self.inst_cb_db.update_callback_request_server(
                    current_request.id,
                    'ERROR',
                    get_Server_Manager.id,
                    str_manager_res)
            else:
                # Callback Successful
                self.inst_cb_db.update_callback_request_server(
                    current_request.id,
                    'SENT',
                    get_Server_Manager.id,
                    str_manager_res)

            logging.info("[" + time.strftime("%Y/%m/%d %H:%M:%S", time.localtime()) + "] Placed " +
                         str(self.num_placed) + " calls")

            """
            self.inst_cb_db.update_callback_request(
                    current_request.id,
                    'PENDING')
            sys.exit()
            """


class callback_manager(object):
    _manager = None
    _manager_host = None
    _manager_login = None
    _manager_passw = None

    def connect(self, host, login, password):
        if (self._manager_host != host or
           self._manager_login != login or
           self._manager_passw != password or
           self._manager is None):
            # we have different manager parameter so let s connect
            if self._manager is not None:
                self.disconnect()
            self._manager_host = host
            self._manager_login = login
            self._manager_passw = password
            return self.try_connect()
        return True

    def try_connect(self):
        self._manager = manager.Manager()
        try:
            self._manager.connect(self._manager_host)
            self._manager.login(self._manager_login, self._manager_passw)

        except manager.ManagerSocketException, (errno, reason):
            logging.error("Error connecting to the manager: %s" % reason)
            #sys.exit(1)
            return False
        except manager.ManagerAuthException, reason:
            logging.error("Error connecting to the manager: %s" % reason)
            #sys.exit(1)
            return False
        except manager.ManagerException, reason:
            logging.error("Error connecting to the manager: %s" % reason)
            #sys.exit(1)
            return False

        return True

    def try_originate(self, channel=None, exten=None, context=None, priority=None, timeout=None,
                      caller_id=None, async=True, account=None, application=None, data=None,
                      variables=None, actionid=None):
        response = self._manager.originate(
            channel,
            exten,
            context,
            priority,
            timeout,
            caller_id,
            async,
            account,
            application,
            data,
            variables,
            actionid)
        return response

    def disconnect(self):
        self._manager.close()
        self._manager = None


def main():
    CallBackDaemon().main()


# ------------------------------ MAIN ------------------------------
if __name__ == '__main__':
    CallBackDaemon().main()

    #inst_cb_db = CallBackDatabase()
    #inst_cb_action = CallBackAction(inst_cb_db)
    #inst_cb_action.perform()

    logging.info("End of Script")

    sys.exit()
