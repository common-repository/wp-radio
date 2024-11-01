<?php

defined( 'ABSPATH' ) || exit;
/**
 * Get post meta value. Default value return if meta value is empty
 *
 * @param $post_id
 * @param $key
 * @param string $default
 *
 * @return mixed|string
 */
function wp_radio_get_meta( $post_id, $key, $default = '' )
{
    $meta = get_post_meta( $post_id, $key, true );
    return ( !empty($meta) ? $meta : $default );
}

/**
 * Get the stream data object
 *
 * @return array
 */
function wp_radio_get_stream_data( $post_id )
{
    if ( !$post_id ) {
        return array(
            'id'        => '',
            'title'     => 'WP Radio',
            'thumbnail' => WP_RADIO_ASSETS . '/images/placeholder.jpg',
            'link'      => '',
            'stream'    => '',
        );
    }
    $url = get_the_permalink( $post_id );
    $is_popup = !empty($_GET['player']) && sanitize_key( $_GET['player'] ) == 'popup';
    if ( $is_popup ) {
        $url = str_replace( 'https', 'http', $url );
    }
    $title = wp_radio_escape_quote( get_the_title( $post_id ) );
    return [
        'id'        => $post_id,
        'title'     => $title,
        'thumbnail' => wp_radio_get_meta( $post_id, 'logo', WP_RADIO_ASSETS . '/images/placeholder.jpg' ),
        'link'      => $url,
        'stream'    => apply_filters( 'wp_radio/stream_url', wp_radio_get_meta( $post_id, 'stream_url' ), $post_id ),
    ];
}

function wp_radio_get_stream_title( $id )
{
    $url = wp_radio_get_meta( $id, 'stream_url' );
    $title = '';
    if ( empty($title) ) {
        
        if ( preg_match( '/(?<server>^https?:\\/\\/.*:\\d+\\/)/', $url, $match ) ) {
            $server = $match['server'] . 'stats?sid=1&json=1';
            $response = wp_remote_get( $server );
            
            if ( !is_wp_error( $response ) ) {
                $json = wp_remote_retrieve_body( $response );
                $meta = json_decode( $json );
                if ( !empty($meta) ) {
                    if ( !empty($meta->songtitle) ) {
                        $title = $meta->songtitle;
                    }
                }
            }
        
        }
    
    }
    
    if ( empty($title) ) {
        $stream_title = wp_radio_get_stream_header_title( $url );
        if ( !is_wp_error( $stream_title ) && !empty($stream_title) ) {
            $title = $stream_title;
        }
    }
    
    return $title;
}

/**
 * Get the streaming title
 *
 * Return the current track's title that is being playing
 *
 * @param $streamingUrl
 * @param $interval
 * @param int $offset
 *
 * @return false|string
 * @since 1.0.0
 *
 */
function wp_radio_get_stream_header_title( $streamingUrl, $interval = 19200, $offset = 0 )
{
    if ( empty($streamingUrl) ) {
        return false;
    }
    $needle = ( stristr( $streamingUrl, '.ogg' ) ? 'title=' : 'StreamTitle=' );
    $ua = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.110 Safari/537.36';
    $opts = [
        'http' => [
        'method'     => 'GET',
        'header'     => 'Icy-MetaData: 1',
        'user_agent' => $ua,
    ],
    ];
    try {
        $headers = @get_headers( $streamingUrl );
    } catch ( Exception $exception ) {
        return $exception->getMessage();
    }
    if ( !is_array( $headers ) ) {
        return '';
    }
    foreach ( $headers as $h ) {
        if ( strpos( strtolower( $h ), 'icy-metaint' ) !== false && ($interval = explode( ':', $h )[1]) ) {
            break;
        }
    }
    $context = stream_context_create( $opts );
    
    if ( $stream = @fopen(
        $streamingUrl,
        'r',
        false,
        $context
    ) ) {
        @($buffer = stream_get_contents( $stream, (int) $interval, $offset ));
        
        if ( strpos( $buffer, $needle ) !== false ) {
            fclose( $stream );
            $title = explode( $needle, $buffer )[1];
            return ( 'title=' == $needle ? substr( $title, 1, strpos( $title, 'server' ) - 5 ) : substr( $title, 1, strpos( $title, ';' ) - 2 ) );
        }
    
    } else {
        return '';
    }

}

if ( !function_exists( 'wp_radio_get_country' ) ) {
    function wp_radio_get_country( $country_code )
    {
        $term = get_term_by( 'slug', $country_code, 'radio_country' );
        return ( $term ? $term : false );
    }

}
function wp_radio_get_country_flag( $country_code, $size = 16 )
{
    if ( strlen( $country_code ) != 2 ) {
        $country_code = 'ww';
    }
    $url = WP_RADIO_ASSETS . "/images/flags/{$country_code}.svg";
    return sprintf( '<img src="%s" width="%s" loading="lazy">', $url, $size );
}

function wp_radio_get_next_prev_stream_data( $current_id, $next_prev = 'next' )
{
    global  $post ;
    $post = get_post( $current_id );
    $post_id = ( 'next' == $next_prev ? get_next_post()->ID : get_previous_post()->ID );
    return ( !empty($post_id) ? wp_radio_get_stream_data( $post_id ) : false );
}

function wp_radio_get_stations( $args = array(), $return_query = false )
{
    $args = wp_parse_args( $args, [
        'post_type'   => 'wp_radio',
        'post_status' => 'publish',
    ] );
    $query = new WP_Query( $args );
    if ( $return_query ) {
        return $query;
    }
    return ( $query->have_posts() ? $query->posts : false );
}

if ( !function_exists( 'wp_radio_get_station_count' ) ) {
    function wp_radio_get_station_count( $country )
    {
        global  $wpdb ;
        $sql = "SELECT COUNT(`id`) FROM {$wpdb->prefix}wp_radio_station WHERE country_code = '%s'; ";
        $result = $wpdb->get_var( $wpdb->prepare( $sql, $country ) );
        return $result;
    }

}
if ( !function_exists( 'wp_radio_premium_countries' ) ) {
    function wp_radio_premium_countries()
    {
        $premium_countries = array(
            'AR',
            'AU',
            'AT',
            'BY',
            'BE',
            'BR',
            'BO',
            'BA',
            'BG',
            'CA',
            'CL',
            'CN',
            'CO',
            'CR',
            'HR',
            'CY',
            'CZ',
            'DK',
            'DO',
            'EC',
            'EG',
            'SV',
            'EE',
            'FI',
            'FR',
            'GR',
            'DE',
            'GH',
            'GR',
            'GT',
            'HT',
            'HN',
            'HU',
            'IN',
            'ID',
            'IE',
            'IL',
            'IT',
            'JM',
            'JP',
            'KE',
            'LV',
            'LB',
            'LT',
            'MK',
            'MY',
            'ML',
            'MX',
            'MD',
            'ME',
            'MA',
            'NP',
            'NL',
            'NZ',
            'NI',
            'NG',
            'NO',
            'PK',
            'PA',
            'PY',
            'PE',
            'PH',
            'PL',
            'PR',
            'RO',
            'RU',
            'SN',
            'RS',
            'SG',
            'SK',
            'SI',
            'ZA',
            'ES',
            'SE',
            'CH',
            'TW',
            'TH',
            'TT',
            'TR',
            'UA',
            'AE',
            'US',
            'GB',
            'UY',
            'VE'
        );
        return array_map( 'strtolower', $premium_countries );
    }

}
/**
 * Get the user country code and show the radio stations
 * of the country as default in the index page
 *
 * @return string
 * @since 1.0.0
 *
 */
function wp_radio_get_visitor_country()
{
    global  $wpdb ;
    $ip = wp_radio_get_user_ip();
    $table = $wpdb->prefix . 'wp_radio_visitors';
    $sql = $wpdb->prepare( "SELECT country_code FROM {$table} WHERE ip=%s LIMIT 1;", $ip );
    $saved_ip = $wpdb->get_var( $sql );
    if ( $saved_ip ) {
        return $saved_ip;
    }
    $json_feed_url = 'http://ip-api.com/json/' . $ip;
    $args = [
        'timeout' => 120,
    ];
    $response = wp_remote_get( $json_feed_url, $args );
    if ( is_wp_error( $response ) ) {
        return '';
    }
    $json_feed = json_decode( $response['body'] );
    
    if ( !empty($json_feed->countryCode) ) {
        $country_code = strtolower( $json_feed->countryCode );
        $sql = "INSERT INTO \n                        {$table} (`ip`,`country_code`) \n                    VALUES \n                        ( %s, %s)\n                    ON DUPLICATE KEY UPDATE\n                        `country_code` = VALUES (country_code)  \n                ";
        $wpdb->query( $wpdb->prepare( $sql, [ $ip, $country_code ] ) );
        return $country_code;
    }
    
    return '';
}

