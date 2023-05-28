<?php 

// Themefic Plugin Set Admin Notice Status
if(!function_exists('ins_review_activation_status')){

    function ins_review_activation_status(){ 
        $ins_installation_date = get_option('ins_installation_date'); 
        if( !isset($_COOKIE['ins_installation_date']) && empty($ins_installation_date) && $ins_installation_date == 0){
            setcookie('ins_installation_date', 1, time() + (86400 * 7), "/"); 
        }else{
            update_option( 'ins_installation_date', '1' );
        }
    }
    add_action('admin_init', 'ins_review_activation_status');
}

// Themefic Plugin Review Admin Notice
if(!function_exists('ins_review_notice')){
    
     function ins_review_notice(){ 
        $get_current_screen = get_current_screen();  
        if($get_current_screen->base == 'dashboard'){
            $current_user = wp_get_current_user();
        ?>
            <div class="notice notice-info themefic_review_notice"> 
               
                <?php echo sprintf( 
                        __( ' <p>Hey %1$s 👋, You have been using %2$s for quite a while. If you feel %2$s is helping your business to grow in any way, would you please help %2$s to grow by simply leaving a 5* review on the WordPress Forum?</p>', 'instantio' ),
                        $current_user->user_login,
                        'Instantio'
                    ); ?> 
                
                <ul>
                    <li><a target="_blank" href="<?php echo esc_url('https://wordpress.org/support/plugin/instantio/reviews/#new-post') ?>" class=""><span class="dashicons dashicons-external"></span><?php _e(' Ok, you deserve it!', 'instantio' ) ?></a></li>
                    <li><a href="#" class="already_done" data-status="already"><span class="dashicons dashicons-smiley"></span> <?php _e('I already did', 'instantio') ?></a></li>
                    <li><a href="#" class="later" data-status="later"><span class="dashicons dashicons-calendar-alt"></span> <?php _e('Maybe Later', 'instantio') ?></a></li>
                    <li><a target="_blank"  href="<?php echo esc_url('https://themefic.com/docs/instantio/') ?>" class=""><span class="dashicons dashicons-sos"></span> <?php _e('I need help', 'instantio') ?></a></li>
                    <li><a href="#" class="never" data-status="never"><span class="dashicons dashicons-dismiss"></span><?php _e('Never show again', 'instantio') ?> </a></li> 
                </ul>
            </div>

            <!--   Themefic Plugin Review Admin Notice Script -->
            <script>
                jQuery(document).ready(function($) {
                    $(document).on('click', '.already_done, .later, .never', function( event ) {
                        event.preventDefault();
                        var $this = jQuery(this);
                        var status = $this.attr('data-status'); 
                        $this.closest('.themefic_review_notice').css('display', 'none')
                        data = {
                            action : 'ins_review_notice_callback',
                            status : status,
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
     $ins_review_notice_status = get_option('ins_review_notice_status'); 
     $ins_installation_date = get_option('ins_installation_date'); 
     if(isset($ins_review_notice_status) && $ins_review_notice_status <= 0 && $ins_installation_date == 1 && !isset($_COOKIE['ins_review_notice_status']) && !isset($_COOKIE['ins_installation_date'])){ 
        add_action( 'admin_notices', 'ins_review_notice' );  
     }
     
}

 
// Themefic Plugin Review Admin Notice Ajax Callback 
if(!function_exists('ins_review_notice_callback')){

    function ins_review_notice_callback(){
        $status = $_POST['status'];
        if( $status == 'already'){ 
            update_option( 'ins_review_notice_status', '1' );
        }else if($status == 'never'){ 
            update_option( 'ins_review_notice_status', '2' );
        }else if($status == 'later'){
            $cookie_name = "ins_review_notice_status";
            $cookie_value = "1";
            setcookie($cookie_name, $cookie_value, time() + (86400 * 7), "/"); 
            update_option( 'ins_review_notice_status', '0' ); 
        }  
        wp_die();
    }
    add_action( 'wp_ajax_ins_review_notice_callback', 'ins_review_notice_callback' );

}

if(!function_exists('new_updated_announcement')){
    function new_updated_announcement() { 
        $current_user = wp_get_current_user();
        $imgurl = INS_ADMIN_URL;
        ?>

        <div class="notice themefic_review_notice new_updated_anno">  
            <?php echo sprintf( 
                    __( '
                        <p>
                            Hey %1$s 👋 
                        </p>
                        <a class="new_updated_anno_banner_url" target="_blank" href="https://themefic.com/introducing-instantio-3-0/"><img class="new_updated_anno_banner" src="%2$s/img/version-update-banner.png" /></a>
                    ', 'instantio' ),
                    $current_user->user_login, $imgurl,
                    'Instantio'
                ); ?>

            <a class="new_updated_anno_close" href="#" class="never" data-status="never">
                <span class="dashicons dashicons-dismiss"></span>
            </a>

        </div>

    <?php }

    
    add_action( 'admin_notices', 'new_updated_announcement' );
    

 
}

?>