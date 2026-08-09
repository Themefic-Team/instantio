<?php
defined( 'ABSPATH' ) || exit;

// Layout variables and hooks are compatibility points shared with Instantio Pro and theme integrations.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals
 
//  ob_start();
?> 
<div class="loader-container"><div class="db-spinner"></div></div>



<?php do_action( 'ins_cart_header' ) ?> 

<?php 

	// Empty Cart Content
	$on_emtpty_cart_content        = isset( insopt( 'empty-cart-content' )['on-empty-cart-content'] ) ? insopt( 'empty-cart-content' )['on-empty-cart-content'] : false;
	$empty_cart_text               = isset( insopt( 'empty-cart-content' )['empty_cart_text'] ) ? insopt( 'empty-cart-content' )['empty_cart_text'] : '';
	$empty_cart_button_prefix_text = isset( insopt( 'empty-cart-content' )['empty_cart_button_prefix_info'] ) ? insopt( 'empty-cart-content' )['empty_cart_button_prefix_info'] : '';
	$empty_cart_button_text        = isset( insopt( 'empty-cart-content' )['empty_cart_button_text'] ) ? insopt( 'empty-cart-content' )['empty_cart_button_text'] : '';
	$empty_cart_button_url         = isset( insopt( 'empty-cart-content' )['empty_cart_button_url'] ) ? insopt( 'empty-cart-content' )['empty_cart_button_url'] : '';

	$empty_cart_button_prefix_text = ! empty( $empty_cart_button_prefix_text ) && $on_emtpty_cart_content == true ? wp_strip_all_tags( $empty_cart_button_prefix_text ) : __( 'Please go to', 'instantio' );
	$empty_cart_text               = ! empty( $empty_cart_text ) && $on_emtpty_cart_content == true ? wp_strip_all_tags( $empty_cart_text ) : __( 'Your cart is empty.', 'instantio' );
	$empty_cart_button_text        = ! empty( $empty_cart_button_text ) && $on_emtpty_cart_content == true ? wp_strip_all_tags( $empty_cart_button_text ) : __( 'View Cart', 'instantio' );
	$empty_cart_button_url         = ! empty( $empty_cart_button_url ) && $on_emtpty_cart_content         == true ? $empty_cart_button_url : home_url( '/shop' );
	

$display = 'ins-show';
$hide_empty = 'hide';

if(WC()->cart->is_empty()): 
	$hide_empty = 'ins-show';
	$display = 'hide'; 
endif;
	echo sprintf(
		'<div class="ins-cart-empty %s"><span>%s <br> %s</span></div>',
		esc_attr( $hide_empty ),
		esc_html( $empty_cart_text ),
		esc_html( $empty_cart_button_prefix_text ) . ' ' . '<a href="' . esc_url( $empty_cart_button_url ) . '">' . esc_html( $empty_cart_button_text ) . '</a>'
	);
	do_action( 'ins_cart_content', $display); 
//  echo ob_get_clean();
?>
