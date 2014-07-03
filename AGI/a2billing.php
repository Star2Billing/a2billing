#!/usr/bin/php -q
<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2012 - Star2billing S.L.
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
    pcntl_signal(SIGHUP, SIG_IGN);
}

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

include(dirname(__FILE__) . "/lib/Class.Table.php");
include(dirname(__FILE__) . "/lib/Class.A2Billing.php");
include(dirname(__FILE__) . "/lib/Class.RateEngine.php");
include(dirname(__FILE__) . "/lib/phpagi/phpagi.php");
include(dirname(__FILE__) . "/lib/phpagi/phpagi-asmanager.php");
include(dirname(__FILE__) . "/lib/Misc.php");
include(dirname(__FILE__) . "/lib/interface/constants.php");

$charge_callback = 0;
$G_startime = time();
$agi_version = "A2Billing - v2.0.14";

if ($argc > 1 && ($argv[1] == '--version' || $argv[1] == '-v')) {
    echo "$agi_version\n";
    exit;
}

$agi = new AGI();

$optconfig = array();
if ($argc > 1 && strstr($argv[1], "+")) {
    /*
    This change allows some configuration overrides on the AGI command-line by allowing the user to add them after the configuration number, like so:
    exten => 0312345678, 3, AGI(a2billing.php, "1+use_dnid=0&extracharge_did=12345")
    */
    //check for configuration overrides in the first argument
    $idconfig = substr($argv[1], 0, strpos($argv[1], "+"));
    $configstring = substr($argv[1], strpos($argv[1], "+") + 1);

    foreach (explode("&", $configstring) as $conf) {
        $var = substr($conf, 0, strpos($conf, "="));
        $val = substr($conf, strpos($conf, "=") + 1);
        $optconfig[$var] = $val;
    }
} elseif ($argc > 1 && is_numeric($argv[1]) && $argv[1] >= 0) {
    $idconfig = $argv[1];
} else {
    $idconfig = 1;
}

if ($dynamic_idconfig = intval($agi->get_variable("IDCONF", true))) {
    $idconfig = $dynamic_idconfig;
}

if ($argc > 2 && strlen($argv[2]) > 0 && $argv[2] == 'did')                      $mode = 'did';
elseif ($argc > 2 && strlen($argv[2]) > 0 && $argv[2] == 'callback')             $mode = 'callback';
elseif ($argc > 2 && strlen($argv[2]) > 0 && $argv[2] == 'cid-callback')         $mode = 'cid-callback';
elseif ($argc > 2 && strlen($argv[2]) > 0 && $argv[2] == 'cid-prompt-callback')  $mode = 'cid-prompt-callback';
elseif ($argc > 2 && strlen($argv[2]) > 0 && $argv[2] == 'all-callback')         $mode = 'all-callback';
elseif ($argc > 2 && strlen($argv[2]) > 0 && $argv[2] == 'voucher')              $mode = 'voucher';
elseif ($argc > 2 && strlen($argv[2]) > 0 && $argv[2] == 'campaign-callback')    $mode = 'campaign-callback';
elseif ($argc > 2 && strlen($argv[2]) > 0 && $argv[2] == 'conference-moderator') $mode = 'conference-moderator';
elseif ($argc > 2 && strlen($argv[2]) > 0 && $argv[2] == 'conference-member')    $mode = 'conference-member';
else                                                                             $mode = 'standard';

// get the area code for the cid-callback, all-callback and cid-prompt-callback
if ($argc > 3 && strlen($argv[3]) > 0) {
    $caller_areacode = $argv[3];
}

if ($argc > 4 && strlen($argv[4]) > 0) {
    $groupid = $argv[4];
    $A2B->group_mode = true;
    $A2B->group_id = $groupid;
}

if ($argc > 5 && strlen($argv[5]) > 0) {
    $cid_1st_leg_tariff_id = $argv[5];
}

$A2B = new A2Billing();
$A2B->load_conf($agi, NULL, 0, $idconfig, $optconfig);
$A2B->mode = $mode;
$A2B->G_startime = $G_startime;

$A2B->debug(INFO, $agi, __FILE__, __LINE__, "IDCONFIG : $idconfig");
$A2B->debug(INFO, $agi, __FILE__, __LINE__, "MODE : $mode");

$A2B->CC_TESTING = isset($A2B->agiconfig['debugshell']) && $A2B->agiconfig['debugshell'];
//$A2B->CC_TESTING = true;

define("DB_TYPE", isset($A2B->config["database"]['dbtype']) ? $A2B->config["database"]['dbtype'] : null);
define("SMTP_SERVER", isset($A2B->config['global']['smtp_server']) ? $A2B->config['global']['smtp_server'] : null);
define("SMTP_HOST", isset($A2B->config['global']['smtp_host']) ? $A2B->config['global']['smtp_host'] : null);
define("SMTP_USERNAME", isset($A2B->config['global']['smtp_username']) ? $A2B->config['global']['smtp_username'] : null);
define("SMTP_PASSWORD", isset($A2B->config['global']['smtp_password']) ? $A2B->config['global']['smtp_password'] : null);

// Print header
$A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "AGI Request:\n" . print_r($agi->request, true));
$A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[INFO : $agi_version]");

/* GET THE AGI PARAMETER */
$A2B->get_agi_request_parameter($agi);

if (!$A2B->DbConnect()) {
    $agi->stream_file('prepaid-final', '#');
    exit;
}

define("WRITELOG_QUERY", true);
$instance_table = new Table();
$A2B->set_instance_table($instance_table);

//GET CURRENCIES FROM DATABASE
$QUERY = "SELECT id, currency, name, value FROM cc_currencies ORDER BY id";
$result = $A2B->instance_table->SQLExec($A2B->DBHandle, $QUERY, 1, 300);

if (is_array($result)) {
    $num_cur = count($result);
    for ($i = 0; $i < $num_cur; $i++) {
        $currencies_list[$result[$i][1]] = array(1=>$result[$i][2], 2=>$result[$i][3]);
    }
}

$RateEngine = new RateEngine();

if ($A2B->CC_TESTING) {
    $RateEngine->debug_st = 1;
    $accountcode = '2222222222';
}

