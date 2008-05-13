<?php
class Ticket
{

   private $id;
   private $title;
   private $description;
   private $creatorid;
   private $priority;
   private $creationdate;
   private $status;
   private $componentid;
   private $componentname;



   public static $NEW = 0;
   public static $FIXED =1;
   public static $REOPEN =2;
   public static $CLOSED =3;
   public static $INVALID =4;

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
        	$this->componentid = $value["id_component"];
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

     if(!is_null($this->componentid)){
   	 $instance_comp_table = new Table("cc_support_component", "name");
	 $QUERY = " id = ".$this->componentid;
	 $return = null;
     $return = $instance_comp_table -> Get_list($DBHandle, $QUERY, 0);
     $value = $return[0];

        if(!is_null($value)){
        	$this->componentname = $value["name"];
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

   function getComponentid(){
   		 return $this->componentid;
   }

   function getComponentname(){
   		 return $this->componentname;


   }

   function getCreationdate(){

   	 return substr($this->creationdate,0,19);
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
		        	$comment->setCreatorname( gettext("(ADMINISTRATOR) ").$subvalue["name"]);
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
   	 if (DB_TYPE == "postgres") {
		 $QUERY_FIELDS = 'id_ticket, description,creator, is_admin';
	 	 $QUERY_VALUES = "'$this->id', '$desc','$creator', '$isadmin'";
	}else{
		$QUERY_FIELDS = 'id_ticket, description,creator, is_admin , date';
		$QUERY_VALUES = "'$this->id', '$desc','$creator', '$isadmin', now() ";
	}
	 $return = $instance_sub_table-> Add_table ($DBHandle, $QUERY_VALUES, $QUERY_FIELDS, 'cc_ticket_comment', 'id');



   }

   public static function getStatusDisplay($status){

		switch($status){
			case 0: return "NEW";
			case 1: return "FIXED";
			case 2: return "REOPEN";
			case 3: return "CLOSED";
			case 4: return "INVALID";

		}

   }

   public static function getAllStatus(){
   	$result = array();
   	$result[0] = "NEW";
   	$result[1] = "FIXED";
   	$result[2] = "REOPEN";
   	$result[3] = "CLOSED";
   	$result[4] = "INVALID";
   	return $result;

   }

    public static function getAllStatusListView(){
   	$result = array();
   	$result[0] = array( gettext("NEW"), "0");
   	$result[1] = array( gettext("FIXED"), "1");
   	$result[2] = array( gettext("REOPEN"), "2");
   	$result[3] = array( gettext("CLOSED"), "3");
   	$result[4] = array( gettext("INVALID"), "4");
   	return $result;

   }

	 public static function getPossibleStatus($initialStatus,$isadmin){
   	$result = array();
   	$result[0] = array();
   	$result[0]["id"]=$initialStatus;
   	$result[0]["name"]=	Ticket::getStatusDisplay($initialStatus);

   	switch($initialStatus){
   			//NEW
   			case 0:
			//REOPEN
			case 2: if($isadmin){
					$result[1] = array();
					$result[1]["id"]= Ticket::$FIXED;
   					$result[1]["name"]=	"FIXED";
   					$result[2] = array();
					$result[2]["id"]= Ticket::$INVALID;
   					$result[2]["name"]=	"INVALID";
			}else {
				$result[1] = array();
				$result[1]["id"]= Ticket::$CLOSED;
   				$result[1]["name"]=	"CLOSED";
			}
				return $result;
			// FIXED
			case 1:

				$result[1] = array();
				$result[1]["id"]= Ticket::$REOPEN;
				$result[1]["name"]=	"REOPEN";

			// CLOSED .... ADD CLOSED BEHAVIOR TO FIXED BEHAVIOR
			case 3: if(!$isadmin){
						$result[1] = array();
						$result[1]["id"]= Ticket::$CLOSED;
		   				$result[1]["name"]=	"CLOSED";
					}
					return $result;
			case 4:if($isadmin){
   					$result[1] = array();
					$result[1]["id"]= Ticket::$REOPEN;
   					$result[1]["name"]=	"REOPEN";
					$result[2] = array();
					$result[2]["id"]= Ticket::$FIXED;
   					$result[2]["name"]=	"FIXED";
			}
					return $result;
			default : return $result;

		}


   }


}
?>
