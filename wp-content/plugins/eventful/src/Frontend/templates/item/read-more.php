<?php

/**
 * Read more
 *
 * This template can be overridden by copying it to yourtheme/eventful/templates/item/read-more.php
 *
 * @package    Eventful
 * @subpackage Eventful/public
 */

use ThemeAtelier\Eventful\Frontend\Helpers\EventfulFunctions;

$template_style   = isset($options['template_style']) ? $options['template_style'] : 'custom';
$eventful_event_content = isset($event_content_setting['eventful_event_content']) ? $event_content_setting['eventful_event_content'] : '';
$show_read_more = isset($eventful_event_content['show_read_more']) ? $eventful_event_content['show_read_more'] : '';

if (!$show_read_more || 'full_content' === $content_type) {
    return '';
}
$read_more_type     = isset($eventful_event_content['read_more_type']) ? $eventful_event_content['read_more_type'] : '';
$eventful_read_label     = isset($eventful_event_content['eventful_read_label']) ? $eventful_event_content['eventful_read_label'] : '';
$eventful_page_link_type = EventfulFunctions::eventful_metabox_value('eventful_page_link_type', $options);
$eventful_link_rel       = EventfulFunctions::eventful_metabox_value('eventful_link_rel', $options);
$eventful_link_rel_text  = '';
if ($eventful_link_rel) {
    $eventful_link_rel_text = "rel='nofollow'";
}
$readmore_target = EventfulFunctions::eventful_metabox_value('eventful_link_target', $options);

$setting_options = get_option('eventful_settings');
$button_color  = isset($setting_options['read_more_button_color']) ? $setting_options['read_more_button_color'] : '';
$button_color_color  = isset($button_color['color']) ? $button_color['color'] : '#111111';
$button_hover_color  = isset($button_color['hover_color']) ? $button_color['hover_color'] : '#ffffff';
$button_background  = isset($button_color['background']) ? $button_color['background'] : 'transparent';
$button_hover_background  = isset($button_color['hover_background']) ? $button_color['hover_background'] : '#111111';
$button_border  = isset($button_color['border']) ? $button_color['border'] : '#888888';
$button_hover_border  = isset($button_color['hover_border']) ? $button_color['hover_border'] : '#222222';
$button_link_colors  = isset($setting_options['read_more_button_link_color']) ? $setting_options['read_more_button_link_color'] : '';
$button_link_color  = isset($button_link_colors['color']) ? $button_link_colors['color'] : '#222222';
$button_link_hover_color  = isset($button_link_colors['hover_color']) ? $button_link_colors['hover_color'] : '#222222';

$eventful_event_content = isset($event_content_setting['eventful_event_content']) ? $event_content_setting['eventful_event_content'] : '';
$read_more_btn_border = isset($eventful_event_content['read_more_btn_border']) ? $eventful_event_content['read_more_btn_border'] : '';
$border_width = isset($read_more_btn_border['all']) ? $read_more_btn_border['all'] : '';
$border_style = isset($read_more_btn_border['style']) ? $read_more_btn_border['style'] : '';
$border_color = !empty($read_more_btn_border['color']) ? $read_more_btn_border['color'] : $button_border;
$border_hover_color = !empty($read_more_btn_border['hover_color']) ? $read_more_btn_border['hover_color'] : $button_hover_border;
$border_radius = isset($read_more_btn_border['border_radius']) ? $read_more_btn_border['border_radius'] : '';
$read_more_btn_border = "{$border_width}px {$border_style} {$border_color}";

$readmore_padding        = isset($eventful_event_content['readmore_padding']) ? $eventful_event_content['readmore_padding'] : array();
$readmore_padding_top = !empty($readmore_padding['top']) ? $readmore_padding['top'] : '7';
$readmore_padding_right = !empty($readmore_padding['right']) ? $readmore_padding['right'] : '15';
$readmore_padding_bottom = !empty($readmore_padding['bottom']) ? $readmore_padding['bottom'] : '7';
$readmore_padding_left = !empty($readmore_padding['left']) ? $readmore_padding['left'] : '15';
$readmore_padding = "{$readmore_padding_top}px {$readmore_padding_right}px {$readmore_padding_bottom}px {$readmore_padding_left}px;";
$event_readmore_margin        = isset($eventful_event_content['event_readmore_margin']) ? $eventful_event_content['event_readmore_margin'] : array();
$event_readmore_margin_top = !empty($event_readmore_margin['top']) ? $event_readmore_margin['top'] : '0';
$event_readmore_margin_right = !empty($event_readmore_margin['right']) ? $event_readmore_margin['right'] : '0';
$event_readmore_margin_bottom = !empty($event_readmore_margin['bottom']) ? $event_readmore_margin['bottom'] : '0';
$event_readmore_margin_left = !empty($event_readmore_margin['left']) ? $event_readmore_margin['left'] : '0';

