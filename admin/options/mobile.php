<?php

CSF::createSection( $prefix, array(
    'id'    => 'mobile',
    'title' => __( 'Mobile', 'instantio' ),
    'icon'  => 'fas fa-mobile-alt',
    'fields' => array(

        array(
            'id'       => 'mobile',
            'type'     => 'switcher',
            'title'    => __('Dedicated Mobile Version', 'instantio'),
            'subtitle' => __('Enable/disable dedicated mobile version', 'instantio'),
            'text_on'    => 'Enabled',
            'text_off'   => 'Disabled',
            'text_width' => 100,
            'default'   => true,
            'help'  => 'Pro Feature',
            'dependency' => array( '', '==', '', '', 'visible' ),
        ),

        array(
            'type'    => 'subheading',
            'content' => __('Cart Section', 'instantio'),
        ),

        array(
            'id'       => 'mobile-cart-panel',
            'type'     => 'switcher',
            'title'    => __('Enable Cart Panel', 'instantio'),
            'subtitle' => __('Enable/disable cart in mobile version', 'instantio'),
            'text_on'    => 'Yes',
            'text_off'   => 'No',
            'default'   => true,
            'help'  => 'Pro Feature',
            'dependency' => array( '', '==', '', '', 'visible' ),
        ),

        array(
            'id'         => 'mobile-cart-url',
            'type'       => 'button_set',
            'title'      => __('Cart URL', 'instantio'),
            'options'    => array(
              'default'  => 'Default',
              'custom' => 'Custom',
              'no' => 'No URL',
              'hide' => 'Hide',
            ),
            'default'    => 'default',
            'dependency' => array( '', '==', '', '', 'visible' ),
        ),

        array(
            'id'      => 'mobile-cart-cc-url',
            'type'    => 'text',
            'title'   => __('Custom Cart URL', 'instantio'),
            'default' => get_site_url() . '/cart',
            'validate' => 'csf_validate_url',
            'dependency' => array( '', '==', '', '', 'visible' ),
        ),

        array(
            'type'    => 'subheading',
            'content' => __('Checkout Section', 'instantio'),
        ),

        array(
            'id'       => 'mobile-checkout-panel',
            'type'     => 'switcher',
            'title'    => __('Enable Checkout Panel', 'instantio'),
            'subtitle' => __('Enable/disable checkout in mobile version', 'instantio'),
            'text_on'    => 'Yes',
            'text_off'   => 'No',
            'default'   => true,
            'help'  => 'Pro Feature',
            'dependency' => array( '', '==', '', '', 'visible' ),
        ),

        array(
            'id'         => 'mobile-checkout-url',
            'type'       => 'button_set',
            'title'      => __('Checkout URL', 'instantio'),
            'options'    => array(
              'default'  => 'Default',
              'custom' => 'Custom',
              'hide' => 'Hide',
            ),
            'default'    => 'default',
            'dependency' => array( '', '==', '', '', 'visible' ),
        ),

        array(
            'id'      => 'mobile-checkout-cc-url',
            'type'    => 'text',
            'title'   => __('Custom Checkout URL', 'instantio'),
            'default' => get_site_url() . '/checkout',
            'validate' => 'csf_validate_url',
            'dependency' => array( '', '==', '', '', 'visible' ),
        ),

        array(
            'id'      => 'mobile-checkout-txt',
            'type'    => 'text',
            'title'   => __('Checkout Button Text', 'instantio'),
            'desc'   => __( 'Default: <code>Checkout Now</code>', 'instantio' ),
            'dependency' => array( '', '==', '', '', 'visible' ),
        ),

    )
) );

?>