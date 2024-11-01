<?php

/** Block direct access */
defined( 'ABSPATH' ) || exit();


class WP_Radio_Metabox {

	/**
	 * @var null
	 */
	private static $instance = null;

	/**
	 * WP_Radio_Metabox constructor.
	 * Initialize the custom Meta Boxes for prince-options api.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
		add_action( 'do_meta_boxes', [ $this, 'remove_meta_box' ] );

		add_action( 'save_post_wp_radio', [ $this, 'save_meta' ] );
	}

	public function save_meta( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		//return if quick edit
		if ( ! empty( $_POST['action'] ) && $_POST['action'] == 'inline-save' ) {
			return;
		}

		$values = [
			'logo'       => ! empty( $_POST['logo'] ) ? esc_url( $_POST['logo'] ) : '',
			'stream_url' => ! empty( $_POST['stream_url'] ) ? esc_url( $_POST['stream_url'] ) : '',
			'slogan'     => ! empty( $_POST['slogan'] ) ? sanitize_text_field( $_POST['slogan'] ) : '',
			'featured'   => ! empty( $_POST['featured'] ) ? sanitize_text_field( $_POST['featured'] ) : '',
			'popup'      => ! empty( $_POST['popup'] ) ? sanitize_text_field( $_POST['popup'] ) : '',

			'website'   => ! empty( $_POST['website'] ) ? esc_url( $_POST['website'] ) : '',
			'facebook'  => ! empty( $_POST['facebook'] ) ? esc_url( $_POST['facebook'] ) : '',
			'twitter'   => ! empty( $_POST['twitter'] ) ? esc_url( $_POST['twitter'] ) : '',
			'wikipedia' => ! empty( $_POST['wikipedia'] ) ? esc_url( $_POST['wikipedia'] ) : '',

			'address' => ! empty( $_POST['address'] ) ? sanitize_textarea_field( $_POST['address'] ) : '',
			'email'   => ! empty( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '',
			'phone'   => ! empty( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '',
		];

		foreach ( $values as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}

		$country = ! empty( $_POST['country'] ) ? intval( $_POST['country'] ) : '';
		$state   = ! empty( $_POST['state'] ) ? intval( $_POST['state'] ) : '';
		$city    = ! empty( $_POST['city'] ) ? intval( $_POST['city'] ) : '';

		wp_set_post_terms( $post_id, [ $country, $state, $city ], 'radio_country' );

	}

	public function remove_meta_box() {
		remove_meta_box( 'postimagediv', 'wp_radio', 'side' );
		remove_meta_box( 'radio_countrydiv', 'wp_radio', 'side' );
	}

	/**
	 * register metaboxes
	 */
	public function register_meta_boxes() {

		// Station Information Metabox
		add_meta_box( 'wp_radio_metabox',
			__( 'Station Information', 'wp-radio' ),
			[ $this, 'render_metabox' ],
			[ 'wp_radio' ],
			'normal',
			'high' );

		add_meta_box( 'wp_radio_location', __( 'Station Location', 'wp-radio' ), [
			$this,
			'render_location_metabox'
		], [ 'wp_radio' ], 'side' );
	}

	/**
	 * render station info metabox content
	 *
	 * @since 1.0.0
	 */
	public function render_metabox() {
		include WP_RADIO_INCLUDES . '/admin/views/metabox.php';
	}

	public function render_location_metabox() {
		include WP_RADIO_INCLUDES . '/admin/views/metabox-location.php';
	}

	/**
	 * @return WP_Radio_Metabox|null
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}

WP_Radio_Metabox::instance();
