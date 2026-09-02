<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Instantio_Field_Callback' ) ) {
	class Instantio_Field_Callback extends Instantio_Field {

		public function __construct( $field, $value = '', $settings_id = '', $parent_field = '' ) {
			parent::__construct( $field, $value, $settings_id, $parent_field );
		}

		public function render() {
			if ( isset( $this->field['function'] ) && is_callable( $this->field['function'] ) ) {
				call_user_func( $this->field['function'] );
			}
		}

	}
}
