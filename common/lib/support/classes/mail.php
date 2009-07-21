<?php


class Mail {

	private $id_card;
        private $message='';
        private $title='';
        private $from_email='';
        private $from_name='';
        private $to_email='';
        //About payment
        static public $ITEM_NAME_KEY='$itemName';
        static public $ITEM_ID_KEY='$itemName';
        static public $ITEM_AMOUNT_KEY='$itemAmount';
        static public $PAYMENT_METHOD_KEY='$paymentMethod';
        static public $PAYMENT_STATUS_KEY='$paymentStatus';
        //About customer
        static public $CUSTOMER_EMAIL_KEY='$email';
        static public $CUSTOMER_FIRSTNAME_KEY='$firstname';
        static public $CUSTOMER_LASTNAME_KEY='$lastname';
        static public $CUSTOMER_CREDIT_BASE_CURRENCY_KEY='$credit';
        static public $CUSTOMER_CREDIT_IN_OWN_CURRENCY_KEY='$credit_currency';
        static public $CUSTOMER_CURRENCY='$currency';
        static public $CUSTOMER_CARDNUMBER_KEY='$cardnumber';
        static public $CUSTOMER_PASSWORD_KEY='$password';
        static public $CUSTOMER_LOGIN_KEY='$login';
        //About system
        static public $SYSTEM_CURRENCY='$base_currency';
        static public $SYSTEM_CREDIT_NOTIFICATION='$credit_notification';




	function __construct($type,$id_card=null) {
            $DBHandle = DbConnect();
            $tmpl_table = new Table("cc_templatemail", "*");
            $tmpl_clause = " mailtype = " . $type;
            $result_tmpl = $result_tmpl->Get_list($DBHandle, $tmpl_clause, 0);
            if(is_array($result_tmpl) && sizeof($result_tmpl)>0 ){
                $mail_tmpl= $result_tmpl[0];
                $this->message= $mail_tmpl['messagetext'];
                $this->title= $mail_tmpl['subject'];
                $this->from_email= $mail_tmpl['fromemail'];
                $this->from_name= $mail_tmpl['fromemail'];

                if(!is_null($id_card) && is_numeric($id_card))
                {
                $this->id_card= $id_card;
                $card_table = new Table("cc_card", "*");
                $card_clause = " id = " . $id_card;
                $result_card = $card_table->Get_list($DBHandle, $card_clause, 0);
                if(is_array($result_card) && sizeof($result_card)>0 )
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
                    $this->replaceInEmail(self::$CUSTOMER_CARDALIAS_KEY, $card['useralias']);
                    $this->replaceInEmail(self::$CUSTOMER_CARDNUMBER_KEY, $card['username']);
                    $this->replaceInEmail(self::$CUSTOMER_EMAIL_KEY, $card['email']);
                    $this->replaceInEmail(self::$CUSTOMER_FIRSTNAME_KEY, $card['firstname']);
                    $this->replaceInEmail(self::$CUSTOMER_LASTNAME_KEY, $card['lastname']);
                    $this->replaceInEmail(self::$CUSTOMER_LOGIN_KEY, $card['useralias']);
                    $this->replaceInEmail(self::$CUSTOMER_PASSWORD_KEY, $card['uipass']);
                    $this->replaceInEmail(self::$CUSTOMER_CREDIT_BASE_CURRENCY_KEY, $credit);
                    $this->replaceInEmail(self::$CUSTOMER_CREDIT_IN_OWN_CURRENCY_KEY, $credit_currency);
                    $this->replaceInEmail(self::$CUSTOMER_CURRENCY, $currency);

                }
                $this->replaceInEmail(self::$SYSTEM_CREDIT_NOTIFICATION, $currency);
                $this->replaceInEmail(self::$SYSTEM_CURRENCY, BASE_CURRENCY);
            }

	}

        function replaceInEmail($key,$val) {
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

	function getTitle() {

		return $this->title;
	}

	function getFromName() {

		return $this->from_name;
	}
        function setFromEmail($from_email) {

		$this->from_email=$from_email;
	}

	function getFromName($from_name) {

		 $this->from_name =$from_name;
	}

        function send($to_email=null){
            if(empty ($to_email)) a2b_mail ($this->to_email, $this->title, $this->message, $this->from_email, $this->from_name);
            else  a2b_mail ($this->to_email, $this->title, $this->message, $this->from_email, $this->from_name);
        }
	

}


