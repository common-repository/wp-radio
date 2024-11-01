<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( empty( $locations ) ) {
	return;
}

$country = ! empty( $locations['country'] ) ? $locations['country'] : false;
$region  = ! empty( $locations['region'] ) ? $locations['region'] : false;
$city    = ! empty( $locations['city'] ) ? $locations['city'] : false;

?>
<div class="radio-country"><span><?php _e( 'Country:', 'wp-radio' ); ?></span>

	<?php

	if ( $country ) {
		printf( '<a class="radio-country-link" href="%s">%s</a>',
			$country['link'], wp_radio_get_country_flag( $country['slug'] ) );
	}

	if ( $city ) {
		printf( '<a class="radio-country-link" href="%s">%s</a>,',
			$city['link'], $city['name'] );
	}

	if ( $region ) {
		printf( '<a class="radio-country-link" href="%s">%s</a>,',
			$region['link'], $region['name'] );
	}

	if ( $country ) {
		printf( ' <a class="radio-country-link" href="%s"> %s</a>',
			$country['link'], $country['name'] );
	}

	?>
</div>