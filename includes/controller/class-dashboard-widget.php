<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class InsDashboardWidget {

    private static $instance = null;

    public function __construct() {
        add_action( 'wp_dashboard_setup', array( $this, 'ins_register_dashboard_widget' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'ins_widget_enqueue_assets' ) );
    }

    public static function instance() {
        if ( ! self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function ins_register_dashboard_widget() {
        wp_add_dashboard_widget( 'ins_widget', __( 'Instantio Overview', 'instantio' ), array( $this, 'ins_display_dashboard_widget' ) , null, null, 'normal', 'high' );
    }

    public function ins_widget_enqueue_assets( $screen ) {

        /**
		 * Admin Dashboard CSS
		 */
		if ( $screen == 'index.php' ) {
			wp_enqueue_style( 'ins-admin-dashboard', INS_ASSETS_URL . '/admin/css/ins-admin-dashboard.css', '', INSTANTIO_VERSION );
		}

    }

    public function ins_display_dashboard_widget() {
        $insOptions = insopt();
        ?>
        <div class="ins-widget">

            <!-- Stats Row -->
            <div class="ins-stats">
                <div class="ins-stat">
                    <?php
                        $products = wp_count_posts( 'product' );
                        $totalProducts = isset( $products->publish ) ? (int) $products->publish : 0;
                    ?>
                    <strong><?php echo esc_html( $totalProducts ); ?></strong>
                    <span><?php esc_html_e( 'Total Products', 'instantio' ); ?></span>
                </div>
                <div class="ins-stat">
                    <strong>
                        <?php 
                        // Total Active Orders
                        $active_statuses = [ 'wc-pending', 'wc-processing', 'wc-on-hold' ];

                        $orders = wc_get_orders( [
                            'status' => $active_statuses,
                            'limit'  => -1,
                            'return' => 'ids',
                        ] );

                        $total_active_orders = count( $orders );

                        echo $total_active_orders;
                        ?>
                    </strong>
                    <span><?php esc_html_e( 'Total Active Orders', 'instantio' ); ?></span>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="ins-actions">
                <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=product' ) ); ?>" class="button button-primary">
                    <?php esc_html_e( 'Create new product', 'instantio' ); ?>
                </a>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wiopt#tab=general' ) ); ?>" class="button">
                    <?php esc_html_e( 'Instantio Settings', 'instantio' ); ?>
                </a>
            </div>
            
            <!-- Popular Integrations -->
            <div class="ins-section ins-integrations">
                <h4><?php esc_html_e( 'Highlighted Features', 'instantio' ); ?></h4>

                <div class="ins-integration-grid">
                    <div class="ins-integration-item">
                        <svg fill="#2271b1" viewBox="0 0 52 52" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" stroke="#2271b1"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M38.67,27.35A11.33,11.33,0,1,1,27.35,38.67h0A11.34,11.34,0,0,1,38.67,27.35ZM20.36,37.63a4,4,0,1,1-4,4v0A4,4,0,0,1,20.36,37.63ZM42.8,34.07l-6.06,6.79L34,38.09a.79.79,0,0,0-1.11,0l0,0-1.11,1.07a.7.7,0,0,0-.07,1l.07.08L35.6,44a1.62,1.62,0,0,0,1.14.48A1.47,1.47,0,0,0,37.87,44l7.19-7.87a.83.83,0,0,0,0-1l-1.12-1.05a.79.79,0,0,0-1.11,0ZM8.2,2a2.42,2.42,0,0,1,2.25,1.7h0l.62,2.16H46.36A1.5,1.5,0,0,1,47.9,7.31a1.24,1.24,0,0,1-.06.47h0L43.66,22.43a1.42,1.42,0,0,1-.52.82,16.42,16.42,0,0,0-4.47-.64,16,16,0,0,0-5.47,1H19.36a2.2,2.2,0,0,0-2.22,2.18,2.11,2.11,0,0,0,.13.75h0v.08a2.26,2.26,0,0,0,2.17,1.62h7.1a16,16,0,0,0-2.77,4.61H16a2.32,2.32,0,0,1-2.25-1.7h0L6.5,6.62H4.33A2.37,2.37,0,0,1,2,4.22V4.16A2.46,2.46,0,0,1,4.48,2H8.2Z"></path></g></svg>
                        <span><?php esc_html_e( 'Instant Checkout', 'instantio' ); ?></span>
                    </div>

                    <div class="ins-integration-item">
                        <svg fill="#2271b1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52" enable-background="new 0 0 52 52" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M20,37.5c0-0.8-0.7-1.5-1.5-1.5h-15C2.7,36,2,36.7,2,37.5v11C2,49.3,2.7,50,3.5,50h15c0.8,0,1.5-0.7,1.5-1.5 V37.5z"></path> <path d="M8.1,22H3.2c-1,0-1.5,0.9-0.9,1.4l8,8.3c0.4,0.3,1,0.3,1.4,0l8-8.3c0.6-0.6,0.1-1.4-0.9-1.4h-4.7 c0-5,4.9-10,9.9-10V6C15,6,8.1,13,8.1,22z"></path> <path d="M41.8,20.3c-0.4-0.3-1-0.3-1.4,0l-8,8.3c-0.6,0.6-0.1,1.4,0.9,1.4h4.8c0,6-4.1,10-10.1,10v6 c9,0,16.1-7,16.1-16H49c1,0,1.5-0.9,0.9-1.4L41.8,20.3z"></path> <path d="M50,3.5C50,2.7,49.3,2,48.5,2h-15C32.7,2,32,2.7,32,3.5v11c0,0.8,0.7,1.5,1.5,1.5h15c0.8,0,1.5-0.7,1.5-1.5 V3.5z"></path> </g></svg>
                        <span><?php esc_html_e( 'Floating Cart', 'instantio' ); ?></span>
                    </div>

                    <div class="ins-integration-item">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#2271b1"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M15.9087 3.87352C16.4681 3.31421 17.2266 3 18.0176 3C18.4093 3 18.7971 3.07714 19.1589 3.22702C19.5208 3.3769 19.8495 3.59658 20.1265 3.87352C20.4034 4.15046 20.6231 4.47924 20.773 4.84108C20.9229 5.20292 21 5.59074 21 5.98239C21 6.37404 20.9229 6.76186 20.773 7.1237C20.6231 7.48554 20.4034 7.81432 20.1265 8.09126L19.0231 9.19466C18.6326 9.58519 17.9994 9.58519 17.6089 9.19467L14.8053 6.39114C14.4148 6.00062 14.4148 5.36745 14.8053 4.97693L15.9087 3.87352ZM13.3911 7.80536C13.0006 7.41483 12.3674 7.41483 11.9769 7.80536L5.01084 14.7714C4.37004 15.4122 3.91545 16.2151 3.69566 17.0943L3.02986 19.7575C2.94467 20.0982 3.04452 20.4587 3.2929 20.7071C3.54128 20.9555 3.90177 21.0553 4.24254 20.9701L6.90572 20.3043C7.78488 20.0846 8.58778 19.63 9.22857 18.9892L16.1946 12.0231C16.5852 11.6326 16.5852 10.9994 16.1946 10.6089L13.3911 7.80536Z" fill="#2271b1"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M12 20C12 19.4477 12.4477 19 13 19L20 19C20.5523 19 21 19.4477 21 20C21 20.5523 20.5523 21 20 21L13 21C12.4477 21 12 20.5523 12 20Z" fill="#2271b1"></path> </g></svg>
                        <span><?php esc_html_e( 'Checkout Editor', 'instantio' ); ?></span>
                    </div>

                    <div class="ins-integration-item">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M7.00016 4.76363C7.84716 3.06962 9.57838 2 11.4721 2H12.5279C14.4218 2 16.1531 3.07003 17 4.76396L17.8944 6.55279C18.1414 7.04677 17.9412 7.64744 17.4472 7.89443C16.9532 8.14142 16.3526 7.94119 16.1056 7.44721L15.2112 5.65838C14.703 4.64202 13.6642 4 12.5279 4H11.4721C10.3357 4 9.29715 4.64179 8.78901 5.65805L7.89443 7.44721C7.64744 7.94119 7.04677 8.14142 6.55279 7.89443C6.05881 7.64744 5.85859 7.04676 6.10558 6.55279L7.00016 4.76363Z" fill="#2271b1"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M6.45925 6C4.02505 6 2.1552 8.15595 2.49945 10.5657L3.51965 17.7071C3.87155 20.1704 5.98115 22 8.4694 22H15.531C18.0193 22 20.1289 20.1704 20.4808 17.7071L21.501 10.5657C21.8452 8.15595 19.9754 6 17.5412 6H6.45925ZM15.6839 11.2705C16.0869 11.6482 16.1073 12.281 15.7295 12.6839L11.9795 16.6839C11.6213 17.0661 11.0289 17.107 10.6215 16.7778L8.37148 14.9596C7.94192 14.6125 7.87508 13.9829 8.22221 13.5533C8.56933 13.1237 9.19896 13.0569 9.62852 13.404L11.1559 14.6383L14.2705 11.3161C14.6482 10.9131 15.281 10.8927 15.6839 11.2705Z" fill="#2271b1"></path> </g></svg>
                        <span><?php esc_html_e( 'AJAX Add to Cart', 'instantio' ); ?></span>
                    </div>

                </div>
            </div>

            <!-- Button for more integrations -->
            <div class="ins-actions">
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wiopt=#tab=layout_option' ) ); ?>" class="button">
                    <?php esc_html_e( 'Check More Features', 'instantio' ); ?>
                </a>
            </div>

            <?php if(!class_exists('WOOINS')){  ?>
            <!-- Upsell -->
            <div class="ins-upsell">
                <h4><?php esc_html_e( 'Unlock Pro Features', 'instantio' ); ?></h4>
                <ul>
                    <li><?php esc_html_e( '✔ Single Page Checkout', 'instantio' ); ?></li>
                    <li><?php esc_html_e( '✔ Upsells Products', 'instantio' ); ?></li>
                    <li><?php esc_html_e( '✔ Crossell Products', 'instantio' ); ?></li>
                    <li><?php esc_html_e( '✔ Customize Checkout Fields', 'instantio' ); ?></li>
                </ul>
                <a href="<?php echo esc_url( 'https://themefic.com/instantio/pricing/' ); ?>" target="_blank" class="button button-primary go-pro">
                    <?php esc_html_e( 'Upgrade Now', 'instantio' ); ?>
                </a>
            </div>
            <?php } ?>

            <!-- Blog Section -->
			<div class="ins-section-title"><?php esc_html_e( 'Latest posts from Instantio', 'instantio' ); ?></div>
			<ul class="ins-blog-list">
				<li>
					<span class="ins-badge"><?php esc_html_e( 'NEW', 'instantio' ); ?></span>
					<a href="<?php echo esc_url( 'https://themefic.com/instantio-mini-cart-drawer/' ); ?>" target="_blank"><?php esc_html_e( 'Enhance Your WooCommerce Checkout with the Instantio Mini Cart Drawer', 'instantio' ); ?></a>
				</li>
				<li>
					<a href="<?php echo esc_url( 'https://themefic.com/woocommerce-checkout-field-editor/' ); ?>" target="_blank"><?php esc_html_e( 'Best WooCommerce Checkout Field Editor to Customize WooCommerce Checkout Fields', 'instantio' ); ?></a>
				</li>
				<li>
					<a href="<?php echo esc_url( 'https://themefic.com/reduce-cart-abandonment-on-your-ecommerce-store/' ); ?>" target="_blank"><?php esc_html_e( 'How to Reduce Cart Abandonment on Your WooCommerce Website – A Definitive Guide', 'instantio' ); ?></a>
				</li>
			</ul>

            <!-- Footer -->
            <div class="ins-footer">
                <a href="<?php echo esc_url( 'https://themefic.com/docs/instantio/' ); ?>" target="_blank">
                    <?php esc_html_e( 'Docs', 'instantio' ); ?>
                    <span aria-hidden="true" class="dashicons dashicons-external"></span>
                </a>
                <a href="<?php echo esc_url( 'https://portal.themefic.com/support/' ); ?>" target="_blank">
                    <?php esc_html_e( 'Support', 'instantio' ); ?>
                    <span aria-hidden="true" class="dashicons dashicons-external"></span>
                </a>
                <a href="<?php echo esc_url( 'https://themefic.com/blog/' ); ?>" target="_blank">
                    <?php esc_html_e( 'Blog', 'instantio' ); ?>
                    <span aria-hidden="true" class="dashicons dashicons-external"></span>
                </a>
                <a href="<?php echo esc_url( 'https://themefic.com/instantio/pricing/' ); ?>" target="_blank" class="go-pro">
                    <?php esc_html_e( 'Buy Now', 'instantio' ); ?>
                    <span aria-hidden="true" class="dashicons dashicons-external"></span>
                </a>
            </div>

        </div>
        <?php
    }



}

InsDashboardWidget::instance();