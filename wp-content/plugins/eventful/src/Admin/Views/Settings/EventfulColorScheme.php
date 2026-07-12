<?php

/**
 * The Enqueue and Dequeue CSS and JS files setting configurations.
 *
 * @package Eventful
 * @subpackage Eventful/admin
 */

namespace ThemeAtelier\Eventful\Admin\Views\Settings;

use ThemeAtelier\Eventful\Admin\Framework\Classes\Eventful;

if (! defined('ABSPATH')) {
	die;
} // Cannot access pages directly.

/**
 * The Layout building class.
 */
class EventfulColorScheme
{

	/**
	 * Advanced setting section.
	 *
	 * @param string $prefix The settings.
	 * @return void
	 */
	public static function section($prefix)
	{
		Eventful::createSection(
			$prefix,
			array(
				'title'  => esc_html__('Color Scheme', 'eventful'),
				'icon'   => 'icofont-ui-theme',
				'fields' => array(
					array(
						'type'    => 'subheading',
						'content' => esc_html__('Filter Bar', 'eventful'),
					),
					array(
						'id'         => 'filter_bar_button_color',
						'type'       => 'color_group',
						'title'      => esc_html__('Filter Bar Button Colors', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Customize the text, border, and background colors for filter bar buttons, including hover and active states.', 'eventful') . '</div> <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'docs/color-scheme/#filter-bar') . '">' . esc_html__('Open Docs', 'eventful') . '</a>',
						'options'  => array(
							'text_color'        => esc_html__('Text', 'eventful'),
							'text_acolor'       => esc_html__('Text Active', 'eventful'),
							'border_color'      => esc_html__('Border', 'eventful'),
							'border_acolor'     => esc_html__('Border Active', 'eventful'),
							'background'        => esc_html__('Background', 'eventful'),
							'active_background' => esc_html__('Active Background', 'eventful'),
						),
						'default'  => array(
							'text_color'        => '#5e5e5e',
							'text_acolor'       => '#ffffff',
							'border_color'      => '#bbbbbb',
							'border_acolor'     => '#222222',
							'background'        => '#ffffff',
							'active_background' => '#222222',
						),
					),
					array(
						'id'       => 'g_show_hide_filter_button',
						'type'     => 'color_group',
						'title'      => esc_html__('Show / Hide Filter Button Colors', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Set color scheme for the Show/Hide filter toggle button.', 'eventful') . '</div>',
						'options'  => array(
							'text'       => esc_html__('Color', 'eventful'),
							'hover_text' => esc_html__('Hover Color', 'eventful'),
							'color'       => esc_html__('Background', 'eventful'),
							'hover_color' => esc_html__('Hover Background', 'eventful'),
						),
						'default' => array(
							'text'       => '#ffffff',
							'hover_text' => '#ffffff',
							'color'       => '#222222',
							'hover_color' => '#222222',
						),
					),
					array(
						'type'    => 'subheading',
						'content' => esc_html__('Content Fields', 'eventful'),
					),
					array(
						'id'         => 'read_more_button_color',
						'type'       => 'color_group',
						'title'      => esc_html__('Read More Button Colors', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Control text, background, and border colors for the Read More button in normal and hover states for Read more button.', 'eventful') . '</div> <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'docs/color-scheme/#content-fields') . '">' . esc_html__('Open Docs', 'eventful') . '</a>',
						'options' => array(
							'color'   => esc_html__('Color', 'eventful'),
							'hover_color'   => esc_html__('Hover Color', 'eventful'),
							'background'   => esc_html__('Background', 'eventful'),
							'hover_background' => esc_html__('Hover Background', 'eventful'),
							'border' => esc_html__('Border Color', 'eventful'),
							'hover_border' => esc_html__('Hover Border Color', 'eventful'),
						),
						'default'   => array(
							'color' => '#111111',
							'hover_color' => '#ffffff',
							'background' => '',
							'hover_background' => '#111111',
							'border' => '#888888',
							'hover_border' => '#222222',
						),
					),
					array(
						'id'         => 'read_more_button_link_color',
						'type'       => 'color_group',
						'title'      => esc_html__('Read More Link Colors', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Set the text color and hover color for Read More links.', 'eventful') . '</div>',
						'options' => array(
							'color'   => esc_html__('Color', 'eventful'),
							'hover_color'   => esc_html__('Hover Color', 'eventful'),
						),
						'default'   => array(
							'color' => '#222222',
							'hover_color' => '#222222',
						),
					),
					array(
						'id'      => 'g_social_icon_color',
						'class'      => 'pro_only',
						'type'    => 'color_group',
						'title'      => esc_html__('Social Icon Colors', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Set icon and background colors for social media buttons, including hover states.', 'eventful') . '</div>',
						'options' => array(
							'icon_color'       => esc_html__('Color', 'eventful'),
							'icon_hover_color' => esc_html__('Hover Color', 'eventful'),
							'icon_bg'          => esc_html__('Background', 'eventful'),
							'icon_bg_hover'    => esc_html__('Hover Background', 'eventful'),
						),
						'default' => array(
							'icon_color'       => '#ffffff',
							'icon_hover_color' => '#ffffff',
							'icon_bg'          => '#222222',
							'icon_bg_hover'    => '#263ad0',
						),
					),
					array(
						'id'      => 'social_icon_border_color',
						'class'      => 'pro_only',
						'type'    => 'color_group',
						'title'      => esc_html__('Social Icon Border Colors', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Define the border color and hover border color for social media icons.', 'eventful') . '</div>',
						'options'    => array(
							'color'        => esc_html__('Color', 'eventful'),
							'hover_color'       => esc_html__('Hover Color', 'eventful'),
						),
						'default' => array(
							'color' => '#222222',
							'hover_color' => '#222222',
						),
					),
					array(
						'type'    => 'subheading',
						'content' => esc_html__('Pagination', 'eventful'),
					),
					array(
						'id'         => 'g_pagination_color',
						'class'      => 'pro_only',
						'type'       => 'color_group',
						'title'      => esc_html__('Pagination Colors', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Customize text, border, and background colors for pagination, including active states.', 'eventful') . '</div> <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'docs/color-scheme/#pagination') . '">' . esc_html__('Open Docs', 'eventful') . '</a>',
						'options'    => array(
							'text_color'        => esc_html__('Text Color', 'eventful'),
							'text_acolor'       => esc_html__('Text Active Color', 'eventful'),
							'border_color'      => esc_html__('Border Color', 'eventful'),
							'border_acolor'     => esc_html__('Border Active Color', 'eventful'),
							'background'        => esc_html__('Background', 'eventful'),
							'active_background' => esc_html__('Active BG', 'eventful'),
						),
						'default'    => array(
							'text_color'        => '#5e5e5e',
							'text_acolor'       => '#ffffff',
							'border_color'      => '#bbbbbb',
							'border_acolor'     => '#222222',
							'background'        => '#ffffff',
							'active_background' => '#222222',
						),
					),
					array(
						'id'         => 'load_more_button_color',
						'type'       => 'color_group',
						'title'      => esc_html__('Load More Button Colors', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Customize text, border, and background colors for the Load More button, including hover effects.', 'eventful') . '</div>',
						'options'    => array(
							'text_color'        => esc_html__('Text Color', 'eventful'),
							'text_hover'       => esc_html__('Text Hover Color', 'eventful'),
							'border_color'      => esc_html__('Border Color', 'eventful'),
							'border_hover'     => esc_html__('Border Hover Color', 'eventful'),
							'background'        => esc_html__('Background', 'eventful'),
							'active_background' => esc_html__('Hover Background', 'eventful'),
							'preloader_color' => esc_html__('Preloader Color', 'eventful'),
						),
						'default'    => array(
							'text_color'        => '#5e5e5e',
							'text_hover'       => '#ffffff',
							'border_color'      => '#bbbbbb',
							'border_hover'     => '#222222',
							'background'        => '#ffffff',
							'active_background' => '#222222',
							'preloader_color' => '#222222',
						),
					),
					array(
						'type'    => 'subheading',
						'content' => esc_html__('Pre Made Theme', 'eventful'),
					),
					array(
						'id'       => 'pre_made_theme_color_scheme',
						'type'     => 'color_group',
						'title'      => esc_html__('Theme Color Scheme', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Set primary colors for featured and non-featured event layouts.', 'eventful') . '</div> <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'docs/color-scheme/#pre-made-theme') . '">' . esc_html__('Open Docs', 'eventful') . '</a>',
						'options'  => array(
							'featured' => esc_html__('Featured', 'eventful'),
							'non_featured'    => esc_html__('Non Featured', 'eventful'),
						),
						'default' => array(
							'featured' => '#222222',
							'non_featured'    => '#222222',
						),
					),
					array(
						'type'    => 'subheading',
						'content' => esc_html__('Carousel', 'eventful'),
					),
					array(
						'id'         => 'carousel_preloader_color',
						'type'       => 'color',
						'title'      => esc_html__('Carousel Lazy Preloader Color', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Select the color of the loading spinner shown while carousel slides load.', 'eventful') . '</div> <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'docs/color-scheme/#carousel') . '">' . esc_html__('Open Docs', 'eventful') . '</a>',
						'default'    => '#222222',
					),
					array(
						'id'         => 'carousel_navigation_colors',
						'type'       => 'color_group',
						'title'      => esc_html__('Carousel Navigation Colors', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Set icon, background, and border colors for carousel navigation arrows, including hover states.', 'eventful') . '</div>',
						'options'    => array(
							'color'              => esc_html__('Color', 'eventful'),
							'hover-color'        => esc_html__('Hover Color', 'eventful'),
							'bg'                 => esc_html__('Background', 'eventful'),
							'hover-bg'           => esc_html__('Hover Background', 'eventful'),
							'border-color'       => esc_html__('Border', 'eventful'),
							'hover-border-color' => esc_html__('Hover Border', 'eventful'),
						),
						'default'    => array(
							'color'              => '#aaa',
							'hover-color'        => '#fff',
							'bg'                 => '#fff',
							'hover-bg'           => '#222222',
							'border-color'       => '#aaa',
							'hover-border-color' => '#222222',
						),
					),
					array(
						'id'         => 'carousel_pagination_color',
						'title'      => esc_html__('Carousel Pagination Colors', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Control the normal and active colors for carousel pagination dots.', 'eventful') . '</div>',
						'type'       => 'color_group',
						'options'    => array(
							'color'        => esc_html__('Color', 'eventful'),
							'active-color' => esc_html__('Active Color', 'eventful'),
						),
						'default'    => array(
							'color'        => '#cccccc',
							'active-color' => '#222222',
						),
					),
					array(
						'id'         => 'carousel_number_pagination_color',
						'class'         => 'pro_only',
						'title'      => esc_html__('Carousel Number Pagination Colors', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Set text and background colors for numbered carousel pagination, including hover styles.', 'eventful') . '</div>',
						'type'       => 'color_group',
						'options'    => array(
							'color'       => esc_html__('Color', 'eventful'),
							'hover-color' => esc_html__('Hover Color', 'eventful'),
							'bg'          => esc_html__('Background', 'eventful'),
							'hover-bg'    => esc_html__('Hover Background', 'eventful'),
						),
						'default'    => array(
							'color'       => '#ffffff',
							'hover-color' => '#ffffff',
							'bg'          => '#cccccc',
							'hover-bg'    => '#222222',
						),
					),
					array(
						'type'    => 'subheading',
						'content' => esc_html__('Timeline', 'eventful'),
					),
					array(
						'id'       => 'timeline_colors',
						'type'     => 'color_group',
						'title'      => esc_html__('Timeline Colors', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Customize the colors used in the timeline view, including the date badge, timeline line, and event dot to match your design.', 'eventful') . '</div> <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'docs/color-scheme/#single-event-popup') . '">' . esc_html__('Open Docs', 'eventful') . '</a>',
						'options'  => array(
							'date_badge'       => esc_html__('Date Badge', 'eventful'),
							'timeline_line'        => esc_html__('Timeline Line', 'eventful'),
							'event_dot'  => esc_html__('Event Dot', 'eventful'),
						),
						'default'  => array(
							'date_badge'       => '#1e1e2f',
							'timeline_line'        => '#1e1e2f',
							'event_dot'  => '#1e1e2f',
						),
					),
					array(
						'type'    => 'subheading',
						'content' => esc_html__('Single Event Popup', 'eventful'),
					),
					array(
						'id'       => 'g_popup_content_color',
						'class'       => 'pro_only',
						'type'     => 'color_group',
						'title'      => esc_html__('Popup Content Text Colors', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Define individual text colors for popup title, meta, and content fields.', 'eventful') . '</div> <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'docs/color-scheme/#single-event-popup') . '">' . esc_html__('Open Docs', 'eventful') . '</a>',
						'options'  => array(
							'event_title'       => esc_html__('Title', 'eventful'),
							'event_meta'        => esc_html__('Meta', 'eventful'),
							'event_meta_hover'  => esc_html__('Meta Hover', 'eventful'),
							'event_content'     => esc_html__('Content', 'eventful'),
						),
						'default'  => array(
							'event_title'       => '#111111',
							'event_meta'        => '#111111',
							'event_meta_hover'  => '#0015b5',
							'event_content'     => '#444',
						),
					),
					array(
						'id'       => 'g_popup_bg_color',
						'class'       => 'pro_only',
						'type'		=> 'color',
						'title'      => esc_html__('Popup Background Color', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Set the background color of the popup window.', 'eventful') . '</div>',
						'default'  => '#fff',
					),
					array(
						'id'       => 'g_popup_overlay_color',
						'class'       => 'pro_only',
						'type'     => 'color',
						'title'      => esc_html__('Popup Overlay Color', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Choose the overlay color and transparency behind the popup.', 'eventful') . '</div>',
						'default'  => 'rgba(11,11,11,0.8)',
					),
					array(
						'id'       => 'g_popup_close_button_color',
						'class'       => 'pro_only',
						'type'     => 'color_group',
						'title'      => esc_html__('Popup Close Button Colors', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Set the normal and hover colors for the popup close icon.', 'eventful') . '</div>',
						'sanitize' => 'spf_eventful_sanitize_color_group_field',
						'options'  => array(
							'color'       => esc_html__('Color', 'eventful'),
							'hover_color' => esc_html__('Hover Color', 'eventful'),
						),
						'default'  => array(
							'color'       => '#111',
							'hover_color' => '#111',
						),
					),
					array(
						'id'       => 'g_popup_nav_color',
						'class'       => 'pro_only',
						'type'     => 'color_group',
						'title'      => esc_html__('Popup Navigation Colors', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Customize the text and background colors for popup navigation controls.', 'eventful') . '</div>',
						'sanitize' => 'spf_eventful_sanitize_color_group_field',
						'options'  => array(
							'color'       => esc_html__('Color', 'eventful'),
							'hover_color' => esc_html__('Hover Color', 'eventful'),
							'bg'          => esc_html__('Background', 'eventful'),
							'hover_bg'    => esc_html__('Hover Background', 'eventful'),
						),
						'default'  => array(
							'color'       => '#ffffff',
							'hover_color' => '#ffffff',
							'bg'          => 'rgba(0,0,0,0.5)',
							'hover_bg'    => 'rgba(0,0,0,0.6)',
						),
					),
					array(
						'type'    => 'subheading',
						'content' => esc_html__('Typography', 'eventful'),
					),
					array(
						'id'       => 'section_title_color',
						'type'     => 'color_group',
						'title'      => esc_html__('Section Title Color', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Set the text color for section headings.', 'eventful') . '</div> <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'docs/color-scheme/#typography') . '">' . esc_html__('Open Docs', 'eventful') . '</a>',
						'sanitize' => 'spf_eventful_sanitize_color_group_field',
						'options'  => array(
							'color'       => esc_html__('Color', 'eventful'),
						),
						'default'  => array(
							'color'       => '#444',
						),
					),
					array(
						'id'       => 'event_title_color',
						'type'     => 'color_group',
						'title'      => esc_html__('Event Title Colors', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Set normal and hover colors for event titles.', 'eventful') . '</div>',
						'sanitize' => 'spf_eventful_sanitize_color_group_field',
						'options'  => array(
							'color'       => esc_html__('Color', 'eventful'),
							'hover_color'       => esc_html__('Hover Color', 'eventful'),
						),
						'default'  => array(
							'color'       => '#111111',
							'hover_color'       => '#0015b5',
						),
					),
					array(
						'id'       => 'event_meta_field_color',
						'type'     => 'color_group',
						'title'      => esc_html__('Event Meta Field Colors', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Set normal and hover colors for event meta information.', 'eventful') . '</div>',
						'sanitize' => 'spf_eventful_sanitize_color_group_field',
						'options'  => array(
							'color'       => esc_html__('Color', 'eventful'),
							'hover_color'       => esc_html__('Hover Color', 'eventful'),
						),
						'default'  => array(
							'color'       => '#111111',
							'hover_color'       => '#0015b5',
						),
					),
					array(
						'id'       => 'event_content_color',
						'type'     => 'color_group',
						'title'    => esc_html__('Event Content Color', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Set the main text color for event descriptions.', 'eventful') . '</div>',
						'sanitize' => 'spf_eventful_sanitize_color_group_field',
						'options'  => array(
							'color'       => esc_html__('Color', 'eventful'),
						),
						'default'  => array(
							'color'       => '#111111',
						),
					),
					array(
						'id'       => 'event_custom_fields',
						'class'       => 'pro_only',
						'type'     => 'color_group',
						'title'      => esc_html__('Event Custom Field Color', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Set the text color for custom event fields.', 'eventful') . '</div>',
						'sanitize' => 'spf_eventful_sanitize_color_group_field',
						'options'  => array(
							'color'       => esc_html__('Color', 'eventful'),
						),
						'default'  => array(
							'color'       => '#111111',
						),
					),
				)
			)
		);
	}
}
