<?php

defined('ABSPATH') || exit;

if ( empty( $genres ) ) {
	return;
}

?>

<div class="genres">
    <span><?php _e( 'Genres : ', 'wp-radio' ) ?></span>

	<?php
	foreach ( $genres as $genre ) {
		printf( '<a href="%s">%s</a>', $genre['link'], $genre['name'] );
	}
	?>
</div>