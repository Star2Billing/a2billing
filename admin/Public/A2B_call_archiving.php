<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,   
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 * 
 * @copyright   Copyright (C) 2004-2009 - Star2billing S.L. 
 * @author      Belaid Arezqui <areski@gmail.com>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 * @package     A2Billing
 *
 * Software License Agreement (GNU Affero General Public License)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * 
**/


include_once(dirname(__FILE__) . "/../lib/admin.defines.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include_once(dirname(__FILE__) . "/../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");


if (! has_rights (ACX_MAINTENANCE)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();
}

check_demo_mode();

getpost_ifset(array('customer', 'entercustomer', 'enterprovider', 'entertariffgroup', 'entertrunk', 'enterratecard', 'posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'month_earlier', 'dsttype', 'srctype', 'clidtype', 'channel', 'resulttype', 'stitle', 'atmenu', 'current_page', 'order', 'sens', 'dst', 'src', 'clid', 'choose_currency', 'terminatecauseid','archive', 'id'));

if (!isset ($current_page) || ($current_page == "")){	
	$current_page=0; 
}
$HD_Form = new FormHandler("cc_call t1 LEFT OUTER JOIN cc_trunk t3 ON t1.id_trunk = t3.id_trunk","Calls");

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
$HD_Form -> AddViewElement(gettext("terminatecauseid"), "terminatecauseid", "10%", "center", "SORT", "30");
$HD_Form -> AddViewElement(gettext("IAX/SIP"), "sipiax", "6%", "center", "SORT",  "", "list", $yesno);
$HD_Form -> AddViewElement(gettext("InitialRate"), "calledrate", "10%", "center", "SORT", "30", "", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("Cost"), "sessionbill", "10%", "center", "SORT", "30", "", "", "", "", "", "display_2bill");

$FG_COL_QUERY='id, starttime, calledstation, destination, real_sessiontime, card_id, terminatecauseid, sipiax, buycost, sessionbill';

$HD_Form -> FieldViewElement ($FG_COL_QUERY);

if ($posted==1) {
	$SQLcmd = '';
	$SQLcmd = do_field($SQLcmd, 'src', 'src');
	$SQLcmd = do_field($SQLcmd, 'dst', 'calledstation');
}


$date_clause='';
if (DB_TYPE == "postgres"){		
	$UNIX_TIMESTAMP = "";
}else{
	$UNIX_TIMESTAMP = "UNIX_TIMESTAMP";
}
$lastdayofmonth = date("t", strtotime($tostatsmonth.'-01'));
normalize_day_of_month($fromstatsday_sday, $fromstatsmonth_sday, 1);
normalize_day_of_month($tostatsday_sday, $tostatsmonth_sday, 1);
if ($Period=="Month"){
	if ($frommonth && isset($fromstatsmonth)) $date_clause.=" AND $UNIX_TIMESTAMP(starttime) >= $UNIX_TIMESTAMP('$fromstatsmonth-01')";
	if ($tomonth && isset($tostatsmonth)) $date_clause.=" AND $UNIX_TIMESTAMP(starttime) <= $UNIX_TIMESTAMP('".$tostatsmonth."-$lastdayofmonth 23:59:59')"; 
}else{
	if ($fromday && isset($fromstatsday_sday) && isset($fromstatsmonth_sday)) $date_clause.=" AND $UNIX_TIMESTAMP(starttime) >= $UNIX_TIMESTAMP('$fromstatsmonth_sday-$fromstatsday_sday')";
	if ($today && isset($tostatsday_sday) && isset($tostatsmonth_sday)) $date_clause.=" AND $UNIX_TIMESTAMP(starttime) <= $UNIX_TIMESTAMP('$tostatsmonth_sday-".sprintf("%02d",intval($tostatsday_sday)/*+1*/)." 23:59:59')";
}

if ($Period=="month_older_rad"){
	$from_month = $month_earlier;
	if(DB_TYPE == "postgres"){
		$date_clause .= " AND CURRENT_TIMESTAMP - interval '$from_month months' > starttime";
	}else{
		$date_clause .= " AND DATE_SUB(NOW(),INTERVAL $from_month MONTH) > starttime";
	}
}



  
if (strpos($SQLcmd, 'WHERE') > 0) { 
	$HD_Form -> FG_TABLE_CLAUSE = substr($SQLcmd,6).$date_clause; 
}elseif (strpos($date_clause, 'AND') > 0){
	$HD_Form -> FG_TABLE_CLAUSE = substr($date_clause,5); 
}


if (!isset ($HD_Form -> FG_TABLE_CLAUSE) || strlen($HD_Form -> FG_TABLE_CLAUSE)==0){
	$cc_yearmonth = sprintf("%04d-%02d-%02d",date("Y"),date("n"),date("d"));
	$HD_Form -> FG_TABLE_CLAUSE=" $UNIX_TIMESTAMP(starttime) >= $UNIX_TIMESTAMP('$cc_yearmonth')";
}


if (isset($customer)  &&  ($customer>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="username='$customer'";
}else{
	if (isset($entercustomer)  &&  ($entercustomer>0)){
		if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
		$HD_Form -> FG_TABLE_CLAUSE.="username='$entercustomer'";
	}
}
if ($_SESSION["is_admin"] == 1)
{
	if (isset($enterprovider) && $enterprovider > 0) {
		if (strlen($HD_Form -> FG_TABLE_CLAUSE) > 0) $HD_Form -> FG_TABLE_CLAUSE .= " AND ";
		$HD_Form -> FG_TABLE_CLAUSE .= "t3.id_provider = '$enterprovider'";
	}
	if (isset($entertrunk) && $entertrunk > 0) {
		if (strlen($HD_Form -> FG_TABLE_CLAUSE) > 0) $HD_Form -> FG_TABLE_CLAUSE .= " AND ";
		$HD_Form -> FG_TABLE_CLAUSE .= "t3.id_trunk = '$entertrunk'";
	}
	if (isset($entertariffgroup) && $entertariffgroup > 0) {
		if (strlen($HD_Form -> FG_TABLE_CLAUSE) > 0) $HD_Form -> FG_TABLE_CLAUSE .= " AND ";
		$HD_Form -> FG_TABLE_CLAUSE .= "id_tariffgroup = '$entertariffgroup'";
	}
	if (isset($enterratecard) && $enterratecard > 0) {
		if (strlen($HD_Form -> FG_TABLE_CLAUSE) > 0) $HD_Form -> FG_TABLE_CLAUSE .= " AND ";
		$HD_Form -> FG_TABLE_CLAUSE .= "id_ratecard = '$enterratecard'";
	}

}

//To select just terminatecauseid=ANSWER
if (!isset($terminatecauseid)) {
	$terminatecauseid="ANSWER";
}
if ($terminatecauseid=="ANSWER") {
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE .= " (terminatecauseid=1) ";
}

if($posted == 1){
	$_SESSION['ss_calllist'] = '';
	$_SESSION['ss_calllist'] = $HD_Form -> FG_TABLE_CLAUSE;
}
if(isset($archive) && !empty($archive)){
	$condition = $_SESSION['ss_calllist'];
    if (strlen($condition) && strpos($condition,'WHERE') === false){
        $condition = " WHERE $condition";
    }
    $rec = archive_data($condition, "call");
    if($rec > 0)
        $archive_message = "The data has been successfully archived";
}

$smarty->display('main.tpl');

?>



<!-- ** ** ** ** ** Part for the research ** ** ** ** ** -->
<center>
<FORM METHOD=POST name="myForm" ACTION="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>">
<INPUT TYPE="hidden" NAME="posted" value=1>
<INPUT TYPE="hidden" NAME="current_page" value=0>	
	<TABLE class="bar-status" width="85%" border="0" cellspacing="1" cellpadding="2" align="center">
		<?php  if ($_SESSION["pr_groupID"]==2 && is_numeric($_SESSION["pr_IDCust"])){ ?>
		<?php  }else{ ?>
		<tr>
			<td align="left" valign="top" class="bgcolor_004">					
				<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("CUSTOMERS");?></font>
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
		<?php  }?>
		<tr>
			<td class="bgcolor_002" align="left">

				<input type="radio" name="Period" value="Month" <?php  if (($Period=="Month") || !isset($Period)){ ?>checked="checked" <?php  } ?>> 
				<font class="fontstyle_003"><?php echo gettext("SELECT MONTH");?></font>
			</td>
			<td class="bgcolor_003" align="left">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td class="fontstyle_searchoptions">
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
				</select>
				</td><td  class="fontstyle_searchoptions">&nbsp;&nbsp;
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
				</td></tr></table>
			</td>
		</tr>
		
		<tr>
			<td align="left" class="bgcolor_004">
				<input type="radio" name="Period" value="Day" <?php  if ($Period=="Day"){ ?>checked="checked" <?php  } ?>> 
				<font class="fontstyle_003"><?php echo gettext("SELECT DAY");?></font>
			</td>
			<td align="left" class="bgcolor_005">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td class="fontstyle_searchoptions">
				<input type="checkbox" name="fromday" value="true" <?php  if ($fromday){ ?>checked<?php }?>> <?php echo gettext("From");?> :
				<select name="fromstatsday_sday" class="form_input_select">
					<?php  
					for ($i=1;$i<=31;$i++){
						if ($fromstatsday_sday==sprintf("%02d",$i)) $selected="selected";
						else	$selected="";
						echo '<option value="'.sprintf("%02d",$i)."\"$selected>".sprintf("%02d",$i).'</option>';
					}
					?>	
				</select>
				<select name="fromstatsmonth_sday" class="form_input_select">
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
							if ($fromstatsmonth_sday=="$i-$month_formated") $selected="selected";
							else $selected="";
							echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";				
						}
					}
				?>
				</select>
				</td><td class="fontstyle_searchoptions">&nbsp;&nbsp;
				<input type="checkbox" name="today" value="true" <?php  if ($today){ ?>checked<?php }?>> 
				<?php echo gettext("To");?>  :
				<select name="tostatsday_sday" class="form_input_select">
				<?php  
					for ($i=1;$i<=31;$i++){
						if ($tostatsday_sday==sprintf("%02d",$i)){$selected="selected";}else{$selected="";}
						echo '<option value="'.sprintf("%02d",$i)."\"$selected>".sprintf("%02d",$i).'</option>';
					}
				?>						
				</select>
				<select name="tostatsmonth_sday" class="form_input_select">
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
							if ($tostatsmonth_sday=="$i-$month_formated") $selected="selected";
							else	$selected="";
							echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";				
						}
					}
				?>
				</select>
				</td></tr>
				</table>
			</td>
		</tr>
		<tr>
    		<td align="left" class="bgcolor_002">
				<input type="radio" name="Period" value="month_older_rad" <?php  if ($Period =="month_older_rad"){ ?>checked="checked" <?php  } ?>>
				<font class="fontstyle_003"><?php echo gettext("Select card older than");?></font>
			</td>
  			<td align="left" class="bgcolor_003">
				<table  border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr><td class="fontstyle_searchoptions">&nbsp;&nbsp;
				<select name="month_earlier" class="form_input_select">
					<?php
						for ($i=3;$i<=12;$i++){
							if ($month_earlier == $i){$selected="selected";}else{$selected="";}
							echo '<option value="'.$i."\"$selected>".$i.' Months</option>';
						}
					?>
				</select>
				</td></tr></table>
  			</td>
		</tr>
		<tr>
			<td class="bgcolor_002" align="left">			
				<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("DESTINATION");?></font>
			</td>				
			<td class="bgcolor_003" align="left">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td>&nbsp;&nbsp;<INPUT TYPE="text" NAME="dst" value="<?php echo $dst?>" class="form_input_text"></td>
				<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="dsttype" value="1" <?php if((!isset($dsttype))||($dsttype==1)){?>checked<?php }?>><?php echo gettext("Exact");?></td>
				<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="dsttype" value="2" <?php if($dsttype==2){?>checked<?php }?>><?php echo gettext("Begins with");?></td>
				<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="dsttype" value="3" <?php if($dsttype==3){?>checked<?php }?>><?php echo gettext("Contains");?></td>
				<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="dsttype" value="4" <?php if($dsttype==4){?>checked<?php }?>><?php echo gettext("Ends with");?></td>
				</tr>
			</table></td>
		</tr>			
		<tr>
			<td align="left" class="bgcolor_004">					
				<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("SOURCE");?></font>
			</td>				
			<td class="bgcolor_005" align="left">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" >
			<tr><td>&nbsp;&nbsp;<INPUT TYPE="text" NAME="src" value="<?php echo "$src";?>" class="form_input_text"></td>
			<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="srctype" value="1" <?php if((!isset($srctype))||($srctype==1)){?>checked<?php }?>><?php echo gettext("Exact");?></td>
			<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="srctype" value="2" <?php if($srctype==2){?>checked<?php }?>><?php echo gettext("Begins with");?></td>
			<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="srctype" value="3" <?php if($srctype==3){?>checked<?php }?>><?php echo gettext("Contains");?></td>
			<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="srctype" value="4" <?php if($srctype==4){?>checked<?php }?>><?php echo gettext("Ends with");?></td>
			</tr></table></td>
		</tr>
		
		
		<!-- Select Option : to show just the Answered Calls or all calls, Result type, currencies... -->
		<tr>
		  <td class="bgcolor_002" align="left" ><font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("OPTIONS");?></font></td>
		  <td class="bgcolor_003" align="center"><div align="left">
		  
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="20%"  class="fontstyle_searchoptions">
					<?php echo gettext("SHOW");?> :  						
			   </td>
			   <td width="80%"  class="fontstyle_searchoptions">
					<?php echo gettext("Answered Calls");?>  
					<input name="terminatecauseid" type="radio" value="ANSWER" <?php if((!isset($terminatecauseid))||($terminatecauseid=="ANSWER")){?>checked<?php }?> /> 
					<?php echo gettext("All Calls");?>  
					<input name="terminatecauseid" type="radio" value="ALL" <?php if($terminatecauseid=="ALL"){?>checked<?php }?>/>
				</td>
			</tr>
			<tr class="bgcolor_005">
				<td  class="fontstyle_searchoptions">
					<?php echo gettext("RESULT");?> : 
			   </td>
			   <td  class="fontstyle_searchoptions">
					<?php echo gettext("mins");?><input type="radio" NAME="resulttype" value="min" <?php if((!isset($resulttype))||($resulttype=="min")){?>checked<?php }?>> - <?php echo gettext("secs")?> <input type="radio" NAME="resulttype" value="sec" <?php if($resulttype=="sec"){?>checked<?php }?>>
				</td>
			</tr>
			<tr>
				<td  class="fontstyle_searchoptions">
					<?php echo gettext("CURRENCY");?> :
				</td>
				<td  class="fontstyle_searchoptions">
					<select NAME="choose_currency" size="1" class="form_input_select">
						<?php
							$currencies_list = get_currencies();
							foreach($currencies_list as $key => $cur_value) {
						?>
							<option value='<?php echo $key ?>' <?php if (($choose_currency==$key) || (!isset($choose_currency) && $key==strtoupper(BASE_CURRENCY))){?>selected<?php } ?>><?php echo $cur_value[1].' ('.$cur_value[2].')' ?>
							</option>
						<?php 	} ?>
					</select>
				</td>
			</tr>
			</table>
		</td>
		</tr>
		<!-- Select Option : to show just the Answered Calls or all calls, Result type, currencies... -->
		
		<tr>
			<td class="bgcolor_004" align="left" > </td>
			<td class="bgcolor_005" align="center" >
				<input type="image"  name="image16" align="top" border="0" src="<?php echo Images_Path;?>/button-search.gif"/>
			</td>
		</tr>
	</table>
</FORM>
</center>


<!-- ** ** ** ** ** Displaying the Archiving options ** ** ** ** ** -->
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
</form>
</center>


<!-- ** ** ** ** ** Displaying the Archiving message, calls list and Archive action button and link ** ** ** ** ** -->
<?php

if(isset($archive) && !empty($archive)) {
	$HD_Form -> CV_NO_FIELDS = "";
	print "<div align=\"center\">".$archive_message."</div>";
}

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;

$list = $HD_Form -> perform_action($form_action);

$HD_Form -> create_form ($form_action, $list, $id=null) ;

$smarty->display('footer.tpl');

