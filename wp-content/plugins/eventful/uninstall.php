<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package eventful-pro
 * @subpackage eventful-pro/src/Admin/Views/Advance
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

// If uninstall not called from WordPress, then exit.
if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/**
 * Delete plugin data function.
 *
 * @return void
 */
function eventful_delete_plugin_data()
{

	// Delete offer banner related option keys.

    // Delete plugin option settings.
    $options = [
        'eventful_settings',
        'eventful_version',
        'eventful_db_version',
        'eventful_first_version',
        'eventful_activation_date',
        'ta_eventful_settings',
        'themeatelier_offer_banner_dismissed_new_year_2026',
    ];

    foreach ($options as $option_name) {
        delete_option($option_name);       // Delete regular option.
        delete_site_option($option_name); // Delete multisite option.
    }

    $eventful_posts = get_posts([
        'post_type'      => 'eventful',
        'numberposts'    => -1,
        'post_status'    => 'any,trash,auto-draft',
        'fields'         => 'ids',
    ]);
    $meta_keys = [
        'eventful_layouts',
        'eventful_view_options',
        'eventful_display_shortcode',
    ];
    foreach ($eventful_posts as $post_id) {
        // Delete specified meta keys
        foreach ($meta_keys as $meta_key) {
            delete_post_meta($post_id, $meta_key);
        }
        wp_delete_post($post_id, true);
    }
}

// Load WPTP file.
require plugin_dir_path(__FILE__) . '/eventful.php';
$eventful_g_option = get_option('eventful_settings');
$eventful_data_delete     = isset($eventful_g_option['clean_up_data']) ? $eventful_g_option['clean_up_data'] : false;

if ($eventful_data_delete) {
    eventful_delete_plugin_data();
}
