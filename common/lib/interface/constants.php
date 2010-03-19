<?php

class Constants
{
	public function reverse_array($arr) {
		$reverted_arr = array();
		foreach ($arr as $ind => $val_arr) {
			$reverted_arr[$ind] = array($val_arr[1], $val_arr[0]);
		}
		return $reverted_arr;
	}
	
	public static function getMsgTypeList(){
		$msgtype_list = array();
		$msgtype_list["0"] = array( gettext("INFO"),"0","msg_info");
		$msgtype_list["1"] = array( gettext("SUCCESS"),"1","msg_success");
		$msgtype_list["2"] = array( gettext("WARNING"),"2","msg_warning");
		$msgtype_list["3"] = array( gettext("ERROR"),"3","msg_error");
		return $msgtype_list;
	}

	public static function getLanguagesList(){
		$language_list = array();
		$language_list["0"] = array( gettext("ENGLISH"), "en");
		$language_list["1"] = array( gettext("SPANISH"), "es");
		$language_list["2"] = array( gettext("FRENCH"),  "fr");
		$language_list["3"] = array( gettext("RUSSIAN"), "ru");
		$language_list["4"] = array( gettext("BRAZILIAN"), "br");
		return $language_list;
	}
	
	public static function getLanguagesRevertList() {
		return Constants::reverse_array(Constants::getLanguagesList());
	}
	
	public static function getLanguages(){
		$language_list = array();
		$language_list["en"] = array( gettext("ENGLISH"));
		$language_list["es"] = array( gettext("SPANISH"));
		$language_list["fr"] = array( gettext("FRENCH"));
		$language_list["ru"] = array( gettext("RUSSIAN"));
		$language_list["br"] = array( gettext("BRAZILIAN"));
		return $language_list;
	}
	
	public static function getRestrictionList(){
		$restriction_list = array();
		$restriction_list["0"] = array( gettext("NONE RESTRICTION USED"), "0");
		$restriction_list["1"] = array( gettext("CAN'T CALL RESTRICTED NUMBERS"), "1");
		$restriction_list["2"] = array( gettext("CAN ONLY CALL RESTRICTED NUMBERS"),  "2");
		return $restriction_list;
	}
	public static function getComponentUserTypeList(){
		$usertype_list = array();
		$usertype_list["0"] = array( gettext("CUSTOMERS"), "0");
		$usertype_list["1"] = array( gettext("AGENTS"), "1");
		$usertype_list["2"] = array( gettext("CUSTOMERS AND AGENTS"),  "2");
		return $usertype_list;
	}
	
	public static function getYesNoList(){
		$yesno = array();
		$yesno["1"] = array( gettext("Yes"), "1");
		$yesno["0"] = array( gettext("No"), "0");
		return $yesno;
	}	

	public static function getCallbackStatusList(){
		$status_list = array();
		$status_list["PENDING"] = array( gettext("PENDING"), "PENDING");
		$status_list["SENT"] = array( gettext("SENT"), "SENT");
		$status_list["ERROR"] = array( gettext("ERROR"), "ERROR");
		
		return $status_list;
	}	
	
	public static function getPeriodsList(){
	  	$period_list = array();
		$period_list["1"]  = array( "Hourly", "1");
		$period_list["2"]  = array( "Daily", "2");
		$period_list["3"]  = array( "Weekly", "3");
		$period_list["4"]  = array( "Monthly", "4");
		return $period_list;
	}

	public static function getActivationList(){
		$actived_list = array();
		$actived_list["0"] = array( gettext("Inactive"), "0");
		$actived_list["1"] = array( gettext("Active"), "1");
		return $actived_list;
	}

	public static function getActivation_Revert_List(){
		return Constants::reverse_array(Constants::getActivationList());
	}

	public static function getActivationTrueFalseList(){
		$actived_list = array();
		$actived_list["t"] = array( "Active", "t");
		$actived_list["f"] = array( "Inactive", "f");
		return $actived_list;
	}
	
	public static function getActivationTrueFalse_Revert_List() {
		return Constants::reverse_array(Constants::getActivationTrueFalseList());
	}

	public static function getBillingTypeList(){	
		$billingtype_list = array();
		$billingtype_list["0"] = array( gettext("Fix per month + dialoutrate"), "0");
		$billingtype_list["1"] = array( gettext("Fix per month"), "1");
		$billingtype_list["2"] = array( gettext("Only dialout rate"), "2"); 
		$billingtype_list["3"]  = array( gettext("Free"), "3");
		return $billingtype_list;
	}

