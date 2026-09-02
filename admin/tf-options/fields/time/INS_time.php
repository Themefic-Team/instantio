<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Instantio_Field_Time' ) ) {
	class Instantio_Field_Time extends Instantio_Field {

		public function __construct( $field, $value = '', $settings_id = '', $parent_field = '' ) {
			parent::__construct( $field, $value, $settings_id, $parent_field );
		}

		public function render() {

			$args = wp_parse_args( $this->field, array(
				'format' => 'h:i K',
				'placeholder' => esc_html__( 'Select Time', 'instantio' ),
			) );

			$format = ( ! empty( $args['format'] ) ) ? $args['format'] : 'Y-m-d';
			$placeholder = ( ! empty( $args['placeholder'] ) ) ? $args['placeholder'] : esc_html__( 'Select Date', 'instantio' );
			?>
			<input type="text" name="<?php echo esc_attr( $this->field_name() ); ?>"
				placeholder="<?php echo esc_attr( $placeholder ) ?>" value="<?php echo esc_attr( $this->value ); ?>"
				class="flatpickr " data-format="<?php echo esc_attr( $format ); ?>" <?php
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Pre-escaped attribute fragment.
				echo $this->field_attributes();
				?> />
			<i class="fa-regular fa-clock"></i>
			<?php
		}


	}
}
