<?php
// INS is the established Instantio namespace and its hooks are public compatibility APIs.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals
namespace INS\Controller;

class Admin {

	public function __construct() {

		add_action( 'init', array( $this, 'ins_update_option' ) );

		$ins_review_notice_status = get_option( 'ins_review_notice_status' );
		$ins_installation_date = get_option( 'ins_installation_date' );
		if ( isset( $ins_review_notice_status ) && $ins_review_notice_status <= 0 && $ins_installation_date == 1 && ! isset( $_COOKIE['ins_review_notice_status'] ) && ! isset( $_COOKIE['ins_installation_date'] ) ) {
			// add_action( 'admin_notices', array($this, 'ins_review_notice') );  
		}

		// add_action( 'admin_notices', array($this, 'ins_review_notice') ); 

		// add_action('admin_init', array($this, 'ins_review_activation_status')); 

		/**
		 * Check if WooCommerce is active, and if it isn't, disable the plugin.
		 *
		 * @since 1.0 
		 */
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			add_action( 'admin_notices', array( $this, 'ins_is_woo' ) );
			// deactivate_plugins('instantio/instantio.php');
		}

		/**
		 * Check if Instantio Pro version is 3.0.0 or above, if it isn't, disable the plugin.
		 *
		 * @since 3.0
		 */
		if ( class_exists( 'WOOINS' ) ) {
			$minimum_pro_version = '3.0.0';

			if ( ! defined( 'INSTANTIO_PRO_VERSION' ) || version_compare( INSTANTIO_PRO_VERSION, $minimum_pro_version, '<' ) ) {
				deactivate_plugins( 'wooinstant/wooinstant.php' );
				add_action( 'admin_notices', array( $this, 'version_warning' ) );
				add_action( 'admin_notices', array( $this, 'ins_wooinstantio_updated' ) );
			}
		}

		// Define Plugin Action Links.
		add_filter( 'plugin_action_links_' . INS_BASE_LOCATION, array( $this, 'instantio_plugin_action_links' ) );

