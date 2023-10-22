<?php
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
		function( $links, $file ) {

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
	add_action( 'wp_ajax_nopriv_ins_del_billing_fields', 'ins_del_billing_fields'  );  
	add_action( 'wp_ajax_ins_del_billing_fields', 'ins_del_billing_fields');
	add_action( 'wp_ajax_nopriv_ins_del_shipping_fields', 'ins_del_shipping_fields'  );  
	add_action( 'wp_ajax_ins_del_shipping_fields', 'ins_del_shipping_fields');

	function insopt( $option = '', $default = null ) {
		$options = get_option( 'wiopt' ); 
		return ( isset( $options[$option] ) ) ? $options[$option] : $default;
	}

	/**
	 * Check Pro Active or not
	 * @since 3.1.6
	 * @author M Hemel Hasan
	 * @return bool
	 */
	function is_tf_pro_active() {
		if ( is_plugin_active( 'wooinstant/wooinstant.php' ) && class_exists('WOOINS') ) {
			return true;
		}
		return false;
	}

	function ins_del_billing_fields() {
		$ins_billing_fields = get_option('wiopt');

		if (isset($ins_billing_fields['checkout_editors_fields'])) {
			// Remove the 'checkout_editors_fields' key from the 'wiopt' option
			unset($ins_billing_fields['checkout_editors_fields']);
		
			// Update the 'wiopt' option without the 'checkout_editors_fields' key
			update_option('wiopt', $ins_billing_fields);
		}

	}



	function ins_del_shipping_fields() {
		$ins_shipping_fields = get_option('wiopt');

		if (isset($ins_shipping_fields['checkout_shiping_editors_fields'])) {
			// Remove the 'checkout_shiping_editors_fields' key from the 'wiopt' option
			unset($ins_shipping_fields['checkout_shiping_editors_fields']);
		
			// Update the 'wiopt' option without the 'checkout_shiping_editors_fields' key
			update_option('wiopt', $ins_shipping_fields);
		}

	}


?>