if ($mode == 'standard') {
    if ($A2B->agiconfig['answer_call'] == 1) {
        $A2B->debug(INFO, $agi, __FILE__, __LINE__, '[ANSWER CALL]');
        $agi->answer();
        $status_channel = 6;
    } else {
        $A2B->debug(INFO, $agi, __FILE__, __LINE__, '[NO ANSWER CALL]');
        $status_channel = 4;
    }

    $A2B->play_menulanguage($agi);

    // Play intro message
    if (strlen($A2B->agiconfig['intro_prompt']) > 0) {
        $agi->stream_file($A2B->agiconfig['intro_prompt'], '#');
    }

    $cia_res = $A2B->callingcard_ivr_authenticate($agi);
    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[TRY : callingcard_ivr_authenticate]");

    // CALL AUTHENTICATE AND WE HAVE ENOUGH CREDIT TO GO AHEAD
    if ($cia_res == 0) {
        // RE-SET THE CALLERID
        $A2B->callingcard_auto_setcallerid($agi);

        for ($i = 0; $i < $A2B->agiconfig['number_try']; $i++) {
            $RateEngine->Reinit();
            $A2B->Reinit();

            // RETRIEVE THE CHANNEL STATUS AND LOG : STATUS - CREIT - MIN_CREDIT_2CALL
            $stat_channel = $agi->channel_status($A2B->channel);
            $A2B->debug(INFO, $agi, __FILE__, __LINE__, '[CHANNEL STATUS : ' . $stat_channel["result"] . ' = ' . $stat_channel["data"] . ']' .
                "\n[CREDIT : " . $A2B->credit . "][CREDIT MIN_CREDIT_2CALL : " . $A2B->agiconfig['min_credit_2call'] . "]");

            // CHECK IF THE CHANNEL IS UP
            if (($A2B->agiconfig['answer_call'] == 1) && ($stat_channel["result"] != $status_channel) && ($A2B->CC_TESTING != 1)) {
                if ($A2B->set_inuse == 1) $A2B->callingcard_acct_start_inuse($agi, 0);
                $A2B->write_log("[STOP - EXIT]", 0);
                exit();
            }

            // CREATE A DIFFERENT UNIQUEID FOR EACH TRY
            if ($i > 0) {
                $A2B->uniqueid = $A2B->uniqueid + 1000000000;
            }

            if ($A2B->agiconfig['ivr_enable_locking_option'] == 1) {
                $QUERY = "SELECT block, lock_pin FROM cc_card WHERE username = '{$A2B->username}'";
                $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[QUERY] : " . $QUERY);
                $result = $A2B->instance_table->SQLExec($A2B->DBHandle, $QUERY);

                // Check if the locking option is enabled for this account
                if ($result[0][0] == 1 && strlen($result[0][1]) > 0) {
                    $try = 0;
                    do {
                        $return = FALSE;
                        $res_dtmf = $agi->get_data('prepaid-enter-pin-lock', 3000, 10, '#'); //Please enter your locking code
                        if ($res_dtmf['result'] != $result[0][1]) {
                            $agi->say_digits($res_dtmf['result']);
                            if (strlen($res_dtmf['result']) > 0)
                                $agi->stream_file('prepaid-no-pin-lock', '#');
                            $try++;
                            $return = TRUE;
                        }
                        if ($try > 3) {
                            if ($A2B->set_inuse == 1)
                                $A2B->callingcard_acct_start_inuse($agi, 0);
                            $agi->hangup();
                            exit();
                        }
                    } while ($return);
                }
            }

            // Feature to switch the Callplan from a customer : callplan_deck_minute_threshold
            $A2B->deck_switch($agi);

            if (!$A2B->enough_credit_to_call() && $A2B->agiconfig['jump_voucher_if_min_credit'] == 1) {

                $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[NOTENOUGHCREDIT - Refill with vouchert]");
                $vou_res = $A2B->refill_card_with_voucher($agi, 2);
                if ($vou_res == 1) {
                    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[ADDED CREDIT - refill_card_withvoucher Success] ");
                } else {
                    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[NOTENOUGHCREDIT - refill_card_withvoucher fail] ");
                }
            }

            if ($A2B->agiconfig['ivr_enable_account_information'] == 1) {
                $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, " [GET ACCOUNT INFORMATION]");
                $res_dtmf = $agi->get_data('prepaid-press4-info', 5000, 1, '#'); //Press 4 to get information about your account
                if ($res_dtmf['result'] == "4") {

                    $QUERY = "SELECT UNIX_TIMESTAMP(c.lastuse) as lastuse, UNIX_TIMESTAMP(c.lock_date) as lock_date, UNIX_TIMESTAMP(c.firstusedate) as firstuse
                                FROM cc_card c
                                WHERE username = '{$A2B->username}'
                                LIMIT 1";
                    $result = $A2B->instance_table->SQLExec($A2B->DBHandle, $QUERY);
                    $card_info = $result[0];
                    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[QUERY] : " . $QUERY);

                    if (is_array($card_info)) {
                        $try = 0;
                        do {
                            $try++;
                            $return = FALSE;

                            # INFORMATION MENU
                            $info_menu['1'] = 'prepaid-press1-listen-lastcall'; //Press 1 to listen the time and duration of the last call
                            $info_menu['2'] = 'prepaid-press2-listen-accountlocked'; //Press 2 to time and date when the account last has been locked
                            $info_menu['3'] = 'prepaid-press3-listen-firstuse'; //Press 3 to date of when the account was first in use
                            $info_menu['9'] = 'prepaid-press9-listen-exit-infomenu'; //Press 9 to exit information menu
                            $info_menu['*'] = 'prepaid-pressdisconnect'; //Press * to disconnect
                            //================================================================================================================
                            $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[INFORMATION MENU]");
                            $res_dtmf = $agi->menu($info_menu, 5000);

                            switch ($res_dtmf) {
                            case 1 :
                                $QUERY = "SELECT starttime FROM cc_call
                                    WHERE card_id = {$A2B->id_card} ORDER BY starttime DESC LIMIT 1";
                                $result = $A2B->instance_table->SQLExec($A2B->DBHandle, $QUERY);
                                $lastcall_info = $result[0];
                                if (is_array($lastcall_info)) {
                                    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[INFORMATION MENU]:[OPTION 1]");
                                    $agi->stream_file('prepaid-lastcall', '#'); //Your last call was made
                                    $agi->exec("SayUnixTime {$card_info['lastuse']}");
                                    $agi->stream_file('prepaid-call-duration', '#'); //the duration of the call was
                                    $agi->say_number($card_info['sessiontime']);
                                    $agi->stream_file('seconds', '#');
                                } else {
                                    $agi->stream_file('prepaid-no-call', '#'); //No call has been made
                                }
                                $return = TRUE;
                                break;
                            case 2 :
                                if ($card_info['lock_date']) {
                                    $agi->stream_file('prepaid-account-has-locked', '#'); //Your Account has been locked the
                                    $agi->exec("SayUnixTime {$card_info['lock_date']}");
                                } else {
                                    $agi->stream_file('prepaid-account-nolocked', '#'); //Your account is not locked
                                }
                                $return = TRUE;
                                break;
                            case 3 :
                                $agi->stream_file('prepaid-account-firstused', '#'); //Your Account has been used for the first time the
                                $agi->exec("SayUnixTime {$card_info['firstuse']}");
                                $return = TRUE;
                                break;
                            case 9 :
                                $return = FALSE;
                                break;
                            case '*' :
                                $agi->stream_file('prepaid-final', '#');
                                if ($A2B->set_inuse == 1)
                                    $A2B->callingcard_acct_start_inuse($agi, 0);
                                $agi->hangup();
                                exit();
                            }
                            $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[TRY : $try]");
                        } while ($return && $try < 0);
                    }
                }
            }

            if ($A2B->agiconfig['ivr_enable_locking_option'] == 1) {
                $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[LOCKING OPTION]");

                $return = FALSE;
                $res_dtmf = $agi->get_data('prepaid-press5-lock', 5000, 1, '#'); //Press 5 to lock your account

                if ($res_dtmf['result'] == 5) {
                    for ($ind_lock = 0; $ind_lock <= 3; $ind_lock++) {
                        $res_dtmf = $agi->get_data('prepaid-enter-code-lock-account', 3000, 10, '#'); //Please, Enter the code you want to use to lock your
                        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[res_dtmf = " . $res_dtmf['result'] . "]");

                        if (strlen($res_dtmf['result']) > 0 && is_int(intval($res_dtmf['result']))) {
                            break;
                        }
                    }

                    if (strlen($res_dtmf['result']) > 0 && is_int(intval($res_dtmf['result']))) {

                        $agi->stream_file('prepaid-your-locking-is', '#'); //Your locking code is
                        $agi->say_digits($res_dtmf['result']);
                        $lock_pin = $res_dtmf['result'];

                        if (strlen($lock_pin) > 0) {
                            # MENU OF LOCK
                            $lock_menu['1'] = 'prepaid-listen-press1-confirmation-lock'; //Do you want to proceed and lock your account, then press 1 ?
                            $lock_menu['9'] = 'prepaid-press9-listen-exit-lockmenu'; //Press 9 to exit lock menu
                            $lock_menu['*'] = 'prepaid-pressdisconnect'; //Press * to disconnect
                            //================================================================================================================
                            $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[MENU OF LOCK]");
                            $res_dtmf = $agi->menu($lock_menu, 5000);

                            switch ($res_dtmf) {
                            case 1 :
                                $QUERY = "UPDATE cc_card SET block = 1, lock_pin = '{$lock_pin}', lock_date = NOW() WHERE username = '{$A2B->username}'";
                                $A2B->instance_table->SQLExec($A2B->DBHandle, $QUERY);
                                $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[QUERY]:[$QUERY]");
                                $agi->stream_file('prepaid-locking-accepted', '#'); // Your locking code has been accepted
                                $return = TRUE;
                                break;
                            case 9 :
                                $return = FALSE;
                                break;
                            case '*' :
                                $agi->stream_file('prepaid-final', '#');
                                if ($A2B->set_inuse == 1)
                                    $A2B->callingcard_acct_start_inuse($agi, 0);
                                $agi->hangup();
                                exit();
                            }
                        }
                    }
                }
            }

            $A2B->debug(INFO, $agi, __FILE__, __LINE__, "TARIFF ID->". $A2B->tariff);

            if (!$A2B->enough_credit_to_call()) {
                // SAY TO THE CALLER THAT IT DEOSNT HAVE ENOUGH CREDIT TO MAKE A CALL
                $prompt = "prepaid-no-enough-credit-stop";
                $agi->stream_file($prompt, '#');
                $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[STOP STREAM FILE $prompt]");

                if (($A2B->agiconfig['notenoughcredit_cardnumber'] == 1) && (($i + 1)< $A2B->agiconfig['number_try'])) {

                    if ($A2B->set_inuse == 1) {
                        $A2B->callingcard_acct_start_inuse($agi, 0);
                    }

                    $A2B->agiconfig['cid_enable'] = 0;
                    $A2B->agiconfig['use_dnid'] = 0;
                    $A2B->agiconfig['cid_auto_assign_card_to_cid'] = 0;
                    $A2B->accountcode = '';
                    $A2B->username = '';
                    $A2B->ask_other_cardnumber = 1;

                    $cia_res = $A2B->callingcard_ivr_authenticate($agi);
                    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[NOTENOUGHCREDIT_CARDNUMBER - TRY : callingcard_ivr_authenticate]");
                    if ($cia_res != 0) break;

                    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[NOTENOUGHCREDIT_CARDNUMBER - callingcard_acct_start_inuse]");
                    $A2B->callingcard_acct_start_inuse($agi, 1);
                    continue;

                } else {
                    $send_reminder = 1;
                    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[SET MAIL REMINDER - NOT ENOUGH CREDIT]");
                    break;
                }
            }

            $A2B->dnid = $A2B->orig_dnid;
            $A2B->extension = $A2B->orig_ext;

            if ($A2B->agiconfig['ivr_voucher'] == 1) {
                $res_dtmf = $agi->get_data('prepaid-refill_card_with_voucher', 5000, 1);
                $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "RES REFILL CARD VOUCHER DTMF : " . $res_dtmf["result"]);
                $A2B->ivr_voucher = $res_dtmf["result"];
                if ((isset($A2B->ivr_voucher)) && ($A2B->ivr_voucher == $A2B->agiconfig['ivr_voucher_prefixe'])) {
                    $vou_res = $A2B->refill_card_with_voucher($agi, $i);
                }
            }

            if ($A2B->agiconfig['ivr_enable_ivr_speeddial'] == 1) {
                $A2B->debug(INFO, $agi, __FILE__, __LINE__, "[IVR SPEED DIAL]");
                do {
                    $return_mainmenu = FALSE;

                    $res_dtmf = $agi->get_data("prepaid-press9-new-speeddial", 5000, 1); //Press 9 to add a new Speed Dial

                    if ($res_dtmf["result"] == 9) {
                        $try_enter_speeddial = 0;
                        do {
                            $try_enter_speeddial++;
                            $return_enter_speeddial = FALSE;
                            $res_dtmf = $agi->get_data("prepaid-enter-speeddial", 3000, 1); //Please enter the speeddial number
                            $speeddial_number = $res_dtmf['result'];
                            $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "SPEEDDIAL DTMF : " . $speeddial_number);

                            if (!empty($speeddial_number) && is_numeric($speeddial_number) && $speeddial_number >= 0) {
                                $action = 'insert';
                                $QUERY = "SELECT cc_speeddial.phone, cc_speeddial.id
                                            FROM cc_speeddial, cc_card WHERE cc_speeddial.id_cc_card = cc_card.id
                                            AND cc_card.id = " . $A2B->id_card . " AND cc_speeddial.speeddial = " . $speeddial_number . "";
                                $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, $QUERY);
                                $result = $A2B->instance_table->SQLExec($A2B->DBHandle, $QUERY);
                                $id_speeddial = $result[0][1];
                                if (is_array($result)) {
                                    $agi->say_number($speeddial_number);
                                    $agi->stream_file("prepaid-is-used-for", "#");
                                    $agi->say_digits($result[0][0]);
                                        $res_dtmf = $agi->get_data("prepaid-press1-change-speeddial", 3000, 1); //if you want to change it press 1 or an other key to enter an other speed dial number.
                                    if ($res_dtmf['result'] != 1) {
                                        $return_mainmenu = TRUE;
                                        break;
                                    } else {
                                        $action = 'update';
                                    }
                                }
                                $try_phonenumber = 0;
                                do {
                                    $try_phonenumber++;
                                    $return_phonenumber = FALSE;
                                    $res_dtmf = $agi->get_data("prepaid-phonenumber-to-speeddial", 5000, 30, "#"); //Please enter the phone number followed by the pound key
                                    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "PHONENUMBER TO SPEEDDIAL DTMF : " . $res_dtmf['result']);

                                    if (!empty($res_dtmf["result"]) && is_numeric($res_dtmf["result"]) && $res_dtmf["result"] > 0) break;

                                    if ($try_phonenumber < 3) $return_phonenumber = TRUE;
                                    else $return_mainmenu;

                                } while ($return_phonenumber);

                                if (!empty($res_dtmf["result"]) && is_numeric($res_dtmf["result"]) && $res_dtmf["result"] > 0) {
                                    $assigned_number = $res_dtmf["result"];
                                    $agi->stream_file("prepaid-the-phonenumber", "#"); //The phone number
                                    $agi->say_digits($assigned_number, "#");
                                    $agi->stream_file("prepaid-assigned-speeddial", "#"); //will be assigned to the speed dial number
                                    $agi->say_number($speeddial_number, "#");

                                    $res_dtmf = $agi->get_data("prepaid-press1-add-speeddial", 3000, 1); //If you want to proceed please press 1 or press an other key to cancel ?
                                    if ($res_dtmf['result'] == 1) {
                                        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "ACTION : " . $action);
                                        if ($action == 'insert')
                                            $QUERY = "INSERT INTO cc_speeddial (id_cc_card, phone, speeddial) VALUES (" . $A2B->id_card . ", " . $assigned_number . ", '" . $speeddial_number . "')";
                                        elseif ($action == 'update')
                                            $QUERY = "UPDATE cc_speeddial SET phone = '" . $assigned_number . "' WHERE id = " . $id_speeddial;

                                        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, $QUERY);
                                        $result = $A2B->instance_table->SQLExec($A2B->DBHandle, $QUERY);
                                        $agi->stream_file("prepaid-speeddial-saved"); //The speed dial number has been successfully saved.
                                        $return_mainmenu = TRUE;
                                        break;
                                    }
                                }
                            }

                            if ($try_enter_speeddial < 3) {
                                $return_enter_speeddial = TRUE;
                            } else {
                                $return_mainmenu = TRUE;
                            }
                        } while ($return_enter_speeddial);
                    }
                } while ($return_mainmenu);
            }

            if ($A2B->agiconfig['sip_iax_friends'] == 1) {

                if ($A2B->agiconfig['sip_iax_pstn_direct_call'] == 1) {

                    if ($A2B->agiconfig['use_dnid'] == 1 && !in_array($A2B->dnid, $A2B->agiconfig['no_auth_dnid']) && strlen($A2B->dnid) > 2 && $i == 0) {

                        $A2B->destination = $A2B->dnid;

                    } elseif ($i == 0) {
                        $prompt_enter_dest = $A2B->agiconfig['file_conf_enter_destination'];
                        $res_dtmf = $agi->get_data($prompt_enter_dest, 4000, 20);
                        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "RES sip_iax_pstndirect_call DTMF : " . $res_dtmf["result"]);
                        $A2B->destination = $res_dtmf["result"];
                    }

                    if ((strlen($A2B->destination) > 0)
                        && (strlen($A2B->agiconfig['sip_iax_pstn_direct_call_prefix']) > 0)
                        && (strncmp($A2B->agiconfig['sip_iax_pstn_direct_call_prefix'], $A2B->destination, strlen($A2B->agiconfig['sip_iax_pstn_direct_call_prefix'])) == 0)) {

                        $A2B->dnid = $A2B->destination;
                        $A2B->sip_iax_buddy = $A2B->agiconfig['sip_iax_pstn_direct_call_prefix'];
                        $A2B->agiconfig['use_dnid'] = 1;
                        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "SIP 1. IAX - dnid : " . $A2B->dnid . " - " . strlen($A2B->agiconfig['sip_iax_pstn_direct_call_prefix']));
                        $A2B->dnid = substr($A2B->dnid, strlen($A2B->agiconfig['sip_iax_pstn_direct_call_prefix']));
                        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "SIP 2. IAX - dnid : " . $A2B->dnid);

                    } elseif (strlen($A2B->destination) > 0) {
                        $A2B->dnid = $A2B->destination;
                        $A2B->agiconfig['use_dnid'] = 1;
                        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "TRUNK - dnid : " . $A2B->dnid . " (" . $A2B->agiconfig['use_dnid'] . ")");
                    }
                } else {
                    $res_dtmf = $agi->get_data('prepaid-sipiax-press9', 4000, 1);
                    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "RES SIP_IAX_FRIEND DTMF : " . $res_dtmf["result"]);
                    $A2B->sip_iax_buddy = $res_dtmf["result"];
                }
            }

            if (strlen($A2B->sip_iax_buddy) > 0 || ($A2B->sip_iax_buddy == $A2B->agiconfig['sip_iax_pstn_direct_call_prefix'])) {

                $A2B->debug(INFO, $agi, __FILE__, __LINE__, 'CALL SIP_IAX_BUDDY');
                $cia_res = $A2B->call_sip_iax_buddy($agi, $RateEngine, $i);

            } else {

                $ans = $A2B->callingcard_ivr_authorize($agi, $RateEngine, $i, true);
                $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, 'ANSWER fct callingcard_ivr authorize:> ' . $ans);

                if ($ans == 1) {
                    // PERFORM THE CALL
                    $result_callperf = $RateEngine->rate_engine_performcall($agi, $A2B->destination, $A2B);

                    if (!$result_callperf) {
                        $prompt = "prepaid-dest-unreachable";
                        $agi->stream_file($prompt, '#');
                    }
                    // INSERT CDR & UPDATE SYSTEM
                    $RateEngine->rate_engine_updatesystem($A2B, $agi, $A2B->destination);

                    if ($A2B->agiconfig['say_balance_after_call'] == 1) {
                        $A2B->fct_say_balance($agi, $A2B->credit);
                    }
                    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, '[a2billing account stop]');

                } elseif ($ans == "2DID") {

                    $A2B->debug(INFO, $agi, __FILE__, __LINE__, "[ CALL OF THE SYSTEM - [DID=" . $A2B->destination . "]");

                    $QUERY = "SELECT cc_did.id, cc_did_destination.id, billingtype, tariff, destination, voip_call, username, useralias, connection_charge, ".
                            " selling_rate, did, aleg_carrier_connect_charge, aleg_carrier_cost_min, aleg_retail_connect_charge, aleg_retail_cost_min, ".
                            " aleg_carrier_initblock, aleg_carrier_increment, aleg_retail_initblock, aleg_retail_increment, ".
                            " aleg_timeinterval, ".
                            " aleg_carrier_connect_charge_offp, aleg_carrier_cost_min_offp, aleg_retail_connect_charge_offp, aleg_retail_cost_min_offp, ".
                            " aleg_carrier_initblock_offp, aleg_carrier_increment_offp, aleg_retail_initblock_offp, aleg_retail_increment_offp, ".
                            " cc_card.id ".
                            " FROM cc_did, cc_did_destination, cc_card ".
                            " WHERE id_cc_did=cc_did.id AND cc_card.status=1 AND cc_card.id=id_cc_card and cc_did_destination.activated=1 ".
                            " AND cc_did.activated=1 AND did='" . $A2B->destination . "' ".
                            " AND cc_did.startingdate <= CURRENT_TIMESTAMP ".
                            " AND (cc_did.expirationdate > CURRENT_TIMESTAMP OR cc_did.expirationdate IS NULL ".
                            " AND cc_did_destination.validated = 1 ";
                    if ($A2B->config["database"]['dbtype'] == "mysql") {
                        $QUERY .= " OR cc_did.expirationdate = '0000-00-00 00:00:00'";
                    }
                    $QUERY .= ") ORDER BY priority ASC";

                    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, $QUERY);
                    $result = $A2B->instance_table->SQLExec($A2B->DBHandle, $QUERY);

                    if (is_array($result)) {
                        //On Net
                        $A2B->call_2did($agi, $RateEngine, $result);
                        if ($A2B->set_inuse == 1)
                            $A2B->callingcard_acct_start_inuse($agi, 0);
                    }
                }
            }
            $A2B->agiconfig['use_dnid'] = 0;
        }//END FOR

    } else {
        $A2B->debug(WARN, $agi, __FILE__, __LINE__, "[NO AUTH (CN:".$A2B->accountcode.", cia_res:".$cia_res.", CREDIT:".$A2B->credit.")]");
    }
    # SAY GOODBYE
    if ($A2B->agiconfig['say_goodbye'] == 1) $agi->stream_file('prepaid-final', '#');

