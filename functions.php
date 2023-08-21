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


	function ins_del_billing_fields() {
		$data = get_option( 'wiopt' );
		$data['checkout_editors_fields'] = [];
		update_option( 'wiopt', $data);


		$billing_fields = WC()->checkout()->get_checkout_fields('billing');

		// Store the billing fields in a global variable
		$my_plugin_billing_fields = array();

		foreach( $billing_fields as $fieldkey => $insfields){

			$label       = !empty($insfields['label']) ? $insfields['label'] : '';
			$placeholder = !empty($insfields['placeholder']) ? $insfields['placeholder'] : '';
			$required    = !empty($insfields['required']) ? $insfields['required'] : 'false';
			$priority    = !empty($insfields['priority']) ? $insfields['priority'] : '';

			$my_plugin_billing_fields[] = array(
				'checkout_form_field_origin'  => $fieldkey,
				'checkout_form_field_name'    => $label,
				'checkout_form_field_place'   => $placeholder,
				'required'                    => $required,
				'checkout_form_field_status'  => true,
				'priority'                    => $priority,
			);

		}
	
		$data['checkout_editors_fields'] = $my_plugin_billing_fields;

		update_option( 'wiopt', $data );

	}

	function ins_del_shipping_fields() {
		$data_shipping = get_option( 'wiopt' );
		$data_shipping['checkout_shiping_editors_fields'] = [];
		update_option( 'wiopt', $data_shipping);

		$shipping_fields = WC()->checkout()->get_checkout_fields('shipping');

		// Store the billing fields in a global variable
		$my_plugin_shipping_fields = array();

		foreach( $shipping_fields as $fieldkey => $insfields){

			$label       = !empty($insfields['label']) ? $insfields['label'] : '';
			$placeholder = !empty($insfields['placeholder']) ? $insfields['placeholder'] : '';
			$required    = !empty($insfields['required']) ? $insfields['required'] : 'false';
			$priority    = !empty($insfields['priority']) ? $insfields['priority'] : '';

			$my_plugin_shipping_fields[] = array(
				'checkout_shipping_form_field_origin'  => $fieldkey,
				'checkout_shipping_form_field_name'    => $label,
				'checkout_shipping_form_field_place'   => $placeholder,
				'required_shipping'                    => $required,
				'checkout_shipping_form_field_status'  => true,
				'priority_shipping'                    => $priority,
			);

		}
	
		$data_shipping['checkout_shiping_editors_fields'] = $my_plugin_shipping_fields;

		update_option( 'wiopt', $data_shipping );

	}



 
?>