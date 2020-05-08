<?php
/**
 * This file handles uninstall features
 */

namespace Phylax\WPPlugin\AnalyticsHead;

/**
 * Make sure to run this file within WordPress environment and with our plugin only
 */
( defined( 'ABSPATH' ) && defined( __NAMESPACE__ . '\PLUGIN_DIR' ) ) or die( 'Sorry, you cannot use me outside the WordPress environment.' );

/**
 * This is the uninstall class to handle uninstall hook. Everything it does
 * is removing options from WP *_options table.
 */
class Remove {

	/**
	 * This method is triggered on deleting plugin files. We use it to
	 * delete options field. It must be static because of the WordPress
	 *
	 * @return void
	 */
	public static function uninstall() {
		delete_option( OPTION_NAME );
	}

}

/**
 * Register uninstall hook.
 */
register_uninstall_hook( PLUGIN_FILE, [
	__NAMESPACE__ . '\Remove',
	'uninstall',
] );