<?php

$is_grid = 'grid' == wp_radio_get_settings( 'listing_view', 'list' );

$args = [
	'posts_per_page' => $is_grid ? 4 : 3,
	'orderby'        => 'rand',
	'post__not_in'   => [ $post_id ],
	'tax_query'      => [
		'relation' => 'AND',
	],
];

$location = wp_radio_get_station_location( $post_id );

if ( ! empty( $location['country'] ) ) {
	$country = $location['country'];

	$args['tax_query'][] = [
		'taxonomy' => 'radio_country',
		'field'    => 'term_id',
		'terms'    => $country->term_id,
	];
}

$genres = wp_get_post_terms( $post_id, 'radio_genre' );
$genres = wp_list_pluck( $genres, 'term_id' );

if ( ! empty( $genres ) ) {
	$args['tax_query'][] = [
		'taxonomy' => 'radio_genre',
		'field'    => 'term_id',
		'terms'    => $genres,
	];
}

$related = wp_radio_get_stations( $args );

if ( ! empty( $related ) ) { ?>
    <div class="wp-radio-related">
        <h3><?php esc_html_e( 'You Also May Like', 'wp-radio' ); ?></h3>

        <div class="wp-radio-listings <?php echo $is_grid ? 'wp-radio-listing-grid' : ''; ?>">
            <div class="wp-radio-listing-wrap">
				<?php
				$col          = wp_radio_get_settings( 'grid_column', 4 );
				$show_genres  = ! $is_grid && wp_radio_get_settings( 'listing_genre', true );
				$show_content = ! $is_grid && wp_radio_get_settings( 'listing_content', false );
				foreach ( $related as $station ) {
					wp_radio_get_template( 'listing/loop', [
						'station' => $station,
						'is_grid' => $is_grid,
                        'show_genres' => $show_genres,
                        'show_content' => $show_content,
					] );
				}
				?>
            </div>
        </div>
    </div>
<?php } ?>