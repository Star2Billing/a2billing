<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_receipt.inc");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_INVOICING)){ 
	   Header ("HTTP/1.0 401 Unauthorized");
	   Header ("Location: PP_error.php?c=accessdenied");	   
	   die();	   
}


getpost_ifset(array('id','action'));

$DBHandle=DbConnect();

if($action=="lock"){
	if(!empty($id) && is_numeric($id)){
		$instance_table_invoice = new Table("cc_receipt");
		$param_update_invoice = "status = '1'";
		$clause_update_invoice = " id ='$id'";
		$instance_table_invoice-> Update_table ($DBHandle, $param_update_invoice, $clause_update_invoice, $func_table = null);
	}
die();
}

/***********************************************************************************/

$HD_Form -> setDBHandler ($DBHandle);


$HD_Form -> init();


if ($id!="" || !is_null($id)){	
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);	
}


if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;


$list = $HD_Form -> perform_action($form_action);



// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
echo $CC_help_view_receipt;



// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


// #### CREATE FORM OR LIST
//$HD_Form -> CV_TOPVIEWER = "menu";
if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];

$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### FOOTER SECTION
$smarty->display('footer.tpl');

?>
<script type="text/javascript">	
$(document).ready(function () {
	$('.lock').click(function () {
			$.get("A2B_entity_receipt.php", { id: ""+ this.id, action: "lock" },
				  function(data){
				    location.reload(true);
				  });

	        });
	
});
</script>

