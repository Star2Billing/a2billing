<?php

/**
 * A2Billing checker.
 * 
 * Checks the following:
 * 1. "inuse" field data for trunks with real value from Asterisk realtime channels
 * 2. if "minutes_per_day" is reached by trunk - hangups channel
 * 3. if account type is "prepaid" and credit is <= 0 - hangups channel (works only for directly registered devices on A2B)
 * 
 * How to use:
 * 1. To run in foreground - execute "php checker.php -nf"
 * 2. To see help - execute "php checker.php -h"
 * 3. To run as daemon - use script "checker.init.d.sh" from the same folder,
 *    copy it to /etc/init.d/checker, give 755 rights and change the daemon path in the header, 
 *    then run as "/etc/init.d/checker start"
 * 4. IMPORTANT: each trunk is determined by providertech/providerip - keep it unique!
 * 
 * @author Roman Davydov <openvoip.co@gmail.com>
 * @license http://openvoip.co Free (just keep reference to my name and my site)
 * 
 */

require_once('phpagi-asmanager.php');
declare(ticks = 1);

function _asm_event_handler($ecode, $data, $server, $port) {
    if (is_object($checker = Checker::getInstance()))
        $checker->asmHandleEvent($ecode, $data, $server, $port);
}

class Checker {

    private static
        $instance = null,
        $daemon = false,
        $pidfile = '',
        $master_pid = 0,
        $logfile = 'checker.log',
        $action_prefix = 'checker_';
    
    private
        $childs = array(),
        $worker = null,
        $stop = false,
        $params = array(),
        $mysqli = null,
        $asm = null;
   
    public static function getInstance($argv = null) {
        if (!self::$instance) {
            self::$instance = new self($argv);
            
            // check args
            if (!is_array($argv) || count($argv) == 0)
                $argv = array(__FILE__);
            $script = basename($argv[0]);
        
            // show help
            if (in_array('-h', $argv)) {

                echo "Usage: $script [OPTIONS]
------------------------------------------------
OPTIONS:
-p   Enable PID file (only one running process)
-nf  No fork
-h   Show this help

";
                exit(0);
            }
            
            if (in_array('-nf', $argv)) {

                ini_set('error_reporting', E_ALL);
                
                self::$master_pid = getmypid();
                self::log('No fork mode', LOG_INFO);
                
            } else {
            
                $pid = pcntl_fork();
                if ($pid == -1) {
                    self::log('Could not fork!', LOG_ERR);
                    exit(1);
                } else if ($pid) {
                    // we are the parent
                    exit(0);
                } else {
                    // we are daemon
                    ini_set('error_log', '/var/log/' . self::$logfile);
                    ini_set('error_reporting', E_ERROR);

                    self::$daemon = true;
                    self::$master_pid = getmypid();
                    
                    // check pid
                    if (in_array('-p', $argv)) {
                        self::log("Checking PID");
                        self::$pidfile = "/var/run/$script";
                        if (is_file(self::$pidfile)) {
                            $pid = file_get_contents(self::$pidfile);
                            $prog = trim(shell_exec("ps --pid $pid -o comm="));
                            if (strlen($prog) > 0) {
                                self::log("Old instance is still running!", LOG_ERR);
                                self::$pidfile = null;
                                exit(10);
                            }
                        }
                        
                        // update pid file
                        $pid = self::$master_pid;
                        file_put_contents(self::$pidfile, $pid);
                    }
                }
            }
        }
        
        return self::$instance;
    }
    
    private function __construct() {
    
        // load settings
        $config = '/etc/a2billing.conf';
        if (file_exists($config))
            $this->params = parse_ini_file($config, true);
        
    }
    
