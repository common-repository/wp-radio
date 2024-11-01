<?php

defined( 'ABSPATH' ) || exit;
if ( !class_exists( 'WP_Radio_Statistics' ) ) {
    class WP_Radio_Statistics
    {
        private static  $instance = null ;
        protected  $query_args = array() ;
        public function __construct( $args )
        {
            $this->query_args = $args;
        }
        
        public function filter_bar()
        {
            ?>
            <div class="wp-radio-filter-bar" id="wp_radio_statistics_filter">

                <p class="description">
					<?php 
            _e( 'Select specific dates to filter the statistics data.', 'wp-radio' );
            ?>
                </p>

                <form action="" method="post">
                    <div class="input-group">

                        <div class="form-group">
                            <label for="start_date"><?php 
            _e( 'Start Date:', 'wp-radio' );
            ?></label>
                            <input type="text" id="start_date" class="wp_radio_date_field" name="start_date"
                                   value="<?php 
            echo  $this->query_args['start_date'] ;
            ?>">
                        </div>

                        <div class="form-group">
                            <label for="end_date"><?php 
            _e( 'End Date:', 'wp-radio' );
            ?></label>
                            <input type="text" id="end_date" class="wp_radio_date_field" name="end_date"
                                   value="<?php 
            echo  $this->query_args['end_date'] ;
            ?>">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="statistics_filter_submit button button-primary button-large">
                                <i class="dashicons dashicons-filter"></i> <?php 
            _e( 'Filter', 'wp-radio' );
            ?></button>
                        </div>

                    </div>
                </form>
            </div>
		<?php 
        }
        
        public function log_chart()
        {
            $days = [];
            for ( $i = 0 ;  $i < 15 ;  $i++ ) {
                $days[] = date( "Y-m-d", strtotime( '-' . $i . ' days' ) );
            }
            $dates = array_merge( range( 1500, 2500, 100 ), range( 700, 3000, 300 ) );
            $chart['values'] = '["' . implode( '", "', $dates ) . '"]';
            $chart['labels'] = '["' . implode( '", "', $days ) . '"]';
            return $chart;
        }
        
        public function chart()
        {
            ?>
            <div class="chart-container">
                <canvas height="300" id="plays_chart" data-labels='<?php 
            echo  $this->log_chart()['labels'] ;
            ?>'
                        data-values='<?php 
            echo  $this->log_chart()['values'] ;
            ?>'></canvas>
            </div>
		<?php 
        }
        
        public function get_top_stations()
        {
            global  $wpdb ;
            $table = $wpdb->prefix . 'wp_radio_statistics';
            $page = ( !empty($this->query_args['page']) ? intval( $this->query_args['page'] ) : 1 );
            $per_page = ( !empty($this->query_args['per_page']) ? intval( $this->query_args['per_page'] ) : 50 );
            $start_date = esc_attr( $this->query_args['start_date'] );
            $end_date = esc_attr( $this->query_args['end_date'] );
            $offset = $per_page * ($page - 1);
            $where = "WHERE (`updated_at` BETWEEN '{$start_date}' AND '{$end_date}') ";
            if ( !empty($this->query_args['where']) ) {
                $where .= $this->query_args['where'];
            }
            $sql = "SELECT\r\n                DISTINCT `station_id`,\r\n                COUNT(`id`) AS `total_uniques`,\r\n                SUM(`count`) AS `total_sessions`\r\n            FROM {$table} {$where}\r\n            GROUP BY `station_id`\r\n            ORDER BY\r\n                `total_sessions` DESC,\r\n                `total_uniques` DESC\r\n            LIMIT {$offset}, {$per_page}\r\n            ";
            return $stations = $wpdb->get_results( $sql );
        }
        
        public function top_stations()
        {
            $stations = [];
            $stations[]['name'] = 'Heart Radio';
            $stations[]['name'] = 'Heart FM';
            $stations[]['name'] = 'Capital FM';
            $stations[]['name'] = 'Magic Radio';
            $stations[]['name'] = 'BBC Radio 2';
            $stations[]['name'] = 'BBC Radio 4';
            $stations[]['name'] = 'BBC Radio 1';
            $stations[]['name'] = 'Classic FM';
            $stations[]['name'] = 'BBC Radio 5';
            $stations[]['name'] = 'Kiss';
            $stations[]['name'] = 'Magic';
            $stations[]['name'] = 'talkSPORT';
            $stations[]['name'] = 'Absolute Radio';
            $stations[]['name'] = 'BBC Radio 6 Music';
            $stations[]['name'] = 'LBC';
            $stations[]['name'] = 'BBC Radio 4 Extra';
            $stations[]['name'] = 'BBC Radio 3';
            $stations[]['name'] = 'Kisstory';
            $stations[]['name'] = 'BBC Radio 1Xtra';
            $stations[]['name'] = 'National radio stations';
            $stations[]['name'] = 'Bayerischer Rundfunk (BR)';
            $stations[]['name'] = 'Hessischer Rundfunk (hr)';
            $stations[]['name'] = 'Mitteldeutscher Rundfunk (MDR)';
            $stations[]['name'] = 'Norddeutscher Rundfunk (NDR)';
            $stations[]['name'] = 'Radio Bremen (RB)';
            $countries = [
                'Australia',
                'United States',
                'United Kingdom',
                'India',
                'Germany',
                'Brazil',
                'Italy',
                'Spain',
                'France',
                'Bangladesh',
                'Russia',
                'Argentina'
            ];
            ?>
            <div class="statistics-top-stations <?php 
            echo  ( !empty($stations) ? '' : 'hidden' ) ;
            ?>">

                <div class="statistics-table-header">
                    <h3 class="statistics-table-title"><?php 
            _e( 'Most Listened Stations', 'wp-radio' );
            ?></h3>
                    <p class="description"><?php 
            _e( 'Lists of the top played radio stations.', 'wp-radio' );
            ?></p>
                </div>

                <table class="widefat" id="top-pages-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th><?php 
            _e( 'Station', 'wp-radio' );
            ?></th>
                        <th><?php 
            _e( 'Station Country', 'wp-radio' );
            ?></th>
                        <th>
							<?php 
            _e( 'Listeners Count', 'wp-radio' );
            ?>
                            <i class="dashicons dashicons-info wr-tooltip">
                                <span class="tooltiptext"><?php 
            _e( 'Total number of listeners who played the station.', 'wp-radio' );
            ?></span>
                            </i>
                        </th>
                        <th>
							<?php 
            _e( 'Play Count', 'wp-radio' );
            ?>
                            <i class="dashicons dashicons-info wr-tooltip">
                                <span class="tooltiptext"><?php 
            _e( 'The number of times the stations has been played.', 'wp-radio' );
            ?></span>
                            </i>
                        </th>
                    </tr>
                    </thead>

                    <tbody>

					<?php 
            
            if ( !empty($stations) ) {
                $i = 1;
                foreach ( $stations as $item ) {
                    $name = $item['name'];
                    $link = '#';
                    $country_name = $countries[array_rand( $countries )];
                    $total_listeners = mt_rand( 3000, 15000 );
                    $total_played = mt_rand( 8700, 100000 );
                    ?>
                            <tr>
                                <td><?php 
                    echo  $i ;
                    ?></td>
                                <td><a href="<?php 
                    echo  $link ;
                    ?>" target="_blank"><?php 
                    echo  $name ;
                    ?></a></td>
                                <td><?php 
                    echo  $country_name ;
                    ?></td>
                                <td><?php 
                    echo  $total_listeners ;
                    ?></td>
                                <td><?php 
                    echo  $total_played ;
                    ?></td>
                            </tr>
							<?php 
                    $i++;
                }
            }
            
            ?>

                    </tbody>
                </table>
            </div>
		<?php 
        }
        
        public static function instance( $args = array() )
        {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self( $args );
            }
            return self::$instance;
        }
    
    }
}