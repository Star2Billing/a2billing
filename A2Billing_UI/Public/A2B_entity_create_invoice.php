<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_view_invoice.inc");
include ("../lib/smarty.php");
include ("../lib/invoice.php");

if (! has_rights (ACX_BILLING)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

getpost_ifset(array('tocustomer','forcustomer','sendemail'));
$HD_Form -> setDBHandler (DbConnect());
$verbose_level = 0;
$groupcard = 100;
if($forcustomer != "")
{
	$instance_table = new Table();
	$currencies_list = get_currencies($HD_Form ->DBHandle);
	
	// CHECK COUNT OF CARD ON WHICH APPLY THE SERVICE
	if($tocustomer == "")	
	{
		$QUERY = "SELECT count(*) FROM cc_card WHERE ID = '$forcustomer' ";
	}
	else
	{
		$QUERY = "SELECT count(*) FROM cc_card WHERE ID >= '$forcustomer' AND ID <= '$tocustomer'";
	}	
	$result = $instance_table -> SQLExec ($HD_Form ->DBHandle, $QUERY);
	$nb_card = $result[0][0];
	$nbpagemax = (intval($nb_card/$groupcard));
	
	if ($verbose_level>=1) echo "===> NB_CARD : $nb_card - NBPAGEMAX:$nbpagemax\n";
	
	if (!($nb_card>0)){
		if ($verbose_level>=1) echo "[No card to create the Invoice]\n";		
		exit();
	}		
	
	for ($page = 0; $page <= $nbpagemax; $page++) 
	{
		if ($verbose_level >= 1)  echo "$page <= $nbpagemax \n";
		$Query_Customers = "SELECT id, creationdate, firstusedate, expirationdate, enableexpire, expiredays, username, vat, invoiceday FROM cc_card ";
		if ($tocustomer == "")
		{
			$Query_Customers .= " WHERE ID = '$forcustomer' ";
		}
		else
		{
			$Query_Customers .= "  WHERE ID >= '$forcustomer' AND ID <= '$tocustomer'";
		}
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
									
				// Here we have to check for the Last Invoice date to set the Cover Start date. 
				// if a user dont have a Last invocie then we have to Set the Cover Start date to it Creation Date.
				$query_billdate = "SELECT CASE WHEN max(cover_enddate) is NULL THEN '0001-01-01 00:01:00' ELSE max(cover_enddate) END FROM cc_invoices WHERE cardid='$Customer[0]'";
				if ($verbose_level>=1) echo "\nQUERY_BILLDATE = $query_billdate";
				
				$resdate = $instance_table -> SQLExec ($HD_Form ->DBHandle, $query_billdate);
				if($verbose_level >= 2) print_r($resdate);
				if (is_array($resdate) && count($resdate)>0 && $resdate[0][0] != "0001-01-01 00:01:00"){
					// Customer Last Invoice Date
					$cover_startdate = $resdate[0][0];
				} else {
					// Customer Creation Date			
					$cover_startdate = $Customer[1];
				}
				if($verbose_level >= 1)	echo "\n Cover Start Date for '$Customer[6]': ".$cover_startdate;				
				$FG_TABLE_CLAUSE = " t1.username='$Customer[6]' AND t1.starttime > '$cover_startdate'";				
				
				// init totalcost
				$totalcost = 0;
				$totaltax = 0;
				$totalcall = 0;
				$totalminutes = 0;
				$totalcharge = 0;
				
				//************************************* CALLS SECTION *************************************************
				//$Query_Destinations = "SELECT destination, sum(t1.sessiontime) AS calltime, sum(t1.sessionbill) AS cost, count(*) AS nbcall FROM cc_call t1 WHERE (t1.sipiax<>2 AND t1.sipiax<>3) AND ".$FG_TABLE_CLAUSE." GROUP BY destination";		
				$Query_Destinations = "SELECT destination, sum(t1.sessiontime) AS calltime, sum(t1.sessionbill) AS cost, count(*) AS nbcall FROM cc_call t1 WHERE ".
									  $FG_TABLE_CLAUSE." GROUP BY destination";
				$list_total_destination = $instance_table -> SQLExec ($HD_Form ->DBHandle, $Query_Destinations);
				if (is_array($list_total_destination)){
					$num = count($list_total_destination);
				}else{
					$num = 0;
				}
				
				if($verbose_level >= 1){
					echo "\n Query_Destinations = $Query_Destinations";
					echo "\n Number of Destinatios for '$Customer[6]' Found: ".$num;
				}
				
				//Get the calls destination wise and calculate total cost			
				if (is_array($list_total_destination) && count($list_total_destination) > 0){
					foreach ($list_total_destination as $data){
						$totalcall+=$data[3];
						$totalminutes+=$data[1];
						$totalcost+=$data[2];
					}
				}
				if($verbose_level >= 1){
					echo "\n AFTER DESTINATION : totalcall = $totalcall - totalminutes = $totalminutes - totalcost = $totalcost ";
				}
							
				//************************************* CHARGE SECTION *************************************************
				// chargetype : 1 - connection charge for DID setup, 2 - Montly charge for DID use, 3 - Subscription fee, 4 - Extra Charge, etc...
				$FG_TABLE_CLAUSE = " id_cc_card='$Customer[0]' AND creationdate > '$cover_startdate'";
				$QUERY_CHARGE = "SELECT id, id_cc_card, iduser, creationdate, amount, chargetype, description, id_cc_did, currency, id_cc_subscription_fee FROM cc_charge".
								" WHERE $FG_TABLE_CLAUSE";
				$list_total_charge = $instance_table -> SQLExec ($HD_Form ->DBHandle, $QUERY_CHARGE, 1);
				$num  = 0;				
				$num = count($list_total_charge);
				if($verbose_level >= 1){
					echo "\n QUERY_CHARGE = $QUERY_CHARGE";
					echo "\n Number of Charge for '$Customer[6]' Found: ".$num;
				}
				
				//Get the calls destination wise and calculate total cost			
				if (is_array($list_total_charge) && count($list_total_charge) > 0){
					foreach ($list_total_charge as $data){
						$charge_amount = $data[4];
						$charge_currency = $data[8];
						$base_currency = $A2B->config['global']['base_currency'];
						$charge_converted = convert_currency ($currencies_list, $charge_amount, strtoupper($charge_currency), strtoupper($base_currency));
						if($verbose_level >= 1){
							echo "\n charge_amount = $charge_amount - charge_currency = $charge_currency ".
								 " - charge_converted=$charge_converted - base_currency=$base_currency";
						}
						$totalcharge+=1;
						$totalcost+=$charge_converted;
					}
				}
				if($verbose_level >= 1){
					echo "\n AFTER DESTINATION : totalcharge = $totalcharge - totalcost = $totalcost";
				}
				
				//************************************* INSERT INVOICE *************************************************			
				if ($Customer[7] > 0 && $totalcost > 0){
					$totaltax = ($totalcost / 100) * $Customer[7];
				}
				
				// Here we have to Create a Insert Statement to insert Records into the Invoices Table.
				$Query_Invoices = "INSERT INTO cc_invoices (cardid, orderref, invoicecreated_date, cover_startdate, cover_enddate, amount, tax, total, invoicetype,".
					"filename) VALUES ('$Customer[0]', NULL, NOW(), '$cover_startdate', NOW(), $totalcost, $totaltax, $totalcost + $totaltax, NULL, NULL)";
				$instance_table -> SQLExec ($HD_Form ->DBHandle, $Query_Invoices);
				if($sendemail =="Yes")
				{
					$QUERY = "Select Max(id) from cc_invoices";
					$result = $instance_table -> SQLExec ($HD_Form ->DBHandle, $QUERY);
					$invoice_id = $result[0][0];
					$ok = EmailInvoice($invoice_id, 2);
					$issent = 0;
					if($ok)
					{
						$issent = 1;
					}
					$currentdate = date("Y-m-d h:i:s");
					$QUERY = "INSERT INTO cc_invoice_history (invoiceid,invoicesent_date,invoicestatus) VALUES('$invoice_id', '$currentdate', '$issent')";
					$instance_table -> SQLExec ($HD_Form ->DBHandle, $QUERY);
				}				
				if($verbose_level >= 1)
				{
					echo "\n Total Cost for '$Customer[0]': ".$totalcost;
					echo "\n Query_Invoices=$Query_Invoices \n";
					echo "\n ################################################################################# \n\n";
				}
				
			}// END foreach($resmax as $Customer)
		}
	}		
	//End of ALL
}



