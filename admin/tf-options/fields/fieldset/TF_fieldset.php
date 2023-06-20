<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: fieldset
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'TF_fieldset' ) ) {
  class TF_fieldset extends TF_Fields {

    public function __construct( $field, $value = '', $settings_id = '',  $parent_field = '' ) {
      parent::__construct( $field, $value, $settings_id, $parent_field );
    }

    public function render() {

      echo '<div class="tf-fieldset-content" data-depend-id="'. esc_attr( $this->field['id'] ) .'">';

      foreach ( $this->field['fields'] as $field ) {
       

        $field_id      = ( isset( $field['id'] ) ) ? $field['id'] : '';
        $field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
        $field_value   = ( isset( $this->value[$field_id] ) ) ? $this->value[$field_id] : $field_default;
        $unique_id     = ( ! empty( $this->settings_id ) ) ? $this->settings_id .'['. $this->field['id'] .']' : $this->field['id'];
        
        // TF_Options::field( $field, $field_value, $unique_id, $this->parent_field );
        $tf_option = new TF_Options();
				$tf_option->field(  $field, $field_value, $unique_id, $this->parent_field); 

      } 
      echo '</div>'; 

    }
    public function sanitize() {
			// return wp_kses_post($this->value);
			return $this->value;
		}

  }
}
