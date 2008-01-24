<?php
	/** skeleton, abstract class for Model fields
	    Some functions are provided, empty, for convenience.
	*/

abstract class BaseField {
	public $fieldname;
	public $fieldexpr = null; ///< if set, use this expression in select
	public $fieldtitle; ///< The user-visible title of the field.
	public $fieldacr = null;   ///< An acronym, to be used in list table
	
	public $does_list = true; ///< Field will appear in list view
	public $does_list_sort = true; ///< Field will be sortable in list view
	public $does_edit = true; ///< Field will appear in edit view
	public $does_add = false; ///< Field will appear in add view
	public $does_del = false; ///< Field will appear in del view
	
	public $listWidth = null;
	public $editDescr = null;
	
	//public var $

	/** Produce the necessary html code at the body.
	    Useful for styles, scripts etc.
	    @param $action	The form action. Sometimes, the header is only needed 
	    		for edits etc.
	   */
	public function html_body($action = null){
	}

	/** Display the field inside the list table.
	   \param $qrow An array(fieldname=> val, ...) resulting from the sql query
	   \param $form The form
	   */
	abstract public function DispList(array &$qrow,&$form);
	
	/** Editing may be skipped, by default */
	public function DispEdit(array &$qrow,&$form){
		$this->DispAddEdit($qrow[$this->fieldname],$form);
	}
	
	public function DispAdd(array &$qrow,&$form){
		$this->DispAddEdit('',$form);
	}

	/** Alternatively, a field can have a common method for both
	    add and edit actions.
	    \param $val the value of the field
	    */
	public function DispAddEdit(&$val,&$form){
		//stub!
	}
	
	/** query expression */
	public function listQueryField(&$dbhandle){
		if (!$this->does_list)
			return;
		if ($this->fieldexpr)
			return $this->fieldexpr ." AS ". $this->fieldname;
		return $this->fieldname;
	}
	
	public function editQueryField(&$dbhandle){
		if (!$this->does_edit)
			return;
		if ($this->fieldexpr)
			return $this->fieldexpr ." AS ". $this->fieldname;
		return $this->fieldname;
	}

	/** Add this clause to the query */
	public function listQueryClause(&$dbhandle){
		return null;
	}
	
	public function editQueryClause(&$dbhandle,&$form){
		return null;
	}
	
	public function delQueryClause(&$dbhandle,&$form){
		return editQueryClause($dbhandle,$form);
	}
	
	public function addQueryClause(&$dbhandle,&$form){
		return null;
	}



	/** Render the List head cell (together with 'td' element) */
	function RenderListHead(&$form){
		if (!$this->does_list)
			return;
		echo "<td";
		if ($this->listWidth)
			echo ' width="'.$this->listWidth .'"';
		echo '>';
		
		if ($this->does_list_sort)
			$this->RenderListHead_sort($form);
		else
			$this->RenderListHead_i($form);
		echo "</td>\n";
	}
	
	protected function RenderListHead_sort(&$form){
		$sens = $form->sens;
		if (!$sens) $sens = 'asc';
		
		$order_sel = false;
		if ($form->order == $this->fieldname) {
			if ($sens == 'asc')
				$sens = 'desc';
			else
				$sens = 'asc';
			$order_sel = true;
		}
		echo '<a href="';
		echo $form->selfUrl(array( order=> $this->fieldname, sens=>$sens));
		echo '">';
		$this->RenderListHead_i($form);
		if ($order_sel) {
			if($sens == 'asc')
				echo '&nbsp;<img src="./Images/icon_up_12x12.png" border="0">';
			else
				echo '&nbsp;<img src="./Images/icon_down_12x12.png" border="0">';
		}
		echo '</a>';
	}
	
	protected function RenderListHead_i($form){
		if ($this->fieldacr){
			echo '<acronym title="'.htmlspecialchars($this->fieldtitle).'" >';
			echo htmlspecialchars($this->fieldacr);
			echo '<acronym>';
			
		}else
			echo htmlspecialchars($this->fieldtitle);
	}
	
	public function RenderListCell(array &$qrow,&$form){
		if (!$this->does_list)
			return;
		echo "<td>";
		$this->DispList($qrow,$form);
		echo "</td>";
	}
	
	public function RenderEditTitle(&$form){
		echo htmlspecialchars($this->fieldtitle);
	}
	
};

?>