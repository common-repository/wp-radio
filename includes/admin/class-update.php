<?php

defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'WP_Radio_Update' ) ) {
	class WP_Radio_Update {

		private static $instance = null;


		/**
		 * The upgrades
		 *
		 * @var array
		 */
		private static $upgrades = array( '2.2.0', '3.0.0', '3.0.4', '3.1.4' );

		public function installed_version() {

			return get_option( 'wp_radio_version', '0' );
		}

		/**
		 * Check if the plugin needs any update
		 *
		 * @return boolean
		 */
		public function needs_update() {

			// may be it's the first install
			if ( empty( $this->installed_version() ) ) {
				return false;
			}

			//if previous version is lower
			if ( version_compare( $this->installed_version(), wp_radio()->version, '<' ) ) {
				return true;
			}


			return false;
		}

		/**
		 * Perform all the necessary upgrade routines
		 *
		 * @return void
		 */
		public function perform_updates() {

			foreach ( self::$upgrades as $version ) {

				if ( version_compare( $this->installed_version(), $version, '<' ) ) {

					$file = WP_RADIO_INCLUDES.'/updates/class-update-'.$version.'.php';
					include $file;

					update_option( 'wp_radio_version', $version );
				}
			}

			delete_option( 'wp_radio_version' );
			update_option( 'wp_radio_version', wp_radio()->version );
		}

		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

	}
}