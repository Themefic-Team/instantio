<?php 

CSF::createSection( $prefix, array(
    'parent' => 'design', // The slug id of the parent section
    'title' => __( 'Others', 'instantio' ),
    'fields' => array(

        array(
            'type'    => 'subheading',
            'content' => __('Others', 'instantio'),
        ),

        array(
            'id'       => 'wi-quickview-bg',
            'type'     => 'color',
            'title'    => __( 'Quick View Background', 'instantio' ),
            'subtitle' => __( 'Instantio Quick View Panel Background Color', 'instantio' ),
            'output'      => '.ins-quick-view, .ins-quick-view .variations_form table tbody tr td.label',
            'output_mode' => 'background',  
            'dependency' => array( '', '==', '', '', 'visible' ),		
        ),
		
		array(
            'id'       => 'ins-quickview-color',
            'type'     => 'color',
            'title'    => __( 'Quick View Color', 'instantio' ),
            'subtitle' => __( 'Instantio Quick View Panel Text & Cross Color', 'instantio' ),
            'output'      => '.ins-quick-view, .ins-quick-view table tbody td label',  	
            'dependency' => array( '', '==', '', '', 'visible' ),	
        ),

        array(
            'id'       => 'wi-custom-css',
            'type'     => 'code_editor',
            'title'    => 'Custom CSS',
            'subtitle' => __( 'If you want to make extra CSS then you can do it from here', 'instantio' ),
            'settings' => array(
              'theme'  => 'monokai',
              'mode'   => 'css',
            ),
            'dependency' => array( '', '==', '', '', 'visible' ),
          ),

    )
) );
  
?>