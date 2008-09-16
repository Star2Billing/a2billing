<?php

class Constants
{
	
	public static function getLanguagesList(){
		$language_list = array();
		$language_list["0"] = array( gettext("ENGLISH"), "en");
		$language_list["1"] = array( gettext("SPANISH"), "es");
		$language_list["2"] = array( gettext("FRENCH"),  "fr");
		return $language_list;
	}

	public static function getYesNoList(){
		$yesno = array();
		$yesno["1"] = array( gettext("Yes"), "1");
		$yesno["0"] = array( gettext("No"), "0");
		return $yesno;
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

	public static function getActivationTrueFalseList(){
		$actived_list = array();
		$actived_list["t"] = array( "Active", "t");
		$actived_list["f"] = array( "Disactive", "f");
		return $actived_list;
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
		$typepaid_list["1"] = array( gettext("POSTPAY CARD"), "1");
		return $typepaid_list;
	}

	public static function getExpirationList(){
		$expire_list = array();
		$expire_list["0"] = array( gettext("NO EXPIRATION"), "0");
		$expire_list["1"] = array( gettext("EXPIRE DATE"), "1");
		$expire_list["2"] = array( gettext("EXPIRE DAYS SINCE FIRST USE"), "2");
		$expire_list["3"] = array( gettext("EXPIRE DAYS SINCE CREATION"), "3");
		return $expire_list;
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
		$packagetype_list["2"] = array( gettext("Free minutes"), "2");
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

	public static function getUsedList(){
		$used_list = array();
		$used_list["0"] = array( gettext("NO USED"), "0");
		$used_list["1"] = array( gettext("USED"), "1");
		return $used_list;
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

}

/*
Constants::getYesNoList();
Constants::getActivationTrueFalseList();
Constants::getPaidTypeList();
Constants::getPaymentStatusList();
Constants::getEmailStatusList();
Constants::getActivationList();
Constants::getPackagesTypeList();
Constants::getBillingPeriodsList();
Constants::getPaymentStateList();
Constants::getLcTypesList();
Constants::getLcShortTypesList();
Constants::getTicketPriorityList();
Constants::getUsedList();
Constants::getBillingTypeList();
Constants::getBillingTypeShortList();
*/

