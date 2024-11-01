<h3 class="tab-content-title"><?php _e( 'General', 'wp-radio' ) ?></h3>

<div class="tab-content-section">
    <h4 class="tab-content-section-title"> <?php esc_html_e( '⚠️ Station Playing Issue', 'wp-radio' ); ?></h4>
    <p>
		<?php _e( 'Hi there, do you know that most of the radio stations are HTTP (Unsecured) that can\'t be played on HTTPS (Secured) website because of browser mixed-content restrictions.

        Modern browsers no longer accepts mixed requests.
        Please check this link:
        <a href="https://web.dev/what-is-mixed-content/" target="_blank">https://web.dev/what-is-mixed-content/</a>', 'wp-radio' ); ?>

    </p>

</div>

<div class="tab-content-section" style="text-align: center">
    <blockquote>
	    <?php _e('
        You have to use the <b>WP Radio Proxy Player</b> addon to remove the browser mixed-content restrictions.
        That means you can play HTTP radio stream link on HTTPS website by using a cors-proxy system by using the <b>WP
            Radio Proxy Player</b> Addon.', 'wp-radio'); ?>
    </blockquote>

    <br>
    <a href="<?php echo WP_RADIO_ADDONS; ?>" class="button-primary"><?php esc_html_e('GET WP Radio Proxy Player Addon', 'wp-radio'); ?></a>
</div>

<div class="tab-content-section">
    <blockquote><?php esc_html_e('It is important to note that, all the channels might not work for you all the time.
        Because there are some radio channels who stop streaming after a certain time of the day or the stream link has
        been changed.', 'wp-radio'); ?>
    </blockquote>
</div>