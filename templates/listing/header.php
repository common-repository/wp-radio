<?php

defined( 'ABSPATH' ) || exit;

$show_start = ( ( $paginate - 1 ) * $perpage ) + 1;
$show_end   = ( $paginate * $perpage ) > $total ? $total : ( $paginate * $perpage );

$perpage_options = [ 5, 10, 15, 20, 30, 50 ];

$sort_options = [
	'asc'       => __( 'A -Z', 'wp-radio' ),
	'desc'      => __( 'Z -A', 'wp-radio' ),
	'date_desc' => __( 'Newest', 'wp-radio' ),
	'date_asc'  => __( 'Oldest', 'wp-radio' ),
];

?>

<div class="listing-top">

    <div class="result-count">

		<?php
		if ( $total > 0 ) {
			printf( __( 'Showing %s - %s of %s stations', 'wp-radio' ), $show_start, $show_end, $total );
		} else {
			_e( 'No stations found', 'wp-radio' );
		}
		?>
    </div>

    <div class="show-per-page">
        <label for="show_per_page"><?php _e( 'Show : ', 'wp-radio' ) ?></label>
        <select id="show_per_page" class="show-per-page-selection" name="perpage"
                onchange="var submitBtn = this.form.querySelector('[type=submit]'); if(submitBtn){submitBtn.click()}else{this.form.submit()}"
        >

            <option value=""><?php _e( 'Items per page', 'wp-radio' ); ?></option>

			<?php foreach ( $perpage_options as $option ) : ?>
                <option value="<?php echo $option ?>" <?php echo $perpage == $option ? 'selected' : '' ?>><?php echo $option ?></option>
			<?php endforeach; ?>
        </select>
    </div>

    <div class="listing-sort">
        <label for="listing_sort"><?php _e( 'Sort : ', 'wp-radio' ) ?></label>
        <select id="listing_sort" class="listing-sort-selection" name="sort"
                onchange="var submitBtn = this.form.querySelector('[type=submit]'); if(submitBtn){submitBtn.click()}else{this.form.submit()}"
        >
			<?php foreach ( $sort_options as $key => $value ) : ?>
                <option value="<?php echo $key ?>" <?php echo $sort == $key ? 'selected' : '' ?>><?php echo $value ?></option>
			<?php endforeach; ?>
        </select>
    </div>


</div>
