<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_INVOICING)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

getpost_ifset(array('customer', 'posted', 'Period', 'cardid','exporttype','choose_billperiod','id','invoice_type','payment_status'));

if ($invoice_type == "")  $invoice_type = 1;

if ($invoice_type == 1) {
	if($cardid == "" ) {
		exit("Invalid ID");
	}
}
if ( $invoice_type == 2) {
	if(($id == "" || !is_numeric($id))) {
		exit(gettext("Invalid ID"));
	}
}
if ($invoice_type == 1) {
	$invoice_heading = gettext("Unbilled Details");	
} else {
	$invoice_heading = gettext("Billed Details");
}

$DBHandle = DbConnect();
$num = 0;
if ($invoice_type == 1) {
	$QUERY = "Select username, vat, t1.id from cc_card t1 where t1.id = $cardid";
} else {
	$QUERY = "Select username, vat, t1.id from cc_card t1, cc_invoices t2 where t1.id = t2.cardid and t2.id = $id";
}
$res_user = $DBHandle -> Execute($QUERY);
if ($res_user)
	$num = $res_user -> RecordCount( );

if($num > 0) {
	$userRecord = $res_user -> fetchRow();				 
	$customer = $userRecord[0];	
	$vat = $userRecord[1];
	$customerID = $userRecord[2];	
} else {
	exit(gettext("No User found"));
}

if($payment_status != "") {
	$QUERY = "UPDATE cc_invoices SET payment_status ='$payment_status' WHERE id='$id'"; 
	$DBHandle -> Execute($QUERY);
}

// this variable specifie the debug type (0 => nothing, 1 => sql result, 2 => boucle checking, 3 other value checking)
$FG_DEBUG = 0;

// The variable FG_TABLE_NAME define the table name to use
$FG_TABLE_NAME="cc_call t1 , cc_prefix t2, cc_card t3";

$DBHandle  = DbConnect();

$FG_TABLE_COL = array();
$FG_TABLE_COL[]=array (gettext("Calldate"), "starttime", "18%", "center", "SORT", "19", "", "", "", "", "", "display_dateformat");
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

$FG_COL_QUERY='t1.starttime, t1.src, t1.calledstation, t2.destination, t1.sessiontime  ';
if (!(isset($customer)  &&  ($customer>0)) && !(isset($entercustomer)  &&  ($entercustomer>0))){
	$FG_COL_QUERY.=', t3.username';
}
$FG_COL_QUERY.=', t1.sessionbill';
if (LINK_AUDIO_FILE == 'YES') 
	$FG_COL_QUERY .= ', t1.uniqueid';

$FG_COL_QUERY_GRAPH='t1.callstart, t1.duration';

// The variable LIMITE_DISPLAY define the limit of record to display by page
$FG_LIMITE_DISPLAY=500;

// Number of column in the html table
$FG_NB_TABLE_COL=count($FG_TABLE_COL);

//This variable will store the total number of column
$FG_TOTAL_TABLE_COL = $FG_NB_TABLE_COL;
if ($FG_DELETION || $FG_EDITION) $FG_TOTAL_TABLE_COL++;

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


if (strpos($SQLcmd, 'WHERE') > 0) { 
	$FG_TABLE_CLAUSE = substr($SQLcmd,6).$date_clause; 
}elseif (strpos($date_clause, 'AND') > 0){
	$FG_TABLE_CLAUSE = substr($date_clause,5); 
}

if (isset($customer)  &&  ($customer>0)){
	if (strlen($FG_TABLE_CLAUSE)>0) $FG_TABLE_CLAUSE.=" AND ";
	$FG_TABLE_CLAUSE.="t3.username='$customer'";
}else{
	if (isset($entercustomer)  &&  ($entercustomer>0)){
		if (strlen($FG_TABLE_CLAUSE)>0) $FG_TABLE_CLAUSE.=" AND ";
		$FG_TABLE_CLAUSE.="t3.username='$entercustomer'";
	}
}

if (strlen($FG_TABLE_CLAUSE)>0)
{
	$FG_TABLE_CLAUSE.=" AND ";
}
$FG_TABLE_CLAUSE.=" id_cc_prefix = t2.id AND card_id = t3.id ";

