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
 * Version: 2.5.13
 * Tested up to: 6.1.1
 * Requires PHP: 7.2
 * WC tested up to: 7.1.0
 */
 
// don't load directly
defined( 'ABSPATH' ) || exit;

/**
 * Including Plugin file
 * 
 * @since 1.0
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );


/**
 * Instantio All the Defines
 *
 * @since 1.0
 */
// URLs
define( 'INS_URL', plugin_dir_url( __FILE__ ) );
define( 'INS_INC_URL', INS_URL.'inc' );
define( 'INS_LAYOUTS_URL', INS_URL.'inc/layouts' );
define( 'INS_ASSETS_URL', INS_URL.'assets' );
define( 'INS_ADMIN_URL', INS_URL.'admin' );
// Paths
define( 'INS_PATH', plugin_dir_path( __FILE__ ) );
define( 'INS_ADMIN_PATH', INS_PATH.'admin' );
define( 'INS_INC_PATH', INS_PATH.'inc' );
define( 'INS_LAYOUTS_PATH', INS_INC_PATH.'/layouts' );


/**
 * Enqueue Admin scripts
 * 
 * @since 1.0
 */
if ( !function_exists('ins_enqueue_admin_scripts') ) {
    function ins_enqueue_admin_scripts(){

        // Custom
		wp_enqueue_style('ins-admin', INS_ADMIN_URL . '/css/admin.css','', '' );
		wp_enqueue_script( 'ins-admin', INS_ADMIN_URL . '/js/admin.js', array('jquery'), '', true );  
        wp_localize_script( 'ins-admin', 'ins_params',
            array(
                'ins_nonce' => wp_create_nonce( 'updates' ),
                'ajax_url' => admin_url( 'admin-ajax.php' ),
            )
        );    
    }
    add_action( 'admin_enqueue_scripts', 'ins_enqueue_admin_scripts' );
}

/**
 * Check if WooCommerce is active, and if it isn't, disable the plugin.
 *
 * @since 1.0
 */
if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
	add_action( 'admin_notices', 'ins_is_woo' );

	/**
     * Ajax install & activate WooCommerce
     *
     * @since 1.0
     * @link https://developer.wordpress.org/reference/functions/wp_ajax_install_plugin/
     */
    add_action("wp_ajax_ins_ajax_install_plugin" , "wp_ajax_install_plugin");

	return;
}

// Define INSTANTIO_VERSION.
if ( ! defined( 'INSTANTIO_VERSION' ) ) {
	define( 'INSTANTIO_VERSION', '2.5.13' );
}

/**
 * Load plugin textdomain.
 */
function ins_load_textdomain() {
	load_plugin_textdomain( 'instantio', false, 'instantio/lang/' );
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
	add_action( 'plugins_loaded', 'instantio_plugin_loaded_action' );
}


/**
 * Initialize the tracker
 *
 * @return void
 */

function appsero_init_tracker_instantio() {

    if ( ! class_exists( 'Appsero\Client' ) ) {
	      require_once (INS_INC_PATH . '/app/src/Client.php');
    }

    $client = new Appsero\Client( '29e55a76-0819-490f-b692-8368956cbf12', 'instantio', __FILE__ );

    // Active insights
    $client->insights()->init();

}

