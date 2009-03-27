<?php
Class Notification {
	
	static public $KEYMSG = array("sip_iax_changed" => "New SIP & IAX added : Friends conf have to be generated",
								  "sip_changed" => "New SIP added : Sip Friends conf have to be generated",
								  "iax_changed" => "New IAX added : IAX Friends conf have to be generated"	);
	
	
	static public $ADMIN = 0;
	static public $AGENT = 1;
	static public $CUST = 2;
	static public $BATCH = 3;
	static public $UNKNOWN = -1;
	
	static public $LOW = 0;
	static public $MEDIUM = 1;
	static public $HIGN = 2;
	
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
	
	function getFromDisplay() {
		$display = "";
		switch($this->from_type){
			case 0: $display.= "ADMIN: ".getnameofadmin($this->from_id);
					break;
			case 1:$display.= "AGENT: ".getnameofagent($this->from_id);
					break;
			case 2:$display.= "CUST: ".getnameofcustomer_id($this->from_id);
					break;
			case 3:$display.= gettext("BATCH");
					break;
			case -1 :$display.= gettext("UNKNOWN");
					break;
		}
		return $display;
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