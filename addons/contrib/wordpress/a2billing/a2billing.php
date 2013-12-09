<?php

// pluginname A2Billing
// shortname A2Billing
// dashname A2Billing

/*
Plugin Name: A2Billing
Version: 1.0
Plugin URI: http://www.asterisk2billing.org/cgi-bin/trac.cgi/wiki/WordPress-plugins
Author: Belaid Arezqui <areski@gmail.com>
Description: A2Billing allows you to display information relative to A2Billing into WordPress, such as Rates

*/

global $wp_version;

$exit_msg='A2Billing requires WordPress 2.3 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update!</a>';

if (version_compare($wp_version,"2.3","<")) {
    exit ($exit_msg);
}

include_once (dirname(__FILE__)."/functions.php");

// Avoid name collisions.
if ( !class_exists('A2Billing') ) :

class a2billing
{
    // Name for our options in the DB
    public $DB_option = 'A2Billing_options';

    // the plugin URL
    public $plugin_url;

    // plugin language textdomain
    public $plugin_domain='A2Billing';

    // Initialize WordPress hooks
    public function A2Billing()
    {
        $this->plugin_url = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/'. dirname( plugin_basename(__FILE__) );

        $this->handle_load_domain();
        $options = $this->get_options();

        //add_filter('the_content',  array(&$this, 'content_filter'), 10);
        // Add Options Page
        add_action('admin_menu',  array(&$this, 'admin_menu'));

        // add shortcode handler
        add_shortcode('a2billing_rates', array(&$this, 'a2billing_rates_func'));

    }

    // Hook the admin menu
    public function admin_menu()
    {
        add_options_page('A2Billing Options', 'A2Billing', 8, basename(__FILE__), array(&$this, 'handle_options'));
    }

        // shortcode_handler
    // $atts    ::= array of attributes
    // $content ::= text within enclosing form of shortcode element
    // $code    ::= the shortcode found, when == callback name
    // example : [my-shortcode foo='bar']content[/my-shortcode]
    // [a2billing_rates id='1']
    public function a2billing_rates_func( $atts, $content=null, $code="" )
    {
        extract(shortcode_atts(array(
            'callplan_id' => '1'
        ), $atts));

        $options = $this->get_options();

        $callplan_clause = "";
        if ($callplan_id >= 1 && $callplan_id < 100000) {
            $callplan_clause = "&tariffgroupid=$callplan_id";
        }

        $url_get_rates = $options["url_a2billing_rates"];

        if (!strlen($url_get_rates) > 10) {
            return "Wrong URL for id : $id";
        }

        $curPageURL = get_curPageURL();
        $private_key = $options["api_private_key"];

        $private_key_md5 = md5($private_key);
        $url_wordpress_ratepage = $options["url_wordpress_ratepage"];

        $api_url = $url_get_rates . "?" .
                    "key=$private_key_md5" .
                    "&page_url=$curPageURL" .
                    "&field_to_display=t1.destination,t1.dialprefix,t1.rateinitial" .
                    "&column_name=Destination,Prefix,Rate/Min&field_type=,,money" .
                    "&fullhtmlpage=0&filter=prefix".
                    "$callplan_clause".
                    "&".$_SERVER['QUERY_STRING'];
                    #"&".$_SERVER['REDIRECT_QUERY_STRING'];
        // if you have some issue with the filter, you can try using REDIRECT_QUERY_STRING instead of QUERY_STRING
        // This might be due to rewrite rules avoiding to get _SERVER['QUERY_STRING']

        $content = a2b_open_url ($api_url);

        return $content;

    }

    public function handle_load_domain()
    {
        $locale = get_locale();
        $mofile = $this->plugin_url.'/lang/' .$this->plugin_domain. '-' . $locale . '.mo';
        load_textdomain($this->plugin_domain, $mofile);
    }

    // Handle our options
    public function get_options()
    {
       $options = array(
               'url_a2billing_rates' => '',
               'api_private_key' => ''
        );

        $saved = get_option($this->DB_option);

        if (!empty($saved)) {
            foreach ($saved as $key => $option)
                $options[$key] = $option;
        }
        if ($saved != $options)
            update_option($this->DB_option, $options);

        return $options;
    }

    // Set up default values
    public function install()
    {
        $this->get_options();

    }

    public function handle_options()
    {
        $options = $this->get_options();

        if ( isset($_POST['submitted']) ) {
            $options = array();
            $options['url_a2billing_rates']		= $_POST['url_a2billing_rates'];
            $options['api_private_key']			= $_POST['api_private_key'];

            update_option($this->DB_option, $options);
            echo '<div class="updated fade"><p>'.__('Plugin settings saved.').'</p></div>';
        }

        $action_url = $_SERVER['REQUEST_URI'];

        $url_a2billing_rates = $options['url_a2billing_rates'];
        $api_private_key = $options['api_private_key'];

        $action_url = $_SERVER['REQUEST_URI'];
        $imgpath = $this->plugin_url.'/img';

        include 'a2billing-options.php';
    }

}

endif;

if ( class_exists('A2Billing') ) :

    $A2Billing= new A2Billing();
    if (isset($A2Billing)) {
        register_activation_hook( __FILE__, array(&$A2Billing, 'install') );
    }
endif;
