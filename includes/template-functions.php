<?php

defined( 'ABSPATH' ) || exit();

function wp_radio_get_template( $template_name, $args = array(), $template_path = 'wp-radio', $default_path = '' ) {

	/* Add php file extension to the template name */
	$template_name = $template_name . '.php';

	/* Extract the args to variables */
	if ( $args && is_array( $args ) ) {
		extract( $args );
	}

	/* Look within passed path within the theme - this is high priority. */
	$template = locate_template( array( trailingslashit( $template_path ) . $template_name ) );

	/* Get default template. */
	if ( ! $template && false !== $default_path ) {
		$default_path = $default_path ? $default_path : WP_RADIO_TEMPLATES;
		if ( file_exists( trailingslashit( $default_path ) . $template_name ) ) {
			$template = trailingslashit( $default_path ) . $template_name;
		}
	}

	// Return what we found.
	include( apply_filters( 'wp_radio_locate_template', $template, $template_name, $template_path ) );

}

function wp_radio_popup_player() {

	if ( ! empty( $_GET['player'] ) && 'popup' == $_GET['player'] && ! empty( $_GET['station_id'] ) ) {

		$id = intval( $_GET['station_id'] );

		query_posts( [
			'p'         => $id,
			'post_type' => get_post_type( $id ),
		] );

		add_filter( 'show_admin_bar', '__return_false' );

		// Remove all WordPress actions
		remove_all_actions( 'wp_head' );
		remove_all_actions( 'wp_print_styles' );
		remove_all_actions( 'wp_print_head_scripts' );
		remove_all_actions( 'wp_footer' );

		// Handle `wp_head`
		add_action( 'wp_head', 'wp_enqueue_scripts', 1 );
		add_action( 'wp_head', 'wp_print_styles', 8 );
		add_action( 'wp_head', 'wp_print_head_scripts', 9 );
		add_action( 'wp_head', 'wp_site_icon' );

		// Handle `wp_footer`
		add_action( 'wp_footer', 'wp_print_footer_scripts', 20 );
		add_action( 'wp_footer', 'wp_auth_check_html', 30 );

		// Handle `wp_enqueue_scripts`
		remove_all_actions( 'wp_enqueue_scripts' );

		// Also remove all scripts hooked into after_wp_tiny_mce.
		remove_all_actions( 'after_wp_tiny_mce' );

		if ( is_callable( [ 'WP_Radio_Enqueue', 'frontend_scripts' ] ) ) {
			add_action( 'wp_enqueue_scripts', [ 'WP_Radio_Enqueue', 'frontend_scripts' ], 999999 );
		}

		if ( defined( 'WR_USER_FRONTEND_VERSION' ) ) {
			if ( is_callable( [ 'WR_User_Frontend_Enqueue', 'enqueue_scripts' ] ) ) {
				add_action( 'wp_enqueue_scripts', [ 'WR_User_Frontend_Enqueue', 'enqueue_scripts' ], 999999 );
			}
		}

		if ( defined( 'WR_ADS_PLAYER_VERSION' ) ) {
			if ( is_callable( [ 'WR_Ads_Player_Enqueue', 'frontend_scripts' ] ) ) {
				add_action( 'wp_enqueue_scripts', [ 'WR_Ads_Player_Enqueue', 'frontend_scripts' ], 999999 );
			}
		}

		?>

        <!doctype html>
        <html lang="<?php language_attributes(); ?>">
        <head>
            <meta charset="<?php bloginfo( 'charset' ); ?>">
            <meta name="viewport"
                  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title><?php wp_title( '' ); ?></title>

			<?php do_action( 'wp_head' ); ?>

            <style>
                .wp-radio-popup-player {
                    padding: 0 !important;
                }
            </style>

        </head>
        <body class="wp-radio-popup-player">

		<?php

		wp_radio_get_template( 'player', [ 'player_type' => 'popup', 'id' => $id ] );

		if ( defined( 'WR_ADS_PLAYER_VERSION' ) ) {
			$ad = wp_radio_get_settings( 'popup_ads' );

			if ( ! empty( $ad ) ) {
				echo '<div class="wp-radio-ads">' . $ad . '</div>';
			}
		}

		?>


		<?php do_action( 'wp_footer' ); ?>

        </body>
        </html>

		<?php
		exit();
	}
}

