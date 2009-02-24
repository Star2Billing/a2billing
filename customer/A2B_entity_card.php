<?php
include ("./lib/customer.defines.php");
include ("./lib/customer.module.access.php");
include ("./lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_card.inc");
include ("./lib/customer.smarty.php");

if (! has_rights (ACX_ACCESS)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();


if ($id!="" || !is_null($id)){
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;

if ($message != "success") {
	$list = $HD_Form -> perform_action($form_action);
}

// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
echo $CC_help_card;


// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

if($message == "success") {
	
?>
<table width="50%" align="center">
<tr height="100px">
<td align="center"><?php echo gettext("Your personal information is updated successfully.")?></td>
</tr>
</table>

<?php
} else {
	$HD_Form -> create_form ($form_action, $list, $id=null) ;
}

// #### FOOTER SECTION
$smarty->display('footer.tpl');



