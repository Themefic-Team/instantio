<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

$badge_up = '<div class="ins-csf-badge"><span class="ins-upcoming">' .__("Upcoming", "instantio"). '</span></div>';
$badge_pro = '<div class="ins-csf-badge"><span class="ins-pro">' .__("Pro Feature", "instantio"). '</span></div>';
$badge_up_pro = '<div class="ins-csf-badge"><span class="ins-upcoming">' .__("Upcoming", "instantio"). '</span><span class="ins-pro">' .__("Pro Feature", "instantio"). '</span></div>';

CSF::createSection( $prefix, array(
'parent' => 'design', // The slug id of the parent section
'title' => __( 'Toggle Panel Design', 'instantio' ),
'fields' => array(

  array(
    'type'    => 'subheading',
    'content' => __('Toggle Panel Design', 'instantio'),
  ),

  array(
    'id'       => 'toggle-panel-position',
    'type'     => 'button_set',
    'title'    => __('Toggle Panel Position', 'instantio'),
    'subtitle' => __('Changes position of the Cart Toggle Panel', 'instantio'),
    'options' => array(
      'left' => 'Left', 
      'right' => 'Right', 
     ), 
    'default' => 'right',
    'dependency' => array('ins-layout','any', '2,4', 'all', 'visible'),
  ),

  array(
    'id'        => 'wi-inner-bg-colors',
    'type'      => 'color_group',
    'title'    => __( 'Toggle Panel Background Colors', 'instantio' ),
    'subtitle' => __( 'Checkout button background regular & hover color', 'instantio' ),
    'options'   => array(
      'regular' => 'Regular',
      'hover' => 'Hover',
    ),
    'dependency' => array('ins-layout','any', '1', 'all'),
  ),

  array(
    'id'        => 'wi-inner-border-colors',
    'type'      => 'color_group',
    'title'    => __( 'Toggle Panel Border Colors', 'instantio' ),
    'subtitle' => __( 'Checkout button border regular & hover color', 'instantio' ),
    'options'   => array(
      'regular' => 'Regular',
      'hover' => 'Hover',
    ),
    'dependency' => array('ins-layout','any', '1', 'all'),
  ),

  array(
    'id'        => 'wi-inner-text-colors',
    'type'      => 'color_group',
    'title'    => __( 'Toggle Panel Text Colors', 'instantio' ),
    'subtitle' => __( 'Set regular & hover color of checkout text', 'instantio' ),
    'options'   => array(
      'regular' => 'Regular',
      'hover' => 'Hover',
    ),
    'dependency' => array('ins-layout','any', '1', 'all'),
  ),

  array(
    'id'        => 'wi-container-bg',
    'class' => 'ins-csf-disable ins-csf-pro',
    'type'      => 'color',
    'title'    => __( 'Panel Background', 'instantio' ),
    'subtitle' => __( 'Toggle Panel Background Color' .$badge_pro, 'instantio' ),
    'dependency' => array('ins-layout','!=', '1', 'all'),
    'output'      => '.ins-container .ins-inner, .fancybox-content',
    'output_mode' => 'background',
  ),

  array(
      'id'     => 'ins-panel-border',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'   => 'border',
      'title'  => 'Border',
      'subtitle'   => $badge_pro,
      'output' => '.ins-container.panel-open .ins-inner',
      'output_important' => true,
  ),

  array(
      'id'        => 'ins-panel-text-color',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'      => 'color',
      'title'    => __( 'Panel Text Color', 'instantio' ),
      'subtitle' => __( 'Openning heading "Your Cart" color' .$badge_pro, 'instantio' ),
      'dependency' => array('ins-layout','!=', '1', 'all'),
      'output' => array( 'fill' => '.ins-container .ins-header h3 svg, .ins-container .ins-close svg', 'color' => '.element-2', 'color' => '.ins-container .ins-header h3' ),
  ),

  array(
      'id'        => 'ins-panel-button-bg',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'      => 'color_group',
      'title'    => __( 'Panel Button Colors', 'instantio' ),
      'subtitle' => __( '"Proceed to Checkout", "Back to Cart" button color' .$badge_pro, 'instantio' ),
      'options'   => array(
          'regular' => 'Regular',
          'hover' => 'Hover',
      ),
      
  ),

  array(
      'id'        => 'ins-panel-button-border',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'      => 'color_group',
      'title'    => __( 'Panel Button Border Colors', 'instantio' ),
      'subtitle' => __( '"Proceed to Checkout", "Back to Cart" button border color' .$badge_pro, 'instantio' ),
      'options'   => array(
          'regular' => 'Regular',
          'hover' => 'Hover',
      ),
      
  ),

  array(
      'id'        => 'ins-panel-button-text',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'      => 'color_group',
      'title'    => __( 'Panel Button Text Colors', 'instantio' ),
      'subtitle' => __( '"Proceed to Checkout", "Back to Cart" button text color' .$badge_pro, 'instantio' ),
      'options'   => array(
          'regular' => 'Regular',
          'hover' => 'Hover',
      ),
      
  ),

  array(
      'id'    => 'wi-zindex',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'  => 'slider',
      'title'    => __( 'Panel Z-index', 'instantio' ),
      'subtitle' => __( 'Control z-index from this option. More about <a target="_blank" href="https://css-tricks.com/almanac/properties/z/z-index/">z-index</a>' .$badge_pro, 'instantio' ),
      'default' => 99999,
      'min' => 999,
      'max' => 9999999,
      
    ),

    array(
      'id'        => 'panel-width-1200',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'      => 'slider',
      'title'     => __('Toggle Panel Width (1200px-auto)', 'instantio'),
      'subtitle'  => __('Set the percent of width of toggle panel for display dimension greater than 1199px.' .$badge_pro, 'instantio'),
      'desc'  => __('Range 0%-100%. Default 40', 'instantio'),
      "default"   => 40,
      "min"       => 1,
      "step"      => 1,
      "max"       => 100,
      'unit'      => '%',
      
  ),
  array(
      'id'        => 'panel-width-1024',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'      => 'slider',
      'title'     => __('Toggle Panel Width (1024px-1199px)', 'instantio'),
      'subtitle'  => __('Set the percent of width of toggle panel for display dimension greater than 1023px.' .$badge_pro, 'instantio'),
      'desc'  => __('Range 0%-100%. Default 48', 'instantio'),
      "default"   => 48,
      "min"       => 1,
      "step"      => 1,
      "max"       => 100,
      'unit'      => '%',
      
  ),
  array(
      'id'        => 'panel-width-767',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'      => 'slider',
      'title'     => __('Toggle Panel Width (501px-1023)', 'instantio'),
      'subtitle'  => __('Set the percent of width of toggle panel for display dimension greater than 500px.' .$badge_pro, 'instantio'),
      'desc'  => __('Range 0%-100%. Default 60', 'instantio'),
      "default"   => 60,
      "min"       => 1,
      "step"      => 1,
      "max"       => 100,
      'unit'      => '%',
      
  ),

  array(
      'type'    => 'submessage',
      'style'   => 'info',
      'content' => 'Width is 100% for devices which dimension up to 500px.',
      
  ),

  // Cart
  array(
      'type'    => 'content',
      'content' => '<div id="cart" class="ins-scroll-to"></div>',
      
  ),

  array(
      'type'    => 'subheading',
      'content' => __('Cart Section', 'instantio'),
  ),

  array(
      'id'       => 'cart-header-bg',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Cart Header Background', 'instantio' ),
      'subtitle' => __( 'Toggle panel cart header background color' .$badge_pro, 'instantio' ),
  'output'    => array( 'background' => '.ins-container table.shop_table.cart th' ),
  
  ),

  array(
      'id'       => 'cart-header-text',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Cart Header Text Color', 'instantio' ),
      'subtitle' => __( 'Toggle panel cart header text color' .$badge_pro, 'instantio' ),
  'output'   => array('.ins-container table.shop_table.cart th'),
  
  ),

  array(
      'id'       => 'cart-item-bg',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Cart Items Background', 'instantio' ),
      'subtitle' => __( 'Toggle panel cart items background color' .$badge_pro, 'instantio' ),
  'output'    => array('background' => '.ins-container table.shop_table .cart_item td'),
  
  ),

  array(
      'id'       => 'cart-item-text-color',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Cart Item Text Color', 'instantio' ),
      'subtitle' => __( 'Toggle panel cart item text color' .$badge_pro, 'instantio' ),
  'output'   => array('.ins-container .cart_item .woocommerce-Price-amount.amount'),
  
  ),

  array(
      'id'       => 'cart-input-bg',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Cart Input Background', 'instantio' ),
      'subtitle' => __( 'Toggle panel cart input background color' .$badge_pro, 'instantio' ),
  'output'    => array('background' => '.ins-container .quantity .qty, .ins-container .actions .ins-cart-update .ins-coupon',
          'border-color' => '.ins-container .actions .ins-cart-update .ins-coupon'),
          
  ),

  array(
      'id'       => 'cart-input-text-color',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Cart Input Text Color', 'instantio' ),
      'subtitle' => __( 'Toggle panel cart input text color' .$badge_pro, 'instantio' ),
      
  ),

  array(
      'id'        => 'cart-button-background-colors',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'      => 'color_group',
      'title'    => __( 'Cart Button Background Colors', 'instantio' ),
      'subtitle' => __( 'Set regular & hover color' .$badge_pro, 'instantio' ),
      'options'   => array(
      'regular' => 'Regular',
      'hover' => 'Hover',
      ),
      
  ),

  array(
      'id'        => 'cart-button-text-colors',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'      => 'color_group',
      'title'    => __( 'Cart Button Text Colors', 'instantio' ),
      'subtitle' => __( 'Set regular & hover color' .$badge_pro, 'instantio' ),
      'options'   => array(
      'regular' => 'Regular',
      'hover' => 'Hover',
      ),
      
  ),

  array(
      'id'       => 'cart-pricing-bg',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Cart Pricing Table Background', 'instantio' ),
      'subtitle' => __( 'Toggle panel pricing table background color' .$badge_pro, 'instantio' ),
  'output'    => array('background' => '.ins-container .cart_totals table.shop_table th, .ins-container .cart_totals table.shop_table tbody td'),
  
  ),

  array(
      'id'       => 'cart-pricing-text',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Cart Pricing Table Text Color', 'instantio' ),
      'subtitle' => __( 'Toggle panel pricing table text color' .$badge_pro, 'instantio' ),
  'output'    => array('.ins-container .cart_totals table.shop_table tbody th, .ins-container .cart_totals table.shop_table tbody td .woocommerce-Price-amount.amount'),
  
  ),

  // Billing
  array(
      'type'    => 'content',
      'content' => '<div id="billing" class="ins-scroll-to"></div>',
  ),

  array(
      'type'    => 'subheading',
      'content' => __('Billing Section', 'instantio'),
  ),

  array(
      'id'       => 'ins-bill-bg',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Panel Billing Background', 'instantio' ),
      'subtitle' => __( 'Toggle panel billing section background color' .$badge_pro, 'instantio' ),
  'output'    => array('background' => '.ins-checkout-body #customer_details'),
  
  ),

  array(
      'id'       => 'ins-bill-heading',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Panel Billing Heading Color', 'instantio' ),
      'subtitle' => __( 'Toggle panel billing section heading text color' .$badge_pro, 'instantio' ),
  'output'    => array('color' => '.ins-checkout-body #customer_details h3', 'border-color' => '.ins-checkout-body #customer_details h3'),
  
  ),

  array(
  'id'             => 'ins-bill-bg-padding',
  'class' => 'ins-csf-disable ins-csf-pro',
  'type'           => 'spacing',
  'output'         => array('.ins-checkout-body #customer_details'),
  'mode'           => 'padding',
  'units'          => array('px'),
  'units_extended' => 'false',
  'title'          => __('Panel Billing Padding', 'instantio'),
  'subtitle'       => __('Toggle panel billing section padding' .$badge_pro, 'instantio'),
  'default'            => array(
  'top'     => '0', 
  'right'   => '0', 
  'bottom'  => '0', 
  'left'    => '0',
  'units'   => 'px', 
  ),
  
  ),

  array(
      'id'       => 'ins-bill-label',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Panel Billing Label Color', 'instantio' ),
      'subtitle' => __( 'Toggle panel billing section label text color' .$badge_pro, 'instantio' ),
  'output'    => array('.ins-checkout-body form.woocommerce-checkout .form-row label'),
  
  ),

  array(
      'id'       => 'ins-bill-input-bg',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Panel Billing Input Background', 'instantio' ),
      'subtitle' => __( 'Toggle panel billing section input background color' .$badge_pro, 'instantio' ),
  'output'    => array('background' => '.ins-checkout-body form.woocommerce-checkout .input-text, .ins-checkout-body form.woocommerce-checkout .select2-selection'),
  
  ),

  array(
      'id'       => 'ins-bill-input-border',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Panel Billing Input Border Color', 'instantio' ),
      'subtitle' => __( 'Toggle panel billing section input border color' .$badge_pro, 'instantio' ),
  'output'    => array('border-color' => '.ins-checkout-body form.woocommerce-checkout .input-text, .ins-checkout-body form.woocommerce-checkout .select2-selection'),
  
  ),

  array(
      'id'       => 'ins-bill-input-text',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Panel Billing Input Text Color', 'instantio' ),
      'subtitle' => __( 'Toggle panel billing section input text color' .$badge_pro, 'instantio' ),
  'output'    => array('.ins-checkout-body form.woocommerce-checkout .input-text, .ins-checkout-body form.woocommerce-checkout .select2-selection, .ins-checkout-body form.woocommerce-checkout .input-text::placeholder, .ins-checkout-body form.woocommerce-checkout .select2-selection .select2-selection__rendered'),
  
  ),

  array(
      'id'       => 'ins-bill-input-shadow',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Panel Billing Input Shadow Color', 'instantio' ),
      'subtitle' => __( 'Toggle panel billing section input shadow color' .$badge_pro, 'instantio' ),
      
  ),

  // Review
  array(
      'type'    => 'content',
      'content' => '<div id="review" class="ins-scroll-to"></div>',
  ),

  array(
      'type'    => 'subheading',
      'content' => __('Review Section', 'instantio'),
  ),

  array(
      'id'       => 'ins-review-heading',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Order Review Heading', 'instantio' ),
      'subtitle' => __( 'Toggle panel review section heading color' .$badge_pro, 'instantio' ),
  'output'    => array('color' => '.ins-checkout-body #order_review_heading',
          'border-color' => '.ins-checkout-body #order_review_heading'),
          
  ),

  array(
      'id'       => 'ins-review-bg',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Review Section Background', 'instantio' ),
      'subtitle' => __( 'Toggle panel review section background color' .$badge_pro, 'instantio' ),
  'output'    => array('background' => '.ins-checkout-body .woocommerce-checkout-review-order-table'),
  
  ),

  array(
      'id'       => 'ins-review-tbl-head-bg',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Review Table Head Background', 'instantio' ),
      'subtitle' => __( 'Toggle panel review section table head background color' .$badge_pro, 'instantio' ),
  'output'    => array('background' => '.ins-checkout-body table.woocommerce-checkout-review-order-table thead tr th'),
  
  ),

  array(
      'id'       => 'ins-review-tbl-head-txt',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Review Table Head Text Color', 'instantio' ),
      'subtitle' => __( 'Toggle panel review section table head text color' .$badge_pro, 'instantio' ),
  'output'    => array('.ins-checkout-body table.woocommerce-checkout-review-order-table thead tr th'),
  
  ),

  array(
      'id'       => 'ins-review-tbl-item-bg',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Review Table Item Background', 'instantio' ),
      'subtitle' => __( 'Toggle panel review section table item background color' .$badge_pro, 'instantio' ),
      
  ),

  array(
      'id'       => 'ins-review-tbl-item-txt',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Review Table Item Text Color', 'instantio' ),
      'subtitle' => __( 'Toggle panel review section table item text color' .$badge_pro, 'instantio' ),
  'output'    => array('.ins-checkout-body table.woocommerce-checkout-review-order-table tbody td, .ins-checkout-body tbody .woocommerce-Price-amount.amount'),
  
  ),

  array(
      'id'       => 'ins-review-pricing-bg',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Review Pricing Background', 'instantio' ),
      'subtitle' => __( 'Toggle panel review section pricing background color' .$badge_pro, 'instantio' ),
  'output'    => array('background' => '.ins-checkout-body #order_review table.woocommerce-checkout-review-order-table tfoot th, .ins-checkout-body table.woocommerce-checkout-review-order-table tfoot td'),
  
  ),

  array(
      'id'       => 'ins-review-pricing-txt',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Review Pricing Text Color', 'instantio' ),
      'subtitle' => __( 'Toggle panel review section pricing text color' .$badge_pro, 'instantio' ),
  'output'    => array('.ins-checkout-body #order_review table.woocommerce-checkout-review-order-table tfoot th, .ins-checkout-body tfoot .woocommerce-Price-amount.amount'),
  
  ),

  // Payment
  array(
      'type'    => 'content',
      'content' => '<div id="payment" class="ins-scroll-to"></div>',
    ),

  array(
      'type'    => 'subheading',
      'content' => __('Payment Section', 'instantio'),
  ),

  array(
      'id'       => 'ins-pay-item-bg',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Payment Methods Background', 'instantio' ),
      'subtitle' => __( 'Toggle panel Payment methods background color' .$badge_pro, 'instantio' ),
  'output'    => array('background' => '.ins-checkout-body .woocommerce-checkout #payment ul.payment_methods li label'),
  
  ),

  array(
      'id'       => 'ins-pay-item-txt',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Payment Methods Text Color', 'instantio' ),
      'subtitle' => __( 'Toggle panel Payment methods text color' .$badge_pro, 'instantio' ),
  'output'    => array('.ins-checkout-body .woocommerce-checkout #payment ul.payment_methods li label, .ins-checkout-body .woocommerce-checkout #payment ul.payment_methods li label a'),
  
  ),

  array(
      'id'       => 'ins-pay-item-des-bg',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Payment Methods Description Background', 'instantio' ),
      'subtitle' => __( 'Toggle panel Payment methods description background color' .$badge_pro, 'instantio' ),
  'output'    => array('background' => '.ins-checkout-body .woocommerce-checkout #payment div.payment_box'),
  
  ),

  array(
      'id'       => 'ins-pay-item-des-txt',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Payment Methods Description Text Color', 'instantio' ),
      'subtitle' => __( 'Toggle panel Payment methods description text color' .$badge_pro, 'instantio' ),
  'output'    => array('#payment .payment_methods li p'),
  
  ),

  array(
      'id'       => 'ins-place-order-bg',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Place Order Background', 'instantio' ),
      'subtitle' => __( 'Toggle panel place order background color' .$badge_pro, 'instantio' ),
  'output'    => array('background' => '.ins-checkout-body .woocommerce-checkout #payment .place-order'),
  
  ),

  array(
      'id'       => 'ins-place-order-txt',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'     => 'color',
      'title'    => __( 'Place Order Text Color', 'instantio' ),
      'subtitle' => __( 'Toggle panel place order text color' .$badge_pro, 'instantio' ),
  'output'    => array('.woocommerce-terms-and-conditions-wrapper .woocommerce-privacy-policy-text p,
            .woocommerce-terms-and-conditions-wrapper .woocommerce-privacy-policy-text p a'),
            
  ),

  array(
      'id'        => 'ins-place-order-button-bg',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'      => 'color_group',
      'title'    => __( 'Place Order Button Background', 'instantio' ),
      'subtitle' => __( 'Place order button background regular & hover color' .$badge_pro, 'instantio' ),
      'options'   => array(
      'regular' => 'Regular',
      'hover' => 'Hover',
      ),
      
  ),

  array(
      'id'        => 'ins-place-order-button-border',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'      => 'color_group',
      'title'    => __( 'Place Order Button Border Colors', 'instantio' ),
      'subtitle' => __( 'Place order button border regular & hover color' .$badge_pro, 'instantio' ),
      'options'   => array(
      'regular' => 'Regular',
      'hover' => 'Hover',
      ),
      
  ),

  array(
      'id'        => 'ins-place-order-button-text',
      'class' => 'ins-csf-disable ins-csf-pro',
      'type'      => 'color_group',
      'title'    => __( 'Place Order Button Text Colors', 'instantio' ),
      'subtitle' => __( 'Place order button text regular & hover color' .$badge_pro, 'instantio' ),
      'options'   => array(
      'regular' => 'Regular',
      'hover' => 'Hover',
      ),
      
  ),
 
)
) );

?>