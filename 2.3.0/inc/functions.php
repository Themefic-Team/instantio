<?php 
defined( 'ABSPATH' ) || exit;

/**
 * Instantio Cart Fragments
 */
if ( !function_exists('instantio_cart_fragments') ) {
	function instantio_cart_fragments( $fragments ) {
		global $woocommerce;

		ob_start();
		instantio_cart_count();
		$fragments['span.ins_cart_total'] = ob_get_clean();

		return $fragments;

	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'instantio_cart_fragments', 10, 1 );

/**
 * Cart Count function
 * Ajax CSS JS
 */
if ( ! function_exists( 'instantio_cart_count' ) ) {
	function instantio_cart_count() {
		$hide_toggler = insopt( 'hide-toggler' );
		$cart_item_count = WC()->cart->get_cart_contents_count();
?>

		<span class="ins_cart_total">
			
			<?php if( $hide_toggler == true ) { 
				if ($cart_item_count == 0) {
			?>
				<script>					
					(function($) {
						'use strict';
						jQuery(document).ready(function() {
							jQuery('.ins-container').addClass( 'nocart' );
							$.fancybox.close();
						});
					})(jQuery);

				</script>
				<script>
				
			</script>
				<?php } else { ?>
				<script>
					jQuery('.ins-container').removeClass( 'nocart' );
				</script>
			<?php } 
			} ?>
			
			<script type='text/javascript'>
			/* <![CDATA[ */
				var wiCartTotal = <?php echo WC()->cart->get_cart_contents_count(); ?>;
			/* ]]> */
			</script>
			
			<?php if ( WC()->cart->get_cart_contents_count() == 0 ) { ?>			
								
			<style type="text/css">
				html.ins-panel-open, html.ins-panel-open body { overflow: auto; }					
				.cart-content { display: none; }
			</style>
			
			<?php } else { ?>

			<script>
				jQuery('.ins-container').removeClass( 'nocart' );
			</script>
				
			<style type="text/css">
				.cart-content { display: inherit; }
				.empty-cart-content { display: none; }
			</style>
			
			<?php } 
			echo WC()->cart->get_cart_contents_count(); ?>
		</span> 
<?php
	}
}

/**
 *	Instantio Ajax functions
 */
// variable product quick view ajax actions
add_action('wp_ajax_wi_variable_product_quick_view', 'instantio_ajax_quickview_variable_products');
add_action('wp_ajax_nopriv_wi_variable_product_quick_view', 'instantio_ajax_quickview_variable_products');

// variable product quick view ajax function
function instantio_ajax_quickview_variable_products(){
	global $post, $product, $woocommerce;
	check_ajax_referer( 'wi_ajax_nonce', 'security' );

	add_action( 'wcqv_product_data', 'woocommerce_template_single_add_to_cart');

	$product_id = $_POST['product_id'];
    $wiqv_loop = new WP_Query(
        array(
            'post_type' => 'product',
            'p' => $product_id,
        )
    );

    ob_start();
	if( $wiqv_loop->have_posts() ) :
		while ( $wiqv_loop->have_posts() ) : $wiqv_loop->the_post(); ?>
			<?php wc_get_template( 'single-product/add-to-cart/variation.php' ); ?>
			<script>
	            jQuery.getScript("<?php echo $woocommerce->plugin_url(); ?>/assets/js/frontend/add-to-cart-variation.min.js");
	 	    </script> <?php
			do_action( 'wcqv_product_data' );
	 	endwhile;
	endif;

	echo ob_get_clean();

	wp_die();
}

// single product ajax add to cart actions
add_action('wp_ajax_wi_single_ajax_add_to_cart', 'instantio_single_ajax_add_to_cart');
add_action('wp_ajax_nopriv_wi_single_ajax_add_to_cart', 'instantio_single_ajax_add_to_cart');

// single product ajax add to cart actions
function instantio_single_ajax_add_to_cart() {

    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    $variation_id = absint($_POST['variation_id']);
    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id);

    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {

        do_action('woocommerce_ajax_added_to_cart', $product_id);

        if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
            wc_add_to_cart_message(array($product_id => $quantity), true);
        }

        WC_AJAX :: get_refreshed_fragments();
    } else {

        $data = array(
            'error' => true,
            'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));

        echo wp_send_json($data);
    }

    wp_die();
}

/*
 * Clear Cart
 */
function ins_empty_cart_url() {
    if ( isset( $_GET['empty-cart'] ) ) {
        global $woocommerce;
        $woocommerce->cart->empty_cart();
		header('Location: '.$_SERVER['HTTP_REFERER']); exit;
    }
}
add_action( 'init', 'ins_empty_cart_url' );

/*
 * Buttons
 */
// Cart Button
if ( ! function_exists( 'cart_button' ) ) {
	function cart_button() {
		if (insopt( 'cart-btn' )['on-cart-btn'] == true) {
			if (insopt( 'cart-btn' )['cart_button_text']){
				$cart_text = insopt( 'cart-btn' )['cart_button_text'];
			} else {
				$cart_text = 'View Cart';
			}
			if (insopt( 'cart-btn' )['cart_button_url']){
				$cart_url = insopt( 'cart-btn' )['cart_button_url'];
			} else {
				$cart_url = wc_get_cart_url();
			}
	
			echo '<a href="' .$cart_url. '">' .$cart_text. '</a>';
		}		
	}
}

// Checkout Button
if ( ! function_exists( 'checkout_button' ) ) {
	function checkout_button() {
		if (insopt( 'checkout-btn' )['on-checkout-btn'] == true) {
			if (insopt( 'checkout-btn' )['checkout_button_text']){
				$checkout_text = insopt( 'checkout-btn' )['checkout_button_text'];
			} else {
				$checkout_text = 'Checkout Now';
			}
			if (insopt( 'checkout-btn' )['checkout_button_url']){
				$checkout_url = insopt( 'checkout-btn' )['checkout_button_url'];
			} else {
				$checkout_url = wc_get_checkout_url();
			}
	
			echo '<a style="margin-bottom: 0px;" href="' .$checkout_url. '">' .$checkout_text. '</a>';
		}		
	}
}


/**
 *	Including Layouts
 */

if (file_exists( INS_LAYOUTS_PATH . '/toggler.php')) {
	require_once( INS_LAYOUTS_PATH . '/toggler.php' );
}

if ( file_exists( INS_LAYOUTS_PATH . '/layout-1/layout-1.php' ) && insopt( 'ins-layout' ) == 1 ) {
	require_once INS_LAYOUTS_PATH . '/layout-1/layout-1.php';
} elseif ( file_exists( INS_LAYOUTS_PATH . '/layout-2/layout-2.php' ) && insopt( 'ins-layout' ) == 2 ) {
	require_once INS_LAYOUTS_PATH . '/layout-2/layout-2.php';
} elseif ( file_exists( INS_LAYOUTS_PATH . '/layout-3/layout-3.php' ) && insopt( 'ins-layout' ) == 3 ) {
	require_once INS_LAYOUTS_PATH . '/layout-3/layout-3.php';
}
?>