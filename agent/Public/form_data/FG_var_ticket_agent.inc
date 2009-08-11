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


include (LIBDIR."/support/classes/ticket.php");

getpost_ifset(array('id', 'title', 'id_component','description','priority','status','viewed_agent'));

$HD_Form = new FormHandler("cc_ticket LEFT JOIN cc_ticket_comment ON cc_ticket.id = cc_ticket_comment.id_ticket","Ticket");
$HD_Form = new FormHandler(" cc_ticket LEFT JOIN
( SELECT aa.id, CASE WHEN MAX(aa.viewed_agent)=1 OR SUM(bb.viewed_agent)>0 THEN 1 ELSE 0 END as viewed
  FROM cc_ticket aa
  LEFT JOIN cc_ticket_comment bb ON aa.id = bb.id_ticket
  WHERE aa.creator_type =1 AND aa.creator= ".$_SESSION['agent_id']." GROUP BY aa.id
) AS v ON cc_ticket.id = v.id", "Ticket");


$plang='en';

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = " cc_ticket.id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";
$HD_Form -> FG_TABLE_CLAUSE = "cc_ticket.creator_type=1 AND cc_ticket.creator= ".$_SESSION['agent_id'];
$HD_Form -> FG_TABLE_ID="cc_ticket.id";

$status_list = Ticket::getAllStatusListView();
$viewed_list = Constants::getTicketViewedList();
$priority_list =Constants::getTicketPriorityList();

$filter_view ='v.viewed ';


$HD_Form -> AddViewElement(gettext("ID"), "id", "5%", "center", "sort");
$HD_Form -> AddViewElement(gettext("TITLE"), "title", "20%", "center", "sort");
$HD_Form -> AddViewElement(gettext("CREATIONDATE"), "creationdate", "15%", "center", "sort", "19", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("COMPONENT"), "id_component", "10%", "center", "sort", "15", "lie", "cc_support_component", "name", "id='%id'", "%1");
$HD_Form -> AddViewElement(gettext("PRIORITY"), "priority", "10%", "center", "sort", "", "list", $priority_list);
$HD_Form -> AddViewElement(gettext("TO SEE?"), "viewed_agent", "10%", "center", "sort", "", "list", $viewed_list);
$HD_Form -> AddViewElement(gettext("STATUS"), "status", "10%", "center", "sort", "", "list", $status_list);

$HD_Form -> FieldViewElement ("cc_ticket.id, title, cc_ticket.creationdate, id_component , priority, $filter_view, cc_ticket.status");


$HD_Form -> FG_OTHER_BUTTON1_LINK = "A2B_ticket_view.php?id=";
$HD_Form -> FG_OTHER_BUTTON1_IMG = Images_Path."/icon-viewdetails.gif";


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO")." ".strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_OTHER_BUTTON1 = true;
$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;
$HD_Form -> FG_ADDITION = false;


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
