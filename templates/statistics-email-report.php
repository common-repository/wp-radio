<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

?>

<div class="email-wrap">

    <table width="100%" border="0" align="center">
        <tbody>

        <tr>
            <td height="25" style="background:#28ABE1;padding:15px 5%;font-size:1px;border-collapse:collapse;margin:0">
                <p style="margin-top:0;margin-bottom:0;text-align: center">
                    <span style="color:#fff;font-size:20px;font-family:inherit;font-weight:bold;text-transform:uppercase;text-decoration:initial;line-height:40px;letter-spacing:normal">Stations Play Statistics of last <?php printf( _n( '%s day', '%s days', $length, 'wp-radio' ), $length ); ?> :</span>
                </p>
            </td>
        </tr>

        <tr>
            <td height="25"
                style="background:#29abe1;padding:0;font-size:1px;border-collapse:collapse;margin:0;height:5px"></td>
        </tr>
        </tbody>
    </table>

    <table align="center">
        <tr>
            <td data-text="Titles" data-font="Primary" align="center" valign="middle"
                style="font-family: Poppins, sans-serif; color: #28ABE1; font-size: 37px; line-height: 47px; font-weight: 600; letter-spacing: 0px; padding: 40px 0;"
                contenteditable="true" data-gramm="false">
				<?php _e( 'Total plays per day', 'wp-radio' ); ?>
            </td>
        </tr>
    </table>

    <table cellpadding="0" cellspacing="0"
           style="width:100%;font-family:HelveticaNeue,Roboto,sans-serif;font-size:15px">
        <thead>
        <tr>
            <th style="padding:10px;border-bottom:1px solid #e5e5e5;border-right:1px solid #e5e5e5;text-align:left;font-weight:normal;color:#333;background:#f5f5f5">
                <u></u><b><?php _e( 'Date', 'wp-radio' ); ?>:</b><u></u>
            </th>
            <th style="padding:10px;border-bottom:1px solid #e5e5e5;border-right:1px solid #e5e5e5;text-align:left;font-weight:normal;color:#333;background:#f5f5f5">
                <u></u><b><?php _e( 'Total Play', 'wp-radio' ); ?>:</b><u></u>
            </th>
        </tr>
        </thead>
        <tbody>
		<?php
		if ( ! empty( $dates ) ) {
			foreach ( $dates as $date => $count ) { ?>
                <tr>
                    <td style="padding:8px;background:#fff;border:1px solid #eee">
						<?php echo $date; ?>
                    </td>
                    <td style="padding:8px;background:#fff;border:1px solid #eee">
						<?php echo $count; ?>
                    </td>
                </tr>
			<?php }
		} ?>
        </tbody>
    </table>

    <table align="center">
        <tr>
            <td data-text="Titles" data-font="Primary" align="center" valign="middle"
                style="font-family: Poppins, sans-serif; color: #28ABE1; font-size: 37px; line-height: 47px; font-weight: 600; letter-spacing: 0px; padding: 40px 0;"
                contenteditable="true" data-gramm="false">
				<?php _e( 'Most Played Stations', 'wp-radio' ); ?>
            </td>
        </tr>
    </table>

    <table cellpadding="0" cellspacing="0"
           style="width:100%;font-family:HelveticaNeue,Roboto,sans-serif;font-size:15px">
        <thead>
        <tr>
            <th style="padding:10px;border-bottom:1px solid #e5e5e5;border-right:1px solid #e5e5e5;text-align:left;font-weight:normal;color:#333;background:#f5f5f5">
                <u></u><b><?php _e( 'Station Name', 'wp-radio' ); ?>:</b><u></u>
            </th>
            <th style="padding:10px;border-bottom:1px solid #e5e5e5;border-right:1px solid #e5e5e5;text-align:left;font-weight:normal;color:#333;background:#f5f5f5">
                <u></u><b><?php _e( 'Total Play', 'wp-radio' ); ?>:</b><u></u>
            </th>
        </tr>
        </thead>
        <tbody>
		<?php
		if ( ! empty( $top_stations ) ) {
			foreach ( $top_stations as $station ) { ?>
                <tr>
                    <td style="padding:8px;background:#fff;border:1px solid #eee">
                        <a href="<?php echo $station->link; ?>">
                            <img width="40" style="margin-right: 10px; border-radius: 5px;"
                                 src="<?php echo $station->image; ?>" alt="<?php echo $station->name; ?>">
							<?php echo $station->name; ?>
                        </a>
                    </td>
                    <td style="padding:8px;background:#fff;border:1px solid #eee">
						<?php echo $station->total_sessions; ?>
                    </td>
                </tr>
			<?php }
		} ?>
        </tbody>
    </table>

    <div style="font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;width:100%;clear:both;color:#999;margin:0;padding:20px">
        <table width="100%"
               style="font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;margin:0">
            <tbody>
            <tr style="font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;margin:0">
                <td align="center" valign="top"
                    style="font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:12px;vertical-align:top;color:#999;text-align:center;margin:0;padding:0 0 20px">
                    <p>
                        <?php _e('This email has been generated from WP RADIO at', 'wp-radio'); ?>
                        <a href="<?php echo site_url(); ?>"><?php bloginfo('name') ?></a>.
                    </p>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

</div>