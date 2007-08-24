<?php
include ("../lib/defines.php");
include ("../lib/module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_card_money.inc");
include ("../lib/smarty.php");

if (! has_rights (ACX_INVOICING)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

getpost_ifset(array(
	'invoicetype', // ('billed', 'unbilled')	(already got by FG_var_card_money.inc)
	'exporttype',  // ('pdf', 'html')			to see the outstanding
	'billcalls',   // ('on', '') 				show calls in outstanding 
	'billcharges', // ('on', '') 				show charges in outstanding
	'templatefile' // file						Template to show unbilled invoice
	));
	
/***********************************************************************************/

	
$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;

$list = $HD_Form -> perform_action($form_action);


// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
if ($invoicetype == 'unbilled') {
	echo $CC_help_invoices_unbilled;
}
else
{
	echo $CC_help_invoices_card;
}

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


if ($invoicetype == 'unbilled') {
	// #### MENU SECTION
	?>
	<br>
	<form name="UnbilledForm" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
	<input type="hidden" name="invoicetype" value="unbilled" />
		<table align="center" class="bar-status" border="0" width="55%">
			<tbody>
				<tr>
	 				<td align="left" valign="top" class="bgcolor_004">
						<font class="fontstyle_003"> &nbsp;&nbsp;<?php echo gettext("VIEW AS");?> </font>
					</td>
					<td class="bgcolor_005" align="left">
						<?php echo gettext("HTML");?> 
						<input type="radio" NAME="exporttype" value="html" <?php if((!isset($exporttype))||($exporttype=="html")){?>checked<?php }?>>
						<?php echo gettext("PDF");?> 
						<input type="radio" NAME="exporttype" value="pdf" <?php if($exporttype=="pdf"){?>checked<?php }?>>					
					</td>
	    		</tr>
	    		<tr>
	    			<td align="left" class="bgcolor_004">
	    				<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext('SHOW'); ?></font>
	    			</td>
	    			<td class="bgcolor_005">
	    				<table width="100%"><tr>
		    				<td  width="50%" class="fontstyle_searchoptions">
		    					<input type="checkbox" name="billcharges" <?php  if ($billcharges){ ?>checked<?php }?>>
		    					<?php echo gettext('Charges'); ?>
		    				</td>
		    				<td  class="fontstyle_searchoptions">
		    					<input type="checkbox" name="billcalls" <?php  if ($billcalls){ ?>checked<?php }?>>
		    					<?php echo gettext('Calls'); ?>			
		    				</td>    					
	    				</tr></table>
	    			</td>
    			</tr>
    			<tr>
					<td class="bgcolor_004" align="left">
						<font class="fontstyle_003"> &nbsp;&nbsp;<?php echo gettext("TEMPLATE");?></font>
					</td>
					<td class="bgcolor_003" align="left">
						<select name="templatefile" class="form_input_select" >
							<option value="" <?php if ($templatefile == "") echo 'selected';?>><?php echo gettext('Customer default'); ?></option>
							<?php
								$dir = opendir ('./templates/default/'.$A2B->config['global']['outstanding_template_path']);
						        while (false !== ($file = readdir($dir))) {
						                if (strpos($file, '.tpl',1)) {
						                    echo "<option value=\"$file\" ".($templatefile == $file? 'selected ':'').'> '.substr($file,0,-4).' </option>'; 
						                }
						        }
						        closedir($dir);
							?>
						</select>				
					</td>
				</tr>
			</tbody>
		</table>
	</form>
	<br>
	<?php
}

// #### CREATE FORM OR LIST
//$HD_Form -> CV_TOPVIEWER = "menu";
if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];

$HD_Form -> create_form ($form_action, $list, $id=null) ;

?>
