<?php
defined( 'ABSPATH' ) || exit;

 ob_start(); 
 ?>
	<div class="ins-fixed-toogle"> <?php do_action( 'instantio_cart_toggle' ); ?></div>
 <?php
	 // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Buffer contains registered cart-toggle callback output.
	 echo ob_get_clean();
?>
