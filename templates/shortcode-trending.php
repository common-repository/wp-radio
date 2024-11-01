<?php
/* Block direct access */
defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'WP_Radio_Statistics' ) ) {
	include_once WP_RADIO_INCLUDES . '/class-statistics__premium_only.php';
}

$args = [
	'count' => $count,
];
if ( ! empty( $country ) ) {
	$country_term = get_term_by( 'slug', strtolower( $country ), 'radio_country' );
	if ( ! empty( $country_term ) ) {
		$args['where'] = " AND station_country_id = $country_term->term_id";
	}
}

$stations = WP_Radio_Statistics::instance( $args )->get_top_stations();

$from_posts = false;
if ( empty( $stations ) ) {
	$args = array(
		'post_type'   => 'wp_radio',
		'numberposts' => $count,
		'order'       => 'DESC',
		'orderby'     => 'ID',
	);

	if ( ! empty( $country ) ) {
		$args['tax_query'] = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'radio_country',
				'field'    => 'slug',
				'terms'    => $country,
			)
		);
	}

	$stations = get_posts( $args );

	$from_posts = true;
}

$items = [];

if ( ! empty( $stations ) ) {
	$is_grid      = 'grid' == wp_radio_get_settings( 'listing_view', 'list' );
	$col          = wp_radio_get_settings( 'grid_column', 4 );
	$show_genres  = ! $is_grid && wp_radio_get_settings( 'listing_genre', true );
	$show_content = ! $is_grid && wp_radio_get_settings( 'listing_content', false );

	?>
    <div class="wp-radio-listings">
        <div class="wp-radio-listing-wrap <?php echo $is_grid ? 'wp-radio-listing-grid' : ''; ?>">

			<?php

			foreach ( $stations as $station ) {
				$post_id = $from_posts ? $station->ID : $station->station_id;

				$station = wp_radio_get_station_data( $post_id );
				wp_radio_get_template( 'listing/loop', [
					'station'      => $station,
					'is_grid'      => $is_grid,
					'col'          => $col,
					'show_genres'  => $show_genres,
					'show_content' => $show_content,
				] );
			}
			?>
        </div>
    </div>
<?php }


