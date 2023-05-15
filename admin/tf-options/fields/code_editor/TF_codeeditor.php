<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TF_codeeditor' ) ) {
	class TF_codeeditor extends TF_Fields {

        public $version = '5.62.2';
        public $cdn_url = 'https://cdn.jsdelivr.net/npm/codemirror@';

		public function __construct( $field, $value = '', $settings_id = '', $parent_field = '') {
			parent::__construct( $field, $value, $settings_id, $parent_field );
		}

		public function render() {
            $default_settings = array(
                'tabSize'       => 2,
                'lineNumbers'   => true,
                'theme'         => 'default',
                'mode'          => 'htmlmixed',
                'cdnURL'        => $this->cdn_url . $this->version,
              );

            $settings = ( ! empty( $this->field['settings'] ) ) ? $this->field['settings'] : array();
            $settings = wp_parse_args( $settings, $default_settings );

        ?>
        <div class="tf-field-textarea">
            <!-- <textarea name="<?php // echo $this->field_name(); ?>" id="<?php  // echo $tf_editor_unique_id; ?>" class="<?php //echo esc_attr( $parent_class )  ?> tf_wp_editor" cols="30" data-count-id=""><?php // echo $this->value; ?></textarea> -->
            <?php
                echo '<textarea name="'. esc_attr( $this->field_name() ) .'"'. $this->field_attributes() .' data-editor="'. esc_attr( json_encode( $settings ) ) .'">'. $this->value .'</textarea>';
            ?>

        </div>
       <?php
		}
        
        public function enqueue() {

            $page = ( ! empty( $_GET[ 'page' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'page' ] ) ) : '';
      
            // Do not loads CodeMirror in revslider page.
            if ( in_array( $page, array( 'revslider' ) ) ) { return; }
      
            if ( ! wp_script_is( 'tf-codemirror' ) ) {
              wp_enqueue_script( 'tf-codemirror', esc_url( $this->cdn_url . $this->version .'/lib/codemirror.min.js' ), array( 'tf' ), $this->version, true );
              wp_enqueue_script( 'tf-codemirror-loadmode', esc_url( $this->cdn_url . $this->version .'/addon/mode/loadmode.min.js' ), array( 'tf-codemirror' ), $this->version, true );
            }
      
            if ( ! wp_style_is( 'tf-codemirror' ) ) {
              wp_enqueue_style( 'tf-codemirror', esc_url( $this->cdn_url . $this->version .'/lib/codemirror.min.css' ), array(), $this->version );
            }
      
        }

        public function sanitize() {
			return wp_kses_post($this->value);
		}
	}
}