<?php


class FormBO {
	
	
/**
	* Function to add/modify cc_did_use and cc_did_destination if records existe
	*
	*/
	static public function is_did_in_use()
	{
		$FormHandler = FormHandler::GetInstance();
		$processed = $FormHandler->getProcessed();
		$did_id=$processed['id'];
		$instance_did_use_table = new Table();
		$QUERY_DID="select id_cc_card from cc_did_use where id_did ='".$did_id."' and releasedate IS NULL and activated = 1";
		$row= $instance_did_use_table -> SQLexec ($FormHandler->DBHandle,$QUERY_DID, 1);
		if ((isset($row[0][0])) && (strlen($row[0][0]) > 0))
			$FormHandler -> FG_INTRO_TEXT_ASK_DELETION = gettext ("This did is in use by customer id:".$row[0][0].", If you really want remove this ". $FormHandler -> FG_INSTANCE_NAME .", click on the delete button.");
	}
	
	/**
     * Function did_use_delete
     * @public
     */
	static public function did_use_delete()
	{
		$FormHandler = FormHandler::GetInstance();
		$processed = $FormHandler->getProcessed();
		$did_id=$processed['id'];
		$FG_TABLE_DID_USE_NAME = "cc_did_use";
		$FG_TABLE_DID_USE_CLAUSE= "id_did = '".$did_id."' and releasedate IS NULL";
		$FG_TABLE_DID_USE_PARAM= "releasedate = now()";
		$instance_did_use_table = new Table($FG_TABLE_DID_USE_NAME);
		$result_query= $instance_did_use_table -> Update_table ($FormHandler->DBHandle, $FG_TABLE_DID_USE_PARAM, $FG_TABLE_DID_USE_CLAUSE, null);
		$FG_TABLE_DID_USE_NAME = "cc_did_destination";
		$instance_did_use_table = new Table($FG_TABLE_DID_USE_NAME);
		$FG_TABLE_DID_USE_CLAUSE= "id_cc_did = '".$did_id."'";
		$result_query= $instance_did_use_table -> Delete_table ($FormHandler->DBHandle, $FG_TABLE_DID_USE_CLAUSE, null);
	}
	
	/**
     * Function add_did_use
     * @public
     */
	static public function add_did_use()
	{
		$FormHandler = FormHandler::GetInstance();
		$processed = $FormHandler->getProcessed();
		$did=$processed['did'];
		$FG_TABLE_DID_USE_NAME = "cc_did_use";
		$FG_QUERY_ADITION_DID_USE_FIELDS = 'id_did';
		$instance_did_use_table = new Table($FG_TABLE_DID_USE_NAME, $FG_QUERY_ADITION_DID_USE_FIELDS);
		$id = $FormHandler -> RESULT_QUERY;
		$result_query= $instance_did_use_table -> Add_table ($FormHandler->DBHandle, $id, null, null, null);
	}
	
	
	/**
     * Function create_status_log
     * @public
     */
	static public function create_status_log()
	{
		$FormHandler = FormHandler::GetInstance();
		$processed = $FormHandler->getProcessed();
		$status = $processed['status'];
		$oldstatus = $processed['oldstatus'];
		if($oldstatus != $status){
			if ($FormHandler -> RESULT_QUERY && !(is_object($FormHandler -> RESULT_QUERY)) )
				$id = $FormHandler -> RESULT_QUERY; // DEFINED BEFORE FG_ADDITIONAL_FUNCTION_AFTER_ADD		
			else
				$id = $processed['id']; // DEFINED BEFORE FG_ADDITIONAL_FUNCTION_AFTER_ADD		
			
			$value = "'$status','$id'";
			$func_fields = "status,id_cc_card";
			$func_table = 'cc_status_log';
			$id_name = "";
			$instance_table = new Table();
			$inserted_id = $instance_table -> Add_table ($FormHandler->DBHandle, $value, $func_fields, $func_table, $id_name);
		}
	}
	
	/**
     * Function create_sipiax_friends_reload
     * @public
     */
	static public function create_sipiax_friends_reload()
	{
		$FormHandler = FormHandler::GetInstance();
		self :: create_sipiax_friends();
		
		// RELOAD SIP & IAX CONF
		require_once (dirname(__FILE__)."/../phpagi/phpagi-asmanager.php");
		
		$as = new AGI_AsteriskManager();
		// && CONNECTING  connect($server=NULL, $username=NULL, $secret=NULL)
		$res =@  $as->connect(MANAGER_HOST,MANAGER_USERNAME,MANAGER_SECRET);				
		if	($res) {
			$res = $as->Command('sip reload');		
			$res = $as->Command('iax2 reload');		
			// && DISCONNECTING	
			$as->disconnect();
		} else {
			echo "Error : Manager Connection";
		}
	}
	
	/**
     * Function add_card_refill
     * @public
     */
	static public function add_card_refill()
	{
		
		global $A2B;
		$FormHandler = FormHandler::GetInstance();
		$processed = $FormHandler->getProcessed();
		$credit = $processed['credit'];
		$card_id = $processed['card_id'];
		
		// REFILL CARD
		$instance_table_card = new Table("cc_card");
		$param_update_card = "credit = credit + '".$credit."'";
		$clause_update_card = " id='$card_id'";
		$instance_table_card -> Update_table ($FormHandler->DBHandle, $param_update_card, $clause_update_card, $func_table = null);
	}
	
	/**
     * Function add_card_refill_agent
     * @public
     */
	static public function add_card_refill_agent()
	{
		global $A2B;
		$FormHandler = FormHandler::GetInstance();
		$processed = $FormHandler->getProcessed();
		$credit = $processed['credit'];
		$card_id = $processed['card_id'];
	
		//check if enought credit
		$instance_table_agent = new Table("cc_agent", "credit, currency");
		$FG_TABLE_CLAUSE_AGENT = "id = ".$_SESSION['agent_id'] ;
		$agent_info = $instance_table_agent -> Get_list ($FormHandler -> DBHandle, $FG_TABLE_CLAUSE_AGENT, null, null, null, null, null, null);			
		$credit_agent = $agent_info[0][0];
		  
		if ($credit_agent >= $credit) {
		
			//Substract credit for agent
			$param_update_agent = "credit = credit - '".$credit."'";
			$instance_table_agent -> Update_table ($FormHandler -> DBHandle, $param_update_agent, $FG_TABLE_CLAUSE_AGENT, $func_table = null);	
			
			// REFILL CARD
			$instance_table_card = new Table("cc_card");
			$param_update_card = "credit = credit + '".$credit."'";
			$clause_update_card = " id='$card_id'";
			$instance_table_card -> Update_table ($FormHandler->DBHandle, $param_update_card, $clause_update_card, $func_table = null);
			
			return true;
		}
		
		return false;
	}

