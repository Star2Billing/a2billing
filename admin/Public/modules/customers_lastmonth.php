<?php

$DBHandle  = DbConnect();
$table = new Table('cc_card','*');

$temp = date("Y-m-01");
$datetime = new DateTime($temp);
$datetime_end = new DateTime($temp);
$datetime_end->modify("+1 month");
$datetime->modify("-11 month");
$checkdate= $datetime->format("Y-m-d");
$datetime->modify("-15 day");
$begin_date_graphe = $datetime->format("Y-m-d");
$end_date_graphe = $datetime_end->format("Y-m-01");
$mingraph = strtotime($begin_date_graphe);
$maxgraph = strtotime($end_date_graphe);
$QUERY_GRAPH_CARD_CREATION = "SELECT UNIX_TIMESTAMP(DATE_FORMAT(creationdate,'%Y-%m-01')), count(*) FROM cc_card WHERE creationdate>= TIMESTAMP('$checkdate') AND creationdate <= CURRENT_TIMESTAMP GROUP BY MONTH(creationdate) ORDER BY creationdate;";
$result_graph_card_creation = $table -> SQLExec($DBHandle,$QUERY_GRAPH_CARD_CREATION);
$QUERY_GRAPH_CARD_EXPIRATION = "SELECT UNIX_TIMESTAMP(DATE_FORMAT(expirationdate,'%Y-%m-01')), count(*) FROM cc_card WHERE expirationdate>= TIMESTAMP('$checkdate') AND expirationdate <= CURRENT_TIMESTAMP GROUP BY MONTH(expirationdate) ORDER BY expirationdate;";
$result_graph_card_expiration = $table -> SQLExec($DBHandle,$QUERY_GRAPH_CARD_EXPIRATION);
$QUERY_GRAPH_CARD_FIRSTUSE = "SELECT UNIX_TIMESTAMP(DATE_FORMAT(firstusedate,'%Y-%m-01')), count(*) FROM cc_card WHERE firstusedate>= TIMESTAMP('$checkdate') AND firstusedate <= CURRENT_TIMESTAMP GROUP BY MONTH(firstusedate) ORDER BY firstusedate;";
$result_graph_card_firstuse = $table -> SQLExec($DBHandle,$QUERY_GRAPH_CARD_FIRSTUSE);
?>


<input id="card_creation" type="radio" name="mode_cust" value="CreationDate">&nbsp; <?php echo gettext("CREATION DATE"); ?><br/>
<input id="card_expiration" type="radio" name="mode_cust" value="ExpirationDate">&nbsp; <?php echo gettext("EXPIRATION DATE"); ?><br/>
<input id="card_firstuse" type="radio" name="mode_cust" value="FirstUse">&nbsp; <?php echo gettext("FIRST USE DATE"); ?><br/>
<br/>
<div id="cust_graph" class="dashgraph" style="width:310px;height:170px;margin-left: auto;margin-right: auto;"></div>

<?php 
//Creationdate data treatment
  	 $val_creation= "[";
  	 $max_creation=0;
  	 if(is_array($result_graph_card_creation)){
		 for($i=0;$i<count($result_graph_card_creation);$i++) {
		 	$max_creation = max($max_creation,$result_graph_card_creation[$i][1]);
		   $val_creation.="[".$result_graph_card_creation[$i][0]."000,".$result_graph_card_creation[$i][1]."]";
		   if($i<count($result_graph_card_creation)-1){ 
		   	$val_creation.=",";
		   }
		 }
  	 }
	 $val_creation.="]";
//expiration data treatment	 
	 $max_expiration=0;
	 $val_expiration= "[";
  	 if(is_array($result_graph_card_expiration)){
		 for($i=0;$i<count($result_graph_card_expiration);$i++) {
		 	$max_expiration = max($max_expiration,$result_graph_card_expiration[$i][1]);
		   	$val_expiration.="[".$result_graph_card_expiration[$i][0]."000,".$result_graph_card_expiration[$i][1]."]";
			$valticks_expiration .="[".$result_graph_card_expiration[$i][0]."000,'".substr($result_graph_card_expiration[$i][2],0,3)."']";
			if($i<count($result_graph_card_expiration)-1){ 
		   	$val_expiration.=",";
		   }
		 }
  	 }
	 $val_expiration.="]";
