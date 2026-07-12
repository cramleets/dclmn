<?php

/**
 *  Carousel view
 *
 * @package    Eventful
 * @subpackage Eventful/public/template
 */

use ThemeAtelier\Eventful\Frontend\Helpers\EventfulLoopHtml;
use ThemeAtelier\Eventful\Frontend\Helpers\EventfulFunctions;

if (!defined('ABSPATH')) {
	exit;
}

$eventful_g_option                  = get_option( 'eventful_settings' );
$eventful_responsive_screen_setting = isset( $eventful_g_option['responsive_screen_setting'] ) ? $eventful_g_option['responsive_screen_setting'] : '';
$eventful_desktop_screen_size       = isset( $eventful_responsive_screen_setting['desktop'] ) ? $eventful_responsive_screen_setting['desktop'] : '1200';
$eventful_tablet_screen_size        = isset( $eventful_responsive_screen_setting['tablet'] ) ? $eventful_responsive_screen_setting['tablet'] : '992';
$eventful_mobile_land_screen_size   = isset( $eventful_responsive_screen_setting['mobile_landscape'] ) ? $eventful_responsive_screen_setting['mobile_landscape'] : '768';
$eventful_mobile_screen_size        = isset( $eventful_responsive_screen_setting['mobile'] ) ? $eventful_responsive_screen_setting['mobile'] : '576';
$eventful_template_style            = isset( $options['template_style'] ) ? $options['template_style'] : 'custom';
if ( $eventful_template_style === 'custom' ) {
	$eventful_event_title             = isset( $event_content_sorter['eventful_event_title'] ) ? $event_content_sorter['eventful_event_title'] : '';
	$eventful_event_title_length      = isset( $eventful_event_title['eventful_title_length'] ) ? $eventful_event_title['eventful_title_length'] : '';
	$eventful_event_title_length_limit = isset( $eventful_event_title_length['all'] ) ? $eventful_event_title_length['all'] : '20';
	$eventful_event_title_length_unit  = isset( $eventful_event_title_length['unit'] ) ? $eventful_event_title_length['unit'] : 'words';

	$eventful_event_content             = isset( $event_content_sorter['eventful_event_content'] ) ? $event_content_sorter['eventful_event_content'] : '';
	$eventful_event_content_length      = isset( $eventful_event_content['eventful_content_length'] ) ? $eventful_event_content['eventful_content_length'] : '';
	$eventful_event_content_length_limit = isset( $eventful_event_content_length['all'] ) ? $eventful_event_content_length['all'] : '20';
	$eventful_event_content_length_unit  = isset( $eventful_event_content_length['unit'] ) ? $eventful_event_content_length['unit'] : 'words';
}
?>
<style>
	<?php
	if ( $eventful_template_style === 'custom' ) {
		if ( $eventful_event_title_length_unit === 'lines' ) { ?>#eventful_<?php echo esc_attr( $eventful_gl_id . ' ' ); ?>.eventful__item--title a {
		overflow: hidden;
		display: -webkit-box;
		-webkit-line-clamp: <?php echo esc_attr( $eventful_event_title_length_limit ); ?>;
		line-clamp: <?php echo esc_attr( $eventful_event_title_length_limit ); ?>;
		-webkit-box-orient: vertical;
	}

	<?php }
		if ( $eventful_event_content_length_unit === 'lines' ) { ?>#eventful_<?php echo esc_attr( $eventful_gl_id . ' ' ); ?>.eventful__item__content {
		overflow: hidden;
		display: -webkit-box;
		-webkit-line-clamp: <?php echo esc_attr( $eventful_event_content_length_limit ); ?>;
		line-clamp: <?php echo esc_attr( $eventful_event_content_length_limit ); ?>;
		-webkit-box-orient: vertical;
	}

	<?php }
	}
	?>
</style>

<div id="eventful_<?php echo esc_attr($eventful_gl_id); ?>" class="<?php self::eventful_wrapper_classes($options, $layout_preset, $eventful_gl_id, $item_same_height_class); ?>" <?php self::wrapper_data($pagination_type, $pagination_type_mobile, $eventful_gl_id); ?> data-lang="<?php echo esc_attr($ta_eventful_lang); ?>"
	style="
--desktop_screen_size: <?php echo esc_attr( $eventful_desktop_screen_size ); ?>px;
--tablet_screen_size: <?php echo esc_attr( $eventful_tablet_screen_size ); ?>px;
--mobile_land_screen_size: <?php echo esc_attr( $eventful_mobile_land_screen_size ); ?>px;
--mobile_screen_size: <?php echo esc_attr( $eventful_mobile_screen_size ); ?>px;
--margin_between_event_half: <?php echo esc_attr($margin_between_event_half); ?>px;
">
	<?php
	EventfulLoopHtml::eventful_section_title($options, $section_title, $show_section_title);
	EventfulLoopHtml::eventful_preloader($show_preloader);
	?>
	<?php require EventfulFunctions::eventful_locate_template('filter-bar.php'); ?>
	<div class="eventful" style="<?php echo esc_attr($item_style_var) ?>">
		<div class="ta-row">
			<?php self::eventful_get_posts($options, $layout_preset, $event_content_sorter, $eventful_query, $eventful_gl_id); ?>
		</div>
	</div>
	<?php require EventfulFunctions::eventful_locate_template('pagination.php'); ?>
</div>