<?php

/*
     * File: Class.Logger.php
     *
     * Description: Define an Logger class that can be used to log 
     *              different events happening in the application.
     *
*/	 

class Logger
{
	var $do_debug = 0;
	
	//constructor
	function Logger()
	{
	
	}
	//Function insertLog
	// Inserts the Log into table
	function insertLog_Add($userID, $logLevel, $actionPerformed, $description, $tableName, $ipAddress, $pageName, $param_add_fields, $param_add_value)
	{
		$DB_Handle = DBConnect();
		$table_log = new Table();		
		$pageName = basename($pageName);
		$pageName    = array_shift(explode('?', $pageName));		
		$description = str_replace("'", "", $description);
		$str_submitted_fields = explode(',', $param_add_fields);
		$str_submitted_values = explode(',', $param_add_value);
		$num_records = count($str_submitted_fields);
		for($num = 0; $num < $num_records; $num++)
		{
			$str_name_value_pair .= $str_submitted_fields[$num]." = ".str_replace("'",'',$str_submitted_values[$num]);
			if($num != $num_records -1)
			{
				$str_name_value_pair .= "|";
			}
		}
		$QUERY = "INSERT INTO cc_system_log (iduser, loglevel, action, description, tablename, pagename, ipaddress, data) ";
		$QUERY .= " VALUES('".$userID."','".$logLevel."','".$actionPerformed."','".$description."','".$tableName."','".$pageName."','".$ipAddress."','".$str_name_value_pair."')";
		if ($this -> do_debug) echo $QUERY;

		$table_log -> SQLExec($DB_Handle, $QUERY);		
	}
	
	function insertLog_Update($userID, $logLevel, $actionPerformed, $description, $tableName, $ipAddress, $pageName, $param_update)
	{
		$DB_Handle = DBConnect();
		$table_log = new Table();		
		$pageName = basename($pageName);
		$pageName    = array_shift(explode('?', $pageName));		
		$description = str_replace("'", "", $description);
		$str_submitted_fields = explode(',', $param_update);		
		$num_records = count($str_submitted_fields);
		for($num = 0; $num < $num_records; $num++)
		{
			$str_name_value_pair .= str_replace("'","",$str_submitted_fields[$num]);
			if($num != $num_records -1)
			{
				$str_name_value_pair .= "|";
			}
		}
		$QUERY = "INSERT INTO cc_system_log (iduser, loglevel, action, description, tablename, pagename, ipaddress, data) ";
		$QUERY .= " VALUES('".$userID."','".$logLevel."','".$actionPerformed."','".$description."','".$tableName."','".$pageName."','".$ipAddress."','".$str_name_value_pair."')";
		
		if ($this -> do_debug) echo $QUERY;

		$table_log -> SQLExec($DB_Handle, $QUERY);		
	}	
	function insertLog($userID, $logLevel, $actionPerformed, $description, $tableName, $ipAddress, $pageName, $data='')
	{
		$DB_Handle = DBConnect();
		$table_log = new Table();		
		$pageName = basename($pageName);
		$pageArray = explode('?', $pageName);
		$pageName = array_shift($pageArray);
		$description = str_replace("'", "", $description);
		
		$QUERY = "INSERT INTO cc_system_log (iduser, loglevel, action, description, tablename, pagename, ipaddress, data) ";
		$QUERY .= " VALUES('".$userID."','".$logLevel."','".$actionPerformed."','".$description."','".$tableName."','".$pageName."','".$ipAddress."','".$data."')";
		if ($this -> do_debug) echo $QUERY;

		$table_log -> SQLExec($DB_Handle, $QUERY);		
	}
	
	//Funtion deleteLog
	//Delete the log from table
	function deleteLog($id = 0)
	{
		$DB_Handle = DBConnect();
		$table_log = new Table();
		$QUERY = "DELETE FROM cc_system_log WHERE id = ".$id;		
		if ($this -> do_debug) echo $QUERY;		
		$table_log -> SQLExec($DB_Handle, $QUERY);
	}
}


?>
