<?php
include ("./lib/customer.defines.php");
include ("./lib/customer.module.access.php");
include ("./lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_ticket.inc");
include ("./lib/customer.smarty.php");

if (! has_rights (ACX_SUPPORT)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}


getpost_ifset(array('title', 'description', 'priority' , 'component'));


$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();

/************************************  ADD TICKET  ***********************************************/
if (strlen($description)>0  && is_numeric($priority) && strlen($title)>0  && is_numeric($component)){

		$FG_SPEEDDIAL_TABLE  = "cc_ticket";
		$instance_sub_table = new Table($FG_SPEEDDIAL_TABLE, "*");

		$QUERY = "INSERT INTO cc_ticket (creator,title, description, id_component, priority, viewed_cust) VALUES ('".$_SESSION["card_id"]."', '".$title."', '".$description."', '".$component."', '".$priority ."' ,'0')";
		
		$result = $instance_sub_table -> SQLExec ($HD_Form -> DBHandle, $QUERY, 0);
		$update_msg = gettext("Ticket added successfully");

}

if ($id!="" || !is_null($id)) {
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;
$list = $HD_Form -> perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl');

if ($form_action == "list") {
    // My code for Creating two functionalities in a page
    $HD_Form -> create_toppage ("ask-add");
    if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];

?>
	  </center>
	  <center><font class="error_message"><?php echo gettext("Create support ticket"); ?></font></center>
	  <center>
	   <table align="center" >
		<form name="theForm" action="<?php  $_SERVER["PHP_SELF"]?>">


		<tr class="bgcolor_001">
		<td align="left" valign="bottom">
		<font class="fontstyle_002"><?php echo gettext("Title");?> :</font>
		</td>
		<td>
				<input class="form_input_text" name="title" size="100" maxlength="100" />
		</td>
		</tr>
        <tr>
         <td>
         	<font class="fontstyle_002"><?php echo gettext("Priority");?> :</font>
         </td>
         <td>
       			<select NAME="priority" class="form_input_select">
						<option class=input value='0' ><?php echo gettext("NONE");?> </option>
						<option class=input value='1' ><?php echo gettext("LOW");?> </option>
						<option class=input value='2' ><?php echo gettext("MEDIUM");?> </option>
						<option class=input value='3' ><?php echo gettext("HIGH");?> </option>
				</select>
         </td>
        </tr>
          <tr class="bgcolor_001">
         <td>
         	<font class="fontstyle_002"><?php echo gettext("Component");?> :</font>
         </td>
         <td>
         <select NAME="component" class="form_input_select">
	         <?php
			         $DBHandle  = DbConnect();
			   	 $instance_sub_table = new Table("cc_support_component", "*");
				 $QUERY = " activated = 1";
				 $return = null;
			     $return = $instance_sub_table -> Get_list($DBHandle, $QUERY, 0);
			     	foreach ($return as $value) {
						echo	'<option class=input value=" '. $value["id"].'"  > ' . $value["name"]. '  </option>' ;
			     	}
	    	?>
				</select>

         </td>
        </tr>
		<tr>
		<td align="left" valign="top">
				<font class="fontstyle_002"><?php echo gettext("Description");?> :</font>
			</td>
			<td>
				 <textarea class="form_input_text" name="description" cols="100" rows="6"></textarea>
			</td>
        </tr>
        <tr>
			<td colspan="2" align="right" valign="middle">
						<input class="form_input_button"  value="<?php echo gettext("CREATE");?>"  type="submit">
		</td>
		</tr>
	</form>
      </table>
	  </center>
	  <br>
<center><font class="error_message"><?php if (isset($update_msg) && strlen($update_msg)>0) echo $update_msg; ?></font></center>
	<?php
    // END END END My code for Creating two functionalities in a page
}



// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


$HD_Form -> create_form ($form_action, $list, $id=null) ;


// #### FOOTER SECTION
$smarty->display('footer.tpl');

