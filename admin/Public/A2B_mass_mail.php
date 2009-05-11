<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("../lib/admin.smarty.php");


if (! has_rights (ACX_MAIL)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}


$HD_Form = new FormHandler("cc_card");
$HD_Form -> FG_FILTER_SEARCH_SESSION_NAME = 'entity_card_selection';
$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();
$instance_cus_table = new Table("cc_card","id, email, credit, currency, lastname, firstname, loginkey, username, useralias, uipass");
$cardstatus_list_r = array();
$cardstatus_list_r["0"]  = array("0", gettext("CANCELLED"));
$cardstatus_list_r["1"]  = array("1", gettext("ACTIVE"));
$cardstatus_list_r["2"]  = array("2", gettext("NEW"));
$cardstatus_list_r["3"]  = array("3", gettext("WAITING-MAILCONFIRMATION"));
$cardstatus_list_r["4"]  = array("4", gettext("RESERVED"));
$cardstatus_list_r["5"]  = array("5", gettext("EXPIRED"));

$currency_list_r = array();
$currencies_list = get_currencies();
foreach($currencies_list as $key => $cur_value) {
	$currency_list_r[$key]  = array( $key, $cur_value[1]);
}

$simultaccess_list_r = array();
$simultaccess_list_r["0"] = array( "0", gettext("INDIVIDUAL ACCESS"));
$simultaccess_list_r["1"] = array( "1", gettext("SIMULTANEOUS ACCESS"));

$language_list_r = array();
$language_list_r["0"] = array("en", gettext("ENGLISH"));
$language_list_r["1"] = array("es", gettext("SPANISH"));
$language_list_r["2"] = array("fr", gettext("FRENCH"));
	
$HD_Form -> FG_FILTER_SEARCH_FORM = true;
$HD_Form -> FG_FILTER_SEARCH_TOP_TEXT = gettext('Define specific criteria to search for cards created.');
$HD_Form -> FG_FILTER_SEARCH_1_TIME_TEXT = gettext('Creation date / Month');
$HD_Form -> FG_FILTER_SEARCH_2_TIME_TEXT = gettext('Creation date / Day');
$HD_Form -> FG_FILTER_SEARCH_2_TIME_FIELD = 'creationdate';
$HD_Form -> AddSearchElement_C1(gettext("ACCOUNT NUMBER"), 'username','usernametype');
$HD_Form -> AddSearchElement_C1(gettext("LASTNAME"),'lastname','lastnametype');
$HD_Form -> AddSearchElement_C1(gettext("LOGIN"),'useralias','useraliastype');
$HD_Form -> AddSearchElement_C1(gettext("MACADDRESS"),'mac_addr','macaddresstype');
$HD_Form -> AddSearchElement_C1(gettext("EMAIL"),'email','emailtype');
$HD_Form -> AddSearchElement_C2(gettext("CUSTOMER ID (SERIAL)"),'id1','id1type','id2','id2type','id');
$HD_Form -> AddSearchElement_C2(gettext("CREDIT"),'credit1','credit1type','credit2','credit2type','credit');
$HD_Form -> AddSearchElement_C2(gettext("INUSE"),'inuse1','inuse1type','inuse2','inuse2type','inuse');

$HD_Form -> FG_FILTER_SEARCH_FORM_SELECT_TEXT = '';
$HD_Form -> AddSearchElement_Select(gettext("SELECT LANGUAGE"), null, null, null, null, null, "language", 0, $language_list_r);
$HD_Form -> AddSearchElement_Select(gettext("SELECT TARIFF"), "cc_tariffgroup", "id, tariffgroupname, id", "", "tariffgroupname", "ASC", "tariff");
$HD_Form -> AddSearchElement_Select(gettext("SELECT STATUS"), null, null, null, null,null , "status", 0, $cardstatus_list_r);
$HD_Form -> AddSearchElement_Select(gettext("SELECT ACCESS"), null, null, null, null, null, "simultaccess", 0, $simultaccess_list_r);
$HD_Form -> AddSearchElement_Select(gettext("SELECT CURRENCY"), null, null, null, null, null, "currency", 0, $currency_list_r);
$HD_Form -> prepare_list_subselection('list');
$HD_Form -> FG_TABLE_ID="id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";
$nb_customer = 0;

if(!empty($HD_Form -> FG_TABLE_CLAUSE)) {
	$HD_Form -> FG_TABLE_CLAUSE .= " AND email <> ''";
	$list_customer = $instance_cus_table -> Get_list ($HD_Form -> DBHandle, $HD_Form -> FG_TABLE_CLAUSE, null, null, null, null, null, null);			
} else {
	$sql_clause = "email <> ''";
	$list_customer = $instance_cus_table -> Get_list ($HD_Form -> DBHandle, $sql_clause);
}

