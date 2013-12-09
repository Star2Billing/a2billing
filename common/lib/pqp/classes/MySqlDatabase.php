<?php

/* - - - - - - - - - - - - - - - - - - - - -

 Title : PHP Quick Profiler MySQL Class
 Author : Created by Ryan Campbell
 URL : http://particletree.com

 Last Updated : April 22, 2009

 Description : A simple database wrapper that includes
 logging of queries.

- - - - - - - - - - - - - - - - - - - - - */

require_once 'PqpDatabase.Interface.php';

class MySqlDatabase implements PqpDatabase
{
    private $host;
    private $user;
    private $password;
    private $database;
    public $queryCount = 0;
    public $queries = array();
    public $conn;

    /*------------------------------------
              CONFIG CONNECTION
    ------------------------------------*/

    public function __construct($host, $user, $password)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    public function connect($new = false)
    {
        $this->conn = mysql_connect($this->host, $this->user, $this->password, $new);
        if (!$this->conn) {
            throw new Exception('We\'re working on a few connection issues.');
        }
    }

    public function changeDatabase($database)
    {
        $this->database = $database;
        if ($this->conn) {
            if (!mysql_select_db($database, $this->conn)) {
                throw new CustomException('We\'re working on a few connection issues.');
            }
        }
    }

    public function lazyLoadConnection()
    {
        $this->connect(true);
        if($this->database) $this->changeDatabase($this->database);
    }

    /*-----------------------------------
                       QUERY
    ------------------------------------*/

    public function query($sql)
    {
        if(!$this->conn) $this->lazyLoadConnection();
        $start = $this->getTime();
        $rs = mysql_query($sql, $this->conn);
        $this->queryCount += 1;
        $this->logQuery($sql, $start);
        if (!$rs) {
            throw new Exception('Could not execute query.');
        }

        return $rs;
    }

    /*-----------------------------------
                  DEBUGGING
    ------------------------------------*/

    public function logQuery($sql, $start)
    {
        $query = array(
                'sql' => $sql,
                'time' => ($this->getTime() - $start)*1000
            );
        array_push($this->queries, $query);
    }

    public function getTime()
    {
        $time = microtime();
        $time = explode(' ', $time);
        $time = $time[1] + $time[0];
        $start = $time;

        return $start;
    }

    public function getReadableTime($time)
    {
        $ret = $time;
        $formatter = 0;
        $formats = array('ms', 's', 'm');
        if ($time >= 1000 && $time < 60000) {
            $formatter = 1;
            $ret = ($time / 1000);
        }
        if ($time >= 60000) {
            $formatter = 2;
            $ret = ($time / 1000) / 60;
        }
        $ret = number_format($ret,3,'.','') . ' ' . $formats[$formatter];

        return $ret;
    }

    public function __destruct()
    {
        @mysql_close($this->conn);
    }

}
