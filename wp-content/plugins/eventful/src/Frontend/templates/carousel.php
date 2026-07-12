<?php

use ThemeAtelier\Eventful\Frontend\Helpers\EventfulFunctions;
use ThemeAtelier\Eventful\Frontend\Helpers\EventfulLoopHtml;

/**
 *  Carousel view
 *
 * @package    Eventful
 * @subpackage Eventful/public/template
 */

if (!defined('ABSPATH')) {
	exit;
}
$eventful_swiper_css      = isset($g_option['eventful_swiper_css']) ? $g_option['eventful_swiper_css'] : true;
if ($eventful_swiper_css) {
	wp_enqueue_style('swiper-bundle');
}

wp_enqueue_script('swiper-bundle');
$carousel_mode        = isset($options['eventful_carousel_mode']) ? $options['eventful_carousel_mode'] : 'standard';
$carousel_autoplay    = (isset($options['eventful_autoplay']) && ($options['eventful_autoplay'])) ? 'true' : 'false';
$autoplay_speed       = isset($options['eventful_autoplay_speed']) ? $options['eventful_autoplay_speed'] : '2000';
$carousel_speed       = isset($options['eventful_carousel_speed']) ? $options['eventful_carousel_speed'] : '600';
$ticker_speed         = isset($options['eventful_ticker_speed']) ? $options['eventful_ticker_speed'] : '3000';
$pause_hover          = (isset($options['eventful_pause_hover']) && ($options['eventful_pause_hover'])) ? 'true' : 'false';
$_slides_to_scroll    = isset($options['eventful_slides_to_scroll']) ? $options['eventful_slides_to_scroll'] : array();
$infinite_loop        = (isset($options['eventful_infinite_loop']) && ($options['eventful_infinite_loop'])) ? 'true' : 'false';
$carousel_auto_height = (isset($options['eventful_adaptive_height']) && ($options['eventful_adaptive_height'])) ? 'true' : 'false';
$number_of_columns    = isset($options['eventful_number_of_columns']) ? $options['eventful_number_of_columns'] : array();

if ($layout_preset === 'slider') {
	$column_lg_desktop = '1';
	$column_desktop = '1';
	$column_tablet = '1';
	$column_mobile_landscape = '1';
	$column_mobile = '1';
} else {
	$column_lg_desktop = !empty($number_of_columns['lg_desktop']) ? $number_of_columns['lg_desktop'] : '3';
	$column_desktop = !empty($number_of_columns['desktop']) ? $number_of_columns['desktop'] : '3';
	$column_tablet = !empty($number_of_columns['tablet']) ? $number_of_columns['tablet'] : '2';
	$column_mobile_landscape = !empty($number_of_columns['mobile_landscape']) ? $number_of_columns['mobile_landscape'] : '1';
	$column_mobile = !empty($number_of_columns['mobile']) ? $number_of_columns['mobile'] : '1';
}

$ticker_slide_width   = isset($options['eventful_ticker_slide_width']) ? $options['eventful_ticker_slide_width'] : '450';
$lazy_load            = (isset($options['eventful_lazy_load']) && ($options['eventful_lazy_load'])) ? 'true' : 'false';

$eventful_pagination	= isset($options['eventful_pagination']) ? $options['eventful_pagination'] : 'show';
$ta_eventful_class = '';
if ('hide' !== $eventful_pagination && 'ticker' !== $carousel_mode) {
	$ta_eventful_class = ' pb_60';
}
if ('hide_on_mobile' === $eventful_pagination) {
	$ta_eventful_class .= ' pagination_hide_on_mobile';
}

$carousel_nav_position = EventfulFunctions::eventful_metabox_value('eventful_carousel_nav_position', $options, 'top_right');
$eventful_nav_position = 'initial';
if ('vertically_center_outer' === $carousel_nav_position) {
	$eventful_nav_position = 'static';
}

