<?php

getpost_ifset(array('IDCust', 'id', 'id_cc_card', 'cid', 'activated'));


$HD_Form = new FormHandler("cc_callerid","CallerID");

$HD_Form -> FG_DEBUG = 0;
// FAQ

$actived_list = Constants::getActivationTrueFalseList();

$yesno = Constants::getYesNoList();


$HD_Form -> AddViewElement(gettext("CALLERID"), "cid", "25%", "center", "sort");
// $HD_Form -> AddViewElement("CARDNUMBER", "id_cc_card", "25%", "center", "sort", "15", "lie", "cc_card", "username", "id='%id'", "%1");
$HD_Form -> AddViewElement(gettext("ACTIVATED"), "activated", "25%", "center", "sort", "26");


$HD_Form -> FieldViewElement ('cid, activated');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO")." ".strtoupper($HD_Form->FG_INSTANCE_NAME);
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_LIMITE_DISPLAY = 10;
$HD_Form -> FG_VIEW_TABLE_WITDH = '70%';


// My Code for Where Cluase
if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0)
    $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="id_cc_card =".$_SESSION["card_id"];
	
if (strlen($HD_Form -> FG_EDITION_CLAUSE)>0)
    $HD_Form -> FG_EDITION_CLAUSE.=" AND ";
	$HD_Form -> FG_EDITION_CLAUSE.="id_cc_card =".$_SESSION["card_id"];
	


//end My Code for Where Clause
$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;
$HD_Form -> FG_OTHER_BUTTON1 = false;


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

 /*
 $HD_Form -> AddEditElement ("CALLERID",
			   "callerid",
			   '$value',
			   "INSERT",
			   "",
			   "",
			   "",
			   "sql",
			   "call_tariff",
			   "label, id",
			   "", "", "%1 : (%2)", "", "", "cc_callerid:cid:cc_callerid:cid, cid, id_cc_card:id_cc_card = %id:id_cc_card:%3 - (%1)::othertablelinked:cli, id_cc_card:%1:id_cc_card='%1'");

  */




if (isset($filterprefix)  &&  (strlen($filterprefix)>0))
{
        if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0)
            $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
        $HD_Form -> FG_TABLE_CLAUSE.="cid like '$filterprefix%'";
}


$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");

$HD_Form -> FG_OTHER_BUTTON1_LINK="A2B_entity_card?form_action=ask-edit&stitle=Customers_Card&id=";
$HD_Form -> FG_OTHER_BUTTON1_IMG = Images_Path."/icon-card.png";



$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("You can add your home phone number below to allow you to call in and use your Calling Card Services with out having to enter your Calling Card every time you call in.")." ".gettext("You can also add your Soft Phone number (The number you used to log into this Website) and be able to make calls with out having to enter your Calling Card information.");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path."/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Made your change to edit this callerid.");



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
?>
