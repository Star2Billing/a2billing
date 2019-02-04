<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2015 - Star2billing S.L.
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

/***************************************************************************
 *
 * USAGE : http://localhost/webservice/SOAP/soap-callback-server.php?wsdl
 *
 * 			webservice/SOAP/soap-callback-server.php?wsdl
 *
 *
 ****************************************************************************/

$disable_check_cp = true;
include '../lib/admin.defines.php';
include '../lib/regular_express.inc';
include '../lib/Class.RateEngine.php';
require_once 'SOAP/Server.php';
require_once 'SOAP/Disco.php';

define("LOG_CALLBACK", isset ($A2B->config["log-files"]['api_callback']) ? $A2B->config["log-files"]['api_callback'] : null);

/*
//$phone_number = '34650784355';
$phone_number = $_GET['phone_number'];
$callerid = $_GET['callerid'];
$security_key = $_GET['security_key'];
$uniqueid = $_GET['uniqueid'];
$callback_time = urldecode($_GET['callback_time']);
*/

//$ans = Request($security_key, $phone_number, $callerid, $callback_time, $uniqueid);
//print_r($ans);

class Callback
{
    public $__dispatch_map = array ();

    public function __construct()
    {

        // Define the signature of the dispatch map on the Web servicesmethod
        $this->__dispatch_map['Request'] = array (
            'in' => array ( 'security_key' => 'string', 'pn_calledparty' => 'string', 'pn_destination' => 'string', 'callerid' => 'string', 'callback_time' => 'string', 'uniqueid' => 'string', 'accountnumber' => 'string' ),
            'out' => array ( 'id' => 'string', 'result' => 'string', 'details' => 'string' )
        );

        $this->__dispatch_map['Status'] = array (
            'in' => array ( 'security_key' => 'string', 'id' => 'string' ),
            'out' => array ( 'uniqueid' => 'string', 'result' => 'string', 'details' => 'string' )
        );

    }

    /*
     *		Function to make Callback : it will insert a callback request
     */
    public function Status($security_key, $id)
    {

        $status = 'null';
        $uniqueid = '';

        $DBHandle = DbConnect();
        $instance_table = new Table();

        $QUERY = "SELECT status, uniqueid FROM cc_callback_spool WHERE id='$id'";
        $callback_data = $instance_table->SQLExec($DBHandle, $QUERY);
        if (!is_array($callback_data) || count($callback_data) == 0) {
            // FAIL SELECT
            write_log(LOG_CALLBACK, basename(__FILE__) . ' line:' . __LINE__ . "[" . date("Y/m/d G:i:s", mktime()) . "] " . " ERROR SELECT -> \n QUERY=" . $QUERY);
            sleep(2);

            return array ( $uniqueid, 'result=null', ' ERROR - SELECT DB' );
        }

        $status = $callback_data[0][0];
        $uniqueid = $callback_data[0][1];

        return array ( $uniqueid, 'result=' . $status, " - Callback request found $QUERY" );
    }

