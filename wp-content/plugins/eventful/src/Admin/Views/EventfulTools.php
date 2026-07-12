<?php

/**
 * The main class for tools configurations.
 *
 * @package Eventful
 * @subpackage Eventful/admin/views
 */

namespace ThemeAtelier\Eventful\Admin\Views;

use ThemeAtelier\Eventful\Frontend\Helpers\EventfulFunctions;
use ThemeAtelier\Eventful\Admin\Framework\Classes\Eventful;

if (! defined('ABSPATH')) {
    die;
} // Cannot access directly.

class EventfulTools
{

    /**
     * Create a tools page.
     *
     * @return void
     */
    public static function settings($prefix)
    {
        $capability = EventfulFunctions::eventful_dashboard_capability();
        Eventful::createOptions(
            $prefix,
            array(
                'menu_title'       => esc_html__('Tools', 'eventful'),
                'menu_type'        => 'submenu', // menu, submenu, options, theme, etc.
                'menu_slug'        => 'eventful-tools',
                'theme'            => 'light',
                'show_all_options' => false,
                'show_search'      => false,
                'show_footer'      => false,
                'show_bar_menu'    => false,
                'class'            => 'eventful-settings eventful_tools_page',
                'framework_title'  => esc_html__('Tools', 'eventful'),
                'menu_capability'  => $capability,
                'show_reset_all'  => false,
                'show_reset_all'  => false,
                'show_reset_section'  => false,
                'save_defaults'  => false,
                'sticky_header'  => false,
            )
        );
        Eventful::createSection(
            $prefix,
            array(
                'title'  => __('Export', 'eventful'),
                'icon'   => 'icofont-logout',
                'fields' => array(
                    array(
                        'id'       => 'eventful_what_export',
                        'type'     => 'radio',
                        'class'    => 'eventful_what_export',
                        'title'    => esc_html__('Choose What To Export', 'eventful'),
                        'multiple' => false,
                        'options'  => array(
                            'all_shortcodes'      => esc_html__('All Eventful Views (Shortcodes)', 'eventful'),
                            'selected_shortcodes' => esc_html__('Selected Eventful Views (Shortcodes)', 'eventful'),
                        ),
                        'default'  => 'all_shortcodes',
                        'sanitize' => 'eventful_sanitize_text',
                    ),
                    array(
                        'id'          => 'eventful_post',
                        'class'       => 'eventful_post_id',
                        'type'        => 'select',
                        'title'       => ' ',
                        'options'     => 'posts',
                        'chosen'      => true,
                        'sortable'    => false,
                        'multiple'    => true,
                        'placeholder' => esc_html__('Choose eventful view(s)', 'eventful'),
                        'query_args'  => array(
                            'post_type'      => 'eventful',
                            'posts_per_page' => -1,
                        ),
                        'dependency'  => array('eventful_what_export', '==', 'selected_shortcodes', true),
                    ),
                    array(
                        'id'       => 'export',
                        'class'    => 'eventful_export',
                        'type'     => 'button_set',
                        'title'    => ' ',
                        'sanitize' => 'eventful_sanitize_text',
                        'options'  => array(
                            '' => 'Export',
                        ),
                    ),
                ),
            )
        );
        Eventful::createSection(
            $prefix,
            array(
                'title'  => esc_html__('Import', 'eventful'),
                'icon'   => 'icofont-login',
                'fields' => array(
                    array(
                        'class' => 'eventful_import',
                        'type'  => 'custom_import',
                        'title' => esc_html__('Import JSON File To Upload', 'eventful'),
                    ),
                ),
            )
        );
    }
}
