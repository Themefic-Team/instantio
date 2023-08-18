<?php
    /**
     * init Instantio Checkout Editor.
     * @author M Hemel Hasan
     * @since 3.1.0
     */
    ob_start();

    

    if ( is_admin() ) {
        // Make sure WooCommerce is loaded
        if ( function_exists('WC') && is_object(WC()) ) {
            $billing_fields = WC()->checkout()->get_checkout_fields('billing');

            // Store the billing fields in a global variable
            $my_plugin_billing_fields = array();

            foreach( $billing_fields as $fieldkey => $insfields){

                $label       = !empty($insfields['label']) ? $insfields['label'] : '';
                $placeholder = !empty($insfields['placeholder']) ? $insfields['placeholder'] : '';
                $required    = !empty($insfields['required']) ? $insfields['required'] : '';
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

            // var_dump($billing_fields);
            // var_dump($my_plugin_billing_fields);
        }
    }

    /**
     * Get All Checkout Fields Data form Instantio.
     * @author M Hemel Hasan
     * @since 3.1.0
     * @return Var,
     */
    
    $billing_fields = WC()->checkout()->get_checkout_fields('billing');
    $get_ins_data_for_editor_fl = unserialize(insopt('checkout_editors_fields'));
    $ins_all_checkout_fields = !empty($get_ins_data_for_editor_fl) ? $get_ins_data_for_editor_fl : $billing_fields;
    
    //
    function ins_override_checkout_fields($fields) {
        unset($fields['billing']['billing_address_2']);

        foreach( $ins_all_checkout_fields as $fieldskey => $ins_field){
            $field_origin   = $ins_field['checkout_form_field_origin'];

            // All Fields 
            $firstNmae          = $fields['billing']['billing_first_name'];
            $lastNmae           = $fields['billing']['billing_last_name'];
            $companyNmae        = $fields['billing']['billing_company'];
            $billEmail          = $fields['billing']['billing_email'];
            $billCountry        = $fields['billing']['billing_country'];
            $billAddress1       = $fields['billing']['billing_address_1'];
            // $billcountry        = $fields['billing']['billing_address_2'];
            $billCity           = $fields['billing']['billing_city'];
            $billState          = $fields['billing']['billing_state'];
            $billPosCode        = $fields['billing']['billing_postcode'];
            $billPhone          = $fields['billing']['billing_phone'];

            // Check All Fields Origin And Set Data Accordingly 
            if($field_origin == $firstNmae){
                $firstNmae['label']             = $ins_field['checkout_form_field_name'];
                $firstNmae['placeholder']       = $ins_field['checkout_form_field_place'];
            } elseif ($field_origin == $lastNmae){
                $lastNmae['label']              = $ins_field['checkout_form_field_name'];
                $lastNmae['placeholder']        = $ins_field['checkout_form_field_place'];
            } elseif ($field_origin == $companyNmae){
                $companyNmae['label']           = $ins_field['checkout_form_field_name'];
                $companyNmae['placeholder']     = $ins_field['checkout_form_field_place'];
            } elseif ($field_origin == $billEmail){
                $billEmail['label']             = $ins_field['checkout_form_field_name'];
                $billEmail['placeholder']       = $ins_field['checkout_form_field_place'];
            } elseif ($field_origin == $billCountry){
                $billCountry['label']           = $ins_field['checkout_form_field_name'];
                $billCountry['placeholder']     = $ins_field['checkout_form_field_place'];
            } elseif ($field_origin == $billAddress1){
                $billAddress1['label']          = $ins_field['checkout_form_field_name'];
                $billAddress1['placeholder']    = $ins_field['checkout_form_field_place'];
            } elseif ($field_origin == $billCity){
                $billCity['label']              = $ins_field['checkout_form_field_name'];
                $billCity['placeholder']        = $ins_field['checkout_form_field_place'];
            } elseif ($field_origin == $billState){
                $billState['label']             = $ins_field['checkout_form_field_name'];
                $billState['placeholder']       = $ins_field['checkout_form_field_place'];
            } elseif ($field_origin == $billPosCode){
                $billPosCode['label']           = $ins_field['checkout_form_field_name'];
                $billPosCode['placeholder']     = $ins_field['checkout_form_field_place'];
            } elseif ($field_origin == $billPhone){
                $billPhone['label']             = $ins_field['checkout_form_field_name'];
                $billPhone['placeholder']       = $ins_field['checkout_form_field_place'];
            } 
        }

        // $fields['shipping']['shipping_first_name']['placeholder'] = 'First Name';
        // $fields['shipping']['shipping_last_name']['placeholder'] = 'Last Name';
        // $fields['shipping']['shipping_company']['placeholder'] = 'Company Name'; 
        return $fields;
    }

    add_filter('woocommerce_checkout_fields', 'ins_override_checkout_fields');

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

       



    

   
   

    
    // echo ob_get_clean();
?>