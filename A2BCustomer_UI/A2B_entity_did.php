<?php
include ("./lib/defines.php");
include ("./lib/module.access.php");
include ("./lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_did.inc");
include ("./lib/smarty.php");

if (! has_rights (ACX_ACCESS)){
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}

if (!$A2B->config["webcustomerui"]['did'])
{
    exit();
}

/***********************************************************************************/

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


// #### HEADER SECTION
$smarty->display( 'main.tpl');




$FG_LIMITE_DISPLAY=10;
if (isset($mydisplaylimit) && (is_numeric($mydisplaylimit) || ($mydisplaylimit=='ALL'))){
	if ($mydisplaylimit=='ALL'){
		$FG_LIMITE_DISPLAY=5000;
	}else{
		$FG_LIMITE_DISPLAY=$mydisplaylimit;
	}
}
if (isset($choose_did_rate) && strlen($choose_did_rate)!=0){
	$did_rate=explode("CUR",$choose_did_rate);
	$choose_did=$did_rate[0];
	$rate=$did_rate[1];
}

$QUERY = "SELECT credit, creditlimit, typepaid	FROM cc_card	WHERE username = '".$_SESSION["pr_login"]."' AND uipass = '".$_SESSION["pr_password"]."'";
$DBHandle_max  = DbConnect();
$resmax = $DBHandle_max -> Execute($QUERY);
if ($resmax) {
	$row = $resmax -> fetchRow();
	$user_credit = $row[0];
	$user_creditlimit = $row[1];
	$user_typepaid = $row[2];
}

/*************************************************************/
/*           releese the choosen did                        */

if ($action_release=="confirm_release"){

	$message = "\n\n".gettext("The following Destinaton-DID has been relesed:")."\n\n";
	$instance_table = new Table();
	$QUERY = "UPDATE cc_did SET iduser = 0, reserved=0 WHERE id=$choose_did" ;
	$result = $instance_table -> SQLExec ($HD_Form -> DBHandle, $QUERY, 0);
	$message .= "QUERY on cc_did : $QUERY \n\n";

	$QUERY = "UPDATE cc_did_use SET releasedate = now() WHERE id_did =$choose_did and activated = 1" ;
	$result = $instance_table -> SQLExec ($HD_Form -> DBHandle, $QUERY, 0);
	$message .= "QUERY on cc_did_use : $QUERY \n\n";

	$QUERY = "INSERT INTO cc_did_use (activated, id_did) VALUES ('0','".$choose_did."')";
	$result = $instance_table -> SQLExec ($HD_Form -> DBHandle, $QUERY, 0);
	$message .= "INSERT new free entrie in cc_did use : $QUERY \n\n";

	$QUERY = "DELETE FROM cc_did_destination WHERE id_cc_did =".$choose_did;
	$result = $instance_table -> SQLExec ($HD_Form -> DBHandle, $QUERY, 0);
	$message .= "DELETE all DID destination: $QUERY \n\n";

	$date = date("D M j G:i:s T Y", time());
		// email header
	$em_headers  = "From: A2BILLING ALERT <a2billing_alert@localhost>\n";
	$em_headers .= "X-Priority: 3\n";
	if (strlen($A2B->config["webcustomerui"]['error_email'])>3)
	mail($A2B->config["webcustomerui"]['error_email'], "[$date] Release-DID notification", $message, $em_headers);

}

/***********************************************************/

