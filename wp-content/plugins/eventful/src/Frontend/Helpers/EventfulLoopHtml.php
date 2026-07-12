<?php

namespace ThemeAtelier\Eventful\Frontend\Helpers;

use ThemeAtelier\Eventful\Frontend\Helpers\EventfulFunctions;
use ThemeAtelier\Eventful\Frontend\Helpers\EventfulQueryInside;

/**
 * The file of query insides.
 *
 * @package Eventful
 * @subpackage Eventful/public
 *
 * @since 2.0.0
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}
/**
 * Event all html method.
 *
 * @since 2.0.0
 */
class EventfulLoopHtml
{
	/**
	 * Event title html.
	 *
	 * @param string $layout layout preset.
	 * @return void
	 */
	public static function eventful_event_title($sorter, $layout, $options, $event)
	{
		ob_start();
		include EventfulFunctions::eventful_locate_template('item/title.php');
		$title = apply_filters('eventful_item_title', ob_get_clean());
		echo wp_kses_post($title);
	}

	/**
	 * Show Event Content html.
	 *
	 * @param array $options options.
	 * @return void
	 */
	public static function eventful_content_html($sorter, $options, $event)
	{
		ob_start();
		include EventfulFunctions::eventful_locate_template('item/content.php');
		$description = apply_filters('eventful_item_description', ob_get_clean());
		echo wp_kses_post($description);
	}

	/**
	 * Read more function
	 *
	 * @param array $content_type The content type.
	 * @param array $options The parent of this field.
	 */
	public static function eventful_readmore($event_content_setting, $options, $content_type, $event)
	{
		ob_start();
		include EventfulFunctions::eventful_locate_template('item/read-more.php');
		$read_more_button = apply_filters('eventful_read_more_btn', ob_get_clean(), $link = get_permalink($event));
		echo wp_kses_post($read_more_button);
	}

	/**
	 * Event thumb HTML.
	 *
	 * @param int   $scode_id Shortcode ID.
	 * @param int   $slide_id The slide/post ID.
	 * @param array $options The slide/post ID.
	 * @return void
	 */
	public static function eventful_event_thumb_html($sorter, $scode_id, $event, $options, $layout)
	{
		ob_start();
		include EventfulFunctions::eventful_locate_template('item/thumbnail.php');
		$item_thumb = apply_filters('eventful_item_thumbnail', ob_get_clean());
		echo $item_thumb; // phpcs:ignore
	}

	/**
	 * Event Social Html
	 *
	 * @param array  $field_id event content option array.
	 * @param array  $options options.
	 * @param object $event event.
	 * @return void
	 */
	public static function eventful_social_share_html($sorter, $options, $event)
	{
		ob_start();
		include EventfulFunctions::eventful_locate_template('item/social-share.php');
		$social_share = apply_filters('eventful_item_social_share', ob_get_clean());
		echo wp_kses_post($social_share);
	}

	/**
	 * Event fildes HTML
	 *
	 * @return void
	 */
	public static function eventful_event_meta_html($sorter, $options, $event)
	{
		$eventful_event_meta 	= EventfulFunctions::eventful_metabox_value('eventful_event_meta', $sorter);
		$event_fildes_fields 	= EventfulFunctions::eventful_metabox_value('eventful_event_fildes_group', $eventful_event_meta);
		$show_event_fildes   	= EventfulFunctions::eventful_metabox_value('show_event_fildes', $eventful_event_meta);
		$event_meta_separator  = EventfulFunctions::eventful_metabox_value('event_meta_separator', $eventful_event_meta);

		if ($event_fildes_fields && $show_event_fildes) {
			ob_start();
			include EventfulFunctions::eventful_locate_template('item/event-meta.php');
			$item_meta = apply_filters('eventful_item_meta', ob_get_clean());
			echo wp_kses_post( $item_meta );
		}
	}

