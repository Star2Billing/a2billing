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


include ("./lib/customer.defines.php");
include ("./lib/customer.module.access.php");
include ("./lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_did.inc");
include ("./lib/customer.smarty.php");

if (!has_rights(ACX_DID)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

$HD_Form->setDBHandler(DbConnect());
$HD_Form->init();

if (!isset ($form_action))
	$form_action = "list";
if (!isset ($action))
	$action = $form_action;

$FG_LIMITE_DISPLAY = 10;
if (isset ($mydisplaylimit) && (is_numeric($mydisplaylimit) || ($mydisplaylimit == 'ALL'))) {
	if ($mydisplaylimit == 'ALL') {
		$FG_LIMITE_DISPLAY = 5000;
	} else {
		$FG_LIMITE_DISPLAY = $mydisplaylimit;
	}
}
if (isset ($choose_did_rate) && strlen($choose_did_rate) != 0) {
	$did_rate = explode("CUR", $choose_did_rate);
	$choose_did = $did_rate[0];
	$rate = $did_rate[1];
}

$QUERY = "SELECT credit, creditlimit, typepaid FROM cc_card	WHERE username = '" . $_SESSION["pr_login"] . "' AND uipass = '" . $_SESSION["pr_password"] . "'";
$DBHandle_max = DbConnect();
$resmax = $DBHandle_max->Execute($QUERY);
if ($resmax) {
	$row = $resmax->fetchRow();
	$user_credit = $row[0];
	$user_creditlimit = $row[1];
	$user_typepaid = $row[2];
}

/*           release the choosen did                        */
if ($action_release == "confirm_release") {

	$message = "\n\n" . gettext("The following Destinaton-DID has been relesed:") . "\n\n";
	$instance_table = new Table();
	$QUERY = "UPDATE cc_did SET iduser = 0, reserved=0 WHERE id=$choose_did";
	$result = $instance_table->SQLExec($HD_Form->DBHandle, $QUERY, 0);
	$message .= "QUERY on cc_did : $QUERY \n\n";

	$QUERY = "UPDATE cc_did_use SET releasedate = now() WHERE id_did =$choose_did and activated = 1";
	$result = $instance_table->SQLExec($HD_Form->DBHandle, $QUERY, 0);
	$message .= "QUERY on cc_did_use : $QUERY \n\n";

	$QUERY = "INSERT INTO cc_did_use (activated, id_did) VALUES ('0','" . $choose_did . "')";
	$result = $instance_table->SQLExec($HD_Form->DBHandle, $QUERY, 0);
	$message .= "INSERT new free entrie in cc_did use : $QUERY \n\n";

	$QUERY = "DELETE FROM cc_did_destination WHERE id_cc_did =" . $choose_did;
	$result = $instance_table->SQLExec($HD_Form->DBHandle, $QUERY, 0);
	$message .= "DELETE all DID destination: $QUERY \n\n";

	$date = date("D M j G:i:s T Y", time());
	$from = 'a2billing_alert@localhost';
	$fromname = 'A2BILLING ALERT';
	$subject = "[$date] Release-DID notification";
	$messagetext = '';
	try {
	    a2b_mail(ADMIN_EMAIL, $subject, $messagetext, $from, $fromname);
	}catch (Exception $e) {
	    echo gettext("Error : Sending mail");
    }
}

/***********************************************************/

if ($action_release == "ask_release") {
	// #### HEADER SECTION
	$smarty->display('main.tpl');

	echo $CC_help_release_did;
?>
	<FORM action="A2B_entity_did.php" name="form1">
		<INPUT type="hidden" name="choose_did" value="<?php echo $choose_did?>">
		<INPUT type="hidden" name="action_release" value="confirm_release"><br><br>
		<br><br>
		<TABLE cellspacing="0" class="delform_table5">
			<tr>
				<td width="434" class="text_azul"><?php echo gettext("If you really want release this DID , Click on the release button.")?>
				</td>
			</tr>
			<tr height="2">
				<td style="border-bottom: medium dotted rgb(255, 119, 102);">&nbsp; </td>
			</tr>
			<tr>
		    		<td width="190" align="right" class="text"><INPUT title="<?php echo gettext("Release the DID ");?> " alt="<?php echo gettext("Release the DID "); ?>" hspace=2 name=submit src="<?php echo Images_Path;?>/btn_release_did_94x20.gif" type="image"></td>
			</tr>
		</TABLE>
	</FORM>
<?php

}


if (!isset ($action_release) || $action_release == "confirm_release" || $action_release == "") {

	if ((isset ($confirm_buy_did)) && ($confirm_buy_did == 1)) {
		if ($rate <= $user_credit || ($user_typepaid == 1 && $rate <= $user_credit + $user_creditlimit))
			$confirm_buy_did = 2;
		else
			$confirm_buy_did = 0;
	} else {
		if ($confirm_buy_did != 4)
			$confirm_buy_did = 0;
	}

	if (is_numeric($voip_call) && ($confirm_buy_did >= 2) && ($voip_call==0 || ($voip_call==1 && strpos(substr($destination, strpos( $destination, '@')),'.')))) {
		 
		$instance_table_did_use = new Table();
		$validated = ($voip_call==1) ? 0 : 1;
		
		if ($voip_call==0)
		    $destination = (intval($destination) > 0) ? $destination : 'no valid';
		
		$QUERY = "INSERT INTO cc_did_destination (activated, id_cc_card, id_cc_did, destination, priority, voip_call, validated) VALUES ('1', '" . $_SESSION["card_id"] . "', '" . $choose_did . "', '" . $destination . "', '1', '" . $voip_call . "', '$validated')";
		
		$result = $instance_table_did_use->SQLExec($HD_Form->DBHandle, $QUERY, 0);
		if ($confirm_buy_did == 2) {
			$QUERY1 = "INSERT INTO cc_charge (id_cc_card, amount, chargetype, id_cc_did) " .
					"VALUES ('" . $_SESSION["card_id"] . "', '" . $rate . "', '2','" . $choose_did . "')";
			$result = $instance_table_did_use->SQLExec($HD_Form->DBHandle, $QUERY1, 0);

			$QUERY1 = "UPDATE cc_did set iduser = " . $_SESSION["card_id"] . ",reserved=1 where id = '" . $choose_did . "'";
			$result = $instance_table_did_use->SQLExec($HD_Form->DBHandle, $QUERY1, 0);

			$QUERY1 = "UPDATE cc_card set credit = credit -" . $rate . " where id = '" . $_SESSION["card_id"] . "'";
			$result = $instance_table_did_use->SQLExec($HD_Form->DBHandle, $QUERY1, 0);

			$QUERY1 = "UPDATE cc_did_use set releasedate = now() where id_did = '" . $choose_did . "' and activated = 0";
			$result = $instance_table_did_use->SQLExec($HD_Form->DBHandle, $QUERY1, 0);

			$QUERY1 = "INSERT INTO cc_did_use (activated, id_cc_card, id_did, month_payed) values ('1','" . $_SESSION["card_id"] . "','" . $choose_did . "', 1)";
			$result = $instance_table_did_use->SQLExec($HD_Form->DBHandle, $QUERY1, 0);
		}
		$date = date("D M j G:i:s T Y", time());
		$message = "\n\n" . gettext("The following Destinaton for your DID has been added:") . "\n\n";
		$message .= "$QUERY";

		// email header
		$em_headers = "From: A2BILLING ALERT <a2billing_alert@localhost>\n";
		$em_headers .= "X-Priority: 3\n";

		if (strlen($A2B->config["webcustomerui"]['error_email']) > 3)
			mail($A2B->config["webcustomerui"]['error_email'], "[$date] Destinaton-DID notification", $message, $em_headers);

	} else {
		if ($voip_call==1 && strpos(substr($destination, strpos( $destination, '@')),'.') === false) {
			$confirm_buy_did = 5;
		} else {
			if ($confirm_buy_did != 4)
				$confirm_buy_did = 0;
		}
	}

	if (!isset ($current_page) || ($current_page == ""))
		$current_page = 0;

	if ($id != "" || !is_null($id)) {
		if (isset ($form_action) && ($form_action == 'ask-edit' || $form_action == 'edit')) {
			$HD_Form->FG_EDITION_CLAUSE = " id = " . $id;
		} else {
			$HD_Form->FG_EDITION_CLAUSE = $HD_Form->FG_TABLE_CLAUSE . " AND t1.id = " . $id;
		}
	}

	// TODO integrate in Framework
	if ($form_action == "delete") {
		$HD_Form->FG_TABLE_NAME = "cc_did_destination";
		$HD_Form->FG_EDITION_CLAUSE = "id_cc_card='" . $_SESSION["card_id"] . "' AND id = " . $id;
	}
	$list = $HD_Form->perform_action($form_action);

	// #### HEADER SECTION
	$smarty->display('main.tpl');

	// #### HELP SECTION
	if ($form_action == 'list') {
		echo $CC_help_list_did;
	}

	// #### TOP SECTION PAGE
	$HD_Form->create_toppage($form_action);

	$instance_table = new Table($HD_Form->FG_TABLE_NAME, $HD_Form->FG_COL_QUERY);
	$instance_table_phonenumberdid = new Table($HD_Form->FG_TABLE_NAME, $HD_Form->FG_COL_QUERY);
	$list_phonenumberdid = $instance_table_phonenumberdid->Get_list($HD_Form->DBHandle, $HD_Form->FG_TABLE_CLAUSE, $order, $sens, null, null, $limite, $current_record);
	$nb_record = count($list_phonenumberdid);

	$nb_record = $instance_table->Table_count($HD_Form->DBHandle, $HD_Form->FG_TABLE_CLAUSE);

	if ($nb_record <= $FG_LIMITE_DISPLAY) {
		$nb_record_max = 1;
	} else {
		if ($nb_record % $FG_LIMITE_DISPLAY == 0) {
			$nb_record_max = (intval($nb_record / $FG_LIMITE_DISPLAY));
		} else {
			$nb_record_max = (intval($nb_record / $FG_LIMITE_DISPLAY) + 1);
		}
	}

	$instance_table_country = new Table("cc_country, cc_did", "cc_country.id, countryname");
	$FG_TABLE_CLAUSE = "id_cc_country=cc_country.id and cc_did.reserved=0 group by cc_country.id, countryname ";
	$list_country = $instance_table_country->Get_list($HD_Form->DBHandle, $FG_TABLE_CLAUSE, "countryname", "ASC", null, null, null, null);
	$nb_country = count($list_country);

	if (!isset ($new_did_page) || ($new_did_page == "")) {
		$new_did_page = 0;
	}

	if (!isset ($assign))
		$assign = 1;

	if (isset ($choose_country)) {
		// LIST FREE DID TO ADD PHONENUMBER
		$instance_table_did = new Table("cc_did", "DISTINCT cc_did.id, did, fixrate, connection_charge, selling_rate, aleg_retail_connect_charge, aleg_retail_cost_min");
		$FG_TABLE_CLAUSE = "id_cc_country=$choose_country and id_cc_didgroup='" . $_SESSION["id_didgroup"] . "' and reserved=0";
		$list_did = $instance_table_did->Get_list($HD_Form->DBHandle, $FG_TABLE_CLAUSE, "did", "ASC", null, null, null, null);
		$nb_did = count($list_did);
	}
	elseif ($assign >= 2) {
		// LIST USED DID TO ADD PHONENUMBER
		$instance_table_did = new Table("cc_did LEFT JOIN cc_did_use ON id_did=cc_did.id", "cc_did.id, did, fixrate");
		$FG_TABLE_CLAUSE = "id_cc_didgroup='" . $_SESSION["id_didgroup"] . "' and id_cc_card='" . $_SESSION["card_id"] . "' and cc_did_use.activated=1 AND ( releasedate IS NULL OR releasedate < '1984-01-01 00:00:00')  GROUP BY cc_did.id, did, fixrate ";
		//$instance_table_did -> debug_st = 1;
		$list_did = $instance_table_did->Get_list($HD_Form->DBHandle, $FG_TABLE_CLAUSE, "did", "ASC", null, null, null, null);
		$nb_did = count($list_did);
	}
?>
<script language="JavaScript" type="text/JavaScript">
<!--

// Function to validate is a string is numeric
function IsNumeric(sText)
{
	var ValidChars = "0123456789.";
	var IsNumber=true;
	var Char;

	for (i = 0; i < sText.length && IsNumber == true; i++)
	{
		Char = sText.charAt(i);
		if (ValidChars.indexOf(Char) == -1)
		{
			IsNumber = false;
		}
	}
	return IsNumber;
}

function NextPage(){
	if (document.theForm.new_did_page.value < 2) 
	document.theForm.new_did_page.value++;
	else
	document.theForm.new_did_page.value=0;
}

function PrevPage(){
	if (document.theForm.new_did_page.value > 0) document.theForm.new_did_page.value--;
}

function CheckCountry(Source){
	var country,test=false;
	if ((Source == 'select') || (Source == 'NextButton1')) 
	{
		var index = document.theForm.choose_country.selectedIndex;
		country = document.theForm.choose_country.options[index].value;
		if (country == '') return false;
		if (IsNumeric(country)) test=true;
	}
	if ((Source == 'NextButton') || (Source == 'NextButton1')) 
	{
		var index = document.theForm.choose_country.selectedIndex;
		var indexdid = document.theForm.choose_did_rate.selectedIndex;
		destination = document.theForm.destination.value;
		if ((destination == '') || (indexdid <= 0)) return false;
		else test=true;
		NextPage();
	}
	if (Source == 'PrevButton')
	{
		test=true;
		PrevPage();
	}
	if (Source == 'Add')
	{	
		destination = document.theForm.destination.value;
		document.theForm.confirm_buy_did.value=4;
		if (destination == '') 
		{ 
			return false; 			
		}
		else test=true;
	}
	if (Source == 'did_release')
	{	
		document.theForm.action_release.value = 'ask_release';
		document.theForm.assign.value=1;
		did = document.theForm.choose_did_rate.value;
		if (did == '') 
		{ 
			return false; 			
		}
		else test=true;
	}
	if (test) document.theForm.submit();
	return false;
}


//-->
</script>
	  <center><?php echo $error_msg;?>
	  <a href="A2B_entity_did.php?assign=1"><input type="radio" value="1" <?php if ($assign==1) echo 'checked'; ?>/><?php echo gettext("Buy New DID");?> </a> - <a href="A2B_entity_did.php?assign=2"><input type="radio" value="2" <?php if ($assign==2) echo 'checked'; ?>/><?php echo gettext("Add Phone Number to your DID");?></a> - <a href="A2B_entity_did.php?assign=3"><input type="radio" value="3" <?php if ($assign==3) echo 'checked'; ?>/><?php echo gettext("Release DID");?></a>
	  
	   <table align="center"  border="0" class="bgcolor_006" width="75%">
		<form name="theForm" action="A2B_entity_did.php">
		<INPUT type="hidden" name="assign" value="<?php echo $assign ?>">
		<INPUT type="hidden" name="new_did_page" value="<?php echo $new_did_page?>">
		<INPUT type="hidden" name="confirm_buy_did" value="0">
		<INPUT type="hidden" name="action_release">
		<?php 
		switch ($new_did_page) {
		
		case 0:
		if ($assign==1){ ?>
		<tr class="bgcolor_001">
          <td align="left" width="80%" colspan="2">
				<select NAME="choose_country" size="1" class="form_input_select"  onChange="JavaScript:CheckCountry('select');">
					<option value=''><?php echo gettext("Select Country");?></option>
					<?php
				  	 foreach ($list_country as $recordset){
					?>
						<option class=input value='<?php echo $recordset[0]?>' <?php if ($choose_country==$recordset[0]) echo 'selected'; ?>><?php echo $recordset[1]?></option>
					<?php 	 }
					?>
				</select>
			</td>
		</tr>
		<?php } ?>
		<tr class="did_maintable_tr2" valign="top">
			<td align="left" valign="top" colspan="2">
				<select NAME="choose_did_rate" size="3" class="form_input_select">
					<option value=''><?php echo gettext("Select Virtual Phone Number");?></option>

					<?php
				  	foreach ($list_did as $recordset){
				  	// fixrate, connection_charge, selling_rate, aleg_retail_connect_charge, aleg_retail_cost_min
					$price_annoucement = gettext("Monthly:").$recordset[2].' '.BASE_CURRENCY;
					$price_annoucement .= ';'.gettext("B-Leg Connect:").$recordset[3].' '.BASE_CURRENCY;
					$price_annoucement .= ';'.gettext("B-Leg Rate:").$recordset[4].' '.BASE_CURRENCY;
					$price_annoucement .= ';'.gettext("A-Leg Connect:").$recordset[5].' '.BASE_CURRENCY;
					$price_annoucement .= ';'.gettext("A-Leg Rate:").$recordset[6].' '.BASE_CURRENCY;
					?>
					
						<option class=input value='<?php echo $recordset[0]."CUR".$recordset[2] ?>'<?php if ($choose_did_rate == $recordset[0]."CUR".$recordset[2]) echo 'selected';?>><?php echo $recordset[1]?>  (<?php echo $price_annoucement; ?>)</option>
					<?php 	 }
					?>
				</select>
			</td>
		</tr>
		<tr class="bgcolor_007">
		<?php if ($assign<=2){ ?> <td align="left" valign="bottom"> <?php } ?>

		<?php if ($assign<=2) {

			echo gettext("VOIP CALL : ");?> <?php echo gettext("Yes");?><input class="form_enter" name="voip_call" value="1" type="radio" <?php if ((isset($voip_call)) && ($voip_call == 1)) echo "checked" ?>> - <?php echo gettext("NO");?> <input class="form_enter" name="voip_call" value="0" type="radio" <?php if (!isset($voip_call)) { echo "checked";} else  {if ($voip_call == 0) echo "checked"; }?>>
				<br>
				<?php echo gettext("Destination");?> :

				<input class="form_input_text" name="destination" size="40" maxlength="120"  <?php if (isset($destination) && ($confirm_buy_did!=4)) {?>value="<?php echo $destination; }?>">
				<br/><center><font color="red"><?php echo gettext("Enter the phone number you wish to call, or the SIP/IAX client to reach  (ie: 347894999 or SIP/jeremy@182.212.1.45). In order to call a VoIP number, you will need to enable voip_call");?> </font></center>
			</td>
		<?php } else { ?>
			<td align="left" valign="middle">
				<center><font color="red"><?php echo "<br>".gettext("If you release the did you will not be monthly charged any more.")."<br><br>";?></font></center>
			</td>
		<?php }

				echo '<td align="center" valign="middle">';
				echo '<input class="form_input_button" value="' ;
				switch ($assign) {
					case 1:echo gettext("Next").'" type="button" onclick="CheckCountry(\'NextButton1\')">';
					break; 
					case 2:echo gettext("Add phone number").'" Type="button" onclick="CheckCountry(\'Add\')">';
					break;
					case 3: echo gettext("Ok").'" Type="button" onclick="CheckCountry(\'did_release\')">';
					break;
				}?>
			</td>
        </tr>
		<?php 
		break; 
		case 1:
		?>
		<INPUT type="hidden" name="choose_did_rate" value="<?php echo $choose_did_rate ?>">
		<INPUT type="hidden" name="destination" value="<?php echo $destination ?>">
		<INPUT type="hidden" name="voip_call" value="<?php echo $voip_call ?>">
		<INPUT type="hidden" name="choose_country" value="<?php echo $choose_country ?>">
		<INPUT type="hidden" name="confirm_buy_did" value="1">
		<tr class="bgcolor_007" valign="middle">
			<td colspan="2" height="40">
				<center><font color="black"><?php echo gettext("Confirm the purchase of the DID ");?> </font></center>
			</td>
		</tr>
		<tr class="did_maintable_tr2">
			<td colspan="2" height="40">
				<center><font color="red"><?php echo gettext("A monthly fee of ").number_format($rate,2,".",",")." ".BASE_CURRENCY."<br>".gettext(" will be carried out from your acount");?> </font></center>
			</td>
		</tr>
		<tr class="bgcolor_007">
			<td align="center" valign="middle">
				<input class="form_input_button"  value=" <?php echo gettext("Prev");?> " type="button" onclick="CheckCountry('PrevButton')">
			</td>
			<td align="center" valign="middle">
				<input class="form_input_button"  value=" <?php echo gettext("Ok");?> "type="button" onclick="CheckCountry('NextButton')">
			</td>
        </tr>
		<?php
		break; 
		
		case 2:
		?>
		<tr class="did_maintable_tr2" valign="middle">
			<td colspan="2" height="40">
				<?php
				if ($confirm_buy_did == 2) {?><center><font color="black"><?php echo gettext("The purchase of the DID is done ")?> </font></center>
				<?php }elseif ($confirm_buy_did == 5){?><center><font color="red"><?php echo "<br>".gettext("The purchase of the DID cannot be done, the VoIP destination have to be a proper URI");?> </font></center>
				<?php }else {?><center><font color="red"><?php echo "<br>".gettext("The purchase of the DID cannot be done, your credit of  ").number_format($user_credit,2,".",",")." ".BASE_CURRENCY.gettext(" is lower than Fixerate of the DID  ").number_format($rate,2,".",",")." ".BASE_CURRENCY." </br> <hr>".gettext("Please reload your account ");?> </font></center>
				<?php } ?>
			</td>
		</tr>
		<INPUT type="hidden" name="choose_did_rate" value="">
		<INPUT type="hidden" name="destination" value=" ">
		<INPUT type="hidden" name="voip_call" value="">
		<INPUT type="hidden" name="choose_country" value="">
		<tr class="bgcolor_007">
			<td align="center" valign="middle">
				<input class="form_input_button"  value=" <?php echo gettext("Ok");?> "type="button" onclick="CheckCountry('NextButton')">
			</td>
        </tr>
		<?php
		break;
		} ?>

	</form>
  </table>
  </center>
  <br>

<?php
				$HD_Form->create_form($form_action, $list, $id = null);
			} // End Switch


// #### FOOTER SECTION
$smarty->display('footer.tpl');