	public static function getBillingTypeShortList(){
		$billingtype_list_short = array();
		$billingtype_list_short["0"] = array( gettext("Fix+Dial"), "0");
		$billingtype_list_short["1"] = array( gettext("Fix"), "1");
		$billingtype_list_short["2"] = array( gettext("Dial"), "2");
		$billingtype_list_short["3"] = array( gettext("Free"), "3");
		return $billingtype_list_short;
	}

	public static function getSimultAccessList(){
		$billingtype_list_short = array();
	  	$simultaccess_list["0"] = array( gettext("INDIVIDUAL ACCESS"), "0");
		$simultaccess_list["1"] = array( gettext("SIMULTANEOUS ACCESS"), "1");
		return $billingtype_list_short;
	}

	public static function getPaidTypeList(){
		$typepaid_list = array();
		$typepaid_list["0"] = array( gettext("PREPAID CARD"), "0");
		$typepaid_list["1"] = array( gettext("POSTPAID CARD"), "1");
		return $typepaid_list;
	}
	
	public static function getPaidTypeList_Revert_List(){
		return Constants::reverse_array(Constants::getPaidTypeList());
	}

	public static function getExpirationList(){
		$expire_list = array();
		$expire_list["0"] = array( gettext("NO EXPIRATION"), "0");
		$expire_list["1"] = array( gettext("EXPIRE DATE"), "1");
		$expire_list["2"] = array( gettext("EXPIRE DAYS SINCE FIRST USE"), "2");
		$expire_list["3"] = array( gettext("EXPIRE DAYS SINCE CREATION"), "3");
		return $expire_list;
	}

	
	public static function getInvoiceStatusList(){
		$invoice_status_list = array();
		$invoice_status_list['0'] = array( gettext('OPEN'), '0');
		$invoice_status_list['1'] = array( gettext('CLOSE'), '1');
		return $invoice_status_list;
	}
	
	public static function getInvoiceStatusList_Revert_List() {
		return Constants::reverse_array(Constants::getInvoiceStatusList());
	}
	
	public static function getBillingInvoiceStatusList(){
		$invoice_status_list = array();
		$invoice_status_list['0'] = array( gettext('OPEN'), '0');
		$invoice_status_list['1'] = array( gettext('CLOSE'), '1');
		$invoice_status_list['2'] = array( '', '');
		return $invoice_status_list;
	}
	
	public static function getInvoicePaidStatusList(){
		$invoice_status_list = array();
		$invoice_status_list['0'] = array( gettext('UNPAID'), '0');
		$invoice_status_list['1'] = array( gettext('PAID'), '1');
		return $invoice_status_list;
	}

	public static function getInvoicePaidStatusList_Revert_List(){
		return Constants::reverse_array(Constants::getInvoicePaidStatusList());
	}

    public static function getSubscriptionPaidStatusList(){
		$subscription_status_list = array();
		$subscription_status_list['0'] = array( gettext('FIRSTUSE'), '0');
		$subscription_status_list['1'] = array( gettext('BILLED'), '1');
        $subscription_status_list['2'] = array( gettext('PAID'), '2');
        $subscription_status_list['3'] = array( gettext('UNPAID'), '3');
		return $subscription_status_list;
	}
	
	public static function getMonth(){
		$month_list = array();
		$month_list['1'] = array( gettext('January'), '1');
		$month_list['2'] = array( gettext('February'), '2');
		$month_list['3'] = array( gettext('March'), '3');
		$month_list['4'] = array( gettext('April'), '4');
		$month_list['5'] = array( gettext('May'), '5');
		$month_list['6'] = array( gettext('June'), '6');
		$month_list['7'] = array( gettext('July'), '7');
		$month_list['8'] = array( gettext('August'), '8');
		$month_list['9'] = array( gettext('September'), '9');
		$month_list['10'] = array( gettext('October'), '10');
		$month_list['11'] = array( gettext('November'), '11');
		$month_list['12'] = array( gettext('December'), '12');
		return $month_list;
	}
	
	public static function getPaymentStatusList(){
		$payment_status_list = array();
		$payment_status_list['0'] = array( gettext('UNPAID'), '0');
		$payment_status_list['1'] = array( gettext('SENT-UNPAID'), '1');
		$payment_status_list['2'] = array( gettext('SENT-PAID'), '2');
		$payment_status_list['3'] = array( gettext('PAID'), '3');
		return $payment_status_list;
	}
	
