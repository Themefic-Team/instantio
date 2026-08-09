<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'INS_textarea' ) ) {
	class INS_textarea extends INS_Fields {

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
