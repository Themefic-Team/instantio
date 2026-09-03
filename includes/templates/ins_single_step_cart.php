<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

// WooCommerce template variables/hooks are compatibility APIs; copied WooCommerce strings retain its translations.
// phpcs:disable WordPress.WP.I18n.TextDomainMismatch
$instantio_layout = ! empty( instantio_get_option( 'ins-layout-options' ) ) ? instantio_get_option( 'ins-layout-options' ) : '1';
do_action( 'woocommerce_before_cart' ); ?>
<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

	<div class="ins-checkout-body ins-content">
		<div class="ins-cart-content-wrap">
			<!-- Single Cart item title Start -->
			<div class="ins-cart-item-heading">
				<span class="ins-cart-item-heading-remove"></span>
				<span class="ins-cart-item-heading-title">
					<?php esc_html_e( 'Product', 'woocommerce' ); ?>
				</span>
				<span class="ins-cart-item-heading-price">
					<?php esc_html_e( 'Price', 'woocommerce' ); ?>
				</span>
				<span class="ins-cart-item-heading-quantity">
					<?php esc_html_e( 'Quantity', 'woocommerce' ); ?>
				</span>
				<span class="ins-cart-item-heading-total">
					<?php esc_html_e( 'Subtotal', 'woocommerce' ); ?>
				</span>
			</div>

			<div class="ins-single-cart-wrap">
				<!-- Single Cart item end Start -->

				<?php do_action( 'woocommerce_before_cart_table' ); ?>
				<?php
				foreach ( WC()->cart->get_cart() as $instantio_cart_item_key => $instantio_cart_item ) {

					$instantio_product = apply_filters( 'woocommerce_cart_item_product', $instantio_cart_item['data'], $instantio_cart_item, $instantio_cart_item_key );
					$instantio_product_id = apply_filters( 'woocommerce_cart_item_product_id', $instantio_cart_item['product_id'], $instantio_cart_item, $instantio_cart_item_key );
					$instantio_variation_id = $instantio_cart_item['variation_id'];
					if ( $instantio_product && $instantio_product->exists() && $instantio_cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $instantio_cart_item, $instantio_cart_item_key ) ) {
						$instantio_product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $instantio_product->is_visible() ? $instantio_product->get_permalink( $instantio_cart_item ) : '', $instantio_cart_item, $instantio_cart_item_key );
						?>
						<!-- Single Cart Item Start -->
						<div class="ins-single-cart-item woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $instantio_cart_item, $instantio_cart_item_key ) ); ?>"
								data-cart-item-key="<?php echo esc_attr( $instantio_cart_item_key ); ?>"
								data-product-id="<?php echo absint( $instantio_cart_item['product_id'] ); ?>">
							<div class="ins-cart-remove">

								<span class="ins-cart-item-remove product-remove">
									<?php
									echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-variation_id="%s" data-product_sku="%s">
                                                <span class="ins-single-item-remove"><svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4.83366 2.33329V0.666626H13.167V2.33329H17.3337V3.99996H15.667V16.5C15.667 16.721 15.5792 16.9329 15.4229 17.0892C15.2666 17.2455 15.0547 17.3333 14.8337 17.3333H3.16699C2.94598 17.3333 2.73402 17.2455 2.57774 17.0892C2.42146 16.9329 2.33366 16.721 2.33366 16.5V3.99996H0.666992V2.33329H4.83366ZM4.00033 3.99996V15.6666H14.0003V3.99996H4.00033ZM6.50033 6.49996H8.16699V13.1666H6.50033V6.49996ZM9.83366 6.49996H11.5003V13.1666H9.83366V6.49996Z" fill="#535E70"/>
                                                </svg></span>
                                            </a>',
											esc_url( wc_get_cart_remove_url( $instantio_cart_item_key ) ),
											esc_html__( 'Remove this item', 'woocommerce' ),
											esc_attr( $instantio_product_id ),
											esc_attr( $instantio_variation_id ),
											esc_attr( $instantio_product->get_sku() )
										),
										$instantio_cart_item_key
									);
									?>
								</span>

							</div>
							<div class="ins-cart-item-product">
								<div class="ins-cart-item-image">
									<?php
									$instantio_thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $instantio_product->get_image(), $instantio_cart_item, $instantio_cart_item_key );

									if ( ! $instantio_product_permalink ) {
										// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce image HTML filtered by woocommerce_cart_item_thumbnail.
										echo $instantio_thumbnail;
									} else {
										// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- URL is escaped; thumbnail is WooCommerce-filtered image HTML.
										printf( '<a href="%s">%s</a>', esc_url( $instantio_product_permalink ), $instantio_thumbnail );
									}
									?>
								</div>
								<div class="ins-cart-item-title" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
									<?php

									$instantio_product_name = $instantio_product->get_name();

									if ( strlen( $instantio_product_name ) > 30 ) {
										$instantio_limited_name = substr( $instantio_product_name, 0, 30 ) . "...";
									} else {
										$instantio_limited_name = $instantio_product_name;
									}

									if ( ! $instantio_product_permalink ) {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $instantio_limited_name, $instantio_cart_item, $instantio_cart_item_key ) . '&nbsp;' );
									} else {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $instantio_product_permalink ), $instantio_limited_name ), $instantio_cart_item, $instantio_cart_item_key ) );
									}

									do_action( 'woocommerce_after_cart_item_name', $instantio_cart_item, $instantio_cart_item_key );

									// Meta data.
										echo wp_kses_post( wc_get_formatted_cart_item_data( $instantio_cart_item ) );
							
									// Backorder notification.
									if ( $instantio_product->backorders_require_notification() && $instantio_product->is_on_backorder( $instantio_cart_item['quantity'] ) ) {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $instantio_product_id ) );
									}
									?>
								</div>
							</div>
							<div class="ins-cart-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
								<?php
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $instantio_product ), $instantio_cart_item, $instantio_cart_item_key ) );
								?>
							</div>
							<div class="ins-cart-item-quantity ins-cart-qty-wrap">
								<?php
								if ( $instantio_product->is_sold_individually() ) {
									$instantio_min_quantity = 1;
									$instantio_max_quantity = 1;
								} else {
									$instantio_min_quantity = 0;
									$instantio_max_quantity = $instantio_product->get_max_purchase_quantity();
								}
								$instantio_minus_icon = '<svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_173_413)">
                                <rect x="4.66699" y="9.16663" width="11.6667" height="1.66667" fill="#494E5C"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_173_413">
                                <rect width="20" height="20" fill="white" transform="translate(0.5)"/>
                                </clipPath>
                                </defs>
                                </svg>';

								$instantio_plus_icon = '<svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_173_410)">
                                <path d="M9.66699 9.16663V4.16663H11.3337V9.16663H16.3337V10.8333H11.3337V15.8333H9.66699V10.8333H4.66699V9.16663H9.66699Z" fill="#494E5C"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_173_410">
                                <rect width="20" height="20" fill="white" transform="translate(0.5)"/>
                                </clipPath>
                                </defs>
                                </svg>';


								/* translators: %s: Product name. */
								$instantio_decrease_quantity_label = sprintf( __( 'Decrease quantity for %s', 'instantio' ), $instantio_product->get_name() );
								/* translators: %s: Product name. */
								$instantio_increase_quantity_label = sprintf( __( 'Increase quantity for %s', 'instantio' ), $instantio_product->get_name() );
								$instantio_product_quantity = '<button type="button" class="minus ins-cart-minus" aria-label="' . esc_attr( $instantio_decrease_quantity_label ) . '">' . $instantio_minus_icon . '</button>';
								$instantio_product_quantity .= woocommerce_quantity_input(
									array(
										'input_name' => "cart[{$instantio_cart_item_key}][qty]",
										'input_value' => $instantio_cart_item['quantity'],
										'max_value' => $instantio_max_quantity,
										'min_value' => $instantio_min_quantity,
										'product_name' => $instantio_product->get_name(),
									),
									$instantio_product,
									false
								);
								$instantio_product_quantity .= '<button type="button" class="plus ins-cart-plus" aria-label="' . esc_attr( $instantio_increase_quantity_label ) . '">' . $instantio_plus_icon . '</button>';

								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce quantity HTML plus static local buttons; callbacks own escaping.
								echo apply_filters( 'woocommerce_cart_item_quantity', $instantio_product_quantity, $instantio_cart_item_key, $instantio_cart_item );
								?>

							</div>
							<div class="ins-cart-item-total" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
								<?php
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $instantio_product, $instantio_cart_item['quantity'] ), $instantio_cart_item, $instantio_cart_item_key ) );
								?>
							</div>

						</div>
						<!-- Single Cart Item End -->
						<?php
					}
				}
				?>
			</div>
		</div>

			<?php echo wp_kses_post( apply_filters( 'instantio_show_items_upsells', '' ) ); ?>
	</div>

	<?php do_action( 'woocommerce_cart_contents' ); ?>



	<!-- Cart Footer Content -->
	<div class="ins-cart-footer-wrap">
		<div class="ins-cart-footer-content">
			<div class="ins-footer-cart-button">
				<div class="ins-cart-coupon">
					<?php if ( wc_coupons_enabled() ) { ?>
						<div class="coupon">
							<label class="screen-reader-text" for="coupon_code"><?php esc_html_e( 'Coupon code', 'woocommerce' ); ?></label>
							<input type="text" name="coupon_code" class="input-text" id="coupon_code" value=""
								placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" />

							<button type="submit"
								class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>"
								name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>">
								<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>
							</button>

							<?php do_action( 'woocommerce_cart_coupon' ); ?>
						</div>
					<?php } ?>

					<button type="submit"
						class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> ins-cart-coupon-updated-cart"
						name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>">
						<?php esc_html_e( 'Update cart', 'woocommerce' ); ?>
					</button>

					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
				</div>
				<div class="ins-empty-cart-button">
					<button class="ins-empty-cart">
						<?php esc_html_e( 'Empty Cart', 'instantio' ); ?>
					</button>
				</div>
			</div>

			<?php do_action( 'woocommerce_after_cart_table' ); ?>

			<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

			<div class="ins-cart-collaterals cart-collaterals">
				<?php

				/**
				 * Cart collaterals hook.
				 *
				 * @hooked woocommerce_cross_sell_display
				 * @hooked woocommerce_cart_totals - 10
				 */

				// do_action( 'woocommerce_cart_collaterals' );
				
				woocommerce_cart_totals();
				?>
			</div>
		</div>
	</div>

</form>



<?php //do_action( 'woocommerce_after_cart' );  ?>
