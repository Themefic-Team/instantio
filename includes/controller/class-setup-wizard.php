<?php
defined( 'ABSPATH' ) || exit;
/**
 * Setup Wizard Class
 * @since 2.9.3
 * @author Foysal
 */
if ( ! class_exists( 'TF_Setup_Wizard' ) ) {
	class TF_Setup_Wizard {

		private static $instance = null;
		private static $current_step = null;

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
			add_action( 'admin_menu', [ $this, 'tf_wizard_menu' ], 100 );
			add_filter( 'woocommerce_enable_setup_wizard', '__return_false' );
			add_action( 'admin_init', [ $this, 'tf_activation_redirect' ] );
			add_action( 'wp_ajax_tf_setup_wizard_submit', [ $this, 'tf_setup_wizard_submit_ajax' ] );
			add_action( 'in_admin_header', [ $this, 'remove_notice' ], 1000 );

			self::$current_step = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : 'welcome';
		}

		/**
		 * Add wizard submenu
		 */
		public function tf_wizard_menu() {

			if ( current_user_can( 'manage_options' ) ) {
				add_submenu_page(
					'',
					esc_html__( 'Instantio Setup Wizard', 'instantio' ),
					esc_html__( 'Instantio Setup Wizard', 'instantio' ),
					'manage_options',
					'tf-setup-wizard',
					[ $this, 'tf_wizard_page' ],
					99
				);
			}
		}

		/**
		 * Remove all notice in setup wizard page
		 */
		public function remove_notice() {
			if ( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'tf-setup-wizard' ) {
				remove_all_actions( 'admin_notices' );
				remove_all_actions( 'all_admin_notices' );
			}
		}

		/**
		 * Setup wizard page
		 */
		public function tf_wizard_page() {
			?>
            <div class="tf-setup-wizard-wrapper" id="tf-setup-wizard-wrapper">
                <div class="tf-setup-container">
                    <div class="tf-setup-header">
                        <div class="tf-setup-header-left">
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=wiopt#tab=general' ) ); ?>" class="tf-admin-btn tf-btn-secondary back-to-dashboard"><span><?php _e( 'Back to Dashboard', 'tourfic' ) ?></span></a>
                        </div>
                        <div class="tf-setup-header-right">
                            <span class="get-help-link"><?php _e('Having troubles?', 'instantio') ?> <a class="" target="_blank" href="https://portal.themefic.com/support/"><?php _e('Get help', 'torufic') ?></a></span>
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
						<?php wp_nonce_field( 'tf_setup_wizard_action', 'tf_setup_wizard_nonce' ); ?>
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
                    <img src="<?php echo INS_ADMIN_URL . '/tf-options/img/instanio-logo.png' ?>" alt="<?php esc_attr_e( 'Welcome to Instantio!', 'instantio' ) ?>">
                </div>
                
                <h1 class="tf-setup-welcome-title">
                    <?php _e( 'Welcome to instantio!', 'instantio' ) ?>
                </h1>

                <div class="tf-setup-welcome-description">
                    <?php _e( 'Thanks for choosing instantio for your business. We are excited to have you on board. This quick setup wizard is simple and straightforward and shouldn’t take longer than five minutes. It will help you configure the basic settings of instantio to get started. Please note that this setup guide is entirely optional.', 'instantio' ) ?>
                </div>

                <div class="tf-setup-welcome-footer">
                    <button type="button" class="tf-admin-btn tf-btn-secondary tf-setup-start-btn">
                        <span><?php _e( 'Get Started', 'instantio' ) ?></span>
                    </button>

                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=wiopt#tab=general' ) ); ?>" class="tf-link-btn"><?php _e( 'Skip to Dashboard', 'instantio' ) ?></a>
                </div>

            </div>
			<?php
		}

		/**
		 * Setup step one
		 */
		private function tf_setup_step_one() {
			?>
            <div class="tf-setup-step-container tf-setup-step-1 <?php echo self::$current_step == 'step_1' ? 'active' : ''; ?>" data-step="1">
                <section class="tf-setup-step-layout">
					<?php $this->tf_setup_wizard_steps_header() ?>
                    <h1 class="tf-setup-step-title"><?php _e( 'Choose cart options', 'instantio' ) ?></h1>
                    <p class="tf-setup-step-desc"><?php _e( '(You can choose any one)', 'instantio' ) ?></p>
                    <ul class="tf-select-service">
                        <li>
                            <input type="radio" name="ins-layout-options" value="1" checked/>
                            <label for="tf-hotel">
                                <img src="<?php echo INS_ADMIN_URL . '/tf-options/img/layout/Directcheckout.jpg' ?>" alt="<?php esc_attr_e( 'Direct-Checkout', 'instantio' ) ?>">
                                <span><?php _e( 'Direct Checkout', 'instantio' ) ?></span>
                            </label>
                        </li>
                        <li>
                            <input type="radio" name="ins-layout-options" value="2" checked/>
                            <label for="tf-tour">
                                <img src="<?php echo INS_ADMIN_URL . '/tf-options/img/layout/Cart.svg' ?>" alt="<?php esc_attr_e( 'Side-Cart', 'instantio' ) ?>">
                                <span><?php _e( 'Side Cart', 'instantio' ) ?></span>
                            </label>
                        </li>

                        <li>
                            <input type="radio" name="ins-layout-options" value="3" checked/>
                            <label for="tf-tour">
                                <img src="<?php echo INS_ADMIN_URL . '/tf-options/img/layout/Popup.jpg' ?>" alt="<?php esc_attr_e( 'Popup-Cart', 'instantio' ) ?>">
                                <span><?php _e( 'Popup Cart', 'instantio' ) ?></span>
                            </label>
                        </li>
                    </ul>
                </section>
                <div class="tf-setup-action-btn-wrapper">
                    <div></div>
                    <div class="tf-setup-action-btn-next">
                        <button type="button" class="tf-setup-skip-btn tf-link-btn"><?php _e( 'Skip this step', 'instantio' ) ?></button>
                        <button type="button" class="tf-setup-next-btn tf-admin-btn tf-btn-secondary"><?php _e( 'Next', 'instantio' ) ?></button>
                    </div>
                </div>
            </div>
			<?php
		}

		/**
		 * Setup step two
		 */
		private function setup_step_two() {
			?>
            <div class="tf-setup-step-container tf-setup-step-2 <?php echo self::$current_step == 'step_2' ? 'active' : ''; ?>" data-step="2">
                <section class="tf-setup-step-layout">
					<?php $this->tf_setup_wizard_steps_header( 2 ) ?>
                    <h1 class="tf-setup-step-title">
                        <?php _e( 'General Settings (Layouts)', 'instantio' ) ?>
                    </h1>
                    <div class="tf-setup-form-item">

                        <div class="tf-setup-form-item-label">
                            <label class="">
                                <?php _e( 'Select The Mode', 'instantio' ) ?>
                            </label>
                        </div>

                        <div class="tf-setup-form-item-input">
                            <select name="ins-layout-mode" id="tf-search-result-page">
                                <option value=""><?php _e( 'Select a page', 'instantio' ) ?></option>
                                <option value="light"><?php _e( 'Light', 'instantio' ) ?></option>
                                <option value="dark"><?php _e( 'Dark', 'instantio' ) ?></option>
                                <option value="glass-morphism"><?php _e( 'Glass Morphism', 'instantio' ) ?></option>
                                <option value="gradient"><?php _e( 'Gradient', 'instantio' ) ?></option>
                            </select>
                        </div>
                    </div>

                    <?php

                        $is_Pro_class = new TF_Options;
                        $is_Pro_active = $is_Pro_class->is_tf_pro_active(); 

                        if($is_Pro_active === false) { ?>
                    
                            <div class="tf-setup-form-item">
                                <div class="tf-setup-form-item-label">
                                    <label class="">
                                        <?php _e( 'Select The ProgressBar', 'instantio' ) ?> 
                                        <span>Pro</span> 
                                    </label>
                                </div>
                                <div class="tf-setup-form-item-input">
                                    <select disabled name="ins-layout-progressbar" id="tf-search-result-page">
                                        <option value="1"><?php _e( 'Select a page', 'instantio' ) ?></option>
                                    </select>
                                </div>
                            </div>

                    <?php } else { ?>

                            <div class="tf-setup-form-item">
                                <div class="tf-setup-form-item-label">
                                    <label class=""><?php _e( 'Select The ProgressBar', 'instantio' ) ?></label>
                                </div>
                                <div class="tf-setup-form-item-input">
                                    <select name="ins-layout-progressbar" id="tf-search-result-page">
                                        <option value=""><?php _e( 'Select a page', 'instantio' ) ?></option>
                                        <option value="1"><?php _e( 'Version 1', 'instantio' ) ?></option>
                                        <option value="2"><?php _e( 'Version 2', 'instantio' ) ?></option>
                                        <option value="3"><?php _e( 'Version 3', 'instantio' ) ?></option>
                                        <option value="4"><?php _e( 'Version 4', 'instantio' ) ?></option>
                                    </select>
                                </div>
                            </div>

                    <?php } ?>
                    
                    <div class="tf-setup-form-item">

                        <div class="tf-setup-form-item-label">
                            <label class=""><?php _e( 'Select Cart Options', 'instantio' ) ?></label>
                        </div>

                        <div class="tf-setup-form-item-input">
                            <select name="ins-layout" id="tf-wishlist-page">
                                <option value=""><?php _e( 'Select a page', 'instantio' ) ?></option>
								<option value="cart"><?php _e( 'Only Cart', 'instantio' ) ?></option>
                                <?php $is_Pro_class = new TF_Options;
                                $is_Pro_active = $is_Pro_class->is_tf_pro_active(); 

                                if($is_Pro_active === true) { ?>
                                    <option value="cartandcheckout"><?php _e( 'Cart & Checkout', 'instantio' ) ?></option>
                                <?php }?>

                            </select>
                        </div>
                    </div>

                    

                </section>
                <div class="tf-setup-action-btn-wrapper">
                    <button type="button" class="tf-setup-prev-btn tf-admin-btn tf-btn-secondary"><?php _e( 'Previous', 'instantio' ) ?></button>
                    <div class="tf-setup-action-btn-next">
                        <button type="button" class="tf-setup-skip-btn tf-link-btn"><?php _e( 'Skip this step', 'instantio' ) ?></button>
                        <button type="button" class="tf-setup-next-btn tf-admin-btn tf-btn-secondary"><?php _e( 'Next', 'instantio' ) ?></button>
                    </div>
                </div>
            </div>
			<?php
		}

		/**
		 * Setup step three
		 */
		private function tf_setup_step_three() {
			?>
            <div class="tf-setup-step-container tf-setup-step-3 <?php echo self::$current_step == 'step_3' ? 'active' : ''; ?>" data-step="3">
                <section class="tf-setup-step-layout">
					<?php $this->tf_setup_wizard_steps_header( 3 ) ?>
                    <div class="tf-hotel-setup-wizard">
                        <h3 class="tf-setup-step-subtitle">
                            <?php _e( 'Feature Settings', 'instantio' ) ?>
                        </h3>

                        <p class="tf-setup-step-desc">
                            <?php _e( 'These settings can be overridden from <strong>Instantio Settings</strong>', 'instantio' ) ?>
                        </p>

                        <!-- Auto Open Toggle Option -->
                        <div class="tf-setup-form-item">
                            <div class="tf-setup-form-item-label">
                                <label class="" for="auto-tog-panel"><?php _e( 'Auto Open Toggle Panel', 'instantio' ) ?></label>
                            </div>
                            <div class="tf-setup-form-item-input">
                                <label for="auto-tog-panel" class="tf-switch-label">
                                    <input type="checkbox" id="auto-tog-panel" name="auto-tog-panel" value="1" class="tf-switch" checked/>
                                    <span class="tf-switch-slider"></span>
                                </label>
                            </div>
                        </div>
                        <?php $is_Pro_class = new TF_Options;
                            $is_Pro_active = $is_Pro_class->is_tf_pro_active(); 

                            if($is_Pro_active === true) { ?>
                                <!--Quickview Section-->
                                <div class="tf-setup-form-item">
                                    <div class="tf-setup-form-item-label"><label class="" for="tf-hotel-review-section"><?php _e( 'Disable Quick View', 'instantio' ) ?></label></div>
                                    <div class="tf-setup-form-item-input">
                                        <label for="woins-quickview-disable" class="tf-switch-label">
                                            <input type="checkbox" id="woins-quickview-disable" name="woins-quickview-disable" value="1" class="tf-switch" checked/>
                                            <span class="tf-switch-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <!--Quickview Section-->
                                <div class="tf-setup-form-item pro">
                                    <div class="tf-setup-form-item-label">
                                        <label class="" for="tf-hotel-review-section">
                                            <?php _e( 'Disable Quick View', 'instantio' ) ?> 
                                            <span>Pro</span>  
                                        </label></div>
                                    <div class="tf-setup-form-item-input">
                                        <label for="woins-quickview-disable" class="tf-switch-label">
                                            <input disabled type="checkbox" id="woins-quickview-disable" name="woins-quickview-disable" value="" class="tf-switch"/>
                                            <span class="tf-switch-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php $is_Pro_class = new TF_Options;
                            $is_Pro_active = $is_Pro_class->is_tf_pro_active(); 

                            if($is_Pro_active === true) { ?>
                                <!--Disable Ajax Option-->
                                <div class="tf-setup-form-item">
                                    <div class="tf-setup-form-item-label">
                                        <label class="" for="tf-hotel-share-option">
                                            <?php _e( 'Disable Ajax Add to Cart', 'instantio' ) ?>
                                        </label>
                                    </div>
                                    <div class="tf-setup-form-item-input">
                                        <label for="wi-disable-ajax-add-cart" class="tf-switch-label">
                                            <input type="checkbox" id="wi-disable-ajax-add-cart" name="wi-disable-ajax-add-cart" value="1" class="tf-switch" checked/>
                                            <span class="tf-switch-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <!--Disable Ajax Option-->
                                <div class="tf-setup-form-item pro">
                                    <div class="tf-setup-form-item-label">
                                        <label class="" for="tf-hotel-share-option">
                                            <?php _e( 'Disable Ajax Add to Cart', 'instantio' ) ?>
                                            <span>Pro</span> 
                                        </label>
                                    </div>
                                    <div class="tf-setup-form-item-input">
                                        <label for="wi-disable-ajax-add-cart" class="tf-switch-label">
                                            <input disabled type="checkbox" id="wi-disable-ajax-add-cart" name="wi-disable-ajax-add-cart" value="" class="tf-switch" />
                                            <span class="tf-switch-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            <?php } ?>

                    </div>



                </section>
                <div class="tf-setup-action-btn-wrapper">
                    <button type="button" class="tf-setup-prev-btn tf-admin-btn tf-btn-secondary"><?php _e( 'Previous', 'instantio' ) ?></button>
                    <div class="tf-setup-action-btn-next">
                        <button type="submit" class="tf-setup-skip-btn tf-link-btn tf-setup-submit-btn"><?php _e( 'Skip this step', 'instantio' ) ?></button>
                        <button type="submit" class="tf-setup-submit-btn tf-admin-btn tf-btn-secondary"><?php _e( 'Finish', 'instantio' ) ?></button>
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
                <div class="welcome-img"><img src="<?php echo TF_ASSETS_ADMIN_URL . 'images/hooray.png' ?>" alt="<?php esc_attr_e( 'Thank you', 'tourfic' ) ?>"></div>
                <h1 class="tf-setup-welcome-title"><?php _e( 'Hooray! You’re all set.', 'tourfic' ) ?></h1>
                <div class="tf-setup-welcome-description"><?php _e( 'Let\'s get started and make the most out of Tourfic. With this plugin, you can manage your hotel or travel bookings with ease, and provide your customers with a seamless booking experience. So, let\'s dive in and start streamlining your hotel or travel business operations today!', 'tourfic' ) ?></div>
                <div class="tf-setup-welcome-footer tf-setup-finish-footer">
                    <a href="<?php echo admin_url( 'post-new.php?post_type=tf_hotel' ) ?>" class="tf-admin-btn tf-btn-secondary"><?php _e( 'Create new Hotel', 'tourfic' ) ?></a>
                    <a href="<?php echo admin_url( 'post-new.php?post_type=tf_tours' ) ?>" class="tf-admin-btn"><?php _e( 'Create new Tour', 'tourfic' ) ?></a>
                    <a href="<?php echo admin_url( 'admin.php?page=tf_settings' ) ?>" class="tf-admin-btn tf-btn-secondary"><?php _e( 'Tourfic Setting', 'tourfic' ) ?></a>
                </div>
            </div>
        <?php
        }

		/**
		 * redirect to set up wizard when active plugin
		 */
		public function tf_activation_redirect() {
			if ( ! get_option( 'tf_setup_wizard' ) && ! get_option( 'tf_settings' ) ) {
				update_option( 'tf_setup_wizard', 'active' );
				wp_redirect( admin_url( 'admin.php?page=tf-setup-wizard' ) );
				exit;
			}
		}

		private function tf_setup_wizard_steps_header( $active_step = 1 ) {
			$inactive_icon = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="11" stroke="#D8D9DF" stroke-width="2"></circle></svg>';
			$active_icon   = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="12" fill="#5D5DFF"></circle><circle cx="12" cy="12" r="4" fill="white"></circle></svg>';
			$finish_icon   = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="12" fill="#5D5DFF"></circle><path fill-rule="evenodd" clip-rule="evenodd" d="M17.7077 8.29352C18.0979 8.68439 18.0974 9.31755 17.7065 9.70773L11.703 15.7007C11.3123 16.0906 10.6796 16.0903 10.2894 15.7L7.29289 12.7036C6.90237 12.3131 6.90237 11.6799 7.29289 11.2894C7.68342 10.8988 8.31658 10.8988 8.70711 11.2894L10.9971 13.5794L16.2935 8.29227C16.6844 7.90209 17.3176 7.90265 17.7077 8.29352Z" fill="white"></path></svg>';
			?>
            <div class="tf-setup-steps">
                <div class="tf-steps-item <?php echo $active_step == 1 ? 'active' : ''; ?>">
                    <div class="tf-steps-item-container">
                        <div class="tf-steps-item-tail"></div>
                        <div class="tf-steps-item-icon">
                            <span class="tf-steps-icon">
                                <?php echo $active_step == 1 ? $active_icon : $finish_icon; ?>
                            </span>
                        </div>
                        <div class="tf-steps-item-title"><?php _e( 'Step 1', 'tourfic' ); ?></div>
                    </div>
                </div>

                <div class="tf-steps-item <?php echo $active_step == 2 ? 'active' : ''; ?>">
                    <div class="tf-steps-item-container">
                        <div class="tf-steps-item-tail"></div>
                        <div class="tf-steps-item-icon">
                            <span class="tf-steps-icon">
                                <?php echo $active_step == 2 ? $active_icon : ( $active_step > 2 ? $finish_icon : $inactive_icon ); ?>
                            </span>
                        </div>
                        <div class="tf-steps-item-title"><?php _e( 'Step 2', 'instantio' ); ?></div>
                    </div>
                </div>
                <div class="tf-steps-item <?php echo $active_step == 3 ? 'active' : ''; ?>">
                    <div class="tf-steps-item-container">
                        <div class="tf-steps-item-tail"></div>
                        <div class="tf-steps-item-icon">
                            <span class="tf-steps-icon">
                                <?php echo $active_step == 3 ? $active_icon : ( $active_step > 3 ? $finish_icon : $inactive_icon ); ?>
                            </span>
                        </div>
                        <div class="tf-steps-item-title"><?php _e( 'Step 3', 'instantio' ); ?></div>
                    </div>
                </div>
            </div>
			<?php
		}

		function tf_setup_wizard_submit_ajax() {

			// Add nonce for security and authentication.
			$nonce_name   = isset( $_POST['tf_setup_wizard_nonce'] ) ? $_POST['tf_setup_wizard_nonce'] : '';
			$nonce_action = 'tf_setup_wizard_action';

			// Check if a nonce is set.
			if ( ! isset( $nonce_name ) ) {
				return;
			}

			// Check if a nonce is valid.
			if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
				return;
			}

			$tf_settings            = get_option( 'tf_settings' );
			$tf_services            = array( 'hotel', 'tour' );
			$services               = isset( $_POST['tf-services'] ) ? $_POST['tf-services'] : [];
			$search_page            = isset( $_POST['tf-search-result-page'] ) ? $_POST['tf-search-result-page'] : '';
			$search_result_per_page = isset( $_POST['tf-search-result-posts-per-page'] ) ? $_POST['tf-search-result-posts-per-page'] : '';
			$wishlist_page          = isset( $_POST['tf-wishlist-page'] ) ? $_POST['tf-wishlist-page'] : '';
			$auto_publish           = isset( $_POST['tf-auto-publish-review'] ) ? $_POST['tf-auto-publish-review'] : '';
			$hotel_review           = isset( $_POST['tf-hotel-review-section'] ) ? $_POST['tf-hotel-review-section'] : '';
			$hotel_share            = isset( $_POST['tf-hotel-share-option'] ) ? $_POST['tf-hotel-share-option'] : '';
			$hotel_permalink        = isset( $_POST['tf-hotel-permalink'] ) ? $_POST['tf-hotel-permalink'] : '';
			$tour_review            = isset( $_POST['tf-tour-review-section'] ) ? $_POST['tf-tour-review-section'] : '';
			$tour_related           = isset( $_POST['tf-tour-related-section'] ) ? $_POST['tf-tour-related-section'] : '';
			$tour_permalink         = isset( $_POST['tf-tour-permalink'] ) ? $_POST['tf-tour-permalink'] : '';

			//skip steps
			$skip_steps = isset( $_POST['tf-skip-steps'] ) ? $_POST['tf-skip-steps'] : [];
			$skip_steps = explode( ',', $skip_steps );

			if ( ! in_array( 1, $skip_steps ) ) {
				$services = array_diff( $tf_services, $services );
				$services = array_map( 'sanitize_text_field', $services );

				$tf_settings['disable-services'] = [];
				if ( ! empty( $services ) ) {
					foreach ( $services as $service ) {
						$tf_settings['disable-services'][ $service ] = $service;
					}
				}
			}

			if ( ! in_array( 2, $skip_steps ) ) {
				$tf_settings['search-result-page'] = ! empty( $search_page ) ? $search_page : '';
				$tf_settings['posts_per_page']     = ! empty( $search_result_per_page ) ? $search_result_per_page : '';
				$tf_settings['wl-page']            = ! empty( $wishlist_page ) ? $wishlist_page : '';
				$tf_settings['r-auto-publish']     = ! empty( $auto_publish ) ? $auto_publish : '';
			}

			if ( ! in_array( 3, $skip_steps ) && ! in_array( 'hotel', $services ) ) {
				$tf_settings['h-review'] = ! empty( $hotel_review ) ? 0 : 1;
				$tf_settings['h-share']  = ! empty( $hotel_share ) ? 0 : 1;

				if ( ! empty( $hotel_permalink ) ) {
					update_option( 'hotel_slug', $hotel_permalink );
				}
			}

			if ( ! in_array( 3, $skip_steps ) && ! in_array( 'tour', $services ) ) {
				$tf_settings['t-review']  = ! empty( $tour_review ) ? 0 : 1;
				$tf_settings['t-related'] = ! empty( $tour_related ) ? 0 : 1;

				if ( ! empty( $tour_permalink ) ) {
					update_option( 'tour_slug', $tour_permalink );
				}
			}

			update_option( 'tf_settings', $tf_settings );
			$response              = [
				'success'      => true,
				'redirect_url' => esc_url( admin_url( 'admin.php?page=tf_settings' ) )
			];

			echo json_encode( $response );
			wp_die();
		}
	}
}

TF_Setup_Wizard::instance();