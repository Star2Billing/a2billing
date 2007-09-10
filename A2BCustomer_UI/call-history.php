<?php
include ("lib/defines.php");
include ("lib/module.access.php");
include ("lib/smarty.php");

if (! has_rights (ACX_ACCESS)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();
}


if (!$A2B->config["webcustomerui"]['cdr']) exit();


$QUERY = "SELECT  username, credit, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, lastuse, activated, status FROM cc_card WHERE username = '".$_SESSION["pr_login"]."' AND uipass = '".$_SESSION["pr_password"]."'";


$DBHandle_max  = DbConnect();
$numrow = 0;
$resmax = $DBHandle_max -> Execute($QUERY);
if ($resmax)
	$numrow = $resmax -> RecordCount();

if ($numrow == 0) exit();
$customer_info =$resmax -> fetchRow();

if($customer_info [14] != "1" ) {
	exit();
}

$customer = $_SESSION["pr_login"];

getpost_ifset(array('posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'dsttype', 'sourcetype', 'clidtype', 'channel', 'resulttype', 'stitle', 'atmenu', 'current_page', 'order', 'sens', 'dst', 'src', 'clid', 'choose_currency', 'terminatecause'));


if (!isset ($current_page) || ($current_page == "")){
	$current_page=0;
}


// this variable specifie the debug type (0 => nothing, 1 => sql result, 2 => boucle checking, 3 other value checking)
$FG_DEBUG = 0;

// The variable FG_TABLE_NAME define the table name to use
$FG_TABLE_NAME="cc_call t1";


// THIS VARIABLE DEFINE THE COLOR OF THE HEAD TABLE
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#FFFFFF";
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#F2F8FF";

$yesno = array(); 	$yesno["1"]  = array( "Yes", "1");	 $yesno["0"]  = array( "No", "0");
// 0 = NORMAL CALL ; 1 = VOIP CALL (SIP/IAX) ; 2= DIDCALL + TRUNK ; 3 = VOIP CALL DID ; 4 = CALLBACK call
$list_calltype = array(); 	$list_calltype["0"]  = array( "STANDARD", "0");	 $list_calltype["1"]  = array( "SIP/IAX", "1");
$list_calltype["2"]  = array( "DIDCALL", "2"); $list_calltype["3"]  = array( "DID_VOIP", "3"); $list_calltype["4"]  = array( "CALLBACK", "4");
$list_calltype["5"]  = array( "PREDICT", "5");

//$link = DbConnect();
$DBHandle  = DbConnect();

// The variable Var_col would define the col that we want show in your table
// First Name of the column in the html page, second name of the field
$FG_TABLE_COL = array();


$FG_TABLE_COL[]=array (gettext("Calldate"), "starttime", "14%", "center", "SORT", "19", "", "", "", "", "", "display_dateformat");
$FG_TABLE_COL[]=array (gettext("Source"), "source", "12%", "center", "SORT", "30");
$FG_TABLE_COL[]=array (gettext("PhoneNumber"), "calledstation", "12%", "center", "SORT", "30", "", "", "", "", "", "remove_prefix");
$FG_TABLE_COL[]=array (gettext("Destination"), "destination", "12%", "center", "SORT", "30", "", "", "", "", "", "remove_prefix");
$FG_TABLE_COL[]=array (gettext("Duration"), "sessiontime", "7%", "center", "SORT", "30", "", "", "", "", "", "display_minute");
$FG_TABLE_COL[]=array (gettext("Cardused"), "username", "11%", "center", "SORT", "30");
$FG_TABLE_COL[]=array ('<acronym title="'.gettext("Terminate Cause").'">'.gettext("TC").'</acronym>', "terminatecause", "9%", "center", "SORT", "30");
$FG_TABLE_COL[]=array (gettext("Calltype"), "sipiax", "6%", "center", "SORT",  "", "list", $list_calltype);
$FG_TABLE_COL[]=array (gettext("InitalRate"), "calledrate", "7%", "center", "SORT", "30", "", "", "", "", "", "display_2dec");
$FG_TABLE_COL[]=array (gettext("Cost"), "sessionbill", "10%", "center", "SORT", "30", "", "", "", "", "", "display_2bill");


$FG_TABLE_DEFAULT_ORDER = "t1.starttime";
$FG_TABLE_DEFAULT_SENS = "DESC";
	
// This Variable store the argument for the SQL query
$FG_COL_QUERY='t1.starttime, t1.src, t1.calledstation, t1.destination, t1.sessiontime, t1.username, t1.terminatecause, t1.sipiax, t1.calledrate, t1.sessionbill';
// t1.stoptime,

$FG_COL_QUERY_GRAPH='t1.callstart, t1.duration';

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
$FG_HTML_TABLE_TITLE=" - ".gettext("Call Logs")." - ";

//This variable define the width of the HTML table
$FG_HTML_TABLE_WIDTH="98%";

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
							case 4: $sql = "$sql LIKE '%".$$fld."'";
						}
                }else{ $sql = "$sql LIKE '%".$$fld."%'"; }
		}
        return $sql;
  }  
  $SQLcmd = '';
  
  $SQLcmd = do_field($SQLcmd, 'src', 'source');
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
	if ($fromday && isset($fromstatsday_sday) && isset($fromstatsmonth_sday)) $date_clause.=" AND $UNIX_TIMESTAMP(t1.starttime) >= $UNIX_TIMESTAMP('$fromstatsmonth_sday-$fromstatsday_sday')";
	if ($today && isset($tostatsday_sday) && isset($tostatsmonth_sday)) $date_clause.=" AND $UNIX_TIMESTAMP(t1.starttime) <= $UNIX_TIMESTAMP('$tostatsmonth_sday-".sprintf("%02d",intval($tostatsday_sday)/*+1*/)." 23:59:59')";
}

