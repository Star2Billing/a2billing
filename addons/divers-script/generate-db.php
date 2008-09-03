#!/usr/bin/php -q
<?php


set_time_limit(0);
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

require("../../common/lib/admin.defines.php");

$cli_args = arguments($argv);
//nb provider
$nb_provider = 5;

//nb_trunk
$nb_trunk = 5;


//nb call plan
$nb_callplan= 5;
//nb ratecard
$nb_ratecard= 10;

//nb rate 
$nb_rate = 100;

$customer_nb = 10;
$customer_balance = 10;
// callerid
$nb_callerid = 10;
// history
$nb_history =10;

//cdr
$back_days = 1;
$amount_cdr = 10;

//payment

$nb_payment = 10;
//refill
$nb_refill = 10;

$verbose = 1;
// VERBOSITY 
if (!empty($cli_args['debug']) || !empty($cli_args['d']))
	$verbose=3;
else if (!empty($cli_args['verbose']) || !empty($cli_args['v']))
	$verbose=2;
else if (!empty($cli_args['silent']) || !empty($cli_args['q']))
	$verbose=0;
	
$A2B = new A2Billing();
$A2B -> load_conf($agi, NULL, 0, $idconfig);

if (!$A2B -> DbConnect()){
	echo "[Cannot connect to the database]\n";
	write_log(LOGFILE_CRONT_CHECKACCOUNT, basename(__FILE__).' line:'.__LINE__."[Cannot connect to the database]");
	exit;
}

$instance_table = new Table();



//PROVIDER
for($i=0; $i< $nb_provider;$i++)
{

 if($verbose>0) echo "CREATE PROVIDER : $i\n";	
 $id_name = microtime(true)*10000;
 $query= "INSERT INTO cc_provider (id ,provider_name ,creationdate ,description)". 
"VALUES ('PROVIDER : $id_name',NOW() , 'AUTOMATIZED DESCRIPTION');";
 $instance_table -> SQLExec ($A2B -> DBHandle, $query);
}

$instance_provider = new Table("cc_provider","id");
$result_provider_id = $instance_provider-> Get_list($A2B -> DBHandle) ;
$nb_db_provider = sizeof($result_provider_id);

//TRUNK

for($i=0;$i<$nb_trunk;$i++)
	{
	 if($verbose>0) echo "CREATE TRUNK : $i\n";		
	$id_provider= $result_provider_id [rand(0,$nb_db_provider )] ['id'];
	$name = microtime(true)*10000;
	$query = "INSERT INTO cc_trunk (id_provider, trunkcode, providertech, providerip, failover_trunk, inuse, maxuse, if_max_use, status, creationdate ) values".
			" ('$id_provider', 'Trunk : $name', 'SIP', 'Test', '-1', '0', '-1', '0', '1', now());" ;
	
	$instance_table -> SQLExec ($A2B -> DBHandle, $query);
	}


$id_callplan_list = array();
//CALLPLAN
for($i=0; $i< $nb_callplan;$i++)
{
   if($verbose>0) echo "CREATE CALLPLAN : $i\n";	
 $id_name = microtime(true)*10000;
 
 $QUERY_FIELDS = "iduser ,idtariffplan ,tariffgroupname ,lcrtype ,creationdate ,removeinterprefix ,id_cc_package_offer";
 $QUERY_VALUES = "'0', '0', 'CALLPLAN : $id_name', '0',NOW() , '0', '-1'" ;
 $QUERY_ID = "id" ;
 $QUERY_TABLE = "cc_tariffgroup";

 $id_tmp = $instance_table -> Add_table ($A2B -> DBHandle,$QUERY_VALUES, $QUERY_FIELDS,$QUERY_TABLE,$QUERY_ID);
 $id_callplan_list [$i] = $id_tmp; 
 
}

//RATECARD
$instance_trunk = new Table("cc_trunk","id");
$result_trunk_id = $instance_trunk-> Get_list($A2B -> DBHandle) ;
$nb_db_trunk = sizeof($result_trunk_id);

$id_ratecards_list = array();
for($i=0; $i< $nb_ratecard;$i++)
{
  if($verbose>0) echo "CREATE RATECARD : $i\n";	
 $id_trunk= $result_trunk_id [rand(0,$nb_db_trunk )] ['id'];	
 $id_name = microtime(true)*10000;
 $QUERY_FIELDS = "tariffname, startingdate, expirationdate, id_trunk, description, dnidprefix, calleridprefix, creationdate";
 $QUERY_VALUES = "'RATECARD : $id_name', NOW(), '2033-09-03 01:24:33', '$id_trunk', 'Automatized Description', 'all', 'all', NOW()" ;
 $QUERY_ID = "id" ;
 $QUERY_TABLE = "cc_tariffplan";

 $id_tmp = $instance_table -> Add_table ($A2B -> DBHandle,$QUERY_VALUES, $QUERY_FIELDS,$QUERY_TABLE,$QUERY_ID);
 $id_ratecards_list [$i] = $id_tmp; 
}

//LINK RATECARD CALLPLAN

$id_callplan_list;
$id_ratecards_list;
//RATES


$instance_ratecard = new Table("cc_tariffplan","id");
$result_ratecard_id = $instance_ratecard-> Get_list($A2B -> DBHandle) ;
$nb_db_ratecard = sizeof($result_ratecard_id);

