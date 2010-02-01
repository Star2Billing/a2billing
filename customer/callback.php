<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,   
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 * 
 * @copyright   Copyright (C) 2004-2009 - Star2billing S.L. 
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


include ("lib/customer.defines.php");
include ("lib/customer.module.access.php");
include ("lib/Class.RateEngine.php");	 
include ("lib/customer.smarty.php");

include (dirname(__FILE__)."/lib/phpagi/phpagi-asmanager.php");


getpost_ifset(array('callback', 'called', 'calling'));


if (! has_rights (ACX_CALL_BACK)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}

$FG_DEBUG = 0;
$color_msg = 'red';


$QUERY = "SELECT username, credit, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, lastuse, activated, status FROM cc_card WHERE username = '".$_SESSION["pr_login"]."' AND uipass = '".$_SESSION["pr_password"]."'";

$DBHandle_max = DbConnect();
$numrow = 0;
$resmax = $DBHandle_max -> Execute($QUERY);
if ($resmax)
	$numrow = $resmax -> RecordCount();

if ($numrow == 0) exit();
$customer_info =$resmax -> fetchRow();

if ($customer_info[14] != "1" && $customer_info[14] != "8") {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}


if ($callback) {
	
	if (strlen($called)>1 && strlen($calling)>1 && is_numeric($called) && is_numeric($calling)) {
		
		$A2B -> DBHandle = DbConnect();
		$instance_table = new Table();
		$A2B -> set_instance_table ($instance_table);
		$A2B -> cardnumber = $_SESSION["pr_login"];
			
		if ($A2B -> callingcard_ivr_authenticate_light ($error_msg)) {
		
			$RateEngine = new RateEngine();
			$RateEngine -> webui = 0;
			// LOOKUP RATE : FIND A RATE FOR THIS DESTINATION
			
			$A2B -> agiconfig['accountcode']=$_SESSION["pr_login"];
			$A2B -> agiconfig['use_dnid']=1;
			$A2B -> agiconfig['say_timetocall']=0;						
			$A2B -> extension = $A2B -> dnid = $A2B -> destination = $called;
			
			$resfindrate = $RateEngine->rate_engine_findrates($A2B, $called, $_SESSION["tariff"]);
			
			// IF FIND RATE
			if ($resfindrate!=0) {				
				$res_all_calcultimeout = $RateEngine->rate_engine_all_calcultimeout($A2B, $A2B->credit);
				if ($res_all_calcultimeout) {						
					
					// MAKE THE CALL
					if ($RateEngine -> ratecard_obj[0][34]!='-1') {
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
					
					$destination = $called;
					if (strncmp($destination, $removeprefix, strlen($removeprefix)) == 0) $destination= substr($destination, strlen($removeprefix));
					
					$pos_dialingnumber = strpos($ipaddress, '%dialingnumber%' );
					$ipaddress = str_replace("%cardnumber%", $A2B->cardnumber, $ipaddress);
					$ipaddress = str_replace("%dialingnumber%", $prefix.$destination, $ipaddress);
					
					$dialparams = '';
					if ($pos_dialingnumber !== false) {					   
						$dialstr = "$tech/$ipaddress".$dialparams;
					} else {
						if ($A2B->agiconfig['switchdialcommand'] == 1){
							$dialstr = "$tech/$prefix$destination@$ipaddress".$dialparams;
						} else {
							$dialstr = "$tech/$ipaddress/$prefix$destination".$dialparams;
						}
					}	
					
					//ADDITIONAL PARAMETER 			%dialingnumber%,	%cardnumber%	
					if (strlen($addparameter)>0) {
						$addparameter = str_replace("%cardnumber%", $A2B->cardnumber, $addparameter);
						$addparameter = str_replace("%dialingnumber%", $prefix.$destination, $addparameter);
						$dialstr .= $addparameter;
					}
					
					$channel= $dialstr;
					$exten = $calling;
					$context = $A2B -> config["callback"]['context_callback'];
					$id_server_group = $A2B -> config["callback"]['id_server_group'];
					$priority=1;
					$timeout = $A2B -> config["callback"]['timeout']*1000;
					$application='';
					$callerid = $A2B -> config["callback"]['callerid'];
					$account = $_SESSION["pr_login"];
					
					$uniqueid 	=  MDP_NUMERIC(5).'-'.MDP_STRING(7);
					$status = 'PENDING';
					$server_ip = 'localhost';
					$num_attempt = 0;
					
					if ($A2B->config['global']['asterisk_version'] == "1_6") {
						$variable = "CALLED=$called,CALLING=$calling,CBID=$uniqueid,LEG=".$A2B->cardnumber;
					} else {
						$variable = "CALLED=$called|CALLING=$calling|CBID=$uniqueid|LEG=".$A2B->cardnumber;
					}
					
					$QUERY = " INSERT INTO cc_callback_spool (uniqueid, status, server_ip, num_attempt, channel, exten, context, priority," .
							 " variable, id_server_group, callback_time, account, callerid, timeout ) " .
							 " VALUES ('$uniqueid', '$status', '$server_ip', '$num_attempt', '$channel', '$exten', '$context', '$priority'," .
							 " '$variable', '$id_server_group',  now(), '$account', '$callerid', '30000')";
					$res = $A2B -> DBHandle -> Execute($QUERY);
					
					if (!$res) {
						$error_msg= gettext("Cannot insert the callback request in the spool!");
					} else {
						$error_msg = gettext("Your callback request has been queued correctly!");
						$color_msg = 'green';
					}
					
				} else {
					$error_msg = gettext("Error : You don t have enough credit to call you back!");
				}
			} else {
				$error_msg = gettext("Error : There is no route to call back your phonenumber!");
			}
		} else {
			// ERROR MESSAGE IS CONFIGURE BY THE callingcard_ivr_authenticate_light
		}
	} else {
		$error_msg = gettext("Error : You have to specify your phonenumber and the number you wish to call!");
	
	}
}
$customer = $_SESSION["pr_login"];


$smarty->display( 'main.tpl');

echo $CC_help_callback;

?>
<br>
<center>
 <font class="fontstyle_007"> 
 <font face='Arial, Helvetica, sans-serif' size='2' color='<?php echo $color_msg; ?>'><b>
 	<?php echo $error_msg ?> 
 </b></font>
 <br><br>
  <?php echo gettext("You can initiate the callback by entering your phonenumber and the number you wish to call!");?>
  </font>
  </center>
   <table align="center" class="callback_maintable">
	<form name="theForm" action=<?php echo $PHP_SELF;?> method="POST" >
	<INPUT type="hidden" name="callback" value="1">
	<tr class="bgcolor_001">
	<td align="left" valign="bottom">
			<br/>
			<font class="fontstyle_007"><?php echo gettext("Your Phone Number");?> :</font>
			<input class="form_input_text" name="called" value="<?php echo $called; ?>" size="30" maxlength="40" >
			<br/><br/>
			<font class="fontstyle_007"><?php echo gettext("The number you wish to call");?> :</font>
			<input class="form_input_text" name="calling" value="<?php echo $calling; ?>" size="30" maxlength="40">
			<br/><br/>
		</td>	
		<td align="center" valign="middle"> 
		<input class="form_input_button"  value="[ <?php echo gettext("Click here to Place Call");?> ]" type="submit"> 
		</td>
    </tr>
	</form>
  </table>
  <br>
<br></br><br></br>
<?php

$smarty->display( 'footer.tpl');

