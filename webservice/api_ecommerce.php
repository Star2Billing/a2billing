<?php
/***************************************************************************
 *
 * Written for PHP 4.x & PHP 5.X versions.
 *
 * A2Billing -- Asterisk billing solution.
 * Copyright (C) 2004, 2009 Belaid Arezqui <areski _atl_ gmail com>
 *
 * See http://www.asterisk2billing.org for more information about
 * the A2Billing project. 
 * Please submit bug reports, patches, etc to <areski _atl_ gmail com>
 *
 ****************************************************************************/


include ("lib/admin.defines.php");
include ("lib/regular_express.inc");

/*
OUTPUT
	200 Successful
	400 Bad Request
	500 Internal server error

FOR TEST
http://localhost/all/svn/a2billing/trunk/A2Billing_UI/api/api_ecommerce.php?key=123123&productid=1&forceid=&lastname=Callabress&firstname=Jordia
http://localhost/all/svn/a2billing/trunk/A2Billing_UI/api/api_ecommerce.php?key=0951aa29a67836b860b0865bc495225c&productid=1&forceid=&lastname=Callabress&firstname=Jordia&email=info@koko.net


INPUT PARAMETER 
	
	@key                            text 40 long
	@productid                      int
	@forceid				   		   bigint  ()
	
	// below information found from TABLE_CUSTOMERS_INFO, it will be used to populate the new user account
	@lastname                       text 40 long
	@firstname                 	   text 40 long
	@address                    	   text 100 long
	@city                           text 40 long
	@state                          text 40 long
	@country                    	   text 40 long
	@zipcode                        text 40 long
	@phone                          text 40 long
	@email                          text 60 long
	@fax                            text 40 long
*/

// The wrapper variables for security
$security_key = API_SECURITY_KEY;
// Authorized ips to access to api
$IP_AUTH = $A2B->config["webui"]['api_ip_auth'];
// recipient email to send the alarm
$email_alarm = EMAIL_ADMIN;
// display debug
$FG_DEBUG = 0;

getpost_ifset(array('key', 'productid', 'createfriend', 'forceid', 'lastname', 'firstname', 'address', 'city', 'state', 'country', 'zipcode', 'phone', 'email', 'fax'));


$list_params = "productid=$productid [createfriend=$createfriend;forceid=$forceid;lastname=$lastname;firstname=$firstname;address=$address;city=$city;state=$state;country=$country;country=$country;zipcode=$zipcode;phone=$phone;email=$email;fax=$fax";

if ($FG_DEBUG > 0)
	echo ("Request asked: $list_params]");
write_log(LOGFILE_API_ECOMMERCE, "Request asked: $list_params]");
$mail_content = "Request asked: $list_params]";

// Wrapper IP 
$ip_remote = getenv('REMOTE_ADDR');
if (!in_array($ip_remote, $IP_AUTH)) {
	a2b_mail($email_alarm, "ALARM : API (IP_AUTH:$ip_remote) . CODE_ERROR 1", $mail_content);
	if ($FG_DEBUG > 0)
		echo ("[productid=$productid - ip_remote=$ip_remote] CODE_ERROR 1");
	write_log(LOGFILE_API_ECOMMERCE, "[productid=$productid] CODE_ERROR 1");
	echo ("400 Bad Request");
	exit ();
}

// CHECK KEY
if ($FG_DEBUG > 0)
	echo "<br> md5(" . md5($security_key) . ") !== $key";
if (md5($security_key) !== $key || strlen($security_key) == 0) {
	a2b_mail($email_alarm, "ALARM : API - CODE_ERROR 2", $mail_content);
	if ($FG_DEBUG > 0)
		echo ("[productid=$productid] - CODE_ERROR 2");
	write_log(LOGFILE_API_ECOMMERCE, "[productid=$productid] - CODE_ERROR 2");
	echo ("400 Bad Request");
	exit ();
}
// CHECK PRODUCTID
if (!is_numeric($productid) || $productid < 0) {
	a2b_mail($email_alarm, "ALARM : API  - CODE_ERROR 3", $mail_content);
	if ($FG_DEBUG > 0)
		echo ("[productid=$productid] - CODE_ERROR 3");
	write_log(LOGFILE_API_ECOMMERCE, "[productid=$productid] - CODE_ERROR 3");
	echo ("400 Bad Request");
	exit ();
}

