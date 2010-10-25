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
	static public $SOAPSERVER = 4;
	static public $UNKNOWN = -1;
	
	static public $LOW = 0;
	static public $MEDIUM = 1;
	static public $HIGH = 2;
	
	static public $LINK_NONE = "none";
	static public $LINK_TICKET_CUST = "ticket_cust";
	static public $LINK_TICKET_AGENT = "ticket_agent";
	static public $LINK_REMITTANCE = "remittance";
	static public $LINK_DID_DESTINATION = "did_destination";
	static public $LINK_CARD = "card";
	
	private $id;
	private $date;
	private $key;
	private $priority;
	private $from_type;
	private $from_id;
	private $new;
	private $link_type;
	private $link_id;
	
	function __construct($id, $date, $key, $priority, $from_type, $from_id, $link_id = null, $link_type = null, $new){
		$this->id = $id;
		$this->date = $date;
		$this->priority = $priority;
		$this->from_type = $from_type;
		$this->from_id = $from_id;
		$this->key = $key;
		$this->new = $new;
		$this->link_id = $link_id;
		$this->link_type = $link_type;
	}
	
	static public function getAllKey(){
		return array("sip_iax_changed" 		=> gettext("New SIP & IAX added : Friends conf have to be generated"),
					  "sip_changed" 		=> gettext("New SIP added : Sip Friends conf have to be generated"),
					  "iax_changed"			=> gettext("New IAX added : IAX Friends conf have to be generated"),
					  "ticket_added_agent" 	=> gettext("New Ticket added by agent"),
					  "ticket_added_cust" 	=> gettext("New Ticket added by customer"),
					  "did_destination_edited_cust" => gettext("DID Destination edited by customer"),
                      "remittance_added_agent"	=> gettext("New Remittance request added"),
                      "added_new_signup"	=> gettext("Added new sign-up"));
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
	
	function getLinkType() {
		return $this->link_type;
	}
	
	function getLinkId() {
		return $this->link_id;
	}
	
	function getFromDisplay() {
		$display = "";
		switch($this->from_type){
			case 0: $display.= "ADMIN: ".getnameofadmin($this->from_id);
					break;
			case 1: $display.= "AGENT: ".getnameofagent($this->from_id);
					break;
			case 2: $display.= "CUST: ".getnameofcustomer_id($this->from_id);
					break;
			case 3: $display.= gettext("BATCH");
					break;
			case 4: $display.= gettext("SOAP-SERVER");
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
		else return $this->key;
	}
	
	function getUrl(){
		$link = "";
		if(!empty($this->link_id) && !empty($this->link_type) && $this->link_type != Notification::$LINK_NONE ){
			switch ($this->link_type) {
				case Notification::$LINK_REMITTANCE:$link .= "A2B_remittance_info.php?id=";
				    break;
				case Notification::$LINK_DID_DESTINATION:$link .= "A2B_entity_did_destination.php?form_action=ask-edit&id=";
				    break;
				case Notification::$LINK_TICKET_CUST:
				case Notification::$LINK_TICKET_AGENT:$link .= "CC_ticket_view.php?id=";
				case Notification::$LINK_CARD:$link .= "A2B_entity_card.php?form_action=ask-edit&id=";
                    break;
			}
			$link .= $this->link_id;
		}
		return $link;
	}
  	  	
}

