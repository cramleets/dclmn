<?php

/**
 * Elementor ShortCode Block.
 *
 * @since       2.1.13
 * @package   eventful
 * @subpackage eventful/src/Admin
 */

namespace ThemeAtelier\Eventful\Admin;

/**
 * Eventful_Element_Shortcode_Block
 */
class Eventful_Element_Shortcode_Block
{

    /**
     * Instance
     *
     * @since  2.1.13
     *
     * @access private
     * @static
     *
     * @var Eventful_Element_Shortcode_Block The single instance of the class.
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since  2.1.13
     *
     * @access public
     * @static
     *
     * @return Elementor_Test_Extension An instance of the class.
     */
    public static function instance()
    {

        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     *
     * @since  2.1.13
     *
     * @access public
     */
    public function __construct()
    {
        $this->on_plugins_loaded();
        add_action('elementor/preview/enqueue_scripts', array($this, 'eventful_block_enqueue_scripts'));
        add_action('elementor/preview/enqueue_styles', array($this, 'eventful_block_enqueue_style'));
    }

    /**
     * Register the JavaScript for the elementor block area.
     *
     * @since     2.1.13
     */
    public function eventful_block_enqueue_scripts()
    {

        /**
         * Register element editor script for backend.
         */
        wp_enqueue_script('swiper-bundle');
        wp_enqueue_script('eventful-lazy');
        wp_enqueue_script('eventful-script');
    }
    /**
     * Register the JavaScript for the elementor block area.
     *
     * @since     2.1.13
     */
    public function eventful_block_enqueue_style()
    {
        /**
         * Register element editor script for backend.
         */
        wp_enqueue_style('eventful-icofont');
        wp_enqueue_style('swiper-bundle');
        wp_enqueue_style('eventful-grid');
        wp_enqueue_style('eventful-style');
    }

    /**
     * On Plugins Loaded
     *
     * Checks if Elementor has loaded, and performs some compatibility checks.
     * If All checks pass, inits the plugin.
     *
     * Fired by `plugins_loaded` action hook.
     *
     * @since  2.1.13
     *
     * @access public
     */
    public function on_plugins_loaded()
    {
        add_action('elementor/init', array($this, 'init'));
    }

    /**
     * Initialize the plugin
     *
     * Load the plugin only after Elementor (and other plugins) are loaded.
     * Load the files required to run the plugin.
     *
     * Fired by `plugins_loaded` action hook.
     *
     * @since  2.1.13
     *
     * @access public
     */
    public function init()
    {
        // Add Plugin actions.
        add_action('elementor/widgets/register', array($this, 'init_widgets'));
    }

    /**
     * Init Widgets
     *
     * Include widgets files and register them
     *
     * @since  2.1.13
     *
     * @access public
     */
    public function init_widgets()
    {
        // Register widget.
        \Elementor\Plugin::instance()->widgets_manager->register(new ElementBlock\Eventful_Shortcode_Widget());
    }
}

Eventful_Element_Shortcode_Block::instance();
