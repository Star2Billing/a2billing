<?php
include ("../lib/defines.php");
include ("../lib/module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("../lib/smarty.php");

set_time_limit(0);

if (! has_rights (ACX_RATECARD)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();
}

$HD_Form = new FormHandler();
$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();
$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_ID="id";
$HD_Form -> FG_FILTER_SEARCH_SESSION_NAME = 'entity_ratecard_selection';

getpost_ifset(array('posted' ,'ratecard_source' ,'ratecard_destination', 'search_sources'));
if($posted == 1){

$instance_table = new Table();
$bool = false;
	
	$ratecard_src_val = $ratecard_source;
	if (!is_numeric($ratecard_src_val)){ 
		if($bool) $msg .= "<br>";
		$bool = true; 
		$msg .= gettext("No Source ratecard selected !"); 
	}
	
	$ratecard_des_val = $ratecard_destination;
	if (!is_numeric($ratecard_des_val)){ 
		if($bool) $msg .= "<br>";
		$bool = true; 
		$msg .= gettext("No Destination ratecard selected !"); 
	}

	if ($ratecard_des_val == $ratecard_src_val){ 
		if($bool) $msg .= "<br>";
		$bool = true; 
		$msg .= gettext("Source Ratecard Should be different from Destination ratecard!"); 
	}


	if ($search_sources != 'nochange'){
		$fieldtomerge= split("\t", $search_sources);
		$fieldtomerge_sql = str_replace("\t", ", ", $search_sources);
		$fieldtomerge_sql = trim ($fieldtomerge_sql);
		if (strlen($fieldtomerge_sql)>0) $fieldtomerge_sql = ', '.$fieldtomerge_sql;
	}
	
	if(!$bool){
		$fields = "$ratecard_des_val, id_trunk, dialprefix, destination, rateinitial";
		$fields .= $fieldtomerge_sql; 
		
		$fun_fields = "idtariffplan, id_trunk, dialprefix, destination, rateinitial";
		$fun_fields .= $fieldtomerge_sql;
		
		$condition_del = "idtariffplan = $ratecard_des_val";
		if(!empty($_SESSION['search_ratecard'])){
			$condition_del .= " AND ".$_SESSION['search_ratecard'];
		}
		
		$condition_insert = "idtariffplan = $ratecard_src_val";
		if(!empty($_SESSION['search_ratecard'])){
			$condition_insert .= " AND ".$_SESSION['search_ratecard'];
		}

		$fun_table = "cc_ratecard";
		$result = $instance_table -> Delete_table ($HD_Form -> DBHandle, $condition_del, $fun_table);
		
		
		$value = "SELECT $fields FROM cc_ratecard where $condition_insert";
		$func_fields = $fun_fields;
		$func_table = 'cc_ratecard';
		$id_name = "";
		$subquery = true;
		$result = $instance_table -> Add_table ($HD_Form -> DBHandle, $value, $func_fields, $func_table, $id_name,$subquery);
		if($result == 1){
			$msg = "Ratecard is successfully merged.";
		}else{
			$msg = "Merge is unsuccessfull, please try again later .";
		}
	}
	$_SESSION['search_ratecard'] = "";
}

$HD_Form -> FG_FILTER_SEARCH_FORM = true;
$HD_Form -> FG_FILTER_SEARCH_TOP_TEXT = gettext("Define the criteria to search");
$HD_Form -> FG_FILTER_SEARCH_1_TIME_TEXT = gettext("Start Date / Month");
$HD_Form -> FG_FILTER_SEARCH_2_TIME_TEXT = gettext("Start Date / Day");
$HD_Form -> FG_FILTER_SEARCH_2_TIME_FIELD = 'startdate';
$HD_Form -> AddSearchElement_C1(gettext("TAG"), 'tag','tagtype');
$HD_Form -> AddSearchElement_C1(gettext("DESTINATION"), 'destination','destinationtype');
$HD_Form -> AddSearchElement_C1(gettext("PREFIX"),'dialprefix','dialprefixtype');
$HD_Form -> AddSearchElement_C2(gettext("BUYRATE"),'buyrate1','buyrate1type','buyrate2','buyrate2type','buyrate');
$HD_Form -> AddSearchElement_C2(gettext("RATE INITIAL"),'rateinitial1','rateinitial1type','rateinitial2','rateinitial2type','rateinitial');
$HD_Form -> prepare_list_subselection('list');
$HD_Form -> FG_FILTER_SEARCH_FORM_SELECT_TEXT = 'TRUNK';
$HD_Form -> AddSearchElement_Select('SELECT TRUNK',"cc_trunk","id_trunk, trunkcode, providerip","","trunkcode","ASC","id_trunk");
$_SESSION['search_ratecard'] = $HD_Form -> FG_TABLE_CLAUSE;


/*************************************************************/

$instance_table_tariffname = new Table("cc_tariffplan", "id, tariffname");

$con = "";

$list_tariffname = $instance_table_tariffname  -> Get_list ($HD_Form -> DBHandle, $con, "tariffname", "ASC", null, null, null, null);

$nb_tariffname = count($list_tariffname);


?>
<?php
$smarty->display('main.tpl');

?>
<script language="JavaScript" type="text/javascript">
<!--
function deselectHeaders()
{
    document.prefs.unselected_search_sources[0].selected = false;
    document.prefs.selected_search_sources[0].selected = false;
}

function resetHidden()
{
    var tmp = '';
    for (i = 1; i < document.prefs.selected_search_sources.length; i++) {
        tmp += document.prefs.selected_search_sources[i].value;
        if (i < document.prefs.selected_search_sources.length - 1)
            tmp += "\t";
    }

    document.prefs.search_sources.value = tmp;
}

function addSource()
{
    for (i = 1; i < document.prefs.unselected_search_sources.length; i++) {
        if (document.prefs.unselected_search_sources[i].selected) {
            document.prefs.selected_search_sources[document.prefs.selected_search_sources.length] = new Option(document.prefs.unselected_search_sources[i].text, document.prefs.unselected_search_sources[i].value);
            document.prefs.unselected_search_sources[i] = null;
            i--;
        }
    }

    resetHidden();
}

function removeSource()
{
    for (i = 1; i < document.prefs.selected_search_sources.length; i++) {
        if (document.prefs.selected_search_sources[i].selected) {
            document.prefs.unselected_search_sources[document.prefs.unselected_search_sources.length] = new Option(document.prefs.selected_search_sources[i].text, document.prefs.selected_search_sources[i].value)
            document.prefs.selected_search_sources[i] = null;
            i--;
        }
    }

    resetHidden();
}
// -->
</script>

<?php //echo $CC_help_import_ratecard;?>
<script language="JavaScript" src="javascript/card.js"></script>
<div class="toggle_hide2show">
<center><a href="#" target="_self" class="toggle_menu"><img class="toggle_hide2show" src="<?php echo KICON_PATH; ?>/toggle_hide2show.png" onmouseover="this.style.cursor='hand';" HEIGHT="16"> <font class="fontstyle_002"><?php echo gettext("SEARCH RATES");?> </font></a></center>
	<div class="tohide" style="display:none;">

<?php
// #### CREATE SEARCH FORM
	$HD_Form -> create_search_form();
?>

	</div>
</div>
<center>
		<table width="95%" border="0" cellspacing="2" align="center" class="editform_table1">
              <form name="prefs" action="<?=$_SERVER['PHP_SELF']?>" method="post">
				<?php if($posted){?>
				<tr>
					<td align="center" colspan="2">
						<?php echo $msg;?>
					</td>
				</tr>
				<?php }?>
				<tr>
					<td width="30%" valign="middle" class="form_head"><?php echo gettext("RATECARD SOURCE");?> :</td>
                  	<td width="70%" valign="top" class="tableBodyRight">
				  		<select NAME="ratecard_source" size="1"  style="width=250" class="form_input_select">
							<option value=''><?php echo gettext("SOURCE RATECARD");?></option>
							<?php foreach ($list_tariffname as $recordset){?>
								<option class=input value='<?php  echo $recordset[0]?>' <?php if ($recordset[0]==$tariffplan) echo "selected";?>><?php echo $recordset[1]?></option>                        
							<?php }?>
						</select>
					 </td>
				</tr>
				<tr>
					<td width="30%" valign="middle" class="form_head"><?php echo gettext("RATECARD TO UPDATE");?> :</td>
                  	<td width="70%" valign="top" class="tableBodyRight">
				  		<select NAME="ratecard_destination" size="1"  style="width=250" class="form_input_select">
							<option value=''><?php echo gettext("DESTINATION RATECARD");?></option>
							<?php foreach ($list_tariffname as $recordset){?>
								<option class=input value='<?php  echo $recordset[0]?>' <?php if ($recordset[0]==$tariffplan) echo "selected";?>><?php echo $recordset[1]?></option>                        
							<?php }?>
						</select>
					 </td>
				</tr>

				<tr>
					<td width="30%" valign="middle" class="form_head"><?php echo gettext("Choose the additional fields to merge");?> :</td> 
					<td width="70%" valign="top" class="tableBodyRight">
						<input name="search_sources" value="nochange" type="hidden">
						<table>
						    <tbody><tr>
						        <td>
						            <select name="unselected_search_sources" multiple="multiple" size="9" width="50" onchange="deselectHeaders()" class="form_input_select">
										<option value=""><?php echo gettext("Unselected Fields...");?></option>
										<option value="buyrate"><?php echo gettext("buyrate");?></option>
										<option value="buyrateinitblock"><?php echo gettext("buyrateinitblock");?></option>
										<option value="buyrateincrement"><?php echo gettext("buyrateincrement");?></option>
						
										<option value="initblock"><?php echo gettext("initblock");?></option>
										<option value="billingblock"><?php echo gettext("billingblock");?></option>
										<option value="connectcharge"><?php echo gettext("connectcharge");?></option>
										<option value="disconnectcharge"><?php echo gettext("disconnectcharge");?></option>
										<option value="stepchargea"><?php echo gettext("stepchargea");?></option>
										<option value="chargea"><?php echo gettext("chargea");?></option>
										<option value="timechargea"><?php echo gettext("timechargea");?></option>
										<option value="billingblocka"><?php echo gettext("billingblocka");?></option>
						
										<option value="stepchargeb"><?php echo gettext("stepchargeb");?></option>
										<option value="chargeb"><?php echo gettext("chargeb");?></option>
										<option value="timechargeb"><?php echo gettext("timechargeb");?></option>
										<option value="billingblockb"><?php echo gettext("billingblockb");?></option>
						
										<option value="stepchargec"><?php echo gettext("stepchargec");?></option>
										<option value="chargec"><?php echo gettext("chargec");?></option>
										<option value="timechargec"><?php echo gettext("timechargec");?></option>
										<option value="billingblockc"><?php echo gettext("billingblockc");?></option>
						
										<option value="startdate"><?php echo gettext("startdate");?></option>
										<option value="stopdate"><?php echo gettext("stopdate");?></option>
						
										<option value="starttime"><?php echo gettext("starttime");?></option>
										<option value="endtime"><?php echo gettext("endtime");?></option>
										<option value="tag"><?php echo gettext("tag");?></option>
										<option value="rounding_calltime"><?php echo gettext("rounding calltime");?></option>
										<option value="rounding_threshold"><?php echo gettext("rounding threshold");?></option>
						 				<option value="additional_block_charge"><?php echo gettext("additional block charge");?></option>
										<option value="additional_block_charge_time"><?php echo gettext("additional block charge time");?></option>
									</select>
						        </td>
						
						        <td>
						            <a href="" onclick="addSource(); return false;"><img src="<?php echo Images_Path;?>/forward.png" alt="add source" title="add source" border="0"></a>
						            <br>
						            <a href="" onclick="removeSource(); return false;"><img src="<?php echo Images_Path;?>/back.png" alt="remove source" title="remove source" border="0"></a>
						        </td>
						        <td>
						            <select name="selected_search_sources" multiple="multiple" size="9" width="50" onchange="deselectHeaders();" class="form_input_select">
										<option value=""><?php echo gettext("Selected Fields...");?></option>
									</select>
						        </td>
						    </tr>
						</tbody></table>
					</td>
				</tr>
				<tr>
					 <td colspan="2" style="border-bottom: medium dotted rgb(102, 119, 102);">&nbsp; </td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td align="right">
					<input class="form_input_button" name="submit"  TYPE="submit" VALUE="MERGE"></td>
				</tr>
				<input type="hidden" name="posted" value="1">
              </form>
            </table>
</center>

<?php
	$smarty->display('footer.tpl');
?>
