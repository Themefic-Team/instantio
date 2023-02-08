<?php
defined( 'ABSPATH' ) || exit;

// Mobile detect
if( class_exists( 'INS_Mobile_Detect' ) ) {
	$detect = new INS_Mobile_Detect;
	$dedicated_mobile = !empty(insopt('mobile')) ? insopt('mobile') : '0';
}

/**
 *	Dequeue scripts
 */
if ( !function_exists('ins_dequeue_scripts') ) {
	function ins_dequeue_scripts() {
		if ( class_exists( 'woocommerce' ) ) {
			wp_dequeue_style( 'woocommerce-smallscreen' );
			wp_deregister_style( 'woocommerce-smallscreen' );
		}
	} 
	add_action( 'wp_enqueue_scripts', 'ins_dequeue_scripts', 999 );
}

/**
 *	Enqueue frontend scripts
 */
if ( !function_exists('instantio_enqueue_scripts') ) {
	function instantio_enqueue_scripts(){

		global $detect, $dedicated_mobile;
		
		$mincss = insopt( 'css-min' ) ? '.min' : '';
		$minjs = insopt( 'js-min' ) ? '.min' : '';

		// Modified Woo small screen CSS
		wp_enqueue_style('woocommerce-smallscreen', INS_INC_URL . '/woocommerce/css/woocommerce-smallscreen.css','', INSTANTIO_VERSION, 'only screen and (max-width: 768px)' );
		
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
	add_action( 'wp_enqueue_scripts', 'instantio_enqueue_scripts', 9999 );
}



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
			$hide_toggler = !empty(insopt( 'hide-toggler' )) ? insopt( 'hide-toggler' ) : '';
			$show_checkout_btn = !empty(insopt( 'checkout-btn' )['on-checkout-btn']) ? insopt( 'checkout-btn' )['on-checkout-btn'] : '';
			$show_cart_btn = !empty(insopt( 'cart-btn' )['on-cart-btn']) ? insopt( 'cart-btn' )['on-cart-btn'] : '';
			// Toggler CSS
			$tg_bg_color_reg = !empty(insopt( 'wi-header-bg-colors' )['regular']) ? insopt( 'wi-header-bg-colors' )['regular'] : '';
			$tg_bg_color_hov = !empty(insopt( 'wi-header-bg-colors' )['hover']) ? insopt( 'wi-header-bg-colors' )['hover'] : '';
			$tg_bor_color_reg = !empty(insopt( 'wi-header-border-colors' )['regular']) ? insopt( 'wi-header-border-colors' )['regular'] : '';
			$tg_bor_color_hov = !empty(insopt( 'wi-header-border-colors' )['hover']) ? insopt( 'wi-header-border-colors' )['hover'] : '';
			$ins_toggler_icon_color_reg = !empty(insopt( 'ins-tog-icon-colors' )['regular']) ? insopt( 'ins-tog-icon-colors' )['regular'] : '';
			$ins_toggler_icon_color_hov = !empty(insopt( 'ins-tog-icon-colors' )['hover']) ? insopt( 'ins-tog-icon-colors' )['hover'] : '';
			$tg_icon_size = !empty(insopt( 'wi-header-icon-size' )['width']) ? insopt( 'wi-header-icon-size' )['width'] : '';
			$tg_item_numb_bg_reg = !empty(insopt( 'ins-tog-item-bg' )['regular']) ? insopt( 'ins-tog-item-bg' )['regular'] : '';
			$tg_item_numb_bg_hov = !empty(insopt( 'ins-tog-item-bg' )['hover']) ? insopt( 'ins-tog-item-bg' )['hover'] : '';
			$tg_item_numb_color_reg = !empty(insopt( 'wi-header-text-colors' )['regular']) ? insopt( 'wi-header-text-colors' )['regular'] : '';
			$tg_item_numb_color_hov = !empty(insopt( 'wi-header-text-colors' )['hover']) ? insopt( 'wi-header-text-colors' )['hover'] : '';
			$tg_item_numb_size = !empty(insopt( 'wi-header-text-size' )['font-size']) ? insopt( 'wi-header-text-size' )['font-size'] : '';
			
			$output = '';

			// Common CSS
			if ( class_exists( 'woocommerce' ) ) {
    			if( $hide_toggler != true && WC()->cart->get_cart_contents_count() == 0 ) { $output .= '
    				.ins-container {visibility: visible !important;}
    			'; }
			}

			//Cart Footer Button 
			if( $show_checkout_btn != true || $show_cart_btn != true) { $output .= '
				.ins-footer .footer-button {grid-template-columns: auto !important;}
			'; }

			if( $show_cart_btn != true) { $output .= '
				.ins-lay2-container .ins-footer a:nth-child(1){display: none !important;}
			'; }

			if( $show_checkout_btn != true) { $output .= '
				.ins-lay2-container .ins-footer a:nth-child(2){display: none !important;}
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

		// Backend options as variables
		$ins_layout = !empty(insopt( 'ins-layout' )) ? insopt( 'ins-layout' ) : '';
		$cart_fly_anim = !empty(insopt( 'cart-fly' )['cart-fly-anim']) ? insopt( 'cart-fly' )['cart-fly-anim'] : '';
		$cart_fly_icon = !empty(insopt( 'cart-fly' )['cart-fly-icon']) ? insopt( 'cart-fly' )['cart-fly-icon'] : '';
		$auto_tog_panel = !empty(insopt( 'auto-tog-panel' )) ? insopt( 'auto-tog-panel' ) : '';
		$disable_quickview = !empty(insopt( 'wi-disable-quickview' )) ? insopt( 'wi-disable-quickview' ) : '';
		$disable_ajax_add_cart = !empty(insopt( 'wi-disable-ajax-add-cart' )) ? insopt( 'wi-disable-ajax-add-cart' ) : '';
		$active_window = !empty(insopt( 'wi-active-window' )) ? insopt( 'wi-active-window' ) : '';

		$output = '';
			
		// For Lite Version
		$output .= "var ins_layout = '" .$ins_layout. "'; ";

		if( $cart_fly_anim == true ) {
			$output .= "var cartFlyanim = 'on'; ";
		} else {
			$output .= "var cartFlyanim = 'off'; ";
		}
		
		if( $cart_fly_icon == 1 ) {
			$output .= "var cartFlyicon = 'toggler'; ";
		} else {
			$output .= "var cartFlyicon = 'thumb'; ";
		}

		if( $auto_tog_panel == true ) {
			$output .= "var autotogpanel = 'true'; ";
		} else {
			$output .= "var autotogpanel = 'false'; ";
		}

		if( $ins_layout == 1 ) {
			$output .= "var cartButton = '" .insopt( 'cart-button-open' ). "'; ";
		}

		// For Pro Version

		if( $disable_quickview == 0 ) {
			$output .= "var noquickview = 'no'; ";
		} else {
			$output .= "var noquickview = 'yes'; ";
		}

		if( $disable_ajax_add_cart == 0 ) {
			$output .= "var noajaxaddtocart = 'no'; ";
		} else {
			$output .= "var noajaxaddtocart = 'yes'; ";
		}

		if( $active_window == 1 ) {
			$output .= "var activewindow = 'ck'; ";
		} else {
			$output .= "var activewindow = 'cart'; ";
		}

		$output .= "var iframe_src = '" .esc_url(home_url( '/instantio-checkout')). "'; ";

		// if( $detect->isMobile() && !$detect->isTablet() ) {
		// 	$output .= "var cartflymob = 'no'; ";
		// } else {
		// 	$output .= "var cartflymob = 'yes'; ";
		// }
		$output .= "var cartflymob = 'yes'; ";

		wp_add_inline_script( 'instantio-inline-scripts', $output );
	}
	add_action( 'wp_enqueue_scripts', 'instantio_custom_js', 99999 );
}
?>