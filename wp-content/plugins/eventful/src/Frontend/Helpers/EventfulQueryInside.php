<?php

namespace ThemeAtelier\Eventful\Frontend\Helpers;

use ThemeAtelier\Eventful\Frontend\Helpers\EventfulFunctions;

/**
 * The file of query insides.
 *
 * @package Eventful
 * @subpackage public
 *
 * @since 2.2.0
 */

/**
 * The query inside class to process the query.
 *
 * @since 2.2.0
 */
class EventfulQueryInside
{

	/**
	 * The post ID.
	 *
	 * @var string post ID.
	 */

	/**
	 * Filtered content.
	 *
	 * @param integer $eventful_gl_id Shortcode ID.
	 * @return statement
	 */
	public static function get_filtered_content($view_options, $id = '', $layout_preset = 'default', $on_screen = null)
	{
		$eventful_post_type   = 'tribe_events';
		$hide_free_events = !empty($view_options['hide_free_events']) ? $view_options['hide_free_events'] : '';
		$hide_event_without_thumbnail = !empty($view_options['hide_event_without_thumbnail']) ? $view_options['hide_event_without_thumbnail'] : '';
		$event_limit      = isset($view_options['eventful_event_limit']) ? $view_options['eventful_event_limit'] : 10000;
		$event_per_page   = isset($view_options['post_per_page']) ? $view_options['post_per_page'] : 12;
		$event_offset     = 0;
		$eventful_sticky_post = isset($view_options['eventful_sticky_post']) ? $view_options['eventful_sticky_post'] : 0;
		$show_pagination = isset($view_options['show_post_pagination']) ? $view_options['show_post_pagination'] : false;
		$event_per_page   = ($event_per_page > $event_limit) ? $event_limit : $event_per_page;
		$event_per_page   = (!$show_pagination) ? $event_limit : $event_per_page;

		$paged            = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$filter_url_value = isset($_SERVER['QUERY_STRING']) ? wp_unslash($_SERVER['QUERY_STRING']) : '';
		if (!empty($filter_url_value)) {
			$shortcode_id = isset($_GET['efp']) ? wp_unslash(sanitize_text_field($_GET['efp'])) : '';
			if ($shortcode_id == $id) {
				$eventful_page = isset($_GET['eventful_page']) ? wp_unslash(sanitize_text_field($_GET['eventful_page'])) : '1';
				if (!empty($eventful_page)) {
					$paged = $eventful_page;
				}
			}
		}
		$event_per_page = EventfulFunctions::eventful_post_per_page($event_limit, $event_per_page, $paged);
		if ($event_per_page < 1) {
			$event_per_page = isset($view_options['post_per_page']) ? $view_options['post_per_page'] : 12;
		}
		$offset               = (int) $event_per_page * ($paged - 1);
		$sticky_post_position = 'top_list' === $eventful_sticky_post ? 0 : 1;
		if ('carousel_layout' === $layout_preset || 'slider' === $layout_preset) {
			$event_per_page = ($event_limit > 0) ? $event_limit : 999999;
			$args          = array(
				'post_type'           => $eventful_post_type,
				'suppress_filters'    => false,
				'ignore_sticky_posts' => $sticky_post_position,
				'posts_per_page'      => $event_per_page,
			);
		} else {
			$args = array(
				'post_type'           => $eventful_post_type,
				'suppress_filters'    => false,
				'ignore_sticky_posts' => $sticky_post_position,
				'posts_per_page'      => $event_per_page,
				'paged'               => $paged,
			);
		}
		$event_filter = isset($view_options['event_filter']) ? $view_options['event_filter'] : 'latest';
		if ($event_filter == 'specific') {
			// Include specific event.
			$include_posts = isset($view_options['eventful_include_only_posts']) ? $view_options['eventful_include_only_posts'] : '';

			// Exclude event.
			$exclude_post_set  = isset($view_options['eventful_exclude_post_set']) ? $view_options['eventful_exclude_post_set'] : '';
			$exclude_too       = !empty($exclude_post_set['eventful_exclude_too']) ? $exclude_post_set['eventful_exclude_too'] : array();
			$current_post_id   = in_array('current', $exclude_too, true) ? array(get_the_ID()) : array();

			$exclude_posts     = !empty($exclude_post_set['eventful_exclude_posts']) && isset($exclude_post_set['eventful_exclude_posts']) ? $exclude_post_set['eventful_exclude_posts'] : '';
			$exclude_posts_int = array();
			if (!empty($exclude_posts)) {
				foreach ($exclude_posts as $exclude_post) {
					$exclude_posts_int[] = intval($exclude_post);
				}
			}
			$exclude_post_list = array_merge($exclude_posts_int, $current_post_id);
			if (!empty($exclude_post_list) && !empty($include_posts)) {
				$include_posts = array_diff($include_posts, $exclude_post_list);
			} elseif (!empty($exclude_post_list)) {
				$args['post__not_in'] = ($exclude_post_list);
			}
			// Include specific event.
			if (!empty($include_posts)) {
				$args['post__in'] = $include_posts;
			}

			// Exclude password protected event.
			$password_protected = in_array('password_protected', $exclude_too, true);
			if ($password_protected) {
				$args['has_password'] = false;
			}
			// Exclude children event.
			$exclude_children = in_array('children', $exclude_too, true);
			if ($exclude_children) {
				$args['post_parent'] = 0;
			}
		}
		$filter_order_by = isset($view_options['filter_order_by']) ? $view_options['filter_order_by'] : '_EventStartDate';
		$filter_order = isset($view_options['filter_order']) ? $view_options['filter_order'] : 'ASC';
		$args['post_status'] = 'publish';
		$args['orderby'] = $filter_order_by;
		$args['order'] = $filter_order;
		$upcoming_by_start_date = isset($view_options['upcoming_by_start_date']) ? $view_options['upcoming_by_start_date'] : '';


		$event_filter = isset($view_options['event_filter']) ? $view_options['event_filter'] : 'latest';
		if ($event_filter === 'feature') {
			$args['featured'] = true;
		}
		if ($upcoming_by_start_date) {
			$meta_key = '_EventStartDate';
		} else {
			$meta_key = '_EventEndDate';
		}
		$args['meta_key']    = '_EventStartDate';
		$meta_query = array(
			'relation' => 'AND',

			// Existing upcoming filter
			array(
				'key'     => $meta_key,
				'value'   => current_time('Y-m-d H:i:s'),
				'compare' => '>=',
				'type'    => 'DATETIME',
			),
		);

		/**
		 * 🔹 Hide free events (no price)
		 */
		if (!empty($hide_free_events)) {
			$meta_query[] = array(
				'key'     => '_EventCost',
				'compare' => 'EXISTS',
			);

			$meta_query[] = array(
				'key'     => '_EventCost',
				'value'   => '',
				'compare' => '!=',
			);
		}

		/**
		 * 🔹 Hide events without featured image
		 */
		if (!empty($hide_event_without_thumbnail)) {
			$meta_query[] = array(
				'key'     => '_thumbnail_id',
				'compare' => 'EXISTS',
			);
		}

		// Assign back
		$args['meta_query'] = $meta_query;

		$advanced_filters    = isset($view_options['eventful_advanced_filter']) && !empty($view_options['eventful_advanced_filter']) ? $view_options['eventful_advanced_filter'] : '';
		if ($advanced_filters) {
			foreach ($advanced_filters as $advanced_filter) {
				switch ($advanced_filter) {
					case 'filter_option':
						$taxonomy_types = isset($view_options['eventful_filter_options']['filter_options_group']) && !empty($view_options['eventful_filter_options']['filter_options_group']) ? $view_options['eventful_filter_options']['filter_options_group'] : '';
						if (!$taxonomy_types) {
							break;
						}
						$tax_settings = array();
						foreach ($taxonomy_types as $tax_type) {
							$filter_option = isset($tax_type['filter_option']) ? $tax_type['filter_option'] : '';
							$category = isset($tax_type['eventful_select_categories']) ? $tax_type['eventful_select_categories'] : '';
							$tags = isset($tax_type['eventful_select_tags']) ? $tax_type['eventful_select_tags'] : '';

							$terms = array();
							if ($filter_option === 'category') {
								$all_cat = get_terms('tribe_events_cat');
								$all_cat_ids = wp_list_pluck($all_cat, 'term_id');
								$terms = !empty($category) ? $category : $all_cat_ids;
								$filter_option = 'tribe_events_cat';
							} elseif ($filter_option === 'event_tag') {
								$all_tag = get_terms('post_tag');
								$all_tag_ids = wp_list_pluck($all_tag, 'term_id');
								$terms = !empty($tags) ? $tags : '';
								$filter_option = 'post_tag';
							}

							$all_button_label = isset($tax_type['ajax_filter_options']['ajax_rename_all_text']) ? $tax_type['ajax_filter_options']['ajax_rename_all_text'] : '';

							if ($filter_option === 'tribe_events_cat' || $filter_option === 'post_tag') {
								if ($terms) {
									$operator = isset($tax_type['filter_option_operator']) ? $tax_type['filter_option_operator'] : '';
									if ('AND' === $operator && 1 == count($terms)) {
										$operator = 'IN';
									}

									$tax_settings[] = array(
										'taxonomy'         => $filter_option,
										'field'            => 'term_id',
										'terms'            => $all_button_label ? $terms : $terms[0],
										'operator'         => $operator,
										'include_children' => ('AND' === $operator ? 'false' : 'true'),
									);
								}
							}
						}

						if (count($tax_settings) > 1) {
							$tax_settings['relation'] = 'AND';
						}
						$args = array_merge($args, array('tax_query' => $tax_settings));
						break;
					case 'keyword':
						$keyword_value = isset($view_options['eventful_filter_by_keyword']['eventful_set_event_keyword']) && !empty($view_options['eventful_filter_by_keyword']['eventful_set_event_keyword']) ? $view_options['eventful_filter_by_keyword']['eventful_set_event_keyword'] : '';
						if ($keyword_value) {
							$args = array_merge(
								$args,
								array(
									's' => $keyword_value,
								)
							);
						}
						break;
				}
			}
		}

		$filter_url_value = isset($_SERVER['QUERY_STRING']) ? wp_unslash($_SERVER['QUERY_STRING']) : '';
		if (!empty($filter_url_value)) {
			$shortcode_id = isset($_GET['efp']) ? wp_unslash(sanitize_text_field($_GET['efp'])) : '';
			if ($shortcode_id == $id) {
				$url_args           = $args;
				$url_args['fields'] = 'ids';
				$relation           = 'AND';

				$taxonomies          = get_object_taxonomies($eventful_post_type);
				$tax_settings_by_url = array();
				foreach ($taxonomies as $taxonomy) {
					$filter_url_value = isset($_GET["tx_$taxonomy"]) ? wp_unslash(sanitize_text_field($_GET["tx_$taxonomy"])) : '';
					if (!empty($filter_url_value)) {
						if (strpos($filter_url_value, ',') !== false) {
							$filter_url_value = explode(',', $filter_url_value);
						}
						$tax_settings_by_url[] = array(
							'taxonomy' => $taxonomy,
							'field'    => 'term_id',
							'terms'    => $filter_url_value,
							'operator' => 'IN',
						);
					}
				}
				if (!empty($tax_settings_by_url)) {

					if (count($tax_settings_by_url) > 1) {
						$tax_settings_by_url['relation'] = $relation;
					}
					if ('OR' === $relation) {
						$url_args['posts_per_page'] = '10000';
					}
					$url_post_ids     = get_posts($url_args);
					$args             = array_merge($args, array('tax_query' => $tax_settings_by_url));
					$args['post__in'] = $url_post_ids;
				}

				$final_search_url_value = isset($_GET['eventful_keyword']) ? sanitize_text_field(wp_unslash($_GET['eventful_keyword'])) : '';
				if (!empty($final_search_url_value)) {
					$args['s'] = $final_search_url_value;
				}
			}
		}
		return apply_filters('eventful_query_args', $args, $view_options, $id, $layout_preset, $on_screen);
	}
}
