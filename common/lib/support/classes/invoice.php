<?php
class Invoice
{

	private $id;
	private $title;
	private $description;
	private $date;
	private $status;
	private $paid_status;
	private $id_card;
	private $username;
	private $reference;



	function __construct($id){

   	 $DBHandle  = DbConnect();
   	 $instance_sub_table = new Table("cc_invoice", "*");
	 $QUERY = " id = ".$id;
	 $return = null;
     $return = $instance_sub_table -> Get_list($DBHandle, $QUERY, 0);

     $value = $return[0];
        if(!is_null($value)){
        	$this->id = $value["id"];
        	$this->card =$value["id_card"];
        	$this->description =$value["description"];
        	$this->title =$value["title"];
        	$this->status =$value["status"];
        	$this->paid_status =$value["paid_status"];
        	$this->date = $value["date"];
        	$this->reference = $value["reference"];
        }

     if(!is_null($this->card)){
   	 $instance_sub_table = new Table("cc_card", "lastname, firstname,username");
	 $QUERY = " id = ".$this->card;
	 $return = null;
     $return = $instance_sub_table -> Get_list($DBHandle, $QUERY, 0);
     $value = $return[0];

        if(!is_null($value)){
        	$this->username = $value["lastname"]." ".$value["firstname"]." "."(".$value["username"].")";
        }
     }


   }

   function getId(){

   	 return $this->id;
   }

 function getReference(){

   	 return $this->reference;
   }

   function getTitle(){

   	 return $this->title;
   }

    function getDescription(){

   	 return $this->description;
   }

    function getCard(){

   	 return $this->card;
   }

    function getPriority(){

   	 return $this->priority;
   }

   function getStatus(){

   	 return $this->status;

   }
	function getPaidStatus(){

   	 return $this->paid_status;

   }
   
   	//0 customer
   	//1 agent
   	//2 admin
	function getViewed($type){
		switch ($type) {
			case 0: return $this ->viewed_cust;
			case 1: return $this ->viewed_agent;
			case 2: return $this ->viewed_admin;
			default: return 0; 
		}
	}
   
    function getPriorityDisplay(){

		switch($this->priority){
			case 1: return "LOW";
			case 2: return "MEDIUM";
			case 3: return "HIGH";
			default : return "NONE";

		}

   }

   function getComponentid(){
   		 return $this->componentid;
   }

   function getComponentname(){
   		 return $this->componentname;


   }

   function getDate(){

   	 return substr($this->date,0,10);
   }

   function getUsernames(){

   	 return $this->username;
   }

   function loadItems(){
	if(!is_null($this->id)){
	 $result= array();
	 $DBHandle  = DbConnect();
   	 $instance_sub_table = new Table("cc_invoice_item", "*");
	 $QUERY = " id_invoice = ".$this->id;
	 $return = null;
     $return = $instance_sub_table -> Get_list($DBHandle, $QUERY,"date","ASC");
     $i = 0;
 	foreach ($return as $value)
      { $comment = new InvoiceItem($value['id'],$value['description'],$value['date'],$value["price"],$value["VAT"]);
    	$result[$i]= $comment;
    	$i++;
      }
      //sort r�sult by date
     return $result;

	}else return null;

   }

   function insertInvoiceItem($desc,$price,$VAT){

   	$DBHandle  = DbConnect();
   	$instance_sub_table = new Table("cc_invoice_item", "*");
	$QUERY_FIELDS = 'id_invoice, description,price, VAT';
	$QUERY_VALUES = "'$this->id', '$desc','$price', '$VAT'";
	$return = $instance_sub_table-> Add_table ($DBHandle, $QUERY_VALUES, $QUERY_FIELDS, 'cc_invoice_item', 'id');

   }

   public static function getStatusDisplay($status){

		switch($status){
			case 0: return "OPEN";
			case 1: return "CLOSE";

		}

   }
   
	public static function getPaidStatusDisplay($status){

		switch($status){
			case 0: return "UNPAID";
			case 1: return "PAID";

		}

   }

  


}
?>
