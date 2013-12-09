<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2012 - Star2billing S.L.
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

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_remittance_request.inc';
include '../lib/admin.smarty.php';

if (!has_rights(ACX_BILLING)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}
getpost_ifset(array (
    'id',
    'action'
));

$DBHandle = DbConnect();

if ($action == "accept") {
    if (!empty ($id) && is_numeric($id)) {
        $instance_table_remittance = new Table("cc_remittance_request","*");
        $param_update_remittance = "status = '1'";
        $clause_update_remittance = " id ='$id'";
        $instance_table_remittance->Update_table($DBHandle, $param_update_remittance, $clause_update_remittance, $func_table = null);
        // load
        $result=$instance_table_remittance -> Get_list($DBHandle,$clause_update_remittance);
        $type = $result[0]['type'];
        $agent_id = $result[0]['id_agent'];
        $credit = $result[0]['amount'];
        if ($type==0) {
            // insert refill
            $field_insert = " credit, agent_id, description";
            $value_insert = "'".$credit."', '$agent_id', '".gettext('REFILL BY REMITTANCE REQUEST')."'";
            $instance_sub_table = new Table("cc_logrefill_agent", $field_insert);
            $instance_sub_table -> Add_table ($DBHandle, $value_insert, null, null, 'id');

            //REFILL... UPDATE AGENT
            $instance_table_agent = new Table("cc_agent");
            $param_update_agent = "credit = credit + '".$credit."' , com_balance = com_balance - $credit ";
            $clause_update_agent = " id='$agent_id'";
            $instance_table_agent -> Update_table ($DBHandle, $param_update_agent, $clause_update_agent, $func_table = null);
        } else {
            //UPDATE AGENT
            $instance_table_agent = new Table("cc_agent");
            $param_update_agent = " com_balance = com_balance - $credit ";
            $clause_update_agent = " id='$agent_id'";
            $instance_table_agent -> Update_table ($DBHandle, $param_update_agent, $clause_update_agent, $func_table = null);
        }
    }
    die();
}

if ($action == "refuse") {
    if (!empty ($id) && is_numeric($id)) {
        $instance_table_remittance = new Table("cc_remittance_request");
        $param_update_remittance = "status = '2'";
        $clause_update_remittance = " id ='$id'";
        $instance_table_remittance->Update_table($DBHandle, $param_update_remittance, $clause_update_remittance, $func_table = null);
    }
    die();
}
$HD_Form->setDBHandler(DbConnect());
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
echo $CC_help_view_remittance_agent;

if ($form_action == "list") {
    $HD_Form->create_search_form();
}
// #### TOP SECTION PAGE
$HD_Form->create_toppage($form_action);

$HD_Form->create_form($form_action, $list, $id = null);

// #### FOOTER SECTION
$smarty->display('footer.tpl');
?>
<script type="text/javascript">
$(document).ready(function () {
    $('.accept_click').click(function () {
        $.get("A2B_entity_remittance_request.php", { id: ""+ this.id, action: "accept" },
              function(data){
                location.reload(true);
              });
        });
    $('.refuse_click').click(function () {
        $.get("A2B_entity_remittance_request.php", { id: ""+ this.id, action: "refuse" },
              function(data){
                location.reload(true);
              });
        });
});
</script>
