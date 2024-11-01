<?php

defined( 'ABSPATH' ) || exit;

$keyword = isset( $_GET['keyword'] ) ? sanitize_text_field( $_GET['keyword'] ) : '';
$country = isset( $_GET['country'] ) ? sanitize_text_field( $_GET['country'] ) : '';
$genre   = isset( $_GET['genre'] ) ? sanitize_text_field( $_GET['genre'] ) : '';

$country_terms = get_terms( [ 'taxonomy' => 'radio_country', 'parent' => 0 ] );
$genre_terms   = get_terms( [ 'taxonomy' => 'radio_genre', 'parent' => 0 ] );

$countries = [];
foreach ( $country_terms as $item ) {
	$countries[ $item->slug ] = wp_radio_escape_quote( $item->name );
}

$genres = [];
foreach ( $genre_terms as $item ) {
	$genres[ $item->term_id ] = wp_radio_escape_quote( $item->name );
}

$country_filter = ! isset( $country_filter ) || 'true' == $country_filter;
$genre_filter   = ! isset( $genre_filter ) || 'true' == $genre_filter;

?>

<div class="wp-radio-search search-1">

    <div class="search_toggle"
         onclick="jQuery('.wp-radio-search-form').toggleClass('hidden');"
    >
        <button type="button" class="button-primary">
            <i class="dashicons dashicons-menu"></i>
			<?php esc_html_e( 'Search stations', 'wp-radio' ); ?>
        </button>
    </div>

    <div class="wp-radio-search-form <?php echo wp_is_mobile() ? 'hidden' : ''; ?>">

        <div class="search-input">
            <input name="keyword" type="search" placeholder="<?php _e( 'Enter search keyword', 'wp-radio' ); ?>"
                   value="<?php echo $keyword; ?>"
            >
        </div>

		<?php if ( $country_filter ) { ?>
            <div class="wp-radio-select-wrap country-select">
                <select name="country" class="wp-radio-select">
                    <option value=""><?php _e( 'Select country', 'wp-radio' ); ?></option>
					<?php
					if ( ! empty( $countries ) ) {
						foreach ( $countries as $code => $name ) {
							printf( '<option value="%s" %s>%s</option>', $code, $code == $country ? 'selected' : '', $name );
						}
					} ?>
                </select>
            </div>
		<?php } ?>

		<?php if ( $genre_filter ) { ?>
            <div class="wp-radio-select-wrap genre-select">
                <select name="genre" class="wp-radio-select">
                    <option value=""><?php _e( 'Select genre', 'wp-radio' ); ?></option>
					<?php
					if ( ! empty( $genres ) ) {
						foreach ( $genres as $code => $name ) {
							printf( '<option value="%s" %s>%s</option>', $code, $code == $genre ? 'selected' : '', $name );
						}
					} ?>
                </select>
            </div>
		<?php } ?>

        <button type="submit" class="wp-radio-search-form-submit">
            <i class="dashicons dashicons-search"></i>
            <span> <?php _e( 'Search', 'wp-radio' ); ?> </span>
        </button>

    </div>

</div>
