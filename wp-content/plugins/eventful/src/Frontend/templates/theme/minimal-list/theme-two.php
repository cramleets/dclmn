<?php

/**
 * Theme Two
 *
 * This template can be overridden by copying it to yourtheme/eventful/templates/theme/minimal-list/theme-two.php
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
$thumb_border = isset($options['thumb_border']) ? $options['thumb_border'] : array();
$thumb_border_width = isset($thumb_border['all']) ? $thumb_border['all'] : '2';
$thumb_border_style = isset($thumb_border['style']) ? $thumb_border['style'] : 'solid';
$thumb_border_color = isset($thumb_border['color']) ? $thumb_border['color'] : '';
$thumb_border_radius = isset($thumb_border['border_radius']) ? $thumb_border['border_radius'] : '';
$thumb_border_css = $thumb_border_width . 'px ' . $thumb_border_style . ' ' . $thumb_border_color;

$content_alignment = isset($options['content_alignment']) ? $options['content_alignment'] : 'left';

$event_title = get_the_title($event->ID);
$thumb_id = get_post_thumbnail_id($event->ID);
if (function_exists('wc_placeholder_img_src')) {
    $placeholder_img = wc_placeholder_img_src();
} else {
    $placeholder_img = EVENTFUL_DIR_URL . 'src/Frontend/assets/img/placeholder.png';
}
$placeholder_img = apply_filters('eventful_no_thumb_placeholder', $placeholder_img);
if (empty($thumb_id) && !empty($placeholder_img)) {
    $thumb_id = attachment_url_to_postid($placeholder_img);
}
if ($layout === 'slider') {
    $image_sizes = isset($options['slider_thumbnail_size']) ? $options['slider_thumbnail_size'] : 'full';
    $event_image_width  = isset($options['slider_thumbnail_custom_size']['top']) ? $options['slider_thumbnail_custom_size']['top'] : '';
    $event_image_height = isset($options['slider_thumbnail_custom_size']['right']) ? $options['slider_thumbnail_custom_size']['right'] : '';
    $event_image_crop   = isset($options['slider_thumbnail_custom_size']['style']) ? $options['slider_thumbnail_custom_size']['style'] : '';
} else {
    $image_sizes = isset($options['thumbnail_size']) ? $options['thumbnail_size'] : 'custom';
    $event_image_width  = isset($options['thumbnail_custom_size']['top']) ? $options['thumbnail_custom_size']['top'] : '';
    $event_image_height = isset($options['thumbnail_custom_size']['right']) ? $options['thumbnail_custom_size']['right'] : '';
    $event_image_crop   = isset($options['thumbnail_custom_size']['style']) ? $options['thumbnail_custom_size']['style'] : '';
}
$thumbnail_load_2x = isset($options['thumbnail_load_2x']) ? $options['thumbnail_load_2x'] : '';
$image_resize_2x_url       = '';
$hard_crop = 'Hard-crop' === $event_image_crop ? true : false;
if (!empty($thumb_id)) {
    $thumb_full_src    = wp_get_attachment_image_src($thumb_id, 'full');
    $thumb_full_src    = is_array($thumb_full_src) ? $thumb_full_src : array('', '', '');
    $image_src         = wp_get_attachment_image_src($thumb_id, $image_sizes);
    $image_src         = is_array($image_src) ? $image_src : array('', '', '');

    if (('custom' === $image_sizes) && (!empty($event_image_width) && $thumb_full_src[1] >= $event_image_width) && (!empty($event_image_height) && $thumb_full_src[2] >= $event_image_height)) {

        $image = EventfulFunctions::eventful_resize($thumb_full_src[0], $event_image_width, $event_image_height, $hard_crop);
        if ($thumbnail_load_2x && ($thumb_full_src[1] >= ($event_image_width * 2)) && $thumb_full_src[2] >= ($event_image_height * 2)) {
            $image_resize_2x_url = EventfulFunctions::eventful_resize($thumb_full_src[0], $event_image_width * 2, $event_image_height * 2, $hard_crop);
        } elseif ($thumbnail_load_2x && (($event_image_width * 2) === $thumb_full_src[1]) && ($event_image_height * 2) === $thumb_full_src[2]) {
            $image_resize_2x_url = $thumb_full_src[0];
        }
        $image_width  = $event_image_width;
        $image_height = $event_image_height;
    } else {
        $image        = !empty($image_src[0]) ? $image_src[0] : $placeholder_img;
        $image_width  = !empty($image_src[1]) ? $image_src[1] : 600;
        $image_height = !empty($image_src[2]) ? $image_src[2] : 450;
    }
} else {
    $image        = $placeholder_img;
    $image_width  = $event_image_width;
    $image_height = $event_image_height;
}
$eventful_image_attr = array(
    'src'        => $image,
    '2x_src'     => $image_resize_2x_url,
    'width'      => $image_width,
    'height'     => $image_height,
);

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

echo '<div style="--featured: ' . esc_attr($featured) . '; --non_featured: ' . esc_attr($non_featured) . ';  --content_alignment: ' . esc_attr($content_alignment) . '; --thumb_border: ' . esc_attr($thumb_border_css) . '; --thumb_border_radius: ' . esc_attr($thumb_border_radius) . 'px;" class="' . esc_attr($item_wrapper_class . ' ' . $theme_style_minimal_list . ' content_' . $content_alignment . ' ' . $list_content_postion) . '" data-id="' . esc_attr($event->ID) . '">';
if ($event_thumb_left_meta === 'taxonomy') {
    ob_start();
    EventfulFunctions::eventful_thumb_meta($event, $event_thumb_left_meta, $event_thumb_left_meta_position, $event_thumb_meta_taxonomy_left);
    echo wp_kses_post(apply_filters('eventful_thumb_' . $event_thumb_left_meta . '', ob_get_clean()));
} elseif ($event_thumb_left_meta === 'date') {
    ob_start();
    EventfulFunctions::eventful_thumb_meta($event, $event_thumb_left_meta, $event_thumb_left_meta_position);
    echo wp_kses_post(apply_filters('eventful_thumb_' . $event_thumb_left_meta . '', ob_get_clean()));
} elseif ($event_thumb_left_meta === 'venue') {
    ob_start();
    EventfulFunctions::eventful_thumb_meta($event, $event_thumb_left_meta, $event_thumb_left_meta_position);
    echo wp_kses_post(apply_filters('eventful_thumb_' . $event_thumb_left_meta . '', ob_get_clean()));
} elseif ($event_thumb_left_meta === 'organizer') {
    ob_start();
    EventfulFunctions::eventful_thumb_meta($event, $event_thumb_left_meta, $event_thumb_left_meta_position);
    echo wp_kses_post(apply_filters('eventful_thumb_' . $event_thumb_left_meta . '', ob_get_clean()));
} elseif ($event_thumb_left_meta === 'social_media') {
    ob_start();
    EventfulFunctions::eventful_thumb_meta($event, $event_thumb_left_meta, $event_thumb_left_meta_position);
    echo wp_kses_post(apply_filters('eventful_thumb_' . $event_thumb_left_meta . '', ob_get_clean()));
}
echo '<div class="event__image">
<a target="_self" ' . wp_kses_post($link_attr) . '><img src="' . esc_url($eventful_image_attr['src']) . '" alt="' . wp_kses_post($event_title) . '" width="' . esc_attr($eventful_image_attr['width']) . '" height="' . esc_attr($eventful_image_attr['height']) . '" /></a>';
echo '</div>';
echo '<div class="event__wrapper">';
echo '<div class="event__meta">';
ob_start();
EventfulFunctions::category($event);
echo wp_kses_post(apply_filters('eventful_thumb_after_taxonomy', ob_get_clean()));
ob_start();
EventfulFunctions::date($event);
echo wp_kses_post(apply_filters('eventful_thumb_after_date', ob_get_clean()));
echo '</div>';
echo '<div class="event__title"><h2><a ' . wp_kses_post($link_attr) . '>' . wp_kses_post($event_title) . '</a></h2></div>';
if (!empty($venue_details)) {
    if (!empty($google_map_link)) {
        $venue_details .= '<a href="' . esc_url($google_map_link) . '" target="_blank" rel="nofollow noopener">' . esc_html__(' + Google Map', 'eventful') . '</a>';
    }
    echo '<div class="event__venue"><i class="icofont-google-map"></i><span class="venue_content">' . wp_kses_post($venue_details) . '</span></div>';
}
echo '</div></div>';
