<?php

defined( 'ABSPATH' ) || exit;

if ( $pageCount < 2 ) {
	return;
}

?>

<div class="listing-footer">

    <div class="wp-radio-pagination">
		<?php
		echo paginate_links( array(
			'format'    => '?paginate=%#%',
			'current'   => max( 1, $paginate ),
			'total'     => $pageCount,
			'mid_size'  => 1,
			'prev_text' => '<i class="dashicons dashicons-arrow-left-alt"></i>',
			'next_text' => '<i class="dashicons dashicons-arrow-right-alt"></i>',
		) );
		?>
    </div>

    <form class="wp-radio-pagination-form" method="get">
        <label for="go-to-page"><?php _e( 'Go to page : ', 'wp-radio' ); ?></label>
        <input id="go-to-page" type="number" name="paginate" value="<?php echo $paginate; ?>"/>

        <button type="submit" class="wp-radio-button wp-radio-pagination-form-submit">
            <span><?php _e( 'Go', 'wp-radio' ); ?></span>
            <span class="dashicons dashicons-arrow-right-alt2"></span>
        </button>

    </form>

</div>
