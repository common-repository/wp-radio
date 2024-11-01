<div class="notice-text">
    <img src="<?php echo WP_RADIO_ASSETS . '/images/addons/wp-radio-proxy-player-icon.png'; ?>"
         alt="<?php esc_html_e( 'WP Radio Proxy Player Addon', 'wp-radio' ); ?>">

    <p><?php esc_html_e( 'Hi there, do you know that most of the radio stations are HTTP (Unsecured) that couldn\'t be played on HTTPS (Secured) website because of browser mixed-content restrictions.', 'wp-radio' ); ?>
        <a href="<?php echo wr_fs()->get_addons_url(); ?>"><?php esc_html_e( 'WP Radio Proxy Player', 'wp-radio' ); ?></a> <?php esc_html_e( 'addon is now available for playing the HTTP radio stream links on HTTPS website.', 'wp-radio' ); ?>
        <br>
        <br>
        <?php esc_html_e('WP Radio Proxy Player also fixes the station metadata (song title) not showing issue.', 'wp-radio'); ?>
    </p>
</div>

<div class="proxy-notice-actions">
    <a href="<?php echo wr_fs()->get_addons_url(); ?>" class="button-primary"
       style="margin-right: 10px;"><?php _e( 'Get Now', 'wp-radio' ); ?></a>
    <a href="#" class="hide_notice button-link-delete"><?php _e( 'Never show this', 'wp-radio' ); ?></a>
</div>