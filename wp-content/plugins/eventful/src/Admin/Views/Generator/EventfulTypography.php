<?php

namespace ThemeAtelier\Eventful\Admin\Views\Generator;

use ThemeAtelier\Eventful\Admin\Framework\Classes\Eventful;

if (!defined('ABSPATH')) {
	die;
} // Cannot access directly.

/**
 * The Typography class.
 */
class EventfulTypography
{

	/**
	 * Typography section metabox.
	 *
	 * @param string $prefix The metabox key.
	 * @return void
	 */
	public static function section($prefix)
	{
		Eventful::createSection(
			$prefix,
			array(
				'title'           => esc_html__('Typography', 'eventful'),
				'icon'            => 'icofont-font',
				'enqueue_webfont' => true,
				'fields'          => array(
					array(
						 'type'    => 'notice',
						 'style'   => 'info',
						 'content' => __('In this section, you can customize the typography settings for various elements of your event showcase. Check <a target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'custom-typography-and-styling/') . '"><b>Demo →</b></a> and <a target="_blank" href="'. esc_url(EVENTFUL_DEMO_URL . 'demo/typography/') . '"><b>Docs →</b></a> for guidance on using these typography options effectively.', 'eventful'),
						 'class'  => 'eventful-info-box',
					),
					array(
						'id'         => 'section_title_typography',
						'type'       => 'typography',
						'title'      => esc_html__('Section Title', 'eventful'),
						'subtitle'   => esc_html__('Set event showcase section title font properties.', 'eventful'),
						'title_help' => '<div class="eventful-info-label">' . esc_html__('Set typography options for the section title, including font, size, and line height.', 'eventful') . '</div> <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'docs/typography/?ref=1') . '">' . esc_html__('Open Docs', 'eventful') . '</a>',
						'default'    => array(
							'color'              => '',
							'font-family'        => '',
							'font-weight'        => '',
							'subset'             => '',
							'font-size'          => '24',
							'tablet-font-size'   => '15',
							'mobile-font-size'   => '18',
							'line-height'        => '28',
							'tablet-line-height' => '24',
							'mobile-line-height' => '15',
							'letter-spacing'     => '0',
							'text-align'         => 'inherit',
							'text-transform'     => 'none',
							'type'               => '',
							'unit'               => 'px',
						),
						'dependency' => array('section_title', '==', 'true', 'all'),
					),
					array(
						'id'          => 'event_title_typography',
						'type'        => 'typography',
						'title'       => esc_html__('Title', 'eventful'),
						'subtitle'  => esc_html__('Set typography for individual event titles, including hover color.',	 'eventful'),
						'hover_color' => true,
						'default'     => array(
							'color'              => '',
							'hover_color'        => '',
							'font-family'        => '',
							'font-weight'        => '',
							'subset'             => '',
							'font-size'          => '22',
							'tablet-font-size'   => '18',
							'mobile-font-size'   => '16',
							'line-height'        => '24',
							'tablet-line-height' => '22',
							'mobile-line-height' => '15',
							'letter-spacing'     => '0',
							'text-align'         => 'inherit',
							'text-transform'     => 'none',
							'type'               => '',
							'unit'               => 'px',
						),
						'dependency'  => array('show_event_title', '==', 'true', 'all'),
					),
					
					array(
						'id'          => 'event_meta_typography',
						'type'        => 'typography',
						'title'       => esc_html__('Event Meta Fields', 'eventful'),
						'subtitle'  => esc_html__('Set typography for event meta fields, including hover color.', 'eventful'),
						'hover_color' => true,
						'default'     => array(
							'color'              => '',
							'hover_color'        => '',
							'font-family'        => '',
							'font-weight'        => '',
							'subset'             => '',
							'font-size'          => '13',
							'tablet-font-size'   => '13',
							'mobile-font-size'   => '12',
							'line-height'        => '19',
							'tablet-line-height' => '19',
							'mobile-line-height' => '18',
							'letter-spacing'     => '0',
							'text-align'         => 'inherit',
							'text-transform'     => 'none',
							'type'               => '',
							'unit'               => 'px',
						),
						'dependency'  => array('show_event_fildes', '==', 'true', 'all'),
					),
					array(
						'id'         => 'event_content_typography',
						'type'       => 'typography',
						'title'      => esc_html__('Event Content', 'eventful'),
						'subtitle' =>esc_html__('Set typography for the event content block.', 'eventful'),
						'default'    => array(
							'color'              => '',
							'font-family'        => '',
							'font-weight'        => '',
							'subset'             => '',
							'font-size'          => '16',
							'tablet-font-size'   => '14',
							'mobile-font-size'   => '12',
							'line-height'        => '22',
							'tablet-line-height' => '20',
							'mobile-line-height' => '18',
							'letter-spacing'     => '0',
							'text-align'         => 'inherit',
							'text-transform'     => 'none',
							'type'               => '',
							'unit'               => 'px',
						),
						'dependency' => array('show_event_content', '==', 'true', 'all'),
					),
					array(
						'id'         => 'read_more_typography',
						'type'       => 'typography',
						'title'      => esc_html__('Read More', 'eventful'),
						'subtitle' => esc_html__('Set typography for the Read More button or link.', 'eventful'),
						'color'      => false,
						'default'    => array(
							'font-family'        => '',
							'font-weight'        => '600',
							'subset'             => '',
							'font-size'          => '12',
							'tablet-font-size'   => '12',
							'mobile-font-size'   => '10',
							'line-height'        => '18',
							'tablet-line-height' => '18',
							'mobile-line-height' => '16',
							'letter-spacing'     => '0',
							'text-align'         => 'inherit',
							'text-transform'     => 'uppercase',
							'type'               => '',
							'unit'               => 'px',
						),
						'dependency' => array('show_read_more', '==', 'true', 'all'),
					),
				), // End of fields array.
			)
		);
	}
}
