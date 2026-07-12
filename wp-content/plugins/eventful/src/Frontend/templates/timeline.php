<?php

/**
 * Timeline view
 *
 * @package    Eventful
 * @subpackage Eventful/public/template
 */

use ThemeAtelier\Eventful\Frontend\Helpers\EventfulLoopHtml;
use ThemeAtelier\Eventful\Frontend\Helpers\EventfulFunctions;

if (!defined('ABSPATH')) {
	exit;
}

$g_option                  = get_option('eventful_settings');
$responsive_screen_setting = isset($g_option['responsive_screen_setting']) ? $g_option['responsive_screen_setting'] : '';
$desktop_screen_size       = isset($responsive_screen_setting['desktop']) ? $responsive_screen_setting['desktop'] : '1200';
$tablet_screen_size        = isset($responsive_screen_setting['tablet']) ? $responsive_screen_setting['tablet'] : '992';
$mobile_land_screen_size   = isset($responsive_screen_setting['mobile_landscape']) ? $responsive_screen_setting['mobile_landscape'] : '768';
$mobile_screen_size        = isset($responsive_screen_setting['mobile']) ? $responsive_screen_setting['mobile'] : '576';

$template_style = isset($options['template_style']) ? $options['template_style'] : 'custom';
if ($template_style === 'custom') {
	$eventful_event_title         = isset($event_content_sorter['eventful_event_title']) ? $event_content_sorter['eventful_event_title'] : '';
	$event_title_length           = isset($eventful_event_title['eventful_title_length']) ? $eventful_event_title['eventful_title_length'] : '';
	$event_title_length_limit     = isset($event_title_length['all']) ? $event_title_length['all'] : '';
	$event_title_length_unit      = isset($event_title_length['unit']) ? $event_title_length['unit'] : 'words';

	$eventful_event_content       = isset($event_content_sorter['eventful_event_content']) ? $event_content_sorter['eventful_event_content'] : '';
	$event_content_length         = isset($eventful_event_content['eventful_content_length']) ? $eventful_event_content['eventful_content_length'] : '';
	$event_content_length_limit   = isset($event_content_length['all']) ? $event_content_length['all'] : '20';
	$event_content_length_unit    = isset($event_content_length['unit']) ? $event_content_length['unit'] : 'words';
}

$vertical_timeline_style = isset($options['vertical_timeline_style']) ? $options['vertical_timeline_style'] : 'style_01';
?>
<style>
	<?php if ($template_style === 'custom') {
		if ($event_title_length_unit === 'lines') { ?>#eventful_<?php echo esc_attr($eventful_gl_id . ' '); ?>.eventful__item--title a {
		overflow: hidden;
		display: -webkit-box;
		-webkit-line-clamp: <?php echo esc_attr($event_title_length_limit); ?>;
		line-clamp: <?php echo esc_attr($event_title_length_limit); ?>;
		-webkit-box-orient: vertical;
	}

	<?php }
		if ($event_content_length_unit === 'lines') { ?>#eventful_<?php echo esc_attr($eventful_gl_id . ' '); ?>.eventful__item__content {
		overflow: hidden;
		display: -webkit-box;
		-webkit-line-clamp: <?php echo esc_attr($event_content_length_limit); ?>;
		line-clamp: <?php echo esc_attr($event_content_length_limit); ?>;
		-webkit-box-orient: vertical;
	}

	<?php }
	} ?>
</style>

<div id="eventful_<?php echo esc_attr($eventful_gl_id); ?>" class="vertical_timeline <?php self::eventful_wrapper_classes($options, $layout_preset, $eventful_gl_id, $item_same_height_class); ?>" <?php self::wrapper_data($pagination_type, $pagination_type_mobile, $eventful_gl_id); ?> data-lang="<?php echo esc_attr($ta_eventful_lang); ?>" data-layout="<?php echo esc_attr($layout_preset); ?>"
	style="
--desktop_screen_size: <?php echo esc_attr($desktop_screen_size); ?>px;
--tablet_screen_size: <?php echo esc_attr($tablet_screen_size); ?>px;
--mobile_land_screen_size: <?php echo esc_attr($mobile_land_screen_size); ?>px;
--mobile_screen_size: <?php echo esc_attr($mobile_screen_size); ?>px;
--margin_between_event_half: <?php echo esc_attr($margin_between_event_half); ?>px;
">
	<?php
	EventfulLoopHtml::eventful_section_title($options, $section_title, $show_section_title);
	EventfulLoopHtml::eventful_preloader($show_preloader);
	?>
	<?php require EventfulFunctions::eventful_locate_template('filter-bar.php'); ?>
	<div class="eventful" style="<?php echo esc_attr($item_style_var); ?>">
		<div class="ta-row layout_timeline <?php echo esc_attr($vertical_timeline_style); ?>">
			<?php self::eventful_get_posts($options, $layout_preset, $event_content_sorter, $eventful_query, $eventful_gl_id); ?>
		</div>
	</div>
	<?php require EventfulFunctions::eventful_locate_template('pagination.php'); ?>
</div>
