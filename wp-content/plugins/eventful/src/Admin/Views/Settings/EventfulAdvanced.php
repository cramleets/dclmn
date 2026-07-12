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
class EventfulAdvanced
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
				'title'  => esc_html__('Advanced', 'eventful'),
				'icon'   => 'icofont-code-alt',
				'fields' => array(
					array(
						'id'         => 'clean_up_data',
						'type'       => 'checkbox',
						'title'      => esc_html__('Clean-up Data on Deletion', 'eventful'),
						'title_help' => '<div class="eventful-info-desc">' . esc_html__('Delete all Eventful data from the database when the plugin is uninstalled or deleted.', 'eventful') . '</div> <a class="tooltip_btn_primary" target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'docs/advanced/?ref=1') . '">' . esc_html__('Open Docs', 'eventful') . '</a>',
					),
					array(
						'type'    => 'subheading',
						'content' => esc_html__('Enqueue or Dequeue JS', 'eventful'),
					),
					array(
						'id'         => 'eventful_swiper_js',
						'type'       => 'switcher',
						'title'      => esc_html__('Swiper JS', 'eventful'),
						'text_on'    => esc_html__('Enqueued', 'eventful'),
						'text_off'   => esc_html__('Dequeued', 'eventful'),
						'text_width' => 110,
						'default'    => true,
					),
					array(
						'type'    => 'subheading',
						'content' => esc_html__('Enqueue or Dequeue CSS', 'eventful'),
					),
					array(
						'id'         => 'eventful_swiper_css',
						'type'       => 'switcher',
						'title'      => esc_html__('Swiper CSS', 'eventful'),
						'text_on'    => esc_html__('Enqueued', 'eventful'),
						'text_off'   => esc_html__('Dequeued', 'eventful'),
						'text_width' => 110,
						'default'    => true,
					),
					array(
						'id'         => 'eventful_icofont_css',
						'type'       => 'switcher',
						'title'      => esc_html__('IcoFont', 'eventful'),
						'text_on'    => esc_html__('Enqueued', 'eventful'),
						'text_off'   => esc_html__('Dequeued', 'eventful'),
						'text_width' => 110,
						'default'    => true,
					),
				)
			)
		);
	}
}
