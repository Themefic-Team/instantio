<?php 

// General Settings
CSF::createSection( $prefix, array(
'id'    => 'general', // Set a unique slug-like ID
'title' => __( 'General', 'instantio' ),
'icon'  => 'fas fa-cogs',
'fields' => array(

  array(
		  'type'    => 'subheading',
		  'content' => __('General Settings', 'instantio'),
	  ),

  array(
    'id'          => 'ins-layout',
    'type'        => 'select',
    'title'       => __('Select Layout', 'instantio'),
    'subtitle'    => __('Choose cart layout', 'instantio'),
    'placeholder' => 'Select Layout',
    'options'     => array(
      'Cart'   => array(
        '1'   => 'Direct Checkout Button',
        '2'   => 'Side Cart',
        '3'   => 'Popup Cart',
      ),
      'Cart + Checkout (Pro)'   => array(
        '4'   => 'Side Cart + Checkout',
        '5'   => 'Popup Cart + Checkout',
      ),
    ),
    'default' => '2'
  ),

  array(
    'id'        => 'ins-toggler',
    'type'      => 'image_select',
    'title'     => __('Toggler Design', 'instantio'), 
    'subtitle' => __('Select toggler design', 'instantio'),
    'options'   => array(
      'tog-1' => INS_ADMIN_URL.'/img/toggler-1.png',
      'tog-2' => INS_ADMIN_URL.'/img/toggler-2.png',
    ),
    'default'   => 'tog-1',
    'dependency' => array('ins-layout', '!=', '1', '', 'visible'),
  ),

  array(
		'id'       => 'cart-button-open',
		'type'     => 'button_set',
		'title'    => __('Cart Toggler Open on', 'instantio'),
		'subtitle' => __('Choose how cart toggler should open for direct checkout button', 'instantio'),
		  'dependency' => array('ins-layout','==','1'),
		'options' => array(
			'click' => 'Click', 
			'mouseover' => 'Hover', 
		), 
		'default' => 'click'
	), 

  array(
    'id'     => 'cart-fly',
    'type'   => 'fieldset',
    'title'  => 'Cart Fly Animation',
    'subtitle' => __('Enable/dsiable cart fly animation or change icon', 'instantio'),
    'fields' => array(
      array(
        'id'       => 'cart-fly-anim',
        'type'     => 'switcher',
        'title'    => __('Cart Fly Animation', 'instantio'), 
        'text_on'    => 'Enabled',
        'text_off'   => 'Disabled',
        'text_width' => 100,
        'default'   => true,
      ),

      array(
        'id'       => 'cart-fly-icon',
        'type'     => 'button_set',
        'title'    => __('Cart Fly Animation Icon', 'instantio'), 
        'options'  => array(
          '1' => 'Toggler Icon',
          '2' => 'Product Thumbnail',
        ),
        'default'  => '2',
        'dependency' => array('cart-fly-anim', '==', true, '', 'visiable'),
      ),
    ),
  ),

  array(
    'id'       => 'auto-tog-panel',
    'type'     => 'switcher',
    'title'    => __('Auto Open Toggle Panel', 'instantio'),
    'subtitle' => __('When an item is added to the cart automatically open the toggle panel', 'instantio'),
    'text_on'    => 'Yes',
    'text_off'   => 'No',
    'default'   => false,
    'dependency' => array('ins-layout', 'any', '2,4', 'all', 'visiable'),
  ),

  array(
    'id'     => 'cart-btn',
    'type'   => 'fieldset',
    'title'  => 'Cart Button',
    'subtitle' => __('Show/hide or change cart button\'s text & url', 'instantio'),
    'dependency' => array('ins-layout','!=', '1', 'all'),
    'fields' => array(
      array(
        'id'       => 'on-cart-btn',
        'type'     => 'switcher',
        'title'    => __('Show Cart Button', 'instantio'),
        'text_on'  => 'Yes',
        'text_off' => 'No',
        'default'   => true,
      ),
      array(
        'id'       => 'cart_button_text',
        'type'     => 'text',
        'title'    => __( 'Cart Button Text', 'instantio' ),
        'desc'   => __( 'Default: <code>View Cart</code>', 'instantio' ),
        'dependency' => array('on-cart-btn','==','true'),				
      ),
      array(
        'id'       => 'cart_button_url',
        'type'     => 'text',
        'title'    => __( 'Cart Button URL', 'instantio' ),
        'placeholder' => 'https://',
        'desc'   => __( 'Default: default cart page', 'instantio' ),
        'validate' => 'csf_validate_url',
        'dependency' => array('on-cart-btn','==','true'),
      ),
    ),
  ),

  array(
    'id'     => 'checkout-btn',
    'type'   => 'fieldset',
    'title'  => 'Checkout Button',
    'subtitle' => __('Show/hide or change checkout button\'s text & url', 'instantio'),
    'fields' => array(
      array(
        'id'       => 'on-checkout-btn',
        'type'     => 'switcher',
        'title'    => __('Show Checkout Button', 'instantio'),
        'text_on'  => 'Yes',
        'text_off' => 'No',
        'default'   => true,
        'dependency' => array('ins-layout','!=','1', 'all', 'visible'),	
      ),

      array(
        'id'       => 'checkout_button_text',
        'type'     => 'text',
        'title'    => __( 'Checkout Button Text', 'instantio' ),
        'desc'   => __( 'Default: <code>Checkout Now</code>', 'instantio' ),
        'dependency' => array('on-checkout-btn','==','true', '', 'visible'),				
      ),

      array(
        'id'       => 'checkout_button_url',
        'type'     => 'text',
        'title'    => __( 'Checkout Button URL', 'instantio' ),
        'placeholder' => 'https://',
        'desc'   => __( 'Default: default checkout page', 'instantio' ),
        'validate' => 'csf_validate_url',
        'dependency' => array('on-checkout-btn','==','true', '', 'visible'),
      ),
    ),
  ),


  array(
      'id'       => 'wi-window-type',
      'type'     => 'button_set',
      'title'    => __('Drawer Window Type', 'instantio'),
      'subtitle' => __( 'Choose the function of Cart Drawer / Side Cart.', 'instantio' ),
      'desc'   => __( 'Multistep: Cart-> Checkout; Single step: Cart+Checkout on same window', 'instantio' ),
      'options' => array(
          '0' => __( 'Multi Step', 'instantio' ),
          '1' => __( 'Single Step', 'instantio' ),
      ),
      'default' => '0',
      'help'     => __( 'Pro Feature', 'instantio' ),
      'dependency' => array( '', '==', '', '', 'visible' ),
  ),

  array(
      'id'       => 'wi-disable-quickview',
      'type'     => 'switcher',
      'title'    => __( 'Disable Quick View', 'instantio' ),
      'subtitle' => __('You can disable it if you already have quick view function in your theme (Applicable for Variable products).', 'instantio'),
      'text_on'  => 'Yes',
      'text_off' => 'No',
      'default'   => false,
      'help'     => __( 'Pro Feature', 'instantio' ),
      'dependency' => array( '', '==', '', '', 'visible' ),
  ),

  array(
      'id'       => 'wi-disable-ajax-add-cart',
      'type'     => 'switcher',
      'title'    => __( 'Disable Ajax Add to Cart', 'instantio' ),
      'subtitle' => __('You can disable it if you already have ajax "add to cart" function in your theme (To avoid conflict).', 'instantio'),
      'text_on'  => 'Yes',
      'text_off' => 'No',
      'default'   => false,
      'help'     => __( 'Pro Feature', 'instantio' ),
      'dependency' => array( '', '==', '', '', 'visible' ),
  ),

  array(
      'id'       => 'wi-active-window',
      'type'     => 'button_set',
      'title'    => __('Choose Active Window', 'instantio'),
      'subtitle' => __( 'Cart or Checkout, which one should be seen when customers open the "Side Cart', 'instantio' ),
      'options' => array(
          '0' => __( 'Cart', 'instantio' ),
          '1' => __( 'Checkout', 'instantio' ),
      ),
      'default' => '0',
      'help'     => __( 'Pro Feature', 'instantio' ),
      'dependency' => array(
        array( 'ins-layout', 'any', '4,5' ),
        array( 'wi-window-type',   '==', '0' ),
      ),
      'dependency' => array( '', '==', '', '', 'visible' ),
  ),

  array(
    'id'       => 'upsell',
    'type'     => 'switcher',
    'title'    => __('Upsells in Cart', 'instantio'),
    'subtitle' => __('Enable/disable upsells items in cart', 'instantio'),
    'text_on'    => 'Enabled',
    'text_off'   => 'Disabled',
    'text_width' => 100,
    'default'   => true,
    'help'  => 'Pro Feature',
    'dependency' => array( '', '==', '', '', 'visible' ),
  ),

  array(
    'id'      => 'upsell-heading',
    'type'    => 'text',
    'title'   => __('Upsell Heading', 'instantio'),
    'subtitle' => __('The text shown before upsell items', 'instantio'),
    'desc'    => __('Default: Hang on! We have this offer just for you!', 'instantio'),
    'placeholder' => __('Hang on! We have this offer just for you!', 'instantio'),
    'help'  => 'Pro Feature',
    'dependency' => array( '', '==', '', '', 'visible' ),
  ),

  array(
    'id'       => 'crosssell',
    'type'     => 'switcher',
    'title'    => __('Cross Sells in Checkout', 'instantio'),
    'subtitle' => __('Enable/disable cross sell items in checkout', 'instantio'),
    'text_on'    => 'Enabled',
    'text_off'   => 'Disabled',
    'text_width' => 100,
    'default'   => true,
    'help'  => 'Pro Feature',
    'dependency' => array( '', '==', '', '', 'visible' ),
  ),

  array(
    'id'      => 'crosssell-heading',
    'type'    => 'text',
    'title'   => __('Cross Sell Heading', 'instantio'),
    'subtitle' => __('The text shown before cross sell items', 'instantio'),
    'desc'    => __('Default: You may be interested in…', 'instantio'),
    'placeholder' => __('You may be interested in…', 'instantio'),
    'help'  => 'Pro Feature',
    'dependency' => array( '', '==', '', '', 'visible' ),
  ),

),
) );

?>