<?php

/**
 * @file
 * common functions - core handler
 */

/*
 * Checks if user is set and sets
 */
function checkErrorMessage() {

  if ($_SESSION['ari_error']) {
    $ret .= "<div class='error'>
               " . $_SESSION['ari_error'] . "
             </div>
             <br>";
    unset($_SESSION['ari_error']);
  }

  return $ret;
}

/*
 * Checks modules directory, and configuration, and loaded modules 
 */
function loadModules() {

  global $ARI_ADMIN_MODULES;
  global $ARI_DISABLED_MODULES;

  global $loaded_modules;

  $modules_path = "./modules";
  if (is_dir($modules_path)) {

    $filter = ".module";
    $recursive_max = 1;
    $recursive_count = 0;
    $files = getFiles($modules_path,$filter,$recursive_max,$recursive_count);

    foreach($files as $key => $path) {

      // build module object 
      include_once($path); 
      $path_parts = pathinfo($path);
      list($name,$ext) = split("\.",$path_parts['basename']);

      // check for module and get rank
      if (class_exists($name)) {

        $module = new $name();

        // check if admin module
        $found = 0;
        if ($ARI_ADMIN_MODULES) {
          $admin_modules = split(',',$ARI_ADMIN_MODULES);
          foreach ($admin_modules as $key => $value) {
            if ($name==$value) {
              $found = 1;
              break;
            }
          }
        }

        // check if disabled module
        $disabled = 0;
        if ($ARI_DISABLED_MODULES) {
          $disabled_modules = split(',',$ARI_DISABLED_MODULES);
          foreach ($disabled_modules as $key => $value) {
            if ($name==$value) {
              $disabled = 1;
              break;
            }
          }
        }

        // if not admin module or admin user add to module name to array
        if (!$disabled && (!$found || $_SESSION['ari_user']['admin'])) {
          $loaded_modules[$name] = $module;
        }
      }
    }
  }
  else {
    $_SESSION['ari_error'] = _("$path not a directory or not readable");
  }
}

/**
 * Builds database connections
 */
