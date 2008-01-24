<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_currencies.inc");
include ("../lib/smarty.php");

if (! has_rights (ACX_BILLING)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

getpost_ifset(array('updatecurrency'));

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();


/********************************* BATCH UPDATE CURRENCY TABLE ***********************************/
$A2B -> DBHandle = $HD_Form -> DBHandle;
if ($updatecurrency == 1){
	$instance_table = new Table();
	$A2B -> set_instance_table ($instance_table);
	
	$QUERY =  "SELECT id,currency,basecurrency FROM cc_currencies ORDER BY id";
	$result = $A2B -> instance_table -> SQLExec ($A2B->DBHandle, $QUERY);
		
	$url = "http://download.finance.yahoo.com/d/quotes.csv?s=";
	
	/* result[index_result][field] */
	
	$index_base_currency = 0;

	if (is_array($result)){
		$num_cur = count($result);
		for ($i=0;$i<$num_cur;$i++){
			
			// Finish and add termination ? 
			if ($i+1 == $num_cur) $url .= BASE_CURRENCY.$result[$i][1]."=X&f=l1";
			else $url .= BASE_CURRENCY.$result[$i][1]."=X+";
			
			// Check what is the index of BASE_CURRENCY to save it
			if (strcasecmp(BASE_CURRENCY, $result[$i][1]) == 0) {
				$index_base_currency = $result[$i][0];
			}
		}
		
		// Create the script to get the currencies
		exec("wget '".$url."' -O /tmp/currencies.cvs  2>&1", $output);
		
		// get the file with the currencies to update the database
		$currencies = file("/tmp/currencies.cvs");
		
		// update database
		foreach ($currencies as $currency){
			
			$currency = trim($currency);
			
			if (!is_numeric($currency)){ 
				continue; 
			}
			$id++;
			// if the currency is BASE_CURRENCY the set to 1
			if ($id == $index_base_currency) $currency = 1;
			
			if ($currency!=0) $currency=1/$currency;
			$QUERY="UPDATE cc_currencies set value=".$currency;
			
			if (BASE_CURRENCY != $result[$i][2]){
				$QUERY .= ",basecurrency='".BASE_CURRENCY."'";
			}
			$QUERY .= " , lastupdate = CURRENT_TIMESTAMP WHERE id =".$id;
			
			$result = $A2B -> instance_table -> SQLExec ($A2B->DBHandle, $QUERY, 0);
		}
	}
	$update_msg = '<center><font color="green"><b>'.gettext('Success! All currencies are now updated.').'</b></font></center>';
}
/***********************************************************************************/


if ($id!="" || !is_null($id)){	
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);	
}


if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;


$list = $HD_Form -> perform_action($form_action);



// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
echo $CC_help_currency;
?>
<table align="center" border="0" width="65%"  cellspacing="1" cellpadding="2">
	<form name="updateForm" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
	<INPUT type="hidden" name="updatecurrency" value="1">
	<tr>
	  <td align="center"  class="bgcolor_001">
		&nbsp;<?php echo gettext("THE CURRENCY LIST IS BASED FROM YAHOO FINANCE"); ?>&nbsp;: 
			<input class="form_input_button"  value=" <?php echo gettext("CLICK HERE TO UPDATE NOW");?>  " type="submit">
		</td>
	</tr>
	</form>
</table>

<?php

if (isset($update_msg) && strlen($update_msg)>0) echo $update_msg; 

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


// #### CREATE FORM OR LIST
//$HD_Form -> CV_TOPVIEWER = "menu";
if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];

$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### FOOTER SECTION
$smarty->display('footer.tpl');




?>
