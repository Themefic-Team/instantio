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

        // Single Product Page Ajax Add to Cart
        add_action( 'wp_ajax_nopriv_ins_ajax_cart_single', array( $this, 'ins_ajax_cart_single' ), 20 );  
        add_action( 'wp_ajax_ins_ajax_cart_single', array( $this, 'ins_ajax_cart_single' ), 20 ); 

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

        // Ins Cart Toggle
        add_action( 'ins_cart_toggle', array( $this, 'ins_cart_toggle' ));

        // Ins Cart Buttons
        add_action( 'ins_cart_buttons', array( $this, 'ins_cart_buttons' ));
   
    }

    public function ins_layout_set_data() {
        $ins_layout = !empty(insopt( 'ins-layout' )) ? insopt( 'ins-layout' ) : '';

        require_once INS_INC_PATH .  "/controller/icon-svg.php";
        
        if ($ins_layout == 1) {
            $this->layout = $ins_layout;
            $this->layout_class = '';
            $this->layouts_slug =  "/layouts/layout-1.php";
        } elseif ($ins_layout == 2) {
            $this->layout = $ins_layout;
            $this->layout_class = 'slide '; 
            $this->layouts_slug =  "/layouts/layout-2.php";
            
        } elseif ($ins_layout == 3) { 
            $this->layout = $ins_layout;
            $this->layout_class = 'popup ';
            $this->layouts_slug =  "/layouts/layout-3.php";
        }
    }

    // Ins Toggle button
    public function ins_cart_toggle() {
        ob_start();
        $ins_toggler =  insopt( 'ins-toggler' );
        $cart_icon =  insopt( 'cart-icon' );

        if( $this->layout == 2){
            $togglebtnClass = 'sidecart';
        } elseif($this->layout == 3){
            $togglebtnClass = 'popupcart';
        }

        if($this->layout == 1 ){
            $ins_toggler = 'tog-1';
            ?>
            <a class="ins-toggle-btn <?php echo esc_attr( $ins_toggler ) ?>" href="<?php echo esc_url(wc_get_checkout_url());  ?>"> 
                <?php echo instantio_svg_icon($cart_icon); ?>

                <span class="ins-items-count"><span id="ins_cart_total" class="ins_cart_total"><?php echo WC()->cart->get_cart_contents_count(); ?></span></span> 
           </a> 
            <?php
        }else {
            ?> 
            <div class="ins-click-to-show ins-toggle-btn <?php echo esc_attr( $togglebtnClass ) ?> <?php echo esc_attr( $ins_toggler ) ?>">
                <?php echo instantio_svg_icon($cart_icon); ?>
                <span class="ins-items-count"><span id="ins_cart_total" class="ins_cart_total"><?php echo WC()->cart->get_cart_contents_count(); ?></span></span>
            </div> 
            <?php
        }
        
         echo ob_get_clean();
    }
 
    // Ins Cart Buttons
    public function ins_cart_buttons() {
        ob_start();

        // Cart Button
        $on_cart_btn = isset(insopt( 'cart-btn' )['on-cart-btn']) ? insopt( 'cart-btn' )['on-cart-btn'] : true; 
		$cart_button_text = isset(insopt( 'cart-btn' )['cart_button_text']) ? insopt( 'cart-btn' )['cart_button_text'] : '';
		$cart_button_url = isset(insopt( 'cart-btn' )['cart_button_url']) ? insopt( 'cart-btn' )['cart_button_url'] : '';

        $cart_button_text = !empty($cart_button_text) ? wp_strip_all_tags( __( $cart_button_text, 'instantio' )) : __( 'View Cart', 'instantio' );
        $cart_button_url = !empty($cart_button_url) ? $cart_button_url : wc_get_cart_url();

        // Cart Button Link
        $cart_button = $on_cart_btn == true ? '<a href="'.esc_url( $cart_button_url ).'" class="view-cart active">'.esc_html( $cart_button_text ).'</a>' : '';


        // Checkout Button
        $on_checkout_btn = isset(insopt( 'checkout-btn' )['on-checkout-btn']) ? insopt( 'checkout-btn' )['on-checkout-btn'] : true;
        
		$checkout_button_text = isset(insopt( 'checkout-btn' )['checkout_button_text']) ? insopt( 'checkout-btn' )['checkout_button_text'] : '';
		$checkout_button_url = isset(insopt( 'checkout-btn' )['checkout_button_url']) ? insopt( 'checkout-btn' )['checkout_button_url'] : '';

        $checkout_button_text = !empty($checkout_button_text) ? wp_strip_all_tags( __( $checkout_button_text, 'instantio' )) : __( 'Checkout Now', 'instantio' ); 
        $checkout_button_url = !empty($checkout_button_url) ? $checkout_button_url : wc_get_checkout_url();

        //  Checkout button Link
        $checkout_button = $on_checkout_btn == true ? '<a href="'.esc_url( $checkout_button_url ).'" class="checkout">'.esc_html( $checkout_button_text ).'</a>' : '';

        
        ?> 
        <div class="ins-cart-btns"> 
            <?php echo $cart_button; ?> 
            <?php echo $checkout_button; ?>
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

    // Ajax Single Page Add to Cart
    public function ins_ajax_cart_single() {
        $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
        $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
        $variation_id = absint($_POST['variation_id']);
        $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
        $product_status = get_post_status($product_id);

        if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && $product_status === 'publish') {

            do_action('woocommerce_ajax_added_to_cart', $product_id);

            if (get_option('woocommerce_cart_redirect_after_add') === 'yes' ) {
                wc_add_to_cart_message(array($product_id => $quantity), true);
            }

            $this->ins_ajax_cart_reload();

        } else {
            $data = array(
                'error' => true,
                'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));
    
            echo wp_send_json($data);
        }
        wp_die();
    }

    // Ajax Cart Remove To cart
    public function ins_ajax_cart_item_remove() { 
        $product_id = $_POST['product_id'];  
        $variation_id = $_POST['variation_id'];  
        
        $cart = WC()->cart->get_cart();  

        foreach ($cart as $cart_item_key => $cart_item){
            if($cart_item['product_id'] == $product_id && $cart_item['variation_id'] == $variation_id ){ 
             
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
        $toggle_position_horizontal = insopt( 'toggle-position-horizontal' );
        $toggle_position_vertical = insopt( 'toggle-position-vertical' );
        $this->layout_class .= !empty($toggle_position_horizontal) ? 'ins-hori-'.$toggle_position_horizontal.' ' :  'ins-hori-right ';
        $this->layout_class .= !empty($toggle_position_vertical) ? 'ins-var-'.$toggle_position_vertical.' ' :  'ins-var-bottom '; 
        
        ob_start();
        if( $this->layout == 1 ||  $this->layout == 3):
        ?>
            <div class="ins-fixed-toogle <?php echo esc_attr( $this->layout_class ) ?>"> <?php echo do_action('ins_cart_toggle'); ?></div>
        <?php 
        endif; 

        if($this->layout == 2 ||  $this->layout == 3):
            
        ?>
        <div class="ins-checkout-popup <?php echo esc_attr( $this->layout_class ) ?>"> 
            <div class="ins-checkout-overlay"></div>
            <div class="ins-checkout-layout ins-checkout-layout-3 <?php echo esc_attr( $this->layout_class ) ?>">
                <?php require_once INS_INC_PATH .  $this->layouts_slug; ?>
            </div>
        </div> 
        <?php  
        endif; 
        $output = ob_get_clean();
        echo $output;
    } 
     
}


?>