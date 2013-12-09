<?php

/* - - - - - - - - - - - - - - - - - - - - -

 Title : PqpDatbase Interface
 Author : Created by Ryan Campbell
 URL : http://particletree.com

 Last Updated : April 26, 2009

 Description : An Interface for your Database class
 should you wish to integrate it with PQP. See online docs
 or MySqlDatabase.php file for sample implementation that
 follows this interface.

- - - - - - - - - - - - - - - - - - - - - */

interface PqpDatabase
{
    /*------------------------------
        Required Member Variables
    -------------------------------*/

    //public $queryCount = 0;
    //public $queries = array();

    /*------------------------------
          Required Functions
    -------------------------------*/

    public function query($sql);
    public function logQuery($sql, $start);
    public function getTime();
    public function getReadableTime($time);

}