// MODE DID
} elseif ($mode == 'did') {

    if ($A2B->agiconfig['answer_call'] == 1) {
        $A2B->debug(INFO, $agi, __FILE__, __LINE__, '[ANSWER CALL]');
        $agi->answer();
    } else {
        $A2B->debug(INFO, $agi, __FILE__, __LINE__, '[NO ANSWER CALL]');
    }

    $RateEngine->Reinit();
    $A2B->Reinit();

    $mydnid = $A2B->orig_ext;

    if (strlen($mydnid) > 0) {
        $A2B->debug(INFO, $agi, __FILE__, __LINE__, "[DID CALL - [CallerID=" . $A2B->CallerID . "]:[DID=" . $mydnid . "]");

        $QUERY = "SELECT cc_did.id, cc_did_destination.id, billingtype, tariff, destination, voip_call, username, useralias, connection_charge, ".
            " selling_rate, did, aleg_carrier_connect_charge, aleg_carrier_cost_min, aleg_retail_connect_charge, aleg_retail_cost_min, ".
            " aleg_carrier_initblock, aleg_carrier_increment, aleg_retail_initblock, aleg_retail_increment, ".
            " aleg_timeinterval, ".
            " aleg_carrier_connect_charge_offp, aleg_carrier_cost_min_offp, aleg_retail_connect_charge_offp, aleg_retail_cost_min_offp, ".
            " aleg_carrier_initblock_offp, aleg_carrier_increment_offp, aleg_retail_initblock_offp, aleg_retail_increment_offp ".
            " FROM cc_did, cc_did_destination, cc_card ".
            " WHERE id_cc_did=cc_did.id AND cc_card.status=1 AND cc_card.id=id_cc_card AND cc_did_destination.activated=1 ".
            " AND cc_did.activated=1 AND did='$mydnid' ".
            " AND cc_did.startingdate<= CURRENT_TIMESTAMP AND (cc_did.expirationdate > CURRENT_TIMESTAMP OR cc_did.expirationdate IS NULL ".
            " AND cc_did_destination.validated=1";
        if ($A2B->config["database"]['dbtype'] != "postgres") {
            // MYSQL
            $QUERY .= " OR cc_did.expirationdate = '0000-00-00 00:00:00'";
        }
        $QUERY .= ") ORDER BY priority ASC";

        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, $QUERY);
        $result = $A2B->instance_table->SQLExec($A2B->DBHandle, $QUERY);
        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, $result);

        if (is_array($result)) {
            //Off Net
            $A2B->call_did($agi, $RateEngine, $result);
            if ($A2B->set_inuse == 1) $A2B->callingcard_acct_start_inuse($agi, 0);
        }
    }

