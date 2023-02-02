<?php 
namespace INS\Includes;

class Admin {
    
    public function __construct() {  
        $this->includes();
        $this->init();
        $this->init_hooks();
    }
    
    /**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes() {   
        
        require_once( INS_ADMIN_PATH.'/admin-notice.php' );

	}

    /**
	 * Init Instantio when WordPress Initialises.
	 */
	private function init_hooks() { 
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
			add_action("wp_ajax_ins_ajax_install_plugin" , array($this, "wp_ajax_install_plugin") );
		
			return;
		}
		
		add_action( 'plugins_loaded', array($this, 'instantio_plugin_loaded_action') ); 
	}

    /**
	 *  init Instantio when WordPress Initialises.
	 *
	 * @since 1.0
	 */
	public function init() {

		// Set up localisation
		$this->ins_load_textdomain();
 

		// Activation & Deactivation Hooks
		register_activation_hook( __FILE__, array($this,  'ins_activate'));
		register_deactivation_hook( __FILE__, array($this, 'ins_deactivate') );


	}

    /**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 */
	public function ins_load_textdomain() {
		load_plugin_textdomain( 'instantio', false, 'instantio/lang/' );
	}

    /*
	* Plugins Loaded
	* Including Option Framework
	* Including Options
	* Disable WooCommerce Notices
	*/
	public function instantio_plugin_loaded_action() {
		
		// Option Framework
		if ( file_exists( INS_ADMIN_PATH .'/framework/framework.php' ) ) {
			require_once( INS_ADMIN_PATH .'/framework/framework.php' );
		}
		
		// Options
		if ( file_exists( WP_PLUGIN_DIR .'/wooinstant/admin/config.php' )  && defined( 'INSTANTIO_PRO_CONFIG' ) && defined( 'INSTANTIO_PRO' ) ) {
			require_once( WP_PLUGIN_DIR .'/wooinstant/admin/config.php' );
		} elseif ( file_exists( INS_ADMIN_PATH .'/config.php' ) ) {
			require_once( INS_ADMIN_PATH .'/config.php' );
		}

		// Disable WooCommerce Notices
		if ( class_exists( 'woocommerce' ) ) {
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
			remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices', 10 );
			add_filter('woocommerce_cart_item_removed_notice_type', '__return_null');
		}
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
     * Activation Hook
     *
     * @since 1.0
     */
       public  function ins_activate() {
         $installed = get_option( 'instantio_active_time' );
 
         if ( ! $installed ) {
             update_option( 'instantio_active_time', time() );
         }
         
     }
 
     /**
      * Deactivation Hook
      * 
      * @since 1.0
      */
 
     public function ins_deactivate() {
         $installed = get_option( 'instantio_active_time' );
         if($installed){
             delete_option('instantio_active_time' );
         }
     }

}

?>