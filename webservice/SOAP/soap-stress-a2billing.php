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

/*
 * 	USAGE : http://localhost/webservice/SOAP/test-callbackexec.php
 */

$disable_check_cp = true;
include '../lib/admin.defines.php';
require 'SOAP/Client.php';

$security_key = API_SECURITY_KEY;

// WebService URL
$endpoint = 'http://localhost/~areski/svn/asterisk2billing/trunk/webservice/SOAP/soap-a2billing-server.php';

/*
<message name="Create_CustomerRequest">
<part name="security_key" type="xsd:string" />
<part name="instance" type="xsd:string" />
<part name="id_callplan" type="xsd:integer" />
<part name="id_didgroup" type="xsd:integer" />
<part name="units" type="xsd:integer" />
<part name="accountnumber_len" type="xsd:integer" />
<part name="balance" type="xsd:float" />
<part name="activated" type="xsd:boolean" />
<part name="status" type="xsd:integer" />
<part name="simultaccess" type="xsd:integer" />
<part name="currency" type="xsd:string" />
<part name="typepaid" type="xsd:integer" />
<part name="sip" type="xsd:integer" />
<part name="iax" type="xsd:integer" />
<part name="language" type="xsd:string" />
<part name="voicemail_enabled" type="xsd:boolean" />
<part name="country" type="xsd:string" />
</message>
*/

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
$units = 10000;

// Create SOAP Client
$callback = new SOAP_Client($endpoint);
$callback -> setOpt("timeout", 0);

echo "<hr>#############   Create Account  ############# <br/><hr>";
$method = 'Create_Customer';
$time_start = microtime(true);
$params = array('security_key' => md5($security_key),
                'instance' => $instance,
                'id_callplan' => $id_callplan,
                'id_didgroup' => $id_didgroup,
                'units' => $units,
                'accountnumber_len' => $accountnumber_len,
                'balance' => $balance,
                'activated' => $activated,
                'status' => $status,
                'simultaccess' => $simultaccess,
                'currency' => $currency,
                'typepaid' => $typepaid,
                'sip_buddy' => $sip_buddy,
                'iax_buddy' => $iax_buddy,
                'language' => $language,
                'voicemail_enabled' => $voicemail_enabled,
                'country' => $country,
                );

$ans = $callback -> call($method, $params);

print_r($ans);

$time_end = microtime(true);
$time = $time_end - $time_start;

echo ("<br/>>>>>>>> ACCOUNT CREATED : $units <br/>");
echo (">>>>>>> RUNNING TIME = $time secs <br/><br/><br/><br/>");
