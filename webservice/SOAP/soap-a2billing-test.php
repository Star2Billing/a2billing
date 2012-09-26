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

include '../lib/Class.SOAP-function.php';

$security_key = md5(API_SECURITY_KEY);

$webservice = new SOAP_A2Billing();

$method = 'Reload_Asterisk_SIP_IAX';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key);
print_r ($arr_result);
exit();

$method = 'Update_Currencies_list';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key);
print_r ($arr_result);

//$instance = 'VillageTelco_rmsu-2114';
$instance_name = 'VillageTelco';
$uri_trunk = 'http://www.YourDomain.com/Create_TrunkConfig.php';
$provider_name = 'YourDomain';
$provisioning_uri = 'http://www.YourDomain.com/provisioning.txt';
$uri_rate = 'http://www.YourDomain.com/Get_Rates.php';
$activation_code = '54wefdsf$3Z';
$margin = 0.2;

$method = 'Get_Subscription_Signup';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key);

echo "##".$arr_result[0]."##";
var_dump (unserialize($arr_result[0]));

$method = 'Set_Setting';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, 'base_country', 'swe');
print_r ($arr_result);

$method = 'Get_Setting';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, 'base_country');
print_r ($arr_result);

$method = 'Get_Languages';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key);
print_r ($arr_result);

// BUILD TEST SUIT FOR CLASS SOAP FUNCTION

$method = 'Authenticate_Admin';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, 'admin', 'admin');
print_r ($arr_result);

$method = 'Set_AdminPwd';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, 'admin', 'admin');
print_r ($arr_result);

$method = 'Write_Notification';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, 'admin', 'Test SOAP Notification : '.date("Y/m/d G:i:s", mktime()), 1);
print_r ($arr_result);

$method = 'Create_Instance';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, $instance_name);
print_r ($arr_result);
$instance = $arr_result[0];
echo "instance => $instance \n-----------------------\n\n";

$method = 'Set_InstanceDescription';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, $instance, 'hello here');
print_r ($arr_result);

$method = 'Set_InstanceProvisioning';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, $instance, 'test1|test2|ko');
print_r ($arr_result);

$method = 'Get_CustomerGroups';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key);
print_r ($arr_result);

$method = 'Get_Currencies';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key);
print_r ($arr_result);

$method = 'Get_Currencies_value';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, 'usd');
print_r (unserialize($arr_result[0]));

$method = 'Get_Countries';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key);
print_r ($arr_result);

$method = 'Set_Setting';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, 'base_country', 'swe');
print_r ($arr_result);

$method = 'Get_Setting';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, 'base_country');
print_r ($arr_result);

$method = 'Get_Languages';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key);
print_r ($arr_result);

$method = 'Create_DIDGroup';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, $instance);
print_r ($arr_result);
$id_didgroup = $arr_result[0];

$method = 'Create_Provider';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, $instance);
print_r ($arr_result);

$method = 'Create_Ratecard';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, $instance);
print_r ($arr_result);
$id_ratecard = $arr_result[0];

$method = 'Create_Callplan';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, $instance, $id_ratecard);
print_r ($arr_result);
$id_callplan = $arr_result[0];

// VOUCHER CREATION
$credit = 3;
$units = 10;
$currency = 'EUR';

$method = 'Create_Voucher';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, $credit, $units, $currency);
print_r ($arr_result);

// Customer
$id_callplan = 223;
$id_didgroup = 7;
$units = 5;
$accountnumber_len = 10;
$balance = 55;
$activated = 1;
$status = 1;
$simultaccess = 1;
$currency = 'EUR';
$typepaid = 1;
$sip_buddy = $iax_buddy = 1;
$language = 'en';
$voicemail_enabled = 1;

$method = 'Create_Customer';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, $instance, $id_callplan, $id_didgroup, $units, $accountnumber_len, $balance, $activated, $status, $simultaccess, $currency, $typepaid, $sip_buddy, $iax_buddy, $language, $voicemail_enabled);
print_r ($arr_result);

$arr_id_card = array();
foreach (unserialize($arr_result[0]) as $arr_result_val) {
    $arr_id_card[] = $arr_result_val[1];
}
print_r ($arr_id_card);

// VALIDATE DID
$did_prefix = '600';

$method = 'Validate_DIDPrefix';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, $did_prefix);
print_r ($arr_result);

// DID CREATION
$rate = 1.4;
$connection_charge = 0;
$did_suffix = '8700';
$id_country = 225; // USA
$method = 'Create_DID';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, $arr_id_card, $id_didgroup, $rate, $connection_charge, $did_prefix, $did_suffix, $id_country);
print_r ($arr_result[0]);

exit();
$method = 'Get_ProvisioningList';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, $provisioning_uri);
print_r ($arr_result);

$method = 'Create_TrunkConfig';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, $instance, $uri_trunk, $activation_code, $provider_name);
print_r ($arr_result);

$method = 'Get_Rates';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, $uri_rate, $activation_code, $margin);
print_r ($arr_result);

$arr_rates = $arr_result[0];

// CREATE RATES

$method = 'Create_Rates';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, $instance, $arr_rates);
print_r ($arr_result);

// UPDATE RATES

$method = 'Update_Rates';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, $instance, $arr_rates);
print_r ($arr_result);
