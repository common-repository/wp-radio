<?php

/* Block Direct Access */
defined( 'ABSPATH' ) || exit();

$is_pro = wr_fs()->can_use_premium_code__premium_only();

/** player-type */
$is_fullwidth = 'full-width' == $player_type;
$is_popup     = 'popup' == $player_type;
$is_shortcode = 'shortcode' == $player_type;
$align        = ! empty( $align ) ? $align : 'center';

$show_next_prev = wp_radio_get_settings( 'next_prev', ! $is_shortcode );

if ( isset( $next_prev ) ) {
	$show_next_prev = $next_prev == 'true';
}

$show_popup_btn = wp_radio_get_settings( 'popup_btn', ! $is_shortcode );
if ( isset( $popup_btn ) ) {
	$show_popup_btn = $popup_btn == 'true';
}

//initialize values
$stream = wp_radio_get_stream_data( isset( $id ) ? $id : null );

//Type fixed player attributes
$is_hidden = $is_fullwidth && ( wp_radio_get_settings( 'full_width_player', false ) || ( ! empty( $_GET['player'] ) && sanitize_key( $_GET['player'] ) == 'popup' ) );

//Type shortcode player attributes
if ( $is_shortcode ) {

	if ( empty( $id ) || ! get_post( $id ) ) {
		global $wp_radio_args;

		$wp_radio_args = [ 'posts_per_page' => 1 ];
		$stations      = wp_radio_get_stations_by_country();
		if ( ! empty( $stations ) ) {
			$id = $stations[0]->ID;
		}
	}

	$stream = wp_radio_get_stream_data( $id );
}

// Player type class
$playerClass = "align-{$align} ";

if ( $is_popup ) {
	$playerClass .= 'shortcode popup';
} elseif ( $is_shortcode ) {
	$playerClass .= 'shortcode';
} elseif ( $is_fullwidth ) {
	$playerClass .= 'full-width init-hidden';
}

?>
<div class="wp-radio-player <?php echo $playerClass; ?> <?php echo $is_hidden ? 'hidden' : ''; ?>">

    <!--******* Details ********-->
    <div class="wp-radio-player-details">
        <a href="<?php echo $stream['link'] ?>" class="station-thumbnail-wrap">
            <img src="<?php echo $stream['thumbnail']; ?>" class="station-thumbnail"
                 alt="<?php echo $stream['title']; ?>"/>
        </a>

        <div class="station-meta">
            <a href="<?php echo $stream['link'] ?>" class="station-title"><?php echo $stream['title']; ?></a>

            <!-- Player Status -->
            <div class="station-status">
                <div class="wp-radio-player-status status-<?php echo $stream['id']; ?>">
                    <span class="status-text-offline"><?php esc_html_e( 'OFFLINE', 'wp-radio' ); ?></span>
                    <span class="status-text-live"><?php esc_html_e( 'LIVE', 'wp-radio' ); ?></span>

                    <span class="status-dot"></span>
                </div>

                <!--Song Title-->
                <div class="wp-radio-player-song-title" data-id="<?php echo $stream['id']; ?>"></div>
            </div>
        </div>
    </div>

    <!--******* Player Controls *******-->
    <div class="wp-radio-player-controls">

		<?php do_action( 'wp_radio/player/controls/start', $stream['id'], $player_type ); ?>

        <!-- Previous -->
		<?php if ( $show_next_prev ) { ?>
            <button type="button" class="play-prev"
                    aria-label="<?php esc_attr_e( 'Play Previous', 'wp-radio' ); ?>"
                    title="<?php esc_attr_e( 'Play Previous', 'wp-radio' ); ?>"
            >
                <span class="dashicons dashicons-controls-skipback"></span>
            </button>
		<?php } ?>

        <!-- Play/ Pause -->
        <button
                type="button"
                aria-label="<?php esc_attr_e( 'Play/Pause', 'wp-radio' ); ?>"
                title="<?php esc_attr_e( 'Play/Pause', 'wp-radio' ); ?>"
                class="play-btn"
                data-id="<?php echo $stream['id']; ?>"
			<?php if ( ! $is_fullwidth ) { ?>
                onclick='wpRadioHooks.doAction("playPause",this, <?php echo json_encode( [
					'id'        => $stream['id'],
					'title'     => $stream['title'],
					'thumbnail' => $stream['thumbnail'],
					'link'      => $stream['link'],
					'stream'    => $stream['stream'],
				] ); ?>)'
			<?php } ?>
        >

            <div class="wp-radio-spinner">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>

            <span class="dashicons dashicons-controls-play"></span>
            <span class="dashicons dashicons-controls-pause"></span>
        </button>

        <!-- Next -->
		<?php if ( $show_next_prev ) { ?>
            <button type="button" class="play-next"
                    aria-label="<?php esc_attr_e( 'Play Next', 'wp-radio' ); ?>"
                    title="<?php esc_attr_e( 'Play Next', 'wp-radio' ); ?>"
            >
                <span class="dashicons dashicons-controls-skipforward"></span>
            </button>
		<?php } ?>

        <!-- Popup Icon -->
		<?php if ( $is_pro && ! $is_popup && $show_popup_btn ) { ?>
            <button type="button" class="player-popup"
                    aria-label="<?php esc_attr_e( 'Open popup player', 'wp-radio' ); ?>"
                    title="<?php esc_attr_e( 'Open popup player', 'wp-radio' ); ?>"
            >
                <span class="dashicons dashicons-external"></span>
            </button>
		<?php } ?>

        <!-- Volume Control -->
        <div class="player-volume-wrap">
            <div class="volume-slider">
                <div class="volume-slider-bar">
                    <div class="volume-slider-handle"></div>
                </div>
                <input type="range" min="0" max="100" value="100" step="1">
            </div>
            <button type="button" class="player-volume">
                <i class="dashicons dashicons-controls-volumeon"></i>
            </button>
        </div>

		<?php do_action( 'wp_radio/player/controls/end', $stream['id'] ); ?>

    </div>

    <!-- Player toggle -->
	<?php if ( $is_fullwidth ) { ?>
        <div class="wp-radio-player-toggle">
            <i class="dashicons dashicons-arrow-down-alt2" aria-label="Collapse player" title="Collapse player"></i>
            <i class="dashicons dashicons-controls-play" aria-label="Expand player" title="Expand player"></i>
        </div>
	<?php } ?>


	<?php if ( ! $is_shortcode ) { ?>
        <audio id="wp-radio-media" src="<?php echo $stream['stream']; ?>" preload="none"></audio>
	<?php } ?>

</div>