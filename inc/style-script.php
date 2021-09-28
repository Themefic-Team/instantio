<?php

/**
 *	Enqueue Instantio scripts
 *
 */
// Admin CSS
if ( !function_exists('ins_admin_enqueue_scripts') ) {
	function ins_admin_enqueue_scripts() {
		wp_enqueue_style('ins-admin', INS_ADMIN_URL . '/css/admin.css','', INSTANTIO_VERSION );
		wp_enqueue_script( 'ins-admin', INS_ADMIN_URL . '/js/admin.js', array('jquery'), INSTANTIO_VERSION, true );
	}
}
add_action( 'admin_enqueue_scripts', 'ins_admin_enqueue_scripts' );

// Front End
if ( !function_exists('instantio_enqueue_scripts') ) {
	function instantio_enqueue_scripts(){

		if (insopt( 'css-min' ) == true) {
			wp_enqueue_style('instantio-common-styles', plugin_dir_url( __DIR__ ) . 'assets/css/instantio.min.css','', INSTANTIO_VERSION );
		} else {
			wp_enqueue_style('instantio-common-styles', plugin_dir_url( __DIR__ ) . 'assets/css/instantio.css','', INSTANTIO_VERSION );
		}

		if (insopt( 'js-min' ) == true) {
			wp_enqueue_script( 'instantio-common-scripts', plugin_dir_url( __DIR__ ) . 'assets/js/instantio.min.js', array('jquery'), INSTANTIO_VERSION, true );
		} else {
			wp_enqueue_script( 'instantio-common-scripts', plugin_dir_url( __DIR__ ) . 'assets/js/instantio.js', array('jquery'), INSTANTIO_VERSION, true );
		}

		wp_register_script( 'instantio-inline-scripts', '' );
		wp_enqueue_script( 'instantio-inline-scripts' );

		wp_localize_script( 'instantio-common-scripts', 'instantio_ajax_params',
			array(
				'wi_ajax_nonce' => wp_create_nonce( 'wi_ajax_nonce' ),
				'wi_ajax_url' => admin_url( 'admin-ajax.php' ),
				'cart_icon' => instantio_get_svg_icon(insopt( 'cart-icon' )),
			)
		);


		/**
		 * Handle WC frontend scripts
		 *
		 * @package WooCommerce/Classes
		 * @version 2.3.0
		 * http://woocommerce.wp-a2z.org/oik_api/wc_frontend_scriptsget_script_data/
		 */

		//first check that woo exists to prevent fatal errors
		if( function_exists('is_woocommerce') ) {
			$suffix               = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? : '.min';
			$assets_path          = str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/';
			$frontend_script_path = $assets_path . 'js/frontend/';

			global $wp_scripts;

			wp_enqueue_script( 'wc-cart', $frontend_script_path . 'cart' . $suffix . '.js', array( 'jquery', 'wc-country-select', 'wc-address-i18n' ) );

			wp_localize_script('wc-cart', 'wc_cart_params', apply_filters('wc_cart_params', array(
				'ajax_url' => WC()->ajax_url() ,
				'wc_ajax_url' => WC_AJAX::get_endpoint(' %%endpoint%%') ,
				'ajax_loader_url' => apply_filters('woocommerce_ajax_loader_url', $assets_path . 'images / ajax - loader@2x . gif') ,
				'update_shipping_method_nonce' => wp_create_nonce('update-shipping-method') ,
			)));

			wp_enqueue_script( 'wc-add-to-cart-variation', $frontend_script_path . 'add-to-cart-variation' . $suffix . '.js', array( 'jquery', 'wp-util', 'jquery-blockui' ) );

			wp_localize_script('wc-add-to-cart-variation', 'wc_add_to_cart_variation_params', apply_filters('wc_add_to_cart_variation_params', array(
				'wc_ajax_url'                      => WC_AJAX::get_endpoint( '%%endpoint%%' ),
				'i18n_no_matching_variations_text' => esc_attr__( 'Sorry, no products matched your selection. Please choose a different combination.', 'woocommerce' ),
				'i18n_make_a_selection_text'       => esc_attr__( 'Please select some product options before adding this product to your cart.', 'woocommerce' ),
				'i18n_unavailable_text'            => esc_attr__( 'Sorry, this product is unavailable. Please choose a different combination.', 'woocommerce' ),
			)));

		}

	}
}
add_filter( 'wp_enqueue_scripts', 'instantio_enqueue_scripts' );

/*
 * 
 * CUSTOM CSS
 * 
 */
