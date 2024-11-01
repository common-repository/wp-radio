<?php

defined( 'ABSPATH' ) || exit;

if ( empty( $regions ) ) {
	return;
}
?>
<div class="region-list">
	<?php
	foreach ( $regions as $region ) {
		printf( '<a href="%s">%s</a>', $region['link'], $region['name'] );
	}
	?>
</div>



