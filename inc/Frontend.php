<?php
namespace INS\Includes;

class Frontend {
    
        public function __construct() {  
            $this->includes();
            $this->init();
            $this->init_hooks();
        }
        
        /**
        * Include required core files used in admin and on the frontend.
        */
        private function includes() {   
             
    
        }
    
        /**
        * Init Instantio when WordPress Initialises.
        */
        private function init_hooks() { 
            /**
            * Check if WooCommerce is active, and if it isn't, disable the plugin.
            *
            * @since 1.0
            */ 
        }
    
        /**
        *  init Instantio when WordPress Initialises.
        *
        * @since 1.0
        */
        public function init() {
            
            
        } 

}
?>