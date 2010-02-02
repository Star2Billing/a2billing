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
include ("./form_data/FG_var_invoice.inc");
include ("../lib/admin.smarty.php");

if (!has_rights(ACX_INVOICING)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

getpost_ifset(array (
	'id',
	'action'
));

$DBHandle = DbConnect();

if ($action == "lock") {
	if (!empty ($id) && is_numeric($id)) {
		$instance_table_invoice = new Table("cc_invoice");
		$param_update_invoice = "status = '1'";
		$clause_update_invoice = " id ='$id'";
		$instance_table_invoice->Update_table($DBHandle, $param_update_invoice, $clause_update_invoice, $func_table = null);
	}
	die();
}

$HD_Form->setDBHandler($DBHandle);
$HD_Form->init();

if ($id != "" || !is_null($id)) {
	$HD_Form->FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form->FG_EDITION_CLAUSE);
}

if (!isset ($form_action))
	$form_action = "list"; //ask-add
if (!isset ($action))
	$action = $form_action;

$list = $HD_Form->perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
echo $CC_help_view_invoice;

?>
<script language="JavaScript" src="javascript/card.js"></script>
<div class="toggle_hide2show">
<center><a href="#" target="_self" class="toggle_menu"><img class="toggle_hide2show" src="<?php echo KICON_PATH; ?>/toggle_hide2show.png" onmouseover="this.style.cursor='hand';" HEIGHT="16"> <font class="fontstyle_002"><?php echo gettext("SEARCH INVOICE");?> </font></a><?php if(!empty($_SESSION['entity_invoice_selection'])){ ?>&nbsp;(<font style="color:#EE6564;" > <?php echo gettext("search activated"); ?> </font> ) <?php } ?> </center>
	<div class="tohide" style="display:none;">
<?php
// #### CREATE SEARCH FORM
if ($form_action == "list") {
	$HD_Form -> create_search_form();
}
?>
	</div>
</div>

<?php
// #### TOP SECTION PAGE
$HD_Form->create_toppage($form_action);

$HD_Form->create_form($form_action, $list, $id = null);

// #### FOOTER SECTION
$smarty->display('footer.tpl');

?>


<script type="text/javascript">	
$(document).ready(function () {
	$('.lock').click(function () {
			$.get("A2B_entity_invoice.php", { id: ""+ this.id, action: "lock" },
				  function(data){
				    location.reload(true);
				  });
	        });
});
</script>

