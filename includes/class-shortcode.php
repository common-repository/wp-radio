<?php

defined( 'ABSPATH' ) || exit;
if ( !class_exists( 'WP_Radio_Shortcode' ) ) {
    class WP_Radio_Shortcode
    {
        private static  $instance = null ;
        /* constructor */
        public function __construct()
        {
            add_shortcode( 'wp_radio_listing', array( $this, 'listing' ) );
            add_shortcode( 'wp_radio_station', array( $this, 'station' ) );
            add_shortcode( 'wp_radio_player', array( $this, 'player' ) );
            add_shortcode( 'wp_radio_search_form', array( $this, 'search_form' ) );
        }
        
        public function station( $atts )
        {
            $atts = shortcode_atts( array(
                'id'               => '',
                'show_genre'       => 'true',
                'show_description' => 'false',
            ), $atts );
            if ( 'wp_radio' != get_post_type( $atts['id'] ) ) {
                return sprintf( __( 'No station found for id (%s)!', 'wp-radio' ), $atts['id'] );
            }
            ob_start();
            $station = wp_radio_get_station_data( $atts['id'] );
            wp_radio_get_template( 'listing/loop', [
                'station'      => $station,
                'is_grid'      => false,
                'col'          => 1,
                'show_genres'  => filter_var( $atts['show_genre'], FILTER_VALIDATE_BOOLEAN ),
                'show_content' => filter_var( $atts['show_description'], FILTER_VALIDATE_BOOLEAN ),
            ] );
            return ob_get_clean();
        }
        
        public function listing( $atts )
        {
            $atts = shortcode_atts( array(
                'country' => '',
                'genre'   => '',
                'col'     => 4,
            ), $atts );
            ob_start();
            wp_radio_get_template( 'listing', [
                'shortcode_args' => $atts,
            ] );
            return ob_get_clean();
        }
        
        public function player( $atts )
        {
            $atts = shortcode_atts( array(
                'id'          => '',
                'player_type' => 'shortcode',
                'next_prev'   => 'false',
                'align'       => 'center',
            ), $atts );
            if ( 'wp_radio' != get_post_type( $atts['id'] ) ) {
                return sprintf( __( 'No station found for id (%s)!', 'wp-radio' ), $atts['id'] );
            }
            ob_start();
            wp_radio_get_template( 'player', $atts );
            return ob_get_clean();
        }
        
        public function featured( $atts )
        {
            $atts = shortcode_atts( array(
                'count'   => 30,
                'country' => '',
            ), $atts );
            ob_start();
            wp_radio_get_template( 'shortcode-featured', $atts );
            return ob_get_clean();
        }
        
        public function trending( $atts )
        {
            $atts = shortcode_atts( array(
                'count'   => 30,
                'country' => '',
            ), $atts );
            ob_start();
            wp_radio_get_template( 'shortcode-trending', $atts );
            return ob_get_clean();
        }
        
        public function country_list( $atts )
        {
            $atts = shortcode_atts( array(
                'shortcode' => true,
                'style'     => 'vertical',
                'columns'   => 3,
            ), $atts );
            ob_start();
            wp_radio_get_template( 'sidebar', $atts );
            return ob_get_clean();
        }
        
        public function search_form( $atts )
        {
            $atts = shortcode_atts( array(
                'country_filter' => 'true',
                'genre_filter'   => 'true',
            ), $atts );
            ob_start();
            $action_link = get_permalink( wp_radio_get_settings( 'stations_page' ) );
            ?>
            <form class="wp-radio-search-form-wrap" action="<?php 
            echo  $action_link ;
            ?>">
				<?php 
            wp_radio_get_template( 'listing/search', $atts );
            ?>
            </form>
			<?php 
            return ob_get_clean();
        }
        
        public static function instance()
        {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }
    
    }
}
WP_Radio_Shortcode::instance();