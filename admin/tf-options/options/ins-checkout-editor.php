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



            $shipping_fields = WC()->checkout()->get_checkout_fields('shipping');

            // Store the shiping fields in a global variable
            $my_plugin_shipping_fields = array();

            foreach( $shipping_fields as $key => $insshipfields){

                $label       = !empty($insshipfields['label']) ? $insshipfields['label'] : '';
                $placeholder = !empty($insshipfields['placeholder']) ? $insshipfields['placeholder'] : '';
                $required    = !empty($insshipfields['required']) ? $insshipfields['required'] : 'false';
                $priority    = !empty($insshipfields['priority']) ? $insshipfields['priority'] : '';

                $my_plugin_shipping_fields[] = array(
                    'checkout_shipping_form_field_origin'   => $key,
                    'checkout_shipping_form_field_name'     => $label,
                    'checkout_shipping_form_field_place'    => $placeholder,
                    'required_shipping'                     => $required,
                    'checkout_shipping_form_field_status'   => true,
                    'priority_shipping'                     => $priority,
                );

            }
 
        }
    }

   
// echo ob_get_clean();
    
    
?>