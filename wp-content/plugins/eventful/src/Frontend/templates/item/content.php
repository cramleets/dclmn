<?php

use ThemeAtelier\Eventful\Frontend\Helpers\EventfulFunctions;

/**
 * Content
 *
 * This template can be overridden by copying it to yourtheme/eventful/templates/item/content.php
 *
 * @package    Eventful
 * @subpackage Eventful/public
 */

$template_style   = isset($options['template_style']) ? $options['template_style'] : 'custom';
$event_content_setting     = EventfulFunctions::eventful_metabox_value('eventful_event_content', $sorter);
$show_event_content     = EventfulFunctions::eventful_metabox_value('show_event_content', $event_content_setting);

$show_read_more                = EventfulFunctions::eventful_metabox_value('show_read_more', $event_content_setting);
$eventful_content_type              = EventfulFunctions::eventful_metabox_value('post_content_type', $event_content_setting);
$setting_options = get_option('eventful_settings');
$event_content_color  = isset($setting_options['event_content_color']) ? $setting_options['event_content_color'] : '';
$g_color  = isset($event_content_color['color']) ? $event_content_color['color'] : '#111111';

if ($show_event_content || $show_read_more) {
	$event_content_margin        = isset($event_content_setting['event_content_margin']) ? $event_content_setting['event_content_margin'] : array();
	$event_content_margin_top = !empty($event_content_margin['top']) ? $event_content_margin['top'] : '0';
	$event_content_margin_right = !empty($event_content_margin['right']) ? $event_content_margin['right'] : '0';
	$event_content_margin_bottom = !empty($event_content_margin['bottom']) ? $event_content_margin['bottom'] : '15';
	$event_content_margin_left = !empty($event_content_margin['left']) ? $event_content_margin['left'] : '0';
	if ('custom' === $template_style) {
		$event_content_margin = "{$event_content_margin_top}px {$event_content_margin_right}px {$event_content_margin_bottom}px {$event_content_margin_left}px;";
	} else {
		$event_content_margin = '';
	}

	$_event_content_typography   = isset($options['event_content_typography']) ? $options['event_content_typography'] : array();
	$event_content_color = !empty($_event_content_typography['color']) ? $_event_content_typography['color'] : $g_color;
	$event_content_font_family = isset($_event_content_typography['font-family']) ? $_event_content_typography['font-family'] : '';
	$event_content_font_weight = !empty($_event_content_typography['font-weight']) ? $_event_content_typography['font-weight'] : '400';
	$event_content_font_style = !empty($_event_content_typography['font-style']) ? $_event_content_typography['font-style'] : 'normal';
	$event_content_subset = isset($_event_content_typography['subset']) ? $_event_content_typography['subset'] : '';
	$event_content_font_size = isset($_event_content_typography['font-size']) ? $_event_content_typography['font-size'] : '16';
	$event_content_tablet_font_size = isset($_event_content_typography['tablet-font-size']) ? $_event_content_typography['tablet-font-size'] : '14';
	$event_content_mobile_font_size = isset($_event_content_typography['mobile-font-size']) ? $_event_content_typography['mobile-font-size'] : '12';
	$event_content_line_height = isset($_event_content_typography['line-height']) ? $_event_content_typography['line-height'] : '22';
	$event_content_tablet_line_height = isset($_event_content_typography['tablet-line-height']) ? $_event_content_typography['tablet-line-height'] : '18';
	$event_content_mobile_line_height = isset($_event_content_typography['mobile-line-height']) ? $_event_content_typography['mobile-line-height'] : '18';
	$event_content_letter_spacing = isset($_event_content_typography['letter-spacing']) ? $_event_content_typography['letter-spacing'] : '0';
	$event_content_text_align = isset($_event_content_typography['text-align']) ? $_event_content_typography['text-align'] : 'left';
	$event_content_text_transform = isset($_event_content_typography['text-transform']) ? $_event_content_typography['text-transform'] : 'none';

	$content_typography = '';
	if ($event_content_font_family) {
		$content_typography = "--event_content_font_family: $event_content_font_family; --event_content_font_weight: $event_content_font_weight; --event_content_font_style: $event_content_font_style";
	}
	$read_more_type = isset($event_content_setting['read_more_type']) ? $event_content_setting['read_more_type'] : '';

	echo '<div class="eventful__item__content"
	style="
	--event_content_margin: ' . esc_attr($event_content_margin) . ';
	--event_content_color: ' . esc_attr($event_content_color) . ';
	' . wp_kses_post($content_typography) . '
	--event_content_subset: ' . esc_attr($event_content_subset) . ';
	--event_content_font_size: ' . esc_attr($event_content_font_size) . 'px;
	--event_content_tablet_font_size: ' . esc_attr($event_content_tablet_font_size) . 'px;
	--event_content_mobile_font_size: ' . esc_attr($event_content_mobile_font_size) . 'px;
	--event_content_line_height: ' . esc_attr($event_content_line_height) . 'px;
	--event_content_tablet_line_height: ' . esc_attr($event_content_tablet_line_height) . 'px;
	--event_content_mobile_line_height: ' . esc_attr($event_content_mobile_line_height) . 'px;
	--event_content_letter_spacing: ' . esc_attr($event_content_letter_spacing) . 'px;
	--event_content_text_align: ' . esc_attr($event_content_text_align) . ';
	--event_content_text_transform: ' . esc_attr($event_content_text_transform) . ';
	">';
	if ($show_event_content) {
		echo wp_kses(EventfulFunctions::eventful_content($event_content_setting, $options, $eventful_content_type, $event), apply_filters('ta_wp_eventful_allowed_tags', EventfulFunctions::allowed_tags()));
	}
	if ('text_link' === $read_more_type && $show_read_more) {
		self::eventful_readmore($sorter, $options, $eventful_content_type, $event);
	}
	echo '</div>';
	if ('button' === $read_more_type && $show_read_more) {
		self::eventful_readmore($sorter, $options, $eventful_content_type, $event);
	}
}
