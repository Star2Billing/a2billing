<?php


class Ticket {

	private $id;
	private $title;
	private $description;
	private $creatorid;
	private $creator_type;
	private $creator_login;
	private $creator_firstname;
	private $creator_lastname;
	private $creator_language;
	private $creator_email;
	private $priority;
	private $creationdate;
	private $status;
	private $componentid;
	private $componentname;
	private $viewed_cust;
	private $viewed_agent;
	private $viewed_admin;
	private $supportbox_email;
	private $supportbox_language;
	public static $NEW = 0;
	public static $FIXED = 1;
	public static $REOPEN = 2;
	public static $CLOSED = 3;
	public static $INVALID = 4;

	function __construct($id) {

		$DBHandle = DbConnect();
		$instance_sub_table = new Table("cc_ticket", "*");
		$QUERY = " id = " . $id;
		$return = null;
		$return = $instance_sub_table->Get_list($DBHandle, $QUERY, 0);

		$value = $return[0];
		if (!is_null($value)) {
			$this->id = $value["id"];
			$this->creatorid = $value["creator"];
			$this->description = $value["description"];
			$this->priority = $value["priority"];
			$this->title = $value["title"];
			$this->status = $value["status"];
			$this->creationdate = $value["creationdate"];
			$this->componentid = $value["id_component"];
			$this->viewed_admin = $value["viewed_admin"];
			$this->viewed_agent = $value["viewed_agent"];
			$this->viewed_cust = $value["viewed_cust"];
		}

		if (!is_null($this->creatorid)) {
			$this->creator_type = $value["creator_type"];

			switch ($this->creator_type) {
				case 0:	$instance_sub_table = new Table("cc_card", "lastname, firstname,username,language,email");
					$QUERY = " id = " . $this->creatorid;
					$subreturn = null;
					$subreturn = $instance_sub_table->Get_list($DBHandle, $QUERY, 0);
					$subvalue = $subreturn[0];
					if (!is_null($subvalue)) {
						$this->creatorname= $subvalue["lastname"] . " " . $subvalue["firstname"];
					}
					$this->creator_login= $subvalue["username"];
					$this->creator_firstname=$subvalue["firstname"];
					$this->creator_lastname=$subvalue["lastname"];
					$this->creator_language=$subvalue["language"];
					$this->creator_email=$subvalue["email"];
					break;
				case 1: $instance_sub_table = new Table("cc_agent", "lastname, firstname,login,language,email");
					$QUERY = " id = " . $this->creatorid;
					$subreturn = null;
					$subreturn = $instance_sub_table->Get_list($DBHandle, $QUERY, 0);
					$subvalue = $subreturn[0];
					if (!is_null($subvalue)) {
						$this->creatorname= gettext("(AGENT)") . " " . $subvalue["firstname"] . " " . $subvalue["lastname"];
					}
					$this->creator_login= $subvalue["login"];
					$this->creator_firstname=$subvalue["firstname"];
					$this->creator_lastname=$subvalue["lastname"];
					$this->creator_language=$subvalue["language"];
					$this->creator_email=$subvalue["email"];
				    break;
			}
				
		}

		if (!is_null($this->componentid)) {
			$component_table = new Table('cc_support_component LEFT JOIN cc_support ON id_support = cc_support.id', "cc_support_component.name,email,language");
			$component_clause = "cc_support_component.id = ".$this->componentid;
			$return = null;
			$return = $component_table->Get_list($DBHandle, $component_claus);

			if (is_array($return)) {

				$this->componentname = $return[0]["name"];
				$this->supportbox_email = $return[0]["email"];
				$this->supportbox_language = $return[0]["language"];
			}
		}

	}

	function getId() {

		return $this->id;
	}

	function getTitle() {

		return $this->title;
	}

	function getDescription() {

		return $this->description;
	}

	function getCreatorid() {

		return $this->creatorid;
	}

	function getPriority() {

		return $this->priority;
	}

	function getStatus() {

		return $this->status;

	}
	//0 customer
	//1 agent
	//2 admin
	function getViewed($type) {
		switch ($type) {
			case 0 :
				return $this->viewed_cust;
			case 1 :
				return $this->viewed_agent;
			case 2 :
				return $this->viewed_admin;
			default :
				return 0;
		}
	}

	function getPriorityDisplay() {
		return Ticket::DisplayPriority($this->priority);
	}
	public static function DisplayPriority($prior) {
		switch ($prior) {
			case 1 :
				return "LOW";
			case 2 :
				return "MEDIUM";
			case 3 :
				return "HIGH";
			default :
				return "NONE";

		}
	}


	function getComponentid() {
		return $this->componentid;
	}

	function getComponentname() {
		return $this->componentname;

	}

	function getCreationdate() {

		return substr($this->creationdate, 0, 19);
	}

	function getCreatorname() {

		return $this->creatorname;
	}

