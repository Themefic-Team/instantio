<?php
defined( 'ABSPATH' ) || exit;
 
 ob_start();
?> 
<div class="loader-container"><div class="db-spinner"></div></div>
<div class="ins-click-to-show ins-toggle-btn">
	<svg class="cart-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" xmlns:v="https://vecta.io/nano"><path d="M490.299 185.717H384.08L324.496 49.284c-3.315-7.591-12.157-11.06-19.749-7.743s-11.059 12.158-7.743 19.75l54.34 124.427H160.656l54.34-124.427c3.315-7.592-.151-16.434-7.743-19.75a15 15 0 0 0-19.749 7.743L127.92 185.717H21.701c-13.895 0-24.207 12.579-21.167 25.82l55.935 243.63c2.221 9.674 11.015 16.55 21.167 16.55h356.728c10.152 0 18.946-6.876 21.167-16.55l55.935-243.63c3.04-13.24-7.273-25.82-21.167-25.82zm-359.557 46.004c-2.004 0-4.041-.404-5.996-1.258-7.592-3.315-11.059-12.157-7.743-19.75l11.268-25.802h32.736l-16.512 37.808c-2.461 5.639-7.971 9.002-13.753 9.002zM181 391.717c0 8.284-6.716 15-15 15s-15-6.716-15-15v-110c0-8.284 6.716-15 15-15s15 6.716 15 15zm90 0c0 8.284-6.716 15-15 15s-15-6.716-15-15v-110c0-8.284 6.716-15 15-15s15 6.716 15 15zm90 0c0 8.284-6.716 15-15 15s-15-6.716-15-15v-110c0-8.284 6.716-15 15-15s15 6.716 15 15zm26.253-161.254a14.94 14.94 0 0 1-5.995 1.258c-5.782 0-11.292-3.362-13.754-9.001l-16.512-37.808h32.736l11.268 25.802a15 15 0 0 1-7.743 19.749z"></path></svg>

	<span class="ins-items-count"><span id="ins_cart_total" class="ins_cart_total"><?php echo WC()->cart->get_cart_contents_count(); ?></span></span>
</div>
<span class="ins-checkout-close"><svg width="18px" height="18px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"><path fill="#000000" d="M764.288 214.592 512 466.88 259.712 214.592a31.936 31.936 0 0 0-45.12 45.12L466.752 512 214.528 764.224a31.936 31.936 0 1 0 45.12 45.184L512 557.184l252.288 252.288a31.936 31.936 0 0 0 45.12-45.12L557.12 512.064l252.288-252.352a31.936 31.936 0 1 0-45.12-45.184z"/></svg></span> 

<h4 class="ins-label">Your Cart</h4>
<?php if(WC()->cart->is_empty()): 
	echo '<div class="woocommerce-message" role="alert">Cart is empty.</div>';
	elseif(WC()->cart->is_empty() == false):

?>
<div class="ins-content">
	<div class="ins-cart-inner">
		<?php require_once apply_filters( 'ins_cart_path', INS_INC_PATH . '/templates/cart.php' ); ?>	
	</div> 
	<div class="ins-cart-btns">
		<a href="#" class="view-cart active">View Cart</a>
		<a href="#" class="checkout">Checkout Now</a>
	</div>  
</div>

<?php

endif;

 echo ob_get_clean();
?>