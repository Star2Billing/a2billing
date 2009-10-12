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


include ("./lib/customer.defines.php");
include ("./lib/customer.module.access.php");
include ("./lib/Form/Class.FormHandler.inc.php");
include ("./lib/epayment/includes/general.php");
include ("./lib/epayment/includes/configure.php");
include ("./lib/epayment/includes/html_output.php");
$popup_select = 1;
include ("./lib/customer.smarty.php");
//include("./lib/epayment/includes/PP_header.php");


getpost_ifset(array('errcode'));

// #### HEADER SECTION
$smarty->display( 'main.tpl');
?>

<br>
<br>
<table width=80% align=center class="infoBox">
<tr height="15">
    <td colspan=2 class="infoBoxHeading">&nbsp;<?php echo gettext("Message")?></td>
</tr>
<tr>
    <td width=50%>&nbsp;</td>
    <td width=50%>&nbsp;</td>
</tr>
<tr>
    <td align=center colspan=2>
	<?php echo gettext("Thank you for your purchase")?>
	&nbsp;
    <?php
      switch($errcode)
      {
          case -2:
		  	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__." ERROR TRANSACTION FAILED");
            echo gettext("We are sorry your transaction is failed. Please try later or check your provided information.");
          break;
          case -1:
		  	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__." ERROR TRANSACTION DENIED");
            echo gettext("We are sorry your transaction is denied. Please try later or check your provided information.");
          break;
          case 0:
		  	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__." ERROR TRANSACTION PENDING");
            echo gettext("We are sorry your transaction is pending.");
          break;
          case 1:
		  	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__." ERROR TRANSACTION INPROGRESS");
            echo gettext("Your transaction is in progress.");
          break;
          case 2:
		  	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__." TRANSACTION SUCCESSFUL");
            echo gettext("Your transaction was successful.");
          break;
      }
    ?>  &nbsp;
    </td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>
<tr>
    <td align=center colspan=2><a href="<?php echo tep_href_link("agentinfo.php","", 'SSL', false, false);?>">[Home]</a></td>
</tr>

</table>
<?php 
// #### FOOTER SECTION
$smarty->display( 'footer.tpl');
?>