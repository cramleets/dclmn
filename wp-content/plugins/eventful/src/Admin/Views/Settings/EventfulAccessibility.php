<?php

namespace ThemeAtelier\Eventful\Admin\Views\Settings;

use ThemeAtelier\Eventful\Admin\Framework\Classes\Eventful;

if (!defined('ABSPATH')) {
	die;
} // Cannot access directly.

/**
 * The accessibility setting class.
 */
class EventfulAccessibility
{
	/**
	 * Accessibility setting section.
	 *
	 * @param string $prefix The settings.
	 * @return void
	 */
	public static function section($prefix)
	{
		Eventful::createSection(
			$prefix,
			array(
				'title'  => esc_html__('Accessibility', 'eventful'),
				'icon'   => 'icofont-transparent',
				'fields' => array(
					array(
						'id'         => 'accessibility',
						'type'       => 'switcher',
						'title'      => esc_html__('Carousel Accessibility', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Enable accessibility support for carousel navigation and screen readers.', 'eventful') . '</div> <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'docs/accessibility/?ref=1') . '">' . esc_html__('Open Docs', 'eventful') . '</a>',
						'text_on'    => esc_html__('Enabled', 'eventful'),
						'text_off'   => esc_html__('Disabled', 'eventful'),
						'text_width' => 100,
						'default'    => true,
					),
					array(
						'id'         => 'prev_slide_message',
						'type'       => 'text',
						'title'      => esc_html__('Previous Slide Message', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Accessible label announced when navigating to the previous slide.', 'eventful') . '</div>',
						'default'    => esc_html__('Previous slide', 'eventful'),
						'dependency' => array('accessibility', '==', 'true'),
					),
					array(
						'id'         => 'next_slide_message',
						'type'       => 'text',
						'title'      => esc_html__('Next Slide Message', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Accessible label announced when navigating to the next slide.', 'eventful') . '</div>',
						'default'    => esc_html__('Next slide', 'eventful'),
						'dependency' => array('accessibility', '==', 'true'),
					),
					array(
						'id'         => 'first_slide_message',
						'type'       => 'text',
						'title'      => esc_html__('First Slide Message', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Message announced when the first slide is reached.', 'eventful') . '</div>',
						'default'    => esc_html__('This is the first slide', 'eventful'),
						'dependency' => array('accessibility', '==', 'true'),
					),
					array(
						'id'         => 'last_slide_message',
						'type'       => 'text',
						'title'      => esc_html__('Last Slide Message', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Message announced when the last slide is reached.', 'eventful') . '</div>',
						'default'    => esc_html__('This is the last slide', 'eventful'),
						'dependency' => array('accessibility', '==', 'true'),
					),
					array(
						'id'         => 'pagination_bullet_message',
						'type'       => 'text',
						'title'      => esc_html__('Pagination Bullet Message', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Accessible text announced for each pagination bullet. {{index}} will be replaced by slide number.', 'eventful') . '</div>',
						'default'    => esc_html__('Go to slide {{index}}', 'eventful'),
						'dependency' => array('accessibility', '==', 'true'),
					),
				),
			)
		);
	}
}
