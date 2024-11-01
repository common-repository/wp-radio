<?php

/* Block direct access */
defined( 'ABSPATH' ) || exit();

/**
 * Class Post_Types
 *
 * Register Custom post types and taxonomies
 *
 * @package Prince\WP_Radio
 *
 * @since 1.0.0
 */

if ( ! class_exists( 'WP_Radio_CPT' ) ) {
	class WP_Radio_CPT {
		private static $instance = null;

		/**
		 * Post_Types constructor.
		 */
		function __construct() {
			add_action( 'init', array( $this, 'register_post_types' ) );
			add_action( 'init', array( $this, 'register_taxonomies' ) );
			add_action( 'init', array( $this, 'flush_rewrite_rules' ), 99 );
		}

		/**
		 * register custom post types
		 *
		 * @since 1.0.0
		 */
		public function register_post_types() {
			register_post_type( 'wp_radio', array(
				'labels'              => $this->get_posts_labels( __( 'Radio Stations', 'wp-radio' ), __( 'Station', 'wp-radio' ), __( 'Stations', 'wp-radio' ) ),
				'hierarchical'        => false, //Hierarchical causes memory issues - WP Loads all records
				'supports'            => apply_filters( 'wp_radio_post_supports', array(
					'title',
					'editor',
					'thumbnail',
					//'comments'
				) ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'menu_position'       => 5,
				'menu_icon'           => WP_RADIO_ASSETS . '/images/wp-radio-icon-w20.png',
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => array( 'slug' => apply_filters( 'wp_radio_radios_slug', 'station' ) ),
				'capability_type'     => 'post',
			) );
		}

		/**
		 * Register custom taxonomies
		 *
		 * @since 1.0.0
		 */
		public function register_taxonomies() {
			register_taxonomy( 'radio_country', array( 'wp_radio' ), array(
				'hierarchical'      => true,
				'labels'            => $this->get_taxonomy_labels( __( 'Countries', 'wp-radio' ), __( 'Country', 'wp-radio' ), __( 'Countries', 'wp-radio' ), 'singular' ),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => apply_filters( 'wp_radio_country_slug', [ 'slug' => 'country' ] ),
			) );

			register_taxonomy( 'radio_genre', array( 'wp_radio' ), array(
				'hierarchical'      => false,
				'labels'            => $this->get_taxonomy_labels( __( 'Genres', 'wp-radio' ), __( 'Genre', 'wp-radio' ), __( 'Genres', 'wp-radio' ) ),
				'show_ui'           => true,
				'show_admin_column' => true,
				'rewrite'           => apply_filters( 'wp_radio_genre_slug', [ 'slug' => 'genre' ] ),
				'query_var'         => true,
			) );

		}

		/**
		 * Get all labels from post types
		 *
		 * @param $menu_name
		 * @param $singular
		 * @param $plural
		 *
		 * @return array
		 * @since 1.0.0
		 */
		protected static function get_posts_labels( $menu_name, $singular, $plural, $type = 'plural' ) {
			$labels = array(
				'name'               => 'plural' == $type ? $plural : $singular,
				'all_items'          => sprintf( __( "All %s", 'wp-radio' ), $plural ),
				'singular_name'      => $singular,
				'add_new'            => sprintf( __( 'Add New %s', 'wp-radio' ), $singular ),
				'add_new_item'       => sprintf( __( 'Add New %s', 'wp-radio' ), $singular ),
				'edit_item'          => sprintf( __( 'Edit %s', 'wp-radio' ), $singular ),
				'new_item'           => sprintf( __( 'New %s', 'wp-radio' ), $singular ),
				'view_item'          => sprintf( __( 'View %s', 'wp-radio' ), $singular ),
				'search_items'       => sprintf( __( 'Search %s', 'wp-radio' ), $plural ),
				'not_found'          => sprintf( __( 'No %s found', 'wp-radio' ), $plural ),
				'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'wp-radio' ), $plural ),
				'parent_item_colon'  => sprintf( __( 'Parent %s:', 'wp-radio' ), $singular ),
				'menu_name'          => $menu_name,
			);

			return $labels;
		}


		/**
		 * Get all labels from taxonomies
		 *
		 * @param $menu_name
		 * @param $singular
		 * @param $plural
		 *
		 * @return array
		 * @since 1.0.0
		 */
		protected static function get_taxonomy_labels( $menu_name, $singular, $plural ) {
			$labels = array(
				'name'              => sprintf( _x( '%s', 'taxonomy general name', 'wp-radio' ), $plural ),
				'singular_name'     => sprintf( _x( '%s', 'taxonomy singular name', 'wp-radio' ), $singular ),
				'search_items'      => sprintf( __( 'Search %s', 'wp-radio' ), $plural ),
				'all_items'         => sprintf( __( 'All %s', 'wp-radio' ), $plural ),
				'parent_item'       => sprintf( __( 'Parent %s', 'wp-radio' ), $singular ),
				'parent_item_colon' => sprintf( __( 'Parent %s:', 'wp-radio' ), $singular ),
				'edit_item'         => sprintf( __( 'Edit %s', 'wp-radio' ), $singular ),
				'update_item'       => sprintf( __( 'Update %s', 'wp-radio' ), $singular ),
				'add_new_item'      => sprintf( __( 'Add New %s', 'wp-radio' ), $singular ),
				'new_item_name'     => sprintf( __( 'New %s Name', 'wp-radio' ), $singular ),
				'menu_name'         => __( $menu_name, 'wp-radio' ),
			);

			return $labels;
		}

		/**
		 * Flash The Rewrite Rules
		 *
		 * @since 2.0.2
		 */
		public function flush_rewrite_rules() {
			if ( get_option( 'wp_radio_flush_rewrite_rules' ) ) {
				flush_rewrite_rules();
				delete_option( 'wp_radio_flush_rewrite_rules' );
			}
		}

		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
}

WP_Radio_CPT::instance();

