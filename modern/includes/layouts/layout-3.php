<?php
defined( 'ABSPATH' ) || exit;
 
 ob_start();
?> 
<div class="loader-container"><div class="db-spinner"></div></div>



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