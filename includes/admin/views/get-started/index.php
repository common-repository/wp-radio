<div class="wrap wp-radio-get-started">

    <div class="get-started-header">
        <h2>
            <img width="20" height="20" src="<?php echo WP_RADIO_ASSETS . '/images/wp-radio-icon.png' ?>"/>
			<?php _e( 'WP Radio - Getting Started', 'wp-radio' ); ?></h2>
    </div>

    <div class="wp-radio-tab-wrap">

        <div class="tab-links">
            <a href="javascript:;" data-target="new" class="tab-link active">
                <i class="dashicons dashicons-info-outline"></i>
				<?php _e( 'What\'s New?', 'wp-radio' ); ?>
            </a>

            <a href="javascript:;" data-target="setup" class="tab-link">
                <i class="dashicons dashicons-admin-tools"></i>
				<?php _e( 'Plugin Setup', 'wp-radio' ); ?></a>

            <a href="javascript:;" data-target="import" class="tab-link">
                <i class="dashicons dashicons-database-import"></i>
				<?php _e( 'Import Stations', 'wp-radio' ); ?></a>

            <a href="javascript:;" data-target="shortcodes" class="tab-link">
                <i class="dashicons dashicons-shortcode"></i>
				<?php _e( 'Shortcodes', 'wp-radio' ); ?></a>

            <a href="javascript:;" data-target="gutenberg" class="tab-link">
                <i class="dashicons dashicons-table-row-after"></i>
				<?php _e( 'Gutenberg Blocks', 'wp-radio' ); ?></a>

            <a href="javascript:;" data-target="elementor" class="tab-link">
                <i class="dashicons dashicons-align-pull-left"></i>
				<?php _e( 'Elementor Widgets', 'wp-radio' ); ?></a>

            <a href="javascript:;" data-target="widget" class="tab-link">
                <i class="dashicons dashicons-align-wide"></i>
				<?php _e( 'Sidebar Widgets', 'wp-radio' ); ?></a>

            <a href="javascript:;" data-target="wp_radio_ads_player" class="tab-link">
                <i class="dashicons dashicons-money-alt"></i>
				<?php _e( 'Ads Player', 'wp-radio' ); ?>
                <span class="badge"><?php esc_html_e( 'Addon', 'wp-radio' ); ?></span>
            </a>

            <a href="javascript:;" data-target="proxy_player" class="tab-link">
                <i class="dashicons dashicons-controls-play"></i>
				<?php _e( 'Proxy Player', 'wp-radio' ); ?>
                <span class="badge"><?php esc_html_e( 'Addon', 'wp-radio' ); ?></span>
            </a>

            <a href="javascript:;" data-target="user_frontend" class="tab-link">
                <i class="dashicons dashicons-buddicons-buddypress-logo"></i>
				<?php _e( 'User Frontend', 'wp-radio' ); ?>
                <span class="badge"><?php esc_html_e( 'Addon', 'wp-radio' ); ?></span>
            </a>

            <a href="javascript:;" data-target="image_import" class="tab-link">
                <i class="dashicons dashicons-format-gallery"></i>
				<?php _e( 'Image Import', 'wp-radio' ); ?>
                <span class="badge"><?php esc_html_e( 'Addon', 'wp-radio' ); ?></span>
            </a>

            <a href="#" data-target="podcast_box" class="tab-link">
                <i class="dashicons dashicons-microphone"></i>
				<?php _e( 'Podcast Box', 'wp-radio' ); ?>
                <span class="badge"><?php _e( 'Plugin', 'wp-radio' ); ?></span>
            </a>

            <a href="javascript:;" data-target="faq" class="tab-link">
                <i class="dashicons dashicons-editor-help"></i>
				<?php esc_html_e( 'FAQ', 'wp-radio' ); ?></a>
        </div>

        <div id="new" class="tab-content active">
			<?php include_once WP_RADIO_INCLUDES . '/admin/views/get-started/new.php'; ?>
        </div>

        <div id="setup" class="tab-content">
			<?php include_once WP_RADIO_INCLUDES . '/admin/views/get-started/setup.php'; ?>
        </div>

        <div id="import" class="tab-content">
			<?php include_once WP_RADIO_INCLUDES . '/admin/views/get-started/import.php'; ?>
        </div>

        <div id="shortcodes" class="tab-content">
			<?php include_once WP_RADIO_INCLUDES . '/admin/views/get-started/shortcodes.php'; ?>
        </div>

        <div id="gutenberg" class="tab-content">
			<?php include_once WP_RADIO_INCLUDES . '/admin/views/get-started/gutenberg.php'; ?>
        </div>

        <div id="elementor" class="tab-content">
			<?php include_once WP_RADIO_INCLUDES . '/admin/views/get-started/elementor.php'; ?>
        </div>

        <div id="widget" class="tab-content">
			<?php include_once WP_RADIO_INCLUDES . '/admin/views/get-started/widget.php'; ?>
        </div>

        <div id="wp_radio_ads_player" class="tab-content">
			<?php include WP_RADIO_INCLUDES . '/admin/views/get-started/ads-player.php'; ?>
        </div>

        <div id="proxy_player" class="tab-content">
			<?php include WP_RADIO_INCLUDES . '/admin/views/get-started/proxy-player.php'; ?>
        </div>

        <div id="user_frontend" class="tab-content">
			<?php include WP_RADIO_INCLUDES . '/admin/views/get-started/user-frontend.php'; ?>
        </div>

        <div id="image_import" class="tab-content">
			<?php include WP_RADIO_INCLUDES . '/admin/views/get-started/image-import.php'; ?>
        </div>

        <div id="podcast_box" class="tab-content">
			<?php include WP_RADIO_INCLUDES . '/admin/views/get-started/podcast-box.php'; ?>
        </div>

        <div id="faq" class="tab-content">
			<?php include_once WP_RADIO_INCLUDES . '/admin/views/get-started/faq.php'; ?>
        </div>

    </div>

</div>

<?php if ( isset( $_GET['tab'] ) ) { ?>
    <script>
        ;(function ($) {
            $(document).ready(function () {
                localStorage.setItem('wp_radio_get_started_tab', '<?php echo sanitize_text_field( $_GET['tab'] ); ?>');
            });
        })(jQuery);
    </script>
<?php } ?>