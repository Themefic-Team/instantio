<?php

// Control core classes for avoid errors
if( class_exists( 'CSF' ) ) {


  // Set a unique slug-like ID
  $prefix = 'wiopt';

  // Create options
  CSF::createOptions( $prefix, array(
    'framework_title' => 'Instantio Settings <small>by <a style="color: #bfbfbf;text-decoration:none;" href="https://themefic.com" target="_blank">Themefic</a></small>',
    'menu_title' => 'Instantio',
    'menu_slug'  => 'instantio_options',
    'menu_icon'  => 'dashicons-cart',
    'footer_credit' => __('<em>Enjoyed <strong>Instantio</strong>? Please leave us a <a style="color:#e9570a;" href="https://wordpress.org/support/plugin/instantio/reviews/?filter=5/#new-post" target="_blank">★★★★★</a> rating. We really appreciate your support!</em>', 'instantio'),
    'show_bar_menu' => false,
  ) );

  /*
  * Including General Options
  */    
  if ( file_exists( dirname( __FILE__ ) . '/options/general.php' ) ) {
    require_once dirname( __FILE__ ) . '/options/general.php';
  }

  /*
  * Design Parent
  */ 
  CSF::createSection( $prefix, array(
    'id'    => 'design', // Set a unique slug-like ID
    'title' => __( 'Design', 'instantio' ),
    'icon'  => 'fas fa-palette',
  ) );


  /*
  * Including Toggler Options
  */    
  if ( file_exists( dirname( __FILE__ ) . '/options/toggler.php' ) ) {
    require_once dirname( __FILE__ ) . '/options/toggler.php';
  }


  /*
  * Including Toggle Panel Options
  */    
  if ( file_exists( dirname( __FILE__ ) . '/options/toggle-panel.php' ) ) {
    require_once dirname( __FILE__ ) . '/options/toggle-panel.php';
  }

  /*
  * Including Others Options
  */    
  if ( file_exists( dirname( __FILE__ ) . '/options/others.php' ) ) {
    require_once dirname( __FILE__ ) . '/options/others.php';
  }

  /*
  * Including Mobile Options
  */ 
  if ( file_exists( dirname( __FILE__ ) . '/options/mobile.php' ) ) {
    require_once dirname( __FILE__ ) . '/options/mobile.php';
  }

  /*
  * Including Optimization Options
  */ 
  if ( file_exists( dirname( __FILE__ ) . '/options/optimization.php' ) ) {
    require_once dirname( __FILE__ ) . '/options/optimization.php';
  }

  /*
  * Backup
  */ 
  CSF::createSection( $prefix, array(
    'title'       => __('Import/Export', 'instantio'),
    'icon'        => 'fas fa-hdd',
    'fields'      => array(
  
      array(
        'type' => 'backup',
      ),
  
    )
  ) );
  
}

?>