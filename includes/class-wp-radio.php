<?php

defined( 'ABSPATH' ) || exit;
final class WP_Radio
{
    /**
     * WP_Radio version.
     *
     * @var string
     */
    public  $version = WP_RADIO_VERSION ;
    public  $name = 'WP Radio' ;
    /**
     * Minimum PHP version required
     *
     * @var string
     */
    private  $min_php = '5.6.0' ;
    /**
     * The single instance of the class.
     *
     * @var WP_Radio
     * @since 1.0.0
     */
    protected static  $instance = null ;
    public function __construct()
    {
        $this->check_environment();
        $this->includes();
        $this->init_hooks();
        //Register activation hook of the plugin
        register_activation_hook( WP_RADIO_FILE, array( $this, 'activation' ) );
        register_deactivation_hook( WP_RADIO_FILE, array( $this, 'deactivation' ) );
        do_action( 'wp_radio_loaded' );
    }
    
    public function activation()
    {
        include_once WP_RADIO_INCLUDES . '/class-install.php';
    }
    
    public function deactivation()
    {
        // clear all statistics email reporting schedule
        wp_clear_scheduled_hook( 'wp_radio_statistics_monthly_report' );
        wp_clear_scheduled_hook( 'wp_radio_statistics_weekly_report' );
        wp_clear_scheduled_hook( 'wp_radio_statistics_daily_report' );
    }
    
    /**
     * Ensure theme and server variable compatibility
     */
    public function check_environment()
    {
        
        if ( version_compare( PHP_VERSION, $this->min_php, '<=' ) ) {
            deactivate_plugins( plugin_basename( WP_RADIO_FILE ) );
            wp_die( "Unsupported PHP version. Minimum required PHP Version:{$this->min_php}" );
        }
    
    }
    
    /**
     * Include required core files used in admin and on the frontend.
     */
    public function includes()
    {
        //core includes
        include_once WP_RADIO_INCLUDES . '/functions.php';
        include_once WP_RADIO_INCLUDES . '/class-hooks.php';
        include_once WP_RADIO_INCLUDES . '/class-shortcode.php';
        include_once WP_RADIO_INCLUDES . '/class-ajax.php';
        include_once WP_RADIO_INCLUDES . '/class-cpt.php';
        include_once WP_RADIO_INCLUDES . '/class-enqueue.php';
        include_once WP_RADIO_INCLUDES . '/template-functions.php';
        /** radio station gutenberg block */
        include_once WP_RADIO_PATH . '/block/class-block.php';
        // Elementor
        include_once WP_RADIO_INCLUDES . '/elementor/class-elementor.php';
        //admin includes
        
        if ( is_admin() ) {
            include_once WP_RADIO_INCLUDES . '/admin/class-admin-notice.php';
            include_once WP_RADIO_INCLUDES . '/admin/class-chart-widget.php';
            include_once WP_RADIO_INCLUDES . '/admin/class-metabox.php';
            include_once WP_RADIO_INCLUDES . '/admin/class-admin-ajax.php';
            include_once WP_RADIO_INCLUDES . '/admin/class-admin.php';
            include_once WP_RADIO_INCLUDES . '/admin/class-settings.php';
        }
    
    }
    
    /**
     * Hook into actions and filters.
     *
     * @since 2.3
     */
    private function init_hooks()
    {
        add_action( 'admin_notices', [ $this, 'print_notices' ], 15 );
        add_action( 'wp_radio_notices', [ $this, 'print_notices' ], 15 );
        //Localize our plugin
        add_action( 'init', [ $this, 'localization_setup' ] );
    }
    
    /**
     * Initialize plugin for localization
     *
     * @return void
     * @since 1.0.0
     *
     */
    public function localization_setup()
    {
        load_plugin_textdomain( 'wp-radio', false, dirname( plugin_basename( WP_RADIO_FILE ) ) . '/languages/' );
    }
    
    public function add_notice( $class, $message )
    {
        $notices = get_option( sanitize_key( 'wp_radio_notification' ), [] );
        
        if ( is_string( $message ) && is_string( $class ) && !wp_list_filter( $notices, array(
            'message' => $message,
        ) ) ) {
            $notices[] = array(
                'message' => $message,
                'class'   => $class,
            );
            update_option( sanitize_key( 'wp_radio_notification' ), $notices );
        }
    
    }
    
    public function print_notices()
    {
        $notices = get_option( sanitize_key( 'wp_radio_notification' ), [] );
        foreach ( $notices as $notice ) {
            ?>
            <div class="notice notice-large is-dismissible wp-radio-admin-notice notice-<?php 
            echo  $notice['class'] ;
            ?>">
				<?php 
            echo  $notice['message'] ;
            ?>
            </div>
			<?php 
            update_option( sanitize_key( 'wp_radio_notification' ), [] );
        }
    }
    
    /**
     * Main WP_Radio Instance.
     *
     * Ensures only one instance of WP_Radio is loaded or can be loaded.
     *
     * @return WP_Radio - Main instance.
     * @since 1.0.0
     * @static
     */
    public static function instance()
    {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

}
if ( !function_exists( 'wp_radio' ) ) {
    function wp_radio()
    {
        return WP_Radio::instance();
    }

}
//fire off the plugin
wp_radio();