	/**
	 * Event content with thumb.
	 *
	 * @param string $layout Layout preset.
	 * @param int    $scode_id The Shortcode ID.
	 * @param object $event The Event object.
	 * @return void
	 */
	public static function eventful_post_content_with_thumb($sorter, $layout, $scode_id, $event, $options)
	{
		$eventful_event_meta 	= EventfulFunctions::eventful_metabox_value('eventful_event_meta', $sorter);
		$event_fildes_fields 	= EventfulFunctions::eventful_metabox_value('eventful_event_fildes_group', $eventful_event_meta);
		$show_event_fildes   	= EventfulFunctions::eventful_metabox_value('show_event_fildes', $eventful_event_meta);
		$eventful_event_social_share   = EventfulFunctions::eventful_metabox_value('eventful_event_social_share', $sorter);
		$show_social_media    = EventfulFunctions::eventful_metabox_value('show_social_media', $eventful_event_social_share);
		if ($sorter) {
			foreach ($sorter as $style_key => $style_value) {
				switch ($style_key) {
					case 'eventful_event_thumb':
						self::eventful_event_thumb_html($sorter, $scode_id, $event, $options, $layout);
						break;
					case 'eventful_event_title':
						self::eventful_event_title($sorter, $layout, $options, $event);
						break;
					case 'eventful_event_content':
						self::eventful_content_html($sorter, $options, $event);
						break;
					case 'eventful_event_meta':
						if ($event_fildes_fields && $show_event_fildes) {
							self::eventful_event_meta_html($sorter, $options, $event);
						}
						break;
					case 'eventful_event_social_share':
						if ($show_social_media) {
							self::eventful_social_share_html($sorter, $options, $event);
						}
						break;
				}
			}
		}
	}

	/**
	 * Event content without thumb.
	 *
	 * @param string $layout Layout preset.
	 * @param object $event visitor number.
	 * @param array  $options Shortcode options.
	 * @return void
	 */
	public static function eventful_post_content_without_thumb($sorter, $layout, $scode_id, $event, $options)
	{
		$eventful_event_meta 	= EventfulFunctions::eventful_metabox_value('eventful_event_meta', $sorter);
		$event_fildes_fields 	= EventfulFunctions::eventful_metabox_value('eventful_event_fildes_group', $eventful_event_meta);
		$show_event_fildes   	= EventfulFunctions::eventful_metabox_value('show_event_fildes', $eventful_event_meta);
		$eventful_event_social_share   = EventfulFunctions::eventful_metabox_value('eventful_event_social_share', $sorter);
		$show_social_media    = EventfulFunctions::eventful_metabox_value('show_social_media', $eventful_event_social_share);
		$eventful_custom_fields   = EventfulFunctions::eventful_metabox_value('eventful_custom_fields', $sorter);
		$show_custom_field    = EventfulFunctions::eventful_metabox_value('show_custom_field', $eventful_custom_fields);
		if ($sorter) {
			foreach ($sorter as $style_key => $style_value) {
				switch ($style_key) {
					case 'eventful_event_title':
						self::eventful_event_title($sorter, $layout, $options, $event);
						break;
					case 'eventful_event_content':
						self::eventful_content_html($sorter, $options, $event);
						break;
					case 'eventful_event_meta':
						if ($event_fildes_fields && $show_event_fildes) {
							self::eventful_event_meta_html($sorter, $options, $event);
						}
						break;
					case 'eventful_event_social_share':
						if ($show_social_media) {
							self::eventful_social_share_html($sorter, $options, $event);
						}
						break;
				}
			}
		}
	}

