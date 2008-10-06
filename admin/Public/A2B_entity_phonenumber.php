<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_phonenumber.inc");
include ("../lib/admin.smarty.php");


if (! has_rights (ACX_PREDICTIVE_DIALER)) { 
	   Header ("HTTP/1.0 401 Unauthorized");
	   Header ("Location: PP_error.php?c=accessdenied");	   
	   die();	   
}



/***********************************************************************************/


getpost_ifset(array('action','campaign'));

if(!empty($action) &&!empty($campaign) && is_numeric($campaign) && ($action=="run"||$action=="hold"|| $action=="stop")){
	$DBHandle  = DbConnect();
	$status = 0;
	if($action == "stop") $status =2;
	elseif ($action == "hold") $status = 1;
	$table = new Table();
	$table ->SQLExec($DBHandle,"UPDATE cc_campain_phonestatus SET status = $status WHERE id_phonenumber =$id AND id_campaign = $campaign " );
	Header ("Location: A2B_entity_phonenumber.php?form_action=ask-edit&id=$id");
	
}
	
$HD_Form -> setDBHandler (DbConnect());


$HD_Form -> init();


if ($id!="" || !is_null($id)) {	
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);	
}


if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;


$list = $HD_Form -> perform_action($form_action);



// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
echo $CC_help_phonelist;



// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


// #### CREATE FORM OR LIST
if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];

$HD_Form -> create_form ($form_action, $list) ;


if($form_action="ask_edit"){
	$DBHandle  = DbConnect();
	$instance_table = new Table();
	
	$QUERY_PHONENUMBERS = 'SELECT cc_campaign.id, cc_campaign.name,cc_campaign.status, cc_campaign.startingdate <= CURRENT_TIMESTAMP started ,cc_campaign.expirationdate <= CURRENT_TIMESTAMP expired  FROM cc_phonenumber , cc_phonebook , cc_campaign_phonebook, cc_campaign WHERE ';
	//JOIN CLAUSE
	$QUERY_PHONENUMBERS .= 'cc_phonenumber.id_phonebook = cc_phonebook.id AND cc_campaign_phonebook.id_phonebook = cc_phonebook.id AND cc_campaign_phonebook.id_campaign = cc_campaign.id ';
	//CAMPAIGN CLAUSE
	$QUERY_PHONENUMBERS .= 'AND cc_phonenumber.id= '.$id;
	$result = $instance_table -> SQLExec ($DBHandle, $QUERY_PHONENUMBERS);
	if($result){
	 	?>
	 	<br/>
	 	<br/>
		<table width="100%" class="editform_table1" >
		<tr>
			<th>
				<?php echo gettext("CAMPAIGN") ?> 
			</th>
			<th>
				<?php echo gettext("INFO") ?> 
			</th>
			<th>
				<?php echo gettext("STATUS") ?> 
			</th>
			<th>
				<?php echo gettext("ACTION") ?> 
			</th>
		</tr>
		
		<?php 
		foreach ($result as $phone){
			$query = "SELECT id_callback, status FROM cc_campain_phonestatus WHERE id_campaign = $phone[0] AND id_phonenumber = $id";
			$res = $instance_table -> SQLExec ($DBHandle, $query);
         ?>
		<tr>
			<td class="form_head" align="center" width="20%" >
			 <?php echo $phone['name'] ?>
			</td>
			<td  class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="40%" >
			 <?php 
			 	if ($phone['expired']) echo gettext("EXPIRED");
				else if ($phone['started']) {
					if($res) echo gettext("STARTED AND IN PROCESS");
					else echo gettext("STARTED BUT NOT IN PROCESS : check the batch");
				}else echo gettext("NOT STARTED");
				
			 ?>
			</td>
			<td  class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="10%" >
			 	<?php 
			 	if($res) {
			 		if($res[0]['status']==0) echo gettext("RUN");
			 		elseif ($res[0]['status']==1) echo gettext("HOLD");
			 		else echo gettext("STOP");
			 	}else echo gettext("NO STATUS");
			 	?>
			</td>
			<td  class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" align="center" width="30%" >
			 	&nbsp;
			 	<?php 
			 	if($res) {
			 	?>
			 		<a href="<?php echo "A2B_entity_phonenumber.php?action=run&id=$id&campaign=$phone[0]&section=16"?>"> <img src="<?php echo Images_Path."/control_play.png" ?>" border="0" title="<?php echo "RUN"?>" alt="<?php echo "RUN"?>"></a>
			 		<a href="<?php echo "A2B_entity_phonenumber.php?action=hold&id=$id&campaign=$phone[0]&section=16"?>"> <img src="<?php echo Images_Path."/control_pause.png" ?>" border="0" title="<?php echo "PAUSE"?>" alt="<?php echo "PAUSE"?>"></a>
			 		<a href="<?php echo "A2B_entity_phonenumber.php?action=stop&id=$id&campaign=$phone[0]&section=16"?>"> <img src="<?php echo Images_Path."/control_stop.png" ?>" border="0" title="<?php echo "STOP"?>" alt="<?php echo "STOP"?>"></a>
			
				<?php } ?>
			</td>
		</tr>
		
		<?php 	
		}
		
		?>
		</table>
		<?php 
		
	}
}

// #### FOOTER SECTION
$smarty->display('footer.tpl');



