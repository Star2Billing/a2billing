<?php
getpost_ifset(array('id', 'config_title', 'config_key', 'config_value', 'config_description', 'config_group_id', 'groupselect', 'posted'));


$HD_Form = new FormHandler("cc_config ccc");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_ID="ccc.id";

$HD_Form -> FG_EDITION_CLAUSE = " ccc.id = %id";


$HD_Form -> FG_EDITION = true;




$HD_Form -> AddEditElement (gettext("LIST OF VALUES"),
	"config_listvalues",
	'$value',
	"INPUT",
	"size=30 ",
	"",
	"",
	"" , "", "", "", "", "", "", gettext("Insert the List of values, comma separated"));


$HD_Form -> FieldEditElement ('config_listvalues');

$HD_Form -> FG_INTRO_TEXT_EDITION= '';
$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");

$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = "A2B_entity_config.php?form_action=list&atmenu=document&stitle=Document&wh=AC&id=";


?>
