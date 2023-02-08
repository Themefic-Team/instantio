<?php 
// don't load directly
defined( 'ABSPATH' ) || exit;

$badge_up = '<div class="ins-csf-badge"><span class="ins-upcoming">' .__("Upcoming", "instantio"). '</span></div>';
$badge_pro = '<div class="ins-csf-badge"><span class="ins-pro">' .__("Pro Feature", "instantio"). '</span></div>';
$badge_up_pro = '<div class="ins-csf-badge"><span class="ins-upcoming">' .__("Upcoming", "instantio"). '</span><span class="ins-pro">' .__("Pro Feature", "instantio"). '</span></div>';

CSF::createSection( $prefix, array(
'parent' => 'design', // The slug id of the parent section
'title' => __( 'Toggler Design', 'instantio' ),
'fields' => array(

  array(
    'type'    => 'subheading',
    'content' => __('Toggler Design', 'instantio'),
  ),

  array(
    'id'       => 'hide-toggler',
    'type'     => 'switcher', 
    'title'    => __('Hide Toggler when No Cart Item', 'instantio'),
    'subtitle' => __('When there is no item in the cart hide toggler', 'instantio'),
    'text_on' => __('Yes', 'instantio'),
    'text_off' => __('No', 'instantio'),
    'default'  => false,
  ),

  array(
    'id'       => 'cart-icon',
    'type'     => 'image_select',
    'title'    => __('Toggler Icon', 'instantio'), 
    'subtitle' => __('Select cart icon which will appear in cart toggler', 'instantio'),
    'options'  => array(
      'cart-1' => INS_ADMIN_URL.'/img/cart-1.svg',
      'cart-2' => INS_ADMIN_URL.'/img/cart-2.svg',
      'cart-3' => INS_ADMIN_URL.'/img/cart-3.svg',
      'cart-4' => INS_ADMIN_URL.'/img/cart-4.svg',
      'cart-5' => INS_ADMIN_URL.'/img/cart-5.svg',
      'cart-6' => INS_ADMIN_URL.'/img/cart-6.svg',
    ),
    'default' => 'cart-1'
  ),

  array(
    'id'       => 'wi-icon-choice',
    'class' => 'ins-csf-disable ins-csf-pro',
    'type'     => 'switcher', 
    'title'    => __('Custom Image as Toggler Icon', 'instantio'),
    'subtitle' => __('Set custom image as icon for the toggler instead of the defaults one.' .$badge_pro, 'instantio'),
          'text_on'  => __('Yes', 'instantio'),
          'text_off' => __('No', 'instantio'),
    'default'  => false,
  ),

  array(
    'id'       => 'toggle-position-horizontal',
    'type'     => 'button_set',
    'title'    => __('Toggler Horizontal Position', 'instantio'),
		'subtitle' => __('Changes position of the Cart Toggler horizontally', 'instantio'),
    'options' => array(
      'left' => __('Left', 'instantio'),
      'right' => __('Right', 'instantio'),
     ), 
    'default' => 'right'
  ),

  array(
    'id'       => 'toggle-position-vertical',
    'type'     => 'button_set',
    'title'    => __('Toggler Vertical Position', 'instantio'),
		'subtitle' => __('Changes position of the Cart Toggler vertically', 'instantio'),
    'options' => array(
      'top' => __('Top', 'instantio'),
      'middle' => __('Middle', 'instantio'),
      'bottom' => __('Bottom', 'instantio'),				
     ), 
    'default' => 'bottom',
    'dependency' => array('ins-toggler|ins-layout','!=|!=', 'tog-2|1', 'all', 'visible'),
  ),

  array(
    'id'        => 'wi-header-bg-colors',
    'type'      => 'color_group',
    'title'    => __( 'Toggler Background Colors', 'instantio' ),
    'subtitle' => __( 'Set regular & hover color', 'instantio' ),
    'options'   => array(
      'regular' => __('Regular', 'instantio'),
      'hover' => __('Hover', 'instantio'),
    )
  ),

  array(
    'id'        => 'wi-header-border-colors',
    'type'      => 'color_group',
    'title'    => __( 'Toggler Border Colors', 'instantio' ),
    'subtitle' => __( 'Set regular & hover color', 'instantio' ),
    'options'   => array(
      'regular' => __('Regular', 'instantio'),
      'hover' => __('Hover', 'instantio'),
    )
  ),

  array(
    'id'        => 'ins-tog-icon-colors',
    'type'      => 'color_group',
    'title'    => __( 'Toggler Icon Color', 'instantio' ),
    'subtitle' => __( 'Set regular & hover color of text & icon', 'instantio' ),
    'options'   => array(
      'regular' => __('Regular', 'instantio'),
      'hover' => __('Hover', 'instantio'),
    )
  ),

  array(
    'id'     => 'wi-header-icon-size',
    'type'   => 'dimensions',
    'title'  => __('Toggler Icon Size', 'instantio'),
    'subtitle' => __('Set width of the toggler icon', 'instantio'),
    'desc'     => __('Default: 26px', 'instantio'),
    'height' => false,
    'units'  => array( 'px' ),
  ),

  array(
    'id'        => 'ins-tog-item-bg',
    'type'      => 'color_group',
    'title'    => __( 'Toggler Item Number Background', 'instantio' ),
    'subtitle' => __( 'Set regular & hover background color', 'instantio' ),
    'options'   => array(
      'regular' => __('Regular', 'instantio'),
      'hover' => __('Hover', 'instantio'),
    )
  ),

  array(
    'id'        => 'wi-header-text-colors',
    'type'      => 'color_group',
    'title'    => __( 'Toggler Item Number Color', 'instantio' ),
    'subtitle' => __( 'Set regular & hover color of text & icon', 'instantio' ),
    'options'   => array(
      'regular' => __('Regular', 'instantio'),
      'hover' => __('Hover', 'instantio'),
    )
  ),

  array(
    'id'    => 'wi-header-text-size',
    'type'  => 'typography',
    'title'  => __('Toggler Item Number Size', 'instantio'),
    'subtitle' => __('Set font size & line height of cart toggler text', 'instantio'),
    'desc'     => __('Default: 14px', 'instantio'),
    'font_family' => false,
    'text_align' => false,
    'text_transform' => false,
    'line_height' => false,
    'letter_spacing' => false,
    'color' => false,
    'preview' => false,
    'chosen' => false,
  ),

)
) );
  
?>