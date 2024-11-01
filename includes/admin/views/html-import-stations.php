<?php

defined( 'ABSPATH' ) || exit();


$imported = get_option( 'wp_radio_imported_countries' );
$imported = ! empty( $imported ) ? array_filter( $imported ) : [];
$imported = array_values( $imported );

$countries = wp_radio_country_station_map();

?>
<script>
    var wpRadioImportedCountries = <?php echo json_encode( $imported ); ?>;
    var wpRadioCountries = <?php echo json_encode( $countries ); ?>;
</script>

<div id="wp-radio-import" class="wp-radio-import"></div>