$nb_customer = sizeof($list_customer);
$DBHandle  = DbConnect();
$instance_table = new Table();



/***********************************************************************************/
getpost_ifset(array('subject', 'message','atmenu','submit','hd_email', 'total_customer', 'from', 'fromname'));


if(isset($submit)) {
	
	check_demo_mode();
	
	foreach ($list_customer as $cc_customer){
		$messagetext = $message;
		//  $email, $lastname, $firstname, $credit, $credit_currency, $currency, $cardnumber, $cardalias, $password, $loginkey, $base_currency
		$email = $cc_customer[1];
		$credit = $cc_customer[2];
		$currency = $cc_customer[3];
		$lastname = $cc_customer[4];
		$firstname = $cc_customer[5];
		$loginkey = $cc_customer[6];
		$username = $cc_customer[7];
		$useralias = $cc_customer[8];
		$uipass = $cc_customer[9];
		
		// convert credit to currency
		if (!isset($currencies_list[strtoupper($currency)][2]) || !is_numeric($currencies_list[strtoupper($currency)][2])) $mycur = 1;
		else $mycur = $currencies_list[strtoupper($currency)][2];
		$credit_currency = $credit / $mycur;
		$credit_currency = round($credit_currency,3);
		
		// replace tags in message
		$messagetext = str_replace('$email', $email, $messagetext);
		$messagetext = str_replace('$lastname', $lastname, $messagetext);
		$messagetext = str_replace('$firstname', $firstname, $messagetext);
		$messagetext = str_replace('$credit_currency', "$credit_currency", $messagetext);
		$messagetext = str_replace('$credit', $credit, $messagetext);
		$messagetext = str_replace('$currency', $currency, $messagetext);
		$messagetext = str_replace('$cardnumber', $username, $messagetext);
		$messagetext = str_replace('$cardalias', $useralias, $messagetext);
		$messagetext = str_replace('$password', $uipass, $messagetext);
		$messagetext = str_replace('$loginkey', "$loginkey", $messagetext);
		$messagetext = str_replace('$base_currency', BASE_CURRENCY, $messagetext);
		
		$subject_replaced = $subject;
		
		// replace tags in subject
		$subject_replaced = str_replace('$email', $email, $subject_replaced);
		$subject_replaced = str_replace('$lastname', $lastname, $subject_replaced);
		$subject_replaced = str_replace('$firstname', $firstname, $subject_replaced);
		$subject_replaced = str_replace('$credit_currency', "$credit_currency", $subject_replaced);
		$subject_replaced = str_replace('$credit', $credit, $subject_replaced);
		$subject_replaced = str_replace('$currency', $currency, $subject_replaced);
		$subject_replaced = str_replace('$cardnumber', $username, $subject_replaced);
		$subject_replaced = str_replace('$cardalias', $useralias, $subject_replaced);
		$subject_replaced = str_replace('$password', $uipass, $subject_replaced);
		$subject_replaced = str_replace('$loginkey', "$loginkey", $subject_replaced);
		$subject_replaced = str_replace('$base_currency', BASE_CURRENCY, $subject_replaced);
		
		a2b_mail($email, $subject_replaced, $messagetext, $from, $fromname);
		
		$result = true;
	}
	
}
// #### HEADER SECTION
$smarty->display('main.tpl');

echo $CC_help_mass_mail;

if(!isset($submit)) {
?>
<script language="JavaScript" src="javascript/card.js"></script>
<div class="toggle_hide2show">
<center><a href="#" target="_self" class="toggle_menu"><img class="toggle_hide2show" src="<?php echo KICON_PATH; ?>/toggle_hide2show.png" onmouseover="this.style.cursor='hand';" HEIGHT="16"> <font class="fontstyle_002"><?php echo gettext("SEARCH CUSTOMERS");?> </font></a></center>
	<div class="tohide" style="display:none;">

<?php
// #### CREATE SEARCH FORM
	$HD_Form -> create_search_form();
?>

	</div>
</div>

<?php 
}

// #### CREATE FORM OR LIST
if (strlen($_GET["menu"])>0) {
	$_SESSION["menu"] = $_GET["menu"];
}
?>
<FORM action="<?php echo $_SERVER['PHP_SELF']?>" method="post" name="mass_mail"> 
	<table class="editform_table1" cellspacing="2">
