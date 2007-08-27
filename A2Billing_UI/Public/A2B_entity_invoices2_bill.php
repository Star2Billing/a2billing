<?php
include ("../lib/defines.php");
include ("../lib/module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_list_invoice.inc");
include ("../lib/smarty.php");
include ("../lib/A2B_invoice.php");

if (! has_rights (ACX_BILLING)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
	
}
// TODO: ajouter le menu custom template
getpost_ifset(array('tocustomer',
					'forcustomer',
					'customer',
					'rangeradio',
					'sendemail',
					'billcalls',
					'billcharges',
					'enableminimalamount',
					'customtemplate',
					'choose_currency',
					'minimalamount'
					));

$HD_Form -> setDBHandler (DbConnect());

$nowdate = date('Y-m-d H:i:s');
$verbose_level = 0;
$groupcard = 100;

$instance_table = new Table();
$currencies_list = get_currencies($HD_Form ->DBHandle);

// Set Default Values 
if ($rangeradio == "") {
	
	$billcalls = true;
	$billcharges = true;
	$choose_currency = '';
}
	
// Count how many cards to bill
if ($rangeradio == "one")
	$tocustomer = $forcustomer = $customer;
	
$QUERY = "SELECT count(*) FROM cc_card WHERE ID >= '$forcustomer' AND ID <= '$tocustomer'";
$result = $instance_table -> SQLExec ($HD_Form ->DBHandle, $QUERY);

$nb_card = $result[0][0];	
$nbpagemax = (intval($nb_card/$groupcard));

if ($verbose_level>=1) 
	echo "===> NB_CARD : $nb_card - NBPAGEMAX:$nbpagemax\n";

if ($nb_card < 1){
	
	if ($verbose_level>=1) echo "[No card to create the Invoice]\n";
			
} else {
	
	// Create Invoice object
	$invoice = new A2B_Invoice($HD_Form->DBHandle, $verbose_level);
	
	// For each customer
	for ($page = 0; $page <= $nbpagemax; $page++) 
	{
		if ($verbose_level >= 1)  echo "$page <= $nbpagemax \n";
		
		$Query_Customers = "SELECT id FROM cc_card WHERE ID >= '$forcustomer' AND ID <= '$tocustomer'";
		
		if ($A2B->config["database"]['dbtype'] == "postgres")
		{
			$Query_Customers .= " LIMIT $groupcard OFFSET ".$page*$groupcard;
		}
		else
		{
			$Query_Customers .= " LIMIT ".$page*$groupcard.", $groupcard";
		}
		$resmax = $instance_table -> SQLExec ($HD_Form ->DBHandle, $Query_Customers);
		
		if (is_array($resmax)){
			$numrow = count($resmax);
			if($verbose_level >= 2) print_r($resmax[0]);
		}else{
			$numrow = 0;
		}
		
		if($verbose_level >= 1) echo "\n Total Customers Found: ".$numrow;
		
		if ($numrow == 0) {
			if ($verbose_level>=1) echo "\n[No card to create the Invoice]\n";			
			exit();			
		}else{
			foreach($resmax as $Customer){
				
				$invoice->RetrieveInformation($Customer[0], $billcalls == "on", $billcharges == "on", $nowdate);
				$invoice->CreateInvoice($choose_currency);			
				$invoice->BillInvoice(($enableminimalamount == 'on'), $minimalamount, $customtemplate);
				
				if ($sendemail == "on")
					$invoice->SendEMail($smarty);
			}
		}
	}
}

$HD_Form -> init();

if (cardid!='' || !is_null($cardid)){	
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$cardid", $HD_Form -> FG_EDITION_CLAUSE);	
}

$HD_Form -> FG_OTHER_BUTTON1 = false;
$HD_Form -> FG_OTHER_BUTTON2 = false;	

$form_action = "list";

$list = $HD_Form -> perform_action($form_action);

$smarty->display('main.tpl');

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


// #### CREATE FORM OR LIST
if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];

// #### HELP SECTION
echo $CC_help_bill_invoice;

