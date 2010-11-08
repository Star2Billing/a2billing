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


include ("lib/customer.defines.php");
include ("lib/customer.module.access.php");
include ("lib/customer.smarty.php");


if (! has_rights (ACX_CALL_HISTORY)) { 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();
}

getpost_ifset(array('posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'phonenumbertype', 'sourcetype', 'clidtype', 'channel', 'resulttype', 'stitle', 'atmenu', 'current_page', 'order', 'sens', 'phonenumber', 'src', 'clid', 'choose_currency', 'terminatecauseid', 'choose_calltype', 'download', 'file'));


if (($download == "file") && $file && $ACXSEERECORDING) {
	
	if (strpos($file, '/') !== false) exit;
	
	$value_de = base64_decode ( $file );
	$dl_full = MONITOR_PATH . "/" . $value_de;
	$dl_name = $value_de;
	
	if (! file_exists ( $dl_full )) {
		echo gettext ( "ERROR: Cannot download file " . $dl_full . ", it does not exist.<br>" );
		exit ();
	}
	
	header ( "Content-Type: application/octet-stream" );
	header ( "Content-Disposition: attachment; filename=$dl_name" );
	header ( "Content-Length: " . filesize ( $dl_full ) );
	header ( "Accept-Ranges: bytes" );
	header ( "Pragma: no-cache" );
	header ( "Expires: 0" );
	header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
	header ( "Content-transfer-encoding: binary" );
	
	@readfile ( $dl_full );
	exit ();
}


$QUERY = "SELECT username, credit, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, lastuse, activated, status FROM cc_card WHERE username = '".$_SESSION["pr_login"]."' AND uipass = '".$_SESSION["pr_password"]."'";

$DBHandle_max = DbConnect();
$numrow = 0;
$resmax = $DBHandle_max -> Execute($QUERY);
if ($resmax)
	$numrow = $resmax -> RecordCount();

if ($numrow == 0) exit();
$customer_info =$resmax -> fetchRow();

if ($customer_info[14] != "1" && $customer_info[14] != "8") {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

$customer = $_SESSION["card_id"];


$dialstatus_list = Constants::getDialStatusList();

if (!isset ($current_page) || ($current_page == "")) {
	$current_page=0;
}

$FG_DEBUG = 0;
$FG_TABLE_NAME="cc_call t1";

// THIS VARIABLE DEFINE THE COLOR OF THE HEAD TABLE
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#FFFFFF";
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#F2F8FF";

$yesno = array();
$yesno["1"] = array( "Yes", "1");
$yesno["0"] = array( "No", "0");

// 0 = NORMAL CALL ; 1 = VOIP CALL (SIP/IAX) ; 2= DIDCALL + TRUNK ; 3 = VOIP CALL DID ; 4 = CALLBACK call
$list_calltype = array(); 
$list_calltype["0"]  = array( gettext("STANDARD"), "0");
$list_calltype["1"]  = array( gettext("SIP/IAX"), "1");
$list_calltype["2"]  = array( gettext("DIDCALL"), "2");
$list_calltype["3"]  = array( gettext("DID_VOIP"), "3");
$list_calltype["4"]  = array( gettext("CALLBACK"), "4");
$list_calltype["5"]  = array( gettext("PREDICT"), "5");
$list_calltype ["6"] = array (gettext("AUTO DIALER"), "6" );
$list_calltype ["7"] = array (gettext("DID-ALEG"), "7" );

$DBHandle  = DbConnect();

$FG_TABLE_DEFAULT_ORDER = "t1.starttime";
$FG_TABLE_DEFAULT_SENS = "DESC";

$FG_TABLE_COL = array();
$FG_TABLE_COL[]=array (gettext("Date"), "starttime", "17%", "center", "SORT", "22", "", "", "", "", "", "");
$FG_TABLE_COL[]=array (gettext("CallerID"), "source", "14%", "center", "SORT", "30");
$FG_TABLE_COL[]=array (gettext("PhoneNumber"), "calledstation", "14%", "center", "SORT", "30", "", "", "", "", "", "");
$FG_TABLE_COL[]=array (gettext("Destination"), "destination", "14%", "center", "SORT", "30", "lie", "cc_prefix", "destination", "prefix='%id'", "%1" );
$FG_TABLE_COL[]=array (gettext("Duration"), "sessiontime", "10%", "center", "SORT", "30", "", "", "", "", "", "display_minute");
$FG_TABLE_COL[]=array ('<acronym title="'.gettext("Terminate Cause").'">'.gettext("TC").'</acronym>', "terminatecauseid", "10%", "center", "SORT", "", "list", $dialstatus_list);
$FG_TABLE_COL[]=array (gettext("CallType"), "sipiax", "12%", "center", "SORT",  "", "list", $list_calltype);
$FG_TABLE_COL[]=array (gettext("Cost"), "sessionbill", "12%", "center", "SORT", "30", "", "", "", "", "", "display_2bill");

if ($ACXSEERECORDING) {
	$FG_TABLE_COL [] = array ("", "uniqueid", "1%", "center", "", "30", "", "", "", "", "", "linkonmonitorfile_customer" );
}

$FG_COL_QUERY = 't1.starttime, t1.src, t1.calledstation, t1.destination, t1.sessiontime, t1.terminatecauseid, t1.sipiax, t1.sessionbill';

if ($ACXSEERECORDING) {
	$FG_COL_QUERY .= ', t1.uniqueid';
}

$FG_LIMITE_DISPLAY = 25;
$FG_NB_TABLE_COL = count($FG_TABLE_COL);
$FG_EDITION = true;
$FG_TOTAL_TABLE_COL = $FG_NB_TABLE_COL;
if ($FG_DELETION || $FG_EDITION) $FG_TOTAL_TABLE_COL++;
$FG_HTML_TABLE_TITLE = " - ".gettext("Call Logs")." - ";
$FG_HTML_TABLE_WIDTH = "98%";


$instance_table = new Table($FG_TABLE_NAME, $FG_COL_QUERY);


if ( is_null ($order) || is_null($sens) ){
	$order = $FG_TABLE_DEFAULT_ORDER;
	$sens  = $FG_TABLE_DEFAULT_SENS;
}

if ($posted==1) {
	$SQLcmd = '';
	$SQLcmd = do_field($SQLcmd, 'src', 'source');
	$SQLcmd = do_field($SQLcmd, 'phonenumber', 'calledstation');
}


$date_clause = '';

normalize_day_of_month($fromstatsday_sday, $fromstatsmonth_sday, 1);
normalize_day_of_month($tostatsday_sday, $tostatsmonth_sday, 1);
if ($fromday && isset($fromstatsday_sday) && isset($fromstatsmonth_sday)) $date_clause.=" AND t1.starttime >= ('$fromstatsmonth_sday-$fromstatsday_sday')";
if ($today && isset($tostatsday_sday) && isset($tostatsmonth_sday)) $date_clause.=" AND t1.starttime <= ('$tostatsmonth_sday-".sprintf("%02d",intval($tostatsday_sday)/*+1*/)." 23:59:59')";

  
if (strpos($SQLcmd, 'WHERE') > 0) {
	$FG_TABLE_CLAUSE = substr($SQLcmd,6).$date_clause;
} elseif (strpos($date_clause, 'AND') > 0) {
	$FG_TABLE_CLAUSE = substr($date_clause,5);
}


if (!isset ($FG_TABLE_CLAUSE) || strlen($FG_TABLE_CLAUSE)==0) {
	$cc_yearmonth = sprintf("%04d-%02d-%02d",date("Y"),date("n"),date("d"));
	$FG_TABLE_CLAUSE=" t1.starttime >= ('$cc_yearmonth')";
}


if (strlen($FG_TABLE_CLAUSE)>0) $FG_TABLE_CLAUSE.=" AND ";
$FG_TABLE_CLAUSE.="t1.card_id='$customer'";


if (isset($choose_calltype) && ($choose_calltype!=-1)) {
	if (strlen($FG_TABLE_CLAUSE)>0) $FG_TABLE_CLAUSE.=" AND ";
	$FG_TABLE_CLAUSE .= " t1.sipiax='$choose_calltype' ";
}

if (!isset($terminatecauseid)) {
	$terminatecauseid="ANSWER";
} elseif ($terminatecauseid=="ANSWER") {
	if (strlen($FG_TABLE_CLAUSE)>0) $FG_TABLE_CLAUSE .= " AND ";
	$FG_TABLE_CLAUSE .= " (t1.terminatecauseid=1) ";
}

if (!$nodisplay) {
	$list = $instance_table -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, $order, $sens, null, null, $FG_LIMITE_DISPLAY, $current_page*$FG_LIMITE_DISPLAY);
}
$_SESSION["pr_sql_export"] = "SELECT $FG_COL_QUERY FROM $FG_TABLE_NAME WHERE $FG_TABLE_CLAUSE";

$QUERY = "SELECT DATE(t1.starttime) AS day, sum(t1.sessiontime) AS calltime, sum(t1.sessionbill) AS cost, count(*) as nbcall FROM $FG_TABLE_NAME WHERE ".$FG_TABLE_CLAUSE." GROUP BY day ORDER BY day"; //extract(DAY from calldate)

if (!$nodisplay) {
	$res = $DBHandle -> Execute($QUERY);
	if ($res) {
		$num = $res -> RecordCount();
		for($i=0;$i<$num;$i++) {				
			$list_total_day [] =$res -> fetchRow();
		}
	}
	
	if ($FG_DEBUG == 3) echo "<br>Clause : $FG_TABLE_CLAUSE";
	$nb_record = $instance_table -> Table_count ($DBHandle, $FG_TABLE_CLAUSE);
	if ($FG_DEBUG >= 1) var_dump ($list);
}

if ($nb_record<=$FG_LIMITE_DISPLAY) { 
	$nb_record_max=1;
} else { 
	if ($nb_record % $FG_LIMITE_DISPLAY == 0) {
		$nb_record_max=(intval($nb_record/$FG_LIMITE_DISPLAY));
	} else {
		$nb_record_max=(intval($nb_record/$FG_LIMITE_DISPLAY)+1);
	}	
}


$smarty->display( 'main.tpl');

// #### HELP SECTION
echo $CC_help_balance_customer;

?>


<!-- ** ** ** ** ** Part for the research ** ** ** ** ** -->
	<center>
	<FORM METHOD=POST ACTION="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>&terminatecauseid=<?php echo $terminatecauseid?>">
		<INPUT TYPE="hidden" NAME="posted" value=1>
		<INPUT TYPE="hidden" NAME="current_page" value=0>
		<table class="callhistory_maintable" align="center">
			
			<tr>
        		<td align="left" class="bgcolor_002"> &nbsp;
					<font class="fontstyle_003"><?php echo gettext("DATE");?></b></font>
				</td>
      			<td align="left" class="bgcolor_003">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" >
					<tr><td class="fontstyle_searchoptions">
	  				<input type="checkbox" name="fromday" value="true" <?php  if ($fromday){ ?>checked<?php }?>> <?php echo gettext("FROM");?> :
					<select name="fromstatsday_sday" class="form_input_select">
						<?php  
							for ($i=1;$i<=31;$i++){
								if ($fromstatsday_sday==sprintf("%02d",$i)){$selected="selected";}else{$selected="";}
								echo '<option value="'.sprintf("%02d",$i)."\"$selected>".sprintf("%02d",$i).'</option>';
							}
						?>	
					</select>
				 	<select name="fromstatsmonth_sday" class="form_input_select">
					<?php 	
						$year_actual = date("Y");
						for ($i=$year_actual;$i >= $year_actual-1;$i--) {		   
							$monthname = array( gettext("JANUARY"), gettext("FEBRUARY"), gettext("MARCH"), gettext("APRIL"), gettext("MAY"), gettext("JUNE"), gettext("JULY"), gettext("AUGUST"), gettext("SEPTEMBER"), gettext("OCTOBER"), gettext("NOVEMBER"), gettext("DECEMBER"));
							if ($year_actual==$i){
								$monthnumber = date("n")-1; // Month number without lead 0.
							}else{
								$monthnumber=11;
							}		   
							for ($j=$monthnumber;$j>=0;$j--){	
								$month_formated = sprintf("%02d",$j+1);
							   	if ($fromstatsmonth_sday=="$i-$month_formated"){$selected="selected";}else{$selected="";}
								echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";				
							}
						}
					?>
					</select>
					</td><td class="fontstyle_searchoptions">&nbsp;&nbsp;
					<input type="checkbox" name="today" value="true" <?php  if ($today){ ?>checked<?php }?>> <?php echo gettext("TO");?> :
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
							   $monthname = array( gettext("JANUARY"), gettext("FEBRUARY"), gettext("MARCH"), gettext("APRIL"), gettext("MAY"), gettext("JUNE"), gettext("JULY"), gettext("AUGUST"), gettext("SEPTEMBER"), gettext("OCTOBER"), gettext("NOVEMBER"), gettext("DECEMBER"));
							   if ($year_actual==$i){
									$monthnumber = date("n")-1; // Month number without lead 0.
							   }else{
									$monthnumber=11;
							   }		   
							   for ($j=$monthnumber;$j>=0;$j--){	
										$month_formated = sprintf("%02d",$j+1);
							   			if ($tostatsmonth_sday=="$i-$month_formated"){$selected="selected";}else{$selected="";}
										echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";				
							   }
						}
					?>
					</select>
					</td></tr></table>
	  			</td>
    		</tr>
			<tr>
				<td  align="left" class="bgcolor_004">
					<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("PHONENUMBER");?></font>
				</td>
				<td  align="left" class="bgcolor_005">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td class="fontstyle_searchoptions">&nbsp;&nbsp;<INPUT TYPE="text" NAME="phonenumber" value="<?php echo $phonenumber?>" class="form_input_text"></td>
				<td  align="center" class="fontstyle_searchoptions"><input type="radio" NAME="phonenumbertype" value="1" <?php if((!isset($phonenumbertype))||($phonenumbertype==1)){?>checked<?php }?>><?php echo gettext("Exact");?></td>
				<td  align="center" class="fontstyle_searchoptions"><input type="radio" NAME="phonenumbertype" value="2" <?php if($phonenumbertype==2){?>checked<?php }?>><?php echo gettext("Begins with")?></td>
				<td  align="center" class="fontstyle_searchoptions"><input type="radio" NAME="phonenumbertype" value="3" <?php if($phonenumbertype==3){?>checked<?php }?>><?php echo gettext("Contains");?></td>
				<td  align="center" class="fontstyle_searchoptions"><input type="radio" NAME="phonenumbertype" value="4" <?php if($phonenumbertype==4){?>checked<?php }?>><?php echo gettext("Ends with");?></td>
				</tr></table></td>
			</tr>		
			<!-- Select Calltype: -->
			<tr>
			  <td class="bgcolor_002" align="left" ><font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("CALL TYPE"); ?></font></td>
			  <td class="bgcolor_003" align="center">
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
					<td  class="fontstyle_searchoptions">
					<select NAME="choose_calltype" size="1" class="form_input_select" >
						<option value='-1' <?php if (($choose_calltype==-1) || (!isset($choose_calltype))){?>selected<?php } ?>><?php echo gettext('ALL CALLS') ?>
								</option>
							<?php
								foreach($list_calltype as $key => $cur_value) {
							?>
								<option value='<?php echo $cur_value[1] ?>' <?php if ($choose_calltype==$cur_value[1]){?>selected<?php } ?>><?php echo gettext($cur_value[0]) ?>
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
			  <td class="bgcolor_002" align="left" ><font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("OPTIONS"); ?></font></td>
			  <td class="bgcolor_003" align="center">
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="20%"  class="fontstyle_searchoptions">
						<?php echo gettext("SHOW");?> :  						
				   </td>
				   <td width="80%"  class="fontstyle_searchoptions">				   		
				 <?php echo gettext("Answered Calls"); ?>
				  <input name="terminatecauseid" type="radio" value="ANSWER" <?php if((!isset($terminatecauseid))||($terminatecauseid=="ANSWER")){?>checked<?php }?> />
				  <?php echo gettext("All Calls"); ?>

				   <input name="terminatecauseid" type="radio" value="ALL" <?php if($terminatecauseid=="ALL"){?>checked<?php }?>/>
					</td>
				</tr>
				<tr class="bgcolor_005">
					<td  class="fontstyle_searchoptions">
						<?php echo gettext("RESULT");?> : 
				   </td>
				   <td  class="fontstyle_searchoptions">
					<?php echo gettext("Minutes");?><input type="radio" NAME="resulttype" value="min" <?php if((!isset($resulttype))||($resulttype=="min")){?>checked<?php }?>> - <?php echo gettext("Seconds");?> <input type="radio" NAME="resulttype" value="sec" <?php if($resulttype=="sec"){?>checked<?php }?>>
					</td>
				</tr>
				<tr>
					<td  class="fontstyle_searchoptions">
						<?php echo gettext("CURRENCY");?> :
					</td>
					<td  class="fontstyle_searchoptions">
					<select NAME="choose_currency" size="1" class="form_input_select" >
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
					<input class="form_input_button" value=" <?php echo gettext("Search");?> " type="submit">
	  			</td>
    		</tr>
	</table>
	</FORM>
</center>


<!-- ** ** ** ** ** Part to display the CDR ** ** ** ** ** -->
<center><?php echo gettext("Number of Calls");?> : <?php  if (is_array($list) && count($list)>0){ echo $nb_record; }else{echo "0";}?></center>
     <table width="<?php echo $FG_HTML_TABLE_WIDTH?>" border="0" align="center" cellpadding="0" cellspacing="0">
		<TR bgcolor="#ffffff"> 
          <TD class="callhistory_td11"> 
            <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
                <TR> 
                  <TD><SPAN style="COLOR: #ffffff; FONT-SIZE: 11px"><B><?php echo $FG_HTML_TABLE_TITLE?></B></SPAN></TD>
                </TR>
            </TABLE></TD>
        </TR>
        <TR> 
          <TD> <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
                <TR class="bgcolor_008">
				  <TD width="<?php echo $FG_ACTION_SIZE_COLUMN?>" align="center" class="tableBodyRight" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px"></TD>					
				  
                  <?php 
				  	if (is_array($list) && count($list)>0) {
					
				  	for($i=0;$i<$FG_NB_TABLE_COL;$i++) {
					?>
	                  <TD width="<?php echo $FG_TABLE_COL[$i][2]?>" align=middle class="tableBody" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px"> 
	                    <center><strong> 
	                    <?php  if (strtoupper($FG_TABLE_COL[$i][4])=="SORT"){?>
	                    <a href="<?php  echo $PHP_SELF."?customer=$customer&s=1&t=0&stitle=$stitle&atmenu=$atmenu&current_page=$current_page&order=".$FG_TABLE_COL[$i][1]."&sens="; if ($sens=="ASC"){echo"DESC";}else{echo"ASC";} 
						echo "&posted=$posted&Period=$Period&frommonth=$frommonth&fromstatsmonth=$fromstatsmonth&tomonth=$tomonth&tostatsmonth=$tostatsmonth&fromday=$fromday&fromstatsday_sday=$fromstatsday_sday&fromstatsmonth_sday=$fromstatsmonth_sday&today=$today&tostatsday_sday=$tostatsday_sday&tostatsmonth_sday=$tostatsmonth_sday&phonenumbertype=$phonenumbertype&sourcetype=$sourcetype&clidtype=$clidtype&channel=$channel&resulttype=$resulttype&phonenumber=$phonenumber&src=$src&clid=$clid&terminatecauseid=$terminatecauseid&choose_calltype=$choose_calltype";?>"> 
	                    <span class="liens"><?php  } ?>
	                    <?php echo $FG_TABLE_COL[$i][0]?> 
	                    <?php if ($order==$FG_TABLE_COL[$i][1] && $sens=="ASC"){?>
	                    &nbsp;<img src="<?php echo Images_Path_Main ?>/icon_up_12x12.GIF" width="12" height="12" border="0"> 
	                    <?php }elseif ($order==$FG_TABLE_COL[$i][1] && $sens=="DESC"){?>
	                    &nbsp;<img src="<?php echo Images_Path_Main ?>/icon_down_12x12.GIF" width="12" height="12" border="0"> 
	                    <?php }?>
	                    <?php  if (strtoupper($FG_TABLE_COL[$i][4])=="SORT"){?>
	                    </span></a> 
	                    <?php }?>
	                    </strong></center></TD>
				   <?php } ?>
				   		
                </TR>
                <TR> 
                  <TD bgColor="#e1e1e1" colSpan=<?php echo $FG_TOTAL_TABLE_COL?> height="1">
                </TR>
				<?php
				  	 $ligne_number=0;					 
				  	 foreach ($list as $recordset){ 
						 $ligne_number++;
						 $recordset[0] = display_GMT($recordset[0], $_SESSION["gmtoffset"], 1);
				?>
				
               		 <TR bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>"  onMouseOver="bgColor='#C4FFD7'" onMouseOut="bgColor='<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>'"> 
						<TD align="<?php echo $FG_TABLE_COL[$i][3]?>" class="tableBody"><?php  echo $ligne_number+$current_page*$FG_LIMITE_DISPLAY.".&nbsp;"; ?></TD>
							 
				  		<?php for($i=0;$i<$FG_NB_TABLE_COL;$i++){ ?>
						<?php 				
							if ($FG_TABLE_COL[$i][6]=="lie"){
									$instance_sub_table = new Table($FG_TABLE_COL[$i][7], $FG_TABLE_COL[$i][8]);
									$sub_clause = str_replace("%id", $recordset[$i], $FG_TABLE_COL[$i][9]);																																	
									$select_list = $instance_sub_table -> Get_list ($DBHandle, $sub_clause, null, null, null, null, null, null);
									
									
									$field_list_sun = preg_split('/,/',$FG_TABLE_COL[$i][8]);
									$record_display = $FG_TABLE_COL[$i][10];
									
									for ($l=1;$l<=count($field_list_sun);$l++){										
										$record_display = str_replace("%$l", $select_list[0][$l-1], $record_display);	
									}
								
							}elseif ($FG_TABLE_COL[$i][6]=="list"){
									$select_list = $FG_TABLE_COL[$i][7];
									$record_display = $select_list[$recordset[$i]][0];
							
							}else{
									$record_display = $recordset[$i];
							}
							
							if ( is_numeric($FG_TABLE_COL[$i][5]) && (strlen($record_display) > $FG_TABLE_COL[$i][5]) ) {
								$record_display = substr($record_display, 0, $FG_TABLE_COL[$i][5]-3)."";  
							}
							
							
				 		 ?>
                 		 <TD vAlign=top align="<?php echo $FG_TABLE_COL[$i][3]?>" class=tableBody><?php 
                 		 if (isset ($FG_TABLE_COL[$i][11]) && strlen($FG_TABLE_COL[$i][11])>1){
						 	call_user_func($FG_TABLE_COL[$i][11], $record_display);
						 }else{
						 	echo stripslashes($record_display);
						 }						 
						 ?></TD>
				 		 <?php  } ?>
                  
					</TR>
				<?php
					 }//foreach ($list as $recordset)
					 if ($ligne_number < $FG_LIMITE_DISPLAY)  $ligne_number_end=$ligne_number +2;
					 while ($ligne_number < $ligne_number_end){
					 	$ligne_number++;
				?>
					<TR bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>"> 
				  		<?php for($i=0;$i<$FG_NB_TABLE_COL;$i++){ 
				 		 ?>
                 		 <TD vAlign=top class=tableBody>&nbsp;</TD>
				 		 <?php  } ?>
                 		 <TD align="center" vAlign=top class=tableBodyRight>&nbsp;</TD>				
					</TR>
									
				<?php					 
					 } //END_WHILE
					 
				  }else{
				  		echo gettext("No data found !!!");
				  }//end_if
				 ?>
            </TABLE></td>
        </tr>
        <TR bgcolor="#ffffff"> 
          <TD bgColor=#ADBEDE height=16 style="PADDING-LEFT: 5px; PADDING-RIGHT: 3px"> 
			<TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
                <TR> 
                  <TD align="right"><SPAN style="COLOR: #ffffff; FONT-SIZE: 11px"><B> 
                    <?php if ($current_page>0){?>
                    <img src="<?php echo Images_Path_Main ?>/fleche-g.gif" width="5" height="10"> <a href="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php  echo ($current_page-1)?><?php  if (!is_null($letter) && ($letter!="")){ echo "&letter=$letter";} 
					echo "&customer=$customer&posted=$posted&Period=$Period&frommonth=$frommonth&fromstatsmonth=$fromstatsmonth&tomonth=$tomonth&tostatsmonth=$tostatsmonth&fromday=$fromday&fromstatsday_sday=$fromstatsday_sday&fromstatsmonth_sday=$fromstatsmonth_sday&today=$today&tostatsday_sday=$tostatsday_sday&tostatsmonth_sday=$tostatsmonth_sday&phonenumbertype=$phonenumbertype&sourcetype=$sourcetype&clidtype=$clidtype&channel=$channel&resulttype=$resulttype&phonenumber=$phonenumber&src=$src&clid=$clid&terminatecauseid=$terminatecauseid&choose_calltype=$choose_calltype";?>"> 
                    <?php echo gettext("PREVIOUS");?> </a> -
                    <?php }?>
                    <?php echo ($current_page+1);?> / <?php  echo $nb_record_max;?> 
                    <?php if ($current_page<$nb_record_max-1){?>
                    - <a href="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php  echo ($current_page+1)?><?php  if (!is_null($letter) && ($letter!="")){ echo "&letter=$letter";} 
					echo "&customer=$customer&posted=$posted&Period=$Period&frommonth=$frommonth&fromstatsmonth=$fromstatsmonth&tomonth=$tomonth&tostatsmonth=$tostatsmonth&fromday=$fromday&fromstatsday_sday=$fromstatsday_sday&fromstatsmonth_sday=$fromstatsmonth_sday&today=$today&tostatsday_sday=$tostatsday_sday&tostatsmonth_sday=$tostatsmonth_sday&phonenumbertype=$phonenumbertype&sourcetype=$sourcetype&clidtype=$clidtype&channel=$channel&resulttype=$resulttype&phonenumber=$phonenumber&src=$src&clid=$clid&terminatecauseid=$terminatecauseid&choose_calltype=$choose_calltype";?>"> 
                    <?php echo gettext("NEXT");?> </a> <img src="<?php echo Images_Path_Main ?>/fleche-d.gif" width="5" height="10">
                    </B></SPAN> 
                    <?php }?>
                  </TD>
            </TABLE></TD>
        </TR>
      </table>

<!-- ** ** ** ** ** Part to display the GRAPHIC ** ** ** ** ** -->
<br>

<?php 

if (is_array($list_total_day) && count($list_total_day)>0){

$mmax=0;
$totalcall==0;
$totalminutes=0;
foreach ($list_total_day as $data){
	if ($mmax < $data[1]) $mmax=$data[1];
	$totalcall+=$data[3];
	$totalminutes+=$data[1];
	$totalcost+=$data[2];
}

?>


<!-- TITLE GLOBAL -->
<center>
 <table border="0" cellspacing="0" cellpadding="0" width="80%"><tr><td align="left" height="30">
		<table cellspacing="0" cellpadding="1" bgcolor="#000000" width="50%"><tr><td>
			<table cellspacing="0" cellpadding="0" width="100%">
				<tr><td class="callhistory_td1" align="left" ><?php echo gettext("SUMMARY");?></td></tr>
			</table>
		</td></tr></table>
 </td></tr></table>

<!-- FIN TITLE GLOBAL MINUTES //-->

<table border="0" cellspacing="0" cellpadding="0"  width="80%">
<tr><td bgcolor="#000000">			
	<table border="0" cellspacing="1" cellpadding="2" width="100%">
	<tr>
		<td align="center" class="callhistory_td2"></td>
    	<td class="callhistory_td3" align="center" colspan="5"><?php echo gettext("CALLING CARD MINUTES");?></td>
    </tr>
	<tr>
		<td align="center" class="callhistory_td3"><?php echo gettext("DATE");?></td>
        <td align="center" class="callhistory_td2"><?php echo gettext("DURATION");?></td>
		<td align="center" class="callhistory_td2"><?php echo gettext("GRAPHIC");?></td>
		<td align="center" class="callhistory_td2"><?php echo gettext("CALLS");?></td>
		<td align="center" class="callhistory_td2"><acronym title="<?php echo gettext("AVERAGE LENGTH OF CALL");?>"><?php echo gettext("ALOC");?></acronym></font></td>
		<td align="center" class="callhistory_td2"><?php echo gettext("TOTAL COST");?></td>

		<!-- LOOP -->
	<?php
		$i=0;
		foreach ($list_total_day as $data) {	
			$i=($i+1)%2;
			$tmc = $data[1]/$data[3];
			
			if ((!isset($resulttype)) || ($resulttype=="min")){  
				$tmc = sprintf("%02d",intval($tmc/60)).":".sprintf("%02d",intval($tmc%60));		
			}else{
				$tmc =intval($tmc);
			}
			
			if ((!isset($resulttype)) || ($resulttype=="min")){  
				$minutes = sprintf("%02d",intval($data[1]/60)).":".sprintf("%02d",intval($data[1]%60));
			}else{
				$minutes = $data[1];
			}
			if ($mmax>0) 	$widthbar= intval(($data[1]/$mmax)*200); 
			
		?>
			</tr><tr>
			<td align="right" class="sidenav" nowrap="nowrap"><font class="callhistory_td5"><?php echo $data[0]?></font></td>
			<td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i]?>" align="right" nowrap="nowrap" class="fontstyle_001"><?php echo $minutes?> </td>
	        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i]?>" align="left" nowrap="nowrap" width="<?php echo $widthbar+60?>">
		        <table cellspacing="0" cellpadding="0"><tr>
		        	<td bgcolor="#e22424"><img src="<?php echo Images_Path_Main ?>/spacer.gif" width="<?php echo $widthbar?>" height="6"></td>
		        </tr></table>
	        </td>
	        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i]?>" align="right" nowrap="nowrap" class="fontstyle_001"><?php echo $data[3]?></td>
	        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i]?>" align="right" nowrap="nowrap" class="fontstyle_001" ><?php echo $tmc?> </td>
			<td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i]?>" align="right" nowrap="nowrap" class="fontstyle_001"><?php  display_2bill($data[2]) ?></td>
     	<?php 	 
     	}	 	 	
	 	
		if ((!isset($resulttype)) || ($resulttype=="min")){  
			$total_tmc = sprintf("%02d",intval(($totalminutes/$totalcall)/60)).":".sprintf("%02d",intval(($totalminutes/$totalcall)%60));				
			$totalminutes = sprintf("%02d",intval($totalminutes/60)).":".sprintf("%02d",intval($totalminutes%60));
		}else{
			$total_tmc = intval($totalminutes/$totalcall);			
		}
	 
	 ?>                   	
	</tr>

	<!-- TOTAL -->
	<tr class="callhistory_td2">
		<td align="right" nowrap="nowrap" class="callhistory_td4"><?php echo gettext("TOTAL");?></td>
		<td align="center" nowrap="nowrap" colspan="2" class="callhistory_td4"><?php echo $totalminutes?> </td>
		<td align="center" nowrap="nowrap" class="callhistory_td4"><?php echo $totalcall?></td>
		<td align="center" nowrap="nowrap" class="callhistory_td4"><?php echo $total_tmc?></td>
		<td align="center" nowrap="nowrap" class="callhistory_td4"><?php  display_2bill($totalcost) ?></td>
	</tr>
	
	</table>
	  
</td></tr></table>

<?php  } else { ?>
	<center><h3><?php echo gettext("No calls in your selection");?>.</h3></center>
<?php  } ?>
</center>

<?php

$smarty->display( 'footer.tpl');