if ('custom' === $template_style) {
    $event_readmore_margin = "{$event_readmore_margin_top}px {$event_readmore_margin_right}px {$event_readmore_margin_bottom}px {$event_readmore_margin_left}px;";
} else {
    $event_readmore_margin = '';
}
$_read_more_typography   = isset($options['read_more_typography']) ? $options['read_more_typography'] : array();
$event_readmore_color = isset($_read_more_typography['color']) ? $_read_more_typography['color'] : $button_color_color;
$event_readmore_font_family = isset($_read_more_typography['font-family']) ? $_read_more_typography['font-family'] : '';
$event_readmore_font_weight = !empty($_read_more_typography['font-weight']) ? $_read_more_typography['font-weight'] : '400';
$event_readmore_font_style = !empty($_read_more_typography['font-style']) ? $_read_more_typography['font-style'] : 'normal';
$event_readmore_subset = isset($_read_more_typography['subset']) ? $_read_more_typography['subset'] : '';
$event_readmore_font_size = isset($_read_more_typography['font-size']) ? $_read_more_typography['font-size'] : '12';
$event_readmore_tablet_font_size = isset($_read_more_typography['tablet-font-size']) ? $_read_more_typography['tablet-font-size'] : '12';
$event_readmore_mobile_font_size = isset($_read_more_typography['mobile-font-size']) ? $_read_more_typography['mobile-font-size'] : '10';
$event_readmore_line_height = isset($_read_more_typography['line-height']) ? $_read_more_typography['line-height'] : '18';
$event_readmore_tablet_line_height = isset($_read_more_typography['tablet-line-height']) ? $_read_more_typography['tablet-line-height'] : '18';
$event_readmore_mobile_line_height = isset($_read_more_typography['mobile-line-height']) ? $_read_more_typography['mobile-line-height'] : '16';
$event_readmore_letter_spacing = isset($_read_more_typography['letter-spacing']) ? $_read_more_typography['letter-spacing'] : '0';
$event_readmore_text_align = isset($_read_more_typography['text-align']) ? $_read_more_typography['text-align'] : 'left';
$event_readmore_text_transform = isset($_read_more_typography['text-transform']) ? $_read_more_typography['text-transform'] : 'none';

$event_content_margin        = isset($eventful_event_content['event_content_margin']) ? $eventful_event_content['event_content_margin'] : array();
$event_content_margin_top = !empty($event_content_margin['top']) ? $event_content_margin['top'] : '0';
$event_content_margin_right = !empty($event_content_margin['right']) ? $event_content_margin['right'] : '0';
$event_content_margin_bottom = !empty($event_content_margin['bottom']) ? $event_content_margin['bottom'] : '15';
$event_content_margin_left = !empty($event_content_margin['left']) ? $event_content_margin['left'] : '0';
$event_content_margin = "{$event_content_margin_top}px {$event_content_margin_right}px {$event_content_margin_bottom}px {$event_content_margin_left}px;";
$readmore_typography = '';
if ($event_readmore_font_family) {
    $readmore_typography = "--event_readmore_font_family: $event_readmore_font_family; --event_readmore_font_weight: $event_readmore_font_weight; --event_readmore_font_style: $event_readmore_font_style";
}

$read_more_type = isset($eventful_event_content['read_more_type']) ? $eventful_event_content['read_more_type'] : '';
$readmore_color_button = isset($eventful_event_content['readmore_color_button']) ? $eventful_event_content['readmore_color_button'] : '';
$read_more_color_text = !empty($readmore_color_button['text']) ? $readmore_color_button['text'] : $button_color_color;
$read_more_color_hover_text = !empty($readmore_color_button['hover_text']) ? $readmore_color_button['hover_text'] : $button_hover_color;
$read_more_color_bg = !empty($readmore_color_button['bg']) ? $readmore_color_button['bg'] : $button_background;
$read_more_color_hover_bg = !empty($readmore_color_button['hover_bg']) ? $readmore_color_button['hover_bg'] : $button_hover_background;

