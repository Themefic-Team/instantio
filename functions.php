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

        // foreach( $ins_all_checkout_fields as $fieldskey => $ins_field){
        //     $field_origin   = $ins_field['checkout_form_field_origin'];

        //     // Check All Fields Origin And Set Data Accordingly 
        //     if($field_origin == 'billing_first_name'){
        //         $ins_field['checkout_form_field_name']		= 'Fast name';
		// 		$ins_field['checkout_form_field_place']		= '';
		// 		$ins_field['required']						= 'true';
		// 		$ins_field['checkout_form_field_status']	= true;

        //     } elseif ($field_origin == 'billing_last_name'){
        //         $fields['billing']['billing_last_name']['label']        = $ins_field['checkout_form_field_name'];
        //         $fields['billing']['billing_last_name']['placeholder']  = $ins_field['checkout_form_field_place'];

        //         if($field_status === false){
        //             unset($fields['billing']['billing_last_name']);
        //         }

        //     } elseif ($field_origin == 'billing_company'){
        //         $fields['billing']['billing_company']['label']          = $ins_field['checkout_form_field_name'];
        //         $fields['billing']['billing_company']['placeholder']    = $ins_field['checkout_form_field_place'];

        //         if($field_status === false){
        //             unset($fields['billing']['billing_company']);
        //         }

        //     } elseif ($field_origin == 'billing_email'){
        //         $fields['billing']['billing_email']['label']            = $ins_field['checkout_form_field_name'];
        //         $fields['billing']['billing_email']['placeholder']      = $ins_field['checkout_form_field_place'];

        //         if($field_status === false){
        //             unset($fields['billing']['billing_email']);
        //         }
                

        //     } elseif ($field_origin == 'billing_country'){
        //         $fields['billing']['billing_country']['label']          = $ins_field['checkout_form_field_name'];
        //         $fields['billing']['billing_country']['placeholder']    = $ins_field['checkout_form_field_place'];

        //         if($field_status === false){
        //             unset($fields['billing']['billing_country']);
        //         }

        //     } elseif ($field_origin == 'billing_address_1'){
        //         $fields['billing']['billing_address_1']['label']        = $ins_field['checkout_form_field_name'];
        //         $fields['billing']['billing_address_1']['placeholder']  = $ins_field['checkout_form_field_place'];

        //         if($field_status === false){
        //             unset($fields['billing']['billing_address_1']);
        //         }

        //     } elseif ($field_origin == 'billing_address_2'){
        //         $fields['billing']['billing_address_2']['placeholder']  = $ins_field['checkout_form_field_place'];

        //         if($field_status === false){
        //             unset($fields['billing']['billing_address_2']);
        //         }

        //     } elseif ($field_origin == 'billing_city'){
        //         $fields['billing']['billing_city']['label']             = $ins_field['checkout_form_field_name'];
        //         $fields['billing']['billing_city']['placeholder']       = $ins_field['checkout_form_field_place'];

        //         if($field_status === false){
        //             unset($fields['billing']['billing_city']);
        //         }

        //     } elseif ($field_origin == 'billing_state'){
        //         $fields['billing']['billing_state']['label']            = $ins_field['checkout_form_field_name'];
        //         $fields['billing']['billing_state']['placeholder']      = $ins_field['checkout_form_field_place'];

        //         if($field_status === false){
        //             unset($fields['billing']['billing_state']);
        //         }

        //     } elseif ($field_origin == 'billing_postcode'){
        //         $fields['billing']['billing_postcode']['label']         = $ins_field['checkout_form_field_name'];
        //         $fields['billing']['billing_postcode']['placeholder']   = $ins_field['checkout_form_field_place'];

        //         if($field_status === false){
        //             unset($fields['billing']['billing_postcode']);
        //         }

        //     } elseif ($field_origin == 'billing_phone'){
        //         $fields['billing']['billing_phone']['label']             = $ins_field['checkout_form_field_name'];
        //         $fields['billing']['billing_phone']['placeholder']       = $ins_field['checkout_form_field_place'];

        //         if($field_status === false){
        //             unset($fields['billing']['billing_phone']);
        //         }

        //     }

        // }

	}

	function ins_del_shipping_fields() {
		$data_shipping = get_option( 'wiopt' );

		$my_plugin_shipping_fields = get_option( 'wocom_defualt_shipping');

		$data_shipping['checkout_shiping_editors_fields'] = $my_plugin_shipping_fields;

		update_option( 'wiopt', $data_shipping );

	}



 
?>