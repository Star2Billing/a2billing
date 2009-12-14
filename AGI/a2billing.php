#!/usr/bin/php -q
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

declare(ticks = 1);
if (function_exists('pcntl_signal')) {
	pcntl_signal(SIGHUP,  SIG_IGN);
}

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

include_once (dirname(__FILE__)."/lib/Class.Table.php");
include (dirname(__FILE__)."/lib/Class.A2Billing.php");
include (dirname(__FILE__)."/lib/Class.RateEngine.php");
include (dirname(__FILE__)."/lib/phpagi/phpagi.php");
include (dirname(__FILE__)."/lib/phpagi/phpagi-asmanager.php");
include (dirname(__FILE__)."/lib/Misc.php");
include (dirname(__FILE__)."/lib/interface/constants.php");

$charge_callback=0;
$G_startime = time();
$agi_version = "A2Billing - Version 1.4.4 (Lambic) - Released : 14 December 2009";

if ($argc > 1 && ($argv[1] == '--version' || $argv[1] == '-v')) {
	echo "A2Billing - Version $agi_version\n";
	exit;
}


/********** 	 CREATE THE AGI INSTANCE + ANSWER THE CALL		**********/
$agi = new AGI();


if ($argc > 1 && is_numeric($argv[1]) && $argv[1] >= 0) {
	$idconfig = $argv[1];
} else {
	$idconfig = 1;
}

if($dynamic_idconfig = intval($agi->get_variable("IDCONF", true)))
	$idconfig = $dynamic_idconfig;


if ($argc > 2 && strlen($argv[2]) > 0 && $argv[2] == 'did')						$mode = 'did';
elseif ($argc > 2 && strlen($argv[2]) > 0 && $argv[2] == 'callback')			$mode = 'callback';
elseif ($argc > 2 && strlen($argv[2]) > 0 && $argv[2] == 'cid-callback')		$mode = 'cid-callback';
elseif ($argc > 2 && strlen($argv[2]) > 0 && $argv[2] == 'all-callback')		$mode = 'all-callback';
elseif ($argc > 2 && strlen($argv[2]) > 0 && $argv[2] == 'voucher')				$mode = 'voucher';
elseif ($argc > 2 && strlen($argv[2]) > 0 && $argv[2] == 'campaign-callback')	$mode = 'campaign-callback';
else																			$mode = 'standard';

// get the area code for the cid-callback & all-callback
if ($argc > 3 && strlen($argv[3]) > 0) 
	$caller_areacode = $argv[3];
	
if ($argc > 4 && strlen($argv[4]) > 0){ 
	$groupid = $argv[4];
	$A2B -> group_mode = true;
	$A2B -> group_id = $groupid;
}

if ($argc > 5 && strlen($argv[5]) > 0){ 
	$cid_1st_leg_tariff_id = $argv[5];
}

$A2B = new A2Billing();
$A2B -> load_conf($agi, NULL, 0, $idconfig);
$A2B -> mode = $mode;



$A2B -> debug( INFO, $agi, __FILE__, __LINE__, "IDCONFIG : $idconfig");
$A2B -> debug( INFO, $agi, __FILE__, __LINE__, "MODE : $mode");


$A2B -> CC_TESTING = isset($A2B->agiconfig['debugshell']) && $A2B->agiconfig['debugshell'];
//$A2B -> CC_TESTING = true;

define ("DB_TYPE", isset($A2B->config["database"]['dbtype'])?$A2B->config["database"]['dbtype']:null);
define ("SMTP_SERVER", isset($A2B->config['global']['smtp_server'])?$A2B->config['global']['smtp_server']:null);
define ("SMTP_HOST", isset($A2B->config['global']['smtp_host'])?$A2B->config['global']['smtp_host']:null);
define ("SMTP_USERNAME", isset($A2B->config['global']['smtp_username'])?$A2B->config['global']['smtp_username']:null);
define ("SMTP_PASSWORD", isset($A2B->config['global']['smtp_password'])?$A2B->config['global']['smtp_password']:null);


// TEST DID
// if ($A2B -> CC_TESTING) $mode = 'did';

// Print header
$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "AGI Request:\n".print_r($agi->request, true));


/* GET THE AGI PARAMETER */
$A2B -> get_agi_request_parameter ($agi);

if (!$A2B -> DbConnect()) {
	$agi-> stream_file('prepaid-final', '#');
	exit;
}

define ("WRITELOG_QUERY", true);
$instance_table = new Table();
$A2B -> set_instance_table ($instance_table);

//GET CURRENCIES FROM DATABASE
$QUERY =  "SELECT id, currency, name, value FROM cc_currencies ORDER BY id";
$result = $A2B -> instance_table -> SQLExec ($A2B->DBHandle, $QUERY, 1, 300);

if (is_array($result)) {
	$num_cur = count($result);
	for ($i=0;$i<$num_cur;$i++) {
		$currencies_list[$result[$i][1]] = array (1 => $result[$i][2], 2 => $result[$i][3]);
	}
}

$RateEngine = new RateEngine();

if ($A2B -> CC_TESTING) {
	$RateEngine->debug_st = 1;
	$accountcode = '2222222222';
}


