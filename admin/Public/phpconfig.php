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



/**
 *
 * Asterisk configuration file interface script
 *
 *
 *
 *
 *
 * phpconfig:,v 1.0 2003/07/03 17:19:37
 * Authors: Dave Packham <dave.packham@utah.edu>
 *          Rob Birkinshaw <robert.birkinshaw@utah.edu>
 */

// add for a2billing 
include_once ("../lib/admin.defines.php");
include_once ("../lib/admin.module.access.php");
include_once ("../lib/regular_express.inc");
include_once ("../lib/phpagi/phpagi-asmanager.php");
include_once ("../lib/admin.smarty.php");

if (!has_rights(ACX_ACXSETTING)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

check_demo_mode_intro();

require_once ("phpconfig_init.php");
require_once ("cls_phpconfig.php");
require_once ("cls_phpconfig_html.php");

// create and initalize objects //
$page = new Open_Conf_HTML();
$conf = new Open_Conf();

// setup 3pane html theme
$page->OC_HTML_set3Pane(true);

// pass thru phpconfig_init paramaters to object
$conf->OC_setConfDirectories($conf_directories);
$conf->OC_setTempDir($temporary_directory);
$conf->OC_setTempPrefix($temporary_file_prefix);
$conf->OC_setAccessFile($access_file);
$conf->OC_setConfDir($default_conf_file_direcotry, $conf_directories);
$conf->OC_setConfFilter($conf_file_filter);
$conf->OC_setResetCmd($reset_cmd);
$conf->OC_setRemark($remark);

// pass thru html output parameters
$page->OC_HTML_setTextareaRows($textarea_rows);
$page->OC_HTML_setImagesDir($images_dir);
$page->OC_HTML_setLogo($logo);
$page->OC_HTML_setTitle($title);
$page->OC_HTML_setPageTitle($page_title);
$page->OC_HTML_setDescription($description);
$page->OC_HTML_setKeywords($keywords);
$page->OC_HTML_setWebmaster($webmaster);
$page->OC_HTML_setDisclaimer($disclaimer);
$page->OC_HTML_setFooterText($footer_text);
$page->OC_HTML_setLogoLink($logo_link);

// init top menu bar
$page->OC_HTML_setHeaderBar(array_merge($conf->_OC_conf_dirs, array (
	"Re-Read Configs" => "phpconfig.php?reset=reset"
)));

// init side menus
$menuList = array ();
$rightMenuList = array ();

session_start();

// this session variable will be set by a login screen
// in a future release
// for now, fake it for the prototype
$_SESSION['valid_user'] = $fakeuser;

$conf->OC_checkValidUser();

if (isset ($_GET['file'])) // conf file requested via menu link
	{
	$conf->OC_setConfDir($_SESSION['confdir'], $conf_directories);
	// read the file into array
	$result = $conf->OC_readConfFile($_GET['file']);

	if ($result) // file read successfully
		{
		// change page title to conf file
		$page->OC_HTML_setPageTitle($_GET['file']);

		// side menu population of file sections
		foreach ($conf->_OC_the_sections as $record) {
			$menuList[$record[0]] = $_SERVER['PHP_SELF'] . "?file=" . $_GET['file'] . "&section_conf=$record[0]";
		}

		if ($_GET['section_conf']) // sidemenu link was clicked (edit section)
			{
			// create HTML header and left menu
			$page->OC_HTML_doHtmlHeader_a2billing($smarty);
			$page->OC_HTML_doSideMenu($menuList);

			// obtain section entries and comments
			$theItems = $conf->OC_getConfSectionItems($_GET['section_conf']);
			if (!$theItems) {
				die("Unable to obtain section data");
			}

			// display update textarea form
			$page->OC_HTML_doConfigurationForm($_GET['file'], $_GET['section_conf'], $theItems, $conf->_OC_md5);

			// grab values for section and display on right menu
			reset($theItems);
			$result = $conf->OC_readConfValues($theItems);

			if ($result) {
				// populate right menu
				foreach ($conf->_OC_the_values as $record) {
					$rightMenuList["$record"] = "javascript:findString('$record');";
				}

				// set right menu list
				$page->OC_HTML_setRightMenuList($rightMenuList);
			}

		}
		elseif ($_POST['tryUpdate']) // update button pushed from edit section page
		{
			// create HTML header and left menu
			$page->OC_HTML_doHtmlHeader_a2billing($smarty);
			$page->OC_HTML_doSideMenu($menuList);

			// attempt to write the changes
			$result = $conf->OC_writeConfSection($_POST['themd5'], $_POST['updateSection'], $_POST['section_text']);

			if (!$result) {
				echo "Write failed!<br>";
			} else {
				echo "Write completed successfully. <br>";
			}
		}

	} else // problem reading conf file
		{
		// create HTML header and left menu
		$page->OC_HTML_doHtmlHeader_a2billing($smarty);
		$page->OC_HTML_doSideMenu();
		echo "Unable to read configuration file.<br>";
	}
}
elseif (isset ($_GET['reset'])) // top menu "re-read conf" pushed
{
	// create HTML header and left menu
	$page->OC_HTML_doHtmlHeader_a2billing($smarty);
	$page->OC_HTML_doSideMenu();

	// check if person is authorized to execute re-read configuration
	//$access_result = $conf->OC_checkAccess($_SESSION['valid_user']);
	$access_result = true;

	if ($access_result) {
		// re-read configuration
		/*@system($conf->_OC_reset_cmd, $result);
		if ($result == 0)
		{
		    echo "<br>Reset succeded.<br>";
		}
		else
		{
		    echo "<br>Reset failed.<br>";
		}*/

		$as = new AGI_AsteriskManager();
		$res = $as->connect(MANAGER_HOST, MANAGER_USERNAME, MANAGER_SECRET);
		if ($res)
			$res = $as->Command('reload');

		if ($res) {
			echo "<br>Reset succeded.<br>";
		} else {
			echo "<br>Reset failed.<br>";
		}
		$as->disconnect();

	}
} else // default page
	{
	$page->OC_HTML_doHtmlHeader_a2billing($smarty);

	if (isset ($_GET['dir'])) {
		$conf->OC_setConfDir($_GET['dir'], $conf_directories);
	}

	$_SESSION['confdir'] = $conf->_OC_conf_dir;

	// grab and display conf files for current directory
	$files = $conf->OC_getConfFiles();

	if ($files) {
		foreach ($files as $file) {
			$menuList[$file] = $_SERVER['PHP_SELF'] . "?file=$file&section_conf=$file";
			// HTML output
			$page->OC_HTML_setMenuList($menuList);
		}
	}
	$page->OC_HTML_doSideMenu();

	if (!$files) {
		echo "No configuration files found.<br>";
	}
}
// HTML output
$page->OC_HTML_doRightMenu();
$page->OC_HTML_doHtmlFooter_a2billing($smarty);