function wp_radio_get_stations_by_country( $return_query = false )
{
    global  $wp_radio_args ;
    $country = wp_radio_get_visitor_country();
    if ( $country ) {
        $wp_radio_args['tax_query'] = [
            'relation' => 'AND',
            [
            'taxonomy' => 'radio_country',
            'field'    => 'slug',
            'terms'    => $country,
        ],
        ];
    }
    if ( $return_query ) {
        return wp_radio_get_stations( $wp_radio_args, true );
    }
    $posts = wp_radio_get_stations( $wp_radio_args );
    return ( !empty($posts) ? $posts : wp_radio_get_stations() );
}

if ( !function_exists( 'wp_radio_get_country_list' ) ) {
    /**
     * Get the raw country list
     *
     * @return array
     * @since 1.0.0
     *
     */
    function wp_radio_get_country_list()
    {
        $countries = array(
            "AF" => array(
            "country"   => "Afghanistan",
            "continent" => "Asia",
        ),
            "AL" => array(
            "country"   => "Albania",
            "continent" => "Europe",
        ),
            "DZ" => array(
            "country"   => "Algeria",
            "continent" => "Africa",
        ),
            "AS" => array(
            "country"   => "American Samoa",
            "continent" => "Oceania",
        ),
            "AD" => array(
            "country"   => "Andorra",
            "continent" => "Europe",
        ),
            "AO" => array(
            "country"   => "Angola",
            "continent" => "Africa",
        ),
            "AI" => array(
            "country"   => "Anguilla",
            "continent" => "North America",
        ),
            "AG" => array(
            "country"   => "Antigua and Barbuda",
            "continent" => "North America",
        ),
            "AR" => array(
            "country"   => "Argentina",
            "continent" => "South America",
        ),
            "AM" => array(
            "country"   => "Armenia",
            "continent" => "Asia",
        ),
            "AW" => array(
            "country"   => "Aruba",
            "continent" => "North America",
        ),
            "AU" => array(
            "country"   => "Australia",
            "continent" => "Oceania",
        ),
            "AT" => array(
            "country"   => "Austria",
            "continent" => "Europe",
        ),
            "AZ" => array(
            "country"   => "Azerbaijan",
            "continent" => "Asia",
        ),
            "BS" => array(
            "country"   => "Bahamas",
            "continent" => "North America",
        ),
            "BH" => array(
            "country"   => "Bahrain",
            "continent" => "Asia",
        ),
            "BD" => array(
            "country"   => "Bangladesh",
            "continent" => "Asia",
        ),
            "BB" => array(
            "country"   => "Barbados",
            "continent" => "North America",
        ),
            "BY" => array(
            "country"   => "Belarus",
            "continent" => "Europe",
        ),
            "BE" => array(
            "country"   => "Belgium",
            "continent" => "Europe",
        ),
            "BZ" => array(
            "country"   => "Belize",
            "continent" => "North America",
        ),
            "BJ" => array(
            "country"   => "Benin",
            "continent" => "Africa",
        ),
            "BM" => array(
            "country"   => "Bermuda",
            "continent" => "North America",
        ),
            "BT" => array(
            "country"   => "Bhutan",
            "continent" => "Asia",
        ),
            "BO" => array(
            "country"   => "Bolivia",
            "continent" => "South America",
        ),
            "BA" => array(
            "country"   => "Bosnia and Herzegovina",
            "continent" => "Europe",
        ),
            "BW" => array(
            "country"   => "Botswana",
            "continent" => "Africa",
        ),
            "BR" => array(
            "country"   => "Brazil",
            "continent" => "South America",
        ),
            "BN" => array(
            "country"   => "Brunei",
            "continent" => "Asia",
        ),
            "BG" => array(
            "country"   => "Bulgaria",
            "continent" => "Europe",
        ),
            "BF" => array(
            "country"   => "Burkina Faso",
            "continent" => "Africa",
        ),
            "BI" => array(
            "country"   => "Burundi",
            "continent" => "Africa",
        ),
            "BQ" => array(
            "country"   => "Bonaire",
            "continent" => "Europe",
        ),
            "KH" => array(
            "country"   => "Cambodia",
            "continent" => "Asia",
        ),
            "CM" => array(
            "country"   => "Cameroon",
            "continent" => "Africa",
        ),
            "CA" => array(
            "country"   => "Canada",
            "continent" => "North America",
        ),
            "CV" => array(
            "country"   => "Cape Verde",
            "continent" => "Africa",
        ),
            "KY" => array(
            "country"   => "Cayman Islands",
            "continent" => "North America",
        ),
            "CF" => array(
            "country"   => "Central African Republic",
            "continent" => "Africa",
        ),
            "TD" => array(
            "country"   => "Chad",
            "continent" => "Africa",
        ),
            "CL" => array(
            "country"   => "Chile",
            "continent" => "South America",
        ),
            "CN" => array(
            "country"   => "China",
            "continent" => "Asia",
        ),
            "CO" => array(
            "country"   => "Colombia",
            "continent" => "South America",
        ),
            "KM" => array(
            "country"   => "Comoros",
            "continent" => "Africa",
        ),
            "CG" => array(
            "country"   => "Congo",
            "continent" => "Africa",
        ),
            "CD" => array(
            "country"   => "DR Congo",
            "continent" => "Africa",
        ),
            "CK" => array(
            "country"   => "Cook Islands",
            "continent" => "Oceania",
        ),
            "CR" => array(
            "country"   => "Costa Rica",
            "continent" => "North America",
        ),
            "CI" => array(
            "country"   => "Ivory Coast",
            "continent" => "Africa",
        ),
            "HR" => array(
            "country"   => "Croatia",
            "continent" => "Europe",
        ),
            "CU" => array(
            "country"   => "Cuba",
            "continent" => "North America",
        ),
            "CY" => array(
            "country"   => "Cyprus",
            "continent" => "Asia",
        ),
            "CZ" => array(
            "country"   => "Czech Republic",
            "continent" => "Europe",
        ),
            "CW" => array(
            "country"   => "Curacao",
            "continent" => "South America",
        ),
            "xk" => array(
            "country"   => "Kosovo",
            "continent" => "Europe",
        ),
            "DK" => array(
            "country"   => "Denmark",
            "continent" => "Europe",
        ),
            "DJ" => array(
            "country"   => "Djibouti",
            "continent" => "Africa",
        ),
            "DM" => array(
            "country"   => "Dominica",
            "continent" => "North America",
        ),
            "DO" => array(
            "country"   => "Dominican Republic",
            "continent" => "North America",
        ),
            "EC" => array(
            "country"   => "Ecuador",
            "continent" => "South America",
        ),
            "EG" => array(
            "country"   => "Egypt",
            "continent" => "Africa",
        ),
            "SV" => array(
            "country"   => "El Salvador",
            "continent" => "North America",
        ),
            "GQ" => array(
            "country"   => "Equatorial Guinea",
            "continent" => "Africa",
        ),
            "ER" => array(
            "country"   => "Eritrea",
            "continent" => "Africa",
        ),
            "EE" => array(
            "country"   => "Estonia",
            "continent" => "Europe",
        ),
            "ET" => array(
            "country"   => "Ethiopia",
            "continent" => "Africa",
        ),
            "FK" => array(
            "country"   => "Falkland Islands",
            "continent" => "South America",
        ),
            "FO" => array(
            "country"   => "Faroe Islands, Denmark",
            "continent" => "Europe",
        ),
            "FJ" => array(
            "country"   => "Fiji",
            "continent" => "Oceania",
        ),
            "FI" => array(
            "country"   => "Finland",
            "continent" => "Europe",
        ),
            "FR" => array(
            "country"   => "France",
            "continent" => "Europe",
        ),
            "GF" => array(
            "country"   => "French Guiana",
            "continent" => "South America",
        ),
            "PF" => array(
            "country"   => "French Polynesia",
            "continent" => "Oceania",
        ),
            "GA" => array(
            "country"   => "Gabon",
            "continent" => "Africa",
        ),
            "GM" => array(
            "country"   => "Gambia",
            "continent" => "Africa",
        ),
            "GE" => array(
            "country"   => "Georgia",
            "continent" => "Asia",
        ),
            "DE" => array(
            "country"   => "Germany",
            "continent" => "Europe",
        ),
            "GH" => array(
            "country"   => "Ghana",
            "continent" => "Africa",
        ),
            "GI" => array(
            "country"   => "Gibraltar",
            "continent" => "Europe",
        ),
            "GR" => array(
            "country"   => "Greece",
            "continent" => "Europe",
        ),
            "GL" => array(
            "country"   => "Greenland",
            "continent" => "North America",
        ),
            "GD" => array(
            "country"   => "Grenada",
            "continent" => "North America",
        ),
            "GP" => array(
            "country"   => "Guadeloupe",
            "continent" => "North America",
        ),
            "GU" => array(
            "country"   => "Guam",
            "continent" => "Oceania",
        ),
            "GT" => array(
            "country"   => "Guatemala",
            "continent" => "North America",
        ),
            "GG" => array(
            "country"   => "Guernsey",
            "continent" => "Europe",
        ),
            "GN" => array(
            "country"   => "Guinea",
            "continent" => "Africa",
        ),
            "GW" => array(
            "country"   => "Guinea-bissau",
            "continent" => "Africa",
        ),
            "GY" => array(
            "country"   => "Guyana",
            "continent" => "South America",
        ),
            "HT" => array(
            "country"   => "Haiti",
            "continent" => "North America",
        ),
            "VA" => array(
            "country"   => "Vatican",
            "continent" => "Europe",
        ),
            "HN" => array(
            "country"   => "Honduras",
            "continent" => "North America",
        ),
            "HK" => array(
            "country"   => "Hong Kong",
            "continent" => "Asia",
        ),
            "HU" => array(
            "country"   => "Hungary",
            "continent" => "Europe",
        ),
            "IS" => array(
            "country"   => "Iceland",
            "continent" => "Europe",
        ),
            "IN" => array(
            "country"   => "India",
            "continent" => "Asia",
        ),
            "ID" => array(
            "country"   => "Indonesia",
            "continent" => "Asia",
        ),
            "IR" => array(
            "country"   => "Iran",
            "continent" => "Asia",
        ),
            "IQ" => array(
            "country"   => "Iraq",
            "continent" => "Asia",
        ),
            "IE" => array(
            "country"   => "Ireland",
            "continent" => "Europe",
        ),
            "IM" => array(
            "country"   => "Isle of Man",
            "continent" => "Europe",
        ),
            "IL" => array(
            "country"   => "Israel",
            "continent" => "Asia",
        ),
            "IT" => array(
            "country"   => "Italy",
            "continent" => "Europe",
        ),
            "JM" => array(
            "country"   => "Jamaica",
            "continent" => "North America",
        ),
            "JP" => array(
            "country"   => "日本 (Japan)",
            "continent" => "Asia",
        ),
            "JE" => array(
            "country"   => "Jersey",
            "continent" => "Europe",
        ),
            "JO" => array(
            "country"   => "Jordan",
            "continent" => "Asia",
        ),
            "KZ" => array(
            "country"   => "Kazakhstan",
            "continent" => "Asia",
        ),
            "KE" => array(
            "country"   => "Kenya",
            "continent" => "Africa",
        ),
            "KI" => array(
            "country"   => "Kiribati",
            "continent" => "Oceania",
        ),
            "KR" => array(
            "country"   => "대한민국 (South Korea)",
            "continent" => "Asia",
        ),
            "KW" => array(
            "country"   => "Kuwait",
            "continent" => "Asia",
        ),
            "KG" => array(
            "country"   => "Kyrgyzstan",
            "continent" => "Asia",
        ),
            "LA" => array(
            "country"   => "Laos",
            "continent" => "Asia",
        ),
            "LV" => array(
            "country"   => "Latvia",
            "continent" => "Europe",
        ),
            "LB" => array(
            "country"   => "Lebanon",
            "continent" => "Asia",
        ),
            "LS" => array(
            "country"   => "Lesotho",
            "continent" => "Africa",
        ),
            "LR" => array(
            "country"   => "Liberia",
            "continent" => "Africa",
        ),
            "LY" => array(
            "country"   => "Libya",
            "continent" => "Africa",
        ),
            "LI" => array(
            "country"   => "Liechtenstein",
            "continent" => "Europe",
        ),
            "LT" => array(
            "country"   => "Lithuania",
            "continent" => "Europe",
        ),
            "LU" => array(
            "country"   => "Luxembourg",
            "continent" => "Europe",
        ),
            "MK" => array(
            "country"   => "Macedonia",
            "continent" => "Europe",
        ),
            "MG" => array(
            "country"   => "Madagascar",
            "continent" => "Africa",
        ),
            "MW" => array(
            "country"   => "Malawi",
            "continent" => "Africa",
        ),
            "MY" => array(
            "country"   => "Malaysia",
            "continent" => "Asia",
        ),
            "MV" => array(
            "country"   => "Maldives",
            "continent" => "Asia",
        ),
            "ML" => array(
            "country"   => "Mali",
            "continent" => "Africa",
        ),
            "MT" => array(
            "country"   => "Malta",
            "continent" => "Europe",
        ),
            "MH" => array(
            "country"   => "Marshall Islands",
            "continent" => "Oceania",
        ),
            "MQ" => array(
            "country"   => "Martinique",
            "continent" => "North America",
        ),
            "MR" => array(
            "country"   => "Mauritania",
            "continent" => "Africa",
        ),
            "MU" => array(
            "country"   => "Mauritius",
            "continent" => "Africa",
        ),
            "YT" => array(
            "country"   => "Mayotte",
            "continent" => "Africa",
        ),
            "MX" => array(
            "country"   => "Mexico",
            "continent" => "North America",
        ),
            "FM" => array(
            "country"   => "Micronesia",
            "continent" => "Oceania",
        ),
            "MD" => array(
            "country"   => "Moldova",
            "continent" => "Europe",
        ),
            "MC" => array(
            "country"   => "Monaco",
            "continent" => "Europe",
        ),
            "MN" => array(
            "country"   => "Mongolia",
            "continent" => "Asia",
        ),
            "ME" => array(
            "country"   => "Montenegro",
            "continent" => "Europe",
        ),
            "MS" => array(
            "country"   => "Montserrat",
            "continent" => "North America",
        ),
            "MA" => array(
            "country"   => "Morocco",
            "continent" => "Africa",
        ),
            "MZ" => array(
            "country"   => "Mozambique",
            "continent" => "Africa",
        ),
            "MM" => array(
            "country"   => "Myanmar",
            "continent" => "Asia",
        ),
            "MF" => array(
            "country"   => "Saint Martin",
            "continent" => "North America",
        ),
            "SX" => array(
            "country"   => "Sint Maarten",
            "continent" => "North America",
        ),
            "BL" => array(
            "country"   => "Saint-Barthelemy",
            "continent" => "North America",
        ),
            "NA" => array(
            "country"   => "Namibia",
            "continent" => "Africa",
        ),
            "NP" => array(
            "country"   => "Nepal",
            "continent" => "Asia",
        ),
            "NL" => array(
            "country"   => "Netherlands",
            "continent" => "Europe",
        ),
            "NC" => array(
            "country"   => "New Caledonia",
            "continent" => "Oceania",
        ),
            "NZ" => array(
            "country"   => "New Zealand",
            "continent" => "Oceania",
        ),
            "NI" => array(
            "country"   => "Nicaragua",
            "continent" => "North America",
        ),
            "NE" => array(
            "country"   => "Niger",
            "continent" => "Africa",
        ),
            "NG" => array(
            "country"   => "Nigeria",
            "continent" => "Africa",
        ),
            "MP" => array(
            "country"   => "Northern Mariana Islands",
            "continent" => "Oceania",
        ),
            "NO" => array(
            "country"   => "Norway",
            "continent" => "Europe",
        ),
            "OM" => array(
            "country"   => "Oman",
            "continent" => "Asia",
        ),
            "PK" => array(
            "country"   => "Pakistan",
            "continent" => "Asia",
        ),
            "PW" => array(
            "country"   => "Palau",
            "continent" => "Oceania",
        ),
            "PS" => array(
            "country"   => "Palestine",
            "continent" => "Asia",
        ),
            "PA" => array(
            "country"   => "Panama",
            "continent" => "North America",
        ),
            "PG" => array(
            "country"   => "Papua New Guinea",
            "continent" => "Oceania",
        ),
            "PY" => array(
            "country"   => "Paraguay",
            "continent" => "South America",
        ),
            "PE" => array(
            "country"   => "Peru",
            "continent" => "South America",
        ),
            "PH" => array(
            "country"   => "Philippines",
            "continent" => "Asia",
        ),
            "PL" => array(
            "country"   => "Poland",
            "continent" => "Europe",
        ),
            "PT" => array(
            "country"   => "Portugal",
            "continent" => "Europe",
        ),
            "PR" => array(
            "country"   => "Puerto Rico",
            "continent" => "North America",
        ),
            "QA" => array(
            "country"   => "Qatar",
            "continent" => "Asia",
        ),
            "RE" => array(
            "country"   => "Reunion",
            "continent" => "Africa",
        ),
            "RO" => array(
            "country"   => "Romania",
            "continent" => "Europe",
        ),
            "RU" => array(
            "country"   => "Россия (Russia)",
            "continent" => "Europe",
        ),
            "RW" => array(
            "country"   => "Rwanda",
            "continent" => "Africa",
        ),
            "KN" => array(
            "country"   => "Saint Kitts and Nevis",
            "continent" => "North America",
        ),
            "LC" => array(
            "country"   => "Saint Lucia",
            "continent" => "North America",
        ),
            "PM" => array(
            "country"   => "Saint Pierre and Miquelon",
            "continent" => "North America",
        ),
            "VC" => array(
            "country"   => "Saint Vincent and the Grenadines",
            "continent" => "North America",
        ),
            "WS" => array(
            "country"   => "Samoa",
            "continent" => "Oceania",
        ),
            "SM" => array(
            "country"   => "Republic of San Marino",
            "continent" => "Europe",
        ),
            "ST" => array(
            "country"   => "Sao Tome and Principe",
            "continent" => "Africa",
        ),
            "SA" => array(
            "country"   => "Saudi Arabia",
            "continent" => "Asia",
        ),
            "SN" => array(
            "country"   => "Senegal",
            "continent" => "Africa",
        ),
            "RS" => array(
            "country"   => "Serbia",
            "continent" => "Europe",
        ),
            "SC" => array(
            "country"   => "Seychelles",
            "continent" => "Africa",
        ),
            "SS" => array(
            "country"   => "South Sudan",
            "continent" => "Africa",
        ),
            "SL" => array(
            "country"   => "Sierra Leone",
            "continent" => "Africa",
        ),
            "SG" => array(
            "country"   => "Singapore",
            "continent" => "Asia",
        ),
            "SK" => array(
            "country"   => "Slovakia",
            "continent" => "Europe",
        ),
            "SI" => array(
            "country"   => "Slovenia",
            "continent" => "Europe",
        ),
            "SB" => array(
            "country"   => "Solomon Islands",
            "continent" => "Oceania",
        ),
            "SO" => array(
            "country"   => "Somalia",
            "continent" => "Africa",
        ),
            "ZA" => array(
            "country"   => "South Africa",
            "continent" => "Africa",
        ),
            "ES" => array(
            "country"   => "Spain",
            "continent" => "Europe",
        ),
            "LK" => array(
            "country"   => "Sri Lanka",
            "continent" => "Asia",
        ),
            "SD" => array(
            "country"   => "Sudan",
            "continent" => "Africa",
        ),
            "SR" => array(
            "country"   => "Suriname",
            "continent" => "South America",
        ),
            "SZ" => array(
            "country"   => "Swaziland",
            "continent" => "Africa",
        ),
            "SE" => array(
            "country"   => "Sweden",
            "continent" => "Europe",
        ),
            "CH" => array(
            "country"   => "Switzerland",
            "continent" => "Europe",
        ),
            "SY" => array(
            "country"   => "Syria",
            "continent" => "Asia",
        ),
            "TW" => array(
            "country"   => "Taiwan",
            "continent" => "Asia",
        ),
            "TJ" => array(
            "country"   => "Tajikistan",
            "continent" => "Asia",
        ),
            "TZ" => array(
            "country"   => "Tanzania",
            "continent" => "Africa",
        ),
            "TH" => array(
            "country"   => "Thailand",
            "continent" => "Asia",
        ),
            "TL" => array(
            "country"   => "Timor-leste",
            "continent" => "Asia",
        ),
            "TG" => array(
            "country"   => "Togo",
            "continent" => "Africa",
        ),
            "TK" => array(
            "country"   => "Tokelau",
            "continent" => "Oceania",
        ),
            "TO" => array(
            "country"   => "Tonga",
            "continent" => "Oceania",
        ),
            "TT" => array(
            "country"   => "Trinidad and Tobago",
            "continent" => "North America",
        ),
            "TN" => array(
            "country"   => "Tunisia",
            "continent" => "Africa",
        ),
            "TR" => array(
            "country"   => "Turkey",
            "continent" => "Asia",
        ),
            "TM" => array(
            "country"   => "Turkmenistan",
            "continent" => "Asia",
        ),
            "TC" => array(
            "country"   => "Turks and Caicos Islands",
            "continent" => "North America",
        ),
            "TV" => array(
            "country"   => "Tuvalu",
            "continent" => "Oceania",
        ),
            "UG" => array(
            "country"   => "Uganda",
            "continent" => "Africa",
        ),
            "UA" => array(
            "country"   => "Ukraine",
            "continent" => "Europe",
        ),
            "AE" => array(
            "country"   => "United Arab Emirates",
            "continent" => "Asia",
        ),
            "GB" => array(
            "country"   => "United Kingdom",
            "continent" => "Europe",
        ),
            "US" => array(
            "country"   => "United States",
            "continent" => "North America",
        ),
            "UY" => array(
            "country"   => "Uruguay",
            "continent" => "South America",
        ),
            "UZ" => array(
            "country"   => "Uzbekistan",
            "continent" => "Asia",
        ),
            "VU" => array(
            "country"   => "Vanuatu",
            "continent" => "Oceania",
        ),
            "VE" => array(
            "country"   => "Venezuela",
            "continent" => "South America",
        ),
            "VN" => array(
            "country"   => "Vietnam",
            "continent" => "Asia",
        ),
            "VG" => array(
            "country"   => "Virgin Islands, British",
            "continent" => "North America",
        ),
            "VI" => array(
            "country"   => "Virgin Islands (US)",
            "continent" => "North America",
        ),
            "WF" => array(
            "country"   => "Wallis and Futuna",
            "continent" => "Oceania",
        ),
            "EH" => array(
            "country"   => "Western Sahara",
            "continent" => "Africa",
        ),
            "YE" => array(
            "country"   => "Yemen",
            "continent" => "Asia",
        ),
            "ZM" => array(
            "country"   => "Zambia",
            "continent" => "Africa",
        ),
            "ZW" => array(
            "country"   => "Zimbabwe",
            "continent" => "Africa",
        ),
        );
        return $countries;
    }

}
if ( !function_exists( 'wp_radio_get_user_ip' ) ) {
    /**
     * Get the visitor ip address
     *
     * @return string
     * @since 2.0.2.1
     *
     */
    function wp_radio_get_user_ip()
    {
        
        if ( !empty($_SERVER['HTTP_CLIENT_IP']) ) {
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif ( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        return $ip;
    }

}
function wp_radio_get_settings( $key, $default = '' )
{
    $settings = get_option( 'wp_radio_settings' );
    $value = ( isset( $settings[$key] ) ? $settings[$key] : $default );
    
    if ( $value == 'true' ) {
        $value = true;
    } elseif ( $value == 'false' ) {
        $value = false;
    }
    
    return $value;
}

if ( !function_exists( 'wp_radio_get_var' ) ) {
    /**
     * Get data if set, otherwise return a default value or null. Prevents notices when data is not set.
     *
     * @param mixed $var Variable.
     * @param string $default Default value.
     *
     * @return mixed
     * @since  3.2.0
     */
    function wp_radio_get_var( &$var, $default = null )
    {
        return ( isset( $var ) ? $var : $default );
    }

}
if ( !function_exists( 'wp_radio_get_station_country' ) ) {
    function wp_radio_get_station_country( $post_id )
    {
        $country = get_the_terms( $post_id, 'radio_country' );
        return ( !empty($country[0]) ? $country[0] : false );
    }

}
function wp_radio_localize_array()
{
    $data = [
        'pluginUrl' => WP_RADIO_URL,
        'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
        'siteUrl'   => site_url(),
        'popupURL'  => str_replace( 'https', 'http', site_url() ),
        'nonce'     => wp_create_nonce( 'wp_rest' ),
        'isPro'     => wr_fs()->can_use_premium_code__premium_only(),
        'settings'  => get_option( 'wp_radio_settings' ),
    ];
    
    if ( is_admin() ) {
        $admin_data = [
            'pricingPage'        => wr_fs()->get_upgrade_url(),
            'adminUrl'           => admin_url(),
            'imported_countries' => get_option( 'wp_radio_imported_countries' ),
        ];
        $data = array_merge( $data, $admin_data );
    }
    
    return $data;
}

function wp_radio_get_station_location( $post_id )
{
    $terms = wp_get_post_terms( $post_id, 'radio_country', [
        'orderby' => 'parent',
        'order'   => 'ASC',
    ] );
    $location = [];
    
    if ( !empty($terms) ) {
        $location['country'] = ( !empty($terms[0]) ? $terms[0] : '' );
        $location['region'] = ( !empty($terms[1]) ? $terms[1] : '' );
        $location['city'] = ( !empty($terms[2]) ? $terms[2] : '' );
    }
    
    return $location;
}

function wp_radio_get_location( $post_id )
{
    $terms = wp_get_post_terms( $post_id, 'radio_country', [
        'orderby' => 'parent',
        'order'   => 'ASC',
    ] );
    $location = [];
    
    if ( !empty($terms) ) {
        $location['country'] = ( !empty($terms[0]) ? $terms[0] : '' );
        $location['region'] = ( !empty($terms[1]) ? $terms[1] : '' );
        $location['city'] = ( !empty($terms[2]) ? $terms[2] : '' );
    }
    
    $items = [];
    if ( !empty($location) ) {
        foreach ( array_filter( $location ) as $key => $loc ) {
            $items[$key] = [
                'name' => wp_radio_escape_quote( $loc->name ),
                'slug' => $loc->slug,
                'link' => get_term_link( $loc->term_id ),
            ];
        }
    }
    return $items;
}

function wp_radio_location_html( $post_id, $return = false )
{
    $location = wp_radio_get_station_location( $post_id );
    extract( $location );
    $html = '';
    if ( !empty($country) ) {
        $html .= sprintf( '<a href="%1$s" class="radio-country-link">%2$s</a>', get_term_link( $country ), wp_radio_get_country_flag( $country->slug ) );
    }
    if ( !empty($city) ) {
        $html .= sprintf( '<a href="%1$s" class="radio-country-link">%2$s,</a>', get_term_link( $city ), $city->name );
    }
    if ( !empty($region) ) {
        $html .= sprintf( '<a href="%1$s" class="radio-country-link">%2$s,</a>', get_term_link( $region ), $region->name );
    }
    if ( !empty($country) ) {
        $html .= sprintf( '<a href="%1$s" class="radio-country-link">%2$s</a>', get_term_link( $country ), $country->name );
    }
    $html = sprintf( '<div class="radio-country"><span>Country: </span>%s</div>', $html );
    if ( $return ) {
        return $html;
    }
    echo  $html ;
}

function wp_radio_get_genres( $post_id )
{
    $terms = wp_get_post_terms( $post_id, 'radio_genre' );
    $genres = [];
    if ( !empty($terms) ) {
        foreach ( $terms as $term ) {
            $genres[] = [
                'id'   => $term->term_id,
                'link' => get_term_link( $term->term_id ),
                'name' => wp_radio_escape_quote( $term->name ),
            ];
        }
    }
    return $genres;
}

function wp_radio_country_station_map()
{
    $options = [
        'af' => [
        'count' => '7',
        'label' => ' Afghanistan',
    ],
        'as' => [
        'count' => '2',
        'label' => ' American Samoa',
    ],
        'ad' => [
        'count' => '4',
        'label' => ' Andorra',
    ],
        'ao' => [
        'count' => '15',
        'label' => ' Angola',
    ],
        'ai' => [
        'count' => '3',
        'label' => ' Anguilla',
    ],
        'ag' => [
        'count' => '13',
        'label' => ' Antigua and Barbuda',
    ],
        'aw' => [
        'count' => '8',
        'label' => ' Aruba',
    ],
        'az' => [
        'count' => '14',
        'label' => ' Azerbaijan',
    ],
        'bh' => [
        'count' => '1',
        'label' => ' Bahrain',
    ],
        'bb' => [
        'count' => '12',
        'label' => ' Barbados',
    ],
        'bz' => [
        'count' => '8',
        'label' => ' Belize',
    ],
        'bj' => [
        'count' => '6',
        'label' => ' Benin',
    ],
        'bm' => [
        'count' => '10',
        'label' => ' Bermuda',
    ],
        'bw' => [
        'count' => '6',
        'label' => ' Botswana',
    ],
        'bn' => [
        'count' => '7',
        'label' => ' Brunei',
    ],
        'bi' => [
        'count' => '4',
        'label' => ' Burundi',
    ],
        'kh' => [
        'count' => '10',
        'label' => ' Cambodia',
    ],
        'cm' => [
        'count' => '4',
        'label' => ' Cameroon',
    ],
        'cv' => [
        'count' => '5',
        'label' => ' Cape Verde',
    ],
        'ky' => [
        'count' => '7',
        'label' => ' Cayman Islands',
    ],
        'cf' => [
        'count' => '1',
        'label' => ' Central African Republic',
    ],
        'cg' => [
        'count' => '2',
        'label' => ' Congo',
    ],
        'cu' => [
        'count' => '16',
        'label' => ' Cuba',
    ],
        'dm' => [
        'count' => '10',
        'label' => ' Dominica',
    ],
        'cd' => [
        'count' => '12',
        'label' => ' DR Congo',
    ],
        'et' => [
        'count' => '8',
        'label' => ' Ethiopia',
    ],
        'fk' => [
        'count' => '1',
        'label' => ' Falkland Islands',
    ],
        'fo' => [
        'count' => '4',
        'label' => ' Faroe Islands, Denmark',
    ],
        'pf' => [
        'count' => '3',
        'label' => ' French Polynesia',
    ],
        'ga' => [
        'count' => '1',
        'label' => ' Gabon',
    ],
        'gm' => [
        'count' => '4',
        'label' => ' Gambia',
    ],
        'ge' => [
        'count' => '12',
        'label' => ' Georgia',
        'isPro' => true,
    ],
        'gd' => [
        'count' => '15',
        'label' => ' Grenada',
        'isPro' => true,
    ],
        'gp' => [
        'count' => '7',
        'label' => ' Guadeloupe',
        'isPro' => true,
    ],
        'gu' => [
        'count' => '12',
        'label' => ' Guam',
        'isPro' => true,
    ],
        'gi' => [
        'count' => '5',
        'label' => ' Gibraltar',
        'isPro' => true,
    ],
        'gg' => [
        'count' => '1',
        'label' => ' Guernsey',
        'isPro' => true,
    ],
        'gn' => [
        'count' => '8',
        'label' => ' Guinea',
        'isPro' => true,
    ],
        'gy' => [
        'count' => '10',
        'label' => ' Guyana',
        'isPro' => true,
    ],
        'al' => [
        'count' => '39',
        'label' => ' Albania',
        'isPro' => true,
    ],
        'dz' => [
        'count' => '63',
        'label' => ' Algeria',
        'isPro' => true,
    ],
        'am' => [
        'count' => '18',
        'label' => ' Armenia',
        'isPro' => true,
    ],
        'bs' => [
        'count' => '20',
        'label' => ' Bahamas',
        'isPro' => true,
    ],
        'bd' => [
        'count' => '24',
        'label' => ' Bangladesh',
        'isPro' => true,
    ],
        'by' => [
        'count' => '27',
        'label' => ' Belarus',
        'isPro' => true,
    ],
        'ar' => [
        'count' => '1557',
        'label' => ' Argentina',
        'isPro' => true,
    ],
        'au' => [
        'count' => '698',
        'label' => ' Australia',
        'isPro' => true,
    ],
        'at' => [
        'count' => '162',
        'label' => ' Austria',
        'isPro' => true,
    ],
        'be' => [
        'count' => '349',
        'label' => ' Belgium',
        'isPro' => true,
    ],
        'bo' => [
        'count' => '101',
        'label' => ' Bolivia',
        'isPro' => true,
    ],
        'ba' => [
        'count' => '94',
        'label' => ' Bosnia and Herzegovina',
        'isPro' => true,
    ],
        'br' => [
        'count' => '3333',
        'label' => ' Brazil',
        'isPro' => true,
    ],
        'bg' => [
        'count' => '71',
        'label' => ' Bulgaria',
        'isPro' => true,
    ],
        'ca' => [
        'count' => '1840',
        'label' => ' Canada',
        'isPro' => true,
    ],
        'cl' => [
        'count' => '623',
        'label' => ' Chile',
        'isPro' => true,
    ],
        'cn' => [
        'count' => '150',
        'label' => ' China',
        'isPro' => true,
    ],
        'co' => [
        'count' => '740',
        'label' => ' Colombia',
        'isPro' => true,
    ],
        'cr' => [
        'count' => '99',
        'label' => ' Costa Rica',
        'isPro' => true,
    ],
        'hr' => [
        'count' => '123',
        'label' => ' Croatia',
        'isPro' => true,
    ],
        'cy' => [
        'count' => '55',
        'label' => ' Cyprus',
        'isPro' => true,
    ],
        'cz' => [
        'count' => '155',
        'label' => ' Czech Republic',
        'isPro' => true,
    ],
        'dk' => [
        'count' => '134',
        'label' => ' Denmark',
        'isPro' => true,
    ],
        'do' => [
        'count' => '287',
        'label' => ' Dominican Republic',
        'isPro' => true,
    ],
        'ec' => [
        'count' => '288',
        'label' => ' Ecuador',
        'isPro' => true,
    ],
        'eg' => [
        'count' => '38',
        'label' => ' Egypt',
        'isPro' => true,
    ],
        'sv' => [
        'count' => '120',
        'label' => ' El Salvador',
        'isPro' => true,
    ],
        'ee' => [
        'count' => '49',
        'label' => ' Estonia',
        'isPro' => true,
    ],
        'fj' => [
        'count' => '11',
        'label' => ' Fiji',
        'isPro' => true,
    ],
        'fi' => [
        'count' => '67',
        'label' => ' Finland',
        'isPro' => true,
    ],
        'fr' => [
        'count' => '2080',
        'label' => ' France',
        'isPro' => true,
    ],
        'de' => [
        'count' => '2919',
        'label' => ' Germany',
        'isPro' => true,
    ],
        'gh' => [
        'count' => '160',
        'label' => ' Ghana',
        'isPro' => true,
    ],
        'gr' => [
        'count' => '735',
        'label' => ' Greece',
        'isPro' => true,
    ],
        'gt' => [
        'count' => '282',
        'label' => ' Guatemala',
        'isPro' => true,
    ],
        'ht' => [
        'count' => '194',
        'label' => ' Haiti',
        'isPro' => true,
    ],
        'hn' => [
        'count' => '92',
        'label' => ' Honduras',
        'isPro' => true,
    ],
        'hu' => [
        'count' => '155',
        'label' => ' Hungary',
        'isPro' => true,
    ],
        'is' => [
        'count' => '16',
        'label' => ' Iceland',
        'isPro' => true,
    ],
        'in' => [
        'count' => '329',
        'label' => ' India',
        'isPro' => true,
    ],
        'id' => [
        'count' => '561',
        'label' => ' Indonesia',
        'isPro' => true,
    ],
        'ir' => [
        'count' => '19',
        'label' => ' Iran',
        'isPro' => true,
    ],
        'iq' => [
        'count' => '17',
        'label' => ' Iraq',
        'isPro' => true,
    ],
        'ie' => [
        'count' => '190',
        'label' => ' Ireland',
        'isPro' => true,
    ],
        'il' => [
        'count' => '108',
        'label' => ' Israel',
        'isPro' => true,
    ],
        'it' => [
        'count' => '1476',
        'label' => ' Italy',
        'isPro' => true,
    ],
        'ci' => [
        'count' => '10',
        'label' => ' Ivory Coast',
        'isPro' => true,
    ],
        'jm' => [
        'count' => '40',
        'label' => ' Jamaica',
        'isPro' => true,
    ],
        'jo' => [
        'count' => '16',
        'label' => ' Jordan',
        'isPro' => true,
    ],
        'kz' => [
        'count' => '16',
        'label' => ' Kazakhstan',
        'isPro' => true,
    ],
        'ke' => [
        'count' => '92',
        'label' => ' Kenya',
        'isPro' => true,
    ],
        'xk' => [
        'count' => '17',
        'label' => ' Kosovo',
        'isPro' => true,
    ],
        'kw' => [
        'count' => '113',
        'label' => ' Kuwait',
        'isPro' => true,
    ],
        'kg' => [
        'count' => '3',
        'label' => ' Kyrgyzstan',
        'isPro' => true,
    ],
        'la' => [
        'count' => '1',
        'label' => ' Laos',
        'isPro' => true,
    ],
        'lv' => [
        'count' => '40',
        'label' => ' Latvia',
        'isPro' => true,
    ],
        'lb' => [
        'count' => '52',
        'label' => ' Lebanon',
        'isPro' => true,
    ],
        'ls' => [
        'count' => '5',
        'label' => ' Lesotho',
        'isPro' => true,
    ],
        'lr' => [
        'count' => '2',
        'label' => ' Liberia',
        'isPro' => true,
    ],
        'ly' => [
        'count' => '5',
        'label' => ' Libya',
        'isPro' => true,
    ],
        'li' => [
        'count' => '2',
        'label' => ' Liechtenstein',
        'isPro' => true,
    ],
        'lt' => [
        'count' => '42',
        'label' => ' Lithuania',
        'isPro' => true,
    ],
        'lu' => [
        'count' => '22',
        'label' => ' Luxembourg',
        'isPro' => true,
    ],
        'mk' => [
        'count' => '38',
        'label' => ' Macedonia',
        'isPro' => true,
    ],
        'mg' => [
        'count' => '19',
        'label' => ' Madagascar',
        'isPro' => true,
    ],
        'mw' => [
        'count' => '7',
        'label' => ' Malawi',
        'isPro' => true,
    ],
        'my' => [
        'count' => '96',
        'label' => ' Malaysia',
        'isPro' => true,
    ],
        'mv' => [
        'count' => '3',
        'label' => ' Maldives',
        'isPro' => true,
    ],
        'ml' => [
        'count' => '6',
        'label' => ' Mali',
        'isPro' => true,
    ],
        'mt' => [
        'count' => '29',
        'label' => ' Malta',
        'isPro' => true,
    ],
        'mr' => [
        'count' => '1',
        'label' => ' Mauritania',
        'isPro' => true,
    ],
        'mu' => [
        'count' => '18',
        'label' => ' Mauritius',
        'isPro' => true,
    ],
        'mx' => [
        'count' => '1717',
        'label' => ' Mexico',
        'isPro' => true,
    ],
        'fm' => [
        'count' => '1',
        'label' => ' Micronesia',
        'isPro' => true,
    ],
        'md' => [
        'count' => '23',
        'label' => ' Moldova',
        'isPro' => true,
    ],
        'mc' => [
        'count' => '7',
        'label' => ' Monaco',
        'isPro' => true,
    ],
        'mn' => [
        'count' => '2',
        'label' => ' Mongolia',
        'isPro' => true,
    ],
        'me' => [
        'count' => '17',
        'label' => ' Montenegro',
        'isPro' => true,
    ],
        'ms' => [
        'count' => '1',
        'label' => ' Montserrat',
        'isPro' => true,
    ],
        'ma' => [
        'count' => '44',
        'label' => ' Morocco',
        'isPro' => true,
    ],
        'mz' => [
        'count' => '4',
        'label' => ' Mozambique',
        'isPro' => true,
    ],
        'na' => [
        'count' => '24',
        'label' => ' Namibia',
        'isPro' => true,
    ],
        'np' => [
        'count' => '75',
        'label' => ' Nepal',
        'isPro' => true,
    ],
        'nl' => [
        'count' => '1080',
        'label' => ' Netherlands',
        'isPro' => true,
    ],
        'nz' => [
        'count' => '275',
        'label' => ' New Zealand',
        'isPro' => true,
    ],
        'ni' => [
        'count' => '85',
        'label' => ' Nicaragua',
        'isPro' => true,
    ],
        'ng' => [
        'count' => '161',
        'label' => ' Nigeria',
        'isPro' => true,
    ],
        'mp' => [
        'count' => '2',
        'label' => ' Northern Mariana Islands',
        'isPro' => true,
    ],
        'no' => [
        'count' => '148',
        'label' => ' Norway',
        'isPro' => true,
    ],
        'om' => [
        'count' => '11',
        'label' => ' Oman',
        'isPro' => true,
    ],
        'pk' => [
        'count' => '66',
        'label' => ' Pakistan',
        'isPro' => true,
    ],
        'ps' => [
        'count' => '10',
        'label' => ' Palestine',
        'isPro' => true,
    ],
        'pa' => [
        'count' => '77',
        'label' => ' Panama',
        'isPro' => true,
    ],
        'pg' => [
        'count' => '2',
        'label' => ' Papua New Guinea',
        'isPro' => true,
    ],
        'py' => [
        'count' => '72',
        'label' => ' Paraguay',
        'isPro' => true,
    ],
        'pe' => [
        'count' => '431',
        'label' => ' Peru',
        'isPro' => true,
    ],
        'ph' => [
        'count' => '227',
        'label' => ' Philippines',
        'isPro' => true,
    ],
        'pl' => [
        'count' => '515',
        'label' => ' Poland',
        'isPro' => true,
    ],
        'pt' => [
        'count' => '322',
        'label' => ' Portugal',
        'isPro' => true,
    ],
        'pr' => [
        'count' => '152',
        'label' => ' Puerto Rico',
        'isPro' => true,
    ],
        'qa' => [
        'count' => '14',
        'label' => ' Qatar',
        'isPro' => true,
    ],
        'sm' => [
        'count' => '2',
        'label' => ' Republic of San Marino',
        'isPro' => true,
    ],
        'ro' => [
        'count' => '336',
        'label' => ' Romania',
        'isPro' => true,
    ],
        'rw' => [
        'count' => '15',
        'label' => ' Rwanda',
        'isPro' => true,
    ],
        'kn' => [
        'count' => '5',
        'label' => ' Saint Kitts and Nevis',
        'isPro' => true,
    ],
        'lc' => [
        'count' => '16',
        'label' => ' Saint Lucia',
        'isPro' => true,
    ],
        'mf' => [
        'count' => '3',
        'label' => ' Saint Martin',
        'isPro' => true,
    ],
        'vc' => [
        'count' => '15',
        'label' => ' Saint Vincent and the Grenadines',
        'isPro' => true,
    ],
        'sa' => [
        'count' => '24',
        'label' => ' Saudi Arabia',
        'isPro' => true,
    ],
        'sn' => [
        'count' => '29',
        'label' => ' Senegal',
        'isPro' => true,
    ],
        'rs' => [
        'count' => '160',
        'label' => ' Serbia',
        'isPro' => true,
    ],
        'sc' => [
        'count' => '14',
        'label' => ' Seychelles',
        'isPro' => true,
    ],
        'sl' => [
        'count' => '2',
        'label' => ' Sierra Leone',
        'isPro' => true,
    ],
        'sg' => [
        'count' => '28',
        'label' => ' Singapore',
        'isPro' => true,
    ],
        'sx' => [
        'count' => '1',
        'label' => ' Sint Maarten',
        'isPro' => true,
    ],
        'sk' => [
        'count' => '51',
        'label' => ' Slovakia',
        'isPro' => true,
    ],
        'si' => [
        'count' => '85',
        'label' => ' Slovenia',
        'isPro' => true,
    ],
        'za' => [
        'count' => '327',
        'label' => ' South Africa',
        'isPro' => true,
    ],
        'es' => [
        'count' => '1656',
        'label' => ' Spain',
        'isPro' => true,
    ],
        'lk' => [
        'count' => '72',
        'label' => ' Sri Lanka',
        'isPro' => true,
    ],
        'sd' => [
        'count' => '6',
        'label' => ' Sudan',
        'isPro' => true,
    ],
        'sr' => [
        'count' => '28',
        'label' => ' Suriname',
        'isPro' => true,
    ],
        'sz' => [
        'count' => '2',
        'label' => ' Swaziland',
        'isPro' => true,
    ],
        'se' => [
        'count' => '221',
        'label' => ' Sweden',
        'isPro' => true,
    ],
        'ch' => [
        'count' => '483',
        'label' => ' Switzerland',
        'isPro' => true,
    ],
        'sy' => [
        'count' => '9',
        'label' => ' Syria',
        'isPro' => true,
    ],
        'tw' => [
        'count' => '46',
        'label' => ' Taiwan',
        'isPro' => true,
    ],
        'tj' => [
        'count' => '1',
        'label' => ' Tajikistan',
        'isPro' => true,
    ],
        'tz' => [
        'count' => '26',
        'label' => ' Tanzania',
        'isPro' => true,
    ],
        'th' => [
        'count' => '126',
        'label' => ' Thailand',
        'isPro' => true,
    ],
        'tg' => [
        'count' => '8',
        'label' => ' Togo',
        'isPro' => true,
    ],
        'to' => [
        'count' => '2',
        'label' => ' Tonga',
        'isPro' => true,
    ],
        'tt' => [
        'count' => '48',
        'label' => ' Trinidad and Tobago',
        'isPro' => true,
    ],
        'tn' => [
        'count' => '39',
        'label' => ' Tunisia',
        'isPro' => true,
    ],
        'tr' => [
        'count' => '384',
        'label' => ' Turkey',
        'isPro' => true,
    ],
        'ug' => [
        'count' => '51',
        'label' => ' Uganda',
        'isPro' => true,
    ],
        'ua' => [
        'count' => '137',
        'label' => ' Ukraine',
        'isPro' => true,
    ],
        'ae' => [
        'count' => '54',
        'label' => ' United Arab Emirates',
        'isPro' => true,
    ],
        'gb' => [
        'count' => '2297',
        'label' => ' United Kingdom',
        'isPro' => true,
    ],
        'us' => [
        'count' => '18186',
        'label' => ' United States',
        'isPro' => true,
    ],
        'uy' => [
        'count' => '178',
        'label' => ' Uruguay',
        'isPro' => true,
    ],
        'uz' => [
        'count' => '4',
        'label' => ' Uzbekistan',
        'isPro' => true,
    ],
        'vu' => [
        'count' => '2',
        'label' => ' Vanuatu',
        'isPro' => true,
    ],
        'va' => [
        'count' => '7',
        'label' => ' Vatican',
        'isPro' => true,
    ],
        've' => [
        'count' => '198',
        'label' => ' Venezuela',
        'isPro' => true,
    ],
        'vn' => [
        'count' => '4',
        'label' => ' Vietnam',
        'isPro' => true,
    ],
        'vi' => [
        'count' => '20',
        'label' => ' Virgin Islands (US)',
        'isPro' => true,
    ],
        'ww' => [
        'count' => '64',
        'label' => 'Web',
        'isPro' => true,
    ],
        'ye' => [
        'count' => '3',
        'label' => ' Yemen',
        'isPro' => true,
    ],
        'zm' => [
        'count' => '13',
        'label' => ' Zambia',
        'isPro' => true,
    ],
        'zw' => [
        'count' => '12',
        'label' => ' Zimbabwe',
        'isPro' => true,
    ],
        'ru' => [
        'count' => '533',
        'label' => ' Россия (Russia)',
        'isPro' => true,
    ],
        'jp' => [
        'count' => '357',
        'label' => ' 日本 (Japan)',
        'isPro' => true,
    ],
        'kr' => [
        'count' => '89',
        'label' => ' 대한민국 (South Korea)',
        'isPro' => true,
    ],
    ];
    return $options;
}

function wp_radio_get_related_stations( $post_id )
{
    $is_grid = 'grid' == wp_radio_get_settings( 'listing_view', '' );
    $args = [
        'posts_per_page' => ( $is_grid ? 4 : 3 ),
        'orderby'        => 'rand',
        'post__not_in'   => [ $post_id ],
        'tax_query'      => [
        'relation' => 'AND',
    ],
    ];
    $location = wp_radio_get_station_location( $post_id );
    
    if ( !empty($location['country']) ) {
        $country = $location['country'];
        $args['tax_query'][] = [
            'taxonomy' => 'radio_country',
            'field'    => 'term_id',
            'terms'    => $country->term_id,
        ];
    }
    
    $genres = wp_get_post_terms( $post_id, 'radio_genre' );
    $genres = wp_list_pluck( $genres, 'term_id' );
    if ( !empty($genres) ) {
        $args['tax_query'][] = [
            'taxonomy' => 'radio_genre',
            'field'    => 'term_id',
            'terms'    => $genres,
        ];
    }
    $related = wp_radio_get_stations( $args );
    $items = [];
    if ( !empty($related) ) {
        foreach ( $related as $post ) {
            $post_id = $post->ID;
            $item = [];
            $item['id'] = $post_id;
            $item['title'] = get_the_title( $post_id );
            $item['slogan'] = wp_radio_get_meta( $post_id, 'slogan' );
            $item['content'] = get_post_field( 'post_content', $post_id );
            $item['link'] = get_the_permalink( $post_id );
            $item['thumbnail'] = wp_radio_get_meta( $post_id, 'logo', WP_RADIO_ASSETS . '/images/placeholder.jpg' );
            $item['stream'] = apply_filters( 'wp_radio/stream_url', wp_radio_get_meta( $post_id, 'stream_url' ), $post_id );
            $item['locations'] = wp_radio_get_location( $post_id );
            $item['genres'] = wp_radio_get_genres( $post_id );
            $item['website'] = wp_radio_get_meta( $post_id, 'website' );
            $item['facebook'] = wp_radio_get_meta( $post_id, 'facebook' );
            $item['twitter'] = wp_radio_get_meta( $post_id, 'twitter' );
            $item['wikipedia'] = wp_radio_get_meta( $post_id, 'wikipedia' );
            $item['contacts'] = array_filter( [
                'Address' => wp_radio_get_meta( $post_id, 'address' ),
                'Email'   => wp_radio_get_meta( $post_id, 'email' ),
                'Phone'   => wp_radio_get_meta( $post_id, 'phone' ),
            ] );
            $items[] = $item;
        }
    }
    return $items;
}

function wp_radio_get_regions( $country )
{
    $term = get_term_by( 'slug', $country, 'radio_country' );
    if ( !$term ) {
        return;
    }
    $terms = get_terms( 'radio_country', array(
        'parent'  => $term->term_id,
        'orderby' => 'slug',
    ) );
    $items = [];
    if ( !empty($terms) ) {
        foreach ( $terms as $term ) {
            $items[] = [
                'name' => wp_radio_escape_quote( $term->name ),
                'slug' => $term->slug,
                'link' => get_term_link( $term->term_id ),
            ];
        }
    }
    return $items;
}

function wp_radio_get_listing_items( $query )
{
    $items = [];
    if ( !empty($query->posts) ) {
        foreach ( $query->posts as $post ) {
            $post_id = $post->ID;
            $items[] = wp_radio_get_station_data( $post_id );
        }
    }
    return $items;
}

function wp_radio_get_station_data( $post_id )
{
    $item = [];
    $title = wp_radio_escape_quote( get_the_title( $post_id ) );
    $content = wp_radio_escape_quote( get_post_field( 'post_content', $post_id ) );
    $item['id'] = $post_id;
    $item['title'] = $title;
    $item['content'] = $content;
    $item['slogan'] = wp_radio_get_meta( $post_id, 'slogan' );
    $item['link'] = get_the_permalink( $post_id );
    $item['thumbnail'] = wp_radio_get_meta( $post_id, 'logo', WP_RADIO_ASSETS . '/images/placeholder.jpg' );
    $item['stream'] = apply_filters( 'wp_radio/stream_url', wp_radio_get_meta( $post_id, 'stream_url' ), $post_id );
    $item['locations'] = wp_radio_get_location( $post_id );
    $item['genres'] = wp_radio_get_genres( $post_id );
    $item['website'] = wp_radio_get_meta( $post_id, 'website' );
    $item['facebook'] = wp_radio_get_meta( $post_id, 'facebook' );
    $item['twitter'] = wp_radio_get_meta( $post_id, 'twitter' );
    $item['wikipedia'] = wp_radio_get_meta( $post_id, 'wikipedia' );
    $item['contacts'] = array_filter( [
        'Address' => wp_radio_get_meta( $post_id, 'address' ),
        'Email'   => wp_radio_get_meta( $post_id, 'email' ),
        'Phone'   => wp_radio_get_meta( $post_id, 'phone' ),
    ] );
    return $item;
}

function wp_radio_get_playlist_items( $post_id )
{
    $url = wp_radio_get_meta( $post_id, 'stream_url' );
    
    if ( preg_match( '/(?<server>^https?:\\/\\/.*:\\d+\\/)/', $url, $match ) ) {
        $played_url = $match['server'] . 'played.html';
        $items = [];
        try {
            $data = wp_radio_url_get_contents( $played_url );
            if ( is_wp_error( $data ) ) {
                return [];
            }
            $dom = new DOMDocument();
            libxml_use_internal_errors( true );
            @$dom->loadHTML( $data );
            libxml_clear_errors();
            $xp = new DOMXPath( $dom );
            $col = $xp->query( '//table[2]/tr/td' );
            $times = [];
            $tracks = [];
            $time = true;
            if ( !empty($col) ) {
                for ( $i = 2 ;  $i < $col->count() ;  $i++ ) {
                    
                    if ( $i == 4 ) {
                        $time = true;
                        continue;
                    }
                    
                    
                    if ( $time ) {
                        $times[] = sanitize_text_field( $col->item( $i )->textContent );
                    } else {
                        $tracks[] = sanitize_text_field( $col->item( $i )->textContent );
                    }
                    
                    $time = !$time;
                }
            }
            if ( !empty($times) ) {
                foreach ( $times as $key => $time ) {
                    if ( empty($tracks[$key]) ) {
                        continue;
                    }
                    $track = $tracks[$key];
                    $track = preg_replace( '/\\[.+\\]/', '', $track );
                    $items[$time] = $track;
                }
            }
        } catch ( Exception $exception ) {
        }
        return $items;
    }

}

function wp_radio_escape_quote( $string )
{
    return preg_replace( '/["\']/', '', $string );
}

function wp_radio_upload_file_image( $uploaded_file )
{
    if ( !function_exists( 'wp_handle_upload' ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
    }
    $upload_overrides = array(
        'test_form' => false,
    );
    $move_file = wp_handle_upload( $uploaded_file, $upload_overrides );
    
    if ( $move_file ) {
        $file = $move_file['file'];
        $upload_id = wp_insert_attachment( array(
            'guid'           => $move_file['url'],
            'post_mime_type' => $move_file['type'],
            'post_content'   => '',
            'post_status'    => 'inherit',
        ), $file, 0 );
        if ( !function_exists( 'wp_generate_attachment_metadata' ) ) {
            require_once ABSPATH . 'wp-admin/includes/image.php';
        }
        $attach_data = wp_generate_attachment_metadata( $upload_id, $move_file['file'] );
        wp_update_attachment_metadata( $upload_id, $attach_data );
    }
    
    return ( !empty($upload_id) ? $upload_id : false );
}

function wp_radio_url_get_contents(
    $url,
    $useragent = 'cURL',
    $headers = false,
    $follow_redirects = true,
    $debug = false
)
{
    // initialise the CURL library
    $ch = curl_init();
    // specify the URL to be retrieved
    curl_setopt( $ch, CURLOPT_URL, $url );
    // we want to get the contents of the URL and store it in a variable
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    // specify the useragent: this is a required courtesy to site owners
    curl_setopt( $ch, CURLOPT_USERAGENT, $useragent );
    // ignore SSL errors
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
    // return headers as requested
    if ( $headers == true ) {
        curl_setopt( $ch, CURLOPT_HEADER, 1 );
    }
    // only return headers
    if ( $headers == 'headers only' ) {
        curl_setopt( $ch, CURLOPT_NOBODY, 1 );
    }
    // follow redirects - note this is disabled by default in most PHP installs from 4.4.4 up
    if ( $follow_redirects == true ) {
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
    }
    // if debugging, return an array with CURL's debug info and the URL contents
    
    if ( $debug == true ) {
        $result['contents'] = curl_exec( $ch );
        $result['info'] = curl_getinfo( $ch );
    } else {
        $result = curl_exec( $ch );
    }
    
    // free resources
    curl_close( $ch );
    // send back the data
    return $result;
}
