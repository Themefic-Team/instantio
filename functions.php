<?php 

/*
 * Plugins Loaded
 * Including Option Framework
 * Including Options
 * Disable WooCommerce Notices
 */
if ( ! function_exists( 'instantio_plugin_loaded_action' ) ) {
	function instantio_plugin_loaded_action() {
		
		// Option Framework
		if ( file_exists( INS_CLASSIC_PATH .'/admin/framework/framework.php' ) ) {
			require_once( INS_CLASSIC_PATH .'/admin/framework/framework.php' );
		}
		
		// // Options
		// if ( file_exists( WP_PLUGIN_DIR .'/wooinstant/admin/config.php' )  && defined( 'INSTANTIO_PRO_CONFIG' ) && defined( 'INSTANTIO_PRO' ) ) {
		// 	require_once( WP_PLUGIN_DIR .'/wooinstant/admin/config.php' );
		// } elseif ( file_exists( INS_ADMIN_PATH .'/config.php' ) ) {
		// 	require_once( INS_ADMIN_PATH .'/config.php' );
		// }

		// Disable WooCommerce Notices
		if ( class_exists( 'woocommerce' ) ) {
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
			remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices', 10 );
			add_filter('woocommerce_cart_item_removed_notice_type', '__return_null');
		}
	}
	add_action( 'plugins_loaded', 'instantio_plugin_loaded_action' );


    function insopt( $option = '', $default = null ) {
        $options = get_option( 'wiopt' ); 
        return ( isset( $options[$option] ) ) ? $options[$option] : $default;
    }
}

?>