add_action( 'template_redirect', 'wp_radio_popup_player' );

/**
 * Station Listing
 */
function wp_radio_listing_page_content() {

	$queried_object = get_queried_object();
	global $wp_query;

	$is_tax    = is_tax( 'radio_country' ) || is_tax( 'radio_genre' );
	$is_single = is_singular( 'wp_radio' );

	if ( $is_tax ) {
		$post_id   = 0;
		$post_name = ! empty( $queried_object->slug ) ? $queried_object->slug : '';

		ob_start();
		echo do_shortcode( '[wp_radio_listing]' );
		$post_content = ob_get_clean();

	} else if ( $is_single ) {
		$post_id   = $queried_object->ID;
		$post_name = $queried_object->post_name;

		ob_start();
		wp_radio_get_template( 'single' );
		$post_content = ob_get_clean();
	}

	$dummy_post_properties = array(
		'ID'                    => $post_id,
		'post_status'           => 'publish',
		'post_author'           => '',
		'post_parent'           => 0,
		'post_type'             => 'wp_radio',
		'post_date'             => '',
		'post_date_gmt'         => '',
		'post_modified'         => '',
		'post_modified_gmt'     => '',
		'post_content'          => $post_content,
		'post_title'            => '',
		'post_excerpt'          => '',
		'post_content_filtered' => '',
		'post_mime_type'        => '',
		'post_password'         => '',
		'post_name'             => $post_name,
		'guid'                  => '',
		'menu_order'            => 0,
		'pinged'                => '',
		'to_ping'               => '',
		'ping_status'           => '',
		'comment_status'        => 'closed',
		'comment_count'         => 0,
		'filter'                => 'raw',
	);

	// Set the $post global.
	$post = new WP_Post( (object) $dummy_post_properties );

	// Copy the new post global into the main $wp_query.
	$wp_query->post  = $post;
	$wp_query->posts = array( $post );

	// Prevent comments form from appearing.
	$wp_query->post_count = 1;
	$wp_query->is_page    = true;
	$wp_query->is_single  = true;

	if ( is_tax() ) {
		$wp_query->is_single = false;
		$wp_query->is_tax    = true;
	}

	$wp_query->max_num_pages = 0;

	// Prepare everything for rendering.
	setup_postdata( $post );

	remove_all_filters( 'the_content' );
	remove_all_filters( 'the_excerpt' );
	remove_all_filters( 'the_title' );
}

function wp_radio_template_redirect() {
	if ( is_singular( 'wp_radio' )
	     || is_tax( 'radio_country' )
	     || is_tax( 'radio_genre' ) ) {

		if ( is_tax( 'radio_genre' ) ) {
			add_filter( 'edit_post_link', '__return_false' );
		}

		wp_radio_listing_page_content();

	}
}

add_action( 'template_redirect', 'wp_radio_template_redirect', - 9 );

/**
 * Single Station page content
 *
 * @param $content
 *
 * @return false|string
 */
function wp_radio_filter_content( $content ) {
	if ( is_singular( 'wp_radio' ) ) {
		ob_start();
		wp_radio_get_template( 'single' );
		$content = ob_get_clean();
	}

	return $content;
}

add_filter( 'the_content', 'wp_radio_filter_content' );


// add breadcrumb to the archive title
add_filter( 'get_the_archive_title', 'wp_radio_archive_title', 10, 2 );

function wp_radio_archive_title( $title, $original_title ) {

	if ( is_tax( 'radio_country' ) || is_tax( 'radio_genre' ) ) {
		$image = wp_radio_get_country_flag( get_queried_object()->slug, 24 );
		$title = $image . $original_title . __( ' - Radio Stations', 'wp-radio' );

		$title = sprintf( '<div class="wp-radio-page-title archive-title"><h2 class="entry-title" itemprop="headline">%s</h2></div>', $title );
	}

	return $title;
}