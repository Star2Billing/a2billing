<?php
class Comment
{
   private $description;
   private $creationdate;
   private $creatorname;
   function __construct($desc,$date){
   	$this->description = $desc;
   	$this->creationdate = $date;


   }

   function getDescription(){

   	 return $this->description;
   }


   function getCreationdate(){

   	 return $this->creationdate;
   }

   function setCreatorname($creatorname){

   	  $this->creatorname = $creatorname;
   }

	function getCreatorname(){

   	  return $this->creatorname;
   }



}
?>
