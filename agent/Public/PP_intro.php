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
			<img src="images/logo/a2billing.png">
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

