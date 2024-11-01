<?php

defined( 'ABSPATH' ) || exit();

class WP_Radio_Update_3_1_4 {

	private static $instance = null;

	public function __construct() {
		$this->update_settings();
	}

	public function update_settings() {
		$options = [
			'wp_radio_general_settings',
			'wp_radio_player_settings',
			'wp_radio_display_settings',
			'wp_radio_style_settings',
			'wp_radio_image_settings',
			'wp_radio_statistics_settings',
			'wp_radio_proxy_settings',
			'wp_radio_user_frontend_settings',
			'wp_radio_ads_settings',
		];

		$new_settings = [];
		foreach ( $options as $option ) {

			$pre_settings = get_option( $option );

			if ( ! empty( $pre_settings ) ) {
				foreach ( $pre_settings as $key => $value ) {
					if ( $value == 'on' ) {
						$value = true;
					} elseif ( $value == 'off' ) {
						$value = false;
					}

					$new_settings[ $key ] = $value;
				}
			}
		}

		update_option( 'wp_radio_settings', $new_settings );
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


}

WP_Radio_Update_3_1_4::instance();