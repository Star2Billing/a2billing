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
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_mailtemplate.inc");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_MAIL)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

getpost_ifset(array('languages','id','action'));

if($action=="load") {
	$DBHandle=DbConnect();
	if(!empty($id) && is_numeric($id)){
		$instance_table_mail = new Table("cc_templatemail","messagetext, fromemail, fromname, subject");
		$clause_mail = " id ='$id'";
		$result=$instance_table_mail-> Get_list($DBHandle, $clause_mail);
		echo json_encode($result[0]);
	}
	die();
}

if ($popup_select) {
?>
<SCRIPT LANGUAGE="javascript">
<!-- Begin
function sendValue(selvalue){
	$.getJSON("A2B_entity_mailtemplate.php", { id: ""+ selvalue, action: "load" },
	function(data){
		window.opener.document.getElementById('msg_mail').value = data.messagetext;
		window.opener.document.getElementById('from').value = data.fromemail;
		window.opener.document.getElementById('fromname').value = data.fromname;
		window.opener.document.getElementById('subject').value = data.subject;
		window.close();
	});
}
// End -->
</script>
<?php
}

$HD_Form -> setDBHandler (DbConnect());


$HD_Form -> init();

if ($id!="" || !is_null($id)) {
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;


$list = $HD_Form -> perform_action($form_action);


// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
if (!$popup_select) echo $CC_help_list_misc;
if(isset($form_action) && $form_action=="list"){
?>
<table align="center" class="bgcolor_001" border="0" width="30%">
    <tr>
		<form name="theForm" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		  <?php if($popup_select){ ?>
		  		<input type="hidden" name="popup_select" value="<?php echo $popup_select; ?>" />
		  <?php } ?>
          <td align="left" width="75%">
					<?php
						$handle = DbConnect();
						$instance_table = new Table();
						$QUERY =  "SELECT code, name FROM cc_iso639 order by code";
						$result = $instance_table -> SQLExec ($handle, $QUERY);
						if (is_array($result)){
							$num_cur = count($result);
							for ($i=0;$i<$num_cur;$i++){
								$languages_list[$result[$i][0]] = array (0 => $result[$i][0], 1 => $result[$i][1]);
							}
						}
					?>
				<select NAME="languages" size="1" class="form_input_select" onChange="form.submit()">
					<?php 
					foreach($languages_list as $key => $lang_value) {											
				?>
					<option value='<?php echo $lang_value[0];?>' <?php if($lang_value[0]==$languages) print "selected";?>><?php echo $lang_value[1]; ?></option>
				<?php } ?>		
        </td>
       </form>
   </tr>
</table>
<?php
}

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### FOOTER SECTION
$smarty->display('footer.tpl');


