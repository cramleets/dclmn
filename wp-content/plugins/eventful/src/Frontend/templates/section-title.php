<?php

/**
 * Section title
 *
 * This template can be overridden by copying it to yourtheme/eventful/templates/section-title.php
 *
 * @package    Eventful
 * @subpackage Eventful/public/template
 */
$section_title_margin   = isset($options['section_title_margin']) ? $options['section_title_margin'] : array();
$section_title_margin_top = !empty($section_title_margin['top']) ? $section_title_margin['top'] : '0';
$section_title_margin_right = !empty($section_title_margin['right']) ? $section_title_margin['right'] : '0';
$section_title_margin_bottom = !empty($section_title_margin['bottom']) ? $section_title_margin['bottom'] : '0';
$section_title_margin_left = !empty($section_title_margin['left']) ? $section_title_margin['left'] : '0';

$g_option = get_option('eventful_settings');
$section_title_color  = isset($g_option['section_title_color']) ? $g_option['section_title_color'] : '';
$g_color  = isset($section_title_color['color']) ? $section_title_color['color'] : '#444444';

$_section_title_typography   = isset($options['section_title_typography']) ? $options['section_title_typography'] : array();
$section_title_color = !empty($_section_title_typography['color']) ? $_section_title_typography['color'] : $g_color;
$section_title_font_family = isset($_section_title_typography['font-family']) ? $_section_title_typography['font-family'] : '';
$section_title_font_weight = isset($_section_title_typography['font-weight']) ? $_section_title_typography['font-weight'] : '';
$section_title_subset = isset($_section_title_typography['subset']) ? $_section_title_typography['subset'] : '';
$section_title_font_size = isset($_section_title_typography['font-size']) ? $_section_title_typography['font-size'] : '24';
$section_title_tablet_font_size = isset($_section_title_typography['tablet-font-size']) ? $_section_title_typography['tablet-font-size'] : '18';
$section_title_mobile_font_size = isset($_section_title_typography['mobile-font-size']) ? $_section_title_typography['mobile-font-size'] : '15';
$section_title_line_height = isset($_section_title_typography['line-height']) ? $_section_title_typography['line-height'] : '28';
$section_title_tablet_line_height = isset($_section_title_typography['tablet-line-height']) ? $_section_title_typography['tablet-line-height'] : '24';
$section_title_mobile_line_height = isset($_section_title_typography['mobile-line-height']) ? $_section_title_typography['mobile-line-height'] : '18';
$section_title_letter_spacing = isset($_section_title_typography['letter-spacing']) ? $_section_title_typography['letter-spacing'] : '0';
$section_title_text_align = isset($_section_title_typography['text-align']) ? $_section_title_typography['text-align'] : 'left';
$section_title_text_transform = isset($_section_title_typography['text-transform']) ? $_section_title_typography['text-transform'] : 'none';

if (! empty($section_title_text)) {
?>
	<h2 class="eventful__section_title"
		style="
    --section_title_color: <?php echo esc_attr($section_title_color); ?>; 
    --section_title_font_family: <?php echo esc_attr($section_title_font_family); ?>;
    --section_title_font_weight: <?php echo esc_attr($section_title_font_weight); ?>;
    --section_title_subset: <?php echo esc_attr($section_title_subset); ?>;
    --section_title_font_size: <?php echo esc_attr($section_title_font_size . 'px'); ?>;
    --section_title_tablet_font_size: <?php echo esc_attr($section_title_tablet_font_size . 'px'); ?>;
    --section_title_mobile_font_size: <?php echo esc_attr($section_title_mobile_font_size . 'px'); ?>;
    --section_title_line_height: <?php echo esc_attr($section_title_line_height . 'px'); ?>;
    --section_title_tablet_line_height: <?php echo esc_attr($section_title_tablet_line_height . 'px'); ?>;
    --section_title_mobile_line_height: <?php echo esc_attr($section_title_mobile_line_height . 'px'); ?>; 
    --section_title_letter_spacing: <?php echo esc_attr($section_title_letter_spacing . 'px'); ?>; 
    --section_title_text_align: <?php echo esc_attr($section_title_text_align); ?>; 
    --section_title_text_transform: <?php echo esc_attr($section_title_text_transform); ?>;
    --section_title_margin: <?php echo esc_attr($section_title_margin_top . 'px' . ' ' . $section_title_margin_right . 'px' . ' ' . $section_title_margin_bottom . 'px' . ' ' . $section_title_margin_left . 'px'); ?>;
    "><?php echo wp_kses_post($section_title_text); ?> </h2>
<?php } ?>