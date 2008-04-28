<?php
include ("../lib/support/classes/comment.php");
class Ticket
{
   private $id;
   private $title;
   private $description;
   private $creatorid;
   private $priority;
   private $creationdate;
   private $status;

	function __construct($id){

   	 $DBHandle  = DbConnect();
   	 $instance_sub_table = new Table("cc_ticket", "*");
	 $QUERY = " id = ".$id;
	 $return = null;
     $return = $instance_sub_table -> Get_list($DBHandle, $QUERY, 0);

     $value = $return[0];
        if(!is_null($value)){
        	$this->id = $value["id"];
        	$this->creatorid =$value["creator"];
        	$this->description =$value["description"];
        	$this->priority =$value["priority"];
        	$this->title =$value["title"];
        	$this->status =$value["status"];
        	$this->creationdate = $value["creationdate"];
        }

     if(!is_null($this->creatorid)){
   	 $instance_sub_table = new Table("cc_card", "lastname, firstname");
	 $QUERY = " id = ".$this->creatorid;
	 $return = null;
     $return = $instance_sub_table -> Get_list($DBHandle, $QUERY, 0);
     $value = $return[0];

        if(!is_null($value)){
        	$this->creatorname = $value["lastname"]." ".$value["firstname"];
        }
     }


   }

   function getId(){

   	 return $this->id;
   }


   function getTitle(){

   	 return $this->title;
   }

    function getDescription(){

   	 return $this->description;
   }

    function getCreatorid(){

   	 return $this->creatorid;
   }

    function getPriority(){

   	 return $this->priority;
   }

   function getStatus(){

   	 return $this->status;
   }

    function getPriorityDisplay(){

		switch($this->priority){
			case 1: return "LOW";
			case 2: return "MEDIUM";
			case 3: return "HIGH";
			default : return "NONE";

		}

   }

   function getCreationdate(){

   	 return $this->creationdate;
   }

   function getCreatorname(){

   	 return $this->creatorname;
   }

   function loadComments(){
	if(!is_null($this->id)){
	 $result= array();
	 $DBHandle  = DbConnect();
   	 $instance_sub_table = new Table("cc_ticket_comment", "*");
	 $QUERY = " id_ticket = ".$this->id;
	 $return = null;
     $return = $instance_sub_table -> Get_list($DBHandle, $QUERY,"date","ASC");
     $i = 0;
 	foreach ($return as $value)
      { $comment = new Comment($value['description'],$value['date']);
      	$creatorid =$value["creator"];
        $isadmin = $value["is_admin"]=="f"?false:true;

      	 if(!is_null($creatorid)){
        	if($isadmin){
				 $instance_sub_table = new Table("cc_ui_authen", "*");
		 		 $QUERY = " userid = ".$creatorid;
		 		 $subreturn = null;
	     		 $subreturn = $instance_sub_table -> Get_list($DBHandle, $QUERY, 0);
	     		 $subvalue = $subreturn[0];
		        if(!is_null($subvalue)){
		        	$comment->setCreatorname( $subvalue["name"]);
		        }
        	}else{


	   			 $instance_sub_table = new Table("cc_card", "lastname, firstname");
		 		 $QUERY = " id = ".$creatorid;
		 		 $subreturn = null;
	     		 $subreturn = $instance_sub_table -> Get_list($DBHandle, $QUERY, 0);
	     		 $subvalue = $subreturn[0];
		        if(!is_null($subvalue)){
		        	$comment->setCreatorname( $subvalue["lastname"]." ".$subvalue["firstname"]);
		        }

        	}

	     }


    	$result[$i]= $comment;
    	$i++;
      }
      //sort rŽsult by date
     return $result;

	}else return null;

   }

   function insertComment($desc,$creator,$isadmin){

   	$DBHandle  = DbConnect();
   	 $instance_sub_table = new Table("cc_ticket_comment", "*");
   	 $QUERY_FIELDS = 'id_ticket, description,creator, is_admin';
	 $QUERY_VALUES = "'$this->id', '$desc','$creator', '$isadmin'";
	 $return = $instance_sub_table-> Add_table ($DBHandle, $QUERY_VALUES, $QUERY_FIELDS, 'cc_ticket_comment', 'id');



   }


}
?>