<?php
	if(isset($submit)) {
		if($result) {
?>
		<TR> 
		 <td align="center" colspan="2"><?php echo gettext("The e-mail has been sent to "); echo $total_customer; echo gettext(" customer(s)!")?></td>
		</TR>
	<?php } else {?>
		<tr> 
		 <td align="center" colspan="2"><?php echo gettext("There is some error sending e-mail, please try later.");?></td>
	   </tr>
	<?php 
		}
	
	} else {
		if(is_array($list_customer) || $nb_customer > 1) {
?>
	<tr> 
		<td><span class="viewhandler_span1">&nbsp;</span></td>
		<td align="right"> <span class="viewhandler_span1"><?php echo $nb_customer;?> <?php echo gettext("Record(s)");?></span></td>
	</tr>
<?php
	if (is_array($list_customer)) {
?>
       <TR> 		
			<TD width="%25" valign="middle" class="form_head"><?php echo gettext("TO");?></TD>  
			<TD width="%75" valign="top" class="tableBodyRight" background="../Public/templates/default/images/background_cells.gif" >
		    <?php
		    	$link_to_customer = CUSTOMER_UI_URL;
		    	//print_r ($list_customer);
		    	if(is_array($list_customer)){
					for($key=0; $key < $nb_customer && $key <= 50; $key++){
						echo "<a href=A2B_entity_card.php?form_action=ask-edit&id=".$list_customer[$key]['id']." target=\"_blank\">".$list_customer[$key][1]."</a>";
						if ($key + 1 != $nb_customer) echo ",&nbsp;";
							echo "<input type=\"hidden\" name=\"hd_email[]\" value=".$list_customer[$key][0].">";
						if($key == 19) {
							echo "<br><a href=\"A2B_entity_card.php?atmenu=card&stitle=Customers_Card&section=1\" target=\"_blank\">".gettext("Click on list customer to see them all")."</a>";
						}
					}
				}?><span class="liens"></span>&nbsp;<br>
		     </TD>
       </TR>
<?php 
	}
?>
		<TR> 		
			<TD width="%25" valign="middle" class="form_head"><?php echo gettext("EMAIL FROM");?></TD>  
			<TD width="%75" valign="top" class="tableBodyRight" background="../Public/templates/default/images/background_cells.gif" >
				<INPUT class="form_input_text" name="from"  size="20" maxlength="80" value="<?php echo EMAIL_ADMIN; ?>"><span class="liens"></span>&nbsp; 
			 </TD>
		</TR>
		<TR> 		
			<TD width="%25" valign="middle" class="form_head"><?php echo gettext("FROM NAME");?></TD>  
			<TD width="%75" valign="top" class="tableBodyRight" background="../Public/templates/default/images/background_cells.gif" >
				<INPUT class="form_input_text" name="fromname"  size="20" maxlength="80" value=""><span class="liens"></span>&nbsp; 
			 </TD>
		</TR>
		<TR> 		
			<TD width="%25" valign="middle" class="form_head"><?php echo gettext("SUBJECT");?></TD>  
			<TD width="%75" valign="top" class="tableBodyRight" background="../Public/templates/default/images/background_cells.gif" >
				<INPUT class="form_input_text" name="subject"  size="30" maxlength="80" value=""><span class="liens"></span>&nbsp; 
			 </TD>
		</TR>
		<TR> 		
			<TD width="%25" valign="middle" class="form_head"><?php echo gettext("MESSAGE");?></TD>  
			<TD width="%75" valign="top" class="tableBodyRight" background="../Public/templates/default/images/background_cells.gif" >
				<textarea class="form_input_textarea" name="message"  cols="70" rows="10""></textarea> 
					<span class="liens"></span>&nbsp; </TD>
		 </TR>
		 
		<tr>
		 <td colspan="2"> <?php echo gettext("The followings tags will be replaced in the message by the value in the database");?>  : $email, $lastname, $firstname, $credit, $credit_currency, $currency, $cardnumber, $cardalias, $password, $loginkey, $base_currency </td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td align="right">
			<input class="form_input_button" name="submit"  TYPE="submit" VALUE="<?php echo gettext("EMAIL");?>"></td>
		</tr>
			<?php } else {?>
		<tr>
			 <td colspan="2" align="center"><?php echo gettext("No Record Found!");?></td>
		</tr>
		<?php }
		}
		?>
		</table>
		<input type="hidden" name="total_customer" value="<?php echo $nb_customer?>">
	</FORM>

<?php

// #### FOOTER SECTION
$smarty->display('footer.tpl');

