<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");

if (! has_rights (ACX_INVOICING)){ 
	   Header ("HTTP/1.0 401 Unauthorized");
	   Header ("Location: PP_error.php?c=accessdenied");	   
	   die();	   
}

getpost_ifset(array('id'));

$DBHandle=DbConnect();
if(!empty($id) && is_numeric($id)){
	$instance_table_invoice = new Table("cc_invoice");
	$param_update_invoice = "status = '1'";
	$clause_update_invoice = " id ='$id'";
	$instance_table_invoice-> Update_table ($DBHandle, $param_update_invoice, $clause_update_invoice, $func_table = null);
		
}

?>

