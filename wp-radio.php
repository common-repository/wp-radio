<?php

/**
 * Plugin Name: WP Radio
 * Plugin URI:  https://wpmilitary.com/wp-radio
 * Description: Worldwide online radio stations directory for WordPress.
 * Version:     3.1.9
 * Author:      WP Military
 * Author URI:  https://wpmilitary.com/
 * Text Domain: wp-radio
 * Domain Path: /languages/
 *
 */
// don't call the file directly
if ( !defined( 'ABSPATH' ) ) {
    wp_die( __( 'You can\'t access this page', 'wp-radio' ) );
}
// check min-wp version

if ( !version_compare( get_bloginfo( 'version' ), '5.0', '>=' ) ) {
    $notice = sprintf( '%1$s requires WordPress version %2$s or greater. Please update your WordPress to the latest version.', '<strong>WP Radio Directory</strong>', '<strong>5.0</strong>' );
    add_action( 'admin_notices', function () use( $notice ) {
        ?>
		<div class="notice is-dismissible notice-error">
			<p><?php 
        echo  $notice ;
        ?></p>
		</div>
	<?php 
    } );
    add_action( 'after_plugin_row_wp-radio/plugin.php', function () use( $notice ) {
        ?>
		<tr class="plugin-update-tr active">
			<td class="plugin-update colspanchange" colspan="4">
				<div class="update-message notice inline notice-error notice-alt">
					<p><?php 
        echo  $notice ;
        ?></p>
				</div>
			</td>
		</tr>
	<?php 
    } );
} else {
    
    if ( function_exists( 'wr_fs' ) ) {
        wr_fs()->set_basename( false, __FILE__ );
    } else {
        // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
        
        if ( !function_exists( 'wr_fs' ) ) {
            // Create a helper function for easy SDK access.
            function wr_fs()
            {
                global  $wr_fs ;
                
                if ( !isset( $wr_fs ) ) {
                    // Activate multisite network integration.
                    if ( !defined( 'WP_FS__PRODUCT_4227_MULTISITE' ) ) {
                        define( 'WP_FS__PRODUCT_4227_MULTISITE', true );
                    }
                    // Include Freemius SDK.
                    require_once dirname( __FILE__ ) . '/freemius/start.php';
                    $wr_fs = fs_dynamic_init( array(
                        'id'             => '4227',
                        'slug'           => 'wp-radio',
                        'type'           => 'plugin',
                        'public_key'     => 'pk_6acab182f004d200ec631d063d6c4',
                        'is_premium'     => false,
                        'premium_suffix' => 'PRO',
                        'has_addons'     => true,
                        'has_paid_plans' => true,
                        'menu'           => array(
                        'slug'       => 'edit.php?post_type=wp_radio',
                        'first-path' => 'edit.php?post_type=wp_radio&page=wp-radio-getting-started',
                        'contact'    => false,
                        'support'    => false,
                    ),
                        'is_live'        => true,
                    ) );
                }
                
                return $wr_fs;
            }
            
            // Init Freemius.
            wr_fs();
        }
        
        // ... Your plugin's main file logic ...
        /** define constants */
        define( 'WP_RADIO_VERSION', '3.1.9' );
        define( 'WP_RADIO_FILE', __FILE__ );
        define( 'WP_RADIO_PATH', dirname( WP_RADIO_FILE ) );
        define( 'WP_RADIO_INCLUDES', WP_RADIO_PATH . '/includes' );
        define( 'WP_RADIO_URL', plugins_url( '', WP_RADIO_FILE ) );
        define( 'WP_RADIO_ASSETS', WP_RADIO_URL . '/assets' );
        define( 'WP_RADIO_TEMPLATES', WP_RADIO_PATH . '/templates' );
        //Include the base plugin file.
        include_once WP_RADIO_INCLUDES . '/class-wp-radio.php';
        // Signal that SDK was initiated.
        do_action( 'wr_fs_loaded' );
    }

}
