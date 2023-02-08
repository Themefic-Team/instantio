<?php
defined( 'ABSPATH' ) || exit;

/**
 *	Enqueue Layout 1 scripts
 *
 */
if ( !function_exists('instantio_layout_1_enqueue_scripts') ) {
	function instantio_layout_1_enqueue_scripts(){

		wp_enqueue_style('instantio-layout-1', plugin_dir_url( __FILE__ ) . 'layout-1.css','', INSTANTIO_VERSION );
		wp_enqueue_script( 'instantio-layout-1', plugin_dir_url( __FILE__ ) . 'layout-1.js', array('jquery'), INSTANTIO_VERSION, true );

	}
}
add_filter( 'wp_enqueue_scripts', 'instantio_layout_1_enqueue_scripts', 99999 );

if ( !function_exists('instantio_layout_1') ) {
	function instantio_layout_1( ){

		// Return if WooCommerce not active
		if ( !class_exists( 'woocommerce' ) ) {
    		return;
		}
		
		// Return if checkout page
		if ( class_exists( 'woocommerce' ) ) {
    		if (is_checkout()) {
    			return;
    		}
		}

		// Return if cart page
		if ( class_exists( 'woocommerce' ) ) {
    		if (is_page( 'cart' ) || is_cart()) {
    			return;
    		}
		}
		
		$toggle_position_horizontal = !empty(insopt( 'toggle-position-horizontal' )) ? insopt( 'toggle-position-horizontal' ) : '';
		$checkout_btn_txt = !empty(insopt( 'checkout-btn' )['checkout_button_text']) ? insopt( 'checkout-btn' )['checkout_button_text'] : '';
		$checkout_btn_url = !empty(insopt( 'checkout-btn' )['checkout_button_url']) ? insopt( 'checkout-btn' )['checkout_button_url'] : '';
		
		if ($checkout_btn_txt) {
			$checkout_txt = wp_strip_all_tags( __( $checkout_btn_txt, 'instantio' ));
		} else {
			$checkout_txt = __( 'View Checkout', 'instantio' );
		}

		if ($checkout_btn_url) {
			$checkout_url = esc_url($checkout_btn_url); // PHPCS: XSS ok.
		} else {
			$checkout_url = wc_get_checkout_url();
		}
		 ?>
			<div class="ins-container ins-lay1-container ins-position-<?php echo $toggle_position_horizontal; ?> <?php if( insopt( 'hide-toggler' ) == true ) { if( WC()->cart->get_cart_contents_count() <= 0 ){ echo 'nocart'; } } ?>">

				<div id="ins-toggle-button" class="ins-cart-button">
					<?php instantio_svg_icon(insopt( 'cart-icon' )); ?>				
					<span class="ins_cart_total"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
				</div>
				
				<a href="<?php echo $checkout_url; ?>" class="ins-inner">
					<div class="ins-content woocommerce-cart-form">								
						<?php echo $checkout_txt; ?>
					</div>
				</a>

			</div>
			<div class="ins-cart-fragments"></div>
			<?php
	}
	add_action( 'wp_footer', 'instantio_layout_1', 10 );
}
 
?>