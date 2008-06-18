<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Class.RateEngine.php");	
include ("../lib/admin.smarty.php");


if (! has_rights (ACX_RATECARD)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();
}

getpost_ifset(array('posted', 'tariffplan', 'balance', 'id_cc_card', 'called'));


$FG_DEBUG = 0;
$DBHandle  = DbConnect();

if ($called  && $id_cc_card) {
	
	$calling = $called;
	
	if ( strlen($calling)>2 && is_numeric($calling)) {
		
		$A2B -> DBHandle = DbConnect();
		$instance_table = new Table();
		$A2B -> set_instance_table ($instance_table);
		$num = 0;
		$QUERY = "SELECT username, tariff, credit FROM cc_card where id='$id_cc_card'";
		$resmax = $DBHandle -> Execute($QUERY);
		if ($resmax)
			$num = $resmax -> RecordCount( );
		
		if ($num==0) {
			echo gettext("Error card !!!"); exit();
		}
		
		for($i=0;$i<$num;$i++) {
			$row [] =$resmax -> fetchRow();	
		}
		
		$A2B -> cardnumber = $row[0][0];
		$A2B -> credit = $balance = $row[0][2];
		if ($FG_DEBUG == 1) echo "cardnumber = ".$row[0][0] ."<br>";
		
		if ($A2B -> callingcard_ivr_authenticate_light ($error_msg)){
			if ($FG_DEBUG == 1) $RateEngine -> debug_st = 1;
			
			$RateEngine = new RateEngine();
			$RateEngine -> webui = 0;
			// LOOKUP RATE : FIND A RATE FOR THIS DESTINATION
			
			$A2B ->agiconfig['accountcode'] = $A2B -> cardnumber ;
			$A2B ->agiconfig['use_dnid']=1;
			$A2B ->agiconfig['say_timetocall']=0;						
			$A2B ->dnid = $A2B ->destination = $calling;
			
			if ($A2B->removeinterprefix) $A2B->destination = $A2B -> apply_rules ($A2B->destination);			
			
			$resfindrate = $RateEngine->rate_engine_findrates($A2B, $A2B->destination, $row[0][1]);
			if ($FG_DEBUG == 1) echo "resfindrate=$resfindrate";
			
			// IF FIND RATE
			if ($resfindrate!=0){	
				$res_all_calcultimeout = $RateEngine->rate_engine_all_calcultimeout($A2B, $A2B->credit);
				if ($FG_DEBUG == 1) print_r($RateEngine->ratecard_obj);
			}
		}
		
	}
}

/**************************************************************/

$instance_table_tariffname = new Table("cc_tariffplan", "id, tariffname");
$FG_TABLE_CLAUSE = "";
$list_tariffname = $instance_table_tariffname  -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, "tariffname", "ASC", null, null, null, null);
$nb_tariffname = count($list_tariffname);

/*************************************************************/


$smarty->display('main.tpl');


echo $CC_help_sim_ratecard;

?>
	
	
	<center> <?php echo "$error_msg"; ?> </center>
	<br>
	<table width="<?php echo $FG_HTML_TABLE_WIDTH?>" border="0" align="center" cellpadding="0" cellspacing="0">
	<TR>
	  <TD style="border-bottom: medium dotted #8888CC" colspan="2"> <B><?php echo gettext("RATECARD SIMULATOR");?></B></TD>
	</TR>
	<FORM NAME="theFormFilter" action="<?php echo $PHP_SELF?>">		
	<tr>			
		<td height="31" style="padding-left: 5px; padding-right: 3px;" class="bgcolor_009">
				<br><font color="white"><b><?php echo gettext("NUMBER YOU WISH TO CALL");?> :</b></font>
				<INPUT type="text" name="called" value="<?php echo $called;?>" class="form_input_text">
				<br>
				<?php if (false){ ?>
				<br>
				<font color="white"><b><?php echo gettext("YOUR BALANCE");?> :</b></font>
				<INPUT type="text" class="form_input_text" name="balance" value="<?php if (!isset($balance)) echo "10"; else echo $balance;?>"> 
				<?php } ?>
				
				<br>
				 <input class="form_input_text" name="id_cc_card" size="30" maxlength="50" value="<?php echo $id_cc_card;?>"> 
					<a href="#" onclick="window.open('A2B_entity_card.php?popup_select=1&popup_formname=theFormFilter&popup_fieldname=id_cc_card' , 'CardNumberSelection','width=550,height=330,top=20,left=100,scrollbars=1');"><img src="<?php echo Images_Path;?>/icon_arrow_orange.gif"></a>
					
				   <?php echo gettext("Select the card number ID to use");?>.
				<br>
				
		</td>
		<td height="31" class="bgcolor_009" style="padding-left: 5px; padding-right: 3px;">
			<input type="SUBMIT" value="<?php echo gettext("SIMULATE");?>"  class="form_input_button"/>
		</td>
	</tr>
	
	</FORM>	
	<TR> 
	  <TD style="border-bottom: medium dotted #8888CC"  colspan="2"><br></TD>
	</TR>
	</table>
	
	
