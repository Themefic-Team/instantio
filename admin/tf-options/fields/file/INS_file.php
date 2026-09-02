<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Instantio_Field_File' ) ) {
	class Instantio_Field_File extends Instantio_Field {

		public function __construct( $field, $value = '', $settings_id = '', $parent_field = '' ) {
			parent::__construct( $field, $value, $settings_id, $parent_field );
		}

		public function render() {
			$type = ( ! empty( $this->field['type'] ) ) ? $this->field['type'] : 'text';
			$placeholder = ( ! empty( $this->field['placeholder'] ) ) ? 'placeholder="' . esc_attr( $this->field['placeholder'] ) . '"' : '';
			// Placeholder and field_attributes() are pre-escaped attribute fragments.
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $this->field_name() ) . '" id="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '" ' . $placeholder . ' ' . $this->field_attributes() . ' class="itinerary-fonts-file" multiple />';
		}

	}
}
