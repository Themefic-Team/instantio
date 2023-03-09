<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

if ( file_exists( TF_OPTIONS_PATH . 'options/tf-menu-icon.php' ) ) {
	require_once TF_OPTIONS_PATH . 'options/tf-menu-icon.php';
} else {
	$menu_icon = 'dashicons-cart';
}

TF_Settings::option( 'wiopt', array(
	'title'    => __( 'Instantio', 'tourfic' ),
	'icon'     => 'dashicons-cart',
	'position' => 25,
	'sections' => array(
		'general'            => array(
			'title'  => esc_html__( 'General', 'tourfic' ),
			'icon'   => 'fa fa-cog',
			'fields' => array(
				array(
					'id'        => 'ins-layout',
					'type'      => 'select',
					'label'     => 'Select Layout',
					'subtitle'  => 'Choose cart layout',
					'class'     => 'tf-field-class',
					'options'   => array(
						'1' => 'Direct Checkout Button',
						'2' => 'Side Cart',
						'3' => 'Popup Cart',
						'4' => 'Side Cart + Checkout Design 1',
						'5' => 'Popup Cart + Checkout Design 1',
						'6' => 'Side Cart + Checkout Design 1 V2',
						'7' => 'Popup Cart + Checkout Design 1 V2',
					),
					'default'   => '2',
				),
				array(
					'id'        => 'ins-toggler',
					'type'      => 'imageselect',
					'label'     => __('Toggler Design', 'instantio'), 
					'subtitle' => __('Select toggler design', 'instantio'),
					'multiple' => true,
					'inline'   => true,
					'options'   => array(
					'tog-1' => INS_MODERN_URL.'/admin/tf-options/img/toggler-1.png',
					'tog-2' => INS_MODERN_URL.'/admin/tf-options/img/toggler-2.png',
					),
					'default'   => 'tog-1',
					'dependency' => array('ins-layout',  '!=', '1' ),
				),

				array(
					'id'       => 'auto-tog-panel',
					'type'     => 'switch',
					'label'    => __('Auto Open Toggle Panel', 'instantio'), 
					'label_on'    => __('Enabled', 'instantio'),
					'label_off'   => __('Disabled', 'instantio'),
					'width' => 100,
					'default'   => false,
				),
				
				array(
					'id'     => 'cart-fly',
					'type'   => 'fieldset',
					'label'  => __('Cart Fly Animation', 'instantio'),
					'subtitle' => __('Enable/dsiable cart fly animation or change icon', 'instantio'),
					'fields' => array(
						array(
						  'id'       => 'cart-fly-anim',
						  'type'     => 'switch',
						  'label'    => __('Cart Fly Animation', 'instantio'), 
						  'label_on'    => __('Enabled', 'instantio'),
						  'label_off'   => __('Disabled', 'instantio'),
						  'width' => 100,
						  'default'   => false,
						),
						array(
						  'id'       => 'cart-fly-icon',
						  'type'     => 'switch',
						  'label'    => __('Cart Fly Animation Icon', 'instantio'),
						  'label_on'    => __('Icon', 'instantio'),
						  'label_off'   => __('Thum', 'instantio'),
						  'width' => 100,
						  'default'   => true,
						  'dependency' => array('cart-fly-anim', '==', 1),
						),
					  ),
				),
				array(
					'id'     => 'cart-btn',
					'type'   => 'fieldset',
					'label'  => __('Cart Button', 'instantio'),
					'subtitle' => __('Show/hide or change cart button\'s text & url', 'instantio'),
					'dependency' => array('ins-layout','!=', '1'),
					'fields' => array(
						array(
							'id'       		=> 'on-cart-btn',
							'type'     		=> 'switch',
							'label'    		=> __('Show Cart Button', 'instantio'),
							'label_on'  		=> __('Yes', 'instantio'),
							'label_off' 		=> __('No', 'instantio'),
							'default'   	=> true,
						  ),
						  array(
							'id'        	=> 'cart_button_text',
							'type'      	=> 'text',
							'label'     	=> __( 'Cart Button Text', 'instantio' ),
							'placeholder'  => 'View Cart',
							'description'   => __( 'Default: <code>View Cart</code>', 'instantio' ),
							'default'  	=> 'View Cart',
							'dependency' 	=> array('on-cart-btn','==','true'),				
						  ),
						  array(
							'id'       		=> 'cart_button_url',
							'type'     		=> 'text',
							'label'    		=> __( 'Cart Button URL', 'instantio' ),
							'placeholder' 	=> 'https://',
							'description'   => __( 'Default: Default cart page', 'instantio' ),
							'validate' 		=> 'tf_validate_url',
							'dependency' 	=> array('on-cart-btn','==','true'),
						  ),
					  ),
				),
				array(
					'id'            => 'checkout-btn',
					'type'   		=> 'fieldset',
					'label'  		=> __('Checkout Button', 'instantio'),
					'subtitle' 		=> __('Show/hide or change checkout button\'s text & url', 'instantio'),
					'dependency'	=> array('ins-layout','!=', '1'),
					'fields' 		=> array(
						array(
							'id'        	=> 'on-checkout-btn',
							'type'      	=> 'switch',
							'label'     	=> __('Show Checkout Button', 'instantio'),
							'label_on'   	=> __('Yes', 'instantio'),
							'label_off'  	=> __('No', 'instantio'),
							'default'   	=> true,
						  ),
						  array(
							'id'        	=> 'checkout_button_text',
							'type'      	=> 'text',
							'default'   	=> 'Checkout',
							'placeholder' 	=> 'Checkout',
							'label'     	=> __( 'Checkout Button Text', 'instantio' ),
							'description'   => __( 'Default: <code>Checkout</code>', 'instantio' ),
							'dependency' 	=> array('on-checkout-btn','==','true'),				
						  ),
						  array(
							'id'       		=> 'checkout_button_url',
							'type'     		=> 'text',
							'label'    		=> __( 'Checkout Button URL', 'instantio' ),
							'placeholder' 	=> 'https://',
							'description'   => __( 'Default: Default Checkout page', 'instantio' ),
							'validate' 		=> 'csf_validate_url',
							'dependency' 	=> array('on-checkout-btn','==','true'),
						  ),
					  ),
				),

				array(
					'id'        => 'woins-quickview-enable',
					'class'     => 'ins-csf-disable badge_pro',
					'type'      => 'switch',
					'label'     => __( 'Enable Quick View', 'instantio' ),
					'subtitle'  => __('You can disable it if you already have quick view function in your theme (Applicable for Variable products)', 'instantio'),
					'is_pro'    => true,
					'label_on'  => __('Yes', 'instantio'),
					'label_off' => __('No', 'instantio'),
					'default'   => false,
				),
			  
				array(
					'id'        => 'wi-disable-ajax-add-cart',
					'class'     => 'ins-csf-disable badge_pro',
					'type'      => 'switch',
					'label'     => __( 'Enable Ajax Add to Cart', 'instantio' ),
					'subtitle'  => __('You can disable it if you already have ajax "add to cart" function in your theme (To avoid conflict)', 'instantio'),
					'is_pro'    => true,
					'label_on'  => __('Yes', 'instantio'),
					'label_off' => __('No', 'instantio'),
					'default'   => false,
				),

				array(
					'id'       => 'ins-upsell',
					'class' => 'ins-csf-disable ins-csf-pro',
					'type'     => 'switch',
					'label'    => __('Show Upsells Product in Cart', 'instantio'),
					'subtitle' => __('Enable/disable upsells items in cart', 'instantio'),
					'label_on'    => __('Enabled', 'instantio'),
					'label_off'   => __('Disabled', 'instantio'),
					'width' => 100,
					'is_pro'    => true,
					'default'   => false,
				),
				
				array(
					'id'      => 'upsell-heading',
					'class' => 'ins-csf-disable ins-csf-pro',
					'type'    => 'text',
					'label'   => __('Upsell Heading', 'instantio'),
					'subtitle' => __('The text shown before upsell items', 'instantio'),
					'desc'    => __('Default: Hang on! We have this offer just for you!', 'instantio'),
					'placeholder' => __('Hang on! We have this offer just for you!', 'instantio'),
					'is_pro'    => true,
				),
				
				array(
					'id'       => 'crosssell',
					'class' => 'ins-csf-disable ins-csf-pro',
					'type'     => 'switch',
					'label'    => __('Cross Sells in Checkout', 'instantio'),
					'subtitle' => __('Enable/disable cross sell items in checkout', 'instantio'),
					'label_on'    => __('Enabled', 'instantio'),
					'label_off'   => __('Disabled', 'instantio'),
					'width' => 100,
					'default'   => false,
					'is_pro'    => true,
				),
				
				array(
					'id'      => 'crosssell-heading',
					'class' => 'ins-csf-disable ins-csf-pro',
					'type'    => 'text',
					'label'   => __('Cross Sell Heading', 'instantio'),
					'subtitle' => __('The text shown before cross sell items', 'instantio'),
					'desc'    => __('Default: You may be interested in…', 'instantio'),
					'placeholder' => __('You may be interested in…', 'instantio'),
					'default' => 'Enter your default value',
					'is_pro'    => true,
				),
			),
		),

		'design_option'       => array(
			'title'  => esc_html__( 'Design', 'instantio' ),
			'icon'   => 'fas fa-palette',
			'fields' => array(),
		),
		'toggle_page'        => array(
			'title'  => esc_html__( 'Toggle Design', 'instantio' ),
			'parent' => 'design_option',
			'icon'   => 'fa fa-cogs',
			'fields' => array(
				array(
					'id'        => 'label_off_heading',
					'type'      => 'heading',
					'label'     => __( 'Global Settings for Instantio Toggle Cart icon', 'instantio' ),
					'sub_title' => __( 'These options can be overridden from defualt Settings.', 'instantio' ),
				),

				array(
					'id'        => 'ins-cart-emty-hide',
					'type'      => 'switch',
					'label'     => __( 'Hide Toggler when No Cart Item', 'instantio' ),
					'label_on'  => __( 'Yes', 'instantio' ),
					'label_off' => __( 'No', 'instantio' ),
					'default'   => false
				),

				array(
					'id'       => 'cart-icon',
					'class'    => 'imageset-inline',
					'type'     => 'imageselect',
					'label'    => __('Toggler Icon', 'instantio'), 
					'subtitle' => __('Select cart icon which will appear in cart toggler', 'instantio'),
					'options'  => array(
					  'cart-1' => INS_MODERN_URL.'/admin/tf-options/img/cart-1.svg',
					  'cart-2' => INS_MODERN_URL.'/admin/tf-options/img/cart-2.svg',
					  'cart-3' => INS_MODERN_URL.'/admin/tf-options/img/cart-3.svg',
					  'cart-4' => INS_MODERN_URL.'/admin/tf-options/img/cart-4.svg',
					  'cart-5' => INS_MODERN_URL.'/admin/tf-options/img/cart-5.svg',
					  'cart-6' => INS_MODERN_URL.'/admin/tf-options/img/cart-6.svg',
					),
					'default' => 'cart-1'
				),

				array(
					'id'       => 'wi-icon-choice',
					'class'    => 'ins-csf-disable',
					'type'     => 'switch', 
					'label'    => __('Custom Image as Toggler Icon', 'instantio'),
					'subtitle' => __('Set custom image as icon for the toggler instead of the defaults one.','instantio'),
					'label_on'  => __('Yes', 'instantio'),
					'label_off' => __('No', 'instantio'),
					'default'  => false,
					'is_pro'    => true,
				),

				array(
					'id'       => 'toggle-position-horizontal',
					'type'     => 'radio',
					'label'    => __('Toggler Horizontal Position', 'instantio'),
					'subtitle' => __('Changes position of the Cart Toggler horizontally', 'instantio'),
					'options'  => array(
						'left'   => __('Left', 'instantio'),
						'right'  => __('Right', 'instantio'),
					), 
					'default'  => 'right',
					'inline'   => true,
				),

				array(
					'id'       => 'toggle-position-vertical',
					'type'     => 'radio',
					'label'    => __('Toggler Vertical Position', 'instantio'),
					'subtitle' => __('Changes position of the Cart Toggler vertically', 'instantio'),
					'options' => array(
						'top' => __('Top', 'instantio'),
						'middle' => __('Middle', 'instantio'),
						'bottom' => __('Bottom', 'instantio'),				
					), 
					'default' => 'bottom',
					'inline'   => true,
					// 'dependency' => [    
					// 	array( 'ins-layout',  '!=', '1' ),   
					// 	array( 'ins-toggler', '!=', 'tog-2' ),    
					// ],			
				),

				array(
					'id'        => 'wi-header-bg-colors',
					'type'      => 'color',
					'label'     => __( 'Toggler Background Colors', 'instantio' ),
					'subtitle'  => __( 'Set regular & hover color', 'instantio' ),
					'default'   => '#ffffff',
					'multiple'  => true,
					'inline'    => true,
					'colors' => array(
						'regular' => __('Regular', 'instantio'),
						'hover' => __('Hover', 'instantio'),
					 ),
				),

				array(
					'id'        => 'wi-header-border-colors',
					'type'      => 'color',
					'label'    => __( 'Toggler Border Colors', 'instantio' ),
					'subtitle' => __( 'Set regular & hover color', 'instantio' ),
					'default'   => '#ffffff',
					'multiple'  => true,
					'inline'    => true,
					'colors'   => array(
					  'regular' => __('Regular', 'instantio'),
					  'hover' => __('Hover', 'instantio'),
					)
				),

				array(
					'id'        => 'ins-tog-icon-colors',
					'type'      => 'color',
					'label'    => __( 'Toggler Icon Color', 'instantio' ),
					'subtitle' => __( 'Set regular & hover color of text & icon', 'instantio' ),
					'default'   => '#ffffff',
					'multiple'  => true,
					'inline'    => true,
					'colors'   => array(
					  'regular' => __('Regular', 'instantio'),
					  'hover' => __('Hover', 'instantio'),
					)
				),

				array(
					'id'     		=> 'wi-header-icon-size',
					'type'   		=> 'number',
					'label'  		=> __('Toggler Icon Size', 'instantio'),
					'subtitle' 		=> __('Set width of the toggler icon', 'instantio'),
					'placeholder'   => __('Default: 26', 'instantio'),
					'description'   => __('Default: 26 px', 'instantio'),
				),

				
			),
		),
		'toggle_panel'        => array(
			'title'  => esc_html__( 'Toggle Panel Design', 'instantio' ),
			'parent' => 'design_option',
			'icon'   => 'fa fa-cog',
			'fields' => array(
				array(
					'id'    => 'hotel_room_heading',
					'type'  => 'heading',
					'label' => __( 'Global Configuration for Hotel Rooms', 'instantio' ),
				),

				array(
					'id'       => '',
					'type'     => 'switch',
					'label'    => __( 'Children Age Limit', 'instantio' ),
					'subtitle' => __( 'Turn on this option to set the Maximum age limit for Children. This can be overridden from Single Hotel Settings.', 'instantio' ),
					'is_pro'   => true,
				),
				
				
			),
		),
		'other_design' => array(
			'title'  => esc_html__( 'Toggle Design', 'instantio' ),
			'parent' => 'design_option',
			'icon'   => 'fa fa-cog',
			'fields' => array(),
		),
	 

		/**
		 * Import/Export
		 *
		 * Main menu
		 */
		'import_export' => array(
			'title' => __( 'Import/Export', 'instantio' ),
			'icon' => 'fas fa-hdd',
			'fields' => array(
				array(
					'id' => 'backup',
					'type' => 'backup',
				),  

			),
		),
	),
) );
