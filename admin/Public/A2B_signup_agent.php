<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");


if (! has_rights (ACX_ADMINISTRATOR)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();
}

getpost_ifset(array('agentid', 'tariffplan', 'group','task'));


$FG_DEBUG = 0;

$DBHandle  = DbConnect();

$instance_table_agent = new Table("cc_agent ", "id, login, firstname, lastname");

$list_agent = $instance_table_agent  -> Get_list ($DBHandle, "", "id", "ASC", null, null, null, null);

$disabled = true;
	
if(!empty($agentid) && is_numeric($agentid)){
	
	$instance_table_tariffname = new Table("cc_tariffgroup LEFT JOIN cc_agent_tariffgroup ON cc_tariffgroup.id = cc_agent_tariffgroup.id_tariffgroup", "id, tariffgroupname");
	
	$FG_TABLE_CLAUSE = "id_agent = ".$agentid;
	
	$list_tariffname = $instance_table_tariffname  -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, "tariffgroupname", "ASC", null, null, null, null);
	
	$instance_table_group = new Table("cc_card_group", "id, name");
	
	$FG_TABLE_CLAUSE = "id_agent = ".$agentid;
	
	$list_group = $instance_table_group -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, "id", "ASC", null, null, null, null);
	
	$disabled =false;
}



if($task=="generate" && !empty($agentid) && !empty($tariffplan) && !empty($group)){
	$instance_table_agent_secret = new Table("cc_agent ", "secret");
	$list_agent_secret = $instance_table_agent_secret  -> Get_list ($DBHandle, "id=".$agentid, "id", "ASC", null, null, null, null);
	if(is_array($list_agent_secret)){
		$URL = $A2B->config['signup']['urlcustomerinterface']."signup/index.php?agentid=".$agentid."&agentkey=";
		$secret = $list_agent_secret[0][0];
		echo $secret."    ";
		$result = a2b_encrypt($group."-".$tariffplan."-",$secret);
		echo $result;
		$URL.= urlencode($result);
	}
}



?>
<?php
$smarty->display('main.tpl');

?>
<script type="text/javascript">
<!--


function submit_form(form){
	if ((form.tariffplan.value.length < 1)||(form.group.value.length < 1)||(form.agentid.value.length < 1)){
		return (false);
	}

    document.forms["form"].elements["task"].value = "generate";	
    document.form.submit();
}

//-->
</script>


<?php
 	echo $CC_help_generate_signup;
?>
<center>
		<b><?php echo gettext("Create signup url for a specific agent, customer group and Call Plan.");?>.</b></br></br>
		<table width="95%" border="0" cellspacing="2" align="center" class="records">
			
              <form name="form" enctype="multipart/form-data" action="A2B_signup_agent.php" method="post">
				<tr> 
                 	 <td colspan="2" align=center> 
					  	<?php echo gettext ( "Select Agent" );?>: 
					  	
					  	<select id="selectagent" NAME="agentid" size="1" class="form_input_select">
								<option value='' ><?php echo gettext("Choose an Agent ");?></option>
								<?php					 
									 foreach ($list_agent as $recordset){ 						 
								?>
									<option class=input value='<?php  echo $recordset['id']?>' <?php if ($recordset['id']==$agentid) echo "selected";?>><?php echo $recordset['login']." ( ".$recordset['id']." - ".$recordset['firstname']." ".$recordset['lastname']." )"?></option>                        
								<?php 	 }
								?>
				  		 </select>
				  		 <a href="#"
					onclick="window.open('A2B_entity_agent.php?popup_select=1&popup_formname=form&popup_fieldname=agentid' , 'CardNumberSelection','scrollbars=1,width=550,height=330,top=20,left=100,scrollbars=1');"><img
					src="<?php echo Images_Path; ?>/icon_arrow_orange.gif"></a>
					  	
			  		</td>
	  			</tr> 
				<tr> 
                  <td colspan="2" align=center> 
				  <?php echo gettext("Choose the Call Plan to use");?> :
				  <select id="tariff" NAME="tariffplan" size="1"  style="width=250" class="form_input_select<?php if($disabled) echo "_disabled" ?>" <?php if($disabled) echo "disabled=true" ?> >
								<option value=''><?php echo gettext("Choose a Call Plan");?></option>
							
								<?php					 
								 foreach ($list_tariffname as $recordset){ 						 
								?>
									<option class=input value='<?php  echo $recordset['id']?>' <?php if ($recordset['id']==$tariffplan) echo "selected";?>><?php echo $recordset[1]?></option>                        
								<?php 	 }
								?>
						</select>	
						<br><br>
				   <?php echo gettext("Choose the Customer group to use");?> :
				  <select id="group" NAME="group" size="1"  style="width=250" class="form_input_select<?php if($disabled) echo "_disabled" ?>" <?php if($disabled) echo "disabled=true" ?> >
							  <option value=''><?php echo gettext("Choose a Customer Group");?></option>
								<?php					 
								 foreach ($list_group as $recordset){
								?>
									<option class=input value='<?php  echo $recordset['id']?>' <?php if ($recordset[0]==$group) echo "selected";?>><?php echo $recordset[1]?></option>                        
								<?php 	 }
								?>
						</select>	
						<br>
						</br>
				  				  
				
				</td>
				</tr>
                <tr> 
                 <td  width="50%"> 
					 &nbsp;   
                  </td>
                  <td  align="center" width="50%"> 
                  	  <input type="hidden" name="task" value="">
					  <input id="generate" type="button" value="Generate Url" onFocus=this.select() class="form_input_button_disabled" name="submit1" onClick="submit_form(this.form);" disabled="true" />
					   </p>     
                  </td>
                </tr>
				
				
                <tr> 
                  <td colspan="2"  align="left"> 

						<div id="result" >
						 
						 <?php if(!empty($URL)){ ?>
						 <span style="font-family: sans-serif" > 
						 <?php 	echo "<b>";
						 	echo gettext("GENERATED URL:");
						 	?>
						 	&nbsp;<a href="<?php echo $URL;?>"> <?php echo gettext("LINK"); ?></a>
						 	<?php
						 	 echo "<br/>";echo "<br/>";
						 	echo $URL;	echo "</b><br>"; ?>
						   </span>
						<?php  }  ?>
						
						</div>
					  
                  </td>
                </tr>
               
               
              </form>
            </table>
</center>

<?php
	$smarty->display('footer.tpl');
?>


<script type="text/javascript">
	

function checkgenerate(){

 var test = true;
  test = test && ($('#tariff').val().length>0);
  test = test && ($('#group').val().length>0);
  if(test){
   	$('#generate').removeAttr("disabled");
   	$('#generate').attr("class","form_input_button");
   }
  else{ 
  	$('#generate').attr("disabled", true);
  	$('#generate').attr("class","form_input_button_disabled");
  	}
}

$(document).ready(function () {
	$('#selectagent').change(function () {
			  document.form.method="GET";
	          $('form').submit();
	        });
	$('#group').change(function () {
			   checkgenerate();
			   $('#result').empty();
	        });
	$('#tariff').change(function () {
			   checkgenerate();
			   $('#result').empty();
	        });
});
</script>
