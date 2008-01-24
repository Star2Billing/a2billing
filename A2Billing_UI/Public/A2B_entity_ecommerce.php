<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_ecommerce.inc");
include ("../lib/smarty.php");

if (! has_rights (ACX_BILLING)){ 
	   Header ("HTTP/1.0 401 Unauthorized");
	   Header ("Location: PP_error.php?c=accessdenied");	   
	   die();	   
}



/***********************************************************************************/

$HD_Form -> setDBHandler (DbConnect());


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
echo $CC_help_ecommerce;



// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


// #### CREATE FORM OR LIST
//$HD_Form -> CV_TOPVIEWER = "menu";
if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];

$HD_Form -> create_form ($form_action, $list, $id=null) ;

$_SESSION["pr_sql_export_entity_card"]= "SELECT * FROM $HD_Form->FG_TABLE_NAME";
if (strlen($HD_Form->FG_TABLE_CLAUSE)>1) 
	$_SESSION["pr_sql_export_entity_card"] .= " WHERE $HD_Form->FG_TABLE_CLAUSE ";
if (!is_null ($HD_Form->FG_ORDER) && ($HD_Form->FG_ORDER!='') && !is_null ($HD_Form->FG_SENS) && ($HD_Form->FG_SENS!='')) 
	$_SESSION["pr_sql_export_entity_card"].= " ORDER BY $HD_Form->FG_ORDER $HD_Form->FG_SENS";


// #### FOOTER SECTION
$smarty->display('footer.tpl');
?>