// MOVE VOUCHER TO LET CUSTOMER ONLY REFILL
} elseif ($mode == 'voucher') {

    if ($A2B->agiconfig['answer_call'] == 1) {
        $A2B->debug(INFO, $agi, __FILE__, __LINE__, '[ANSWER CALL]');
        $agi->answer();
        $status_channel = 6;
    } else {
        $A2B->debug(INFO, $agi, __FILE__, __LINE__, '[NO ANSWER CALL]');
        $status_channel = 4;
    }

    $A2B->play_menulanguage($agi);
    # PLAY INTRO MESSAGE
    if (strlen($A2B->agiconfig['intro_prompt']) > 0) $agi->stream_file($A2B->agiconfig['intro_prompt'], '#');

    if (strlen($A2B->CallerID) > 1 && is_numeric($A2B->CallerID)) {
        $A2B->CallerID = $caller_areacode . $A2B->CallerID;
    }
    $cia_res = $A2B->callingcard_ivr_authenticate($agi);
    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[TRY : callingcard_ivr_authenticate]");

    // CALL AUTHENTICATE AND WE HAVE ENOUGH CREDIT TO GO AHEAD
    if ($A2B->id_card > 0) {
        for ($k = 0; $k < 3; $k++) {
            $vou_res = $A2B->refill_card_with_voucher($agi, null);
            $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "VOUCHER RESULT = $vou_res");
            if ($vou_res == 1) {
                break;
            } else {
                $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[NOTENOUGHCREDIT - refill_card_withvoucher fail] ");
            }
        }
    }

    // SAY GOODBYE
    if ($A2B->agiconfig['say_goodbye'] == 1) $agi->stream_file('prepaid-final', '#');

    $agi->hangup();
    if ($A2B->set_inuse == 1) $A2B->callingcard_acct_start_inuse($agi, 0);
    $A2B->write_log("[STOP - EXIT]", 0);
    exit();

