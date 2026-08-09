<?php
defined( 'ABSPATH' ) || exit;

// Layout hooks are public compatibility points shared with Instantio Pro and theme integrations.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals
 
 ob_start(); 
 ?>
	<div class="ins-fixed-toogle"> <?php do_action( 'ins_cart_toggle' ); ?></div>
 <?php
	 // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Buffer contains registered cart-toggle callback output.
	 echo ob_get_clean();
?>
