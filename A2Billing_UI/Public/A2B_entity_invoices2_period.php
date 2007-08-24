<?php
include ("../lib/defines.php");
include ("../lib/module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_list_invoice.inc");
include ("../lib/smarty.php");

if (! has_rights (ACX_INVOICING)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}



/***********************************************************************************/

$HD_Form -> setDBHandler (DbConnect());


$HD_Form -> init();

$form_action="list";
$list = $HD_Form -> perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
echo $CC_help_invoices_period;

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


// #### CREATE FORM OR LIST
//$HD_Form -> CV_TOPVIEWER = "menu";
if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];

?>
<br>
<form name="searchform" id="searchform" method="post" action="A2B_entity_invoices_period.php">
<table class="bar-status" width="85%" border="0" cellspacing="1" cellpadding="2" align="center">
			<tbody>
			<?php 	
				if (!isset($oncard)) {	//to avoid having 2 similar filters. $filtercard is set for exemple when viewing billed invoices per customers 
			?>
					<tr>
						<td align="left" class="bgcolor_004">
							<font class="fontstyle_003"> &nbsp;&nbsp;<?php echo gettext("SELECT CARDNUMBER");?> </font>
						</td>
						<td class="bgcolor_005" align="left">
							<INPUT TYPE="text" NAME="filtercustomer" value="<?php echo $filtercustomer?>" class="form_input_text">
							<a href="#" onclick="window.open('A2B_entity_card.php?popup_select=2&popup_formname=searchform&popup_fieldname=filtercustomer' , 'CardNumberSelection','scrollbars=1,width=550,height=330,top=20,left=100,scrollbars=1');">
								<img src="<?php echo Images_Path;?>/icon_arrow_orange.gif">
							</a>
						</td>
					</tr>
			<?php	} else { ?>
					<tr>
						<td align="left" class="bgcolor_004">
							<font class="fontstyle_003"> &nbsp;&nbsp;<?php echo gettext("CARDNUMBER");?> </font>
						</td>
						<td class="bgcolor_005" align="left">
							<input style="background-color: #CCCCCC;" readonly TYPE="text" NAME="oncard" value="<?php echo $oncard?>" class="form_input_text">
						</td>
					</tr>			
			<?php	} ?>
			<tr>
        		<td class="bgcolor_002" align="left">					 
					<font class="fontstyle_003"> &nbsp;&nbsp;<?php echo gettext("CREATION DATE");?></font>
				</td>
      			<td class="bgcolor_003" align="left">
					<table width="100%"><tr>
						<td class="fontstyle_searchoptions" width = "50%">
			  				<input type="checkbox" name="frommonth" value="true" <?php  if ($frommonth){ ?>checked<?php }?>>
							<?php echo gettext("From");?> : <select name="fromstatsmonth" class="form_input_select">
							<?php
								$monthname = array( gettext("January"), gettext("February"),gettext("March"), gettext("April"), gettext("May"), gettext("June"), gettext("July"), gettext("August"), gettext("September"), gettext("October"), gettext("November"), gettext("December"));
								$year_actual = date("Y");  	
								for ($i=$year_actual;$i >= $year_actual-1;$i--)
								{		   
								   if ($year_actual==$i){
									$monthnumber = date("n")-1; // Month number without lead 0.
								   }else{
									$monthnumber=11;
								   }		   
								   for ($j=$monthnumber;$j>=0;$j--){	
									$month_formated = sprintf("%02d",$j+1);
						   			if ($fromstatsmonth=="$i-$month_formated")	$selected="selected";
									else $selected="";
									echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";				
								   }
								}
							?>		
							<option value="0001-01"><?php echo gettext("January")."-0001"?></option>
							</select>
						</td>
						<td  class="fontstyle_searchoptions">
							<input type="checkbox" name="tomonth" value="true" <?php  if ($tomonth){ ?>checked<?php }?>> 
							<?php echo gettext("To");?> : <select name="tostatsmonth" class="form_input_select">
							<?php 	$year_actual = date("Y");  	
								for ($i=$year_actual;$i >= $year_actual-1;$i--)
								{
								   if ($year_actual==$i){
									$monthnumber = date("n")-1; // Month number without lead 0.
								   }else{
									$monthnumber=11;
								   }		   
								   for ($j=$monthnumber;$j>=0;$j--){	
									$month_formated = sprintf("%02d",$j+1);
						   			if ($tostatsmonth=="$i-$month_formated") $selected="selected";
									else $selected="";
									echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";				
								   }
								}
							?>
							</select>
						</td>
					</tr></table>
	  			</td>
    		</tr>
    		<tr>
    			<td align="left" class="bgcolor_004">
    				<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext('HAS TO COVER'); ?></font>
    			</td>
    			<td class="bgcolor_005">
    				<table width="100%"><tr>
    				<td  width="50%" class="fontstyle_searchoptions">
    					<input type="checkbox" name="inclusivecharges" <?php  if ($inclusivecharges){ ?>checked<?php }?>>
    					<?php echo gettext('Charges'); ?>
    				</td>
    				<td  class="fontstyle_searchoptions">
    					<input type="checkbox" name="inclusivecalls" <?php  if ($inclusivecalls){ ?>checked<?php }?>>
    					<?php echo gettext('Calls'); ?>			
    				</td>    					
    				</tr></table>
    			</td>
    		</tr>
    		<tr>
				<td align="left" class="bgcolor_002"> 
					<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext('INVOICE AMOUNT')?></font>
				</td>
				<td class="bgcolor_003">
					<select name="filtertotaloperator" class="form_input_select">
						<option value="any"	<?php if ($filtertotaloperator == "any") echo 'selected';?>>&nbsp;any&nbsp;</option>
						<option value="equal" <?php if ($filtertotaloperator == "equal") echo 'selected';?>>&nbsp; = &nbsp;</option>
						<option value="greater" <?php if ($filtertotaloperator == "greater") echo 'selected';?>>&nbsp; > &nbsp;</option>
						<option value="less" <?php if ($filtertotaloperator == "less") echo 'selected';?>>&nbsp; < &nbsp;</option>
						<option value="greaterthanequal" <?php if ($filtertotaloperator == "greaterthanequal") echo 'selected';?>>&nbsp; >= &nbsp;</option>
						<option value="lessthanequal" <?php if ($filtertotaloperator == "lessthanequal") echo 'selected'; ?>>&nbsp; <= &nbsp;</option>
					</select>
					<?php echo gettext("to"); ?>
					<input name="filtertotal" id="filtertotal" value="<?php echo $filtertotal?>"  class="form_input_text" style="width:60px; text-align:right;">
				</td>
			</tr>
			<tr>
				<td class="bgcolor_005" align="center" colspan="2">
					<input type="image"  name="image16" align="top" border="0" src="<?php echo Images_Path;?>/button-search.gif" />
	  			</td>
    		</tr>
    	</tbody>
    	</table>
    	<br>
    	<table class="bar-status" width="85%" border="0" cellspacing="1" cellpadding="2" align="center">
			<tr>
				<td class="bgcolor_004" align="left">
					<font class="fontstyle_003"> &nbsp;&nbsp;<?php echo gettext("TEMPLATE");?></font>
				</td>
				<td class="bgcolor_003" align="left">
					<select name="templatefile" class="form_input_select" >
						<option value="" <?php if ($templatefile == "") echo 'selected';?>><?php echo gettext('Invoice default'); ?></option>
						<?php
							$dir = opendir ('./templates/default/'.$A2B->config['global']['invoice_template_path']);
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
			<tr>
 				<td align="left" valign="top" class="bgcolor_002">
					<font class="fontstyle_003"> &nbsp;&nbsp;<?php echo gettext("VIEW AS");?> </font>
				</td>
				<td class="bgcolor_005" align="left">
					<?php echo gettext("HTML");?> 
					<input type="radio" NAME="exporttype" value="html" <?php if((!isset($exporttype))||($exporttype=="html")){?>checked<?php }?>>
					<?php echo gettext("PDF");?> 
					<input type="radio" NAME="exporttype" value="pdf" <?php if($exporttype=="pdf"){?>checked<?php }?>>					
				</td>
    		</tr>
		</table>
</form>
<?php

$HD_Form -> create_form ($form_action, $list, $id=null);

?>
