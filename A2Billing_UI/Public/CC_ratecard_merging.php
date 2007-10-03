<?php
include ("../lib/defines.php");
include ("../lib/module.access.php");
include ("../lib/smarty.php");

set_time_limit(0);

if (! has_rights (ACX_RATECARD)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();
}

$FG_DEBUG = 0;

$DBHandle  = DbConnect();
$instance_table = new Table();
$bool = false;
getpost_ifset(array('posted','tariffplan','trunk', 'search_sources','enterratecard'));
if($posted == 1){
	
	if (!is_numeric($enterratecard)){ 
		$msg = gettext("No ratecard defined !");
		$bool = true; 
	}
	
	$tariffplanval= split('-:-', $tariffplan);
	if (!is_numeric($tariffplanval[0])){ 
		if($bool) $msg .= "<br>";
		$bool = true; 
		$msg .= gettext("No tariffplan defined !"); 
	}
	
	$trunkval= split('-:-', $trunk);
	if (!is_numeric($trunkval[0])){ 
		if($bool) $msg .= "<br>";
		$bool = true; 
		$msg .= gettext("No Trunk defined !"); 
	}
	
	if ($search_sources!='nochange'){
		//echo "<br>---$search_sources";
		$fieldtomerge= split("\t", $search_sources);
		$fieldtomerge_sql = str_replace("\t", ", ", $search_sources);
		$fieldtomerge_sql = trim ($fieldtomerge_sql);
		if (strlen($fieldtomerge_sql)>0) $fieldtomerge_sql = ', '.$fieldtomerge_sql;
	}
	
	$fields = "idtariffplan, id_trunk, dialprefix, destination, rateinitial";
	$fields .= $fieldtomerge_sql;
	$value = "SELECT $fields FROM cc_ratecard where id = $enterratecard";
	$func_fields = $fields;
	$func_table = 'cc_ratecard';
	$id_name = "";
	$subquery = true;
	$result = $instance_table -> Add_table ($DBHandle, $value, $func_fields, $func_table, $id_name,$subquery);
	if($result == 1){
		if($bool) $msg .= "<br>";
		$bool = true; 
		$msg .= "Ratecard is successfully merged.";
	}
	if($result != 1){
		if($bool) $msg .= "<br>";
		$bool = true; 
		$msg .= "Merge is unsuccessfull, please try again later .";
	}
}



/*************************************************************/

$instance_table_tariffname = new Table("cc_tariffplan", "id, tariffname");

$FG_TABLE_CLAUSE = "";

$list_tariffname = $instance_table_tariffname  -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, "tariffname", "ASC", null, null, null, null);

$nb_tariffname = count($list_tariffname);

/*************************************************************/

$instance_table_trunk = new Table("cc_trunk", "id_trunk, trunkcode");

$FG_TABLE_CLAUSE = "";

$list_trunk = $instance_table_trunk  -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, "id_trunk", "ASC", null, null, null, null);

$nb_trunk = count($list_trunk);


?>
<?php
$smarty->display('main.tpl');

?>
<script type="text/javascript">
<!--

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

//-->
</script>


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
<center>
		<b><?php echo gettext("New rate cards have to be merged form the existing");?>.</b></br></br>
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
					<td width="30%" valign="middle" class="form_head"><?php echo gettext("Select ratecard you want to merge together");?> :</td>
					<td width="70%" valign="top" class="tableBodyRight"><INPUT TYPE="text" NAME="enterratecard" value="<?php echo $enterratecard?>" size="8" class="form_input_text">&nbsp;<a href="#" onclick="window.open('A2B_entity_def_ratecard.php?popup_select=2&popup_formname=prefs&popup_fieldname=enterratecard' , 'RatecardSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><img src="<?php echo Images_Path;?>/icon_arrow_orange.gif"></a></td>
				</tr>
				<tr>
					<td width="30%" valign="middle" class="form_head"><?php echo gettext("Choose the ratecard");?> :</td>
                  	<td width="70%" valign="top" class="tableBodyRight">
				  		<select NAME="tariffplan" size="1"  style="width=250" class="form_input_select">
							<option value=''><?php echo gettext("Choose a ratecard");?></option>
							<?php foreach ($list_tariffname as $recordset){?>
								<option class=input value='<?php  echo $recordset[0]?>-:-<?php  echo $recordset[1]?>' <?php if ($recordset[0]==$tariffplan) echo "selected";?>><?php echo $recordset[1]?></option>                        
							<?php }?>
						</select>
					 </td>
				</tr>
				<tr>	
				  <td width="30%" valign="middle" class="form_head"><?php echo gettext("Choose the trunk to use");?> :</td>
				  <td width="70%" valign="top" class="tableBodyRight">
				  	<select NAME="trunk" size="1"  style="width=250" class="form_input_select">
					<?php foreach ($list_trunk as $recordset){?>
						<option class=input value='<?php  echo $recordset[0]?>-:-<?php  echo $recordset[1]?>' <?php if ($recordset[0]==$trunk) echo "selected";?>><?php echo $recordset[1]?></option>                        
					<?php }?>
				  	</select>
				  </td>
				</tr>
				<tr>	
				  <td width="30%" valign="middle" class="form_head"><?php echo gettext("These fields are mandatory");?> :</td>
				  <td width="70%" valign="top" class="tableBodyRight"><strong>Dialprefix, Destination and Rateinitial</strong></td>
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
