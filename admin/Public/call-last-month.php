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


include_once(dirname(__FILE__) . "/../lib/admin.defines.php");
include_once(dirname(__FILE__) . "/../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_CALL_REPORT)) { 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();
}


getpost_ifset(array('months_compare', 'current_page', 'fromstatsmonth_sday', 'days_compare', 'min_call', 'posted',  'dsttype', 'srctype', 'clidtype', 'channel', 'resulttype', 'stitle', 'atmenu', 'current_page', 'order', 'sens', 'dst', 'src', 'clid', 'userfieldtype', 'userfield', 'accountcodetype', 'accountcode', 'customer', 'entercustomer', 'enterprovider','entertariffgroup', 'entertrunk', 'enterratecard', 'graphtype'));
$smarty->display('main.tpl');

?>

<!-- ** ** ** ** ** Part for the research ** ** ** ** ** -->
	<center>
	<FORM METHOD=POST name="myForm" ACTION="<?php echo $PHP_SELF?>?s=<?php echo $s?>&t=<?php echo $t?>&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>">
	<INPUT TYPE="hidden" NAME="posted" value=1>
		<table class="bar-status" width="80%" border="0" cellspacing="1" cellpadding="2" align="center">
			<tbody>
			<tr>
				<td align="left" valign="top" class="bgcolor_004">					
					<font  class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("CUSTOMERS");?></font>
				</td>				
			<td class="bgcolor_005" align="left">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td class="fontstyle_searchoptions" width="50%" valign="top">
					<?php echo gettext("Enter the customer ID");?>: <INPUT TYPE="text" NAME="entercustomer" value="<?php echo $entercustomer?>" class="form_input_text">
					<a href="#" onclick="window.open('A2B_entity_card.php?popup_select=1&popup_formname=myForm&popup_fieldname=entercustomer' , 'CardNumberSelection','scrollbars=1,width=550,height=330,top=20,left=100,scrollbars=1');"><img src="<?php echo Images_Path;?>/icon_arrow_orange.gif"></a>
				</td>
				<td width="50%">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td align="left" class="fontstyle_searchoptions"><?php echo gettext("CallPlan");?> :</td>
							<td align="left" class="fontstyle_searchoptions"><INPUT TYPE="text" NAME="entertariffgroup" value="<?php echo $entertariffgroup?>" size="4" class="form_input_text">&nbsp;<a href="#" onclick="window.open('A2B_entity_tariffgroup.php?popup_select=2&popup_formname=myForm&popup_fieldname=entertariffgroup' , 'CallPlanSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><img src="<?php echo Images_Path;?>/icon_arrow_orange.gif"></a></td>
							<td align="left" class="fontstyle_searchoptions"><?php echo gettext("Provider");?> :
							<td align="left" class="fontstyle_searchoptions"><INPUT TYPE="text" NAME="enterprovider" value="<?php echo $enterprovider?>" size="4" class="form_input_text">&nbsp;<a href="#" onclick="window.open('A2B_entity_provider.php?popup_select=2&popup_formname=myForm&popup_fieldname=enterprovider' , 'ProviderSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><img src="<?php echo Images_Path;?>/icon_arrow_orange.gif"></a></td>
						</tr>
						<tr>
							<td align="left" class="fontstyle_searchoptions"><?php echo gettext("Trunk");?> :</td>
							<td align="left" class="fontstyle_searchoptions"><INPUT TYPE="text" NAME="entertrunk" value="<?php echo $entertrunk?>" size="4" class="form_input_text">&nbsp;<a href="#" onclick="window.open('A2B_entity_trunk.php?popup_select=2&popup_formname=myForm&popup_fieldname=entertrunk' , 'TrunkSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><img src="<?php echo Images_Path;?>/icon_arrow_orange.gif"></a></td>
							<td align="left" class="fontstyle_searchoptions"><?php echo gettext("Rate");?> :</td>
							<td align="left" class="fontstyle_searchoptions"><INPUT TYPE="text" NAME="enterratecard" value="<?php echo $enterratecard?>" size="4" class="form_input_text">&nbsp;<a href="#" onclick="window.open('A2B_entity_def_ratecard.php?popup_select=2&popup_formname=myForm&popup_fieldname=enterratecard' , 'RatecardSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><img src="<?php echo Images_Path;?>/icon_arrow_orange.gif"></a></td>
						</tr>
					</table>
				</tr>
			</table>
			</td>
			</tr>
			<tr>
        		<td align="left"  class="bgcolor_002">					
					<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("SELECT MONTH");?></font>
				</td>
      			<td align="left"  class="bgcolor_003">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" >
					<tr><td class="fontstyle_searchoptions">
	  				<?php echo gettext("From");?> : 
				 	<select name="fromstatsmonth_sday" class="form_input_select">
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
								if ($fromstatsmonth_sday=="$i-$month_formated") $selected="selected";
								else $selected="";
								echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";				
							}
						}								
					?>										
					</select>
					</td><td class="fontstyle_searchoptions">&nbsp;&nbsp;
					<?php echo gettext("Number of months to compare");?> :
				 	<select name="months_compare" class="form_input_select">
					<option value="6" <?php if ($months_compare=="6"){ echo "selected";}?>>- 6 <?php echo gettext("months");?></option>
					<option value="5" <?php if ($months_compare=="5"){ echo "selected";}?>>- 5 <?php echo gettext("months");?></option>
					<option value="4" <?php if ($months_compare=="4"){ echo "selected";}?>>- 4 <?php echo gettext("months");?></option>
					<option value="3" <?php if ($months_compare=="3"){ echo "selected";}?>>- 3 <?php echo gettext("months");?></option>
					<option value="2" <?php if (($months_compare=="2")|| !isset($months_compare)){ echo "selected";}?>>- 2 <?php echo gettext("months");?></option>
					<option value="1" <?php if ($months_compare=="1"){ echo "selected";}?>>- 1 <?php echo gettext("months");?></option>
					</select>
					</td></tr></table>
	  			</td>
    		</tr>	
			
			<tr>
				<td class="bgcolor_004" align="left" >
					<font class="fontstyle_003" >&nbsp;&nbsp;<?php echo gettext("CALLEDNUMBER");?></font>
				</td>				
				<td class="bgcolor_005" align="left" >
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td class="fontstyle_searchoptions">&nbsp;&nbsp;<INPUT TYPE="text" NAME="dst" value="<?php echo $dst?>" class="form_input_select"></td>
				<td  align="center" class="fontstyle_searchoptions"><input type="radio" NAME="dsttype" value="1" <?php if((!isset($dsttype))||($dsttype==1)){?>checked<?php }?>><?php echo gettext("Exact");?></td>
				<td  align="center" class="fontstyle_searchoptions"><input type="radio" NAME="dsttype" value="2" <?php if($dsttype==2){?>checked<?php }?>><?php echo gettext("Begins with");?></td>
				<td  align="center" class="fontstyle_searchoptions"><input type="radio" NAME="dsttype" value="3" <?php if($dsttype==3){?>checked<?php }?>><?php echo gettext("Contains");?></td>
				<td  align="center" class="fontstyle_searchoptions"><input type="radio" NAME="dsttype" value="4" <?php if($dsttype==4){?>checked<?php }?>><?php echo gettext("Ends with");?></td>
				</tr></table></td>
			</tr>			
			

			<tr>
        		<td class="bgcolor_002" align="left" > </td>

				<td class="bgcolor_003" align="center">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bgcolor_003">
						<tr>
						<td align="center">							
							<input type="image"  name="image16" align="top" border="0" src="<?php echo Images_Path;?>/button-search.gif" />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td></tr></table>
	  			</td>
    		</tr>
		</tbody></table>
	</FORM>
