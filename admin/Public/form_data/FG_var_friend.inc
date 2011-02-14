<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,   
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 * 
 * @copyright   Copyright (C) 2004-2009 - Star2billing S.L. 
 * @author      Belaid Arezqui <areski@gmail.com>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 * @package     A2Billing
 *
 * Software License Agreement (GNU Affero General Public License)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * 
**/



getpost_ifset(array('id_cc_card', 'cardnumber', 'id', 'name', 'amaflags', 'callgroup', 'accountcode', 'callerid', 'canreinvite', 'context', 'defaultip', 'dtmfmode', 
'fromuser', 'fromdomain', 'host', 'insecure', 'language', 'mailbox', 'md5secret', 'nat', 'permit', 'deny', 'mask', 'pickupgroup', 'port', 'qualify', 
'restrictcid', 'rtptimeout', 'rtpholdtimeout', 'secret', 'type', 'username', 'disallow', 'allow', 'musiconhold', 'regseconds', 'ipaddr', 'cancallforward', 'regexten','friend_id'));

getpost_ifset(array('defaultuser', 'subscribemwi', 'vmexten', 'cid_number', 'callingpres', 'usereqphone', 'incominglimit', 'subscribecontext', 'musicclass', 
'allowtransfer', 'autoframing', 'maxcallbitrate', 'outboundproxy', 'regserver', 'rtpkeepalive', 'dbsecret ', 'regcontext ', 'sourceaddress ', 'mohinterpret ', 'mohsuggest ', 
'inkeys ', 'outkey ', ' sendani ', 'fullname ', ' maxauthreq ', 'encryption ', 'transfer ', 'jitterbuffer ', 'forcejitterbuffer ', 'codecpriority ', 'qualifysmoothing ', 
'qualifyfreqok ', 'qualifyfreqnotok ', 'timezone', 'adsi'));



if ($atmenu=='sip') {
	$HD_Form = new FormHandler("cc_sip_buddies", gettext("SIP Config"));
} else {
	$HD_Form = new FormHandler("cc_iax_buddies", gettext("IAX Config"));
}


$HD_Form -> FG_DEBUG = 0;
$typepaid_list = Constants::getPaidTypeList();
$actived_list =  Constants::getActivationTrueFalseList();
$yesno = Constants::getYesNoList();



