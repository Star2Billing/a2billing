<?php

class RevRef2 {
	var $assoctable;
	var $assocleft;
	var $assocright;
	
	var $presenttable;
	var $presentname = 'name';
	var $presentid = 'id';
	
	var $debug_st = 1;

	/** params : 5= form field name 
	*/
	public function DispEdit($scol, $sparams, $svalue, $DBHandle = null){
		$presentname = $this->presenttable . '.' . $this->presentname ;
		$presentid = $this->presenttable . '.' . $this->presentid ;
		$assocleft= $this->assoctable . '.' . $this->assocleft;
		$assocright= $this->assoctable . '.' . $this->assocright;
		
		?><input type="hidden" name="<?php echo $sparams[5] . '_action' ?>" value="">
		<?php
		$QUERY = str_dbparams($DBHandle, "SELECT $presentid, $presentname FROM $this->presenttable, $this->assoctable ".
			"WHERE $assocleft= %1 AND $assocright = $presentid ; ",array($svalue));
			
		$res = $DBHandle->Execute ($QUERY);
		if (! $res){
			if ($this->debug_st) {
				?> Query failed: <?php echo htmlspecialchars($QUERY) ?><br>
				Error: <?php echo $DBHandle->ErrorMsg() ?><br>
				<?php
			}
			echo _("No data found!");
		}else{
		?> <table class="FormRR2t1">
		<thead>
		<tr><td><?php echo $sparams[0] ?></td><td><?php echo _("Action") ?></td></tr>
		</thead>
		<tbody>
		<?php while ($row = $res->fetchRow()){ ?>
			<tr><td><?php echo htmlspecialchars($row[1]) ?></td>
			    <td><a onClick="formRR2delete('<?php echo $scol ?>','<?php echo $sparams[5]. '_action' ?>','<?php echo $sparams[5] .'_del' ?>','<?php echo $row[0] ?>')" > <img src="../Images/icon-del.png" alt="<?php echo _("Remove this") ?>" /></a></td>
			</tr>
		<?php } ?>
		</tbody>
		</table>
		<input type="hidden" name="<?php echo $sparams[5] . '_del' ?>" value="">
		<?php
		}
		
		// Now, find those refs NOT already in the list!
		$QUERY = str_dbparams($DBHandle, "SELECT $presentid, $presentname FROM $this->presenttable ".
			"WHERE $presentid NOT IN (SELECT $assocright FROM $this->assoctable WHERE $assocleft= %1); ",
			array($svalue));
		$res = $DBHandle->Execute ($QUERY);
		if (! $res){
			if ($this->debug_st) {
				?> Query failed: <?php echo htmlspecialchars($QUERY) ?><br>
				Error: <?php echo $DBHandle->ErrorMsg() ?><br>
				<?php
			}
			echo _("No additional data found!");
		}else{
			$add_combos = array(array('', _("Select one to add..")));
			while ($row = $res->fetchRow()){
				$add_combos[] = $row;
			}
			gen_Combo($sparams[5]. '_add','',$add_combos);
			 ?>
			 <a onClick="formRR2add('<?php echo $scol ?>','<?php echo $sparams[5]. '_action' ?>')"><img src="../Images/btn_Add_94x20.png" alt="<?php echo _("Add this") ?>" /></a>
		<?php
		}
		
	}
	
	/** Produce the necessary html code at the body.
	    Useful for styles, scripts etc.
	    @param $action	The form action. Sometimes, the header is only needed 
	    		for edits etc.
	   */
	public function html_body($action = null){
		if ($action == 'list')
			return;
	?>
<style>
table.FormRR2t1 {
	border: thin solid black;
	color: blue;
	width: 300;
	font: Arial, Verdana;
}

table.FormRR2t1 thead td{
	background: gray;
	color: white;
	font-weight: bold;
}
</style>

<script language="JavaScript" type="text/JavaScript">
<!--
function formRR2delete(rid,raction,rname, instance){
  document.myForm.form_action.value = "object-edit";
  document.myForm.sub_action.value = rid;
  document.myForm.elements[raction].value='delete';
  if (rname != null) document.myForm.elements[rname].value = instance;
  myForm.submit();
}

function formRR2add(rid,raction){
  document.myForm.form_action.value = "object-edit";
  document.myForm.sub_action.value = rid;
  document.myForm.elements[raction].value='add';
  myForm.submit();
}
//-->
</script>
	
	<?php
	}

	/** Called by the framework when we have requested an 'object-edit'
	*/
	public function PerformObjEdit($scol, $sparams, $DBHandle = null){
		$oeaction = getpost_single($sparams[5].'_action');
		if ($this->debug_st)
			echo "Object edit! Action: $oeaction <br>\n";
		$oeid = getpost_single($sparams[1]);
		switch($oeaction){
		case 'add':
			$QUERY = str_dbparams($DBHandle,"INSERT INTO $this->assoctable ($this->assocleft, $this->assocright) VALUES(%1, %2);",
				array($oeid, getpost_single($sparams[5].'_add')));
			$res = $DBHandle->Execute ($QUERY);
			if (! $res){
				if ($this->debug_st) {
					?> Query failed: <?php echo htmlspecialchars($QUERY) ?><br>
					Error: <?php echo $DBHandle->ErrorMsg() ?><br>
					<?php
				}
				echo _("Could not add!");
			}else{
				if ($this->debug_st)
					echo _("Item added!");
			}
			break;
		case 'delete':
			$QUERY = str_dbparams($DBHandle,"DELETE FROM $this->assoctable WHERE $this->assocleft = %1 AND $this->assocright = %2;",
				array($oeid, getpost_single($sparams[5].'_del')));
			$res = $DBHandle->Execute ($QUERY);
			if (! $res){
				if ($this->debug_st) {
					?> Query failed: <?php echo htmlspecialchars($QUERY) ?><br>
					Error: <?php echo $DBHandle->ErrorMsg() ?><br>
					<?php
				}
				echo _("Could not delete!");
			}else{
				if ($this->debug_st)
					echo _("Item deleted!");
			}
			break;
		default:
			echo "Unknown action $oeaction";
		}
	}
};




?>