if (strlen($FG_TABLE_CLAUSE)>0)
{
	$FG_TABLE_CLAUSE.=" AND ";
}
if ($invoice_type == 1)
{
	$FG_TABLE_CLAUSE.="t1.starttime >(Select CASE  WHEN max(cover_enddate) IS NULL THEN '0001-01-01 01:00:00' ELSE max(cover_enddate) END from cc_invoices WHERE cardid = '$cardid') ";
}
else
{
	$FG_TABLE_CLAUSE.="t1.starttime >(Select cover_startdate  from cc_invoices where id ='$id') AND t1.stoptime <(Select cover_enddate from cc_invoices where id ='$id') ";
}

if (!$nodisplay){
	$list = $instance_table -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, $order, $sens, null, null, $FG_LIMITE_DISPLAY, $current_page*$FG_LIMITE_DISPLAY);
}
$_SESSION["pr_sql_export"]="SELECT $FG_COL_QUERY FROM $FG_TABLE_NAME WHERE $FG_TABLE_CLAUSE";

/************************/

$QUERY = "SELECT substring(t1.starttime,1,10) AS day, sum(t1.sessiontime) AS calltime, sum(t1.sessionbill) AS cost, count(*) as nbcall FROM $FG_TABLE_NAME WHERE ".$FG_TABLE_CLAUSE."  GROUP BY substring(t1.starttime,1,10) ORDER BY day"; //extract(DAY from calldate)

if (!$nodisplay)
{
	$list_total_day = $instance_table->SQLExec ($DBHandle, $QUERY);	
	$nb_record = $instance_table -> Table_count ($DBHandle, $FG_TABLE_CLAUSE);
}//end IF nodisplay

/************************************************ DID Billing Section *********************************************/
// Fixed + Dial = 0
// Fixed = 1
// Dail = 2
// Free = 3


// 1. Billing Type:: All DID Calls that have DID Type 0 and 2
if ($invoice_type == 1)
{
	$QUERY = "SELECT t1.amount, t1.creationdate, t1.description, t3.countryname, t2.did, t1.currency ".
	" FROM cc_charge t1 LEFT JOIN (cc_did t2, cc_country t3 ) ON ( t1.id_cc_did = t2.id AND t2.id_cc_country = t3.id ) ".
	" WHERE (t1.chargetype = 1 OR t1.chargetype = 2) AND t1.id_cc_card = ".$cardid.
	" AND t1.creationdate >(Select CASE  WHEN max(cover_enddate) IS NULL THEN '0001-01-01 01:00:00' ELSE max(cover_enddate) END from cc_invoices)";
}
else
{
	$QUERY = "SELECT t1.amount, t1.creationdate, t1.description, t3.countryname, t2.did, t1.currency ".
	" FROM cc_charge t1 LEFT JOIN (cc_did t2, cc_country t3 ) ON ( t1.id_cc_did = t2.id AND t2.id_cc_country = t3.id ) ".
	" WHERE (t1.chargetype = 2 OR t1.chargetype = 1) AND t1.id_cc_card = ".$customerID.
	" AND t1.creationdate > (Select cover_startdate  from cc_invoices where id ='$id') AND t1.creationdate <(Select cover_enddate from cc_invoices where id ='$id')";
}
 
if (!$nodisplay)
{
	$list_total_did = $instance_table->SQLExec ($DBHandle, $QUERY);	
}//end IF nodisplay

/************************************************ END DID Billing Section *********************************************/

/*************************************************CHARGES SECTION START ************************************************/

// Charge Types