// CHECK FORCEID
if (strlen($forceid) > 0 && !is_numeric($forceid)) {
	a2b_mail($email_alarm, "ALARM : API  - CODE_ERROR 5 - forceid=[$forceid]", $mail_content);
	if ($FG_DEBUG > 0)
		echo ("[$forceid] - CODE_ERROR 5");
	write_log(LOGFILE_API_ECOMMERCE, "[$forceid] - CODE_ERROR 5");
	echo ("400 Bad Request");
	exit ();
}

// CHECK LASTNAME ; FIRSTNAME ; ADDRESS ; ....
if (strlen($lastname) > 40 || strlen($firstname) > 40 || strlen($address) > 100 || strlen($city) > 40 || strlen($state) > 40 || strlen($country) > 40 || strlen($zipcode) > 40 || strlen($phone) > 40 || strlen($email) > 60 || strlen($fax) > 40) {
	a2b_mail($email_alarm, "ALARM : API  - CODE_ERROR 6 - [$lastname;$firstname;$address;$city;$state;$country;$zipcode;$phone;$email;$fax]", $mail_content);
	if ($FG_DEBUG > 0)
		echo ("[$lastname;$firstname;$address;$city;$state;$country;$zipcode;$phone;$email;$fax] - CODE_ERROR 6");
	write_log(LOGFILE_API_ECOMMERCE, "[$lastname;$firstname;$address;$city;$state;$country;$zipcode;$phone;$email;$fax] - CODE_ERROR 6");
	echo ("400 Bad Request");
	exit ();
}

// CHECK EMAIL FORMAT
if (!ereg($regular[1][0], $email)) {
	a2b_mail($email_alarm, "ALARM : API  - CODE_ERROR 7 - email=[$email]", $mail_content);
	if ($FG_DEBUG > 0)
		echo ("[$email] - CODE_ERROR 7");
	write_log(LOGFILE_API_ECOMMERCE, "[$email] - CODE_ERROR 7");
	echo ("400 Bad Request");
	exit ();
}

if ($FG_DEBUG > 0)
	echo "<br>INPUT CHECK CORRECT<br>";

$DBHandle = DbConnect();
$FG_TABLE_NAME = 'cc_ecommerce_product, cc_templatemail';
$instance_table = new Table($FG_TABLE_NAME, $FG_QUERY_EDITION);

$ec_prod = get_productinfo($DBHandle, $instance_table, $productid, $email_alarm, $mail_content, $logfile);
if ($FG_DEBUG > 0)
	echo "GET_PRODUCTINFO<br>";
if ($FG_DEBUG > 0)
	print_r($ec_prod);

// Create new account
$FG_ADITION_SECOND_ADD_TABLE = "cc_card";
$FG_ADITION_SECOND_ADD_FIELDS = "username, useralias, credit, tariff, id_didgroup, activated, lastname, firstname, email, address, city, state, country, zipcode, phone, userpass, simultaccess, currency, typepaid, creditlimit, language, runservice, enableexpire, uipass, sip_buddy, iax_buddy";

$gen_id = time();

$arr_card_alias = gen_card_with_alias('cc_card', 1);
$cardnum = $arr_card_alias[0];
$useralias = $arr_card_alias[1];
$uipass = MDP_STRING();

// 0 product_name, creationdate, description, expirationdate, enableexpire, expiredays, credit, tariff, id_didgroup, activated, simultaccess, 
// 11 currency, typepaid, creditlimit, language, runservice, sip_friend, iax_friend, cc_ecommerce_product.mailtype, fromemail, fromname, 
// 21 subject, messagetext, messagehtml

