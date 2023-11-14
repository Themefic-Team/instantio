<?php
	// Reset Data
	add_filter(
		'plugin_row_meta',
		/**
		 * Add links below the description on the Plugins page.
		 *
		 * @param array $links
		 * @param string $file
		 * @retun array
		 */
		function( $links, $file ) {

			if ( INS_BASE_LOCATION !== $file ) {
				return $links;
			}

			return array_merge(
				$links,
				array(
					sprintf(
						'<a target="_blank" href="%1$s">%2$s</a>',
						'https://themefic.com/docs/instantio/',
						__( 'Documentation', 'instantio' )
					),
					sprintf(
						'<a target="_blank" href="%1$s">%2$s</a>',
						'https://portal.themefic.com/support/',
						__( 'Get help', 'instantio' )
					),
					sprintf(
						'<a target="_blank" href="%1$s">%2$s</a>',
						'https://themefic.com/feature-request/',
						__( 'Request a feature', 'instantio' )
					),
					sprintf(
						'<a target="_blank" href="%1$s">%2$s</a>',
						'https://portal.themefic.com/support/',
						__( 'Submit a bug', 'instantio' )
					),
				)
			);
		},
		10,
		2
	);

	// Reset Data
	add_action( 'wp_ajax_nopriv_ins_del_billing_fields', 'ins_del_billing_fields'  );  
	add_action( 'wp_ajax_ins_del_billing_fields', 'ins_del_billing_fields');
	add_action( 'wp_ajax_nopriv_ins_del_shipping_fields', 'ins_del_shipping_fields'  );  
	add_action( 'wp_ajax_ins_del_shipping_fields', 'ins_del_shipping_fields');

	function insopt( $option = '', $default = null ) {
		$options = get_option( 'wiopt' ); 
		return ( isset( $options[$option] ) ) ? $options[$option] : $default;
	}

	/**
	 * Check Pro Active or not
	 * @since 3.1.6
	 * @author M Hemel Hasan
	 * @return bool
	 */
	function is_tf_pro_active() {
		if ( is_plugin_active( 'wooinstant/wooinstant.php' ) && class_exists('WOOINS') ) {
			return true;
		}
		return false;
	}

	function ins_del_billing_fields() {
		$ins_billing_fields = get_option('wiopt');

		if (isset($ins_billing_fields['checkout_editors_fields'])) {
			// Remove the 'checkout_editors_fields' key from the 'wiopt' option
			unset($ins_billing_fields['checkout_editors_fields']);
		
			// Update the 'wiopt' option without the 'checkout_editors_fields' key
			update_option('wiopt', $ins_billing_fields);
		}

	}



	function ins_del_shipping_fields() {
		$ins_shipping_fields = get_option('wiopt');

		if (isset($ins_shipping_fields['checkout_shiping_editors_fields'])) {
			// Remove the 'checkout_shiping_editors_fields' key from the 'wiopt' option
			unset($ins_shipping_fields['checkout_shiping_editors_fields']);
		
			// Update the 'wiopt' option without the 'checkout_shiping_editors_fields' key
			update_option('wiopt', $ins_shipping_fields);
		}

	}

	/**
	 * Black Friday Deals 2023
	 */
	if(!function_exists('tf_black_friday_2023_admin_notice')){
		function tf_black_friday_2023_admin_notice(){ 
			if(is_tf_pro_active()){
				return;
			}

			$deal_link =sanitize_url('https://themefic.com/deals/');
			$get_current_screen = get_current_screen();  
			if(!isset($_COOKIE['tf_dismiss_admin_notice']) && $get_current_screen->base == 'dashboard'){ 
				?>
				<style> 
					.tf_black_friday_20222_admin_notice a:focus {
						box-shadow: none;
					} 
					.tf_black_friday_20222_admin_notice {
						padding: 7px;
						position: relative;
						z-index: 10;
					} 
					.tf_black_friday_20222_admin_notice button:before {
						color: #fff !important;
					}
					.tf_black_friday_20222_admin_notice button:hover::before {
						color: #d63638 !important;
					}
				</style>
				<div class="notice notice-success tf_black_friday_20222_admin_notice"> 
					<a href="<?php echo $deal_link; ?>" target="_blank" >
						<img  style="width: 100%;" src="<?php echo INS_ASSETS_URL ?>/img/BLACK_FRIDAY_BACKGROUND_GRUNGE_notice.png" alt="">
					</a> 
					<button type="button" class="notice-dismiss tf_black_friday_notice_dismiss"><span class="screen-reader-text"><?php echo __('Dismiss this notice.', 'ultimate-addons-cf7' ) ?></span></button>
				</div>
				<script>
					jQuery(document).ready(function($) {
						$(document).on('click', '.tf_black_friday_notice_dismiss', function( event ) {
							jQuery('.tf_black_friday_20222_admin_notice').css('display', 'none')
							data = {
								action : 'tf_black_friday_notice_dismiss_callback',
							};
							$.ajax({
								url: ajaxurl,
								type: 'post',
								data: data,
								success: function (data) { ;
								},
								error: function (data) { 
								}
							});
						});
					});
				</script>
			
			<?php 
			}
			
		} 
		if (strtotime('2023-12-01') > time()) {
			add_action( 'admin_notices', 'tf_black_friday_2023_admin_notice' );  
		}   
	}
	if(!function_exists('tf_black_friday_notice_dismiss_callback')){
		function tf_black_friday_notice_dismiss_callback() { 
			$cookie_name = "tf_dismiss_admin_notice";
			$cookie_value = "1"; 
			setcookie($cookie_name, $cookie_value, strtotime('2023-12-01'), "/"); 
			wp_die();
		}
		add_action( 'wp_ajax_tf_black_friday_notice_dismiss_callback', 'tf_black_friday_notice_dismiss_callback' );
	}

?>