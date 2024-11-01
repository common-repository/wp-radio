<?php

global  $post_id, $post ;
$stream_url = wp_radio_get_meta( $post_id, 'stream_url' );
$slogan = wp_radio_get_meta( $post_id, 'slogan' );
$logo = wp_radio_get_meta( $post_id, 'logo' );
$featured = wp_radio_get_meta( $post_id, 'featured' );
$website = wp_radio_get_meta( $post_id, 'website' );
$facebook = wp_radio_get_meta( $post_id, 'facebook' );
$twitter = wp_radio_get_meta( $post_id, 'twitter' );
$wikipedia = wp_radio_get_meta( $post_id, 'wikipedia' );
$address = wp_radio_get_meta( $post_id, 'address' );
$email = wp_radio_get_meta( $post_id, 'email' );
$phone = wp_radio_get_meta( $post_id, 'phone' );
$tab_links = [
    'general'   => [
    'title' => __( 'General', 'wp-radio' ),
    'icon'  => 'dashicons-info-outline',
],
    'reference' => [
    'title' => __( 'Socials', 'wp-radio' ),
    'icon'  => 'dashicons-admin-links',
],
    'contacts'  => [
    'title' => __( 'Contacts', 'wp-radio' ),
    'icon'  => 'dashicons-phone',
],
];
?>
<div class="wp-radio-tab-wrap" xmlns="http://www.w3.org/1999/html">
    <div class="wp-radio-metabox">

        <div class="tab-links">

			<?php 
foreach ( $tab_links as $key => $tab_link ) {
    ?>
                <button type="button" data-target="general"
                        class="tab-link <?php 
    echo  ( $key === 'general' ? 'active' : '' ) ;
    ?>"
                        onclick="jQuery(this).siblings().removeClass('active');jQuery(this).addClass('active');jQuery('.tab-content').removeClass('active');jQuery('#<?php 
    echo  $key ;
    ?>').addClass('active');"
                >
                    <i class="dashicons <?php 
    echo  $tab_link['icon'] ;
    ?>"></i>
					<?php 
    echo  $tab_link['title'] ;
    ?>
                </button>
			<?php 
}
?>

        </div>

        <div id="general" class="tab-content active">
            <!--------------- General -------------->
            <table class="form-table">

                <thead>
                <tr>
                    <th colspan="2">
                        <i class="dashicons dashicons-info-outline"></i>
						<?php 
esc_html_e( 'General Information', 'wp-radio' );
?>
                    </th>
                </tr>
                </thead>

                <tbody>

                <!-- stream URL-->
                <tr>
                    <th scope="row">
                        <label for="stream_url"><?php 
esc_html_e( 'Stream URL', 'wp-radio' );
?></label>
                    </th>
                    <td>
                        <input name="stream_url" type="text" id="stream_url"
                               value="<?php 
echo  esc_url( $stream_url ) ;
?>"
                               class="regular-text ltr">
                        <p class="description"><?php 
esc_html_e( 'Enter the live shoutcast, icecast or mp3 stream URL.', 'wp-radio' );
?></b></p>

                        <p>
                            <b><?php 
esc_html_e( 'For example:', 'wp-radio' );
?></b><a
                                    href="https://i4.streams.ovh:8352/stream" target="_blank">https://i4.streams.ovh:8352/stream</a>
                        </p>
                    </td>
                </tr>

                <!--  slogan -->
                <tr>
                    <th scope="row">
                        <label for="slogan"><?php 
esc_html_e( 'Slogan', 'wp-radio' );
?></label>
                    </th>
                    <td>
                        <input name="slogan" type="text" id="slogan" value="<?php 
echo  $slogan ;
?>"
                               class="regular-text ltr">
                        <p class="description">
							<?php 
_e( 'Enter the station slogan.', 'wp-radio' );
?>
                        </p>
                    </td>
                </tr>

                <!-- station logo-->
                <tr>
                    <th scope="row">
                        <label for="logo"><?php 
esc_html_e( 'Station Logo', 'wp-radio' );
?></label>
                    </th>
                    <td>
                        <div class="logo-metabox">
                            <div class="logo-metabox-actions">
                                <input type="text" id="logo" name="logo" value="<?php 
echo  esc_url( $logo ) ;
?>"
                                       placeholder="<?php 
_e( 'Enter the image url or select an image by clicking the plus icon.', 'wp-radio' );
?>">
                                <a href="#" class="button button-primary select_img"><i
                                            class="dashicons dashicons-plus-alt"></i></a>
                                <a href="#" class="button button-link-delete delete_img <?php 
echo  ( !empty($logo) ? '' : 'hidden' ) ;
?> "><i class="dashicons dashicons-trash"></i></a>
                            </div>

                            <img src="<?php 
echo  esc_url( $logo ) ;
?>" class="logo-metabox-preview">

                        </div>
                        <p class="description">
							<?php 
_e( 'Enter the live stream URL of the radio station.', 'wp-radio' );
?>
                        </p>
                    </td>
                </tr>

                <!-- Featured Channel switcher -->
                <tr class="featured_station ">
                    <th scope="row">
                        <label for="featured"><?php 