function databaseLogon() {

  global $STANDALONE;

  global $ASTERISKMGR_DBHOST;

  global $AMP_FUNCTIONS_FILES;
  global $AMPORTAL_CONF_FILE;

  global $LEGACY_AMP_DBENGINE;
  global $LEGACY_AMP_DBFILE;
  global $LEGACY_AMP_DBHOST;
  global $LEGACY_AMP_DBNAME;
  
  global $LEGACY_A2BILLING_DBENGINE;
  global $LEGACY_A2BILLING_DBFILE;
  global $LEGACY_A2BILLING_DBHOST;
  global $LEGACY_A2BILLING_DBNAME;

  global $ASTERISKCDR_DBENGINE;
  global $ASTERISKCDR_DBFILE;
  global $ASTERISKCDR_DBHOST;
  global $ASTERISKCDR_DBNAME;

  global $ARI_DISABLED_MODULES;

  global $loaded_modules;

  // get user
  if ($STANDALONE['use']) {

    $mgrhost = $ASTERISKMGR_DBHOST;
    $mgruser = $STANDALONE['asterisk_mgruser'];
    $mgrpass = $STANDALONE['asterisk_mgrpass'];

    $asteriskcdr_dbengine = $ASTERISKCDR_DBENGINE;
    $asteriskcdr_dbfile = $ASTERISKCDR_DBFILE;
    $asteriskcdr_dbuser = $STANDALONE['asteriskcdr_dbuser'];
    $asteriskcdr_dbpass = $STANDALONE['asteriskcdr_dbpass'];
    $asteriskcdr_dbhost = $ASTERISKCDR_DBHOST;
    $asteriskcdr_dbname = $ASTERISKCDR_DBNAME;
  } 
  else {
	
    if ($STANDALONE['a2billing']) {
	    
	    $mgrhost = $ASTERISKMGR_DBHOST;
	    $mgruser = $STANDALONE['asterisk_mgruser'];
	    $mgrpass = $STANDALONE['asterisk_mgrpass'];

	    $asteriskcdr_dbengine = $ASTERISKCDR_DBENGINE;
	    $asteriskcdr_dbfile = $ASTERISKCDR_DBFILE;
	    $asteriskcdr_dbuser = $STANDALONE['asteriskcdr_dbuser'];
	    $asteriskcdr_dbpass = $STANDALONE['asteriskcdr_dbpass'];
	    $asteriskcdr_dbhost = $ASTERISKCDR_DBHOST;
	    $asteriskcdr_dbname = $ASTERISKCDR_DBNAME;
	    
	    
	    $a2b_dbengine = $LEGACY_A2BILLING_DBENGINE;
	    $a2b_dbfile = $LEGACY_A2BILLING_DBFILE;
	    $a2b_dbuser = $STANDALONE['a2billing_dbuser'];
	    $a2b_dbpass = $STANDALONE['a2billing_dbpass'];
	    $a2b_dbhost = $LEGACY_A2BILLING_DBHOST;
	    $a2b_dbname = $LEGACY_A2BILLING_DBNAME;
	    
    } 
    else {
    
	    $include = 0;
	    $files = split(';',$AMP_FUNCTIONS_FILES);
	    foreach ($files as $file) {
	      if (is_file($file)) {
		include_once($file);
		$include = 1;
	      }
	    }

	    if ($include) {
	      $amp_conf = parse_amportal_conf($AMPORTAL_CONF_FILE);

	      $mgrhost = $ASTERISKMGR_DBHOST;
	      $mgruser = $amp_conf['AMPMGRUSER'];
	      $mgrpass = $amp_conf['AMPMGRPASS'];

	      $amp_dbengine = isset($amp_conf["AMPDBENGINE"]) ? $amp_conf["AMPDBENGINE"] : $LEGACY_AMP_DBENGINE;
	      $amp_dbfile = isset($amp_conf["AMPDBFILE"]) ? $amp_conf["AMPDBFILE"] : $LEGACY_AMP_DBFILE;
	      $amp_dbuser = $amp_conf["AMPDBUSER"];
	      $amp_dbpass = $amp_conf["AMPDBPASS"];
	      $amp_dbhost = isset($amp_conf["AMPDBHOST"]) ? $amp_conf["AMPDBHOST"] : $LEGACY_AMP_DBHOST;
	      $amp_dbname = isset($amp_conf["AMPDBNAME"]) ? $amp_conf["AMPDBNAME"] : $LEGACY_AMP_DBNAME;

	      $asteriskcdr_dbengine = $ASTERISKCDR_DBENGINE;
	      $asteriskcdr_dbfile = $ASTERISKCDR_DBFILE;
	      $asteriskcdr_dbuser = $amp_conf["AMPDBUSER"];
	      $asteriskcdr_dbpass = $amp_conf["AMPDBPASS"];
	      $asteriskcdr_dbhost = $ASTERISKCDR_DBHOST;
	      $asteriskcdr_dbname = $ASTERISKCDR_DBNAME;

	      unset($amp_conf);
	    }
    }    
  }

  // asterisk manager interface (berkeley database I think)
  global $asterisk_manager_interface;
  $asterisk_manager_interface = new AsteriskManagerInterface();

  $success = $asterisk_manager_interface->Connect($mgrhost,$mgruser,$mgrpass);
  if (!$success) {
    $_SESSION['ari_error'] =  
      _("ARI does not appear to have access to the Asterisk Manager.") . " ($errno)<br>" . 
      _("Check the ARI 'main.conf.php' configuration file to set the Asterisk Manager Account.") . "<br>" . 
      _("Check /etc/asterisk/manager.conf for a proper Asterisk Manager Account") . "<br>" .
      _("make sure [general] enabled = yes and a 'permit=' line for localhost or the webserver.");
    return FALSE;
  }

  // pear interface databases
  $db = new Database();

  // AMP asterisk database
  if (!$STANDALONE['use']) {
    if ($STANDALONE['a2billing']) {
    	    // --- echo "---- $a2b_dbengine, $a2b_dbfile, $a2b_dbuser,  $a2b_dbpass, $a2b_dbhost, $a2b_dbname";
    	    $_SESSION['dbh_asterisk'] = $db->logon($a2b_dbengine,
						   $a2b_dbfile,
						   $a2b_dbuser, 
						   $a2b_dbpass,
						   $a2b_dbhost,
						   $a2b_dbname);
	    
	    if (!isset($_SESSION['dbh_asterisk'])) {
	      $_SESSION['ari_error'] .= _("Cannot connect to the $amp_dbname database") . "<br>" .
				       _("Check AMP installation, asterisk, and ARI main.conf");
	      return FALSE;
	    }
    }
    else{
    	    $_SESSION['dbh_asterisk'] = $db->logon($amp_dbengine,
						   $amp_dbfile,
						   $amp_dbuser, 
						   $amp_dbpass,
						   $amp_dbhost,
						   $amp_dbname);;
	    if (!isset($_SESSION['dbh_asterisk'])) {
	      $_SESSION['ari_error'] .= _("Cannot connect to the $amp_dbname database") . "<br>" .
				       _("Check AMP installation, asterisk, and ARI main.conf");
	      return FALSE;
	    }

    }
  }

  // cdr database
  if (in_array('callmonitor',array_keys($loaded_modules))) {
    $_SESSION['dbh_cdr'] = $db->logon($asteriskcdr_dbengine,
                                      $asteriskcdr_dbfile,
                                      $asteriskcdr_dbuser, 
                                      $asteriskcdr_dbpass,
                                      $asteriskcdr_dbhost,
                                      $asteriskcdr_dbname);
    if (!isset($_SESSION['dbh_cdr'])) {
      $_SESSION['ari_error'] .= sprintf(_("Cannot connect to the $asteriskcdr_dbname database"),$asteriskcdr_dbname) . "<br>" .
                               _("Check AMP installation, asterisk, and ARI main.conf");
      return FALSE;
    }
  }

  return TRUE;
}

