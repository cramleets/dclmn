<?php

namespace ThemeAtelier\Eventful\Admin\HelpPage;

if (! defined('ABSPATH')) {
    exit;
}  // if direct access.

/**
 * The help class for the Testimonial Free
 */
class Help
{

    /**
     * Single instance of the class
     *
     * @var null
     */
    protected static $_instance = null;

    /**
     * Plugins Path variable.
     *
     * @var array
     */
    protected static $plugins = array(
        'greet-bubble'              => 'greet-bubble.php',
        'domain-for-sale'           => 'domain-for-sale.php',
        'ask-faq'                   => 'ask-faq.php',
        'attentive-security'        => 'attentive-security.php',
        'better-chat-support'       => 'messenger-chat-support.php',
        'bizreview'                 => 'bizreview.php',
        'booklet-booking-system'    => 'booklet.php',
        'skype-chat'                => 'skype-chat.php',
        'chat-help'                 => 'chat-whatsapp.php',
        'chat-telegram'             => 'telegram-chat.php',
        'chat-viber'                => 'chat-viber-lite.php',
        'click-to-dial'             => 'click-to-dial.php',
        'click-to-mail'             => 'click-to-mail.php',
        'darkify'                   => 'darkify.php',
        'eventful-for-elementor'    => 'eventful-for-elementor.php',
        'postify'                   => 'postify.php',
    );


    /**
     * Welcome pages
     *
     * @var array
     */
    public $pages = array(
        'eventful',
    );

    /**
     * Not show this plugin list.
     *
     * @var array
     */
    protected static $not_show_plugin_list = array('bizreview', 'idonate', 'eventful', 'chat-viber', 'chat-telegram', 'click-to-dial', 'chat-skype', 'click-to-mail', 'ask-faq,', 'attentive-security', 'booklet-booking-system', 'postify', 'ask-faq');

    /**
     * Help page construct function.
     */
    public function __construct()
    {
        add_action('eventful_recommended_page_menu', array($this, 'help_admin_menu'), 80);

        $page   = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';
        if ('eventful' !== $page) {
            return;
        }
        add_action('admin_print_scripts', array($this, 'disable_admin_notices'));
        add_action('admin_enqueue_scripts', array($this, 'help_page_enqueue_scripts'));
    }

    /**
     * Main Help page Instance
     *
     * @static
     * @return self Main instance
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Help_page_enqueue_scripts function.
     *
     * @return void
     */
    public function help_page_enqueue_scripts()
    {
        wp_enqueue_style('eventful-help-style', plugins_url('assets/css/help-page.css', __FILE__), array(), '1.0.0');
        wp_enqueue_script('eventful-help-script', plugins_url('assets/js/help-page.js', __FILE__), array(), '1.0.0', true);
    }

    /**
     * Add admin menu.
     *
     * @return void
     */
    public function help_admin_menu()
    {
        add_submenu_page(
            'edit.php?post_type=eventful',
            __('Recommended', 'eventful'),
            __('Recommended', 'eventful'),
            'manage_options',
            'edit.php?post_type=eventful&page=eventful#recommended'
        );
        add_submenu_page(
            'edit.php?post_type=eventful',
            __('Lite vs Pro', 'eventful'),
            __('Lite vs Pro', 'eventful'),
            'manage_options',
            'edit.php?post_type=eventful&page=eventful#lite-to-pro'
        );

        add_submenu_page(
            'edit.php?post_type=eventful',
            __('Get Help', 'eventful'),
            __('Get Help', 'eventful'),
            'manage_options',
            'eventful',
            array(
                $this,
                'help_page_callback',
            )
        );
    }

