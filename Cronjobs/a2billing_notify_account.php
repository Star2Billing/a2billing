#!/usr/bin/php -q
<?php
/***************************************************************************
 *            a2billing_notify_account.php
 *
 *  20 May 2008
 *  Purpose: To check account of each Users and send an email if the balance is less than the user have choice.
 *  ADD THIS SCRIPT IN A CRONTAB JOB
 *
 *  The sample above will run the script once every hour
	crontab -e
	1 * * * * php /usr/local/a2billing/Cronjobs/a2billing_notify_account.php

	field	 allowed values
	-----	 --------------
	minute	 0-59
	hour		 0-23
	day of month	 1-31
	month	 1-12 (or names, see below)
	day of week	 0-7 (0 or 7 is Sun, or use names)

****************************************************************************/

// CHECK ALL AND ENSURE IT WORKS / NOT URGENT

set_time_limit(0);
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

include_once (dirname(__FILE__)."/lib/admin.defines.php");


$verbose_level=0;
$groupcard=5000;


$A2B = new A2Billing();
$A2B -> load_conf($agi, NULL, 0, $idconfig);


write_log(LOGFILE_CRONT_CHECKACCOUNT, basename(__FILE__).' line:'.__LINE__."[#### BATCH BEGIN ####]");

if (!$A2B -> DbConnect()){
	echo "[Cannot connect to the database]\n";
	write_log(LOGFILE_CRONT_CHECKACCOUNT, basename(__FILE__).' line:'.__LINE__."[Cannot connect to the database]");
	exit;
}


//Check if the notifications is Enable or Disable
if(empty($A2B->config['notifications']['cron_notifications'])) {
	echo "[The cron of notification is disactived]\n";
	exit;
}


//$A2B -> DBHandle
$instance_table = new Table();


$QUERY = "SELECT mailtype, fromemail, fromname, subject, messagetext, messagehtml FROM cc_templatemail WHERE mailtype='reminder' ";
$listtemplate = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY);
if (!is_array($listtemplate)){
	echo "[Cannot find a template mail for reminder]\n";
	write_log(LOGFILE_CRONT_CHECKACCOUNT, basename(__FILE__).' line:'.__LINE__."[Cannot find a template mail for reminder]");
	exit;
}


list($mailtype, $from, $fromname, $subject, $messagetext, $messagehtml) = $listtemplate [0];
if ($FG_DEBUG == 1) echo "<br><b>mailtype : </b>$mailtype</br><b>from:</b> $from</br><b>fromname :</b> $fromname</br><b>subject</b> : $subject</br><b>ContentTemplate:</b></br><pre>$messagetext</pre></br><hr>";


// Prepare the date interval to filter the card that don't have to receive a notification;

$Delay_Clause = "( " ;
if ($A2B->config["database"]['dbtype'] ==  "postgres") {
	$Delay_Clause .= "last_notification < CURRENT_DATE - ".$A2B->config['notifications']['delay_notifications']." OR ";
}else{
	$Delay_Clause .= "last_notification < CURDATE() - ". $A2B->config['notifications']['delay_notifications']." OR ";
}
$Delay_Clause.= "last_notification IS NULL )";
// CHECK AMOUNT OF CARD ON WHICH APPLY THE CHECK ACCOUNT SERVICE
$QUERY = "SELECT count(*) FROM cc_card WHERE notify_email ='1' AND credit < credit_notification AND ".$Delay_Clause;

if ($verbose_level>=1) {
	echo "[QUERY COUNT]\n";
	echo "$QUERY\n";
}

$result = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY);
$nb_card = $result[0][0];
$nbpagemax=(ceil($nb_card/$groupcard));
if ($verbose_level>=1) echo "===> NB_CARD : $nb_card - NBPAGEMAX:$nbpagemax\n";

if (!($nb_card>0)){
	if ($verbose_level>=1) echo "[No card to run the Recurring service]\n";
	write_log(LOGFILE_CRONT_CHECKACCOUNT, basename(__FILE__).' line:'.__LINE__."[No card to run the check account service]");
	exit();
}


write_log(LOGFILE_CRONT_CHECKACCOUNT, basename(__FILE__).' line:'.__LINE__."[Number of card found : $nb_card]");

// GET the currencies to define the email
$currencies_list = get_currencies($A2B -> DBHandle);



