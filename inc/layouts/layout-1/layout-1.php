<?php
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

		// Return if checkout page
		if ( is_checkout() ) {
			return;
		}
		
		$toggle_position_horizontal = insopt( 'toggle-position-horizontal' );
		$checkout_btn_txt = insopt( 'checkout-btn' )['checkout_button_text'];
		$checkout_btn_url = insopt( 'checkout-btn' )['checkout_button_url'];
		
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
}
add_action( 'wp_footer', 'instantio_layout_1', 10 );

/**
 *	Custom CSS function fot layout 1
 */
if( !function_exists( 'instantio_layout_1_custom_css' ) ){
	function instantio_layout_1_custom_css(){

		$output = '';

		if( insopt( 'wi-header-bg-colors' )['regular']  ) :
			$output .= '
			.ins-lay1-container .ins-cart-button{
				background-color: '.insopt( 'wi-header-bg-colors' )['regular'].';
				transition: 0.3s; }
			';
		endif;
		
		if( insopt( 'wi-header-bg-colors' )['hover']  ) :
			$output .= '
			.ins-lay1-container .ins-cart-button:hover{
				background-color: '.insopt( 'wi-header-bg-colors' )['hover'].';
			}
			';
		endif;
		
		if( insopt( 'wi-header-border-colors' )['regular']  ) :
			$output .= '
			.ins-lay1-container .ins-cart-button{
				border: 1px solid '.insopt( 'wi-header-border-colors' )['regular'].';
			}
			';
		endif;
		
		if( insopt( 'wi-header-border-colors' )['hover']  ) :
			$output .= '
			.ins-lay1-container .ins-cart-button:hover{
				border: 1px solid '.insopt( 'wi-header-border-colors' )['hover'].';
			}
			';
		endif;
		
		if( insopt( 'wi-header-icon-size' )['width']  ) :
			$output .= '
				.ins-lay1-container .ins-cart-button .cart-icon{
					width: '.insopt( 'wi-header-icon-size' )['width'].'px !important;
				}
				';
		endif;
		
		if( insopt( 'wi-header-text-size' )['font-size']  ) :
			$output .= '
				.ins-lay1-container .ins-cart-button span.ins_cart_total{
					font-size: '.insopt( 'wi-header-text-size' )['font-size'].'px;
				}
				';
		endif;

		if( insopt( 'wi-header-text-colors' )['regular']  ) :
			$output .= '
			.ins-lay1-container .ins-cart-button,
			.ins-lay1-container .ins-cart-button:not([href]):not([tabindex]){ /*for bootstrap override*/
				color: '.insopt( 'wi-header-text-colors' )['regular'].';
				transition: 0.3s;
			}
			.ins-lay1-container .ins-cart-button .cart-icon {
				fill: '.insopt( 'wi-header-text-colors' )['regular'].' !important;
				transition: 0.3s;
			}
			';
		endif;
		
		if( insopt( 'wi-header-text-colors' )['hover'] != '' ) :
			$output .= '
			.ins-lay1-container .ins-cart-button:hover,
			.ins-lay1-container .ins-cart-button:not([href]):not([tabindex]):hover,
			.ins-lay1-container .ins-cart-button:not([href]):not([tabindex]):focus{
				color: '.insopt( 'wi-header-text-colors' )['hover'].';
			}
			.ins-lay1-container .ins-cart-button:hover .cart-icon {fill: '.insopt( 'wi-header-text-colors' )['hover'].' !important;}
			';
		endif;
		
		if( insopt( 'wi-inner-bg-colors' )['regular']  ) :
			$output .= '
			.ins-lay1-container .ins-inner{
				background: '.insopt( 'wi-inner-bg-colors' )['regular'].';
				transition: 0.3s;
			}
			';
		endif;
		
		if( insopt( 'wi-inner-bg-colors' )['hover']  ) :
			$output .= '
			.ins-lay1-container .ins-inner:hover{
				background: '.insopt( 'wi-inner-bg-colors' )['hover'].';
			}
			';
		endif;
		
		if( insopt( 'wi-inner-border-colors' )['regular']  ) :
			$output .= '
			.ins-lay1-container .ins-inner{
				border: 1px solid '.insopt( 'wi-inner-border-colors' )['regular'].';
			}
			';
		endif;
		
		if( insopt( 'wi-inner-border-colors' )['hover']  ) :
			$output .= '
			.ins-lay1-container .ins-inner:hover{
				border: 1px solid '.insopt( 'wi-inner-border-colors' )['hover'].';
			}
			';
		endif;

		if( insopt( 'wi-inner-text-colors' )['regular']  ) :
			$output .= '
			.ins-lay1-container .ins-inner .ins-content{
				color: '.insopt( 'wi-inner-text-colors' )['regular'].';
				transition: 0.3s;
			}
			';
		endif;
		
		if( insopt( 'wi-inner-text-colors' )['hover']  ) :
			$output .= '
			.ins-lay1-container .ins-inner:hover .ins-content{
				color: '.insopt( 'wi-inner-text-colors' )['hover'].';
			}
			';
		endif;
		
		if( insopt( 'cart-button-open' ) == 'mouseover'  ) :
			$output .= '
			.ins-container.ins-position-right:hover a.ins-inner {
    		transform: translate(-67px, -50%);
			}
			.ins-container.ins-position-left:hover a.ins-inner {
    		transform: translate(67px, -50%);
			}
			';
		endif;

		wp_add_inline_style( 'instantio-common-styles', $output );
	}
}
add_action( 'wp_enqueue_scripts', 'instantio_layout_1_custom_css', 999999 );
?>