<?php

/**
 * Theme One
 *
 * This template can be overridden by copying it to yourtheme/eventful/templates/theme/minimal-list/theme-one.php
 *
 * @link       https://themeatelier.net/
 * @since      2.1.11
 * @version    2.1.11
 *
 * @package    eventful
 * @subpackage eventful/Frontend/templates
 */

use ThemeAtelier\Eventful\Frontend\Helpers\EventfulFunctions;

$list_content_postion = !empty($options['list_template']) ? $options['list_template'] : 'left_thumb';
$event_content = isset($options['event_content']) ? $options['event_content'] : '';
$event_thumb_left_meta = !empty($options['event_thumb_left_meta']) ? $options['event_thumb_left_meta'] : 'date';
$event_thumb_right_meta = !empty($options['event_thumb_right_meta']) ? $options['event_thumb_right_meta'] : 'taxonomy';
$content_element = !empty($options['content_element']) ? $options['content_element'] : '';
$event_price_position = !empty($options['event_price_position']) ? $options['event_price_position'] : 'right_side';
$event_thumb_left_meta_position = !empty($options['event_thumb_left_meta_position']) ? $options['event_thumb_left_meta_position'] : 'top_left';
$event_thumb_right_meta_position = !empty($options['event_thumb_right_meta_position']) ? $options['event_thumb_right_meta_position'] : 'top_right';
$event_thumb_meta_taxonomy_left = isset($options['event_thumb_meta_taxonomy_left']) ? $options['event_thumb_meta_taxonomy_left'] : '';
$event_thumb_meta_taxonomy_right = isset($options['event_thumb_meta_taxonomy_right']) ? $options['event_thumb_meta_taxonomy_right'] : '';
$g_option = get_option('eventful_settings');
$pre_made_theme_color_scheme = isset($g_option['pre_made_theme_color_scheme']) ? $g_option['pre_made_theme_color_scheme'] : array();
$featured = isset($pre_made_theme_color_scheme['featured']) ? $pre_made_theme_color_scheme['featured'] : '#222222';
$non_featured = isset($pre_made_theme_color_scheme['non_featured']) ? $pre_made_theme_color_scheme['non_featured'] : '#222222';

$event_thumb_meta_color = isset($options['event_thumb_meta_color']) ? $options['event_thumb_meta_color'] : array();
$featured = !empty($event_thumb_meta_color['featured']) ? $event_thumb_meta_color['featured'] : $featured;
$non_featured = !empty($event_thumb_meta_color['non_featured']) ? $event_thumb_meta_color['non_featured'] : $non_featured;
$content_alignment = isset($options['content_alignment']) ? $options['content_alignment'] : 'left';
$event_title = get_the_title($event->ID);
$event_content = get_the_excerpt($event);
$google_map_link = tribe_get_map_link($event->ID);
$venue_details       = tribe_get_venue_details($event->ID);
$venue_details = implode(',', $venue_details);
$event_link = get_the_permalink($event->ID);
$event_price = tribe_get_cost($event->ID, true);

$eventful_page_link_type = isset($options['eventful_page_link_type']) ? $options['eventful_page_link_type'] : 'single_page';
if ($eventful_page_link_type === 'popup') {
    $link_attr = 'class="popup-modal"';
} elseif ($eventful_page_link_type === 'none') {
    $link_attr = '';
} else {
    $link_attr = 'href="' . esc_url($event_link) . '"';
}

echo '<div style="--featured: ' . esc_attr($featured) . '; --non_featured: ' . esc_attr($non_featured) . ';  --content_alignment: ' . esc_attr($content_alignment) . ';" class="' . esc_attr($item_wrapper_class . ' ' . $theme_style_minimal_list . ' content_' . $content_alignment . ' ' . $list_content_postion) . '" data-id="' . esc_attr($event->ID) . '">';

$day = tribe_get_start_date($event->ID, false, 'd');
$month = tribe_get_start_date($event->ID, false, 'M');
$weekday = tribe_get_start_date($event->ID, false, 'l');
$end_day = tribe_get_end_date($event->ID, false, 'd');
$end_month = tribe_get_end_date($event->ID, false, 'M');
$end_year = tribe_get_end_date($event->ID, false, 'Y');
echo '<div class="theme_three_meta_wrapper"><span class="meta_wrapper">
        <span class="day">' . esc_html($day) . '</span>
        <span class="weekday_wrap">
        <span class="weekday">' . esc_html($weekday) . '</span>
        <span class="month">' . esc_html($month) . ' - ' . esc_html($end_day . ' ' . $end_month . ' ' . $end_year) . '</span>
        </span>
    </span>';
ob_start();
EventfulFunctions::date($event);
echo wp_kses_post(apply_filters('eventful_thumb_after_date', ob_get_clean()));

echo '</div><div class="event__wrapper">';
echo '<div class="event__title"><h2><a ' . wp_kses_post($link_attr) . '>' . wp_kses_post($event_title) . '</a></h2></div>';
if (!empty($venue_details)) {
    if (!empty($google_map_link)) {
        $venue_details .= '<a href="' . esc_url($google_map_link) . '" target="_blank" rel="nofollow noopener">' . esc_html__(' + Google Map', 'eventful') . '</a>';
    }
    echo '<div class="event__venue"><i class="icofont-google-map"></i><span class="venue_content">' . wp_kses_post($venue_details) . '</span></div>';
}
echo '</div></div>';
