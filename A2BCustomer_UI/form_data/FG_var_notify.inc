<?php

getpost_ifset(array('id', 'email_notification','credit_notification','notify_email',));


$HD_Form = new FormHandler("cc_card","Card");
$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_ID="id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";
$HD_Form -> FG_HTML_TABLE_WIDTH = "60%";
$HD_Form -> FG_EDITION_CLAUSE = " id = ".$_SESSION["card_id"];
$HD_Form -> FG_INTRO_TEXT_EDITION = '';


// Possible value to notify the user
 $DBHandle  = DbConnect();
 $instance_config_table = new Table("cc_config", "id, config_value");
 $QUERY = " config_key = 'values_notifications' ";
 $return = null;
 $return = $instance_config_table -> Get_list($DBHandle, $QUERY, 0);
 $values = explode(":",$return[0]["config_value"]);
 $limits_notify = array();
 $idx =0;
  foreach ($values as $val)
	{ 	$limits_notify [$idx] = array($val,$val);
		$idx++;
	}


$HD_Form -> AddEditElement (gettext("LIMIT CREDIT NOTIFICATION"),
			   "credit_notification",
			   '',
			   "SELECT",
			   "",
			   "",
			   gettext("Insert the number of credit when the customer receive a notification"),
			   "list" , "", "", "", $limits_notify , "%1", "", gettext("currency : ").strtoupper(BASE_CURRENCY),"", '<OPTION  value="-1" selected>'.gettext("NOT DEFINED").'</OPTION>'  );




$HD_Form -> AddEditElement (gettext("PERMITTED NOTIFICATIONS BY MAIL"),
			   "notify_email",
			   '0',
			   "RADIOBUTTON",
			   "",
			   "",
			   gettext("Choose if you want to enable the notification by email for this card"),
			   "" , "", "", "Yes :1, - No:0", "", "" , "", gettext("Enable the notification by mail for this card."));

$HD_Form -> AddEditElement (gettext("EMAIL NOTIFICATION"),
			   "email_notification",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=70",
			   "1",
			   gettext("Insert the email to notify the customers"),
			   "" , "", "",	"", "", "" , "NO", "");

$HD_Form -> FieldEditElement ('credit_notification, notify_email, email_notification');

$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily update your information clicking on the button.");

$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your record has been updated.")."<br>";

$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = "userinfo.php?section=1";

?>
