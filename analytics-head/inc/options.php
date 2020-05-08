<?php
/**
 * This is main installation file
 */

namespace Phylax\WPPlugin\AnalyticsHead;

/**
 * Make sure to run this file within WordPress environment and with our plugin only
 */
( defined( 'ABSPATH' ) && defined( __NAMESPACE__ . '\PLUGIN_DIR' ) ) or die( 'Sorry, you cannot use me outside the WordPress environment.' );

/**
 * This class will handle options manipulation.
 */
class Options {

	/**
	 * We are storing options here
	 */
	public $data = null;

	/**
	 * Should we perform install action?
	 */
	public $do_install = false;

	/**
	 * This method will be executed every time options class is loaded
	 */
	public function __construct() {
		/**
		 * Get current options, if available.
		 */
		$current = get_option( OPTION_NAME );
		if ( $current === false ) {
			/**
			 * There are no options here. Let's see if we can find some old options...
			 * If we are, we will get it. If not, we will fill it with defaults, except
			 * Google ID of course.
			 */
			$this->do_install = true;
		} else {
			/**
			 * We've got options on board! Let's see if those aren't current options
			 */
			if ( isset( $current['version'] ) && ( $current['version'] < PLUGIN_VERSION ) ) {
				$this->do_install = true;
			}
		}
		if ( ! $this->do_install ) {
			$this->data = $current;
		}
	}

	/**
	 * This method will simply reload options
	 */
	public function reload_options() {
		$this->data = get_option( OPTION_NAME );
	}

}

# EOF