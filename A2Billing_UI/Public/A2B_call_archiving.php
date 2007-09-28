<?php
include_once(dirname(__FILE__) . "/../lib/defines.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include_once(dirname(__FILE__) . "/../lib/module.access.php");
include ("../lib/smarty.php");


if (! has_rights (ACX_CALL_REPORT)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();
}


getpost_ifset(array('current_page', 'fromstatsday_sday', 'fromstatsmonth_sday', 'days_compare', 'min_call', 'posted',  'dsttype', 'srctype', 'clidtype', 'channel', 'resulttype', 'stitle', 'atmenu', 'current_page', 'order', 'sens', 'dst', 'src', 'clid', 'userfieldtype', 'userfield', 'accountcodetype', 'accountcode', 'customer', 'entercustomer', 'enterprovider','entertariffgroup', 'entertrunk', 'enterratecard','archive', 'id'));


if (!isset ($current_page) || ($current_page == "")){	
		$current_page=0; 
	}

$HD_Form = new FormHandler("cc_call LEFT OUTER JOIN cc_trunk t3 ON cc_call.id_trunk = t3.id_trunk","Calls");
$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "starttime";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";
$HD_Form -> FG_LIMITE_DISPLAY=30;

$yesno = array();
$yesno["1"] = array( gettext("Yes"), "1");
$yesno["0"] = array( gettext("No"), "0");


$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = FALSE;
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_DO_ARCHIVE_ALL = true;
$HD_Form -> AddViewElement(gettext("ID"), "id", "3%", "center", "sort");
$HD_Form -> AddViewElement(gettext("Calldate"), "starttime", "15%", "center", "SORT", "19", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("CalledNumber"), "calledstation", "15%", "center", "SORT", "30", "", "", "", "", "", "remove_prefix");
$HD_Form -> AddViewElement(gettext("Destination"), "destination", "15%", "center", "SORT", "30", "", "", "", "", "", "remove_prefix");
$HD_Form -> AddViewElement(gettext("Duration"), "sessiontime", "7%", "center", "SORT", "30", "", "", "", "", "", "display_minute");
$HD_Form -> AddViewElement(gettext("CardUsed"), "username", "11%", "center", "SORT", "", "30", "", "", "", "", "linktocustomer");
$HD_Form -> AddViewElement(gettext("terminatecause"), "terminatecause", "10%", "center", "SORT", "30");
$HD_Form -> AddViewElement(gettext("IAX/SIP"), "sipiax", "6%", "center", "SORT",  "", "list", $yesno);
$HD_Form -> AddViewElement(gettext("InitialRate"), "calledrate", "10%", "center", "SORT", "30", "", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("Cost"), "sessionbill", "10%", "center", "SORT", "30", "", "", "", "", "", "display_2bill");

$FG_COL_QUERY='id, starttime, calledstation, destination, sessiontime, username, terminatecause, sipiax, calledrate, sessionbill';

$HD_Form -> FieldViewElement ($FG_COL_QUERY);
$HD_Form -> FG_OTHER_BUTTON1 = true;
$HD_Form -> FG_OTHER_BUTTON1_LINK= $_SERVER['PHP_SELF']."?archive=true&id=|col0|";
$HD_Form -> FG_OTHER_BUTTON1_IMG = Images_Path . "/bluearrow.gif";
$HD_Form -> FG_OTHER_BUTTON1_ALT=gettext("ARCHIVE");


if ($posted==1){
	

  function do_field($sql,$fld,$dbfld){
  		$fldtype = $fld.'type';
		global $$fld;
		global $$fldtype;		
        if ($$fld){
                if (strpos($sql,'WHERE') > 0){
                        $sql = "$sql AND ";
                }else{
                        $sql = "$sql WHERE ";
                }
				$sql = "$sql $dbfld";
				if (isset ($$fldtype)){                
                        switch ($$fldtype) {
							case 1:	$sql = "$sql='".$$fld."'";  break;
							case 2: $sql = "$sql LIKE '".$$fld."%'";  break;
							case 3: $sql = "$sql LIKE '%".$$fld."%'";  break;
							case 4: $sql = "$sql LIKE '%".$$fld."'";
						}
                }else{ $sql = "$sql LIKE '%".$$fld."%'"; }
		}
        return $sql;
  }  
  $SQLcmd = '';
  
  $SQLcmd = do_field($SQLcmd, 'src', 'src');
  $SQLcmd = do_field($SQLcmd, 'dst', 'calledstation');

  if ($_POST['before']) {
    if (strpos($SQLcmd, 'WHERE') > 0) { 	$SQLcmd = "$SQLcmd AND ";
    }else{     								$SQLcmd = "$SQLcmd WHERE "; }
    $SQLcmd = "$SQLcmd starttime <'".$_POST['before']."'";
  }
  if ($_POST['after']) {    if (strpos($SQLcmd, 'WHERE') > 0) {      $SQLcmd = "$SQLcmd AND ";
  } else {      $SQLcmd = "$SQLcmd WHERE ";    }
    $SQLcmd = "$SQLcmd starttime >'".$_POST['after']."'";
  }
  
  
}

//echo "SQLcmd:$SQLcmd<br>";

$date_clause='';
// Period (Month-Day)


if (!isset($fromstatsday_sday)){	
	$fromstatsday_sday = date("d");
	$fromstatsmonth_sday = date("Y-m");	
}


if (!isset($days_compare)){		
	$days_compare=2;
}



//if (isset($fromstatsday_sday) && isset($fromstatsmonth_sday)) $date_clause.=" AND calldate <= '$fromstatsmonth_sday-$fromstatsday_sday+23' AND calldate >= SUBDATE('$fromstatsmonth_sday-$fromstatsday_sday',INTERVAL $days_compare DAY)";

if (DB_TYPE == "postgres"){	
	if (isset($fromstatsday_sday) && isset($fromstatsmonth_sday)) $date_clause.=" AND starttime < date'$fromstatsmonth_sday-$fromstatsday_sday'+ INTERVAL '1 DAY' AND starttime >= date'$fromstatsmonth_sday-$fromstatsday_sday' - INTERVAL '$days_compare DAY'";
}else{
	if (isset($fromstatsday_sday) && isset($fromstatsmonth_sday)) $date_clause.=" AND starttime < ADDDATE('$fromstatsmonth_sday-$fromstatsday_sday',INTERVAL 1 DAY) AND starttime >= SUBDATE('$fromstatsmonth_sday-$fromstatsday_sday',INTERVAL $days_compare DAY)";  
}

if (isset($customer)  &&  ($customer>0)){
	if (strlen($SQLcmd)>0) $SQLcmd.=" AND ";
	else $SQLcmd.=" WHERE ";
	$SQLcmd.=" username='$customer' ";
}else{
	if (isset($entercustomer)  &&  ($entercustomer>0)){
		if (strlen($SQLcmd)>0) $SQLcmd.=" AND ";
		else $SQLcmd.=" WHERE ";
		$SQLcmd.=" username='$entercustomer' ";
	}
}
if ($_SESSION["is_admin"] == 1)
{
        if (isset($enterprovider) && $enterprovider > 0) {
			if (strlen($SQLcmd) > 0) $SQLcmd .= " AND "; else $SQLcmd .= " WHERE ";
			$SQLcmd .= " t3.id_provider = '$enterprovider' ";
        }
        if (isset($entertrunk) && $entertrunk > 0) {
			if (strlen($SQLcmd) > 0) $SQLcmd .= " AND "; else $SQLcmd .= " WHERE ";
			$SQLcmd .= " t3.id_trunk = '$entertrunk' ";
        }
		if (isset($entertariffgroup) && $entertariffgroup > 0) {
			if (strlen($SQLcmd) > 0) $SQLcmd .= " AND "; else $SQLcmd .= " WHERE ";
			$SQLcmd .= "id_tariffgroup = '$entertariffgroup'";
		}
		if (isset($enterratecard) && $enterratecard > 0) {
			if (strlen($SQLcmd) > 0) $SQLcmd .= " AND "; else $SQLcmd .= " WHERE ";
			$SQLcmd .= "id_ratecard = '$enterratecard'";
		}
}

if (strpos($SQLcmd, 'WHERE') > 0) { 
	$HD_Form -> FG_TABLE_CLAUSE = substr($SQLcmd,6).$date_clause; 
}elseif (strpos($date_clause, 'AND') > 0){
	$HD_Form -> FG_TABLE_CLAUSE = substr($date_clause,5); 
}
if(isset($archive) && !empty($archive)){
	if(isset($id) && !empty($id)){
		$condition = "id = $id";
	}else{
		$condition = $HD_Form -> FG_TABLE_CLAUSE;
	}
	if (strpos($condition,'WHERE') <= 0){
	        $condition = " WHERE $condition";
	}

archive_data($condition, "call");
$archive_message = "The data has been successfully archived";
}

$smarty->display('main.tpl');
?>

<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

//-->
</script>



<!-- ** ** ** ** ** Part for the research ** ** ** ** ** -->
	<center>
	<FORM METHOD=POST name="myForm" ACTION="<?php echo $PHP_SELF?>?s=<?php echo $s?>&t=<?php echo $t?>&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>">
	<INPUT TYPE="hidden" NAME="posted" value=1>
		<table class="bar-status" width="80%" border="0" cellspacing="1" cellpadding="2" align="center">
			<tbody>
			<tr>
				<td align="left" valign="top"  class="bgcolor_004">					
					<font  class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("CUSTOMERS");?></font>
				</td>
			<td class="bgcolor_005" align="left">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td class="fontstyle_searchoptions" width="50%" valign="top">
					<?php echo gettext("Enter the cardnumber");?>: <INPUT TYPE="text" NAME="entercustomer" value="<?php echo $entercustomer?>" class="form_input_text">
					<a href="#" onclick="window.open('A2B_entity_card.php?popup_select=2&popup_formname=myForm&popup_fieldname=entercustomer' , 'CardNumberSelection','scrollbars=1,width=550,height=330,top=20,left=100,scrollbars=1');"><img src="<?php echo Images_Path;?>/icon_arrow_orange.gif"></a>
				</td>
				<td width="50%">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td align="left" class="fontstyle_searchoptions"><?php echo gettext("CallPlan");?> :</td>
							<td align="left" class="fontstyle_searchoptions"><INPUT TYPE="text" NAME="entertariffgroup" value="<?php echo $entertariffgroup?>" size="4" class="form_input_text">&nbsp;<a href="#" onclick="window.open('A2B_entity_tariffgroup.php?popup_select=2&popup_formname=myForm&popup_fieldname=entertariffgroup' , 'CallPlanSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><img src="<?php echo Images_Path;?>/icon_arrow_orange.gif"></a></td>
							<td align="left" class="fontstyle_searchoptions"><?php echo gettext("Provider");?> :
							<td align="left" class="fontstyle_searchoptions"><INPUT TYPE="text" NAME="enterprovider" value="<?php echo $enterprovider?>" size="4" class="form_input_text">&nbsp;<a href="#" onclick="window.open('A2B_entity_provider.php?popup_select=2&popup_formname=myForm&popup_fieldname=enterprovider' , 'ProviderSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><img src="<?php echo Images_Path;?>/icon_arrow_orange.gif"></a></td>
						</tr>
						<tr>
							<td align="left" class="fontstyle_searchoptions"><?php echo gettext("Trunk");?> :</td>
							<td align="left" class="fontstyle_searchoptions"><INPUT TYPE="text" NAME="entertrunk" value="<?php echo $entertrunk?>" size="4" class="form_input_text">&nbsp;<a href="#" onclick="window.open('A2B_entity_trunk.php?popup_select=2&popup_formname=myForm&popup_fieldname=entertrunk' , 'TrunkSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><img src="<?php echo Images_Path;?>/icon_arrow_orange.gif"></a></td>
							<td align="left" class="fontstyle_searchoptions"><?php echo gettext("Ratecard ID");?> :</td>
							<td align="left" class="fontstyle_searchoptions"><INPUT TYPE="text" NAME="enterratecard" value="<?php echo $enterratecard?>" size="4" class="form_input_text">&nbsp;<a href="#" onclick="window.open('A2B_entity_def_ratecard.php?popup_select=2&popup_formname=myForm&popup_fieldname=enterratecard' , 'RatecardSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><img src="<?php echo Images_Path;?>/icon_arrow_orange.gif"></a></td>
						</tr>
					</table>
				</tr>
			</table>
			</td>
			</tr>
			<tr>
        		<td align="left" class="bgcolor_002">					
					<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("SELECT DAY");?></font>
				</td>
      			<td align="left" class="bgcolor_003">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" >
					<tr><td class="fontstyle_searchoptions">
	  				<?php echo gettext("From");?> : <select name="fromstatsday_sday" class="form_input_select">
					<?php  
						for ($i=1;$i<=31;$i++){
							if ($fromstatsday_sday==sprintf("%02d",$i)){$selected="selected";}else{$selected="";}
							echo '<option value="'.sprintf("%02d",$i)."\"$selected>".sprintf("%02d",$i).'</option>';
						}
					?>					
					</select>
				 	<select name="fromstatsmonth_sday" class="form_input_select">
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
								if ($fromstatsmonth_sday=="$i-$month_formated") $selected="selected";
								else $selected="";
								echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";				
							}
						}								
					?>										
					</select>
					</td><td class="fontstyle_searchoptions">&nbsp;&nbsp;
					<?php echo gettext("Number of days to compare");?> :
				 	<select name="days_compare" class="form_input_select">
					<option value="4" <?php if ($days_compare=="4"){ echo "selected";}?>>- 4 <?php echo gettext("days");?></option>
					<option value="3" <?php if ($days_compare=="3"){ echo "selected";}?>>- 3 <?php echo gettext("days");?></option>
					<option value="2" <?php if (($days_compare=="2")|| !isset($days_compare)){ echo "selected";}?>>- 2 <?php echo gettext("days");?></option>
					<option value="1" <?php if ($days_compare=="1"){ echo "selected";}?>>- 1 <?php echo gettext("days");?></option>
					</select>
					</td></tr></table>
	  			</td>
    		</tr>	
			
			<tr>
				<td class="bgcolor_004" align="left">			
					<font  class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("DESTINATION");?></font>
				</td>				
				<td class="bgcolor_005" align="left" >
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td class="fontstyle_searchoptions">&nbsp;&nbsp;<INPUT TYPE="text" NAME="dst" value="<?php echo $dst?>"></td>
				<td  align="center" class="fontstyle_searchoptions" ><input type="radio" NAME="dsttype" value="1" <?php if((!isset($dsttype))||($dsttype==1)){?>checked<?php }?>><?php echo gettext("Exact");?></td>
				<td  align="center" class="fontstyle_searchoptions"><input type="radio" NAME="dsttype" value="2" <?php if($dsttype==2){?>checked<?php }?>><?php echo gettext("Begins with");?></td>
				<td  align="center" class="fontstyle_searchoptions"><input type="radio" NAME="dsttype" value="3" <?php if($dsttype==3){?>checked<?php }?>><?php echo gettext("Contains");?></td>
				<td  align="center" class="fontstyle_searchoptions"><input type="radio" NAME="dsttype" value="4" <?php if($dsttype==4){?>checked<?php }?>><?php echo gettext("Ends with");?></td>
				</tr></table></td>
			</tr>			
			<tr>
				<td align="left" class="bgcolor_002">					
					<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("SOURCE");?></font>
				</td>				
				<td class="bgcolor_003" align="left">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td class="fontstyle_searchoptions">&nbsp;&nbsp;<INPUT TYPE="text" NAME="src" value="<?php echo "$src";?>"></td>
				<td  align="center" class="fontstyle_searchoptions" ><input type="radio" NAME="srctype" value="1" <?php if((!isset($srctype))||($srctype==1)){?>checked<?php }?>><?php echo gettext("Exact");?></td>
				<td  align="center" class="fontstyle_searchoptions"><input type="radio" NAME="srctype" value="2" <?php if($srctype==2){?>checked<?php }?>><?php echo gettext("Begins with");?></td>
				<td  align="center" class="fontstyle_searchoptions"><input type="radio" NAME="srctype" value="3" <?php if($srctype==3){?>checked<?php }?>><?php echo gettext("Contains");?></td>
				<td  align="center" class="fontstyle_searchoptions"><input type="radio" NAME="srctype" value="4" <?php if($srctype==4){?>checked<?php }?>><?php echo gettext("Ends with");?></td>
				</tr></table></td>
			</tr>

			<tr>
        		<td class="bgcolor_004" align="left"> </td>

				<td class="bgcolor_005" align="center" >
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bgcolor_005">
					<tr><td class="fontstyle_searchoptions">				
						<?php echo gettext("Graph");?> :
							<select name="min_call" class="form_input_select">					
							<option value=1 <?php  if ($min_call==1){ echo "selected";}?>><?php echo gettext("Minutes by hours");?></option>
							<option value=0 <?php  if (($min_call==0) || !isset($min_call)){ echo "selected";}?>><?php echo gettext("Number of calls by hours");?></option>
							<option value=2 <?php  if ($min_call==2){ echo "selected";}?>><?php echo gettext("Profits by hours");?></option>
							<option value=3 <?php  if ($min_call==3){ echo "selected";}?>><?php echo gettext("Sells by hours");?></option>
							<option value=4 <?php  if ($min_call==4){ echo "selected";}?>><?php echo gettext("Buys by hours");?></option>
							</select>
						</td>
						<td align="right">							
							
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td></tr></table>
	  			</td>
    		</tr>
			<tr>
				<td align="left" class="bgcolor_002">					
					
				</td>				
				<td class="bgcolor_003" align="left">
				<center><input type="image"  name="image16" align="top" border="0" src="<?php echo Images_Path;?>/button-search.gif" />
				</center>
				</td>
			</tr>
		</tbody></table>
	</FORM>
</center>
<center>
<form name="frm_archive" id="frm_archive" method="post" action="A2B_data_archiving.php">
<table class="bar-status" width="50%" border="0" cellspacing="1" cellpadding="2" align="center">
			<tbody>			
			<tr>
				<td width="30%" align="left" valign="top" class="bgcolor_004">					
					<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("ARCHIVING OPTIONS");?></font>
				</td>				
				<td width="70%" align="CENTER" class="bgcolor_005">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center"><tr>
				  <td class="fontstyle_searchoptions">
				<select name="archiveselect" class="form_input_select" onchange="form.submit();">
				<option value="" ><?php echo gettext("Calls Archiving");?></option>
				<option value="" ><?php echo gettext("Customer Archiving");?></option>
				</select>
					</td>					
				</tr></table></td>
			</tr>			
		</tbody></table>
</FORM>
</center>
<?php
if(isset($archive) && !empty($archive)){
	$HD_Form -> CV_NO_FIELDS = "";
	print "<div align=\"center\">".$archive_message."</div>";
}
if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;

$list = $HD_Form -> perform_action($form_action);

      $HD_Form -> create_form ($form_action, $list, $id=null) ;
       $smarty->display('footer.tpl');
?>
