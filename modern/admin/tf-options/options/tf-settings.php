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
					// 'placeholder' => 'Enter your placeholder',
					// 'description' => 'Enter your description',
					'class'     => 'tf-field-class',
					'options'   => array(
						'option-1' => 'Direct Checkout Button',
						'option-2' => 'Side Cart',
						'option-3' => 'Popup Cart',
						'option-4' => 'Side Cart + Checkout Design 1',
						'option-5' => 'Popup Cart + Checkout Design 1',
						'option-6' => 'Side Cart + Checkout Design 1 V2',
						'option-7' => 'Popup Cart + Checkout Design 1 V2',
					),
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
					
				),

				array(
					'id'        => 'woins-quickview-enable',
					'class'     => 'ins-csf-disable badge_pro',
					'type'      => 'switch',
					'label'     => __( 'Disable Quick View', 'instantio' ),
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
					'label'     => __( 'Disable Ajax Add to Cart', 'instantio' ),
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
			'icon'   => 'fa fa-cog',
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
					'id'        => 'ins-image-asa-cart-icon',
					'type'      => 'switch',
					'label'     => __( 'Custom Image as Toggler Icon', 'instantio' ),
					'label_on'  => __( 'Yes', 'instantio' ),
					'label_off' => __( 'No', 'instantio' ),
					'default'   => false
				),
				array(
					'id'        => 'feature-filter',
					'type'      => 'switch',
					'label'     => __( 'Filter By Feature', 'tourfic' ),
					'label_on'  => __( 'Yes', 'tourfic' ),
					'label_off' => __( 'No', 'tourfic' ),
					'default'   => true,
					'is_pro'    => true
				),
				array(
					'id'       => 'h-enquiry-email',
					'type'     => 'text',
					'label'    => __( 'Email for Enquiry Form', 'tourfic' ),
					'subtitle' => __( 'The Email to receive all enquiry form submissions', 'instantio' ),
					'is_pro'   => true,
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
