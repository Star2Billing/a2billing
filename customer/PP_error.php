<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2015 - Star2billing S.L.
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

include 'lib/customer.defines.php';

include 'lib/customer.smarty.php';

$smarty->display('header.tpl');
session_destroy();

getpost_ifset(array('c'));
if (!isset($c)){
    $c = "0";
}

$error["0"] = gettext("ERROR : ACCESS REFUSED");
$error["syst"] = gettext("Sorry a problem occur on our system, please try later!");
$error["errorpage"] = gettext("There is an error on this page!");
$error["accessdenied"] = gettext("Sorry, you don't have access to this page !");

?>

<div class="block-updesign">

<div id="login-wrapper" class="login-border-up">
    <div class="login-border-down">
    <div class="login-border-center">
    <table>
        <tr>
            <td class="login-title" colspan="2">
                <font size="3"> <?php echo gettext("ERROR PAGE");?> </font>
            </td>
        </tr>
        <tr>
            <td width="70px" align="center">
                <img src="<?php echo KICON_PATH;?>/system-config-rootpassword.png">
            </td>
            <td align="center">
                <b><font size="é"><?php echo $error[$c]?></font></b>
            </td>
        </tr>
    </table>
          <div style="text-align:right;padding-right:10px;" >
              <a href="index.php<?php  if(isset($_SESSION['stylefile']) && !empty($_SESSION['stylefile'])) echo "?cssname=" . $_SESSION['stylefile'];?>" ><?php echo gettext("GO TO LOGIN PAGE"); ?>&nbsp;<img src="<?php echo Images_Path; ?>/key_go.png"> </a>
          </div>
    </div>

    </div>
</div>

</div>

<?php

$smarty->display('footer.tpl');
