<?php

namespace ThemeAtelier\Eventful\Admin\Views\Generator;

use ThemeAtelier\Eventful\Admin\Framework\Classes\Eventful;

if (!defined('ABSPATH')) {
	die;
} // Cannot access directly.

/**
 * The Popup settings class.
 */
class EventfulDetailSettings
{

	/**
	 * Popup settings section metabox.
	 *
	 * @param string $prefix The metabox key.
	 * @return void
	 */
	public static function section($prefix)
	{
		Eventful::createSection(
			$prefix,
			array(
				'title'  => esc_html__('Detail Page Settings', 'eventful'),
				'icon'   => 'icofont-external-link',
				'fields' => array(
					array(
						'id'       => 'eventful_page_link_type',
						'type'     => 'radio',
						'title'    => esc_html__('Detail Page Link Type', 'eventful'),
						'subtitle' => esc_html__('Choose a link type for the (item) detail page.', 'eventful'),
						'options'  => array(
							'single_page' => esc_html__('Single Page', 'eventful'),
							'none'        => esc_html__('None (no link action)', 'eventful'),
							'popup' 	  => esc_html__('Popup (Pro)', 'eventful'),
						),
						'default'  => 'single_page',
					),
					array(
						'id'         => 'eventful_link_target',
						'type'       => 'radio',
						'title'      => esc_html__('Target', 'eventful'),
						'subtitle' => 	esc_html__('Set a target for the item link.', 'eventful'),
						'options'    => array(
							'_self'   => esc_html__('Current Tab', 'eventful'),
							'_blank'  => esc_html__('New Tab', 'eventful'),
							'_parent' => esc_html__('Parent', 'eventful'),
							'_top'    => esc_html__('Top', 'eventful'),
						),
						'default'    => '_self',
						'dependency' => array('eventful_page_link_type', '==', 'single_page'),
					),
					array(
						'id'      => 'eventful_link_rel',
						'type'    => 'checkbox',
						'title'      => esc_html__('Add rel="nofollow" to item links', 'eventful'),
						'subtitle' => esc_html__('Check this to add rel="nofollow" to event links.', 'eventful'),
						'default' => 'false',
						'dependency' => array('eventful_page_link_type', '==', 'single_page'),
					),
					array(
						 'type'    => 'notice',
						 'style'   => 'info',
						 'content' => __('Unlock advanced popup settings such as Single & Multiple Popups, Show/Hide draggable popup fields, custom width & height, color controls, and many more customization options by <a target="_blank" href="' . esc_url(EVENTFUL_DEMO_URL . 'pricing/') . '"><b><u>upgrading to Pro</u></b></a>. ', 'eventful'),
						 'class'  => 'eventful-info-box',
					),
				), // End of fields array.
			)
		); // Display settings section end.
	}
}
