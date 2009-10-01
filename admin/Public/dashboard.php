<?php
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
	
	if($position=="LEFT"){
		$idx = count($left);
		$left[$idx] = array();
		$left[$idx]["title"] = $title;
		$left[$idx]["links"] = $links;
	}elseif ($position=="CENTER"){
		$idx = count($center);
		$center[$idx] = array();
		$center[$idx]["title"] = $title;
		$center[$idx]["links"] = $links;
	}elseif ($position=="RIGHT"){
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

