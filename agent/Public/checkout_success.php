<?php
include ("../lib/agent.defines.php");
include ("../lib/agent.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("../lib/epayment/includes/general.php");
include ("../lib/epayment/includes/configure.php");
include ("../lib/epayment/includes/html_output.php");
$popup_select = 1;
include ("../lib/agent.smarty.php");


getpost_ifset(array('errcode'));

// #### HEADER SECTION
$smarty->display( 'main.tpl');
?>

<br>
<br>
<table width=80% align=center class="infoBox">
<tr height="15">
    <td colspan=2 class="infoBoxHeading">&nbsp;<?php echo gettext("Message")?></td>
</tr>
<tr>
    <td width=50%>&nbsp;</td>
    <td width=50%>&nbsp;</td>
</tr>
<tr>
    <td align=center colspan=2>
	<?php echo gettext("Thank you for your purchase")?>
	&nbsp;
    <?php
      switch($errcode)
      {
          case -2:
		  	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__." ERROR TRANSACTION FAILED");
            echo gettext("We are sorry your transaction is failed. Please try later or check your provided information.");
          break;
          case -1:
		  	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__." ERROR TRANSACTION DENIED");
            echo gettext("We are sorry your transaction is denied. Please try later or check your provided information.");
          break;
          case 0:
		  	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__." ERROR TRANSACTION PENDING");
            echo gettext("We are sorry your transaction is pending.");
          break;
          case 1:
		  	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__." ERROR TRANSACTION INPROGRESS");
            echo gettext("Your transaction is in progress.");
          break;
          case 2:
		  	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__." TRANSACTION SUCCESSFUL");
            echo gettext("Your transaction was successful.");
          break;
      }
    ?>  &nbsp;
    </td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>
<tr>
    <td align=center colspan=2><a href="<?php echo tep_href_link("userinfo.php","", 'SSL', false, false);?>">[Home]</a></td>
</tr>

</table>
<?php 
// #### FOOTER SECTION
$smarty->display( 'footer.tpl');
?>