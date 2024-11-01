<div class="notice-text">
    <img src="<?php echo WP_RADIO_ASSETS . '/images/addons/wp-radio-ads-player-icon.png'; ?>"
         alt="<?php esc_html_e( 'WP Radio Ads Player Addon', 'wp-radio' ); ?>">

    <p><a href="<?php echo wr_fs()->get_addons_url(); ?>"><?php _e( 'Hi there, WP Radio Ads Player', 'wp-radio' ) ?></a>
	    <?php _e('is available now to monetize your website by playing custom advertisements and banner ads.
        <br> It let you play custom mic drops, stringer, and audio advertisements at the start of radio station play.', 'wp-radio'); ?>

    </p>
</div>

<div class="ads-player-notice-actions">
    <a href="<?php  echo wr_fs()->get_addons_url(); ?>" class="button-primary"
       style="margin-right: 10px;"><?php esc_html_e( 'Get Now', 'wp-radio' ); ?></a>
    <a href="#" class="hide_notice button-link-delete"><?php esc_html_e( 'Never show this', 'wp-radio' ); ?></a>
</div>