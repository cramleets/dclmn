<?php

use ThemeAtelier\Eventful\Frontend\Helpers\EventfulFunctions;

/**
 * Meta
 *
 * This template can be overridden by copying it to yourtheme/eventful/templates/item/event-meta.php
 *
 * @package    Eventful
 * @subpackage Eventful/public
 */

$template_style   = isset($options['template_style']) ? $options['template_style'] : 'custom';
$event_meta_margin        = isset($eventful_event_meta['event_meta_margin']) ? $eventful_event_meta['event_meta_margin'] : array();
$event_meta_margin_top = !empty($event_meta_margin['top']) ? $event_meta_margin['top'] : '0';
$event_meta_margin_right = !empty($event_meta_margin['right']) ? $event_meta_margin['right'] : '0';
$event_meta_margin_bottom = !empty($event_meta_margin['bottom']) ? $event_meta_margin['bottom'] : '15';
$event_meta_margin_left = !empty($event_meta_margin['left']) ? $event_meta_margin['left'] : '0';
if ('custom' === $template_style) {
	$event_meta_margin = "{$event_meta_margin_top}px {$event_meta_margin_right}px {$event_meta_margin_bottom}px {$event_meta_margin_left}px;";
} else {
	$event_meta_margin = '';
}
$event_meta_between_margin        = isset($eventful_event_meta['event_meta_between_margin']) ? $eventful_event_meta['event_meta_between_margin'] : array();
$event_meta_between_margin_top = !empty($event_meta_between_margin['top']) ? $event_meta_between_margin['top'] : '0';
$event_meta_between_margin_right = !empty($event_meta_between_margin['right']) ? $event_meta_between_margin['right'] : '0';
$event_meta_between_margin_bottom = !empty($event_meta_between_margin['bottom']) ? $event_meta_between_margin['bottom'] : '0';
$event_meta_between_margin_left = !empty($event_meta_between_margin['left']) ? $event_meta_between_margin['left'] : '0';
$event_meta_between_margin = "{$event_meta_between_margin_top}px {$event_meta_between_margin_right}px {$event_meta_between_margin_bottom}px {$event_meta_between_margin_left}px;";

$setting_options = get_option('eventful_settings');
$event_meta_field_color  = isset($setting_options['event_meta_field_color']) ? $setting_options['event_meta_field_color'] : '';
$g_color  = isset($event_meta_field_color['color']) ? $event_meta_field_color['color'] : '#111111';
$g_hover_color  = isset($event_meta_field_color['hover_color']) ? $event_meta_field_color['hover_color'] : '#0015b5';

$_event_meta_typography   = isset($options['event_meta_typography']) ? $options['event_meta_typography'] : array();
$event_meta_color = !empty($_event_meta_typography['color']) ? $_event_meta_typography['color'] : $g_color;
$event_meta_hover_color = !empty($_event_meta_typography['hover_color']) ? $_event_meta_typography['hover_color'] : $g_hover_color;
$event_meta_font_family = isset($_event_meta_typography['font-family']) ? $_event_meta_typography['font-family'] : '';
$event_meta_font_weight = !empty($_event_meta_typography['font-weight']) ? $_event_meta_typography['font-weight'] : '400';
$event_meta_font_style = !empty($_event_meta_typography['font-style']) ? $_event_meta_typography['font-style'] : 'normal';
$event_meta_subset = isset($_event_meta_typography['subset']) ? $_event_meta_typography['subset'] : '';
$event_meta_font_size = isset($_event_meta_typography['font-size']) ? $_event_meta_typography['font-size'] : '12';
$event_meta_tablet_font_size = isset($_event_meta_typography['tablet-font-size']) ? $_event_meta_typography['tablet-font-size'] : '12';
$event_meta_mobile_font_size = isset($_event_meta_typography['mobile-font-size']) ? $_event_meta_typography['mobile-font-size'] : '10';
$event_meta_line_height = isset($_event_meta_typography['line-height']) ? $_event_meta_typography['line-height'] : '18';
$event_meta_tablet_line_height = isset($_event_meta_typography['tablet-line-height']) ? $_event_meta_typography['tablet-line-height'] : '18';
$event_meta_mobile_line_height = isset($_event_meta_typography['mobile-line-height']) ? $_event_meta_typography['mobile-line-height'] : '16';
$event_meta_letter_spacing = isset($_event_meta_typography['letter-spacing']) ? $_event_meta_typography['letter-spacing'] : '0';
$event_meta_text_align = isset($_event_meta_typography['text-align']) ? $_event_meta_typography['text-align'] : 'left';
$event_meta_text_transform = isset($_event_meta_typography['text-transform']) ? $_event_meta_typography['text-transform'] : 'none';

$readmore_typography = '';
if ($event_meta_font_family) {
    $readmore_typography = "--event_meta_font_family: " . esc_attr($event_meta_font_family) . ";
        --event_meta_font_weight: " . esc_attr($event_meta_font_weight) . ";
        --event_meta_font_style: " . esc_attr($event_meta_font_style) . ";";
}

echo '<div class="eventful__item--meta event_meta"
style="
--event_meta_margin: ' . esc_attr($event_meta_margin) . ';
--event_meta_between_margin: ' . esc_attr($event_meta_between_margin) . ';
--event_meta_color: ' . esc_attr($event_meta_color) . '; 
--event_meta_hover_color: ' . esc_attr($event_meta_hover_color) . '; 
' . wp_kses_post($readmore_typography) . '
--event_meta_subset: ' . esc_attr($event_meta_subset) . '; 
--event_meta_font_size: ' . esc_attr($event_meta_font_size) . 'px;
--event_meta_tablet_font_size: ' . esc_attr($event_meta_tablet_font_size) . 'px;
--event_meta_mobile_font_size: ' . esc_attr($event_meta_mobile_font_size) . 'px;
--event_meta_line_height: ' . esc_attr($event_meta_line_height) . 'px;
--event_meta_tablet_line_height: ' . esc_attr($event_meta_tablet_line_height) . 'px;
--event_meta_mobile_line_height: ' . esc_attr($event_meta_mobile_line_height) . 'px;
--event_meta_letter_spacing: ' . esc_attr($event_meta_letter_spacing) . 'px;
--event_meta_text_align: ' . esc_attr($event_meta_text_align) . '; 
--event_meta_text_transform: ' . esc_attr($event_meta_text_transform) . '; 
"
>';

EventfulFunctions::eventful_get_event_fildes($event, $event_fildes_fields, $event_meta_separator);

echo '</div>';
