<?php 
defined( 'ABSPATH' ) || exit;

// Mobile detect
if( class_exists( 'INS_Mobile_Detect' ) ) {
	$detect = new INS_Mobile_Detect;
}

if ( is_plugin_active( 'wooinstant/wooinstant.php' ) ) {
	$dedicated_mobile = !empty(insopt('mobile')) ? insopt('mobile') : '0';
} else {
	$dedicated_mobile = '0';
}

/**
 * Add instantio class to body
 */
add_filter( 'body_class', function( $classes ) {
    return array_merge( $classes, array( 'instantio' ) );
} );

/**
 * Ajax cart item count
 * Conditiional CSS
 * Cart fragments
 */
if ($detect->isMobile() && !$detect->isTablet() && $dedicated_mobile == true) {} else {
	if (!function_exists('ins_cart_fragments')){
		function ins_cart_fragments($fragments) {
			ob_start();
			?>
			<div class="ins-cart-fragments">

				<?php
				$cart_item_count = WC()->cart->get_cart_contents_count();
				$hide_toggler = !empty(insopt( 'hide-toggler' )) ? insopt( 'hide-toggler' ) : '';
				?>

				<script type='text/javascript'>
					var wiCartTotal = <?php echo $cart_item_count; ?>;
					jQuery('.ins_cart_total').html('<?php echo $cart_item_count; ?>');
				</script>

				<?php
				// Hide toggler when cart count 0
				if( $hide_toggler == true ) { 
					if ($cart_item_count == 0) {
					?>
						<script>					
							jQuery(document).ready(function() {
								jQuery('.ins-container').addClass( 'nocart' );
								jQuery('html').removeClass('ins-panel-open');
								jQuery.fancybox.close();
							});
						</script>
					<?php 
					} else { 
					?>
						<script>
							jQuery('.ins-container').removeClass( 'nocart' );
						</script>
					<?php
					} 
				} 

				if ( $cart_item_count == 0 ) {
				?>												
					<style type="text/css">
						.cart-content {display:none !important;}
					</style>				
				<?php
				} else {
				?>
					<script>
						jQuery('.ins-container').removeClass( 'nocart' );
					</script>				
					<style type="text/css">
						.empty-cart-content {display:none !important;}
					</style>				
				<?php 
				}
				?>

			</div>
			<?php
			$fragments['.ins-cart-fragments'] = ob_get_clean();
			return $fragments;
		}
	}
	add_filter( 'woocommerce_add_to_cart_fragments', 'ins_cart_fragments', 10, 1 );
}

/**
 *	Ajax variable products quick view
 */
// actions
add_action('wp_ajax_wi_variable_product_quick_view', 'instantio_ajax_quickview_variable_products');
add_action('wp_ajax_nopriv_wi_variable_product_quick_view', 'instantio_ajax_quickview_variable_products');

