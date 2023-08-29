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
		$get_ins_data = insopt('checkout_editors_fields');

        // Check if the variable is serialized
        if (is_serialized($get_ins_data)) {
            // If it's already serialized, unserialize it
            $get_ins_data_for_editor_fl = unserialize($get_ins_data);
            
        } else {
            // If it's not serialized, serialize it
            $get_ins_data_for_editor_fl = $get_ins_data;
        }
        
        $ins_all_checkout_fields = !empty($get_ins_data_for_editor_fl) ? $get_ins_data_for_editor_fl : [];

        foreach( $ins_all_checkout_fields as $fieldskey => $ins_field){
            $field_origin   = $ins_field['checkout_form_field_origin'];

            // Check All Fields Origin And Set Data Accordingly 
            if($field_origin == 'billing_first_name'){
                $ins_field['checkout_form_field_name']		= 'Fast name';
				$ins_field['checkout_form_field_place']		= '';
				$ins_field['required']						= 'true';
				$ins_field['checkout_form_field_status']	= true;

                $ins_billing_field = $ins_field;

            } elseif ($field_origin == 'billing_last_name'){
                $ins_field['checkout_form_field_name']		= 'Last name';
				$ins_field['checkout_form_field_place']		= '';
				$ins_field['required']						= 'true';
				$ins_field['checkout_form_field_status']	= true;

                $ins_billing_field += $ins_field;

            } elseif ($field_origin == 'billing_company'){
                $ins_field['checkout_form_field_name']		= 'Company name';
				$ins_field['checkout_form_field_place']		= '';
				$ins_field['required']						= 'false';
				$ins_field['checkout_form_field_status']	= true;

                $ins_billing_field += $ins_field;

            } elseif ($field_origin == 'billing_email'){
                $ins_field['checkout_form_field_name']		= 'Email address';
				$ins_field['checkout_form_field_place']		= '';
				$ins_field['required']						= 'true';
				$ins_field['checkout_form_field_status']	= true;

                $ins_billing_field += $ins_field;
                
            } elseif ($field_origin == 'billing_country'){
                $ins_field['checkout_form_field_name']		= 'Country / Region';
				$ins_field['checkout_form_field_place']		= '';
				$ins_field['required']						= 'true';
				$ins_field['checkout_form_field_status']	= true;

                $ins_billing_field += $ins_field;

            } elseif ($field_origin == 'billing_address_1'){
                $ins_field['checkout_form_field_name']		= 'Street address';
				$ins_field['checkout_form_field_place']		= '';
				$ins_field['required']						= 'true';
				$ins_field['checkout_form_field_status']	= true;

                $ins_billing_field += $ins_field;

            } elseif ($field_origin == 'billing_address_2'){
                $ins_field['checkout_form_field_name']		= 'Apartment, suite, unit, etc.';
				$ins_field['checkout_form_field_place']		= '';
				$ins_field['required']						= 'false';
				$ins_field['checkout_form_field_status']	= true;

                $ins_billing_field += $ins_field;

            } elseif ($field_origin == 'billing_city'){
                $ins_field['checkout_form_field_name']		= 'Town / City';
				$ins_field['checkout_form_field_place']		= '';
				$ins_field['required']						= 'true';
				$ins_field['checkout_form_field_status']	= true;

                $ins_billing_field += $ins_field;

            } elseif ($field_origin == 'billing_state'){
                $ins_field['checkout_form_field_name']		= 'District';
				$ins_field['checkout_form_field_place']		= '';
				$ins_field['required']						= 'true';
				$ins_field['checkout_form_field_status']	= true;

                $ins_billing_field += $ins_field;

            } elseif ($field_origin == 'billing_postcode'){
                $ins_field['checkout_form_field_name']		= 'Postcode / ZIP';
				$ins_field['checkout_form_field_place']		= '';
				$ins_field['required']						= 'false';
				$ins_field['checkout_form_field_status']	= true;

                $ins_billing_field += $ins_field;

            } elseif ($field_origin == 'billing_phone'){
                $ins_field['checkout_form_field_name']		= 'Phone';
				$ins_field['checkout_form_field_place']		= '';
				$ins_field['required']						= 'true';
				$ins_field['checkout_form_field_status']	= true;

                $ins_billing_field += $ins_field;

            }

        }

        $ins_billing_fields['checkout_editors_fields'] = $ins_billing_field;

        update_option( 'wiopt', $ins_billing_fields );

	}

	function ins_del_shipping_fields() {
		$data_shipping = get_option( 'wiopt' );

		$my_plugin_shipping_fields = get_option( 'wocom_defualt_shipping');

		$data_shipping['checkout_shiping_editors_fields'] = $my_plugin_shipping_fields;

		update_option( 'wiopt', $data_shipping );

	}



 
?>