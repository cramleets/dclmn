<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package eventful
 * @subpackage eventful/src/Helpers
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\Eventful\Admin\Helpers;

/**
 * The Helpers class to manage all public facing stuffs.
 *
 * @since 1.0.0
 */
class ReviewNotice
{
    public function __construct()
    {
        add_action('admin_notices', [$this, 'display_admin_notice']);
        add_action('wp_ajax_eventful-never-show-review-notice', [$this, 'dismiss_review_notice']);
    }
    public function display_admin_notice()
    {
        // Show only to Admins.
        if (! current_user_can('manage_options')) {
            return;
        }

        // Variable default value.
        $review = get_option('eventful_review_notice_dismiss');
        $time   = time();
        $load   = false;

        if (! $review) {
            $review = array(
                'time'      => $time,
                'dismissed' => false,
            );
            add_option('eventful_review_notice_dismiss', $review);
        } else {
            // Check if it has been dismissed or not.
            if ((isset($review['dismissed']) && ! $review['dismissed']) && (isset($review['time']) && (($review['time'] + (DAY_IN_SECONDS * 3)) <= $time))) {
                $load = true;
            }
        }

        // If we cannot load, return early.
        if (! $load) {
            return;
        }
?>
        <div id="eventful-review-notice" class="eventful-review-notice">
            <div class="eventful-plugin-icon">
                <img src="<?php echo esc_url(EVENTFUL_DIR_URL . 'src/Admin/HelpPage/assets/images/eventful.png'); ?>" alt="<?php echo esc_html__('Eventful', 'eventful') ?>">
            </div>
            <div class="eventful-notice-text">
                <h3><?php echo esc_html__('Enjoying', 'eventful') ?> <strong><?php echo esc_html__('Eventful', 'eventful') ?></strong>?</h3>
                <p><?php echo esc_html__('We hope you had a wonderful experience using', 'eventful') ?> <strong><?php echo esc_html__('Eventful', 'eventful') ?></strong>. <?php echo esc_html__('Please take a moment to leave a review on', 'eventful') ?> <a href="https://wordpress.org/support/plugin/eventful/reviews/?filter=5#new-post" target="_blank"><strong><?php echo esc_html__('WordPress.org', 'eventful') ?></strong></a>. <?php echo esc_html__('Your positive review will help us improve. Thank you!', 'eventful') ?> 😊</p>

                <p class="eventful-review-actions">
                    <a href="https://wordpress.org/support/plugin/eventful/reviews/?filter=5#new-post" target="_blank" class="button button-primary notice-dismissed rate-eventful"><?php echo esc_html__('Ok, you deserve', 'eventful') ?> ★★★★★</a>
                    <a href="#" class="notice-dismissed remind-me-later"><span class="dashicons dashicons-clock"></span><?php echo esc_html__('Nope, maybe later', 'eventful') ?>
                    </a>
                    <a href="#" class="notice-dismissed never-show-again"><span class="dashicons dashicons-dismiss"></span><?php echo esc_html__('Never show again', 'eventful') ?></a>
                </p>
            </div>
        </div>

        <script type='text/javascript'>
            jQuery(document).ready(function($) {
                $(document).on('click', '#eventful-review-notice.eventful-review-notice .notice-dismissed', function(event) {
                    if ($(this).hasClass('rate-eventful')) {
                        var notice_dismissed_value = "1";
                    }
                    if ($(this).hasClass('remind-me-later')) {
                        var notice_dismissed_value = "2";
                        event.preventDefault();
                    }
                    if ($(this).hasClass('never-show-again')) {
                        var notice_dismissed_value = "3";
                        event.preventDefault();
                    }

                    $.post(ajaxurl, {
                        action: 'eventful-never-show-review-notice',
                        notice_dismissed_data: notice_dismissed_value,
                        nonce: '<?php echo esc_attr(wp_create_nonce('eventful_review_notice')); ?>'
                    });

                    $('#eventful-review-notice.eventful-review-notice').hide();
                });
            });
        </script>
<?php
    }

    /**
     * Dismiss review notice
     *
     * @since  2.0.4
     *
     * @return void
     **/
    public function dismiss_review_notice()
    {
        $post_data = wp_unslash($_POST);
        $review    = get_option('eventful_review_notice_dismiss');

        if (! isset($post_data['nonce']) || ! wp_verify_nonce(sanitize_key($post_data['nonce']), 'eventful_review_notice')) {
            return;
        }

        if (! $review) {
            $review = array();
        }
        switch (isset($post_data['notice_dismissed_data']) ? $post_data['notice_dismissed_data'] : '') {
            case '1':
                $review['time']      = time();
                $review['dismissed'] = true;
                break;
            case '2':
                $review['time']      = time();
                $review['dismissed'] = false;
                break;
            case '3':
                $review['time']      = time();
                $review['dismissed'] = true;
                break;
        }
        update_option('eventful_review_notice_dismiss', $review);
        die;
    }
}
