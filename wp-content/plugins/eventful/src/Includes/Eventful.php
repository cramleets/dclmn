<?php

namespace ThemeAtelier\Eventful\Includes;

use ThemeAtelier\Eventful\Admin\GutenbergBlock\Gutenberg_Block_Init;
use ThemeAtelier\Eventful\Includes\Loader;
use ThemeAtelier\Eventful\Admin\Admin;
use ThemeAtelier\Eventful\Frontend\Frontend;
use ThemeAtelier\Eventful\Frontend\Helpers\EventfulFunctions;
use ThemeAtelier\Eventful\Admin\Eventful_Element_Shortcode_Block;
use ThemeAtelier\Eventful\Includes\Eventful_Replace_Layout;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Eventful
 * @subpackage Eventful/includes
 * @author     ThemeAtelier <themeatelierbd@gmail.com>
 */
class Eventful
{
    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name = EVENTFUL_BASENAME;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;
    /**
     * Main Loader.
     *
     * The loader that's responsible for maintaining and registering all hooks that empowers
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var object
     */
    protected $loader;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        new Admin(EVENTFUL_BASENAME, EVENTFUL_VERSION);
        new Frontend();
        new Eventful_Replace_Layout();
        $this->load_dependencies();
        $this->init_filters();
        $this->init_actions();
    }

    /**
     * Initialize WordPress filter hooks
     *
     * @return void
     */
    public function init_filters()
    {
        // Gutenberg Block.
        if (version_compare($GLOBALS['wp_version'], '5.3', '>=')) {
            new Gutenberg_Block_Init();
        }

        // Elementor shortcode block.
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        if ((is_plugin_active('elementor/elementor.php') || is_plugin_active_for_network('elementor/elementor.php'))) {
            new Eventful_Element_Shortcode_Block();
        }
    }

    public function eventful_load_textdomain()
    {
        load_textdomain('eventful', WP_LANG_DIR . '/eventful/languages/eventful' . apply_filters('plugin_locale', get_locale(), 'eventful') . '.mo');
        load_plugin_textdomain('eventful', false, EVENTFUL_DIR_NAME . '/languages');
    }

    public function eventful_set_redirect_transient( $plugin )
    {
        if ( EVENTFUL_BASENAME === $plugin ) {
            set_transient( 'eventful_activation_redirect', true, 30 );
        }
    }

    public function eventful_redirect_on_admin_init()
    {
        if ( get_transient( 'eventful_activation_redirect' ) ) {
            delete_transient( 'eventful_activation_redirect' );
            if ( ! is_network_admin() && ! isset( $_GET['activate-multi'] ) ) {
                wp_safe_redirect( admin_url( 'edit.php?post_type=eventful' ) );
                exit;
            }
        }
    }

    public function init()
    {
        if (!class_exists('Tribe__Events__Main')) {
            add_action('admin_notices', [$this, 'tribe_event_main_fail_load']);

            return;
        }
    }
    function print_error($message)
    {
        if (!$message) {
            return;
        }
        echo '<div class="error">' . wp_kses_post($message) . '</div>';
    }

    function tribe_event_main_fail_load()
    {
        $screen = get_current_screen();
        if (isset($screen->parent_file) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id) {
            return;
        }

        $plugin = 'the-events-calendar/the-events-calendar.php';

        if (EventfulFunctions::is_the_events_calendar_installed()) {
            if (!current_user_can('activate_plugins')) {
                return;
            }

            $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin);

            $message = '<h3>' . esc_html__('Eventful plugin requires activate The Events Calendar plugin', 'eventful') . '</h3>';
            $message .= '<p>' . esc_html__('Activate The Events Calendar plugin to start using all of Eventful plugin’s features.', 'eventful') . '</p>';
            $message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $activation_url, esc_html__('Activate Now', 'eventful')) . '</p>';
        } else {
            if (!current_user_can('install_plugins')) {
                return;
            }

            $install_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=the-events-calendar'), 'install-plugin_the-events-calendar');

            $message = '<h3>' . esc_html__('Eventful plugin requires installing The Events Calendar plugin', 'eventful') . '</h3>';
            $message .= '<p>' . esc_html__('Install and activate The Events Calendar plugin to start using all of Eventful plugin’s features.', 'eventful') . '</p>';
            $message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $install_url, esc_html__('Install Now', 'eventful')) . '</p>';
        }


        $this->print_error($message);
    }

    /**
     * Define constant if not already set
     *
     * @since 2.2.0
     *
     * @param string      $name Define constant.
     * @param string|bool $value Define constant.
     */
    public function define($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        }
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Eventful_Loader. Orchestrates the hooks of the plugin.
     * - Eventful_i18n. Defines internationalization functionality.
     * - Eventful_Admin. Defines all hooks for the admin area.
     * - Eventful_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        $this->loader = new Loader();
    }

    /**
     * Add eventful admin columns.
     *
     * @since 2.0.0
     * @return statement
     */
    public function filter_eventful_admin_column()
    {

        $admin_columns['cb']         = '<input type="checkbox" />';
        $admin_columns['title']      = esc_html__('Title', 'eventful');
        $admin_columns['shortcode']  = esc_html__('Shortcode', 'eventful');
        $admin_columns['eventful_layout'] = esc_html__('Layout', 'eventful');
        $admin_columns['date']       = esc_html__('Date', 'eventful');

        return $admin_columns;
    }

    /**
     * Display admin columns for the eventfuls.
     *
     * @param mix    $column The columns.
     * @param string $post_id The post ID.
     * @return void
     */
    public function display_eventful_admin_fields($column, $post_id)
    {
        $eventful_layouts     = get_post_meta($post_id, 'eventful_layouts', true);
        $eventfuls_types = isset($eventful_layouts['eventful_layout_preset']) ? $eventful_layouts['eventful_layout_preset'] : '';
        switch ($column) {
            case 'shortcode':
                $column_field = '<input  class="eventful_input" style="width: 230px;padding: 4px 8px;cursor: pointer;" type="text" onClick="this.select();" readonly="readonly" value="[eventful id=&quot;' . esc_attr($post_id) . '&quot;]"/> <div class="eventful-after-copy-text"><i class="icofont-check-circled"></i> ' . esc_html('Shortcode Copied to Clipboard!', 'eventful') . ' </div>';

                $allowed_tags = array(
                    'input' => array(
                        'class' => true,
                        'style' => true,
                        'type' => true,
                        'onclick' => true,
                        'readonly' => true,
                        'value' => true,
                    ),
                    'div' => array(
                        'class' => true,
                    ),
                    'i' => array(
                        'class' => true,
                    ),
                );

                // Output with KSES sanitization
                echo wp_kses($column_field, $allowed_tags);
                break;
            case 'eventful_layout':
                $layout = ucwords(str_replace('_layout', ' ', $eventfuls_types));
                echo esc_html($layout);
                break;
        } // end switch.
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }


    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

    private function init_actions()
    {
        add_action('plugins_loaded', [$this, 'eventful_load_textdomain']);
        add_action('activated_plugin', [$this, 'eventful_set_redirect_transient']);
        add_action('admin_init', [$this, 'eventful_redirect_on_admin_init']);
        add_action('plugins_loaded', [$this, 'init']);

        add_filter('manage_eventful_posts_columns', [$this, 'filter_eventful_admin_column']);
        add_action('manage_eventful_posts_custom_column', [$this, 'display_eventful_admin_fields'], 10, 2);

        // Import Export.
        $import_export = new Import_Export($this->get_plugin_name(), $this->get_version());

        add_action('wp_ajax_eventful_export_shortcodes', array($import_export, 'export_shortcodes'));
        add_action('wp_ajax_eventful_import_shortcodes', array($import_export, 'import_shortcodes'));
    }
}
