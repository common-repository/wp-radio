<?php

defined( 'ABSPATH' ) || exit();

class WP_Radio_Update_3_0_4 {

	private static $instance = null;

	public function __construct() {
		$this->create_tables();
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function create_tables() {
		global $wpdb;
		$wpdb->hide_errors();
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$tables = [

			//ip table
			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wp_radio_visitors(
         	id bigint(20) NOT NULL AUTO_INCREMENT,
			ip varchar(128)  NOT NULL DEFAULT '',
			country_code varchar(128) NOT NULL DEFAULT '',
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			UNIQUE KEY `ip` (`ip`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",

		];

		foreach ( $tables as $table ) {
			dbDelta( $table );
		}

	}


}

WP_Radio_Update_3_0_4::instance();