if ($forceid > 0) {
	// FORCE THE INSERT WITH A DEFINED ID
	$instance_sub_table = new Table($FG_ADITION_SECOND_ADD_TABLE, 'id, ' . $FG_ADITION_SECOND_ADD_FIELDS);
	$FG_ADITION_SECOND_ADD_VALUE = "'$forceid', '$cardnum', '$useralias', '" . $ec_prod[6] . "', '" . $ec_prod[7] . "', '" . $ec_prod[8] . "', 't', '$lastname', '$firstname', '$email', '$address', '$city', '$state', '$country', '$zipcode', '$phone', '$cardnum', " . $ec_prod[10] . ", '" . $ec_prod[11] . "', '" . $ec_prod[12] . "', '" . $ec_prod[13] . "', '" . $ec_prod[14] . "', " . $ec_prod[15] . ", 0, '$uipass', " . $ec_prod[16] . ", " . $ec_prod[17] . "";
} else {
	// LEAVE THE AUTO INCREMENT FOR THE ID
	$instance_sub_table = new Table($FG_ADITION_SECOND_ADD_TABLE, $FG_ADITION_SECOND_ADD_FIELDS);
	$FG_ADITION_SECOND_ADD_VALUE = "'$cardnum', '$useralias', '" . $ec_prod[6] . "', '" . $ec_prod[7] . "', '" . $ec_prod[8] . "', 't', '$lastname', '$firstname', '$email', '$address', '$city', '$state', '$country', '$zipcode', '$phone', '$cardnum', " . $ec_prod[10] . ", '" . $ec_prod[11] . "', '" . $ec_prod[12] . "', '" . $ec_prod[13] . "', '" . $ec_prod[14] . "', " . $ec_prod[15] . ", 0, '$uipass', " . $ec_prod[16] . ", " . $ec_prod[17] . "";
}
$result_query = $instance_sub_table->Add_table($DBHandle, $FG_ADITION_SECOND_ADD_VALUE, null, null, 'id');

if (!$result_query) {
	if ($FG_DEBUG > 0)
		echo "<br>ALARM : API (Add_table)", "$FG_ADITION_SECOND_ADD_VALUE<hr><br>";
	a2b_mail($email_alarm, "ALARM : API (Add_table)", "$FG_ADITION_SECOND_ADD_VALUE\n\n" . $mail_content);
	write_log(LOGFILE_API_ECOMMERCE, "[productid=$productid] CODE_ERROR Add_table0");
	echo ("500 Internal server error");
	exit ();
}

if ($FG_DEBUG > 0)
	echo "NEW ACCOUNT CREATED - <b>result_query=$result_query</b> <br> $FG_ADITION_SECOND_ADD_VALUE";

$id_cc_card = $result_query;
$type = FRIEND_TYPE;
$allow = FRIEND_ALLOW;
$context = FRIEND_CONTEXT;
$nat = FRIEND_NAT;
$amaflags = FRIEND_AMAFLAGS;
$qualify = FRIEND_QUALIFY;
$host = FRIEND_HOST;
$dtmfmode = FRIEND_DTMFMODE;

$FG_QUERY_ADITION_SIP_IAX = 'name, type, username, accountcode, regexten, callerid, amaflags, secret, md5secret, nat, dtmfmode, qualify, canreinvite,disallow, allow, host, callgroup, context, defaultip, fromuser, fromdomain, insecure, language, mailbox, permit, deny, mask, pickupgroup, port,restrictcid, rtptimeout, rtpholdtimeout, musiconhold, regseconds, ipaddr, cancallforward';

$uipass = MDP_STRING();
// For IAX and SIP
$param_add_fields = "name, accountcode, regexten, amaflags, callerid, context, dtmfmode, host,  type, username, allow, secret, id_cc_card, nat,  qualify";
$param_add_value = "'$cardnum', '$cardnum', '$cardnum', '$amaflags', '$cardnum', '$context', '$dtmfmode','$host', '$type', '$cardnum', '$allow', '" . $uipass . "', '$id_cc_card', '$nat', '$qualify'";

$list_names = explode(",", $FG_QUERY_ADITION_SIP_IAX);
$FG_TABLE_SIP_NAME = "cc_sip_buddies";
$FG_TABLE_IAX_NAME = "cc_iax_buddies";

