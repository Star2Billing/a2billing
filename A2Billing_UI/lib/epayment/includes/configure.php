<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)
  define('HTTP_SERVER', 'http://202.154.239.73'); // eg, http://localhost - should not be empty for productive servers
  define('HTTPS_SERVER', 'https://202.154.239.73'); // eg, https://localhost - should not be empty for productive servers
  define('ENABLE_SSL', true); // secure webserver for checkout procedure?
  define('HTTP_COOKIE_DOMAIN', '202.154.239.73');
  define('HTTPS_COOKIE_DOMAIN', '202.154.239.73');
  define('HTTP_COOKIE_PATH', '/AB/trunk/A2BCustomer_UI/');
  define('HTTPS_COOKIE_PATH', '/AB/trunk/A2BCustomer_UI/');
  define('DIR_WS_HTTP_CATALOG', '/AB/trunk/A2BCustomer_UI/');
  define('DIR_WS_HTTPS_CATALOG', '/AB/trunk/A2BCustomer_UI/');
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');

  define('DIR_WS_DOWNLOAD_PUBLIC', 'pub/');
  define('DIR_FS_CATALOG', '/var/www/html/AB/trunk/A2BCustomer_UI/');
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');

// define our database connection
  define('DB_SERVER', '192.168.1.124'); // eg, localhost - should not be empty for productive servers
  define('DB_SERVER_USERNAME', 'a2billinguser');
  define('DB_SERVER_PASSWORD', 'a2billing');
  define('DB_DATABASE', 'oscommerce');
  define('USE_PCONNECT', 'false'); // use persistent connections?
  define('STORE_SESSIONS', ''); // leave empty '' for default handler or set to 'mysql'
?>
