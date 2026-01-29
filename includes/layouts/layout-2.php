<?php
defined( 'ABSPATH' ) || exit;
 
//  ob_start();

?> 

<div class="loader-container">
	<div class="db-spinner"></div>
</div>


<?php 

	do_action('ins_cart_toggle');

	// Empty Cart Content
	$on_emtpty_cart_content        = isset( insopt( 'empty-cart-content' )['on-empty-cart-content'] ) ? insopt( 'empty-cart-content' )['on-empty-cart-content'] : false;
	$empty_cart_text               = isset( insopt( 'empty-cart-content' )['empty_cart_text'] ) ? insopt( 'empty-cart-content' )['empty_cart_text'] : '';
	$empty_cart_button_prefix_text = isset( insopt( 'empty-cart-content' )['empty_cart_button_prefix_info'] ) ? insopt( 'empty-cart-content' )['empty_cart_button_prefix_info'] : '';
	$empty_cart_button_text        = isset( insopt( 'empty-cart-content' )['empty_cart_button_text'] ) ? insopt( 'empty-cart-content' )['empty_cart_button_text'] : '';
	$empty_cart_button_url         = isset( insopt( 'empty-cart-content' )['empty_cart_button_url'] ) ? insopt( 'empty-cart-content' )['empty_cart_button_url'] : '';

	$empty_cart_button_prefix_text = ! empty( $empty_cart_button_prefix_text ) && $on_emtpty_cart_content == true ? wp_strip_all_tags( __( $empty_cart_button_prefix_text , 'instantio' ) ) : __( 'Please go to', 'instantio' );
	$empty_cart_text               = ! empty( $empty_cart_text ) && $on_emtpty_cart_content               == true ? wp_strip_all_tags( __( $empty_cart_text , 'instantio' ) ) : __( 'Your cart is empty.', 'instantio' );
	$empty_cart_button_text        = ! empty( $empty_cart_button_text  ) && $on_emtpty_cart_content       == true ? wp_strip_all_tags( __( $empty_cart_button_text , 'instantio' ) ) : __( 'View Cart', 'instantio' );
	$empty_cart_button_url         = ! empty( $empty_cart_button_url ) && $on_emtpty_cart_content         == true ? $empty_cart_button_url : home_url( '/shop' );


	$display = 'ins-show';
	$hide_empty = 'hide';

	if(WC()->cart->is_empty()):   
		$hide_empty = 'ins-show';
		$display = 'hide'; 
	endif;


	do_action( 'ins_cart_header');

	echo sprintf( '<div class="ins-cart-empty %s"><span>%s <br> %s</span></div>',
		esc_attr__( $hide_empty ),
		esc_html__( $empty_cart_text, 'instantio' ),
		esc_html__( $empty_cart_button_prefix_text, 'instantio') . '<a href="' . esc_url( $empty_cart_button_url ) . '">' . esc_html__( $empty_cart_button_text, 'instantio' ) . '</a>'
	);

	do_action( 'ins_cart_content', $display ); 
	//  echo ob_get_clean();

?>