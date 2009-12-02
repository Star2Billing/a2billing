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
include ("../lib/Class.RateEngine.php");	
include ("../lib/admin.smarty.php");


if (! has_rights (ACX_RATECARD)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();
}

getpost_ifset(array('posted', 'tariffplan', 'balance', 'id_cc_card', 'called' , 'username'));


$FG_DEBUG = 0;
$DBHandle  = DbConnect();

if ($called  && ($id_cc_card > 0 || $username > 0)) {
	
	$A2B -> DBHandle = DbConnect();
	
	if ($username>0) {
		$instance_table_cardnum = new Table("cc_card", "username, id");
		/* CHECK IF THE CARDNUMBER IS ON THE DATABASE */			
		$FG_TABLE_CLAUSE_card = "username='".$username."'";
		$list_tariff_card = $instance_table_cardnum -> Get_list ($A2B -> DBHandle, $FG_TABLE_CLAUSE_card, null, null, null, null, null, null);			
		if ($username == $list_tariff_card[0][0]) $id_cc_card = $list_tariff_card[0][1];
	}
	
	$calling = $called;
	
	if ( strlen($calling)>=1 && is_numeric($calling)) {
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
		if ($FG_DEBUG == 1) echo "cardnumber = ".$row[0][0] ."<br>";
		
		if ($A2B -> callingcard_ivr_authenticate_light ($error_msg,$balance)){
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
			if ($resfindrate!=0) {	
				$res_all_calcultimeout = $RateEngine->rate_engine_all_calcultimeout($A2B, $A2B->credit);
	
				if ($FG_DEBUG == 1) print_r($RateEngine->ratecard_obj);
			} else {
				$error_msg = '<font face="Arial, Helvetica, sans-serif" size="2" color="red"><b>'.gettext("No matching rate found !").'</b></font><br><br>';
			}
		}
		
	}
}

$smarty->display('main.tpl');

echo $CC_help_sim_ratecard;

?>	
	<center> 
	<br>
	<FORM NAME="theFormFilter" action="<?php echo $PHP_SELF?>">		
	<table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
	<TR>
	  <TD colspan="2"> <B><?php echo gettext("RATECARD SIMULATOR");?></B></TD>
	</TR>
	<tr>			
		<td height="31" style="padding-left: 5px; padding-right: 3px;" class="bgcolor_001" colspan="3">
				<br><b><?php echo gettext("NUMBER TO CALL");?> :</b>
				<INPUT type="text" name="called" value="<?php echo $called;?>" class="form_input_text">
				<br>
				<b><?php echo gettext("INITAL CREDIT");?> :</b>
				<INPUT type="text" class="form_input_text" name="balance" size="6" maxlength="6" value="<?php if (!isset($balance)) echo "10"; else echo $balance;?>">
				<br>
				<b><?php echo gettext("Choose 0 to simulate with the account current credit");?></b>
				
				<br>
				<br>
		</td>
	</tr>
	<tr>
		<td height="31" style="padding-left: 5px; padding-right: 3px;" class="bgcolor_001">
	    &nbsp;
	    </td>
		<td align="right" height="31" style="padding-left: 5px; padding-right: 3px;" class="bgcolor_001">
				  <?php echo gettext("Card ID");?>  &nbsp; :  &nbsp; 
		</td>
		<td height="31" style="padding-left: 5px; padding-right: 3px;" class="bgcolor_001">				  
				  <input class="form_input_text" name="id_cc_card" size="20" maxlength="40" value="<?php echo $id_cc_card;?>"> 
					<a href="#" onclick="window.open('A2B_entity_card.php?popup_select=1&popup_formname=theFormFilter&popup_fieldname=id_cc_card' , 'CardNumberSelection','width=550,height=330,top=20,left=100,scrollbars=1');"><img src="<?php echo Images_Path;?>/icon_arrow_orange.gif"></a>
		</td>		   
	</tr>
	<tr>
		<td colspan="3"  style="padding-left: 5px; padding-right: 3px;" class="bgcolor_001">		   
				<strong>
				 <?php echo gettext("OR");?>
				</strong>  
		</td>
		
	</tr>
	<tr>
	    <td  style="padding-left: 5px; padding-right: 3px;" class="bgcolor_001">
	    &nbsp;
	    </td>
		<td  align="right"  style="padding-left: 5px; padding-right: 3px;" class="bgcolor_001">
				<?php echo gettext("Card Number");?>  &nbsp; :  &nbsp;
		</td>
		<td  style="padding-left: 5px; padding-right: 3px;" class="bgcolor_001">
				 <input class="form_input_text" name="username" size="30" maxlength="50" value="<?php echo $username;?>" /> 
		</td>
	</tr>
	<tr>
		<td  align="right" colspan="3" height="31" class="bgcolor_001" style="padding-left: 5px; padding-right: 3px;">
			<input type="SUBMIT" value="<?php echo gettext("SIMULATE");?>"  class="form_input_button"/>
		</td>
	</tr>
	<TR> 
	  <TD style="border-bottom: medium dotted #8888CC"  colspan="4"><br></TD>
	</TR>
	</table>
	
	</FORM>	
	
<?php