// Connection charge for DID setup = 1
// Monthly Charge for DID use = 2
// Subscription fee = 3
// Extra charge =  4
if ($invoice_type == 1)
{
	$QUERY = "SELECT t1.id_cc_card, t1.iduser, t1.creationdate, t1.amount, t1.chargetype, t1.id_cc_did, t1.currency, t1.description" .
	" FROM cc_charge t1, cc_card t2 WHERE (t1.chargetype <> 1 AND t1.chargetype <> 2) " .
	" AND t2.username = '$customer' AND t1.id_cc_card = t2.id AND t1.creationdate >= (Select CASE WHEN max(cover_enddate) is NULL " .
	" THEN '0001-01-01 01:00:00' ELSE max(cover_enddate) END from cc_invoices) Order by t1.creationdate";
}
else
{
	$QUERY = "SELECT t1.id_cc_card, t1.iduser, t1.creationdate, t1.amount, t1.chargetype, t1.id_cc_did, t1.currency, t1.description" .
	" FROM cc_charge t1, cc_card t2 WHERE (t1.chargetype <> 2 AND t1.chargetype <> 1)" .
	" AND t2.username = '$customer' AND t1.id_cc_card = t2.id AND " .
	" t1.creationdate >(Select cover_startdate  from cc_invoices where id ='$id') " .
	" AND t1.creationdate <(Select cover_enddate  from cc_invoices where id ='$id')";
}
//echo "<br>".$QUERY."<br>";

if (!$nodisplay)
{
	$list_total_charges = $instance_table->SQLExec ($DBHandle, $QUERY);	
}//end IF nodisplay


/*************************************************CHARGES SECTION END ************************************************/
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

if ((isset($customer)  &&  ($customer>0)) || (isset($entercustomer)  &&  ($entercustomer>0))){

	$FG_TABLE_CLAUSE = "";
	if (isset($customer)  &&  ($customer>0)){		
		$FG_TABLE_CLAUSE =" username='$customer' ";
	}elseif (isset($entercustomer)  &&  ($entercustomer>0)){
		$FG_TABLE_CLAUSE =" username='$entercustomer' ";
	}

	$instance_table_customer = new Table("cc_card", "id,  username, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, activated, creationdate");
	$info_customer = $instance_table_customer -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, "id", "ASC", null, null, null, null);
	
	if($invoice_type == 1)
	{
		$QUERY = "Select CASE WHEN max(cover_enddate) is NULL THEN '0001-01-01 01:00:00' ELSE max(cover_enddate) END from cc_invoices WHERE cardid = ".$cardid;
	}
	else
	{
		$QUERY = "Select cover_enddate, cover_startdate, payment_status  from cc_invoices where id ='$id'";
	}
	if (!$nodisplay){
		$invoice_data = $instance_table->SQLExec ($DBHandle, $QUERY);			
		if ($invoice_data[0][0] == '0001-01-01 01:00:00')
		{
			$invoice_data[0][0] = $info_customer[0][13];
		}
	}//end IF nodisplay
}
$payment_status_list = array();
$payment_status_list["0"] = array( gettext("UNPAID"), "0");
$payment_status_list["1"] = array( gettext("SENT-UNPAID"), "1");
$payment_status_list["2"] = array( gettext("SENT-PAID"),  "2");
$payment_status_list["3"] = array( gettext("PAID"),  "3");
?>

<?php
$smarty->display( 'main.tpl');

?>
<?php 
$currencies_list = get_currencies();
//For DID DIAL & Fixed + Dial
$totalcost = 0;

