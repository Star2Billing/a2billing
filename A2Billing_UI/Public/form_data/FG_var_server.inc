<?php

getpost_ifset(array('id', 'id_group', 'server_ip', 'manager_host', 'manager_username', 'manager_secret'));


$HD_Form = new FormHandler("cc_server_manager","Server");

$HD_Form -> FG_DEBUG = 0;

$HD_Form -> AddViewElement("ID_GROUP", "id_group", "8%", "center", "sort", "15");
$HD_Form -> AddViewElement("GROUP", "id_group", "12%", "center", "sort", "15", "lie", "cc_server_group", "name", "id='%id'", "%1");
$HD_Form -> AddViewElement("SERVER_IP", "server_ip", "12%", "center", "sort");
$HD_Form -> AddViewElement("MANAGER_HOST", "manager_host", "15%", "center", "sort");
$HD_Form -> AddViewElement("MANAGER_USERNAME", "manager_username", "15%", "center", "sort");
$HD_Form -> AddViewElement("LASTTIME_USED", "lasttime_used", "14%", "center", "sort", "19", "", "", "", "", "", "display_dateformat");


$HD_Form -> FieldViewElement ('id_group, id_group, server_ip, manager_host, manager_username, lasttime_used');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO")." ".strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_LIMITE_DISPLAY = 10;


$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;

$HD_Form -> AddEditElement (gettext("SERVER GROUP"),
				"id_group",
				'$value',
				"SELECT",
				"", "", "",
				"sql",
				"cc_server_group",
				"name, id",
				"", "", "%1","", "");

$HD_Form -> AddEditElement ("SERVER_IP",	
			   "server_ip",		
			   '$value',	
			   "INPUT",	
			   "size=30 maxlength=40",	
			   "3",	
			   "Insert the server_ip", 
			   "" , "", "",	"", "" , "", "", "");

$HD_Form -> AddEditElement ("MANAGER_HOST",	
			   "manager_host",		
			   '$value',	
			   "INPUT",	
			   "size=30 maxlength=40",	
			   "3",	
			   "Insert the server_ip", 
			   "" , "", "",	"", "" , "", "", "");

$HD_Form -> AddEditElement ("MANAGER_USERNAME",	
			   "manager_username",		
			   '$value',	
			   "INPUT",	
			   "size=30 maxlength=40",	
			   "3",	
			   "Insert the server_ip", 
			   "" , "", "",	"", "" , "", "", "");

$HD_Form -> AddEditElement ("MANAGER_SECRET",	
			   "manager_secret",		
			   '$value',	
			   "INPUT",	
			   "size=30 maxlength=40",	
			   "3",	
			   "Insert the server_ip", 
			   "" , "", "",	"", "" , "", "", "");

$HD_Form -> FieldEditElement ('id_group, server_ip, manager_host, manager_username, manager_secret');


// Set the filter variables
$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'id_group';
$HD_Form -> FG_FILTERFIELDNAME = 'Server ID Group';


if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.=" id_group like '$filterprefix%'";
}



$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("Modify the properties of the")." ".$HD_Form->FG_INSTANCE_NAME.".";
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");



$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." " .$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Setup those values to create the new")." ".$HD_Form->FG_INSTANCE_NAME;


$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
?>