// BROWSE THROUGH THE CARD TO APPLY THE CHECK ACCOUNT SERVICE
for ($page = 0; $page < $nbpagemax; $page++) {
	$sql = "SELECT id, credit, username,useralias,uipass,lastname,firstname,loginkey,credit,currency , email_notification,credit_notification FROM cc_card WHERE notify_email='1' AND credit < credit_notification AND ".$Delay_Clause." ORDER BY id  ";
	if ($A2B->config["database"]['dbtype'] == "postgres"){
		$sql .= " LIMIT $groupcard OFFSET ".$page*$groupcard;
	}else{
		$sql .= " LIMIT ".$page*$groupcard.", $groupcard";
	}

	if ($verbose_level>=1) echo "==> SELECT CARD QUERY : $sql\n";

	$result_card = $instance_table -> SQLExec ($A2B -> DBHandle, $sql);
	
	foreach ($result_card as $mycard){
		
		$messagetextuser = $messagetext;
		if ($verbose_level>=1) print_r ($mycard);
		if ($verbose_level>=1) echo "------>>>  ID = ".$mycard['id']." - CARD =".$mycard['username']." - BALANCE =".$mycard['credit']." \n";

		// SEND NOTIFICATION
		if (strlen($mycard['email_notification'])>0){ // ADD CHECK EMAIL
		
		$email = $mycard['email_notification'];
		$credit = $mycard['credit'];
		$currency = $mycard['currency'];
		$lastname = $mycard['lastname'];
		$firstname = $mycard['firstname'];
		$loginkey = $mycard['loginkey'];
		$username = $mycard['username'];
		$useralias = $mycard['useralias'];
		$uipass = $mycard['uipass'];
		$credit_notification = $mycard['credit_notification'];
		// convert credit to currency
		if (!isset($currencies_list[strtoupper($currency)][2]) || !is_numeric($currencies_list[strtoupper($currency)][2])) $mycur = 1;
		else $mycur = $currencies_list[strtoupper($currency)][2];
		$credit_currency = $credit / $mycur;
		$credit_currency = round($credit_currency,3);

		// replace tags in message
		$messagetextuser = str_replace('$credit_notification', $credit_notification, $messagetextuser);
		$messagetextuser = str_replace('$email', $email, $messagetextuser);
		$messagetextuser = str_replace('$lastname', $lastname, $messagetextuser);
		$messagetextuser = str_replace('$firstname', $firstname, $messagetextuser);
		$messagetextuser = str_replace('$credit_currency', "$credit_currency", $messagetextuser);
		$messagetextuser = str_replace('$credit', $credit, $messagetextuser);
		$messagetextuser = str_replace('$currency', $currency, $messagetextuser);
		$messagetextuser = str_replace('$cardnumber', $username, $messagetextuser);
		$messagetextuser = str_replace('$cardalias', $useralias, $messagetextuser);
		$messagetextuser = str_replace('$password', $uipass, $messagetextuser);
		$messagetextuser = str_replace('$loginkey', "$loginkey", $messagetextuser);
		$messagetextuser = str_replace('$base_currency', BASE_CURRENCY, $messagetextuser);
		$mail_tile =  "CREDIT LOW : You have less than  ".$mycard['credit_notification'];
		
		// Sent Mail
		a2b_mail($email, $mail_tile, $messagetextuser, $from, $fromname);

		//update the card with the date of last notification
		if ($A2B->config["database"]['dbtype'] == "postgres") {
			$now= "CURRENT_TIMESTAMP";
		} else {
			$now= "now()";
		}
		$sql_update_card = "UPDATE cc_card SET last_notification = ".$now." WHERE id = ".$mycard['id'];
		$instance_table -> SQLExec ($A2B -> DBHandle, $sql_update_card);

		if ($verbose_level>=1) {
			echo "[UPDATE CARD ID < ".$mycard['id']." > : last_notification]\n";
			echo "$sql_update_card\n";
			}

		}//endif check the email not null
	}
	// Little bit of rest
	sleep(10);
}



if ($verbose_level>=1) echo "#### END RECURRING CHECK ACCOUNT \n";
write_log(LOGFILE_CRONT_CHECKACCOUNT, basename(__FILE__).' line:'.__LINE__."[#### BATCH PROCESS END ####]");


