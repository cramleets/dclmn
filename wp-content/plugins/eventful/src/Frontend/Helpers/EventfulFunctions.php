<?php

namespace ThemeAtelier\Eventful\Frontend\Helpers;

use TEC\Events_Pro\Custom_Tables\V1\Models\Series_Relationship;

/**
 * The file functions.
 *
 * @package Eventful
 * @subpackage Eventful/public/helper
 *
 * @since 2.0.0
 */

/**
 * Event views helper method.
 *
 * @since 2.0.0
 */
class EventfulFunctions
{
	/**
	 * Event title character limit.
	 *
	 * @param string  $eventful_title The post title.
	 * @param integer $limit_length The length for the title.
	 * @param string  $eventful_after_string The string after title.
	 * @return statement
	 */
	public static function event_title_limit($eventful_title, $limit_length, $eventful_after_string = '...')
	{
		return mb_strimwidth($eventful_title, 0, $limit_length, apply_filters('eventful_event_title_ellipsis', $eventful_after_string));
	}

	/**
	 * Tag name to full tag conversion.
	 *
	 * @param array $meta_tag Tag option.
	 * @return string
	 */
	public static function short_tag_to_html($meta_tag)
	{
		$exclude_tag_string = '';
		foreach ($meta_tag as $key => $value) {
			$exclude_tag_string .= '<' . $value . '>,';
		}
		return $exclude_tag_string;
	}

	/**
	 * Limit the the text.
	 *
	 * @param mixed  $text The text you want to limit.
	 * @param int    $limit The number of words to display.
	 * @param string $ellipsis The ellipsis at the end of the text.
	 * @return statement
	 */
	public static function eventful_limit_text($text, $limit, $ellipsis = '...')
	{
		$text  = self::eventful_clean_text($text);
		$limit = (int) $limit;

		if ($limit < 1 || '' === $text) {
			return $text;
		}

		$word_arr = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
		if (count($word_arr) > $limit) {
			return implode(' ', array_slice($word_arr, 0, $limit)) . $ellipsis;
		}

		return $text;
	}

	/**
	 * Characters Limit of the text.
	 *
	 * @param mixed  $text The text you want to limit.
	 * @param int    $limit The number of words to display.
	 * @param string $ellipsis The ellipsis at the end of the text.
	 * @return statement
	 */
	public static function limit_content_chr($text, $limit, $ellipsis = '...')
	{
		$text  = self::eventful_clean_text($text);
		$limit = (int) $limit;

		if ($limit < 1 || '' === $text) {
			return $text;
		}

		// Multibyte-safe length so accented/non-latin characters count as one.
		if (function_exists('mb_strlen')) {
			if (mb_strlen($text) <= $limit) {
				return $text; // Already within the limit, nothing to trim.
			}
			$visible = mb_substr($text, 0, $limit);
		} else {
			if (strlen($text) <= $limit) {
				return $text;
			}
			$visible = substr($text, 0, $limit);
		}

		// Avoid cutting a word in half: step back to the last whole word when
		// the cut lands inside one (skip if the limit is shorter than a word).
		$last_space = function_exists('mb_strrpos') ? mb_strrpos($visible, ' ') : strrpos($visible, ' ');
		if (false !== $last_space && $last_space > 0) {
			$visible = function_exists('mb_substr') ? mb_substr($visible, 0, $last_space) : substr($visible, 0, $last_space);
		}

		return rtrim($visible) . $ellipsis;
	}

	/**
	 * Normalise raw post content into clean, visible plain text.
	 *
	 * Mirrors the core excerpt pipeline: drop shortcodes, strip the blocks that
	 * should not appear in a preview, render the remaining blocks, then remove
	 * every tag and HTML/block comment. This guarantees character/word limits
	 * are applied to real text and never to markup such as `<img class="align…`
	 * or `<!-- wp:tribe/event-… -->`.
	 *
	 * @param string $text Raw content.
	 * @return string Clean, single-spaced plain text.
	 */
	private static function eventful_clean_text($text)
	{
		if (!is_string($text) || '' === $text) {
			return '';
		}

		$text = strip_shortcodes($text);

		if (function_exists('excerpt_remove_footnotes')) {
			$text = excerpt_remove_footnotes($text);
		}
		if (function_exists('excerpt_remove_blocks')) {
			$text = excerpt_remove_blocks($text);
		}
		if (function_exists('do_blocks')) {
			$text = do_blocks($text);
		}

		// wp_strip_all_tags() also removes HTML comments (block delimiters).
		$text = wp_strip_all_tags($text, true);
		// Collapse any remaining runs of whitespace into single spaces.
		$text = preg_replace('/\s+/u', ' ', $text);

		return trim($text);
	}

