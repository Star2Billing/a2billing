<?php
include ("../lib/defines.php");
include ("../lib/module.access.php");
include ("../lib/smarty.php");


if (! has_rights (ACX_MISC)){
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}

getpost_ifset(array('posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'current_page', 'lst_time','trunks'));


$DBHandle  = DbConnect();

$date_clause = "";
$QUERY = '';
if (DB_TYPE == "postgres") 
{
	$UNIX_TIMESTAMP = "";
} else {
	$UNIX_TIMESTAMP = "UNIX_TIMESTAMP";
}

$lastdayofmonth = date("t", strtotime($tostatsmonth.'-01'));

if ($Period=="Month")
{
	if ($frommonth && isset($fromstatsmonth)) $date_clause.=" $UNIX_TIMESTAMP(t.creationdate) >= $UNIX_TIMESTAMP('$fromstatsmonth-01')";
	if ($tomonth && isset($tostatsmonth))
	{
		if (strlen($date_clause)>0) $date_clause.=" AND ";
		$date_clause.=" $UNIX_TIMESTAMP(t.creationdate) <= $UNIX_TIMESTAMP('".$tostatsmonth."-$lastdayofmonth 23:59:59')"; 
	}
} else if($Period=="Time") {
	if ($lst_time != "") 
	{
		if (strlen($date_clause)>0) $date_clause.=" AND ";
			if(DB_TYPE == "postgres"){
				switch($lst_time){
					case 1:
						$date_clause .= "CURRENT_TIMESTAMP - interval '1 hour' <= t.creationdate";
					break;
					case 2:
						$date_clause .= "CURRENT_TIMESTAMP - interval '6 hours' <= t.creationdate";
					break;
					case 3:
						$date_clause .= "CURRENT_TIMESTAMP - interval '1 day' <= t.creationdate";
					break;
					case 4:
						$date_clause .= "CURRENT_TIMESTAMP - interval '7 days' <= t.creationdate";
					break;
				}
			}else{
				switch($lst_time){
					case 1:
						$date_clause .= "DATE_SUB(NOW(),INTERVAL 1 HOUR) <= (t.creationdate)";
					break;
					case 2:
						$date_clause .= "DATE_SUB(NOW(),INTERVAL 6 HOUR) <= (t.creationdate)";
					break;
					case 3:
						$date_clause .= "DATE_SUB(NOW(),INTERVAL 1 DAY) <= (t.creationdate)";
					break;
					case 4:
						$date_clause .= "DATE_SUB(NOW(),INTERVAL 7 DAY) <= (t.creationdate)";
					break;
				}
			}
				
	}	
}else{
	if ($fromday && isset($fromstatsday_sday) && isset($fromstatsmonth_sday)) 
	{
		if (strlen($date_clause)>0) $date_clause.=" AND ";
		$date_clause.=" $UNIX_TIMESTAMP(t.creationdate) >= $UNIX_TIMESTAMP('$fromstatsmonth_sday-$fromstatsday_sday')";
	}
	if ($today && isset($tostatsday_sday) && isset($tostatsmonth_sday))
	{
		if (strlen($date_clause)>0) $date_clause.=" AND ";
		$date_clause.=" $UNIX_TIMESTAMP(t.creationdate) <= $UNIX_TIMESTAMP('$tostatsmonth_sday-".sprintf("%02d",intval($tostatsday_sday)/*+1*/)." 23:59:59')";
	}
}

if(DB_TYPE == "postgres"){

	$QUERY = "SELECT c.id_trunk,t.providerip, t.trunkcode, (SUM(extract(epoch from (stoptime - starttime))/60) / Count(c.id_trunk)) AS ALOC,  count(c.id_trunk ) AS total_calls, t.creationdate FROM cc_call c, cc_trunk t WHERE c.id_trunk = t.id_trunk";
	
	if($trunks != "")
	{
		$QUERY.=" AND c.id_trunk = '$trunks'";
	}
	
	if($date_clause != "")
	{
		$QUERY.=" AND ".$date_clause;
	}
	
	$QUERY .= " GROUP BY c.id_trunk,  t.providerip, t.trunkcode, c.stoptime, c.starttime, t.creationdate";
	
}else{

	$QUERY = "SELECT t.id_trunk, t.providerip, t.trunkcode, (SUM( TIME_TO_SEC( TIMEDIFF(c.stoptime, c.starttime ) ) ) / count(c.id_trunk )
	) ALOC, count(c.id_trunk ) total_calls, t.creationdate FROM cc_call c, cc_trunk t WHERE c.id_trunk = t.id_trunk";
	
	if($trunks != "")
	{
		$QUERY.=" AND c.id_trunk = '$trunks'";
	}
	
	if($date_clause != "")
	{
		$QUERY.=" AND ".$date_clause;
	}
	
	$QUERY .= " GROUP BY c.id_trunk";

}

$res = $DBHandle -> Execute($QUERY);
if ($res){
	$num = $res -> RecordCount( );		
	for($i=0;$i<$num;$i++)
	{		
		$trunk_calls [] =$res -> fetchRow();
	}
}

echo $QUERY;

// #### HEADER SECTION
$smarty->display('main.tpl');


// #### CREATE FORM OR LIST
//$HD_Form -> CV_TOPVIEWER = "menu";
if (strlen($_GET["menu"])>0) 
	$_SESSION["menu"] = $_GET["menu"];
	?>
<FORM METHOD=POST name="myForm" ACTION="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>">
	<INPUT TYPE="hidden" NAME="posted" value=1>
	<INPUT TYPE="hidden" NAME="current_page" value=0>	
		<table class="bar-status" width="85%" border="0" cellspacing="1" cellpadding="2" align="center">
			<tbody>
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
					</td></tr></table>
	  			</td>
    		</tr>
			<tr>
				<td class="bgcolor_002" align="left">		
				<input type="radio" name="Period" value="Time" <?php  if (($Period=="Time")){ ?>checked="checked" <?php  } ?>>	
					<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("Select Time");?></font>
				</td>				
				<td class="bgcolor_003" align="left">
				<select name="lst_time" style="width:100px;" class="form_input_select">
				<option value="" selected>Select Time</option>
				<option value="1" <?php if ($lst_time == 1) echo "selected"?>>Last 1 hour</option>
				<option value="2" <?php if ($lst_time == 2) echo "selected"?>>Last 6 hours</option>
				<option value="3" <?php if ($lst_time == 3) echo "selected"?>>Last day</option>
				<option value="4" <?php if ($lst_time == 4) echo "selected"?>>Last week</option>
				</select>
				</td>
			</tr>			
			<tr>
				<td class="bgcolor_002" align="left">			
					<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("Select Trunk");?></font>
				</td>				
				<td class="bgcolor_003" align="left">
				<?php
				  $DBHandle  = DbConnect();
				  $instance_table = new Table();
				    $QUERY = "SELECT id_trunk, trunkcode from cc_trunk"; 					
					$list_trunks  = $instance_table->SQLExec ($DBHandle, $QUERY);		
				 ?>
				<select name="trunks" class="form_input_select">
				<option value="" selected ><?php echo gettext("Select Trunk");?></option>
				<?php 
				foreach($list_trunks as $val){
				?>
				<option value="<?php echo $val[0]?>" <?php if($trunks == $val[0]) echo "selected"?>><?php echo $val[1]?></option>
				<?php 
				}
				?></select>
				</td>
			</tr>			

			<tr>
        		<td class="bgcolor_004" align="left" > </td>

				<td class="bgcolor_005" align="center" >
					<input type="image"  name="image16" align="top" border="0" src="<?php echo Images_Path;?>/button-search.gif" />
					
	  			</td>
    		</tr>
		</tbody></table>
</FORM>


			<table border="0" cellpadding="2" cellspacing="2" width="90%" align="center">
				<tbody>
				<?php if($num > 0){?>
					<tr class="form_head"> 
					 <td class="tableBody" style="padding: 2px;" align="center" width="4%"> 				
						<strong> 
							<font color="#ffffff">Trunk Name</font>
						</strong>
					</td>
					 <td class="tableBody" style="padding: 2px;" align="center" width="4%"> 				
						<strong> 
							<font color="#ffffff">Trunk IP</font>
						</strong>
					</td>
					 <td class="tableBody" style="padding: 2px;" align="center" width="4%"> 				
						<strong> 
							<font color="#ffffff">ASR</font>
						</strong>
					</td>
					 <td class="tableBody" style="padding: 2px;" align="center" width="4%"> 				
						<strong> 
							<font color="#ffffff">ALOC</font>
						</strong>
					</td>
					 <td class="tableBody" style="padding: 2px;" align="center" width="4%"> 				
						<strong> 
							<font color="#ffffff">CIC</font>
						</strong>
					</td>
                </tr>
		<?php
			$i = 0;
			foreach($trunk_calls as $key => $cur_val){
			$trunk_id = $cur_val[0];
			if(DB_TYPE == "postgres")
			{
				$QUERY_CIC = "SELECT count(c.id_trunk) AS CIC FROM cc_call c, cc_trunk t WHERE (extract(epoch from (stoptime - starttime))/60) <= 10 AND c.id_trunk = t.id_trunk AND c.id_trunk = $trunk_id group by c.id_trunk";
			} else {
				$QUERY_CIC = "SELECT count(c.id_trunk) AS CIC FROM cc_call c, cc_trunk t WHERE TIME_TO_SEC( TIMEDIFF(c.stoptime, c.starttime )) <= 10 AND c.id_trunk = t.id_trunk AND c.id_trunk = $trunk_id group by c.id_trunk";
			}
			$res_CIC = $DBHandle -> Execute($QUERY_CIC);
			$row_CIC = $res_CIC->fetchRow();
			$total_calls = $cur_val[4];
			$QUERY_ASR = "SELECT (count(c.id_trunk )/ $total_calls) AS ASR FROM cc_call c, cc_trunk t WHERE c.id_trunk = t.id_trunk AND c.terminatecause = 'ANSWER' AND c.id_trunk = $cur_val[0]";
			$res = $DBHandle -> Execute($QUERY_ASR);
			$row = $res->fetchRow();
			
			if($i % 2 == 0)
			{
				$bgcolor = "bgcolor='#F2F2EE'";$mouseout = "bgColor='#F2F2EE'";}else{$bgcolor = "bgcolor='#FCFBFB'";$mouseout = "bgColor='#FCFBFB'";
			}
			?>
               	 <tr onmouseover="bgColor='#FFDEA6'" onmouseout=<?=$mouseout?> <?=$bgcolor?>> 
					<td class="tableBody" align="center" valign="top"><?=$cur_val[1]?></td>
					<td class="tableBody" align="center" valign="top"><?=$cur_val[2]?></td>
					<td class="tableBody" align="center" valign="top"><?=$row[0]?></td>
					<td class="tableBody" align="center" valign="top"><?=round($cur_val[3])?>&nbsp;sec</td>
					<td class="tableBody" align="center" valign="top"><?=$row_CIC[0]?></td>
					</tr>
			<?php $i++;}?>
               	 <tr bgcolor="#fcfbfb"> 
					<td class="tableBody" align="center" valign="top">&nbsp;</td>
					<td class="tableBody" align="center" valign="top">&nbsp;</td>
					<td class="tableBody" align="center" valign="top">&nbsp;</td>
					<td class="tableBody" align="center" valign="top">&nbsp;</td>
					<td class="tableBody" align="center" valign="top">&nbsp;</td>
				</tr>
               	 <tr bgcolor="#fcfbfb"> 
					<td class="tableBody" align="center" valign="top">&nbsp;</td>
					<td class="tableBody" align="center" valign="top">&nbsp;</td>
					<td class="tableBody" align="center" valign="top">&nbsp;</td>
					<td class="tableBody" align="center" valign="top">&nbsp;</td>
					<td class="tableBody" align="center" valign="top">&nbsp;</td>
				</tr>
               	 <tr bgcolor="#fcfbfb"> 
					<td class="tableBody" align="center" valign="top">&nbsp;</td>
					<td class="tableBody" align="center" valign="top">&nbsp;</td>
					<td class="tableBody" align="center" valign="top">&nbsp;</td>
					<td class="tableBody" align="center" valign="top">&nbsp;</td>
					<td class="tableBody" align="center" valign="top">&nbsp;</td>
				</tr>
                <tr>
					<td class="tableDivider" colspan="5"><img src="../Public/templates/default/images/clear.gif" height="1" width="1"></td>
				</tr>
			<?php }else{?>
				<tr>
					<td colspan="5" align="center">No Record Found!</td>
				</tr>				
			<?php }?>
			</tbody>
</table>	
	<?

// #### FOOTER SECTION
$smarty->display('footer.tpl');
?>
