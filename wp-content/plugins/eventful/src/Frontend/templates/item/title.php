<?php

/**
 * Item Title
 *
 * This template can be overridden by copying it to yourtheme/eventful/templates/item/title.php
 *
 * @package    Eventful
 * @subpackage Eventful/public
 */

use ThemeAtelier\Eventful\Frontend\Helpers\EventfulFunctions;

$template_style   = isset($options['template_style']) ? $options['template_style'] : 'custom';


$event_title_setting     = EventfulFunctions::eventful_metabox_value('eventful_event_title', $sorter);
$show_event_title     = EventfulFunctions::eventful_metabox_value('show_event_title', $event_title_setting);
$eventful_page_link_type = EventfulFunctions::eventful_metabox_value('eventful_page_link_type', $options);
$eventful_link_rel       = EventfulFunctions::eventful_metabox_value('eventful_link_rel', $options);
$eventful_link_rel_text  = '1' === $eventful_link_rel ? "rel='nofollow'" : '';
$eventful_link_target      = EventfulFunctions::eventful_metabox_value('eventful_link_target', $options);

$g_option = get_option('eventful_settings');
$event_title_color  = isset($g_option['event_title_color']) ? $g_option['event_title_color'] : '';
$g_color  = isset($event_title_color['color']) ? $event_title_color['color'] : '#111111';
$g_hover_color  = isset($event_title_color['hover_color']) ? $event_title_color['hover_color'] : '#0015b5';

