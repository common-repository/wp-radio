<?php

defined( 'ABSPATH' ) || exit;


class WP_Radio_Block {

	private static $instance = null;

	public function __construct() {
		add_filter( 'block_categories_all', [ $this, 'filter_block_categories' ], 10, 2 );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ] );
	}

	public function filter_block_categories( $block_categories, $editor_context ) {

		if ( ! empty( $editor_context->post ) ) {
			$new_categories = [
				[
					'slug'  => 'wp-radio-category',
					'title' => __( 'WP Radio', 'wp-radio' ),
					'icon'  => null,
				]
			];

			$block_categories = array_merge( $block_categories, $new_categories );
		}

		return $block_categories;
	}

	public function enqueue_block_editor_assets( $hook ) {


		// Frontend Scripts
		wp_register_style( 'wp-radio-frontend', WP_RADIO_ASSETS . '/css/frontend.css', [ 'dashicons' ], WP_RADIO_VERSION );

		wp_register_script( 'wp-radio-frontend', WP_RADIO_ASSETS . '/js/frontend.js', [
			'jquery',
			'jquery-migrate',
			'wp-mediaelement',
			'wp-hooks',
			'wp-util',
		], WP_RADIO_VERSION, true );


		// Editor Scripts
		wp_enqueue_style( 'wp-radio-block',
			WP_RADIO_URL . '/block/build/editor.css', [ 'wp-radio-frontend' ], WP_RADIO_VERSION );


		wp_enqueue_script( 'wp-radio-block',
			WP_RADIO_URL . '/block/build/index.js',
			[
				'wp-blocks',
				'wp-i18n',
				'wp-element',
				'wp-radio-admin',
				'wp-radio-frontend',
			],
			WP_RADIO_VERSION, true );

		// Adds internalization support
		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'wp-radio-block', 'wp-radio' );
		}
	}

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

}

WP_Radio_Block::instance();
