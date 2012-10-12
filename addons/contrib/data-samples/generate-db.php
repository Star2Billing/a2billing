#!/usr/bin/php -q
<?php
/***************************************************************************
 *
 *            generate-db.php
 *
 *
 *  9 September 2008
 *  Purpose: generate DB in batch for testing performance
 *
 *  USAGE : ./generate-db.php --debug
 *
****************************************************************************/

exit;

set_time_limit(0);
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

require '../../../common/lib/admin.defines.php';

$verbose = 2;

$cli_args = arguments($argv);

// VERBOSITY
if (!empty($cli_args['debug']) || !empty($cli_args['d']))
    $verbose=3;
else if (!empty($cli_args['verbose']) || !empty($cli_args['v']))
    $verbose=2;
else if (!empty($cli_args['silent']) || !empty($cli_args['q']))
    $verbose=0;

// ---------------- Parameters ---------------------

// nb provider
$nb_provider = 1;

// nb_trunk
$nb_trunk = 1;

// nb call plan
$nb_callplan= 1;

// nb ratecard
$nb_ratecard= 1;

//nb rate
$nb_rate = 800000;

// nb customer to create
$nb_customer = 1;

// customer balance
$customer_balance = 1;

// callerid
$nb_callerid = 1;

// history
$nb_history =1;

//payment
$nb_payment = 1;

//refill
$nb_refill = 1;

echo "Going to add :\n---------------------------\n";
echo "nb_provider=$nb_provider\n nb_trunk=$nb_trunk\n nb_callplan=$nb_callplan\n ".
        " nb_ratecard=$nb_ratecard\n nb_rate=$nb_rate\n nb_customer=$nb_customer\n customer_balance=$customer_balance\n ".
        " nb_callerid=$nb_callerid\n nb_history=$nb_history\n nb_payment=$nb_payment nb_refill=$nb_refill\n";

//cdr
// $back_days = 1;
// $amount_cdr = 10;

$A2B = new A2Billing();
$A2B -> load_conf($agi, NULL, 0, $idconfig);

if (!$A2B -> DbConnect()) {
    echo "[Cannot connect to the database]\n";
    write_log(LOGFILE_CRONT_CHECKACCOUNT, basename(__FILE__).' line:'.__LINE__."[Cannot connect to the database]");
    exit;
}

$instance_table = new Table();

// -----------------------------------
// CREATE PROVIDER
// -----------------------------------

for ($i=0; $i< $nb_provider;$i++) {
    if($verbose>1) echo "CREATE PROVIDER : $i\n";
    $id_name = intval(microtime(true)*10000) + rand(100000000, 999999999);
    $query= "INSERT INTO cc_provider (provider_name, description)".
            "VALUES ('PROVIDER : $id_name', 'AUTOMATIZED DESCRIPTION');";
    // echo "$query<br>";
    $instance_table -> SQLExec ($A2B -> DBHandle, $query);
}

$instance_provider = new Table("cc_provider","id");
$result_provider_id = $instance_provider-> Get_list($A2B -> DBHandle, null, null, null, null, null, 1000, 1);
$nb_db_provider = sizeof($result_provider_id);

if($verbose > 0) echo "CREATE PROVIDER : $nb_db_provider <br><br>\n\n";

// -----------------------------------
// CREATE TRUNK
// -----------------------------------
for ($i=0;$i<$nb_trunk;$i++) {

    if($verbose>1) echo "CREATE TRUNK : $i\n";
    $id_provider= $result_provider_id [rand(0,$nb_db_provider )] ['id'];
    $name = intval(microtime(true)*10000) + rand(100000000, 999999999);
    $query = "INSERT INTO cc_trunk (id_provider, trunkcode, providertech, providerip, failover_trunk, inuse, maxuse, if_max_use, status ) values".
                " ('$id_provider', 'Trunk : $name', 'SIP', 'Test', '-1', '0', '-1', '0', '1');" ;
    // echo "$query<br>";
    $instance_table -> SQLExec ($A2B -> DBHandle, $query);
}

