<?php
class InvoiceItem
{
   private $description;
   private $date;
   private $price;
   private $VAT;
   
   
   function __construct($id,$desc,$date,$price,$VAT){
   	$this->id = $id;
   	$this->description = $desc;
   	$this->date = $date;
	$this->price =  $price;
	$this->VAT =  $VAT;

   }

  function getId(){

   	 return $this->id;
   }
   
	function getPrice(){

   	 return $this->price;
   }
   
	function getVAT(){

   	 return $this->VAT;
   }
   function getDescription(){

   	 return $this->description;
   }


   function getDate(){

   	 return substr($this->date,0,10);
   }




}
?>