//echo "<br>$date_clause<br>";
/*
Month
fromday today
frommonth tomonth (true)
fromstatsmonth tostatsmonth

fromstatsday_sday
fromstatsmonth_sday
tostatsday_sday
tostatsmonth_sday
*/


  
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


//To select just terminatecause=ANSWER
if (!isset($terminatecause)){
	$terminatecause="ANSWER";
}
if ($terminatecause=="ANSWER") {
	if (strlen($FG_TABLE_CLAUSE)>0) $FG_TABLE_CLAUSE.=" AND ";
	$FG_TABLE_CLAUSE .= " (t1.terminatecause='ANSWER' OR t1.terminatecause='ANSWERED') ";
}

if (!$nodisplay){
	$list = $instance_table -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, $order, $sens, null, null, $FG_LIMITE_DISPLAY, $current_page*$FG_LIMITE_DISPLAY);
}
$_SESSION["pr_sql_export"]="SELECT $FG_COL_QUERY FROM $FG_TABLE_NAME WHERE $FG_TABLE_CLAUSE";

/************************/
//$QUERY = "SELECT substring(calldate,1,10) AS day, sum(duration) AS calltime, count(*) as nbcall FROM cdr WHERE ".$FG_TABLE_CLAUSE." GROUP BY substring(calldate,1,10)"; //extract(DAY from calldate)


$QUERY = "SELECT substring(t1.starttime,1,10) AS day, sum(t1.sessiontime) AS calltime, sum(t1.sessionbill) AS cost, count(*) as nbcall FROM $FG_TABLE_NAME WHERE ".$FG_TABLE_CLAUSE." GROUP BY substring(t1.starttime,1,10) ORDER BY day"; //extract(DAY from calldate)

