<?php



class Comment {
	private $description;
	private $creationdate;
	private $creatorname;
	private $viewed_cust;
	private $viewed_agent;
	private $viewed_admin;

	function __construct($id, $desc, $date, $viewed_cust, $viewed_agent, $viewed_admin) {
		$this->id = $id;
		$this->description = $desc;
		$this->creationdate = $date;
		$this->viewed_cust = $viewed_cust;
		$this->viewed_agent = $viewed_agent;
		$this->viewed_admin = $viewed_admin;

	}

	function getId() {

		return $this->id;
	}

	function getDescription() {

		return $this->description;
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

	function getCreationdate() {

		return substr($this->creationdate, 0, 19);
	}

	function setCreatorname($creatorname) {

		$this->creatorname = $creatorname;
	}

	function getCreatorname() {

		return $this->creatorname;
	}

}