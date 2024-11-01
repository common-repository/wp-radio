<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_Radio_Rest_Api_Controller' ) ) {
	class WP_Radio_Rest_Api_Controller extends WP_REST_Controller {
		/** @var null */
		private static $instance = null;

		/**
		 * WP_Radio_Rest_Api_Controller constructor.
		 */
		public function __construct() {
			$this->namespace = 'wp-radio/v1';
		}

		public function register_routes() {

			register_rest_route( $this->namespace, '/station/', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_station' ),
					'permission_callback' => '__return_true',
				),
			) );

			register_rest_route( $this->namespace, '/station-data/(?P<id>\d+)', array(
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'get_station_data' ),
					'permission_callback' => '__return_true',
				),
			) );

			register_rest_route( $this->namespace, '/player-data/(?P<id>\d+)', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_player_data' ),
					'permission_callback' => '__return_true',
				),
			) );

			register_rest_route( $this->namespace, '/related/(?P<id>\d+)', array(
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'get_related' ),
					'permission_callback' => '__return_true',
				),
			) );

			register_rest_route( $this->namespace, '/playlist/(?P<id>\d+)', array(
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'get_playlist_items' ),
					'permission_callback' => '__return_true',
				),
			) );

			// register route [GET] to get the stream title
			register_rest_route( $this->namespace, '/get-stream-title/(?P<id>\d+)', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_stream_title' ),
					'permission_callback' => '__return_true',
				),
			) );

			// register route [GET] to get nex-prev station player
			register_rest_route( $this->namespace, '/next-prev/', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'handle_next_prev' ),
					'permission_callback' => '__return_true',
				),
			) );

			// register [POST] route to save statistics data
			register_rest_route( $this->namespace, '/statistics/', array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'save_statistics' ),
					'permission_callback' => '__return_true',
				),
			) );

			//register [GET] route to get keyword suggestions
			register_rest_route( $this->namespace, '/keyword/', array(
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'get_keyword_suggestions' ),
					'permission_callback' => '__return_true',
				),
			) );

			do_action( 'wp_radio/register_routes', $this->namespace );
		}

		public function save_statistics( $request ) {
			$data = json_decode( $request->get_body() );

			$user_ip           = ! empty( $data->ip ) ? sanitize_text_field( $data->ip ) : '';
			$user_country_code = ! empty( $data->country_code ) ? sanitize_key( $data->country_code ) : '';
			$station_id        = ! empty( $data->id ) ? intval( $data->id ) : '';

			if ( ! $station_id ) {
				wp_send_json_error( 'Empty station ID' );
			}

			$country    = get_the_terms( $station_id, 'radio_country' );
			$country_id = ! empty( $country[0] ) ? $country[0]->term_id : '';

			$date = date( 'Y-m-d' );

			$unique_id = md5( $date . $station_id . $user_ip );

			global $wpdb;
			$table = $wpdb->prefix . 'wp_radio_statistics';

			$sql = "INSERT INTO 
                        {$table} (`station_id`,`station_country_id`,`unique_id`, `user_ip`, `user_country_code`, `created_at`, `updated_at`) 
                    VALUES 
                        (%d, %s, %s, %s, %s, %s, %s)
                    ON DUPLICATE KEY UPDATE
                        `count` = `count` + 1,
                        `updated_at` = VALUES (updated_at)  
                ";

			$wpdb->query( $wpdb->prepare( $sql, [
				$station_id,
				$country_id,
				$unique_id,
				$user_ip,
				$user_country_code,
				$date,
				$date
			] ) );

			//listen count
			$sql          = $wpdb->prepare( "SELECT SUM(`count`) FROM {$table} WHERE station_id = %d LIMIT 1;", $station_id );
			$listen_count = $wpdb->get_var( $sql );
			update_post_meta( $station_id, 'listen_count', $listen_count );

			wp_send_json_success();
		}

		public function handle_next_prev( $request ) {
			$id   = intval( $request->get_param( 'id' ) );
			$type = sanitize_key( $request->get_param( 'type' ) );

			$stream_data = wp_radio_get_next_prev_stream_data( $id, $type );

			if ( $stream_data ) {
				wp_send_json_success( $stream_data );
			}

			wp_send_json_error( __( 'No Post.', 'wp-radio' ) );
		}

		public function get_stream_title( $data ) {

			$station_id = isset( $data['id'] ) ? $data['id'] : '';

			$url = wp_radio_get_meta( $station_id, 'stream_url' );

			$title = '';

			//get stream info
			if ( 'on' == wp_radio_get_settings( 'metadata_proxy', 'off', 'wp_radio_proxy_settings' ) ) {
				$url      = 'http://199.192.19.73/stream-title.php?url=' . $url;
				$response = wp_remote_get( $url );

				if ( ! is_wp_error( $response ) ) {
					$title = wp_remote_retrieve_body( $response );
				}

			} else {
				if ( preg_match( '/(?<server>^https?:\/\/.*:\d+\/)/', $url, $match ) ) {
					$server   = $match['server'] . 'stats?sid=1&json=1';
					$response = wp_remote_get( $server );

					if ( ! is_wp_error( $response ) ) {

						$json = wp_remote_retrieve_body( $response );
						$meta = json_decode( $json );

						if ( ! empty( $meta ) ) {
							if ( ! empty( $meta->songtitle ) ) {
								$title = $meta->songtitle;
							}
						}
					}
				} else {
					$stream_title = wp_radio_get_stream_title( $url );

					if ( ! is_wp_error( $stream_title ) && ! empty( $stream_title ) ) {
						$title = $stream_title;
					}
				}
			}

			wp_send_json_success( $title );
		}

		public function get_station( $request ) {

			$paginate = intval( $request['paginate'] );
			$keyword  = sanitize_text_field( $request['keyword'] );
			$country  = sanitize_text_field( $request['country'] );
			$genre    = intval( $request['genre'] );
			$sort     = sanitize_text_field( $request['sort'] );
			$perpage  = intval( $request['perpage'] );

			$args = [
				'paged' => $paginate,
			];

			/** per page */
			if ( ! empty( $perpage ) ) {
				$args['posts_per_page'] = $perpage;
			}

			/** listing sort */
			if ( ! empty( $sort ) ) {
				$sortby = 'title';
				if ( 'date_asc' == $sort ) {
					$sortby = 'date';
					$sort   = 'asc';
				} elseif ( 'date_desc' == $sort ) {
					$sortby = 'date';
					$sort   = 'desc';
				}

				$args['order']   = $sort;
				$args['orderby'] = $sortby;
			}

			/** search query */
			if ( ! empty( $keyword ) ) {
				$args['s'] = $keyword;
			}


			/** country tax query */
			$countries = [];

			if ( ! empty( $country ) ) {
				$countries[] = $country;
			}

			if ( ! empty( $countries ) ) {
				$args['tax_query'][] = array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'radio_country',
						'field'    => 'slug',
						'terms'    => $countries,
					)
				);
			}

			/** genre tax query */
			$genres = [];
			if ( ! empty( $genre ) ) {
				$genres[] = $genre;
			}

			if ( ! empty( $genres ) ) {
				$args['tax_query'][] = array(
					'relation' => 'AND',
					'taxonomy' => 'radio_genre',
					'field'    => 'term_id',
					'terms'    => $genres,
				);
			}

			/** post query */
			$query = wp_radio_get_stations( $args, true );

			$return = [
				'total'     => $query->found_posts,
				'pageCount' => $query->max_num_pages,
			];

			$return['items'] = wp_radio_get_listing_items( $query );


			if ( ! empty( $country ) && 'all' !== $country ) {
				$return['country'] = $country;
				$return['regions'] = wp_radio_get_regions( $country );
			}

			wp_send_json_success( $return );
		}

		/**
		 * Get the rest api station preview
		 *
		 * @param $data
		 */
		public function get_station_data( $data ) {
			$id = isset( $data['id'] ) ? $data['id'] : '';

			wp_send_json_success( [ wp_radio_get_station_data( $id ) ] );
		}

		public function get_player_data( $data ) {
			$id = isset( $data['id'] ) ? $data['id'] : '';

			wp_send_json_success( wp_radio_get_stream_data( $id ) );
		}

		public function get_related( $data ) {
			$id = isset( $data['id'] ) ? $data['id'] : '';

			wp_send_json_success( wp_radio_get_related_stations( $id ) );
		}

		public function get_playlist_items( $data ) {
			$id = isset( $data['id'] ) ? $data['id'] : '';

			wp_send_json_success( wp_radio_get_playlist_items( $id ) );
		}

		public function get_keyword_suggestions( $request ) {
			$keyword = sanitize_text_field( $request->get_param( 'keyword' ) );
			$country = sanitize_key( $request->get_param( 'country' ) );
			$genre   = intval( $request->get_param( 'genre' ) );

			$args = [ 's' => $keyword ];

			/** country tax query */
			$countries = [];

			if ( ! empty( $country ) ) {
				$countries[] = $country;
			}

			if ( ! empty( $countries ) ) {
				$args['tax_query'][] = array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'radio_country',
						'field'    => 'slug',
						'terms'    => $countries,
					)
				);
			}

			/** genre tax query */
			$genres = [];
			if ( ! empty( $genre ) ) {
				$genres[] = $genre;
			}

			if ( ! empty( $genres ) ) {
				$args['tax_query'][] = array(
					'relation' => 'AND',
					'taxonomy' => 'radio_genre',
					'field'    => 'term_id',
					'terms'    => $genres,
				);
			}


			$data = [];

			$posts = wp_radio_get_stations( $args );
			if ( ! empty( $posts ) ) {
				$data = wp_list_pluck( $posts, 'post_title' );
			}

			wp_send_json_success( $data );
		}

		/**
		 * @return WP_Radio_Rest_Api_Controller|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
}
