<?php

namespace ThemeAtelier\Eventful\Admin\Views\Generator;

use ThemeAtelier\Eventful\Admin\Framework\Classes\Eventful;

if (!defined('ABSPATH')) {
	die;
} // Cannot access directly.

/**
 * The Timeline building class.
 */
class EventfulTimeline
{
	/**
	 * Timeline section metabox.
	 *
	 * @param string $prefix The metabox key.
	 * @return void
	 */
	public static function section($prefix)
	{
		Eventful::createSection(
			$prefix,
			array(
				'title'  => esc_html__('Timeline Controls', 'eventful'),
				'icon'   => 'icofont-settings',
				'class'  => 'timeline_controls',
				'fields' => array(
					array(
						'id'       => 'vertical_timeline_style',
						'type'     => 'layout_preset',
						'title'    => esc_html__('Vertical Timeline Style', 'eventful'),
						'subtitle' => esc_html__('Choose a design style for the vertical timeline layout.', 'eventful'),
						'options'  => array(
							'style_01' => array(
								'image'           => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/timeline.svg',
								'text'            => esc_html__('Style 01', 'eventful'),
								'option_demo_url' => EVENTFUL_DEMO_URL . 'vertical-timeline/#vertical_style_01',
							),
							'style_02' => array(
								'image'           => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/vertical-timeline-2.svg',
								'text'            => esc_html__('Style 02', 'eventful'),
								'option_demo_url' => EVENTFUL_DEMO_URL . 'vertical-timeline/#vertical_style_02',
								'pro_only'        => true,
							),
							'style_03' => array(
								'image'           => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/vertical-timeline-3.svg',
								'text'            => esc_html__('Style 03', 'eventful'),
								'option_demo_url' => EVENTFUL_DEMO_URL . 'vertical-timeline/#vertical_style_03',
								'pro_only'        => true,
							),
							'style_04' => array(
								'image'           => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/vertical-timeline-4.svg',
								'text'            => esc_html__('Style 04', 'eventful'),
								'option_demo_url' => EVENTFUL_DEMO_URL . 'vertical-timeline/#vertical_style_04',
								'pro_only'        => true,
							),
							'style_05' => array(
								'image'           => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/vertical-timeline-5.svg',
								'text'            => esc_html__('Style 05', 'eventful'),
								'option_demo_url' => EVENTFUL_DEMO_URL . 'vertical-timeline/#vertical_style_05',
								'pro_only'        => true,
							),
						),
						'default'    => 'style_01',
						'dependency' => array('eventful_layout_preset', 'any', 'timeline', true),
					),
					array(
						'id'         => 'timeline_show_month_separator',
						'type'       => 'switcher',
						'title'      => esc_html__('Month/Year Separator', 'eventful'),
						'subtitle'   => esc_html__('Show a month and year label on the timeline line between event groups.', 'eventful'),
						'default'    => false,
						'text_on'    => esc_html__('Enabled', 'eventful'),
						'text_off'   => esc_html__('Disabled', 'eventful'),
						'text_width' => 94,
						'dependency' => array('eventful_layout_preset', 'any', 'timeline', true),
					),
					array(
						'type'       => 'subheading',
						'content'    => esc_html__('Timeline Colors', 'eventful'),
						'dependency' => array('eventful_layout_preset', 'any', 'timeline', true),
					),
					array(
						'id'         => 'date_badge_color',
						'type'       => 'color',
						'title'      => esc_html__('Date Badge Color', 'eventful'),
						'subtitle'   => esc_html__('Background color for the month and year badge (for example, "Jun 2026") shown above each event group.', 'eventful'),
						'dependency' => array('eventful_layout_preset|timeline_show_month_separator', 'any|==', 'timeline|true', true),
					),
					array(
						'id'         => 'timeline_line_thickness',
						'type'       => 'spacing',
						'title'      => esc_html__('Timeline Line Thickness', 'eventful'),
						'subtitle'   => esc_html__('Controls the thickness of the timeline line.', 'eventful'),
						'units'      => array('px'),
						'all'        => true,
						'default'    => array(
							'all' => '5',
						),
						'dependency' => array('eventful_layout_preset', 'any', 'timeline', true),
					),
					array(
						'id'         => 'timeline_line_color',
						'type'       => 'color',
						'title'      => esc_html__('Timeline Line Color', 'eventful'),
						'subtitle'   => esc_html__('Color of the vertical line that runs through the timeline and connects every event.', 'eventful'),
						'dependency' => array('eventful_layout_preset', 'any', 'timeline', true),
					),
					array(
						'id'         => 'event_dot_color',
						'type'       => 'color',
						'title'      => esc_html__('Event Dot Color', 'eventful'),
						'subtitle'   => esc_html__('Color of the round dot marker shown next to each event on the timeline line.', 'eventful'),
						'dependency' => array('eventful_layout_preset', 'any', 'timeline', true),
					),
				),
			)
		);
	}
}
