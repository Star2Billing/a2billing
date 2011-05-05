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


include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");

set_time_limit(0);

if (!has_rights(ACX_RATECARD)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

$FG_DEBUG = 0;
$DBHandle = DbConnect();
$my_max_file_size = (int) MY_MAX_FILE_SIZE_IMPORT;

// GET CALLPLAN LIST
$instance_table_tariffname = new Table("cc_tariffplan", "id, tariffname");
$FG_TABLE_CLAUSE = "";
$list_tariffname = $instance_table_tariffname->Get_list($DBHandle, $FG_TABLE_CLAUSE, "tariffname", "ASC", null, null, null, null);
$nb_tariffname = count($list_tariffname);

// GET TRUNK LIST
$instance_table_trunk = new Table("cc_trunk", "id_trunk, trunkcode");
$FG_TABLE_CLAUSE = "";
$list_trunk = $instance_table_trunk->Get_list($DBHandle, $FG_TABLE_CLAUSE, "id_trunk", "ASC", null, null, null, null);
$nb_trunk = count($list_trunk);

$smarty->display('main.tpl');

?>

<script language="JavaScript" type="text/javascript">
<!--

function sendtoupload(form){
	if (form.tariffplan.value.length < 1){
		alert ('<?php echo addslashes(gettext("Please, you must first select a ratecard !")); ?>');
		form.tariffplan.focus ();
		return (false);
	}
	if (form.the_file.value.length < 2){
		alert ('<?php echo addslashes(gettext("Please, you must first select a file !")); ?>');
		form.the_file.focus ();
		return (false);
	}
	
    document.forms["prefs"].elements["task"].value = "upload";	
	document.prefs.submit();
}

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

function moveSourceUp()
{
    var sel = document.prefs.selected_search_sources.selectedIndex;
	//var sel = document.prefs["selected_search_sources[]"].selectedIndex;
	
    if (sel == -1 || document.prefs.selected_search_sources.length <= 2) return;

    // deselect everything but the first selected item
    document.prefs.selected_search_sources.selectedIndex = sel;

    if (sel == 1) {
        tmp = document.prefs.selected_search_sources[sel];
        document.prefs.selected_search_sources[sel] = null;
        document.prefs.selected_search_sources[document.prefs.selected_search_sources.length] = tmp;
        document.prefs.selected_search_sources.selectedIndex = document.prefs.selected_search_sources.length - 1;
    } else {
        tmp = new Array();

        for (i = 1; i < document.prefs.selected_search_sources.length; i++) {
            tmp[i - 1] = new Option(document.prefs.selected_search_sources[i].text, document.prefs.selected_search_sources[i].value)
        }

        for (i = 0; i < tmp.length; i++) {
            if (i + 1 == sel - 1) {
                document.prefs.selected_search_sources[i + 1] = tmp[i + 1];
            } else if (i + 1 == sel) {
                document.prefs.selected_search_sources[i + 1] = tmp[i - 1];
            } else {
                document.prefs.selected_search_sources[i + 1] = tmp[i];
            }
        }

        document.prefs.selected_search_sources.selectedIndex = sel - 1;
    }

    resetHidden();
}

function moveSourceDown()
{
    var sel = document.prefs.selected_search_sources.selectedIndex;

    if (sel == -1 || document.prefs.selected_search_sources.length <= 2) return;

    // deselect everything but the first selected item
    document.prefs.selected_search_sources.selectedIndex = sel;

    if (sel == document.prefs.selected_search_sources.length - 1) {
        tmp = new Array();

        for (i = 1; i < document.prefs.selected_search_sources.length; i++) {
            tmp[i - 1] = new Option(document.prefs.selected_search_sources[i].text, document.prefs.selected_search_sources[i].value)
        }

        document.prefs.selected_search_sources[1] = tmp[tmp.length - 1];
        for (i = 0; i < tmp.length - 1; i++) {
            document.prefs.selected_search_sources[i + 2] = tmp[i];
        }

        document.prefs.selected_search_sources.selectedIndex = 1;
    } else {
        tmp = new Array();

        for (i = 1; i < document.prefs.selected_search_sources.length; i++) {
            tmp[i - 1] = new Option(document.prefs.selected_search_sources[i].text, document.prefs.selected_search_sources[i].value)
        }

        for (i = 0; i < tmp.length; i++) {
            if (i + 1 == sel) {
                document.prefs.selected_search_sources[i + 1] = tmp[i + 1];
            } else if (i + 1 == sel + 1) {
                document.prefs.selected_search_sources[i + 1] = tmp[i - 1];
            } else {
                document.prefs.selected_search_sources[i + 1] = tmp[i];
            }
        }

        document.prefs.selected_search_sources.selectedIndex = sel + 1;
    }

    resetHidden();
}

// -->
</script>

<?php

echo $CC_help_import_ratecard;

?>
<center>
		<b><?php echo gettext("New rate cards have to be imported from a CSV file.");?>.</b></br></br>
		<table width="95%" border="0" cellspacing="2" align="center" class="records">
			  <form name="prefs" enctype="multipart/form-data" action="CC_ratecard_import_analyse.php" method="post">
				<tr> 
                  <td colspan="2" align=center> 
				  <?php echo gettext("Choose the ratecard to import");?> :
				  <select NAME="tariffplan" size="1"  style="width=250" class="form_input_select">
								<option value=''><?php echo gettext("Choose a ratecard");?></option>
								<?php					 
								 foreach ($list_tariffname as $recordset){ 						 
								?>
									<option class=input value='<?php  echo $recordset[0]?>-:-<?php  echo $recordset[1]?>' <?php if ($recordset[0]==$tariffplan) echo "selected";?>><?php echo $recordset[1]?></option>                        
								<?php 	 }
								?>
						</select>	
						<br><br>
				   <?php echo gettext("Choose the trunk to use");?> :
						  <select NAME="trunk" size="1"  style="width=250" class="form_input_select">
						  		<OPTION  value="-1" selected><?php echo gettext("NOT DEFINED");?></OPTION>
								<?php					 
								 foreach ($list_trunk as $recordset){
								?>
									<option class=input value='<?php  echo $recordset[0]?>-:-<?php  echo $recordset[1]?>' <?php if ($recordset[0]==$trunk) echo "selected";?>><?php echo $recordset[1]?></option>                        
								<?php 	 }
								?>
						</select>	
						<br></br>
				  		  
					<?php echo gettext("These fields are mandatory");?><br>

					<select  name="bydefault" multiple="multiple" size="4" width="40" class="form_input_select">
						<option value="bb1"><?php echo gettext("dialprefix");?></option>
						<option value="bb2"><?php echo gettext("destination");?></option>
						<option value="bb3"><?php echo gettext("selling rate");?></option>
					</select>
					<br/><br/>
					
					<?php echo gettext("Choose the additional fields to import from the CSV file");?>.<br>
					
					<input name="search_sources" value="nochange" type="hidden">
					<table>
					    <tbody><tr>
					        <td>
					            <select name="unselected_search_sources" multiple="multiple" size="9" width="50" onchange="deselectHeaders()" class="form_input_select">
									<option value=""><?php echo gettext("Unselected Fields...");?></option>
									<option value="buyrate"><?php echo gettext("buyrate");?></option>
									<option value="buyrateinitblock"><?php echo gettext("buyrate min duration");?></option>
									<option value="buyrateincrement"><?php echo gettext("buyrate billing block");?></option>
					
									<option value="initblock"><?php echo gettext("sellrate min duration");?></option>
									<option value="billingblock"><?php echo gettext("sellrate billing block");?></option>
									
									<option value="connectcharge"><?php echo gettext("connect charge");?></option>
									<option value="disconnectcharge"><?php echo gettext("disconnect charge");?></option>
									<option value="disconnectcharge_after"><?php echo gettext("disconnect charge threshold");?></option>
									
									<option value="minimal_cost"><?php echo gettext("minimum call cost");?></option> 
									
									<option value="stepchargea"><?php echo gettext("step charge a");?></option>
									<option value="chargea"><?php echo gettext("charge a");?></option>
									<option value="timechargea"><?php echo gettext("time charge a");?></option>
									<option value="billingblocka"><?php echo gettext("billing block a");?></option>
					
									<option value="stepchargeb"><?php echo gettext("step charge b");?></option>
									<option value="chargeb"><?php echo gettext("charge b");?></option>
									<option value="timechargeb"><?php echo gettext("time charge b");?></option>
									<option value="billingblockb"><?php echo gettext("billing block b");?></option>
					
									<option value="stepchargec"><?php echo gettext("step charge c");?></option>
									<option value="chargec"><?php echo gettext("charge c");?></option>
									<option value="timechargec"><?php echo gettext("time charge c");?></option>
									<option value="billingblockc"><?php echo gettext("billing block c");?></option>
					
									<option value="startdate"><?php echo gettext("start date");?></option>
									<option value="stopdate"><?php echo gettext("stop date");?></option>
									<option value="additional_grace"><?php echo gettext("additional grace");?></option>
									<option value="starttime"><?php echo gettext("start time");?></option>
									<option value="endtime"><?php echo gettext("end time");?></option>
									<option value="tag"><?php echo gettext("tag");?></option>
									<option value="rounding_calltime"><?php echo gettext("rounding calltime");?></option>
									<option value="rounding_threshold"><?php echo gettext("rounding threshold");?></option>
					 				<option value="additional_block_charge"><?php echo gettext("additional block charge");?></option>
									<option value="additional_block_charge_time"><?php echo gettext("additional block charge time");?></option>
									<option value="announce_time_correction"><?php echo gettext("announce time correction");?></option>
									
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
					
					        <td>
					            <a href="" onclick="moveSourceUp(); return false;"><img src="<?php echo Images_Path;?>/up_black.png" alt="move up" title="move up" border="0"></a>
					            <br>
					            <a href="" onclick="moveSourceDown(); return false;"><img src="<?php echo Images_Path;?>/down_black.png" alt="move down" title="move down" border="0"></a>
					        </td>
					    </tr>
					</tbody></table>
		
				
				</td></tr>
				
				<tr>
				<td colspan="2" align="center">
				<?php echo gettext("Currency import as")?>&nbsp;: <input type="radio" name="currencytype" checked value="unit" > <?php echo gettext("Unit")?>&nbsp;&nbsp;
				<input type="radio" name="currencytype" value="cent"> <?php echo gettext("Cents")?>&nbsp;
				</td>
				</tr>
				<tr>
				<td colspan="2" align="center">&nbsp;
				
				</td>
				</tr>
                <tr> 
                  <td colspan="2"> 
                    <div align="center"><span class="textcomment"> 
                      

					  <?php echo gettext("Use the example below  to format the CSV file. Fields are separated by [,] or [;]");?><br/>
					  <?php echo gettext("(dot) . is used for decimal format.");?><br/>
					  <?php echo gettext("Note that Dial-codes expressed in REGEX format cannot be imported, and must be entered manually via the Add Rate page.");?>


					  <br/>
					  <a href="importsamples.php?sample=RateCard_Complex" target="superframe"><?php echo gettext("Complex Sample");?></a> -
					  <a href="importsamples.php?sample=RateCard_Simple" target="superframe"> <?php echo gettext("Simple Sample");?></a>
                      </span></div>


						<center>
							<iframe name="superframe" src="importsamples.php?sample=RateCard_Simple" BGCOLOR=white	width=600 height=80 marginWidth=10 marginHeight=10  frameBorder=1  scrolling=yes>

							</iframe>
                            </font>
						</center>
					  
                  </td>
                </tr>
                <tr> 
                  <td colspan="2"> 
                    <p align="center"><span class="textcomment"> 
                      <?php echo gettext("The maximum file size is ");?>
                      <?php echo $my_max_file_size / 1024?>
                      KB </span><br>
                      <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $my_max_file_size?>">
                      <input type="hidden" name="task" value="upload">
                      <input name="the_file" type="file" size="50" onFocus=this.select() class="saisie1">
					  <input type="button" value="Import RateCard" onFocus=this.select() class="form_input_button" name="submit1" onClick="sendtoupload(this.form);">
					   </p>     
                  </td>
                </tr>
               
               
              </form>
            </table>
</center>

<?php


$smarty->display('footer.tpl');
