<?php 
namespace INS\Controller;

class App {

    private $layouts;

    public function __construct() {
        // $layouts =  "\layouts\layout.php";

        // wp_die( INS_INC_PATH . $layouts );
        
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
 
        // Plus Minus Button added to cart
        add_action( 'woocommerce_after_quantity_input_field', array( $this, 'ins_after_quantity_input_field' ), 20 ); 
        add_action( 'woocommerce_before_quantity_input_field', array( $this, 'ins_before_quantity_input_field' ), 20 );   
    }

   
    // Plus Minus Button added to cart
    public function ins_after_quantity_input_field() {
        echo '<button type="button" class="plus ins-cart-plus">+</button>';
    }
 
    // Plus Minus Button added to cart
    public function ins_before_quantity_input_field() {
        echo '<button type="button" class="minus ins-cart-minus">-</button>';
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
        require_once INS_INC_PATH . "\layouts\layout.php";
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
        require_once INS_INC_PATH . "\layouts\layout.php";
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
         
        for ( $i = 0; $i < count( $cart_item_keys ); $i++ ) {
            if ( WC()->cart->set_quantity( $cart_item_keys[ $i ], $quantities[ $i ], false ) ) {
                $cart_updated = true;
            }
        } 
        if ( ! empty( $coupon_code ) ) {
            WC()->cart->add_discount( sanitize_text_field( $coupon_code ) );
        }

        WC()->cart->calculate_totals();
        WC()->cart->maybe_set_cart_cookies(); 

        ob_start();
        require_once INS_INC_PATH . "\layouts\layout.php";
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
        require_once INS_INC_PATH . "\layouts\layout.php";
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
        require_once INS_INC_PATH . $layouts;
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
        ?>
        <div class="ins-checkout-overlay"></div>
        <div class="ins-checkout-layout ins-checkout-layout-3 slide">
            <?php require_once INS_INC_PATH . "\layouts\layout.php" ?>	
        </div>
        <?php
        $output = ob_get_clean();
        echo $output;
    } 
     
}


?>