function instantio_ajax_quickview_variable_products(){
	global $post, $product, $woocommerce;
	check_ajax_referer( 'ins_ajax_nonce', 'security' );

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

/**
 *	Ajax single product add to cart
 */
// actions
add_action('wp_ajax_wi_single_ajax_add_to_cart', 'instantio_single_ajax_add_to_cart');
add_action('wp_ajax_nopriv_wi_single_ajax_add_to_cart', 'instantio_single_ajax_add_to_cart');

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
 * Ajax clear cart
 */
add_action( 'wp_ajax_insclearcart', 'insclearcartCallBack' );
add_action( 'wp_ajax_nopriv_insclearcart', 'insclearcartCallBack' );
function insclearcartCallBack() {
    WC()->cart->empty_cart();
    die();
}

/*
 * Buttons
 */
// Cart Button
if ( ! function_exists( 'cart_button' ) ) {
	function cart_button() {

		$on_cart_btn = !empty(insopt( 'cart-btn' )['on-cart-btn']) ? insopt( 'cart-btn' )['on-cart-btn'] : '';
		$cart_button_text = !empty(insopt( 'cart-btn' )['cart_button_text']) ? insopt( 'cart-btn' )['cart_button_text'] : '';
		$cart_button_url = !empty(insopt( 'cart-btn' )['cart_button_url']) ? insopt( 'cart-btn' )['cart_button_url'] : '';

		if ($on_cart_btn == true) {
			if ($cart_button_text){
				$cart_text = wp_strip_all_tags( __( $cart_button_text, 'instantio' ));
			} else {
				$cart_text = __( 'View Cart', 'instantio' );
			}
			if ($cart_button_url){
				$cart_url = esc_url($cart_button_url); // PHPCS: XSS ok.
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

		$on_checkout_btn = !empty(insopt( 'checkout-btn' )['on-checkout-btn']) ? insopt( 'checkout-btn' )['on-checkout-btn'] : '';
		$checkout_button_text = !empty(insopt( 'checkout-btn' )['checkout_button_text']) ? insopt( 'checkout-btn' )['checkout_button_text'] : '';
		$checkout_button_url = !empty(insopt( 'checkout-btn' )['checkout_button_url']) ? insopt( 'checkout-btn' )['checkout_button_url'] : '';

		if ($on_checkout_btn == true) {
			if ($checkout_button_text){
				$checkout_text = wp_strip_all_tags( __( $checkout_button_text, 'instantio' ));
			} else {
				$checkout_text = __( 'Checkout Now', 'instantio' );
			}
			if ($checkout_button_url){
				$checkout_url = esc_url($checkout_button_url); // PHPCS: XSS ok.
			} else {
				$checkout_url = wc_get_checkout_url();
			}
	
			echo '<a style="margin-bottom: 0px;" href="' .$checkout_url. '">' .$checkout_text. '</a>';
		}		
	}
}

/**
 * Enqueue FancyBox
 * 
 * v3.5.7
 */
if ( !function_exists('fancybox_enqueue_scripts') ) {
	function fancybox_enqueue_scripts(){

		$fancy_cdn = !empty(insopt( 'fancy-cdn' )) ? insopt( 'fancy-cdn' ) : '';

		if ($fancy_cdn == true) {
			wp_enqueue_style( 'fancyBox-3', '//cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css', array(), '3.5.7' );
			wp_enqueue_script( 'fancyBox-3', '//cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js', array( 'jquery' ), '3.5.7', true );
		} else {
			wp_enqueue_style( 'fancyBox-3', INS_ASSETS_URL . '/css/jquery.fancybox.min.css', array(), '3.5.7' );
			wp_enqueue_script( 'fancyBox-3', INS_ASSETS_URL . '/js/fancybox.min.js', array( 'jquery' ), '3.5.7', true );
		}
		
	}
}


/**
 *	Including Layouts
 */

if ($detect->isMobile() && !$detect->isTablet() && $dedicated_mobile == true) {
	return;
} else {

	require_once( INS_LAYOUTS_PATH . '/toggler.php' );

	$ins_layout = !empty(insopt( 'ins-layout' )) ? insopt( 'ins-layout' ) : '';

	if ($ins_layout == 1) {
		require_once INS_LAYOUTS_PATH . '/layout-1/layout-1.php';
	} elseif ($ins_layout == 2) {
		require_once INS_LAYOUTS_PATH . '/layout-2/layout-2.php';
	} elseif ($ins_layout == 3) {
		require_once INS_LAYOUTS_PATH . '/layout-3/layout-3.php';
	}
}

/**
 * Black Friday Deals 2022
 */ 
if(!function_exists('ins_black_friday_20222_admin_notice')){
	function ins_black_friday_20222_admin_notice(){
		$deal_link =sanitize_url('https://themefic.com/go/instantio-bf-deal');
		// $get_current_screen = get_current_screen(); 
		if(!isset($_COOKIE['ins_dismiss_admin_notice'])){
			?>
			<style> 
				.tf_black_friday_20222_admin_notice a:focus {
					box-shadow: none;
				} 
				.tf_black_friday_20222_admin_notice {
					padding: 7px;
					position: relative;
						z-index: 10;
				}
			</style>
			<div class="notice notice-success tf_black_friday_20222_admin_notice">
			
				<a href="<?php echo $deal_link; ?>" target="_blank" >
					<img  style="width: auto; height: 100px;" src="<?php echo INS_URL ?>/assets/img/BLACK_FRIDAY_BACKGROUND_GRUNGE.jpg" alt="BLACK FRIDAY 2022">
				</a> 
				<button type="button" class="notice-dismiss tf_black_friday_notice_dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
			</div> 
			<script>
				jQuery(document).ready(function($) {
					$(document).on('click', '.tf_black_friday_notice_dismiss', function( event ) {
						jQuery('.tf_black_friday_20222_admin_notice').css('display', 'none')
						data = {
							action : 'ins_black_friday_notice_dismiss_callback',
						};

						$.ajax({
							url: ajaxurl,
							type: 'post',
							data: data,
							success: function (data) { ;
							},
							error: function (data) { 
							}
						});
					});
				});
			</script>
			
			<?php 
		} 
		
	} 
	if (strtotime('2022-12-01') > time()) {
		// add_action( 'admin_notices', 'ins_black_friday_20222_admin_notice' ); 
	}    
	
	function ins_black_friday_notice_dismiss_callback() { 
		$cookie_name = "ins_dismiss_admin_notice";
		$cookie_value = "1";
		setcookie($cookie_name, $cookie_value, time() + (86400 * 3), "/"); 
		wp_die();
	}
	add_action( 'wp_ajax_ins_black_friday_notice_dismiss_callback', 'ins_black_friday_notice_dismiss_callback' );
}

?>