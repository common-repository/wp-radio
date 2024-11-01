<?php

defined( 'ABSPATH' ) || exit;

class WP_Radio_Admin_Notice {

	private static $instance = null;

	public function __construct() {
		add_action( 'admin_notices', [ $this, 'display_notices' ] );
	}

	public function display_notices() {

		// Ads player update notice.
		$min_ads_player_version = '1.0.5';
		if ( defined( 'WR_ADS_PLAYER_VERSION' ) && WR_ADS_PLAYER_VERSION < $min_ads_player_version ) {
			ob_start();
			include WP_RADIO_INCLUDES . '/admin/views/notice/ads-player-update.php';
			$notice_html = ob_get_clean();
			wp_radio()->add_notice( 'error  ads-player-update-notice', $notice_html );

		}

		// User frontend update notice.
		$min_user_frontend_version = '1.2.0';
		if ( defined( 'WR_USER_FRONTEND_VERSION' ) && WR_USER_FRONTEND_VERSION < $min_user_frontend_version ) {

			ob_start();
			include WP_RADIO_INCLUDES . '/admin/views/notice/user-frontend-update.php';
			$notice_html = ob_get_clean();
			wp_radio()->add_notice( 'error user-frontend-update-notice', $notice_html );
		}

		// proxy player update notice
		$min_proxy_player_version = '1.1.1';
		if ( defined( 'WP_RADIO_PROXY_VERSION' ) && WP_RADIO_PROXY_VERSION < $min_proxy_player_version ) {

			ob_start();
			include WP_RADIO_INCLUDES . '/admin/views/notice/proxy-player-update.php';
			$notice_html = ob_get_clean();
			wp_radio()->add_notice( 'error proxy-player-update-notice', $notice_html );
		}

		//proxy player pro notice
		if ( ! defined( 'WP_RADIO_PROXY_VERSION' )
		     && 'off' != get_option( 'wp_radio_display_proxy_notice' )
		     && 'off' != get_transient( 'wp_radio_proxy_notice_interval' )
		) {

			ob_start();
			include WP_RADIO_INCLUDES . '/admin/views/notice/proxy-player.php';
			$notice_html = ob_get_clean();
			wp_radio()->add_notice( 'info  proxy-player-notice', $notice_html );

			return;
		}

		//ads player pro notice
		if ( ! class_exists( 'WP_Radio_Ads_player' )
		     && 'off' != get_option( 'wp_radio_display_ads_player_notice' )
		     && 'off' != get_transient( 'wp_radio_ads_player_notice_interval' )
		) {

			ob_start();
			include WP_RADIO_INCLUDES . '/admin/views/notice/ads-player.php';
			$notice_html = ob_get_clean();
			wp_radio()->add_notice( 'info  ads-player-notice', $notice_html );

			return;
		}

		//Rating notice if proxy notice is off
		if ( 'off' == get_option( 'wp_radio_display_proxy_notice' )
		     && 'off' != get_option( 'wp_radio_rating_notice' )
		     && 'off' != get_transient( 'wp_radio_rating_notice_interval' )
		) {

			ob_start();
			include WP_RADIO_INCLUDES . '/admin/views/notice/rating.php';
			$notice_html = ob_get_clean();
			wp_radio()->add_notice( 'info  wp-radio-rating-notice', $notice_html );

			return;
		}

	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}

WP_Radio_Admin_Notice::instance();