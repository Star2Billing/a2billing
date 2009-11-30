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
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_ticket_agent.inc");
include ("../lib/agent.smarty.php");

if (! has_rights (ACX_SUPPORT)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}

getpost_ifset(array('title', 'description', 'priority' , 'component'));

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();


// ADD Ticket
if (strlen($description)>0  && is_numeric($priority) && strlen($title)>0  && is_numeric($component)) {

		$fields = "creator,creator_type,title, description, id_component, priority, viewed_agent";
		$ticket_table = new Table('cc_ticket', $fields);
		$values = "'".$_SESSION["agent_id"]."',1, '".$title."', '".$description."', '".$component."', '".$priority ."' ,'0'";
		$id_ticket = $ticket_table ->Add_table($HD_Form -> DBHandle, $values, null, null, "id");
		NotificationsDAO::AddNotification("ticket_added_agent",Notification::$MEDIUM,Notification::$AGENT,$_SESSION['agent_id'],Notification::$LINK_TICKET_AGENT,$id_ticket);
		$table_agent =new Table("cc_agent", "firstname,lastname,language,email");
		$agent_clause = "id = ".$_SESSION["agent_id"];
		$result=$table_agent ->Get_list($HD_Form -> DBHandle, $agent_clause);
		$owner = $_SESSION["pr_login"]." (".$result[0]['firstname']." ".$result[0]['lastname'].")";
		try {
			$mail = new Mail(Mail::$TYPE_TICKET_NEW, null, $result[0]['language']);
			$mail->replaceInEmail(Mail::$TICKET_OWNER_KEY, $owner);
			$mail->replaceInEmail(Mail::$TICKET_NUMBER_KEY, $id_ticket);
			$mail->replaceInEmail(Mail::$TICKET_PRIORITY_KEY, Ticket::DisplayPriority($priority));
			$mail->replaceInEmail(Mail::$TICKET_DESCRIPTION_KEY, $description);
			$mail->replaceInEmail(Mail::$TICKET_STATUS_KEY,"NEW");
			$mail->replaceInEmail(Mail::$TICKET_TITLE_KEY, $title);
			$mail->send($result[0]['email']);
		} catch (A2bMailException $e) {
            $error_msg = $e->getMessage();
        }
		$component_table = new Table('cc_support_component LEFT JOIN cc_support ON id_support = cc_support.id', "email");
		$component_clause = "cc_support_component.id = ".$component;
		$result= $component_table -> Get_list($HD_Form -> DBHandle, $component_clause);
		
		try {
			$mail = new Mail(Mail::$TYPE_TICKET_NEW, null, $result[0]['language']);
			$mail->replaceInEmail(Mail::$TICKET_OWNER_KEY, $owner);
			$mail->replaceInEmail(Mail::$TICKET_NUMBER_KEY, $id_ticket);
			$mail->replaceInEmail(Mail::$TICKET_PRIORITY_KEY, Ticket::DisplayPriority($priority));
			$mail->replaceInEmail(Mail::$TICKET_DESCRIPTION_KEY, $description);
			$mail->replaceInEmail(Mail::$TICKET_STATUS_KEY,"NEW");
			$mail->replaceInEmail(Mail::$TICKET_TITLE_KEY, $title);
			$mail->send($result[0]['email']);
		} catch (A2bMailException $e) {
            $error_msg = $e->getMessage();
        }
		$update_msg = gettext("Ticket added successfully");

}

if ($id!="" || !is_null($id)) { 
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;
$list = $HD_Form -> perform_action($form_action);
// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
echo $CC_help_support_list;


if ($form_action == "list") {
    // My code for Creating two functionalities in a page
    $HD_Form -> create_toppage ("ask-add");

?>
	  </center>
	  <center><font class="error_message"><?php echo gettext("Create a NEW Support Ticket"); ?></font></center>
	  <center>
	   <table align="center" >
		<form name="theForm" action="<?php  $_SERVER["PHP_SELF"]?>">


		<tr class="bgcolor_001">
		<td align="left" valign="bottom">
		<font class="fontstyle_002"><?php echo gettext("Title");?> :</font>
		</td>
		<td>
				<input class="form_input_text" name="title" size="100" maxlength="100" />
		</td>
		</tr>
        <tr>
         <td>
         	<font class="fontstyle_002"><?php echo gettext("Priority");?> :</font>
         </td>
         <td>
       			<select NAME="priority" class="form_input_select">
						<option class=input value='0' >NONE </option>
						<option class=input value='1' >LOW </option>
						<option class=input value='2' >MEDIUM </option>
						<option class=input value='3' >HIGH </option>
				</select>
         </td>
        </tr>
          <tr class="bgcolor_001">
         <td>
         	<font class="fontstyle_002"><?php echo gettext("Component");?> :</font>
         </td>
         <td>
         <select NAME="component" class="form_input_select">
	         <?php
			         $DBHandle  = DbConnect();
			   	 $instance_sub_table = new Table("cc_support_component", "*");
				 $QUERY = " activated = 1 AND (type_user = 1 OR type_user = 2)";
				 $return = null;
			     $return = $instance_sub_table -> Get_list($DBHandle, $QUERY, 0);
			     	foreach ($return as $value){

						echo	'<option class=input value=" '. $value["id"].'"  > ' . $value["name"]. '  </option>' ;


			     	}
	     ?>
				</select>

         </td>
        </tr>
		<tr>
		<td align="left" valign="top">
				<font class="fontstyle_002"><?php echo gettext("Description");?> :</font>
			</td>
			<td>
				 <textarea class="form_input_text" name="description" cols="100" rows="6"></textarea>
			</td>
        </tr>
        <tr>
			<td colspan="2" align="right" valign="middle">
						<input class="form_input_button"  value="<?php echo gettext("CREATE");?>"  type="submit">
		</td>
		</tr>
	</form>
      </table>
	  </center>
	  <br>
<center><font class="error_message"><?php if (isset($update_msg) && strlen($update_msg)>0) echo $update_msg; ?></font></center>
	<?php
    // END END END My code for Creating two functionalities in a page
}


// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### FOOTER SECTION
$smarty->display('footer.tpl');