	/**
	 * Allowed tags function of the Plugin.
	 *
	 * @since 2.0.0
	 * @return array allowed tags
	 */
	public static function allowed_tags()
	{
		$allowed_tags           = wp_kses_allowed_html('post');
		$allowed_tags['iframe'] = array(
			'src'             => array(),
			'height'          => array(),
			'width'           => array(),
			'frameborder'     => array(),
			'allowfullscreen' => array(),
			'title'           => array(),
			'alt'             => array(),
		);

		$allowed_tags['style'] = array();

		return $allowed_tags;
	}

	/**
	 * Content function.
	 *
	 * @param array  $options Read more options array.
	 * @param string $type Content type.
	 * @param  mixed  $event post.
	 * @return content
	 */
	public static function eventful_content($event_content_setting, $options, $type, $event)
	{
		$eventful_content_length      = isset($event_content_setting['eventful_content_length']) ? $event_content_setting['eventful_content_length'] : '';
		$template_content_length_limit      = isset($eventful_content_length['all']) ? $eventful_content_length['all'] : '20';
		$template_content_length_unit      = isset($eventful_content_length['unit']) ? $eventful_content_length['unit'] : 'words';

		$event_content_ellipsis        = isset($event_content_setting['post_content_ellipsis']) ? $event_content_setting['post_content_ellipsis'] : '';
		$eventful_strip_tags               = isset($event_content_setting['eventful_strip_tags']) ? $event_content_setting['eventful_strip_tags'] : '';
		$eventful_allow_tag_name           = isset($event_content_setting['eventful_allow_tag_name']) ? $event_content_setting['eventful_allow_tag_name'] : '';
		$allowed_tags                 = explode(',', $eventful_allow_tag_name);

		$is_page_content = false;
		$is_page_content = apply_filters('eventful_strip_shortcode_in_page_content', $is_page_content);

		global $wp_embed;
		if ('excerpt' === $type) {
			$eventful_post_content = get_the_excerpt($event);
		} elseif ('full_content' === $type) {


			if ($is_page_content) {
				$event_content = apply_filters('eventful_the_content', strip_shortcodes($event->post_content));
			} else {
				$event_content = apply_filters('eventful_the_content', $event->post_content);
			}
			if ('allow_some' === $eventful_strip_tags) {
				$eventful_post_content = strip_tags($event_content, self::short_tag_to_html($allowed_tags));
			} elseif ('strip_all' === $eventful_strip_tags) {
				$eventful_post_content = wp_strip_all_tags($event_content);
			} else {
				$eventful_post_content = $event_content;
			}
		} else {
			if ($is_page_content) {
				$event_content = apply_filters('eventful_the_content', strip_shortcodes($event->post_content));
			} else {
				$event_content = apply_filters('eventful_the_content', $event->post_content);
			}
			if ($template_content_length_limit > 0) {
				if ('characters' === $template_content_length_unit) {
					$_trimmed_content = ('strip_all' === $eventful_strip_tags) ? wp_html_excerpt($event_content, $template_content_length_limit, $event_content_ellipsis) : self::limit_content_chr($event_content, $template_content_length_limit, $event_content_ellipsis);
				} else if ('words' === $template_content_length_unit) {
					$_trimmed_content = self::eventful_limit_text($event_content, $template_content_length_limit, $event_content_ellipsis);
				} else {
					$_trimmed_content = $event_content;
				}
			} else {
				$_trimmed_content = $event_content;
			}

			if ('allow_some' === $eventful_strip_tags) {
				$eventful_post_content = strip_tags($_trimmed_content, self::short_tag_to_html($allowed_tags));
			} elseif ('strip_all' === $eventful_strip_tags) {
				$eventful_post_content = wp_strip_all_tags($_trimmed_content);
			} else {
				$eventful_post_content = $_trimmed_content;
			}
			$eventful_post_content = force_balance_tags($eventful_post_content);
		}
		$eventful_post_content = do_shortcode($wp_embed->autoembed($eventful_post_content));
		return $eventful_post_content;
	}

	/**
	 * Thumb alter text
	 *
	 * @param integer $slide_id The slide/post ID.
	 *
	 * @return string
	 */
	public static function eventful_thumb_alter_text($slide_id)
	{
		$image_id = get_post_thumbnail_id($slide_id);
		$alt_text = get_post_meta($image_id, '_wp_attachment_image_alt', true);
		return $alt_text;
	}

