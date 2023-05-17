<?php
defined( 'ABSPATH' ) || exit;
 
//  ob_start();

?> 

<div class="loader-container"><div class="db-spinner"></div></div>

 
<?php do_action('ins_cart_toggle') ?>

<?php do_action( 'ins_cart_header' ) ?> 
<?php 
if(WC()->cart->is_empty()): 
	echo sprintf( '<div class="ins-cart-empty"><span>%s <br> %s</span></div>', 
			esc_html__('Your cart is empty.','instantio'), 
			' Please go to <a href="'.esc_url( home_url( '/shop' ) ).'">'.esc_html__('Shop Now','instantio').'</a>' 
		); 

		
elseif(WC()->cart->is_empty() == false):
	do_action( 'ins_cart_content' ); 
endif;  
//  echo ob_get_clean();
?>