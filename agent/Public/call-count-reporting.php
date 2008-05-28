<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_CALL_REPORT)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();
}

getpost_ifset(array('inputtopvar','topsearch', 'posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'resulttype', 'stitle', 'atmenu', 'current_page', 'order', 'sens', 'choose_currency', 'terminatecause', 'nodisplay','grouped'));

//var $field=array();

switch ($topsearch){
	case "topuser":
		$field=array('CARD ID','username');
	break;
	case "topdestination":
		$field=array('DESTINATION','destination');
	break;	
	default:
		$field=array('CARD ID','username');
}


if (!isset ($current_page) || ($current_page == "")){	
	$current_page=0; 
}

if (!isset ($FG_TABLE_CLAUSE) || strlen($FG_TABLE_CLAUSE)==0){
		
		$cc_yearmonth = sprintf("%04d-%02d-%02d",date("Y"),date("n"),date("d")); 	
		$FG_TABLE_CLAUSE=" $UNIX_TIMESTAMP(starttime) <= $UNIX_TIMESTAMP('$cc_yearmonth')";
}

// this variable specifie the debug type (0 => nothing, 1 => sql result, 2 => boucle checking, 3 other value checking)
$FG_DEBUG = 0;

// The variable FG_TABLE_NAME define the table name to use
$FG_TABLE_NAME="cc_call";

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


if ((!isset($field)) && (count($field)<=1)){
	$field[0]="CARD ID";
	$field[1]="username";	}

$FG_TABLE_COL[]=array (gettext($field[0]), $field[1], "20%", "center","SORT", "", "30", "", "", "", "", "linktocustomer");
$FG_TABLE_COL[]=array (gettext("Duration"), "calltime", "20%", "center", "SORT", "30", "", "", "", "", "", "display_minute");
$FG_TABLE_COL[]=array (gettext("Buy"), "buy", "25%", "center","sort","","","","","","","display_2bill");
$FG_TABLE_COL[]=array (gettext("Sell"), "cost", "25%", "center","sort","","","","","","","display_2bill");
if ($grouped) $FG_TABLE_COL[]=array (gettext("Calldate"), "day", "10%", "center", "SORT", "19", "", "", "", "", "", "display_dateformat");
$FG_TABLE_COL[]=array (gettext("terminatecause"), "terminatecause", "10%", "center", "SORT", "30");

if ((isset($inputtopvar)) && ($inputtopvar!="") && (isset($topsearch)) && ($topsearch!="")){
	$FG_TABLE_COL[]=array (gettext("NbrCall"), 'nbcall', "10%", "center", "SORT");
}

if ($grouped){
	$FG_COL_QUERY=$field[1].', sum(sessiontime) AS calltime, sum(sessionbill) as cost, sum(buycost) as buy,substring(starttime,1,10) AS day,terminatecause, count(*) as nbcall';
	$SQL_GROUP="GROUP BY ".$field[1].",day,terminatecause ";
}else{
	$FG_COL_QUERY=$field[1].', sum(sessiontime) AS calltime, sum(sessionbill) as cost, sum(buycost) as buy,terminatecause, count(*) as nbcall';
	$SQL_GROUP="GROUP BY ".$field[1].",terminatecause ";
}

$FG_TABLE_DEFAULT_ORDER = "username";
$FG_TABLE_DEFAULT_SENS = "DESC";
	
// The variable LIMITE_DISPLAY define the limit of record to display by page
$FG_LIMITE_DISPLAY=20;

// Number of column in the html table
$FG_NB_TABLE_COL=count($FG_TABLE_COL);

// The variable $FG_EDITION define if you want process to the edition of the database record
$FG_EDITION=false;

//This variable will store the total number of column
$FG_TOTAL_TABLE_COL = $FG_NB_TABLE_COL;
if ($FG_DELETION || $FG_EDITION) $FG_TOTAL_TABLE_COL++;

//This variable define the Title of the HTML table
$FG_HTML_TABLE_TITLE=gettext(" - Call Report - ");

//This variable define the width of the HTML table
$FG_HTML_TABLE_WIDTH="96%";




	if ($FG_DEBUG == 3) echo "<br>Table : $FG_TABLE_NAME  	- 	Col_query : $FG_COL_QUERY";
	


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
$lastdayofmonth = date("t", strtotime($tostatsmonth.'-01'));
if ($Period=="Month"){
	if ($frommonth && isset($fromstatsmonth)) $date_clause.=" AND $UNIX_TIMESTAMP(starttime) >= $UNIX_TIMESTAMP('$fromstatsmonth-01')";
	if ($tomonth && isset($tostatsmonth)) $date_clause.=" AND $UNIX_TIMESTAMP(starttime) <= $UNIX_TIMESTAMP('".$tostatsmonth."-$lastdayofmonth 23:59:59')"; 
}else{
	if ($fromday && isset($fromstatsday_sday) && isset($fromstatsmonth_sday)) $date_clause.=" AND $UNIX_TIMESTAMP(starttime) >= $UNIX_TIMESTAMP('$fromstatsmonth_sday-$fromstatsday_sday')";
	if ($today && isset($tostatsday_sday) && isset($tostatsmonth_sday)) $date_clause.=" AND $UNIX_TIMESTAMP(starttime) <= $UNIX_TIMESTAMP('$tostatsmonth_sday-".sprintf("%02d",intval($tostatsday_sday)/*+1*/)." 23:59:59')";
}


if (strpos($date_clause, 'AND') > 0){
	$FG_TABLE_CLAUSE = substr($date_clause,5); 
}


//To select just terminatecause=ANSWER
if (!isset($terminatecause)){
	$terminatecause="ANSWER";
}
if ($terminatecause=="ANSWER") {
	if (strlen($FG_TABLE_CLAUSE)>0) $FG_TABLE_CLAUSE.=" AND ";
	$FG_TABLE_CLAUSE.=" (terminatecause='ANSWER' OR terminatecause='ANSWERED') ";
}

if ($grouped){
	$QUERY_TOTAL = "SELECT sum(sessiontime) AS calltime, sum(sessionbill) as cost, sum(buycost) as buy, count(*) as nbcall,substring(starttime,1,10) AS day FROM $FG_TABLE_NAME  GROUP BY day";
}else{
	$QUERY_TOTAL = "SELECT sum(sessiontime) AS calltime, sum(sessionbill) as cost, sum(buycost) as buy, count(*) as nbcall FROM $FG_TABLE_NAME";
}

if ((isset($inputtopvar)) && ($inputtopvar!="") && (isset($topsearch)) && ($topsearch!="")){
	if ($grouped){
		$FG_COL_QUERY1=$field[1].', sum(sessiontime) AS sessiontime, sum(sessionbill) as sessionbill, sum(buycost) as buycost,substring(starttime,1,10) AS starttime,terminatecause, count(*) as nbcall';
		$SQL_GROUP1=" GROUP BY $field[1],starttime,terminatecause";
	}else{
		$FG_COL_QUERY1=$field[1].', sum(sessiontime) AS sessiontime, sum(sessionbill) as sessionbill, sum(buycost) as buycost,terminatecause, count(*) as nbcall,starttime';
		$SQL_GROUP1=" GROUP BY $field[1],terminatecause,starttime";
	}
	$QUERY = "CREATE TEMPORARY TABLE temp_result AS (SELECT $FG_COL_QUERY1 FROM $FG_TABLE_NAME WHERE $FG_TABLE_CLAUSE  $SQL_GROUP1 ORDER BY nbcall DESC LIMIT $inputtopvar)";
	$res = $DBHandle -> Execute($QUERY);
	if ($res){
		$FG_TABLE_NAME="temp_result";
		if ($grouped){
			$FG_COL_QUERY=$field[1].', sum(sessiontime) AS calltime, sum(sessionbill) as cost, sum(buycost) as buy,substring(starttime,1,10) AS day,terminatecause, nbcall';
			$SQL_GROUP=" GROUP BY ".$field[1].",day,terminatecause,nbcall ";
			$QUERY_TOTAL = "SELECT sum(sessiontime) AS calltime, sum(sessionbill) as cost, sum(buycost) as buy, sum(nbcall) as nbcall,substring(starttime,1,10) AS day FROM $FG_TABLE_NAME GROUP BY day";
		}else{
			$FG_COL_QUERY=$field[1].', sum(sessiontime) AS calltime, sum(sessionbill) as cost, sum(buycost) as buy,terminatecause, nbcall';
			$SQL_GROUP=" GROUP BY ".$field[1].",terminatecause,nbcall ";
			$QUERY_TOTAL = "SELECT sum(sessiontime) AS calltime, sum(sessionbill) as cost, sum(buycost) as buy, sum(nbcall) as nbcall FROM $FG_TABLE_NAME";
		}
		$order = "nbcall";
	}
}

$instance_table = new Table($FG_TABLE_NAME, $FG_COL_QUERY);
if (!$nodisplay){
	$list = $instance_table -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, $order, $sens, null, null, $FG_LIMITE_DISPLAY, $current_page*$FG_LIMITE_DISPLAY,$SQL_GROUP);
	$res = $DBHandle -> Execute($QUERY_TOTAL);
	if ($res){
		$num=$res->RecordCount( );
		for($i=0;$i<$num;$i++)
		{				
			$list_total[]=$res -> fetchRow();
		}
	}
	if ($FG_DEBUG == 3) echo "<br>Clause : $FG_TABLE_CLAUSE";
	$nb_record = $instance_table -> Table_count ($DBHandle);
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


/*******************   TOTAL COSTS  *****************************************

$instance_table_cost = new Table($FG_TABLE_NAME, "sum(.costs), sum(.buycosts)");		
if (!$nodisplay){	
	$total_cost = $instance_table_cost -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, null, null, null, null, null, null);
}
*/


/*************************************************************/



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
	<FORM METHOD=POST name="myForm" ACTION="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>">
	<INPUT TYPE="hidden" NAME="posted" value=1>
	<INPUT TYPE="hidden" NAME="current_page" value=0>	
		<table class="bar-status" width="85%" border="0" cellspacing="1" cellpadding="2" align="center">
			<tbody>
			<tr>
        		<td width="17%" align="left" class="bgcolor_004">

					<input type="radio" name="Period" value="Month" <?php  if (($Period=="Month") || !isset($Period)){ ?>checked="checked" <?php  } ?>> 
					<font class="fontstyle_003"><?php echo gettext("SELECT MONTH");?></b></font>
				</td>
      			<td width="83%" align="left" class="bgcolor_005">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" >
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
					</td><td class="fontstyle_searchoptions">&nbsp;&nbsp;
					<input type="checkbox" name="tomonth" value="true" <?php  if ($tomonth){ ?>checked<?php }?>> 
					To : <select name="tostatsmonth" class="form_input_select">
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
        		<td align="left" class="bgcolor_002">
					<input type="radio" name="Period" value="Day" <?php  if ($Period=="Day"){ ?>checked="checked" <?php  } ?>> 
					<font class="fontstyle_003"><?php echo gettext("SELECT DAY");?></font>
				</td>
      			<td align="left" class="bgcolor_003">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" >
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
					<input type="checkbox" name="today" value="true" <?php  if ($today){ ?>checked<?php }?>> <?php echo gettext("To");?>  :
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
			<TD class="bgcolor_004" align="left">
				<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("TOP");?></font>	
			</TD>
			<td class="bgcolor_005" align="left" >
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>	<TD  align="center" class="fontstyle_searchoptions" >
						<input type="text" name="inputtopvar" value="<?php echo $inputtopvar;?>" class="form_input_text">
					</td>
					<td  align="center" class="fontstyle_searchoptions">
						<input type="radio" name="topsearch" value="topuser"<?php if ($topsearch=="topuser"){ ?> checked="checked" <?php  } ?>><?php echo gettext("Users making the more calls");?>
					</td>
					<td  align="center" class="fontstyle_searchoptions">
						<input type="radio" name="topsearch" value="topdestination"<?php if ($topsearch=="topdestination"){ ?> checked="checked" <?php  } ?>><?php echo gettext("More calls destination");?>
					</td>
				</tr></table>
			</TD>
		</tr>
			<!-- Select Option : to show just the Answered Calls or all calls, Result type, currencies... -->

			
			<tr>
			  <td class="bgcolor_002" align="left" ><font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("OPTIONS");?></font></td>
			  <td class="bgcolor_003" align="center" >
			  
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
			  	<td width="35%" class="fontstyle_searchoptions" >
				
				<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$-->
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="20%"  class="fontstyle_searchoptions">
						<?php echo gettext("SHOW");?> :  						
				   </td>
				   <td width="80%"  class="fontstyle_searchoptions">				   		
				  <?php echo gettext("Answered Calls")?>
				  <input name="terminatecause" type="radio" value="ANSWER" <?php if((!isset($terminatecause))||($terminatecause=="ANSWER")){?>checked<?php }?> /> 
				  <?php echo gettext("All Calls")?>	
				   <input name="terminatecause" type="radio" value="ALL" <?php if($terminatecause=="ALL"){?>checked<?php }?>/>
					</td>
				</tr>
				<tr class="bgcolor_005">
					<td  class="fontstyle_searchoptions">
						<?php echo gettext("GROUP BY DAY");?> : 
				   </td>
				   <td  class="fontstyle_searchoptions">
				   <?php echo gettext("Yes")?>
				  <input name="grouped" type="radio" value="1" <?php if($grouped){?>checked<?php }?> /> 
				  <?php echo gettext("NO")?>
				  <input name="grouped" type="radio" value="0" <?php if((!isset($grouped))||(!$grouped)){?>checked<?php }?>/>
					</td>
				</tr>
				<tr>
					<td  class="fontstyle_searchoptions">
						<?php echo gettext("RESULT");?> :
					</td>
					<td  class="fontstyle_searchoptions">
						
	
					<?php echo gettext("Mins");?><input type="radio" NAME="resulttype" value="min" <?php if((!isset($resulttype))||($resulttype=="min")){?>checked<?php }?>> - <?php echo gettext("Secs")?> <input type="radio" NAME="resulttype" value="sec" <?php if($resulttype=="sec"){?>checked<?php }?>>

					</td>
				</tr>
				<tr class="bgcolor_005">
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
				
				
				<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$-->
				  
				   
			   </td>
			  	
				</tr>
				
				</table>
			  </td>
			  </tr>

			<!-- Select Option : to show just the Answered Calls or all calls, Result type, currencies... -->
			
			
			<tr>
        		<td class="bgcolor_004" align="left" > </td>

				<td class="bgcolor_005" align="center" >
					<input type="image"  name="image16" align="top" border="0" src="<?php echo Images_Path;?>/button-search.gif" />
					
	  			</td>
    		</tr>
		</tbody></table>
	</FORM>
</center>


<br><br>

<!-- ** ** ** ** ** Part to display the CDR ** ** ** ** ** -->

			<center><?php echo gettext("Number of call");?> : <?php  if (is_array($list) && count($list)>0){ echo $nb_record; }else{echo "0";}?></center>
      <table width="<?php echo $FG_HTML_TABLE_WIDTH?>" border="0" align="center" cellpadding="0" cellspacing="0">
<TR bgcolor="#ffffff"> 
          <TD  class="bgcolor_021" height="16px" style="PADDING-LEFT: 5px; PADDING-RIGHT: 3px"> 
            <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
              <TBODY>
                <TR> 
                  <TD><SPAN  class="fontstyle_003"><tr><?php echo $FG_HTML_TABLE_TITLE?></B></SPAN></TD>
                  <TD align=right> <IMG alt="Back to Top" border=0 height=12 src="<?php echo Images_Path;?>/btn_top_12x12.gif" width=12> 
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
                    <a href="<?php  echo $PHP_SELF."?s=1&t=0&stitle=$stitle&atmenu=$atmenu&current_page=$current_page&order=".$FG_TABLE_COL[$i][1]."&sens="; if ($sens=="ASC"){echo"DESC";}else{echo"ASC";} 
					echo "&topsearch=$topsearch&inputtopvar=$inputtopvar&posted=$posted&Period=$Period&frommonth=$frommonth&fromstatsmonth=$fromstatsmonth&tomonth=$tomonth&tostatsmonth=$tostatsmonth&fromday=$fromday&fromstatsday_sday=$fromstatsday_sday&fromstatsmonth_sday=$fromstatsmonth_sday&today=$today&tostatsday_sday=$tostatsday_sday&tostatsmonth_sday=$tostatsmonth_sday&resulttype=$resulttype&terminatecause=$terminatecause&grouped=$grouped";?>"> 
                    <span class="liens"><?php  } ?>
                    <?php echo $FG_TABLE_COL[$i][0]?> 
                    <?php if ($order==$FG_TABLE_COL[$i][1] && $sens=="ASC"){?>
                    &nbsp;<img src="<?php echo Images_Path;?>/icon_up_12x12.GIF" width="12" height="12" border="0"> 
                    <?php }elseif ($order==$FG_TABLE_COL[$i][1] && $sens=="DESC"){?>
                    &nbsp;<img src="<?php echo Images_Path;?>/icon_down_12x12.GIF" width="12" height="12" border="0"> 
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
                  <TD  class="tableDivider" colSpan=<?php echo $FG_TOTAL_TABLE_COL?>><IMG 
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
							 
					<?php for($i=0;$i<$FG_NB_TABLE_COL;$i++){
						$record_display = $recordset[$i];
						if ( is_numeric($FG_TABLE_COL[$i][5]) && (strlen($record_display) > $FG_TABLE_COL[$i][5])  ){
							$record_display = substr($record_display, 0, $FG_TABLE_COL[$i][5]-3)."";  
						} ?>
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
                  <TD class="tableDivider" colSpan=<?php echo $FG_TOTAL_TABLE_COL?>><IMG height=1 
                              src="<?php echo Images_Path;?>/clear.gif" 
                              width=1></TD>
                </TR>
                <TR> 
                  <TD class="tableDivider" colSpan=<?php echo $FG_TOTAL_TABLE_COL?>><IMG height=1 
                              src="<?php echo Images_Path;?>/clear.gif" 
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
                  <TD align="right"><SPAN style="COLOR: #ffffff; FONT-SIZE: 11px"><td> 
                    <?php if ($current_page>0){?>
                    <img src="<?php echo Images_Path;?>/fleche-g.gif" width="5" height="10"> <a href="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php  echo ($current_page-1)?><?php  if (!is_null($letter) && ($letter!="")){ echo "&letter=$letter";} 
					echo "&topsearch=$topsearch&inputtopvar=$inputtopvar&posted=$posted&Period=$Period&frommonth=$frommonth&fromstatsmonth=$fromstatsmonth&tomonth=$tomonth&tostatsmonth=$tostatsmonth&fromday=$fromday&fromstatsday_sday=$fromstatsday_sday&fromstatsmonth_sday=$fromstatsmonth_sday&today=$today&tostatsday_sday=$tostatsday_sday&tostatsmonth_sday=$tostatsmonth_sday&resulttype=$resulttype&terminatecause=$terminatecause&grouped=$grouped";?>">
                    <?php echo gettext("Previous");?> </a> -
                    <?php }?>
                    <?php echo ($current_page+1);?> / <?php  echo $nb_record_max;?>
                    <?php if ($current_page<$nb_record_max-1){?>
                    - <a href="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php  echo ($current_page+1)?><?php  if (!is_null($letter) && ($letter!="")){ echo "&letter=$letter";}
					echo "&posted=$posted&Period=$Period&frommonth=$frommonth&fromstatsmonth=$fromstatsmonth&tomonth=$tomonth&tostatsmonth=$tostatsmonth&fromday=$fromday&fromstatsday_sday=$fromstatsday_sday&fromstatsmonth_sday=$fromstatsmonth_sday&today=$today&tostatsday_sday=$tostatsday_sday&tostatsmonth_sday=$tostatsmonth_sday&clidtype=$clidtype&resulttype=$resulttype&clid=$clid&terminatecause=$terminatecause&topsearch=$topsearch&inputtopvar=$inputtopvar&grouped=$grouped";?>">
                    <?php echo gettext("Next");?></a> <img src="<?php echo Images_Path;?>/fleche-d.gif" width="5" height="10">
                    </B></TD></SPAN> 
                    <?php }?>
                  
              </TBODY>
            </TABLE></TD>
        </TR>
      </table>
<br><br><br>

<?php 
if (is_array($list_total) && count($list_total)>0){
	$maxtotalday="";
	$maxtotalminute=0;
	$totalcall==0;
	$totalminutes=0;
	foreach ($list_total as $data){	
		if ($maxtotalminute < $data[0]){
			$maxtotalday=$data[4];
			$maxtotalminute=$data[0];
		}elseif($maxtotalminute == $data[0]){
			$maxtotalday.="  $data[4]";
		}
		$totalminutes+=$data[0];
		$totalcost+=$data[1];
		$totalbuycost+=$data[2];
		$totalcall+=$data[3];
	}
	if ((!isset($resulttype)) || ($resulttype=="min")){
		$total_tmc=sprintf("%02d",intval(($totalminutes/$totalcall)/60)).":".sprintf("%02d",intval(($totalminutes/$totalcall)%60));
		$totalminutes = sprintf("%02d",intval($totalminutes/60)).":".sprintf("%02d",intval($totalminutes%60));
		$maxtotalminute =sprintf("%02d",intval($maxtotalminute/60)).":".sprintf("%02d",intval($maxtotalminute%60));
	}else{
		$total_tmc = intval($totalminutes/$totalcall);	
	}
}
if (is_array($list) && count($list)>0){
?>
<center>
 <table border="0" cellspacing="1" cellpadding="0" width="80%" bgcolor="Black"><tbody>
	<tr bgcolor="Black">
		<td align="left" colspan="5"><font  class="fontstyle_003"><?php echo gettext("TOTAL");?></font>
		</td>
	</tr>
	<?php if ($grouped){?>
	<tr bgcolor="Black">
		<TD align="center" bgcolor="Silver"><font face="verdana" size="1" color="#000000"><b><?php echo gettext("Days with the maximun total duration");?></b></font>
		</TD>
		<td align="center" colspan="4">	
			<TABLE border="0" cellspacing="1" cellpadding="0" width="100%" bgcolor="Black">
			<tr  class="bgcolor_019">
			<TD align="center" class="sidenav" nowrap="nowrap" width="75%"><font class="fontstyle_006"><?php echo gettext ("DAYS");?></font><//TABLE>
			</TD>
			<TD align="center" class="sidenav" nowrap="nowrap"><font face="verdana" size="1" color="#ffffff"><?php echo gettext("DURATION");?>></font></TD>
			</TD>
			</tr>
			<tr class="bgcolor_021">
			<TD align="center" class="sidenav" nowrap="nowrap"><font class="fontstyle_003"><?php echo $maxtotalday?></b></font></TD>
			</TD>
			<TD align="center" class="sidenav" nowrap="nowrap"><font class="fontstyle_003"><?php echo $maxtotalminute?></b></font></TD>
			</TD>
			</tr>
			</TABLE>
		</td>
	</tr><?php }?>
	<tr class="bgcolor_019">
		<td align="center"><font class="fontstyle_003"><?php echo gettext("DURATION");?></font></td>
		<td align="center"><font class="fontstyle_003"><?php echo gettext("CALLS");?></font></td>
		<td align="center"><font class="fontstyle_003"><?php echo gettext("AVERAGE CONNECTION TIME");?></font></td>
		<td align="center"><font class="fontstyle_003"><?php echo gettext("SELL");?></font></td>
		<td align="center"><font class="fontstyle_003"><?php echo gettext("BUY");?></font></td>
		<!-- LOOP -->
	<tr class="bgcolor_023">
		<td align="center" class="sidenav" nowrap="nowrap"><font class="fontstyle_003"><?php echo $totalminutes?> </font></td>
		<td align="center" class="sidenav" nowrap="nowrap"><font class="fontstyle_003"><?php echo $totalcall?> </font></td>
	        <td align="center" class="sidenav" nowrap="nowrap"><font class="fontstyle_003"><?php echo $total_tmc?></font></td>
        	<td align="center" class="sidenav" nowrap="nowrap"><font class="fontstyle_003"><?php display_2bill($totalcost)?> </font></td>
		<td align="center" class="sidenav" nowrap="nowrap"><font class="fontstyle_003"><?php display_2bill($totalbuycost) ?></font></td>
	</tr>
</tbody></table>

<?php  }else{ ?>
	<center><h3><?php echo gettext("No calls in your selection");?>.</h3></center>
<?php  } ?>
</center>

<?php
	$smarty->display('footer.tpl');
?>
