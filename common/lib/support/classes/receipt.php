<?php


class Receipt {

	private $id;
	private $title;
	private $description;
	private $status;
	private $card;
	private $username;

	function __construct($id) {

		$DBHandle = DbConnect();
		$instance_sub_table = new Table("cc_receipt", "*");
		$QUERY = " id = " . $id;
		$return = null;
		$return = $instance_sub_table->Get_list($DBHandle, $QUERY, 0);

		$value = $return[0];
		if (!is_null($value)) {
			$this->id = $value["id"];
			$this->card = $value["id_card"];
			$this->description = $value["description"];
			$this->title = $value["title"];
			$this->status = $value["status"];
			$this->date = $value["date"];
		}

		if (!is_null($this->card)) {
			$instance_sub_table = new Table("cc_card", "lastname, firstname,username");
			$QUERY = " id = " . $this->card;
			$return = null;
			$return = $instance_sub_table->Get_list($DBHandle, $QUERY, 0);
			$value = $return[0];

			if (!is_null($value)) {
				$this->username = $value["lastname"] . " " . $value["firstname"] . " " . "(" . $value["username"] . ")";
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

	function getCard() {

		return $this->card;
	}

	function getStatus() {

		return $this->status;

	}

	function getDate() {

		return substr($this->date, 0, 10);
	}

	function getUsernames() {

		return $this->username;
	}

	function loadItems() {
		if (!is_null($this->id)) {
			$result = array ();
			$DBHandle = DbConnect();
			$instance_sub_table = new Table("cc_receipt_item", "*");
			$QUERY = " id_receipt = " . $this->id;
			$return = null;
			$return = $instance_sub_table->Get_list($DBHandle, $QUERY, "date", "ASC");
			$i = 0;
			foreach ($return as $value) {
				$comment = new ReceiptItem($value['id'], $value['description'], $value['date'], $value["price"],$value["type_ext"],$value["id_ext"]);
				$result[$i] = $comment;
				$i++;
			}
			//sort r�sult by date
			return $result;

		} else
			return null;

	}

	function loadDetailledItems($begin=null,$nb=null) {
		if (!is_null($this->id)) {
			$result = array ();
			$DBHandle = DbConnect();
			$instance_sub_table = new Table("cc_receipt_item", "*");
			$QUERY = " id_receipt = " . $this->id;
			$return = null;
			$return = $instance_sub_table->Get_list($DBHandle, $QUERY, "date", "ASC");
			$i = 0;
			foreach ($return as $value) {
				if ($value['id_ext'] && $value['type_ext'] == "CALLS") {

					$billing_table = new Table("cc_billing_customer", "date,start_date");
					$billing_clause = "id = " . $value['id_ext'];
					$result_billing = $billing_table->Get_list($DBHandle, $billing_clause);
					if (is_array($result_billing) && !empty ($result_billing[0]['date'])) {
						$call_table = new Table("cc_call", "*");
						$call_clause = " card_id = " . $this->card . " AND stoptime< '" . $result_billing[0]['date'] . "'";
						if (!empty ($result_billing[0]['start_date'])) {
							$call_clause .= " AND stoptime >= '" . $result_billing[0]['start_date'] . "'";
						}
						$return_calls = $call_table->Get_list($DBHandle, $call_clause,'starttime','ASC',null,null,$nb,$begin);
						foreach ($return_calls as $call) {
							$min = floor($call['sessiontime'] / 60);
							$sec = $call['sessiontime'] % 60;
							$item = new ReceiptItem(null, "CALL : " . $call['calledstation'] . " DURATION : " . $min . " min " . $sec . " sec", $call['starttime'], $call["sessionbill"], $value["VAT"], true);
							$result[$i] = $item;
							$i++;
						}
					}
				} else {
					$item = new ReceiptItem($value['id'], $value['description'], $value['date'], $value["price"], $value["VAT"],$value["type_ext"],$value["id_ext"]);
					$result[$i] = $item;
					$i++;
				}
			}
			//sort r�sult by date
			return $result;

		} else
			return null;

	}
        function nbDetailledItems() {
		if (!is_null($this->id)) {
			$result = array ();
			$DBHandle = DbConnect();
			$instance_sub_table = new Table("cc_receipt_item", "*");
			$QUERY = " id_receipt = " . $this->id;
			$return = null;
			$return = $instance_sub_table->Get_list($DBHandle, $QUERY, "date", "ASC");
			$i = 0;
			foreach ($return as $value) {
				if ($value['id_ext'] && $value['type_ext'] == "CALLS") {

					$billing_table = new Table("cc_billing_customer", "date,start_date");
					$billing_clause = "id = " . $value['id_ext'];
					$result_billing = $billing_table->Get_list($DBHandle, $billing_clause);
					if (is_array($result_billing) && !empty ($result_billing[0]['date'])) {
						$call_table = new Table("cc_call", "COUNT(*)");
						$call_clause = " card_id = " . $this->card . " AND stoptime< '" . $result_billing[0]['date'] . "'";
						if (!empty ($result_billing[0]['start_date'])) {
							$call_clause .= " AND stoptime >= '" . $result_billing[0]['start_date'] . "'";
						}
						$return_calls = $call_table->Get_list($DBHandle, $call_clause,'starttime','ASC');
						if(is_array($return_calls))$i=$i+$return_calls[0][0];

					}
				} else {
					$i++;
				}
			}
			return $i;

		} else
			return 0;

	}

	 function SumItemsPrice() {
		if (!is_null($this->id)) {
			$result = array ();
			$DBHandle = DbConnect();
			$instance_sub_table = new Table("cc_receipt_item", "SUM(price)");
			$QUERY = " id_receipt = " . $this->id;
			$return = null;
			$return = $instance_sub_table->Get_list($DBHandle, $QUERY, "date", "ASC");
			if(empty ($return)||!is_array($return)||empty ($return[0][0]))
				return 0;
			else
				return $return[0][0];
		} else {
			return 0;
		}
	}

	function insertReceiptItem($desc, $price) {

		$DBHandle = DbConnect();
		$instance_sub_table = new Table("cc_receipt_item", "*");
		$QUERY_FIELDS = 'id_receipt, description,price';
		$QUERY_VALUES = "'$this->id', '$desc','$price'";
		$return = $instance_sub_table->Add_table($DBHandle, $QUERY_VALUES, $QUERY_FIELDS, 'cc_receipt_item', 'id');

	}

	public static function getStatusDisplay($status) {

		switch ($status) {
			case 0 :
				return "OPEN";
			case 1 :
				return "CLOSE";

		}

	}


}


