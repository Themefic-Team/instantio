<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Instantio_Settings' ) ) {
	class Instantio_Settings {

		public $option_id       = null;
		public $option_title    = null;
		public $option_icon     = null;
		public $option_position = null;
		public $option_sections = array();
		public $pre_tabs        = array();
		public $pre_fields      = array();
		public $pre_sections    = array();

		public function __construct( $key, $params = array() ) {
			$this->option_id = $key;
			$this->option_title = ! empty( $params['title'] ) ? apply_filters( 'instantio_' . $key . '_title', $params['title'] ) : '';
			$this->option_icon = ! empty( $params['icon'] ) ? apply_filters( 'instantio_' . $key . '_icon', $params['icon'] ) : '';
			$this->option_position = ! empty( $params['position'] ) ? apply_filters( 'instantio_' . $key . '_position', $params['position'] ) : 5;
			$this->option_sections = ! empty( $params['sections'] ) ? apply_filters( 'instantio_' . $key . '_sections', $params['sections'] ) : array();

			// run only is admin panel options, avoid performance loss
			$this->pre_tabs = $this->pre_tabs( $this->option_sections );
			$this->pre_fields = $this->pre_fields( $this->option_sections );
			$this->pre_sections = $this->pre_sections( $this->option_sections );

			//options
			add_action( 'admin_menu', array( $this, 'instantio_tf_options' ) );

			//save options
			add_action( 'admin_init', array( $this, 'save_options' ) );

			//ajax save options
			add_action( 'wp_ajax_instantio_options_save', array( $this, 'tf_ajax_save_options' ) );
			add_action( 'wp_ajax_instantio_themefic_manage_plugin', array( $this, 'instantio_themefic_manage_plugin' ) );

			// constent defined
			if ( ! defined( 'INSTANTIO_OPTION_ID' ) ) {
				define( 'INSTANTIO_OPTION_ID', $this->option_id );
			}
		}



		public static function option( $key, $params = array() ) {
			return new self( $key, $params );
		}

		public function pre_tabs( $sections ) {

			$result = array();
			$parents = array();

			foreach ( $sections as $key => $section ) {
				if ( ! empty( $section['parent'] ) ) {
					$parents[ $section['parent'] ][ $key ] = $section;
					unset( $sections[ $key ] );
				}
			}

			foreach ( $sections as $key => $section ) {
				if ( ! empty( $key ) && ! empty( $parents[ $key ] ) ) {
					$section['sub_section'] = $parents[ $key ];
				}
				$result[ $key ] = $section;
			}

			return $result;
		}

		public function pre_fields( $sections ) {

			$result = array();

			foreach ( $sections as $key => $section ) {
				if ( ! empty( $section['fields'] ) ) {
					foreach ( $section['fields'] as $field ) {
						$result[] = $field;
					}
				}
			}

			return $result;
		}

		public function pre_sections( $sections ) {

			$result = array();

			foreach ( $this->pre_tabs as $tab ) {
				if ( ! empty( $tab['subs'] ) ) {
					foreach ( $tab['subs'] as $sub ) {
						$sub['ptitle'] = $tab['title'];
						$result[] = $sub;
					}
				}
				if ( empty( $tab['subs'] ) ) {
					$result[] = $tab;
				}
			}

			return $result;
		}

		/**
		 * Options Page menu
		 * @author Foysal
		 */
		public function instantio_tf_options() {
			add_menu_page(
				$this->option_title,
				$this->option_title,
				'manage_options',
				$this->option_id,
				array( $this, 'ins_admin_options_page' ),
				$this->option_icon,
				$this->option_position
			);

			//What's New submenu
			add_submenu_page(
				$this->option_id,
				__( 'Dashboard', 'instantio' ),
				__( 'Dashboard', 'instantio' ),
				'manage_options',
				'ins_dashboard',
				array( $this, 'ins_get_dashboard_callback' ),
			);


			//Setting submenu
			add_submenu_page(
				$this->option_id,
				__( 'Settings', 'instantio' ),
				__( 'Settings', 'instantio' ),
				'manage_options',
				$this->option_id . '#tab=general',
				array( $this, 'ins_admin_options_page' ),
			);
			// remove first submenu
			remove_submenu_page( $this->option_id, $this->option_id );

		}


		/**
		 * Page top header
		 * @author M Hemel Hasan
		 */
		function ins_admin_top_header() {
			?>
			<div class="tf-setting-top-bar">
				<div class="version">
					<img src="<?php echo esc_url( INSTANTIO_ADMIN_URL . '/tf-options/img/instanio-logo.png' ); ?>" alt="logo">
					<span>
						<?php echo esc_html( INSTANTIO_VERSION ); ?>
					</span>
				</div>
				<div class="other-document">
					<svg width="26" height="25" viewBox="0 0 26 25" fill="none" xmlns="http://www.w3.org/2000/svg"
						style="color: #DB5209;">
						<path
							d="M19.2106 0H6.57897C2.7895 0 0.263184 2.52632 0.263184 6.31579V13.8947C0.263184 17.6842 2.7895 20.2105 6.57897 20.2105V22.9011C6.57897 23.9116 7.70318 24.5179 8.53687 23.9495L14.1579 20.2105H19.2106C23 20.2105 25.5263 17.6842 25.5263 13.8947V6.31579C25.5263 2.52632 23 0 19.2106 0ZM12.8948 15.3726C12.3642 15.3726 11.9474 14.9432 11.9474 14.4253C11.9474 13.9074 12.3642 13.4779 12.8948 13.4779C13.4253 13.4779 13.8421 13.9074 13.8421 14.4253C13.8421 14.9432 13.4253 15.3726 12.8948 15.3726ZM14.4863 10.1305C13.9937 10.4589 13.8421 10.6737 13.8421 11.0274V11.2926C13.8421 11.8105 13.4127 12.24 12.8948 12.24C12.3769 12.24 11.9474 11.8105 11.9474 11.2926V11.0274C11.9474 9.56211 13.0211 8.84211 13.4253 8.56421C13.8927 8.24842 14.0442 8.03368 14.0442 7.70526C14.0442 7.07368 13.5263 6.55579 12.8948 6.55579C12.2632 6.55579 11.7453 7.07368 11.7453 7.70526C11.7453 8.22316 11.3158 8.65263 10.7979 8.65263C10.28 8.65263 9.85055 8.22316 9.85055 7.70526C9.85055 6.02526 11.2148 4.66105 12.8948 4.66105C14.5748 4.66105 15.939 6.02526 15.939 7.70526C15.939 9.14526 14.8779 9.86526 14.4863 10.1305Z"
							fill="#DB5209"></path>
					</svg>

					<div class="dropdown">
						<div class="list-item">
							<a href="https://portal.themefic.com/support/" target="_blank">
								<svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path
										d="M10.0482 4.37109H4.30125C4.06778 4.37109 3.84329 4.38008 3.62778 4.40704C1.21225 4.6137 0 6.04238 0 8.6751V12.2693C0 15.8634 1.43674 16.5733 4.30125 16.5733H4.66044C4.85799 16.5733 5.1184 16.708 5.23514 16.8608L6.3127 18.2985C6.78862 18.9364 7.56087 18.9364 8.03679 18.2985L9.11435 16.8608C9.24904 16.6811 9.46456 16.5733 9.68905 16.5733H10.0482C12.6793 16.5733 14.107 15.3692 14.3136 12.9432C14.3405 12.7275 14.3495 12.5029 14.3495 12.2693V8.6751C14.3495 5.80876 12.9127 4.37109 10.0482 4.37109ZM4.04084 11.5594C3.53798 11.5594 3.14288 11.1551 3.14288 10.6609C3.14288 10.1667 3.54696 9.76233 4.04084 9.76233C4.53473 9.76233 4.93881 10.1667 4.93881 10.6609C4.93881 11.1551 4.53473 11.5594 4.04084 11.5594ZM7.17474 11.5594C6.67188 11.5594 6.27678 11.1551 6.27678 10.6609C6.27678 10.1667 6.68086 9.76233 7.17474 9.76233C7.66862 9.76233 8.07271 10.1667 8.07271 10.6609C8.07271 11.1551 7.6776 11.5594 7.17474 11.5594ZM10.3176 11.5594C9.81476 11.5594 9.41966 11.1551 9.41966 10.6609C9.41966 10.1667 9.82374 9.76233 10.3176 9.76233C10.8115 9.76233 11.2156 10.1667 11.2156 10.6609C11.2156 11.1551 10.8115 11.5594 10.3176 11.5594Z"
										fill="#DB5209"></path>
									<path
										d="M17.9423 5.08086V8.67502C17.9423 10.4721 17.3855 11.6941 16.272 12.368C16.0026 12.5298 15.6884 12.3141 15.6884 11.9996L15.6973 8.67502C15.6973 5.08086 13.641 3.0232 10.0491 3.0232L4.58048 3.03219C4.26619 3.03219 4.05067 2.7177 4.21231 2.44814C4.88578 1.33395 6.10702 0.776855 7.89398 0.776855H13.641C16.5055 0.776855 17.9423 2.21452 17.9423 5.08086Z"
										fill="#DB5209"></path>
								</svg>
								<span>
									<?php esc_html_e( "Need Help?", "instantio" ); ?>
								</span>
							</a>
							<a href="https://themefic.com/docs/instantio/" target="_blank">
								<svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path
										d="M16.1896 7.57803H13.5902C11.4586 7.57803 9.72274 5.84103 9.72274 3.70803V1.10703C9.72274 0.612031 9.318 0.207031 8.82332 0.207031H5.00977C2.23956 0.207031 0 2.00703 0 5.22003V13.194C0 16.407 2.23956 18.207 5.00977 18.207H12.0792C14.8494 18.207 17.089 16.407 17.089 13.194V8.47803C17.089 7.98303 16.6843 7.57803 16.1896 7.57803ZM8.09478 14.382H4.4971C4.12834 14.382 3.82254 14.076 3.82254 13.707C3.82254 13.338 4.12834 13.032 4.4971 13.032H8.09478C8.46355 13.032 8.76935 13.338 8.76935 13.707C8.76935 14.076 8.46355 14.382 8.09478 14.382ZM9.89363 10.782H4.4971C4.12834 10.782 3.82254 10.476 3.82254 10.107C3.82254 9.73803 4.12834 9.43203 4.4971 9.43203H9.89363C10.2624 9.43203 10.5682 9.73803 10.5682 10.107C10.5682 10.476 10.2624 10.782 9.89363 10.782Z"
										fill="#DB5209"></path>
								</svg>
								<span>
									<?php esc_html_e( "Documentation", "instantio" ); ?>
								</span>

							</a>
							<a href="https://themefic.com/feature-request/" target="_blank">
								<svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd"
										d="M13.5902 7.57803H16.1896C16.6843 7.57803 17.089 7.98303 17.089 8.47803V13.194C17.089 16.407 14.8494 18.207 12.0792 18.207H5.00977C2.23956 18.207 0 16.407 0 13.194V5.22003C0 2.00703 2.23956 0.207031 5.00977 0.207031H8.82332C9.318 0.207031 9.72274 0.612031 9.72274 1.10703V3.70803C9.72274 5.84103 11.4586 7.57803 13.5902 7.57803ZM11.9613 0.396012C11.5926 0.0270125 10.954 0.279013 10.954 0.792013V3.93301C10.954 5.24701 12.0693 6.33601 13.4274 6.33601C14.2818 6.34501 15.4689 6.34501 16.4852 6.34501H16.4854C16.998 6.34501 17.2679 5.74201 16.9081 5.38201C16.4894 4.96018 15.9637 4.42927 15.3988 3.85888L15.3932 3.85325L15.3913 3.85133L15.3905 3.8505L15.3902 3.85016C14.2096 2.65803 12.86 1.29526 11.9613 0.396012ZM3.0145 12.0732C3.0145 11.7456 3.28007 11.48 3.60768 11.48H5.32132V9.76639C5.32132 9.43879 5.58689 9.17321 5.9145 9.17321C6.2421 9.17321 6.50768 9.43879 6.50768 9.76639V11.48H8.22131C8.54892 11.48 8.8145 11.7456 8.8145 12.0732C8.8145 12.4008 8.54892 12.6664 8.22131 12.6664H6.50768V14.38C6.50768 14.7076 6.2421 14.9732 5.9145 14.9732C5.58689 14.9732 5.32132 14.7076 5.32132 14.38V12.6664H3.60768C3.28007 12.6664 3.0145 12.4008 3.0145 12.0732Z"
										fill="#DB5209"></path>
								</svg>
								<span>
									<?php esc_html_e( "Feature Request", "instantio" ); ?>
								</span>
							</a>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Dashboard tab pages 
		 * @author M Hemel Hasan
		 */
		public function ins_get_dashboard_callback() {
			include_once 'Ins_ChangeLog.php';
			?>
			<div class="tf-setting-dashboard">
				<?php $this->ins_admin_top_header(); ?>
				
				<div class="ins-dashboard-promo-banner-header">
					<?php do_action( 'instantio_dashboard_promo_notice' ); ?>
				</div>
				<div class="ins-dashboad-wrapper">
					<ul class="dashboad-tab">
						<li class="dashboad-tab-singel active">
							<span>
								<?php esc_html_e( "General", "instantio" ); ?>
							</span>
						</li>
						<li class="dashboad-tab-singel">
							<span>
								<?php esc_html_e( "Tutorial", "instantio" ); ?>
							</span>
						</li>
						<li class="dashboad-tab-singel">
							<span>
								<?php esc_html_e( "Pro", "instantio" ); ?>
							</span>
						</li>
						<li class="dashboad-tab-singel">
							<span>
								<?php esc_html_e( "FAQs", "instantio" ); ?>
							</span>
						</li>
						<li class="dashboad-tab-singel">
							<span>
								<?php esc_html_e( "What's New", "instantio" ); ?>
							</span>
						</li>
					</ul>

					<div class="dashboad-content-wrap">

						<div class="dashboad-content help-center active">
							<div class="tf-settings-help-center">
								<div class="tf-help-center-banner"
							style="background-image: url('<?php echo esc_url( INSTANTIO_ADMIN_URL . '/tf-options/img/wizard/setup_wizard_bg.png' ); ?>')">
									<div class="tf-help-center-content">
										<h2>
											<?php esc_html_e( "Setup Wizard", "instantio" ); ?>
										</h2>
										<p>
											<?php esc_html_e( "Click the button below to run the setup wizard of Instantio. Your existing settings will be change.", "instantio" ); ?>
										</p>
										<a href="<?php echo esc_url( admin_url( 'admin.php?page=ins-setup-wizard' ) ) ?>"
											class="tf-admin-btn tf-btn-secondary">
											<?php esc_html_e( "Setup Wizard", "instantio" ); ?>
										</a>
									</div>
									<!-- <div class="tf-help-center-content-img">
										<img src="<?php // echo INSTANTIO_ADMIN_URL ?>/tf-options/img/wizard/setup_wizard_icon.svg" alt="image"/>
									</div> -->

								</div>

								<div class="tf-support-document">
									<div class="tf-single-support">
										<a href="https://themefic.com/docs/instantio/" target="_blank">
											<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../img/wizard/tf-documents.svg' ) ?>"
												alt="Document">
											<h3>
												<?php esc_html_e( "Documentation", "instantio" ); ?>
											</h3>
											<p>
												<?php esc_html_e( "How the plugin works, what it can do, and how to use it.", "instantio" ); ?>
											</p>
											<span>
												<?php esc_html_e( "Read More", "instantio" ); ?>
											</span>
										</a>
									</div>
									
									<div class="tf-single-support">
										<a href="https://www.youtube.com/playlist?list=PLY0rtvOwg0ykIvNBa8XI3SR7WEbdqqKoO"
											target="_blank">
											<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../img/wizard/tf-tutorial.svg' ) ?>"
												alt="Document">
											<h3>
												<?php esc_html_e( "Video Tutorials", "instantio" ); ?>
											</h3>
											<p>
												<?php esc_html_e( "We allows you to get help in real-time, which can improve satisfaction.", "instantio" ); ?>
											</p>
											<span>
												<?php esc_html_e( "Watch Video", "instantio" ); ?>
											</span>
										</a>
									</div>
								</div>
 
							</div>
						</div>

						<div class="dashboad-content tutorial">
							<div class="tutorial_wrapper">
								<div class="tutorial-heading">
									<h4>
										<?php esc_html_e( "Basic Tutorials", "instantio" ); ?>
									</h4>

									<a target="_blank"
										href="https://www.youtube.com/playlist?list=PLY0rtvOwg0ykIvNBa8XI3SR7WEbdqqKoO"
										class="btn view-all-btn">
										<?php esc_html_e( "View all", "instantio" ); ?>
									</a>
								</div>
								<div class="tutorial-body">
									<figure class="figure">
										<div class="main-caption">
											<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../img/tutorial-2.png' ) ?>"
												class="figure-img" alt="turorial" />
											<div class="play-button-overlap">
												<a target="_blank"
													href="https://www.youtube.com/watch?v=1biwrwu-Io8&list=PLY0rtvOwg0ykIvNBa8XI3SR7WEbdqqKoO&index=2">
													<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../img/play.png' ) ?>"
														alt="turorial" /></a>
											</div>
										</div>
										<figcaption class="figure-caption">
											<?php esc_html_e( "How to setup a Fast WooCommerce Checkout", "instantio" ); ?>
										</figcaption>
									</figure>

									<figure class="figure">
										<div class="main-caption">
											<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../img/tutorial-1.png' ) ?>"
												class="figure-img" alt="turorial" />
											<div class="play-button-overlap">
												<a target="_blank"
													href="https://www.youtube.com/watch?v=2RYjb-dZSlE&list=PLY0rtvOwg0ykIvNBa8XI3SR7WEbdqqKoO&index=1">
													<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../img/play.png' ) ?>"
														alt="turorial" /></a>
											</div>
										</div>
										<figcaption class="figure-caption">
											<?php esc_html_e( "How to Install / Update Instantio", "instantio" ); ?>
										</figcaption>
									</figure>

									<figure class="figure">
										<div class="main-caption">
											<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../img/tutorial.png' ) ?>"
												class="figure-img" alt="turorial" />
											<div class="play-button-overlap">
												<a target="_blank"
													href="https://www.youtube.com/watch?v=tW9iRCYASSs&list=PLY0rtvOwg0ykIvNBa8XI3SR7WEbdqqKoO&index=3">
													<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../img/play.png' ) ?>"
														alt="turorial" />
												</a>
											</div>
										</div>
										<figcaption class="figure-caption">
											<?php esc_html_e( "Instant Checkout for WooCommerce", "instantio" ); ?>
										</figcaption>
									</figure>
								</div>
							</div>
						</div>

						<div class="dashboad-content premium">
							<div class="premium_wrapper">
								<div class="premium-heading">
									<h4>
										<?php esc_html_e( "Pro Features", "instantio" ); ?>
									</h4>

									<a target="_blank" href="https://themefic.com/instantio/" class="btn view-all-btn">
										<?php esc_html_e( "View all", "instantio" ); ?>
									</a>
								</div>
								<div class="premium-body">
									<figure class="figure">
										<div class="main-caption">
											<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../img/pro/Side-CartCheckout-Pro-Multi-Step.jpg' ) ?>"
												class="figure-img" alt="turorial" />
										</div>
										<figcaption class="figure-caption">
											<h4>
												<?php esc_html_e( "Side Cart + Side Checkout (Multi Step)", "instantio" ); ?>
											</h4>
											<p>
												<?php esc_html_e( "Customer will checkout from Same Window (Side drawer). The checkout process will be Multi-step (Cart -> Checkout, No Reload).", "instantio" ); ?>
											</p>
											<a target="_blank" href="https://themefic.com/instantio/" class="btn-premium-fea">
												<?php esc_html_e( "See Preview", "instantio" ); ?>
											</a>
										</figcaption>
									</figure>

									<figure class="figure">
										<div class="main-caption">
											<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../img/pro/Side-CartCheckout-Pro-Single-Step.jpg' ) ?>"
												class="figure-img" alt="turorial" />
										</div>
										<figcaption class="figure-caption">
											<h4>
												<?php esc_html_e( "Side Cart + Side Checkout (Single Step)", "instantio" ); ?>
											</h4>
											<p>
												<?php esc_html_e( "The checkout process will be Single-step. Cart & Checkout will be shown on the Same Window, No Page Reload.", "instantio" ); ?>
											</p>
											<a target="_blank" href="https://themefic.com/instantio/"
												class="btn-premium-fea">
												<?php esc_html_e( "See Preview", "instantio" ); ?>
											</a>
										</figcaption>
									</figure>

									<figure class="figure">
										<div class="main-caption">
											<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../img/pro/Popup-CartCheckout-Pro-Multi-Step.jpg' ) ?>"
												class="figure-img" alt="turorial" />
										</div>
										<figcaption class="figure-caption">
											<h4>
												<?php esc_html_e( "Popup Cart + Popup Checkout (Multi Step)", "instantio" ); ?>
											</h4>

											<p>
												<?php esc_html_e( "Customer will checkout from Same Window (Popup). The checkout process will be Multi-step (Cart -> Checkout)", "instantio" ); ?>
											</p>

											<a target="_blank" href="https://themefic.com/instantio/" class="btn-premium-fea">
												<?php esc_html_e( "See Preview", "instantio" ); ?>
											</a>
										</figcaption>
									</figure>

									<figure class="figure">
										<div class="main-caption">
											<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../img/pro/Popup-CartCheckout-Pro-Single-Step.jpg' ) ?>"
												class="figure-img" alt="turorial" />
										</div>
										<figcaption class="figure-caption">
											<h4>
												<?php esc_html_e( "Popup Cart + Popup Checkout (Single Step)", "instantio" ); ?>
											</h4>
											<p>
												<?php esc_html_e( "The checkout process will be Single-step Popup (Cart & Checkout on Same Window, No Page Reload).", "instantio" ); ?>
											</p>
											<a target="_blank" href="https://themefic.com/instantio/"
												class="btn-premium-fea">
												<?php esc_html_e( "See Preview", "instantio" ); ?>
											</a>
										</figcaption>
									</figure>

									<figure class="figure">
										<div class="main-caption">
											<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../img/pro/Up-Sell.jpg' ) ?>"
												class="figure-img" alt="turorial" />
										</div>
										<figcaption class="figure-caption">
											<h4>
												<?php esc_html_e( "Upsell (Pro)", "instantio" ); ?>
											</h4>
											<p>
												<?php esc_html_e( "Instantio offer Ajax-based Upsell feature with which you can sell related or complementary products to a customer.", "instantio" ); ?>
											</p>
											<a target="_blank" href="https://themefic.com/instantio/" class="btn-premium-fea">
												<?php esc_html_e( "See Preview", "instantio" ); ?>
											</a>
										</figcaption>
									</figure>

									<figure class="figure">
										<div class="main-caption">
											<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../img/pro/Cross-Sell.jpg' ) ?>"
												class="figure-img" alt="turorial" />
										</div>
										<figcaption class="figure-caption">
											<h4>
												<?php esc_html_e( "Cross-sell (Pro)", "instantio" ); ?>
											</h4>
											<p>
												<?php esc_html_e( "With Instantio, you can also do Ajax based cross-sell by selling related or complementary products to a customer.", "instantio" ); ?>
											</p>
											<a target="_blank" href="https://themefic.com/instantio/" class="btn-premium-fea">
												<?php esc_html_e( "See Preview", "instantio" ); ?>
											</a>
										</figcaption>
									</figure>

									<figure class="figure">
										<div class="main-caption">
											<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../img/pro/Dedicated-Mobile-Layout.png' ) ?>"
												class="figure-img" alt="turorial" />
										</div>
										<figcaption class="figure-caption">
											<h4>
												<?php esc_html_e( "Dedicated Mobile Layout", "instantio" ); ?>
											</h4>
											<p>
												<?php esc_html_e( "A dedicated mobile layout for smaller devices to make your checkout process much smoother for customers.", "instantio" ); ?>
											</p>
											<a target="_blank" href="https://themefic.com/instantio/" class="btn-premium-fea">
												<?php esc_html_e( "See Preview", "instantio" ); ?>
											</a>
										</figcaption>
									</figure>

								</div>
							</div>
						</div>

						<div class="dashboad-content faqs">
							<div class="faqs_wrapper">
								<div class="faqs-heading">
									<h4>
										<?php esc_html_e( "Frequently asked questions", "instantio" ); ?>
									</h4>
								</div>

								<div class="tf-accordion-wrapper">
									<div class="tf-accrodian-item">
										<div class="tf-single-faq">
											<div class="tf-faq-title">
												<i class="fas fa-angle-down"></i>
												<h4>
													<?php esc_html_e( "1. What is WooCommerce One Page Checkout?", "instantio" ); ?>
												</h4>
											</div>
											<div class="tf-faq-desc">
												<p>
													<?php esc_html_e( "WooCommerce One Page Checkout means converting the default multistep checkout for WooCommerce process into a single page Checkout. WordPress Plugins like Instantio offers such a solution.", "instantio" ); ?>
												</p>
											</div>
										</div>
									</div>
									<div class="tf-accrodian-item">
										<div class="tf-single-faq">
											<div class="tf-faq-title">
												<i class="fas fa-angle-down"></i>
												<h4>
													<?php esc_html_e( "2. What is Direct Checkout for WooCommerce?", "instantio" ); ?>
												</h4>
											</div>
											<div class="tf-faq-desc">
												<p>
													<?php esc_html_e( "WooCommerce Direct Checkout is a solution to reduce the steps of the default Woocommerce checkout process. Customers can skip the cart page and directly checkout woocommerce (go directly to the checkout page). This helps improving cart abandonment of a website. Our Plugin Instantio offers such a solution.", "instantio" ); ?>
												</p>
											</div>
										</div>
									</div>
									<div class="tf-accrodian-item">
										<div class="tf-single-faq">
											<div class="tf-faq-title">
												<i class="fas fa-angle-down"></i>
												<h4>
													<?php esc_html_e( "3. How to install Instantio?", "instantio" ); ?>
												</h4>
											</div>
											<div class="tf-faq-desc">
												<p>
												<?php echo wp_kses_post( __( "See the installation link. <a target='_blank' href='https://wordpress.org/plugins/instantio/#installation'>Install Link</a>", 'instantio' ) ); ?>
												</p>
											</div>
										</div>
									</div>
									<div class="tf-accrodian-item">
										<div class="tf-single-faq">
											<div class="tf-faq-title">
												<i class="fas fa-angle-down"></i>
												<h4>
													<?php esc_html_e( "4. Is the Free version fully free or there is a gap?", "instantio" ); ?>
												</h4>
											</div>
											<div class="tf-faq-desc">
												<p>
													<?php esc_html_e( "Yes, Instantio is fully free which is available on WordPress.org. This free version will always be free. It also has a pro version with additional features which you can purchase from our official website.", "instantio" ); ?>
												</p>
											</div>
										</div>
									</div>
									<div class="tf-accrodian-item">
										<div class="tf-single-faq">
											<div class="tf-faq-title">
												<i class="fas fa-angle-down"></i>
												<h4>
													<?php esc_html_e( "5. Is the free version supported?", "instantio" ); ?>
												</h4>
											</div>
											<div class="tf-faq-desc">
												<p>
													<?php esc_html_e( "Yes, we fully support both the free and pro version. Please feel free to post questions or bug reports through our website, but for timely support, we recommend purchasing Pro version.", "instantio" ); ?>
												</p>
											</div>
										</div>
									</div>
									<div class="tf-accrodian-item">
										<div class="tf-single-faq">
											<div class="tf-faq-title">
												<i class="fas fa-angle-down"></i>
												<h4>
													<?php esc_html_e( "5. Will I be able to edit WooCommerce checkout page with Instantio?", "instantio" ); ?>
												</h4>
											</div>
											<div class="tf-faq-desc">
												<p>
													<?php esc_html_e( "Yes, Instantio allows you to edit WooCommerce checkout page to some extent. You can remove the cart page and make your customer directly go to the checkout page.", "instantio" ); ?>
												</p>
											</div>
										</div>
									</div>
									<div class="tf-accrodian-item">
										<div class="tf-single-faq">
											<div class="tf-faq-title">
												<i class="fas fa-angle-down"></i>
												<h4>
													<?php esc_html_e( "6. Does Instantio allows WooCommerce one-click checkout?", "instantio" ); ?>
												</h4>
											</div>
											<div class="tf-faq-desc">
												<p>
													<?php esc_html_e( "Yes, Instantio converts WooCommerce multistep checkout process into WooCommerce one click checkout.", "instantio" ); ?>
												</p>
											</div>
										</div>
									</div>
									<div class="tf-accrodian-item">
										<div class="tf-single-faq">
											<div class="tf-faq-title">
												<i class="fas fa-angle-down"></i>
												<h4>
													<?php esc_html_e( "7. Will I be able to skip cart page on WooCommerce?", "instantio" ); ?>
												</h4>
											</div>
											<div class="tf-faq-desc">
												<p>
													<?php esc_html_e( "Yes, Instantio allows you to skip cart page WooCommerce and make your customer directly go to the checkout page.", "instantio" ); ?>
												</p>
											</div>
										</div>
									</div>
									<div class="tf-accrodian-item">
										<div class="tf-single-faq">
											<div class="tf-faq-title">
												<i class="fas fa-angle-down"></i>
												<h4>
													<?php esc_html_e( "8. Does Instantio allows WooCommerce Quick checkout?", "instantio" ); ?>
												</h4>
											</div>
											<div class="tf-faq-desc">
												<p>
													<?php esc_html_e( "Yes, Instantio converts the default multistep WooCommerce checkout process into WooCommerce Quick checkout.", "instantio" ); ?>
												</p>
											</div>
										</div>
									</div>
									<div class="tf-accrodian-item">
										<div class="tf-single-faq">
											<div class="tf-faq-title">
												<i class="fas fa-angle-down"></i>
												<h4>
													<?php esc_html_e( "9. Does Instantio allows WooCommerce Express checkout?", "instantio" ); ?>
												</h4>
											</div>
											<div class="tf-faq-desc">
												<p>
													<?php esc_html_e( "Yes, Instantio converts the default multistep WooCommerce checkout process into WooCommerce Express checkout.", "instantio" ); ?>
												</p>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>

						<div class="dashboad-content whatnew">
							<div class="whatnew_wrapper">
								<div class="whatnew-heading">
									<h4>
										<?php esc_html_e( "All updates", "instantio" ); ?>
									</h4>
								</div>
								<div class="whatnew_updates">
									<?php
									if ( ! empty( $instantio_change ) ) {
										foreach ( $instantio_change as $key => $value ) { ?>

											<div class="whatnew_updates_card">
												<div class="cardleft_date_version">
													<div class="ins_cardleft_date">
									<?php echo esc_html( $value['date'] ); ?>
													</div>
													<div class="ins_cardleft_version">
									<?php echo esc_html( $value['version'] ); ?>
													</div>
												</div>

												<div class="cardright_changelog">
													<?php
													$changelogs = $value['changelog'];
													if ( ! empty( $changelogs ) ) {
														foreach ( $changelogs as $key => $values ) { ?>
								<ul class="ins_changelog_<?php echo esc_attr( $key ); ?>">
																<span>
									<?php echo esc_html( $key ); ?>
																</span>
																<?php foreach ( $values as $value ) { ?>
																	<li>
										<?php echo wp_kses_post( $value ); ?>
																	</li>
																<?php } ?>
															</ul>
															<?php
														}
													} ?>
												</div>
											</div>

										<?php }
									} else { ?>

										<div class="whatnew_updates_card">
											<div class="chnagelog_not_found">
												<?php esc_html_e( "No change logs found. Please try again later. Maybe the changelog is being updated, it will come shortly.", "instantio" ); ?>
											</div>
										</div>

									<?php } ?>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
			<?php
		}


		/**
		 * Options Page
		 * @author M Hemel Hasan
		 */
		public function ins_admin_options_page() {

			// Retrieve an existing value from the database.
			$tf_option_value = get_option( $this->option_id );
			$current_page_url = $this->get_current_page_url();
			$query_string = $this->get_query_string( $current_page_url );

			// Set default values.
			if ( empty( $tf_option_value ) ) {
				$tf_option_value = array();
			}


			$ajax_save_class = 'tf-ajax-save';

			if ( ! empty( $this->option_sections ) ) :
				?>
				<div class="tf-setting-dashboard">

					<div class="instantio-settings-page">
						
						<div class="tf-option-wrapper tf-setting-wrapper">
							<form method="post" action="" class="tf-option-form <?php echo esc_attr( $ajax_save_class ) ?>"
								enctype="multipart/form-data">

								<!-- Body -->
								<div class="tf-option">
									<div class="tf-admin-tab tf-option-nav">
										<?php
										$section_count = 0;
										foreach ( $this->pre_tabs as $key => $section ) :
											$parent_tab_key = ! empty( $section['fields'] ) ? $key : array_key_first( $section['sub_section'] );
											?>
											<div
												class="tf-admin-tab-item<?php echo ! empty( $section['sub_section'] ) ? ' tf-has-submenu' : '' ?>">

												<a href="#<?php echo esc_attr( $parent_tab_key ); ?>"
													class="tf-tablinks <?php echo $section_count == 0 ? 'active' : ''; ?>"
													data-tab="<?php echo esc_attr( $parent_tab_key ) ?>">
													<?php echo ! empty( $section['icon'] ) ? '<span class="tf-sec-icon"><i class="' . esc_attr( $section['icon'] ) . '"></i></span>' : ''; ?>
									<?php echo esc_html( $section['title'] ); ?>
												</a>

												<?php if ( ! empty( $section['sub_section'] ) ) : ?>
													<ul class="tf-submenu">
														<?php foreach ( $section['sub_section'] as $sub_key => $sub ) : ?>
															<li>
																<a href="#<?php echo esc_attr( $sub_key ); ?>"
																	class="tf-tablinks <?php echo $section_count == 0 ? 'active' : ''; ?>"
																	data-tab="<?php echo esc_attr( $sub_key ) ?>">
																	<span class="tf-tablinks-inner">
																		<?php echo ! empty( $sub['icon'] ) ? '<span class="tf-sec-icon"><i class="' . esc_attr( $sub['icon'] ) . '"></i></span>' : ''; ?>
																		<?php echo esc_html( $sub['title'] ); ?>
																	</span>
																</a>
															</li>
														<?php endforeach; ?>
													</ul>
												<?php endif; ?>
											</div>
											<?php $section_count++; endforeach; ?>
									</div>

									<div class="tf-tab-wrapper">
										<div class="tf-mobile-setting">
											<a href="#" class="tf-mobile-tabs"><i class="fa-solid fa-bars"></i></a>
										</div>
										<?php
										$content_count = 0;
										foreach ( $this->option_sections as $key => $section ) : ?>
											<div id="<?php echo esc_attr( $key ) ?>"
												class="tf-tab-content <?php echo $content_count == 0 ? 'active' : ''; ?>">

												<?php
												if ( ! empty( $section['fields'] ) ) :
													foreach ( $section['fields'] as $field ) :

														$default = isset( $field['default'] ) ? $field['default'] : '';
														$value = isset( $tf_option_value[ $field['id'] ] ) ? $tf_option_value[ $field['id'] ] : $default;

														$tf_option = new Instantio_Options();
														$tf_option->field( $field, $value, $this->option_id );

													endforeach;
												endif; ?>

											</div>
											<?php $content_count++; endforeach; ?>

										<!-- Footer -->
										<div class="tf-option-footer">
											<button type="submit" class="tf-admin-btn tf-btn-secondary tf-submit-btn">
												<?php esc_html_e( 'Save', 'instantio' ); ?>
											</button>
										</div>
									</div>
								</div>
								<?php wp_nonce_field( 'instantio_option_nonce_action', 'instantio_option_nonce' ); ?>
							</form>
						</div>					
					</div>
				</div>
				<?php
			endif;
		}

		public function tf_get_sidebar_plugin_list() {
			$plugins = [
				[
					'name'       => 'Hydra',
					'slug'       => 'hydra-booking',
					'file_name'  => 'hydra-booking',
					'subtitle'   => 'All in One Appointment Booking System',
					'image'      => INSTANTIO_ADMIN_URL . '/tf-options/img/instanio-logo.png',
					// 'pro'        => [
					// 	'slug'      => 'hydra-booking-pro',
					// 	'file_name' => 'hydra-booking-pro',
					// 	'url'       => 'https://hydrabooking.com/',
					// ],
				],
				[
					'name'       => 'UACF7',
					'slug'       => 'ultimate-addons-for-contact-form-7',
					'file_name'  => 'ultimate-addons-for-contact-form-7',
					'subtitle'   => '40+ Essential Addons for Contact Form 7',
					'image'      => INSTANTIO_ADMIN_URL . '/tf-options/img/instanio-logo.png',
					// 'pro'        => [
					// 	'slug'      => 'ultimate-addons-for-contact-form-7-pro',
					// 	'file_name' => 'ultimate-addons-for-contact-form-7-pro',
					// 	'url'       => 'https://cf7addons.com/pricing/',
					// ],
				],
				[
					'name'       => 'BEAF',
					'slug'       => 'beaf-before-and-after-gallery',
					'file_name'  => 'before-and-after-gallery',
					'subtitle'   => 'Ultimate Before After Image Slider & Gallery',
					'image'      => INSTANTIO_ADMIN_URL . '/tf-options/img/instanio-logo.png',
					// 'pro'        => [
					// 	'slug'      => 'beaf-before-and-after-gallery-pro',
					// 	'file_name' => 'before-and-after-gallery-pro',
					// 	'url'       => 'https://themefic.com/plugins/beaf/pro/',
					// ],
				],
				[
					'name'       => 'Tourfic',
					'slug'       => 'tourfic',
					'file_name'  => 'tourfic',
					'subtitle'   => 'Travel, Hotel Booking & Car Rental WP Plugin',
					'image'      => INSTANTIO_ADMIN_URL . '/tf-options/img/instanio-logo.png',
					// 'pro'        => [
					// 	'slug'      => 'tourfic-pro',
					// 	'file_name' => 'tourfic-pro',
					// 	'url'       => 'https://themefic.com/tourfic/',
					// ],
				],
				// [
				// 	'name'       => 'Instantio',
				// 	'slug'       => 'instantio',
				// 	'file_name'  => 'instantio',
				// 	'subtitle'   => 'WooCommerce Quick & Direct Checkout',
				// 	// 'pro'        => [
				// 	// 	'slug'      => 'wooinstant',
				// 	// 	'file_name' => 'wooinstant',
				// 	// 	'url'       => 'https://themefic.com/instantio/',
				// 	// ],
				// ],
				// [
				// 	'name'       => 'Before After Slider for WooCommerce – eBEAF',
				// 	'slug'       => 'before-after-for-woocommerce',
				// 	'file_name'  => 'before-after-for-woocommerce',
				// 	'pro_url'    => '',
				// 	'pro'        => [
				// 		'slug'      => 'before-after-for-woocommerce-pro',
				// 		'file_name' => 'before-after-for-woocommerce-pro',
				// 		'url'       => 'https://themefic.com/plugins/ebeaf/pro/',
				// 	],
				// ],
			];

			?>

			<ul>
				<?php foreach ($plugins as $plugin): 
					$plugin_path = $plugin['slug'] . '/' . $plugin['file_name'] . '.php';
					$installed = file_exists(WP_PLUGIN_DIR . '/' . $plugin_path);
					$activated = $installed && is_plugin_active($plugin_path);

					$pro_installed = false;
					$pro_activated = false;
					
					if (!empty($plugin['pro'])) {
						$pro_path = $plugin['pro']['slug'] . '/' . $plugin['pro']['file_name'] . '.php';
						$pro_installed = file_exists(WP_PLUGIN_DIR . '/' . $pro_path);
						$pro_activated = $pro_installed && is_plugin_active($pro_path);
					}

					?>

					<li class="plugin-item <?php echo esc_attr($plugin['slug'] == 'hydra-booking' ? 'featured' : ''); ?>" data-plugin-slug="<?php echo esc_attr($plugin['slug']); ?>">
						<div class="plugin-info-wrapper">
							<div class="plugin-info">
								<img src="<?php echo esc_url($plugin['image']); ?>" alt="<?php echo esc_attr($plugin['name']); ?>" class="<?php echo esc_attr($plugin['name'] == 'BEAF' ? 'beaf-logo' : ''); ?>" width="40" height="40">
								<div class="plugin-btn">
									<span class="badge free">Free</span>
									<?php if (!$installed): ?>
										<button class="plugin-button install" data-action="install" data-plugin="<?php echo esc_attr($plugin['slug']); ?>" data-plugin_filename="<?php echo esc_attr($plugin['file_name']); ?>">
											Install
										</button>
									<?php elseif (!$activated): ?>
										<button class="plugin-button activate" data-action="activate" data-plugin="<?php echo esc_attr($plugin['slug']); ?>" data-plugin_filename="<?php echo esc_attr($plugin['file_name']); ?>" >
											Activate
										</button>
									<?php else: ?>
										<span class="plugin-button plugin-status active">Activated</span>
									<?php endif; ?>

									<?php if (!empty($plugin['pro'])): ?>
										<?php if (!$pro_installed): ?>
											<a href="<?php echo esc_url($plugin['pro']['url']); ?>" class="plugin-button pro" target="_blank">Get Pro</a>
										<?php elseif (!$pro_activated): ?>
											<button class="plugin-button activate-pro" data-action="activate" data-plugin="<?php echo esc_attr($plugin['pro']['slug']); ?>" data-plugin_filename="<?php echo esc_attr($plugin['pro']['file_name']); ?>">
												Activate Pro <span class="loader"></span>
											</button>
										<?php else: ?>
											<span class="plugin-button plugin-status active-pro">Pro Activated</span>
										<?php endif; ?>
									<?php endif; ?>
								</div>
							</div>
							<div class="instantio-plugin-content">
								<h4><?php echo esc_html($plugin['name']); ?></h4>
								<p><?php echo esc_html($plugin['subtitle']); ?></p>
								<strong></strong>
							</div>
						</div>
					</li>

				<?php endforeach; ?>

			</ul>

			<?php
		}

		public function instantio_themefic_manage_plugin() {
			check_ajax_referer('themefic_plugin_nonce', 'security');

			if (!current_user_can('install_plugins')) {
				wp_send_json_error('You do not have permission to perform this action.');
			}

				$plugin_slug = isset($_POST['plugin_slug']) ? sanitize_key( wp_unslash( $_POST['plugin_slug'] ) ) : '';
				$plugin_filename = isset($_POST['plugin_filename']) ? sanitize_text_field( wp_unslash( $_POST['plugin_filename'] ) ) : '';
				$plugin_action = isset($_POST['plugin_action']) ? sanitize_key( wp_unslash( $_POST['plugin_action'] ) ) : '';

			if (!$plugin_slug || !$plugin_action) {
				wp_send_json_error('Invalid request.');
			}

			include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			include_once ABSPATH . 'wp-admin/includes/plugin.php';

			if ($plugin_action === 'install') {
				$api = plugins_api('plugin_information', ['slug' => $plugin_slug]);

				if (is_wp_error($api)) {
					wp_send_json_error($api->get_error_message());
				}

				$upgrader = new Plugin_Upgrader(new WP_Ajax_Upgrader_Skin());
				$install_result = $upgrader->install($api->download_link);

				if (is_wp_error($install_result)) {
					wp_send_json_error($install_result->get_error_message());
				}

				wp_send_json_success(['message' => 'Installed successfully.']);
			}

			if ($plugin_action === 'activate') {
				$plugin_path = WP_PLUGIN_DIR . '/' . $plugin_slug . '/' . $plugin_filename . '.php';

				if (!file_exists($plugin_path)) {
					wp_send_json_error('Plugin file not found.');
				}

				$activate_result = activate_plugin($plugin_path);

				if (is_wp_error($activate_result)) {
					wp_send_json_error($activate_result->get_error_message());
				}

				wp_send_json_success(['message' => 'Activated successfully.']);
			}

			wp_send_json_error('Invalid action.');
		}


		/**
		 * Determine whether an array uses sequential numeric keys.
		 *
		 * Kept compatible with the plugin's PHP 7.4 minimum, where array_is_list()
		 * is not available.
		 *
		 * @param array $value Array to inspect.
		 * @return bool
		 */
		private function ins_is_list_array( $value ) {
			if ( array() === $value ) {
				return true;
			}

			return array_keys( $value ) === range( 0, count( $value ) - 1 );
		}

		/**
		 * Merge submitted settings without deleting settings owned by an add-on.
		 *
		 * Associative groups are merged recursively so an Instantio Free save keeps
		 * unsubmitted Instantio Pro keys. Numeric lists (such as repeater rows) are
		 * replaced as a unit so intentionally deleted rows do not return.
		 *
		 * @param array $stored    Previously stored settings.
		 * @param array $submitted Sanitized settings submitted by the active schema.
		 * @return array
		 */
		private function ins_merge_option_values( $stored, $submitted ) {
			foreach ( $submitted as $key => $value ) {
				if (
					isset( $stored[ $key ] ) &&
					is_array( $stored[ $key ] ) &&
					is_array( $value ) &&
					! $this->ins_is_list_array( $stored[ $key ] ) &&
					! $this->ins_is_list_array( $value )
				) {
					$stored[ $key ] = $this->ins_merge_option_values( $stored[ $key ], $value );
				} else {
					$stored[ $key ] = $value;
				}
			}

			return $stored;
		}

		/**
		 * Merge submitted repeater rows with stored add-on metadata by stable origin.
		 *
		 * Only submitted rows are returned, so deleting a row remains intentional.
		 * Matching stored rows contribute keys that are not rendered by the active
		 * Free or Pro schema.
		 *
		 * @param mixed  $stored_value    Previously stored serialized rows.
		 * @param mixed  $submitted_value Submitted serialized rows.
		 * @param string $origin_key      Stable origin key in each row.
		 * @return string
		 */
		private function ins_merge_repeater_rows( $stored_value, $submitted_value, $origin_key ) {
			$stored_rows    = maybe_unserialize( $stored_value );
			$submitted_rows = maybe_unserialize( $submitted_value );
			$checkbox_keys  = 'checkout_form_field_origin' === $origin_key
				? array( 'checkout_form_field_status', 'required' )
				: array( 'checkout_shipping_form_field_status', 'required_shipping' );

			if ( ! is_array( $submitted_rows ) ) {
				return $submitted_value;
			}

			$stored_by_origin = array();
			if ( is_array( $stored_rows ) ) {
				foreach ( $stored_rows as $stored_row ) {
					if ( is_array( $stored_row ) && isset( $stored_row[ $origin_key ] ) ) {
						$stored_by_origin[ (string) $stored_row[ $origin_key ] ] = $stored_row;
					}
				}
			}

			$merged_rows = array();
			foreach ( $submitted_rows as $submitted_row ) {
				if ( ! is_array( $submitted_row ) ) {
					continue;
				}

				$origin = isset( $submitted_row[ $origin_key ] ) ? (string) $submitted_row[ $origin_key ] : '';
				if ( '' !== $origin && isset( $stored_by_origin[ $origin ] ) ) {
					$submitted_keys = $submitted_row;
					$submitted_row  = $this->ins_merge_option_values( $stored_by_origin[ $origin ], $submitted_row );

					foreach ( $checkbox_keys as $checkbox_key ) {
						if ( ! array_key_exists( $checkbox_key, $submitted_keys ) ) {
							$submitted_row[ $checkbox_key ] = '';
						}
					}
				}

				$merged_rows[] = $submitted_row;
			}

			return serialize( $merged_rows );
		}

		/**
		 * Return the field classes that settings submissions may instantiate.
		 *
		 * Keeping this list explicit prevents a submitted or filtered field type from
		 * being turned into an arbitrary class name during a settings save.
		 *
		 * @return array<string, string>
		 */
		private function ins_get_allowed_field_classes() {
			return array(
				'callback'    => 'Instantio_Field_Callback',
				'checkbox'    => 'Instantio_Field_Checkbox',
				'codeeditor'  => 'Instantio_Field_Codeeditor',
				'color'       => 'Instantio_Field_Color',
				'date'        => 'Instantio_Field_Date',
				'editor'      => 'Instantio_Field_Editor',
				'fieldset'    => 'Instantio_Field_Fieldset',
				'gallery'     => 'Instantio_Field_Gallery',
				'heading'     => 'Instantio_Field_Heading',
				'icon'        => 'Instantio_Field_Icon',
				'image'       => 'Instantio_Field_Image',
				'imageselect' => 'Instantio_Field_Imageselect',
				'notice'      => 'Instantio_Field_Notice',
				'number'      => 'Instantio_Field_Number',
				'radio'       => 'Instantio_Field_Radio',
				'repeater'    => 'Instantio_Field_Repeater',
				'select'      => 'Instantio_Field_Select',
				'select2'     => 'Instantio_Field_Select2',
				'switch'      => 'Instantio_Field_Switch',
				'tab'         => 'Instantio_Field_Tab',
				'text'        => 'Instantio_Field_Text',
				'textarea'    => 'Instantio_Field_Textarea',
				'time'        => 'Instantio_Field_Time',
			);
		}

		/**
		 * Sanitize a submitted value according to its registered field schema.
		 *
		 * Compound controls are rebuilt from their declared child fields, so unknown
		 * request keys are discarded before data reaches a field class.
		 *
		 * @param array $field Field definition.
		 * @param mixed $value Unslashed submitted value.
		 * @return mixed
		 */
		private function ins_sanitize_submitted_field( $field, $value ) {
			$type = isset( $field['type'] ) ? sanitize_key( $field['type'] ) : '';

			if ( 'repeater' === $type ) {
				if ( ! is_array( $value ) || empty( $field['fields'] ) || ! is_array( $field['fields'] ) ) {
					return array();
				}

				$rows = array();
				foreach ( $value as $row ) {
					if ( ! is_array( $row ) ) {
						continue;
					}

					$sanitized_row = $this->ins_sanitize_submitted_children( $field['fields'], $row );
					if ( ! empty( $sanitized_row ) ) {
						$rows[] = $sanitized_row;
					}
				}

				return $rows;
			}

			if ( 'fieldset' === $type ) {
				return is_array( $value ) && ! empty( $field['fields'] ) && is_array( $field['fields'] )
					? $this->ins_sanitize_submitted_children( $field['fields'], $value )
					: array();
			}

			if ( 'tab' === $type ) {
				if ( ! is_array( $value ) || empty( $field['tabs'] ) || ! is_array( $field['tabs'] ) ) {
					return array();
				}

				$tab_fields = array();
				foreach ( $field['tabs'] as $tab ) {
					if ( ! empty( $tab['fields'] ) && is_array( $tab['fields'] ) ) {
						$tab_fields = array_merge( $tab_fields, $tab['fields'] );
					}
				}

				return $this->ins_sanitize_submitted_children( $tab_fields, $value );
			}

			if ( 'color' === $type && ! empty( $field['multiple'] ) ) {
				$colors = array();
				if ( is_array( $value ) && ! empty( $field['colors'] ) && is_array( $field['colors'] ) ) {
					foreach ( array_keys( $field['colors'] ) as $color_key ) {
						if ( isset( $value[ $color_key ] ) && is_scalar( $value[ $color_key ] ) ) {
							$color = $this->ins_sanitize_color( $value[ $color_key ] );
							if ( '' !== $color ) {
								$colors[ $color_key ] = $color;
							}
						}
					}
				}

				return $colors;
			}

			if ( 'checkbox' === $type && is_array( $value ) ) {
				return $this->ins_validate_field_choices( $field, $value );
			}

			if ( 'select2' === $type && ! empty( $field['multiple'] ) ) {
				return is_array( $value ) ? $this->ins_validate_field_choices( $field, $value ) : array();
			}

			if ( 'gallery' === $type ) {
				if ( ! is_scalar( $value ) ) {
					return '';
				}

				$attachment_ids = array_filter( array_map( 'absint', explode( ',', (string) $value ) ) );
				return implode( ',', $attachment_ids );
			}

			if ( is_array( $value ) || is_object( $value ) ) {
				return '';
			}

			switch ( $type ) {
				case 'checkbox':
				case 'switch':
					$value = sanitize_text_field( $value );
					return in_array( $value, array( '', '0', '1' ), true ) ? $value : '';
				case 'imageselect':
				case 'radio':
				case 'select':
				case 'select2':
					$choices = $this->ins_validate_field_choices( $field, array( $value ) );
					return isset( $choices[0] ) ? $choices[0] : '';
				case 'color':
					return $this->ins_sanitize_color( $value );
				case 'image':
					return esc_url_raw( $value );
				case 'editor':
					return wp_kses_post( $value );
				case 'codeeditor':
				case 'textarea':
					return sanitize_textarea_field( $value );
				case 'number':
					$value = sanitize_text_field( $value );
					return '' === $value || is_numeric( $value ) ? $value : '';
				default:
					return sanitize_text_field( $value );
			}
		}

		/**
		 * Validate submitted values against a field's declared choices.
		 *
		 * @param array $field Field definition.
		 * @param array $values Submitted values.
		 * @return array
		 */
		private function ins_validate_field_choices( $field, $values ) {
			$options = isset( $field['options'] ) ? $field['options'] : array();

			if ( isset( $field['options_callback'] ) && is_callable( $field['options_callback'] ) ) {
				$options = call_user_func( $field['options_callback'] );
			}

			if ( ! empty( $field['query_args'] ) && 'posts' === $options ) {
				$options = array();
				foreach ( get_posts( $field['query_args'] ) as $post ) {
					$options[ $post->ID ] = $post->post_title;
				}
			} elseif ( ! empty( $field['query_args'] ) && 'terms' === $options ) {
				$options = array();
				$terms   = get_terms( $field['query_args'] );
				if ( ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						$options[ $term->term_id ] = $term->name;
					}
				}
			}

			$values = array_filter( $values, 'is_scalar' );
			$values = array_map( 'sanitize_text_field', $values );
			if ( ! is_array( $options ) ) {
				return array();
			}

			$allowed = array_map( 'strval', array_keys( $options ) );
			return array_values(
				array_filter(
					$values,
					static function ( $value ) use ( $allowed ) {
						return in_array( (string) $value, $allowed, true );
					}
				)
			);
		}

		/**
		 * Validate a color-picker value while retaining supported alpha colors.
		 *
		 * @param mixed $value Submitted color.
		 * @return string
		 */
		private function ins_sanitize_color( $value ) {
			$value = is_scalar( $value ) ? sanitize_text_field( (string) $value ) : '';
			if ( '' === $value || 'transparent' === strtolower( $value ) ) {
				return $value;
			}

			if ( preg_match( '/^#(?:[0-9a-f]{3,4}|[0-9a-f]{6}|[0-9a-f]{8})$/i', $value ) ) {
				return $value;
			}

			if ( preg_match( '/^rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})(?:\s*,\s*(0(?:\.\d+)?|1(?:\.0+)?))?\s*\)$/i', $value, $matches ) ) {
				if ( (int) $matches[1] <= 255 && (int) $matches[2] <= 255 && (int) $matches[3] <= 255 ) {
					return $value;
				}
			}

			return '';
		}

		/**
		 * Sanitize the declared children of a compound field.
		 *
		 * @param array $child_fields Child field definitions.
		 * @param array $submitted Submitted compound value.
		 * @return array
		 */
		private function ins_sanitize_submitted_children( $child_fields, $submitted ) {
			$sanitized = array();

			foreach ( $child_fields as $child_field ) {
				if ( empty( $child_field['id'] ) || ! array_key_exists( $child_field['id'], $submitted ) ) {
					continue;
				}

				$sanitized[ $child_field['id'] ] = $this->ins_sanitize_submitted_field(
					$child_field,
					$submitted[ $child_field['id'] ]
				);
			}

			return $sanitized;
		}

		/**
		 * Save Options
		 * @author M Hemel Hasan
		 */
		public function save_options() {

			// Check if a nonce is valid.
			if ( ! isset( $_POST['instantio_option_nonce'] ) || ! isset( $_POST[ $this->option_id ] ) ) {
				return;
			}

			if ( ! current_user_can( 'manage_options' ) ) {
					wp_die( esc_html__( 'You are not allowed to perform this action.', 'instantio' ) );
			}

			// Check nonce
				if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['instantio_option_nonce'] ) ), 'instantio_option_nonce_action' ) ) {
				return;
			}


			$tf_option_value = array();
				// Values are sanitized against the registered field schema before a field class is created.
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized per field by ins_sanitize_submitted_field().
				$option_request       = ! empty( $_POST[ $this->option_id ] ) && is_array( $_POST[ $this->option_id ] ) ? wp_unslash( $_POST[ $this->option_id ] ) : array();
				$allowed_field_classes = $this->ins_get_allowed_field_classes();
			if ( ! empty( $option_request ) && ! empty( $this->option_sections ) ) {
				foreach ( $this->option_sections as $section ) {
					if ( ! empty( $section['fields'] ) ) {

						foreach ( $section['fields'] as $field ) {

							if ( ! empty( $field['id'] ) ) {
								$field_type = isset( $field['type'] ) ? sanitize_key( $field['type'] ) : '';
								if ( ! isset( $allowed_field_classes[ $field_type ] ) ) {
									continue;
								}

								$data       = isset( $option_request[ $field['id'] ] ) ? $option_request[ $field['id'] ] : '';
								$data       = $this->ins_sanitize_submitted_field( $field, $data );
								$data = 'repeater' === $field_type ? serialize( $data ) : $data;

								$tf_option_value[ $field['id'] ] = $data;

							}
						}
					}
				}
			}

				if ( ! empty( $tf_option_value ) ) {
					$stored_option_value = get_option( $this->option_id, array() );
					$stored_option_value = is_array( $stored_option_value ) ? $stored_option_value : array();
					$shared_repeaters = array(
						'checkout_editors_fields'         => 'checkout_form_field_origin',
						'checkout_shiping_editors_fields' => 'checkout_shipping_form_field_origin',
					);

					foreach ( $shared_repeaters as $repeater_key => $origin_key ) {
						if ( isset( $tf_option_value[ $repeater_key ] ) ) {
							$stored_repeater_value = isset( $stored_option_value[ $repeater_key ] ) ? $stored_option_value[ $repeater_key ] : '';
							$tf_option_value[ $repeater_key ] = $this->ins_merge_repeater_rows( $stored_repeater_value, $tf_option_value[ $repeater_key ], $origin_key );
						}
					}

					$merged_option_value = $this->ins_merge_option_values( $stored_option_value, $tf_option_value );

				update_option( $this->option_id, $merged_option_value );
			}
		}


		/*
		 * Ajax Save Options
		 * @author Foysal
		 */
		public function tf_ajax_save_options() {
			$response = [ 
				'status' => 'error',
				'message' => __( 'Something went wrong!', 'instantio' ),
			];

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error(
					array( 'message' => __( 'You are not allowed to change these settings.', 'instantio' ) ),
					403
				);
			}

			if ( ! check_ajax_referer( 'instantio_option_nonce_action', 'instantio_option_nonce', false ) ) {
				wp_send_json_error(
					array( 'message' => __( 'The security check failed. Refresh the page and try again.', 'instantio' ) ),
					403
				);
			}

			if ( ! empty( $_POST['instantio_option_nonce'] ) ) {
				$this->save_options();
				$response = [ 
					'status' => 'success',
					'message' => __( 'Options saved successfully!', 'instantio' ),
				];
			}

			wp_send_json( $response );
		}

		/*
		 * Get current page url
		 * @return string
		 * @author Foysal
		 */
		public function get_current_page_url() {
				$scheme      = isset( $_SERVER['HTTPS'] ) && 'on' === sanitize_text_field( wp_unslash( $_SERVER['HTTPS'] ) ) ? 'https' : 'http';
				$host        = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
				$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
				$page_url    = esc_url_raw( $scheme . '://' . $host . $request_uri );

			return $page_url;
		}

		/*
		 * Get query string from url
		 * @return array
		 * @author Foysal
		 */
		public function get_query_string( $url ) {
			$url_parts    = wp_parse_url( $url );
			$query_string = array();
			if ( is_array( $url_parts ) && ! empty( $url_parts['query'] ) ) {
				parse_str( $url_parts['query'], $query_string );
			}

			return $query_string;
		}
	}
}