	/**
	 * Modify query params
	 *
	 * @param  array  $query_args query_args.
	 * @param  string $keyword keyword.
	 * @param  int    $author_id author id.
	 * @param  array  $selected_term_list term_list.
	 * @param  array  $event_in post in.
	 * @param  string $lang current lang.
	 * @return array
	 */
	public static function modify_query_params($query_args, $keyword, $selected_term_list, $event_in = array(), $lang = '')
	{

		if (!empty($keyword)) {
			$query_args['s'] = $keyword;
		}
		if (!empty($lang)) {
			$query_args['lang'] = $lang;
		}

		$query_args['post__in'] = $event_in;
		if (!empty($order)) {
			$query_args['order'] = $order;
		}

		$tax_settings = array();
		$meta_settings = array();
		if (!empty($selected_term_list)) {
			if (count($selected_term_list) > 1) {
				$tax_settings['relation'] = 'AND';
				$meta_settings['relation'] = 'AND';
			}
			if (is_array($selected_term_list)) {
				foreach ($selected_term_list as $key => $tax_type) {
					$taxonomy = $tax_type['taxonomy'];
					$terms    = $tax_type['term_id'];
					if (strpos($terms, ',') !== false) {
						$terms = explode(',', $terms);
					}
					if (in_array($taxonomy, ['tribe_events_cat', 'post_tag'])) {


						if ($taxonomy && $terms) {
							$tax_settings[] = array(
								'taxonomy'         => $taxonomy,
								'field'            => 'term_id',
								'terms'            => $terms,
								'operator'         => 'IN',
								'include_children' => false,
							);
						}
					}
				}
				$query_args['tax_query'] = $tax_settings;
				$query_args['meta_query'] = $meta_settings;
			}
		}
		return $query_args;
	}

	/**
	 * Thumb Sized function
	 *
	 * @param array   $event_thumb_setting Thumbnails options array.
	 * @param integer $slide_id The slide/post ID.
	 *
	 * @return string
	 */
	public static function eventful_sized_thumb($event_thumb_setting, $slide_id, $layout)
	{
		$thumb_id                  = '';
		$image                     = '';
		$show_2x_image             = isset($event_thumb_setting['load_2x_image']) ? $event_thumb_setting['load_2x_image'] : false;
		$image_resize_2x_url       = '';
		if (has_post_thumbnail($slide_id)) {
			$thumb_id = get_post_thumbnail_id($slide_id);
		}

		if (function_exists('wc_placeholder_img_src')) {
			$placeholder_img = wc_placeholder_img_src();
		} else {
			$placeholder_img = EVENTFUL_DIR_URL . 'src/Frontend/assets/img/placeholder.png';
		}
		$placeholder_img = apply_filters('eventful_no_thumb_placeholder', $placeholder_img);

		if (empty($thumb_id) && !empty($placeholder_img)) {
			$thumb_id = attachment_url_to_postid($placeholder_img);
		}

		if ($layout === 'slider') {
			$image_sizes       = isset($event_thumb_setting['eventful_slider_thumb_sizes']) ? $event_thumb_setting['eventful_slider_thumb_sizes'] : 'large';
			$event_image_width  = isset($event_thumb_setting['eventful_slider_image_crop_size']['top']) ? $event_thumb_setting['eventful_slider_image_crop_size']['top'] : '';
			$event_image_height = isset($event_thumb_setting['eventful_slider_image_crop_size']['right']) ? $event_thumb_setting['eventful_slider_image_crop_size']['right'] : '';
			$event_image_crop   = isset($event_thumb_setting['eventful_slider_image_crop_size']['style']) ? $event_thumb_setting['eventful_slider_image_crop_size']['style'] : '';
		} else {
			$image_sizes       = isset($event_thumb_setting['eventful_thumb_sizes']) ? $event_thumb_setting['eventful_thumb_sizes'] : 'large';
			$event_image_width  = isset($event_thumb_setting['eventful_image_crop_size']['top']) ? $event_thumb_setting['eventful_image_crop_size']['top'] : '';
			$event_image_height = isset($event_thumb_setting['eventful_image_crop_size']['right']) ? $event_thumb_setting['eventful_image_crop_size']['right'] : '';
			$event_image_crop   = isset($event_thumb_setting['eventful_image_crop_size']['style']) ? $event_thumb_setting['eventful_image_crop_size']['style'] : '';
		}
		if (!empty($thumb_id)) {
			$thumb_full_src    = wp_get_attachment_image_src($thumb_id, 'full');
			$thumb_full_src    = is_array($thumb_full_src) ? $thumb_full_src : array('', '', '');
			$image_src         = wp_get_attachment_image_src($thumb_id, $image_sizes);
			$image_src         = is_array($image_src) ? $image_src : array('', '', '');

			if (('custom' === $image_sizes) && (!empty($event_image_width) && $thumb_full_src[1] >= $event_image_width) && (!empty($event_image_height) && $thumb_full_src[2] >= $event_image_height)) {
				$hard_crop = 'Hard-crop' === $event_image_crop ? true : false;

				$image = self::eventful_resize($thumb_full_src[0], $event_image_width, $event_image_height, $hard_crop);
				if ($show_2x_image && ($thumb_full_src[1] >= ($event_image_width * 2)) && $thumb_full_src[2] >= ($event_image_height * 2)) {
					$image_resize_2x_url = self::eventful_resize($thumb_full_src[0], $event_image_width * 2, $event_image_height * 2, $hard_crop);
				} elseif ($show_2x_image && (($event_image_width * 2) === $thumb_full_src[1]) && ($event_image_height * 2) === $thumb_full_src[2]) {
					$image_resize_2x_url = $thumb_full_src[0];
				}
				$image_width  = $event_image_width;
				$image_height = $event_image_height;
			} else {
				$image        = !empty($image_src[0]) ? $image_src[0] : $placeholder_img;
				$image_width  = !empty($image_src[1]) ? $image_src[1] : 600;
				$image_height = !empty($image_src[2]) ? $image_src[2] : 450;
			}
		} else {
			$image        = $placeholder_img;
			$image_width  = $event_image_width;
			$image_height = $event_image_height;
		}
		$eventful_image_attr = array(
			'src'        => $image,
			'2x_src'     => $image_resize_2x_url,
			'width'      => $image_width,
			'height'     => $image_height,
		);

		return $eventful_image_attr;
	}

