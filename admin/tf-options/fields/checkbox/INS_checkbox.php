<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'INS_checkbox' ) ) {
	class INS_checkbox extends INS_Fields {

		public function __construct( $field, $value = '', $settings_id = '', $parent_field = '' ) {
			parent::__construct( $field, $value, $settings_id, $parent_field );
		}

		public function render() {
			//added 'options_callback' callback support @ah
			if ( isset( $this->field['options_callback'] ) && is_callable( $this->field['options_callback'] ) ) {
				$this->field['options'] = call_user_func( $this->field['options_callback'] );
			}
			if ( isset( $this->field['options'] ) ) {
				$inline = ( isset( $this->field['inline'] ) && $this->field['inline'] ) ? 'tf-inline' : '';
				echo '<ul class="tf-checkbox-group ' . esc_attr( $inline ) . '">';
				foreach ( $this->field['options'] as $key => $value ) {
					$checked = ( is_array( $this->value ) && in_array( $key, $this->value, true ) ) ? ' checked' : '';
					$field_name = $this->field_name() . '[' . $key . ']';
					if ( $key !== '' ) {
						// field_attributes() returns an attribute fragment with escaped keys and values.
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo '<li><input type="checkbox" id="' . esc_attr( $field_name ) . '" name="' . esc_attr( $this->field_name() ) . '[]" data-depend-id="' . esc_attr( $this->field['id'] ) . '" class="tf-group-checkbox" value="' . esc_attr( $key ) . '" ' . esc_attr( $checked ) . ' ' . $this->field_attributes() . '/><label for="' . esc_attr( $field_name ) . '">' . esc_html( $value ) . '</label></li>';
					} else {
						//disabled checkbox
						// field_attributes() returns an attribute fragment with escaped keys and values.
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo '<li><input type="checkbox" id="' . esc_attr( $field_name ) . '" name="' . esc_attr( $this->field_name() ) . '[]" data-depend-id="' . esc_attr( $this->field['id'] ) . '" class="tf-group-checkbox" value="' . esc_attr( $key ) . '" ' . esc_attr( $checked ) . ' ' . $this->field_attributes() . ' disabled/><label for="' . esc_attr( $field_name ) . '">' . esc_html( $value ) . '</label></li>';
					}
				}
				echo '</ul>';
			} else {
				// field_attributes() returns an attribute fragment with escaped keys and values.
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<input type="checkbox" id="' . esc_attr( $this->field_name() ) . '" name="' . esc_attr( $this->field_name() ) . '" value="1" ' . esc_attr( checked( $this->value, 1, false ) ) . ' ' . $this->field_attributes() . '/><label for="' . esc_attr( $this->field_name() ) . '">' . esc_html( $this->field['title'] ) . '</label>';
			}
		}
		public function sanitize() {
			$value = ( is_array( $this->value ) ) ? array_map( 'sanitize_text_field', $this->value ) : sanitize_text_field( $this->value );

			return $value;
		}
	}
}
