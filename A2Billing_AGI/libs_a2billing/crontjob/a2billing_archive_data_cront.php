#!/usr/bin/php -q
<?php 
/***************************************************************************
 *            a2billing_archive_data_cront.php
 *
 *  Fri Oct 28 11:51:08 2005
 *  Copyright  2005  User
 *  ADD THIS SCRIPT IN A CRONTAB JOB
 *
	crontab -e
	0 12 * * * php /var/lib/asterisk/agi-bin/libs_a2billing/crontjob/a2billing_archive_data_cront.php
	
	field	 allowed values
	-----	 --------------
	minute	 		0-59
	hour		 	0-23
	day of month	1-31
	month	 		1-12 (or names, see below)
	day of week	 	0-7 (0 or 7 is Sun, or use names)

	
****************************************************************************/

set_time_limit(0);
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
include_once (dirname(__FILE__)."/../db_php_lib/Class.Table.php");
include (dirname(__FILE__)."/../Class.A2Billing.php");

$A2B = new A2Billing();
$A2B -> load_conf($agi, NULL, 0, $idconfig);

$instance_table = new Table();

$from_month = $A2B->config["backup"]['archive_data_x_month'];
print LOGFILE_CRONT_ARCHIVE_DATA;exit;

write_log(LOGFILE_CRONT_ARCHIVE_DATA, basename(__FILE__).' line:'.__LINE__."[#### ARCHIVING DATA BEGIN ####]");
if (!$A2B -> DbConnect()){				
	echo "[Cannot connect to the database]\n";
	write_log(LOGFILE_CRONT_ARCHIVE_DATA, basename(__FILE__).' line:'.__LINE__."[Cannot connect to the database]");
	exit;
}

if($A2B->config["database"]['dbtype'] == "postgres"){
	$condition = "CURRENT_TIMESTAMP - interval '$from_month months' > c.starttime";
}else{
	$condition = "DATE_SUB(NOW(),INTERVAL $from_month MONTH) > starttime";
}

$value = "SELECT sessionid,uniqueid,username,nasipaddress,starttime,stoptime,sessiontime,calledstation,startdelay,stopdelay,terminatecause,usertariff,calledprovider,calledcountry,calledsub,calledrate,sessionbill,destination,id_tariffgroup,id_tariffplan,id_ratecard,id_trunk,sipiax,src,id_did,buyrate,buycost,id_card_package_offer,real_sessiontime FROM cc_call where $condition";
$func_fields = "sessionid,uniqueid,username,nasipaddress,starttime,stoptime,sessiontime,calledstation,startdelay,stopdelay,terminatecause,usertariff,calledprovider,calledcountry,calledsub,calledrate,sessionbill,destination,id_tariffgroup,id_tariffplan,id_ratecard,id_trunk,sipiax,src,id_did,buyrate,buycost,id_card_package_offer,real_sessiontime";
$func_table = 'cc_call_archive';
$id_name = "";
$subquery = true;
$result = $instance_table -> Add_table ($A2B -> DBHandle, $value, $func_fields, $func_table, $id_name,$subquery);


$fun_table = "cc_call";
$result = $instance_table -> Delete_table ($A2B -> DBHandle, $condition, $fun_table);

write_log(LOGFILE_CRONT_ARCHIVE_DATA, basename(__FILE__).' line:'.__LINE__."[#### ARCHIVING DATA END ####]");

?>