	static public function ticket_add()
	{
		global $A2B;
		$FormHandler = FormHandler::GetInstance();
		$id_ticket = $FormHandler -> RESULT_QUERY;
		$processed = $FormHandler->getProcessed();
		$title = $processed['title'];
		$card_id = $processed['creator'];
		$priority = $processed['priority'];
		$description = $processed['description'];
		$component_id = $processed['id_component'];
		$table_card =new Table("cc_card", "username,firstname,lastname,language,email");
		$card_clause = "id = ".$card_id;
		$result=$table_card ->Get_list($FormHandler->DBHandle, $card_clause);
		
		$owner = $result[0]['username']." (".$result[0]['firstname']." ".$result[0]['lastname'].")";
		
		try {
			$mail = new Mail(Mail::$TYPE_TICKET_NEW, null, $result[0]['language']);
			$mail->replaceInEmail(Mail::$TICKET_OWNER_KEY, $owner);
			$mail->replaceInEmail(Mail::$TICKET_NUMBER_KEY, $id_ticket);
			$mail->replaceInEmail(Mail::$TICKET_DESCRIPTION_KEY, $description);
			$mail->replaceInEmail(Mail::$TICKET_PRIORITY_KEY, Ticket::DisplayPriority($priority));
			$mail->replaceInEmail(Mail::$TICKET_STATUS_KEY,"NEW");
			$mail->replaceInEmail(Mail::$TICKET_TITLE_KEY, $title);
			$mail->send($result[0]['email']);
		} catch (A2bMailException $e) {
            $error_msg = $e->getMessage();
        }
		
		$component_table = new Table('cc_support_component LEFT JOIN cc_support ON id_support = cc_support.id', "email,language");
		$component_clause = "cc_support_component.id = ".$component_id;
		$result= $component_table -> Get_list($FormHandler->DBHandle, $component_clause);
		
		try {
			$mail = new Mail(Mail::$TYPE_TICKET_NEW, null, $result[0]['language']);
			$mail->replaceInEmail(Mail::$TICKET_OWNER_KEY, $owner);
			$mail->replaceInEmail(Mail::$TICKET_NUMBER_KEY, $id_ticket);
			$mail->replaceInEmail(Mail::$TICKET_DESCRIPTION_KEY, $description);
			$mail->replaceInEmail(Mail::$TICKET_PRIORITY_KEY, Ticket::DisplayPriority($priority));
			$mail->replaceInEmail(Mail::$TICKET_STATUS_KEY,"NEW");
			$mail->replaceInEmail(Mail::$TICKET_TITLE_KEY, $title);
			$mail->send($result[0]['email']);
		} catch (A2bMailException $e) {
            $error_msg = $e->getMessage();
        }
	}

	static public function ticket_agent_add()
	{
		global $A2B;
		$FormHandler = FormHandler::GetInstance();
		$id_ticket = $FormHandler -> RESULT_QUERY;
		$processed = $FormHandler->getProcessed();
		$title = $processed['title'];
		$agent_id = $processed['creator'];
		$priority = $processed['priority'];
		$description = $processed['description'];
		$component_id = $processed['id_component'];
		$table_agent =new Table("cc_agent", "login,firstname,lastname,language,email");
		$agent_clause = "id = ".$agent_id;
		$result=$table_agent ->Get_list($FormHandler->DBHandle, $agent_clause);
		
		$owner = $result[0]['username']." (".$result[0]['firstname']." ".$result[0]['lastname'].")";
		
		try {
			$mail = new Mail(Mail::$TYPE_TICKET_NEW, null, $result[0]['language']);
			$mail->replaceInEmail(Mail::$TICKET_OWNER_KEY, $owner);
			$mail->replaceInEmail(Mail::$TICKET_NUMBER_KEY, $id_ticket);
			$mail->replaceInEmail(Mail::$TICKET_DESCRIPTION_KEY, $description);
			$mail->replaceInEmail(Mail::$TICKET_PRIORITY_KEY, Ticket::DisplayPriority($priority));
			$mail->replaceInEmail(Mail::$TICKET_STATUS_KEY,"NEW");
			$mail->replaceInEmail(Mail::$TICKET_TITLE_KEY, $title);
			$mail->send($result[0]['email']);
		} catch (A2bMailException $e) {
            $error_msg = $e->getMessage();
        }
        
		$component_table = new Table('cc_support_component LEFT JOIN cc_support ON id_support = cc_support.id', "email,language");
		$component_clause = "cc_support_component.id = ".$component_id;
		$result= $component_table -> Get_list($FormHandler->DBHandle, $component_clause);
		
		try {
			$mail = new Mail(Mail::$TYPE_TICKET_NEW, null, $result[0]['language']);
			$mail->replaceInEmail(Mail::$TICKET_OWNER_KEY, $owner);
			$mail->replaceInEmail(Mail::$TICKET_NUMBER_KEY, $id_ticket);
			$mail->replaceInEmail(Mail::$TICKET_DESCRIPTION_KEY, $description);
			$mail->replaceInEmail(Mail::$TICKET_PRIORITY_KEY, Ticket::DisplayPriority($priority));
			$mail->replaceInEmail(Mail::$TICKET_STATUS_KEY,"NEW");
			$mail->replaceInEmail(Mail::$TICKET_TITLE_KEY, $title);
			$mail->send($result[0]['email']);
		} catch (A2bMailException $e) {
            $error_msg = $e->getMessage();
        }
	}
	
	
	static public function add_agent_refill()
	{
		global $A2B;
		$FormHandler = FormHandler::GetInstance();
		$processed = $FormHandler->getProcessed();
		$credit = $processed['credit'];
		$agent_id = $processed['agent_id'];
		
		//REFILL CARD .. UPADTE AGENT
		$instance_table_agent = new Table("cc_agent");
		$param_update_agent = "credit = credit + '".$credit."'";
		$clause_update_agent = " id='$agent_id'";
		$instance_table_agent -> Update_table ($FormHandler->DBHandle, $param_update_agent, $clause_update_agent, $func_table = null);
	}
	
	static public function creation_card_refill()
	{
		$FormHandler = FormHandler::GetInstance();
		$processed = $FormHandler->getProcessed();
		$credit = $processed['credit'];
		
		if ($credit>0) {
			$field_insert = " credit, card_id, description";
			$card_id = $FormHandler -> RESULT_QUERY;
			$description = gettext("CREATION CARD REFILL");
			$value_insert = "'$credit', '$card_id', '$description' ";
			$instance_refill_table = new Table("cc_logrefill", $field_insert);
			$instance_refill_table -> Add_table ($FormHandler->DBHandle, $value_insert, null, null);	
		}
	}
	