	/**
	 * Process all the event fildes.
	 *
	 * @param object $event The selected event.
	 * @param array  $event_fildes_fields The selected event fildes to show.
	 * @param string $fildes_separator The event fildes separator.
	 * @return void
	 */
	public static function eventful_get_event_fildes($event, $event_fildes_fields, $event_meta_separator)
	{
		$meta_wrapper_start_tag =  apply_filters('eventful_event_fildes_wrapper_start', '<span class="event_meta_wrapper">');
		$meta_wrapper_end_tag   = apply_filters('eventful_event_fildes_wrapper_end', '</span>');
		echo wp_kses_post($meta_wrapper_start_tag);
		$i = 0;
		foreach ($event_fildes_fields as $each_meta) {
			$selected_meta        = isset($each_meta['select_event_fildes']) ? $each_meta['select_event_fildes'] : '';
			$meta_venue_map_link        = isset($each_meta['meta_venue_map_link']) ? $each_meta['meta_venue_map_link'] : '';
			$venue_phone_icon        = isset($each_meta['venue_phone_icon']) ? $each_meta['venue_phone_icon'] : '';
			$meta_date_format     = isset($each_meta['event_meta_fildes_date_format']) ? $each_meta['event_meta_fildes_date_format'] : 'j F, Y';
			$event_date_style   = isset($each_meta['event_meta_fildes_date_type']) ? $each_meta['event_meta_fildes_date_type'] : 'start_date_time_with_end_date_time';
			$custom_date_format   = isset($each_meta['event_meta_fildes_custom_date_format']) ? $each_meta['event_meta_fildes_custom_date_format'] : 'j F, Y g:i A';

			$meta_icon      = !empty($each_meta['select_event_fildes_icon']) ? sprintf('<i class="' . $each_meta['select_event_fildes_icon'] . '"></i>') : '';
			$start_tag      =  	'<span class="event_meta_address">';
			$end_tag        = 	'</span>';
			$meta_tag_start = apply_filters('eventful_event_fildes_html_tag_start', $start_tag);
			$meta_tag_end   = apply_filters('eventful_event_fildes_html_tag_end', $end_tag);
			$allowed_html   = array(
				'a'    => array(
					'href'  => array(),
					'title' => array(),
				),
				'i'    => array(
					'class' => array(),
					'id'    => array(),
				),
				'span' => array(
					'class' => array(),
					'id'    => array(),
				),
				'div'  => array(
					'class' => array(),
					'id'    => array(),
				),
			);

			switch ($selected_meta) {
				case 'venue':
					if (0 < $i) {
						echo wp_kses_post($event_meta_separator);
					}
					$meta_venue_settings = isset($each_meta['meta_venue_settings']) ? $each_meta['meta_venue_settings'] : "";
					$google_map_link = tribe_get_map_link($event->ID); // Google Map Link

					if (tribe_get_venue($event->ID, true)) {
						if ($meta_venue_settings) {
							echo wp_kses_post($meta_tag_start);
							echo wp_kses($meta_icon, $allowed_html);
							$venue_name = '';
							$venue_address = '';
							$venue_city = '';
							$venue_state = '';
							$venue_country = '';
							$venue_zip = '';
							foreach ($meta_venue_settings as $venue_setting) {
								switch ($venue_setting) {

									case 'venue_name':
										$venue_name = tribe_get_venue($event->ID); // Venue name
										break;
									case 'address':
										$venue_address = tribe_get_address($event->ID); // Address
										break;
									case 'city':
										$venue_city = tribe_get_city($event->ID); // City
										break;
									case 'country':
										$venue_country = tribe_get_country($event->ID); // Country
										break;
									case 'state':
										$venue_state = tribe_get_stateprovince($event->ID); // State
										break;
									case 'postal_code':
										$venue_zip = tribe_get_zip($event->ID); // ZIP/Postal Code
										break;
									case 'phone':
										$phone = tribe_get_phone($event->ID, true);

										if ($phone) {
											echo '<span>' . esc_html($phone) . '</span>';
										}
										break;
								}
							}

							$venue_link = tribe_get_venue_link($event->ID);

							// Make venue name a clickable link
							if (!empty($venue_name) && !empty($venue_link)) {
								$venue_name = $venue_link;
							}

							$venue_details = trim(implode(', ', array_filter([$venue_name, $venue_address, $venue_city, $venue_state, $venue_zip, $venue_country])));

							if (!empty($venue_details)) {
								if (!empty($google_map_link && $meta_venue_map_link)) {
									$venue_details .= '<a href="' . esc_url($google_map_link) . '" target="_blank" rel="nofollow noopener">' . __(' + Google Map', 'eventful') . '</a>';
								}
								echo wp_kses_post($venue_details);
							}

							echo wp_kses_post($meta_tag_end);
						}
					}
					break;
				case 'organizer':
					if (0 < $i) {
						echo wp_kses_post($event_meta_separator);
					}
					if (tribe_get_organizer($event->ID, true)) {
						echo wp_kses_post($meta_tag_start);
						echo wp_kses($meta_icon, $allowed_html);
?>
						<a href="<?php echo esc_url(tribe_get_organizer_website_url($event->ID)); ?>"><?php echo esc_html(tribe_get_organizer($event->ID, true));  ?></a>
					<?php
						echo wp_kses_post($meta_tag_end);
					}
					break;
				case 'price':
					if (tribe_get_cost($event->ID)) {
						echo wp_kses_post($meta_tag_start);
						echo wp_kses($meta_icon, $allowed_html);
					?>
						<span><?php echo esc_html(tribe_get_cost($event->ID, true)) ?></span>
					<?php
						echo wp_kses_post($meta_tag_end);
					}
					break;
				case 'event_time':
					if (0 < $i) {
						echo wp_kses_post($event_meta_separator);
					}
					echo wp_kses_post($meta_tag_start);
					if ('start_date' === $event_date_style) {
						if ('custom' === $meta_date_format) {
							$event_date = tribe_get_start_date($event->ID, false, $custom_date_format);
						} else {
							$event_date = tribe_get_start_date($event->ID, false, $meta_date_format);
						}
					} elseif ('end_date' === $event_date_style) {
						if ('custom' === $meta_date_format) {
							$event_date = tribe_get_end_date($event->ID, false, $custom_date_format);
						} else {
							$event_date = tribe_get_end_date($event->ID, false, $meta_date_format);
						}
					} else {
						if ('custom' === $meta_date_format) {
							$event_date = tribe_get_start_date($event->ID, true, $custom_date_format) . ' - ' . tribe_get_end_date($event->ID, true, $custom_date_format);
						} else {
							$event_date = tribe_get_start_date($event->ID, true, $meta_date_format) . ' - ' . tribe_get_end_date($event->ID, true, $meta_date_format);
						}
					}
					echo wp_kses($meta_icon, $allowed_html); ?>
					<time class="entry-date published updated"><?php echo wp_kses_post($event_date); ?></time>
		<?php
					echo wp_kses_post($meta_tag_end);
					break;
			}
			++$i;
		} // End Foreach.
		echo wp_kses_post($meta_wrapper_end_tag);
	}

