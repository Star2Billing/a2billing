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


include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");

if (!has_rights(ACX_ADMINISTRATOR)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

getpost_ifset(array('id','result','action','message','id_msg','type','logo'));

if (empty($id)) {
	header("Location: A2B_entity_agent.php?atmenu=user&section=2");
}
if ($result == "success") {
	$message_action = gettext("Home updated successfully");
}
$DBHandle = DbConnect();

if (!empty($action)) {
	switch ($action) {
		case 'add' :
			$DBHandle = DbConnect();
			$instance_table = new Table("cc_message_agent","COUNT(*)");
			$clause="id_agent = $id";
			$result=$instance_table -> Get_list($DBHandle, $clause);
			if(is_array($result) && sizeof($result)>0){
			    $count = $result[0][0];
			    $fields="id_agent,type,message,order_display,logo";
			    if(empty($logo))$logo=0;
			    $values="$id,$type,'".addslashes($_POST['message'])."',$count,$logo";
			    $return=$instance_table->Add_table($DBHandle, $values, $fields);
			    if($return)$result_param ="success";
			    else $result_param ="faild";
			    header("Location: A2B_agent_home.php?" . "id=" . $id . "&result=$result_param");
			    die();
			}
			header("Location: A2B_agent_home.php?" . "id=" . $id . "&result=faild");
			die();
			break;
		case 'askedit' :
			if(is_numeric($id_msg)){
			    $DBHandle = DbConnect();
			    $clause = "id = $id_msg";
			    $instance_table = new Table("cc_message_agent","*");
			    $result=$instance_table -> Get_list($DBHandle, $clause);
			    if(is_array($result) && sizeof($result)>0){
				$message = stripslashes($result[0]['message']);
				$logo =$result[0]['message'];
				$type=$result[0]['type'];
				$logo=$result[0]['logo'];
				$action="edit";
			    }
			}
			break;
		case 'edit' :
			if(is_numeric($id_msg)){
			    $DBHandle = DbConnect();
			    $clause = "id = $id_msg";
			    $instance_table = new Table("cc_message_agent","*");
			    if(empty($logo))$logo=0;
			    $values="type = $type, message ='".addslashes($_POST['message'])."',logo = $logo";
			    $return=$instance_table -> Update_table($DBHandle, $values, $clause);
			    if($return)$result_param ="success";
			    else $result_param ="faild";
			    header("Location: A2B_agent_home.php?" . "id=" . $id . "&result=$result_param");
			    die();
			}
			header("Location: A2B_agent_home.php?" . "id=" . $id . "&result=faild");
			die();
			break;
		case 'delete' :
			if(is_numeric($id_msg)){
			    $DBHandle = DbConnect();
			    $instance_table = new Table("cc_message_agent","*");
			    $clause = "id = $id_msg";
			    $result=$instance_table -> Get_list($DBHandle, $clause);
			    if(is_array($result) && sizeof($result)>0){
				$order = $result[0]['order_display'];
				$instance_table -> Delete_table($DBHandle, $clause);
				$instance_table->Update_table($DBHandle, "order_display = order_display - 1", "id_agent = '".$id."' AND order_display >".$order);
				echo "true";
				die();
			    }
			    echo "false";
			    die();
			}
			echo "false";
			die();
			break;
		case 'up' :
			if(is_numeric($id_msg)){
			    $DBHandle = DbConnect();
			    $clause = "id = $id_msg";
			    $instance_table = new Table("cc_message_agent","*");
			    $result=$instance_table -> Get_list($DBHandle, $clause);
			    if(is_array($result) && sizeof($result)>0){
				$order = $result[0]['order_display'];
				$instance_table->Update_table($DBHandle, "order_display = order_display + 1", "id_agent = '".$id."' AND order_display =".($order-1));
				$instance_table->Update_table($DBHandle, "order_display = order_display - 1", "id_agent = '".$id."' AND order_display =".($order)." AND id =$id_msg");
				echo "true";
				die();
			    }
			    echo "false";
			    die();
			}
			echo "false";
			die();
			break;
		case 'down':
			if(is_numeric($id_msg)){
			    $DBHandle = DbConnect();
			    $clause = "id = $id_msg";
			    $instance_table = new Table("cc_message_agent","*");
			    $result=$instance_table -> Get_list($DBHandle, $clause);
			    if(is_array($result) && sizeof($result)>0){
				$order = $result[0]['order_display'];
				$instance_table->Update_table($DBHandle, "order_display = order_display - 1", "id_agent = '".$id."' AND order_display =".($order+1));
				$instance_table->Update_table($DBHandle, "order_display = order_display + 1", "id_agent = '".$id."' AND order_display =".($order)." AND id =$id_msg");
				echo "true";
				die();
			    }
			    echo "false";
			    die();
			}
			echo "false";
			die();
			break;

	}
}
//load home message agent
if(empty($action)) $action="add";

$table_message = new Table("cc_message_agent", "*");
$clause_message = "id_agent = $id";
$messages = $table_message -> Get_list($DBHandle, $clause_message, 'order_display', 'ASC');
$smarty->display('main.tpl');
$message_types = Constants::getMsgTypeList();
?>

<form action="<?php echo $PHP_SELF.'?id='.$id ?>" method="post" >
 	<input id="action" type="hidden" name="action" value="<?php echo $action;?>"/>
	<input id="id_msg" type="hidden" name="id_msg" value="<?php echo $id_msg;?>"/>
	<table class="epayment_conf_table">
		<tr>
			<td colspan="2">
			    <font style="font-weight:bold; " ><?php echo gettext("MESSAGE : "); ?> </font>
			 </td>
		</tr>
		<tr>
			<td colspan="2" align="center">
			    <textarea id="wysiwyg" class="form_input_textarea" name="message" cols="100" rows="10"><?php echo $message;?></textarea>
			 </td>
		</tr>
		<tr>
			<td colspan="2">
			    <font style="font-weight:bold; " ><?php echo gettext("TYPE : "); ?></font>
			    <select name="type">
			     <?php
			     foreach ($message_types as $msg_type)
				     { ?>
					<option value="<?php echo $msg_type[1];?>" <?php if($type==$msg_type[1]) echo "selected"?> > <?php echo $msg_type[0];?></option>
					<?php
				     }
			      ?>
			    </select>

			    <input type="checkbox" value="1" name="logo" <?php if( $action!="edit" || $logo==1  ) echo "checked";?> /> <?php echo gettext("logo "); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right">
			    <input class="form_input_button" type="submit" value="<?php if($action!="edit")echo gettext("ADD");else echo gettext("UPDATE") ?>"/>
			 </td>
		</tr>
	</table>
  </form>
<br/>
<div align="center">
<h1><?php echo gettext("Agent Home Page Preview")?> </h1>
<br/>
<?php
$size_msg = sizeof($messages);
if(!is_array($messages)){ ?>
<h3><?php echo gettext("No Message")?> </h1>
<?php
}?>
</div>
<?php
foreach ($messages as $message) {
    ?>

	<div id="msg" class="<?php echo $message_types[$message['type']][2];?>" style="margin-top:0px;position:relative;<?php if($message['logo']==0)echo 'background-image:none;padding-left:10px;'; ?>" >
	    <?php if($message['order_display']>0){ ?>
		    <img id="<?php echo $message['id']; ?>" onmouseover="this.style.cursor='pointer'" class="up" src="<?php echo Images_Path ?>/arrow_up.png"  border="0" style="position:absolute;right:60px;top:0;display:none"/>
	     <?php } ?>
		<img id="<?php echo $message['id']; ?>" onmouseover="this.style.cursor='pointer'" class="delete" src="<?php echo Images_Path ?>/delete.png"  border="0" style="position:absolute;right:40px;top:0;display:none"/>
	        <img id="<?php echo $message['id']; ?>" onmouseover="this.style.cursor='pointer'" class="edit" src="<?php echo Images_Path ?>/edit.png"  border="0" style="position:absolute;right:20px;top:0;display:none"/>
	     <?php if($message['order_display']<$size_msg-1){ ?>
		    <img id="<?php echo $message['id']; ?>" onmouseover="this.style.cursor='pointer'" class="down" src="<?php echo Images_Path ?>/arrow_down.png"  border="0" style="position:absolute;right:0px;top:0;display:none" />
	     <?php } ?>
	    <?php echo stripslashes($message['message']); ?>
	</div>

<?php
}


$smarty->display('footer.tpl');
?>

<script type="text/javascript">
var id_agent= <?php echo $id; ?> ;
$(function()
{
    $('#wysiwyg').wysiwyg();
});

$(document).ready(function () {

	$('.msg_info , .msg_success , .msg_warning , .msg_error').mouseover(function () {
	    $(this).children(".up,.down,.delete,.edit").show();
	     });
	$('.msg_info , .msg_success , .msg_warning , .msg_error').mouseout(function () {
		$(this).children(".up,.down,.delete,.edit").hide();
	     });
	$('.delete').click(function () {
		if (confirm("<?php echo gettext("Do you want delete this message ?") ?>")) {
			$.get("A2B_agent_home.php", { id : id_agent ,id_msg: ""+ this.id, action: "delete" },
				  function(data){
				      if(data)window.location= "A2B_agent_home.php?id="+id_agent+"&result=success";
				  });
		}
	    });
	$('.up').click(function () {
		$.get("A2B_agent_home.php", { id : id_agent ,id_msg: ""+ this.id, action: "up" },
			  function(data){
			      if(data)window.location= "A2B_agent_home.php?id="+id_agent;
			  });
	    });
	$('.down').click(function () {
		$.get("A2B_agent_home.php", { id : id_agent ,id_msg: ""+ this.id, action: "down" },
			  function(data){
			      if(data)window.location= "A2B_agent_home.php?id="+id_agent;
			  });
	    });
	$('.edit').click(function () {
		window.location= "A2B_agent_home.php?id="+id_agent+"&id_msg="+this.id+"&action=askedit";
	    });
});
</script>