	static public function deletion_card_refill_agent()
	{
		$FormHandler = FormHandler::GetInstance();
		$processed = $FormHandler->getProcessed();
		//AFTER A DELETE YOU DON T HAVE ACCESS TO ANY FIELD AND YOU CAN ACCESS ONLY TO THE ID
		//SO YOU HAVE TO LOAD THE FIELD THAT YOU NEED
		$card_id = $processed['id'];
		$card_table = new Table('cc_card','credit');
		$card_clause = "id = ".$card_id;
		$card_result = $card_table -> Get_list($FormHandler->DBHandle, $card_clause, 0);
		
		$credit = $card_result[0][0];
		
		if ($credit>0 || $credit<0) {
			if($credit>0){
				$sign="+";
			}else{
				$sign="-";
			}
			$instance_table_agent = new Table("cc_agent");
			$param_update_agent = "credit = credit $sign '".abs($credit)."'";
			$clause_update_agent = " id='".$_SESSION['agent_id']."'";
			$instance_table_agent -> Update_table ($FormHandler->DBHandle, $param_update_agent, $clause_update_agent, $func_table = null);
			$field_insert = " credit, card_id, refill_type, description";
			$description = gettext("DELETION CARD REFILL");
			$correction = 0-$credit;
			$value_insert = "'$correction', '$card_id', 1 ,'$description' ";
			$instance_refill_table = new Table("cc_logrefill", $field_insert);
			$instance_refill_table -> Add_table ($FormHandler->DBHandle, $value_insert, null, null);
			if($credit>0){ 
				$table_transaction = new Table();
				$result_agent = $table_transaction -> SQLExec($FormHandler->DBHandle,"SELECT cc_card_group.id_agent FROM cc_card LEFT JOIN cc_card_group ON cc_card_group.id = cc_card.id_group WHERE cc_card.id = $card_id");
				
				if (is_array($result_agent)&& !is_null($result_agent[0]['id_agent']) && $result_agent[0]['id_agent']>0 ) {
					// test if the agent exist and get its commission
					$id_agent = $result_agent[0]['id_agent'];
					$agent_table = new Table("cc_agent", "commission");
					$agent_clause = "id = ".$id_agent;
					$result_agent= $agent_table -> Get_list($FormHandler->DBHandle,$agent_clause);
					
					if (is_array($result_agent) && is_numeric($result_agent[0]['commission']) && $result_agent[0]['commission']>0) {
						$field_insert = "id_payment, id_card, amount,description,id_agent";
						$commission = a2b_round($credit * ($result_agent[0]['commission']/100));
						$description_commission = gettext("CORRECT COMMISSION AFTER CARD DELETED!");
						$description_commission.= "\nID CARD : ".$card_id;
						$description_commission.= "\n AMOUNT: ".$credit;
						$description_commission.= "\nCOMMISSION APPLIED: ".$result_agent[0]['commission'];
						$value_insert = "'-1', '$card_id', '-$commission','$description_commission','$id_agent'";
						$commission_table = new Table("cc_agent_commission", $field_insert);
						$id_commission = $commission_table -> Add_table ($FormHandler->DBHandle, $value_insert, null, null,"id");
						$table_agent = new Table('cc_agent');
						$param_update_agent = "com_balance = com_balance - '".$commission."'";
						$clause_update_agent = " id='".$id_agent."'";
						$table_agent -> Update_table ($FormHandler->DBHandle, $param_update_agent, $clause_update_agent, $func_table = null);
					}
				}		
			}
		}
	}
	
	static public function creation_agent_refill()
	{
		$FormHandler = FormHandler::GetInstance();
		$processed = $FormHandler->getProcessed();
		$credit = $processed['credit'];
		
		if ($credit>0) {
			$field_insert = " credit,agent_id, description";
			$agent_id = $FormHandler -> RESULT_QUERY;
			$description = gettext("CREATION AGENT REFILL");
			$value_insert = "'$credit', '$agent_id', '$description' ";
			$instance_refill_table = new Table("cc_logrefill_agent", $field_insert);
			$instance_refill_table -> Add_table ($FormHandler->DBHandle, $value_insert, null, null);	
		}
	}
	
	static public function processing_card_signup()
	{
		$FormHandler = FormHandler::GetInstance();
		if (RELOAD_ASTERISK_IF_SIPIAX_CREATED) {
			self::create_sipiax_friends_reload();
		} else {
			self::create_sipiax_friends();
		}
		
		self::create_subscriptions();
		self::create_notification_signup();
	}
	
	static public function create_subscriptions()
	{
		global $A2B;
		$FormHandler = FormHandler::GetInstance();
		$processed = $FormHandler->getProcessed();
		$subscriber = $processed['subscriber_signup'];
		$table_subscription = new Table("cc_subscription_service","*");
		$subscription_clause = "id = ".$subscriber;
		$result_sub = $table_subscription->Get_list($FormHandler->DBHandle, $subscription_clause);
		
		if (is_numeric($subscriber) && is_array($result_sub) && $result_sub[0]['fee'] > 0) {

			$subscription = $result_sub[0];
			$billdaybefor_anniversery = $A2B->config['global']['subscription_bill_days_before_anniversary'];

			$unix_startdate = time();
			$startdate = date("Y-m-d",$unix_startdate);
			$day_startdate = date("j",$unix_startdate);
			$month_startdate = date("m",$unix_startdate);
			$year_startdate= date("Y",$unix_startdate);
			$lastday_of_startdate_month = lastDayOfMonth($month_startdate,$year_startdate,"j");

			$next_bill_date = strtotime("01-$month_startdate-$year_startdate + 1 month");
			$lastday_of_next_month= lastDayOfMonth(date("m",$next_bill_date),date("Y",$next_bill_date),"j");
			$limite_pay_date = date("Y-m-d",strtotime(" + $billdaybefor_anniversery day")) ;

			if ($day_startdate > $lastday_of_next_month) {
				$next_limite_pay_date = date ("$lastday_of_next_month-m-Y" ,$next_bill_date);
			} else {
				$next_limite_pay_date = date ("$day_startdate-m-Y" ,$next_bill_date);
			}

			$next_bill_date = date("Y-m-d",strtotime("$next_limite_pay_date - $billdaybefor_anniversery day")) ;
			
			$field_insert = " id_cc_card, id_subscription_fee, product_name, paid_status, startdate, next_billing_date, limit_pay_date, last_run";
			$card_id = $FormHandler -> RESULT_QUERY;

			$instance_table = new Table("cc_card", "");
			$QUERY = "UPDATE cc_card SET status=8 WHERE id=$card_id";
			$instance_table->SQLExec($FormHandler->DBHandle, $QUERY, 0);

			$product_name = $subscription['label'];
			$value_insert = "'$card_id', '$subscriber' ,'$product_name', 1 , '$startdate', '$next_bill_date','$limite_pay_date','$startdate'";
			$instance_subscription_table = new Table("cc_card_subscription", $field_insert);
			$id_card_subscription = $instance_subscription_table -> Add_table ($FormHandler->DBHandle, $value_insert, null, null, "id");
			$reference = generate_invoice_reference();

			//CREATE INVOICE If a new card then just an invoice item in the last invoice
			$field_insert = "date, id_card, title, reference, description, status, paid_status";
			$date = date("Y-m-d h:i:s");
			$title = gettext("SUBSCRIPTION INVOICE REMINDER");
			$description = "You have $billdaybefor_anniversery days to pay your subscription with this invoice (REF: $reference ) or the account will be automatically disactived \n\n";
			$value_insert = " '$date' , '$card_id', '$title','$reference','$description',1,0";
			$instance_table = new Table("cc_invoice", $field_insert);
			$id_invoice = $instance_table->Add_table($FormHandler->DBHandle, $value_insert, null, null, "id");

			if (!empty ($id_invoice) && is_numeric($id_invoice)) {
				$description = "Subscription service";
				$amount = $subscription['fee'];
				$vat = 0;
				$field_insert = "date, id_invoice, price, vat, description, id_ext, type_ext";
				$instance_table = new Table("cc_invoice_item", $field_insert);
				$value_insert = " '$date' , '$id_invoice', '$amount','$vat','$description','$id_card_subscription','SUBSCR'";
				if ($verbose_level >= 1)
					echo "INSERT INVOICE ITEM : $field_insert =>	$value_insert \n";
				$instance_table->Add_table($FormHandler->DBHandle, $value_insert, null, null, "id");
			}
			
			$mail = new Mail(Mail::$TYPE_SUBSCRIPTION_UNPAID,$card_id );
			$mail -> replaceInEmail(Mail::$DAY_REMAINING_KEY,$day_remaining );
			$mail -> replaceInEmail(Mail::$INVOICE_REF_KEY,$reference);
			$mail -> replaceInEmail(Mail::$SUBSCRIPTION_FEE,$subscription['fee']);
			$mail -> replaceInEmail(Mail::$SUBSCRIPTION_ID,$subscription['id']);
			$mail -> replaceInEmail(Mail::$SUBSCRIPTION_LABEL,$subscription['product_name']);

			//insert charge
			$QUERY = "INSERT INTO cc_charge (id_cc_card, amount, chargetype, id_cc_card_subscription, invoiced_status) VALUES ('" . $card_id . "', '" . $subscription['fee']  . "', '3','" . $subscription['card_subscription_id'] . "',1)";
			$instance_table->SQLExec($FormHandler->DBHandle, $QUERY, 0);
			
			try {
				$mail -> send();
			} catch (A2bMailException $e) {
			}
		}
	}
	
