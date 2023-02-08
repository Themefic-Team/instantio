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
        define( 'INS_INC_URL', INS_URL.'inc' );
        define( 'INS_LAYOUTS_URL', INS_URL.'inc/layouts' );
        define( 'INS_ASSETS_URL', INS_URL.'assets' );
        // Paths
        define( 'INS_PATH', INS_MODERN_PATH.'/' );
        define( 'INS_INC_PATH', INS_PATH.'inc' );
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
        new INS\Controller\Assets();

        if ( is_admin() ) {
            new INS\Controller\Admin();
        }else{
			new INS\Controller\App();
		} 

	}
 

}
NEW MODERN();

?>