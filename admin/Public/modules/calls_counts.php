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
?>

<?php echo gettext("TOTAL NUMBERS OF CALLS TODAY");?>&nbsp;:&nbsp; <font style="color:#EE6564;" > <?php echo $result_count_all; ?> </font> <br/>
<?php if($result_count_answered>0){ ?>	
  <?php echo gettext("NUMBERS OF CALLS ANSWERED");?>&nbsp;:&nbsp;<?php echo $result_count_answered; ?><br/>
<?php } ?>
<?php if($result_count_busy>0){ ?>
	<?php echo gettext("NUMBERS OF CALLS BUSIED");?>&nbsp;:&nbsp;<?php echo $result_count_busy; ?><br/>
<?php } ?>
<?php if($result_count_noanswer>0){ ?> 	
	<?php echo gettext("NUMBERS OF CALLS NO ANSWERED");?>&nbsp;:&nbsp;<?php echo $result_count_noanswer; ?><br/>
<?php } ?>
<?php if($result_count_cancelled>0){ ?>
 	<?php echo gettext("NUMBERS OF CALLS CANCELLED");?>&nbsp;:&nbsp;<?php echo $result_count_cancelled; ?><br/>
<?php } ?>
<?php if($result_count_congested>0){ ?>
  	<?php echo gettext("NUMBERS OF CALLS CONGESTED");?>&nbsp;:&nbsp;<?php echo $result_count_congested; ?><br/>
<?php } ?>
<?php if($result_count_chanunavail>0){ ?>
	<?php echo gettext("NUMBERS OF CALLS WITH CHANNEL UNAVAILABLE");?>&nbsp;:&nbsp;<?php echo $result_count_chanunavail; ?><br/>
<?php } ?>