$readmore_color_text = !empty($eventful_event_content['readmore_color_text']) ? $eventful_event_content['readmore_color_text'] : '';
$readmore_color_text_color = !empty($readmore_color_text['color']) ? $readmore_color_text['color'] : $button_link_color;
$read_more_color_text_hover_color = !empty($readmore_color_text['hover_color']) ? $readmore_color_text['hover_color'] : $button_link_hover_color;

if ($read_more_type === 'button') {
    $read_more_color = "--read_more_color_text: $read_more_color_text; --read_more_color_hover_text: $read_more_color_hover_text; --read_more_color_bg: $read_more_color_bg; --read_more_color_hover_bg: $read_more_color_hover_bg;";
} else {
    $read_more_color = "--read_more_color_text: $readmore_color_text_color; --read_more_color_hover_text: $read_more_color_text_hover_color;";
}

if ('text_link' === $read_more_type) {
    if ('popup' === $eventful_page_link_type) { ?>
        <a style="<?php echo esc_attr($read_more_color); ?>" class="popup-modal eventful__item__link" target="<?php echo esc_attr($readmore_target); ?>" <?php echo esc_html($eventful_link_rel_text); ?>><?php echo esc_html($eventful_read_label); ?></a>
    <?php } elseif ('none' === $eventful_page_link_type) { ?>
        <a style="<?php echo esc_attr($read_more_color); ?>" class="eventful__item__link" target="<?php echo esc_attr($readmore_target); ?>" <?php echo esc_html($eventful_link_rel_text); ?>><?php echo esc_html($eventful_read_label); ?></a>
    <?php } else { ?>
        <a style="<?php echo esc_attr($read_more_color); ?>" class="eventful__item__link" target="<?php echo esc_attr($readmore_target); ?>" ta rel="<?php echo esc_attr($eventful_link_rel); ?>" href="<?php the_permalink($event); ?>" <?php echo esc_html($eventful_link_rel_text); ?>>
            <?php echo esc_html($eventful_read_label); ?> </a>
    <?php }
} else {
    echo '<div class="eventful__item__readmore"
            style="
            --read_more_btn_border: ' . esc_attr($read_more_btn_border) . ';
            --border_hover_color: ' . esc_attr($border_hover_color) . ';
            --border_radius: ' . esc_attr($border_radius) . 'px;
            --readmore_padding: ' . esc_attr($readmore_padding) . ';
            --event_readmore_margin: ' . esc_attr($event_readmore_margin) . ';
            --event_readmore_color: ' . esc_attr($event_readmore_color) . ';
            ' . wp_kses_post($readmore_typography) . ';
            --event_readmore_subset: ' . esc_attr($event_readmore_subset) . ';
            --event_readmore_font_size: ' . esc_attr($event_readmore_font_size) . 'px;
            --event_readmore_tablet_font_size: ' . esc_attr($event_readmore_tablet_font_size) . 'px;
            --event_readmore_mobile_font_size: ' . esc_attr($event_readmore_mobile_font_size) . 'px;
            --event_readmore_line_height: ' . esc_attr($event_readmore_line_height) . 'px;
            --event_readmore_tablet_line_height: ' . esc_attr($event_readmore_tablet_line_height) . 'px;
            --event_readmore_mobile_line_height: ' . esc_attr($event_readmore_mobile_line_height) . 'px;
            --event_readmore_letter_spacing: ' . esc_attr($event_readmore_letter_spacing) . 'px;
            --event_readmore_text_align: ' . esc_attr($event_readmore_text_align) . ';
            --event_readmore_text_transform: ' . esc_attr($event_readmore_text_transform) . ';
            --event_content_margin: ' . esc_attr($event_content_margin) . ';
            ' . wp_kses_post($read_more_color) . ';
            "
            >';
    if ('popup' === $eventful_page_link_type) { ?>
        <a class="popup-modal eventful__item__btn" href="<?php the_permalink($event); ?>" target="<?php echo esc_attr($readmore_target); ?>" <?php echo esc_html($eventful_link_rel_text); ?>><?php echo esc_html($eventful_read_label); ?></a>
    <?php } elseif ('none' === $eventful_page_link_type) { ?>
        <a class="eventful__item__btn" target="<?php echo esc_attr($readmore_target); ?>" <?php echo esc_html($eventful_link_rel_text); ?>><?php echo esc_html($eventful_read_label); ?></a>
    <?php } else { ?>
        <a class="eventful__item__btn" target="<?php echo esc_attr($readmore_target); ?>" href="<?php the_permalink($event); ?>" <?php echo esc_html($eventful_link_rel_text); ?>><?php echo esc_html($eventful_read_label); ?></a>
<?php }
    echo '</div>';
}