	public static function getPaymentStateList(){
		$status_list = array();
		$status_list = array();
		$status_list["0"] = array( gettext("New"), "0");
		$status_list["1"] = array( gettext("Proceed"), "1");
		$status_list["2"] = array( gettext("In Process"), "2"); 
		return $status_list;
	}
	
	public static function getEmailStatusList(){
		$status_list = array();
		$status_list['0'] = array( gettext('Failed'), '0');
		$status_list['1'] = array( gettext('Successful'), '1');
		$status_list['']  = array( gettext('Not Sent'), '');
		return $status_list;
	}

	public static function getPackagesTypeList(){
		$packagetype_list = array();
		$packagetype_list["0"] = array( gettext("Unlimited calls"), "0");
		$packagetype_list["1"] = array( gettext("Number of Free calls"), "1");
		$packagetype_list["2"] = array( gettext("Free seconds"), "2");
		return $packagetype_list;
	}
	
	public static function getBillingPeriodsList(){
		$billingtype_list = array();
		$billingtype_list["0"] = array( gettext("Monthly"), "0");
		$billingtype_list["1"] = array( gettext("Weekly"), "1");
		return $billingtype_list;
	}
	
	public static function getLcTypesList(){
		$lcrtype_list = array();
		$lcrtype_list["0"] = array( gettext("LCR : According to the buyer price"), "0");
		$lcrtype_list["1"] = array( gettext("LCD : According to the seller price"), "1");
		return $lcrtype_list;
	}

	public static function getLcShortTypesList(){
		$lcrtype_list_short = array();
		$lcrtype_list_short["0"] = array( gettext("LCR : buyer price"), "0");
		$lcrtype_list_short["1"] = array( gettext("LCD : seller price"), "1");
		return $lcrtype_list_short;
	}

	public static function getTicketPriorityList(){
		$priority_list = array();
		$priority_list["0"] = array( gettext("NONE"), "0");
		$priority_list["1"] = array( gettext("LOW"), "1");
		$priority_list["2"] = array( gettext("MEDIUM"), "2");
		$priority_list["3"] = array( gettext("HIGH"), "3");
		return $priority_list;
	}
	
	public static function getTicketViewedList(){
		$viewed_list = array();
		$viewed_list["0"] = array( gettext('VIEWED'), "0");
		$viewed_list["1"] = array( '<strong style="font-size:8px; color:#B00000; background-color:white; border:solid 1px;"> &nbsp;'.gettext('NEW').'&nbsp;</strong>', '1');
		// $viewed_list["1"] = array( "<img src='".Images_Path."/eye.png' border='0' title='".gettext("Not Viewed")."' alt='".gettext("Not Viewed")."' ", "1");
		return $viewed_list;
	}

	public static function getUsedList(){
		$used_list = array();
		$used_list["0"] = array( gettext("NOT USED"), "0");
		$used_list["1"] = array( gettext("USED"), "1");
		return $used_list;
	}
	
	public static function getUsed_revert_List() {
		return Constants::reverse_array(Constants::getUsedList());
	}
	
	public static function getDialStatusList(){
		$dialstatus_list = array();
		$dialstatus_list["1"] = array( gettext("ANSWER")		, "1");
		$dialstatus_list["2"] = array( gettext("BUSY")			, "2");
		$dialstatus_list["3"] = array( gettext("NOANSWER")		, "3");
		$dialstatus_list["4"] = array( gettext("CANCEL")		, "4");
		$dialstatus_list["5"] = array( gettext("CONGESTION")	, "5");
		$dialstatus_list["6"] = array( gettext("CHANUNAVAIL")	, "6");
		$dialstatus_list["7"] = array( gettext("DONTCALL")		, "7");
		$dialstatus_list["8"] = array( gettext("TORTURE")		, "8");
		$dialstatus_list["9"] = array( gettext("INVALIDARGS")	, "9");
		return $dialstatus_list;
	}
	