	/**
	 * Pagination function
	 *
	 * @param object $loop Query array.
	 * @param array  $layout layout.
	 * @param array  $views_id id.
	 * @param array  $paged paged.
	 * @param array  $on_screen screen type.
	 */
	public static function eventful_pagination_bar($events_found, $options, $layout, $views_id, $paged = null, $on_screen = null)
	{
		$event_offset   = 0;
		$events_found   = (int) $events_found - (int) $event_offset;
		$event_limit    = isset($options['eventful_event_limit']) ? $options['eventful_event_limit'] : 100000;
		$event_limit    = ($event_limit > 0 && $events_found > $event_limit) ? $event_limit : $events_found;
		$event_per_page = isset($options['post_per_page']) ? $options['post_per_page'] : 12;
		$event_per_page = ($event_per_page > $event_limit) ? $event_limit : $event_per_page;

		$layout_preset = isset($layout['eventful_layout_preset']) ? $layout['eventful_layout_preset'] : '';
		// Event display settings.
		if ('filter_layout' === $layout_preset) {
			$pagination_type = isset($options['filter_pagination_type']) ? $options['filter_pagination_type'] : '';
		} else {
			$pagination_type = isset($options['event_pagination_type']) ? $options['event_pagination_type'] : 'ajax_load_more';
			if ('on_mobile' === $on_screen) {
				$pagination_type = isset($options['event_pagination_type_mobile']) ? $options['event_pagination_type_mobile'] : 'infinite_scroll';
			}
		}
		$event_limit = (int) $event_limit;
		if ($event_limit < 1) {
			$pages = 0;
		} else {
			$pages = EventfulFunctions::eventful_max_pages($event_limit, $event_per_page);
		}
		$big = 999999999; // need an unlikely integer.
		if ($pages > 1) {
			$page_current     = max(1, get_query_var('paged'));
			$filter_url_value = isset($_SERVER['QUERY_STRING']) ? wp_unslash($_SERVER['QUERY_STRING']) : ''; //phpcs:ignore
			if (!empty($filter_url_value)) {
				$shortcode_id = isset($_GET['efp']) ? wp_unslash(sanitize_text_field($_GET['efp'])) : ''; //phpcs:ignore
				if ($shortcode_id == $views_id) {
					$eventful_page = isset($_GET['eventful_page']) ? wp_unslash(sanitize_text_field($_GET['eventful_page'])) : ''; //phpcs:ignore
					if (!empty($eventful_page)) {
						$page_current = $eventful_page;
					}
				}
			}

			$page_links = paginate_links(
				array(
					'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
					'format'    => '?paged=%#%',
					'current'   => $page_current,
					'total'     => $pages,
					'show_all'  => true,
					'prev_next' => false,
					'type'      => 'array',
					'prev_text' => '<i class="icofont-rounded-left"></i>',
					'next_text' => '<i class="icofont-rounded-right"></i>',
				)
			);
			$html       = '';
			$p_num      = 1;
			foreach ($page_links as $link) {
				$class = 'page-numbers ';
				if (strpos($link, 'current') !== false) {
					$class .= 'active';
				}
				if (strpos($link, 'next') !== false) {
					$data_page = 'data-page="next"';
					$class    .= 'eventful_next ';
				} elseif (strpos($link, 'prev') !== false) {
					$data_page = 'data-page="prev"';
					$class    .= 'eventful_prev active';
				} else {
					$data_page = 'data-page="' . $p_num . '"';
				}
				$link  = preg_replace('/<span[^>]*>/', '<a href="#" class="' . $class . '" ' . $data_page . '>', $link);
				$link  = preg_replace('/<a [^>]*>/', '<a href="#" class="' . $class . '" ' . $data_page . '>', $link);
				$link  = str_replace('</span>', '</a>', $link);
				$html .= $link;
				$p_num++;
			}
			echo wp_kses_post($html); //phpcs:ignore
		}
	}

	/**
	 * Section title
	 *
	 * @param int $eventful_id Shortcode id.
	 * @return void
	 */

	public static function eventful_section_title($options, $section_title_text, $show_section_title)
	{
		if ($show_section_title) {
			$section_title_text = apply_filters('eventful_section_title_text', $section_title_text);
			ob_start();
			do_action('eventful_before_section_title');
			include EventfulFunctions::eventful_locate_template('section-title.php');
			do_action('eventful_after_section_title');
			$section_title = apply_filters('eventful_filter_section_title', ob_get_clean());
			echo wp_kses_post($section_title);
		}
	}

	/**
	 * Preloader
	 *
	 * @param bool $preloader show preloader.
	 * @return void
	 */
	public static function eventful_preloader($preloader)
	{
		if ($preloader) {
			ob_start();
			include EventfulFunctions::eventful_locate_template('preloader.php');
			$preloader = apply_filters('eventful_preloader', ob_get_clean());
			echo wp_kses_post($preloader);
		}
	}

