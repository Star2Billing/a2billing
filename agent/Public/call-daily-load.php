<?php
include_once(dirname(__FILE__) . "/../lib/admin.defines.php");
include_once(dirname(__FILE__) . "/../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_CALL_REPORT)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();
}



getpost_ifset(array('current_page', 'fromstatsday_sday', 'fromstatsmonth_sday', 'days_compare', 'min_call', 'posted',  'dsttype', 'srctype', 'clidtype', 'channel', 'resulttype', 'stitle', 'atmenu', 'current_page', 'order', 'sens', 'dst', 'src', 'clid', 'userfieldtype', 'userfield', 'accountcodetype', 'accountcode', 'customer', 'entercustomer','entertariffgroup' ,'enterprovider', 'entertrunk', 'enterratecard'));


if (!isset ($current_page) || ($current_page == "")){	
		$current_page=0; 
	}

// this variable specifie the debug type (0 => nothing, 1 => sql result, 2 => boucle checking, 3 other value checking)
$FG_DEBUG = 0;

// The variable FG_TABLE_NAME define the table name to use
$FG_TABLE_NAME="cc_call t1 LEFT OUTER JOIN cc_trunk t3 ON t1.id_trunk = t3.id_trunk";

if ($_SESSION["is_admin"]==0){
 	//$FG_TABLE_NAME.=", cc_card t2";
}

// THIS VARIABLE DEFINE THE COLOR OF THE HEAD TABLE
$FG_TABLE_HEAD_COLOR = "#D1D9E7";


$FG_TABLE_EXTERN_COLOR = "#7F99CC"; //#CC0033 (Rouge)
$FG_TABLE_INTERN_COLOR = "#EDF3FF"; //#FFEAFF (Rose)




// THIS VARIABLE DEFINE THE COLOR OF THE HEAD TABLE
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#FFFFFF";
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#F2F8FF";



//$link = DbConnect();
$DBHandle  = DbConnect();

// The variable Var_col would define the col that we want show in your table
// First Name of the column in the html page, second name of the field
$FG_TABLE_COL = array();



/*******
Calldate Clid Src Dst Dcontext Channel Dstchannel Lastapp Lastdata Duration Billsec Disposition Amaflags Accountcode Uniqueid Serverid
*******/

$FG_TABLE_COL[]=array (gettext("Calldate"), "starttime", "15%", "center", "SORT", "19", "", "", "", "", "", "display_dateformat");
//$FG_TABLE_COL[]=array ("Callend", "stoptime", "15%", "center", "SORT", "19");


//$FG_TABLE_COL[]=array ("Source", "source", "20%", "center", "SORT", "30");

$FG_TABLE_COL[]=array (gettext("CalledNumber"), "calledstation", "15%", "center", "SORT", "30", "", "", "", "", "", "remove_prefix");
$FG_TABLE_COL[]=array (gettext("Destination"), "destination", "15%", "center", "SORT", "30", "", "", "", "", "", "remove_prefix");
//$FG_TABLE_COL[]=array ("Country",  "calledcountry", "10%", "center", "SORT", "30", "lie", "country", "countryname", "countrycode='%id'", "%1");
//$FG_TABLE_COL[]=array ("Site", "site_id", "7%", "center", "sort", "15", "lie", "site", "name", "id='%id'", "%1");

$FG_TABLE_COL[]=array (gettext("Duration"), "sessiontime", "7%", "center", "SORT", "30", "", "", "", "", "", "display_minute");

$FG_TABLE_COL[]=array (gettext("CardUsed"), "username", "11%", "center", "SORT", "", "30", "", "", "", "", "linktocustomer");
$FG_TABLE_COL[]=array (gettext("terminatecause"), "terminatecause", "10%", "center", "SORT", "30");
$FG_TABLE_COL[]=array (gettext("IAX/SIP"), "sipiax", "6%", "center", "SORT",  "", "list", $yesno);
//$FG_TABLE_COL[]=array ("DestID", "destID", "12%", "center", "SORT", "30");

