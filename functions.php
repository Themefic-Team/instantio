<?php

defined( 'ABSPATH' ) || exit;

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

		if ( INSTANTIO_BASE_LOCATION !== $file ) {
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
add_action( 'wp_ajax_instantio_delete_billing_fields', 'instantio_delete_billing_fields' );
add_action( 'wp_ajax_instantio_delete_shipping_fields', 'instantio_delete_shipping_fields' );

function instantio_get_option( $option = '', $default = null ) {
	$options = get_option( 'wiopt' );
	return ( isset( $options[ $option ] ) ) ? $options[ $option ] : $default;
}

/**
 * Check Pro Active or not
 * @since 3.1.6
 * @author M Hemel Hasan
 * @return bool
 */
function instantio_is_pro_active() {
	if ( is_plugin_active( 'wooinstant/wooinstant.php' ) && class_exists( 'WOOINS' ) ) {
		return true;
	}
	return false;
}

function instantio_delete_billing_fields() {
	// Verify nonce
    if ( ! check_ajax_referer( 'instantio_ajax_nonce', '_wpnonce', false ) ) {
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



function instantio_delete_shipping_fields() {
	// Verify nonce
    if ( ! check_ajax_referer( 'instantio_ajax_nonce', '_wpnonce', false ) ) {
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


function instantio_utm_generator( $url, $utm_params = array() ) {
	$utm_params = array_merge( array(
		'utm_source'   => 'instantio',
		'utm_medium'   => 'plugin',
		'utm_campaign' => 'instantio_plugin_installation',
	), $utm_params );

	$query_string = http_build_query( $utm_params );
	return esc_url( $url . ( strpos( $url, '?' ) === false ? '?' : '&' ) . $query_string );
}

?>
