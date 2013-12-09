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

// Instance
$instance = 'VillageTelco_wgov-4942';
$instance_name = 'VillageTelco';

// Customer
$id_callplan = 223;
$id_didgroup = 7;
$accountnumber_len = 10;
$balance = 2424;
$activated = 1;
$status = 1;
$simultaccess = 1;
$currency = 'EUR';
$typepaid = 1;
$sip_buddy = $iax_buddy = 1;
$language = 'en';
$voicemail_enabled = 1;
$country = 'USA';

// #Account
$units = 1;

$method = 'Create_Customer';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$time_start = microtime(true);
$arr_result = $webservice -> $method ($security_key, $instance, $id_callplan, $id_didgroup, $units, $accountnumber_len, $balance, $activated, $status, $simultaccess, $currency, $typepaid, $sip_buddy, $iax_buddy, $language, $voicemail_enabled, $country);
$time_end = microtime(true);
$time = $time_end - $time_start;

print_r ($arr_result);
echo (">>>>>>> RUNNING TIME = $time secs\n\n\n");