//if ($_SESSION["is_admin"]==1) $FG_TABLE_COL[]=array ("Con_charg", "connectcharge", "12%", "center", "SORT", "30");
//if ($_SESSION["is_admin"]==1) $FG_TABLE_COL[]=array ("Dis_charg", "disconnectcharge", "12%", "center", "SORT", "30");
//if ($_SESSION["is_admin"]==1) $FG_TABLE_COL[]=array ("Sec/mn", "secpermin", "12%", "center", "SORT", "30");


//if ($_SESSION["is_admin"]==1) $FG_TABLE_COL[]=array ("Buycosts", "buycosts", "12%", "center", "SORT", "30");
$FG_TABLE_COL[]=array (gettext("InitialRate"), "calledrate", "10%", "center", "SORT", "30", "", "", "", "", "", "display_2dec");
$FG_TABLE_COL[]=array (gettext("Cost"), "sessionbill", "10%", "center", "SORT", "30", "", "", "", "", "", "display_2bill");



// ??? cardID
$FG_TABLE_DEFAULT_ORDER = "t1.starttime";
$FG_TABLE_DEFAULT_SENS = "DESC";
	
// This Variable store the argument for the SQL query

$FG_COL_QUERY='t1.starttime, t1.calledstation, t1.destination, t1.sessiontime, t1.username, t1.terminatecause, t1.sipiax, t1.calledrate, t1.sessionbill';
// t1.stoptime,

$FG_COL_QUERY_GRAPH='t1.starttime, t1.sessiontime';

// The variable LIMITE_DISPLAY define the limit of record to display by page
$FG_LIMITE_DISPLAY=25;

// Number of column in the html table
$FG_NB_TABLE_COL=count($FG_TABLE_COL);

// The variable $FG_EDITION define if you want process to the edition of the database record
$FG_EDITION=true;

//This variable will store the total number of column
$FG_TOTAL_TABLE_COL = $FG_NB_TABLE_COL;
if ($FG_DELETION || $FG_EDITION) $FG_TOTAL_TABLE_COL++;

//This variable define the Title of the HTML table
$FG_HTML_TABLE_TITLE= gettext(" - Call Logs - ");

//This variable define the width of the HTML table
$FG_HTML_TABLE_WIDTH="90%";




if ($FG_DEBUG == 3) echo "<br>Table : $FG_TABLE_NAME  	- 	Col_query : $FG_COL_QUERY";
$instance_table = new Table($FG_TABLE_NAME, $FG_COL_QUERY);
$instance_table_graph = new Table($FG_TABLE_NAME, $FG_COL_QUERY_GRAPH);


if ( is_null ($order) || is_null($sens) ){
	$order = $FG_TABLE_DEFAULT_ORDER;
	$sens  = $FG_TABLE_DEFAULT_SENS;
}


if ($posted==1){
  $SQLcmd = '';
  
  //$SQLcmd = do_field($SQLcmd, 'src', 'source');
  $SQLcmd = do_field($SQLcmd, 'dst', 'calledstation');
  if ($_POST['before']) {
    if (strpos($SQLcmd, 'WHERE') > 0) { 	$SQLcmd = "$SQLcmd AND ";
    }else{     								$SQLcmd = "$SQLcmd WHERE "; }
    $SQLcmd = "$SQLcmd t1.starttime <'".$_POST['before']."'";
  }
  if ($_POST['after']) {    if (strpos($SQLcmd, 'WHERE') > 0) {      $SQLcmd = "$SQLcmd AND ";
  } else {      $SQLcmd = "$SQLcmd WHERE ";    }
    $SQLcmd = "$SQLcmd t1.starttime >'".$_POST['after']."'";
  }
  
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
			$SQLcmd .= "t1.id_tariffgroup = '$entertariffgroup'";
		}
		if (isset($enterratecard) && $enterratecard > 0) {
			if (strlen($SQLcmd) > 0) $SQLcmd .= " AND "; else $SQLcmd .= " WHERE ";
			$SQLcmd .= "t1.id_ratecard = '$enterratecard'";
		}
        
}


$date_clause='';
// Period (Month-Day)


if (!isset($fromstatsday_sday)){	
	$fromstatsday_sday = date("d");
	$fromstatsmonth_sday = date("Y-m");	
}



