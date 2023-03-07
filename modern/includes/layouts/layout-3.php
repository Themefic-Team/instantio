<?php
defined( 'ABSPATH' ) || exit;
 
//  ob_start();
?> 
<div class="loader-container"><div class="db-spinner"></div></div>



<?php do_action( 'ins_cart_header' ) ?> 

<?php if(WC()->cart->is_empty()): 
	echo '<div class="woocommerce-message" role="alert">Cart is empty.</div>'; 
elseif(WC()->cart->is_empty() == false):
	do_action( 'ins_cart_content' );
endif;
//  echo ob_get_clean();
?>