if ($mode == 'standard') {

	if ($A2B->agiconfig['answer_call']==1) {
		$A2B -> debug( INFO, $agi, __FILE__, __LINE__, '[ANSWER CALL]');
		$agi->answer();
		$status_channel=6;
	} else {
		$A2B -> debug( INFO, $agi, __FILE__, __LINE__, '[NO ANSWER CALL]');
		$status_channel=4;
	}

	$A2B -> play_menulanguage ($agi);

	// Play intro message
	if (strlen($A2B->agiconfig['intro_prompt'])>0) {
		$agi-> stream_file($A2B->agiconfig['intro_prompt'], '#');
	}
	
	$cia_res = $A2B -> callingcard_ivr_authenticate($agi);
	$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[TRY : callingcard_ivr_authenticate]");
	
	// CALL AUTHENTICATE AND WE HAVE ENOUGH CREDIT TO GO AHEAD
	if ($cia_res==0) {

		// RE-SET THE CALLERID
		$A2B->callingcard_auto_setcallerid($agi);
		
		for ($i=0;$i< $A2B->agiconfig['number_try'] ;$i++) {

			$RateEngine->Reinit();
			$A2B-> Reinit();

			// RETRIEVE THE CHANNEL STATUS AND LOG : STATUS - CREIT - MIN_CREDIT_2CALL
			$stat_channel = $agi->channel_status($A2B-> channel);
			$A2B -> debug( INFO, $agi, __FILE__, __LINE__, '[CHANNEL STATUS : '.$stat_channel["result"].' = '.$stat_channel["data"].']'.
						   "\n[CREDIT : ".$A2B-> credit."][CREDIT MIN_CREDIT_2CALL : ".$A2B->agiconfig['min_credit_2call']."]");
			
			// CHECK IF THE CHANNEL IS UP
			if (($A2B->agiconfig['answer_call']==1) && ($stat_channel["result"]!=$status_channel) && ($A2B -> CC_TESTING!=1)) {
				if ($A2B->set_inuse==1) $A2B->callingcard_acct_start_inuse($agi,0);
				$A2B -> write_log("[STOP - EXIT]", 0);
				exit();
			}

			// CREATE A DIFFERENT UNIQUEID FOR EACH TRY
			if ($i>0) {
				$A2B-> uniqueid = $A2B-> uniqueid + 1000000000;
			}
			
			// Feature to switch the Callplan from a customer : callplan_deck_minute_threshold 
			$A2B-> deck_switch($agi);
			
			if( !$A2B -> enough_credit_to_call() && $A2B->agiconfig['jump_voucher_if_min_credit']==1) {

				$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[NOTENOUGHCREDIT - Refill with vouchert]");
				$vou_res = $A2B -> refill_card_with_voucher($agi,2);
				if ($vou_res==1) {
					$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[ADDED CREDIT - refill_card_withvoucher Success] ");
				} else {
					$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[NOTENOUGHCREDIT - refill_card_withvoucher fail] ");
				}
			}
			
			$A2B -> debug( INFO, $agi, __FILE__, __LINE__,  "TARIFF ID -> ". $A2B->tariff);
			
			if(!$A2B -> enough_credit_to_call()) {

				// SAY TO THE CALLER THAT IT DEOSNT HAVE ENOUGH CREDIT TO MAKE A CALL
				$prompt = "prepaid-no-enough-credit-stop";
				$agi-> stream_file($prompt, '#');
				$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[STOP STREAM FILE $prompt]");
				
				if (($A2B->agiconfig['notenoughcredit_cardnumber']==1) && (($i+1)< $A2B->agiconfig['number_try'])) {

					if ($A2B->set_inuse==1)
						$A2B->callingcard_acct_start_inuse($agi,0);

					$A2B->agiconfig['cid_enable']=0;
					$A2B->agiconfig['use_dnid']=0;
					$A2B->agiconfig['cid_auto_assign_card_to_cid']=0;
					$A2B->accountcode='';
					$A2B->username='';
					$A2B-> ask_other_cardnumber	= 1;

					$cia_res = $A2B -> callingcard_ivr_authenticate($agi);
					$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[NOTENOUGHCREDIT_CARDNUMBER - TRY : callingcard_ivr_authenticate]");
					if ($cia_res!=0) break;

					$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[NOTENOUGHCREDIT_CARDNUMBER - callingcard_acct_start_inuse]");
					$A2B->callingcard_acct_start_inuse($agi,1);
					continue;

				} else {
					$send_reminder = 1;
					$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[SET MAIL REMINDER - NOT ENOUGH CREDIT]");
					break;
				}
			}
			
			$A2B->dnid = $agi->request['agi_dnid'];
			$A2B->extension = $agi->request['agi_extension'];

			if ($A2B->agiconfig['ivr_voucher']==1) {
				$res_dtmf = $agi->get_data('prepaid-refill_card_with_voucher', 5000, 1);
				$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "RES REFILL CARD VOUCHER DTMF : ".$res_dtmf ["result"]);
				$A2B-> ivr_voucher = $res_dtmf ["result"];
				if ((isset($A2B-> ivr_voucher)) && ($A2B-> ivr_voucher == $A2B->agiconfig['ivr_voucher_prefixe'])) {
					$vou_res = $A2B->refill_card_with_voucher($agi, $i);
				}
			}
			
			if ($A2B->agiconfig['sip_iax_friends']==1) {

				if ($A2B->agiconfig['sip_iax_pstn_direct_call']==1) {
					
					if ($A2B->agiconfig['use_dnid']==1 && !in_array ($A2B->dnid, $A2B->agiconfig['no_auth_dnid']) && strlen($A2B->dnid)>2 && $i==0 ) {

						$A2B -> destination = $A2B->dnid;

					} elseif ($i == 0) {
						$prompt_enter_dest = $A2B->agiconfig['file_conf_enter_destination'];
						$res_dtmf = $agi->get_data($prompt_enter_dest, 4000, 20);
						$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "RES sip_iax_pstndirect_call DTMF : ".$res_dtmf ["result"]);
						$A2B-> destination = $res_dtmf ["result"];
					}

					if ( (strlen($A2B-> destination)>0) 
						&& (strlen($A2B->agiconfig['sip_iax_pstn_direct_call_prefix'])>0) 
						&& (strncmp($A2B->agiconfig['sip_iax_pstn_direct_call_prefix'], $A2B-> destination,strlen($A2B->agiconfig['sip_iax_pstn_direct_call_prefix']))==0) ) {
						
						$A2B -> dnid = $A2B-> destination;
						$A2B -> sip_iax_buddy = $A2B->agiconfig['sip_iax_pstn_direct_call_prefix'];
						$A2B -> agiconfig['use_dnid'] = 1;
						$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "SIP 1. IAX - dnid : ".$A2B->dnid." - ".strlen($A2B->agiconfig['sip_iax_pstn_direct_call_prefix']));
						$A2B -> dnid = substr($A2B->dnid,strlen($A2B->agiconfig['sip_iax_pstn_direct_call_prefix']));
						$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "SIP 2. IAX - dnid : ".$A2B->dnid);
						
					} elseif (strlen($A2B->destination)>0) {
						$A2B -> dnid = $A2B->destination;
						$A2B -> agiconfig['use_dnid'] = 1;
						$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "TRUNK - dnid : ".$A2B->dnid." (".$A2B->agiconfig['use_dnid'].")");
					}
				} else {
					$res_dtmf = $agi->get_data('prepaid-sipiax-press9', 4000, 1);
					$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "RES SIP_IAX_FRIEND DTMF : ".$res_dtmf ["result"]);
					$A2B -> sip_iax_buddy = $res_dtmf ["result"];
				}
			}

			if ( strlen($A2B-> sip_iax_buddy) > 0 || ($A2B-> sip_iax_buddy == $A2B->agiconfig['sip_iax_pstn_direct_call_prefix'])) {

				$A2B -> debug( INFO, $agi, __FILE__, __LINE__, 'CALL SIP_IAX_BUDDY');
				$cia_res = $A2B-> call_sip_iax_buddy($agi, $RateEngine, $i);

			} else {
				
				$ans=$A2B-> callingcard_ivr_authorize($agi, $RateEngine, $i,true);
				$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, 'ANSWER fct callingcard_ivr authorize:> '.$ans);
				
				if ($ans==1) {
					// PERFORM THE CALL
					$result_callperf = $RateEngine->rate_engine_performcall ($agi, $A2B-> destination, $A2B);

					if (!$result_callperf) {
						$prompt="prepaid-dest-unreachable";
						$agi-> stream_file($prompt, '#');
					}
					// INSERT CDR  & UPDATE SYSTEM
					$RateEngine->rate_engine_updatesystem($A2B, $agi, $A2B-> destination);

					if ($A2B->agiconfig['say_balance_after_call']==1) {
						$A2B-> fct_say_balance ($agi, $A2B-> credit);
					}
					$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, '[a2billing account stop]');
					
				} else if($ans=="2DID") {
					
                    $A2B -> debug( INFO, $agi, __FILE__, __LINE__, "[ CALL OF THE SYSTEM - [DID=".$A2B-> destination."]");
					
                    $QUERY =  "SELECT cc_did.id, cc_did_destination.id, billingtype, tariff, destination,  voip_call, username, useralias, connection_charge, selling_rate, did".
                            " FROM cc_did, cc_did_destination,  cc_card ".
                            " WHERE id_cc_did=cc_did.id  AND cc_card.status=1 and cc_card.id=id_cc_card and cc_did_destination.activated=1  and cc_did.activated=1 and did='".$A2B-> destination."' ".
                            " AND cc_did.startingdate<= CURRENT_TIMESTAMP AND (cc_did.expirationdate > CURRENT_TIMESTAMP OR cc_did.expirationdate IS NULL";
                    if ($A2B->config["database"]['dbtype'] == "mysql") {
                        $QUERY .= " OR cc_did.expirationdate = '0000-00-00 00:00:00'";
                    }
                    $QUERY .= ") ORDER BY priority ASC";

                    $A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, $QUERY);
                    $result = $A2B -> instance_table -> SQLExec ($A2B->DBHandle, $QUERY);

                    if (is_array($result)) {
						$A2B -> call_2did($agi, $RateEngine, $result);
						if($A2B->set_inuse==1) $A2B -> callingcard_acct_start_inuse($agi,0);
                    }
            	}
			}
			$A2B->agiconfig['use_dnid']=0;
		}//END FOR

	} else {
		$A2B -> debug( WARN, $agi, __FILE__, __LINE__, "[AUTHENTICATION FAILED (cia_res:".$cia_res.")]");
	}
	
	/****************  SAY GOODBYE   ***************/
	if ($A2B->agiconfig['say_goodbye']==1) $agi-> stream_file('prepaid-final', '#');


