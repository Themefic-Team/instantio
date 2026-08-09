<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'INS_color' ) ) {
	class INS_color extends INS_Fields {

		public function __construct( $field, $value = '', $settings_id = '', $parent_field = '' ) {
			parent::__construct( $field, $value, $settings_id, $parent_field );
		}

		public function render() {
			// $color_value = $_value = ( ! is_array( $this->value ) ) ? unserialize( $this->value ) : $this->value; ;
			$color_value = $_value = $this->value;

			if ( isset( $this->field['colors'] ) && $this->field['multiple'] ) {
				$inline = ( isset( $this->field['inline'] ) && $this->field['inline'] ) ? 'tf-inline' : '';
				echo '<ul class="tf-color-group ' . esc_attr( $inline ) . '">';

				foreach ( $this->field['colors'] as $key => $value ) {
					$_value = ( ! empty( $color_value[ $key ] ) ) ? $color_value[ $key ] : '';
					echo '<li>';
					$field_name = $this->field_name() . '[' . $key . ']';
					echo '<label for="' . esc_attr( $field_name ) . '">' . esc_html( $value ) .'</label>';
					// field_attributes() returns an attribute fragment with escaped keys and values.
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_name ) . '" value="' . esc_attr( $_value ) . '" class="tf-color" data-alpha-enabled="true" '. $this->field_attributes() .'/>';
					echo '</li>';
				}
				echo '</ul>';
			} else {
				// field_attributes() returns an attribute fragment with escaped keys and values.
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<input type="text" name="' . esc_attr( $this->field_name() ) . '" id="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $color_value ) . '" class="tf-color" data-alpha-enabled="true" '. $this->field_attributes() .'/>';
			}
		}

		public function sanitize() {
			// return wp_kses_post($this->value);
			return $this->value;
		}

	}
}