$HD_Form -> AddViewElement(gettext("CARDHOLDER"), "id_cc_card", "17%", "center", "sort", "25", "lie", "cc_card", "lastname, firstname", "id='%id'", "%1 %2");
$HD_Form -> AddViewElement(gettext("USERNAME"), "username", "15%", "center", "sort", "", "30", "", "", "", "", "linktocustomer");
$HD_Form -> AddViewElement(gettext("ACCOUNTCODE"), "accountcode", "12%", "center", "sort");
$HD_Form -> AddViewElement(gettext("SECRET"), "secret", "12%", "center", "sort");
$HD_Form -> AddViewElement(gettext("CALLERID"), "callerid", "15%", "center", "sort");
$HD_Form -> AddViewElement(gettext("CONTEXT"), "context", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DEFAULTIP"), "defaultip", "10%", "center", "sort");


$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_friend.php?form_action=ask-add&atmenu=$atmenu&section=".$_SESSION["menu_section"];

if ($atmenu=='sip') {
	$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add SIP Config");
} else {
	$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add IAX Config");
}
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/telephone_add.png" ;



$HD_Form -> FieldViewElement ('id_cc_card, username, accountcode, secret, callerid, context, defaultip');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> CV_FILTER_ABOVE_TABLE_PARAM = "?friend_id=";
$HD_Form -> FG_LIMITE_DISPLAY = 10;

if (isset($friend_id)  &&  (strlen($friend_id)>0)) {
	if ($friend_id!='all') {
		if (strlen($HD_Form ->FG_TABLE_CLAUSE)>0) $HD_Form ->FG_TABLE_CLAUSE.=" AND ";
		$HD_Form ->FG_TABLE_CLAUSE.="name='$friend_id'";
	}
}



$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;
$HD_Form -> FG_ADDITION = true;

$maxi='';
if ($form_action=="ask-add") {
	$card_gen = gen_card($HD_Form->FG_TABLE_NAME);
	$maxi = "value='".$card_gen."'";
	$pass =  MDP(6);
	$pass_secret = "value='".$pass."'";
}


if ($form_action=="ask-add" || $form_action == "add_sip" || $form_action == "add_iax") {
	$AMAFLAGS_default= "value='".$A2B->config["peer_friend"]['amaflag']."'";
	$TYPE_default= "value='friend'";
	$NAT_default= "value='yes'";
	$DTMFMODE_default= "value='RFC2833'";
	$CANREINVITE_default= "value='yes'";
	$DISALLOW_default= "value='all'";
	$ALLOW_default= "value='ulaw,alaw,gsm,g729'";
	$HOST_default= "value='dynamic'";
	$CONTEXT_default= "value='".$A2B->config["peer_friend"]['context']."'";
	$REGSECONDS_default= "value='0'";
	$CANCALLFORWARD_default= "value='yes'";
	$TRUNK_default= "value='yes'";
}


$HD_Form -> AddEditElement (gettext("ID CARD"),
							"id_cc_card",
							'',
							"POPUPVALUE",
							"size=30 maxlength=50",
							"4",
							gettext("Insert the Id CardNumber"),
							"", "", "", "", "",
							"A2B_entity_card.php?popup_select=1&"
							, ", 'CardNumberSelection','width=590,height=350,top=20,left=100,scrollbars=1'",
							gettext("Insert the card ID."));


$HD_Form -> AddEditElement (gettext("NAME"),
						   "name",			
						   "$value",	
						   "INPUT",	
							"size=40 $maxi maxlength=40",	 
						   "9",	
						   gettext("Insert the name"),
						   "", "", "",	"", "", "",	"", "" );


$HD_Form -> AddEditElement (gettext("ACCOUNTCODE"),
                           "accountcode",
                           '',
                           "INPUT",
                           "size=20 $maxi maxlength=50",
                           "",
                           gettext("Insert the accountcode"),
                           "", "", "", "", "", "",	"", "" );


$HD_Form -> AddEditElement (gettext("REGEXTEN"),
                           "regexten",
                           '',
                           "INPUT",
                           "size=20 maxlength=20",
                           "",
                           gettext("Insert the regexten"),
                           "", "", "", "", "", "",	"", "" );


$HD_Form -> AddEditElement (gettext("CALLERID"),
                           "callerid",
                           '',
                           "INPUT",
                           "size=40 maxlength=60",
                           "",
                           gettext("Insert the callerid"),
                           "", "", "", "", "", "",	"", "" );


$HD_Form -> AddEditElement (gettext("CID NUMBER"),
                           "cid_number",
                           '',
                           "INPUT",
                           "size=40 maxlength=40",
                           "",
                           gettext("Insert the cid number"),
                           "", "", "", "", "", "",	"", "" );


$HD_Form -> AddEditElement (gettext("AMAFLAGS"),
						   "amaflags",		
						   '',	
						   "INPUT",	
						   "size=10 maxlength=7 $AMAFLAGS_default",	
						   "",	
						   gettext("Insert the amaflags"),
						   "", "", "",	"", "", "",	"", "(default : billing)" );


$HD_Form -> AddEditElement (gettext("SECRET"),
                           "secret",
                           '',
                           "INPUT",
                           "size=40 maxlength=40 $pass_secret",
                           "",
                           gettext("Insert the secret"),
                           "", "", "", "", "", "",	"", "" );


$HD_Form -> AddEditElement (gettext("QUALIFY"),
                           "qualify",
                           '',
                           "INPUT",
                           "size=8 maxlength=7",
                           "",
                           gettext("Insert the qualify"),
                           "", "", "", "", "", "",	"", "" );
                           

$HD_Form -> AddEditElement (gettext("DISALLOW"),
                           "disallow",
                           '',
                           "INPUT",
                           "size=50 maxlength=40 $DISALLOW_default",
                           "",
                           gettext("Insert the disallow"),
                           "", "", "", "", "", "",	"", gettext("need to disallow=all before we can use allow. ( default : all )"));


$HD_Form -> AddEditElement (gettext("ALLOW"),
                           "allow",
                           '',
                           "INPUT",
                           "size=50 maxlength=40 $ALLOW_default",
                           "",
                           gettext("Insert the allow"),
                           "", "", "", "", "", "",	"", gettext("Set allow codecs separated by a comma, e.g. gsm,alaw,ulaw ( default : ulaw,alaw,gsm,g729)"));


$HD_Form -> AddEditElement (gettext("HOST"),
                           "host",
                           '',
                           "INPUT",
                           "size=40 maxlength=31 $HOST_default",
                           "",
                           gettext("Insert the host"),
                           "", "", "", "", "", "",	"", gettext("Use dynamic or set an IP ( default : dynamic )"));


$HD_Form -> AddEditElement (gettext("CONTEXT"),
						   "context",		
						   '',	
						   "INPUT",	
						   "size=40 maxlength=40 $CONTEXT_default",	
						   "",	
						   gettext("Insert the context"),
						   "", "", "",	"", "", "",	"", gettext("set the context for this user (default : a2billing )"));
						   

$HD_Form -> AddEditElement (gettext("DEFAULTIP"),
						   "defaultip",		
						   '',	
						   "INPUT",	
						   "size=20 maxlength=15",	
						   "",	
						   gettext("Insert the defaultip"),
						   "", "", "",	"", "", "",	"", "" );
						   

$HD_Form -> AddEditElement (gettext("LANGUAGE"),
						   "language",		
						   '',	
						   "INPUT",	
						   "size=10 maxlength=4",	
						   "",	
						   gettext("Insert the language"),
						   "", "", "",	"", "", "",	"", "" );


$HD_Form -> AddEditElement (gettext("PORT"),
						   "port",		
						   '',	
						   "INPUT",	
						   "size=10 maxlength=5",	
						   "",	
						   gettext("Insert the port"),
						   "", "", "",	"", "", "",	"", "" );


$HD_Form -> AddEditElement (gettext("REGSECONDS"),	
						   "regseconds",		
						   '4',	
						   "INPUT",	
						   "size=50 maxlength=40 $REGSECONDS_default",	
						   "",	
						   gettext("Insert the regseconds"),
						   "", "", "",	"", "", "",	"", "" );


$HD_Form -> AddEditElement (gettext("IPADDR"),
						   "ipaddr",		
						   '',	
						   "INPUT",	
						   "size=20 maxlength=15",	
						   "",	
						   gettext("Insert the ipaddr"),
						   "", "", "",	"", "", "",	"", "" );
						   
$HD_Form -> AddEditElement (gettext("MOHSUGGEST"),
						   "mohsuggest",		
						   '',	
						   "INPUT",	
						   "size=20 maxlength=20",	
						   "",	
						   gettext("Insert the mohsuggest"),
						   "", "", "",	"", "", "",	"", "" );

						   
$HD_Form -> AddEditElement (gettext("AUTH"),
						   "auth",		
						   '',	
						   "INPUT",	
						   "size=20 maxlength=20",	
						   "",	
						   gettext("Insert the auth"),
						   "", "", "",	"", "", "",	"", "" );


$HD_Form -> AddEditElement (gettext("SETVAR"),
						   "setvar",		
						   '',	
						   "INPUT",	
						   "size=60 maxlength=200",	
						   "",	
						   gettext("Insert the setvar"),
						   "", "", "",	"", "", "",	"", "" );

$HD_Form -> AddEditElement (gettext("TYPE"),
                           "type",
                           '',
                           "INPUT",
                           "size=10 maxlength=6 $TYPE_default",
                           "",
                           gettext("Insert the type"),
                           "", "", "", "", "", "", "", gettext("type = friend | peer | user ( default : friend )"));

$HD_Form -> AddEditElement (gettext("DENY"),
						   "deny",		
						   '',	
						   "INPUT",	
						   "size=60 maxlength=50",	
						   "",	
						   gettext("Insert the deny"),
						   "", "", "",	"", "", "",	"", "" );

$HD_Form -> AddEditElement (gettext("PERMIT"),
						   "permit",
						   '',
						   "INPUT",
						   "size=60 maxlength=50",
						   "",
						   gettext("Insert the permit"),
						   "", "", "",	"", "", "",	"", "" );

if ($atmenu=='sip') {

	
	$HD_Form -> AddEditElement (gettext("USERNAME"),
	                           "username",
	                           '',
	                           "INPUT",
	                           "size=50 $maxi maxlength=40",
	                           "",
	                           gettext("Insert the username"),
	                           "", "", "", "", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("MD5SECRET"),
	                           "md5secret",
	                           '',
	                           "INPUT",
	                           "size=60 maxlength=50",
	                           "",
	                           gettext("Insert the md5secret"),
	                           "", "", "", "", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("NAT"),
	                           "nat",
	                           '4',
	                           "INPUT",
	                           "size=60 maxlength=50 $NAT_default",
	                           "",
	                           gettext("Insert the nat"),
	                           "", "", "", "", "", "",	"", gettext("nat = yes | no | never | route ( default : yes )") );
	
	
	$HD_Form -> AddEditElement (gettext("DTMFMODE"),
	                           "dtmfmode",
	                           '',
	                           "INPUT",
	                           "size=10 maxlength=7 $DTMFMODE_default",
	                           "",
	                           gettext("Insert the dtmfmode"),
	                           "", "", "", "", "", "",	"", gettext("dtmfmode = RFC2833 | INFO | INBAND | AUTO ( default : RFC2833 )"));
	
	
	
	$HD_Form -> AddEditElement (gettext("CANREINVITE"),
	                           "canreinvite",
	                           '',
	                           "INPUT",
	                           "size=20 maxlength=10 $CANREINVITE_default",
	                           "",
	                           gettext("Insert the canreinvite"),
	                           "", "", "", "", "", "",	"", gettext("canreinvite : yes | no ( default : yes )"));
	
	
	
	
	$HD_Form -> AddEditElement (gettext("CALLGROUP"),
							   "callgroup",		
							   '',	
							   "INPUT",	
							   "size=10 maxlength=10",	
							   "",	
							   gettext("Insert the callgroup"),
							   "", "", "",	"", "", "",	"", "" );
	
	
	
	
	$HD_Form -> AddEditElement (gettext("FROMUSER"),
							   "fromuser",		
							   '',	
							   "INPUT",	
							   "size=40 maxlength=40",	
							   "",	
							   gettext("Insert the fromuser"),
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("FROMDOMAIN"),
							   "fromdomain",		
							   '',	
							   "INPUT",	
							   "size=40 maxlength=40",	
							   "",	
							   gettext("Insert the fromdomain"),
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("INSECURE"),
							   "insecure",		
							   '',	
							   "INPUT",	
							   "size=20 maxlength=30",	
							   "",	
							   gettext("Insert the insecure"),
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("MAILBOX"),
							   "mailbox",		
							   '',	
							   "INPUT",	
							   "size=60 maxlength=50",	
							   "",	
							   gettext("Insert the mailbox"),
							   "", "", "",	"", "", "",	"", "" );
	
	
	
	$HD_Form -> AddEditElement (gettext("MASK"),
							   "mask",		
							   '',	
							   "INPUT",	
							   "size=60 maxlength=50",	
							   "",	
							   gettext("Insert the mask"),
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("PICKUPGROUP"),
							   "pickupgroup",		
							   '',	
							   "INPUT",	
							   "size=15 maxlength=10",	
							   "",	
							   gettext("Insert the pickupgroup"),
							   "", "", "",	"", "", "", "", "");
	
	
	$HD_Form -> AddEditElement (gettext("RESTRICTCID"),
							   "restrictcid",		
							   '',	
							   "INPUT",	
							   "size=7 maxlength=1",	
							   "",	
							   gettext("Insert the restrictcid"),
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("RTPTIMEOUT"),
							   "rtptimeout",		
							   '',	
							   "INPUT",	
							   "size=7 maxlength=3",	
							   "",	
							   gettext("Insert the rtptimeout"),
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("RTPHOLDTIMEOUT"),
							   "rtpholdtimeout",		
							   '',	
							   "INPUT",	
							   "size=7 maxlength=3",	
							   "",	
							   gettext("Insert the rtpholdtimeout"),
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("MUSICONHOLD"),
							   "musiconhold",		
							   '',	
							   "INPUT",	
							   "size=50 maxlength=40",	
							   "",	
							   gettext("Insert the musiconhold"),
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("CANCALLFORWARD"),
							   "cancallforward",		
							   '',	
							   "INPUT",	
							   "size=7 maxlength=3 $CANCALLFORWARD_default",	
							   "",	
							   gettext("Insert the cancallforward"),
							   "", "", "",	"", "", "",	"", gettext("cancallforward = yes | no ( default : yes )"));
	
	
	$HD_Form -> AddEditElement (gettext("DEFAULTUSER"),
							   "defaultuser",		
							   '',	
							   "INPUT",	
							   "size=10 maxlength=10",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
							   
	
	$HD_Form -> AddEditElement (gettext("SUBSCRIBEMWI"),
							   "subscribemwi",		
							   '',	
							   "INPUT",	
							   "size=10 maxlength=10",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("VMEXTEN"),
							   "vmexten",		
							   '',	
							   "INPUT",	
							   "size=20 maxlength=20",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("CALLINGPRES"),
							   "callingpres",		
							   '',	
							   "INPUT",	
							   "size=20 maxlength=20",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("USEREQPHONE"),
							   "usereqphone",		
							   '',	
							   "INPUT",	
							   "size=10 maxlength=10",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("INCOMINGLIMIT"),
							   "incominglimit",		
							   '',	
							   "INPUT",	
							   "size=10 maxlength=10",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("SUBSCRIBECONTEXT"),
							   "subscribecontext",		
							   '',	
							   "INPUT",	
							   "size=40 maxlength=40",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("MUSICCLASS"),
							   "musicclass",		
							   '',	
							   "INPUT",	
							   "size=20 maxlength=20",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("ALLOWTRANSFER"),
							   "allowtransfer",		
							   '',	
							   "INPUT",	
							   "size=20 maxlength=20",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("AUTOFRAMING"),
							   "autoframing",		
							   '',	
							   "INPUT",	
							   "size=10 maxlength=10",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("MAXCALLBITRATE"),
							   "maxcallbitrate",		
							   '',	
							   "INPUT",	
							   "size=15 maxlength=15",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("OUTBOUNDPROXY"),
							   "outboundproxy",		
							   '',	
							   "INPUT",	
							   "size=40 maxlength=40",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("REGSERVER"),
							   "regserver",		
							   '',	
							   "INPUT",	
							   "size=20 maxlength=20",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("RTPKEEPALIVE"),
							   "rtpkeepalive",		
							   '',	
							   "INPUT",	
							   "size=15 maxlength=15",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );

} else {
	
	$HD_Form -> AddEditElement (gettext("TRUNK"),
							   "trunk",		
							   '',	
							   "INPUT",
							   "size=7 maxlength=3 $TRUNK_default",	
							   "",	
							   gettext("Define the iax trunking"),
							   "", "", "",	"", "", "",	"", gettext("iax trunking = yes | no ( default : yes )"));
						   
	
	$HD_Form -> AddEditElement (gettext("DBSECRET"),
							   "dbsecret",		
							   '',	
							   "INPUT",	
							   "size=40 maxlength=40",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("REGCONTEXT"),
							   "regcontext",		
							   '',	
							   "INPUT",	
							   "size=40 maxlength=40",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("SOURCEADDRESS"),
							   "sourceaddress",		
							   '',	
							   "INPUT",	
							   "size=20 maxlength=10",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	$HD_Form -> AddEditElement (gettext("MOHINTERPRET"),
							   "mohinterpret",		
							   '',	
							   "INPUT",	
							   "size=20 maxlength=20",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("INKEYS"),
							   "inkeys",		
							   '',	
							   "INPUT",	
							   "size=40 maxlength=40",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("OUTKEY"),
							   "outkey",		
							   '',	
							   "INPUT",	
							   "size=40 maxlength=40",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	$HD_Form -> AddEditElement (gettext("SENDANI"),
							   "sendani",		
							   '',	
							   "INPUT",	
							   "size=10 maxlength=10",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	$HD_Form -> AddEditElement (gettext("FULLNAME"),
							   "fullname",		
							   '',	
							   "INPUT",	
							   "size=40 maxlength=40",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("MAXAUTHREQ"),
							   "maxauthreq",		
							   '',	
							   "INPUT",	
							   "size=15 maxlength=15",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("ENCRYPTION"),
							   "encryption",		
							   '',	
							   "INPUT",	
							   "size=20 maxlength=20",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
							   
	
	$HD_Form -> AddEditElement (gettext("TRANSFER"),
							   "transfer",		
							   '',	
							   "INPUT",	
							   "size=10 maxlength=10",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("JITTERBUFFER"),
							   "jitterbuffer",		
							   '',	
							   "INPUT",	
							   "size=10 maxlength=10",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("FORCEJITTERBUFFER"),
							   "forcejitterbuffer",		
							   '',	
							   "INPUT",	
							   "size=10 maxlength=10",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("CODECPRIORITY"),
							   "codecpriority",		
							   '',	
							   "INPUT",	
							   "size=40 maxlength=40",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("QUALIFYSMOOTHING"),
							   "qualifysmoothing",		
							   '',	
							   "INPUT",	
							   "size=10 maxlength=10",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("QUALIFYFREQOK"),
							   "qualifyfreqok",		
							   '',	
							   "INPUT",	
							   "size=10 maxlength=10",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("QUALIFYFREQNOTOK"),
							   "qualifyfreqnotok",		
							   '',	
							   "INPUT",	
							   "size=10 maxlength=10",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("TIMEZONE"),
							   "timezone",		
							   '',	
							   "INPUT",	
							   "size=20 maxlength=20",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );
	
	
	$HD_Form -> AddEditElement (gettext("ADSI"),
							   "adsi",		
							   '',	
							   "INPUT",	
							   "size=10 maxlength=10",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", "" );

	
	$HD_Form -> AddEditElement (gettext("REQUITE CALL TOKEN"),
							   "requirecalltoken",		
							   '',	
							   "INPUT",	
							   "size=15 maxlength=20",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", gettext("No supported by Realtime") );
							   
	
	$HD_Form -> AddEditElement (gettext("MAX CALL NUMBERS"),
							   "maxcallnumbers",		
							   '',	
							   "INPUT",	
							   "size=10 maxlength=10",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", gettext("No supported by Realtime") );
							   
	
	$HD_Form -> AddEditElement (gettext("MAX CALL NUMBERS NON VALIDATED"),
							   "maxcallnumbers_nonvalidated",		
							   '',	
							   "INPUT",	
							   "size=10 maxlength=10",	
							   "",	
							   "",
							   "", "", "",	"", "", "",	"", gettext("No supported by Realtime") );
}


$field_list_edit = 'id_cc_card, name, accountcode, regexten, callerid, cid_number, amaflags, secret, qualify, disallow, allow, host, context, defaultip, language, port, regseconds, ipaddr, mohsuggest, auth, setvar, type, deny, permit';

if ($atmenu=='sip') {
	$field_list_edit .= ', username, md5secret, nat, dtmfmode, canreinvite, callgroup, fromuser, fromdomain, insecure, mailbox, mask, pickupgroup, restrictcid, rtptimeout, rtpholdtimeout, ' .
			'musiconhold,  cancallforward, defaultuser, subscribemwi, vmexten, callingpres, usereqphone, incominglimit, subscribecontext, musicclass, allowtransfer, ' .
			'autoframing, maxcallbitrate, outboundproxy, regserver, rtpkeepalive';
} else {
	$field_list_edit .= ', trunk, dbsecret, regcontext, sourceaddress, mohinterpret, inkeys, outkey,  sendani, fullname,  maxauthreq, encryption, transfer, jitterbuffer, ' .
			'forcejitterbuffer, codecpriority, qualifysmoothing, qualifyfreqok, qualifyfreqnotok, timezone, adsi, requirecalltoken, maxcallnumbers, maxcallnumbers_nonvalidated';
}


$HD_Form -> FieldEditElement ($field_list_edit);

$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'username';
$HD_Form -> FG_FILTERFIELDNAME = gettext('username');
$HD_Form -> FG_FILTER_FORM_ACTION = 'list';


if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="name like '$filterprefix%'";
}

$HD_Form -> FG_INTRO_TEXT_EDITION= '';
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new").$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted. <br>");


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");


$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=$atmenu&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=$atmenu&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=$atmenu&stitle=Document&wh=AC&id=";

