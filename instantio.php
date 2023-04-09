<?php
/**
 * Plugin Name: Instantio - Instant Checkout for WooCommerce
 * Plugin URI: https://themefic.com/instantio/
 * Description: WooCommerce Quick Checkout through Side Cart, Floating Cart, Popup Cart & Direct Checkout Button. The whole checkout process would take only 15-25 seconds. Less Cart Abandonment and Better Sales Rate.
 * Author: Themefic
 * Text Domain: instantio
 * Domain Path: /lang/
 * Author URI: https://themefic.com
 * Tags: woocommerce, direct checkout, floating cart, side cart, ajax cart, cart popup, ajax add to cart, one page checkout, single page checkout, fly cart, mini cart, quick buy, instant checkout, quick checkout, same page checkout, sidebar cart, sticky cart, woocommerce ajax, one click checkout, woocommerce one page checkout, direct checkout woocommerce, woocommerce one click checkout, woocommerce quick checkout, woocommerce express checkout, woocommerce simple checkout, skip cart page woocommerce, woocommerce cart popup, edit woocommerce checkout page, woocommerce direct checkout

 * Version: 2.5.23
 * Tested up to: 6.1.1
 * Requires PHP: 7.2
 * WC tested up to: 7.2.2
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
	 * Define constants
	 */
	private function define_constants() {
		if ( ! defined( 'INSTANTIO_VERSION' ) ) { 
			define( 'INSTANTIO_VERSION', '2.5.23' ); 
		} 
		define( 'INS_URL', plugin_dir_url( __FILE__ ) ); 
		define( 'INS_INC_URL', INS_URL.'includes' );
		define( 'INS_LAYOUTS_URL', INS_URL.'includes/layouts' );
		define( 'INS_ASSETS_URL', INS_URL.'assets' );
		define( 'INS_ADMIN_URL', INS_URL.'admin' ); 

		define( 'INS_PATH', plugin_dir_path( __FILE__ ) );  
		define( 'INS_INC_PATH', INS_PATH.'includes' );
		define( 'INS_LAYOUTS_PATH', INS_INC_PATH.'includes' );
		define( 'INS_ADMIN_PATH', INS_PATH.'admin' );
		
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes() {  
		require_once __DIR__ . '/vendor/autoload.php'; 
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );  
		require_once( 'functions.php' );
	}


	/**
	 * Init Instantio when WordPress Initialises.
	 */
	private function init_hooks() {  
		add_action( 'plugins_loaded', array( $this, 'init' ), 0 ); 
	}

	/**
	 *  init Instantio when WordPress Initialises.
	 *
	 * @since 1.0
	 */
	public function init() {    
		add_action( 'plugins_loaded', array( $this, 'tf_plugin_loaded_action' ) );
        new INS\Controller\Assets(); 

        if ( is_admin() && !wp_doing_ajax() ) {   
            new INS\Controller\Admin();
        }else{  
			new INS\Controller\App();
        }
		
	} 

	/**
     * Plugins Loaded Actions
     *
     * Including Option Panel
     *
     * Including Options
     */ 
    public function tf_plugin_loaded_action() {  
        if(class_exists('WOOINS')) return;

        if ( file_exists( WP_PLUGIN_DIR .'/wooinstant/admin/config.php' )  && defined( 'INSTANTIO_PRO_CONFIG' ) && defined( 'INSTANTIO_PRO' ) ) {
			require_once INS_PATH . 'admin/tf-options/TF_Options.php';
		} elseif ( file_exists( INS_PATH . 'admin/tf-options/TF_Options.php' ) ) {
			require_once INS_PATH . 'admin/tf-options/TF_Options.php';
		}

    } 
 
}
new INSTANTIO();