	/**
	 * Maximum pages.
	 *
	 * @param int $total_post Number of total posts.
	 * @param int $event_per_page Events per page.
	 *
	 * @return void
	 */
	public static function eventful_max_pages($total_post, $event_per_page)
	{
		if (!$total_post) {
			return;
		}
		$max_num_pages = ceil($total_post / $event_per_page);
		return (int) $max_num_pages;
	}

	/**
	 * Event per page.
	 *
	 * @param int $limit Event Limit.
	 * @param int $event_per_page post per page.
	 * @param int $page paged number.
	 *
	 * @return int
	 */
	public static function eventful_post_per_page($limit, $event_per_page, $page)
	{
		$limit               = (empty($limit) || '-1' === $limit) ? 10000000 : $limit;
		$offset              = (int) $event_per_page * ($page - 1);
		$final_post_per_page = $event_per_page;
		if (intval($event_per_page) > $limit - $offset) {
			$final_post_per_page = $limit - $offset;
		}
		return $final_post_per_page;
	}

	/**
	 * Pagination last page post
	 *
	 * @param [type] $limit total post limit.
	 * @param [type] $event_per_page post per page.
	 * @param [type] $total_page last post page.
	 *
	 * @return int.
	 */
	public static function eventful_last_page_post($limit, $event_per_page, $total_page)
	{
		$limit              = (empty($limit) || '-1' === $limit) ? 10000000 : $limit;
		$offset             = $event_per_page * ($total_page - 1);
		$eventful_last_page_post = $limit - $offset;
		return $eventful_last_page_post;
	}

	/**
	 * Get view option from view ID
	 *
	 * @param string $eventful_gl_id ID of custom field.
	 *
	 * @return array
	 */
	public static function view_options($eventful_gl_id)
	{
		if (!$eventful_gl_id) {
			return;
		}
		$view_options = get_post_meta($eventful_gl_id, 'eventful_view_options', true);
		return $view_options;
	}

