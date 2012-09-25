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

set_time_limit(0);
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

include (dirname(__FILE__) . "/../common/lib/admin.defines.php");
include (dirname(__FILE__) . "/../common/lib/phpagi/phpagi-asmanager.php");

function print_usage()
{
    echo "Usage : php originate_echo.php [DID] [MAX_NUMBER_ACCOUNT]\n";
    echo "        [DID] : PhoneNumber to reach\n";
    echo "        [MAX_NUMBER_ACCOUNT] : Max Number of accounts to call\n\n";

}

if ($argc > 1 && ($argv[1] == '--version' || $argv[1] == '-v')) {
    echo "OutBound Call to Provisioned number V0.1\n";
    exit;
}

if ($argc > 1 && ($argv[1] == '--help' || $argv[1] == '-h')) {
    print_usage();
    exit;
}

if ($argc > 1 && $argv[1] >= 0) {
    $DID_to_reach = $argv[1];
} else {
    $DID_to_reach = False;
}

if ($argc > 2 && $argv[2] >= 0) {
    $max_call_account = $argv[2];
} else {
    $max_call_account = 50;
}

$DBHandle = DbConnect();
$table_instance = new Table();

if (!$DBHandle) {
    echo "Error Database connection!";
    exit();
}

echo "Max number of account to call : $max_call_account \n\n";

$as = new AGI_AsteriskManager();

$res = $as->connect(MANAGER_HOST, MANAGER_USERNAME, MANAGER_SECRET);

if ($res) {
    $account_sip = array();
    $res = $as->Command('sip show peers');

    $res_splitted = preg_split("/\n/", $res['data']);
    $fl_array = preg_grep("/OK \(/", $res_splitted);
    //print_r ($fl_array);

    foreach ($fl_array as $inst_fl) {
        preg_match('/(?P<digit>\d+)/', $inst_fl, $matches);
        if (strlen($matches[0]) > 1) {
            $account_sip[] = $matches[0];
        }
    }
    if (count($account_sip) > 0) {
        implode(",", $array);
        $comma_separated = '\''.implode("' , '", $account_sip).'\'';

        $QUERY = "SELECT cc.username, cc.credit, cc.status, cc.id, cc_did.did " .
                 "FROM cc_card cc ".
                 "LEFT JOIN cc_timezone AS ct ON ct.id = cc.id_timezone LEFT JOIN cc_card_group ON cc_card_group.id=cc.id_group ".
                 "LEFT JOIN cc_did ON cc_did.iduser=cc.id ".
                 "WHERE cc.username IN ($comma_separated)";
        $res = $DBHandle -> Execute($QUERY);

        $num = $res -> RecordCount();
        if ($num==0) {
            echo "Error : Fetching the account!";
            exit();
        }
        $Config_output = '';

        for ($i=0;$i<$num;$i++) {
            $accounts[$i] =$res -> fetchRow();
        }

        foreach ($accounts as $calling_account) {
            $current_did = $calling_account[4];
            if (!$DID_to_reach or $current_did==$DID_to_reach) {
                $channel = "SIP/".$calling_account[0];
                $exten = '1234';
                $context = '1234@a2billing_echotest';
                $priority = 1;
                $timeout = 30000;
                $async = True;
                $callerid = "1234";
                 echo "--> Trying to Originate call to $channel \n\n";
                $res_orig = $as->Originate($channel,
                           $exten, $context, $priority,
                           $application=NULL, $data=NULL,
                           $timeout, $callerid, $variable=NULL, $account=NULL, $async, $actionid=NULL);
                print_r ($res_orig);
            }
        }

    } else {
        echo "No account found";

    }
    // && DISCONNECTING
    echo "\nDisconnect\n";
    $as->disconnect();

} else {
    echo "Cannot connect to the asterisk manager!<br>Please check your manager configuration.\n\n";
}
