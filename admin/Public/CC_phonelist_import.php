<?php
exit;
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");

set_time_limit(0);


if (! has_rights (ACX_PREDICTIVE_DIALER)){ 
	   Header ("HTTP/1.0 401 Unauthorized");
	   Header ("Location: PP_error.php?c=accessdenied");	   
	   die();	   
}

$FG_DEBUG = 0;

$DBHandle  = DbConnect();



$my_max_file_size = (int) MY_MAX_FILE_SIZE_IMPORT;


/*************************************************************/

$instance_table_tariffname = new Table("cc_campaign", "id, campaign_name");

$FG_TABLE_CLAUSE = "";

$list_campaign = $instance_table_tariffname  -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, "campaign_name", "ASC", null, null, null, null);

$nb_campaign = count($list_campaign);




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

<script language="JavaScript">
<!--
function sendtofield(form){

	if (form.listemail.value.length < 5){
		alert ('<?php echo gettext("Insert emails on the Field!")?>');
		form.listemail.focus ();
		return (false);
	}
	
    document.forms["prefs"].elements["task"].value = "field";	
	document.forms[0].submit();
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
	echo $CC_help_phonelist;
?>
<center>
		<b><?php echo gettext("New phone lists are imported from a CSV file.");?></b></br></br>
		<table width="95%" border="0" cellspacing="2" align="center" class="records">
			
              <form name="prefs" enctype="multipart/form-data" action="CC_phonelist_import_analyse.php" method="post">
			  
			  
				<tr> 
                  <td colspan="2" align=center>
				  <?php echo gettext("Choose the campaign to import");?> :
				  <select NAME="campaign" size="1"  style="width=250" class="form_input_select">
								<option value=''><?php echo gettext("Choose a campaign");?></option>
							
								<?php					 
								 foreach ($list_campaign as $recordset){ 						 
								?>
									<option class=input value='<?php  echo $recordset[0]?>-:-<?php  echo $recordset[1]?>' <?php if ($recordset[0]==$campaign) echo "selected";?>><?php echo $recordset[1]?></option>                        
								<?php 	 }
								?>
						</select>	
						<br>
						</br>
				  				  
			   <?php echo gettext("The fields below are mandatory");?>!<br>

<select  name="bydefault" multiple="multiple" size="2" width="40" class="form_input_select">
	<option value="bb1"><?php echo gettext("Phone Number");?></option>
</select>
<br/><br/>

				
				</td></tr>
				
                <tr> 
                  <td colspan="2"> 
                    <div align="center"><span class="textcomment"> 
                     <?php echo gettext("Use the example below to format the CSV file. Fields are separated by ; or :");?></br>
					  <?php echo gettext(". and , are used for decimal format.");?>
					  <br/>				
                      </span></div>
					  
					  
						<center>
							<iframe name="superframe" src="CC_phonelist_import_sample.txt" BGCOLOR=white	width=500 height=80 marginWidth=10 marginHeight=10  frameBorder=1  scrolling=yes>
						
							</iframe>
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
                      <input type="button" value="<?php echo gettext("Import Phonelist");?>" onFocus=this.select() class="form_input_button" name="submit1" onClick="sendtoupload(this.form);">
					  
                      <br>
                      &nbsp; </p>
                  </td>
                </tr>
                
                <tr> 
                  <td  class="bgcolor_014" colspan="2"><b> 
                    <?php echo $translate[P34_9]?>
                    </b></td>
                </tr>
               
              </form>
            </table>
</center>

<?php
	$smarty->display('footer.tpl');
?>
