<?php

namespace ThemeAtelier\Eventful\Admin\Views\Settings;

use ThemeAtelier\Eventful\Admin\Framework\Classes\Eventful;

if (!defined('ABSPATH')) {
	die;
} // Cannot access directly.

class EventfulCustomCSS
{

	/**
	 * Custom CSS & JS settings.
	 *
	 * @param string $prefix The settings.
	 * @return void
	 */
	public static function section($prefix)
	{
		Eventful::createSection(
			$prefix,
			array(
				'title'  => esc_html__('Custom CSS & JS', 'eventful'),
				'icon'   => 'icofont-file-css',
				'fields' => array(
					array(
						'id'       => 'eventful_custom_css',
						'type'     => 'code_editor',
						'title'    => esc_html__('Custom CSS', 'eventful'),
						'settings' => array(
							'icon'  => 'icofont-edit',
							'theme' => 'mbo',
							'mode'  => 'css',
						),
					),
					array(
						'id'       => 'eventful_custom_js',
						'type'     => 'code_editor',
						'title'    => esc_html__('Custom JavaScript', 'eventful'),
						'settings' => array(
							'icon'  => 'icofont-edit',
							'theme' => 'monokai',
							'mode'  => 'javascript',
						),
					),
				),
			)
		);
	}
}