if (!$nodisplay){
		$res = $DBHandle -> Execute($QUERY);
		if ($res){
			$num = $res -> RecordCount();
			for($i=0;$i<$num;$i++)
			{				
				$list_total_day [] =$res -> fetchRow();
			}
		}		

if ($FG_DEBUG == 3) echo "<br>Clause : $FG_TABLE_CLAUSE";
$nb_record = $instance_table -> Table_count ($DBHandle, $FG_TABLE_CLAUSE);
if ($FG_DEBUG >= 1) var_dump ($list);

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

?>

<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

//-->
</script>

<?php
	$smarty->display( 'main.tpl');
	

	
// #### HELP SECTION
echo $CC_help_balance_customer;

?>




<!-- ** ** ** ** ** Part for the research ** ** ** ** ** -->
	<center>
	<FORM METHOD=POST ACTION="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>&terminatecause=<?php echo $terminatecause?>">
		<INPUT TYPE="hidden" NAME="posted" value=1>
		<INPUT TYPE="hidden" NAME="current_page" value=0>
		<table class="callhistory_maintable" align="center">
			<tr>
        		<td class="bgcolor_004" align="left" >
					
					<input type="radio" name="Period" value="Month" <?php  if (($Period=="Month") || !isset($Period)){ ?>checked="checked" <?php  } ?>> 
					<font class="fontstyle_003"><?php echo gettext("SELECT BY MONTH");?></b></font>
				</td>
				<td class="bgcolor_005" align="left" >
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
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
					<?php 
						$year_actual = date("Y");
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
								echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";				
							}
						}
					?>
					</select>
					</td></tr></table>
	  			</td>
    		</tr>
			
			<tr>
        		<td align="left" class="bgcolor_002">
					<input type="radio" name="Period" value="Day" <?php  if ($Period=="Day"){ ?>checked="checked" <?php  } ?>> 
					<font class="fontstyle_003"><?php echo gettext("SELECT BY DAY");?></b></font>
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
					<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("DESTINATION");?></font>
				</td>
				<td  align="left" class="bgcolor_005">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td class="fontstyle_searchoptions">&nbsp;&nbsp;<INPUT TYPE="text" NAME="dst" value="<?php echo $dst?>" class="form_input_text"></td>
				<td  align="center" class="fontstyle_searchoptions"><input type="radio" NAME="dsttype" value="1" <?php if((!isset($dsttype))||($dsttype==1)){?>checked<?php }?>><?php echo gettext("Exact");?></td>
				<td  align="center" class="fontstyle_searchoptions"><input type="radio" NAME="dsttype" value="2" <?php if($dsttype==2){?>checked<?php }?>><?php echo gettext("Begins with")?></td>
				<td  align="center" class="fontstyle_searchoptions"><input type="radio" NAME="dsttype" value="3" <?php if($dsttype==3){?>checked<?php }?>><?php echo gettext("Contains");?></td>
				<td  align="center" class="fontstyle_searchoptions"><input type="radio" NAME="dsttype" value="4" <?php if($dsttype==4){?>checked<?php }?>><?php echo gettext("End with");?></td>
				</tr></table></td>
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
				  <input name="terminatecause" type="radio" value="ANSWER" <?php if((!isset($terminatecause))||($terminatecause=="ANSWER")){?>checked<?php }?> />
				  <?php echo gettext("All Calls"); ?>

				   <input name="terminatecause" type="radio" value="ALL" <?php if($terminatecause=="ALL"){?>checked<?php }?>/>
					</td>
				</tr>
				<tr class="bgcolor_005">
					<td  class="fontstyle_searchoptions">
						<?php echo gettext("RESUTL");?> : 
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
              <TBODY>
                <TR> 
                  <TD><SPAN style="COLOR: #ffffff; FONT-SIZE: 11px"><B><?php echo $FG_HTML_TABLE_TITLE?></B></SPAN></TD>
                  <TD align=right> <IMG alt="Back to Top" border=0 height=12 src="<?php echo Images_Path_Main ?>/btn_top_12x12.gif" width=12> 
                  </TD>
                </TR>
              </TBODY>
            </TABLE></TD>
        </TR>
        <TR> 
          <TD> <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
