<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

/**
 * Field: text
 */
if ( ! class_exists( 'INS_gallery' ) ) {
	class INS_gallery extends INS_Fields {

		public function __construct( $field, $value = '', $settings_id = '', $parent_field = '' ) {
			parent::__construct( $field, $value, $settings_id, $parent_field );
		}

		public function render() {
			$type = ( ! empty( $this->field['type'] ) ) ? $this->field['type'] : 'text';

			$gallery_class = str_replace( array( '[', ']', '-' ), '_', $this->field_name() );
			echo '<div class="tf-fieldset-gallery-preview ' . esc_attr( $gallery_class ) . '">';
			if ( ! empty( $this->value ) ) {
				$INS_gallery_ids = explode( ',', $this->value );
				foreach ( $INS_gallery_ids as $key => $gallery_item_id ) {
					$image_url = wp_get_attachment_url( $gallery_item_id, 'full' );
					echo '<img src="' . esc_url( $image_url ) . '" alt="">';
				}
			}
			echo '
			</div>
			<div class="tf-fieldset-gallery">
			<a href="#" tf-field-name="' . esc_attr( $this->field_name() ) . '" class="tf-gallery-upload button button-primary button-large">' . esc_html( "Add Gallery", "instantio" ) . '</a>';
			if ( ! empty( $this->value ) ) {
				echo '<a href="#" tf-field-name="' . esc_attr( $this->field_name() ) . '" class="tf-gallery-edit button button-primary button-large ' . esc_attr( $gallery_class ) . '">' . esc_html__( 'Edit Gallery', 'instantio' ) . '</a><a href="#" tf-field-name="' . esc_attr( $this->field_name() ) . '" class="tf-gallery-remove button button-warning button-large ' . esc_attr( $gallery_class ) . '" style="display:inline-block">' . esc_html__( 'Clear', 'instantio' ) . '</a>';
			} else {
				echo '<a href="#" tf-field-name="' . esc_attr( $this->field_name() ) . '" class="tf-gallery-edit button button-primary button-large ' . esc_attr( $gallery_class ) . '" style="display:none">' . esc_html__( 'Edit Gallery', 'instantio' ) . '</a><a href="#" tf-field-name="' . esc_attr( $this->field_name() ) . '" class="tf-gallery-remove button button-warning button-large ' . esc_attr( $gallery_class ) . '" style="display:none">' . esc_html__( 'Clear', 'instantio' ) . '</a>';
			}
			echo '</div>
			<input type="hidden" name="' . esc_attr( $this->field_name() ) . '" id="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '"  />';
		}



	}
}