	static public function processing_commission_add()
	{
		$FormHandler = FormHandler::GetInstance();
		$processed = $FormHandler->getProcessed();
		$id_agent = $processed['id_agent'];
		$id = $FormHandler -> RESULT_QUERY;
		$type_com =  $processed['commission_type'];
		if (!empty($id_agent)) {
			//update record with agent commission
			$table_agent = new Table('cc_agent','commission');
			$agent_clause = "id = ".$id_agent;
			$agent_result = $table_agent -> Get_list($FormHandler->DBHandle, $agent_clause, 0);
			$agent_com = $agent_result[0][0];
			if (empty($agent_com) ) {
				$table_commission = new Table("cc_agent_commission");
				$param_update_commission = "commission_percent = $agent_com";
				$clause_update_commission = " id='".$id."'";
				$table_commission -> Update_table ($FormHandler->DBHandle, $param_update_commission, $clause_update_commission, $func_table = null);
			}
			$amount = $processed['amount'];
			if($amount>0)$sign="+";
			else $sign="-";
			$param_update_agent = "com_balance = com_balance $sign '".abs($amount)."'";
			$clause_update_agent = " id='".$id_agent."'";
			$table_agent -> Update_table ($FormHandler->DBHandle, $param_update_agent, $clause_update_agent, $func_table = null);
		}
	}
	static public function processing_card_add()
	{
		self::create_sipiax_friends();
		self::creation_card_refill();
		self::create_lock_card();
	}
	
	static public function processing_card_del_agent()
	{	
		self::deletion_card_refill_agent();
	}
	
	static public function processing_card_add_agent()
	{
		self::create_sipiax_friends();
		self::create_lock_card();
	}
	
	static public function processing_refill_add()
	{
		$FormHandler = FormHandler::GetInstance();
		self::add_card_refill();
		//add invoice
		self::create_invoice_after_refill();
	}
	
	/*
	 * static public function to add a new DID Destination and set the DID use & Charge correctly
	 */
	static public function did_destination_add()
	{
		global $A2B;
		$FormHandler = FormHandler::GetInstance();
		$processed = $FormHandler->getProcessed();
		
		$instance_table = new Table();
		$id_cc_did = $processed['id_cc_did'];
		$id_cc_card = $processed['id_cc_card'];
		
		// 3 cases to handle :
		// the DID is released so we can purchase it
		// the DID is used by an other user, we might want to change
		// the DID is new nothing in cc_did_use
				
		$QUERY_DID = "SELECT cc_did_use.id, cc_did_use.id_cc_card, cc_did.fixrate, billingtype, releasedate ".
		             "FROM cc_did_use ".
		             "LEFT JOIN cc_did ON cc_did.id = cc_did_use.id_did ".
		             "WHERE id_did ='".$id_cc_did."'" .
		             "ORDER BY cc_did_use.id DESC";
		
		$result_did = $instance_table -> SQLExec($FormHandler->DBHandle, $QUERY_DID);

		if (is_array($result_did) && count($result_did)>0) {
			
			// check the id_cc_card, if id_cc_card is null it means it has been released 
			if ((isset($result_did[0]['id_cc_card'])) && (strlen($result_did[0]['id_cc_card']) > 0)){
				// echo("DID $did_id is in use by customer id:".$existing_owner_id);
				$existing_owner_id = $result_did[0]['id_cc_card'];
				
			} else {  
				// did_use without a registered card
				// echo("DID $did_id has been freed");
				$existing_owner_id = -1;
			}
		} else {
			// No result, the DID hasnt been purchased yet
			$existing_owner_id = -2;
		}

		if($existing_owner_id >= -2 && $existing_owner_id != $id_cc_card) {
			
			// The did ownership has changed and we need to update. (regardless of how it's billed)
			if( $result_did[0]['billingtype'] == 0 || $result_did[0]['billingtype'] == 1 ) {
				$rate = $result_did[0]['fixrate'];
				$QUERY1 = "INSERT INTO cc_charge (id_cc_card, amount, chargetype, id_cc_did) VALUES ".
				           "('" . $id_cc_card . "', '" . $rate . "', '2','" . $id_cc_did . "')";
				$result = $instance_table->SQLExec($FormHandler->DBHandle, $QUERY1, 0);

				$QUERY1 = "UPDATE cc_card set credit = credit -" . $rate . " where id = '" . $id_cc_card . "'";
				$result = $instance_table->SQLExec($FormHandler->DBHandle, $QUERY1, 0);
			}

			$QUERY1 = "UPDATE cc_did set iduser = " . $id_cc_card . ",reserved=1 where id = '" . $id_cc_did . "'";
			$result = $instance_table->SQLExec($FormHandler->DBHandle, $QUERY1, 0);

			$QUERY1 = "UPDATE cc_did_use set releasedate = now() where id_did = '" . $id_cc_did . "' and activated = 0";
			$result = $instance_table->SQLExec($FormHandler->DBHandle, $QUERY1, 0);

			// Should we do something special when billing != 0 or 1?
			$QUERY1 = "INSERT INTO cc_did_use (activated, id_cc_card, id_did, month_payed) values ('1','" . $id_cc_card . "','" . $id_cc_did . "', 1)";
			$result = $instance_table->SQLExec($FormHandler->DBHandle, $QUERY1, 0);
		} 
		// else existing_owner_id is already correctly set due to prior destinations on the same DID
	}

