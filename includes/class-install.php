<?php
/**
 * Installation related functions and actions.
 *
 * @since 2.0.2
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class Install
 */
class WP_Radio_Install {

	private static $instance = null;

	public function __construct() {

		if ( ! class_exists( 'WP_Radio_Update' ) ) {
			require_once WP_RADIO_INCLUDES . '/admin/class-update.php';

			$updater = new WP_Radio_Update();

			if ( $updater->needs_update() ) {
				$updater->perform_updates();
			} else {
				self::create_pages();
				self::create_default_data();
				self::create_tables();
			}
		}

	}

	/**
	 * Create pages
	 *
	 * @since 2.1.0
	 */
	private static function create_pages() {
		if ( get_page_by_title( 'Live Radio' ) ) {
			return;
		}

		$id = wp_insert_post( array(
			'post_type'    => 'page',
			'post_title'   => esc_html__( 'Live Radio', 'wp-radio' ),
			'post_content' => '[wp_radio_listing]',
			'post_status'  => 'publish',
		) );

		$settings                  = get_option( 'wp_radio_settings' );
		$settings['stations_page'] = $id;

		update_option( 'wp_radio_settings', $settings );

	}

	/**
	 * create default data
	 *
	 * @since 2.0.8
	 */
	private static function create_default_data() {

		$version      = get_option( 'wp_radio_version' );
		$install_time = get_option( 'wp_radio_install_time', '' );

		if ( empty( $version ) ) {
			update_option( 'wp_radio_version', wp_radio()->version );
		}

		if ( ! empty( $install_time ) ) {
			$date_format = get_option( 'date_format' );
			$time_format = get_option( 'time_format' );
			update_option( 'wp_radio_install_time', date( $date_format . ' ' . $time_format ) );
		}

		update_option( 'wp_radio_flush_rewrite_rules', true );

		set_transient( 'wp_radio_rating_notice_interval', 'off', 3 * DAY_IN_SECONDS );
		set_transient( 'wp_radio_ads_player_notice_interval', 'off', 5 * DAY_IN_SECONDS );
		set_transient( 'wp_radio_proxy_notice_interval', 'off', 3 * HOUR_IN_SECONDS );
	}

	private static function create_tables() {
		global $wpdb;
		$wpdb->hide_errors();
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}

		$tables = [
			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wp_radio_visitors(
         	id BIGINT  NOT NULL AUTO_INCREMENT,
			ip varchar(128)  NOT NULL DEFAULT '',
			country_code varchar(10) NOT NULL DEFAULT '',
			created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			UNIQUE KEY `ip` (`ip`)
            ) {$collate};",

			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wp_radio_statistics(
         	id BIGINT  NOT NULL AUTO_INCREMENT,
			station_id BIGINT  NOT NULL,
			station_country_id BIGINT  NOT NULL DEFAULT 0,
         	unique_id varchar (32) NOT NULL DEFAULT '',
			`count` BIGINT  NOT NULL DEFAULT '1',
			user_ip varchar(128)  NOT NULL DEFAULT '',
			user_country_code varchar(128) NULL,
			created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at TIMESTAMP NOT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY unique_id (unique_id)
            ) {$collate};
            "
		];

		foreach ( $tables as $table ) {
			dbDelta( $table );
		}

	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


}

WP_Radio_Install::instance();