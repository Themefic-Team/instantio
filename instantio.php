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
		require_once __DIR__ . '/vendor/autoload.php';
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
		require_once( INS_INC_PATH.'/functions.php' );
		require_once( INS_INC_PATH.'/svg-icons.php' ); 
 
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
		// Initialize the appsero
        $this->appsero_init_tracker_instantio(); 
		
		new INS\Includes\Enqueue();

		if ( is_admin() ) {
            new INS\Includes\Admin();
        }else{
			new INS\Includes\Frontend();
		}

	}
 

	/*
	* Plugins Loaded
	* Including Option Framework
	* Including Options
	* Disable WooCommerce Notices
	*/
 

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