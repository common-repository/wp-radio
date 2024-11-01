<?php

defined( 'ABSPATH' ) || exit();


if ( ! class_exists( 'WP_Radio_Chart_Widget' ) ) {
	class WP_Radio_Chart_Widget {
		/** @var null */
		private static $instance = null;

		/**
		 * WP_Radio_Chart_Widget constructor.
		 */
		public function __construct() {
			if ( wp_radio_get_settings( 'enable_statistics', true ) ) {
				add_action( 'wp_dashboard_setup', [ $this, 'dashboard_widgets' ] );
			}
		}

		public function dashboard_widgets() {

			wp_add_dashboard_widget( 'wp_radio_chart', esc_html__( 'WP Radio Stats - Stations play count per day', 'wp-radio' ), [
				$this,
				'render_dashboard_widget'
			] );

			// Globalize the metaboxes array, this holds all the widgets for wp-admin.
			global $wp_meta_boxes;

			// Get the regular dashboard widgets array
			// (which already has our new widget but appended at the end).
			$default_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

			// Backup and delete our new dashboard widget from the end of the array.
			$wp_dark_mode_widget_backup = array( 'wp_radio_chart' => $default_dashboard['wp_radio_chart'] );
			unset( $default_dashboard['wp_radio_chart'] );

			// Merge the two arrays together so our widget is at the beginning.
			$sorted_dashboard = array_merge( $wp_dark_mode_widget_backup, $default_dashboard );

			// Save the sorted array back into the original metaboxes.
			$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
		}

		public function render_dashboard_widget() { ?>
            <div class="wp-radio-chart-widget-wrapper"></div>
		<?php }

		/**
		 * @return WP_Radio_Chart_Widget|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}

}

WP_Radio_Chart_Widget::instance();