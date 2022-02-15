<?php

/**
 *	Enqueue Layout 2 scripts
 *
 */
if ( !function_exists('instantio_layout_2_enqueue_scripts') ) {
function instantio_layout_2_enqueue_scripts(){

	wp_enqueue_style('instantio-layout-2', plugin_dir_url( __FILE__ ) . 'layout-2.css','', INSTANTIO_VERSION );
	//wp_enqueue_script( 'instantio-layout-2', plugin_dir_url( __FILE__ ) . 'layout-2.js', array('jquery'), INSTANTIO_VERSION, true );
	
}
}
add_filter( 'wp_enqueue_scripts', 'instantio_layout_2_enqueue_scripts', 99999 );

/**
 *	Layout 2 Content
 *
 */
if ( !function_exists('instantio_layout_2') ) {
	function instantio_layout_2( ){
		
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
		
		$toggle_panel_position = insopt( 'toggle-panel-position' );
		$toggler = insopt( 'ins-toggler' );
?>
		
		<div class="ins-container ins-lay2-container ins-position-<?php echo $toggle_panel_position; ?> <?php if( insopt( 'hide-toggler' ) == true ) { if( WC()->cart->get_cart_contents_count() <= 0 ){ echo 'nocart'; } } ?>">
			
		<div class="ins-overlay"></div>
		<div class="loader-container-fixed"><div class="db-spinner"></div></div>
		<div class="loader-overlay-fixed"></div>

		<?php if ($toggler == 'tog-1'){			
			toggler_1();			
		} elseif ($toggler == 'tog-2') {			
			toggler_2();			
		} ?>
			
			<div class="ins-inner">
				<span id="ins-close" class="ins-close"><?php instantio_svg_icon('close'); ?></span>
				<div class="ins-inner-container ins-free-cart">
					<div class="ins-header">
						<h3><?php instantio_svg_icon('shopping-bag'); ?><?php _e( 'Your Cart', 'instantio' ); ?></h3>

						<div class="ins-my-auto">					                
							<div class="cart-content">
								<?php require_once INS_INC_PATH . '/templates/cart.php'; ?>							
							</div>                
						</div>	
					</div>
					<div class="ins-body">
						<div class="ins-my-auto">
							<div class="empty-cart-content">							
								<h4><?php _e( 'Your cart is empty', 'instantio' ); ?></h4>
							</div>						                              
						</div>				
					</div>
					<div class="ins-footer">
						<div class="empty-cart-content">							
							<a href="<?php echo wc_get_page_permalink( 'shop' ); ?>"><?php _e( 'Continue Shopping', 'instantio' ); ?></a>
						</div>	
						<div class="cart-content">
							<div class="cart_totals"><p><?php _e( 'Subtotal: ', 'instantio' ); ?><?php echo WC()->cart->get_cart_subtotal(); ?></p></div>
							<div class="footer-button">	
                                <?php cart_button(); ?>				
								<?php checkout_button(); ?>
							</div>							
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="ins-cart-fragments"></div>
		
	<?php }
}
add_action( 'wp_footer', 'instantio_layout_2', 10 );

?>