for ($ki = 0; $ki < 2; $ki++) {
	if ($ki == 0) {
		$cfriend = 'sip';
		$FG_TABLE_NAME = "cc_sip_buddies";
		$buddyfile = BUDDY_SIP_FILE;
	} else {
		$cfriend = 'iax';
		$FG_TABLE_NAME = "cc_iax_buddies";
		$buddyfile = BUDDY_IAX_FILE;
	}

	if ($FG_DEBUG > 0)
		echo "CREATION $cfriend FRIEND<br>";

	// Insert Sip/Iax account info
	if ($ec_prod[17]) {
		$instance_table1 = new Table($FG_TABLE_NAME, $FG_QUERY_ADITION_SIP_IAX);
		$result_query1 = $instance_table1->Add_table($DBHandle, $param_add_value, $param_add_fields, null, null);

		$instance_table_friend = new Table($FG_TABLE_NAME, 'id, ' . $FG_QUERY_ADITION_SIP_IAX);
		$list_friend = $instance_table_friend->Get_list($DBHandle, '', null, null, null, null);

		$fd = fopen($buddyfile, "w");
		if (!$fd) {
			a2b_mail($email_alarm, "ALARM : API  - CODE_ERROR 8 - Could not open buddy file '$buddyfile'", $mail_content);
			write_log(LOGFILE_API_ECOMMERCE, "[Could not open buddy file '$buddyfile'] - CODE_ERROR 8");
		} else {
			foreach ($list_friend as $data) {
				$line = "\n\n[" . $data[1] . "]\n";
				if (fwrite($fd, $line) === FALSE) {
					write_log(LOGFILE_API_ECOMMERCE, "[cannot write to the file ($buddyfile)] - CODE_ERROR 8");
					break;
				} else {
					for ($i = 1; $i < count($data) - 1; $i++) {
						if (strlen($data[$i +1]) > 0) {
							if (trim($list_names[$i]) == 'allow') {
								$codecs = explode(",", $data[$i +1]);
								$line = "";
								foreach ($codecs as $value)
									$line .= trim($list_names[$i]) . '=' . $value;
							} else
								$line = (trim($list_names[$i]) . '=' . $data[$i +1]);

							if (fwrite($fd, $line) === FALSE) {
								write_log(LOGFILE_API_ECOMMERCE, "[cannot write to the file ($buddyfile)] - CODE_ERROR 8");
								break;
							}
						}
					}
				}
			}
			fclose($fd);
		}
	}
} // END OF FOR

// SEND AN EMAIL CUSTOMER - (TEMPLATE FOR MAILING WILL CUSTOMIZABLE) 	WITH INFO DETAILS 
// -------------------------------------------------------------------------------------
$from = $ec_prod[19];
$fromname = $ec_prod[20];
$subject = $ec_prod[21];
$messagetext = $ec_prod[22];
$messagehtml = $ec_prod[23];

$cardnum = $arr_card_alias[0];
$useralias = $arr_card_alias[1];

$messagetext = str_replace('$name', $lastname, $messagetext);
$messagetext = str_replace('$card_gen', $cardnum, $messagetext);
$messagetext = str_replace('$password', $uipass, $messagetext);
$messagetext = str_replace('$cardalias', $cardalias, $messagetext);

if ($FG_DEBUG > 0)
	echo "SEND MAIL TO THE CUSTOMER<br>$messagetext<hr></hr><br>";

a2b_mail($email, $subject, $messagetext, $from, $fromname);

// WARN THE ADMIN ABOUT THE NEW CUSTOMER
$messagetext = "Notification that a new card has been created through the E-Commerce API\n\n  productid=$productid\n  name=$lastname $firstname\n cardnum:$cardnum\n";
a2b_mail(EMAIL_ADMIN, "[A2Billing : NEW CUSTOMER THROUGH THE E-COMMERCE API - cardnum:$cardnum]", $messagetext, $from, $fromname);

if ($FG_DEBUG > 0)
	echo "WARN THE ADMIN ABOUT THE NEW CUSTOMER<br>$messagetext<hr></hr><br>";

if ($FG_DEBUG > 0)
	echo "SUCCESS : ACCOUNT CREATED CORRECTLY";

// SUCCESS : ACCOUNT CREATED CORRECTLY	
write_log(LOGFILE_API_ECOMMERCE, "[$event_id] OK 7");
echo ("200 Successful");