$instance_trunk = new Table("cc_trunk","id_trunk");
$result_trunk_id = $instance_trunk-> Get_list($A2B -> DBHandle, null, null, null, null, null, 1000, 1);
$nb_db_trunk = sizeof($result_trunk_id);

if($verbose > 0) echo "CREATE TRUNK : $nb_db_trunk <br><br>\n\n";

// -----------------------------------
// CREATE CALLPLAN
// -----------------------------------

$id_callplan_list = array();

for ($i=0; $i< $nb_callplan;$i++) {

    if($verbose > 1) echo "CREATE CALLPLAN : $i\n";
    $id_name = intval(microtime(true)*10000) + rand(100000000, 999999999);

    $QUERY_FIELDS = "iduser ,idtariffplan ,tariffgroupname ,lcrtype ,creationdate ,removeinterprefix ,id_cc_package_offer";
    $QUERY_VALUES = "'0', '0', 'CALLPLAN : $id_name', '0',NOW() , '0', '-1'" ;
    $QUERY_ID = "id" ;
    $QUERY_TABLE = "cc_tariffgroup";

    $id_tmp = $instance_table -> Add_table ($A2B -> DBHandle,$QUERY_VALUES, $QUERY_FIELDS,$QUERY_TABLE,$QUERY_ID);
    $id_callplan_list [$i] = $id_tmp;
}

$instance_callplan = new Table("cc_tariffgroup","id");
$id_callplan_list = $instance_callplan-> Get_list($A2B -> DBHandle, null, null, null, null, null, 1000, 1);
$nb_db_callplan = sizeof($id_callplan_list);

if($verbose > 0) echo "CREATE CALLPLAN : ".$nb_db_callplan." <br><br>\n\n";

// -----------------------------------
// CREATE RATECARD
// -----------------------------------

$id_ratecards_list = array();
for ($i=0; $i< $nb_ratecard;$i++) {
    if($verbose > 1) echo "CREATE RATECARD : $i\n";
    $id_trunk= $result_trunk_id [rand(0,$nb_db_trunk )] ['id'];
    $id_name = intval(microtime(true)*10000) + rand(100000000, 999999999);
    $QUERY_FIELDS = "tariffname, startingdate, expirationdate, id_trunk, description, dnidprefix, calleridprefix, creationdate";
    $QUERY_VALUES = "'RATECARD : $id_name', NOW(), '2033-09-03 01:24:33', '$id_trunk', 'Automatized Description', 'all', 'all', NOW()" ;
    $QUERY_ID = "id" ;
    $QUERY_TABLE = "cc_tariffplan";

    $id_tmp = $instance_table -> Add_table ($A2B -> DBHandle,$QUERY_VALUES, $QUERY_FIELDS,$QUERY_TABLE,$QUERY_ID);
    $id_ratecards_list [$i] = $id_tmp;
}

$instance_ratecard = new Table("cc_tariffplan","id");
$result_ratecard_id = $instance_ratecard-> Get_list($A2B -> DBHandle, null, null, null, null, null, 1000, 1);
$nb_db_ratecard = sizeof($result_ratecard_id);

if($verbose > 0) echo "CREATE RATECARD : $nb_db_ratecard <br><br>\n\n";

// -----------------------------------
// LIST PREFIX
// -----------------------------------

$instance_prefix = new Table("cc_prefix","id,prefixe,destination");
$result_prefix = $instance_prefix-> Get_list($A2B -> DBHandle, null, null, null, null, null, 1000, 1);
$nb_db_prefix = sizeof($result_prefix);

if($verbose > 0) echo "LIST PREFIX : $nb_db_prefix <br><br>\n\n";

// LINK RATECARD CALLPLAN
$id_callplan_list;
$id_ratecards_list;

// -----------------------------------
// CREATE RATES
// -----------------------------------

