<?php

include(dirname(__FILE__).'/../includes/methods/iridium.php');

class iridium {
    var $code, $title, $description, $enabled, $status;

	// class constructor
    function iridium() {
		global $order;
		
		$this->code = 'iridium';
		$this->title = MODULE_PAYMENT_IRIDIUM_TEXT_TITLE;
		$this->description = MODULE_PAYMENT_IRIDIUM_TEXT_DESCRIPTION;
		$this->sort_order = 0;
		$this->enabled = ((MODULE_PAYMENT_IRIDIUM_STATUS == 'True') ? true : false);
		
		/*$my_actionurl = 'https://www.moneybookers.com/app/payment.pl';
		
		if  (strlen(MODULE_PAYMENT_IRIDIUM_REFID) <= '5')
		{
			$my_actionurl = $my_actionurl . '?rid=811621' ;
		}
		else
		{
			$my_actionurl = $my_actionurl . '?rid=' . MODULE_PAYMENT_IRIDIUM_REFID;
		}
		
		$this->form_action_url = $my_actionurl; */
	}

	// class methods
    function javascript_validation() {
    	return false;
    }


	function get_cc_images() {
		$cc_images = '';
		$cc_images .= tep_image(DIR_WS_ICONS . 'iridium' . '.gif');
		return $cc_images;
	}

   /* function selection() {
      	return array('id' => $this->code, 'module' => $this->title);
    } */


	function selection() {
      		global $order;

		$countries = get_countries();

      		for ($i=1; $i<13; $i++) {
        		$expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
      		}

      		for ($i=1; $i<32; $i++) {
        		$dom[] = array('id' => sprintf('%02d', $i), 'text' => sprintf('%02d', $i));
      		}

      		$today = getdate(); 
      			for ($i=$today['year']; $i < $today['year']+10; $i++) {
        		$expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
      		}
	
      		for ($i=$today['year']-10; $i <= $today['year']; $i++) {
        		$starts_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
      		}

		if(isset($_SESSION["agent_id"]) && !empty($_SESSION["agent_id"])){
                	$QUERY = "SELECT login as username, credit, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, '1', currency FROM cc_agent WHERE id = '".$_SESSION["agent_id"]."'";
        	}elseif(isset($_SESSION["card_id"]) && !empty($_SESSION["card_id"])){
                	$QUERY = "SELECT username, credit, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, status, currency FROM cc_card WHERE id = '".$_SESSION["card_id"]."'";
        	}else{
                	echo "ERROR";
                	die();
        	}

       		$DBHandle  = DbConnect();
       		$resmax = $DBHandle -> query($QUERY);
       		$numrow = $resmax -> numRows();
       		if ($numrow == 0) {exit();}
       		$customer_info =$resmax -> fetchRow();
       		if ($customer_info [12] != "1" ) {
                       	exit();
       		}
		$name = $customer_info['firstname'] . ' ' . $customer_info['lastname'];
		$addr1 = $customer_info['address'];
		$addr2 = $customer_info['city'];
		$addr3 = $customer_info['state'];
		$phone = $customer_info['phone'];
		$postcode = $customer_info['zipcode'];
		$countrycode = $customer_info['country'];
		$QUERY = "select countryname from cc_country where countrycode = '$countrycode'";
		$resmax = $DBHandle -> query($QUERY);
                $numrow = $resmax -> numRows();
		$cid = -1 ;
                if ($numrow == 0) {
			write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__. " Country not found" );
		} else {
               		$country_info =$resmax -> fetchRow();
			$country = $country_info['countryname'];
			for ($i = 0; $i < sizeof($countries); $i++) {
				if( ($country == $countries[$i]['text']) ) {
					$cid = $countries[$i]['id'];
				}
			}	
		}
			