// MODE DID

} elseif ($mode == 'did') {
	
	if ($A2B->agiconfig['answer_call']==1) {
		$A2B -> debug( INFO, $agi, __FILE__, __LINE__, '[ANSWER CALL]');
		$agi->answer();
	} else {
		$A2B -> debug( INFO, $agi, __FILE__, __LINE__, '[NO ANSWER CALL]');
	}
	// TODO
	// CRONT TO CHARGE MONTLY

	$RateEngine -> Reinit();
	$A2B -> Reinit();

	$mydnid = $agi->request['agi_extension'];
	if ($A2B -> CC_TESTING) $mydnid = '11111111';

	if (strlen($mydnid) > 0){
		$A2B -> debug( INFO, $agi, __FILE__, __LINE__, "[DID CALL - [CallerID=".$A2B->CallerID."]:[DID=".$mydnid."]");

		$QUERY =  "SELECT cc_did.id, cc_did_destination.id, billingtype, tariff, destination,  voip_call, username, useralias".
			" FROM cc_did, cc_did_destination,  cc_card ".
			" WHERE id_cc_did=cc_did.id and cc_card.status=1 and cc_card.id=id_cc_card and cc_did_destination.activated=1  and cc_did.activated=1 and did='$mydnid' ".
			" AND cc_did.startingdate<= CURRENT_TIMESTAMP AND (cc_did.expirationdate > CURRENT_TIMESTAMP OR cc_did.expirationdate IS NULL";
		if ($A2B->config["database"]['dbtype'] != "postgres"){
			// MYSQL
			$QUERY .= " OR cc_did.expirationdate = '0000-00-00 00:00:00'";
		}
		$QUERY .= ") ORDER BY priority ASC";

		$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, $QUERY);
		$result = $A2B -> instance_table -> SQLExec ($A2B->DBHandle, $QUERY);
		$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, $result);

		if (is_array($result)){
			$A2B -> call_did($agi, $RateEngine, $result);
			if ($A2B->set_inuse==1) $A2B -> callingcard_acct_start_inuse($agi,0);
		}
	}

