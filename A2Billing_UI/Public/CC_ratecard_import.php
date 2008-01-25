<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");

set_time_limit(0);

if (! has_rights (ACX_RATECARD)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();
}

$FG_DEBUG = 0;

$DBHandle  = DbConnect();



$my_max_file_size = (int) MY_MAX_FILE_SIZE_IMPORT;


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

function sendtoupload(form){
	
	if (form.the_file.value.length < 2){
		alert ('<?php echo gettext("Please, you must first select a file !")?>');
		form.the_file.focus ();
		return (false);
	}
	
    document.forms["prefs"].elements["task"].value = "upload";	
	document.forms[0].submit();
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
								<?php					 
								 foreach ($list_trunk as $recordset){
								?>
									<option class=input value='<?php  echo $recordset[0]?>-:-<?php  echo $recordset[1]?>' <?php if ($recordset[0]==$trunk) echo "selected";?>><?php echo $recordset[1]?></option>                        
								<?php 	 }
								?>
						</select>	
						<br>
						</br>
				  				  
				<?php echo gettext("These fields are mandatory");?><br>

<select  name="bydefault" multiple="multiple" size="4" width="40" class="form_input_select">
	<option value="bb1"><?php echo gettext("dialprefix");?></option>
	<option value="bb2"><?php echo gettext("destination");?></option>
	<option value="bb3"><?php echo gettext("rate initial");?></option>
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
                      

					  <?php echo gettext("Use the example below  to format the CSV file. Fields are separated by  ; or :");?></br>
					  <?php echo gettext(". and , are used for decimal format.");?>


					  <br/>
					  <a href="importsamples.php?sample=RateCard_Complex" target="superframe"><?php echo gettext("Complex Sample");?></a> -
					  <a href="importsamples.php?sample=RateCard_Simple" target="superframe"> <?php echo gettext("Simple Sample");?></a>
                      </span></div>


						<center>
							<iframe name="superframe" src="importsamples.php?sample=RateCard_Simple" BGCOLOR=white	width=500 height=80 marginWidth=10 marginHeight=10  frameBorder=1  scrolling=yes>

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
					  <input type="submit" value="Import RateCard" onFocus=this.select() class="form_input_button" name="submit1" onClick="sendtoupload(this.form);">
					   </p>     
                  </td>
                </tr>
               
               
              </form>
            </table>
</center>

<?php
	$smarty->display('footer.tpl');
?>
