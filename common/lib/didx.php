<?php

class didx {
	function setupcost($cost) {
		if($cost !== "N/A")
			return $cost/*+ add your profit here*/;
		else
			return $cost;
	}
	function monthly($cost) {
		if($cost !== "N/A")
			return $cost/*+ add your profit here*/;
		else
			return $cost;
	}
	function minutecharge($cost) {
		if($cost !== "N/A")
			return $cost/*+ add your profit here*/;
		else
			return $cost;
	}
	function freeminutes($mins) {
		return $mins/*+ add your profit here*/;
	}

	function getAvailableDIDS($country, $area, $vendor=-1, $VRatingFrom=DIDX_MIN_RATING, $VRatingTo=10, $CountryID=-1) {
		$ret = true;
		$client = new SoapClient(null, array('location' => "https://".DIDX_SITE."/cgi-bin/WebGetListServer.cgi",
						     'connection_timeout' => 5,
		                                     'uri'      => "http://".DIDX_SITE."/GetList"));
		try {		     
			$ret = $client->getAvailableDIDS(DIDX_ID, DIDX_PASS, $country, $area, $vendor, $VRatingFrom, $VRatingTo, $CountryID);
			$ret = $ret['Array'];
			@sort($ret);
			for($i=1; $i<count($ret); $i++) {
				$ret[$i][1]=$this->setupcost($ret[$i][1]);
				$ret[$i][2]=$this->monthly($ret[$i][2]);
				$ret[$i][3]=$this->minutecharge($ret[$i][3]);
			}
		} catch (SoapFault $exception) {
			$ret = false;
		} catch (Exception $e) {
			$ret = false;
		}
		return $ret;
	}

	function getAvailableRatedDIDSbyCountryCode ($country, $VRatingFrom=DIDX_MIN_RATING,$VRatingTo=10, $limit=-1, $vendors=-1, $ratefrom=-1, $rateto=-1, $CountryID=-1) {
		$ret = true;
		$client = new SoapClient(null, array('location' => "https://".DIDX_SITE."/cgi-bin/WebGetAllRatedDIDS.cgi",
						'trace'=>1,
						     'connection_timeout' => 5,
		                                     'uri'      => "http://".DIDX_SITE."/GetList"));
		try {		     
			$ret = $client->getAvailableRatedDIDSbyCountryCode(DIDX_ID, DIDX_PASS, $country, $VRatingFrom, $VRatingTo, $limit, $vendors, $ratefrom, $rateto, $CountryID);
			$ret = $ret['Array'];
			sort($ret);
			for($i=1; $i<count($ret); $i++) {
				$ret[$i][1]=$this->setupcost($ret[$i][1]);
				$ret[$i][2]=$this->monthly($ret[$i][2]);
				$ret[$i][3]=$this->minutecharge($ret[$i][3]);
			}
		} catch (SoapFault $exception) {
			$ret = false;
		} catch (Exception $e) {
			$ret = false;
		}
		return $ret;
	}

	function getDIDCountry($VRatingFrom=DIDX_MIN_RATING,$VRatingTo=10) {
		$ret = true;
		// echo '----------'." $VRatingFrom - $VRatingTo:: https://".DIDX_SITE."/cgi-bin/WebGetDIDCountriesServer.cgi";
		if(isset($_SESSION["country-$VRatingFrom"]))
			return $_SESSION["country-$VRatingFrom"];
		$client = new SoapClient(null, array('location' => "https://".DIDX_SITE."/cgi-bin/WebGetDIDCountriesServer.cgi",
						     'connection_timeout' => 5,
		                                     'uri'      => "http://".DIDX_SITE."/GetList"));
		try {		     
			$ret = $client->getDIDCountry(DIDX_ID, DIDX_PASS, $VRatingFrom, $VRatingTo);
			$ret = $ret['Array'];
			$_SESSION["country-$VRatingFrom"] = $ret;
		} catch (SoapFault $exception) {
			$ret = false;
		} catch (Exception $e) {
			$ret = false;
		}
		return $ret;
	}
	
	function getDIDArea($CountryCode, $VRatingFrom=DIDX_MIN_RATING,$VRatingTo=10, $vendors="", $ratefrom=-1, $rateto=-1, $CountryID=-1) {
		$ret = true;
		if(isset($_SESSION["country-$CountryCode-$CountryID-$VRatingFrom"]))
			return $_SESSION["country-$CountryCode-$CountryID-$VRatingFrom"];
		$client = new SoapClient(null, array('location' => "https://".DIDX_SITE."/cgi-bin/WebGetDIDAreasServer.cgi",
						     'connection_timeout' => 5,
						'trace'=>1,
		                                     'uri'      => "http://".DIDX_SITE."/GetList"));
		try {		     
			$ret = $client->getDIDArea(DIDX_ID, DIDX_PASS, $CountryCode, $VRatingFrom, $VRatingTo, $vendors, $ratefrom, $rateto, $CountryID);
			$ret = $ret['Array'];
			$_SESSION["country-$CountryCode-$CountryID-$VRatingFrom"] = $ret;
		} catch (SoapFault $exception) {
			$ret = false;
		} catch (Exception $e) {
			$ret = false;
		}
		return $ret;
	}
	
