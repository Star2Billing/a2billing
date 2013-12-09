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
 *            a2billing_batch_cache.php
 *
 *  Fri Oct 21 11:51 2008
 *  Copyright  2008  A2Billing
 *  ADD THIS SCRIPT IN A CRONTAB JOB
 *
 *  Description :
 *  This script will read the sqlite Database and import the CDR to the main DB
 *  The import is processed by block in order to optimize the queries
 *
 *
    crontab -e
    * / 15 * * * * php /usr/local/a2billing/Cronjobs/a2billing_batch_cache.php

    field	 allowed values
    -----	 --------------
    minute	 		0-59
    hour		 	0-23
    day of month	1-31
    month	 		1-12 (or names, see below)
    day of week	 	0-7 (0 or 7 is Sun, or use names)

    #Run command every 5 minutes during 6-13 hours
    * / 5 6-13 * * mon-fri test.script    !!! no space between * / 5

****************************************************************************/

set_time_limit(0);
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

include (dirname(__FILE__) . "/lib/admin.defines.php");
include (dirname(__FILE__) . "/lib/ProcessHandler.php");

if (!defined('PID')) {
    define("PID", "/var/run/a2billing/a2billing_batch_cache_pid.php");
}

// CHECK IF THE CRONT PROCESS IS ALREADY RUNNING
$pH= new ProcessHandler();
if ($pH->isActive()) {
        die(); // Already running!
        } else {
                $pH->activate();
                }

$verbose_level = 1;
$nb_record = 100;
$wait_time = 10;

$A2B = new A2Billing();
$A2B->load_conf($agi, NULL, 0, $idconfig);

write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[#### IMPORT CACHE CRONT START ####]");

if (!$A2B->DbConnect()) {
    if ($verbose_level >= 1)
        echo "[Cannot connect to the database]\n";
    write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[Cannot connect to the database]");
    exit;
}

$instance_table = new Table();

if ($A2B->config["global"]['cache_enabled']) {
    if (empty ($A2B->config["global"]['cache_path'])) {
        if ($verbose_level >= 1)
            echo "[Path to the cache is not defined]\n";

        write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[Path to the cache is not defined]");
        exit;
    }

    if (!file_exists($A2B->config["global"]['cache_path'])) {
        if ($verbose_level >= 1)
            echo "[File doesn't exist or permission denied]\n";

        write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[File doesn't exist or permission denied]");
        exit;
    }

    // Open Sqlite
    if ($db = sqlite_open($A2B->config["global"]['cache_path'], 0666, $sqliteerror)) {

        for (;;) {
            // Select CDR
            $result = sqlite_array_query($db, "SELECT rowid , * from cc_call limit $nb_record", SQLITE_ASSOC);
            if (sizeof($result) > 0) {
                $column = "";
                $values = "";
                $delete_id = "( ";
                for ($i = 0; $i < sizeof($result); $i++) {
                    $j = 0;
                    if ($i == 0)
                        $values .= "( ";
                    else
                        $values .= ",( ";

                    $delete_id .= $result[$i]['rowid'];
                    if (sizeof($result) > 0 && $i < sizeof($result) - 1) {
                        $delete_id .= " , ";
                    }

                    foreach ($result[$i] as $key => $value) {
                        $j++;
                        if ($key == "rowid")
                            continue;
                        if ($i == 0) {
                            $column .= " $key ";
                            if ($j < sizeof($result[$i])) {
                                $column .= ",";
                            }
                        }
                        $values .= " '$value' ";
                        if ($j < sizeof($result[$i]))
                            $values .= ",";

                    }
                    $values .= " )";
                }
                $delete_id .= " )";
                $INSERT_QUERY = "INSERT INTO cc_call ( $column ) VALUES $values";
                if ($verbose_level >= 1)
                    echo "QUERY INSERT : [$INSERT_QUERY]\n";
                $instance_table->SQLExec($A2B->DBHandle, $INSERT_QUERY);
                $DELETE_QUERY = "DELETE FROM cc_call WHERE rowid in $delete_id";
                if ($verbose_level >= 1)
                    echo "QUERY DELETE : [$DELETE_QUERY]\n";
                sqlite_query($db, $DELETE_QUERY);
            }
            echo "Waiting ....\n";
            sleep($wait_time);
        }

    } else {
        if ($verbose_level >= 1)
            echo "[Error to connect to cache : $sqliteerror]\n";
        write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[Error to connect to cache : $sqliteerror]\n");
    }

}

if ($verbose_level >= 1)
    echo "#### END RECURRING SERVICES \n";

write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[#### BATCH PROCESS END ####]");
