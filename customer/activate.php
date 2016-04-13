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

session_name("UISIGNUP");
session_start();

// check if the script has been already called in the previous minute, no multiple signup
if (!isset ($_SESSION["date_activation"]) || (time() - $_SESSION["date_activation"]) > 0) {
    $_SESSION["date_activation"] = time();
} else {
    sleep(3);
    echo gettext("Sorry the activation has been sent already, please wait 1 minute before making any other try !");
    exit ();
}

// get include
include './lib/customer.defines.php';
include './lib/customer.module.access.php';
include './lib/Form/Class.FormHandler.inc.php';
include './lib/customer.smarty.php';

getpost_ifset(array ( 'key' ));

$HD_Form = new FormHandler("cc_card", "User");
$HD_Form->setDBHandler(DbConnect());
$HD_Form->init();

// HEADER SECTION
$smarty->display('signup_header.tpl');

if (empty ($key))
    $key = null;

$result = null;
$instance_sub_table = new Table('cc_card', "username, lastname, firstname, email, uipass, credit, useralias, loginkey, status, id");
$QUERY = "( loginkey = '" . $key . "' )";
$list = $instance_sub_table->Get_list($HD_Form->DBHandle, $QUERY);

if (isset ($key) && $list[0][8] != "1") {
    if ($A2B->config["signup"]['activated']) {
        // Status : 1 - Active
        $QUERY = "UPDATE cc_card SET status = 1 WHERE ( status = 2 OR status = 3 ) AND loginkey = '" . $key . "' ";
    } else {
        // Status : 2 - New
        $QUERY = "UPDATE cc_card SET status = 2 WHERE ( status = 2 OR status = 3 ) AND loginkey = '" . $key . "' ";
    }
    $result = $instance_sub_table->SQLExec($HD_Form->DBHandle, $QUERY, 0);
}

if ($list[0][8] != "1" && isset ($result) && $result != null) {

    list ($username, $lastname, $firstname, $email, $uipass, $credit, $cardalias, $loginkey, $status, $idcard) = $list[0];
    if ($FG_DEBUG == 1) {
        echo "<br/># $username, $lastname, $firstname, $email, $uipass, $credit, $cardalias #<br/>";
    }

    try {
        $mail = new Mail(Mail :: $TYPE_SIGNUPCONFIRM, $idcard);
        $mail->send($email);

        $mail->setTitle("NEW ACCOUNT CREATED : " . $mail->getTitle());
        $mail->send(ADMIN_EMAIL);

    } catch (A2bMailException $e) {
        echo "Error : sent mail!";
    }
?>

<blockquote>
    <div align="center"><br/><br/>
     <font color="#FF0000"><b><?php echo gettext("Welcome! Your account has been successfully activated. Thank you!"); ?></b></font><br/>
          <br/><br/>
          <?php echo $list[0][2]; ?> <?php echo $list[0][1]; ?>, <?php echo gettext("Thank you for registering with us !");?><br/>
          <?php echo gettext("An email confirming your information has been sent to"); ?> <b><?php echo $list[0][3]; ?></b><br/><br/>
            <h3>
              <?php echo gettext("Your cardnumber is "); ?> <b><font color="#00AA00"><?php echo $list[0][0]; ?></font></b><br/><br/><br/>
              <?php echo gettext("To login to your account :"); ?><br/>
              <?php echo gettext("Your card alias (login) is "); ?> <b><font color="#00AA00"><?php echo $list[0][6]; ?></font></b><br/>
              <?php echo gettext("Your password is "); ?> <b><font color="#00AA00"><?php echo $list[0][4]; ?></font></b><br/>
            </h3>

            <br/><br/>
    <?php echo gettext("Follow the link to access your account : ").'<a href="'.$A2B->config["signup"]['urlcustomerinterface'].'">'.$A2B->config["signup"]['urlcustomerinterface']."</a><br/>"; ?>

</div>
</blockquote>

<?php
} else {
?>

<center>
<br/><br/><br/><br/>
<br/><br/><br/><br/>

<table width="400">
<tr><td colspan="2" bgcolor="#DDDDDD"></td></tr>
<tr><td colspan="2" bgcolor="#DDDDDD"></td></tr>
<tr>
<td bgcolor="#EEEEEE"><img src="<?php echo KICON_PATH;?>/khelpcenter.gif"/></td>
<td bgcolor="#EEEEEE">
<b>
<?php
if ($list[0][9] == "1") {
    echo gettext("Your account is already activated.")." <br/>";
} elseif (isset($result) || $result != null) {
    // nothing
} else {
    echo gettext("Your account cannot be activated please contact <br/> the website administrator or retry later.")." <br/>";
}
?>
</b>
</td></tr>
<tr><td colspan="2" bgcolor="#DDDDDD"></td></tr>
<tr><td colspan="2" bgcolor="#DDDDDD"></td></tr>
</table>

<br/><br/><br/><br/>
<br/><br/><br/><br/>
</center>

<?php
}

// #### FOOTER SECTION
$smarty->display('signup_footer.tpl');