	function GetCostOfDIDByNumber($DIDNumber) {
		$client = new SoapClient(null, array('location' => "https://".DIDX_SITE."/cgi-bin/WebGetCostOfDIDServer.cgi",
		                                     'uri'      => "http://".DIDX_SITE."/GetCost"));
		try {		     
			$ret = $client->GetCostOfDIDByNumber(DIDX_ID, DIDX_PASS, $DIDNumber);
			$ret = $ret['Array'][1];
		} catch (SoapFault $exception) {
			$ret = false;
		} catch (Exception $e) {
			$ret = false;
		}
		return $ret;
	}

	function BuyDIDByNumber($DIDNumber,$SIPorIAX, $Flag=1,$VendorID=-1) {
		
		$ret = true;
		$client = new SoapClient(null, array('location' => "https://".DIDX_SITE."/cgi-bin/WebBuyDIDServer.cgi",
						'trace'=>1,
		                                     'uri'      => "http://".DIDX_SITE."/BuyDID"));
		try {		     
			$ret = $client->BuyDIDByNumber(DIDX_ID, DIDX_PASS, $DIDNumber, $SIPorIAX, $Flag,$VendorID);
		} catch (SoapFault $exception) {
			$ret = -99;
		} catch (Exception $e) {
			$ret = -99;
		}
		return $ret;
	}

	function  ReleaseDID($DIDNumber) {
		
		$ret = true;
		$client = new SoapClient(null, array('location' => "https://".DIDX_SITE."/cgi-bin/WebReleaseDIDServer.cgi",
						'trace'=>1,
		                                     'uri'      => "http://".DIDX_SITE."/Release"));
		try {		     
			$ret = $client-> ReleaseDID(DIDX_ID, DIDX_PASS, $DIDNumber);
		} catch (SoapFault $exception) {
			$ret = -99;
		} catch (Exception $e) {
			$ret = -99;
		}
		return $ret;
	}

	function getDIDMinutesInfo($DIDNumber) {
		$client = new SoapClient(null, array('location' => "http://".DIDX_SITE."/cgi-bin/WebGetDIDSMinutes.cgi",
						'trace'=>1,
		                                     'uri'      => "http://".DIDX_SITE."/GetMinutes"));
		try {		     
			$ret = $client->getDIDMinutesInfo(DIDX_ID, DIDX_PASS, $DIDNumber);
			$ret = $ret['Array'][1];
		} catch (SoapFault $exception) {
			$ret = false;
		} catch (Exception $e) {
			$ret = false;
		}
		$cost = $this->GetCostOfDIDByNumber($DIDNumber);
		$ret = array("Setup Price"=> $this->setupcost($cost[0]),
		        "Monthly Price" => $this->monthly($cost[1]),
		        "Minutes Included" => $this->freeminutes($ret[1]),
		        "After free Minute Charges" => $this->minutecharge($ret[2]),
		        "Channels" => $ret[3]
		        );
		return $ret;
	}

	function getAvailableRatedNXX($CountryCode, $NPA, $VRatingFrom=DIDX_MIN_RATING,$VRatingTo=10, $Vendor=-1) {
		if(isset($_SESSION["country-$CountryCode-$NPA-$VRatingFrom"]))
			return $_SESSION["country-$CountryCode-$NPA-$VRatingFrom"];
		$client = new SoapClient(null, array('location' => "https://".DIDX_SITE."/cgi-bin/WebGetListServer.cgi",
						'trace'=>1,
		                                     'uri'      => "http://".DIDX_SITE."/GetList"));
		try {		     
			$ret = $client->getAvailableRatedNXX(DIDX_ID,DIDX_PASS,$CountryCode,$NPA,$VRatingFrom,$VRatingTo, $Vendor);
			$ret = $ret['Array'];
			$_SESSION["country-$CountryCode-$NPA-$VRatingFrom"] = $ret;
		} catch (SoapFault $exception) {
			$ret = false;
		} catch (Exception $e) {
			$ret = false;
		}
		return $ret;
	}
	
	function getDIDCallLog($DIDNumber, $page=0) {
		$client = new SoapClient(null, array('location' => "http://".DIDX_SITE."/cgi-bin/WebGetDIDUsage.cgi",
						'trace'=>1,
		                                     'uri'      => "http://".DIDX_SITE."/GetList"));
		try {		     
			$ret = $client->getDIDCallLog(DIDX_ID, DIDX_PASS, $DIDNumber, $page);
			$ret = $ret['Array'];
		} catch (SoapFault $exception) {
			$ret = false;
		} catch (Exception $e) {
			$ret = false;
		}
		return $ret;
	}

	function DoTestMyPurchasedDID($DIDNumber, $sMail=2 ) {
		$client = new SoapClient(null, array('location' => "https://".DIDX_SITE."/cgi-bin/WebDIDTester.cgi",
						'trace'=>1,
		                                     'uri'      => "http://".DIDX_SITE."/DIDTest"));
		try {		     
			$ret = $client->DoTestMyPurchasedDID(DIDX_ID, DIDX_PASS, $DIDNumber, $sMail);
		} catch (SoapFault $exception) {
			$ret = $exception;
		} catch (Exception $e) {
			$ret = $e;
		}
		return $ret;
	}
}

