<?php
/*
 Plugin Name: Google Analytics Head
  Plugin URI: https://phylax.pl/plugins/google-analytics-head/
 Description: This plugin adds tracking code for <strong>Google Analytics</strong> to your WordPress site. Unlike other plugins, code is added to the <i>&lt;head&gt;</i> section, so you can authorize your site in <strong>Google Webmaster Tools</strong>. Of course you can also move your tracking code into footer.
      Author: Lukasz Nowicki
  Author URI: http://lukasznowicki.info/
     Version: 1.6.0
     License: GPLv2 or later
 License URI: http://www.gnu.org/licenses/gpl-2.0.html
 Text Domain: analytics-head
 Domain path: /languages
 Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZUE7KNWHW3CJ4
*/
namespace Phylax\WPPlugin\AnalyticsHead;

/**
 * This is a plugin, it means it should not be run without WordPress environment.
 * WordPress deifnes ABSPATH so we may use it to determine if there is WP somewhere
 * outside...
 */
defined('ABSPATH') or die('Cheatin\', huh?');

/**
 * Define a few constants for later use
 */
define( __NAMESPACE__ . '\PLUGIN_DIR', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );
define( __NAMESPACE__ . '\PLUGIN_FILE', __FILE__ );
define( __NAMESPACE__ . '\PLUGIN_BASE', dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
define( __NAMESPACE__ . '\PLUGIN_VERSION', 1060000 );
define( __NAMESPACE__ . '\PLUGIN_NAME', 'Google Analytics Head' );
define( __NAMESPACE__ . '\PLUGIN_ACTION_LINK', 'analytics-head/analytics_head.php' );

define( __NAMESPACE__ . '\OPTION_NAME', 'phylax_plugin-analytics_head' );
define( __NAMESPACE__ . '\OPTION_SLUG', 'phylax_plugin_analytics_head' );
define( __NAMESPACE__ . '\OPTION_GROUP', 'phylax_plugin_analytics_head_group' );
define( __NAMESPACE__ . '\OPTION_GENERAL', 'phylax_plugin_analytics_head_general' );
define( __NAMESPACE__ . '\OPTION_DEVELOPER', 'phylax_plugin_analytics_head_dev' );

/**
 * Include required files
 */
require_once( PLUGIN_DIR . 'inc' . DIRECTORY_SEPARATOR . 'options.php' );
require_once( PLUGIN_DIR . 'inc' . DIRECTORY_SEPARATOR . 'install.php' );
require_once( PLUGIN_DIR . 'inc' . DIRECTORY_SEPARATOR . 'remove.php' );
require_once( PLUGIN_DIR . 'inc' . DIRECTORY_SEPARATOR . 'plugin.php' );

/**
 * Let's take care of options and possibly installation...
 */
$options = new Options();
$install = new Install( $options );

/**
 * Let's see if user is in admin panel... We do not need to serve all
 * that stuff with options menu etc if not.
 */
if ( is_admin() ) {
	require_once( PLUGIN_DIR . 'inc' . DIRECTORY_SEPARATOR . 'admin.php' );
	$admin = new Admin( $options );
}

/**
 * Now, run the plugin!
 */
$plugin = new Plugin( $options );
if ( is_admin() ) {
	$plugin->action_settings_link();
	$plugin->id_admin_notice();
}

$plugin->add_tracking_code();

/**
 * We use this fake function to provide more translations in WordPress setup page.
 */
function never_invokes() {
	_('This plugin adds tracking code for <strong>Google Analytics</strong> to your WordPress site. Unlike other plugins, code is added to the <i>&lt;head&gt;</i> section, so you can authorize your site in <strong>Google Webmaster Tools</strong>. Of course you can also move your tracking code into footer.', 'analytics-head' );
}

# EOF