      		$selection = array('id' => $this->code,
			'module' => $this->title . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $this->get_cc_images(),
			'fields' => array( array('title' => MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_OWNER,
			'field' => tep_draw_input_field('CardName', $name). ' (Override the default values if different )') , 
			array ('title' => MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_ADDR1,
                                'field' => tep_draw_input_field('Addr1', $addr1)),
			array ('title' => MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_ADDR2,
                                'field' => tep_draw_input_field('Addr2', $addr2)),
			array ('title' => MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_ADDR3,
                                'field' => tep_draw_input_field('Addr3', $addr3)),
			array ('title' => MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_POSTCODE,
                                'field' => tep_draw_input_field('PostCode', $postcode)),
			array ('title' => MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_COUNTRY,
                                'field' => tep_draw_pull_down_menu('Country', $countries, $cid)),
			array ('title' => MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_TELEPHONE,
                                'field' => tep_draw_input_field('Telephone', $phone)),
			array('title' => MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_NUMBER,
				'field' => tep_draw_input_field('CardNumber')),
	        	array('title' => MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_STARTS,
				'field' => tep_draw_pull_down_menu('StartDateMonth', $expires_month) . '&nbsp;' .
				tep_draw_pull_down_menu('StartDateYear', $starts_year)),
	        	array('title' => MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_EXPIRES,
				'field' => tep_draw_pull_down_menu('ExpiryDateMonth', $expires_month) . '&nbsp;' .
				tep_draw_pull_down_menu('ExpiryDateYear', $expires_year)),
			array('title' => MODULE_PAYMENT_IRIDIUM_TEXT_ISSUE_NUMBER,
                                'field' => tep_draw_input_field('IssueNumber', '',"SIZE=5, MAXLENGTH=5"). '(Switch/Solo/Maestro only)'),
			array('title' => 'CV2 ' . ' ' .'<a href="#" onclick="javascript:window.open(\'' . 'cvv.php' . '\',\'CardNumberSelection\',\'width=600,height=280,top=20,left=100,scrollbars=1\');">' . '<u><i>' . '(' .		MODULE_PAYMENT_IRIDIUM_TEXT_CVV_LINK . ')' . '</i></u></a>',
			'field' => tep_draw_input_field('CV2','',"SIZE=4, MAXLENGTH=4"))

         ));
     return $selection;
	/*
	 array ('title' => MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_DOB,
                                'field' => tep_draw_pull_down_menu('DOB_day', $dom) . '&nbsp;' .
                                tep_draw_pull_down_menu('DOB_month', $expires_month) . '&nbsp;' .
                                tep_draw_pull_down_menu('DOB_year', $dob_year)), */
    }



    function pre_confirmation_check() {
	global $_POST, $cvv;

        //include(dirname(__FILE__).'/../classes/cc_validation.php');
        //$cc_validation = new cc_validation();
	$cardNo = $_POST['CardNumber'];
        //$result = $cc_validation->validate($cardNo, $_POST['ExpiryDateMonth'], $_POST['ExpiryDateYear'], $_POST['CV2'], '');
	$result = true;
        
        $error = '';
  	$ccErrors [0] = "Credit card number has invalid format";
  	$ccErrors [1] = "Credit card number is invalid";
        
	if (!preg_match('/^[0-9]{13,19}$/i',$cardNo))  {
     		$errornumber = 0;     
     		$error = $ccErrors [$errornumber];
     		$result = false; 
  	}

    	$checksum = 0;                 
    	$j = 1;
    	for ($i = strlen($cardNo) - 1; $i >= 0; $i--) {
      		$calc = $cardNo{$i} * $j;
      		if ($calc > 9) {
        		$checksum = $checksum + 1;
        		$calc = $calc - 10;
      		}
      		$checksum = $checksum + $calc;
      		if ($j ==1) {$j = 2;} else {$j = 1;};
    	} 
    	if ($checksum % 10 != 0) {
     		$errornumber = 1;     
     		$error = $ccErrors [$errornumber];
     		$result = false; 
    	}

	if ( ($result == false) ) {
        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&CardName=' . urlencode($_POST['CardName']) . '&ExpiryDateMonth=' . $_POST['ExpiryDateMonth'] . '&ExpiryDateYear=' . $_POST['ExpiryDateYear']; 
        $payment_error_return .= '&amount=' . $_POST['amount'].'&item_name=' . $_POST['item_name'].'&item_number=' . $_POST['item_number'];
        $payment_error_return .= '&item_id='.$_POST['item_id'].'&item_type='.$_POST['item_type'];
        //tep_redirect(tep_href_link("checkout_payment.php", $payment_error_return, 'SSL', true, false));

      }
		return false;
    }

    
    function confirmation() {
		return false;
    }

   function threeDSecureAuth($mdMerchantDetails, $rgeplRequestGatewayEntryPointList, $CrossReference, $PaRES) {


 	$tdsidThreeDSecureInputData = new ThreeDSecureInputData($CrossReference, $PaRES);
 	$tdsaThreeDSecureAuthentication = new ThreeDSecureAuthentication($rgeplRequestGatewayEntryPointList, 1, null, $mdMerchantDetails, $tdsidThreeDSecureInputData, "Some data to be passed out");
 	$boTransactionProcessed = $tdsaThreeDSecureAuthentication->processTransaction($goGatewayOutput, $tomTransactionOutputMessage);

 	if ($boTransactionProcessed == false)
 	{
  		// could not communicate with the payment gateway
  		$Message = "Couldn't communicate with payment gateway";
		$retCode = -2;
 	}
 	else
 	{
  		switch ($goGatewayOutput->getStatusCode())
  		{

   		case 0:
    			// status code of 0 - means transaction successful
    			$Message = $goGatewayOutput->getMessage();
			write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__.$Message );
    			$retCode = 2;
    			break;
   		case 5:
    			// status code of 5 - means transaction declined
    			$Message = $goGatewayOutput->getMessage();
			write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__.$Message );
			$retCode = -2;
    			break;
   		case 20:
    			// status code of 20 - means duplicate transaction
    			$Message = $goGatewayOutput->getMessage();
			write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__.$Message );
    			if ($goGatewayOutput->getPreviousTransactionResult()->getStatusCode()->getValue() == 0)
    			{
				$retCode = 2;
    			}
    			else
    			{
				$retCode = -2;
       			}
    			$PreviousTransactionMessage = $goGatewayOutput->getPreviousTransactionResult()->getMessage();
    			break;
   		case 30:
    			// status code of 30 - means an error occurred
    			$Message = $goGatewayOutput->getMessage();
    			if ($goGatewayOutput->getErrorMessages()->getCount() > 0)
    			{
     				$Message = $Message."<br /><ul>";
     				for ($LoopIndex = 0; $LoopIndex < $goGatewayOutput->getErrorMessages()->getCount(); $LoopIndex++)
     				{
      					$Message = $Message."<li>".$goGatewayOutput->getErrorMessages()->getAt($LoopIndex)."</li>";
     				}
     				$Message = $Message."</ul>";
				$retCode = -2;
    			}
			write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__.$Message );
    			break;
   		default:
    			// unhandled status code 
    			$Message=$goGatewayOutput->getMessage();
			write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__.$Message );
			$retCode = -2;
    			break;
  		}

 	}
	return $retCode;
   }

    function process_payment() {

	foreach ($_POST as $field => $value) 
	{
		$$field = $value;
	}

	$rgeplRequestGatewayEntryPointList = new RequestGatewayEntryPointList();

	if ($PaymentProcessorPort == 443)
	{
		$PaymentProcessorFullDomain = $PaymentProcessorDomain."/";
	}
	else
	{
		$PaymentProcessorFullDomain = $PaymentProcessorDomain.":".$PaymentProcessorPort."/";
	}

	$rgeplRequestGatewayEntryPointList->add("https://gw1.".$PaymentProcessorFullDomain, 100, 2);
	$rgeplRequestGatewayEntryPointList->add("https://gw2.".$PaymentProcessorFullDomain, 200, 2);
	$rgeplRequestGatewayEntryPointList->add("https://gw3.".$PaymentProcessorFullDomain, 300, 2);

	$mdMerchantDetails = new MerchantDetails($MerchantID, $Password);


	$ttTransactionType = new NullableTRANSACTION_TYPE(TRANSACTION_TYPE::SALE);
	$mdMessageDetails = new MessageDetails($ttTransactionType);

	$boEchoCardType = new NullableBool(true);
	$boEchoAmountReceived = new NullableBool(true);
	$boEchoAVSCheckResult = new NullableBool(true);
	$boEchoCV2CheckResult = new NullableBool(true);
	$boThreeDSecureOverridePolicy = new NullableBool(true);

	$nDuplicateDelay = new NullableInt(60);

	$tcTransactionControl = new TransactionControl($boEchoCardType, $boEchoAVSCheckResult, $boEchoCV2CheckResult, $boEchoAmountReceived, $nDuplicateDelay, "",  "", $boThreeDSecureOverridePolicy,  "",  null, null);



	$nAmount = new NullableInt($Amount);
	$nCurrencyCode = new NullableInt($CurrencyISOCode);
	$nDeviceCategory = new NullableInt(0);
	$tdsbdThreeDSecureBrowserDetails = new ThreeDSecureBrowserDetails($nDeviceCategory, "*/*",  $_SERVER["HTTP_USER_AGENT"]);
	$tdTransactionDetails = new TransactionDetails($mdMessageDetails, $nAmount, $nCurrencyCode, $OrderID, $OrderDescription, $tcTransactionControl, $tdsbdThreeDSecureBrowserDetails);

	if ($ExpiryDateMonth != "")
	{
		$nExpiryDateMonth = new NullableInt($ExpiryDateMonth);
	}
	else
	{
		$nExpiryDateMonth = null;
	}
	if ($ExpiryDateYear != "")
	{
		$nExpiryDateYear = new NullableInt($ExpiryDateYear);
	}
	else
	{
		$nExpiryDateYear = null;
	}
	$ccdExpiryDate = new CreditCardDate($nExpiryDateMonth, $nExpiryDateYear);
	if ($StartDateMonth != "")
	{
		$nStartDateMonth = new NullableInt($StartDateMonth);
	}
	else
	{
		$nStartDateMonth = null;
	}
	if ($StartDateYear != "")
	{
		$nStartDateYear = new NullableInt($StartDateYear);
	}
	else
	{
		$nStartDateYear = null;
	}
	$ccdStartDate = new CreditCardDate($nStartDateMonth, $nStartDateYear);
	$cdCardDetails = new CardDetails($CardName, $CardNumber, $ccdExpiryDate, $ccdStartDate, $IssueNumber, $CV2);

	if ($CountryISOCode != "" &&
		$CountryISOCode != -1)
	{
		$nCountryCode = new NullableInt($CountryISOCode);
	}
	else
	{
		$nCountryCode = null;
	}
	$adBillingAddress = new AddressDetails($Address1, $Address2, $Address3, $Address4, $City, $State, $PostCode, $nCountryCode);
	$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
	$cdCustomerDetails = new CustomerDetails($adBillingAddress, $EmailID, $Phone, '');
	$cdtCardDetailsTransaction = new CardDetailsTransaction($rgeplRequestGatewayEntryPointList, 1, null, $mdMerchantDetails, $tdTransactionDetails, $cdCardDetails, $cdCustomerDetails, "");
	$boTransactionProcessed = $cdtCardDetailsTransaction->processTransaction($goGatewayOutput, $tomTransactionOutputMessage);

	if ($boTransactionProcessed == false)
	{
		// could not communicate with the payment gateway 
		$Message = "Couldn't communicate with payment gateway";
		$TransactionSuccessful = -2;
	}
	else
	{
		
		write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__. " Payment Gateway status Code : ". $goGatewayOutput->getStatusCode() );
		switch ($goGatewayOutput->getStatusCode())
		{
			case 0:

				// status code of 0 - means transaction successful 

				$Message = $goGatewayOutput->getMessage();
				write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__.$Message );
				$TransactionSuccessful = 2;

				break;

			case 3:

				// status code of 3 - means 3D Secure authentication required 
				$Message = $goGatewayOutput->getMessage();
				write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__.$Message );
				$PaRES = $tomTransactionOutputMessage->getThreeDSecureOutputData()->getPaREQ();
				$CrossReference = $tomTransactionOutputMessage->getCrossReference();
				$FormAction = $tomTransactionOutputMessage->getThreeDSecureOutputData()->getACSURL();
				$return_link = tep_href_link("iridium_threed.php","", 'SSL', false, false);
				include (dirname(__FILE__).'/../../customer.smarty.php');
				/*
				$ACXCALL_HISTORY = 1; $ACXPAYMENT_HISTORY = 1; $ACXVOUCHER = 1; $ACXINVOICES = 1; $ACXDID = 1; $ACXSPEED_DIAL = 1;
				$ACXRATECARD = 1; $ACXSIMULATOR  = 1; $ACXCALL_BACK = 1; $ACXCALLER_ID = 1; $ACXPASSWORD = 1; $ACXSUPPORT = 1;
				$ACXNOTIFICATION = 1; $ACXAUTODIALER = 1;
				$smarty->assign("ACXCALL_HISTORY", $ACXCALL_HISTORY);
				$smarty->assign("ACXPAYMENT_HISTORY", $ACXPAYMENT_HISTORY);
				$smarty->assign("ACXVOUCHER", $ACXVOUCHER);
				$smarty->assign("ACXINVOICES", $ACXINVOICES);
				$smarty->assign("ACXDID", $ACXDID);
				$smarty->assign("ACXSPEED_DIAL", $ACXSPEED_DIAL);
				$smarty->assign("ACXRATECARD", $ACXRATECARD);
				$smarty->assign("ACXSIMULATOR", $ACXSIMULATOR);
				$smarty->assign("ACXCALL_BACK", $ACXCALL_BACK);
				$smarty->assign("ACXCALLER_ID", $ACXCALLER_ID);
				$smarty->assign("ACXPASSWORD", $ACXPASSWORD);
				$smarty->assign("ACXSUPPORT", $ACXSUPPORT);
				$smarty->assign("ACXNOTIFICATION", $ACXNOTIFICATION);
				$smarty->assign("ACXAUTODIALER", $ACXAUTODIALER);
				*/
				$smarty->display( 'main.tpl');
				echo " <body onload=\"document.Form.submit();\">
					<div style=\"width:800px;margin:auto\">
                                        <form name=\"Form\" action=\"$FormAction\" method=\"post\"  target=\"ACSFrame\">
                                        <input name=\"PaReq\" type=\"hidden\" value=\"$PaRES\" / >
                                        <input name=\"MD\" type=\"hidden\" value=\"$CrossReference\" />
                                        <input name=\"TermUrl\" type=\"hidden\" value=\"$return_link?transactionID=$transactionID\" />
					<iframe id=\"ACSFrame\" name=\"ACSFrame\" width=\"500\" height=\"500\" frameborder=\"0\"></iframe>

                                </form>
                                </div>
				</body>";
				$smarty->display( 'footer.tpl');

				$TransactionSuccessful = 0;
				/*
				$TransactionSuccessful = $this->threeDSecureAuth($mdMerchantDetails, $rgeplRequestGatewayEntryPointList,
								$CrossReference, $PaRES);*/

				break;

			case 5:

				// status code of 5 - means transaction declined 


				$Message=$goGatewayOutput->getMessage();
				write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__.$Message );
				$TransactionSuccessful = -2;

				break;

			case 20:

				// status code of 20 - means duplicate transaction 

				$Message = $goGatewayOutput->getMessage();
				write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__.$Message );

				if ($goGatewayOutput->getPreviousTransactionResult()->getStatusCode()->getValue() == 0)

				{

					$TransactionSuccessful = 2;

				}

				else

				{

					$TransactionSuccessful = -2;

			   	}

				$PreviousTransactionMessage = $goGatewayOutput->getPreviousTransactionResult()->getMessage();
				$DuplicateTransaction = true;
				break;

			case 30:

				// status code of 30 - means an error occurred 

				$Message = $goGatewayOutput->getMessage();

				if ($goGatewayOutput->getErrorMessages()->getCount() > 0)

				{

					$Message = $Message."<br /><ul>";



					for ($LoopIndex = 0; $LoopIndex < $goGatewayOutput->getErrorMessages()->getCount(); $LoopIndex++)

					{

						$Message = $Message."<li>".$goGatewayOutput->getErrorMessages()->getAt($LoopIndex)."</li>";

					}

					$Message = $Message."</ul>";

					$TransactionSuccessful = -2;

				}
				write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__.$Message );

				break;

			default:

				// unhandled status code  

				$Message = $goGatewayOutput->getMessage();
				write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__.$Message );
				$TransactionSuccessful = -2;
				break;

		}

	}


	
	return $TransactionSuccessful;

    }

    function get_CurrentCurrency()
    {
        $my_currency = MODULE_PAYMENT_IRIDIUM_CURRENCY;
	$currencycodes = array('EUR' => '978','USD' => '840', 'GBP' => '826', 'HKD' => '344', 'SGD' => '702', 'JPY' => '392', 'CAD' => '124', 'AUD' => '036', 'CHF' => '756', 'DKK' => '208', 'SEK' => '752', 'NOK' => '578', 'ILS' => '376', 'MYR' => '458', 'NZD' => '554', 'TWD' => '901', 'THB' => '764', 'CZK' => '203', 'HUF' => '348', 'ISK' => '352', 'INR' => '356');

                if (!in_array($my_currency, array('EUR', 'USD', 'GBP', 'HKD', 'SGD', 'JPY', 'CAD', 'AUD', 'CHF', 'DKK', 'SEK', 'NOK', 'ILS', 'MYR', 'NZD', 'TWD', 'THB', 'CZK', 'HUF', 'SKK', 'ISK', 'INR'))) {
                $my_currency = 'USD';
        }
        return $currencycodes[$my_currency];
    }

    function process_button($transactionID = 0, $key= "") {

		global $order;
		$my_currency = MODULE_PAYMENT_IRIDIUM_CURRENCY;
	   	$amount = number_format($order->info['total'], 2, '.', '') * 100;
		$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
		$process_button_string = tep_draw_hidden_field('MerchantID', MODULE_PAYMENT_IRIDIUM_MERCHANTID) .
			   tep_draw_hidden_field('Password', MODULE_PAYMENT_IRIDIUM_PASSWORD) .
			   tep_draw_hidden_field('PaymentProcessorDomain', MODULE_PAYMENT_IRIDIUM_GATEWAY) .
			   tep_draw_hidden_field('PaymentProcessorPort', MODULE_PAYMENT_IRIDIUM_GATEWAY_PORT) .
			   tep_draw_hidden_field('Amount', $amount) .
			   tep_draw_hidden_field('CardName', $_POST['CardName']) .  
			   tep_draw_hidden_field('CardNumber', $_POST['CardNumber']) .
			   tep_draw_hidden_field('IssueNumber', $_POST['IssueNumber']) .
      			   tep_draw_hidden_field('CV2', $_POST['CV2']) .  
      			   tep_draw_hidden_field('CurrencyISOCode', $this->get_CurrentCurrency()) .  
      			   tep_draw_hidden_field('transactionID', $transactionID) .  
      			   tep_draw_hidden_field('sess_id', tep_session_id()) . 
			   tep_draw_hidden_field('ExpiryDateMonth', $_POST['ExpiryDateMonth']) . 
			   tep_draw_hidden_field('ExpiryDateYear', $_POST['ExpiryDateYear']) .
			   tep_draw_hidden_field('StartDateMonth', $_POST['StartDateMonth']) . 
			   tep_draw_hidden_field('StartDateYear', $_POST['StartDateYear']) .
                           tep_draw_hidden_field('OrderID', $transactionID) .
                           tep_draw_hidden_field('Address1', $_POST['Addr1']) .
                           tep_draw_hidden_field('City', $_POST['City']) .
                           tep_draw_hidden_field('State', $_POST['State']) .
                           tep_draw_hidden_field('PostCode', $_POST['PostCode']).
                           tep_draw_hidden_field('CountryISOCode', $_POST['Country']).
                           tep_draw_hidden_field('EmailID', $order->billing['email'] ? $order->billing['email']:ADMIN_EMAIL).
                           tep_draw_hidden_field('Phone', $_POST['Telephone']).
                           tep_draw_hidden_field('IPAddress', $ip).
			   tep_draw_hidden_field('transactionID', $transactionID). 
                           tep_draw_hidden_field('key', $key).
                           tep_draw_hidden_field('sess_id', tep_session_id()).
			   tep_draw_hidden_field('return_url', tep_href_link("userinfo.php", '', 'SSL')); 
                           //tep_draw_hidden_field('cancel_url', tep_href_link("checkout_payment.php", '', 'SSL'));	
				
                               //tep_draw_hidden_field('CountryISOCode', $order->billing['country']);
							   
		$process_button_string .= tep_draw_hidden_field(tep_session_name(), tep_session_id());

      return $process_button_string;
    }
	
    function before_process()
    {
        return;
    }
	
    function get_OrderStatus()
    {
        //status of the transaction;
        // Failed= -2
        // Canceled = -1;
        // Pending = 0
        //Scheduled = 1
        // Processed = 2

	$status = $this->process_payment();

        //echo '<a href="userinfo.php">Return to merchant</a>';
	//$payment_return = 'errcode='. urlencode($status);
	switch($status)
      	{
          case -2:
                        write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__." ERROR TRANSACTION FAILED");
            echo gettext("We are sorry your transaction is failed. Please try later or check your provided information.");
          break;
          case -1:
                        write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__." ERROR TRANSACTION DENIED");
            echo gettext("We are sorry your transaction is denied. Please try later or check your provided information.");
          break;
          case 0:
                        write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__." ERROR TRANSACTION PENDING");
			return $status;
            		//echo gettext("We are sorry your transaction is pending.");
          break;
          case 1:
                        write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__." ERROR TRANSACTION INPROGRESS");
            echo gettext("Your transaction is in progress.");
          break;
          case 2:
                        write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__." TRANSACTION SUCCESSFUL");
            echo gettext("Your transaction was successful.");
          break;
      }
      $link = tep_href_link("userinfo.php","", 'SSL', false, false);
      echo "<br><br>";
      echo "<a href=\"$link\">Return to Account</a>";
      return $status;
    }

    function after_process() {
	
      /*$payment_return = 'errcode = '.$this->status;
      tep_redirect(tep_href_link("checkout_success.php", $payment_return, 'SSL', true, false)); */

      return false;
    }

    function get_error() {
      global $_GET;
      $error = array('title' => MODULE_PAYMENT_IRIDIUM_TEXT_ERROR,
                     'error' => stripslashes(urldecode($_GET['error'])));
      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_IRIDIUM_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function output_error() {
      return false;
    }

    function install() {
      

	   tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description) 
	   VALUES ('MerchantID', 'MODULE_PAYMENT_IRIDIUM_MERCHANTID', 'yourMerchantId', 'Your Mechant Id provided by Iridium')");

	   tep_db_query("insert into " . TABLE_CONFIGURATION . "(configuration_title, configuration_key, configuration_value, configuration_description) 
	   VALUES ('Password', 'MODULE_PAYMENT_IRIDIUM_PASSWORD', 'Password', 'password for Iridium merchant')");
	   
	   tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description) 
	   VALUES ('PaymentProcessor', 'MODULE_PAYMENT_IRIDIUM_GATEWAY', 'PaymentGateway1 URL ', 'Enter payment gateway URL')");

       tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description) 
       VALUES ('PaymentProcessorPort', 'MODULE_PAYMENT_IRIDIUM_GATEWAY_PORT', 'PaymentGateway Port ', 'Enter payment gateway port')");

       tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, set_function) 
       VALUES ('Transaction Currency', 'MODULE_PAYMENT_IRIDIUM_CURRENCY', 'Selected Currency', 'The default currency for the payment transactions', 'tep_cfg_select_option(array(\'Selected Currency\',\'EUR\', \'USD\', \'GBP\', \'HKD\', \'SGD\', \'JPY\', \'CAD\', \'AUD\', \'CHF\', \'DKK\', \'SEK\', \'NOK\', \'ILS\', \'MYR\', \'NZD\', \'TWD\', \'THB\', \'CZK\', \'HUF\', \'SKK\', \'ISK\', \'INR\'), ')");

       tep_db_query("insert into " . TABLE_CONFIGURATION . "  (configuration_title, configuration_key, configuration_value, configuration_description, set_function) 
       VALUES ('Transaction Language', 'MODULE_PAYMENT_IRIDIUM_LANGUAGE', 'Selected Language', 'The default language for the payment transactions', 'tep_cfg_select_option(array(\'Selected Language\',\'EN\', \'DE\', \'ES\', \'FR\'), ')");

       tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, set_function) 
       VALUES ('Enable iridium Module', 'MODULE_PAYMENT_IRIDIUM_STATUS', 'True', 'Do you want to accept Iridium payments?','tep_cfg_select_option(array(\'True\', \'False\'), ')");
    }

    function remove() {
		tep_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key IN ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
		return array('MODULE_PAYMENT_IRIDIUM_STATUS', 'MODULE_PAYMENT_IRIDIUM_MERCHANTID', 'MODULE_PAYMENT_IRIDIUM_PASSWORD', 'MODULE_PAYMENT_IRIDIUM_GATEWAY',  'MODULE_PAYMENT_IRIDIUM_GATEWAY_PORT', 'MODULE_PAYMENT_IRIDIUM_LANGUAGE', 'MODULE_PAYMENT_IRIDIUM_CURRENCY');
    }
  }


   function get_countries() {

	$countries  = array( array ('id' => '826' , 'text' => 'United Kingdom'), 

		array ('id' => '840', 'text'=> 'United states'),

		array ('id' => '36', 'text'=> 'Australia'),

		array('id'=>'124','text'=>'Canada'),

		array('id'=>'250','text'=>'France'),

		 array ('id' => '276' , 'text' => 'Germany'), 

		array ('id' => '-1', 'text'=> '--------------------------'),

		array ('id' => '4', 'text'=> 'Afghanistan'),

		array('id'=>'248','text'=>'Åland Islands'),

		array('id'=>'8','text'=>'Albania'),

		 array ('id' => '12' , 'text' => 'Algeria'), 

		array ('id' => '16', 'text'=> 'American Samoa'),

		array ('id' => '20', 'text'=> 'Andorra'),

		array('id'=>'24','text'=>'Angola'),

		array('id'=>'660','text'=>'Anguilla'),		

		array ('id' => '10' , 'text' => 'Antarctica'), 

		array ('id' => '28', 'text'=> 'Antigua and Barbuda'),

		array ('id' => '32', 'text'=> 'Argentina'),

		array('id'=>'51','text'=>'Armenia'),

		array('id'=>'533','text'=>'Aruba'),

		 array ('id' => '40' , 'text' => 'Austria'), 

		array ('id' => '31', 'text'=> 'Azerbaijan'),

		array ('id' => '44', 'text'=> 'Bahamas'),

		array('id'=>'48','text'=>'Bahrain'),

		array('id'=>'50','text'=>'Bangladesh'),

		 array ('id' => '52' , 'text' => 'Barbados'), 

		array ('id' => '112', 'text'=> 'Belarus'),

		array ('id' => '56', 'text'=> 'Belgium'),

		array('id'=>'84','text'=>'Belize'),

		array('id'=>'204','text'=>'Benin'),

		 array ('id' => '60' , 'text' => 'Bermuda'), 

		array ('id' => '64', 'text'=> 'Bhutan'),

		array ('id' => '68', 'text'=> 'Bolivia'),

		array('id'=>'70','text'=>'Bosnia and Herzegovina'),

		array('id'=>'72','text'=>'Botswana'),

		 array ('id' => '74' , 'text' => 'Bouvet Island'), 

		array ('id' => '76', 'text'=> 'Brazil Federative'),

		array ('id' => '86', 'text'=> 'British Indian Ocean Territory'),

		array('id'=>'96','text'=>'Brunei'),

		array('id'=>'100','text'=>'Bulgaria'),

		array ('id' => '854', 'text'=> 'Burkina Faso'),

		array('id'=>'108','text'=>'Burundi'),

		array('id'=>'116','text'=>'Cambodia'),

		array ('id' => '120', 'text'=> 'Cameroon'),

		array('id'=>'132 ','text'=>'Cape Verde'),

		array('id'=>'136','text'=>'Cayman Islands'),

		array ('id' => '140', 'text'=> 'Central African Republic'),

		array('id'=>'148','text'=>'Chad'),

		array('id'=>'152','text'=>'Chile'),

		array ('id' => '156', 'text'=> 'China'),

		array('id'=>'162','text'=>'Christmas Island'),

		array('id'=>'166','text'=>'Cocos (Keeling) Islands'),	

		array('id'=>'170','text'=>'Colombia'),

		array ('id' => '174', 'text'=> 'Comoros'),

		array('id'=>'180','text'=>'Congo'),

		array('id'=>'178','text'=>'Congo'),

		array ('id' => '184', 'text'=> 'Cook Islands'),

		array('id'=>'188','text'=>'Costa Rica'),

		array('id'=>'384','text'=>'Cote dIvoire'),

		array ('id' => '191', 'text'=> 'Croatia'),

		array('id'=>'192','text'=>'Cuba'),

		array('id'=>'196','text'=>'Cyprus'),	

		array ('id' => '203', 'text'=> 'Czech Republic'),

		array('id'=>'208','text'=>'Denmark'),

		array('id'=>'262','text'=>'Djibouti'),

		array ('id' => '212', 'text'=> 'Dominica'),

		array('id'=>'214','text'=>'Dominican Republic'),

		array('id'=>'626','text'=>'East Timor'),

		array ('id' => '218', 'text'=> 'Ecuador'),

		array('id'=>'818','text'=>'Egypt'),

		array('id'=>'222','text'=>'El Salvador'),

		array ('id' => '226', 'text'=> 'Equatorial Guinea'),

		array('id'=>'232','text'=>'Eritrea'),

		array('id'=>'233','text'=>'Estonia'),

		array('id'=>'231','text'=>'Ethiopia'),

		array('id'=>'238','text'=>'Falkland Islands (Malvinas)'),

		array ('id' => '234', 'text'=>'Faroe Islands'),

		array('id'=>'242','text'=>'Fiji'),

		array('id'=>'246','text'=>'Finland'),

		array('id'=>'254','text'=>'French Guiana'),

		array('id'=>'258','text'=>'French Polynesia'),

		array ('id' => '260', 'text'=> 'French Southern Territories'),

		array('id'=>'266','text'=>'Gabon'),

		array('id'=>'270','text'=>'Gambia'),

		array ('id' => '268', 'text'=> 'Georgia'),

		array('id'=>'288','text'=>'Ghana'),

		array('id'=>'292','text'=>'Gibraltar'),

		array ('id' => '300', 'text'=> 'Greece'),

		array('id'=>'304','text'=>'Greenland'),

		array('id'=>'308','text'=>'Grenada'),

		array ('id' => '312', 'text'=> 'Guadaloupe'),

		array('id'=>'316','text'=>'Guam'),

		array('id'=>'320','text'=>'Guatemala'),

		array ('id' => '831', 'text'=> 'Guernsey'),

		array('id'=>'324','text'=>'Guinea'),

		array('id'=>'624','text'=>'Guinea-Bissau'),

		array('id'=>'328','text'=>'Guyana'),

		array('id'=>'332 ','text'=>'Haiti'),

		array('id'=>'334','text'=>'Heard Island and McDonald Islands'),

		array('id'=>'340','text'=>'Honduras'),

		array('id'=>'344','text'=>'Hong Kong'),

		array('id'=>'348','text'=>'Hungary'),

		array('id'=>'352','text'=>'Iceland'),

		array('id'=>'356','text'=>'India'),

		array('id'=>'360','text'=>'Indonesia'),

		array('id'=>'364','text'=>'Iran'),

		array('id'=>'368','text'=>'Iraq'),

		array('id'=>'372','text'=>'Ireland'),

		array('id'=>'833','text'=>'Isle of Man'),

		array('id'=>'376','text'=>'Israel'),

array('id'=>'380','text'=>'Italy'),

		array('id'=>'388','text'=>'Jamaica'),

		array('id'=>'392' ,'text'=>'Japan'),

		array('id'=>'832','text'=>'Jersey'),

		array('id'=>'400','text'=>'Jordan'),

		array('id'=>'398','text'=>'Kazakhstan'),

		array('id'=>'404','text'=>'Kenya'),

		array('id'=>'296','text'=>'Kiribati'),

		array('id'=>'410','text'=>'Korea'),

		array('id'=>'408','text'=>'Korea'),

		array('id'=>'414','text'=>'Kuwait'),

		array('id'=>'417','text'=>'Kyrgyzstan'),

		array('id'=>'418','text'=>'Lao'),

		array('id'=>'428','text'=>'Latvia'),

		array('id'=>'422','text'=>'Lebanon'),

		array('id'=>'426','text'=>'Lesotho'),

		array('id'=>'430','text'=>'Liberia'),

		array('id'=>'434','text'=>'Libyan Arab Jamahiriya'),

		array('id'=>'438','text'=>'Liechtenstein'),

		array('id'=>'440','text'=>'Lithuania'),

		array('id'=>'442','text'=>'Luxembourg'),

		array('id'=>'446','text'=>'Macau'),

		array('id'=>'807','text'=>'Macedonia'),

		array('id'=>'450','text'=>'Madagascar'),

		array('id'=>'454','text'=>'Malawi'),

		array('id'=>'458','text'=>'Malaysia'),

		array('id'=>'462','text'=>'Maldives'),

		array('id'=>'466','text'=>'Mali'),

		array('id'=>'470','text'=>'Malta'),

		array('id'=>'584','text'=>'Marshall Islands'),

		array('id'=>'474','text'=>'Martinique'),

		array('id'=>'478','text'=>'Mauritania Islamic'),

		array('id'=>'480','text'=>'Mauritius'),

		array('id'=>'175','text'=>'Mayotte'),

		array('id'=>'484','text'=>'Mexico'),

		array('id'=>'583','text'=>'Micronesia'),

		array('id'=>'498','text'=>'Moldova'),

		array('id'=>'492','text'=>'Monaco'),

		array('id'=>'496','text'=>'Mongolia'),

		array('id'=>'499','text'=>'Montenegro'),

		array('id'=>'500','text'=>'Montserrat'),

		array('id'=>'504','text'=>'Morocco'),

		array('id'=>'508','text'=>'Mozambique'),

		array('id'=>'104','text'=>'Myanmar'),

		array('id'=>'516','text'=>'Namibia'),

		array('id'=>'520','text'=>'Nauru'),

		array('id'=>'524','text'=>'Nepal'),

		array('id'=>'528','text'=>'Netherlands'),

		array('id'=>'530','text'=>'Netherlands Antilles'),

		array('id'=>'540','text'=>'New Caledonia'),

		array('id'=>'554','text'=>'New Zealand'),

		array('id'=>'558','text'=>'Nicaragua'),

		array('id'=>'562','text'=>'Niger'),

		array('id'=>'566','text'=>'Nigeria'),

		array('id'=>'570','text'=>'Niue'),

		array('id'=>'574','text'=>'Norfolk Island'),

		array('id'=>'580','text'=>'Northern Mariana Islands'),

		array('id'=>'578','text'=>'Norway'),

		array('id'=>'512','text'=>'Oman'),

		array('id'=>'586','text'=>'Pakistan'),

		array('id'=>'585','text'=>'Palau'),

		array('id'=>'275','text'=>'Palestine'),

		array('id'=>'591','text'=>'Panama'),

		array('id'=>'598','text'=>'Papua New Guinea'),

		array('id'=>'600','text'=>'Paraguay'),	

		array('id'=>'604 ','text'=>'Peru'),

		array('id'=>'608','text'=>'Philippines'),

		array('id'=>'612','text'=>'Pitcairn'),

		array('id'=>'616','text'=>'Poland'),

		array('id'=>'620','text'=>'Portugal'),

		array('id'=>'630','text'=>'Puerto Rico'),

		array('id'=>'634','text'=>'Qatar'),

		array('id'=>'638','text'=>'Réunion'),

		array('id'=>'642','text'=>'Romania'),

		array('id'=>'643','text'=>'Russian Federation'),

		array('id'=>'646','text'=>'Rwanda'),

		array('id'=>'652','text'=>'Saint Barthélemy'),

		array('id'=>'654','text'=>'Saint Helena'),

		array('id'=>'659','text'=>'Saint Kitts and Nevis'),

		array('id'=>'662','text'=>'Saint Lucia'),

		array('id'=>'663','text'=>'Saint Martin (French part)'),

		array('id'=>'666','text'=>'Saint Pierre and Miquelon'),

		array('id'=>'670','text'=>'Saint Vincent and the Grenadines'),

array('id'=>'882','text'=>'Samoa'),

		array('id'=>'674','text'=>'San Marino'),

		array('id'=>'678','text'=>'São Tomé and Príncipe Democratic'),

		array('id'=>'682','text'=>'Saudi Arabia'),

		array('id'=>'686','text'=>'Senegal'),

		array('id'=>'688','text'=>'Serbia'),

		array('id'=>'690','text'=>'Seychelles'),

		array('id'=>'694','text'=>'Sierra Leone'),												

		array('id'=>'702','text'=>'Singapore'),

		array('id'=>'703','text'=>'Slovakia'),

		array('id'=>'705','text'=>'Slovenia'),

		array('id'=>'90','text'=>'Solomon Islands'),

		array('id'=>'706','text'=>'Somalia'),

		array('id'=>'710','text'=>'South Africa'),

		array('id'=>'239','text'=>'South Georgia and the South Sandwich Islands'),

		array('id'=>'724 ','text'=>'Spain'),

		array('id'=>'144','text'=>'Sri Lanka'),

		array('id'=>'736','text'=>'Sudan'),

		array('id'=>'740','text'=>'Suriname'),

		array('id'=>'744','text'=>'Svalbard and Jan Mayen'),

		array('id'=>'748','text'=>'Swaziland'),

		array('id'=>'752','text'=>'Sweden'),

		array('id'=>'756','text'=>'Switzerland'),

		array('id'=>'760','text'=>'Syrian Arab Republic'),

		array('id'=>'158','text'=>'Taiwan,'),

		array('id'=>'762','text'=>'Tajikistan'),

		array('id'=>'834','text'=>'Tanzania'),

		array('id'=>'764','text'=>'Thailand'),

		array('id'=>'768','text'=>'Togo'),

		array('id'=>'772','text'=>'Tokelau'),

		array('id'=>'776','text'=>'Tonga'),

		array('id'=>'780','text'=>'Trinidad and Tobago'),

		array('id'=>'788','text'=>'Tunisia'),

		array('id'=>'792','text'=>'Turkey'),

		array('id'=>'795','text'=>'Turkmenistan'),

		array('id'=>'796','text'=>'Turks and Caicos Islands'),

		array('id'=>'798','text'=>'Tuvalu'),

		array('id'=>'800','text'=>'Uganda'),

		array('id'=>'804','text'=>'Ukraine'),

		array('id'=>'784','text'=>'United Arab Emirates'),

		array('id'=>'581','text'=>'United States Minor Outlying Islands'),

		array('id'=>'858','text'=>'Uruguay Eastern'),

		array('id'=>'860','text'=>'Uzbekistan'),

		array('id'=>'548','text'=>'Vanuatu'),

		array('id'=>'336','text'=>'Vatican City State'),

		array('id'=>'862','text'=>'Venezuela'),

		array('id'=>'704','text'=>'Vietnam'),

		array('id'=>'92','text'=>'Virgin Islands, British'),

		array('id'=>'850','text'=>'Virgin Islands, U.S.'),

		array('id'=>'876','text'=>'Wallis and Futuna'),

		array('id'=>'732','text'=>'Western Sahara'),

		array('id'=>'887','text'=>'Yemen'),

		array('id'=>'894','text'=>'Zambia'),
	);
		
	return $countries;
		

   }


?>
