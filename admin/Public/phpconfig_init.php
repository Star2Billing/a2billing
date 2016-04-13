<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2015 - Star2billing S.L.
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

/**
 *
 * Asterisk configuration file interface init file
 * we are using this init file to handle multiple installations
 * files.
 *
 *
 *
 *
 * phpconfig:,v 1.0 2003/07/03 17:19:37
 * Authors: Dave Packham <dave.packham@utah.edu>
 *          Rob Birkinshaw <robert.birkinshaw@utah.edu>
 */

define ("AST_CONF_DIR",'/etc/asterisk');

// directories that contain your confiles
$conf_directories = array(AST_CONF_DIR);

// temporary directory where conf file
// copies are placed
$temporary_directory = "/tmp";

// file prefix for temporary conf files
$temporary_file_prefix = "conf-";

// file that contains users in the form of "[username]"
// who have write privledges
$access_file = AST_CONF_DIR."/manager.conf";

// Since login screen is still in the works, fake
// the user has logged in with success for the prototype.
// for write access $fakeuser must exist in $access_file
$fakeuser = "myasterisk";

// conf file directory displayed by default
$default_conf_file_direcotry = AST_CONF_DIR;

// regular expression filter for valid conf files
//$conf_file_filter = "/.conf\$|.cnf\$/";
$conf_file_filter = "/a2billing.conf\$|^extensions.conf\$|^iax.conf|sip.conf\$|iax.conf\$/";

// command for switch to read conf files
// commented out for demo
//$reset_cmd = "/bin/asterisk.reload";
//chaged for demo
$reset_cmd = "./asterisk.reload";

// remark symbol in conf file
$remark = ";";

// HTML Output //
$images_dir = "images";
// page logo "top left"
$logo = "logo.gif";
// title for <head>
$title = "PBX Config for A2Billing";
// title for page
$page_title = "PBX Config for A2Billing";
// description for page <meta name="description">
$description = "The Open Source PBX";
// keywords for page <meta name="keywords">
$keywords = "PBX Asterisk";
// text to appear in footer bar
$footer_text = "Created by p0lar, Dave Packham & Rob Birkinshaw";
// link to web master
$webmaster = "http://www.asterisk.org";
// disclaimer
$disclaimer = "http://www.asterisk.org";
// link attached to logo
$logo_link = "http://www.asterisk.org";
// how many rows to set textarea control for
// editing and viewing conf files
$textarea_rows = 40;
