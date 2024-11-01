<?php

global $post_id;

$countries = get_terms( [
	'taxonomy'   => 'radio_country',
	'hide_empty' => false,
	'number'     => 1
] );


$location = wp_radio_get_station_location( $post_id );

$country = ! empty( $location['country'] ) ? $location['country'] : '';
$state   = ! empty( $location['region'] ) ? $location['region'] : '';
$city    = ! empty( $location['city'] ) ? $location['city'] : '';


?>

<div class="location-metabox">

	<?php
	if ( empty( $countries ) ) { ?>
        <div style="text-align: center">
            <h4><?php _e( 'You didn\'t add any country yet.<br>To select a country for the station, you need add a country first.', 'wp-radio' ); ?></h4>
            <a href="<?php echo admin_url( 'edit-tags.php?taxonomy=radio_country&post_type=wp_radio' ); ?>"
               class="button button-primary"><?php esc_html_e( 'Add Country', 'wp-radio' ); ?></a>
        </div>
	<?php } else {
		?>

        <span class="spinner is-active"></span>

        <!--country-->
        <div>
            <label for="country"><?php esc_html_e( 'Country:', 'wp-radio' ); ?></label>
            <select name="country" id="country" data-placeholder="<?php echo ! empty( $country->name ) ? $country->name
				: __( 'Select country', 'wp-radio' ) ?>">
                <option value=""><?php esc_html_e( 'Select Country', 'wp-radio' ); ?></option>
				<?php

				$countries = get_terms( [ 'taxonomy' => 'radio_country', 'parent' => 0, 'hide_empty' => false, ] );

				if ( ! empty( $countries ) ) {
					$countries = wp_list_pluck( $countries, 'name', 'term_id' );


					foreach ( $countries as $id => $name ) {
						printf( '<option value="%s" %s >%s</option>', $id,
							selected( $id, ! empty( $country->term_id ) ? $country->term_id : 0, false ), $name );
					}
				}

				?>
            </select>
        </div>

        <!-- State -->
        <div>
            <label for="state"><?php esc_html_e( 'State:', 'wp-radio' ); ?></label>
            <select name="state" id="state"
                    data-placeholder="<?php echo ! empty( $state->name ) ? $state->name : __( 'Select state', 'wp-radio' ); ?>">
				<?php

				if ( $country ) {
					$states = get_terms( [
						'taxonomy'   => 'radio_country',
						'parent'     => $country->term_id,
						'hide_empty' => false,
					] );

					if ( empty( $state ) ) {
						echo '<option value="">' . __( 'Select state', 'wp-radio' ) . '</option>';
					}

					if ( ! empty( $states ) ) {

						$states = wp_list_pluck( $states, 'name', 'term_id' );

						foreach ( $states as $id => $name ) {
							printf( '<option value="%s" %3$s>%s</option>', $id, $name, selected( $id, ! empty( $state->term_id ) ? $state->term_id : '', false ) );
						}
					}
				}

				?>
            </select>
        </div>

        <!-- City -->
        <div>
            <label for="city"><?php esc_html_e( 'City: ', 'wp-radio' ); ?></label>
            <select name="city" id="city"
                    data-placeholder="<?php echo ! empty( $city->name ) ? $city->name : __( 'Select city', 'wp-radio' ); ?>">
				<?php

				if ( $state ) {
					$cities = get_terms( [
						'taxonomy'   => 'radio_country',
						'parent'     => $state->term_id,
						'hide_empty' => false,
					] );

					if ( ! empty( $cities ) ) {

						$cities = wp_list_pluck( $cities, 'name', 'term_id' );

						foreach ( $cities as $id => $name ) {
							printf( '<option value="%s" %3$s>%s</option>', $id, $name, selected( $id, $city->term_id, false ) );
						}
					} else {
						printf( '<option value="0">' . __( 'No Cities Found', 'wp-radio' ) . '</option>' );
					}
				}
				?>
            </select>
        </div>

	<?php } ?>

</div>