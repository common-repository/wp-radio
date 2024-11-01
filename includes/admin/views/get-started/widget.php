<?php
$is_pro = wr_fs()->can_use_premium_code__premium_only();
?>

<h3 class="tab-content-title"><?php esc_html_e('Sidebar Widgets', 'wp-radio'); ?></h3>

<p><?php esc_html_e('The plugin provides two widgets for displaying the radio player and another one is for displaying the country list.', 'wp-radio'); ?></p>

<div class="tab-content-section">

    <h4 class="tab-content-section-title"><?php esc_html_e('Display The Radio Player in sidebar by widget ', 'wp-radio'); ?><?php echo ! $is_pro
			? '<span class="badge">PRO</span>' : ''; ?></h4>

    <p><?php _e('For displaying the Radio Player by the widget, you have to navigate to <code>Appearance > Widgets</code>.
        Then select the Radio Player widget and drag to the sidebar where you want to display the radio player.', 'wp-radio'); ?></p>

    <img src="<?php echo WP_RADIO_ASSETS . '/images/get-started/add-radio-player-widget.png' ?>" alt="<?php esc_html_e('Add Radio Player Widget', 'wp-radio'); ?>">
    <span class="img-caption"><?php _e('Radio player sidebar widget', 'wp-radio'); ?></span>


    <p><?php esc_html_e('After selecting the Radio Player, you have to enter the widget title and select the station that will be played by the player.
        You need to select the station by searching in the station field.', 'wp-radio'); ?>
    </p>

    <img src="<?php echo WP_RADIO_ASSETS . '/images/get-started/radio-player-widget.png' ?>" alt="<?php esc_html_e('Radio Player Widget', 'wp-radio'); ?>">
    <span class="img-caption"><?php _e('Add radio player sidebar widget', 'wp-radio'); ?></span>

    <p><?php esc_html_e('Then the output of the radio player widget will be like below.', 'wp-radio'); ?></p>

    <img src="<?php echo WP_RADIO_ASSETS . '/images/get-started/radio-player-widget-output.png' ?>" alt="<?php esc_html_e('Radio Player Widget', 'wp-radio'); ?>">
    <span class="img-caption"><?php _e('Radio player sidebar widget output', 'wp-radio'); ?></span>


</div>

<div class="tab-content-section">

    <h4 class="tab-content-section-title"><?php esc_html_e('Display the radio country list in sidebar by widget', 'wp-radio'); ?> <?php echo ! $is_pro
			? '<span class="badge">PRO</span>' : ''; ?></h4>

    <p><?php _e('For displaying the radio countries in a list by the widget, you have to navigate to <code>Appearance > Widgets</code>.
        Then select the <b>Radio Country List</b> widget and drag to the sidebar where you want to display the country list.', 'wp-radio'); ?></p>

    <img src="<?php echo WP_RADIO_ASSETS . '/images/get-started/country-list-widget.png' ?>" alt="<?php esc_html_e('Country List Widget', 'wp-radio'); ?>">
    <span class="img-caption"><?php _e('Country list sidebar widget', 'wp-radio'); ?></span>

    <p><?php esc_html_e('You just need to enter the widget tile. The widget will render the country list in the frontend.', 'wp-radio'); ?></p>

    <p><?php esc_html_e('The output of the country list widget will be like below.', 'wp-radio'); ?></p>
    <img src="<?php echo WP_RADIO_ASSETS . '/images/get-started/country-list-widget-output.png' ?>" alt="<?php esc_html_e('Country List Widget', 'wp-radio'); ?>">
    <span class="img-caption"><?php _e('Country list sidebar widget output', 'wp-radio'); ?></span>

</div>

