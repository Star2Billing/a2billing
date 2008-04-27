<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_currencies.inc");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_BILLING)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

getpost_ifset(array('updatecurrency'));

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();


/********************************* BATCH UPDATE CURRENCY TABLE ***********************************/
$A2B -> DBHandle = $HD_Form -> DBHandle;
if ($updatecurrency == 1){
	$instance_table = new Table();
	$A2B -> set_instance_table ($instance_table);
	$return = $A2B -> currencies_update_yahoo($A2B -> DBHandle, $A2B -> instance_table);
	$update_msg = '<center><font color="green"><b>'.$return.'</b></font></center>';
}
/***********************************************************************************/


if ($id!="" || !is_null($id)){	
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);	
}


if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;


$list = $HD_Form -> perform_action($form_action);



// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
echo $CC_help_currency;
?>
<table align="center" border="0" width="65%"  cellspacing="1" cellpadding="2">
	<form name="updateForm" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
	<INPUT type="hidden" name="updatecurrency" value="1">
	<tr>
	  <td align="center"  class="bgcolor_001">
		&nbsp;<?php echo gettext("THE CURRENCY LIST IS BASED FROM YAHOO FINANCE"); ?>&nbsp;: 
			<input class="form_input_button"  value=" <?php echo gettext("CLICK HERE TO UPDATE NOW");?>  " type="submit">
		</td>
	</tr>
	</form>
</table>

<?php

if (isset($update_msg) && strlen($update_msg)>0) echo $update_msg; 

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


// #### CREATE FORM OR LIST
//$HD_Form -> CV_TOPVIEWER = "menu";
if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];

$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### FOOTER SECTION
$smarty->display('footer.tpl');




?>
