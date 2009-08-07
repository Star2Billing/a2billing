<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,   
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 * 
 * @copyright   Copyright (C) 2004-2009 - Star2billing S.L. 
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
 * 
 * 
**/

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
	
	function insertLogAgent($userID, $logLevel, $actionPerformed, $description, $tableName, $ipAddress, $pageName, $data='')
	{
		$DB_Handle = DBConnect();
		$table_log = new Table();		
		$pageName = basename($pageName);
		$pageArray = explode('?', $pageName);
		$pageName = array_shift($pageArray);
		$description = str_replace("'", "", $description);
		
		$QUERY = "INSERT INTO cc_system_log (iduser, loglevel, action, description, tablename, pagename, ipaddress, data, agent) ";
		$QUERY .= " VALUES('".$userID."','".$logLevel."','".$actionPerformed."','".$description."','".$tableName."','".$pageName."','".$ipAddress."','".$data."', 1)";
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


