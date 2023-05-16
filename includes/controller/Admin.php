<?php
namespace INS\Controller;

class Admin{
    public function __construct() { 

        // Load Text Domain
        add_action( 'init', array( $this, 'ins_load_textdomain' ) );
        
        $ins_review_notice_status = get_option('ins_review_notice_status'); 
        $ins_installation_date = get_option('ins_installation_date'); 
        if(isset($ins_review_notice_status) && $ins_review_notice_status <= 0 && $ins_installation_date == 1 && !isset($_COOKIE['ins_review_notice_status']) && !isset($_COOKIE['ins_installation_date'])){ 
            add_action( 'admin_notices', array($this, 'ins_review_notice') );  
        }

        add_action( 'wp_ajax_ins_review_notice_callback', array($this, 'ins_review_notice_callback'));
        add_action('admin_init', array($this, 'ins_review_activation_status')); 
       
        /**
         * Check if WooCommerce is active, and if it isn't, disable the plugin.
         *
         * @since 1.0
         */
        if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            add_action( 'admin_notices', array($this, 'ins_is_woo') );

            /**
             * Ajax install & activate WooCommerce
             *
             * @since 1.0
             * @link https://developer.wordpress.org/reference/functions/wp_ajax_install_plugin/
             */
            add_action("wp_ajax_ins_ajax_install_plugin" , "wp_ajax_install_plugin");

            return;
        }
        
        if ( !is_plugin_active( 'wooinstant/wooinstant.php' ) ) {

            add_action('admin_menu', array($this,  'add_pro_link_menu'), 9999);
        }
    }

    /**
     * Load plugin textdomain.
     */
    public function ins_load_textdomain() {
        load_plugin_textdomain( 'instantio', false, 'instantio/lang/' );
    } 

    // Themefic Plugin Review Admin Notice
    public function ins_review_activation_status(){ 
        $ins_installation_date = get_option('ins_installation_date'); 
        if( !isset($_COOKIE['ins_installation_date']) && empty($ins_installation_date) && $ins_installation_date == 0){
            setcookie('ins_installation_date', 1, time() + (86400 * 7), "/"); 
        }else{
            update_option( 'ins_installation_date', '1' );
        }
    }
    // Themefic Plugin Review Admin Notice
    public function ins_review_notice(){ 
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

     // Themefic Plugin Review Admin Notice
    public function ins_review_notice_callback(){
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


    /**
     * Called when WooCommerce is inactive to display an inactive notice.
     *
     * @since 1.0
     */
    public function ins_is_woo() {
        if ( current_user_can( 'activate_plugins' ) ) {
            if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) && !file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) ) {
            ?>

                <div id="message" class="error">
                    <p><?php printf( __( 'Instantio requires %1$s WooCommerce %2$s to be activated.', 'instantio' ), '<strong><a href="https://wordpress.org/plugins/woocommerce/" target="_blank">', '</a></strong>' ); ?></p>
                    <p><a class="install-now button tf-install" data-plugin-slug="woocommerce"><?php esc_attr_e( 'Install Now', 'instantio' ); ?></a></p>
                </div>

            <?php 
            } elseif ( !is_plugin_active( 'woocommerce/woocommerce.php' ) && file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) ) {
            ?>

                <div id="message" class="error">
                    <p><?php printf( __( 'Instantio requires %1$s WooCommerce %2$s to be activated.', 'instantio' ), '<strong><a href="https://wordpress.org/plugins/woocommerce/" target="_blank">', '</a></strong>' ); ?></p>
                    <p><a href="<?php echo get_admin_url(); ?>plugins.php?_wpnonce=<?php echo wp_create_nonce( 'activate-plugin_woocommerce/woocommerce.php' ); ?>&action=activate&plugin=woocommerce/woocommerce.php" class="button activate-now button-primary"><?php esc_attr_e( 'Activate', 'instantio' ); ?></a></p>
                </div>

            <?php 
            } elseif ( version_compare( get_option( 'woocommerce_db_version' ), '2.5', '<' ) ) {
            ?>

                <div id="message" class="error">
                    <p><?php printf( __( '%sInstantio is inactive.%s This plugin requires WooCommerce 2.5 or newer. Please %supdate WooCommerce to version 2.5 or newer%s', 'instantio' ), '<strong>', '</strong>', '<a href="' . admin_url( 'plugins.php' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
                </div>

            <?php 
            }
        }
    }


    /**
     * Add Pro link in menu.
     */
    public function add_pro_link_menu() {
        $prolink = 'https://themefic.com/instantio/go/upgrade';
        $menuname = '<span style="color:#ffba00;">' .__("Upgrade to Pro", "instantio"). '</span>';
        add_submenu_page( 'ins_dashboard', __('Upgrade to Pro', 'instantio'), $menuname, 'manage_options', $prolink);
    }

 
}

?>