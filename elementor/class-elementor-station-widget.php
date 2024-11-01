<?php

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'WP_Radio_Elementor_Station_Widget' ) ) {
	class WP_Radio_Elementor_Station_Widget extends \Elementor\Widget_Base {

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
			return [ 'basic' ];
		}

		public function get_keywords() {
			return [ 'audio', 'radio', 'music', 'wp-radio' ];
		}

		public function _register_controls() {

			$this->start_controls_section( '_section_radio_station',
			                               [
				                               'label' => __( 'Alignment', 'wp-radio' ),
				                               'tab'   => Controls_Manager::TAB_CONTENT,
			                               ] );

			//switch style
			$this->add_control( '_station_heading',
			                    [
				                    'label' => __( 'Radio Station', 'wp-radio' ),
				                    'type'  => Controls_Manager::HEADING,
			                    ] );

			$this->add_control( 'station_id',
			                    [
				                    'label'       => __( 'Station ID', 'wp-radio' ),
				                    'type'        => Controls_Manager::TEXT,
				                    'label_block' => false,
			                    ] );

			$this->add_responsive_control( 'align',
			                               [
				                               'label'     => __( 'Alignment', 'wp-radio' ),
				                               'type'      => Controls_Manager::CHOOSE,
				                               'options'   => [
					                               'left'   => [
						                               'title' => __( 'Left', 'wp-radio' ),
						                               'icon'  => 'fa fa-align-left',
					                               ],
					                               'center' => [
						                               'title' => __( 'Center', 'wp-radio' ),
						                               'icon'  => 'fa fa-align-center',
					                               ],
					                               'right'  => [
						                               'title' => __( 'Right', 'wp-radio' ),
						                               'icon'  => 'fa fa-align-right',
					                               ],
				                               ],
				                               'toggle'    => true,
				                               'default'   => 'left',
				                               'selectors' => [
					                               '{{WRAPPER}}' => 'text-align: {{VALUE}};',
				                               ],
			                               ] );

			$this->end_controls_section();
		}

		public function render() {
			$settings = $this->get_settings_for_display();
			extract( $settings );

			echo do_shortcode( "[wp_radio_station id={$station_id}]" );
		}

	}
}