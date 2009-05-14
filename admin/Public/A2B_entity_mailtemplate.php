<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_mailtemplate.inc");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_MAIL)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

getpost_ifset(array('languages','id','action'));

if($action=="load") {
	$DBHandle=DbConnect();
	if(!empty($id) && is_numeric($id)){
		$instance_table_mail = new Table("cc_templatemail","messagetext,fromemail,fromname,subject");
		$clause_mail = " id ='$id'";
		$result=$instance_table_mail-> Get_list($DBHandle, $clause_mail);
		echo json_encode($result[0]);
	}
	die();
}

if ($popup_select) {
?>
<SCRIPT LANGUAGE="javascript">
<!-- Begin
function sendValue(selvalue){
	$.getJSON("A2B_entity_mailtemplate.php", { id: ""+ selvalue, action: "load" },
				  function(data){
				    window.opener.document.getElementById('msg_mail').value = data.messagetext;
				    window.opener.document.getElementById('from').value = data.fromemail;
				    window.opener.document.getElementById('fromname').value = data.fromname;
				    window.opener.document.getElementById('subject').value = data.subject;
				    window.close();
				  });
	
}

// End -->
</script>
<?php
}

$HD_Form -> setDBHandler (DbConnect());

if($languages != '') {
	if($languages != '') {
		$_SESSION["Langfilter"]=$languages;
	}
	if (isset($_SESSION["Langfilter"])) {
		$HD_Form -> FG_TABLE_CLAUSE = "id_language='".$_SESSION["Langfilter"]."'";	
	}
}

$HD_Form -> init();
$id_language = $languages;
$HD_Form -> FG_EDITION_LINK	= $_SERVER['PHP_SELF']."?form_action=ask-edit&id_language=$id_language&id=";
$HD_Form -> FG_DELETION_LINK = $_SERVER['PHP_SELF']."?form_action=ask-delete&id_language=$id_language&id=";

if ($id!="" || !is_null($id)) {
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;


$list = $HD_Form -> perform_action($form_action);


// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
if (!$popup_select) echo $CC_help_list_misc;
if(isset($form_action) && $form_action=="list"){
?>
<table align="center" class="bgcolor_001" border="0" width="30%">
    <tr>
		<form name="theForm" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		  <?php if($popup_select){ ?>
		  		<input type="hidden" name="popup_select" value="<?php echo $popup_select; ?>" />
		  <?php } ?>
          <td align="left" width="75%">
					<?php
						$handle = DbConnect();
						$instance_table = new Table();
						$QUERY =  "SELECT code, name FROM cc_iso639 order by code";
						$result = $instance_table -> SQLExec ($handle, $QUERY);
						if (is_array($result)){
							$num_cur = count($result);
							for ($i=0;$i<$num_cur;$i++){
								$languages_list[$result[$i][0]] = array (0 => $result[$i][0], 1 => $result[$i][1]);
							}
						}
					?>
				<select NAME="languages" size="1" class="form_input_select" onChange="form.submit()">
					<?php 
					foreach($languages_list as $key => $lang_value) {											
				?>
					<option value='<?php echo $lang_value[0];?>' <?php if($lang_value[0]==$languages)print "selected";?>><?php echo $lang_value[1]; ?></option>
				<?php } ?>		
        </td>
       </form>
   </tr>
</table>
<?php
}

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### FOOTER SECTION
$smarty->display('footer.tpl');


