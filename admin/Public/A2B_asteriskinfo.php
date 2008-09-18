<?php 
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/regular_express.inc");
include ("../lib/phpagi/phpagi-asmanager.php");
include ("../lib/admin.smarty.php");


if (! has_rights (ACX_ADMINISTRATOR)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

/***********************************************************************************/


// #### HEADER SECTION
$smarty->display('main.tpl');

?>
<br>

<center>





<?php

$astman = new AGI_AsteriskManager();
$res = $astman->connect(MANAGER_HOST,MANAGER_USERNAME,MANAGER_SECRET);

/* $Id: page.parking.php 2243 2006-08-12 17:13:17Z p_lindheimer $ */
//Copyright (C) 2006 Astrogen LLC 
//
//This program is free software; you can redistribute it and/or
//modify it under the terms of the GNU General Public License
//as published by the Free Software Foundation; either version 2
//of the License, or (at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.

$dispnum = 'asteriskinfo'; //used for switch on config.php

$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
$extdisplay = isset($_REQUEST['extdisplay'])?$_REQUEST['extdisplay']:'summary';

$modes = array(
	"summary" => "Summary",
	"registries" => "Registries",
	"channels" => "Channels",
	"peers" => "Peers",
	"sip" => "Sip Info",
	"iax" => "IAX Info",
	"conferences" => "Conferences",
	"subscriptions" => "Subscriptions",
	"voicemail" => "Voicemail Users",
	"all" => "Full Report"
);
$arr_all = array(
	"Uptime" => "show uptime",
	"Active Channel(s)" => "show channels",
	"Sip Channel(s)" => "sip show channels",
	"IAX2 Channel(s)" => "iax2 show channels",
	"Sip Registry" => "sip show registry",
	"Sip Peers" => "sip show peers",
	"IAX2 Registry" => "iax2 show registry",
	"IAX2 Peers" => "iax2 show peers",
	"Subscribe/Notify" => "show hints",
	"Zaptel driver info" => "zap show channels",
	"Conference Info" => "meetme",
	"Voicemail users" => "show voicemail users",
);
$arr_registries = array(
	"Sip Registry" => "sip show registry",
	"IAX2 Registry" => "iax2 show registry",
);
$arr_channels = array(
	"Active Channel(s)" => "show channels",
	"Sip Channel(s)" => "sip show channels",
	"IAX2 Channel(s)" => "iax2 show channels",
);
$arr_peers = array(
	"Sip Peers" => "sip show peers",
	"IAX2 Peers" => "iax2 show peers",
);
$arr_sip = array(
	"Sip Registry" => "sip show registry",
	"Sip Peers" => "sip show peers",
);
$arr_iax = array(
	"IAX2 Registry" => "iax2 show registry",
	"IAX2 Peers" => "iax2 show peers",
);
$arr_conferences = array(
	"Conference Info" => "meetme",
);
$arr_subscriptions = array(
	"Subscribe/Notify" => "show hints"
);
$arr_voicemail = array(
	"Voicemail users" => "show voicemail users",
);

//$engineinfo = engine_getinfo();
//$astver =  $engineinfo['version'];

//if (version_compare($astver, '1.4', 'ge')) {
	$arr_all["Uptime"]="core show uptime";
	$arr_all["Active Channel(s)"]="core show channels";
	$arr_all["Subscribe/Notify"]="core show hints";
	$arr_all["Voicemail users"]="voicemail show users";
	$arr_channels["Active Channel(s)"]="core show channels";
	$arr_subscriptions["Subscribe/Notify"]="core show hints";
	$arr_voicemail["Voicemail users"]="voicemail show users";
//}

?>

<div class="rnav"><ul>
<?php 
$i=0;
foreach ($modes as $mode => $value) {
	$i++;
	if ($i > 1) echo " | ";
	//echo "<li><a id=\"".($extdisplay==$mode)."\" href=\"".$_SERVER['PHP_SELF']."?&section=".$section."type=".urlencode("tool")."&display=".urlencode($dispnum)."&extdisplay=".urlencode($mode)."\">"._($value)."</a></li>";
	echo "<a id=\"".($extdisplay==$mode)."\" href=\"".$_SERVER['PHP_SELF']."?section=".$section."&type=".urlencode("tool")."&display=".urlencode($dispnum)."&extdisplay=".urlencode($mode)."\">"._($value)."</a>";
}
?>
</ul></div>

<div class="content">
<h2><span class="headerHostInfo"><?php echo _("Asterisk (Ver. ").$astver."): "._($modes[$extdisplay])?></span></h2>

<form name="asteriskinfo" action="<?php  $_SERVER['PHP_SELF'] ?>" method="post">
<input type="hidden" name="display" value="asteriskinfo"/>
<input type="hidden" name="action" value="asteriskinfo"/>
<table>

<table class="box">
<?php
if (!$astman) {
?>
	<tr class="boxheader">
		<td colspan="2" align="center"><h5><?php echo _("ASTERISK MANAGER ERROR")?><hr></h5></td>
	</tr>
		<tr class="boxbody">
			<td>
			<table border="0" >
				<tr>
					<td align="left">
							<?php 
							echo "<br>The module was unable to connect to the asterisk manager.<br>Make sure Asterisk is running and your manager.conf settings are proper.<br><br>";
							?>
					</td>
				</tr>
			</table>
			</td>
		</tr>
<?php
} else {
	if ($extdisplay != "summary") {
		$arr="arr_".$extdisplay;
		foreach ($$arr as $key => $value) {
?>
			<tr class="boxheader">
				<td colspan="2" align="center"><h5><?php echo _("$key")?><hr></h5></td>
			</tr>
			<tr class="boxbody">
				<td>
				<table border="0" >
					<tr>
						<td>
							<pre>
								<?php 
								$response = $astman->send_request('Command',array('Command'=>$value));
								$new_value = $response['data'];
								echo ltrim($new_value,'Privilege: Command');
								?>
							</pre>
						</td>
					</tr>
				</table>
				</td>
			</tr>
		<?php
			}
		} else {
	?>
			<tr class="boxheader">
				<td colspan="2" align="center"><h5><?php echo _("Summary")?><hr></h5></td>
			</tr>
			<tr class="boxbody">
				<td>
				<table border="0">
					<tr>
						<td>
							<?php echo buildAsteriskInfo(); ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
<?php
	}
}
?>
	</table>
<tr>
	<td colspan="2"><h6><input name="Submit" class="form_input_button" type="submit" value="<?php echo _("Refresh")?>"></h6></td>
</tr>
</table>

<script language="javascript">
<!--
var theForm = document.asteriskinfo;
//-->
</script>
</form>

<?php

function convertActiveChannel($sipChannel, $channel = NULL){
	if($channel == NULL){
		print_r($sipChannel);
		exit();
		$sipChannel_arr = explode(' ', $sipChannel[1]);
		if($sipChannel_arr[0] == 0){
			return 0;
		}else{
			return count($sipChannel_arr[0]);
		}
	}elseif($channel == 'IAX2'){
		$iaxChannel = $sipChannel;
	}
}

function getActiveChannel($channel_arr, $channelType = NULL){
	if(count($channel_arr) > 1){
		if($channelType == NULL || $channelType == 'SIP'){
			$sipChannel_arr = $channel_arr;
			$sipChannel_arrCount = count($sipChannel_arr);
			$sipChannel_string = $sipChannel_arr[$sipChannel_arrCount - 2];
			$sipChannel = explode(' ', $sipChannel_string);
			return $sipChannel[0];
		}elseif($channelType == 'IAX2'){
			$iax2Channel_arr = $channel_arr;
			$iax2Channel_arrCount = count($iax2Channel_arr);
			$iax2Channel_string = $iax2Channel_arr[$iax2Channel_arrCount - 2];
			$iax2Channel = explode(' ', $iax2Channel_string);
			return $iax2Channel[0];
		}
	}
}

function getRegistration($registration, $channelType = 'SIP'){
	if($channelType == NULL || $channelType == 'SIP'){
		$sipRegistration_arr = $registration;
		$sipRegistration_count = count($sipRegistration_arr);
		return $sipRegistration_count-3;
		
	}elseif($channelType == 'IAX2'){
		$iax2Registration_arr = $registration;
		$iax2Registration_count = count($iax2Registration_arr);
		return $iax2Registration_count-3;
	}
}

function getPeer($peer, $channelType = NULL){
	global $astver_major, $astver_minor;
	global $astver;
	if(count($peer) > 1){	
		if($channelType == NULL || $channelType == 'SIP'){
			$sipPeer = $peer;
			$sipPeer_count = count($sipPeer);
			$sipPeerInfo_arr['sipPeer_count'] = $sipPeer_count -3;
			$sipPeerInfo_string = $sipPeer[$sipPeer_count -2];
			$sipPeerInfo_arr2 = explode('[',$sipPeerInfo_string);
			$sipPeerInfo_arr3 = explode(' ',$sipPeerInfo_arr2[1]);
			if (version_compare($astver, '1.4', 'ge')) { 
				$sipPeerInfo_arr['online'] = $sipPeerInfo_arr3[1] + $sipPeerInfo_arr3[6];
				$sipPeerInfo_arr['offline'] = $sipPeerInfo_arr3[3] + $sipPeerInfo_arr3[8];
			}else{
				$sipPeerInfo_arr['online'] = $sipPeerInfo_arr3[0];
				$sipPeerInfo_arr['offline'] = $sipPeerInfo_arr3[3];
			}
			return $sipPeerInfo_arr;
			
		}elseif($channelType == 'IAX2'){
			$iax2Peer = $peer;
			$iax2Peer_count = count($iax2Peer);
			$iax2PeerInfo_arr['iax2Peer_count'] = $iax2Peer_count -3;
			$iax2PeerInfo_string = $iax2Peer[$iax2Peer_count -2];
			$iax2PeerInfo_arr2 = explode('[',$iax2PeerInfo_string);
			$iax2PeerInfo_arr3 = explode(' ',$iax2PeerInfo_arr2[1]);
			$iax2PeerInfo_arr['online'] = $iax2PeerInfo_arr3[0];
			$iax2PeerInfo_arr['offline'] = $iax2PeerInfo_arr3[2];
			$iax2PeerInfo_arr['unmonitored'] = $iax2PeerInfo_arr3[4];
			return $iax2PeerInfo_arr;
		}
	}
}

function buildAsteriskInfo(){
	global $astman;
	global $astver;
	
	$arr = array(
		"Uptime" => "show uptime",
		"Active SIP Channel(s)" => "sip show channels",
		"Active IAX2 Channel(s)" => "iax2 show channels",
		"Sip Registry" => "sip show registry",
		"IAX2 Registry" => "iax2 show registry",
		"Sip Peers" => "sip show peers",	
		"IAX2 Peers" => "iax2 show peers",
	);
	
	//if (version_compare($astver, '1.4', 'ge')) {
		$arr['Uptime'] = 'core show uptime';
	//}
	
	$htmlOutput = '<div style="color:#000000;font-size:12px;margin:10px;">';
	$htmlOutput .= '<table border="1" cellpadding="10">';

	foreach ($arr as $key => $value) {
		$response = $astman->send_request('Command',array('Command'=>$value));
		$astout = explode("\n",$response['data']);
		switch ($key) {
			case 'Uptime':
				$uptime = $astout;
				$htmlOutput .= '<tr><td colspan="2">'.$uptime[1]."<br />".$uptime[2]."<br /></td>";
				$htmlOutput .= '</tr>';
			break;
			case 'Active SIP Channel(s)':
				$activeSipChannel = $astout;
				$activeSipChannel_count = getActiveChannel($activeSipChannel, $channelType = 'SIP');
				$htmlOutput .= '<tr>';
				$htmlOutput .= "<td>Active Sip Channels: ".$activeSipChannel_count."</td>";
			break;
			case 'Active IAX2 Channel(s)':
				$activeIAX2Channel = $astout;
				$activeIAX2Channel_count = getActiveChannel($activeIAX2Channel, $channelType = 'IAX2');
				$htmlOutput .= "<td>Active IAX2 Channels: ".$activeIAX2Channel_count."</td>";
				$htmlOutput .= '</tr>';
			break;
			break;
			case 'Sip Registry':
				$sipRegistration = $astout;
				$sipRegistration_count = getRegistration($sipRegistration, $channelType = 'SIP');
				$htmlOutput .= '<tr>';
				$htmlOutput .= "<td>SIP Registrations: ".$sipRegistration_count."</td>";
			break;
			case 'IAX2 Registry':
				$iax2Registration = $astout;
				$iax2Registration_count = getRegistration($iax2Registration, $channelType = 'IAX2');
				$htmlOutput .= "<td>IAX2 Registrations: ".$iax2Registration_count."</td>";
				$htmlOutput .= '</tr>';
			break;
			case 'Sip Peers':
				$sipPeer = $astout;
				$sipPeer_arr = getPeer($sipPeer, $channelType = 'SIP');
				if($sipPeer_arr['offline'] != 0){
					$sipPeerColor = 'red';
				}else{
					$sipPeerColor = '#000000';
				}
				$htmlOutput .= '<tr>';
				$htmlOutput .= "<td>SIP Peers<br />&nbsp;&nbsp;&nbsp;&nbsp;Online: ".$sipPeer_arr['online']."<br />&nbsp;&nbsp;&nbsp;&nbsp;Offline: <span style=\"color:".$sipPeerColor.";font-weight:bold;\">".$sipPeer_arr['offline']."</span></td>";
			break;
			case 'IAX2 Peers':
				$iax2Peer = $astout;
				$iax2Peer_arr = getPeer($iax2Peer, $channelType = 'IAX2');
				if($iax2Peer_arr['offline'] != 0){
					$iax2PeerColor = 'red';
				}else{
					$iax2PeerColor = '#000000';
				}
				$htmlOutput .= "<td>IAX2 Peers<br />&nbsp;&nbsp;&nbsp;&nbsp;Online: ".$iax2Peer_arr['online']."<br />&nbsp;&nbsp;&nbsp;&nbsp;Offline: <span style=\"color:".$iax2PeerColor.";font-weight:bold;\">".$iax2Peer_arr['offline']."</span><br />&nbsp;&nbsp;&nbsp;&nbsp;Unmonitored: ".$iax2Peer_arr['unmonitored']."</td>";
				$htmlOutput .= '</tr>';
			break;
			default:
			}
		}
	$htmlOutput .= '</table>';
	return $htmlOutput."</div>";
}


?>

</center>


<?php
// #### FOOTER SECTION
$smarty->display('footer.tpl');
?>