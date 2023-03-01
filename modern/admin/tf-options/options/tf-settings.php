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
					'id'       => 'disable-services',
					'type'     => 'checkbox',
					'label'    => __( 'Disable Post Types', 'tourfic' ),
					'subtitle' => __( 'Tick the checkbox to disable the Post Type you don\'t need.', 'tourfic' ),
					'options'  => array(
						'hotel' => __( 'Hotels', 'tourfic' ),
						'tour'  => __( 'Tours', 'tourfic' ),
					),
				),
			),
		),
		'hotel_option'       => array(
			'title'  => esc_html__( 'Hotel Options', 'tourfic' ),
			'icon'   => 'fas fa-hotel',
			'fields' => array(),
		),
		'single_page'        => array(
			'title'  => esc_html__( 'Single Page', 'tourfic' ),
			'parent' => 'hotel_option',
			'icon'   => 'fa fa-cog',
			'fields' => array(
				array(
					'id'        => 'label_off_heading',
					'type'      => 'heading',
					'label'     => __( 'Global Settings for Single Hotel Page', 'tourfic' ),
					'sub_title' => __( 'These options can be overridden from Single Hotel Settings.', 'tourfic' ),
				),

				array(
					'id'        => 'h-review',
					'type'      => 'switch',
					'label'     => __( 'Disable Review Section', 'tourfic' ),
					'label_on'  => __( 'Yes', 'tourfic' ),
					'label_off' => __( 'No', 'tourfic' ),
					'default'   => false
				),

				array(
					'id'        => 'h-share',
					'type'      => 'switch',
					'label'     => __( 'Disable Share Option', 'tourfic' ),
					'label_on'  => __( 'Yes', 'tourfic' ),
					'label_off' => __( 'No', 'tourfic' ),
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
					'subtitle' => __( 'The Email to receive all enquiry form submissions', 'tourfic' ),
					'is_pro'   => true,
				),
			),
		),
		'room_config'        => array(
			'title'  => esc_html__( 'Room Config', 'tourfic' ),
			'parent' => 'hotel_option',
			'icon'   => 'fa fa-cog',
			'fields' => array(
				array(
					'id'    => 'hotel_room_heading',
					'type'  => 'heading',
					'label' => __( 'Global Configuration for Hotel Rooms', 'tourfic' ),
				),

				array(
					'id'       => '',
					'type'     => 'switch',
					'label'    => __( 'Children Age Limit', 'tourfic' ),
					'subtitle' => __( 'Turn on this option to set the Maximum age limit for Children. This can be overridden from Single Hotel Settings.', 'tourfic' ),
					'is_pro'   => true,
				),
				array(
					'id'         => '',
					'type'       => 'number',
					'label'      => __( 'Insert your Maximum Age Limit', 'tourfic' ),
					'subtitle'   => __( 'Numbers Only', 'tourfic' ),
					'attributes' => array(
						'min' => '0',
					),
					'is_pro'     => true,
				),
			),
		),
	 

		/**
		 * Import/Export
		 *
		 * Main menu
		 */
		// 'import_export' => array(
		// 	'title' => __( 'Import/Export', 'tourfic' ),
		// 	'icon' => 'fas fa-hdd',
		// 	'fields' => array(
		// 		array(
		// 			'id' => 'backup',
		// 			'type' => 'backup',
		// 		),  

		// 	),
		// ),
	),
) );
