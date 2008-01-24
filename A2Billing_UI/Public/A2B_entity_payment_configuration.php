<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_payment_configuration.inc");
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
echo $CC_help_list_payment_methods;

// #### PAYMENT METHOD SECTION
echo $PAYMENT_METHOD;

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


// #### CREATE FORM OR LIST
//$HD_Form -> CV_TOPVIEWER = "menu";
if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];

$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### FOOTER SECTION
$smarty->display('footer.tpl');




?>
