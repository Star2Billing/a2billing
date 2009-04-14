<?php

getpost_ifset(array('id', 'id_agent', 'id_tariff_group', 'code','id_group'));


$HD_Form = new FormHandler("cc_agent_signup","SIGNUP KEY");

$HD_Form -> FG_TABLE_ID = 'cc_agent_signup.id';
$HD_Form -> FG_TABLE_CLAUSE = "id_agent = ".$_SESSION['agent_id'];
$HD_Form -> FG_DEBUG = 0;

$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_signup_agent.php?atmenu=user&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add Signup KEY");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/key_add.png" ;
$HD_Form -> FG_DELETION = true;


$HD_Form -> AddViewElement(gettext("KEY"), "code", "25%", "center", "sort", "","", "", "", "", "", "");
$HD_Form -> AddViewElement(gettext("AGENT"), "id_agent", "25%", "center", "sort", "", "lie", "cc_agent", "firstname, lastname , login", "id='%id'", "%1 %2 (%3)","");
$HD_Form -> AddViewElement(gettext("CALL PLAN"), "id_tariffgroup","20%","center", "sort", "", "lie", "cc_tariffgroup", "tariffgroupname", "id='%id'", "%1","");
$HD_Form -> AddViewElement(gettext("GROUP"), "id_group", "20%", "center", "sort", "", "lie", "cc_card_group", "name", "id='%id'", "%1","");

$HD_Form -> FieldViewElement ('code, id_agent, id_tariffgroup ,id_group');




$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_OTHER_BUTTON1 = true;
$HD_Form -> FG_OTHER_BUTTON1_ALT = '<font color="red">URL</font>';
$HD_Form -> FG_OTHER_BUTTON1_IMG = '';
$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:sexyBOX('".$A2B->config['signup']['urlcustomerinterface']."signup.php?key=|col0|',400);";


$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF'];