	/**
	 * Get value of a setting from global settings array
	 *
	 * @param string     $field        The full name of setting to get value.
	 * @param array      $array_to_get Array to get values of wanted setting.
	 * @param mixed|null $assign       The value to assign if setting is not found.
	 */
	public static function eventful_metabox_value($field, $array_to_get = null, $assign = null)
	{
		global $eventful_gl_id;
		if (empty($array_to_get)) {
			$array_to_get = self::view_options($eventful_gl_id);
		}
		return isset($array_to_get[$field]) ? $array_to_get[$field] : $assign;
	}

	/**
	 * Custom Template locator .
	 *
	 * @param  mixed $template_name template name .
	 * @param  mixed $template_path template path .
	 * @param  mixed $default_path default path .
	 * @return string
	 */
	public static function eventful_locate_template($template_name, $template_path = '', $default_path = '')
	{
		if (!$template_path) {
			$template_path = 'eventful/templates';
		}

		if (!$default_path) {
			$default_path = EVENTFUL_PATH . '/src/Frontend/templates/';
		}
		$template = locate_template(trailingslashit($template_path) . $template_name);
		// Get default template.
		if (!$template) {
			$template = $default_path . $template_name;
		}
		// Return what we found.
		return $template;
	}

	/**
	 * Eventful dashboard capability.
	 *
	 * @return string
	 */
	public static function eventful_dashboard_capability()
	{
		return apply_filters('eventful_dashboard_capability', 'manage_options');
	}

	/**
	 * This is just a tiny wrapper function for the class above so that there is no
	 * need to change any code in your own WP themes. Usage is still the same :)
	 *
	 * @param  mixed $url image url.
	 * @param  mixed $width image width.
	 * @param  mixed $height height.
	 * @param  mixed $crop crop.
	 * @param  mixed $single single.
	 * @param  mixed $upscale upscale.
	 * @return statement
	 */
	public static function eventful_resize($url, $width = null, $height = null, $crop = null, $single = true, $upscale = false)
	{
		$eventful_resize = EventfulImageResizer::getInstance();
		return $eventful_resize->process($url, $width, $height, $crop, $single, $upscale);
	}

