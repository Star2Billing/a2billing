<?php
Class Notification {
	
	static public $KEYMSG = array("new_sip_buddies" => " New SIP generated");
	
	private $id;
	private $date;
	private $key;
	private $priority;
	private $from_type;
	private $from_id;
	private $new;
	
	function __construct($id,$date,$key,$priority,$from_type,$from_id,$new){
		$this->id = $id;
		$this->date = $date;
		$this->priority = $priority;
		$this->from_type = $from_type;
		$this->from_id = $from_id;
		$this->key = $key;
		$this->new = $new;
	}
	

	function getId() {
		return $this->id;
	}
	
	function getNew() {
		return $this->new;
	}
	
	function getDate() {
		return $this->date;
	}
	
	function getPriority() {
		return $this->priority;
	}
	function getPriorityMsg() {
		
		switch ($this->priority) {
			case 2: return gettext("HIGH");
					break;
			case 1: return gettext("MEDIUM");
					break;
			case 0: 
			default:return gettext("LOW");
					break;
		}
	
	}
	
	function getFromType() {
		return $this->from_type;
	}
	
	function getFromId() {
		return $this->from_id;
	}
	
	function getKey() {
		return $this->key;
	}
	
	function getKeyMsg() {
		if(array_key_exists($this->key,Notification::$KEYMSG)) return Notification::$KEYMSG[$this->key];
		else return gettext("No Message Unknown");
	}
  	
  	
}
?>