	public static function getDialStatus_Revert_List(){
		$dialstatus_rev_list = array();
		$dialstatus_rev_list["ANSWER"] 		= 1;
		$dialstatus_rev_list["BUSY"] 		= 2;
		$dialstatus_rev_list["NOANSWER"] 	= 3;
		$dialstatus_rev_list["CANCEL"] 		= 4;
		$dialstatus_rev_list["CONGESTION"] 	= 5;
		$dialstatus_rev_list["CHANUNAVAIL"] = 6;
		$dialstatus_rev_list["DONTCALL"] 	= 7;
		$dialstatus_rev_list["TORTURE"] 	= 8;
		$dialstatus_rev_list["INVALIDARGS"] = 9;
		return $dialstatus_rev_list;
	}

	public static function getCardStatus_List(){
		$cardstatus_list = array();
		$cardstatus_list["1"]  = array( gettext("ACTIVE"), "1");
		$cardstatus_list["0"]  = array( gettext("CANCELLED"), "0");
		$cardstatus_list["2"]  = array( gettext("NEW"), "2");
		$cardstatus_list["3"]  = array( gettext("WAITING-MAILCONFIRMATION"), "3");
		$cardstatus_list["4"]  = array( gettext("RESERVED"), "4");
		$cardstatus_list["5"]  = array( gettext("EXPIRED"), "5");
		$cardstatus_list["6"]  = array( gettext("SUSPENDED FOR UNDERPAYMENT"), "6");
		$cardstatus_list["7"]  = array( gettext("SUSPENDED FOR LITIGATION"), "7");
        $cardstatus_list["8"]  = array( gettext("WAITING SUBSCRIPTION PAYMENT"), "8");
		return $cardstatus_list;
	}
	
	public static function getCardStatus_Revert_List() {
		return Constants::reverse_array(Constants::getCardStatus_List());
	}
	
	public static function getCardStatus_Acronym_List(){
		$cardstatus_list_acronym = array();
		$cardstatus_list_acronym["0"]  = array( "<acronym title=\"".gettext("CANCELLED")."\">".gettext("CANCEL")."</acronym>", "0");
		$cardstatus_list_acronym["1"]  = array( "<acronym title=\"".gettext("ACTIVATED")."\">".gettext("ACTIVATED")."</acronym>", "1");
		$cardstatus_list_acronym["2"]  = array( "<acronym title=\"".gettext("NEW")."\">".gettext("NEW")."</acronym>", "2");
		$cardstatus_list_acronym["3"]  = array( "<acronym title=\"".gettext("WAITING-MAILCONFIRMATION")."\">".gettext("WAITING")."</acronym>", "3");
		$cardstatus_list_acronym["4"]  = array( "<acronym title=\"".gettext("RESERVED")."\">".gettext("RESERV")."</acronym>", "4");
		$cardstatus_list_acronym["5"]  = array( "<acronym title=\"".gettext("EXPIRED")."\">".gettext("EXPIRED")."</acronym>", "5");
		$cardstatus_list_acronym["6"]  = array( "<acronym title=\"".gettext("SUSPENDED FOR UNDERPAYMENT")."\">".gettext("SUS-PAY")."</acronym>", "6");
		$cardstatus_list_acronym["7"]  = array( "<acronym title=\"".gettext("SUSPENDED FOR LITIGATION")."\">".gettext("SUS-LIT")."</acronym>", "7");
		$cardstatus_list_acronym["8"]  = array( "<acronym title=\"".gettext("WAITING-SUBSCRIPTION-PAYMENT")."\">".gettext("WAIT-PAY")."</acronym>", "8");
		return $cardstatus_list_acronym;
	}
	
	public static function getCardStatus_VT_List(){
		$cardstatus_list = array();
		$cardstatus_list["1"]  = array( gettext("ACTIVE"), "1");
		$cardstatus_list["0"]  = array( gettext("CANCELLED"), "0");
		return $cardstatus_list;
	}
	
	public static function getCardStatus_VT_Revert_List() {
		return Constants::reverse_array(Constants::getCardStatus_VT_List());
	}
	
	public static function getCardStatus_VT_Acronym_List() {
		$cardstatus_list_acronym = array();
		$cardstatus_list_acronym["0"]  = array( "<acronym title=\"".gettext("CANCELLED")."\">".gettext("CANCEL")."</acronym>", "0");
		$cardstatus_list_acronym["1"]  = array( "<acronym title=\"".gettext("ACTIVATED")."\">".gettext("ACTIVATED")."</acronym>", "1");
		return $cardstatus_list_acronym;
	}
	
	
	public static function getCardAccess_List() {
		$simultaccess_list = array();
		$simultaccess_list["1"] = array( gettext("SIMULTANEOUS ACCESS"), "1");
		$simultaccess_list["0"] = array( gettext("INDIVIDUAL ACCESS"), "0");
		return $simultaccess_list;
	}
	
