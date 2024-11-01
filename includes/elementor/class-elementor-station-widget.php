<?php

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit();

class WP_Radio_Elementor_Station_Widget extends Widget_Base {

	public function get_name() {
		return 'wp_radio_station';
	}

	public function get_title() {
		return __( 'Radio Station', 'wp-radio' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
	}

	public function get_categories() {
		return [ 'wp_radio' ];
	}

	public function get_keywords() {
		return [ 'audio', 'radio', 'music', 'wp-radio', 'player' ];
	}

	public function register_controls() {

		$this->start_controls_section( '_section_radio_station',
			[
				'label' => __( 'Radio Station', 'wp-radio' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			] );

		$this->add_control( 'station_id',
			[
				'label'       => __( 'Station ID', 'wp-radio' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
			] );

		$this->add_control( 'show_description',
			[
				'label'        => __( 'Show Description : ', 'wp-radio' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'true',
				'label_on'     => __( 'Show', 'wp-radio' ),
				'label_off'    => __( 'Hide', 'wp-radio' ),
				'return_value' => 'true',
			] );

		$this->add_control( 'show_genre',
			[
				'label'        => __( 'Show Genres : ', 'wp-radio' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'true',
				'label_on'     => __( 'Show', 'wp-radio' ),
				'label_off'    => __( 'Hide', 'wp-radio' ),
				'return_value' => 'true',
			] );

		$this->end_controls_section();
	}

	public function render() {
		$settings         = $this->get_settings_for_display();
		$station_id       = $settings['station_id'];
		$show_description = $settings['show_description'];
		$show_genre       = $settings['show_genre'];

		if(empty($station_id)){
			esc_html_e("Please, enter a station id.", 'wp-radio');
		}else {
			echo do_shortcode( "[wp_radio_station id='{$station_id}' show_description='{$show_description}' show_genre='{$show_genre}' ]" );
		}
	}

}