$HD_Form -> init();

if (cardid!='' || !is_null($cardid)){	
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$cardid", $HD_Form -> FG_EDITION_CLAUSE);	
}
$HD_Form -> FG_OTHER_BUTTON1 = false;
$HD_Form -> FG_OTHER_BUTTON2 = false;


if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;

$list = $HD_Form -> perform_action($form_action);

$smarty->display('main.tpl');

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


// #### CREATE FORM OR LIST
//$HD_Form -> CV_TOPVIEWER = "menu";
if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];

// #### HELP SECTION
echo $CC_help_money_situation;

?>
<br>
<form name="theForm" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
<table align="center"  class="bgcolor_001" border="0" width="55%">
        <tbody><tr>
	
          <td align="left" width="35%">
		<?php echo gettext("FROM CARD ID");?>:	
		
		  
		</td>		
		<td width="65%" align="left" valign="bottom"><INPUT TYPE="text" NAME="forcustomer" value="<?php echo $forcustomer?>" class="form_input_text">
						<a href="#" onclick="window.open('A2B_entity_card.php?popup_select=1&popup_formname=theForm&popup_fieldname=forcustomer' , 'CardNumberSelection','scrollbars=1,width=550,height=330,top=20,left=100,scrollbars=1');"><img src="<?php echo Images_Path;?>/icon_arrow_orange.gif"></a> 
        </td>
	
        </tr>
          <tr>
            <td align="left"><?php echo gettext("TO CARD ID");?>:</td>
            <td align="left" valign="bottom"><INPUT TYPE="text" NAME="tocustomer" value="<?php echo $tocustomer?>" class="form_input_text">
						<a href="#" onclick="window.open('A2B_entity_card.php?popup_select=1&popup_formname=theForm&popup_fieldname=tocustomer' , 'CardNumberSelection','scrollbars=1,width=550,height=330,top=20,left=100,scrollbars=1');"><img src="<?php echo Images_Path;?>/icon_arrow_orange.gif"></a></td>
          </tr>
          <tr>
            <td align="left"><?php echo gettext("EMAIL TO CUSTOMER");?>:</td>
            <td align="left" valign="bottom"><input type="radio" name="sendemail" value="Yes"><?php echo gettext("Yes");?>&nbsp;&nbsp;<input type="radio" name="sendemail" value="No" checked><?php echo gettext("No");?></td>
          </tr>
          <tr>
            <td align="left">&nbsp;</td>
            <td align="left" valign="bottom"><input name="submit" type="submit" class="form_input_button"  value=" GENERATE INVOICE "></td>
          </tr>
      </tbody></table>
	   </form>
<br>
	  
<?php
	$HD_Form -> create_form ($form_action, $list, $id=null);

?>	  

<br>
<?php
$smarty->display('footer.tpl');
?>