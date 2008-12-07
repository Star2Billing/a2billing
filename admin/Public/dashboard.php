<?php
include ("../lib/admin.defines.php");
include_once ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");


if(!$A2B->config["dashboard"]["dashboard_enabled"]){
	Header ("Location: PP_intro.php");	 
}


$smarty->display('main.tpl');


?>

<table align="center" width="100%" >
	<tr>
		<td width="33%" valign="top">
		  <div class="dashbox">
		   
		  	<div class="dashtitle" >
		  	<?php echo gettext("INFO CUSTOMERS");?> 
		  	</div>
		  	<?php include ("./modules/customers_numbers.php"); ?>
		  <br/>
		  	<?php include ("./modules/customers_lastmonth.php"); ?>
		  </div>
		</td>
		<td width="33%" valign="top">
		  <div class="dashbox">
		   
		  	<div class="dashtitle" >
		  	<?php echo gettext("INFO REFILLS");?> 
		  	</div>
		  	<?php include ("./modules/refills_lastmonth.php"); ?>
		  </div>
		   <br/>
		<div class="dashbox">
		   
		  	<div class="dashtitle" >
		  	<?php echo gettext("INFO CALLS");?> 
		  	</div>
		  	<?php include ("./modules/calls_counts.php"); ?>
		  	 <br/>
		  	<?php include ("./modules/calls_lastmonth.php"); ?>
		  </div>
		</td>
		<td width="33%" valign="top">
		     <div class="dashbox">
		  	<div class="dashtitle" >
		  	<?php echo gettext("INFO PAYMENTS");?> 
		  	</div>
		  	<?php include ("./modules/payments_lastmonth.php"); ?>
		  </div>
		</td>
	</tr>

</table>

