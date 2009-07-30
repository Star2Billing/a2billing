<?php

class A2bMailException extends Exception {
}

class Mail {

	private $id_card;
	private $message = '';
	private $title = '';
	private $from_email = '';
	private $from_name = '';
	private $to_email = '';
	//mail type
	static public $TYPE_PAYMENT = 'payment';
	static public $TYPE_REMINDER = 'reminder';
	static public $TYPE_SIGNUP = 'signup';
	static public $TYPE_FORGETPASSWORD = 'forgetpassword';
	static public $TYPE_SIGNUPCONFIRM = 'signupconfirmed';
	static public $TYPE_EPAYMENTVERIFY = 'epaymentverify';
	static public $TYPE_INVOICE = 'invoice';
	static public $TYPE_REMINDERCALL = 'remindercall';

	//Used by mail type = epaymentverify
	static public $TIME_KEY = '$time$';
	static public $PAYMENTGATEWAY_KEY = '$paymentgateway$';
	//Used by mail type = payment
	static public $ITEM_NAME_KEY = '$itemName$';
	static public $ITEM_ID_KEY = '$itemID$';
	static public $PAYMENT_METHOD_KEY = '$paymentMethod$';
	static public $PAYMENT_STATUS_KEY = '$paymentStatus$';
	//used by type = payment and type = epaymentverify
	static public $ITEM_AMOUNT_KEY = '$itemAmount$';
	//used in all mail
	static public $CUSTOMER_EMAIL_KEY = '$email$';
	static public $CUSTOMER_FIRSTNAME_KEY = '$firstname$';
	static public $CUSTOMER_LASTNAME_KEY = '$lastname$';
	static public $CUSTOMER_CREDIT_BASE_CURRENCY_KEY = '$credit$';
	static public $CUSTOMER_CREDIT_IN_OWN_CURRENCY_KEY = '$creditcurrency$';
	static public $CUSTOMER_CURRENCY = '$currency$';
	static public $CUSTOMER_CARDNUMBER_KEY = '$cardnumber$';
	static public $CUSTOMER_PASSWORD_KEY = '$password$';
	static public $CUSTOMER_LOGIN = '$login$';
	static public $CUSTOMER_LOGINKEY = '$loginkey$';
	static public $CUSTOMER_CREDIT_NOTIFICATION = '$credit_notification$';
	//About system
	//used in all mail
	static public $SYSTEM_CURRENCY = '$base_currency$';
	//

	function __construct($type, $id_card = null, $lg = null, $msg = null, $title = null) {
		$DBHandle = DbConnect();
		if (!empty ($type)) {
			$tmpl_table = new Table("cc_templatemail", "*");
			$tmpl_clause = " mailtype = '$type'";
			$order = null;
			$order_field = null;
			if (!empty ($lg)) {
				$tmpl_clause .= " AND ( id_language = '$lg' OR  id_language = 'en' )";
				$order_field = 'id_language';
				if (strcasecmp($lg, 'en') < 0)
					$order = 'ASC';
				else
					$order = 'DESC';
			}
			elseif (!is_null($id_card) && is_numeric($id_card)) {
				//load the lg in the card... 
			}
			$result_tmpl = $tmpl_table->Get_list($DBHandle, $tmpl_clause, $order_field, $order);
			if (is_array($result_tmpl) && sizeof($result_tmpl) > 0) {
				$mail_tmpl = $result_tmpl[0];
				$this->message = $mail_tmpl['messagetext'];
				$this->title = $mail_tmpl['subject'];
				$this->from_email = $mail_tmpl['fromemail'];
				$this->from_name = $mail_tmpl['fromname'];
			} else {
				throw new A2bMailException("Template Type '$type' cannot be found into the database!");
			}
		} elseif (!empty ($msg) || !empty ($title)) {
			$this->message = $msg;
			$this->title = $title;
		} else {
			throw new A2bMailException("Error : no Type defined and neither message or subject is provided!");
		}
		if (!empty ($this->message) || !empty ($this->title)) {
			if (!is_null($id_card) && is_numeric($id_card)) {
				$this->id_card = $id_card;
				$card_table = new Table("cc_card", "*");
				$card_clause = " id = " . $id_card;
				$result_card = $card_table->Get_list($DBHandle, $card_clause, 0);
				if (is_array($result_card) && sizeof($result_card) > 0)
					$card = $result_card[0];
				$credit = $card['credit'];
				$currency = $card['currency'];
				$currencies_list = get_currencies($DBHandle);
				if (!isset ($currencies_list[strtoupper($currency)][2]) || !is_numeric($currencies_list[strtoupper($currency)][2])) {
					$mycur = 1;
				} else {
					$mycur = $currencies_list[strtoupper($currency)][2];
				}

				$credit_currency = $credit / $mycur;
				$credit_currency = round($credit_currency, 3);
				$this->to_email = $card['email'];
				$this->replaceInEmail(self :: $CUSTOMER_CARDNUMBER_KEY, $card['username']);
				$this->replaceInEmail(self :: $CUSTOMER_EMAIL_KEY, $card['email']);
				$this->replaceInEmail(self :: $CUSTOMER_FIRSTNAME_KEY, $card['firstname']);
				$this->replaceInEmail(self :: $CUSTOMER_LASTNAME_KEY, $card['lastname']);
				$this->replaceInEmail(self :: $CUSTOMER_LOGIN, $card['useralias']);
				$this->replaceInEmail(self :: $CUSTOMER_LOGINKEY, $card['loginkey']);
				$this->replaceInEmail(self :: $CUSTOMER_PASSWORD_KEY, $card['uipass']);
				$this->replaceInEmail(self :: $CUSTOMER_CREDIT_IN_OWN_CURRENCY_KEY, $credit_currency);
				$this->replaceInEmail(self :: $CUSTOMER_CREDIT_BASE_CURRENCY_KEY, $credit);
				$this->replaceInEmail(self :: $CUSTOMER_CURRENCY, $currency);
				$this->replaceInEmail(self :: $CUSTOMER_CREDIT_NOTIFICATION, $card['credit_notification']);

			}
			$this->replaceInEmail(self :: $SYSTEM_CURRENCY, BASE_CURRENCY);
		}
	}

	function replaceInEmail($key, $val) {
		$this->message = str_replace($key, $val, $this->message);
		$this->title = str_replace($key, $val, $this->title);
	}

	function getIdCard() {

		return $this->id_card;
	}

	function getFromEmail() {

		return $this->from_email;
	}
	function getToEmail() {

		return $this->to_email;
	}
	function getMessage() {

		return $this->message;
	}
	function AddToMessage($msg) {

		$this->message = $this->message . $msg;
	}
	function getTitle() {

		return $this->title;
	}

	function getFromName() {

		return $this->from_name;
	}
	function setFromEmail($from_email) {

		$this->from_email = $from_email;
	}

	function setToEmail($to_email) {

		$this->to_email = $to_email;
	}
	function setFromName($from_name) {

		$this->from_name = $from_name;
	}

	function send($to_email = null) {
		if (empty ($to_email)){
			a2b_mail($this->to_email, $this->title, $this->message, $this->from_email, $this->from_name);}
		else{
			a2b_mail($this->to_email, $this->title, $this->message, $this->from_email, $this->from_name);
                }
	}

}

