<div class="notice-text">
    <img src="<?php echo WP_RADIO_ASSETS . '/images/wp-radio-icon.png' ?>" alt="WP Radio">
    <p>
	    <?php _e('Hi there, it seems like <strong>WP Radio</strong> is bringing you some value, and that is pretty awesome! Can you please show us some love and rate WP Radio on WordPress?
        <br> It will take two minutes of your time, and will really help us spread the world.', 'wp-radio'); ?>
    </p>
</div>

<div class="notice-actions">
    <a class="hide_notice button-primary" data-value="hide_notice" href="https://wordpress.org/support/plugin/wp-radio/reviews/?filter=5#new-post" target="_blank"><?php _e( 'I\'d love to help :)',
			'wp-radio' ); ?></a>
    <a href="#" class="remind_later button-link-delete"><?php _e( 'Not this time', 'wp-radio' ); ?></a>
    <a href="#" class="hide_notice" data-value="hide_notice"><?php _e( 'I\'ve already rated you', 'wp-radio' ); ?></a>
</div>

<div class="notice-overlay-wrap">
    <div class="notice-overlay">
        <h4><?php _e( 'Would you like us to remind you about this later?', 'wp-radio' ); ?></h4>

        <div class="notice-overlay-actions">
            <a href="#" data-value="3"><?php _e( 'Remind me in 3 days', 'wp-radio' ); ?></a>
            <a href="#" data-value="10"><?php _e( 'Remind me in 10 days', 'wp-radio' ); ?></a>
            <a href="#" data-value="hide_notice"><?php _e( 'Don\'t remind me about this', 'wp-radio' ); ?></a>
        </div>

        <button type="button" class="close-notice">&times;</button>
    </div>
</div>
