#!/usr/bin/env python
# vim: set expandtab shiftwidth=4:

'''
    database.py
    module to connect to Postgresql & Mysql Database and manipulate database information .

'''

__author__ = "Belaid Arezqui (areski@gmail.com)"
__copyright__ = "Copyright (C) Belaid Arezqui"

__revision__ = "$Id$"
__version__ = "1.00"



# ------------------------------ IMPORT ------------------------------  
import sys
INTP_VER = sys.version_info[:2]
if INTP_VER < (2, 2):
    raise RuntimeError("Python v.2.2 or later needed")

import ConfigParser

from sqlalchemy import *
from sqlalchemy import orm
from sqlalchemy.orm import sessionmaker
import datetime, time

# ------------------------------ CLASS ------------------------------  

class SQLError(Exception):
    ''' Error exception class '''

    def __init__(self, value):
        self.value = value

    def __str__(self):
        return repr(self.value)

class ConnectionError(Exception): pass

class SQlRow_Empty(Exception): pass


# Class for the ORM 

# These are the empty classes that will become our data classes
class CallBack_Spool(object):
    pass

class Server_Group(object):
    pass

class Server_Manager(object):
    pass



class callback_database:
    """Daemon base class"""
    config_filename = None
    section = 'database'   # override this
    
    "A class to handle all modification on DB"
    dbname = ''
    dbhost = ''
    dbport = None
    dbopt = ''
    dbtty = ''
    dbuser = ''
    dbpasswd = ''
    dbtype = ''
    
    count_server_manager = 0
    # TODO : create it with protected __ for better design
    
    
    def __init__(self):
        # cool to call a function to fetch the conf
        self.read_basic_config()
        self.db_connect()
    
    def read_basic_config(self):
        """Read basic options from the daemon config file"""
        cp = ConfigParser.ConfigParser()
        cp.read([self.config_filename])
        self.config_parser = cp
        
        self.dbname = cp.get(self.section, 'dbname')
        self.dbhost = cp.get(self.section, 'hostname')
        self.dbport = cp.get(self.section, 'port')
        self.dbuser = cp.get(self.section, 'user')
        self.dbpasswd = cp.get(self.section, 'password')
        self.dbtype = cp.get(self.section, 'dbtype')
        
    
    def status_on (self, status):
        if (status.lower()=='on') :
            return 'ACTIVE'
        else : 
            return 'INACTIVE'
        
    
    def db_connect (self):
        
        if (len(self.dbpasswd) > 0) :
            connection_string = self.dbtype + "://" + self.dbuser + ":" + self.dbpasswd + "@" + self.dbhost + "/" + self.dbname
        else :
            connection_string = self.dbtype + "://" + self.dbuser + "@" + self.dbhost + "/" + self.dbname
        
        try:
            self.engine = create_engine(connection_string)
            self.engine.echo = False  # Try changing this to True and see what happens
            
            self.metadata = MetaData(self.engine)
            
            Session = sessionmaker(bind=self.engine, autoflush=True)
            
            # create a Session
            self.session = Session()
            
            self.cc_callback_spool = Table('cc_callback_spool', self.metadata, autoload=True)
            self.cc_server_group = Table('cc_server_group', self.metadata, autoload=True)
            self.cc_server_manager = Table('cc_server_manager', self.metadata, autoload=True)
    
            # map to the class
            CallBack_Spool_mapper = orm.mapper(CallBack_Spool, self.cc_callback_spool)
            Server_Group_mapper = orm.mapper(Server_Group, self.cc_server_group)
            Server_Manager_mapper = orm.mapper(Server_Manager, self.cc_server_manager)
    
            self.CallBack_Spool_q = self.session.query(CallBack_Spool)
            self.Server_Manager_q = self.session.query(Server_Manager)
            
        except Exception, error_message:
            #print "connection error to " + connection_string
            raise ConnectionError(error_message)
        
    
    def db_close (self):
        try:
            self.session.flush()
        except Exception, error_message:
            raise SQLError(error_message)
    
    
    def count_callback_spool(self):
        return self.CallBack_Spool_q.filter((self.cc_callback_spool.c.status=='PENDING')).count()
    
    
    def find_server_manager(self, c_id_group):
        
        get_Server_Manager = self.Server_Manager_q.filter(
                                 (self.cc_server_manager.c.id_group==c_id_group)
                                 ).all()
        return get_Server_Manager
    
    
    def find_server_manager_roundrobin(self, c_id_group):
        
        nball_Server_Manager = self.Server_Manager_q.filter(
                                 (self.cc_server_manager.c.id_group==c_id_group)
                                 ).count()
        if (nball_Server_Manager == 0):
            raise SQlRow_Empty("No Server_Manager has been found for this idgroup : "+ str(c_id_group))
        
        nb_sel_Server_Manager = (self.count_server_manager % nball_Server_Manager) + 1
        selected_Server_Manager = self.Server_Manager_q.get(nb_sel_Server_Manager)
        self.count_server_manager = self.count_server_manager + 1
        
        return selected_Server_Manager
    
    
    def find_callback_request(self, c_status = 'PENDING', c_hours = 24):
        
        get_CallBack_Spool = self.CallBack_Spool_q.filter(
                                 (self.cc_callback_spool.c.status==c_status) &
                                 (self.cc_callback_spool.c.entry_time > datetime.datetime.now() - datetime.timedelta(hours=c_hours)) &
                                 ((self.cc_callback_spool.c.callback_time==None) | (self.cc_callback_spool.c.callback_time < datetime.datetime.now()))
                                 ).all()
        return get_CallBack_Spool
    
    
    def update_callback_request (self, c_id, c_status): 
        try:
            get_CallBack_Spool = self.CallBack_Spool_q.filter((self.cc_callback_spool.c.id == c_id)).one()
            get_CallBack_Spool.status = c_status
            self.session.flush()
        except:
            #print "--- nothing to update ---"
            pass
    
    
    def update_callback_request_server (self, c_id, c_status, c_id_server, c_manager_result):
        try:
            get_CallBack_Spool = self.CallBack_Spool_q.filter((self.cc_callback_spool.c.id == c_id)).one()
            get_CallBack_Spool.status = c_status
            get_CallBack_Spool.id_server = c_id_server
            get_CallBack_Spool.manager_result = c_manager_result
            get_CallBack_Spool.num_attempt += 1
            get_CallBack_Spool.last_attempt_time = func.now();
            self.session.flush()
        except:
            #print "--- nothing to update ---"
            pass


