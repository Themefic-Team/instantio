<?php 
namespace INS\Controller;

class App {

    public $layout = 1;
    public $layouts_slug = "/layouts/layout.php";
    public $layout_class = "";
    

    public function __construct() {
         
        $this->ins_layout_set_data();

        add_action( 'wp_footer', array($this, 'ins_layout_three'), 10 );
        add_filter( 'woocommerce_add_to_cart_fragments', array($this, 'ins_cart_count_fragments'), 50, 1 );

        // Ajax Cart reload After Product Add to Cart
        add_action( 'wp_ajax_nopriv_ins_ajax_cart_reload', array( $this, 'ins_ajax_cart_reload' ), 20 );  
        add_action( 'wp_ajax_ins_ajax_cart_reload', array( $this, 'ins_ajax_cart_reload' ), 20 ); 

        // Ajax Cart Remove To cart
        add_action( 'wp_ajax_nopriv_ins_ajax_cart_item_remove', array( $this, 'ins_ajax_cart_item_remove' ), 20 );  
        add_action( 'wp_ajax_ins_ajax_cart_item_remove', array( $this, 'ins_ajax_cart_item_remove' ), 20 ); 

        // Ajax Cart Remove To cart
        add_action( 'wp_ajax_nopriv_ins_ajax_empty_cart', array( $this, 'ins_ajax_empty_cart' ), 20 );  
        add_action( 'wp_ajax_ins_ajax_empty_cart', array( $this, 'ins_ajax_empty_cart' ), 20 ); 

        // Ajax Update Cart
        add_action( 'wp_ajax_nopriv_ins_ajax_update_cart', array( $this, 'ins_ajax_update_cart' ));
        add_action( 'wp_ajax_ins_ajax_update_cart', array( $this, 'ins_ajax_update_cart' ));

        // Ajax Remove Coupon
        add_action( 'wp_ajax_nopriv_ins_ajax_remove_coupon', array( $this, 'ins_ajax_remove_coupon' ));
        add_action( 'wp_ajax_ins_ajax_remove_coupon', array( $this, 'ins_ajax_remove_coupon' ));

        add_action( 'ins_button_toggle', array( $this, 'ins_button_toggle' ));
   
    }

    public function ins_layout_set_data() {
        $ins_layout = !empty(insopt( 'ins-layout' )) ? insopt( 'ins-layout' ) : '';
        
        if ($ins_layout == 1) {
            $this->layout = $ins_layout;
            $this->layout_class = '';
            $this->layouts_slug =  "/layouts/layout-1.php";
        } elseif ($ins_layout == 2) {
            $this->layout = $ins_layout;
            $this->layout_class = 'slide'; 
            $this->layouts_slug =  "/layouts/layout-2.php";
            
        } elseif ($ins_layout == 3) { 
            $this->layout = $ins_layout;
            $this->layout_class = 'popup';
            $this->layouts_slug =  "/layouts/layout-3.php";
        }
    }

    // Ins Toggle button
    public function ins_button_toggle() {
        ob_start();
        $ins_toggler =  insopt( 'ins-toggler' );
         ?>
            <div class="ins-click-to-show ins-toggle-btn <?php echo esc_attr( $ins_toggler ) ?>">
                <svg class="cart-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" xmlns:v="https://vecta.io/nano"><path d="M490.299 185.717H384.08L324.496 49.284c-3.315-7.591-12.157-11.06-19.749-7.743s-11.059 12.158-7.743 19.75l54.34 124.427H160.656l54.34-124.427c3.315-7.592-.151-16.434-7.743-19.75a15 15 0 0 0-19.749 7.743L127.92 185.717H21.701c-13.895 0-24.207 12.579-21.167 25.82l55.935 243.63c2.221 9.674 11.015 16.55 21.167 16.55h356.728c10.152 0 18.946-6.876 21.167-16.55l55.935-243.63c3.04-13.24-7.273-25.82-21.167-25.82zm-359.557 46.004c-2.004 0-4.041-.404-5.996-1.258-7.592-3.315-11.059-12.157-7.743-19.75l11.268-25.802h32.736l-16.512 37.808c-2.461 5.639-7.971 9.002-13.753 9.002zM181 391.717c0 8.284-6.716 15-15 15s-15-6.716-15-15v-110c0-8.284 6.716-15 15-15s15 6.716 15 15zm90 0c0 8.284-6.716 15-15 15s-15-6.716-15-15v-110c0-8.284 6.716-15 15-15s15 6.716 15 15zm90 0c0 8.284-6.716 15-15 15s-15-6.716-15-15v-110c0-8.284 6.716-15 15-15s15 6.716 15 15zm26.253-161.254a14.94 14.94 0 0 1-5.995 1.258c-5.782 0-11.292-3.362-13.754-9.001l-16.512-37.808h32.736l11.268 25.802a15 15 0 0 1-7.743 19.749z"></path></svg>
 
                <span class="ins-items-count"><span id="ins_cart_total" class="ins_cart_total"><?php echo WC()->cart->get_cart_contents_count(); ?></span></span>
            </div>
         <?php
         echo ob_get_clean();
    }
 
