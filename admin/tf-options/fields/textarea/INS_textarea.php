<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Instantio_Field_Textarea' ) ) {
	class Instantio_Field_Textarea extends Instantio_Field {

		public function __construct( $field, $value = '', $settings_id = '', $parent_field = '' ) {
			parent::__construct( $field, $value, $settings_id, $parent_field );
		}

		public function render() {
			$placeholder = ( ! empty( $this->field['placeholder'] ) ) ? ' placeholder="' . esc_attr( $this->field['placeholder'] ) . '"' : '';
			// Placeholder and field_attributes() are pre-escaped attribute fragments.
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<textarea name="' . esc_attr( $this->field_name() ) . '" id="' . esc_attr( $this->field_name() ) . '"' . $placeholder . ' ' . $this->field_attributes() . '>' . esc_textarea( $this->value ) . '</textarea>';
		}

	}
}
