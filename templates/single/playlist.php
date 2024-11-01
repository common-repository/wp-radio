<?php

$items = wp_radio_get_playlist_items( $id );

if ( ! empty( $items ) ) { ?>
    <div class="wp-radio-playlist">
        <h3><?php esc_html_e( 'Playlist', 'wp-radio' ); ?></h3>

        <ul>
			<?php foreach ( $items as $time => $title ) { ?>
                <li>
                    <span class="playlist-item-time"><?php echo $time; ?></span>
                    <span class="playlist-item-title"><?php echo $title; ?></span>
                </li>
			<?php } ?>
        </ul>
    </div>
<?php }