	function loadComments() {
		if (!is_null($this->id)) {
			$result = array ();
			$DBHandle = DbConnect();
			$instance_sub_table = new Table("cc_ticket_comment", "*");
			$QUERY = " id_ticket = " . $this->id;
			$return = null;
			$return = $instance_sub_table->Get_list($DBHandle, $QUERY, "date", "ASC");
			$i = 0;
			foreach ($return as $value) {
				$comment = new Comment($value['id'], $value['description'], $value['date'], $value["viewed_cust"], $value["viewed_agent"], $value["viewed_admin"]);
				$creatorid = $value["creator"];
				$creator_type = $value["creator_type"];

				if (!is_null($creatorid)) {
					if ($creator_type == 1) {
						$instance_sub_table = new Table("cc_ui_authen", "*");
						$QUERY = " userid = " . $creatorid;
						$subreturn = null;
						$subreturn = $instance_sub_table->Get_list($DBHandle, $QUERY, 0);
						$subvalue = $subreturn[0];
						if (!is_null($subvalue)) {
							$comment->setCreatorname(gettext("(ADMINISTRATOR) ") . $subvalue["name"]);
						}
					}
					elseif ($creator_type == 0) {

						$instance_sub_table = new Table("cc_card", "lastname, firstname");
						$QUERY = " id = " . $creatorid;
						$subreturn = null;
						$subreturn = $instance_sub_table->Get_list($DBHandle, $QUERY, 0);
						$subvalue = $subreturn[0];
						if (!is_null($subvalue)) {
							$comment->setCreatorname($subvalue["lastname"] . " " . $subvalue["firstname"]);
						}

					} else {
						$instance_sub_table = new Table("cc_agent", "lastname, firstname");
						$QUERY = " id = " . $creatorid;
						$subreturn = null;
						$subreturn = $instance_sub_table->Get_list($DBHandle, $QUERY, 0);
						$subvalue = $subreturn[0];
						if (!is_null($subvalue)) {
							$comment->setCreatorname(gettext("(AGENT)") . " " . $subvalue["firstname"] . " " . $subvalue["lastname"]);
						}

					}

				}

				$result[$i] = $comment;
				$i++;
			}
			//sort r�sult by date
			return $result;

		} else
			return null;

	}

	function insertComment($desc, $creator, $creator_type) {

		$DBHandle = DbConnect();

		$instance_sub_table = new Table("cc_ticket_comment", "*");
		$QUERY_FIELDS = 'id_ticket, description,creator, creator_type';
		$QUERY_VALUES = "'$this->id', '$desc','$creator', '$creator_type'";

		switch ($creator_type) {
			case 0 :
				$QUERY_FIELDS .= " ,viewed_cust ";
				$QUERY_VALUES .= " ,'0'";
				break;
			case 1 :
				$QUERY_FIELDS .= " ,viewed_admin ";
				$QUERY_VALUES .= " ,'0'";
				break;
			case 2 :
				$QUERY_FIELDS .= " ,viewed_agent ";
				$QUERY_VALUES .= " ,'0'";
				break;

		}
		$return = $instance_sub_table->Add_table($DBHandle, $QUERY_VALUES, $QUERY_FIELDS, 'cc_ticket_comment', 'id');
		switch ($creator_type) {
		    case 0: $instance_sub_table = new Table("cc_card", "lastname, firstname");
			    $QUERY = " id = " . $creator;
			    $subreturn = null;
			    $subreturn = $instance_sub_table->Get_list($DBHandle, $QUERY, 0);
			    $subvalue = $subreturn[0];
			    if (!is_null($subvalue)) {
				    $owner_comment=$subvalue["lastname"] . " " . $subvalue["firstname"];
			    }
			    break;
		    case 1: $instance_sub_table = new Table("cc_ui_authen", "*");
			    $QUERY = " userid = " . $creator;
			    $subreturn = null;
			    $subreturn = $instance_sub_table->Get_list($DBHandle, $QUERY, 0);
			    $subvalue = $subreturn[0];
			    if (!is_null($subvalue)) {
				   $owner_comment="(ADMINISTRATOR) " . $subvalue["login"];
			    }
			    break;
		    case 2: $instance_sub_table = new Table("cc_agent", "lastname, firstname,login");
			    $QUERY = " id = " . $creator;
			    $subreturn = null;
			    $subreturn = $instance_sub_table->Get_list($DBHandle, $QUERY, 0);
			    $subvalue = $subreturn[0];
			    if (!is_null($subvalue)) {
				    $owner_comment="(AGENT) " .$subvalue["login"]. " - " . $subvalue["firstname"] . " " . $subvalue["lastname"];
			    }
			    break;
		}
		
		$owner = $this->creator_login." (".$this->creator_firstname." ".$this->creator_lastname.")";
		
		try {
			$mail = new Mail(Mail::$TYPE_TICKET_MODIFY, null, $this->creator_language);
			$mail->replaceInEmail(Mail::$TICKET_OWNER_KEY, $owner);
			$mail->replaceInEmail(Mail::$TICKET_NUMBER_KEY, $this->id);
			$mail->replaceInEmail(Mail::$TICKET_DESCRIPTION_KEY, $this->description);
			$mail->replaceInEmail(Mail::$TICKET_PRIORITY_KEY, Ticket::DisplayPriority($this->priority));
			$mail->replaceInEmail(Mail::$TICKET_STATUS_KEY,Ticket::getStatusDisplay($this->status));
			$mail->replaceInEmail(Mail::$TICKET_TITLE_KEY, $this->title);
			$mail->replaceInEmail(Mail::$TICKET_COMMENT_DESCRIPTION_KEY, $desc);
			$mail->replaceInEmail(Mail::$TICKET_COMMENT_CREATOR_KEY, $owner_comment);
			$mail->send($this->creator_email);
		} catch (A2bMailException $e) {
            $error_msg = $e->getMessage();
        }
        
        try {
			$mail = new Mail(Mail::$TYPE_TICKET_MODIFY, null, $this->supportbox_language);
			$mail->replaceInEmail(Mail::$TICKET_OWNER_KEY, $owner);
			$mail->replaceInEmail(Mail::$TICKET_NUMBER_KEY, $this->id);
			$mail->replaceInEmail(Mail::$TICKET_DESCRIPTION_KEY, $this->description);
			$mail->replaceInEmail(Mail::$TICKET_PRIORITY_KEY, Ticket::DisplayPriority($this->priority));
			$mail->replaceInEmail(Mail::$TICKET_STATUS_KEY,Ticket::getStatusDisplay($this->status));
			$mail->replaceInEmail(Mail::$TICKET_TITLE_KEY, $this->title);
			$mail->replaceInEmail(Mail::$TICKET_COMMENT_DESCRIPTION_KEY, $desc);
			$mail->replaceInEmail(Mail::$TICKET_COMMENT_CREATOR_KEY, $owner_comment);
			$mail->send($this->supportbox_email);
		} catch (A2bMailException $e) {
            $error_msg = $e->getMessage();
        }
	}

