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


include_once ("../../lib/admin.defines.php");
include_once ("../../lib/admin.module.access.php");

if (!has_rights(ACX_DASHBOARD)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

$DBHandle = DbConnect();
$QUERY_COUNT_CARD_ALL = "SELECT count(*) FROM cc_card";
$QUERY_COUNT_CARD_ACTIVED = "SELECT count(*) FROM cc_card WHERE status = 1";
$QUERY_COUNT_CARD_CANCELLED = "SELECT count(*) FROM cc_card WHERE status = 0";
$QUERY_COUNT_CARD_NEW = "SELECT count(*) FROM cc_card WHERE status = 2";
$QUERY_COUNT_CARD_WAITING = "SELECT count(*) FROM cc_card WHERE status = 3";
$QUERY_COUNT_CARD_RESERVED = "SELECT count(*) FROM cc_card WHERE status = 4";
$QUERY_COUNT_CARD_EXPIRED = "SELECT count(*) FROM cc_card WHERE status = 5";
$QUERY_COUNT_CARD_SUSPENDED = "SELECT count(*) FROM cc_card WHERE status = 6 OR status = 7";

$table = new Table('cc_card', '*');
$result = $table->SQLExec($DBHandle, $QUERY_COUNT_CARD_ALL);
$result_count_all = $result[0][0];
$result = $table->SQLExec($DBHandle, $QUERY_COUNT_CARD_ACTIVED);
$result_count_actived = $result[0][0];
$result = $table->SQLExec($DBHandle, $QUERY_COUNT_CARD_CANCELLED);
$result_count_cancelled = $result[0][0];
$result = $table->SQLExec($DBHandle, $QUERY_COUNT_CARD_NEW);
$result_count_new = $result[0][0];
$result = $table->SQLExec($DBHandle, $QUERY_COUNT_CARD_WAITING);
$result_count_waiting = $result[0][0];
$result = $table->SQLExec($DBHandle, $QUERY_COUNT_CARD_RESERVED);
$result_count_reserved = $result[0][0];
$result = $table->SQLExec($DBHandle, $QUERY_COUNT_CARD_EXPIRED);
$result_count_expired = $result[0][0];
$result = $table->SQLExec($DBHandle, $QUERY_COUNT_CARD_SUSPENDED);
$result_count_suspended = $result[0][0];
?>

<?php echo gettext("Total Number of Accounts ");?>&nbsp;:&nbsp; <font style="color:#EE6564;" > <?php echo $result_count_all; ?> </font> <br/>
<?php if($result_count_actived>0){ ?>	
  <?php echo gettext("Total Number of Active Accounts ");?>&nbsp;:&nbsp;<?php echo $result_count_actived; ?><br/>
<?php } ?>
<?php if($result_count_cancelled>0){ ?>
	<?php echo gettext("Cancelled Accounts ");?>&nbsp;:&nbsp;<?php echo $result_count_cancelled; ?><br/>
<?php } ?>
<?php if($result_count_new>0){ ?> 	
	<?php echo gettext("New Accounts ");?>&nbsp;:&nbsp;<?php echo $result_count_new; ?><br/>
<?php } ?>
<?php if($result_count_waiting>0){ ?>
 	<?php echo gettext("Account not yet Activated");?>&nbsp;:&nbsp;<?php echo $result_count_waiting; ?><br/>
<?php } ?>
<?php if($result_count_reserved>0){ ?>
  	<?php echo gettext("Accounts Reserved");?>&nbsp;:&nbsp;<?php echo $result_count_reserved; ?><br/>
<?php } ?>
<?php if($result_count_expired>0){ ?>
	<?php echo gettext("Accounts Expired");?>&nbsp;:&nbsp;<?php echo $result_count_expired; ?><br/>
<?php } ?>
<?php if($result_count_suspended>0){ ?>
	<?php echo gettext("Accounts Suspended ");?>&nbsp;:<?php echo $result_count_suspended; ?><br/>
<?php } ?>
