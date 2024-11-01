<?php

defined( 'ABSPATH' ) || exit();

class WP_Radio_Enqueue {

	private static $instance = null;

	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue' ] );
	}

	/**
	 * Frontend Scripts
	 *
	 * @param $hook
	 */
	public static function frontend_scripts( $hook ) {

		/* enqueue frontend styles */
		wp_enqueue_style( 'select2', WP_RADIO_ASSETS . '/vendor/select2/select2.min.css', false, '4.0.11' );
		wp_enqueue_style( 'wp-radio', WP_RADIO_ASSETS . '/css/frontend.css', [ 'dashicons' ], WP_RADIO_VERSION );

		/* inline styles */

		$listing_selector = '.wp-radio-listing-wrap .wp-radio-listing';
		$listing_color    = wp_radio_get_settings( 'listing_color', '' );
		$listing_bg_color = wp_radio_get_settings( 'listing_bg_color', '' );

		$player_selector = '.wp-radio-player';
		$player_bg_color = wp_radio_get_settings( 'player_bg_color', '' );
		$player_color    = wp_radio_get_settings( 'player_color', '' );

		$listing_thumb_selector = '.wp-radio-listing .listing-thumbnail img.listing-thumb';
		$listing_thumb_size     = wp_radio_get_settings( 'listing_thumbnail_size', 'auto' );
		$listing_thumb_width    = wp_radio_get_settings( 'listing_thumbnail_width', '' );
		$listing_thumb_height   = wp_radio_get_settings( 'listing_thumbnail_height', '' );

		$player_thumb_selector = '.wp-radio-player.shortcode .station-thumbnail';
		$player_thumb_size     = wp_radio_get_settings( 'player_thumbnail_size', 'auto' );
		$player_thumb_width    = wp_radio_get_settings( 'player_thumbnail_width', '' );
		$player_thumb_height   = wp_radio_get_settings( 'player_thumbnail_height', '' );

		ob_start();

		//player colors
		if ( ! empty( $player_bg_color ) ) {
			printf( '%1$s {--wp-radio-player-bg-color: %2$s;}', $player_selector, $player_bg_color );
		}

		if ( ! empty( $player_color ) ) {
			printf( '%1$s {--wp-radio-player-text-color: %2$s;}', $player_selector, $player_color );
		}

		//listing colors
		if ( ! empty( $listing_bg_color ) ) {
			printf( '%1$s {--wp-radio-listing-bg-color: %2$s;}', $listing_selector, $listing_bg_color );
		}

		if ( ! empty( $listing_color ) ) {
			printf( '%1$s * {--wp-radio-listing-color: %2$s;}', $listing_selector, $listing_color );
		}

		if ( 'custom' == $listing_thumb_size ) {
			printf( '%1$s {width: %2$spx; height: %3$spx}', $listing_thumb_selector, $listing_thumb_width, $listing_thumb_height );
		}

		if ( 'custom' == $player_thumb_size ) {
			printf( '%1$s {width: %2$spx; height: %3$spx}', $player_thumb_selector, $player_thumb_width, $player_thumb_height );
		}

		$custom_css = ob_get_clean();

		wp_add_inline_style( 'wp-radio', $custom_css );


		/* enqueue frontend js */
		wp_enqueue_script( 'select2', WP_RADIO_ASSETS . '/vendor/select2/select2.min.js', [ 'jquery' ], '4.0.11', true );
		wp_enqueue_script( 'jquery.hideseek', WP_RADIO_ASSETS . '/vendor/jquery.hideseek.min.js', [ 'jquery' ], WP_RADIO_VERSION, true );

		wp_enqueue_script( 'wp-radio', WP_RADIO_ASSETS . '/js/frontend.js', [
			'jquery',
			'jquery-migrate',
			'wp-mediaelement',
			'wp-util',
			'wp-i18n',
			'wp-hooks',
		], WP_RADIO_VERSION, true );


		/* localized script attached to 'wp-radio' */
		wp_localize_script( 'wp-radio', 'wpRadio', wp_radio_localize_array() );

		/* execute scripts after actions */
		do_action( 'wp_radio_scripts_after' );
	}

	/**
	 * Admin Scripts
	 *
	 * @param $hook
	 */
	public function admin_enqueue( $hook ) {

		wp_enqueue_style( 'select2', WP_RADIO_ASSETS . '/vendor/select2/select2.min.css', false, '4.0.11' );
		wp_enqueue_style( 'wp-radio-admin', WP_RADIO_ASSETS . '/css/admin.css', [ 'wp-components' ], WP_RADIO_VERSION );

		wp_enqueue_script( 'select2', WP_RADIO_ASSETS . '/vendor/select2/select2.min.js', [ 'jquery' ], '4.0.11', true );
		wp_enqueue_script( 'jquery.hideseek', WP_RADIO_ASSETS . '/vendor/jquery.hideseek.min.js', [ 'jquery' ], WP_RADIO_VERSION, true );
		wp_enqueue_script( 'jquery.syotimer', WP_RADIO_ASSETS . '/vendor/jquery.syotimer.min.js', [ 'jquery' ], '', true );

		$dependencies = [
			'jquery',
			'wp-api-fetch',
			'wp-element',
			'wp-components',
			'wp-block-editor',
			'wp-i18n',
			'wp-hooks',
			'wp-util',
		];


		if ( in_array( $hook, [ 'wp_radio_page_wp-radio-statistics', 'index.php' ] ) ) {
			wp_enqueue_script( 'chart.js', WP_RADIO_ASSETS . '/vendor/Chart.bundle.min.js', [], '2.8.0', true );

			$dependencies[] = 'jquery-ui-datepicker';
		}

		wp_enqueue_script( 'wp-radio-admin', WP_RADIO_ASSETS . '/js/admin.js', $dependencies, WP_RADIO_VERSION, true );


		wp_localize_script( 'wp-radio-admin', 'wpRadio', wp_radio_localize_array() );
	}


	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

WP_Radio_Enqueue::instance();




