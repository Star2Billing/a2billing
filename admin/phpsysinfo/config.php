<?php
/***************************************************************************
 *   Copyright (C) 2008 by phpSysInfo - A PHP System Information Script    *
 *   http://phpsysinfo.sourceforge.net/                                    *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 *   This program is distributed in the hope that it will be useful,       *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *   GNU General Public License for more details.                          *
 *                                                                         *
 *   You should have received a copy of the GNU General Public License     *
 *   along with this program; if not, write to the                         *
 *   Free Software Foundation, Inc.,                                       *
 *   59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.             *
 ***************************************************************************/

// add for a2billing 
include_once ("../lib/admin.defines.php");
include_once ("../lib/admin.module.access.php");
include_once ("../lib/admin.smarty.php");

if (! has_rights (ACX_ADMINISTRATOR)){ 
	echo "Error loading phpSysInfo!";
	die();	   
}

//
// $Id: config.php.new,v 1.35 2008/05/31 20:38:57 bigmichi1 Exp $
//

// turn on dubugging of some functions and include errors and warnings in xml and provide a 
// popup for displaying errors
// false = no debug infos are stored in xml or displayed
// true = debug infos stored in xml and displayed *be carfull if set this to true, may include infos from your pc*
define('debug', false);

// define the default language and template here
define('lang', 'en');
define('template', 'phpsysinfo.css');

// display the virtual host name and address
// default is canonical host name and address
// Use define('useVhost', true); to display virtual host name.
define('useVhost', false);

// define the motherboard monitoring program here
// we support four programs so far
// 1. lmsensors  http://www.lm-sensors.org/
// 2. healthd    http://healthd.thehousleys.net/
// 3. hwsensors  http://www.openbsd.org/
// 4. mbmon      http://www.nt.phys.kyushu-u.ac.jp/shimizu/download/download.html
// 5. mbm5       http://mbm.livewiredev.com/

// Example: If you want to use lmsensors.
// define('sensorProgram', 'lmsensors');
define('sensorProgram', false);

// show mount point
// true = show mount point
// false = do not show mount point
define('showMountPoint', true);

// show bind
// true = display filesystems mounted with the bind options under Linux
// false = hide them
define('showBind', false);

// show inode usage
// true = display used inodes in percent
// false = hide them
define('showInodes', true);

// Hide mount(s). Example:
// define('hideMounts', '/home,/usr');
define('hideMounts', '');

// Hide filesystem types. Example:
// define('hideFstypes', 'tmpfs,usbfs');
define('hideFstypes', '');

// Hide network interfaces. Example:
// define('hideNetworkInterface', 'eth0, sit0');
define('hideNetworkInterface', '');

// if the hddtemp program is available we can read the temperature, if hdd is smart capable
// !!ATTENTION!! hddtemp might be a security issue
// define('hddTemp', 'tcp');	// read data from hddtemp deamon (localhost:7634)
// define('hddTemp', 'suid');     // read data from hddtemp programm (must be set suid)
define('hddTemp', false);

// show a graph for current cpuload
// true = displayed, but it's a performance hit (because we have to wait to get a value, 1 second)
// false = will not be displayed
define('loadBar', true);

// additional paths where to look for installed programs
// e.g. define('addPaths', '/opt/bin','/opt/sbin');
define('addPaths', false);

// format in which temperature is displayed (not implemented)
// 'c'    shown in celsius
// 'f'    shown in fahrenheit
// 'c-f'	both shown first celsius and fahrenheit in braces
// 'f-c'	both shown first fahrenheit and celsius in braces
define('tempFormat', 'c');

// UPS information
// We support the following programs at the moment
// 1. apcupsd http://www.apcupsd.com/
define('upsProgram', false);

// apcupsd supports multiple UPSes you can specify comma delimited list in the form <hostname>:<port>
// or <ip>:<port>. The defaults are: 127.0.0.1:3551
// See the following parameters in apcupsd.conf: NETSERVER, NISIP, NISPORT
define('apcupsdUpsList', '127.0.0.1:3551');

// byteFormat controls the units & format for network, memeory and filesystem 
// 'G' or 'g'    everything is in GigaByte
// 'M' or 'm'    everything is in MegaByte
// 'K' or 'k'    everything is in KiloByte
// 'auto'        everything is automatic done if value is to big for, e.g M then it will be in G
define('byteFormat', 'auto');

// Plugins that should be included in xml and output
// list of plugins should look like "plugin,plugin,plugin"
// define('PSI_PLUGINS', 'plugin,plugin'); // list of plugins
// define('PSI_PLUGINS', false); //no plugins
define('PSI_PLUGINS', false);
?>