    public function __destruct() {
        
        if (!$this->worker) { // master ending

            if (count($this->childs) > 0) {
            
                self::log("Destroying master...");
                foreach ($this->childs as $pid => $id) {
                    if (posix_kill($pid, SIGTERM)) {
                        $res = pcntl_waitpid($pid, $status, WNOHANG | WUNTRACED);
                        if ($res == -1) {
                            self::log("Something happened with worker, pid = $pid", LOG_ERR);
                        } else if (!pcntl_wifstopped($status)) {
                            self::log("Child is agile, killing with 9", LOG_INFO);
                            posix_kill($pid, 9);
                        }
                    } else {
                        self::log("Cannot kill worker, pid = $pid", LOG_ERR);
                    }
                }
                
            } // else - it is just daemon parent

            if (self::$daemon) {
                # removes PID if exists
                if (is_file(self::$pidfile)) {
                    self::log("Removing PID file");
                    unlink(self::$pidfile);
                }
            }
            
        } else {
        
            $pid = self::$master_pid;
            self::log("$pid Destroying worker '$this->worker' ...");
            
            // signalling to parent
            $res = posix_kill($pid, SIGCHLD);
            
        }
        
    }
    
    // signal handler function
    public function sig_handler($signo) {
        switch ($signo) {
            case SIGTERM:
            case SIGHUP:
                self::log("Term signal recieved, ending...");
                $this->stop = true;
                break;
            case SIGUSR1:
                break;
            case SIGCHLD:
                self::log("Child term signal recieved, ending...");
                $this->stop = true;
                break;
            default:
                // handle all other signals
        }
    }    
    
    private function createWorker($id = '') {
        // run another child for schedule managing
        $pid = pcntl_fork();
        if ($pid == -1) {
            self::log("Cannot create worker!", LOG_ERR);
            return false;
        } else if ($pid) {
            // we are the parent
            $this->childs[$pid] = $id;
        } else {
            // we are worker
            $this->worker = $id;
            $func = array($this, 'initWorker'.$id);
            if (is_callable($func)) {
                $result = call_user_func($func);
                if ($result) {
                    $func = array($this, 'runWorker'.$id);
                    if (is_callable($func)) {
                        // run worker task
                        self::log("Running worker '$id'");
                        exit(call_user_func($func));
                    }
                    self::log("Cannot run worker '$id' !", LOG_ERR);
                    exit(50);
                }
            }
            self::log("Cannot initiate worker '$id' !", LOG_ERR);
            exit(50);
        }
        return true;
    }
    
