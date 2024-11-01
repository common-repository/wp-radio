<?php

defined( 'ABSPATH' ) || exit();

class WP_Radio_Settings {

	private static $instance = null;

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
	}

	public function admin_menu() {

		add_submenu_page( 'edit.php?post_type=wp_radio', __( 'WP Radio Settings', 'wp-radio' ), __( 'Settings', 'wp-radio' ), 'manage_options', 'wp-radio-settings', [
			$this,
			'render_settings_page'
		] );

	}

	public function render_settings_page() {

		$pages = wp_list_pluck( get_pages(), 'post_title', 'ID' );

		$settings_data = [
			'pages'           => $pages,
			'adminEmail'      => get_option( 'admin_email' ),
			'addonsUrl'       => admin_url( 'edit.php?post_type=wp_radio&page=wp-radio-addons' ),
			'canUserFrontend' => function_exists( 'wr_user_frontend_fs' ) && wr_user_frontend_fs()->can_use_premium_code__premium_only(),
			'canProxyPlayer'  => function_exists( 'wrp_fs' ) && wrp_fs()->can_use_premium_code__premium_only(),
			'canAdsPlayer'    => function_exists( 'wrap_fs' ) && wrap_fs()->can_use_premium_code__premium_only(),
		];

		if ( defined( 'WR_ADS_PLAYER_VERSION' ) ) {
			$settings_data['siteTitle'] = get_bloginfo( 'name' );
		}

		?>
        <script>
            var wpRadioSettingsData = <?php echo json_encode( $settings_data ); ?>;
        </script>

        <div class="wp-radio-settings-container" id="wp-radio-settings"></div>
		<?php
	}

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

}

WP_Radio_Settings::instance();