<?php

if ( (is_array($RateEngine->ratecard_obj)) && (!empty($RateEngine->ratecard_obj)) ){
if ($FG_DEBUG == 1) print_r($RateEngine->ratecard_obj);

$arr_ratecard=array('tariffgroupname', 'lcrtype', 'idtariffgroup', 'cc_tariffgroup_plan.idtariffplan', 'tariffname', 'destination', 'cc_ratecard.id' , 'dialprefix', 'destination', 'buyrate', 'buyrateinitblock', 'buyrateincrement', 'rateinitial', 'initblock', 'billingblock', 'connectcharge', 'disconnectcharge', 'stepchargea', 'chargea', 'timechargea', 'billingblocka', 'stepchargeb', 'chargeb', 'timechargeb', 'billingblockb', 'stepchargec', 'chargec', 'timechargec', 'billingblockc', 'tp_id_trunk', 'tp_trunk', 'providertech', 'tp_providerip', 'tp_removeprefix');

$FG_TABLE_ALTERNATE_ROW_COLOR[0]='#CDC9C9';
$FG_TABLE_ALTERNATE_ROW_COLOR[1]='#EEE9E9';
?>
 <br>
	  <table width="65%" border="0" align="center" cellpadding="0" cellspacing="0">
		
		<TR> 
          <TD style="border-bottom: medium dotted #FF4444" colspan="2"> <B><font color="red" size="3"> <?php echo gettext("Simulator found a rate for your destination");?></font></B></TD>
        </TR>
		
		<?php if (count($RateEngine->ratecard_obj)>1){ ?>
		<TR> 
          <td height="15"  class="bgcolor_010" style="padding-left: 5px; padding-right: 3px;" colspan="2">
					<b><?php echo gettext("MORE THAN ONE ROUTE FOUND ON THE RATECARD");?></b>
			</td>
        </TR>		
		<?php } ?>
		<?php for($j=0;$j<count($RateEngine->ratecard_obj);$j++){ ?>
			<TR> 
          	<td height="15" bgcolor="" style="padding-left: 5px; padding-right: 3px;" colspan="2">
					
			</td>
        	</TR>
			<TR> 
          	<td height="15" class="bgcolor_011" style="padding-left: 5px; padding-right: 3px;" colspan="2">
					<b><?php echo gettext("PREFIX-RATECARD");?> : #<?php echo $j+1;?></b>
			</td>
        	</TR>
			<tr>
				<td height="15" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[1]?>" style="padding-left: 5px; padding-right: 3px;">
						<font color="blue"><b><?php echo gettext("MAX DURATION FOR THE CALL");?></b></font>
						
				</td>
				<td height="15" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[1]?>" style="padding-left: 5px; padding-right: 3px;">
						<font color="blue"><i><?php echo display_minute($RateEngine->ratecard_obj[$j]['timeout']);?> <?php echo gettext("Minutes");?> </i></font>
						
				</td>
			</tr>
			<?php for($i=0;$i<count($arr_ratecard);$i++){ ?>
			<tr>			
				<td height="15" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i%2]?>" style="padding-left: 5px; padding-right: 3px;">
						<b><?php echo $arr_ratecard[$i];?></b>
						
				</td>
				<td height="15" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i%2]?>" style="padding-left: 5px; padding-right: 3px;">
						<i><?php echo $RateEngine->ratecard_obj[$j][$i];?></i>
				</td>
			</tr>
			<?php  } ?>
			
		<?php } ?>
		
		<TR> 
          <TD style="border-bottom: medium dotted #8888CC"  colspan="2"><br></TD>
        </TR>
	  </table>
<?php  } else { ?>

<br><br><br><br>

<?php  } 

	$smarty->display('footer.tpl');

?>
