<?php

	// Define the webserver and path parameters
	// * DIR_FS_* = Filesystem directories (local/physical)
	// * DIR_WS_* = Webserver directories (virtual/URL)
	define('HTTP_SERVER', $A2B->config["epayment_method"]["http_server"]); 
	define('HTTPS_SERVER', $A2B->config["epayment_method"]["http_server"]);
	define('ENABLE_SSL', $A2B->config["epayment_method"]["enable_ssl"]);  

	define('HTTP_COOKIE_DOMAIN', $A2B->config["epayment_method"]["http_domain"]);
	define('HTTPS_COOKIE_DOMAIN', $A2B->config["epayment_method"]["http_domain"]);
	define('HTTP_COOKIE_PATH', $A2B->config["epayment_method"]["dir_ws_http"]);
	define('HTTPS_COOKIE_PATH', $A2B->config["epayment_method"]["dir_ws_http"]);
	define('DIR_WS_HTTP_CATALOG', $A2B->config["epayment_method"]["dir_ws_http"]);
	define('DIR_WS_HTTPS_CATALOG', $A2B->config["epayment_method"]["dir_ws_http"]);


	define('PAYPAL_PAYMENT_URL', $A2B->config["epayment_method"]["paypal_payment_url"]);
	define('AUTHORIZE_PAYMENT_URL', $A2B->config["epayment_method"]["authorize_payment_url"]);
	define('PLUGNPAY_PAYMENT_URL', $A2B->config["epayment_method"]["plugnpay_payment_url"]);
	define('STORE_NAME', $A2B->config["epayment_method"]["store_name"]);

	define('DIR_WS_IMAGES', 'images/');
	define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
	define('DIR_WS_INCLUDES', 'includes/');
	define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
	define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
	define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
	define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
	define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');

	define('STORE_SESSIONS', ''); // leave empty '' for default handler or set to 'mysql'

