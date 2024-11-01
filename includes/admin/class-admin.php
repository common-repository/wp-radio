<?php

defined( 'ABSPATH' ) || exit();

class WP_Radio_Admin {

	private static $instance = null;

	/**
	 * Admin constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );

		add_action( 'admin_init', [ $this, 'init_update' ] );

		add_action( 'pre_get_posts', [ $this, 'pre_get_posts' ] );
		add_action( 'restrict_manage_posts', [ $this, 'add_posts_filter_field' ] );
		add_filter( 'display_post_states', [ $this, 'station_page_status' ], 10, 2 );

		/*Add ID column to the posts table*/
		add_filter( 'manage_edit-wp_radio_columns', [ $this, 'custom_set_posts_columns' ] );
		add_action( 'manage_posts_custom_column', [ $this, 'custom_set_posts_columns_value' ], 10, 2 );

		//add_action( 'admin_menu', array( $this, 'recommended_plugins_menu' ), 11 );

	}

	public function recommended_plugins_menu() {
		if ( isset( $_GET['hide_wp_radio_recommended_plugin'] ) && isset( $_GET['nonce'] ) ) {
			if ( current_user_can( 'manage_options' ) ) {
				$nonce = $_GET['nonce'];
				if ( wp_verify_nonce( $nonce, 'wp_radio_recommended_plugin' ) ) {
					update_option( 'hide_wp_radio_recommended_plugin', true );
				}
			}
		}

		if ( ! get_option( 'hide_wp_radio_recommended_plugin' ) ) {
			add_submenu_page(
				'edit.php?post_type=wp_radio', 'Recommended Plugins', 'Recommended Plugins', 'manage_options',
				'wp-radio-recommended-plugins', [ $this, 'recommended_plugins_page' ], 99
			);
		}
	}


	public function custom_set_posts_columns( $columns ) {
		return array(
			'cb'                     => '<input type="checkbox" />',
			'id'                     => __( 'ID', 'wp-radio' ),
			'title'                  => __( 'Title', 'wp-radio' ),
			'taxonomy-radio_country' => __( 'Country', 'wp-radio' ),
			'taxonomy-radio_genre'   => __( 'Genre', 'wp-radio' ),
			'date'                   => __( 'Date', 'wp-radio' ),
		);
	}

	public function custom_set_posts_columns_value( $column, $post_id ) {
		if ( 'wp_radio' == get_post_type( $post_id ) && $column == 'id' ) {
			echo $post_id;
		}
	}


	/**
	 * Run Update
	 *
	 * @return void
	 * @since 2.0.8
	 *
	 */
	public function init_update() {

		if ( ! class_exists( 'WP_Radio_Update' ) && current_user_can( 'manage_options' ) ) {
			include_once WP_RADIO_INCLUDES . '/admin/class-update.php';

			$updater = WP_Radio_Update::instance();
			if ( $updater->needs_update() ) {
				$updater->perform_updates();
			}
		}
	}

	/**
	 * Add post filter field
	 *
	 * @return void
	 */
	public function add_posts_filter_field() {
		$type = ! empty( $_GET['post_type'] ) ? sanitize_key( $_GET['post_type'] ) : '';

		if ( 'wp_radio' == $type ) { ?>
            <select name="country">
                <option value=""><?php _e( 'All Countries', 'wp-radio' ); ?></option>
				<?php
				$countries = get_terms( [ 'taxonomy' => 'radio_country', 'parent' => 0 ] );

				if ( ! empty( $countries ) ) {

					foreach ( $countries as $country ) {
						printf( '<option value="%1$s" %2$s >%3$s</option>', $country->slug, selected( $country->slug, ! empty( $_GET['country'] ) ? $_GET['country'] : '' ), $country->name );
					}
				}
				?>
            </select>
			<?php
		}
	}

	public function pre_get_posts( $query ) {

		if ( ! is_admin() ) {
			return;
		}

		global $pagenow;
		$is_edit       = 'edit.php' == $pagenow;
		$is_type       = ! empty( $query->query_vars['post_type'] ) && 'wp_radio' == $query->query_vars['post_type'];
		$is_main_query = $query->is_main_query();

		if ( $is_edit && $is_type && $is_main_query ) {


			if ( ! empty( $_GET['country'] ) ) {
				$query->query_vars['tax_query'] = [
					'relation' => 'AND',
					[
						'taxonomy' => 'radio_country',
						'field'    => 'slug',
						'terms'    => sanitize_text_field( $_GET['country'] ),
					]
				];
			}

		}

	}

	/**
	 * Add admin menu
	 *
	 * @return void
	 * @since 2.0.7
	 *
	 */
	public function admin_menu() {

		add_submenu_page( 'edit.php?post_type=wp_radio', __( 'Getting Started with WP Radio', 'wp-radio' ), __( 'Getting Started', 'wp-radio' ), 'manage_options', 'wp-radio-getting-started', [
			$this,
			'render_getting_started_page'
		] );

		add_submenu_page( 'edit.php?post_type=wp_radio', __( 'Import Radio Stations', 'wp-radio' ), __( 'Import Stations', 'wp-radio' ), 'manage_options', 'wp-radio-import-stations', [
			$this,
			'import_menu_page'
		] );

	}

	public function render_getting_started_page() { ?>
        <div class="wp-radio-getting-started-wrapper"></div>
	<?php }

	public function recommended_plugins_page() {
		include_once WP_RADIO_INCLUDES . '/admin/views/recommended-plugins.php';
	}

	/**
	 * Import Page
	 */
	public function import_menu_page() {
		include WP_RADIO_INCLUDES . '/admin/views/html-import-stations.php';
	}


	/**
	 * Add status for radios base page
	 *
	 * @param $states
	 * @param $post
	 *
	 * @return array
	 */
	public function station_page_status( $states, $post ) {

		if ( wp_radio_get_settings( 'stations_page' ) == $post->ID ) {
			$states[] = __( 'Radio Archive', 'wp-radio' );

		}

		return $states;
	}


	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}

WP_Radio_Admin::instance();