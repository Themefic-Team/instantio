<?php
/**
 * Plugin Name: Instantio — Side Cart & One-Page Checkout for WooCommerce
 * Plugin URI: https://themefic.com/instantio/
 * Description: WooCommerce direct checkout plugin with Side Cart, Popup Cart, Floating Cart & Popup Checkout function (+ 4 more WooCommerce Quick Checkout systems).
 * Author: Themefic
 * Text Domain: instantio
 * Domain Path: /lang/
 * Author URI: https://themefic.com
 * Tags: woocommerce cart, woocommerce checkout, woocommerce direct checkout, multistep checkout, woocommerce side cart
 * Version: 3.3.37
 * Requires PHP: 7.4
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html 
**/

// don't load directly
defined( 'ABSPATH' ) || exit;

class INSTANTIO {

	/**
	 * Legacy Pro version prevented from booting during this request.
	 *
	 * @var string
	 */
	private $instantio_incompatible_pro_version = '';

	public function __construct() {
		$this->define_constants();
		$this->init_hooks();
		$this->includes();
		$this->ins_public_hooks();

		//enqueue scripts
		add_action( 'admin_enqueue_scripts', [ $this, 'Ins_tourfic_admin_denqueue_script' ], 20 );
	}

	/**
	 * Define constants
	 */
	private function define_constants() {
		if ( ! defined( 'INSTANTIO_VERSION' ) ) {
			define( 'INSTANTIO_VERSION', '3.3.37' );
		}
		define( 'INSTANTIO_MIN_PREFIXED_PRO_VERSION', '3.2.12' );
		define( 'INSTANTIO_URL', plugin_dir_url( __FILE__ ) );
		define( 'INSTANTIO_INC_URL', INSTANTIO_URL . 'includes' );
		define( 'INSTANTIO_LAYOUTS_URL', INSTANTIO_URL . 'includes/layouts' );
		define( 'INSTANTIO_ASSETS_URL', INSTANTIO_URL . 'assets' );
		define( 'INSTANTIO_ADMIN_URL', INSTANTIO_URL . 'admin' );

		define( 'INSTANTIO_PATH', plugin_dir_path( __FILE__ ) );
		define( 'INSTANTIO_INC_PATH', INSTANTIO_PATH . 'includes' );
		define( 'INSTANTIO_ADMIN_PATH', INSTANTIO_PATH . 'admin' );
		define( 'INSTANTIO_CONTROLLER_PATH', INSTANTIO_INC_PATH . '/controller' );
		define( 'INSTANTIO_BASE_LOCATION', plugin_basename( __FILE__ ) );
		define( 'INSTANTIO_TEMPLATES_PATH', INSTANTIO_INC_PATH . '/templates' );

		/**
		 * Ajax install & activate WooCommerce
		 *
		 * @since 3.0
		 * @link https://developer.wordpress.org/reference/functions/wp_ajax_install_plugin/
		 */
		add_action( "wp_ajax_instantio_ajax_install_woocommerce", "wp_ajax_install_plugin" );

	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes() {
		require_once __DIR__ . '/vendor/autoload.php';
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		require_once( 'functions.php' );

		// Ins Quick Setup wizard & Ins_checkout_Editor
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			require_once INSTANTIO_INC_PATH . '/controller/class-setup-wizard.php';

			// Ins_checkout_Editor
			require_once INSTANTIO_INC_PATH . '/controller/checkout_editor.php';
		}

		// ins Promo Banner
		if ( defined('INSTANTIO_INC_PATH') && !empty(INSTANTIO_INC_PATH) ) {
			require_once INSTANTIO_INC_PATH . '/controller/class-dashboard-widget.php';
		}

	}

	/**
	 * Init Instantio when WordPress Initialises.
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'init' ), 0 );
	}

	/**
	 *  init Instantio when WordPress Initialises.
	 *
	 * @since 1.0
	 */
	public function init() {
		$this->instantio_guard_legacy_pro();

		add_action( 'init', array( $this, 'ins_plugin_loaded_action' ) );

		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			new Themefic\Instantio\Controller\Assets();
		}