		// Activation & Dactivation Hook
		register_activation_hook( INS_PATH . 'instantio.php', array( $this, 'ins_activate' ) );
		register_deactivation_hook( INS_PATH . 'instantio.php', array( $this, 'ins_deactivate' ) );


	}


	/**
	 * Update Instantio Notice
	 */

	public function ins_update_option() {
		$update_option = get_option( 'wiopt_update_option' );
		if ( '3.0.0' == INSTANTIO_VERSION && $update_option < 1 ) {
			update_option( 'wiopt', '' );
			update_option( 'wiopt_update_option', '1' );
		}
	}

	// Themefic Plugin Review Admin Notice
	// public function ins_review_activation_status(){ 
	//     $ins_installation_date = get_option('ins_installation_date'); 
	//     if( !isset($_COOKIE['ins_installation_date']) && empty($ins_installation_date) && $ins_installation_date == 0){
	//         setcookie('ins_installation_date', 1, time() + (86400 * 7), "/"); 
	//     }else{
	//         update_option( 'ins_installation_date', '1' );
	//     }
	// }

	// Themefic Plugin Review Admin Notice
	public function ins_review_notice() {
		$get_current_screen = get_current_screen();
		if ( $get_current_screen->base == 'dashboard' ) {
			$current_user = wp_get_current_user();
			?>
			<div class="notice notice-info themefic_review_notice">

					<?php
						printf(
							/* translators: 1: current user login, 2: plugin name. */
							esc_html__( ' Hey %1$s 👋, You have been using %2$s for quite a while. If you feel %2$s is helping your business to grow in any way, would you please help %2$s to grow by simply leaving a 5* review on the WordPress Forum?', 'instantio' ),
							esc_html( $current_user->user_login ),
							esc_html( 'Instantio' )
					);
					?>

				<ul>
					<li><a target="_blank"
							href="<?php echo esc_url( 'https://wordpress.org/support/plugin/instantio/reviews/#new-post' ) ?>"
							class=""><span class="dashicons dashicons-external"></span>
								<?php esc_html_e( ' Ok, you deserve it!', 'instantio' ) ?>
						</a></li>
					<li><a href="#" class="already_done" data-status="already"><span class="dashicons dashicons-smiley"></span>
								<?php esc_html_e( 'I already did', 'instantio' ) ?>
						</a></li>
					<li><a href="#" class="later" data-status="later"><span class="dashicons dashicons-calendar-alt"></span>
								<?php esc_html_e( 'Maybe Later', 'instantio' ) ?>
						</a></li>
					<li><a target="_blank" href="<?php echo esc_url( 'https://themefic.com/docs/instantio/' ) ?>" class=""><span
								class="dashicons dashicons-sos"></span>
								<?php esc_html_e( 'I need help', 'instantio' ) ?>
						</a></li>
					<li><a href="#" class="never" data-status="never"><span class="dashicons dashicons-dismiss"></span>
								<?php esc_html_e( 'Never show again', 'instantio' ) ?>
						</a></li>
				</ul>
			</div>

			<!--   Themefic Plugin Review Admin Notice Script -->
			<script>
				jQuery(document).ready(function ($) {
					$(document).on('click', '.already_done, .later, .never', function (event) {
						event.preventDefault();
						var $this = jQuery(this);
						var status = $this.attr('data-status');
						$this.closest('.themefic_review_notice').css('display', 'none')
						data = {
							action: 'ins_review_notice_callback',
						};

							$.ajax({
								url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
							type: 'POST',
							data: data,
							success: function (res) {
							},
							error: function (err) {
							}
						});
					});
				});
			</script>
		<?php
		}
	}

	// Version Warning Admin Notice
	public function version_warning() { ?>
			<div class="notice notice-error">
				<p><?php esc_html_e( 'We have noticed a discrepancy between the version of your Pro plugin and the free plugin. Please update your Pro plugin to ensure compatibility and optimal performance.', 'instantio' ); ?></p>
		</div>
	<?php }

	// Themefic Plugin Review Admin Notice
	// public function ins_review_notice_callback() {
	//     // Check if this is a valid AJAX request
	//     check_ajax_referer('ins_review_nonce', 'security');

	//     $status = sanitize_text_field($_POST['status']); // Sanitize user input

	//     if ($status === 'already') {
	//         update_option('ins_review_notice_status', '1');
	//     } elseif ($status === 'never') {
	//         update_option('ins_review_notice_status', '2');
	//     } elseif ($status === 'later') {
	//         $cookie_name = 'ins_review_notice_status';
	//         $cookie_value = '1';
	//         // Set a cookie with a 7-day expiration
	//         setcookie($cookie_name, $cookie_value, time() + (86400 * 7), '/');
	//         update_option('ins_review_notice_status', '0');
	//     }

	//     wp_die();
	// } 


	/**
	 * Called when WooCommerce is inactive to display an inactive notice.
	 *
	 * @since 1.0
	 */
	public function ins_is_woo() {
		if ( current_user_can( 'activate_plugins' ) ) {
			if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) && ! file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) ) {
				?>

				<div id="message" class="error">
					<p>
							<?php
							/* translators: 1: opening link markup, 2: closing link markup. */
								echo wp_kses_post( sprintf( __( 'Instantio requires %1$s WooCommerce %2$s to be activated.', 'instantio' ), '<strong><a href="https://wordpress.org/plugins/woocommerce/" target="_blank">', '</a></strong>' ) );
							?>
					</p>
					<p>
						<a href="plugin-install.php?s=woocommerce%2520%2520&tab=search&type=term" class="install-now button ins_wooinstall">
							<?php esc_attr_e( 'Install Now', 'instantio' ); ?>
						</a>
					</p>
				</div>
			<?php
			} elseif ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) && file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) ) {
				?>

				<div id="message" class="error">
					<p>
							<?php
							/* translators: 1: opening link markup, 2: closing link markup. */
								echo wp_kses_post( sprintf( __( 'Instantio requires %1$s WooCommerce %2$s to be activated.', 'instantio' ), '<strong><a href="https://wordpress.org/plugins/woocommerce/" target="_blank">', '</a></strong>' ) );
							?>
					</p>
					<p>
						<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'plugins.php?action=activate&plugin=woocommerce/woocommerce.php' ), 'activate-plugin_woocommerce/woocommerce.php' ) ); ?>" class="button activate-now button-primary">
							<?php esc_attr_e( 'Activate', 'instantio' ); ?>
						</a>
					</p>
				</div>

			<?php
			} elseif ( version_compare( get_option( 'woocommerce_db_version' ), '7.0', '<' ) ) {
				?>

				<div id="message" class="error">
					<p>
							<?php
							/* translators: 1: opening strong tag, 2: closing strong tag, 3: opening update link, 4: closing update link. */
								echo wp_kses_post( sprintf( __( '%1$sInstantio is inactive.%2$s This plugin requires WooCommerce 7.0 or newer. Please %3$supdate WooCommerce to version 7.0 or newer%4$s', 'instantio' ), '<strong>', '</strong>', '<a href="' . esc_url( admin_url( 'plugins.php' ) ) . '">', '&nbsp;&raquo;</a>' ) );
							?>
					</p>
				</div>

			<?php
			}
		}
	}

	/**
	 * Called when Instantio Pro is inactive to display an inactive notice.
	 *
	 * @since 3.0
	 */
	public function ins_wooinstantio_updated() {
		if ( current_user_can( 'activate_plugins' ) ) {
			?>
			<div id="message" class="error">
				<p>
					<?php esc_html_e( 'To activate Instantio, it is necessary to have Instantio Pro version 3 installed.', 'instantio' ); ?>
				</p>

				<p>
					<span class="ins-notices" data-plugin-slug="instantiopro">
						<?php esc_attr_e( 'Please Updated Now', 'instantio' ); ?>
					</span>

					<!-- <a  href="<?php //echo get_admin_url();  ?>update.php?action=upgrade-plugin&amp;plugin=wooinstant%2Fwooinstant.php&_wpnonce=<?php // echo wp_create_nonce('updates')  ?>" href="//echo get_admin_url();wp-admin/update.php?action=upgrade-plugin&amp;plugin=wooinstant%2Fwooinstant.php&amp;_wpnonce=442cf75765" class="update-link" aria-label="Update Instantio Pro now">update now</a> -->
					<!-- //echo get_admin_url();wp-admin/update.php?action=upgrade-plugin&plugin=wooinstant%2Fwooinstant.php&_wpnonce=00962cb15e -->

				</p>
			</div>

		<?php
		}
	}


	/**
	 * Add plugin action links.
	 *
	 */
	public function instantio_plugin_action_links( $links ) {

		$settings_link = array(
			'<a href="admin.php?page=wiopt#tab=layout_option">' . esc_html__( 'Settings', 'instantio' ) . '</a>',
		);

		if ( ! is_plugin_active( 'wooinstant/wooinstant.php' ) ) {
			$gopro_link = array(
				'<a href="https://themefic.com/instantio/go/upgrade" target="_blank" style="color:#cc0000;font-weight: bold;text-shadow: 0px 1px 1px hsl(0deg 0% 0% / 28%);">' . esc_html__( 'GO PRO', 'instantio' ) . '</a>',
			);
		} else {
			$gopro_link = array( '' );
		}
		return array_merge( $settings_link, $links, $gopro_link );
	}

	// Activation  Hooks
	public function ins_activate() {
		$installed = get_option( 'instantio_active_time' );

		if ( ! $installed ) {
			update_option( 'instantio_active_time', time() );
		}

	}

	// Deactivation  Hooks
	public function ins_deactivate() {
		$installed = get_option( 'instantio_active_time' );
		if ( $installed ) {
			delete_option( 'instantio_active_time' );
		}
	}
}