    private function initDb() {
        // connecting to DB
        if (strcasecmp($this->getParam('database.dbtype', ''), 'mysql') != 0) {
            self::log('Supports only mysql database', LOG_ERR);
            return false;
        }
        $host = $this->getParam('database.hostname', '127.0.0.1');
        $user = $this->getParam('database.user', 'root');
        $pass = $this->getParam('database.password', '');
        $db = $this->getParam('database.dbname', 'a2billing');
        $mysqli = new mysqli($host, $user, $pass, $db);
        if ($mysqli->connect_error) {
            self::log('Mysql connection error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error, LOG_ERR);
            return false;
        }
        $this->mysqli = $mysqli;
        $this->mysqli->set_charset("utf8");
        return true;
    }

    private function initAsm() {
        // connecting to Asterisk
        $this->asm = new AGI_AsteriskManager();
        $this->asm->pagi = &$this;
        if ($this->asm->connect(
            $this->getConfig('manager_host', '127.0.0.1'),
            $this->getConfig('manager_username', 'manager'),
            $this->getConfig('manager_secret', '')))  {

            $this->asm->Events('on');
            $this->asm->add_event_handler('*', '_asm_event_handler');
        } else {
            self::log('Asterisk connection error!', LOG_ERR);
            return false;
        }
        return true;
    }
    
    function conlog($str, $vbl = 1) {
        if (!self::$daemon)
            $this->log($str);
    }

    private function initWorkerEventHandler() {
        return $this->initDb() && $this->initAsm();
    }
    
    protected $channels = array();
    private function runWorkerEventHandler() {

        // life cycle
        while (!$this->stop) {
            if (!$this->pingResources())
                break;

            // send initial request
            $this->sendStatusRequest();
            
            // handle responses
            $this->asm->wait_response(true);
        }
        
    }
    
    private function asmEventEventHandler($ecode, $data, $server, $port) {
        
        if ($ecode == 'status') {
            
            $this->channels[] = $data;
            
        } else if ($ecode == 'statuscomplete') {
            
            // do check
            $this->doCheck();
            
            // sleep to prevent event flooding
            sleep(1);
            
            // send next request
            $this->sendStatusRequest();
            
        }
        
    }
    
    private function sendStatusRequest() {
        $data = $this->asm->send_request('Status', array('ActionID' => $this->generateId(time())));
    }

    private function doCheck() {
        if (count($this->channels) == 0)
            return;
            
        //print_r($this->channels);

        // list of channels to hangup
        $hangup = array();

        // prepare channels
        $channels = array();
        foreach ($this->channels as $channel) {            
            // check bridged channel
            if (isset($channel['BridgedChannel']) && preg_match("/^(.+\/.+)\-.*$/", $channel['BridgedChannel'], $matches)) {
                $channel_id = strtolower($matches[1]);
                if (!isset($channels[$channel_id])) {
                    $channels[$channel_id] = array(
                        'seconds' => 0,
                        'channels' => array()
                    );
                }
                $billsec = $this->asm->GetVar($channel['BridgedChannel'], "CDR(billsec)");
                $channels[$channel_id]['seconds'] += is_array($billsec) && isset($billsec['Value']) ? intval($billsec['Value']) : 0;
                $channels[$channel_id]['channels'][$channel['BridgedChannel']] = isset($channel['Accountcode']) ? $channel['Accountcode'] : '';
            }
        }
        
        //print_r($channels);
        
        // check trunks (minutes_per_day, inuse)
        $sql = "select * from cc_trunk";
        $trunks = $this->query($sql);
        foreach ($trunks as $t) {
            $id = $t['id_trunk'];
            $minutes_per_day = intval($t['minutes_per_day']);
            $inuse = intval($t['inuse']);
            $channel_id = strtolower($t['providertech'] . '/' . $t['providerip']);

            // check if trunk minutes limit exceeded
            if ($minutes_per_day > 0 && isset($channels[$channel_id])) {
                $realtime_seconds = $channels[$channel_id]['seconds'];
                $sql = "select * from cc_trunk_counter where id_trunk = '$id' and calldate = CURDATE() and ((seconds + $realtime_seconds / 60) >= $minutes_per_day) limit 1";
                $data = $this->query($sql);
                if (count($data) > 0)
                    $hangup = array_merge($hangup, array_keys($channels[$channel_id]['channels']));
            }

            // check inuse
            $inuse_real = isset($channels[$channel_id]) ? count($channels[$channel_id]['channels']) : 0;
            if ($inuse_real != $inuse) {
                self::log("Fixing 'inuse' for trunk with ID = $id", LOG_INFO);
                $sql = "update cc_trunk set inuse = $inuse_real where id_trunk = $id";
                $this->query($sql);
            }
        }

        // check account balances limit (works only for devices registered on A2B)
        $sql = "select * from cc_card where typepaid = 0 and credit <= 0";
        $data = $this->query($sql);
        foreach ($data as $card) {
            foreach ($channels as $channel_id => $info) {
                foreach ($info['channels'] as $channel => $accountcode) {
                    if (strcasecmp($accountcode, $card['username']) == 0)
                        $hangup[] = $channel;
                }
            }
        }

        // hangup channels
        foreach ($hangup as $channel) {
            // sending Hangup for the channel
            self::log("Hanging up channel '$channel'", LOG_INFO);
            $this->asm->Hangup($channel);
        }

        // reset channels list
        $this->channels = array();
    }
    
    public function run() {
        $result = 0;
        self::log('Starting...', LOG_INFO);

        // set signal handlers for ALL !
        pcntl_signal(SIGTERM, array($this, "sig_handler"));
        pcntl_signal(SIGHUP,  array($this, "sig_handler"));
        pcntl_signal(SIGUSR1, array($this, "sig_handler"));
        pcntl_signal(SIGCHLD, array($this, "sig_handler"));
        
        // create workers
        if ($this->createWorker('EventHandler')) {

            // waiting for stop
            while (!$this->stop)
                sleep(1);
                
        } else {
            $result = 15;
        }
        
        self::log('Exiting...', LOG_INFO);
        return $result;
    }
    
    private static function getEventData($event, $key, $default = '') {
        if (!is_array($event))
            return $default;
            
        $event = array_change_key_case($event);
        $key = strtolower($key);
        return isset($event[$key]) ? $event[$key] : $default;
    }
    
    private static function isId($actid) {
        $result = null;
        $prefix = self::$action_prefix;
        if (preg_match("/^$prefix(.+)$/i", $actid, $matches)) {
            $result = $matches[1];
        }
        return $result;
    }
    
    private static function generateId($actid) {
        return self::$action_prefix . $actid;
    }
    
    private function pingResources() {
        
        static $stamp_mysqli = 0;
        static $stamp_asm = 0;
        
        // step # 1 - ping mysql
        $stamp = time();
        $ping_interval = 3;
        if ($this->mysqli !== null && ($stamp - $stamp_mysqli) > $ping_interval) {
            $stamp_mysqli = $stamp;
            if (!$this->mysqli->ping()) {
                self::log("Mysql connection has been lost in '" . $this->worker . "', trying to reconnect...", LOG_ERR);
                // reconnect
                $connected = false;
                while (!$this->stop) {
                    $connected = $this->initDb();
                    if ($connected)
                        break;
                    sleep(1);
                }
                if (!$connected) {
                    self::log("Cannot establish Mysql connection in '" . $this->worker . "' !", LOG_ERR);
                    return false;
                } else {
                    self::log("Mysql connection reestablished in '" . $this->worker . "' !", LOG_INFO);
                }
            }
        }
        
        // step # 2 - ping Asterisk
        $stamp = time();
        $ping_interval = 7;
        if ($this->asm !== null && ($stamp - $stamp_asm) > $ping_interval) {
            $stamp_asm = $stamp;
            $result = $this->asm->Ping();
            if (is_array($result) && isset($result['Ping']) && $result['Ping'] == 'Pong') {
                // connection OK
            } else {
                self::log("AMI connection has been lost in '" . $this->worker . "', trying to reconnect...", LOG_ERR);
                // reconnect
                $connected = false;
                while (!$this->stop) {
                    $connected = $this->initAsm();
                    if ($connected)
                        break;
                    sleep(1);
                }
                if (!$connected) {
                    self::log("Cannot establish AMI connection in '" . $this->worker . "' !", LOG_ERR);
                    return false;
                } else {
                    self::log("AMI connection reestablished in '" . $this->worker . "' !", LOG_INFO);
                }
            }
        }
    
        return true;
    }
    
    public function asmHandleEvent($ecode, $data, $server, $port) {
        if (!$this->worker) 
            return;
        
        $func = array($this, 'asmEvent'.$this->worker);
        if (is_callable($func))
            call_user_func_array($func, array($ecode, $data, $server, $port));
    }
    
    private function getParam($key, $default = null) {
        $keys = explode('.', $key);
        if (!is_array($keys))
            $keys = array($key);
        
        // get param
        $value = $this->params;
        foreach ($keys as $key) {
            $value = isset($value[$key]) ? $value[$key] : $default;
        }
        
        return $value;
    }
    
    private function getConfig($key, $default = null, $group = 'global') {
        if (!$this->mysqli)
            return $default;
        
        $sql = "select * from cc_config where config_group_title = '$group' and config_key = '$key' limit 1";
        $data = $this->query($sql);
        
        return count($data) > 0 ? $data[0]['config_value'] : $default;
    }

    public static function log($msg, $level = LOG_INFO) {
        if (self::$daemon)
            syslog($level, $msg);
        else
            error_log(date('r') . ' - ' . $msg);
    }
    
    public function query($sql) {
        $data = array();
        if (is_object($result = $this->mysqli->query($sql))) {
            while ($row = $result->fetch_assoc())
                $data[] = $row;
            $result->free();
        }
        return $data;
    }
    
}

// run Process
$checker = Checker::getInstance($argv);
exit($checker->run());
