<?php
include (LIBDIR."/support/classes/ticket.php");

getpost_ifset(array('id', 'title', 'id_component','description','priority','status','viewed_agent'));

$HD_Form = new FormHandler("cc_ticket","Ticket");



$plang='en';

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = " id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";
$HD_Form -> FG_TABLE_CLAUSE = "creator_type=1 AND creator= ".$_SESSION['agent_id'];
$HD_Form -> FG_TABLE_ID="id";

$status_list = Ticket::getAllStatusListView();
$viewed_list = Constants::getTicketViewedList();
$priority_list =Constants::getTicketPriorityList();


$HD_Form -> AddViewElement(gettext("ID"), "id", "5%", "center", "sort");
$HD_Form -> AddViewElement(gettext("TITLE"), "title", "20%", "center", "sort");
$HD_Form -> AddViewElement(gettext("CREATIONDATE"), "creationdate", "15%", "center", "sort", "19", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("COMPONENT"), "id_component", "10%", "center", "sort", "15", "lie", "cc_support_component", "name", "id='%id'", "%1");
$HD_Form -> AddViewElement(gettext("PRIORITY"), "priority", "10%", "center", "sort", "", "list", $priority_list);
$HD_Form -> AddViewElement(gettext("TO SEE?"), "viewed_agent", "10%", "center", "sort", "", "list", $viewed_list);
$HD_Form -> AddViewElement(gettext("STATUS"), "status", "10%", "center", "sort", "", "list", $status_list);

$HD_Form -> FieldViewElement ('cc_ticket.id, title, cc_ticket.creationdate, id_component , priority,viewed_agent, status');


$HD_Form -> FG_OTHER_BUTTON1_LINK = "A2B_ticket_view.php?id=";
$HD_Form -> FG_OTHER_BUTTON1_IMG = Images_Path."/icon-viewdetails.gif";


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO")." ".strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_OTHER_BUTTON1 = true;
$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;



$HD_Form -> FG_INTRO_TEXT_EDITION= '';
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";

$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");

$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
?>