// Direction.
$carousel_direction = (isset($options['eventful_carousel_direction'])) ? $options['eventful_carousel_direction'] : 'ltr';
if ('ticker' === $carousel_mode) {
	$carousel_direction = 'rtl' === $carousel_direction ? 'prev' : 'next';
}
$is_carousel_accessibility            = (isset($g_option['accessibility']) && ($g_option['accessibility'])) ? 'true' : 'false';
$accessibility_prev_slide_text        = isset($g_option['prev_slide_message']) ? $g_option['prev_slide_message'] : '';
$accessibility_next_slide_text        = isset($g_option['next_slide_message']) ? $g_option['next_slide_message'] : '';
$accessibility_first_slide_text       = isset($g_option['first_slide_message']) ? $g_option['first_slide_message'] : '';
$accessibility_last_slide_text        = isset($g_option['last_slide_message']) ? $g_option['last_slide_message'] : '';
$accessibility_pagination_bullet_text = isset($g_option['pagination_bullet_message']) ? $g_option['pagination_bullet_message'] : '';

$responsive_screen_setting = isset($g_option['responsive_screen_setting']) ? $g_option['responsive_screen_setting'] : '';
$desktop_screen_size           = isset($responsive_screen_setting['desktop']) ? $responsive_screen_setting['desktop'] : '1200';
$tablet_screen_size            = isset($responsive_screen_setting['tablet']) ? $responsive_screen_setting['tablet'] : '980';
$mobile_land_screen_size       = isset($responsive_screen_setting['mobile_landscape']) ? $responsive_screen_setting['mobile_landscape'] : '736';
$mobile_screen_size            = isset($responsive_screen_setting['mobile']) ? $responsive_screen_setting['mobile'] : '576';

$template_style = isset($options['template_style']) ? $options['template_style'] : 'custom';
if ($template_style === 'custom') {
	$eventful_event_title            = isset($event_content_sorter['eventful_event_title']) ? $event_content_sorter['eventful_event_title'] : '';
	$event_title_length     = isset($eventful_event_title['eventful_title_length']) ? $eventful_event_title['eventful_title_length'] : '';
	$event_title_length_limit = isset($event_title_length['all']) ? $event_title_length['all'] : '20';
	$event_title_length_unit = isset($event_title_length['unit']) ? $event_title_length['unit'] : 'words';

	$eventful_event_content            = isset($event_content_sorter['eventful_event_content']) ? $event_content_sorter['eventful_event_content'] : '';
	$event_content_length     = isset($eventful_event_content['eventful_content_length']) ? $eventful_event_content['eventful_content_length'] : '';
	$event_content_length_limit = isset($event_content_length['all']) ? $event_content_length['all'] : '20';
	$event_content_length_unit = isset($event_content_length['unit']) ? $event_content_length['unit'] : 'words';
}
?>
<style>
	<?php
	if ($template_style === 'custom') {
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
	}
	?>
</style>
<?php
// Row.
$carousel_row = array(
	'lg_desktop'       => '1',
	'desktop'          => '1',
	'tablet'           => '1',
	'mobile_landscape' => '1',
	'mobile'           => '1',
);

$eventful_swiper_js = isset($g_option['eventful_swiper_js']) ? $g_option['eventful_swiper_js'] : '';
if ($eventful_swiper_js) {
	wp_enqueue_script('eventful_swiper');
}

// Navigation.
$_navigation = isset($options['eventful_navigation']) ? $options['eventful_navigation'] : '';

$navigation        = 'true';
$navigation_mobile = 'true';
switch ($_navigation) {
	case 'show':
		$navigation        = 'true';
		$navigation_mobile = 'true';
		break;
	case 'hide':
		$navigation        = 'false';
		$navigation_mobile = 'false';
		break;
	case 'hide_on_mobile':
		$navigation        = 'true';
		$navigation_mobile = 'false';
		break;
}
$nav_hide_on_mobile = '';
if ($navigation_mobile === 'false') {
	$nav_hide_on_mobile = 'none';
}

$navigation_icons      = isset($options['navigation_icons']) ? $options['navigation_icons'] : 'icofont-rounded-right';
$carousel_nav_position = isset($options['eventful_carousel_nav_position']) ? $options['eventful_carousel_nav_position'] : 'top_right';
if ('hide' !== $_navigation && 'hide_on_mobile' !== $_navigation && 'ticker' !== $carousel_mode) {
	if ($carousel_nav_position === 'top_right' || $carousel_nav_position === 'top_center' || $carousel_nav_position === 'top_left') {
		$ta_eventful_class .= ' pt_60';
	}
}

if ('hide_on_mobile' == $_navigation && 'ticker' !== $carousel_mode) {
	if ($carousel_nav_position === 'top_right' || $carousel_nav_position === 'top_center' || $carousel_nav_position === 'top_left') {
		$ta_eventful_class .= ' pt_md_60';
	}
}

