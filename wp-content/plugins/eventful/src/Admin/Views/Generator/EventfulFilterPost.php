<?php

namespace ThemeAtelier\Eventful\Admin\Views\Generator;

use ThemeAtelier\Eventful\Admin\Framework\Classes\Eventful;

/**
 * The Filter Event Meta-box configurations.
 *
 * @package Eventful
 * @subpackage admin
 */

if (!defined('ABSPATH')) {
	die;
} // Cannot access pages directly.

/**
 * The Filter post building class.
 */
class EventfulFilterPost
{

	/**
	 * Filter Event section metabox.
	 *
	 * @param string $prefix The metabox key.
	 * @return void
	 */
	public static function section($prefix)
	{
		Eventful::createSection(
			$prefix,
			array(
				'title'  => esc_html__('Filter Events', 'eventful'),
				'icon'   => 'icofont-filter',
				'fields' => array(
					array(
						'id'      => 'event_filter',
						'type'    => 'select',
						'title' => esc_html__('Filter Events', 'eventful'),
						'title_help' => esc_html__('Select how events should be filtered: latest events, featured events, or manually selected events.', 'eventful') . ' <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'docs/filter-events/?ref=1') . '">' . esc_html__('Open Docs', 'eventful') . '</a> <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'specific-events/?ref=1') . '">' . esc_html__('Open Demo', 'eventful') . '</a>',
						'subtitle' => esc_html__('Filter the events you want to show.', 'eventful'),
						'options' => array(
							'latest'	=> esc_html__('Latest', 'eventful'),
							'feature'	=> esc_html__('Feature', 'eventful'),
							'specific'	=> esc_html__('Specific', 'eventful'),
						),
						'attributes' => array(
							'style' => 'width: 200px;',
						),
						'default' => '_EventStartDate',
					),
					array(
						'id'          => 'eventful_include_only_posts',
						'type'        => 'select',
						'title' => esc_html__('Include Only', 'eventful'),
						'title_help' => esc_html__('Manually choose specific events to include in the listing. Only selected events will be shown.', 'eventful'),
						'placeholder' => esc_html__('Choose events', 'eventful'),
						'options'     => 'posts',
						'ajax'        => false,
						'sortable'    => true,
						'chosen'      => true,
						'multiple'    => true,
						'query_args'  => array(
							'post_type' => 'tribe_events',
							'posts_per_page' => -1,
							'cache_results' => false,
							'no_found_rows' => true,
						),
						'dependency'  => array('event_filter', 'any', 'specific'),
					),
					array(
						'id'       => 'eventful_exclude_post_set',
						'type'     => 'fieldset',
						'title' => esc_html__('Exclude', 'eventful'),
						'title_help' => esc_html__('Exclude selected events or event types from appearing in the listing.', 'eventful'),
						'class'    => 'eventful_exclude_post_set',
						'fields'   => array(
							array(
								'id'          => 'eventful_exclude_posts',
								'type'        => 'select',
								'options'     => 'posts',
								'chosen'      => true,
								'class'       => 'eventful_exclude_events',
								'multiple'    => true,
								'ajax'        => false,
								'placeholder' => esc_html__('Choose events to exclude', 'eventful'),
								'query_args'  => array(
									'post_type' => 'tribe_events',
									'posts_per_page' => -1,
									'cache_results' => false,
									'no_found_rows' => true,
								),
								'dependency'  => array('eventful_include_only_posts', '==', '', true),
							),
							array(
								'id'      => 'eventful_exclude_too',
								'type'    => 'checkbox',
								'options' => array(
									'current'            => esc_html__('Current Event', 'eventful'),
									'password_protected' => esc_html__('Password Protected Events', 'eventful'),
								),
							),
						),
						'dependency'  => array('event_filter', 'any', 'specific'),
					),
					array(
						'id'       => 'eventful_event_type',
						'type'     => 'button_set',
						'title'    => esc_html__('Type of Event', 'eventful'),
						'subtitle' => esc_html__('Choose which events to display: upcoming events, past events, or all events.', 'eventful'),
						'options'   => array(
							'future' => array(
								'text' => esc_html__('Upcoming', 'eventful'),
							),
							'past'   => array(
								'text'	=> esc_html__('Past', 'eventful'),
								'pro_only' => true,
							),
							'all'    => array(
								'text'	=> esc_html__('All (Upcoming + Past)', 'eventful'),
								'pro_only' => true,
							),
						),
						'default'  => 'future',
						'dependency'  => array('eventful_include_only_posts|eventful_exclude_posts', '==|==', '' | '', true),
					),
					array(
						'id'    => 'upcoming_by_start_date',
						'type'  => 'checkbox',
						'title' => esc_html__('Determine Event Status By Event start date', 'eventful'),
						'label' => esc_html__('Event start date', 'eventful'),
						'subtitle' => esc_html__('Events are upcoming until they begin, and past once they start.', 'eventful'),
						'dependency' => array('eventful_include_only_posts|eventful_exclude_posts|eventful_event_type', '==|==|==', '||future', true),
					),
					array(
						'id'         => 'hide_free_events',
						'type'       => 'checkbox',
						'title'      => esc_html__('Hide Free Events', 'eventful'),
						'subtitle' 	=> esc_html__('Check to hide free events.', 'eventful'),
					),
					array(
						'id'         => 'hide_event_without_thumbnail',
						'type'       => 'checkbox',
						'title'      => esc_html__('Hide Events Without Featured Images', 'eventful'),
						'subtitle' 	=> esc_html__('Hide event if featured image not exist', 'eventful'),
					),
					array(
						'id'      => 'filter_order_by',
						'type'    => 'select',
						'title'   => esc_html__('Order by', 'eventful'),
						'subtitle' => esc_html__('Set a order by option.', 'eventful'),
						'options' => array(
							'id'           		=> esc_html__('ID', 'eventful'),
							'title'        		=> esc_html__('Title', 'eventful'),
							'_EventStartDate'   => esc_html__('Event Start Date', 'eventful'),
							'post_slug'    		=> esc_html__('Event slug', 'eventful'),
							'rand'         		=> esc_html__('Random', 'eventful'),
						),
						'default' => '_EventStartDate',
					),
					array(
						'id'         => 'filter_order',
						'type'       => 'select',
						'title'      => esc_html__('Order', 'eventful'),
						'subtitle' => esc_html__('Choose ascending or descending order for events.', 'eventful'),
						'options'    => array(
							'ASC'  => esc_html__('Ascending', 'eventful'),
							'DESC' => esc_html__('Descending', 'eventful'),
						),
						'default'    => 'ASC',
					),
					array(
						'id'       => 'eventful_event_limit',
						'type'     => 'spinner',
						'title' => esc_html__('Limit', 'eventful'),
						'title_help' => esc_html__('Set the maximum number of events to display. Leave empty to show all events.', 'eventful') . '<div> <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'docs/filter-events/?ref=1') . '">' . esc_html__('Open Docs', 'eventful') . '</a></div>',
						'subtitle' => esc_html__('Set the total number of events to display.', 'eventful'),
						'sanitize' => 'eventful_sanitize_number_field',
						'default'  => 15,
						'min'      => 1,
					),
					array(
						'id'       => 'condense_events_in_series',
						'type'     => 'button_set',
						'class'     => 'condense_events_in_series',
						'title' => esc_html__('Condense Events in Series', 'eventful'),
						'title_help' => '<div class="eventful-info-content">' . esc_html__('Due to a TEC limitation, only upcoming events are displayed when this option is enabled.', 'eventful') . '</div> <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'docs/filter-events/?ref=1') . '">' . esc_html__('Open Docs', 'eventful') . '</a>',
						'subtitle' => esc_html__('Display only the next upcoming event from each event series when using Events Calendar Pro.', 'eventful'),
						'options'   => array(
							'default' => array(
								'text' => esc_html__('Default', 'eventful'),
							),
							'enabled'   => array(
								'text'	=> esc_html__('Enabled', 'eventful'),
								'pro_only' => true,
							),
							'disabled'    => array(
								'text'	=> esc_html__('Disabled', 'eventful'),
							),
						),
						'default'	=> 'default',
						'dependency'  => array('eventful_include_only_posts|eventful_exclude_posts', '==|==', '' | '', true),
					),
					array(
						'id'         => 'seo_schema',
						'type'       => 'switcher',
						'title'      => esc_html__('SEO Schema Markup', 'eventful'),
						'subtitle' 	=> esc_html__('Enable/Disable schema markup.', 'eventful'),
						'title_help' => '<div class="eventful-info-label">' . esc_html__('Schema Markup', 'eventful') . '</div><div class="eventful-info-content">' . __('<b>Schema Markup</b> adds structured data to your Event Showcase, enhancing search engine visibility and improving the display of your Event Showcase in search results.', 'eventful') . '</div> <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'docs/advanced/?ref=1') . '">' . esc_html__('Open Docs', 'eventful') . '</a>',
						'text_on'    => esc_html__('Enabled', 'eventful'),
						'text_off'   => esc_html__('Disabled', 'eventful'),
						'text_width' => 100,
						'class'    => 'switcher_pro_only',
					),
					array(
						'type'    => 'subheading',
						'content' => esc_html__('Filtering', 'eventful'),
					),
					array(
						'id'       => 'eventful_advanced_filter',
						'type'     => 'checkbox',
						'class'    => 'eventful_column_2 eventful_advanced_filter',
						'title'    => esc_html__('Filter Type', 'eventful'),
						'title_help' => '<div class="eventful-info-label">' . esc_html__('Filter Type', 'eventful') . '</div> <div>' . __('Enable <b>Keyword Search</b> to let users filter events by search terms and display a search bar. Enable <b>Advanced Filtering</b> to add powerful filters such as taxonomies, venues, organizers, event type, timeframe, month, and more.', 'eventful') . '</div> <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'ajax-live-filter/?ref=1') . '">' . esc_html__('Demo', 'eventful') . '</a> <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'docs/filter-events/?ref=1') . '">' . esc_html__('Open Docs', 'eventful') . '</a>',
						'subtitle' => esc_html__('Select the filtering options you want to enable for event filtering.', 'eventful'),
						'options'  => array(
							'keyword'      		=> esc_html__('Keyword Search', 'eventful'),
							'filter_option'     => esc_html__('Advanced Filtering', 'eventful'),
							'event_start_end'   => esc_html__('Event Date & Time (Pro)', 'eventful'),
						),
					),
					array(
						'id'         => 'eventful_filter_by_keyword',
						'type'       => 'accordion',
						'class'      => 'padding-t-0 eventful-opened-accordion',
						'accordions' => array(
							array(
								'title'  => esc_html__('Keyword Search Options', 'eventful'),
								'icon'   => 'icofont-key',
								'fields' => array(
									array(
										'id'         => 'eventful_set_event_keyword',
										'type'       => 'text',
										'title'      => esc_html__('Type Keyword', 'eventful'),
										'desc'   => esc_html__('Enter a keyword to display events that match this keyword.', 'eventful'),
										'title_help' => '<div class="eventful-info-content">' . esc_html__('Enter keyword to filter and search through event titles, content, and metadata.', 'eventful') . '</div> <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'docs/filter-events/?ref=1#advanced-filtering-keyword-search') . '">' . esc_html__('Open Docs', 'eventful') . '</a> <a class="tooltip_btn_secondary" target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'ajax-live-filter/') . '">' . esc_html__('Demo', 'eventful') . '</a>',
									),
									array(
										'id'         => 'add_search_filter_post',
										'type'       => 'checkbox',
										'title'      => esc_html__('Show Search Bar', 'eventful'),
										'desc' => esc_html__('Check this option to display a keyword search input for filtering events.', 'eventful'),
										'dependency' => array('eventful_layout_preset', '!=', 'filter_layout', true),
										'default'	=> true,
									),
									array(
										'id'         => 'ajax_filter_options',
										'type'       => 'fieldset',
										'title'      => esc_html__('Search bar', 'eventful'),
										'dependency' => array('add_search_filter_post', '==', 'true', true),
										'fields'     => array(
											array(
												'id'    => 'ajax_search_filter_label',
												'type'  => 'text',
												'title' => esc_html__('Label', 'eventful'),
												'desc' => esc_html__('Set a label text displayed above or beside the search input field.', 'eventful'),
											),
											array(
												'id'    => 'ajax_filter_placeholder',
												'type'  => 'text',
												'title' => esc_html__('Placeholder', 'eventful'),
												'desc' => esc_html__('Define the placeholder text shown inside the search input field.', 'eventful'),
												'default'	=> esc_html__('Search...', 'eventful')
											),
											array(
												'id'      => 'eventful_live_filter_align',
												'type'    => 'button_set',
												'title'   => esc_html__('Alignment', 'eventful'),
												'options'    => array(
													'left'   => wp_kses(__('<i class="icofont-align-left" title="Left"></i>', 'eventful'), array('i' => array('class' => array()))),
													'center' => wp_kses(__('<i class="icofont-align-center" title="Center"></i>', 'eventful'), array('i' => array('class' => array()))),
													'right'  => wp_kses(__('<i class="icofont-align-right" title="Right"></i>', 'eventful'), array('i' => array('class' => array()))),
												),
												'default' => 'center',
											),

											array(
												'id'       => 'search_bar_width',
												'title'    => esc_html__('Max Width', 'eventful'),
												'desc' => esc_html__('Set the maximum width of the search field.', 'eventful'),
												'type'     => 'dimensions',
												'height'	=> false,
											),
										),
									),
								),
							),
						),
						'dependency' => array('eventful_advanced_filter', 'not-any', 'filter_option,event_start_end'),
					),
					array(
						'id'         => 'eventful_filter_options',
						'type'       => 'accordion',
						'class'      => 'padding-t-0 eventful-opened-accordion',
						'accordions' => array(
							array(
								'title'  => esc_html__('Advanced Filtering Options', 'eventful'),
								'icon'   => "icofont-filter",
								'fields' => array(
									// The Group Fields.
									array(
										'id'     => 'filter_options_group',
										'type'   => 'group',
										'accordion_title_auto' => true,
										'fields' => array(
											array(
												'id'      => 'filter_option',
												'type'    => 'select',
												'title'   => esc_html__('Select Option', 'eventful'),
												'desc' 	  => esc_html__('Select which event data you want to filter by.', 'eventful'),
												'options' => array(
													'category'	=> esc_html__('Event Categories', 'eventful'),
													'event_tag'	=> esc_html__('Event Tags', 'eventful'),
													'venue'	=> esc_html__('Event Venue (Pro)', 'eventful'),
													'organizer'	=> esc_html__('Organizer (Pro)', 'eventful'),
													'event_type_time'	=> esc_html__('Event Type (Time-Based) (Pro)', 'eventful'),
													'event_timeframe'	=> esc_html__('Event Timeframe (Pro)', 'eventful'),
													'event_month'	=> esc_html__('Event Month (Pro)', 'eventful'),
												),
												'attributes' => array(
													'style' => 'width: 220px;',
												),
											),
											array(
												'id'       => 'eventful_select_categories',
												'type'     => 'select',
												'title'    => esc_html__('Choose Category(s)', 'eventful'),
												'desc' => esc_html__('Choose the category(s) to show events from. Leave empty to display events from all available categories.', 'eventful'),
												'empty_message' => esc_html__('No categories found.', 'eventful'),
												'placeholder'   => esc_html__('Select Category(s)', 'eventful'),
												'options' => 'categories',
												'query_args'  => array(
													'post_type' => 'tribe_events',
													'taxonomy'  => 'tribe_events_cat',
													'posts_per_page'  => -1,
												),
												'width'    => '300px',
												'multiple' => true,
												'sortable' => true,
												'chosen'   => true,
												'dependency' => array('filter_option', '==', 'category'),
											),
											array(
												'id'       => 'eventful_select_tags',
												'type'     => 'select',
												'title'    => esc_html__('Choose Tag(s)', 'eventful'),
												'desc' => esc_html__('Choose the tags to show events from. Leave empty to include events from all tags.', 'eventful'),
												'empty_message' => esc_html__('No tags found.', 'eventful'),
												'placeholder'   => esc_html__('Select Tag(s)', 'eventful'),
												'options'  => 'tags',
												'query_args'  => array(
													'post_type' => 'tribe_events',
													'taxonomy'  => 'post_tag',
													'posts_per_page'  => -1,
												),
												'width'    => '300px',
												'multiple' => true,
												'sortable' => true,
												'chosen'   => true,
												'dependency' => array('filter_option', '==', 'event_tag'),
											),
											array(
												'id'      => 'filter_option_operator',
												'type'    => 'select',
												'title'   => esc_html__('Operator', 'eventful'),
												'title_help' => '<div class="eventful-info-contnet">' . esc_html__('IN - Show events which associate with one or more terms', 'eventful') . '<br>' . esc_html__('AND - Show events which match all terms', 'eventful') . '<br>' . esc_html__('NOT IN - Show events which don\'t match the terms', 'eventful') . '</div><a class="tooltip_btn_primary" target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'docs/filter-events/?ref=1#advanced-filtering-venue-organizer-taxonomy') . '">' . esc_html__('Open Docs', 'eventful') . '</a>',
												'dependency' => array('filter_option', 'not-any', 'event_type_time,venue,organizer'),
												'options' => array(
													'IN'  => esc_html__('IN', 'eventful'),
													'AND' => esc_html__('AND', 'eventful'),
													'NOT IN' => esc_html__('NOT IN', 'eventful'),
												),
												'default' => 'IN',

											),
											array(
												'id'    => 'add_filter_option_event',
												'type'  => 'checkbox',
												'title' => esc_html__('Show Ajax Live Filter', 'eventful'),
												'desc' => esc_html__('Check this option to enable the event filter in Ajax Live Filters.', 'eventful'),
												'dependency' => array('filter_option', '!=', ''),
											),
											array(
												'id'     => 'ajax_filter_options',
												'type'   => 'fieldset',
												'title'  => esc_html__('Ajax Live Filter Options', 'eventful'),
												'class'	 => 'ajax_live_filter_fieldset',
												'dependency' => array('add_filter_option_event', '==', 'true'),
												'fields' => array(
													array(
														'id'       => 'ajax_filter_style',
														'type'     => 'layout_preset',
														'title'    => esc_html__('Filter Type', 'eventful'),
														'subtitle' => esc_html__('Select a type for live filter.', 'eventful'),
														'class'    => 'filter_type',
														'options' => array(
															'fl_btn'  => array(
																'image' => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/filter-type/button.svg',
																'text'  => esc_html__('Button', 'eventful'),
																'option_demo_url' => EVENTFUL_DEMO_URL . 'events-carousel/#button',
															),
															'fl_dropdown'      => array(
																'image' => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/filter-type/dropdown.svg',
																'text'  => esc_html__('Dropdown', 'eventful'),
																'option_demo_url' => EVENTFUL_DEMO_URL . 'ajax-live-filter/',
																'pro_only'        => true,
															),
															'fl_radio'  => array(
																'image' => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/filter-type/radio.svg',
																'text'  => esc_html__('Radio', 'eventful'),
																'option_demo_url' => EVENTFUL_DEMO_URL . 'events-list/',
																'pro_only'        => true,
															),
															'fl_checkbox'  => array(
																'image' => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/filter-type/radio.svg',
																'text'  => esc_html__('Checkbox', 'eventful'),
																'option_demo_url' => EVENTFUL_DEMO_URL . 'center-carousel/#checkbox',
																'pro_only'        => true,
															),
														),
														'default'  => 'fl_btn',
													),
													array(
														'id'      => 'ajax_filter_icon',
														'class'      => 'select_event_filter_icon',
														'type'    => 'icon',
														'title'   => esc_html__('Filter Icon', 'eventful'),
														'subtitle' => esc_html__('Select an icon for the filter button.', 'eventful'),
														'default' => 'icofont-filter',
														'dependency' => array('ajax_filter_style', 'any', 'fl_dropdown,fl_btn'),
													),
													array(
														'id'       => 'eventful_filter_btn_color',
														'type'     => 'color_group',
														'title'    => esc_html__('Button Color', 'eventful'),
														'subtitle' => esc_html__('Set the color for the button text, border, background, and hover states.', 'eventful'),
														'dependency' => array('ajax_filter_style', '==', 'fl_btn'),
														'options'  => array(
															'text_color'        => esc_html__('Text', 'eventful'),
															'text_acolor'       => esc_html__('Text Active', 'eventful'),
															'border_color'      => esc_html__('Border', 'eventful'),
															'border_acolor'     => esc_html__('Border Active', 'eventful'),
															'background'        => esc_html__('Background', 'eventful'),
															'active_background' => esc_html__('Active Background', 'eventful'),
														),
													),

													array(
														'id'       => 'ajax_filter_label',
														'type'     => 'text',
														'title'    => esc_html__('Label', 'eventful'),
														'subtitle' => esc_html__('Enter the label to show before the live filter.', 'eventful'),
													),
													array(
														'id'       => 'ajax_rename_all_text',
														'type'     => 'text',
														'title'    => esc_html__('Rename "All" Text', 'eventful'),
														'subtitle' => esc_html__('Rename the "All" text shown in the filter. Leave empty to hide.', 'eventful'),
														'default'  => esc_html__('All', 'eventful'),
														'dependency' => array('ajax_filter_style', '!=', 'fl_checkbox'),
													),
													array(
														'id'       => 'ajax_hide_empty',
														'type'     => 'checkbox',
														'title'    => esc_html__('Hide Empty Option(s)', 'eventful'),
														'subtitle' => esc_html__('Check to hide filter options with no events.', 'eventful'),
													),
													array(
														'id'       => 'ajax_show_count',
														'type'     => 'checkbox',
														'title'    => esc_html__('Show Event Count', 'eventful'),
														'subtitle' => esc_html__('Check to show the number of events for each filter option.', 'eventful'),
														'default'  => true,
													),
													array(
														'id'       => 'eventful_live_filter_align',
														'type'     => 'button_set',
														'title'    => esc_html__('Alignment', 'eventful'),
														'subtitle' => esc_html__('Set alignment of the live filter buttons.', 'eventful'),
														'options'    => array(
															'left'   => wp_kses(__('<i class="icofont-align-left" title="Left"></i>', 'eventful'), array('i' => array('class' => array()))),
															'center' => wp_kses(__('<i class="icofont-align-center" title="Center"></i>', 'eventful'), array('i' => array('class' => array()))),
															'right'  => wp_kses(__('<i class="icofont-align-right" title="Right"></i>', 'eventful'), array('i' => array('class' => array()))),
														),
														'dependency' => array('ajax_filter_style', '!=', 'fl_dropdown'),
														'default'  => 'center',
													),
												),
											),
										),
										'default'   => array(
											array(
												'filter_option' => 'category',
												'filter_option_operator' => 'IN',
												'add_orderby_filter_post' => true,
												'add_filter_option_event' => true,
												'ajax_filter_options' => array(
													'ajax_filter_style' => 'fl_btn',
													'ajax_filter_icon' => 'icofont-ui-folder',
													'ajax_rename_all_text' => 'All Category',
													'ajax_show_count' => true,
												)
											),
											array(
												'filter_option' => 'event_tag',
												'filter_option_operator' => 'IN',
												'add_orderby_filter_post' => true,
												'add_filter_option_event' => true,
												'ajax_filter_options' => array(
													'ajax_filter_style' => 'fl_btn',
													'ajax_filter_icon' => 'icofont-tags',
													'ajax_rename_all_text' => 'All Tags',
													'ajax_show_count' => true,
												)
											),

										),
									),
									array(
										'id'       => 'filter_option_columns',
										'type'     => 'column',
										'title'    => esc_html__('Filter Option Column(s)', 'eventful'),
										'desc' => esc_html__('Set the number of column(s) in different devices for a responsive view.', 'eventful'),
										'title_help' => '<i class="icofont-imac"></i> <b>' . esc_html__('Large Desktop', 'eventful') . '</b> - ' . esc_html__('width', 'eventful') . ' &gt; 1200px<br>' . '<i class="icofont-monitor"></i> <b>' . esc_html__('Desktop', 'eventful') . '</b> - ' . esc_html__('width', 'eventful') . ' &gt; 992px<br>' . '<i class="icofont-laptop-alt"></i> <b>' . esc_html__('Tablet', 'eventful') . '</b> - ' . esc_html__('width', 'eventful') . ' &gt; 768px<br>' .
											'<i class="icofont-ipad"></i> <b>' . esc_html__('Mobile Landscape', 'eventful') . '</b> - ' . esc_html__('width', 'eventful') . ' &gt; 576px<br>' . '<i class="icofont-android-tablet"></i> <b>' . esc_html__('Mobile', 'eventful') . '</b> - ' . esc_html__('width', 'eventful') . ' &lt;= 576px',
										'default'  => array(
											'lg_desktop'       => '1',
											'desktop'          => '1',
											'tablet'           => '1',
											'mobile_landscape' => '1',
											'mobile'           => '1',
										),
										'min'      => '1',
									),
									array(
										'id'              => 'margin_between_filter',
										'type'            => 'spacing',
										'title'           => esc_html__('Margin Between Columns', 'eventful'),
										'desc' => esc_html__('Adjust the spacing between each filter column. Use px values only.', 'eventful'),
										'all_placeholder' => esc_html__('Margin', 'eventful'),
										'all'             => true,
										'all_icon'        => '<i class="icofont-drag"></i>',
										'units'           => array(
											'px',
										),
										'default'         => array(
											'all' => '20',
										),
									),

								), // Fields array.
							),
						), // Accordions end.
						'dependency' => array('eventful_advanced_filter', 'not-any', 'event_start_end,keyword'),
					),
					array(
						'id'       => 'advance_filter_reset_button',
						'type'     => 'switcher',
						'title'    => esc_html__('Advance Filter Reset Button', 'eventful'),
						'subtitle' => esc_html__('Enable to show a reset button for advanced filters.', 'eventful'),
						'text_on'  => esc_html__('Enabled', 'eventful'),
						'text_off' => esc_html__('Disabled', 'eventful'),
						'text_width' => 100,
						'default'	=> true,
						'dependency' => array('eventful_advanced_filter', 'any', 'keyword,filter_option'),
					),
					array(
						'id'       => 'advance_filter_wrapper_margin',
						'type'     => 'spacing',
						'title'    => esc_html__('Filter Wrapper Margin Bottom', 'eventful'),
						'subtitle' => esc_html__('Set the bottom margin for the advanced filter wrapper.', 'eventful'),
						'left'  => false,
						'top' => false,
						'right' => false,
						'default'  => array(
							'top'    => '0',
							'right'  => '0',
							'bottom' => '30',
							'left'   => '0',
							'unit'   => 'px',
						),
						'dependency' => array('eventful_advanced_filter', 'any', 'keyword,filter_option'),
					),
					array(
						'id'       => 'show_hide_filter_button',
						'type'     => 'switcher',
						'title'    => esc_html__('Show Hide Filter Button', 'eventful'),
						'subtitle' => esc_html__('Display a button to toggle the advanced filter panel.', 'eventful'),
						'text_on'  => esc_html__('Show', 'eventful'),
						'text_off' => esc_html__('Hide', 'eventful'),
						'text_width' => 75,
						'dependency' => array('add_search_filter_post|eventful_advanced_filter', '==|any', 'true|filter_option'),
					),
					array(
						'id'       => 'show_button_text',
						'type'     => 'text',
						'title'    => esc_html__('Show Button Text', 'eventful'),
						'subtitle' => esc_html__('Text displayed on the button when the filter is hidden.', 'eventful'),
						'default'  => esc_html__('Show Filter', 'eventful'),
						'dependency' => array(
							array('eventful_advanced_filter', 'any', 'keyword,filter_option'),
							array('add_search_filter_post', '==', true),
							array('show_hide_filter_button', '==', true),
						),
					),
					array(
						'id'       => 'hide_button_text',
						'type'     => 'text',
						'title'    => esc_html__('Hide Button Text', 'eventful'),
						'subtitle' => esc_html__('Text displayed on the button when the filter is visible.', 'eventful'),
						'default'  => esc_html__('Hide Filter', 'eventful'),
						'dependency' => array(
							array('eventful_advanced_filter', 'any', 'keyword,filter_option'),
							array('add_search_filter_post', '==', true),
							array('show_hide_filter_button', '==', true),
						),
					),
					array(
						'id'       => 'show_hide_filter_button_background',
						'type'     => 'color_group',
						'title'    => esc_html__('Show Hide Filter Button Background', 'eventful'),
						'subtitle' => esc_html__('Set the color and hover color for the Show/Hide filter button.', 'eventful'),
						'options'   => array(
							'text'       => esc_html__('Color', 'eventful'),
							'hover_text' => esc_html__('Hover Color', 'eventful'),
							'color' => esc_html__('Color', 'eventful'),
							'hover_color' => esc_html__('Hover Color', 'eventful'),
						),
						'dependency' => array(
							array('eventful_advanced_filter', 'any', 'keyword,filter_option'),
							array('add_search_filter_post', '==', true),
							array('show_hide_filter_button', '==', true),
						),
					),
				),
			)
		); // Filter settings section end.
	}
}