if (is_array($list_total_destination) && count($list_total_destination)>0){
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
	<table  cellspacing="0" class="invoice_main_table">
     
      <tr>
        <td class="invoice_heading"><?php echo $invoice_heading; ?></td>
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
			<?php 
			if ($invoice_type == 1){
			?>
            <tr>
              <td width="35%" class="invoice_td"><?php echo gettext("As of Date");?>&nbsp;:</td>
              <td width="65%" class="invoice_td"><?php echo display_dateonly($invoice_data[0][0]);?> </td>
            </tr>
			<?php }else{ ?>
			<tr>
              <td width="35%" class="invoice_td"><?php echo gettext("From Date");?>&nbsp;:</td>
              <td width="65%" class="invoice_td"><?php echo display_dateonly($invoice_data[0][1]);?> </td>
            </tr>
			<tr>
              <td width="35%" class="invoice_td"><?php echo gettext("To Date");?>&nbsp;:</td>
              <td width="65%" class="invoice_td"><?php echo display_dateonly($invoice_data[0][0]);?> </td>
            </tr>
			<tr>
              <td width="35%" class="invoice_td"><?php echo gettext("Status");?>&nbsp;:</td>
              <td width="65%" class="invoice_td" valign="middle"> <form  method="post" action="A2B_entity_invoice_detail.php?id=<?php echo $id;?>&invoice_type=<?php echo $invoice_type;?>">
			  
			  <select NAME="payment_status" size="1" class="form_input_select">
						<?php							
							foreach($payment_status_list as $data) {
						?>
							<option value='<?php echo $data[1] ?>' <?php if ($invoice_data[0][2]==$data[1]){?>selected<?php } ?>><?php echo $data[0]; ?>
							</option>
						<?php 	} ?>
					</select>&nbsp;<input type="submit" class="form_input_button" name="submit" value="Update">
					</form>
					</td>
            </tr>
			
			<?php } ?>
            <tr>
              <td >&nbsp;</td>
              <td >&nbsp;</td>
            </tr>
            
        </table></td>
      </tr>	   
      <tr>
        <td valign="top"><table width="100%" align="left" cellpadding="0" cellspacing="0">

   				<?php 
				if (is_array($list_total_destination) && count($list_total_destination)>0)
				{ 
				?>
				<tr>
				<td colspan="5" align="center"> <b><?php echo gettext("Calls by Destination");?></b></font> </td>

				</tr>

			<tr class="invoice_subheading">
              <td class="invoice_td" width="29%"><?php echo gettext("Destination");?> </td>
              <td width="19%" class="invoice_td"><?php echo gettext("Duration");?> </td>
			  <td width="20%" class="invoice_td"><?php echo gettext("Graphic");?> </td>
			  <td width="11%" class="invoice_td"><?php echo gettext("Calls");?> </td>
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
              <td width="21%" align="right" class="invoice_td"><?php  display_2bill($totalcost - $totalcost_did) ?> </td>
            </tr>  
            <tr>
              <td width="29%">&nbsp;</td>
              <td width="19%">&nbsp;</td>
              <td width="20%">&nbsp; </td>
			  <td width="11%">&nbsp; </td>
			  <td width="21%">&nbsp; </td>
			  
            </tr>		
			<?php } ?>  
			<!-- Start Here ****************************************-->
			<?php			
				
				$mmax=0;
				$totalcall=0;
				$totalminutes=0;
				$totalcost_day=0;
				if (is_array($list_total_day) && count($list_total_day)>0)
				{
				foreach ($list_total_day as $data){	
					if ($mmax < $data[1]) $mmax=$data[1];
					$totalcall+=$data[3];
					$totalminutes+=$data[1];
					$totalcost_day+=$data[2];
				}
				?>
				<tr>
				<td colspan="5" align="center"><b><?php echo gettext("Calls by Date");?></b> </td>
				</tr>
			  <tr class="invoice_subheading">
              <td class="invoice_td" width="29%"><?php echo gettext("Date");?> </td>
              <td width="19%" class="invoice_td"><?php echo gettext("Duration");?> </td>
			  <td width="20%" class="invoice_td"><?php echo gettext("Graphic");?> </td>
			  <td width="11%" class="invoice_td"><?php echo gettext("Calls");?> </td>
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
              <td width="21%" align="right" class="invoice_td"><?php  display_2bill($totalcost_day) ?> </td>
            </tr>           
            <tr >
              <td width="29%">&nbsp;</td>
              <td width="19%">&nbsp;</td>
              <td width="20%">&nbsp; </td>
			  <td width="11%">&nbsp; </td>
			  <td width="21%">&nbsp; </td>			  
            </tr>
				 <?php }?> 
				
			
			<!-- END HERE ******************************************-->
        </table>		
		</td>
      </tr>
      <?php 
      if (is_array($list_total_did) && count($list_total_did)>0)
				{
      ?>
	   <tr>
	  <td>
	  <!------------------------ DID Billing Here Starts ----------------------->
		
		<table width="100%" align="left" cellpadding="0" cellspacing="0">
   				<tr>
				<td colspan="5" align="center"><font></font> <b><?php echo gettext("DID Billing")?></b></td>
				</tr>
			<tr class="invoice_subheading">
			  <td width="17%" class="invoice_td"><?php echo gettext("Charge Date")?> </td>
              <td class="invoice_td" width="12%"><?php echo gettext("DID")?> </td>
              <td width="14%" class="invoice_td"><?php echo gettext("Country")?> </td>
			  <td width="40%" class="invoice_td"><?php echo gettext("Description")?> </td>			  			  
              <td width="17%" class="invoice_td" align="right"><?php echo gettext("Amount")." (".BASE_CURRENCY.")"; ?> </td>
            </tr>
			<?php  		
				$i=0;				
				$totaldidcost = 0;
				
				foreach ($list_total_did as $data)
				{	
					$totaldidcost = $totaldidcost + convert_currency($currencies_list, $data[0], $data[5], BASE_CURRENCY);
			
			?>
			 <tr class="invoice_rows">
			 <td width="17%" class="invoice_td"><?php echo $data[1]?> </td>
              <td width="12%" class="invoice_td">&nbsp;<?php echo $data[4]; ?></td>
              <td width="14%" class="invoice_td">&nbsp;<?php echo $data[3]; ?> </td>
  			  <td width="40%" class="invoice_td"><?php echo $data[2]?></td>			  			  
              <td width="17%" align="right" class="invoice_td"><?php  echo convert_currency($currencies_list, $data[0], $data[5], BASE_CURRENCY)." ".BASE_CURRENCY?></td>
            </tr>
			 <?php
				}
				$totalcost = $totalcost  + $totaldidcost;
			 ?>   
			 <tr >
              <td width="17%" class="invoice_td">&nbsp;</td>
              <td width="12%" class="invoice_td">&nbsp;</td>
              <td width="14%" class="invoice_td">&nbsp; </td>
			  <td width="40%" class="invoice_td">&nbsp; </td>			  
			  <td width="17%" class="invoice_td">&nbsp; </td>
			  
            </tr>
            <tr class="invoice_subheading">
              <td width="17%" class="invoice_td"><?php echo gettext("TOTAL");?> </td>
              <td class="invoice_td" >&nbsp;</td>			  
			  <td width="14%" class="invoice_td">&nbsp; </td>
			  <td width="40%" class="invoice_td">&nbsp;</td>
              <td width="17%" align="right" class="invoice_td"><?php  display_2bill($totaldidcost) ?> </td>
            </tr> 
		
            <tr >
              <td width="17%">&nbsp;</td>
              <td width="12%">&nbsp;</td>
              <td width="14%">&nbsp; </td>
			  <td width="40%">&nbsp; </td>			  
			  <td width="17%">&nbsp; </td>
			  
            </tr>
		
		</table>
		
		<!------------------------DID Billing ENDS Here ----------------------------->
	  </td>
	  </tr>
	  <?php			 
			 }
			 ?>
	   <!------------------------Extra Charges Start Here ----------------------------->
	  <?php  		
		$i=0;				
		$extracharge_total = 0;
		if (is_array($list_total_charges) && count($list_total_charges)>0)
		{
					
	  ?>		
	  <tr>
	  <td>
	  
	  <table width="100%" align="left" cellpadding="0" cellspacing="0">
   				<tr>
				<td colspan="4" align="center"><font></font> <b><?php echo gettext("Extra Charges")?></b></td>
				</tr>
			<tr class="invoice_subheading">
              <td class="invoice_td" width="18%"><?php echo gettext("Date")?> </td>
              <td width="15%" class="invoice_td"><?php echo gettext("Type")?> </td>			  
			  <td width="12%" class="invoice_td"><?php echo gettext("Description")?> </td>  			  
              <td width="25%" class="invoice_td" align="right"><?php echo gettext("Amount")." (".BASE_CURRENCY.")"; ?> </td>
            </tr>
			<?php  		
			
			foreach ($list_total_charges as $data)
			{	
			 	$extracharge_total = $extracharge_total + convert_currency($currencies_list,$data[3], $data[6], BASE_CURRENCY) ;
		
			?>
			 <tr class="invoice_rows">
              <td width="18%" class="invoice_td"><?php echo $data[2]?></td>
              <td width="15%" class="invoice_td"><?php 
			  if($data[4] == 1) //connection setup charges
				{
					echo gettext("Setup Charges");
				}
				if($data[4] == 2) //DID Montly charges
				{
					echo gettext("DID Montly Use");
				}
				if($data[4] == 3) //Subscription fee charges
				{
					echo gettext("Subscription Fee");
				}
				if($data[4] == 4) //Extra Misc charges
				{
					echo gettext("Extra Charges");
				}
			  ?> </td>
  			  <td width="10%" class="invoice_td"><?php  echo $data[7]; ?></td>			  
              <td width="25%" align="right" class="invoice_td"><?php echo convert_currency($currencies_list,$data[3], $data[6],BASE_CURRENCY)." ".BASE_CURRENCY ?></td>
            </tr>
			 <?php
			  }
			  //for loop end here
			   ?>
			 <tr >
              <td width="18%" class="invoice_td">&nbsp;</td>
              <td width="15%" class="invoice_td">&nbsp;</td>
              <td width="13%" class="invoice_td">&nbsp; </td>			  			 
			  <td width="25%" class="invoice_td">&nbsp; </td>
			  
            </tr>
            <tr class="invoice_subheading">
              <td width="18%" class="invoice_td"><?php echo gettext("TOTAL");?> </td>
              <td class="invoice_td" >&nbsp;</td>			  
			  <td width="17%" class="invoice_td">&nbsp; </td>
              <td width="25%" align="right" class="invoice_td"><?php echo display_2bill($extracharge_total) ?> </td>
            </tr>
			
            <tr >
              <td width="18%">&nbsp;</td>
              <td width="15%">&nbsp;</td>
              <td width="13%">&nbsp; </td>			  
			  <td width="25%">&nbsp; </td>			  
            </tr>		
		</table>
		
	  
	  </td>
	  </tr>
	  <?php
	   }
	   //if check end here
	   $totalcost = $totalcost + $extracharge_total;
	   ?>
	  <!------------------------Extra Charges End Here ----------------------------->
	  
	 <tr>
	 <td>&nbsp;</td>
	 </tr>
 	 <tr class="invoice_subheading">
	 <td  align="right" class="invoice_td"><?php echo gettext("Total");?> = <?php echo display_2bill($totalcost);?>&nbsp;</td>
	 </tr>
	 <tr class="invoice_subheading">
	 <td  align="right" class="invoice_td"><?php echo gettext("VAT");?> = <?php 
	 $prvat = ($vat / 100) * $totalcost;
	 display_2bill($prvat);?>&nbsp;</td>
	 </tr>
	 <tr class="invoice_subheading">
	 <td  align="right" class="invoice_td"><?php echo gettext("Grand Total");?> = <?php echo display_2bill($totalcost + $prvat);?>&nbsp;</td>
	 </tr>
	 <tr>
	 <td>&nbsp;</td>
	 </tr>
	  <?php if ($exporttype != "pdf"){?>
      <tr>
        <td><table cellspacing="0" cellpadding="0">
            <tr>
              <td width="15%"><?php echo gettext("Status");?> :&nbsp; </td>
             <td width="20%"><?php if($info_customer[0][12] == 't') {?>
			  <img width="18" height="7" src="<?php echo Images_Path;?>/connected.gif">
			  <?php }
			  else
			  {
			  ?>
			  <img width="18" height="7" src="<?php echo Images_Path;?>/terminated.gif">
			  <?php }?></td>
              <td width="65%"><img width="18" height="7" src="<?php echo Images_Path;?>/connected.gif">&nbsp;<?php echo gettext("Connected");?>&nbsp;&nbsp;&nbsp;
			  <img width="22" height="7" src="<?php echo Images_Path;?>/terminated.gif">&nbsp; <?php echo gettext("DisConnected");?></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td valign="top"><table width="400" height="22" align="left" cellpadding="0" cellspacing="0">
                  
                </table>
                  <table cellpadding="0">
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
        </table></td>
      </tr>
	  <?php } ?>
    </table>
<?php

$smarty->display( 'footer.tpl');
?>
