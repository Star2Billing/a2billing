<?php

// add for a2billing
include_once '../lib/admin.defines.php';
include_once '../lib/admin.module.access.php';
include_once '../lib/admin.smarty.php';

if (! has_rights (ACX_ADMINISTRATOR)) {
    echo "Error loading phpSysInfo!";
    die();
}

// phpSysInfo - A PHP System Information Script
// http://phpsysinfo.sourceforge.net/

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

// $Id: config.php.new,v 1.23 2007/02/18 19:02:59 bigmichi1 Exp $

// if $webpath set to an value it will be possible to include phpsysinfo with a simple include() statement in other scripts
// but the structure in the phpsysinfo directory can't be changed
// $webpath specifies the absolute path when you browse to the phpsysinfo page
// e.g.: your domain  www.yourdomain.com
//       you put the phpsysinfo directory at /phpsysinfo in the webroot
//       then normally you browse there with www.yourdomain.com/phpsysinfo
//       now you want to include the index.php from phpsysinfo in a script, locatet at /
//       then you need to set $webpath to /phpsysinfo/
// if you put the phpsysinfo folder at /tools/phpsysinfo $webpath will be /tools/phpsysinfo/
// you don't need to change it, if you don't include it in other pages
// so default will be fine for everyone
$webpath = "";

// define the default lng and template here
$default_lng='en';
$default_template='classic';

// hide language and template picklist
// false = display picklist
// true = do not display picklist
$hide_picklist = false;

// display the virtual host name and address
// default is canonical host name and address
$show_vhostname = false;

// define the motherboard monitoring program here
// we support four programs so far
// 1. lmsensors  http://www.lm-sensors.org/
// 2. healthd    http://healthd.thehousleys.net/
// 3. hwsensors  http://www.openbsd.org/
// 4. mbmon      http://www.nt.phys.kyushu-u.ac.jp/shimizu/download/download.html
// 5. mbm5       http://mbm.livewiredev.com/

// $sensor_program = "lmsensors";
// $sensor_program = "healthd";
// $sensor_program = "hwsensors";
// $sensor_program = "mbmon";
// $sensor_program = "mbm5";
$sensor_program = "";

// show mount point
// true = show mount point
// false = do not show mount point
$show_mount_point = true;

// show bind
// true = display filesystems mounted with the bind options under Linux
// false = hide them
$show_bind = false;

// show inode usage
// true = display used inodes in percent
// false = hide them
$show_inodes = true;

// Hide mount(s). Example:
// $hide_mounts = array( '/home', '/dev' );
$hide_mounts = array();

// Hide filesystem typess. Example:
// $hide_fstypes = array( 'tmpfs', 'usbfs' );
$hide_fstypes = array();

// if the hddtemp program is available we can read the temperature, if hdd is smart capable
// !!ATTENTION!! hddtemp might be a security issue
// $hddtemp_avail = "tcp";	// read data from hddtemp deamon (localhost:7634)
// $hddtemp_avail = "suid";     // read data from hddtemp programm (must be set suid)

// show a graph for current cpuload
// true = displayed, but it's a performance hit (because we have to wait to get a value, 1 second)
// false = will not be displayed
$loadbar = true;

// additional paths where to look for installed programs
// e.g. $addpaths = array('/opt/bin', '/opt/sbin');
$addpaths = array();

// display error messages at the top of the page
// $showerrors = true;          // show the errors
// $showerrors = false;         // don't show the errors
$showerrors = true;

// format in which temperature is displayed
// $temperatureformat = "c";	// shown in celsius
// $temperatureformat = "f";	// shown in fahrenheit
// $temperatureformat = "c-f";	// both shown first celsius and fahrenheit in braces
// $temperatureformat = "f-c";	// both shown first fahrenheit and celsius in braces
$temperatureformat = "c";
