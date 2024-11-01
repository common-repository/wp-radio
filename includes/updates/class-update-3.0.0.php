<?php

defined( 'ABSPATH' ) || exit();

class WP_Radio_Update_3_0_0 {

	private static $instance = null;

	public function __construct() {
		$this->update_options();
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function update_options() {
		$version = get_option( 'wp_radio_version' );

		if ( $version < '3.0.0' ) {
			update_option( 'wp_radio_needs_stations_update', true );
			update_option( 'wp_radio_update_countries', array_keys( wp_radio_country_station_map() ) );
		}

	}


}

WP_Radio_Update_3_0_0::instance();