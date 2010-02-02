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
include_once ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");


if (!has_rights(ACX_DASHBOARD)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

$left = array();
$center = array();
$right = array();

function put_dislay($position,$title,$links)
{   
	global $left;
	global $center;
	global $right;
	
	if ($position=="LEFT") {
		$idx = count($left);
		$left[$idx] = array();
		$left[$idx]["title"] = $title;
		$left[$idx]["links"] = $links;
	} elseif ($position=="CENTER") {
		$idx = count($center);
		$center[$idx] = array();
		$center[$idx]["title"] = $title;
		$center[$idx]["links"] = $links;
	} elseif ($position=="RIGHT") {
		$idx = count($right);
		$right[$idx] = array();
		$right[$idx]["title"] = $title;
		$right[$idx]["links"] = $links;
	}
}

if( !empty($A2B->config["dashboard"]["customer_info_enabled"]) && $A2B->config["dashboard"]["customer_info_enabled"]!="NONE"){
	put_dislay($A2B->config["dashboard"]["customer_info_enabled"],gettext("ACCOUNTS INFO"),array("./modules/customers_numbers.php","./modules/customers_lastmonth.php"));
}
if( !empty($A2B->config["dashboard"]["refill_info_enabled"]) && $A2B->config["dashboard"]["refill_info_enabled"]!="NONE"){
	put_dislay($A2B->config["dashboard"]["refill_info_enabled"],gettext("REFILLS INFO"),array("./modules/refills_lastmonth.php"));
}
if( !empty($A2B->config["dashboard"]["payment_info_enabled"]) && $A2B->config["dashboard"]["refill_info_enabled"]!="NONE"){
	put_dislay($A2B->config["dashboard"]["payment_info_enabled"],gettext("PAYMENTS INFO"),array("./modules/payments_lastmonth.php"));
}
if( !empty($A2B->config["dashboard"]["call_info_enabled"]) && $A2B->config["dashboard"]["refill_info_enabled"]!="NONE"){
	put_dislay($A2B->config["dashboard"]["call_info_enabled"],gettext("CALLS INFO TODAY"),array("./modules/calls_counts.php","./modules/calls_lastmonth.php"));
}
if( !empty($A2B->config["dashboard"]["system_info_enable"]) && $A2B->config["dashboard"]["system_info_enable"]!="NONE"){
	put_dislay($A2B->config["dashboard"]["system_info_enable"],gettext("SYSTEM INFO"),array("./modules/system_info.php"));
}
if( !empty($A2B->config["dashboard"]["news_enabled"]) && $A2B->config["dashboard"]["news_enabled"]!="NONE"){
	put_dislay($A2B->config["dashboard"]["news_enabled"],gettext("NEWS"),array("./modules/news.php"));
}

$smarty->display('main.tpl');

?>
<center>

<table align="center" width="100%">
	<tr>
		<td width="33%" valign="top" class="tableBodyRight">
		  <?php for($i_left=0;$i_left<count($left);$i_left++){ ?>
		  <div class="dashbox">
		  	<div class="dashtitle" >
		  	 <?php echo $left[$i_left]["title"]; ?>
		  	</div>
	  			<?php for($j_left=0;$j_left<count($left[$i_left]["links"]);$j_left++){ 
	  			  include ($left[$i_left]["links"][$j_left]);
	  				?>
	 			 <br/>
	 		 	<?php } ?>
		  </div>
		   <br/>
		  <?php } ?>
		</td>
		
		<td width="33%" valign="top"  class="tableBodyRight">
		  <?php for($i_center=0;$i_center<count($center);$i_center++){ ?>
		  <div class="dashbox">
		  	<div class="dashtitle" >
		  	 <?php echo $center[$i_center]["title"]; ?>
		  	</div>
	  			<?php for($j_center=0;$j_center<count($center[$i_center]["links"]);$j_center++){ 
	  			  include ($center[$i_center]["links"][$j_center]);
	  				?>
	 			 <br/>
	 		 	<?php } ?>
		  </div>
		   <br/>
		  <?php } ?>
		</td>
		
		<td width="33%" valign="top"  class="tableBodyRight">
		  <?php for($i_right=0;$i_right<count($right);$i_right++){ ?>
		  <div class="dashbox">
		  	<div class="dashtitle" >
		  	 <?php echo $right[$i_right]["title"]; ?>
		  	</div>
	  			<?php for($j_right=0;$j_right<count($right[$i_right]["links"]);$j_right++){ 
	  			  include ($right[$i_right]["links"][$j_right]);
	  				?>
	 			 <br/>
	 		 	<?php } ?>
		  </div>
		   <br/>
		  <?php } ?>
		</td>
	</tr>
</table>
</center>

<?php

$smarty->display('footer.tpl');