// MODE CAMPAIGN-CALLBACK
} elseif ($mode == 'campaign-callback') {
    $A2B->update_callback_campaign($agi);

// MODE cid-callback & cid-prompt-callback
} elseif ($mode == 'cid-callback' || $mode == 'cid-prompt-callback') {

    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, '[MODE : ' . strtoupper($mode) . ' - ' . $A2B->CallerID . ']');

    if ($A2B->agiconfig['answer_call'] == 1 && $mode == 'cid-callback') {
        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, '[HANGUP CLI CALLBACK TRIGGER]');
        $agi->hangup();
    } elseif ($mode == 'cid-prompt-callback') {
        $agi->answer();
    } else {
        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, '[CLI CALLBACK TRIGGER RINGING]');
    }

    // MAKE THE AUTHENTICATION ACCORDING TO THE CALLERID
    $A2B->agiconfig['cid_enable'] = 1;
    $A2B->agiconfig['cid_askpincode_ifnot_callerid'] = 0;
    $A2B->agiconfig['say_balance_after_auth'] = 0;

    if (strlen($A2B->CallerID) > 1 && is_numeric($A2B->CallerID)) {

        /* WE START ;) */
        $cia_res = $A2B->callingcard_ivr_authenticate($agi);
        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[TRY : callingcard_ivr_authenticate]");
        if ($cia_res == 0) {

            $RateEngine = new RateEngine();

            // Apply 1st leg tariff override if param was passed in
            if (strlen($cid_1st_leg_tariff_id) > 0) {
                $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, 'Callback Tariff override for 1st Leg only. New tariff is ' . $cid_1st_leg_tariff_id);
                $A2B->tariff = $cid_1st_leg_tariff_id;
            }

            $A2B->agiconfig['use_dnid'] = 1;
            $A2B->agiconfig['say_timetocall'] = 0;

            // We arent removing leading zero in front of the callerID if needed this might be done over the dialplan
            $A2B->extension = $A2B->dnid = $A2B->destination = $caller_areacode . $A2B->CallerID;

            $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, '[destination: - ' . $A2B->destination . ']');

            // LOOKUP RATE : FIND A RATE FOR THIS DESTINATION
            $resfindrate = $RateEngine->rate_engine_findrates($A2B, $A2B->destination, $A2B->tariff);
            $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, '[resfindrate: - ' . $resfindrate . ']');

            // IF FIND RATE
            if ($resfindrate != 0) {
                //$RateEngine->debug_st = 1;
                $res_all_calcultimeout = $RateEngine->rate_engine_all_calcultimeout($A2B, $A2B->credit);
                //echo("RES_ALL_CALCULTIMEOUT ::> $res_all_calcultimeout");

                if ($res_all_calcultimeout) {

                    $CALLING_VAR = '';
                    $MODE_VAR = "MODE=CID";
                    if ($mode == 'cid-prompt-callback') {

                        $MODE_VAR = "MODE=CID-PROMPT";

                        $try = 0;
                        do {
                            $try++;
                            $return = TRUE;

                            // GET THE DESTINATION NUMBER
                            $prompt_enter_dest = $A2B->agiconfig['file_conf_enter_destination'];
                            $res_dtmf = $agi->get_data($prompt_enter_dest, 6000, 20);
                            $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "RES DTMF : " . $res_dtmf["result"]);
                            $outbound_destination = $res_dtmf["result"];

                            if ($A2B->agiconfig['cid_prompt_callback_confirm_phonenumber'] == 1) {
                                $agi->stream_file('prepaid-the-number-u-dialed-is', '#'); //Your locking code is
                                $agi->say_digits($outbound_destination);

                                $subtry = 0;
                                do {
                                    $subtry++;
                                    //= CONFIRM THE DESTINATION NUMBER
                                    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[MENU OF CONFIRM (" . $res_dtmf["result"] . ")]");
                                    $res_dtmf = $agi->get_data('prepaid-re-enter-press1-confirm', 4000, 1);
                                    if ($subtry >= 3) {
                                        if ($A2B->set_inuse == 1)
                                            $A2B->callingcard_acct_start_inuse($agi, 0);
                                        $agi->hangup();
                                        exit();
                                    }
                                } while ($res_dtmf["result"] != '1' && $res_dtmf["result"] != '2');

                                // Check the result
                                if ($res_dtmf["result"] == '1') {
                                    $return = TRUE;
                                } elseif ($res_dtmf["result"] == '2') {
                                    $return = FALSE;
                                }

                                $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[TRY : $try]");
                            } else {
                                $return = FALSE;
                            }
                        } while ($return && $try < 3);

                        if (strlen($outbound_destination) <= 0) {
                            if ($A2B->set_inuse == 1)
                                $A2B->callingcard_acct_start_inuse($agi, 0);
                            $agi->hangup();
                            exit();
                        }

                        $CALLING_VAR = "CALLING=" . $outbound_destination;
                    } // if ($mode == 'cid-prompt-callback')

                    // MAKE THE CALL
                    if ($RateEngine->ratecard_obj[0][34] != '-1') {
                        $usetrunk = 34;
                        $usetrunk_failover = 1;
                        $RateEngine->usedtrunk = $RateEngine->ratecard_obj[0][34];
                    } else {
                        $usetrunk = 29;
                        $RateEngine->usedtrunk = $RateEngine->ratecard_obj[0][29];
                        $usetrunk_failover = 0;
                    }

                    $prefix         = $RateEngine->ratecard_obj[0][$usetrunk + 1];
                    $tech           = $RateEngine->ratecard_obj[0][$usetrunk + 2];
                    $ipaddress      = $RateEngine->ratecard_obj[0][$usetrunk + 3];
                    $removeprefix   = $RateEngine->ratecard_obj[0][$usetrunk + 4];
                    $timeout        = $RateEngine->ratecard_obj[0]['timeout'];
                    $callbackrate   = $RateEngine->ratecard_obj[0]['callbackrate'];
                    $failover_trunk = $RateEngine->ratecard_obj[0][40 + $usetrunk_failover];
                    $addparameter   = $RateEngine->ratecard_obj[0][42 + $usetrunk_failover];

                    $destination = $A2B->destination;
                    if (strncmp($destination, $removeprefix, strlen($removeprefix)) == 0) $destination= substr($destination, strlen($removeprefix));

                    $pos_dialingnumber = strpos($ipaddress, '%dialingnumber%');

                    $ipaddress = str_replace("%cardnumber%", $A2B->cardnumber, $ipaddress);
                    $ipaddress = str_replace("%dialingnumber%", $prefix . $destination, $ipaddress);

                    if ($pos_dialingnumber !== false) {
                        $dialstr = "$tech/$ipaddress";
                    } else {
                        if ($A2B->agiconfig['switchdialcommand'] == 1) {
                            $dialstr = "$tech/$prefix$destination@$ipaddress";
                        } else {
                            $dialstr = "$tech/$ipaddress/$prefix$destination";
                        }
                    }

                    //ADDITIONAL PARAMETER %dialingnumber%, %cardnumber%
                    if (strlen($addparameter) > 0) {
                        $addparameter = str_replace("%cardnumber%", $A2B->cardnumber, $addparameter);
                        $addparameter = str_replace("%dialingnumber%", $prefix . $destination, $addparameter);
                        $dialstr .= $addparameter;
                    }

                    $channel = $dialstr;
                    $exten = $A2B->config["callback"]['extension'];
                    if ($argc > 4 && strlen($argv[4]) > 0)
                        $exten = $argv[4];
                    $context = $A2B->config["callback"]['context_callback'];
                    $id_server_group = $A2B->config["callback"]['id_server_group'];
                    $priority = 1;
                    $timeout = $A2B->config["callback"]['timeout'] * 1000;
                    $callerid = $A2B->config["callback"]['callerid'];
                    $application = '';
                    $account = $A2B->accountcode;

                    $uniqueid = MDP_NUMERIC(5) . '-' . MDP_STRING(7);

                    $sep = ($A2B->config['global']['asterisk_version'] == "1_2" || $A2B->config['global']['asterisk_version'] == "1_4") ? '|' : ',';

                    $variable = "IDCONF=$idconfig" . $sep . "CALLED=" . $A2B->destination . $sep . $CALLING_VAR . $sep . $MODE_VAR . $sep . "CBID=$uniqueid" . $sep . "LEG=" . $A2B->username;

                    foreach ($callbackrate as $key => $value) {
                        $variable .= $sep . strtoupper($key) . '=' . $value;
                    }
                    //pass the tariff if it was passed in
                    if (strlen($cid_1st_leg_tariff_id) > 0) {
                        $variable .= $sep . 'TARIFF=' . $cid_1st_leg_tariff_id;
                    }

                    $status = 'PENDING';
                    $server_ip = 'localhost';
                    $num_attempt = 0;

                    if (is_numeric($A2B->config["callback"]['sec_wait_before_callback']) && $A2B->config["callback"]['sec_wait_before_callback'] >= 1) {
                        $sec_wait_before_callback = $A2B->config["callback"]['sec_wait_before_callback'];
                    } else {
                        $sec_wait_before_callback = 1;
                    }

                    $QUERY = " INSERT INTO cc_callback_spool (uniqueid, status, server_ip, num_attempt, channel, exten, context, priority, variable, id_server_group, callback_time, account, callerid, timeout) VALUES ('$uniqueid', '$status', '$server_ip', '$num_attempt', '$channel', '$exten', '$context', '$priority', '$variable', '$id_server_group', ADDDATE( CURRENT_TIMESTAMP, INTERVAL $sec_wait_before_callback SECOND ), '$account', '$callerid', '$timeout')";
                    $res = $A2B->DBHandle->Execute($QUERY);
                    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-ALL : INSERT CALLBACK REQUEST IN SPOOL : QUERY=$QUERY]");

                    if (!$res) {
                        $error_msg= "Cannot insert the callback request in the spool!";
                        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-ALL : CALLED=" . $A2B->destination . " | $error_msg]");
                    }

                } else {
                    $error_msg = 'Error : You don t have enough credit to call you back !!!';
                    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-CALLERID : CALLED=" . $A2B->destination . " | $error_msg]");
                }

            } else {
                $error_msg = 'Error : There is no route to call back your phonenumber !!!';
                $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-CALLERID : CALLED=" . $A2B->destination . " | $error_msg]");
            }

        } else {
            $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-CALLERID : CALLED=" . $A2B->destination . " | Authentication failed]");
        }

    } else {
        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-CALLERID : CALLED=" . $A2B->destination . " | error callerid]");
    }

} elseif ($mode == 'all-callback') {

    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, '[MODE : ALL-CALLBACK - ' . $A2B->CallerID . ']');

    // END
    if ($A2B->agiconfig['answer_call'] == 1) {
        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, '[HANGUP ALL CALLBACK TRIGGER]');
        $agi->hangup();
    } else {
        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, '[ALL CALLBACK TRIGGER RINGING]');
    }

    $A2B->credit = 1000;
    $A2B->tariff = $A2B->config["callback"]['all_callback_tariff'];

    if (strlen($A2B->CallerID) > 1 && is_numeric($A2B->CallerID)) {

        /* WE START ;) */
        if ($cia_res == 0) {

            $RateEngine = new RateEngine();
            // $RateEngine->webui = 0;
            // LOOKUP RATE : FIND A RATE FOR THIS DESTINATION

            $A2B->agiconfig['use_dnid'] = 1;
            $A2B->agiconfig['say_timetocall'] = 0;
            $A2B->agiconfig['say_balance_after_auth'] = 0;
            $A2B->extension = $A2B->dnid = $A2B->destination = $caller_areacode . $A2B->CallerID;

            $resfindrate = $RateEngine->rate_engine_findrates($A2B, $A2B->destination, $A2B->tariff);

            // IF FIND RATE
            if ($resfindrate != 0) {
                //$RateEngine->debug_st = 1;
                $res_all_calcultimeout = $RateEngine->rate_engine_all_calcultimeout($A2B, $A2B->credit);

                if ($res_all_calcultimeout) {
                    // MAKE THE CALL
                    if ($RateEngine->ratecard_obj[0][34] != '-1') {
                        $usetrunk = 34;
                        $usetrunk_failover = 1;
                        $RateEngine->usedtrunk = $RateEngine->ratecard_obj[$k][34];
                    } else {
                        $usetrunk = 29;
                        $RateEngine->usedtrunk = $RateEngine->ratecard_obj[$k][29];
                        $usetrunk_failover = 0;
                    }

                    $prefix         = $RateEngine->ratecard_obj[0][$usetrunk + 1];
                    $tech           = $RateEngine->ratecard_obj[0][$usetrunk + 2];
                    $ipaddress      = $RateEngine->ratecard_obj[0][$usetrunk + 3];
                    $removeprefix   = $RateEngine->ratecard_obj[0][$usetrunk + 4];
                    $timeout        = $RateEngine->ratecard_obj[0]['timeout'];
                    $failover_trunk = $RateEngine->ratecard_obj[0][40 + $usetrunk_failover];
                    $addparameter   = $RateEngine->ratecard_obj[0][42 + $usetrunk_failover];

                    $destination = $A2B->destination;
                    if (strncmp($destination, $removeprefix, strlen($removeprefix)) == 0) $destination= substr($destination, strlen($removeprefix));

                    $pos_dialingnumber = strpos($ipaddress, '%dialingnumber%');

                    $ipaddress = str_replace("%cardnumber%", $A2B->cardnumber, $ipaddress);
                    $ipaddress = str_replace("%dialingnumber%", $prefix . $destination, $ipaddress);

                    if ($pos_dialingnumber !== false) {
                        $dialstr = "$tech/$ipaddress";
                    } else {
                        if ($A2B->agiconfig['switchdialcommand'] == 1) {
                            $dialstr = "$tech/$prefix$destination@$ipaddress";
                        } else {
                            $dialstr = "$tech/$ipaddress/$prefix$destination";
                        }
                    }

                    //ADDITIONAL PARAMETER %dialingnumber%, %cardnumber%
                    if (strlen($addparameter) > 0) {
                        $addparameter = str_replace("%cardnumber%", $A2B->cardnumber, $addparameter);
                        $addparameter = str_replace("%dialingnumber%", $prefix . $destination, $addparameter);
                        $dialstr .= $addparameter;
                    }

                    $channel= $dialstr;
                    $exten = $A2B->config["callback"]['extension'];
                    if ($argc > 4 && strlen($argv[4]) > 0) $exten = $argv[4];
                    $context = $A2B->config["callback"]['context_callback'];
                    $id_server_group = $A2B->config["callback"]['id_server_group'];
                    $callerid = $A2B->config["callback"]['callerid'];
                    $priority = 1;
                    $timeout = $A2B->config["callback"]['timeout'] * 1000;
                    $application = '';
                    $account = $A2B->accountcode;

                    $uniqueid = MDP_NUMERIC(5) . '-' . MDP_STRING(7);

                    $sep = ($A2B->config['global']['asterisk_version'] == "1_2" || $A2B->config['global']['asterisk_version'] == "1_4") ? '|' : ',';

                    $variable = "IDCONF=$idconfig" . $sep . "CALLED=" . $A2B->destination . $sep . "MODE=ALL" . $sep . "CBID=$uniqueid" . $sep . "TARIFF=" . $A2B->tariff . $sep . "LEG=" . $A2B->username;

                    $status = 'PENDING';
                    $server_ip = 'localhost';
                    $num_attempt = 0;

                    if (is_numeric($A2B->config["callback"]['sec_wait_before_callback']) && $A2B->config["callback"]['sec_wait_before_callback'] >= 1) {
                        $sec_wait_before_callback = $A2B->config["callback"]['sec_wait_before_callback'];
                    } else {
                        $sec_wait_before_callback = 1;
                    }

                    $QUERY = " INSERT INTO cc_callback_spool (uniqueid, status, server_ip, num_attempt, channel, exten, context, priority, variable, id_server_group, callback_time, account, callerid, timeout) VALUES ('$uniqueid', '$status', '$server_ip', '$num_attempt', '$channel', '$exten', '$context', '$priority', '$variable', '$id_server_group', ADDDATE( CURRENT_TIMESTAMP, INTERVAL $sec_wait_before_callback SECOND ), '$account', '$callerid', '$timeout')";
                    $res = $A2B->DBHandle->Execute($QUERY);
                    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-ALL : INSERT CALLBACK REQUEST IN SPOOL : QUERY=$QUERY]");

                    if (!$res) {
                        $error_msg= "Cannot insert the callback request in the spool!";
                        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-ALL : CALLED=" . $A2B->destination . " | $error_msg]");
                    }

                } else {
                    $error_msg = 'Error : You don t have enough credit to call you back !!!';
                    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-CALLERID : CALLED=" . $A2B->destination . " | $error_msg]");
                }
            } else {
                $error_msg = 'Error : There is no route to call back your phonenumber !!!';
                $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-CALLERID : CALLED=" . $A2B->destination . " | $error_msg]");
            }

        } else {
            $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-CALLERID : CALLED=" . $A2B->destination . " | Authentication failed]");
        }

    } else {
        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-CALLERID : CALLED=" . $A2B->destination . " | error callerid]");
    }

