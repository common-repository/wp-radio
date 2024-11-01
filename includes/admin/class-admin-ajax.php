<?php

defined( 'ABSPATH' ) || exit;
if ( !class_exists( 'WP_Radio_Admin_Ajax' ) ) {
    class WP_Radio_Admin_Ajax
    {
        private static  $instance = null ;
        public function __construct()
        {
            add_action( 'wp_ajax_wp_radio_import_stations', array( $this, 'handle_import' ) );
            add_action( 'wp_ajax_wp_radio_display_notice', array( $this, 'handle_notice' ) );
            add_action( 'wp_ajax_wp_radio_rating_notice', array( $this, 'handle_rating_notice' ) );
            add_action( 'wp_ajax_wp_radio_get_states', array( $this, 'get_states' ) );
            add_action( 'wp_ajax_wp_radio_get_cities', array( $this, 'get_cities' ) );
            add_action( 'wp_ajax_wp_radio_get_chart', array( $this, 'get_chart' ) );
            //save settings
            add_action( 'wp_ajax_wp_radio_save_settings', array( $this, 'save_settings' ) );
            //remove country
            add_action( 'wp_ajax_wp_radio_remove_country', array( $this, 'remove_country' ) );
        }
        
        public function remove_country()
        {
            $country = sanitize_key( $_REQUEST['country'] );
            global  $wpdb ;
            $term_id = $wpdb->get_var( $wpdb->prepare( "SELECT term_id FROM {$wpdb->terms} WHERE slug = %s", $country ) );
            $post_ids = $wpdb->get_col( $wpdb->prepare( "SELECT object_id FROM {$wpdb->term_relationships} WHERE term_taxonomy_id = %d;", $term_id ) );
            
            if ( !empty($post_ids) ) {
                $placeholders = '';
                foreach ( $post_ids as $post_id ) {
                    $placeholders .= '%s,';
                }
                $prepared_placeholders = trim( $placeholders, ',' );
                $prepared_values = array_merge( array( 'wp_radio' ), $post_ids );
                // Delete posts + data.
                $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->posts} WHERE post_type = %s AND ID IN ({$prepared_placeholders});", $prepared_values ) );
                $wpdb->query( "DELETE meta FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL;" );
            }
            
            //delete child category
            $children = get_term_children( $term_id, 'radio_country' );
            if ( !empty($children) ) {
                foreach ( $children as $children_id ) {
                    wp_delete_term( $children_id, 'radio_country' );
                }
            }
            wp_delete_term( $term_id, 'radio_country' );
            // Delete orphan relationships.
            $wpdb->query( "DELETE tr FROM {$wpdb->term_relationships} tr LEFT JOIN {$wpdb->posts} posts ON posts.ID = tr.object_id WHERE posts.ID IS NULL;" );
            // Delete orphan terms.
            $wpdb->query( "DELETE t FROM {$wpdb->terms} t LEFT JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id WHERE tt.term_id IS NULL;" );
            // Delete orphan term meta.
            if ( !empty($wpdb->termmeta) ) {
                $wpdb->query( "DELETE tm FROM {$wpdb->termmeta} tm LEFT JOIN {$wpdb->term_taxonomy} tt ON tm.term_id = tt.term_id WHERE tt.term_id IS NULL;" );
            }
            // Clear any cached data that has been removed.
            wp_cache_flush();
            $option_key = ( !empty($_REQUEST['type']) ? wp_unslash( $_REQUEST['type'] ) : 'wp_radio_imported_countries' );
            $countries = (array) get_option( $option_key );
            if ( ($key = array_search( $country, $countries )) !== false ) {
                unset( $countries[$key] );
            }
            update_option( $option_key, $countries );
            wp_send_json_success();
        }
        
        public function save_settings()
        {
            $settings = $_POST['settings'];
            if ( !empty($settings['popup_ads']) ) {
                $settings['popup_ads'] = str_replace( [ "\\'", '\\"' ], [ "'", '"' ], $settings['popup_ads'] );
            }
            update_option( 'wp_radio_settings', $settings );
            wp_send_json_success();
        }
        
        public function get_chart()
        {
            $days = ( !empty($_REQUEST['days']) ? intval( $_REQUEST['days'] ) : 30 );
            if ( !class_exists( 'WP_Radio_Statistics' ) ) {
                include_once WP_RADIO_INCLUDES . '/class-statistics.php';
            }
            $args = [
                'start_date' => date( 'Y-m-d', strtotime( "-{$days} Days" ) ),
                'end_date'   => date( 'Y-m-d', strtotime( 'tomorrow' ) ),
            ];
            $obj = WP_Radio_Statistics::instance( $args );
            ob_start();
            $obj->chart();
            $html = ob_get_clean();
            wp_send_json_success( [
                'html' => $html,
            ] );
        }
        
        public function handle_import()
        {
            $countries = ( !empty($_REQUEST['countries']) ? $_REQUEST['countries'] : '' );
            include_once WP_RADIO_INCLUDES . '/admin/class-importer.php';
            $importer = WP_Radio_Importer::instance( $countries );
            $response = $importer->handle_import();
            wp_send_json_success( $response );
        }
        
        public function handle_notice()
        {
            $notice = ( !empty($_REQUEST['notice']) ? wp_unslash( $_REQUEST['notice'] ) : '' );
            if ( 'proxy_notice' == $notice ) {
                update_option( 'wp_radio_display_proxy_notice', 'off' );
            }
            if ( 'ads_player_notice' == $notice ) {
                update_option( 'wp_radio_display_ads_player_notice', 'off' );
            }
            update_option( 'wp_radio_notification', [] );
            exit;
        }
        
        public function handle_rating_notice()
        {
            $value = ( !empty($_REQUEST['value']) ? intval( $_REQUEST['value'] ) : 7 );
            
            if ( 'hide_notice' == $value ) {
                update_option( 'wp_radio_rating_notice', 'off' );
            } else {
                set_transient( 'wp_radio_rating_notice_interval', 'off', $value * DAY_IN_SECONDS );
            }
            
            update_option( sanitize_key( 'wp_radio_notification' ), [] );
        }
        
        public static function instance()
        {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }
        
        public function get_states()
        {
            if ( empty($_REQUEST['country_id']) ) {
                return;
            }
            $country_id = intval( $_REQUEST['country_id'] );
            $states = get_terms( [
                'taxonomy'   => 'radio_country',
                'parent'     => $country_id,
                'hide_empty' => false,
            ] );
            $html = '';
            
            if ( !empty($states) ) {
                $html .= '<option value="0">' . __( 'Select state', 'wp-radio' ) . '</option>';
                $states = wp_list_pluck( $states, 'name', 'term_id' );
                foreach ( $states as $id => $name ) {
                    $html .= sprintf( '<option value="%s">%s</option>', $id, $name );
                }
            }
            
            wp_send_json_success( [
                'html' => $html,
            ] );
        }
        
        public function get_cities()
        {
            if ( empty($_REQUEST['state_id']) ) {
                return;
            }
            $state_id = intval( $_REQUEST['state_id'] );
            $cities = get_terms( [
                'taxonomy'   => 'radio_country',
                'parent'     => $state_id,
                'hide_empty' => false,
            ] );
            $html = '';
            
            if ( !empty($cities) ) {
                $html .= '<option value="0">' . __( 'Select city', 'wp-radio' ) . '</option>';
                $cities = wp_list_pluck( $cities, 'name', 'term_id' );
                foreach ( $cities as $id => $name ) {
                    $html .= sprintf( '<option value="%s">%s</option>', $id, $name );
                }
            }
            
            wp_send_json_success( [
                'html' => $html,
            ] );
        }
    
    }
}
WP_Radio_Admin_Ajax::instance();