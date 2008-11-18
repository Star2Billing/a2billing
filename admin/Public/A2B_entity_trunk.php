<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_trunk.inc");
include ("../lib/admin.smarty.php");


if (! has_rights (ACX_TRUNK)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

getpost_ifset(array('popup_select', 'popup_formname', 'popup_fieldname'));


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

if ($popup_select)
{
?>
	<SCRIPT LANGUAGE="javascript">
	<!-- Begin
	function sendValue(selvalue) {
        	window.opener.document.<?php echo $popup_formname ?>.<?php echo $popup_fieldname ?>.value = selvalue;
		window.close();
	}
	// End -->
	</script>
<?php
}


// #### HELP SECTION
if ($form_action=='list') { 
	if (!$popup_select) echo $CC_help_trunk_list;
} else {
	echo $CC_help_trunk_edit;
}

echo $CALL_LABS;

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


// #### CREATE FORM OR LIST
//$HD_Form -> CV_TOPVIEWER = "menu";
if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];

$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### FOOTER SECTION
if (!$popup_select) $smarty->display('footer.tpl');
