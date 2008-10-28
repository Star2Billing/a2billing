<?php
include ("lib/customer.defines.php");
include ("lib/customer.module.access.php");
include ("lib/customer.smarty.php");


if (! has_rights (ACX_INVOICES)){
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}

getpost_ifset(array('customer', 'posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'dsttype', 'sourcetype', 'clidtype', 'channel', 'resulttype', 'stitle', 'atmenu', 'current_page', 'order', 'sens', 'dst', 'src', 'clid', 'fromstatsmonth_sday', 'fromstatsmonth_shour', 'tostatsmonth_sday', 'tostatsmonth_shour', 'srctype', 'src', 'choose_currency','exporttype','terminatecauseid'));

$customer = $_SESSION["pr_login"];
$vat = $_SESSION["vat"];

if (($_GET[download]=="file") && $_GET[file] ) 
{
	
	$value_de=base64_decode($_GET[file]);
	$dl_full = MONITOR_PATH."/".$value_de;
	$dl_name=$value_de;

	if (!file_exists($dl_full))
	{ 
		echo gettext("ERROR: Cannot download file $dl_full , it does not exist").'<br>';
		exit();
	} 
	
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$dl_name");
	header("Content-Length: ".filesize($dl_full));
	header("Accept-Ranges: bytes");
	header("Pragma: no-cache");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Content-transfer-encoding: binary");
	
	@readfile($dl_full);
	exit();

}


if (!isset ($current_page) || ($current_page == "")){	
	$current_page=0; 
}


// this variable specifie the debug type (0 => nothing, 1 => sql result, 2 => boucle checking, 3 other value checking)
$FG_DEBUG = 0;

// The variable FG_TABLE_NAME define the table name to use
$FG_TABLE_NAME="cc_call t1";

// THIS VARIABLE DEFINE THE COLOR OF THE HEAD TABLE
$FG_TABLE_HEAD_COLOR = "#D1D9E7";

$FG_TABLE_EXTERN_COLOR = "#7F99CC"; //#CC0033 (Rouge)
$FG_TABLE_INTERN_COLOR = "#EDF3FF"; //#FFEAFF (Rose)

// THIS VARIABLE DEFINE THE COLOR OF THE HEAD TABLE
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#FFFFFF";
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#F2F8FF";

$yesno = array(); 	$yesno["1"]  = array( "Yes", "1");	 $yesno["0"]  = array( "No", "0");

$DBHandle  = DbConnect();

// The variable Var_col would define the col that we want show in your table
// First Name of the column in the html page, second name of the field
$FG_TABLE_COL = array();


/*******
Calldate Clid Src Dst Dcontext Channel Dstchannel Lastapp Lastdata Duration Billsec Disposition Amaflags Accountcode Uniqueid Serverid
*******/

$FG_TABLE_COL[]=array (gettext("Calldate"), "starttime", "18%", "center", "SORT", "19", "", "", "", "", "", "");
$FG_TABLE_COL[]=array (gettext("Source"), "src", "10%", "center", "SORT", "30");
$FG_TABLE_COL[]=array (gettext("Callednumber"), "calledstation", "18%", "right", "SORT", "30", "", "", "", "", "", "");
$FG_TABLE_COL[]=array (gettext("Destination"), "destination", "18%", "center", "SORT", "30", "", "", "", "", "", "remove_prefix");
$FG_TABLE_COL[]=array (gettext("Duration"), "sessiontime", "8%", "center", "SORT", "30", "", "", "", "", "", "display_minute");


if (!(isset($customer)  &&  ($customer>0)) && !(isset($entercustomer)  &&  ($entercustomer>0))){
	$FG_TABLE_COL[]=array (gettext("Cardused"), "username", "11%", "center", "SORT", "30");
}

$FG_TABLE_COL[]=array (gettext("Cost"), "sessionbill", "9%", "center", "SORT", "30", "", "", "", "", "", "display_2bill");

$FG_TABLE_DEFAULT_ORDER = "t1.starttime";
$FG_TABLE_DEFAULT_SENS = "DESC";
	
// This Variable store the argument for the SQL query

$FG_COL_QUERY='t1.starttime, t1.src, t1.calledstation, t1.destination, t1.sessiontime  ';
if (!(isset($customer)  &&  ($customer>0)) && !(isset($entercustomer)  &&  ($entercustomer>0))){
	$FG_COL_QUERY.=', t1.username';
}
$FG_COL_QUERY.=', t1.sessionbill';
if (LINK_AUDIO_FILE == 'YES') 
	$FG_COL_QUERY .= ', t1.uniqueid';

$FG_COL_QUERY_GRAPH='t1.callstart, t1.duration';

// The variable LIMITE_DISPLAY define the limit of record to display by page
// $FG_LIMITE_DISPLAY=500;

// Number of column in the html table
$FG_NB_TABLE_COL=count($FG_TABLE_COL);

// The variable $FG_EDITION define if you want process to the edition of the database record
$FG_EDITION=true;

//This variable will store the total number of column
$FG_TOTAL_TABLE_COL = $FG_NB_TABLE_COL;
if ($FG_DELETION || $FG_EDITION) $FG_TOTAL_TABLE_COL++;

//This variable define the Title of the HTML table
$FG_HTML_TABLE_TITLE=" - Call Logs - ";

//This variable define the width of the HTML table
$FG_HTML_TABLE_WIDTH="70%";

	if ($FG_DEBUG == 3) echo "<br>Table : $FG_TABLE_NAME  	- 	Col_query : $FG_COL_QUERY";
	$instance_table = new Table($FG_TABLE_NAME, $FG_COL_QUERY);
	$instance_table_graph = new Table($FG_TABLE_NAME, $FG_COL_QUERY_GRAPH);


if ( is_null ($order) || is_null($sens) ){
	$order = $FG_TABLE_DEFAULT_ORDER;
	$sens  = $FG_TABLE_DEFAULT_SENS;
}

if ($posted==1){
  
  $SQLcmd = '';
  
  $SQLcmd = do_field($SQLcmd, 'src', 'src');
  $SQLcmd = do_field($SQLcmd, 'dst', 'calledstation');  
}
$date_clause='';
// Period (Month-Day)
if (DB_TYPE == "postgres"){		
	 	$UNIX_TIMESTAMP = "";
}else{
		$UNIX_TIMESTAMP = "UNIX_TIMESTAMP";
}
$lastdayofmonth = date("t", strtotime($tostatsmonth.'-01'));
if ($Period=="Month"){
		
		
		if ($frommonth && isset($fromstatsmonth)) $date_clause.=" AND $UNIX_TIMESTAMP(t1.starttime) >= $UNIX_TIMESTAMP('$fromstatsmonth-01')";
		if ($tomonth && isset($tostatsmonth)) $date_clause.=" AND $UNIX_TIMESTAMP(t1.starttime) <= $UNIX_TIMESTAMP('".$tostatsmonth."-$lastdayofmonth 23:59:59')"; 
		
}else{
		if ($fromday && isset($fromstatsday_sday) && isset($fromstatsmonth_sday) && isset($fromstatsmonth_shour) && isset($fromstatsmonth_smin) ) $date_clause.=" AND $UNIX_TIMESTAMP(t1.starttime) >= $UNIX_TIMESTAMP('$fromstatsmonth_sday-$fromstatsday_sday $fromstatsmonth_shour:$fromstatsmonth_smin')";
		if ($today && isset($tostatsday_sday) && isset($tostatsmonth_sday) && isset($tostatsmonth_shour) && isset($tostatsmonth_smin)) $date_clause.=" AND $UNIX_TIMESTAMP(t1.starttime) <= $UNIX_TIMESTAMP('$tostatsmonth_sday-".sprintf("%02d",intval($tostatsday_sday))." $tostatsmonth_shour:$tostatsmonth_smin')";
}

  
if (strpos($SQLcmd, 'WHERE') > 0) { 
	$FG_TABLE_CLAUSE = substr($SQLcmd,6).$date_clause; 
}elseif (strpos($date_clause, 'AND') > 0){
	$FG_TABLE_CLAUSE = substr($date_clause,5); 
}


if (!isset ($FG_TABLE_CLAUSE) || strlen($FG_TABLE_CLAUSE)==0){
		
		$cc_yearmonth = sprintf("%04d-%02d-%02d",date("Y"),date("n"),date("d")); 	
		$FG_TABLE_CLAUSE=" $UNIX_TIMESTAMP(t1.starttime) >= $UNIX_TIMESTAMP('$cc_yearmonth')";
}


if (strlen($FG_TABLE_CLAUSE)>0) $FG_TABLE_CLAUSE.=" AND ";
$FG_TABLE_CLAUSE.="t1.username='$customer'";


if (!isset($terminatecauseid)){
	$terminatecauseid="ALL";
}
if ($terminatecauseid=="ANSWER") {
	if (strlen($FG_TABLE_CLAUSE)>0) $FG_TABLE_CLAUSE.=" AND ";
	$FG_TABLE_CLAUSE.=" (t1.terminatecauseid=1) ";
}

$FG_TABLE_CLAUSE_NORMAL = $FG_TABLE_CLAUSE ." AND t1.sipiax not in (2,3)";

if (!$nodisplay){
	$list = $instance_table -> Get_list ($DBHandle, $FG_TABLE_CLAUSE_NORMAL, $order, $sens, null, null, $FG_LIMITE_DISPLAY, $current_page*$FG_LIMITE_DISPLAY);
}

$FG_TABLE_CLAUSE_DID = $FG_TABLE_CLAUSE ." AND t1.sipiax in (2,3)";

if (!$nodisplay){
	$list_did = $instance_table -> Get_list ($DBHandle, $FG_TABLE_CLAUSE_DID, $order, $sens, null, null, $FG_LIMITE_DISPLAY, $current_page*$FG_LIMITE_DISPLAY);
}
$_SESSION["pr_sql_export"]="SELECT $FG_COL_QUERY FROM $FG_TABLE_NAME WHERE $FG_TABLE_CLAUSE";
/************************/
$QUERY = "SELECT substring(t1.starttime,1,10) AS day, sum(t1.sessiontime) AS calltime, sum(t1.sessionbill) AS cost, count(*) as nbcall FROM $FG_TABLE_NAME WHERE ".$FG_TABLE_CLAUSE." GROUP BY substring(t1.starttime,1,10) ORDER BY day"; //extract(DAY from calldate)
//echo "$QUERY";

if (!$nodisplay)
{
	$list_total_day  = $instance_table->SQLExec ($DBHandle, $QUERY);
	if ($FG_DEBUG == 3) echo "<br>Clause : $FG_TABLE_CLAUSE";
	$nb_record = $instance_table -> Table_count ($DBHandle, $FG_TABLE_CLAUSE_NORMAL);
	$nb_record_did = $instance_table -> Table_count ($DBHandle, $FG_TABLE_CLAUSE_DID);
	if ($FG_DEBUG >= 1) var_dump ($list);
}//end IF nodisplay

// GROUP BY DESTINATION FOR THE INVOICE
$QUERY = "SELECT destination, sum(t1.sessiontime) AS calltime, 
sum(t1.sessionbill) AS cost, count(*) as nbcall FROM $FG_TABLE_NAME WHERE ".$FG_TABLE_CLAUSE."  GROUP BY destination";

if (!$nodisplay)
{
	$list_total_destination = $instance_table->SQLExec ($DBHandle, $QUERY);
}//end IF nodisplay

if ($nb_record<=$FG_LIMITE_DISPLAY){
	$nb_record_max=1;
}else{ 
	if ($nb_record % $FG_LIMITE_DISPLAY == 0){
		$nb_record_max=(intval($nb_record/$FG_LIMITE_DISPLAY));
	}else{
		$nb_record_max=(intval($nb_record/$FG_LIMITE_DISPLAY)+1);
	}	
}
if ($FG_DEBUG == 3) echo "<br>Nb_record : $nb_record";
if ($FG_DEBUG == 3) echo "<br>Nb_record_max : $nb_record_max";
/*************************************************************/

if ((isset($customer)  &&  ($customer>0)) || (isset($entercustomer)  &&  ($entercustomer>0))){

	$FG_TABLE_CLAUSE = "";
	if (isset($customer)  &&  ($customer>0)){		
		$FG_TABLE_CLAUSE =" username='$customer' ";
	}elseif (isset($entercustomer)  &&  ($entercustomer>0)){
		$FG_TABLE_CLAUSE =" username='$entercustomer' ";
	}
	$instance_table_customer = new Table("cc_card", "id,  username, lastname, firstname, address, city, state, country, zipcode, phone, email, fax");
	$info_customer = $instance_table_customer -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, "id", "ASC", null, null, null, null);
}
/************************************************************/

$date_clause='';

if ($Period=="Month"){		
		if ($frommonth && isset($fromstatsmonth)) $date_clause.=" AND $UNIX_TIMESTAMP(t1.creationdate) >= $UNIX_TIMESTAMP('$fromstatsmonth-01')";
		if ($tomonth && isset($tostatsmonth)) $date_clause.=" AND  $UNIX_TIMESTAMP(t1.creationdate) <= $UNIX_TIMESTAMP('".$tostatsmonth."-$lastdayofmonth 23:59:59')"; 
}else{
		if ($fromday && isset($fromstatsday_sday) && isset($fromstatsmonth_sday) && isset($fromstatsmonth_shour) && isset($fromstatsmonth_smin) ) $date_clause.=" AND  $UNIX_TIMESTAMP(t1.creationdate) >= $UNIX_TIMESTAMP('$fromstatsmonth_sday-$fromstatsday_sday $fromstatsmonth_shour:$fromstatsmonth_smin')";
		if ($today && isset($tostatsday_sday) && isset($tostatsmonth_sday) && isset($tostatsmonth_shour) && isset($tostatsmonth_smin)) $date_clause.=" AND  $UNIX_TIMESTAMP(t1.creationdate) <= $UNIX_TIMESTAMP('$tostatsmonth_sday-".sprintf("%02d",intval($tostatsday_sday))." $tostatsmonth_shour:$tostatsmonth_smin')";
}

$QUERY = "SELECT substring(t1.creationdate,1,10) AS day, sum(t1.amount) AS cost, count(*) as nbcharge FROM cc_charge t1 ".
		 " WHERE id_cc_card='".$_SESSION["card_id"]."' $date_clause GROUP BY substring(t1.creationdate,1,10) ORDER BY day"; //extract(DAY from calldate)

if (!$nodisplay)
{	
	$list_total_day_charge = $instance_table->SQLExec ($DBHandle, $QUERY);	 
}//end IF nodisplay
?>
<?php
$smarty->display( 'main.tpl');
$currencies_list = get_currencies();
?>

<!-- %%%%%%%%%%%%%%%%%Call Details Filter Code Here-->
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function formsubmit()
{
	if(document.calldataform.exporttype[1].checked == true)
	{
		document.calldataform.action="A2B_entity_call_details_pdf.php?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>";		
	}
	if(document.calldataform.exporttype[0].checked == true)
	{
		document.calldataform.action="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>";
	}
	document.calldataform.submit();
}

//-->
</script>


<!-- ** ** ** ** ** Part for the research ** ** ** ** ** -->
	
	<FORM name="calldataform" id="calldataform"  METHOD=POST>
	<INPUT TYPE="hidden" NAME="posted" value=1>
	<INPUT TYPE="hidden" NAME="current_page" value=0>	
		<table class="invoices_table1" align="center">
			<tbody>
			
			<tr>
        		<td class="bgcolor_004" align="left">

					<input type="radio" name="Period" value="Month" <?php  if (($Period=="Month") || !isset($Period)){ ?>checked="checked" <?php  } ?>> 
					<font face="verdana" size="1" color="#ffffff"><b><?php echo gettext("SELECT BY MONTH");?></b></font>
				</td>
      			<td class="bgcolor_005" align="left">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" >
					<tr><td class="fontstyle_searchoptions">
	  				<input type="checkbox" name="frommonth" value="true" <?php  if ($frommonth){ ?>checked<?php }?>> 
					<?php echo gettext("FROM");?> : <select name="fromstatsmonth" class="form_input_select">
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
								if ($fromstatsmonth=="$i-$month_formated"){$selected="selected";}else{$selected="";}
								echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";
							}
						}
					?>		
					</select>
					</td><td class="fontstyle_searchoptions">&nbsp;&nbsp;
					<input type="checkbox" name="tomonth" value="true" <?php  if ($tomonth){ ?>checked<?php }?>> 
					<?php echo gettext("TO");?> : <select name="tostatsmonth" class="form_input_select">
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
								if ($tostatsmonth=="$i-$month_formated"){$selected="selected";}else{$selected="";}
								echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";						   }
						}
					?>
					</select>
					</td></tr></table>
	  			</td>
    		</tr>
			
			<tr>
        		<td align="left" class="bgcolor_002">
					<input type="radio" name="Period" value="Day" <?php  if ($Period=="Day"){ ?>checked="checked" <?php  } ?>> 
					<font face="verdana" size="1" color="#ffffff"><b><?php echo gettext("SELECT BY DAY");?></b></font>
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
								if ($fromstatsmonth_sday=="$i-$month_formated"){$selected="selected";}else{$selected="";}
								echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";
							   }
						}
					?>
					</select>
					<select name="fromstatsmonth_shour" class="form_input_select">
					<?php  
						if (strlen($fromstatsmonth_shour)==0) $fromstatsmonth_shour='0';
						for ($i=0;$i<=23;$i++){	
							if ($fromstatsmonth_shour==sprintf("%02d",$i)){$selected="selected";}else{$selected="";}						
							echo '<option value="'.sprintf("%02d",$i)."\" $selected>".sprintf("%02d",$i).'</option>';
						}
					?>					
					</select>:<select name="fromstatsmonth_smin" class="form_input_select">
					<?php  
						if (strlen($fromstatsmonth_smin)==0) $fromstatsmonth_smin='0';
						for ($i=0;$i<=59;$i++){	
							if ($fromstatsmonth_smin==sprintf("%02d",$i)){$selected="selected";}else{$selected="";}						
							echo '<option value="'.sprintf("%02d",$i)."\" $selected>".sprintf("%02d",$i).'</option>';
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
					<select name="tostatsmonth_shour" class="form_input_select">
					<?php  
						if (strlen($tostatsmonth_shour)==0) $tostatsmonth_shour='23';
						for ($i=0;$i<=23;$i++){	
							if ($tostatsmonth_shour==sprintf("%02d",$i)){$selected="selected";}else{$selected="";}						
							echo '<option value="'.sprintf("%02d",$i)."\" $selected>".sprintf("%02d",$i).'</option>';
						}
					?>					
					</select>:<select name="tostatsmonth_smin" class="form_input_select">
					<?php  
						if (strlen($tostatsmonth_smin)==0) $tostatsmonth_smin='59';
						for ($i=0;$i<=59;$i++){	
							if ($tostatsmonth_smin==sprintf("%02d",$i)){$selected="selected";}else{$selected="";}						
							echo '<option value="'.sprintf("%02d",$i)."\" $selected>".sprintf("%02d",$i).'</option>';
						}
					?>					
					</select>
					</td></tr></table>
	  			</td>
    		</tr>
			<tr>
				<td class="bgcolor_004" align="left" >			
					<font face="verdana" size="1" color="#ffffff"><b>&nbsp;&nbsp;<?php echo gettext("DESTINATION");?></b></font>
				</td>				
				<td class="bgcolor_005" align="left" >
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td class="fontstyle_searchoptions">&nbsp;&nbsp;<INPUT TYPE="text" NAME="dst" value="<?php echo $dst?>" class="form_input_text"></td>
				<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="dsttype" value="1" <?php if((!isset($dsttype))||($dsttype==1)){?>checked<?php }?>> <?php echo gettext("Exact");?></td>
				<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="dsttype" value="2" <?php if($dsttype==2){?>checked<?php }?>><?php echo gettext("Begins with");?></td>
				<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="dsttype" value="3" <?php if($dsttype==3){?>checked<?php }?>><?php echo gettext("Contains");?></td>
				<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="dsttype" value="4" <?php if($dsttype==4){?>checked<?php }?>><?php echo gettext("Ends with");?></td>
				<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="dsttype" value="5" <?php if($sourcetype==5){?>checked<?php }?>><?php echo gettext("Is not");?></td>
				</tr></table></td>
			</tr>			
			<tr>
				<td align="left" class="bgcolor_002">					
					<font face="verdana" size="1" color="#ffffff"><b>&nbsp;&nbsp;<?php echo gettext("SOURCE");?></b></font>
				</td>				
				<td class="bgcolor_003" align="left" >
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td class="fontstyle_searchoptions">&nbsp;&nbsp;<INPUT TYPE="text" NAME="src" value="<?php echo "$src";?>" class="form_input_text"></td>
				<td class="fontstyle_searchoptions" align="center"><input type="radio" NAME="srctype" value="1" <?php if((!isset($srctype))||($srctype==1)){?>checked<?php }?>><?php echo gettext("Exact");?></td>
				<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="srctype" value="2" <?php if($srctype==2){?>checked<?php }?>><?php echo gettext("Begins with");?></td>
				<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="srctype" value="3" <?php if($srctype==3){?>checked<?php }?>><?php echo gettext("Contains");?></td>
				<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="srctype" value="4" <?php if($srctype==4){?>checked<?php }?>><?php echo gettext("Ends with");?></td>
				<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="srctype" value="5" <?php if($srctype==5){?>checked<?php }?>><?php echo gettext("Is not");?></td>
				</tr></table></td>
			</tr>
			<tr>
        		<td class="bgcolor_004" align="left"><font face="verdana" size="1" color="#ffffff"><b>&nbsp;&nbsp;<?php echo gettext("OPTIONS");?></b></font> </td>

				<td class="bgcolor_005" align="center" >
				
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="20%"  class="fontstyle_searchoptions">
						<?php echo gettext("RESULT");?> :  						
				   </td>
				   <td width="80%"  class="fontstyle_searchoptions">				   		
				 <?php echo gettext("Minutes");?><input type="radio" NAME="resulttype" value="min" <?php if((!isset($resulttype))||($resulttype=="min")){?>checked<?php }?>> - <?php echo gettext("Seconds");?> <input type="radio" NAME="resulttype" value="sec" <?php if($resulttype=="sec"){?>checked<?php }?>>
					</td>
				</tr>
				<tr class="bgcolor_005">
					<td  class="fontstyle_searchoptions">
						<?php echo gettext("EXPORT FORMAT");?> : 
				   </td>
				   <td  class="fontstyle_searchoptions">
					<?php echo gettext("See Invoice in HTML");?><input type="radio" NAME="exporttype" value="html" <?php if((!isset($exporttype))||($exporttype=="html")){?>checked<?php }?>>
					<?php echo gettext("or Export PDF");?> <input type="radio" NAME="exporttype" value="pdf" <?php if($exporttype=="pdf"){?>checked<?php }?>>
					</td>
				</tr>
				<tr class="bgcolor_005">
					<td  class="fontstyle_searchoptions">
						<?php echo gettext("SHOW");?> : 
				   </td>
				   <td  class="fontstyle_searchoptions">
					<?php echo gettext("Answered Calls"); ?>
				  <input name="terminatecauseid" type="radio" value="ANSWER" <?php if((!isset($terminatecauseid))||($terminatecauseid=="ANSWER")){?>checked<?php }?> />
				  <?php echo gettext("All Calls"); ?>

				   <input name="terminatecauseid" type="radio" value="ALL" <?php if($terminatecauseid=="ALL"){?>checked<?php }?>/>
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
			<tr>
				<td class="bgcolor_002" align="left">&nbsp;			
					
				</td>				
				<td class="bgcolor_003" align="left" >
				<center>
					<input class="form_input_button"  value=" <?php echo gettext("Search");?> " type="button"  onClick="formsubmit();">				
				</center>
				</td>
			</tr>			
		
		</tbody></table>
	
   
<br><br>
<?php
$currencies_list = get_currencies();

//calculate calls cost
$totalcost = 0;
$totalcallmade = 0;

$totalcost_did = $totalcost;
if (is_array($list_total_destination) && count($list_total_destination)>0)
{
	$totalcallmade = $totalcallmade + count($list_total_destination);
	$mmax=0;
	$totalcall=0;
	$totalminutes=0;	
	foreach ($list_total_destination as $data){	
		if ($mmax < $data[1]) $mmax=$data[1];
		$totalcall+=$data[3];
		$totalminutes+=$data[1];
		$totalcost+=$data[2];	
	}	
}
?>

<!-- %%%%%%%%%%%%%%%%%Call Details Filter ENDS Here-->
<!-- ################# Call Details            -->

<table  cellspacing="0" class="invoice_main_table">
     
      <tr>
        <td class="invoice_heading" width="100%"><?php echo gettext("Call Details");?></td>
      </tr>
      <tr>
        <td valign="top"><table width="60%" align="left" cellpadding="0" cellspacing="0">
            <tr>
              <td width="35%">&nbsp; </td>
              <td width="65%">&nbsp; </td>
            </tr>
            <tr>
              <td width="35%" class="invoice_td"><?php echo gettext("Name")?>&nbsp; : </td>
              <td width="65%" class="invoice_td"><?php echo $info_customer[0][3] ." ".$info_customer[0][2] ?></td>
            </tr>
            <tr>
              <td width="35%" class="invoice_td"><?php echo gettext("Card Number")?>&nbsp; :</td>
              <td width="65%" class="invoice_td"><?php echo $info_customer[0][1] ?> </td>
            </tr>
            <tr>
              <td width="35%" class="invoice_td"><?php echo gettext("As of Date")?>&nbsp; :</td>
              <td width="65%" class="invoice_td"><?php echo display_GMT(date('m/d/Y'), $_SESSION["gmtoffset"], 0);?> </td>
            </tr>
            <tr>
              <td colspan="2">&nbsp; </td>
            </tr>
        </table></td>
      </tr>	  
	  <tr>
	  <td>
	  <table width="100%" align="left" cellpadding="0" cellspacing="0">
   				<?php 
				if (is_array($list_total_destination) && count($list_total_destination)>0)
				{
				?>
				<tr>
				<td colspan="5" align="center"><font></font> <b><?php echo gettext("Calls by Destination")?></b></font> </td>
				</tr>

			<tr class="invoice_subheading">
              <td class="invoice_td" width="29%"><?php echo gettext("Destination")?> </td>
              <td width="19%" class="invoice_td"><?php echo gettext("Duration")?> </td>
			  <td width="20%" class="invoice_td"><?php echo gettext("Graphic")?> </td>
			  <td width="11%" class="invoice_td"><?php echo gettext("Calls")?> </td>
              <td width="21%" class="invoice_td" align="right"><?php echo gettext("Amount")." (".BASE_CURRENCY.")"; ?> </td>
            </tr>
			<?php  		
				$i=0;
				
				foreach ($list_total_destination as $data){	
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
            <tr class="invoice_rows">
              <td width="29%" class="invoice_td"><?php echo $data[0]?></td>
              <td width="19%" class="invoice_td"><?php echo $minutes?> </td>
			  <td width="20%" class="invoice_td"><img src="<?php echo Images_Path_Main ?>/sidenav-selected.gif" height="6" width="<?php echo $widthbar?>"> </td>
			  <td width="11%" class="invoice_td"><?php echo $data[3]?> </td>
              <td width="21%" align="right" class="invoice_td"><?php  display_2bill($data[2]) ?></td>
            </tr>
			<?php 	 }	 	 	
	 	
			if ((!isset($resulttype)) || ($resulttype=="min")){  
				$total_tmc = sprintf("%02d",intval(($totalminutes/$totalcall)/60)).":".sprintf("%02d",intval(($totalminutes/$totalcall)%60));				
				$totalminutes = sprintf("%02d",intval($totalminutes/60)).":".sprintf("%02d",intval($totalminutes%60));
			}else{
				$total_tmc = intval($totalminutes/$totalcall);			
			}
			 ?>   
			 <tr >
              <td width="29%" class="invoice_td">&nbsp;</td>
              <td width="19%" class="invoice_td">&nbsp;</td>
              <td width="20%" class="invoice_td">&nbsp; </td>
			  <td width="11%" class="invoice_td">&nbsp; </td>
			  <td width="21%" class="invoice_td">&nbsp; </td>
			  
            </tr>
            <tr class="invoice_subheading">
              <td width="29%" class="invoice_td"><?php echo gettext("TOTAL");?> </td>
              <td width="39%" class="invoice_td"colspan="2"><?php echo $totalminutes?></td>			  
			  <td width="11%" class="invoice_td"><?php echo $totalcall?> </td>
              <td width="21%" align="right" class="invoice_td"><?php  display_2bill($totalcost -$totalcost_did) ?> </td>
            </tr> 
			
            <tr >
              <td width="29%">&nbsp;</td>
              <td width="19%">&nbsp;</td>
              <td width="20%">&nbsp; </td>
			  <td width="11%">&nbsp; </td>
			  <td width="21%">&nbsp; </td>
			  
            </tr>			
			<?php }?>				
			<!-- Start Here ****************************************-->
			<?php 
				
				
				$mmax=0;
				$totalcall=0;
				$totalminutes=0;
				$totalcost_day=0;
				if (is_array($list_total_day) && count($list_total_day) > 0)
				{
				foreach ($list_total_day as $data){	
					if ($mmax < $data[1]) $mmax=$data[1];
					$totalcall+=$data[3];
					$totalminutes+=$data[1];
					$totalcost_day+=$data[2];
				}
				?>
				<tr>
				<td colspan="5" align="center"><b><?php echo gettext("Calls by Date")?></b> </td>
				</tr>
			  <tr class="invoice_subheading">
              <td class="invoice_td" width="29%"><?php echo gettext("Date")?> </td>
              <td width="19%" class="invoice_td"><?php echo gettext("Duration")?> </td>
			  <td width="20%" class="invoice_td"><?php echo gettext("Graphic")?> </td>
			  <td width="11%" class="invoice_td"><?php echo gettext("Calls")?> </td>
              <td width="21%" class="invoice_td" align="right"><?php echo gettext("Amount")." (".BASE_CURRENCY.")"; ?> </td>
            </tr>
			<?php  		
				$i=0;
				
				foreach ($list_total_day as $data){	
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
            <tr class="invoice_rows">
              <td width="29%" class="invoice_td"><?php echo display_GMT($data[0], $_SESSION["gmtoffset"], 0);?></td>
              <td width="19%" class="invoice_td"><?php echo $minutes?> </td>
			  <td width="20%" class="invoice_td"><img src="<?php echo Images_Path_Main ?>/sidenav-selected.gif" height="6" width="<?php echo $widthbar?>"> </td>
			  <td width="11%" class="invoice_td"><?php echo $data[3]?> </td>
              <td width="21%" align="right" class="invoice_td"><?php  display_2bill($data[2]) ?></td>
            </tr>
			 <?php 	 }	 	 	
	 	
				if ((!isset($resulttype)) || ($resulttype=="min")){  
					$total_tmc = sprintf("%02d",intval(($totalminutes/$totalcall)/60)).":".sprintf("%02d",intval(($totalminutes/$totalcall)%60));				
					$totalminutes = sprintf("%02d",intval($totalminutes/60)).":".sprintf("%02d",intval($totalminutes%60));
				}else{
					$total_tmc = intval($totalminutes/$totalcall);			
				}
			 
			 ?>               
			 <tr >
              <td width="29%" class="invoice_td">&nbsp;</td>
              <td width="19%" class="invoice_td">&nbsp;</td>
              <td width="20%" class="invoice_td">&nbsp; </td>
			  <td width="11%" class="invoice_td">&nbsp; </td>
			  <td width="21%" class="invoice_td">&nbsp; </td>
			  
            </tr>
            <tr class="invoice_subheading">
              <td width="29%" class="invoice_td"><?php echo gettext("TOTAL");?> </td>
              <td width="39%" class="invoice_td"colspan="2"><?php echo $totalminutes?></td>			  
			  <td width="11%" class="invoice_td"><?php echo $totalcall?> </td>
              <td width="21%" align="right" class="invoice_td"><?php  display_2bill($totalcost_day) ?> </td>
            </tr> 				   
            <tr >
              <td width="29%">&nbsp;</td>
              <td width="19%">&nbsp;</td>
              <td width="20%">&nbsp; </td>
			  <td width="11%">&nbsp; </td>
			  <td width="21%">&nbsp; </td>			  
            </tr>		
			<?php
			 	}
				?>
			<!-- END HERE ******************************************-->
        </table>
	  
	  </td>
	  </tr>
	  <tr class="invoice_subheading">
	 <td  align="right" class="invoice_td"><?php echo gettext("Total")?> = <?php  display_2bill($totalcost);?>&nbsp;</td>
	 </tr>
	  <tr class="invoice_subheading">
	 <td  align="right" class="invoice_td"><?php echo gettext("VAT")?> = <?php  
	 $prvat = ($vat / 100) * $totalcost;
	 display_2bill($prvat);?>&nbsp;</td>
	 </tr>	 
	 <tr class="invoice_subheading">
	 <td  align="right" class="invoice_td"><?php echo gettext("Grand Total")?> = <?php  display_2bill($totalcost + $prvat);?>&nbsp;</td>
	 </tr>
	 <tr>
	 <td>&nbsp;</td>
	 </tr>
	  
      <tr>
        <td valign="top"><table width="100%" align="left" cellpadding="0" cellspacing="0">
   				<tr>
				<td colspan="100" align="center"><font><b><?php echo gettext("No of Calls")?>:&nbsp;<?php  if (is_array($list) && count($list)>0){ echo $nb_record; }else{echo "0";}?></center></b></font> </td>
				</tr>

			<tr class="invoice_subheading">
              <td class="invoice_td" width="5%">nb </td>
			   <?php 
				  	if (is_array($list) && count($list)>0)
					{
					
				  		for($i=0;$i<$FG_NB_TABLE_COL;$i++)
						{ 
				?>				
				  
						  <TD width="<?php echo $FG_TABLE_COL[$i][2]?>" align=middle class="invoice_td" >
							<center>
							<?php echo $FG_TABLE_COL[$i][0]?>
						   
					 		</center></TD>
				   <?php } ?>		
				   <?php if ($FG_DELETION || $FG_EDITION)
				   {
				   ?>
				   <?php
				   } ?>	
            </tr>
			
			<?php
				  	 $ligne_number=0;					 
				  	 foreach ($list as $recordset)
					 { 
						 $ligne_number++;
			?>
			
            <tr class="invoice_rows">
             <TD align="<?php echo $FG_TABLE_COL[$i][3]?>" class="invoice_td_rightborder" ><?php  echo $ligne_number+$current_page*$FG_LIMITE_DISPLAY; ?></TD>
				  		<?php for($i=0;$i<$FG_NB_TABLE_COL;$i++){ 
							if ($FG_TABLE_COL[$i][6]=="lie"){
								$instance_sub_table = new Table($FG_TABLE_COL[$i][7], $FG_TABLE_COL[$i][8]);
								$sub_clause = str_replace("%id", $recordset[$i], $FG_TABLE_COL[$i][9]);																																	
								$select_list = $instance_sub_table -> Get_list ($DBHandle, $sub_clause, null, null, null, null, null, null);
									
									
								$field_list_sun = split(',',$FG_TABLE_COL[$i][8]);
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
							
							
							if ( is_numeric($FG_TABLE_COL[$i][5]) && (strlen($record_display) > $FG_TABLE_COL[$i][5])  ){
								$record_display = substr($record_display, 0, $FG_TABLE_COL[$i][5]-3)."";  
															
							}
							
				 		 ?>
                 		 <TD align="<?php echo $FG_TABLE_COL[$i][3]?>" class="invoice_td_rightborder"><?php
						 if (isset ($FG_TABLE_COL[$i][11]) && strlen($FG_TABLE_COL[$i][11])>1){
						 	call_user_func($FG_TABLE_COL[$i][11], $record_display);
						 }else{
						 		if($i == 0)
								{
									echo display_GMT($record_display, $_SESSION["gmtoffset"], 1);
								}
								else
								{
									echo stripslashes($record_display);
								}
						 }						 
						 ?></TD>
				 		 <?php  } 
						 
						 
					 }//foreach ($list as $recordset)
					 if ($ligne_number < $FG_LIMITE_DISPLAY)  $ligne_number_end=$ligne_number +2;
					 while ($ligne_number < $ligne_number_end){
					 	$ligne_number++;
				?>
					
									
				<?php					 
					 } //END_WHILE
					 
				  }else{
				  		echo gettext("No data found !!!");
				  }//end_if
				 ?>      					
              
            <tr >
              <td width="100%" colspan="100">&nbsp;</td>			  
            </tr>						
        </table></td>
      </tr>      
	  <tr>
	  <td valign="top">
	  <!-----------------------------------------------DID CALLS RECORDS---------------------------------------------->
	  
	  <table width="100%" align="left" cellpadding="0" cellspacing="0">
   				<tr>
				<td colspan="100" align="center"><font><b><?php echo gettext("DID Calls")?>&nbsp; ::&nbsp; <?php echo gettext("No of Calls")?>:&nbsp;<?php  if (is_array($list_did) && count($list_did)>0){ echo $nb_record_did; }else{echo "0";}?></center></b></font> </td>
				</tr>

			<tr class="invoice_subheading">
              <td class="invoice_td" width="5%">nb </td>
			   <?php 
				  	if (is_array($list_did) && count($list_did)>0)
					{
					
				  		for($i=0;$i<$FG_NB_TABLE_COL;$i++)
						{ 
				?>				
				  
						  <TD width="<?php echo $FG_TABLE_COL[$i][2]?>" align=middle class="invoice_td" >
							<center>
							<?php echo $FG_TABLE_COL[$i][0]?>
						   
					 		</center></TD>
				   <?php } ?>		
				   <?php if ($FG_DELETION || $FG_EDITION)
				   {
				   ?>
				   <?php
				   } ?>	
            </tr>
			
			<?php
				  	 $ligne_number=0;					 
				  	 foreach ($list_did as $recordset)
					 { 
						 $ligne_number++;
			?>
			
            <tr class="invoice_rows">
             <TD align="<?php echo $FG_TABLE_COL[$i][3]?>" class="invoice_td_rightborder" ><?php  echo $ligne_number+$current_page*$FG_LIMITE_DISPLAY; ?></TD>
				  		<?php for($i=0;$i<$FG_NB_TABLE_COL;$i++){ 
							if ($FG_TABLE_COL[$i][6]=="lie"){
								$instance_sub_table = new Table($FG_TABLE_COL[$i][7], $FG_TABLE_COL[$i][8]);
								$sub_clause = str_replace("%id", $recordset[$i], $FG_TABLE_COL[$i][9]);																																	
								$select_list = $instance_sub_table -> Get_list ($DBHandle, $sub_clause, null, null, null, null, null, null);
									
									
								$field_list_sun = split(',',$FG_TABLE_COL[$i][8]);
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
							
							
							if ( is_numeric($FG_TABLE_COL[$i][5]) && (strlen($record_display) > $FG_TABLE_COL[$i][5])  ){
								$record_display = substr($record_display, 0, $FG_TABLE_COL[$i][5]-3)."";  
															
							}
							
				 		 ?>
                 		 <TD align="<?php echo $FG_TABLE_COL[$i][3]?>" class="invoice_td_rightborder"><?php
						 if (isset ($FG_TABLE_COL[$i][11]) && strlen($FG_TABLE_COL[$i][11])>1){
						 	call_user_func($FG_TABLE_COL[$i][11], $record_display);
						 }else{
						 	echo stripslashes($record_display);
						 }						 
						 ?></TD>
				 		 <?php  } 
						 
						 
					 }//foreach ($list as $recordset)
					 if ($ligne_number < $FG_LIMITE_DISPLAY)  $ligne_number_end=$ligne_number +2;
					 while ($ligne_number < $ligne_number_end){
					 	$ligne_number++;
				?>
					
									
				<?php					 
					 } //END_WHILE
					 
				  }else{
				  		echo gettext("No data found !!!");
				  }//end_if
				 ?>      					
              
            <tr >
              <td width="100%" colspan="100">&nbsp;</td>			  
            </tr>						
        </table>
		
		<!-------------------------------END DID BILLING ------------------------------->
	  </td>
	  </tr>
    </table>


<!--################### Call Details Ends --> 


<?php  if($exporttype!="pdf"){ ?>

<?php
$smarty->display( 'footer.tpl');
?>

<?php  }else{
// EXPORT TO PDF

	$html = ob_get_contents();
	// delete output-Buffer
	ob_end_clean();
	
	$pdf = new HTML2FPDF();
	
	$pdf -> DisplayPreferences('HideWindowUI');
	
	$pdf -> AddPage();
	$pdf -> WriteHTML($html);
	
	$html = ob_get_contents();
	
	$pdf->Output('CC_invoice_'.date("d/m/Y-H:i").'.pdf', 'I');



} ?>
