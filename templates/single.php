<?php

defined( 'ABSPATH' ) || exit;
$post_id = get_the_ID();
$url = get_the_permalink( $post_id );
$country_terms = get_terms( [
    'taxonomy' => 'radio_country',
    'parent'   => 0,
] );
$genre_terms = get_terms( [
    'taxonomy' => 'radio_genre',
    'parent'   => 0,
] );
$country_list = [];
foreach ( $country_terms as $item ) {
    $country_list[$item->slug] = wp_radio_escape_quote( $item->name );
}
$genre_list = [];
foreach ( $genre_terms as $item ) {
    $genre_list[$item->slug] = wp_radio_escape_quote( $item->name );
}
$title = wp_radio_escape_quote( get_the_title( $post_id ) );
$content = wp_radio_escape_quote( get_post_field( 'post_content', $post_id ) );
$thumbnail = wp_radio_get_meta( $post_id, 'logo', WP_RADIO_ASSETS . '/images/placeholder.jpg' );
$slogan = wp_radio_get_meta( $post_id, 'slogan' );
$stream = apply_filters( 'wp_radio/stream_url', wp_radio_get_meta( $post_id, 'stream_url' ), $post_id );
$website = wp_radio_get_meta( $post_id, 'website' );
$facebook = wp_radio_get_meta( $post_id, 'facebook' );
$twitter = wp_radio_get_meta( $post_id, 'twitter' );
$wikipedia = wp_radio_get_meta( $post_id, 'wikipedia' );
$email = wp_radio_get_meta( $post_id, 'email' );
$phone = wp_radio_get_meta( $post_id, 'phone' );
$address = wp_radio_get_meta( $post_id, 'address' );
$contacts = array_filter( [
    '<i class="dashicons dashicons-location"></i> Address'      => ( !empty($address) ? sprintf( '<span>%s</span>', $address ) : '' ),
    '<i class="dashicons dashicons-email"></i> Email'           => ( !empty($email) ? sprintf( '<a href="mailto:%1$s" target="_blank">%1$s</a>', $email ) : '' ),
    '<i class="dashicons dashicons-phone"></i> Phone'           => ( !empty($phone) ? sprintf( '<a href="tel:%1$s" target="_blank">%1$s</a>', $phone ) : '' ),
    '<i class="dashicons dashicons-admin-links"></i> Website'   => ( !empty($website) ? sprintf( '<a href="%1$s" target="_blank">%1$s</a>', $website ) : '' ),
    '<i class="dashicons dashicons-facebook"></i> Facebook'     => ( !empty($facebook) ? sprintf( '<a href="%1$s" target="_blank">%1$s</a>', $facebook ) : '' ),
    '<i class="dashicons dashicons-twitter"></i> Twitter'       => ( !empty($twitter) ? sprintf( '<a href="%1$s" target="_blank">%1$s</a>', $twitter ) : '' ),
    '<i class="dashicons dashicons-admin-links"></i> Wikipedia' => ( !empty($wikipedia) ? sprintf( '<a href="%1$s" target="_blank">%1$s</a>', $wikipedia ) : '' ),
] );
?>

<div id="wp-radio-single-container">

    <div class="wp-radio-single" itemScope itemType="https://schema.org/RadioStation">

        <!--Breadcrumbs-->
		<?php 
wp_radio_get_template( 'single/breadcrumbs' );
?>

        <div class="wp-radio-page-title">
            <div class="wp-radio-thumbnail">
                <img src=<?php 
echo  $thumbnail ;
?> alt="<?php 
echo  $title ;
?>" title="<?php 
echo  $title ;
?>"
                     itemProp="image"/>
            </div>

            <div class="wp-radio-player-status status-<?php 
echo  $post_id ;
?>">
                <span class="status-text-offline"><?php 
esc_html_e( 'OFFLINE', 'wp-radio' );
?></span>
                <span class="status-text-live"><?php 
