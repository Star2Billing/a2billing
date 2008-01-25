<?php
require_once("Class.BaseField.inc.php");

	/** Field class for primary key (scalar)
	*/

class PKeyField extends BaseField {

	function PKeyField($fldtitle, $fldname,$fldwidth = null){
		$this->does_edit = false;
		$this->fieldname = $fldname;
		$this->fieldtitle = $fldtitle;
		$this->listWidth = $fldwidth;
	}

	public function DispList(array &$qrow,&$form){
		echo htmlspecialchars($qrow[$this->fieldname]);
	}

	/// Reimplement: the key may not be listed, but is always queried
	public function listQueryField(&$dbhandle){
		if ($this->fieldexpr)
			return $this->fieldexpr ." AS ". $this->fieldname;
		return $this->fieldname;
	}
	
	public function editQueryClause(&$dbhandle,&$form){
		return str_dbparams($dbhandle,
			"$this->fieldname = %#1",array($form->getpost_dirty($this->fieldname)));
	}


};

/** Also hyperlink to the Edit page
    \note This will NOT pass itself as the primary key field, but will ask
    the form to do so. This way, the form will always decide which key to 
    use. */
class PKeyFieldEH extends PKeyField{
	
	public function DispList(array &$qrow,&$form){
		echo '<a href="'. $form->askeditURL($qrow) . '">';
		echo htmlspecialchars($qrow[$this->fieldname]);
		echo '</a>';
	}
	
};

?>