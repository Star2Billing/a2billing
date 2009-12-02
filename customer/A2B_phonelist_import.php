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


include ("./lib/customer.defines.php");
include ("./lib/customer.module.access.php");
include ("./lib/customer.smarty.php");


set_time_limit(0);
if (! has_rights (ACX_AUTODIALER)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}

$FG_DEBUG = 0;
$DBHandle  = DbConnect();

$my_max_file_size = (int) MY_MAX_FILE_SIZE_IMPORT;


$instance_table_phonebook = new Table("cc_phonebook", "id, name");
$FG_TABLE_CLAUSE = "id_card =".$_SESSION['card_id'];
$list_phonebook = $instance_table_phonebook  -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, "name", "ASC", null, null, null, null);
$nb_phonebook = count($list_phonebook);


$smarty->display('main.tpl');

?>
<script type="text/javascript">
<!--

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function sendtoupload(form){
	if (form.phonebook.value.length < 1){
		alert ('<?php echo gettext("Please, you must first select a phonebook !")?>');
		form.tariffplan.focus ();
		return (false);
	}
	if (form.the_file.value.length < 2){
		alert ('<?php echo gettext("Please, you must first select a file !")?>');
		form.the_file.focus ();
		return (false);
	}
	
    document.forms["prefs"].elements["task"].value = "upload";	
	document.prefs.submit();
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
	echo $CC_help_import_phonebook;
?>
<center>
		<b><?php echo gettext("New phonebooks have to imported from a CSV file.");?>.</b></br></br>
		<table width="95%" border="0" cellspacing="2" align="center" class="records">
			
              <form name="prefs" enctype="multipart/form-data" action="A2B_phonelist_import_analyse.php" method="post">
			  
				<tr> 
                  <td colspan="2" align=center> 
				  <?php echo gettext("Choose the phonebook to import");?> :
				  <select NAME="phonebook" size="1"  style="width=250" class="form_input_select">
								<option value=''><?php echo gettext("Choose a phonebook");?></option>
							
								<?php					 
								 foreach ($list_phonebook as $recordset){ 						 
								?>
									<option class=input value='<?php  echo $recordset[0]?>-:-<?php  echo $recordset[1]?>' <?php if ($recordset[0]==$phonebook) echo "selected";?>><?php echo $recordset[1]?></option>                        
								<?php 	 }
								?>
						</select>	
						<br><br>
			
				  				  
				<?php echo gettext("These fields are mandatory");?><br>

<select  name="bydefault" multiple="multiple" size="1" width="40" class="form_input_select">
	<option value="bb1"><?php echo gettext("number");?></option>
</select>
<br/><br/>

<?php echo gettext("Choose the additional fields to import from the CSV file");?>.<br>

<input name="search_sources" value="nochange" type="hidden">
<table>
    <tbody><tr>
        <td>
            <select name="unselected_search_sources" multiple="multiple" size="5" width="50" onchange="deselectHeaders()" class="form_input_select">
				<option value=""><?php echo gettext("Unselected Fields...");?></option>
				<option value="name"><?php echo gettext("name");?></option>
				<option value="info"><?php echo gettext("info");?></option>

			</select>
        </td>

        <td>
            <a href="" onclick="addSource(); return false;"><img src="<?php echo Images_Path;?>/forward.png" alt="add source" title="add source" border="0"></a>
            <br>
            <a href="" onclick="removeSource(); return false;"><img src="<?php echo Images_Path;?>/back.png" alt="remove source" title="remove source" border="0"></a>
        </td>
        <td>
            <select name="selected_search_sources" multiple="multiple" size="5" width="50" onchange="deselectHeaders();" class="form_input_select">
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
				<td colspan="2" align="center">&nbsp;
				
				</td>
				</tr>
                <tr> 
                  <td colspan="2"> 
                    <div align="center"><span class="textcomment"> 
                      

					  <?php echo gettext("Use the example below  to format the CSV file. Fields are separated by [,] or [;]");?><br/>
					  <?php echo gettext("(dot) . is used for decimal format.");?>


					  <br/>
					  <a href="importsamples.php?sample=Phonebook_Complex" target="superframe"><?php echo gettext("Complex Sample");?></a> -
					  <a href="importsamples.php?sample=Phonebook_Simple" target="superframe"><?php echo gettext("Simple Sample");?></a>
                      </span></div>


						<center>
							<iframe name="superframe" src="importsamples.php?sample=Phonebook_Simple" BGCOLOR=white	width=500 height=80 marginWidth=10 marginHeight=10  frameBorder=1  scrolling=yes>

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
					  <input type="button" value="<?php echo gettext("Import Phonebook");?>" onFocus=this.select() class="form_input_button" name="submit1" onClick="sendtoupload(this.form);">
					   </p>     
                  </td>
                </tr>
               
               
              </form>
            </table>
</center>

<?php

$smarty->display('footer.tpl');