esc_html_e( 'Featured Station', 'wp-radio' );
?></label>
						<?php 
printf( '<span class="pro-badge">PRO</span>' );
?>
                    </th>
                    <td>

                        <div class="checkbox">
                            <input type="checkbox" name="featured" id="featured"
                                   value="yes" <?php 
checked( 'yes', $featured );
?>
                                   onclick="if(!wpRadio.isPro){return false;}"
                            />
                            <div>
                                <label for="featured"></label>
                            </div>
                        </div>

                        <p class="description">
							<?php 
_e( 'Turn ON, to featured this station.', 'wp-radio' );
?>
                            <br>
							<?php 
_e( 'List the featured stations by using <code>[wp_radio_featured]</code> shortcode.', 'wp-radio' );
?>
                        </p>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>

        <div id="reference" class="tab-content">
            <!--------------- Reference -------------->
            <table class="form-table">
                <thead>
                <tr>
                    <th colspan="2">
                        <i class="dashicons dashicons-admin-links"></i>
						<?php 
esc_html_e( 'Social Links', 'wp-radio' );
?></th>
                </tr>
                </thead>


                <tbody>

                <!-- Station Website-->
                <tr>
                    <th scope="row">
                        <label for="website"><?php 
esc_html_e( 'Station Website', 'wp-radio' );
?></label>
                    </th>
                    <td>
                        <input name="website" type="text" id="website" value="<?php 
echo  esc_url( $website ) ;
?>"
                               class="regular-text ltr">
                        <p class="description"><?php 
_e( 'Enter the website url of the station.', 'wp-radio' );
?></p>
                    </td>
                </tr>

                <!-- Station Facebook Page -->
                <tr>
                    <th scope="row">
                        <label for="facebook"><?php 
esc_html_e( 'Facebook Page', 'wp-radio' );
?></label>
                    </th>
                    <td>
                        <input name="facebook" type="text" id="facebook" value="<?php 
echo  esc_url( $facebook ) ;
?>"
                               class="regular-text ltr">
                        <p class="description"><?php 
_e( 'Enter the facebook page url of the station.', 'wp-radio' );
?></p>
                    </td>
                </tr>

                <!-- Station  Twitter URL -->
                <tr>
                    <th scope="row">
                        <label for="twitter"><?php 
esc_html_e( 'Twitter Link', 'wp-radio' );
?></label>
                    </th>
                    <td>
                        <input name="twitter" type="text" id="twitter" value="<?php 
echo  esc_url( $twitter ) ;
?>"
                               class="regular-text ltr">
                        <p class="description"><?php 
_e( 'Enter the twitter url of the station.', 'wp-radio' );
?></p>
                    </td>
                </tr>


                <!-- Station  Wikipedia URL -->
                <tr>
                    <th scope="row">
                        <label for="wikipedia"><?php 
esc_html_e( 'Wikipedia Link', 'wp-radio' );
?></label>
                    </th>
                    <td>
                        <input name="wikipedia" type="text" id="wikipedia" value="<?php 
echo  esc_url( $wikipedia ) ;
?>"
                               class="regular-text ltr">
                        <p class="description"><?php 
_e( 'Enter the wikipedia url of the station.', 'wp-radio' );
?></p>
                    </td>
                </tr>


                </tbody>
            </table>
        </div>

        <div id="contacts" class="tab-content">
            <!--------------- Contacts -------------->
            <table class="form-table">
                <thead>
                <tr>
                    <th colspan="2">
                        <i class="dashicons dashicons-phone"></i>
						<?php 
esc_html_e( 'Contacts', 'wp-radio' );
?></th>
                </tr>
                </thead>

                <tbody>
                <!-- Station  Address -->
                <tr>
                    <th scope="row">
                        <label for="address"><?php 
esc_html_e( ' Address', 'wp-radio' );
?></label>
                    </th>
                    <td>
                <textarea name="address" id="" cols="30" rows="5"
                          class="regular-text ltr"><?php 
echo  trim( str_replace( 'Address', '', $address ) ) ;
?></textarea>
                        <p class="description"><?php 
_e( 'Enter the contact address of the station.', 'wp-radio' );
?></p>
                    </td>
                </tr>

                <!-- Station  Email -->
                <tr>
                    <th scope="row">
                        <label for="email"><?php 
esc_html_e( ' Email', 'wp-radio' );
?></label>
                    </th>
                    <td>
                        <input name="email" type="text" id="email" value="<?php 
echo  trim( str_replace( 'Email', '', $email ) ) ;
?>" class="regular-text ltr">
                        <p class="description"><?php 
_e( 'Enter the Contact address of the Radio station.', 'wp-radio' );
?></p>
                    </td>
                </tr>

                <!-- Station  Phone -->
                <tr>
                    <th scope="row">
                        <label for="phone"><?php 
esc_html_e( ' Phone Number', 'wp-radio' );
?></label>
                    </th>
                    <td>
                        <input name="phone" type="text" id="phone" value="<?php 
echo  trim( str_replace( 'Phone', '', $phone ) ) ;
?>" class="regular-text ltr">
                        <p class="description"><?php 
_e( 'Enter the contact phone number of the station.', 'wp-radio' );
?></p>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>

    </div>
</div>