<?php

namespace ThemeAtelier\Eventful\Frontend\Helpers;

use ThemeAtelier\Eventful\Frontend\Helpers\EventfulFunctions;
use ThemeAtelier\Eventful\Frontend\Helpers\EventfulQueryInside;

/**
 * The file of live filter.
 *
 * @package Eventful
 * @subpackage Eventful/public/helper
 *
 * @since 4.0.0
 */

/**
 * Live filter helper method.
 *
 * @since 4.0.0
 */
class EventfulLiveFilter
{
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    4.0.0
	 */
	public function __construct()
	{
		add_action('wp_ajax_eventful_live_filter_reset', array($this, 'eventful_live_filter_reset'));
		add_action('wp_ajax_eventful_admin_live_filter_reset', array($this, 'eventful_admin_live_filter_reset'));
		add_action('wp_ajax_nopriv_eventful_live_filter_reset', array($this, 'eventful_live_filter_reset'));
	}
	/**
	 * Live filter markup style.
	 *
	 * @param string $btn_type Filter type.
	 * @param string $taxonomy Current taxonomy to view.
	 * @param string $label filter label.
	 * @param string $all_text all text.
	 * @param string $align alignment.
	 * @param bool   $show_count show hide post count.
	 * @param int    $term term id.
	 * @param string $name term name.
	 * @param int    $p_count term found post.
	 * @param int    $id   post id.
	 * @param string $pre_selected selected.
	 * @param string $pre_checked  checked.
	 * @return array
	 */
	public static function eventful_filter_style($number_of_columns, $taxonomy_types_index, $btn_type, $ajax_filter_icon, $taxonomy, $label, $all_text, $align = 'center', $show_count = false, $term = null, $name = null, $p_count = null, $id = '', $pre_selected = '', $pre_checked = '')
	{
		if ($show_count) {
			$event_count_markup = '<span class="eventful-count">(' . $p_count . ')</span>';
		} else {
			$event_count_markup = '';
		}
		$ajax_filter_options = isset($taxonomy_types_index['ajax_filter_options']) ? $taxonomy_types_index['ajax_filter_options'] : array();
		$eventful_filter_btn_color = isset($ajax_filter_options['eventful_filter_btn_color']) ? $ajax_filter_options['eventful_filter_btn_color'] : '';
		$g_option = get_option('eventful_settings');
		$filter_bar_button_color = isset($g_option['filter_bar_button_color']) ? $g_option['filter_bar_button_color'] : '';
		$btn_text_color = isset($filter_bar_button_color['text_color']) ? $filter_bar_button_color['text_color'] : '#5e5e5e';
		$btn_text_acolor = isset($filter_bar_button_color['text_acolor']) ? $filter_bar_button_color['text_acolor'] : '#ffffff';
		$btn_border_color = isset($filter_bar_button_color['border_color']) ? $filter_bar_button_color['border_color'] : '#bbbbbb';
		$btn_border_acolor = isset($filter_bar_button_color['border_acolor']) ? $filter_bar_button_color['border_acolor'] : '#222222';
		$btn_background = isset($filter_bar_button_color['background']) ? $filter_bar_button_color['background'] : '#ffffff';
		$btn_active_background = isset($filter_bar_button_color['active_background']) ? $filter_bar_button_color['active_background'] : '#222222';

		$filter_btn_text_color = !empty($eventful_filter_btn_color['text_color']) ? $eventful_filter_btn_color['text_color'] : $btn_text_color;
		$filter_btn_text_acolor = !empty($eventful_filter_btn_color['text_acolor']) ? $eventful_filter_btn_color['text_acolor'] : $btn_text_acolor;
		$filter_btn_border_color = !empty($eventful_filter_btn_color['border_color']) ? $eventful_filter_btn_color['border_color'] : $btn_border_color;
		$filter_btn_border_acolor = !empty($eventful_filter_btn_color['border_acolor']) ? $eventful_filter_btn_color['border_acolor'] : $btn_border_acolor;
		$filter_btn_background = !empty($eventful_filter_btn_color['background']) ? $eventful_filter_btn_color['background'] : $btn_background;
		$filter_btn_active_background = !empty($eventful_filter_btn_color['active_background']) ? $eventful_filter_btn_color['active_background'] : $btn_active_background;
		$is_checked       = $pre_checked;
		$is_selected      = $pre_selected;
		$checked          = $pre_checked;
		$selected         = $pre_selected;
		$filter_url_value = isset($_SERVER['QUERY_STRING']) ? wp_unslash($_SERVER['QUERY_STRING']) : '';
		if (!empty($filter_url_value)) {
			$shortcode_id = isset($_GET['efp']) ? wp_unslash(sanitize_text_field($_GET['efp'])) : '';
			if ($shortcode_id == $id) {
				$filter_url_value = isset($_GET["tx_$taxonomy"]) ? wp_unslash(sanitize_text_field($_GET["tx_$taxonomy"])) : '';
				if (!empty($filter_url_value)) {
					if (strpos($filter_url_value, ',') !== false) {
						$filter_url_value = explode(',', $filter_url_value);
					}
					if (is_array($filter_url_value)) {
						if (in_array($term, $filter_url_value)) {
							$is_checked  = 'checked';
							$is_selected = 'selected';
						} else {
							$is_checked  = $pre_checked;
							$is_selected = $pre_selected;
						}
					} else {
						if ($filter_url_value == $term) {
							$is_checked  = 'checked';
							$is_selected = 'selected';
						} else {
							$is_checked  = $pre_checked;
							$is_selected = $pre_selected;
						}
					}
				}
			}
		}

		$checked  = $pre_checked;
		$selected = $pre_selected;

		if ('fl_btn' === $btn_type) {
			$all_label  = !empty($all_text) ? '<div><i class="' . esc_attr($ajax_filter_icon) . '"></i>' . $all_text . '</div>' : '';
			$all_button = !empty($all_text) ? '<div class="fl_radio"><label><input checked type="radio" name="' . $taxonomy . '" data-taxonomy="' . $taxonomy . '" value="all">' . $all_label . '</label></div>' : '';

			$first_item = '<form class="eventful__filter_by eventful__bar ' . esc_attr(EventfulLoopHtml::eventful_post_responsive_columns($layout = "", $number_of_columns)) . ' fl_button filter-' . esc_attr($taxonomy) . '" 
				style="
					text-align: ' . esc_attr($align) . ';
					--eventful-filter-btn-text-color: ' . esc_attr($filter_btn_text_color) . ';
					--eventful_filter_btn_text_acolor: ' . esc_attr($filter_btn_text_acolor) . ';
					--eventful_filter_btn_border_color: ' . esc_attr($filter_btn_border_color) . ';
					--eventful_filter_btn_border_acolor: ' . esc_attr($filter_btn_border_acolor) . ';
					--eventful_filter_btn_background: ' . esc_attr($filter_btn_background) . ';
					--eventful_filter_btn_active_background: ' . esc_attr($filter_btn_active_background) . ';">';
			if (!empty($label)) {
				$first_item .= '<p>' . esc_html($label) . '</p>';
			}
			$first_item .= $all_button;

			$push_item  = '<div class="fl_radio"><label><input ' . $is_checked . ' name="' . $taxonomy . '" type="radio" ' . $checked . ' data-taxonomy="' . $taxonomy . '" value="' . $term . '"><div>' . $name . $event_count_markup . '</div></label></div>';
		}
		$filter_output = array(
			'first_item' => $first_item,
			'push_item'  => $push_item,
		);


		return $filter_output;
	}
	/**
	 * Multi dimensional to flatten array.
	 *
	 * @param array $array input array.
	 * @return array
	 */
	public static function array_flatten($array)
	{
		if (!is_array($array)) {
			return false;
		}
		$result = array();
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$result = array_merge($result, self::array_flatten($value));
			} else {
				$result[$key] = $value;
			}
		}
		return $result;
	}

	/**
	 * Live Filter reset after ajax request.
	 */
	public static function eventful_live_filter_reset()
	{
		if (isset($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'eventful_nonce')) {
			return false;
		}
		$eventful_gl_id      = isset($_POST['id']) ? absint($_POST['id']) : '';
		$keyword             = isset($_POST['keyword']) ? sanitize_text_field(wp_unslash($_POST['keyword'])) : '';
		$eventful_lang       = isset($_POST['lang']) ? sanitize_text_field(wp_unslash($_POST['lang'])) : '';
		$last_filter         = isset($_POST['last_filter']) ? sanitize_text_field(wp_unslash($_POST['last_filter'])) : '';
		$paged               = isset($_POST['page']) ? sanitize_text_field(wp_unslash($_POST['page'])) : '';
		$selected_term_list  = isset($_POST['term_list']) ? wp_unslash($_POST['term_list']) : ''; //phpcs:ignore

		$options                 	= get_post_meta($eventful_gl_id, 'eventful_view_options', true);
		$query_args                   = EventfulQueryInside::get_filtered_content($options, $eventful_gl_id);
		$query_args['fields']         = 'ids';
		$event_limit                   = isset($options['eventful_event_limit']) && !empty($options['eventful_event_limit']) ? $options['eventful_event_limit'] : 10000;
		$query_args['posts_per_page'] = $event_limit;
		$query_post_ids               = get_posts($query_args);
		$is_term_intersect            = true;

		$query_args = EventfulFunctions::modify_query_params($query_args, $keyword, $selected_term_list, $query_post_ids, $eventful_lang, $options);

		$eventful_query  = array();
		self::eventful_live_filter_options($options, $query_args, $eventful_gl_id, $is_term_intersect, $selected_term_list, $last_filter);
		wp_die();
	}

	/**
	 * Filter Options.
	 *
	 * @param array  $options options array.
	 * @param  array  $query_args  query array.
	 * @param  string $id shortcode id.
	 * @param  bool   $is_term_intersect is_ajax.
	 * @param  array  $selected_term_list selected term list.
	 * @param  string $last_filter last filter.
	 * @return void
	 */
	public static function eventful_live_filter_options($options, $query_args = '', $id = '', $is_term_intersect = true, $selected_term_list = array(), $last_filter = '')
	{
		$filter_by                    = isset($options['eventful_advanced_filter']) ? $options['eventful_advanced_filter'] : array();
		$event_limit                   = isset($options['eventful_event_limit']) && !empty($options['eventful_event_limit']) ? $options['eventful_event_limit'] : 10000;
		$query_args['posts_per_page'] = $event_limit;
		$query_args['fields']         = 'ids';
		if (in_array('filter_option', $filter_by, true)) {

			$eventful_filter_options = isset($options['eventful_filter_options']) ? $options['eventful_filter_options'] : '';
			$filter_options_group = isset($eventful_filter_options['filter_options_group']) ? $eventful_filter_options['filter_options_group'] : '';
			$relation = 'AND';

			$filter_option_columns = isset($eventful_filter_options['filter_option_columns']) ? $eventful_filter_options['filter_option_columns'] : '';

			if (!empty($filter_options_group)) {
				$output         = '';
				$newterm_array  = array();
				$index          = 0;
				$taxonomies     = array();
				$options_count = count($filter_options_group);
				while ($index < $options_count) {
					$item = $filter_options_group[$index];
					$add_filter = isset($filter_options_group[$index]['add_filter_option_event']) ? $filter_options_group[$index]['add_filter_option_event'] : '';
					if ($add_filter) {
						$filter_option = isset($item['filter_option']) ? $item['filter_option'] : '';
						$category = isset($item['eventful_select_categories']) ? $item['eventful_select_categories'] : '';
						$tags = isset($item['eventful_select_tags']) ? $item['eventful_select_tags'] : '';

						$terms = array();
						if ($filter_option === 'category') {
							$all_cat = get_terms('tribe_events_cat');
							$all_cat_ids = wp_list_pluck($all_cat, 'term_id');
							$terms = !empty($category) ? $category : $all_cat_ids;
							$filter_option = 'tribe_events_cat';
						} elseif ($filter_option === 'event_tag') {
							$all_tag = get_terms('post_tag');
							$all_tag_ids = wp_list_pluck($all_tag, 'term_id');
							$terms = !empty($tags) ? $tags : $all_tag_ids;
							$filter_option = 'post_tag';
						}

						$all_post_ids = get_posts($query_args);
						$event_limit = count($all_post_ids);
						$url_last_filter = isset($_GET['slf']) ? wp_unslash(sanitize_text_field($_GET['slf'])) : '';

						if (! empty($selected_term_list) && is_array($selected_term_list)) {
							if ($last_filter == $filter_option && 'AND' === $relation) {
								$new_query = $query_args;
								$tax_query = isset($new_query['tax_query']) ? $new_query['tax_query'] : array();
								if (! empty($tax_query)) {
									foreach ($tax_query as $key => $value) {
										if (is_array($value)) {
											if ($value['taxonomy'] == $filter_option) {
												unset($tax_query[$key]);
											}
										}
									}
									$new_query['tax_query'] = $tax_query;
									$all_post_ids           = get_posts($new_query);
									$event_limit             = count($all_post_ids);
								}
							}
						} elseif ($url_last_filter == $filter_option && 'AND' === $relation) {
							$new_query = $query_args;
							$tax_query = isset($new_query['tax_query']) ? $new_query['tax_query'] : array();
							if (! empty($tax_query)) {
								foreach ($tax_query as $key => $value) {
									if (is_array($value)) {
										if ($value['taxonomy'] == $filter_option) {
											unset($tax_query[$key]);
										}
									}
								}
								$new_query['tax_query'] = $tax_query;
								$all_post_ids           = get_posts($new_query);
								$event_limit             = count($all_post_ids);
							}
						}

						$filter_options = isset($item['ajax_filter_options']) ? $item['ajax_filter_options'] : '';
						$all_text = !empty($filter_options['ajax_rename_all_text']) ? $filter_options['ajax_rename_all_text'] : '';
						$btn_style = isset($filter_options['ajax_filter_style']) ? $filter_options['ajax_filter_style'] : 'fl_dropdown';
						$ajax_filter_icon = isset($filter_options['ajax_filter_icon']) ? $filter_options['ajax_filter_icon'] : 'icofont-filter';
						$label = !empty($filter_options['ajax_filter_label']) ? $filter_options['ajax_filter_label'] : '';
						$hide_empty = isset($filter_options['ajax_hide_empty']) ? $filter_options['ajax_hide_empty'] : '';
						$show_count = isset($filter_options['ajax_show_count']) ? $filter_options['ajax_show_count'] : '';
						$align = isset($filter_options['eventful_live_filter_align']) ? $filter_options['eventful_live_filter_align'] : 'center';

						if (!empty($terms)) {
							$filter_item = self::eventful_filter_style($filter_option_columns, $item, $btn_style, $ajax_filter_icon, $filter_option, $label, $all_text, $align);
							$newterm_array[$index] = array($filter_item['first_item']);

							foreach ($terms as $term) {
								$selected = '';
								$checked = '';
								if (! empty($selected_term_list) && is_array($selected_term_list)) {
									foreach ($selected_term_list as $tax_type) {
										$cr_taxonomy = $tax_type['taxonomy'];
										$cr_terms = is_array($tax_type['term_id']) ? $tax_type['term_id'] : explode(',', $tax_type['term_id']);

										if ($cr_taxonomy === $filter_option && in_array($term, $cr_terms)) {
											$selected = 'selected';
											$checked = 'checked';
											break;
										}
									}
								}
								switch ($filter_option) {
									case 'tribe_events_cat':
									case 'post_tag':
										$p_term = get_term($term, $filter_option);
										if (!is_wp_error($p_term) || !empty($p_term)) {
											$term_post_count = $p_term->count;
											$term_post_count = $term_post_count > $event_limit ? $event_limit : $term_post_count;
											if ($show_count && 'AND' == $relation) {
												$count_query = $query_args;
												$count_query['tax_query'] = array(array(
													'taxonomy' => $filter_option,
													'field' => 'term_id',
													'terms' => $term,
												));
												$count_post_ids = get_posts($count_query);
												$term_post_count = count(array_intersect($count_post_ids, $all_post_ids));
											}
											if (!$hide_empty || ($hide_empty && $term_post_count > 0)) {
												$push_item = self::eventful_filter_style($filter_option_columns, $item, $btn_style, $ajax_filter_icon, $filter_option, $label, $all_text, $align, $show_count, $term, $p_term->name, $term_post_count, $id, $selected, $checked)['push_item'];
												array_push($newterm_array[$index], $push_item);
											}
										};

										break;
								}
							}
							if (!$all_text) {
								$newterm_array[$index][1] = preg_replace('/ type=/i', ' selected checked type=', $newterm_array[$index][1]);
							}

							$tax_html = implode('', $newterm_array[$index]);

							$output .= force_balance_tags($tax_html);
						}
					}
					$index++;
				}
				echo $output; //phpcs:ignore
			}
		}
	}

	/**
	 * Live Filter reset after ajax request.
	 */
	public static function eventful_admin_live_filter_reset()
	{
		if (isset($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'eventful_nonce')) {
			return false;
		}
		$eventful_gl_id      = isset($_POST['id']) ? absint($_POST['id']) : '';
		$keyword             = isset($_POST['keyword']) ? sanitize_text_field(wp_unslash($_POST['keyword'])) : '';
		$taxonomy            = isset($_POST['taxonomy']) ? sanitize_text_field(wp_unslash($_POST['taxonomy'])) : '';
		$term_id             = isset($_POST['term_id']) ? sanitize_text_field(wp_unslash($_POST['term_id'])) : '';
		$eventful_lang   	 = isset($_POST['lang']) ? sanitize_text_field(wp_unslash($_POST['lang'])) : '';
		$last_filter         = isset($_POST['last_filter']) ? sanitize_text_field(wp_unslash($_POST['last_filter'])) : '';
		$paged               = isset($_POST['page']) ? sanitize_text_field(wp_unslash($_POST['page'])) : '';
		$selected_term_list  = isset($_POST['term_list']) ? wp_unslash($_POST['term_list']) : '';
		$settings            = array(); //phpcs:ignore
		parse_str($_POST['data'], $settings); //phpcs:ignore
		$layout                       = $settings['eventful_layouts'];
		$options                 	= $settings['eventful_view_options'];
		$query_args                   = EventfulQueryInside::get_filtered_content($options, $eventful_gl_id);
		$query_args['fields']         = 'ids';
		$event_limit                   = isset($options['eventful_event_limit']) && !empty($options['eventful_event_limit']) ? $options['eventful_event_limit'] : 10000;
		$query_args['posts_per_page'] = $event_limit;
		$query_post_ids               = get_posts($query_args);
		$is_term_intersect = true;

		$query_args = EventfulFunctions::modify_query_params($query_args, $keyword, $selected_term_list, $query_post_ids, $eventful_lang, $options);
		self::eventful_live_filter_options($options, $query_args, $eventful_gl_id, $is_term_intersect, $selected_term_list, $last_filter);
		wp_die();
	}

	/**
	 * Live search bar
	 *
	 * @param int    $options options.
	 * @param string $sid shortcode id.
	 * @return void
	 */
	public static function eventful_live_search_bar($options, $sid = null)
	{
		$filter_by        = isset($options['eventful_advanced_filter']) ? $options['eventful_advanced_filter'] : array();
		$show_button_text        = isset($options['show_button_text']) ? $options['show_button_text'] : 'Show Filter';
		$hide_button_text        = isset($options['hide_button_text']) ? $options['hide_button_text'] : 'Hide Filter';

		$g_option = get_option('eventful_settings');
		$g_show_hide_filter_button = isset($g_option['g_show_hide_filter_button']) ? $g_option['g_show_hide_filter_button'] : '';
		$show_hide_filter_button_text = isset($g_show_hide_filter_button['text']) ? $g_show_hide_filter_button['text'] : '#ffffff';
		$show_hide_filter_button_hover_text = isset($g_show_hide_filter_button['hover_text']) ? $g_show_hide_filter_button['hover_text'] : '#ffffff';
		$show_hide_filter_button_color = isset($g_show_hide_filter_button['color']) ? $g_show_hide_filter_button['color'] : '#222222';
		$show_hide_filter_button_hover_color = isset($g_show_hide_filter_button['hover_color']) ? $g_show_hide_filter_button['hover_color'] : '#222222';


		$final_keyword    = '';
		$filter_url_value = isset($_SERVER['QUERY_STRING']) ? wp_unslash($_SERVER['QUERY_STRING']) : '';
		if (!empty($filter_url_value)) {
			$shortcode_id = isset($_GET['efp']) ? wp_unslash(sanitize_text_field($_GET['efp'])) : '';
			if ($shortcode_id == $sid) {
				$final_keyword = isset($_GET['eventful_keyword']) ? sanitize_text_field(wp_unslash($_GET['eventful_keyword'])) : '';
			}
		}
		$show_hide_filter_button        = isset($options['show_hide_filter_button']) ? $options['show_hide_filter_button'] : '';
		$background_color        = !empty($options['show_hide_filter_button_background']['color']) ? $options['show_hide_filter_button_background']['color'] : $show_hide_filter_button_color;
		$hover_background_color        = !empty($options['show_hide_filter_button_background']['hover_color']) ? $options['show_hide_filter_button_background']['hover_color'] : $show_hide_filter_button_hover_color;
		$text_color        = !empty($options['show_hide_filter_button_background']['text']) ? $options['show_hide_filter_button_background']['text'] : $show_hide_filter_button_text;
		$hover_text_color        = !empty($options['show_hide_filter_button_background']['hover_text']) ? $options['show_hide_filter_button_background']['hover_text'] : $show_hide_filter_button_hover_text;

		if (in_array('keyword', $filter_by, true)) {
			$eventful_filter_by_keyword = isset($options['eventful_filter_by_keyword']) ? $options['eventful_filter_by_keyword'] : '';
			$add_filter_post       = isset($eventful_filter_by_keyword['add_search_filter_post']) ? $eventful_filter_by_keyword['add_search_filter_post'] : '';
			if ($add_filter_post) {
				$ajax_filter_options   = isset($eventful_filter_by_keyword['ajax_filter_options']) ? $eventful_filter_by_keyword['ajax_filter_options'] : '';
				$placeholder = isset($ajax_filter_options['ajax_filter_placeholder']) && !empty($ajax_filter_options['ajax_filter_placeholder']) ? $ajax_filter_options['ajax_filter_placeholder'] : 'Search...';
				$label = isset($ajax_filter_options['ajax_search_filter_label']) && !empty($ajax_filter_options['ajax_search_filter_label']) ? $ajax_filter_options['ajax_search_filter_label'] : '';

				$eventful_live_filter_align = isset($ajax_filter_options['eventful_live_filter_align']) ? $ajax_filter_options['eventful_live_filter_align'] : 'center';
				echo '<div class="" style="text-align:' . esc_attr($eventful_live_filter_align) . ';">';
				if ($label) {
					echo '<label for="search_event">' . esc_html($label) . '</label>';
				}

				echo '<div class="eventful-ajax-search eventful__bar"><div class="search_input_area"><i class="icofont-search-1"></i><input id="search_event" type="text" value="' . esc_attr($final_keyword) . '" class="eventful-search-field" placeholder="' . esc_attr($placeholder) . '" /></div>';

				$keyword = isset($filter_by[0]) ? $filter_by[0] : '';
				$filter_option = isset($filter_by[1]) ? $filter_by[1] : '';
				if ($keyword && $filter_option && $show_hide_filter_button && $add_filter_post) {
					echo '<button data-show_button="' . esc_attr($show_button_text) . '" data-hide_button="' . esc_attr($hide_button_text) . '" style="--background_color: ' . esc_attr($background_color) . ';--hover_background_color: ' . esc_attr($hover_background_color) . ';--text_color: ' . esc_attr($text_color) . ';--hover_text_color: ' . esc_attr($hover_text_color) . '" class="show_hide_filter">' . esc_html($show_button_text) . '</button>';
				}
				echo '</div></div>';
			}
		}
	}
}
