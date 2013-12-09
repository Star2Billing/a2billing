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

/***************************************************************************
 *            currencies_update_yahoo.php
 *
 *  ADD THIS SCRIPT IN A CRONTAB JOB
 *
    crontab -e
    0 6 * * * php /usr/local/a2billing/Cronjobs/currencies_update_yahoo.php

    field	 allowed values
    -----	 --------------
    minute	 		0-59
    hour		 	0-23
    day of month	1-31
    month	 		1-12 (or names, see below)
    day of week	 	0-7 (0 or 7 is Sun, or use names)

    The sample above will run the script every day at 6AM

****************************************************************************/

set_time_limit(120);
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

include (dirname(__FILE__) . "/lib/admin.defines.php");
include (dirname(__FILE__) . "/lib/ProcessHandler.php");

if (!defined('PID')) {
    define("PID", "/var/run/a2billing/currencies_update_yahoo_pid.php");
}

// CHECK IF THE CRONT PROCESS IS ALREADY RUNNING

$prcHandler = new ProcessHandler();

if ($prcHandler->isActive()) {
    die(); // Already running!
} else {
    $prcHandler->activate();
}

$FG_DEBUG = 0;
$A2B = new A2Billing();
$A2B -> load_conf($agi, DEFAULT_A2BILLING_CONFIG, 1);

// DEFINE FOR THE DATABASE CONNECTION
define ("BASE_CURRENCY", strtoupper($A2B->config["global"]['base_currency']));

$A2B -> load_conf($agi, NULL, 0, $idconfig);

write_log(LOGFILE_CRONT_CURRENCY_UPDATE, basename(__FILE__).' line:'.__LINE__."[#### START CURRENCY UPDATE ####]");

if (!$A2B -> DbConnect()) {
    echo "[Cannot connect to the database]\n";
    write_log(LOGFILE_CRONT_CURRENCY_UPDATE, basename(__FILE__).' line:'.__LINE__."[Cannot connect to the database]");
    exit;
}

$instance_table = new Table();
$A2B -> set_instance_table ($instance_table);

$return = currencies_update_yahoo($A2B -> DBHandle, $A2B -> instance_table);
write_log(LOGFILE_CRONT_CURRENCY_UPDATE, basename(__FILE__).' line:'.__LINE__.$return, 0);

die();
