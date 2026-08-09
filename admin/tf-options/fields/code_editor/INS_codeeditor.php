<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'INS_codeeditor' ) ) {
	class INS_codeeditor extends INS_Fields {

		public $editor_settings = array();

		public function __construct( $field, $value = '', $settings_id = '', $parent_field = '' ) {
			parent::__construct( $field, $value, $settings_id, $parent_field );
			$this->enqueue();

		}

		public function render() {
			$default_settings = array(
				'tabSize' => 4,
				'lineNumbers' => true,
				'theme' => 'default',
				'mode' => 'htmlmixed',
			);
			$default_settings = wp_parse_args( $this->editor_settings, $default_settings );

			$settings = ( ! empty( $this->field['settings'] ) ) ? $this->field['settings'] : array();
			$settings = wp_parse_args( $settings, $default_settings );

			?>
			<div class="tf-field-textarea tf-field-codearea">
				<?php
				// field_attributes() returns an attribute fragment with escaped keys and values.
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<textarea name="' . esc_attr( $this->field_name() ) . '"' . $this->field_attributes() . ' data-editor="' . esc_attr( wp_json_encode( $settings ) ) . '">' . esc_textarea( $this->value ) . '</textarea>';
				?>

			</div>
			<?php
		}

		public function enqueue() {

				// Read-only screen detection; no state change is performed from this value.
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$page = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';

			// Do not loads CodeMirror in revslider page.
			if ( in_array( $page, array( 'revslider' ) ) ) {
				return;
			}

			$core_settings = wp_enqueue_code_editor( array( 'type' => 'text/html' ) );
			if ( is_array( $core_settings ) && isset( $core_settings['codemirror'] ) ) {
				$this->editor_settings = $core_settings['codemirror'];
			}

		}

		public function sanitize() {
			return wp_kses_post( $this->value );
		}

	}
}