if ('hide_on_mobile' === $_navigation) {
	$ta_eventful_class .= ' navigation_hide_on_mobile';
}
// Pagination Settings.
$_pagination = isset($options['eventful_pagination']) ? $options['eventful_pagination'] : '';
$pagination        = 'true';
$pagination_mobile = 'true';
switch ($_pagination) {
	case 'show':
		$pagination        = 'true';
		$pagination_mobile = 'true';
		break;
	case 'hide':
		$pagination        = 'false';
		$pagination_mobile = 'false';
		break;
	case 'hide_on_mobile':
		$pagination        = 'true';
		$pagination_mobile = 'false';
		break;
}

$dynamic_bullets    = (isset($options['eventful_dynamicBullets']) && ($options['eventful_dynamicBullets'])) ? 'true' : 'false';
$bullet_types       = (isset($options['bullet_types'])) ? $options['bullet_types'] : '';
$eventful_accessibility  = (isset($options['eventful_accessibility']) && ($options['eventful_accessibility'])) ? 'true' : 'false';
$touch_swipe        = (isset($options['touch_swipe']) && ($options['touch_swipe'])) ? 'true' : 'false';
$slider_draggable   = (isset($options['slider_draggable']) && ($options['slider_draggable'])) ? 'true' : 'false';
$slider_mouse_wheel = (isset($options['slider_mouse_wheel']) && ($options['slider_mouse_wheel'])) ? 'true' : 'false';
$center_mode        = 'false';
if ('center' === $carousel_mode) {
	$center_mode = 'true';
}

$carousel_pagination_color         = isset($g_option['carousel_pagination_color']) ? $g_option['carousel_pagination_color'] : array();
$pagination_color         = isset($carousel_pagination_color['color']) ? $carousel_pagination_color['color'] : '#cccccc';
$pagination_active_color  = isset($carousel_pagination_color['active-color']) ? $carousel_pagination_color['active-color'] : '#222222';

$_pagination_color_set         = isset($options['eventful_pagination_color_set']) ? $options['eventful_pagination_color_set'] : '';
$_pagination_colors            = isset($_pagination_color_set['eventful_pagination_color']) ? $_pagination_color_set['eventful_pagination_color'] : '';
$pagination_color              = !empty($_pagination_colors['color']) ? $_pagination_colors['color'] : $pagination_color;
$pagination_color_active       = !empty($_pagination_colors['active-color']) ? $_pagination_colors['active-color'] : $pagination_active_color;

$_nav_icon_radius              = EventfulFunctions::eventful_metabox_value(
	'navigation_icons_border_radius',
	$options,
	array(
		'all'  => '0',
		'unit' => 'px',
	)
);

$nav_icon_radius_all = !empty($_nav_icon_radius['all']) ? $_nav_icon_radius['all'] : '0';
$nav_icon_radius_unit = !empty($_nav_icon_radius['unit']) ? $_nav_icon_radius['unit'] : 'px';
$nav_icon_radius = "{$nav_icon_radius_all}{$nav_icon_radius_unit}";

$carousel_navigation_colors = isset($g_option['carousel_navigation_colors']) ? $g_option['carousel_navigation_colors'] : '';
$navigation_color = isset($carousel_navigation_colors['color']) ? $carousel_navigation_colors['color'] : '#aaa';
$navigation_hover_color = isset($carousel_navigation_colors['hover-color']) ? $carousel_navigation_colors['hover-color'] : '#fff';
$navigation_bg = isset($carousel_navigation_colors['bg']) ? $carousel_navigation_colors['bg'] : '#fff';
$navigation_hover_bg = isset($carousel_navigation_colors['hover-bg']) ? $carousel_navigation_colors['hover-bg'] : '#222222';
$navigation_border = isset($carousel_navigation_colors['border-color']) ? $carousel_navigation_colors['border-color'] : '#aaa';
$navigation_hover_border = isset($carousel_navigation_colors['hover-border-color']) ? $carousel_navigation_colors['hover-border-color'] : '#222222';

