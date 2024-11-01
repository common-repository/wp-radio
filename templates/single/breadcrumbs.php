<?php

global $post;

$location = wp_radio_get_location( $post->ID );

$country = ! empty( $location['country'] ) ? $location['country'] : false;
$region  = ! empty( $location['region'] ) ? $location['region'] : false;
$city    = ! empty( $location['city'] ) ? $location['city'] : false;

$icon = '<svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 898.3 898.3">
        <g>
            <g>
                <polygon points="120.2,882.5 553.6,449.2 120.2,15.8 0,136 313.2,449.2 0,762.3"/>
                <polygon points="344.7,762.3 464.9,882.5 898.3,449.2 464.9,15.8 344.7,136 657.9,449.2"/>
            </g>
        </g>
    </svg>';

?>

<div class="wp-radio-breadcrumb">

    <div class="wp-radio-breadcrumb-items">
		<?php

		if ( $country ) {
			printf( '<a class="breadcrumb-item" href="%s">%s %s </a> %s',
				 $country['link'], wp_radio_get_country_flag( $country['slug'] ), $country['name'], $icon );
		}

		if ( $region ) {
			printf( '<a class="breadcrumb-item" href="%s">%s %s </a> %s',
				 $region['link'], wp_radio_get_country_flag( $region['slug'] ), $region['name'], $icon );
		}

		if ( $city ) {
			printf( '<a class="breadcrumb-item" href="%s">%s %s </a> %s',
				 $city['link'], wp_radio_get_country_flag( $city['slug'] ), $city['name'], $icon );
		}

		if ( $country || $region || $city ) {
			printf( '<span class="breadcrumb-item disabled">%s</span>', get_the_title( $post ) );
		}

		?>

    </div>
</div>