//if (isset($fromstatsday_sday) && isset($fromstatsmonth_sday)) $date_clause.=" AND calldate <= '$fromstatsmonth_sday-$fromstatsday_sday+23' AND calldate >= SUBDATE('$fromstatsmonth_sday-$fromstatsday_sday',INTERVAL $days_compare DAY)";

if (DB_TYPE == "postgres"){	
	if (isset($fromstatsday_sday) && isset($fromstatsmonth_sday)) $date_clause.=" AND t1.starttime < date'$fromstatsmonth_sday-$fromstatsday_sday'+ INTERVAL '1 DAY' AND t1.starttime >= date'$fromstatsmonth_sday-$fromstatsday_sday'";
}else{
	if (isset($fromstatsday_sday) && isset($fromstatsmonth_sday)) $date_clause.=" AND t1.starttime < ADDDATE('$fromstatsmonth_sday-$fromstatsday_sday',INTERVAL 1 DAY) AND t1.starttime >= '$fromstatsmonth_sday-$fromstatsday_sday'";  
}

if ($FG_DEBUG == 3) echo "<br>$date_clause<br>";


  
if (strpos($SQLcmd, 'WHERE') > 0) { 
	$FG_TABLE_CLAUSE = substr($SQLcmd,6).$date_clause; 
}elseif (strpos($date_clause, 'AND') > 0){
	$FG_TABLE_CLAUSE = substr($date_clause,5); 
}

if ($_POST['posted']==1){
	$list = $instance_table -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, $order, $sens, null, null, $FG_LIMITE_DISPLAY, $current_page*$FG_LIMITE_DISPLAY);
	
	$list_total = $instance_table_graph -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, null, null, null, null, null, null);
}


if ($FG_DEBUG == 3) echo "<br>Clause : $FG_TABLE_CLAUSE";
//$nb_record = $instance_table -> Table_count ($FG_TABLE_CLAUSE);
$nb_record = count($list_total);
if ($FG_DEBUG >= 1) var_dump ($list);



if ($nb_record<=$FG_LIMITE_DISPLAY){ 
	$nb_record_max=1;
}else{ 
	$nb_record_max=(intval($nb_record/$FG_LIMITE_DISPLAY)+1);
}

if ($FG_DEBUG == 3) echo "<br>Nb_record : $nb_record";
if ($FG_DEBUG == 3) echo "<br>Nb_record_max : $nb_record_max";


/*************************************************************/


$instance_table_customer = new Table("cc_card", "id,  username, lastname");

$FG_TABLE_CLAUSE = "";
/*if ($_SESSION["is_admin"]==0){ 	
	$FG_TABLE_CLAUSE =" IDmanager='".$_SESSION["pr_reseller_ID"]."'";	
}*/

$list_customer = $instance_table_customer -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, "id", "ASC", null, null, null, null);

$nb_customer = count($list_customer);

?>

<?php
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
				<td align="left" valign="top" class="bgcolor_004">
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
							<td align="left" class="fontstyle_searchoptions"><?php echo gettext("Rate");?> :</td>
							<td align="left" class="fontstyle_searchoptions"><INPUT TYPE="text" NAME="enterratecard" value="<?php echo $enterratecard?>" size="4" class="form_input_text">&nbsp;<a href="#" onclick="window.open('A2B_entity_def_ratecard.php?popup_select=2&popup_formname=myForm&popup_fieldname=enterratecard' , 'RatecardSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><img src="<?php echo Images_Path;?>/icon_arrow_orange.gif"></a></td>
						</tr>
					</table>
				</tr>
			</table>
			</td>
			</tr>
			<tr>
        		<td align="left" class="bgcolor_002">
					<font  class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("SELECT DAY");?></font>
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
					</td></tr></table>
	  			</td>
    		</tr>	
			
			<tr>
				<td class="bgcolor_004" align="left" >
					<font  class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("CALLEDNUMBER");?></font>
				</td>				
				<td class="bgcolor_005" align="left">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td class="fontstyle_searchoptions">&nbsp;&nbsp;<INPUT TYPE="text" NAME="dst" value="<?php echo $dst?>" class="form_input_text"></td>
				<td class="fontstyle_searchoptions"  align="center"><input type="radio" NAME="dsttype" value="1" <?php if((!isset($dsttype))||($dsttype==1)){?>checked<?php }?>><?php echo gettext("Exact");?></td>
				<td  class="fontstyle_searchoptions" align="center"><input type="radio" NAME="dsttype" value="2" <?php if($dsttype==2){?>checked<?php }?>><?php echo gettext("Begins with");?></td>
				<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="dsttype" value="3" <?php if($dsttype==3){?>checked<?php }?>><?php echo gettext("Contains");?></td>
				<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="dsttype" value="4" <?php if($dsttype==4){?>checked<?php }?>><?php echo gettext("Ends with");?></td>
				</tr></table></td>
			</tr>			
			

			<tr>
        		<td class="bgcolor_002" align="left"> </td>

				<td class="bgcolor_003" align="center">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					 <tr>
						<td align="center">							
							<input type="image"  name="image16" align="top" border="0" src="<?php echo Images_Path;?>/button-search.gif" />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td></tr></table>
	  			</td>
    		</tr>
		</tbody></table>
	</FORM>
