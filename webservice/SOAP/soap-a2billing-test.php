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

include ("../lib/Class.SOAP-function.php");


$security_key = md5(API_SECURITY_KEY);

$webservice = new SOAP_A2Billing();


$instance = 'VillageTelco_iiud-7645';
$instance_name = 'VillageTelco';
$uri_trunk = 'http://www.call-labs.com/Create_TrunkConfig.php';
$provider_name = 'call-labs';
$activation_code = 'PL@uj243oj24';


// BUILD TEST SUIT FOR CLASS SOAP FUNCTION
/*
$method = 'Create_Instance';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, $instance_name);
print_r ($arr_result);
*/

$method = 'Create_TrunkConfig';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key, $instance, $uri_trunk, $activation_code, $provider_name);
print_r ($arr_result);


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



$method = 'Get_Countries';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key);
print_r ($arr_result);
