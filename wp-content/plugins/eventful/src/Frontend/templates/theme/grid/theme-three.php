<?php

/**
 * Theme Three
 *
 * This template can be overridden by copying it to yourtheme/eventful/templates/theme/theme-three.php
 *
 * @link       https://themeatelier.net/
 * @since      2.1.11
 * @version    2.1.11
 *
 * @package    eventful
 * @subpackage eventful/Frontend/templates
 */

use ThemeAtelier\Eventful\Frontend\Helpers\EventfulFunctions;

$event_content = isset($options['event_content']) ? $options['event_content'] : '';
$content_element = !empty($options['content_element']) ? $options['content_element'] : '';
$event_price_position = !empty($options['event_price_position']) ? $options['event_price_position'] : 'right_side';
$g_option = get_option('eventful_settings');
$pre_made_theme_color_scheme = isset($g_option['pre_made_theme_color_scheme']) ? $g_option['pre_made_theme_color_scheme'] : array();
$featured = isset($pre_made_theme_color_scheme['featured']) ? $pre_made_theme_color_scheme['featured'] : '#222222';
$non_featured = isset($pre_made_theme_color_scheme['non_featured']) ? $pre_made_theme_color_scheme['non_featured'] : '#222222';

$event_thumb_meta_color = isset($options['event_thumb_meta_color']) ? $options['event_thumb_meta_color'] : array();
$featured = !empty($event_thumb_meta_color['featured']) ? $event_thumb_meta_color['featured'] : $featured;
$non_featured = !empty($event_thumb_meta_color['non_featured']) ? $event_thumb_meta_color['non_featured'] : $non_featured;
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
$event_price_display = isset($options['event_price_display']) ? $options['event_price_display'] : '';
$template_content_type = isset($options['template_content_type']) ? $options['template_content_type'] : 'words';
$event_content_ellipsis        = ' [...]';
$is_page_content = false;
$is_page_content = apply_filters('eventful_strip_shortcode_in_page_content', $is_page_content);

if ('excerpt' === $template_content_type) {
    $eventful_post_content = get_the_excerpt($event);
} elseif ('full_content' === $template_content_type) {
    if ($is_page_content) {
        $event_content = apply_filters('eventful_the_content', strip_shortcodes($event->post_content));
    } else {
        $event_content = apply_filters('eventful_the_content', $event->post_content);
    }
    $eventful_post_content = $event_content;
} else {
    if ($is_page_content) {
        $event_content = apply_filters('eventful_the_content', strip_shortcodes($event->post_content));
    } else {
        $event_content = apply_filters('eventful_the_content', $event->post_content);
    }
    $_trimmed_content = EventfulFunctions::eventful_limit_text($event_content, 30, $event_content_ellipsis);
    $eventful_post_content = force_balance_tags($_trimmed_content);
}

$google_map_link = tribe_get_map_link($event->ID);
$venue_details       = tribe_get_venue_details($event->ID);
if (array_key_exists("linked_name", $venue_details)) {
    $venue_details = implode(',', $venue_details);
} else {
    $venue_details = '';
}
$event_link = get_the_permalink($event->ID);
$event_price = tribe_get_cost($event->ID, true);
$venue_id   = tribe_get_venue_id($event->ID);
$venue_url  = get_permalink($venue_id);
$venue_name = get_the_title($venue_id);

echo '<div style="--featured: ' . esc_attr($featured) . '; --non_featured: ' . esc_attr($non_featured) . ';  --content_alignment: ' . esc_attr($content_alignment) . ';" class="' . esc_attr($item_wrapper_class . ' ' . $theme_style . ' content_' . $content_alignment) . '" data-id="' . esc_attr($event->ID) . '">';
echo '<div class="event__image">
<a target="_self" href="' . esc_url($event_link) . '"><img src="' . esc_url($eventful_image_attr['src']) . '" alt="' . wp_kses_post($event_title) . '" width="' . esc_attr($eventful_image_attr['width']) . '" height="' . esc_attr($eventful_image_attr['height']) . '" /></a>';

$day = tribe_get_start_date($event->ID, false, 'd');
$month = tribe_get_start_date($event->ID, false, 'M');

echo '<div class="thumb_meta_wrapper"><span class="date_meta">
				<span class="day">' . esc_html($day) . '</span><br>
				<span class="month">' . esc_html($month) . '</span>
				</span>';
echo '<div class="event__meta">';
ob_start();
EventfulFunctions::date($event);
echo wp_kses_post(apply_filters('eventful_thumb_after_date', ob_get_clean()));
echo '<a href="' . esc_url($venue_url) . '"><i class="icofont-google-map"></i>' . esc_html($venue_name) . '</a>';
echo '</div></div>';
echo '</div>';
echo '<div class="event__wrapper">';
echo '<div class="event__title"><h2><a href="' . esc_url($event_link) . '">' . wp_kses_post($event_title) . '</a></h2></div>';

echo '<div class="event__content">' . wp_kses($eventful_post_content, EventfulFunctions::allowed_tags()) . '</div>';
if (!empty($venue_details)) {
    if (!empty($google_map_link)) {
        $venue_details .= '<a href="' . esc_url($google_map_link) . '" target="_blank" rel="nofollow noopener">' . esc_html__(' + Google Map', 'eventful') . '</a>';
    }
    echo '<div class="event__venue"><i class="icofont-google-map"></i><span class="venue_content">' . wp_kses_post($venue_details) . '</span></div>';
}

echo '<div class="event__buttons event_price_' . esc_attr($event_price_position) . '">';
if (in_array($event_price_position, ['before_read_more', 'left_side'], true) && $event_price_display) {
    echo wp_kses_post( EventfulFunctions::event_price_content($event_price, $event->ID) );
}
$read_more_label = !empty($event_read_more_button_label) ? $event_read_more_button_label : esc_html__('Read More', 'eventful');
echo '<a class="event__btn" target="_self" href="' . esc_url($event_link) . '">' . esc_html($read_more_label) . '</a>';
if (in_array($event_price_position, ['after_read_more', 'right_side'], true) && $event_price_display) {
    echo wp_kses_post( EventfulFunctions::event_price_content($event_price, $event->ID) );
}
echo '</div>';
echo '</div></div>';
