<?php
include_once  ("../../lib/admin.defines.php");


$DBHandle  = DbConnect();
$QUERY_COUNT_CARD_ALL = "select count(*) from cc_card";
$QUERY_COUNT_CARD_ACTIVED = "select count(*) from cc_card WHERE status = 1";
$QUERY_COUNT_CARD_CANCELLED = "select count(*) from cc_card WHERE status = 0";
$QUERY_COUNT_CARD_NEW = "select count(*) from cc_card WHERE status = 2";
$QUERY_COUNT_CARD_WAITING = "select count(*) from cc_card WHERE status = 3";
$QUERY_COUNT_CARD_RESERVED = "select count(*) from cc_card WHERE status = 4";
$QUERY_COUNT_CARD_EXPIRED = "select count(*) from cc_card WHERE status = 5";
$QUERY_COUNT_CARD_SUSPENDED = "select count(*) from cc_card WHERE status = 6 OR status = 7";
$table = new Table('cc_card','*');
$result = $table -> SQLExec($DBHandle,$QUERY_COUNT_CARD_ALL);
$result_count_all= $result[0][0];
$result = $table -> SQLExec($DBHandle,$QUERY_COUNT_CARD_ACTIVED);
$result_count_actived= $result[0][0];
$result = $table -> SQLExec($DBHandle,$QUERY_COUNT_CARD_CANCELLED);
$result_count_cancelled= $result[0][0];
$result = $table -> SQLExec($DBHandle,$QUERY_COUNT_CARD_NEW);
$result_count_new= $result[0][0];
$result = $table -> SQLExec($DBHandle,$QUERY_COUNT_CARD_WAITING);
$result_count_waiting= $result[0][0];
$result = $table -> SQLExec($DBHandle,$QUERY_COUNT_CARD_RESERVED);
$result_count_reserved= $result[0][0];
$result = $table -> SQLExec($DBHandle,$QUERY_COUNT_CARD_EXPIRED);
$result_count_expired= $result[0][0];
$result = $table -> SQLExec($DBHandle,$QUERY_COUNT_CARD_SUSPENDED);
$result_count_suspended= $result[0][0];
?>

<?php echo gettext("TOTAL NUMBERS OF CUSTOMERS");?>&nbsp;:&nbsp; <font style="color:#EE6564;" > <?php echo $result_count_all; ?> </font> <br/>
<?php if($result_count_actived>0){ ?>	
  <?php echo gettext("NUMBERS OF ACTIVED CUSTOMERS ");?>&nbsp;:&nbsp;<?php echo $result_count_actived; ?><br/>
<?php } ?>
<?php if($result_count_cancelled>0){ ?>
	<?php echo gettext("NUMBERS OF CANCELED CUSTOMERS ");?>&nbsp;:&nbsp;<?php echo $result_count_cancelled; ?><br/>
<?php } ?>
<?php if($result_count_new>0){ ?> 	
	<?php echo gettext("NUMBERS OF NEW CUSTOMERS ");?>&nbsp;:&nbsp;<?php echo $result_count_new; ?><br/>
<?php } ?>
<?php if($result_count_waiting>0){ ?>
 	<?php echo gettext("NUMBERS OF CUSTOMERS IN WAITING");?>&nbsp;:&nbsp;<?php echo $result_count_waiting; ?><br/>
<?php } ?>
<?php if($result_count_reserved>0){ ?>
  	<?php echo gettext("NUMBERS OF RESERVED CUSTOMERS");?>&nbsp;:&nbsp;<?php echo $result_count_reserved; ?><br/>
<?php } ?>
<?php if($result_count_expired>0){ ?>
	<?php echo gettext("NUMBERS OF EXPIRED CUSTOMERS");?>&nbsp;:&nbsp;<?php echo $result_count_expired; ?><br/>
<?php } ?>
<?php if($result_count_suspended>0){ ?>
	<?php echo gettext("NUMBERS OF SUSPENDED CUSTOMERS");?>&nbsp;:<?php echo $result_count_suspended; ?><br/>
<?php } ?>