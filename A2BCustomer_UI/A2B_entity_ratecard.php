<?php
include ("./lib/customer.defines.php");
include ("./lib/customer.module.access.php");
include ("./lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_ratecard.inc");
include ("./lib/customer.smarty.php");


if (! has_rights (ACX_ACCESS)){
	   Header ("HTTP/1.0 401 Unauthorized");
	   Header ("Location: PP_error.php?c=accessdenied");
	   die();
}

$ratesort = $_GET['ratesort'];
//if (strlen($ratesort)==0) $ratesort='A';
/***********************************************************************************/

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();

if (strlen($ratesort)==1) $HD_Form -> FG_TABLE_CLAUSE .= " AND (SUBSTRING(cc_ratecard.destination,1,1)='".strtolower($ratesort)."' OR SUBSTRING(cc_ratecard.destination,1,1)='".$ratesort."')"; // sort by first letter

$FG_LIMITE_DISPLAY=10;
if (isset($mydisplaylimit) && (is_numeric($mydisplaylimit) || ($mydisplaylimit=='ALL'))){
	if ($mydisplaylimit=='ALL'){
		$FG_LIMITE_DISPLAY=5000;
	}else{
		$FG_LIMITE_DISPLAY=$mydisplaylimit;
	}
}

if ($id!="" || !is_null($id)){
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;


if ( ($form_action == "list") &&  ($HD_Form->FG_FILTER_SEARCH_FORM) && ($_POST['posted_search'] == 1 )){
	$HD_Form->FG_TABLE_CLAUSE = "idtariffplan='$mytariff_id'";
}

$list = $HD_Form -> perform_action($form_action);


// #### HEADER SECTION
$smarty->display('main.tpl');



// #### HELP SECTION
if ($form_action == 'list')
{
    echo $CC_help_ratecard.'';
}

$HD_Form -> FG_TABLE_CLAUSE = "cc_tariffplan.id = cc_tariffgroup_plan.idtariffplan AND cc_tariffgroup_plan.idtariffgroup = '".$_SESSION["tariff"]."'";

if ($form_action == "list" ) $HD_Form -> create_select_form_client($HD_Form -> FG_TABLE_CLAUSE);

$HD_Form -> FG_TABLE_CLAUSE .= " cc_tariffgroup_plan.idtariffplan=cc_ratecard.idtariffplan   AND cc_ratecard.idtariffplan='".$_SESSION["mytariff_id"]."' AND cc_tariffgroup_plan.idtariffgroup = '".$_SESSION["tariff"]."'";

 // #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

?>
    <table width="75%" border=0 cellspacing=1 cellpadding=3 bgcolor="#000033" align="center">
        <tr>
       <td bgcolor="#000033" width="100%" valign="top" align="center" class="bb2">
	   		  <a href="A2B_entity_ratecard.php?form_action=list&ratesort="><?php echo gettext("NONE")?></a>
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=A">A</a>
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=B">B</a>         
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=C">C</a>         
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=D">D</a>         
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=E">E</a>
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=F">F</a>         
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=G">G</a>         
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=H">H</a>         
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=I">I</a>
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=J">J</a>         
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=K">K</a>         
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=L">L</a>         
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=M">M</a>
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=N">N</a>         
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=O">O</a>         
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=P">P</a>         
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=Q">Q</a>
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=R">R</a>         
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=S">S</a>         
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=T">T</a>         
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=U">U</a>
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=V">V</a>         
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=W">W</a>         
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=X">X</a>         
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=Y">Y</a>
              <a href="A2B_entity_ratecard.php?form_action=list&ratesort=Z">Z</a>         
       </td>
        </tr>
    </table>
<?php   

// #### CREATE FORM OR LIST
//$HD_Form -> CV_TOPVIEWER = "menu";
if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];

$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### CREATE SEARCH FORM
if ($form_action == "list"){
	$HD_Form -> create_search_form();
}


// #### FOOTER SECTION
$smarty->display('footer.tpl');

?>
