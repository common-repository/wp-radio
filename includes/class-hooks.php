<?php

/* Block direct access */
defined( 'ABSPATH' ) || exit();

class WP_Radio_Hooks {

	private static $instance = null;

	public function __construct() {

		// render footer player
		add_action( 'wp_footer', [ $this, 'wp_radio_footer' ] );

		add_filter( 'get_previous_post_join', [ $this, 'prev_next_post_join' ] );
		add_filter( 'get_next_post_join', [ $this, 'prev_next_post_join' ] );

		add_filter( 'get_next_post_sort', [ $this, 'next_post_sort' ] );
		add_filter( 'get_previous_post_sort', [ $this, 'previous_post_sort' ] );

		add_filter( 'get_next_post_where', [ $this, 'next_post_where' ] );
		add_filter( 'get_previous_post_where', [ $this, 'previous_post_where' ] );

		add_filter( 'excerpt_more', [ $this, 'excerpt_more' ], 99 );

		wr_fs()->add_action( 'after_uninstall', [ $this, 'uninstall' ] );
	}


	/**
	 * WP Radio Uninstall
	 *
	 * Uninstalling WP Radio deletes stations, settings
	 *
	 * @since 2.0.2
	 */
	public function uninstall() {

		$delete_data = wp_radio_get_settings( 'delete_data', false );
		if ( $delete_data ) {
			global $wpdb;

			//delete pages
			$station_page = wp_radio_get_settings( 'stations_page' );
			if ( $station_page ) {
				wp_delete_post( $station_page, true );
			}

			// Delete options.
			$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'wp\_radio\_%';" );

			// Delete posts + data.
			$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type = 'wp_radio';" );
			$wpdb->query( "DELETE meta FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL;" );

			// Delete term taxonomies.
			foreach ( array( 'radio_country', 'radio_genre' ) as $taxonomy ) {
				$wpdb->delete( $wpdb->term_taxonomy,
					array(
						'taxonomy' => $taxonomy,
					) );
			}

			// Delete orphan relationships.
			$wpdb->query( "DELETE tr FROM {$wpdb->term_relationships} tr LEFT JOIN {$wpdb->posts} posts ON posts.ID = tr.object_id WHERE posts.ID IS NULL;" );

			// Delete orphan terms.
			$wpdb->query( "DELETE t FROM {$wpdb->terms} t LEFT JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id WHERE tt.term_id IS NULL;" );

			// Delete orphan term meta.
			if ( ! empty( $wpdb->termmeta ) ) {
				$wpdb->query( "DELETE tm FROM {$wpdb->termmeta} tm LEFT JOIN {$wpdb->term_taxonomy} tt ON tm.term_id = tt.term_id WHERE tt.term_id IS NULL;" );
			}

			// Clear any cached data that has been removed.
			wp_cache_flush();
		}
	}


	/**
	 * Footer full-width player
	 * Load only on popup window if popup enabled
	 *
	 * @since 2.0.2
	 */
	public function wp_radio_footer() {
		global $pagenow;
		if ( ! empty( $pagenow ) && 'widgets.php' == $pagenow ) {
			return false;
		}

		wp_radio_get_template( 'player', [ 'player_type' => 'full-width' ] );
	}

	/**
	 * Filter Previous/ Next Station Link
	 *
	 * @param $join
	 *
	 * @return string
	 */
	public function prev_next_post_join( $join ) {
		global $post, $wpdb;
		if ( get_post_type( $post ) == 'wp_radio' ) {
			$join = " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";
		}

		return $join;
	}

	public function next_post_sort( $sort ) {
		global $post;
		if ( get_post_type( $post ) == 'wp_radio' ) {
			$sort = "ORDER BY p.post_title ASC LIMIT 1";
		}

		return $sort;
	}

	public function next_post_where( $where ) {
		global $post, $wpdb;

		if ( get_post_type( $post ) == 'wp_radio' ) {
			$where = $wpdb->prepare( "WHERE p.post_title > '%s' AND p.post_type = '" . get_post_type( $post ) . "' AND p.post_status = 'publish'",
				$post->post_title );

			$term_array = wp_get_object_terms( $post->ID, 'radio_country', array( 'fields' => 'ids' ) );
			$term_array = array_map( 'intval', $term_array );

			if ( ! $term_array || is_wp_error( $term_array ) ) {
				return '';
			}

			$where .= ' AND tt.term_id IN (' . implode( ',', $term_array ) . ')';
		}

		return $where;
	}

	public function previous_post_sort( $sort ) {
		global $post;

		if ( get_post_type( $post ) == 'wp_radio' ) {
			$sort = "ORDER BY p.post_title DESC LIMIT 1";
		}

		return $sort;
	}

	public function previous_post_where( $where ) {
		global $post, $wpdb;

		if ( get_post_type( $post ) == 'wp_radio' ) {
			$where = $wpdb->prepare( "WHERE p.post_title < '%s' AND p.post_type = '" . get_post_type( $post ) . "' AND p.post_status = 'publish'",
				$post->post_title );

			$term_array = wp_get_object_terms( $post->ID, 'radio_country', array( 'fields' => 'ids' ) );
			$term_array = array_map( 'intval', $term_array );

			if ( ! $term_array || is_wp_error( $term_array ) ) {
				return '';
			}

			$where .= ' AND tt.term_id IN (' . implode( ',', $term_array ) . ')';
		}

		return $where;
	}

	public function excerpt_more( $more ) {
		return 'wp_radio' == get_post_type() ? sprintf( '... <a href="%1$s" class="read-more">[ %2$s ]</a>',
			get_the_permalink(),
			__( 'View Station', 'wp-radio' ) ) : $more;
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}


WP_Radio_Hooks::instance();