	public static function is_the_events_calendar_installed()
	{
		$file_path = 'the-events-calendar/the-events-calendar.php';
		$installed_plugins = get_plugins();

		return isset($installed_plugins[$file_path]);
	}
	/**
	 * Show event thumb category.
	 *
	 * @param integer $event_id The event ID.
	 * @return statement.
	 */
	public static function eventful_thumb_meta(
		$event,
		$type,
		$position,
		$taxonomy = '',
		$side = '',
		$background_color = '',
		$background_gradient_color = '',
		$background_gradient_direction = '',
		$gradient = false,
		$theme_style = '',
		$options = ''
	) {
		// Gradient styles
		$gradient_style = '';
		if ($gradient) {
			$gradient_style = '--gradient_bg_start: ' . esc_attr($background_color) . ';  --gradient_bg_end: ' . esc_attr($background_gradient_color) . '; --gradient_direction: ' . esc_attr($background_gradient_direction) . ';';
		}

		// Base icon markup
		$icon = '';
		if ($type === 'taxonomy') {
			if ($taxonomy === 'tribe_events_cat') {
				$icon = '<i class="icofont-ui-folder"></i>';
			} else {
				$icon = '<i class="icofont-tags"></i>';
			}
		} elseif ($type === 'date') {
			$icon = '<i class="icofont-clock-time"></i>';
		} elseif ($type === 'venue') {
			$icon = '<i class="icofont-google-map"></i>';
		} elseif ($type === 'organizer') {
			$icon = '<i class="icofont-user-alt-3"></i>';
		} elseif ($type === 'social_media') {
			$icon = '<i class="icofont-ui-social-link"></i>';
		}

		// ========== CONTENT GENERATION ==========
		$content = '';

		// ---- 1. TAXONOMY MODE ----
		if ($type === 'taxonomy' && !empty($taxonomy)) {

			$terms = get_the_terms($event->ID, $taxonomy);

			if (!empty($terms) && !is_wp_error($terms)) {
				$first = reset($terms);

				if ($first) {
					$content = '<a href="' . esc_url(get_tag_link($first->term_id)) . '"><span class="meta_wrapper">' .
						$icon . esc_html($first->name) .
						'</span></a>';
				}
			}
		}

		// ---- 2. DATE MODE ----
		elseif ($type === 'date') {
			if ($theme_style === 'theme-two') {
				$event_date_style    = EventfulFunctions::eventful_metabox_value('event_thumb_meta_date_type', $options);
				$meta_date_format  = EventfulFunctions::eventful_metabox_value('event_thumb_meta_date_format', $options);
				$custom_date_format  = EventfulFunctions::eventful_metabox_value('event_thumb_meta_custom_date_format', $options);

				if ('start_date' === $event_date_style) {
					if ('custom' === $meta_date_format) {
						$event_date = tribe_get_start_date($event->ID, false, $custom_date_format);
					} else {
						$event_date = tribe_get_start_date($event->ID, false, $meta_date_format);
					}
				} elseif ('end_date' === $event_date_style) {
					if ('custom' === $meta_date_format) {
						$event_date = tribe_get_end_date($event->ID, false, $custom_date_format);
					} else {
						$event_date = tribe_get_end_date($event->ID, false, $meta_date_format);
					}
				} else {
					if ('custom' === $meta_date_format) {
						$event_date = tribe_get_start_date($event->ID, true, $custom_date_format) . ' - ' . tribe_get_end_date($event->ID, true, $custom_date_format);
					} else {
						$event_date = tribe_get_start_date($event->ID, true, $meta_date_format) . ' - ' . tribe_get_end_date($event->ID, true, $meta_date_format);
					}
				}

				$content = '<span class="meta_wrapper">' . $icon . '
	            <span class="date">' . esc_html($event_date) . '</span></span>
	        ';
			} else {
				$day = tribe_get_start_date($event->ID, false, 'd');
				$month = tribe_get_start_date($event->ID, false, 'M');

				$content = '<span class="meta_wrapper">
				<span class="day">' . esc_html($day) . '</span><br>
				<span class="month">' . esc_html($month) . '</span>
				</span>';
			}
		} elseif ($type === 'venue') {
			$venue_id   = tribe_get_venue_id($event->ID);
			$venue_url  = get_permalink($venue_id);
			$venue_name = get_the_title($venue_id);
			$content = '<a href="' . esc_url($venue_url) . '"><span class="meta_wrapper">' . $icon . '' . esc_html($venue_name) . '</span></a>';
		} elseif ($type === 'organizer') {
			$organizer   = tribe_get_organizer($event->ID, true);
			$organizer_web_url   = tribe_get_organizer_website_url($event->ID);
			$content = '<a href="' . esc_url($organizer_web_url) . '"><span class="meta_wrapper">' . $icon . '' . esc_html($organizer) . '</span></a>';
		} elseif ($type === 'social_media') {
			$permalink = esc_url(get_permalink($event));
			$title     = esc_attr(get_the_title($event));
			$content = '<div class="social_media_wrapper">
							<div class="meta_wrapper">' . $icon . '</div>
							<ul class="social_media">
								<li><a href="https://www.facebook.com/sharer/sharer.php?u=' . $permalink . '" target="_blank" rel="nofollow noopener" title="' . esc_attr__('Facebook', 'eventful') . '" onclick="window.open(this.href, \'facebook-share\', \'width=450,height=300,left=\'+(screen.availWidth/2-225)+\',top=\'+(screen.availHeight/2-150)); return false;"> <i class="icofont-facebook"></i></a></li>
								<li> <a href="https://twitter.com/share?url=' . $permalink . '&text=' . $title . '" target="_blank" rel="nofollow noopener" title="' . esc_attr__('Twitter', 'eventful') . '" onclick="window.open(this.href, \'twitter-share\', \'width=450,height=300,left=\'+(screen.availWidth/2-225)+\',top=\'+(screen.availHeight/2-150)); return false;"> <i class="icofont-twitter"></i> </a></li>
								<li><a href="https://www.linkedin.com/shareArticle?mini=true&url=' . $permalink . '" target="_blank" rel="nofollow noopener" title="' . esc_attr__('LinkedIn', 'eventful') . '" onclick="window.open(this.href, \'linkedin-share\', \'width=550,height=400,left=\'+(screen.availWidth/2-275)+\',top=\'+(screen.availHeight/2-200)); return false;"><i class="icofont-linkedin"></i></a></li>
								<li><a href="mailto:?subject=' . rawurlencode($title) . '&body=' . rawurlencode($permalink) . '" title="' . esc_attr__('Email', 'eventful') . '"><i class="icofont-envelope"></i></a></li>
								<li><a href="https://api.whatsapp.com/send?text=' . rawurlencode($title . ' ' . $permalink) . '" target="_blank" rel="nofollow noopener" title="' . esc_attr__('WhatsApp', 'eventful') . '" onclick="window.open(this.href, \'whatsapp-share\', \'width=450,height=300,left=\'+(screen.availWidth/2-225)+\',top=\'+(screen.availHeight/2-150)); return false;"><i class="icofont-whatsapp"></i></a></li>
							</ul>
						</div>';
		}
		?>
		<div class="eventful__item__thumb_meta <?php echo esc_attr($position . ' ' . $side . ' ' . $type); ?>"
			style="<?php echo wp_kses_post($gradient_style); ?>">
			<?php if (!empty($content)) : ?>
				<?php echo wp_kses_post($content); ?>
			<?php endif; ?>
		</div>
<?php
	}

