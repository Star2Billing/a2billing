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

include (dirname(__FILE__) . "/../../../../common/lib/admin.defines.php");

function print_usage()
{
    echo "Usage : php a2billing_provisioning.php @IP_SERVER @NUMBER_ACCOUNT\n";
    echo "        @IP_SERVER : IP of your Asterisk Server\n";
    echo "        @NUMBER_ACCOUNT : Number of accounts to return\n\n";

}

if ($argc > 1 && ($argv[1] == '--version' || $argv[1] == '-v')) {
    echo "A2Billing Provisioning system V0.1\n";
    exit;
}

if ($argc > 1 && ($argv[1] == '--help' || $argv[1] == '-h')) {
    print_usage();
    exit;
}

if ($argc > 1 && $argv[1] >= 0 && strlen($argv[1])>1) {
    $Asterisk_IP = $argv[1];
} else {
    print_usage();
    exit;
}

if ($argc > 2 && $argv[2] >= 0) {
    $Number_account = $argv[2];
} else {
    $Number_account = 10000;
}

echo "Your Asterisk IP : $Asterisk_IP \n";
echo "Max number of account to return : $Number_account \n\n";

$DBHandle = DbConnect();
$table_instance = new Table();

if (!$DBHandle) {
    echo "Error Database connection!";
    exit();
}

$trunkname = 'trunk-villagetelco';

list($accountnumber, $password) = (preg_split("{_}",$activation_code,2));

$QUERY = "SELECT cc.username, cc.credit, cc.status, cc.id, cc.id_didgroup, cc.tariff, cc.vat, ct.gmtoffset, cc.voicemail_permitted, " .
         "cc.voicemail_activated, cc_card_group.users_perms, cc.currency, cc_did.did " .
         "FROM cc_card cc ".
         "LEFT JOIN cc_timezone AS ct ON ct.id = cc.id_timezone LEFT JOIN cc_card_group ON cc_card_group.id=cc.id_group ".
         "LEFT JOIN cc_did ON cc_did.iduser=cc.id ".
         "LIMIT 0, $Number_account";
$res = $DBHandle -> Execute($QUERY);

$num = $res -> RecordCount();
if ($num==0) {
    echo "Error : Fetching the account!";
    exit();
}
$Config_output = '';

for ($i=0;$i<$num;$i++) {

    $accounts[$i] =$res -> fetchRow();
    $card_id = $accounts[$i][3];
    $did = $accounts[$i][12];

    //$QUERY_IAX = "SELECT iax.id, iax.username, iax.secret, iax.disallow, iax.allow, iax.type, iax.host, iax.context FROM cc_iax_buddies iax WHERE iax.id_cc_card = $card_id";
    $QUERY_SIP = "SELECT sip.id, sip.username, sip.secret, sip.disallow, sip.allow, sip.type, sip.host, sip.context FROM cc_sip_buddies sip WHERE sip.id_cc_card = $card_id";

    //$iax_data = $table_instance->SQLExec ($DBHandle, $QUERY_IAX);
    $sip_data = $table_instance->SQLExec ($DBHandle, $QUERY_SIP);

    //Additonal parameters
    $additional_sip = explode("|", SIP_ADDITIONAL_PARAMETERS);
    $additional_iax = explode("|", IAX_ADDITIONAL_PARAMETERS);

    // ADD REGISTER
    // register => [peer?][transport://]user[@domain][:secret[:authuser]]@host[:port][/extension][~expiry]
    // 2345:password@mysipprovider.com
    $Config_output .= "\n\n";
    $Config_output .= "; REGISTER COMMAND\n";
    $Config_output .= "register => ".$sip_data[0][1].":".$sip_data[0][2]."@$Asterisk_IP\n";

    // SIP
    $Config_output .= "\nDID : $did\n";
    $Config_output .= "[$trunkname]\n";
    $Config_output .= "username=".$sip_data[0][1]."\n";
    $Config_output .= "type=friend\n";
    $Config_output .= "secret=".$sip_data[0][2]."\n";
    $Config_output .= "host=$Asterisk_IP\n";
    $Config_output .= "context=".$sip_data[0][7]."\n";
    $Config_output .= "disallow=all\n";
    $Config_output .= "allow=".SIP_IAX_INFO_ALLOWCODEC."\n";
    if (count($additional_sip) > 0) {
        for ($j = 0; $j< count($additional_sip); $j++) {
            $Config_output .= trim($additional_sip[$j]).chr(10);
        }
    }
    $Config_output .= "insecure=very\n";
    $Config_output .= "permit=$Asterisk_IP\n";

    $Config_output .= "\n\n\n";
    echo $Config_output;
    $Config_output = '';

} // END FOR