$list_time = array('1','30','60');

$id_ratecard = $result_ratecard_id [rand(0,$nb_db_ratecard )] ['id'];

if ($nb_db_prefix >= $nb_rate) {
    $prefix = "00".$result_prefix[$i]['prefixe'];
    $dest = $result_prefix[$i]['destination'];
} else {
    $ratio = intval( $nb_rate / $nb_db_prefix );
    $idx = intval($i /$ratio );
    $prefix = ($i%2 == 0) ? "":"0";
    $sub_prefix = rand(1,9).rand(1,9).rand(1.9);
    $prefix .= "00".$result_prefix[$idx]['prefixe'].$sub_prefix;
    $dest = $result_prefix[$i]['destination'];
}

$block = $list_time[rand(0,count($list_time))];
$buyrate = rand(5,20) /1000;
$sellrate = $buyrate * ((rand(0,count($list_time)))+1);

for ($i=0; $i< $nb_rate;$i++) {

    $query = "INSERT INTO cc_ratecard (idtariffplan, dialprefix, destination, buyrate, buyrateinitblock, buyrateincrement, rateinitial, initblock, billingblock, startdate, stopdate, starttime, endtime, id_trunk, id_outbound_cidgroup) values" .
            "('$id_ratecard', '$prefix', '$dest', '$buyrate', '$block', '$block', '$sellrate', '$block', '$block', NOW(), '2020-12-31 06:06:06', '0', '10079', '-1', '-1');";
    $instance_table -> SQLExec ($A2B -> DBHandle, $query);

    if (($i % 1000)==0) {
        if($verbose > 1)
            echo "RATE CREATED : $i\n";
    }
}

$query = "SELECT count(*) FROM cc_ratecard;";
$nb_db_rates = $instance_table -> SQLExec ($A2B -> DBHandle, $query, 1);
if($verbose > 0) echo "TOTAL DB RATES : ".$nb_db_rates[0][0]." <br><br>\n\n";

// -----------------------------------
// CREATE CARDS
// -----------------------------------
$instance_callplan = new Table("cc_tariffgroup","id");
$result_callplan_id = $instance_callplan-> Get_list($A2B -> DBHandle, null, null, null, null, null, 1000, 1);
$nb_db_callplan = sizeof($result_callplan_id);

for ($i=0;$i<$nb_customer;$i++) {

    if($verbose > 1)
        echo "CREATE CARD : $i\n";
    $id_callplan= $result_callplan_id [rand(0,$nb_db_callplan )] ['id'];
    $array_card_generated  = gen_card_with_alias("cc_card", 0, 10, $A2B -> DBHandle);
    $card_number = $array_card_generated[0];
    $card_alias = $array_card_generated[1];
    $pass = MDP_NUMERIC(5).MDP_STRING(10).MDP_NUMERIC(5);
    $query = "INSERT INTO cc_card (username, useralias, uipass, id_group, credit, language, tariff, id_didgroup, status, simultaccess, currency, runservice, autorefill, initialbalance, typepaid, enableexpire, expirationdate, expiredays, voicemail_permitted, voicemail_activated, invoiceday, lastname, firstname, country, id_timezone, sip_buddy, iax_buddy, inuse, credit_notification, notify_email ) values".
            "('1111', '11111', '1111', '1', '10', 'en', '1', '-1', '-1', '1', 'USD', '0', '0', '0', '0', '0', '2018-09-02 23:21:33', '0', '1', '0', '0', '11111', 'card', 'AFG', '1', '0', '0', '0', '-1', '0'); ";
    $instance_table -> SQLExec ($A2B -> DBHandle, $query);

}

$query = "SELECT count(*) FROM cc_card;";
$nb_db_card = $instance_table -> SQLExec ($A2B -> DBHandle, $query, 1);
if($verbose > 0) echo "TOTAL DB CARDS : ".$nb_db_card[0][0]." <br><br>\n\n";