	public static function category($event, $all = false)
	{
		$terms = get_the_terms($event->ID, 'tribe_events_cat');

		if (!empty($terms) && !is_wp_error($terms)) {


			if ($all) {
				$term_links = [];

				foreach ($terms as $term) {
					$term_link = get_term_link($term);

					if (!is_wp_error($term_link)) {
						$term_links[] = '<a href="' . esc_url($term_link) . '">' . esc_html($term->name) . '</a>';
					}
				}

				echo wp_kses_post( implode( ', ', $term_links ) );
			} else {
				$first = reset($terms);

				if ($first) {
					echo '<a class="event_category" href="' . esc_url(get_tag_link($first->term_id)) . '">' . esc_html($first->name) . '</a>';
				}
			}
		}
	}
	public static function date($event)
	{
		$event_start_date = tribe_get_start_date($event->ID, false, 'g:i a');
		$event_end_date = tribe_get_end_date($event->ID, false, 'g:i a');
		echo '<span class="event_date"><i class="icofont-clock-time"></i>' . esc_html($event_start_date) . ' - ' . esc_html($event_end_date) . '</span>';
	}

	public static function eventful_tribe_tickets_buy_button($event_id, $echo = true)
	{
		// get an array for ticket and rsvp counts
		$types = \Tribe__Tickets__Tickets::get_ticket_counts($event_id);
		// if no rsvp or tickets return
		if (! $types) {
			return null;
		}

		$html  = array();
		$parts = array();

		// If we have tickets or RSVP, but everything is Sold Out then display the Sold Out message
		foreach ($types as $type => $data) {
			if (! $data['count']) {
				continue;
			}



			if (! $data['available']) {
				$parts[$type . '-stock'] = '<span class="tribe-out-of-stock">' . esc_html_x('Sold out', 'list view stock sold out', 'eventful') . '</span>';

				// Only re-apply if we don't have a stock yet
				if (empty($html['stock'])) {
					$html['stock'] = $parts[$type . '-stock'];
				}
			} else {
				$stock = $data['stock'];

				if ($data['unlimited'] || ! $data['stock']) {
					// if unlimited tickets, tickets with no stock and rsvp, or no tickets and rsvp unlimited - hide the remaining count
					$stock = false;
				}

				$stock_html = '';

				if ($stock) {
					$threshold = \Tribe__Settings_Manager::get_option('ticket-display-tickets-left-threshold', 0);

					/**
					 * Overwrites the threshold to display "# tickets left".
					 *
					 * @param int   $threshold Stock threshold to trigger display of "# tickets left"
					 * @param array $data      Ticket data.
					 * @param int   $event_id  Event ID.
					 *
					 * @since 4.10.1
					 */
					$threshold = absint(apply_filters('tribe_display_tickets_left_threshold', $threshold, $data, $event_id));

					if (! $threshold || $stock <= $threshold) {

						$number = number_format_i18n($stock);
						if ('rsvp' === $type) {
							$text = _n('%s spot left', '%s spots left', $stock, 'eventful');
						} else {
							$text = _n('%s ticket left', '(%s tickets left)', $stock, 'eventful');
						}

						$stock_html = '<span class="tribe-tickets-left">'
							. esc_html(sprintf($text, $number))
							. '</span>';
					}
				}


				$parts[$type . '-stock'] = $html['stock'] = $stock_html;

				if ('rsvp' === $type) {
					$button_label  = __('RSVP Now', 'eventful');
					$button_anchor = '#rsvp-now';
				} else {
					$button_label  = __('Buy Now', 'eventful');
					$button_anchor = '#tpp-buy-tickets';
				}

				$permalink    = get_the_permalink($event_id);
				$query_string = parse_url($permalink, PHP_URL_QUERY);
				$query_params = empty($query_string) ? array() : (array) explode('&', $query_string);

				// $button = '<form method="get" action="' . esc_url( $permalink . $button_anchor ) . '">';

				$html['link'] = '<a href="' . esc_url($permalink . $button_anchor) . '">' . $button_label . '</a>';
			}
		}

		/**
		 * Filter the ticket count and purchase button
		 *
		 * @since  4.5
		 *
		 * @param array $html     An array with the final HTML
		 * @param array $parts    An array with all the possible parts of the HTMl button
		 * @param array $types    Ticket and RSVP count array for event
		 * @param int   $event_id Post Event ID
		 */
		$html = apply_filters('tribe_tickets_buy_button', $html, $parts, $types, $event_id);
		$html = implode("\n", $html);

		if ($echo) {
			echo wp_kses_post( $html );
		}

		return $html;
	}

	public static function event_price_content($event_price, $event_id)
	{
		if (empty($event_price)) {
			return '';
		}

		$price_html  = '<span class="event_price">';
		$price_html .= '<span><i class="icofont-air-ticket"></i>' . esc_html($event_price) . '</span>';

		if (class_exists('Tribe__Tickets__Main')) {
			$price_html .= '<span class="ect-ticket-info">';
			$price_html .= EventfulFunctions::eventful_tribe_tickets_buy_button($event_id, false);
			$price_html .= '</span>';
		}

		$price_html .= '</span>';

		return $price_html;
	}
}
