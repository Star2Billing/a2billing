<?php
include ("../lib/agent.defines.php");
include_once ("../lib/agent.module.access.php");
include ("../lib/agent.smarty.php");


if (!$ACXACCESS) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

$smarty->display('main.tpl');
$DBHandle = DbConnect();
$table_message = new Table("cc_message_agent", "*");
$clause_message = "id_agent = ".$_SESSION['agent_id'];
$messages = $table_message -> Get_list($DBHandle, $clause_message, 'order_display', 'ASC');
$message_types = Constants::getMsgTypeList();
?>
<br/><br/>
<?php
if(is_array($messages)&& sizeof($messages)>0){
    foreach ($messages as $message) {
	?>

	    <div id="msg" class="<?php echo $message_types[$message['type']][2];?>" style="margin-top:0px;position:relative;<?php if($message['logo']==0)echo 'background-image:none;padding-left:10px;'; ?>" >
		<?php echo stripslashes($message['message']); ?>
	    </div>
    <?php }
}else{
?>
<center>
<table align="center" width="90%" bgcolor="white" cellpadding="25" cellspacing="25" style="border: solid 1px">
	<tr>
		<td width="340">
			<img src="<?php echo Images_Path;?>/a2b-logo-450.png">
			<br><br>
			<center><b><i>A2Billing is licensed under <a href="http://www.fsf.org/licensing/licenses/agpl-3.0.html" target="_blank">AGPL 3</a>.</i></b></center>
			<br><br>
		</td>
		<td align="left">
		For information and documentation on A2Billing, <br> please visit <a href="http://www.a2billing.org" target="_blank">http://www.a2billing.org</a><br><br>
		
		For Commercial Installations, Hosted Systems, Customisation and Commercial support, please visit <a href="http://www.star2billing.com" target="_blank">http://www.star2billing.com</a><br><br>
		<br/><br/><br/><br/>
		
		</td>
	</tr>
</table>
</center>

<br>

	

<?php
}
$smarty->display('footer.tpl');