	/*
	 * static public function to release a DID and set the DID use correctly
	 */
	static public function did_destination_del()
	{
		global $A2B;
		$FormHandler = FormHandler::GetInstance();
		$processed = $FormHandler->getProcessed();
		
		$instance_table = new Table();
		$did_destination_id = $processed['id'];

		$QUERY_did = "SELECT cc_did.id AS did_id, dg.dest_count AS destination_count ".
		             "FROM cc_did ".
		             "LEFT JOIN cc_did_destination ON cc_did_destination.id_cc_did = cc_did.id ".
		             "LEFT JOIN ( SELECT st1.id, count(*) AS dest_count ".
		                          "FROM cc_did AS st1 ".
		                          "INNER JOIN cc_did_destination AS st2 ON st2.id_cc_did = st1.id ".
		                          "GROUP BY st1.id ".
		                        ") AS dg ON dg.id = cc_did.id ".
		             "WHERE cc_did_destination.id = '". $did_destination_id ."'";
		// Also possible to do FROM cc_did_destination AS dest1 JOIN cc_did JOIN cc_did_destination AS dest2 GROUP BY dest1.id, cc_did.id
		// To get the count but NULL and NO row behavoir is flaky no matter the types of joins used. Therefore using SubSelect.
		$result_did_dest = $instance_table -> SQLExec($FormHandler->DBHandle, $QUERY_did );

		if (is_array($result_did_dest) && !is_null($result_did_dest[0]['did_id'])) {
			if( $result_did_dest[0]['destination_count'] < 2 ) {
				// Only remove did from card if this is the LAST destination connecting the two.
				// < 2, not 1 because destination is deleted after this call.
				$choose_did = $result_did_dest[0]['did_id'];
				
				$QUERY = "UPDATE cc_did SET iduser = 0, reserved=0 WHERE id=$choose_did";
				$result = $instance_table->SQLExec($FormHandler->DBHandle, $QUERY, 0);

				$QUERY = "UPDATE cc_did_use SET releasedate = now() WHERE id_did =$choose_did and activated = 1";
				$result = $instance_table->SQLExec($FormHandler->DBHandle, $QUERY, 0);

				$QUERY = "INSERT INTO cc_did_use (activated, id_did) VALUES ('0','" . $choose_did . "')";
				$result = $instance_table->SQLExec($FormHandler->DBHandle, $QUERY, 0);
			}
		}
	}
	