// MODE CALLBACK
} elseif ($mode == 'callback') {

    $callback_been_connected = 0;

    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, '[CALLBACK]:[MODE : CALLBACK]');

    if ($A2B->config["callback"]['answer_call'] == 1) {
        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, '[CALLBACK]:[ANSWER CALL]');
        $agi->answer();
        $status_channel = 6;
        $A2B->play_menulanguage($agi);

        // PLAY INTRO FOR CALLBACK
        if (strlen($A2B->config["callback"]['callback_audio_intro']) > 0) {
            $agi->stream_file($A2B->config["callback"]['callback_audio_intro'], '#');
        }
    } else {
        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, '[CALLBACK]:[NO ANSWER CALL]');
        $status_channel = 4;
        $A2B->play_menulanguage($agi);
    }

    $called_party      = $agi->get_variable("CALLED", true);
    $calling_party     = $agi->get_variable("CALLING", true);
    $callback_mode     = $agi->get_variable("MODE", true);
    $callback_tariff   = $agi->get_variable("TARIFF", true);
    $callback_uniqueid = $agi->get_variable("CBID", true);
    $callback_leg      = $agi->get_variable("LEG", true);

    // |MODEFROM=ALL-CALLBACK|TARIFF=" . $A2B->tariff;
    $A2B->extension = $A2B->dnid = $A2B->destination = $calling_party;

    if ($callback_mode == 'CID') {
        $charge_callback = 1;
        $A2B->agiconfig['use_dnid'] = 0;
        $A2B->CallerID = $called_party;

    } elseif ($callback_mode == 'CID-PROMPT') {
        $charge_callback = 1;
        $A2B->agiconfig['use_dnid'] = 1;
        $A2B->CallerID = $called_party;

    } elseif ($callback_mode == 'ALL') {
        $A2B->agiconfig['use_dnid'] = 0;
        $A2B->agiconfig['cid_enable'] = 0;
        $A2B->CallerID = $called_party;

    } else {
        $charge_callback = 1;
        // FOR THE WEB-CALLBACK
        $A2B->agiconfig['use_dnid'] = 1;
        $A2B->agiconfig['say_balance_after_auth'] = 0;
        $A2B->agiconfig['cid_enable'] = 0;
        $A2B->agiconfig['say_timetocall'] = 0;
    }

    if ($A2B->agiconfig['callback_beep_to_enter_destination'] == 1) {
        $A2B->callback_beep_to_enter_destination = True;
    }

    $A2B->debug(INFO, $agi, __FILE__, __LINE__, "[CALLBACK]:[GET VARIABLE : CALLED=$called_party | CALLING=$calling_party | MODE=$callback_mode | TARIFF=$callback_tariff | CBID=$callback_uniqueid | LEG=$callback_leg | CALLERID=".$A2B->CallerID."]");

    $QUERY = "UPDATE cc_callback_spool SET agi_result='AGI PROCESSING' WHERE uniqueid='$callback_uniqueid'";
    $res = $A2B->DBHandle->Execute($QUERY);
    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK : UPDATE CALLBACK AGI_RESULT : QUERY=$QUERY]");


    /* WE START ;) */
    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK]:[TRY : callingcard_ivr_authenticate]");
    $cia_res = $A2B->callingcard_ivr_authenticate($agi);
    if ($cia_res == 0) {

        $charge_callback = 1; // EVEN FOR ALL CALLBACK
        $callback_leg = $A2B->username;

        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK]:[Start]");
        $A2B->callingcard_auto_setcallerid($agi);

        for ($i = 0; $i < $A2B->agiconfig['number_try']; $i++) {

            $RateEngine->Reinit();
            $A2B->Reinit();

            // DIVIDE THE AMOUNT OF CREDIT BY 2 IN ORDER TO AVOID NEGATIVE BALANCE IF THE USER USE ALL HIS CREDIT
            $orig_credit = $A2B->credit;

            if ($A2B->agiconfig['callback_reduce_balance'] > 0 && $A2B->credit > $A2B->agiconfig['callback_reduce_balance']) {
                $A2B->credit = $A2B->credit - $A2B->agiconfig['callback_reduce_balance'];
            } else {
                $A2B->credit = $A2B->credit / 2;
            }

            $stat_channel = $agi->channel_status($A2B->channel);
            $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, '[CALLBACK]:[CHANNEL STATUS : ' . $stat_channel["result"] . ' = ' . $stat_channel["data"] . ']'.
                            "[status_channel=$status_channel]:[ORIG_CREDIT : " . $orig_credit . " - CUR_CREDIT - : " . $A2B->credit.
                            " - CREDIT MIN_CREDIT_2CALL : " . $A2B->agiconfig['min_credit_2call'] . "]");

            if (!$A2B->enough_credit_to_call()) {
                // SAY TO THE CALLER THAT IT DEOSNT HAVE ENOUGH CREDIT TO MAKE A CALL
                $prompt = "prepaid-no-enough-credit-stop";
                $agi->stream_file($prompt, '#');
                $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK]:[STOP STREAM FILE $prompt]");
            }

            if ($A2B->callingcard_ivr_authorize($agi, $RateEngine, $i) == 1) {
                // PERFORM THE CALL
                $result_callperf = $RateEngine->rate_engine_performcall($agi, $A2B->destination, $A2B);
                if (!$result_callperf) {
                    $prompt = "prepaid-dest-unreachable";
                    $agi->stream_file($prompt, '#');
                }

                // INSERT CDR & UPDATE SYSTEM
                $RateEngine->rate_engine_updatesystem($A2B, $agi, $A2B->destination);

                if ($A2B->agiconfig['say_balance_after_call'] == 1) {
                    $A2B->fct_say_balance($agi, $A2B->credit);
                }

                $charge_callback = 1;
                if ($RateEngine->dialstatus == "ANSWER") {
                    $callback_been_connected = 1;
                }

                $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK]:[a2billing end loop num_try] RateEngine->usedratecard=" . $RateEngine->usedratecard);
            }
        }//END FOR

        if ($A2B->set_inuse == 1) {
            $A2B->callingcard_acct_start_inuse($agi, 0);
        }

    } else {
        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK]:[AUTHENTICATION FAILED (cia_res:" . $cia_res . ")]");
    }

