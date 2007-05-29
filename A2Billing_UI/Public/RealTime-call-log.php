<?php 
include ("../lib/defines.php");
include ("../lib/module.access.php");
//include ("../lib/Class.Table.php");
exit();
session_start();

getpost_ifset(array('posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'dsttype', 'sourcetype', 'clidtype', 'channel', 'resulttype', 'stitle', 'atmenu', 'current_page', 'order', 'sens', 'dst', 'src', 'clid'));



if (!isset ($current_page) || ($current_page == "")){	
		$current_page=0; 
	}


// this variable specifie the debug type (0 => nothing, 1 => sql result, 2 => boucle checking, 3 other value checking)
$FG_DEBUG = 0;

// The variable FG_TABLE_NAME define the table name to use
$FG_TABLE_NAME="log t1";

if ($_SESSION["is_admin"]==0){
 	$FG_TABLE_NAME.=", Customer t2";
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


$FG_TABLE_COL[]=array (gettext("Nb Call"), "count(*)", "12%", "center", "SORT", "30");

$FG_TABLE_COL[]=array (gettext("Customer"), "cardID", "20%", "center", "SORT", "30", "lie", "Customer", "AnaCust", "IDCust ='%id'", "%1");



/*
if ($_SESSION["is_admin"]==1) $FG_TABLE_COL[]=array ("Buycosts", "buycosts", "12%", "center", "SORT", "30");
if ($_SESSION["is_admin"]==1) $FG_TABLE_COL[]=array ("Rate", "rate", "12%", "center", "SORT", "30");
*/
$FG_TABLE_COL[]=array (gettext("Incoming"), "costs", "12%", "center", "SORT", "30");

if ($_SESSION["is_admin"]==1){
	$FG_TABLE_COL[]=array (gettext("RealDuration"), "realduration", "12%", "center", "SORT", "30");
}else{
	$FG_TABLE_COL[]=array (gettext("Duration"), "duration", "12%", "center", "SORT", "30");
}


// ??? cardID
$FG_TABLE_DEFAULT_ORDER = "t1.callstart";
$FG_TABLE_DEFAULT_SENS = "DESC";

// This Variable store the argument for the SQL query
//$FG_COL_QUERY='calldate, channel, src, clid, lastapp, lastdata, dst, dst, serverid, disposition, duration';
//$FG_COL_QUERY='calldate, channel, src, clid, lastapp, lastdata, dst, dst, disposition, duration';
if ($_SESSION["is_admin"]==1) {
	$FG_COL_QUERY='count(*), t1.cardID,  sum(t1.costs), sum(t1.realduration), sum(t1.buycosts)';
}else{
	$FG_COL_QUERY='count(*), t1.cardID, sum(t1.costs), sum(t1.duration), sum(t1.buycosts)';	
}

$FG_COL_QUERY_GRAPH='t1.callstart, t1.duration';

// The variable LIMITE_DISPLAY define the limit of record to display by page
$FG_LIMITE_DISPLAY=8;

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
$FG_HTML_TABLE_WIDTH="96%";




if ($FG_DEBUG == 3) echo "<br>Table : $FG_TABLE_NAME  	- 	Col_query : $FG_COL_QUERY";
$instance_table = new Table($FG_TABLE_NAME, $FG_COL_QUERY);
$instance_table_graph = new Table($FG_TABLE_NAME, $FG_COL_QUERY_GRAPH);


if ( is_null ($order) || is_null($sens) ){
	$order = $FG_TABLE_DEFAULT_ORDER;
	$sens  = $FG_TABLE_DEFAULT_SENS;
}



$date_clause='';
// Period (Month-Day)
if (DB_TYPE == "postgres"){		
	 	$UNIX_TIMESTAMP = "";
}else{
		$UNIX_TIMESTAMP = "UNIX_TIMESTAMP";
}

  


if (!isset ($FG_TABLE_CLAUSE) || strlen($FG_TABLE_CLAUSE)==0){
		
		$cc_yearmonth = sprintf("%04d-%02d-%02d",date("Y"),date("n")-1,date("d")); 	
		$FG_TABLE_CLAUSE=" $UNIX_TIMESTAMP(t1.callstart) >= $UNIX_TIMESTAMP('$cc_yearmonth')";
}
//--$list_total = $instance_table_graph -> Get_list ($FG_TABLE_CLAUSE, null, null, null, null, null, null);


if (isset($customer)  &&  ($customer>0)){
	if (strlen($FG_TABLE_CLAUSE)>0) $FG_TABLE_CLAUSE.=" AND ";
	$FG_TABLE_CLAUSE.="t1.cardID='$customer'";
}

if ($_SESSION["is_admin"]==0){ 	
	if (strlen($FG_TABLE_CLAUSE)>0) $FG_TABLE_CLAUSE.=" AND ";
	$FG_TABLE_CLAUSE.="t1.cardID=t2.IDCust AND t2.IDmanager='".$_SESSION["pr_reseller_ID"]."'";
	
}

//> function Get_list ($clause=null, $order=null, $sens=null, $field_order_letter=null, $letters = null, $limite=null, $current_record = NULL)
//$list = $instance_table -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, $order, $sens, null, null, $FG_LIMITE_DISPLAY, $current_page*$FG_LIMITE_DISPLAY);
$list = $instance_table -> Get_list ($DBHandle, $FG_TABLE_CLAUSE." GROUP BY t1.cardID", $order, $sens, null, null, null, null);
//echo "<br>--<br>".$FG_TABLE_CLAUSE."<br><br>";
$_SESSION["pr_sql_export"]="SELECT $FG_COL_QUERY FROM $FG_TABLE_NAME WHERE $FG_TABLE_CLAUSE";
//echo "SELECT $FG_COL_QUERY FROM $FG_TABLE_NAME WHERE $FG_TABLE_CLAUSE";
	
/************************/
//$QUERY = "SELECT substring(calldate,1,10) AS day, sum(duration) AS calltime, count(*) as nbcall FROM cdr WHERE ".$FG_TABLE_CLAUSE." GROUP BY substring(calldate,1,10)"; //extract(DAY from calldate) 


$QUERY = "SELECT substring(t1.callstart,1,10) AS day, sum(t1.duration) AS calltime, count(*) as nbcall FROM $FG_TABLE_NAME WHERE ".$FG_TABLE_CLAUSE." GROUP BY substring(t1.callstart,1,10)"; //extract(DAY from calldate) 
//echo "$QUERY";

		$res = $DBHandle -> Execute($QUERY);
		if ($res){
			$num = $res -> RecordCount( );

			for($i=0;$i<$num;$i++)
			{				
				$list_total_day [] =$res -> fetchRow();				 
			}
		}



if ($FG_DEBUG == 3) echo "<br>Clause : $FG_TABLE_CLAUSE";
$nb_record = $instance_table -> Table_count ($DBHandle, $FG_TABLE_CLAUSE);
//$nb_record = count($list_total);
if ($FG_DEBUG >= 1) var_dump ($list);



if ($nb_record<=$FG_LIMITE_DISPLAY){ 
	$nb_record_max=1;
}else{ 
	$nb_record_max=(intval($nb_record/$FG_LIMITE_DISPLAY)+1);
}

if ($FG_DEBUG == 3) echo "<br>Nb_record : $nb_record";
if ($FG_DEBUG == 3) echo "<br>Nb_record_max : $nb_record_max";


/*******************   TOTAL COSTS  *****************************************/
if ($_SESSION["is_admin"]==1){ 
				$fields_costs="sum(t1.costs), sum(t1.buycosts),  sum(realduration)";
}else{
				$fields_costs="sum(t1.costs), sum(t1.buycosts),  sum(duration)";
}
$instance_table_cost = new Table($FG_TABLE_NAME, $fields_costs);		
$total_cost = $instance_table_cost -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, null, null, null, null, null, null);

//echo "<br/>".$total_cost[0][0]."-".$total_cost[0][1];


/*************************************************************/

/*******************   REFILL  *****************************************/
		//**** GET REFILL BY CUSTOMERS
		$QUERY = "SELECT  sum(credit)  from Customer";
		
		if ($_SESSION["is_admin"]==0){ 				
			$QUERY.=" WHERE IDmanager='".$_SESSION["pr_reseller_ID"]."'";
		}elseif (isset($IDmanager)){
			$QUERY.=" WHERE IDmanager='".$IDmanager."'";			
		}
		
		//echo $QUERY ;
		$res = $DBHandle -> Execute($QUERY);
		if ($res){
			$num = $res -> RecordCount( );		
		
			for($i=0;$i<$num;$i++)
			{		
				$list_credit [] =$res -> fetchRow();			
			}
		}
	   //print_r($list_refill);
	    $total_credit=$list_credit[0][0];


/*******************   REFILL  *****************************************/
		//**** GET REFILL BY CUSTOMERS
		$QUERY = "SELECT  t1.IDCust, sum(t3.credit)  from Customer as t1, cc_logrefill as t3  WHERE  t1.IDCust=t3.IDCust AND t3.opt='0' ";
		//echo $QUERY ;
		if ($_SESSION["is_admin"]==0){ 				
			$QUERY.=" AND t1.IDmanager='".$_SESSION["pr_reseller_ID"]."'";
		}elseif (isset($IDmanager)){
			$QUERY.=" AND t1.IDmanager='".$IDmanager."'";			
		}
		$QUERY.=" GROUP BY t1.IDCust";
		
		$res = $DBHandle -> Execute($QUERY);
		if ($res){
			$num = $res -> RecordCount( );		
		
			for($i=0;$i<$num;$i++)
			{		
				$list_refill [] =$res -> fetchRow();			
			}
		}
	   //print_r($list_refill);
	   $total_refill=0;
	   if (is_array($list_refill)){
	   		foreach ($list_refill as $refill_records){ 
	   			$total_refill+=$refill_records[1];
	   		}
	   }	   	      
	   
	   function findkey($refill, $keytofind){
	   		if (is_array($refill)){
				foreach ($refill as $refill_records){ 
					//print_r ($refill_records);
					//echo $refill_records[0];
					if ($refill_records[0] == $keytofind) return $refill_records[1];				
					
				}
			}
			return 0;			
	   }

/*******************   DISCOUNT  *****************************************/
		//**** GET DISCOUNT BY CUSTOMERS		
		$QUERY = "SELECT  t1.IDCust, sum(t3.credit)  from Customer as t1, cc_logrefill as t3  WHERE  t1.IDCust=t3.IDCust AND t3.opt='1' ";
		if ($_SESSION["is_admin"]==0){ 				
			$QUERY.=" AND t1.IDmanager='".$_SESSION["pr_reseller_ID"]."'";
		}elseif (isset($IDmanager)){
			$QUERY.=" AND t1.IDmanager='".$IDmanager."'";			
		}
		$QUERY.=" GROUP BY t1.IDCust";
		
		$res = $DBHandle -> Execute($QUERY);
		if ($res){
			$num = $res -> RecordCount( );		
		
			for($i=0;$i<$num;$i++)
			{		
				$list_discount[] =$res -> fetchRow();			
			}
		}
	   //print_r($list_discount);
	   $total_discount=0;
	   if (is_array($list_discount)){
	   		foreach ($list_discount as $refill_records){ 
	   			$total_discount+=$refill_records[1];
	   		}
	   }
	   
	   
	   
/*******************   SALDO  *****************************************/
		//**** GET SALDO BY CUSTOMERS
		// select t1.IDCust, t1.AnaCust, t1.credit, SUM(t2.saldo)  from Customer as t1, saldorefill as t2 WHERE t1.IDCust=t2.IDCust GROUP BY t1.IDCust;
		$QUERY = "SELECT t1.IDCust, SUM(t2.saldo)  from Customer as t1, saldorefill as t2 WHERE t1.IDCust=t2.IDCust";
		if ($_SESSION["is_admin"]==0){ 				
			$QUERY.=" AND t1.IDmanager='".$_SESSION["pr_reseller_ID"]."'";
		}elseif (isset($IDmanager)){
			$QUERY.=" AND t1.IDmanager='".$IDmanager."'";			
		}
		$QUERY.=" GROUP BY t1.IDCust";
		
		$res = $DBHandle -> Execute($QUERY);
		if ($res){
			$num = $res -> RecordCount( );		
		
			for($i=0;$i<$num;$i++)
			{		
				$list_saldo [] =$res -> fetchRow();			
			}
		}
		
		$total_saldo=0;
	    if (is_array($list_saldo)){
	   		foreach ($list_saldo as $refill_records){ 
	   			$total_saldo+=$refill_records[1];
	   		}
	   }

/******************* *******************/



?>

<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

//-->
</script>

<?php
	include("PP_header.php");
?>
<!-- ** ** ** ** ** Part to display the CDR ** ** ** ** ** -->
<?php  if (is_array($list) && count($list)>0){  ?>


			<!-- ************** TOTAL SECTION ************* -->
			<br/>
			<div style="padding-left: 15px;">
			<table cellpadding="1" bgcolor="#000000" cellspacing="1" width="<?php  if ($_SESSION["is_admin"]==1) echo "600"; else echo "300";  ?>" align="left">
				<tbody>
                <tr class="form_head">                   									   
				   <td width="20%" align="center" class="tableBodyRight" bgcolor="#bbbbbb" style="padding: 5px;"><strong><?php echo gettext("TOTAL COSTS")?></strong></td>
				   <?php  if ($_SESSION["is_admin"]==1) { ?><td width="20%" align="center" class="tableBodyRight" bgcolor="#aaaaaa" style="padding: 5px;"><strong><?php echo gettext("TOTAL BUYCOSTS")?></strong></td><?php }?>
				   <?php  if ($_SESSION["is_admin"]==1) { ?><td width="20%" align="center" class="tableBodyRight" bgcolor="#999999" style="padding: 5px;"><strong><?php echo gettext("REVENUE")?></strong></td><?php }?>
				   <?php  if ($_SESSION["is_admin"]==1) { ?><td width="20%" align="center" class="tableBodyRight" bgcolor="#888888" style="padding: 5px;"><strong><?php echo gettext("PERCENTAGE")?></strong></td><?php }?>
				   <td width="20%" align="center" class="tableBodyRight" bgcolor="#777777" style="padding: 5px;"><strong><?php echo gettext("TOTAL MINUTES")?></strong></td>				   
                </tr>
				<tr>
				  <td valign="top" align="center" class="tableBody" bgcolor="#FFFFFF"><b><?php  echo sprintf("%8.3f",$total_cost[0][0]);?></b></td>
				  <?php  if ($_SESSION["is_admin"]==1) { ?><td valign="top" align="center" class="tableBody" bgcolor="#EFEFEF"><b><?php  echo sprintf("%8.3f",$total_cost[0][1]);?></b></td><?php }?>
				  <?php  if ($_SESSION["is_admin"]==1) { ?><td valign="top" align="center" class="tableBody" bgcolor="#EEEEEE"><b><?php  echo sprintf("%8.3f",$total_cost[0][0]-$total_cost[0][1]);?></b></td><?php }?>
				  <?php  if ($_SESSION["is_admin"]==1) { ?><td valign="top" align="center" class="tableBody" bgcolor="#DEDEDE"><b><?php  if ($total_cost[0][1]>0){ echo sprintf("%8.3f",(($total_cost[0][0]-$total_cost[0][1])* 100)/$total_cost[0][1]); }else{ echo "- -";}?></b></td><?php }?>
				  <td valign="top" align="center" class="tableBody" bgcolor="#DDDDDD"><b><?php  echo sprintf("%02d",intval($total_cost[0][2]/60)).":".sprintf("%02d",intval($total_cost[0][2]%60)); ?></b></td>
				</tr>
			</table>
			</div>
			
			<div style="padding-right: 15px;">
				<table border=0  cellpadding="1"  cellspacing="1" width="50" align="right">
					<tbody>
					<tr>                   									   
					   <td  align="center"style="padding: 5px;" >
					   		<a href="<?php  echo $_SERVER['PHP_SELF'] ."?stitle=RealTime_Log"; ?>"> <img src="<?php echo Images_Path;?>/refresh.gif" border=0></a>
						</td>
					</tr>
				</table>
			</div>
			<br/><br/><br/><br/>
					
					
					
					
<!-- ************** TOTAL SECTION ************* -->

			
<div class="scrollreal" style="padding-left: 15px;">
<center><?php echo gettext("Number of call")?> : <?php  if (is_array($list) && count($list)>0){ echo $nb_record; }else{echo "0";}?></center>
      <table width="<?php echo $FG_HTML_TABLE_WIDTH?>" border="0" align="center" cellpadding="0" cellspacing="0">
        <TR> 
          <TD> <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
<TBODY>
                <TR  class="bgcolor_008"> 
				  <TD width="<?php echo $FG_ACTION_SIZE_COLUMN?>" align=center class="tableBodyRight" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px"> <span class="liens"><?php echo gettext("idx")?></span></TD>					
				  
                  <?php 
				  	if (is_array($list) && count($list)>0){
					
				  	for($i=0;$i<$FG_NB_TABLE_COL-2;$i++){ 
						//$FG_TABLE_COL[$i][1];			
						//$FG_TABLE_COL[]=array ("Name", "name", "20%");
					?>				
				  
					
                  <TD width="<?php echo $FG_TABLE_COL[$i][2]?>" align=middle class="tableBody" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px"> 
                    <center><strong> 
                    <?php  if (strtoupper($FG_TABLE_COL[$i][4])=="SORT"){?>
                    <span class="liens">
					<?php }?>
                    <?php echo $FG_TABLE_COL[$i][0]?>                     
                    <?php  if (strtoupper($FG_TABLE_COL[$i][4])=="SORT"){?>
                    </span></a> 
                    <?php }?>
                    </strong></center></TD>
				   <?php } ?>		

					<TD width="<?php echo $FG_ACTION_SIZE_COLUMN?>" align=center class="tableBodyRight" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px"><strong><span class="liens"><?php echo gettext("Credit")?></span></strong></TD>
					<TD width="<?php echo $FG_ACTION_SIZE_COLUMN?>" align=center class="tableBodyRight" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px"><strong><span class="liens"><?php echo gettext("Incoming")?></span></strong></TD>
				   <?php  if ($_SESSION["is_admin"]==1) { ?>
				   <TD width="<?php echo $FG_ACTION_SIZE_COLUMN?>" align=center class="tableBodyRight" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px"><strong><span class="liens"><?php echo gettext("Cost")?></span></strong></TD>
				   <?php  } ?>
				   <TD width="<?php echo $FG_ACTION_SIZE_COLUMN?>" align=center class="tableBodyRight" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px"><strong><span class="liens"><?php echo gettext("Minutes")?></span></strong></TD>
				   
<!--					<TD width="<?php echo $FG_ACTION_SIZE_COLUMN?>" align=center class="tableBodyRight" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px"><strong><span class="liens"><?php echo gettext("Refill")?></span></strong></TD>
				   <TD width="<?php echo $FG_ACTION_SIZE_COLUMN?>" align=center class="tableBodyRight" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px"><strong><span class="liens"><?php echo gettext("Payment")?></span></strong></TD>
-->				   <TD width="<?php echo $FG_ACTION_SIZE_COLUMN?>" align=center class="tableBodyRight" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px"><strong><span class="liens"><?php echo gettext("Signal")?></span></strong></TD>
					

                </TR>
                <TR> 
                  <TD  class="tableDivider" colSpan=<?php echo $FG_TOTAL_TABLE_COL?> ><IMG 
                              height=1 
                              src="<?php echo Images_Path;?>/clear.gif" 
                              width=1></TD>
                </TR>
				<?php
				
				
				  
				  	 $ligne_number=0;					 
					 //print_r($list);
				  	 foreach ($list as $recordset){ 
						 $ligne_number++;
				?>
				
               		 <TR bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>"  onMouseOver="bgColor='#C4FFD7'" onMouseOut="bgColor='<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>'"> 
						<TD vAlign=top align="<?php echo $FG_TABLE_COL[$i][3]?>" class=tableBody><?php  echo $ligne_number+$current_page*$FG_LIMITE_DISPLAY.".&nbsp;"; ?></TD>
							 
				  		<?php for($i=0;$i<$FG_NB_TABLE_COL-2;$i++){ ?>
						
						  
						<?php 	//$FG_TABLE_COL[$i][1];			
							//$FG_TABLE_COL[]=array ("Name", "name", "20%");
							
							
							if ($FG_TABLE_COL[$i][6]=="lie"){


									$instance_sub_table = new Table($FG_TABLE_COL[$i][7], $FG_TABLE_COL[$i][8]);
									$sub_clause = str_replace("%id", $recordset[$i], $FG_TABLE_COL[$i][9]);																																	
									
									$select_list = $instance_sub_table -> Get_list ($DBHandle, $sub_clause, null, null, null, null, null, null);
									
									
									$field_list_sun = split(',',$FG_TABLE_COL[$i][8]);
									$record_display = $FG_TABLE_COL[$i][10];
									//echo $record_display;
									
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
                 		 <TD vAlign=top align="<?php echo $FG_TABLE_COL[$i][3]?>" class=tableBody><?php echo stripslashes($record_display)?></TD>
				 		 <?php  } 
						 $my_minutes = sprintf("%02d",intval($recordset[3]/60)).":".sprintf("%02d",intval($recordset[3]%60));
						 $valrefill = findkey ($list_refill, $recordset[1]); 
						 $valsaldo=findkey ($list_saldo, $recordset[1]); 
						 
						 ?>
						 
				<TD vAlign=top align="center" class=tableBody><?php echo $valrefill-$valsaldo?></TD>
				<TD vAlign=top align="<?php echo $FG_TABLE_COL[1][3]?>" class=tableBody><?php  echo sprintf("%8.3f",$recordset[2]);?></TD>
				<?php  if ($_SESSION["is_admin"]==1) { ?>
				<TD vAlign=top align="<?php echo $FG_TABLE_COL[1][3]?>" class=tableBody><?php  echo sprintf("%8.3f",$recordset[4]);?></TD>	
				<?php  } ?>
				<TD vAlign=top align="<?php echo $FG_TABLE_COL[2][3]?>" class=tableBody><?php echo $my_minutes?></TD>		
				
            <!--      <TD vAlign=top align="center" class=tableBody><?php  echo $valrefill;?></TD>
				  <TD vAlign=top align="center" class=tableBody><?php  if (($valsaldo!=0) && (1==0)){echo "-";} echo $valsaldo; ?></TD>
		-->		  <TD vAlign=top align="center" class=tableBody><span class="liens"><?php  if (($valrefill-$valsaldo) <100){echo "CAUTION !";} ?></span></TD>
			  
					</TR>
				<?php
					 }//foreach ($list as $recordset)
					 while ($ligne_number < $FG_LIMITE_DISPLAY){
					 	$ligne_number++;
				?>
					<TR bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>"> 
				  		<?php 
						    if ($_SESSION["is_admin"]==1) $FIX_FG_NB_TABLE_COL=$FG_NB_TABLE_COL+3;
							else $FIX_FG_NB_TABLE_COL=$FG_NB_TABLE_COL+2;
							for($i=0;$i<$FIX_FG_NB_TABLE_COL;$i++){ 
							//$FG_TABLE_COL[$i][1];			
							//$FG_TABLE_COL[]=array ("Name", "name", "20%");
				 		 ?>
                 		 <TD vAlign=top class=tableBody>&nbsp;</TD>
				 		 <?php  } ?>
                 		 <TD align="center" vAlign=top class=tableBodyRight>&nbsp;</TD>				
					</TR>
									
				<?php					 
					 } //END_WHILE
					 
				  }else{
				  		echo "No data found !!!";				  
				  }//end_if
				 ?>
                <TR> 
                  <TD class=tableDivider colSpan=<?php echo $FG_TOTAL_TABLE_COL?>><IMG height=1 
                              src="<?php echo Images_Path;?>/clear.gif" 
                              width=1></TD>
                </TR>
                <TR> 
                  <TD class=tableDivider colSpan=<?php echo $FG_TOTAL_TABLE_COL?>><IMG height=1 
                              src="<?php echo Images_Path;?>/clear.gif" 
                              width=1></TD>
                </TR>
              </TBODY>
            </TABLE></td>
        </tr>
        
      </table>
</div>

<?php  } ?>

<!-- ** ** ** ** ** Part to display the GRAPHIC ** ** ** ** ** -->
<br><br>

<?php 

if (is_array($list_total_day) && count($list_total_day)>0){
/*if (is_array($list) && count($list)>0){

$table_graph=array();
$numm=0;
foreach ($list_total as $recordset){
		$numm++;
		$mydate= substr($recordset[0],0,10);
		//echo "$mydate<br>";
		
		if (is_array($table_graph[$mydate])){
			$table_graph[$mydate][0]++;
			$table_graph[$mydate][1]=$table_graph[$mydate][1]+$recordset[1];
		}else{
			$table_graph[$mydate][0]=1;
			$table_graph[$mydate][1]=$recordset[1];
		}
		
}*/


$mmax=0;
$totalcall==0;
$totalminutes=0;
foreach ($list_total_day as $data){	
	if ($mmax < $data[1]) $mmax=$data[1];
	$totalcall+=$data[2];
	$totalminutes+=$data[1];
}
//echo "<br/>$totalcall-$totalminutes";


/*foreach ($table_graph as $tkey => $data){	
	if ($mmax < $data[1]) $mmax=$data[1];
	$totalcall+=$data[0];
	$totalminutes+=$data[1];
}*/
//print_r($table_graph);

?>



<!-- TITLE GLOBAL -->
<center>
 <table border="0" cellspacing="0" cellpadding="0" width="80%"><tbody><tr><td align="left" height="30">
		<table cellspacing="0" cellpadding="1" bgcolor="#000000" width="50%"><tbody><tr><td>
			<table cellspacing="0" cellpadding="0" width="100%"><tbody>
				<tr><td  class="bgcolor_019" align="left"><font  class="fontstyle_003"><?php echo gettext("TOTAL")?></font></td></tr>
			</tbody></table>
		</td></tr></tbody></table>
 </td></tr></tbody></table>
		  
<!-- FIN TITLE GLOBAL MINUTES //-->
				
<table border="0" cellspacing="0" cellpadding="0" width="80%">
<tbody><tr><td bgcolor="#000000">			
	<table border="0" cellspacing="1" cellpadding="2" width="100%"><tbody>
	<tr>	
		<td align="center" class="bgcolor_019" ></td>
    	<td  class="bgcolor_020" align="center" colspan="4"><font class="fontstyle_003"><?php echo gettext("ASTERISK MINUTES")?></font></td>
    </tr>
	<tr class="bgcolor_019">
		<td align="right"  class="bgcolor_020"><font class="fontstyle_003"><?php echo gettext("DATE")?></font></td>
        <td align="center"><font class="fontstyle_003"><?php echo gettext("DURATION")?></font></td>
		<td align="center"><font class="fontstyle_003"><?php echo gettext("GRAPHIC")?></font></td>
		<td align="center"><font class="fontstyle_003"><?php echo gettext("CALLS")?></font></td>
		<td align="center"><font class="fontstyle_003"><?php echo gettext("TMC")?></font></td>
                			
		<!-- LOOP -->
	<?php  		
		$i=0;
		// #ffffff #cccccc
		foreach ($list_total_day as $data){	
		$i=($i+1)%2;		
		$tmc = $data[1]/$data[2];
		
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
		$widthbar= intval(($data[1]/$mmax)*200); 
		
		//bgcolor="#336699" 
	?>
		</tr><tr>
		<td align="right" class="sidenav" nowrap="nowrap"><font  class="fontstyle_006"><?php echo $data[0]?></font></td>
		<td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i]?>" align="right" nowrap="nowrap"><font face="verdana" color="#000000" size="1"><?php echo $minutes?> </font></td>
        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i]?>" align="left" nowrap="nowrap" width="<?php echo $widthbar+60?>">
        <table cellspacing="0" cellpadding="0"><tbody><tr>
        <td bgcolor="#e22424"><img src="<?php echo Images_Path; ?>/spacer.gif" width="<?php echo $widthbar?>" height="6"></td>
        </tr></tbody></table></td>
        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i]?>" align="right" nowrap="nowrap"><font face="verdana" color="#000000" size="1"><?php echo $data[2]?></font></td>
        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i]?>" align="right" nowrap="nowrap"><font face="verdana" color="#000000" size="1"><?php echo $tmc?> </font></td>
     <?php 	 }	 	 	
	 	
		if ((!isset($resulttype)) || ($resulttype=="min")){  
			$total_tmc = sprintf("%02d",intval(($totalminutes/$totalcall)/60)).":".sprintf("%02d",intval(($totalminutes/$totalcall)%60));				
			$totalminutes = sprintf("%02d",intval($totalminutes/60)).":".sprintf("%02d",intval($totalminutes%60));
		}else{
			$total_tmc = intval($totalminutes/$totalcall);			
		}
	 
	 ?>                   	
	</tr>
	<!-- FIN DETAIL -->		
	
				
				<!-- FIN BOUCLE -->

	<!-- TOTAL -->
	<tr  class="bgcolor_019">
		<td align="right" nowrap="nowrap"><font class="fontstyle_003"><?php echo gettext("TOTAL")?></font></td>
		<td align="center" nowrap="nowrap" colspan="2"><font class="fontstyle_003"><?php echo $totalminutes?> </font></td>
		<td align="center" nowrap="nowrap"><font class="fontstyle_003"><?php echo $totalcall?></font></td>
		<td align="center" nowrap="nowrap"><font class="fontstyle_003"><?php echo $total_tmc?></font></td>                        
	</tr>
	<!-- FIN TOTAL -->

	  </tbody></table>
	  <!-- Fin Tableau Global //-->

</td></tr></tbody></table>


<?php  }else{ ?>
	<center><h3><?php echo gettext("No calls today")?>&nbsp; !!!</h3></center><br></br>
<?php  } ?>
</center>

<?php
	include("PP_footer.php");
?>
