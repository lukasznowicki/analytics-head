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
 * This is the main installer class. We won't use installation/activation/deactivation
 * hooks here, because they can't handle update (since WP3.1)
 *
 * @link http://core.trac.wordpress.org/ticket/14915
 */
class Install {

	/**
	 * This method will be executed every single page load. It isn't very good
	 * thing to do but we have no choice becuase of that updating issue
	 *
	 * @link http://core.trac.wordpress.org/ticket/14915
	 * @see  Options
	 */
	public function __construct( Options $opt ) {
		if ( $opt->do_install ) {
			/**
			 * Well, according to options class, we need installation...
			 */
			$this->do_install();
			$opt->reload_options();
		}
	}

	/**
	 * This method will perform installation script. It will check for old
	 * options and will take care of them.
	 *
	 * @return void
	 */
	private function do_install() {
		$o  = [];
		$o5 = $this->get_options_v5();
		$o6 = $this->get_options_v6();
		$o1 = $this->get_options_v1();
		if ( ! is_null( $o5 ) ) {
			$this->update_options_v5( $o, $o5 );
		}
		if ( ! is_null( $o6 ) ) {
			$this->update_options_v6( $o, $o6 );
		}
		if ( ! is_null( $o1 ) ) {
			$this->update_options_v1( $o, $o1 );
		}
		$this->update_options_first1( $o );
		/* now, let's update/add our plugin options */
		update_option( OPTION_NAME, $o );
		/* and then we may delete old options... all versions, before 1.x */
		# version 0.1/0.2
		delete_option( 'wordpress_ln_gah_ad' );
		delete_option( 'wordpress_ln_gah_id' );
		# version 0.3
		delete_option( 'wordpress_ln_p_ah_ga_id' );
		delete_option( 'wordpress_ln_p_ah_add_for_admins' );
		# version 0.4.1
		delete_option( 'wordpress_ln_p_ah' );
		# version 0.5.x
		delete_option( 'wordpress_lnpo_ah' );
		# version 0.6
		delete_option( 'rmnd_phylax_analytics_head' );
	}

	/**
	 * Get options stored by 0.5.x version of the plugin
	 *
	 * @return mixed Null if there are no options or array when there are something
	 */
	private function get_options_v5() {
		$o = get_option( 'wordpress_lnpo_ah' );
		if ( $o === false ) {
			return null;
		}

		return $o;
	}

	/**
	 * Get options stored by 0.6 version of the plugin
	 *
	 * @return mixed Null if there are no options or serialized string when there are something
	 */
	private function get_options_v6() {
		$o = get_option( 'rmnd_phylax_analytics_head' );
		if ( $o === false ) {
			return null;
		}

		return $o;
	}

	/**
	 * Get options stored by 1.x version of the plugin
	 *
	 * @return mixed Null if there are no options or array when there are something
	 */
	private function get_options_v1() {
		$o = get_option( OPTION_NAME );
		if ( $o === false ) {
			return null;
		}

		return $o;
	}

	/**
	 * Try to get v0.5.x options...
	 *
	 * @param array  $o Options array to be filled
	 * @param string $g Serialized option string used in version 0.5.x
	 *
	 * @return void
	 */
	private function update_options_v5( &$o, $g ) {
		$t = @unserialize( $g );
		if ( ! is_array( $t ) ) {
			return;
		}
		if ( isset( $t['GoogleID'] ) && ( $t['GoogleID'] != '' ) ) {
			$o['google_id'] = $t['GoogleID'];
		}
		if ( isset( $t['HideForAdmins'] ) && ( $t['HideForAdmins'] ) ) {
			$o['hide-in_admin_panel'] = 1;
		}
		if ( isset( $t['HideForAdmins'] ) && ( ! $t['HideForAdmins'] ) ) {
			$o['hide_in-admin_panel'] = 0;
		}
	}

	/**
	 * Try to get v0.6 options...
	 *
	 * @param array  $o Options array to be filled
	 * @param string $t array of options used in version 0.6
	 *
	 * @return void
	 */
	private function update_options_v6( &$o, $t ) {
		if ( ! is_array( $t ) ) {
			return;
		}
		if ( isset( $t['GoogleID'] ) && ( $t['GoogleID'] != '' ) ) {
			$o['google_id'] = $t['GoogleID'];
		}
		if ( isset( $t['HideInAdmin'] ) && ( $t['HideInAdmin'] ) ) {
			$o['hide-in_admin_panel'] = 1;
		}
		if ( isset( $t['HideInAdmin'] ) && ( ! $t['HideInAdmin'] ) ) {
			$o['hide-in_admin_panel'] = 0;
		}
		if ( isset( $t['HideForLogged'] ) && ( $t['HideForLogged'] ) ) {
			$o['hide-for_logged_users'] = 1;
		}
		if ( isset( $t['HideForLogged'] ) && ( ! $t['HideForLogged'] ) ) {
			$o['hide-for_logged_users'] = 0;
		}
		if ( isset( $t['IntoFooter'] ) && ( $t['IntoFooter'] ) ) {
			$o['move-to_footer'] = 1;
		}
		if ( isset( $t['IntoFooter'] ) && ( ! $t['IntoFooter'] ) ) {
			$o['move-to_footer'] = 0;
		}
	}

	private function update_options_v1( &$o, $t ) {
		$o = $t;
	}

	/**
	 * First time version 1.x? We need to update one more thing,
	 * plugin version id. And of course, fill option array with default
	 * values if there are none of them.
	 *
	 * @param array $o Options array to be filled
	 */
	private function update_options_first1( &$o ) {
		$o['version'] = PLUGIN_VERSION;
		if ( ! isset( $o['google_id'] ) ) {
			$o['google_id'] = '';
		}
		if ( ! isset( $o['hide-in_admin_panel'] ) ) {
			$o['hide-in_admin_panel'] = 1;
		}
		if ( ! isset( $o['hide-for_logged_users'] ) ) {
			$o['hide-for_logged_users'] = 0;
		}
		if ( ! isset( $o['move-to_footer'] ) ) {
			$o['move-to_footer'] = 0;
		}
		if ( ! isset( $o['use_hooks'] ) ) {
			$o['use_hooks'] = 0;
		}
		if ( ! isset( $o['add-plugin_info'] ) ) {
			$o['add-plugin_info'] = 0;
		}
	}

	/**
	 * This method will update v1.x plugin options. Because this is the first
	 * version of 1.x, it should not be used...
	 *
	 * @param string Serialized string with options
	 *
	 * @return array New options array
	 */
	private function update_v1( $o ) {
		$o            = $t;
		$o['version'] = PLUGIN_VERSION;
	}

}

# EOF