<TBODY>
                <TR class="bgcolor_008"> 
				  <TD width="<?php echo $FG_ACTION_SIZE_COLUMN?>" align=center class="tableBodyRight" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px"></TD>					
				  
                  <?php 
				  	if (is_array($list) && count($list)>0){
					
				  	for($i=0;$i<$FG_NB_TABLE_COL;$i++){ 
					?>				
				  
					
                  <TD width="<?php echo $FG_TABLE_COL[$i][2]?>" align=middle class="tableBody" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px"> 
                    <center><strong> 
                    <?php  if (strtoupper($FG_TABLE_COL[$i][4])=="SORT"){?>
                    <a href="<?php  echo $PHP_SELF."?customer=$customer&s=1&t=0&stitle=$stitle&atmenu=$atmenu&current_page=$current_page&order=".$FG_TABLE_COL[$i][1]."&sens="; if ($sens=="ASC"){echo"DESC";}else{echo"ASC";} 
					echo "&posted=$posted&Period=$Period&frommonth=$frommonth&fromstatsmonth=$fromstatsmonth&tomonth=$tomonth&tostatsmonth=$tostatsmonth&fromday=$fromday&fromstatsday_sday=$fromstatsday_sday&fromstatsmonth_sday=$fromstatsmonth_sday&today=$today&tostatsday_sday=$tostatsday_sday&tostatsmonth_sday=$tostatsmonth_sday&dsttype=$dsttype&sourcetype=$sourcetype&clidtype=$clidtype&channel=$channel&resulttype=$resulttype&dst=$dst&src=$src&clid=$clid&terminatecause=$terminatecause";?>"> 
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
				   <?php if ($FG_DELETION || $FG_EDITION){ ?>
				   
                  
				   <?php } ?>		
                </TR>
                <TR> 
                  <TD bgColor=#e1e1e1 colSpan=<?php echo $FG_TOTAL_TABLE_COL?> height=1><IMG
                              height=1 
                              src="<?php echo Images_Path_Main ?>/clear.gif" width=1></TD>
                </TR>
				<?php
					  
				  	 $ligne_number=0;					 
				  	 foreach ($list as $recordset){ 
						 $ligne_number++;
						 $recordset[0] = display_GMT($recordset[0], $_SESSION["gmtoffset"], 1);
				?>
				
               		 <TR bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>"  onMouseOver="bgColor='#C4FFD7'" onMouseOut="bgColor='<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>'"> 
						<TD vAlign=top align="<?php echo $FG_TABLE_COL[$i][3]?>" class=tableBody><?php  echo $ligne_number+$current_page*$FG_LIMITE_DISPLAY.".&nbsp;"; ?></TD>
							 
				  		<?php for($i=0;$i<$FG_NB_TABLE_COL;$i++){ ?>
						<?php 				
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
                <TR> 
                  <TD class=tableDivider colSpan=<?php echo $FG_TOTAL_TABLE_COL?>><IMG height=1
                              src="<?php echo Images_Path_Main ?>/clear.gif" 
                              width=1></TD>
                </TR>
                <TR> 
                  <TD class=tableDivider colSpan=<?php echo $FG_TOTAL_TABLE_COL?>><IMG height=1
                              src="<?php echo Images_Path_Main ?>/clear.gif" 
                              width=1></TD>
                </TR>
              </TBODY>
            </TABLE></td>
        </tr>
        <TR bgcolor="#ffffff"> 
          <TD bgColor=#ADBEDE height=16 style="PADDING-LEFT: 5px; PADDING-RIGHT: 3px"> 
			<TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
              <TBODY>
                <TR> 
                  <TD align="right"><SPAN style="COLOR: #ffffff; FONT-SIZE: 11px"><B> 
                    <?php if ($current_page>0){?>
                    <img src="<?php echo Images_Path_Main ?>/fleche-g.gif" width="5" height="10"> <a href="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php  echo ($current_page-1)?><?php  if (!is_null($letter) && ($letter!="")){ echo "&letter=$letter";} 
					echo "&customer=$customer&posted=$posted&Period=$Period&frommonth=$frommonth&fromstatsmonth=$fromstatsmonth&tomonth=$tomonth&tostatsmonth=$tostatsmonth&fromday=$fromday&fromstatsday_sday=$fromstatsday_sday&fromstatsmonth_sday=$fromstatsmonth_sday&today=$today&tostatsday_sday=$tostatsday_sday&tostatsmonth_sday=$tostatsmonth_sday&dsttype=$dsttype&sourcetype=$sourcetype&clidtype=$clidtype&channel=$channel&resulttype=$resulttype&dst=$dst&src=$src&clid=$clid&terminatecause=$terminatecause";?>"> 
                    <?php echo gettext("PREVIOUS");?> </a> -
                    <?php }?>
                    <?php echo ($current_page+1);?> / <?php  echo $nb_record_max;?> 
                    <?php if ($current_page<$nb_record_max-1){?>
                    - <a href="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php  echo ($current_page+1)?><?php  if (!is_null($letter) && ($letter!="")){ echo "&letter=$letter";} 
					echo "&customer=$customer&posted=$posted&Period=$Period&frommonth=$frommonth&fromstatsmonth=$fromstatsmonth&tomonth=$tomonth&tostatsmonth=$tostatsmonth&fromday=$fromday&fromstatsday_sday=$fromstatsday_sday&fromstatsmonth_sday=$fromstatsmonth_sday&today=$today&tostatsday_sday=$tostatsday_sday&tostatsmonth_sday=$tostatsmonth_sday&dsttype=$dsttype&sourcetype=$sourcetype&clidtype=$clidtype&channel=$channel&resulttype=$resulttype&dst=$dst&src=$src&clid=$clid&terminatecause=$terminatecause";?>"> 
                    <?php echo gettext("NEXT");?> </a> <img src="<?php echo Images_Path_Main ?>/fleche-d.gif" width="5" height="10">
                    </B></SPAN> 
                    <?php }?>
                  </TD>
              </TBODY>
            </TABLE></TD>
        </TR>
      </table>

<?php  if (is_array($list) && count($list)>0 && 3==4){ ?>
<!-- ************** TOTAL SECTION ************* -->
			<br/>
			<div style="padding-right: 15px;">
			<table cellpadding="1" bgcolor="#000000" cellspacing="1" width="200" align="right">
				<tbody>
                <tr class="form_head">                   									   
				   <td width="33%" align="center" class="tableBodyRight" bgcolor="#600101" style="padding: 5px;"><strong><?php echo gettext("Total Cost");?></strong></td>
				   
                </tr>
				<tr>
				  <td valign="top" align="center" class="tableBody" bgcolor="white"><b><?php echo $total_cost[0][0]?></b></td>
				  
				</tr>
			</table>
			</div>
			<br/><br/>
					
<!-- ************** TOTAL SECTION ************* -->
<?php  } ?>

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
 <table border="0" cellspacing="0" cellpadding="0" width="80%"><tbody><tr><td align="left" height="30">
		<table cellspacing="0" cellpadding="1" bgcolor="#000000" width="50%"><tbody><tr><td>
			<table cellspacing="0" cellpadding="0" width="100%"><tbody>
				<tr><td class="callhistory_td1" align="left" ><?php echo gettext("SUMMARY");?></td></tr>
			</tbody></table>
		</td></tr></tbody></table>
 </td></tr></tbody></table>

<!-- FIN TITLE GLOBAL MINUTES //-->

<table border="0" cellspacing="0" cellpadding="0"  width="80%">
<tbody><tr><td bgcolor="#000000">			
	<table border="0" cellspacing="1" cellpadding="2" width="100%"><tbody>
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
		</tr><tr>
		<td align="right" class="sidenav" nowrap="nowrap"><font class="callhistory_td5"><?php echo $data[0]?></font></td>
		<td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i]?>" align="right" nowrap="nowrap" class="fontstyle_001"><?php echo $minutes?> </td>
        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i]?>" align="left" nowrap="nowrap" width="<?php echo $widthbar+60?>">
        <table cellspacing="0" cellpadding="0"><tbody><tr>
        <td bgcolor="#e22424"><img src="<?php echo Images_Path_Main ?>/spacer.gif" width="<?php echo $widthbar?>" height="6"></td>
        </tr></tbody></table></td>
        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i]?>" align="right" nowrap="nowrap" class="fontstyle_001"><?php echo $data[3]?></td>
        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i]?>" align="right" nowrap="nowrap" class="fontstyle_001" ><?php echo $tmc?> </td>
		<td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i]?>" align="right" nowrap="nowrap" class="fontstyle_001"><?php  display_2bill($data[2]) ?></td>
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
	<tr class="callhistory_td2">
		<td align="right" nowrap="nowrap" class="callhistory_td4"><?php echo gettext("TOTAL");?></td>
		<td align="center" nowrap="nowrap" colspan="2" class="callhistory_td4"><?php echo $totalminutes?> </td>
		<td align="center" nowrap="nowrap" class="callhistory_td4"><?php echo $totalcall?></td>
		<td align="center" nowrap="nowrap" class="callhistory_td4"><?php echo $total_tmc?></td>
		<td align="center" nowrap="nowrap" class="callhistory_td4"><?php  display_2bill($totalcost) ?></td>
	</tr>
	<!-- FIN TOTAL -->

	  </tbody></table>
	  <!-- Fin Tableau Global //-->

</td></tr></tbody></table>

<?php  }else{ ?>
	<center><h3><?php echo gettext("No calls in your selection");?>.</h3></center>
<?php  } ?>
</center>

<?php
$smarty->display( 'footer.tpl');
?>