esc_html_e( 'LIVE', 'wp-radio' );
?></span>

                <span class="status-dot"></span>
            </div>

            <h1 class="entry-title" itemProp="headline"><?php 
echo  $title ;
?></h1>

			<?php 
if ( !empty($slogan) ) {
    printf( '<span class="station-slogan">%s</span>', $slogan );
}
?>

            <div class="wp-radio-player-song-title" data-id="<?php 
echo  $post_id ;
?>"></div>
        </div>

        <div class="wp-radio-header">

            <div class="wp-radio-details">

				<?php 
wp_radio_get_template( 'listing/locations', [
    'locations' => wp_radio_get_location( $post_id ),
] );
?>
				<?php 
wp_radio_get_template( 'listing/genres', [
    'genres' => wp_radio_get_genres( $post_id ),
] );
?>
            </div>

            <!-- Play Btn-->
            <div class="play-btn-wrap">
				<?php 
do_action( 'wp_radio/play_btn/before', $post_id );
?>

                <button type="button" class="play-btn"
                        data-id="<?php 
echo  $post_id ;
?>"
                        onclick='wpRadioHooks.doAction("playPause",this, <?php 
echo  json_encode( [
    'id'        => $post_id,
    'title'     => $title,
    'thumbnail' => $thumbnail,
    'link'      => get_permalink( $post_id ),
    'stream'    => $stream,
] ) ;
?>)'
                >
                    <div class="wp-radio-spinner">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                    <i class="dashicons dashicons-controls-play"></i>
                    <i class="dashicons dashicons-controls-pause"></i>
                </button>
            </div>

        </div>

        <div class="wp-radio-body">
            <p class="description" itemProp="description"><?php 
echo  $content ;
?></p>
        </div>

        <div class="wp-radio-footer"><?php 
do_action( 'wp_radio/single/footer', $post_id );
?></div>

		<?php 
do_action( 'wp_radio/single/contacts/before' );
?>

        <!-- Contacts -->
		<?php 

if ( !empty($contacts = array_filter( $contacts )) ) {
    ?>

            <div class="station-contacts">
                <h3 class="contacts-title"><?php 
    esc_html_e( 'Contacts', 'wp-radio' );
    ?></h3>

				<?php 
    foreach ( $contacts as $label => $value ) {
        $value = str_replace( [ 'Address: ', 'Email: ', 'Phone: ' ], [ '', '', '' ], $value );
        printf( '<div class="contact-item"><span class="contact-item-label">%1$s </span> %2$s</div>', $label, trim( $value ) );
    }
    ?>
            </div>

		<?php 
}

do_action( 'wp_radio/single/playlist/after', $post_id );
?>

        <!-- Related Stations -->
		<?php 
$related_stations = wp_radio_get_related_stations( $post_id );

if ( !empty($related_stations) ) {
    $is_grid = 'grid' == wp_radio_get_settings( 'listing_view', 'list' );
    ?>
        <div class="wp-radio-related">
            <h3><?php 
    _e( 'You also may like', 'wp-radio' );
    ?></h3>
            <div class="wp-radio-listings">
                <div class="wp-radio-listing-wrap <?php 
    echo  ( $is_grid ? 'wp-radio-listing-grid' : '' ) ;
    ?>">
					<?php 
    $col = wp_radio_get_settings( 'grid_column', 4 );
    $show_genres = !$is_grid && wp_radio_get_settings( 'listing_genre', true );
    $show_content = !$is_grid && wp_radio_get_settings( 'listing_content', false );
    foreach ( $related_stations as $station ) {
        wp_radio_get_template( 'listing/loop', [
            'station'      => $station,
            'col'          => $col,
            'is_grid'      => $is_grid,
            'show_genres'  => $show_genres,
            'show_content' => $show_content,
        ] );
    }
    ?>
                </div>
            </div>
        </div>
    </div>
	<?php 
}

?>
</div>