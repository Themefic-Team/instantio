<?php
defined( 'ABSPATH' ) || exit;

// Layout variables and hooks are compatibility points shared with Instantio Pro and theme integrations.
//  ob_start();
?> 
<div class="loader-container"><div class="db-spinner"></div></div>



<?php do_action( 'instantio_cart_header' ) ?>

<?php 

	// Empty Cart Content
	$instantio_empty_cart_enabled        = isset( instantio_get_option( 'empty-cart-content' )['on-empty-cart-content'] ) ? instantio_get_option( 'empty-cart-content' )['on-empty-cart-content'] : false;
	$instantio_empty_cart_text               = isset( instantio_get_option( 'empty-cart-content' )['empty_cart_text'] ) ? instantio_get_option( 'empty-cart-content' )['empty_cart_text'] : '';
	$instantio_empty_cart_button_prefix_text = isset( instantio_get_option( 'empty-cart-content' )['empty_cart_button_prefix_info'] ) ? instantio_get_option( 'empty-cart-content' )['empty_cart_button_prefix_info'] : '';
	$instantio_empty_cart_button_text        = isset( instantio_get_option( 'empty-cart-content' )['empty_cart_button_text'] ) ? instantio_get_option( 'empty-cart-content' )['empty_cart_button_text'] : '';
	$instantio_empty_cart_button_url         = isset( instantio_get_option( 'empty-cart-content' )['empty_cart_button_url'] ) ? instantio_get_option( 'empty-cart-content' )['empty_cart_button_url'] : '';

	$instantio_empty_cart_button_prefix_text = ! empty( $instantio_empty_cart_button_prefix_text ) && $instantio_empty_cart_enabled == true ? wp_strip_all_tags( $instantio_empty_cart_button_prefix_text ) : __( 'Please go to', 'instantio' );
	$instantio_empty_cart_text               = ! empty( $instantio_empty_cart_text ) && $instantio_empty_cart_enabled == true ? wp_strip_all_tags( $instantio_empty_cart_text ) : __( 'Your cart is empty.', 'instantio' );
	$instantio_empty_cart_button_text        = ! empty( $instantio_empty_cart_button_text ) && $instantio_empty_cart_enabled == true ? wp_strip_all_tags( $instantio_empty_cart_button_text ) : __( 'View Cart', 'instantio' );
	$instantio_empty_cart_button_url         = ! empty( $instantio_empty_cart_button_url ) && $instantio_empty_cart_enabled         == true ? $instantio_empty_cart_button_url : home_url( '/shop' );
	

$instantio_display = 'ins-show';
$instantio_hide_empty = 'hide';

if(WC()->cart->is_empty()): 
	$instantio_hide_empty = 'ins-show';
	$instantio_display = 'hide';
endif;
	echo sprintf(
		'<div class="ins-cart-empty %s"><span>%s <br> %s</span></div>',
		esc_attr( $instantio_hide_empty ),
		esc_html( $instantio_empty_cart_text ),
		esc_html( $instantio_empty_cart_button_prefix_text ) . ' ' . '<a href="' . esc_url( $instantio_empty_cart_button_url ) . '">' . esc_html( $instantio_empty_cart_button_text ) . '</a>'
	);
	do_action( 'instantio_cart_content', $instantio_display);
//  echo ob_get_clean();
?>
