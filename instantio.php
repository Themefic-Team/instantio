<?php
/**
 * Plugin Name: Instantio - Instant Checkout for WooCommerce
 * Plugin URI: https://wpinstant.io/
 * Description: WooCommerce Quick Checkout through Side Cart, Floating Cart, Popup Cart & Direct Checkout Button. The whole checkout process would take only 15-25 seconds. Less Cart Abandonment and Better Sales Rate.
 * Author: Themefic
 * Text Domain: instantio
 * Domain Path: /lang/
 * Author URI: https://themefic.com
 * Tags: woocommerce, direct checkout, floating cart, side cart, ajax cart, cart popup, ajax add to cart, one page checkout, single page checkout, fly cart, mini cart, quick buy, instant checkout, quick checkout, same page checkout, sidebar cart, sticky cart, woocommerce ajax, one click checkout, woocommerce one page checkout, direct checkout woocommerce, woocommerce one click checkout, woocommerce quick checkout, woocommerce express checkout, woocommerce simple checkout, skip cart page woocommerce, woocommerce cart popup, edit woocommerce checkout page, woocommerce direct checkout
 * Version: 2.4.2
 * WC tested up to: 5.9
 */
 
// don't load directly
defined( 'ABSPATH' ) || exit;

// Define INSTANTIO_VERSION.
if ( ! defined( 'INSTANTIO_VERSION' ) ) {
	define( 'INSTANTIO_VERSION', '2.4.2' );
}
// INSTANTIO Defines
define( 'INS_PATH', plugin_dir_path( __FILE__ ) );
define( 'INS_ADMIN_PATH', INS_PATH.'admin' );
define( 'INS_INC_PATH', INS_PATH.'inc' );
define( 'INS_LAYOUTS_PATH', INS_INC_PATH.'/layouts' );
define( 'INS_URL', plugin_dir_url( __FILE__ ) );
define( 'INS_INC_URL', INS_URL.'inc' );
define( 'INS_LAYOUTS_URL', INS_URL.'inc/layouts' );
define( 'INS_ASSETS_URL', INS_URL.'assets' );
define( 'INS_ADMIN_URL', INS_URL.'admin' );

/**
 * Including Plugin file for security
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Load plugin textdomain.
 */
function ins_load_textdomain() {
	load_plugin_textdomain( 'instantio', false, INS_PATH . '/lang/' );
}
add_action( 'init', 'ins_load_textdomain' );

/*
 * Plugins Loaded
 * Including Option Framework
 * Including Options
 * Disable WooCommerce Notices
 */
if ( ! function_exists( 'instantio_plugin_loaded_action' ) ) {
	function instantio_plugin_loaded_action() {
		
		// Option Framework
		if ( file_exists( INS_ADMIN_PATH .'/framework/framework.php' ) ) {
			require_once( INS_ADMIN_PATH .'/framework/framework.php' );
		}
		
		// Options
		if ( file_exists( WP_PLUGIN_DIR .'/wooinstant/admin/config.php' )  && defined( 'INSTANTIO_PRO_CONFIG' ) && defined( 'INSTANTIO_PRO' ) ) {
			require_once( WP_PLUGIN_DIR .'/wooinstant/admin/config.php' );
		} elseif ( file_exists( INS_ADMIN_PATH .'/config.php' ) ) {
			require_once( INS_ADMIN_PATH .'/config.php' );
		}

		// Disable WooCommerce Notices
		if ( class_exists( 'woocommerce' ) ) {
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
			remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices', 10 );
			add_filter('woocommerce_cart_item_removed_notice_type', '__return_null');
		}
	}
}
add_action( 'plugins_loaded', 'instantio_plugin_loaded_action' );

/*
 * Global Admin Get Option
 */
if ( ! function_exists( 'insopt' ) ) {
	function insopt( $option = '', $default = null ) {
	  $options = get_option( 'wiopt' ); 
	  return ( isset( $options[$option] ) ) ? $options[$option] : $default;
	}
}

// Mobile Detect
if (!class_exists('Mobile_Detect')) {
	require_once INS_INC_PATH . '/mobile-detect.php';
}
// Functions
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ){
	if (!defined( 'INSTANTIO_PRO_FUNCTIONS' )) {
		require_once INS_INC_PATH . '/functions.php';
	}
}
// SVG Icons
if (!defined( 'INSTANTIO_PRO_ICONS' )) {
	require_once( INS_INC_PATH . '/svg-icons.php' );
}
// Styles & Scripts
if (!defined( 'INSTANTIO_PRO_SCRIPT' )) {
	require_once INS_INC_PATH . '/style-script.php';
}


