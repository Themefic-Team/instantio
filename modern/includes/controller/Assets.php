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
        $ins_toggle_bg = !empty(insopt( 'wi-header-bg-colors' )['regular']) ? insopt( 'wi-header-bg-colors' )['regular'] : '#fffdfd'; 
        $ins_toggle_bg_hover = !empty(insopt( 'wi-header-bg-colors' )['hover']) ? insopt( 'wi-header-bg-colors' )['hover'] : '#fffdfd';
        $ins_toggle_border = !empty(insopt( 'wi-header-border-colors' )['regular']) ? insopt( 'wi-header-border-colors' )['regular'] : '#e9570a'; 
        $ins_toggle_border_hover = !empty(insopt( 'wi-header-border-colors' )['hover']) ? insopt( 'wi-header-border-colors' )['hover'] : '#e9570a';
        $ins_toggle_icon = !empty(insopt( 'ins-tog-icon-colors' )['regular']) ? insopt( 'ins-tog-icon-colors' )['regular'] : '#e9570a';
        $ins_toggle_icon_hover = !empty(insopt( 'ins-tog-icon-colors' )['hover']) ? insopt( 'ins-tog-icon-colors' )['hover'] : '#e9570a';
        $ins_toggle_icon_size = !empty(insopt( 'wi-header-icon-size' )['width']) ? insopt( 'wi-header-icon-size' )['width'].'px' : '24px';
        $ins_toggle_item_bg = !empty(insopt( 'ins-tog-item-bg' )['regular']) ? insopt( 'ins-tog-item-bg' )['regular'] : '#ffd200';
        $ins_toggle_item_bg_hover = !empty(insopt( 'ins-tog-item-bg' )['hover']) ? insopt( 'ins-tog-item-bg' )['hover'] : '#ffd200';
        $ins_toggle_item_color = !empty(insopt( 'wi-header-text-colors' )['regular']) ? insopt( 'wi-header-text-colors' )['regular'] : '#000';
        $ins_toggle_item_color_hover = !empty(insopt( 'wi-header-text-colors' )['hover']) ? insopt( 'wi-header-text-colors' )['hover'] : '#000';
        $ins_toggle_item_size = !empty(insopt( 'wi-header-text-size' )['font-size']) ? insopt( 'wi-header-text-size' )['font-size'].'px' : '14px';
        if( insopt( 'ins-toggler' ) == 'tog-2' ){
            $ins_toggle_item_color = '#000';
            $ins_toggle_item_color_hover = '#000';
            $ins_toggle_item_bg = '#fff';
            $ins_toggle_item_bg_hover = '#fff';
        }
        $output = '
        :root {
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
        wp_add_inline_style( 'ins-style', $output );
    }

    public function enqueue_custom_js_scripts(){

        $output = '';
        $hide_toggler = insopt( 'hide-toggler' );
        $auto_open_toggle = insopt( 'auto-tog-panel' ); 
        $output .=  isset($hide_toggler) ? 'var hide_toggler = '.$hide_toggler.';' : '';
        $output .=  isset($auto_open_toggle) && $auto_open_toggle == true ? 'var auto_open_toggle = true;' : 'var auto_open_toggle = false;';
         
		wp_add_inline_script( 'ins-script', $output );
    }

    
}

?>