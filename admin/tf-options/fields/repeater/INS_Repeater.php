<?php

// Cannot access directly.
defined( 'ABSPATH' ) || exit;

/**
 *
 * Field: repeater
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'INS_Repeater' ) ) {
	class INS_Repeater extends INS_Fields {
		public function __construct( $field, $value = '', $settings_id = '', $parent_field = '' ) {
			parent::__construct( $field, $value, $settings_id, $parent_field );
		}
		public function render() {
			$label = ( ! empty( $this->field['label'] ) ) ? $this->field['label'] : '';
			?>
			<div id="tf-repeater-1" class="tf-repeater <?php echo $this->field['id']; ?>">
				<div class="tf-repeater-wrap tf-repeater-wrap-<?php echo $this->field['id']; ?>">
					<?php if ( ! empty( $this->value ) ) :
						$num = 0;

						if ( ! is_array( $this->value ) ) {
							$INS_rep_value = preg_replace_callback( '!s:(\d+):"(.*?)";!', function ($match) {
								return ( $match[1] == strlen( $match[2] ) ) ? $match[0] : 's:' . strlen( $match[2] ) . ':"' . $match[2] . '";';
							}, $this->value );

							$data = unserialize( $INS_rep_value );
						} else {
							$data = $this->value;
						}
						if ( is_array( $data ) ) :
							foreach ( $data as $key => $value ) :
								$INS_repater_default_value = reset( $value );
								if ( $this->field['id'] == "room" ) {
									$INS_repater_default_value = $value['title'];
								}
								?>
								<div class="tf-single-repeater tf-single-repeater-<?php echo $this->field['id']; ?>">
									<input type="hidden" name="INS_parent_field" value="<?php echo $this->parent_field; ?>">
									<input type="hidden" name="INS_repeater_count" value="<?php echo $key; ?>">
									<input type="hidden" name="INS_current_field" value="<?php echo $this->field['id']; ?>">
									<div class="tf-repeater-header">
										<span class="tf-repeater-icon tf-repeater-icon-collapse">
											<i class="fa-solid fa-angle-down"></i>
										</span>
										<span class="tf-repeater-title">
											<?php echo ! empty( $INS_repater_default_value ) && gettype( $INS_repater_default_value ) == "string" ? $INS_repater_default_value : esc_html( $label, "instantio" ) ?>
										</span>
										<div class="tf-repeater-icon-absulate">
											<span class="tf-repeater-icon tf-repeater-icon-move">
												<i class="fa-solid fa-up-down-left-right"></i>
											</span>
											<?php
											if ( empty( $this->field['drag_only'] ) || ! $this->field['drag_only'] ) {
												?>
												<span class="tf-repeater-icon tf-repeater-icon-clone" data-repeater-max="<?php if ( isset( $this->field['max'] ) ) {
													echo esc_attr( $this->field['max'] );
												} ?>">
													<i class="fa-solid fa-copy"></i>
												</span>
												<span class="tf-repeater-icon tf-repeater-icon-delete">
													<i class="fa-solid fa-trash"></i>
												</span>
											<?php } ?>
										</div>
									</div>
									<div class="tf-repeater-content-wrap hide" style="display: none">
										<?php
										foreach ( $this->field['fields'] as $re_field ) :

											if ( $re_field['type'] == 'editor' ) {
												$re_field['wp_editor'] = 'wp_editor';
											}
											if ( $re_field['type'] == 'select2' ) {
												$re_field['select2'] = 'select2';
											}

											if ( ! empty( $this->parent_field ) ) {
												$parent_field = $this->parent_field . '[' . $this->field['id'] . '][' . $key . ']';
											} else {
												$parent_field = '[' . $this->field['id'] . '][' . $key . ']';
											}

											$id = ( ! empty( $this->settings_id ) ) ? $this->settings_id . '[' . $this->field['id'] . '][00]' . '[' . $re_field['id'] . ']' : $this->field['id'] . '[00]' . '[' . $re_field['id'] . ']';
											if ( isset( $INS_meta_box_value[ $id ] ) ) {
												$value = isset( $INS_meta_box_value[ $id ] ) ? $INS_meta_box_value[ $id ] : '';
											} else {
												$value = ( isset( $re_field['id'] ) && isset( $data[ $key ][ $re_field['id'] ] ) ) ? $data[ $key ][ $re_field['id'] ] : '';
											}

											$INS_option = new Ins_TF_Options();
											$INS_option->field( $re_field, $value, $this->settings_id, $parent_field );
										endforeach;
										$num++;
										?>
									</div>
								</div>
							<?php endforeach; endif; endif; ?>
				</div>
				<div class=" tf-single-repeater-clone tf-single-repeater-clone-<?php if ( isset( $this->field['id'] ) ) {
					echo esc_attr( $this->field['id'] );
				} ?>">
					<div class="tf-single-repeater tf-single-repeater-<?php echo $this->field['id']; ?>">

						<input type="hidden" name="INS_parent_field" value="<?php if ( isset( $this->parent_field ) ) {
							echo esc_attr( $this->parent_field );
						} ?>">
						<input type="hidden" name="INS_repeater_count" value="0">
						<input type="hidden" name="INS_current_field" value="<?php if ( isset( $this->field['id'] ) ) {
							echo esc_attr( $this->field['id'] );
						} ?>">

						<input type="hidden" name="INS_origin_field" value="<?php if ( isset( $this->field['origin'] ) ) {
							echo esc_attr( $this->field['origin'] );
						} ?>">

						<div class="tf-repeater-header">
							<span class="tf-repeater-icon tf-repeater-icon-collapse">
								<i class="fa-solid fa-angle-up"></i>
							</span>
							<span class="tf-repeater-title">
								<?php if ( isset( $this->field['label'] ) ) {
									echo esc_html( $this->field['label'], "instantio" );
								} ?>
							</span>
							<div class="tf-repeater-icon-absulate">
								<span class="tf-repeater-icon tf-repeater-icon-move">
									<i class="fa-solid fa-up-down-left-right"></i>
								</span>
								<?php
								if ( empty( $this->field['drag_only'] ) || ! $this->field['drag_only'] ) {
									?>
									<span class="tf-repeater-icon tf-repeater-icon-clone" data-repeater-max="<?php if ( isset( $this->field['max'] ) ) {
										echo esc_attr( $this->field['max'] );
									} ?>">
										<i class="fa-solid fa-copy"></i>
									</span>
									<span class="tf-repeater-icon tf-repeater-icon-delete">
										<i class="fa-solid fa-trash"></i>
									</span>
								<?php } ?>
							</div>
						</div>
						<div class="tf-repeater-content-wrap">

							<?php foreach ( $this->field['fields'] as $key => $re_field ) {
								if ( ! empty( $this->parent_field ) ) {
									$parent = $this->parent_field . '[' . $this->field['id'] . '][00]';
								} else {
									$parent = '[' . $this->field['id'] . '][00]';
								}
								$id = ( ! empty( $this->settings_id ) ) ? $this->settings_id . '[' . $this->field['id'] . '][00]' . '[' . $re_field['id'] . ']' : $this->field['id'] . '[00]' . '[' . $re_field['id'] . ']';
								$default = isset( $re_field['default'] ) ? $re_field['default'] : '';
								$value = isset( $INS_meta_box_value[ $id ] ) ? $INS_meta_box_value[ $id ] : $default;
								$INS_option = new Ins_TF_Options();
								$INS_option->field( $re_field, $value, '_____' . $this->settings_id, $parent );
							} ?>
						</div>
					</div>

				</div>
				<?php
				if ( empty( $this->field['drag_only'] ) || ! $this->field['drag_only'] ) {
					?>
					<div class="tf-repeater-add tf-repeater-add-<?php if ( isset( $this->field['id'] ) ) {
						echo esc_attr( $this->field['id'] );
					} ?>">

						<span data-repeater-id="<?php if ( isset( $this->field['id'] ) ) {
							echo esc_attr( $this->field['id'] );
						} ?>" data-repeater-max="<?php if ( isset( $this->field['max'] ) ) {
							 echo esc_attr( $this->field['max'] );
						 } ?>" class="tf-repeater-icon tf-repeater-icon-add tf-repeater-add-<?php if ( isset( $this->field['id'] ) ) {
							  echo esc_attr( $this->field['id'] );
						  } ?>">
							<?php
							if ( isset( $this->field['button_title'] ) && ! empty( $this->field['button_title'] ) ) {
								echo $this->field['button_title'];
							} else {
								echo '<i class="fa-solid fa-plus"></i>';
							}
							?>

						</span>
					</div>
				<?php } ?>
			</div>
			<?php

		}
		public function sanitize() {
			// return wp_kses_post($this->value);
			return $this->value;
		}
		// public function enqueue() {

		// 	if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
		// 		wp_enqueue_script( 'jquery-ui-sortable' );
		// 	}

		// }
	}
}