# ------------------------------ MAIN ------------------------------  

if __name__ == "__main__":
    
    """
    print "\n\n"
    inst_cb_db = callback_database()
    print inst_cb_db.count_callback_spool()
    
    print
    get_CallBack_Spool = inst_cb_db.find_callback_request('SENT', 121212)
    for p in get_CallBack_Spool[0:5]:
        print p.id,' ===========>>> >>> ',p.uniqueid, '>> ',p.status, '>> ',p.num_attempt, ' ::>> ',p.id_server, ' ::>> ',p.manager_result
    
    
    
    inst_cb_db.update_callback_request (5, 'SENT')
    inst_cb_db.update_callback_request (5, 'SENT')
    
    inst_cb_db.update_callback_request_server (5, 'SENT', 77, 'rhaaaaaaaa')
    
    print
    get_Server_Manager = inst_cb_db.find_server_manager(1)
    for p in get_Server_Manager[0:5]:
        print p.id,' ===========>>> >>> ',p.id_group, '>> ',p.server_ip, '>> ',p.manager_username
    
    
    try:
        get_Server_Manager = inst_cb_db.find_server_manager_roundrobin(11)
        print get_Server_Manager.id,' ===========>>> >>> ',get_Server_Manager.id_group, '>> ',get_Server_Manager.server_ip, '>> ',get_Server_Manager.manager_username
    except:
        print "--- no manager ---"
        pass
    """
    