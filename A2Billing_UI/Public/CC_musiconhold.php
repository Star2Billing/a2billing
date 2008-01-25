<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");

$FG_DEBUG =0;


if (! has_rights (ACX_FILE_MANAGER)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();
}

	$smarty->display('main.tpl');
?>
		
			<table width="70%" border="0" align="center" cellpadding="0" cellspacing="5" >
	  
				<TR> 
				  <TD style="border-bottom: medium dotted #EEEEEE" colspan=2>&nbsp; </TD>
				</TR>
				<?php  for ($i=1;$i<=NUM_MUSICONHOLD_CLASS;$i++){ ?>
				<tr>
					<td class="bgcolor_006" height="31" align="center">
						<img src="<?php echo KICON_PATH; ?>/stock-panel-multimedia.gif"/>
					</td>
					<td class="bgcolor_006" height="31" align="center">
						<a href="CC_upload.php?section=11&acc=<?php echo $i?>"><?php echo gettext("CUSTOM THE MUSICONHOLD CLASS");?> : <b>ACC_<?php echo $i?></b></a>
					</td>
				</tr>
				<?php  } ?>
				
			   </table>
	  
	  <br>
	 
	 
<?php
	$smarty->display('footer.tpl');
?>