/**
 * Notice if WooCommerce is not installed or inactive
 */
if ( !function_exists('instantio_is_woocommerce') ) {
	function instantio_is_woocommerce() {

		if ( !class_exists( 'WooCommerce' ) && file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) ) { ?>

			<div class="notice notice-error ins-error">
				<p><?php instantio_svg_icon('close'); ?> <strong><?php esc_attr_e( 'Instantio requires WooCommerce to be activated. ', 'instantio' ); ?> <a href="<?php echo esc_url( admin_url('/plugin-install.php?s=slug:woocommerce&tab=search&type=term') ); ?>"><?php esc_attr_e( 'Activate Now', 'instantio' ); ?></a></strong></p>
			</div>

		<?php } elseif ( !class_exists( 'WooCommerce' ) && !file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) ) { ?>

			<div class="notice notice-error">
				<p><strong><?php esc_attr_e( 'Instantio requires WooCommerce to be installed & activated. ', 'instantio' ); ?> <a href="<?php echo esc_url( admin_url('/plugin-install.php?s=slug:woocommerce&tab=search&type=term') ); ?>"><?php esc_attr_e( 'Install Now', 'instantio' ); ?></a></strong></p>
			</div>

		<?php }

	}
}
add_action( 'admin_notices', 'instantio_is_woocommerce' );

/**
 * Add Pro link in menu.
 */
if ( !is_plugin_active( 'wooinstant/wooinstant.php' ) ) {
	function add_pro_link_menu() {
		$prolink = 'https://wpinstant.io/';
		$menuname = '<span style="color:#ffba00;">Upgrade to Pro</span>';
		add_submenu_page( 'instantio_options', 'Upgrade to Pro', $menuname, 'manage_options', $prolink);
	}
	add_action('admin_menu', 'add_pro_link_menu', 9999);
}

/**
 * Add plugin action links.
 *
 */
function instantio_plugin_action_links( $links ) {

	$settings_link = array(
		'<a href="admin.php?page=instantio_options">' . esc_html__( 'Settings', 'instantio' ) . '</a>',
	);

	if ( !is_plugin_active( 'wooinstant/wooinstant.php' ) ) {
		$gopro_link = array(
			'<a href="https://wpinstant.io/" target="_blank" style="color:#cc0000;font-weight: bold;text-shadow: 0px 1px 1px hsl(0deg 0% 0% / 28%);">' . esc_html__( 'GO PRO', 'instantio' ) . '</a>',
		);
	} else {
		$gopro_link = array('');
	}
	return array_merge( $settings_link, $links, $gopro_link );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'instantio_plugin_action_links' );

/**
 * Admin review notice
 */
function ins_admin_rating_notice () { 
	$display_status = get_option( 'ins-dismiss' );
	if ($display_status) { ?>

		<div id='ins-notice' class="ins-notice notice notice-info">
			<p style="float: left;">If you like <strong>Instantio</strong> please leave a review</p>
			<p style="float: right;">
				<a href="//wordpress.org/plugins/instantio" target="_blank">Rate Us <span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span></a>
				<a class="maybe-dis">Maybe later</a>
				<a class="done-dis">Already Rated <span class="dashicons dashicons-smiley"></span></a>
			</p>
		</div>

		<script>
			jQuery(document).ready(function($) {
				$(document).on('click', '#ins-notice .maybe-dis, #ins-notice .done-dis', function( event ) {
					jQuery('#ins-notice').css('display', 'none')
					data = {
						action : 'disable_ins_notice',
					};

					$.post(ajaxurl, data, function (response) {
					});
				});
			});
		</script>

	<?php }
}
add_action( 'admin_notices', 'ins_admin_rating_notice' );

function disable_ins_notice() {
	update_option( 'ins-dismiss', false );
	wp_die();
}
add_action( 'wp_ajax_disable_ins_notice', 'disable_ins_notice' );

function set_ins_dismiss() {
    update_option( 'ins-dismiss', true );
}
register_activation_hook(  plugin_dir_path( __FILE__ ) . 'instantio.php', 'set_ins_dismiss' );
?>
