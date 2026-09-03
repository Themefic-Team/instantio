<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Instantio_Options' ) ) {
	class Instantio_Options {
		private static $instance = null;

		/**
		 * Singleton instance
		 * @since 1.0.0
		 */
		public static function instance() {
			if ( self::$instance == null ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		public function __construct() {

			// constant define
			$this->tf_options_define();

			//load files
			$this->Ins_classes_load_files();

			//load options
			$this->Ins_load_options();

			//load taxonomy


			//enqueue scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'Ins_tf_options_admin_enqueue_scripts' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'Ins_tf_options_wp_enqueue_scripts' ) );
		}

		// constant define
		public function tf_options_define() {
			if ( ! defined( 'INSTANTIO_OPTIONS_PATH' ) ) {
				define( 'INSTANTIO_OPTIONS_PATH', plugin_dir_path( __FILE__ ) );
			}


		}

		public function Ins_tf_options_version() {
			return '2.3.0';
		}

		public function Ins_tf_options_file_path( $file_path = '' ) {
			return plugin_dir_path( __FILE__ ) . $file_path;
		}

		public function Ins_tf_options_file_url( $file_url = '' ) {
			return plugin_dir_url( __FILE__ ) . $file_url;
		}

		/**
		 * Load files
		 * @author Foysal
		 */
		public function Ins_classes_load_files() {
			// Settings Class
			require_once $this->Ins_tf_options_file_path( 'classes/Instantio_Settings.php' );

			// Field base class must load before the individual field implementations.
			require_once $this->Ins_tf_options_file_path( 'fields/INS_Fields.php' );
			$field_files = glob( $this->Ins_tf_options_file_path( 'fields/*/*.php' ) );
			if ( is_array( $field_files ) ) {
				foreach ( $field_files as $field_file ) {
					require_once $field_file;
				}
			}

		}

		/**
		 * Load Options
		 * @author Foysal
		 */
		public function Ins_load_options() {
			$license_status = apply_filters( 'instantio_checked_license_status', false );
			if ( $this->instantio_is_pro_active() && false !== $license_status ) {
				$options = glob( INS_PRO_ADMIN_PATH . '/tf-options/options/*.php' );
			} else {
				$options = glob( $this->Ins_tf_options_file_path( 'options/*.php' ) );
			}

			if ( ! empty( $options ) ) {
				foreach ( $options as $option ) {
					if ( file_exists( $option ) ) {
						require_once $option;
					}
				}
			}
		}



		/**
		 * Admin Enqueue scripts
		 * @author M Hemel Hasan
		 */
		public function Ins_tf_options_admin_enqueue_scripts( $screen ) {
			// var_dump($screen);
			global $post_type;
			$tf_options_screens = array( 'toplevel_page_wiopt', 'instantio_page_ins_dashboard', 'instantio_page_tf_license_info', 'instantio_page_ins_get_help', 'instantio_page_ins_whats_new', 'admin_page_ins-setup-wizard', 'instantio_page_ins-license-activation' );

			if ( in_array( $screen, $tf_options_screens ) ) {
				wp_enqueue_style( 'wp-color-picker' );
					wp_enqueue_style( 'instantio-fontawesome-4', $this->Ins_tf_options_file_url( 'assets/libs/font-awesome-4/css/font-awesome.min.css' ), array(), '4.7.0' );
					wp_enqueue_style( 'instantio-fontawesome-5', $this->Ins_tf_options_file_url( 'assets/libs/font-awesome-5/css/all.min.css' ), array(), '5.15.4' );
					wp_enqueue_style( 'instantio-fontawesome-6', $this->Ins_tf_options_file_url( 'assets/libs/font-awesome-6/css/all.min.css' ), array(), '6.4.2' );
					wp_enqueue_style( 'instantio-remixicon', $this->Ins_tf_options_file_url( 'assets/libs/remixicon/fonts/remixicon.css' ), array(), '2.5.0' );
					wp_enqueue_style( 'instantio-select2', $this->Ins_tf_options_file_url( 'assets/libs/select2/css/select2.min.css' ), array(), '4.1.0-rc.0' );
					wp_enqueue_style( 'instantio-flatpickr', $this->Ins_tf_options_file_url( 'assets/libs/flatpickr/flatpickr.min.css' ), array(), '4.6.13' );
				wp_enqueue_style( 'instantio-options-style', $this->Ins_tf_options_file_url( 'assets/css/tf-options.css' ), array(), $this->Ins_tf_options_version() );
				wp_enqueue_style( 'instantio-notyf-css', $this->Ins_tf_options_file_url( 'assets/libs/notyf/notyf.min.css' ), array(), $this->Ins_tf_options_version() );
			}

			//Js
				if ( in_array( $screen, $tf_options_screens ) ) {
					$setup_wizard_path    = $this->Ins_tf_options_file_path( 'assets/js/setup-wizard.js' );
					$setup_wizard_version = file_exists( $setup_wizard_path ) ? (string) filemtime( $setup_wizard_path ) : $this->Ins_tf_options_version();

						wp_enqueue_script( 'instantio-flatpickr', $this->Ins_tf_options_file_url( 'assets/libs/flatpickr/flatpickr.min.js' ), array( 'jquery' ), '4.6.13', true );
					wp_enqueue_script( 'instantio-select2', $this->Ins_tf_options_file_url( 'assets/libs/select2/js/select2.min.js' ), array( 'jquery' ), '4.1.0-rc.0', true );
					wp_enqueue_script( 'instantio-color-picker-alpha', $this->Ins_tf_options_file_url( 'assets/libs/wp-color-picker-alpha/wp-color-picker-alpha.js' ), array( 'jquery', 'wp-color-picker' ), $this->Ins_tf_options_version(), true );
						wp_enqueue_script( 'instantio-setup-wizard', $this->Ins_tf_options_file_url( 'assets/js/setup-wizard.js' ), array( 'jquery' ), $setup_wizard_version, true );
					wp_enqueue_script( 'instantio-notyf-js', $this->Ins_tf_options_file_url( 'assets/libs/notyf/notyf.min.js' ), array( 'jquery' ), $this->Ins_tf_options_version(), true );
				//dashboard
				if ( $screen == 'instantio_page_ins_dashboard' ) {
						wp_enqueue_script( 'instantio-dashboard-js', $this->Ins_tf_options_file_url( 'assets/js/dashboard.js' ), array( 'jquery' ), $this->Ins_tf_options_version(), true );
				}
					wp_enqueue_script( 'instantio-admin', $this->Ins_tf_options_file_url( 'assets/js/admin.js' ), array( 'jquery' ), $this->Ins_tf_options_version(), true );

				wp_enqueue_script( 'jquery-ui-autocomplete' );

				if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
					wp_enqueue_script( 'jquery-ui-sortable' );
				}
				wp_enqueue_media();
				wp_enqueue_editor();

					wp_enqueue_script( 'instantio-options', $this->Ins_tf_options_file_url( 'assets/js/ins-options.js' ), array( 'jquery' ), $this->Ins_tf_options_version(), true );

				wp_localize_script( 'instantio-options', 'instantio_options', array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce( 'instantio_options_nonce' ),
					'option_id' => 'wiopt',
				) );

				wp_localize_script( 'instantio-admin', 'instantio_admin', array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'ajax_nonce' => wp_create_nonce( 'instantio_ajax_nonce' )
				) );

			}
		}

		/**
		 * Enqueue scripts
		 * @author 
		 */
		public function Ins_tf_options_wp_enqueue_scripts() {
			wp_enqueue_style( 'instantio-fontawesome-4', $this->Ins_tf_options_file_url( 'assets/libs/font-awesome-4/css/font-awesome.min.css' ), array(), '4.7.0' );
			wp_enqueue_style( 'instantio-fontawesome-5', $this->Ins_tf_options_file_url( 'assets/libs/font-awesome-5/css/all.min.css' ), array(), '5.15.4' );
			wp_enqueue_style( 'instantio-fontawesome-6', $this->Ins_tf_options_file_url( 'assets/libs/font-awesome-6/css/all.min.css' ), array(), '6.4.2' );
			wp_enqueue_style( 'instantio-remixicon', $this->Ins_tf_options_file_url( 'assets/libs/remixicon/fonts/remixicon.css' ), array(), '2.5.0' );
		}

		/*
		 * Field Base
		 * @author 
		 */
		public function field( $field, $value, $settings_id = '', $parent = '' ) {
			if ( $field['type'] == 'repeater' ) {
				$id = ( ! empty( $settings_id ) ) ? $settings_id . '[' . $field['id'] . '][0]' . '[' . $field['id'] . ']' : $field['id'] . '[0]' . '[' . $field['id'] . ']';
			} else {
				$id = $settings_id . '[' . $field['id'] . ']';
			}

			$class = isset( $field['class'] ) ? $field['class'] : '';

			$badge_up = isset( $field['badge_up'] ) ? $field['badge_up'] : '';

			if ( $badge_up == true ) {
				$class .= ' tf-field-disable tf-field-upcoming';
			}
			$tf_meta_box_dep_value = get_post_meta( get_the_ID(), $settings_id, true );


			$depend = '';
			if ( ! empty( $field['dependency'] ) ) {

				$dependency = $field['dependency'];
				$depend_visible = '';
				$data_controller = '';
				$data_condition = '';
				$data_value = '';
				$data_global = '';

				if ( is_array( $dependency[0] ) ) {
					$data_controller = implode( '|', array_column( $dependency, 0 ) );
					$data_condition = implode( '|', array_column( $dependency, 1 ) );
					$data_value = implode( '|', array_column( $dependency, 2 ) );
					$data_global = implode( '|', array_column( $dependency, 3 ) );
					$depend_visible = implode( '|', array_column( $dependency, 4 ) );
				} else {
					$data_controller = ( ! empty( $dependency[0] ) ) ? $dependency[0] : '';
					$data_condition = ( ! empty( $dependency[1] ) ) ? $dependency[1] : '';
					$data_value = ( ! empty( $dependency[2] ) ) ? $dependency[2] : '';
					$data_global = ( ! empty( $dependency[3] ) ) ? $dependency[3] : '';
					$depend_visible = ( ! empty( $dependency[4] ) ) ? $dependency[4] : '';
				}

				$depend .= ' data-controller="' . esc_attr( $data_controller . $parent ) . '"';
				$depend .= ' data-condition="' . esc_attr( $data_condition ) . '"';
				$depend .= ' data-value="' . esc_attr( $data_value ) . '"';
				$depend .= ( ! empty( $data_global ) ) ? ' data-depend-global="true"' : '';

				$visible = 'tf-dependency-control';
				$visible = ( ! empty( $depend_visible ) ) ? ' tf-depend-visible' : ' tf-depend-hidden';
			}

			//field width
			$field_width = isset( $field['field_width'] ) && ! empty( $field['field_width'] ) ? esc_attr( $field['field_width'] ) : '100';
			if ( $field_width == '100' ) {
				$field_style = 'width:100%;';
			} else {
				$field_style = 'width:calc(' . $field_width . '% - 10px);';
			}
			?>

			<div class="tf-field tf-field-<?php echo esc_attr( $field['type'] ); ?> <?php echo esc_attr( $class ); ?> <?php echo esc_attr( ! empty( $visible ) ? $visible : '' ); ?>"
				<?php
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Attribute fragment is assembled above with escaped dynamic values.
				echo ! empty( $depend ) ? $depend : '';
				?> style="<?php echo esc_attr( $field_style ); ?>">

				<?php if ( ! empty( $field['label'] ) ) : ?>
					<label for="<?php echo esc_attr( $id ) ?>" class="tf-field-label">
						<?php echo esc_html( $field['label'] ) ?>
						<?php if ( $badge_up ) : ?>
							<div class="tf-csf-badge"><span class="tf-upcoming">
								<?php esc_html_e( 'Upcoming', 'instantio' ); ?>
								</span></div>
						<?php endif; ?>
					</label>
				<?php endif; ?>

				<?php if ( ! empty( $field['subtitle'] ) ) : ?>
					<span class="tf-field-sub-title">
						<?php echo wp_kses_post( $field['subtitle'] ) ?>
					</span>
				<?php endif; ?>

				<div class="tf-fieldset">
					<?php
					$fieldClass = 'Instantio_Field_' . ucfirst( $field['type'] );
					if ( class_exists( $fieldClass ) ) {
						$_field = new $fieldClass( $field, $value, $settings_id, $parent );
						$_field->render();
					} else {
						echo '<p>' . esc_html__( 'Field not found!', 'instantio' ) . '</p>';
					}
					?>
				</div>
				<?php if ( ! empty( $field['description'] ) ) : ?>
					<p class="description">
						<?php echo wp_kses_post( $field['description'] ) ?>
					</p>
				<?php endif; ?>
			</div>
			<?php
		}

		public function instantio_is_pro_active() {
			if ( is_plugin_active( 'wooinstant/wooinstant.php' ) && class_exists( 'WOOINS' ) ) {
				return true;
			}
			return false;
		}

	}
}

Instantio_Options::instance();
