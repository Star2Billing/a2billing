#!/usr/bin/php -q
<?php 
/***************************************************************************
 *            a2billing_batch_process.php
 *
 *  Fri Oct 28 11:51:08 2005
 *  Copyright  2005  User
 *  ADD THIS SCRIPT IN A CRONTAB JOB
 *
	crontab -e
	0 12 * * * php /var/lib/asterisk/agi-bin/libs_a2billing/crontjob/a2billing_batch_process.php
	
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
//dl("pgsql.so"); // remove "extension= pgsql.so !

include_once (dirname(__FILE__)."/lib/Class.Table.php");
include (dirname(__FILE__)."/lib/interface/constants.php");
include (dirname(__FILE__)."/lib/Class.A2Billing.php");
include (dirname(__FILE__)."/lib/Misc.php");
include (dirname(__FILE__)."/lib/Class.RateEngine.php");

$verbose_level=1;
// time to wait between every send in callback queue
$timing =60;
$group=100;


$A2B = new A2Billing();
$A2B -> load_conf($agi, NULL, 0, $idconfig);

write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[#### BATCH BEGIN ####]");

if (!$A2B -> DbConnect()){				
	echo "[Cannot connect to the database]\n";
	write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[Cannot connect to the database]");
	exit;
}


if ($A2B->config["database"]['dbtype'] == "postgres"){
	$UNIX_TIMESTAMP = "date_part('epoch',";
}else{
	$UNIX_TIMESTAMP = "UNIX_TIMESTAMP(";
}

//$A2B -> DBHandle
$instance_table = new Table();

$QUERY_COUNT_PHONENUMBERS = 'SELECT count(*) FROM cc_phonenumber , cc_phonebook , cc_campaign_phonebook, cc_campaign WHERE ';
//JOIN CLAUSE
$QUERY_COUNT_PHONENUMBERS .= 'cc_phonenumber.id_phonebook = cc_phonebook.id AND cc_campaign_phonebook.id_phonebook = cc_phonebook.id AND cc_campaign_phonebook.id_campaign = cc_campaign.id ';
//CAMPAIGN CLAUSE
$QUERY_COUNT_PHONENUMBERS .= 'AND cc_campaign.status = 1 AND cc_campaign.startingdate <= CURRENT_TIMESTAMP AND cc_campaign.expirationdate > CURRENT_TIMESTAMP ';
//NUMBER CLAUSE
$QUERY_COUNT_PHONENUMBERS .= 'AND cc_phonenumber.status = 1 ';

if ($verbose_level>=1) echo "SQL QUERY: $QUERY_COUNT_PHONENUMBERS  \n";

$result_count_phonenumbers = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY_COUNT_PHONENUMBERS);
print_r($result_count_phonenumbers);

if ($result_count_phonenumbers[0][0]==0){
	if ($verbose_level>=1) echo "[No phonenumbers to call now]\n";
	write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[No phonenumbers to call now]");
	exit();
}

$nb_record = $result_count_phonenumbers[0][0];
$nbpage=(ceil($nb_record/$group));






$QUERY_PHONENUMBERS = 'SELECT cc_phonenumber.id, cc_phonenumber.number, cc_campaign.id, cc_campaign.frequency , cc_campaign.forward_number  ,cc_campaign.callerid , cc_card.id , cc_card.tariff, cc_card.username FROM cc_phonenumber , cc_phonebook , cc_campaign_phonebook, cc_campaign, cc_card WHERE ';
//JOIN CLAUSE
$QUERY_PHONENUMBERS .= 'cc_phonenumber.id_phonebook = cc_phonebook.id AND cc_campaign_phonebook.id_phonebook = cc_phonebook.id AND cc_campaign_phonebook.id_campaign = cc_campaign.id AND cc_campaign.id_card = cc_card.id ';
//CAMPAIGN CLAUSE
$QUERY_PHONENUMBERS .= 'AND cc_campaign.status = 1 AND cc_campaign.startingdate <= CURRENT_TIMESTAMP AND cc_campaign.expirationdate > CURRENT_TIMESTAMP ';
//NUMBER CLAUSE
$QUERY_PHONENUMBERS .= 'AND cc_phonenumber.status = 1 ' ;


// BROWSE THROUGH THE CARD TO APPLY THE CHECK ACCOUNT SERVICE
for ($page = 0; $page < $nbpage; $page++) {
	if ($A2B->config["database"]['dbtype'] == "postgres"){
		$sql = $QUERY_PHONENUMBERS. " LIMIT $group OFFSET ".$page*$group;
	}else{
		$sql = $QUERY_PHONENUMBERS." LIMIT ".$page*$group.", $group";
	}

	if ($verbose_level>=1) echo "==> SELECT QUERY : $sql\n";
		$result_phonenumbers = $instance_table -> SQLExec ($A2B -> DBHandle, $sql);
	
		foreach ($result_phonenumbers as $phone){
			if ($verbose_level>=1) print_r ($phone);

			//check the balance
			$query_balance = "SELECT cc_card_group.flatrate , cc_card.credit FROM cc_card_group , cc_card WHERE cc_card.id = $phone[6] AND cc_card.id_group = cc_card_group.id";
			$result_balance = $instance_table -> SQLExec ($A2B -> DBHandle, $query_balance);
			
			if ($verbose_level>=1) echo "\n CHECK BALANCE :".$query_balance;
			if($result_balance){
				if($result_balance[0][1]<$result_balance[0][0]){
					write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[ user $phone[8] don't have engouh credit ]");
					if ($verbose_level>=1) echo "\n[ Error : Can't send callback -> user $phone[8] don't have enough credit ]";
					continue;
				}
				
			}else {
				write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[ user $phone[8] don't have a group correctly defined ]");
				if ($verbose_level>=1) echo "\n[ Error : Can't send callback -> user $phone[8] don't have a group correctly defined ]";
				continue;
			}
						
			//test if you have to inject it again
			
			$query_searche_phonestatus = "SELECT status FROM cc_campain_phonestatus WHERE id_campaign = ".$phone[2]." AND id_phonenumber = ".$phone[0] ; 
			$result_search_phonestatus = $instance_table -> SQLExec ($A2B -> DBHandle, $query_searche_phonestatus);
			
			if ($verbose_level>=1) echo "\nSEARCH PHONESTATUS QUERY : ".$query_searche_phonestatus;
			if ($verbose_level>=1) echo "\nSEARCH PHONESTATUS RESULT : ".print_r($result_search_phonestatus);
			//check callback spool
			$action='';
			$create_callback=true;
			if($result_search_phonestatus) {
				$action="update";
				//Filter phone number holded and stoped
				if($result_search_phonestatus[0][0]==1 || $result_search_phonestatus[0][0]==2) $create_callback = false;
			}else{ 
				$action="insert";
			}
			
		if($create_callback){	
			//// Search Road...
			
			$A2B -> set_instance_table ($instance_table);
			$A2B -> cardnumber = $phone["username"];
			$error_msg ="";	
			if ($A2B -> callingcard_ivr_authenticate_light ($error_msg)){
			
				$RateEngine = new RateEngine();
				$RateEngine -> webui = 0;
				// LOOKUP RATE : FIND A RATE FOR THIS DESTINATION
				
				$A2B ->agiconfig['accountcode']=$phone["username"];
				$A2B ->agiconfig['use_dnid']=1;
				$A2B ->agiconfig['say_timetocall']=0;	
									
				$A2B ->dnid = $A2B ->destination = $phone["number"];
				
				$resfindrate = $RateEngine->rate_engine_findrates($A2B, $phone["number"], $phone["tariff"]);
				
				// IF FIND RATE
				if ($resfindrate!=0){				
					$res_all_calcultimeout = $RateEngine->rate_engine_all_calcultimeout($A2B, $A2B->credit);
					if ($res_all_calcultimeout){							
						
						// MAKE THE CALL
						if ($RateEngine -> ratecard_obj[0][34]!='-1'){
							$usetrunk = 34; 
							$usetrunk_failover = 1;
							$RateEngine -> usedtrunk = $RateEngine -> ratecard_obj[0][34];
						} else {
							$usetrunk = 29;
							$RateEngine -> usedtrunk = $RateEngine -> ratecard_obj[0][29];
							$usetrunk_failover = 0;
						}
						
						$prefix			= $RateEngine -> ratecard_obj[0][$usetrunk+1];
						$tech 			= $RateEngine -> ratecard_obj[0][$usetrunk+2];
						$ipaddress 		= $RateEngine -> ratecard_obj[0][$usetrunk+3];
						$removeprefix 	= $RateEngine -> ratecard_obj[0][$usetrunk+4];
						$timeout		= $RateEngine -> ratecard_obj[0]['timeout'];	
						$failover_trunk	= $RateEngine -> ratecard_obj[0][40+$usetrunk_failover];
						$addparameter	= $RateEngine -> ratecard_obj[0][42+$usetrunk_failover];
						
						$destination = $phone["number"];
						if (strncmp($destination, $removeprefix, strlen($removeprefix)) == 0) $destination= substr($destination, strlen($removeprefix));
						
						$pos_dialingnumber = strpos($ipaddress, '%dialingnumber%' );
						$ipaddress = str_replace("%cardnumber%", $A2B->cardnumber, $ipaddress);
						$ipaddress = str_replace("%dialingnumber%", $prefix.$destination, $ipaddress);
						
						if ($pos_dialingnumber !== false){					   
							$dialstr = "$tech/$ipaddress".$dialparams;
						}else{
							if ($A2B->agiconfig['switchdialcommand'] == 1){
								$dialstr = "$tech/$prefix$destination@$ipaddress".$dialparams;
							}else{
								$dialstr = "$tech/$ipaddress/$prefix$destination".$dialparams;
							}
						}	
						
						//ADDITIONAL PARAMETER 			%dialingnumber%,	%cardnumber%	
						if (strlen($addparameter)>0){
							$addparameter = str_replace("%cardnumber%", $A2B->cardnumber, $addparameter);
							$addparameter = str_replace("%dialingnumber%", $prefix.$destination, $addparameter);
							$dialstr .= $addparameter;
						}
						
						$channel= $dialstr;
						$exten = 11;
						$context = $A2B -> config["callback"]['context_campaign_callback'];
						$id_server_group = $A2B -> config["callback"]['id_server_group'];
						$priority=1;
						$timeout = $A2B -> config["callback"]['timeout']*1000;
						$application='';
						$callerid = $phone["callerid"];
						$account = $_SESSION["pr_login"];
						
						$uniqueid 	=  MDP_NUMERIC(5).'-'.MDP_STRING(7);
						$status = 'PENDING';
						$server_ip = 'localhost';
						$num_attempt = 0;
						$variable = "CALLED=$destination|USERNAME=$phone[8]|USERID=$phone[6]|CBID=$uniqueid|LEG=".$A2B->cardnumber;
						
						$QUERY = " INSERT INTO cc_callback_spool (uniqueid, status, server_ip, num_attempt, channel, exten, context, priority, variable, id_server_group, callback_time, account, callerid, timeout ) VALUES ('$uniqueid', '$status', '$server_ip', '$num_attempt', '$channel', '$exten', '$context', '$priority', '$variable', '$id_server_group',  now(), '$account', '$callerid', '30000')";
						$res = $A2B -> DBHandle -> Execute($QUERY);
						
						if (!$res){
							if ($verbose_level>=1) echo "[Cannot insert the callback request in the spool!]";
						}else{
							if ($verbose_level>=1) echo "[Your callback request has been queued correctly!]";

							if($action == "update") $query = "UPDATE cc_campain_phonestatus SET id_callback = '$uniqueid', lastuse = CURRENT_TIMESTAMP WHERE id_phonenumber =$phone[0] AND id_campaign = $phone[2] "  ;
							else $query = "INSERT INTO cc_campain_phonestatus (id_phonenumber ,id_campaign ,id_callback ,status) VALUES ( $phone[0], $phone[2], '$uniqueid' , '0') ";
							
							if ($verbose_level>=1) echo "\nINSERT PHONESTATUS QUERY : $query";
							$res = $A2B -> DBHandle -> Execute($query);
						}
						
						
					}else{
						if ($verbose_level>=1) echo "Error : You don t have enough credit to call you back!";
					}
				}else{
					if ($verbose_level>=1) echo "Error : There is no route to call back your phonenumber!";
				}
				
			}else{
				if ($verbose_level>=1) echo "Error : ".$error_msg;
			}

			
		}
			/// End Search Road....
			
		}
		
	if($page != $nbpage-1)sleep($timing);
	
}
//LIMIT
exit();





$result_phonenumbers = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY_PHONENUMBERS);

if ($verbose_level>=1) echo $QUERY_PHONENUMBERS;



// CHECK AMOUNT OF CARD ON WHICH APPLY THE SERVICE
$QUERY = 'SELECT count(*) FROM cc_card WHERE  firstusedate IS NOT NULL AND firstusedate>0 AND runservice=1 AND id_group '.$groupe_clause;
if ($verbose_level>=1) echo $QUERY;
$result = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY);
$nb_card = $result[0][0];
$nbpagemax=(intval($nb_card/$groupcard));
if ($verbose_level>=1) echo "===> NB_CARD : $nb_card - NBPAGEMAX:$nbpagemax\n";

if (!($nb_card>0)){
	if ($verbose_level>=1) echo "[No card to run the Recurring service]\n";
	write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[No card to run the Recurring service]");
	exit();
}



// CHECK THE SERVICES
$QUERY = "SELECT DISTINCT id, name, amount, period, rule, daynumber, stopmode, maxnumbercycle, status, numberofrun, datecreate, $UNIX_TIMESTAMP datelastrun), emailreport, totalcredit,totalcardperform FROM cc_service , cc_cardgroup_service WHERE status=1 AND id = id_service";

$result = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY);
if ($verbose_level>=1) print_r ($result);

if( !is_array($result)) {
	echo "[No Recurring service to run]\n";
	write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[ No Recurring service to run]");
	exit();
}
// 0 id, 1 name, 2 amount, 3 period, 4 rule, 5 daynumber, 6 stopmode,  7 maxnumbercycle, 8 status, 9 numberofrun, 
// 10 datecreate, 11 datelastrun, 12 emailreport, 13 totalcredit, 14 totalcardperform 

write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[Number of card found : $nb_card]");

$oneday = 60*60*24;

// mail variable for user notification

// BROWSE THROUGH THE SERVICES 
foreach ($result as $myservice) {

	$totalcardperform = 0;
	$totalcredit = 0;
	$timestamp_lastsend = strtotime($myservice[11]);  // 4 aug 1PM
	$datewish = time()- (intval($myservice[3]) * $oneday) - 1800; //minus 30 min   4 aug 1:29PM
	
	write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[Service : ".$myservice[1]." ]");
	
	if ($verbose_level>=1) echo "------>>>   TIME STAMP $datewish < $timestamp_lastsend \n";		
	
	// 1 -> APPLY SERVICE IF NOT USED IN THE LAST X DAYS
	// 2 -> APPLY SERVICE IF CARD HAS BEEN USED IN THE LAST X DAYS
	
	// Comment if you dont wish to check time of the service running - testing
	// we will apply the service only if there is a laps of X days 
	if ($myservice[4]!=3)  if ($datewish < $timestamp_lastsend){        write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[Service in the Date range : not to run ]"); continue; }


	write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[Service analyze cards on which to apply service ]");
	// BROWSE THROUGH THE CARD TO APPLY THE SERVICE 
	for ($page = 0; $page <= $nbpagemax; $page++) {
		
		$sql = "SELECT id, credit, nbservice, $UNIX_TIMESTAMP lastuse), username, $UNIX_TIMESTAMP servicelastrun), email FROM cc_card , cc_cardgroup_service WHERE id_group = id_card_group AND id_service = $myservice[0] AND firstusedate IS NOT NULL AND firstusedate>0 AND runservice=1  ORDER BY id  ";
		if ($A2B->config["database"]['dbtype'] == "postgres"){
			$sql .= " LIMIT $groupcard OFFSET ".$page*$groupcard;
		}else{
			$sql .= " LIMIT ".$page*$groupcard.", $groupcard";
		}
		if ($verbose_level>=1) echo "==> SELECT CARD QUERY : $sql\n";
		$result_card = $instance_table -> SQLExec ($A2B -> DBHandle, $sql);
	
		foreach ($result_card as $mycard){
			if ($verbose_level>=1) print_r ($mycard);
			if ($verbose_level>=1) echo "------>>>  ID = ".$mycard[0]." - CARD =".$mycard[4]." - BALANCE =".$mycard[1]." \n";	

			// RULE 3 : Apply the period to card - card last run date >= period
			if ($myservice[4]==3){
				
				$timestamp_servicelastrun = $mycard[5];	 // 4 aug 1PM		
				
				//$datewish = time()- (intval($myservice[5]) * $oneday) - 1800; //minus 30 min   4 aug 1:29PM
				// DATEWISH - already - $datewish = time()- (intval($myservice[3]) * $oneday) - 1800;
				// echo "timestamp_servicelastrun=$timestamp_servicelastrun - mycard_5=$mycard[5] - datewish:$datewish\n";
				if ( ($datewish < $timestamp_servicelastrun) ) {
					if ($verbose_level>=1) echo "#### CARD : NOT - Apply the period to card - card last run date >= period :".$myservice[3]." day(s)\n";
					continue;
				}
				if ($verbose_level>=1) echo "#### CARD : Apply the period to card - card last run date >= period :".$myservice[3]." day(s)\n";
			}
			if ($verbose_level>=1) echo "#### CARD : Apply the period to card - card last run date >= period :".$myservice[3]." day(s)\n";
			if ( ($myservice[4]==1)  || ($myservice[4]==2) ){
				
				$timestamp_lastuse = strtotime($mycard[3]);  // 4 aug 1PM
				$datewish = time()- (intval($myservice[5]) * $oneday) - 1800; //minus 30 min   4 aug 1:29PM
				
				$temp = $datewish < $timestamp_lastuse;					
				if ($verbose_level>=1) echo "------>>>   TIME STAMP $datewish < $timestamp_lastuse = $temp \n";		
				
				// RULE 1 : "User didnt use card since %nextfield% day(s)"
				if ($verbose_level>=1) echo "RULE 1 : User didnt use card since %nextfield% day(s)\n";
				if ( ($myservice[4]==1) && ($datewish < $timestamp_lastuse) && ($myservice[5]>0) ) {
					if ($verbose_level>=1) echo "#### CARD : card used since ".$myservice[5]." day(s)\n";
					continue;
				}
				
				// RULE 2 : "User use the card in the last %nextfield% day(s)"
				if ($verbose_level>=1) echo "RULE 2 : User use the card in the last %nextfield% day(s)\n";
				if ( ($myservice[4]==2) && ($datewish > $timestamp_lastuse) && ($myservice[5]>0) ) {
					if ($verbose_level>=1) echo "#### CARD : User didnt use the card in the last ".$myservice[5]." day(s)\n";
					continue;
				}					
				
			}
			// RULE 0 : NO RULES :D
			
			// CHECK if NBSERVICE > MAXNUMBERCYCLE  && STOPMODE Max number of cycle reach
			if ($mycard[2]>$myservice[7] && $myservice[6]==2) continue;
			
			
			// CHECK if CREDIT <= 0 && STOPMODE Account balance below zero
			if ( $mycard[1]<=0 && $myservice[6]==1 ) continue;
			
			// UPDATE THE CARD CREDIT AND SERVICE LAST RUN
			$QUERY = "UPDATE cc_card SET nbservice=nbservice+1, credit=credit-'".$myservice[2]."', servicelastrun=now() WHERE id=".$mycard[0];	
			$result = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY, 0);
			if ($verbose_level>=1) echo "==> UPDATE CARD QUERY: 	$QUERY\n";
			$totalcardperform ++;
			$totalcredit += $myservice[2];
			//exit();
		}
		// Little bit of rest
		sleep(15);
	}

	write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[Service finish]");
	
	// INSERT REPORT SERVICE INTO THE DATABASE
	$QUERY = "INSERT INTO cc_service_report (cc_service_id, totalcardperform, totalcredit, daterun) ".
			 "VALUES ('".$myservice[0]."', '$totalcardperform', '$totalcredit', now())";		
	$result_insert = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY, 0);
	if ($verbose_level>=1) echo "==> INSERT SERVICE REPORT QUERY=$QUERY\n";

	write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[Service report : 'totalcardperform=$totalcardperform', 'totalcredit=$totalcredit']");
	
	// UPDATE THE SERVICE		
	$QUERY = "UPDATE cc_service SET datelastrun=now(), numberofrun=numberofrun+1, totalcardperform=totalcardperform+".$totalcardperform.
			 ", totalcredit = totalcredit + '".$totalcredit."' WHERE id=".$myservice[0];	
	$result = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY, 0);
	if ($verbose_level>=1) echo "==> SERVICE UPDATE QUERY: 	$QUERY\n";
	
	
	// SEND REPORT
	if (strlen($myservice[12])>0){
		$mail_content = "SERVICE NAME = ".$myservice[1];
		$mail_content .= "\n\nTotal card updated = ".$totalcardperform;
		$mail_content .= "\nTotal credit removed = ".$totalcredit;
		mail($myservice[12], "RECURRING SERVICES : REPORT", $mail_content);
	}

} // END FOREACH SERVICES

if ($verbose_level>=1) echo "#### END RECURRING SERVICES \n";
write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[#### BATCH PROCESS END ####]");
	
?>
