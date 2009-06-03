<?php
include ("./lib/customer.defines.php");
include ("./lib/customer.module.access.php");
include ("./lib/Form/Class.FormHandler.inc.php");
include ("./lib/customer.smarty.php");


if (!$A2B->config["signup"]['enable_signup'])
	exit;

getpost_ifset(array ('test'));


//check subscriber
$table_subscriber = new Table("cc_subscription_signup", "*");
$clause_subscriber = "enable = 1";
$result_subscriber = $table_subscriber->Get_list(DbConnect(), $clause_subscriber);

if (strlen($_GET["menu"]) > 0)
	$_SESSION["menu"] = $_GET["menu"];

// #### HEADER SECTION
$smarty->display('signup_header.tpl');


?>
<form id="myForm" method="post" name="myForm" action="signup.php">
<?php if ($dotest){ ?>
	<input type="hidden" name="test" value="<?php echo $test; ?>" />
<?php } ?>
<div align="center">
<table  style="width : 80%;" class="editform_table1">
   <tr>
   		<th colspan="2" background="../Public/templates/default/images/background_cells.gif">
   			<?php echo gettext("SELECT THE SERVICE THAT YOU WANT SUBSCRIBE") ?>
   		</th>	
   </tr>
   <tr height="20px">
		<td  colspan="2">
			&nbsp;
		</td>
	</tr>
   <tr height="20px">
		<td  class="form_head">
			&nbsp;<?php echo gettext("SERVICE") ?> :
		</td>
		<td class="tableBodyRight"  background="./templates/default/images/background_cells.gif" width="70%">
			&nbsp; <select name="subscriber" > 
			<?php  foreach($result_subscriber as $subscriber){?>
					<option  class="form_input_select" value="<?php echo $subscriber['id_subscription']; ?>" >  <?php echo $subscriber['label']; ?> </option>
			<?php  }?>
					</select>
		</td>
	</tr>
	 <tr height="20px">
		<td  colspan="2">
			&nbsp;
		</td>
	</tr>
	 <tr>
		<td colspan="2" align="right" class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<a class="cssbutton_big" onClick="javascript:document.myForm.submit();"  href="#">
				<img src="<?php echo Images_Path_Main;?>/icon_arrow_orange.gif"/>
				<?php echo gettext("SUBSCRIBE THIS SERVICE"); ?>
			</a>
		</td>
	</tr>
	
 </table>
 </div>
</form>

<?php 

// #### FOOTER SECTION
$smarty->display('signup_footer.tpl');