$_nav_colors                   = isset($options['eventful_nav_colors']) ? $options['eventful_nav_colors'] : '';
$nav_color                     = !empty($_nav_colors['color']) ? $_nav_colors['color'] : $navigation_color;
$nav_color_hover               = !empty($_nav_colors['hover-color']) ? $_nav_colors['hover-color'] : $navigation_hover_color;
$nav_color_bg                  = !empty($_nav_colors['bg']) ? $_nav_colors['bg'] : $navigation_bg;
$nav_color_bg_hover            = !empty($_nav_colors['hover-bg']) ? $_nav_colors['hover-bg'] : $navigation_hover_bg;
$nav_color_border              = !empty($_nav_colors['border-color']) ? $_nav_colors['border-color'] : $navigation_border;
$nav_color_hover_border        = !empty($_nav_colors['hover-border-color']) ? $_nav_colors['hover-border-color'] : $navigation_hover_border;
$nav_icon_size                 = EventfulFunctions::eventful_metabox_value('eventful_nav_icon_size', $options);
?>
<!-- Markup Starts -->
<div id="eventful_<?php echo esc_html($eventful_gl_id); ?>" class="<?php self::eventful_wrapper_classes($options, $layout_preset, $eventful_gl_id, $item_same_height_class); ?> <?php echo esc_html($carousel_mode); ?>" <?php self::wrapper_data($pagination_type, $pagination_type_mobile, $eventful_gl_id); ?> data-sid="<?php echo esc_html($eventful_gl_id); ?>">
	<?php
	EventfulLoopHtml::eventful_section_title($options, $section_title, $show_section_title);
	EventfulLoopHtml::eventful_preloader($show_preloader);

	require EventfulFunctions::eventful_locate_template('filter-bar.php'); ?>
	<div class="eventful event_<?php echo esc_attr($layout_preset); echo $carousel_nav_position === 'vertically_center_outer' ? 'vertically_center_outer' : '' ?>" style="<?php echo esc_attr($item_style_var) ?>">
		<div style="--nav-position:<?php echo esc_attr($eventful_nav_position); ?>" id="ta-eventful-id-<?php echo esc_html($eventful_gl_id); ?>" class="swiper swiper-container ta-eventful-carousel <?php echo esc_html($carousel_nav_position . $ta_eventful_class); ?>" dir="<?php echo esc_html($carousel_direction); ?>" data-carousel='{"mode":"<?php echo esc_html($carousel_mode); ?>", "speed":<?php echo esc_html($carousel_speed); ?>, "ticker_speed":<?php echo esc_html($ticker_speed); ?>, "ticker_width":<?php echo esc_html($ticker_slide_width); ?>, "items":<?php echo esc_html($column_lg_desktop); ?>, "spaceBetween":<?php echo esc_html($margin_between_event); ?>, "navigation":<?php echo esc_html($navigation); ?>, "pagination": <?php echo esc_html($pagination); ?>, "autoplay": <?php echo esc_html($carousel_autoplay); ?>, "autoplay_speed": <?php echo esc_html($autoplay_speed); ?>, "loop": <?php echo esc_html($infinite_loop); ?>, "autoHeight": <?php echo esc_html($carousel_auto_height); ?>, "lazy":  <?php echo esc_html($lazy_load); ?>, "effect": "slide", "simulateTouch": <?php echo esc_html($slider_draggable); ?>, "slider_mouse_wheel": <?php echo esc_html($slider_mouse_wheel); ?>, "allowTouchMove": <?php echo esc_html($touch_swipe); ?>, "dynamicBullets": <?php echo esc_html($dynamic_bullets); ?>, "bullet_types": "<?php echo esc_html($bullet_types); ?>", "center_mode": <?php echo esc_html($center_mode); ?>, "slidesRow": {"lg_desktop": <?php echo esc_html($carousel_row['lg_desktop']); ?>, "desktop": <?php echo esc_html($carousel_row['desktop']); ?>, "tablet": <?php echo esc_html($carousel_row['tablet']); ?>, "mobile_landscape": <?php echo esc_html($carousel_row['mobile_landscape']); ?>, "mobile": <?php echo esc_html($carousel_row['mobile']); ?>}, "responsive": {"lg_desktop": <?php echo esc_html($desktop_screen_size); ?>, "desktop": <?php echo esc_html($tablet_screen_size); ?>, "tablet": <?php echo esc_html($mobile_land_screen_size); ?>, "mobile_landscape": <?php echo esc_html($mobile_screen_size); ?>}, "slidesPerView": {"lg_desktop": <?php echo esc_html($column_lg_desktop); ?>, "desktop": <?php echo esc_html($column_desktop); ?>, "tablet": <?php echo esc_html($column_tablet); ?>, "mobile_landscape": <?php echo esc_html($column_mobile_landscape); ?>, "mobile": <?php echo esc_html($column_mobile); ?>}, "slideToScroll": {"lg_desktop": <?php echo esc_html($_slides_to_scroll['lg_desktop']); ?>, "desktop": <?php echo esc_html($_slides_to_scroll['desktop']); ?>, "tablet": <?php echo esc_html($_slides_to_scroll['tablet']); ?>, "mobile_landscape": <?php echo esc_html($_slides_to_scroll['mobile_landscape']); ?>, "mobile": <?php echo esc_html($_slides_to_scroll['mobile']); ?> }, "navigation_mobile": <?php echo esc_html($navigation_mobile); ?>, "pagination_mobile": <?php echo esc_html($pagination_mobile); ?>, "stop_onHover": <?php echo esc_html($pause_hover); ?>, "enabled": <?php echo esc_html($is_carousel_accessibility); ?>, "prevSlideMessage": "<?php echo esc_html($accessibility_prev_slide_text); ?>", "nextSlideMessage": "<?php echo esc_html($accessibility_next_slide_text); ?>", "firstSlideMessage": "<?php echo esc_html($accessibility_first_slide_text); ?>", "lastSlideMessage": "<?php echo esc_html($accessibility_last_slide_text); ?>","keyboard": "<?php echo esc_html($eventful_accessibility); ?>", "paginationBulletMessage": "<?php echo esc_html($accessibility_pagination_bullet_text); ?>" }'>
			<div class="swiper-wrapper">
				<?php self::eventful_get_posts($options, $layout_preset, $event_content_sorter, $eventful_query, $eventful_gl_id); ?>
			</div>
			<?php
			if ('true' === $pagination && 'ticker' !== $carousel_mode) {
			?>
				<div class="eventful-pagination swiper-pagination <?php echo esc_html($bullet_types); ?>"
					style="
						--pagination_color: <?php echo esc_attr($pagination_color); ?>;
						--pagination_color_active: <?php echo esc_attr($pagination_color_active); ?>;
					">
				</div>
			<?php
			}
			if ('true' === $navigation && 'ticker' !== $carousel_mode) { ?>
				<div
					style="
					--nav_hide_on_mobile:<?php echo esc_attr($nav_hide_on_mobile); ?>;
					--nav_icon_radius:<?php echo esc_attr($nav_icon_radius); ?>;
					--nav_color:<?php echo esc_attr($nav_color); ?>;
					--nav_color_hover:<?php echo esc_attr($nav_color_hover); ?>;
					--nav_color_bg:<?php echo esc_attr($nav_color_bg); ?>;
					--nav_color_bg_hover:<?php echo esc_attr($nav_color_bg_hover); ?>;
					--nav_color_border:<?php echo esc_attr($nav_color_border); ?>;
					--nav_color_hover_border:<?php echo esc_attr($nav_color_hover_border); ?>;
					--nav_icon_size: <?php echo esc_attr($nav_icon_size); ?>px;
					"
					class="eventful-button-next swiper-button-next <?php echo esc_html($carousel_nav_position); ?>"><i class="<?php echo esc_html($navigation_icons); ?>-right"></i></div>
				<div
					style="
					--nav_hide_on_mobile:<?php echo esc_attr($nav_hide_on_mobile); ?>;
					--nav_icon_radius:<?php echo esc_attr($nav_icon_radius); ?>;
					--nav_color:<?php echo esc_attr($nav_color); ?>;
					--nav_color_hover:<?php echo esc_attr($nav_color_hover); ?>;
					--nav_color_bg:<?php echo esc_attr($nav_color_bg); ?>;
					--nav_color_bg_hover:<?php echo esc_attr($nav_color_bg_hover); ?>;
					--nav_color_border:<?php echo esc_attr($nav_color_border); ?>;
					--nav_color_hover_border:<?php echo esc_attr($nav_color_hover_border); ?>;
					--nav_icon_size: <?php echo esc_attr($nav_icon_size); ?>px;
					"
					class="eventful-button-prev swiper-button-prev <?php echo esc_html($carousel_nav_position); ?>"><i class="<?php echo esc_html($navigation_icons); ?>-left"></i></div><?php } ?>
		</div>
	</div>
</div>