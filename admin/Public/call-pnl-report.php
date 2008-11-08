<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("../lib/admin.smarty.php");


if (! has_rights (ACX_CALL_REPORT)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}


/***********************************************************************************/

getpost_ifset(array('posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'current_page', 'lst_time','group_id'));


//     Initialization of variables	///////////////////////////////

$condition = "";
$QUERY = '';
$from_to = '';
$bool = false;


//     Generating WHERE CLAUSE		///////////////////////////////

$lastdayofmonth = date("t", strtotime($tostatsmonth.'-01'));
if ($Period=="Month" && $frommonth && $tomonth)
{
	if ($frommonth && isset($fromstatsmonth)) {
		$condition.=" $UNIX_TIMESTAMP(cdr.starttime) >= $UNIX_TIMESTAMP('$fromstatsmonth-01')";
	}
	if ($tomonth && isset($tostatsmonth))
	{
		if (strlen($condition)>0) $condition.=" AND ";
		$condition.=" $UNIX_TIMESTAMP(cdr.starttime) <= $UNIX_TIMESTAMP('".$tostatsmonth."-$lastdayofmonth 23:59:59')";
	}

} else if($Period=="Time" && $lst_time != "") {
	if (strlen($condition)>0) $condition.=" AND ";
	if(DB_TYPE == "postgres"){
		switch($lst_time){
			case 1:
				$condition .= "CURRENT_TIMESTAMP - interval '1 hour' <= cdr.starttime";
			break;
			case 2:
				$condition .= "CURRENT_TIMESTAMP - interval '6 hours' <= cdr.starttime";
			break;
			case 3:
				$condition .= "CURRENT_TIMESTAMP - interval '1 day' <= cdr.starttime";
			break;
			case 4:
				$condition .= "CURRENT_TIMESTAMP - interval '7 days' <= cdr.starttime";
			break;
			case 5:
                                $condition .= "CURRENT_TIMESTAMP - interval '1 month' <= cdr.starttime";
                        break;

		}
	}else{
		switch($lst_time){
			case 1:
				$condition .= "DATE_SUB(NOW(),INTERVAL 1 HOUR) <= (cdr.starttime)";
			break;
			case 2:
				$condition .= "DATE_SUB(NOW(),INTERVAL 6 HOUR) <= (cdr.starttime)";
			break;
			case 3:
				$condition .= "DATE_SUB(NOW(),INTERVAL 1 DAY) <= (cdr.starttime)";
			break;
			case 4:
				$condition .= "DATE_SUB(NOW(),INTERVAL 7 DAY) <= (cdr.starttime)";
			break;
			case 5:
                                $condition .= "DATE_SUB(NOW(),INTERVAL 1 MONTH) <= (cdr.starttime)";
                        break;
		}
	}	
}else if($Period=="Day" && $fromday && $today){
	if ($fromday && isset($fromstatsday_sday) && isset($fromstatsmonth_sday)) 
	{
		if (strlen($condition)>0) $condition.=" AND ";
		$condition.=" $UNIX_TIMESTAMP(cdr.starttime) >= $UNIX_TIMESTAMP('$fromstatsmonth_sday-$fromstatsday_sday')";
	}
	if ($today && isset($tostatsday_sday) && isset($tostatsmonth_sday))
	{
		if (strlen($condition)>0) $condition.=" AND ";
		$condition.=" $UNIX_TIMESTAMP(cdr.starttime) <= $UNIX_TIMESTAMP('$tostatsmonth_sday-".sprintf("%02d",intval($tostatsday_sday)/*+1*/)." 23:59:59')";
	}
}else{
	$bool = true;
	if(DB_TYPE == "postgres"){
		$condition .= "CURRENT_TIMESTAMP - interval '1 day' <= cdr.starttime";
	}
	else {
		$condition .= "DATE_SUB( NOW( ) , INTERVAL 1 DAY ) <= cdr.starttime";
	}
}

	

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
				<option value="5" <?php if ($lst_time == 5) echo "selected"?>>Last month</option>
				</select>
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


<?php

#$res_ALOC  = $instance_table->SQLExec ($DBHandle, $QUERY_ALOC);
#foreach($res_ALOC as $val){
#        $ALOC =  $val[0];
#        $Total_calls = $val[1];
#}



$condition1=str_replace('cdr.starttime','date',$condition);
$condition2=str_replace('cdr.starttime','firstusedate',$condition);
#$SQL="create temporary table temp_dnid(dnid varchar(20),cost float,sell_cost float default 0,dnid_type integer default 2 ) engine=memory";
$payphones=$A2B->config["webui"]["report_pnl_pay_phones"];
$tallfree=$A2B->config["webui"]["report_pnl_tall_free"];
$payphones=str_replace(' ','',$payphones);$tallfree=str_replace(' ','',$tallfree);
$payphones=str_replace('),(',' ,1 as dnid_type union select ',$payphones);
$payphones=str_replace(')',' ,1  ',$payphones);
$tallfree=str_replace('),(',' ,2  union select ',$tallfree);
$tallfree=str_replace(')',' ,2 ',$tallfree);
$tallfree=str_replace('(',' select ',$tallfree);
$payphones=str_replace('(',' select ',$payphones);
$dnids="select 'dnid' as dnid, 0.1 as sell_cost,0.1 as cost,0 as dnid_type";
if (strlen($tallfree)>0)$dnids.=" union ".$tallfree;
if (strlen($payphones)>0)$dnids.=" union ".$payphones;

if(!isset($group_id)){
	$q_id_group="id_group";
	$q_id_tab="cc_card_group ";
	$q_cg_name="cg.name";
	$q_where="";
}else {
	$q_id_group="id_agent";
	$q_id_tab="cc_agent";
	$q_cg_name="cg.login as name";
	$q_where=" and id_group=$group_id";
};
$QUERY="
select main_id as id, name,call_count,time_minutes,tall_free_buy_cost,pay_phone_buy_cost,orig_only,credits,orig_cost+credits as orig_total,
	tall_free_sell_cost,pay_phone_sell_cost,term_only,charges,term_cost+charges as term_total,
	first_use,discount,
	term_cost+charges -(orig_cost+credits  ) as profit,
	(term_cost+charges -(orig_cost+credits  ))*(100-discount)/100/(orig_cost+credits )*100 as margin, 
	(term_cost+charges -(orig_cost+credits  ))*(100-discount)/100 as profit2 
from(
 select  t1.$q_id_group as main_id,$q_cg_name,call_count,time_minutes,tall_free_buy_cost,pay_phone_buy_cost,
	orig_cost-tall_free_buy_cost-pay_phone_buy_cost  as orig_only,orig_cost,
  case when credits is null then 0 else credits end as credits,0 as total,
  tall_free_sell_cost,pay_phone_sell_cost,term_cost-tall_free_sell_cost-pay_phone_sell_cost as term_only,term_cost,
  case when charges is null then 0 else  charges end as  charges,
  0 as total1,first_use,discount

 from
 (
  select  $q_id_group,count(*) as call_count ,sum(sessiontime) div 60 as time_minutes,
        sum( case when tall_free=0 then 0 else real_sessiontime/60*tf_cost end) as tall_free_buy_cost,
        sum( case when pay_phone=0 then 0 else real_sessiontime/60*tf_cost end) as pay_phone_buy_cost,
        sum(buycost) as orig_cost,
        sum( case when tall_free=0 then 0 else real_sessiontime/60*tf_sell_cost end) as tall_free_sell_cost,
        sum( case when pay_phone=0 then 0 else real_sessiontime/60*tf_sell_cost end) as pay_phone_sell_cost,
        sum(sessionbill) as term_cost,
        avg(discount) as discount
  from (
   select cc.$q_id_group,
       cdr.sessiontime,cdr.dnid,cdr.real_sessiontime,sessionbill,buycost,cc.discount,
           case when tf.cost is null then 0 else tf.cost end as tf_cost,
            case when tf.sell_cost is null then 0 else tf.sell_cost end as tf_sell_cost,
                case when tf.dnid_type is null then 0 when tf.dnid_type=1 then 1 else 0 end as tall_free,
                case when tf.dnid_type is null then 0 when tf.dnid_type=2 then 1 else 0 end as pay_phone
            from cc_call cdr left join cc_card cc on cdr.card_id=cc.id left join 
      		($dnids
		) as tf on tf.dnid=substr(cdr.dnid,1,length(tf.dnid))
  where
    sessiontime>0 and $condition $q_where
    order by cdr.starttime desc
   ) as a group by $q_id_group
 ) as t1 left join $q_id_tab as cg on cg.id=$q_id_group left join (
        select cc.$q_id_group,sum(cr.credit) as credits from cc_logrefill cr left join cc_card  cc on cc.id=cr.card_id
         where refill_type=1 and $condition1 $q_where
         group by $q_id_group
 ) as t2 on t1.$q_id_group=t2.$q_id_group left join
 (
        select cc.$q_id_group,-sum(cr.credit) as charges from cc_logrefill cr left join cc_card  cc on cc.id=cr.card_id
         where refill_type=2 and $condition1 $q_where
         group by $q_id_group
 ) as t3 on t1.$q_id_group=t3.$q_id_group left join (
 select $q_id_group,count(*) as first_use from cc_card where $condition2 $q_where
 group by $q_id_group
 )as t4 on t1.$q_id_group=t4.$q_id_group
)as result
";


$HD_Form = new FormHandler("pnl_report","PNL Report");

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();


$HD_Form -> DBHandle -> Execute("create temporary table pnl_report engine =memory  as $QUERY ");


$FG_DEBUG = 0;


// THIS VARIABLE DEFINE THE COLOR OF THE HEAD TABLE
$FG_TABLE_HEAD_COLOR = "#D1D9E7";
$FG_TABLE_EXTERN_COLOR = "#7F99CC"; //#CC0033 (Rouge)
$FG_TABLE_INTERN_COLOR = "#EDF3FF"; //#FFEAFF (Rose)
// THIS VARIABLE DEFINE THE COLOR OF THE HEAD TABLE
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#FFFFFF";
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#F2F8FF";



/*******
name,call_count,time_minutes,tall_free_buy_cost,pay_phone_buy_cost,orig_only,credits,orig_total,
tall_free_sell_cost,pay_phone_sell_cost,term_only,charges, term_total,
first_use,discount,profit,margin, profit2
*******/
function linktonext($value){
	$handle = DbConnect();
        $inst_table = new Table("cc_card_group", "id");
        $FG_TABLE_CLAUSE = "name = '$value'";
        $list_group = $inst_table -> Get_list ($handle, $FG_TABLE_CLAUSE, "", "", "", "", "", "", "", 10);
        $id = $list_group[0][0];
    if($id > 0){
        echo "<a href=\"call-pnl-report.php?group_id=$id\">$value</a>";
    }else{
        echo $value;
    }

}
if(!isset($group_id)){
$HD_Form -> AddViewElement(gettext("Group"), "name", "*", "center", "SORT", "19","", "", "", "", "", "linktonext");
} else {
 $HD_Form -> AddViewElement(gettext("Agent"), "name", "*", "center", "SORT", "19","", "", "", "", "", "");
}
$HD_Form -> AddViewElement(gettext("CallCount"), "call_count", "*", "center", "SORT", "30");
$HD_Form -> AddViewElement(gettext("Minutes"), "time_minutes", "*", "center", "SORT", "30");
#", "10%", "center", "SORT", "15", "lie", "cc_prefix", "destination", "id='%id'", "%1");
$HD_Form -> AddViewElement(gettext("Toll Free Cost"), "tall_free_buy_cost", "*", "center", "SORT", "30","", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("Pay Phone Cost"), "pay_phone_buy_cost", "*", "center", "SORT",30,"", "", "", "", "", "display_2dec" );
$HD_Form -> AddViewElement(gettext("Origination Cost"), "orig_only", "*", "center", "SORT", "30","", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("Credits"), "credits", "*", "center", "SORT", "30","", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("Total Cost"),"orig_total", "*", "center", "SORT", "30","", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("Toll Free Revenu"),"tall_free_sell_cost","*", "center", "SORT", "30","", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("Pay Phone Revenu"),"pay_phone_sell_cost","*", "center", "SORT", "30","", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("Termination Revenu"),"term_only","*", "center", "SORT", "30","", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("Extra Charges"),"charges","*", "center", "SORT", "30","", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("Total Revenu"),"term_total","*", "center", "SORT", "30","", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("First Use"),"first_use","*", "center", "SORT", "30");
$HD_Form -> AddViewElement(gettext("Avg Discount"),"discount","*", "center", "SORT", "30","", "", "", "", "", "display_2dec_percentage");
$HD_Form -> AddViewElement(gettext("Profit Before Discount"),"profit","*", "center", "SORT", "30","", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("Margin"),"margin","*", "center", "SORT", "30","", "", "", "", "", "display_2dec_percentage");
$HD_Form -> AddViewElement(gettext("Total Profit"),"profit2","*", "center", "SORT", "30","", "", "", "", "", "display_2dec");


$FG_COL_QUERY="name,call_count,time_minutes,tall_free_buy_cost,pay_phone_buy_cost,orig_only,credits,orig_total,
tall_free_sell_cost,pay_phone_sell_cost,term_only,charges, term_total,
first_use,discount,profit,margin, profit2";
$FG_COL_QUERY_SUM=str_replace('name,',"'TOTAL',",$FG_COL_QUERY);
$FG_COL_QUERY_SUM=str_replace(',','),sum(',$FG_COL_QUERY.')');
$FG_COL_QUERY_SUM=str_replace(' ','',$FG_COL_QUERY_SUM);
$FG_COL_QUERY_SUM=str_replace('sum(discount)','avg(discount)',$FG_COL_QUERY_SUM);
$FG_COL_QUERY_SUM=str_replace('sum(margin)','sum(orig_total)/sum(profit)',$FG_COL_QUERY_SUM);
$FG_COL_QUERY_SUM=str_replace('name)',"'TOTAL'",$FG_COL_QUERY_SUM);

$HD_Form -> FG_TOTAL_TABLE_COL=19;





$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_HTML_TABLE_WIDTH ="90%";
#$HD_Form -> FG_TABLE_ID="id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";
$HD_Form -> FG_FILTER_SEARCH_SESSION_NAME = 'pnl_selection';
$HD_Form -> FG_FK_DELETE_CONFIRM = true;
$HD_Form -> FG_FK_DELETE_ALLOWED = true;
$HD_Form -> FieldViewElement ($FG_COL_QUERY);

$HD_Form -> CV_NO_FIELDS  = gettext("NO INFO!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


// Code here for adding the fields in the Export File
$HD_Form -> FieldExportElement($FG_COL_QUERY);
if (!($popup_select>=1)) $HD_Form -> FG_EXPORT_CSV = true;
if (!($popup_select>=1)) $HD_Form -> FG_EXPORT_XML = true;
$HD_Form -> FG_EXPORT_SESSION_VAR = "pr_export_pnl_report";

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;


$list = $HD_Form -> perform_action($form_action);





// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


// #### CREATE FORM OR LIST
//$HD_Form -> CV_TOPVIEWER = "menu";
if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];

$HD_Form -> create_form ($form_action, $list, $id=null) ;



#$list = $instance_table -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, $order, $sens, null, null, $FG_LIMITE_DISPLAY, $current_page*$FG_LIMITE_DISPLAY);
#$DBHandle  = DbConnect();
 $res = $HD_Form -> DBHandle -> Execute("select $FG_COL_QUERY_SUM from pnl_report");
 if ($res){
	?><br><br><table cellspacing="0" cellpadding="0" border="0" align="center" width="95%">
	<tr class="form_head"><td class='tableBody'></td>
	<td class='tableBody'>Total Calls</td><td class='tableBody'>Total Min</td><td class='tableBody'>Toll Free Cost</td>
	<td class='tableBody'>PayPhone Cost</td><td class='tableBody'>Origination Cost</td><td class='tableBody'>Credits</td>
	<td class='tableBody'>Total Cost</td><td class='tableBody'>Tall Free Revenu</td><td class='tableBody'>Pay Phone Revenu</td>
	<td class='tableBody'>Termination Revenu</td><td class='tableBody'>Extra Charges</td><td class='tableBody'>Total Revenu</td>
	<td class='tableBody'>First Use</td><td class='tableBody'>Average Discount</td><td class='tableBody'>Profit Before Discount</td><td class='tableBody'
	>Margin</td><td class='tableBody'>Total Profit</td></tr>
			<?php
			$roa=array();
                        $row =$res -> fetchRow();
			echo "<TR>";
			for($k=0;$k<18;$k++) {
			  echo "<TD class='tableBody'>";
			 if ($k<3){
			  echo $row[$k];
			 }else{
				echo number_format($row[$k],2);
				if(($k==14)||($k==16)){
				echo "%";
			           }
			 }
			echo "</TD>";
			}?>
			</tr><td colspan="19" class="tableDivider"><img height="1" width="1" src="../Public/templates/default/images/clear.gif"/></td>
</table>
<?php         
 }



// Code for the Export Functionality
//* Query Preparation.
$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR]= $QUERY;
if (strlen($HD_Form->FG_TABLE_CLAUSE)>1)
        $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR] .= " WHERE $HD_Form->FG_TABLE_CLAUSE ";
if (!is_null ($HD_Form->FG_ORDER) && ($HD_Form->FG_ORDER!='') && !is_null ($HD_Form->FG_SENS) && ($HD_Form->FG_SENS!=''))
        $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR].= " ORDER BY $HD_Form->FG_ORDER $HD_Form->FG_SENS";

$smarty->display('footer.tpl');

?>
