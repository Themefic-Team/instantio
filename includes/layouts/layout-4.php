<?php 

    /*
    * Instantio Single Step
    */

    defined( 'ABSPATH' ) || exit; ?> 

    <div class="loader-container">
        <div class="db-spinner"></div>
    </div>

<?php 
    do_action('ins_cart_toggle');
    do_action( 'ins_cart_header' );

    $display = 'ins-show';
    $hide_empty = 'hide';

    if(WC()->cart->is_empty()): 
        $hide_empty = 'ins-show';
        $display = 'hide'; 
    endif;

	echo sprintf( '<div class="ins-cart-empty %s"><span>%s <br> %s</span></div>', 
		esc_attr__($hide_empty), 
		esc_html__('Your cart is empty.','instantio'), 
		' Please go to <a href="'.esc_url( home_url( '/shop' ) ).'">'.esc_html__('Shop Now','instantio').'</a>' 
	);

	do_action( 'ins_cart_content', $display); 
	// do_action( 'ins_cart_content_single', $display); 

    // echo 'Single Step';




?>