<?php
$is_pro = wr_fs()->can_use_premium_code__premium_only();
?>

<h3 class="tab-content-title"><?php _e( 'Shortcodes', 'wp-radio' ) ?></h3>

<p><?php esc_html_e( 'This plugin provides the following shortcodes:', 'wp-radio' ); ?></p>

<div class="tab-content-section">
    <h4 class="tab-content-section-title"><?php esc_html_e( 'Radio Stations Listing Shortcode - ', 'wp-radio' ); ?>
        <code>[wp_radio_listing]</code>
        <i class="dashicons dashicons-plus-alt"></i>
    </h4>

    <p><code>[wp_radio_listing]</code> - <?php _e( 'For displaying the radio stations listing use
        <strong>[wp_radio_listing]</strong> shortcode. This shortcode supports two filter attributes, those are <code>country</code>
        & <code>genre</code>. Both are optional.
        <br>
        <br>
        In the <b>country</b> attribute, you can pass comma separated country codes to filter the listing.
        <br>
        <br>
        In the <b>genre</b> attribute, you can pass comma separated genre name (slug) to filter the listing.
        <br>
        <br>
        <b>Example: </b> <code>[wp_radio_listing country="us, ru, in, bd" genre="rock,news,rock"]</code>', 'wp-radio' ); ?>
    </p>

</div>

<div class="tab-content-section">
    <h4 class="tab-content-section-title"><?php esc_html_e( 'Radio Station Shortcode', 'wp-radio' ); ?> - <code>[wp_radio_station]</code>
        <i class="dashicons dashicons-plus-alt"></i></h4>
    <p><code>[wp_radio_station]</code> – <?php _e( 'For displaying the radio station use <b>[wp_radio_station]</b> shortcode.
        <br>
        <br>
        This Shortcode supports an required <code>id</code> attribute where you have to pass the id of a radio station.
        <br>
        <br>
        <b>Example:</b> <code>[wp_radio_id id="55"]</code>', 'wp-radio' ); ?>
    </p>
</div>

<div class="tab-content-section">
    <h4 class="tab-content-section-title"><?php esc_html_e( 'Radio Player Shortcode', 'wp-radio' ); ?> - <code>[wp_radio_player]</code>
        <i
                class="dashicons dashicons-plus-alt"></i></h4>

    <p><code>[wp_radio_player]</code> – <?php _e( 'For displaying the radio player use <b>[wp_radio_player]</b> shortcode.
        <br>
        <br>
        This Shortcode supports an optional <code>id</code> attribute where you have to pass the id of a radio station
        as
        default station of the player.
        <br>
        <br>
        <b>Example:</b> <code>[wp_radio_player id="11"]</code>', 'wp-radio' ); ?>
    </p>

</div>

<div class="tab-content-section">
    <h4 class="tab-content-section-title"><?php esc_html_e( 'Featured Stations Listing Shortcode', 'wp-radio' ); ?> -
        <code>[wp_radio_featured]</code> <?php echo ! $is_pro
			? '<span class="badge">PRO</span>' : ''; ?> <i class="dashicons dashicons-plus-alt"></i></h4>
    <p>
        <code>[wp_radio_featured]</code> – <?php _e( 'For displaying the listing of the featured stations, use <b>[wp_radio_featured]</b>shortcode.
        <br>
        <br>
        This Shortcode supports 2 optional filter attributes. Those are <code>count</code> & <code>country</code>
        attributes.
        <br>
        <br>
        Use <b>count</b> attribute to limit how many Radio Stations you want to show on the listing. You can pass any
        valid number to this attribute.
        <br>
        <br>
        Use <b>country</b> attribute to filter the listing by country. You can pass comma separated country codes to
        this
        attribute.
        <br>
        <br>
        <b>Example:</b> <code>[wp_radio_featured count="10" country="us"]</code>', 'wp-radio' ); ?>
    </p>

</div>

<div class="tab-content-section">
    <h4 class="tab-content-section-title"><?php esc_html_e( 'Trending Stations Listing Shortcode', 'wp-radio' ); ?> -
        <code>[wp_radio_trending]</code> <?php echo ! $is_pro
			? '<span class="badge">PRO</span>' : ''; ?>
        <i class="dashicons dashicons-plus-alt"></i></h4>
    <p><code>[wp_radio_trending]</code> – <?php _e( 'For displaying the listing of the trending stations, use <b>[wp_radio_trending]</b>
        shortcode.
        <br>
        <br>
        This shortcode supports the same attribute of the <code>[wp_radio_featured]</code> shortcode.
        <br>
        <br>
        <b>Example:</b> <code>[wp_radio_trending count="10" country="us"]</code>.', 'wp-radio' ); ?>
    </p>
</div>

<div class="tab-content-section">
    <h4 class="tab-content-section-title"><?php esc_html_e( 'Country List Shortcode', 'wp-radio' ); ?> -
        <code>[wp_radio_country_list]</code> <?php echo ! $is_pro
			? '<span class="badge">PRO</span>' : ''; ?> <i class="dashicons dashicons-plus-alt"></i></h4>
    <p><code>[wp_radio_country_list]</code> – <?php _e( 'Use this short code for displaying the all country list of the radio
        stations.
        <br>
        <br>
        This shortcode has no attribute attribute.
        <br>
        <br>
        <b>Example:</b> <code>[wp_radio_country_list]</code>', 'wp-radio' ); ?>
    </p>
</div>

<div class="tab-content-section">
    <h4 class="tab-content-section-title"><?php esc_html_e( 'User Favorites Shortcode', 'wp-radio' ); ?> -
        <code>[wp_radio_user_favorites]</code> <?php echo ! defined( 'WR_USER_FRONTEND_VERSION' )
			? '<span class="badge">User Frontend Addon</span>' : ''; ?> <i class="dashicons dashicons-plus-alt"></i>
    </h4>
    <p><code>[wp_radio_user_favorites]</code> – <?php _e( 'For displaying the list of all the favorites stations of a user, use <b>[wp_radio_user_favorites]</b>
        shortcode.
        <br>
        <br>
        This shortcode supports no attributes.
        <br>
        <br>
        You must activate the WP Radio User Frontend Addon to use this shortcode.', 'wp-radio' ); ?>
    </p>
</div>
