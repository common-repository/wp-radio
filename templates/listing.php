<?php

defined( 'ABSPATH' ) || exit();

$queried_object = get_queried_object();

$searchKeyword = ! empty( $_GET['keyword'] ) ? sanitize_text_field( urldecode( $_GET['keyword'] ) ) : '';
$searchCountry = ! empty( $_GET['country'] ) && 'all' != $_GET['country'] ? sanitize_text_field( $_GET['country'] ) : '';
$searchGenre   = ! empty( $_GET['genre'] ) ? intval( $_GET['genre'] ) : '';

$paginate = ! empty( $_GET['paginate'] ) ? intval( $_GET['paginate'] ) : 1;
$per_page = ! empty( $_GET['perpage'] ) ? intval( $_GET['perpage'] ) : wp_radio_get_settings( 'posts_per_page', 20 );

$sort = ! empty( $_GET['sort'] ) ? sanitize_key( $_GET['sort'] )
	: wp_radio_get_settings( 'listing_order', 'asc' );

$is_grid = 'grid' == wp_radio_get_settings( 'listing_view', 'list' );

/* Check if it is search results */
$is_search = ! empty( $searchKeyword ) || ! empty( $searchCountry ) || ! empty( $searchGenre );

/* Check if it is taxonomy page */
$is_tax = is_tax( 'radio_country' ) || is_tax( 'radio_genre' );

/* get filter args from the shortcode */
$shortcode_filter = ! empty( $shortcode_args['country'] ) || ! empty( $shortcode_args['genre'] );

/*check if is customized result*/
$is_custom = $shortcode_filter || $is_search || $is_tax;

$ip_listing = wp_radio_get_settings( 'ip_listing', false );

$country = [];
$genre   = [];

/* If not customized query, then get the user country results first  */
if ( ! $is_custom && $ip_listing ) {
	$ip_country = wp_radio_get_visitor_country();
	$country[]  = $ip_country;
} else {

	//country search
	if ( ! empty( $searchCountry ) ) {
		$country[] = $searchCountry;
	}

	//country archive taxonomy
	if ( is_tax( 'radio_country' ) ) {
		$country[] = $queried_object->slug;
	}

	//genre search
	if ( ! empty( $searchGenre ) ) {
		$genre[] = $searchGenre;
	}

	//genre taxonomy archive
	if ( is_tax( 'radio_genre' ) ) {
		$genre[] = intval( $queried_object->term_id );
	}


	$countries = ! empty( $shortcode_args['country'] ) ? array_filter( explode( ',', $shortcode_args['country'] ) ) : '';
	if ( ! empty( $countries ) ) {
		$country = $countries;
	}


	$genres = ! empty( $shortcode_args['genre'] ) ? array_filter( explode( ',', $shortcode_args['genre'] ) ) : '';
	if ( ! empty( $genres ) ) {
		foreach ( $genres as $g ) {
			$genre_term = get_term_by( 'slug', $g, 'radio_genre' );
			if ( $genre_term ) {
				$genre[] = $genre_term->term_id;
			}
		}
	}
}

$country_term = get_term_by( 'slug', end( $country ), 'radio_country' );

$data_country = ! is_wp_error( $country_term ) && ! empty( $country_term ) && $country_term->count > 0 ? end( $country ) : '';


/**
 * Query args
 */
$args = [
	'paged' => $paginate,
];

/** per page */
if ( ! empty( $per_page ) ) {
	$args['posts_per_page'] = $per_page;
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
if ( ! empty( $searchKeyword ) ) {
	$args['s'] = $searchKeyword;
}


/** country tax query */
$countries = [];

if ( ! empty( $country ) ) {
	if ( is_array( $country ) ) {
		if ( ! empty( array_filter( $country ) ) ) {
			$countries = $country;
		}
	} else {
		$countries[] = $country;
	}
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
	if ( is_array( $genre ) ) {
		if ( ! empty( array_filter( $genre ) ) ) {
			$genres = $genre;
		}
	} else {
		$genres[] = $genre;
	}
}

if ( ! empty( $genres ) ) {
	$args['tax_query'][] = array(
		'relation' => 'AND',
		'taxonomy' => 'radio_genre',
		'field'    => 'term_id',
		'terms'    => $genres,
	);
}


$query = wp_radio_get_stations( $args, true );

/**
 * If no stations found for the ip listing country - get all stations
 */
if ( ! $query->found_posts && $paginate == 1 ) {
	$ip_listing = wp_radio_get_settings( 'ip_listing', false );

	if ( $ip_listing ) {
		unset( $args['tax_query'][0] ); //remove country from query args
		$query = wp_radio_get_stations( $args, true );
	}
}

$total      = $query->found_posts;
$page_count = $query->max_num_pages;
$stations   = wp_radio_get_listing_items( $query );

if ( ! empty( $country ) && 'all' !== $country ) {
	$regions = wp_radio_get_regions( is_array( $country ) ? end( $country ) : $country );
}


?>
<div class="wp-radio-listings">
    <div class="wp-radio-listing-wrap <?php echo $is_grid ? 'wp-radio-listing-grid' : ''; ?>">
		<?php

		//Regions
		if ( ! empty( $regions ) ) {
			wp_radio_get_template( 'listing/regions', [
				'regions' => $regions,
			] );
		}


		$action_link = get_permalink( wp_radio_get_settings( 'stations_page' ) );
		?>

        <form class="wp-radio-search-form-wrap" action="<?php echo $action_link; ?>">
			<?php

			if ( wp_radio_get_settings( 'show_search', true ) ) {
				wp_radio_get_template( 'listing/search', [
					'country' => $country,
					'genre'   => $genre,
				] );
			}

			if ( wp_radio_get_settings( 'listing_header', true ) ) {
				wp_radio_get_template( 'listing/header', [
					'paginate' => $paginate,
					'perpage'  => $per_page,
					'sort'     => $sort,
					'total'    => $total,
				] );
			}

			?>
        </form>

		<?php

		// Loop
		if ( ! empty( $stations ) ) {
			$is_grid      = 'grid' == wp_radio_get_settings( 'listing_view', 'list' );
			$col          = wp_radio_get_settings( 'grid_column', 4 );
			$show_genres  = ! $is_grid && wp_radio_get_settings( 'listing_genre', true );
			$show_content = ! $is_grid && wp_radio_get_settings( 'listing_content', false );
			foreach ( $stations as $station ) {
				wp_radio_get_template( 'listing/loop', [
					'station'      => $station,
					'is_grid'      => $is_grid,
					'col'          => $col,
					'show_genres'  => $show_genres,
					'show_content' => $show_content,
				] );
			}
		}

		// Footer
		wp_radio_get_template( 'listing/footer', [
			'pageCount' => $page_count,
			'paginate'  => $paginate,
		] );

		?>
    </div>
</div>
