<?php



class InvoiceItem {
	private $description;
	private $date;
	private $price;
	private $VAT;
	private $precision = false;
	private $ext_id;
	private $ext_type;

	function __construct($id, $desc, $date, $price, $VAT,$type_ext,$id_ext=null) {
		$this->id = $id;
		$this->description = $desc;
		$this->date = $date;
		$this->price = $price;
		$this->VAT = $VAT;
		$this->ext_id = $id_ext;
		$this->ext_type = $type_ext;
	}

	function getId() {

		return $this->id;
	}

	function getExtId() {

		return $this->ext_id;
	}
	function getExtType() {

		return $this->ext_type;
	}

	function getPrice() {

		return $this->price;
	}

	function getVAT() {

		return $this->VAT;
	}
	
	function getDescription() {

		return $this->description;
	}

	function getDate() {

		return substr($this->date, 0, 10);
	}

}

