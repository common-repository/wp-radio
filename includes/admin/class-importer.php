<?php

defined( 'ABSPATH' ) || exit();


use League\Csv\Reader;
use League\Csv\Statement;

include WP_RADIO_PATH . '/vendor/autoload.php';


class WP_Radio_Importer {

	private static $instance = null;

	private $countries = [];

	/**
	 * Importer constructor.
	 *
	 * @param array $countries
	 * @param string $file_path
	 */

	function __construct( $countries ) {
		$this->countries = $countries;
	}

	public function handle_import() {

		@ini_set( 'max_execution_time', - 1 );
		@ini_set( 'memory_limit', - 1 );

		$response = [];

		$file_path = WP_RADIO_INCLUDES . "/admin/data/data.csv";

		$countries = array_diff( (array) $this->countries, (array) get_option( 'wp_radio_imported_countries' ) );

		//offset
		$last_countries = get_option( 'wp_radio_last_selected_countries' );
		$offset         = $countries == $last_countries ? get_option( 'wp_radio_import_offset' ) : 0;

		update_option( 'wp_radio_last_selected_countries', $countries );

		//stations per page to be inserted
		$length = 10;

		//get stations from offset to offset + length
		$csv = Reader::createFromPath( $file_path, 'r' );
		$csv->setHeaderOffset( 0 ); //set the CSV header offset

		$stmt = Statement::create()->offset( $offset )->limit( $length )->where( [ $this, 'where' ] );

		$stations = $stmt->process( $csv );

		if ( $stations->count() ) {

			foreach ( $stations as $station ) {
				$this->import_station( $station );
			}

			$count = count( $stations );

			if ( $count < $length ) {
				$length = $count;
			}

			$offset = $offset + $length;
			update_option( 'wp_radio_import_offset', $offset );

		} else {
			$response['done'] = true;
			update_option( 'wp_radio_imported_countries',
				array_merge( $countries, (array) get_option( 'wp_radio_imported_countries' ) ) );
			update_option( 'wp_radio_import_offset', 0 );
		}

		$response['imported'] = $offset;

		return $response;

	}

	/**
	 * Import Station
	 *
	 * @param $station
	 */
	function import_station( $station ) {

		$defaults = array(
			'id'           => '',
			'name'         => '',
			'slogan'       => '',
			'src'          => '',
			'description'  => '',
			'logo'         => '',
			'language'     => '',
			'genres'       => '',
			'frequency'    => '',
			'bitrate'      => '',
			'website'      => '',
			'facebook'     => '',
			'twitter'      => '',
			'wikipedia'    => '',
			'address'      => '',
			'phone'        => '',
			'email'        => '',
			'country'      => '',
			'country_code' => '',
			'region'       => '',
			'city'         => '',
		);


		$station = array_merge( $defaults, $station );

		$title = sanitize_text_field( $station['name'] );

		if ( $this->station_exists( $title, $station['country_code'] ) ) {
			return;
		}

		$content = sanitize_textarea_field( $station['description'] );

		$tax_input = [
			'radio_genre' => $station['genres'],
		];

		$meta_input = array(
			'slogan'     => sanitize_text_field( $station['slogan'] ),
			'stream_url' => esc_url( $station['src'] ),
			'logo'       => esc_url( $station['logo'] ),
			'frequency'  => sanitize_text_field( $station['frequency'] ),
			'bitrate'    => sanitize_text_field( $station['bitrate'] ),
			'language'   => sanitize_text_field( $station['language'] ),

			'website'   => esc_url( $station['website'] ),
			'facebook'  => esc_url( $station['facebook'] ),
			'twitter'   => esc_url( $station['twitter'] ),
			'wikipedia' => esc_url( $station['wikipedia'] ),

			'address' => sanitize_textarea_field( $station['address'] ),
			'phone'   => sanitize_text_field( $station['phone'] ),
			'email'   => sanitize_email( $station['email'] ),

			'genres'       => sanitize_text_field( $station['genres'] ),
			'country'      => sanitize_text_field( $station['country'] ),
			'country_code' => sanitize_text_field( $station['country_code'] ),
			'region'       => sanitize_text_field( $station['region'] ),
			'city'         => sanitize_text_field( $station['city'] ),

		);


		//insert post
		$post_id = wp_insert_post( array(
			'post_type'    => 'wp_radio',
			'post_title'   => $title,
			'post_content' => $content,
			'post_status'  => 'publish',
			'tax_input'    => $tax_input,
		) );

		if ( is_wp_error( $post_id ) ) {
			return;
		}

		//update meta
		foreach ( $meta_input as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}

		//update country terms
		$country_taxonomy = 'radio_country';

		$country_code = trim( $station['country_code'] );
		$country      = trim( $station['country'] );
		$region       = trim( $station['region'] );
		$city         = trim( $station['city'] );

		$country_id = 0;
		$region_id  = null;
		$city_id    = 0;


		//country
		if ( $country_exists = get_term_by( 'slug', $country_code, $country_taxonomy, ARRAY_A ) ) {
			$country_id = $country_exists['term_id'];
		} else {
			$country_inserted = wp_insert_term( $country, $country_taxonomy, [ 'slug' => $country_code ] );

			if ( ! is_wp_error( $country_inserted ) ) {
				$country_id = $country_inserted['term_id'];
			}
		}


		if ( ! empty( $station['country_code'] ) && 'ww' != $station['country_code'] ) {
			//region
			if ( ! empty( $region ) ) {
				if ( $region_exists = term_exists( $region, $country_taxonomy, $country_id ) ) {
					$region_id = $region_exists['term_id'];
				} else {
					$region_inserted = wp_insert_term( $region, $country_taxonomy, [ 'parent' => $country_id ] );

					if ( ! is_wp_error( $region_inserted ) ) {
						$region_id = $region_inserted['term_id'];
					}
				}
			}

			//city
			if ( ! empty( $city ) ) {
				if ( $city_exists = term_exists( $city, $country_taxonomy, ! empty( $region_id ) ? $region_id : $country_id ) ) {
					$city_id = $city_exists['term_id'];
				} else {
					$city_inserted = wp_insert_term( $city, $country_taxonomy,
						[ 'parent' => ! empty( $region_id ) ? $region_id : $country_id ] );

					if ( ! is_wp_error( $city_inserted ) ) {
						$city_id = $city_inserted['term_id'];
					}
				}
			}
		}

		wp_set_post_terms( $post_id, [ $country_id, $region_id, $city_id ], $country_taxonomy );

	}

	function where( $station ) {
		$countries = array_diff( (array) $this->countries, (array) get_option( 'wp_radio_imported_countries' ) );

		return in_array( $station['country_code'], $countries );
	}

	/**
	 * Check if stations exists or not
	 *
	 * @param $station_name
	 * @param $country_code
	 *
	 * @return bool
	 */
	public function station_exists( $station_name, $country_code ) {
		$station = get_page_by_title( $station_name, OBJECT, 'wp_radio' );

		if ( $station ) {
			$countries = wp_get_post_terms( $station->ID, 'radio_country' );

			if ( $countries and is_array( $countries ) ) {

				foreach ( $countries as $country ) {
					if ( $country->parent == 0 ) {
						$parent = $country;
					}
				}

				if ( isset( $parent ) && $country_code == $parent->slug ) {
					return true;
				}

			} else {
				return true;
			}
		}

		return false;
	}

	public static function instance( $countries = [] ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self( $countries );
		}

		return self::$instance;
	}

}