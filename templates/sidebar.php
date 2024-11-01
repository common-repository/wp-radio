<?php
/* Block direct access */
defined( 'ABSPATH' ) || exit;

$countries       = get_terms( [ 'taxonomy' => 'radio_country', 'parent' => 0 ] );
$style           = ! empty( $style ) ? $style : 'vertical';
$columns         = ! empty( $columns ) ? $columns : '3';

?>


<div class="wp-radio-sidebar <?php echo $style; ?> <?php echo wp_is_mobile() ? '' : 'open'; ?>">

    <div class="sidebar-header">
        <span class="dashicons dashicons-menu-alt toggle-country-list"></span>
        <span class="sidebar-header-title"><?php _e( 'Radio Countries', 'wp-radio' ); ?></span>
    </div>

	<?php if ( ! empty( $countries ) ) { ?>
        <div class="country-search-wrap">
            <i class="dashicons dashicons-search"></i>
            <input class="country-search" placeholder="<?php _e( 'Search country', 'wp-radio' ); ?>" type="text"
                   data-list=".sidebar-listing">
        </div>

        <ul class="sidebar-listing" style="--wp-radio-country-list-columns: <?php echo $columns; ?>">
			<?php
			$i = 0;

			foreach ( $countries as $country ) {

				$image = wp_radio_get_country_flag( $country->slug, 16 );


				printf( '<li %s ><a href="%s" title="%4$s">%s <span>%s</span></a></li>', ! empty( $active ) && $country->slug == $active ? 'class="active"' : '', get_term_link( $country->term_id ), $image, $country->name );

				$i ++;

			}//End of the loop

			?>
        </ul>

	<?php } else { ?>
        <p class="no-country"><?php _e( 'No country added yet.', 'wp-radio' ); ?></p>
	<?php } ?>

</div>