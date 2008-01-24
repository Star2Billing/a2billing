<?php

	// Option fields.. Delete, edit buttons etc
require_once("Class.BaseField.inc.php");

abstract class OptionField extends BaseField {
	function OptionField(){
		$this->does_list_sort=false;
		$this->does_edit=false;
		$this->does_add=false;
		$this->does_del=false;
		$this->fieldtitle=_("Actions");
	}
	
	public function listQueryField(&$dbhandle){
		return null;
	}
};

class GroupField extends OptionField {
	public $items=array();
	function GroupField($its){
		$this->OptionField();
		$this->does_edit=true;
		$this->does_add=true;
		$this->does_del=true;
		if (is_array($its))
			$this->items = $its;
		else
			$this->items[] = $its;
	}
	
	public function DispList(array &$qrow,&$form){
		foreach ($this->items as $it)
			$it->DispList($qrow,$form);
	}
};

class EditBtnField extends OptionField {
	public $message = null;
	public $img = './Images/icon-edit.png';
	
	public function DispList(array &$qrow,&$form){
		if ($this->message)
			$msg=$this->message;
		else
			$msg=str_params(_("Edit this %1"),array($form->model_name_s),1);
			
		echo '&nbsp;<a href="'. $form->askeditURL($qrow) . '" title="'.$msg .'">';
		echo '<img src="'.$this->img.'" border="0" alt="'. $msg. '">';
		echo '</a>';
	}
};

class DelBtnField extends OptionField {
	public $message = null;
	public $img = './Images/icon-del.png';
	
	public function DispList(array &$qrow,&$form){
		if ($this->message)
			$msg=$this->message;
		else
			$msg=str_params(_("Delete this %1"),array($form->model_name_s),1);
		
		$mod_pk= $form->getModelPK();
		$url= $_SERVER['PHP_SELF'].'?'.
			$form->prefix.'action=ask-del&'.
			$form->prefix.$mod_pk->fieldname.'='.rawurlencode($qrow[$mod_pk->fieldname]);
	
		echo '&nbsp;<a href="'. $url . '" title="'.$msg .'">';
		echo '<img src="'.$this->img.'" border="0" alt="'. $msg. '">';
		echo '</a>';
	}
};

?>