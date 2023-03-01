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

 * Version: 2.5.19
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
			define( 'INSTANTIO_VERSION', '2.5.18' ); 
		} 
		define( 'INS_ROOT_URL', plugin_dir_url( __FILE__ ) ); 
		define( 'INS_ADMIN_URL', INS_ROOT_URL.'admin' );
		define( 'INS_CLASSIC_URL', INS_ROOT_URL.'classic' );
		define( 'INS_MODERN_URL', INS_ROOT_URL.'modern' );

		define( 'INS_ROOT_PATH', plugin_dir_path( __FILE__ ) );  
		define( 'INS_ADMIN_PATH', INS_ROOT_PATH.'admin' );
		define( 'INS_CLASSIC_PATH', INS_ROOT_PATH.'classic' );
		define( 'INS_MODERN_PATH', INS_ROOT_PATH.'modern' ); 
		
		
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes() {  
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
		if(!empty(insopt('ins_enable_classic')) && insopt('ins_enable_classic') == '1'){ 
			require_once( INS_CLASSIC_PATH.'/classic.php' );
		}else{
			require_once( INS_MODERN_PATH.'/modern.php' );
		}
		
	} 
 
}
new INSTANTIO();