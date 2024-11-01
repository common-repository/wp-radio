<?php

defined( 'ABSPATH' ) || exit();

/**
 * Class Ajax
 *
 * Handle all Ajax requests
 *
 * @since 1.0.0
 *
 * @package Prince\WP_Radio
 */
class WP_Radio_Ajax {
	private static $instance = null;

	public function __construct() {

		//station search
		add_action( 'wp_ajax_wp_radio_search_station', array( $this, 'search_station' ) );
		add_action( 'wp_ajax_nopriv_wp_radio_search_station', array( $this, 'search_station' ) );

		add_action( 'wp_ajax_wp_radio_get_country_list_html', array( $this, 'get_country_list_html' ) );
		add_action( 'wp_ajax_nopriv_wp_radio_get_country_list_html', array( $this, 'get_country_list_html' ) );

		// get search form html
		add_action( 'wp_ajax_wp_radio_get_search_form_html', array( $this, 'get_search_form_html' ) );
		add_action( 'wp_ajax_nopriv_wp_radio_get_search_form_html', array( $this, 'get_search_form_html' ) );

		//station html
		add_action( 'wp_ajax_wp_radio_get_station_html', array( $this, 'get_station_html' ) );
		add_action( 'wp_ajax_nopriv_wp_radio_get_station_html', array( $this, 'get_station_html' ) );

		//player html
		add_action( 'wp_ajax_wp_radio_get_player_html', array( $this, 'get_player_html' ) );
		add_action( 'wp_ajax_nopriv_wp_radio_get_player_html', array( $this, 'get_player_html' ) );

		//get stream title
		add_action( 'wp_ajax_wp_radio_get_stream_title', array( $this, 'get_stream_title' ) );
		add_action( 'wp_ajax_nopriv_wp_radio_get_stream_title', array( $this, 'get_stream_title' ) );

		//get next-prev station
		add_action( 'wp_ajax_wp_radio_get_next_prev', array( $this, 'get_next_prev' ) );
		add_action( 'wp_ajax_nopriv_wp_radio_get_next_prev', array( $this, 'get_next_prev' ) );
		//get playlist html
		add_action( 'wp_ajax_wp_radio_get_playlist_html', array( $this, 'get_playlist_html' ) );
		add_action( 'wp_ajax_nopriv_wp_radio_get_playlist_html', array( $this, 'get_playlist_html' ) );

	}

	public function get_playlist_html() {
		$id = intval( $_POST['id'] );

		ob_start();
		wp_radio_get_template( 'single/playlist', [ 'id' => $id ] );
		$html = ob_get_clean();

		wp_send_json_success( $html );
	}

	public function get_next_prev() {
		$id   = intval( $_POST['current_id'] );
		$type = sanitize_key( $_POST['type'] );

		$stream_data = wp_radio_get_next_prev_stream_data( $id, $type );

		if ( $stream_data ) {
			wp_send_json_success( $stream_data );
		}

		wp_send_json_error( __( 'No station found.', 'wp-radio' ) );
	}


	public function get_stream_title() {
		$ids = $_POST['ids'];

		$data = [];
		if ( ! empty( $ids ) ) {
			foreach ( $ids as $id ) {
				$data[ $id ] = wp_radio_get_stream_title( $id );
			}
		}

		wp_send_json_success( $data );
	}

	public function get_player_html() {
		$station_id = intval( $_POST['id'] );


		$align = sanitize_key( $_POST['align'] );
		$next_prev = sanitize_key( $_POST['next_prev'] );

		ob_start();
		echo do_shortcode( '[wp_radio_player id="' . $station_id . '" next_prev="' . $next_prev . '" align="' . $align . '"]' );
		$html = ob_get_clean();

		wp_send_json_success( $html );
	}

	public function get_station_html() {
		$station_id = intval( $_POST['id'] );


		$show_genres      = sanitize_key( $_POST['show_genres'] );
		$show_description = sanitize_key( $_POST['show_description'] );

		ob_start();
		echo do_shortcode( '[wp_radio_station id="' . $station_id . '" show_genre="' . $show_genres . '" show_description="' . $show_description . '"]' );
		$html = ob_get_clean();

		wp_send_json_success( $html );
	}

	public function get_country_list_html() {
		$style   = ! empty( $_POST['style'] ) ? $_POST['style'] : 'list';
		$columns = ! empty( $_POST['columns'] ) ? $_POST['columns'] : '3';

		$html = do_shortcode( "[wp_radio_country_list style='{$style}' columns='{$columns}' ]" );

		wp_send_json_success( $html );
	}


	public function get_search_form_html() {
		$country_filter = ! empty( $_POST['showCountry'] ) ? $_POST['showCountry'] : 'false';
		$genre_filter   = ! empty( $_POST['showGenre'] ) ? $_POST['showGenre'] : 'false';

		$html = do_shortcode( "[wp_radio_search_form country_filter='{$country_filter}' genre_filter='{$genre_filter}' ]" );

		wp_send_json_success( $html );
	}

	/**
	 * search station
	 */
	public function search_station() {

		$s        = ! empty( $_REQUEST['s'] ) ? $_REQUEST['s'] : '';
		$stations = wp_radio_get_stations( [ 's' => $s ] );

		$data = [];
		if ( ! empty( $stations ) ) {
			foreach ( $stations as $station ) {
				$data[] = wp_radio_get_stream_data( $station->ID );
			}
		}

		wp_send_json_success( $data );
	}


	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}

WP_Radio_Ajax::instance();