	/**
	 * Get all query posts.
	 *
	 * @param array $options Views options.
	 * @param array $layout Layout preset.
	 * @param object $eventful_query post query.
	 * @param int   $view_id Shortcode ID.
	 * @return void
	 */
	public static function eventful_get_posts($options, $layout, $sorter, $eventful_query, $view_id, $start_count = 1)
	{
		$eventful_count            = (int) $start_count > 0 ? (int) $start_count : 1;
		$timeline_show_month_sep   = ('timeline' === $layout) ? (isset($options['timeline_show_month_separator']) ? $options['timeline_show_month_separator'] : '') : '';
		$vertical_timeline_style   = isset($options['vertical_timeline_style']) ? $options['vertical_timeline_style'] : 'style_01';
		$show_separator            = ('timeline' === $layout && !empty($timeline_show_month_sep));
		$prev_month_year           = '';

		foreach ($eventful_query as $key => $event) {
			if ($show_separator) {
				$current_month_year = tribe_get_start_date($event->ID, false, 'M Y');
				if ($current_month_year !== $prev_month_year) {
					echo '<div class="timeline-month-separator"><span>' . esc_html($current_month_year) . '</span></div>';
					$prev_month_year = $current_month_year;
					if (in_array($vertical_timeline_style, array('style_02', 'style_03'), true)) {
						$eventful_count = 1;
					}
				}
			}
			self::eventful_post_loop($options, $layout, $sorter, $eventful_count, $view_id, $event);
			$eventful_count++;
		}
	}

	/**
	 * Event responsive columns class.
	 *
	 * @param string $layout Layout preset.
	 * @param string $columns Columns number.
	 * @return string
	 */
	public static function eventful_post_responsive_columns($layout, $columns)
	{
		$column_lg_desktop = !empty($columns['lg_desktop']) ? $columns['lg_desktop'] : '3';
		$column_desktop = !empty($columns['desktop']) ? $columns['desktop'] : '3';
		$column_tablet = !empty($columns['tablet']) ? $columns['tablet'] : '2';
		$column_mobile_landscape = !empty($columns['mobile_landscape']) ? $columns['mobile_landscape'] : '1';
		$column_mobile = !empty($columns['mobile']) ? $columns['mobile'] : '1';

		$eventful_post_columns = '';
		if ('carousel_layout' === $layout || 'slider' === $layout) {
			$eventful_post_columns .= 'swiper-slide swiper-lazy eventful__carousel_item';
		} elseif ('minimal_list' === $layout) {
			$eventful_post_columns = 'ta-col-xs-1 minimal_list';
		} elseif ('timeline' === $layout) {
			$eventful_post_columns = 'ta-col-xs-1 timeline_layout';
		} else {
			$eventful_post_columns .= " ta-col-xs-$column_mobile ta-col-sm-$column_mobile_landscape ta-col-md-$column_tablet ta-col-lg-$column_desktop ta-col-xl-$column_lg_desktop";
		}
		return $eventful_post_columns;
	}

