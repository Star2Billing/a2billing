<?php
include ("lib/customer.defines.php");
include ("lib/customer.module.access.php");
include ("lib/customer.smarty.php");

if (! has_rights (ACX_WEB_PHONE)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();
}



//require (LANGUAGE_DIR.FILENAME_WEBPHONE);

$QUERY = "SELECT  activated, sip_buddy, iax_buddy, username FROM cc_card WHERE username = '".$_SESSION["pr_login"]."' AND uipass = '".$_SESSION["pr_password"]."'";

$DBHandle  = DbConnect();
$instance_table = new Table();
$customer_info = $instance_table -> SQLExec ($DBHandle, $QUERY);

if (!is_array($customer_info)){ 
	echo gettext("ERROR TO LOAD PEER!");
	exit();
};
//print_r($customer_info);

if( $customer_info [0][1] == "t" || $customer_info [0][1] == "1" ) {
	$SIPQUERY="SELECT secret FROM cc_sip_buddies WHERE username = '".$customer_info[0][3]."'";
	$sip_info = $instance_table -> SQLExec ($DBHandle, $SIPQUERY);
}

if( $customer_info [0][2] == "t" || $customer_info [0][2] == "1" ) {
	$IAXQUERY="SELECT secret FROM cc_iax_buddies WHERE username = '".$customer_info[0][3]."'";
	$iax_info = $instance_table -> SQLExec ($DBHandle, $IAXQUERY);
}

$customer = $_SESSION["pr_login"];
$smarty->display( 'main.tpl');

// #### HELP SECTION
echo $CC_help_webphone;

?>

<br>
<br><center>
<br><br>
<font class="fontstyle_002"><?php echo gettext("Account/Phone");?> :</font> <font class="fontstyle_007"><?php echo $customer_info[0][3]; ?></font>
</center>

<?php if (false){ ?>
	<table align="center" class="bgcolor_006" border="0" width="75%">
		<FORM NAME="phonesip" METHOD="POST" ACTION="jiaxclient/sipphone.php" target="_blank">
		<?php
			echo "<INPUT TYPE=\"HIDDEN\" NAME=\"webphone_server\" VALUE=\"".$A2B->config['webcustomerui']['webphoneserver']."\">\n";
			echo "<INPUT TYPE=\"HIDDEN\" NAME=\"webphone_user\" VALUE=\"".$customer_info[0][3]."\">\n";
			echo "<INPUT TYPE=\"HIDDEN\" NAME=\"webphone_secret\" VALUE=\"".$sip_info[0][0]."\">\n";
			echo "<INPUT TYPE=\"HIDDEN\" NAME=\"webphone_number\" VALUE=\"\">\n";
		?>
        <tr  class="bgcolor_006">
			<td align="center" valign="bottom">
				<img src="<?php echo KICON_PATH ?>/stock_cell-phone.gif" class="phone"/><br>
				<br><font class="fontstyle_002"><?php echo gettext("SIP WEB-PHONE")?></font>
					</br></br>
			</td>
			<td align="center" valign="middle"><font class="fontstyle_007">
					<?php
						if( $customer_info [0][1] != "t" && $customer_info [0][1] != "1" ) {
							echo "&nbsp;".gettext("NO SIP ACCOUNT")."&nbsp;";
						}else{ ?>
						<input class="form_input_button"  value="[ <?php echo gettext("Click to start SIP WebPhone")?>]" type="submit">
					<?php } ?></font>
			</td>
        </tr>
		</FORM>
	</table>
<?php } ?>

<br>
<table align="center" class="bgcolor_006" border="0" width="75%">
	<FORM NAME="phoneiax" METHOD="POST" ACTION="jiaxclient/iaxphone.php" target="_blank">
	<?php
		echo "<INPUT TYPE=\"HIDDEN\" NAME=\"webphone_server\" VALUE=\"".$A2B->config['webcustomerui']['webphoneserver']."\">\n";
		echo "<INPUT TYPE=\"HIDDEN\" NAME=\"webphone_user\" VALUE=\"".$customer_info[0][3]."\">\n";
		echo "<INPUT TYPE=\"HIDDEN\" NAME=\"webphone_secret\" VALUE=\"".$iax_info[0][0]."\">\n";
		echo "<INPUT TYPE=\"HIDDEN\" NAME=\"webphone_number\" VALUE=\"\">\n";
	?>
	<tr class="bgcolor_006">
		<td align="center" valign="bottom">
			<img src="<?php echo KICON_PATH ?>/stock_cell-phone.gif" class="phone"/><br>
			<font class="fontstyle_002"><?php echo gettext("IAX WEB-PHONE")?></font>
				</br></br>
		</td>
		<td align="center" valign="middle"><font class="fontstyle_007">
				<?php
					if( $customer_info [0][2] != "t" && $customer_info [0][2] != "1" ) {
						echo gettext("NO IAX ACCOUNT");
					}else{ ?>
					<input class="form_input_button" value="[ <?php echo gettext("START IAX PHONE")?>]" type="submit">
				<?php } ?></font>
		</td>
	</tr>
	</FORM>
</table>
<br><br><br><br>


<?php

// #### FOOTER SECTION
$smarty->display('footer.tpl');

