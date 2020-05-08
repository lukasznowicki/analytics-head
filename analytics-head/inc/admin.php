<?php
/**
 * This is main admin file, it will add menu item, show options tab etc
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
class Admin {

	/**
	 * Handler for Options class
	 *
	 * @see Options
	 */
	private $o;

	/**
	 * This is construction method, it requires Options handler
	 *
	 * @param Options $o Options class instance, it will be stored in $o
	 *
	 * @see $o
	 */
	public function __construct( Options $o ) {
		$this->o = $o;
		add_action( 'admin_menu', [
			$this,
			'admin_menu',
		] );
		add_action( 'admin_init', [
			$this,
			'admin_init',
		] );
	}

	/**
	 * WordPress 'admin_menu' hook, it is the right place to add menu item.
	 *
	 * @return void
	 */
	public function admin_menu() {
		add_options_page( __( 'Google Analytics Head plugin options', 'analytics-head' ), PLUGIN_NAME, 'manage_options', OPTION_SLUG, [
				$this,
				'view_admin_page',
			] );
	}

	/**
	 * This method is used for sanitizing options passed by a WordPress form
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	public function sanitize_options( $options ) {
		if ( ! isset( $options['google_id'] ) ) {
			$options['google_id'] = '';
		}
		if ( $options['google_id'] != '' ) {
			$t = preg_match( '([U][A][-][0-9]{1,16}[-][0-9]{1,4})', $options['google_id'], $r );
			if ( $t > 0 ) {
				$options['google_id'] = $r[0];
			} else {
				$options['google_id'] = '';
			}
		}
		if ( ! isset( $options['hide-in_admin_panel'] ) ) {
			$options['hide-in_admin_panel'] = 0;
		}
		if ( ! isset( $options['hide-for_logged_users'] ) ) {
			$options['hide-for_logged_users'] = 0;
		}
		if ( ! isset( $options['move-to_footer'] ) ) {
			$options['move-to_footer'] = 0;
		}
		if ( ! isset( $options['use_hooks'] ) ) {
			$options['use_hooks'] = 0;
		}
		if ( ! isset( $options['add-plugin_info'] ) ) {
			$options['add-plugin_info'] = 0;
		}
		$options['version'] = PLUGIN_VERSION;

		return $options;
	}

	/**
	 * WordPress 'admin_init' hook, it is the right place to register
	 * settings/sections/fields etc for options page
	 *
	 * @return void
	 */
	public function admin_init() {
		register_setting( OPTION_GROUP, OPTION_NAME, [
			$this,
			'sanitize_options',
		] );
		add_settings_section( OPTION_GENERAL, __( 'General settings', 'analytics-head' ), [
			$this,
			'print_general_info_cb',
		], OPTION_SLUG );
		add_settings_field( 'google_id', '<label for="google_id">' . __( 'Google ID', 'analytics-head' ) . '</label>', [
			$this,
			'google_id_cb',
		], OPTION_SLUG, OPTION_GENERAL );
		add_settings_field( 'hide-in_admin_panel', __( 'Hiding', 'analytics-head' ), [
			$this,
			'hide_in_admin_panel_cb',
		], OPTION_SLUG, OPTION_GENERAL );
		add_settings_field( 'hide-for_logged_users', '', [
			$this,
			'hide_for_logged_users_cb',
		], OPTION_SLUG, OPTION_GENERAL );
		add_settings_field( 'move-to_footer', __( 'Move code', 'analytics-head' ), [
			$this,
			'move_to_footer_cb',
		], OPTION_SLUG, OPTION_GENERAL );

		add_settings_section( OPTION_DEVELOPER, __( 'Developer settings', 'analytics-head' ), [
			$this,
			'print_developer_info_cb',
		], OPTION_SLUG );
		add_settings_field( 'use_hooks', '', [
			$this,
			'use_hooks_cb',
		], OPTION_SLUG, OPTION_DEVELOPER );
		add_settings_field( 'add-plugin_info', '', [
			$this,
			'add_plugin_info_cb',
		], OPTION_SLUG, OPTION_DEVELOPER );
	}

	/**
	 * This method will get options and view it on plugin-options page
	 *
	 * @return void
	 */
	public function view_admin_page() {
		global $current_user;
		?>
        <div class="wrap">
            <h2><?= PLUGIN_NAME; ?></h2>
            <form method="post" action="options.php">
				<?php
				settings_fields( OPTION_GROUP );
				do_settings_sections( OPTION_SLUG );
				submit_button();
				?>
            </form>
            <p class="description"><?php
				echo '<a href="' . 'https://phylax.pl/plugins/google-analytics-head/' . '">' . PLUGIN_NAME . '</a>, ';
				echo '&copy;2011-' . date( 'Y' ) . ' <a href="http://lukasznowicki.info/">Łukasz Nowicki</a>. ';
				printf( __( 'You feel thankful? You may <a href="%s">buy me a beer or coke</a> if you wish :)', 'analytics-head' ), 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZUE7KNWHW3CJ4' );
				?></p>
        </div>
		<?php
	} // view_admin_page

	/**
	 * Write common information about general options page
	 *
	 * @return void
	 */
	public function print_general_info_cb() {
		_e( 'Enter your settings below. Note that you must enter valid Google ID code to make this plugin work!', 'analytics-head' );
	}

	/**
	 * Write common information about general options page
	 *
	 * @return void
	 */
	public function print_developer_info_cb() {
		_e( 'This part is for developers and people who may be interested in plugin manipulation without touching source code. This is highly recommended way to do that kind of things.', 'analytics-head' );
	}

	/**
	 * View google_id option callback
	 *
	 * @return void
	 */
	public function google_id_cb() {
		printf( '<input type="text" id="google_id" name="' . OPTION_NAME . '[google_id]" value="%s" />', isset( $this->o->data['google_id'] ) ? esc_attr( $this->o->data['google_id'] ) : '' );
		echo '<p class="description">' . __( 'Something like UA-XXXXXXXX-X, where X-es are digits. It is your personal Google Analytics code provided by Google.', 'analytics-head' ) . '</p>';
	}

	/**
	 * View hide-in_admin_panel option callback
	 *
	 * @return void
	 */
	public function hide_in_admin_panel_cb() {
		printf( '<input type="checkbox" id="hide-in_admin_panel" name="' . OPTION_NAME . '[hide-in_admin_panel]" value="1"%s><label for="hide-in_admin_panel">' . __( 'Hide Google tracking code in the admin area', 'analytics-head' ) . '</label>', ( isset( $this->o->data['hide-in_admin_panel'] ) && ( $this->o->data['hide-in_admin_panel'] == '1' ) ) ? ' checked' : '' );
	}

	/**
	 * View hide-for_logged_users option callback
	 *
	 * @return void
	 */
	public function hide_for_logged_users_cb() {
		printf( '<input type="checkbox" id="hide-for_logged_users" name="' . OPTION_NAME . '[hide-for_logged_users]" value="1"%s><label for="hide-for_logged_users">' . __( 'Hide Google tracking code for all logged-in users, anywhere', 'analytics-head' ) . '</label>', ( isset( $this->o->data['hide-for_logged_users'] ) && ( $this->o->data['hide-for_logged_users'] == '1' ) ) ? ' checked' : '' );
	}

	/**
	 * View move-to_footer option callback
	 *
	 * @return void
	 */
	public function move_to_footer_cb() {
		printf( '<input type="checkbox" id="move-to_footer" name="' . OPTION_NAME . '[move-to_footer]" value="1"%s><label for="move-to_footer">' . __( 'Move Google tracking code to the end of the page instead of the &lt;head&gt; section.', 'analytics-head' ) . '</label>', ( isset( $this->o->data['move-to_footer'] ) && ( $this->o->data['move-to_footer'] == '1' ) ) ? ' checked' : '' );
	}

	/**
	 * View use_hooks callback
	 *
	 * @return void
	 */
	public function use_hooks_cb() {
		printf( '<input type="checkbox" id="use_hooks" name="' . OPTION_NAME . '[use_hooks]" value="1"%s><label for="use_hooks">' . __( 'After enabling this option, plugin will be firing two actions (<strong>pp_google_analytics_head_before</strong>, <strong>pp_google_analytics_head_after</strong>) and will use one filter (<strong>pp_google_analytics_head_output</strong>) for output.', 'analytics-head' ) . '</label>', ( isset( $this->o->data['use_hooks'] ) && ( $this->o->data['use_hooks'] == '1' ) ) ? ' checked' : '' );
	}

	/**
	 * View use_hooks callback
	 *
	 * @return void
	 */
	public function add_plugin_info_cb() {
		printf( '<input type="checkbox" id="add-plugin_info" name="' . OPTION_NAME . '[add-plugin_info]" value="1"%s><label for="add-plugin_info">' . __( 'Add plugin name to the tracking code', 'analytics-head' ) . '</label>', ( isset( $this->o->data['add-plugin_info'] ) && ( $this->o->data['add-plugin_info'] == '1' ) ) ? ' checked' : '' );
	}

}