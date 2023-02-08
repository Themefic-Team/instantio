<?php 
// don't load directly
defined( 'ABSPATH' ) || exit;

$badge_up = '<div class="ins-csf-badge"><span class="ins-upcoming">' .__("Upcoming", "instantio"). '</span></div>';
$badge_pro = '<div class="ins-csf-badge"><span class="ins-pro">' .__("Pro Feature", "instantio"). '</span></div>';
$badge_up_pro = '<div class="ins-csf-badge"><span class="ins-upcoming">' .__("Upcoming", "instantio"). '</span><span class="ins-pro">' .__("Pro Feature", "instantio"). '</span></div>';

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
            'class' => 'ins-csf-disable ins-csf-pro',
            'type'     => 'color',
            'title'    => __( 'Quick View Background', 'instantio' ),
            'subtitle' => __( 'Instantio Quick View Panel Background Color' .$badge_pro, 'instantio' ),
            'output'      => '.ins-quick-view, .ins-quick-view .variations_form table tbody tr td.label',
            'output_mode' => 'background',  		
        ),
		
		array(
            'id'       => 'ins-quickview-color',
            'class' => 'ins-csf-disable ins-csf-pro',
            'type'     => 'color',
            'title'    => __( 'Quick View Color', 'instantio' ),
            'subtitle' => __( 'Instantio Quick View Panel Text & Cross Color' .$badge_pro, 'instantio' ),
            'output'      => '.ins-quick-view, .ins-quick-view table tbody td label',  		
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
          ),

    )
) );
  
?>