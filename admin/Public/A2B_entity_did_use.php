<?php
include_once ("../lib/admin.defines.php");
include_once ("../lib/admin.module.access.php");
include_once ("../lib/Form/Class.FormHandler.inc.php");
include_once ("../lib/admin.smarty.php");
include_once ("./form_data/FG_var_diduse.inc");



if (! has_rights (ACX_DID)){ 
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


if (!isset($form_action))  $form_action="list"; 
if (!isset($action)) $action = $form_action;




$smarty->display('main.tpl');



// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

switch ($actionbtn){
	case "release_did":
	echo $CC_help_release_did;
	?>
	<FORM action=<?php echo $_SERVER['PHP_SELF']?> id=form1 method=post name=form1>
		<INPUT type="hidden" name="did" value="<?php echo $did?>">
		<INPUT type="hidden" name="atmenu" value="<?php echo $atmenu?>">
		<INPUT type="hidden" name="actionbtn" value="ask_release"><br><br>
		<br><br>
		<TABLE cellspacing="0" class="delform_table5">
			<tr>
				<td width="434" class="text_azul"><?php echo gettext("If you really want release this DID , Click on the 	release button.")?>
				</td>
			</tr>
			<tr height="2">
				<td style="border-bottom: medium dotted rgb(255, 119, 102);">&nbsp; </td>
			</tr>
			<tr>
		    		<td width="190" align="right" class="text"><INPUT title="<?php echo gettext("Release the DID ");?> " alt="<?php echo gettext("Release the DID "); ?>" hspace=2 id=submit22 name=submit22 src="<?php echo Images_Path_Main;?>/btn_release_did_94x20.gif" type="image"></td>
			</tr>
		</TABLE>
	</FORM>
<?php
	break;
	case "ask_release":
		$instance_table = Table::getInstance();
		$QUERY = "UPDATE cc_did set iduser = 0 ,reserved=0 where id=$did" ;
		$result = $instance_table -> SQLExec ($HD_Form -> DBHandle, $QUERY, 0);

		$QUERY = "UPDATE cc_did_use set releasedate = now() where id_did =$did and activated = 1" ;
		$result = $instance_table -> SQLExec ($HD_Form -> DBHandle, $QUERY, 0);

		$QUERY = "insert into cc_did_use (activated, id_did) values ('0','".$did."')";
		$result = $instance_table -> SQLExec ($HD_Form -> DBHandle, $QUERY, 0);

		$QUERY = "delete FROM cc_did_destination where id_cc_did =".$did;
		$result = $instance_table -> SQLExec ($HD_Form -> DBHandle, $QUERY, 0);

	break;
}

if (!isset($actionbtn) || $actionbtn=="ask_release"){

echo $CC_help_list_did_use;
if (!isset($inuse) || $inuse=="")$inuse=1;
/*<!-- ** ** ** ** ** Part for the research ** ** ** ** ** -->*/?>
	<center>
	<FORM METHOD=POST name="myForm" ACTION="<?php echo $PHP_SELF?>?order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>">
	<INPUT TYPE="hidden" NAME="posted" value=1>
	<INPUT TYPE="hidden" NAME="current_page" value=0>
		<table class="bar-status" width="85%" border="0" cellspacing="1" cellpadding="2" align="center">
		<tbody>
		<tr>
			<td class="bgcolor_001" align="left" colspan="2">
				<?php echo gettext("Enter the DID id");?>: <INPUT TYPE="text" name="did" value="<?php echo $did?>" class="form_input_text">
			</td>
		</tr>			
		<tr>
		<tr>
			<td class="bgcolor_004" align="left" ><font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("Options");?></font>
			</td>
			<td class="bgcolor_005" align="center"><div align="left">
			<b><?php echo gettext("Show")?>:<?php echo gettext("Dids in use")?> 
				<input name="inuse" type=radio value=1 <?php if($inuse){?>checked<?php } ?>> 
				<?php echo gettext("All Dids")?> <input name="inuse" type="radio" value=0 <?php if (!$inuse){?>checked<?php } ?>>

					
			</td>
		</tr>
		<tr>
        		<td class="bgcolor_004" align="left" > 
			</td>
			<td class="bgcolor_005" align="center" >
				<input type="image"  name="image16" align="top" border="0" src="<?php echo Images_Path;?>/button-search.gif" />
	  		</td>
    		</tr>
		</tbody></table>
	</FORM>
</center>
<?php
// #### CREATE FORM OR LIST
//$HD_Form -> CV_TOPVIEWER = "menu";
if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];

$list = $HD_Form -> perform_action($form_action);

$HD_Form -> create_form ($form_action, $list, $id=null) ;

}
$smarty->display('footer.tpl');


?>