appsero_init_tracker_instantio();


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
if (!class_exists('INS_Mobile_Detect')) {
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
 * Called when WooCommerce is inactive to display an inactive notice.
 *
 * @since 1.0
 */
function ins_is_woo() {
    if ( current_user_can( 'activate_plugins' ) ) {
        if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) && !file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) ) {
        ?>

            <div id="message" class="error">
                <p><?php printf( __( 'Instantio requires %1$s WooCommerce %2$s to be activated.', 'instantio' ), '<strong><a href="https://wordpress.org/plugins/woocommerce/" target="_blank">', '</a></strong>' ); ?></p>
                <p><a class="install-now button tf-install" data-plugin-slug="woocommerce"><?php esc_attr_e( 'Install Now', 'instantio' ); ?></a></p>
            </div>

        <?php 
        } elseif ( !is_plugin_active( 'woocommerce/woocommerce.php' ) && file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) ) {
        ?>

            <div id="message" class="error">
                <p><?php printf( __( 'Instantio requires %1$s WooCommerce %2$s to be activated.', 'instantio' ), '<strong><a href="https://wordpress.org/plugins/woocommerce/" target="_blank">', '</a></strong>' ); ?></p>
                <p><a href="<?php echo get_admin_url(); ?>plugins.php?_wpnonce=<?php echo wp_create_nonce( 'activate-plugin_woocommerce/woocommerce.php' ); ?>&action=activate&plugin=woocommerce/woocommerce.php" class="button activate-now button-primary"><?php esc_attr_e( 'Activate', 'instantio' ); ?></a></p>
            </div>

        <?php 
        } elseif ( version_compare( get_option( 'woocommerce_db_version' ), '2.5', '<' ) ) {
        ?>

            <div id="message" class="error">
                <p><?php printf( __( '%sInstantio is inactive.%s This plugin requires WooCommerce 2.5 or newer. Please %supdate WooCommerce to version 2.5 or newer%s', 'instantio' ), '<strong>', '</strong>', '<a href="' . admin_url( 'plugins.php' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
            </div>

        <?php 
        }
    }
}

/**
 * Add Pro link in menu.
 */
if ( !is_plugin_active( 'wooinstant/wooinstant.php' ) ) {
	function add_pro_link_menu() {
		$prolink = 'https://wpinstant.io/go/upgrade';
		$menuname = '<span style="color:#ffba00;">' .__("Upgrade to Pro", "instantio"). '</span>';
		add_submenu_page( 'instantio_options', __('Upgrade to Pro', 'instantio'), $menuname, 'manage_options', $prolink);
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
			'<a href="https://wpinstant.io/go/upgrade" target="_blank" style="color:#cc0000;font-weight: bold;text-shadow: 0px 1px 1px hsl(0deg 0% 0% / 28%);">' . esc_html__( 'GO PRO', 'instantio' ) . '</a>',
		);
	} else {
		$gopro_link = array('');
	}
	return array_merge( $settings_link, $links, $gopro_link );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'instantio_plugin_action_links' );



function ins_activate() {
	$installed = get_option( 'instantio_active_time' );

	if ( ! $installed ) {
		update_option( 'instantio_active_time', time() );
	}
	
}

function ins_deactivate() {
	$installed = get_option( 'instantio_active_time' );
	if($installed){
		delete_option('instantio_active_time' );
	}
}



/**
 * Admin review notice
 */
function ins_admin_rating_notice () { 
	$display_status = get_option( 'ins-dismiss' );
	if ($display_status) { ?>

		<div id='ins-notice' class="ins-notice notice notice-info">
			<p style="float: left;">
				<?php 
					echo sprintf(__('If you like %1$sInstantio%2$s please leave a review ', 'instantio'), '<strong>', '</strong>');
					
					$ins_activetime = get_option('instantio_active_time' );
 					echo date("F d, Y h:i:s", $ins_activetime); 
				?>
			</p>
			<p style="float: right;">
				<a href="//wordpress.org/plugins/instantio" target="_blank"><?php _e('Rate Us', 'instantio'); ?> <span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span></a>
				<a class="maybe-dis"><?php _e('Maybe later', 'instantio'); ?></a>
				<a class="done-dis"><?php _e('Already Rated', 'instantio'); ?> <span class="dashicons dashicons-smiley"></span></a>
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
$ins_activetime = get_option('instantio_active_time' );
$ins_activetime= 00;
if( time() < $ins_activetime){
	add_action( 'admin_notices', 'ins_admin_rating_notice' );
}


function disable_ins_notice() {
	update_option( 'ins-dismiss', false );
	wp_die();
}
add_action( 'wp_ajax_disable_ins_notice', 'disable_ins_notice' );

function set_ins_dismiss() {
    update_option( 'ins-dismiss', true );
}
register_activation_hook(  plugin_dir_path( __FILE__ ) . 'instantio.php', 'set_ins_dismiss' );
register_activation_hook(  plugin_dir_path( __FILE__ ) . 'instantio.php',  'ins_activate');
register_deactivation_hook( plugin_dir_path( __FILE__ ) . 'instantio.php', 'ins_deactivate' );

?>