<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

$badge_up = '<div class="ins-csf-badge"><span class="ins-upcoming">' .__("Upcoming", "instantio"). '</span></div>';
$badge_pro = '<div class="ins-csf-badge"><span class="ins-pro">' .__("Pro Feature", "instantio"). '</span></div>';
$badge_up_pro = '<div class="ins-csf-badge"><span class="ins-upcoming">' .__("Upcoming", "instantio"). '</span><span class="ins-pro">' .__("Pro Feature", "instantio"). '</span></div>';

CSF::createSection( $prefix, array(
    'id'    => 'mobile',
    'title' => __( 'Mobile', 'instantio' ),
    'icon'  => 'fas fa-mobile-alt',
    'fields' => array(

        array(
            'id'       => 'mobile',
            'class' => 'ins-csf-disable ins-csf-pro',
            'type'     => 'switcher',
            'title'    => __('Dedicated Mobile Version', 'instantio'),
            'subtitle' => __('Enable/disable dedicated mobile version' .$badge_pro, 'instantio'),
            'text_on'    => __('Enabled', 'instantio'),
            'text_off'   => __('Disabled', 'instantio'),
            'text_width' => 100,
            'default'   => true,
        ),

        array(
            'type'    => 'subheading',
            'content' => __('Cart Section', 'instantio'),
        ),

        array(
            'id'       => 'mobile-cart-panel',
            'class' => 'ins-csf-disable ins-csf-pro',
            'type'     => 'switcher',
            'title'    => __('Enable Cart Panel', 'instantio'),
            'subtitle' => __('Enable/disable cart in mobile version' .$badge_pro, 'instantio'),
            'text_on'    => __('Yes', 'instantio'),
            'text_off'   => __('No', 'instantio'),
            'default'   => true,
        ),

        array(
            'id'         => 'mobile-cart-url',
            'class' => 'ins-csf-disable ins-csf-pro',
            'type'       => 'button_set',
            'title'      => __('Cart URL', 'instantio'),
            'subtitle'   => $badge_pro,
            'options'    => array(
              'default'  => __('Default', 'instantio'),
              'custom' => __('Custom', 'instantio'),
              'no' => __('No URL', 'instantio'),
              'hide' => __('Hide', 'instantio'),
            ),
            'default'    => 'default',
        ),

        array(
            'id'      => 'mobile-cart-cc-url',
            'class' => 'ins-csf-disable ins-csf-pro',
            'type'    => 'text',
            'title'   => __('Custom Cart URL', 'instantio'),
            'subtitle'   => $badge_pro,
            'default' => get_site_url() . '/cart',
            'validate' => 'csf_validate_url',
        ),

        array(
            'type'    => 'subheading',
            'content' => __('Checkout Section', 'instantio'),
        ),

        array(
            'id'       => 'mobile-checkout-panel',
            'class' => 'ins-csf-disable ins-csf-pro',
            'type'     => 'switcher',
            'title'    => __('Enable Checkout Panel', 'instantio'),
            'subtitle' => __('Enable/disable checkout in mobile version' .$badge_pro, 'instantio'),
            'text_on'    => __('Yes', 'instantio'),
            'text_off'   => __('No', 'instantio'),
            'default'   => true,
        ),

        array(
            'id'         => 'mobile-checkout-url',
            'class' => 'ins-csf-disable ins-csf-pro',
            'type'       => 'button_set',
            'title'      => __('Checkout URL', 'instantio'),
            'subtitle'   => $badge_pro,
            'options'    => array(
              'default'  => __('Default', 'instantio'),
              'custom' => __('Custom', 'instantio'),
              'hide' => __('Hide', 'instantio'),
            ),
            'default'    => 'default',
        ),

        array(
            'id'      => 'mobile-checkout-cc-url',
            'class' => 'ins-csf-disable ins-csf-pro',
            'type'    => 'text',
            'title'   => __('Custom Checkout URL', 'instantio'),
            'subtitle'   => $badge_pro,
            'default' => get_site_url() . '/checkout',
            'validate' => 'csf_validate_url',
        ),

        array(
            'id'      => 'mobile-checkout-txt',
            'class' => 'ins-csf-disable ins-csf-pro',
            'type'    => 'text',
            'title'   => __('Checkout Button Text', 'instantio'),
            'subtitle'   => $badge_pro,
            'desc'   => __( 'Default: <code>Checkout Now</code>', 'instantio' ),
        ),

    )
) );

?>