#!/usr/bin/php -q
<?php
/***************************************************************************
 *
 *            generate-cdr.php
 *
 *
 *  20 May 2008
 *  Purpose: generate CDR in batch for testing performance
 *
 *  USAGE : ./generate-cdr.php --debug --amount_cdr=1000
 *
****************************************************************************/

exit();
// CHECK ALL AND ENSURE IT WORKS / NOT URGENT

set_time_limit(0);
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

require '../../../common/lib/admin.defines.php';

$verbose = 3;
//$back_days = 15;
$back_days = 10;
$amount_cdr = 10;
$cardid = 3;
$id_ratecard = 1;
$calledstation = '670411000000';
$destination = 670; // Belgium
$nb_cdr_flush = 500;

$cli_args = arguments($argv);

// VERBOSITY
if (!empty($cli_args['debug']) || !empty($cli_args['d']))
    $verbose=3;
else if (!empty($cli_args['verbose']) || !empty($cli_args['v']))
    $verbose=2;
else if (!empty($cli_args['silent']) || !empty($cli_args['q']))
    $verbose=0;

// AMOUNT CDR
if (!empty($cli_args['amount_cdr']))
    $amount_cdr = $cli_args['amount_cdr'];

$A2B = new A2Billing();
$A2B -> load_conf($agi, NULL, 0, $idconfig);

if (!$A2B -> DbConnect()) {
    echo "[Cannot connect to the database]\n";
    write_log(LOGFILE_CRONT_CHECKACCOUNT, basename(__FILE__).' line:'.__LINE__."[Cannot connect to the database]");
    exit;
}

//$A2B -> DBHandle
$instance_table = new Table();

$A2B -> DBHandle -> Execute('SET AUTOCOMMIT=1');

$c_qry_header = "INSERT INTO cc_call ( sessionid, uniqueid,  starttime, stoptime, sessiontime, calledstation, " .
                " terminatecauseid,   sessionbill, id_tariffgroup, src, buycost, " .
                "id_card_package_offer, card_id, id_ratecard, destination) VALUES \n";

$qry = '';

$cdr_per_day = intval($amount_cdr / $back_days);

$nb_cdr = 0;
for ($i=1 ; $i <= $back_days; $i++) {
    echo "Day : $i...\n";

    for ($j=1 ; $j <= $cdr_per_day; $j++) {
        $nb_cdr ++;
        $maxhour = sprintf("%02d",rand(0,23));
        $minhour = sprintf("%02d",rand(0,23));
        if ($maxhour<$minhour) {
                $temp = $maxhour; $maxhour = $minhour; $minhour = $maxhour;
        }
        $startdate_toinsert = date("Y-m-d", strtotime("-$i day")).' '.$minhour.":".sprintf("%02d",rand(0,59));
        $enddate_toinsert = date("Y-m-d", strtotime("-$i day")).' '.$maxhour.":".sprintf("%02d",rand(0,59));
        $uniqueid = date("Y-m-d", strtotime("-$i day")).'_'.rand(0,10000000);
        $sessiontime = rand(0,500);

        $c_qry_value = " ( 'IAX2/areskiax-3', '$uniqueid', '$startdate_toinsert', '$enddate_toinsert', $sessiontime, " .
                "'$calledstation', 1,   1.2000, 1,  '1856254697', 0.40000, 0, $cardid, $id_ratecard, $destination) \n";

        if (strlen($qry)==0) {
            $qry = $c_qry_header.$c_qry_value;
        } else {
            $qry .= ' , '.$c_qry_value;
        }

        if (($nb_cdr % $nb_cdr_flush) == 0) {
            if ($verbose)
                echo "Processing CDR generation for $nb_cdr_flush CDRs \n";

            if ($verbose >=3) echo "CDR No=$nb_cdr --> $qry\n";

            $A2B -> DBHandle -> Execute('BEGIN;');
            $instance_table -> SQLExec ($A2B -> DBHandle, $qry);
            $A2B -> DBHandle -> Execute('COMMIT;');
            $qry = '';
        }

    }
}

if (strlen($qry)>0) {
    echo "CDR No=$nb_cdr --> $qry\n";
    $A2B -> DBHandle -> Execute('BEGIN;');
    $instance_table -> SQLExec ($A2B -> DBHandle, $qry);
    $A2B -> DBHandle -> Execute('COMMIT;');
}

if ($verbose)
    echo "End of the process \n\n";