	/**
	 * Event Loop.
	 *
	 * @param array  $options Views options.
	 * @param string $layout Layout preset.
	 * @param int    $scode_id Shortcode ID.
	 * @return void
	 */
	public static function eventful_post_loop($options, $layout, $sorter, $eventful_count, $scode_id, $event)
	{
		$number_of_columns = EventfulFunctions::eventful_metabox_value('eventful_number_of_columns', $options);
		$template_style         = isset($options['template_style']) ? $options['template_style'] : 'custom';
		$theme_style         = isset($options['theme_style']) ? $options['theme_style'] : 'theme-one';
		$theme_style_minimal_list         = isset($options['theme_style_minimal_list']) ? $options['theme_style_minimal_list'] : 'theme-one';

		$list_template         = isset($options['list_template']) ? $options['list_template'] : 'left_thumb';
		$_event_thumb_setting = EventfulFunctions::eventful_metabox_value('eventful_event_thumb', $sorter);
		$event_thumb_zoom        = isset($_event_thumb_setting['event_thumb_zoom']) ? $_event_thumb_setting['event_thumb_zoom'] : 'none';
		$event_thumb_gray_scale        = isset($_event_thumb_setting['event_thumb_gray_scale']) ? $_event_thumb_setting['event_thumb_gray_scale'] : 'none';

		// Items main class
		$item_wrapper_class = "eventful__item eventful-item-{$event->ID}";
		$is_featured = get_post_meta($event->ID, '_tribe_featured', true);
		if ($is_featured) {
			$item_wrapper_class .= " eventful_featured";
		}
		if ($event_thumb_zoom != "none") {
			$item_wrapper_class .= " {$event_thumb_zoom}";
		}
		if ($event_thumb_gray_scale != "none") {
			$item_wrapper_class .= " {$event_thumb_gray_scale}";
		}

		if (('carousel_layout' === $layout || 'slider' === $layout || 'grid_layout' === $layout)) {
			echo '<div class="' . esc_attr(self::eventful_post_responsive_columns($layout, $number_of_columns)) . '">';
			if ('custom' === $template_style) {
				echo '<div class="' . esc_attr($item_wrapper_class) . '" data-id="' . esc_attr($event->ID) . '">';
				self::eventful_post_content_with_thumb($sorter, $layout, $scode_id, $event, $options);
				echo '</div>';
			} else {
				if ($theme_style) {
					include EventfulFunctions::eventful_locate_template('theme/grid/' . esc_attr($theme_style) . '.php');
				}
			}
			echo '</div>';
		} else if ('minimal_list' === $layout) {
			echo '<div class="' . esc_attr(self::eventful_post_responsive_columns($layout, $number_of_columns)) . '">';
			if ('custom' === $template_style) {
				echo '<div class="' . esc_attr($item_wrapper_class) . ' ' . esc_attr($list_template) . '" data-id="' . esc_attr($event->ID) . '">';
				self::eventful_event_thumb_html($sorter, $scode_id, $event, $options, $layout);
				echo '<div class="eventful__item__details minimal_list">';
				self::eventful_post_content_without_thumb($sorter, $layout, $scode_id, $event, $options);
				echo '</div></div>';
			} else {
				include EventfulFunctions::eventful_locate_template('theme/minimal-list/' . esc_attr($theme_style_minimal_list) . '.php');
			}
			echo '</div>';
		} else if ('timeline' === $layout) {
			$position_class = ($eventful_count % 2 !== 0) ? 'timeline-left' : 'timeline-right';
			echo '<div class="' . esc_attr(self::eventful_post_responsive_columns($layout, $number_of_columns) . ' ' . $position_class) . '">';
			if ('custom' === $template_style) {
				echo '<div class="' . esc_attr($item_wrapper_class) . '" data-id="' . esc_attr($event->ID) . '">';
				self::eventful_post_content_with_thumb($sorter, $layout, $scode_id, $event, $options);
				echo '</div>';
			} else {
				if ($theme_style) {
					include EventfulFunctions::eventful_locate_template('theme/grid/' . esc_attr($theme_style) . '.php');
				}
			}
			echo '</div>';
		}
	}

	/**
	 * EFP shortcode markup wrapper classes.
	 *
	 * @param string $layout_preset The selected layout name.
	 * @param int    $shortcode_id The shortcode ID.
	 *
	 */
	public static function eventful_wrapper_classes($options, $layout_preset, $eventful_gl_id, $item_same_height_class = '')
	{
		$wrapper_class = "ta-eventful-section ta-container eventful-wrapper-{$eventful_gl_id}";
		switch ($layout_preset) {
			case 'carousel_layout':
				$wrapper_class .= " eventful__carousel_wrapper{$item_same_height_class}";
				break;
			case 'slider':
				$wrapper_class .= " eventful__carousel_wrapper{$item_same_height_class}";
				break;
			case 'grid_layout':
				$wrapper_class .= $item_same_height_class;
				break;
			case 'timeline':
				$wrapper_class .= $item_same_height_class;
				break;
		}
		echo esc_attr($wrapper_class);
	}

