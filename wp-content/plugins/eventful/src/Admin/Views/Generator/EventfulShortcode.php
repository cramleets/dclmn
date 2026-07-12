<?php

namespace ThemeAtelier\Eventful\Admin\Views\Generator;

use ThemeAtelier\Eventful\Admin\Framework\Classes\Eventful;

if (!defined('ABSPATH')) {
	die;
} // Cannot access directly.

/**
 * The Shortcode display class.
 */
class EventfulShortcode
{

	/**
	 * Shortcode display metabox section.
	 *
	 * @param string $prefix The metabox key.
	 * @return void
	 */
	public static function section($prefix)
	{
		if (isset($_GET['post'])) {
			Eventful::createSection(
				$prefix,
				array(
					'fields' => array(
						array(
							'type'  => 'shortcode',
							'shortcode' => 'manage_view',
							'class' => 'eventful-admin-sidebar',
						),
					),
				)
			);
		}
	}
}
