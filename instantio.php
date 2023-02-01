<?php
/**
 * Plugin Name: Instantio - Instant Checkout for WooCommerce
 * Plugin URI: https://wpinstant.io/
 * Description: WooCommerce Quick Checkout through Side Cart, Floating Cart, Popup Cart & Direct Checkout Button. The whole checkout process would take only 15-25 seconds. Less Cart Abandonment and Better Sales Rate.
 * Author: Themefic
 * Text Domain: instantio
 * Domain Path: /lang/
 * Author URI: https://themefic.com
 * Tags: woocommerce, direct checkout, floating cart, side cart, ajax cart, cart popup, ajax add to cart, one page checkout, single page checkout, fly cart, mini cart, quick buy, instant checkout, quick checkout, same page checkout, sidebar cart, sticky cart, woocommerce ajax, one click checkout, woocommerce one page checkout, direct checkout woocommerce, woocommerce one click checkout, woocommerce quick checkout, woocommerce express checkout, woocommerce simple checkout, skip cart page woocommerce, woocommerce cart popup, edit woocommerce checkout page, woocommerce direct checkout
 * Version: 2.5.18
 * Tested up to: 6.1.1
 * Requires PHP: 7.2
 * WC tested up to: 7.3.0
**/

// don't load directly
defined( 'ABSPATH' ) || exit;

class INSTANTIO {
	
	public function __construct() {
		$this->define_constants(); 
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Instantio All the Defines
	 *
	 * @since 3.0
	 */
	// URLs

	private function define_constants() {

		if ( ! defined( 'INSTANTIO_VERSION' ) ) { 
			define( 'INSTANTIO_VERSION', '2.5.18' ); 
		}
		
		define( 'INS_URL', plugin_dir_url( __FILE__ ) );
		define( 'INS_INC_URL', INS_URL.'inc' );
		define( 'INS_LAYOUTS_URL', INS_URL.'inc/layouts' );
		define( 'INS_ASSETS_URL', INS_URL.'assets' );
		define( 'INS_ADMIN_URL', INS_URL.'admin' );
		// Paths
		define( 'INS_PATH', plugin_dir_path( __FILE__ ) );
		define( 'INS_ADMIN_PATH', INS_PATH.'admin' );
		define( 'INS_INC_PATH', INS_PATH.'inc' );
		define( 'INS_LAYOUTS_PATH', INS_INC_PATH.'/layouts' );
		
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes() { 
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
		require_once( INS_INC_PATH.'/functions.php' );
		require_once( INS_INC_PATH.'/svg-icons.php' );
		require_once( INS_INC_PATH.'/enqueue.php' );

		if(is_admin()){
			require_once( INS_ADMIN_PATH.'/admin-notice.php' );
		}


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
		add_action( 'plugins_loaded', array( $this, 'init' ), 0 );
	}
 
	/**
	 *  init Instantio when WordPress Initialises.
	 *
	 * @since 1.0
	 */
	public function init() {

		// Set up localisation
		$this->ins_load_textdomain();

		// Initialize the appsero
        $this->appsero_init_tracker_instantio();

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

	function appsero_init_tracker_instantio() {

		if ( ! class_exists( 'Appsero\Client' ) ) {
			require_once (INS_INC_PATH . '/app/src/Client.php');
		}
	
			$client = new Appsero\Client( '29e55a76-0819-490f-b692-8368956cbf12', 'instantio', __FILE__ );
		
		// Change notice text
		$notice = sprintf( $client->__trans( 'Want to help make <strong>%1$s</strong> even more awesome? Allow %1$s to collect non-sensitive diagnostic data and usage information. I agree to get Important Product Updates & Discount related information on my email from  %1$s (I can unsubscribe anytime).' ), $client->name );
		
		$client->insights()->notice($notice);
	
		// Active insights
		$client->insights()->init();
	
	}
}
new INSTANTIO();