// MODE CONFERENCE MODERATOR
} elseif ($mode == 'conference-moderator') {

    $callback_been_connected = 0;

    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, '[CALLBACK]:[MODE : CONFERENCE MODERATOR]');

    if ($A2B->config["callback"]['answer_call'] == 1) {
        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, '[CALLBACK]:[ANSWER CALL]');
        $agi->answer();
        $status_channel = 6;
    } else {
        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, '[CALLBACK]:[NO ANSWER CALL]');
        $status_channel = 4;
    }

    $A2B->play_menulanguage($agi);

    $called_party =       $agi->get_variable("CALLED", true);
    $calling_party =      $agi->get_variable("CALLING", true);
    $callback_mode =      $agi->get_variable("MODE", true);
    $callback_tariff =    $agi->get_variable("TARIFF", true);
    $callback_uniqueid =  $agi->get_variable("CBID", true);
    $callback_leg =       $agi->get_variable("LEG", true);
    $accountcode =        $agi->get_variable("ACCOUNTCODE", true);
    $phonenumber_member = $agi->get_variable("PN_MEMBER", true);
    $room_number =        $agi->get_variable("ROOMNUMBER", true);

    $A2B->debug(INFO, $agi, __FILE__, __LINE__, "[CALLBACK]:[GET VARIABLE : CALLED=$called_party | CALLING=$calling_party | MODE=$callback_mode | TARIFF=$callback_tariff | CBID=$callback_uniqueid | LEG=$callback_leg | ACCOUNTCODE=$accountcode | PN_MEMBER=$phonenumber_member | ROOMNUMBER=$room_number]");


    $error_settings = False;
    $room_number = intval($room_number);
    if ($room_number <= 0) {
        $error_settings = True;
    }

    if (strlen($accountcode) == 0 || strlen($phonenumber_member) == 0) {
        $error_settings = True;
    } else {
        $list_pn_member = preg_split("/[\s;]+/", $phonenumber_member);

        if (count($list_pn_member) == 0) {
            $error_settings = True;
        }
    }

    if ($error_settings) {
        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK : Error settings accountcode and phonenumber_member]");
        $agi->hangup();
        $A2B->write_log("[STOP - EXIT]", 0);
        exit();
    }

    $A2B->username = $A2B->accountcode = $accountcode;
    $A2B->callingcard_acct_start_inuse($agi, 1);

    if ($callback_mode == 'CONF-MODERATOR') {
        $charge_callback = 1;
        $A2B->CallerID = $called_party;
        $A2B->agiconfig['number_try'] = 1;
        $A2B->agiconfig['use_dnid'] = 1;
        $A2B->agiconfig['say_balance_after_auth'] = 0;
        $A2B->agiconfig['cid_enable'] = 0;
        $A2B->agiconfig['say_timetocall'] = 0;
    }

    $QUERY = "UPDATE cc_callback_spool SET agi_result = 'AGI PROCESSING' WHERE uniqueid='$callback_uniqueid'";
    $res = $A2B->DBHandle->Execute($QUERY);
    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK : UPDATE CALLBACK AGI_RESULT : QUERY = $QUERY]");


    /* WE START ;) */
    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK]:[TRY : callingcard_ivr_authenticate]");
    $cia_res = $A2B->callingcard_ivr_authenticate($agi);
    if ($cia_res == 0) {

        $charge_callback = 1; // EVEN FOR ALL CALLBACK
        $callback_leg = $A2B->username;


        for ($i = 0; $i < $A2B->agiconfig['number_try']; $i++) {

            $RateEngine->Reinit();
            //$A2B->Reinit();

            $stat_channel = $agi->channel_status($A2B->channel);
            $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, '[CALLBACK]:[CHANNEL STATUS : ' . $stat_channel["result"] . ' = ' . $stat_channel["data"] . ']'.
                            "[status_channel=$status_channel]:[CREDIT - : " . $A2B->credit . " - CREDIT MIN_CREDIT_2CALL : " . $A2B->agiconfig['min_credit_2call'] . "]");

            if (!$A2B->enough_credit_to_call()) {
                // SAY TO THE CALLER THAT IT DEOSNT HAVE ENOUGH CREDIT TO MAKE A CALL
                $prompt = "prepaid-no-enough-credit-stop";
                $agi->stream_file($prompt, '#');
                $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK]:[STOP STREAM FILE $prompt]");
            }

            // find the route and Initiate new callback for all the members
            foreach ($list_pn_member as $inst_pn_member) {
                $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, '[CALLBACK]:[Spool Callback for the PhoneNumber ' . $inst_pn_member . ']');
                $A2B->extension = $A2B->dnid = $A2B->destination = $inst_pn_member;

                $resfindrate = $RateEngine->rate_engine_findrates($A2B, $A2B->destination, $A2B->tariff);

                // IF FIND RATE
                if ($resfindrate != 0) {
                    //$RateEngine->debug_st = 1;
                    $res_all_calcultimeout = $RateEngine->rate_engine_all_calcultimeout($A2B, $A2B->credit);

                    if ($res_all_calcultimeout) {
                        // MAKE THE CALL
                        if ($RateEngine->ratecard_obj[0][34] != '-1') {
                            $usetrunk = 34;
                            $usetrunk_failover = 1;
                            $RateEngine->usedtrunk = $RateEngine->ratecard_obj[$k][34];
                        } else {
                            $usetrunk = 29;
                            $RateEngine->usedtrunk = $RateEngine->ratecard_obj[$k][29];
                            $usetrunk_failover = 0;
                        }

                        $prefix         = $RateEngine->ratecard_obj[0][$usetrunk + 1];
                        $tech           = $RateEngine->ratecard_obj[0][$usetrunk + 2];
                        $ipaddress      = $RateEngine->ratecard_obj[0][$usetrunk + 3];
                        $removeprefix   = $RateEngine->ratecard_obj[0][$usetrunk + 4];
                        $timeout        = $RateEngine->ratecard_obj[0]['timeout'];
                        $failover_trunk = $RateEngine->ratecard_obj[0][40 + $usetrunk_failover];
                        $addparameter   = $RateEngine->ratecard_obj[0][42 + $usetrunk_failover];

                        $destination = $A2B->destination;
                        if (strncmp($destination, $removeprefix, strlen($removeprefix)) == 0) $destination= substr($destination, strlen($removeprefix));

                        $pos_dialingnumber = strpos($ipaddress, '%dialingnumber%');

                        $ipaddress = str_replace("%cardnumber%", $A2B->cardnumber, $ipaddress);
                        $ipaddress = str_replace("%dialingnumber%", $prefix . $destination, $ipaddress);

                        if ($pos_dialingnumber !== false) {
                            $dialstr = "$tech/$ipaddress";
                        } else {
                            if ($A2B->agiconfig['switchdialcommand'] == 1) {
                                $dialstr = "$tech/$prefix$destination@$ipaddress";
                            } else {
                                $dialstr = "$tech/$ipaddress/$prefix$destination";
                            }
                        }

                        //ADDITIONAL PARAMETER %dialingnumber%, %cardnumber%
                        if (strlen($addparameter) > 0) {
                            $addparameter = str_replace("%cardnumber%", $A2B->cardnumber, $addparameter);
                            $addparameter = str_replace("%dialingnumber%", $prefix . $destination, $addparameter);
                            $dialstr .= $addparameter;
                        }

                        $channel= $dialstr;
                        $exten = $inst_pn_member;
                        $context = 'a2billing-conference-member';;
                        $id_server_group = $A2B->config["callback"]['id_server_group'];
                        $callerid = $called_party;
                        $priority = 1;
                        $timeout = $A2B->config["callback"]['timeout'] * 1000;
                        $application = '';
                        $account = $A2B->accountcode;
                        $uniqueid = $callback_uniqueid . '-' . MDP_NUMERIC(5);

                        $sep = ($A2B->config['global']['asterisk_version'] == "1_2" || $A2B->config['global']['asterisk_version'] == "1_4") ? '|' : ',';

                        $variable = "CALLED=$inst_pn_member" . $sep . "CALLING=$inst_pn_member" . $sep . "CBID=$callback_uniqueid" . $sep . "TARIFF=$callback_tariff" . $sep.
                                    "LEG=" . $A2B->accountcode . $sep . "ACCOUNTCODE=" . $A2B->accountcode . $sep . "ROOMNUMBER=" . $room_number;

                        $status = 'PENDING';
                        $server_ip = 'localhost';
                        $num_attempt = 0;

                        if (is_numeric($A2B->config["callback"]['sec_wait_before_callback']) && $A2B->config["callback"]['sec_wait_before_callback'] >= 1) {
                            $sec_wait_before_callback = $A2B->config["callback"]['sec_wait_before_callback'];
                        } else {
                            $sec_wait_before_callback = 1;
                        }

                        $QUERY = " INSERT INTO cc_callback_spool (uniqueid, status, server_ip, num_attempt, channel, exten, context, priority, variable, id_server_group, callback_time, account, callerid, timeout) VALUES ('$uniqueid', '$status', '$server_ip', '$num_attempt', '$channel', '$exten', '$context', '$priority', '$variable', '$id_server_group', ADDDATE( CURRENT_TIMESTAMP, INTERVAL $sec_wait_before_callback SECOND ), '$account', '$callerid', '$timeout')";
                        $res = $A2B->DBHandle->Execute($QUERY);
                        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-ALL : INSERT CALLBACK REQUEST IN SPOOL : QUERY=$QUERY]");

                        if (!$res) {
                            $error_msg= "Cannot insert the callback request in the spool!";
                            $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-ALL : CALLED=" . $A2B->destination . " | $error_msg]");
                        }

                    } else {
                        $error_msg = 'Error : You don t have enough credit to call you back !!!';
                        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-CALLERID : CALLED=" . $A2B->destination . " | $error_msg]");
                    }
                } else {
                    $error_msg = 'Error : There is no route to call back your phonenumber !!!';
                    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK-CALLERID : CALLED=" . $A2B->destination . " | $error_msg]");
                }
            }

            // DIAL INTO THE CONFERENCE AS ADMINISTRATOR
            $dialstr = "local/$room_number@a2billing-conference-room";

            $A2B->debug(INFO, $agi, __FILE__, __LINE__, "DIAL $dialstr");
            $myres = $A2B->run_dial($agi, $dialstr);

            $charge_callback = 1;

        }//END FOR

        if ($A2B->set_inuse == 1) {
            $A2B->callingcard_acct_start_inuse($agi, 0);
        }

    } else {
        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK]:[AUTHENTICATION FAILED (cia_res:" . $cia_res . ")]");
    }