		if ( is_admin() && ! wp_doing_ajax() ) {
			new Themefic\Instantio\Controller\Admin();

		} else {
			new Themefic\Instantio\Controller\App();

			// ins Variation product Quick Views
			add_action( 'wp_ajax_instantio_variable_product_quick_view', array( $this, 'ins_ajax_quickview_variable_products' ) );
			add_action( 'wp_ajax_nopriv_instantio_variable_product_quick_view', array( $this, 'ins_ajax_quickview_variable_products' ) );
		}

	}

	/**
	 * Prevent legacy Pro releases from calling removed, noncompliant Free APIs.
	 *
	 * Instantio Pro versions before 3.2.12 call the former global insopt()
	 * helper. That short global name cannot remain in the WordPress.org package.
	 * Free initializes at priority 0, before Pro's priority-10 bootstrap, so the
	 * incompatible callback can be paused without deactivating the add-on.
	 */
	private function instantio_guard_legacy_pro() {
		if ( ! is_plugin_active( 'wooinstant/wooinstant.php' ) || ! class_exists( 'WOOINS', false ) ) {
			return;
		}

		$pro_version = defined( 'INSTANTIO_PRO_VERSION' ) ? INSTANTIO_PRO_VERSION : '0';
		if ( version_compare( $pro_version, INSTANTIO_MIN_PREFIXED_PRO_VERSION, '>=' ) ) {
			return;
		}

		global $wp_filter;

		if ( empty( $wp_filter['init'] ) || empty( $wp_filter['init']->callbacks ) ) {
			return;
		}

		foreach ( $wp_filter['init']->callbacks as $priority => $callbacks ) {
			foreach ( $callbacks as $callback ) {
				$function = isset( $callback['function'] ) ? $callback['function'] : null;

				if (
					is_array( $function )
					&& isset( $function[0], $function[1] )
					&& is_object( $function[0] )
					&& is_a( $function[0], 'WOOINS' )
					&& 'wooinstant' === $function[1]
				) {
					remove_action( 'init', $function, $priority );
					$this->instantio_incompatible_pro_version = $pro_version;
				}
			}
		}

		if ( $this->instantio_incompatible_pro_version && is_admin() ) {
			add_action( 'admin_notices', array( $this, 'instantio_incompatible_pro_notice' ) );
		}
	}

	/**
	 * Tell administrators why legacy Pro was safely paused.
	 */
	public function instantio_incompatible_pro_notice() {
		if ( ! current_user_can( 'update_plugins' ) ) {
			return;
		}
		?>
		<div class="notice notice-error">
			<p>
				<?php
				printf(
					/* translators: 1: installed Pro version, 2: required Pro version. */
					esc_html__( 'Instantio Pro %1$s was not loaded because it is incompatible with this Instantio Free release. Update Instantio Pro to version %2$s or newer to restore Pro features.', 'instantio' ),
					esc_html( $this->instantio_incompatible_pro_version ),
					esc_html( INSTANTIO_MIN_PREFIXED_PRO_VERSION )
				);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Plugins Loaded Actions
	 *
	 * Including Option Panel
	 *
	 * Including Options
	 */
	public function ins_plugin_loaded_action() {

		if ( defined('INSTANTIO_PATH') && !empty(INSTANTIO_PATH) ) {
			require_once INSTANTIO_PATH . 'admin/tf-options/Instantio_Options.php';
		}

	}

	/**
	 *	Ajax variable products quick view
	 */

	public function ins_ajax_quickview_variable_products() {
		global $post, $product, $woocommerce;

		// return 1;
		check_ajax_referer( 'instantio_ajax_nonce', 'security' );

		add_action( 'instantio_product_data', 'woocommerce_template_single_add_to_cart' );

		$product_id = isset( $_POST['product_id'] ) ? absint( wp_unslash( $_POST['product_id'] ) ) : 0;
		if ( ! $product_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid product.', 'instantio' ) ), 400 );
		}

		$wiqv_loop = new WP_Query(
			array(
				'post_type' => 'product',
				'p' => $product_id,
			)
		);
		if ( $wiqv_loop->have_posts() ) :
			while ( $wiqv_loop->have_posts() ) :
				$wiqv_loop->the_post(); ?>
				<?php wc_get_template( 'single-product/add-to-cart/variation.php' ); ?>
				<script>
					jQuery.getScript("<?php echo esc_url( $woocommerce->plugin_url() . '/assets/js/frontend/add-to-cart-variation.min.js' ); ?>");
				</script>
				<?php
				do_action( 'instantio_product_data' );
			endwhile;
		endif;
		wp_reset_postdata();

		wp_die();
	}

	private function ins_public_hooks() {
		add_action( 'after_setup_theme', [ $this, 'ins_check_editor' ] );
	}

	public function ins_check_editor() {
		$ins_billing_fields = apply_filters( 'instantio_billing_fields_priority', 1000 );
		$ins_shipping_fields = apply_filters( 'instantio_shipping_fields_priority', 1000 );

		add_filter( 'woocommerce_default_address_fields', 'instantio_override_checkout_billing_address', $ins_billing_fields, 2 );
		add_filter( 'woocommerce_checkout_fields', 'instantio_override_checkout_billing_fields', $ins_billing_fields, 2 );
		add_filter( 'woocommerce_checkout_fields', 'instantio_override_checkout_shipping_fields', $ins_shipping_fields, 2 );
		// add_filter('woocommerce_default_address_fields', 'instantio_override_checkout_shipping_address');
	}

	public function Ins_tourfic_admin_denqueue_script( $screen ) {
		$ins_options_screens = array(
			'toplevel_page_wiopt',
			'instantio_page_ins_dashboard',
			'instantio_page_tf_license_info',
			'instantio_page_ins_get_help',
			'instantio_page_ins_whats_new',
			'admin_page_ins-setup-wizard',
			'instantio_page_ins-license-activation'
		);

		//The tourfic admin js Listings Directory Compatibility
		if ( in_array( $screen, $ins_options_screens ) ) {
			wp_dequeue_style( 'tf-admin' );
			wp_deregister_style( 'tf-admin' );
			wp_dequeue_style( 'tf-pro' );
			wp_dequeue_script( 'tf-pro' );
			wp_deregister_script('tf-pro');
		}
	}

}


new INSTANTIO();

add_action( 'admin_enqueue_scripts', 'instantio_admin_enqueue_scripts' );
add_action( 'before_woocommerce_init', 'instantio_before_woocommerce_init' );

function instantio_before_woocommerce_init() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
}

function instantio_admin_enqueue_scripts($screen) {
	wp_enqueue_style( 'instantio-admin', INSTANTIO_ASSETS_URL . '/admin/css/instantio-admin-style.css', array(), INSTANTIO_VERSION );
	wp_enqueue_script( 'instantio-admin-script', INSTANTIO_ASSETS_URL . '/admin/js/instantio-admin-script.js', array( 'jquery' ), INSTANTIO_VERSION, true );

	wp_localize_script( 'instantio-admin-script', 'instantio_admin_params',
		array(
			'ins_nonce' => wp_create_nonce( 'instantio_updates' ),
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		)
	);
}
