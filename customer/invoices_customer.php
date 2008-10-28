<?php
include ("lib/customer.defines.php");
include ("lib/customer.module.access.php");
include ("lib/customer.smarty.php");



if (! has_rights (ACX_INVOICES)) { 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

getpost_ifset(array('customer', 'posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'dsttype', 'sourcetype', 'clidtype', 'channel', 'resulttype', 'stitle', 'atmenu', 'current_page', 'order', 'sens', 'dst', 'src', 'clid', 'fromstatsmonth_sday', 'fromstatsmonth_shour', 'tostatsmonth_sday', 'tostatsmonth_shour', 'srctype', 'src', 'choose_currency','exporttype'));

$customer = $_SESSION["pr_login"];
$vat = $_SESSION["vat"];

if (($_GET[download]=="file") && $_GET[file] ) {
	
	$value_de=base64_decode($_GET[file]);
	$dl_full = MONITOR_PATH."/".$value_de;
	$dl_name=$value_de;

	if (!file_exists($dl_full)) { 
		echo gettext("ERROR: Cannot download file"). $dl_full.", ".gettext("it does not exist").'<br>';
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

$DBHandle  = DbConnect();

$FG_TABLE_COL = array();
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
  
if (strpos($SQLcmd, 'WHERE') > 0) { 
	$FG_TABLE_CLAUSE = substr($SQLcmd,6).$date_clause; 
}elseif (strpos($date_clause, 'AND') > 0){
	$FG_TABLE_CLAUSE = substr($date_clause,5); 
}

if (strlen($FG_TABLE_CLAUSE)>0) $FG_TABLE_CLAUSE.=" AND ";
$FG_TABLE_CLAUSE.="t1.username='$customer'";

if (strlen($FG_TABLE_CLAUSE)>0)
{
	$FG_TABLE_CLAUSE.=" AND ";
}
$FG_TABLE_CLAUSE .=" t1.starttime >(Select CASE  WHEN max(cover_enddate) IS NULL THEN '0001-01-01 01:00:00' ELSE max(cover_enddate) END from cc_invoices WHERE cardid = ".$_SESSION["card_id"].")";


if (!$nodisplay){
	$list = $instance_table -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, $order, $sens, null, null, $FG_LIMITE_DISPLAY, $current_page*$FG_LIMITE_DISPLAY);
}
$_SESSION["pr_sql_export"]="SELECT $FG_COL_QUERY FROM $FG_TABLE_NAME WHERE $FG_TABLE_CLAUSE";

/************************/
$QUERY = "SELECT substring(t1.starttime,1,10) AS day, sum(t1.sessiontime) AS calltime, sum(t1.sessionbill) AS cost, count(*) as nbcall FROM $FG_TABLE_NAME WHERE ".$FG_TABLE_CLAUSE." GROUP BY substring(t1.starttime,1,10) ORDER BY day"; //extract(DAY from calldate)
//echo "$QUERY";

if (!$nodisplay)
{
	$list_total_day  = $instance_table->SQLExec ($DBHandle, $QUERY);
	$nb_record = $instance_table -> Table_count ($DBHandle, $FG_TABLE_CLAUSE);
}//end IF nodisplay


// GROUP BY DESTINATION FOR THE INVOICE

$QUERY = "SELECT destination, sum(t1.sessiontime) AS calltime, 
sum(t1.sessionbill) AS cost, count(*) as nbcall FROM $FG_TABLE_NAME WHERE ".$FG_TABLE_CLAUSE." GROUP BY destination";

if (!$nodisplay) {
	$list_total_destination  = $instance_table->SQLExec ($DBHandle, $QUERY);
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

if ($FG_DEBUG == 3) echo "<br>Nb_record : $nb_record";
if ($FG_DEBUG == 3) echo "<br>Nb_record_max : $nb_record_max";

if ((isset($customer)  &&  ($customer>0)) || (isset($entercustomer)  &&  ($entercustomer>0))){

	$FG_TABLE_CLAUSE = "";
	if (isset($customer)  &&  ($customer>0)) {
		$FG_TABLE_CLAUSE =" username='$customer' ";
	}  elseif (isset($entercustomer)  &&  ($entercustomer>0)) {
		$FG_TABLE_CLAUSE =" username='$entercustomer' ";
	}

	$instance_table_customer = new Table("cc_card", "id,  username, lastname, firstname, address, city, state, country, zipcode, phone, email, fax ,activated, vat, creationdate");
	$info_customer = $instance_table_customer -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, "id", "ASC", null, null, null, null);
}
/************************************************************/

/*
$QUERY = "SELECT substring(t1.creationdate,1,10) AS day, sum(t1.amount) AS cost, count(*) as nbcharge, t1.currency, t1.description FROM cc_charge t1 ".
		 " WHERE id_cc_card='".$_SESSION["card_id"]."' AND t1.creationdate >= (Select CASE WHEN max(cover_enddate) is NULL  THEN '0001-01-01 01:00:00' ELSE max(cover_enddate) END from cc_invoices) GROUP BY substring(t1.creationdate,1,10) ORDER BY day"; //extract(DAY from calldate)
*/
$QUERY = "SELECT substring(t1.creationdate,1,10) AS day, t1.amount AS cost, t1.currency, t1.currency, t1.description FROM cc_charge t1 ".
		 " WHERE id_cc_card='".$_SESSION["card_id"]."' AND t1.creationdate >= (Select CASE WHEN max(cover_enddate) is NULL  THEN '0001-01-01 01:00:00' ELSE max(cover_enddate) END from cc_invoices) ORDER BY day"; //extract(DAY from calldate)

if (!$nodisplay) {
	$list_total_day_charge  = $instance_table->SQLExec ($DBHandle, $QUERY);
}

$QUERY = "Select CASE WHEN max(cover_enddate) is NULL  THEN '0001-01-01 01:00:00' ELSE max(cover_enddate) END from cc_invoices WHERE cardid = ".$info_customer[0][0];

if (!$nodisplay) {
	$invoice_dates = $instance_table->SQLExec ($DBHandle, $QUERY);			 
}

if ($invoice_dates[0][0] == '0001-01-01 01:00:00') {
	$invoice_dates[0][0] = $info_customer[0][14];
}

if ($choose_currency == "") {
	$selected_currency = BASE_CURRENCY;
} else {
	$selected_currency = $choose_currency;
}
if($exporttype!="pdf"){
$currencies_list = get_currencies();
$smarty->display( 'main.tpl');


} else {
   require('pdf-invoices/html2pdf/html2fpdf.php');
   ob_start();

}


if($exporttype!="pdf") {

?>
<center>
	<FORM METHOD=POST ACTION="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>">
	<INPUT TYPE="hidden" NAME="posted" value=1>
	<INPUT TYPE="hidden" NAME="current_page" value=0>	
		<table class="invoices_table1">	
			<tr>
				<td class="bgcolor_004" align="left"><font face="verdana" size="1" color="#ffffff"><b>&nbsp;&nbsp;<?php echo gettext("OPTIONS");?></b></font> </td>
				<td class="bgcolor_005" align="center">
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
					<input class="form_input_button"  value=" <?php echo gettext("Search");?> " type="submit">					
				</center>
				</td>
			</tr>			
		</table>
	</FORM>
</center>





<table width="14%" align="center">
<tr>
<td height="93"> <img src="<?php echo Images_Path;?>/asterisk01.jpg"/> </td>
</tr>
</table>

<br>


<table  class="invoice_main_table">
 <tr>
	<td class="invoice_heading" ><?php echo gettext("Invoice Details"); ?></td>
 </tr>
 <tr>
        <td valign="top"><table width="60%" align="left" cellpadding="0" cellspacing="0">
            <tr>
              <td width="35%">&nbsp; </td>
              <td width="65%">&nbsp; </td>
            </tr>
            <tr>
              <td width="35%" class="invoice_td"><?php echo gettext("Name");?>&nbsp;: </td>
              <td width="65%" class="invoice_td"><?php echo $info_customer[0][3] ." ".$info_customer[0][2] ?></td>
            </tr>
            <tr>
              <td width="35%" class="invoice_td"><?php echo gettext("Card Number");?>&nbsp;:</td>
              <td width="65%" class="invoice_td"><?php echo $info_customer[0][1] ?> </td>
            </tr>           
            <tr>
              <td width="35%" class="invoice_td"><?php echo gettext("From Date");?>&nbsp;:</td>
              <td width="65%" class="invoice_td"><?php echo display_GMT($invoice_dates[0][0], $_SESSION["gmtoffset"], 0);?></td>
            </tr>
            <tr>
              <td >&nbsp;</td>
              <td >&nbsp;</td>
            </tr>
            
        </table></td>
      </tr>	   
	  <tr>
		  <td valign="top">
		  	<?php 
				if (is_array($list_total_day_charge) && count($list_total_day_charge)>0){
				
				$totalcharge=0;
				$totalcost=0;
				$total_extra_charges = 0;
				foreach ($list_total_day_charge as $data){
					$totalcharge+=$data[2];
					$total_extra_charges += convert_currency($currencies_list,$data[1], $data[3], $selected_currency);
				}
				
				?>
				<!-- FIN TITLE GLOBAL MINUTES //-->
				<table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
					<tr>	
						<td colspan="3" align="center"><b><?php echo gettext("Extra Charges");?></b></font></td>
					</tr>
					<tr class="invoice_subheading">
						<td class="invoice_td" align="center" width="20%"><?php echo gettext("DATE");?></td>
						<td class="invoice_td" align="center" width="60%"><?php echo gettext("DESCRIPTION");?></td>
						<td class="invoice_td" align="right" width="20%"><?php echo gettext("TOTALCOST");?></td>
					</tr>
					<?php
						$i=0;
						foreach ($list_total_day_charge as $data) {	
						$i=($i+1)%2;		
					?>
					<tr class="invoice_rows">
						<td align="center" class="invoice_td"><?php echo display_GMT($data[0], $_SESSION["gmtoffset"], 0);?></td>
						<td class="invoice_td" align="center"><?php echo $data[4]?></td>
						<td  class="invoice_td" align="right"><?php echo number_format(convert_currency($currencies_list, $data[1], $data[3], $selected_currency),3)." ".$selected_currency ?></td>
					</tr>	 
					<?php 
						}	 	 	
					?>  
					<tr>
						<td class="invoice_td">&nbsp;</td>
						<td class="invoice_td">&nbsp;</td>
						<td class="invoice_td">&nbsp;</td>
					</tr> 
					<tr class="invoice_subheading">
						<td class="invoice_td"><?php echo gettext("TOTAL");?></td>
						<td class="invoice_td" align="center"><?php echo gettext("NB CHARGE");?> : <?php echo $totalcharge?></td>
						<td class="invoice_td" align="right"><?php  display_2bill($total_extra_charges)?></td>
					</tr>
				</table>
					  
				<?php  } ?>			
				
		  </td>
	  </tr>
	  <?php 			
				$mmax=0;
				$totalcall=0;
				$totalminutes=0;
				$totalcost=0;
				if (is_array($list_total_destination) && count($list_total_destination)>0){
				foreach ($list_total_destination as $data){	
					if ($mmax < $data[1]) $mmax=$data[1];
					$totalcall+=$data[3];
					$totalminutes+=$data[1];
					$totalcost+=$data[2];
				}
				
				?>
	  <tr>
	  <td>&nbsp;</td>
	  </tr>
	  <tr>
	  <td>	  
	  		<table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
			<tr>				
				<td  align="center" colspan="5"><b><?php echo gettext("CALLS PER DESTINATION");?></b></td>
			</tr>
			<tr class="invoice_subheading">
				<td align="center" class="invoice_td"><?php echo gettext("DESTINATION");?></td>
				<td align="right" class="invoice_td"><?php echo gettext("DUR");?></td>
				<td align="center" class="invoice_td"><?php echo gettext("GRAPHIC");?>  </td>
				<td align="right" class="invoice_td"><?php echo gettext("CALL");?></td>
				<td align="right" class="invoice_td"><?php echo gettext("TOTALCOST");?></td>
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
				<td align="left" class="invoice_td"><?php echo $data[0]?></font></td>
				<td class="invoice_td" align="right"><?php echo $minutes?> </font></td>
				<td class="invoice_td" align="left">
					<img src="<?php echo Images_Path_Main ?>/sidenav-selected.jpg" height="6" width="<?php echo $widthbar?>">
				</td>
				<td class="invoice_td" align="right"><?php echo $data[3]?></td>				
				<td class="invoice_td" align="right"><?php  display_2bill($data[2]) ?></td>			                  	
			</tr>	
			 <?php 	 }	 	 	
				
				if ((!isset($resulttype)) || ($resulttype=="min")){
					$total_tmc = sprintf("%02d",intval(($totalminutes/$totalcall)/60)).":".sprintf("%02d",intval(($totalminutes/$totalcall)%60));				
					$totalminutes = sprintf("%02d",intval($totalminutes/60)).":".sprintf("%02d",intval($totalminutes%60));
				}else{
					$total_tmc = intval($totalminutes/$totalcall);			
				}
			 
			 ?> 
			 <tr>
				<td class="invoice_td">&nbsp;</td>
				<td class="invoice_td">&nbsp;</td>
				<td class="invoice_td">&nbsp;</td>
				<td class="invoice_td">&nbsp;</td>
				<td class="invoice_td">&nbsp;</td>
			</tr>
			
			<tr class="invoice_subheading">
				<td align="left" class="invoice_td" ><?php echo gettext("TOTAL");?></td>
				<td align="right"  class="invoice_td"><?php echo $totalminutes?> </td>				
				<td align="right" class="invoice_td" colspan="2"><?php echo $totalcall?></b></font></td>
				<td align="right" class="invoice_td"><?php  display_2bill($totalcost) ?></td>
			</tr>
	</table>	  
	  </td>
	  </tr>
	  <?php
	  }
	  ?>
	  <tr>
	  <td>&nbsp;</td>
	  </tr>	  
	  <tr>
	  <td>
	  
	   <?php 
	   $total_invoice_cost = $totalcost + $total_extra_charges;
			if (is_array($list_total_day) && count($list_total_day)>0){
			
			$mmax=0;
			$totalcall=0;
			$totalminutes=0;
			$totalcost=0;
			foreach ($list_total_day as $data){	
				if ($mmax < $data[1]) $mmax=$data[1];
				$totalcall+=$data[3];
				$totalminutes+=$data[1];
				$totalcost+=$data[2];
			}
			
			?>
			
			<!-- FIN TITLE GLOBAL MINUTES //-->
			<table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
				<tr>	
					<td align="center" colspan="5"><b><?php echo gettext("CALLS PER DAY");?></b> </td>
				</tr>
				<tr class="invoice_subheading">
					<td align="center" class="invoice_td"><?php echo gettext("DATE");?></td>
					<td align="right" class="invoice_td"><?php echo gettext("DUR");?> </td>
					<td align="center" class="invoice_td"><?php echo gettext("GRAPHIC");?> </td>
					<td align="right" class="invoice_td"><?php echo gettext("CALL");?></td>
					<td align="right" class="invoice_td"><?php echo gettext("TOTALCOST");?></td>			
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
					<td align="center"  class="invoice_td"><?php echo display_GMT($data[0], $_SESSION["gmtoffset"], 0);?></td>
					<td class="invoice_td" align="right"><?php echo $minutes?> </td>
					<td class="invoice_td" align="left">
						<img src="<?php echo Images_Path_Main ?>/sidenav-selected.jpg" height="6" width="<?php echo $widthbar?>">
					</td>
					<td class="invoice_td" align="right"><?php echo $data[3]?></font></td>
					<td class="invoice_td" align="right"><?php  display_2bill($data[2]) ?></td>
				 <?php 	 }	 	 	
					if ((!isset($resulttype)) || ($resulttype=="min")){
						$total_tmc = sprintf("%02d",intval(($totalminutes/$totalcall)/60)).":".sprintf("%02d",intval(($totalminutes/$totalcall)%60));				
						$totalminutes = sprintf("%02d",intval($totalminutes/60)).":".sprintf("%02d",intval($totalminutes%60));
					}else{
						$total_tmc = intval($totalminutes/$totalcall);			
					}
				 
				 ?>                   	
				</tr>	
				<tr >
					<td align="right">&nbsp;</td>
					<td align="center" colspan="2">&nbsp;</td>
					<td align="center">&nbsp;</td>
					<td align="center">&nbsp;</td>
				</tr>
				
				<tr class="invoice_subheading">
					<td align="left" class="invoice_td"><?php echo gettext("TOTAL");?></td>
					<td align="right"  class="invoice_td"><?php echo $totalminutes?> </td>
					<td align="center"  class="invoice_td">&nbsp; </td>
					<td align="right" class="invoice_td"><?php echo $totalcall?></td>
					<td align="right" class="invoice_td"><?php  display_2bill($totalcost) ?></td>
				</tr>
			</table>
				  
		<?php  } ?>
	  </td>
	  </tr>	  
	  <tr>
	  <td>&nbsp;</td>
	  </tr>
	  <?php  if (is_array($list) && count($list)>0){ ?>
	  <tr>
	  <td align="center"><b><?php echo gettext("Number of call");?> : <?php  if (is_array($list) && count($list)>0){ echo $nb_record; }else{echo "0";}?></b></td>
	  </tr>
	  <tr>
	  <td>	 
		<TABLE border=0 cellPadding=0 cellSpacing=0 width="100%" align="center">
                <TR class="invoice_subheading"> 
		  		<TD width="7%" class="invoice_td">nb</TD>					
                  <?php 
				  	if (is_array($list) && count($list)>0){
				  		for($i=0;$i<$FG_NB_TABLE_COL;$i++){ 
					?>				
                  <TD width="<?php echo $FG_TABLE_COL[$i][2]?>" align=middle class="invoice_td" > 
                    <center>
                    <?php echo $FG_TABLE_COL[$i][0]?> 
                  </center></TD>
				   <?php } ?>		
				   <?php if ($FG_DELETION || $FG_EDITION){ ?>
				   <?php } ?>		
                </TR>
				<?php
				  	 $ligne_number=0;					 
				  	 foreach ($list as $recordset){ 
						 $ligne_number++;
				?>
               		 <TR class="invoice_rows"> 
			<TD align="<?php echo $FG_TABLE_COL[$i][3]?>" class="invoice_td"><?php  echo $ligne_number+$current_page*$FG_LIMITE_DISPLAY; ?></TD>
				  		<?php for($i=0;$i<$FG_NB_TABLE_COL;$i++){ 
							if ($FG_TABLE_COL[$i][6]=="lie"){
								$instance_sub_table = new Table($FG_TABLE_COL[$i][7], $FG_TABLE_COL[$i][8]);
								$sub_clause = str_replace("%id", $recordset[$i], $FG_TABLE_COL[$i][9]);
								$select_list = $instance_sub_table -> Get_list ($DBHandle, $sub_clause, null, null, null, null, null, null);
								$field_list_sun = split(',',$FG_TABLE_COL[$i][8]);
								$record_display = $FG_TABLE_COL[$i][10];
								for ($l=1;$l<=count($field_list_sun);$l++){$record_display = str_replace("%$l", $select_list[0][$l-1], $record_display);	
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
                 		 <TD align="<?php echo $FG_TABLE_COL[$i][3]?>" class="invoice_td"><?php 
						 if (isset ($FG_TABLE_COL[$i][11]) && strlen($FG_TABLE_COL[$i][11])>1){
						 		call_user_func($FG_TABLE_COL[$i][11], $record_display);
						 }else{
						 		if($i == 0)
								{
									echo display_GMT($record_display, $_SESSION["gmtoffset"]);
								}
								else
								{
									echo stripslashes($record_display);
								}
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
					<TR> 
				  		<?php for($i=0;$i<$FG_NB_TABLE_COL;$i++){ 
				 		 ?>
                 		 <TD>&nbsp;</TD>
				 		 <?php  } ?>
                 		 <TD align="center">&nbsp;</TD>				
					</TR>
									
				<?php					 
					 } //END_WHILE
					 
				  }else{
				  		echo gettext("No data found !!!");				  
				  }//end_if
				 ?>
            </TABLE>
	  </td>
	  </tr>
	  <?php } ?>
	 <tr class="invoice_subheading">
	 <td  align="right" class="invoice_td"><?php echo gettext("Total");?> = <?php echo display_2bill($total_invoice_cost);?>&nbsp;</td>
	 </tr>
	 <tr class="invoice_subheading">
	 <td  align="right" class="invoice_td"><?php echo gettext("VAT");?> = <?php 
	 $prvat = ($info_customer[0][13] / 100) * $total_invoice_cost;
	 display_2bill($prvat);?>&nbsp;</td>
	 </tr>
	 <tr class="invoice_subheading">
	 <td  align="right" class="invoice_td"><?php echo gettext("Grand Total");?> = <?php echo display_2bill($total_invoice_cost + $prvat);?>&nbsp;</td>
	 </tr>
	 <tr>
	 <td>&nbsp;</td>
	 </tr>
	 <tr>
	 <td  align="left" ><b><?php echo gettext("Status");?></b> :&nbsp; 
	 <?php if($info_customer[0][12] == 't') {?>
			  <img width="18" height="7" src="<?php echo Images_Path;?>/connected.jpg">
	 <?php }
			else
			{
	 ?>
			  <img width="18" height="7" src="<?php echo Images_Path;?>/terminated.jpg">
	<?php 
			}
	?>
	 
	 &nbsp;</td>
	 </tr>
	  <tr >
	 <td>&nbsp;</td>
	 </tr>
</table>
<?php  }else{ ?>
<?php if (INVOICE_IMAGE != ""){ ?>
<table cellpadding="0"  align="center">
<tr>
<td align="center">
<img src="<?php echo Images_Path;?>/asterisk01.jpg" align="middle">
</td>
</tr>
</table>
<?php } ?>

<table cellspacing="0" cellpadding="2" align="center" width="80%" >
     
      <tr>
        <td colspan="2" bgcolor="#FFFFCC"><font size="5" color="#FF0000"><?php echo gettext("Invoice Details"); ?></font></td>
      </tr>
      <tr>
        <td valign="top" colspan="2"></td>
      </tr>	 
	<tr>
	  <td width="35%">&nbsp; </td>
	  <td >&nbsp; </td>
	</tr>
	<tr>
	  <td width="35%" ><font color="#003399"><?php echo gettext("Name")?>&nbsp; :</font> </td>
	  <td  ><font color="#003399"><?php echo $info_customer[0][3] ." ".$info_customer[0][2] ?></font></td>
	</tr>
	<tr>
	  <td width="35%" ><font color="#003399"><?php echo gettext("Card Number")?>&nbsp; :</font></td>
	  <td  ><font color="#003399"><?php echo $info_customer[0][1] ?></font> </td>
	</tr>           
	<tr>
	  <td width="35%" ><font color="#003399"><?php echo gettext("From Date")?>&nbsp; :</font></td>
	  <td  ><font color="#003399"><?php echo display_GMT($invoice_dates[0][0], $_SESSION["gmtoffset"], 0);?> </font></td>
	</tr>
	</table>
	<?php 	
			if (is_array($list_total_day_charge) && count($list_total_day_charge)>0){				
				$totalcharge=0;
				$totalcost=0;
				$total_extra_charges = 0;
				foreach ($list_total_day_charge as $data){	
					if ($mmax < $data[1]) $mmax=$data[1];
					$totalcharge+=$data[2];
					$totalcost+=$data[1];
					$total_extra_charges += convert_currency($currencies_list,$data[1], $data[3], $selected_currency);
				}
				
				?>
	<table align="center" width="80%"> 
	<tr>
		<td colspan="4" align="center"><font> <b><?php echo gettext("Extra Charges")?></b></font> </td>
	</tr>

			<tr bgcolor="#CCCCCC">
              <td  width="37%"><font color="#003399"><b><?php echo gettext("DATE")?> </b></font></td>
              <td width="41%" ><font color="#003399"><b><?php echo gettext("NB CHARGE")?></b></font> </td>			  
              <td   align="right"><font color="#003399"><b><?php echo gettext("AMOUNT")?> </b></font></td>
            </tr>
			<?php  		
						$i=0;
						foreach ($list_total_day_charge as $data){	
						$i=($i+1)%2;		
					?>
            <tr class="invoice_rows">
              <td width="37%" ><font color="#003399"><?php echo display_GMT($data[0], $_SESSION["gmtoffset"], 0);?></font></td>
              <td width="41%" ><font color="#003399"><?php echo $data[2]?> </font></td>			 
              <td  align="right" ><font color="#003399"><?php echo number_format(convert_currency($currencies_list, $data[1], $data[3], $selected_currency),3)." ".$selected_currency ?></font></td>
            </tr>
			  <?php } ?> 
			 <tr >
              <td width="37%" >&nbsp;</td>
              <td width="41%" >&nbsp;</td>              
			  <td width="22%" >&nbsp; </td>		  
            </tr>
            <tr bgcolor="#CCCCCC">
              <td width="37%" ><font color="#003399"><?php echo gettext("TOTAL");?> </font></td>
              <td width="41%" ><font color="#003399"><?php echo $totalcharge; ?></font></td>  
              <td align="right" ><font color="#003399"><?php  display_2bill($total_extra_charges); ?></font> </td>
            </tr>			
			
            <tr >
              <td width="37%">&nbsp;</td>
              <td width="41%">&nbsp;</td>              
			  <td width="22%">&nbsp; </td>			  
            </tr>			
			</table>
			<?php } ?>
			
			<!-- this is start of destination-->
			  <?php 			
				$mmax=0;
				$totalcall=0;
				$totalminutes=0;
				$totalcost=0;
				if (is_array($list_total_destination) && count($list_total_destination)>0){
				foreach ($list_total_destination as $data){	
					if ($mmax < $data[1]) $mmax=$data[1];
					$totalcall+=$data[3];
					$totalminutes+=$data[1];
					$totalcost+=$data[2];
				}
				
				?>
			<table align="center" width="80%"> 
	<tr>
		<td colspan="4" align="center"><font> <b><?php echo gettext("CALLS PER DESTINATION");?></b></font> </td>
	</tr>

			<tr bgcolor="#CCCCCC">
              <td  width="26%"><font color="#003399"><b><?php echo gettext("DESTINATION")?> </b></font></td>
              <td width="28%" ><font color="#003399"><b><?php echo gettext("DURATION")?></b></font> </td>			  
              <td   align="left"><font color="#003399"><b><?php echo gettext("CALL")?> </b></font></td>
			  <td   align="right"><font color="#003399"><b><?php echo gettext("TOTALCOST")?> </b></font></td>
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
              <td width="26%" ><font color="#003399"><?php echo display_GMT($data[0], $_SESSION["gmtoffset"], 0);?></font></td>
              <td width="28%" ><font color="#003399"><?php echo $minutes ?> </font></td>
			   <td width="30%" ><font color="#003399"><?php echo $data[3]?> </font></td>			 
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
              <td width="26%" >&nbsp;</td>
              <td width="28%" >&nbsp;</td>              
			  <td width="30%" >&nbsp; </td>
			  <td width="16%" >&nbsp; </td>		  
            </tr>
            <tr bgcolor="#CCCCCC">
              <td width="26%" ><font color="#003399"><?php echo gettext("TOTAL");?> </font></td>
              <td width="28%" ><font color="#003399"><?php echo $totalminutes; ?></font></td> 
			  <td width="30%" ><font color="#003399"><?php echo $totalcall; ?></font></td>  
              <td align="right" ><font color="#003399"><?php  display_2bill($totalcost); ?></font> </td>
            </tr>			
			
            <tr >
              <td width="26%">&nbsp;</td>
              <td width="28%">&nbsp;</td>              
			  <td width="30%">&nbsp; </td>			
			   <td width="16%">&nbsp; </td>			  
            </tr>			
			</table>
			<?php } ?>
			<!-- THIS IS END of destination-->
			<!-- This is start of per day-->
			  <?php 
	   $total_invoice_cost = $totalcost + $total_extra_charges;
			if (is_array($list_total_day) && count($list_total_day)>0){
			
			$mmax=0;
			$totalcall=0;
			$totalminutes=0;
			$totalcost=0;
			foreach ($list_total_day as $data){	
				if ($mmax < $data[1]) $mmax=$data[1];
				$totalcall+=$data[3];
				$totalminutes+=$data[1];
				$totalcost+=$data[2];
			}
			
			?>
			<table align="center" width="80%"> 
	<tr>
		<td colspan="4" align="center"><font> <b><?php echo gettext("CALLS PER DAY");?></b></font> </td>
	</tr>

			<tr bgcolor="#CCCCCC">
              <td  width="26%"><font color="#003399"><b><?php echo gettext("DESTINATION")?> </b></font></td>
              <td width="28%" ><font color="#003399"><b><?php echo gettext("DURATION")?></b></font> </td>			  
              <td   align="left"><font color="#003399"><b><?php echo gettext("CALL")?> </b></font></td>
			  <td   align="right"><font color="#003399"><b><?php echo gettext("TOTALCOST")?> </b></font></td>
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
              <td width="26%" ><font color="#003399"><?php echo $data[0]?></font></td>
              <td width="28%" ><font color="#003399"><?php echo $minutes ?> </font></td>
			   <td width="30%" ><font color="#003399"><?php echo $data[3]?> </font></td>			 
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
              <td width="26%" >&nbsp;</td>
              <td width="28%" >&nbsp;</td>              
			  <td width="30%" >&nbsp; </td>
			  <td width="16%" >&nbsp; </td>		  
            </tr>
            <tr bgcolor="#CCCCCC">
              <td width="26%" ><font color="#003399"><?php echo gettext("TOTAL");?> </font></td>
              <td width="28%" ><font color="#003399"><?php echo $totalminutes; ?></font></td> 
			  <td width="30%" ><font color="#003399"><?php echo $totalcall; ?></font></td>  
              <td align="right" ><font color="#003399"><?php  display_2bill($totalcost); ?></font> </td>
            </tr>			
			
            <tr >
              <td width="26%">&nbsp;</td>
              <td width="28%">&nbsp;</td>              
			  <td width="30%">&nbsp; </td>			
			   <td width="16%">&nbsp; </td>			  
            </tr>			
			</table>
			<?php } ?>
			<!-- THIS IS END of PER DAY-->
			
			<!-- This is start of calls list-->
			 
			<?php  if (is_array($list) && count($list)>0){ ?>
			<table align="center" width="80%"> 
			<tr>
				<td colspan="4" align="center"><font> <b><?php echo gettext("Number of call");?> : <?php  if (is_array($list) && count($list)>0){ echo $nb_record; }else{echo "0";}?></b></font> </td>
			</tr>

			<tr bgcolor="#CCCCCC">
              <td  width="7%"><font color="#003399"><b><?php echo gettext("nb")?> </b></font></td>
			  <?php 
				  	
				  		for($i=0;$i<$FG_NB_TABLE_COL;$i++){ 
					?>	
              <td align="center"><font color="#003399"><b><?php echo $FG_TABLE_COL[$i][0]?> </b></font> </td>
			  <?php } ?>		
			                
            </tr>
				<?php
				  	 $ligne_number=0;					 
				  	 foreach ($list as $recordset){ 
						 $ligne_number++;
				?>
            <tr class="invoice_rows">
              <td align="<?php echo $FG_TABLE_COL[$i][3]?>"><font color="#003399"><?php  echo $ligne_number+$current_page*$FG_LIMITE_DISPLAY; ?></font></td>
			  <?php for($i=0;$i<$FG_NB_TABLE_COL;$i++){ 
							if ($FG_TABLE_COL[$i][6]=="lie"){
								$instance_sub_table = new Table($FG_TABLE_COL[$i][7], $FG_TABLE_COL[$i][8]);
								$sub_clause = str_replace("%id", $recordset[$i], $FG_TABLE_COL[$i][9]);
								$select_list = $instance_sub_table -> Get_list ($DBHandle, $sub_clause, null, null, null, null, null, null);
								$field_list_sun = split(',',$FG_TABLE_COL[$i][8]);
								$record_display = $FG_TABLE_COL[$i][10];
								for ($l=1;$l<=count($field_list_sun);$l++){	$record_display = str_replace("%$l", $select_list[0][$l-1], $record_display);	
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
              <td align="<?php echo $FG_TABLE_COL[$i][3]?>"><font color="#003399"><?php 
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
						 ?> </font></td>
						  <?php  } ?>
			  
            </tr>
			<?php
					 }//foreach ($list as $recordset)
					 if ($ligne_number < $FG_LIMITE_DISPLAY)  $ligne_number_end=$ligne_number +2;
					 while ($ligne_number < $ligne_number_end){
					 	$ligne_number++;
				?>			  
			 <tr >
			 <?php for($i=0;$i<$FG_NB_TABLE_COL;$i++){ 
				 		 ?>
              <td >&nbsp;</td>
			  <?php  } ?>
              <td >&nbsp;</td>              
            </tr>			
			</table>
			<?php } 
			}
			?>
			<!-- THIS IS END of CALLs LIST-->
			<table align="center" width="80%">
			<tr bgcolor="#CCCCCC">
			<td width="100%" align="right"><font color="#003399"><b> <?php echo gettext("Total");?> = <?php echo display_2bill($total_invoice_cost);?>&nbsp;</font></b></td>
			</tr>
			<tr bgcolor="#CCCCCC">
			  <td align="right"><font color="#003399"><b><?php echo gettext("VAT");?> = <?php 
	 $prvat = ($info_customer[0][13] / 100) * $total_invoice_cost;
	 display_2bill($prvat);?>&nbsp;</font></b></td>
			  </tr>
			<tr bgcolor="#CCCCCC"><font color="#003399"><b>
			  <td align="right"><?php echo gettext("Grand Total");?> = <?php echo display_2bill($total_invoice_cost + $prvat);?>&nbsp;</font></td></b>
			  </tr>
			</table>
<?php  } ?>
<br><br>

<?php

if ($exporttype!="pdf") {

	$smarty->display('footer.tpl');

} else {
	
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
}

?>