// MOVE VOUCHER TO LET CUSTOMER ONLY REFILL
}elseif ($mode == 'voucher'){

	if ($A2B->agiconfig['answer_call']==1){
		$A2B -> debug( INFO, $agi, __FILE__, __LINE__, '[ANSWER CALL]');
		$agi->answer();
		$status_channel=6;
	}else{
		$A2B -> debug( INFO, $agi, __FILE__, __LINE__, '[NO ANSWER CALL]');
		$status_channel=4;
	}

	$A2B -> play_menulanguage ($agi);
	/*************************   PLAY INTRO MESSAGE   ************************/
	if (strlen($A2B->agiconfig['intro_prompt'])>0) 		$agi-> stream_file($A2B->agiconfig['intro_prompt'], '#');

	if (strlen($A2B->CallerID)>1 && is_numeric($A2B->CallerID)) {
		$A2B->CallerID = $caller_areacode.$A2B->CallerID;
	}
	$cia_res = $A2B -> callingcard_ivr_authenticate($agi);
	$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[TRY : callingcard_ivr_authenticate]");

	for ($k=0;$k<3;$k++){
		$vou_res = $A2B -> refill_card_with_voucher($agi, null);
		$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "VOUCHER RESULT = $vou_res");
		if ($vou_res==1){
			break;
		} else {
			$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[NOTENOUGHCREDIT - refill_card_withvoucher fail] ");
		}
	}

	// SAY GOODBYE
	if ($A2B->agiconfig['say_goodbye']==1) $agi-> stream_file('prepaid-final', '#');

	$agi->hangup();
	if ($A2B->set_inuse==1) $A2B->callingcard_acct_start_inuse($agi,0);
	$A2B -> write_log("[STOP - EXIT]", 0);
	exit();

