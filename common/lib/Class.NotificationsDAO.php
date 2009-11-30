<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,   
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 * 
 * @copyright   Copyright (C) 2004-2009 - Star2billing S.L. 
 * @author      Belaid Rachid <rachid.belaid@gmail.com>
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

class NotificationsDAO {
	
	static function AddNotification($key,$priority,$from_type,$from_id=0,$link_type=null,$link_id=null) {
			$DBHandle = DbConnect();
			$table = new Table("cc_notification", "*");
			$fields = " key_value , priority, from_type, from_id, link_type,link_id";
			$values = " '$key' , $priority,$from_type	,$from_id ,'$link_type',$link_id";
			$return = $table->Add_table($DBHandle, $values, $fields);
			return $return;
  	 }
  	 
	static function DelNotification($id) {
		if (is_numeric($id)) {
			$DBHandle = DbConnect();
			$table = new Table("cc_notification", "*");
			$CLAUSE = " id = " . $id ;
			$table->Delete_table($DBHandle, $CLAUSE);
			$table = new Table("cc_notification_admin", "*");
			$CLAUSE = " id_notification = " . $id ;
			$table->Delete_table($DBHandle, $CLAUSE);
			return true;
		} else
			return false;
  	 }
  	 
  	 
  	 
  	static function getNbNotifications(){
  		$DBHandle = DbConnect();
		$table = new Table("cc_notification", "count(*)");
		$return = $table->Get_list($DBHandle, "", "", "");
  		return $return[0][0];
  	}
  	
	static function getAllNotifications(){
		$DBHandle = DbConnect();
		$table = new Table("cc_notification LEFT JOIN cc_notification_admin ON id = id_notification", "*");
		$clause = "id_admin = $id";
		$return = $table->Get_list($DBHandle, $clause, "date", "DESC");
		$list = array();
		$i=0;
		foreach ($return as $record) {
			if($record['viewed']!=0 && !is_null($record['viewed']))$new = false;
			else $new = true;
			$list[$i] = new Notification($record['id'],$record['date'],$record['key_value'],$record['priority'],$record['from_type'],$record['from_id'],$record['link_id'],$record['link_type'],$new);
			$i++; 
		}
		return $list;
  		
  	}
  	
	static function IfNewNotification($id){
  		$DBHandle = DbConnect();
		$table = new Table("cc_notification LEFT JOIN cc_notification_admin ON id = id_notification AND id_admin =$id", "count(*)");
		$clause = "viewed != 1 OR viewed IS NULL";
		$return = $table->Get_list($DBHandle,$clause, "", "");
		if($return[0][0]==0)return false;
		else return true;
  	}
  	
	static function getNotifications($id,$current,$nb){
  		$DBHandle = DbConnect();
		$table = new Table("cc_notification LEFT JOIN cc_notification_admin ON id = id_notification AND id_admin =$id", "*");
		$return = $table->Get_list($DBHandle, "", "date", "DESC",null,null,$nb,$current);
		$i=0;
		foreach ($return as $record) {
			if($record['viewed']!=0 && !is_null($record['viewed']))$new = false;
			else $new = true;
			$list[$i] = new Notification($record['id'],$record['date'],$record['key_value'],$record['priority'],$record['from_type'],$record['from_id'],$record['link_id'],$record['link_type'],$new);
			$i++; 
		}
		return $list;
  	}
  	  	
}


