<?php
include ("../lib/defines.php");
include_once("../lib/module.access.php");
include ("../lib/smarty.php");

$smarty->display('main.tpl');
?>
<br/><br/><br/><br/>
<br/>
<table align=center width="80%" bgcolor="white" cellpadding="5" cellspacing="5" style="border-bottom: medium dotted #AA0000">
	<tr>
		<td width="10%"><img src="<?php echo Images_Path;?>/logoA2B-white-300.gif"></td>
		<td align="right"> <?php  echo TEXTCONTACT; ?> <a href="mailto:<?php  echo EMAILCONTACT; ?>"><?php  echo EMAILCONTACT; ?></a>

		</td>
	</tr>
</table>

	<br></br>
	<center>
		<?php echo gettext("Support this project :");?>  
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
	
	<br/>
	<br/><br/><br/>

<?php
	$smarty->display('footer.tpl');
?>
