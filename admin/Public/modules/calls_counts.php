<?php
include_once ("../../lib/admin.defines.php");
include_once ("../../lib/admin.module.access.php");

if (!has_rights(ACX_DASHBOARD)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}
$checkdate = date("Y-m-d");
$DBHandle = DbConnect();

$QUERY_COUNT_CALL_ALL = "select terminatecauseid, count(*) from cc_call WHERE starttime >= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP GROUP BY terminatecauseid";

$QUERY_COUNT_CALL_BILL = "SELECT sum(sessiontime), sum(sessionbill), sum(buycost) FROM cc_call WHERE starttime>= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP ;";

$table = new Table('cc_call', '*');
$result = $table->SQLExec($DBHandle, $QUERY_COUNT_CALL_ALL);

$result_count_all = 0;
$result_count_answered = 0;
$result_count_noanswer = 0;
$result_count_cancelled = 0;
$result_count_congested = 0;
$result_count_busy = 0;
$result_count_chanunavail = 0;

foreach ($result as $res_row){
	if ($res_row[0]==1)
		$result_count_answered = $res_row[1];
	if ($res_row[0]==2)
		$result_count_busy = $res_row[1];
	if ($res_row[0]==3)
		$result_count_noanswer = $res_row[1];
	if ($res_row[0]==4)
		$result_count_cancelled = $res_row[1];
	if ($res_row[0]==5)
		$result_count_congested = $res_row[1];	
	if ($res_row[0]==6)
		$result_count_chanunavail = $res_row[1];
	
	$result_count_all = $result_count_all + $res_row[1];
}

$result = $table->SQLExec($DBHandle, $QUERY_COUNT_CALL_BILL);
$result_count_calls_times = $result[0][0];
$result_count_calls_sell = a2b_round($result[0][1]);
$result_count_calls_buy = a2b_round($result[0][2]);
$result_count_calls_profit = $result_count_calls_sell-$result_count_calls_buy;

?>

<?php echo gettext("Total Calls");?>&nbsp;:&nbsp; <font style="color:#EE6564;" > <?php echo $result_count_all; ?> </font> <br/>
&nbsp; :: <?php echo gettext("Answered");?>&nbsp;:&nbsp;<?php echo $result_count_answered; ?>
&nbsp; :: <?php echo gettext("Busy");?>&nbsp;:&nbsp;<?php echo $result_count_busy; ?>
&nbsp; :: <?php echo gettext("Unanswered");?>&nbsp;:&nbsp;<?php echo $result_count_noanswer; ?><br/>
&nbsp; :: <?php echo gettext("Cancelled");?>&nbsp;:&nbsp;<?php echo $result_count_cancelled; ?>
&nbsp; :: <?php echo gettext("Congestion");?>&nbsp;:&nbsp;<?php echo $result_count_congested; ?>
&nbsp; :: <?php echo gettext("Unavailable");?>&nbsp;:&nbsp;<?php echo $result_count_chanunavail; ?><br/>

<br/>

<?php echo gettext("Revenue");?>&nbsp;:&nbsp; <font style="color:#EE6564;" > 
<?php if($result_count_calls_sell == null){echo "0";}else{ echo $result_count_calls_sell;} ?> </font>&nbsp;<?php echo $A2B->config["global"]["base_currency"];?> <br/>
	
<?php echo gettext("Cost");?>&nbsp;:&nbsp; <font style="color:#EE6564;" > 
<?php if($result_count_calls_buy == null){echo "0";}else{ echo $result_count_calls_buy;} ?> </font>&nbsp;<?php echo $A2B->config["global"]["base_currency"];?>  <br/>

<?php echo gettext("Profit");?>&nbsp;:&nbsp; <font style="color:#EE6564;" > 
<?php if($result_count_calls_profit == null){echo "0";}else{ echo $result_count_calls_profit;} ?> </font>&nbsp;<?php echo $A2B->config["global"]["base_currency"];?>  <br/>

<?php echo gettext("Duration");?>&nbsp;:&nbsp; <font style="color:#EE6564;" > 
<?php if($result_count_calls_times == null){echo "0";}else{ echo $result_count_calls_times;} ?> </font>&nbsp;<?php echo gettext("sec");?>  <br/>


