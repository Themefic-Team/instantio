<?php

/**
 *	Enqueue Layout 3 scripts
 *
 */

if ( !function_exists('fancybox_enqueue_scripts') ) {
	function fancybox_enqueue_scripts(){

		if (insopt( 'fancy-cdn' ) == true) {
			wp_enqueue_style( 'fancyBox-3', '//cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css', array(), '3.5.7' );
			wp_enqueue_script( 'fancyBox-3', '//cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js', array( 'jquery' ), '3.5.7', true );
		} else {
			wp_enqueue_style( 'fancyBox-3', INS_ASSETS_URL . '/css/jquery.fancybox.min.css', array(), '3.5.7' );
			wp_enqueue_script( 'fancyBox-3', INS_ASSETS_URL . '/js/fancybox.min.js', array( 'jquery' ), '3.5.7', true );
		}
		
	}
}
add_filter( 'wp_enqueue_scripts', 'fancybox_enqueue_scripts' );

if ( !function_exists('instantio_layout_3_enqueue_scripts') ) {
	function instantio_layout_3_enqueue_scripts(){

		wp_enqueue_style('instantio-layout-3', plugin_dir_url( __FILE__ ) . 'layout-3.css','', INSTANTIO_VERSION );
		wp_enqueue_script( 'instantio-layout-3', plugin_dir_url( __FILE__ ) . 'layout-3.js', array('jquery'), INSTANTIO_VERSION, true );
		
	}
}
add_filter( 'wp_enqueue_scripts', 'instantio_layout_3_enqueue_scripts' );

/**
 *	Layout 2 Content
 *
 */
if ( !function_exists('instantio_layout_3') ) {
	function instantio_layout_3( ){
		global $wiopt;	

		if ( is_checkout() ) {
			return;
		}
	
		if ( is_page('instantio-checkout') ) {
			return;
		}
		
		$toggle_panel_position = insopt( 'toggle-panel-position' );
		$toggler = insopt( 'ins-toggler' );
?>
		
		<div class="ins-container ins-lay3-container ins-position-<?php echo $toggle_panel_position; ?> <?php if( insopt( 'hide-toggler' ) == true ) { if( WC()->cart->get_cart_contents_count() <= 0 ){ echo 'nocart'; } } ?>">
			
		<?php if ($toggler == 'tog-1'){			
			toggler_1();			
		} elseif ($toggler == 'tog-2') {			
			toggler_2();			
		} ?>
						
			
		</div>
		<div id="ins-popup" class="ins-container ins-lay3-container" style="display: none;">
			<div class="ins-inner">
				<div class="ins-inner-container">
					<div class="ins-header">
						<h3><?php instantio_svg_icon('shopping-bag'); ?><?php esc_attr_e( 'Your Cart', 'instantio' ); ?></h3>
						
						<div class="ins-my-auto">					                
							<div class="cart-content">
								<?php require_once INS_INC_PATH . '/templates/cart.php'; ?>							
							</div>                
						</div>	
					</div>
					<div class="ins-body">
						<div class="ins-my-auto">
							<div class="empty-cart-content">							
								<h4><?php esc_attr_e( 'Your cart is empty', 'instantio' ); ?></h4>
							</div>						                               
						</div>				
					</div>
					<div class="ins-footer">
						<div class="empty-cart-content">							
							<a href="<?php echo wc_get_page_permalink( 'shop' ); ?>"><?php esc_attr_e( 'Continue Shopping', 'instantio' ); ?></a>
						</div>	
						<div class="cart-content">
							<div class="cart_totals"><p><?php esc_attr_e( 'Subtotal: ', 'instantio' ); ?><?php echo WC()->cart->get_cart_subtotal(); ?></p></div>
							<div class="footer-button">
								<?php cart_button(); ?>
								<?php checkout_button(); ?>							
							</div>	
						</div>
					</div>
				</div>
			</div>
			
		</div>
		
	<?php }
}
add_action( 'wp_footer', 'instantio_layout_3', 10 );

?>