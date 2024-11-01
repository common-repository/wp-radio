<?php

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit();

class WP_Radio_Elementor_Country_List extends Widget_Base {

	public function get_name() {
		return 'wp_radio_country_list';
	}

	public function get_title() {
		return __( 'Country List', 'wp-radio' );
	}

	public function get_icon() {
		return 'eicon-post-list';
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
				'label' => __( 'Radio Country List', 'wp-radio' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			] );

		$this->add_responsive_control( 'style',
			[
				'label'   => __( 'List Style : ', 'wp-radio' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'list'   => [
						'title' => __( 'List', 'wp-radio' ),
						'icon'  => 'eicon-editor-list-ul',
					],
					'grid' => [
						'title' => __( 'Grid', 'wp-radio' ),
						'icon'  => 'eicon-gallery-grid',
					],
				],
				'toggle'  => true,
				'default' => 'list',
			] );

		$this->add_control(
			'columns',
			[
				'label'     => esc_html__( 'Grid Columns', 'wp-radio' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 6,
				'step'      => 1,
				'default'   => 3,
				'condition' => [
					'style' => 'grid',
				],
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings_for_display();
		$style    = $settings['style'];
		$columns    = $settings['columns'];

		echo do_shortcode( "[wp_radio_country_list style='{$style}' columns='{$columns}' ]" );
	}

}
