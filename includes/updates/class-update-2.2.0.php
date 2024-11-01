<?php

class WP_Radio_Update_2_2_0 {

	private static $instance = null;

	public function __construct() {
		$this->update_settings();
		$this->update_options();
	}

	public static function instance(){
		if(is_null(self::$instance)){
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function update_options(){
		update_option('wp_radio_2.2.0_install_time', date('Y-m-d H:i:s'));
	}

	public function update_settings(){
		$settings = get_option( 'wp_radio_options' );

		$keys = [
			'wp_radio_general_settings' => [
				'stations_page',
				'notification_email',
				'enable_report',
				'delete_data',
				'account_page',
				'submit_station_page',
			],

			'wp_radio_display_settings' => [
				'posts_per_page',
				'listing_view',
				'hide_listing_content',
				'single_next_prev',
				'you_may_like',
				'show_search',
			],

			'wp_radio_player_settings' => [
				'play_btn',
				'hide_player',
			],

			'wp_radio_style_settings' => [
				'listing_bg',
				'listing_hover_bg',
				'listing_play_color',
				'fixed_player_bg',
				'player_icon_color',
			],

			'wp_radio_permalink_settings' => [
				'permalink_structure',
			],
		];

		foreach ( $keys as $key => $fields ) {
			$options = [];
			foreach ( $fields as $field ) {
				$options[] = ! empty( $settings[ $field ] ) ? $settings[ $field ] : '';
			}

			update_option( $key, $options );
		}
	}

	public function update_meta(){

	}


}

WP_Radio_Update_2_2_0::instance();