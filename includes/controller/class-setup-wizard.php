<?php
defined( 'ABSPATH' ) || exit;
/**
 * Setup Wizard Class
 * @since 3.0.0
 * @author M Hemel Hasan
 */
if ( ! class_exists( 'Instantio_Setup_Wizard' ) ) {
	class Instantio_Setup_Wizard {

		private static $instance = null;
		private static $current_step = null;

		/**
		 * Singleton instance
		 * @since 3.0.0
		 */
		public static function instance() {
			if ( self::$instance == null ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		public function __construct() {
			add_action( 'admin_menu', [ $this, 'tf_wizard_menu' ], 100 );
			add_filter( 'woocommerce_enable_setup_wizard', '__return_false' );
			add_action( 'admin_init', [ $this, 'tf_activation_redirect' ] );
			add_action( 'wp_ajax_instantio_setup_wizard_submit', [ $this, 'instantio_setup_wizard_submit_ajax' ] );
			add_action( 'in_admin_header', [ $this, 'remove_notice' ], 1000 );

				// Read-only wizard routing; no state change is performed from this value.
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				self::$current_step = isset( $_GET['step'] ) ? sanitize_key( wp_unslash( $_GET['step'] ) ) : 'welcome';
		}

		/**
		 * Add wizard submenu
		 */
		public function tf_wizard_menu() {

			if ( current_user_can( 'manage_options' ) ) {
				add_submenu_page(
					' ',
					esc_html__( 'Instantio Setup Wizard', 'instantio' ),
					esc_html__( 'Instantio Setup Wizard', 'instantio' ),
					'manage_options',
					'ins-setup-wizard',
					[ $this, 'ins_wizard_page' ],
					99
				);
			}
		}

		/**
		 * Remove all notice in setup wizard page
		 */
		public function remove_notice() {
				// Read-only screen detection; no state change is performed from this value.
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				if ( isset( $_GET['page'] ) && 'ins-setup-wizard' === sanitize_key( wp_unslash( $_GET['page'] ) ) ) {
				remove_all_actions( 'admin_notices' );
				remove_all_actions( 'all_admin_notices' );
			}
		}

		/**
		 * Setup wizard page
		 */
		public function ins_wizard_page() {
			?>
            <div class="tf-setup-wizard-wrapper" id="tf-setup-wizard-wrapper">
                <div class="tf-setup-container">
                    <div class="tf-setup-header">
                        <div class="tf-setup-header-left">
                            <a href="<?php echo esc_url( admin_url() ); ?>" class="tf-admin-btn tf-btn-secondary back-to-dashboard"><span><?php esc_html_e( 'Back to dashboard', 'instantio' ) ?></span></a>
                        </div>
                        <div class="tf-setup-header-right">
                            <span class="get-help-link"><?php esc_html_e('Having troubles?', 'instantio') ?> <a class="" target="_blank" href="https://portal.themefic.com/support/"><?php esc_html_e('Get help', 'instantio') ?></a></span>
                        </div>
                    </div>
                    <form method="post" id="tf-setup-wizard-form" data-skip-steps="">
						<?php
						$this->tf_setup_welcome_step();
						$this->tf_setup_step_one();
						$this->setup_step_two();
						$this->tf_setup_step_three();
						$this->tf_setup_finish_step();
						?>
						<?php wp_nonce_field( 'instantio_setup_wizard_action', 'instantio_setup_wizard_nonce' ); ?>
                        <input type="hidden" name="tf-skip-steps">
                    </form>
                </div>
            </div>
			<?php
		}

		/**
		 * Welcome step
		 */
		private function tf_setup_welcome_step() {
			?>
            <div class="tf-setup-content-layout tf-welcome-step <?php echo self::$current_step == 'welcome' ? 'active' : ''; ?>">

                <div class="welcome-img">
                    <img src="<?php echo esc_url( INS_ADMIN_URL . '/tf-options/img/instantio.png' ) ?>" alt="<?php esc_attr_e( 'Welcome to Instantio!', 'instantio' ) ?>">
                </div>
                
                <h1 class="tf-setup-welcome-title">
                    <?php esc_html_e( 'Welcome to instantio!', 'instantio' ) ?>
                </h1>

                <div class="tf-setup-welcome-description">
                    <?php esc_html_e( 'Our quick setup wizard makes getting started a breeze. It\'s simple, takes just few second, and helps configure basic settings. Remember, this guide is optional. Let\'s begin!', 'instantio' ) ?>
                </div>

                <div class="tf-setup-welcome-footer">
                    <button type="button" class="tf-admin-btn tf-btn-secondary tf-setup-start-btn">
                        <span><?php esc_html_e( 'Get Started', 'instantio' ) ?></span>
                    </button> 
                </div>

            </div>
			<?php
		}

		/**
		 * Setup step one
		 */
		private function tf_setup_step_one() { 
            $ins_layout_options = !empty(insopt( 'ins-layout-options' )) ? insopt( 'ins-layout-options' ) : ''; 

	            $ins_layout_mode = !empty(insopt( 'ins-layout-mode' )) ? insopt( 'ins-layout-mode' ) : ''; ?>

            <div class="tf-setup-step-container tf-setup-step-1 <?php echo self::$current_step == 'step_1' ? 'active' : ''; ?>" data-step="1">
                <section class="tf-setup-step-layout">
                    <?php $this->tf_setup_wizard_steps_header() ?>
                    
                    <div class="tf-setup-form-item bg">
                        <label class="">
                            <?php esc_html_e( 'Choose cart options', 'instantio' ) ?>
                        </label> 
                        <ul class="tf-select-service">
                            <li>
                                <input type="radio" name="ins-layout-options" value="1" <?php echo !empty($ins_layout_options) && ($ins_layout_options === '1') ? esc_attr( 'checked' ) : ''; ?> />
                                <label for="ins-layout-options">
                                    <img src="<?php echo esc_url( INS_ADMIN_URL . '/tf-options/img/layout/Directcheckout.jpg' ) ?>" alt="<?php esc_attr_e( 'Direct-Checkout', 'instantio' ) ?>">
                                    <span><?php esc_html_e( 'Direct Checkout', 'instantio' ) ?></span>
                                </label>
                            </li>
                            <li>
                                <input type="radio" name="ins-layout-options" value="2" <?php echo !empty($ins_layout_options) && ($ins_layout_options === '2') ? esc_attr( 'checked' ) : 'checked'; ?> />
                                <label for="ins-layout-options">
                                    <img src="<?php echo esc_url( INS_ADMIN_URL . '/tf-options/img/layout/sidecart.jpg' ) ?>" alt="<?php esc_attr_e( 'Side-Cart', 'instantio' ) ?>">
                                    <span><?php esc_html_e( 'Side Cart', 'instantio' ) ?></span>
                                </label>
                            </li>

                            <li>
                                <input type="radio" name="ins-layout-options" value="3" <?php echo !empty($ins_layout_options) && ($ins_layout_options === '3') ? esc_attr( 'checked' ) : ''; ?> />
                                <label for="ins-layout-options">
                                    <img src="<?php echo esc_url( INS_ADMIN_URL . '/tf-options/img/layout/Popup.jpg' ) ?>" alt="<?php esc_attr_e( 'Popup-Cart', 'instantio' ) ?>">
                                    <span><?php esc_html_e( 'Popup Cart', 'instantio' ) ?></span>
                                </label>
                            </li>
                        </ul>
                    </div>

                    <div class="tf-setup-form-item bg">
                        
                        <label class="">
                            <?php esc_html_e( 'Select The Mode', 'instantio' ) ?>
                        </label>

                        <ul class="tf-select-mode">
                            <li>
                                <input type="radio" name="ins-layout-mode" value="light" <?php echo !empty($ins_layout_mode) && ( 'light' === $ins_layout_mode) ? esc_attr( 'checked' ) : 'checked'; ?> />
                                <label for="ins-layout-mode">
                                    <img src="<?php echo esc_url( INS_ADMIN_URL . '/tf-options/img/layout/Light.png' ) ?>" alt="<?php esc_attr_e( 'light', 'instantio' ) ?>">
                                    <span><?php esc_html_e( 'Light', 'instantio' ) ?></span>
                                </label>
                            </li>

                            <li>
                                <input type="radio" name="ins-layout-mode" value="dark" <?php echo !empty($ins_layout_mode) && ( 'dark' === $ins_layout_mode) ? esc_attr( 'checked' ) : ''; ?> />
                                <label for="ins-layout-mode">
                                    <img src="<?php echo esc_url( INS_ADMIN_URL . '/tf-options/img/layout/Dark.png' ) ?>" alt="<?php esc_attr_e( 'dark', 'instantio' ) ?>">
                                    <span><?php esc_html_e( 'Dark', 'instantio' ) ?></span>
                                </label>
                            </li>

                            <li>
                                <input type="radio" name="ins-layout-mode" value="glass-morphism" <?php echo !empty($ins_layout_mode) && ( 'glass-morphism' === $ins_layout_mode) ? esc_attr( 'checked' ) : ''; ?> />
                                <label for="ins-layout-mode">
                                    <img src="<?php echo esc_url( INS_ADMIN_URL . '/tf-options/img/layout/GlassMorphism.png' ) ?>" alt="<?php esc_attr_e( 'GlassMorphism', 'instantio' ) ?>">
                                    <span><?php esc_html_e( 'Glass Morphism', 'instantio' ) ?></span>
                                </label>
                            </li>

                            <li>
                                <input type="radio" name="ins-layout-mode" value="gradient" <?php echo !empty($ins_layout_mode) && ( 'gradient' === $ins_layout_mode) ? esc_attr( 'checked' ) : ''; ?> />
                                <label for="ins-layout-mode">
                                    <img src="<?php echo esc_url( INS_ADMIN_URL . '/tf-options/img/layout/Gradient.png' ) ?>" alt="<?php esc_attr_e( 'gradient', 'instantio' ) ?>">
                                    <span><?php esc_html_e( 'Gradient', 'instantio' ) ?></span>
                                </label>
                            </li>
                        </ul>
                    </div>

	                    <?php do_action( 'instantio_setup_wizard_after_mode_options' ); ?>

                </section>
                    
                <div class="tf-setup-action-btn-wrapper">
                    <div></div>
                    <div class="tf-setup-action-btn-next">
                        <button type="button" class="tf-setup-skip-btn tf-link-btn"><?php esc_html_e( 'Skip this step', 'instantio' ) ?></button>
                        <button type="button" class="tf-setup-next-btn tf-admin-btn tf-btn-secondary"><?php esc_html_e( 'Next', 'instantio' ) ?></button>
                    </div>
                </div>
            </div>
		<?php }

		/**
		 * Setup step two
		 */
		private function setup_step_two() {
            $ins_layout = !empty(insopt( 'ins-layout' )) ? insopt( 'ins-layout' ) : '';
            $toggle_position = !empty(insopt( 'toggle-position' )) ? insopt( 'toggle-position' ) : 'right-bottom';
            $auto_tog_panel = !empty(insopt( 'auto-tog-panel' )) ? insopt( 'auto-tog-panel' ) : '';
            
			?>
            <div class="tf-setup-step-container tf-setup-step-2 <?php echo self::$current_step == 'step_2' ? 'active' : ''; ?>" data-step="2">
                <section class="tf-setup-step-layout">
					<?php $this->tf_setup_wizard_steps_header( 2 ) ?>
              
                    <div class="tf-setup-form-item middle">

                        <div class="tf-setup-form-item-label">
                            <label class=""><?php esc_html_e( 'Select layout Options', 'instantio' ) ?></label>
                        </div>

                        <div class="tf-setup-form-item-input">
                            <select name="ins-layout" id="tf-wishlist-page">

                                <option value="">
                                    <?php esc_html_e( 'Select a Cart', 'instantio' ) ?>
                                </option>

								<option value="cart" <?php echo !empty($ins_layout) && ( 'cart' === $ins_layout) ? esc_attr( 'selected' ) : ''; ?>>
                                    <?php esc_html_e( 'Only Cart', 'instantio' ) ?>
                                </option>

	                                <?php do_action( 'instantio_setup_wizard_layout_options', $ins_layout ); ?>

                            </select>
                        </div>

                    </div>

                    <!-- Cart Button Horizontal Position Option-->
                    <div class="tf-setup-form-item middle">
                        <div class="tf-setup-form-item-label">
                            <label class="" for="toggle-position">
                                <?php esc_html_e( 'Icon Position', 'instantio' ) ?>
                            </label>
                        </div>
                        <div class="tf-setup-form-item-input"> 
                            <select name="toggle-position" id="toggle-position">
                                <option value=""><?php esc_html_e( 'Select a position', 'instantio' ) ?></option>

                                <option value="right-top" <?php echo !empty($toggle_position) && ( 'right-top' == $toggle_position) ? esc_attr( 'selected' ) : ''; ?>>
                                    <?php esc_html_e( 'Right Top', 'instantio' ) ?>
                                </option>

                                <option value="right-middle" <?php echo !empty($toggle_position) && ( 'right-middle' == $toggle_position) ? esc_attr( 'selected' ) : ''; ?>>
                                    <?php esc_html_e( 'Right Middle', 'instantio' ) ?>
                                </option>

                                <option value="right-bottom" <?php echo !empty($toggle_position) && ( 'right-bottom' == $toggle_position) ? esc_attr( 'selected' ) : ''; ?>>
                                    <?php esc_html_e( 'Right Bottom', 'instantio' ) ?>
                                </option>

                                <option value="left-top" <?php echo !empty($toggle_position) && ( 'left-top' == $toggle_position) ? esc_attr( 'selected' ) : ''; ?>>
                                    <?php esc_html_e( 'Left Top', 'instantio' ) ?>
                                </option>

                                <option value="left-middle" <?php echo !empty($toggle_position) && ( 'left-middle' == $toggle_position) ? esc_attr( 'selected' ) : ''; ?>>
                                    <?php esc_html_e( 'Left Middle', 'instantio' ) ?>
                                </option>

                                <option value="left-bottom" <?php echo !empty($toggle_position) && ( 'left-bottom' == $toggle_position) ? esc_attr( 'selected' ) : ''; ?>>
                                    <?php esc_html_e( 'Left Bottom', 'instantio' ) ?>
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Auto Open Toggle Option -->
                    <div class="tf-setup-form-item middle">
                        <div class="tf-setup-form-item-label">
                            <label class="" for="auto-tog-panel"><?php esc_html_e( 'Auto Open Toggle Panel', 'instantio' ) ?></label>
                        </div>
                        <div class="tf-setup-form-item-input">
                            <label for="auto-tog-panel" class="tf-switch-label">
                                <input type="checkbox" id="auto-tog-panel" name="auto-tog-panel" value="<?php echo !empty($auto_tog_panel) ? esc_attr( '1' ) : ''; ?>" class="tf-switch" <?php echo !empty($auto_tog_panel) ? esc_attr( 'checked' ) : ''; ?>/>
                                <span class="tf-switch-slider"></span>
                            </label>
                        </div>
                    </div>
                </section>

                <div class="tf-setup-action-btn-wrapper">
                    <button type="button" class="tf-setup-prev-btn tf-admin-btn tf-btn-secondary"><?php esc_html_e( 'Previous', 'instantio' ) ?></button>
                    <div class="tf-setup-action-btn-next">
                        <button type="button" class="tf-setup-skip-btn tf-link-btn"><?php esc_html_e( 'Skip this step', 'instantio' ) ?></button>
                        <button type="button" class="tf-setup-next-btn tf-admin-btn tf-btn-secondary"><?php esc_html_e( 'Next', 'instantio' ) ?></button>
                    </div>
                </div>

            </div>

			<?php
		}

		/**
		 * Setup step three
		 */
		private function tf_setup_step_three() {
            $cart_icon_style = !empty(insopt( 'cart-icon-style' )) ? insopt( 'cart-icon-style' ) : 'cart-style-1';
            $ins_layout_animation = !empty(insopt( 'ins-layout-animation' )) ? insopt( 'ins-layout-animation' ) : '';
            $cart_fly_anim = !empty(insopt( 'cart-fly-anim' )) ? insopt( 'cart-fly-anim' ) : '';
            $ins_cart_emty_hide = !empty(insopt( 'ins-cart-emty-hide' )) ? insopt( 'ins-cart-emty-hide' ) : '';
			?>
            <div class="tf-setup-step-container tf-setup-step-3 <?php echo self::$current_step == 'step_3' ? 'active' : ''; ?>" data-step="3">
                <section class="tf-setup-step-layout">
					<?php $this->tf_setup_wizard_steps_header( 3 ) ?>
                    <div class="tf-hotel-setup-wizard">

                        <!-- Choose Cart Icon Style Option-->
                        <div class="tf-setup-form-item middle">
                            <label><?php esc_html_e( 'Cart Icon Style', 'instantio' ) ?></label>

                            <ul class="tf-select-mode">
                                <li>
                                    <input type="radio" name="cart-icon-style" value="cart-style-1" <?php echo !empty($cart_icon_style) && ( 'cart-style-1' === $cart_icon_style) ? esc_attr( 'checked' ) : ''; ?>/>
                                    <label for="cart-icon-style">
                                        <img src="<?php echo esc_url( INS_ADMIN_URL . '/tf-options/img/cart-style-1.svg' ) ?>" alt="<?php esc_attr_e( 'Cart 1', 'instantio' ) ?>">
                                        <span><?php esc_html_e( 'Cart 1', 'instantio' ) ?></span>
                                    </label>
                                </li>
                                
                                <li>
                                    <input type="radio" name="cart-icon-style" value="cart-style-2" <?php echo !empty($cart_icon_style) && ( 'cart-style-2' === $cart_icon_style) ? esc_attr( 'checked' ) : ''; ?> />
                                    <label for="cart-icon-style">
                                        <img src="<?php echo esc_url( INS_ADMIN_URL . '/tf-options/img/cart-2.svg' ) ?>" alt="<?php esc_attr_e( 'Cart 2', 'instantio' ) ?>">
                                        <span><?php esc_html_e( 'Cart 2', 'instantio' ) ?></span>
                                    </label>
                                </li>

                                <li>
                                    <input type="radio" name="cart-icon-style" value="cart-style-3" <?php echo !empty($cart_icon_style) && ( 'cart-style-3' === $cart_icon_style) ? esc_attr( 'checked' ) : ''; ?> />
                                    <label for="cart-icon-style">
                                        <img src="<?php echo esc_url( INS_ADMIN_URL . '/tf-options/img/cart-3.svg' ) ?>" alt="<?php esc_attr_e( 'Cart 3', 'instantio' ) ?>">
                                        <span><?php esc_html_e( 'Cart 3', 'instantio' ) ?></span>
                                    </label>
                                </li>

                                <li>
                                    <input type="radio" name="cart-icon-style" value="cart-style-4" <?php echo !empty($cart_icon_style) && ( 'cart-style-4' === $cart_icon_style) ? esc_attr( 'checked' ) : ''; ?> />
                                    <label for="cart-icon-style">
                                        <img src="<?php echo esc_url( INS_ADMIN_URL . '/tf-options/img/cart-4.svg' ) ?>" alt="<?php esc_attr_e( 'Cart 4', 'instantio' ) ?>">
                                        <span><?php esc_html_e( 'Cart 4', 'instantio' ) ?></span>
                                    </label>
                                </li>
                            </ul>
                        </div>

                        <!-- Choose layout Animation Option-->
                        <div class="tf-setup-form-item middle">
                            <div class="tf-setup-form-item-label">
                                <label class="" for="ins-layout-animation">
                                    <?php esc_html_e( 'Choose layout Animation', 'instantio' ) ?>
                                </label>
                            </div>
                            <div class="tf-setup-form-item-input"> 
                                <select name="ins-layout-animation" id="ins-layout-animation">
                                    <option value="">
                                        <?php esc_html_e( 'Select a position', 'instantio' ) ?>
                                    </option>

                                    <option value="ins_animate_default" <?php echo empty($ins_layout_animation) || ( 'ins_animate_default' === $ins_layout_animation) ? esc_attr( 'selected' ) : ''; ?>>
                                        <?php esc_html_e( 'Default Animation', 'instantio' ) ?>
                                    </option>

                                    <option value="ins_animate_one" <?php echo empty($ins_layout_animation) || ( 'ins_animate_one' === $ins_layout_animation) ? esc_attr( 'selected' ) : ''; ?>>
                                        <?php esc_html_e( 'Fade In Animate', 'instantio' ) ?>
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Choose Cart Fly Animation Option-->
                        <div class="tf-setup-form-item middle">
                            <div class="tf-setup-form-item-label">
                                <label class="" for="cart-fly-anim">
                                    <?php esc_html_e( 'Cart Fly Animation', 'instantio' ) ?>
                                </label>
                            </div>

                            <div class="tf-setup-form-item-input">
                                <label for="cart-fly-anim" class="tf-switch-label">
                                    <input type="checkbox" id="cart-fly-anim" name="cart-fly-anim" value="<?php echo !empty($cart_fly_anim) ? esc_attr( '1' ) : ''; ?>" class="tf-switch" <?php echo !empty($cart_fly_anim) ? esc_attr( 'checked' ) : ''; ?> />
                                    <span class="tf-switch-slider"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Choose Hide Cart Button Option-->
                        <div class="tf-setup-form-item middle">
                            <div class="tf-setup-form-item-label">
                                <label class="" for="ins-cart-emty-hide">
                                    <?php esc_html_e( 'Hide Cart Button when No Cart Item', 'instantio' ) ?>
                                </label>
                            </div>

                            <div class="tf-setup-form-item-input">
                                <label for="ins-cart-emty-hide" class="tf-switch-label">
                                    <input type="checkbox" id="ins-cart-emty-hide" name="ins-cart-emty-hide" value="<?php echo !empty($ins_cart_emty_hide) ? esc_attr( '1' ) : ''; ?>" class="tf-switch" <?php echo !empty($ins_cart_emty_hide) ? esc_attr( 'checked' ) : ''; ?>/>
                                    <span class="tf-switch-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                </section>
                <div class="tf-setup-action-btn-wrapper">
                    <button type="button" class="tf-setup-prev-btn tf-admin-btn tf-btn-secondary"><?php esc_html_e( 'Previous', 'instantio' ) ?></button>
                    <div class="tf-setup-action-btn-next">
                        <button type="submit" class="tf-setup-skip-btn tf-link-btn tf-setup-submit-btn"><?php esc_html_e( 'Skip this step', 'instantio' ) ?></button>
                        <button type="submit" class="tf-setup-submit-btn tf-admin-btn tf-btn-secondary"><?php esc_html_e( 'Finish', 'instantio' ) ?></button>
                    </div>
                </div>
            </div>
			<?php
		}

        /*
         * Finish setup wizard
         */
        private function tf_setup_finish_step() {
            ?>
            <div class="tf-setup-content-layout tf-finish-step <?php echo self::$current_step == 'finish' ? 'active' : ''; ?>">

                <div class="ins_quick_setup_finish">
                    <div class="welcome-img"><img src="<?php  echo esc_url( INS_ADMIN_URL . '/tf-options/img/wizard/quikfinal.png' ) ?>" alt="<?php esc_attr_e( 'Thank you', 'instantio' ) ?>"></div>

                    <h1 class="tf-setup-welcome-title">
                        <?php esc_html_e( 'Hooray! You’re all set', 'instantio' ) ?>
                    </h1>

                    <div class="tf-setup-welcome-description">
                        <?php esc_html_e( 'Let\'s get started with Instantio. With this plugin, you can manage your store and provide seamless checkout for your customers. Let\'s streamline your business operations today.', 'instantio' ) ?>

                        <p class="tf-setup-welcome-description-info">
                            <?php esc_html_e( 'Overrides can be made from Instantio settings.', 'instantio' ) ?>
                        </p>
                    </div>

                    <div class="tf-setup-welcome-footer tf-setup-finish-footer">
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=wiopt#tab=layout_option' ) ); ?>" class="tf-admin-btn tf-btn-secondary bg-not"><?php esc_html_e( 'Instantio Settings', 'instantio' ) ?></a>
                        
						<a href="<?php echo esc_url( admin_url( '/' ) ); ?>" class="tf-admin-btn tf-btn-secondary"><?php esc_html_e( 'Back to Dashboard', 'instantio' ) ?></a>
                    </div>
                </div>

            </div>
        <?php
        }

		/**
		 * redirect to set up wizard when active plugin
		 */
		public function tf_activation_redirect() {
			$wizard_status = get_option( 'instantio_setup_wizard', false );
			$legacy_status = get_option( 'tf_setup_wizard', false );

			if ( false === $wizard_status && false !== $legacy_status ) {
				update_option( 'instantio_setup_wizard', $legacy_status );
				$wizard_status = $legacy_status;
			}

			if ( ! $wizard_status ) {
				update_option( 'instantio_setup_wizard', 'active' );
				wp_safe_redirect( admin_url( 'admin.php?page=ins-setup-wizard' ) );
				exit;
			}
		}

		private function tf_setup_wizard_steps_header( $active_step = 1 ) {
			$inactive_icon = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="11" stroke="#DB5209" stroke-width="2"></circle></svg>';
			$active_icon   = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="12" fill="#DB5209"></circle><circle cx="12" cy="12" r="4" fill="white"></circle></svg>';
			$finish_icon   = '<svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="13.8359" cy="13.8359" r="13.8359" fill="#DB5209"/>
                <g clip-path="url(#clip0_511_10759)">
                    <path d="M10.9524 13.8356L13.8349 16.7181L19.5998 10.9531" stroke="#FCF9F7" stroke-width="1.29711" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8.07245 13.8356L10.9549 16.7181M13.8374 13.8356L16.7199 10.9531" stroke="#FCF9F7" stroke-width="1.29711" stroke-linecap="round" stroke-linejoin="round"/>
                </g>
            </svg>';
			?>
            <div class="tf-setup-steps">
                <div class="tf-steps-item <?php echo $active_step == 1 ? 'active' : ''; ?>">
                    <div class="tf-steps-item-container">
                        <div class="tf-steps-item-tail"></div>
                        <div class="tf-steps-item-icon">
                            <span class="tf-steps-icon">
								<?php
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG strings defined in this method.
								echo $active_step == 1 ? $active_icon : $finish_icon;
								?>
                            </span>
                        </div>
                        <div class="tf-steps-item-title"><?php esc_html_e( 'Step 1', 'instantio' ); ?></div>
                    </div>
                </div>

                <div class="tf-steps-item <?php echo $active_step == 2 ? 'active' : ''; ?>">
                    <div class="tf-steps-item-container">
                        <div class="tf-steps-item-tail"></div>
                        <div class="tf-steps-item-icon">
                            <span class="tf-steps-icon">
								<?php
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG strings defined in this method.
								echo $active_step == 2 ? $active_icon : ( $active_step > 2 ? $finish_icon : $inactive_icon );
								?>
                            </span>
                        </div>
                        <div class="tf-steps-item-title"><?php esc_html_e( 'Step 2', 'instantio' ); ?></div>
                    </div>
                </div>
                <div class="tf-steps-item <?php echo $active_step == 3 ? 'active' : ''; ?>">
                    <div class="tf-steps-item-container">
                        <div class="tf-steps-item-tail"></div>
                        <div class="tf-steps-item-icon">
                            <span class="tf-steps-icon">
								<?php
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG strings defined in this method.
								echo $active_step == 3 ? $active_icon : ( $active_step > 3 ? $finish_icon : $inactive_icon );
								?>
                            </span>
                        </div>
                        <div class="tf-steps-item-title"><?php esc_html_e( 'Step 3', 'instantio' ); ?></div>
                    </div>
                </div>
            </div>
			<?php
		}

			function instantio_setup_wizard_submit_ajax() {
				check_ajax_referer( 'instantio_setup_wizard_action', 'instantio_setup_wizard_nonce' );

				if ( ! current_user_can( 'manage_options' ) ) {
					wp_send_json_error( array( 'message' => esc_html__( 'You are not allowed to change these settings.', 'instantio' ) ), 403 );
				}

				$request = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );

				$options = get_option( 'wiopt' );
           
            if($options == '' && !is_array($options)){
                $options = [];
				$options['ins-layout-options'] = isset( $request['ins-layout-options'] ) ? $request['ins-layout-options'] : 1;
            
				$options['ins-layout-mode'] = isset( $request['ins-layout-mode'] ) ? $request['ins-layout-mode'] : 'light';
				$options['ins-layout'] = 'cart';
				$options['auto-tog-panel'] = isset( $request['auto-tog-panel'] ) ? $request['auto-tog-panel'] : '1';
				$options['ins-toggle-tab']['toggle-position'] = isset( $request['toggle-position'] ) ? $request['toggle-position'] : 'right-bottom';
				$options['woins-quickview-disable'] = isset( $request['woins-quickview-disable'] ) ? $request['woins-quickview-disable'] : false;
				$options['wi-disable-ajax-add-cart'] = isset( $request['wi-disable-ajax-add-cart'] ) ? $request['wi-disable-ajax-add-cart'] : false;
				$options['js-min'] = isset( $request['js-min'] ) ? $request['js-min'] : false;
				$options['ins-toggle-tab']['cart-icon-style'] = isset( $request['cart-icon-style'] ) ? $request['cart-icon-style'] : 'cart-style-1';
				$options['ins-layout-animation'] = isset( $request['ins-layout-animation'] ) ? $request['ins-layout-animation'] : 'ins_animate_default';
				$options['cart-fly']['cart-fly-anim'] = isset( $request['cart-fly-anim'] ) ? $request['cart-fly-anim'] : false;
				$options['ins-toggle-tab']['ins-cart-emty-hide'] = isset( $request['ins-cart-emty-hide'] ) ? $request['ins-cart-emty-hide'] : false;
                $options['ins-toggler'] = 'tog-1';
                $options['cart-fly']['cart-fly-icon'] = 1;
                
            } else{
				$options['ins-layout-options'] = isset( $request['ins-layout-options'] ) ? $request['ins-layout-options'] : 1;
            
				$options['ins-layout-mode'] = isset( $request['ins-layout-mode'] ) ? $request['ins-layout-mode'] : 'light';
				$options['ins-layout'] = 'cart';
				$options['auto-tog-panel'] = isset( $request['auto-tog-panel'] ) ? $request['auto-tog-panel'] : '1';
				$options['ins-toggle-tab']['toggle-position'] = isset( $request['toggle-position'] ) ? $request['toggle-position'] : 'right-bottom';
				$options['woins-quickview-disable'] = isset( $request['woins-quickview-disable'] ) ? $request['woins-quickview-disable'] : false;
				$options['wi-disable-ajax-add-cart'] = isset( $request['wi-disable-ajax-add-cart'] ) ? $request['wi-disable-ajax-add-cart'] : false;
				$options['js-min'] = isset( $request['js-min'] ) ? $request['js-min'] : false;
				$options['ins-toggle-tab']['cart-icon-style'] = isset( $request['cart-icon-style'] ) ? $request['cart-icon-style'] : 'cart-style-1';
				$options['ins-layout-animation'] = isset( $request['ins-layout-animation'] ) ? $request['ins-layout-animation'] : 'ins_animate_default';
				$options['cart-fly']['cart-fly-anim'] = isset( $request['cart-fly-anim'] ) ? $request['cart-fly-anim'] : false;
				$options['ins-toggle-tab']['ins-cart-emty-hide'] = isset( $request['ins-cart-emty-hide'] ) ? $request['ins-cart-emty-hide'] : false;
                $options['ins-toggler'] = 'tog-1';
                $options['cart-fly']['cart-fly-icon'] = 1;
            }
            
				$options = apply_filters( 'instantio_setup_wizard_options', $options, $request );

				update_option( 'wiopt', $options );
			$response              = [
				'success'      => true,
				'redirect_url' => esc_url( admin_url( 'admin.php?page=ins_tf_settings' ) )
			];

				wp_send_json( $response );
		}
	}
}

Instantio_Setup_Wizard::instance();
