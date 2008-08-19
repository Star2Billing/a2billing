<?php
getpost_ifset(array('IDCust', 'id', 'cid', 'activated'));

$HD_Form = new FormHandler("cc_callerid","CallerID");

$HD_Form -> FG_DEBUG = 0;

$actived_list = Constants::getActivationTrueFalseList();

$yesno = Constants::getYesNoList();


$HD_Form -> AddViewElement(gettext("CALLERID"), "cid", "25%", "center", "sort");
$HD_Form -> AddViewElement(gettext("CARDNUMBER"), "id_cc_card", "20%", "center", "sort", "", "lie_link", "cc_card", "username,id", "id='%id'", "%1", "", "A2B_entity_card.php");
$HD_Form -> AddViewElement(gettext("ACTIVATED"), "activated", "25%", "center", "sort", "", "list", $actived_list);


$HD_Form -> FieldViewElement ('cid, id_cc_card, activated, id');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ").strtoupper($HD_Form->FG_INSTANCE_NAME).gettext(" CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_LIMITE_DISPLAY = 10;


$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;
$HD_Form -> FG_OTHER_BUTTON1 = true;


$HD_Form -> AddEditElement (gettext("CALLERID"),
			   "cid",
			   '$value',
			   "INPUT",
			   "size=20 maxlength=30",
			   "18",
			   gettext("Insert the CallerID"),
			   "" , "", "",	"", "" , "", "", "");


$HD_Form -> AddEditElement (gettext("ACTIVATED"),
				   "activated",
				   't',
				   "RADIOBUTTON",
				   "",
				   "",
				   gettext("Choose if you want to activate this card"),
				   "" , "", "", "Yes :t, - No:f", "", "", "", "" );



$HD_Form -> FieldEditElement ('cid, activated');



// Set the filter variables
$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'callerid';
$HD_Form -> FG_FILTERFIELDNAME = 'callerid';

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="cid like '$filterprefix%'";
}


$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("Modify via the form below, the properties of the callerID");
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME."<br>".gettext("Fill the following fields and confirm by clicking on the button add.");

$HD_Form -> FG_OTHER_BUTTON1_LINK="A2B_entity_card.php?form_action=ask-edit&stitle=Customers_Card&id=|col_orig1|";
$HD_Form -> FG_OTHER_BUTTON1_IMG = Images_Path . "/icon-card.png";

$HD_Form -> FG_OTHER_BUTTON2 = true;
$HD_Form -> FG_OTHER_BUTTON2_LINK="A2B_entity_payment.php?stitle=Payment_add&form_action=ask-add&card_id=|col_orig1|";
$HD_Form -> FG_OTHER_BUTTON2_IMG = Images_Path . "/money.png";
$HD_Form -> FG_OTHER_BUTTON2_ALT = gettext('ADD PAYMENT');



$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add a ".$HD_Form->FG_INSTANCE_NAME." now.");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click Confirm Data to apply your changes");



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
?>