// MODE CONFERENCE MEMBER
} elseif ($mode == 'conference-member') {

    $callback_been_connected = 0;

    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, '[CALLBACK]:[MODE : CONFERENCE MEMBER]');

    if ($A2B->config["callback"]['answer_call'] == 1) {
        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, '[CALLBACK]:[ANSWER CALL]');
        $agi->answer();
        $status_channel = 6;
    } else {
        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, '[CALLBACK]:[NO ANSWER CALL]');
        $status_channel = 4;
    }

    $A2B->play_menulanguage($agi);

    $called_party      = $agi->get_variable("CALLED", true);
    $calling_party     = $agi->get_variable("CALLING", true);
    $callback_mode     = $agi->get_variable("MODE", true);
    $callback_tariff   = $agi->get_variable("TARIFF", true);
    $callback_uniqueid = $agi->get_variable("CBID", true);
    $callback_leg      = $agi->get_variable("LEG", true);
    $accountcode       = $agi->get_variable("ACCOUNTCODE", true);
    $room_number       = $agi->get_variable("ROOMNUMBER", true);

    $A2B->debug(INFO, $agi, __FILE__, __LINE__, "[CALLBACK]:[GET VARIABLE : CALLED=$called_party | CALLING=$calling_party | MODE=$callback_mode | TARIFF=$callback_tariff | CBID=$callback_uniqueid | LEG=$callback_leg | ACCOUNTCODE=$accountcode | ROOMNUMBER=$room_number]");


    $error_settings = False;
    $room_number = intval($room_number);
    if ($room_number <= 0) {
        $error_settings = True;
    }

    if (strlen($accountcode) == 0) {
        $error_settings = True;
    }

    if ($error_settings) {
        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK : Error settings accountcode]");
        $agi->hangup();
        $A2B->write_log("[STOP - EXIT]", 0);
        exit();
    }

    $A2B->username = $A2B->accountcode = $accountcode;
    $A2B->callingcard_acct_start_inuse($agi, 1);

    if ($callback_mode == 'CONF-MODERATOR') {
        $charge_callback = 1;
        $A2B->CallerID = $called_party;
        $A2B->agiconfig['number_try'] = 1;
        $A2B->agiconfig['use_dnid'] = 1;
        $A2B->agiconfig['say_balance_after_auth'] = 0;
        $A2B->agiconfig['cid_enable'] = 0;
        $A2B->agiconfig['say_timetocall'] = 0;
    }

    $QUERY = "UPDATE cc_callback_spool SET agi_result = 'AGI PROCESSING' WHERE uniqueid = '$callback_uniqueid'";
    $res = $A2B->DBHandle->Execute($QUERY);
    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK : UPDATE CALLBACK AGI_RESULT : QUERY=$QUERY]");


    /* WE START ;) */
    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK]:[TRY : callingcard_ivr_authenticate]");
    $cia_res = $A2B->callingcard_ivr_authenticate($agi);
    if ($cia_res == 0) {

        $charge_callback = 1; // EVEN FOR ALL CALLBACK
        $callback_leg = $A2B->username;

        for ($i = 0; $i < $A2B->agiconfig['number_try']; $i++) {

            $RateEngine->Reinit();
            //$A2B->Reinit();

            $stat_channel = $agi->channel_status($A2B->channel);
            $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, '[CALLBACK]:[CHANNEL STATUS : ' . $stat_channel["result"] . ' = ' . $stat_channel["data"] . ']'.
                            "[status_channel=$status_channel]:[CREDIT - : " . $A2B->credit . " - CREDIT MIN_CREDIT_2CALL : " . $A2B->agiconfig['min_credit_2call'] . "]");

            if (!$A2B->enough_credit_to_call()) {
                // SAY TO THE CALLER THAT IT DEOSNT HAVE ENOUGH CREDIT TO MAKE A CALL
                $prompt = "prepaid-no-enough-credit-stop";
                $agi->stream_file($prompt, '#');
                $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK]:[STOP STREAM FILE $prompt]");
            }

            // DIAL INTO THE CONFERENCE AS ADMINISTRATOR
            $dialstr = "local/$room_number@a2billing-conference-room";

            $A2B->debug(INFO, $agi, __FILE__, __LINE__, "DIAL $dialstr");
            $myres = $A2B->run_dial($agi, $dialstr);


            $charge_callback = 1;

        }//END FOR

        if ($A2B->set_inuse == 1) {
            $A2B->callingcard_acct_start_inuse($agi, 0);
        }

    } else {
        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK]:[AUTHENTICATION FAILED (cia_res:" . $cia_res . ")]");
    }
}

// CHECK IF WE HAVE TO CHARGE CALLBACK
if ($charge_callback) {

    $callback_username = $callback_leg;
    $A2B->accountcode = $callback_username;
    $A2B->agiconfig['say_balance_after_auth'] = 0;
    $A2B->agiconfig['cid_enable'] = 0;
    $A2B->agiconfig['say_timetocall'] = 0;

    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK 1ST LEG]:[INFO FOR THE 1ST LEG - callback_username=$callback_username");
    $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK 1ST LEG]:[TRY : callingcard_ivr_authenticate]");
    $cia_res = $A2B->callingcard_ivr_authenticate($agi);

    //overrides the tariff for the user with the one passed in.
    if (strlen($callback_tariff) > 0) {
        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "*** Tariff override **** Changing from " . $A2B->tariff . " to " . $callback_tariff . " cia_res=$cia_res");
        $A2B->tariff = $callback_tariff;
    }

    if ($cia_res == 0) {

        $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[CALLBACK 1ST LEG]:[MAKE BILLING FOR THE 1ST LEG - TARIFF:" . $A2B->tariff . ";CALLED=$called_party]");
        $A2B->agiconfig['use_dnid'] = 1;
        $A2B->dnid = $A2B->destination = $called_party;

        $resfindrate = $RateEngine->rate_engine_findrates($A2B, $called_party, $A2B->tariff);
        $RateEngine->usedratecard = 0;

        // IF FIND RATE
        if ($resfindrate != 0) {
            $res_all_calcultimeout = $RateEngine->rate_engine_all_calcultimeout($A2B, $A2B->credit);

            if ($res_all_calcultimeout) {
                // SET CORRECTLY THE CALLTIME FOR THE 1st LEG
                $RateEngine->answeredtime = time() - $G_startime;
                $RateEngine->dialstatus = 'ANSWERED';
                $A2B->debug(INFO, $agi, __FILE__, __LINE__, "[CALLBACK]:[RateEngine->answeredtime=" . $RateEngine->answeredtime . "]");

                //(ST) replace above code with the code below to store CDR for all callbacks and to only charge for the callback if requested
                if ($callback_been_connected == 1 || ($A2B->agiconfig['callback_bill_1stleg_ifcall_notconnected'] == 1)) {
                    //(ST) this is called if we need to bill the user
                    $RateEngine->rate_engine_updatesystem($A2B, $agi, $A2B->destination, 1, 0, 1);
                } else {
                    //(ST) this is called if we don't bill ther user but to keep track of call costs
                    $RateEngine->rate_engine_updatesystem($A2B, $agi, $A2B->destination, 0, 0, 1);
                }

            } else {
                $A2B->debug(ERROR, $agi, __FILE__, __LINE__, "[CALLBACK 1ST LEG]:[ERROR - BILLING FOR THE 1ST LEG - rate_engine_all_calcultimeout: CALLED=$called_party]");
            }
        } else {
            $A2B->debug(ERROR, $agi, __FILE__, __LINE__, "[CALLBACK 1ST LEG]:[ERROR - BILLING FOR THE 1ST LEG - rate_engine_findrates: CALLED=$called_party - RateEngine->usedratecard=" . $RateEngine->usedratecard . "]");
        }
    } else {
        $A2B->debug(ERROR, $agi, __FILE__, __LINE__, "[CALLBACK 1ST LEG]:[ERROR - AUTHENTICATION USERNAME]");
    }

}// END if ($charge_callback)

if ($mode != 'cid-callback' && $mode != 'all-callback') {
    $agi->hangup();
} elseif ($A2B->agiconfig['answer_call'] == 1) {
    $agi->hangup();
}

// SEND MAIL REMINDER WHEN CREDIT IS TOO LOW
if (isset($send_reminder) && $send_reminder == 1 && $A2B->agiconfig['send_reminder'] == 1) {

    if (strlen($A2B->cardholder_email) > 5) {
        include_once(dirname(__FILE__) . "/lib/mail/class.phpmailer.php");
        include_once(dirname(__FILE__) . "/lib/Class.Mail.php");

        try {
            $mail = new Mail(Mail::$TYPE_REMINDERCALL, $A2B->id_card);
            $mail->send();
            $A2B->debug(DEBUG, $agi, __FILE__, __LINE__, "[SEND-MAIL REMINDER]:[TO:" . $A2B->cardholder_email . " - FROM:$from - SUBJECT:$subject]");
        } catch (A2bMailException $e) {
        }
    }
}

if ($A2B->set_inuse == 1) {
    $A2B->callingcard_acct_start_inuse($agi, 0);
}

# End
$A2B->write_log("[exit]", 0);