	public static function getStatusDisplay($status) {

		switch ($status) {
			case 0 :
				return "NEW";
			case 1 :
				return "FIXED";
			case 2 :
				return "REOPEN";
			case 3 :
				return "CLOSED";
			case 4 :
				return "INVALID";

		}

	}

	public static function getAllStatus() {
		$result = array ();
		$result[0] = "NEW";
		$result[1] = "FIXED";
		$result[2] = "REOPEN";
		$result[3] = "CLOSED";
		$result[4] = "INVALID";
		return $result;

	}

	public static function getAllStatusListView() {
		$result = array ();
		$result[0] = array (
			gettext("NEW"
		), "0");
		$result[1] = array (
			gettext("FIXED"
		), "1");
		$result[2] = array (
			gettext("REOPEN"
		), "2");
		$result[3] = array (
			gettext("CLOSED"
		), "3");
		$result[4] = array (
			gettext("INVALID"
		), "4");
		return $result;

	}

	public static function getPossibleStatus($initialStatus, $isadmin) {
		$result = array ();
		$result[0] = array ();
		$result[0]["id"] = $initialStatus;
		$result[0]["name"] = Ticket :: getStatusDisplay($initialStatus);

		switch ($initialStatus) {
			//NEW
			case 0 :
				//REOPEN
			case 2 :
				if ($isadmin) {
					$result[1] = array ();
					$result[1]["id"] = Ticket :: $FIXED;
					$result[1]["name"] = "FIXED";
					$result[2] = array ();
					$result[2]["id"] = Ticket :: $INVALID;
					$result[2]["name"] = "INVALID";
				} else {
					$result[1] = array ();
					$result[1]["id"] = Ticket :: $CLOSED;
					$result[1]["name"] = "CLOSED";
				}
				return $result;
				// FIXED
			case 1 :

				$result[1] = array ();
				$result[1]["id"] = Ticket :: $REOPEN;
				$result[1]["name"] = "REOPEN";

				// CLOSED .... ADD CLOSED BEHAVIOR TO FIXED BEHAVIOR
			case 3 :
				if (!$isadmin) {
					$result[1] = array ();
					$result[1]["id"] = Ticket :: $CLOSED;
					$result[1]["name"] = "CLOSED";
				}
				return $result;
			case 4 :
				if ($isadmin) {
					$result[1] = array ();
					$result[1]["id"] = Ticket :: $REOPEN;
					$result[1]["name"] = "REOPEN";
					$result[2] = array ();
					$result[2]["id"] = Ticket :: $FIXED;
					$result[2]["name"] = "FIXED";
				}
				return $result;
			default :
				return $result;

		}

	}

}

