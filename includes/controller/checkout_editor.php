<?php
    /**
     * Get All Checkout Fields Data form Instantio.
     * @author M Hemel Hasan
     * @since 3.1.0
     * @return Var,
     */

    
    function ins_over_checkout_billing_fields($fields) {

        $get_ins_data_for_editor_fl = unserialize(insopt('checkout_editors_fields'));
        $ins_all_checkout_fields = !empty($get_ins_data_for_editor_fl) ? $get_ins_data_for_editor_fl : [];

        // unset($fields['billing']['billing_address_2']);

        foreach( $ins_all_checkout_fields as $fieldskey => $ins_field){
            $field_origin   = $ins_field['checkout_form_field_origin'];

            $field_status   = (isset($ins_field['checkout_form_field_status']) && $ins_field['checkout_form_field_status'] === '1') ? true : false;


            // All Fields
            // $fields['billing']['billing_address_2'];
            // Check All Fields Origin And Set Data Accordingly 
            if($field_origin == 'billing_first_name'){
                $fields['billing']['billing_first_name']['label']       = $ins_field['checkout_form_field_name'];
                $fields['billing']['billing_first_name']['placeholder'] = $ins_field['checkout_form_field_place'];

                if($field_status === false){
                    unset($fields['billing']['billing_first_name']);
                }

            } elseif ($field_origin == 'billing_last_name'){
                $fields['billing']['billing_last_name']['label']        = $ins_field['checkout_form_field_name'];
                $fields['billing']['billing_last_name']['placeholder']  = $ins_field['checkout_form_field_place'];

                if($field_status === false){
                    unset($fields['billing']['billing_last_name']);
                }

            } elseif ($field_origin == 'billing_company'){
                $fields['billing']['billing_company']['label']          = $ins_field['checkout_form_field_name'];
                $fields['billing']['billing_company']['placeholder']    = $ins_field['checkout_form_field_place'];

                if($field_status === false){
                    unset($fields['billing']['billing_company']);
                }

            } elseif ($field_origin == 'billing_email'){
                $fields['billing']['billing_email']['label']            = $ins_field['checkout_form_field_name'];
                $fields['billing']['billing_email']['placeholder']      = $ins_field['checkout_form_field_place'];

                if($field_status === false){
                    unset($fields['billing']['billing_email']);
                }
                

            } elseif ($field_origin == 'billing_country'){
                $fields['billing']['billing_country']['label']          = $ins_field['checkout_form_field_name'];
                $fields['billing']['billing_country']['placeholder']    = $ins_field['checkout_form_field_place'];

                if($field_status === false){
                    unset($fields['billing']['billing_country']);
                }

            } elseif ($field_origin == 'billing_address_1'){
                $fields['billing']['billing_address_1']['label']        = $ins_field['checkout_form_field_name'];
                $fields['billing']['billing_address_1']['placeholder']  = $ins_field['checkout_form_field_place'];

                if($field_status === false){
                    unset($fields['billing']['billing_address_1']);
                }

            } elseif($field_origin == 'billing_address_2'){
                $fields['billing']['billing_address_2']['placeholder']  = $ins_field['checkout_form_field_place'];

                if($field_status === false){
                    unset($fields['billing']['billing_address_2']);
                }

            } elseif ($field_origin == 'billing_city'){
                $fields['billing']['billing_city']['label']             = $ins_field['checkout_form_field_name'];
                $fields['billing']['billing_city']['placeholder']       = $ins_field['checkout_form_field_place'];

                if($field_status === false){
                    unset($fields['billing']['billing_city']);
                }

            } elseif ($field_origin == 'billing_state'){
                $fields['billing']['billing_state']['label']            = $ins_field['checkout_form_field_name'];
                $fields['billing']['billing_state']['placeholder']      = $ins_field['checkout_form_field_place'];

                if($field_status === false){
                    unset($fields['billing']['billing_state']);
                }

            } elseif ($field_origin == 'billing_postcode'){
                $fields['billing']['billing_postcode']['label']         = $ins_field['checkout_form_field_name'];
                $fields['billing']['billing_postcode']['placeholder']   = $ins_field['checkout_form_field_place'];

                if($field_status === false){
                    unset($fields['billing']['billing_postcode']);
                }

            } elseif ($field_origin == 'billing_phone'){
                $fields['billing']['billing_phone']['label']             = $ins_field['checkout_form_field_name'];
                $fields['billing']['billing_phone']['placeholder']       = $ins_field['checkout_form_field_place'];

                if($field_status === false){
                    unset($fields['billing']['billing_phone']);
                }

            }

        }

        // $fields['shipping']['shipping_first_name']['placeholder'] = 'First Name';
        // $fields['shipping']['shipping_last_name']['placeholder'] = 'Last Name';
        // $fields['shipping']['shipping_company']['placeholder'] = 'Company Name';
         
        return $fields;

    }
    add_filter('woocommerce_billing_fields', 'ins_billing_unrequire_fields');

    function ins_billing_unrequire_fields($fields) {

        $get_ins_data_for_editor_fl = unserialize(insopt('checkout_editors_fields'));
        $ins_all_checkout_fields = !empty($get_ins_data_for_editor_fl) ? $get_ins_data_for_editor_fl : [];

        foreach( $ins_all_checkout_fields as $fieldskey => $ins_field){
            $field_origin   = $ins_field['checkout_form_field_origin'];

            $required = (isset($ins_field['required']) && $ins_field['required'] === '1') ? true : false;
            
            // All Fields
            // $fields['billing']['billing_address_2'];
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

    
    
    
    function ins_default_checkout_fields($fields) {

        $fields['billing']['billing_first_name']['label'] = 'First Name'; 
        $fields['billing']['billing_first_name']['placeholder'] = ''; 

        $fields['billing']['billing_last_name']['label'] = 'Last Name';
        $fields['billing']['billing_last_name']['placeholder'] = '';

        $fields['billing']['billing_company']['label'] = 'Business Name';
        $fields['billing']['billing_company']['placeholder'] = '';

        $fields['billing']['billing_email']['label'] = 'Email Address';
        $fields['billing']['billing_email']['placeholder'] = '';

        $fields['billing']['billing_phone']['label'] = 'Phone ';
        $fields['billing']['billing_phone']['placeholder'] = 'Phone ';

        return $fields;
    }

    //add_filter('woocommerce_checkout_fields', 'ins_default_checkout_fields');

    


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
                <div class="csf-subtitle-text">' . __( "Delete review fields that don't match with the present fields.<br><b style='color: red;'>Be aware! You will lose your old data!</b>", "tourfic" ) . '</div>
            </div>
            <div class="csf-fieldset">
                <button type="button" data-delete-all="no" class="button button-large csf-warning-primary tf-del-old-review-fields tf-order-remove">' . __( "Delete Fields", "tourfic" ) . '</button>
            </div>
            <div class="clear"></div>
        ';
    }


?>