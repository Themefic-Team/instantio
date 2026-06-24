<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ins_Dashboard_Promo_Notice {

	const NOTICE_KEY = 'ins_pro_promo';

	public function __construct() {

		add_action(
			'wp_ajax_ins_dismiss_promo_notice',
			array( $this, 'dismiss_notice' )
		);

		/**
		 * Custom hook.
		 *
		 * Usage:
		 * do_action( 'ins_dashboard_promo_notice' );
		 */
		add_action(
			'ins_dashboard_promo_notice',
			array( $this, 'render' )
		);
	}

    // instance method to make it singleton class
    public static function instance() {
        static $instance = null;

        if ( is_null( $instance ) ) {
            $instance = new self();
        }

        return $instance;
    }

	/**
	 * Check if Pro plugin active.
	 */
	private function is_pro_active() {

        if ( defined( 'INSTANTIO_PRO_VERSION' ) || class_exists( 'WOOINS' ) ) {
            return true;
        }
		
        return false;
	}

	private function get_dynamic_pricing() {

		$cache_key = 'ins_dynamic_pricing';

		$pricing = get_transient( $cache_key );

		if ( false !== $pricing ) {
			return $pricing;
		}

		$response = wp_remote_get(
			'http://api.themefic.com/dynamic-pricing/pricing.json',
			array(
				'timeout'     => 10,
				'redirection' => 3,
			)
		);

		if ( is_wp_error( $response ) ) {
			return array();
		}

		if ( 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			return array();
		}

		$body = wp_remote_retrieve_body( $response );

		if ( empty( $body ) ) {
			return array();
		}

		$data = json_decode( $body, true );

		if (
			JSON_ERROR_NONE !== json_last_error() ||
			! is_array( $data )
		) {
			return array();
		}

		set_transient(
			$cache_key,
			$data,
			DAY_IN_SECONDS
		);

		return $data;
	}

	private function get_current_offer() {

		$base_price = 199;

		$fallback = array(
			'offer_name'     => 'Special Deal',
			'discount'       => 75,
			'coupon'         => '',
			'regular_price'  => $base_price,
			'discount_price' => 49,
		);

		$pricing = $this->get_dynamic_pricing();

		try {

			$datetime = new DateTime(
				'now',
				new DateTimeZone( 'America/Toronto' )
			);

			$is_weekend = ( (int) $datetime->format( 'N' ) >= 6 );

			$key = $is_weekend ? 'weekend' : 'weekday';

			if (
				empty( $pricing[ $key ] ) ||
				! is_array( $pricing[ $key ] )
			) {
				return $fallback;
			}

			$config = $pricing[ $key ];

			$discount = isset( $config['discount'] )
				? absint( $config['discount'] )
				: 0;

			if ( $discount <= 0 || $discount >= 100 ) {
				return $fallback;
			}

			$discount_price = (int) floor(
				$base_price * ( ( 100 - $discount ) / 100 )
			);

			$coupon = '';

			if ( ! empty( $config['coupons']['instantio'] ) ) {
				$coupon = sanitize_text_field(
					$config['coupons']['instantio']
				);
			}

			if ( ! empty( $config['coupon'] ) ) {
				$coupon = sanitize_text_field(
					$config['coupon']
				);
			}

			return array(
				'offer_name'     => ! empty( $config['offer'] )
					? sanitize_text_field( $config['offer'] )
					: 'Special Deal',
				'discount'       => $discount,
				'coupon'         => $coupon,
				'regular_price'  => $base_price,
				'discount_price' => $discount_price,
			);

		} catch ( Exception $e ) {
			return $fallback;
		}
	}

	/**
	 * Should display notice?
	 */
	public function should_display() {
		
		if ( $this->is_pro_active() ) {
			return false;
		}
		
		$user_id = get_current_user_id();

		$data = get_user_meta(
			$user_id,
			self::NOTICE_KEY,
			true
		);

		if ( empty( $data ) ) {
			return true;
		}

		if ( ! empty( $data['forever'] ) ) {
			return false;
		}

		if (
			! empty( $data['hide_until'] ) &&
			time() < absint( $data['hide_until'] )
		) {
			return false;
		}

		return true;
	}

	/**
	 * Render banner.
	 */
	public function render() {

		// if ( ! $this->should_display() ) {
		// 	return;
		// }

		$offer = $this->get_current_offer();

		?>

		<div class="ins-promo-banner">

			<button
				type="button"
				class="ins-promo-close"
				aria-label="<?php esc_attr_e( 'Dismiss', 'instantio' ); ?>"
			>
				<svg width="9" height="9" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 0.5L0.5 8M0.5 0.5L8 8" stroke="#626A6A" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
			</button>

			<div class="ins-promo-icon">

				<img style="height:72px; width:60px;" src="<?php echo INS_ASSETS_URL; ?>/img/shield-icon.gif" alt="shield logo">

			</div>

			<div class="ins-promo-content">

				<h3>
					<?php
					echo esc_html(
						sprintf(
							__( 'Lifetime License only for $%s', 'instantio' ),
							number_format_i18n( $offer['discount_price'] )
						)
					);
					?>
				</h3>

				<p>
					<?php esc_html_e(
						'All PRO features included.',
						'instantio'
					); ?>
				</p>

			</div>

			<div class="ins-promo-action">
				<a
					href="<?php echo esc_url( ins_utm_generator( 'https://themefic.com/instantio/pricing/', array( 'utm_medium' => 'dashboard_promo_notice', 'utm_source' => 'ins_in_plugin_addons_button', 'utm_campaign' => 'ins_plugin_free' ) ) ); ?>"
					target="_blank"
					class="button button-primary"
				>
					
					<div class="buy-now-text">
						<?php esc_html_e( 'Buy Now', 'instantio' ); ?>
					</div>
					<div class="arrow-icon">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M17 17V7H7M17 7L7 17" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</div>
				</a>

			</div>

		</div>

		<?php
	}

	/**
	 * Dismiss notice.
	 */
	public function dismiss_notice() {

		check_ajax_referer(
			'ins_notice_nonce',
			'nonce'
		);

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Unauthorized' );
        }

		$user_id = get_current_user_id();

		$data = get_user_meta(
			$user_id,
			self::NOTICE_KEY,
			true
		);

		if ( empty( $data ) ) {
			$data = array(
				'dismiss_count' => 1,
				'hide_until'    => strtotime( '+7 days' ),
			);
		} else {

			$count = isset( $data['dismiss_count'] )
				? absint( $data['dismiss_count'] )
				: 0;

			$count++;

			if ( $count >= 2 ) {

				$data = array(
					'dismiss_count' => $count,
					'forever'       => true,
				);

			} else {

				$data = array(
					'dismiss_count' => $count,
					'hide_until'    => strtotime( '+7 days' ),
				);
			}
		}

		update_user_meta(
			$user_id,
			self::NOTICE_KEY,
			$data
		);

		wp_send_json_success();
	}

}

ins_dashboard_promo_notice::instance();