if ($action_release=="ask_release") { 
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

if (!isset($action_release) || $action_release=="confirm_release" || $action_release==""){ 
	// #### HELP SECTION
	if ($form_action=='list')
	{
		echo $CC_help_list_did;
	}	

	if ((isset($confirm_buy_did)) && ($confirm_buy_did == 1))
	{
		if ($rate <= $user_credit || ($user_typepaid == 1 && $rate <= $user_credit + $user_creditlimit)) $confirm_buy_did = 2;
		else $confirm_buy_did = 0;
	} else 
	{   
		if ($confirm_buy_did != 4) $confirm_buy_did = 0;
	}

	if (is_numeric($voip_call) && ($confirm_buy_did >= 2)){
	//if (strlen($destination)>0  && is_numeric($choose_did) && is_numeric($voip_call) && ($confirm_buy_did >= 2)){		
		
		$instance_table_did_use = new Table();
		$QUERY = "INSERT INTO cc_did_destination (activated, id_cc_card, id_cc_did, destination, priority, voip_call) VALUES ('1', '".$_SESSION["card_id"]."', '".$choose_did."', '".$destination."', '1', '".$voip_call."')";

		$result = $instance_table_did_use -> SQLExec ($HD_Form -> DBHandle, $QUERY, 0);
		if ($confirm_buy_did == 2){			
			$QUERY1 = "INSERT INTO cc_charge (id_cc_card, amount, chargetype, id_cc_did, currency) VALUES ('".$_SESSION["card_id"]."', '".$rate."', '2','".$choose_did."','".strtoupper(BASE_CURRENCY)."')";
			$result = $instance_table_did_use -> SQLExec ($HD_Form -> DBHandle, $QUERY1, 0);	
			
			$QUERY1 = "UPDATE cc_did set iduser = ".$_SESSION["card_id"].",reserved=1 where id = '".$choose_did."'" ;
			$result = $instance_table_did_use -> SQLExec ($HD_Form -> DBHandle, $QUERY1, 0);
	
			$QUERY1 = "UPDATE cc_card set credit = credit -".$rate." where id = '".$_SESSION["card_id"]."'" ;
			$result = $instance_table_did_use -> SQLExec ($HD_Form -> DBHandle, $QUERY1, 0);
	
			$QUERY1 = "UPDATE cc_did_use set releasedate = now() where id_did = '".$choose_did."' and activated = 0" ;
			$result = $instance_table_did_use -> SQLExec ($HD_Form -> DBHandle, $QUERY1, 0);
	
			$QUERY1 = "INSERT INTO cc_did_use (activated, id_cc_card, id_did, month_payed) values ('1','".$_SESSION["card_id"]."','".$choose_did."', 1)";
			$result = $instance_table_did_use -> SQLExec ($HD_Form -> DBHandle, $QUERY1, 0);
		}
		$date = date("D M j G:i:s T Y", time());
		$message = "\n\n".gettext("The following Destinaton-DID has been added:")."\n\n";
		$message .= "$QUERY";

		// email header
		$em_headers  = "From: A2BILLING ALERT <a2billing_alert@localhost>\n";
		$em_headers .= "X-Priority: 3\n";

		if (strlen($A2B->config["webcustomerui"]['error_email'])>3)
		mail($A2B->config["webcustomerui"]['error_email'], "[$date] Destinaton-DID notification", $message, $em_headers);
	} else {
		if ($confirm_buy_did != 4) $confirm_buy_did = 0;
	}
	if (!isset ($current_page) || ($current_page == "")) $current_page=0;

///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////


	if ($id!="" || !is_null($id)){
		if (isset($form_action) && ($form_action=='ask-edit' || $form_action=='edit')){
			$HD_Form -> FG_EDITION_CLAUSE = " id = ".$id;
		}else{
			$HD_Form -> FG_EDITION_CLAUSE = $HD_Form -> FG_TABLE_CLAUSE." AND t1.id = ".$id;
		}
	}



	// TODO integrate in Framework
	if ($form_action == "delete")
	{
		$HD_Form -> FG_TABLE_NAME = "cc_did_destination";
		$HD_Form -> FG_EDITION_CLAUSE = "id_cc_card='".$_SESSION["card_id"]."' AND id = ".$id;
	}
	$list = $HD_Form -> perform_action($form_action);



///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////



$instance_table = new Table($HD_Form -> FG_TABLE_NAME, $HD_Form -> FG_COL_QUERY);
$instance_table_phonenumberdid = new Table($HD_Form -> FG_TABLE_NAME, $HD_Form -> FG_COL_QUERY);

$list_phonenumberdid = $instance_table_phonenumberdid -> Get_list ($HD_Form -> DBHandle, $HD_Form -> FG_TABLE_CLAUSE, $order, $sens, null, null, $limite, $current_record);
$nb_record = count($list_phonenumberdid );


$nb_record = $instance_table -> Table_count ($HD_Form -> DBHandle, $HD_Form -> FG_TABLE_CLAUSE);
if ($FG_DEBUG >= 1) var_dump ($list);

if ($nb_record<=$FG_LIMITE_DISPLAY){
	$nb_record_max=1;
}else{
	if ($nb_record % $FG_LIMITE_DISPLAY == 0){
		$nb_record_max=(intval($nb_record/$FG_LIMITE_DISPLAY));
	}else{
		$nb_record_max=(intval($nb_record/$FG_LIMITE_DISPLAY)+1);
	}
}

if ($FG_DEBUG == 3) echo "<br>Nb_record : $nb_record";
if ($FG_DEBUG == 3) echo "<br>Nb_record_max : $nb_record_max";



/*****************************************************************/

$instance_table_country = new Table("cc_country, cc_did", "cc_country.id, countryname");

$FG_TABLE_CLAUSE = "id_cc_country=cc_country.id and cc_did.reserved=0 group by cc_country.id, countryname ";

$list_country = $instance_table_country -> Get_list ($HD_Form -> DBHandle, $FG_TABLE_CLAUSE, "countryname", "ASC", null, null, null, null);

$nb_country = count($list_country);

if (!isset ($new_did_page) || ($new_did_page == ""))
{
	$new_did_page=0;
}

if (!isset($assign)) $assign=1;

if (isset($choose_country)){

		/*************************************************************/

		// LIST FREE DID TO ADD PHONENUMBER
		//	$instance_table_did = new Table("cc_did", "id, did, fixrate");
		//	$FG_TABLE_CLAUSE = "id_cc_country=$choose_country and id_cc_didgroup='".$_SESSION["id_didgroup"]."' and activated='1' and id NOT IN (select id_cc_did from cc_did_destination)";

		// FIX SQL for Mysql < 4 that doesn't support subqueries
		$instance_table_did = new Table("cc_did", "DISTINCT cc_did.id, did, fixrate");
		$FG_TABLE_CLAUSE = "id_cc_country=$choose_country and id_cc_didgroup='".$_SESSION["id_didgroup"]."' and reserved=0";
		$list_did = $instance_table_did -> Get_list ($HD_Form -> DBHandle, $FG_TABLE_CLAUSE, "did", "ASC", null, null, null, null);
		$nb_did = count($list_did);


}elseif ($assign>=2){
		// LIST USED DID TO ADD PHONENUMBER
		$instance_table_did = new Table("cc_did LEFT JOIN cc_did_use ON id_did=cc_did.id", "cc_did.id, did, fixrate");
		$FG_TABLE_CLAUSE = "id_cc_didgroup='".$_SESSION["id_didgroup"]."' and id_cc_card='".$_SESSION["card_id"]."' and cc_did_use.activated=1 AND ( releasedate IS NULL OR releasedate = '0000-00-00 00:00:00')  GROUP BY cc_did.id, did, fixrate ";
		//$instance_table_did -> debug_st = 1;
		$list_did = $instance_table_did -> Get_list ($HD_Form -> DBHandle, $FG_TABLE_CLAUSE, "did", "ASC", null, null, null, null);
		$nb_did = count($list_did);
}



///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////
?>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}


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


function openURL(theLINK)
{
	voucher = document.theForm.voucher.value;
	self.location.href = theLINK + goURL + "&voucher="+voucher;
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
	  </center>

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
					?>
						<option class=input value='<?php echo $recordset[0]."CUR".$recordset[2] ?>'<?php if ($choose_did_rate == $recordset[0]."CUR".$recordset[2]) echo 'selected';?>><?php echo $recordset[1]?>  (<?php echo $recordset[2].' '.BASE_CURRENCY ?> )</option>
					<?php 	 }
					?>
				</select>
			</td>
		</tr>
		<tr class="bgcolor_007">
		<?php if ($assign<=2){ ?> <td align="left" valign="bottom"> <?php }?>
<?php if (1==2){ ?>
				 <?php echo gettext("Ring To Number ");?> :
				<select name="countrycode"  class="form_input_select">
                <option value="1">1 - Virgin Islands British</option>
                <option value="0">0 - Austria</option>
                <option value="1">1 - Northern Mariana Islands</option>
                <option value="1">1 - Canada</option>
                <option value="1">1 - Anguilla</option>
                <option value="1">1 - Saint Kitts and Nevis</option>
                <option value="1">1 - Saint Lucia</option>
                <option value="1">1 - Saint Vincent and the Grenadines</option>
                <option value="1" selected="selected">1 - USA</option>
                <option value="1">1 - Virgin Islands US</option>
                <option value="20">20 - Egypt</option>
                <option value="212">212 - Morocco</option>
                <option value="213">213 - Algeria</option>
                <option value="216">216 - Tunisia</option>
                <option value="218">218 - Libya</option>
                <option value="220">220 - Gambia</option>
                <option value="221">221 - Senegal</option>
                <option value="222">222 - Mauritania</option>
                <option value="223">223 - Mali</option>
                <option value="224">224 - Guinea Republic</option>
                <option value="225">225 - Côte D'Ivoire</option
                ><option value="226">226 - Burkina Faso</option>
                <option value="227">227 - Niger</option>
                <option value="228">228 - Togo</option>
                <option value="229">229 - Benin</option>
                <option value="230">230 - Mauritius</option>
                <option value="231">231 - Liberia</option>
                <option value="232">232 - Sierra Leone</option>
                <option value="233">233 - Ghana</option>
                <option value="234">234 - Nigeria</option>
                <option value="235">235 - Chad</option>
                <option value="236">236 - Central Africa Republic</option>
                <option value="237">237 - Cameroon</option>
                <option value="238">238 - Cape Verde Islands</option>
                <option value="239">239 - São Tome and Principe</option
                ><option value="240">240 - Equatorial Guinea</option>
                <option value="241">241 - Gabon</option>
                <option value="242">242 - Congo</option>
                <option value="242">242 - Bahamas</option>
                <option value="244">244 - Angola</option>
                <option value="245">245 - Guinea-Bissau </option>
                <option value="246">246 - British Indian Ocean Territory</option>
                <option value="246">246 - Barbados</option>
                <option value="247">247 - Ascension Island</option>
                <option value="249">249 - Sudan</option>
                <option value="250">250 - Rwanda</option>
                <option value="252">252 - Somalia</option>
                <option value="253">253 - Djibouti</option>
                <option value="254">254 - Kenya</option>
                <option value="255">255 - Tanzania</option>
                <option value="256">256 - Uganda</option>
                <option value="257">257 - Burundi</option>
                <option value="260">260 - Zambia</option>
                <option value="261">261 - Madagascar</option>
                <option value="262">262 - Reunion Island</option>
                <option value="263">263 - Zimbabwe</option>
                <option value="264">264 - Namibia</option>
                <option value="265">265 - Malawi</option>
                <option value="266">266 - Lesotho</option>
                <option value="267">267 - Botswana</option>
                <option value="268">268 - Antigua and Barbuda</option>
                <option value="268">268 - Swaziland</option>
                <option value="269">269 - Mayotte Islands</option>
                <option value="269">269 - Comoros Island</option>
                <option value="27">27 - South Africa</option>
                <option value="290">290 - Saint Helena</option>
                <option value="297">297 - Aruba</option>
                <option value="298">298 - Faroe Islands</option>
                <option value="299">299 - Greenland</option>
                <option value="30">30 - Greece</option>
                <option value="31">31 - Netherlands</option>
                <option value="32">32 - Belgium</option>
                <option value="33">33 - France</option>
                <option value="34">34 - Spain</option>
                <option value="345">345 - Cayman Islands</option>
                <option value="350">350 - Gibraltar</option>
                <option value="351">351 - Portugal</option>
                <option value="352">352 - Luxembourg</option>
                <option value="353">353 - Ireland</option>
                <option value="354">354 - Iceland</option>
                <option value="356">356 - Malta</option>
                <option value="357">357 - Cyprus</option>
                <option value="358">358 - Finland</option>
                <option value="359">359 - Bulgaria</option>
                <option value="36">36 - Hungary</option>
                <option value="370">370 - Lithuania</option>
                <option value="371">371 - latvia</option>
                <option value="372">372 - Estonia</option>
                <option value="373">373 - Moldova</option>
                <option value="374">374 - Armenia</option>
                <option value="375">375 - Belarus</option>
                <option value="376">376 - Andorra</option>
                <option value="377">377 - Monaco</option>
                <option value="378">378 - San Marino</option>
                <option value="380">380 - Ukraine</option>
                <option value="381">381 - Serbia</option>
                <option value="385">385 - Croatia</option>
                <option value="387">387 - Bosnia</option>
                <option value="389">389 - Macedonia (Fyrom)</option>
                <option value="39">39 - Italy</option>
                <option value="39">39 - Holy See (Vatican City State)</option>
                <option value="40">40 - Romania</option>
                <option value="41">41 - Switzerland</option>
                <option value="420">420 - Czech Republic</option>
                <option value="421">421 - Slovak Republic</option>
                <option value="423">423 - Liechtenstein</option>
                <option value="44">44 - United Kingdom</option>
                <option value="441">441 - Bermuda</option>
                <option value="45">45 - Denmark</option>
                <option value="46">46 - Sweden</option>
                <option value="47">47 - Norway</option>
                <option value="473">473 - Grenada</option>
                <option value="48">48 - Poland</option>
                <option value="49">49 - Germany</option>
                <option value="500">500 - Falkland Islands</option>
                <option value="501">501 - Belize</option>
                <option value="502">502 - Guatemala</option>
                <option value="503">503 - Honduras</option>
                <option value="503">503 - El Salvador</option>
                <option value="505">505 - Nicaragua</option>
                <option value="506">506 - Costa Rica</option>
                <option value="507">507 - Panama</option>
                <option value="508">508 - Saint Pierre and Miquelon</option>
                <option value="509">509 - Haiti</option>
                <option value="51">51 - Peru</option>
                <option value="52">52 - Mexico</option>
                <option value="53">53 - Cuba</option>
                <option value="54">54 - Argentina</option>
                <option value="55">55 - Brazil</option>
                <option value="56">56 - Chile</option>
                <option value="57">57 - Columbia</option>
                <option value="58">58 - Venezuela</option>
                <option value="590">590 - Guadeloupe</option>
                <option value="591">591 - Bolivia</option>
                <option value="592">592 - Guyana</option>
                <option value="593">593 - Ecuador</option>
                <option value="594">594 - French Guiana&nbsp;</option>
                <option value="595">595 - Paraguay</option>
                <option value="597">597 - Surinam</option>
                <option value="598">598 - Uruguay</option>
                <option value="599">599 - Netherlands Antilles</option>
                <option value="60">60 - Malaysia</option>
                <option value="61">61 - Cocos(Keeling)Island</option><option value="61">61 - Christmas Island</option><option value="61">61 - Australia</option><option value="62">62 - Indonesia</option><option value="63">63 - Philippines</option><option value="64">64 - New Zealand</option><option value="649">649 - Turks &amp; Caicos Islands</option><option value="65">65 - Singapore</option><option value="66">66 - Thailand</option><option value="664">664 - Montserrat</option><option value="671">671 - Guam</option><option value="672">672 - Antarctica</option><option value="673">673 - Brunei</option><option value="674">674 - Nauru</option><option value="675">675 - Papua New Guinea</option><option value="676">676 - Tonga</option><option value="677">677 - Solomon Islands</option><option value="678">678 - Vanuatu</option><option value="679">679 - Fiji Islands</option><option value="680">680 - Palau</option><option value="681">681 - Wallis &amp; Futuna Islands</option><option value="682">682 - Cook Islands</option><option value="683">683 - Niue</option><option value="684">684 - American Samoa </option><option value="685">685 - Samoa </option><option value="686">686 - Kiribati</option><option value="687">687 - New Caledonia</option><option value="688">688 - Tuvalu</option><option value="689">689 - French Polynesia</option><option value="691">691 - Micronesia</option><option value="692">692 - Marshall Islands</option><option value="7">7 - Kazakhstan</option><option value="7">7 - Russia</option><option value="767">767 - Dominica Islands</option><option value="787">787 - Puerto Rico</option><option value="809">809 - Dominican Republic</option><option value="81">81 - Japan</option><option value="82">82 - Korea</option><option value="84">84 - Vietnam</option><option value="852">852 - Hong Kong</option><option value="853">853 - Macau</option><option value="855">855 - Cambodia</option><option value="856">856 - Laos</option><option value="86">86 - China</option><option value="868">868 - Trinidad &amp; Tobago</option><option value="871">871 - Inmarsat Atlantic Ocean-East </option><option value="872">872 - Inmarsat Pacific Ocean</option><option value="873">873 - Inmarsat Indian Ocean </option><option value="874">874 - Inmarsat Atlantic Ocean-West</option><option value="876">876 - Jamaica</option><option value="880">880 - Bangladesh</option><option value="886">886 - Taiwan</option><option value="90">90 - Turkey</option><option value="91">91 - India</option><option value="92">92 - Pakistan</option><option value="93">93 - Afghanistan</option><option value="94">94 - Sri Lanka</option><option value="95">95 - Myanmar (Burma)</option><option value="960">960 - Maldives Republic</option><option value="961">961 - Lebanon</option><option value="962">962 - Jordan</option><option value="963">963 - Syria</option><option value="964">964 - Iraq</option><option value="965">965 - Kuwait</option><option value="966">966 - Saudi Arabia</option><option value="968">968 - Oman</option><option value="971">971 - United Arab Emirates</option><option value="972">972 - Israel</option><option value="973">973 - Bahrain</option><option value="974">974 - Qatar</option><option value="975">975 - Bhutan</option><option value="977">977 - Nepal</option><option value="98">98 - Iran</option><option value="992">992 - Tajikistan</option><option value="993">993 - Turkmenistan</option><option value="994">994 - Azerbaijan</option><option value="995">995 - Georgia</option><option value="996">996 - Kyrgyzstan</option><option value="998">998 - Uzbekistan</option></select>

				-

				<input class="form_input_text" name="arecode" size="4" maxlength="5">

				<input class="form_input_text" name="phonenumber" size="10" maxlength="15" >
				<br/><center><font color="red">
				<?php echo gettext("Country Code - Area Code - Number");?></font></center>

<?php } ?>
<?php if ($assign<=2){
echo gettext("VOIP CALL : ");?> <?php echo gettext("Yes");?><input class="form_enter" name="voip_call" value="1" type="radio" <?php if ((isset($voip_call)) && ($voip_call == 1)) echo "checked" ?>> - <?php echo gettext("NO");?> <input class="form_enter" name="voip_call" value="0" type="radio" <?php if (!isset($voip_call)) { echo "checked";} else  {if ($voip_call == 0) echo "checked"; }?>>                        <span class="liens">
                                                </span><br>
				<?php echo gettext("Ring To destination ");?> :

				<input class="form_input_text" name="destination" size="40" maxlength="80"  <?php if (isset($destination) && ($confirm_buy_did!=4)) {?>value="<?php echo $destination; }?>">
				<br/><center><font color="red"><?php echo gettext("Enter the phone number you wish to call, or the SIP/IAX client to reach  (ie: 347894999 or SIP/jeremy@182.212.1.45). In order to call a VoIP number, you will need to enable voip_call");?> </font></center>
			</td>
<?php }else{ ?>
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
					<center><font color="red"><?php echo gettext("A monthly fee of ").number_format($rate,2,".",",")." ".BASE_CURRENCY."<br>".gettext(" will be carrie out from your acount");?> </font></center>
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
					<?php }else {?><center><font color="red"><?php echo "<br>".gettext("The purchase of the DID cant be done, your credit of  ").number_format($user_credit,2,".",",")." ".BASE_CURRENCY.gettext(" is lower than Fixerate of the DID  ").number_format($rate,2,".",",")." ".BASE_CURRENCY." </br> <hr>".gettext("Please reload your account ");?> </font></center>
				<?php } ?></td>
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
	  <br>

<?php 
// #### CREATE FORM OR LIST
//$HD_Form -> CV_TOPVIEWER = "menu";
if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];

$HD_Form -> create_form ($form_action, $list, $id=null) ;
} 
// #### FOOTER SECTION
$smarty->display( 'footer.tpl');
?>
