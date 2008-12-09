<?php
include_once  ("../../lib/admin.defines.php");

$checkdate = date("Y-m-d");
$DBHandle  = DbConnect();
$QUERY_COUNT_CALL_ALL = "select count(*) from cc_call WHERE starttime >= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP";
$QUERY_COUNT_CALL_ANSWERED = "select count(*) from cc_call WHERE starttime >= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP AND terminatecauseid = 1";
$QUERY_COUNT_CALL_BUSY = "select count(*) from cc_call WHERE starttime >= TIMESTAMP('$checkdate') AND starttime < CURRENT_TIMESTAMP AND terminatecauseid = 2";
$QUERY_COUNT_CALL_NOANSWER = "select count(*) from cc_call WHERE starttime >= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP AND terminatecauseid = 3";
$QUERY_COUNT_CALL_CANCELLED = "select count(*) from cc_call WHERE starttime >= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP AND terminatecauseid = 4";
$QUERY_COUNT_CALL_CONGESTED = "select count(*) from cc_call WHERE starttime >= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP AND terminatecauseid = 5";
$QUERY_COUNT_CALL_CHANUNAVAIL = "select count(*) from cc_call WHERE starttime >= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP AND terminatecauseid = 6";
$QUERY_COUNT_CALL_TIMES = "SELECT  sum(sessiontime) FROM cc_call WHERE starttime>= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP ;";
$QUERY_COUNT_CALL_SELL = "SELECT  sum(sessionbill) FROM cc_call WHERE starttime>= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP ;";
$QUERY_COUNT_CALL_BUY = "SELECT  sum(buycost) FROM cc_call WHERE starttime>= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP ;";



$table = new Table('cc_call','*');
$result = $table -> SQLExec($DBHandle,$QUERY_COUNT_CALL_ALL);
$result_count_all= $result[0][0];
$result = $table -> SQLExec($DBHandle,$QUERY_COUNT_CALL_ANSWERED);
$result_count_answered= $result[0][0];
$result = $table -> SQLExec($DBHandle,$QUERY_COUNT_CALL_NOANSWER);
$result_count_noanswer= $result[0][0];
$result = $table -> SQLExec($DBHandle,$QUERY_COUNT_CALL_CANCELLED);
$result_count_cancelled= $result[0][0];
$result = $table -> SQLExec($DBHandle,$QUERY_COUNT_CALL_CONGESTED);
$result_count_congested= $result[0][0];
$result = $table -> SQLExec($DBHandle,$QUERY_COUNT_CALL_BUSY);
$result_count_busy= $result[0][0];
$result = $table -> SQLExec($DBHandle,$QUERY_COUNT_CALL_CHANUNAVAIL);
$result_count_chanunavail= $result[0][0];
$result = $table -> SQLExec($DBHandle,$QUERY_COUNT_CALL_TIMES);

$result_count_calls_times = $result[0][0];
$result = $table -> SQLExec($DBHandle,$QUERY_COUNT_CALL_SELL);
$result_count_calls_sell = $result[0][0];
$result = $table -> SQLExec($DBHandle,$QUERY_COUNT_CALL_BUY);
$result_count_calls_buy = $result[0][0];
if($result_count_calls_buy == null) echo "NULLL";
?>

<?php echo gettext("TOTAL NUMBERS OF CALLS TODAY");?>&nbsp;:&nbsp; <font style="color:#EE6564;" > <?php echo $result_count_all; ?> </font> <br/>
<?php if($result_count_answered>0){ ?>	
  &nbsp;<?php echo gettext("NUMBERS OF CALLS ANSWERED");?>&nbsp;:&nbsp;<?php echo $result_count_answered; ?><br/>
<?php } ?>
<?php if($result_count_busy>0){ ?>
	&nbsp;<?php echo gettext("NUMBERS OF CALLS BUSIED");?>&nbsp;:&nbsp;<?php echo $result_count_busy; ?><br/>
<?php } ?>
<?php if($result_count_noanswer>0){ ?> 	
	&nbsp;<?php echo gettext("NUMBERS OF CALLS NO ANSWERED");?>&nbsp;:&nbsp;<?php echo $result_count_noanswer; ?><br/>
<?php } ?>
<?php if($result_count_cancelled>0){ ?>
 	&nbsp;<?php echo gettext("NUMBERS OF CALLS CANCELLED");?>&nbsp;:&nbsp;<?php echo $result_count_cancelled; ?><br/>
<?php } ?>
<?php if($result_count_congested>0){ ?>
  	&nbsp;<?php echo gettext("NUMBERS OF CALLS CONGESTED");?>&nbsp;:&nbsp;<?php echo $result_count_congested; ?><br/>
<?php } ?>
<?php if($result_count_chanunavail>0){ ?>
	&nbsp;<?php echo gettext("NUMBERS OF CALLS WITH CHANNEL UNAVAILABLE");?>&nbsp;:&nbsp;<?php echo $result_count_chanunavail; ?><br/>
<?php } ?>
<?php echo gettext("AMOUNT SELL TODAY TODAY");?>&nbsp;:&nbsp; <font style="color:#EE6564;" > <?php if($result_count_calls_buy == null){echo "0";}else{ echo $result_count_calls_times;} ?> </font>&nbsp;sec <br/>
<?php echo gettext("AMOUNT BUY TODAY");?>&nbsp;:&nbsp; <font style="color:#EE6564;" > <?php if($result_count_calls_buy == null){echo "0";}else{ echo $result_count_calls_sell;} ?> </font>&nbsp;<?php echo $A2B->config["global"]["base_currency"];?>  <br/>
<?php echo gettext("TOTAL TIME USED TODAY");?>&nbsp;:&nbsp; <font style="color:#EE6564;" > <?php if($result_count_calls_buy == null){echo "0";}else{ echo $result_count_calls_buy;} ?> </font>&nbsp;<?php echo $A2B->config["global"]["base_currency"];?>  <br/>