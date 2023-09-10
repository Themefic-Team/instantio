<?php

    // Filter Action
    add_filter('woocommerce_billing_fields', 'ins_billing_unrequire_fields');
    add_filter('woocommerce_shipping_fields', 'ins_shipping_unrequire_fields');



    /**
     * Get Billing Checkout Fields Data form Instantio.
     * @author M Hemel Hasan
     * @since 3.1.0
     * @return Var,
     */
    function ins_over_checkout_billing_fields($fields) {

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

            $field_status   = (isset($ins_field['checkout_form_field_status']) && $ins_field['checkout_form_field_status'] === '1') ? true : false;

            // var_dump($field_origin, $fieldskey);


            // All Fields
            // Check All Fields Origin And Set Data Accordingly 
            if($field_origin == 'billing_first_name'){
                $fields['billing']['billing_first_name']['label']       = $ins_field['checkout_form_field_name'];
                $fields['billing']['billing_first_name']['placeholder'] = $ins_field['checkout_form_field_place'];

                $fields['billing']['billing_first_name']['priority']    = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['billing']['billing_first_name']);
                }

            } elseif ($field_origin == 'billing_last_name'){
                $fields['billing']['billing_last_name']['label']        = $ins_field['checkout_form_field_name'];
                $fields['billing']['billing_last_name']['placeholder']  = $ins_field['checkout_form_field_place'];

                $fields['billing']['billing_last_name']['priority']     = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['billing']['billing_last_name']);
                }

            } elseif ($field_origin == 'billing_company'){
                $fields['billing']['billing_company']['label']          = $ins_field['checkout_form_field_name'];
                $fields['billing']['billing_company']['placeholder']    = $ins_field['checkout_form_field_place'];

                $fields['billing']['billing_company']['priority']       = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['billing']['billing_company']);
                }

            } elseif ($field_origin == 'billing_email'){
                $fields['billing']['billing_email']['label']            = $ins_field['checkout_form_field_name'];
                $fields['billing']['billing_email']['placeholder']      = $ins_field['checkout_form_field_place'];

                $fields['billing']['billing_email']['priority']         = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['billing']['billing_email']);
                }
                

            } elseif ($field_origin == 'billing_country'){
                // $fields['billing']['billing_country']['label']          = $ins_field['checkout_form_field_name'];
                // $fields['billing']['billing_country']['placeholder']    = $ins_field['checkout_form_field_place'];
                // $fields['billing']['billing_country']['priority']       = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['billing']['billing_country']);
                }

            } elseif ($field_origin == 'billing_address_1'){
                // $fields['billing']['billing_address_1']['label']        = $ins_field['checkout_form_field_name'];
                // $fields['billing']['billing_address_1']['placeholder']  = $ins_field['checkout_form_field_place'];
                // $fields['billing']['billing_address_1']['priority']     = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['billing']['billing_address_1']);
                }

            } elseif ($field_origin == 'billing_address_2'){
                // $fields['billing']['billing_address_2']['placeholder']  = $ins_field['checkout_form_field_place'];
                // $fields['billing']['billing_address_2']['priority']     = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['billing']['billing_address_2']);
                }

            } elseif ($field_origin == 'billing_city'){
                // $fields['billing']['billing_city']['label']             = $ins_field['checkout_form_field_name'];
                // $fields['billing']['billing_city']['placeholder']       = $ins_field['checkout_form_field_place'];
                // $fields['billing']['billing_city']['priority']          = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['billing']['billing_city']);
                }

            } elseif ($field_origin == 'billing_state'){
                $fields['billing']['billing_state']['label']            = $ins_field['checkout_form_field_name'];
                $fields['billing']['billing_state']['placeholder']      = $ins_field['checkout_form_field_place'];

                $fields['billing']['billing_state']['priority']         = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['billing']['billing_state']);
                }

            } elseif ($field_origin == 'billing_postcode'){
                // $fields['billing']['billing_postcode']['label']         = $ins_field['checkout_form_field_name'];
                // $fields['billing']['billing_postcode']['placeholder']   = $ins_field['checkout_form_field_place'];

                // $fields['billing']['billing_postcode']['priority']      = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['billing']['billing_postcode']);
                }

            } elseif ($field_origin == 'billing_phone'){
                $fields['billing']['billing_phone']['label']             = $ins_field['checkout_form_field_name'];
                $fields['billing']['billing_phone']['placeholder']       = $ins_field['checkout_form_field_place'];

                $fields['billing']['billing_phone']['priority']          = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['billing']['billing_phone']);
                }

            }

        }

        return $fields;

    }

    function ins_over_checkout_billing_address($address_fields){
        $get_ins_add_data = insopt('checkout_editors_fields');

        // Check if the variable is serialized
        if (is_serialized($get_ins_add_data)) {
            // If it's already serialized, unserialize it
            $get_ins_data_add_editor_fl = unserialize($get_ins_add_data);
            
        } else {
            // If it's not serialized, serialize it
            $get_ins_data_add_editor_fl = $get_ins_add_data;
        }
        
        $ins_address_checkout_fields = !empty($get_ins_data_add_editor_fl) ? $get_ins_data_add_editor_fl : [];

        
        foreach( $ins_address_checkout_fields as $fieldskey => $ins_field){
            $field_origin   = $ins_field['checkout_form_field_origin'];

            // Check Address Fields Origin And Set Data Accordingly 
            if ($field_origin == 'billing_country'){
                $address_fields['country']['label']          = $ins_field['checkout_form_field_name'];
                $address_fields['country']['placeholder']    = $ins_field['checkout_form_field_place'];
                $address_fields['country']['priority']       = $fieldskey . '0';

            } elseif ($field_origin == 'billing_address_1'){
                $address_fields['address_1']['label']        = $ins_field['checkout_form_field_name'];
                $address_fields['address_1']['placeholder']  = $ins_field['checkout_form_field_place'];
                $address_fields['address_1']['priority']     = $fieldskey . '0';

            } elseif ($field_origin == 'billing_address_2'){
                $address_fields['address_2']['placeholder']  = $ins_field['checkout_form_field_place'];
                $address_fields['address_2']['priority']     = $fieldskey . '0';

            } elseif ($field_origin == 'billing_city'){
                $address_fields['city']['label']             = $ins_field['checkout_form_field_name'];
                $address_fields['city']['placeholder']       = $ins_field['checkout_form_field_place'];
                $address_fields['city']['priority']          = $fieldskey . '0';

            } elseif ($field_origin == 'billing_postcode'){
                $address_fields['postcode']['label']         = $ins_field['checkout_form_field_name'];
                $address_fields['postcode']['placeholder']   = $ins_field['checkout_form_field_place'];
                $address_fields['postcode']['priority']      = $fieldskey . '0';
            }

        }
        
        return $address_fields;
    }
    

    function ins_billing_unrequire_fields($fields) {

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

            $required = (isset($ins_field['required']) && $ins_field['required'] === '1') ? true : false;
            
            // All Fields
            // Check All Fields Origin And Set Data Accordingly 
            if($field_origin == 'billing_first_name'){
                $fields['billing_first_name']['required']   = $required;

            } elseif ($field_origin == 'billing_last_name'){
               
                $fields['billing_last_name']['required']    = $required;

            } elseif ($field_origin == 'billing_company'){
                
                $fields['billing_company']['required']      = $required;

            } elseif ($field_origin == 'billing_email'){
                
                $fields['billing_email']['required']        = $required;

            } elseif ($field_origin == 'billing_country'){
            
                $fields['billing_country']['required']      = $required;

            } elseif ($field_origin == 'billing_address_1'){
                
                $fields['billing_address_1']['required']    = $required;

            } elseif ($field_origin == 'billing_city'){
                
                $fields['billing_city']['required']         = $required;

            } elseif ($field_origin == 'billing_state'){
                
                $fields['billing_state']['required']        = $required;

            } elseif ($field_origin == 'billing_postcode'){
                
                $fields['billing_postcode']['required']     = $required;

            } elseif ($field_origin == 'billing_phone'){
                
                $fields['billing_phone']['required']        = $required;

            }

        }

        return $fields;

    }

    
    
    
    /**
     * Get Shipping Checkout Fields Data form Instantio.
     * @author M Hemel Hasan
     * @since 3.1.0
     * @return Var,
     */
    function ins_over_checkout_shipping_fields($fields) {

        $get_ins_data = insopt('checkout_shiping_editors_fields');

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
            $field_origin   = $ins_field['checkout_shipping_form_field_origin'];

            $field_status   = (isset($ins_field['checkout_shipping_form_field_status']) && $ins_field['checkout_shipping_form_field_status'] === '1') ? true : false;


            // All Fields
            // Check All Fields Origin And Set Data Accordingly 
            if($field_origin == 'shipping_first_name'){
                $fields['shipping']['shipping_first_name']['label']         = $ins_field['checkout_shipping_form_field_name'];
                $fields['shipping']['shipping_first_name']['placeholder']   = $ins_field['checkout_shipping_form_field_place'];

                $fields['shipping']['shipping_first_name']['priority']      = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['shipping']['shipping_first_name']);
                }

            } elseif ($field_origin == 'shipping_last_name'){
                $fields['shipping']['shipping_last_name']['label']          = $ins_field['checkout_shipping_form_field_name'];
                $fields['shipping']['shipping_last_name']['placeholder']    = $ins_field['checkout_shipping_form_field_place'];

                $fields['shipping']['shipping_last_name']['priority']       = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['shipping']['shipping_last_name']);
                }

            } elseif ($field_origin == 'shipping_company'){
                $fields['shipping']['shipping_company']['label']            = $ins_field['checkout_shipping_form_field_name'];
                $fields['shipping']['shipping_company']['placeholder']      = $ins_field['checkout_shipping_form_field_place'];

                $fields['shipping']['shipping_company']['priority']         = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['shipping']['shipping_company']);
                }

            } elseif ($field_origin == 'shipping_country'){
                // $fields['shipping']['shipping_country']['label']            = $ins_field['checkout_shipping_form_field_name'];
                // $fields['shipping']['shipping_country']['placeholder']      = $ins_field['checkout_shipping_form_field_place'];

                // $fields['shipping']['shipping_country']['priority']         = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['shipping']['shipping_country']);
                }

            } elseif ($field_origin == 'shipping_address_1'){
                // $fields['shipping']['shipping_address_1']['label']          = $ins_field['checkout_shipping_form_field_name'];
                // $fields['shipping']['shipping_address_1']['placeholder']    = $ins_field['checkout_shipping_form_field_place'];

                // $fields['shipping']['shipping_address_1']['priority']       = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['shipping']['shipping_address_1']);
                }

            } elseif ($field_origin == 'shipping_address_2'){
                // $fields['shipping']['shipping_address_2']['placeholder']    = $ins_field['checkout_shipping_form_field_place'];

                // $fields['shipping']['shipping_address_2']['priority']       = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['shipping']['shipping_address_2']);
                }

            } elseif ($field_origin == 'shipping_city'){
                // $fields['shipping']['shipping_city']['label']               = $ins_field['checkout_shipping_form_field_name'];
                // $fields['shipping']['shipping_city']['placeholder']         = $ins_field['checkout_shipping_form_field_place'];

                $fields['shipping']['shipping_city']['priority']            = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['shipping']['shipping_city']);
                }

            } elseif ($field_origin == 'shipping_state'){
                $fields['shipping']['shipping_state']['label']              = $ins_field['checkout_shipping_form_field_name'];
                $fields['shipping']['shipping_state']['placeholder']        = $ins_field['checkout_shipping_form_field_place'];

                $fields['shipping']['shipping_state']['priority']           = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['shipping']['shipping_state']);
                }

            } elseif ($field_origin == 'shipping_postcode'){
                // $fields['shipping']['shipping_postcode']['label']           = $ins_field['checkout_shipping_form_field_name'];
                // $fields['shipping']['shipping_postcode']['placeholder']     = $ins_field['checkout_shipping_form_field_place'];

                // $fields['shipping']['shipping_postcode']['priority']        = $fieldskey . '0';

                if($field_status === false){
                    unset($fields['shipping']['shipping_postcode']);
                }

            }

        }

        return $fields;

    }

    function ins_over_checkout_shiping_address($address_fields){
        $get_ins_add_data = insopt('checkout_shiping_editors_fields');

        // Check if the variable is serialized
        if (is_serialized($get_ins_add_data)) {
            // If it's already serialized, unserialize it
            $get_ins_data_add_editor_fl = unserialize($get_ins_add_data);
            
        } else {
            // If it's not serialized, serialize it
            $get_ins_data_add_editor_fl = $get_ins_add_data;
        }
        
        $ins_address_checkout_fields = !empty($get_ins_data_add_editor_fl) ? $get_ins_data_add_editor_fl : [];

        
        foreach( $ins_address_checkout_fields as $fieldskey => $ins_field){
            $field_origin   = $ins_field['checkout_shipping_form_field_origin'];

            // Check Address Fields Origin And Set Data Accordingly 
            if ($field_origin == 'shipping_country'){
                $address_fields['country']['label']          = $ins_field['checkout_shipping_form_field_name'];
                $address_fields['country']['placeholder']    = $ins_field['checkout_shipping_form_field_place'];
                $address_fields['country']['priority']       = $fieldskey . '0';

            } elseif ($field_origin == 'shipping_address_1'){
                $address_fields['address_1']['label']        = $ins_field['checkout_shipping_form_field_name'];
                $address_fields['address_1']['placeholder']  = $ins_field['checkout_shipping_form_field_place'];
                $address_fields['address_1']['priority']     = $fieldskey . '0';

            } elseif ($field_origin == 'shipping_address_2'){
                $address_fields['address_2']['placeholder']  = $ins_field['checkout_shipping_form_field_place'];
                $address_fields['address_2']['priority']     = $fieldskey . '0';

            } elseif ($field_origin == 'shipping_city'){
                $address_fields['city']['label']             = $ins_field['checkout_shipping_form_field_name'];
                $address_fields['city']['placeholder']       = $ins_field['checkout_shipping_form_field_place'];
                $address_fields['city']['priority']          = $fieldskey . '0';

            } elseif ($field_origin == 'shipping_postcode'){
                $address_fields['postcode']['label']         = $ins_field['checkout_shipping_form_field_name'];
                $address_fields['postcode']['placeholder']   = $ins_field['checkout_shipping_form_field_place'];
                $address_fields['postcode']['priority']      = $fieldskey . '0';
            }

        }
        
        return $address_fields;
    }

    function ins_shipping_unrequire_fields($fields) {

        $get_ins_data = insopt('checkout_shiping_editors_fields');

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
            $field_origin   = $ins_field['checkout_shipping_form_field_origin'];

            $required = (isset($ins_field['required_shipping']) && $ins_field['required_shipping'] === '1') ? true : false;
            
            // All Fields
            // Check All Fields Origin And Set Data Accordingly 
            if($field_origin == 'shipping_first_name'){
                $fields['shipping_first_name']['required']   = $required;

            } elseif ($field_origin == 'shipping_last_name'){
               
                $fields['shipping_last_name']['required']    = $required;

            } elseif ($field_origin == 'shipping_company'){
                
                $fields['shipping_company']['required']      = $required;

            } elseif ($field_origin == 'shipping_country'){
                
                $fields['shipping_country']['required']        = $required;

            } elseif ($field_origin == 'shipping_address_1'){
            
                $fields['shipping_address_1']['required']      = $required;

            } elseif ($field_origin == 'shipping_address_2'){
                
                $fields['shipping_address_2']['required']    = $required;

            } elseif ($field_origin == 'shipping_city'){
                
                $fields['shipping_city']['required']         = $required;

            } elseif ($field_origin == 'shipping_state'){
                
                $fields['shipping_state']['required']        = $required;

            } elseif ($field_origin == 'shipping_postcode'){
                
                $fields['shipping_postcode']['required']     = $required;

            }
        }

        return $fields;

    }


    /**
     * Reset blliling Fields Data form Instantio.
     * @author M Hemel Hasan
     * @since 3.1.0
     * @return obj,
     */
    function ins_reset_blliling_fields_button() {
        echo '
            <div class="csf-title">
                <h4>' . __( "Reset Billing Fields", "instantio" ) . '</h4>
                <div class="csf-subtitle-text">' . __( "All data entered in the edit field will be erased and reset to the default position.<br><b style='color: red;'>Be aware! You will lose your old data!</b>", "instantio" ) . '</div>
            </div>
            <div class="csf-fieldset">
                <button type="button" data-delete-all="no" class="button button-large ins-del-billing-fields ins-order-remove">' . __( "Reset Fields", "instantio" ) . '</button>
            </div>
            <div class="clear"></div>
        ';
    }

    /**
     * Reset shipping Fields Data form Instantio.
     * @author M Hemel Hasan
     * @since 3.1.0
     * @return obj,
     */
    function ins_reset_shipping_fields_button() {
        echo '
            <div class="csf-title">
                <h4>' . __( "Reset Shipping Fields", "instantio" ) . '</h4>
                <div class="csf-subtitle-text">' . __( "All data entered in the edit field will be erased and reset to the default position.<br><b style='color: red;'>Be aware! You will lose your old data!</b>", "instantio" ) . '</div>
            </div>
            <div class="csf-fieldset">
                <button type="button" data-delete-all="no" class="button button-large ins-del-shipping-fields ins-order-remove">' . __( "Reset Fields", "instantio" ) . '</button>
            </div>
            <div class="clear"></div>
        ';
    }

    


?>