    // Cart Count Fragments
    public function ins_cart_count_fragments(){
        ob_start();
        ?>
        <span id="ins_cart_total" class="ins_cart_total"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
        <?php
        $fragments['#ins_cart_total'] = ob_get_clean();
        return $fragments;
    }

    // Ajax Cart reload After Product Add to Cart
    public function ins_ajax_cart_reload() { 
        ob_start();
        require_once INS_INC_PATH .  $this->layouts_slug;
        echo ob_get_clean(); 
        wp_die();
       
    }

    // Ajax Cart Remove To cart
    public function ins_ajax_cart_item_remove() { 
        $id = $_POST['id'];  
        
        $cart = WC()->cart->get_cart();  

        foreach ($cart as $cart_item_key => $cart_item){
            if($cart_item['product_id'] == $id ){ 
                // Remove product in the cart using  cart_item_key.
                WC()->cart->remove_cart_item($cart_item_key); 
            }
        } 
        WC()->cart->calculate_totals();
        WC()->cart->maybe_set_cart_cookies();
      
        ob_start();
        require_once INS_INC_PATH .  $this->layouts_slug; 
        
        $cart_data = ob_get_clean(); 
        // Fragments and mini cart are returned
        $data = array(
            'cart_data' => $cart_data,
            'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() )
        );

        wp_send_json( $data );
        wp_die();
       
    }

    // Ajax Update Cart
    public function ins_ajax_update_cart() { 
       
    
        $cart_item_keys = $_POST['cart_item_keys'];
        $product_ids = $_POST['product_ids'];
        $quantities = $_POST['quantities'];
        $coupon_code = $_POST['coupon_code'];
        $cart_updated = false;
       
        for ( $i = 0; $i < count( $cart_item_keys ); $i++ ) {

            WC()->cart->set_quantity( $cart_item_keys[ $i ], $quantities[ $i ], false );

            if($quantities[ $i ] == 0){
                WC()->cart->remove_cart_item( $cart_item_keys[ $i ] );
                continue;
            }
        } 
        if ( ! empty( $coupon_code ) ) {
            WC()->cart->add_discount( sanitize_text_field( $coupon_code ) );  
        } 
        
        WC()->cart->calculate_totals();
        WC()->cart->maybe_set_cart_cookies();  
         
    
       
        // if ( WC()->cart->is_empty() ) {
        //     echo '<div class="woocommerce-message" role="alert">Cart is empty.</div>';
        //     return;
        // }
    
        ob_start();
        require_once INS_INC_PATH .  $this->layouts_slug;
        $cart_data = ob_get_clean(); 
        // Fragments and mini cart are returned
        $data = array(
            'cart_data' => $cart_data,
            'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() )
        );

        wp_send_json( $data );
        wp_die();
       
    }

    // Ajax Cart Empty To cart
    public function ins_ajax_empty_cart() { 

        WC()->cart->empty_cart();
        WC()->cart->calculate_totals();
        WC()->cart->maybe_set_cart_cookies();
        
        ob_start();
        require_once INS_INC_PATH .  $this->layouts_slug;
        $cart_data = ob_get_clean(); 
        // Fragments and mini cart are returned
        $data = array(
            'cart_data' => $cart_data,
            'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() )
        );

        wp_send_json( $data );
        wp_die();
       
    }

    // Ajax Remove Coupon
    public function ins_ajax_remove_coupon() { 
        $coupon_code = $_POST['coupon'];
        WC()->cart->remove_coupon( $coupon_code );

        WC()->cart->calculate_totals();
        WC()->cart->maybe_set_cart_cookies();
        
        ob_start();
        require_once INS_INC_PATH .  $this->layouts_slug;
        $cart_data = ob_get_clean(); 
        // Fragments and mini cart are returned
        $data = array(
            'cart_data' => $cart_data,
            'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() )
        );

        wp_send_json( $data );
        wp_die();
       
    }



    

    public function ins_layout_three(){
        // Return if WooCommerce not active
		if ( !class_exists( 'woocommerce' ) ) {
    		return;
		}
		
		// Return if checkout page
		if ( class_exists( 'woocommerce' ) ) {
    		if (is_checkout()) {
    			return;
    		}
		}

		// Return if cart page
		if ( class_exists( 'woocommerce' ) ) {
    		if (is_page( 'cart' ) || is_cart()) {
    			return;
    		}
		}
       
       
        ob_start();
        if( $this->layout == 3 ){
                do_action('ins_button_toggle');
        }
        ?>
        <div class="ins-checkout-popup <?php echo esc_attr( $this->layout_class ) ?>">
            <div class="ins-checkout-overlay"></div>
            <div class="ins-checkout-layout ins-checkout-layout-3 <?php echo esc_attr( $this->layout_class ) ?>"> 

                <?php require_once INS_INC_PATH .  $this->layouts_slug ?>	
            </div>
        </div>
        <?php
        $output = ob_get_clean();
        echo $output;
    } 
     
}


?>