</center>


<?php  if ($posted==1){ ?>
	<center>
	<?php echo gettext("TRAFFIC")?><br> 
	<IMG SRC="graph_pie.php?graphtype=1&min_call=<?php echo $min_call?>&fromstatsday_sday=<?php echo $fromstatsday_sday?>&months_compare=<?php echo $months_compare?>&fromstatsmonth_sday=<?php echo $fromstatsmonth_sday?>&dsttype=<?php echo $dsttype?>&srctype=<?php echo $srctype?>&clidtype=<?php echo $clidtype?>&channel=<?php echo $channel?>&resulttype=<?php echo $resulttype?>&dst=<?php echo $dst?>&src=<?php echo $src?>&clid=<?php echo $clid?>&userfieldtype=<?php echo $userfieldtype?>&userfield=<?php echo $userfield?>&accountcodetype=<?php echo $accountcodetype?>&accountcode=<?php echo $accountcode?>&customer=<?php echo $customer?>&entercustomer=<?php echo $entercustomer?>&enterprovider=<?php echo $enterprovider?>&entertrunk=<?php echo $entertrunk?>&enterratecard=<?php echo $enterratecard?>&entertariffgroup=<?php echo $entertariffgroup?>" ALT="<?php echo gettext("Stat Graph");?>">
	</center>
	<br>
	
	<center>
	<?php echo gettext("PROFIT")?> <br>
	<IMG SRC="graph_pie.php?graphtype=2&min_call=<?php echo $min_call?>&fromstatsday_sday=<?php echo $fromstatsday_sday?>&months_compare=<?php echo $months_compare?>&fromstatsmonth_sday=<?php echo $fromstatsmonth_sday?>&dsttype=<?php echo $dsttype?>&srctype=<?php echo $srctype?>&clidtype=<?php echo $clidtype?>&channel=<?php echo $channel?>&resulttype=<?php echo $resulttype?>&dst=<?php echo $dst?>&src=<?php echo $src?>&clid=<?php echo $clid?>&userfieldtype=<?php echo $userfieldtype?>&userfield=<?php echo $userfield?>&accountcodetype=<?php echo $accountcodetype?>&accountcode=<?php echo $accountcode?>&customer=<?php echo $customer?>&entercustomer=<?php echo $entercustomer?>&enterprovider=<?php echo $enterprovider?>&entertrunk=<?php echo $entertrunk?>&enterratecard=<?php echo $enterratecard?>&entertariffgroup=<?php echo $entertariffgroup?>" ALT="<?php echo gettext("Stat Graph");?>">
	</center>
	
	<br>
		<center>
	<?php echo gettext("SELL")?> <br>
	<IMG SRC="graph_pie.php?graphtype=3&min_call=<?php echo $min_call?>&fromstatsday_sday=<?php echo $fromstatsday_sday?>&months_compare=<?php echo $months_compare?>&fromstatsmonth_sday=<?php echo $fromstatsmonth_sday?>&dsttype=<?php echo $dsttype?>&srctype=<?php echo $srctype?>&clidtype=<?php echo $clidtype?>&channel=<?php echo $channel?>&resulttype=<?php echo $resulttype?>&dst=<?php echo $dst?>&src=<?php echo $src?>&clid=<?php echo $clid?>&userfieldtype=<?php echo $userfieldtype?>&userfield=<?php echo $userfield?>&accountcodetype=<?php echo $accountcodetype?>&accountcode=<?php echo $accountcode?>&customer=<?php echo $customer?>&entercustomer=<?php echo $entercustomer?>&enterprovider=<?php echo $enterprovider?>&entertrunk=<?php echo $entertrunk?>&enterratecard=<?php echo $enterratecard?>&entertariffgroup=<?php echo $entertariffgroup?>" ALT="<?php echo gettext("Stat Graph");?>">
	</center>
	
<?php  } ?>
</center>

<br><br>

<?php
$smarty->display('footer.tpl');
