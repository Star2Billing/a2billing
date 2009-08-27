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



getpost_ifset(array('id', 'name', 'path', 'creationdate'));


$HD_Form = new FormHandler("cc_backup","restore");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = " name";
$HD_Form -> FG_EDITION_CLAUSE = " id='%id' ";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

$HD_Form -> AddViewElement(gettext("ID"), "id", "5%", "center", "sort");
$HD_Form -> AddViewElement(gettext("NAME"), "name", "5%", "center", "sort");
$HD_Form -> AddViewElement(gettext("FILE"), "path", "20%", "center", "sort");
$HD_Form -> AddViewElement(gettext("CREATIONDATE"), "creationdate", "20%", "center", "sort", "19", "", "", "", "", "", "display_dateformat");


$HD_Form -> FieldViewElement ('id, name, path, creationdate');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO")." ".strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_ADDITION = true;
$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;
// Restore and Download
$HD_Form -> FG_OTHER_BUTTON1 = true;
$HD_Form -> FG_OTHER_BUTTON2 = true;

$HD_Form -> AddEditElement (gettext("Name "),
	"name",
	"",
	"INPUT",
	"size=50 maxlength=50",
	"",
	gettext("Insert the name of backup"),
	"" , "", "", "", "", "", "", gettext("Name of the backup"));
	
$HD_Form -> AddEditElement ("PATH",
	"path",
	'',
	"INPUT",
	"size=100 maxlength=200 readonly",
	"",
	"the backup file",
	"" , "", "", "", "", "", "", gettext("The backup file name"));
	


$HD_Form -> FieldEditElement ('name, path, creationdate');

//$HD_Form -> FG_QUERY_EDITION_HIDDEN_FIELDS = "path";
//$HD_Form -> FG_QUERY_EDITION_HIDDEN_VALUE  = "$path";


if ($form_action=="ask-restore") {
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS .="form_action";
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  .="restore";
}


$HD_Form -> FG_INTRO_TEXT_EDITION= '';
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_OTHER_BUTTON1_LINK = $_SERVER['PHP_SELF']."?form_action=restore&atmenu=$HD_Form->FG_INSTANCE_NAME&id=";
$HD_Form -> FG_OTHER_BUTTON1_IMG = Images_Path . "/icon-restore.gif";
$HD_Form -> FG_OTHER_BUTTON1_ALT = gettext("Restore");

$HD_Form -> FG_OTHER_BUTTON2_LINK = $_SERVER['PHP_SELF']."?form_action=download&atmenu=$HD_Form->FG_INSTANCE_NAME&id=";
$HD_Form -> FG_OTHER_BUTTON2_IMG = Images_Path . "/dl.gif";
$HD_Form -> FG_OTHER_BUTTON2_ALT = gettext("Download");

$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add a")." ". $HD_Form->FG_INSTANCE_NAME." ".gettext("now.");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Once you have completed the form above, click on the CONTINUE button.");


$FG_GO_LINK_AFTER_UPLOAD = $_SERVER['PHP_SELF']."?form_action=list&atmenu=$HD_Form->FG_INSTANCE_NAME&id=";

$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";

unset($FG_GO_LINK_AFTER_UPLOAD);
unset($HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE);

