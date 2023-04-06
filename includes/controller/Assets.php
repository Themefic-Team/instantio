<?php  
namespace INS\Controller;

class Assets {
    public function __construct() { 
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_custom_js_scripts' ), 99999 ); 
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_custom_css_scripts' ), 99999 ); 
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 ); 
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) ); 
    } 
    public function enqueue_scripts() { 
        wp_enqueue_style( 'ins-style', INS_ASSETS_URL.'/app/css/instantio-style.css', array(), INSTANTIO_VERSION ); 
        // wp_enqueue_style( 'ins-style-modern', INS_ASSETS_URL.'/app/css/instantio-modern-style.css', array(), INSTANTIO_VERSION ); 
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

    public function enqueue_custom_css_scripts(){
        $ins_toggle_bg = !empty(insopt( 'ins-toggle-tab' )['wi-header-bg-colors']['regular']) ? insopt( 'ins-toggle-tab' )['wi-header-bg-colors']['regular'] : '#e9570a'; 
        $ins_toggle_bg_hover = !empty(insopt( 'ins-toggle-tab' )['wi-header-bg-colors']['hover']) ? insopt( 'ins-toggle-tab' )['wi-header-bg-colors']['hover'] : '#fffdfd';
        $ins_toggle_border = !empty(insopt( 'ins-toggle-tab' )['wi-header-border-colors']['regular']) ? insopt( 'ins-toggle-tab' )['wi-header-border-colors']['regular'] : '#e9570a'; 
        $ins_toggle_border_hover = !empty(insopt( 'ins-toggle-tab' )['wi-header-border-colors']['hover']) ? insopt( 'ins-toggle-tab' )['wi-header-border-colors']['hover'] : '#e9570a';
        $ins_toggle_icon = !empty(insopt( 'ins-toggle-tab' )['ins-tog-icon-colors']['regular']) ? insopt( 'ins-toggle-tab' )['ins-tog-icon-colors']['regular'] : '#fff';
        $ins_toggle_icon_hover = !empty(insopt( 'ins-toggle-tab' )['ins-tog-icon-colors']['hover']) ? insopt( 'ins-toggle-tab' )['ins-tog-icon-colors']['hover'] : '#e9570a';
        $ins_toggle_icon_size = !empty(insopt( 'ins-toggle-tab' )['wi-header-icon-size']) ? insopt( 'ins-toggle-tab' )['wi-header-icon-size'].'px' : '24px';
        $ins_toggle_item_bg = !empty(insopt( 'ins-toggle-tab' )['ins-tog-item-bg']['regular']) ? insopt( 'ins-toggle-tab' )['ins-tog-item-bg']['regular'] : '#ffd200';
        $ins_toggle_item_bg_hover = !empty(insopt( 'ins-toggle-tab' )['ins-tog-item-bg']['hover']) ? insopt( 'ins-toggle-tab' )['ins-tog-item-bg']['hover'] : '#ffd200';
        $ins_toggle_item_color = !empty(insopt( 'ins-toggle-tab' )['wi-header-text-colors']['regular']) ? insopt( 'ins-toggle-tab' )['wi-header-text-colors']['regular'] : '#000';
        $ins_toggle_item_color_hover = !empty(insopt( 'ins-toggle-tab' )['wi-header-text-colors']['hover']) ? insopt( 'ins-toggle-tab' )['wi-header-text-colors']['hover'] : '#000';
        $ins_toggle_item_size = !empty(insopt( 'ins-toggle-tab' )['wi-header-text-size']) ? insopt( 'ins-toggle-tab' )['wi-header-text-size'].'px' : '14px';
        if( insopt( 'ins-toggler' ) == 'tog-2' ){
            $ins_toggle_item_color = '#000';
            $ins_toggle_item_color_hover = '#000';
            $ins_toggle_item_bg = '#fff';
            $ins_toggle_item_bg_hover = '#fff';
        }
        $output = '';
        $output .= '
        :root {
            --ins_checkout_theme: #e9570a;
            --ins_toggle_bg: '.$ins_toggle_bg.';
            --ins_toggle_hover_bg: '.$ins_toggle_bg_hover.';
            --ins_toggle_border: '.$ins_toggle_border.';
            --ins_toggle_hover_border: '.$ins_toggle_border_hover.';
            --ins_toggle_icon: '.$ins_toggle_icon.';
            --ins_toggle_icon_hover: '.$ins_toggle_icon_hover.';
            --ins_toggle_icon_size: '.$ins_toggle_icon_size.';
            --ins_toggle_item_bg: '.$ins_toggle_item_bg.';
            --ins_toggle_item_bg_hover: '.$ins_toggle_item_bg_hover.';
            --ins_toggle_item_color: '.$ins_toggle_item_color.';
            --ins_toggle_item_color_hover: '.$ins_toggle_item_color_hover.';
            --ins_toggle_item_size: '.$ins_toggle_item_size.'; 
          } 
        ';  
        // ins-toggle-panel-tab
        $ins_panel_border_option = !empty(insopt( 'ins-toggle-panel-tab' )['ins_panel_border_option']) ? insopt( 'ins-toggle-panel-tab' )['ins_panel_border_option'] : '000';
        echo $ins_panel_border_option;
        $output .=  $ins_panel_border_option;
        if($ins_panel_border_option == '1'){
            $ins_panel_border_top = !empty(insopt( 'ins_panel_border' )['ins-toggle-panel-tab']['ins-panel-border-top']) ? insopt( 'ins-toggle-panel-tab' )['ins-panel-border-top'] : '0';
            $ins_panel_border_right = !empty(insopt( 'ins_panel_border' )['ins-toggle-panel-tab']['ins-panel-border-right']) ? insopt( 'ins-toggle-panel-tab' )['ins-panel-border-right'] : '0';
            $ins_panel_border_bottom = !empty(insopt( 'ins_panel_border' )['ins-toggle-panel-tab']['ins-panel-border-bottom']) ? insopt( 'ins-toggle-panel-tab' )['ins-panel-border-bottom'] : '0';
            $ins_panel_border_left = !empty(insopt( 'ins_panel_border' )['ins-toggle-panel-tab']['ins-panel-border-left']) ? insopt( 'ins-toggle-panel-tab' )['ins-panel-border-left'] : '0';
            $ins_panel_border_color = !empty(insopt( 'ins_panel_border' )['ins-toggle-panel-tab']['ins_panel_border_color']) ? insopt( 'ins-toggle-panel-tab' )['ins_panel_border_color'] : 'transparent';
             
            $output .= '
            
                .ins-checkout-layout {
                    border-left: '.$ins_panel_border_left.'px solid !important; 
                    border-right: '.$ins_panel_border_right.'px solid !important;
                    border-top: '.$ins_panel_border_top.'px solid !important; 
                    border-bottom: '.$ins_panel_border_bottom.'px solid !important;
                    border-color: '.$ins_panel_border_color.' !important;
                }
            ';
        }
        wp_add_inline_style( 'ins-style', $output );
    }

    public function enqueue_custom_js_scripts(){

        $output = ''; 
        $auto_open_toggle = insopt( 'auto-tog-panel' ); 
        $cart_fly_anim = isset(insopt( 'cart-fly' )['cart-fly-anim']) ? insopt( 'cart-fly' )['cart-fly-anim'] : false; 
        $cart_fly_icon = isset(insopt( 'cart-fly' )['cart-fly-icon']) ? insopt( 'cart-fly' )['cart-fly-icon'] : false;  
        $ins_empty_cart = !empty(insopt( 'ins-toggle-tab' )['ins-cart-emty-hide']) ? insopt( 'ins-toggle-tab' )['ins-cart-emty-hide'] : false;
        if($cart_fly_icon == true){
            $cart_icon = !empty(insopt( 'cart-icon' )) ? insopt( 'cart-icon' ) : 'shopping-bag';
            $cart_fly_icon = instantio_svg_icon($cart_icon);
        }else{
            $cart_fly_icon = false;
        }
        $output .=  isset($ins_empty_cart) ? 'var hide_toggler = '.$ins_empty_cart.';' : 'var hide_toggler = false;';
        $output .=  isset($auto_open_toggle) && $auto_open_toggle == true ? 'var auto_open_toggle = true;' : 'var auto_open_toggle = false;';
        $output .=  isset($cart_fly_anim) && $cart_fly_anim == true ? 'var cart_fly_anim = true;' : 'var cart_fly_anim = false;';
        $output .=  isset($cart_fly_icon) && $cart_fly_icon != false ? 'var cart_fly_icon = `'.$cart_fly_icon.'`' : 'var cart_fly_icon = false;';  
        
         
		wp_add_inline_script( 'ins-script', $output );
    }

    
}

?>