	static public function proccessing_billing_customer()
	{
		global $A2B;
		$FormHandler = FormHandler::GetInstance();
		$processed = $FormHandler->getProcessed();
		//find the last billing 
		$card_id = $processed['id_card'];
		$date_bill=$processed['date'];

		//GET VAT
		$card_table = new Table('cc_card', 'vat, typepaid, credit');
		$card_clause = "id = ".$card_id;
		$card_result = $card_table -> Get_list($FormHandler->DBHandle, $card_clause, 0);
		
		if(!is_array($card_result)||empty($card_result[0]['vat'])||!is_numeric($card_result[0]['vat']))
			$vat=0;
		else 
			$vat = $card_result[0][0];
		
		// FIND THE LAST BILLING
		$billing_table = new Table('cc_billing_customer','id,date');
		$clause_last_billing = "id_card = $card_id AND id != ".$FormHandler -> RESULT_QUERY;
		$result = $billing_table -> Get_list($FormHandler->DBHandle, $clause_last_billing,"date","desc");
		$call_table = new Table('cc_call',' COALESCE(SUM(sessionbill),0)' );
		$clause_call_billing ="card_id = $card_id AND ";
		$clause_charge = "id_cc_card = $card_id AND ";
		$desc_billing="";
		$desc_billing_postpaid="";
		$start_date =null;
		
		if (is_array($result) && !empty($result[0][0])) {
			$clause_call_billing .= "stoptime >= '" .$result[0][1]."' AND "; 
			$clause_charge .= "creationdate >= '".$result[0][1]."' AND  ";
			$desc_billing = "Calls cost between the ".$result[0][1]." and  $date_bill" ;
			$desc_billing_postpaid="Amount for period between the ".date("Y-m-d", strtotime($result[0][1]) + $oneday)." and $date_bill";
			$start_date = $result[0][1];
		} else {
			$desc_billing = "Calls cost before the $date_bill" ;
			$desc_billing_postpaid="Amount for period before the $date_bill" ;
		}
		$lastpostpaid_amount = 0;
		$query_table = "cc_billing_customer LEFT JOIN cc_invoice ON cc_billing_customer.id_invoice = cc_invoice.id ";
		$query_table .= "LEFT JOIN (SELECT st1.id_invoice, TRUNCATE(SUM(st1.price),2) as total_price FROM cc_invoice_item AS st1 WHERE st1.type_ext ='POSTPAID' GROUP BY st1.id_invoice ) as items ON items.id_invoice = cc_invoice.id";
		$invoice_table = new Table($query_table,'SUM( items.total_price) as total');
		$lastinvoice_clause = "cc_billing_customer.id_card = $card_id AND cc_invoice.paid_status=0 AND cc_billing_customer.id != ".$FormHandler -> RESULT_QUERY;
		$result_lastinvoice = $invoice_table ->Get_list($FormHandler->DBHandle, $lastinvoice_clause);
		if(is_array($result_lastinvoice)&& !empty($result_lastinvoice[0][0])){
		    $lastpostpaid_amount = $result_lastinvoice [0][0];
		}
		$clause_call_billing .= "stoptime < '$date_bill' ";
		$clause_charge .= "creationdate < '$date_bill' ";


		$result =  $call_table -> Get_list($FormHandler->DBHandle, $clause_call_billing);
		// COMMON BEHAVIOUR FOR PREPAID AND POSTPAID ... GENERATE A RECEIPT FOR THE CALLS OF THE MONTH
		if (is_array($result) && is_numeric($result[0][0])) {
			$amount_calls = $result[0][0];
			$amount_calls = ceil($amount_calls*100)/100;
			$date = date("Y-m-d h:i:s");
			/// create receipt
			$field_insert = "date, id_card, title, description,status";
			$title = gettext("SUMMARY OF CALLS");
			$description = gettext("Summary of the calls charged since the last billing");
			$value_insert = " '$date' , '$card_id', '$title','$description',1";
			$instance_table = new Table("cc_receipt", $field_insert);
			$id_receipt = $instance_table -> Add_table ($FormHandler->DBHandle, $value_insert, null, null,"id");
			if(!empty($id_receipt)&& is_numeric($id_receipt)){
				$description = $desc_billing;
				$field_insert = "date, id_receipt,price,description,id_ext,type_ext";
				$instance_table = new Table("cc_receipt_item", $field_insert);
				$value_insert = " '$date' , '$id_receipt', '$amount_calls','$description','".$FormHandler -> RESULT_QUERY."','CALLS'";
				$instance_table -> Add_table ($FormHandler->DBHandle, $value_insert, null, null,"id");
			}
		}
		// GENERATE RECEIPT FOR CHARGE ALREADY CHARGED 
		$table_charge = new Table("cc_charge", "*");
		$result =  $table_charge -> Get_list($FormHandler->DBHandle, $clause_charge." AND charged_status = 1");
		if (is_array($result)) {
			$field_insert = "date, id_card, title, description,status";
			$title = gettext("SUMMARY OF CHARGE");
			$date = date("Y-m-d h:i:s");
			$description = gettext("Summary of the charge charged since the last billing.");
			$value_insert = " '$date' , '$card_id', '$title','$description',1";
			$instance_table = new Table("cc_receipt", $field_insert);
			$id_receipt = $instance_table -> Add_table ($FormHandler->DBHandle, $value_insert, null, null,"id");
			if(!empty($id_receipt)&& is_numeric($id_receipt)){
				foreach ($result as $charge) {
					$description = gettext("CHARGE :").$charge['description'];
					$amount = $charge['amount'];
					$field_insert = "date, id_receipt,price,description,id_ext,type_ext";
					$instance_table = new Table("cc_receipt_item", $field_insert);
					$value_insert = " '".$charge['creationdate']."' , '$id_receipt', '$amount','$description','".$charge['id']."','CHARGE'";
					$instance_table -> Add_table ($FormHandler->DBHandle, $value_insert, null, null,"id");
				}
			}
		}
		$total =0;
		$total_vat =0;
		// GENERATE INVOICE FOR CHARGE NOT YET CHARGED
		$table_charge = new Table("cc_charge", "*");
		$result =  $table_charge -> Get_list($FormHandler->DBHandle, $clause_charge." AND charged_status = 0 AND invoiced_status = 0");
		$last_invoice = null;
		if (is_array($result) && sizeof($result)>0){
			$reference = generate_invoice_reference();
			$field_insert = "date, id_card, title ,reference, description,status,paid_status";
			$date = date("Y-m-d h:i:s");
			$title = gettext("BILLING CHARGES");
			$description = gettext("This invoice is for some charges unpaid since the last billing.")." ".$desc_billing_postpaid;
			$invoice_title = $title;
			$invoice_reference =$reference;
			$invoice_description = $description;
			$value_insert = " '$date' , '$card_id', '$title','$reference','$description',1,0";
			$instance_table = new Table("cc_invoice", $field_insert);
			$id_invoice = $instance_table -> Add_table ($FormHandler->DBHandle, $value_insert, null, null,"id");
			if(!empty($id_invoice)&& is_numeric($id_invoice)){
			    $last_invoice = $id_invoice;
					    foreach ($result as $charge) {
						    $description = gettext("CHARGE :").$charge['description'];
						    $amount = $charge['amount'];
						    $total = $total + $amount;
						    $total_vat =$total_vat + round($amount *(1+($vat/100)),2);
						    $field_insert = "date, id_invoice,price,vat,description,id_ext,type_ext";
						    $instance_table = new Table("cc_invoice_item", $field_insert);
						    $value_insert = " '".$charge['creationdate']."' , '$id_invoice', '$amount','$vat','$description','".$charge['id']."','CHARGE'";
						    $instance_table -> Add_table ($FormHandler->DBHandle, $value_insert, null, null,"id");
					    }
				    }
		}
		
		// behaviour postpaid
		if($card_result[0]['typepaid']==1 && is_numeric($card_result[0]['credit']) && ($card_result[0]['credit']+$lastpostpaid_amount)<0) {
			
			//GENERATE AN INVOICE TO COMPLETE THE BALANCE
		    if (!empty($last_invoice)) {
			$id_invoice = $last_invoice;
		    } else {
			$reference = generate_invoice_reference();
			$field_insert = "date, id_card, title ,reference, description,status,paid_status";
			$date = date("Y-m-d h:i:s");
			$title = gettext("BILLING POSTPAID");
			$description = gettext("Invoice for POSTPAID");
			$invoice_title = $title;
			$invoice_reference =$reference;
			$invoice_description = $description;
			$value_insert = " '$date' , '$card_id', '$title','$reference','$description',1,0";
			$instance_table = new Table("cc_invoice", $field_insert);
			$id_invoice = $instance_table -> Add_table ($FormHandler->DBHandle, $value_insert, null, null,"id");
		    }
            
			if (!empty($id_invoice)&& is_numeric($id_invoice)) {
				$last_invoice = $id_invoice;
				$description = $desc_billing_postpaid;
				$amount = abs($card_result[0]['credit']+$lastpostpaid_amount);
				$total = $total + $amount;
				$total_vat =$total_vat + round($amount *(1+($vat/100)),2);
				$field_insert = "date, id_invoice,price,vat,description,id_ext,type_ext";
				$instance_table = new Table("cc_invoice_item", $field_insert);
				$value_insert = " '$date' , '$id_invoice', '$amount','$vat','$description','".$FormHandler -> RESULT_QUERY."','POSTPAID'";
				$instance_table -> Add_table ($FormHandler->DBHandle, $value_insert, null, null,"id");
			}
		}
		if (!empty($last_invoice)) {
		    $param_update_billing = "id_invoice = '".$last_invoice."'";
		    $clause_update_billing = " id= ".$FormHandler -> RESULT_QUERY;
		    $billing_table ->Update_table($FormHandler->DBHandle,$param_update_billing,$clause_update_billing);
		}
		//Send a mail for invoice to pay
		if (!empty($last_invoice)) {
		    $total = round($total,2);
		    $mail = new Mail(Mail::$TYPE_INVOICE_TO_PAY, $card_id);
		    $mail->replaceInEmail(Mail::$INVOICE_REFERENCE_KEY, $invoice_reference);
		    $mail->replaceInEmail(Mail::$INVOICE_TITLE_KEY, $invoice_title);
		    $mail->replaceInEmail(Mail::$INVOICE_DESCRIPTION_KEY, $invoice_description);
		    $mail->replaceInEmail(Mail::$INVOICE_TOTAL_KEY, $total);
		    $mail->replaceInEmail(Mail::$INVOICE_TOTAL_VAT_KEY, $total_vat);
		    $mail -> send();
		}

		//Update billing ...
		if (!empty($start_date)) {
				$param_update_billing = "start_date = '".$start_date."'";
				$clause_update_billing = " id= ".$FormHandler -> RESULT_QUERY;
				$billing_table ->Update_table($FormHandler->DBHandle,$param_update_billing,$clause_update_billing);
		}
	}
	
	
	static public function create_invoice_after_refill()
	{
		global $A2B;
		$FormHandler = FormHandler::GetInstance();
		$processed = $FormHandler->getProcessed();
		
		if ($processed['added_invoice']==1) {
			//CREATE AND UPDATE REF NUMBER
			$list_refill_type=Constants::getRefillType_List();
			$refill_type = $processed['refill_type'];
			$reference = generate_invoice_reference();
			$field_insert = "date, id_card, title ,reference, description";
			$date = $processed['date'];
			$card_id = $processed['card_id'];
			if($refill_type!=0){
				$title = $list_refill_type[$refill_type][0]." ".gettext("REFILL");
			}else{
				$title = gettext("REFILL");
			}
			$description = gettext("Invoice for refill");
			
			$value_insert = " '$date' , '$card_id', '$title','$reference','$description' ";
			$instance_table = new Table("cc_invoice", $field_insert);
			$id_invoice = $instance_table -> Add_table ($FormHandler->DBHandle, $value_insert, null, null,"id");
			//load vat of this card
			if(!empty($id_invoice)&& is_numeric($id_invoice)){
				$amount = $processed['credit'];
				$description = $processed['description'];
				$card_table = new Table('cc_card','vat');
				$card_clause = "id = ".$card_id;
				$card_result = $card_table -> Get_list($FormHandler->DBHandle, $card_clause, 0);
				if(!is_array($card_result)||empty($card_result[0][0])||!is_numeric($card_result[0][0])) $vat=0;
				else $vat = $card_result[0][0];
				$field_insert = "date, id_invoice ,price,vat, description";
				$instance_table = new Table("cc_invoice_item", $field_insert);
				$value_insert = " '$date' , '$id_invoice', '$amount','$vat','$description' ";
				$instance_table -> Add_table ($FormHandler->DBHandle, $value_insert, null, null,"id");
			}
		}	
	}
	
	 
	static public function create_invoice_reference()
	{
		global $A2B;
		$FormHandler = FormHandler::GetInstance();
		$processed = $FormHandler->getProcessed();
		$id_invoice = $FormHandler -> RESULT_QUERY;
		//CREATE AND UPDATE REF NUMBER
		$reference = generate_invoice_reference();
		$instance_table_invoice = new Table("cc_invoice");
		$param_update_invoice = "reference = '".$reference."'";
		$clause_update_invoice = " id ='$id_invoice'";
		$instance_table_invoice-> Update_table ($FormHandler->DBHandle, $param_update_invoice, $clause_update_invoice, $func_table = null);
		
	}
	
	
	/**
     * Function create_refill
     * @public
     */
	static public function create_refill_after_payment()
	{ 
		global $A2B;
		$FormHandler = FormHandler::GetInstance();
		$processed = $FormHandler->getProcessed();
		if ($processed['added_refill']==1) {
			$id_payment = $FormHandler -> RESULT_QUERY;
			// CREATE REFILL
			$field_insert = "date, credit, card_id ,refill_type, description";
			$date = $processed['date'];
			$credit = $processed['payment'];
			$card_id = $processed['card_id'];
			$refill_type= $processed['payment_type'];
			$description = $processed['description'];
            $card_table = new Table('cc_card','vat');
            $card_clause = "id = ".$card_id;
            $card_result = $card_table -> Get_list($FormHandler->DBHandle, $card_clause, 0);
            if(!is_array($card_result)||empty($card_result[0][0])||!is_numeric($card_result[0][0]))
            	$vat=0;
            else
            	$vat = $card_result[0][0];
            $credit_without_vat = $credit / (1+$vat/100);
            
			$value_insert = " '$date' , '$credit_without_vat', '$card_id','$refill_type', '$description' ";
			$instance_sub_table = new Table("cc_logrefill", $field_insert);
			$id_refill = $instance_sub_table -> Add_table ($FormHandler->DBHandle, $value_insert, null, null,"id");	
			// REFILL CARD .. UPADTE CARD
			$instance_table_card = new Table("cc_card");
			$param_update_card = "credit = credit + '".$credit_without_vat."'";
			$clause_update_card = " id='$card_id'";
			$instance_table_card -> Update_table ($FormHandler->DBHandle, $param_update_card, $clause_update_card, $func_table = null);
			//LINK THE REFILL TO THE PAYMENT .. UPADTE PAYMENT
			$instance_table_pay = new Table("cc_logpayment");
			$param_update_pay = "id_logrefill = '".$id_refill."'";
			$clause_update_pay = " id ='$id_payment'";
			$instance_table_pay-> Update_table ($FormHandler->DBHandle, $param_update_pay, $clause_update_pay, $func_table = null);
		
			// Create invoice associated
		
			// CREATE AND UPDATE REF NUMBER
			$list_refill_type=Constants::getRefillType_List();
			$refill_type = $processed['payment_type'];
			$year = date("Y");
			$invoice_conf_table = new Table('cc_invoice_conf','value');
			$conf_clause = "key_val = 'count_$year'";
			$result = $invoice_conf_table -> Get_list($FormHandler->DBHandle, $conf_clause, 0);
			if (is_array($result) && !empty($result[0][0])) {
				// update count
				$count =$result[0][0];
				if(!is_numeric($count)) $count=0;
				$count++;
				$param_update_conf = "value ='".$count."'";
				$clause_update_conf = "key_val = 'count_$year'";
				$invoice_conf_table -> Update_table ($FormHandler->DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);
			} else {
				// insert newcount
				$count=1;
				$QUERY= "INSERT INTO cc_invoice_conf (key_val ,value) VALUES ( 'count_$year', '1');";
				$invoice_conf_table -> SQLExec($FormHandler->DBHandle,$QUERY);
			}
			$field_insert = "date, id_card, title ,reference, description,status,paid_status";
			if($refill_type!=0) {
				$title = $list_refill_type[$refill_type][0]." ".gettext("REFILL");
			} else {
				$title = gettext("REFILL");
			}
			$description = gettext("Invoice for refill");
			$reference = $year.sprintf("%08d",$count);
			$value_insert = " '$date' , '$card_id', '$title','$reference','$description','1','1' ";
			$instance_table = new Table("cc_invoice", $field_insert);
			$id_invoice = $instance_table -> Add_table ($FormHandler->DBHandle, $value_insert, null, null,"id");
			//add payment to this invoice
			$field_insert = "id_invoice, id_payment";
			$value_insert = "'$id_invoice' , '$id_payment'";
			$instance_table = new Table("cc_invoice_payment", $field_insert);
			$instance_table -> Add_table ($FormHandler->DBHandle, $value_insert, null, null);
			//load vat of this card
			if (!empty($id_invoice) && is_numeric($id_invoice)) {
				$description = $processed['description'];
				$field_insert = "date, id_invoice ,price,vat, description";
				$instance_table = new Table("cc_invoice_item", $field_insert);
				$value_insert = " '$date' , '$id_invoice', '$credit_without_vat','$vat','$description' ";
				$instance_table -> Add_table ($FormHandler->DBHandle, $value_insert, null, null,"id");
			}
		}
		
		if($processed['added_commission']==1) {
			$card_id = $processed['card_id'];
			$table_transaction = new Table();
			$result_agent = $table_transaction -> SQLExec($FormHandler->DBHandle,"SELECT cc_card_group.id_agent FROM cc_card LEFT JOIN cc_card_group ON cc_card_group.id = cc_card.id_group WHERE cc_card.id = $card_id");
			
			if (is_array($result_agent)&& !is_null($result_agent[0]['id_agent']) && $result_agent[0]['id_agent']>0 ) {
				
				// test if the agent exist and get its commission
				$id_agent = $result_agent[0]['id_agent'];
				// update refill & payment to keep a trace of agent in the timeline
				$table_refill = new Table("cc_logrefill");
				$table_payment = new Table("cc_logpayment");
				$param_update = "agent_id = '".$id_agent."'";
				if(!empty($id_refill)){
					$clause_update_refill_agent = " id ='$id_refill'";
					$table_refill-> Update_table ($FormHandler->DBHandle, $param_update, $clause_update_refill_agent, $func_table = null);
				}
				$clause_update_payment_agent = " id ='$id_payment'";
				$table_payment-> Update_table ($FormHandler->DBHandle, $param_update, $clause_update_payment_agent, $func_table = null);
				
				$agent_table = new Table("cc_agent", "commission");
				$agent_clause = "id = ".$id_agent;
				$result_agent= $agent_table -> Get_list($FormHandler->DBHandle,$agent_clause);
				
				if (is_array($result_agent) && is_numeric($result_agent[0]['commission']) && $result_agent[0]['commission']>0) {
					$field_insert = "id_payment, id_card, amount,description,id_agent";
					$commission = a2b_round($processed['payment'] * ($result_agent[0]['commission']/100));
					$description_commission = gettext("AUTOMATICALY GENERATED COMMISSION!");
					$description_commission.= "\nID CARD : ".$card_id;
					$description_commission.= "\nID PAYMENT : ".$id_payment;
					$description_commission.= "\nPAYMENT AMOUNT: ".$amount_paid;
					$description_commission.= "\nCOMMISSION APPLIED: ".$result_agent[0]['commission'];
					$value_insert = "'".$id_payment."', '$card_id', '$commission','$description_commission','$id_agent'";
					$commission_table = new Table("cc_agent_commission", $field_insert);
					$id_commission = $commission_table -> Add_table ($FormHandler->DBHandle, $value_insert, null, null,"id");
					$table_agent = new Table('cc_agent');
					$param_update_agent = "com_balance = com_balance + '".$commission."'";
					$clause_update_agent = " id='".$id_agent."'";
					$table_agent -> Update_table ($FormHandler->DBHandle, $param_update_agent, $clause_update_agent, $func_table = null);
				}
			}	
		}
	}