// MODE CID-CALLBACK
}elseif ($mode == 'campaign-callback'){
	$A2B -> update_callback_campaign ($agi);
	
}elseif ($mode == 'cid-callback'){

	$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, '[MODE : CALLERID-CALLBACK - '.$A2B->CallerID.']');
	// END
	if ($A2B->agiconfig['answer_call'] == 1) {
		$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, '[HANGUP CLI CALLBACK TRIGGER]');
		$agi->hangup();
	} else {
		$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, '[CLI CALLBACK TRIGGER RINGING]');
	}

	// MAKE THE AUTHENTICATION ACCORDING TO THE CALLERID
	$A2B->agiconfig['cid_enable']=1;
	$A2B->agiconfig['cid_askpincode_ifnot_callerid']=0;
	$A2B->agiconfig['say_balance_after_auth']=0;

	if (strlen($A2B->CallerID)>1 && is_numeric($A2B->CallerID)) {

		/* WE START ;) */
		$cia_res = $A2B -> callingcard_ivr_authenticate($agi);
		$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[TRY : callingcard_ivr_authenticate]");
		if ($cia_res==0){

			$RateEngine = new RateEngine();
			
			// Apply 1st leg tariff override if param was passed in
			if (strlen($cid_1st_leg_tariff_id) > 0)
			{ 
				$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, 'Callback Tariff override for 1st Leg only. New tariff is '.$cid_1st_leg_tariff_id);
				$A2B ->tariff = $cid_1st_leg_tariff_id;
			}

			$A2B -> agiconfig['use_dnid']=1;
			$A2B -> agiconfig['say_timetocall']=0;

			// We arent removing leading zero in front of the callerID if needed this might be done over the dialplan
			$A2B -> extension = $A2B -> dnid = $A2B -> destination = $caller_areacode.$A2B->CallerID;
			
			$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, '[destination: - '.$A2B->destination.']');
			
			// LOOKUP RATE : FIND A RATE FOR THIS DESTINATION
			$resfindrate = $RateEngine->rate_engine_findrates($A2B, $A2B ->destination, $A2B ->tariff);
			$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, '[resfindrate: - '.$resfindrate.']');

			// IF FIND RATE
			if ($resfindrate!=0) {
				//$RateEngine -> debug_st	=1;
				$res_all_calcultimeout = $RateEngine->rate_engine_all_calcultimeout($A2B, $A2B->credit);
				//echo ("RES_ALL_CALCULTIMEOUT ::> $res_all_calcultimeout");

				if ($res_all_calcultimeout) {
					// MAKE THE CALL
					if ($RateEngine -> ratecard_obj[0][34]!='-1') {
						$usetrunk = 34;
						$usetrunk_failover = 1;
						$RateEngine -> usedtrunk = $RateEngine -> ratecard_obj[0][34];
					} else {
						$usetrunk = 29;
						$RateEngine -> usedtrunk = $RateEngine -> ratecard_obj[0][29];
						$usetrunk_failover = 0;
					}

					$prefix			= $RateEngine -> ratecard_obj[0][$usetrunk+1];
					$tech 			= $RateEngine -> ratecard_obj[0][$usetrunk+2];
					$ipaddress 		= $RateEngine -> ratecard_obj[0][$usetrunk+3];
					$removeprefix 	= $RateEngine -> ratecard_obj[0][$usetrunk+4];
					$timeout		= $RateEngine -> ratecard_obj[0]['timeout'];
					$callbackrate	= $RateEngine -> ratecard_obj[0]['callbackrate'];
					$failover_trunk	= $RateEngine -> ratecard_obj[0][40+$usetrunk_failover];
					$addparameter	= $RateEngine -> ratecard_obj[0][42+$usetrunk_failover];

					$destination = $A2B ->destination;
					if (strncmp($destination, $removeprefix, strlen($removeprefix)) == 0) $destination= substr($destination, strlen($removeprefix));

					$pos_dialingnumber = strpos($ipaddress, '%dialingnumber%' );

					$ipaddress = str_replace("%cardnumber%", $A2B->cardnumber, $ipaddress);
					$ipaddress = str_replace("%dialingnumber%", $prefix.$destination, $ipaddress);

					if ($pos_dialingnumber !== false){
						   $dialstr = "$tech/$ipaddress";
					}else{
						if ($A2B->agiconfig['switchdialcommand'] == 1){
							$dialstr = "$tech/$prefix$destination@$ipaddress";
						}else{
							$dialstr = "$tech/$ipaddress/$prefix$destination";
						}
					}

					//ADDITIONAL PARAMETER 			%dialingnumber%,	%cardnumber%
					if (strlen($addparameter)>0){
						$addparameter = str_replace("%cardnumber%", $A2B->cardnumber, $addparameter);
						$addparameter = str_replace("%dialingnumber%", $prefix.$destination, $addparameter);
						$dialstr .= $addparameter;
					}

					$channel= $dialstr;
					$exten = $A2B -> config["callback"]['extension'];
					if ($argc > 4 && strlen($argv[4]) > 0) $exten = $argv[4];
					$context = $A2B -> config["callback"]['context_callback'];
					$id_server_group = $A2B -> config["callback"]['id_server_group'];
					$priority = 1;
					$timeout = $A2B -> config["callback"]['timeout']*1000;
					//$callerid = $A2B -> config["callback"]['callerid'];
					$callerid=$A2B->CallerID;
					$application='';
					$account = $A2B -> accountcode;

					$uniqueid = MDP_NUMERIC(5).'-'.MDP_STRING(7);
					
					$sep = ($A2B->config['global']['asterisk_version'] == "1_6")?',':'|';
					
					$variable = "IDCONF=$idconfig".$sep."CALLED=".$A2B ->destination.$sep."MODE=CID".$sep."CBID=$uniqueid".$sep."LEG=".$A2B -> username;
					
					foreach($callbackrate as $key => $value){
						$variable .= $sep.strtoupper($key).'='.$value;
					}
					//pass the tariff if it was passed in
					if (strlen($cid_1st_leg_tariff_id) > 0)
					{ 
						$variable .= $sep.'TARIFF='.$cid_1st_leg_tariff_id;
					}
					
					$status = 'PENDING';
					$server_ip = 'localhost';
					$num_attempt = 0;

					if (is_numeric($A2B -> config["callback"]['sec_wait_before_callback']) && $A2B -> config["callback"]['sec_wait_before_callback']>=1){
						$sec_wait_before_callback = $A2B -> config["callback"]['sec_wait_before_callback'];
					}else{
						$sec_wait_before_callback = 1;
					}

					$QUERY = " INSERT INTO cc_callback_spool (uniqueid, status, server_ip, num_attempt, channel, exten, context, priority, variable, id_server_group, callback_time, account, callerid, timeout ) VALUES ('$uniqueid', '$status', '$server_ip', '$num_attempt', '$channel', '$exten', '$context', '$priority', '$variable', '$id_server_group', ADDDATE( CURRENT_TIMESTAMP, INTERVAL $sec_wait_before_callback SECOND ), '$account', '$callerid', '$timeout')";
					$res = $A2B -> DBHandle -> Execute($QUERY);
					$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-ALL : INSERT CALLBACK REQUEST IN SPOOL : QUERY=$QUERY]");

					if (!$res){
						$error_msg= "Cannot insert the callback request in the spool!";
						$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-ALL : CALLED=".$A2B ->destination." | $error_msg]");
					}

				}else{
					$error_msg = 'Error : You don t have enough credit to call you back !!!';
					$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-CALLERID : CALLED=".$A2B ->destination." | $error_msg]");
				}

			}else{
				$error_msg = 'Error : There is no route to call back your phonenumber !!!';
				$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-CALLERID : CALLED=".$A2B ->destination." | $error_msg]");
			}

		}else{
			$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-CALLERID : CALLED=".$A2B ->destination." | Authentication failed]");
		}

	}else{
		$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-CALLERID : CALLED=".$A2B ->destination." | error callerid]");
	}

} elseif ($mode == 'all-callback') {

	$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, '[MODE : ALL-CALLBACK - '.$A2B->CallerID.']');

	// END
	if ($A2B->agiconfig['answer_call'] == 1) {
		$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, '[HANGUP ALL CALLBACK TRIGGER]');
		$agi -> hangup();
	} else {
		$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, '[ALL CALLBACK TRIGGER RINGING]');
	}

	$A2B ->credit = 1000;
	$A2B ->tariff = $A2B -> config["callback"]['all_callback_tariff'];

	if (strlen($A2B->CallerID)>1 && is_numeric($A2B->CallerID)) {
		
		/* WE START ;) */
		if ($cia_res==0) {

			$RateEngine = new RateEngine();
			// $RateEngine -> webui = 0;
			// LOOKUP RATE : FIND A RATE FOR THIS DESTINATION

			$A2B ->agiconfig['use_dnid']=1;
			$A2B ->agiconfig['say_timetocall']=0;
			$A2B ->agiconfig['say_balance_after_auth']=0;
			$A2B -> extension = $A2B -> dnid = $A2B -> destination = $caller_areacode.$A2B -> CallerID;
			
			$resfindrate = $RateEngine->rate_engine_findrates($A2B, $A2B -> destination, $A2B -> tariff);

			// IF FIND RATE
			if ($resfindrate!=0) {
				//$RateEngine -> debug_st = 1;
				$res_all_calcultimeout = $RateEngine->rate_engine_all_calcultimeout($A2B, $A2B->credit);

				if ($res_all_calcultimeout){
					// MAKE THE CALL
					if ($RateEngine -> ratecard_obj[0][34]!='-1'){
						$usetrunk = 34;
						$usetrunk_failover = 1;
						$RateEngine -> usedtrunk = $RateEngine -> ratecard_obj[$k][34];
					} else {
						$usetrunk = 29;
						$RateEngine -> usedtrunk = $RateEngine -> ratecard_obj[$k][29];
						$usetrunk_failover = 0;
					}

					$prefix			= $RateEngine -> ratecard_obj[0][$usetrunk+1];
					$tech 			= $RateEngine -> ratecard_obj[0][$usetrunk+2];
					$ipaddress 		= $RateEngine -> ratecard_obj[0][$usetrunk+3];
					$removeprefix 	= $RateEngine -> ratecard_obj[0][$usetrunk+4];
					$timeout		= $RateEngine -> ratecard_obj[0]['timeout'];
					$failover_trunk	= $RateEngine -> ratecard_obj[0][40+$usetrunk_failover];
					$addparameter	= $RateEngine -> ratecard_obj[0][42+$usetrunk_failover];

					$destination = $A2B ->destination;
					if (strncmp($destination, $removeprefix, strlen($removeprefix)) == 0) $destination= substr($destination, strlen($removeprefix));

					$pos_dialingnumber = strpos($ipaddress, '%dialingnumber%' );

					$ipaddress = str_replace("%cardnumber%", $A2B->cardnumber, $ipaddress);
					$ipaddress = str_replace("%dialingnumber%", $prefix.$destination, $ipaddress);

					if ($pos_dialingnumber !== false) {
						   $dialstr = "$tech/$ipaddress";
					} else {
						if ($A2B->agiconfig['switchdialcommand'] == 1) {
							$dialstr = "$tech/$prefix$destination@$ipaddress";
						} else {
							$dialstr = "$tech/$ipaddress/$prefix$destination";
						}
					}

					//ADDITIONAL PARAMETER 			%dialingnumber%,	%cardnumber%
					if (strlen($addparameter)>0){
						$addparameter = str_replace("%cardnumber%", $A2B->cardnumber, $addparameter);
						$addparameter = str_replace("%dialingnumber%", $prefix.$destination, $addparameter);
						$dialstr .= $addparameter;
					}

					$channel= $dialstr;
					$exten = $A2B -> config["callback"]['extension'];
					if ($argc > 4 && strlen($argv[4]) > 0) $exten = $argv[4];
					$context = $A2B -> config["callback"]['context_callback'];
					$id_server_group = $A2B -> config["callback"]['id_server_group'];
					$callerid = $A2B -> config["callback"]['callerid'];
					$priority = 1;
					$timeout = $A2B -> config["callback"]['timeout']*1000;
					$application='';
					$account = $A2B -> accountcode;

					$uniqueid = MDP_NUMERIC(5).'-'.MDP_STRING(7);
					
					$sep = ($A2B->config['global']['asterisk_version'] == "1_6")?',':'|';
					
					$variable = "IDCONF=$idconfig".$sep."CALLED=".$A2B ->destination.$sep."MODE=ALL".$sep."CBID=$uniqueid".$sep."TARIFF=".$A2B ->tariff.$sep."LEG=".$A2B -> username;
					
					$status = 'PENDING';
					$server_ip = 'localhost';
					$num_attempt = 0;

					if (is_numeric($A2B -> config["callback"]['sec_wait_before_callback']) && $A2B -> config["callback"]['sec_wait_before_callback']>=1) {
						$sec_wait_before_callback = $A2B -> config["callback"]['sec_wait_before_callback'];
					} else {
						$sec_wait_before_callback = 1;
					}

					$QUERY = " INSERT INTO cc_callback_spool (uniqueid, status, server_ip, num_attempt, channel, exten, context, priority, variable, id_server_group, callback_time, account, callerid, timeout ) VALUES ('$uniqueid', '$status', '$server_ip', '$num_attempt', '$channel', '$exten', '$context', '$priority', '$variable', '$id_server_group', ADDDATE( CURRENT_TIMESTAMP, INTERVAL $sec_wait_before_callback SECOND ), '$account', '$callerid', '$timeout')";
					$res = $A2B -> DBHandle -> Execute($QUERY);
					$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-ALL : INSERT CALLBACK REQUEST IN SPOOL : QUERY=$QUERY]");

					if (!$res) {
						$error_msg= "Cannot insert the callback request in the spool!";
						$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-ALL : CALLED=".$A2B ->destination." | $error_msg]");
					}

				} else {
					$error_msg = 'Error : You don t have enough credit to call you back !!!';
					$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-CALLERID : CALLED=".$A2B ->destination." | $error_msg]");
				}
			} else {
				$error_msg = 'Error : There is no route to call back your phonenumber !!!';
				$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-CALLERID : CALLED=".$A2B ->destination." | $error_msg]");
			}

		} else {
			$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-CALLERID : CALLED=".$A2B ->destination." | Authentication failed]");
		}

	} else {
		$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-CALLERID : CALLED=".$A2B ->destination." | error callerid]");
	}


