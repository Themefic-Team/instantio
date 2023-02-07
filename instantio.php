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
		define( 'INS_CLASSIC_URL', INS_ROOT_URL.'classic' );
		define( 'INS_MODERN_URL', INS_ROOT_URL.'modern' );
		define( 'INS_ROOT_PATH', plugin_dir_path( __FILE__ ) ); 
		define( 'INS_CLASSIC_PATH', INS_ROOT_PATH.'classic' );
		define( 'INS_MODERN_PATH', INS_ROOT_PATH.'modern' ); 
		
		
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes() { 
		require_once __DIR__ . '/vendor/autoload.php';
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
		// require_once( 'functions.php' );
	}

	/**
	 * Init Instantio when WordPress Initialises.
	 */
	private function init_hooks() {  

		// add_action( 'plugins_loaded', array( $this, 'init' ), 0 );
		add_action( 'plugins_loaded', array( $this, 'ins_version_swich_register' ), 0 );
	}

	/**
	 *  init Instantio when WordPress Initialises.
	 *
	 * @since 1.0
	 */
	public function init() {  
		require_once( INS_CLASSIC_PATH.'/classic.php' );
	} 

	public function ins_version_swich_register(){
		require_once( INS_CLASSIC_PATH .'/admin/framework/framework.php' );
		// Control core classes for avoid errors
		// if( class_exists( 'CSF' ) ) {
			// Set a unique slug-like ID
  			$prefix = 'wiopt_mood';
			// Create options
			CSF::createOptions( $prefix, array(
				'framework_title' => __( 'Instantio Settings <small>by <a style="color: #bfbfbf;text-decoration:none;" href="https://themefic.com" target="_blank">Themefic</a></small>', 'instantio' ),
				'menu_title' => __( 'Instantio', 'instantio' ),
				'menu_slug'  => 'instantio_options',
				'menu_icon'  => 'dashicons-cart',
				'footer_credit' => __('<em>Enjoyed <strong>Instantio</strong>? Please leave us a <a style="color:#e9570a;" href="https://wordpress.org/support/plugin/instantio/reviews/?filter=5/#new-post" target="_blank">★★★★★</a> rating. We really appreciate your support!</em>', 'instantio'),
				'show_bar_menu' => false,
			) );
			// General Settings
			CSF::createSection( $prefix, array(
				'id'    => 'general', // Set a unique slug-like ID
				'title' => __( 'General', 'instantio' ),
				'icon'  => 'fas fa-cogs',
				'fields' => array(
					array(
						'id'     => 'cart-fly',
						'type'   => 'fieldset',
						'title'  => __('Cart Fly Animation', 'instantio'),
						'subtitle' => __('Enable/dsiable cart fly animation or change icon', 'instantio'),
						'fields' => array(
							array(
							'id'       => 'cart-fly-anim',
							'type'     => 'switcher',
							'title'    => __('Cart Fly Animation', 'instantio'), 
							'text_on'    => __('Enabled', 'instantio'),
							'text_off'   => __('Disabled', 'instantio'),
							'text_width' => 100,
							'default'   => true,
							),
				
							array(
							'id'       => 'cart-fly-icon',
							'type'     => 'button_set',
							'title'    => __('Cart Fly Animation Icon', 'instantio'), 
							'options'  => array(
								'1' => __('Toggler Icon', 'instantio'),
								'2' => __('Product Thumbnail', 'instantio'),
							),
							'default'  => '2',
							'dependency' => array('cart-fly-anim', '==', true, '', 'visiable'),
						),
					),
				),
			),
			) );


		// }

	}
}
new INSTANTIO();