$instance_prefix = new Table("cc_prefix","id,prefixe,destination");
$result_prefix = $instance_prefix-> Get_list($A2B -> DBHandle) ;
$nb_db_prefix = sizeof($result_prefix);
$list_time = array('1','30','60');
for($i=0; $i< $nb_rate;$i++){

  if($verbose>0) echo "CREATE RATE : $i\n";	
$id_ratecard= $result_ratecard_id [rand(0,$nb_db_ratecard )] ['id'];	
	
if($nb_db_prefix>= $nb_rate){
	$prefix = "00".$result_prefix[$i]['prefixe'];
	$dest = $result_prefix[$i]['destination'];	
}else{
	$ratio = intval( $nb_rate / $nb_db_prefix );
	$idx = intval($i /$ratio );
	$prefix = ($i%2 == 0) ? "":"0";
	$sub_prefix = rand(1,9).rand(1,9).rand(1.9); 
	$prefix .= "00".$result_prefix[$idx]['prefixe'].$sub_prefix;
	$dest = $result_prefix[$i]['destination'];	
}
$block = $list_time[$i%3];
$buyrate = rand(5,20) /1000;
$sellrate = $buyrate * (($i%3)+1);
$query="INSERT INTO cc_ratecard (idtariffplan, dialprefix, destination, buyrate, buyrateinitblock, buyrateincrement, rateinitial, initblock, billingblock, startdate, stopdate, starttime, endtime, id_trunk, id_outbound_cidgroup) values" .
"('$id_ratecard', '$prefix', '$dest', '$buyrate', '$block', '$block', '$sellrate', '$block', '$block', NOW(), '2020-12-31 06:06:06', '0', '10079', '-1', '-1');";
$instance_table -> SQLExec ($A2B -> DBHandle, $query);
}





// CARDS
$instance_callplan = new Table("cc_tariffgroup","id");
$result_callplan_id = $instance_callplan-> Get_list($A2B -> DBHandle) ;
$nb_db_callplan = sizeof($result_callplan_id);

for($i=0;$i<$customer_nb;$i++)
{
	 if($verbose>0) echo "CREATE CARD : $i\n";	
	$id_callplan= $result_callplan_id [rand(0,$nb_db_callplan )] ['id'];	
	$array_card_generated  = gen_card_with_alias("cc_card", 0, 10);
	$card_number = $array_card_generated[0];
	$card_alias = $array_card_generated[1];
	$pass = MDP_NUMERIC(10);
	$query = "INSERT INTO cc_card (username, useralias, uipass, id_group, credit, language, tariff, id_didgroup, id_agent, status, activatedbyuser, simultaccess, currency, runservice, autorefill, initialbalance, typepaid, enableexpire, expirationdate, expiredays, voicemail_permitted, voicemail_activated, invoiceday, lastname, firstname, country, id_timezone, sip_buddy, iax_buddy, inuse, template_invoice, template_outstanding, credit_notification, notify_email, creationdate ) values". 
			"('$card_number', '$card_alias', '$pass', '1', '10', 'en', '$id_callplan', '-1', '-1', '1', 't', '1', 'USD', '0', '0', '0', '0', '0', '2018-09-02 23:21:33', '0', '1', '0', '0', '$card_number', 'card', 'AFG', '1', '0', '0', '0', 'invoice exemple.tpl', 'outstanding exemple.tpl', '-1', '0', now()); ";
	$instance_table -> SQLExec ($A2B -> DBHandle, $query);
	
}

$instance_card = new Table("cc_card","id");
$result_card_id = $instance_card -> Get_list($A2B -> DBHandle) ;
$nb_db_card = sizeof($result_card_id);

// CALLERID	
	for($i=0;$i<$nb_callerid;$i++)
	{
	 if($verbose>0) echo "CREATE CALLERID : $i\n";	
	$id_card= $result_card_id [rand(0,$nb_db_card )] ['id'];
    $cid = rand(100000000,999999999);
	$query = "INSERT INTO cc_callerid (cid ,id_cc_card ,activated)VALUES".
	         " ('$cid', '$id_card', 't');" ;
	$instance_table -> SQLExec ($A2B -> DBHandle, $query);
	}
	
// HISTORY
	for($i=0;$i<$nb_history;$i++)
	{
	 if($verbose>0) echo "CREATE CARD HISTORY : $i\n";	
	$id_card= $result_card_id [rand(0,$nb_db_card )] ['id'];
    
	$query = "INSERT INTO cc_card_history (id_cc_card ,datecreated ,description) VALUES".
				" ( '$id_card',NOW() , 'Automatized history by script');" ;
	$instance_table -> SQLExec ($A2B -> DBHandle, $query);
	}


	for($i=0;$i<$nb_refill;$i++)
	{
	 if($verbose>0) echo "CREATE CARD REFILL : $i\n";	
	$id_card= $result_card_id [rand(0,$nb_db_card )] ['id'];
     $amount = rand(10,30);
	$query = "INSERT INTO cc_logrefill (date ,credit ,card_id ,reseller_id ,description)VALUES ".
"(NOW() , '$amount', '$id_card', NULL , 'Automatized description');" ;
	$instance_table -> SQLExec ($A2B -> DBHandle, $query);
	}
	

	
	
	

	for($i=0;$i<$nb_payment;$i++)
	{
	 if($verbose>0) echo "CREATE CARD PAYMENT : $i\n";	
	$id_card= $result_card_id [rand(0,$nb_db_card )] ['id'];
    $amount = rand(10,30);
	$query = "INSERT INTO cc_logpayment (date ,payment ,card_id ,reseller_id ,id_logrefill ,description ,added_refill)VALUES".
	 "(NOW() , '$amount', '$id_card', NULL , NULL , 'Automatized description', '0');";
	$instance_table -> SQLExec ($A2B -> DBHandle, $query);
	}



?>