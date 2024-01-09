<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'INS_switch' ) ) {
	class INS_switch extends INS_Fields {

		public function __construct( $field, $value = '', $settings_id = '', $parent_field = '' ) {
			parent::__construct( $field, $value, $settings_id, $parent_field );
		}

		public function render() {

			$args = wp_parse_args( $this->field, array(
				'label_on' => esc_html__( 'On', 'instantio' ),
				'label_off' => esc_html__( 'Off', 'instantio' ),
			) );

			$on = ( ! empty( $args['label_on'] ) ) ? $args['label_on'] : esc_html__( 'On', 'instantio' );
			$off = ( ! empty( $args['label_off'] ) ) ? $args['label_off'] : esc_html__( 'Off', 'instantio' );
			$width = ( ! empty( $this->field['width'] ) ) ? ' style="width: ' . esc_attr( $this->field['width'] ) . 'px;"' : '';
			?>
			<label for="<?php echo esc_attr( $this->field_name() ); ?>" class="tf-switch-label" <?php echo wp_kses_post( $width ); ?>>
				<input type="checkbox" id="<?php echo esc_attr( $this->field_name() ); ?>"
					name="<?php echo esc_attr( $this->field_name() ); ?>" value="<?php echo $this->value; ?>"
					data-depend-id="<?php echo esc_attr( $this->field['id'] ); ?><?php echo esc_attr( $this->parent_field ); ?>"
					class="tf-switch" <?php checked( $this->value, 1 ); ?> 			<?php echo $this->field_attributes() ?> />
				<span class="tf-switch-slider">
					<span class="tf-switch-on">
						<?php echo esc_html( $on ); ?>
					</span>
					<span class="tf-switch-off">
						<?php echo esc_html( $off ); ?>
					</span>
				</span>
			</label>
			<?php
		}

	}
}