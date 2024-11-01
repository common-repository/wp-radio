<h3 class="tab-content-title"><?php _e( 'Plugin Setup Guide', 'wp-radio' ) ?></h3>

<p><?php esc_html_e( 'Setting up WP Radio is very easy. This plugin setup itself on the installation. Here are few things that you should consider:', 'wp-radio' ); ?></p>

<div class="tab-content-section">
    <p>
		<?php _e( 'After activating this plugin it creates a page named <b>Radio Stations</b>. The page contains
        <code>[wp_radio_listing]</code> shortcode.
        The <b>Radio Stations</b> page is the default station archive page, where all the stations will be listed.', 'wp-radio' ); ?>
    </p>

    <img src="<?php echo WP_RADIO_ASSETS . '/images/get-started/radio-stations-page.png' ?>"
         alt="<?php _e( 'Radio Stations Page', 'wp-radio' ); ?>">
    <span class="img-caption"><?php _e( 'Automatically created stations archive page.', 'wp-radio' ); ?></span>

    <p><?php _e( 'On This Page, Visitors Will See Their Country’s Radio Stations, If the country exists on your website.
        Otherwise the visitors will see all the other stations, those you have added or imported.', 'wp-radio' ); ?></p>
</div>

<div class="tab-content-section">
    <p><?php _e( 'The next step is to add new or import Radio Stations.', 'wp-radio' ); ?></p>
    <img src="<?php echo WP_RADIO_ASSETS . '/images/get-started/add-import-stations.png' ?>" alt="Add Import Stations">
    <span class="img-caption"><?php _e( 'Add new station & import stations menu', 'wp-radio' ); ?></span>

    <p><?php _e( 'To Add new station, you need to go <code>Radio Stations > Add New Station</code> page.', 'wp-radio' ); ?> </p>
    <p><?php _e( 'To import the Radio Stations, you need to go <code>Radio Stations > Import Stations</code> page on the Admin Dashboard.', 'wp-radio' ); ?></p>
</div>