<?php
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
define( 'INS_URL', INS_CLASSIC_URL.'/' );
define( 'INS_INC_URL', INS_URL.'inc' );
define( 'INS_LAYOUTS_URL', INS_URL.'inc/layouts' );
define( 'INS_ASSETS_URL', INS_URL.'assets' );
// Paths
define( 'INS_PATH', INS_CLASSIC_PATH.'/' );
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
if(is_admin()){ 
	require_once( INS_ADMIN_PATH.'/admin-notice.php' ); 
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
	define( 'INSTANTIO_VERSION', '2.5.19' );

}


/**
 * Load plugin textdomain.
 */
function ins_load_textdomain() {
	load_plugin_textdomain( 'instantio', false, 'instantio/lang/' );
}
add_action( 'init', 'ins_load_textdomain' );



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
	
	// Change notice text
	$notice = sprintf( $client->__trans( 'Want to help make <strong>%1$s</strong> even more awesome? Allow %1$s to collect non-sensitive diagnostic data and usage information. I agree to get Important Product Updates & Discount related information on my email from  %1$s (I can unsubscribe anytime).' ), $client->name );
	
	$client->insights()->notice($notice);

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



register_activation_hook( plugin_dir_path( __FILE__ ) . 'instantio.php',  'ins_activate');
register_deactivation_hook( plugin_dir_path( __FILE__ ) . 'instantio.php', 'ins_deactivate');