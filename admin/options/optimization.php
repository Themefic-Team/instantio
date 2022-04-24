<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

$badge_up = '<div class="ins-csf-badge"><span class="ins-upcoming">' .__("Upcoming", "instantio"). '</span></div>';
$badge_pro = '<div class="ins-csf-badge"><span class="ins-pro">' .__("Pro Feature", "instantio"). '</span></div>';
$badge_up_pro = '<div class="ins-csf-badge"><span class="ins-upcoming">' .__("Upcoming", "instantio"). '</span><span class="ins-pro">' .__("Pro Feature", "instantio"). '</span></div>';

CSF::createSection( $prefix, array(
    'id'    => 'optimization',
    'title' => __( 'Optimization', 'instantio' ),
    'icon'  => 'fas fa-bolt',
    'fields' => array(

        array(
            'id'       => 'fancy-cdn',
            'class' => 'ins-csf-disable ins-csf-pro',
            'type'     => 'switcher',
            'title'    => __('FancyBox CDN', 'instantio'),
            'subtitle' => __('Enable/disable cloudflare CDN for FancyBox CSS & JS' .$badge_pro, 'instantio'),
            'text_on'    => __('Enabled', 'instantio'),
            'text_off'   => __('Disabled', 'instantio'),
            'text_width' => 100,
            'default'   => false,
        ),

        array(
            'id'       => 'css-min',
            'class' => 'ins-csf-disable ins-csf-pro',
            'type'     => 'switcher',
            'title'    => __('Minify CSS', 'instantio'),
            'subtitle' => __('Enable/disable Instantio CSS minification' .$badge_pro, 'instantio'),
            'text_on'    => __('Enabled', 'instantio'),
            'text_off'   => __('Disabled', 'instantio'),
            'text_width' => 100,
            'default'   => false,
        ),

        array(
            'id'       => 'js-min',
            'class' => 'ins-csf-disable ins-csf-pro',
            'type'     => 'switcher',
            'title'    => __('Minify JS', 'instantio'),
            'subtitle' => __('Enable/disable Instantio JS minification' .$badge_pro, 'instantio'),
            'text_on'    => __('Enabled', 'instantio'),
            'text_off'   => __('Disabled', 'instantio'),
            'text_width' => 100,
            'default'   => false,
        ),

    )
) );

?>