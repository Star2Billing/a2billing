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

Class Notification {
	
	static public $ADMIN = 0;
	static public $AGENT = 1;
	static public $CUST = 2;
	static public $BATCH = 3;
	static public $UNKNOWN = -1;
	
	static public $LOW = 0;
	static public $MEDIUM = 1;
	static public $HIGH = 2;
	
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
	
	static public function getAllKey(){
		return array("sip_iax_changed" 		=> gettext("New SIP & IAX added : Friends conf have to be generated"),
					  "sip_changed" 		=> gettext("New SIP added : Sip Friends conf have to be generated"),
					  "iax_changed"			=> gettext("New IAX added : IAX Friends conf have to be generated"),
					  "ticket_added_agent" 	=> gettext("New Ticket added by agent"),
					  "ticket_added_cust" 	=> gettext("New Ticket added by customer"));
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
		$keys=Notification::getAllKey();
		if(array_key_exists($this->key,$keys)) return $keys[$this->key];
		else return gettext("No Message Unknown");
	}
  	  	
}

