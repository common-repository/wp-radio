<div class="wrap wp-radio-statistics">

    <h1 class="wp-heading-inline statistics-page-heading">
        <i class="dashicons dashicons-chart-bar"></i>
		<?php 
_e( 'Statistics', 'wp-radio' );
?>
    </h1>

        <p><?php 
esc_html_e( 'Get overview of how many listeners are listening how many radio stations in a day and get the list of top played radio stations, etc.', 'wp-radio' );
?></p>


	<?php 
include_once WP_RADIO_INCLUDES . '/class-statistics.php';
$args = [
    'start_date' => date( 'Y-m-d', strtotime( '-1 month' ) ),
    'end_date'   => date( 'Y-m-d' ),
];
if ( !empty($_REQUEST['start_date']) ) {
    $args['start_date'] = wp_unslash( $_REQUEST['start_date'] );
}
if ( !empty($_REQUEST['end_date']) ) {
    $args['end_date'] = wp_unslash( $_REQUEST['end_date'] );
}
$statistics = WP_Radio_Statistics::instance( $args );
?>

    <div class="statistics-wrap">
		<?php 
$is_hidden = false;
$promo_title = __( 'Upgrade to PRO to view the statistics.', 'wp-radio' );
include WP_RADIO_INCLUDES . '/admin/views/promo.php';
$statistics->filter_bar();
$statistics->chart();
$statistics->top_stations();
?>
    </div>

</div>