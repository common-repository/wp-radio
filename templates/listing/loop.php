<?php

defined( 'ABSPATH' ) || exit;

?>

<div class="wp-radio-listing listing-<?php echo $station['id']; ?> <?php echo $is_grid ? 'listing-col-' . $col : '' ?>">

    <div class="listing-thumbnail">
        <a href=<?php echo $is_grid ? 'javascript:;' : $station['link']; ?>>
            <img src=<?php echo $station['thumbnail']; ?> alt="<?php echo $station['title'] ?>" class="listing-thumb"/>
        </a>
    </div>

    <div class="listing-details">

        <div class="listing-heading">
            <a href="<?php echo $station['link']; ?>" class="station-name">
                <span><?php echo $station['title']; ?></span>
            </a>

			<?php
			if ( ! $is_grid ) {
				printf( '<span class="slogan">%s</span>', $station['slogan'] );

				if ( ! empty( $station['locations'] ) ) {
					wp_radio_get_template( 'listing/locations', array( 'locations' => $station['locations'] ) );
				}

			}
			?>

        </div>

		<?php
		if ( $show_genres && ! empty( $station['genres'] ) ) {
			wp_radio_get_template( 'listing/genres', array( 'genres' => $station['genres'] ) );
		}
		?>

    </div>

    <div class="play-btn-wrap">
		<?php do_action( 'wp_radio/play_btn/before', $station['id'] ); ?>

        <button type="button"
                class="play-btn"
                data-id="<?php echo $station['id']; ?>"
                onclick='wpRadioHooks.doAction("playPause",this, <?php echo json_encode( wp_radio_get_stream_data($station['id']) ); ?>)'
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
            <i class="dashicons dashicons-controls-play"></i>
            <i class="dashicons dashicons-controls-pause"></i>
        </button>
    </div>

	<?php
	if ( $show_content ) {
		printf( '<p class="listing-desc">%s</p>', $station['content'] );
	}
	?>
</div>
