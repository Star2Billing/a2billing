<?php
Class NotificationsDAO {
	
	static function AddNotification($key,$priority,$from_type,$from_id=0) {
			$DBHandle = DbConnect();
			$table = new Table("cc_notification", "*");
			$fields = " key_value , priority, from_type, from_id";
			$values = " '$key' , $priority,$from_type	,$from_id ";
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
		$return = $table->Get_list($DBHandle, "", "date", "ASC");
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
			$list[$i] = new Notification($record['id'],$record['date'],$record['key_value'],$record['priority'],$record['from_type'],$record['from_id'],$new);
			$i++; 
		}
		return $list;
  		
  	}
  	
	static function IfNewNotification($id){
  		$DBHandle = DbConnect();
		$table = new Table("cc_notification LEFT JOIN cc_notification_admin ON id = id_notification AND id_admin =$id", "count(*)");
		$clause = "viewed != 1 OR viewed IS NULL";
		$return = $table->Get_list($DBHandle,$clause, "date", "DESC");
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
			$list[$i] = new Notification($record['id'],$record['date'],$record['key_value'],$record['priority'],$record['from_type'],$record['from_id'],$new);
			$i++; 
		}
		return $list;
  	}
  	
  	
}
?>