// MODE CALLBACK
} elseif ($mode == 'callback') {

	$callback_been_connected = 0;
	
	$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, '[CALLBACK]:[MODE : CALLBACK]');
	
	if ($A2B -> config["callback"]['answer_call']==1) {
		$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, '[CALLBACK]:[ANSWER CALL]');
		$agi -> answer();
		$status_channel = 6;

		// PLAY INTRO FOR CALLBACK
		if (strlen($A2B -> config["callback"]['callback_audio_intro']) > 0) {
			$agi-> stream_file($A2B -> config["callback"]['callback_audio_intro'], '#');
		}

	} else {
		$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, '[CALLBACK]:[NO ANSWER CALL]');
		$status_channel = 4;
	}

	$called_party = $agi->get_variable("CALLED", true);
	$calling_party = $agi->get_variable("CALLING", true);
	$callback_mode = $agi->get_variable("MODE", true);
	$callback_tariff = $agi->get_variable("TARIFF", true);
	$callback_uniqueid = $agi->get_variable("CBID", true);
	$callback_leg = $agi->get_variable("LEG", true);

	// |MODEFROM=ALL-CALLBACK|TARIFF=".$A2B ->tariff;
	$A2B -> extension = $A2B -> dnid = $A2B -> destination = $calling_party;

	if ($callback_mode=='CID') {
		$charge_callback = 1;
		$A2B->agiconfig['use_dnid'] = 0;
		$A2B->agiconfig['number_try'] =1;
		$A2B->CallerID = $called_party;

	}elseif ($callback_mode=='ALL') {
		$A2B->agiconfig['use_dnid'] = 0;
		$A2B->agiconfig['number_try'] =1;
		$A2B->agiconfig['cid_enable'] =0;

	} else {
		$charge_callback = 1;
		// FOR THE WEB-CALLBACK
		$A2B->agiconfig['number_try'] =1;
		$A2B->agiconfig['use_dnid'] =1;
		$A2B->agiconfig['say_balance_after_auth']=0;
		$A2B->agiconfig['cid_enable'] =0;
		$A2B->agiconfig['say_timetocall']=0;
	}

	$A2B -> debug( INFO, $agi, __FILE__, __LINE__, "[CALLBACK]:[GET VARIABLE : CALLED=$called_party | CALLING=$calling_party | MODE=$callback_mode | TARIFF=$callback_tariff | CBID=$callback_uniqueid | LEG=$callback_leg]");
	
	$QUERY = "UPDATE cc_callback_spool SET agi_result='AGI PROCESSING' WHERE uniqueid='$callback_uniqueid'";
	$res = $A2B -> DBHandle -> Execute($QUERY);
	$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK : UPDATE CALLBACK AGI_RESULT : QUERY=$QUERY]");


	/* WE START ;) */
	$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK]:[TRY : callingcard_ivr_authenticate]");
	$cia_res = $A2B -> callingcard_ivr_authenticate($agi);
	if ($cia_res==0) {

		$charge_callback = 1; // EVEN FOR  ALL CALLBACK
		$callback_leg = $A2B -> username;

		$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK]:[Start]");
		$A2B -> callingcard_auto_setcallerid($agi);

		for ($i=0 ; $i < 1 ; $i++) {

			$RateEngine->Reinit();
			$A2B-> Reinit();
			
			// DIVIDE THE AMOUNT OF CREDIT BY 2 IN ORDER TO AVOID NEGATIVE BALANCE IF THE USER USE ALL HIS CREDIT
			$orig_credit = $A2B -> credit;
			$A2B -> credit = $A2B->credit / 2;
			
			$stat_channel = $agi->channel_status($A2B-> channel);
			$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, '[CALLBACK]:[CHANNEL STATUS : '.$stat_channel["result"].' = '.$stat_channel["data"].']'.
							"[status_channel=$status_channel]:[ORIG_CREDIT : ".$orig_credit." - CUR_CREDIT - : ".$A2B -> credit.
							" - CREDIT MIN_CREDIT_2CALL : ".$A2B->agiconfig['min_credit_2call']."]");

			//if ($stat_channel["result"]!= $status_channel && ($A2B -> CC_TESTING!=1)) {
			//	break;
			//}

			if( !$A2B->enough_credit_to_call()) {
				// SAY TO THE CALLER THAT IT DEOSNT HAVE ENOUGH CREDIT TO MAKE A CALL
				$prompt = "prepaid-no-enough-credit-stop";
				$agi-> stream_file($prompt, '#');
				$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK]:[STOP STREAM FILE $prompt]");
			}

			if ($A2B-> callingcard_ivr_authorize($agi, $RateEngine, $i)==1) {
				// PERFORM THE CALL
				$result_callperf = $RateEngine->rate_engine_performcall ($agi, $A2B-> destination, $A2B);
				if (!$result_callperf) {
					$prompt="prepaid-dest-unreachable";
					$agi-> stream_file($prompt, '#');
				}

				// INSERT CDR  & UPDATE SYSTEM
				$RateEngine->rate_engine_updatesystem($A2B, $agi, $A2B-> destination);

				if ($A2B->agiconfig['say_balance_after_call']==1) {
					$A2B-> fct_say_balance ($agi, $A2B->credit);
				}

				$charge_callback = 1;
				if ($RateEngine->dialstatus == "ANSWER") {
					$callback_been_connected = 1;
				}
				
				$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK]:[a2billing end loop num_try] RateEngine->usedratecard=".$RateEngine->usedratecard);
			}
		}//END FOR

		if ($A2B->set_inuse==1) {
			$A2B->callingcard_acct_start_inuse($agi,0);
		}

	} else {
		$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK]:[AUTHENTICATION FAILED (cia_res:".$cia_res.")]");
	}

}

