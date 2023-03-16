<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

if ( file_exists( TF_OPTIONS_PATH . 'options/tf-menu-icon.php' ) ) {
	require_once TF_OPTIONS_PATH . 'options/tf-menu-icon.php';
} else {
	$menu_icon = 'dashicons-cart';
}

TF_Settings::option( 'wiopt', array(
	'title'    			=> __( 'Instantio', 'instantio' ),
	'icon'     			=> 'dashicons-cart',
	'position' 			=> 25,
	'sections' 			=> array(
		'general'            		=> array(
			'title'  				=> esc_html__( 'General', 'tourfic' ),
			'icon'   				=> 'fa fa-cog',
			'fields' 				=> array(
				array(
					'id'        	=> 'ins-layout',
					'type'      	=> 'select',
					'label'     	=> 'Select Layout',
					'subtitle'  	=> 'Choose cart layout',
					'class'     	=> 'tf-field-class',
					'options'   	=> array(
						'1' 		=> 'Direct Checkout Button',
						'2' 		=> 'Side Cart',
						'3' 		=> 'Popup Cart',
						'4' 		=> 'Side Cart + Checkout Design 1',
						'5' 		=> 'Popup Cart + Checkout Design 1',
						'6' 		=> 'Side Cart + Checkout Design 1 V2',
						'7' 		=> 'Popup Cart + Checkout Design 1 V2',
					),
					'default'   	=> '2',
				),
				array(
					'id'        	=> 'ins-toggler',
					'type'      	=> 'imageselect',
					'label'     	=> __('Toggler Design', 'instantio'), 
					'subtitle' 		=> __('Select toggler design', 'instantio'),
					'multiple' 		=> true,
					'inline'   		=> true,
					'options'   	=> array(
						'tog-1' 	=> plugin_dir_url( __FILE__ ).'../img/toggler-1.png',
						'tog-2' 	=> plugin_dir_url( __FILE__ ).'../img/toggler-2.png',
					),
					'default'   	=> 'tog-1',
					'dependency' 	=> array('ins-layout',  '!=', '1' ),
				),

				array(
					'id'       		=> 'auto-tog-panel',
					'type'     		=> 'switch',
					'label'    		=> __('Auto Open Toggle Panel', 'instantio'), 
					'label_on'    	=> __('Enabled', 'instantio'),
					'label_off'   	=> __('Disabled', 'instantio'),
					'width' 		=> 100,
					'default'   	=> false,
				),

				array(
					'id'       		=> 'bottom-cart-panel',
					'type'     		=> 'switch',
					'label'    		=> __('Bottom Full With Cart Panel', 'instantio'),
					'description'   => __( 'If auto cart open on then it would work', 'instantio' ), 
					'label_on'    	=> __('Enabled', 'instantio'),
					'label_off'   	=> __('Disabled', 'instantio'),
					'width' 		=> 100,
					'default'   	=> false,
					'dependency' 	=> array('auto-tog-panel',  '==', 'false' ),
				),
				
				array(
					'id'     		=> 'cart-fly',
					'type'   		=> 'fieldset',
					'label'  		=> __('Cart Fly Animation', 'instantio'),
					'subtitle' 		=> __('Enable/dsiable cart fly animation or change icon', 'instantio'),
					'fields' 		=> array(
						array(
						  'id'       		=> 'cart-fly-anim',
						  'type'     		=> 'switch',
						  'label'    		=> __('Cart Fly Animation', 'instantio'), 
						  'label_on'    	=> __('Enabled', 'instantio'),
						  'label_off'   	=> __('Disabled', 'instantio'),
						  'width' 			=> 100,
						  'default'   		=> false,
						),
						array(
						  'id'       		=> 'cart-fly-icon',
						  'type'     		=> 'switch',
						  'label'    		=> __('Cart Fly Animation Icon', 'instantio'),
						  'label_on'    	=> __('Icon', 'instantio'),
						  'label_off'   	=> __('Thum', 'instantio'),
						  'width' 			=> 100,
						  'default'   		=> true,
						  'dependency' 		=> array('cart-fly-anim', '==', 1),
						),
					),
				),
				array(
					'id'     		=> 'cart-btn',
					'type'   		=> 'fieldset',
					'label'  		=> __('Cart Button', 'instantio'),
					'subtitle' 		=> __('Show/hide or change cart button\'s text & url', 'instantio'),
					'dependency' 	=> array('ins-layout','!=', '1'),
					'fields' 		=> array(
						array(
							'id'       		=> 'on-cart-btn',
							'type'     		=> 'switch',
							'label'    		=> __('Show Cart Button', 'instantio'),
							'label_on'  	=> __('Yes', 'instantio'),
							'label_off' 	=> __('No', 'instantio'),
							'default'   	=> true,
						),
						array(
							'id'        	=> 'cart_button_text',
							'type'      	=> 'text',
							'label'     	=> __( 'Cart Button Text', 'instantio' ),
							'placeholder'  	=> 'View Cart',
							'description'   => __( 'Default: <code>View Cart</code>', 'instantio' ),
							'default'  		=> 'View Cart',
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
					'id'        	=> 'woins-quickview-enable',
					'class'     	=> 'ins-csf-disable badge_pro',
					'type'      	=> 'switch',
					'label'     	=> __( 'Enable Quick View', 'instantio' ),
					'subtitle'  	=> __('You can disable it if you already have quick view function in your theme (Applicable for Variable products)', 'instantio'),
					'is_pro'    	=> true,
					'label_on'  	=> __('Yes', 'instantio'),
					'label_off' 	=> __('No', 'instantio'),
					'default'   	=> false,
				),
			  
				array(
					'id'        	=> 'wi-disable-ajax-add-cart',
					'class'     	=> 'ins-csf-disable badge_pro',
					'type'      	=> 'switch',
					'label'     	=> __( 'Enable Ajax Add to Cart', 'instantio' ),
					'subtitle'  	=> __('You can disable it if you already have ajax "add to cart" function in your theme (To avoid conflict)', 'instantio'),
					'is_pro'    	=> true,
					'label_on'  	=> __('Yes', 'instantio'),
					'label_off' 	=> __('No', 'instantio'),
					'default'   	=> false,
				),

				array(
					'id'       		=> 'ins-upsell',
					'class' 		=> 'ins-csf-disable ins-csf-pro',
					'type'     		=> 'switch',
					'label'    		=> __('Show Upsells Product in Cart', 'instantio'),
					'subtitle' 		=> __('Enable/disable upsells items in cart', 'instantio'),
					'label_on'    	=> __('Enabled', 'instantio'),
					'label_off'   	=> __('Disabled', 'instantio'),
					'width' 		=> 100,
					'is_pro'    	=> true,
					'default'   	=> false,
				),
				
				array(
					'id'      		=> 'upsell-heading',
					'class' 		=> 'ins-csf-disable ins-csf-pro',
					'type'    		=> 'text',
					'label'   		=> __('Upsell Heading', 'instantio'),
					'subtitle' 		=> __('The text shown before upsell items', 'instantio'),
					'desc'    		=> __('Default: Hang on! We have this offer just for you!', 'instantio'),
					'placeholder' 	=> __('Hang on! We have this offer just for you!', 'instantio'),
					'is_pro'    	=> true,
				),
				
				array(
					'id'       		=> 'crosssell',
					'class' 		=> 'ins-csf-disable ins-csf-pro',
					'type'     		=> 'switch',
					'label'    		=> __('Cross Sells in Checkout', 'instantio'),
					'subtitle' 		=> __('Enable/disable cross sell items in checkout', 'instantio'),
					'label_on'    	=> __('Enabled', 'instantio'),
					'label_off'   	=> __('Disabled', 'instantio'),
					'width' 		=> 100,
					'default'   	=> false,
					'is_pro'    	=> true,
				),
				
				array(
					'id'      		=> 'crosssell-heading',
					'class' 		=> 'ins-csf-disable ins-csf-pro',
					'type'    		=> 'text',
					'label'   		=> __('Cross Sell Heading', 'instantio'),
					'subtitle' 		=> __('The text shown before cross sell items', 'instantio'),
					'desc'    		=> __('Default: You may be interested in…', 'instantio'),
					'placeholder' 	=> __('You may be interested in…', 'instantio'),
					'default' 		=> 'Enter your default value',
					'is_pro'    	=> true,
				),
			),
		),

		'design_option'       		=> array(
			'title'  				=> esc_html__( 'Design', 'instantio' ),
			'icon'  				=> 'fas fa-palette',
			'fields' 				=> array(),
		),

		'toggle_page'        		=> array(
			'title'  				=> esc_html__( 'Toggle Design', 'instantio' ),
			'parent' 				=> 'design_option',
			'icon'   				=> 'fa fa-cogs',
			'fields' 				=> array(
				array(
					'id'        	=> 'label_off_heading',
					'type'      	=> 'heading',
					'label'     	=> __( 'Global Settings for Instantio Toggle Cart icon', 'instantio' ),
					'sub_title' 	=> __( 'These options can be overridden from defualt Settings.', 'instantio' ),
				),

				array(
					'id'        	=> 'ins-cart-emty-hide',
					'type'      	=> 'switch',
					'label'     	=> __( 'Hide Toggler when No Cart Item', 'instantio' ),
					'label_on'  	=> __( 'Yes', 'instantio' ),
					'label_off' 	=> __( 'No', 'instantio' ),
					'default'   	=> false
				),

				array(
					'id'       		=> 'cart-icon',
					'class'    		=> 'imageset-inline',
					'type'     		=> 'imageselect',
					'label'    		=> __('Toggler Icon', 'instantio'), 
					'subtitle' 		=> __('Select cart icon which will appear in cart toggler', 'instantio'),
					'options'  		=> array(
						'cart-1' 			=> plugin_dir_url( __FILE__ ).'../img/cart-1.svg',
						'cart-2' 			=> plugin_dir_url( __FILE__ ).'../img/cart-2.svg',
						'cart-3' 			=> plugin_dir_url( __FILE__ ).'../img/cart-3.svg',
						'cart-4' 			=> plugin_dir_url( __FILE__ ).'../img/cart-4.svg',
						'cart-5' 			=> plugin_dir_url( __FILE__ ).'../img/cart-5.svg',
						'cart-6' 			=> plugin_dir_url( __FILE__ ).'../img/cart-6.svg',
					),
					'default' 		=> 'cart-1'
				),

				array(
					'id'       		=> 'wi-icon-choice',
					'class'    		=> 'ins-csf-disable',
					'type'     		=> 'switch', 
					'label'    		=> __('Custom Image as Toggler Icon', 'instantio'),
					'subtitle' 		=> __('Set custom image as icon for the toggler instead of the defaults one.','instantio'),
					'label_on'  	=> __('Yes', 'instantio'),
					'label_off' 	=> __('No', 'instantio'),
					'default'  		=> false,
					'is_pro'    	=> true,
				),

				array(
					'id'       		=> 'toggle-position-horizontal',
					'type'     		=> 'radio',
					'label'    		=> __('Toggler Horizontal Position', 'instantio'),
					'subtitle' 		=> __('Changes position of the Cart Toggler horizontally', 'instantio'),
					'default'  		=> 'right',
					'inline'   		=> true,
					'options'  		=> array(
						'left'   			=> __('Left', 'instantio'),
						'right'  			=> __('Right', 'instantio'),
					), 
				),

				array(
					'id'       		=> 'toggle-position-vertical',
					'type'     		=> 'radio',
					'label'    		=> __('Toggler Vertical Position', 'instantio'),
					'subtitle' 		=> __('Changes position of the Cart Toggler vertically', 'instantio'),
					'options' 		=> array(
						'top' 				=> __('Top', 'instantio'),
						'middle' 			=> __('Middle', 'instantio'),
						'bottom' 			=> __('Bottom', 'instantio'),				
					), 
					'default' 		=> 'bottom',
					'inline'   		=> true,
					// 'dependency' => [    
					// 	array( 'ins-layout',  '!=', '1' ),   
					// 	array( 'ins-toggler', '!=', 'tog-2' ),    
					// ],			
				),

				array(
					'id'        	=> 'wi-header-bg-colors',
					'type'      	=> 'color',
					'label'     	=> __( 'Toggler Background Colors', 'instantio' ),
					'subtitle'  	=> __( 'Set regular & hover color', 'instantio' ),
					'default'   	=> '#ffffff',
					'multiple'  	=> true,
					'inline'    	=> true,
					'colors' 		=> array(
						'regular' 			=> __('Regular', 'instantio'),
						'hover' 			=> __('Hover', 'instantio'),
					 ),
				),

				array(
					'id'        	=> 'wi-header-border-colors',
					'type'      	=> 'color',
					'label'    		=> __( 'Toggler Border Colors', 'instantio' ),
					'subtitle' 		=> __( 'Set regular & hover color', 'instantio' ),
					'default'  	 	=> '#ffffff',
					'multiple'  	=> true,
					'inline'    	=> true,
					'colors'   		=> array(
						'regular' 			=> __('Regular', 'instantio'),
						'hover' 			=> __('Hover', 'instantio'),
					)
				),

				array(
					'id'        	=> 'ins-tog-icon-colors',
					'type'      	=> 'color',
					'label'    		=> __( 'Toggler Icon Color', 'instantio' ),
					'subtitle' 		=> __( 'Set regular & hover color of text & icon', 'instantio' ),
					'default'   	=> '#ffffff',
					'multiple'  	=> true,
					'inline'    	=> true,
					'colors'   		=> array(
						'regular' 			=> __('Regular', 'instantio'),
						'hover' 			=> __('Hover', 'instantio'),
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

				array(
					'id'        	=> 'ins-tog-item-bg',
					'type'      	=> 'color',
					'label'    		=> __( 'Toggler Item Number Background', 'instantio' ),
					'subtitle' 		=> __( 'Set regular & hover background color', 'instantio' ),
					'multiple'  	=> true,
					'inline'    	=> true,
					'colors'   		=> array(
						'regular' 			=> __('Regular', 'instantio'),
						'hover' 			=> __('Hover', 'instantio'),
					)
				),

				array(
					'id'        	=> 'wi-header-text-colors',
					'type'      	=> 'color',
					'label'    		=> __( 'Toggler Item Number Color', 'instantio' ),
					'subtitle' 		=> __( 'Set regular & hover color of text & icon', 'instantio' ),
					'multiple'  	=> true,
					'inline'    	=> true,
					'colors'   		=> array(
						'regular' 			=> __('Regular', 'instantio'),
						'hover' 			=> __('Hover', 'instantio'),
					)
				),

				array(
					'id'    		=> 'wi-header-text-size',
					'type'  		=> 'number',
					'label'  		=> __('Toggler Item Number Size', 'instantio'),
					'subtitle' 		=> __('Set font size & line height of cart toggler text', 'instantio'),
					'description'   => __('Default: 14px', 'instantio'),
				),	
			),
		),

		'toggle_panel'        		=> array(
			'title'  				=> esc_html__( 'Toggle Panel Design', 'instantio' ),
			'parent' 				=> 'design_option',
			'icon'   				=> 'fa fa-cog',
			'fields' 				=> array(
				array(
					'id'    		=> 'toggle_panel_heading',
					'type' 			=> 'heading',
					'label' 		=> __( 'Toggle Panel Design', 'instantio' ),
				),
				array(
					'id'       		=> 'toggle-panel-position',
					'type'     		=> 'radio',
					'label'    		=> __('Toggle Panel Position', 'instantio'),
					'subtitle' 		=> __('Changes position of the Cart Toggle Panel', 'instantio'),
					'options'  		=> array(
						'left'   			=> __('Left', 'instantio'),
						'right'  			=> __('Right', 'instantio'),
					), 
					'default'  		=> 'right',
					'inline'   		=> true,
				),
				array(
					'id'        	=> 'wi-inner-bg-colors',
					'type'      	=> 'color',
					'label'    		=> __( 'Toggle Panel Background Colors', 'instantio' ),
					'subtitle' 		=> __( 'Checkout button background regular & hover color', 'instantio' ),
					'multiple'  	=> true,
					'inline'    	=> true,
					'colors'   		=> array(
						'regular' 			=> __('Regular', 'instantio'),
						'hover' 			=> __('Hover', 'instantio'),
					),
				),

				array(
					'id'        	=> 'wi-container-bg',
					'type'      	=> 'color',
					'class'      	=> 'tf-field-color-single',
					'label'    		=> __( 'Panel Background', 'instantio' ),
					'subtitle' 		=> __( 'Toggle Panel Background Color', 'instantio' ),
				),

				array(
					'id'       		=> 'ins_panel_border_option',
					'type'     		=> 'switch', 
					'label'    		=> __('Enable Toggle Panel Border', 'instantio'),
					'subtitle' 		=> __('Set Toggle Panel Border','instantio'),
					'label_on'  	=> __('Enable', 'instantio'),
					'label_off' 	=> __('Disable', 'instantio'),
					'width'   		=> 100,
					'default'  		=> false,
				),

				array(
					'id'            => 'ins_panel_border',
					'type'   		=> 'fieldset',
					'label'  		=> __('Toggle Panel Border Design', 'instantio'),
					'subtitle' 		=> __('Toggle Panel Border Design', 'instantio'),
					'dependency' 	=> array('ins_panel_border_option','==','true'),
					'fields' 		=> array(
						array(
							'id'        	=> 'ins_panel_border_color',
							'type'      	=> 'color',
							'class'      	=> 'tf-field-color-single',
							'label'    		=> __( 'Border Color', 'instantio' ),
							'subtitle' 		=> __( 'Toggle Panel Border Color', 'instantio' ),
						),
						array(
							'id' 			=> 'ins-panel-border-top',
							'class' 		=> 'tf-field-inline',
							'type' 			=> 'number',
							'label' 		=> 'Border Top',
							'subtitle' 		=> 'Border Top ',
							'placeholder' 	=> '1px',
							'default' 		=> '1px',
						),
						array(
							'id' 			=> 'ins-panel-border-right',
							'class' 		=> 'tf-field-inline',
							'type' 			=> 'number',
							'label' 		=> 'Border Right',
							'subtitle' 		=> 'Border Right ',
							'placeholder' 	=> '1px',
							'default' 		=> '1px',
						),
						array(
							'id' 			=> 'ins-panel-border-bottom',
							'class' 		=> 'tf-field-inline',
							'type' 			=> 'number',
							'label' 		=> 'Border Bottom',
							'subtitle' 		=> 'Border Bottom ',
							'placeholder' 	=> '1px',
							'default' 		=> '1px',
						),
						array(
							'id' 			=> 'ins-panel-border-left',
							'class' 		=> 'tf-field-inline',
							'type' 			=> 'number',
							'label' 		=> 'Border Left',
							'subtitle' 		=> 'Border Left ',
							'placeholder' 	=> '1px',
							'default' 		=> '1px',
						),
					),
				),
				array(
					'id'        	=> 'ins-panel-text-color',
					'type'      	=> 'color',
					'class'      	=> 'tf-field-color-single',
					'label'    		=> __( 'Panel Text Color', 'instantio' ),
					'subtitle' 		=> __( 'Openning heading "Your Cart" color', 'instantio' )
				),

				array(
					'id'        	=> 'ins-panel-button-bg',
					'type'      	=> 'color',
					'label'    		=> __( 'Panel Button Colors', 'instantio' ),
					'subtitle' 		=> __( '"Proceed to Checkout", "Back to Cart" button color', 'instantio' ),
					'multiple'  	=> true,
					'inline'    	=> true,
					'colors'   		=> array(
						'regular' 			=> __('Regular', 'instantio' ),
						'hover' 			=> __('Hover', 'instantio' ),
					)
				),

				array(
					'id'        	=> 'ins-panel-button-border',
					'type'     	 	=> 'color',
					'label'    		=> __( 'Panel Button Border Colors', 'instantio' ),
					'subtitle' 		=> __( '"Proceed to Checkout", "Back to Cart" button border color', 'instantio' ),
					'multiple'  	=> true,
					'inline'    	=> true,
					'colors'   		=> array(
						'regular' 			=> __('Regular', 'instantio' ),
						'hover' 			=> __('Hover', 'instantio' ),
					)
				),

				array(
					'id'        	=> 'ins-panel-button-text',
					'type'      	=> 'color',
					'label'    		=> __( 'Panel Button Text Colors', 'instantio' ),
					'subtitle' 		=> __( '"Proceed to Checkout", "Back to Cart" button text color', 'instantio' ),
					'multiple'  	=> true,
					'inline'    	=> true,
					'colors'   		=> array(
						'regular' 			=> __('Regular', 'instantio' ),
						'hover' 			=> __('Hover', 'instantio' ),
					)
				),

				array(
					'id'    		=> 'wi-zindex',
					'type'  		=> 'number',
					'label'    		=> __( 'Panel Z-index', 'instantio' ),
					'subtitle' 		=> __( 'Control z-index from this option. More about <a target="_blank" href="https://css-tricks.com/almanac/properties/z/z-index/">z-index</a>', 'instantio' ),
					'default' 		=> 99999,
					'attributes'	=> array(
						'min' 				=> 999,
						'max' 				=> 9999999,
					),
				),

				array(
					'id'        	=> 'panel-width-1200',
					'type'      	=> 'number',
					'label'     	=> __('Toggle Panel Width (1200px-auto)', 'instantio'),
					'subtitle'  	=> __('Set the percent of width of toggle panel for display dimension greater than 1199px.', 'instantio'),
					'description'  	=> __('Range 0%-100%. Default 40', 'instantio'),
					"default"   	=> 35,
					'attributes'	=> array(
						"min"       		=> 1,
						"max"       		=> 100,
					),
				),

				array(
					'id'        	=> 'panel-width-1024',
					'type'     	 	=> 'number',
					'label'     	=> __('Toggle Panel Width (1024px-1199px)', 'instantio'),
					'subtitle'  	=> __('Set the percent of width of toggle panel for display dimension greater than 1023px.', 'instantio'),
					'description'  	=> __('Range 0%-100%. Default 48', 'instantio'),
					"default"   	=> 48,
					'attributes'	=> array(
						"min"       		=> 1,
						"max"       		=> 100,
					),
				),

				array(
					'id'        	=> 'panel-width-767',
					'type'      	=> 'number',
					'label'     	=> __('Toggle Panel Width (501px-1023)', 'instantio'),
					'subtitle'  	=> __('Set the percent of width of toggle panel for display dimension greater than 500px.', 'instantio'),
					'description'  	=> __('Range 0%-100%. Default 60 <br/> <br/> Width is 100% for devices which dimension up to 500px.', 'instantio'),
					"default"   	=> 60,
					'attributes'	=> array(
						"min"       		=> 1,
						"max"       		=> 100,
					),
				),

				array(
					'id'    		=> 'cart_section',
					'type'    		=> 'heading',
					'content' 		=> __('Cart Section', 'instantio'),
				),

				array(
					'id'       		=> 'cart-header-bg',
					'type'     		=> 'color',
					'class'      	=> 'tf-field-color-single',
					'label'    		=> __( 'Cart Header Background', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel cart header background color', 'instantio' ),
				),

				array(
					'id'       		=> 'cart-header-text',
					'type'     		=> 'color',
					'class'      	=> 'tf-field-color-single',
					'label'    		=> __( 'Cart Header Text Color', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel cart header text color', 'instantio' ),
				),

				array(
					'id'       		=> 'cart-item-bg',
					'type'    		=> 'color',
					'class'      	=> 'tf-field-color-single',
					'label'    		=> __( 'Cart Items Background', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel cart items background color', 'instantio' ),
				),

				array(
					'id'       		=> 'cart-item-text-color',
					'type'     		=> 'color',
					'class'      	=> 'tf-field-color-single',
					'label'    		=> __( 'Cart Item Text Color', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel cart item text color', 'instantio' ),
				),

				array(
					'id'       		=> 'cart-input-bg',
					'type'     		=> 'color',
					'class'      	=> 'tf-field-color-single',
					'label'    		=> __( 'Cart Input Background', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel cart input background color', 'instantio' ),
				),

				array(
					'id'       		=> 'cart-input-text-color',
					'type'     		=> 'color',
					'class'      	=> 'tf-field-color-single',
					'label'    		=> __( 'Cart Input Text Color', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel cart input text color', 'instantio' ),
				),

				array(
					'id'       		=> 'cart-pricing-bg',
					'type'     		=> 'color',
					'class'      	=> 'tf-field-color-single',
					'label'    		=> __( 'Cart Pricing Table Background', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel pricing table background color', 'instantio' ),
				),

				array(
					'id'       		=> 'cart-pricing-text',
					'type'     		=> 'color',
					'class'      	=> 'tf-field-color-single',
					'label'    		=> __( 'Cart Pricing Table Text Color', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel pricing table text color', 'instantio' ),
				),

				array(
					'id'        	=> 'cart-button-background-colors',
					'type'      	=> 'color',
					'multiple'  	=> true,
					'inline'    	=> true,
					'label'    		=> __( 'Cart Button Background Colors', 'instantio' ),
					'subtitle' 		=> __( 'Set regular & hover color', 'instantio' ),
					'colors'   		=> array(
						'regular' 			=> __('Regular', 'instantio'),
						'hover' 			=> __('Hover', 'instantio'),
					),
				),

				array(
					'id'        	=> 'cart-button-text-colors',
					'type'      	=> 'color',
					'multiple'  	=> true,
					'inline'    	=> true,
					'label'    		=> __( 'Cart Button Text Colors', 'instantio' ),
					'subtitle' 		=> __( 'Set regular & hover color', 'instantio' ),
					'colors'   		=> array(
						'regular' 			=> __('Regular', 'instantio'),
						'hover' 			=> __('Hover', 'instantio'),
					),
				),

				array(
					'id'    		=> 'Billing_section',
					'type'    		=> 'heading',
					'content' 		=> __('Billing Section', 'instantio'),
				),

				array(
					'id'       		=> 'ins-bill-bg',
					'type'     		=> 'color',
					'class'      	=> 'tf-field-color-single',
					'label'    		=> __( 'Panel Billing Background', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel billing section background color', 'instantio' ),
				),

				array(
					'id'       		=> 'ins-bill-heading',
					'type'     		=> 'color',
					'class'      	=> 'tf-field-color-single',
					'label'    		=> __( 'Panel Billing Heading Color', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel billing section heading text color', 'instantio' ),
				),

				array(
					'id'       		=> 'ins-bill-label',
					'type'     		=> 'color',
					'class'			=> 'tf-field-color-single',
					'label'    		=> __( 'Panel Billing Label Color', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel billing section label text color', 'instantio' ),
					'output'    	=> array('.ins-checkout-body form.woocommerce-checkout .form-row label'),
				),

				array(
					'id'       		=> 'ins-bill-bg-padding_option',
					'type'     		=> 'switch', 
					'label'    		=> __('Enable Panel Billing Padding', 'instantio'),
					'subtitle' 		=> __('Set Panel Billing Padding','instantio'),
					'label_on'  	=> __('Enable', 'instantio'),
					'label_off' 	=> __('Disable', 'instantio'),
					'width'   		=> 100,
					'default'  		=> false,
				),

				array(
					'id'            => 'ins-bill-bg-padding',
					'type'   		=> 'fieldset',
					'label'  		=> __('Panel Billing Padding', 'instantio'),
					'subtitle' 		=> __('Toggle panel billing section padding', 'instantio'),
					'dependency' 	=> array('ins-bill-bg-padding_option','==','true'),
					'fields' 		=> array(
						array(
							'id' 			=> 'ins-bill-bg-padding-top',
							'class' 		=> 'tf-field-inline',
							'type' 			=> 'number',
							'label' 		=> 'Border Top',
							'subtitle' 		=> 'Border Top ',
							'placeholder' 	=> '1px',
							'default' 		=> '1px',
						),
						array(
							'id' 			=> 'ins-bill-bg-padding-right',
							'class' 		=> 'tf-field-inline',
							'type' 			=> 'number',
							'label' 		=> 'Border Right',
							'subtitle' 		=> 'Border Right ',
							'placeholder' 	=> '1px',
							'default' 		=> '1px',
						),
						array(
							'id' 			=> 'ins-bill-bg-padding-bottom',
							'class' 		=> 'tf-field-inline',
							'type' 			=> 'number',
							'label' 		=> 'Border Bottom',
							'subtitle' 		=> 'Border Bottom ',
							'placeholder' 	=> '1px',
							'default' 		=> '1px',
						),
						array(
							'id' 			=> 'ins-bill-bg-padding-left',
							'class' 		=> 'tf-field-inline',
							'type' 			=> 'number',
							'label' 		=> 'Border Left',
							'subtitle' 		=> 'Border Left ',
							'placeholder' 	=> '1px',
							'default' 		=> '1px',
						),
					),
				),

				array(
					'id'       		=> 'ins-bill-label',
					'type'     		=> 'color',
					'class'			=> 'tf-field-color-single',
					'label'    		=> __( 'Panel Billing Label Color', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel billing section label text color', 'instantio' ),
				),
				
				array(
					'id'       		=> 'ins-bill-input-bg',
					'type'     		=> 'color',
					'class'			=> 'tf-field-color-single',
					'label'    		=> __( 'Panel Billing Input Background', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel billing section input background color', 'instantio' ),
				),
				
				array(
					'id'       		=> 'ins-bill-input-border',
					'type'     		=> 'color',
					'class'			=> 'tf-field-color-single',
					'label'    		=> __( 'Panel Billing Input Border Color', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel billing section input border color', 'instantio' ),
				),
				
				array(
					'id'       		=> 'ins-bill-input-text',
					'type'     		=> 'color',
					'class'			=> 'tf-field-color-single',
					'label'    		=> __( 'Panel Billing Input Text Color', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel billing section input text color', 'instantio' ),
				),
				
				array(
					'id'       		=> 'ins-bill-input-shadow',
					'type'     		=> 'color',
					'class'			=> 'tf-field-color-single',
					'label'    		=> __( 'Panel Billing Input Shadow Color', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel billing section input shadow color', 'instantio' ),
				),

				array(
					'id'    		=> 'review-section',
					'type'    		=> 'heading',
					'content' 		=> __('Review Section', 'instantio'),
				),

				array(
					'id'       		=> 'ins-review-heading',
					'type'     		=> 'color',
					'class'			=> 'tf-field-color-single',
					'label'    		=> __( 'Order Review Heading', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel review section heading color', 'instantio' ),
				),
				
				array(
					'id'       		=> 'ins-review-bg',
					'type'     		=> 'color',
					'class'			=> 'tf-field-color-single',
					'label'    		=> __( 'Review Section Background', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel review section background color', 'instantio' ),
				),
				
				array(
					'id'       		=> 'ins-review-tbl-head-bg',
					'type'     		=> 'color',
					'class'			=> 'tf-field-color-single',
					'label'    		=> __( 'Review Table Head Background', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel review section table head background color', 'instantio' ),
				),

				array(
					'id'       		=> 'ins-review-tbl-head-txt',
					'type'     		=> 'color',
					'class'			=> 'tf-field-color-single',
					'label'    		=> __( 'Review Table Head Text Color', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel review section table head text color', 'instantio' ),
				),
				
				array(
					'id'       		=> 'ins-review-tbl-item-bg',
					'type'     		=> 'color',
					'class'			=> 'tf-field-color-single',
					'label'    		=> __( 'Review Table Item Background', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel review section table item background color', 'instantio' ),
				),
				
				array(
					'id'       		=> 'ins-review-tbl-item-txt',
					'type'     		=> 'color',
					'class'			=> 'tf-field-color-single',
					'label'    		=> __( 'Review Table Item Text Color', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel review section table item text color', 'instantio' ),
				),
				
				array(
					'id'       		=> 'ins-review-pricing-bg',
					'type'     		=> 'color',
					'class'			=> 'tf-field-color-single',
					'label'    		=> __( 'Review Pricing Background', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel review section pricing background color', 'instantio' ),
				),

				array(
					'id'       		=> 'ins-review-pricing-txt',
					'type'     		=> 'color',
					'class'			=> 'tf-field-color-single',
					'label'    		=> __( 'Review Pricing Text Color', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel review section pricing text color', 'instantio' ),
				),

				array(
					'id'    		=> 'payment-section',
					'type'    		=> 'heading',
					'content'	 	=> __('Payment Section', 'instantio'),
				),
		
				array(
					'id'       		=> 'ins-pay-item-bg',
					'type'     		=> 'color',
					'class'			=> 'tf-field-color-single',
					'label'    		=> __( 'Payment Methods Background', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel Payment methods background color', 'instantio' ),
				),
				
				array(
					'id'       		=> 'ins-pay-item-txt',
					'type'     		=> 'color',
					'class'			=> 'tf-field-color-single',
					'label'    		=> __( 'Payment Methods Text Color', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel Payment methods text color', 'instantio' ),
				),
				
				array(
					'id'       		=> 'ins-pay-item-des-bg',
					'type'     		=> 'color',
					'class'			=> 'tf-field-color-single',
					'label'    		=> __( 'Payment Methods Description Background', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel Payment methods description background color', 'instantio' ),
				),
				
				array(
					'id'       		=> 'ins-pay-item-des-txt',
					'type'     		=> 'color',
					'class'			=> 'tf-field-color-single',
					'label'    		=> __( 'Payment Methods Description Text Color', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel Payment methods description text color', 'instantio' ),
				),
				
				array(
					'id'       		=> 'ins-place-order-bg',
					'type'     		=> 'color',
					'class'			=> 'tf-field-color-single',
					'label'    		=> __( 'Place Order Background', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel place order background color', 'instantio' ),
				),
				
				array(
					'id'       		=> 'ins-place-order-txt',
					'type'     		=> 'color',
					'class'			=> 'tf-field-color-single',
					'label'    		=> __( 'Place Order Text Color', 'instantio' ),
					'subtitle' 		=> __( 'Toggle panel place order text color', 'instantio' ),
				),
		
				array(
					'id'        	=> 'ins-place-order-button-bg',
					'type'      	=> 'color',
					'multiple'  	=> true,
					'inline'    	=> true,
					'label'    		=> __( 'Place Order Button Background', 'instantio' ),
					'subtitle' 		=> __( 'Place order button background regular & hover color', 'instantio' ),
					'colors'   		=> array(
						'regular' 			=> __('Regular', 'instantio' ),
						'hover'				=> __('Hover', 'instantio' ),
					),
				),
		
				array(
					'id'        	=> 'ins-place-order-button-border',
					'type'      	=> 'color',
					'multiple'  	=> true,
					'inline'    	=> true,
					'label'    		=> __( 'Place Order Button Border Colors', 'instantio' ),
					'subtitle' 		=> __( 'Place order button border regular & hover color', 'instantio' ),
					'colors'   		=> array(
						'regular' 			=> __('Regular', 'instantio' ),
						'hover' 			=> __('Hover', 'instantio' ),
					),
				),
		
				array(
					'id'        	=> 'ins-place-order-button-text',
					'type'      	=> 'color',
					'multiple'  	=> true,
					'inline'    	=> true,
					'label'    		=> __( 'Place Order Button Text Colors', 'instantio' ),
					'subtitle' 		=> __( 'Place order button text regular & hover color', 'instantio' ),
					'colors'   		=> array(
						'regular' 			=> __('Regular', 'instantio' ),
						'hover' 			=> __('Hover', 'instantio' ),
					),
				),
			),
		),

		'other_design' 				=> array(
			'title'  				=> esc_html__( 'Toggle Design', 'instantio' ),
			'parent' 				=> 'design_option',
			'icon'   				=> 'fa fa-cog',
			'fields' 				=> array(

				array(
					'id'       		=> 'wi-quickview-bg',
					'type'     		=> 'color',
					'class'			=> 'tf-field-color-single',
					'label'    		=> __( 'Quick View Background', 'instantio' ),
					'subtitle' 		=> __( 'Instantio Quick View Panel Background Color', 'instantio' ),
				),

				array(
					'id'       		=> 'ins-quickview-color',
					'type'     		=> 'color',
					'class'			=> 'tf-field-color-single',
					'label'    		=> __( 'Quick View Color', 'instantio' ),
					'subtitle' 		=> __( 'Instantio Quick View Panel Text & Cross Color', 'instantio' ),  		
				),

				array(
					'id'       		=> 'wi-custom-css',
					'type'     		=> 'code_editor',
					'label'    		=> __('Custom CSS', 'instantio' ),
					'subtitle' 		=> __( 'If you want to make extra CSS then you can do it from here', 'instantio' ),
					'settings' 		=> array(
					  	'theme'  			=> 'monokai',
						'mode'   			=> 'css',
					),
				),
			),
		),
	 
		/**
		 * Mobile
		 * Main menu
		 */
		'mobile' 					=> array(
			'title' 				=> __( 'Mobile', 'instantio' ),
			'icon' 					=> 'fas fa-mobile-alt',
			'fields' 				=> array(

				array(
					'id'       		=> 'mobile',
					'type'     		=> 'switch',
					'label'    		=> __('Dedicated Mobile Version', 'instantio'),
					'subtitle' 		=> __('Enable/disable dedicated mobile version', 'instantio'),
					'label_on'    	=> __('Enabled', 'instantio' ),
					'label_off'   	=> __('Disabled', 'instantio' ),
					'width' 		=> 100,
					'default'   	=> false,          
				),

				array(
					'id'    		=> 'cart-section_mobile',
					'type'    		=> 'heading',
					'content' 		=> __('Cart Section', 'instantio'),
				),

				array(
					'id'       		=> 'mobile-cart-coupon',
					'type'     		=> 'switch',
					'label'    		=> __('Enable Cart Coupon', 'instantio'),
					'subtitle' 		=> __('Enable/disable Coupon field in the cart in mobile version', 'instantio'),
					'label_on'    	=> __('Yes', 'instantio' ),
					'label_off'   	=> __('No', 'instantio' ),
					'default'   	=> false,          
					'dependency' 	=> array('mobile', '==', 'true'),
				),

				array(
					'id'       		=> 'mobile-cart-panel',
					'type'     		=> 'switch',
					'label'    		=> __('Enable Cart Panel', 'instantio'),
					'subtitle' 		=> __('Enable/disable cart in mobile version', 'instantio'),
					'label_on'    	=> __('Yes', 'instantio' ),
					'label_off'   	=> __('No', 'instantio' ),
					'default'   	=> false,          
					'dependency' => array('mobile', '==', 'true'),
				),

			),
		),


		'optimization'				=> array(
			'title' 				=> __( 'Optimization', 'instantio' ),
			'icon'  				=> 'fas fa-bolt',
			'fields' 				=> array(
				
				array(
					'id'       		=> 'fancy-cdn',
					'type'     		=> 'switch',
					'label'    		=> __('FancyBox CDN', 'instantio'),
					'subtitle' 		=> __('Enable/disable cloudflare CDN for FancyBox CSS & JS', 'instantio'),
					'label_on'    	=> __('Enabled', 'instantio' ),
					'label_off'   	=> __('Disabled', 'instantio' ),
					'width' 		=> 100,
					'default'   	=> false,
				),
		
				array(
					'id'       		=> 'css-min',
					'type'     		=> 'switch',
					'label'    		=> __('Minify CSS', 'instantio'),
					'subtitle' 		=> __('Enable/disable Instantio CSS minification', 'instantio'),
					'label_on'    	=> __('Enabled', 'instantio' ),
					'label_off'   	=> __('Disabled', 'instantio' ),
					'width' 		=> 100,
					'default'   	=> false,           
				),
		
				array(
					'id'       		=> 'js-min',
					'type'     		=> 'switch',
					'label'    		=> __('Minify JS', 'instantio'),
					'subtitle' 		=> __('Enable/disable Instantio JS minification', 'instantio'),
					'label_on'    	=> __('Enabled', 'instantio' ),
					'label_off'   	=> __('Disabled', 'instantio' ),
					'width' 	=> 100,
					'default'   	=> false,           
				),
			),
		),



	),
) );

