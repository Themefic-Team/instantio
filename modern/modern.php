<?php  
class MODERN{
    public function __construct() { 
       
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
        
	}

    /**
     * Define constants
     */
    private function define_constants() {
        if ( ! defined( 'INSTANTIO_VERSION' ) ) { 
            define( 'INSTANTIO_VERSION', '2.5.18' ); 
        } 
        // URLs
        define( 'INS_URL', INS_MODERN_URL.'/' );
        define( 'INS_INC_URL', INS_URL.'includes' );
        define( 'INS_LAYOUTS_URL', INS_URL.'includes/layouts' );
        define( 'INS_ASSETS_URL', INS_URL.'assets' );
        // Paths 
        // define( 'INS_MODERN_PATH', INS_ROOT_PATH.'modern/' );
        define( 'INS_PATH', INS_MODERN_PATH.'/' );
        define( 'INS_INC_PATH', INS_PATH.'includes' );
        define( 'INS_LAYOUTS_PATH', INS_INC_PATH.'/layouts' );

    }
 
    /**
     * Include required core files used in admin and on the frontend.
     */

    private function includes() { 
        require_once __DIR__ . '/vendor/autoload.php'; 
    }

    /**
	 * Init Instantio when WordPress Initialises.
	 */
	private function init_hooks() {  
        add_action( 'plugins_loaded', array( $this, 'tf_plugin_loaded_action' ) );
        new INS\Controller\Assets(); 

        if ( is_admin() && !wp_doing_ajax() ) {   
            new INS\Controller\Admin();
        }else{  
			new INS\Controller\App();
        }

	}
 
    /**
     * Plugins Loaded Actions
     *
     * Including Option Panel
     *
     * Including Options
     */ 
    public function tf_plugin_loaded_action() { 
        if ( file_exists( INS_PATH . 'admin/tf-options/TF_Options.php' ) ) {
            require_once INS_PATH . 'admin/tf-options/TF_Options.php';
        }  

    }  

}
NEW MODERN();

?>