	static public function create_agent_refill()
	{
		global $A2B;
		$FormHandler = FormHandler::GetInstance();
		$processed = $FormHandler->getProcessed();
		
		if ($processed['added_refill']==1) {
			$id_payment = $FormHandler -> RESULT_QUERY;
			
			//CREATE REFILL
			$field_insert = "date, credit, agent_id ,refill_type, description";
			$date = $processed['date'];
			$credit = $processed['payment'];
			$agent_id = $processed['agent_id'];
			$refill_type= $processed['payment_type'];
			$description = $processed['description'];
			$value_insert = " '$date' , '$credit', '$agent_id','$refill_type', '$description' ";
			$instance_sub_table = new Table("cc_logrefill_agent", $field_insert);
			$id_refill = $instance_sub_table -> Add_table ($FormHandler->DBHandle, $value_insert, null, null,"id");	
			
			//REFILL AGENT .. UPADTE AGENT
			$instance_table_agent = new Table("cc_agent");
			$param_update_agent = "credit = credit + '".$credit."'";
			$clause_update_agent = " id='$agent_id'";
			$instance_table_agent -> Update_table ($FormHandler->DBHandle, $param_update_agent, $clause_update_agent, $func_table = null);
			
			//LINK THE REFILL TO THE PAYMENT .. UPADTE PAYMENT
			$instance_table_pay = new Table("cc_logpayment_agent");
			$param_update_pay = "id_logrefill = '".$id_refill."'";
			$clause_update_pay = " id ='$id_payment'";
			$instance_table_pay-> Update_table ($FormHandler->DBHandle, $param_update_pay, $clause_update_pay, $func_table = null);
		}
	}
	
	
	/**
     * Function to edit the fields
     * @public
     */
	static public function create_sipiax_friends()
	{
		global $A2B;
		$FormHandler = FormHandler::GetInstance();
		$processed = $FormHandler->getProcessed();
		$id = $FormHandler -> RESULT_QUERY; // DEFINED BEFORE FG_ADDITIONAL_FUNCTION_AFTER_ADD		
		$sip = stripslashes($processed['sip_buddy']);
		$iax = stripslashes($processed['iax_buddy']);
		
		// $FormHandler -> FG_QUERY_EXTRA_HIDDED - username, useralias, uipass, loginkey
		if (strlen($FormHandler -> FG_QUERY_EXTRA_HIDDED[0])>0) {
			$username 	= $FormHandler -> FG_QUERY_EXTRA_HIDDED[0];
			$uipass 	= $FormHandler -> FG_QUERY_EXTRA_HIDDED[2];
			$useralias 	= $FormHandler -> FG_QUERY_EXTRA_HIDDED[1];
		} else {
			$username 	= $processed['username'];
			$uipass 	= $processed['uipass'];
			$useralias 	= $processed['useralias'];
		}
		
		$instance_realtime = new Realtime();
		
		$instance_realtime -> insert_voip_config ($sip, $iax, $id, $username, $uipass);
		
		// Save info in table and in sip file
		if ($sip == 1) {
			$instance_realtime -> create_trunk_config_file ('sip');
		}
		
		// Save info in table and in iax file
		if ($iax == 1) {
			$instance_realtime -> create_trunk_config_file ('iax');
		}
	}

