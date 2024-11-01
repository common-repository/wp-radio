<?php

defined( 'ABSPATH' ) || exit;


/**
 * Register all the block assets so that they can be enqueued through the block editor
 * in the corresponding context
 */
add_action( 'init', 'wp_radio_register_block' );
function wp_radio_register_block() {

	// If block editor is not active, bail.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	/**
	 * Frontend Scripts
	 */
	wp_register_style( 'wp-radio-editor', WP_RADIO_ASSETS . '/css/frontend.css', [ 'dashicons' ], WP_RADIO_VERSION );

	wp_register_script( 'wp-radio-editor', WP_RADIO_ASSETS . '/js/frontend.min.js', [
		'wp-element',
		'wp-components',
		'wp-api-fetch',
		'wp-block-editor',
		'jquery',
		'jquery-migrate',
		'wp-util',
	], WP_RADIO_VERSION, true );

	// Register the block editor scripts
	wp_register_script( 'wp-radio-editor-script',
		plugins_url( 'build/index.js', __FILE__ ),
		[ 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-radio-editor' ],
		filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js' ) );

	// Register the block editor styles
	wp_register_style( 'wp-radio-editor-style',
		plugins_url( 'build/editor.css', __FILE__ ),
		[ 'wp-radio-editor' ],
		filemtime( plugin_dir_path( __FILE__ ) . 'build/editor.css' ) );


	register_block_type( 'wp-radio/radio-station',
		[
			'editor_script' => 'wp-radio-editor-script',
			'editor_style'  => 'wp-radio-editor-style',
		] );


	register_block_type( 'wp-radio/radio-player',
		[
			'editor_script' => 'wp-radio-editor-script',
			'editor_style'  => 'wp-radio-editor-style',
		] );


	if ( function_exists( 'wp_set_script_translations' ) ) {
		/**
		 * Adds internalization support
		 */
		wp_set_script_translations( 'wp-radio-editor-script', 'wp-radio' );
	}
}