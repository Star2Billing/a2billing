<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,   
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 * 
 * @copyright   Copyright (C) 2004-2009 - Star2billing S.L. 
 * @author      Belaid Arezqui <areski@gmail.com>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 * @package     A2Billing
 *
 * Software License Agreement (GNU Affero General Public License)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * 
**/


include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("../lib/admin.smarty.php");

if (!has_rights(ACX_CALL_REPORT)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

getpost_ifset(array (
	'posted',
	'Period',
	'frommonth',
	'fromstatsmonth',
	'tomonth',
	'tostatsmonth',
	'fromday',
	'fromstatsday_sday',
	'fromstatsmonth_sday',
	'today',
	'tostatsday_sday',
	'tostatsmonth_sday',
	'current_page',
	'lst_time',
	'group_id',
	'report_type'
));

//     Initialization of variables	///////////////////////////////

$condition = "";
$QUERY = '';
$from_to = '';
$bool = false;

//     Generating WHERE CLAUSE		///////////////////////////////

normalize_day_of_month($fromstatsday_sday, $fromstatsmonth_sday, 1);
normalize_day_of_month($tostatsday_sday, $tostatsmonth_sday, 1);
if ($Period == "Time" && $lst_time != "") {
	if (strlen($condition) > 0)
		$condition .= " AND ";
	if (DB_TYPE == "postgres") {
		switch ($lst_time) {
			case 1 :
				$condition .= "CURRENT_TIMESTAMP - interval '1 hour' <= cdr.starttime";
				break;
			case 2 :
				$condition .= "CURRENT_TIMESTAMP - interval '6 hours' <= cdr.starttime";
				break;
			case 3 :
				$condition .= "CURRENT_TIMESTAMP - interval '1 day' <= cdr.starttime";
				break;
			case 4 :
				$condition .= "CURRENT_TIMESTAMP - interval '7 days' <= cdr.starttime";
				break;
			case 5 :
				$condition .= "CURRENT_TIMESTAMP - interval '1 month' <= cdr.starttime";
				break;

		}
	} else {
		switch ($lst_time) {
			case 1 :
				$condition .= "DATE_SUB(NOW(),INTERVAL 1 HOUR) <= (cdr.starttime)";
				break;
			case 2 :
				$condition .= "DATE_SUB(NOW(),INTERVAL 6 HOUR) <= (cdr.starttime)";
				break;
			case 3 :
				$condition .= "DATE_SUB(NOW(),INTERVAL 1 DAY) <= (cdr.starttime)";
				break;
			case 4 :
				$condition .= "DATE_SUB(NOW(),INTERVAL 7 DAY) <= (cdr.starttime)";
				break;
			case 5 :
				$condition .= "DATE_SUB(NOW(),INTERVAL 1 MONTH) <= (cdr.starttime)";
				break;
		}
	}
}
elseif ($Period == "Day" && $fromday && $today) {
	if ($fromday && isset ($fromstatsday_sday) && isset ($fromstatsmonth_sday)) {
		if (strlen($condition) > 0)
			$condition .= " AND ";
		$condition .= " $UNIX_TIMESTAMP(cdr.starttime) >= $UNIX_TIMESTAMP('$fromstatsmonth_sday-$fromstatsday_sday')";
	}
	if ($today && isset ($tostatsday_sday) && isset ($tostatsmonth_sday)) {
		if (strlen($condition) > 0)
			$condition .= " AND ";
		$condition .= " $UNIX_TIMESTAMP(cdr.starttime) <= $UNIX_TIMESTAMP('$tostatsmonth_sday-" . sprintf("%02d", intval($tostatsday_sday) /*+1*/
		) . " 23:59:59')";
	}
} else {
	$bool = true;
	if (DB_TYPE == "postgres") {
		$condition .= "CURRENT_TIMESTAMP - interval '1 day' <= cdr.starttime";
	} else {
		$condition .= "DATE_SUB( NOW( ) , INTERVAL 1 DAY ) <= cdr.starttime";
	}
}
#save conditions for later use
if ($posted == "1") {
	$_SESSION['condition'] = $condition;
	$_SESSION['group_id'] = "";
	$_SESSION['report_type'] = $report_type;
} else {
	if (isset ($_SESSION['condition']) && strlen($_SESSION['condition']) > 5) {
		$condition = $_SESSION['condition'];
	}
	if (isset ($_SESSION['report_type']) && strlen($_SESSION['report_type']) > 0) {
		$report_type = $_SESSION['report_type'];
	}
}
if (isset ($group_id)) {
	$_SESSION['group_id'] = $group_id;
} else {
	if (isset ($_SESSION['group_id']) && strlen($_SESSION['group_id']) > 1) {
		$group_id = $_SESSION['group_id'];
	}
}
if (!isset ($report_type)) {
	$report_type = 1;
}
// #### HEADER SECTION
$smarty->display('main.tpl');


?>
<div align="center">
<FORM METHOD=POST name="myForm" ACTION="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>">
	<INPUT TYPE="hidden" NAME="posted" value=1>
	<INPUT TYPE="hidden" NAME="current_page" value=0>	
		<table class="bar-status" width="85%" border="0" cellspacing="1" cellpadding="2" align="center">
			<tr>
        		<td align="left" class="bgcolor_004">
					<input type="radio" name="Period" value="Day" <?php  if ($Period=="Day"){ ?>checked="checked" <?php  } ?>> 
					<font class="fontstyle_003"><?php echo gettext("Select Day");?></font>
				</td>
      			<td align="left" class="bgcolor_005">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr><td class="fontstyle_searchoptions">
	  				<input type="checkbox" name="fromday" value="true" <?php  if ($fromday){ ?>checked<?php }?>> <?php echo gettext("From");?> :
					<select name="fromstatsday_sday" class="form_input_select">
						<?php  
						for ($i=1;$i<=31;$i++) {
							if ($fromstatsday_sday==sprintf("%02d",$i)) $selected="selected";
							else	$selected="";
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
							} else {
								$monthnumber=11;
							}		   
							for ($j=$monthnumber;$j>=0;$j--) {	
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
							if ($year_actual==$i) {
								$monthnumber = date("n")-1; // Month number without lead 0.
							} else {
								$monthnumber=11;
							}		   
							for ($j=$monthnumber;$j>=0;$j--)
{	
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
					<font class="fontstyle_003">&nbsp;<?php echo gettext("Select Time");?></font>
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
                                <td class="bgcolor_002" align="left"><font class="fontstyle_003">&nbsp;<?php echo gettext("Report Type");?></font></td>
                                <td class="bgcolor_003" align="left">
                                <select name="report_type" style="width:100px;" class="form_input_select">
                                <option value="1" <?php if ($report_type == 1) echo "selected"?>>GROUP</option>
                                <option value="2" <?php if ($report_type == 2) echo "selected"?>>CALLPLAN</option>
                                </select>
                                </td>
                        </tr>

			<tr>
        		<td class="bgcolor_004" align="left" > </td>

				<td class="bgcolor_005" align="center" >
					<input type="image"  name="image16" align="top" border="0" src="<?php echo Images_Path;?>/button-search.gif" />
					
	  			</td>
    		</tr>
		</table>
</FORM>


<?php

$HD_Form = new FormHandler("pnl_report","PNL Report");

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();


$condition1=str_replace('cdr.starttime','date',$condition);
$condition2=str_replace('cdr.starttime','firstusedate',$condition);
$payphones=$A2B->config["webui"]["report_pnl_pay_phones"];
$tallfree=$A2B->config["webui"]["report_pnl_tall_free"];
$payphones=str_replace(' ','',$payphones);
$tallfree=str_replace(' ','',$tallfree);
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
 if ($report_type==1){
	$q_id_group="id_group";
	$q_cg_name="cg.name";
	$q_t1="cc";
 }elseif($report_type==2){
        $q_id_group="id_tariffgroup";
	$q_cg_name="cg.tariffgroupname as name ";
	$q_t1="cdr";
 }
}else {
	$q_id_group="destination";
	$q_cg_name="cg.destination as name";
 	$q_t1="cdr";
	if ($report_type==1){
		$q_where="  AND cc.id_group=$group_id";
	}elseif($report_type==2){
		$q_where="  AND cc.tariff=$group_id";
	}
};

$HD_Form -> DBHandle -> Execute("SET autocommit = 0");

$QUERY="
select  id,name,call_count,time_minutes,tall_free_buy_cost,pay_phone_buy_cost,orig_only,credits,orig_total,
	tall_free_sell_cost,pay_phone_sell_cost,term_only,charges,term_total,   first_use,discount,
	net_revenue,(net_revenue-orig_total) as profit, (net_revenue-orig_total)/net_revenue*100 as  margin
from(
select main_id as id, name,call_count,time_minutes,tall_free_buy_cost,pay_phone_buy_cost,orig_only,credits,orig_cost+credits as orig_total,
	tall_free_sell_cost,pay_phone_sell_cost,term_only,charges,term_cost+charges as term_total,
	first_use,discount,
        ((term_cost+charges))*( 1-discount/100)            as net_revenue
from(
 select  t1.$q_id_group as main_id,$q_cg_name,call_count,time_minutes,tall_free_buy_cost,pay_phone_buy_cost,
	orig_cost-tall_free_buy_cost-pay_phone_buy_cost  as orig_only,orig_cost,
  	case when credits is null then 0 else credits end as credits,0 as total,
  	tall_free_sell_cost,pay_phone_sell_cost,term_cost-tall_free_sell_cost-pay_phone_sell_cost as term_only,term_cost,
  	case when charges is null then 0 else  charges end as  charges,
  	first_use,discount

 from
 (
  select  $q_id_group,count(*) as call_count ,sum(sessiontime) div 60 as time_minutes,
        sum( case when tall_free=0 then 0 else real_sessiontime/60*tf_cost end) as tall_free_buy_cost,
        sum( case when pay_phone=0 then 0 else real_sessiontime/60*tf_cost end) as pay_phone_buy_cost,
        sum(buycost) as orig_cost,
        sum( case when tall_free=0 then 0 else real_sessiontime/60*tf_sell_cost end) as tall_free_sell_cost,
        sum( case when pay_phone=0 then 0 else real_sessiontime/60*tf_sell_cost end) as pay_phone_sell_cost,
        sum(sessionbill) as term_cost,
        sum(discount*sessionbill)/sum(sessionbill) as discount
  from (
   select $q_t1.$q_id_group,
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
 ) as t1 ";
 
if(!isset($group_id)) {
 if ($report_type==1) {
	 $HD_Form -> DBHandle -> Execute("create temporary table pnl_report_sub1 as
					   select cc.id_group,sum(cr.credit) as credits from cc_logrefill cr
						 left join cc_card  cc on cc.id=cr.card_id
					         where refill_type=1 and $condition1
					         group by id_group");
	 $HD_Form -> DBHandle -> Execute("create index pnl_report_sub1_get on pnl_report_sub1(id_group)");
	 $HD_Form -> DBHandle -> Execute("create temporary table pnl_report_sub2 as
					    select cc.id_group,-sum(cr.credit) as charges from cc_logrefill cr
				   	      left join cc_card  cc on cc.id=cr.card_id
				              where refill_type=2 and $condition1 
				         group by  id_group ");
	 $HD_Form -> DBHandle -> Execute("create index pnl_report_sub2_get on pnl_report_sub2(id_group)");
	 $HD_Form -> DBHandle -> Execute("create temporary table pnl_report_sub3 as
						 select id_group,count(*) as first_use from cc_card
						   where $condition2 group by id_group");
 $HD_Form -> DBHandle -> Execute("create index pnl_report_sub3_get on pnl_report_sub3(id_group)");

 $QUERY.=" left join cc_card_group as cg on cg.id=id_group left join pnl_report_sub1 as t2 on t1.id_group=t2.id_group 
	   left join pnl_report_sub2  as t3 on t1.id_group=t3.id_group
	   left join  pnl_report_sub3 as t4 on t1.id_group=t4.id_group
	 )as result
	)as final
";
  } elseif($report_type==2) {
	 $HD_Form -> DBHandle -> Execute("create temporary table pnl_report_sub1 as
		select cc.tariff as id_tariffgroup,
	             sum(case when refill_type=1 then cr.credit else 0 end ) as credits,
		     - sum(case when refill_type=2 then  cr.credit else 0 end ) as charges      
            	from cc_logrefill cr left join cc_card as  cc on cc.id=cr.card_id
        	where $condition1
        	group by cc.tariff");
	 $HD_Form -> DBHandle -> Execute("create temporary table pnl_report_sub2 as
					    select tariff as id_tariffgroup,count(*) as first_use
					    from cc_card where $condition2
					 group by tariff");
	 $HD_Form -> DBHandle -> Execute("create index pnl_report_sub1_get on pnl_report_sub1(id_tariffgroup)");
	 $HD_Form -> DBHandle -> Execute("create index pnl_report_sub2_get on pnl_report_sub2(id_tariffgroup)");
	 $QUERY.=" left join cc_tariffgroup as cg on cg.id=id_tariffgroup 
		   left join pnl_report_sub1 as t2 on t1.id_tariffgroup=t2.id_tariffgroup 
		   left join  pnl_report_sub2 as t4 on t1.id_tariffgroup=t4.id_tariffgroup
	 )as result
	)as final
";
  }
} else {
	$QUERY.= "left join cc_prefix as cg on cg.prefix=t1.destination,  " .
			 "(select '-' as  credits,'-' as charges,'-' as first_use) as t2 " .
			 ")as result )as final ";
}



$HD_Form -> DBHandle -> Execute("create temporary table pnl_report  as $QUERY ");

$FG_DEBUG = 0;


// THIS VARIABLE DEFINE THE COLOR OF THE HEAD TABLE
$FG_TABLE_HEAD_COLOR = "#D1D9E7";
$FG_TABLE_EXTERN_COLOR = "#7F99CC";
$FG_TABLE_INTERN_COLOR = "#EDF3FF";
// THIS VARIABLE DEFINE THE COLOR OF THE HEAD TABLE
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#FFFFFF";
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#F2F8FF";


function linktonext_1($value){
	$handle = DbConnect();
        $inst_table = new Table("cc_card_group", "id");
        $FG_TABLE_CLAUSE = "name = '$value'";
        $list_group = $inst_table -> Get_list ($handle, $FG_TABLE_CLAUSE, "", "", "", "", "", "", "", 10);
        $id = $list_group[0][0];
    if($id > 0){
        echo "<a href=\"call-pnl-report.php?group_id=$id&report_type=1\">$value</a>";
    }else{
        echo $value;
    }
}
function linktonext_2($value){
        $handle = DbConnect();
        $inst_table = new Table("cc_tariffgroup", "id");
        $FG_TABLE_CLAUSE = "tariffgroupname = '$value'";
        $list_group = $inst_table -> Get_list ($handle, $FG_TABLE_CLAUSE, "", "", "", "", "", "", "", 10);
        $id = $list_group[0][0];
    if($id > 0){
        echo "<a href=\"call-pnl-report.php?group_id=$id&report_type=2\">$value</a>";
    }else{
        echo $value;
    }
}



if(!isset($group_id)){
	if ($report_type==1){
 	 $HD_Form -> AddViewElement(gettext("Group"), "name", "*", "center", "SORT", "19","", "", "", "", "", "linktonext_1");
	}elseif($report_type==2){
	 $HD_Form -> AddViewElement(gettext("Callplan"),"name", "*", "center", "SORT", "19","", "", "", "", "", "linktonext_2");
	} 
} else {
 $HD_Form -> AddViewElement(gettext("Country"), "name", "*", "center", "SORT", "19","", "", "", "", "", "");
}
$HD_Form -> AddViewElement(gettext("CallCount"), "call_count", "*", "center", "SORT", "30");
$HD_Form -> AddViewElement(gettext("Minutes"), "time_minutes", "*", "center", "SORT", "30");
$HD_Form -> AddViewElement(gettext("Toll Free Cost"), "tall_free_buy_cost", "*", "center", "SORT", "30","", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("Pay Phone Cost"), "pay_phone_buy_cost", "*", "center", "SORT",30,"", "", "", "", "", "display_2dec" );
$HD_Form -> AddViewElement(gettext("Origination Cost"), "orig_only", "*", "center", "SORT", "30","", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("Credits"), "credits", "*", "center", "SORT", "30","", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("Total Cost"),"orig_total", "*", "center", "SORT", "30","", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("Toll Free Revenu"),"tall_free_sell_cost","*", "center", "SORT", "30","", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("Pay Phone Revenu"),"pay_phone_sell_cost","*", "center", "SORT", "30","", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("Termination Revenu"),"term_only","*", "center", "SORT", "30","", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("Extra Charges"),"charges","*", "center", "SORT", "30","", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("Total Revenue"),"term_total","*", "center", "SORT", "30","", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("First Use"),"first_use","*", "center", "SORT", "30");
$HD_Form -> AddViewElement(gettext("Avg Discount"),"discount","*", "center", "SORT", "30","", "", "", "", "", "display_2dec_percentage");
$HD_Form -> AddViewElement(gettext("Net Revenue"),"net_revenue","*", "center", "SORT", "30","", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("Margin"),"margin","*", "center", "SORT", "30","", "", "", "", "", "display_2dec_percentage");
$HD_Form -> AddViewElement(gettext("Total Profit"),"profit","*", "center", "SORT", "30","", "", "", "", "", "display_2dec");


$FG_COL_QUERY="name,call_count,time_minutes,tall_free_buy_cost,pay_phone_buy_cost,orig_only,credits,orig_total,
        tall_free_sell_cost,pay_phone_sell_cost,term_only,charges,term_total,   first_use,discount,
        net_revenue,  margin, profit, id";

$FG_COL_QUERY_SUM=str_replace('name,',"'TOTAL',",$FG_COL_QUERY);
$FG_COL_QUERY_SUM=str_replace(',','),sum(',$FG_COL_QUERY.')');
$FG_COL_QUERY_SUM=str_replace(' ','',$FG_COL_QUERY_SUM);
$FG_COL_QUERY_SUM=str_replace('sum(discount)','(1-sum(net_revenue)/sum(term_total))*100',$FG_COL_QUERY_SUM);
$FG_COL_QUERY_SUM=str_replace('sum(margin)','sum(profit)/sum(net_revenue)*100',$FG_COL_QUERY_SUM);
$FG_COL_QUERY_SUM=str_replace('name)',"'TOTAL'",$FG_COL_QUERY_SUM);

$HD_Form -> FG_TOTAL_TABLE_COL=19;



$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_HTML_TABLE_WIDTH ="90%";
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


$HD_Form -> create_form ($form_action, $list, $id=null) ;



$res = $HD_Form -> DBHandle -> Execute("select $FG_COL_QUERY_SUM from pnl_report");
if ($res){

	?><br><br><table cellspacing="0" cellpadding="0" border="0" align="center" width="95%">
	<tr class="form_head"><td class='tableBody'></td>
	<td class='tableBody'><?php gettext('Total Calls');?></td>
	<td class='tableBody'><?php gettext('Total Min');?></td>
	<td class='tableBody'><?php gettext('Toll Free Cost');?></td>
	<td class='tableBody'><?php gettext('PayPhone Cost');?></td>
	<td class='tableBody'><?php gettext('Origination Cost');?></td>
	<td class='tableBody'><?php gettext('Credits');?></td>
	<td class='tableBody'><?php gettext('Total Cost');?></td>
	<td class='tableBody'><?php gettext('Toll Free Revenue');?></td>
	<td class='tableBody'><?php gettext('Pay Phone Revenue');?></td>
	<td class='tableBody'><?php gettext('Termination Revenue');?></td>
	<td class='tableBody'><?php gettext('Extra Charges');?></td>
	<td class='tableBody'><?php gettext('Total Revenue');?></td>
	<td class='tableBody'><?php gettext('First Use');?></td>
	<td class='tableBody'><?php gettext('Average Discount');?></td>
	<td class='tableBody'><?php gettext('Net Revenue');?></td>
	<td class='tableBody'><?php gettext('Margin');?></td>
	<td class='tableBody'><?php gettext('Total Profit');?></td></tr>
	<?php
	$roa=array();
	$row =$res -> fetchRow();
	echo "<TR>";
	for($k=0;$k<18;$k++) {
		echo "<TD class='tableBody'>";
	 	if ($k<3) {
			echo $row[$k];
		} else {
			echo number_format($row[$k],2);
			if(($k==14)||($k==16)) {
				echo "%";
			}
		}
		echo "</TD>";
	}?>
	
	</tr><td colspan="19" class="tableDivider"><img height="1" width="1" src="../Public/templates/default/images/clear.gif"/></td>
</table>

<?php         
}
?>
</div>
<?php
// Code for the Export Functionality
//* Query Preparation.
$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR]= $QUERY;
if (strlen($HD_Form->FG_TABLE_CLAUSE)>1)
        $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR] .= " WHERE $HD_Form->FG_TABLE_CLAUSE ";
if (!is_null ($HD_Form->FG_ORDER) && ($HD_Form->FG_ORDER!='') && !is_null ($HD_Form->FG_SENS) && ($HD_Form->FG_SENS!=''))
        $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR].= " ORDER BY $HD_Form->FG_ORDER $HD_Form->FG_SENS";


$smarty->display('footer.tpl');

