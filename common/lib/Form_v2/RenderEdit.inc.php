<?php
     /* This is the implementation of function FormHandler::RenderEdit()
     */

	// For convenience, ref the dbhandle locally
	$dbhandle = &$this->a2billing->DBHandle();
?>
<style>
table.editForm {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	font-weight: bold;
	width: 90%;
}
table.editForm thead {
	text-transform: uppercase;
	color: #FFFFFF;
	background-color: #7a7a7a;
}
table.editForm thead .field {
	width: 25%;
}
table.editForm thead .value {
	width: 75%;
}

table.editForm tbody .field {
	text-transform: uppercase;
	color: #FFFFFF;
	background-color: #9a9a9a;
}
table.editForm div.descr {
	font-size: 9px;
	font-weight: normal;
}
</style>

<?php
	if ($this->FG_DEBUG>3)
		echo "List! Building query..";
		
	
	$query_fields = array();
	$query_clauses = array();
	foreach($this->model as $fld){
		$tmp= $fld->editQueryField($dbhandle);
		if ( is_string($tmp))
			$query_fields[] = $tmp;
		
		$tmp= $fld->editQueryClause($dbhandle,$this);
		if ( is_string($tmp))
			$query_clauses[] = $tmp;
	}
	
	if ($this->model_table == null){
		if ($this->FG_DEBUG>0)
			echo "No table!\n";
		return;
	}
	
	$QUERY = 'SELECT ';
	if (count($query_fields)==0) {
		if ($this->FG_DEBUG>0)
			echo "No query fields!\n";
		return;
	}
	
	$QUERY .= implode(', ', $query_fields);
	$QUERY .= ' FROM ' . $this->model_table;
	
	if (count($query_clauses))
		$QUERY .= ' WHERE ' . implode(' AND ', $query_clauses);
	
	$QUERY .= ' LIMIT 1;'; // we can only edit one record at a time!
	
	if ($this->FG_DEBUG>3)
		echo "QUERY: $QUERY\n<br>\n";
	
	// Perform the query
	$res =$dbhandle->Execute($QUERY);
	if (! $res){
		if ($this->FG_DEBUG>0)
			echo "Query Failed: ". nl2br(htmlspecialchars($dbhandle->ErrorMsg()));
		return;
	}
	
	if ($res->EOF) /*&& cur_page==0) */ {
		if ($this->edit_no_records)
			echo $edit_no_records;
		else echo str_params(_("No %1 found!"),array($this->model_name_s),1);
	} else {
		// do the table..
		$row=$res->fetchRow();
		?>
	<form action=<?php echo $_SERVER['PHP_SELF']?> method=post name="<?php echo $this->prefix?>Frm" id="<?php echo $this->prefix ?>Frm">
	<?php $this->gen_PostParams(array( action => 'edit', sub_action => ''),true); ?>
	<table class="editForm" cellspacing="2">
	<thead><tr><td class="field">&nbsp;</td><td class="value">&nbsp;</td></tr>
	</thead>
	<tbody>
	<?php
		foreach($this->model as $fld)
			if ($fld && $fld->does_edit){
		?><tr><td class="field"><?php
				$fld->RenderEditTitle($this);
		?></td><td class="value"><?php
				$fld->DispEdit($row,$this);
		?></td></tr>
		<?php
			}
	?>
	</tbody>
	</table> </form>
	<?php
	}
//eof
?>