	/**
	 * Shortcode Wrapper data attributes.
	 *
	 * @param int    $shortcode_id The shortcode ID.
	 * @return void
	 */
	public static function wrapper_data($pagination_type, $pagination_type_mobile, $shortcode_id)
	{
		$wrapper_data = '';
		if ($pagination_type) {
			$wrapper_data .= " data-pagination={$pagination_type}";
		}
		if ($pagination_type_mobile) {
			$wrapper_data .= " data-pagination_mobile={$pagination_type_mobile}";
		}
		if ($shortcode_id) {
			$wrapper_data .= " data-sid={$shortcode_id}";
		}
		echo esc_html($wrapper_data);
	}
	/**
	 * Full html show.
	 *
	 * @param array  $options all options.
	 * @param array  $layout show layout.
	 * @param array  $eventful_gl_id Shortcode ID.
	 * @param array  $section_title section title.
	 * @param object $query layout query.
	 */
	public static function eventful_html_show($options, $layout, $eventful_gl_id, $section_title = '', $query = array())
	{
		$layout_preset        = isset($layout['eventful_layout_preset']) ? $layout['eventful_layout_preset'] : 'carousel_layout';
		$g_option         = get_option('eventful_settings');
		$event_content_sorter  = isset($options['event_content_sorter']) ? $options['event_content_sorter'] : '';
		$show_section_title   = isset($options['section_title']) ? $options['section_title'] : false;
		$margin_between_event  = isset($options['margin_between_event']['all']) ? $options['margin_between_event']['all'] : '';
		$show_preloader       = isset($options['show_preloader']) ? $options['show_preloader'] : 0;

		$item_same_height_class = isset($options['item_same_height']) && $options['item_same_height'] ? ' eventful_same_height ' : '';

		if (is_object($query) && !empty($query)) {
			$query_args       = array();
			$eventful_query        = $query;
			$events_found = $eventful_query->post_count;
		} else {
			$query_args       = EventfulQueryInside::get_filtered_content($options, $eventful_gl_id, $layout_preset);
			$eventful_query        = tribe_get_events($query_args);
			$query_args['posts_per_page'] = -1;
			$events_found = count(tribe_get_events($query_args));
		}
		// Pagination.
		$show_pagination = isset($options['show_post_pagination']) ? $options['show_post_pagination'] : true;
		if ('filter_layout' === $layout_preset) {
			$pagination_type        = isset($options['filter_pagination_type']) ? $options['filter_pagination_type'] : '';
			$pagination_type_mobile = isset($options['filter_pagination_type']) ? $options['filter_pagination_type'] : '';
		} else {
			$pagination_type        = isset($options['event_pagination_type']) ? $options['event_pagination_type'] : 'ajax_load_more';
			$pagination_type_mobile = isset($options['event_pagination_type_mobile']) ? $options['event_pagination_type_mobile'] : 'infinite_scroll';
		}
		$advanced_filter = isset($options['eventful_advanced_filter']) ? $options['eventful_advanced_filter'] : false;
		$eventful_icofont_css = isset($g_option['eventful_icofont_css']) ? $g_option['eventful_icofont_css'] : true;
		if ($eventful_icofont_css) {
			wp_enqueue_style('eventful-icofont');
		}
		wp_enqueue_style('eventful-grid');
		wp_enqueue_style('eventful-style');

		global $eventful_google_fonts;
		if (! isset($eventful_google_fonts)) {
			$eventful_google_fonts = array();
		}

		$section_title_typography = isset($options['section_title_typography']) ? $options['section_title_typography'] : array();
		$event_title_typography   = isset($options['event_title_typography']) ? $options['event_title_typography'] : array();
		$event_content_typography = isset($options['event_content_typography']) ? $options['event_content_typography'] : array();
		$read_more_typography     = isset($options['read_more_typography']) ? $options['read_more_typography'] : array();

		$eventful_typography = array(
			$section_title_typography,
			$event_title_typography,
			$event_content_typography,
			$read_more_typography,
		);

		foreach ($eventful_typography as $font) {
			if (isset($font['font-family'], $font['type']) && 'google' === $font['type'] && ! empty($font['font-family'])) {
				$family  = str_replace(' ', '+', $font['font-family']);
				$variant = ! empty($font['font-weight']) ? ':' . $font['font-weight'] : '';
				$eventful_google_fonts[] = $family . $variant;
			}
		}

		wp_enqueue_script('eventful-script');
		$ta_eventful_lang = '';
		if (function_exists('pll_current_language')) {
			$ta_eventful_lang = pll_current_language();
		}

		$margin_between_event      = isset($options['margin_between_event']['all']) ? (int) $options['margin_between_event']['all'] : 20;
		$margin_between_event_half = $margin_between_event / 2;

		$event_inner_padding       = EventfulFunctions::eventful_metabox_value('eventful_event_inner_padding', $options);
		$event_inner_padding_top = isset($event_inner_padding['top']) ? $event_inner_padding['top'] : '0';
		$event_inner_padding_right = isset($event_inner_padding['right']) ? $event_inner_padding['right'] : '0';
		$event_inner_padding_bottom = isset($event_inner_padding['bottom']) ? $event_inner_padding['bottom'] : '0';
		$event_inner_padding_left = isset($event_inner_padding['left']) ? $event_inner_padding['left'] : '0';

		$event_inner_padding = "{$event_inner_padding_top}px {$event_inner_padding_right}px {$event_inner_padding_bottom}px {$event_inner_padding_left}px";

		$event_alignment         = isset($options['event_alignment']) ? $options['event_alignment'] : 'left';
		$content_template_style         = EventfulFunctions::eventful_metabox_value('content_template_style', $options);
		$eventful_event_background         = isset($options['eventful_event_background']) ? $options['eventful_event_background'] : 'transparent';
		$eventful_event_background_color         = isset($eventful_event_background['color']) ? $eventful_event_background['color'] : 'transparent';
		$eventful_event_background_hover_color         = isset($eventful_event_background['hover_color']) ? $eventful_event_background['hover_color'] : 'transparent';
		$eventful_featured_event_background         = isset($options['eventful_featured_event_background']) ? $options['eventful_featured_event_background'] : '';
		$eventful_featured_event_background_color = !empty($eventful_featured_event_background['color']) ? $eventful_featured_event_background['color'] : $eventful_event_background_color;
		$eventful_featured_event_background_hover_color = !empty($eventful_featured_event_background['hover_color']) ? $eventful_featured_event_background['hover_color'] : $eventful_event_background_hover_color;
		$show_eventful_event_box_shadow = EventfulFunctions::eventful_metabox_value('show_eventful_event_box_shadow', $options, false);
		$eventful_event_box_shadow_property = '';
		if ($show_eventful_event_box_shadow) {
			$eventful_event_box_shadow = EventfulFunctions::eventful_metabox_value(
				'eventful_event_box_shadow',
				$options,
				array(
					'horizontal' => '0',
					'vertical'   => '2',
					'blur'       => '8',
					'spread'     => '-2',
					'color'      => 'rgb(187, 187, 187)',
				)
			);
			$box_shadow_h             = EventfulFunctions::eventful_metabox_value('horizontal', $eventful_event_box_shadow);
			$box_shadow_v             = EventfulFunctions::eventful_metabox_value('vertical', $eventful_event_box_shadow);
			$box_shadow_blur          = EventfulFunctions::eventful_metabox_value('blur', $eventful_event_box_shadow);
			$box_shadow_spread        = EventfulFunctions::eventful_metabox_value('spread', $eventful_event_box_shadow);
			$box_shadow_color         = EventfulFunctions::eventful_metabox_value('color', $eventful_event_box_shadow);
			$box_shadow_style         = 'outset' === $eventful_event_box_shadow['style'] ? '' : $eventful_event_box_shadow['style'];
			$box_shadow_margin_top    = 'inset' === $box_shadow_style ? '0' : ($box_shadow_spread - $box_shadow_v + 0.5 * $box_shadow_blur);
			$box_shadow_margin_right  = 'inset' === $box_shadow_style ? '0' : ($box_shadow_spread + $box_shadow_h + 0.5 * $box_shadow_blur);
			$box_shadow_margin_bottom = 'inset' === $box_shadow_style ? '0' : ($box_shadow_spread + $box_shadow_v + 0.5 * $box_shadow_blur);
			$box_shadow_margin_left   = 'inset' === $box_shadow_style ? '0' : ($box_shadow_spread - $box_shadow_h + 0.5 * $box_shadow_blur);

			$event_item_box_shadow = "{$box_shadow_h}px {$box_shadow_v}px {$box_shadow_blur}px {$box_shadow_spread}px {$box_shadow_color} {$box_shadow_style}";
			$event_item_box_margin = "{$box_shadow_margin_top}px {$box_shadow_margin_right}px {$box_shadow_margin_bottom}px {$box_shadow_margin_left}px";
			$eventful_event_box_shadow_property = "--eventful_box_shadow: $event_item_box_shadow; --eventful_box_margin: $event_item_box_margin;";
		}
		$_eventful_event_border_radius         = EventfulFunctions::eventful_metabox_value(
			'eventful_event_border_radius',
			$options,
			array(
				'all' => '0',
			)
		);
		$eventful_event_border_radius_unit     = EventfulFunctions::eventful_metabox_value('unit', $_eventful_event_border_radius);
		$eventful_event_border_radius_length   = EventfulFunctions::eventful_metabox_value('all', $_eventful_event_border_radius);
		$eventful_event_border_radius = $eventful_event_border_radius_length > 0 ? $eventful_event_border_radius_length . $eventful_event_border_radius_unit : '0';

		$eventful_event_border     = EventfulFunctions::eventful_metabox_value(
			'eventful_event_border',
			$options,
			array(
				'all' => '0',
				'style' => 'solid',
				'color' => 'transparent',
			)
		);
		$all     = EventfulFunctions::eventful_metabox_value('all', $eventful_event_border);
		$style   = EventfulFunctions::eventful_metabox_value('style', $eventful_event_border);
		$color   = EventfulFunctions::eventful_metabox_value('color', $eventful_event_border);
		$eventful_event_border = "{$all}px {$style} {$color}";
		$item_style_var = "--eventful_event_border: $eventful_event_border;--eventful_event_border_radius: $eventful_event_border_radius;--event_inner_padding: $event_inner_padding;$eventful_event_box_shadow_property;--eventful_event_alignment: $event_alignment;";

		$item_style_var .= "--eventful_event_background_color: $eventful_event_background_color;--eventful_event_background_hover_color: $eventful_event_background_hover_color;--eventful_featured_event_background_color: $eventful_featured_event_background_color;--eventful_featured_event_background_hover_color: $eventful_featured_event_background_hover_color;--margin_between_event: {$margin_between_event}px;";

		if ('timeline' === $layout_preset) {
			$g_option = get_option('eventful_settings');
			$timeline_colors = isset($g_option['timeline_colors']) ? $g_option['timeline_colors'] : '';
			$timeline_colors_date_badge = !empty($timeline_colors['date_badge']) ? $timeline_colors['date_badge'] : '#1e1e2f';
			$timeline_colors_timeline_line = !empty($timeline_colors['timeline_line']) ? $timeline_colors['timeline_line'] : '#1e1e2f';
			$timeline_colors_event_dot = !empty($timeline_colors['event_dot']) ? $timeline_colors['event_dot'] : '#1e1e2f';
			print_r($timeline_colors);

			$timeline_date_badge_color = !empty($options['date_badge_color']) ? $options['date_badge_color'] : $timeline_colors_date_badge;
			$timeline_line_thickness   = EventfulFunctions::eventful_metabox_value('timeline_line_thickness', $options, array('all' => 5));
			$timeline_line_thickness   = !empty($timeline_line_thickness['all']) ? $timeline_line_thickness['all'] . 'px' : '5px';
			$timeline_line_color       = !empty($options['timeline_line_color']) ? $options['timeline_line_color'] : $timeline_colors_timeline_line;
			$timeline_event_dot_color  = !empty($options['event_dot_color']) ? $options['event_dot_color'] : $timeline_colors_event_dot;
			$item_style_var .= "--date_badge_color: {$timeline_date_badge_color};--timeline_line_thickness: {$timeline_line_thickness};--timeline_line_color: {$timeline_line_color};--event_dot_color: {$timeline_event_dot_color};";
		}

		if ($eventful_query) {
			if ('carousel_layout' === $layout_preset) {
				include EventfulFunctions::eventful_locate_template('carousel.php');
			} elseif ('slider' === $layout_preset) {
				include EventfulFunctions::eventful_locate_template('carousel.php');
			} elseif ('grid_layout' === $layout_preset) {
				include EventfulFunctions::eventful_locate_template('grid.php');
			} elseif ('minimal_list' === $layout_preset) {
				include EventfulFunctions::eventful_locate_template('list.php');
			} elseif ('timeline' === $layout_preset) {
				include EventfulFunctions::eventful_locate_template('timeline.php');
			} else {
				include EventfulFunctions::eventful_locate_template('carousel.php');
			}
		} else {
			echo '<div style="text-align: center;">' . esc_html__('No events found based on your criteria.', 'eventful') . '</div>';
		}
	}
}
