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

include './lib/customer.defines.php';
include './lib/customer.module.access.php';
include './lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_callerid.inc';
include 'lib/customer.smarty.php';

if (! has_rights (ACX_CALLER_ID)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('add_callerid'));

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();

// ADD SPEED DIAL
if (strlen($add_callerid)>0  && is_numeric($add_callerid)) {
    $instance_sub_table = new Table('cc_callerid');
    $QUERY = "SELECT count(*) FROM cc_callerid WHERE id_cc_card='".$_SESSION["card_id"]."'";
    $result = $instance_sub_table -> SQLExec ($HD_Form -> DBHandle, $QUERY, 1);
    // CHECK IF THE AMOUNT OF CALLERID IS LESS THAN THE LIMIT
    if ($result[0][0] < $A2B->config["webcustomerui"]['limit_callerid']) {
        $QUERY = "INSERT INTO cc_callerid (id_cc_card, cid) VALUES ('".$_SESSION["card_id"]."', '".$add_callerid."')";
        $result = $instance_sub_table -> SQLExec ($HD_Form -> DBHandle, $QUERY, 0);
    }
}

if ($id!="" || !is_null($id)) {
    $HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;

$list = $HD_Form -> perform_action($form_action);

// #### HEADER SECTION
$smarty->display( 'main.tpl');

if ($form_action == "list") {
    // My code for Creating two functionalities in a page
    $HD_Form -> create_toppage ("ask-add");
?>
<center>
<?php

    if (isset($update_msg) && strlen($update_msg)>0) echo $update_msg;

    $count_cid = is_array($list) ? sizeof($list) : 0;
    if ($count_cid < $A2B->config["webcustomerui"]['limit_callerid']) {

?>
       <table align="center"  border="0" width="55%" class="bgcolor_006">
        <form name="theForm" action="<?php  $_SERVER["PHP_SELF"]?>">
        <tr class="bgcolor_001" >

        <td align="center" valign="top">
                <?php gettext("CALLER ID :");?>
                <input class="form_input_text" name="add_callerid" size="15" maxlength="60">
            </td>
            <td align="center" valign="middle">
                        <input class="form_input_button"  value="<?php echo gettext("ADD NEW CALLERID"); ?>"  type="submit">
        </td>
        </tr>
        </form>
      </table>
      <br>
    <?php
    } else {

    ?>
        <table align="center"  border="0" width="70%" class="bgcolor_006">
            <tr class="bgcolor_001" >
                <td align="center" valign="middle">
                    <b><i> <?php  echo gettext("You are not allowed to add more CallerID.");
                    echo "<br/>";
                     echo gettext("Remove one if you are willing to use an other CallerID.");?> </i> </b>
                    <br/>
                    <?php echo gettext("Max CallerId");?> &nbsp;:&nbsp; <?php echo $A2B->config["webcustomerui"]['limit_callerid'] ?>
                  </td>
              </tr>
         </table>
    <?php
    }
    // END END END My code for Creating two functionalities in a page
}
?>
</center>
<?php

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### FOOTER SECTION
$smarty->display( 'footer.tpl');
