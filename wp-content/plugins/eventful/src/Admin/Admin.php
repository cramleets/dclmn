<?php

/**
 * The admin-facing functionality of the plugin.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package eventful
 * @subpackage eventful/Admin
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\Eventful\Admin;

use ThemeAtelier\Eventful\Admin\DBUpdates;
use ThemeAtelier\Eventful\Admin\Eventful_Duplicator;
use ThemeAtelier\Eventful\Admin\Helpers\ReviewNotice;
use ThemeAtelier\Eventful\Admin\HelpPage\Help;
use ThemeAtelier\Eventful\Admin\Preview\Preview;
use ThemeAtelier\Eventful\Admin\Views\EventfulMetaboxes;
use ThemeAtelier\Eventful\Admin\Views\EventfulSettings;
use ThemeAtelier\Eventful\Admin\Views\EventfulTools;
use ThemeAtelier\Eventful\Admin\Views\EventfulReplaceLayout;
use ThemeAtelier\Eventful\Frontend\Helpers\EventfulFunctions;
use ThemeAtelier\Eventful\Admin\Helpers\ThemeAtelier_Offer_Banner;

class Admin
{
    protected $suffix;
    private $plugin_name;
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->suffix      = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG || defined('WP_DEBUG') && WP_DEBUG ? '' : '.min';
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        // $this->eventful_support_submenu();

        add_action('admin_menu', array($this, 'eventful_support_submenu'));
        add_action('init', array($this, 'register_eventful_post_type'));
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        add_action('after_setup_theme', array($this, 'initialize_metabox_configs'));
        new ReviewNotice();
        Help::instance();
        new DBUpdates();
        new Preview();
        new Eventful_Duplicator();
        if (! defined('THEMEATELIER_OFFER_BANNER_LOADED')) {
            define('THEMEATELIER_OFFER_BANNER_LOADED', true);
            ThemeAtelier_Offer_Banner::instance();
        }
    }

    public function initialize_metabox_configs()
    {
        EventfulMetaboxes::layout_metabox('eventful_layouts');
        EventfulMetaboxes::option_metabox('eventful_view_options');
        EventfulMetaboxes::shortcode_metabox('eventful_display_shortcode');
        EventfulMetaboxes::page_builders_metabox('eventful_page_builders_metabox');
        EventfulMetaboxes::promotional_metabox('eventful_promotional_metabox');
        EventfulSettings::settings('eventful_settings');
        EventfulReplaceLayout::settings('eventful_replace_layout');
        EventfulTools::settings('eventful_tools');

        $active_plugins = get_option('active_plugins');
        foreach ($active_plugins as $active_plugin) {
            $_temp = strpos($active_plugin, 'eventful.php');
            if (false != $_temp) {
                add_filter('admin_footer_text', array($this, 'eventful_admin_footer'), 1, 2);
                add_filter('plugin_action_links_' . $active_plugin, array($this, 'add_plugin_action_links'));
                add_filter('plugin_row_meta', array($this, 'after_eventful_row_meta'), 10, 4);
            }
        }
    }



    public function eventful_support_submenu()
    {
        add_submenu_page(
            'edit.php?post_type=eventful',
            esc_html__('Settings', 'eventful'),
            esc_html__('Settings', 'eventful'),
            'manage_options', // More permissive for testing
            'eventful-settings',
            array($this, 'eventful_settings')
        );
        add_submenu_page(
            'edit.php?post_type=eventful',
            esc_html__('Replace Layout', 'eventful'),
            esc_html__('Replace Layout', 'eventful'),
            'manage_options', // More permissive for testing
            'eventful-replace-layout',
            array($this, 'eventful_replace_layout')
        );
        add_submenu_page(
            'edit.php?post_type=eventful',
            esc_html__('Tools', 'eventful'),
            esc_html__('Tools', 'eventful'),
            'manage_options', // More permissive for testing
            'eventful-tools',
            array($this, 'eventful_tools')
        );

        do_action('eventful_recommended_page_menu');
    }

    public function eventful_settings() {}
    public function eventful_replace_layout() {}
    public function eventful_tools() {}

    /**
     * Enqueue admin scripts
     */
    public function admin_scripts()
    {
        // Review notice CSS
        wp_enqueue_style('eventful-review-notice', EVENTFUL_DIR_URL . 'src/Admin/assets/css/review-notice' . $this->suffix . '.css', array(), EVENTFUL_VERSION, 'all');
        wp_enqueue_style('eventful-admin', EVENTFUL_DIR_URL . 'src/Admin/assets/css/eventful-admin' . $this->suffix . '.css', array(), EVENTFUL_VERSION, 'all');
        wp_enqueue_script('eventful-admin', EVENTFUL_DIR_URL . 'src/Admin/assets/js/eventful-admin' . $this->suffix . '.js', array(), EVENTFUL_VERSION, true);
    }


    /**
     * Eventful post type
     */
    public function register_eventful_post_type()
    {
        if (post_type_exists('eventful')) {
            return;
        }
        $capability = EventfulFunctions::eventful_dashboard_capability();
        // Set the Eventful post type labels.
        $labels = apply_filters(
            'eventful_post_type_labels',
            array(
                'name'               => esc_html__('Eventful Layout', 'eventful'),
                'singular_name'      => esc_html__('Shortcode', 'eventful'),
                'menu_name'          => esc_html__('Eventful', 'eventful'),
                'all_items'          => esc_html__('All Layouts', 'eventful'),
                'add_new'            => esc_html__('Add Layout', 'eventful'),
                'add_new_item'       => esc_html__('Add New Layout', 'eventful'),
                'new_item'           => esc_html__('Add New Layout', 'eventful'),
                'edit_item'          => esc_html__('Edit Generated Shortcode', 'eventful'),
                'view_item'          => esc_html__('View Generated Shortcode', 'eventful'),
                'name_admin_bar'     => esc_html__('Eventful Generator', 'eventful'),
                'search_items'       => esc_html__('Search Generated Shortcode', 'eventful'),
                'parent_item_colon'  => esc_html__('Parent Generated Shortcode:', 'eventful'),
                'not_found'          => esc_html__('No Shortcode found.', 'eventful'),
                'not_found_in_trash' => esc_html__('No Shortcode found in Trash.', 'eventful')
            )
        );

        $args      = apply_filters(
            'eventful_post_type_args',
            array(
                'label'           => esc_html__('Eventful Shortcode', 'eventful'),
                'description'     => esc_html__('Eventful Shortcode', 'eventful'),
                'public'          => false,
                'show_ui'         => true,
                'show_in_menu'    => true,
                'menu_icon'       => 'dashicons-calendar',
                'hierarchical'    => false,
                'query_var'       => false,
                'menu_position'   => 7,
                'supports'        => array('title'),
                'capabilities'    => array(
                    'publish_posts'       => $capability,
                    'edit_posts'          => $capability,
                    'edit_others_posts'   => $capability,
                    'delete_posts'        => $capability,
                    'delete_others_posts' => $capability,
                    'read_private_posts'  => $capability,
                    'edit_post'           => $capability,
                    'delete_post'         => $capability,
                    'read_post'           => $capability,
                ),
                'capability_type' => 'post',
                // 'rewrite'         => true,
                'labels'          => $labels,
            )
        );

        register_post_type('eventful', $args);
    }

    /**
     * Add plugin row action link.
     *
     * @since 2.0
     *
     * @param array  $plugin_action .
     * @param string $file .
     *
     * @return array
     */
    public function add_plugin_action_links($links)
    {
        $new_links = array(
            sprintf('<a href="%s">%s</a>', admin_url('post-new.php?post_type=eventful'), esc_html__('Add New', 'eventful')),
            sprintf('<a target="_blank" href="https://wordpress.org/support/plugin/eventful/#new-topic-0">' . esc_html__('Support', 'eventful') . '</a>'),
            // sprintf('<a style="font-weight: bold;color:#263ad0" target="_blank" href="https://wpeventful.com/pricing/?utm_source=eventful_plugin&utm_medium=action_link&utm_campaign=regular">' . esc_html__('Go Pro', 'eventful') . '</a>'),
        );
        $links[] = sprintf('<a style="font-weight: bold;color:#35b747" target="_blank" href="https://wpeventful.com/pricing/?utm_source=eventful_plugin&utm_medium=action_link&utm_campaign=regular">%s</a>', esc_html__('Go Pro!', 'eventful'));
        return array_merge($new_links, $links);
    }
    /**
     * Add plugin row meta link.
     *
     * @since 2.0
     *
     * @param array  $plugin_meta .
     * @param string $file .
     *
     * @return array
     */
    public function after_eventful_row_meta($plugin_meta, $file)
    {

        if (EVENTFUL_BASENAME === $file) {
            $plugin_meta[] = '<a href="' . EVENTFUL_DEMO_URL . 'lite-version-demo/" target="_blank">' . __('Live Demo', 'eventful') . '</a>';
        }

        return $plugin_meta;
    }

    /**
     * Review Text.
     *
     * @param string $text text.
     *
     * @return string
     */
    public function eventful_admin_footer($text)
    {

        $screen = get_current_screen();
        if ('eventful' === $screen->post_type || 'eventful_page_eventful-tools' === $screen->id || 'eventful_page_eventful-settings' === $screen->id) {
            $text = sprintf(
                /* translators: 1: start strong tag, 2: close strong tag. 3: start link 4: close link */
                __('<i>Enjoying %1$sEventful?%2$s Please rate us %3$sWordPress.org%4$s. Your positive feedback will help us grow more. Thank you! 😊</i>', 'eventful'),
                '<strong>',
                '</strong>',
                '<span class="greet-footer-text-star">★★★★★</span> <a href="https://wordpress.org/support/plugin/eventful/reviews/#new-post" target="_blank">',
                '</a>'
            );
        }

        return $text;
    }
}
