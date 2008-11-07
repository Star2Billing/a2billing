<?php
include ("../lib/agent.defines.php");
include ("../lib/agent.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_moneysituation_details.inc");
include ("../lib/agent.smarty.php");

if (! has_rights (ACX_BILLING)){ 
	   Header ("HTTP/1.0 401 Unauthorized");
	   Header ("Location: PP_error.php?c=accessdenied");	   
	   die();	   
}

/***********************************************************************************/

$HD_Form -> setDBHandler (DbConnect());


	if(isset($id) && !empty($id)&& $id>0){
		if ($type=='payment'){
			$table_agent_security = new Table("cc_logpayment,cc_card", " cc_card.id_agent");
			$clause_agent_security = "cc_card.id= ".$id." AND cc_card.id=cc_logpayment.card_id";
		}else{
			$table_agent_security = new Table("cc_logrefill,cc_card", " cc_card.id_agent");
			$clause_agent_security = "cc_card.id= ".$id." AND cc_card.id=cc_logrefill.card_id";
			
		}
		$result_security= $table_agent_security -> Get_list ($HD_Form -> DBHandle, $clause_agent_security, null, null, null, null, null, null);
		if ( $result_security[0][0] !=$_SESSION['agent_id'] ) { 
			Header ("HTTP/1.0 401 Unauthorized");
			//Header ("Location: PP_error.php?c=accessdenied");	   
			die();
		}
	}

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
// No needed echo '<br><br>'.$CC_help_trunk_list;



// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


// #### CREATE FORM OR LIST
//$HD_Form -> CV_TOPVIEWER = "menu";
if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];

$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### FOOTER SECTION
// include("PP_footer.php");

?>
