<?php

defined( 'ABSPATH' ) || exit;

// Legacy helper and promotion callback names are retained for extension and saved-hook compatibility.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

// Reset Data
add_filter(
	'plugin_row_meta',
	/**
	 * Add links below the description on the Plugins page.
	 *
	 * @param array $links
	 * @param string $file
	 * @retun array
	 */
	function ($links, $file) {

		if ( INS_BASE_LOCATION !== $file ) {
			return $links;
		}

		return array_merge(
			$links,
			array(
				sprintf(
					'<a target="_blank" href="%1$s">%2$s</a>',
					'https://themefic.com/docs/instantio/',
					__( 'Documentation', 'instantio' )
				),
				sprintf(
					'<a target="_blank" href="%1$s">%2$s</a>',
					'https://portal.themefic.com/support/',
					__( 'Get help', 'instantio' )
				),
				sprintf(
					'<a target="_blank" href="%1$s">%2$s</a>',
					'https://themefic.com/feature-request/',
					__( 'Request a feature', 'instantio' )
				),
				sprintf(
					'<a target="_blank" href="%1$s">%2$s</a>',
					'https://portal.themefic.com/support/',
					__( 'Submit a bug', 'instantio' )
				),
			)
		);
	},
	10,
	2
);

// Reset Data
add_action( 'wp_ajax_ins_del_billing_fields', 'ins_del_billing_fields' );
add_action( 'wp_ajax_ins_del_shipping_fields', 'ins_del_shipping_fields' );

function insopt( $option = '', $default = null ) {
	$options = get_option( 'wiopt' );
	return ( isset( $options[ $option ] ) ) ? $options[ $option ] : $default;
}

/**
 * Check Pro Active or not
 * @since 3.1.6
 * @author M Hemel Hasan
 * @return bool
 */
function is_ins_pro_active() {
	if ( is_plugin_active( 'wooinstant/wooinstant.php' ) && class_exists( 'WOOINS' ) ) {
		return true;
	}
	return false;
}

function ins_del_billing_fields() {
	// Verify nonce
    if ( ! check_ajax_referer( 'ins_ajax_nonce', '_wpnonce', false ) ) {
        wp_send_json_error( 'Invalid nonce.', 403 );
        exit;
    }

	// Verify user capabilities
    if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Unauthorized action.', 403 );
        exit;
    }

	$ins_billing_fields = get_option( 'wiopt' );

	if ( isset( $ins_billing_fields['checkout_editors_fields'] ) ) {
		// Remove the 'checkout_editors_fields' key from the 'wiopt' option
		unset( $ins_billing_fields['checkout_editors_fields'] );

		// Update the 'wiopt' option without the 'checkout_editors_fields' key
		update_option( 'wiopt', $ins_billing_fields );
	}

	wp_send_json_success( 'Shipping fields reset successfully.' );

    exit;
}



function ins_del_shipping_fields() {
	// Verify nonce
    if ( ! check_ajax_referer( 'ins_ajax_nonce', '_wpnonce', false ) ) {
        wp_send_json_error( 'Invalid nonce.', 403 );
        exit;
    }

	// Verify user capabilities
    if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Unauthorized action.', 403 );
        exit;
    }

	$ins_shipping_fields = get_option( 'wiopt' );

	if ( isset( $ins_shipping_fields['checkout_shiping_editors_fields'] ) ) {
		// Remove the 'checkout_shiping_editors_fields' key from the 'wiopt' option
		unset( $ins_shipping_fields['checkout_shiping_editors_fields'] );

		// Update the 'wiopt' option without the 'checkout_shiping_editors_fields' key
		update_option( 'wiopt', $ins_shipping_fields );
	}

	wp_send_json_success( 'Shipping fields reset successfully.' );

    exit;
}


function ins_utm_generator( $url, $utm_params = array() ) {
	$utm_params = array_merge( array(
		'utm_source'   => 'instantio',
		'utm_medium'   => 'plugin',
		'utm_campaign' => 'ins_plugin_installation',
	), $utm_params );

	$query_string = http_build_query( $utm_params );
	return esc_url( $url . ( strpos( $url, '?' ) === false ? '?' : '&' ) . $query_string );
}

/**
 * Remove data and scheduled events created by the retired remote promotion service.
 */
function ins_cleanup_remote_promotion_data() {
	if ( wp_next_scheduled( 'ins_promo__schudle' ) ) {
		wp_clear_scheduled_hook( 'ins_promo__schudle' );
	}

	delete_option( 'ins_promo__schudle_option' );
	delete_option( 'ins_promo__schudle_start_from' );
	delete_transient( 'ins_dynamic_pricing' );
}
add_action( 'admin_init', 'ins_cleanup_remote_promotion_data' );

// ins Featured Banner
if ( file_exists( INS_INC_PATH . '/controller/class-helper-banner.php' ) ) {
	require_once INS_INC_PATH . '/controller/class-helper-banner.php';
}

if ( file_exists( INS_INC_PATH . '/controller/dashboard-promo-notice.php' ) ) {
	require_once INS_INC_PATH . '/controller/dashboard-promo-notice.php';
}

?>
