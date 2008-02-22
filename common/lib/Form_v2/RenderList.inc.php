<?php
     /* This is the implementation of function FormHandler::RenderList()
     */

	// For convenience, ref the dbhandle locally
	$dbhandle = &$this->a2billing->DBHandle();
?>
<style>
table.cclist {
	width: 95%;
	border-bottom: #ffab12 0px solid; 
	border-left: #e1e1e1 0px solid; 
	border-right: #e1e1e1 1px solid; 
	border-top: #e1e1e1 0px solid; 
	padding-bottom: 4px; 
	padding-left: 4px; 
	padding-right: 4px; 
	padding-top: 4px;	
	font-size: 10px;
}
.cclist thead {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	font-weight: bold;
	text-transform: uppercase;
	color: #FFFFFF;
	background-color: #7a7a7a;
}
.cclist thead a{
	color: #FFFFFF;
}
.cclist thead a:hover{
	color: #FFFFFF;
}

table.cclist tbody tr{
	background-color: #F2F2F2;
}

table.cclist tbody .odd{
	background-color: #E0E0E0;
}

table.cclist tbody tr:hover {
	background-color: #FFDEA6;
}
</style>
<?php
	if ($this->FG_DEBUG>3)
		echo "List! Building query..";
		
	
	$query_fields = array();
	$query_clauses = array();
	foreach($this->model as $fld){
		$tmp= $fld->listQueryField($dbhandle);
		if ( is_string($tmp))
			$query_fields[] = $tmp;
		
		$tmp= $fld->listQueryClause($dbhandle);
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
	
	$QUERY .= ';';
	
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
		if ($this->list_no_records)
			echo $list_no_records;
		else echo str_params(_("No %1 found!"),array($this->model_name_s),1);
	} else {
		// now, DO render the table!
		?>
	<TABLE cellPadding="2" cellSpacing="2" align='center' class="<?php echo $this->list_class?>">
		<thead><tr>
		<?php
		foreach ($this->model as $fld)
			if ($fld) $fld->RenderListHead($this);
		?>
		</tr></thead>
		<tbody>
		<?php
		$row_num = 0;
		while ($row = $res->fetchRow()){
			if ($this->FG_DEBUG > 4) {
				echo '<tr><td colspan = 3>';
				print_r($row);
				echo '</td></tr>';
			}
			if ($row_num % 2)
				echo '<tr class="odd">';
			else	echo '<tr>';
			
			foreach ($this->model as $fld)
				if ($fld) $fld->RenderListCell($row,$this);
			echo "</tr>\n";
			$row_num++;
		}
		for(;$row_num < $this->list_least_rows; $row_num++)
			if ($row_num % 2)
				echo '<tr class="odd"></tr>';
			else	echo '<tr></tr>';
		?>
		</tbody>
	</table>
	<?php
	} // query table
	
?>