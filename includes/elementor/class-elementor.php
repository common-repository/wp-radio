<?php

defined( 'ABSPATH' ) || exit;

class WP_Radio_Elementor {

	private static $instance = null;

	public function __construct() {
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_categories' ] );
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widget' ] );
	}

	public function add_categories( $elements_manager ) {
		$elements_manager->add_category( 'wp_radio', [
				'title' => __( 'WP Radio', 'wp-radio' ),
				'icon'  => 'fa fa-plug',
			]
		);
	}

	/**
	 * register darkmode switch elementor widget
	 *
	 * @throws Exception
	 */
	public function register_widget( $widgets_manager ) {
		include_once WP_RADIO_INCLUDES . '/elementor/class-elementor-station-widget.php';
		include_once WP_RADIO_INCLUDES . '/elementor/class-elementor-player-widget.php';
		include_once WP_RADIO_INCLUDES . '/elementor/class-elementor-country-list.php';
		include_once WP_RADIO_INCLUDES . '/elementor/class-elementor-station-search.php';

		$widgets_manager->register_widget_type( new WP_Radio_Elementor_Station_Widget() );
		$widgets_manager->register_widget_type( new WP_Radio_Elementor_Player_Widget() );
		$widgets_manager->register_widget_type( new WP_Radio_Elementor_Country_List() );
		$widgets_manager->register_widget_type( new WP_Radio_Elementor_Station_Search() );
	}

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

}

WP_Radio_Elementor::instance();