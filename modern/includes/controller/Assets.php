<?php  
namespace INS\Controller;

class Assets {
    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 ); 
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) ); 
    } 
    public function enqueue_scripts() { 
        wp_enqueue_style( 'ins-style', INS_ASSETS_URL.'/app/css/instantio-style.css', array(), INSTANTIO_VERSION ); 
        wp_enqueue_script( 'ins-script', INS_ASSETS_URL.'/app/js/instantio-script.js', array('jquery'), INSTANTIO_VERSION, true ); 
        wp_localize_script( 'ins-script', 'ins_params',
            array( 
                'ajax_url' => admin_url( 'admin-ajax.php' ),
            )
        ); 
        
    }
    public function admin_enqueue_scripts() { 
        wp_enqueue_style( 'ins-admin', INS_ASSETS_URL.'/admin/css/instantio-admin-style.css', array(), INSTANTIO_VERSION ); 
        wp_enqueue_script( 'ins-admin-script', INS_ASSETS_URL.'/admin/js/instantio-admin-script.js', array('jquery'), INSTANTIO_VERSION, true ); 
    }

    
}

?>