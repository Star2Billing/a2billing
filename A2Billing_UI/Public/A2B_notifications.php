<?php

include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_CUSTOMER)){
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}


// #### HEADER SECTION
$smarty->display('main.tpl');
?>

<table align="center"  class="bgcolor_001" border="0" width="65%">


	<tr>
	 	<td>

		<?php if ( has_rights (ACX_MISC)){ ?>

			 	<?php echo gettext("To Enable or Disable the module of Notifications for the users follow the link to access to configuration section.");?>

		 			 <a href="A2B_entity_config.php?groupselect=3&section=8"><?php echo gettext("Configuration")?></a>

		<?php }else{ ?>
			 	<?php echo gettext("You don't have enough right to enable or disable the module of Notifications. Ask your administrator");?>


		<?php
		} ?>


	 	</td>
	</tr>



</table>
<br/>


<?php
// Load the list of values in the config table ! key=values_notifications
 $key= "values_notifications";
 $DBHandle  = DbConnect();
 $instance_config_table = new Table("cc_config", "id, config_value");
 $QUERY = " config_key = '".$key."' ";
 $return = null;
 $return = $instance_config_table -> Get_list($DBHandle, $QUERY, 0);
 $id_config = $return[0]["id"];
?>

<table align="center"  class="bgcolor_001" border="0" width="65%">

	 	   <?php if(!is_null($return)&& (!empty($return)>0) ){
	 	   		$values = explode(":",$return[0]["config_value"]);
	 	   	 ?>

			<tr>
			 	<td width="70%"><?php echo gettext("Possible values to choose when the user receive a notification");?>
				<br/>
				<br/>
				<?php if ( has_rights (ACX_MISC)){ ?>

						 	<?php echo gettext("To modify the list of values that the users can choose follow the link to access to configuration section.");

					 			echo ' <a href="A2B_entity_config.php?form_action=ask-edit&id='.$id_config.'&section=8">';
						 		echo gettext("Modify") ."</a>";
						      }else{ ?>
								 	<?php echo gettext("You don't have enough right to modify the list of values. Ask your administrator");?>

							<?php
							} ?>

			 	</td>
			 	<td align="center">

				 		<select class="form_input_select" multiple="multiple" width="50">
				 		<?php
				 			 foreach ($values as $val)
							 {
				 			echo '<option value="'.$val .'"> '.$val.'</option>';
							 }?>
				 		</select>
			 	</td>
			</tr>


		<?php }?>

</table>