//firstuse data treatment
	 $max_firstuse=0;	 
	 $val_firstuse= "[";
  	 if(is_array($result_graph_card_firstuse)){
		 for($i=0;$i<count($result_graph_card_firstuse);$i++) {
		   $max_firstuse = max($max_firstuse,$result_graph_card_firstuse[$i][1]);
		   $val_firstuse.="[".$result_graph_card_firstuse[$i][0]."000,".$result_graph_card_firstuse[$i][1]."]";
		   if($i<count($result_graph_card_firstuse)-1){ 
		   	$val_firstuse.=",";
		   }
		 }
  	 }
	 $val_firstuse.="]";
	
	 ?>
<script id="source" language="javascript" type="text/javascript">
$(document).ready(function () {
var width= Math.min($("#cust_graph").parent("div").width(),$("#cust_graph").parent("div").innerWidth());
$("#cust_graph").width(width-10);
$("#cust_graph").height(Math.floor(width/2));
  	
	$('#card_creation').click(function () {
	
		var d = <?php echo $val_creation ?>;
    	$.plot($("#cust_graph"), [
						        {
						            data: d,
						            bars: { show: true,
						            		barWidth: <?php echo 28*24 * 60 * 60 * 1000; ?>,
						            		align: "centered"
						            }
						        }
   							 ],
						    {   xaxis: {
							    mode: "time",
							    timeformat: "%b",
							    ticks :12,
						   		min : <?php echo $mingraph."000" ?>,
						   		max : <?php echo $maxgraph."000" ?>
							  }, 
							  yaxis: {
							  max:<?php echo ($max_creation+5-($max_creation%5)); ?>,
							  minTickSize: 1,
							  tickDecimals:0
							  },selection: { mode: "y" },
						         grid: { hoverable: true,clickable: true}
							  });
	
	        });
	$('#card_expiration').click(function () {
			var d = <?php echo $val_expiration ?>;
    		$.plot($("#cust_graph"), [
						        {
						            data: d,
						            bars: { show: true,
						            		barWidth: <?php echo 28*24 * 60 * 60 * 1000; ?>,
						            		align: "centered"
						            }
						        }
   							 ],
						    {   xaxis: {
							    mode: "time",
						   		timeformat: "%b",
							    ticks :12,
						   		min : <?php echo $mingraph."000" ?>,
						   		max : <?php echo $maxgraph."000" ?>
							  }, 
							  yaxis: {
							  max:<?php echo ($max_expiration+5-($max_expiration%5)); ?>,
							  minTickSize: 1,
							  tickDecimals:0
							  },selection: { mode: "y" },
						         grid: { hoverable: true,clickable: true}
							  });
				
			
	        });
	$('#card_firstuse').click(function () {
			var d = <?php echo $val_firstuse ?>;
    		$.plot($("#cust_graph"), [
						        {
						            data: d,
						            bars: { show: true,
						            		barWidth: <?php echo 28*24 * 60 * 60 * 1000; ?>,
						            		align: "centered"
						            }
						        }
   							 ],
						    {   xaxis: {
						   		mode: "time",
							    timeformat: "%b",
							    ticks :12,
						   		min : <?php echo $mingraph."000" ?>,
						   		max : <?php echo $maxgraph."000" ?>
							  }, 
							  yaxis: {
							  max:<?php echo ($max_firstuse+5-($max_firstuse%5)); ?>,
							  minTickSize: 1,
							  tickDecimals:0
							  },selection: { mode: "y" },
						         grid: { hoverable: true,clickable: true}
							  });
			
	        });
	        
	        
 $('#card_creation').click();
 
   function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fee',
            opacity: 0.80
        }).appendTo("body").fadeIn(200);
    }

    var previousPoint = null;
    $("#cust_graph").bind("plothover", function (event, pos, item) {
            if (item) {
                if (previousPoint != item.datapoint) {
                    previousPoint = item.datapoint;
                    
                    $("#tooltip").remove();
                    var y = item.datapoint[1].toFixed(0);
                    
                    showTooltip(item.pageX, item.pageY, y);
                }
            }
            else {
                $("#tooltip").remove();
                previousPoint = null;            
            }
    });
 	                
});
  
  
  
  
</script>
