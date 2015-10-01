<?php

interface IApi {
    public function init($api);
}

class Api {
    private
        $config = null,
        $dbh = null,
        $api = array(),
        $format = array();
    
    public function __construct() {
        if (!$this->initConfig())
            throw new RuntimeException('Cannot load config');
            
        if (!$this->initDb())
            throw new RuntimeException('Cannot connect to db');
        
        if (!$this->initApi())
            throw new RuntimeException('Cannot init APIs');
    }
    
    public function getConfig($key, $default = null) {
        $keys = explode('.', $key);
        if (!is_array($keys))
            $keys = array($key);
        
        // get param
        $value = $this->config;
        foreach ($keys as $key) {
            $value = (is_array($value) && isset($value[$key])) ? $value[$key] : $default;
        }
        
        return $value;
    }
    
    private function initConfig() {
        $config = '/etc/a2billing.conf';
        if (!file_exists($config))
            return false;
        $this->config = parse_ini_file($config, true);
        return is_array($this->config);
    }
    
    private function initDb() {
        // connecting to DB
        if (strcasecmp($this->getConfig('database.dbtype', ''), 'mysql') != 0) {
            self::log('Supports only mysql database', LOG_ERR);
            return false;
        }
        $host = $this->getConfig('database.hostname', '127.0.0.1');
        $user = $this->getConfig('database.user', 'root');
        $pass = $this->getConfig('database.password', '');
        $db = $this->getConfig('database.dbname', 'a2billing');
        $mysqli = new mysqli($host, $user, $pass, $db);
        if ($mysqli->connect_error) {
            self::log('Mysql connection error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error, LOG_ERR);
            return false;
        }
        $this->dbh = $mysqli;
        $this->dbh->set_charset("utf8");
        return true;
    }
    
    private function initApi() {
        $scripts = glob(dirname(__FILE__).'/api/*.php');
        if (!is_array($scripts))
            return false;
        
        foreach ($scripts as $script) {
            try {
                require_once($script);
                $class = basename($script, '.php');
                $api = new $class();
                if ($api instanceof IApi)
                    $api->init($this);
            } catch (Exception $e) {
                return false;
            }
        }
        
        return true;
    }
    
    public function columnExists($table, $column) {
        $result = false;
        $sql = "show fields from $table";
        $data = $this->query($sql);
        foreach ($data as $row) {
            if (strtolower($row['Field']) == strtolower($column)) {
                $result = true;
                break;
            }
        }
        return $result;
    }
    
    public function getParam($key, $default = null, $group = 'global') {
        $sql = "select * from cc_config where config_group_title = '$group' and config_key = '$key' limit 1";
        $data = $this->query($sql);
        
        return (is_array($data) && count($data)) > 0 ? $data[0]['config_value'] : $default;
    }
    
    public function getQueryParam($key, $default = null) {
        return array_key_exists($key, $_REQUEST) ? $_REQUEST[$key] : $default;
    }
    
    public function query($sql) {
        $data = array();
        if ($this->dbh) {
            try {
                $result = $this->dbh->query($sql);
                if ($result === true) { // data changed
                    $data['affected_rows'] = $this->dbh->affected_rows;
                    $data['insert_id'] = $this->dbh->insert_id;
                } else if ($result instanceof mysqli_result) { // data extracted
                    while ($row = $result->fetch_assoc())
                        $data[] = $row;
                    $result->free();
                } else {
                    $data['error'] = $this->dbh->error;
                }
            } catch (Exception $e) {
                $data['error'] = $e->getMessage();
            }
        } else {
            $data['error'] = 'No connection';
        }
        return $data;
    }
    
    public function escape($value) {
        return $this->dbh ? $this->dbh->real_escape_string($value) : $value;
    }
    
    public function registerApi($name, $callback) {
        if (!array_key_exists($name, $this->api) && is_callable($callback))
            $this->api[$name] = $callback;
    }

    public function registerFormat($name, $callback) {
        if (!array_key_exists($name, $this->format) && is_callable($callback))
            $this->format[$name] = $callback;
    }
    
    private function check() {
        $ips = $this->getParam('api_ip_auth', null, 'webui');
        if (strlen($ips) > 0 && $ips != '*') {
            if (!in_array($_SERVER['REMOTE_ADDR'], explode(';', $ips))) {
                return 'Your IP is not allowed';
            }
        }
        
        if (strtolower(md5($this->getParam('api_security_key', null, 'webui'))) != strtolower($this->getQueryParam('api_security_key', 'error'))) {
            return 'Wrong API key';
        }

        return $error;
    }
    
    public function process() {
        $data = '';
        $ips = $this->getParam('api_ip_auth', null, 'webui');
        if (!strlen($ips) || $ips == '*' || in_array($_SERVER['REMOTE_ADDR'], explode(';', $ips))) {
            if (strtolower(md5($this->getParam('api_security_key', null, 'webui'))) == strtolower($this->getQueryParam('api_security_key', 'error'))) {
                $method = $this->getQueryParam('api_method');
                if (!is_null($method) && strlen($method) > 0 && array_key_exists($method, $this->api) && is_callable($this->api[$method])) {
                    $data = call_user_func($this->api[$method]);
                } else {
                    $data = 'Wrong API method';
                }
            } else {
                $data = 'Wrong API key';
            }
        } else {
            $data = 'Your IP is not allowed';
        }
        
        $format = $this->getQueryParam('api_format');
        if (!is_null($format) && strlen($format) > 0 && array_key_exists($format, $this->format) && is_callable($this->format[$format])) {
            echo call_user_func($this->format[$format], $data);
        } else {
            print_r($data);
        }
    }
    
}

$api = new Api();
$api->process();