	public static function getCardAccess_Revert_List() {
		return Constants::reverse_array(Constants::getCardAccess_List());
	}
	
	public static function getCardExpire_List(){
		$expire_list = array();
		$expire_list["0"]  = array( gettext("NO EXPIRY"), "0");
		$expire_list["1"]  = array( gettext("EXPIRE DATE"), "1");
		$expire_list["2"]  = array( gettext("EXPIRE DAYS SINCE FIRST USE"), "2");
		$expire_list["3"]  = array( gettext("EXPIRE DAYS SINCE CREATION"), "3");
		return $expire_list;
	}
	
	public static function getMonitorQueryType_List(){
		$mquery_type_list = array();
		$mquery_type_list["1"]  = array( gettext("SQL"),"1");
		$mquery_type_list["2"]  = array( gettext("SHELL SCRIPT"),"2");
		return $mquery_type_list;
	}

	public static function getMonitorResultType_List(){
		$mresult_type_list = array();
		$mresult_type_list["1"]  = array( gettext("TEXT2SPEECH"), "1");
		$mresult_type_list["2"]  = array( gettext("UNIXTIME"), "2");
		$mresult_type_list["3"]  = array( gettext("NUMBER"), "3");
		$mresult_type_list["4"]  = array( gettext("DIGIT"), "4");
		return $mresult_type_list;
	}
	
	public static function getRefillType_List(){
		$refill_type_list = array();
		$refill_type_list["0"]  = array( gettext("AMOUNT"),"0");
		$refill_type_list["1"]  = array( gettext("CORRECTION"),"1");
		$refill_type_list["2"]  = array( gettext("EXTRA FEE"),"2");
		$refill_type_list["3"]  = array( gettext("AGENT REFUND"),"3");
		return $refill_type_list;
	}
	
	public static function getRemittanceType_List(){
		$remittance_type_list = array();
		$remittance_type_list["0"]  = array( gettext("TO BALANCE"),"0");
		$remittance_type_list["1"]  = array( gettext("TO BANK"),"1");
		return $remittance_type_list;
	}
	
	public static function getRemittanceType_Revert_List() {
		return Constants::reverse_array(Constants::getRemittanceType_List());
	}
	
	public static function getRemittanceStatus_List() {
		$remittance_type_list = array();
		$remittance_type_list["0"]  = array( gettext("WAITING"),"0");
		$remittance_type_list["1"]  = array( gettext("ACCEPTED"),"1");
		$remittance_type_list["2"]  = array( gettext("REFUSED"),"2");
		$remittance_type_list["3"]  = array( gettext("CANCELLED"),"3");
		return $remittance_type_list;
	}
	
	public static function getRemittanceStatus_Revert_List() {
		return Constants::reverse_array(Constants::getRemittanceStatus_List());
	}
	
	public static function getInvoiceDay_List(){
		$invoiceday_list = array();
		for ($k=1;$k<=28;$k++)
			$invoiceday_list["$k"]  = array("$k", "$k");
		return $invoiceday_list;
	}
	
	public static function getDiscount_List(){
		$discount_list  = array();
		$discount_list["0.00"] = array( gettext("NO DISCOUNT"),"0.00");
		for($i=1;$i<=99;$i++) {
			$discount_list[$i.".00"]=array($i."%",$i.".00");
		}
		return $discount_list;
	}
	
	public static function getLimitNotify_List($A2B){
		// Possible value to notify the user
		$values = explode(":", $A2B->config['notifications']['values_notifications']);
		$limits_notify = array();
		$idx = 0;
		foreach ($values as $val) {
		 	$limits_notify [$idx] = array($val,$val);
			$idx++;
		}
		return $limits_notify;
	}
	
	public static function getMusicOnHold_List(){
		$musiconhold_list = array();
		$musiconhold_list[] = array( "No MusicOnHold", "");
		for ($i=1;$i<=NUM_MUSICONHOLD_CLASS;$i++) {
			$musiconhold_list[]  = array( "MUSICONHOLD CLASS ACC_$i", "acc_$i");
		}
		return $musiconhold_list;
	}
	
}