?>
<br>
<form name="theForm" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
<table align="center"  class="bar-status" border="0" width="55%">
       <tbody>
       	<tr>
       		<td class="bgcolor_002" align="left" width="30%">
       		 	&nbsp;&nbsp;
				<input type="radio" name="rangeradio" value="one" class="form_input_text" title="Bill one customer" <?php if ($rangeradio == "one") echo 'checked'; ?>>
				<font class="fontstyle_003"><?php echo gettext("SINGLE");?></font>       			   			
       		</td>
       		<td class="bgcolor_005">
       			<table width='100%'><tr>
       				<td align="left">
       					<?php echo gettext("Card ID");?>:
       				</td>
					<td align="right">
						<input TYPE="text" NAME="customer" value="<?php echo $customer?>" class="form_input_text">
						<a href="#" onclick="window.open('A2B_entity_card.php?popup_select=1&popup_formname=theForm&popup_fieldname=customer' , 'CardNumberSelection','scrollbars=1,width=550,height=330,top=20,left=100,scrollbars=1');">
							<img src="<?php echo Images_Path;?>/icon_arrow_orange.gif">
						</a>
					</td>
				</tr></table>
		</tr>
		<tr>
			<td class="bgcolor_004" rowspan = "2" align="left">
			 	&nbsp;&nbsp;
				<input type="radio" name="rangeradio" value="multi" class="form_input_text" title="Bill a range of customers" <?php if ($rangeradio == "multi" || $rangeradio == "") echo 'checked'; ?>>
				<font class="fontstyle_003"><?php echo gettext("MULTI");?></font>				
			</td>			
			<td class="bgcolor_003">
				<table width='100%'><tr>
					<td align="left">
						<?php echo gettext("From Card ID");?>:
					</td>
					<td align="right">
						<input TYPE="text" NAME="forcustomer" value="<?php echo $forcustomer?>" class="form_input_text">
						<a href="#" onclick="window.open('A2B_entity_card.php?popup_select=1&popup_formname=theForm&popup_fieldname=forcustomer' , 'CardNumberSelection','scrollbars=1,width=550,height=330,top=20,left=100,scrollbars=1');">
							<img src="<?php echo Images_Path;?>/icon_arrow_orange.gif">
						</a>
					</td>
				</tr></table>
			</td>
		</tr>			
		<tr>
			<td class="bgcolor_005">
				<table width='100%'><tr>
					<td align="left">
						<?php echo gettext("To");?>:
					</td>
					<td align="right">
						<input TYPE="text" NAME="tocustomer" value="<?php echo $tocustomer?>" class="form_input_text">
						<a href="#" onclick="window.open('A2B_entity_card.php?popup_select=1&popup_formname=theForm&popup_fieldname=tocustomer' , 'CardNumberSelection','scrollbars=1,width=550,height=330,top=20,left=100,scrollbars=1');">
							<img src="<?php echo Images_Path;?>/icon_arrow_orange.gif">
						</a>
					</td>
				</tr></table>
		</tr>
		<tr>
			<td class="bgcolor_002" align="left">
				<font class="fontstyle_003"> &nbsp;&nbsp;<?php echo gettext("EMAIL");?></font>
				
			</td>
			<td class="bgcolor_003" align="left">
				<input type="checkbox" name="sendemail" <?php if ($sendemail == "on") echo 'checked'; ?>>				
				<?php echo gettext("Send Email to Customer");?>
			</td>
		</tr>
		<tr>
			<td class="bgcolor_004" align="left">
				<font class="fontstyle_003"> &nbsp;&nbsp;<?php echo gettext("BILL");?></font>
			</td>			
			<td class="bgcolor_005" align="left">
				<table width='100%' border='0'><tr>
					<td width='50%'>
						<input type="checkbox" name="billcalls" <?php if ($billcalls == "on") echo 'checked'; ?>>
						<?php echo gettext("Calls");?>
					</td>
					<td>
						<input type="checkbox" name="billcharges" <?php if ($billcharges == "on") echo 'checked'; ?>>
						<?php echo gettext("Charges");?>
					</td>					
				</tr></table>
			</td>
		</tr>
		<tr>
			<td class="bgcolor_002" align="left">
				<font class="fontstyle_003"> &nbsp;&nbsp;<?php echo gettext("MINIMAL AMOUNT");?></font>
			</td>
			<td class="bgcolor_003" align="left">
				<input type="checkbox" name="enableminimalamount" <?php if ($enableminimalamount == "on") echo 'checked'; ?>>
				<input type="text" name="minimalamount" value="<?php echo $minimalamount?>" class="form_input_text">				
			</td>
		</tr>
		<tr>
			<td class="bgcolor_004" align="left">
				<font class="fontstyle_003"> &nbsp;&nbsp;<?php echo gettext("INVOICE TEMPLATE");?></font>
			</td>
			<td class="bgcolor_005" align="left">
				<select name="customtemplate" class="form_input_select" >
					<option value="" <?php if ($templatefile == "") echo 'selected';?>><?php echo gettext('Customer default'); ?></option>
					<?php
						$dir = opendir ('./templates/default/'.$A2B->config['global']['invoice_template_path']);
				        while (false !== ($file = readdir($dir))) {
				                if (strpos($file, '.tpl',1)) {
				                    echo "<option value=\"$file\" ".($customtemplate == $file? 'selected ':'').'> '.substr($file,0,-4).' </option>'; 
				                }
				        }
				        closedir($dir);
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td  class="bgcolor_002" align="left">
				<font class="fontstyle_003"><?php echo gettext("CURRENCY");?></font>
			</td>
			<td  class="bgcolor_003" align="left">
				<select NAME="choose_currency" size="1" class="form_input_select" >
					<option value='' <?php if ($choose_currency == '') echo 'selected';?>><?php echo gettext('Customer default'); ?></option>
				<?php
					$currencies_list = get_currencies();
					foreach($currencies_list as $key => $cur_value) {
				?>
					<option value='<?php echo $key ?>' <?php if (($choose_currency==$key) || (!isset($choose_currency) && $key==strtoupper(BASE_CURRENCY))){?>selected<?php } ?>><?php echo $cur_value[1].' ('.$cur_value[2].')' ?>
					</option>
				<?php 	} ?>			
				</select>
			</td>
		</tr>
		<tr>
		  <td class="bgcolor_005" align="center" colspan = "3">&nbsp;
		  	<input name="submit" type="submit" class="form_input_button"  value=" GENERATE INVOICE ">
		  </td>
		</tr>
     </tbody>
</table>
</form>
<br>
	  
<?php
$HD_Form -> create_form ($form_action, $list, $id=null);

$smarty->display('footer.tpl');
?>