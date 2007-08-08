<?php
getpost_ifset(array('id', 'config_title', 'config_key', 'config_value', 'config_description', 'config_group_id', 'groupselect', 'posted','agi_conf'));

// This will generate agi-confx 
// agi-confx is duplication of Global configuration and list of configurations of : agi-conf1 
if($agi_conf)
{
	$groupselect = add_agi_confx();
	unset($agi_conf);
}
$setbool = false;
$handle = DbConnect();
$instance_table = new Table();
$QUERY =  "SELECT config_value, config_listvalues from cc_config where id = $id";
$result = $instance_table -> SQLExec ($handle, $QUERY);
if($result[0][1] == "yes,no" || $result[0][1] == "no,yes"){
	$setbool = true;
}

$value_list[0][0] = "no";
$value_list[0][1] = 0;
$value_list[1][0] = "yes";
$value_list[1][1] = 1;
foreach($value_list as $key => $cur_value) {
	if(in_array($result[0][0],$cur_value)){
		$value_list[$key]  = array( $cur_value[0], $key." selected");
	}else{
		$value_list[$key]  = array( $cur_value[0], $key);
	}
}
$yesno = array();

$QUERY =  "SELECT config_value, config_valuetype from cc_config order by id";
$result = $instance_table -> SQLExec ($handle, $QUERY);

foreach($result as $key => $cur_value) {
		if($cur_value[1] == "1"){
			if($cur_value[0] == "1"){
				$yesno[$cur_value[0]] = array('Yes',$key);
			}else if($cur_value[0] == "0"){
				$yesno[$cur_value[0]] = array('No', $key);						
			}else{
				$yesno[$cur_value[0]] = array( $cur_value[0], $key);	
			}
		}else{
			$yesno[$cur_value[0]] = array( $cur_value[0], $key);	
		}
	
}

$HD_Form = new FormHandler("cc_config ccc, cc_config_group ccg","Configuration");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_ID="ccc.id";
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "config_group_id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";

$HD_Form -> FG_TABLE_CLAUSE = "ccc.config_group_id =  ccg.id";

if ($groupselect != ""){
	$HD_Form -> FG_TABLE_CLAUSE .= " AND ccc.config_group_id =  ".$groupselect;
	$_SESSION['ss_groupselect'] = $HD_Form -> FG_TABLE_CLAUSE;
}
if (isset($posted)){
	$_SESSION['ss_groupselect'] = '';
	$_SESSION['ss_groupselect'] = $HD_Form -> FG_TABLE_CLAUSE;
}

if($form_action == "list"){
		$_SESSION['ss_groupselect'] = '';
}else{
	$HD_Form -> FG_TABLE_CLAUSE = $_SESSION['ss_groupselect']; 
}
$HD_Form -> FG_EDITION_CLAUSE = " ccc.config_group_id =  ccg.id AND ccc.id = %id";
$HD_Form -> AddViewElement(gettext("TITLE"), "config_title", "20%", "center");
$HD_Form -> AddViewElement(gettext("KEY"), "config_key", "15%", "center");
$HD_Form -> AddViewElement(gettext("Value"), "config_value", "10%", "center","","","list",$yesno);
$HD_Form -> AddViewElement(gettext("DESCRIPTION"), "config_description", "40%", "left");
$HD_Form -> AddViewElement(gettext("GROUP"), "group_title", "5%", "center");

// added a parameter to append  FG_TABLE_ID  ( by default ) or disable 0.
$HD_Form -> FieldViewElement ('config_title, config_key, config_value, config_description, group_title');

$HD_Form -> CV_NO_FIELDS  = gettext("THERE ARE NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_LIMITE_DISPLAY = 25;

$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = false;

if(!$setbool){
	$HD_Form -> FG_DISPLAY_SELECT = true;
	$HD_Form -> FG_SELECT_FIELDNAME = "config_listvalues";
	$HD_Form -> FG_CONF_VALUE_FIELDNAME = "config_value";
}

$HD_Form -> AddEditElement (gettext("GROUP"),
	"group_title",
	'$value',
	"INPUT",
	"size=30 readonly",
	"",
	gettext("This is the group of the configuration. Not editable!"),
	"" , "", "", "", "", "", "", gettext("Group Name of the configuration variable"));
	

$HD_Form -> AddEditElement (gettext("TITLE"),
	"config_title",
	'$value',
	"INPUT",
	"size=30 maxlength=100 readonly",
	"",
	gettext("Insert the TITLE"),
	"" , "", "", "", "" , "", "" ,gettext("Title of the configuration variable"));

$HD_Form -> AddEditElement (gettext("KEY"),
	"config_key",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	gettext("Insert the TITLE"),
	"" , "", "", "", "" , "", "" ,gettext("Key name of the configuration variable"));
if($setbool){
$HD_Form -> AddEditElement (gettext("VALUE"),
			   "config_value",
			   "",
			   "SELECT",
			   "","","",
			   "list" , "", "",	"", $value_list, "", "", "" );
	
}else{
$HD_Form -> AddEditElement (gettext("VALUE"),
	"config_value",
	'$value',
	"INPUT",
	"size=30 ",
	"","",
	"" , "", "", "", "", "", "", gettext("Insert the Value"));
}
if(LIST_OF_VALUES){
$HD_Form -> AddEditElement (gettext("LIST OF VALUES"),
	"config_listvalues",
	'$value',
	"INPUT",
	"size=30 ",
	"",
	"",
	"" , "", "", "", "", "", "", gettext("Insert the List of values, comma separated"));
}

$HD_Form -> AddEditElement (gettext("DESCRIPTION"),
	"config_description",
	'',
	"SPAN",
	"cols=80 rows=10",
	"",
	gettext("Insert the Description"),
	"" , "", "", "", "" , "", "", "");

if(LIST_OF_VALUES){
	$HD_Form -> FieldEditElement ('ccg.group_title, config_title, config_key, config_value ,config_listvalues,config_description');
}else{
	$HD_Form -> FieldEditElement ('ccg.group_title, config_title, config_key, config_value ,config_description');
}


$HD_Form -> FG_INTRO_TEXT_EDITION= '';
$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");

$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";


?>
