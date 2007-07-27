<?php
include ("../lib/defines.php");
include_once("../lib/module.access.php");
include ("../lib/smarty.php");

$smarty->display('main.tpl');
?>
<br/><br/>

<table align="center" width="90%" bgcolor="white" cellpadding="5" cellspacing="5" style="border-bottom: medium dotted #AA0000">
	<tr>
		<td width="340">
			<img src="<?php echo Images_Path;?>/logoA2B-white-300.gif">
			<center>A2Billing is licensed under GPL.</center>
			<br><br>
		</td>
		<td align="left"> <?php  echo ''; ?>
		For information and documentation on A2Billing, <br> please visit <a href="http://www.asterisk2billing.org" target="_blank">www.asterisk2billing.org</a><br><br>
		
		For Commercial Installations, Hosted Systems, Customisation and Commercial support, please email <a href="mailto:info@asterisk2billing.org">info@asterisk2billing.org</a><br><br>
		
		<center>
		<?php echo '<a href="http://www.call-labs.com/" target="_blank"><img src="'.Images_Path.'/call-labs.com.png" alt="call-labs"/></a>'; ?>
		</center>
		For VoIP termination, please visit <a href="http://www.call-labs.com" target="_blank">http://www.call-labs.com</a> <br>
		Profits from Call-Labs are used to support the A2Billing project.<br><br>
		
		</td>
	</tr>
</table>

	<br>
	
<table align=center width="90%" bgcolor="white" cellpadding="5" cellspacing="5">
	<tr>
		<td align="center"> 
		
		<center>
			<?php echo gettext("If you find A2Billing useful, please donate to the A2Billing project by clicking the Donate button :");?>  
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="blank">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="business" value="info@areski.net">
			<input type="hidden" name="no_note" value="1">
			<input type="hidden" name="currency_code" value="EUR">
			<input type="hidden" name="tax" value="0">
			<input type="hidden" name="LC" value="US">		
			<input type="hidden" name="country" value="USA">
			<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" border="0" name="submit" alt="Make Donation with PayPal - it's fast, free and secure!">
			</form>
		</center>
			 <br>
		<span class="liens">
	           BY USING THIS SOFTWARE, YOU ASSUME ALL RISKS OF USE AND NO WARRANTIES EXPRESS OR IMPLIED  <BR>
			   ARE PROVIDED WITH THIS SOFTWARE INCLUDING FITNESS FOR A PARTICULAR PURPOSE AND MERCHANTABILITY.
		</span> 		</td>
	</tr>
</table>
	

<?php
	$smarty->display('footer.tpl');
?>