// CHECK IF WE HAVE TO CHARGE CALLBACK
if ($charge_callback) {
	
	$callback_username = $callback_leg;
	$A2B -> accountcode = $callback_username;
	$A2B -> agiconfig['say_balance_after_auth'] = 0;
	$A2B -> agiconfig['cid_enable'] = 0;
	$A2B -> agiconfig['say_timetocall'] = 0;

	$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK 1ST LEG]:[INFO FOR THE 1ST LEG - callback_username=$callback_username");
	$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK 1ST LEG]:[TRY : callingcard_ivr_authenticate]");
	$cia_res = $A2B -> callingcard_ivr_authenticate($agi);
	
	//overrides the tariff for the user with the one passed in.
	if (strlen($callback_tariff) > 0)
	{ 
		$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "*** Tariff override **** Changing from ".$A2B -> tariff." to ".$callback_tariff);
		$A2B ->tariff = $callback_tariff;
	}
	
	if ($cia_res==0) {

		$A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK 1ST LEG]:[MAKE BILLING FOR THE 1ST LEG - TARIFF:".$A2B -> tariff.";CALLED=$called_party]");
		$A2B->agiconfig['use_dnid'] = 1;
		$A2B ->dnid = $A2B ->destination = $called_party;
		
		$resfindrate = $RateEngine->rate_engine_findrates($A2B, $called_party, $A2B -> tariff);
		
		$RateEngine-> usedratecard = 0;
		
		// IF FIND RATE
		if ($resfindrate!=0 && is_numeric($RateEngine->usedratecard)) {
			$res_all_calcultimeout = $RateEngine->rate_engine_all_calcultimeout($A2B, $A2B->credit);

			if ($res_all_calcultimeout) {
				// SET CORRECTLY THE CALLTIME FOR THE 1st LEG
				$RateEngine -> answeredtime  = time() - $G_startime;
				$RateEngine -> dialstatus = 'ANSWERED';
				$A2B -> debug( INFO, $agi, __FILE__, __LINE__, "[CALLBACK]:[RateEngine -> answeredtime=".$RateEngine -> answeredtime."]");
				
				//(ST) replace above code with the code below to store CDR for all callbacks and to only charge for the callback if requested
				if ($callback_been_connected==1 || ($A2B->agiconfig['callback_bill_1stleg_ifcall_notconnected']==1) )  { 
					//(ST) this is called if we need to bill the user
					$RateEngine->rate_engine_updatesystem($A2B, $agi, $A2B-> destination, 1, 0, 1); 
				} else { 
					//(ST) this is called if we don't bill ther user but to keep track of call costs
					$RateEngine->rate_engine_updatesystem($A2B, $agi, $A2B-> destination, 0, 0, 1); 
				} 
				
			} else {
				$A2B -> debug( ERROR, $agi, __FILE__, __LINE__, "[CALLBACK 1ST LEG]:[ERROR - BILLING FOR THE 1ST LEG - rate_engine_all_calcultimeout: CALLED=$called_party]");
			}
		} else {
			$A2B -> debug( ERROR, $agi, __FILE__, __LINE__, "[CALLBACK 1ST LEG]:[ERROR - BILLING FOR THE 1ST LEG - rate_engine_findrates: CALLED=$called_party - RateEngine->usedratecard=".$RateEngine->usedratecard."]");
		}
	} else {
		$A2B -> debug( ERROR, $agi, __FILE__, __LINE__, "[CALLBACK 1ST LEG]:[ERROR - AUTHENTICATION USERNAME]");
	}
	
}// END if ($charge_callback)


// END
if ($mode != 'cid-callback' && $mode != 'all-callback') {
	$agi->hangup();
} elseif ($A2B->agiconfig['answer_call'] == 1) {
	$agi->hangup();
}


// SEND MAIL REMINDER WHEN CREDIT IS TOO LOW
if (isset($send_reminder) && $send_reminder == 1 && $A2B->agiconfig['send_reminder'] == 1) {

	if (strlen($A2B -> cardholder_email) > 5) {
        include_once (dirname(__FILE__)."/lib/mail/class.phpmailer.php");
        include_once (dirname(__FILE__)."/lib/Class.Mail.php");
		
        try {
            $mail = new Mail(Mail::$TYPE_REMINDERCALL,$A2B->id_card );
            $mail -> send();
            $A2B -> debug( DEBUG, $agi, __FILE__, __LINE__, "[SEND-MAIL REMINDER]:[TO:".$A2B -> cardholder_email." - FROM:$from - SUBJECT:$subject]");
        } catch (A2bMailException $e) {
        }
	}
}

if ($A2B->set_inuse==1) {
	$A2B->callingcard_acct_start_inuse($agi,0);
}

/************** END OF THE APPLICATION ****************/
$A2B -> write_log("[exit]", 0);