/**
 * Logout if needed for any databases
 */
function databaseLogoff() {

  global $asterisk_manager_interface;

  $asterisk_manager_interface->Disconnect();
}

/*
 * Checks if user is set and sets
 */
function loginBlock() {

  $login = new Login();

  if (isset($_REQUEST['logout'])) {
    $login->Unauth();
  }

  if (!isset($_SESSION['ari_user'])) {
    $login->Auth();

  }

  if (!isset($_SESSION['ari_user'])) {

    // login form
    $ret .= $login->GetForm();

    return $ret;
  }
}

/*
 * Main handler for website
 */
function handleBlock() {

  global $ARI_NO_LOGIN;

  global $loaded_modules;

  // check errors here and in login block
  $content .= checkErrorMessage();

  // check logout
  if ($_SESSION['ari_user'] && !$ARI_NO_LOGIN) {
    $logout = 1;
  }

  // if nothing set goto user default page
  if (!isset($_REQUEST['m'])) {
    $_REQUEST['m'] = $_SESSION['ari_user']['default_page'];
  }
  // if not function specified then use display page function
  if (!isset($_REQUEST['f'])) {
    $_REQUEST['f'] = 'display';
  }

  $m = $_REQUEST['m'];     // module
  $f = $_REQUEST['f'];     // function
  $a = $_REQUEST['a'];     // action

  // set arguments
  $args = array();
  foreach($_REQUEST as $key => $value) {
    $args[$key] = $value;
  }

  // set rank
  $ranked_modules = array();
  foreach ($loaded_modules as $module) {

    $module_methods = get_class_methods($module);    // note that PHP4 returns all lowercase
    while (list($index, $value) = each($module_methods)) {
      $module_methods[strtolower($index)] = strtolower($value);
    }
    reset($module_methods);
        
    $rank = 99999;
    $rank_function = "rank";
    if (in_array(strtolower($rank_function), $module_methods)) {
      $rank = $module->$rank_function(); 
    }

    $ranked_modules[$rank] = $module;
  }
  ksort($ranked_modules);

  // process modules
  foreach ($ranked_modules as $module) {

    // process module
    $name = get_class($module);    // note PHP4 returns all lowercase
    $module_methods = get_class_methods($module);    // note PHP4 returns all lowercase
    while (list($index, $value) = each($module_methods)) {
      $module_methods[strtolower($index)] = strtolower($value);
    }
    reset($module_methods);

    // init module
    $module->init();

    // add nav menu items
    $nav_menu_function = "navMenu";
    if (in_array(strtolower($nav_menu_function), $module_methods)) {
      $nav_menu .= $module->$nav_menu_function($args); 
    }      

    if (strtolower($m)==strtolower($name)) {

      // build sub menu 
      $subnav_menu_function = "navSubMenu";
      if (in_array(strtolower($subnav_menu_function), $module_methods)) {
        $subnav_menu .= $module->$subnav_menu_function($args); 
      }

      // execute function (usually to build content)
      if (in_array(strtolower($f), $module_methods)) {
        $content .= $module->$f($args);
      }
    }
  }

  // add logout link
  if ($logout != '') { 
    $nav_menu .= "<p><small><small><a href='" . $_SESSION['ARI_ROOT'] . "?logout=1'>" . _("Logout") . "</a></small></small></p>";
  } 

  // error message if no content
  if (!$content) {
    $content .= _("Page Not Found.");
  } 

  return array($nav_menu,$subnav_menu,$content);
}

/*
 * Main handler for website
 */
function handler() {

  global $ARI_VERSION;

  // version
  $ari_version = $ARI_VERSION;

  // check error
  $error = $_SESSION['ari_error'];

  // load modules
  loadModules();

  // login to database
  $success = databaseLogon();
  if ($success) {

    // check if login is needed
    $content = loginBlock();
    if (!isset($content)) {
        list($nav_menu,$subnav_menu,$content) = handleBlock();
    }
  }
  else {

    $display = new Display();

    $content .= $display->displayHeaderText("ARI");
    $content .= $display->displayLine();
    $content .= checkErrorMessage();
  }

  // log off any databases needed
  databaseLogoff();

  // check for ajax request and refresh or if not build the page
  if (isset($_REQUEST['ajax_refresh']) ) {

    echo "<?xml version='1.0' encoding='UTF-8' standalone='yes'?>
      <response>
        <nav_menu><![CDATA[" . $nav_menu . "]]></nav_menu>
        <subnav_menu><![CDATA[" . $subnav_menu . "]]></subnav_menu>
        <content><![CDATA[" . $content . "]]></content>
      </response>";
  }
  else {

    // build the page
    include_once("./theme/page.tpl.php"); 
  }
}

/**
 * Includes and run functions
 */  

// create asterisk manager interface singleton
$asterisk_manager_interface = '';

// array to keep track of loaded modules
$loaded_modules = array();

include_once("./includes/asi.php");
include_once("./includes/database.php");
include_once("./includes/display.php"); 
include_once("./includes/ajax.php");


?>
