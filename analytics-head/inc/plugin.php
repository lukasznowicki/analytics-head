<?php
/**
 * This is main plugin operational file
 */

namespace Phylax\WPPlugin\AnalyticsHead;

/**
 * Make sure to run this file within WordPress environment and with our plugin only
 */
( defined( 'ABSPATH' ) && defined( __NAMESPACE__ . '\PLUGIN_DIR' ) ) or die( 'Sorry, you cannot use me outside the WordPress environment.' );

/**
 * This is the main plugin class. Here we will decide to add Google code or not
 * and when to do that. We can also trigger hooks and use filter if needed
 */
class Plugin {

	/**
	 * Handler for Options class
	 *
	 * @see Options
	 */
	private $o;

	/**
	 * This is construction method, it requires Options handler. The only
	 * thing it is doing, except assigning options handler, is loading
	 * text-domain. Everything else in this class must be called.
	 *
	 * @param Options $options Options class instance, it will be stored in $o
	 *
	 * @see $o
	 */
	public function __construct( Options $options ) {
		$this->o = $options;
		add_action( 'init', [
			$this,
			'init',
		] );
	}

	/**
	 * This method will hood wp_loaded action, if there is google id code provided
	 */
	public function add_tracking_code() {
		/* no google id, let's get out of here */
		if ( ! isset( $this->o->data['google_id'] ) || ( $this->o->data['google_id'] == '' ) ) {
			return;
		}
		add_action( 'wp_loaded', [
			$this,
			'wp_loaded',
		] );
	}

	/**
	 * This method will check if showing google tracking code is applicable
	 */
	public function wp_loaded() {
		global $current_user;
		/* if we are in admin panel and we hide google code for admins then let's get out of here */
		if ( is_admin() && isset( $this->o->data['hide-in_admin_panel'] ) && ( $this->o->data['hide-in_admin_panel'] ) ) {
			return;
		}
		/* if current user is logged in and we want to hide google code for logged users... you know what to do */
		if ( ( $current_user->ID != 0 ) && isset( $this->o->data['hide-for_logged_users'] ) && $this->o->data['hide-for_logged_users'] ) {
			return;
		}
		/* So, we may add google tracking code, let's find out where... */
		if ( isset( $this->o->data['move-to_footer'] ) && $this->o->data['move-to_footer'] ) {
			/* in the footer */
			if ( is_admin() ) {
				/* footer in admin area */
				add_action( 'admin_footer', [
					$this,
					'print_code',
				], 9999 );
			} else {
				/* footer on common page */
				add_action( 'wp_footer', [
					$this,
					'print_code',
				], 9999 );
			}
		} else {
			/* in the head section */
			if ( is_admin() ) {
				/* head section in admin area */
				add_action( 'admin_head', [
					$this,
					'print_code',
				], 9999 );
			} else {
				/* head section on common page */
				add_action( 'wp_head', [
					$this,
					'print_code',
				], 9999 );
			}
		}
	}

	/**
	 * This method is responsible for printing google analytics tracking code
	 */
	public function print_code() {
		if ( isset( $this->o->data['use_hooks'] ) && ( $this->o->data['use_hooks'] ) ) {
			$use_hooks = true;
		} else {
			$use_hooks = false;
		}
		if ( isset( $this->o->data['add-plugin_info'] ) && ( $this->o->data['add-plugin_info'] ) ) {
			$add_info = true;
		} else {
			$add_info = false;
		}
		if ( $use_hooks ) {
			do_action( 'pp_google_analytics_head_before' );
		}
		$c = \PHP_EOL;
		if ( $add_info ) {
			$c .= "\t" . '<!-- ' . __( 'BEGIN: Added by Google Analytics Head plugin', 'analytics-head' ) . ' -->' . \PHP_EOL;
		}
		$c.= "\t" . "<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src=\"https://www.googletagmanager.com/gtag/js?id=" . $this->o->data['google_id'] . "\"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', '" . $this->o->data['google_id'] . "');
	</script>" . \PHP_EOL;
		if ( $use_hooks ) {
			$c = apply_filters( 'pp_google_analytics_head_output', $c );
		}
		echo $c;
		if ( $use_hooks ) {
			do_action( 'pp_google_analytics_head_after' );
		}
	}

	/**
	 * This method will hook into admin_notices action, if there is no Google ID code.
	 */
	public function id_admin_notice() {
		if ( ! isset( $this->o->data['google_id'] ) || ( $this->o->data['google_id'] == '' ) ) {
			add_action( 'admin_notices', [
				$this,
				'admin_notices',
			] );
		}
	}

	/**
	 * This method will show notice about empty google id slot
	 */
	public function admin_notices() {
		global $pagenow;
		/**
		 * Show notice everywhere in the admin panel, except our own settings page.
		 */
		if ( ( $pagenow != 'options-general.php' ) || ( ( $pagenow == 'options-general.php' ) && ! isset( $_GET['page'] ) ) || ( isset( $_GET['page'] ) && ( $_GET['page'] != OPTION_SLUG ) ) ) {
			echo '<div class="update-nag"><p>';
			printf( __( 'Please remember to <a href="%s">setup Google Tracking ID</a> in order to use Google Analytics Head plugin!', 'analytics-head' ), 'options-general.php?page=' . OPTION_SLUG );
			echo '</p></div>';
		}
	}

	/**
	 * This method will hook into plugin_action_links filter so we can add
	 * our own link to our plugin item
	 */
	public function action_settings_link() {
		add_filter( 'plugin_action_links', [
			$this,
			'plugin_action_links',
		], 10, 2 );
	}

	/**
	 * This method will add additional 'settings' link at your
	 * plugins list page. This is a standard wordPress filter.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/plugin_action_links/
	 */
	public function plugin_action_links( $links, $file ) {
		if ( PLUGIN_ACTION_LINK == $file ) {
			$links[] = '<a href="options-general.php?page=' . OPTION_SLUG . '">' . __( 'Settings', 'analytics-head' ) . '</a>';
		}

		return $links;
	}

	/**
	 * This method will load language files if needed
	 */
	public function init() {
		load_plugin_textdomain( 'analytics-head', false, PLUGIN_BASE );
	}

}

# EOF