</center>


<!-- ** ** ** ** ** Part to display the GRAPHIC ** ** ** ** ** -->
<br><br>

<?php 
if (is_array($list) && count($list)>0){

$table_graph=array();
$table_graph_hours=array();
$numm=0;
foreach ($list_total as $recordset){
		$numm++;
		$mydate= substr($recordset[0],0,10);
		$mydate_hours= substr($recordset[0],0,13);
		if (is_array($table_graph_hours[$mydate_hours])){
			$table_graph_hours[$mydate_hours][0]++;
			$table_graph_hours[$mydate_hours][1]=$table_graph_hours[$mydate_hours][1]+$recordset[1];
		}else{
			$table_graph_hours[$mydate_hours][0]=1;
			$table_graph_hours[$mydate_hours][1]=$recordset[1];
		}
		if (is_array($table_graph[$mydate])){
			$table_graph[$mydate][0]++;
			$table_graph[$mydate][1]=$table_graph[$mydate][1]+$recordset[1];
		}else{
			$table_graph[$mydate][0]=1;
			$table_graph[$mydate][1]=$recordset[1];
		}
}

$mmax=0;
$totalcall==0;
$totalminutes=0;
foreach ($table_graph as $tkey => $data){	
	if ($mmax < $data[1]) $mmax=$data[1];
	$totalcall+=$data[0];
	$totalminutes+=$data[1];
}

?>


<!-- TITLE GLOBAL -->
<center>
 <table border="0" cellspacing="0" cellpadding="0" width="80%"><tbody><tr><td align="left" height="30">
		<table cellspacing="0" cellpadding="1" bgcolor="#000000" width="50%"><tbody><tr><td>
			<table cellspacing="0" cellpadding="0" width="100%"><tbody>
				<tr><td  class="bgcolor_019" align="left"><font  class="fontstyle_003"><?php echo gettext("TOTAL");?></font></td></tr>
			</tbody></table>
		</td></tr></tbody></table>
 </td></tr></tbody></table>
		  
<!-- FIN TITLE GLOBAL MINUTES //-->
				
<table border="0" cellspacing="0" cellpadding="0" width="80%">
<tbody><tr><td bgcolor="#000000">			
	<table border="0" cellspacing="1" cellpadding="2" width="100%"><tbody>
	<tr>	
		<td align="center" class="bgcolor_019"></td>
    	<td  class="bgcolor_020" align="center" colspan="4"><font  class="fontstyle_003"><?php echo gettext("ASTERISK MINUTES");?></font></td>
    </tr>
	<tr  class="bgcolor_019">
		<td align="right" class="bgcolor_020"><font class="fontstyle_003"><?php echo gettext("DATE");?></font></td>
        <td align="center"><font class="fontstyle_003"><?php echo gettext("DURATION");?></font></td>
		<td align="center"><font class="fontstyle_003"><?php echo gettext("GRAPHIC");?></font></td>
		<td align="center"><font class="fontstyle_003"><?php echo gettext("CALLS");?></font></td>
		<td align="center"><font class="fontstyle_003"><acronym title="<?php echo gettext("Average Connection Time");?>"><?php echo gettext("ACT");?></acronym> </font></td>

		<!-- LOOP -->
	<?php  		
		$i=0;
		if ($FG_DEBUG == 3) print_r($table_graph);
		foreach ($table_graph as $tkey => $data){	
			$i=($i+1)%2;		
			$tmc = $data[1]/$data[0];
			$tmc_60 = sprintf("%02d",intval($tmc/60)).":".sprintf("%02d",intval($tmc%60));
			$minutes_60 = sprintf("%02d",intval($data[1]/60)).":".sprintf("%02d",intval($data[1]%60));
			if ((!$mmax) || (!$data[1])) $widthbar = 0;
			else $widthbar= intval(($data[1]/$mmax)*200);
	?>
		</tr><tr>
		<td align="right" class="sidenav" nowrap="nowrap"><font class="fontstyle_006"><?php echo $tkey?></font></td>
		<td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i]?>" align="right" nowrap="nowrap"><font class="fontstyle_006"><?php echo $minutes_60?> </font></td>
        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i]?>" align="left" nowrap="nowrap" width="<?php echo $widthbar+60?>">
        <table cellspacing="0" cellpadding="0"><tbody><tr>
        <td bgcolor="#e22424"><img src="images/spacer.gif" width="<?php echo $widthbar?>" height="6"></td>
        </tr></tbody></table></td>
        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i]?>" align="right" nowrap="nowrap"><font class="fontstyle_006"><?php echo $data[0]?></font></td>
        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i]?>" align="right" nowrap="nowrap"><font class="fontstyle_006"><?php echo $tmc_60?> </font></td>
     <?php 	 }	 
	 	$total_tmc_60 = sprintf("%02d",intval(($totalminutes/$totalcall)/60)).":".sprintf("%02d",intval(($totalminutes/$totalcall)%60));				
		$total_minutes_60 = sprintf("%02d",intval($totalminutes/60)).":".sprintf("%02d",intval($totalminutes%60));
	 
	 ?>                   	
	</tr>
	<!-- FIN DETAIL -->		
	
	<!-- FIN BOUCLE -->

	<!-- TOTAL -->
	<tr class="bgcolor_019">
		<td align="right" nowrap="nowrap"><font class="fontstyle_003"><?php echo gettext("TOTAL");?></font></td>
		<td align="center" nowrap="nowrap" colspan="2"><font class="fontstyle_003"><?php echo $total_minutes_60?></font></td>
		<td align="center" nowrap="nowrap"><font class="fontstyle_003"><?php echo $totalcall?></font></td>
		<td align="center" nowrap="nowrap"><font class="fontstyle_003"><?php echo $total_tmc_60?></font></td>                        
	</tr>
	<!-- FIN TOTAL -->

	</tbody></table>
	<!-- Fin Tableau Global //-->

