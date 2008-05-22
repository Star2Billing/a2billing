<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_invoice_list.inc.php");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_INVOICING)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}



/***********************************************************************************/

$HD_Form -> setDBHandler (DbConnect());


$HD_Form -> init();

if (cardid!='' || !is_null($cardid)){	
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$cardid", $HD_Form -> FG_EDITION_CLAUSE);	
}


if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;


$list = $HD_Form -> perform_action($form_action);



// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
echo $CC_help_money_situation;



// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


// #### CREATE FORM OR LIST
//$HD_Form -> CV_TOPVIEWER = "menu";
if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];

?>
<br>
<script language="javascript">
function go(URL)
{
	if ( Check() )
	{
		
		document.searchform.action = URL;		
		alert(document.searchform.action);
		document.searchform.submit();

	}
		
}	

function Check()
{
	if(document.searchform.filterradio[1].value == "payment")	
	{
		if (document.searchform.paymenttext.value < 0)
		{
			alert("Payment amount cannot be less than Zero.");
			document.searchform.paymenttext.focus();
			return false;
		}
	}	
	return true;
}
</script>
<form name="searchform" id="searchform" method="post" action="A2B_entity_invoices_billed.php?cardid=<?php echo $cardid;?>">
<input type="hidden" name="searchenabled" value="yes">
<table width="60%" bordercolor="#CCCCCC" cellpadding="2" cellspacing="0" align="center" style="border:1px solid orange;">
<tr>
	<td colspan="3" bgcolor="#FFFFCC">
		&nbsp;<font color="#3399CC" style="font-weight:bold; font-size:12px; font-family:Verdana, Arial, Helvetica, sans-serif"><?php echo gettext("Search")?></font>
	</td>	
</tr>
<tr height="5px">
<td colspan="3" >&nbsp;</td>
</tr>
<tr>
<td width="21%" align="right"><input type="radio" name="filterradio" value="date" class="form_input_text" title="Month and Year wise search" <?php if ($filterradio == "date" || $filterradio == "") echo 'checked'; ?>>  </td>
<td width="26%" align="right"><?php echo gettext("Invoice")?>: </td>
<td width="53%"> <select name="monthselect" class="form_input_select"> 
<option value="1" <?php if ($monthselect == "1") echo 'selected'; ?>><?php echo gettext("January") ?></option>
<option value="2" <?php if ($monthselect == "2") echo 'selected'; ?>><?php echo gettext("Feburay") ?></option>
<option value="3" <?php if ($monthselect == "3") echo 'selected'; ?>><?php echo gettext("March") ?></option>
<option value="4" <?php if ($monthselect == "4") echo 'selected'; ?>><?php echo gettext("April") ?></option>
<option value="5" <?php if ($monthselect == "5") echo 'selected'; ?>><?php echo gettext("May") ?></option>
<option value="6" <?php if ($monthselect == "6") echo 'selected'; ?>><?php echo gettext("June") ?></option>
<option value="7" <?php if ($monthselect == "7") echo 'selected'; ?>><?php echo gettext("July") ?></option>
<option value="8" <?php if ($monthselect == "8") echo 'selected'; ?>><?php echo gettext("August") ?></option>
<option value="9" <?php if ($monthselect == "9") echo 'selected'; ?>><?php echo gettext("September") ?></option>
<option value="10" <?php if ($monthselect == "10") echo 'selected'; ?>><?php echo gettext("October") ?>z</option>
<option value="11" <?php if ($monthselect == "11") echo 'selected'; ?>><?php echo gettext("November") ?></option>
<option value="12" <?php if ($monthselect == "12") echo 'selected'; ?>><?php echo gettext("December") ?></option>
</select> 
<select name="yearselect" class="form_input_select">
<option value="2001" <?php if ($yearselect == "2001") echo 'selected'; ?>>2001</option>
<option value="2002" <?php if ($yearselect == "2002") echo 'selected'; ?>>2002</option>
<option value="2003" <?php if ($yearselect == "2003") echo 'selected'; ?>>2003</option>
<option value="2004" <?php if ($yearselect == "2004") echo 'selected'; ?>>2004</option>
<option value="2005" <?php if ($yearselect == "2005") echo 'selected'; ?>>2005</option>
<option value="2006" <?php if ($yearselect == "2006") echo 'selected'; ?>>2006</option>
<option value="2007" <?php if ($yearselect == "2007") echo 'selected'; ?>>2007</option>
<option value="2008" <?php if ($yearselect == "2008") echo 'selected'; ?>>2008</option>
<option value="2009" <?php if ($yearselect == "2009") echo 'selected'; ?>>2009</option>
</select> </td>
</tr>
<tr>
<td align="right"> <input type="radio" name="filterradio" value="total" class="form_input_text" title="Payment Amount wise search" <?php if ($filterradio == "total") echo 'checked'; ?>></td>
<td align="right"> <?php echo gettext("Invoice Amount")?>:</td>
<td><select name="totaloperator" class="form_input_select">
<option value="equal" <?php if ($totaloperator == "equal") echo 'selected';?>>&nbsp; = &nbsp;</option>
<option value="greater" <?php if ($totaloperator == "greater") echo 'selected';?>>&nbsp; > &nbsp;</option>
<option value="less" <?php if ($totaloperator == "less") echo 'selected';?>>&nbsp; < &nbsp;</option>
<option value="greaterthanequal" <?php if ($totaloperator == "greaterthanequal") echo 'selected';?>>&nbsp; >= &nbsp;</option>
<option value="lessthanequal" <?php if ($totaloperator == "lessthanequal") echo 'selected'; ?>>&nbsp; <= &nbsp;</option>
</select> to
<input name="totaltext" id="totaltext" value="<?php echo $totaltext?>"  class="form_input_text" style="width:60px; text-align:right;">
</td>
</tr>
<tr>
	<td  >&nbsp;
		
	</td>	
	<td>&nbsp;</td>
	<td align="left"><input type="submit" name="submit" class="form_input_button" value="&nbsp;Search&nbsp;" onClick="javascript: return Check()"></td>
</tr>
</table>
</form>
<?php

$HD_Form -> create_form ($form_action, $list, $id=null) ;

?>
