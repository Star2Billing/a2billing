<?php
include ("../lib/agent.defines.php");
include ("../lib/agent.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_moneysituation.inc");
include ("../lib/agent.smarty.php");

if (! has_rights (ACX_BILLING)){ 
	   Header ("HTTP/1.0 401 Unauthorized");
	   Header ("Location: PP_error.php?c=accessdenied");	   
	   die();	   
}

?>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

//-->
</script>

<?php

/***********************************************************************************/

$HD_Form -> setDBHandler (DbConnect());


$HD_Form -> init();


if ($id!='' || !is_null($id)){	
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);	
}


if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;


$list = $HD_Form -> perform_action($form_action);



// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
echo $CC_help_money_situation;



// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


// #### CREATE FORM OR LIST
//$HD_Form -> CV_TOPVIEWER = "menu";
if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];

$HD_Form -> create_form ($form_action, $list, $id=null) ;
//change Clause for agent filter 


// SELECT ROUND(SUM(credit)) from cc_card ;
$instance_table = new Table("cc_card LEFT JOIN cc_agent_cardgroup ON cc_card.id_group=cc_agent_cardgroup.id_card_group", "ROUND(SUM(credit))");
$list1 = $instance_table -> Get_list ($HD_Form -> DBHandle,"cc_agent_cardgroup.id_agent=".$_SESSION['agent_id'], null, null, null, null, null, null);
// SELECT SUM(t1.credit) from  cc_logrefill as t1, cc_card as t2 where t1.card_id = t2.id;
$instance_table = new Table("cc_logrefill as t1, cc_card as t2  LEFT JOIN cc_agent_cardgroup ON t2.id_group=cc_agent_cardgroup.id_card_group", "SUM(t1.credit)");
$list2 = $instance_table -> Get_list ($HD_Form -> DBHandle, "t1.card_id = t2.id AND cc_agent_cardgroup.id_agent=".$_SESSION['agent_id'], null, null, null, null, null, null);
// SELECT SUM(payment) from cc_logpayment as t1 ,cc_card as t2 where t1.card_id=t2.id;
$instance_table = new Table("cc_logpayment as t1 ,cc_card as t2 LEFT JOIN cc_agent_cardgroup ON t2.id_group=cc_agent_cardgroup.id_card_group", "SUM(payment)");
$list3 = $instance_table -> Get_list ($HD_Form -> DBHandle, "t1.card_id=t2.id AND cc_agent_cardgroup.id_agent=".$_SESSION['agent_id'] , null, null, null, null, null, null);
$list4 = $list2[0][0] - $list3[0][0];
?>
<br/>
<table border="1" cellpadding="4" cellspacing="2" width="90%" align="center" class="bgcolor_017" >		
	<tr>
		<td>		
			<table border="2" cellpadding="3" cellspacing="5" width="450" align="right" class="bgcolor_018">		
				<tr class="form_head">                   					
					<td width="20%" align="center" class="tableBodyRight" style="padding: 2px;"><strong><?php echo gettext("TOTAL CREDIT");?></strong></td>
					<td width="20%" align="center" class="tableBodyRight" style="padding: 2px;"><strong><?php echo gettext("TOTAL REFILL");?></strong></td>
					<td width="20%" align="center" class="tableBodyRight" style="padding: 2px;"><strong><?php echo gettext("TOTAL PAYMENT");?></strong></td>
					<td width="20%" align="center" class="tableBodyRight" style="padding: 2px;"><strong><?php echo gettext("TOTAL TOPAY");?></strong></td>
				</tr>
				<tr>
					<td valign="top" align="center" class="tableBody" bgcolor="white"><b><?php echo $list1[0][0]?></b></td>
					<td valign="top" align="center" class="tableBody" bgcolor="white"><b><?php echo $list2[0][0]?></b></td>
					<td valign="top" align="center" class="tableBody" bgcolor="#DD4444"><b><?php echo $list3[0][0]?></b></td>
					<td valign="top" align="center" class="tableBody" bgcolor="#DDDDDD"><b><?php echo $list4?></b></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
	<br></br>
<?php
// #### FOOTER SECTION
$smarty->display('footer.tpl');

?>