if ($show_event_title) {
    // Event Title Settings.
    $event_title_tag    = EventfulFunctions::eventful_metabox_value('event_title_tag', $event_title_setting, 'h2');
    $event_title_limit = EventfulFunctions::eventful_metabox_value('event_title_limit', $event_title_setting);
    if ($event_title_limit) {
        $event_title_length     = isset($event_title_setting['eventful_title_length']) ? $event_title_setting['eventful_title_length'] : '';
        $event_title_length_limit = isset($event_title_length['all']) ? $event_title_length['all'] : '20';
        $event_title_length_unit = isset($event_title_length['unit']) ? $event_title_length['unit'] : 'words';

        if ($event_title_length_limit > 0) {
            $event_title_limit_after = apply_filters('eventful_event_title_limit_after', '...');
            // Limit event title by words.
            if ('words' === $event_title_length_unit) {
                $eventful_event_title = wp_trim_words(get_the_title($event->ID), (int) $event_title_length_limit, $event_title_limit_after);
            } elseif ('characters' === $event_title_length_unit) { // Limit event title by characters.
                $eventful_event_title = wp_html_excerpt(get_the_title($event->ID), $event_title_length_limit, $event_title_limit_after);
            } else { // Limit event title by lines. It is done with custom css.
                $eventful_event_title = get_the_title($event->ID);
            }
        } else {
            $eventful_event_title = get_the_title($event->ID);
        }
    } else {
        $eventful_event_title = get_the_title($event->ID);
    }

    $allowed_html_tags = array(
        'em'     => array(),
        'strong' => array(),
        'sup'    => array(),
        'i'      => array(),
        'small'  => array(),
        'del'    => array(),
        'ins'    => array(),
        'span'   => array(
            'style' => array(),
            'class' => array(),
        ),
    );

    $event_title_margin = EventfulFunctions::eventful_metabox_value('event_title_margin', $event_title_setting);
    $event_title_margin_top = EventfulFunctions::eventful_metabox_value('top', $event_title_margin);
    $event_title_margin_right = EventfulFunctions::eventful_metabox_value('right', $event_title_margin);
    $event_title_margin_bottom = EventfulFunctions::eventful_metabox_value('bottom', $event_title_margin);
    $event_title_margin_left = EventfulFunctions::eventful_metabox_value('left', $event_title_margin);
    $event_title_margin = "{$event_title_margin_top}px {$event_title_margin_right}px {$event_title_margin_bottom}px {$event_title_margin_left}px;";

    $_event_title_typography = EventfulFunctions::eventful_metabox_value('event_title_typography', $options);
    $event_title_color = !empty($_event_title_typography['color']) ? $_event_title_typography['color'] : $g_color;
    $event_title_hover_color = !empty($_event_title_typography['hover_color']) ? $_event_title_typography['hover_color'] : $g_hover_color;
    $event_title_font_family = EventfulFunctions::eventful_metabox_value('font-family', $_event_title_typography);

    $event_title_font_weight = ! empty($_event_title_typography['font-weight']) ? $_event_title_typography['font-weight'] : '400';
    $event_title_font_style  = ! empty($_event_title_typography['font-style']) ? $_event_title_typography['font-style'] : 'normal';

    $event_title_subset = EventfulFunctions::eventful_metabox_value('subset', $_event_title_typography);
    $event_title_font_size = EventfulFunctions::eventful_metabox_value('font-size', $_event_title_typography);
    $event_title_tablet_font_size = EventfulFunctions::eventful_metabox_value('tablet-font-size', $_event_title_typography);
    $event_title_mobile_font_size = EventfulFunctions::eventful_metabox_value('mobile-font-size', $_event_title_typography);
    $event_title_line_height = EventfulFunctions::eventful_metabox_value('line-height', $_event_title_typography);
    $event_title_tablet_line_height = EventfulFunctions::eventful_metabox_value('tablet-line-height', $_event_title_typography);
    $event_title_mobile_line_height = EventfulFunctions::eventful_metabox_value('mobile-line-height', $_event_title_typography);
    $event_title_letter_spacing = EventfulFunctions::eventful_metabox_value('letter-spacing', $_event_title_typography);
    $event_title_text_align = EventfulFunctions::eventful_metabox_value('text-align', $_event_title_typography);
    $event_title_text_transform = EventfulFunctions::eventful_metabox_value('text-transform', $_event_title_typography);


    $title_typography = '';
    if ($event_title_font_family) {
        $title_typography = "--event_title_font_family: " . esc_attr($event_title_font_family) . ";
        --event_title_font_weight: " . esc_attr($event_title_font_weight) . ";
        --event_title_font_style: " . esc_attr($event_title_font_style) . ";";
    } else {
        $title_typography = '';
    }

    echo '<' . esc_attr($event_title_tag) . ' class="eventful__item--title"
style="
--event_title_margin: ' . esc_attr($event_title_margin) . ';
--event_title_color: ' . esc_attr($event_title_color) . '; 
--event_title_hover_color: ' . esc_attr($event_title_hover_color) . '; 
' . wp_kses_post($title_typography) . '
--event_title_subset: ' . esc_attr($event_title_subset) . '; 
--event_title_font_size: ' . esc_attr($event_title_font_size) . 'px; 
--event_title_tablet_font_size: ' . esc_attr($event_title_tablet_font_size) . 'px; 
--event_title_mobile_font_size: ' . esc_attr($event_title_mobile_font_size) . 'px; 
--event_title_line_height: ' . esc_attr($event_title_line_height) . 'px; 
--event_title_tablet_line_height: ' . esc_attr($event_title_tablet_line_height) . 'px; 
--event_title_mobile_line_height: ' . esc_attr($event_title_mobile_line_height) . 'px; 
--event_title_letter_spacing: ' . esc_attr($event_title_letter_spacing) . 'px; 
--event_title_text_align: ' . esc_attr($event_title_text_align) . '; 
--event_title_text_transform: ' . esc_attr($event_title_text_transform) . '; 
"
>';

    if ('none' === $eventful_page_link_type) {
        echo sprintf('<a %2$s>%1$s</a>', wp_kses($eventful_event_title, $allowed_html_tags), esc_attr($eventful_link_rel_text));
    } else {
        echo sprintf('<a href="%1$s" %3$s target="%4$s">%2$s</a>', esc_url(get_the_permalink($event)), wp_kses($eventful_event_title, $allowed_html_tags), esc_attr($eventful_link_rel_text), esc_attr($eventful_link_target));
    }
    echo '</' . esc_attr($event_title_tag) . '>';
}
