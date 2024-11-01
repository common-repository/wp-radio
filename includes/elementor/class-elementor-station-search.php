<?php

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit();

class WP_Radio_Elementor_Station_Search extends Widget_Base {

	public function get_name() {
		return 'wp_radio_station_search';
	}

	public function get_title() {
		return __( 'Station Search', 'wp-radio' );
	}

	public function get_icon() {
		return 'eicon-search';
	}

	public function get_categories() {
		return [ 'wp_radio' ];
	}

	public function get_keywords() {
		return [ 'search', 'station' ];
	}

	public function register_controls() {

		$this->start_controls_section( '_section_radio_station',
			[
				'label' => __( 'Station Search', 'wp-radio' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			] );

		$this->add_responsive_control( 'country_filter',
			[
				'label'        => __( 'Show country filter : ', 'wp-radio' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'true',
				'label_on'     => __( 'Show', 'wp-radio' ),
				'label_off'    => __( 'Hide', 'wp-radio' ),
				'return_value' => 'true',
			] );


		$this->add_responsive_control( 'genre_filter',
			[
				'label'        => __( 'Show genre filter : ', 'wp-radio' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'true',
				'label_on'     => __( 'Show', 'wp-radio' ),
				'label_off'    => __( 'Hide', 'wp-radio' ),
				'return_value' => 'true',
			] );


		$this->end_controls_section();
	}

	public function render() {
		$settings       = $this->get_settings_for_display();
		$genre_filter   = $settings['genre_filter'];
		$country_filter = $settings['country_filter'];

		echo do_shortcode( "[wp_radio_search_form country_filter='{$country_filter}' genre_filter='{$genre_filter}' ]" );
	}

}
