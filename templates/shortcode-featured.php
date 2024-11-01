<?php
/* Block direct access */
defined( 'ABSPATH' ) || exit();

$paginate = ! empty( $_GET['paginate'] ) ? intval( $_GET['paginate'] ) : 1;


$args = array(
	'paged'          => $paginate,
	'posts_per_page' => $count,
	'meta_key'       => 'featured',
	'meta_value'     => 'yes',
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

$query = wp_radio_get_stations( $args, true );

if ( ! empty( $query->posts ) ) {
	$is_grid      = 'grid' == wp_radio_get_settings( 'listing_view', 'list' );
	$col          = wp_radio_get_settings( 'grid_column', 4 );
	$show_genres  = ! $is_grid && wp_radio_get_settings( 'listing_genre', true );
	$show_content = ! $is_grid && wp_radio_get_settings( 'listing_content', false );

	?>
    <div class="wp-radio-listings">
        <div class="wp-radio-listing-wrap <?php echo $is_grid ? 'wp-radio-listing-grid' : ''; ?>">
			<?php
			foreach ( $query->posts as $post ) {
				$post_id = $post->ID;

				$station = wp_radio_get_station_data( $post_id );
				wp_radio_get_template( 'listing/loop', [
					'station'      => $station,
					'is_grid'      => $is_grid,
					'col'          => $col,
					'show_genres'  => $show_genres,
					'show_content' => $show_content,
				] );
			}

			// Footer
			wp_radio_get_template( 'listing/footer', [
				'pageCount' => $query->max_num_pages,
				'paginate'  => $paginate,
			] );
			?>
        </div>
    </div>
<?php }

