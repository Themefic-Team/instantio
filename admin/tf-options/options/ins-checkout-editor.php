<?php
    /**
     *  init Instantio Checkout Editor.
     * @author Hemel Hasan
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
                    'checkout_form_field_name'    => $label,
                    'checkout_form_field_place'   => $placeholder,
                    'required'                    => $required,
                    'checkout_form_field_status'  => true,
                    'priority'                    => $priority,
                );

            }
            // var_dump($my_plugin_billing_fields);
        }
    }

        $ins_fi = insopt('checkout_editors_fields');
        $test = unserialize($ins_fi);

        echo "<pre>";
        var_dump($test);
        echo "</pre>";

        // exit();



    

   
   

    
    // echo ob_get_clean();
?>