	static public function create_lock_card()
	{
		global $A2B;
		$FormHandler = FormHandler::GetInstance();
		$processed = $FormHandler->getProcessed();
		$id = $FormHandler -> RESULT_QUERY;
		if($processed['block'] == 1) {
			$instance_sub_table = new Table("cc_card");
			$param_update_card = "lock_date = NOW()";
			$clause_update_card = "id = $id";
			$instance_sub_table -> Update_table ($FormHandler->DBHandle, $param_update_card, $clause_update_card, $func_table = null);
		}
	}
	
	static public function change_card_lock()
	{
		global $A2B;
		$FormHandler = FormHandler::GetInstance();
		$processed = $FormHandler->getProcessed();
		$instance_sub_table = new Table("cc_card", "block");
		$FG_TABLE_CLAUSE_CARD = "id = ".$processed['id'];
		$card_info = $instance_sub_table -> Get_list ($FormHandler -> DBHandle, $FG_TABLE_CLAUSE_CARD, null, null, null, null, null, null);
		if (is_array($result) && !empty($result[0][0])) {
			$card_lock_info = $card_info[0][0];
		
			if ($card_lock_info != $processed['block'] && $processed['block'] == 1) {
				$param_update_card = "lock_date = NOW()";
				$clause_update_card = "id = ".$processed['id'];
				$instance_sub_table -> Update_table ($FormHandler->DBHandle, $param_update_card, $clause_update_card, $func_table = null);
			}
		}
	}
	
	/**
	 * Function to added new sign-ups in the notification
	 * @public
	 */
	static public function create_notification_signup()
	{
		global $A2B;
		$FormHandler = FormHandler::GetInstance();
		$id_card = $FormHandler -> RESULT_QUERY;
		NotificationsDAO::AddNotification("added_new_signup",Notification::$MEDIUM,Notification::$CUST,$id_card,Notification::$LINK_CARD,$id_card);
	}
}

?>