if( !function_exists( 'ins_custom_css' ) ){
	function ins_custom_css(){
		
		// Store as PHP variables
		// Common CSS
		$hide_toggler = insopt( 'hide-toggler' );
		$show_checkout_btn = insopt( 'checkout-btn' )['on-checkout-btn'];
		$show_cart_btn = insopt( 'cart-btn' )['on-cart-btn'];
		// Toggler CSS
		$tg_bg_color_reg = insopt( 'wi-header-bg-colors' )['regular'];
		$tg_bg_color_hov = insopt( 'wi-header-bg-colors' )['hover'];
		$tg_bor_color_reg = insopt( 'wi-header-border-colors' )['regular'];
		$tg_bor_color_hov = insopt( 'wi-header-border-colors' )['hover'];
		$ins_toggler_icon_color_reg = insopt( 'ins-tog-icon-colors' )['regular'];
		$ins_toggler_icon_color_hov = insopt( 'ins-tog-icon-colors' )['hover'];
		$tg_icon_size = insopt( 'wi-header-icon-size' )['width'];
		$tg_item_numb_bg_reg = insopt( 'ins-tog-item-bg' )['regular'];
		$tg_item_numb_bg_hov = insopt( 'ins-tog-item-bg' )['hover'];
		$tg_item_numb_color_reg = insopt( 'wi-header-text-colors' )['regular'];
		$tg_item_numb_color_hov = insopt( 'wi-header-text-colors' )['hover'];
		$tg_item_numb_size = insopt( 'wi-header-text-size' )['font-size'];
		
		$output = '';

		// Common CSS
		if( $hide_toggler != true && WC()->cart->get_cart_contents_count() == 0 ) { $output .= '
			.ins-container {visibility: visible !important;}
		'; }
		if( $show_checkout_btn != true || $show_cart_btn != true) { $output .= '
			.ins-footer .footer-button {grid-template-columns: auto !important;}
		'; }
		
		// Toggler CSS
		if( $tg_bg_color_reg  ) { $output .= '
			.ins-toggle-button {background: '.$tg_bg_color_reg.';}
		'; }		
		if( $tg_bg_color_hov  ) { $output .= '
			.ins-toggle-button:hover {background: '.$tg_bg_color_hov.';}
		'; }		
		if( $tg_bor_color_reg  ) { $output .= '
			.ins-toggle-button {border-color: '.$tg_bor_color_reg.';}
		'; }		
		if( $tg_bor_color_hov  ) { $output .= '
			.ins-toggle-button:hover {border-color: '.$tg_bor_color_hov.';}
		'; }
		if( $ins_toggler_icon_color_reg  ) { $output .= '
			#ins-toggle-button svg{fill: '.$ins_toggler_icon_color_reg.';}
		'; }		
		if( $ins_toggler_icon_color_hov  ) { $output .= '
			#ins-toggle-button:hover svg{fill: '.$ins_toggler_icon_color_hov.';}
		'; }		
		if( $tg_icon_size  ) { $output .= '
			.ins-toggle-button svg, #ins-toggle-button img {width: '.$tg_icon_size.'px; height: '.$tg_icon_size.'px;}
			.ins-toggle-button-2 .ins-items-count { line-height: '.$tg_icon_size.'px; }
		'; }		
		if( $tg_item_numb_bg_reg ) { $output .= '
			.ins-toggle-button .ins-items-count {background: '.$tg_item_numb_bg_reg.';}
		'; }		
		if( $tg_item_numb_bg_hov ) { $output .= '
			.ins-toggle-button:hover .ins-items-count {background: '.$tg_item_numb_bg_hov.';}
		'; }		
		if( $tg_item_numb_color_reg ) { $output .= '
			.ins-toggle-button .ins-items-count {color: '.$tg_item_numb_color_reg.';}
		'; }		
		if( $tg_item_numb_color_hov ) { $output .= '
			.ins-toggle-button:hover .ins-items-count {color: '.$tg_item_numb_color_hov.';}
		'; }		
		if( $tg_item_numb_size ) { $output .= '
			.ins-toggle-button .ins-items-count {font-size: '.$tg_item_numb_size.'px;}
		'; }
		
		wp_add_inline_style( 'instantio-common-styles', $output );
	}
}
add_action( 'wp_enqueue_scripts', 'ins_custom_css', 200 );


/*
 * 
 * CUSTOM JS
 * 
 */
if( !function_exists( 'instantio_custom_js' ) ){
	function instantio_custom_js(){

		$output = '';

		// For Lite Version
		if( insopt( 'cart-fly' )['cart-fly-anim'] == true ) {
			$output .= "
				var cartFlyanim = 'on';
		"; } else {
			$output .= "
				var cartFlyanim = 'off';
		";}
		
		if( insopt( 'cart-fly' )['cart-fly-icon'] == 1 ) {
			$output .= "
				var cartFlyicon = 'toggler';
		"; } else {
			$output .= "
				var cartFlyicon = 'thumb';
		";}

		if( insopt( 'ins-layout' ) == 1 ) {
			$output .= "
			var cartButton = '" .insopt( 'cart-button-open' ). "';
		"; }

		// For Pro Version

		$insCheckoutUrl = esc_url( home_url('/instantio-checkout/') );
		$output .= "var iframesrc = '".$insCheckoutUrl."';";

		if( insopt( 'wi-disable-quickview' ) == 0 ) {
			$output .= "
				var noquickview = 'no';
		"; } else {
			$output .= "
				var noquickview = 'yes';
		";}

		if( insopt( 'wi-disable-ajax-add-cart' ) == 0 ) {
			$output .= "
				var noajaxaddtocart = 'no';
		"; } else {
			$output .= "
				var noajaxaddtocart = 'yes';
		";}

		if( insopt( 'wi-active-window' ) == 1 ) {
			$output .= "
				var activewindow = 'ck';
		"; } else {
			$output .= "
				var activewindow = 'cart';
		";}

		wp_add_inline_script( 'instantio-inline-scripts', $output );
	}
}
add_action( 'wp_enqueue_scripts', 'instantio_custom_js', 200 );
?>