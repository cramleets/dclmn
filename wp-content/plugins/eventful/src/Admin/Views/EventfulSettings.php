<?php

/**
 * The main class for Settings configurations.
 *
 * @package Eventful
 * @subpackage Eventful/admin/views
 */

namespace ThemeAtelier\Eventful\Admin\Views;

use ThemeAtelier\Eventful\Admin\Framework\Classes\Eventful;
use ThemeAtelier\Eventful\Admin\Views\Settings\EventfulAdvanced;
use ThemeAtelier\Eventful\Admin\Views\Settings\EventfulAccessibility;
use ThemeAtelier\Eventful\Admin\Views\Settings\EventfulColorScheme;
use ThemeAtelier\Eventful\Admin\Views\Settings\EventfulCustomCSS;
use ThemeAtelier\Eventful\Frontend\Helpers\EventfulFunctions;

if (! defined('ABSPATH')) {
	die;
} // Cannot access pages directly.

/**
 * Settings.
 */
class EventfulSettings
{

	/**
	 * Create a settings page.
	 *
	 * @param string $prefix The settings.
	 * @return void
	 */
	public static function settings($prefix)
	{

		$capability = EventfulFunctions::eventful_dashboard_capability(); // TODO: filter is not working.
		Eventful::createOptions(
			$prefix,
			array(
				'menu_title'       => esc_html__('Settings', 'eventful'),
				'menu_type'        => 'submenu', // menu, submenu, options, theme, etc.
				'menu_slug'        => 'eventful-settings',
				'theme'            => 'light',
				'show_all_options' => false,
				'show_search'      => false,
				'show_footer'      => false,
				'show_bar_menu'    => false,
				'class'            => 'eventful-settings',
				'framework_title'  => esc_html__('Eventful', 'eventful'),
				'menu_capability'  => $capability,
			)
		);
		EventfulColorScheme::section($prefix);
		EventfulCustomCSS::section($prefix);
		EventfulAdvanced::section($prefix);
		EventfulAccessibility::section($prefix);
		// License::section($prefix);
	}
}