// Get a list of card
$instance_card = new Table("cc_card","id");
$result_card_id = $instance_card -> Get_list($A2B -> DBHandle, null, null, null, null, null, 1000, 1);
$nb_db_card = sizeof($result_card_id);

// -----------------------------------
// CALLERID
// -----------------------------------
for ($i=0;$i<$nb_callerid;$i++) {
    if($verbose > 1) echo "CREATE CALLERID : $i\n";
    $id_card= $result_card_id [rand(0,$nb_db_card )] ['id'];
    $cid = rand(100000000,999999999);
    $query = "INSERT INTO cc_callerid (cid ,id_cc_card ,activated)VALUES".
             " ('$cid', '$id_card', 't');" ;
    $instance_table -> SQLExec ($A2B -> DBHandle, $query);
}

$query = "SELECT count(*) FROM cc_callerid;";
$nb_db_cc_callerid = $instance_table -> SQLExec ($A2B -> DBHandle, $query, 1);
if($verbose > 0) echo "TOTAL DB CALLERID : ".$nb_db_cc_callerid[0][0]." <br><br>\n\n";

// -----------------------------------
// CREATE HISTORY
// -----------------------------------
for ($i=0;$i<$nb_history;$i++) {
    if($verbose > 1) echo "CREATE CARD HISTORY : $i\n";
    $id_card= $result_card_id [rand(0,$nb_db_card )] ['id'];

    $query = "INSERT INTO cc_card_history (id_cc_card ,datecreated ,description) VALUES".
                " ( '$id_card',NOW() , 'Automatized history by script');" ;
    $instance_table -> SQLExec ($A2B -> DBHandle, $query);
}

$query = "SELECT count(*) FROM cc_card_history;";
$nb_db_cc_card_history = $instance_table -> SQLExec ($A2B -> DBHandle, $query, 1);
if($verbose > 0) echo "TOTAL DB CARD HISTORY : ".$nb_db_cc_card_history[0][0]." <br><br>\n\n";

// -----------------------------------
// CREATE REFILL
// -----------------------------------
for ($i=0;$i<$nb_refill;$i++) {
    if($verbose > 1) echo "CREATE CARD REFILL : $i\n";
    $id_card= $result_card_id [rand(0,$nb_db_card )] ['id'];
    $amount = rand(10,30);
    $query = "INSERT INTO cc_logrefill (date ,credit ,card_id ,reseller_id ,description)VALUES ".
    "(NOW() , '$amount', '$id_card', NULL , 'Automatized description');" ;
    $instance_table -> SQLExec ($A2B -> DBHandle, $query);
}

$query = "SELECT count(*) FROM cc_callerid;";
$nb_db_cc_logrefill = $instance_table -> SQLExec ($A2B -> DBHandle, $query, 1);
if($verbose > 0) echo "TOTAL DB LOG REFILL : ".$nb_db_cc_logrefill[0][0]." <br><br>\n\n";

// -----------------------------------
// CREATE PAYMENT
// -----------------------------------
for ($i=0;$i<$nb_payment;$i++) {
    if($verbose > 1) echo "CREATE CARD PAYMENT : $i\n";
    $id_card= $result_card_id [rand(0,$nb_db_card )] ['id'];
    $amount = rand(10,30);
    $query = "INSERT INTO cc_logpayment (date ,payment ,card_id ,reseller_id ,id_logrefill ,description ,added_refill)VALUES".
     "(NOW() , '$amount', '$id_card', NULL , NULL , 'Automatized description', '0');";
    $instance_table -> SQLExec ($A2B -> DBHandle, $query);
}

$query = "SELECT count(*) FROM cc_logpayment;";
$nb_db_cc_logpayment = $instance_table -> SQLExec ($A2B -> DBHandle, $query, 1);
if($verbose > 0) echo "TOTAL DB LOG PAYMENT : ".$nb_db_cc_logpayment[0][0]." <br><br>\n\n";

exit();
