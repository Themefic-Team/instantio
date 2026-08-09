<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'INS_radio' ) ) {
	class INS_radio extends INS_Fields {

		public function __construct( $field, $value = '', $settings_id = '', $parent_field = '' ) {
			parent::__construct( $field, $value, $settings_id, $parent_field );
		}

		public function render() {
			if ( isset( $this->field['options'] ) ) {
				$inline = ( isset( $this->field['inline'] ) && $this->field['inline'] ) ? 'tf-inline' : '';
				echo '<ul class="tf-radio-group ' . esc_attr( $inline ) . '">';
				foreach ( $this->field['options'] as $key => $value ) {
					$checked = $key == $this->value ? ' checked' : '';
					$field_name = $this->field_name() . '[' . $key . ']';
					// field_attributes() returns an attribute fragment with escaped keys and values.
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<li><input type="radio" id="' . esc_attr( $field_name ) . '" name="' . esc_attr( $this->field_name() ) . '" data-depend-id="' . esc_attr( $this->field['id'] . $this->parent_field ) . '" value="' . esc_attr( $key ) . '" ' . esc_attr( $checked ) . ' ' . $this->field_attributes() . '/><label for="' . esc_attr( $field_name ) . '">' . esc_html( $value ) . '</label></li>';
				}
				echo '</ul>';
			} else {
				// field_attributes() returns an attribute fragment with escaped keys and values.
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<input type="radio" id="' . esc_attr( $this->field_name() ) . '" name="' . esc_attr( $this->field_name() ) . '" data-depend-id="' . esc_attr( $this->field['id'] . $this->parent_field ) . '" value="1" ' . esc_attr( checked( $this->value, 1, false ) ) . ' ' . $this->field_attributes() . '/><label for="' . esc_attr( $this->field_name() ) . '">' . esc_html( $this->field['title'] ) . '</label>';
			}
		}
	}
}
