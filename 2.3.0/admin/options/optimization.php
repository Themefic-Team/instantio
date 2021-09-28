<?php

CSF::createSection( $prefix, array(
    'id'    => 'optimization',
    'title' => __( 'Optimization', 'instantio' ),
    'icon'  => 'fas fa-bolt',
    'fields' => array(

        array(
            'id'       => 'fancy-cdn',
            'type'     => 'switcher',
            'title'    => __('FancyBox CDN', 'instantio'),
            'subtitle' => __('Enable/disable cloudflare CDN for FancyBox CSS & JS', 'instantio'),
            'text_on'    => 'Enabled',
            'text_off'   => 'Disabled',
            'text_width' => 100,
            'default'   => false,
            'help'  => 'Pro Feature',
            'dependency' => array( '', '==', '', '', 'visible' ),
        ),

        array(
            'id'       => 'css-min',
            'type'     => 'switcher',
            'title'    => __('Minify CSS', 'instantio'),
            'subtitle' => __('Enable/disable Instantio CSS minification', 'instantio'),
            'text_on'    => 'Enabled',
            'text_off'   => 'Disabled',
            'text_width' => 100,
            'default'   => false,
            'help'  => 'Pro Feature',
            'dependency' => array( '', '==', '', '', 'visible' ),
        ),

        array(
            'id'       => 'js-min',
            'type'     => 'switcher',
            'title'    => __('Minify JS', 'instantio'),
            'subtitle' => __('Enable/disable Instantio JS minification', 'instantio'),
            'text_on'    => 'Enabled',
            'text_off'   => 'Disabled',
            'text_width' => 100,
            'default'   => false,
            'help'  => 'Pro Feature',
            'dependency' => array( '', '==', '', '', 'visible' ),
        ),

    )
) );

?>