    /**
     * Sprtf_plugins_info_api_help_page function.
     *
     * @return void
     */
    public function themeatelier_plugins_info_api_help_page()
    {
        $plugins_arr = get_transient('themeatelier_plugins');
        if (false === $plugins_arr) {
            $args    = (object) array(
                'author'   => 'themeatelier',
                'per_page' => '120',
                'page'     => '1',
                'fields'   => array(
                    'slug',
                    'name',
                    'version',
                    'downloaded',
                    'active_installs',
                    'last_updated',
                    'rating',
                    'num_ratings',
                    'short_description',
                    'author',
                ),
            );
            $request = array(
                'action'  => 'query_plugins',
                'timeout' => 30,
                'request' => serialize($args),
            );
            // https://codex.wordpress.org/WordPress.org_API.
            $url      = 'http://api.wordpress.org/plugins/info/1.0/';
            $response = wp_remote_post($url, array('body' => $request));

            if (! is_wp_error($response)) {

                $plugins_arr = array();
                $plugins     = unserialize($response['body']);

                if (isset($plugins->plugins) && (count($plugins->plugins) > 0)) {
                    foreach ($plugins->plugins as $pl) {
                        if (! in_array($pl->slug, self::$not_show_plugin_list, true)) {
                            $plugins_arr[] = array(
                                'slug'              => $pl->slug,
                                'name'              => $pl->name,
                                'version'           => $pl->version,
                                'downloaded'        => $pl->downloaded,
                                'active_installs'   => $pl->active_installs,
                                'last_updated'      => strtotime($pl->last_updated),
                                'rating'            => $pl->rating,
                                'num_ratings'       => $pl->num_ratings,
                                'short_description' => $pl->short_description,
                            );
                        }
                    }
                }

                set_transient('themeatelier_plugins', $plugins_arr, 24 * HOUR_IN_SECONDS);
            }
        }

        if (is_array($plugins_arr) && (count($plugins_arr) > 0)) {
            array_multisort(array_column($plugins_arr, 'active_installs'), SORT_DESC, $plugins_arr);


            foreach ($plugins_arr as $plugin) {
                $plugin_slug = $plugin['slug'];
                $image_type  = 'png';
                if (isset(self::$plugins[$plugin_slug])) {
                    $plugin_file = self::$plugins[$plugin_slug];
                } else {
                    $plugin_file = $plugin_slug . '.php';
                }

                switch ($plugin_slug) {
                    case 'postify':
                        $image_type = 'jpg';
                        break;
                    case 'darkify':
                        $image_type = 'gif?rev=3301202';
                        break;
                }

                $details_link = network_admin_url('plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin['slug'] . '&amp;TB_iframe=true&amp;width=600&amp;height=550');
?>
                <div class="plugin-card <?php echo esc_attr($plugin_slug); ?>" id="<?php echo esc_attr($plugin_slug); ?>">
                    <div class="plugin-card-top">
                        <div class="name column-name">
                            <h3>
                                <a class="thickbox" title="<?php echo esc_attr($plugin['name']); ?>"
                                    href="<?php echo esc_url($details_link); ?>">
                                    <?php echo esc_html($plugin['name']); ?>
                                    <img src="<?php echo esc_url('https://ps.w.org/' . $plugin_slug . '/assets/icon-256x256.' . $image_type); ?>"
                                        class="plugin-icon" />
                                </a>
                            </h3>
                        </div>
                        <div class="action-links">
                            <ul class="plugin-action-buttons">
                                <li>
                                    <?php
                                    if ($this->is_plugin_installed($plugin_slug, $plugin_file)) {
                                        if ($this->is_plugin_active($plugin_slug, $plugin_file)) {
                                    ?>
                                            <button type="button" class="button button-disabled" disabled="disabled">Active</button>
                                        <?php
                                        } else {
                                        ?>
                                            <a href="<?php echo esc_url($this->activate_plugin_link($plugin_slug, $plugin_file)); ?>"
                                                class="button button-primary activate-now">
                                                <?php esc_html_e('Activate', 'eventful'); ?>
                                            </a>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <a href="<?php echo esc_url($this->install_plugin_link($plugin_slug)); ?>"
                                            class="button install-now">
                                            <?php esc_html_e('Install Now', 'eventful'); ?>
                                        </a>
                                    <?php } ?>
                                </li>
                                <li>
                                    <a href="<?php echo esc_url($details_link); ?>" class="thickbox open-plugin-details-modal"
                                        aria-label="<?php echo esc_html('More information about ' . $plugin['name']); ?>"
                                        title="<?php echo esc_attr($plugin['name']); ?>">
                                        <?php esc_html_e('More Details', 'eventful'); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="desc column-description">
                            <p><?php echo esc_html(isset($plugin['short_description']) ? $plugin['short_description'] : ''); ?></p>
                            <p class="authors"> <cite>By <a href="https://themeatelier.com/">Themeatelier</a></cite></p>
                        </div>
                    </div>
                    <?php
                    echo '<div class="plugin-card-bottom">';

                    if (isset($plugin['rating'], $plugin['num_ratings'])) {
                    ?>
                        <div class="vers column-rating">
                            <?php
                            wp_star_rating(
                                array(
                                    'rating' => $plugin['rating'],
                                    'type'   => 'percent',
                                    'number' => $plugin['num_ratings'],
                                )
                            );
                            ?>
                            <span class="num-ratings">(<?php echo esc_html(number_format_i18n($plugin['num_ratings'])); ?>)</span>
                        </div>
                    <?php
                    }
                    if (isset($plugin['version'])) {
                    ?>
                        <div class="column-updated">
                            <strong><?php esc_html_e('Version:', 'eventful'); ?></strong>
                            <span><?php echo esc_html($plugin['version']); ?></span>
                        </div>
                    <?php
                    }

                    if (isset($plugin['active_installs'])) {
                    ?>
                        <div class="column-downloaded">
                            <?php echo esc_html(number_format_i18n($plugin['active_installs'])) . esc_html__('+ Active Installations', 'eventful'); ?>
                        </div>
                    <?php
                    }

                    if (isset($plugin['last_updated'])) {
                    ?>
                        <div class="column-compatibility">
                            <strong><?php esc_html_e('Last Updated:', 'eventful'); ?></strong>
                            <span><?php echo esc_html(human_time_diff($plugin['last_updated'])) . ' ' . esc_html__('ago', 'eventful'); ?></span>
                        </div>
                    <?php
                    }

                    echo '</div>';
                    ?>
                </div>
        <?php
            }
        }
    }

    /**
     * Check plugins installed function.
     *
     * @param string $plugin_slug Plugin slug.
     * @param string $plugin_file Plugin file.
     * @return boolean
     */
    public function is_plugin_installed($plugin_slug, $plugin_file)
    {
        return file_exists(WP_PLUGIN_DIR . '/' . $plugin_slug . '/' . $plugin_file);
    }

    /**
     * Check active plugin function
     *
     * @param string $plugin_slug Plugin slug.
     * @param string $plugin_file Plugin file.
     * @return boolean
     */
    public function is_plugin_active($plugin_slug, $plugin_file)
    {
        return is_plugin_active($plugin_slug . '/' . $plugin_file);
    }

    /**
     * Install plugin link.
     *
     * @param string $plugin_slug Plugin slug.
     * @return string
     */
    public function install_plugin_link($plugin_slug)
    {
        return wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=' . $plugin_slug), 'install-plugin_' . $plugin_slug);
    }

    /**
     * Active Plugin Link function
     *
     * @param string $plugin_slug Plugin slug.
     * @param string $plugin_file Plugin file.
     * @return string
     */
    public function activate_plugin_link($plugin_slug, $plugin_file)
    {
        return wp_nonce_url(admin_url('edit.php?post_type=eventful&page=eventful&action=activate&plugin=' . $plugin_slug . '/' . $plugin_file . '#recommended'), 'activate-plugin_' . $plugin_slug . '/' . $plugin_file);
    }


    /**
     * Making page as clean as possible
     */
    public function disable_admin_notices()
    {

        global $wp_filter;

        if (isset($_GET['post_type']) && isset($_GET['page']) && 'eventful' === wp_unslash($_GET['post_type']) && in_array(wp_unslash($_GET['page']), $this->pages)) { // @codingStandardsIgnoreLine

            if (isset($wp_filter['user_admin_notices'])) {
                unset($wp_filter['user_admin_notices']);
            }
            if (isset($wp_filter['admin_notices'])) {
                unset($wp_filter['admin_notices']);
            }
            if (isset($wp_filter['all_admin_notices'])) {
                unset($wp_filter['all_admin_notices']);
            }
        }
    }
    /**
     * The Eventful Help Callback.
     *
     * @return void
     */
    public function help_page_callback()
    {

        add_thickbox();

        $action   = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : '';
        $plugin   = isset($_GET['plugin']) ? sanitize_text_field(wp_unslash($_GET['plugin'])) : '';
        $_wpnonce = isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '';

        if (isset($action, $plugin) && ('activate' === $action) && wp_verify_nonce($_wpnonce, 'activate-plugin_' . $plugin)) {
            activate_plugin($plugin, '', false, true);
        }

        if (isset($action, $plugin) && ('deactivate' === $action) && wp_verify_nonce($_wpnonce, 'deactivate-plugin_' . $plugin)) {
            deactivate_plugins($plugin, '', false, true);
        }

        ?>
        <div class="eventful">
            <!-- Header section start -->
            <section class="themeatelier__help header">
                <div class="themeatelier-container">
                    <div class="header_nav">
                        <div class="header_nav_left">
                            <div class="header_nav_logo">
                                <img src="<?php echo esc_url(EVENTFUL_DIR_URL . 'src/Admin/HelpPage/assets/images/eventful-logo.svg') ?>"
                                    alt="logo">
                            </div>
                            <div class="header_nav_menu">
                                <ul>
                                    <li>
                                        <a href="<?php echo esc_url(home_url('') . '/wp-admin/edit.php?post_type=eventful&page=eventful#get-start'); ?>" data-id="get-start-tab" class="active">
                                            <i class="icofont-play-alt-2"></i>
                                            <?php echo esc_html__('Get Started', 'eventful') ?>
                                        </a>
                                    </li>
                                    <li>

                                        <a href="<?php echo esc_url(home_url('') . '/wp-admin/edit.php?post_type=eventful&page=eventful#recommended'); ?>" data-id="recommended-tab">
                                            <i class="icofont-thumbs-up"></i>
                                            <?php echo esc_html__('Recommended', 'eventful') ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo esc_url(home_url('') . '/wp-admin/edit.php?post_type=eventful&page=eventful#lite-to-pro'); ?>" data-id="lite-to-pro-tab">
                                            <i class="icofont-badge"></i>
                                            <?php echo esc_html__('Lite Vs Pro', 'eventful') ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo esc_url(home_url('') . '/wp-admin/edit.php?post_type=eventful&page=eventful#pro-plugins'); ?>" data-id="pro-plugins-tab">
                                            <i class="icofont-info-circle"></i>
                                            <?php echo esc_html__('Pro Plugins', 'eventful') ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="header_nav_right">
                            <div class="header_nav_right_menu">
                                <a target="_blank" href="https://wpeventful.com/pricing/"><?php echo esc_html__('🚀 Upgrading To Pro!', 'eventful') ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--header section end -->

            <!-- Start Page -->
            <section class="start_page tab-content active" id="get-start-tab">
                <div class="themeatelier-container">
                    <div class="start_page_wrapper">
                        <div class="start_page_nav">
                            <div class="nav_left">
                                <h2 class="section_title"><?php echo esc_html('Welcome to Eventful!', 'eventful') ?><span class="version__badge"><?php echo esc_html(EVENTFUL_VERSION) ?></span></h2>
                                <span class="section_subtitle">
                                    <?php echo esc_html__('Thank you for installing Eventful! This playlist will help you get started with the
                                    plugin. Enjoy!', 'eventful') ?>
                                </span>
                            </div>
                            <div class="nev_right">
                                <i class="icofont-youtube-play"></i>
                                <a target="_blank" href="https://www.youtube.com/@themeatelier">Themeatelier</a>
                            </div>
                        </div>
                        <div class="section_video">
                            <div class="video">
                                <iframe width="724" height="405" src="https://www.youtube.com/embed/bkQ3CtPX5hY" title="How to Display Events in WordPress (Eventful Plugin Tutorial)" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
  
                            </div>
                            <div class="section_video_play_list">
                                 <div class="play_list_item active" data-video_id="bkQ3CtPX5hY">
                                    <div class="play_list_item_img">
                                       Overview
                                    </div>
                                    <div class="play_list_item_content">
                                        <div class="title">How to Display Events in WordPress (Eventful Plugin Tutorial)</div>
                                        <span>3.44</span>
                                    </div>
                                </div>
                                 <div class="play_list_item" data-video_id="DLP-NigYld8">
                                    <div class="play_list_item_img">
                                        Carousel
                                    </div>
                                    <div class="play_list_item_content">
                                        <div class="title">How to Create an Events Carousel in WordPress | Eventful Plugin Tutorial</div>
                                        <span>5.33</span>
                                    </div>
                                </div>

                                <div class="play_list_item" data-video_id="dHd5Ca1geRA">
                                    <div class="play_list_item_img">
                                        Event Timeline
                                    </div>
                                    <div class="play_list_item_content">
                                        <div class="title">
Eventful Timeline Layout Tutorial | Create Beautiful Event Timelines</div>
                                        <span>2.41</span>
                                    </div>
                                </div>
<div class="play_list_item" data-video_id="aJ05gBjA_Kc">
                                    <div class="play_list_item_img">
                                        Event Grid
                                    </div>
                                    <div class="play_list_item_content">
                                        <div class="title">How to Create a WordPress Event Grid Layout | Eventful Plugin Tutorial</div>
                                        <span>6.09</span>
                                    </div>
                                </div>

                                <div class="play_list_item" data-video_id="VpqNl31Rt20">
                                    <div class="play_list_item_img">
                                        Minimal List
                                    </div>
                                    <div class="play_list_item_content">
                                        <div class="title">How to Create an Event Minimal List Layout in WordPress (Eventful Plugin Tutorial)</div>
                                        <span>5.42</span>
                                    </div>
                                </div>

                                  <div class="play_list_item" data-video_id="eyCKojduUmI">
                                    <div class="play_list_item_img">
                                        Global Settings
                                    </div>
                                    <div class="play_list_item_content">
                                        <div class="title">Eventful Global Settings Tutorial – Color Scheme, Custom CSS & Advanced Options</div>
                                        <span>4.06</span>
                                    </div>
                                </div>

                                                                 
                            </div>
                        </div>


                        <ul class="section_buttons">
                            <li>
                                <a class="chat_btn_primary"
                                    href="<?php echo esc_url(admin_url('/edit.php?post_type=eventful')); ?>"><?php echo esc_html__('Create Layout', 'eventful') ?></a>
                            </li>
                            <li>
                                <a target="_blank" class="chat_btn_secondary"
                                    href="https://wpeventful.com/#demo"><?php echo esc_html__('Live Demo', 'eventful') ?></a>
                            </li>
                            <li>
                                <a target="_blank" class="chat_btn_secondary arrow-btn"
                                    href="https://wpeventful.com/pricing/"><?php echo esc_html__('Upgrade To Pro', 'eventful') ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="section_quick_help">
                        <div class="quick_help_wrapper">
                            <a target="_blank" href="https://wpeventful.com/docs/" class="quick_help_item">
                                <div class="quick_help_item_icon"><i class="icofont-file-alt"></i></div>
                                <div class="quick_help_item_content">
                                    <h4 class="quick_help_item_title">
                                        <?php echo esc_html__('Documentation', 'eventful') ?>
                                    </h4>
                                    <div class="content"><?php echo esc_html__('Explore Eventful plugin capabilities in our enriched documentation.', 'eventful') ?></div>
                                </div>
                            </a>
                            <a target="_blank" href="https://wordpress.org/support/plugin/eventful/" class="quick_help_item">
                                <div class="quick_help_item_icon"><i class="icofont-support"></i></div>
                                <div class="quick_help_item_content">
                                    <h4 class="quick_help_item_title">
                                        <?php echo esc_html__('Technical Support', 'eventful') ?>
                                    </h4>
                                    <div class="content"><?php echo esc_html__('For personalized assistance, reach out to our skilled support team for prompt help.', 'eventful') ?></div>
                                </div>
                            </a>
                            <a target="_blank" href="https://www.themeatelier.net/contact/" class="quick_help_item">
                                <div class="quick_help_item_icon"><i class="icofont-users"></i></div>
                                <div class="quick_help_item_content">
                                    <h4 class="quick_help_item_title">
                                        <?php echo esc_html__('Hire Us!', 'eventful') ?>
                                    </h4>
                                    <div class="content"><?php echo esc_html__('We are available for freelance work for any WordPress, React, NextJS projects. Click to contact us.', 'eventful') ?></div>
                                </div>
                            </a>
                        </div>


            <div class="lite_vs_pro_page">
                    <div class="chat_testimonial">
                        <div class="chat_testimonial_title_section">
                            <h2 class="themeatelier-section-title"><?php echo esc_html__('Our Users Love Eventful!', 'eventful'); ?></h2>
                        </div>
                        <div class="chat_testimonial_wrap">

                            <div class="chat_testimonial_area">
                                <div class="chat_testimonial_content">
                                    <h3><a href="https://wordpress.org/support/topic/awesome-tool-for-sites-with-the-events-calendar/" target="_blank">Awesome tool for sites with The Events Calendar</a></h5>
                                    <p><?php echo wp_kses_post( __( "I am loving the layout options for The Events Calendar. I’m using this plugin for clients who want more control over their layouts but don’t want to always come through a developer to get small changes. It’s doing the job wonderfully. I am also impressed with the fast and responsive support.<br>Thanks guys, keep it up!", 'eventful' ) ); ?></p>
                                </div>
                                <div class="chat_testimonial-info">
                                    <div class="themeatelier-img">
                                        <img src="<?php echo esc_url(EVENTFUL_DIR_URL . 'src/Admin/HelpPage/assets/images/bird-house-digital.png'); ?>"
                                            alt="<?php echo esc_attr__('Bird House Digital', 'eventful'); ?>">
                                    </div>
                                    <div class="themeatelier-info">
                                        <h3><?php echo esc_html__('Bird House Digital', 'eventful'); ?></h3>
                                        <div class="themeatelier-star">
                                            <i>★★★★★</i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="chat_testimonial_area">
                                <div class="chat_testimonial_content">
                                    <h3><a href="https://wordpress.org/support/topic/invaluable-help/" target="_blank">Invaluable help</a></h5>
                                    <p><?php echo wp_kses_post( __( "After experiencing issues with the module that I couldn’t resolve, the team offered to help and take direct action on my site, and they took the time to do so. Everything is back to normal now, and I’m very happy about that.<br>Thanks again to the Themeatelier team for their follow-up!", 'eventful' ) ); ?></p>
                                </div>
                                <div class="chat_testimonial-info">
                                    <div class="themeatelier-img">
                                        <img src="<?php echo esc_url(EVENTFUL_DIR_URL . 'src/Admin/HelpPage/assets/images/user_image.jpg'); ?>"
                                            alt="<?php echo esc_attr__('Robin', 'eventful'); ?>">
                                    </div>
                                    <div class="themeatelier-info">
                                        <h3><?php echo esc_html__('Robin', 'eventful'); ?></h3>
                                        <div class="themeatelier-star">
                                            <i>★★★★★</i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="chat_testimonial_area">
                                <div class="chat_testimonial_content">
                                    <h3><a href="https://wordpress.org/support/topic/good-plugin-and-good-support-86/" target="_blank">Good plugin and good support!</a></h5>
                                    <p><?php echo esc_html__('I recently started using this plugin and ran into a few minor issues. I contacted the support team and they quickly provided a solution. Many thanks for the assistance and for the nice work with this plugin, very easy to set up!', 'eventful'); ?></p>
                                </div>
                                <div class="chat_testimonial-info">
                                    <div class="themeatelier-img">
                                        <img src="<?php echo esc_url(EVENTFUL_DIR_URL . 'src/Admin/HelpPage/assets/images/user_image.jpg'); ?>"
                                            alt="<?php echo esc_attr__('Whitelily85', 'eventful'); ?>">
                                    </div>
                                    <div class="themeatelier-info">
                                        <h3><?php echo esc_html__('Whitelily85', 'eventful'); ?></h3>
                                        <div class="themeatelier-star">
                                            <i>★★★★★</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         
                        </div>

                    </div>
</div>
                    </div>

                    
                </div>

                
            </section>

            <!-- Recommended Page -->
            <section id="recommended-tab" class="recommended_page tab-content">
                <div class="themeatelier-container">
                    <h2 class="help_page_title">Enhance your Website with our Free Robust Plugins</h2>
                    <div class="themeatelier-wp-list-table plugin-install-php">
                        <div class="recommended_plugins" id="the-list">
                            <?php
                            $this->themeatelier_plugins_info_api_help_page();
                            ?>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Lite To Pro Page -->
            <section class="themeatelier__help lite_vs_pro_page tab-content" id="lite-to-pro-tab">
                <div class="themeatelier-container">
                    <h2 class="help_page_title">Lite Vs Pro Comparison</h2>

                    <div class="lite_vs_pro_page_wrap">
                        <div class="themeatelier-features">
                            <ul>
                                <li class="themeatelier-header">
                                    <span class="themeatelier-title"><?php echo esc_html__('FEATURES', 'eventful'); ?></span>
                                    <span class="themeatelier-free"><?php echo esc_html__('Lite', 'eventful'); ?></span>
                                    <span class="themeatelier-pro">🚀<?php echo esc_html__('PRO', 'eventful'); ?></span>
                                </li>

                                <!-- ======== LAYOUTS ======== -->
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title"><?php echo esc_html__('All Free Version Features', 'eventful'); ?></span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Layout Presets (Carousel, Slider, Grid, Minimal List, Timeline, Ticker Carousel, Center Carousel, Multi-row Carousel, Masonry, List, Timeline, Table style variants)', 'eventful'); ?>
                                        <i class="themeatelier-new"><?php echo esc_html__('New', 'eventful'); ?></i>
                                    </span>
                                    <span class="themeatelier-free"><b>5</b></span>
                                    <span class="themeatelier-pro"><b>15</b></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Timeline Layout Styles', 'eventful'); ?>
                                        <i class="themeatelier-new"><?php echo esc_html__('New', 'eventful'); ?></i>
                                        <i class="themeatelier-hot"><?php echo esc_html__('Hot', 'eventful'); ?></i>
                                    </span>
                                    <span class="themeatelier-free"><b>1</b></span>
                                    <span class="themeatelier-pro"><b>5</b></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Timeline Customization (Date Badge Color, Line Thickness & Color, Event Dot Color, Month/Year Separator)', 'eventful'); ?>
                                        <i class="themeatelier-new"><?php echo esc_html__('New', 'eventful'); ?></i>
                                    </span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Pre-made Templates for Quick Setup', 'eventful'); ?>
                                        <i class="themeatelier-new"><?php echo esc_html__('New', 'eventful'); ?></i>
                                    </span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>

                                <!-- ======== FILTERING ======== -->
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Filter By Event Types (Latest, Featured, Specific)', 'eventful'); ?>
                                        <i class="themeatelier-hot"><?php echo esc_html__('Hot', 'eventful'); ?></i>
                                    </span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Events View By (Upcoming, Past, All)', 'eventful'); ?>
                                        <i class="themeatelier-hot"><?php echo esc_html__('Hot', 'eventful'); ?></i>
                                    </span>
                                    <span class="themeatelier-free"><?php echo esc_html__('Upcoming Only', 'eventful'); ?></span>
                                    <span class="themeatelier-pro"><?php echo esc_html__('All options', 'eventful'); ?></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Filter Order By (ID, Title, Event Start Date, Slug, Random, ASC, DESC, etc.)', 'eventful'); ?>
                                        <i class="themeatelier-hot"><?php echo esc_html__('Hot', 'eventful'); ?></i>
                                    </span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Hide Free Events', 'eventful'); ?>
                                        <i class="themeatelier-new"><?php echo esc_html__('New', 'eventful'); ?></i>
                                    </span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Hide Events Without Featured Images', 'eventful'); ?>
                                        <i class="themeatelier-new"><?php echo esc_html__('New', 'eventful'); ?></i>
                                    </span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title"><?php echo esc_html__('Advanced Live Search & Sort By Keyword', 'eventful'); ?></span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title"><?php echo esc_html__('Advanced Live Filter By Category & Tags', 'eventful'); ?></span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Advanced Live Filter By Venue, Organizer & Event Type (Time Based)', 'eventful'); ?>
                                        <i class="themeatelier-hot"><?php echo esc_html__('Hot', 'eventful'); ?></i>
                                    </span>
                                    <span class="themeatelier-free themeatelier-close-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Filter By Timeframe & Month', 'eventful'); ?>
                                        <i class="themeatelier-hot"><?php echo esc_html__('Hot', 'eventful'); ?></i>
                                    </span>
                                    <span class="themeatelier-free themeatelier-close-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Ajax Live Filter Type (Button, Dropdown, Radio, Checkbox)', 'eventful'); ?>
                                        <i class="themeatelier-new"><?php echo esc_html__('New', 'eventful'); ?></i>
                                        <i class="themeatelier-hot"><?php echo esc_html__('Hot', 'eventful'); ?></i>
                                    </span>
                                    <span class="themeatelier-free"><b>1</b></span>
                                    <span class="themeatelier-pro"><b>4</b></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title"><?php echo esc_html__('Filter by Event Date & Time Range', 'eventful'); ?></span>
                                    <span class="themeatelier-free themeatelier-close-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>

                                <!-- ======== DISPLAY & STYLING ======== -->
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Event Item Same Height', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free themeatelier-close-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Event Item Border, Radius, Box Shadow, Background, Inner Padding, etc.', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Event Content Position (Default, Right, Left, Overlay)', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free"><b>1</b></span>
                                    <span class="themeatelier-pro"><b>4</b></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('List Layout Image Position (Left, Right)', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free themeatelier-close-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Event Image Custom Dimensions and Retina Ready Supported', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Replace Default/Featured Event Image with First Resource (Image, Video, Audio)', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free themeatelier-close-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Enable Event Image Lazy Load', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free themeatelier-close-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Zoom In, Zoom Out, and Grayscale Modes for Event Images', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free themeatelier-close-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>

                                <!-- ======== EVENT CONTENT FIELDS ======== -->
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Event Title (HTML Tag, Character Limit, Margin)', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Event Meta (Venue, Price, Event Time, Organizer, Taxonomy, Series Archive)', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free"><b>3</b></span>
                                    <span class="themeatelier-pro"><b>6</b></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Event Meta Icon', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free themeatelier-close-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Event Meta Styles (Separator, Margin, Meta Between Margin)', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Event Content (Display Type, Margin, HTML Tags)', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Event Read More Button (Type, Label, Color, Border, Padding)', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Custom Fields as Event Content Fields', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free themeatelier-close-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>

                                <!-- ======== SOCIAL SHARE ======== -->
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Social Share (Facebook, Twitter, LinkedIn, Pinterest, WhatsApp, Email, etc.)', 'eventful'); ?>
                                        <i class="themeatelier-hot"><?php echo esc_html__('Hot', 'eventful'); ?></i>
                                    </span>
                                    <span class="themeatelier-free"><b>5</b></span>
                                    <span class="themeatelier-pro"><b>13</b></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Social Share (Icon Shape, Alignment, Margin)', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Social Share Custom Icon Color', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free themeatelier-close-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>

                                <!-- ======== PAGINATION ======== -->
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Pagination Type (Load More, Ajax Number, Infinite Scroll, No Ajax)', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free"><b>1</b></span>
                                    <span class="themeatelier-pro"><b>4</b></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Separate Mobile Pagination Type', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free themeatelier-close-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Number Type Pagination (Ajax)', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free themeatelier-close-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Pagination Settings (Color, Alignment, Items Per Page, etc.)', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>

                                <!-- ======== CAROUSEL ======== -->
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Carousel Mode (Standard, Center, Ticker, Multi-row)', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free">1</span>
                                    <span class="themeatelier-pro">4</span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Powerful Carousel Settings (AutoPlay, AutoPlay Speed, Carousel Speed, Pause on Hover, Infinite Loop, Carousel Direction, etc.)', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Carousel Transition Effect (Slide, Fade, Coverflow, Cube, Flip)', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free">1</span>
                                    <span class="themeatelier-pro">5</span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Navigation Arrows Positions', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free">3</span>
                                    <span class="themeatelier-pro">9</span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Navigation Arrows Icon Variants', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free">1</span>
                                    <span class="themeatelier-pro">7</span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title">
                                        <?php echo esc_html__('Adaptive Carousel Height, Tab & Key Navigation, Touch Swipe, Mouse Drag, Mouse Wheel', 'eventful'); ?>
                                    </span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>

                                <!-- ======== DETAIL PAGE / POPUP ======== -->
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title"><?php echo esc_html__('Detail Page Link Type (Popup, Single Page, None)', 'eventful'); ?></span>
                                    <span class="themeatelier-free"><?php echo esc_html__('Single Page, None', 'eventful'); ?></span>
                                    <span class="themeatelier-pro"><?php echo esc_html__('All', 'eventful'); ?></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title"><?php echo esc_html__('Customize Popup Type, Max Width, Max Height, Color, Background, Close Button, etc.', 'eventful'); ?></span>
                                    <span class="themeatelier-free themeatelier-close-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title"><?php echo esc_html__('Content Field Drag and Drop Sorting for the Detail Page', 'eventful'); ?></span>
                                    <span class="themeatelier-free themeatelier-close-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>

                                <!-- ======== TYPOGRAPHY & COLORS ======== -->
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title"><?php echo esc_html__('Advanced Typography for All Elements (800+ Google Fonts)', 'eventful'); ?></span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title"><?php echo esc_html__('Global Color Scheme', 'eventful'); ?></span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>

                                <!-- ======== INTEGRATIONS ======== -->
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title"><?php echo esc_html__('Shortcode', 'eventful'); ?></span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title"><?php echo esc_html__('Gutenberg Block', 'eventful'); ?></span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title"><?php echo esc_html__('Compatible With Elementor', 'eventful'); ?></span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title"><?php echo esc_html__('Multilingual Support (WPML & Polylang)', 'eventful'); ?></span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title"><?php echo esc_html__('RTL Language Support', 'eventful'); ?></span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title"><?php echo esc_html__('Multisite Compatible', 'eventful'); ?></span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>

                                <!-- ======== OTHER ======== -->
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title"><?php echo esc_html__('Events Schema SEO Support', 'eventful'); ?></span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>
                                <li class="themeatelier-body">
                                    <span class="themeatelier-title"><?php echo esc_html__('Tools (Export / Import)', 'eventful'); ?></span>
                                    <span class="themeatelier-free themeatelier-check-icon"></span>
                                    <span class="themeatelier-pro themeatelier-check-icon"></span>
                                </li>

                            </ul>
                        </div>

                        <div class="themeatelier-upgrade-to-pro">
                            <h2 class="themeatelier-section-title"><?php echo esc_html__('Upgrade To PRO & Enjoy Advanced Features!', 'eventful'); ?></h2>
                            <span class="themeatelier-section-subtitle">
                                <?php
                                // translators: %s: Number of people using Eventful (e.g., 1300+).
                                echo sprintf(esc_html__('Already, %s people are using Eventful on their websites to create beautiful event showcases, why won’t you!', 'eventful'), '<b>1400+</b>'); ?>
                            </span>
                            <div class="themeatelier-upgrade-to-pro-btn">
                                <div class="themeatelier-action-btn">
                                    <a target="_blank" href="http://wpeventful.com/pricing/" class="chat_btn_primary">
                                        <?php echo esc_html__('Upgrade to Pro Now!', 'eventful'); ?>
                                    </a>
                                    <span class="themeatelier-small-paragraph">
                                        <?php
                                        // translators: %s: Refund Policy link.
                                        echo sprintf(esc_html__('14-Day No-Questions-Asked %s', 'eventful'), '<a target="_blank" href="https://themeatelier.net/refund-policy/">' . esc_html__('Refund Policy', 'eventful') . '</a>'); ?>
                                    </span>
                                </div>
                                <a target="_blank" class="chat_btn_secondary" href="http://wpeventful.com/features/"><?php echo esc_html__('See All Features', 'eventful'); ?></a>
                                <a target="_blank" class="chat_btn_secondary" href="http://wpeventful.com/#demo"><?php echo esc_html__('Pro Live Demo', 'eventful'); ?></a>
                            </div>
                        </div>
                    </div>

                    <div class="chat_testimonial">
                        <div class="chat_testimonial_title_section">
                            <span class="chat_testimonial-subtitle"><?php echo esc_html__('NO NEED TO TAKE OUR WORD FOR IT', 'eventful'); ?></span>
                            <h2 class="themeatelier-section-title"><?php echo esc_html__('Our Users Love Eventful!', 'eventful'); ?></h2>
                        </div>
                        <div class="chat_testimonial_wrap">

                            <div class="chat_testimonial_area">
                                <div class="chat_testimonial_content">
                                    <h3><a href="https://wordpress.org/support/topic/awesome-tool-for-sites-with-the-events-calendar/" target="_blank">Awesome tool for sites with The Events Calendar</a></h5>
                                    <p><?php echo wp_kses_post( __( "I am loving the layout options for The Events Calendar. I’m using this plugin for clients who want more control over their layouts but don’t want to always come through a developer to get small changes. It’s doing the job wonderfully. I am also impressed with the fast and responsive support.<br>Thanks guys, keep it up!", 'eventful' ) ); ?></p>
                                </div>
                                <div class="chat_testimonial-info">
                                    <div class="themeatelier-img">
                                        <img src="<?php echo esc_url(EVENTFUL_DIR_URL . 'src/Admin/HelpPage/assets/images/bird-house-digital.png'); ?>"
                                            alt="<?php echo esc_attr__('Bird House Digital', 'eventful'); ?>">
                                    </div>
                                    <div class="themeatelier-info">
                                        <h3><?php echo esc_html__('Bird House Digital', 'eventful'); ?></h3>
                                        <div class="themeatelier-star">
                                            <i>★★★★★</i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="chat_testimonial_area">
                                <div class="chat_testimonial_content">
                                    <h3><a href="https://wordpress.org/support/topic/invaluable-help/" target="_blank">Invaluable help</a></h5>
                                    <p><?php echo wp_kses_post( __( "After experiencing issues with the module that I couldn’t resolve, the team offered to help and take direct action on my site, and they took the time to do so. Everything is back to normal now, and I’m very happy about that.<br>Thanks again to the Themeatelier team for their follow-up!", 'eventful' ) ); ?></p>
                                </div>
                                <div class="chat_testimonial-info">
                                    <div class="themeatelier-img">
                                        <img src="<?php echo esc_url(EVENTFUL_DIR_URL . 'src/Admin/HelpPage/assets/images/user_image.jpg'); ?>"
                                            alt="<?php echo esc_attr__('Robin', 'eventful'); ?>">
                                    </div>
                                    <div class="themeatelier-info">
                                        <h3><?php echo esc_html__('Robin', 'eventful'); ?></h3>
                                        <div class="themeatelier-star">
                                            <i>★★★★★</i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="chat_testimonial_area">
                                <div class="chat_testimonial_content">
                                    <h3><a href="https://wordpress.org/support/topic/good-plugin-and-good-support-86/" target="_blank">Good plugin and good support!</a></h5>
                                    <p><?php echo esc_html__('I recently started using this plugin and ran into a few minor issues. I contacted the support team and they quickly provided a solution. Many thanks for the assistance and for the nice work with this plugin, very easy to set up!', 'eventful'); ?></p>
                                </div>
                                <div class="chat_testimonial-info">
                                    <div class="themeatelier-img">
                                        <img src="<?php echo esc_url(EVENTFUL_DIR_URL . 'src/Admin/HelpPage/assets/images/user_image.jpg'); ?>"
                                            alt="<?php echo esc_attr__('Whitelily85', 'eventful'); ?>">
                                    </div>
                                    <div class="themeatelier-info">
                                        <h3><?php echo esc_html__('Whitelily85', 'eventful'); ?></h3>
                                        <div class="themeatelier-star">
                                            <i>★★★★★</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         
                        </div>

                    </div>
                </div>
            </section>

            <!-- About Page -->
            <section id="pro-plugins-tab" class="themeatelier__help about-page tab-content">
                <div class="themeatelier-container">
                    <h2 class="help_page_title">Upgrade your Website with our High-quality Plugins!</h2>
                    <div class="themeatelier-our-plugin-list">

                        <div class="themeatelier-our-plugin-list-wrap">
                            <a target="_blank" class="themeatelier-our-plugin-list-box" href="https://wpeventful.com/pricing/">
                                <div class="box_btn">
                                    View Details
                                    <i class="icofont-long-arrow-right"></i>
                                </div>
                                <img src="<?php echo esc_url( EVENTFUL_DIR_URL . 'src/Admin/HelpPage/assets/images/eventful.png' ); ?>" alt="Eventful - Events Showcase">
                                <h4>Eventful - Events Showcase for The "The Events Calendar</h4>
                                <p>With "Eventful," you can effortlessly create intelligent layouts for "The Events Calendar" plugin, effectively addressing and resolving compatibility issues that may arise.</p>
                            </a>

                            <a target="_blank" class="themeatelier-our-plugin-list-box" href="https://wpchathelp.com/pricing/">
                                <div class="box_btn">
                                    View Details
                                    <i class="icofont-long-arrow-right"></i>
                                </div>
                                <img src="<?php echo esc_url( EVENTFUL_DIR_URL . 'src/Admin/HelpPage/assets/images/chat-help-icon.png' ); ?>" alt="WhatsApp Chat Help">
                                <h4>WhatsApp Chat Help</h4>
                                <p>Whatsapp chat support is a WordPress plugin that allows website owners to easily add a WhatsApp chat button to their website.</p>
                            </a>

                            <a target="_blank" class="themeatelier-our-plugin-list-box" href="https://darkifywp.com/pricing/">
                                <div class="box_btn">
                                    View Details
                                    <i class="icofont-long-arrow-right"></i>
                                </div>
                                <img src="<?php echo esc_url( EVENTFUL_DIR_URL . 'src/Admin/HelpPage/assets/images/dark-sun.svg' ); ?>" alt="Darkify - WordPress Dark Mode Plugin">
                                <h4>Darkfiy - WordPress Dark Mode Plugin</h4>
                                <p>Darkify – is an extremely advanced dark mode plugin for any WordPress website. The plugin has the option to enable a dark mode switcher for the front end and also WordPress admin.</p>
                            </a>

                            <a target="_blank" class="themeatelier-our-plugin-list-box" href="https://wpdomainforsale.com/pricing/">
                                <div class="box_btn">
                                    View Details
                                    <i class="icofont-long-arrow-right"></i>
                                </div>
                                <img src="<?php echo esc_url(EVENTFUL_DIR_URL . 'src/Admin/HelpPage/assets/images/domain-for-sale-icon.png') ?>" alt="Domain For Sale">
                                <h4>Domain For Sale</h4>
                                <p>The ultimate WordPress plugin for domain sales, appraisals, auctions, and marketplace management.</p>
                            </a>

                            <a target="_blank" class="themeatelier-our-plugin-list-box" href="https://themeatelier.net/downloads/greet-bubble/">
                                <div class="box_btn">
                                    View Details
                                    <i class="icofont-long-arrow-right"></i>
                                </div>
                                <img src="<?php echo esc_url(EVENTFUL_DIR_URL . 'src/Admin/HelpPage/assets/images/greet-logo.png') ?>" alt="logo">
                                <h4>Greet Bubble - Video Bubble Plugin for WordPress</h4>
                                <p>Placing a video on websites can increase the sales of your services or products in a significant way. Greet is a professional video bubble plugin for showing a welcome video on your websites in a great and fun way.</p>
                            </a>
                            <a target="_blank" class="themeatelier-our-plugin-list-box" href="https://themeatelier.net/downloads/eventful-for-elementor/">
                                <div class="box_btn">
                                    View Details
                                    <i class="icofont-long-arrow-right"></i>
                                </div>
                                <img src="<?php echo esc_url(EVENTFUL_DIR_URL . 'src/Admin/HelpPage/assets/images/eventful-for-elementor.png') ?>" alt="logo">
                                <h4>Eventful for Elementor - Events Showcase for The "The Events Calendar"</h4>
                                <p>Easily display events from The Events Calendar plugin with Elementor widgets, offering seamless customization and powerful layout options.</p>
                            </a>
                            

                        </div>
                    </div>
                </div>
                
            </section>

            <!-- Footer Section -->
            <section class="themeatelier_footer">
                <div class="themeatelier_footer_top">
                    <p>
                        <span>Made With <i class="icofont-heart-alt"></i></span>
                        By the Team <a target="_blank" href="https://themeatelier.net/">ThemeAtelier</a>
                    </p>
                    <p>Get connected with</p>
                    <ul>
                        <li>
                            <a target="_blank" href="https://www.facebook.com/ThemeAtelier/"><i
                                    class="icofont-facebook"></i></a>
                        </li>
                        <li>
                            <a target="_blank" href="https://x.com/intent/follow?screen_name=themeatelier"><i
                                    class="icofont-x"></i></a>
                        </li>
                        <li>
                            <a target="_blank" href="https://profiles.wordpress.org/themeatelier/#content-plugins"><i
                                    class="icofont-brand-wordpress"></i></a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.youtube.com/@themeatelier"><i
                                    class="icofont-youtube-play"></i></a>
                        </li>
                    </ul>
                </div>
            </section>
        </div>
<?php
    }
}