    /*
     *		Function to make Callback : it will insert a callback request
     */
    public function Request($security_key, $called, $calling, $callerid, $callback_time, $uniqueid, $accountnumber)
    {
        // $called : PHONE NUMBER PERSON CALLING
        // $calling : DESTINATION PHONE NUMBER

        global $A2B;

        /*
        $status = 'PENDING';
        $server_ip = 'localhost';
        $num_attempt = 0;
        $channel = 'SIP/'.$phone_number.'@mylittleIP';
        $exten = $phone_number;
        $context = 'a2billing';
        $priority = 1;
        //$timeout	callerid
        $variable = "phonenumber=$phone_number|callerid=$callerid";
        */
        $phone_number = $called;
        $insert_id_callback = 'null';

        if (strlen($uniqueid) == 0) {
            $uniqueid = MDP_STRING(5) . '-' . MDP_NUMERIC(5).MDP_STRING(10).MDP_NUMERIC(5);
        }

        $FG_regular[] = array ( "^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$", "(YYYY-MM-DD HH:MM:SS)" );

        // The wrapper variables for security
        // $security_key = API_SECURITY_KEY;
        write_log(LOG_CALLBACK, " Service_Callback( security_key=$security_key, called=$called, calling=$calling, callerid=$callerid, uniqueid=$uniqueid, callback_time=$callback_time)");
        $mysecurity_key = API_SECURITY_KEY;

        // CHECK CALLERID
        if (strlen($callerid) < 1) {
            write_log(LOG_CALLBACK, basename(__FILE__) . ' line:' . __LINE__ . "[" . date("Y/m/d G:i:s", mktime()) . "] " . " ERROR FORMAT CALLERID AT LEAST 1 DIGIT ");

            return array ( $insert_id_callback, 'result=Error', " ERROR - FORMAT CALLERID AT LEAST 1 DIGIT " );
        }

        // CHECK PHONE_NUMBER
        if (strlen($phone_number) < 10) {
            write_log(LOG_CALLBACK, basename(__FILE__) . ' line:' . __LINE__ . "[" . date("Y/m/d G:i:s", mktime()) . "] " . " ERROR FORMAT PHONENUMBER AT LEAST 10 DIGITS ");

            return array ( $insert_id_callback, 'result=Error', " ERROR - FORMAT PHONENUMBER AT LEAST 10 DIGITS " );
        }

        // CHECK DESTINATION NUMBER
        if (strlen($calling) < 2) {
            write_log(LOG_CALLBACK, basename(__FILE__) . ' line:' . __LINE__ . "[" . date("Y/m/d G:i:s", mktime()) . "] " . " ERROR FORMAT DESTINATION NUMBER AT LEAST 2 DIGITS ");

            return array ( $insert_id_callback, 'result=Error', " ERROR - FORMAT DESTINATION NUMBER AT LEAST 2 DIGITS " );
        }

        // CHECK CALLBACK TIME
        if (strlen($callback_time) > 1 && !(preg_match("/".$FG_regular[0][0]."/", $callback_time))) {
            write_log(LOG_CALLBACK, basename(__FILE__) . ' line:' . __LINE__ . "[" . date("Y/m/d G:i:s", mktime()) . "] " . " ERROR FORMAT CALLBACKTIME : " . $FG_regular[0][0]);

            return array ( $insert_id_callback, 'result=Error', " ERROR - FORMAT CALLBACKTIME : " . $FG_regular[0][0] );
        }

        // CHECK SECURITY KEY
        if (md5($mysecurity_key) !== $security_key || strlen($security_key) == 0) {
            write_log(LOG_CALLBACK, basename(__FILE__) . ' line:' . __LINE__ . "[" . date("Y/m/d G:i:s", mktime()) . "] " . " CODE_ERROR SECURITY_KEY");
            sleep(2);

            return array ( $insert_id_callback, 'result=Error', ' KEY - BAD PARAMETER ' );
        }

        $DBHandle = DbConnect();
        if (!$DBHandle) {
            write_log(LOG_CALLBACK, basename(__FILE__) . ' line:' . __LINE__ . "[" . date("Y/m/d G:i:s", mktime()) . "] " . " ERROR CONNECT DB");
            sleep(2);

            return array ( $insert_id_callback, 'result=Error', ' ERROR - CONNECT DB ' );
        }
        $A2B->DBHandle = $DBHandle;
        $instance_table = new Table();
        $A2B->set_instance_table($instance_table);

        $A2B->credit = 1000;
        $A2B->tariff = $A2B->config["callback"]['all_callback_tariff'];

        if (strlen($accountnumber) > 1) {
            // IF WE HAVE AN ACCOUNT NUMBER DEFINED
            $QUERY = "SELECT tariff, typepaid, credit, creditlimit FROM cc_card WHERE username='$accountnumber'";
            $card_data = $instance_table->SQLExec($DBHandle, $QUERY);
            if (is_array($card_data)) {

                $A2B->credit = $card_data[0]['credit'];

                if ($card_data[0]['typepaid']==1) {
                    $A2B->credit = $A2B->credit + $card_data[0]['creditlimit'];
                }

                $A2B->tariff = $card_data[0]['tariff'];
            }

            //Else find accountnumber from caller's CallerID
        } else {

            $QUERY .=  "SELECT cc_card.tariff, cc_card.typepaid, cc_card.credit, cc_card.creditlimit, cc_card.username".
            " FROM cc_card ".
            " JOIN cc_callerid".
            " ON cc_card.id=cc_callerid.id_cc_card ".
            " WHERE cc_callerid.cid='".$phone_number."'";
            $QUERY .= "ORDER BY 1";
            $card_data = $instance_table->SQLExec($DBHandle, $QUERY);

            if (!is_array($card_data)) {
                return array ( $insert_id_callback, 'result=Error', "CALLING'S PARTY CALLERID DOES NOT EXIST IN DATABASE" );
            }

            $accountnumber = $card_data[0]['username'];

            if (is_array($card_data)) {

                $A2B->credit = $card_data[0]['credit'];

                if ($card_data[0]['typepaid']==1) {
                    $A2B->credit = $A2B->credit + $card_data[0]['creditlimit'];
                }

                $A2B->tariff = $card_data[0]['tariff'];
            }
        }

        $RateEngine = new RateEngine();

        $A2B -> extension = $A2B -> dnid = $A2B -> destination = $called;

        // LOOKUP RATE : FIND A RATE FOR THIS DESTINATION
        $resfindrate = $RateEngine->rate_engine_findrates($A2B, $A2B->destination, $A2B->tariff);

        if ($resfindrate != 0) {

            //$RateEngine -> debug_st = 1;
            $res_all_calcultimeout = $RateEngine->rate_engine_all_calcultimeout($A2B, $A2B->credit);

            if ($res_all_calcultimeout) {
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

                $prefix = $RateEngine->ratecard_obj[0][$usetrunk +1];
                $tech = $RateEngine->ratecard_obj[0][$usetrunk +2];
                $ipaddress = $RateEngine->ratecard_obj[0][$usetrunk +3];
                $removeprefix = $RateEngine->ratecard_obj[0][$usetrunk +4];
                $timeout = $RateEngine->ratecard_obj[0]['timeout'];
                $failover_trunk = $RateEngine->ratecard_obj[0][40 + $usetrunk_failover];
                $addparameter = $RateEngine->ratecard_obj[0][42 + $usetrunk_failover];

                $destination = $A2B->destination;
                if (strncmp($destination, $removeprefix, strlen($removeprefix)) == 0)
                    $destination = substr($destination, strlen($removeprefix));

                $pos_dialingnumber = strpos($ipaddress, '%dialingnumber%');

                $ipaddress = str_replace("%cardnumber%", $A2B->cardnumber, $ipaddress);
                $ipaddress = str_replace("%dialingnumber%", $prefix . $destination, $ipaddress);

                $dialparams = '';
                if ($pos_dialingnumber !== false) {
                    $dialstr = "$tech/$ipaddress" . $dialparams;
                } else {
                    if ($A2B->agiconfig['switchdialcommand'] == 1) {
                        $dialstr = "$tech/$prefix$destination@$ipaddress" . $dialparams;
                    } else {
                        $dialstr = "$tech/$ipaddress/$prefix$destination" . $dialparams;
                    }
                }

                //ADDITIONAL PARAMETER 			%dialingnumber%,	%cardnumber%
                if (strlen($addparameter) > 0) {
                    $addparameter = str_replace("%cardnumber%", $A2B->cardnumber, $addparameter);
                    $addparameter = str_replace("%dialingnumber%", $prefix . $destination, $addparameter);
                    $dialstr .= $addparameter;
                }

                $channel = $dialstr;
                $exten = $calling;
                $context = $A2B->config["callback"]['context_callback'];
                $id_server_group = $A2B->config["callback"]['id_server_group'];
                $priority = 1;
                $timeout = $A2B->config["callback"]['timeout'] * 1000;
                $application = '';

                $status = 'PENDING';
                $server_ip = 'localhost';
                $num_attempt = 0;

                $sep = ($A2B->config['global']['asterisk_version'] == "1_2" || $A2B->config['global']['asterisk_version'] == "1_4")?'|':',';

                $variable = "CALLED=$called".$sep."CALLING=$calling".$sep."CBID=$uniqueid".$sep."TARIFF=" . $A2B->tariff;

                if (is_numeric($A2B->config["callback"]['sec_wait_before_callback']) && $A2B->config["callback"]['sec_wait_before_callback'] >= 1) {
                    $sec_wait_before_callback = $A2B->config["callback"]['sec_wait_before_callback'];
                } else {
                    $sec_wait_before_callback = 1;
                }

                // LIST FIELDS TO INSERT CALLBACK REQUEST
                $QUERY_FIELS = 'uniqueid, status, server_ip, num_attempt, channel, exten, context, priority, variable, id_server_group, callback_time, account, callerid, timeout';

                // DEFINE THE CORRECT VALUE FOR THE INSERT
                if (strlen($callback_time) > 1) {
                    $QUERY_VALUES = "'$uniqueid', '$status', '$server_ip', '$num_attempt', '$channel', '$exten', '$context', '$priority', '$variable', '$id_server_group', '$callback_time', '$accountnumber', '$callerid', '30000'";
                } else {
                    $QUERY_VALUES = "'$uniqueid', '$status', '$server_ip', '$num_attempt', '$channel', '$exten', '$context', '$priority', '$variable', '$id_server_group', ADDDATE( CURRENT_TIMESTAMP, INTERVAL $sec_wait_before_callback SECOND ), '$accountnumber', '$callerid', '30000'";
                }

                $insert_id_callback = $instance_table->Add_table($DBHandle, $QUERY_VALUES, $QUERY_FIELS, 'cc_callback_spool', 'id');

                if (!$insert_id_callback) {
                    // FAIL INSERT
                    write_log(LOG_CALLBACK, basename(__FILE__) . ' line:' . __LINE__ . "[" . date("Y/m/d G:i:s", mktime()) . "] " . " ERROR INSERT -> \n QUERY= $QUERY_FIELS :: $QUERY_VALUES");
                    sleep(2);

                    return array ( $insert_id_callback,	'result=Error',	' ERROR - INSERT INTO DB' );
                }
                // SUCCEED INSERT
                write_log(LOG_CALLBACK, basename(__FILE__) . ' line:' . __LINE__ . "[" . date("Y/m/d G:i:s", mktime()) . "] " . " CALLBACK INSERTED -> \n QUERY= $QUERY_FIELS :: $QUERY_VALUES");

                return array ( $insert_id_callback, 'result=Success', " Success - Callback request has been accepted " );

            } else {
                $error_msg = 'Error : You don t have enough credit to call you back !!!';
            }
        } else {
            $error_msg = 'Error : There is no route to call back your phonenumber !!!';
        }

        // CALLBACK FAIL
        write_log(LOG_CALLBACK, "error_msg = $error_msg");

        return array ( $insert_id_callback, 'result=Error', " ERROR - $error_msg" );
    }

}



// Create SOAP SERVER
$server = new SOAP_Server();

// Create Service
$webservice = new Callback();


// TEST WITH SOAP
// $arr_cb_req = $webservice -> Request(md5(API_SECURITY_KEY), '1234567896', '2342354324', '223424234', $callback_time='', $uniqueid='', $accountnumber='');
// print_r ($arr_cb_req);
//exit;

$server->addObjectMap($webservice, 'http://schemas.xmlsoap.org/soap/envelope/');


if (isset ($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $server->service($HTTP_RAW_POST_DATA);

} else {
    // Create the DISCO server
    $disco = new SOAP_DISCO_Server($server, 'Callback');
    header("Content-type: text/xml");
    if (isset ($_SERVER['QUERY_STRING']) && strcasecmp($_SERVER['QUERY_STRING'], 'wsdl') == 0) {
        echo $disco->getWSDL();
    } else {
        echo $disco->getDISCO();
    }
}
