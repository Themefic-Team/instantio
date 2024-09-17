<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class INS_PROMO_NOTICE {

    // private $api_url = 'http://ins-api.test/';
    private $api_url = 'https://api.themefic.com/';
    private $args = array();
    private $responsed = false; 
    private $ins_promo_option = false; 
    private $error_message = ''; 

    private $months = [
        'January',  
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    ];
    private $plugins_existes = ['uacf7', 'tf', 'beaf', 'ebef'];

 

    public function __construct() {  
		
        
        if(in_array(date('F'), $this->months) && !class_exists('WOOINS')){  

            $ins_promo__schudle_start_from = !empty(get_option( 'ins_promo__schudle_start_from' )) ? get_option( 'ins_promo__schudle_start_from' ) : 0;
            if($ins_promo__schudle_start_from == 0){
                // delete option
                delete_option('ins_promo__schudle_option');

            }elseif($ins_promo__schudle_start_from  != 0 && $ins_promo__schudle_start_from > time()){
                return;
            }  
            
            add_filter('cron_schedules', array($this, 'ins_custom_cron_interval'));
             
            if (!wp_next_scheduled('ins_promo__schudle')) {
                wp_schedule_event(time(), 'ins_every_day', 'ins_promo__schudle');
            }
            
            add_action('ins_promo__schudle', array($this, 'ins_promo__schudle_callback'));
          

            if(get_option( 'ins_promo__schudle_option' )){
                $this->ins_promo_option = get_option( 'ins_promo__schudle_option' );
            }
         
            $dashboard_banner = isset($this->ins_promo_option['dashboard_banner']) ? $this->ins_promo_option['dashboard_banner'] : '';
             
            
            // Admin Notice 
            $tf_existes = get_option( 'tf_promo_notice_exists' );
            if( ! in_array($tf_existes, $this->plugins_existes) && is_array($dashboard_banner) && strtotime($dashboard_banner['end_date']) > time() && strtotime($dashboard_banner['start_date']) < time() && $dashboard_banner['enable_status'] == true){
                add_action( 'admin_notices', array( $this, 'tf_black_friday_2023_admin_notice' ) );
                add_action( 'wp_ajax_tf_admin_notice_dismiss_callback', array($this, 'tf_admin_notice_dismiss_callback') );
             
            }

            // side Notice Woo Product Meta Box Notice 
            $tf_woo_existes = get_option( 'tf_promo_notice_woo_exists' );
            $service_banner = isset($this->ins_promo_option['service_banner']) ? $this->ins_promo_option['service_banner'] : array();
            $promo_banner = isset($this->ins_promo_option['promo_banner']) ? $this->ins_promo_option['promo_banner'] : array();

            $current_day = date('l'); 
            if(isset($service_banner['enable_status']) && $service_banner['enable_status'] == true && in_array($current_day, $service_banner['display_days'])){ 
             
                $start_date = isset($service_banner['start_date']) ? $service_banner['start_date'] : '';
                $end_date = isset($service_banner['end_date']) ? $service_banner['end_date'] : '';
                $enable_side = isset($service_banner['enable_status']) ? $service_banner['enable_status'] : false;
            }else{  
                $start_date = isset($promo_banner['start_date']) ? $promo_banner['start_date'] : '';
                $end_date = isset($promo_banner['end_date']) ? $promo_banner['end_date'] : '';
                $enable_side = isset($promo_banner['enable_status']) ? $promo_banner['enable_status'] : false;
            } 
            if( strtotime($end_date) > time() && strtotime($start_date) < time() && $enable_side == true){  
                add_action( 'add_meta_boxes', array($this, 'tf_black_friday_2023_woo_product') );
                
	            add_filter( 'get_user_option_meta-box-order_product', array($this, 'metabox_order') );
                add_action( 'wp_ajax_ins_black_friday_notice_ins_dismiss_callback', array($this, 'ins_black_friday_notice_ins_dismiss_callback') ); 
            }
            
			
            register_deactivation_hook( INS_PATH . 'instantio.php', array($this, 'ins_promo_notice_deactivation_hook') );
             
            
        }

        
       
    }

    public function ins_get_api_response(){
        $query_params = array(
            'plugin' => 'ins', 
        );
        $response = wp_remote_post($this->api_url, array(
            'body'    => json_encode($query_params),
            'headers' => array('Content-Type' => 'application/json'),
        )); 
        if (is_wp_error($response)) {
            // Handle API request error
            $this->responsed = false;
            $this->error_message = esc_html($response->get_error_message());
 
        } else {
            // API request successful, handle the response content
            $data = wp_remote_retrieve_body($response);
           
            $this->responsed = json_decode($data, true); 

            $ins_promo__schudle_option = get_option( 'ins_promo__schudle_option' ); 
            if(!empty($ins_promo__schudle_option) && $ins_promo__schudle_option['notice_name'] != $this->responsed['notice_name']){ 
                // Unset the cookie variable in the current script
                update_option( 'ins_dismiss_admin_notice', 1);
                update_option( 'ins_dismiss_post_woo_notice', 1);  
                update_option( 'ins_promo__schudle_start_from', time() + 43200);
            }elseif(empty($ins_promo__schudle_option)){
                update_option( 'ins_promo__schudle_start_from', time() + 43200);
            }
            update_option( 'ins_promo__schudle_option', $this->responsed);
            // Add 24 hours to the current time for the next update
            
        } 
    }

    // Define the custom interval
    public function ins_custom_cron_interval($schedules) {
        $schedules['ins_every_day'] = array(
            'interval' => 86400, // Every 24 hours
            // 'interval' => 5, // Every 24 hours
            'display' => __('Every 24 hours')
        );
        return $schedules;
    }

    public function ins_promo__schudle_callback() {  

        $this->ins_get_api_response();

    }
 

    /**
     * Black Friday Deals 2023
     */
    
    public function tf_black_friday_2023_admin_notice(){ 
        $dashboard_banner = isset($this->ins_promo_option['dashboard_banner']) ? $this->ins_promo_option['dashboard_banner'] : '';
        $image_url = isset($dashboard_banner['banner_url']) ? esc_url($dashboard_banner['banner_url']) : '';
        $deal_link = isset($dashboard_banner['redirect_url']) ? esc_url($dashboard_banner['redirect_url']) : ''; 

        $ins_dismiss_admin_notice = get_option( 'ins_dismiss_admin_notice' );
        $get_current_screen = get_current_screen();  
        if(($ins_dismiss_admin_notice == 1  || time() >  $ins_dismiss_admin_notice ) && $get_current_screen->base == 'dashboard'   ){ 
           
            // if very fist time then set the dismiss for our other plugins
            update_option( 'tf_promo_notice_exists', 'ins' );
            ?>
            <style> 
                .tf_black_friday_20222_admin_notice a:focus {
                    box-shadow: none;
                } 
                .tf_black_friday_20222_admin_notice {
                    padding: 7px;
                    position: relative;
                    z-index: 10;
                    max-width: 825px;
                } 
                .tf_black_friday_20222_admin_notice button:before {
                    color: #fff !important;
                }
                .tf_black_friday_20222_admin_notice button:hover::before {
                    color: #d63638 !important;
                }
            </style>
            <div class="notice notice-success tf_black_friday_20222_admin_notice"> 
                <a href="<?php echo esc_attr( $deal_link ); ?>" style="display: block; line-height: 0;" target="_blank" >
                    <img  style="width: 100%;" src="<?php echo esc_attr($image_url) ?>" alt=""> 
                </a> 
                <?php if( isset($dashboard_banner['dismiss_status']) && $dashboard_banner['dismiss_status'] == true): ?>
                <button type="button" class="notice-dismiss tf_black_friday_notice_dismiss"><span class="screen-reader-text"><?php echo __('Dismiss this notice.', 'ultimate-addons-cf7' ) ?></span></button>
                <?php  endif; ?>
            </div>
            <script>
                jQuery(document).ready(function($) {
                    $(document).on('click', '.tf_black_friday_notice_dismiss', function( event ) {
                        jQuery('.tf_black_friday_20222_admin_notice').css('display', 'none')
                        data = {
                            action : 'tf_admin_notice_dismiss_callback',
                        };

                        $.ajax({
                            url: ajaxurl,
                            type: 'post',
                            data: data,
                            success: function (data) { ;
                            },
                            error: function (data) { 
                            }
                        });
                    });
                });
            </script>
        
        <?php 
        }
        
    } 


    public function tf_admin_notice_dismiss_callback() {  
  
        $ins_promo_option = get_option( 'ins_promo__schudle_option' );
        $dashboard_banner = isset($ins_promo_option['dashboard_banner']) ? $ins_promo_option['dashboard_banner'] : '';
        $restart = isset($dashboard_banner['restart']) && $dashboard_banner['restart'] != false ? $dashboard_banner['restart'] : false; 
        
        if($restart == false){
            update_option( 'ins_dismiss_admin_notice', strtotime($dashboard_banner['end_date']) ); 
        }else{
            update_option( 'ins_dismiss_admin_notice', time() + (86400 * $restart) );  
        } 
    
		wp_die();
	}


    /**
     * Black Friday Deals 2023 woo product
     */ 

    public function tf_black_friday_2023_woo_product() { 
        $ins_dismiss_post_woo_notice = get_option( 'ins_dismiss_post_woo_notice' ); 
        if($ins_dismiss_post_woo_notice == 1  || time() >  $ins_dismiss_post_woo_notice ): 
            add_meta_box( 'tf_black_friday_annous', __( ' ', 'instantio' ), array($this, 'tf_black_friday_2023_callback_woo_product'), 'product', 'side', 'high' );
        endif;
   
    }
    public function tf_black_friday_2023_callback_woo_product() {
        $service_banner = isset($this->ins_promo_option['service_banner']) ? $this->ins_promo_option['service_banner'] : array();
        $promo_banner = isset($this->ins_promo_option['promo_banner']) ? $this->ins_promo_option['promo_banner'] : array();

        $current_day = date('l'); 
        if($service_banner['enable_status'] == true && in_array($current_day, $service_banner['display_days'])){ 
           
            $image_url = esc_url($service_banner['banner_url']);
            $deal_link = esc_url($service_banner['redirect_url']);  
            $dismiss_status = $service_banner['dismiss_status'];
        }else{
            $image_url = esc_url($promo_banner['banner_url']);
            $deal_link = esc_url($promo_banner['redirect_url']); 
            $dismiss_status = $promo_banner['dismiss_status'];  
        }  
           
            ?>
            <style>
                #tf_black_friday_annous{
                    border: 0px solid;
                    box-shadow: none;
                    background: transparent;
                }
                .back_friday_2023_preview a:focus {
                    box-shadow: none;
                }

                .back_friday_2023_preview a {
                    display: inline-block;
                }

                #tf_black_friday_annous .inside {
                    padding: 0;
                    margin-top: 0;
                }

                #tf_black_friday_annous .postbox-header {
                    display: none;
                    visibility: hidden;
                }
            </style> 
        
            <div class="back_friday_2023_preview ins-bf-preview" style="text-align: center; overflow: hidden;">
                <a href="<?php echo esc_attr($deal_link); ?>" target="_blank" >
                    <img  style="width: 100%;" src="<?php echo esc_attr($image_url); ?>" alt="">
                </a>  
                <?php if( isset($dismiss_status) && $dismiss_status == true): ?>
                    <button type="button" class="notice-dismiss ins_friday_notice_dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                <?php  endif; ?>
            
            </div>
            <script> 
                jQuery(document).ready(function($) {
                    $(document).on('click', '.ins_friday_notice_dismiss', function( event ) { 
                        jQuery('.ins-bf-preview').css('display', 'none');
                        data = {
                            action : 'ins_black_friday_notice_ins_dismiss_callback', 
                        };

                        $.ajax({
                            url: ajaxurl,
                            type: 'post',
                            data: data,
                            success: function (data) { ;
                            },
                            error: function (data) { 
                            }
                        });
                    });
                });
            </script>
            <?php  
    }

    public function metabox_order( $order ) {
		return array(
			'side' => join( 
				",", 
				array(       // vvv  Arrange here as you desire
					'submitdiv',
					'tf_black_friday_annous',
				)
			),
		);
	}

    public  function ins_black_friday_notice_ins_dismiss_callback() {   

        $ins_promo_option = get_option( 'ins_promo__schudle_option' );
        $service_banner = isset($ins_promo_option['service_banner']) ? $ins_promo_option['service_banner'] : array();
        $promo_banner = isset($ins_promo_option['promo_banner']) ? $ins_promo_option['promo_banner'] : '';

        $current_day = date('l'); 
        if($service_banner['enable_status'] == true && in_array($current_day, $service_banner['display_days'])){ 
            $start_date = $service_banner['start_date'];
            $restart = isset($service_banner['restart']) && $service_banner['restart'] != false ? $service_banner['restart'] : 5;
        }else{
            $start_date = $promo_banner['start_date']; 
            $restart = isset($promo_banner['restart']) && $promo_banner['restart'] != false ? $promo_banner['restart'] : 5;
        } 
        update_option( 'ins_dismiss_post_woo_notice', time() + (86400 * $restart) );  
        wp_die();
    }

    // Deactivation Hook
    public function ins_promo_notice_deactivation_hook() {
        wp_clear_scheduled_hook('ins_promo__schudle'); 

        delete_option('ins_promo__schudle_option');
        delete_option('ins_dismiss_post_woo_notice');
        delete_option('tf_promo_notice_exists');
    }
 
}

new INS_PROMO_NOTICE();
