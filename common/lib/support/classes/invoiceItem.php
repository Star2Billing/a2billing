<?php



class InvoiceItem {
	private $description;
	private $date;
	private $price;
	private $VAT;
	private $precision = false;

	function __construct($id, $desc, $date, $price, $VAT, $precision = false) {
		$this->id = $id;
		$this->description = $desc;
		$this->date = $date;
		$this->price = $price;
		$this->VAT = $VAT;
		$this->precision = $precision;

	}

	function getId() {

		return $this->id;
	}

	function getPrecision() {

		return $this->precision;
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

