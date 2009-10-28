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

class Realtime {

	private $DBHandler;
	
	private $instance_table;
	
	private $FG_TABLE_SIP_NAME = "cc_sip_buddies";
	private $FG_TABLE_IAX_NAME = "cc_iax_buddies";

	private $FG_QUERY_ADITION_SIP_IAX;

    private $FG_QUERY_ADITION_SIP_IAX_FIELDS;
    
    
    // Construct
	public function __construct() {
        
		$this -> DBHandler = DBConnect();
		$this -> instance_table = new Table();	
		
		$this -> FG_QUERY_ADITION_SIP_IAX = 'name, type, username, accountcode, regexten, callerid, amaflags, secret, md5secret, nat, dtmfmode, qualify, canreinvite, disallow, ' .
				' allow, host, callgroup, context, defaultip, fromuser, fromdomain, insecure, language, mailbox, permit, deny, mask, pickupgroup, port,restrictcid, rtptimeout,' .
				' rtpholdtimeout, musiconhold, regseconds, ipaddr, cancallforward';
		
		$this -> FG_QUERY_ADITION_SIP_IAX_FIELDS = "name, accountcode, regexten, amaflags, callerid, context, dtmfmode, host, type, username, allow, secret, id_cc_card, nat, qualify";
	}
	
	// create_iax_config
	// $type - Value : sip, iax
	public function create_trunk_config_file ($type = 'sip') {
	    
	    if (USE_REALTIME) {
	        return false;
	    }
	    
	    if ($type == 'iax') {
	        $buddyfile = BUDDY_IAX_FILE;
	        $table_name = $this -> FG_TABLE_IAX_NAME;
        } else {
            $buddyfile = BUDDY_SIP_FILE;
            $table_name = $this -> FG_TABLE_SIP_NAME;
        }

		$this -> instance_table = new Table($table_name, 'id, ' . $this -> FG_QUERY_ADITION_SIP_IAX);
		$list_friend = $this -> instance_table -> Get_list($this->DBHandler, '', null, null, null, null);
		
		$list_names = explode(",",$this -> FG_QUERY_ADITION_SIP_IAX);
		

		if (is_array($list_friend)) {
			$fd = fopen($buddyfile, "w");
			if (!$fd) {
				$error_msg = "</br><center><b><font color=red>" . gettext("Could not open buddy file") . $buddyfile . "</font></b></center>";
			} else {
				foreach ($list_friend as $data) {
					$line = "\n\n[" . $data[1] . "]\n";
					if (fwrite($fd, $line) === FALSE) {
						echo "Impossible to write to the file ($buddyfile)";
						break;
					} else {
						for ($i = 1; $i < count($data) - 1; $i++) {
							if (strlen($data[$i +1]) > 0) {
								if (trim($list_names[$i]) == 'allow') {
									$codecs = explode(",", $data[$i +1]);
									$line = "";
									foreach ($codecs as $value)
										$line .= trim($list_names[$i]) . '=' . $value . "\n";
								} else {
									$line = (trim($list_names[$i]) . '=' . $data[$i +1] . "\n");
								}
								if (fwrite($fd, $line) === FALSE) {
									echo gettext("Impossible to write to the file") . " ($buddyfile)";
									break;
								}
							}
						}
					}
				}
				fclose($fd);
			}
		} // end if is_array
	}
	
	// insert_voip_config
	// sip : 1 / 0
	// iax : 1 / 0
	function insert_voip_config ($sip, $iax, $id_card, $accountnumber, $passui_secret) 
	{
	    if (!isset ($sip))
	        $sip = 0;
	    
	    if (!isset ($iax))
	        $iax = 0;
        
	    // SIP / IAX FRIENDS TABLE
	    $FG_TABLE_SIP_NAME = "cc_sip_buddies";
	    $FG_TABLE_IAX_NAME = "cc_iax_buddies";
	
	    $FG_QUERY_ADITION_SIP_IAX_FIELDS = "name, accountcode, regexten, amaflags, callerid, context, dtmfmode, host, type, username, allow, secret, id_cc_card, nat, qualify";
	    
	    if ((isset ($sip)) || (isset ($iax))) {
		    $instance_sip_table = new Table($FG_TABLE_SIP_NAME, $FG_QUERY_ADITION_SIP_IAX_FIELDS);
		    $instance_iax_table = new Table($FG_TABLE_IAX_NAME, $FG_QUERY_ADITION_SIP_IAX_FIELDS);
		    
		    $type = FRIEND_TYPE;
		    $allow = str_replace(' ', '', FRIEND_ALLOW);
		    $context = FRIEND_CONTEXT;
		    $nat = FRIEND_NAT;
		    $amaflags = FRIEND_AMAFLAGS;
		    $qualify = FRIEND_QUALIFY;
		    $host = FRIEND_HOST;
		    $dtmfmode = FRIEND_DTMFMODE;
		    
		    if(!USE_REALTIME) {
				if(($sip == 1) && ($iax == 1)) $key = "sip_iax_changed";
				elseif (($sip == 1) ) $key = "sip_changed";
				elseif ($iax == 1) $key = "iax_changed";
				
				//check who
				if ($_SESSION["user_type"]=="ADMIN") {
				    $who = Notification::$ADMIN;
				    $who_id = $_SESSION['admin_id'];
				} elseif ($_SESSION["user_type"]=="AGENT") {
				    $who = Notification::$AGENT;
				    $who_id = $_SESSION['agent_id'];
				} else {
				    $who=Notification::$UNKNOWN;
				    $id=-1;
				}
				NotificationsDAO::AddNotification($key, Notification::$HIGH, $who, $who_id);
			}
	    }
	    
	    // Insert data for sip_buddy
		if (isset ($sip)) {
			$FG_QUERY_ADITION_SIP_IAX_VALUE = "'$accountnumber', '$accountnumber', '$accountnumber', '$amaflags', '', '$context', '$dtmfmode','$host', '$type', ".
			                                    "'$accountnumber', '$allow', '$passui_secret', '$id_card', '$nat', '$qualify'";
			$result_query1 = $instance_sip_table->Add_table($this->DBHandler, $FG_QUERY_ADITION_SIP_IAX_VALUE, null, null, null);
			if (USE_REALTIME) {
				$_SESSION["is_sip_iax_change"] = 1;
				$_SESSION["is_sip_changed"] = 1;
			}
		}

		// Insert data for iax_buddy
		if (isset ($iax)) {
			$FG_QUERY_ADITION_SIP_IAX_VALUE = "'$accountnumber', '$accountnumber', '$accountnumber', '$amaflags', '', '$context', '$dtmfmode','$host', '$type', ".
			                                   "'$accountnumber', '$allow', '$passui_secret', '$id_card', '$nat', '$qualify'";
			$result_query2 = $instance_iax_table->Add_table($this->DBHandler, $FG_QUERY_ADITION_SIP_IAX_VALUE, null, null, null);
			if (USE_REALTIME) {
				$_SESSION["is_sip_iax_change"] = 1;
				$_SESSION["is_iax_changed"] = 1;
			}
		}
	
	}
	

}

