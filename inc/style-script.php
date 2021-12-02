<?php
defined( 'ABSPATH' ) || exit;

/**
 *	Dequeue scripts
 */
if ( !function_exists('ins_dequeue_scripts') ) {
	function ins_dequeue_scripts() {
		wp_dequeue_style( 'woocommerce-smallscreen' );
		wp_deregister_style( 'woocommerce-smallscreen' );
	} 
}
add_action( 'wp_enqueue_scripts', 'ins_dequeue_scripts', 999 );

/**
 *	Enqueue Admin scripts
 */
if ( !function_exists('ins_admin_enqueue_scripts') ) {
	function ins_admin_enqueue_scripts() {
		wp_enqueue_style('ins-admin', INS_ADMIN_URL . '/css/admin.css','', INSTANTIO_VERSION );
		wp_enqueue_script( 'ins-admin', INS_ADMIN_URL . '/js/admin.js', array('jquery'), INSTANTIO_VERSION, true );
	}
}
add_action( 'admin_enqueue_scripts', 'ins_admin_enqueue_scripts' );

/**
 *	Enqueue frontend scripts
 */
if ( !function_exists('instantio_enqueue_scripts') ) {
	function instantio_enqueue_scripts(){

		global $detect, $dedicated_mobile;
		
		$mincss = insopt( 'css-min' ) ? '.min' : '';
		$minjs = insopt( 'js-min' ) ? '.min' : '';

		// Modified Woo small screen CSS
		wp_enqueue_style('woocommerce-smallscreen', INS_INC_URL . '/woocommerce/css/woocommerce-smallscreen.css','', WC_VERSION, 'only screen and (max-width: 768px)' );
		
		// Instantio CSS
		wp_enqueue_style( 'instantio-common-styles', INS_ASSETS_URL . '/css/instantio' . $mincss . '.css', '', INSTANTIO_VERSION );
		// Instantio JS
		wp_enqueue_script( 'instantio-common-scripts', INS_ASSETS_URL . '/js/instantio' . $minjs . '.js', '', INSTANTIO_VERSION );

		// Inline script parent
		wp_register_script( 'instantio-inline-scripts', '' );
		wp_enqueue_script( 'instantio-inline-scripts' );

		// Ajax url
		wp_localize_script( 'instantio-common-scripts', 'ins_ajax_params',
			array(
				'ins_ajax_nonce' => wp_create_nonce( 'ins_ajax_nonce' ),
				'ins_ajax_url' => admin_url( 'admin-ajax.php' ),
				'cart_icon' => instantio_get_svg_icon(insopt( 'cart-icon' )),
				'required_message' => __('Please fill all required fields.','instantio'), 
				'valid_email' => __('Please Enter valid email.','instantio'),
			)
		);

		if ( class_exists( 'woocommerce' ) ) {
			
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? : '.min';
			// Cart CSS
			wp_enqueue_script( 'wc-cart', WC()->plugin_url() . '/assets/js/frontend/cart' . $suffix . '.js', array( 'jquery', 'wc-country-select', 'wc-address-i18n' ) );
			// Add to cart variation CSS
			wp_enqueue_script( 'wc-add-to-cart-variation', WC()->plugin_url() . '/assets/js/frontend/add-to-cart-variation' . $suffix . '.js', array( 'jquery', 'wp-util', 'jquery-blockui' ) );

			wp_enqueue_script( 'wc-add-to-cart', WC()->plugin_url() . '/assets/js/frontend/add-to-cart.min.js' . $suffix . '.js', array( 'jquery', 'wc-country-select', 'wc-address-i18n' ) );

		}

	}
}
add_action( 'wp_enqueue_scripts', 'instantio_enqueue_scripts', 9999 );

/*
 * 
 * CUSTOM CSS
 * 
 */
if ($detect->isMobile() && !$detect->isTablet() && $dedicated_mobile == true) {} else {
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
	add_action( 'wp_enqueue_scripts', 'ins_custom_css', 99999 );
}

/*
 * 
 * CUSTOM JS
 * 
 */
if( !function_exists( 'instantio_custom_js' ) ){
	function instantio_custom_js(){

		global $detect, $dedicated_mobile;

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

		if( insopt( 'auto-tog-panel' ) == true ) {
			$output .= "
				var autotogpanel = 'true';
		"; } else {
			$output .= "
				var autotogpanel = 'false';
		";}

		if( insopt( 'ins-layout' ) == 1 ) {
			$output .= "
			var cartButton = '" .insopt( 'cart-button-open' ). "';
		"; }

		// For Pro Version

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

		if( $detect->isMobile() && !$detect->isTablet() ) {
			$output .= "
				var cartflymob = 'no';
		"; } else {
			$output .= "
				var cartflymob = 'yes';
		";}

		wp_add_inline_script( 'instantio-inline-scripts', $output );
	}
}
add_action( 'wp_enqueue_scripts', 'instantio_custom_js', 99999 );
?>