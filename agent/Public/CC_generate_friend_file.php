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


include ("../lib/agent.defines.php");
include ("../lib/agent.module.access.php");
include ("../lib/regular_express.inc");
include ("../lib/phpagi/phpagi-asmanager.php");
include ("../lib/agent.smarty.php");

$FG_DEBUG =0;


getpost_ifset(array('action', 'atmenu'));


if (! has_rights (ACX_CUSTOMER)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();
}



$DBHandle  = DbConnect();

if ( $action == "reload" ) {
	
	$as = new AGI_AsteriskManager();
	// && CONNECTING  connect($server=NULL, $username=NULL, $secret=NULL)
	$res = $as->connect(MANAGER_HOST,MANAGER_USERNAME,MANAGER_SECRET);
	
	if ($res) {
		if ( $atmenu == "sipfriend" ) {
			$res = $as->Command('sip reload');
		} else {
			$res = $as->Command('iax2 reload');
		}
		$actiondone=1;
		
		// && DISCONNECTING	
		$as->disconnect();
	} else {
		$error_msg= "</br><center><b><font color=red>".gettext("Cannot connect to the asterisk manager!<br>Please check your manager configuration.")."</font></b></center>";		
	}
} else {
	if ( $atmenu == "sipfriend" ) {
		$TABLE_BUDDY = 'cc_sip_buddies';
		$buddyfile = BUDDY_SIP_FILE;
		
		$_SESSION["is_sip_changed"]=0;
		if ($_SESSION["is_iax_changed"]==0) {
			$_SESSION["is_sip_iax_change"]=0;			
		}
	} else {
		$TABLE_BUDDY = 'cc_iax_buddies';
		$buddyfile = BUDDY_IAX_FILE;
		
		$_SESSION["is_iax_changed"]=0;
		if ($_SESSION["is_sip_changed"]==0) {
			$_SESSION["is_sip_iax_change"]=0;			
		}
	}
	
	// This Variable store the argument for the SQL query
	$FG_QUERY_EDITION='name, type, username, accountcode, regexten, callerid, amaflags, secret, md5secret, nat, dtmfmode, qualify, canreinvite, 
disallow, allow, host, callgroup, context, defaultip, fromuser, fromdomain, insecure, language, mailbox, permit, deny, mask, pickupgroup, port, 
restrictcid, rtptimeout, rtpholdtimeout, musiconhold, regseconds, ipaddr, cancallforward';

	$list_names = explode(",",$FG_QUERY_EDITION);

	$instance_table_friend = new Table($TABLE_BUDDY,'id, '.$FG_QUERY_EDITION);	
	$list_friend = $instance_table_friend -> Get_list ($DBHandle, 'id > 0', null, null, null, null);
	
	if (!is_array($list_friend) || count($list_friend)==0) { 
		$error_msg= "</br><center><b><font color=red>".gettext("There is no ").$atmenu." ! </font></b></center>";
	} else {
		
		error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
		$fd=fopen($buddyfile,"w");
		if (!$fd) {   
			$error_msg= "</br><center><b><font color=red>".gettext("Could not open the user configuration file :")." '$buddyfile'</font></b></center>";
		} else {
			foreach ($list_friend as $data){
				$line="\n\n[".$data[1]."]\n";
				if (fwrite($fd, $line) === FALSE) {  
					$error_msg = gettext("Impossible to write to the file")." : $buddyfile";
					break;
				}
				
				for ($i=1;$i<count($data)-1;$i++){
					if (strlen($data[$i+1])>0){
						if (trim($list_names[$i]) == 'allow'){
							$codecs = explode(",",$data[$i+1]);
							$line = "";
							foreach ($codecs as $value)
								$line .= trim($list_names[$i]).'='.$value."\n";
						}else    $line = (trim($list_names[$i]).'='.$data[$i+1]."\n");
						if (fwrite($fd, $line) === FALSE){
							$error_msg = gettext("Impossible to write to the file")." : $buddyfile";
							break 2;
						}
					}
				}
			}
			fclose($fd);
		}
	}
}


$smarty->display('main.tpl');


echo $CC_help_sipfriend_reload;

?>

<table width="60%" border="0" align="center" cellpadding="0" cellspacing="0" >

<TR> 
  <TD style="border-bottom: medium dotted #555555">&nbsp; </TD>
</TR>
<tr><FORM NAME="sipfriend">
	<td height="31" class="bgcolor_001" style="padding-left: 5px; padding-right: 3px;" align=center>
	<br><br>
	<b>
	<?php 	
		if (strlen($error_msg)>0) {			
			echo $error_msg;
		} elseif ( $action != "reload" ) {		
			if ( $atmenu == "sipfriend" ) {
				echo gettext("The sipfriend file has been generated : ").$buddyfile;
			} else {
				echo gettext("The iaxfriend file has been generated : ").$buddyfile;
			}
		
	?>
	
	
	<br><br><br>
	<a href="<?php  echo $PHP_SELF."?atmenu=$atmenu&action=reload";?>"><img src="<?php echo Images_Path;?>/icon_refresh.gif" /> 
		<?php echo gettext("Click here to reload your asterisk server"); ?>
	</a>
	
	<?php 
		} else {
			
			echo gettext("Asterisk has been reloaded.");
		
		}
	?>
	<br><br><br>
	
	</b>
	  </td></FORM>
  </tr>
</table>

<br><br><br>


<?php

$smarty->display('footer.tpl');

?>