</td></tr></tbody></table>
	<br>
 	<IMG SRC="graph_statbar.php?min_call=<?php echo $min_call?>&fromstatsday_sday=<?php echo $fromstatsday_sday?>&days_compare=<?php echo $days_compare?>&fromstatsmonth_sday=<?php echo $fromstatsmonth_sday?>&dsttype=<?php echo $dsttype?>&srctype=<?php echo $srctype?>&clidtype=<?php echo $clidtype?>&channel=<?php echo $channel?>&resulttype=<?php echo $resulttype?>&dst=<?php echo $dst?>&src=<?php echo $src?>&clid=<?php echo $clid?>&userfieldtype=<?php echo $userfieldtype?>&userfield=<?php echo $userfield?>&accountcodetype=<?php echo $accountcodetype?>&accountcode=<?php echo $accountcode?>&customer=<?php echo $customer?>&entercustomer=<?php echo $entercustomer?>&enterprovider=<?php echo $enterprovider?>&entertrunk=<?php echo $entertrunk?>&enterratecard=<?php echo $enterratecard?>&entertariffgroup=<?php echo $entertariffgroup?>" ALT="Stat Graph">



<!-- ** ** ** ** ** HOURLY LOAD ** ** ** ** ** -->
&nbsp;
<br/>
	<center><?php echo gettext("Select the hour interval to see the details");?>
	<FORM METHOD=POST ACTION="graph_hourdetail.php?posted=<?php echo $posted?>&min_call=<?php echo $min_call?>&fromstatsday_sday=<?php echo $fromstatsday_sday?>&days_compare=<?php echo $days_compare?>&fromstatsmonth_sday=<?php echo $fromstatsmonth_sday?>&dsttype=<?php echo $dsttype?>&srctype=<?php echo $srctype?>&clidtype=<?php echo $clidtype?>&channel=<?php echo $channel?>&resulttype=<?php echo $resulttype?>&dst=<?php echo $dst?>&src=<?php echo $src?>&clid=<?php echo $clid?>&userfieldtype=<?php echo $userfieldtype?>&userfield=<?php echo $userfield?>&accountcodetype=<?php echo $accountcodetype?>&accountcode=<?php echo $accountcode?>&enterratecard=<?php echo $enterratecard?>&entertariffgroup=<?php echo $entertariffgroup?>" target="superframe">
	<!-- ** ** ** ** ** HOURLY LOAD ** ** ** ** ** -->
		<table class="bar-status" width="60%" border="0" cellspacing="1" cellpadding="2" align="center">
			<tbody>		
			<tr>
			<td align="left"  class="bgcolor_004">
				<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("HOUR INTERVAL");?> :</font>
				</td>				
				<td class="bgcolor_005" align="center">
				<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>&nbsp;&nbsp;
				<select name="hourinterval" class="form_input_select">
					<?php  
						for ($i=0;$i<=23;$i++){							
							echo '<option value="'.sprintf("%02d",$i)."\"> Interval [".sprintf("%02d",$i).'h to '.sprintf("%02d",$i+1).'h] </option>';
						}
					?>					
					</select>
				
				</td>				
				</tr></table></td>
			</tr>

			<tr>
				<td align="left" class="bgcolor_002">					
					<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("TYPE GRAPH");?> :</font>
				</td>
				<td class="bgcolor_003" align="center">
				<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>&nbsp;&nbsp;
				<select name="typegraph" class="form_input_select">
					<option value="fluctuation"><?php echo gettext("FLUCTUATION GRAPH");?></option> <option value="watch-call" selected> <?php echo gettext("WATCH CALLS GRAPH");?></option>
				</select>
				
				</td>				
				</tr></table></td>
			</tr>
			<tr>
        		<td class="bgcolor_004" align="left"> </td>
				<td class="bgcolor_003" align="center">
					<input type="image"  name="image16" align="top" border="0" src="<?php echo Images_Path;?>/button-search.gif" />
	  			</td>
    		</tr>
		</tbody></table>
	</FORM>
