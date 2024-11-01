<?php

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit();

class WP_Radio_Elementor_Player_Widget extends Widget_Base {

	public function get_name() {
		return 'wp_radio_player';
	}

	public function get_title() {
		return __( 'Radio Player', 'wp-radio' );
	}

	public function get_icon() {
		return 'eicon-play';
	}

	public function get_categories() {
		return [ 'wp_radio' ];
	}

	public function get_keywords() {
		return [ 'audio', 'radio', 'music', 'wp-radio', 'radio player', 'player' ];
	}

	public function register_controls() {

		$this->start_controls_section( '_section_radio_station',
			[
				'label' => __( 'WP Radio Player', 'wp-radio' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			] );

		//switch style
		$this->add_control( '_station_heading',
			[
				'label' => __( 'Radio Player', 'wp-radio' ),
				'type'  => Controls_Manager::HEADING,
			] );

		$this->add_control( 'station_id',
			[
				'label'       => __( 'Station ID : ', 'wp-radio' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
			] );


		$this->add_control( 'prev_next',
			[
				'label'        => __( 'Prev/ Next Buttons : ', 'wp-radio' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'false',
				'label_on'     => __( 'Show', 'wp-radio' ),
				'label_off'    => __( 'Hide', 'wp-radio' ),
				'return_value' => 'true',
			] );

		$this->add_responsive_control( 'align',
			[
				'label'   => __( 'Alignment : ', 'wp-radio' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left'   => [
						'title' => __( 'Left', 'wp-radio' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'wp-radio' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'wp-radio' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'toggle'  => true,
				'default' => 'center',
			] );

		$this->end_controls_section();
	}

	public function render() {
		$settings   = $this->get_settings_for_display();
		$station_id = $settings['station_id'];
		$prev_next  = $settings['prev_next'];
		$align      = $settings['align'];

		if ( empty( $station_id ) ) { ?>
            <h3><?php esc_html_e( 'Please, enter a station ID', 'wp-radio' ); ?></h3>
		<?php } else {
			echo do_shortcode( "[wp_radio_player next_prev='{$prev_next}' id='{$station_id}'  align='{$align}' player_type='shortcode']" );
		}
	}

}