if ( (is_array($RateEngine->ratecard_obj)) && (!empty($RateEngine->ratecard_obj)) ){
if ($FG_DEBUG == 1) print_r($RateEngine->ratecard_obj);

$arr_ratecard=array('tariffgroupname', 'lcrtype', 'idtariffgroup', 'cc_tariffgroup_plan.idtariffplan', 'tariffname', 
		'cc_ratecard.id' , 'dialprefix', 'destination', 'buyrate',
		 'buyrateinitblock', 'buyrateincrement', 'rateinitial', 'initblock', 'billingblock',
		 'connectcharge', 'disconnectcharge','disconnectcharge_after', 'stepchargea', 'chargea', 
		'timechargea', 'billingblocka', 'stepchargeb', 'chargeb', 'timechargeb', 
		'billingblockb', 'stepchargec', 'chargec', 'timechargec', 'billingblockc', 
		'tp_id_trunk', 'tp_trunk', 'providertech', 'tp_providerip', 'tp_removeprefix');
$arr_ratecard_i=array(0,1,2,3,4, 6,7,8,9,   10,11,12,13,14, 15,16,60,17,18,  19,20,21,22,23,  24,25,26,27,28, 29,30,31,32,33);
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
		<?php for($j=0;$j<count($RateEngine->ratecard_obj);$j++) { ?>
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
				<td height="15" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[0]?>" style="padding-left: 5px; padding-right: 3px;">
						<font color="blue"><b><?php echo gettext("MAX DURATION FOR THE CALL");?></b></font>
						
				</td>
				<td height="15" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[0]?>" style="padding-left: 5px; padding-right: 3px;">
						<font color="blue"><i><?php echo display_minute($RateEngine->ratecard_obj[$j]['timeout']);?> <?php echo gettext("Minutes");?> </i></font>
						
				</td>
			</tr>
			<?php if($RateEngine->ratecard_obj[$j]['freetime_include_in_timeout']>0) {?>
			<tr>
				<td height="15" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[0]?>" style="padding-left: 5px; padding-right: 3px;">
						<font color="blue"><?php echo gettext("FREE TIME INCLUDED IN THE DURATION");?></font>
						
				</td>
				<td height="15" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[0]?>" style="padding-left: 5px; padding-right: 3px;">
						<font color="blue"><i><?php echo display_minute($RateEngine->ratecard_obj[$j]['freetime_include_in_timeout']);?> <?php echo gettext("Minutes");?> </i></font>
						
				</td>
			</tr>
			<?php } ?>
			<?php if ($A2B->agiconfig['cheat_on_announcement_time']==1){ ?>
			<tr>
				<td height="15" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[1]?>" style="padding-left: 5px; padding-right: 3px;">
						<font color="blue"><b><?php echo gettext("TIME ANNOUCEMENT FOR THE CALL");?></b></font>
						
				</td>
				<td height="15" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[1]?>" style="padding-left: 5px; padding-right: 3px;">
						<font color="blue"><i>
						<?php echo display_minute($RateEngine->ratecard_obj[$j]['timeout_without_rules']);
						 echo gettext("Minutes");?> </i></font>
						
				</td>
			</tr>
			<?php } ?>
			<?php if ($RateEngine->ratecard_obj[$j][61]>0){?>
			<tr>
                                <td height="15" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i%2]?>" style="padding-left: 5px; padding-right: 3px;">
                                                <b><?php echo gettext("Announce correction ")?></b>

                                </td>
                                <td height="15" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i%2]?>" style="padding-left: 5px; padding-right: 3px;">
                                                <i>x<?php echo $RateEngine->ratecard_obj[$j][61];?></i>
                                </td>
                        </tr>
			<?php }?>
			<?php for($i=0;$i<count($arr_ratecard);$i++){ 
				if ($arr_ratecard[$i]=='destination') {
					$instance_table_cardnum = new Table("cc_prefix", "destination");
					$list_prefix = $instance_table_cardnum -> Get_list ($A2B -> DBHandle, "prefix=".$RateEngine->ratecard_obj[$j][$arr_ratecard_i[$i]], null, null, null, null, null, null);	
					if (is_array($list_prefix)) {
			?>
			<tr>			
				<td height="15" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i%2]?>" style="padding-left: 5px; padding-right: 3px;">
						<b><?php echo $arr_ratecard[$i];?></b>
						
				</td>
				<td height="15" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i%2]?>" style="padding-left: 5px; padding-right: 3px;">
						<i><?php echo $list_prefix[0][0];?></i>
				</td>
			</tr>
			<?php
					}
				} else {
			?>
			<tr>			
				<td height="15" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i%2]?>" style="padding-left: 5px; padding-right: 3px;">
						<b><?php echo $arr_ratecard[$i];?></b>
						
				</td>
				<td height="15" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$i%2]?>" style="padding-left: 5px; padding-right: 3px;">
						<i><?php echo $RateEngine->ratecard_obj[$j][$arr_ratecard_i[$i]];?></i>
				</td>
			</tr>
			<?php }  
			} ?>
			
		<?php } ?>
		
		<TR> 
          <TD style="border-bottom: medium dotted #8888CC"  colspan="2"><br></TD>
        </TR>
	  </table>
	  
<?php  } else { ?>

<br><br><br>
<?php echo "$error_msg"; ?> 
<br>

<?php  }  ?>
</center>
<?php 
$smarty->display('footer.tpl');


