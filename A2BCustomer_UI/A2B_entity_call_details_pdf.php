<?php
include ("lib/defines.php");
include ("lib/module.access.php");
include ("lib/smarty.php");



if (!$A2B->config["webcustomerui"]['invoice']) exit();

if (! has_rights (ACX_ACCESS)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();
}

getpost_ifset(array('customer', 'posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'dsttype', 'sourcetype', 'clidtype', 'channel', 'resulttype', 'stitle', 'atmenu', 'current_page', 'order', 'sens', 'dst', 'src', 'clid', 'fromstatsmonth_sday', 'fromstatsmonth_shour', 'tostatsmonth_sday', 'tostatsmonth_shour', 'srctype', 'src', 'choose_currency','exporttype','terminatecause'));

$customer = $_SESSION["pr_login"];
$vat = $_SESSION["vat"];
//require (LANGUAGE_DIR.FILENAME_INVOICES);

if ($exporttype=="pdf") 
{	
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=CallDetails_".date("d/m/Y-H:i").'.pdf');
	//header("Content-Length: ".filesize($dl_full));
	header("Accept-Ranges: bytes");
	header("Pragma: no-cache");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Content-transfer-encoding: binary");	
	
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
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#F2F8FF";
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#FFFFFF";

$yesno = array(); 	$yesno["1"]  = array( "Yes", "1");	 $yesno["0"]  = array( "No", "0");

$DBHandle  = DbConnect();

// The variable Var_col would define the col that we want show in your table
// First Name of the column in the html page, second name of the field
$FG_TABLE_COL = array();


/*******
Calldate Clid Src Dst Dcontext Channel Dstchannel Lastapp Lastdata Duration Billsec Disposition Amaflags Accountcode Uniqueid Serverid
*******/

$FG_TABLE_COL[]=array (gettext("Calldate"), "starttime", "18%", "center", "SORT", "19", "", "", "", "", "", "display_dateformat");
$FG_TABLE_COL[]=array (gettext("Source"), "src", "10%", "center", "SORT", "30");
$FG_TABLE_COL[]=array (gettext("Callednumber"), "calledstation", "18%", "right", "SORT", "30", "", "", "", "", "", "");
$FG_TABLE_COL[]=array (gettext("Destination"), "destination", "18%", "center", "SORT", "30", "", "", "", "", "", "remove_prefix");
$FG_TABLE_COL[]=array (gettext("Duration"), "sessiontime", "8%", "center", "SORT", "30", "", "", "", "", "", "display_minute");

if (!(isset($customer)  &&  ($customer>0)) && !(isset($entercustomer)  &&  ($entercustomer>0))){
	$FG_TABLE_COL[]=array (gettext("Cardused"), "username", "11%", "center", "SORT", "30");
}

$FG_TABLE_COL[]=array (gettext("Cost"), "sessionbill", "9%", "center", "SORT", "30", "", "", "", "", "", "display_2bill");

// ??? cardID
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
$FG_LIMITE_DISPLAY=500;

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
				$sql = "$sql t1.$dbfld";
				if (isset ($$fldtype)){                
                        switch ($$fldtype) {							
							case 1:	$sql = "$sql='".$$fld."'";  break;
							case 2: $sql = "$sql LIKE '".$$fld."%'";  break;
							case 3: $sql = "$sql LIKE '%".$$fld."%'";  break;
							case 4: $sql = "$sql LIKE '%".$$fld."'";  break;
							case 5:	$sql = "$sql <> '".$$fld."'";  
						}
                }else{ $sql = "$sql LIKE '%".$$fld."%'"; }
		}
        return $sql;
  }  
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

if (isset($customer)  &&  ($customer>0)){
	if (strlen($FG_TABLE_CLAUSE)>0) $FG_TABLE_CLAUSE.=" AND ";
	$FG_TABLE_CLAUSE.="t1.username='$customer'";
}else{
	if (isset($entercustomer)  &&  ($entercustomer>0)){
		if (strlen($FG_TABLE_CLAUSE)>0) $FG_TABLE_CLAUSE.=" AND ";
		$FG_TABLE_CLAUSE.="t1.username='$entercustomer'";
	}
}

if (!isset($terminatecause)){
	$terminatecause="ALL";
}
if ($terminatecause=="ANSWER") {
	if (strlen($FG_TABLE_CLAUSE)>0) $FG_TABLE_CLAUSE.=" AND ";
	$FG_TABLE_CLAUSE.=" (t1.terminatecause='ANSWER' OR t1.terminatecause='ANSWERED') ";
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

if (!$nodisplay)
{
	$list_total_day = $instance_table->SQLExec ($DBHandle, $QUERY);	
	if ($FG_DEBUG == 3) echo "<br>Clause : $FG_TABLE_CLAUSE";
	$nb_record = $instance_table -> Table_count ($DBHandle, $FG_TABLE_CLAUSE_NORMAL);
	$nb_record_did = $instance_table -> Table_count ($DBHandle, $FG_TABLE_CLAUSE_DID);
	if ($FG_DEBUG >= 1) var_dump ($list);

}//end IF nodisplay

// GROUP BY DESTINATION FOR THE INVOICE
$QUERY = "SELECT destination, sum(t1.sessiontime) AS calltime, 
sum(t1.sessionbill) AS cost, count(*) as nbcall FROM $FG_TABLE_NAME WHERE ".$FG_TABLE_CLAUSE." GROUP BY destination";

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
if (!$nodisplay){
	$list_total_day_charge  = $instance_table->SQLExec ($DBHandle, $QUERY);
}//end IF nodisplay
?>
<?php
//$smarty->display( 'main.tpl');
if($exporttype == "pdf")
{
	require('pdf-invoices/html2pdf/html2fpdf.php');
   	ob_start();
}
$currencies_list = get_currencies();
?>

<!-- %%%%%%%%%%%%%%%%%Call Details Filter Code Here-->
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

//-->
</script>
<br><br>

<!-- %%%%%%%%%%%%%%%%%Call Details Filter ENDS Here-->
<!-- ################# Call Details            -->
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
<table cellpadding="0"  align="center">
<tr>
<td align="center">
<img src="<?php echo Images_Path;?>/asterisk01.jpg" align="middle">
</td>
</tr>
</table>
<br>
<center>
  <h4><font color="#FF0000"><?php echo gettext("Call Details for Card Number")?>&nbsp;<?php echo $info_customer[0][1] ?> </font></h4>
</center>
<br>
<br>

<table  cellspacing="0"  cellpadding="2" width="80%" align="center">
     
      <tr>
        <td  colspan="2"  width="100%" bgcolor="#FFFFCC"><font size="5" color="#FF0000"><?php echo gettext("Calls Details")?></font></td>
      </tr>
	  <tr>
              <td width="35%">&nbsp; </td>
              <td width="65%">&nbsp; </td>
            </tr>
            <tr>
              <td width="35%" ><font color="#003399"><?php echo gettext("Name")?>&nbsp; : </font></td>
              <td width="65%" ><font color="#003399"><?php echo $info_customer[0][3] ." ".$info_customer[0][2] ?></font></td>
            </tr>
            <tr>
              <td width="35%" ><font color="#003399"><?php echo gettext("Card Number")?>&nbsp; :</font></td>
              <td width="65%" ><font color="#003399"><?php echo $info_customer[0][1] ?></font> </td>
            </tr>           
            <tr>
              <td width="35%" ><font color="#003399"><?php echo gettext("As of Date")?>&nbsp; :</font></td>
              <td width="65%" ><font color="#003399"><?php echo date('m-d-Y');?> </font></td>
            </tr>
            <tr>
              <td colspan="2">&nbsp; </td>
       </tr>
	</table>
			<table width="80%">
			<tr>
		<td colspan="7">
		
		<table align="center" width="100%"> 
		<?php if (is_array($list_total_destination) && count($list_total_destination)>0){?>
			<tr>
				<td colspan="4" align="center"><font> <b><?php echo gettext("By Destination")?></b></font> </td>
			</tr>

			<tr bgcolor="#CCCCCC">
              <td  width="29%"><font color="#003399"><b><?php echo gettext("Destination")?> </b></font></td>
              <td width="38%" ><font color="#003399"><b><?php echo gettext("Duration")?></b></font> </td>
			 
			  <td width="12%" align="center" ><font color="#003399"><b><?php echo gettext("Calls")?> </b></font></td>
              <td   align="right"><font color="#003399"><b><?php echo gettext("Amount")." (".BASE_CURRENCY.")"; ?> </b></font></td>
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
              <td width="29%" ><font color="#003399"><?php echo $data[0]?></font></td>
              <td width="38%" ><font color="#003399"><?php echo $minutes?> </font></td>
			  
			  <td width="12%" align="right" ><font color="#003399"><?php echo $data[3]?></font> </td>
              <td  align="right" ><font color="#003399"><?php  display_2bill($data[2]) ?></font></td>
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
              <td width="29%" >&nbsp;</td>
              <td width="38%" >&nbsp;</td>              
			  <td width="12%" >&nbsp; </td>
			  <td  >&nbsp; </td>
			  
            </tr>
            <tr bgcolor="#CCCCCC">
              <td width="29%" ><font color="#003399"><?php echo gettext("TOTAL");?> </font></td>
              <td width="38%" ><font color="#003399"><?php echo $totalminutes?></font></td>			  
			  <td width="12%"  align="right"><font color="#003399"><?php echo $totalcall?> </font></td>
              <td  align="right" ><font color="#003399"><?php  display_2bill($totalcost - $totalcost_did) ?></font> </td>
            </tr>   
			         
            <tr >
              <td width="29%">&nbsp;</td>
              <td width="38%">&nbsp;</td>
              
			  <td width="12%">&nbsp; </td>
			  <td >&nbsp; </td>
			  
            </tr>			
			</table>
		
		</td>
		</tr>
		<?php }
		if (is_array($list_total_day) && count($list_total_day)>0){
		?>
		
		<tr>
		<td colspan="7">
		
		<table align="center" width="100%">
			<!-- Start Here ****************************************-->
			<?php 
				
				
				$mmax=0;
				$totalcall=0;
				$totalminutes=0;
				$totalcost_day=0;
				foreach ($list_total_day as $data){	
					if ($mmax < $data[1]) $mmax=$data[1];
					$totalcall+=$data[3];
					$totalminutes+=$data[1];
					$totalcost_day+=$data[2];
				}
				?>
				
				<tr>
				<td colspan="4" align="center"><b><?php echo gettext("By Date")?></b> </td>
				</tr>
			  <tr bgcolor="#CCCCCC">
              <td  width="29%"><font color="#003399"><b><?php echo gettext("Date")?></b> </font></td>
              <td width="38%" ><font color="#003399"><b><?php echo gettext("Duration")?></b> </font></td>
			  
			  <td width="12%" align="center" ><font color="#003399"><b><?php echo gettext("Calls")?></b> </font></td>
              <td width="21%"  align="right"><font color="#003399"><b><?php echo gettext("Amount")." (".BASE_CURRENCY.")"; ?></b> </font></td>
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
              <td width="29%" ><font color="#003399"><?php echo $data[0]?></font></td>
              <td width="38%" ><font color="#003399"><?php echo $minutes?> </font></td>
			  
			  <td width="12%"  align="right"><font color="#003399"><?php echo $data[3]?> </font></td>
              <td width="21%" align="right" ><font color="#003399"><?php  display_2bill($data[2]) ?></font></td>
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
              <td width="29%" >&nbsp;</td>
              <td width="38%" >&nbsp;</td>
              
			  <td width="12%" >&nbsp; </td>
			  <td width="21%" >&nbsp; </td>
			  
            </tr>
            <tr bgcolor="#CCCCCC">
              <td width="29%" ><font color="#003399"><?php echo gettext("TOTAL");?> </font></td>
              <td width="38%" ><font color="#003399"><?php echo $totalminutes?></font></td>			  
			  <td width="12%" align="right" ><font color="#003399"><?php echo $totalcall?></font> </td>
              <td width="21%" align="right" ><font color="#003399"><?php  display_2bill($totalcost_day) ?> </font></td>
            </tr>  
			
			<!-- END HERE ******************************************-->
     
    </table>
		
		</td>
		</tr>
		<?php
			 	}
				?>
		<tr>
		<td colspan="7">
		<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
		<td><img src="<?php echo Images_Path;?>/spacer.jpg" height="30" align="middle"></td>
	 </tr>
	 <tr bgcolor="#CCCCCC" >
	 <td  align="right"><font color="#003399"><b><?php echo gettext("VAT")?> = <?php 
	 $prvat = ($vat / 100) * $totalcost;
	 display_2bill($prvat);?>&nbsp;</b></font></td>
	 </tr>
	 <tr>
	 <td><img src="<?php echo Images_Path;?>/spacer.jpg" height="30" align="middle"></td>
	 </tr>
	 <tr bgcolor="#CCCCCC" >
	 <td  align="right"><font color="#003399"><b><?php echo gettext("Grand Total")?> = <?php echo display_2bill($totalcost + $prvat);?>&nbsp;</b></font></td>
	 </tr>
	 <tr>
	 <td><img src="<?php echo Images_Path;?>/spacer.jpg" height="30" align="middle"></td>
	 </tr>
		</table>
		</td>
		</tr>
			
			</table>
		<table  cellspacing="0"  cellpadding="2" width="80%" align="center">
		<!-- HERE IS THE CODE FOR -->
   		  <tr>
					<td colspan="100" align="center"><font><b><?php echo gettext("No of Calls")?>:&nbsp;<?php  if (is_array($list) && count($list)>0){ echo $nb_record; }else{echo "0";}?></center></b></font> </td>
			  </tr>
      		<tr bgcolor="#CCCCCC">
              <td  width="5%"><font color="#003399"><b><?php echo gettext("Sr")?>#</b></font> </td>
			   <?php 
				  	if (is_array($list) && count($list)>0)
					{
					
				  		for($i=0;$i<$FG_NB_TABLE_COL;$i++)
						{ 
				?>				
				  
						  <TD width="<?php echo $FG_TABLE_COL[$i][2]?>" align=middle  >
							<center>
							<font color="#003399"><b><?php echo $FG_TABLE_COL[$i][0]?></b></font>
						   
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
             <TD align="<?php echo $FG_TABLE_COL[$i][3]?>" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>" ><font color="#003399"><?php  echo $ligne_number+$current_page*$FG_LIMITE_DISPLAY; ?></font></TD>
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
                 		 <TD align="<?php echo $FG_TABLE_COL[$i][3]?>" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>"><font color="#003399"><?php
						 if (isset ($FG_TABLE_COL[$i][11]) && strlen($FG_TABLE_COL[$i][11])>1){
						 	call_user_func($FG_TABLE_COL[$i][11], $record_display);
						 }else{
						 	echo stripslashes($record_display);
						 }						 
						 ?></font></TD>
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
            </tr>  
            <tr >
              <td width="100%" colspan="100">&nbsp;</td>			  
            </tr>					
    </table>
<table  cellspacing="0"  cellpadding="2" width="80%" align="center">
   		  <tr>
					<td colspan="100" align="center"><font><b><?php echo gettext("No of DID Calls")?>:&nbsp;<?php  if (is_array($list_did) && count($list_did)>0){ echo $nb_record_did; }else{echo "0";}?></center></b></font> </td>
			  </tr>
      		<tr bgcolor="#CCCCCC">
              <td  width="5%"><font color="#003399"><b><?php echo gettext("Sr")?>#</b></font> </td>
			   <?php 
				  	if (is_array($list_did) && count($list_did)>0)
					{
					
				  		for($i=0;$i<$FG_NB_TABLE_COL;$i++)
						{ 
				?>				
				  
						  <TD width="<?php echo $FG_TABLE_COL[$i][2]?>" align=middle  >
							<center>
							<font color="#003399"><b><?php echo $FG_TABLE_COL[$i][0]?></b></font>
						   
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
             <TD align="<?php echo $FG_TABLE_COL[$i][3]?>" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>" ><font color="#003399"><?php  echo $ligne_number+$current_page*$FG_LIMITE_DISPLAY; ?></font></TD>
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
                 		 <TD align="<?php echo $FG_TABLE_COL[$i][3]?>" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>"><font color="#003399"><?php
						 if (isset ($FG_TABLE_COL[$i][11]) && strlen($FG_TABLE_COL[$i][11])>1){
						 	call_user_func($FG_TABLE_COL[$i][11], $record_display);
						 }else{
						 	echo stripslashes($record_display);
						 }						 
						 ?></font></TD>
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
            </tr>  
            <tr >
              <td width="100%" colspan="100">&nbsp;</td>			  
            </tr>					
    </table>

<!--################### Call Details Ends --> 


<?php  if($exporttype!="pdf"){ ?>

<?php
//$smarty->display( 'footer.tpl');
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
	
	$pdf->Output('CallDetails_'.date("d/m/Y-H:i").'.pdf', 'I');



} ?>
