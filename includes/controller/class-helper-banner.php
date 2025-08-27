<?php
if(!defined('ABSPATH')){
    exit;
}

class INS_Helper_Banner {

    public function __construct(){

        if(!class_exists('WOOINS')){

            add_filter('ins_dashboard_helper_banner', [$this, 'render_helper_banner'], 10, 2);
            add_action('admin_footer', [ $this, 'ins_admin_helper_footer_script']);

        }
    }

    public function render_helper_banner() {

        $campaign_id = 'flash60'; // change campaign id to start a new campaign
        $user_id = get_current_user_id();
        $user_first_visit_meta_key = 'ins_fomo_first_visit_time_' . $campaign_id;

        // Check if we're on the right page to initiate the countdown
        $screen = get_current_screen();
        
        if ($screen && $screen->id === 'toplevel_page_wiopt') {
            // Only set countdown start time on first visit
            if (!get_user_meta($user_id, $user_first_visit_meta_key, true)) {
                update_user_meta($user_id, $user_first_visit_meta_key, time());
            }
        }

        // If countdown has never started, exit early
        $countdown_start = get_user_meta($user_id, $user_first_visit_meta_key, true);
        
        if (!$countdown_start) {
            return;
        }

        $description         = 'Buy within next 2 hours to avail this discount. Hurry, clock is ticking...';
        $button_url          = 'https://themefic.com/instantio/discount-deal/';
        $button_text         = 'Grab this deal now';
        $discount_percentage = 60;

        $campaign_restart    = false; // set to false to disable
        $interval_time       = (float) 168; // interval in hours (support float values, e.g. 0.01 = 36 seconds)
        $duration            = 109; // duration in minutes
        $end_time            = $countdown_start + ($duration * 60);
        $remaining           = max(0, $end_time - time());

        $should_show = true; // set to false to disable

        // Handle restart logic
        if ($remaining === 0) {
            $restart = $campaign_restart; 
            $interval_hours = $interval_time;  

            if ($restart === true && $interval_hours > 0) {
                $next_start_time = $end_time + ($interval_hours * 3600);
                if (time() < $next_start_time) {
                    $should_show = false;
                } else {
                    // Restart countdown
                    $new_start = time();
                    update_user_meta($user_id, $user_first_visit_meta_key, $new_start);
                    $end_time = $new_start + ($duration * 60);
                    $remaining = max(0, $end_time - time());
                }
            } else {
                $should_show = false;
            }
        }

        if (!$should_show) {
            return;
        }

        $countdown_html = '<div class="ins-promo-countdown" data-end-time="' . esc_attr($end_time) . '"></div>';

        ob_start();
        ?>
        <div class="ins-sidebar-promo">
            <div class="promo-discount">
                <span>
                    <?php echo esc_html('Get'); ?>
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5.44473V17M9 5.44473C8.67847 4.11978 8.12484 2.98706 7.41132 2.19428C6.6978 1.4015 5.8575 0.985449 5 1.00039C4.41063 1.00039 3.8454 1.23451 3.42865 1.65125C3.0119 2.06798 2.77778 2.6332 2.77778 3.22256C2.77778 3.81191 3.0119 4.37713 3.42865 4.79387C3.8454 5.2106 4.41063 5.44473 5 5.44473M9 5.44473C9.32153 4.11978 9.87516 2.98706 10.5887 2.19428C11.3022 1.4015 12.1425 0.985449 13 1.00039C13.5894 1.00039 14.1546 1.23451 14.5713 1.65125C14.9881 2.06798 15.2222 2.6332 15.2222 3.22256C15.2222 3.81191 14.9881 4.37713 14.5713 4.79387C14.1546 5.2106 13.5894 5.44473 13 5.44473M15.2222 9.00019V15.2223C15.2222 15.6937 15.0349 16.1459 14.7015 16.4793C14.3681 16.8127 13.9159 17 13.4444 17H4.55556C4.08406 17 3.63187 16.8127 3.29848 16.4793C2.96508 16.1459 2.77778 15.6937 2.77778 15.2223V9.00019M1.88889 5.44473H16.1111C16.602 5.44473 17 5.84268 17 6.33359V8.11133C17 8.60224 16.602 9.00019 16.1111 9.00019H1.88889C1.39797 9.00019 1 8.60224 1 8.11133V6.33359C1 5.84268 1.39797 5.44473 1.88889 5.44473Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <span class="promo-discount-percent"><?php echo esc_html($discount_percentage); ?>%</span>
                <span class="promo-discount-text"><?php echo __('Discount', 'bafg'); ?></span>
            </div>
            <div class="promo-description">
                <p><?php echo esc_html($description); ?></p>
                <div class="dicount-timer">
                    <div class="countdown">
                        <?php echo $countdown_html; ?>
                    </div>
                    <a class="discount-btn" href="<?php echo esc_url($button_url) . '?ending=' . urlencode($end_time); ?>&utm_source=banner_instantio&utm_medium=plugin_banner&utm_campaign=flash60" target="_blank" class="tf-btn tf-btn-primary">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.00006 20.9989H19.0001M11.5621 3.26487C11.6052 3.18648 11.6686 3.12111 11.7457 3.07558C11.8227 3.03005 11.9106 3.00603 12.0001 3.00603C12.0895 3.00603 12.1774 3.03005 12.2544 3.07558C12.3315 3.12111 12.3949 3.18648 12.4381 3.26487L15.3901 8.86887C15.4605 8.99863 15.5587 9.1112 15.6778 9.19849C15.7968 9.28578 15.9337 9.34562 16.0787 9.37373C16.2236 9.40184 16.3729 9.39751 16.516 9.36105C16.659 9.32459 16.7923 9.25691 16.9061 9.16287L21.1831 5.49887C21.2652 5.43209 21.3663 5.39309 21.472 5.38747C21.5777 5.38186 21.6824 5.40992 21.7712 5.46762C21.8599 5.52532 21.928 5.60968 21.9657 5.70856C22.0034 5.80744 22.0088 5.91574 21.9811 6.01787L19.1471 16.2639C19.0892 16.4735 18.9646 16.6586 18.7921 16.7911C18.6195 16.9235 18.4086 16.9961 18.1911 16.9979H5.81006C5.59239 16.9964 5.38117 16.9239 5.20845 16.7914C5.03573 16.6589 4.91095 16.4737 4.85306 16.2639L2.02006 6.01887C1.99231 5.91674 1.99768 5.80844 2.0354 5.70956C2.07312 5.61068 2.14124 5.52632 2.22996 5.46862C2.31868 5.41092 2.42342 5.38286 2.5291 5.38847C2.63478 5.39409 2.73595 5.43309 2.81806 5.49987L7.09406 9.16387C7.20786 9.25791 7.34107 9.32559 7.48412 9.36205C7.62718 9.39851 7.77653 9.40284 7.92146 9.37473C8.06639 9.34663 8.20329 9.28678 8.32235 9.19949C8.44141 9.1122 8.53966 8.99963 8.61006 8.86987L11.5621 3.26487Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <?php echo esc_html($button_text); ?>
                    </a>
                </div>
            </div>
        </div>

        <?php
        return ob_get_clean();

    }

    public function clear_helper_banner_data($campaign_id){

        $user_id = get_current_user_id();
        delete_user_meta($user_id, 'ins_fomo_first_visit_time_'.$campaign_id);

    }

    public function ins_admin_helper_footer_script( $screen ){
        ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const countdown = document.querySelector('.ins-promo-countdown');
                if (!countdown) return;

                const endTime = parseInt(countdown.getAttribute('data-end-time')) * 1000;

                function updateCountdown() {
                    const now = Date.now();
                    const diff = endTime - now;

                    if (diff <= 0) {
                        countdown.innerText = "Time’s up!";
                        const btn = document.querySelector('.discount-btn');
                        if (btn) btn.style.opacity = '0.5';
                        return;
                    }

                    const hours = Math.floor(diff / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                    countdown.innerHTML = `${hours} <span>h</span> : ${minutes} <span>m</span> : ${seconds} <span>s</span>`;
                    setTimeout(updateCountdown, 1000);
                }

                updateCountdown();
            });
        </script>
        <?php 
    }

}

new INS_Helper_Banner();