</center>
<br>
<center>
    <iframe name="superframe" src="graph_hourdetail.php?posted=<?php echo $posted?>&min_call=<?php echo $min_call?>&fromstatsday_sday=<?php echo $fromstatsday_sday?>&days_compare=<?php echo $days_compare?>&fromstatsmonth_sday=<?php echo $fromstatsmonth_sday?>&dsttype=<?php echo $dsttype?>&srctype=<?php echo $srctype?>&clidtype=<?php echo $clidtype?>&channel=<?php echo $channel?>&resulttype=<?php echo $resulttype?>&dst=<?php echo $dst?>&src=<?php echo $src?>&clid=<?php echo $clid?>&userfieldtype=<?php echo $userfieldtype?>&userfield=<?php echo $userfield?>&accountcodetype=<?php echo $accountcodetype?>&accountcode=<?php echo $accountcode?>&customer=<?php echo $customer?>&entercustomer=<?php echo $entercustomer?>&enterprovider=<?php echo $enterprovider?>&entertrunk=<?php echo $entertrunk?>&enterratecard=<?php echo $enterratecard?>&entertariffgroup=<?php echo $entertariffgroup?>" BGCOLOR=white	width=770 height=800 marginWidth=0 marginHeight=0  frameBorder=0  scrolling=yes>

    </iframe>
</center>


</center>

<?php  }else{ ?>
	<center><h3><?php echo gettext("No calls in your selection");?>.</h3></center>
<?php  } ?>

<br><br>
<?php
        $smarty->display('footer.tpl');
?>
